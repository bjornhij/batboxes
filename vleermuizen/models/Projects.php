<?php
namespace app\models;
use Yii;

/**
 * This is the model class for table "projects".
 *
 * @property integer 	$id
 * @property integer 	$owner_id
 * @property integer 	$main_observer_id
 * @property string 	$name
 * @property string 	$blur
 * @property date 		$embargo
 * @property string 	$remarks
 * @property string 	$date_created
 * @property string 	$date_updated
 * @property boolean 	$deleted
 *
 */

class Projects extends ActiveRecord {
	
	const BLUR_NONE 					= 0;
	const BLUR_100 						= 1;
	const BLUR_500 						= 2;
	const BLUR_NO_BOX_CODE 		= 3;
	
	const BLUR_DISTANCE_100				= 100;
	const BLUR_DISTANCE_500				= 500;
	
	public static function getBlurOptions() {
		return [
			self::BLUR_NONE 			=> Yii::t('app', 'Geen'),
			self::BLUR_100 				=> Yii::t('app', '100M'),
			self::BLUR_500 				=> Yii::t('app', '500M'),
			self::BLUR_NO_BOX_CODE => Yii::t('app', 'Geen kast nummer')
		];
	}
	
	public static function tableName(){
		return 'projects';
	}
	
	public static function find() {
		return (new \app\models\queries\ProjectsQuery(get_called_class()))->andWhere(['projects.deleted' => false]);
	}
	
	public static function getLastEntryWithIdentifier($project_id) {
		return Visits::find()->select('date')->where(['project_id' => $project_id])->orderBy('date DESC')->limit(1)->one();
	}
	
	public $boxcount = 0; /* query helper with default value */
	public $counters = [];
	
	public function init() {
		$this->owner_id 			= Yii::$app->user->id;
		$this->main_observer_id		= Yii::$app->user->id;
	}

	public function rules() {
		return [
			[['owner_id', 'main_observer_id', 'name', 'counters', 'blur'], 'required'], 
			[['owner_id', 'main_observer_id'], 'integer'],
			['name', 'string', 'max' => 45],
			['blur', 'number'], 
			['embargo', 'date', 'format' => 'php:d-m-Y'],
			['remarks', 'string'], 
			['deleted', 'boolean'],
			[['date_created', 'date_updated'], 'safe'],
			['name', 'unique', 'targetAttribute' => 'name'],
			['counters', function($attribute, $params){
				if(!in_array($this->main_observer_id, $this->$attribute))
					$this->addError($attribute, Yii::t('app', 'Telleider moet voorkomen in tellerslijst'));
			}],
		];
	}

	public function attributeLabels() {
		return [
			'id' 					=> Yii::t('app', 'ID'), 
			'owner_id' 				=> Yii::t('app', 'Eigenaar'), 
			'main_observer_id' 		=> Yii::t('app', 'Telleider'), 
			'name' 					=> Yii::t('app', 'Naam'),
			'blur' 					=> Yii::t('app', 'Vervaging'), 
			'embargo' 				=> Yii::t('app', 'Embargo'), 
			'remarks' 				=> Yii::t('app', 'Samenvatting'), 
			'date_created' 			=> Yii::t('app', 'Datum aangemaakt'), 
			'date_updated' 			=> Yii::t('app', 'Datum ge-update'), 
			'deleted' 				=> Yii::t('app', 'Verwijderd'),
		];
	}

	public function getOwner() {
		return $this->hasOne(Users::className() , ['id' => 'owner_id']);
	}

	public function getMainObserver() {
		return $this->hasOne(Users::className() , ['id' => 'main_observer_id']);
	}
	
	public function getBlur() {
		return Projects::getBlurOptions()[$this->blur];
	}
	
	public function getBlurInMeters() {
		switch($this->blur) {
			default: return 0; break;
			case self::BLUR_100: return self::BLUR_DISTANCE_100; break;
			case self::BLUR_500: return self::BLUR_DISTANCE_500; break;
		}
	}
	
	public function getClusters() {
		return $this->hasMany(ProjectClusters::className(), ['project_id' => 'id']);
	}
	
	public function getBoxes() {
		return $this->hasMany(Boxes::className(), ['project_id' => 'id']);
	}
	
	public function getProjectCounters() {
		return $this->hasMany(Users::className(), ['id' => 'user_id'])
		->viaTable('project_counters', ['project_id' => 'id']);
	}
	
	public function getLastEntry() {
		return Visits::find()->select('date')->where(['project_id' => $this->id])->orderBy('date DESC')->limit(1)->one();
	}
	
	public function showBlur($user_id = NULL) {
		if(is_null($user_id) && !Yii::$app->user->getId()) return true;
		$user_id = ($user_id) ? $user_id : Yii::$app->user->getId();
	
		if($this->owner_id == $user_id)
			return false;
	
		if($this->main_observer_id == $user_id)
			return false;

		if(ProjectCounters::find()->where(['and', ['project_id' => $this->id], ['user_id' => $user_id]])->exists())
			return false;

		return true;
	}
	
	public function isAuthorized($user_id = NULL) {
		$user_id = (Yii::$app->user->isGuest) ? 0 : Yii::$app->user->getId();
		
		if(is_object(Yii::$app->user->getIdentity()) && Yii::$app->user->getIdentity()->hasRole(['validator', 'administrator']))
			return true;
		
		if($this->owner_id == $user_id) 
			return true;
		
		if($this->main_observer_id == $user_id)
			return true;
		
		if(ProjectCounters::find()->where(['and', ['project_id' => $this->id], ['user_id' => $user_id]])->exists())
			return true;
			
		if((empty($this->embargo) || $this->embargo <= (new \DateTime())))
			return true;
	
		return false;
	}
	
	public function hasCounter($user_id) {
		return ProjectCounters::find()->where(['and', ['project_id' => $this->id], ['user_id' => $user_id]])->exists();
	}
	
}