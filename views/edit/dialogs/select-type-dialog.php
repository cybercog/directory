<?php 

use yii\jui\Dialog;
use yii\web\View;
use app\modules\directory\directoryModule;

$uid = mt_rand(0, mt_getrandmax());

?>

<?php if(false) { ?><style><?php } ob_start(); ?>
    
<?php $this->registerCss(ob_get_clean()); if(false) { ?></style><?php } ?>

<div class="directory-hide-element">

<?php 
Dialog::begin([
    'id' => 'selectTypeDialog'.$uid,
    'clientOptions' => [
        'modal' => true,
        'autoOpen' => false,
        'resizable' => false,
        'title' => directoryModule::ht('edit', 'Select type'),
        'width' => 600
    ],
]); ?>


<div>
    
    <?=$this->render('types-compact-grid', ['uid' => $uid])?>
    
    <span id="waitDlgQueryCompactDataType" class="directory-hide-element">
        <nobr>
            <img src="<?= directoryModule::getPublishPath('/img/wait.gif')?>">
            <span><?= directoryModule::ht('search', 'processing request')?></span>
        </nobr>
    </span>
    <div id="errorDlgQueryCompactDataType" class="directory-error-msg directory-hide-element"></div>
    <div id="okDlgQueryCompactDataType" class="directory-ok-msg directory-hide-element"></div>

</div>


<?php Dialog::end(); ?>

</div>

<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    (function($) {
        $("#typesCompactGridPjaxWidget<?=$uid?>").on("pjax:start", function() {
            $("#selectTypeDialog<?=$uid?> #waitDlgQueryCompactDataType").removeClass("directory-hide-element");
            $(this).addClass("directory-hide-element");
        }).on("pjax:end", function() {
            $("#selectTypeDialog<?=$uid?> #waitDlgQueryCompactDataType").addClass("directory-hide-element");
            $(this).removeClass("directory-hide-element").find("#typesCompactGridWidget<?=$uid?> tbody tr").addClass("directory-row-selector");
        }).on("pjax:error", function(eventObject) {
            eventObject.preventDefault();
            $("#selectTypeDialog<?=$uid?> #waitDlgQueryCompactDataType").addClass("directory-hide-element");
            $("#selectTypeDialog<?=$uid?> #errorDlgQueryCompactDataType").removeClass("directory-hide-element").html("<nobr><?= directoryModule::ht('search', 'Error connecting to server.')?></nobr>");
            setTimeout(function() { $("#selectTypeDialog<?=$uid?> #errorDlgQueryCompactDataType").addClass("directory-hide-element"); }, 5000);
        }).on("pjax:timeout", function(eventObject) {
            eventObject.preventDefault();
        });
        
        $.selectTypeDialog = function(p) {
            if(p !== undefined) {
                $("#typesCompactGridPjaxWidget<?=$uid?>").on(
                        "click", 
                        "#typesCompactGridWidget<?=$uid?> tbody tr", 
                        { params : p }, 
                        function(eventObject) {
                            $("#selectTypeDialog<?=$uid?>").dialog("close");
                            if(eventObject.data.params.onSuccess !== undefined) {
                                eventObject.data.params.onSuccess({
                                    id : $(this).find("td:first .row-id").text(),
                                    name : $(this).find("td:first .row-display").text(),
                                    type : $(this).find("td:eq(1) .row-value").text(),
                                    typeDiaplay : $(this).find("td:eq(1) .row-display").text()
                                });
                            }
                });
                
                $("#selectTypeDialog<?=$uid?>").dialog("option", "buttons", {
                    "<?= directoryModule::ht('edit', 'Close')?>" : function() { 
                        $("#selectTypeDialog<?=$uid?>").dialog("close");
                    }
                }).
                dialog({
                        open: function() {
                            $.pjax.reload('#typesCompactGridPjaxWidget<?=$uid?>', 
                                            {
                                                push : false,
                                                replace : false,
                                                timeout : <?=\Yii::$app->params['pjaxDefaultTimeout']?>, 
                                                url : $("#typesCompactGridPjaxWidget<?=$uid?> #typesCompactGridWidget<?=$uid?>").yiiGridView("data").settings.filterUrl
                                            });
                        }
                }).
                dialog("open");
            }
        }
    })(jQuery);
    
<?php $this->registerJs(ob_get_clean(), View::POS_READY); if(false) { ?></script><?php } ?>

<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    /*function SelectDataType(resultCallback) {
        $("#selectTypeDialog").
                data("resultCallback", resultCallback).
                dialog("open");
        $("#typesCompactGridWidget").remove();
        $.pjax.reload("#typesCompactGridPjaxWidget", {timeout : <?=\Yii::$app->params['pjaxDefaultTimeout']?>});
    }*/
    
<?php $this->registerJs(ob_get_clean(), View::POS_HEAD); if(false) { ?></script><?php } ?>