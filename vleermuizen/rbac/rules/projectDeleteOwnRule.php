<?php
namespace app\rbac\rules;
use yii\rbac\Rule;

class projectDeleteOwnRule extends Rule {

	public $name = "projectDeleteOwnRule";

	public function execute($user, $item, $params) {
		if(isset($params['project']) && is_object($params['project']))
			if($params['project']->owner_id == $user)
				return true;
					
		return false;
	}

}