<?php 

use yii\web\View;
use yii\helpers\Html;
use app\modules\directory\directoryModule;
use yii\widgets\Breadcrumbs;

use yii\helpers\Url;
use app\modules\directory\widgets\SingletonRenderHelper;
use app\modules\directory\helpers\ajaxJSONResponseHelper;


$this->title = directoryModule::ht('search', 'Directory').' - '.directoryModule::ht('edit', 'Directories');

$uid = mt_rand(0, mt_getrandmax());

?>

<?= SingletonRenderHelper::widget(['viewsRequire' => [
    ['name' => '/helpers/ajax-post-helper'],
    ['name' => '/helpers/publish-result-css'],
    ['name' => '/helpers/publish-types-css'],
    //['name' => '/helpers/ajax-widget-reload-helper'],
    ['name' => '/edit/dialogs/edit-directory-dialog']
    ]]) ?>

<?php if(false) { ?><style><?php } ob_start(); ?>
    h1.directory-directory-h1-icon {
        background: url(<?= directoryModule::getPublishImage('/directory32.png'); ?>) no-repeat;
        padding-left: 36px;
    }
<?php $this->registerCss(ob_get_clean()); if(false) { ?></style><?php } ?>

<h1 class="directory-h1 directory-directory-h1-icon"><?= directoryModule::ht('edit', 'Directories')?></h1>

<?= Breadcrumbs::widget([
    'links' => [
        [
            'label' => directoryModule::ht('search', 'Search'),
            'url' => Url::toRoute('/directory/search/index')
        ],
        directoryModule::ht('edit', 'Directories')
    ]
    ]) ?>

<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    $("#createNewDirectory").button({text : false}).click(
        function() {
            $.editDirectoryDialog({ type : 'new', onSuccess : function() { $("#updateDirectoriesTable").click(); } });
        });

    $("#updateDirectoriesTable").button({text : false}).click(
        function() {
            $.pjax.reload('#directoriesGridPjaxWidget', 
                            {
                                push : false,
                                replace : false,
                                timeout : <?=directoryModule::$SETTING['pjaxDefaultTimeout']?>, 
                                url : $("#directoriesGridWidget").yiiGridView("data").settings.filterUrl
                            });
        });
        
    $(".directory-edit-type-button, .directory-delete-type-button").button({text : false});
    
    $("#directoriesGridPjaxWidget").on("click", ".directory-edit-type-button", 
        function() {
            $.editDirectoryDialog(
                    {
                        type : "edit",
                        data : $.parseJSON($(this).closest("tr").find("td .directory-row-data").text()),
                        onSuccess : function() { $("#updateDirectoriesTable").click(); } 
                    });
    });
    
    $("#directoriesGridPjaxWidget").on("pjax:start", function() {
        $("#waitQueryDirectories").removeClass("directory-hide-element");
    }).on("pjax:end", function() {
        $("#waitQueryDirectories").addClass("directory-hide-element");
        $(".directory-edit-type-button, .directory-delete-type-button").button({text : false});
    }).on("pjax:error", function(eventObject) {
        eventObject.preventDefault();
        $("#waitQueryDirectories").addClass("directory-hide-element");
        $("#errorQueryDirectories").removeClass("directory-hide-element").html("<nobr><?= directoryModule::ht('search', 'Error connecting to server.')?></nobr>");
        setTimeout(function() { $("#errorQueryDirectories").addClass("directory-hide-element"); }, 5000);
    }).on("pjax:timeout", function(eventObject) {
        eventObject.preventDefault();
    }).tooltip({
        content : function() { return $(this).closest("td").find(".row-value").html(); },
        items : ".directory-show-full-text"
    }).on("click", ".directory-delete-type-button", function() {
        $.ajaxPostHelper({
            url : ("<?= Url::toRoute(['/directory/edit/directories', 'cmd' => 'delete', 'id' => $uid])?>").replace("<?=$uid?>", 
                    $.parseJSON($(this).closest("tr").find("td .directory-row-data").text()).id),
            data : "del",
            waitTag: "#waitQueryDirectories",
            errorTag: "#errorQueryDirectories",
            errorWaitTimeout: 5,
            onSuccess: function(dataObject) { 
                $("#updateDirectoriesTable").click();
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
                            <button id="createNewDirectory">
                                <nobr>
                                    <span class="directory-add-button-icon"><?= directoryModule::ht('edit', 'Create new type')?>...</span>
                                </nobr>
                            </button>
                        </td>
                        <td class="directory-min-width">&nbsp;</td>
                        <td class="directory-min-width">
                            <button id="updateDirectoriesTable">
                                <nobr>
                                    <span class="directory-update-button-icon"><?= directoryModule::ht('edit', 'Update table')?></span>
                                </nobr>
                            </button>
                        </td>
                        <td>&nbsp;</td>
                        <td class="directory-min-width">
                            <span id="waitQueryDirectories" class="directory-hide-element">
                                <nobr>
                                    <img src="<?= directoryModule::getPublishImage('/wait.gif')?>">
                                    <span><?= directoryModule::ht('search', 'processing request')?></span>
                                </nobr>
                            </span>
                            <div id="errorQueryDirectories" class="directory-error-msg directory-hide-element"></div>
                            <div id="okQueryDirectories" class="directory-ok-msg directory-hide-element"></div>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="directory-table-wrap">
                <?=$this->render('directories_grid', ['dataModel' => $dataModel])?>
            </div>
        </td>
    </tr>
</table>
