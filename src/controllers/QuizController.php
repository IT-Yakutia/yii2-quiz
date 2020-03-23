<?php

namespace ityakutia\quiz\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use ityakutia\quiz\models\Quiz;
use ityakutia\quiz\models\QuizSearch;
use ityakutia\quiz\models\QuizQuestion;
use ityakutia\quiz\models\QuizOptionSearch;

use uraankhayayaal\sortable\actions\Sorting;
use uraankhayayaal\materializecomponents\imgcropper\actions\UploadAction;



class QuizController extends Controller
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
                'url' => '/images/uploads/quiz/',
                'path' => '@frontend/web/images/uploads/quiz/',
                'maxSize' => 10485760,
            ],
            'sorting' => [
                'class' => Sorting::class,
                'query' => Quiz::find(),
            ],
        ];
    }

    public function actionIndex(){
        $searchModel = new QuizSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(){
        $model = new Quiz();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Запись успешно создана!');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Запись успешно изменена!');
            return $this->redirect(Url::previous());
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionView($id, $question_id = null)
    {
        $model = $this->findModel($id);
        $activeQuestion = QuizQuestion::findOne($question_id);

        $searchModel = new QuizOptionSearch();
        $searchModel->quiz_id = $model->id;
        $searchModel->quiz_question_id = $activeQuestion != null ? $activeQuestion->id : null;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Запись успешно изменена!');
            return $this->redirect(Url::previous());
        }

        return $this->render('view', [
            'model' => $model,
            'activeQuestion' => $activeQuestion,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDelete($id)
    {
        if($this->findModel($id)->delete() !== false)
            Yii::$app->session->setFlash('success', 'Запись успешно удалена!');
        return $this->redirect(Url::previous());
    }

    protected function findModel($id)
    {
        if (($model = Quiz::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}