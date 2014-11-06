<?php 

use app\modules\directory\directoryModule;
use app\modules\directory\helpers\typesViewHelper;

?>


            <?php yii\widgets\Pjax::begin(['timeout' => 30000, 'id' => 'typesGridPjaxWidget']); ?>
            <?= yii\grid\GridView::widget([
                'id' => 'typesGridWidget',
                'dataProvider' => $dataModel->search(),
                'filterModel' => $dataModel,
                'columns' => [
                    [
                        'class' => 'yii\grid\DataColumn',
                        'contentOptions' => ['class' => 'directory-min-width'],
                        'format' => 'raw',
                        'attribute' => 'name',
                        'filterInputOptions' => ['class' => 'directory-stretch-bar directory-grid-filter-control'],
                        'label' => directoryModule::t('edit', 'Name'),
                        'value' => function($data) {
                            return typesViewHelper::getNameString($data);
                        }
                    ],
                    [
                        'class' => 'yii\grid\DataColumn',
                        'contentOptions' => ['class' => 'directory-min-width'],
                        'filterInputOptions' => ['class' => 'directory-stretch-bar directory-grid-filter-control'],
                        'format' => 'raw',
                        'attribute' => 'type',
                        'filter' => ['string' => directoryModule::t('edit', 'string'), 
                                                'text' => directoryModule::t('edit', 'text'), 
                                                'image' => directoryModule::t('edit', 'image'), 
                                                'file' => directoryModule::t('edit', 'file')],
                        'label' => directoryModule::t('edit', 'Type'),
                        'value' => function($data) {
                            return typesViewHelper::getTypeString($data);
                        }
                    ],
                    [
                        'class' => 'yii\grid\DataColumn',
                        'filterInputOptions' => ['class' => 'directory-stretch-bar directory-grid-filter-control'],
                        'format' => 'raw',
                        'attribute' => 'description',
                        'label' => directoryModule::t('edit', 'Description'),
                        'value' => function($data) {
                            return typesViewHelper::getTextString($data['description']);
                        }
                    ],
                    [
                        'class' => 'yii\grid\DataColumn',
                        'filterInputOptions' => ['class' => 'directory-stretch-bar directory-grid-filter-control'],
                        'format' => 'raw',
                        'attribute' => 'validate',
                        'label' => directoryModule::t('edit', 'Validate'),
                        'value' => function($data) {
                            return typesViewHelper::getTextString($data['validate']);
                        }
                    ],
                    [
                        'class' => 'yii\grid\DataColumn',
                        'contentOptions' => ['class' => 'directory-min-width'],
                        'format' => 'raw',
                        'value' => function($data) {
                            return '<nobr>'
                                    . '<button class="directory-edit-type-button directory-small-button" '
                                    . 'title="'.directoryModule::t('edit', 'Edit data type').'"><img src="'.
                                    directoryModule::getPublishPath('/img/edit-item.png').
                                    '" /></button><button class="directory-delete-type-button directory-small-button" '
                                    . 'title="'.directoryModule::t('edit', 'Delete data type').'"><img src="'.
                                    directoryModule::getPublishPath('/img/delete-item.png')
                                    .'" /></button></nobr>';
                        },
                    ]
                ]
            ]) ?>
            <?php yii\widgets\Pjax::end(); ?>
