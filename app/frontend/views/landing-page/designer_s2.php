<?php
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var string       $template
 */

\yii\web\JqueryAsset::register($this);

$this->title = 'Landing Page Designer';
$this->params['subtitle'] = 'Step 2 of 2';
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = 'Step 2';

$js = <<<JS
    $("#template_download").click(function () {
        document.getElementById('tmpl_frame').contentWindow.save($('#template_title').val());
    });
JS;
$this->registerJs($js);
?>
<div class="col-lg-12">
    <div class="ibox">
        <div class="ibox-title">
            Customize Template
        </div>

        <div class="ibox-content">
            <a href="<?= Url::to('/landing-page/designer') ?>" class="btn btn-default btn-sm">
                Back To Template Selection
            </a><br/>

            <label>
                Title: <input type="text" maxlength="64" id="template_title"/>
            </label>

            <button id="template_download" class="btn btn-info btn-sm">
                Save Template
            </button>

            <iframe style="width: 100%; height: 800px;" src="<?= $template ?>" id="tmpl_frame" frameborder="0"></iframe>
        </div>
    </div>
</div>
