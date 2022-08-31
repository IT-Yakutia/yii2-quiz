<?php

use uraankhayayaal\materializecomponents\imgcropper\Cropper;
use uraankhayayaal\materializecomponents\checkbox\WCheckbox;
use ityakutia\quiz\models\Quiz;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

?>

<div class="quiz-result-form">

    <?php $form = ActiveForm::begin([
        'errorCssClass' => 'red-text',
    ]); ?>

    <?= WCheckbox::widget(['model' => $model, 'attribute' => 'is_publish']); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php $label = Quiz::TYPE_RATING === $model->quiz->type ? 'Description (Чтобы указать количество баллов - добавьте к тексту [RATE])' : 'Description'; ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 6, 'class' => 'materialize-textarea'])->label($label) ?>

    <?= $form->field($model, 'quiz_id')->dropDownList(ArrayHelper::map(Quiz::find()->all(),'id','title'), ['prompt' => 'Выберите']) ?>

    <?php
        if(Quiz::TYPE_RATING === $model->quiz->type) {
            ?>
            <?= $form->field($model, 'min_limit')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'max_limit')->textInput(['maxlength' => true]) ?>
            <?php
        }
    ?>

    <?= $form->field($model, 'photo')->widget(Cropper::class, [
        'aspectRatio' => 350/350,
        'maxSize' => [350, 350, 'px'],
        'minSize' => [10, 10, 'px'],
        'startSize' => [100, 100, '%'],
        'uploadUrl' => Url::to(['/quiz/quiz-result/uploadImg']),
    ]); ?>
    <small class="grey-text">Загружать изображения размером 350х350 пикселей</small>

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
