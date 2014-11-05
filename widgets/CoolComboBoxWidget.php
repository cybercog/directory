<?php

namespace app\modules\directory\widgets;

use yii\base\Widget;

class CoolComboBoxWidget extends Widget {
    public $htmlOptions;
    
    public function init() {
        parent::init();
        
        $this->getView()->registerCssFile(
                    isset($this->css) ? 
                        $this->css : 
                        directoryModule::getCSSPath().'/cool-combo-box.css');    
    }
    
    public function run() {
        
    }
}
