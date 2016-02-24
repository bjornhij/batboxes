<?php
namespace app\controllers;
use Yii;
use yii\helpers\Url;
use app\models\Boxes;
use app\models\Visits;
use app\models\Projects;
use app\models\VisitBoxes;
use app\models\Observations;
use app\models\VisitObservers;

class VisitsController extends Controller {
	
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
		$visit = Visits::findOne($id);
		
		if(!is_object($visit) || !$visit->isAuthorized())
			return $this->redirect(Url::toRoute('visits/index'));
		
		return $this->render('detail', [
			'visit' 		=> $visit,
			'observations' 	=> Observations::find()->all()
		]);
	}
	
	public function actionForm($id = NULL) {
		$model = ($id) ? Visits::findOne($id) : new Visits;
	
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			
			/* Check if it is a new record before we save it  */
			$newRecord = $model->isNewRecord;
			
			/* Attempt to save model */
			if($model->save(false)) {
				
				/* Only when the record is new */
				if($newRecord) {
				
					/* Register null-observations for un-checked boxes if available */
					if(!$model->checked_all)
						foreach($model->project->boxes as $box)
							if(in_array($box->id, $model->checked_boxes))
								Observations::createNullObservation($model->id, $box->id);
					
					/* Register checked boxes */
					if($model->checked_boxes) {
						$model->unlinkAll('visitBox', true);
						foreach($model->checked_boxes as $checkedBox) {
							$visitBoxModel 			= new VisitBoxes();
							$visitBoxModel->box_id 	= $checkedBox;
							$visitBoxModel->link('visit', $model);
							$visitBoxModel->save();
						}
					}
					
					/* Register null-observations for all boxes in project when "checked-all" is selected */
					if($model->checked_all)
						foreach($model->project->boxes as $box)
							Observations::createNullObservation($model->id, $box->id);
				
				}
				
				
				/* Register observers by this visit */
				if($model->observers) {
					$model->unlinkAll('observers', true);
					foreach($model->observers as $observer) {
						$visitObservers					= new VisitObservers();
						$visitObservers->observer_id 	= $observer;
						$visitObservers->link('visit', $model);
						$visitObservers->save();
					}
				}

				/* Redirect on success */
				return $this->redirect(Url::toRoute('visits/detail/'.$model->id));
					
			}
				
		}
		
		return $this->render('form', [
			'model' 	=> $model,
			'projects'	=> Projects::find()->hasRights()->hasBoxes()->all()
		]);
	}
	
	
	public function actionAjaxGetBoxes($q = NULL, $pid = 0, $vid = NULL) {
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	
		$boxes = Boxes::find()
		->select(['id', 'code'])
		->byProject($pid)
		->all();
		
		return $boxes;
	}
	
	public function actionDelete($id) {
		$model = Visits::findOne($id);
		if($model) {
			$model->deleted = true;
			$model->save(false);
		}
		foreach($model->observations as $observation) {
			$observation->deleted = true;
			$observation->save(false);
		}
		return $this->redirect(Url::toRoute('visits/personal'));
	}
	
}