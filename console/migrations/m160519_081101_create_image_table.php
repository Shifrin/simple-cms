<?php

use yii\db\Migration;

/**
 * Handles the creation for table `image_table`.
 */
class m160519_081101_create_image_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%image}}', [
            'id' => $this->primaryKey(),
            'unique_id' => $this->integer()->notNull()->unique(),
            'description' => $this->text(),
            'status' => $this->smallInteger(2)->notNull(),
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
        $this->dropTable('{{%image}}');
    }
}
