<?php
namespace app\models\queries;

class ActiveQuery extends \yii\db\ActiveQuery {

	public function id($id) {
		$modelClass = $this->modelClass;
		return $this->andWhere([$modelClass::tableName().'.id' => $id]);
	}
	
}