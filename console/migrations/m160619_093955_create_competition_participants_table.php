<?php

use yii\db\Migration;

/**
 * Handles the creation for table `competition_participants_table`.
 */
class m160619_093955_create_competition_participants_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // Table option
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        // Table Columns
        $this->createTable('{{%competition_participant}}', [
            'id' => $this->primaryKey(),
            'competition_id' => $this->integer(),
            'user_id' => $this->integer()->notNull()->unique(),
            'comment' => $this->text()
        ], $tableOptions);
        // Foreign key
        $this->addForeignKey('fk_competition_participant_user', '{{%competition_participant}}', 'user_id', '{{%user}}',
            'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_competition_participant_user', '{{%competition_participant}}');
        $this->dropTable('{{%competition_participant}}');
    }
}
