<?php
namespace app\models;
use Yii;
use app\components\WGS84;

/**
 * This is the model class for table "boxes".
 *
 * @property integer 	$id
 * @property integer 	$project_id
 * @property integer 	$boxtype_id
 * @property integer 	$cluster_id
 * @property string 	$code
 * @property string 	$placement_date
 * @property string 	$removal_date
 * @property string 	$cord_lat
 * @property string 	$cord_lng
 * @property string 	$location
 * @property string 	$province
 * @property string 	$placement_height
 * @property string 	$direction
 * @property string 	$picture
 * @property string 	$remarks
 * @property string 	$date_created
 * @property string 	$date_updated
 * @property boolean 	$deleted
 *
 */

class Boxes extends ActiveRecord {
	
	const DIRECTION_UNKNOWN 	= 1;
	const DIRECTION_NORTH 		= 2;
	const DIRECTION_NORTH_WEST 	= 3;
	const DIRECTION_NORTH_EAST 	= 4;
	const DIRECTION_WEST 		= 5;
	const DIRECTION_SOUTH_WEST 	= 6;
	const DIRECTION_SOUTH 		= 7;
	const DIRECTION_SOUTH_EAST 	= 8;
	const DIRECTION_EAST 		= 9;
	
	const CORD_FORMAT_WGS84		= 1;
	const CORD_FORMAT_RD		= 2;
	
	public static function getDirectionOptions() {
		return [
			self::DIRECTION_UNKNOWN 	=> Yii::t('app', 'Onbekend'),
			self::DIRECTION_NORTH 		=> Yii::t('app', 'N'),
			self::DIRECTION_NORTH_WEST 	=> Yii::t('app', 'NW'),
			self::DIRECTION_NORTH_EAST 	=> Yii::t('app', 'NO'),
			self::DIRECTION_WEST 		=> Yii::t('app', 'W'),
			self::DIRECTION_SOUTH_WEST 	=> Yii::t('app', 'ZW'),
			self::DIRECTION_SOUTH 		=> Yii::t('app', 'Z'),
			self::DIRECTION_SOUTH_EAST 	=> Yii::t('app', 'ZO'),
			self::DIRECTION_EAST 		=> Yii::t('app', 'O'),
		];
	}
	
	public static function getCordFormats() {
		return [
			self::CORD_FORMAT_WGS84 => Yii::t('app', 'WGS84'),
			self::CORD_FORMAT_RD	=> Yii::t('app', 'Rijksdriehoek')	
		];
	}
	
	public static function tableName() {
        return 'boxes';
    }
    
    public static function find() {
    	return (new \app\models\queries\BoxesQuery(get_called_class()))->where(['boxes.deleted' => false]);
    }
    
    public $imageFile;
    public $cluster;
    public $cord_format;
    public $deleteImage;
   
    public function afterFind() {
    	if(!empty($this->cluster_id))
    		$this->cluster = ProjectClusters::findOne($this->cluster_id)['cluster'];
    	
    	parent::afterFind();
    }
    
    public function rules() {
        return [
            [['code', 'cord_lat', 'cord_lng', 'province'], 'required'],
            [['project_id', 'boxtype_id'], 'integer'],
            [['placement_date', 'removal_date', 'date_created', 'date_updated', 'cluster_id', 'deleteImage'], 'safe'],
            [['remarks', 'placement_height'], 'string'],
            [['deleted'], 'boolean'],
            [['code', 'location'], 'string', 'max' => 45],
            [['cord_lat', 'cord_lng'], 'string', 'max' => 50],
            ['province', 'string', 'max' => 20],
            [['direction'], 'string', 'max' => 2],
            [['picture'], 'string', 'max' => 100],
        	['placement_height', 'trim'],
        	['project_id', 'required', 'when' => function() {
        		return ($this->isNewRecord) ? true : false;
        	}],
			['code', function ($attribute, $params) {
				if(isset($this->project)) {
					$uniqueQuery = Boxes::find()->where(['and', ['code' => $this->$attribute], ['project_id' => $this->project->id]]);
					if(!$this->isNewRecord) 
						$uniqueQuery->andWhere(['<>', 'id', $this->id]);
        			if($uniqueQuery->exists())
        				$this->addError($attribute, Yii::t('app', 'Kastcode bestaat al binnen dit project.'));
				}
        	}],
        	['placement_date', function ($attribute, $params) {
            	if($this->placement_date >= $this->removal_date && !empty($this->removal_date))
            		$this->addError($attribute, Yii::t('app', 'Plaatsingsdatum mag niet na de verwijderdatum liggen.'));
            }],
            ['removal_date', function ($attribute, $params) {
            	if($this->removal_date <= $this->placement_date && !empty($this->removal_date))
            		$this->addError($attribute, Yii::t('app', 'Verwijderdatum mag niet voor de plaatsingsdatum liggen.'));
            }],
            ['cord_lat', function ($attribute, $params) {
            	if(!WGS84::inrange(-90, $this->cord_lat, 90))
            		$this->addError($attribute, Yii::t('app', 'Latitude coördinaat is niet valide'));
            }],
            ['cord_lng', function ($attribute, $params) {
            	if(!WGS84::inrange(-180, $this->cord_lng, 180))
            		$this->addError($attribute, Yii::t('app', 'Longitude coördinaat is niet valide'));
            }],
            ['project_id', function($attribute, $params) {
            	if(!Projects::find()->andWhere(['projects.id' => $this->$attribute])->hasRights()->exists() && $this->isNewRecord)
            		return $this->addError($attribute, Yii::t('app', 'U heeft geen toegang tot het geselecteerde project'));
            }]
        ];
    }

    public function attributeLabels() {
        return [
            'id' 				=> Yii::t('app', '#'),
            'project_id' 		=> Yii::t('app', 'Project'),
            'boxtype_id' 		=> Yii::t('app', 'Kasttype'),
            'cluster_id' 		=> Yii::t('app', 'Cluster'),
            'code' 				=> Yii::t('app', 'Code'),
            'placement_date' 	=> Yii::t('app', 'Plaatsingsdatum'),
            'removal_date' 		=> Yii::t('app', 'Verwijderdatum'),
        	'cord_format'		=> Yii::t('app', 'Coördinaten indeling'),
            'cord_lat' 			=> Yii::t('app', 'Coördinaten latitude'),
            'cord_lng' 			=> Yii::t('app', 'Coördinaten longitude'),
            'location' 			=> Yii::t('app', 'Lokatie'),
            'province' 			=> Yii::t('app', 'Provincie'),
            'placement_height' 	=> Yii::t('app', 'Plaatsingshoogte (m)'),
            'direction' 		=> Yii::t('app', 'Kompasrichting'),
            'picture' 			=> Yii::t('app', 'Afbeelding'),
        	'imageFile'			=> Yii::t('app', 'Afbeelding'),
        	'deleteImage'		=> Yii::t('app', 'Huidige afbeelding verwijderen?'),
            'remarks' 			=> Yii::t('app', 'Opmerkingen'),
            'date_created' 		=> Yii::t('app', 'Datum aangemaakt'),
            'date_updated' 		=> Yii::t('app', 'Datum ge-update'),
            'deleted' 			=> Yii::t('app', 'Verwijderd'),
        ];
    }

    public function getCluster() {
        return $this->hasOne(ProjectClusters::className(), ['id' => 'cluster_id']);
    }

    public function getProject() {
        return $this->hasOne(Projects::className(), ['id' => 'project_id']);
    }
    
    public function getBoxtypes() {
    	return $this->hasOne(Boxtypes::className(), ['id' => 'boxtype_id']);
    }
    
    public function getDirection() {
    	return self::getDirectionOptions()[$this->direction];
    }
    
    public function getPlacementHeight() {
    	return $this->placement_height . " m";
    }
    
	public function isAuthorized() {
		if($this->project->isAuthorized() && !$this->deleted) return true;
		return false;
	}
    
    public function upload() {
		$this->imageFile->saveAs($_SERVER['DOCUMENT_ROOT'].'/uploads/boxes/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
		$this->picture = $this->imageFile->baseName.".".$this->imageFile->extension;
    }
    
}
