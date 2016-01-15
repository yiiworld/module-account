<?php

use yii\db\Schema;

class m160115_202234_account_merging_auth_tables extends \cookyii\db\Migration
{

    static $providers = ['facebook', 'github', 'google', 'linkedin', 'live', 'twitter', 'vkontakte', 'yandex'];

    public function up()
    {
        $this->createTable('{{%account_auth}}', [
            'social_type' => Schema::TYPE_STRING,
            'social_id' => Schema::TYPE_STRING,
            'account_id' => Schema::TYPE_INTEGER,
            'token' => Schema::TYPE_TEXT,
            'PRIMARY KEY ([[social_type]], [[social_id]], [[account_id]])',
            'FOREIGN KEY (account_id) REFERENCES {{%account}} (id) ON DELETE CASCADE ON UPDATE CASCADE',
        ]);

        foreach (static::$providers as $provider) {
            $data = (new \yii\db\Query)
                ->select('*')
                ->from('{{%account_auth_' . $provider . '}}')
                ->all();

            $this->dropTable('{{%account_auth_' . $provider . '}}');

            $values = [];
            if (!empty($data)) {
                foreach ($data as $row) {
                    $values[] = [
                        $provider,
                        $row['social_id'],
                        $row['account_id'],
                        $row['token'],
                    ];
                }
            }

            if (!empty($values)) {
                $this->batchInsert('{{%account_auth}}', ['social_type', 'social_id', 'account_id', 'token'], $values);
            }
        }
    }

    public function down()
    {
        $data = (new \yii\db\Query)
            ->select('*')
            ->from('{{%account_auth}}')
            ->all();

        $this->dropTable('{{%account_auth}}');

        foreach (static::$providers as $provider) {
            $this->createTable('{{%account_auth_' . $provider . '}}', [
                'account_id' => Schema::TYPE_INTEGER,
                'social_id' => Schema::TYPE_STRING,
                'token' => Schema::TYPE_TEXT,
                'PRIMARY KEY (`account_id`, `social_id`)',
                'FOREIGN KEY (account_id) REFERENCES {{%account}} (id) ON DELETE CASCADE ON UPDATE CASCADE',
            ]);

            $values = [];
            if (!empty($data)) {
                foreach ($data as $row) {
                    if ($row['social_type'] === $provider) {
                        $values[] = [
                            $row['account_id'],
                            $row['social_id'],
                            $row['token'],
                        ];
                    }
                }
            }

            if (!empty($values)) {
                $this->batchInsert('{{%account_auth_' . $provider . '}}', ['account_id', 'social_id', 'token'], $values);
            }
        }
    }
}