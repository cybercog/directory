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
        </td>
        <td class="directory-min-width">
            <div>&nbsp;</div>
        </td>
        <td class="directory-min-width">
            <div class="directory-no-empty-record-data-b2">&plus;</div>
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
        <div style="float: left;">
                <?= dataGridCellViewHelper::getValueDataString($first[$keys[$i]]['type'], $first[$keys[$i]]['value'], $first[$keys[$i]]['text']);?>
        </div>
            <?php endfor;
        ?>
    </div>
            </div></td>
    </tr>
</table>
                
                
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

                


<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
<?php $this->registerJs(ob_get_clean(), View::POS_READY); if(false) { ?></script><?php } ?>