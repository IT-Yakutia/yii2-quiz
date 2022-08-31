<?php

namespace ityakutia\quiz\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use ityakutia\quiz\models\Quiz;
use ityakutia\quiz\models\QuizSearch;
use ityakutia\quiz\models\QuizAnswer;
use ityakutia\quiz\models\QuizUserAnswer;


class FrontController extends Controller
{
    public function actionIndex(){
        $view = 'index';
        if(
            isset(Yii::$app->params['custom_view_for_modules']) && 
            isset(Yii::$app->params['custom_view_for_modules']['quiz_front']) && 
            isset(Yii::$app->params['custom_view_for_modules']['quiz_front']['index'])
        )
            $view = Yii::$app->params['custom_view_for_modules']['quiz_front']['index'];

        $searchModel = new QuizSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render($view, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($slug)
    {
        $view = 'view';
        if(
            isset(Yii::$app->params['custom_view_for_modules']) && 
            isset(Yii::$app->params['custom_view_for_modules']['quiz_front']) && 
            isset(Yii::$app->params['custom_view_for_modules']['quiz_front']['view'])
        )
            $view = Yii::$app->params['custom_view_for_modules']['quiz_front']['view'];

        $model = $this->findModelBySlug($slug);

        $quizAnswer = new QuizAnswer();
        
        if ($quizAnswer->load(Yii::$app->request->post()) && $quizUserAnswer = $quizAnswer->make()) {
            Yii::$app->session->setFlash('success', 'Ответы успешно получены!');
            return $this->redirect(['result', 'slug' => $model->slug, 'quizUserAnswerId' => $quizUserAnswer->id]);
        }

        return $this->render($view, [
            'model' => $model,
            'quizAnswer' => $quizAnswer,
        ]);
    }

    public function actionResult($slug, $quizUserAnswerId)
    {
        $view = 'view';
        if(
            isset(Yii::$app->params['custom_view_for_modules']) && 
            isset(Yii::$app->params['custom_view_for_modules']['quiz_front']) && 
            isset(Yii::$app->params['custom_view_for_modules']['quiz_front']['result'])
        )
            $view = Yii::$app->params['custom_view_for_modules']['quiz_front']['result'];

        $quiz = $this->findModelBySlug($slug);
        $quizUserAnswer = QuizUserAnswer::findOne($quizUserAnswerId);
        $model = new QuizAnswer();
        $quizResult = $model->result();

        return $this->render($view, [
            'quizResult' => $quizResult,
            'quiz' => $quiz,
            'quizUserAnswer' => $quizUserAnswer,
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