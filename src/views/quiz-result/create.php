<?php

use yii\helpers\Html;

$this->title = 'Новый ответ';

?>
<div class="quiz-result-create">
    <div class="row">
        <div class="col s12">
		    <?= $this->render('_form', [
		        'model' => $model,
		    ]) ?>
		</div>
	</div>
</div>
