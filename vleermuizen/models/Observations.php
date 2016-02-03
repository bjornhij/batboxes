<?php
namespace app\models;
use Yii;
use yii\bootstrap\Html;

/**
 * This is the model class for table "observations".
 *
 * @property integer 	$id
 * @property integer 	$visit_id
 * @property integer 	$box_id
 * @property integer 	$taxon_id
 * @property integer 	$species_id
 * @property integer 	$observation_type
 * @property integer 	$age
 * @property integer 	$sight_quantity
 * @property boolean 	$manure_collected
 * @property string 	$manure_collection
 * @property integer 	$manure_size
 * @property integer 	$manure_quantity
 * @property string 	$catch_weight
 * @property integer 	$catch_sex
 * @property string 	$catch_forearm_right
 * @property string 	$catch_forearm_left
 * @property integer 	$catch_sexual_status
 * @property integer 	$catch_parasite_id
 * @property boolean 	$catch_parasite_collected
 * @property string 	$catch_parasite_collection
 * @property string 	$catch_ring_code
 * @property string 	$catch_transponder_code
 * @property string 	$catch_radio_transmitter_code
 * @property string 	$catch_dna
 * @property string 	$picture
 * @property string 	$remarks
 * @property boolean 	$dead
 * @property boolean 	$deleted
 * @property string 	$date_created
 * @property string 	$date_updated
 * @property integer 	$validated_by_id
 * @property string 	$validated_date
 * @property integer	$number
 */

class Observations extends ActiveRecord {
 
	const OBSERVATION_TYPE_SIGHT 		= 1;
	const OBSERVATION_TYPE_MANURE 		= 2;
	const OBSERVATION_TYPE_CATCH 		= 3;
	const OBSERVATION_TYPE_NULL 		= 4;
	
	const SIGHT_AGE_UNKNOWN				= 1;
	const SIGHT_AGE_JUVENILE			= 2;
	const SIGHT_AGE_SUBADULT			= 3;
	const SIGHT_AGE_ADULT				= 4;
	
	const MANURE_SIZE_SMALL				= 1;
	const MANURE_SIZE_MEDIUM			= 2;
	const MANURE_SIZE_LARGE				= 3;
	
	const MANURE_QUANTITY_MUCH			= 1;
	const MANURE_QUANTITY_FEW			= 2;
	
	const CATCH_SEX_MALE				= 1;
	const CATCH_SEX_FEMALE				= 2;
	const CATCH_SEX_UNKNOWN				= 3;
	
	const CATCH_SEXUAL_STATUS_UNKOWN	= 0;
	const CATCH_SEXUAL_STATUS_M_SO		= 1;
	const CATCH_SEXUAL_STATUS_M_SA1		= 2;
	const CATCH_SEXUAL_STATUS_M_SA2		= 3;
	const CATCH_SEXUAL_STATUS_M_SA3		= 4;
	const CATCH_SEXUAL_STATUS_M_SV		= 5;
	const CATCH_SEXUAL_STATUS_F_SO		= 6;
	const CATCH_SEXUAL_STATUS_F_ZW		= 7;
	const CATCH_SEXUAL_STATUS_F_HZW		= 8;
	const CATCH_SEXUAL_STATUS_F_ZOG		= 9;
	const CATCH_SEXUAL_STATUS_F_PZOG	= 10;
	const CATCH_SEXUAL_STATUS_F_3		= 11;
	const CATCH_SEXUAL_STATUS_F_5		= 12;
    
    public static function getObservationTypes() {
    	return [
    		self::OBSERVATION_TYPE_SIGHT 	=> Yii::t('app', 'zicht'),
			self::OBSERVATION_TYPE_MANURE 	=> Yii::t('app', 'mest'),
    		self::OBSERVATION_TYPE_CATCH 	=> Yii::t('app', 'vangst'),
    		self::OBSERVATION_TYPE_NULL		=> Yii::t('app', 'null'),
    	];
    }

    public static function getAgeOptions() {
    	return [
			self::SIGHT_AGE_UNKNOWN 		=> Yii::t('app', 'onbekend'),
    		self::SIGHT_AGE_JUVENILE 		=> Yii::t('app', 'jong'),
    		self::SIGHT_AGE_SUBADULT 		=> Yii::t('app', 'jongvolwassen'),
    		self::SIGHT_AGE_ADULT 			=> Yii::t('app', 'volwassen'),
    	];
    }
    
    public static function getAgeById($id) {
    	return self::getAgeOptions()[$id];
    }
    
    public static function getManureSizeOptions() {
    	return [
    		self::MANURE_SIZE_SMALL 		=> Yii::t('app', 'klein'),
    		self::MANURE_SIZE_MEDIUM 		=> Yii::t('app', 'middel'),
    		self::MANURE_SIZE_LARGE 		=> Yii::t('app', 'groot'),
    	];
    }
    
    public static function getManureQuanityOptions() {
    	return [
    		self::MANURE_QUANTITY_MUCH 		=> Yii::t('app', 'veel (> 30)'),
    		self::MANURE_QUANTITY_FEW 		=> Yii::t('app', 'weinig (< 30)'),
    	];
    }
    
    public static function getCatchSexOptions() {
    	return [
    			self::CATCH_SEX_MALE 		=> Yii::t('app', 'man'),
    			self::CATCH_SEX_FEMALE 		=> Yii::t('app', 'vrouw'),
    			self::CATCH_SEX_UNKNOWN 	=> Yii::t('app', 'onbekend'),
    	];
    }
    
    public static function getSexById($id) {
    	return self::getCatchSexOptions()[$id];
    }
    
    public static function getCatchSexualStatusOptions($sex = NULL) {
    	$sexualOptions = [
    			self::CATCH_SEXUAL_STATUS_UNKOWN	=> Yii::t('app', 'Onbekend'),
    			self::CATCH_SEXUAL_STATUS_M_SO		=> Yii::t('app', 'SO'),
				self::CATCH_SEXUAL_STATUS_M_SA1		=> Yii::t('app', 'SA1'),
				self::CATCH_SEXUAL_STATUS_M_SA2		=> Yii::t('app', 'SA2'),
				self::CATCH_SEXUAL_STATUS_M_SA3		=> Yii::t('app', 'SA3'),
				self::CATCH_SEXUAL_STATUS_M_SV		=> Yii::t('app', 'SV'),
				self::CATCH_SEXUAL_STATUS_F_SO		=> Yii::t('app', 'SO'),
				self::CATCH_SEXUAL_STATUS_F_ZW		=> Yii::t('app', 'ZW'),
				self::CATCH_SEXUAL_STATUS_F_HZW		=> Yii::t('app', 'HZW'),
				self::CATCH_SEXUAL_STATUS_F_ZOG		=> Yii::t('app', 'ZOG'),
				self::CATCH_SEXUAL_STATUS_F_PZOG	=> Yii::t('app', 'PZOG'),
				self::CATCH_SEXUAL_STATUS_F_3		=> Yii::t('app', '3'),
				self::CATCH_SEXUAL_STATUS_F_5		=> Yii::t('app', '5'), 
    	];
   
    	if($sex == self::CATCH_SEX_MALE) return [ 
    		$sexualOptions[self::CATCH_SEXUAL_STATUS_M_SO],
    		$sexualOptions[self::CATCH_SEXUAL_STATUS_M_SA1],
    		$sexualOptions[self::CATCH_SEXUAL_STATUS_M_SA2],
    		$sexualOptions[self::CATCH_SEXUAL_STATUS_M_SA3],
    		$sexualOptions[self::CATCH_SEXUAL_STATUS_M_SV],
    	];
    	
    	if($sex == self::CATCH_SEX_FEMALE) return [
    		$sexualOptions[self::CATCH_SEXUAL_STATUS_F_SO],
    		$sexualOptions[self::CATCH_SEXUAL_STATUS_F_ZW],
    		$sexualOptions[self::CATCH_SEXUAL_STATUS_F_HZW],
    		$sexualOptions[self::CATCH_SEXUAL_STATUS_F_ZOG],
    		$sexualOptions[self::CATCH_SEXUAL_STATUS_F_PZOG],
    		$sexualOptions[self::CATCH_SEXUAL_STATUS_F_3],
    		$sexualOptions[self::CATCH_SEXUAL_STATUS_F_5],
    	];
   
    	return $sexualOptions;
    }
    
    public static function getSexualStatusById($id) {
    	return self::getCatchSexualStatusOptions()[$id];
    }
    
    public static function tableName() {
    	return 'observations';
    }
    
    public static function find() {
    	return (new \app\models\queries\ObservationsQuery(get_called_class()))->andWhere(['observations.deleted' => 'false']);
    }
    
    public static function createNullObservation($visit_id, $box_id) {
    	$observationModel 					= new Observations();
    	$observationModel->visit_id 		= $visit_id;
    	$observationModel->box_id 			= $box_id;
    	$observationModel->observation_type = self::OBSERVATION_TYPE_NULL;
    	
    	if($observationModel->isNewRecord)
    		$observationModel->number = $observationModel->visit->observation_counter +1;
    	
    	$observationModel->save(false);
    	
    	$observationModel->visit->observation_counter++;
    	$observationModel->visit->save();
    }
    
    public $pictureFile; // linked to the picture attribute
    public $deleteImage; // boolean to check if the current picture should be deleted
    
    public function beforeSave($insert) {
    	if(parent::beforeSave($insert)) {
	    	if($this->isNewRecord || is_null($this->number)) {
	    		$this->number = $this->visit->observation_counter +1;
				$this->visit->observation_counter++;
		    	$this->visit->save();
	    	}
	    	return true;
    	}
    	
    	return false;
    }
    
    public function rules() {
        return [
            [['visit_id', 'box_id', 'observation_type'], 'required'],
            [['visit_id', 'taxon_id', 'species_id', 'observation_type', 'age', 'sight_quantity', 'manure_size', 'manure_quantity', 'catch_sex', 'catch_sexual_status', 'catch_parasite_id', 'validated_by_id'], 'integer'],
            [['manure_collected', 'catch_parasite_collected', 'dead', 'deleted'], 'boolean'],
            [['catch_forearm_right', 'catch_forearm_left', 'number'], 'number'],
            [['remarks', 'catch_weight'], 'string'],
            [['date_created', 'date_updated', 'validated_date', 'number', 'deleteImage', 'ndff_id', 'ndff_failed.'], 'safe'],
            [['manure_collection', 'catch_parasite_collection', 'picture', 'catch_ring_code', 'catch_transponder_code', 'catch_radio_transmitter_code', 'catch_dna'], 'string', 'max' => 2044],
        	[['taxon_id', 'species_id'], 'required', 'when' => function(){
        		if(in_array($this->observation_type, [self::OBSERVATION_TYPE_CATCH, self::OBSERVATION_TYPE_SIGHT]))
        			return true;
        		return false;
        	}],
        ];
    }

    public function attributeLabels() {
        return [
            'id' 							=> Yii::t('app', '#'),
            'visit_id' 						=> Yii::t('app', 'Visit ID'),
        	'box_id' 						=> Yii::t('app', 'Kast'),
            'taxon_id' 						=> Yii::t('app', 'Soortgroep'),
            'species_id' 					=> Yii::t('app', 'Soort'),
            'observation_type' 				=> Yii::t('app', 'Type waarneming'),
            'age' 							=> Yii::t('app', 'Leeftijd'),
            'sight_quantity' 				=> Yii::t('app', 'Aantal'),
            'manure_collected' 				=> Yii::t('app', 'Verzameld'),
            'manure_collection' 			=> Yii::t('app', 'Collectie'),
            'manure_size' 					=> Yii::t('app', 'Grootte'),
            'manure_quantity' 				=> Yii::t('app', 'Hoeveelheid'),
            'catch_weight' 					=> Yii::t('app', 'Gewicht'),
            'catch_sex' 					=> Yii::t('app', 'Geslacht'),
            'catch_forearm_right' 			=> Yii::t('app', 'Onderarm rechts'),
            'catch_forearm_left' 			=> Yii::t('app', 'Onderarm links'),
            'catch_sexual_status' 			=> Yii::t('app', 'Sexuele status'),
            'catch_parasite_id' 			=> Yii::t('app', 'Parasieten'),
            'catch_parasite_collected' 		=> Yii::t('app', 'Verzameld'),
            'catch_parasite_collection' 	=> Yii::t('app', 'Collectie'),
            'catch_ring_code' 				=> Yii::t('app', 'Ringcode'),
            'catch_transponder_code' 		=> Yii::t('app', 'Transpondercode'),
            'catch_radio_transmitter_code' 	=> Yii::t('app', 'Radiozendercode'),
            'catch_dna' 					=> Yii::t('app', 'DNA'),        		
            'dead' 							=> Yii::t('app', 'Dood'),
        	'pictureFile' 					=> Yii::t('app', 'Afbeelding'),
        	'picture' 						=> Yii::t('app', 'Afbeelding'),
        	'deleteImage' 					=> Yii::t('app', 'Huidige afbeelding verwijderen?'),
        	'remarks' 						=> Yii::t('app', 'Opmerkingen'),
            'deleted' 						=> Yii::t('app', 'Verwijderd'),
            'date_created' 					=> Yii::t('app', 'Datum aangemaakt'),
            'date_updated' 					=> Yii::t('app', 'Datum aangepast'),
            'validated_by_id' 				=> Yii::t('app', 'Gevalideerd'),
            'validated_date' 				=> Yii::t('app', 'Validatie datum'),
        	'number'               			=> Yii::t('app', '#'),
        ];
    }
    
    public function getVisit() {
    	return $this->hasOne(Visits::className(), ['id' => 'visit_id']);
    }
    
    public function getTaxonomy() {
    	return Species::getTaxonomies()[$this->taxon_id];
    }
    
    public function getBox() {
    	return $this->hasOne(Boxes::className(), ['id' => 'box_id']);
    }
    
    public function getSpecies() {
    	return $this->hasOne(Species::className(), ['id' => 'species_id']);
    }
    
    public function getObservationType() {
    	return self::getObservationTypes()[$this->observation_type];
    }
    
    public function getAge() {
    	return self::getAgeOptions()[$this->age];
    }
    
    public function getManureCollected() {
    	return Yii::t('app', ($this->manure_collected) ? 'ja' : 'nee');
    }
    
    public function getManureSize() {
    	return self::getManureSizeOptions()[$this->manure_size];
    }
    
    public function getManureQuanitity() {
    	return self::getManureQuanityOptions()[$this->manure_quantity];
    }
    
    public function getSex() {
    	return self::getCatchSexOptions()[$this->catch_sex];
    }
    
    public function getSexualStatus() {
    	return self::getCatchSexualStatusOptions()[$this->catch_sex];
    }
    
    public function getParasite() {
    	return $this->hasOne(Species::className(), ['id' => 'catch_parasite_id']);
    }
    
    public function getDead() {
    	return Yii::t('app', ($this->dead) ? 'ja' : 'nee');
    }
    
    public function getPictureSet() {
    	return ($this->picture) ? true : false;
    }
    
    public function getValidated() {
    	if($this->validated_by_id)
    		return Yii::t('app', 'ja, door ') . Html::a($this->validator->username, Yii::$app->params['baseUrl']."/".$this->validator->username);
    	else 
    		return Yii::t('app', 'nee');
    }
    
    public function getValidator() {
    	return $this->hasOne(Users::className(), ['id' => 'validated_by_id']);
    }
    
    public function markAsValidated() {
    	$this->validated_by_id 	= Yii::$app->user->getId();
    	$this->validated_date 	= new \DateTime();
    	$this->save();
    }
    
public function isAuthorized() {
    	$user_id = (Yii::$app->user->isGuest) ? 0 : Yii::$app->user->getId();
    	
    	if(!is_object($this->box) || $this->box->deleted)
    		return false;
    	
    	if(!is_object($this->visit) || $this->visit->deleted)
    		return false;
		
		if(is_object(Yii::$app->user->getIdentity()) && Yii::$app->user->getIdentity()->hasRole(['validator', 'administrator']))
    		return true;	
    	
    	if(!$this->visit->isAuthorized())
    		return false;
    	
    	return true;
    }
    
	public function upload() {
		$filename = $this->pictureFile->baseName . '-' . uniqid() . '.' .$this->pictureFile->extension;
		$this->pictureFile->saveAs($_SERVER['DOCUMENT_ROOT'].'/uploads/observations/' . $filename);
		$this->picture = $filename;
    }
    
}
