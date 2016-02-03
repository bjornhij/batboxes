<?php
use app\models\Boxtypes;
use yii\bootstrap\Html;
use yii\helpers\Url;
$boxtypeModel = new Boxtypes();
?>
<div class="boxtype-detail">

	<h1><?= Html::encode($boxtype->model) ?></h1>
	<?php if(Yii::$app->user->getIdentity()->hasRole('administrator')) : ?>
		<a href="<?= Url::toRoute('boxtypes/form/'.$boxtype->id) ?>" class="btn btn-info"><?= Yii::t('app', 'Bewerken')?></a>
		<?php if(!$boxtype->boxes): ?> 
			<a href="<?= Url::toRoute('boxtypes/delete/'.$boxtype->id) ?>" class="btn btn-danger" data-confirm="<?= Yii::t('yii', "Weet je zeker dat je het kasttype $boxtype->model wilt verwijderen?") ?>"><?= Yii::t('app', 'Verwijderen')?></a>
		<?php endif; ?>
	<?php endif;?>
	
	<br><br>
	
	<div class="row">
		<div class="<?= ($boxtype->picture) ? 'col-md-6' : 'col-md-12' ?>">
			<table class="table">
				<tr>
					<td><?= $boxtypeModel->getAttributeLabel('model') ?></td>
					<td><?= Html::encode($boxtype->model) ?></td>
				</tr>
				<?php if($boxtype->shape) : ?>
					<tr>
						<td><?= $boxtypeModel->getAttributeLabel('shape') ?></td>
						<td>
							<?php if($boxtype->shape == Boxtypes::BOX_SHAPE_OTHER) : ?>
								<?= Html::encode($boxtype->shape_other) ?>
							<?php elseif(in_array($boxtype->shape, Boxtypes::getChamberBoxtypeModels())) : ?>
								<?= Boxtypes::getBoxShape($boxtype->shape) . " (".$boxtype->chamber_count." ".Yii::t('app', 'kamers').")" ?>
							<?php else :?>
								<?= Boxtypes::getBoxShape($boxtype->shape) ?>
							<?php endif; ?>
						</td>
					</tr>
				<?php endif; ?>
				<?php if($boxtype->material) : ?>
					<tr>
						<td><?= $boxtypeModel->getAttributeLabel('material') ?></td>
						<td>
							<?php if($boxtype->material == Boxtypes::BOX_MATERIAL_OTHER) : ?>
								<?= Html::encode($boxtype->material_other) ?>
							<?php else: ?>
								<?= Boxtypes::getBoxMaterial($boxtype->material) ?>
							<?php endif; ?>
						</td>
					</tr>
				<?php endif; ?>
				<?php if($boxtype->entrances) : ?>
					<tr>
						<td><?= $boxtypeModel->getAttributeLabel('entrances') ?></td>
						<td>
							<?php foreach($boxtype->boxtypeEntrances as $index => $entrance) : ?> 
	        					<?php if($entrance->entrance_index == Boxtypes::BOX_ENTRANCES_OTHER) : ?>
	        						<?= Html::encode($entrance->other) ?>
	        					<?php else : ?>
	        						<?= Boxtypes::getBoxEntrance($entrance->entrance_index) ?>
	        					<?php endif; ?>
	        				<?php endforeach; ?>
						</td>
					</tr>
				<?php endif; ?>
				<?php if($boxtype->dropping_board) : ?>
					<tr>
						<td><?= $boxtypeModel->getAttributeLabel('dropping_board') ?></td>
						<td>
							<?php if($boxtype->dropping_board == Boxtypes::BOX_DROPPING_BOARD_OTHER) : ?>
								<?= Html::encode($boxtype->dropping_board_other) ?>
							<?php else: ?>
								<?= Boxtypes::getBoxDroppingBoard($boxtype->dropping_board) ?>
							<?php endif; ?>
						</td>
					</tr>
				<?php endif; ?>
				<?php if($boxtype->chamber_count) : ?>
					<tr>
						<td><?= $boxtypeModel->getAttributeLabel('chamber_count') ?></td>
						<td><?= Html::encode($boxtype->chamber_count) ?></td>
					</tr>
				<?php endif; ?>
				<?php if($boxtype->buildingplan) : ?>
					<tr>
						<td><?= $boxtypeModel->getAttributeLabel('buildingplan') ?></td>
						<td><?= Html::a($boxtype->buildingplan, "/uploads/boxtypes/buildingplans/".$boxtype->buildingplan, ['target' => '_BLANK']); ?></td>
					</tr>
				<?php endif; ?>
			</table>
		</div>
		<?php if($boxtype->picture) : ?>
			<div class="col-md-6 text-center">
				<div class="boxtype-picture">
					<?= Html::img('/uploads/boxtypes/pictures/'.$boxtype->picture)?>
				</div>	
			</div>
		<?php endif; ?>
	</div>
	
	<?php if($boxtype->height || $boxtype->depth || $boxtype->width || $boxtype->entrance_width || $boxtype->entrance_height || $boxtype->minimal_crevice_width || $boxtype->maximal_crevice_width) : ?>	
		<h2><?= Yii::t('app', 'Afmetingen')?></h2>
		<table class="table">
			<?php if($boxtype->height) : ?>
				<tr>
					<td><?= $boxtypeModel->getAttributeLabel('height') ?></td>
					<td><?= Html::encode($boxtype->height) ?></td>
				</tr>
			<?php endif; ?>
			<?php if($boxtype->depth) : ?>
				<tr>
					<td><?= $boxtypeModel->getAttributeLabel('depth') ?></td>
					<td><?= Html::encode($boxtype->depth) ?></td>
				</tr>
			<?php endif; ?>
			<?php if($boxtype->width) : ?>
				<tr>
					<td><?= $boxtypeModel->getAttributeLabel('width') ?></td>
					<td><?= Html::encode($boxtype->width) ?></td>
				</tr>
			<?php endif; ?>
			<?php if($boxtype->entrance_width) : ?>
				<tr>
					<td><?= $boxtypeModel->getAttributeLabel('entrance_width') ?></td>
					<td><?= Html::encode($boxtype->entrance_width) ?></td>
				</tr>
			<?php endif; ?>
			<?php if($boxtype->entrance_height) : ?>
				<tr>
					<td><?= $boxtypeModel->getAttributeLabel('entrance_height') ?></td>
					<td><?= Html::encode($boxtype->entrance_height) ?></td>
				</tr>
			<?php endif; ?>
			<?php if($boxtype->minimal_crevice_width) : ?>
				<tr>
					<td><?= $boxtypeModel->getAttributeLabel('minimal_crevice_width') ?></td>
					<td><?= Html::encode($boxtype->minimal_crevice_width) ?></td>
				</tr>
			<?php endif; ?>
			<?php if($boxtype->maximal_crevice_width) : ?>
				<tr>
					<td><?= $boxtypeModel->getAttributeLabel('maximal_crevice_width') ?></td>
					<td><?= Html::encode($boxtype->maximal_crevice_width) ?></td>
				</tr>
			<?php endif; ?>
		</table>
	<?php endif; ?>

</div>