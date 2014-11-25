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

$formModel = new \app\modules\directory\models\forms\DataForm;

?>

<?= SingletonRenderHelper::widget(['viewsRequire' => [
    ['name' => '/helpers/ajax-post-helper'],
    ['name' => '/helpers/publish-result-css'],
    ['name' => '/edit/dialogs/edit-type-dialog'],
    ['name' => '/edit/dialogs/select-type-dialog']
    ]]) ?>

<div class="directory-hide-element">
    
<?php 
Dialog::begin([
    'id' => 'editDataDialog'.$uid,
    'clientOptions' => [
        'modal' => true,
        'autoOpen' => false,
        'resizable' => false,
        'width' => 600
    ],
]); ?>
    
<?php $form = ActiveForm::begin([
    'id' => 'data-edit-form'.$uid,
    'options' => ['enctype' => 'multipart/form-data', 'target' => 'file_upload_'.$uid.'_name']
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
                                <div id="selectDataTypeButton">&nbsp;</div>
                                <ul class="directory-ui-menu">
                                    <li><nobr><?= directoryModule::ht('edit', 'Select type')?>...</nobr></li>
                                    <li><nobr><?= directoryModule::ht('edit', 'Create new type')?>...</nobr></li>
                                </ul>
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
                                <?= Html::input('text', 'file_display', null,
                                                        ['class' => 'directory-stretch-bar directory-grid-filter-control', 
                                                            'readonly' => 'readonly', 
                                                            'id' => Html::getInputId($formModel, 'file').'text']) ; ?></td>
                            <td class="directory-min-width">&nbsp;</td>
                            <td class="directory-min-width">
                                <div id="selectFileButton">
                                    <nobr>...</nobr>
                                </div>
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
                                <?= Html::input('text', 'image_display', null,
                                                        ['class' => 'directory-stretch-bar directory-grid-filter-control', 
                                                            'readonly' => 'readonly', 
                                                            'id' => Html::getInputId($formModel, 'file').'text']) ; ?></td>
                            <td class="directory-min-width">&nbsp;</td>
                            <td class="directory-min-width">
                                <div id="selectImageButton">
                                    <nobr>...</nobr>
                                </div>
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
    <div>
        <span id="waitDlgQueryData" class="directory-hide-element">
            <nobr>
                <img src="<?= directoryModule::getPublishPath('/img/wait.gif')?>">
                <span><?= directoryModule::ht('search', 'processing request')?></span>
            </nobr>
        </span>
        <div id="errorDlgQueryData" class="directory-error-msg directory-hide-element"></div>
        <div id="okDlgQueryData" class="directory-ok-msg directory-hide-element"></div>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php Dialog::end(); ?>

</div>

<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    (function($) {
        $("#data-edit-form<?=$uid?> #selectDataTypeButton").
                button({ text : false, icons : { primary : "ui-icon-triangle-1-s" } }).
                click(function(eventObject) {
                    var menu = $(this).next().show().position({ my : "right top", at : "right bottom", of : this });
                    $(document).one('click', function() { menu.hide(); });
                    return false;
            /*eventObject.preventDefault();
            SelectDataType(function(returnType) {
                if(returnType !== false) {
                    $("#data-edit-form #<?=Html::getInputId($formModel, 'typeId')?>").val(returnType.id);
                    $("#data-edit-form #<?=Html::getInputId($formModel, 'typeId').'text'?>").val(
                            returnType.name + " - [" + returnType.type + "]");
                    updateFormState(returnType.type);
                }
            });*/
        }).next().menu().hide().children().first().click(
                function() {
                    $.selectTypeDialog(
                            {
                                onSuccess : function(data) {
                                    if(data !== undefined) {
                                    }
                                }
                            });
        }).next().click(
                function() {
                    alert($(this).text());
        });

        $.editDataDialog = function(p) {
            
            var updateFormState = function(type) {
                $("#data-edit-form<?=$uid?> .directory-variant").addClass("directory-hide-element");

                switch(type) {
                    case "string":
                        $("#data-edit-form<?=$uid?> .directory-variant-value").removeClass("directory-hide-element");
                        break;
                    case "text":
                        $("#data-edit-form<?=$uid?> .directory-variant-keywords, #data-edit-form .directory-variant-text"
                                ).removeClass("directory-hide-element");
                        break;
                    case "file":
                        $("#data-edit-form<?=$uid?> .directory-variant-keywords, #data-edit-form .directory-variant-file"
                                ).removeClass("directory-hide-element");
                        break;
                    case "image":
                        $("#data-edit-form<?=$uid?> .directory-variant-keywords, #data-edit-form .directory-variant-image"
                                ).removeClass("directory-hide-element");
                        break;
                }
            };
            
            if(p !== undefined) {
                if(p.type !== undefined) {
                    switch(p.type) {
                        case "new":
                            (function(p) {
                                $("#data-edit-form<?=$uid?>").trigger('reset');
                                $("#editDataDialog<?=$uid?>").
                                        dialog("option", "title", "<?= directoryModule::ht('edit', 'Create a new item')?>").
                                        dialog({open : function(event, ui) { updateFormState(false); }}).
                                        dialog("option", "buttons", 
                                                        [
                                                            {
                                                                text : "<?= directoryModule::ht('edit', 'Add data item')?>",
                                                                click : function() {
                                                                    $("#data-edit-form<?=$uid?>").
                                                                            attr("action", "<?= Url::toRoute(['/directory/edit/data', 'cmd' => 'create'])?>")[0].
                                                                            submit();
                                                                }
                                                            },
                                                            {
                                                                text : "<?= directoryModule::ht('edit', 'Close')?>",
                                                                click : function() { $("#editDataDialog<?=$uid?>").dialog("close"); }
                                                            }
                                                        ]).
                                        dialog("open");
                            })(p);
                            break;
                        case "edit":
                            break;
                    }
                }
            }
        };
    })(jQuery);
    
    
    
    
   /*$("#selectDataTypeButton").button().click(function(eventObject) {
        eventObject.preventDefault();
        SelectDataType(function(returnType) {
            if(returnType !== false) {
                $("#data-edit-form #<?=Html::getInputId($formModel, 'typeId')?>").val(returnType.id);
                $("#data-edit-form #<?=Html::getInputId($formModel, 'typeId').'text'?>").val(
                        returnType.name + " - [" + returnType.type + "]");
                updateFormState(returnType.type);
            }
        });
    });*/
    
    $("#data-edit-form #<?=Html::getInputId($formModel, 'file')?>").change(function(eventObject) {
        if(eventObject.target.files.length > 0) {
            $("#<?=Html::getInputId($formModel, 'file').'text'?>").val(
                    eventObject.target.files[0].name);
        } else {
            $("#<?=Html::getInputId($formModel, 'file').'text'?>").val("");
        }
    });

    $("#data-edit-form #<?=Html::getInputId($formModel, 'image')?>").change(function(eventObject) {
        if(eventObject.target.files.length > 0) {
            $("#<?=Html::getInputId($formModel, 'image').'text'?>").val(
                    eventObject.target.files[0].name);
        } else {
            $("#<?=Html::getInputId($formModel, 'image').'text'?>").val("");
        }
    });
    
    $("#selectFileButton").button().click(function(eventObject) {
        eventObject.preventDefault();
        $("#data-edit-form #<?=Html::getInputId($formModel, 'file')?>").click();
    });
    
    $("#selectImageButton").button().click(function(eventObject) {
        eventObject.preventDefault();
        $("#data-edit-form #<?=Html::getInputId($formModel, 'image')?>").click();
    });
    
    
    //var j=true;
    
    $("#file_upload_<?=$uid?>").load(function(){
        
        alert($('#file_upload_<?=$uid?>').contents().find('body').text());
        //if(j){$('#file_upload_<?=$uid?>').attr("src", "/directory/edit/data");j=false;}
        /*try {
            var response = $.parseJSON($('#file_upload_<?=$uid?>').contents().find('body').text());
            if(response.<?=ajaxJSONResponseHelper::resultField?> === "<?=ajaxJSONResponseHelper::okResult?>") {
                $("#editDataDialog").dialog("close");
                $("#waitDlgQueryData").addClass("directory-hide-element");
                $.pjax.reload('#dataGridPjaxWidget', {timeout : <?=\Yii::$app->params['pjaxDefaultTimeout']?>});
            } else {
                $("#waitDlgQueryData").addClass("directory-hide-element");
                $("#errorDlgQueryData").removeClass("directory-hide-element").text(response.<?= ajaxJSONResponseHelper::messageField?>);
                setTimeout(function(){ $("#errorDlgQueryData").removeClass("directory-hide-element"); }, 5000);
            }
        } catch(err) {
            $("#waitDlgQueryData").addClass("directory-hide-element");
            $("#errorDlgQueryData").removeClass("directory-hide-element").text("<?= directoryModule::ht('search', 'Error connecting to server.')?>");
            setTimeout(function(){ $("#errorDlgQueryData").removeClass("directory-hide-element"); }, 5000);
        }*/
    });
    
<?php $this->registerJs(ob_get_clean(), View::POS_READY); if(false) { ?></script><?php } ?>