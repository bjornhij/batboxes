<?php
namespace app\models\search;
use yii\data\ActiveDataProvider;
use app\models\Species;

/**
 * SpeciesSearch represents the model behind the search form about `app\models\Species`.
 */

class SpeciesSearch extends Species {
	
    public function search($params, $personal = false) {
    	
        $query = Species::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);        

        $this->load($params);
		
        return $dataProvider;
        
    }
    
}
