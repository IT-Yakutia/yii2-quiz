<?php


use uraankhayayaal\materializecomponents\checkbox\WCheckbox;
use uraankhayayaal\materializecomponents\imgcropper\Cropper;
use ityakutia\quiz\models\Quiz;
use ityakutia\quiz\models\QuizQuestion;
use ityakutia\quiz\models\QuizResult;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


$quiz = Quiz::find()->where(['id' => $question->quiz_id])->one();
?>

<div class="quiz-form">

    <?php $form = ActiveForm::begin([
        'errorCssClass' => 'red-text',
    ]); ?>

    <div class="row">
        <div class="col s12 m2">
            <?= WCheckbox::widget(['model' => $model, 'attribute' => 'is_publish']); ?>
        </div>

        <?php if($quiz->type === 1) { ?>
            <div class="col s12 m2">
                <?= WCheckbox::widget(['model' => $model, 'attribute' => 'correct_answer']); ?>
            </div>
        <?php } ?>

        <div class="col s12 m8">
            <?= $form->field($model, 'type')->dropDownList($model::TYPES, ['prompt' => 'Выберите']) ?>
        </div>
    </div>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'quiz_question_id')->dropDownList(ArrayHelper::map(QuizQuestion::find()->where(['quiz_id' => $question->quiz_id])->all(), 'id', 'title'), ['prompt' => 'Выберите']) ?>

    <?php
    if($quiz->type === 0) {
        $quiz_results = QuizResult::find()->where(['quiz_id' => $question->quiz_id])->all();
        foreach ($quiz_results as $key => $quiz_result) {
            ?>
            <div class="row">
                <div class="col s4">
                    <p class="switch">
                        <label>
                            Deny
                            <input type="checkbox"
                                   name="<?= Html::getInputName($model, 'quiz_result_ids'); ?>[<?= $quiz_result->id ?>]" <?= ($model->hasQuizResult($quiz_result->id) ? 'checked' : ''); ?>>
                            <span class="lever"></span>
                            Allow
                        </label>
                    </p>
                </div>
                <div class="col s4">
                    <?= $quiz_result->title ?>
                </div>
                <div class="col s4">
                    <?= $quiz_result->photo ?>
                </div>
            </div>
            <?php
        }
    }
    ?>

    <?= $form->field($model, 'src')->widget(Cropper::class, [
        'aspectRatio' => 350 / 540,
        'maxSize' => [540, 350, 'px'],
        'minSize' => [10, 10, 'px'],
        'startSize' => [100, 100, '%'],
        'uploadUrl' => Url::to(['/quiz/quiz-option/uploadImg']),
    ]); ?>
    <small class="grey-text">Загружать изображения размером 540x350 пикселей</small>

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
