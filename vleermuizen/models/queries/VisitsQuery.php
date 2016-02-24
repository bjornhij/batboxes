<?php
namespace app\models\queries;
use app\models\VisitObservers;
use yii\db\Query;

class VisitsQuery extends ActiveQuery {

    public function all($db = null) {
        return parent::all($db);
    }

    public function one($db = null) {
        return parent::one($db);
    }
    
    public function personal() {
    	return $this->andWhere(['in', 'visits.id', (new Query)->select('id')->from(VisitObservers::tableName())->where(['observer_id' => \Yii::$app->user->getId()])]);
    }
    
}