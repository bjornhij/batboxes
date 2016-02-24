<?php
namespace app\models\queries;

class ProjectClustersQuery extends ActiveQuery {
    
    public function all($db = null) {
        return parent::all($db);
    }
    
    public function one($db = null) {
        return parent::one($db);
    }
    
    public function byCluster($cluster) {
    	return parent::andWhere(['cluster' => $cluster]);
    }
    
}