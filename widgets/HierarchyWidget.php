<?php

namespace app\modules\directory\widgets;

use yii\base\Widget;
use app\modules\directory\models\db\Hierahies;

class HierarchyWidget extends Widget {
    private static $uid;
    public $hierarchy;
    
    public function run() {
        if(!isset(HierarchyWidget::$uid)) {
            HierarchyWidget::$uid = mt_rand(0, mt_getrandmax());
        }
        return $this->render('hierarchy', ['hierarchy'=>$this->hierarchy, 'uid'=>HierarchyWidget::$uid]);
    }
}
