<?php

namespace app\modules\directory\controllers;

use yii\web\Controller;
use app\modules\directory\models\forms\SearchForm;

class SearchController extends Controller {
    public function __construct($id, $module, $config = array()) {
        parent::__construct($id, $module, $config);
        $this->layout = 'main';
    }
    
    public function actionIndex() {
        return $this->render('index', ['model' => new SearchForm]);
    }

    public function actionSearch() {
        return $this->render('search');
    }
}

