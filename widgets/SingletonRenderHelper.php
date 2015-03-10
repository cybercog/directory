<?php

namespace app\modules\directory\widgets;

use yii\base\Widget;


class SingletonRenderHelper extends Widget {
    protected static $globalViews = [];
    protected static $globalhtmls = [];
    public $viewsRequire;
    public $htmlRequire;
    
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
        
        if(isset($this->htmlRequire) &&
                is_array($this->htmlRequire)) {
            foreach ($this->htmlRequire as $key=>$html) {
                if(!isset(self::$globalhtmls[$key])) {
                    self::$globalhtmls[$key] = true;
                    $render .= $html;
                }
            }
        }
        
        return $render;
    }
}
