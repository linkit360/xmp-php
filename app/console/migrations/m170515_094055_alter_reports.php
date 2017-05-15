<?php

use yii\db\Migration;

class m170515_094055_alter_reports extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE xmp_reports ALTER COLUMN mo_charge_failed DROP DEFAULT;");
        $this->execute("ALTER TABLE xmp_reports ALTER COLUMN mo_charge_sum DROP DEFAULT;");
        $this->execute("ALTER TABLE xmp_reports ALTER COLUMN mo_rejected DROP DEFAULT;");
        $this->execute("ALTER TABLE xmp_reports ALTER COLUMN renewal_charge_success DROP DEFAULT;");
        $this->execute("ALTER TABLE xmp_reports ALTER COLUMN renewal_charge_sum DROP DEFAULT;");
        $this->execute("ALTER TABLE xmp_reports ALTER COLUMN renewal_failed DROP DEFAULT;");
    }

    public function safeDown()
    {
        $this->execute("ALTER TABLE xmp_reports ALTER COLUMN mo_charge_failed SET DEFAULT 0;");
        $this->execute("ALTER TABLE xmp_reports ALTER COLUMN mo_charge_sum SET DEFAULT 0;");
        $this->execute("ALTER TABLE xmp_reports ALTER COLUMN mo_rejected SET DEFAULT 0;");
        $this->execute("ALTER TABLE xmp_reports ALTER COLUMN renewal_charge_success SET DEFAULT 0;");
        $this->execute("ALTER TABLE xmp_reports ALTER COLUMN renewal_charge_sum SET DEFAULT 0;");
        $this->execute("ALTER TABLE xmp_reports ALTER COLUMN renewal_failed SET DEFAULT 0;");
    }
}
