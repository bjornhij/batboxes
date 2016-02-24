<?php
namespace app\components;
use Yii;
use yii\base\Object;
use app\models\Users;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;

class Drupal extends Object{
	
	private $drupalUser;
	private $yiiUser;
	private $authManager;
	
	public function init() {
		$this->drupalUser 	= $GLOBALS['user'];
		$this->authManager 	= Yii::$app->getAuthManager(); 
		parent::init();		
	}
	
	public function checkIdentity() {
		if($this->drupalUser->uid) 
			$this->parseIdentity();
			
		if(Yii::$app->user->isGuest && $this->drupalUser->uid)
			Yii::$app->user->login($this->yiiUser, 3600*24*30);
	}
	
	/* Check if users exists, if so update user data if necessary else it creates a new user */
	public function parseIdentity() {
		/* User data */
		$userModel 				= (Users::findOne($this->drupalUser->uid)) ? Users::findOne($this->drupalUser->uid) : new Users;
		$userModel->id 			= $this->drupalUser->uid;
		$userModel->username 	= $this->drupalUser->name;
		$userModel->fullname	= field_get_items('user', user_load($this->drupalUser->uid), 'field_fullname')[0]['value'];
		$userModel->save();
		$this->yiiUser = $userModel;
		
		/* Revoke all roles */
		$this->authManager->revokeAll($this->yiiUser->id);
		
		/* User roles */
		foreach($this->drupalUser->roles as $role) {
			$role 		= $this->authManager->getRole($role);
			$userRoles 	= array_keys($this->authManager->getRolesByUser($this->yiiUser->id));
			if($role && !in_array($role->name, $userRoles))
				$this->authManager->assign($role, $this->yiiUser->id);
		}
	}
	
	/* Creates new user in Yii-db, triggered by Drupal hook */
	public function registerDrupalUser($user_id) {
		/* User data */
		$drupalData				= user_load($user_id);
		$userModel 				= (Users::findOne($user_id)) ? Users::findOne($user_id) : new Users;
		$userModel->id 			= $drupalData->uid;
		$userModel->username 	= $drupalData->name;
		$userModel->fullname	= field_get_items('user', user_load($user_id), 'field_fullname')[0]['value'];
		$userModel->save();
		$this->yiiUser = $userModel;
	
		/* Revoke all roles */
		$this->authManager->revokeAll($this->yiiUser->id);
	
		/* User roles */
		foreach($this->drupalUser->roles as $role) {
			$role 		= $this->authManager->getRole($role);
			$userRoles 	= array_keys($this->authManager->getRolesByUser($this->yiiUser->id));
			if($role && !in_array($role->name, $userRoles))
				$this->authManager->assign($role, $this->yiiUser->id);
		}
	}
	
	/* Fetch and convert Drupal navigation to Yii2 navigation  */
	public function navigation() {
		$primary_nav 	= $this->rebuildTree(menu_tree_all_data('main-menu'));	
		$secondary_nav 	= $this->rebuildTree(menu_tree_all_data('user-menu'));
		echo '<div id="navbar" class="navbar navbar-default">';
				echo '<div class="navbar-header">';
					echo '<a class="logo navbar-btn pull-left" href="/">';
						echo '<img src="/images/vleermuis-logo.png" alt="Vleermuiskasten.nl" />';
					echo  '</a>';
					echo '<div class="search hidden-xs">';
						echo '<form id="search">';
							echo '<input type="text" name="search" placeholder="Zoeken">';
							echo '<button type="submit"><i class="glyphicon glyphicon-search"></i></button>';
						echo '</form>';
					echo '</div>';
					echo '<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">';
						echo '<span class="sr-only">Navigatie openen</span>';
						echo '<span class="icon-bar"></span>';
						echo '<span class="icon-bar"></span>';
						echo '<span class="icon-bar"></span>';
					echo '</button>';
				echo '</div>';
				echo '<div class="navbar-collapse collapse">';
					echo '<nav role="navigation">';
						echo Nav::widget([
						    'items' => $primary_nav,
						    'options' => ['class' => 'menu nav navbar-nav'],
						]);
						echo Nav::widget([
							'items' => $secondary_nav,
							'options' => ['class' => 'menu nav navbar-nav'],
						]);
					echo '</nav>';
				echo '</div>';
		echo '</div>';
	}
	
	private function rebuildTree($tree) {
		$items = [];
		foreach($tree as $index => $treeItem) {
			if($treeItem['link']['hidden']) continue;
			if(stristr($treeItem['link']['href'], "front")) $treeItem['link']['href'] = "";
			
			$items[$index] = [
				'label' => $treeItem['link']['title'],
				'url' 	=> (stristr($treeItem['link']['href'], "app.")) ? $treeItem['link']['href'] : Yii::$app->params['baseUrl'].$treeItem['link']['href'],
			];
			if($treeItem['below']) {
				foreach($treeItem['below'] as $belowItem) {
					$items[$index]['items'][] = [
						'label' => $belowItem['link']['title'],
						'url' 	=> (stristr($belowItem['link']['href'], "app.")) ? $belowItem['link']['href'] : Yii::$app->params['baseUrl'].$belowItem['link']['href'],
					];
				}
			}
		}
		return $items;
	}
	
	public function footer() {
		return render(block_get_blocks_by_region('footer'));
	}
	
}