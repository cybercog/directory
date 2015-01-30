<?php 
use yii\web\View;
use app\modules\directory\helpers\dataGridCellViewHelper;
use app\modules\directory\directoryModule;
?>

<?php if(count($directories) > 0) { ?>
<div class="directory-directory-list">
    <div class="directory-directory-list-count">
        <div><?=count($directories)?></div>
        <div>&#9660;</div>
    </div>
    <div class="row-data directory-hide-element directories-data"><?=json_encode($directories)?></div>
    <div class="directory-directory-list-items-parent">
        <div class="directory-directory-list-arrow"></div>
        <div class="directory-directory-list-arrow-background"></div>
        <div class="directory-directory-list-arrow-substrate"></div>
        <div class="directory-directory-list-items">
            <table class="directory-modal-table">
                <tbody>
                    <?php foreach ($directories as $directory) { ?>
                    <tr>
                        <td class="my-first">
                            <span><?=$directory['name']?></span>
                            <img <?php if(!isset($directory['description'])) { ?>class="directory-hide-element"<?php } ?> src="<?=directoryModule::getPublishImage('/info16.png')?>"/>
                            <div class="directory-description-data directory-hide-element"><?=$directory['description']?></div>
                        </td>
                        <td class="my-visible" title="<?=directoryModule::ht('edit', 'Visibility catalog')?>"><?=dataGridCellViewHelper::getVisibleFlagString($directory['visible'])?></td>
                        <td class="my-visible" title="<?=directoryModule::ht('edit', 'Visibility directory entry')?>"><?=dataGridCellViewHelper::getVisibleFlagString($directory['record_visible'])?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    $(".directory-directory-list .directory-directory-list-items table tr td.my-first").tooltip({
        content : function() { return $(this).closest("td").find(".directory-description-data").html(); },
        items : "img"
    });
    $(".directory-directory-list .directory-directory-list-items table tr td.my-visible").tooltip();
    
    $(".directory-directory-list .directory-directory-list-count").click(function() {
        $(".directory-directory-list .directory-directory-list-count").removeClass("directory-directory-list-count-down");
        $(this).addClass("directory-directory-list-count-down");
        $(document).one("click", function() { 
            $(".directory-directory-list .directory-directory-list-count").removeClass("directory-directory-list-count-down");
        });
        return false;
    });
    
<?php $this->registerJs(ob_get_clean(), View::POS_READY); if(false) { ?></script><?php } ?>

<?php } else { ?>
<div class="directory-empty-directory-list">
    <span>(<?=directoryModule::ht('search', 'no')?>)</span>
</div>
<?php } ?>

