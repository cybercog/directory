<?php 

use yii\jui\Dialog;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;

use app\modules\directory\directoryModule;
use app\modules\directory\widgets\SingletonRenderHelper;
use app\modules\directory\helpers\ajaxJSONResponseHelper;

$uid = mt_rand(0, mt_getrandmax());

$formModel = new \app\modules\directory\models\forms\DirectoryForm();

?>

<?= SingletonRenderHelper::widget(['viewsRequire' => [
    ['name' => '/helpers/ajax-post-helper']
    ]]) ?>

<div class="directory-hide-element">
    
<?php 
Dialog::begin([
    'id' => 'editDirectoryDialog'.$uid,
    'clientOptions' => [
        'modal' => true,
        'autoOpen' => false,
        'resizable' => false,
        'width' => 600
    ],
]); ?>

<?php $form = ActiveForm::begin([
    'id' => 'directory-form'.$uid,
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
        
        $.editDirectoryDialog = function(p) {
            if(p !== undefined) {
                if(p.type !== undefined) {
                    switch(p.type) {
                        case 'new':
                            (function(p) {
                                $("#directory-form<?=$uid?>").trigger('reset');
                                $("#editDirectoryDialog<?=$uid?>").
                                        dialog("option", "title", "<?= directoryModule::ht('edit', 'Create new directory')?>").
                                        dialog("option", "buttons", 
                                        [
                                            {
                                                text : "<?= directoryModule::ht('edit', 'Create new directory')?>",
                                                click : function() {
                                                    $.ajaxPostHelper({
                                                        url : ("<?=Url::toRoute(['/directory/edit/directories', 'cmd' => 'create'])?>"),
                                                        data : $("#directory-form<?=$uid?>").serialize(),
                                                        waitTag : "#wait<?=$uid?>",
                                                        errorTag : "#error<?=$uid?>",
                                                        errorWaitTimeout : 5,
                                                        onSuccess : function(dataObject) { 
                                                            $("#editDirectoryDialog<?=$uid?>").dialog("close");
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
                                                click : function() {$("#editDirectoryDialog<?=$uid?>").dialog("close"); }
                                            }
                                        ]).dialog("open");
                            })(p);
                            break;
                        case 'edit':
                            (function(p) {
                                if(p.data === undefined) {
                                    if(p.onError !== undefined) {
                                       p.onError({message : "<?=directoryModule::ht('edit', 'Error: invalid call parameters.')?>"}); 
                                    } else {
                                        alert("<?=directoryModule::ht('edit', 'Error: invalid call parameters.')?>");
                                    }
                                    
                                    return;
                                } 
                                
                                $("#directory-form<?=$uid?>").trigger('reset');
                                $("#directory-form<?=$uid?> [name='<?=Html::getInputName($formModel, 'name')?>']").val(p.data.original_name);
                                $("#directory-form<?=$uid?> [name='<?=Html::getInputName($formModel, 'description')?>']").val(p.data.original_description);
                                $("#directory-form<?=$uid?> [name='<?=Html::getInputName($formModel, 'visible')?>']").prop("checked", p.data.visible);
                                
                                $("#editDirectoryDialog<?=$uid?>").
                                        dialog("option", "title", "<?= directoryModule::ht('edit', 'Edit directory')?>").
                                        dialog("option", "buttons", 
                                        [
                                            {
                                                text : "<?= directoryModule::ht('edit', 'Apply')?>",
                                                click : function() {
                                                    $.ajaxPostHelper({
                                                        url : ("<?=Url::toRoute(['/directory/edit/directories', 'cmd' => 'update', 'id' => $uid])?>").replace("<?=$uid?>", p.data.id),
                                                        data : $("#directory-form<?=$uid?>").serialize(),
                                                        waitTag : "#wait<?=$uid?>",
                                                        errorTag : "#error<?=$uid?>",
                                                        errorWaitTimeout : 5,
                                                        onSuccess : function(dataObject) { 
                                                            $("#editDirectoryDialog<?=$uid?>").dialog("close");
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
                                                click : function() {$("#editDirectoryDialog<?=$uid?>").dialog("close"); }
                                            }
                                        ]).dialog("open");
                            })(p);
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