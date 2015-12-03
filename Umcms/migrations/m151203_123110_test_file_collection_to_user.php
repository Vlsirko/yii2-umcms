<?php

use yii\db\Schema;
use yii\db\Migration;

class m151203_123110_test_file_collection_to_user extends Migration
{
    public function up()
    {
	$this->addColumn('user', 'file_collection_id', 'INT(11) NOT NULL');
    }

    public function down()
    {
        echo "m151203_123110_test_file_collection_to_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
