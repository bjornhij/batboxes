<?php
namespace app\models\search;
use Yii;
use yii\data\ActiveDataProvider;
use app\models\Boxes;
use app\models\Projects;

/**
 * Boxes represents the model behind the search form about `app\models\Projects`.
 */

class BoxesSearch extends Boxes {
	
    public function search($params, $project_id = NULL) {
    	
       /* User identifier */
    	$userIdentifier = (\Yii::$app->user->getId()) ? \Yii::$app->user->getId() : 0; 
    	
    	/* Base query */
        $query = new \yii\db\Query();
        
        /* Selector */
        $query->select(['"boxes".*']);
        
        /* Table */
        $query->from('boxes');
        
        /* Joins */
        $query->join('INNER JOIN', 'projects', '"projects"."id" = "boxes"."project_id" AND "projects"."deleted" = FALSE');
        $query->join('LEFT JOIN', 'project_counters', '"projects"."id" = "project_counters"."project_id" AND "project_counters"."user_id" = :user_id', ['user_id' => $userIdentifier]);
        
        /* Conditions */
        $query->where(['"boxes"."deleted"' => false]);
        
        if(!$project_id && (!is_object(Yii::$app->user->getIdentity()) || (is_object(Yii::$app->user->getIdentity()) && !Yii::$app->user->getIdentity()->hasRole(['validator', 'administrator']))))
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
		    
	    if($project_id)
	    	$query->andWhere(['"projects"."id"' => $project_id]);
	    
	    $query->andWhere([
    		'and',
    		['"projects"."deleted"' => false],
    		['"boxes"."deleted"' => false]
	    ]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $this->load($params);
		
        return $dataProvider;
        
    }
    
}
