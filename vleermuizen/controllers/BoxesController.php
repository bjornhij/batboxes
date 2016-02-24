<?php
namespace app\controllers;
use Yii;
use app\models\Users;
use app\models\Boxes;
use app\models\Projects;
use app\models\ProjectClusters;
use yii\helpers\Url;
use yii\web\UploadedFile;
use app\models\Boxtypes;

class BoxesController extends Controller {
	
	public function init() {
		$this->layout = (Yii::$app->user->isGuest) ? 'public' : 'admin';
	}
	
	public function behaviors() {
		return [
			'access' => [
				'class' => \yii\filters\AccessControl::className(),
				'only' => ['form', 'delete'],
				'rules' => [
					[
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
		];
	}
    
	public function actionIndex() {
		return $this->render('index');
	}

	public function actionForm($id = NULL) {
		$model = ($id) ? Boxes::findOne($id) : new \app\models\Boxes();
		
		if(Yii::$app->request->getQueryParam('project_id'))
			$model->project_id = Yii::$app->request->getQueryParam('project_id');
		
		if ($model->load(Yii::$app->request->post())) {
			$model->imageFile = UploadedFile::getInstance($model, 'imageFile');
			if($model->validate()) {
				// Clusters
				$cluster = Yii::$app->request->post('Boxes')['cluster'];
				if($cluster) {
					if(ProjectClusters::find()->byCluster($cluster)->exists()) {
						$model->cluster_id 		= ProjectClusters::find()->byCluster($cluster)->one()['id'];
					} else {
						$clusterModel 			= new ProjectClusters;
						$clusterModel->cluster 	= $cluster;
						$clusterModel->link('project', $model->project);						
						$clusterModel->save();
						$model->cluster_id 		= $clusterModel->id;
					}
				} else {
					$model->cluster_id = NULL;
				}
				
				/* Image */
				if($model->imageFile) 
					$model->upload();
					
				if($model->deleteImage && !$model->imageFile)
					$model->picture = NULL;
					
				$model->save();
				
				return $this->redirect(Url::toRoute('boxes/detail/'.$model->id));
			} else {
				if(isset($_POST['Boxes']['cluster']))
					$model->cluster = Yii::$app->request->post('Boxes')['cluster'];
			}
		}
		
		return $this->render('form', [
			'model' 	=> $model,
			'user'		=> $this->user,
			'users'		=> Users::find()->select(['id', new \yii\db\Expression("CONCAT(username, ' (', fullname, ')') as username")])->all(),
			'boxtypes'	=> Boxtypes::find()->all(),
			'projects'	=> Projects::find()->select(['id', 'name'])->hasRights()->all()
		]);
	}
	
	public function actionAjaxGetClusters($q = NULL, $pid = 0) {
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$results = [];
		$clusters = ProjectClusters::find()
					->select(['cluster as id', 'cluster as text'])
					->where(['project_id' => $pid])
					//->andWhere(['like', 'cluster', $q])
					->asArray(true)
					->all();

		$out = ['results' => $clusters];
		return $out;
	}
	
	public function actionDetail($id) {
		if(!($boxData = Boxes::findOne($id)))
			return $this->redirect(Url::toRoute('boxes/index'));
		
		if(!$boxData->isAuthorized())	
			return $this->redirect(Url::toRoute('boxes/index'));
		
		return $this->render('detail', ['box' => $boxData]);
	}
	
	
	public function actionDelete($id) {
		if(!($box = Boxes::findOne($id)) == false) {
			$box->deleted = true;
			$box->save(false);
		}
		return $this->redirect(Url::toRoute('boxes/index'));
	}
	
}