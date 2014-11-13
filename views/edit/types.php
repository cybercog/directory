
<?php 

use yii\jui\Dialog;
use yii\web\View;
use yii\helpers\Html;
use app\modules\directory\directoryModule;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;


$this->title = directoryModule::ht('search', 'Directory').' - '.directoryModule::ht('edit', 'Data types');

$uid = mt_rand(0, mt_getrandmax());

$this->params['breadcrumbs'] = [
        [
            'label' => directoryModule::ht('search', 'Search'),
            'url' => Url::toRoute('/directory/search/index')
        ],
        directoryModule::ht('edit', 'Data types')
    ];
?>

<?php require(__DIR__.'/../helpers/ajaxClientHelper.php');?>
<?php require(__DIR__.'/../helpers/publishResultCSS.php');?>
<?php require(__DIR__.'/../helpers/publishTypesCSS.php');?>

<?php if(false) { ?><style><?php } ob_start(); ?>
    h1.directory-types-h1-icon {
        background: url(<?= directoryModule::getPublishPath('/img/types32.png'); ?>) no-repeat;
        padding-left: 36px;
    }
<?php $this->registerCss(ob_get_clean()); if(false) { ?></style><?php } ?>

<h1 class="directory-h1 directory-types-h1-icon"><?= directoryModule::ht('edit', 'Data types')?></h1>

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
                                            ['string' => directoryModule::ht('edit', 'string'), 
                                                'text' => directoryModule::ht('edit', 'text'), 
                                                'image' => directoryModule::ht('edit', 'image'), 
                                                'file' => directoryModule::ht('edit', 'file')], 
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
                        <?= directoryModule::ht('edit', 'regular expression to validate the entered string')?>
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
                <img src="<?= directoryModule::getPublishPath('/img/wait.gif')?>">
                <span><?= directoryModule::ht('search', 'processing request')?></span>
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
    
    $("#createNewType").button({text : false}).click(
            function()  {
                $("#type-data-form").trigger('reset');
                $("#editDialog").
                        dialog("option", "title", "<?= directoryModule::ht('edit', 'Create new type')?>").
                        dialog("option", "buttons", 
                                        {
                                            "<?= directoryModule::ht('edit', 'Create new type')?>": function() {
                                                ajaxPostHelper({
                                                    url : "<?= Url::toRoute(['/directory/edit/types', 'cmd' => 'create'])?>",
                                                    data : $("#type-data-form").serialize(),
                                                    waitTag : "#waitDlgQueryDataType",
                                                    errorTag : "#errorDlgQueryDataType",
                                                    errorWaitTimeout : 5,
                                                    onSuccess : function(dataObject) { 
                                                        $("#editDialog").dialog("close");
                                                        $("#updateTypesTable").click();
                                                    }
                                                });
                                            },
                                            "<?= directoryModule::ht('edit', 'Close')?>": function() { $(this).dialog("close"); }
                                        }).
                        dialog("open");
    }).tooltip({
        content : function() { return $(this).attr("title"); }
    });
    
    $("#updateTypesTable").button({text : false}).click(
        function() {
            $.pjax.reload('#typesGridPjaxWidget', 
                            {
                                push : false,
                                replace : false,
                                timeout : <?=$this->context->module->pjaxDefaultTimeout?>, 
                                url : $("#typesGridWidget").yiiGridView("data").settings.filterUrl
                            });
    }).tooltip({
        content : function() { return $(this).attr("title"); }
    });
    
    $(".directory-edit-type-button, .directory-delete-type-button").button({text : false});
            
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
        $("#editDialog").
                data("row-id", $(this).closest("tr").find("td").first().find("div.row-id").text()).
                dialog("option", "title", "<?= directoryModule::ht('edit', 'Edit type')?>").
                dialog("option", "buttons", 
                                {
                                    "<?= directoryModule::ht('edit', 'Apply')?>": function() {
                                        var url = "<?= Url::toRoute(['/directory/edit/types', 'cmd' => 'update', 'id' => $uid])?>";
                                        ajaxPostHelper({
                                            url : url.replace("<?=$uid?>", $(this).data("row-id")),
                                            data : $("#type-data-form").serialize(),
                                            waitTag: "#waitDlgQueryDataType",
                                            errorTag: "#errorDlgQueryDataType",
                                            errorWaitTimeout: 5,
                                            onSuccess: function(dataObject) { 
                                                $("#editDialog").dialog("close");
                                                $("#updateTypesTable").click();
                                            }
                                        });
                                    },
                                    "<?= directoryModule::ht('edit', 'Close')?>": function() { $(this).dialog("close"); }
                                }).
                dialog("open");
    });
    
    $("body").tooltip({
        content : function() { return $(this).attr("title"); },
        items : ".directory-edit-type-button, .directory-delete-type-button"
    });
    
    $("#typesGridPjaxWidget").on("pjax:start", function() {
        $("#waitQueryDataType").removeClass("directory-hide-element");
    }).on("pjax:end", function() {
        $("#waitQueryDataType").addClass("directory-hide-element");
        $(".directory-edit-type-button, .directory-delete-type-button").button({text : false});
    }).on("pjax:error", function(eventObject) {
        eventObject.preventDefault();
        $("#waitQueryDataType").addClass("directory-hide-element");
        $("#errorQueryDataType").removeClass("directory-hide-element").html("<nobr><?= directoryModule::ht('search', 'Error connecting to server.')?></nobr>");
        setTimeout(function() { $("#errorQueryDataType").addClass("directory-hide-element"); }, 5000);
    }).on("pjax:timeout", function(eventObject) {
        eventObject.preventDefault();
    }).tooltip({
        content : function() { return $(this).closest("td").find(".row-value").text(); },
        items : ".directory-show-full-text"
    }).on("click", ".directory-delete-type-button", function() {
        var url = "<?= Url::toRoute(['/directory/edit/types', 'cmd' => 'delete', 'id' => $uid])?>";
        ajaxPostHelper({
            url : url.replace("<?=$uid?>", $(this).closest("tr").find("td").first().find("div.row-id").text()),
            data : $("#type-data-form").serialize(),
            waitTag: "#waitQueryDataType",
            errorTag: "#errorQueryDataType",
            errorWaitTimeout: 5,
            onSuccess: function(dataObject) { 
                $("#updateTypesTable").click();
            }
        });
    });
    
<?php $this->registerJs(ob_get_clean(), View::POS_READY); if(false) { ?></script><?php } ?>


<table class="directory-modal-table directory-stretch-bar">
    <tr>
        <td class="directory-min-width">
            <div class="directory-buttons-panel-padding-wrap ui-widget-header ui-corner-all">
                <table class="directory-modal-table directory-stretch-bar">
                    <tr>
                        <td class="directory-min-width">
                            <button id="createNewType" title="<?= directoryModule::ht('edit', 'Create new type')?>...">
                                <nobr>
                                    <span class="directory-add-button-icon"><?= directoryModule::ht('edit', 'Create new type')?>...</span>
                                </nobr>
                            </button>
                        </td>
                        <td class="directory-min-width">&nbsp;</td>
                        <td class="directory-min-width">
                            <button id="updateTypesTable" title="<?= directoryModule::ht('edit', 'Update table')?>">
                                <nobr>
                                    <span class="directory-update-button-icon"><?= directoryModule::ht('edit', 'Update table')?></span>
                                </nobr>
                            </button>
                        </td>
                        <td>&nbsp;</td>
                        <td class="directory-min-width">
                            <span id="waitQueryDataType" class="directory-hide-element">
                                <nobr>
                                    <img src="<?= directoryModule::getPublishPath('/img/wait.gif')?>">
                                    <span><?= directoryModule::ht('search', 'processing request')?></span>
                                </nobr>
                            </span>
                            <div id="errorQueryDataType" class="directory-error-msg directory-hide-element"></div>
                            <div id="okQueryDataType" class="directory-ok-msg directory-hide-element"></div>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <?php require('types_grid.php'); ?>
        </td>
    </tr>
</table>




