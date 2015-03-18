<?php 
use yii\helpers\Url;
use app\modules\directory\directoryModule;
use yii\widgets\Breadcrumbs;
use yii\web\View;

use app\modules\directory\widgets\SingletonRenderHelper;
use app\modules\directory\helpers\ajaxJSONResponseHelper;

yii\jui\JuiAsset::register($this);

$this->title = directoryModule::ht('search', 'Directory').' - '.directoryModule::ht('edit', 'Branches');
$uid = mt_rand(0, mt_getrandmax());
?>

<?= SingletonRenderHelper::widget(['viewsRequire' => [
    ['name' => '/helpers/ajax-post-helper'],
    ['name' => '/helpers/publish-result-css'],
    ['name' => '/helpers/publish-types-css'],
    //['name' => '/helpers/ajax-widget-reload-helper'],
    ['name' => '/edit/dialogs/edit-branch-dialog']
    ]]) ?>


<?php if(false) { ?><style><?php } ob_start(); ?>
    h1.directory-branches-h1-icon {
        background: url(<?= directoryModule::getPublishImage('/branch32.png'); ?>) no-repeat;
        padding-left: 36px;
    }
<?php $this->registerCss(ob_get_clean()); if(false) { ?></style><?php } ?>

<h1 class="directory-h1 directory-branches-h1-icon"><?= directoryModule::ht('edit', 'Branches')?></h1>

<?= Breadcrumbs::widget([
    'links' => [
        [
            'label' => directoryModule::ht('search', 'Search'),
            'url' => Url::toRoute('/directory/search/index')
        ],
        directoryModule::ht('edit', 'Branches')
    ]
    ]) ?>

<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    $("#createNewBranch").button({text : false}).click(function() {
        
    });

    $("#updateBranchesTable").button({text : false}).click(function() {
        
    });
    
<?php $this->registerJs(ob_get_clean(), View::POS_READY); if(false) { ?></script><?php } ?>

<table class="directory-modal-table directory-stretch-bar">
    <tr>
        <td class="directory-min-width">
            <div class="directory-buttons-panel-padding-wrap ui-widget-header ui-corner-all">
                <table class="directory-modal-table directory-stretch-bar">
                    <tr>
                        <td class="directory-min-width">
                            <button id="createNewBranch">
                                <nobr>
                                    <span class="directory-add-button-icon"><?= directoryModule::ht('edit', 'Create new hierarchy')?>...</span>
                                </nobr>
                            </button>
                        </td>
                        <td class="directory-min-width">&nbsp;</td>
                        <td class="directory-min-width">
                            <button id="updateBranchesTable">
                                <nobr>
                                    <span class="directory-update-button-icon"><?= directoryModule::ht('edit', 'Update table')?></span>
                                </nobr>
                            </button>
                        </td>
                        <td>&nbsp;</td>
                        <td class="directory-min-width">
                            <span id="waitQueryBranches" class="directory-hide-element">
                                <nobr>
                                    <img src="<?= directoryModule::getPublishImage('/wait.gif')?>">
                                    <span><?= directoryModule::ht('search', 'processing request')?></span>
                                </nobr>
                            </span>
                            <div id="errorQueryBranches" class="directory-error-msg directory-hide-element"></div>
                            <div id="okQueryBranches" class="directory-ok-msg directory-hide-element"></div>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="directory-table-wrap">
            </div>
        </td>
    </tr>
</table>
