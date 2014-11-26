<?php

use yii\web\View;
use app\modules\directory\helpers\ajaxJSONResponseHelper;

$uid = mt_rand(0, mt_getrandmax());

?>

<?php if(0) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    (function($) {
        var reloader = function(p, rootNode, eventNode) {
            var params = $.extend({}, p.ajaxParams);
            
            params.onSuccess = function(dataObject) {
                rootNode.html(dataObject.<?=ajaxJSONResponseHelper::messageField?>);
                alert(dataObject.<?=ajaxJSONResponseHelper::messageField?>);
                
                if(p.ajaxParams.onSuccess !== undefined) {
                    p.ajaxParams.onSuccess(dataObject);
                }
            };
            
            if((eventNode !== undefined) && 
                    (eventNode.attr("href") !== undefined)) {
                params.url = eventNode.attr("href");
            } else {
                if(p.reloadUrl !== undefined) {
                    if(typeof(p.reloadUrl) === "function") {
                        params.url = p.reloadUrl();
                    } else {
                        params.url = p.reloadUrl;
                    }
                } else {
                    params.url = "<?=\Yii::$app->request->url?>";
                }
            }
            
            $.ajaxPostHelper(params);
        }
        
        $.fn.ajaxWidgetReloadHelper = function(p) {
            if(p !== undefined) {
                if(p.ajaxParams !== undefined) {
                    this.data("<?=$uid?>", p);
                    this.on("click", 
                        (p.filter === undefined) ? "a" : p.filter, 
                        { p : p, node : this }, 
                        function(eventObject) {
                            eventObject.preventDefault();
                            reloader(eventObject.data.p, eventObject.data.node, $(this));
                                
                            /*var oldOnSuccess = eventObject.data.p.ajaxParams.onSuccess;
                            eventObject.data.p.ajaxParams.onSuccess = function(dataObject) {
                                eventObject.data.root.html(dataObject.<?=ajaxJSONResponseHelper::messageField?>);
                                
                                if(oldOnSuccess !== undefined) {
                                    oldOnSuccess(dataObject);
                                }
                            };
                            
                            if($(this).attr("href") !== undefined) {
                                eventObject.data.p.ajaxParams.url = $(this).attr("href");
                            } else {
                                if(eventObject.data.p.reloadUrl !== undefined) {
                                    if(typeof(eventObject.data.p.reloadUrl) === "function") {
                                        eventObject.data.p.ajaxParams.url = eventObject.data.p.reloadUrl();
                                    } else {
                                        eventObject.data.p.ajaxParams.url = eventObject.data.p.reloadUrl;
                                    }
                                } else {
                                    eventObject.data.p.ajaxParams.url = "<?=\Yii::$app->request->url?>";
                                }
                            }
                            
                            $.ajaxPostHelper(eventObject.data.p.ajaxParams);*/
                        });
                }
            }
            return this;
        };
        
        $.fn.ajaxWidgetReloadHelperInitialize = function() {
            if(this.data("<?=$uid?>") !== undefined) {
                reloader(this.data("<?=$uid?>"), this);
            }
        };
    })(jQuery);
    
<?php if(0) { ?></script><?php } $this->registerJs(ob_get_clean(), View::POS_READY); 
