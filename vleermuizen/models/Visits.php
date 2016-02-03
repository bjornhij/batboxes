<?php
namespace app\models;
use Yii;

/**
 * This is the model class for table "visits".
 *
 * @property integer 	$id
 * @property integer 	$project_id
 * @property integer 	$observer_id
 * @property date 		$date
 * @property integer 	$count_completeness
 * @property integer 	$checked_all
 * @property integer 	$box_open
 * @property integer 	$cleaned
 * @property integer 	$blur
 * @property integer 	$embargo
 * @property string 	$remarks
 * @property boolean 	$deleted
 * @property string 	$date_created
 * @property string 	$date_updated
 * @property integer	$observation_counter;
 *
 */

class Visits extends ActiveRecord {
	
	const COUNT_COMPLETENESS_EXACT 		= 1;
	const COUNT_COMPLETENESS_ESTIMATED 	= 2;
	
	public $checked_boxes = [];

	public static function getCountCompletenessOptions() {
		return [
			self::COUNT_COMPLETENESS_EXACT 		=> Yii::t('app', 'exact'),
			self::COUNT_COMPLETENESS_ESTIMATED 	=> Yii::t('app', 'schatting'),
		];
	}
	
    public static function tableName() {
        return 'visits';
    }
    
    public static function find() {
    	return (new \app\models\queries\VisitsQuery(get_called_class()))->where(['visits.deleted' => false]);
    }
    
    public function rules() {
        return [
            [['project_id', 'date', 'box_open', 'cleaned', 'checked_all'], 'required'],
            [['project_id', 'observer_id', 'count_completeness', 'checked_all', 'blur', 'box_open', 'cleaned'], 'integer'],
        	['embargo', 'date', 'format' => 'php:d-m-Y'],
        	['date', 'date', 'format' => 'php:d-m-Y', 'max' => strtotime("23:59 today"), 'tooBig' => Yii::t('app', 'Datum mag niet in de toekomst liggen.')],
            [['observer_id', 'date_created', 'date_updated'], 'safe'],
            [['remarks'], 'string'],
            [['deleted'], 'boolean'],
        	['observer_id', 'default', 'value' => Yii::$app->user->getId()],
        	['checked_boxes', 'required',  'when' => function($model) {
        		return (!$model->checked_all && $model->isNewRecord) ? true : false;
        	}],
        ];
    }
    
    public function attributeLabels() {
        return [
            'id'                    => Yii::t('app', 'ID'),
            'project_id'            => Yii::t('app', 'Project'),
            'observer_id'           => Yii::t('app', 'Waarnemer'),
            'date'                  => Yii::t('app', 'Datum van waarnemingen'),
        	'checked_all'			=> Yii::t('app', 'Alle kasten gecontroleerd?'),
            'remarks'               => Yii::t('app', 'Opmerkingen'),
            'deleted'               => Yii::t('app', 'Verwijderd'),
            'date_created'          => Yii::t('app', 'Datum aangemaakt'),
            'date_updated'          => Yii::t('app', 'Datum update'),
            'box_open'           	=> Yii::t('app', 'Kasten geopend tijdens controle?'),
            'cleaned'               => Yii::t('app', 'Kasten schoongemaakt?'),
            'count_completeness'    => Yii::t('app', 'Kwaliteit waarneming'),
            'blur'                  => Yii::t('app', 'Vervaging'),
            'embargo'               => Yii::t('app', 'Embargo'),
        	'observation_counter'   => Yii::t('app', 'Observationcounter'),
        ];
    }

    public function getObserver() {
        return $this->hasOne(Users::className(), ['id' => 'observer_id']);
    }
    
    public function getProject() {
    	return $this->hasOne(Projects::className(), ['id' => 'project_id']);
    }
    
    public function getObservations() {
    	return $this->hasMany(Observations::className(), ['visit_id' => 'id']);
    }
    
    public function getVisitBox() {
    	return $this->hasMany(VisitBoxes::className(), ['visit_id' => 'id']);
    }
    
    public function getCheckedAll() {
    	return Yii::t('app', ($this->checked_all) ? "ja" : "nee");
    }
    
    public function getBoxOpen() {
    	return Yii::t('app', ($this->box_open) ? "ja" : "nee");
    }
    
    public function getCleaned() {
    	return Yii::t('app', ($this->cleaned) ? "ja" : "nee");
    }
    
    public function getCountCompleteness() {
    	return self::getCountCompletenessOptions()[$this->count_completeness];
    }
    
    public function getBlur() {
    	return Projects::getBlurOptions()[$this->blur];
    }
    
    public function getBlurInMeters() {
    	switch($this->blur) {
    		default: $visitBlur = NULL; break;
    		case Projects::BLUR_100: $visitBlur = Projects::BLUR_DISTANCE_100; break;
    		case Projects::BLUR_500: $visitBlur = Projects::BLUR_DISTANCE_500; break;
    	}
    	
    	$projectBlur = $this->project->getBlurInMeters();
    	 
    	if($projectBlur || $visitBlur)
    		return ($projectBlur > $visitBlur) ? $projectBlur : $visitBlur;
    }
    
    public function showBlur() {
    	return ($this->getBlurInMeters()) ? true : false;
    }
    
    public function isAuthorized() {
    	$user_id = (Yii::$app->user->isGuest) ? 0 : Yii::$app->user->getId();
    	
    	if(!is_object($this->project) || $this->project->deleted)
    		return false;
		
		if(is_object(Yii::$app->user->getIdentity()) && Yii::$app->user->getIdentity()->hasRole(['validator', 'administrator']))
    		return true;    		
    	
    	if(!$this->project->isAuthorized())
    		return false;	
    	
    	if($this->observer_id == $user_id)
    		return true;
    		
		if((empty($this->embargo) || $this->embargo <= (new \DateTime())))
			return true;
    	
    	return false;
    }
    
}
