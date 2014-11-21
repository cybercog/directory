
<?php 

use yii\web\View;
use yii\helpers\Html;
use app\modules\directory\directoryModule;

use yii\helpers\Url;
use app\modules\directory\widgets\SingletonRenderHelper;


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

<?= SingletonRenderHelper::widget(['viewsRequire' => [
    ['name' => '/helpers/ajax-post-helper'],
    ['name' => '/helpers/publish-result-css'],
    ['name' => '/helpers/publish-types-css'],
    ['name' => '/edit/dialogs/edit-type-dialog']
    ]]) ?>

<?php if(false) { ?><style><?php } ob_start(); ?>
    h1.directory-types-h1-icon {
        background: url(<?= directoryModule::getPublishPath('/img/types32.png'); ?>) no-repeat;
        padding-left: 36px;
    }
<?php $this->registerCss(ob_get_clean()); if(false) { ?></style><?php } ?>

<h1 class="directory-h1 directory-types-h1-icon"><?= directoryModule::ht('edit', 'Data types')?></h1>

<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    $("#createNewType").button({text : false}).click(
        function() {
            $.editTypeDialog(
                    { 
                        type : "new", 
                        onSuccess : function() { $("#updateTypesTable").click(); } 
                    });
    }).tooltip({
        content : function() { return $(this).attr("title"); }
    });
    
    $("#updateTypesTable").button({text : false}).click(
        function() {
            $.pjax.reload('#typesGridPjaxWidget', 
                            {
                                push : false,
                                replace : false,
                                timeout : <?=\Yii::$app->params['pjaxDefaultTimeout']?>, 
                                url : $("#typesGridWidget").yiiGridView("data").settings.filterUrl
                            });
    }).tooltip({
        content : function() { return $(this).attr("title"); }
    });
    
    $(".directory-edit-type-button, .directory-delete-type-button").button({text : false});
            
    $("#typesGridPjaxWidget").on("click", ".directory-edit-type-button", 
        function() {
            var field = $(this).closest("tr").find("td").first();
            $.editTypeDialog(
                    {
                        type : "edit",
                        data : {
                            id : field.find("div.row-id").text(),
                            name : field.find("div.row-value").text(),
                            type : (function() { field = field.next(); return field; })().find("div.row-value").text(),
                            validate : (function() { field = field.next(); return field; })().find("div.row-value").text(),
                            description : (function() { field = field.next(); return field; })().find("div.row-value").text()
                        },
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
        var url = "<?= Url::toRoute(['/directory/edit/types', 'cmd' => 'delete', 'id' => $uid])?>";
        $.ajaxPostHelper({
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
            <div class="directory-table-wrap">
                <?=$this->render('types_grid', ['dataModel' => $dataModel])?>
            </div>
        </td>
    </tr>
</table>




