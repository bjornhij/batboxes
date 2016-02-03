<?php
use fedemotta\datatables\DataTables;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Projects;
use app\models\Observations;
?>
<div class="table-responsive">
	<?= DataTables::widget([
		'dataProvider' 	=> $dataProvider,
		'filterModel' 	=> $searchModel,
		'columns' => [
			[
				'attribute' => 'number',
				'label'		=> 'ID',
				'value' 	=> function($model, $key, $index, $column) {
					return Html::a($model->number, Url::toRoute('/observations/detail/'.$model->id));
				},
				'format' 	=> 'html',
			],
			[
				'attribute' => 'box_id',
				'value' 	=> function($model, $key, $index, $column) {
					if($model->box->project->blur != Projects::BLUR_NO_BOX_CODE && $model->visit->blur != Projects::BLUR_NO_BOX_CODE)
						return Html::a($model->box->code, Url::toRoute('/boxes/detail/'.$model->box_id));
					else
						return Yii::t('app', 'verborgen');
				},
				'format' 	=> 'html',
			],
			[
				'attribute' => 'species_id',
				'value' 	=> function($model, $key, $index, $column) {
					if($model->observation_type == Observations::OBSERVATION_TYPE_MANURE) {
						return $model->getObservationType();
					}
					return ($model->species) ? Html::a($model->species->dutch, Url::to('/species/detail/'.$model->species_id)) : '';
				},
				'format' 	=> 'html',
			],
			[
				'attribute' => 'sight_quantity',
				'label'		=> '#',
				'value'		=> function($model, $key, $index, $column) {
					return ($model->sight_quantity) ? $model->sight_quantity : 0;
				}
			],
			[ 
				'attribute' => 'picture',
				'label'		=> '',
				'value'		=> function($model, $key, $index, $column) {
					return ($model->getPictureSet()) ? '<img src="/images/haspicture.png" height="20" alt="picture">' : '';
				},
				'format' 	=> 'html',
				'contentOptions'	=> [
					'align' => 'center',
					'width' => '25'		
				]
			],
			[
				'attribute' => 'validated_by_id',
				'label'		=> ' ',
				'value'		=> function($model, $key, $index, $column) {
					return ($model->validated_by_id) ? '<img src="/images/check.png" alt="check" height="20">' : '';
				},
				'format'			=> 'html',
				'contentOptions'	=> [
					'align' => 'center',
					'width' => '20'
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