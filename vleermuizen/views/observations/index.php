<?php
use app\models\search\ObservationsSearch;
$searchModel 	= new ObservationsSearch();
$dataProvider 	= $searchModel->search(Yii::$app->request->queryParams);
?>

<h1><?= Yii::t('app', 'Alle waarnemingen') ?></h1>

<?= $this->render('/observations/partials/dataTable.php', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]) ?>