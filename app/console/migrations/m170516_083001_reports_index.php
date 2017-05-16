<?php

use yii\db\Migration;

class m170516_083001_reports_index extends Migration
{
    public function safeUp()
    {
        $this->execute("DROP TABLE IF EXISTS xmp_reports_tmp;");
        $this->execute("CREATE TABLE xmp_reports_tmp AS TABLE xmp_reports;");
        $this->execute("TRUNCATE xmp_reports_tmp;");
        $this->execute("CREATE UNIQUE INDEX xmp_reports_tmp_id_campaign_operator_code_report_at_uindex ON xmp_reports_tmp (id_campaign, operator_code, report_at);");
        $this->execute("INSERT INTO xmp_reports_tmp SELECT * FROM xmp_reports ON CONFLICT DO NOTHING;");
        $this->execute("TRUNCATE xmp_reports;");
        $this->execute("CREATE UNIQUE INDEX xmp_reports_id_campaign_operator_code_report_at_uindex ON xmp_reports (id_campaign, operator_code, report_at);");
        $this->execute("INSERT INTO xmp_reports SELECT * FROM xmp_reports_tmp ON CONFLICT DO NOTHING;");
        $this->execute("DROP TABLE IF EXISTS xmp_reports_tmp;");
    }

    public function safeDown()
    {
        echo "m170516_083001_reports_index cannot be reverted.\n";
        return false;
    }
}
