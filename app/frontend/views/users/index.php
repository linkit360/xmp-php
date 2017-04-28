<?php
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View                      $this
 * @var array                             $data
 * @var yii\data\ActiveDataProvider       $dataProvider
 * @var \frontend\models\Users\SearchForm $searchModel
 */

$this->title = 'Users';
$this->params['subtitle'] = 'Users and roles management';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-12">
    <div class="ibox">
        <div class="ibox-title">
            <h5>
                Users
            </h5>
        </div>

        <div class="ibox-content">
            <p>
                <?php
                if (Yii::$app->user->can('usersCreate')) {
                    echo '&nbsp;' . Html::a('Create User', ['create'], ['class' => 'btn btn-success']);
                }
                ?>
            </p>

            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'username',
                    'email:email',
                    [
                        'attribute' => 'created_at',
                        'filter' => false,
                        'contentOptions' => [
                            'style' => 'width: 1%; white-space: nowrap;',
                        ],
                        'content' => function ($data) {
                            return date('Y-m-d H:i:s', $data['created_at']);
                        },
                    ],
                    [
                        'attribute' => 'updated_at',
                        'filter' => false,
                        'contentOptions' => [
                            'style' => 'width: 1%; white-space: nowrap;',
                        ],
                        'content' => function ($data) {
                            return date('Y-m-d H:i:s', $data['updated_at']);
                        },
                    ],
                    [
                        'contentOptions' => [
                            'style' => 'width: 1%; white-space: nowrap;',
                        ],
                        'content' => function ($row) {
                            $html = Html::a(
                                'View',
                                [
                                    'view',
                                    'id' => $row['id'],
                                ],
                                [
                                    'class' => 'btn btn-xs btn-success',
                                ]
                            );

                            $html .= '&nbsp;';
                            $html .= Html::a(
                                'Update',
                                [
                                    'update',
                                    'id' => $row['id'],
                                ],
                                [
                                    'class' => 'btn btn-xs btn-primary',
                                ]
                            );

                            $html .= '&nbsp;';
                            $html .= Html::a(
                                'Delete',
                                [
                                    'delete',
                                    'id' => $row['id'],
                                ],
                                [
                                    'class' => 'btn btn-xs btn-danger',
                                ]
                            );

                            return $html;
                        },
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>

<?php
if (!Yii::$app->user->can('rbacManage')) {
    return;
}
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-title">
            <h5>
                Roles
            </h5>
        </div>

        <div class="ibox-content">
            <p>
                <?= Html::a('Create Role', '/rbac/create', ['class' => 'btn btn-success']) ?>
            </p>

            <table class="table table-condensed">
                <tr>
                    <th style="width: 1%;">Name</th>
                    <th>Description</th>
                </tr>

                <?php
                /** @var \yii\rbac\Role $role */
                foreach ($data['roles'] as $role) {
                    ?>
                    <tr>
                        <td>
                            <?php
                            echo Html::a(
                                Html::encode($role->name),
                                '/rbac/view?id=' . $role->name
                            );
                            ?>
                        </td>

                        <td>
                            <?= nl2br(Html::encode($role->description)) ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>
</div>

<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-title">
            <h5>
                Permissions
            </h5>
        </div>

        <div class="ibox-content">
            <table class="table table-condensed">
                <tr>
                    <th style="width: 1%;">Name</th>
                    <th>Description</th>
                </tr>

                <?php
                /** @var \yii\rbac\Permission $perm */
                foreach ($data['permissions'] as $perm) {
                    ?>
                    <tr>
                        <td>
                            <?= Html::encode($perm->name) ?>
                        </td>

                        <td>
                            <?= nl2br(Html::encode($perm->description)) ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>
</div>
