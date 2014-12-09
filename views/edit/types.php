
<?php 

use yii\web\View;
use yii\helpers\Html;
use app\modules\directory\directoryModule;
use yii\widgets\Breadcrumbs;

use yii\helpers\Url;
use app\modules\directory\widgets\SingletonRenderHelper;
use app\modules\directory\helpers\ajaxJSONResponseHelper;


$this->title = directoryModule::ht('search', 'Directory').' - '.directoryModule::ht('edit', 'Data types');

$uid = mt_rand(0, mt_getrandmax());

?>

<?= SingletonRenderHelper::widget(['viewsRequire' => [
    ['name' => '/helpers/ajax-post-helper'],
    ['name' => '/helpers/publish-result-css'],
    ['name' => '/helpers/publish-types-css'],
    ['name' => '/helpers/ajax-widget-reload-helper'],
    ['name' => '/edit/dialogs/edit-type-dialog']
    ]]) ?>

<?php if(false) { ?><style><?php } ob_start(); ?>
    h1.directory-types-h1-icon {
        background: url(<?= directoryModule::getPublishPath('/img/types32.png'); ?>) no-repeat;
        padding-left: 36px;
    }
<?php $this->registerCss(ob_get_clean()); if(false) { ?></style><?php } ?>

<h1 class="directory-h1 directory-types-h1-icon"><?= directoryModule::ht('edit', 'Data types')?></h1>

<?= Breadcrumbs::widget([
    'links' => [
        [
            'label' => directoryModule::ht('search', 'Search'),
            'url' => Url::toRoute('/directory/search/index')
        ],
        directoryModule::ht('edit', 'Data types')
    ]
    ]) ?>

<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    $("#createNewType").button({text : false}).click(
        function() {
            $.editTypeDialog(
                    { 
                        type : "new", 
                        onSuccess : function() { $("#updateTypesTable").click(); } 
                    });
    });
    
    $("#updateTypesTable").button({text : false}).click(
        function() {
            $.pjax.reload('#typesGridPjaxWidget', 
                            {
                                push : false,
                                replace : false,
                                timeout : <?=directoryModule::$SETTING['pjaxDefaultTimeout']?>, 
                                url : $("#typesGridWidget").yiiGridView("data").settings.filterUrl
                            });
    });
    
    $(".directory-edit-type-button, .directory-delete-type-button").button({text : false});
            
    $("#typesGridPjaxWidget").on("click", ".directory-edit-type-button", 
        function() {
            $.editTypeDialog(
                    {
                        type : "edit",
                        data : $.parseJSON($(this).closest("tr").find("td .directory-row-data").text()),
                        onSuccess : function() { $("#updateTypesTable").click(); } 
                    });
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
        content : function() { return $(this).closest("td").find(".row-value").html(); },
        items : ".directory-show-full-text"
    }).on("click", ".directory-delete-type-button", function() {
        $.ajaxPostHelper({
            url : ("<?= Url::toRoute(['/directory/edit/types', 'cmd' => 'delete', 'id' => $uid])?>").replace("<?=$uid?>", 
                    $.parseJSON($(this).closest("tr").find("td .directory-row-data").text()).id),
            data : "del",
            waitTag: "#waitQueryDataType",
            errorTag: "#errorQueryDataType",
            errorWaitTimeout: 5,
            onSuccess: function(dataObject) { 
                if(dataObject.<?=ajaxJSONResponseHelper::messageField?> !== undefined) {
                    if(dataObject.<?=ajaxJSONResponseHelper::messageField?> == "query") {
                        if(confirm(dataObject.<?=ajaxJSONResponseHelper::additionalField?>.message)) {
                            $.ajaxPostHelper({
                                url : ("<?= Url::toRoute(['/directory/edit/types', 'cmd' => 'delete', 'confirm' => 'yes', 'id' => $uid])?>").replace("<?=$uid?>", 
                                        dataObject.<?=ajaxJSONResponseHelper::additionalField?>.id),
                                data : "del",
                                waitTag: "#waitQueryDataType",
                                errorTag: "#errorQueryDataType",
                                errorWaitTimeout: 5,
                                onSuccess: function(dataObject) { 
                                    $("#updateTypesTable").click();
                                }
                            });
                        }
                    }
                } else {
                    $("#updateTypesTable").click();
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
                            <button id="createNewType">
                                <nobr>
                                    <span class="directory-add-button-icon"><?= directoryModule::ht('edit', 'Create new type')?>...</span>
                                </nobr>
                            </button>
                        </td>
                        <td class="directory-min-width">&nbsp;</td>
                        <td class="directory-min-width">
                            <button id="updateTypesTable">
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
            <div class="directory-table-wrap" id="ajaxTypesGrid">
                <?=$this->render('types_grid', ['dataModel' => $dataModel])?>
            </div>
        </td>
    </tr>
</table>




