<?php
namespace app\models;
use Yii;

/**
 * This is the model class for table "visit_boxes".
 *
 * @property integer $id
 * @property integer $visit_id
 * @property integer $box_id
 *
 */

class VisitBoxes extends \yii\db\ActiveRecord {
	
    public static function tableName() {
        return 'visit_boxes';
    }
    
    public static function find() {
    	return new \app\models\queries\VisitBoxesQuery(get_called_class());
    }

    public function rules() {
        return [
            [['visit_id', 'box_id'], 'integer'],
            [['box_id'], 'unique'],
            [['visit_id'], 'unique']
        ];
    }

    public function attributeLabels() {
        return [
            'id' 		=> 'ID',
            'visit_id' 	=> 'Visit ID',
            'box_id' 	=> 'Box ID',
        ];
    }

    public function getBox() {
        return $this->hasOne(Boxes::className(), ['id' => 'box_id']);
    }

    public function getVisit() {
        return $this->hasOne(Visits::className(), ['id' => 'visit_id']);
    }
    
}
