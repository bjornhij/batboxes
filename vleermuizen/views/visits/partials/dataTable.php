<?php
use fedemotta\datatables\DataTables;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="table-responsive">
	<?= DataTables::widget([
		'dataProvider' 	=> $dataProvider,
		'filterModel' 	=> $searchModel,
		'columns' => [
			[
				'attribute' => 'project_id',
				'label'		=> Yii::t('app', 'Project'),
				'value'		=> function($model, $key, $index, $column) {
					return Html::a($model->project->name, Url::toRoute('projects/detail/'.$model->project_id));
				},
				'format'	=> 'html'
			],
			[
				'attribute' => 'date',
				'label'		=> Yii::t('app', 'Datum'),
				'value'		=> function($model, $key, $index, $column) {
					return Html::a($model->date, Url::toRoute('visits/detail/'.$model->id));
				},
				'format'	=> 'html'
			],
			[
				'label' 	=> Yii::t('app', 'Waarnemer(s)'),
				'value' 	=> function($model, $key, $index, $column) {
					if($model->getObservers()->exists()) {
						$returnArray = [];
						foreach($model->getObservers()->all() as $observer)
							$returnArray[] = $observer->username;
						
						return implode($returnArray, ', ');
					}
					return '#VERWIJDERD';
				},
				'format' 	=> 'html',
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
	]);?>
</div>