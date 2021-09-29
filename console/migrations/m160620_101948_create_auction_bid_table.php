<?php

use yii\db\Migration;

/**
 * Handles the creation for table `auction_bid`.
 */
class m160620_101948_create_auction_bid_table extends Migration
{
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // Table option
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        // Create Table
        $this->createTable('{{%auction_bid}}', [
            'id' => $this->primaryKey(),
            'auction_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
        ], $tableOptions);
        // Add Foreign Key
        $this->addForeignKey('fk_auction_bid', '{{%auction_bid}}', 'auction_id', '{{%auction}}',
            'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_auction_bid', '{{%auction_bid}}');
        $this->dropTable('{{%auction_bid}}');
    }
    
}
