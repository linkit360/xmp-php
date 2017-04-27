<?php
/**
 * @var \yii\web\View $this
 * @var string        $content
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

\common\assets\InspiniaAsset::register($this);

/** @var \common\models\Users $user */
$user = Yii::$app->user->identity;
if ($user->new_pass == 1 && Yii::$app->request->pathInfo !== 'main/reset-password') {
    return Yii::$app->getResponse()->redirect('/main/reset-password');
}
$this->beginPage();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/ico" href="<?= Url::toRoute('/img/favicon.png') ?>"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= strlen($this->title) ? Html::encode($this->title) . ' - ' : '' ?>LinkIT360</title>
    <?php $this->head() ?>
</head>

<body class="fixed-sidebar md-skin">
<?php $this->beginBody() ?>

<div class="splash">
    <div class="splash-title">
        <img src="<?= Url::toRoute('/img/LinkIT360_logo.png') ?>"/>
        <h1>XMP</h1>

        <div class="sk-spinner sk-spinner-wave">
            <div class="sk-rect1"></div>
            <div class="sk-rect2"></div>
            <div class="sk-rect3"></div>
            <div class="sk-rect4"></div>
            <div class="sk-rect5"></div>
        </div>
    </div>
</div>

<div id="wrapper">
    <?= $this->render('sidebar') ?>
    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" href="#">
                        <i class="fa fa-bars"></i>
                    </a>
                </div>

                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <a href="<?= Url::toRoute('/main/logout') ?>">
                            <i class="fa fa-sign-out"></i> <?= $user->username ?>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <?php
                                    echo Html::tag('h2', Html::encode($this->title));
                                    echo array_key_exists('subtitle', $this->params)
                                        ? $this->params['subtitle']
                                        : '';
                                    ?>
                                </div>

                                <div class="col-md-6 text-right">
                                    <?php
                                    echo Html::tag('h2', '&nbsp;');
                                    echo Breadcrumbs::widget(
                                        [
                                            'homeLink' => ['label' => 'XMP', 'url' => '/'],
                                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                                            'class' => 'hbreadcrumb breadcrumb',
                                        ]
                                    );
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?= $content ?>
            </div>
        </div>
    </div>
</div>
<?php
$this->endBody();
?>
</body>
</html>
<?php
# do not remove php close tag after endPage, breaks formatting
$this->endPage();
?>
