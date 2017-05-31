<?php

use yii\db\Migration;

class m170531_090010_alter_reports extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE xmp_reports ADD id_instance UUID NULL;");

        // mark lines
        $this->execute("UPDATE xmp_reports SET id_instance = 'a7da1e9f-fcc1-4087-9c58-4d31bcdbd515' WHERE provider_name = 'qrtech'");
        $this->execute("UPDATE xmp_reports SET id_instance = '58fbedf7-1abc-402b-8c2a-89fe256d32d9' WHERE provider_name = 'mobilink'");
        $this->execute("UPDATE xmp_reports SET id_instance = '299a3335-6b05-4613-8fd9-0dbfb94db6ee' WHERE provider_name = 'cheese'");

        // delete testing data && other providers
        $this->execute("DELETE FROM xmp_reports WHERE id_instance IS NULL");

        $this->execute("ALTER TABLE xmp_reports ALTER COLUMN id_instance SET NOT NULL;");
        $this->execute("ALTER TABLE xmp_reports DROP provider_name;");
    }

    public function safeDown()
    {
        echo "m170531_090010_alter_reports cannot be reverted.\n";
        return false;
    }
}
