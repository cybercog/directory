<?php

namespace app\modules\directory\widgets;

use yii\base\Widget;

class DirectoryList extends Widget {
    public $directories = null;

    public function run() {
        $data = [];

        if(isset($this->directories) && is_string($this->directories) && mb_strlen($this->directories)) {
            $exp = mb_split(chr(4), $this->directories);
            if(count($exp) > 0) {
                foreach ($exp as $dir) {
                    $dir_items = mb_split(chr(3), $dir);
                    if(count($dir_items) > 0) {
                        $data[] = ['id' => $dir_items[0], 'record_visible' => $dir_items[1], 
                            'visible' => $dir_items[2], 'name' => $dir_items[3], 'description' => $dir_items[4]];
                    }
                }
            }
        }
        
        return $this->render('directory-list', ['directories' => $data]);
    }
}
