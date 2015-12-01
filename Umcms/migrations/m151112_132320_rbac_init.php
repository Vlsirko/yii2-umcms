<?php

use yii\db\Schema;
use yii\db\Migration;
use common\models\User;

class m151112_132320_rbac_init extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
		$role = Yii::$app->authManager->createRole('admin');
		$role->description = 'Админ';
		Yii::$app->authManager->add($role);

		$roleUser = Yii::$app->authManager->createRole('user');
		$roleUser->description = 'Юзер';
		Yii::$app->authManager->add($roleUser);
		
		$me = User::findByEmail('test@loc');
		\Yii::$app->authManager->assign($role, $me->getId());
	
    }

    public function safeDown()
    {
		Yii::$app->authManager->removeAll();
    }
}
