
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
        <td>
            <div>
                <?php 
                $rootBranches = $hierarchy->getRootBranches()->all();
                foreach ($rootBranches as $rootBranch) : 
                    echo app\modules\directory\widgets\HierarchBranch::widget(
                                ['branch'=>$rootBranch, 
                                    'hierarchyID'=>$hierarchy->id,
                                    'treeRootTag'=>'#tree-table-'.$uid,
                                    'previevSelector'=>'#previev'.$uid, 
                                    'branchTemplateSelector'=>'branch-template-'.$uid]);
                endforeach; ?>
            </div>
        </td>
        <td>
            <div id="previev<?=$uid?>"></div>
        </td>
    </tr>
</table>