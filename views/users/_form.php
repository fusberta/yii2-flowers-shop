<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap5\ActiveForm;
use yii\widgets\Pjax;


/** @var yii\web\View $this */
/** @var app\models\Users $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="users-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email', [
        'enableAjaxValidation' =>
            true
    ])->textInput(['maxlength' => true], ) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'confirm_password')->passwordInput() ?>

    <?= $form->field($model, 'agree')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>