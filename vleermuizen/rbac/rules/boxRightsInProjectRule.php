<?php
namespace app\rbac\rules;
use yii\rbac\Rule;

class boxRightsInProjectRule extends Rule {

	public $name = "boxRightsInProjectRule";

	public function execute($user, $item, $params) {
		if(isset($params['project']) && is_object($params['project']))
			if($params['project']->owner_id == $user || $params['project']->main_observer_id == $user)
				return true;
					
		return false;
	}

}