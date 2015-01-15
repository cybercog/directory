<?php

namespace app\modules\directory\widgets;

use yii\base\Widget;
use app\modules\directory\models\forms\SearchForm;
use app\modules\directory\directoryModule;

class SearchBarWidget extends Widget {
    public $css;
    public $view;

    public function init() {
        parent::init();
        
        $this->getView()->registerCssFile(directoryModule::getPublishCSS());    
    }
    
    public function run() {
        return $this->render(isset($this->view) ? $this->view : 'search-bar', 
                    [
                        'model' => new SearchForm,
                        ]);
    }
}
