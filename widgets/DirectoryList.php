<?php

namespace app\modules\directory\widgets;

use yii\base\Widget;

class DirectoryList extends Widget {
    public $directories = null;

    public function run() {
        $data = \app\modules\directory\helpers\dbStringParser::ParseStringL2($this->directories);
        $data_out = [];
        
        if($data) {
            foreach ($data as $dir_items) {
                $data_out[] = ['id' => $dir_items[0], 'record_visible' => $dir_items[1], 
                    'visible' => $dir_items[2], 'name' => $dir_items[3], 'description' => $dir_items[4]];
            }
        }
        
        return $this->render('directory-list', ['directories' => $data_out]);
    }
}
