<?php

namespace app\modules\directory\models\search;

use yii\base\Model;

abstract class FilterModelBase extends Model {
    protected $_dataProvider;

    abstract public function search();

    public function buildModels() {
        return $this->_dataProvider->getModels();
    }

    public function getDataProvider() {
        return $this->_dataProvider;
    }
}