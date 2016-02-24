<?php
namespace app\models\search;
use yii\data\ActiveDataProvider;
use app\models\Boxtypes;

/**
 * BoxtypeSearch represents the model behind the search form about `app\models\Boxtypes`.
 */

class BoxtypesSearch extends Boxtypes {
	
    public function search($params, $personal = false) {
    	
        $query = Boxtypes::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);        

        $this->load($params);
		
        return $dataProvider;
        
    }
    
}
