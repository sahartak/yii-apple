<?php

use yii\db\Migration;
use common\models\User;

/**
 * Class m200320_110439_add_admin_user
 */
class m200320_110439_add_admin_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $user = new User();
      $user->username = 'admin2020';
      $user->status = User::STATUS_ACTIVE;
      $user->generateAuthKey();
      $user->setPassword('123456');
      $user->email = 'test@task.com';
      $user->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200320_110439_add_admin_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200320_110439_add_admin_user cannot be reverted.\n";

        return false;
    }
    */
}
