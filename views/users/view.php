<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Users $model */

$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css');

?>
<div class="users-view">

    <div class="container">

        <h1>
            <?= $this->title ?>
        </h1>

        <div style="width: 100%;">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'username',
                    'email',
                    'first_name',
                    'last_name',
                    'address',
                ],
            ]) ?>
        </div>
        <div class="d-flex justify-content-end">
            <a class="btn btn-primary" href="/users/update?user_id=<?= $model->user_id ?>">Изменить данные</a>
        </div>

        <?php $orders = $model->orders; ?>

        <?php if (!empty($orders)): ?>
        <div style="width: 100%;">
            <h3>Заказы</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Наименование</th>
                            <th>Количество</th>
                            <th>Сумма</th>
                            <th>Дата</th>
                            <th>Сообщение продавца</th>
                            <th>Статус</th>
                            <th class="text-center align-middle">Управление</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        usort($orders, function ($a, $b) {
                            return strtotime($b->order_date) - strtotime($a->order_date);
                        });
                        foreach ($orders as $order): ?>
                            <tr>
                                <td>
                                    <a href="/products/view?id=<?= $order->product_id ?>">
                                        <?= $order->product->name ?>
                                    </a>
                                </td>
                                <td>
                                    <?= $order->quantity ?>
                                </td>
                                <td>
                                    <?= $order->product->price * $order->quantity ?>
                                </td>
                                <td>
                                    <?= $order->order_date ?>
                                </td>
                                <td>
                                    <?= $order->feedback ?>
                                </td>
                                <td>
                                    <?= $order->status ?>
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" <?= $order->status == 'New' ? null : 'disabled' ?> class="btn btn-outline-danger"
                                        onclick="deleteOrder(<?= $order->order_id ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>

</div>

<script>
    function deleteOrder(id) {
        let title = document.getElementById('staticBackdropLabel');
        let body = document.getElementById('modalBody');
        let btn = document.getElementById('modalButton')
        title.innerText = 'Удаление заказа';
        btn.innerText = 'Подтвердить';
        body.innerHTML = '<p>Чтобы удалить заказ введите пароль</p><input type="password" id="password" class="form-control" placeholder="Password" aria-label="Password"><div style="color:red; font-size: 14px; margin-top:8px" id="text-error"></div>';
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
                        fetch('/orders/delete?id=' + id, {
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