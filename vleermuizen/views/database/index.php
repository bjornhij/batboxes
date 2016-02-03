<?php
use app\models\search\DatabaseSearch;
$searchModel 	= new DatabaseSearch();
$dataProvider 	= $searchModel->search(Yii::$app->request->queryParams); 
?>

<h1><?= Yii::t('app', 'Database')?></h1>
<?= $this->render('partials/dataTable.php', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]) ?>