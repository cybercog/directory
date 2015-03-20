<?php 

foreach ($branches as $rootBranch) : 
    echo '<div>'.app\modules\directory\widgets\HierarchBranch::widget(
                ['branch'=>$rootBranch, 
                    'hierarchyID'=>$hierarchy_id,
                    'treeRootTag'=>'#tree-table-'.$uid,
                    'previevSelector'=>'#previev'.$uid, 
                    'branchTemplateSelector'=>'#branch-template-'.$uid,
                    'waitQueryItems'=>'#waitQueryHierarchyTreeSheets'.$uid,
                    'errorQueryItems'=>'#errorQueryHierarchyTreeSheets'.$uid]).'</div>';
endforeach; 
