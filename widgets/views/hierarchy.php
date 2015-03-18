<?php 
use app\modules\directory\directoryModule;
use yii\web\View;
use app\modules\directory\widgets\SingletonRenderHelper;

yii\jui\JuiAsset::register($this);

?>

<?= SingletonRenderHelper::widget(['viewsRequire' => [
    ['name' => '/helpers/ajax-post-helper'],
    ['name' => '/helpers/publish-result-css'],
    ['name' => '/edit/dialogs/edit-branch-dialog'],
    //['name' => '/edit/dialogs/select-type-dialog']
    ]]) ?>


<div class="directory-hide-element" id="branch-template-<?=$uid?>">
    <table id="middle">
        <tr>
            <td class="directory-min-width"></td>
            <td class="directory-min-width directory-tree-node-line-end"></td>
            <td rowspan="2"></td>
            <td rowspan="2">branch-template-<?=$uid?>-text</td>
        </tr>
        <tr>
            <td class="directory-min-width"></td>
            <td class="directory-min-width directory-tree-node-line-end-bottom directory-tree-node-line-middle"></td>
        </tr>
    </table>
    <table id="end">
        <tr>
            <td class="directory-min-width"></td>
            <td class="directory-min-width directory-tree-node-line-end"></td>
            <td rowspan="2"></td>
            <td rowspan="2">branch-template-<?=$uid?>-text</td>
        </tr>
        <tr>
            <td class="directory-min-width"></td>
            <td class="directory-min-width directory-tree-node-line-end-bottom"></td>
        </tr>
    </table>
</div>

<table class="directory-modal-table directory-stretch-bar" id="tree-table-<?=$uid?>">
    <tr>
        <td colspan="2">
            <table class="directory-modal-table directory-stretch-bar">
                <tr>
                    <td>
                        <div class="directory-buttons-panel-padding-wrap ui-widget-header ui-corner-all">
                            <table class="directory-modal-table directory-stretch-bar">
                                <tr>
                                    <td class="directory-min-width">
                                        <button id="createRootBranch<?=$uid?>">
                                            <nobr>
                                                <span class="directory-add-button-icon"><?= directoryModule::ht('edit', 'Create a new root branch')?>...</span>
                                            </nobr>
                                        </button>
                                    </td>
                                    <td class="directory-min-width">&nbsp;</td>
                                    <td class="directory-min-width">
                                        <button id="updateRootBranchTree<?=$uid?>">
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
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <div>
                <?php 
                $rootBranches = $hierarchy->getRootBranches()->all();
                foreach ($rootBranches as $rootBranch) : 
                    echo '<div>'.app\modules\directory\widgets\HierarchBranch::widget(
                                ['branch'=>$rootBranch, 
                                    'hierarchyID'=>$hierarchy->id,
                                    'treeRootTag'=>'#tree-table-'.$uid,
                                    'previevSelector'=>'#previev'.$uid, 
                                    'branchTemplateSelector'=>'#branch-template-'.$uid,
                                    'waitQueryItems'=>'#waitQueryHierarchyTreeSheets'.$uid,
                                    'errorQueryItems'=>'#errorQueryHierarchyTreeSheets'.$uid]).'</div>';
                endforeach; ?>
            </div>
        </td>
        <td>
            <div>
                <span id="waitQueryHierarchyTreeSheets<?=$uid?>" class="directory-hide-element">
                    <nobr>
                        <img src="<?= directoryModule::getPublishImage('/wait.gif')?>">
                        <span><?= directoryModule::ht('search', 'processing request')?></span>
                    </nobr>
                </span>
                <div id="errorQueryHierarchyTreeSheets<?=$uid?>" class="directory-error-msg directory-hide-element"></div>
                <div id="okQueryHierarchyTreeSheets<?=$uid?>" class="directory-ok-msg directory-hide-element"></div>
            </div>
            <div id="previev<?=$uid?>"></div>
        </td>
    </tr>
</table>

<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    $("#createRootBranch<?=$uid?>").button({text : false}).click(function() {
        
    });
    
    $("#updateRootBranchTree<?=$uid?>").button({text : false}).click(function() {
        
    });
    
<?php $this->registerJs(ob_get_clean(), View::POS_READY, 'hierarchy-5548960005236'); if(false) { ?></script><?php } ?>