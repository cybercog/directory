<?php 
use yii\helpers\Url;
use app\modules\directory\directoryModule;
use yii\widgets\Breadcrumbs;
use yii\web\View;

use app\modules\directory\widgets\SingletonRenderHelper;
use app\modules\directory\helpers\ajaxJSONResponseHelper;

$this->title = directoryModule::ht('search', 'Directory').' - '.directoryModule::ht('edit', 'Statistic');
$uid = mt_rand(0, mt_getrandmax());
?>

<?php if(false) { ?><style><?php } ob_start(); ?>
    h1.directory-records-h1-icon {
        background: url(<?= directoryModule::getPublishImage('/stat32.png'); ?>) no-repeat;
        padding-left: 36px;
    }
<?php $this->registerCss(ob_get_clean()); if(false) { ?></style><?php } ?>

<h1 class="directory-h1 directory-records-h1-icon"><?= directoryModule::ht('edit', 'Statistic')?></h1>

<?= Breadcrumbs::widget([
    'links' => [
        [
            'label' => directoryModule::ht('search', 'Search'),
            'url' => Url::toRoute('/directory/search/index')
        ],
        directoryModule::ht('edit', 'Statistic')
    ]
    ]) ?>
