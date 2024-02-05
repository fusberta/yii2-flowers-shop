<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Orders $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="orders-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model->user, 'username')->textInput() ?>

    <?= $form->field($model->product, 'name')->textInput() ?>

    <?= $form->field($model, 'quantity')->textInput() ?>

    <?= $form->field($model->product, 'price')->textInput() ?>
    
    <?= $form->field($model, 'order_date')->textInput() ?>
    
    <?= $form->field($model, 'feedback')->textarea(['rows' => 3]) ?>
    
    <?= $form->field($model, 'status')->dropDownList([ 'New' => 'New', 'Confirmed' => 'Confirmed', 'Cancelled' => 'Cancelled', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
