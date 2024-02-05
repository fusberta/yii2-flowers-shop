<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
$this->registerCssFile('@web/css/site.css');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <title>
        <?= Html::encode($this->title) ?>
    </title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header id="header">
        <?php
        if (Yii::$app->user->isGuest) {
            $items = [
                ['label' => 'Каталог', 'url' => ['/products/catalog']],
                ['label' => 'О нас', 'url' => ['/site/index']],
                ['label' => 'Где нас найти', 'url' => ['/site/contact']],
                ['label' => 'Регистрация', 'url' => ['/users/create']],
                ['label' => 'Вход', 'url' => ['/site/login']]
            ];
        } else {
            Yii::$app->user->identity->isAdmin == 1 ?
                ($items = [
                    [
                        'label' => 'Панель администратора',
                        'url' => ['/admin/index']
                    ],
                ])
                :
                ($items = [
                    ['label' => 'Каталог', 'url' => ['/products/catalog']],
                    ['label' => 'О нас', 'url' => ['/site/index']],
                    ['label' => 'Где нас найти', 'url' => ['/site/contact']],
                    ['label' => 'Корзина', 'url' => ['/cart/index']],
                    ['label' => 'Личный кабинет', 'url' => ['/users/view', 'user_id' => Yii::$app->user->identity->id]],
                ]);
            array_push($items, '<li class="nav-item">'
                . Html::beginForm(['/site/logout'])
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->email . ')',
                    ['class' => 'nav-link btn btn-link logout']
                )
                . Html::endForm()
                . '</li>');
        }
        NavBar::begin([
            'brandImage' => 'https://up-diveev.xn--80ahdri7a.site/img/flower.png',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-expand-md navbar-dark bg-dark fixed-top'
            ]
        ]);
        echo
            Nav::widget([
                'options' => ['class' => 'navbar-nav flex items-center'],
                'items' => $items
            ]);
        NavBar::end();
        ?>
    </header>

    <main id="main" class="flex-shrink-0" role="main">
        <div class="container">
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
            <?php endif ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                </div>
                <div class="modal-footer">
                    <button type="button" id="modalButton" class="btn btn-primary">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <footer id="footer" class="mt-auto py-3 bg-light">
        <div class="container">
            <div class="row text-muted">
                <div class="col-md-6 text-center text-md-start">&copy; My Company
                    <?= date('Y') ?>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <?= Yii::powered() ?>
                </div>
            </div>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>