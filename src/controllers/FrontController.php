<?php

namespace ityakutia\quiz\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use ityakutia\quiz\models\Quiz;
use ityakutia\quiz\models\QuizSearch;


class FrontController extends Controller
{
    public function actionIndex(){
        $searchModel = new QuizSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($slug)
    {
        $model = $this->findModelBySlug($slug);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Quiz::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelBySlug($slug)
    {
        if (($model = Quiz::find()->where(['is_publish' => true, 'slug' => $slug])->one()) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}