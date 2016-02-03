<?php 
use yii\bootstrap\ActiveForm;
use app\models\Species;
?>
<h1><?= Yii::t('app', 'Soort')?> <?= ($model->isNewRecord) ? Yii::t('app', 'toevoegen') : Yii::t('app', 'bewerken') ?></h1>
<div class="species-form">
	<?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>
		<?= $form->field($model, 'taxon')->dropDownList(Species::getTaxonomies()) ?>
		<?= $form->field($model, 'genus') ?>
		<?= $form->field($model, 'speceus') ?>
		<?= $form->field($model, 'dutch') ?>
		<?= $form->field($model, 'url') ?>
		<div class="form-group text-right">
			<input type="submit" class="btn btn-success" value="<?= Yii::t('app', 'Opslaan')?>">
		</div>
	<?php ActiveForm::end(); ?>
</div>     