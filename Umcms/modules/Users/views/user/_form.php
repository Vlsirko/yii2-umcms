<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use Umcms\models\User;
use Rbac\models\Permitions\Role;
use Uploads\widgets\Kcfinder;
use zxbodya\yii2\galleryManager\GalleryManager;

/* @var $this yii\web\View */
/* @var $model Umcms\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'username')->textInput() ?>
	<?= $form->field($model, 'email')->textInput() ?>
	
	<?php
	
		if(\yii::$app->getUser()->can("rbac/roles/update")){
			echo $form->field($model, 'role')->dropDownList(Role::getAllRolesForDropdown());
			
		}
		
		if(\yii::$app->getUser()->can("users/usercontroller/update")){
			echo $form->field($model, 'status')->dropDownList(User::getUserStatusList());
		}
		
	?>
	<?= $form->field($model, 'password')->passwordInput() ?>

	<?= $form->field($model, 'image')->widget(Kcfinder::className(), ['value' => (string) $model->image]) ?>

	<?php
		if ($model->isNewRecord) {
			echo 'Can not upload images for new record';
		} else {
			echo GalleryManager::widget(
				[
					'model' => $model,
					'behaviorName' => 'galleryBehavior',
					'apiRoute' => '/site/gallery'
				]
			);
		}
	?>
    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

	<?php ActiveForm::end(); ?>

</div>
