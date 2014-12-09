<?php 

use yii\jui\Dialog;
use yii\web\View;
use app\modules\directory\directoryModule;
use app\modules\directory\widgets\SingletonRenderHelper;

$uid = mt_rand(0, mt_getrandmax());

?>

<?= SingletonRenderHelper::widget(['viewsRequire' => [
    //['name' => '/helpers/ajax-post-helper'],
    ['name' => '/helpers/publish-result-css'],
    ['name' => '/helpers/publish-types-css'],
    //['name' => '/edit/dialogs/edit-type-dialog']
    ]]) ?>

<div class="directory-hide-element">
    
<?php 
Dialog::begin([
    'id' => 'selectDataDialog'.$uid,
    'clientOptions' => [
        'modal' => true,
        'autoOpen' => false,
        'resizable' => false,
        'title' => directoryModule::ht('edit', 'Select a data item'),
        'width' => 600
    ],
]); ?>

    <?=$this->render('data-compact-grid', ['uid' => $uid])?>
    
    <span id="waitDlgQueryCompactData" class="directory-hide-element">
        <nobr>
            <img src="<?= directoryModule::getPublishPath('/img/wait.gif')?>">
            <span><?= directoryModule::ht('search', 'processing request')?></span>
        </nobr>
    </span>
    <div id="errorDlgQueryCompactData" class="directory-error-msg directory-hide-element"></div>
    <div id="okDlgQueryCompactData" class="directory-ok-msg directory-hide-element"></div>
    
<?php Dialog::end(); ?>
    
    
</div>

<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    (function($) {
        $("#dataCompactGridPjaxWidget<?=$uid?>").on("pjax:start", function() {
            $("#selectDataDialog<?=$uid?> #waitDlgQueryCompactData").removeClass("directory-hide-element");
            $(this).addClass("directory-hide-element");
        }).on("pjax:end", function() {
            $("#selectDataDialog<?=$uid?> #waitDlgQueryCompactData").addClass("directory-hide-element");
            //$("#typesCompactGridPjaxWidget<?=$uid?> .directory-edit-type-button, #typesCompactGridPjaxWidget<?=$uid?> .directory-delete-type-button").button({text : false});
            $(this).removeClass("directory-hide-element").find("#dataCompactGridWidget<?=$uid?> tbody tr").addClass("directory-row-selector");
        }).on("pjax:error", function(eventObject) {
            eventObject.preventDefault();
            $("#selectDataDialog<?=$uid?> #waitDlgQueryCompactData").addClass("directory-hide-element");
            $("#selectDataDialog<?=$uid?> #errorDlgQueryCompactData").removeClass("directory-hide-element").html("<nobr><?= directoryModule::ht('search', 'Error connecting to server.')?></nobr>");
            setTimeout(function() { $("#selectTypeDialog<?=$uid?> #errorDlgQueryCompactData").addClass("directory-hide-element"); }, 5000);
        }).on("pjax:timeout", function(eventObject) {
            eventObject.preventDefault();
        }).tooltip({
            content : function() { return $(this).closest("td").find(".row-value").html(); },
            items : ".directory-show-full-text"
        });
        
        $("#dataCompactGridPjaxWidget<?=$uid?>").on(
                "click", 
                "#dataCompactGridWidget<?=$uid?> tbody tr td", 
                function(eventObject) {
                    if(!$(eventObject.target).hasClass("directory-data-file-download")) {
                        $("#selectDataDialog<?=$uid?>").dialog("close");
                        var p = $("#selectDataDialog<?=$uid?>").data("<?=$uid?>");
                        if((p !== undefined) && (p.onSuccess !== undefined)) {
                            var data = $.parseJSON($(this).closest("tr").find("td .directory-row-data").text());
                            data.valueDisplay = $(this).closest("tr").find("td:eq(1)").html();
                            p.onSuccess(data);
                        }
                    }
        });
        
        $.selectDataDialog = function(p) {
            if(p !== undefined) {
                
                $("#selectDataDialog<?=$uid?>").data("<?=$uid?>", p).
                dialog("option", "buttons", {
                    "<?= directoryModule::ht('edit', 'Close')?>" : function() { 
                        $("#selectDataDialog<?=$uid?>").dialog("close");
                    }
                }).
                dialog({
                        open: function() {
                            $.pjax.reload('#dataCompactGridPjaxWidget<?=$uid?>', 
                                            {
                                                push : false,
                                                replace : false,
                                                timeout : <?=directoryModule::$SETTING['pjaxDefaultTimeout']?>, 
                                                url : $("#dataCompactGridPjaxWidget<?=$uid?> #dataCompactGridWidget<?=$uid?>").yiiGridView("data").settings.filterUrl
                                            });
                        }
                }).
                dialog("open");
            } else {
                alert("<?=directoryModule::ht('edit', 'Error: invalid call parameters.')?>");
            }
        };
    })(jQuery);
    
<?php $this->registerJs(ob_get_clean(), View::POS_READY); if(false) { ?></script><?php } ?>