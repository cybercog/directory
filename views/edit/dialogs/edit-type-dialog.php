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

?>

<?= SingletonRenderHelper::widget(['viewsRequire' => [
    ['name' => '/helpers/ajax-post-helper']
    ]]) ?>

<div class="directory-hide-element">

<?php 
Dialog::begin([
    'id' => 'editDialog'.$uid,
    'clientOptions' => [
        'modal' => true,
        'autoOpen' => false,
        'resizable' => false,
        'width' => 600
    ],
]); ?>

<?php $form = ActiveForm::begin([
    'id' => 'type-data-form'.$uid,
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
                        <span class="directory-form-label-right-padding"><?= Html::activeLabel($formModel, 'type')?><span class="directory-required-input">*</span></span>
                    </nobr>
                </div>
            </td>
            <td>
                <div class="directory-form-item-bottom-padding">
                <?= Html::activeDropDownList($formModel, 'type', 
                                            ['string' => directoryModule::ht('edit', 'string'), 
                                                'text' => directoryModule::ht('edit', 'text'), 
                                                'image' => directoryModule::ht('edit', 'image'), 
                                                'file' => directoryModule::ht('edit', 'file')], 
                                            ['class'=>'directory-stretch-bar directory-grid-filter-control']) ; ?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="directory-min-width directory-table-label">
                <div class="directory-right-padding">
                    <nobr>
                        <span class="directory-form-label-right-padding"><?= Html::activeLabel($formModel, 'validate')?></span>
                    </nobr>
                </div>
            </td>
            <td>
                <div class="directory-form-item-bottom-padding">
                    <div>
                    <?= Html::activeTextarea($formModel, 'validate', ['class'=>'directory-stretch-bar directory-grid-filter-control', 'rows' => 4]) ; ?>
                    </div>
                    <div class="directory-tooltip-message directory-form-item-bottom-padding">
                        <?= directoryModule::ht('edit', 'regular expression to validate the entered string')?>
                    </div>
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
    </table>
    <div>
        <span id="wait<?=$uid?>" class="directory-hide-element">
            <nobr>
                <img src="<?= directoryModule::getPublishPath('/img/wait.gif')?>">
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
        $.editTypeDialog = function(p) {
            if(p !== undefined) {
                if(p.type !== undefined) {
                    switch(p.type) {
                        case "new":
                            (function(p) {
                                $("#type-data-form<?=$uid?>").trigger('reset');
                                $("#editDialog<?=$uid?>").
                                        dialog("option", "title", "<?= directoryModule::ht('edit', 'Create new type')?>").
                                        dialog("option", "buttons", 
                                        [
                                            {
                                                text : "<?= directoryModule::ht('edit', 'Create new type')?>",
                                                click : function() {
                                                    $.ajaxPostHelper({
                                                        url : "<?=Url::toRoute(['/directory/edit/types', 'cmd' => 'create', 'return' => 'ok'])?>",
                                                        data : $("#type-data-form<?=$uid?>").serialize(),
                                                        waitTag : "#wait<?=$uid?>",
                                                        errorTag : "#error<?=$uid?>",
                                                        errorWaitTimeout : 5,
                                                        onSuccess : function(dataObject) { 
                                                            $("#editDialog<?=$uid?>").dialog("close");
                                                            if(p.onSuccess !== undefined) {
                                                                if((dataObject !== undefined) &&
                                                                        (dataObject.<?=ajaxJSONResponseHelper::additionalField?> !== undefined)) {
                                                                    p.onSuccess(dataObject.<?=ajaxJSONResponseHelper::additionalField?>);
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
                                                click : function() { $("#editDialog<?=$uid?>").dialog("close"); }
                                            }
                                        ]).
                                dialog("open");

                            })(p);
                            break;
                        case "edit":
                            (function(p) {
                                $("#type-data-form<?=$uid?>").trigger('reset');
                                $("#type-data-form<?=$uid?> [name='<?=Html::getInputName($formModel, 'name')?>']").val(p.data.name);
                                $("#type-data-form<?=$uid?> [name='<?=Html::getInputName($formModel, 'type')?>']").val(p.data.type);
                                $("#type-data-form<?=$uid?> [name='<?=Html::getInputName($formModel, 'validate')?>']").val(p.data.validate);
                                $("#type-data-form<?=$uid?> [name='<?=Html::getInputName($formModel, 'description')?>']").val(p.data.description);
                                $("#editDialog<?=$uid?>").
                                    dialog("option", "title", "<?= directoryModule::ht('edit', 'Edit type')?>").
                                    dialog("option", "buttons", 
                                        [
                                            {
                                                text : "<?= directoryModule::ht('edit', 'Apply')?>",
                                                click : function() {
                                                    $.ajaxPostHelper({
                                                        url : ("<?=Url::toRoute(['/directory/edit/types', 'cmd' => 'update', 'id' => $uid])?>").replace("<?=$uid?>", p.data.id),
                                                        data : $("#type-data-form<?=$uid?>").serialize(),
                                                        waitTag : "#wait<?=$uid?>",
                                                        errorTag : "#error<?=$uid?>",
                                                        errorWaitTimeout: 5,
                                                        onSuccess: function(dataObject) { 
                                                            $("#editDialog<?=$uid?>").dialog("close");
                                                            if(p.onSuccess !== undefined) {
                                                                if((dataObject !== undefined) &&
                                                                        (dataObject.<?=ajaxJSONResponseHelper::additionalField?> !== undefined)) {
                                                                    p.onSuccess(dataObject.<?=ajaxJSONResponseHelper::additionalField?>);
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
                                                click : function() { $("#editDialog<?=$uid?>").dialog("close"); }
                                            }
                                        ]).
                                    dialog("open");
                            })(p);
                            break;
                        default:
                            if(p.onError !== undefined) {
                               p.onError({message : "<?=directoryModule::ht('edit', 'Error: invalid call parameters.')?>"}); 
                            }
                            break;
                    }
                } else {
                    if(p.onError !== undefined) {
                       p.onError({message : "<?=directoryModule::ht('edit', 'Error: invalid call parameters.')?>"}); 
                    }
                }
            }
        };
    })(jQuery);
    
<?php $this->registerJs(ob_get_clean(), View::POS_READY); if(false) { ?></script><?php } ?>