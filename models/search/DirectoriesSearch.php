<?php

namespace app\modules\directory\models\search;

use app\modules\directory\models\db\views\LowerDirectories;

class DirectoriesSearch  extends FilterModelBase {
    public $name;
    public $description;
    public $visible;
    
    public function rules() {
        return [
            [['name', 'description'], 'safe'],
            ['visible', 'yii\validators\RangeValidator', 'range' => ['Y', 'N']],
        ];
    }
    
    public function search() {
        $query = LowerDirectories::find();
        
        $query->addOrderBy(['id' => SORT_ASC]);

        $query->andFilterWhere(['like', 'name', mb_strtolower($this->name, 'UTF-8')]);
        $query->andFilterWhere(['like', 'description', mb_strtolower($this->description, 'UTF-8')]);
        $query->andFilterWhere(['visible' => $this->visible]);

        $this->_dataProvider = new \yii\data\ActiveDataProvider([
                    'query' => $query, 
                    'pagination' => ['pageSize' => $this->pagination]]);
                
        return $this->_dataProvider;
    }
}
