<?php

namespace common\helpers;

use yii\web\View;

class ModalHelper
{
    /**
     * @param View $view
     */
    public function modalDelete($view)
    {
        ?>
        <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title">
                            DELETE
                        </h4>
                    </div>

                    <h3 class="text-center">
                        Are you sure?
                    </h3>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <a href="" class="btn btn-danger" id="rowid">Delete</a>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $js = <<<JS
            $('#modalDelete').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var recipient = button.data('rowid');
                var modal = $(this);

                modal.find('#rowid').attr('href', 'delete/' + recipient)
            });
JS;
        $view->registerJs($js);
    }
}
