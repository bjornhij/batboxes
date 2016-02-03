<?php
use yii\bootstrap\Nav;
$this->beginContent('@app/views/layouts/main.php'); 
?>
<div class="row">
	<?php /* 
	<div class="col-md-3 sub-nav hidden-sm hidden-xs">
		<?php 
		$items = [
			[
				'label' => Yii::t('app', 'Dashboard'),
				'url' => ['#'],
				'options' => ['class' => 'category'],
			],
			[
				'label' => Yii::t('app', 'Mijn Vleermuiskasten.nl'),
				'url' => ['index/overview'],
			],
	        [
	            'label' => Yii::t('app', 'Waarnemingen'),
	            'url' => ['#'],
	        	'options' => ['class' => 'category'],
	        ],
			[
				'label' => Yii::t('app', 'Mijn Waarnemingen'),
				'url' => ['observations/personal'],
			],
			[
				'label' => Yii::t('app', 'Toevoegen'),
				'url' => ['visits/form']
			],
			[
				'label' => Yii::t('app', 'Overzicht'),
				'url' => ['observations/index'],
			],
			[
				'label' => Yii::t('app', 'Bezoeken'),
				'url' => ['#'],
				'options' => ['class' => 'category'],
			],
			[
				'label' => Yii::t('app', 'Mijn Bezoeken'),
				'url' => ['visits/personal'],
			],
			[
				'label' => Yii::t('app', 'Overzicht'),
				'url' => ['visits/index'],
			],
			[
				'label' => Yii::t('app', 'Projecten'),
				'url' => ['#'],
				'options' => ['class' => 'category'],
			],
			[
				'label' => Yii::t('app', 'Mijn Projecten'),
				'url' => ['projects/personal'],
			],
			[
				'label' => Yii::t('app', 'Toevoegen'),
				'url' => ['projects/form'],
			],
			[
				'label' => Yii::t('app', 'Overzicht'),
				'url' => ['projects/index'],
			],
			[
				'label' => Yii::t('app', 'Kasten'),
				'url' => ['#'],
				'options' => ['class' => 'category'],
			],
			[
				'label' => Yii::t('app', 'Alle Kasten'),
				'url' => ['boxes/index'],
			],
			[
				'label' => Yii::t('app', 'Toevoegen'),
				'url' => ['boxes/form'],
			],
		];
		
		if(Yii::$app->user->getIdentity()->hasRole(['validator', 'administrator'])) {
			array_push($items, [
				'label' => Yii::t('app', 'Validator'),
				'url' => ['#'],
				'options' => ['class' => 'category'],
			]);
			array_push($items, [
				'label' => Yii::t('app', 'Overzicht'),
				'url' => ['validator/index'],
			]);	
		}
		
		if(Yii::$app->user->getIdentity()->hasRole('administrator')) {
			array_push($items, [
					'label' => Yii::t('app', 'Admin'),
					'url' => ['#'],
					'options' => ['class' => 'category'],
			]);
			array_push($items, [
				'label' => Yii::t('app', 'Soortgroepen'),
				'url' => ['species/index'],
			]);
			array_push($items, [
				'label' => Yii::t('app', 'Kasttypen'),
				'url' => ['boxtypes/index'],
			]);
		}
		
    	echo Nav::widget([
    		'items' 	=> $items,
    		'options' 	=> ['class' =>'nav-pills'],
		]); ?>
	</div> */ ?>
	<div class="col-xs-12 content">
		<?= $content ?>
	</div>
</div>
<?php $this->endContent(); ?>