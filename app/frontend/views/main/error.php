<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var string       $name
 * @var string       $message
 * @var Exception    $exception
 */

$this->title = $name;
?>
<div class="content animate-panel">
    <div class="row">
        <div class="hpanel">
            <div class="panel-body">
                <h1>
                    <?= Html::encode($this->title) ?>
                </h1>

                <div class="alert alert-danger">
                    <?= nl2br(Html::encode($message)) ?>
                </div>

                <p>
                    <br/>
                    The above error occurred while the Web server was processing your request.
                </p>

                <p>
                    Please contact <a href="mailto:kirill.goryunov@linkit360.com">kirill.goryunov@linkit360.com</a>
                    if you think this is a server error. Thank you.
                </p>
            </div>
        </div>
    </div>
</div>
