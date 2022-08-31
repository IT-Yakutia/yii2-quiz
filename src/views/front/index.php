<?php

use yii\widgets\ListView;
use yii\widgets\LinkPager;

$this->title = "Конкурсы";

// $this->params['breadcrumbs'][] = ['label' => "Все конкурсы", 'url' => ['/quiz/front/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="container quiz-index">
    <div class="row">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions' => ['class' => 'item'],
            'itemView' => function ($model, $key, $index, $widget){
                return $this->render('_item', [
                    'model' => $model,
                    'models' => $widget->dataProvider->models,
                    'key' => $key,
                    'index' => $index,
                ]);
            },
            'options' => ['tag' => false, 'class' => false, 'id' => false],
            'itemOptions' => [
                'tag' => false,
                'class' => 'quiz-item',
            ],
            'layout' => '{items}',
            'summaryOptions' => ['class' => 'summary grey-text'],
            'emptyTextOptions' => ['class' => 'empty grey-text'],
        ]) ?>
    </div>
    <div class="row">
        <?= LinkPager::widget([
            'pagination' => $dataProvider->pagination,
            'registerLinkTags' => true,
            'options' => ['class' => 'pagination'],
            'prevPageCssClass' => '',
            'nextPageCssClass' => '',
            'pageCssClass' => 'page-item',
            'nextPageLabel' => '>',
            'prevPageLabel' => '<',
            'linkOptions' => ['class' => 'page-link btn'],
            'disabledPageCssClass' => 'd-none',
        ]); ?>
    </div>
</section>