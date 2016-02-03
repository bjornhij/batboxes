<?php
namespace app\controllers;
use Yii;
use app\models\Species;
use yii\helpers\Url;

class SpeciesController extends Controller {
	
	public function behaviors() {
		return [
			'access' => [
				'class' => \yii\filters\AccessControl::className(),
				'only' 	=> ['index', 'form', 'delete'], 
				'rules' => [
					[
						'allow' 	=> true,
						'actions' 	=> ['index', 'form', 'delete'],
						'roles'		=> ['administrator'],
					],
				]
			]
		];
	}
	
	public function actionIndex() {
		return $this->render('index', [
			'species' => Species::find()->all()
		]);
	}
	
	public function actionDetail($id) {
		return $this->render('detail', [
			'specie' => Species::findOne($id)
		]);
	}
	
	public function actionForm($id = NULL) {
		$model = ($id && Species::findOne($id)) ? Species::findOne($id) : new Species;
		
		if($model->load(Yii::$app->request->post()) && $model->validate()) {
			$model->save(false);
			return $this->redirect(Url::toRoute('species/detail/'.$model->id));
		}
		
		return $this->render('form', [
			'model' => $model
		]);
	}
	
	public function actionDelete($id) {
		$model = Species::findOne($id);
		if($model && !$model->observations) {
			$model->deleted = true;
			$model->save(false);
		}
		return $this->redirect(Url::toRoute('species/index'));
	}
	
}