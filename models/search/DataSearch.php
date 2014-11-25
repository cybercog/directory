<?php

namespace app\modules\directory\models\search;

use yii\db\ActiveRecord;

class LowerData extends ActiveRecord {
    public static function tableName() {
        return 'data_tolower_v';
    }
}

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
        $query = LowerData::find();
        
        $query->andFilterWhere(['like', 'description', mb_strtolower($this->description, 'UTF-8')]);
        $query->andFilterWhere(['like', 'value', mb_strtolower($this->value, 'UTF-8')]);
        $query->andFilterWhere(['like', 'type_name', mb_strtolower($this->type_name, 'UTF-8')]);
        $query->andFilterWhere(['type_type' => $this->type_type]);
        $query->andFilterWhere(['visible' => $this->visible]);
        
        $this->_dataProvider = new \yii\data\ActiveDataProvider([
                    'query' => $query, 
                    'pagination' => ['pageSize' => $this->pagination]]);
        
        return $this->_dataProvider;
    }
}
