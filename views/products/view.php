<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Products $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['catalog']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="products-view">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <div style="display: flex; justify-items: center;">
        <?= Html::img($model->image, ['alt' => 'Product Image', 'style' => 'max-width: 100%;']) ?>
    </div>



    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'product_id',
            'name',
            'description:ntext',
            'price',
            'stock_quantity',
            [
                'attribute' => 'category_id',
                'label' => 'Category',
                'value' => function ($model) {
                    return $model->category->name;
                },
            ],
        ],
    ]) ?>

    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin): ?>
        <p>
            <?= Html::a('Update', ['update', 'product_id' => $model->product_id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'product_id' => $model->product_id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php endif; ?>


</div>