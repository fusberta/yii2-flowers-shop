<?php

use app\models\Orders;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\OrdersSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-index">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <p>
        <?= Html::a('Создать заказ', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Orders $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'order_id' => $model->order_id]);
                }
            ],
        ],
    ]); ?>


</div>