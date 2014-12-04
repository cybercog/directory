<?php

use yii\helpers\Url;
use app\modules\directory\directoryModule;
use yii\widgets\Breadcrumbs;

$this->title = directoryModule::ht('search', 'Directory').' - '.directoryModule::ht('edit', 'Records');

?>


<?php if(false) { ?><style><?php } ob_start(); ?>
    h1.directory-records-h1-icon {
        background: url(<?= directoryModule::getPublishPath('/img/record32.png'); ?>) no-repeat;
        padding-left: 36px;
    }
<?php $this->registerCss(ob_get_clean()); if(false) { ?></style><?php } ?>

<h1 class="directory-h1 directory-records-h1-icon"><?= directoryModule::ht('edit', 'Records')?></h1>

<?= Breadcrumbs::widget([
    'links' => [
        [
            'label' => directoryModule::ht('search', 'Search'),
            'url' => Url::toRoute('/directory/search/index')
        ],
        directoryModule::ht('edit', 'Records')
    ]
    ]) ?>
