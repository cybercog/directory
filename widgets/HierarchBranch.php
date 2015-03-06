<?php

namespace app\modules\directory\widgets;

use yii\base\Widget;

class HierarchBranch extends Widget {
    public $brabch;
    public $previevSelector;
    
    public function run() {
        return $this->render('hierarchy-branch', 
                ['brabch'=>  $this->brabch, 'previevSelector'=>  $this->previevSelector]);
    }
}
