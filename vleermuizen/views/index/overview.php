<?php 
use app\models\search\ProjectsSearch;
use app\models\search\VisitsSearch;
use app\models\search\ObservationsSearch;
?>
<h1><?= Yii::t('app', 'Mijn Vleermuiskasten.nl')?></h1>
<h2><?= Yii::t('app', 'Mijn projecten')?></h2>
<?php
$searchModel 	= new ProjectsSearch;
$dataProvider 	= $searchModel->search(Yii::$app->request->queryParams, true);
echo $this->render('/projects/partials/dataTable.php', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]) 
?>

<h2><?= Yii::t('app', 'Mijn bezoeken')?></h2>
<?php
$searchModel 	= new VisitsSearch();
$dataProvider 	= $searchModel->search(Yii::$app->request->queryParams, ['personal' => true]);
echo $this->render('/visits/partials/dataTable.php', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]) 
?>

<h2><?= Yii::t('app', 'Mijn waarnemingen')?></h2>
<?php
$searchModel 	= new ObservationsSearch();
$dataProvider 	= $searchModel->search(Yii::$app->request->queryParams, ['personal' => true]);
echo $this->render('/observations/partials/dataTable.php', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]) 
?>