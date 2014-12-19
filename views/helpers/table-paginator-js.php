<?php 
use yii\web\View;
?>


<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    (function($) {
        $.fn.tableJSPaginator = {
            init : function(p) {
                $(this).tableJSPaginator.options(p);
                $(this).tableJSPaginator.update();
            },
            update : function() {},
            options : function(p) {
                var dataKey = "paginatorData236614";
                
                if(p !== undefined) {
                    
                }
            }
        };
    })(jQuery);
    
<?php $this->registerJs(ob_get_clean(), View::POS_READY); if(false) { ?></script><?php } ?>