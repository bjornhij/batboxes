<?php
namespace app\models;
use Yii;

/**
 * This is the model class for table "boxtype_entrances".
 *
 * @property integer 	$id
 * @property integer 	$boxtype_id
 * @property string 	$entrance_index
 * @property string 	$other
 */

class BoxtypeEntrances extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'boxtype_entrances';
    }
    
    public static function find() {
    	return new \app\models\queries\BoxtypeEntrancesQuery(get_called_class());
    }

    public function rules() {
        return [
            [['boxtype_id', 'entrance_index'], 'required'],
            [['boxtype_id'], 'integer'],
            [['entrance_index'], 'number'],
            [['other'], 'string']
        ];
    }

    public function attributeLabels() {
        return [
            'id' 				=> Yii::t('app', 'ID'),
            'boxtype_id' 		=> Yii::t('app', 'Boxtype ID'),
            'entrance_index' 	=> Yii::t('app', 'entrance_index'),
            'other' 			=> Yii::t('app', 'Other'),
        ];
    }
    
    public function getBoxtypes() {
    	return $this->hasMany(Boxtypes::className(), ['id' => 'boxtype_id']);
    }
    
}
