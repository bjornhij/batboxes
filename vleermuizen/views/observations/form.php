<?php
use app\models\Observations;
use app\models\Species;
use app\models\Visits;
use app\components\View;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Html;
$observationModel = new Observations();
$visitModel = new Visits();
/* @var $model \app\models\Observations */
?>
<h1><?= Yii::t('app', 'Waarneming toevoegen') ?></h1>

<div class="observation-form">
	<table class="table table-striped">
		<tr>
			<td width="50%"><?= $visitModel->getAttributeLabel('project') ?></td>
			<td><?= Html::encode($model->visit->project->name) ?></td>
		</tr>
		<tr>
			<td><?= $visitModel->getAttributeLabel('date') ?></td>
			<td><?= Html::encode($model->visit->date) ?></td>
		</tr>
		<?php if($model->visit->observer_id == Yii::$app->user->getId() || (is_object(Yii::$app->user->getIdentity()) && Yii::$app->user->getIdentity()->hasRole( 'administrator'))) : ?>
			<?php if($model->visit->blur) : ?>
				<tr>
					<td><?= $model->visit->getAttributeLabel('blur') ?></td>
					<td><?= $model->visit->getBlur() ?></td>
				</tr>
			<?php endif; ?>
			<?php if($model->visit->embargo) : ?>
				<tr>
					<td><?= $visitModel->getAttributeLabel('embargo') ?></td>
					<td><?= Html::encode($model->visit->embargo) ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>
	</table>
    <?php $form = ActiveForm::begin(['enableClientValidation' => false, 'options' => ['enctype' => 'multipart/form-data']]); ?>
    	<?= $form->field($model, 'box_id')->dropDownList(Arrayhelper::map($boxes, 'id', 'code')) ?>
    	<?= $form->field($model, 'observation_type')->dropDownList(Observations::getObservationTypes(), ['data-observation-type' => 1]) ?>
    	<div class="observation-form-details">
			<?= $form->field($model, 'taxon_id')->dropDownList(Species::getTaxonomies(true), ['data-taxon-identifier' => 1]) ?>
			<?= $form->field($model, 'species_id')->dropDownList(ArrayHelper::map($species, 'id', 'dutch'), ['data-species-identifier' => 1]) ?>
			<div class="sight-container hidden-js">
				<?= $form->field($model, 'age')->dropDownList(Observations::getAgeOptions()) ?>
				<?= $form->field($model, 'sight_quantity') ?>
			</div>
			<div class="manure-container hidden-js">
				<?= $form->field($model, 'manure_collected')->checkbox(['data-manure-collected' => 1]) ?>
				<?= $form->field($model, 'manure_collection', ['options' => ['data-manure-collection' => 1]])->widget(\yii\redactor\widgets\Redactor::className(), [
		    		'clientOptions' => [
		    			'buttons' => [
		    				'formatting', 'bold', 'italic',
							'unorderedlist', 'orderedlist', 'outdent', 'indent',
							'link', 'alignment', 'horizontalrule'
		    			]
		    		]
		    	])  ?>
				<?= $form->field($model, 'manure_size')->dropDownList(Observations::getManureSizeOptions()) ?>
				<?= $form->field($model, 'manure_quantity')->dropDownList(Observations::getManureQuanityOptions()) ?>
			</div>
			<div class="catch-container hidden-js">
				<?= $form->field($model, 'catch_weight') ?>
				<?= $form->field($model, 'catch_sex')->dropDownList(Observations::getCatchSexOptions(), ['data-catch-sex' => 1]) ?>
				<?= $form->field($model, 'catch_forearm_right') ?>
				<?= $form->field($model, 'catch_forearm_left') ?>
				<?= $form->field($model, 'catch_sexual_status', ['options' => ['data-sexual-status-container' => 1]])->dropDownList(Observations::getCatchSexualStatusOptions(), ['data-sexual-status' => 1]) ?>
				<?php if($parasites): ?>
					<?= $form->field($model, 'catch_parasite_id')->dropDownList(ArrayHelper::map($parasites, 'id', 'dutch')) ?>
					<?= $form->field($model, 'catch_parasite_collected')->checkbox(['data-catch-parasite-collected' => 1]) ?>
					<?= $form->field($model, 'catch_parasite_collection', ['options' => ['data-catch-parasite-collection' => 1]])->widget(\yii\redactor\widgets\Redactor::className(), [
			    		'clientOptions' => [
			    			'buttons' => [
			    				'formatting', 'bold', 'italic',
								'unorderedlist', 'orderedlist', 'outdent', 'indent',
								'link', 'alignment', 'horizontalrule'
			    			]
			    		]
			    	]) ?>
		    	<?php endif; ?>
				<?= $form->field($model, 'catch_ring_code') ?>
				<?= $form->field($model, 'catch_transponder_code') ?>
				<?= $form->field($model, 'catch_radio_transmitter_code') ?>
				<?= $form->field($model, 'catch_dna') ?>
			</div>
			<?php if($model->picture) : ?>
    			<div class="row">
    				<div class="col-xs-4">
    					<?= $form->field($model, 'pictureFile')->fileInput() ?>
    					<?= $form->field($model, 'deleteImage')->checkbox() ?>
    				</div>
    				<div class="col-xs-8 text-right">
    					<img src="/uploads/observations/<?= $model->picture ?>" alt="currentimage"  style="max-width:200px; max-height: 200px;">
    				</div>
    			</div>
    		<?php else: ?>
    			<?= $form->field($model, 'pictureFile')->fileInput() ?>
    		<?php endif; ?>
			<?= $form->field($model, 'remarks')->widget(\yii\redactor\widgets\Redactor::className(), [
		    		'clientOptions' => [
		    			'buttons' => [
		    				'formatting', 'bold', 'italic',
							'unorderedlist', 'orderedlist', 'outdent', 'indent',
							'link', 'alignment', 'horizontalrule'
		    			]
		    		]
		    ]) ?>
			<div class="dead-container hidden-js">
				<?= $form->field($model, 'dead')->checkbox() ?>
			</div>
		</div>
		<div class="form-group">
			<?= Html::submitButton(Yii::t('app', 'Opslaan'), ['class' => 'btn btn-primary pull-right']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>

<?php View::beginJs(); ?>
	<script>
		/* Variables */
		var species 					= <?= json_encode($species) ?>;
		var sexualStatusOptionsMale 	= <?= json_encode(Observations::getCatchSexualStatusOptions(Observations::CATCH_SEX_MALE)) ?>;
		var sexualStatusOptionsFemale 	= <?= json_encode(Observations::getCatchSexualStatusOptions(Observations::CATCH_SEX_FEMALE)) ?>;
		
		/* Species */
		$(document).on('change', '[data-taxon-identifier]', speciesSelector);
		speciesSelector();

		function speciesSelector() {
			HoldOn.open();
			$('[data-species-identifier]').empty();
			jQuery.each(species, function(index, value) {
				if(value.taxon == $('[data-taxon-identifier]').val()) {
					var selected = (<?= ($model->species_id) ? $model->species_id : "0" ?> == value.id) ? 'selected="selected"' : "";
					$('[data-species-identifier]').append('<option value="' +  value.id + '" ' + selected + '>' + value.dutch + '</option>');
				}				
			});			
			HoldOn.close();
		}

		/* Observation types */		
		$(document).on('change', '[data-observation-type]', showObservationTypeFields);
		showObservationTypeFields();

		function showObservationTypeFields() {
			HoldOn.open();
			$('.observation-form-details').hide();
			$('.sight-container').hide();
			$('.manure-container').hide();
			$('.catch-container').hide();
			$('.dead-container').hide();
			switch($('[data-observation-type]').val()) {
				case '<?= Observations::OBSERVATION_TYPE_SIGHT ?>':
					$('.sight-container').show();
					$('.dead-container').show();
					$('.observation-form-details').show();
				break;
				case '<?= Observations::OBSERVATION_TYPE_MANURE ?>':
					$('.manure-container').show();
					$('.observation-form-details').show();
				break;
				case '<?= Observations::OBSERVATION_TYPE_CATCH ?>':
					$('.catch-container').show();
					$('.dead-container').show();
					$('.observation-form-details').show();
				break;
				case '<?= Observations::OBSERVATION_TYPE_NULL ?>':
					/* Keep form hidden */
				break;
			}
			HoldOn.close();
		}

		/* Manure collected */
		$(document).on('change', '[data-manure-collected]', toggleManureCollection);
		toggleManureCollection();

		function toggleManureCollection() {
			if($('[data-manure-collected]').is(':checked')) 
				$('[data-manure-collection]').show();
			else
				$('[data-manure-collection]').hide();
		}

		/* Sexual status */
		$(document).on('change', '[data-catch-sex]', sexualStatusSelector);
		sexualStatusSelector();

		function sexualStatusSelector() {
			HoldOn.open();
			$('[data-sexual-status]').empty();
			switch($('[data-catch-sex]').val()) {
				case '<?= Observations::CATCH_SEX_MALE ?>':
					jQuery.each(sexualStatusOptionsMale, function(index, value){
						$('[data-sexual-status]').append('<option value="' +  index + '">' + value + '</option>');
						$('[data-sexual-status-container]').show();
					});
				break;
				case '<?= Observations::CATCH_SEX_FEMALE ?>':
					jQuery.each(sexualStatusOptionsFemale, function(index, value){
						$('[data-sexual-status]').append('<option value="' +  index + '">' + value + '</option>');
						$('[data-sexual-status-container]').show();
					});
				break;
				case '<?= Observations::CATCH_SEX_UNKNOWN ?>':
					$('[data-sexual-status-container]').hide();
				break;
			}
			HoldOn.close();
		}

		/* Parasite collected */
		$(document).on('change', '[data-catch-parasite-collected]', toggleParasiteCollection);
		toggleParasiteCollection();

		function toggleParasiteCollection() {
			if($('[data-catch-parasite-collected]').is(':checked')) 
				$('[data-catch-parasite-collection]').show();
			else
				$('[data-catch-parasite-collection]').hide();
		}
	</script>
<?php View::endJs(); ?>