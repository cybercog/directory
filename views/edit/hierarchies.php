<?php 
use yii\helpers\Url;
use app\modules\directory\directoryModule;
use yii\widgets\Breadcrumbs;
use yii\web\View;

use app\modules\directory\widgets\SingletonRenderHelper;
use app\modules\directory\helpers\ajaxJSONResponseHelper;

$this->title = directoryModule::ht('search', 'Directory').' - '.directoryModule::ht('edit', 'Hierarhies');
$uid = mt_rand(0, mt_getrandmax());
?>

<?= SingletonRenderHelper::widget(['viewsRequire' => [
    ['name' => '/helpers/ajax-post-helper'],
    ['name' => '/helpers/publish-result-css'],
    ['name' => '/helpers/publish-types-css'],
    //['name' => '/helpers/ajax-widget-reload-helper'],
    ['name' => '/edit/dialogs/edit-hierarchy-dialog']
    ]]) ?>


<?php if(false) { ?><style><?php } ob_start(); ?>
    h1.directory-records-h1-icon {
        background: url(<?= directoryModule::getPublishImage('/hierarchy32.png'); ?>) no-repeat;
        padding-left: 36px;
    }
<?php $this->registerCss(ob_get_clean()); if(false) { ?></style><?php } ?>

<h1 class="directory-h1 directory-records-h1-icon"><?= directoryModule::ht('edit', 'Hierarhies')?></h1>

<?= Breadcrumbs::widget([
    'links' => [
        [
            'label' => directoryModule::ht('search', 'Search'),
            'url' => Url::toRoute('/directory/search/index')
        ],
        directoryModule::ht('edit', 'Hierarhies')
    ]
    ]) ?>

<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    $("#createNewHierarchy").button({text : false}).click(function() {
        $.editHierachyDialog({type : "new", onSuccess : function() { $("#updateHierarchiesTable").click(); }});
    });

    $("#updateHierarchiesTable").button({text : false}).click(function() {
            $.pjax.reload('#hierarchiesGridPjaxWidget', 
                            {
                                push : false,
                                replace : false,
                                timeout : <?=directoryModule::$SETTING['pjaxDefaultTimeout']?>, 
                                url : $("#hierarchiesGridWidget").yiiGridView("data").settings.filterUrl
                            });
    });
    
    $(".directory-edit-type-button, .directory-delete-type-button").button({text : false});
    
    $("#hierarchiesGridPjaxWidget").on("click", ".directory-edit-type-button", function() {
        var data = undefined;
        var directories = undefined;
        
        try {
            data = $.parseJSON($(this).closest("tr").find(".data-data").text());
        } catch(e) { 
        }
        try {
            directories = $.parseJSON($(this).closest("tr").find(".directories-data").text());
        } catch(e) { 
        }
        
        $.editHierachyDialog({
            type : "edit",
            data : {
                hierachy : data,
                directories : directories
            },
            onSuccess : function() { $("#updateHierarchiesTable").click(); }
        });
    });

    $("#hierarchiesGridPjaxWidget").on("pjax:start", function() {
        $("#waitQueryHierarchies").removeClass("directory-hide-element");
    }).on("pjax:end", function() {
        $("#waitQueryHierarchies").addClass("directory-hide-element");
        $(".directory-edit-type-button, .directory-delete-type-button").button({text : false});
    }).on("pjax:error", function(eventObject) {
        eventObject.preventDefault();
        $("#waitQueryHierarchies").addClass("directory-hide-element");
        $("#errorQueryHierarchies").removeClass("directory-hide-element").html("<nobr><?= directoryModule::ht('search', 'Error connecting to server.')?></nobr>");
        setTimeout(function() { $("#errorQueryHierarchies").addClass("directory-hide-element"); }, 5000);
    }).on("pjax:timeout", function(eventObject) {
        eventObject.preventDefault();
    }).tooltip({
        content : function() { return $(this).closest("td").find(".row-value").html(); },
        items : ".directory-show-full-text"
    }).on("click", ".directory-delete-type-button", function() {
        $.ajaxPostHelper({
            url : ("<?= Url::toRoute(['/directory/edit/hierarchies', 'cmd' => 'delete', 'id' => $uid])?>").replace("<?=$uid?>", 
                    $.parseJSON($(this).closest("tr").find("td .directory-row-data").text()).id),
            data : "del",
            waitTag: "#waitQueryHierarchies",
            errorTag: "#errorQueryDirectories",
            errorWaitTimeout: 5,
            onSuccess: function(dataObject) { 
                $("#updateHierarchiesTable").click();
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
                            <button id="createNewHierarchy">
                                <nobr>
                                    <span class="directory-add-button-icon"><?= directoryModule::ht('edit', 'Create new hierarchy')?>...</span>
                                </nobr>
                            </button>
                        </td>
                        <td class="directory-min-width">&nbsp;</td>
                        <td class="directory-min-width">
                            <button id="updateHierarchiesTable">
                                <nobr>
                                    <span class="directory-update-button-icon"><?= directoryModule::ht('edit', 'Update table')?></span>
                                </nobr>
                            </button>
                        </td>
                        <td>&nbsp;</td>
                        <td class="directory-min-width">
                            <span id="waitQueryHierarchies" class="directory-hide-element">
                                <nobr>
                                    <img src="<?= directoryModule::getPublishImage('/wait.gif')?>">
                                    <span><?= directoryModule::ht('search', 'processing request')?></span>
                                </nobr>
                            </span>
                            <div id="errorQueryHierarchies" class="directory-error-msg directory-hide-element"></div>
                            <div id="okQueryHierarchies" class="directory-ok-msg directory-hide-element"></div>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="directory-table-wrap">
                <?=$this->render('hierarchies_grid', ['dataModel' => $dataModel])?>
            </div>
        </td>
    </tr>
</table>
