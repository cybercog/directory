<?php

namespace app\modules\directory\models\search;

use yii\base\Model;

abstract class FilterModelBase extends Model {
    protected $_dataProvider;
    public $pagination = 20;

    public function search() {
        return $this->_dataProvider;
    }

    public function getDataProvider() {
        return $this->_dataProvider;
    }
}