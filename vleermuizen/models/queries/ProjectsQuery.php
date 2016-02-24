<?php
namespace app\models\queries;
use Yii;
use yii\db\Query;
use app\models\Projects;

class ProjectsQuery extends ActiveQuery {

    public function all($db = null) {
        return parent::all($db);
    }

    public function one($db = null) {
        return parent::one($db);
    }
    
    /* Fetch projects where user has rights to */
    public function hasRights() {
    	$userId = Yii::$app->user->getId();
    	if(Yii::$app->user->getIdentity()->hasRole('administrator'))
    		return $this;
    		
    	return 	$this->andWhere([
    		'OR',
    		['owner_id' => $userId],
    		['main_observer_id' => $userId],
    		['exists', (new Query())->select('id')->from('project_counters')->where(['and', ['user_id' => $userId], 'project_id = projects.id'])],
    	]);
    }
    
    /* Check if project as boxes */
    public function hasBoxes() {
    	return $this->andWhere(['exists', (new Query())->select('COUNT(*)')->from('boxes')->where('"boxes"."project_id" = "projects"."id"')->groupBy('"boxes"."id"')]);
    }
    
}