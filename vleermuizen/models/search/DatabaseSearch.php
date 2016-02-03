<?php
namespace app\models\search;
use Yii;
use yii\data\ActiveDataProvider;
use app\models\Observations;
use app\models\Projects;

class DatabaseSearch extends Observations {
	
    public function search($params, $personal = false) {
    	
    	/* User identifier */
    	$userIdentifier = (\Yii::$app->user->getId()) ? \Yii::$app->user->getId() : 0;
    	
    	/* Base query */
        $query = Observations::find();
        
        /* Joins */
        $query->join('LEFT JOIN', 'boxes', '"observations"."box_id" = "boxes"."id" AND "boxes"."deleted" = FALSE');
        $query->join('LEFT JOIN', 'visits', '"observations"."visit_id" = "visits"."id" AND "visits"."deleted" = FALSE');
        $query->join('LEFT JOIN', 'projects', '"boxes"."project_id" = "projects"."id" AND "projects"."deleted" = FALSE');
        $query->join('LEFT JOIN', 'project_counters', '"projects"."id" = "project_counters"."project_id" AND "project_counters"."user_id" = :user_id', ['user_id' => $userIdentifier]);
       
        /* Conditions */
        $query->where([
			'and', 
        	['observations.deleted' => false], 
        	['not', ['visits.id' => NULL]], 
        	['not', ['boxes.id' => NULL]], 
        	['not', ['projects.id' => NULL]],
        	[
        		'or',
        		['is', '"visits"."embargo"', NULL],
        		['<=', '"visits"."embargo"', 'NOW()'],
        		['visits.observer_id' => $userIdentifier],
        	],
        	['<>', 'observations.observation_type', Observations::OBSERVATION_TYPE_NULL]
        ]);
        
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
        
        $query->andWhere([
        	'and',
        	['"projects"."deleted"' => false],
        	['"boxes"."deleted"' => false],
        	['"visits"."deleted"' => false],
        	['"observations"."deleted"' => false]
        ]);        
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $this->load($params);
		
        return $dataProvider;
        
    }
    
}