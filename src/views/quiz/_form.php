<?php

use uraankhayayaal\materializecomponents\checkbox\WCheckbox;
use uraankhayayaal\materializecomponents\imgcropper\Cropper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="quiz-form">

    <?php $form = ActiveForm::begin([
        'errorCssClass' => 'red-text',
    ]); ?>

    <div class="row">
        <div class="col s12 m6">
            <?= WCheckbox::widget(['model' => $model, 'attribute' => 'is_publish']); ?>
        </div>
        <div class="col s12 m6">
            <?= $form->field($model, 'type')->hiddenInput(['value' => $model::TYPE_RATING])->label(false) ?>
            <?php // $form->field($model, 'type')->dropDownList($model::TYPES) ?>
        </div>
    </div>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <?php if (!$model->isNewRecord) { ?>
    <div class="input-field">
        <input disabled value="<?= '/'.$model->slug; ?>" id="disabled" type="text" class="validate">
        <label for="disabled">Относительный url страницы</label>
    </div>

    <div class="input-field">
        <input disabled value="<?= Yii::$app->params['domain'].$model->slug; ?>" id="disabled" type="text" class="validate">
        <label for="disabled">Асолютный url страницы</label>
        <?= Html::a("Перейти", '/quiz/'. $model->slug, ['target' => "_blank"]); ?>
    </div>
    <?php } ?>

    <?= $form->field($model, 'photo')->widget(Cropper::class, [
        'aspectRatio' => 720/1280,
        'maxSize' => [1280, 720, 'px'],
        'minSize' => [128, 72, 'px'],
        'startSize' => [100, 100, '%'],
        'uploadUrl' => Url::to(['/quiz/quiz/uploadImg']),
    ]); ?>
    <small class="grey-text">Загружать изображения размером 1280x720 пикселей</small>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn']) ?>
    </div>
    <div class="fixed-action-btn">
        <?= Html::submitButton('<i class="material-icons">save</i>', [
            'class' => 'btn-floating btn-large waves-effect waves-light tooltipped',
            'title' => 'Сохранить',
            'data-position' => "left",
            'data-tooltip' => "Сохранить",
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
