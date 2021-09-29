<?php

use yii\db\Migration;

class m160502_122037_create_page_table extends Migration
{
    public function safeUp()
    {
        // Table option
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        // Create new Post table
        $this->createTable('{{%page}}', [
            'id' => $this->primaryKey(),
            'title' => $this->text()->notNull(),
            'slug' => $this->text()->notNull(),
            'content' => 'LONGTEXT DEFAULT NULL',
            'main_layout' => $this->string(),
            'partial_layout' => $this->string(),
            'status' => $this->smallInteger()->notNull(),
            'publish_at' => $this->integer(),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%page}}');
    }
}
