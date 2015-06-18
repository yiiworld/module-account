<?php

use yii\db\mysql\Schema;

class m150610_170115_account_properties extends \components\db\Migration
{

    public function up()
    {
        $this->createTable(
            '{{%account_property}}',
            [
                'account_id' => Schema::TYPE_INTEGER,
                'key' => Schema::TYPE_STRING . ' NOT NULL',
                'value' => Schema::TYPE_TEXT,
                'created_at' => Schema::TYPE_INTEGER,
                'updated_at' => Schema::TYPE_INTEGER,
                'PRIMARY KEY (`account_id`, `key`)',
                'FOREIGN KEY (account_id) REFERENCES {{%account}} (id) ON DELETE CASCADE ON UPDATE CASCADE',
            ]
        );
    }

    public function down()
    {
        $this->dropTable('{{%account_property}}');
    }
}