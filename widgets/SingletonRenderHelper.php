<?php

namespace app\modules\directory\widgets;

use yii\base\Widget;


class SingletonRenderHelper extends Widget {
    protected static $globalViews = [];
    public $viewsRequire;
    
    public function run() {
        $require = function($view) {
            if(!isset(self::$globalViews[$view['name']])) {
                self::$globalViews[$view['name']] = $view;
                $this->render($view['name'], isset($view['params']) ? $view['params'] : []);
            }
        };
        
        if(isset($this->viewsRequire) && 
                is_array($this->viewsRequire)) {
            foreach ($this->viewsRequire as $view) {
                $require($view);
            }
        }
    }
}
