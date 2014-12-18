<?php 

use yii\jui\Dialog;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

use app\modules\directory\directoryModule;
use app\modules\directory\widgets\SingletonRenderHelper;
use app\modules\directory\helpers\ajaxJSONResponseHelper;

$uid = mt_rand(0, mt_getrandmax());

$formModel = new \app\modules\directory\models\forms\RecordForm;
$formItemModel = new \app\modules\directory\models\forms\RecordDataItemForm;

?>

<?= SingletonRenderHelper::widget(['viewsRequire' => [
    ['name' => '/helpers/ajax-post-helper'],
    ['name' => '/helpers/publish-result-css'],
    //['name' => '/edit/dialogs/edit-type-dialog'],
    ['name' => '/edit/dialogs/edit-data-dialog'],
    ['name' => '/edit/dialogs/select-data-dialog']
    ]]) ?>

<div class="directory-hide-element">
    
<?php 
Dialog::begin([
    'id' => 'editRecordDialog'.$uid,
    'clientOptions' => [
        'modal' => true,
        'autoOpen' => false,
        'resizable' => false,
        'width' => 600
    ],
]); ?>
    
    <?php $form = ActiveForm::begin([
    'id' => 'record-form'.$uid,
        ]); ?>
    
    <table class="directory-modal-table directory-stretch-bar directory-table">
        <tr>
            <td colspan="2">
                <div class="directory-record-data-list">
                    <span class="diretory-record-label"><?= directoryModule::ht('edit', 'Items')?></span>
                    <table>
                        <tr>
                            <td class="directory-min-width">
                                <div id="addDataToRecord">
                                    <nobr>
                                        <span class="directory-add-button-icon"><?= directoryModule::ht('edit', 'Add data item')?>...</span>
                                    </nobr>
                                </div>
                            </td>
                            <td class="directory-min-width">&nbsp;</td>
                            <td class="directory-min-width">
                                <div id="createNewDataToRecord">
                                    <nobr>
                                        <span class="directory-new-button-icon"><?= directoryModule::ht('edit', 'New')?>...</span>
                                    </nobr>
                                </div>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                    <div id="dataArray">
                        <table class="directory-modal-table directory-stretch-bar directory-record-item-form">
                            <thead>
                                <tr>
                                    <td><nobr><?=directoryModule::ht('edit', 'Value')?></nobr></td>
                                    <td><nobr><?=directoryModule::ht('edit', 'Position')?></nobr></td>
                                    <td><nobr><?=directoryModule::ht('edit', 'Sub.')?></nobr></td>
                                    <td>&nbsp;</td>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td class="directory-min-width"><?=Html::activeCheckbox($formModel, 'visible', ['label' => null])?></td>
            <td>&nbsp;-&nbsp;<?= Html::activeLabel($formModel, 'visible')?></td>
        </tr>
    </table>

    <?php ActiveForm::end(); ?>
    
    <span id="waitDlgQuery" class="directory-hide-element">
        <nobr>
            <img src="<?= directoryModule::getPublishPath('/img/wait.gif')?>">
            <span><?= directoryModule::ht('search', 'processing request')?></span>
        </nobr>
    </span>
    <div id="errorDlgQuery" class="directory-error-msg directory-hide-element"></div>
    <div id="okDlgQuery" class="directory-ok-msg directory-hide-element"></div>

<?php Dialog::end(); ?>

</div>

<div class="directory-hide-element" id="data-add-template<?=$uid?>">
    <table>
        <tr>
            <td>
                <?=$uid.'p1'?>
                <?=Html::hiddenInput(Html::getInputName($formItemModel, "[$uid]dataId"), null, ['id' => Html::getInputId($formItemModel, "[$uid]dataId")])?>
            </td>
            <td class="directory-min-width"><?=Html::textInput(Html::getInputName($formItemModel, "[$uid]position"), 0, ['id' => Html::getInputId($formItemModel, "[$uid]position"), 'size' => 8, 'class' => 'directory-stretch-bar'])?></td>
            <td class="directory-min-width"><?=Html::textInput(Html::getInputName($formItemModel, "[$uid]subPosition"), 0, ['id' => Html::getInputId($formItemModel, "[$uid]subPosition"), 'size' => 8, 'class' => 'directory-stretch-bar'])?></td>
            <td class="directory-min-width">
                <div class="directory-edit-type-button directory-small-button" title="<?=directoryModule::ht('edit', 'Edit data type')?>">
                    <img src="<?=directoryModule::getPublishPath('/img/delete-item.png')?>" />
                </div>
            </td>
        </tr>
    </table>
</div>

<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    $("#record-form<?=$uid?> #dataArray table tbody").on("click", ".directory-edit-type-button", function() {
        alert(0);
    });
    
    (function($) {
        
        var addDataToTable = function(data) {
            if(data !== undefined) {
                var tmpEl = $("#data-add-template<?=$uid?> tr").clone();
                tmpEl.find("#<?=Html::getInputId($formItemModel, "[$uid]dataId")?>").val(data.id);
                var counter = $("#data-add-template<?=$uid?>").prop("field-counter<?=$uid?>");
                ++counter;
                $("#data-add-template<?=$uid?>").prop("field-counter<?=$uid?>", counter);
                tmpEl.html(tmpEl.html().replace("<?=$uid?>p1", data.valueDisplay).replace(new RegExp("<?=$uid?>","g"), parseInt(counter)));
                tmpEl.find(".directory-edit-type-button").button();
                $("#record-form<?=$uid?> #dataArray table tbody").append(tmpEl);
            }
        }
        
        $("#record-form<?=$uid?> #addDataToRecord").button().click(function() {
            $.selectDataDialog({
                onSuccess : function(data) {
                    addDataToTable(data);
                }
            });
        });
        
        $("#record-form<?=$uid?> #createNewDataToRecord").button().click(function() {
            $.editDataDialog({
                type : "new",
                return : true,
                onSuccess : function(data) {
                    addDataToTable(data);
                }
            });
        });
        
        $.editRecordDialog = function(p) {
            if(p !== undefined) {
                if(p.type !== undefined) {
                    $("#data-add-template<?=$uid?>").prop("field-counter<?=$uid?>", 0);
                    $("#record-form<?=$uid?> #dataArray table tbody").html("");
                    switch(p.type) {
                        case "new":
                            (function(p) {
                                $("#editRecordDialog<?=$uid?>").
                                        dialog("option", "title", "<?= directoryModule::ht('edit', 'Create a new item')?>").
                                        dialog("option", "buttons", 
                                                        [
                                                            {
                                                                text : "<?= directoryModule::ht('edit', 'Add record')?>",
                                                                click : function() { 
                                                                    $.ajaxPostHelper({
                                                                        url : ("<?=Url::toRoute(['/directory/edit/records', 'cmd' => 'create'])?>"),
                                                                        data : $("#record-form<?=$uid?>").serialize(),
                                                                        waitTag : "#editRecordDialog<?=$uid?> #waitDlgQuery",
                                                                        errorTag : "editRecordDialog<?=$uid?> #errorDlgQuery",
                                                                        errorWaitTimeout : 5,
                                                                        onSuccess : function() { 
                                                                            $("#editRecordDialog<?=$uid?>").dialog("close");
                                                                            if(p.onSuccess !== undefined) {
                                                                                p.onSuccess();
                                                                            }
                                                                        }
                                                                    });
                                                                }
                                                            },
                                                            {
                                                                text : "<?= directoryModule::ht('edit', 'Close')?>",
                                                                click : function() { $("#editRecordDialog<?=$uid?>").dialog("close"); }
                                                            }
                                                        ]
                                        ).
                                        dialog("open");
                            })(p);
                            break;
                        case "edit":
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