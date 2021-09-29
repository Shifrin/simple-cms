<?php

use yii\db\Migration;

/**
 * Handles the creation for table `auction_table`.
 */
class m160620_094330_create_auction_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // Table option
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        // Create Table
        $this->createTable('{{%auction}}', [
            'id' => $this->primaryKey(),
            'collection_id' => $this->integer()->notNull(),
            'start_price' => $this->decimal(10, 2)->notNull(),
            'start_time' => $this->integer()->notNull(),
            'end_time' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
        ], $tableOptions);
        // Add Foreign Key
        $this->addForeignKey('fk_auction_collection', '{{%auction}}', 'collection_id', '{{%post}}',
            'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_auction_collection', '{{%auction}}');
        $this->dropTable('{{%auction}}');
    }

}
