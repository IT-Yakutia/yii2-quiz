<?php

use yii\helpers\Html;

$this->title = 'Новый вопрос';

?>
<div class="quiz-question-update">
    <div class="row">
        <div class="col s12">
		    <?= $this->render('_form', [
		        'model' => $model,
		        'quiz' => $quiz,
		    ]) ?>
		</div>
	</div>
</div>
