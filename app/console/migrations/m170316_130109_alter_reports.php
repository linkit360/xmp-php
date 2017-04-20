<?php

use yii\db\Migration;

class m170316_130109_alter_reports extends Migration
{
    public function safeUp()
    {
        $this->execute('ALTER TABLE xmp_reports ALTER COLUMN id_campaign TYPE BIGINT USING id_campaign::BIGINT;');
        $this->execute('ALTER TABLE xmp_reports ALTER COLUMN lp_msisdn_hits TYPE BIGINT USING lp_msisdn_hits::BIGINT;');
        $this->execute('ALTER TABLE xmp_reports ALTER COLUMN id_operator TYPE BIGINT USING id_operator::BIGINT;');
        $this->execute('ALTER TABLE xmp_reports ALTER COLUMN lp_hits TYPE BIGINT USING lp_hits::BIGINT;');
        $this->execute('ALTER TABLE xmp_reports ALTER COLUMN mo TYPE BIGINT USING mo::BIGINT;');
        $this->execute('ALTER TABLE xmp_reports ALTER COLUMN mo_uniq TYPE BIGINT USING mo_uniq::BIGINT;');
        $this->execute('ALTER TABLE xmp_reports ALTER COLUMN mo_success TYPE BIGINT USING mo_success::BIGINT;');
        $this->execute('ALTER TABLE xmp_reports ALTER COLUMN pixels TYPE BIGINT USING pixels::BIGINT;');
    }

    public function safeDown()
    {

    }
}
