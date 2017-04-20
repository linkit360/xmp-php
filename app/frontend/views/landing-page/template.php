<?php
/**
 * @var yii\web\View $this
 * @var string       $template
 * @var integer      $template_id
 */
use yii\helpers\Html;

$csrf_input = Html::hiddenInput(
    Yii::$app->getRequest()->csrfParam,
    Yii::$app->getRequest()->getCsrfToken()
);

$id_input = Html::hiddenInput(
    'templ_id',
    $template_id
);

$html_insert = <<<HTML
<!--lp_dellme_block-->
<link rel="stylesheet" href="/lp/generator/style.css"/>

<div id="newsletter-preloaded-download">
    <form id="export-form" action="/landing-page/save" method="POST" name="export-form">
        {$csrf_input}
        {$id_input}
        <label>
            <textarea id="export-textarea" cols="100" rows="30" name="export-textarea"></textarea>
        </label>
        <input type="hidden" name="export-title" id="export-title">
    </form>
    <div id="newsletter-preloaded-export"></div>
</div>

<div class="sim-edit" id="sim-edit-image">
    <div class="sim-edit-box" style="height: 350px;">
        <div class="sim-edit-box-title">Edit Image</div>
        <div class="sim-edit-box-content">
            <div class="sim-edit-box-content-text">
                <label for="lp_image_text">URL:<span>(full address including http://)</span></label>
            </div>
            <div class="sim-edit-box-content-field">
                <input type="text" class="sim-edit-box-content-field-input image" id="lp_image_text"/>
            </div>
        </div>
        <div class="sim-edit-box-buttons">
            <div class="sim-edit-box-buttons-save">Save</div>
            <div class="sim-edit-box-buttons-cancel">Cancel</div>
        </div>
    </div>
</div>

<div class="sim-edit" id="sim-edit-text">
    <div class="sim-edit-box" style="height: 450px;">
        <div class="sim-edit-box-title">Edit Text</div>

        <div class="sim-edit-box-content">
            <div class="sim-edit-box-content-text">
                <label for="lp_edit_text">Text</label>
            </div>
            
            <div class="sim-edit-box-content-field">
                <textarea class="sim-edit-box-content-field-textarea text" id="lp_edit_text"></textarea>
            </div>
        </div>
        
        <div class="sim-edit-box-buttons">
            <div class="sim-edit-box-buttons-save">Save</div>
            <div class="sim-edit-box-buttons-cancel">Cancel</div>
        </div>
    </div>
</div>

<script src="/lp/generator/js/jquery-3.1.1.min.js"></script>
<script src="/lp/generator/js/gen.js"></script>
<!--lp_dellme_block-->
HTML;

echo str_replace('</body>', $html_insert . PHP_EOL . '</body>', $template);
