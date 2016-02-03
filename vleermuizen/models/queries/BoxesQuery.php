<?php
namespace app\models\queries;

class BoxesQuery extends ActiveQuery {

    public function all($db = null) {
        return parent::all($db);
    }

    public function one($db = null) {
        return parent::one($db);
    }
    
    public function byProject($project_id) {
    	return $this->andWhere(['project_id' => $project_id]);
    }
    
    public function boxInVisit($visit_id = NULL) {
    	if($visit_id) 
    		return $this->select(['id, code, (SELECT 1 FROM public.visit_boxes WHERE public.visit_boxes.box_id = public.boxes.id AND visit_id = '.$visit_id.') AS box_visit_checked']);
    }
    
}