<?php

use yii\db\Migration;

class m160411_080317_create_user_related_tables extends Migration
{
    public function safeUp()
    {
        // Table option
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        // Create new User table
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'email_verify_key' => $this->string()->unique(),
            'registered_ip' => $this->string(50),
            'last_logged_in_ip' => $this->string(),
            'last_logged_in_time' => $this->integer(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
        ], $tableOptions);
        // Create User Profile table
        $this->createTable('{{%user_profile}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->unique(),
            'first_name' => $this->string(),
            'last_name' => $this->string(),
            'bio_info' => $this->text(),
            'gender' => $this->smallInteger(2),
            'website' => $this->string(1024),
            'linkedin' => $this->string(1024),
            'facebook' => $this->string(1024),
            'twitter' => $this->string(1024),
            'github' => $this->string(1024),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
        ], $tableOptions);
        // Add ForeignKey Constraint
        $this->addForeignKey('fk_profile_user', '{{%user_profile}}', 'user_id', '{{%user}}',
            'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown()
    {
        // Delete ForeignKey first
        $this->dropForeignKey('fk_profile_user', '{{%user_profile}}');
        // Delete tables
        $this->dropTable('{{%user_profile}}');
        $this->dropTable('{{%user}}');
    }
}
