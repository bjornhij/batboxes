<?php
namespace app\models\queries;

/**
 * This is the ActiveQuery class for [[\app\models\VisitBoxes]].
 *
 * @see \app\models\VisitBoxes
 */

class VisitBoxesQuery extends \yii\db\ActiveQuery {

	public function all($db = null) {
        return parent::all($db);
    }

    public function one($db = null) {
        return parent::one($db);
    }
    
}