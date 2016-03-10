<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $profile app\models\UserProfile */

$this->title = $model->id;
?>
<div class="user-view">

    <h1>Профиль пользователя: <?= Html::encode($this->title) ?></h1>
    <p>
        <? if (\Yii::$app->user->can('updateUserProfile') || \Yii::$app->user->can('updateOwnUserProfile',['user_id'=>$model->id])): ?>
        <?= Html::a('Update', ['profile', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <? endif ?>
    </p>
    <dl class="details">
        <dt>Аватар пользователя:</dt><dd><?= ($profile->avatar ? Html::img('/image/upload/user/avatar/'.$profile->avatar) : null)?></dd>
        <dt>Псевдоним пользователя:</dt><dd><?= $model->created_at ?></dd>
        <dt>Имя пользователя:</dt><dd><?= $profile->first_name ?></dd>
        <dt>Фамилия пользователя:</dt><dd><?= $profile->second_name ?></dd>
        <dt>Очество пользователя:</dt><dd><?= $profile->middle_name ?></dd>
        <dt>Пол пользователя:</dt><dd><?= $profile->gender ?></dd>
        <dt>Дата рождения пользователя:</dt><dd><?= $profile->birthday ?></dd>
        <dt>Зарегистрирован пользователь:</dt> <dd><?= $model->created_at ?></dd>
    </dl>

</div>
