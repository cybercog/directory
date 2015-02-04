<?php 

use yii\jui\Dialog;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

use app\modules\directory\directoryModule;
use app\modules\directory\widgets\SingletonRenderHelper;
use app\modules\directory\helpers\dataGridCellViewHelper;
use app\modules\directory\helpers\ajaxJSONResponseHelper;

$uid = mt_rand(0, mt_getrandmax());

$formModel = new \app\modules\directory\models\forms\RecordForm;
$formItemModel = new \app\modules\directory\models\forms\RecordDataItemForm;
$formDirectoryItemModel = new \app\modules\directory\models\forms\DirectoryItemForm;

?>

<?= SingletonRenderHelper::widget(['viewsRequire' => [
    ['name' => '/helpers/ajax-post-helper'],
    ['name' => '/helpers/table-paginator-js'],
    ['name' => '/helpers/publish-result-css'],
    //['name' => '/edit/dialogs/edit-type-dialog'],
    ['name' => '/edit/dialogs/edit-data-dialog'],
    ['name' => '/edit/dialogs/select-data-dialog'],
    ['name' => '/edit/dialogs/select-directory-dialog'],
    ['name' => '/helpers/table-paginator-js'],
    ['name' => '/edit/dialogs/edit-directory-dialog']
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
                    <div class="directory-record-data-list-border">
                        <table class="directory-stretch-bar">
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
                                <td class="directory-min-width">
                                    <span id="prevButton" class="directory-small-button directory-navigation">&larr;</span>
                                </td>
                                <td class="directory-min-width">
                                    <span id="nextButton" class="directory-small-button directory-navigation">&rarr;</span>
                                </td>
                            </tr>
                        </table>
                        <div id="dataArray" class="directory-records-max-height">
                            <table class="directory-modal-table directory-stretch-bar directory-record-item-form">
                                <thead>
                                    <tr>
                                        <td class="directory-no-wrap"><?=$formItemModel->getAttributeLabel('dataId')?></td>
                                        <td class="directory-no-wrap"><?=dataGridCellViewHelper::getVisibleFlagString('Y')?></td>
                                        <td class="directory-no-wrap"><?=$formItemModel->getAttributeLabel('position')?></td>
                                        <td class="directory-no-wrap"><?=$formItemModel->getAttributeLabel('subPosition')?></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
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
                                    <div id="addRecordToDirectory">
                                        <nobr>
                                            <span class="directory-add-button-icon"><?= directoryModule::ht('edit', 'Add directory')?>...</span>
                                        </nobr>
                                    </div>
                                </td>
                                <td class="directory-min-width">&nbsp;</td>
                                <td class="directory-min-width">
                                    <div id="createNewDirectoryForAddRecord">
                                        <nobr>
                                            <span class="directory-new-button-icon"><?= directoryModule::ht('edit', 'New')?>...</span>
                                        </nobr>
                                    </div>
                                </td>
                                <td>&nbsp;</td>
                                <td class="directory-min-width">
                                    <span id="prevButton" class="directory-small-button directory-navigation">&larr;</span>
                                </td>
                                <td class="directory-min-width">
                                    <span id="nextButton" class="directory-small-button directory-navigation">&rarr;</span>
                                </td>
                            </tr>
                        </table>
                        <div id="directory-record-list" class="directory-record-list"></div>
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
            <img src="<?= directoryModule::getPublishImage('/wait.gif')?>">
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
            <td class="directory-min-width">
                <div align="center"><?=Html::checkbox(Html::getInputName($formItemModel, "[$uid]visible"), $formItemModel->visible, ['id' => Html::getInputId($formItemModel, "[$uid]visible")])?></div>
            </td>
            <td class="directory-min-width"><?=Html::textInput(Html::getInputName($formItemModel, "[$uid]position"), 0, ['id' => Html::getInputId($formItemModel, "[$uid]position"), 'size' => 8, 'class' => 'directory-stretch-bar'])?></td>
            <td class="directory-min-width"><?=Html::textInput(Html::getInputName($formItemModel, "[$uid]subPosition"), 0, ['id' => Html::getInputId($formItemModel, "[$uid]subPosition"), 'size' => 8, 'class' => 'directory-stretch-bar'])?></td>
            <td class="directory-min-width">
                <div class="directory-edit-type-button" title="<?=directoryModule::ht('edit', 'Edit data type')?>">
                    <img src="<?=directoryModule::getPublishImage('/delete-item.png')?>" />
                </div>
            </td>
        </tr>
    </table>
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
        
        var addDataToTable = function(data) {
            if(data !== undefined) {
                var counter = $("#data-add-template<?=$uid?>").prop("field-counter<?=$uid?>");
                ++counter;
                var tmpEl = $("#data-add-template<?=$uid?> tr").clone();
                tmpEl.find("#<?=Html::getInputId($formItemModel, "[$uid]dataId")?>").val(data.id);
                tmpEl.find("#<?=Html::getInputId($formItemModel, "[$uid]position")?>").attr("value", counter);
                $("#data-add-template<?=$uid?>").prop("field-counter<?=$uid?>", counter);
                tmpEl.html(tmpEl.html().replace("<?=$uid?>p1", data.valueDisplay).replace(new RegExp("<?=$uid?>","g"), parseInt(counter)));
                tmpEl.find(".directory-edit-type-button").button().addClass("directory-small-button");
                $("#record-form<?=$uid?> #dataArray table").tableJSPaginator().addRows(tmpEl);
                $("#record-form<?=$uid?> #dataArray table").tooltip({
                    content : function() { return $(this).closest("td").find(".row-value").html(); },
                    items : ".directory-show-full-text"
                });
            }
        }
        
        $("#record-form<?=$uid?> #dataArray table").tableJSPaginator().init(
            {
                prevButton : "#record-form<?=$uid?> #prevButton",
                nextButton : "#record-form<?=$uid?> #nextButton"
            });
        
        $("#record-form<?=$uid?> #prevButton").button({text : false}).click(function() {
            $("#record-form<?=$uid?> #dataArray table").tableJSPaginator().prevPage();
        });
        $("#record-form<?=$uid?> #nextButton").button({text : false}).click(function() {
            $("#record-form<?=$uid?> #dataArray table").tableJSPaginator().nextPage();
        });
        
        $("#record-form<?=$uid?> #dataArray table tbody").on("click", ".directory-edit-type-button", function() {
            $("#record-form<?=$uid?> #dataArray table").tableJSPaginator().removeRows($(this).closest("tr"));
        });
        
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
        
        var AddRecordToDirectory = function(dir) {
            if(dir !== undefined) {
                var counter = $("#directory-add-template<?=$uid?>").prop("field-counter<?=$uid?>");
                ++counter;
                var tmpEl = $("#directory-add-template<?=$uid?> .directory-directory-item").clone();
                $("#directory-add-template<?=$uid?>").prop("field-counter<?=$uid?>", counter);
                tmpEl.html(tmpEl.html().replace("<?=$uid?>p2", ((dir.original_name === undefined) ? dir.name : dir.original_name)).replace(new RegExp("<?=$uid?>p3","g"), parseInt(counter)).replace("<?=$uid?>p4", dir.id));
                //tmpEl.find('input[name=hidden]').val(dir.id);
                $("#record-form<?=$uid?> #directory-record-list").append(tmpEl);
                tmpEl.find(".directory-delete-directory").button({text : false});
                var description = (dir.original_description === undefined) ? dir.description : dir.original_description;
                if((description === undefined) ? (description.length > 0) : false) {
                    tmpEl.find(".directory-label div").html(description);
                    tmpEl.find(".directory-label").tooltip( {
                        content : function() { return $(tmpEl).find(".directory-label div").html(); },
                        items : "img"
                    });
                    tmpEl.find(".directory-label img").removeClass("directory-hide-element");
                }
            }
        };
        
        $("#record-form<?=$uid?> #addRecordToDirectory").button().click(function() {
            $.selectDirectoryDialog({ onSuccess : AddRecordToDirectory });
        });
        
        $("#record-form<?=$uid?> #createNewDirectoryForAddRecord").button().click( function() { 
            $.editDirectoryDialog({ type : 'new', onSuccess : AddRecordToDirectory }); 
        });
        
        $("#record-form<?=$uid?> #directory-record-list").on("click", 
                ".directory-delete-directory", function() {
                    $(this).closest(".directory-directory-item").remove();
        });
        
        $("#editRecordDialog<?=$uid?>").dialog(
            {
                open : function(event, ui) {
                    $("#record-form<?=$uid?> #dataArray table tbody").html("");
                    $("#record-form<?=$uid?> #directory-record-list").html("");
                }
            }
        );
        
        $.editRecordDialog = function(p) {
            if(p !== undefined) {
                if(p.type !== undefined) {
                    $("#data-add-template<?=$uid?>").prop("field-counter<?=$uid?>", 0);
                    $("#directory-add-template<?=$uid?>").prop("field-counter<?=$uid?>", 0);
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
                                                                        errorTag : "#editRecordDialog<?=$uid?> #errorDlgQuery",
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
                            (function(p){
                                $("#record-form<?=$uid?>").trigger('reset');
                                //id в параметрах запроса
                                $("#record-form<?=$uid?> [name='<?=Html::getInputName($formModel, 'visible')?>']").prop("checked", p.data.record.visible);
                                if(p.data.data !== undefined) {
                                    alert("data");
                                }
                                if(p.data.directories !== undefined) {
                                    alert("directories");
                                }
                            
                                $("#editRecordDialog<?=$uid?>").
                                        dialog("option", "title", "<?= directoryModule::ht('edit', 'Edit record')?>").
                                        dialog("option", "buttons", 
                                                        [
                                                            {
                                                                text : "<?= directoryModule::ht('edit', 'Apply')?>",
                                                                click : function() { 
                                                                    /*$.ajaxPostHelper({
                                                                        url : ("<?=Url::toRoute(['/directory/edit/records', 'cmd' => 'create'])?>"),
                                                                        data : $("#record-form<?=$uid?>").serialize(),
                                                                        waitTag : "#editRecordDialog<?=$uid?> #waitDlgQuery",
                                                                        errorTag : "#editRecordDialog<?=$uid?> #errorDlgQuery",
                                                                        errorWaitTimeout : 5,
                                                                        onSuccess : function() { 
                                                                            $("#editRecordDialog<?=$uid?>").dialog("close");
                                                                            if(p.onSuccess !== undefined) {
                                                                                p.onSuccess();
                                                                            }
                                                                        }
                                                                    });*/
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