<?php

namespace frontend\controllers;

use const null;
use const JSON_PRETTY_PRINT;
use function json_encode;

use Yii;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use common\models\Users;
use common\models\Services;
use common\models\Countries;
use common\models\Providers;
use common\models\Instances;
use common\models\Content\Content;

use frontend\models\Services\CheeseForm;
use frontend\models\Services\QrtechForm;
use frontend\models\Services\MobilinkForm;
use frontend\models\Services\ServicesForm;

/**
 * ServicesController implements the CRUD actions for Services model.
 */
class ServicesController extends Controller
{
    /**
     * Lists all Services models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $providers = Providers::find()
            ->select([
                'name',
                'id',
                'id_country',
            ])
            ->orderBy([
                'name' => SORT_ASC,
            ])
            ->indexBy('id')
            ->all();

        $countries = Countries::find()
            ->select([
                'name',
                'id',
            ])
            ->orderBy([
                'name' => SORT_ASC,
            ])
            ->indexBy('id')
            ->all();

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

        $searchModel = new \frontend\models\Search\Services();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render(
            'index',
            [
                'model' => $searchModel,
                'dataProvider' => $dataProvider,
                'providers' => $providers,
                'countries' => $countries,
                'users' => $users,
            ]
        );
    }

    /**
     * Displays a single Services model.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $modelProvider = $this->getProviderModel($model->id_provider);
        $modelProvider->load(json_decode($model->service_opts, true), '');

        return $this->render(
            'view',
            [
                'model' => $model,
                'modelProvider' => $modelProvider,
            ]
        );
    }

    /**
     * @param $id
     *
     * @return array|Services|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = Services::findOne($id);
        if ($model !== null) {
            if ($model->id_user === Yii::$app->user->id || Yii::$app->user->can('Admin')) {
                return $model;
            }
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param int $id_provider
     *
     * @return Model|null
     */
    public function getProviderModel($id_provider)
    {
        switch ($id_provider) {
            // TH - Cheese Mobile
            case 1:
                return new CheeseForm();
                break;

            // TH - QR Tech
            case 2:
                return new QrtechForm();
                break;

            // PK - Mobilink
            case 5:
                return new MobilinkForm();
                break;

            default:
                return null;
                break;
        }
    }

    /**
     * Creates a new Services model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $opts = [];
        $get = Yii::$app->request->get();
        $model = new ServicesForm();
        $model->loadDefaultValues();
        $stepNow = 1;
        if (array_key_exists('step', $get)) {
            $stepNow = (integer)$get['step'];
        }

        # Step 1, Country
        if ($stepNow === 1) {
            $opts['countries'] = $this->createStep1();
        }

        # Step 2, Provider
        if ($stepNow === 2) {
            if (!array_key_exists('id_country', $get)) {
                return $this->redirect('/services/create?step=1');
            }
        }

        $modelProvider = null;
        # Step 3, Service
        if ($stepNow === 3) {
            # Country
            if (!array_key_exists('id_country', $get)) {
                return $this->redirect('/services/create?step=1');
            }

            # Provider
            if (!array_key_exists('id_provider', $get)) {
                return $this->redirect('/services/create?step=2&id_country=' . $get['id_country']);
            }

            $opts['country'] = Countries::findOne((integer)$get['id_country']);

            $model->id_provider = $get['id_provider'];
            $modelProvider = $this->getProviderModel((integer)$get['id_provider']);
            if ($modelProvider === null) {
                return $this->redirect('/services/create?step=1');
            }

            if ($model->load(Yii::$app->request->post())) {
                # Provider
                $modelProvider->load(Yii::$app->request->post());
                if ($modelProvider->validate()) {
                    $model->id_user = Yii::$app->user->id;
                    $model->service_opts = json_encode(
                        $modelProvider->attributes,
                        JSON_PRETTY_PRINT
                    );

                    # Service
                    if ($model->validate()) {
                        $model->save();

                        $this->sendGoData($model, true);

                        return $this->redirect(['index']);
                    }
                }
            }
        }

        return $this->render(
            'create',
            [
                'stepNow' => $stepNow,
                'models' => [
                    'model_service' => $model,
                    'model_provider' => $modelProvider,
                ],
                'opts' => $opts,
            ]
        );
    }

    public function createStep1()
    {
        $providers = Providers::find()
            ->select('id_country')
            ->where([
                'id' => [
                    1, // cheese
                    2, // qrtech
                    5, // mobilink
                ],
            ])
            ->groupBy('id_country')
            ->asArray()
            ->column();

        $countries = Countries::find()
            ->select([
                'id',
                'name',
                'flag',
            ])
            ->where([
                'id' => $providers,
            ])
            ->orderBy('name')
            ->indexBy('id')
            ->asArray()
            ->all();

        return $countries;
    }

    /**
     * @param Services $model
     * @param bool     $create
     */
    public function sendGoData($model, $create = false)
    {
        // Get instance
        $instance = Instances::find()
            ->select('id')
            ->where([
                'status' => 1,
                'id_provider' => $model->id_provider,
            ])
            ->asArray()
            ->one();

        // If found - send notify
        if ($instance) {
            $payload = [];

            $payload['type'] = 'service.update';
            if ($create) {
                $payload['type'] = 'service.new';
            }

            $payload['for'] = $instance["id"];
            $payload['data'] = $model->attributes;
            $payload['data']['id'] = $model->id;
            $payload['data']['price'] = (int)$payload['data']['price'];

            // Service ID (code)
            $payload['data']['code'] = $model->id_service;
            unset($payload['data']['id_service']);

            // Content
            $payload['data']['contents'] = [];
            $payload['data']['id_content'] = json_decode($payload['data']['id_content']);
            if (count($payload['data']['id_content'])) {
                $conts = Content::find()
                    ->where([
                        'id' => $payload['data']['id_content'],
                    ])
                    ->all();

                if (count($conts)) {
                    /** @var Content $cont */
                    foreach ($conts as $cont) {
                        $payload['data']['contents'][] = [
                            'id' => $cont->id,
                            'title' => $cont->title,
                            'name' => $cont->filename,
                        ];
                    }
                }
            }

            // Service Opts
            $opts = json_decode($payload['data']['service_opts'], true);
            if (count($opts)) {
                foreach ($opts as $key => $val) {
                    $set = $val;

                    if (in_array($key, ["retry_days", "inactive_days", "grace_days", "minimal_touch_times"])) {
                        $set = (int)$set;
                    }

                    $payload['data'][$key] = $set;
                }
            }
            unset($payload['data']['service_opts']);

            $payload['data'] = json_encode($payload['data']);
            $json = json_encode($payload);

            Yii::$app->getDb()
                ->createCommand("NOTIFY xmp_update, '" . $json . "';")
                ->execute();
        }
    }

    /**
     * Updates an existing Services model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $opts = [];
        $model = ServicesForm::findOne($id);

        $modelProvider = $this->getProviderModel($model->id_provider);
        if ($modelProvider === null) {
            return $this->redirect('/services/create?step=1');
        }

        $modelProvider->load(json_decode($model->service_opts, true), '');
        $provider = Providers::findOne($model->id_provider);
        $opts['country'] = Countries::find()
            ->where(
                [
                    'id' => $provider->id_country,
                ]
            )
            ->one();

        if ($model->load(Yii::$app->request->post())) {
            # Provider
            $modelProvider->load(Yii::$app->request->post());
            if ($modelProvider->validate()) {
                if ($model->id_user !== Yii::$app->user->id) {
                    return $this->redirect(['index']);
                }

                $model->service_opts = json_encode(
                    $modelProvider->attributes,
                    JSON_PRETTY_PRINT
                );

                # Service
                if ($model->validate()) {
                    $model->save();

                    $this->sendGoData($model);

                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render(
            'update',
            [
                'models' => [
                    'model_service' => $model,
                    'model_provider' => $modelProvider,
                ],
                'opts' => $opts,
            ]
        );
    }

    /**
     * Deletes an existing Services model.
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
        if ($model->save()) {
            $this->sendGoData($model);
        }

        return $this->redirect(['index']);
    }
}
