<?php

namespace frontend\controllers;

use common\models\Users;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use common\models\MsisdnBlacklist;

use frontend\models\Search\Blacklist;

/**
 * BlacklistController implements the CRUD actions for MsisdnBlacklist model.
 */
class BlacklistController extends Controller
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
                        'roles' => [
                            'blacklistManage',
                        ],
                    ],
                    [
                        'allow' => false,
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all MsisdnBlacklist models.
     * @return mixed
     */
    public function actionIndex()
    {
        $users = Users::find()
            ->select([
                'username',
                'id',
            ])
            ->indexBy('id')
            ->column();

        $model = new Blacklist();
        $dataProvider = $model->search(Yii::$app->request->queryParams);

        return $this->render(
            'index',
            [
                'model' => $model,
                'dataProvider' => $dataProvider,
                'users' => $users,
            ]
        );
    }

    /**
     * Displays a single MsisdnBlacklist model.
     *
     * @param integer $id
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
     * Creates a new MsisdnBlacklist model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MsisdnBlacklist();
        $model->load(Yii::$app->request->post(), 'Blacklist');
        $model->id_user = Yii::$app->user->id;
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing MsisdnBlacklist model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MsisdnBlacklist model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return MsisdnBlacklist the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MsisdnBlacklist::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
