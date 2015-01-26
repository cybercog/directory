<?php

namespace app\modules\directory\widgets;

use yii\base\Widget;

class RecordList extends Widget {
    public $Records = null;
    public $outDBInfo = false;
    public $expandPropertiesDefault = true;
    public $expandProperiesCount = false;
    public $expandPopup = false;

    public function run() {
        $data = \app\modules\directory\helpers\dbStringParser::ParseStringL2($this->Records);
        $data_out = [];
        
        if($data) {
            foreach ($data as $item) {
                if(isset($data[$item[2]])) {
                    
                }
            }
        }
        
        /*
        if(isset($this->Records) && is_string($this->Records) && (mb_strlen($this->Records) > 0)) {
            $exp = mb_split(chr(4), $this->Records);
            if(count($exp) > 0) {
                foreach ($exp as $data_item) {
                    $data[] = mb_split(chr(3), $data_item);
                    $data_items = mb_split(chr(3), $record);
                    if(isset($data[$data_items[2]])) {
                        $temp = $data[$data_items[2]];
                        $counter = $data_items[2];
                        while (isset($temp)) {
                            ++$counter;
                            if(isset($data[$counter])) {
                                $temp2 = $temp;
                                $temp = $data[$counter];
                                $data[$counter] = $temp2;
                            } else {
                                $data[$counter] = $temp;
                                unset($temp);
                            }
                        }
                    }
                    $data[$data_items[2]] = ['data_id' => $data[0], 'visible' => $data[1], 
                        'position' => $data[2], 'sub_position' => $data[3],
                        'type' => $data[4], 'value' => $data[6],
                        'text' => $data[7], 'description' => $data[8],
                        'data_visible' => $data[5]];
                }
            }
        }*/
        
        return $this->render('record-list', ['records' => $data_out]);
    }
}
