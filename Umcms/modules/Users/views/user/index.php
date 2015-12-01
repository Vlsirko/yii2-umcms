<?php

use yii\helpers\Html;
use yii\grid\GridView;
use Umcms\models\User;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'email',
			
			'status' => [	
				'label' => 'Статус',
				'value' => function($data){
					return $data->getStatusForView();
				}
			],
			'role' => [
				'label' => 'Группа',
				'value' => function($data){
					return $data->getRoleForView();
				}
			],
            // 'email:email',
            // 'status',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
