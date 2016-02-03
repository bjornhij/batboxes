<?php
namespace app\models;
use Yii;
use yii\base\NotSupportedException;
use app\models\queries\UsersQuery;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $full_name
 */

class Users extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface {

	const USER_ROLE_AUTHENTICATED 	= "authenticated user";
	const USER_ROLE_VALIDATOR 		= "validator";
	const USER_ROLE_ADMINISTRATOR 	= "administrator";
	
	public static function tableName() {
		return 'users';
	}
	
	public static function findIdentity($id) {
		return static::findOne($id);
	}
	
	public static function findIdentityByAccessToken($token, $type = NULL) {
		throw new NotSupportedException();
	}
	
	public static function checkRole($user_id, $role) {
		$userRoles = array_keys(Yii::$app->getAuthManager()->getRolesByUser($user_id));
		if(is_array($role))
			return array_intersect($userRoles, $role);
	
		return in_array($role, $userRoles);
	}
	
	public static function find() {
		return new \app\models\queries\UsersQuery(get_called_class());
	}
	
	public function beforeSave($insert) {
		if ($this->isNewRecord)
			$this->auth_key = Yii::$app->getSecurity()->generateRandomString();
		return parent::beforeSave($insert);
	}

	public function rules() {
		return [
			[['username', 'fullname'], 'required'],
			[['username', 'auth_key'], 'unique']
		];
	}

	public function attributeLabels() {
		return [
			'id' 			=> Yii::t('app', 'ID'),
			'username' 		=> Yii::t('app', 'Username'),
			'auth_key' 		=> Yii::t('app', 'Auth Key'),
			'fullname' 		=> Yii::t('app', 'Fullname'),
		];
	}
	
	public function validateAuthKey($authKey) {
		return $this->getAuthKey() === $authKey;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getAuthKey() {
		return $this->auth_key;
	}
	
	public function getRoles() {
		return array_keys(Yii::$app->getAuthManager()->getRolesByUser($this->id));
	}
	
	public function hasRole($role) {
		$userRoles = array_keys(Yii::$app->getAuthManager()->getRolesByUser($this->id));
		if(is_array($role))
			return array_intersect($userRoles, $role);
		
		return in_array($role, $userRoles);
	}
	
	public function getObserverProject() {
		return $this->hasOne(Projects::className(), ['main_observer_id' => 'id']);
	}
	
	public function getUserAdoptions() {
		return $this->hasMany(Boxes::className(), ['id' => 'adopted_by_id']);
	}
	
	public function getCounterProjects() {
		return $this->hasMany(Projects::className(), ['id' => 'project_id'])
		->viaTable('project_counters', ['user_id' => 'id']);
	}
	
}
