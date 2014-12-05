<?php

namespace app\modules\directory\models\search;

use app\modules\directory\models\db\Records;

class RecordsSearch extends FilterModelBase {
    public $visible;
    public $value;
    
    public function rules() {
        return [
            ['value', 'safe'],
            ['visible', 'yii\validators\RangeValidator', 'range' => ['Y', 'N']],
        ];
    }
    
    public function search() {
        $query = Records::find();
        
        $query->addOrderBy(['id' => SORT_ASC]);
        
        $query->andFilterWhere(['visible' => $this->visible]);
        
        $this->_dataProvider = new \yii\data\ActiveDataProvider([
                    'query' => $query, 
                    'pagination' => ['pageSize' => $this->pagination]]);
        
        $this->_dataProvider->sort->attributes['value'] = 
                ['asc' => ['id' => SORT_ASC], 'desc' => ['id' => SORT_DESC]];
        
        return $this->_dataProvider;
    }
}
