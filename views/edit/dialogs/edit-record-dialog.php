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

$formModel = new \app\modules\directory\models\forms\RecordForm;

?>

<?= SingletonRenderHelper::widget(['viewsRequire' => [
    ['name' => '/helpers/ajax-post-helper'],
    ['name' => '/helpers/publish-result-css'],
    //['name' => '/edit/dialogs/edit-type-dialog'],
    ['name' => '/edit/dialogs/edit-data-dialog']
    ]]) ?>

<div class="directory-hide-element">
    
<?php 
Dialog::begin([
    'id' => 'editRecordDialog'.$uid,
    'clientOptions' => [
        'modal' => true,
        'autoOpen' => false,
        'resizable' => false,
        'width' => 600
    ],
]); ?>
    
    <?php $form = ActiveForm::begin([
    'id' => 'record-form'.$uid,
        ]); ?>
    
    <table class="directory-modal-table directory-stretch-bar directory-table">
        <tr>
            <td colspan="2">
                <div id="dataArray"></div>
                <div class="directory-min-width">
                    <div id="addDataToRecord">
                        <nobr>
                            <span class="directory-add-button-icon"><?= directoryModule::ht('edit', 'Add')?>...</span>
                        </nobr>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td class="directory-min-width"><?=Html::activeCheckbox($formModel, 'visible', ['label' => null])?></td>
            <td>&nbsp;-&nbsp;<?= Html::activeLabel($formModel, 'visible')?></td>
        </tr>
    </table>

    <?php ActiveForm::end(); ?>
    
<?php Dialog::end(); ?>

</div>

<?php if(false) { ?><script type="text/javascript"><?php } ob_start(); ?>
    
    (function($) {
        
        $("#record-form<?=$uid?> #addDataToRecord").button().click(function() {
            alert("ggggggggg");
        });
        
        $.editRecordDialog = function(p) {
            if(p !== undefined) {
                if(p.type !== undefined) {
                    switch(p.type) {
                        case "new":
                            (function(p) {
                                $("#editRecordDialog<?=$uid?>").
                                        dialog("option", "title", "<?= directoryModule::ht('edit', 'Create a new item')?>").
                                        dialog("option", "buttons", 
                                                        [
                                                            {
                                                                text : "<?= directoryModule::ht('edit', 'Add record')?>",
                                                                click : function() { $("#editRecordDialog<?=$uid?>").dialog("close"); }
                                                            },
                                                            {
                                                                text : "<?= directoryModule::ht('edit', 'Close')?>",
                                                                click : function() { $("#editRecordDialog<?=$uid?>").dialog("close"); }
                                                            }
                                                        ]
                                        ).
                                        dialog("open");
                            })(p);
                            break;
                        case "edit":
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