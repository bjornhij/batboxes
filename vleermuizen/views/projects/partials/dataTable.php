<?php
use fedemotta\datatables\DataTables;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Projects;
?>
<div class="table-responsive">
	<?= DataTables::widget([
		'dataProvider' 	=> $dataProvider,
		'filterModel' 	=> $searchModel,
		'columns' => [
			[
				'attribute' => 'name',
				'label'		=> Yii::t('app', 'Projectnaam'),
				'value'		=> function($model, $key, $index, $column) { return Html::a($model['name'], Url::toRoute('projects/detail/'.$model['id'])); },
				'format'	=> 'html'
			],
			[
				'label' 	=> Yii::t('app', 'Laatste invoer'),
				'value' 	=> function($model, $key, $index, $column) { return (Projects::getLastEntryWithIdentifier($model['id'])) ? Projects::getLastEntryWithIdentifier($model['id'])['date'] : "-"; }
			],
			[
				'label' 	=> Yii::t('app', 'Aantal kasten'),
				'value' 	=> function($model, $key, $index, $column) { return $model['boxcount']; }
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