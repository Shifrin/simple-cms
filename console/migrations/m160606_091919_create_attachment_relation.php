<?php

use yii\db\Migration;

/**
 * Handles the creation for table `attachment_relation`.
 */
class m160606_091919_create_attachment_relation extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        // Attachment Relation table
        $this->createTable('{{%attachment_relation}}', [
            'id' => $this->primaryKey(),
            'attachment_id' => $this->integer()->notNull(),
            'model_id' => $this->integer()->notNull(),
            'model' => $this->string(255)->notNull(),
        ]);
        // Foreign key
        $this->addForeignKey('fk_attachment', '{{%attachment_relation}}', 'attachment_id', '{{%attachment}}',
            'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_attachment', '{{%attachment_relation}}');
        $this->dropTable('{{%attachment_relation}}');
    }
}
