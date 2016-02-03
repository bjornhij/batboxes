<?php
use app\models\Visits;
use yii\helpers\Url;
use yii\bootstrap\Html;
use app\models\Observations;
use app\components\View;
use app\models\Projects;
use app\components\WGS84;
use yii\helpers\HtmlPurifier;
$visitModel = new Visits();
?>
<div class="observation-detail">

	<h1><?= Yii::t('app', 'Waarneming')?> #<?= $observation->number ?></h1>
	<?php if($observation->validated_by_id) : ?>
		<p class="bg-success text-success">
			<?= Yii::t('app', 'Gevalideerd door ')?>
			<?= Html::a($observation->validator->username, "#") ?>
			<?= Yii::t('app', 'op ')?>
			<?= $observation->validated_date ?>
		</p>
	<? endif; ?>
	
	<br>
	
	<?php if(!$observation->validated_by_id && Yii::$app->user->can('updateObservation')) : ?>
		<a href="<?= Url::toRoute('observations/validate/'.$observation->id) ?>" class="btn btn-success"><?= Yii::t('app', 'Valideren')?></a>
	<? endif; ?>
	
	<?php if(Yii::$app->user->can('updateObservation', ['observation' => $observation])) : ?>
		<a href="<?= Url::toRoute('observations/form/'.$observation->id) ?>" class="btn btn-info"><?= Yii::t('app', 'Bewerken')?></a>
	<?php endif; ?> 
	<?php if(Yii::$app->user->can('deleteObservation', ['observation' => $observation])) : ?>
		<a href="<?= Url::toRoute('observations/delete/'.$observation->id) ?>" class="btn btn-danger"><?= Yii::t('app', 'Verwijderen')?></a>
	<?php endif; ?>
	
	<?php if(Yii::$app->user->can('updateObservation', ['observation' => $observation]) || Yii::$app->user->can('deleteObservation', ['observation' => $observation])) : ?>
		<br><br>
	<?php endif; ?>
	
	<table class="table table-striped">
		<tr>
			<td width="50%"><?= Yii::t('app', 'Project') ?></td>
			<td><?= Html::a($observation->box->project->name, Url::toRoute('projects/detail/'.$observation->id)) ?>
		</tr>
		<tr>
			<td><?= $observation->getAttributeLabel('box_id') ?></td>
			<td>
				<?php if($observation->box->project->blur != Projects::BLUR_NO_BOX_CODE && $observation->visit->blur != Projects::BLUR_NO_BOX_CODE): ?>
					<?= Html::a($observation->box->code, Url::toRoute('boxes/detail/'.$observation->box_id)) ?>
				<?php else: ?>
					<?= Yii::t('app', 'verborgen') ?>
				<?php endif; ?>	
			</td>
		</tr>
		<?php if($observation->observation_type != Observations::OBSERVATION_TYPE_NULL): ?>
			<tr>
				<td><?= Yii::t('app', 'Datum') ?></td>
				<td><?= Html::encode($observation->visit->date) ?></td>
			</tr>
		<?php endif; ?>
		<tr>
			<td><?= $observation->getAttributeLabel('observation_type') ?></td>
			<td><?= Html::encode($observation->getObservationType()) ?>
		</tr>
		<tr>
			<td><?= $visitModel->getAttributeLabel('box_open') ?></td>
			<td><?= Html::encode($observation->visit->getBoxOpen()) ?>
		</tr>
		<tr>
			<td><?= $visitModel->getAttributeLabel('cleaned') ?></td>
			<td><?= Html::encode($observation->visit->getCleaned()) ?>
		</tr>
		<?php if($observation->remarks): ?>
			<tr>
				<td><?= $visitModel->getAttributeLabel('remarks') ?></td>
				<td><?= HTMLPurifier::process($observation->remarks) ?>
			</tr>
		<?php endif; ?>
	</table>
	
	<?php if($observation->observation_type != Observations::OBSERVATION_TYPE_NULL): ?>
		<div class="row">
			<div class="col-md-6">
				<table class="table table-striped">
					<tr>
						<td><?= $observation->getAttributeLabel('observation_type') ?></td>
						<td><?= Html::encode($observation->getObservationType()) ?>
					</tr>
					<tr>
						<td><?= $observation->getAttributeLabel('taxon_id') ?></td>
						<td><?= Html::encode($observation->getTaxonomy()) ?>
					</tr>
					<tr>
						<td><?= $observation->getAttributeLabel('species_id') ?></td>
						<td><?= Html::encode($observation->species->dutch) ?>
					</tr>
					<!--  Type: sight -->
					<? if($observation->observation_type == Observations::OBSERVATION_TYPE_SIGHT) : ?>
						<tr>
							<td><?= $observation->getAttributeLabel('age') ?></td>
							<td><?= Html::encode($observation->getAge()) ?>
						</tr>
						<tr>
							<td><?= $observation->getAttributeLabel('sight_quantity') ?></td>
							<td><?= Html::encode($observation->sight_quantity) ?>
						</tr>
					<? endif; ?>
					<!--  /type -->
					<!--  Type: manure -->
					<? if($observation->observation_type == Observations::OBSERVATION_TYPE_MANURE) : ?>
						<tr>
							<td><?= $observation->getAttributeLabel('manure_collected') ?></td>
							<td><?= Html::encode($observation->getManureCollected()) ?>
						</tr>
						<tr>
							<td><?= $observation->getAttributeLabel('manure_collection') ?></td>
							<td><?= HTMLPurifier::process($observation->manure_collection) ?>
						</tr>
						<tr>
							<td><?= $observation->getAttributeLabel('manure_size') ?></td>
							<td><?= Html::encode($observation->getManureSize()) ?>
						</tr>
						<tr>
							<td><?= $observation->getAttributeLabel('manure_quantity') ?></td>
							<td><?= Html::encode($observation->getManureQuanitity()) ?>
						</tr>
					<? endif; ?>
					<!--  /type -->
					<!--  Type: catch -->
					<? if($observation->observation_type == Observations::OBSERVATION_TYPE_CATCH) : ?>
						<tr>
							<td><?= $observation->getAttributeLabel('catch_weight') ?></td>
							<td><?= Html::encode($observation->catch_weight) ?>
						</tr>
						<tr>
							<td><?= $observation->getAttributeLabel('catch_sex') ?></td>
							<td><?= Html::encode($observation->getSex()) ?>
						</tr>
						<? if($observation->sex != Observations::CATCH_SEX_UNKNOWN) : ?>
							<tr>
								<td><?= $observation->getAttributeLabel('catch_sexual_status') ?></td>
								<td><?= Html::encode($observation->catch_sexual_status) ?>
							</tr>
						<? endif; ?>
						<tr>
							<td><?= $observation->getAttributeLabel('catch_forearm_right') ?></td>
							<td><?= Html::encode($observation->catch_forearm_right) ?>
						</tr>
						<tr>
							<td><?= $observation->getAttributeLabel('catch_forearm_right') ?></td>
							<td><?= Html::encode($observation->catch_forearm_left) ?>
						</tr>
						<?php if($observation->catch_parasite_id) : ?>
							<tr>
								<td><?= $observation->getAttributeLabel('catch_parasite_id') ?></td>
								<td><?= Html::encode($observation->catch_parasite_id) ?>
							</tr>
							<tr>
								<td><?= $observation->getAttributeLabel('catch_parasite_collected') ?></td>
								<td><?= Html::encode($observation->catch_parasite_collected) ?>
							</tr>
							<tr>
								<td><?= $observation->getAttributeLabel('catch_parasite_collection') ?></td>
								<td><?= Html::encode($observation->catch_parasite_collection) ?>
							</tr>
						<?php endif; ?>
						<tr>
							<td><?= $observation->getAttributeLabel('catch_ring_code') ?></td>
							<td><?= Html::encode($observation->catch_ring_code) ?>
						</tr>
						<tr>
							<td><?= $observation->getAttributeLabel('catch_transponder_code') ?></td>
							<td><?= Html::encode($observation->catch_transponder_code) ?>
						</tr>
						<tr>
							<td><?= $observation->getAttributeLabel('catch_radio_transmitter_code') ?></td>
							<td><?= Html::encode($observation->catch_radio_transmitter_code) ?>
						</tr>
						<tr>
							<td><?= $observation->getAttributeLabel('catch_dna') ?></td>
							<td><?= Html::encode($observation->catch_dna) ?>
						</tr>
					<? endif; ?>
					<!--  /type -->
					<? if(in_array($observation->observation_type, [Observations::OBSERVATION_TYPE_SIGHT, Observations::OBSERVATION_TYPE_CATCH])) : ?>
						<tr>
							<td><?= $observation->getAttributeLabel('dead') ?></td>
							<td><?= Html::encode($observation->getDead()) ?>
						</tr>
					<? endif; ?>
				</table>
			</div>
			<div class="col-md-6">
				<img src="<?= ($observation->picture) ? '/uploads/observations/'.$observation->picture : 'http://placehold.it/150?text=Geen+afbeelding' ?>" class="image" alt="observation_picture">
			</div>
		</div>
	<?php endif; ?>
	
	<?php if($observation->box->project->blur != Projects::BLUR_NO_BOX_CODE && $observation->visit->blur != Projects::BLUR_NO_BOX_CODE): ?>
		<div class="row">
			<div class="col-xs-12">
				<h2><?= Yii::t('app', 'Lokatie') ?></h2>
				<div id="map" style="width: 100%; height: 250px;"></div>
				<script src="https://maps.googleapis.com/maps/api/js?v=3&libraries=geometry"></script> 
			</div>
		</div>
	<?php endif; ?>
	
</div>
<?php View::beginJS(); ?>
<script>
	/* Google Maps */
	var element = document.getElementById("map");
	var mapTypeIds = ["OSM"];
    for(var type in google.maps.MapTypeId)
        mapTypeIds.push(google.maps.MapTypeId[type]);
	var map = new google.maps.Map(element, {
	    zoom: 8,
	    streetViewControl: false,
	    mapTypeId: "OSM",
        mapTypeControlOptions: {
            mapTypeIds: mapTypeIds
        }
	});
	map.mapTypes.set("OSM", new google.maps.ImageMapType({
	    getTileUrl: function(coord, zoom) {
	        return "http://tile.openstreetmap.org/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
	    },
	    tileSize: new google.maps.Size(256, 256),
	    name: "OpenStreetMap",
	    maxZoom: 18
	}));
	<?php if($observation->visit->showBlur()) : ?>
		var cords = <?= WGS84::blurCoordinates($observation->visit->getBlurInMeters(), $observation->box->cord_lat, $observation->box->cord_lng, true) ?>;
		map.setZoom(16);
		map.setCenter(cords);
		var rectangle = new google.maps.Rectangle({ 
			strokeColor: 'red',
			strokeOpacity: 1,
			map: map,
			bounds: calcBounds(map.getCenter(), new google.maps.Size(<?= $observation->visit->getBlurInMeters() ?>,<?= $observation->visit->getBlurInMeters() ?>))   
		});
	<?php else: ?>
		var marker = new google.maps.Marker({
			position: {'lat': <?= $observation->box->cord_lat ?>, 'lng': <?= $observation->box->cord_lng ?>},
			map: map,
			icon: '/images/googlemap-pointer.png'
		});
		map.setZoom(16);
		map.setCenter(marker.position);
	<?php endif; ?>
</script>
<?php View::endJS(); ?>