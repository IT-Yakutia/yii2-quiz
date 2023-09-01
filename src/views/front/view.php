<?php

use ityakutia\share\ShareWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => "Все конкурсы", 'url' => ['/quiz/front/index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<section class="container quiz-view">
    <div class="row">
        <div class="col-12 col-md-3">
            <p><?= Html::img($model->photo, ['alt' => $model->title, 'class' => 'w-100 mw-100']) ?></p>
            <h4>Поделиться в социальных сетях:</h4>
            <?= ShareWidget::widget(); ?>
        </div>
        <div class="col-12 col-md-9">

            <h3 class="styled d-flex justify-content-between">
                <span><?= $this->title ?></span>
            </h3>
            <p><small><time><?= Yii::$app->formatter->asDatetime($model->created_at) ?></time></small></p>

            <?php $form = ActiveForm::begin(['errorCssClass' => 'red-text']); ?>
                <?= $form->field($quizAnswer, 'quiz_id')->hiddenInput(['maxlength' => true, 'value' => $model->id])->label(false); ?>
                <?php foreach ($model->quizQuestions as $key => $quizQuestion) { ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="card-title"><?= $quizQuestion->title ?></h3>
                        <?php foreach ($quizQuestion->quizOptions as $key => $quizOption) { ?>
                            <div class="option" data-id="<?= $quizOption->id ?>">
                                <?php if($quizQuestion->type == $quizQuestion::TYPE_MULTISELECT) { ?>    
                                    <input id="<?= $model->id ?>-<?= $quizQuestion->id ?>-<?= $quizOption->id ?>" name="<?= $quizAnswer->formName() ?>[quiz_answers][<?= $quizQuestion->id ?>][<?= $quizOption->id ?>]" type="checkbox">
                                <?php }else{ ?>
                                    <input id="<?= $model->id ?>-<?= $quizQuestion->id ?>-<?= $quizOption->id ?>" name="<?= $quizAnswer->formName() ?>[quiz_answers][<?= $quizQuestion->id ?>][radio]" value="<?= $quizOption->id ?>" type="radio">
                                <?php } ?>
                                <label for="<?= $model->id ?>-<?= $quizQuestion->id ?>-<?= $quizOption->id ?>"><?= $quizOption->title ?></label>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
                <div class="form-group d-flex justify-content-between">
                    <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Посмотреть результат', ['/quiz/front/result', 'slug' => $model->slug], ['class' => '']) ?>
                </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</section>