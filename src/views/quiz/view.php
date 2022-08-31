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
            <table>
                <tbody>
                    <tr>
                        <td>
                            <?= Html::a('Редактировать конкурс', ['update', 'id' => $model->id], ['class' => 'btn']) ?>
                        </td>
                        <td>
                            Тип конкурса: <span class="chip <?= ($model->type === $model::TYPE_RATING ? 'lime' : 'cyan') ?> lighten-4"><?= $model::TYPES[$model->type] ?></span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="fixed-action-btn">
                <?= Html::a('<i class="material-icons">edit</i>', ['update', 'id' => $model->id], [
                    'class' => 'btn-floating btn-large waves-effect waves-light tooltipped',
                    'title' => 'Редактировать',
                    'data-position' => "left",
                    'data-tooltip' => "Редактировать",
                ]) ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m3">
            <h5>Результаты</h5>
            <?= Html::a('Добавить результат', ['/quiz/quiz-result/create', 'quiz_id' => $model->id], ['class' => 'btn']) ?>

            <div class="row">
                <div class="col s12">
                    <table class="striped bordered my-responsive-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Наименование</th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($model->quizResults as $key => $result) { ?>
                                <tr>
                                    <td>
                                        <?= Html::a('<i class="material-icons">edit</i>', ['/quiz/quiz-result/update', 'id' => $result->id, 'quiz_id' => $result->quiz_id]) ?>
                                    </td>
                                    <td>
                                        <?= $result->title ?>
                                    </td>
                                    <td>
                                        <?= Html::a('<i class="material-icons">delete</i>', ['/quiz/quiz-result/delete', 'id' => $result->id], ['data-confirm' => "Вы уверены, что хотите удалить этот элемент?", 'data-method' => "post"]) ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if (empty($model->quizResults)) { ?>
                                <tr>
                                    <td colspan="2">
                                        Ничего не найдено.
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col s12 m3">
            <h5>Вопросы</h5>
            <div>
                <?= Html::a('Добавить вопрос', ['/quiz/quiz-question/create', 'quiz_id' => $model->id], ['class' => 'btn']) ?>
            </div>
            <ul class="collection" style="overflow: visible;">
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
                            <a class='dropdown-trigger secondary-content' href='#' data-target='dropdown-<?= $question->id ?>'><i class="material-icons">more</i></a>
                            <ul id='dropdown-<?= $question->id ?>' class='dropdown-content'>
                                <li><?= Html::a('<i class="material-icons">edit</i>', ['/quiz/quiz-question/update', 'id' => $question->id, 'quiz_id' => $model->id]) ?></li>
                                <li><?= Html::a('<i class="material-icons">delete</i>', ['/quiz/quiz-question/delete', 'id' => $question->id, 'quiz_id' => $model->id], ['data-confirm' => "Вы уверены, что хотите удалить этот элемент?", 'data-method' => "post"]) ?></li>
                                <li class="divider" tabindex="-1"></li>
                                <li><a class="disabled"><i class="material-icons <?= ($question->is_publish ? '' : 'red-text') ?>"><?= ($question->is_publish ? 'done' : 'clear') ?></i></a></li>
                            </ul>
                            <?= Html::a($question->title, ['view', 'id' => $model->id, 'question_id' => $question->id], ['class' => 'truncate ' . $question_text_active, 'style' => 'width: calc(100% - 30px);']) ?>
                        </div>
                    </li>
                    <?php
                }
                ?>
                <?php if (empty($model->quizQuestions)) { ?>
                    <li class="collection-item empty">
                        Ничего не найдено.
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="col s12 m6">
            <h5>Варианты ответов</h5>
            <?php if (empty($model->quizQuestions)) { ?>
                <p class="grey-text"><?= Html::a('Добавьте', ['/quiz/quiz-question/create', 'quiz_id' => $model->id]) ?> вопросы для конкурса.</p>
            <?php } ?>
            <?php if (empty($model->quizResults)){ ?>
                <p class="grey-text"><?= Html::a('Добавьте', ['/quiz/quiz-result/create', 'quiz_id' => $model->id]) ?> результаты конкурса.</p>
            <?php } ?>
            <?php if(empty($model->quizQuestions) || $activeQuestion == null) { ?>
                <p class="grey-text">Выберите вопрос слева.</p>
            <?php } else { ?>
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

                        // [
                        //     'header' => 'Фото',
                        //     'format' => 'raw',
                        //     'value' => function($model) {
                        //         return $model->src ? '<img class="materialboxed" src="' . $model->src . '" width="70">' : '';
                        //     }
                        // ],
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

<?php

$this->registerJS("
    $('.dropdown-trigger').dropdown();
", static::POS_READY);

?>