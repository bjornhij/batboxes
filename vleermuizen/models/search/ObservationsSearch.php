<?php
namespace app\models\search;
use yii\data\ActiveDataProvider;
use app\models\Observations;

/**
 * ObservationsSearch represents the model behind the search form about `app\models\Observations`.
 */

class ObservationsSearch extends Observations {
	
    public function search($params, Array $options = NULL) {
    	
    	/* User identifier */
    	$userIdentifier = (\Yii::$app->user->getId()) ? \Yii::$app->user->getId() : 0;
    	
    	/* Base query */
        $query = Observations::find();
        
        /* Joins */
        $query->join('LEFT JOIN', 'visits', 'observations.visit_id = visits.id');
        $query->join('LEFT JOIN', 'boxes', 'observations.box_id = boxes.id');
        $query->join('LEFT JOIN', 'projects', 'visits.project_id = projects.id');
        $query->join('LEFT JOIN', 'project_counters', '"projects"."id" = "project_counters"."project_id" AND "project_counters"."user_id" = :user_id', ['user_id' => $userIdentifier]);
             
        /* Clauses */
        if(isset($options['visit_id']))
        	$query->byVisit($options['visit_id']);
        
        if(isset($options['box_id']))
        	$query->byBox($options['box_id']);
        	
        if(isset($options['validated']))
        	$query->byValidation($options['validated']);
        
        if(isset($options['personal']))
        	$query->byPersonal();
        
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
        
        if(isset($options['show_null']) && !$options['show_null'])
        	$query->andWhere(['<>', 'observations.observation_type', Observations::OBSERVATION_TYPE_NULL]);
        
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