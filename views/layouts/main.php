<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\helpers\Url;
use app\modules\directory\directoryModule;

/* @var $this \yii\web\View */
/* @var $content string */


AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="directory-layout directory-base-font">
<?php $this->beginBody() ?>


    <?php if(false) { ?><style><?php } ob_start(); ?>
        .directory-search-button-icon {
            background: url(<?= directoryModule::getPublishImage('/search-button-icon.png'); ?>) no-repeat;
            padding-left: 20px;
        }
        .directory-types-edit-button-icon {
            background: url(<?= directoryModule::getPublishImage('/types16.png'); ?>) no-repeat;
            padding-left: 20px;
        }
        .directory-data-edit-button-icon {
            background: url(<?= directoryModule::getPublishImage('/data16.png'); ?>) no-repeat;
            padding-left: 20px;
        }
        .directory-record-edit-button-icon {
            background: url(<?= directoryModule::getPublishImage('/record16.png'); ?>) no-repeat;
            padding-left: 20px;
        }
        .directory-directory-edit-button-icon {
            background: url(<?= directoryModule::getPublishImage('/directory16.png'); ?>) no-repeat;
            padding-left: 20px;
        }
        .directory-hierarchy-edit-button-icon {
            background: url(<?= directoryModule::getPublishImage('/hierarchy16.png'); ?>) no-repeat;
            padding-left: 20px;
        }
        .directory-statictic-edit-button-icon {
            background: url(<?= directoryModule::getPublishImage('/stat16.png'); ?>) no-repeat;
            padding-left: 20px;
        }
        .directory-add-button-icon {
            background: url(<?= directoryModule::getPublishImage('/add.png'); ?>) no-repeat;
            padding-left: 20px;
        }
        .directory-new-button-icon {
            background: url(<?= directoryModule::getPublishImage('/new.png'); ?>) no-repeat;
            padding-left: 20px;
        }
        .directory-update-button-icon {
            background: url(<?= directoryModule::getPublishImage('/update.png'); ?>) no-repeat;
            padding-left: 20px;
        }
    <?php $this->registerCss(ob_get_clean()); if(false) { ?></style><?php } ?>
    
    <div class="directory-all-content-wrap">
        <nav>
            <div class="directory-nav-panel">
                <table class="directory-modal-table directory-stretch-bar">
                    <tr>
                        <td class="directory-min-width<?= (\Yii::$app->controller->action->uniqueId == 'directory/search/index') ? ' directory-panel-item-cell-selected': ''?>">
                            <div class="directory-nav-panel-item <?= (\Yii::$app->controller->action->uniqueId == 'directory/search/index') ? ' directory-nav-panel-item-button-selected': 'directory-nav-panel-item-button'?>">
                                <nobr>
                                    <?php if(\Yii::$app->controller->action->uniqueId != 'directory/search/index') { ?>
                                    <a href="<?= Url::toRoute(['/directory/search/index'])?>" class="directory-a-no-decoration">
                                        <span class="directory-search-button-icon">Поиск</span>
                                    </a>
                                    <?php } else { ?>
                                    <span class="directory-search-button-icon">Поиск</span>
                                    <?php } ?>
                                </nobr>
                            </div>
                        </td>
                        <td>&nbsp;</td>
                        <td class="directory-min-width">
                            <div class="directory-edit-panel">
                                <table>
                                    <tr>
                                        <td class="directory-min-width">
                                            <nobr>
                                                <strong>Редактирование:</strong>
                                            </nobr>
                                        </td>
                                        <td class="directory-min-width<?= (\Yii::$app->controller->action->uniqueId == 'directory/edit/types') ? ' directory-panel-item-cell-selected': ''?>">
                                            <div class="directory-edit-panel-item <?= (\Yii::$app->controller->action->uniqueId == 'directory/edit/types') ? 'directory-nav-panel-item-button-selected': 'directory-edit-panel-item-button'?>">
                                                <nobr>
                                                    <?php if(\Yii::$app->controller->action->uniqueId != 'directory/edit/types') { ?>
                                                    <a href="<?= Url::toRoute(['/directory/edit/types'])?>">
                                                        <span class="directory-types-edit-button-icon">Типы</span>
                                                    </a>
                                                    <?php } else { ?>
                                                    <span class="directory-types-edit-button-icon">Типы</span>
                                                    <?php } ?>
                                                </nobr>
                                            </div>
                                        </td>
                                        <td class="directory-min-width<?= (\Yii::$app->controller->action->uniqueId == 'directory/edit/data') ? ' directory-panel-item-cell-selected': ''?>">
                                            <div class="directory-edit-panel-item <?= (\Yii::$app->controller->action->uniqueId == 'directory/edit/data') ? 'directory-nav-panel-item-button-selected': 'directory-edit-panel-item-button'?>">
                                                <nobr>
                                                    <?php if(\Yii::$app->controller->action->uniqueId != 'directory/edit/data') { ?>
                                                    <a href="<?= Url::toRoute(['/directory/edit/data'])?>">
                                                        <span class="directory-data-edit-button-icon">Данные</span>
                                                    </a>
                                                    <?php } else { ?>
                                                    <span class="directory-data-edit-button-icon">Данные</span>
                                                    <?php } ?>
                                                </nobr>
                                            </div>
                                        </td>
                                        <td class="directory-min-width<?= (\Yii::$app->controller->action->uniqueId == 'directory/edit/directories') ? ' directory-panel-item-cell-selected': ''?>">
                                            <div class="directory-edit-panel-item  <?= (\Yii::$app->controller->action->uniqueId == 'directory/edit/directories') ? 'directory-nav-panel-item-button-selected': 'directory-edit-panel-item-button'?>">
                                                <nobr>
                                                    <?php if(\Yii::$app->controller->action->uniqueId != 'directory/edit/directories') { ?>
                                                    <a href="<?= Url::toRoute(['/directory/edit/directories'])?>">
                                                        <span class="directory-directory-edit-button-icon">Каталоги</span>
                                                    </a>
                                                    <?php } else { ?>
                                                    <span class="directory-directory-edit-button-icon">Каталоги</span>
                                                    <?php } ?>
                                                </nobr>
                                            </div>
                                        </td>
                                        <td class="directory-min-width<?= (\Yii::$app->controller->action->uniqueId == 'directory/edit/records') ? ' directory-panel-item-cell-selected': ''?>">
                                            <div class="directory-edit-panel-item <?= (\Yii::$app->controller->action->uniqueId == 'directory/edit/records') ? 'directory-nav-panel-item-button-selected': 'directory-edit-panel-item-button'?>">
                                                <nobr>
                                                    <?php if(\Yii::$app->controller->action->uniqueId != 'directory/edit/records') { ?>
                                                    <a href="<?= Url::toRoute(['/directory/edit/records'])?>">
                                                        <span class="directory-record-edit-button-icon">Записи</span>
                                                    </a>
                                                    <?php } else { ?>
                                                    <span class="directory-record-edit-button-icon">Записи</span>
                                                    <?php } ?>
                                                </nobr>
                                            </div>
                                        </td>
                                        <td class="directory-min-width<?= (\Yii::$app->controller->action->uniqueId == 'directory/edit/hierarchies') ? ' directory-panel-item-cell-selected': ''?>">
                                            <div class="directory-edit-panel-item <?= (\Yii::$app->controller->action->uniqueId == 'directory/edit/hierarchies') ? 'directory-nav-panel-item-button-selected': 'directory-edit-panel-item-button'?>">
                                                <nobr>
                                                    <?php if(\Yii::$app->controller->action->uniqueId != 'directory/edit/hierarchies') { ?>
                                                    <a href="<?= Url::toRoute(['/directory/edit/hierarchies'])?>">
                                                        <span class="directory-hierarchy-edit-button-icon">Иерархии</span>
                                                    </a>
                                                    <?php } else { ?>
                                                    <span class="directory-hierarchy-edit-button-icon">Иерархии</span>
                                                    <?php } ?>
                                                </nobr>
                                            </div>
                                        </td>
                                        <td class="directory-min-width<?= (\Yii::$app->controller->action->uniqueId == 'directory/edit/statistic') ? ' directory-panel-item-cell-selected': ''?>">
                                            <div class="directory-edit-panel-item <?= (\Yii::$app->controller->action->uniqueId == 'directory/edit/statistic') ? 'directory-nav-panel-item-button-selected': 'directory-edit-panel-item-button'?>">
                                                <nobr>
                                                    <?php if(\Yii::$app->controller->action->uniqueId != 'directory/edit/statistic') { ?>
                                                    <a href="<?= Url::toRoute(['/directory/edit/statistic'])?>">
                                                        <span class="directory-statictic-edit-button-icon">Статистика</span>
                                                    </a>
                                                    <?php } else { ?>
                                                    <span class="directory-statictic-edit-button-icon">Статистика</span>
                                                    <?php } ?>
                                                </nobr>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </nav>
        <div class="directory-content">
            <?= $content ?>
        </div>
    </div>

    <?php if(directoryModule::$SETTING['showFooter']) {?>
    <footer class="directory-footer">
        <div>
            <p class="directory-float-left">&copy; Romashka this is - <span class="directory-my-name-class">клёвый справочник</span></p>
            <p class="directory-float-right"><?= Yii::powered() ?></p>
        </div>
    </footer>
    <?php } ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
