<?php 
use app\modules\directory\directoryModule;
use yii\web\View;
use yii\helpers\Url;
use app\modules\directory\helpers\ajaxJSONResponseHelper;
?>

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
        <td><?=$branch->name?></td>
    </tr>
    <tr>
        <td colspan="4">
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
        $(this).closest("table").find("#waitQueryHierarchyTree").removeClass("directory-hide-element");
        $.ajaxPostHelper({
            url : ("<?=Url::toRoute(['/directory/edit/hierarchy', 'cmd' => 'get-child', 'hierarchy' => $hierarchyID, 'branch'=>$uid])?>").replace("<?=$uid?>", p.data.id),
            data : $("#directory-form<?=$uid?>").serialize(),
            waitTag : $(this).closest("table").find("#waitQueryHierarchyTree"),
            errorTag : $(this).closest("table").find("#errorQueryHierarchyTree"),
            errorWaitTimeout : 5,
            onSuccess : function(dataObject) { 
                if(p.onSuccess !== undefined) {
                    if((dataObject !== undefined) &&
                            (dataObject.<?=ajaxJSONResponseHelper::messageField?> !== undefined)) {
                        p.onSuccess(dataObject.<?=ajaxJSONResponseHelper::messageField?>);
                    } else {
                       p.onSuccess();
                    }
                }
            }
        });
    });
    
<?php $this->registerJs(ob_get_clean(), View::POS_READY); if(false) { ?></script><?php } ?>
