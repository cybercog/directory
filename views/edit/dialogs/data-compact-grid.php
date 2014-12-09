<?php 

use app\modules\directory\directoryModule;
use app\modules\directory\helpers\dataGridCellViewHelper;
use app\modules\directory\helpers\boolSaveHelper;
use yii\helpers\Url;

if(!isset($dataDataModel)) {
    $dataDataModel = new \app\modules\directory\models\search\DataSearch();
}

$dataDataModel->pagination = 7;

?>

            <?php yii\widgets\Pjax::begin([
                'timeout' => directoryModule::$SETTING['pjaxDefaultTimeout'], 
                'enablePushState' => false, 
                'enableReplaceState' => false, 
                'id' => 'dataCompactGridPjaxWidget'.$uid,
                'linkSelector' => '#dataCompactGridPjaxWidget'.$uid.' a:not(.directory-data-file-download)'
                ]);?>
            <?= yii\grid\GridView::widget([
                'id' => 'dataCompactGridWidget'.$uid,
                'dataProvider' => $dataDataModel->search(),
                'filterModel' => $dataDataModel,
                'filterUrl' => Url::toRoute(['/directory/edit/data']),
                'columns' => [
                    [
                        'class' => 'yii\grid\DataColumn',
                        'headerOptions' => ['class' => 'directory-min-width'],
                        'format' => 'raw',
                        'attribute' => 'type_name',
                        'filterInputOptions' => ['class' => 'directory-stretch-bar directory-grid-filter-control'],
                        'label' => directoryModule::ht('edit', 'Type name'),
                        'value' => function($data) {
                            $row = $data->attributes;
                            $row['visible'] = boolSaveHelper::string2boolean($row['visible']);
                            return dataGridCellViewHelper::getTextString($data->original_type_name).
                                    dataGridCellViewHelper::getDataTypeString($data->type_type).
                                    '<div class="directory-hide-element directory-row-data">'.
                                    json_encode($row).'</div>';
                        }
                    ],
                    [
                        'class' => 'yii\grid\DataColumn',
                        'format' => 'raw',
                        'attribute' => 'value',
                        'filterInputOptions' => ['class' => 'directory-stretch-bar directory-grid-filter-control'],
                        'label' => directoryModule::ht('edit', 'Value'),
                        'value' => function($data) {
                            return dataGridCellViewHelper::getValueDataString($data->type_type, $data->original_value, $data->original_text);
                        }
                    ],
                    [
                        'class' => 'yii\grid\DataColumn',
                        'format' => 'raw',
                        'attribute' => 'description',
                        'filterInputOptions' => ['class' => 'directory-stretch-bar directory-grid-filter-control'],
                        'label' => directoryModule::ht('edit', 'Description'),
                        'value' => function($data) {
                            return dataGridCellViewHelper::getTextString($data->original_description);
                        }
                    ],
                    [
                        'class' => 'yii\grid\DataColumn',
                        'headerOptions' => ['class' => 'directory-min-width'],
                        'format' => 'raw',
                        'attribute' => 'visible',
                        'filterInputOptions' => ['class' => 'directory-stretch-bar directory-grid-filter-control'],
                        'filter' => ['Y' => directoryModule::ht('edit', 'show'), 
                                                'N' => directoryModule::ht('edit', 'hide')],
                        'label' => directoryModule::ht('edit', 'Visible'),
                        'value' => function($data) {
                            return dataGridCellViewHelper::getVisibleFlagString($data->visible);
                        }
                    ]
                ]
            ]) ?>
            <?php yii\widgets\Pjax::end(); ?>
