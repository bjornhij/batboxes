<?php
namespace app\rbac\rules;
use yii\rbac\Rule;

class observationUpdateOwnRule extends Rule {

	public $name = "observationUpdateOwnRule";

	public function execute($user, $item, $params) {
		if(isset($params['observation']) && is_object($params['observation']))
			if($params['observation']->visit->observer_id == $user)
				return true;
					
		return false;
	}

}