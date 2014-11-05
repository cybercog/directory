<?php

namespace app\modules\directory\models\forms;

use yii\base\Model;

class SearchForm extends Model {
    public $query;
    public $directory;
    public $useHierarchy;
    
    public function rules() {
        return [
            [['query','directory'], 'safe'],
            ['useHierarchy', 'boolean']
        ];
    }
    
    /*public function attributeLabels() {
        return [
            'query' => Yii::t('directory.module', 'Поиск'),
            'directory' => Yii::t('directory.module', 'Справочник'),
            'useHierarchy' => Yii::t('directory.module', 'Использовать иерархическое представление'),
        ];
    }*/
}
