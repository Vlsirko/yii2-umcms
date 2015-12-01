<?php

use yii\db\Schema;
use yii\db\Migration;
use Umcms\models\User;

class m151113_113238_update_user_table extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
		try{
			$this->addColumn(User::tableName(), 'image_path', 'VARCHAR(255) NULL COMMENT "Изображение|text"');
			$this->addColumn(User::tableName(), 'user_ip', 'VARCHAR(255) NULL COMMENT "IP|text"');
			
		}catch(\Exception $e){
			
			return false;
		}
    }

    public function safeDown()
    {
		
		try{
			$this->dropColumn(User::tableName(), 'image_path');
			$this->dropColumn(User::tableName(), 'user_ip');
		}catch(\Exception $e){
			return false;
		}
    }
}
