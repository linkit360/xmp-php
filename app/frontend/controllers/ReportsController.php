<?php

namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

use frontend\models\ReportsForm;

/**
 * ReportsController implements the CRUD actions for Reports model.
 */
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
        $model = new ReportsForm();
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
        $model = new ReportsForm();
        $model->load(Yii::$app->request->get());
        $model->dataConvChart();

        return $this->render(
            'conversion',
            [
                'model' => $model,
            ]
        );
    }
}
