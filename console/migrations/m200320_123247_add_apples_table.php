<?php

use yii\db\Migration;

/**
 * Class m200320_123247_add_apples_table
 */
class m200320_123247_add_apples_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
      $this->createTable('{{%apples}}', [
        'id' => $this->primaryKey(),
        'color' => $this->string(6),
        'on_tree' => $this->boolean()->defaultValue(true),
        'size' =>  $this->decimal(3,2)->defaultValue(1),
        'created_at' => $this->integer(),
        'fall_at' => $this->integer(),
      ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200320_123247_add_apples_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200320_123247_add_apples_table cannot be reverted.\n";

        return false;
    }
    */
}
