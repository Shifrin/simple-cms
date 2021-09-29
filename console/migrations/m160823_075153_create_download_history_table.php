<?php

use yii\db\Migration;

/**
 * Handles the creation for table `download_history`.
 */
class m160823_075153_create_download_history_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%download_history}}', [
            'id' => $this->primaryKey(),
            'image_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'downloaded_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // Add ForeignKey Constraints
        $this->addForeignKey('fk_download_photo', '{{%download_history}}', 'image_id', '{{%image}}',
            'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_download_user', '{{%download_history}}', 'user_id', '{{%user}}',
            'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%download_history}}');
    }
}
