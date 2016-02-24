<?php
use app\models\search\ObservationsSearch;
use fedemotta\datatables\DataTables;
use yii\helpers\Url;
use yii\helpers\Html;
$searchModel 	= new ObservationsSearch();
$dataProvider 	= $searchModel->search(Yii::$app->request->queryParams, ['validated' => false, 'show_null' => false]);
?>

<h1><?= Yii::t('app', 'Niet gevalideerde waarnemingen') ?></h1>
<div class="table-responsive">
	<?= DataTables::widget([
		'dataProvider' 	=> $dataProvider,
		'filterModel' 	=> $searchModel,
		'columns' => [
			[
				'attribute' => 'date',
				'value'		=> function($model, $key, $index, $column) {
					return Html::a($model->visit->date, Url::toRoute('/observations/detail/'.$model->id));
				},
				'format' 	=> 'html',
			],
			[
				'label'		=> Yii::t('app', 'Project'),
				'value' 	=> function($model, $key, $index, $column) {
					return Html::a($model->box->project->name, Url::toRoute('/projects/detail/'.$model->box->project->id));
				},
				'format' 	=> 'html',
			],
			[
				'attribute' => 'box_id',
				'value' 	=> function($model, $key, $index, $column) {
					return Html::a($model->box->code, Url::toRoute('/boxes/detail/'.$model->box_id));
				},
				'format' 	=> 'html',
			],
			[
				'label' 	=> Yii::t('app', 'Waarnemer(s)'),
				'value' 	=> function($model, $key, $index, $column) {
					if($model->visit->getObservers()->exists()) {
						$returnArray = [];
						foreach($model->visit->getObservers()->all() as $observer)
							$returnArray[] = $observer->username;
						
						return implode($returnArray, ', ');
					}
					return '#VERWIJDERD';
				},
				'format' 	=> 'html',
			],
			[
				'attribute' => 'observation_type',
				'value' 	=> function($model, $key, $index, $column) {
					return $model->getObservationType();
				}
			],
			[
				'attribute' => 'species_id',
				'value' 	=> function($model, $key, $index, $column) {
					return Html::a($model->species->dutch, Url::toRoute('/species/detail/'.$model->species_id));
				},
				'format' 	=> 'html',
			],
			'sight_quantity',
			[ 
				'label'		=> '',
				'attribute' => 'picture',
				'value'		=> function($model, $key, $index, $column) {
					return ($model->getPictureSet()) ? '<img src="/images/haspicture.png" height="20" alt="picture">' : '';
				},
				'format'	=> 'html',
				'contentOptions'	=> [
					'align' => 'center',
					'width' => '25'		
				]
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
</div>