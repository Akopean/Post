<?php

use yii\db\Schema;
use yii\db\Migration;

class m160115_174228_create_post_table extends Migration
{
    public function safeUp()
    {
        $this->createTable(
            'post',
            [
                'id' => Schema::TYPE_PK,
                'user_id' => Schema::TYPE_INTEGER.' NOT NULL',
                'post_name' => Schema::TYPE_STRING.' NOT NULL',
                'post_text' => Schema::TYPE_TEXT.' NOT NULL',
                'post_short_text' => Schema::TYPE_STRING.'(255) NOT NULL',
                'post_img' => Schema::TYPE_STRING,
                'post_status' => Schema::TYPE_INTEGER.' NOT NULL',
                'created_at' => Schema::TYPE_STRING.'(32) NOT NULL',
                'updated_at' => Schema::TYPE_STRING.'(32) NOT NULL',
            ]
        );
        $this->addForeignKey('post_user_id','post','user_id','user','id');
    }

    public function safeDown()
    {
        $this->dropTable('post');
    }
}
