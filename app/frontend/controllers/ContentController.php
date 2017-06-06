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
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

use common\models\Users;
use common\models\Content\Content;
use common\models\Content\Categories;
use common\models\Content\Publishers;

use frontend\models\ContentForm;

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
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'roles' => ['contentManage'],
                        'allow' => true,
                    ],
                    [
                        'allow' => false,
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Content models.
     *
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

        $data['id_category'] = Content::find()
            ->select('id_category')
            ->groupBy('id_category')
            ->indexBy('id_category')
            ->column();

        $data['id_category'] = Categories::find()
            ->select([
                'title',
                'id',
            ])
            ->where([
                'id' => array_keys($data['id_category']),
            ])
            ->indexBy('id')
            ->column();


        $data['id_publisher'] = Content::find()
            ->select('id_publisher')
            ->where("id_publisher IS NOT NULL")
            ->groupBy('id_publisher')
            ->indexBy('id_publisher')
            ->column();

        $data['id_publisher'] = Publishers::find()
            ->select([
                'title',
                'id',
            ])
            ->where([
                'id' => array_keys($data['id_publisher']),
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

        $searchModel = new \frontend\models\Search\Content();
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
        $model = Content::findOne($id);
        if ($model !== null) {
            if ($model->id_user === Yii::$app->user->id || Yii::$app->user->can('Admin')) {
                return $model;
            }
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCreate()
    {
        $model = new ContentForm();
        $model->status = 1;

        if ($model->load(Yii::$app->request->post())) {
            $model->id_user = Yii::$app->user->id;
            if ($model->id_publisher === '') {
                unset($model->id_publisher);
            }

            if ($model->validate()) {
                if (array_key_exists('ContentForm', $_FILES) && count($_FILES['ContentForm']['tmp_name'])) {
                    $model = $this->fileUpload(
                        $model,
                        $_FILES['ContentForm']
                    );
                }

                if ($model->save()) {
                    return $this->redirect(['index']);
                }
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
     * @param Content $model
     * @param array   $file
     * @return Content
     */
    private function fileUpload($model, $file)
    {
        $model->filename = self::normalizeString($model->filename) . "." . pathinfo($file['name']['file'])['extension'];
        $fileZip = tempnam('/tmp', 'zip');

        $zip = new ZipArchive();
        $zip->open($fileZip, ZipArchive::OVERWRITE);
        $zip->addFile($file['tmp_name']['file'], $model->filename);
        $zip->close();

        $this->s3->putObject([
            'Bucket' => 'xmp-content',
            'Key' => $model->id,
            'SourceFile' => $fileZip,
        ]);

        return $model;
    }

    public static function normalizeString($str = '')
    {
        $str = strip_tags($str);
        $str = preg_replace('/[\r\n\t ]+/', ' ', $str);
        $str = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $str);
        $str = strtolower($str);
        $str = html_entity_decode($str, ENT_QUOTES, "utf-8");
        $str = htmlentities($str, ENT_QUOTES, "utf-8");
        $str = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
        $str = str_replace(' ', '-', $str);
        $str = rawurlencode($str);
        $str = str_replace('%', '-', $str);

        return $str;
    }

    public function actionUpdate($id)
    {
        $model = ContentForm::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->id_publisher === '') {
                unset($model->id_publisher);
            }

            if ($model->save()) {
                return $this->redirect(['index']);
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

    public function actionDownload($id)
    {
        $model = $this->findModel($id);
        if ($model->id_user !== Yii::$app->user->id) {
            return new NotFoundHttpException();
        }

        $result = $this->s3->getObject(
            [
                'Bucket' => 'xmp-content',
                'Key' => $model->id,
            ]
        );

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=content.zip");
        header("Content-Transfer-Encoding: binary ");
        echo $result['Body'];

        return '';
    }
}
