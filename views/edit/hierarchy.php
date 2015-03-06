<?php 
use yii\helpers\Url;
use app\modules\directory\directoryModule;
use yii\widgets\Breadcrumbs;
use yii\web\View;

use app\modules\directory\widgets\SingletonRenderHelper;
use app\modules\directory\helpers\ajaxJSONResponseHelper;

$this->title = directoryModule::ht('search', 'Directory').' - '.directoryModule::ht('edit', 'Hierarhy');
$uid = mt_rand(0, mt_getrandmax());
?>

<?php if(false) { ?><style><?php } ob_start(); ?>
    h1.directory-records-h1-icon {
        background: url(<?= directoryModule::getPublishImage('/hierarchy32.png'); ?>) no-repeat;
        padding-left: 36px;
    }
<?php $this->registerCss(ob_get_clean()); if(false) { ?></style><?php } ?>

<h1 class="directory-h1 directory-records-h1-icon"><?= directoryModule::ht('edit', 'Hierarhy')?> - <?=$hierarchy->name?></h1>

<?= Breadcrumbs::widget([
    'links' => [
        [
            'label' => directoryModule::ht('search', 'Search'),
            'url' => Url::toRoute('/directory/search/index')
        ],
        [
            'label' => directoryModule::ht('edit', 'Hierarhies'),
            'url' => Url::toRoute('/directory/edit/hierarchies')
        ],
        $hierarchy->name
    ]
    ]) ?>


<?= app\modules\directory\widgets\HierarchyWidget::widget(['hierarchy'=>$hierarchy]); ?>