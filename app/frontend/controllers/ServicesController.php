<?php

namespace frontend\controllers;

use common\helpers\FlagsHelper;
use function dump;
use const null;
use const JSON_PRETTY_PRINT;
use function json_encode;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\Services;
use common\models\Countries;
use common\models\Providers;
use frontend\models\Services\ServicesForm;
use frontend\models\Services\CheeseForm;

/**
 * ServicesController implements the CRUD actions for Services model.
 */
class ServicesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Services models.
     * @return mixed
     */
    public function actionIndex()
    {
        $providers = Providers::find()
            ->select(
                [
                    'name',
                    'id',
                    'id_country',
                ]
            )
            ->orderBy(
                [
                    'name' => SORT_ASC,
                ]
            )
            ->indexBy('id')
            ->all();

        $dataProvider = new ActiveDataProvider([
            'query' => Services::find()
                ->where(
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
                'providers' => $providers,
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
     * Creates a new Services model.
     * If creation is successful, the browser will be redirected to the 'view' page.
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

            if (
                $model->load(Yii::$app->request->post()) &&
                $modelProvider->load(Yii::$app->request->post())
            ) {
                # Provider
                if ($modelProvider->validate()) {
                    $model->service_opts = json_encode(
                        $modelProvider->attributes,
                        JSON_PRETTY_PRINT
                    );

                    # Service
                    if ($model->validate()) {
                        $model->save();
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

            default:
                return null;
                break;
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

        if (
            $model->load(Yii::$app->request->post()) &&
            $modelProvider->load(Yii::$app->request->post())
        ) {
            # Provider
            if ($modelProvider->validate()) {
                $model->service_opts = json_encode(
                    $modelProvider->attributes,
                    JSON_PRETTY_PRINT
                );

                # Service
                if ($model->validate()) {
                    $model->save();
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
        $model->save();

        return $this->redirect(['index']);
    }

    public function createStep1()
    {
        $providers = Providers::find()
            ->select('id_country')
            ->where([
                'id' => [
                    // TH - Cheese Mobile
                    1,
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
     * @param $id
     *
     * @return array|Services|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = Services::find()
            ->where(
                [
                    'id' => $id,
                    'id_user' => Yii::$app->user->id,
                ]
            )
            ->one();

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
