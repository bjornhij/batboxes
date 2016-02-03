<?php
use app\models\search\VisitsSearch;
$searchModel = new VisitsSearch();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
?>
<h1><?= Yii::t('app', 'Alle bezoeken') ?></h1>
<?= $this->render('partials/dataTable.php', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]) ?>