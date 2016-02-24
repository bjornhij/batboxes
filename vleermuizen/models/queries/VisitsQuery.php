<?php
namespace app\models\queries;
<<<<<<< HEAD
use app\models\VisitObservers;
use yii\db\Query;
=======
use Yii;
>>>>>>> 61a704c854038d4f39e156975c6439eae455eecd

class VisitsQuery extends ActiveQuery {

    public function all($db = null) {
        return parent::all($db);
    }

    public function one($db = null) {
        return parent::one($db);
    }
    
    public function personal() {
<<<<<<< HEAD
    	return $this->andWhere(['in', 'visits.id', (new Query)->select('id')->from(VisitObservers::tableName())->where(['observer_id' => \Yii::$app->user->getId()])]);
=======
    	return $this->andWhere(['observer_id' => Yii::$app->user->getId()]);
>>>>>>> 61a704c854038d4f39e156975c6439eae455eecd
    }
    
}