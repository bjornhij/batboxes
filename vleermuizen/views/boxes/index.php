<?php
use app\models\search\BoxesSearch;
use yii\helpers\Url;
$searchModel = new BoxesSearch();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
?>

<h1>
	<?= Yii::t('app', 'Alle kasten') ?>
	<?php if(Yii::$app->user->can('boxCRUDRights')) : ?>
		<a href="<?= Url::toRoute('boxes/form') ?>" class="btn btn-success pull-right"><?= Yii::t('app', 'Toevoegen') ?></a>
	<?php endif; ?>
</h1>

<?= $this->render('partials/dataTable.php', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]) ?>