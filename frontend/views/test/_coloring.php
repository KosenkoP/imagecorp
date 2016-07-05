<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;

    /* @var $this yii\web\View */
    /* @var $model */
    /* @var $currentQuestion */
    /* $var $questionsQuantity */
?>

<!-- Main Content -->
	<div class="container">
		<section class="hero">
                    <div class="hero-content">
                            <h1 class="title"><?= $model->getAttribute('title')?></h1>
                            <div class="desc"><?= $model->getAttribute('subtitle')?></div>
                            <div class="breadcrumbs is-text-centered">
                             <?php 
                                    for($i=1; $i<=$questionsQuantity; $i++){
                                       echo "<a href='" . Url::toRoute(['test/test', 'number' => $i]) . "' class='button brd " . (($i<=$currentQuestion)?"":"is-disabled") . "'>" . $i . "</a>";        
                                    }
                              ?>
                             </div>
			</div>
		</section>
            <?php $form = ActiveForm::begin(['action' => ['test/test/*?number=' . ($currentQuestion+1)],'options' => ['method' => 'post']]); ?>

		<section class="section coloring colr-4">
                    <?php
                       $cnt = count($model->answers);

                       if($cnt): 
                    ?>
			<div class="columns is-mobile is-multiline">
				<div class="column is-half">
					<div class="cropper is-pulled-right">
						<div class="image-editor">
							<div class="cropit-preview"></div>
							<div class="tools-box">
								<span class="minus"></span>
								<input type="range" class="cropit-image-zoom-input">
								<span class="plus"></span>
								<input type="file" name="file" class="cropit-image-input">
								<label for="file" class="cam is-pulled-right">
									<img src="<?= (Yii::$app->controller->route == 'site/index')?>/theme/img/ic/camera.png" alt="">
								</label>
							</div>
						</div>
					</div>

				</div>
				<div class="column is-2 is-offset-1 is-third-tablet is-half-mobile is-quarter-desktop">
                                    <?php foreach ($model->answers as $key => $answer): ?>
					<div class="box is-block">
                                            <div class="is-block">
                                                <?=  $answer->mainImage ?  '<img src="' . $answer->mainImage->getAttribute('src') . '">':"" ?> 
                                            </div>
                                            <div class="is-block">
                                                <div class="is-text-centered">
                                                    <?= $answer->getAttribute('buttton_text')?>
                                                    <input type="radio" id="r<?= $key ?>" name="answer" value="<?=$answer->getAttribute('value')?>">
                                                    <label for="r<?= $key ?>"><span></span></label>
                                                </div>
                                            </div>
					</div>
                                    <?php endforeach;  ?>
				</div>
			</div>
                    <?php else:?>
                       <input type="hidden" name="answer" value="0">
                        <?php endif; ?>
		</section>
		<section class="section is-text-centered">
                     <button type="submit" class="button primary">Далее</button>
		</section>
                <?php ActiveForm::end(); ?>
	</div>
	<!-- /Main Content -->
	<footer>
		<div class="container"></div>
	</footer>
	<!-- /End -->