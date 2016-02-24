<?php
namespace app\rbac\rules;
use yii\rbac\Rule;
use app\models\Users;

class observationCreateRule extends Rule {

	public $name = "observationCreateRule";

	public function execute($user, $item, $params) {
		if(isset($params['visit']) && is_object($params['visit']))
			if(in_array($user, array_keys($params['visit']->observers)) || $params['visit']->project->owner_id == $user || $params['visit']->project->main_observer_id == $user || Users::checkRole($user, 'administrator'))
				return true;
					
		return false;
	}

}