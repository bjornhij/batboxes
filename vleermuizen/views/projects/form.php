<?php
use app\models\Projects;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\components\View;
/* @var $form ActiveForm */
/* @var $this yii\web\View */
/* @var $model \app\models\Projects */
?>
<div class="project-form">
	<h1><?= Yii::t('app', 'Project') ?> <?= ($model->isNewRecord) ? Yii::t('app', 'toevoegen') : Yii::t('app', 'bewerken') ?></h1>
    <?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>
        <?= $form->field($model, 'name', ['inputOptions' => ['maxlength' => 45, 'class' => 'form-control']]) ?>
        <?php if(Yii::$app->user->getIdentity()->hasRole('administrator')): ?>
        	<?= $form->field($model, 'owner_id')->dropDownList(ArrayHelper::map($users, 'id', 'username')) ?>
        <?php endif; ?>
        <div class="text-advanced" data-slide-toggle="advanced"><span>Geavanceerde opties</span></div>
        <div class="advanced <?= ($model->isNewRecord && !$model->hasErrors()) ? 'hidden-js' : '' ?>">
			<?= $form->field($model, 'main_observer_id')->dropDownList(ArrayHelper::map($users, 'id', 'username')) ?>  	        	
	        <?= $form->field($model, 'counters')->widget(Select2::className(), [
			    'data' 		=> ArrayHelper::map($users, 'id', 'username'),
			    'options' 	=> [
			        'prompt' 	=> Yii::t('app', 'Selecteer tellers..'),
			    	'multiple' 	=> true
			    ],
			]); ?>
			<?= $form->field($model, 'blur')->dropDownList(Projects::getBlurOptions()) ?>
		    <?= $form->field($model, 'embargo')->widget(DatePicker::className(), [
				'type' 			=> 1,
				'options' 		=> ['placeholder' => Yii::t('app', 'Selecteer datum')],
				'pluginOptions' => [
					'format' 			=> 'dd-mm-yyyy',
					'todayHighlight' 	=> true,
					'autoclose'			=> true,
					'weekStart'			=> 1,
				]
			]) ?>
		    <?= $form->field($model, 'remarks')->widget(\yii\redactor\widgets\Redactor::className(), [
	    		'clientOptions' => [
	    			'buttons' => [
	    				'formatting', 'bold', 'italic',
						'unorderedlist', 'orderedlist', 'outdent', 'indent',
						'link', 'alignment', 'horizontalrule'
	    			]
	    		]
	    	]) ?>
    	</div>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Opslaan'), ['class' => 'btn btn-primary pull-right']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>

<?= View::beginJs() ?>
<script>
	/* CSS fix */
	$('.select2-search__field').attr('style', 'width: 100%;');
</script>
<?= View::endJs() ?>