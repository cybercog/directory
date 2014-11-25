<?php

use yii\web\View;
use app\modules\directory\helpers\ajaxJSONResponseHelper;

?>

<?php if(0) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    (function($) {
        $.fn.ajaxWidgetReloadHelper = function(p) {
            if(p !== undefined) {
                if(p.ajaxParams !== undefined) {
                    this.on("click", 
                        (p.filter === undefined) ? "a" : p.filter, 
                        { p : p, root : this }, 
                        function(eventObject) {
                            eventObject.preventDefault();
                                
                            var oldOnSuccess = eventObject.data.p.ajaxParams.onSuccess;
                            eventObject.data.p.ajaxParams.onSuccess = function(dataObject) {
                                eventObject.data.root.html(dataObject.<?=ajaxJSONResponseHelper::messageField?>);
                                
                                if(oldOnSuccess !== undefined) {
                                    oldOnSuccess(dataObject);
                                }
                            };
                            
                            if($(this).attr("href") !== undefined) {
                                eventObject.data.p.ajaxParams.url = $(this).attr("href");
                            } else {
                                if(eventObject.data.p.ajaxParams.url === undefined) {
                                    eventObject.data.p.ajaxParams.url = "<?=\Yii::$app->request->url?>";
                                }
                            }
                            
                            $.ajaxPostHelper(eventObject.data.p.ajaxParams);
                        });
                }
            }
            return this;
        };
    })(jQuery);
    
<?php if(0) { ?></script><?php } $this->registerJs(ob_get_clean(), View::POS_READY); 
