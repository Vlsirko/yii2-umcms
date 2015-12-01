<?php

use yii\db\Schema;
use yii\db\Migration;

class m151123_061626_add_image_to_user extends Migration
{
  
    
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
		try {
			$this->addColumn('user', 'image', 'VARCHAR(255) NULL COMMENT "Изображение пользователя|image"');
		} catch (Exception $exc) {
			return false;
		}
	}

    public function safeDown()
    {
		try {
			$this->dropColumn('user', 'image');
		} catch (Exception $exc) {
			return false;
		}
    }
    
}
