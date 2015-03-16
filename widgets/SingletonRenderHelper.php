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
                return $this->render($view['name'], isset($view['params']) ? $view['params'] : []);
            }
            
            return '';
        };
        
        $render = '';
        
        if(isset($this->viewsRequire) && 
                is_array($this->viewsRequire)) {
            foreach ($this->viewsRequire as $view) {
                $render .= $require($view);
            }
        }
        
        return $render;
    }
}
