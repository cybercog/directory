<?php 
use app\modules\directory\directoryModule;
?>

<table class="directory-modal-table directory-stretch-bar">
    <tr>
        <td class="directory-min-width">
            <div class="directory-tree-node-pic-wrap">
                <img src="<?= directoryModule::getPublishImage('/plus.png'); ?>" />
                <img class="directory-hide-element" src="<?= directoryModule::getPublishImage('/minus.png'); ?>" />
            </div>
        </td>
        <td class="directory-min-width">&nbsp;</td>
        <td><?=$brabch->name?></td>
    </tr>
    <tr>
        <td colspan="3">
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
        <td style="padding-left: 10px;;"><div style="width: 100%; height: 100%; background-color: red;"></div></td>
        <td></td>
        <td>(Пусто)</td>
    </tr>
</table>