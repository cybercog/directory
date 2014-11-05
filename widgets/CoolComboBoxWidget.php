<?php

namespace app\modules\directory\widgets;

use yii\base\Widget;
use app\modules\directory\directoryModule;

class CoolComboBoxWidget extends Widget {
    public function init() {
        parent::init();
        
        $this->getView()->registerCssFile(
                    isset($this->css) ? 
                        $this->css : 
                        directoryModule::getCSSPath().'/cool-combo-box.css');    
    }
    
    public function run() {
        return $this->render('cool-combo-box', ['cool' => $this]);
    }
}
