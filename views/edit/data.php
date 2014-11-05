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

?>

<?php require(__DIR__.'/../helpers/ajaxClientHelper.php');?>
<?php require(__DIR__.'/../helpers/publishResultCSS.php');?>

<?php if(false) { ?><style><?php } ob_start(); ?>
    h1.directory-data-h1-icon {
        background: url(<?= directoryModule::getPublishPath('/img/data32.png') ?>) no-repeat;
        padding-left: 36px;
    }
<?php $this->registerCss(ob_get_clean()); if(false) { ?></style><?php } ?>

<?= Breadcrumbs::widget([
                'links' => [
                    [
                        'label' => directoryModule::t('search', 'Search'),
                        'url' => Url::toRoute('/directory/search/index')
                    ],
                    directoryModule::t('edit', 'Data')
                ]
            ]) ?>

<div class="directory-h1-wrap">
    <h1 class="directory-h1 directory-data-h1-icon"><?= directoryModule::t('edit', 'Data')?></h1>
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
                                <td><?= Html::activeInput('text', $formModel, 'typeId', 
                                                            ['class' => 'directory-stretch-bar directory-grid-filter-control', 'readonly' => 'readonly']) ; ?></td>
                                <td>&nbsp;</td>
                                <td class="directory-min-width">
                                    <button id="selectDataTypeButton">
                                        <nobr>...</nobr>
                                    </button>
                                </td>
                            </tr>
                        </table>
                        <span id="waitDlgQueryDataTypeList" class="directory-hide-element">
                            <nobr>
                                <img src="<?= directoryModule::getPublishPath('/img/wait.gif')?>">
                                <span><?= directoryModule::t('search', 'processing request')?></span>
                            </nobr>
                        </span>
                        <div id="errorQueryDataTypeList" class="directory-error-msg directory-hide-element"></div>
                        <div id="okQueryDataTypeList" class="directory-ok-msg directory-hide-element"></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>


<?php Dialog::end(); ?>

</div>

<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    $("#addDataItem").button().click(
            function() {
                $("#edit-data-form").trigger('reset');
                $("#editDataDialog").
                        dialog("option", "title", "<?= directoryModule::t('edit', 'Create new type')?>").
                        dialog({open : function(event, ui) {
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
                                            "<?= directoryModule::t('edit', 'Add data item')?>": function() {
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
                                            "<?= directoryModule::t('edit', 'Close')?>": function() { $(this).dialog("close"); }
                                        }).
                        dialog("open");
            });
            
            $("#selectDataTypeButton").button().click(function(eventObject) {
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
