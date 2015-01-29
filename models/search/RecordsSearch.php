<?php

namespace app\modules\directory\models\search;

use app\modules\directory\models\db\views\LowerRecords;
use app\modules\directory\models\db\Directories;

class RecordsSearch extends FilterModelBase {
    public $visible;
    public $value;
    public $directories;
    
    public function rules() {
        $directories = Directories::find()->all();
        $directoriesRange = [];
        foreach ($directories as $directory) {
            $directoriesRange[] = $directory->name;
        }
        
        return [
            ['value', 'safe'],
            ['visible', 'yii\validators\RangeValidator', 'range' => ['Y', 'N']],
            ['directories', 'yii\validators\RangeValidator', 'range' => $directoriesRange],
        ];
    }
    
    public function search() {
        $query = LowerRecords::find();
        
        $query->addOrderBy(['id' => SORT_ASC]);
        
        $query->andFilterWhere(['visible' => $this->visible]);
        $query->andFilterWhere(['like', 'directories_id', $this->directories]);
        $query->andFilterWhere(['like', 'data_lower', mb_strtolower($this->value, 'UTF-8')]);
        
        $this->_dataProvider = new \yii\data\ActiveDataProvider([
                    'query' => $query, 
                    'pagination' => ['pageSize' => $this->pagination]]);
        
        $this->_dataProvider->sort->attributes['value'] = 
                ['asc' => ['id' => SORT_ASC], 'desc' => ['id' => SORT_DESC]];
        
        return $this->_dataProvider;
    }
}
