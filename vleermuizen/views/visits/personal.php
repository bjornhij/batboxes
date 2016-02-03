<?php
use app\models\search\VisitsSearch;
$searchModel = new VisitsSearch();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams, ['personal' => true]);
?>
<h1><?= Yii::t('app', 'Mijn bezoeken') ?></h1>
<?= $this->render('partials/dataTable.php', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]) ?>