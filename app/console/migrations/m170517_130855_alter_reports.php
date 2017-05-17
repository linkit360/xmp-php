<?php

use yii\db\Migration;

class m170517_130855_alter_reports extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE xmp_reports ADD injection_total BIGINT DEFAULT 0 NOT NULL;");
        $this->execute("ALTER TABLE xmp_reports ADD injection_charge_success BIGINT DEFAULT 0 NOT NULL;");
        $this->execute("ALTER TABLE xmp_reports ADD injection_charge_sum BIGINT DEFAULT 0 NOT NULL;");
        $this->execute("ALTER TABLE xmp_reports ADD injection_failed BIGINT DEFAULT 0 NOT NULL;");

        $this->execute("ALTER TABLE xmp_reports ADD expired_total BIGINT DEFAULT 0 NOT NULL;");
        $this->execute("ALTER TABLE xmp_reports ADD expired_charge_success BIGINT DEFAULT 0 NOT NULL;");
        $this->execute("ALTER TABLE xmp_reports ADD expired_charge_sum BIGINT DEFAULT 0 NOT NULL;");
        $this->execute("ALTER TABLE xmp_reports ADD expired_failed BIGINT DEFAULT 0 NOT NULL;");


        $this->execute("ALTER TABLE xmp_reports ALTER COLUMN injection_total DROP DEFAULT;");
        $this->execute("ALTER TABLE xmp_reports ALTER COLUMN injection_charge_success DROP DEFAULT;");
        $this->execute("ALTER TABLE xmp_reports ALTER COLUMN injection_charge_sum DROP DEFAULT;");
        $this->execute("ALTER TABLE xmp_reports ALTER COLUMN injection_failed DROP DEFAULT;");

        $this->execute("ALTER TABLE xmp_reports ALTER COLUMN expired_total DROP DEFAULT;");
        $this->execute("ALTER TABLE xmp_reports ALTER COLUMN expired_charge_success DROP DEFAULT;");
        $this->execute("ALTER TABLE xmp_reports ALTER COLUMN expired_charge_sum DROP DEFAULT;");
        $this->execute("ALTER TABLE xmp_reports ALTER COLUMN expired_failed DROP DEFAULT;");
    }

    public function safeDown()
    {
        $this->execute("ALTER TABLE xmp_reports DROP injection_total;");
        $this->execute("ALTER TABLE xmp_reports DROP injection_charge_success;");
        $this->execute("ALTER TABLE xmp_reports DROP injection_charge_sum;");
        $this->execute("ALTER TABLE xmp_reports DROP injection_failed;");

        $this->execute("ALTER TABLE xmp_reports DROP expired_total;");
        $this->execute("ALTER TABLE xmp_reports DROP expired_charge_success;");
        $this->execute("ALTER TABLE xmp_reports DROP expired_charge_sum;");
        $this->execute("ALTER TABLE xmp_reports DROP expired_failed;");
    }
}
