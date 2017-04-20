<?php

namespace frontend\controllers;

use const AWS_S3;
use function count;
use function json_decode;
use function array_key_exists;

use Aws\Sdk;
use Aws\S3\S3Client;
use ZipArchive;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use frontend\models\ContentForm;
use common\models\Content\Content;
use common\models\Content\Categories;
use common\models\Content\Publishers;

/**
 * ContentController implements the CRUD actions for Content model.
 */
class ContentController extends Controller
{
    /** @var S3Client */
    public $s3;

    public function init()
    {
        parent::init();
        $sdk = new Sdk(
            [
                'region' => 'ap-southeast-1',
                'version' => '2006-03-01',
                'credentials' => AWS_S3,
            ]
        );

        $this->s3 = $sdk->createS3();
    }


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
     * Lists all Content models.
     * @return mixed
     */
    public function actionIndex()
    {
        $data = [];
        $data['pubs'] = Publishers::find()
            ->where(
                [
                    'status' => 1,
                ]
            )
            ->indexBy('id')
            ->all();

        $data['cats'] = Categories::find()
            ->where(
                [
                    'status' => 1,
                ]
            )
            ->indexBy('id')
            ->all();

        $dataProvider = new ActiveDataProvider([
            'query' => Content::find()->where(
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
                'data' => $data,
            ]
        );
    }

    /**
     * Displays a single Content model.
     *
     * @param string $id
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
     * Creates a new Content model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ContentForm();
        $model->status = 1;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->id_publisher === '') {
                unset($model->id_publisher);
            }

            if ($model->save()) {
                if (array_key_exists('ContentForm', $_FILES) && count($_FILES['ContentForm']['tmp_name'])) {
                    $this->fileUpload(
                        $model,
                        $_FILES['ContentForm']['tmp_name']['file'],
                        $_FILES['ContentForm']['name']['file']
                    );
                }
                return $this->redirect(['view', 'id' => $model->id]);
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
     * Updates an existing Content model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = ContentForm::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->id_publisher === '') {
                unset($model->id_publisher);
            }

            if ($model->save()) {
                if (array_key_exists('ContentForm', $_FILES) && count($_FILES['ContentForm']['tmp_name'])) {
                    $this->fileUpload(
                        $model,
                        $_FILES['ContentForm']['tmp_name']['file'],
                        $_FILES['ContentForm']['name']['file']
                    );
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $model->blacklist_tmp = json_decode($model->blacklist);
        return $this->render(
            'update',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Deletes an existing Content model.
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

    /**
     * Finds the Content model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Content the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Content::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param Content $model
     * @param array   $file
     * @param string  $name
     */
    private function fileUpload($model, $file, $name)
    {
        $fileZip = tempnam('/tmp', 'zip');
        $zip = new ZipArchive();
        $zip->open($fileZip, ZipArchive::OVERWRITE);
        $zip->addFile($file, $name);
        $zip->close();

        $this->s3->putObject([
            'Bucket' => 'xmp-content',
            'Key' => $model->id,
            'SourceFile' => $fileZip,
        ]);
    }
}
