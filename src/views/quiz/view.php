<?php


use uraankhayayaal\sortable\grid\Column;
use uraankhayayaal\materializecomponents\grid\MaterialActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;




$this->title = $model->title;

if($model->type === 0) {
    $results = [
        'header' => 'Результаты к этому варианту',
        'format' => 'raw',
        'value' => function($modelOption) {
            $photos = '<p>';
            foreach ($modelOption->quizResults as $key => $quizResult) {
                $photos .= ($quizResult->photo ? ('<img class="materialboxed" src="' . $quizResult->photo . '" width="25">') : '') . Html::a($quizResult->title, ['/quiz/quiz-result/update', 'id' => $quizResult->id]) . '</p><p>';
            }
            return $photos . '</p>';
        },
        'filter' => ArrayHelper::map($model->quizResults, 'id', 'title'),
    ];
}

if($model->type === 1) {
    $results = [
        'header' => 'Правильность ответа',
        'format' => 'raw',
        'value' => function($modelOption) {
            return $modelOption->correct_answer ? 'Правильный' : 'Неправильный';
        }
    ];
}

?>

<div class="quiz-view">
    <div class="row">
        <div class="col s12">
            <ul class="collapsible">
                <li>
                    <div class="collapsible-header tooltipped" data-position="bottom"
                         data-tooltip="<?= Yii::t('app', 'Нажмите чтобы открыть или закрыть') ?>"><i
                                class="material-icons">filter_drama</i>Результаты
                    </div>
                    <div class="collapsible-body">

                        <?= Html::a('Добавить результат', ['/quiz/quiz-result/create', 'quiz_id' => $model->id], ['class' => 'btn']) ?>

                        <div class="row">
                            <?php foreach ($model->quizResults as $key => $result) { ?>
                                <div class="col s6 m4 l2">
                                    <div class="card">
                                        <div class="card-image waves-effect waves-block waves-light">
                                            <img class="activator" src="<?= $result->photo ?>"
                                                 alt="<?= $result->title ?>">
                                        </div>
                                        <div class="card-content">
                                            <span class="card-title activator grey-text text-darken-4"><?= $result->title ?><i
                                                        class="material-icons right">more_vert</i></span>
                                            <p>
                                                <?= Html::a('<i class="material-icons">edit</i>', ['/quiz/quiz-result/update', 'id' => $result->id, 'quiz_id' => $result->quiz_id]) ?>
                                                <?= Html::a('<i class="material-icons">delete</i>', ['/quiz/quiz-result/delete', 'id' => $result->id], ['data-confirm' => "Вы уверены, что хотите удалить этот элемент?", 'data-method' => "post"]) ?>
                                            </p>
                                        </div>
                                        <div class="card-reveal">
                                            <span class="card-title grey-text text-darken-4"><?= $result->title ?><i
                                                        class="material-icons right">close</i></span>
                                            <p><?= $result->description ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
        </div>
        </li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col s12 m4">
        <h5>Вопросы</h5>
        <div>
            <?= Html::a('Добавить вопрос', ['/quiz/quiz-question/create', 'quiz_id' => $model->id], ['class' => 'btn']) ?>
        </div>
        <ul class="collection">
            <?php
            foreach ($model->quizQuestions as $key => $question) {
                $question_active = '';
                $question_text_active = 'black-text';
                if($activeQuestion !== null && $question->id == $activeQuestion->id) {
                    $question_active = 'active';
                    $question_text_active = 'white-text';
                }

                ?>
                <li class="collection-item <?= $question_active ?>">
                    <div>
                        <?= Html::a('<i class="material-icons ' . ($question->is_publish ? '' : 'red-text') . '">' . ($question->is_publish ? 'done' : 'clear') . '</i>', ['view', 'id' => $model->id, 'question_id' => $question->id], ['class' => 'secondary-content']) ?>
                        <?= Html::a($question->title, ['view', 'id' => $model->id, 'question_id' => $question->id], ['class' => 'truncate ' . $question_text_active, 'style' => 'width: calc(100% - 30px);']) ?>
                    </div>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
    <div class="col s12 m8">
        <h5>Варианты ответов</h5>
        <?php if($activeQuestion == null) { ?>
            <p class="grey-text">Выберите вопрос слева или</p>
            <p class="grey-text">Добавьте сперва результаты
                квиза: <?= Html::a('Добавить результат', ['/quiz/quiz-result/create', 'quiz_id' => $model->id]) ?></p>
        <?php } else { ?>
            <?= Html::a('Редактировать вопрос', ['/quiz/quiz-question/update', 'id' => $activeQuestion->id, 'quiz_id' => $model->id], ['class' => 'btn']) ?>
            <?= Html::a('Удалить вопрос', ['/quiz/quiz-question/delete', 'id' => $activeQuestion->id, 'quiz_id' => $model->id], ['class' => 'btn', 'data-confirm' => "Вы уверены, что хотите удалить этот элемент?", 'data-method' => "post"]) ?>
            <hr>

            <?= Html::a('Добавить вариант ответа', ['/quiz/quiz-option/create', 'quiz_question_id' => $activeQuestion->id], ['class' => 'btn']) ?>
            <?= GridView::widget([
                'tableOptions' => [
                    'class' => 'striped bordered my-responsive-table',
                    'id' => 'sortable'
                ],
                'rowOptions' => function($model, $key, $index, $grid) {
                    return ['data-sortable-id' => $model->id];
                },
                'options' => [
                    'data' => [
                        'sortable-widget' => 1,
                        'sortable-url' => Url::toRoute(['/quiz/quiz-option/sorting']),
                    ]
                ],
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    ['class' => MaterialActionColumn::class, 'controller' => '/quiz/quiz-option', 'template' => '{view} {update}'],

                    [
                        'header' => 'Фото',
                        'format' => 'raw',
                        'value' => function($model) {
                            return $model->src ? '<img class="materialboxed" src="' . $model->src . '" width="70">' : '';
                        }
                    ],
                    [
                        'attribute' => 'title',
                        'header' => 'Вариант ответа',
                        'format' => 'raw',
                        'value' => function($model) {
                            return Html::a($model->title, ['/quiz/quiz-option/update', 'id' => $model->id]);
                        }
                    ],
                    $results,
                    [
                        'attribute' => 'is_publish',
                        'format' => 'raw',
                        'value' => function($model) {
                            return $model->is_publish ? '<i class="material-icons green-text">done</i>' : '<i class="material-icons red-text">clear</i>';
                        },
                        'filter' => [0 => 'Нет', 1 => 'Да'],
                    ],
                    ['class' => MaterialActionColumn::class, 'controller' => '/quiz/quiz-option', 'template' => '{delete}'],
                    [
                        'class' => Column::class,
                    ],
                ],
                'pager' => [
                    'class' => 'yii\widgets\LinkPager',
                    'options' => ['class' => 'pagination center'],
                    'prevPageCssClass' => '',
                    'nextPageCssClass' => '',
                    'pageCssClass' => 'waves-effect',
                    'nextPageLabel' => '<i class="material-icons">chevron_right</i>',
                    'prevPageLabel' => '<i class="material-icons">chevron_left</i>',
                ],
            ]); ?>
        <?php } ?>
    </div>
</div>
</div>