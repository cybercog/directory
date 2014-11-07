<?php

namespace app\modules\directory\models\search;

use app\modules\directory\models\db\Data;

class DataSearch extends FilterModelBase {
    public function search() {
        $query = Data::find();
        
        $this->_dataProvider = new \yii\data\ActiveDataProvider([
                    'query' => $query, 
                    'pagination' => ['pageSize' => $this->pagination]]);
                
        return $this->_dataProvider;
    }
}
