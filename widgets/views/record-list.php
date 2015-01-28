<?php 
use yii\web\View;
use app\modules\directory\helpers\dataGridCellViewHelper;
use app\modules\directory\directoryModule;
?>


    
                
<?php if(count($records) > 0) : ?>
<table class="directory-modal-table">
    <tr>
        <td class="directory-min-width">
            <div class="directory-no-empty-record-data-b1">&#9660;</div>
            <div class="directory-no-empty-record-data-b-menu-substrate">
                <div class="directory-no-empty-record-data-b-menu-border directory-hide-element"></div>
                <div class="directory-no-empty-record-data-b-menu-content directory-hide-element">ui4578754524242878578ui</div>
            </div>
        </td>
        <td class="directory-min-width">
            <div>&nbsp;</div>
        </td>
        <td class="directory-min-width">
            <div class="directory-no-empty-record-data-b2 directory-no-empty-record-data-b-hover">&plus;</div>
            <div class="directory-no-empty-record-data-b-menu-substrate">
                <div class="directory-no-empty-record-data-b-menu-border directory-hide-element"></div>
                <div class="directory-no-empty-record-data-b-menu-content directory-hide-element">
                    <div>
                    <?php foreach ($records as $record1layer) : 
                        $keys = array_keys($record1layer);
                        for ($i = 0; $i < count($keys); ++$i) : ?>
                            <div class="directory-record-data-item">
                            <?= dataGridCellViewHelper::getValueDataString($record1layer[$keys[$i]]['type'], $record1layer[$keys[$i]]['value'], $record1layer[$keys[$i]]['text']);?>
                            </div>
                        <?php endfor; ?>
                    <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </td>
        <td class="directory-min-width">
            <div>&nbsp;</div>
        </td>
        <td>
            <div class="directory-no-empty-record-data">
                <div class="directory-hide-element data-data"><?=  json_encode($records)?></div>
                <div class="directory-no-empty-record-data-item">
        <?php 
            reset($records);
            $first = current($records);
            $keys = array_keys($first);
            
            for ($i = 0; $i < count($keys); ++$i) : ?>
                <div class="directory-record-data-item">
                <?= dataGridCellViewHelper::getValueDataString($first[$keys[$i]]['type'], $first[$keys[$i]]['value'], $first[$keys[$i]]['text']);?>
                </div>
            <?php endfor;
        ?>
    </div>
            </div></td>
    </tr>
</table>

<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    $(".directory-no-empty-record-data-b1").click(function() {
        $(".directory-no-empty-record-data-b1").removeClass("directory-no-empty-record-data-b");
        $(".directory-no-empty-record-data-b-menu-content, .directory-no-empty-record-data-b-menu-border").addClass("directory-hide-element");
        $(this).addClass("directory-no-empty-record-data-b").
                closest("td").find(".directory-no-empty-record-data-b-menu-content, .directory-no-empty-record-data-b-menu-border").
                removeClass("directory-hide-element");
        var oneClickHandler = function(eventObject) {
            if(!$(eventObject.target).hasClass("directory-no-empty-record-data-b-menu-content")) {
                $(".directory-no-empty-record-data-b1").removeClass("directory-no-empty-record-data-b");
                $(".directory-no-empty-record-data-b-menu-content, .directory-no-empty-record-data-b-menu-border").addClass("directory-hide-element");
                $(document).off('click', oneClickHandler);
            }
        };
        $(document).on("click", oneClickHandler);
        return false;
    });
    
    $(".directory-no-empty-record-data-b2").click(function() {
        $(".directory-no-empty-record-data-b2").removeClass("directory-no-empty-record-data-b");
        $(".directory-no-empty-record-data-b-menu-content, .directory-no-empty-record-data-b-menu-border").addClass("directory-hide-element");
        $(this).addClass("directory-no-empty-record-data-b").html("&minus;").
                closest("td").find(".directory-no-empty-record-data-b-menu-content, .directory-no-empty-record-data-b-menu-border").
                removeClass("directory-hide-element");
        var oneClickHandler = function(eventObject) {
            if(!$(eventObject.target).hasClass("directory-no-empty-record-data-b-menu-content")) {
                $(".directory-no-empty-record-data-b2").removeClass("directory-no-empty-record-data-b").html("&plus;");
                $(".directory-no-empty-record-data-b-menu-content, .directory-no-empty-record-data-b-menu-border").addClass("directory-hide-element");
                $(document).off('click', oneClickHandler);
            }
        };
        $(document).on("click", oneClickHandler);
        return false;
    });
    
<?php $this->registerJs(ob_get_clean(), View::POS_READY); if(false) { ?></script><?php } ?>
                
<?php else : ?>
<table class="directory-modal-table">
    <tr>
        <td class="directory-min-width">
            <div class="directory-empty-record-data">
                <span>(<?=directoryModule::ht('search', 'no')?>)</span>
            </div>
        </td>
    </tr>
</table>
<?php endif; ?>
              


