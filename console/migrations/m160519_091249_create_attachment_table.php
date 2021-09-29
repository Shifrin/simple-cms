<?php

use yii\db\Migration;

/**
 * Handles the creation for table `attachment_table`.
 */
class m160519_091249_create_attachment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        // Attachment table
        $this->createTable('{{%attachment}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'ext' => $this->string(10)->notNull(),
            'size' => $this->integer()->notNull(),
            'type' => $this->string(255)->notNull(),
            'path' => $this->string(1024)->notNull(),
            'thumbnail_size' => $this->string(255),
            'meta_data' => $this->string(255),
            'status' => $this->integer(2)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%attachment}}');
    }
}
