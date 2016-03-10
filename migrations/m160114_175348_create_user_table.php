<?php

use yii\db\Schema;
use yii\db\Migration;

class m160114_175348_create_user_table extends Migration
{
    public function up()
    {
        $this->createTable('user', [
            'id' => Schema::TYPE_PK,
            'email' => Schema::TYPE_STRING.'(50) NOT NULL',
            'username' => Schema::TYPE_STRING.'(20) NOT NULL',
            'password_hash' => Schema::TYPE_STRING.'(100) NOT NULL',
            'password_reset_token' => $this->string()->unique(),
            'status' => Schema::TYPE_SMALLINT.' DEFAULT 10 NOT NULL',// $this->smallInteger()->notNull()->defaultValue(10),
            'auth_key' => Schema::TYPE_STRING.'(32) NOT NULL',
            'created_at' => Schema::TYPE_STRING.'(32) NOT NULL',
            'updated_at' => Schema::TYPE_STRING.'(32) NOT NULL'
        ]);
    }

    public function down()
    {
        $this->dropTable('user');
    }
}
