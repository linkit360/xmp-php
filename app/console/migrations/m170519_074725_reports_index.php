<?php

use yii\db\Migration;

class m170519_074725_reports_index extends Migration
{
    public function safeUp()
    {
        $this->execute("CREATE INDEX xmp_reports_id_campaign_index ON xmp_reports (id_campaign);");
    }

    public function safeDown()
    {
        $this->execute("DROP INDEX xmp_reports_id_campaign_index RESTRICT;");
    }
}
