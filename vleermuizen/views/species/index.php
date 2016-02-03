<?php
use app\models\Species;
use app\models\search\SpeciesSearch;
use fedemotta\datatables\DataTables;

use yii\helpers\Url;
use yii\bootstrap\Html;
use yii\grid\DataColumn;
$speciesModel 	= new Species();
$searchModel 	= new SpeciesSearch();
$dataProvider 	= $searchModel->search(Yii::$app->request->queryParams);

?>

<h1><?= Yii::t('app', 'Alle soorten') ?></h1>
<?= Html::a(Yii::t('app', 'Toevoegen'), Url::toRoute('species/form'), ['class' => 'btn btn-successbtn btn-success']) ?>

<br><br>
<?php 
DataColumn::className();
?>

<?= DataTables::widget([
	'dataProvider' 	=> $dataProvider,
	'filterModel' 	=> $searchModel,
	'columns' => [
		[
			'attribute' 	=> 'taxon',
			'value'			=> function($model, $key, $index, $column) { return $model->getTaxonomy($model->taxon); }
		],
		[
			'attribute'		=> 'genus',
			'format'		=> 'html',
			'value'			=> function($model, $key, $index, $column) { return Html::a($model->genus, Url::toRoute('species/detail/'.$model->id)); }
		],
		[
			'attribute'		=> 'speceus',
			'format'		=> 'html',
			'value'			=> function($model, $key, $index, $column) { return Html::a($model->speceus, Url::toRoute('species/detail/'.$model->id)); }
		],
		[
			'attribute'		=> 'dutch',
			'format'		=> 'html',
			'value'			=> function($model, $key, $index, $column) { return Html::a($model->dutch, Url::toRoute('species/detail/'.$model->id)); }
		],
		[
			'attribute'		=> 'url',
			'format'		=> 'html',
			'value'			=> function($model, $key, $index, $column) { return Html::a($model->url, $model->url); }
		],
	],
	'clientOptions' => [
		'info'			=> false,
		'responsive'	=> true,
		'dom' 			=> 'lfTrtip',
		'tableTools' => [
			'aButtons' => [
				[
					'sExtends'=> 'copy',
					'sButtonText'=> Yii::t('app','Copy to clipboard')
				],
				[
					'sExtends'=> 'csv',
					'sButtonText'=> Yii::t('app','Save to CSV')
				],
				[
					'sExtends'=> 'xls',
					'oSelectorOpts'=> ['page'=> 'current']
				],
				[
					'sExtends'=> 'pdf',
					'sButtonText'=> Yii::t('app','Save to PDF')
				],
				[
					'sExtends'=> 'print',
					'sButtonText'=> Yii::t('app','Print')
				],
			]
		]
	],
]);?>