<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\directory\directoryModule;

?>

<?php $form = ActiveForm::begin([
    'id' => 'search-form',
    //'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'template' => "{input}",
        ]
        ]); ?>

    <?php if(false) { ?><style><?php } ob_start(); ?>
        .directory-search-bar-ext-search-button {
            background: url(<?= directoryModule::getPublishImage('/ext-search.png'); ?>) no-repeat;
            padding-left: 20px;
        }
    <?php $this->registerCss(ob_get_clean()); if(false) { ?></style><?php } ?>


<table class="directory-modal-table directory-stretch-bar">
    <tr>
        <td>
            <div class="directory-search-input">
                <?= Html::activeInput('text', $model, 'query', ['class'=>'directory-stretch-bar']) ; ?>
                <div>
                    <img src="<?= directoryModule::getPublishImage('/search-icon.png'); ?>">
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="directory-search-params-border">
                <table class="directory-modal-table directory-stretch-bar">
                    <tr>
                        <td class="directory-min-width"><nobr>Выберети иерархию:</nobr></td>
                        <td class="directory-min-width"></td>
                        <td class="directory-min-width"></td>
                        <td>&nbsp;</td>
                        <td class="directory-min-width">
                            <div class="directory-button-block">
                                <nobr>
                                    <span class="directory-search-bar-ext-search-button">Расширенный поиск</span>
                                </nobr>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>


<?php ActiveForm::end(); ?>