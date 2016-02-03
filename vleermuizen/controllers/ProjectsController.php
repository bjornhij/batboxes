<?php
namespace app\controllers;
use Yii;
use app\models\Projects;
use app\models\Users;
use app\models\forms\ProjectForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

class ProjectsController extends Controller {
	
	public function init() {
		$this->layout = (Yii::$app->user->isGuest) ? 'public' : 'admin';
	}
	
	public function behaviors() {
		return [
			'access' => [
				'class' => \yii\filters\AccessControl::className(),
				'only' => ['personal', 'form', 'delete'], 
				'rules' => [
					[
						'actions' 	=> ['personal', 'form', 'delete'],
						'allow' 	=> true,
						'roles' 	=> ['@'],
					],
				],
			],
		];
	}
	
	public function actionIndex() {
		return $this->render('index');
	}
	
	public function actionPersonal() {
		return $this->render('personal');
	}
	
	public function actionDetail($id) {
		if(!($projectData = Projects::findOne($id)))
			return $this->redirect(Url::toRoute('projects/index'));	
		
		if(!$projectData->isAuthorized())
			return $this->redirect(Url::toRoute('projects/index'));
		
		return $this->render('detail', ['project' => $projectData]);
	}

	public function actionForm($id = NULL) {
		if(Yii::$app->user->can('createProject')) {
			$model 				= ($id) ? Projects::findOne($id) : new Projects();
			$model->counters 	= ($model->projectCounters) ? array_keys(ArrayHelper::map($model->projectCounters, 'id', 'username')) : [Yii::$app->user->getId()];			
			
			if (!($model->load(Yii::$app->request->post()) && $model->validate())) {	
				return $this->render('form', [
					'model' 	=> $model,
					'users'		=> Users::find()->select(['id', 'username', 'fullname'])->all()
				]);
			}
			
			$model->save(false);
			
			/* Project counters */
			$model->unlinkAll('projectCounters', true);
			foreach(Yii::$app->request->post('Projects')['counters'] as $countUser)
				if(!($countUser = Users::findOne($countUser)) == false)
					$countUser->link('counterProjects', $model);
				
			return $this->redirect(Url::toRoute('projects/detail/'.$model->id));
		}
		
		return $this->redirect(Url::toRoute('projects/index'));
	}
	
	public function actionDelete($id) {
		if(!($project = Projects::findOne($id)) == false) {
			if(Yii::$app->user->can('deleteProject', ['project' => $project])) {
				$project->deleted = true;
				$project->save(false);
			}
		}
		return $this->redirect(Url::toRoute('projects/index'));
	}
	
}