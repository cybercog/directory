<?php 
use app\modules\directory\helpers\ajaxJSONResponseHelper; 
use app\modules\directory\directoryModule;
use yii\web\View;
?>

<?php if(0) { ?><script type="text/javascript"><?php } ob_start(); ?>

function ajaxPostHelper(p) {
    var setPostData = function(p) {
        if(p.waitTag !== undefined) {
            if($(p.waitTag).data("postAjax") !== 1) {
                $(p.waitTag).data("postAjax", 1);
            } else {
                return false;
            }
        } else {
            if(p.errorTag !== undefined) {
                if($(p.errorTag).data("postAjax") !== 1) {
                    $(p.errorTag).data("postAjax", 1);
                } else {
                    return false;
                }
            }
        }
        
        return true;
    };
    
    var erasePostData = function(p) {
        if(p.waitTag !== undefined) {
            $(p.waitTag).removeData("postAjax");
        } else {
            if(p.errorTag !== undefined) {
                $(p.errorTag).removeData("postAjax");
            }
        }
    };
    
    var showError = function(p) {
        if(p.errorWaitTimeout === undefined) {
            p.errorWaitTimeout = 10; 
        }
        
        if((p.errorTag !== undefined) && 
                (p.message !== undefined)) {
            var error = $(p.errorTag);
            error.removeClass("directory-hide-element");
            error.html(p.message);
            setTimeout(function() {
                $(error).addClass("directory-hide-element");
                if(p.onHideErrorMessage !== undefined) { p.onHideErrorMessage(); }
                if(p.deleteData !== undefined) p.deleteData();
            }, p.errorWaitTimeout * 1000);
        }
    };
    
    var showOk = function(p) {
        if(p.okWaitTimeout === undefined) {
           p.okWaitTimeout = 10; 
        }

        if((p.okTag !== undefined) && 
                (p.message !== undefined)) {
            var ok = $(p.okTag);
            ok.removeClass("directory-hide-element");
            ok.html(p.message);
            setTimeout(function() {
                $(ok).addClass("directory-hide-element");
            }, p.okWaitTimeout * 1000);
        }
    };
    
    if(!setPostData({ waitTag: p.waitTag, errorTag: p.errorTag})) {
        alert("<?= directoryModule::t('search', 'Request sent.\nWait!')?>");
        return;
    }

    if(p.errorTag !== undefined) { $(p.errorTag).addClass("directory-hide-element"); }
    if(p.waitTag !== undefined) { $(p.waitTag).removeClass("directory-hide-element"); }
    
    try {
        $.post(p.url, p.data, function(dataObj) {
            try {
                if(p.waitTag !== undefined) { $(p.waitTag).addClass("directory-hide-element"); }
                if(dataObj.<?php echo ajaxJSONResponseHelper::resultField; ?> === "<?php echo ajaxJSONResponseHelper::okResult; ?>") {
                    erasePostData({ waitTag: p.waitTag, errorTag: p.errorTag});
                    if(p.onSuccess !== undefined) { p.onSuccess(dataObj); }
                    showOk({
                        okWaitTimeout: p.okWaitTimeout,
                        okTag: p.okTag,
                        message: p.okMessage
                    });
                } else {
                    if(p.onFail !== undefined) { p.onFail(); }
                    showError({
                        errorTag: p.errorTag,
                        message: dataObj.<?php echo ajaxJSONResponseHelper::messageField; ?>,
                        errorWaitTimeout: p.errorWaitTimeout,
                        onHideErrorMessage: p.onHideErrorMessage,
                        deleteData: ( erasePostData({ waitTag: p.waitTag, errorTag: p.errorTag}) )
                    });
                }
            } catch (err) {
                if(p.waitTag !== undefined) { $(p.waitTag).addClass("directory-hide-element"); }
                if(p.onFail !== undefined) { p.onFail(); }
                showError({
                    errorTag: p.errorTag,
                    message: err.message,
                    errorWaitTimeout: p.errorWaitTimeout,
                    onHideErrorMessage: p.onHideErrorMessage,
                    deleteData: ( erasePostData({ waitTag: p.waitTag, errorTag: p.errorTag}) )
                });
            }
        }).fail(function() {
            if(p.waitTag !== undefined) { $(p.waitTag).addClass("directory-hide-element"); }
            if(p.onFail !== undefined) { p.onFail(); }
            showError({
                errorTag: p.errorTag,
                message: "<?= directoryModule::t('search', 'Error connecting to server.')?>",
                errorWaitTimeout: p.errorWaitTimeout,
                onHideErrorMessage: p.onHideErrorMessage,
                deleteData: ( erasePostData({ waitTag: p.waitTag, errorTag: p.errorTag}) )
            });
        });
    } catch (err) {
        if(p.waitTag !== undefined) { $(p.waitTag).addClass("directory-hide-element"); }
        if(p.onFail !== undefined) { p.onFail(); }
        showError({
            errorTag: p.errorTag,
            message: err.message,
            errorWaitTimeout: p.errorWaitTimeout,
            onHideErrorMessage: p.onHideErrorMessage,
            deleteData: ( erasePostData({ waitTag: p.waitTag, errorTag: p.errorTag}) )
        });
    }
}

<?php if(0) { ?></script><?php } $this->registerJs(ob_get_clean(), View::POS_HEAD); 
