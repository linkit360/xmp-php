<?php

namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use common\models\Users;
use common\models\Content\Publishers;

/**
 * ContentPublishersController implements the CRUD actions for Publishers model.
 */
class ContentPublishersController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'roles' => ['publishersManage'],
                        'allow' => true,
                    ],
                    [
                        'allow' => false,
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Publishers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $users = [];
        if (Yii::$app->user->can('Admin')) {
            $users = Users::find()
                ->select([
                    'username',
                    'id',
                ])
                ->indexBy('id')
                ->column();
        }

        $searchModel = new \frontend\models\Search\Publishers();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render(
            'index',
            [
                'model' => $searchModel,
                'dataProvider' => $dataProvider,
                'users' => $users,
            ]
        );
    }

    /**
     * Displays a single Publishers model.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render(
            'view',
            [
                'model' => $this->findModel($id),
            ]
        );
    }

    /**
     * Creates a new Publishers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Publishers();
        $model->status = 1;
        if ($model->load(Yii::$app->request->post())) {
            $model->id_user = Yii::$app->user->id;
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render(
            'create',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Updates an existing Publishers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            unset($model->id_user);
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render(
            'update',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Deletes an existing Publishers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = 0;
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Publishers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Publishers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Publishers::findOne($id);
        if ($model !== null) {
            if ($model->id_user === Yii::$app->user->id || Yii::$app->user->can('Admin')) {
                return $model;
            }
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
