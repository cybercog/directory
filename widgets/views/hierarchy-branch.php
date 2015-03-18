<?php 
use app\modules\directory\directoryModule;
use yii\web\View;
use yii\helpers\Url;
use app\modules\directory\helpers\ajaxJSONResponseHelper;
use app\modules\directory\widgets\SingletonRenderHelper;

$uid = mt_rand(0, mt_getrandmax());
?>

<?= SingletonRenderHelper::widget(['viewsRequire' => [
    ['name' => '/helpers/ajax-post-helper'],
    ['name' => '/helpers/publish-result-css'],
    ['name' => '/helpers/publish-types-css'],
    //['name' => '/helpers/ajax-widget-reload-helper'],
//    ['name' => '/edit/dialogs/edit-hierarchy-dialog']
    ]]) ?>


<table class="directory-modal-table directory-stretch-bar">
    <tr>
        <td class="directory-min-width" colspan="2">
            <div class="directory-tree-node-pic-wrap">
                <img src="<?= directoryModule::getPublishImage('/plus.png'); ?>" />
                <img class="directory-hide-element" src="<?= directoryModule::getPublishImage('/minus.png'); ?>" />
            </div>
            <textarea class="directory-hide-element"><?=json_encode($branch->attributes)?></textarea>
        </td>
        <td class="directory-min-width">&nbsp;</td>
        <td>
            <table class="directory-modal-table">
                <tr>
                    <td class="directory-min-width">
                        <div class="directory-branch-name-element" <?php if(!empty($branch->description)) : ?> title="<?=$branch->description?>" <?php endif; ?>><?=$branch->name?></div>
                    </td>
                    <td class="directory-min-width directory-tools directory-hide-element">&nbsp;</td>
                    <td class="directory-min-width directory-tools directory-hide-element">
                        <div class="directory-btn-tree" title="<?= directoryModule::ht('edit', 'Edit the selected branch')?>">
                            <img src="<?= directoryModule::getPublishImage('/edit-item.png'); ?>" />
                        </div>
                    </td>
                    <td class="directory-min-width directory-tools directory-hide-element">&nbsp;</td>
                    <td class="directory-min-width directory-tools directory-hide-element">
                        <div class="directory-btn-tree" title="<?= directoryModule::ht('edit', 'Delete the selected branch')?>">
                            <img src="<?= directoryModule::getPublishImage('/delete-item.png'); ?>" />
                        </div>
                    </td>
                    <td class="directory-min-width directory-tools directory-hide-element">&nbsp;</td>
                    <td class="directory-min-width directory-table-vertical-line directory-tools directory-hide-element"></td>
                    <td class="directory-min-width directory-tools directory-hide-element">&nbsp;</td>
                    <td class="directory-min-width directory-tools directory-hide-element">
                        <div class="directory-btn-tree" title="<?= directoryModule::ht('edit', 'Create a new subsidiary branch')?>">
                            <img src="<?= directoryModule::getPublishImage('/plus.png'); ?>" />
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td>
            <span id="waitQueryHierarchyTree" class="directory-hide-element">
                <nobr>
                    <img src="<?= directoryModule::getPublishImage('/wait.gif')?>">
                    <span><?= directoryModule::ht('search', 'processing request')?></span>
                </nobr>
            </span>
            <div id="errorQueryHierarchyTree" class="directory-error-msg directory-hide-element"></div>
            <div id="okQueryHierarchyTree" class="directory-ok-msg directory-hide-element"></div>
        </td>
    </tr>
    <tr>
        <td class="directory-min-width"></td>
        <td class="directory-min-width"></td>
        <td class="directory-min-width"></td>
        <td></td>
    </tr>
</table>

<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    $("<?=$treeRootTag?>").on("click", ".directory-tree-node-pic-wrap", function() {
        $.ajaxPostHelper({
            url : ("<?=Url::toRoute(['/directory/edit/hierarchy', 'cmd' => 'get-child', 'hierarchy' => $hierarchyID, 'branch'=>$uid])?>").replace("<?=$uid?>", $.parseJSON($(this).closest("td").find("textarea").text()).id),
            data : null,
            waitTag : $(this).closest("table").find("#waitQueryHierarchyTree"),
            errorTag : $(this).closest("table").find("#errorQueryHierarchyTree"),
            errorWaitTimeout : 5,
            onSuccess : function(dataObject) { 
                alert(dataObject);
            }
        });
    });
    
    $("<?=$treeRootTag?>").on("click", ".directory-branch-name-element", function() {
        $("<?=$treeRootTag?>").find(".directory-branch-name-element-selected").removeClass("directory-branch-name-element-selected").closest("tr").find(".directory-tools").addClass("directory-hide-element");
        $(this).addClass("directory-branch-name-element-selected").closest("tr").find(".directory-tools").removeClass("directory-hide-element");
        $.ajaxPostHelper({
            url : ("<?=Url::toRoute(['/directory/edit/hierarchy', 'cmd' => 'get-records', 'hierarchy' => $hierarchyID, 'branch'=>$uid])?>").replace("<?=$uid?>", $.parseJSON($(this).closest("table").closest("tr").find("td:first textarea").text()).id),
            data : null,
            waitTag : $("<?=$waitQueryItems?>"),
            errorTag : $("<?=$errorQueryItems?>"),
            errorWaitTimeout : 5,
            onSuccess : function(dataObject) { 
                alert(dataObject);
            }
        });
    });
    
<?php $this->registerJs(ob_get_clean(), View::POS_READY, 'hierarchy-branch-5548965236'); if(false) { ?></script><?php } ?>
