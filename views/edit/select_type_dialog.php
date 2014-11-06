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
    
    $("#selectTypeDialog").dialog("option", "buttons", {
            "<?= directoryModule::t('edit', 'Close')?>" : function() { 
                $(this).dialog("close").data("resultCallback")(false); 
            }
    }).find("tbody tr").addClass("directory-row-selector").click(function() {
        $("#selectTypeDialog").dialog("close").data("resultCallback")(
                {
                    id : $(this).find("td:first .row-id").text(),
                    name : $(this).find("td:first .row-display").text(),
                    type : $(this).find("td:eq(1) .row-value").text(),
                    typeDiaplay : $(this).find("td:eq(1) .row-display").text()
        }); 
    });
    
<?php $this->registerJs(ob_get_clean(), View::POS_READY); if(false) { ?></script><?php } ?>

<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    function SelectDataType(resultCallback) {
        $("#selectTypeDialog").
                data("resultCallback", resultCallback).
                dialog("open");
    }
    
<?php $this->registerJs(ob_get_clean(), View::POS_HEAD); if(false) { ?></script><?php } ?>