<?php


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use mihaildev\ckeditor\CKEditor;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'post_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'post_short_text')->widget(CKEditor::className(),[
        'editorOptions' => [
            'preset' => 'basic',
            'inline' => false,
            'height' => 150,
            'insert' => false,
            'removeButtons' => 'Flash,About,Image,PageBreak,Iframe,SpecialChar,Smiley',
        ],
    ]);
    ?>

    <?= $form->field($model, 'post_text')->widget(CKEditor::className(),[
        'editorOptions' => [
            'preset' => 'basic', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
            'inline' => false, //по умолчанию false
            'height' => 300,
            'insert' => false,
            'removeButtons' => 'Flash,About,Image,PageBreak,Iframe,SpecialChar,Smiley',
            ],
    ]);
    ?>
    <?= $form->field($model, 'post_img')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*', 'multiple'=>true],
       // 'id' => 'post',
        'pluginOptions' => [
            'showRemove' => true,
            'showUpload' => false,
            'allowedFileExtensions' => ['jpg', 'jpeg', 'png'],
            'initialPreview'=>[
                ($model->post_img ? Html::img('/image/upload/post/'.$model->post_img) : null),
            ],
        ],
        'pluginEvents' => [
            'fileclear' => "function() { $('#post-hidden_img').val(null); }",
        ],
    ]);  ?>
    <?= $form->field($model, 'hidden_img')->hiddenInput(['value' => empty($model->post_img) ? null : $model->post_img])->label(false) ?>
    <br>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
