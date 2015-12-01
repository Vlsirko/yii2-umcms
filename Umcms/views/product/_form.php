<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use iutbay\yii2kcfinder\KCFinderInputWidget;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	<?= KCFinderInputWidget::widget(['name' => 'image'])?>
    <?php ActiveForm::end(); ?>

</div>
