<?php


namespace ityakutia\quiz\controllers;


use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use ityakutia\quiz\models\QuizQuestion;
use ityakutia\quiz\models\QuizQuestionSearch;
use ityakutia\quiz\models\Quiz;
use uraankhayayaal\sortable\actions\Sorting;


class QuizQuestionController extends Controller
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
            'sorting' => [
                'class' => Sorting::class,
                'query' => QuizQuestion::find(),
            ],
        ];
    }

    public function actionIndex(){
        $searchModel = new QuizQuestionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($quiz_id = null){
        $model = new QuizQuestion();
        $model->quiz_id = $quiz_id;
        $model->type = $model::TYPE_SINGLESELECT;
        $quiz = Quiz::find()->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Запись успешно создана!');
            if($quiz_id == null)
                return $this->redirect(Url::previous());
            else
                return $this->redirect(['/quiz/quiz/view', 'id' => $quiz_id, 'question_id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'quiz' => $quiz,
        ]);
    }

    public function actionUpdate($id, $quiz_id = null)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Запись успешно изменена!');
            if($quiz_id == null)
                return $this->redirect(Url::previous());
            else
                return $this->redirect(['/quiz/quiz/view', 'id' => $quiz_id, 'question_id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
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

    public function actionDelete($id, $quiz_id = null)
    {
        if($this->findModel($id)->delete() !== false)
            Yii::$app->session->setFlash('success', 'Запись успешно удалена!');
        if($quiz_id == null)
            return $this->redirect(Url::previous());
        else
            return $this->redirect(['/quiz/quiz/view', 'id' => $quiz_id]);
    }

    protected function findModel($id)
    {
        if (($model = QuizQuestion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}