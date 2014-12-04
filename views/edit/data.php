<?php 

use yii\jui\Dialog;
use yii\web\View;
use yii\helpers\Html;
use app\modules\directory\directoryModule;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\modules\directory\helpers\ajaxJSONResponseHelper;
use app\modules\directory\widgets\SingletonRenderHelper;
use yii\widgets\Breadcrumbs;

$uid = mt_rand(0, mt_getrandmax());

$this->title = directoryModule::ht('search', 'Directory').' - '.directoryModule::ht('edit', 'Data');

//$uid = mt_rand(0, mt_getrandmax());

?>

<?= SingletonRenderHelper::widget(['viewsRequire' => [
    ['name' => '/helpers/ajax-post-helper'],
    ['name' => '/helpers/publish-result-css'],
    ['name' => '/helpers/publish-types-css'],
    ['name' => '/edit/dialogs/edit-data-dialog', 'params' => ['formModel' => $formModel]]
    ]]) ?>

<?php if(false) { ?><style><?php } ob_start(); ?>
    h1.directory-data-h1-icon {
        background: url(<?= directoryModule::getPublishPath('/img/data32.png') ?>) no-repeat;
        padding-left: 36px;
    }
<?php $this->registerCss(ob_get_clean()); if(false) { ?></style><?php } ?>

<h1 class="directory-h1 directory-data-h1-icon"><?= directoryModule::ht('edit', 'Data')?></h1>

<?= Breadcrumbs::widget([
    'links' => [
        [
            'label' => directoryModule::ht('search', 'Search'),
            'url' => Url::toRoute('/directory/search/index')
        ],
        directoryModule::ht('edit', 'Data')
    ]
    ]) ?>

<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    $("#addDataItem").button().click(
        function() {
            $.editDataDialog({
                type : "new",
                onSuccess : function() { $("#updateDataTable").click(); } 
            });
    }).tooltip({
        content : function() { return $(this).attr("title"); }
    });
    
    $("#dataGridPjaxWidget .directory-edit-type-button, .directory-delete-type-button").button({text : false});

    $("body").tooltip({
        content : function() { return $(this).attr("title"); },
        items : ".directory-edit-type-button, .directory-delete-type-button"
    });
    
    $("#updateDataTable").button({text : false}).click(
        function() {
            $.pjax.reload('#dataGridPjaxWidget', 
                            {
                                push : false,
                                replace : false,
                                timeout : <?=directoryModule::$SETTING['pjaxDefaultTimeout']?>, 
                                url : $("#dataGridWidget").yiiGridView("data").settings.filterUrl
                            });
    }).tooltip({
        content : function() { return $(this).attr("title"); }
    });
    
    $("#dataGridPjaxWidget").on("pjax:start", function() {
        $("#waitQueryData").removeClass("directory-hide-element");
    }).on("pjax:end", function() {
        $("#waitQueryData").addClass("directory-hide-element");
        $(".directory-edit-type-button, .directory-delete-type-button").button({text : false});
    }).on("pjax:error", function(eventObject) {
        eventObject.preventDefault();
        $("#waitQueryData").addClass("directory-hide-element");
        $("#errorQueryData").removeClass("directory-hide-element").html("<nobr><?= directoryModule::ht('search', 'Error connecting to server.')?></nobr>");
        setTimeout(function() { $("#errorQueryDataType").addClass("directory-hide-element"); }, 5000);
    }).on("pjax:timeout", function(eventObject) {
        eventObject.preventDefault();
    }).tooltip({
        content : function() { return $(this).closest("td").find(".row-value").html(); },
        items : ".directory-show-full-text"
    }).on("click", ".directory-edit-type-button", function() {
            $.editDataDialog({
                type : "edit",
                data : $.parseJSON($(this).closest("tr").find("td .directory-row-data").text()),
                onSuccess : function() { $("#updateDataTable").click(); } 
            });
    }).on("click", ".directory-delete-type-button", function() {
        $.ajaxPostHelper({
            url : ("<?= Url::toRoute(['/directory/edit/data', 'cmd' => 'delete', 'id' => $uid])?>").replace("<?=$uid?>", 
                    $.parseJSON($(this).closest("tr").find("td .directory-row-data").text()).id),
            data : "del",
            waitTag: "#waitQueryData",
            errorTag: "#errorQueryData",
            errorWaitTimeout: 5,
            onSuccess: function(dataObject) { 
                if(dataObject.<?=ajaxJSONResponseHelper::messageField?> !== undefined) {
                    if(dataObject.<?=ajaxJSONResponseHelper::messageField?> == "query") {
                        if(confirm(dataObject.<?=ajaxJSONResponseHelper::additionalField?>.message)) {
                            $.ajaxPostHelper({
                                url : ("<?= Url::toRoute(['/directory/edit/data', 'cmd' => 'delete', 'confirm' => 'yes', 'id' => $uid])?>").replace("<?=$uid?>", 
                                        dataObject.<?=ajaxJSONResponseHelper::additionalField?>.id),
                                data : "del",
                                waitTag: "#waitQueryData",
                                errorTag: "#errorQueryData",
                                errorWaitTimeout: 5,
                                onSuccess: function(dataObject) { 
                                    $("#updateDataTable").click();
                                }
                            });
                        }
                    }
                } else {
                    $("#updateDataTable").click();
                }
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
                            <button id="addDataItem" title="<?= directoryModule::ht('edit', 'Add data item')?>...">
                                <nobr>
                                    <span class="directory-add-button-icon"><?= directoryModule::ht('edit', 'Add data item')?>...</span>
                                </nobr>
                            </button>
                        </td>
                        <td class="directory-min-width">&nbsp;</td>
                        <td class="directory-min-width">
                            <button id="updateDataTable" title="<?= directoryModule::ht('edit', 'Update table')?>">
                                <nobr>
                                    <span class="directory-update-button-icon"><?= directoryModule::ht('edit', 'Update table')?></span>
                                </nobr>
                            </button>
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
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="directory-table-wrap">
                <?=$this->render('data_grid', ['dataModel' => $dataModel]);?>
            </div>
        </td>
    </tr>
</table>
