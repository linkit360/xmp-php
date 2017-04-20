<?php

use yii\db\Migration;

class m170320_153931_alter_reports extends Migration
{
    public function safeUp()
    {
        $this->dropTable('xmp_reports');
        $this->execute("CREATE TABLE xmp_reports
                            (
                              id             UUID DEFAULT uuid_generate_v4() PRIMARY KEY NOT NULL,
                              report_at      TIMESTAMP                                   NOT NULL,
                              id_campaign    BIGINT                                      NOT NULL,
                              provider_name  VARCHAR(127)                                NOT NULL,
                              operator_code  BIGINT                                      NOT NULL,
                              lp_hits        BIGINT                                      NOT NULL,
                              lp_msisdn_hits BIGINT                                      NOT NULL,
                              mo             BIGINT                                      NOT NULL,
                              mo_uniq        BIGINT                                      NOT NULL,
                              mo_success     BIGINT                                      NOT NULL,
                              retry_success  BIGINT                                      NOT NULL,
                              pixels         BIGINT                                      NOT NULL
                            );
                       ");

        $this->execute("COMMENT ON COLUMN xmp_reports.id IS 'ID';");
        $this->execute("COMMENT ON COLUMN xmp_reports.report_at IS 'Report Date';");
        $this->execute("COMMENT ON COLUMN xmp_reports.id_campaign IS 'Campaign ID';");
        $this->execute("COMMENT ON COLUMN xmp_reports.provider_name IS 'Provider ID';");
        $this->execute("COMMENT ON COLUMN xmp_reports.operator_code IS 'Operator ID';");
        $this->execute("COMMENT ON COLUMN xmp_reports.lp_hits IS 'LP Hits';");
        $this->execute("COMMENT ON COLUMN xmp_reports.lp_msisdn_hits IS 'LP Hits (with MSISDN)';");
        $this->execute("COMMENT ON COLUMN xmp_reports.mo IS 'Valid Transactions';");
        $this->execute("COMMENT ON COLUMN xmp_reports.mo_uniq IS 'Unique Transactions';");
        $this->execute("COMMENT ON COLUMN xmp_reports.mo_success IS 'Success Transactions';");
        $this->execute("COMMENT ON COLUMN xmp_reports.retry_success IS 'Success Retry';");
        $this->execute("COMMENT ON COLUMN xmp_reports.pixels IS 'Pixels Sent';");
    }

    public function safeDown()
    {
        echo "m170320_153931_alter_reports cannot be reverted.\n";

        return false;
    }
}
