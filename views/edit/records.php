<?php

use yii\helpers\Url;
use app\modules\directory\directoryModule;
use yii\widgets\Breadcrumbs;
use yii\web\View;

use app\modules\directory\widgets\SingletonRenderHelper;
use app\modules\directory\helpers\ajaxJSONResponseHelper;

$this->title = directoryModule::ht('search', 'Directory').' - '.directoryModule::ht('edit', 'Records');
$uid = mt_rand(0, mt_getrandmax());

?>

<?= SingletonRenderHelper::widget(['viewsRequire' => [
    ['name' => '/helpers/ajax-post-helper'],
    ['name' => '/helpers/publish-result-css'],
//    ['name' => '/helpers/publish-types-css'],
   // ['name' => '/edit/dialogs/edit-type-dialog']
    ['name' => '/edit/dialogs/edit-record-dialog']
    ]]) ?>


<?php if(false) { ?><style><?php } ob_start(); ?>
    h1.directory-records-h1-icon {
        background: url(<?= directoryModule::getPublishImage('/record32.png'); ?>) no-repeat;
        padding-left: 36px;
    }
<?php $this->registerCss(ob_get_clean()); if(false) { ?></style><?php } ?>

<h1 class="directory-h1 directory-records-h1-icon"><?= directoryModule::ht('edit', 'Records')?></h1>

<?= Breadcrumbs::widget([
    'links' => [
        [
            'label' => directoryModule::ht('search', 'Search'),
            'url' => Url::toRoute('/directory/search/index')
        ],
        directoryModule::ht('edit', 'Records')
    ]
    ]) ?>


<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    $("#addRecordItem").button().click(function() {
        $.editRecordDialog({
            type : "new",
            onSuccess : function() { $("#updateRecordTable").click(); }
        });
    });

    $("#updateRecordTable").button().click(function() {
            $.pjax.reload('#recordsGridPjaxWidget', 
                            {
                                push : false,
                                replace : false,
                                timeout : <?=directoryModule::$SETTING['pjaxDefaultTimeout']?>, 
                                url : $("#recordsGridWidget").yiiGridView("data").settings.filterUrl
                            });
    });
    
    $(".directory-edit-type-button, .directory-delete-type-button").button({text : false});
    
    $("#recordsGridPjaxWidget").on("click", ".directory-edit-type-button", function() {
        var data = undefined;
        var directories = undefined;
        
        try {
            //alert($(this).closest("tr").find(".data-data").text());
            data = $.parseJSON($(this).closest("tr").find(".data-data").text());
        } catch(e) { 
        }
        try {
            directories = $.parseJSON($(this).closest("tr").find(".directories-data").text());
        } catch(e) { 
        }
        
        $.editRecordDialog({
            type : "edit",
            data : {
                record : $.parseJSON($(this).closest("tr").find(".record-id").text()),
                data : data,
                directories : directories
            },
            onSuccess : function() { $("#updateRecordTable").click(); }
        });
    });
    
    $("#recordsGridPjaxWidget").on("pjax:start", function() {
        $("#waitQueryRecord").removeClass("directory-hide-element");
    }).on("pjax:end", function() {
        $("#waitQueryRecord").addClass("directory-hide-element");
        $(".directory-edit-type-button, .directory-delete-type-button").button({text : false});
    }).on("pjax:error", function(eventObject) {
        eventObject.preventDefault();
        $("#waitQueryRecord").addClass("directory-hide-element");
        $("#errorQueryRecord").removeClass("directory-hide-element").html("<nobr><?= directoryModule::ht('search', 'Error connecting to server.')?></nobr>");
        setTimeout(function() { $("#errorQueryRecord").addClass("directory-hide-element"); }, 5000);
    }).on("pjax:timeout", function(eventObject) {
        eventObject.preventDefault();
    }).tooltip({
        content : function() { return $(this).closest("td").find(".row-value").html(); },
        items : ".directory-show-full-text"
    }).on("click", ".directory-delete-type-button", function() {
        $.ajaxPostHelper({
            url : ("<?= Url::toRoute(['/directory/edit/records', 'cmd' => 'delete', 'id' => $uid])?>").replace("<?=$uid?>", 
                    $(this).closest("tr").find(".record-id").text()),
            data : "del",
            waitTag: "#waitQueryRecord",
            errorTag: "#errorQueryRecord",
            errorWaitTimeout: 5,
            onSuccess: function(dataObject) { 
                $("#updateRecordTable").click();
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
                            <button id="addRecordItem">
                                <nobr>
                                    <span class="directory-add-button-icon"><?= directoryModule::ht('edit', 'Add record')?>...</span>
                                </nobr>
                            </button>
                        </td>
                        <td class="directory-min-width">&nbsp;</td>
                        <td class="directory-min-width">
                            <button id="updateRecordTable">
                                <nobr>
                                    <span class="directory-update-button-icon"><?= directoryModule::ht('edit', 'Update table')?></span>
                                </nobr>
                            </button>
                        </td>
                        <td>&nbsp;</td>
                        <td class="directory-min-width">
                            <span id="waitQueryRecord" class="directory-hide-element">
                                <nobr>
                                    <img src="<?= directoryModule::getPublishImage('/wait.gif')?>">
                                    <span><?= directoryModule::ht('search', 'processing request')?></span>
                                </nobr>
                            </span>
                            <div id="errorQueryRecord" class="directory-error-msg directory-hide-element"></div>
                            <div id="okQueryRecord" class="directory-ok-msg directory-hide-element"></div>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="directory-table-wrap">
                <?=$this->render('record_grid', ['dataModel' => $dataModel]);?>
            </div>
        </td>
    </tr>
</table>
