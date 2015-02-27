<?php 

use app\modules\directory\directoryModule;
use app\modules\directory\helpers\dataGridCellViewHelper;
use app\modules\directory\helpers\boolSaveHelper;
use app\modules\directory\widgets\DirectoryList;
use app\modules\directory\models\db\Directories;
use yii\helpers\Url;

$directories = Directories::find()->all();
$directoriesFilter = [];
foreach ($directories as $directory) {
    $directoriesFilter[$directory->id] = $directory->name;
}

?>


            <?php yii\widgets\Pjax::begin([
                'timeout' => directoryModule::$SETTING['pjaxDefaultTimeout'], 
                'enablePushState' => false, 
                'enableReplaceState' => false,
                'id' => 'hierarchiesGridPjaxWidget']); ?>
            <?= yii\grid\GridView::widget([
                'id' => 'hierarchiesGridWidget',
                'dataProvider' => $dataModel->search(),
                'filterModel' => $dataModel,
                'columns' => [
                    [
                        'class' => 'yii\grid\DataColumn',
                        'format' => 'raw',
                        'attribute' => 'name',
                        'filterInputOptions' => ['class' => 'directory-stretch-bar directory-grid-filter-control'],
                        'label' => directoryModule::ht('edit', 'Title'),
                        'value' => function($data) {
                            $row = $data->attributes;
                            $row['visible'] = boolSaveHelper::string2boolean($row['visible']);
                            return '<a href="'.Url::toRoute(['/directory/edit/hierarchy', 'id' => $data->id]).'">'.$data->original_name.
                                    '</a><div class="directory-hide-element directory-row-data data-data">'.
                                    json_encode($row).'</div>';
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
                        'attribute' => 'directories',
                        'filterInputOptions' => ['class' => 'directory-stretch-bar directory-grid-filter-control'],
                        'filter' => $directoriesFilter,
                        'label' => directoryModule::ht('edit', 'Directories'),
                        'value' => function($data) {
                            return DirectoryList::widget(['directories' => $data->directories]);
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
            ])?>
            <?php yii\widgets\Pjax::end(); ?>
