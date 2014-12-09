<?php 

use yii\jui\Dialog;
use yii\web\View;
use app\modules\directory\directoryModule;
use app\modules\directory\widgets\SingletonRenderHelper;

$uid = mt_rand(0, mt_getrandmax());

?>

<?= SingletonRenderHelper::widget(['viewsRequire' => [
    ['name' => '/helpers/ajax-post-helper'],
    ['name' => '/helpers/publish-result-css'],
    ['name' => '/helpers/publish-types-css'],
    ['name' => '/edit/dialogs/edit-type-dialog']
    ]]) ?>

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
            $("#typesCompactGridPjaxWidget<?=$uid?> .directory-edit-type-button, #typesCompactGridPjaxWidget<?=$uid?> .directory-delete-type-button").button({text : false});
            $(this).removeClass("directory-hide-element").find("#typesCompactGridWidget<?=$uid?> tbody tr").addClass("directory-row-selector");
        }).on("pjax:error", function(eventObject) {
            eventObject.preventDefault();
            $("#selectTypeDialog<?=$uid?> #waitDlgQueryCompactDataType").addClass("directory-hide-element");
            $("#selectTypeDialog<?=$uid?> #errorDlgQueryCompactDataType").removeClass("directory-hide-element").html("<nobr><?= directoryModule::ht('search', 'Error connecting to server.')?></nobr>");
            setTimeout(function() { $("#selectTypeDialog<?=$uid?> #errorDlgQueryCompactDataType").addClass("directory-hide-element"); }, 5000);
        }).on("pjax:timeout", function(eventObject) {
            eventObject.preventDefault();
        }).tooltip({
            content : function() { return $(this).closest("td").find(".row-value").html(); },
            items : ".directory-show-full-text"
        });
        
        $("#typesCompactGridPjaxWidget<?=$uid?>").on(
                "click", 
                "#typesCompactGridWidget<?=$uid?> tbody tr td", 
                function(eventObject) {
                    $("#selectTypeDialog<?=$uid?>").dialog("close");
                    var p = $("#selectTypeDialog<?=$uid?>").data("<?=$uid?>");
                    if((p !== undefined) && (p.onSuccess !== undefined)) {
                        var type = $.parseJSON($(this).closest("tr").find("td .directory-row-data").text());
                        type.typeDiaplay = $(this).closest("tr").find("td:eq(1)").text();
                        p.onSuccess(type);
                    }
        });

        $.selectTypeDialog = function(p) {
            if(p !== undefined) {
                
                $("#selectTypeDialog<?=$uid?>").data("<?=$uid?>", p).
                dialog("option", "buttons", {
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
                                                timeout : <?=directoryModule::$SETTING['pjaxDefaultTimeout']?>, 
                                                url : $("#typesCompactGridPjaxWidget<?=$uid?> #typesCompactGridWidget<?=$uid?>").yiiGridView("data").settings.filterUrl
                                            });
                        }
                }).
                dialog("open");
            } else {
                alert("<?=directoryModule::ht('edit', 'Error: invalid call parameters.')?>");
            }
        }
    })(jQuery);
    
<?php $this->registerJs(ob_get_clean(), View::POS_READY); if(false) { ?></script><?php } ?>

