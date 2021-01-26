<?php

namespace backend\controllers;

use Yii;
use common\models\Apple;
use backend\models\AppleSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AppleController implements the CRUD actions for Apple model.
 */
class AppleController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Apple models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AppleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * generates random count Apple models.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionGenerate()
    {
        $count = rand(1, 100);
        $colors = Apple::$colors;
        for ($i = 0; $i < $count; $i++) {
            $randomColor = $colors[rand(0, 3)];
            $model = new Apple($randomColor);
            $model->save();
        }
        Yii::$app->session->setFlash('success', "Successfully generated {$i} apples");
        return $this->redirect(['index']);
    }


    /**
     * Deletes all existing Apple models.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionDeleteAll()
    {
        Apple::deleteAll();
        return $this->redirect(['index']);
    }


    /**
     * Deletes an existing Apple model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Apple model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Apple the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Apple::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Eat apple function
     * @return mixed
     */
    public function actionEat() {
        if (!empty($_POST['editableKey'])) {
            // use Yii's response format to encode output as JSON
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $model = $this->findModel($_POST['editableKey']);
            $size = round($_POST['Apple'][$_POST['editableIndex']]['size'], 2);
            $message = $model->eat($size);
            Yii::$app->session->setFlash('warning', $message);
            return [ 'output' => round($model->size, 2), 'message' => ''];
        }

        return $this->redirect(['index']);
    }

    /**
     * Apple Fall to ground
     * @return mixed
     */
    public function actionFall($id) {
        $model = $this->findModel($id);
        if (!$model->on_tree) {
            Yii::$app->session->setFlash('danger', 'Apple is already on the ground');
        } elseif ($model->fallToGround()) {
            Yii::$app->session->setFlash('success', 'Success! Apple fell to the ground');
        } else {
            Yii::$app->session->setFlash('danger', 'Something went wrong');
        }
        return $this->redirect(['index']);
    }
}
