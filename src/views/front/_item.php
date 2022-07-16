<?php

use yii\helpers\Url;

?>

<div class="col-12 col-md-6">
    <a href="<?= Url::toRoute(['/quiz/front/view', 'slug' => $model->slug])?>" class="pb-4">
        <img class="w-100" src="<?= $model->photo ?>" alt="<?= $model->title; ?>">
        <time datetime="<?= Yii::$app->formatter->asDate($model->created_at); ?>"><?= Yii::$app->formatter->asDuration(time() - $model->created_at); ?></time>
        <p class="title"><?= $model->title; ?></p>
    </a>
</div>