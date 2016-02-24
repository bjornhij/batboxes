<?php
namespace app\models\queries;
use Yii;
use yii\db\Query;
use app\models\Visits;
use app\models\VisitObservers;
/**
 * This is the ActiveQuery class for [[\app\models\Observations]].
 *
 * @see \app\models\Observations
 */

class ObservationsQuery extends ActiveQuery {

    public function all($db = null) {
        return parent::all($db);
    }

    public function one($db = null) {
        return parent::one($db);
    }
    
    public function byPersonal() {
    	return $this->andWhere(['in', 'visit_id', (new Query)->select('id')->from(VisitObservers::tableName())->where(['observer_id' => Yii::$app->user->getId()])]);
    }
    
    public function byValidation($validation) {
    	return $this->andWhere([($validation) ? 'is not' : 'is', 'validated_by_id', null]);
    }
    
    public function byVisit($visit_id) {
    	return $this->andWhere(['visit_id' => $visit_id]);
    }
    
    public function byBox($box_id) {
    	return $this->andWhere(['box_id' => $box_id]);
    }
    
    public function skipNullObservations() {
    	return $this->andWhere(['not', ['observation_type' => \app\models\Observations::OBSERVATION_TYPE_NULL]]);
    }
    
}