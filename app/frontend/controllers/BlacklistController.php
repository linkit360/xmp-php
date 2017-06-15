<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

use common\models\Users;
use common\models\Instances;
use common\models\MsisdnBlacklist;

use frontend\models\Search\Blacklist;

class BlacklistController extends Controller
{
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
     * @return string
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
     * @param $id
     *
     * @return string
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
     * @param string $id
     *
     * @return MsisdnBlacklist
     * @throws NotFoundHttpException
     */
    protected function findModel(string $id)
    {
        if (($model = MsisdnBlacklist::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @return \yii\web\Response
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
