<?php 
use yii\web\View;
?>


<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    (function($) {
        $.fn.tableJSPaginator = function() {
            
            this.init = function(p) {
                this.tableJSPaginator().options(p);
                return this;
            };
            this.clear = function() {
                $(this).removeData("paginatorData236614");
                return this;
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

                $(this).tableJSPaginator().update();
                
                return this;
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
                    if(_p_count < 1) {
                        _p.current = 1;
                    } else {
                        _p.current = _p_count;
                    }
                } else {
                    if(_p.current < 1) {
                        _p.current = 1;
                    }
                }
                
                if(_p_count === 1) {
                    _rows.removeClass("directory-hide-element");
                    if(_p.nextButton !== undefined) {
                        $(_p.nextButton).addClass("directory-hide-element");
                    }
                    if(_p.prevButton !== undefined) {
                        $(_p.prevButton).addClass("directory-hide-element");
                    }
                } else if(_p.current === 1) {
                    _rows.slice(_p.pageSize).addClass("directory-hide-element");
                    _rows.slice(0, _p.pageSize - 1).removeClass("directory-hide-element");
                    if(_p.nextButton !== undefined) {
                        $(_p.nextButton).addClass("directory-hide-element");
                    }
                    if(_p.prevButton !== undefined) {
                        $(_p.prevButton).removeClass("directory-hide-element");
                    }
                } else if(_p.current === _p_count) {
                    _rows.slice(0, _p.pageSize * (_p_count - 1) - 1).addClass("directory-hide-element");
                    _rows.slice(_p.pageSize * (_p_count - 1)).removeClass("directory-hide-element");
                    if(_p.nextButton !== undefined) {
                        $(_p.nextButton).removeClass("directory-hide-element");
                    }
                    if(_p.prevButton !== undefined) {
                        $(_p.prevButton).addClass("directory-hide-element");
                    }
                } else {
                    _rows.slice(0, _p.current * _p.pageSize - 1).addClass("directory-hide-element");
                    _rows.slice(_p.current * _p.pageSize - 1).addClass("directory-hide-element");
                    _rows.slice(_p.pageSize * _p.current, _p.pageSize * (_p.current - 1)).removeClass("directory-hide-element");
                    if(_p.nextButton !== undefined) {
                        $(_p.nextButton).removeClass("directory-hide-element");
                    }
                    if(_p.prevButton !== undefined) {
                        $(_p.prevButton).removeClass("directory-hide-element");
                    }
                }
                
                return this;
            };
            this.addRows = function(rows) {
                $(this).find("tbody").append(rows);
                $(this).tableJSPaginator().update();
                return this;
            };
            this.removeRows = function(rows) {
                $(this).find(rows).remove();
                $(this).tableJSPaginator().update();
                return this;
            };
            this.nextPage = function() {
                var _p = $(this).data("paginatorData236614");
                
                if(_p !== undefined) {
                    ++_p.current;
                    if(_p.current > $(this).tableJSPaginator().countPage()) {
                        _p.current = 1;
                    }
                    
                    $(this).data("paginatorData236614", _p);
                    $(this).tableJSPaginator().update();
                }
                
                return this;
            };
            this.prevPage = function() {
                var _p = $(this).data("paginatorData236614");
                
                if(_p !== undefined) {
                    --_p.current;
                    if(_p.current < 1) {
                        _p.current = $(this).tableJSPaginator().countPage();
                    }
                    
                    $(this).data("paginatorData236614", _p);
                    $(this).tableJSPaginator().update();
                }
                
                return this;
            };
            
            return this;
        };
    })(jQuery);
    
<?php $this->registerJs(ob_get_clean(), View::POS_READY); if(false) { ?></script><?php } ?>