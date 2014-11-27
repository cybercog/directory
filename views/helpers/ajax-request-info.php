<?php 
use app\modules\directory\directoryModule; 
use app\modules\directory\widgets\SingletonRenderHelper;
?>

<?= SingletonRenderHelper::widget(['viewsRequire' => [
    ['name' => '../../views/helpers/publish-result-css'],
    ]]) ?>

<div>
    <span id="waitQuery<?=$blockUid?>" class="directory-hide-element">
        <nobr>
            <img src="<?= directoryModule::getPublishPath('/img/wait.gif')?>">
            <span><?= directoryModule::ht('search', 'processing request')?></span>
        </nobr>
    </span>
    <div id="errorQuery<?=$blockUid?>" class="directory-error-msg directory-hide-element"></div>
    <div id="okQuery<?=$blockUid?>" class="directory-ok-msg directory-hide-element"></div>
</div>
