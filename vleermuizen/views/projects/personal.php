<?php
use app\models\search\ProjectsSearch;
use yii\helpers\Url;
$searchModel = new ProjectsSearch();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);
?>
<h1>
	<?= Yii::t('app', 'Mijn projecten') ?>
	<?php if(Yii::$app->user->can('createProject')) : ?>
		<a href="<?= Url::toRoute('projects/form') ?>" class="btn btn-success pull-right"><?= Yii::t('app', 'Toevoegen') ?></a>
	<?php endif; ?>
</h1>
<?= $this->render('partials/dataTable.php', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]) ?>