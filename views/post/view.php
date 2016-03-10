<?php

use yii\helpers\Html;
use rmrevin\yii\module\Comments;
use kartik\rating\StarRating;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\Post */

$this->title = $model->post_name;
?>
<div class="post-view">

    <p>
        <? if (\Yii::$app->user->can('updatePost') || \Yii::$app->user->can('updateOwnPost',['user_id'=>$model->user_id])): ?>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <? endif ?>
        <? if (\Yii::$app->user->can('deletePost')): ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <? endif ?>
    </p>


</div>
<div class="row">
    <div class="col-sm-9">
        <div class="row">
            <div class="col-sm-12">
                <h1><?= Html::encode($model->post_name) ?></h1>
                <?
                echo StarRating::widget([
                    'id' =>'rating_id',
                    'name' => 'rates',
                    'value' => $model->rates,

                    'pluginOptions' => [
                        'size'=>'xs',
                        'step' => 0.1,
                        'max' => 5,
                        'min' => 0,
                        'disabled'=> \app\models\Rating::find()->where(['post_id'=>$model->id,'ip'=>Yii::$app->request->userIP])->one() ? true : false,
                        'showClear'=>false,
                    ]
                ]);
                ?>
                <input hidden="hidden" id="post_id" value="<?=$model->id;?>">
        <span class="pull-right">

        </span>
            </div>
            <div class="col-sm-12">
                <? if($model->post_img):?>
             <img src="/image/upload/post/<?= $model->post_img ?>">
                <? endif ?>
                <?= $model->post_text ?>
            </div>
            <div class="col-sm-12">
                <span class="pull-right">
                    <i class="fa fa-tags">
                    </i>
                   Автор: <a href="/user/view?id=<?=$model->user_id ?>">
                        <?= $model->user->username ?>
                    </a>
                    |
                     Дата созд.:<i class="fa fa-calendar"></i> <?= $model->created_at ?>
                </span>
            </div>
            <div class="col-sm-12">
                <hr>
                <?
                echo Comments\widgets\CommentListWidget::widget([
                    'entity' => (string) $model->id,
                    'theme' => 'comment',
                ]);
                ?>
            </div>
        </div>
    </div>
</div>