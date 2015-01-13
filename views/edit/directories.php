<?php 

use yii\web\View;
use yii\helpers\Html;
use app\modules\directory\directoryModule;
use yii\widgets\Breadcrumbs;

use yii\helpers\Url;
use app\modules\directory\widgets\SingletonRenderHelper;
use app\modules\directory\helpers\ajaxJSONResponseHelper;


$this->title = directoryModule::ht('search', 'Directory').' - '.directoryModule::ht('edit', 'Directories');

$uid = mt_rand(0, mt_getrandmax());

?>

<?= SingletonRenderHelper::widget(['viewsRequire' => [
    ['name' => '/helpers/ajax-post-helper'],
    ['name' => '/helpers/publish-result-css'],
    ['name' => '/helpers/publish-types-css'],
    //['name' => '/helpers/ajax-widget-reload-helper'],
    ['name' => '/edit/dialogs/edit-directory-dialog']
    ]]) ?>

<?php if(false) { ?><style><?php } ob_start(); ?>
    h1.directory-directory-h1-icon {
        background: url(<?= directoryModule::getPublishPath('/img/directory32.png'); ?>) no-repeat;
        padding-left: 36px;
    }
<?php $this->registerCss(ob_get_clean()); if(false) { ?></style><?php } ?>

<h1 class="directory-h1 directory-directory-h1-icon"><?= directoryModule::ht('edit', 'Directories')?></h1>

<?= Breadcrumbs::widget([
    'links' => [
        [
            'label' => directoryModule::ht('search', 'Search'),
            'url' => Url::toRoute('/directory/search/index')
        ],
        directoryModule::ht('edit', 'Directories')
    ]
    ]) ?>
