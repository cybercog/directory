<?php use app\modules\directory\directoryModule; ?>

<?php if(false) { ?><style><?php } ob_start(); ?>

    .directory-string-type {
        background: url(<?= directoryModule::getImagePath().'/string16.png'; ?>) no-repeat;
        padding-left: 20px;
    }
    .directory-text-type {
        background: url(<?= directoryModule::getImagePath().'/text16.png'; ?>) no-repeat;
        padding-left: 20px;
    }
    .directory-image-type {
        background: url(<?= directoryModule::getImagePath().'/image16.png'; ?>) no-repeat;
        padding-left: 20px;
    }
    .directory-file-type {
        background: url(<?= directoryModule::getImagePath().'/file16.png'; ?>) no-repeat;
        padding-left: 20px;
    }
    
<?php $this->registerCss(ob_get_clean()); if(false) { ?></style><?php } 