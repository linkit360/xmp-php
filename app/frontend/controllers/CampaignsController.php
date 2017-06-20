<?php

namespace frontend\controllers;

use function md5;
use function mt_rand;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use common\models\Users;
use common\models\Services;
use common\models\Campaigns;
use common\models\Instances;

use frontend\models\Campaigns\CampaignsForm;

class CampaignsController extends Controller
{
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
     * @return string
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

        $srv = Services::find()
            ->select([
                "id_provider",
                "id",
            ])
            ->indexBy("id")
            ->asArray()
            ->column();

        $insts = Instances::find()
            ->select([
                "hostname",
                "id_provider",
            ])
            ->where([
                "id_provider" => $srv,
            ])
            ->indexBy("id_provider")
            ->asArray()
            ->column();


        foreach ($srv as $id => $prov) {
            $srv[$id] = $insts[$prov];
        }
        unset ($insts);


        $searchModel = new \frontend\models\Search\Campaigns();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render(
            'index',
            [
                'model' => $searchModel,
                'dataProvider' => $dataProvider,
                'users' => $users,
                'srv' => $srv,
            ]
        );
    }

    /**
     * @param string $id
     *
     * @return string|NotFoundHttpException
     */
    public function actionView(string $id)
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
     * @param string $id
     *
     * @return Campaigns
     * @throws NotFoundHttpException
     */
    protected function findModel(string $id)
    {
        $model = Campaigns::findOne($id);
        if ($model !== null) {
            if ($model->id_user === Yii::$app->user->id || Yii::$app->user->can('Admin')) {
                return $model;
            }
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new CampaignsForm();
        $model->status = 1;
        $model->link = md5(mt_rand(1, 999999));

        if ($model->load(Yii::$app->request->post())) {
            $model->id_user = Yii::$app->user->id;
            if ($model->save()) {
                $this->sendGoData($model, true);

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
     * @param Campaigns $model
     * @param bool      $create
     */
    public function sendGoData($model, $create = false)
    {
        // Get Service
        $service = Services::find()
            ->select([
                'id_provider',
            ])
            ->where([
                'id' => $model->id_service,
            ])
            ->asArray()
            ->one();

        // Get instance
        $instance = Instances::find()
            ->select("id")
            ->where([
                'status' => 1,
                'id_provider' => $service["id_provider"],
            ])
            ->asArray()
            ->one();

        // If found - send notify
        if ($instance) {
            $payload = [];

            $payload['type'] = 'campaign.update';
            if ($create) {
                $payload['type'] = 'campaign.new';
            }

            $payload['for'] = $instance["id"];
            $data = $model->attributes;
            $data['lp'] = $data['id_lp'];
            $data["auto_click_enabled"] = (bool)$data["autoclick_enabled"];
            $data["auto_click_ratio"] = (int)$data["autoclick_ratio"];
            $data['id_old'] = "" . $data['id_old'];

            unset(
                $data["id_user"],
                $data["created_at"],
                $data["updated_at"],
                $data["autoclick_enabled"],
                $data["autoclick_ratio"],
                $data["id_lp"]
            );

            $payload['data'] = json_encode($data, JSON_PRETTY_PRINT);
            $json = json_encode($payload, JSON_PRETTY_PRINT);
            Yii::$app->getDb()
                ->createCommand("NOTIFY xmp_update, '" . $json . "';")
                ->execute();
        }
    }

    /**
     * @param string $id
     *
     * @return string|\yii\web\Response
     */
    public function actionUpdate(string $id)
    {
        $model = CampaignsForm::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->id_user) {
                $model->id_user = Yii::$app->user->id;
            }

            if ($model->save()) {
                $this->sendGoData($model);

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
     * @param string $id
     *
     * @return \yii\web\Response
     */
    public function actionDelete(string $id)
    {
        $model = $this->findModel($id);
        $model->status = 0;
        $model->save();

        $this->sendGoData($model);

        return $this->redirect(['index']);
    }
}
