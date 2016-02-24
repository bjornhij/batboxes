<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\models\Boxes;
use app\components\View;
/* @var $this yii\web\View */
/* @var $model \app\models\Projects */
/* @var $form ActiveForm */
?>
<h1><?= Yii::t('app', 'Kast ') ?><?= ($model->isNewRecord) ? Yii::t('app', 'toevoegen') : Yii::t('app', 'bewerken') ?></h1></h1>
<div class="box-form">
	<?php if($projects || (!$model->isNewRecord && Yii::$app->user->getIdentity()->hasRole('administrator'))) : ?>
	    <?php $form = ActiveForm::begin(['enableClientValidation' => false, 'options' => ['id' => 'boxForm', 'enctype' => 'multipart/form-data']]); ?>
	    	<?= $form->field($model, 'project_id')->dropDownList(["" => Yii::t('app', 'Selecteer een project')] + ArrayHelper::map($projects, 'id', 'name')) ?>
	    	<?= $form->field($model, 'code', ['inputOptions' => ['maxlength' => 45, 'class' => 'form-control']]) ?>
	    	<?= $form->field($model, 'boxtype_id')->dropDownList(["" => Yii::t('app', 'Onbekend')] + ArrayHelper::map($boxtypes, 'id', 'model')) ?>
	    	<?= $form->field($model, 'location', ['inputOptions' => ['maxlength' => 45, 'class' => 'form-control']]) ?>
	    	<?= $form->field($model, 'province')->dropDownList([
				'Noord-Holland' 	=> 'Noord-Holland', 
				'Zuid-Holland' 		=> 'Zuid-Holland', 
				'Friesland' 		=> 'Friesland', 
				'Flevoland' 		=> 'Flevoland', 
				'Utrecht' 			=> 'Utrecht', 
				'Noord-Brabant' 	=> 'Noord-Brabant', 
				'Groningen' 		=> 'Groningen', 
				'Drenthe' 			=> 'Drenthe', 
				'Gelderland' 		=> 'Gelderland', 
				'Limburg' 			=> 'Limburg',
	    		'Zeeland' 			=> 'Zeeland'
			]) ?>
			<?= $form->field($model, 'cord_format')->dropDownList(Boxes::getCordFormats()) ?>
	    	<?= $form->field($model, 'cord_lat', [
			'template' => '
				{label}
				<div class="input-group">
					<span class="input-group-addon" data-toggle="modal" data-target="#cord-container">
						<span class="glyphicon glyphicon-map-marker"></span>
					</span>
					{input}
				</div>
				{error}{hint}
			'])->textInput([
					'data-default' 		=> '', 
					'data-label-wgs84' 	=> $model->getAttributeLabel('cord_lat'),
					'data-label-rd' 	=> Yii::t('app', 'Coördinaten X')
		 	]) ?>
	    	<?= $form->field($model, 'cord_lng', [
			'template' => '
				{label}
				<div class="input-group">
					<span class="input-group-addon" data-toggle="modal" data-target="#cord-container">
						<span class="glyphicon glyphicon-map-marker"></span>
					</span>
					{input}
				</div>
				{error}{hint}
			'])->textInput([
					'data-default' 		=> '', 
					'data-label-wgs84' 	=> $model->getAttributeLabel('cord_lng'),
					'data-label-rd' 	=> Yii::t('app', 'Coördinaten Y')
		 	]) ?>
	    	<?= $form->field($model, 'placement_date')->widget(DatePicker::className(), [
	    		'type' 			=> 1,
			    'options' 		=> ['placeholder' => Yii::t('app', 'Selecteer datum')],
			    'pluginOptions' => [
			        'format' 			=> 'dd-mm-yyyy',
			        'todayHighlight' 	=> true,
			    	'autoclose' 		=> true,
			    	'weekStart'			=> 1,
			]]) ?>
		    
			<div class="text-advanced" data-slide-toggle="advanced"><span>Geavanceerde opties</span></div>
	    	<div class="advanced <?= ($model->isNewRecord && !$model->hasErrors()) ? 'hidden-js' : '' ?>">
	    		<?php if($model->picture) : ?>
	    			<div class="row">
	    				<div class="col-xs-4">
	    					<?= $form->field($model, 'imageFile')->fileInput() ?>
	    					<?= $form->field($model, 'deleteImage')->checkbox() ?>
	    				</div>
	    				<div class="col-xs-8 text-right">
	    					<img src="/uploads/boxes/<?= $model->picture ?>" alt="currentimage"  style="max-width: 200px; max-height: 200px;">
	    				</div>
	    			</div>
	    		<?php else: ?>
	    			<?= $form->field($model, 'imageFile')->fileInput() ?>
	    		<?php endif; ?>
		    	<?= $form->field($model, 'removal_date')->widget(DatePicker::classname(), [
				    'type' => 1,
			    	'options' => ['placeholder' => Yii::t('app', 'Selecteer datum')],
			    	'pluginOptions' => [
				        'format' => 'dd-mm-yyyy',
				        'todayHighlight' => true,
			    		'autoclose' => true
		    	]])  ?>
		    	<?= $form->field($model, 'remarks')->widget(\yii\redactor\widgets\Redactor::className(), [
		    		'clientOptions' => [
		    			'buttons' => [
		    				'formatting', 'bold', 'italic',
							'unorderedlist', 'orderedlist', 'outdent', 'indent',
							'link', 'alignment', 'horizontalrule'
		    			]
		    		]]) ?>
		    	<?= $form->field($model, 'direction')->dropDownList(Boxes::getDirectionOptions()) ?>
		    	<?= $form->field($model, 'placement_height') ?>
		    	<?= $form->field($model, 'cluster')->widget(Select2::className(), [
	    			'options' => ['placeholder' => Yii::t('app', 'Selecteer een cluster')],
	    			'pluginOptions' => [
	    				'tags' => true,
	    				'tokenSeparators' => [',', ' '],
	    				'ajax' => [
	    					'url' => Url::toRoute('boxes/ajax-get-clusters'),
	    					'dataType' => 'json',
	    					'data' => new JsExpression('function(params) { return {q:params.term, pid:$("#boxes-project_id").val()}; }')
	    				],
	    			],
	    		]) ?>
	    	</div>
	        <div class="form-group">
	            <?= Html::submitButton(Yii::t('app', 'Opslaan'), ['class' => 'btn btn-primary pull-right']) ?>
	        </div>
	    <?php ActiveForm::end(); ?>
	    <?php
	    Modal::begin(['id' => 'cord-container', 'header' => '<h3>'.Yii::t('app', 'Selecteer locatie op de kaart').'</h3>']);
	    	echo '<div id="map" style="width:100%; height: 400px;"></div>';
	    	echo '<hr />';
	    	echo '<div class="row">';
	    		echo '<div class="col-md-6"><input id="modal_cord_lat" type="text"  class="form-control"></div>';
	    		echo '<div class="col-md-6"><input id="modal_cord_lng" type="text" class="form-control"></div>';
			echo '</div>';
			echo '<hr />';
			echo '<div class="text-right"><button type="button" class="btn btn-success" data-dismiss="modal">Opslaan</button></div>';
		Modal::end();
		?>
		<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
	<?php else: ?>
		<p><?= Yii::t('app', "U heeft nog geen beschikbare projecten. Klik <a href='".Url::toRoute('projects/form')."'>hier</a> om een nieuw project aan te maken.")?></p>
	<?php endif; ?>
</div>
<script src="/js/rad2wgs.js"></script>
<?php View::beginJs(); ?>
<script>
	/* Coördinate picker shown event */
	$('#cord-container').on('shown.bs.modal', function (e) {

		if($('#boxes-cord_format').val() == <?= Boxes::CORD_FORMAT_RD ?> && !inrange(-90, $('#boxes-cord_lat').val(), 90) && !inrange(-180, $('#boxes-cord_lng').val(), 180)) {
			var lat = $('#boxes-cord_lat').val(), lng = $('#boxes-cord_lng').val();
			$('#boxes-cord_lat').val(RD2lat(lat, lng));
			$('#boxes-cord_lng').val(RD2lng(lat, lng));
			$('#modal_cord_lat').val(RD2lat(lat, lng));
            $('#modal_cord_lng').val(RD2lng(lat, lng));
		}
		
		if(cord_modal_state == "not_set") {
			var element = document.getElementById("map");
			var center = ($('#box-coordinates').val()) ? $('#box-coordinates').val() : new google.maps.LatLng(52.07275365395317, 5.15533447265625);
			var mapTypeIds = ["OSM"];
            for(var type in google.maps.MapTypeId)
                mapTypeIds.push(google.maps.MapTypeId[type]);
            markers = [];
			map = new google.maps.Map(element, {
			    center: center,
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
			
			google.maps.event.addListener(map, "click", function(event) {
				for(i in markers) markers[i].setMap(null);
	            var marker = new google.maps.Marker({
	                position: event.latLng, 
	                map: map,
	                draggable: true
	            });
	            map.setCenter(event.latLng);
	            markers.push(marker);
	            $('#modal_cord_lat').val(event.latLng.lat());
	            $('#modal_cord_lng').val(event.latLng.lng());
	            google.maps.event.addListener(marker, 'dragend', function(event) {
					$('#modal_cord_lat').val(event.latLng.lat());
		            $('#modal_cord_lng').val(event.latLng.lng());
				});
	        });
			
			if($('#boxes-cord_lat').val().length && $('#boxes-cord_lng').val().length && inrange(-90, $('#boxes-cord_lat').val(), 90) && inrange(-180, $('#boxes-cord_lng').val(), 180)) {
				setPreviousMarker();
			}
			
			cord_modal_state = "initialized";
		} else {
			setPreviousMarker();
		}

		$('#boxes-cord_format').val(<?= Boxes::CORD_FORMAT_WGS84 ?>);
			
	});

	function setPreviousMarker() {
		for(i in markers) markers[i].setMap(null);
		var previousPosition = {lat: parseFloat($('#boxes-cord_lat').val()), lng: parseFloat($('#boxes-cord_lng').val())};
		var marker = new google.maps.Marker({
            position: previousPosition, 
            map: map,
           	draggable: true
        });
		map.setCenter(previousPosition);
        map.setZoom(16);
        markers.push(marker);
        $('#modal_cord_lat').val(previousPosition.lat);
        $('#modal_cord_lng').val(previousPosition.lng);
        google.maps.event.addListener(marker, 'dragend', function(event) {
			$('#modal_cord_lat').val(event.latLng.lat());
            $('#modal_cord_lng').val(event.latLng.lng());
		});
        
	}

	/* Coördinate picker close event */
	$('#cord-container').on('hide.bs.modal', function (e) {
		$('#boxes-cord_lat').val($('#modal_cord_lat').val());
		$('#boxes-cord_lng').val($('#modal_cord_lng').val());
		$('#boxes-cord_format').trigger('change');
	});

	function inrange(min, number, max){
		if(!isNaN(number) && (number >= min) && (number <= max) )
		    return true;
		return false;
	}

	$('#boxForm').submit(function() {
		if($('#boxes-cord_format').val() == <?= Boxes::CORD_FORMAT_RD ?> && !inrange(-90, $('#boxes-cord_lat').val(), 90) && !inrange(-180, $('#boxes-cord_lng').val(), 180)) {
			var lat = $('#boxes-cord_lat').val(), lng = $('#boxes-cord_lng').val();
			$('#boxes-cord_lat').val(RD2lat(lat, lng));
			$('#boxes-cord_lng').val(RD2lng(lat, lng));
		}
		return;
	});

	$('#boxes-cord_format').change(function() {
		if($(this).val() == <?= Boxes::CORD_FORMAT_RD ?>) { 
			$('label[for="boxes-cord_lat"]').text($('#boxes-cord_lat').data('label-rd'));
			$('label[for="boxes-cord_lng"]').text($('#boxes-cord_lng').data('label-rd'));
		} else {
			$('label[for="boxes-cord_lat"]').text($('#boxes-cord_lat').data('label-wgs84'));
			$('label[for="boxes-cord_lng"]').text($('#boxes-cord_lng').data('label-wgs84'));
		}
	});
	
</script>
<?php View::endJs(); ?>
