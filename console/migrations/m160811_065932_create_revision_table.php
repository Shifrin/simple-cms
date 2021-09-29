<?php

use yii\db\Migration;

/**
 * Handles the creation for table `revision_table`.
 */
class m160811_065932_create_revision_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%revision}}', [
            'id' => $this->primaryKey(),
            'type' => $this->smallInteger(2),
            'model_id' => $this->integer()->notNull(),
            'model' => $this->char(32)->notNull(),
            'title' => $this->text()->notNull(),
            'slug' => $this->text()->notNull(),
            'content' => 'LONGTEXT DEFAULT NULL',
            'summary' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%revision}}');
    }
}
