<?php

use yii\db\Schema;
use yii\db\Migration;
use common\models\User;

class m151106_072252_create_text_user extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
		try{
			$this->createUser('test@loc', '1111');
		}catch(\Exception $e){
			return false;
		}
    }

    public function safeDown()
    {
		User::findByEmail('test@loc')->delete();
    }
	
	private function createUser($userNameString, $userPasswordString){
		$me = new User();
		$me->email = $userNameString;
		$me->password = $userPasswordString;
		$me->status = User::STATUS_ACTIVE;
		$me->save();
	}
    
}
