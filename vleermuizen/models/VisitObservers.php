<?php
namespace app\models;
/**
 * This is the model class for table "visits".
 *
 * @property integer 	$id
 * @property integer 	$visit_id
 * @property integer 	$observer_id
 * 
 */
class VisitObservers extends ActiveRecord {
	
	public static function tableName() {
		return 'visit_observers';
	}

	public function rules() {
		return [
			[['id', 'visit_id', 'observer_id'], 'required']
		];
	}

	public function attributeLabels() {
		return [
			'id' 			=> 'id',
			'visit_id' 		=> 'Visit id',
			'observer_id' 	=> 'Observer id',
		];
	}
	
	public function getVisit() {
		return $this->hasOne(Visits::className(), ['id' => 'visit_id']);
	}
	
	public function getObservers() {
		return $this->hasMany(Users::className(), ['id' => 'observer_id']);
	}
	
}