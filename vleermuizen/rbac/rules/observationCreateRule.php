<?php
namespace app\rbac\rules;
use yii\rbac\Rule;
use app\models\Users;

class observationCreateRule extends Rule {

	public $name = "observationCreateRule";

	public function execute($user, $item, $params) {
		if(isset($params['visit']) && is_object($params['visit']))
<<<<<<< HEAD
			if(in_array($user, array_keys($params['visit']->observers)) || $params['visit']->project->owner_id == $user || $params['visit']->project->main_observer_id == $user || Users::checkRole($user, 'administrator'))
=======
			if($params['visit']->observer_id == $user || $params['visit']->project->owner_id == $user || $params['visit']->project->main_observer_id == $user || Users::checkRole($user, 'administrator'))
>>>>>>> 61a704c854038d4f39e156975c6439eae455eecd
				return true;
					
		return false;
	}

}