<?php

namespace common\fixtures;

use common\models\Users;
use yii\test\ActiveFixture;

class User extends ActiveFixture
{
    public $modelClass = Users::class;
}
