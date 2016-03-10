<?php

use yii\db\Schema;
use yii\db\Migration;

class m160120_122127_create_post_rating extends Migration
{
    public function up()
    {
        $this->createTable('rating', [
            'id' => Schema::TYPE_PK,
            'post_id' => Schema::TYPE_INTEGER.' NOT NULL',
            'rates' => Schema::TYPE_FLOAT.' NOT NULL',
            'ip' => Schema::TYPE_STRING.'(32) NOT NULL',
        ]);
        $this->addForeignKey('rating_post_id','rating','post_id','post','id');
    }

    public function down()
    {
        $this->dropTable('rating');
    }
}
