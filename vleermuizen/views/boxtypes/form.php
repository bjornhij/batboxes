<?php
use app\components\View;
use app\models\Boxtypes;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
?>
<h1><?= Yii::t('app', 'Kasttypen')?> <?= ($model->isNewRecord) ? Yii::t('app', 'toevoegen') : Yii::t('app', 'bewerken') ?></h1>
<div class="boxtype-form">
	<?php $form = ActiveForm::begin(['enableAjaxValidation' => true, 'id' => 'boxtypes-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>
		<?= $form->field($model, 'model') ?>
		<?= $form->field($model, 'manufacturer_id')->dropDownList(["" => ""] + ArrayHelper::map($users, 'id', 'username')) ?>
		<?= $form->field($model, 'shape')->dropDownList(["" => ""] + Boxtypes::getBoxShapes()) ?>
		<div id="shape_other_container" <?php if(!$model->shape_other) : ?>class="hidden-js" <?php endif;?>>
			<?= $form->field($model, 'shape_other') ?>
		</div>
		<div id="chamber_count_container">
			<?= $form->field($model, 'chamber_count') ?>
		</div>
		<?= $form->field($model, 'material')->dropDownList(["" => ""] + Boxtypes::getBoxMaterials()) ?>
		<div id="material_other_container" <?php if(!$model->material_other) : ?>class="hidden-js" <?php endif;?>>
			<?= $form->field($model, 'material_other') ?>
		</div>
		<?= $form->field($model, 'entrances')->checkboxList($model->getBoxEntrances(), ['unselect' => null, 'item' => function ($index, $label, $name, $checked, $value) use ($form, $model) {
			$extra = '';
			$checked = (is_array($model->checked_entrances) && in_array($value, array_keys($model->checked_entrances))) ? true : false;
			if($value === Boxtypes::BOX_ENTRANCES_OTHER && !empty($model->checked_entrances[$value]))
				$extra = '<input type="text" name="Boxtypes[entrance_other]" value="'.$model->checked_entrances[$value].'" class="form-control">';
			
			return '<div>'.Html::checkbox($name, $checked, ['label' => $label, 'value' => $value]).' '.$extra.'</div>';
		}]) ?>
		<?= $form->field($model, 'dropping_board')->dropDownList(["" => ""] + Boxtypes::getBoxDroppingBoards()) ?>
		<div id="dropping_board_other_container" <?php if(!$model->dropping_board_other) : ?>class="hidden-js" <?php endif;?>>
			<?= $form->field($model, 'dropping_board_other') ?>
		</div>
		<?php if($model->picture) : ?>
    		<div class="row">
    			<div class="col-xs-4">
    				<?= $form->field($model, 'pictureFile')->fileInput() ?>
    				<?= $form->field($model, 'deleteImage')->checkbox() ?>
    			</div>
    			<div class="col-xs-8 text-right">
    				<img src="/uploads/boxtypes/pictures/<?= $model->picture ?>" alt="currentimage" style="max-width:200px; max-height: 200px;">
    			</div>
    		</div>
    	<?php else: ?>
    		<?= $form->field($model, 'pictureFile')->fileInput() ?>
    	<?php endif; ?>
    	<div class="text-advanced" data-slide-toggle="advanced"><span>Geavanceerde opties</span></div>
    	<div class="advanced <?= ($model->isNewRecord && !$model->hasErrors()) ? 'hidden-js' : '' ?>">
			<?php if($model->buildingplan) :?>
				<?= $form->field($model, 'buildingplanFile')->fileInput() ?>
				<?= $form->field($model, 'deleteBuildingplan')->checkbox() ?>
			<?php else: ?>
				<?= $form->field($model, 'buildingplanFile')->fileInput() ?>
			<?php endif; ?>
			<?= $form->field($model, 'height') ?>
			<?= $form->field($model, 'width') ?>
			<?= $form->field($model, 'depth') ?>
			<?= $form->field($model, 'entrance_width') ?>
			<?= $form->field($model, 'entrance_height') ?>
			<?= $form->field($model, 'minimal_crevice_width') ?>
			<?= $form->field($model, 'maximal_crevice_width') ?>    		
    	</div>
		<div class="form-group text-right">
			<input type="submit" class="btn btn-success" value="<?= Yii::t('app', 'Opslaan')?>">
		</div>
	<?php ActiveForm::end(); ?>
</div>
<?php $this->beginJs(View::POS_READY); ?>
	<script type="text/javascript">
		/* Boxtype form */
	
			/* Boxtype shape other */
			$(document).on('change', '.boxtype-form #boxtypes-shape', function() {
				if(<?= Boxtypes::BOX_SHAPE_OTHER ?> == $(this).val()) {
					$('#shape_other_container').show();
				} else {
					$('#shape_other_container').hide();
				}
			});

			/* Boxtype chamber count */
			$(document).on('change', '.boxtype-form #boxtypes-shape', function() {
				var boxtypesWithChambers = <?= json_encode(Boxtypes::getChamberBoxtypeModels()) ?>;
				if(boxtypesWithChambers.indexOf(parseInt($(this).val())) !== -1) {
					$('#chamber_count_container').show();
				} else {
					$('#chamber_count_container').hide();
				}
			});

			/* Boxtype material other */
			$(document).on('change', '.boxtype-form #boxtypes-material', function() {
				if(<?= Boxtypes::BOX_MATERIAL_OTHER ?> == $(this).val()) {
					$('#material_other_container').show();
				} else {
					$('#material_other_container').hide();
				}
			});

			/* Boxtype entrance count */
			$(document).on('change', '.boxtype-form #boxtypes-entrance', function() {
				if(<?= Boxtypes::BOX_ENTRANCES_OTHER ?> == $(this).val()) {
					$('#entrance_other_container').show();
				} else {
					$('#entrance_other_container').hide();
				}
			});

			/* Boxtype dropping board other */
			$(document).on('change', '.boxtype-form #boxtypes-dropping_board', function() {
				if(<?= Boxtypes::BOX_DROPPING_BOARD_OTHER ?> == $(this).val()) {
					$('#dropping_board_other_container').show();
				} else {
					$('#dropping_board_other_container').hide();
				}
			});
			
	</script>
<?php $this->endJs(); ?>