<?php

use yii\db\Migration;

class m170315_102451_create_xmp_reports extends Migration
{
    public function safeUp()
    {
        $this->execute('CREATE TABLE xmp_reports
                            (
                              id             UUID PRIMARY KEY DEFAULT public.uuid_generate_v4() NOT NULL,
                              report_date    TIMESTAMP                                          NOT NULL,
                              id_campaign    INT                                                NOT NULL,
                              id_provider    VARCHAR(127)                                       NOT NULL,
                              id_operator    INT                                                NOT NULL,
                              lp_hits        INT                                                NOT NULL,
                              lp_msisdn_hits INT                                                NOT NULL,
                              mo             INT                                                NOT NULL,
                              mo_uniq        INT                                                NOT NULL,
                              mo_success     INT                                                NOT NULL,
                              pixels         INT                                                NOT NULL
                            );
                      ');

        $this->execute("COMMENT ON TABLE xmp_reports IS 'AD && Conversion Reports';");

        $this->execute("COMMENT ON COLUMN xmp_reports.id IS 'ID';");
        $this->execute("COMMENT ON COLUMN xmp_reports.report_date IS 'Report Date';");
        $this->execute("COMMENT ON COLUMN xmp_reports.id_campaign IS 'Campaign ID';");
        $this->execute("COMMENT ON COLUMN xmp_reports.id_provider IS 'Provider ID';");
        $this->execute("COMMENT ON COLUMN xmp_reports.id_operator IS 'Operator ID';");
        $this->execute("COMMENT ON COLUMN xmp_reports.lp_hits IS 'LP Hits';");
        $this->execute("COMMENT ON COLUMN xmp_reports.lp_msisdn_hits IS 'LP Hits (with MSISDN)';");
        $this->execute("COMMENT ON COLUMN xmp_reports.mo IS 'Valid Transactions';");
        $this->execute("COMMENT ON COLUMN xmp_reports.mo_uniq IS 'Unique Transactions';");
        $this->execute("COMMENT ON COLUMN xmp_reports.mo_success IS 'Success Transactions';");
        $this->execute("COMMENT ON COLUMN xmp_reports.pixels IS 'Pixels Sent';");
    }

    public function safeDown()
    {
        $this->dropTable('xmp_reports');
    }
}
