<?php
use app\models\Boxtypes;
use app\models\search\BoxtypesSearch;
use fedemotta\datatables\DataTables;
use yii\helpers\Url;
use yii\bootstrap\Html;
$boxtypeModel 	= new Boxtypes();
$searchModel 	= new BoxtypesSearch();
$dataProvider 	= $searchModel->search(Yii::$app->request->queryParams);
?>

<h1><?= Yii::t('app', 'Alle kasttypen') ?></h1>
<a href="<?= Url::toRoute('boxtypes/form') ?>" class="btn btn-success"><?= Yii::t('app', 'Toevoegen') ?></a>

<br><br>

<?= DataTables::widget([
	'dataProvider' 	=> $dataProvider,
	'filterModel' 	=> $searchModel,
	'columns' => [
		[
			'attribute' => 'model',
			'value' 	=> function($model, $key, $index, $column) { 
				return Html::a($model->model, Url::toRoute("boxtypes/detail/".$model->id)); 
			},
			'format' 	=> 'html',
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