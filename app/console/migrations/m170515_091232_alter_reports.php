<?php

use yii\db\Migration;

class m170515_091232_alter_reports extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE xmp_reports ADD mo_charge_failed BIGINT DEFAULT 0 NOT NULL;");
        $this->execute("ALTER TABLE xmp_reports ADD mo_charge_sum BIGINT DEFAULT 0 NOT NULL;");
        $this->execute("ALTER TABLE xmp_reports ADD mo_rejected BIGINT DEFAULT 0 NOT NULL;");
        $this->execute("ALTER TABLE xmp_reports ADD renewal_charge_success BIGINT DEFAULT 0 NOT NULL;");
        $this->execute("ALTER TABLE xmp_reports ADD renewal_charge_sum BIGINT DEFAULT 0 NOT NULL;");
        $this->execute("ALTER TABLE xmp_reports ADD renewal_failed BIGINT DEFAULT 0 NOT NULL;");
        $this->execute("ALTER TABLE xmp_reports RENAME COLUMN mo TO mo_total;");
        $this->execute("ALTER TABLE xmp_reports RENAME COLUMN mo_success TO mo_charge_success;");
        $this->execute("ALTER TABLE xmp_reports RENAME COLUMN retry_success TO renewal_total;");
        $this->execute("ALTER TABLE xmp_reports DROP mo_uniq;");
    }

    public function safeDown()
    {
        $this->execute("ALTER TABLE xmp_reports DROP mo_charge_failed;");
        $this->execute("ALTER TABLE xmp_reports DROP mo_charge_sum;");
        $this->execute("ALTER TABLE xmp_reports DROP mo_rejected;");
        $this->execute("ALTER TABLE xmp_reports DROP renewal_charge_success;");
        $this->execute("ALTER TABLE xmp_reports DROP renewal_charge_sum;");
        $this->execute("ALTER TABLE xmp_reports DROP renewal_failed;");
        $this->execute("ALTER TABLE xmp_reports ADD mo_uniq BIGINT DEFAULT 0 NOT NULL;");
        $this->execute("ALTER TABLE xmp_reports RENAME COLUMN mo_total TO mo;");
        $this->execute("ALTER TABLE xmp_reports RENAME COLUMN mo_charge_success TO mo_success;");
        $this->execute("ALTER TABLE xmp_reports RENAME COLUMN renewal_total TO retry_success;");
    }
}
