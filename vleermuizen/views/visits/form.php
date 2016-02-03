<?php
use app\models\Visits;
use app\components\View;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use app\models\Projects;
/* @var $form ActiveForm */
/* @var $model Observations */
?>
<h1><?= Yii::t('app', 'Waarneming') . " " . (($model->isNewRecord) ? Yii::t('app', 'toevoegen') : Yii::t('app', 'bewerken')) ?></h1>
<div class="visit-form">
	<?php if($projects): ?>
	    <?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>
	    	<?= $form->field($model, 'project_id')->dropDownList(["" => Yii::t('app', 'Selecteer een project')] + ArrayHelper::map($projects, 'id', 'name')) ?>
			<?= $form->field($model, 'date')->widget(DatePicker::className(), [
				'type' => 1,
				'options' => ['placeholder' => Yii::t('app', 'Selecteer datum')],
				'pluginOptions' => [
					'format' 			=> 'dd-mm-yyyy',
					'todayHighlight' 	=> true,
					'autoclose' 		=> true,
					'weekStart'			=> 1,
					'endDate'			=> '0d'
				]
			]) ?>
			<?= $form->field($model, 'box_open')->radioList([
					1 => Yii::t('app', 'ja'),
					0 => Yii::t('app', 'nee'),
			]) ?>
			<?= $form->field($model, 'cleaned')->radioList([
					1 => Yii::t('app', 'ja'),
					0 => Yii::t('app', 'nee'),
			]) ?>
			<?php if($model->isNewRecord): ?>
				<?= $form->field($model, 'checked_all')->radioList([
						1 => Yii::t('app', 'ja'),
						0 => Yii::t('app', 'nee'),
				]) ?>
				<div class="form-group field-visits-checked-boxes hidden-js <?php if($model->hasErrors('checked_boxes')): ?>has-error<?php endif; ?>">
					<label class="control-label"><?= Yii::t('app', 'Gecheckte kasten')?></label>
					<div class="boxList"></div>
					<?php if($model->hasErrors('checked_boxes')): ?><div class="help-block"><?= $model->getErrors('checked_boxes')[0] ?></div><?php endif; ?>
				</div>
			<?php endif; ?>
			<?= $form->field($model, 'count_completeness')->dropdownList(Visits::getCountCompletenessOptions()) ?>
			<?= $form->field($model, 'blur')->dropDownList(Projects::getBlurOptions()) ?>
			<?= $form->field($model, 'embargo')->widget(DatePicker::className(), [
				'type' => 1,
				'options' => [
					'placeholder' 			=> Yii::t('app', 'Selecteer datum')
				],
				'pluginOptions' => [
					'format' 			=> 'dd-mm-yyyy',
					'todayHighlight' 	=> true,
					'autoclose' 		=> true,
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
	    	<div class="form-group">
	            <?= Html::submitButton(Yii::t('app', 'Opslaan'), ['class' => 'btn btn-primary pull-right']) ?>
	        </div>
	    <?php ActiveForm::end(); ?>
	    <?php View::beginJs(); ?>
			<script>
				/* If visits-checked-all equals FALSE then fetch boxes */
				if($('input[name="Visits[checked_all]"]:checked').val() == 0)
					fetchBoxList();
				
				$(document).on('change', 'input[name="Visits[checked_all]"]', function() {
					if($('input[name="Visits[checked_all]"]:checked').val() == 0)
						fetchBoxList();
					else
						clearBoxList();
				});
		
				$(document).on('change', '#visits-project_id', function() {
					if($('input[name="Visits[checked_all]"]:checked').val() == 0)
						fetchBoxList();
					else
						clearBoxList();
				});
				
				function fetchBoxList() {
					HoldOn.open();
					$.ajax({
						url: 	'/visits/ajax-get-boxes',
						type: 	'json',
						method: 'get',
						data:	{ 'pid': $('#visits-project_id').val(), 'vid': '<?= $model->id ?>' }
					}).done(function(result){
						console.log(result);
						var htmlList = [];
						jQuery.each(result, function(index, value) {
							tmpString = '<div>' +
								'<label><input type="checkbox" name="Visits[checked_boxes][]" value="' + value.id + '"> ' + value.code + '</label>' +
							'</div>';
							htmlList.push(tmpString);
						});
						$('.boxList').html(htmlList.join(""))
						$('.field-visits-checked-boxes').show();
						HoldOn.close();
					});
				}
		
				function clearBoxList() {
					$('.field-visits-checked-boxes').hide();
					$('.boxList').html();
				}
			</script>
		<?php View::endJs(); ?>
	<?php else: ?>
		<p><?= Yii::t('app', "U heeft nog geen beschikbare projecten of projecten zonder kast.<br>Klik <a href='".Url::toRoute('boxes/form')."'>hier</a> om een nieuwe kast aan te maken of <a href='".Url::toRoute('projects/form')."'>hier</a> om een project aan te maken.")?></p>
	<?php endif; ?>
</div>