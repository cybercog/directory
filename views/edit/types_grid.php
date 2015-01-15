<?php 

use app\modules\directory\directoryModule;
use app\modules\directory\helpers\dataGridCellViewHelper;

?>


            <?php yii\widgets\Pjax::begin([
                'timeout' => directoryModule::$SETTING['pjaxDefaultTimeout'], 
                'enablePushState' => false, 
                'enableReplaceState' => false, 
                'id' => 'typesGridPjaxWidget']); ?>
            <?= yii\grid\GridView::widget([
                'id' => 'typesGridWidget',
                'dataProvider' => $dataModel->search(),
                'filterModel' => $dataModel,
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
                            return dataGridCellViewHelper::getTextString($data->original_description);
                        }
                    ],
                    [
                        'class' => 'yii\grid\DataColumn',
                        'filterInputOptions' => ['class' => 'directory-stretch-bar directory-grid-filter-control'],
                        'format' => 'raw',
                        'attribute' => 'validate',
                        'label' => directoryModule::ht('edit', 'Validate'),
                        'value' => function($data) {
                            return dataGridCellViewHelper::getTextString($data->original_validate);
                        }
                    ],
                    [
                        'class' => 'yii\grid\DataColumn',
                        'headerOptions' => ['class' => 'directory-min-width'],
                        'format' => 'raw',
                        'value' => function($data) {
                            return '<nobr>'
                                    . '<button class="directory-edit-type-button directory-small-button" '
                                    . 'title="'.directoryModule::ht('edit', 'Edit data type').'"><img src="'.
                                    directoryModule::getPublishImage('/edit-item.png').
                                    '" /></button>&nbsp;<button class="directory-delete-type-button directory-small-button" '
                                    . 'title="'.directoryModule::ht('edit', 'Delete data type').'"><img src="'.
                                    directoryModule::getPublishImage('/delete-item.png')
                                    .'" /></button></nobr>';
                        },
                    ]
                ]
            ]) ?>
            <?php yii\widgets\Pjax::end(); ?>
