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
endforeach; 
