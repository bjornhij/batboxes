<?php
namespace app\controllers;
use Yii;
use app\models\Boxtypes;
use app\models\Users;
use yii\helpers\Url;
use yii\web\UploadedFile;
use app\models\BoxtypeEntrances;

class BoxtypesController extends Controller {
	
	public function behaviors() {
		return [
			'access' => [
				'class' 	=> \yii\filters\AccessControl::className(),
				'rules' 	=> [
					[
						'allow' 	=> true,
						'roles' 	=> ['administrator'],
					],
					[
						'actions'	=> ['detail'],
						'allow' 	=> true,
					]
				],
			],
		];
	}
	
	public function actionIndex() {
		return $this->render('index', [
			'boxtypes' => Boxtypes::find()->all()
		]);
	}
	
	public function actionDetail($id) {
		return $this->render('detail', [
			'boxtype' => Boxtypes::findOne($id)
		]);
	}
	
	public function actionForm($id = NULL) {
		$model = ($id && Boxtypes::findOne($id)) ? Boxtypes::findOne($id) : new Boxtypes;
		
		if($model->load(Yii::$app->request->post())) {
			
			/* Uploads */
			$model->pictureFile 		= UploadedFile::getInstance($model, 'pictureFile');
			$model->buildingplanFile 	= UploadedFile::getInstance($model, 'buildingplanFile');
			
			if($model->validate()) {
				
				/* Picture */
				if($model->pictureFile) 
					$model->uploadPicture();
				if($model->deleteImage && !$model->pictureFile)
					$model->picture = NULL;
					
				/* Buildingplan */
				if($model->buildingplanFile)
					$model->uploadBuildingplan();
				if($model->deleteBuildingplan && !$model->buildingplanFile)
					$model->buildingplan = NULL;
				
				/* Save the model */
				$model->save(false);
				
				/* Unlink all previous entries */
				$model->unlinkAll('boxtypeEntrances', true);
				
				/* Save all the selected visit methods */
				if(isset($_POST['Boxtypes']['entrances'])) {
					foreach(Yii::$app->request->post('Boxtypes')['entrances'] as $entrance) {
						$entrancesModel					= new BoxtypeEntrances();
						$entrancesModel->entrance_index = $entrance;
						
						if($entrance == Boxtypes::BOX_ENTRANCES_OTHER)
							$entrancesModel->other = Yii::$app->request->post('Boxtypes')['entrance_other'];
						
						$entrancesModel->link('boxtypes', $model);
						$entrancesModel->save();
					}
				}
				
				return $this->redirect(Url::toRoute('boxtypes/detail/'.$model->id));
			} else {
				/* Save checked entrances */
				if(isset($_POST['Boxtypes']['entrances']))
					foreach(Yii::$app->request->post('Boxtypes')['entrances'] as $entrance)
						$model->checked_entrances[$entrance] = ($entrance != Boxtypes::BOX_ENTRANCES_OTHER) ? true : Yii::$app->request->post('Boxtypes')['entrance_other'];
			}
		}
		
		return $this->render('form', [
			'model' 	=> $model,
			'users'		=> Users::find()->select(['id', new \yii\db\Expression("CONCAT(username, ' (', fullname, ')') as username")])->all(),
		]);
	}
	
	public function actionDelete($id) {
		$model = Boxtypes::findOne($id);
		if($model && !$model->boxes) {
			$model->deleted = true;
			$model->save(false);
		}
		return $this->redirect(Url::toRoute('boxtypes/index'));
	}
	
}