<?php

namespace app\modules\directory\models\search;

use app\modules\directory\models\db\views\LowerHierarchies;
use app\modules\directory\models\db\Directories;

class HierarhiesSearch extends FilterModelBase {
    public $name;
    public $description;
    public $visible;
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
            [['name', 'description'], 'safe'],
            ['visible', 'yii\validators\RangeValidator', 'range' => ['Y', 'N']],
            ['directories', 'yii\validators\RangeValidator', 'range' => $this->directoriesRange],
        ];
    }
    
    public function search() {
        $query = LowerHierarchies::find();
        
        $query->andFilterWhere(['like', 'name', mb_strtolower($this->name, 'UTF-8')]);
        $query->andFilterWhere(['like', 'description', mb_strtolower($this->description, 'UTF-8')]);
        $query->andFilterWhere(['visible' => $this->visible]);
        $query->andFilterWhere(['like', 'directories_id', $this->directories]);

        $this->_dataProvider = new \yii\data\ActiveDataProvider([
                    'query' => $query, 
                    'pagination' => ['pageSize' => $this->pagination]]);
        
        if(\Yii::$app->request->get($this->_dataProvider->sort->sortParam, false) === false) {
            $query->addOrderBy(['id' => SORT_ASC]);
        }
        
        $this->_dataProvider->sort->attributes = 
            [
                'visible' => ['asc' => ['visible' => SORT_ASC], 'desc' => ['visible' => SORT_DESC]],
                'name' => ['asc' => ['name' => SORT_ASC], 'desc' => ['name' => SORT_DESC]],
                'description' => ['asc' => ['description' => SORT_ASC], 'desc' => ['description' => SORT_DESC]],
            ];
        
        return $this->_dataProvider;
    }
}
