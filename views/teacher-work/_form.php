<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TeacherWork */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="teacher-work-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('发布', ['btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>