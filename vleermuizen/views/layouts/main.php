<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use app\assets\AppAsset;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<header>
	<div class="container">
		<?php Yii::$app->drupal->navigation(); ?>
		<div class="banner"></div>
    </div>
</header>
<main>
	<div class="container">
		<?= $content ?>
	</div>
</main>
<footer>
	<div class="container">
		<div class="region region-footer">
			<?= Yii::$app->drupal->footer(); ?>
		</div>
	</div>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
