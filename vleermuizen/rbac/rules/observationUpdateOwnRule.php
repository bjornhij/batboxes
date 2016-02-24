<?php
namespace app\rbac\rules;
use yii\rbac\Rule;

class observationUpdateOwnRule extends Rule {

	public $name = "observationUpdateOwnRule";

	public function execute($user, $item, $params) {
		if(isset($params['observation']) && is_object($params['observation']))
<<<<<<< HEAD
			if(in_array($user, array_keys($params['observation']->visit->observers)))
=======
			if($params['observation']->visit->observer_id == $user)
>>>>>>> 61a704c854038d4f39e156975c6439eae455eecd
				return true;
					
		return false;
	}

}