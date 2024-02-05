<?php

use app\models\Products;
use yii\bootstrap5\Dropdown;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ProductsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css');
$this->title = 'Продукты';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('@web/css/catalog.css');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css');
// $this->registerJsFile('@web/js/sort-icons.js');
?>
<div class="products-index">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="card mb-6">
        <div class="card-body">
            <h5 class="card-title">Сортировка по</h5>
            <ul class="flex flex-wrap pl-0 mt-3 gap-3" id="sort-list">
                <!-- <a href="<?php if (isset($_GET['sort']) and $_GET['sort'] == 'price') {
                    echo Url::current(['sort' => '-price']);
                } else {
                    echo Url::current(['sort' => 'price']);
                }
                ?>" id="sort-link" class="flex items-center">
                    <li class="sort-item">Цене
                    </li>
                    <?php if (isset($_GET['sort']) and $_GET['sort'] == 'price') {
                        echo '<i class="bi bi-chevron-up ml-1"></i>';
                    } else if (isset($_GET['sort']) and $_GET['sort'] == '-price') {
                        echo '<i class="bi bi-chevron-down ml-1"></i>';
                    } ?>
                </a> -->
                <a href="<?php if (isset($_GET['sort']) and $_GET['sort'] == 'date') {
                    echo Url::current(['sort' => '-date']);
                } else {
                    echo Url::current(['sort' => 'date']);
                }
                ?>" id="sort-link" class="flex items-center">
                    <li class="sort-item">Новизне
                    </li>
                    <?php if (isset($_GET['sort']) and $_GET['sort'] == 'date') {
                        echo '<i class="bi bi-chevron-up ml-1"></i>';
                    } else if (isset($_GET['sort']) and $_GET['sort'] == '-date') {
                        echo '<i class="bi bi-chevron-down ml-1"></i>';
                    } ?>
                </a>
                <a href="<?php if (isset($_GET['sort']) and $_GET['sort'] == 'country') {
                    echo Url::current(['sort' => '-country']);
                } else {
                    echo Url::current(['sort' => 'country']);
                }
                ?>" id="sort-link" class="flex items-center">
                    <li class="sort-item">Стране
                        происхождения</li>
                    <?php if (isset($_GET['sort']) and $_GET['sort'] == 'country') {
                        echo '<i class="bi bi-chevron-up ml-1"></i>';
                    } else if (isset($_GET['sort']) and $_GET['sort'] == '-country') {
                        echo '<i class="bi bi-chevron-down ml-1"></i>';
                    } ?>
                </a>
                <a href="<?php if (isset($_GET['sort']) and $_GET['sort'] == 'name') {
                    echo Url::current(['sort' => '-name']);
                } else {
                    echo Url::current(['sort' => 'name']);
                }
                ?>" id="sort-link" class="flex items-center">
                    <li class="sort-item">
                        Наименованию</li>
                    <?php if (isset($_GET['sort']) and $_GET['sort'] == 'name') {
                        echo '<i class="bi bi-chevron-up ml-1"></i>';
                    } else if (isset($_GET['sort']) and $_GET['sort'] == '-name') {
                        echo '<i class="bi bi-chevron-down ml-1"></i>';
                    } ?>
                </a>
            </ul>
            <div class="dropdown btn btn-primary">
                <a href="#" data-bs-toggle="dropdown" style="text-decoration: none;"
                    class="dropdown-toggle text-white category-filter">Фильтры по категориям<b class="caret"></b></a>
                <?= Dropdown::widget([
                    'items' => [
                        ['label' => 'Все товары', 'url' => '/products/catalog'],
                        ['label' => 'Горшки', 'url' => '/products/catalog?ProductsSearch[category_id]=1'],
                        ['label' => 'Упаковка', 'url' => '/products/catalog?ProductsSearch[category_id]=2'],
                        ['label' => 'Цветы', 'url' => '/products/catalog?ProductsSearch[category_id]=3'],
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
    <?php
    $products = $dataProvider->getModels();
    echo "<div class='d-flex flex-row flex-wrap justify-content-center align-items-end'>";
    foreach ($products as $product) {
        if ($product->stock_quantity > 0) {
            echo "<div class='card m-1' style='width: 20%; min-width: 250px;'>
            <a href='/products/view?id={$product->product_id}'>
                <img src='{$product->image}' class='card-img-top'
                    style='max-height: 250px;' alt='image'>
            </a>
            <div class='card-body'>
                <h5 class='card-title'>{$product->name}</h5>
                <p class='card-text'>{$product->description}</p>
                <p class='text-danger'>{$product->price} руб</p>";
            echo (Yii::$app->user->isGuest ? "<a href='/products/view?id={$product->product_id}' class='btn btn-primary'>Просмотр товара</a>" : "<p onclick='add_product({$product->product_id}, 1)'
                    class='btn btn-primary'>Добавить в корзину</p>");
            echo "
            </div>
        </div>";
        }
    }
    echo "</div>";
    ?>
    <script>
        function add_product(id, items) {
            let form = new FormData();
            form.append('product_id', id);
            form.append('count', items);
            let request_options = { method: 'POST', body: form };
            fetch('https://up-diveev.xn--80ahdri7a.site/cart/create', request_options)
                .then(response => response.text())
                .then(result => {
                    console.log(result)
                    let title = document.getElementById('staticBackdropLabel');
                    let body = document.getElementById('modalBody');
                    let btn = document.getElementById('modalButton');
                    btn.setAttribute('data-bs-dismiss', 'modal')
                    if (result == 'false') {
                        title.innerText = 'Ошибка';
                        body.innerHTML = "<p>Ошибка добавления товара, вероятно, товар уже раскупили</p >"
                    } else {
                        title.innerText = 'Информационное сообщение';
                        body.innerHTML = "<p>Товар успешно добавлен в корзину</p>"
                    }
                    let myModal = new bootstrap.Modal(document.getElementById("staticBackdrop"), {});
                    myModal.show();
                })
        }
    </script>

</div>

