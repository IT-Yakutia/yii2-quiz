<?php


namespace ityakutia\quiz\controllers;


use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use ityakutia\quiz\models\QuizOption;
use ityakutia\quiz\models\QuizOptionSearch;
use ityakutia\quiz\models\QuizQuestion;
use uraankhayayaal\materializecomponents\imgcropper\actions\UploadAction;
use uraankhayayaal\sortable\actions\Sorting;


class QuizOptionController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['quiz']
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'uploadImg' => [
                'class' => UploadAction::class,
                'url' => '/images/uploads/quiz-option/',
                'path' => '@frontend/web/images/uploads/quiz-option/',
                'maxSize' => 10485760,
            ],
            'sorting' => [
                'class' => Sorting::class,
                'query' => QuizOption::find(),
            ],
        ];
    }

    public function actionCreate($quiz_question_id = null){
        $model = new QuizOption();
        $model->type = $model::TYPE_STRING;
        $question = QuizQuestion::findOne($quiz_question_id);
        if ($quiz_question_id !== null && $question !== null) {
            $model->quiz_question_id = $question->id;
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Запись успешно создана!');

            if($question === null)
                return $this->redirect(Url::previous());
            else
                return $this->redirect(['/quiz/quiz/view', 'id' => $model->quizQuestion->quiz_id, 'question_id' => $model->quiz_question_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'question' => $question,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Запись успешно изменена!');
            return $this->redirect(['/quiz/quiz/view', 'id' => $model->quizQuestion->quiz_id, 'question_id' => $model->quiz_question_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'question' => $model->quizQuestion,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Запись успешно изменена!');
            return $this->redirect(Url::previous());
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $quiz_id = $model->quizQuestion->quiz_id;
        $question_id = $model->quiz_question_id;

        if($model->delete() !== false)
            Yii::$app->session->setFlash('success', 'Запись успешно удалена!');

        return $this->redirect(['/quiz/quiz/view', 'id' => $quiz_id, 'question_id' => $question_id]);
    }

    protected function findModel($id)
    {
        if (($model = QuizOption::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}