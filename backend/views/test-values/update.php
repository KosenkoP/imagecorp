<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\database\TestValues */

$this->title = 'Update Test Values: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Test Values', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="test-values-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>