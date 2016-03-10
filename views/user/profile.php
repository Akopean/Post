<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\UserProfile */
/* @var $form ActiveForm */

$this->title = 'Профиль: '.$model->user->username;
?>
<div class="user-profile">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>



        <?= $form->field($model, 'first_name') ?>
        <?= $form->field($model, 'second_name') ?>
        <?= $form->field($model, 'middle_name') ?>
        <?= $form->field($model, 'gender')->radioList([0 => 'М',1 => 'Ж'], $options = ['М']) ?>
        <?= $form->field($model, 'birthday')->widget(
        DatePicker::className(), [
        'clientOptions' => [
            'autoclose' => true,
            'language' => 'ru',
            'format' => 'yyyy-mm-dd'
        ]
    ]);?>
    <?= $form->field($model, 'avatar')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*', 'multiple'=>true],
        'pluginOptions' => [
            'showRemove' => true,
            'showUpload' => false,
            'allowedFileExtensions' => ['jpg', 'jpeg', 'png'],
            'initialPreview'=>[
                ($model->avatar ? Html::img('/image/upload/user/avatar/'.$model->avatar) : null),
            ],
        ],
        'pluginEvents' => [
            'fileclear' => "function() { $('#userprofile-hidden_img').val(null); }",
        ],
    ]);  ?>
    <?= $form->field($model, 'hidden_img')->hiddenInput(['value' => empty($model->avatar) ? null : $model->avatar])->label(false) ?>
    
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- user-profile -->
