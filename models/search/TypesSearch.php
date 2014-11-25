<?php

namespace app\modules\directory\models\search;

use yii\db\ActiveRecord;

class LowerTypes extends ActiveRecord {
    public static function tableName() {
        return 'types_tolower_v';
    }
}

class TypesSearch extends FilterModelBase {
    public $name;
    public $type;
    public $description;
    public $validate;
    
    public function rules() {
        return [
            [['name', 'description', 'validate'], 'safe'],
            ['type', 'yii\validators\RangeValidator', 'range' => ['string', 'text', 'image', 'file']]
        ];
    }
    
    public function search() {
        $query = LowerTypes::find();

        $query->andFilterWhere(['like', 'name', mb_strtolower($this->name, 'UTF-8')]);
        $query->andFilterWhere(['type' => $this->type]);
        $query->andFilterWhere(['like', 'description', mb_strtolower($this->description, 'UTF-8')]);
        $query->andFilterWhere(['like', 'validate', mb_strtolower($this->validate, 'UTF-8')]);

        $this->_dataProvider = new \yii\data\ActiveDataProvider([
                    'query' => $query, 
                    'pagination' => ['pageSize' => $this->pagination]]);
                
        return $this->_dataProvider;
    }
}
