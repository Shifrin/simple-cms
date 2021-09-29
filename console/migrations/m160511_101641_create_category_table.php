<?php

use yii\db\Migration;

/**
 * Handles the creation for table `category_table`.
 */
class m160511_101641_create_category_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        // Category table
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'slug' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
        ], $tableOptions);
        // Category relation table
        $this->createTable('{{%category_relation}}', [
            'category_id' => $this->integer()->notNull(),
            'model_id' => $this->integer()->notNull(),
            'type' => $this->string()->notNull(),
        ], $tableOptions);
        // Foreign key
        $this->addForeignKey('fk_category', '{{%category_relation}}', 'category_id', '{{%category}}',
            'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_category', '{{%category_relation}}');
        $this->dropTable('{{%category_relation}}');
        $this->dropTable('{{%category}}');
    }
}
