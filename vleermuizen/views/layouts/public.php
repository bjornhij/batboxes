<?php
use yii\bootstrap\Nav;
$this->beginContent('@app/views/layouts/main.php'); 
?>
<div class="row">
	<?php /*
	<div class="col-md-3 sub-nav hidden-sm hidden-xs">
		<?= Nav::widget([
		'items' => [
			[
				'label' => Yii::t('app', 'Navigatie'),
				'url' => ['#'],
				'options' => ['class' => 'category'],
			],
	        [
	            'label' => Yii::t('app', 'Projecten'),
	            'url' => ['projects/index'],
	        ],
			[
				'label' => Yii::t('app', 'Kasten'),
				'url' => ['boxes/index'],
			],
			[
				'label' => Yii::t('app', 'Bezoeken'),
				'url' => ['visits/index'],
			],
			[
				'label' => Yii::t('app', 'Waarnemingen'),
				'url' => ['observations/index'],
			],
    	],
    	'options' => ['class' =>'nav-pills'],
		]); ?>
	</div> */ ?>
	<div class="col-xs-12 content">
		<?= $content ?>
	</div>
</div>
<?php $this->endContent(); ?>