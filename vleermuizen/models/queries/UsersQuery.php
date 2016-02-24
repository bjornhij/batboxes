<?php
namespace app\models\queries;

class UsersQuery extends ActiveQuery {

    public function all($db = null) {
        return parent::all($db);
    }

    public function one($db = null) {
        return parent::one($db);
    }
    
    public function username($username) {
    	return $this->andWhere(['LOWER(username)' => strtolower($username)]);
    }
    
}