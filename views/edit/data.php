<?php 

use yii\jui\Dialog;
use yii\web\View;
use yii\helpers\Html;
use app\modules\directory\directoryModule;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\modules\directory\helpers\ajaxJSONResponseHelper;
use app\modules\directory\widgets\SingletonRenderHelper;

$uid = mt_rand(0, mt_getrandmax());

$this->title = directoryModule::ht('search', 'Directory').' - '.directoryModule::ht('edit', 'Data');

$uid = mt_rand(0, mt_getrandmax());

$this->params['breadcrumbs'] = [
        [
            'label' => directoryModule::ht('search', 'Search'),
            'url' => Url::toRoute('/directory/search/index')
        ],
        directoryModule::ht('edit', 'Data')
    ];
?>

<?= SingletonRenderHelper::widget(['viewsRequire' => [
    ['name' => '/helpers/ajax-post-helper'],
    ['name' => '/helpers/publish-result-css'],
    ['name' => '/helpers/publish-types-css']
    ]]) ?>

<?php if(false) { ?><style><?php } ob_start(); ?>
    h1.directory-data-h1-icon {
        background: url(<?= directoryModule::getPublishPath('/img/data32.png') ?>) no-repeat;
        padding-left: 36px;
    }
<?php $this->registerCss(ob_get_clean()); if(false) { ?></style><?php } ?>

<h1 class="directory-h1 directory-data-h1-icon"><?= directoryModule::ht('edit', 'Data')?></h1>


<?php require('select_type_dialog.php'); ?>


<div class="directory-hide-element">
    <iframe id="file_upload_<?=$uid?>" name="file_upload_<?=$uid?>_name"></iframe>
</div>


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
                                <div id="selectDataTypeButton">
                                    <nobr>...</nobr>
                                </div>
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
(function( factory ) {
	if ( typeof define === "function" && define.amd ) {

		// AMD. Register as an anonymous module.
		define([
			"jquery",
			"./core"
		], factory );
	} else {

		// Browser globals
		factory( jQuery );
	}
})

(function($){
    $.ajaxPostHelper = function(){
        alert("ajaxPostHelper");
        return $;
    };
});<?php $this->registerJs(ob_get_clean(), View::POS_READY); if(false) { ?></script><?php } ?>


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
                        dialog("option", "title", "<?= directoryModule::ht('edit', 'Create new type')?>").
                        dialog({open : function(event, ui) { updateFormState(false); }}).
                        dialog("option", "buttons", 
                                        [
                                            {
                                                text : "<?= directoryModule::ht('edit', 'Add data item')?>",
                                                click : function() {
                                                    $("#data-edit-form").
                                                            attr("action", "<?= Url::toRoute(['/directory/edit/data', 'cmd' => 'create'])?>")[0].
                                                            submit();
                                                }
                                            },
                                            {
                                                text : "<?= directoryModule::ht('edit', 'Close')?>",
                                                click : function() { $(this).dialog("close"); $.ajaxPostHelper();}
                                            }
                                        ]).
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
                $.pjax.reload('#dataGridPjaxWidget', {timeout : <?=$this->context->module->pjaxDefaultTimeout?>});
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

<table class="directory-modal-table directory-stretch-bar">
    <tr>
        <td class="directory-min-width">
            <div class="directory-buttons-panel-padding-wrap">
                <button id="addDataItem" title="<?= directoryModule::ht('edit', 'Add data item')?>...">
                    <nobr>
                        <span class="directory-add-button-icon"><?= directoryModule::ht('edit', 'Add data item')?>...</span>
                    </nobr>
                </button>
            </div>
        </td>
        <td>&nbsp;</td>
        <td class="directory-min-width">
            <span id="waitQueryData" class="directory-hide-element">
                <nobr>
                    <img src="<?= directoryModule::getPublishPath('/img/wait.gif')?>">
                    <span><?= directoryModule::ht('search', 'processing request')?></span>
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
