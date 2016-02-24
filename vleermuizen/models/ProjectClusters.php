<?php
namespace app\models;
use Yii;

/**
 * This is the model class for table "project_clusters".
 *
 * @property integer $id
 * @property integer $project_id
 * @property string $cluster
 *
 * @property Boxes[] $boxes
 * @property Projects $project
 */

class ProjectClusters extends \yii\db\ActiveRecord {
	
	public $text;
	
    public static function tableName() {
        return 'project_clusters';
    }

    public function rules() {
        return [
            [['project_id', 'cluster'], 'required'],
            [['project_id'], 'integer'],
            [['cluster'], 'string'],
            [['project_id', 'cluster'], 'unique', 'targetAttribute' => ['project_id', 'cluster'], 'message' => 'The combination of Project ID and Cluster has already been taken.']
        ];
    }

    public function attributeLabels() {
        return [
            'id' 			=> Yii::t('app', 'ID'),
            'project_id' 	=> Yii::t('app', 'Project ID'),
            'cluster' 		=> Yii::t('app', 'Cluster'),
        ];
    }

    public function getBoxes() {
        return $this->hasMany(Boxes::className(), ['cluster_id' => 'id']);
    }

    public function getProject() {
        return $this->hasOne(Projects::className(), ['id' => 'project_id']);
    }    

    public static function find() {
        return new \app\models\queries\ProjectClustersQuery(get_called_class());
    }
}
