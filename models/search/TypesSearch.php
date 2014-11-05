<?php

namespace app\modules\directory\models\search;

use app\modules\directory\models\db\Types;

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
        $query = Types::find();

        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['type' => $this->type]);
        $query->andFilterWhere(['like', 'description', $this->description]);
        $query->andFilterWhere(['like', 'validate', $this->validate]);

        $this->_dataProvider = new \yii\data\ActiveDataProvider([
                    'query' => $query, 
                    'pagination' => ['pageSize' => 20]]);
                
        return $this->_dataProvider;
    }
}
