<?php 

use yii\jui\Dialog;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;

use app\modules\directory\directoryModule;
use app\modules\directory\widgets\SingletonRenderHelper;
use app\modules\directory\helpers\ajaxJSONResponseHelper;

$uid = mt_rand(0, mt_getrandmax());

$formModel = new \app\modules\directory\models\forms\HierarchyForm();
$formDirectoryItemModel = new \app\modules\directory\models\forms\DirectoryItemForm;

?>

<?= SingletonRenderHelper::widget(['viewsRequire' => [
    ['name' => '/helpers/ajax-post-helper'],
    ['name' => '/edit/dialogs/select-directory-dialog'],
    ['name' => '/edit/dialogs/edit-directory-dialog']
    ]]) ?>

<div class="directory-hide-element">
    
<?php 
Dialog::begin([
    'id' => 'editHierachyDialog'.$uid,
    'clientOptions' => [
        'modal' => true,
        'autoOpen' => false,
        'resizable' => false,
        'width' => 600
    ],
]); ?>

<?php $form = ActiveForm::begin([
    'id' => 'hierarchy-form'.$uid,
        ]); ?>
    
    
    
<div>
    <table class="directory-modal-table directory-stretch-bar directory-table">
        <tr>
            <td class="directory-min-width directory-table-label">
                <div class="directory-right-padding">
                    <nobr>
                        <span class="directory-form-label-right-padding"><?= Html::activeLabel($formModel, 'name')?><span class="directory-required-input">*</span></span>
                    </nobr>
                </div>
            </td>
            <td>
                <div class="directory-form-item-bottom-padding">
                <?= Html::activeInput('text', $formModel, 'name', ['class'=>'directory-stretch-bar directory-grid-filter-control']) ; ?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="directory-min-width directory-table-label">
                <div class="directory-right-padding">
                    <nobr>
                        <span class="directory-form-label-right-padding"><?= Html::activeLabel($formModel, 'description')?></span>
                    </nobr>
                </div>
            </td>
            <td>
                <div class="directory-form-item-bottom-padding">
                <?= Html::activeTextarea($formModel, 'description', ['class'=>'directory-stretch-bar directory-grid-filter-control', 'rows' => 7]) ; ?>
                </div>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <div class="directory-form-item-bottom-padding">
                    <table class="directory-modal-table directory-stretch-bar directory-table">
                        <tr>
                            <td class="directory-min-width"><?= Html::activeCheckbox($formModel, 'visible', ['label' => null])?></td>
                            <td class="directory-min-width">&nbsp;</td>
                            <td>- <?= Html::activeLabel($formModel, 'visible')?></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="directory-record-data-list">
                    <span class="diretory-record-label"><?= directoryModule::ht('edit', 'Directories')?></span>
                    <div class="directory-record-data-list-border">
                        <table class="directory-stretch-bar">
                            <tr>
                                <td class="directory-min-width">
                                    <div id="addHierarchToDirectory">
                                        <nobr>
                                            <span class="directory-add-button-icon"><?= directoryModule::ht('edit', 'Add directory')?>...</span>
                                        </nobr>
                                    </div>
                                </td>
                                <td class="directory-min-width">&nbsp;</td>
                                <td class="directory-min-width">
                                    <div id="createNewDirectoryForAddHierarchy">
                                        <nobr>
                                            <span class="directory-new-button-icon"><?= directoryModule::ht('edit', 'New')?>...</span>
                                        </nobr>
                                    </div>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                        </table>
                        <div id="directory-record-list" class="directory-record-list"></div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <div>
        <span id="wait<?=$uid?>" class="directory-hide-element">
            <nobr>
                <img src="<?= directoryModule::getPublishImage('/wait.gif')?>">
                <span><?= directoryModule::ht('search', 'processing request')?></span>
            </nobr>
        </span>
        <div id="error<?=$uid?>" class="directory-error-msg directory-hide-element"></div>
        <div id="ok<?=$uid?>" class="directory-ok-msg directory-hide-element"></div>
    </div>
</div>
    
    
<?php ActiveForm::end(); ?>
    
<?php Dialog::end(); ?>

</div>


<div class="directory-hide-element" id="directory-add-template<?=$uid?>">
    <div class="directory-directory-item">
        <table class="directory-modal-table">
            <tr>
                <td>
                    <div class="directory-label">
                        <span><?=$uid.'p2'?></span>
                        <img class="directory-hide-element" src="<?=directoryModule::getPublishImage('/info16.png')?>"/>
                        <div class="directory-hide-element"></div>
                    </div>
                    <div class="directory-hide-element"><?=Html::hiddenInput(Html::getInputName($formDirectoryItemModel, '['.$uid.'p3]directoryId'), $uid.'p4')?></div>
                </td>
                <td>&nbsp;</td>
                <td>
                    <div class="directory-delete-directory directory-small-button" title="<?=directoryModule::ht('edit', 'Edit data type')?>">
                        <img src="<?=directoryModule::getPublishImage('/delete-item.png')?>" />
                    </div>
                </td>
            </tr>
        </table>
        <table class="directory-modal-table directory-table">
            <tr>
                <td class="directory-min-width"><?= Html::activeCheckbox($formDirectoryItemModel, '['.$uid.'p3]visible', ['label' => null])?></td>
                <td class="directory-min-width">&nbsp;</td>
                <td>- <?= Html::activeLabel($formDirectoryItemModel, '['.$uid.'p3]visible')?></td>
            </tr>
        </table>
    </div>
</div>


<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    

    (function($) {
        
        var AddRecordToDirectory = function(dir) {
            if(dir !== undefined) {
                var counter = $("#directory-add-template<?=$uid?>").prop("field-counter<?=$uid?>");
                ++counter;
                var tmpEl = $("#directory-add-template<?=$uid?> .directory-directory-item").clone();
                $("#directory-add-template<?=$uid?>").prop("field-counter<?=$uid?>", counter);
                tmpEl.html(tmpEl.html().replace("<?=$uid?>p2", ((dir.original_name === undefined) ? dir.name : dir.original_name)).replace(new RegExp("<?=$uid?>p3","g"), parseInt(counter)).replace("<?=$uid?>p4", dir.id));
                $("#hierarchy-form<?=$uid?> #directory-record-list").append(tmpEl);
                tmpEl.find(".directory-delete-directory").button({text : false});
                var description = (dir.original_description === undefined) ? dir.description : dir.original_description;
                if((description !== undefined) ? (description.length > 0) : false) {
                    tmpEl.find(".directory-label div").html(description);
                    tmpEl.find(".directory-label").tooltip( {
                        content : function() { return $(tmpEl).find(".directory-label div").html(); },
                        items : "img"
                    });
                    tmpEl.find(".directory-label img").removeClass("directory-hide-element");
                }
            }
        };
        
        $("#hierarchy-form<?=$uid?> #addHierarchToDirectory").button().click(function() {
            $.selectDirectoryDialog({ onSuccess : AddRecordToDirectory });
        });
        
        $("#hierarchy-form<?=$uid?> #createNewDirectoryForAddHierarchy").button().click(function() {
            $.editDirectoryDialog({ type : 'new', onSuccess : AddRecordToDirectory }); 
        });
        
        $("#hierarchy-form<?=$uid?> #directory-record-list").on("click", 
                ".directory-delete-directory", function() {
                    $(this).closest(".directory-directory-item").remove();
        });
        
        $.editHierachyDialog = function(p) {
            if(p !== undefined) {
                if(p.type !== undefined) {
                    $("#directory-add-template<?=$uid?>").prop("field-counter<?=$uid?>", 0);
                    switch(p.type) {
                        case 'new':
                                $("#hierarchy-form<?=$uid?>").trigger('reset');
                                $("#hierarchy-form<?=$uid?> #directory-record-list").html("");
                                $("#editHierachyDialog<?=$uid?>").
                                        dialog("option", "title", "<?= directoryModule::ht('edit', 'Create new hierarchy')?>").
                                        dialog("option", "buttons", 
                                        [
                                            {
                                                text : "<?= directoryModule::ht('edit', 'Create new hierarchy')?>",
                                                click : function() {
                                                    $.ajaxPostHelper({
                                                        url : ("<?=Url::toRoute(['/directory/edit/hierarchies', 'cmd' => 'create'])?>"),
                                                        data : $("#hierarchy-form<?=$uid?>").serialize(),
                                                        waitTag : "#wait<?=$uid?>",
                                                        errorTag : "#error<?=$uid?>",
                                                        errorWaitTimeout : 5,
                                                        onSuccess : function(dataObject) { 
                                                            $("#editHierachyDialog<?=$uid?>").dialog("close");
                                                            if(p.onSuccess !== undefined) {
                                                                if((dataObject !== undefined) &&
                                                                        (dataObject.<?=ajaxJSONResponseHelper::messageField?> !== undefined)) {
                                                                    p.onSuccess(dataObject.<?=ajaxJSONResponseHelper::messageField?>);
                                                                } else {
                                                                   p.onSuccess();
                                                                }
                                                            }
                                                        }
                                                    });
                                                }
                                            },
                                            {
                                                text : "<?= directoryModule::ht('edit', 'Close')?>",
                                                click : function() {$("#editHierachyDialog<?=$uid?>").dialog("close"); }
                                            }
                                        ]).dialog("open");
                            break;
                        case 'edit':
                                $("#hierarchy-form<?=$uid?>").trigger('reset');
                                $("#hierarchy-form<?=$uid?> #directory-record-list").html("");
                                $("#editHierachyDialog<?=$uid?>").
                                        dialog("option", "title", "<?= directoryModule::ht('edit', 'Create new hierarchy')?>").
                                        dialog("option", "buttons", 
                                        [
                                            {
                                                text : "<?= directoryModule::ht('edit', 'Create new hierarchy')?>",
                                                click : function() {
                                                    $.ajaxPostHelper({
                                                        url : ("<?=Url::toRoute(['/directory/edit/hierarchies', 'cmd' => 'create'])?>"),
                                                        data : $("#hierarchy-form<?=$uid?>").serialize(),
                                                        waitTag : "#wait<?=$uid?>",
                                                        errorTag : "#error<?=$uid?>",
                                                        errorWaitTimeout : 5,
                                                        onSuccess : function(dataObject) { 
                                                            $("#editHierachyDialog<?=$uid?>").dialog("close");
                                                            if(p.onSuccess !== undefined) {
                                                                if((dataObject !== undefined) &&
                                                                        (dataObject.<?=ajaxJSONResponseHelper::messageField?> !== undefined)) {
                                                                    p.onSuccess(dataObject.<?=ajaxJSONResponseHelper::messageField?>);
                                                                } else {
                                                                   p.onSuccess();
                                                                }
                                                            }
                                                        }
                                                    });
                                                }
                                            },
                                            {
                                                text : "<?= directoryModule::ht('edit', 'Close')?>",
                                                click : function() {$("#editHierachyDialog<?=$uid?>").dialog("close"); }
                                            }
                                        ]).dialog("open");
                            break;
                        default:
                            if(p.onError !== undefined) {
                               p.onError({message : "<?=directoryModule::ht('edit', 'Error: invalid call parameters.')?>"}); 
                            } else {
                                alert("<?=directoryModule::ht('edit', 'Error: invalid call parameters.')?>");
                            }
                            break;
                    }
                } else {
                    if(p.onError !== undefined) {
                       p.onError({message : "<?=directoryModule::ht('edit', 'Error: invalid call parameters.')?>"}); 
                    } else {
                        alert("<?=directoryModule::ht('edit', 'Error: invalid call parameters.')?>");
                    }
                }
            } else {
                alert("<?=directoryModule::ht('edit', 'Error: invalid call parameters.')?>");
            }
        };
        
        
    })(jQuery);
    
    
<?php $this->registerJs(ob_get_clean(), View::POS_READY); if(false) { ?></script><?php } ?>  
