<?php

use yii\db\Migration;

class m170320_073352_alter_blacklist extends Migration
{
    public function safeUp()
    {
        $this->truncateTable('xmp_msisdn_blacklist');
        $this->execute('ALTER TABLE xmp_msisdn_blacklist RENAME COLUMN provider_name TO id_provider;');
        $this->execute('ALTER TABLE xmp_msisdn_blacklist ALTER COLUMN id_provider TYPE INTEGER USING id_provider::INTEGER;');
        $this->execute('ALTER TABLE xmp_msisdn_blacklist RENAME COLUMN operator_code TO id_operator;');
    }

    public function safeDown()
    {
        echo "m170320_073352_alter_blacklist cannot be reverted.\n";
        return false;
    }
}
