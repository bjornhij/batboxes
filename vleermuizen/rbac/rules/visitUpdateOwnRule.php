<?php
namespace app\rbac\rules;
use yii\rbac\Rule;

class visitUpdateOwnRule extends Rule {

	public $name = "visitUpdateOwnRule";

	public function execute($user, $item, $params) {
		if(isset($params['visit']) && is_object($params['visit']))
<<<<<<< HEAD
			if(in_array($user, array_keys($params['visit']->observers)))
=======
			if($params['visit']->observer_id == $user)
>>>>>>> 61a704c854038d4f39e156975c6439eae455eecd
				return true;
					
		return false;
	}

}