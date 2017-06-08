<?php

namespace frontend\controllers;

use common\models\Instances;
use common\models\Services;
use function md5;
use function mt_rand;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use common\models\Users;
use common\models\Operators;
use common\models\Campaigns;

use frontend\models\Campaigns\CampaignsForm;

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
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $data = [];
        $data['id_operator'] = Operators::find()
            ->select([
                'name',
                'id',
            ])
            ->where([
                'status' => 1,
            ])
            ->indexBy('id')
            ->column();

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

        $searchModel = new \frontend\models\Search\Campaigns();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render(
            'index',
            [
                'model' => $searchModel,
                'dataProvider' => $dataProvider,
                'data' => $data,
                'users' => $users,
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
        $model = Campaigns::findOne($id);
        if ($model !== null) {
            if ($model->id_user === Yii::$app->user->id || Yii::$app->user->can('Admin')) {
                return $model;
            }
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new Campaigns model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
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
            $data["autoclick_enabled"] = (bool)$data["autoclick_enabled"];
            $data['id_old'] = "" . $data['id_old'];

            unset(
                $data["id_user"],
                $data["created_at"],
                $data["updated_at"],
                $data["id_lp"]
            );

            $payload['data'] = json_encode($data, JSON_PRETTY_PRINT);

//            dump($payload['data']);


            $json = json_encode($payload, JSON_PRETTY_PRINT);

//            dump($json);


            Yii::$app->getDb()
                ->createCommand("NOTIFY xmp_update, '" . $json . "';")
                ->execute();
//            die;
        }
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
        if ($model->load(Yii::$app->request->post())) {
            $model->id_user = Yii::$app->user->id;
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

        $this->sendGoData($model);

        return $this->redirect(['index']);
    }
}
