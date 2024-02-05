<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Carousel;

$this->title = 'My Yii Application';
$this->registerCssFile('https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css');
?>
<div class="site-index">

    <main class="container px-36 py-12">
        <div class="italic text-center font-bold text-5xl text-green-700">
            <h1>Мир цветов - мир любви</h1>
        </div>
        <div class="flex flex-col items-center justify-center py-8">
            <h2 class="text-center font-semibold text-4xl text-red-800 pb-2">
                Наши новинки
            </h2>
            <div class="flex items-center justify-center rounded-xl border-2 border-red-800 overflow-hidden w-2/3 bg-red-800">
                <?= Carousel::widget([
                    'items' => array_map(function ($product) {
                                        return [
                                            'content' => '<img src="' . $product->image. '">',
                                            'caption' => '<h3 class="text-center font-bold text-3xl">' . $product->name . '</h3>',
                                        ];
                                    }, $products),
                ]);
                ?>
            </div>
        </div>
    </main>
</div>