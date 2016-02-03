<?php
namespace app\models;
use Yii;

/**
 * This is the model class for table "project_counters".
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $user_id
 *
 */

class ProjectCounters extends ActiveRecord {

    public static function tableName() {
        return 'project_counters';
    }

    public static function find() {
    	return new \app\models\queries\ProjectCountersQuery(get_called_class());
    }

    public function rules() {
        return [
            [['project_id', 'user_id'], 'required'],
            [['project_id', 'user_id'], 'integer'],
            [['project_id', 'user_id'], 'unique', 'targetAttribute' => ['project_id', 'user_id'], 'message' => 'The combination of Project ID and User ID has already been taken.']
        ];
    }

    public function attributeLabels() {
        return [
            'id' 			=> Yii::t('app', 'ID'),
            'project_id' 	=> Yii::t('app', 'Project ID'),
            'user_id' 		=> Yii::t('app', 'User ID'),
        ];
    }

    public function getProject() {
        return $this->hasOne(Projects::className(), ['id' => 'project_id']);
    }

    public function getUser() {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
    
}
