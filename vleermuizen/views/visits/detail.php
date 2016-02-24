<?php 
use app\models\Visits;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use app\models\search\ObservationsSearch;
$visitModel = new Visits();
?>

<h1><?= Yii::t('app', 'Bezoek details van') ?> <?= Html::encode($visit->date) ?> - <?= Html::encode($visit->project->name) ?></h1>

<?php if(Yii::$app->user->can('updateVisit', ['visit' => $visit])) : ?>
	<a href="<?= Url::toRoute('visits/form/'.$visit->id) ?>" class="btn btn-info"><?= Yii::t('app', 'Bewerken')?></a>
<?php endif; ?> 
<?php if(Yii::$app->user->can('deleteVisit', ['visit' => $visit])) : ?>
	<a href="<?= Url::toRoute('visits/delete/'.$visit->id) ?>" class="btn btn-danger"><?= Yii::t('app', 'Verwijderen')?></a>
<?php endif; ?>
<?php if(Yii::$app->user->can('updateVisit', ['visit' => $visit]) || Yii::$app->user->can('deleteVisit', ['visit' => $visit])) : ?>
	<br><br>
<?php endif; ?>

<table class="table table-striped">
	<tr>
		<td width="50%"><?= $visitModel->getAttributeLabel('project') ?></td>
		<td><?= Html::a(Html::encode($visit->project->name), Url::toRoute('projects/detail/'.$visit->project->id)) ?></td>
	</tr>
	<tr>
		<td><?= $visitModel->getAttributeLabel('date') ?></td>
		<td><?= Html::encode($visit->date) ?></td>
	</tr>
	<tr>
		<td><?= $visitModel->getAttributeLabel('box_open') ?></td>
		<td><?= $visit->getBoxOpen() ?></td>
	</tr>
	<tr>
		<td><?= $visitModel->getAttributeLabel('cleaned') ?></td>
		<td><?= $visit->getCleaned() ?></td>
	</tr>
	<tr>
		<td><?= $visitModel->getAttributeLabel('count_completeness') ?></td>
		<td><?= $visit->getCountCompleteness() ?></td>
	</tr>
	<?php if(in_array(Yii::$app->user->getId(), $visit->observers) || (is_object(Yii::$app->user->getIdentity()) && Yii::$app->user->getIdentity()->hasRole( 'administrator'))) : ?>
		<?php if($visit->blur) : ?>
			<tr>
				<td><?= $visit->getAttributeLabel('blur') ?></td>
				<td><?= $visit->getBlur() ?></td>
			</tr>
		<?php endif; ?>
		<?php if($visit->embargo) : ?>
			<tr>
				<td><?= $visit->getAttributeLabel('embargo') ?></td>
				<td><?= Html::encode($visit->embargo) ?></td>
			</tr>
		<?php endif; ?>
	<?php endif; ?>
	<?php if($visitModel->remarks): ?>
		<tr>
			<td><?= $visitModel->getAttributeLabel('remarks') ?></td>
			<td><?= HTMLPurifier::process($visit->remarks) ?></td>
		</tr>
	<?php endif; ?>
</table>

<h2>
	<?= Yii::t('app', 'Waarnemingen') ?>
	<?php if(Yii::$app->user->can('createObservation', ['visit' => $visit])) : ?>
		<a href="<?= Url::toRoute(['observations/form', 'visit_id' => $visit->id]) ?>" class="btn btn-success pull-right"><?= Yii::t('app', 'Waarneming toevoegen')?></a>
	<?php endif; ?>
</h2>

<?php
$searchModel 	= new ObservationsSearch();
$dataProvider 	= $searchModel->search(Yii::$app->request->queryParams, ['visit_id' => $visit->id, 'show_null' => true]);
echo $this->render('/observations/partials/dataTable.php', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]) 
?>