<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\ActiveForm;
use app\assets\AppAsset;
use yii\helpers\Url;
use app\widgets\AlertWidget;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>

<div class="wrap">

    <?php
    NavBar::begin(
        [
            'options' => [
                'class' => 'navbar navbar-default',
                'id' => 'main-menu'
            ],
            'renderInnerContainer' => true,
            'innerContainerOptions' => [
                'class' => 'container'
            ],
            'brandLabel' => 'Постер',
            'brandUrl' => [
                '/'
            ],
            'brandOptions' => [
                'class' => 'navbar-brand'
            ]
        ]
    );

    if (!Yii::$app->user->isGuest):
        ?>

        <div class="navbar-form navbar-right">
            <button class="btn btn-sm btn-default"
                    data-container="body"
                    data-toggle="popover"
                    data-trigger="focus"
                    data-placement="bottom"
                    data-title="<?=Yii::$app->user->identity['username'] ?>"
                    data-content="
                            <a href='<?= Url::to(['/user/profile']) ?>' data-method='post'>Мой профиль</a><br>
                            <a href='<?= Url::to(['/user/logout']) ?>' data-method='post'>Выход</a>
                        ">
                <span class="glyphicon glyphicon-user"></span>
            </button>
        </div>
        <? echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Добавить новость', 'url' => ['/post/create']],
        ],
    ]);?>
    <?php
    endif;
    $menuItems = [];
    if (Yii::$app->user->isGuest):
        $menuItems[] = [
            'label' => 'Регистрация',
            'url' => ['/user/sign-up']
        ];
        $menuItems[] = [
            'label' => 'Войти',
            'url' => ['/user/login']
        ];
    endif;
    echo Nav::widget([
        'items' => $menuItems,
        'activateParents' => true,
        'encodeLabels' => false,
        'options' => [
            'class' => 'navbar-nav navbar-right'
        ]
    ]);
    NavBar::end();
    ?>
    <div class="container">
        <?= AlertWidget::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
            <span class="badge">
                <span class="glyphicon glyphicon-copyright-mark"></span> Постер<?= date('Y') ?>
            </span>
    </div>
</footer>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage() ?>
