<?php 
use app\models\Boxes;
use app\models\search\VisitsSearch;
use app\models\search\BoxesSearch;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
$boxModel = new Boxes();
?>
<div class="project-detail">
	
	<h1><?= Yii::t('app', 'Project') ?> <?= Html::encode($project->name) ?></h1>
	
	<?php if(Yii::$app->user->can('updateProject', ['project' => $project])) : ?>
		<a href="<?= Url::toRoute('projects/form/'.$project->id) ?>" class="btn btn-info"><?= Yii::t('app', 'Bewerken')?></a>
	<?php endif; ?>
	<?php if(Yii::$app->user->can('deleteProject', ['project' => $project])) : ?> 
		<a href="<?= Url::toRoute('projects/delete/'.$project->id) ?>" class="btn btn-danger" data-confirm="<?= Yii::t('yii', "Weet je zeker dat je dit project $project->name wilt verwijderen?") ?>"><?= Yii::t('app', 'Verwijderen')?></a>
	<?php endif; ?>
	<?php if(Yii::$app->user->can('updateProject', ['project' => $project]) || Yii::$app->user->can('deleteProject')) : ?>
		<br>
	<?php endif; ?>
	
	<br>
	
	<div class="row">
		<div class="col-xs-12 col-md-6">
			<table class="table">
				<tr>
					<td><?= $project->getAttributeLabel('name') ?></td>
					<td><?= Html::encode($project->name) ?></td>
				</tr>
				<tr>
					<td><?= $project->getAttributeLabel('owner_id') ?></td>
					<td><?= $project->owner->username . ' (' . $project->owner->fullname . ')' ?></td>
				</tr>
				<tr>
					<td><?= $project->getAttributeLabel('main_observer_id') ?></td>
					<td><?= $project->mainObserver->username . ' (' . $project->mainObserver->fullname . ')' ?></td>
				</tr>
				<tr>
					<td><?= Yii::t('app', 'Tellers') ?></td>
					<td>
						<?php foreach($project->projectCounters as $index => $pc) :?>
							<?= $pc->username ?> (<?= $pc->fullname ?>)<?= (($index+1) < count($project->projectCounters)) ? ', ' : '' ?>
						<?php endforeach; ?>
					</td>
				</tr>
				<?php if($project->owner_id == Yii::$app->user->getId() || $project->main_observer_id == Yii::$app->user->getId() || (is_object(Yii::$app->user->getIdentity()) && Yii::$app->user->getIdentity()->hasRole( 'administrator'))) : ?>
					<?php if($project->blur) : ?>
						<tr>
							<td><?= $project->getAttributeLabel('blur') ?></td>
							<td><?= $project->getBlur() ?></td>
						</tr>
					<?php endif; ?>
					<?php if($project->embargo) : ?>
						<tr>
							<td><?= $project->getAttributeLabel('embargo') ?></td>
							<td><?= Html::encode($project->embargo) ?></td>
						</tr>
					<?php endif; ?>
				<?php endif; ?>
			</table>
		</div>
	</div>
	
	<?php if($project->remarks) : ?>
		<h1><?= Yii::t('app', 'Samenvatting') ?></h1>
		<?= nl2br(HTMLPurifier::process($project->remarks)) ?>
	<?php endif; ?>
	
	<h1><?= Yii::t('app', 'Kasten') ?></h1>
	<?php if(Yii::$app->user->can('boxRights', ['project' => $project])) : ?>
		<?= Html::a(Yii::t('app', 'Kast toevoegen'), Url::toRoute(['boxes/form', 'project_id' => $project->id]), ['class' => 'btn btn-info'])?> <br><br>
	<?php endif; ?>
	<?php
	$searchModel = new BoxesSearch();
	$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $project->id);
	echo $this->render('/boxes/partials/dataTable.php', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
	?>
	
	<h1><?= Yii::t('app', 'Bezoeken') ?></h1>
	<?php
	$searchModel = new VisitsSearch();
	$dataProvider = $searchModel->search(Yii::$app->request->queryParams, ['project_id' => $project->id]);
	echo $this->render('/visits/partials/dataTable.php', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]); 
	?>
	
</div>