<?php

use yii\db\Schema;
use yii\db\Migration;

class m160114_212755_create_user_profile_table extends Migration
{
    public function safeUp()
    {
        $this->createTable(
            'user_profile',
            [
                'user_id' => Schema::TYPE_PK,
                'avatar' => Schema::TYPE_STRING,
                'first_name' => Schema::TYPE_STRING.'(32)',
                'second_name' => Schema::TYPE_STRING.'(32)',
                'middle_name' => Schema::TYPE_STRING.'(32)',
                'birthday' => Schema::TYPE_STRING.'(32)',
                'gender' => Schema::TYPE_SMALLINT
            ]
        );
        $this->addForeignKey('profile_user', 'user_profile', 'user_id', 'user', 'id', 'cascade', 'cascade');
    }

    public function safeDown()
    {
        $this->dropForeignKey('profile_user', 'user_profile');
        $this->dropTable('user_profile');
    }

}
