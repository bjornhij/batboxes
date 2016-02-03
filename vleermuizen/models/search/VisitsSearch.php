<?php
namespace app\models\search;
use yii\data\ActiveDataProvider;
use app\models\Visits;

/**
 * VisitsSearch represents the model behind the search form about `app\models\Visits`.
 */

class VisitsSearch extends Visits {
	
    public function search($params, Array $options = NULL) {

    	/* User identifier */
    	$userIdentifier = (\Yii::$app->user->getId()) ? \Yii::$app->user->getId() : 0;
    	
    	/* Base query */
        $query = Visits::find();
        
        /* Joins */        
        $query->join('LEFT JOIN', 'projects', 'visits.project_id = projects.id AND projects.deleted = FALSE');
        $query->join('LEFT JOIN', 'project_counters', '"projects"."id" = "project_counters"."project_id" AND "project_counters"."user_id" = :user_id', ['user_id' => $userIdentifier]);
        
        /* Clauses */
        if(isset($options['project_id']) && $options['project_id'])
        	$query->where(['project_id' => $options['project_id']]);
        
        $query->where(['and', ['visits.deleted' => false], ['projects.deleted' => false]]);
        
        $query->andWhere([
			'or',
        	['"projects"."owner_id"' => $userIdentifier],
        	['"projects"."main_observer_id"' => $userIdentifier],
        	[
        		'and',
        		[
	        		'or',
	        		['is', '"projects"."embargo"', NULL],
	        		['<=', '"projects"."embargo"', 'NOW()']
        		],
        		[
        			'or',
        			['is', '"visits"."embargo"', NULL],
        			['<=', '"visits"."embargo"', 'NOW()']
        		]
        	],
        	['is not', '"project_counters"."user_id"', NULL]
        ]);
        
        if(isset($options['personal']) && $options['personal'])
        	$query->personal();
        
        if(isset($options['project_id']))
        	$query->andWhere(['visits.project_id' => $options['project_id']]);
        
        $query->andWhere([
			'and',
        	['"projects"."deleted"' => false],
        	['"visits"."deleted"' => false],
        ]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]); 

        $this->load($params);
		
        return $dataProvider;
        
    }
    
}
