<?php
use fedemotta\datatables\DataTables;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Visits;
use app\models\Observations;
use app\models\Boxtypes;
use app\models\ProjectClusters;
?>
<div class="table-responsive">
	<?= DataTables::widget([
		'dataProvider' 	=> $dataProvider,
		'filterModel' 	=> $searchModel,
		'columns' => [
			[
				'attribute' => 'code',
				'format'	=> 'html',
				'value'		=> function($model, $key, $index, $column) { return Html::a($model['code'] , Url::toRoute('boxes/detail/'.$model['id'])); },
			],
			[
				'attribute' => 'boxtype_id',
				'format'	=> 'html',
				'label' 	=> 'Boxtype',
				'value'		=> function($model, $key, $index, $column) {
					$boxType = ($model['boxtype_id']) ? Boxtypes::findOne($model['boxtype_id']) : NULL;
					return ($boxType) ? Html::a($boxType->model, Url::toRoute('boxtypes/detail/'.$boxType->id)) : "-"; 
				},
			],
			[
				'attribute'	=> 'cluster_id',
				'format'	=> 'html',
				'label'		=> 'Cluster',
				'value'		=> function($model, $key, $index, $column) {
					$cluster = ($model['cluster_id']) ? ProjectClusters::findOne($model['cluster_id']) : NULL;
					return ($cluster) ? $cluster->cluster : '-';
				},
			],
			[
				'attribute' => 'placement_date',
				'label'		=> Yii::t('app', 'Plaatsingsdatum')
			],
			[
				'label' 	=> Yii::t('app', 'Laatste waarneming'),
				'value' 	=> function($model, $key, $index, $column) {
					if(($observationModel = Observations::find()->byBox($model['id'])->one()) !== NULL)
						return Visits::findOne($observationModel->visit_id)->date;
					else
						return "-";
				}
			]
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
	]); ?>
</div>