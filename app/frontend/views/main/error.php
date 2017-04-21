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
<div class="col-lg-12">
    <div class="ibox">
        <div class="ibox-content">
            <br/>
            <div class="alert alert-danger">
                <?= nl2br(Html::encode($message)) ?>
            </div>

            <p>
                The above error occurred while the Web server was processing your request.
            </p>

            <p>
                Please contact <a href="mailto:kirill.goryunov@linkit360.com">kirill.goryunov@linkit360.com</a>
                if you think this is a server error. Thank you.
            </p>
        </div>
    </div>
</div>
