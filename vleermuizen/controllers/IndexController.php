<?php
namespace app\controllers;
use Yii;

class IndexController extends Controller {

    public function actionIndex() {
    	$this->redirect(Yii::$app->params['baseUrl']);
    }
    
    public function actionOverview() {
    	$this->layout = 'admin';
    	return $this->render('overview');
    }
    
    public function actionDrupalUser($id) {
    	Yii::$app->drupal->registerDrupalUser($id);
    }
    
}
