<?php 
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;
use app\components\WGS84;
use app\components\View;
use app\models\Projects;
use app\models\search\ObservationsSearch;
use app\models\Observations;
?>
<div class="box-detail">

	<h1><?= Yii::t('app', 'Kast') ?> <?= Html::encode($box->code) ?></h1>
	
	<?php if(Yii::$app->user->can('boxRights', ['project' => $box->project])) : ?>
		<a href="<?= Url::toRoute('boxes/form/'.$box->id) ?>" class="btn btn-info"><?= Yii::t('app', 'Bewerken')?></a> 
		<a href="<?= Url::toRoute('boxes/delete/'.$box->id) ?>" class="btn btn-danger" data-confirm="<?= Yii::t('yii', "Weet je zeker dat je deze kast $box->code wilt verwijderen?") ?>"><?= Yii::t('app', 'Verwijderen')?></a>
	<?php endif; ?>
	
	<br><br>
	
	<div class="row">
		<div class="col-xs-12 col-md-6">
			<table class="table">
				<tr>
					<td><?= $box->getAttributeLabel('code') ?></td>
					<td><?= Html::encode($box->code) ?></td>
				</tr>
				<tr>
					<td><?= $box->getAttributeLabel('project_id') ?></td>
					<td><?= Html::a($box->project->name, Url::toRoute('projects/detail/'.$box->project_id)) ?></td>
				</tr>
				<?php if($box->boxtype_id) : ?>
					<tr>
						<td><?= $box->getAttributeLabel('boxtype_id') ?></td>
						<td><?= Html::a(Html::encode($box->boxtypes->model), Url::toRoute('boxtypes/detail/'.$box->boxtype_id)) ?></td>
					</tr>
				<?php endif; ?>
				<?php if($box->cluster) : ?>
					<tr>
						<td><?= $box->getAttributeLabel('cluster') ?></td>
						<td><?= Html::encode($box->cluster) ?></td>
					</tr>
				<?php endif; ?>
				<?php if($box->placement_date) : ?>
					<tr>
						<td><?= $box->getAttributeLabel('placement_date') ?></td>
						<td><?= Html::encode($box->placement_date) ?></td>
					</tr>
				<?php endif; ?>
				<?php if($box->removal_date) : ?>
					<tr>
						<td><?= $box->getAttributeLabel('removal_date') ?></td>
						<td><?= Html::encode($box->removal_date) ?></td>
					</tr>
				<?php endif; ?>
				<?php if($box->location) : ?>
					<tr>
						<td><?= $box->getAttributeLabel('location') ?></td>
						<td><?= Html::encode($box->location) ?></td>
					</tr>
				<?php endif; ?>
				<?php if($box->direction) : ?>
					<tr>
						<td><?= $box->getAttributeLabel('direction') ?></td>
						<td><?= $box->getDirection() ?></td>
					</tr>
				<?php endif; ?>
				<?php if($box->placement_height) : ?>
					<tr>
						<td width="40%"><?= $box->getAttributeLabel('placement_height') ?></td>
						<td><?= Html::encode($box->getPlacementHeight()) ?></td>
					</tr>
				<?php endif; ?>
			</table>
		</div>
		<div class="col-md-6 text-center box-picture">
			<img src="<?= ($box->picture) ? '/uploads/boxes/'.$box->picture : 'http://placehold.it/150?text=Geen+afbeelding' ?>" alt="box_picture">
		</div>
	</div>
	
	<?php if($box->remarks) : ?>
		<div class="row">
			<div class="col-xs-12">
				<h2><?= $box->getAttributeLabel('remarks') ?></h2>
				<?= HTMLPurifier::process($box->remarks) ?>
			</div>
		</div>
	<?php endif; ?>
	
	<div class="row">
		<div class="col-xs-12">
			<h2><?= $box->getAttributeLabel('location') ?></h2>
			<div id="map" style="width: 100%; height: 250px;"></div>
			<script src="https://maps.googleapis.com/maps/api/js?v=3&libraries=geometry"></script> 
		</div>
	</div>

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
	<?php if(in_array($box->project->blur, [Projects::BLUR_100, Projects::BLUR_500]) && $box->project->showBlur()) : ?>
		var cords = <?=WGS84::blurCoordinates($box->project->getBlurInMeters(), $box->cord_lat, $box->cord_lng, true); ?>;
		map.setZoom(14);
		map.setCenter(cords);
		var rectangle = new google.maps.Rectangle({ 
			strokeColor: 'red',
			strokeOpacity: 1,
			map: map,  	
			bounds: calcBounds(map.getCenter(), new google.maps.Size(<?=$box->project->getBlurInMeters()?>,<?=$box->project->getBlurInMeters()?>))   
		});
	<?php else: ?>
		var marker = new google.maps.Marker({
			position: {'lat': <?= $box->cord_lat ?>, 'lng': <?= $box->cord_lng ?>},
			map: map,
			icon: '/images/googlemap-pointer.png'
		});
		map.setZoom(14);
		map.setCenter(marker.position);
	<?php endif; ?>

	function calcBounds(center,size){
	    var n=google.maps.geometry.spherical.computeOffset(center,size.height/2,0).lat(),
	        s=google.maps.geometry.spherical.computeOffset(center,size.height/2,180).lat(),
	        e=google.maps.geometry.spherical.computeOffset(center,size.width/2,90).lng(),
	        w=google.maps.geometry.spherical.computeOffset(center,size.width/2,270).lng();
	        return new google.maps.LatLngBounds(new google.maps.LatLng(s,w), new google.maps.LatLng(n,e));
	 }
</script>
<?php View::endJS(); ?>