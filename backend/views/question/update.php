<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\database\Question */
/* @var $id */
/* @var $all_types */
/* @var $answers_model */

$this->title = 'Update Question: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Questions', 'url' => ['test', 'id' => $model->getAttribute('test_id')]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="question-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
        'id' => $id,
        'all_types'=> $all_types,
        'answers_model' => $answers_model,
    ]) ?>
</div>
