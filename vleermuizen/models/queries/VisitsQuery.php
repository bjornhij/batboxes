<?php
namespace app\models\queries;
use Yii;

class VisitsQuery extends ActiveQuery {

    public function all($db = null) {
        return parent::all($db);
    }

    public function one($db = null) {
        return parent::one($db);
    }
    
    public function personal() {
    	return $this->andWhere(['observer_id' => Yii::$app->user->getId()]);
    }
    
}