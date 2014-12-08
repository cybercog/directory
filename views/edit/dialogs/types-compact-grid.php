<?php 

use app\modules\directory\directoryModule;
use app\modules\directory\helpers\dataGridCellViewHelper;
use yii\helpers\Url;

if(!isset($typesDataModel)) {
    $typesDataModel = new \app\modules\directory\models\search\TypesSearch();
}

$typesDataModel->pagination = 7;

?>

            <?php yii\widgets\Pjax::begin([
                'timeout' => directoryModule::$SETTING['pjaxDefaultTimeout'], 
                'enablePushState' => false, 
                'enableReplaceState' => false, 
                'id' => 'typesCompactGridPjaxWidget'.$uid,
                ]);?>
            <?= yii\grid\GridView::widget([
                'id' => 'typesCompactGridWidget'.$uid,
                'dataProvider' => $typesDataModel->search(),
                'filterModel' => $typesDataModel,
                'filterUrl' => Url::toRoute(['/directory/edit/types']),
                'columns' => [
                    [
                        'class' => 'yii\grid\DataColumn',
                        'headerOptions' => ['class' => 'directory-min-width'],
                        'format' => 'raw',
                        'attribute' => 'name',
                        'filterInputOptions' => ['class' => 'directory-stretch-bar directory-grid-filter-control'],
                        'label' => directoryModule::ht('edit', 'Name'),
                        'value' => function($data) {
                            return dataGridCellViewHelper::getTextString($data->original_name).
                                    '<div class="directory-hide-element directory-row-data">'.
                                    json_encode($data->attributes).'</div>';
                        }
                    ],
                    [
                        'class' => 'yii\grid\DataColumn',
                        'headerOptions' => ['class' => 'directory-min-width'],
                        'filterInputOptions' => ['class' => 'directory-stretch-bar directory-grid-filter-control'],
                        'format' => 'raw',
                        'attribute' => 'type',
                        'filter' => ['string' => directoryModule::ht('edit', 'string'), 
                                                'text' => directoryModule::ht('edit', 'text'), 
                                                'image' => directoryModule::ht('edit', 'image'), 
                                                'file' => directoryModule::ht('edit', 'file')],
                        'label' => directoryModule::ht('edit', 'Type'),
                        'value' => function($data) {
                            return dataGridCellViewHelper::getDataTypeString($data->type);
                        }
                    ],
                    [
                        'class' => 'yii\grid\DataColumn',
                        'filterInputOptions' => ['class' => 'directory-stretch-bar directory-grid-filter-control'],
                        'format' => 'raw',
                        'attribute' => 'description',
                        'label' => directoryModule::ht('edit', 'Description'),
                        'value' => function($data) {
                            return dataGridCellViewHelper::getTextString($data['description']);
                        }
                    ]
                ]
            ]) ?>
            <?php yii\widgets\Pjax::end(); ?>
