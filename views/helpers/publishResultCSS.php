<?php use app\modules\directory\directoryModule; ?>

<?php if(false) { ?><style><?php } ob_start(); ?>

    .directory-error-msg {
        color: red !important;
        background: url(<?= directoryModule::getImagePath().'/error.png'; ?>) no-repeat;
        padding-left: 20px;
    }
    .directory-ok-msg {
        color: green !important;
        background: url(<?= directoryModule::getImagePath().'/ok.png'; ?>) no-repeat;
        padding-left: 20px;
    }
    
<?php $this->registerCss(ob_get_clean()); if(false) { ?></style><?php } 