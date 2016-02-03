<?php
namespace app\models\search;
use Yii;
use yii\data\ActiveDataProvider;
use app\models\Projects;

/**
 * ProjectsSearch represents the model behind the search form about `app\models\Projects`.
 */

class ProjectsSearch extends Projects {
	
    public function search($params, $personal = false) {
    	
    	/* User identifier */
    	$userIdentifier = (\Yii::$app->user->getId()) ? \Yii::$app->user->getId() : 0; 
    	
    	/* Base query */
        $query = new \yii\db\Query();
        
        /* Selector */
        $query->select(['"projects".*', 'COUNT("boxes"."id") as boxCount']);
        
        /* Table */
        $query->from('projects');
        
        /* Joins */
        $query->join('LEFT JOIN', 'boxes', '"projects"."id" = "boxes"."project_id" AND "boxes"."deleted" = FALSE');
        $query->join('LEFT JOIN', 'project_counters', '"projects"."id" = "project_counters"."project_id" AND "project_counters"."user_id" = :user_id', ['user_id' => $userIdentifier]);
        
        /* Conditions */
        $query->where(['"projects"."deleted"' => false]);
        if(!$personal) {
        	if(!is_object(Yii::$app->user->getIdentity()) || (is_object(Yii::$app->user->getIdentity()) && !Yii::$app->user->getIdentity()->hasRole(['validator', 'administrator']))) {
		        $query->andWhere([
		        	'or',
		        	['"projects"."owner_id"' => $userIdentifier],
		        	['"projects"."main_observer_id"' => $userIdentifier],
		        	[
	        			'or',
	        			['is', '"projects"."embargo"', NULL],
	        			['<=', '"projects"."embargo"', 'NOW()']	
		        	],
		        	['is not', '"project_counters"."user_id"', NULL]
		       	]);
        	}
        } else {
	    	$query->andWhere(['or', ['"projects"."owner_id"' => $userIdentifier], ['"projects"."main_observer_id"' => $userIdentifier], ['is not', '"project_counters"."user_id"', NULL]]);
        }
        
        /* Group */
        $query->groupBy('"projects"."id"');
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $this->load($params);
		
        return $dataProvider;
        
    }
    
}
