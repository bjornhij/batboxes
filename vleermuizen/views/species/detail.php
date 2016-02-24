<?php
use app\models\Species;
use yii\bootstrap\Html;
use yii\helpers\Url;
?>

<h1><?= Html::encode($specie->dutch) ?> (<?= Html::encode($specie->genus) . " " . Html::encode($specie->speceus) ?>)</h1>

<?php if(Yii::$app->user->getIdentity()->hasRole('administrator')) : ?>
	<a href="<?= Url::toRoute('species/form/'.$specie->id) ?>" class="btn btn-info"><?= Yii::t('app', 'Bewerken')?></a>
	<?php if(!$specie->observations): ?> 
		<a href="<?= Url::toRoute('species/delete/'.$specie->id) ?>" class="btn btn-danger" data-confirm="<?= Yii::t('yii', "Weet je zeker dat je de $specie->dutch wilt verwijderen?") ?>"><?= Yii::t('app', 'Verwijderen')?></a>
	<?php endif; ?>
<?php endif; ?>

<br><br>

<div class="table-responsive">
	<table class="table">
		<tr>
			<td><?= $specie->getAttributeLabel('taxon') ?></td>
			<td><?= Species::getTaxonomy($specie->taxon) ?></td>
		</tr>
		<tr>
			<td><?= $specie->getAttributeLabel('genus') ?></td>
			<td><?= Html::encode($specie->genus) ?></td>
		</tr>
		<tr>
			<td><?= $specie->getAttributeLabel('speceus') ?></td>
			<td><?= Html::encode($specie->speceus) ?></td>
		</tr>
		<tr>
			<td><?= $specie->getAttributeLabel('dutch') ?></td>
			<td><?= Html::encode($specie->dutch) ?></td>
		</tr>
		<?php if($specie->url): ?>
			<tr>
				<td><?= $specie->getAttributeLabel('url') ?></td>
				<td><a href="<?= Html::encode($specie->url) ?>"><?= Html::encode($specie->url) ?></a></td>
			</tr>
		<?php endif; ?>
	</table>
</div>