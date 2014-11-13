<?php

namespace app\modules\directory\widgets;

use yii\base\Widget;

class AjaxPostHelper extends Widget {
    private static $isRequire = false;
    
    public function run() {
        if(AjaxPostHelper::$isRequire) {
            return '';
        }
        
        return $this->render('ajax-post-helper');
    }
}
