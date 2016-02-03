<?php
namespace app\controllers;
use Yii;
use app\models\Visits;
use app\models\Boxes;
use app\models\Species;
use app\models\Projects;
use app\models\Observations;
use app\models\VisitBoxes;
use yii\helpers\Url;
use yii\web\UploadedFile;

class ObservationsController extends Controller {
	
	public function behaviors() {
		return [
			'access' => [
				'class' => \yii\filters\AccessControl::className(),
				'only' => ['personal', 'form', 'validate', 'delete'],
				'rules' => [
					[
						'actions' 	=> ['personal', 'form'],
						'allow' 	=> true,
						'roles' 	=> ['@'],
					],
					[
						'actions'	=> ['validate'],
						'allow'		=> true,
						'roles'		=> ['validator', 'administrator'],
						'matchCallback' => function ($rule, $action) {
							return Yii::$app->user->can('updateObservation');
						}
					],
					[
						'actions'	=> ['delete'],
						'allow'		=> true,
						'roles'		=> ['authenticated user', 'validator', 'administrator'],
						'matchCallback' => function ($rule, $action) {
							return Yii::$app->user->can('deleteObservation', [
								'observation' => Observations::findOne(substr(Yii::$app->getRequest()->pathInfo, (strrpos(Yii::$app->getRequest()->pathInfo, '/') +1)))	
							]);
						}
					]
				],
			],
		];
	}
	
	public function actionIndex() {
		return $this->render('index', [
		    'visits' => Visits::find()->all()
		]);
	}
	
	public function actionPersonal() {
		return $this->render('personal');
	}
	
	public function actionForm($id = NULL) {
		$model = ($id) ? Observations::findOne($id) : new Observations();
		
		if(Yii::$app->getRequest()->getQueryParam('visit_id') && is_null($id))
			$model->visit_id = Yii::$app->getRequest()->getQueryParam('visit_id');
		
		if($model->load(Yii::$app->request->post())) {			
			
			/* Load picture file */
			$model->pictureFile = UploadedFile::getInstance($model, 'pictureFile');
			
			/* Validate model */
			if($model->validate()) {
				
				/* Upload picture */
				if($model->pictureFile)
					$model->upload();
				
				/* Delete picture if selected */
				if($model->deleteImage && !$model->pictureFile)
					$model->picture = NULL;
				
				/* Check for auto-validation on observqation */
				if(Yii::$app->user->getIdentity()->hasRole(['validator', 'administrator']))
					$model->markAsValidated();
				
				/* When the observation type equals a null-observation, clear all other fields */
				if($model->observation_type == Observations::OBSERVATION_TYPE_NULL)
					foreach($model->attributes as $attribute => $value)
						if(!in_array($attribute, ['id', 'visit_id', 'observation_type', 'validated_by_id', 'validated_date', 'box_id', 'date_created', 'date_updated', 'deleted'])) $model->$attribute = NULL;
						
				$model->save(false);
				
				if($model->observation_type != Observations::OBSERVATION_TYPE_NULL && Observations::find()->where(['and', ['deleted' => false], ['box_id' => $model->box_id], ['observation_type' => Observations::OBSERVATION_TYPE_NULL]])->exists())
					foreach(Observations::find()->where(['and', ['deleted' => false], ['observation_type' => Observations::OBSERVATION_TYPE_NULL]])->all() as $nullObservation)
						$nullObservation->delete();
				
				return $this->redirect(Url::toRoute(['visits/detail/'.$model->visit_id]));
				
			}
			
		}
		
		return $this->render('form', [
			'model' 	=> $model,
			'boxes'	=> Boxes::find()->where(['project_id' => Visits::findOne($model->visit_id)->project_id])->all(),
			'species'	=> Species::find()->asArray()->all(),
			'parasites'	=> Species::find()->where(['taxon' => Species::TAXONOMY_ARTHROPOD])->all()
		]);
	}
	
	public function actionDetail($id) {
		if(!($observationData = Observations::findOne($id)))
			return $this->redirect(Url::toRoute('database/index'));
		
		if(!$observationData->isAuthorized())
			return $this->redirect(Url::toRoute('database/index'));
			
		return $this->render('detail', ['observation' => $observationData]);
	}
	
	public function actionDelete($id) {
		if(!($visit = Observations::findOne($id)) == false) {
			$visit->deleted = true;
			$visit->save();
		}
		return $this->redirect(Url::toRoute('observations/index'));
	}

	public function actionValidate($id) {
		if(!($observation = Observations::findOne($id)) == false)
			$observation->markAsValidated();
			
		return $this->redirect(Url::toRoute('observations/detail/'.$id));
	}
	
}
