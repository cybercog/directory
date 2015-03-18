<?php

namespace app\modules\directory\widgets;

use yii\base\Widget;

class HierarchBranch extends Widget {
    public $branch;
    public $previevSelector;
    public $branchTemplateSelector;
    public $treeRootTag;
    public $hierarchyID;
    public $waitQueryItems;
    public $errorQueryItems;

    public function run() {
        return $this->render('hierarchy-branch', 
                ['branch'=>  $this->branch, 
                    'previevSelector'=>  $this->previevSelector,
                    'branchTemplateSelector'=>  $this->branchTemplateSelector,
                    'treeRootTag'=>  $this->treeRootTag,
                    'hierarchyID'=>  $this->hierarchyID,
                    'waitQueryItems'=>  $this->waitQueryItems,
                    'errorQueryItems'=>  $this->errorQueryItems]);
    }
}
