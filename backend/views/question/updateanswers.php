<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $answers_models */
/* @var $id */
/* @var $template */

$this->title = 'Update Answers';

$this->params['breadcrumbs'][] = ['label' => 'Questions', 'url' => ['test', 'id' => $id]];
$this->params['breadcrumbs'][] = 'Update Answers';
?>

<div class="question-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php 
        echo  $this->render($template, [
            'answers_models' => $answers_models,
            'id' => $id,
            'isNew'=>false,
        ]); 
   ?> 
</div>