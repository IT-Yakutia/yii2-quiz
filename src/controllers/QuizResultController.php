<?php

namespace ityakutia\quiz\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use ityakutia\quiz\models\QuizResult;
use ityakutia\quiz\models\QuizResultSearch;
use uraankhayayaal\materializecomponents\imgcropper\actions\UploadAction;
use yii\web\NotFoundHttpException;

class QuizResultController extends Controller
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
                'url' => '/images/uploads/quiz-result/',
                'path' => '@frontend/web/images/uploads/quiz-result/',
                'maxSize' => 10485760,
            ]
        ];
    }

    public function actionIndex(){
        $searchModel = new QuizResultSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($quiz_id = null){
        $model = new QuizResult();
        $model->quiz_id = $quiz_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Запись успешно создана!');
            if($quiz_id === null)
                return $this->redirect(Url::previous());
            else
                return $this->redirect(['/quiz/quiz/view', 'id' => $model->quiz_id]);
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
            return $this->redirect(['/quiz/quiz/view', 'id' => $model->quiz_id]);
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

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $quiz_id = $model->quiz_id;
        if($model->delete() !== false)
            Yii::$app->session->setFlash('success', 'Запись успешно удалена!');
        return $this->redirect(['/quiz/quiz/view', 'id' => $quiz_id]);
    }

    protected function findModel($id)
    {
        if (($model = QuizResult::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}