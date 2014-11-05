
<?php 

use yii\jui\Dialog;
use yii\web\View;
use yii\helpers\Html;
use app\modules\directory\directoryModule;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;


$this->title = directoryModule::t('search', 'Directory').' - '.directoryModule::t('edit', 'Data types');

$uid = mt_rand(0, mt_getrandmax());

?>

<?php require(__DIR__.'/../helpers/ajaxClientHelper.php');?>
<?php require(__DIR__.'/../helpers/publishResultCSS.php');?>
<?php require(__DIR__.'/../helpers/publishTypesCSS.php');?>

<?php if(false) { ?><style><?php } ob_start(); ?>
    h1.directory-types-h1-icon {
        background: url(<?= directoryModule::getImagePath().'/types32.png'; ?>) no-repeat;
        padding-left: 36px;
    }
<?php $this->registerCss(ob_get_clean()); if(false) { ?></style><?php } ?>

<?= Breadcrumbs::widget([
                'links' => [
                    [
                        'label' => directoryModule::t('search', 'Search'),
                        'url' => Url::toRoute('/directory/search/index')
                    ],
                    directoryModule::t('edit', 'Data types')
                ]
            ]) ?>

<div class="directory-h1-wrap">
    <h1 class="directory-h1 directory-types-h1-icon"><?= directoryModule::t('edit', 'Data types')?></h1>
</div>

<div class="directory-hide-element">

<?php 
Dialog::begin([
    'id' => 'editDialog',
    'clientOptions' => [
        'modal' => true,
        'autoOpen' => false,
        'resizable' => false,
        'width' => 600
    ],
]); ?>

<?php $form = ActiveForm::begin([
    'id' => 'type-data-form',
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
                        <span class="directory-form-label-right-padding"><?= Html::activeLabel($formModel, 'type')?><span class="directory-required-input">*</span></span>
                    </nobr>
                </div>
            </td>
            <td>
                <div class="directory-form-item-bottom-padding">
                <?= Html::activeDropDownList($formModel, 'type', 
                                            ['string' => directoryModule::t('edit', 'string'), 
                                                'text' => directoryModule::t('edit', 'text'), 
                                                'image' => directoryModule::t('edit', 'image'), 
                                                'file' => directoryModule::t('edit', 'file')], 
                                            ['class'=>'directory-stretch-bar directory-grid-filter-control']) ; ?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="directory-min-width directory-table-label">
                <div class="directory-right-padding">
                    <nobr>
                        <span class="directory-form-label-right-padding"><?= Html::activeLabel($formModel, 'validate')?></span>
                    </nobr>
                </div>
            </td>
            <td>
                <div class="directory-form-item-bottom-padding">
                    <div>
                    <?= Html::activeTextarea($formModel, 'validate', ['class'=>'directory-stretch-bar directory-grid-filter-control', 'rows' => 4]) ; ?>
                    </div>
                    <div class="directory-tooltip-message directory-form-item-bottom-padding">
                        <?= directoryModule::t('edit', 'regular expression to validate the entered string')?>
                    </div>
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
    </table>
    <div>
        <span id="waitDlgQueryDataType" class="directory-hide-element">
            <nobr>
                <img src="<?= directoryModule::getImagePath().'/wait.gif'?>">
                <span><?= directoryModule::t('search', 'processing request')?></span>
            </nobr>
        </span>
        <div id="errorDlgQueryDataType" class="directory-error-msg directory-hide-element"></div>
        <div id="okDlgQueryDataType" class="directory-ok-msg directory-hide-element"></div>
    </div>
</div>
    
<?php ActiveForm::end(); ?>

<?php Dialog::end(); ?>
    
</div>


<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    $("#createNewType").click(
            function()  {
                $("#type-data-form").trigger('reset');
                $("#editDialog").
                        dialog("option", "title", "<?= directoryModule::t('edit', 'Create new type')?>").
                        dialog("option", "buttons", 
                                        {
                                            "<?= directoryModule::t('edit', 'Create new type')?>": function() {
                                                ajaxPostHelper({
                                                    url : "<?= Url::toRoute(['/directory/edit/types', 'cmd' => 'create'])?>",
                                                    data : $("#type-data-form").serialize(),
                                                    waitTag : "#waitDlgQueryDataType",
                                                    errorTag : "#errorDlgQueryDataType",
                                                    errorWaitTimeout : 5,
                                                    onSuccess : function(dataObject) { 
                                                        $("#editDialog").dialog("close");
                                                        $.pjax.reload('#typesGridPjaxWidget', {timeout : 10000});
                                                    }
                                                });
                                            },
                                            "<?= directoryModule::t('edit', 'Close')?>": function() { $(this).dialog("close"); }
                                        }).
                        dialog("open");
            });
            
    $("#typesGridPjaxWidget").on("click", ".directory-edit-type-button", function() {
        $("#type-data-form").trigger('reset');
        var field = $(this).closest("tr").find("td").first();
        $("#type-data-form [name='<?=Html::getInputName($formModel, 'name')?>']").val(field.find("div.row-value").text());
        field = field.next();
        $("#type-data-form [name='<?=Html::getInputName($formModel, 'type')?>']").val(field.find("div.row-value").text());
        field = field.next();
        $("#type-data-form [name='<?=Html::getInputName($formModel, 'validate')?>']").val(field.find("div.row-value").text());
        field = field.next();
        $("#type-data-form [name='<?=Html::getInputName($formModel, 'description')?>']").val(field.find("div.row-value").text());
        $("#editDialog").data("row-id", $(this).closest("tr").find("td").first().find("div.row-id").text());
        $("#editDialog").
                dialog("option", "title", "<?= directoryModule::t('edit', 'Edit type')?>").
                dialog("option", "buttons", 
                                {
                                    "<?= directoryModule::t('edit', 'Apply')?>": function() {
                                        var url = "<?= Url::toRoute(['/directory/edit/types', 'cmd' => 'update', 'id' => $uid])?>";
                                        ajaxPostHelper({
                                            url : url.replace("<?=$uid?>", $(this).data("row-id")),
                                            data : $("#type-data-form").serialize(),
                                            waitTag: "#waitDlgQueryDataType",
                                            errorTag: "#errorDlgQueryDataType",
                                            errorWaitTimeout: 5,
                                            onSuccess: function(dataObject) { 
                                                $("#editDialog").dialog("close");
                                                $.pjax.reload('#typesGridPjaxWidget', {timeout : 10000});
                                            }
                                        });
                                    },
                                    "<?= directoryModule::t('edit', 'Close')?>": function() { $(this).dialog("close"); }
                                }).
                dialog("open");
    });
    
    $("#typesGridPjaxWidget").on("click", ".directory-delete-type-button", function() {
        var url = "<?= Url::toRoute(['/directory/edit/types', 'cmd' => 'delete', 'id' => $uid])?>";
        ajaxPostHelper({
            url : url.replace("<?=$uid?>", $(this).closest("tr").find("td").first().find("div.row-id").text()),
            data : $("#type-data-form").serialize(),
            waitTag: "#waitQueryDataType",
            errorTag: "#errorQueryDataType",
            errorWaitTimeout: 5,
            onSuccess: function(dataObject) { 
                $.pjax.reload('#typesGridPjaxWidget', {timeout : 10000});
            }
        });
    });
    
    $("#typesGridPjaxWidget").tooltip({
        content : function() { return $(this).closest("td").find(".row-value").text(); },
        items : ".directory-show-full-text"
        });

    $("body").tooltip({
        content : function() { return $(this).attr("title"); },
        items : ".directory-edit-type-button, .directory-delete-type-button"
        });

    $("#createNewType").tooltip({
        content : function() { return $(this).attr("title"); }
        });
    
    
<?php $this->registerJs(ob_get_clean(), View::POS_READY); if(false) { ?></script><?php } ?>


<table class="directory-modal-table directory-stretch-bar">
    <tr>
        <td class="directory-min-width">
            <div class="directory-buttons-panel-padding-wrap">
                <div class="directory-button-block" id="createNewType" title="<?= directoryModule::t('edit', 'Create new type')?>...">
                    <nobr>
                        <span class="directory-add-button-icon"><?= directoryModule::t('edit', 'Create new type')?>...</span>
                    </nobr>
                </div>
            </div>
        </td>
        <td>&nbsp;</td>
        <td class="directory-min-width">
            <span id="waitQueryDataType" class="directory-hide-element">
                <nobr>
                    <img src="<?= directoryModule::getImagePath().'/../../img/wait.gif'?>">
                    <span><?= directoryModule::t('search', 'processing request')?></span>
                </nobr>
            </span>
            <div id="errorQueryDataType" class="directory-error-msg directory-hide-element"></div>
            <div id="okQueryDataType" class="directory-ok-msg directory-hide-element"></div>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <?php require('types_grid.php'); ?>
        </td>
    </tr>
</table>




