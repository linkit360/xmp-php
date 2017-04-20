<?php
/**
 * @var $this  yii\web\View
 * @var $form  yii\bootstrap\ActiveForm
 * @var $model \common\models\LoginForm
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

\common\assets\InspiniaAsset::register($this);

$this->beginPage();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/ico" href="/img/favicon.png"/>
    <?= Html::csrfMetaTags() ?>
    <title>Login - LinkIT360</title>
    <?php $this->head() ?>
</head>

<body class="gray-bg">
<?php $this->beginBody() ?>

<div class="splash">
    <div class="splash-title">
        <img src="/img/LinkIT360_logo.png"/>
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

<div class="middle-box text-center loginscreen animated fadeInDown">
    <h1 class="logo-name">XMP</h1>
    <?php
    $form = ActiveForm::begin();
    echo $form->field($model, 'username')->textInput(['autofocus' => true]);
    echo $form->field($model, 'password')->passwordInput();
    echo Html::submitButton(
        'Login',
        [
            'class' => 'btn btn-success btn-block',
            'name' => 'login-button',
        ]
    );
    ActiveForm::end();
    ?>
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
