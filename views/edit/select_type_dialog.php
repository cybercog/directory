<?php 

use yii\jui\Dialog;
use yii\web\View;
use app\modules\directory\directoryModule;

?>

<?php if(false) { ?><style><?php } ob_start(); ?>
    
<?php $this->registerCss(ob_get_clean()); if(false) { ?></style><?php } ?>

<div class="directory-hide-element">

<?php 
Dialog::begin([
    'id' => 'selectTypeDialog',
    'clientOptions' => [
        'modal' => true,
        'autoOpen' => false,
        'resizable' => false,
        'width' => 600
    ],
]); ?>


<?php require('types_compact_grid.php');?>


<?php Dialog::end(); ?>

</div>

<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    $("#typesGridWidget tr").click(function() {
        
    });
    
    function SelectDataType(resultCallback) {
        $("#selectTypeDialog").
                dialog("option", "buttons", {
                    "<?= directoryModule::t('edit', 'Close')?>" : function() { 
                        $(this).dialog("close");
                        resultCallback(false); 
                    }
                }).
                dialog("open");
    }
    
<?php $this->registerJs(ob_get_clean(), View::POS_READY); if(false) { ?></script><?php } ?>