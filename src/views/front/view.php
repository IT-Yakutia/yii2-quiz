<?php

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => "Все квизы", 'url' => ['/quiz/front/index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<section class="container quiz-view">
    <div class="row">
        <div class="col-12">
            <h1><?= $model->title ?></h1>
            <?php foreach ($model->quizQuestions as $key => $quizQuestion) { ?>
                <hr>
                <p><?= $quizQuestion->title ?></p>
                <ul>
                    <?php foreach ($quizQuestion->quizOptions as $key => $quizOption) { ?>
                        <li><p><?= $quizOption->title ?></p></li>
                    <?php } ?>
                </ul>
            <?php } ?>
        </div>
    </div>
</section>