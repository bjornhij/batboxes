<?php
namespace app\models;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "boxtypes".
 *
 * @property integer $id
 * @property integer $manufacturer_id
 * @property string $model
 * @property integer $shape
 * @property integer $shape_other
 * @property integer $chamber_count
 * @property integer $material
 * @property integer $material_other
 * @property integer $dropping_board
 * @property string $dropping_board_other
 * @property string $picture
 * @property string $buildingplan
 * @property numeric $height
 * @property numeric $depth
 * @property numeric $width
 * @property numeric $entrances_width
 * @property numeric $entrances_height
 * @property numeric $minimal_crevice_width
 * @property numeric $maximal_crevice_width
 * @property string $date_created
 * @property string $date_updated
 * @property boolean $deleted
 *
 * @property Users $manufacturer
 */

class Boxtypes extends ActiveRecord {
	
	/* Box shapes */
	const BOX_SHAPE_SMALL_FLAT 			= 1;
	const BOX_SHAPE_SMALL_ROUND 		= 2;
	const BOX_SHAPE_LARGE 				= 3;
	const BOX_SHAPE_BOOTH 				= 4;
	const BOX_SHAPE_WINTER 				= 5;
	const BOX_SHAPE_HEATED				= 6;
	const BOX_SHAPE_BUILD_IN			= 7;
	const BOX_SHAPE_INCLUDED_BUILD		= 8;
	const BOX_SHAPE_OTHER 				= 99;
	
	/* Box materials */
	const BOX_MATERIAL_WOOD 			= 1;
	const BOX_MATERIAL_WOOD_CONCRETE 	= 2;
	const BOX_MATERIAL_CONCRETE 		= 3;
	const BOX_MATERIAL_OTHER			= 99;
	
	/* Box entrancess */
	const BOX_ENTRANCES_UNDER			= 1;
	const BOX_ENTRANCES_FRONT 			= 2;
	const BOX_ENTRANCES_OTHER			= 99;
	
	/* Box dropping boards */
	const BOX_DROPPING_BOARD_YES		= 1;
	const BOX_DROPPING_BOARD_NO			= 2;
	const BOX_DROPPING_BOARD_HALF		= 3;
	const BOX_DROPPING_BOARD_LID		= 4;
	const BOX_DROPPING_BOARD_OTHER		= 99;
    
	/* File upload properties */
	public $pictureFile;
	public $buildingplanFile;
	public $entrances;
	public $deleteBuildingplan;
	public $deleteImage;
	
    public static function tableName() {
        return 'boxtypes';
    }
    
    public function afterFind() {
        $this->entrances = ArrayHelper::map($this->boxtypeEntrances, 'entrance_index', 'entrance_index');
        parent::afterFind();
    }
    
    public static function getBoxShapes() {
    	return [
    		self::BOX_SHAPE_SMALL_FLAT 			=> Yii::t('app', 'Klein plat'),
    		self::BOX_SHAPE_SMALL_ROUND 		=> Yii::t('app', 'Klein bol'),
    		self::BOX_SHAPE_LARGE 				=> Yii::t('app', 'Groot (meerdere compartimenten)'),
    		//self::BOX_SHAPE_BOOTH 				=> Yii::t('app', 'Kraam'),
    		self::BOX_SHAPE_WINTER 				=> Yii::t('app', 'Winter'),
    		self::BOX_SHAPE_HEATED				=> Yii::t('app', 'Verwarmd'),
    		self::BOX_SHAPE_BUILD_IN			=> Yii::t('app', 'Inbouw'),
    		self::BOX_SHAPE_INCLUDED_BUILD		=> Yii::t('app', 'Inclusief Bouwen'),
    		self::BOX_SHAPE_OTHER 				=> Yii::t('app', 'Anders, namelijk'),
    	];
    }
    
    public static function getBoxShape($index) {
    	return self::getBoxShapes()[$index];
    }
    
    public static function getChamberBoxtypeModels() {
    	return [
    		self::BOX_SHAPE_SMALL_FLAT,
    		self::BOX_SHAPE_SMALL_ROUND
    	];
    }
    
    public static function getBoxMaterials() {
    	return [
    		self::BOX_MATERIAL_WOOD 			=> Yii::t('app', 'Hout'),
    		self::BOX_MATERIAL_WOOD_CONCRETE 	=> Yii::t('app', 'Houtbeton'),
    		self::BOX_MATERIAL_CONCRETE 		=> Yii::t('app', 'Beton'),
    		self::BOX_MATERIAL_OTHER 			=> Yii::t('app', 'Anders, namelijk'),
    	];
    }
    
    public static function getBoxMaterial($index) {
    	return self::getBoxMaterials()[$index];
    }
    
    public static function getBoxEntrances() {
    	return [
    		self::BOX_ENTRANCES_UNDER 			=> Yii::t('app', 'Onder'),
    		self::BOX_ENTRANCES_FRONT 			=> Yii::t('app', 'Voor'),
    		self::BOX_ENTRANCES_OTHER 			=> Yii::t('app', 'Anders, namelijk'),
    	];
    }
    
    public static function getBoxEntrance($index) {
    	return self::getBoxEntrances()[$index];
    }
    
    public static function getBoxDroppingBoards() {
    	return [
    		self::BOX_DROPPING_BOARD_YES 		=> Yii::t('app', 'Ja'),
    		self::BOX_DROPPING_BOARD_NO 		=> Yii::t('app', 'Nee'),
    		self::BOX_DROPPING_BOARD_HALF 		=> Yii::t('app', 'Half'),
    		self::BOX_DROPPING_BOARD_LID 		=> Yii::t('app', 'Klep'),
    		self::BOX_DROPPING_BOARD_OTHER 		=> Yii::t('app', 'Anders, namelijk'),
    	];
    }
    
    public static function getBoxDroppingBoard($index) {
    	return self::getBoxDroppingBoards()[$index];
    }
   
    public static function find(){
    	return (new \app\models\queries\BoxtypesQuery(get_called_class()))->where(['deleted' => false]);
    }
    
    public $checked_entrances;

    public function rules() {
        return [
            [['manufacturer_id', 'shape', 'chamber_count', 'material', 'dropping_board'], 'integer'],
        	[['height', 'depth', 'width', 'entrance_width', 'entrance_height', 'minimal_crevice_width', 'maximal_crevice_width'], 'number'],
            [['model'], 'required'],
        	[['model'], 'unique'],
            [['model', 'shape_other', 'material_other', 'dropping_board_other', 'picture', 'buildingplan'], 'string'],
            [['date_created', 'date_updated', 'deleteImage', 'deleteBuildingplan'], 'safe'],
            [['deleted'], 'boolean'],
        	['pictureFile', 'image', 'extensions' => ['jpg', 'jpeg', 'png']],
        	['buildingplanFile', 'image', 'extensions' => ['jpg', 'jpeg', 'png']],
        ];
    }

    public function attributeLabels() {
        return [
            'id' 						=> Yii::t('app', 'ID'),
            'manufacturer_id' 			=> Yii::t('app', 'Producent'),
            'model' 					=> Yii::t('app', 'Model'),
            'shape' 					=> Yii::t('app', 'Vorm'),
        	'shape_other' 				=> Yii::t('app', 'Vorm, anders namelijk'),
            'chamber_count' 			=> Yii::t('app', 'Aantal kamers'),
            'material' 					=> Yii::t('app', 'Materiaal'),
            'dropping_board' 			=> Yii::t('app', 'Landingsbord'),
            'dropping_board_other' 		=> Yii::t('app', 'Landingsbord, anders namelijk'),
            'picture' 					=> Yii::t('app', 'Afbeelding'),
        	'pictureFile'				=> Yii::t('app', 'Afbeelding'),
        	'deleteImage'				=> Yii::t('app', 'Verwijder huidige afbeelding?'),
            'buildingplan' 				=> Yii::t('app', 'Bouwplan'),
        	'buildingplanFile'			=> Yii::t('app', 'Bouwplan'),
        	'deleteBuildingplan'		=> Yii::t('app', 'Verwijder huidig bouwplan?'),
            'height' 					=> Yii::t('app', 'Hoogte (cm)'),
            'depth' 					=> Yii::t('app', 'Diepte (cm)'),
            'width' 					=> Yii::t('app', 'Breedte (cm)'),
            'entrance_width' 			=> Yii::t('app', 'Ingangsbreedte (mm)'),
            'entrance_height' 			=> Yii::t('app', 'Ingangshoogte (mm)'),
            'minimal_crevice_width' 	=> Yii::t('app', 'Minimale spleet breedte (mm)'),
            'maximal_crevice_width' 	=> Yii::t('app', 'Maximale spleet hoogte (mm)'),
            'date_created' 				=> Yii::t('app', 'Datum aangemaakt'),
            'date_updated' 				=> Yii::t('app', 'Datum geüpdatet'),
            'deleted' 					=> Yii::t('app', 'Verwijderd'),
        ];
    }

    public function getManufacturer() {
        return $this->hasOne(Users::className(), ['id' => 'manufacturer_id']);
    }
    
    public function getBoxes() {
    	return $this->hasMany(Boxes::className(), ['boxtype_id' => 'id']);
    }
    
    public function getBoxtypeEntrances() {
    	return $this->hasMany(BoxtypeEntrances::className(), ['boxtype_id' => 'id']);
    }
    
    public function uploadPicture() {
		$this->pictureFile->saveAs($_SERVER['DOCUMENT_ROOT'].'/uploads/boxtypes/pictures/' . $this->pictureFile->baseName . '.' . $this->pictureFile->extension);
		$this->picture = $this->pictureFile->baseName.".".$this->pictureFile->extension;
    }

    public function uploadBuildingplan() {
    	$this->buildingplanFile->saveAs($_SERVER['DOCUMENT_ROOT'].'/uploads/boxtypes/buildingplans/' . $this->buildingplanFile->baseName . '.' . $this->buildingplanFile->extension);
    	$this->buildingplan = $this->buildingplanFile->baseName.".".$this->buildingplanFile->extension;
    }
    
}
