<?php
namespace app\commands;
use Yii;
use yii\console\Controller;

class RbacController extends Controller {
	public function actionInit() {
    	/* Fetch auth manager */
		$auth = Yii::$app->authManager;
		
		/* Define roles */
		$user 			= $auth->createRole('authenticated user');
		$auth->add($user);
		$validator 		= $auth->createRole('validator');
		$auth->add($validator);
		$administrator 	= $auth->createRole('administrator');
		$auth->add($administrator);
		
		/* Project permissions */
	
			/* Create a project */
			$projectCreate 				= $auth->createPermission('createProject');
			$projectCreate->description = 'Create a project';
			$auth->add($projectCreate);
			
			/* Update a project */
			$projectUpdate 				= $auth->createPermission('updateProject');
			$projectUpdate->description = 'Update a project';
			$auth->add($projectUpdate);
			
				/* Update own project */
				$projectUpdateOwnRule			= new \app\rbac\rules\projectUpdateOwnRule;
				$projectUpdateOwn				= $auth->createPermission('updateOwnProject');
				$projectUpdateOwn->ruleName 	= $projectUpdateOwnRule->name;
				$projectUpdateOwn->description 	= 'Update own project';
				$auth->add($projectUpdateOwnRule);
				$auth->add($projectUpdateOwn);
				$auth->addChild($projectUpdateOwn, $projectUpdate);
			
			/* Delete a project */
			$projectDelete 				= $auth->createPermission('deleteProject');
			$projectDelete->description = 'Delete a project';
			$auth->add($projectDelete);
			
				/* Delete own project */
				$projectDeleteOwnRule			= new \app\rbac\rules\projectDeleteOwnRule;
				$projectDeleteOwn				= $auth->createPermission('projectDeleteOwnRule');
				$projectDeleteOwn->ruleName 	= $projectDeleteOwnRule->name;
				$projectDeleteOwn->description 	= 'Delete own project';
				$auth->add($projectDeleteOwnRule);
				$auth->add($projectDeleteOwn);
				$auth->addChild($projectDeleteOwn, $projectDelete);
				
		/* Box permissions */
			
			/* CRUD rights to any box in any project */
			$boxRights 				= $auth->createPermission('boxRights');
			$boxRights->description = 'CRUD rights to any box in any project';
			$auth->add($boxRights);
				
				/* CRUD rights to any box in project */
				$boxRightsInProjectRule				= new \app\rbac\rules\boxRightsInProjectRule;
				$boxRightsInProject					= $auth->createPermission('boxRightsInProjectRule');
				$boxRightsInProject->ruleName 		= $boxRightsInProjectRule->name;
				$boxRightsInProject->description 	= 'CRUD rights to any box in the project';
				$auth->add($boxRightsInProjectRule);
				$auth->add($boxRightsInProject);
				$auth->addChild($boxRightsInProject, $boxRights);
				
		/* Visits and observations */
				
			/* Create a visit */
			$visitCreate 				= $auth->createPermission('createVisit');
			$visitCreate->description = 'Create a visit';
			$auth->add($visitCreate);
				
			/* Update a visit */
			$visitUpdate 				= $auth->createPermission('updateVisit');
			$visitUpdate->description 	= 'Update any visit';
			$auth->add($visitUpdate);
			
				/* Update own visit */
				$visitUpdateOwnRule				= new \app\rbac\rules\visitUpdateOwnRule();
				$visitUpdateOwn					= $auth->createPermission('updateOwnVisit');
				$visitUpdateOwn->ruleName 		= $visitUpdateOwnRule->name;
				$visitUpdateOwn->description 	= 'Update own visit';
				$auth->add($visitUpdateOwnRule);
				$auth->add($visitUpdateOwn);
				$auth->addChild($visitUpdateOwn, $visitUpdate);
				
			/* Delete a visit */
			$visitDelete 				= $auth->createPermission('deleteVisit');
			$visitDelete->description 	= 'Delete any visit';
			$auth->add($visitDelete);
				
				/* Delete own visit */
				$visitDeleteOwnRule				= new \app\rbac\rules\visitDeleteOwnRule;
				$visitDeleteOwn					= $auth->createPermission('visitDeleteOwnRule');
				$visitDeleteOwn->ruleName 		= $visitDeleteOwnRule->name;
				$visitDeleteOwn->description 	= 'Delete own visit';
				$auth->add($visitDeleteOwnRule);
				$auth->add($visitDeleteOwn);
				$auth->addChild($visitDeleteOwn, $visitDelete);
				
			/* Create a observation */
			$observationCreateRule			= new \app\rbac\rules\observationCreateRule();
			$observationCreate 				= $auth->createPermission('createObservation');
			$observationCreate->ruleName	= $observationCreateRule->name;
			$observationCreate->description = 'Create an observation';
			$auth->add($observationCreateRule);
			$auth->add($observationCreate);
			
			/* Update a observation */
			$observationUpdate 				= $auth->createPermission('updateObservation');
			$observationUpdate->description = 'Update an observation';
			$auth->add($observationUpdate);
			
				/* Update own observation */
				$observationUpdateOwnRule			= new \app\rbac\rules\observationUpdateOwnRule;
				$observationUpdateOwn				= $auth->createPermission('observationUpdateOwnRule');
				$observationUpdateOwn->ruleName 	= $observationUpdateOwnRule->name;
				$observationUpdateOwn->description 	= 'Update own observation';
				$auth->add($observationUpdateOwnRule);
				$auth->add($observationUpdateOwn);
				$auth->addChild($observationUpdateOwn, $observationUpdate);
			
			/* Delete a observation */
			$observationDelete 				= $auth->createPermission('deleteObservation');
			$observationDelete->description = 'Delete an observation';
			$auth->add($observationDelete);
			
				/* Delete own observation */
				$observationDeleteOwnRule			= new \app\rbac\rules\observationDeleteOwnRule;
				$observationDeleteOwn				= $auth->createPermission('observationDeleteOwnRule');
				$observationDeleteOwn->ruleName 	= $observationDeleteOwnRule->name;
				$observationDeleteOwn->description 	= 'Delete own observation';
				$auth->add($observationDeleteOwnRule);
				$auth->add($observationDeleteOwn);
				$auth->addChild($observationDeleteOwn, $observationDelete);
				
		/* Assign project permissions */
			
			// User
			$auth->addChild($user, $projectCreate);
			$auth->addChild($user, $projectUpdateOwn);
			$auth->addChild($user, $projectDeleteOwn);
			$auth->addChild($user, $boxRightsInProject);
			$auth->addChild($user, $visitCreate);
			$auth->addChild($user, $visitUpdateOwn);
			$auth->addChild($user, $visitDeleteOwn);
			$auth->addChild($user, $observationCreate);
			$auth->addChild($user, $observationUpdateOwn);
			$auth->addChild($user, $observationDeleteOwn);
			// Validator
			$auth->addChild($validator, $projectCreate);
			$auth->addChild($validator, $projectUpdateOwn);
			$auth->addChild($validator, $projectDeleteOwn);
			$auth->addChild($validator, $boxRightsInProject);
			$auth->addChild($validator, $visitCreate);
			$auth->addChild($validator, $visitUpdateOwn);
			$auth->addChild($validator, $visitDeleteOwn);
			$auth->addChild($validator, $observationCreate);
			$auth->addChild($validator, $observationUpdate);
			$auth->addChild($validator, $observationDeleteOwn);
			// Administrator
			$auth->addChild($administrator, $projectCreate);
			$auth->addChild($administrator, $projectUpdate);
			$auth->addChild($administrator, $projectDelete);
			$auth->addChild($administrator, $boxRights);
			$auth->addChild($administrator, $visitCreate);
			$auth->addChild($administrator, $visitUpdate);
			$auth->addChild($administrator, $visitDelete);
			$auth->addChild($administrator, $observationCreate);
			$auth->addChild($administrator, $observationUpdate);
			$auth->addChild($administrator, $observationDelete);
	
	}
}