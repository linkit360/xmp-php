<?php

use yii\db\Migration;

class m170619_135124_alter_services extends Migration
{
    public function safeUp()
    {
        $this->execute("UPDATE xmp_services SET price = price * 100;");
    }

    public function safeDown()
    {
        $this->execute("UPDATE xmp_services SET price = price / 100;");

    }
}
