<?php
namespace app\controllers;
use Yii;

class Controller extends \yii\web\Controller {
	
	public $user;
	public $layout 	= 'public';

	public function beforeAction($action) {
		\Yii::$app->drupal->checkIdentity();
		if (!Yii::$app->user->isGuest) {
			$this->user 	= Yii::$app->user->getIdentity();
			$this->layout 	= 'admin';
		}
		return parent::beforeAction($action);
	}

	public function flash($type, $message) {
		Yii::$app->getSession()->setFlash($type, $message);
	}
	
	protected function setTitle($title) {
		$this->view->title = $title . " | " . Yii::$app->name;
	}
	
}