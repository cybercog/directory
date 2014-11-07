<?php 

use yii\jui\Dialog;
use yii\web\View;
use yii\helpers\Html;
use app\modules\directory\directoryModule;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\modules\directory\helpers\ajaxJSONResponseHelper;
use app\modules\directory\widgets\CoolComboBoxWidget;


$this->title = directoryModule::t('search', 'Directory').' - '.directoryModule::t('edit', 'Data');

$uid = mt_rand(0, mt_getrandmax());

$this->params['breadcrumbs'] = [
        [
            'label' => directoryModule::t('search', 'Search'),
            'url' => Url::toRoute('/directory/search/index')
        ],
        directoryModule::t('edit', 'Data')
    ];
?>

<?php require(__DIR__.'/../helpers/ajaxClientHelper.php');?>
<?php require(__DIR__.'/../helpers/publishResultCSS.php');?>
<?php require(__DIR__.'/../helpers/publishTypesCSS.php');?>

<?php if(false) { ?><style><?php } ob_start(); ?>
    h1.directory-data-h1-icon {
        background: url(<?= directoryModule::getPublishPath('/img/data32.png') ?>) no-repeat;
        padding-left: 36px;
    }
<?php $this->registerCss(ob_get_clean()); if(false) { ?></style><?php } ?>

    <h1 class="directory-h1 directory-data-h1-icon"><?= directoryModule::t('edit', 'Data')?></h1>


<?php require('select_type_dialog.php'); ?>

<div class="directory-hide-element">
    
<?php 
Dialog::begin([
    'id' => 'editDataDialog',
    'clientOptions' => [
        'modal' => true,
        'autoOpen' => false,
        'resizable' => false,
        'width' => 600
    ],
]); ?>
    
<?php $form = ActiveForm::begin([
    'id' => 'data-edit-form',
        ]); ?>
    
    <div>
        <table class="directory-modal-table directory-stretch-bar directory-table">
            <tr>
                <td class="directory-min-width directory-table-label">
                    <div class="directory-right-padding">
                        <nobr>
                            <span class="directory-form-label-right-padding"><?= Html::activeLabel($formModel, 'typeId')?><span class="directory-required-input">*</span></span>
                        </nobr>
                    </div>
                </td>
                <td>
                    <div class="directory-form-item-bottom-padding">
                        <table class="directory-modal-table directory-stretch-bar directory-table">
                            <tr>
                                <td>
                                    <?= Html::activeHiddenInput($formModel, 'typeId')?>
                                    <?= Html::input('text', 'typeId.display', null,
                                                            ['class' => 'directory-stretch-bar directory-grid-filter-control', 
                                                                'readonly' => 'readonly', 
                                                                'id' => Html::getInputId($formModel, 'typeId').'text']) ; ?></td>
                                <td class="directory-min-width">&nbsp;</td>
                                <td class="directory-min-width">
                                    <button id="selectDataTypeButton">
                                        <nobr>...</nobr>
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            
            <tr class="directory-variant directory-variant-value">
                <td class="directory-min-width directory-table-label">
                    <div class="directory-right-padding">
                        <nobr>
                            <span class="directory-form-label-right-padding"><?= Html::activeLabel($formModel, 'value')?><span class="directory-required-input">*</span></span>
                        </nobr>
                    </div>
                </td>
                <td>
                    <div class="directory-form-item-bottom-padding">
                        <?= Html::activeInput('text', $formModel, 'value', ['class'=>'directory-stretch-bar directory-grid-filter-control']) ; ?>
                    </div>
                </td>
            </tr>
            
            <tr class="directory-variant directory-variant-keywords">
                <td class="directory-min-width directory-table-label">
                    <div class="directory-right-padding">
                        <nobr>
                            <span class="directory-form-label-right-padding"><?= Html::activeLabel($formModel, 'keywords')?></span>
                        </nobr>
                    </div>
                </td>
                <td>
                    <div class="directory-form-item-bottom-padding">
                        <?= Html::activeInput('text', $formModel, 'keywords', ['class'=>'directory-stretch-bar directory-grid-filter-control']) ; ?>
                    </div>
                </td>
            </tr>
            

            
            <tr class="directory-variant directory-variant-file">
                <td class="directory-min-width directory-table-label">
                    <div class="directory-right-padding">
                        <nobr>
                            <span class="directory-form-label-right-padding"><?= Html::activeLabel($formModel, 'file')?><span class="directory-required-input">*</span></span>
                        </nobr>
                    </div>
                </td>
                <td>
                    <div class="directory-form-item-bottom-padding">
                        <table class="directory-modal-table directory-stretch-bar directory-table">
                            <tr>
                                <td>
                                    <div class="directory-hide-element">
                                    <?= Html::activeFileInput($formModel, 'file')?>
                                    </div>
                                    <?= Html::input('text', 'file.display', null,
                                                            ['class' => 'directory-stretch-bar directory-grid-filter-control', 
                                                                'readonly' => 'readonly', 
                                                                'id' => Html::getInputId($formModel, 'file').'text']) ; ?></td>
                                <td class="directory-min-width">&nbsp;</td>
                                <td class="directory-min-width">
                                    <button id="selectFileButton">
                                        <nobr>...</nobr>
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr class="directory-variant directory-variant-image">
                <td class="directory-min-width directory-table-label">
                    <div class="directory-right-padding">
                        <nobr>
                            <span class="directory-form-label-right-padding"><?= Html::activeLabel($formModel, 'image')?><span class="directory-required-input">*</span></span>
                        </nobr>
                    </div>
                </td>
                <td>
                    <div class="directory-form-item-bottom-padding">
                        <table class="directory-modal-table directory-stretch-bar directory-table">
                            <tr>
                                <td>
                                    <div class="directory-hide-element">
                                    <?= Html::activeFileInput($formModel, 'image')?>
                                    </div>
                                    <?= Html::input('text', 'image.display', null,
                                                            ['class' => 'directory-stretch-bar directory-grid-filter-control', 
                                                                'readonly' => 'readonly', 
                                                                'id' => Html::getInputId($formModel, 'file').'text']) ; ?></td>
                                <td class="directory-min-width">&nbsp;</td>
                                <td class="directory-min-width">
                                    <button id="selectImageButton">
                                        <nobr>...</nobr>
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            
            
            
            <tr class="directory-variant directory-variant-text">
                <td class="directory-min-width directory-table-label">
                    <div class="directory-right-padding">
                        <nobr>
                            <span class="directory-form-label-right-padding"><?= Html::activeLabel($formModel, 'text')?><span class="directory-required-input">*</span></span>
                        </nobr>
                    </div>
                </td>
                <td>
                    <div class="directory-form-item-bottom-padding">
                        <?= Html::activeTextarea($formModel, 'text', ['class'=>'directory-stretch-bar directory-grid-filter-control', 'rows' => 4]) ; ?>
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
                        <?= Html::activeTextarea($formModel, 'description', ['class'=>'directory-stretch-bar directory-grid-filter-control', 'rows' => 4]) ; ?>
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
        </table>
    </div>

<?php ActiveForm::end(); ?>

<?php Dialog::end(); ?>

</div>

<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    var updateFormState = function(type) {
        $("#data-edit-form .directory-variant").addClass("directory-hide-element");
        
        switch(type) {
            case "string":
                $("#data-edit-form .directory-variant-value").removeClass("directory-hide-element");
                break;
            case "text":
                $("#data-edit-form .directory-variant-keywords, #data-edit-form .directory-variant-text"
                        ).removeClass("directory-hide-element");
                break;
            case "file":
                $("#data-edit-form .directory-variant-keywords, #data-edit-form .directory-variant-file"
                        ).removeClass("directory-hide-element");
                break;
            case "image":
                $("#data-edit-form .directory-variant-keywords, #data-edit-form .directory-variant-image"
                        ).removeClass("directory-hide-element");
                break;
        }
    };
            
    $("#addDataItem").button().click(
            function() {
                $("#data-edit-form").trigger('reset');
                $("#editDataDialog").
                        dialog("option", "title", "<?= directoryModule::t('edit', 'Create new type')?>").
                        dialog({open : function(event, ui) {
                                updateFormState(false);
                                /*ajaxPostHelper({
                                    url : "<?= Url::toRoute(['/directory/edit/typeslist'])?>",
                                    data : 'query types list',
                                    waitTag : "#waitDlgQueryDataTypeList",
                                    errorTag : "#errorDlgQueryDataTypeList",
                                    errorWaitTimeout : 5,
                                    onSuccess : function(dataObject) {
                                        var types_list = $("#editDataDialog #<?=Html::getInputId($formModel, 'typeId')?>");
                                        types_list.empty();
                                        for (var i = 0; i < dataObject.<?=ajaxJSONResponseHelper::additionalField?>.length; ++i) {
                                            var item = dataObject.<?=ajaxJSONResponseHelper::additionalField?>[i];
                                            types_list.append('<option value="' + item.id + '">' + item.name + ' - [' + item.type + ']</option>');
                                        }
                                    }
                                });*/
                        }}).
                        dialog("option", "buttons", 
                                        {
                                            "<?= directoryModule::t('edit', 'Add data item')?>" : function() {
                                                ajaxPostHelper({
                                                    url : "<?= Url::toRoute(['/directory/edit/data', 'cmd' => 'create'])?>",
                                                    data : $("#edit-data-form").serialize(),
                                                    waitTag : "#waitDlgQueryData",
                                                    errorTag : "#errorDlgQueryData",
                                                    errorWaitTimeout : 5,
                                                    onSuccess : function(dataObject) { 
                                                        $("#editDataDialog").dialog("close");
                                                        $.pjax.reload('#typesGridPjaxWidget', {timeout : 10000});
                                                    }
                                                });
                                            },
                                            "<?= directoryModule::t('edit', 'Close')?>" : function() { $(this).dialog("close"); }
                                        }).
                        dialog("open");
    });
    
    $("#selectDataTypeButton").button().click(function(eventObject) {
        eventObject.preventDefault();
        SelectDataType(function(returnType) {
            if(returnType !== false) {
                $("#data-edit-form #<?=Html::getInputId($formModel, 'typeId')?>").val(returnType.id);
                $("#data-edit-form #<?=Html::getInputId($formModel, 'typeId').'text'?>").val(
                        returnType.name + " - [" + returnType.type + "]");
                updateFormState(returnType.type);
            }
        });
    });
    
    $("#selectFileButton").button().click(function(eventObject) {
        eventObject.preventDefault();
        $("#data-edit-form #<?=Html::getInputId($formModel, 'file')?>").click();
    });
    
    $("#selectImageButton").button().click(function(eventObject) {
        eventObject.preventDefault();
        $("#data-edit-form #<?=Html::getInputId($formModel, 'image')?>").click();
    });
    
    $("#typesCompactGridPjaxWidget").on("pjax:start", function() {
        $("#waitDlgQueryCompactDataType").removeClass("directory-hide-element");
    }).on("pjax:end", function() {
        $("#waitDlgQueryCompactDataType").addClass("directory-hide-element");
        $("#typesCompactGridWidget").
                find("tbody tr").
                addClass("directory-row-selector").
                click(function() {
                    $("#selectTypeDialog").dialog("close").data("resultCallback")(
                            {
                                id : $(this).find("td:first .row-id").text(),
                                name : $(this).find("td:first .row-display").text(),
                                type : $(this).find("td:eq(1) .row-value").text(),
                                typeDiaplay : $(this).find("td:eq(1) .row-display").text()
                            }); 
                });
    }).on("pjax:error", function(eventObject) {
        eventObject.preventDefault();
        $("#waitDlgQueryCompactDataType").addClass("directory-hide-element");
        $("#errorDlgQueryCompactDataType").removeClass("directory-hide-element").html("<nobr><?= directoryModule::t('search', 'Error connecting to server.')?></nobr>");
        setTimeout(function() { $("#errorDlgQueryCompactDataType").addClass("directory-hide-element"); }, 5000);
    }).on("pjax:timeout", function(eventObject) {
        eventObject.preventDefault();
    });
    
<?php $this->registerJs(ob_get_clean(), View::POS_READY); if(false) { ?></script><?php } ?>

<table class="directory-modal-table directory-stretch-bar">
    <tr>
        <td class="directory-min-width">
            <div class="directory-buttons-panel-padding-wrap">
                <button id="addDataItem" title="<?= directoryModule::t('edit', 'Add data item')?>...">
                    <nobr>
                        <span class="directory-add-button-icon"><?= directoryModule::t('edit', 'Add data item')?>...</span>
                    </nobr>
                </button>
            </div>
        </td>
        <td>&nbsp;</td>
        <td class="directory-min-width">
            <span id="waitQueryData" class="directory-hide-element">
                <nobr>
                    <img src="<?= directoryModule::getPublishPath('/img/wait.gif')?>">
                    <span><?= directoryModule::t('search', 'processing request')?></span>
                </nobr>
            </span>
            <div id="errorQueryData" class="directory-error-msg directory-hide-element"></div>
            <div id="okQueryData" class="directory-ok-msg directory-hide-element"></div>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <?php require('data_grid.php'); ?>
        </td>
    </tr>
</table>
