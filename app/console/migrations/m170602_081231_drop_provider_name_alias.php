<?php

use yii\db\Migration;

class m170602_081231_drop_provider_name_alias extends Migration
{
    public function safeUp()
    {
        $this->execute('ALTER TABLE xmp_providers DROP name_alias;');
    }

    public function safeDown()
    {
        $this->execute("ALTER TABLE xmp_providers ADD name_alias VARCHAR(255) DEFAULT - NOT NULL;");
    }
}
