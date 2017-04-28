<?php

namespace frontend\controllers;

use function md5;
use function mt_rand;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use common\models\Campaigns;
use frontend\models\Campaigns\CampaignsForm;

/**
 * CampaignsController implements the CRUD actions for Campaigns model.
 */
class CampaignsController extends Controller
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
                        'allow' => true,
                        'roles' => ['campaignsManage'],
                    ],
                    [
                        'allow' => false,
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Campaigns models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new \frontend\models\Search\Campaigns();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render(
            'index',
            [
                'model' => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );


        $dataProvider = new ActiveDataProvider([
            'query' => Campaigns::find()->where(
                [
                    'id_user' => Yii::$app->user->id,
                    'status' => 1,
                ]
            ),
        ]);

        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
            ]
        );
    }

    /**
     * Displays a single Campaigns model.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if ($model->id_user !== Yii::$app->user->id) {
            return new NotFoundHttpException();
        }

        return $this->render(
            'view',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Creates a new Campaigns model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CampaignsForm();
        $model->status = 1;
        $model->link = md5(mt_rand(1, 999999));

        if ($model->load(Yii::$app->request->post()) && $model->commit()) {
            return $this->redirect(['index']);
        }

        return $this->render(
            'create',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Updates an existing Campaigns model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */

    public function actionUpdate($id)
    {
        $model = CampaignsForm::findOne($id);

        if ($model->id_user !== Yii::$app->user->id) {
            return new NotFoundHttpException();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render(
            'update',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Deletes an existing Campaigns model.
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
     * Finds the Campaigns model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Campaigns the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Campaigns::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
