<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <h2 style="color: #0044cc">
           Тестовое задание!!!
        </h2>
    <hr>
    </p>
    <? foreach($model as $model):?>
    <div class="row">
        <div class="col-sm-10">
            <h1>
                <a href="/post/view?id=<?=$model->id ?>">
                    <?= Html::encode($model->post_name) ?>
                </a>
            </h1>
        </div>
        <div class="col-sm-10">
            <p>
                <?= $model->post_text ?>
            &nbsp;&nbsp;
            <p class="text-right">
                <a href="/user/view?id=<?=$model->user_id ?>">
                    Автор: <?=$model->user->username ?> |
                </a>
                <a href="/post/view?id=<?=$model->id ?>">
                    <?=$model->count_comment ?> Comments |
                </a>

                <i class="fa fa-calendar">
                </i>
                <?=$model->created_at ?>
            </p>
            <hr>
        </div>
    </div>
    <? endforeach ?>
    <div class="pagination center">
        <?
        echo LinkPager::widget([
            'pagination' => $pages,
        ]);
        ?>
    </div>
</div>
