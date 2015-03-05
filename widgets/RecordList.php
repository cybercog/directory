<?php

namespace app\modules\directory\widgets;

use yii\base\Widget;

class RecordList extends Widget {
    public $Records = null;
    public $template = 'record-list';

    public function run() {
        $data = \app\modules\directory\helpers\dbStringParser::ParseStringL2($this->Records);
        $data_out = [];
        $flat_data_out = [];
        
        if($data) {
            foreach ($data as $item) {
                $new_item = ['data_id' => $item[0], 'visible' => $item[1], 
                        'position' => $item[2], 'sub_position' => $item[3],
                        'type' => $item[4], 'value' => $item[6],
                        'text' => $item[7], 'description' => $item[8],
                        'data_visible' => $item[5], 'type_name' => $item[9], 'type_description' => $item[10]];
                $flat_data_out[] = $new_item;
                
                if(isset($data_out[$new_item['position']])) {
                    if(isset($data_out[$new_item['position']][$new_item['sub_position']])) {
                        $sub_position = $new_item['sub_position'];
                        while(true) {
                            ++$sub_position;
                            if(!isset($data_out[$new_item['position']][$sub_position])) {
                                $data_out[$new_item['position']][$sub_position] = $new_item;
                                break;
                            }
                        }
                    } else {
                        $data_out[$new_item['position']][$new_item['sub_position']] = $new_item;
                    }
                } else {
                    $data_out[$new_item['position']] = [$new_item['sub_position'] => $new_item];
                }
            }
        }
        
        return $this->render($this->template, ['records' => $data_out, 'flat_records' => $flat_data_out]);
    }
}
