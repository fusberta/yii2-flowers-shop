<?php

use app\models\Cart;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\CartSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Корзина';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css');
?>
<div class="cart-index">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
        $cartItems = Cart::find()->where(['user_id' => \Yii::$app->user->id])->all();
    ?>

    <?php if (!empty($cartItems)): ?>
        <?php if (Yii::$app->user->isGuest): ?>

        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center align-middle">Наименование</th>
                        <th class="text-center align-middle">Количество</th>
                        <th class="text-center align-middle">Сумма</th>
                        <th class="text-center align-middle">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $cartItem): ?>
                        <tr>
                            <td class="text-center align-middle">
                                <?= $cartItem->product->name ?>
                            </td>
                            <td class="text-center align-middle">
                                <?= $cartItem->quantity ?>
                            </td>
                            <td class="text-center align-middle">
                                <?= $cartItem->quantity * $cartItem->product->price ?>
                            </td>
                            <td class="text-center align-middle">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-success"
                                        onclick="updateCartItem(<?= $cartItem->cart_id ?>, 1)">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-dark"
                                        onclick="updateCartItem(<?= $cartItem->cart_id ?>, -1)">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger"
                                        onclick="deleteCartItem(<?= $cartItem->cart_id ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>
    <?php else: ?>
        <p>Добавленные товары будут отображаться здесь</p>
    <?php endif; ?>

    <?php if (!empty($cartItems)): ?>
        <div class="d-flex flex-row flex-wrap justify-content-end align-items-end">
            <a class="btn btn-primary" onclick="showCheckout()">Оформить заказ</a>
        </div>
    <?php endif; ?>

</div>
<script>
    function deleteCartItem(cartItemId) {
        fetch('/cart/delete?cart_id=' + cartItemId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    console.error('Error deleting item:', data.error);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
    }

    function updateCartItem(cartItemId, quantityChange) {
        fetch('/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: cartItemId,
                quantityChange: quantityChange,
            }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success || data.error === 'Item deleted') {
                    location.reload();
                } else if (data.error === 'not enough stock') {
                    alert('На складе недостаточно товара');
                } else {
                    console.error('Error updating item:', data.error);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
    }

    function showCheckout() {
        let title = document.getElementById('staticBackdropLabel');
        let body = document.getElementById('modalBody');
        let btn = document.getElementById('modalButton')
        title.innerText = 'Подтверждение заказа';
        btn.innerText = 'Подтвердить';
        body.innerHTML = '<p>Чтобы перейти к оформлению заказа введите пароль</p><input type="password" id="password" class="form-control" placeholder="Password" aria-label="Password"><div style="color:red; font-size: 14px; margin-top:8px" id="text-error"></div>';
        let myModal = new bootstrap.Modal(document.getElementById("staticBackdrop"), {});
        myModal.show();
        btn.onclick = function () {
            let password = document.getElementById('password').value;
            if (password == '') {
                alert('Введите пароль');
                return;
            }
            fetch('/users/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    password
                }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetch('/orders/create', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    location.replace('/users/view?user_id=' + data.user_id);
                                } else {
                                    let textError = document.getElementById('text-error');
                                    textError.innerText = data.error;
                                }
                            })
                            .catch(error => {
                                console.error('Fetch error:', error);
                            });
                    } else {
                        let textError = document.getElementById('text-error');
                        textError.innerText = data.error;
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                });
        }
    }
</script>