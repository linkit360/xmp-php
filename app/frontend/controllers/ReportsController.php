<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

use frontend\models\Reports\AdReport;
use frontend\models\Reports\ConvReport;
use frontend\models\Reports\RevenueReport;

class ReportsController extends Controller
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
                        'actions' => [
                            'index',
                        ],
                        'roles' => [
                            'reportsAdvertisingView',
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'conversion',
                        ],
                        'roles' => [
                            'reportsConversionView',
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'revenue',
                        ],
                        'roles' => [
                            'reportsRevenueView',
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
     * AD REPORT
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new AdReport();
        $model->load(Yii::$app->request->get());
        $model->dataAdChart();

        return $this->render(
            'index',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Conversion report
     *
     * @return mixed
     */
    public function actionConversion()
    {
        $model = new ConvReport();
        $model->load(Yii::$app->request->get());
        $model->dataConvChart();

        return $this->render(
            'conversion',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Revenue report
     *
     * @return mixed
     */
    public function actionRevenue()
    {
        $model = new RevenueReport();
        $model->load(Yii::$app->request->get());
        $model->dataChart();

        return $this->render(
            'revenue',
            [
                'model' => $model,
            ]
        );
    }
}
