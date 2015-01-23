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
        return $this->render('record-list', ['records' => $this->Records]);
    }
}
