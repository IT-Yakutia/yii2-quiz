<?php

use yii\helpers\Html;

$this->title = "Результат";
$this->params['breadcrumbs'][] = ['label' => "Все конкурсы", 'url' => ['/quiz/front/index']];
$this->params['breadcrumbs'][] = ['label' => $quiz->title, 'url' => ['/quiz/front/view', 'slug' => $quiz->slug]];
$this->params['breadcrumbs'][] = $this->title;

?>

<style>
    .option {
        border: solid 1px green;
        padding: 1rem;
        border-radius: 4px;
    }
</style>

<section class="container quiz-view">
    <div class="row">
        <div class="col-12 col-md-3">
            <p><?= Html::img($quiz->photo, ['alt' => $quiz->title, 'class' => 'mw-100']) ?></p>
            <h4>Поделиться в социальных сетях:</h4>
            <?= \ityakutia\share\ShareWidget::widget(); ?>
        </div>
        <div class="col-12 col-md-9">
            <h3 class="styled d-flex justify-content-between">
                <span><?= $this->title ?></span>
            </h3>
            <p><small><time><?= Yii::$app->formatter->asDatetime($quizUserAnswer->created_at) ?></time></small></p>

            <!-- <?php if ($quizResult !== null) { ?>
                <p><?= $quizResult->title ?></p>
                <p><?= Html::img($quizResult->photo, ['alt' => $quizResult->title, 'class' => 'mw-100']) ?></p>
            <?php } else { ?>
                <p>Ваши ответы очень нас удивили, и мы не можем определить вам точный результат.</p>
            <?php } ?> -->

            <?php foreach ($quiz->quizQuestions as $key => $quizQuestion) { ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="card-title"><?= $quizQuestion->title ?></h3>
                        <form action=""></form>
                        <?php foreach ($quizQuestion->quizOptions as $key => $quizOption) { ?>
                            <?php
                                $selectedAnswerForMultiselect = in_array($quizOption->id, array_keys(json_decode($quizUserAnswer->answers, true)[$quizQuestion->id]));
                                $selectedAnswerForSingleSelect = json_decode($quizUserAnswer->answers, true)[$quizQuestion->id]['radio'] == $quizOption->id;
                            ?>

                            <div class="option mb-3 <?= $selectedAnswerForMultiselect || $selectedAnswerForSingleSelect ? ((($selectedAnswerForMultiselect || $selectedAnswerForSingleSelect) && $quizOption->correct_answer) ? 'alert-success' : 'alert-danger') : '' ?>" data-id="<?= $quizOption->id ?>">
                                <?php if($quizQuestion->type == $quizQuestion::TYPE_MULTISELECT) { ?>
                                    <input id="<?= $quiz->id ?>-<?= $quizQuestion->id ?>-<?= $quizOption->id ?>" class="<?= $quizOption->correct_answer ? 'is-valid' : 'is-invalid'?>" name="QuizAnswer[quiz_answers][<?= $quizQuestion->id ?>][<?= $quizOption->id ?>]" type="checkbox" <?= $selectedAnswerForMultiselect ? 'checked' : null ?> >
                                <?php }else{ ?>
                                    <input id="<?= $quiz->id ?>-<?= $quizQuestion->id ?>-<?= $quizOption->id ?>" class="<?= $quizOption->correct_answer ? 'is-valid' : 'is-invalid'?>" name="QuizAnswer[quiz_answers][<?= $quizQuestion->id ?>][radio]" value="<?= $quizOption->id ?>" type="radio" <?= $selectedAnswerForSingleSelect ? 'checked' : null ?> >
                                <?php } ?>
                                <label for="<?= $quiz->id ?>-<?= $quizQuestion->id ?>-<?= $quizOption->id ?>"><?= $quizOption->title ?></label>
                                <div class="valid-feedback">
                                    Правильный ответ!
                                </div>
                                <div class="invalid-feedback">
                                    Не правильный.
                                </div>
                            </div>
                        <?php } ?>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>