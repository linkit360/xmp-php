<?php

use yii\db\Migration;

class m170320_091830_create_transactions extends Migration
{
    public function safeUp()
    {
        $this->execute('CREATE TABLE xmp_transactions_results (name VARCHAR(127) NOT NULL PRIMARY KEY);');
        $this->execute("INSERT INTO xmp_transactions_results VALUES
                           ('failed'),
                           ('sms'),
                           ('paid'),
                           ('retry_failed'),
                           ('retry_paid'),
                           ('rejected'),
                           ('expired_paid'),
                           ('expired_failed'),
                           ('injection_paid'),
                           ('injection_failed');");

        $this->execute("CREATE TABLE xmp_transactions
                            (
                              id              UUID PRIMARY KEY   NOT NULL   DEFAULT public.uuid_generate_v4(),
                              created_at      TIMESTAMP          NOT NULL   DEFAULT NOW(),
                              sent_at         TIMESTAMP          NOT NULL   DEFAULT NOW(),
                              tid             VARCHAR(127)       NOT NULL   DEFAULT '',
                              msisdn          VARCHAR(32)        NOT NULL,
                              id_country      INTEGER            NOT NULL   DEFAULT 0,
                              id_service      INTEGER            NOT NULL   DEFAULT 0,
                              id_campaign     INTEGER DEFAULT 0  NOT NULL,
                              id_subscription INTEGER            NOT NULL   DEFAULT 0,
                              id_content      INTEGER            NOT NULL   DEFAULT 0,
                              id_operator     INTEGER            NOT NULL   DEFAULT 0,
                              operator_token  VARCHAR(511)       NOT NULL,
                              price           INTEGER            NOT NULL,
                              result          VARCHAR(127)       NOT NULL,
                              CONSTRAINT xmp_transactions_result_fk FOREIGN KEY (result) REFERENCES xmp_transactions_results (name)
                            );
                      ");

//        $this->execute("INSERT INTO xmp_transactions (created_at, msisdn, id_country, id_service, id_operator, id_subscription, operator_token, price, result, id_campaign, tid, sent_at) VALUES ('2016-12-22 15:51:45.479813', '923006796667', 2, 888, 27, 1211395, '92300679666722155144', 3600, 'failed', 354, '1482421537-885f5b2c-9bb0-4676-7a44-ba7a129f5163', '2016-12-22 15:51:45.471453');");
//        $this->execute("INSERT INTO xmp_transactions (created_at, msisdn, id_country, id_service, id_operator, id_subscription, operator_token, price, result, id_campaign, tid, sent_at) VALUES ('2016-12-22 15:51:45.479813', '923006796667', 2, 888, 27, 1211395, '92300679666722155144', 3600, 'failed', 354, '1482421537-885f5b2c-9bb0-4676-7a44-ba7a129f5163', '2016-12-22 15:51:45.471453');");
//        $this->execute("INSERT INTO xmp_transactions (created_at, msisdn, id_country, id_service, id_operator, id_subscription, operator_token, price, result, id_campaign, tid, sent_at) VALUES ('2016-12-22 16:22:19.009568', '923023423232', 2, 777, 27, 636682, '92302342323222162208', 3600, 'retry_failed', 374, '1481662057-bd59676f-0bfa-4440-5d0c-d323c6630f1e', '2016-12-22 16:22:18.999534');");
//        $this->execute("INSERT INTO xmp_transactions (created_at, msisdn, id_country, id_service, id_operator, id_subscription, operator_token, price, result, id_campaign, tid, sent_at) VALUES ('2016-12-22 16:49:44.890876', '923062834454', 2, 888, 27, 340315, '92306283445422164943', 3600, 'retry_failed', 354, '1480761928-3d588dd1-7d3f-4073-6cbf-4385661770d4', '2016-12-22 16:49:44.881307');");
//        $this->execute("INSERT INTO xmp_transactions (created_at, msisdn, id_country, id_service, id_operator, id_subscription, operator_token, price, result, id_campaign, tid, sent_at) VALUES ('2016-12-22 17:08:04.465113', '923051645290', 2, 888, 27, 403971, '92305164529022170803', 3600, 'retry_failed', 354, '1480793852-2ccfb8d3-803f-4bbf-5d3b-b05935c4d16c', '2016-12-22 17:08:04.456186');");
//        $this->execute("INSERT INTO xmp_transactions (created_at, msisdn, id_country, id_service, id_operator, id_subscription, operator_token, price, result, id_campaign, tid, sent_at) VALUES ('2016-12-22 17:21:41.997759', '923042358310', 2, 888, 27, 936682, '92304235831022172140', 3600, 'retry_failed', 416, '1482198580-72e4365a-f5ff-4489-53a3-549c123e8065', '2016-12-22 17:21:41.988796');");
//        $this->execute("INSERT INTO xmp_transactions (created_at, msisdn, id_country, id_service, id_operator, id_subscription, operator_token, price, result, id_campaign, tid, sent_at) VALUES ('2016-12-22 17:33:01.623347', '923040515200', 2, 777, 27, 253393, '92304051520022173300', 3600, 'retry_failed', 352, '1480705729-a983a9cc-0667-45bb-7c5a-a6d4f186412d', '2016-12-22 17:33:01.614873');");
//        $this->execute("INSERT INTO xmp_transactions (created_at, msisdn, id_country, id_service, id_operator, id_subscription, operator_token, price, result, id_campaign, tid, sent_at) VALUES ('2016-12-22 17:41:52.838753', '923016368556', 2, 888, 27, 233828, '92301636855622174151', 3600, 'retry_failed', 354, '1480685270-8eba827d-4d62-4598-63f2-9b3ef6dc1dbe', '2016-12-22 17:41:52.834039');");
//        $this->execute("INSERT INTO xmp_transactions (created_at, msisdn, id_country, id_service, id_operator, id_subscription, operator_token, price, result, id_campaign, tid, sent_at) VALUES ('2016-12-22 17:41:53.030322', '923048954577', 2, 777, 27, 839758, '92304895457722174151', 3600, 'retry_failed', 353, '1482055282-e23f0cf9-71fd-4024-638e-d89e34a8bfab', '2016-12-22 17:41:53.018227');");
//        $this->execute("INSERT INTO xmp_transactions (created_at, msisdn, id_country, id_service, id_operator, id_subscription, operator_token, price, result, id_campaign, tid, sent_at) VALUES ('2016-12-22 17:41:53.246088', '923004808875', 2, 888, 27, 778191, '92300480887522174152', 3600, 'retry_failed', 354, '1481881843-e37e3bc8-e153-43d2-4e6c-265ffe68f0dd', '2016-12-22 17:41:53.242704');");
    }

    public function safeDown()
    {
        $this->dropTable('xmp_transactions');
        $this->dropTable('xmp_transactions_results');
    }
}
