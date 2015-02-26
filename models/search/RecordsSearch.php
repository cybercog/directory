<?php

namespace app\modules\directory\models\search;

use app\modules\directory\models\db\views\LowerRecords;
use app\modules\directory\models\db\Directories;

class RecordsSearch extends FilterModelBase {
    public $visible;
    public $value;
    public $directories;
    
    private $directoriesRange = [];
    
    public function __construct( $config = [] ) {
        parent::__construct($config);

        $directories = Directories::find()->all();
        foreach ($directories as $directory) {
            $this->directoriesRange[] = $directory->name;
        }
    }
    
    public function rules() {
        return [
            ['value', 'safe'],
            ['visible', 'yii\validators\RangeValidator', 'range' => ['Y', 'N']],
            ['directories', 'yii\validators\RangeValidator', 'range' => $this->directoriesRange],
        ];
    }
    
    public function search() {
        $query = LowerRecords::find();
        
        $query->andFilterWhere(['visible' => $this->visible]);
        $query->andFilterWhere(['like', 'directories_id', $this->directories]);
        $query->andFilterWhere(['like', 'data_lower', mb_strtolower($this->value, 'UTF-8')]);
        
        $this->_dataProvider = new \yii\data\ActiveDataProvider([
                    'query' => $query, 
                    'pagination' => ['pageSize' => $this->pagination]]);
        
        if(\Yii::$app->request->get($this->_dataProvider->sort->sortParam, false) === false) {
            $query->addOrderBy(['id' => SORT_ASC]);
        }
        
        $this->_dataProvider->sort->attributes = 
            [
                'visible' => ['asc' => ['visible' => SORT_ASC], 'desc' => ['visible' => SORT_DESC]],
                'value' => ['asc' => ['id' => SORT_ASC], 'desc' => ['id' => SORT_DESC]]
            ];
        
        return $this->_dataProvider;
    }
}
