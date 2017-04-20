<?php

namespace frontend\controllers;

use const AWS_S3;
use const false;
use function file_get_contents;

use Aws\Sdk;
use Aws\S3\S3Client;
use ZipArchive;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use common\models\Lps;

class LandingPageController extends Controller
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
                        'allow' => true,
                        'roles' => ['lpCreate'],
                    ],
                    [
                        'allow' => false,
                    ],
                ],
            ],
        ];
    }

    public function actionDesigner()
    {
        $get = Yii::$app->request->get();
        $templ_dir = __DIR__ . '/../web/lp/templates';
        // Step 2
        if (count($get)) {
            if (
                !array_key_exists('t', $get) ||
                !is_dir($templ_dir . '/' . (integer)$get['t']) ||
                !is_file($templ_dir . '/' . (integer)$get['t'] . '/index.html') ||
                !is_file($templ_dir . '/' . (integer)$get['t'] . '/preview.png')
            ) {
                throw new NotFoundHttpException();
            }

            return $this->render(
                'designer_s2',
                [
                    'template' => '/lp/templates/' . (integer)$get['t'] . '/index.html',
                ]
            );
        }

        // Step 1
        $templates = [];
        foreach (scandir($templ_dir) as $template) {
            if ($template !== '.' && $template !== '..') {
                $templates[] = $template;
            }
        }

        return $this->render(
            'designer',
            [
                'templates' => $templates,
            ]
        );
    }

    public function actionTemplate()
    {
        $this->layout = 'empty';
        $url = $_SERVER['REQUEST_URI'];
        $url = str_replace('../', '', $url);
        $url = str_replace('?' . $_SERVER['QUERY_STRING'], '', $url);
        $url = dirname(__DIR__) . '/web' . $url;

        if (is_file($url)) {
            $file_name = basename($url);
            $file = file_get_contents($url);
            if ($file_name == 'index.html') {
                $ex = explode('/', $url);
                return $this->render(
                    'template',
                    [
                        'template' => $file,
                        'template_id' => (integer)$ex[7],
                    ]
                );
            } else {
                $path = pathinfo($url);
                switch ($path['extension']) {
                    case 'css':
                        header('Content-Type: text/css');
                        break;

                    case 'js':
                        header('Content-Type: text/javascript');
                        break;

                    default:
                        header('Content-Type: ' . mime_content_type($url));
                }

                echo $file;
            }
        }

        throw new NotFoundHttpException();
    }

    public function actionSave()
    {
        $post = Yii::$app->request->post();
        if (!count($post) || !array_key_exists('export-textarea', $post)) {
            return '';
        }

        $template = $post['export-textarea'];
        $ex = explode('<!--lp_dellme_block-->', $template);

        $template = str_replace($ex[1], '', $template);
        $template = str_replace('<!--lp_dellme_block-->', '', $template);
        $template = str_replace('&lt;', '<', $template);
        $template = str_replace('&gt;', '>', $template);
        $template = str_replace('/lp/templates/' . (integer)$post['templ_id'] . '/', '', $template);

        $dir = __DIR__ . '/../web/lp/templates/' . (integer)$post['templ_id'];
        $files = $this->getFiles($dir);

        $file = tempnam('/tmp', 'zip');
        $zip = new ZipArchive();
        $zip->open($file, ZipArchive::OVERWRITE);

        foreach ($files as $filee) {
            $new_name = str_replace($dir . '/', '', $filee);
            if ($new_name === 'preview.png') {
                continue;
            }

            $zip->addFile(
                $filee,
                $new_name
            );
        }

        $zip->addFromString('index.html', $template);
        $zip->close();

        $model = new Lps();
        $model->title = $post['export-title'];
        if (!$model->save()) {
//            dump($model->getErrors());
            return '';
        }

        $this->s3->putObject(
            [
                'Bucket' => 'xmp-lp',
                'Key' => $model->id,
                'SourceFile' => $file,
            ]
        );

        unlink($file);
        return '<script type="text/javascript">window.top.location.href = "/landing-page/' . $model->id . '";</script>';
    }

    public function actionDownload($id)
    {
        $model = $this->findModel($id);
        if ($model->id_user !== Yii::$app->user->id) {
            return new NotFoundHttpException();
        }

        $result = $this->s3->getObject(
            [
                'Bucket' => 'xmp-lp',
                'Key' => $model->id,
            ]
        );

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
//        header('Content-Length: ' . filesize($file));
        header("Content-Disposition: attachment;filename=template.zip");
        header("Content-Transfer-Encoding: binary ");
        echo $result['Body'];

        return '';
    }

    /**
     * Lists all Lp models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Lps::find()->where([
                'id_user' => Yii::$app->user->id,
                'status' => 1,
            ]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Lp model.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing Lp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Lp model.
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
     * Finds the Lp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Lps the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lps::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function getFiles($dir)
    {
        $files = [];
        foreach (scandir($dir) as $file_name) {
            if ($file_name === '.' || $file_name === '..') {
                continue;
            }

            $file = $dir . '/' . $file_name;
            if (is_dir($file)) {
                $files = array_merge($files, $this->getFiles($file));
            } else {
                $files[] = $file;
            }
        }

        return $files;
    }
}
