<?php 
use app\modules\directory\directoryModule;
use app\modules\directory\widgets\SingletonRenderHelper;
?>

<?php ob_start();?>
<div class="directory-hide-element">
    <table>
        <tr>
            <td class="directory-min-width"></td>
            <td class="directory-min-width directory-tree-node-line-end"></td>
            <td rowspan="2"></td>
            <td rowspan="2">(Пусто)<br />(Пусто)<br />(Пусто)</td>
        </tr>
        <tr>
            <td class="directory-min-width"></td>
            <td class="directory-min-width directory-tree-node-line-end-bottom directory-tree-node-line-middle"></td>
        </tr>
    </table>
</div>

<?= SingletonRenderHelper::widget(['htmlRequire' => ['tree-content-182'=>ob_get_clean()]])?>


<table class="directory-modal-table directory-stretch-bar">
    <tr>
        <td class="directory-min-width" colspan="2">
            <div class="directory-tree-node-pic-wrap">
                <img src="<?= directoryModule::getPublishImage('/plus.png'); ?>" />
                <img class="directory-hide-element" src="<?= directoryModule::getPublishImage('/minus.png'); ?>" />
            </div>
        </td>
        <td class="directory-min-width">&nbsp;</td>
        <td><?=$brabch->name?></td>
    </tr>
    <tr>
        <td colspan="4">
            <span id="waitQueryHierarchyTree" class="directory-hide-element">
                <nobr>
                    <img src="<?= directoryModule::getPublishImage('/wait.gif')?>">
                    <span><?= directoryModule::ht('search', 'processing request')?></span>
                </nobr>
            </span>
            <div id="errorQueryHierarchyTree" class="directory-error-msg directory-hide-element"></div>
            <div id="okQueryHierarchyTree" class="directory-ok-msg directory-hide-element"></div>
        </td>
    </tr>
    <tr>
        <td class="directory-min-width"></td>
        <td class="directory-min-width"></td>
        <td class="directory-min-width"></td>
        <td></td>
    </tr>
</table>