<?php

use yii\db\Migration;

class m160502_075401_create_posts_table extends Migration
{
    public function safeUp()
    {
        // Table option
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        // Create new Post table
        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string()->notNull(),
            'title' => $this->text()->notNull(),
            'slug' => $this->text()->notNull(),
            'content' => 'LONGTEXT DEFAULT NULL',
            'summary' => $this->text(),
            'status' => $this->smallInteger()->notNull(),
            'publish_at' => $this->integer()->notNull(),
            'post_image' => $this->string(1024),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%post}}');
    }
}
