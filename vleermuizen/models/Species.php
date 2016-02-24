<?php
namespace app\models;
use Yii;

/**
 * This is the model class for table "specius".
 *
 * @property integer 	$id
 * @property string		$taxon
 * @property string 	$speceus
 * @property string 	$dutch
 * @property string 	$url
 * @property string 	$ndff_species_url
 */

class Species extends ActiveRecord {

	const TAXONOMY_BAT 			= 1; 
	const TAXONOMY_BIRD 		= 2;
	const TAXONOMY_ARTHROPOD 	= 3;
	
	public static function find() {
		return (new \app\models\queries\SpeciesQuery(get_called_class()))->andWhere(['deleted' => false]);
	}
	
    public static function tableName() {
        return 'species';
    }
    
    public static function getTaxonomies($onlyWithSpecies = false) {
    	$taxonomies = [
			self::TAXONOMY_BAT 			=> Yii::t('app', 'vleermuis'),  		
			self::TAXONOMY_BIRD 		=> Yii::t('app', 'vogel'),
			self::TAXONOMY_ARTHROPOD 	=> Yii::t('app', 'geleedpotige'),
    	];
    	
    	if($onlyWithSpecies)    	
	 		foreach(array_keys($taxonomies) as $tax)
	 			if(!self::find()->andWhere(['taxon' => $tax])->exists())
	 				unset($taxonomies[$tax]);
    	
 		return $taxonomies;
    }
    
    public static function getTaxonomy($index) {
    	return self::getTaxonomies()[$index];
    }
    
    public function rules() {
        return [
            [['taxon', 'genus', 'speceus', 'dutch'], 'required'],
            [['genus', 'speceus', 'dutch'], 'string', 'max' => 45],
        	[['url', 'ndff_species_url'], 'string'],
        	['taxon', 'integer'],
        	['taxon', 'in', 'range' => array_keys(self::getTaxonomies())]
        ];
    }
    
    public function attributeLabels() {
        return [
            'id' 		=> Yii::t('app', 'ID'),
            'taxon' 	=> Yii::t('app', 'Soortgroep'),
        	'genus' 	=> Yii::t('app', 'Genus'),
            'speceus' 	=> Yii::t('app', 'Species'),
        	'dutch' 	=> Yii::t('app', 'Nederlandse vertaling'),
        	'url' 		=> Yii::t('app', 'Link naar Drupal node'),
        ];
    }
    
    public function getObservations() {
    	return $this->hasmany(Observations::className(), ['species_id' => 'id']);
    }
    
}
