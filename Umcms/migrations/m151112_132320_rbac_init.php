<?php

use yii\db\Schema;
use yii\db\Migration;
use Umcms\models\User;

class m151112_132320_rbac_init extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
		yii::$app->cache->flush();
		$role = Yii::$app->authManager->createRole('admin');
		$role->description = 'Админ';
		Yii::$app->authManager->add($role);

		$roleUser = Yii::$app->authManager->createRole('user');
		$roleUser->description = 'Юзер';
		Yii::$app->authManager->add($roleUser);
		
		$permissions = Yii::$app->authManager->getPermissions();
		foreach ($permissions as $p){
			\Yii::$app->authManager->addChild($role, $p);
		}
		
		$me = User::findByEmail('test@loc');
		\Yii::$app->authManager->assign($role, $me->getId());
	
    }

    public function safeDown()
    {
		Yii::$app->authManager->removeAll();
    }
}
