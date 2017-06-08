<?php

use yii\db\Migration;

class m170608_093543_reports_index extends Migration
{
    public function safeUp()
    {
        $this->execute("CREATE INDEX xmp_reports_report_at_index ON xmp_reports (report_at);");
    }

    public function safeDown()
    {

    }
}
