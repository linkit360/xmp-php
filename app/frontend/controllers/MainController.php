<?php

namespace frontend\controllers;

use common\models\Instances;
use const true;
use const false;
use function date;
use function count;
use function array_keys;
use function array_key_exists;

use Yii;
use yii\db\Query;
use yii\web\Response;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;

use common\models\Logs;
use common\models\LoginForm;
use common\models\Countries;
use common\models\Operators;
use common\models\Providers;
use frontend\models\LogsForm;
use frontend\models\TransactionsForm;
use frontend\models\Users\ResetPasswordForm;

class MainController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'error',
                            'login',
                            'rpc',
                            'soap',
                            'call-soap',
                        ],
                        'allow' => true,
                    ],
                    [
                        'actions' => [
                            'index',
                            'monitoring',
                            'logout',
                            'country',
                            'reset-password',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => [
                            'transactions',
                            'logs',
                        ],
                        'allow' => true,
                        'roles' => [
                            'logsView',
                        ],
                    ],
                    [
                        'allow' => false,
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function beforeAction($action)
    {
        if ($action->id === 'error' && Yii::$app->user->isGuest) {
            $this->layout = 'empty';
        }

        return parent::beforeAction($action);
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Displays monitoring page.
     *
     * @return mixed
     */
    public function actionMonitoring()
    {
        return $this->render('monitoring');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'empty';
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $log = new Logs();
            $log->controller = $this->id;
            $log->action = $this->action->id;
            $ips = $this->userIps();
            if (count($ips)) {
                $log->event = [
                    'ips' => $ips,
                ];
            }
            $log->save();

            return $this->goBack();
        }

        return $this->render(
            'login',
            [
                'model' => $model,
            ]
        );
    }

    private function userIps()
    {
        $ips = [];
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $ips['HTTP_X_FORWARDED_FOR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
            $ips['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
        }

        return $ips;
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        if (!Yii::$app->user->isGuest) {
            $log = new Logs();
            $log->controller = $this->id;
            $log->action = $this->action->id;
            $ips = $this->userIps();
            if (count($ips)) {
                $log->event = [
                    'ips' => $ips,
                ];
            }
            $log->save();

            Yii::$app->user->logout(true);
        }

        return $this->goHome();
    }

    /**
     * Resets password.
     *
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword()
    {
        try {
            $model = new ResetPasswordForm();
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render(
            'resetPassword',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Lists all Transactions models.
     *
     * @return mixed
     */
    public function actionTransactions()
    {
        $model = new TransactionsForm();
        $model->load(Yii::$app->request->get());

        return $this->render(
            'transactions',
            [
                'model' => $model,
                'dataProvider' => $model->dataProvider(),
            ]
        );
    }

    public function actionLogs()
    {
        $searchModel = new LogsForm();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render(
            'logs',
            [
                'model' => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );
    }

    public function actionCountry()
    {
        $country = Countries::find()
            ->select([
                'id',
                'name',
            ])
            ->where([
                'iso' => $_GET['iso'],
            ])
            ->asArray()
            ->one();

        $providers = Providers::find()
            ->select([
                'name',
                'id',
            ])
            ->where([
                'id_country' => $country['id'],
            ])
            ->indexBy('name')
            ->asArray()
            ->all();

        $prov_keys = ArrayHelper::map($providers, 'id', 'name');
        $instances = Instances::find()
            ->select([
                "id",
                "id_provider",
            ])
            ->where([
                'id_provider' => array_keys($prov_keys),
            ])
            ->indexBy('id_provider')
            ->asArray()
            ->column();

        $operators = Operators::find()
            ->where([
                'id_provider' => array_keys($prov_keys),
            ])
            ->orderBy('name')
            ->indexBy('code')
            ->asArray()
            ->all();

        $query = (new Query())
            ->from('xmp_reports')
            ->select([
                'SUM(lp_hits) as lp_hits',
                'SUM(mo_total) as mo',
                'SUM(mo_charge_success) as mo_success',

                "date_trunc('day', report_at) as report_at_day",
                'operator_code',
            ])
            ->where([
                'AND',
                "report_at >= '" . date('Y-m-d') . "'",
                [
                    'id_instance' => $instances,
                ],
            ])
            ->groupBy([
                'operator_code',
                'report_at_day',
            ])
            ->all();

        $data = [];
        $data['total'] = [
            'lp_hits' => 0,
            'mo' => 0,
            'mo_success' => 0,
            'name' => $country['name'],
        ];

        if (count($query)) {
            foreach ($query as $operator) {
                if (array_key_exists($operator['operator_code'], $operators)) {
                    $data[$operator['operator_code']] = [
                        'cnt' => $operator,
                        'op' => $operators[$operator['operator_code']],
                    ];
                }

                $data['total']['lp_hits'] += $operator['lp_hits'];
                $data['total']['mo'] += $operator['mo'];
                $data['total']['mo_success'] += $operator['mo_success'];
            }
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return $data;
    }
}
