<?php 
use yii\web\View;
?>


<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    (function($) {
        $.fn.tableJSPaginator = function() {
            
            this.init = function(p) {
                this.options(p);
            };
            this.clear = function() {
                $(this).options(p);
            };
            this.options = function(p) {
                var _p = $(this).data("paginatorData236614");

                if(_p === undefined) {
                    _p = {};
                }

                if(p !== undefined) {
                    if(p.pageSize === undefined) {
                        if(_p.pageSize === undefined) {
                            _p.pageSize = 10;
                        }
                    } else {
                        _p.pageSize = p.pageSize;
                    }

                    if(p.nextButton !== undefined) {
                        _p.nextButton = p.nextButton;
                    }

                    if(p.prevButton !== undefined) {
                        _p.prevButton = p.prevButton;
                    }
                } else {
                    _p.pageSize = 10;
                }

                if(_p.current === undefined) {
                    _p.current = 1;
                }

                $(this).data("paginatorData236614", _p);

                $(this).update();
            };
            this.countPage = function() {
                var _p = $(this).data("paginatorData236614");
                if(_p !== undefined) {
                    return Math.ceil($(this).find("tbody > tr").length / _p.pageSize);
                }

                return undefined;
            };
            this.getData = function() {
               return $(this).data("paginatorData236614");
            };
            this.update = function() {
                var _p = $(this).data("paginatorData236614");

                if(_p === undefined) {
                    return;
                }

                var _rows = $(this).find("tbody > tr");

                var _p_count = Math.ceil(_rows.length / _p.pageSize);

                if(_p.current > _p_count) {
                    _p.current = _p_count;
                } else {
                    if(_p.count < 1) {
                        _p.count = 1;
                    }
                }

                switch(_p.current) {
                    case 1:
                        _rows.slice(_p.pageSize).addClass("directory-hide-element");
                        _rows.slice(0, _p.pageSize - 1).removeClass("directory-hide-element");
                        break;
                    case _p_count:
                        _rows.slice(0, _p.pageSize * (_p_count - 1) - 1).addClass("directory-hide-element");
                        _rows.slice(_p.pageSize * (_p_count - 1)).removeClass("directory-hide-element");
                        break;
                    default:
                        _rows.slice(0, _p.current * _p.pageSize - 1).addClass("directory-hide-element");
                        _rows.slice(_p.current * _p.pageSize - 1).addClass("directory-hide-element");
                        _rows.slice(_p.pageSize * _p.current, _p.pageSize * (_p.current - 1)).removeClass("directory-hide-element");
                        break;
                }
            };
            this.addRows = function(rows) {
                alert(this);
                $(this).find("tbody").append(rows);
                $(this).tableJSPaginator.update();
            };
            this.removeRows = function(rows) {
                $(this).find(rows).remove();
                $(this).tableJSPaginator.update();
            };
            
            return this;
        };
    })(jQuery);
    
<?php $this->registerJs(ob_get_clean(), View::POS_READY); if(false) { ?></script><?php } ?>