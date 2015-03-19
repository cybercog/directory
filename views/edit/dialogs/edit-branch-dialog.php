<?php 

use yii\jui\Dialog;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

use app\modules\directory\directoryModule;
use app\modules\directory\widgets\SingletonRenderHelper;
use app\modules\directory\helpers\ajaxJSONResponseHelper;

$uid = mt_rand(0, mt_getrandmax());

$formModel = new \app\modules\directory\models\forms\BranchForm;

?>

<?= SingletonRenderHelper::widget(['viewsRequire' => [
    ['name' => '/helpers/ajax-post-helper'],
    ['name' => '/helpers/publish-result-css'],
    //['name' => '/edit/dialogs/edit-type-dialog'],
    //['name' => '/edit/dialogs/select-type-dialog']
    ]]) ?>

<div class="directory-hide-element">
    
<?php 
Dialog::begin([
    'id' => 'editBranchDialog'.$uid,
    'clientOptions' => [
        'modal' => true,
        'autoOpen' => false,
        'resizable' => false,
        'width' => 600
    ],
]); ?>
    
<?php $form = ActiveForm::begin([
    'id' => 'branch-form'.$uid,
        ]); ?>
    
    
<div>
    <table class="directory-modal-table directory-stretch-bar directory-table">
        <tr>
            <td class="directory-min-width directory-table-label">
                <div class="directory-right-padding">
                    <nobr>
                        <span class="directory-form-label-right-padding"><?= Html::activeLabel($formModel, 'name')?><span class="directory-required-input">*</span></span>
                    </nobr>
                </div>
            </td>
            <td>
                <div class="directory-form-item-bottom-padding">
                <?= Html::activeInput('text', $formModel, 'name', ['class'=>'directory-stretch-bar directory-grid-filter-control']) ; ?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="directory-min-width directory-table-label">
                <div class="directory-right-padding">
                    <nobr>
                        <span class="directory-form-label-right-padding"><?= Html::activeLabel($formModel, 'description')?></span>
                    </nobr>
                </div>
            </td>
            <td>
                <div class="directory-form-item-bottom-padding">
                <?= Html::activeTextarea($formModel, 'description', ['class'=>'directory-stretch-bar directory-grid-filter-control', 'rows' => 7]) ; ?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="directory-min-width directory-table-label">
                <div class="directory-right-padding">
                    <nobr>
                        <span class="directory-form-label-right-padding"><?= Html::activeLabel($formModel, 'position')?><span class="directory-required-input">*</span></span>
                    </nobr>
                </div>
            </td>
            <td>
                <div class="directory-form-item-bottom-padding">
                <?= Html::activeInput('text', $formModel, 'position', ['class'=>'directory-stretch-bar directory-grid-filter-control']) ; ?>
                </div>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <div class="directory-form-item-bottom-padding">
                    <table class="directory-modal-table directory-stretch-bar directory-table">
                        <tr>
                            <td class="directory-min-width"><?= Html::activeCheckbox($formModel, 'visible', ['label' => null])?></td>
                            <td class="directory-min-width">&nbsp;</td>
                            <td>- <?= Html::activeLabel($formModel, 'visible')?></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
    <div>
        <span id="wait<?=$uid?>" class="directory-hide-element">
            <nobr>
                <img src="<?= directoryModule::getPublishImage('/wait.gif')?>">
                <span><?= directoryModule::ht('search', 'processing request')?></span>
            </nobr>
        </span>
        <div id="error<?=$uid?>" class="directory-error-msg directory-hide-element"></div>
        <div id="ok<?=$uid?>" class="directory-ok-msg directory-hide-element"></div>
    </div>
</div>
    
<?php ActiveForm::end(); ?>
    
<?php Dialog::end(); ?>
    
</div>

<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    (function($) {
        
        $.editBranchDialog = function(p) {
            
            if(p !== undefined) {
                if(p.type !== undefined) {
                    switch(p.type) {
                        case 'new':
                            (function(p) {
                                $("#branch-form<?=$uid?>").trigger('reset');


                                $("#editBranchDialog<?=$uid?>").
                                        dialog("option", "title", "<?= directoryModule::ht('edit', 'Create a new root branch')?>").
                                        dialog("option", "buttons", 
                                        [
                                            {
                                                text : "<?= directoryModule::ht('edit', 'Create a new root branch')?>",
                                                click : function() {
                                                    $.ajaxPostHelper({
                                                        url : ("<?=Url::toRoute(['/directory/edit/hierarchy', 'cmd' => 'create-root-branch', 'hierarchy'=>$uid])?>").replace("<?=$uid?>", p.hierarchy),
                                                        data : $("#branch-form<?=$uid?>").serialize(),
                                                        waitTag : "#wait<?=$uid?>",
                                                        errorTag : "#error<?=$uid?>",
                                                        errorWaitTimeout : 5,
                                                        onSuccess : function(dataObject) { 
                                                            $("#editBranchDialog<?=$uid?>").dialog("close");
                                                            if(p.onSuccess !== undefined) {
                                                                if((dataObject !== undefined) &&
                                                                        (dataObject.<?=ajaxJSONResponseHelper::messageField?> !== undefined)) {
                                                                    p.onSuccess(dataObject.<?=ajaxJSONResponseHelper::messageField?>);
                                                                } else {
                                                                   p.onSuccess();
                                                                }
                                                            }
                                                        }
                                                    });
                                                }
                                            },
                                            {
                                                text : "<?= directoryModule::ht('edit', 'Close')?>",
                                                click : function() {$("#editBranchDialog<?=$uid?>").dialog("close"); }
                                            }
                                        ]).dialog("open");


                            })(p);
                            break;
                        case 'edit':
                            break;
                        default:
                            if(p.onError !== undefined) {
                               p.onError({message : "<?=directoryModule::ht('edit', 'Error: invalid call parameters.')?>"}); 
                            } else {
                                alert("<?=directoryModule::ht('edit', 'Error: invalid call parameters.')?>");
                            }
                            break;
                    }
                } else {
                    if(p.onError !== undefined) {
                       p.onError({message : "<?=directoryModule::ht('edit', 'Error: invalid call parameters.')?>"}); 
                    } else {
                        alert("<?=directoryModule::ht('edit', 'Error: invalid call parameters.')?>");
                    }
                }
            } else {
                alert("<?=directoryModule::ht('edit', 'Error: invalid call parameters.')?>");
            }
        };
        
    })(jQuery);
    
<?php $this->registerJs(ob_get_clean(), View::POS_READY); if(false) { ?></script><?php } ?>