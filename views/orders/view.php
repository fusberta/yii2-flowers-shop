<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Orders $model */

$this->title = 'Заказ ' . $model->order_id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="orders-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'order_id' => $model->order_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'order_id' => $model->order_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'order_id',
            [
                'attribute' => 'Пользователь',
                'value' => function ($model) {
                    return $model->user->username;
                },
            ],
            [
                'attribute' => 'Продукт',
                'value' => function ($model) {
                    return $model->product->name;
                },
            ],
            'quantity',
            [
                'attribute' => 'Сумма',
                'value' => function ($model) {
                    return $model->product->price * $model->quantity;
                },
            ],
            'order_date',
            'feedback',
            'status',
        ],
    ]) ?>

</div>
