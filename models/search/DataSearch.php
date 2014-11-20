<?php

namespace app\modules\directory\models\search;

use app\modules\directory\models\db\Data;
use app\modules\directory\models\db\Types;

class DataSearch extends FilterModelBase {
    public $description;
    public $value;
    public $visible;
    public $type_name;
    public $type_type;
    
    public function rules() {
        return [
            [['description', 'value', 'type_name'], 'safe'],
            ['visible', 'yii\validators\RangeValidator', 'range' => ['Y', 'N']],
            ['type_type', 'yii\validators\RangeValidator', 'range' => ['string', 'text', 'image', 'file']]
        ];
    }
    
    public function search() {
        $query = Data::find()->joinWith('type');
        
        $query->andFilterWhere(['like', 'description', $this->description]);
        $query->andFilterWhere(['like', 'value', $this->value]);
        $query->andFilterWhere(['like', Types::tableName().'.name', $this->type_name]);
        $query->andFilterWhere(['like', Types::tableName().'.type', $this->type_type]);
        $query->andFilterWhere(['visible' => $this->visible]);
        
        $this->_dataProvider = new \yii\data\ActiveDataProvider([
                    'query' => $query, 
                    'pagination' => ['pageSize' => $this->pagination]]);
        
        $this->_dataProvider->sort->attributes['type_name'] = 
            ['asc' => [Types::tableName().'.name' => SORT_ASC], 'desc' => [Types::tableName().'.name' => SORT_DESC]];
        $this->_dataProvider->sort->attributes['type_type'] = 
            ['asc' => [Types::tableName().'.type' => SORT_ASC], 'desc' => [Types::tableName().'.type' => SORT_DESC]];
                
        return $this->_dataProvider;
    }
}
