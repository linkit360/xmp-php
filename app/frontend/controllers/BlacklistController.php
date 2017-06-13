<?php

namespace frontend\controllers;

use common\models\Instances;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

use common\models\Users;
use common\models\MsisdnBlacklist;

use frontend\models\Search\Blacklist;

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
     *
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

    /**
     * Creates a new MsisdnBlacklist model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MsisdnBlacklist();
        $model->load(Yii::$app->request->post(), 'Blacklist');
        $model->id_user = Yii::$app->user->id;
        $model->save();

        $this->sendGoData($model, true);

        return $this->redirect(['index']);
    }

    /**
     * @param MsisdnBlacklist $model
     * @param bool            $create
     */
    public function sendGoData($model, $create = false)
    {
        // Get instance
        $instance = Instances::find()
            ->select("id")
            ->where([
                'status' => 1,
                'id_provider' => $model->id_provider,
            ])
            ->asArray()
            ->one();

        // If found - send notify
        if ($instance) {
            $payload = [];
            $payload['for'] = $instance["id"];

            $payload['type'] = 'blacklist.delete';
            if ($create) {
                $payload['type'] = 'blacklist.new';
            }

            $data = $model->attributes;
            $data["msisdn"] = (int)$data["msisdn"];
            unset(
                $data["id_user"],
                $data["id"],
                $data["created_at"],
                $data["id_operator"],
                $data["id_provider"]
            );

            $payload['data'] = json_encode($data, JSON_PRETTY_PRINT);
            $json = json_encode($payload, JSON_PRETTY_PRINT);

            Yii::$app->getDb()
                ->createCommand("NOTIFY xmp_update, '" . $json . "';")
                ->execute();
        }
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
        $model = $this->findModel($id);
        $this->sendGoData($model);
        $model->delete();

        return $this->redirect(['index']);
    }
}
