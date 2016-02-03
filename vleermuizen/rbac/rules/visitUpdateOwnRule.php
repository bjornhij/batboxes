<?php
namespace app\rbac\rules;
use yii\rbac\Rule;

class visitUpdateOwnRule extends Rule {

	public $name = "visitUpdateOwnRule";

	public function execute($user, $item, $params) {
		if(isset($params['visit']) && is_object($params['visit']))
			if($params['visit']->observer_id == $user)
				return true;
					
		return false;
	}

}