<?php
namespace app\controllers;
class TestController extends Controller {
	
	public function actionIndex() {
		
		
		
		$observation = new \NDFF\Observation();
				
		$observation->setLifestage('http://ndff-ecogrid.nl/codes/domainvalues/observation/lifestages/unknown');
		$observation->setLocation(0, '', 'http://ndff-ecogrid.nl/codes/locationtypes/point');
		$observation->setOriginalabundance(0);
		$observation->setPeriodstart(date('o-m-d\TH:i:s'));
		$observation->setScaleidentity('http://ndff-ecogrid.nl/codes/scales/exact_count');
		$observation->setSex('http://ndff-ecogrid.nl/codes/domainvalues/observation/sexes/undefined');
		$observation->setSubjecttypeidentity('http://ndff-ecogrid.nl/codes/subjecttypes/live/individual');
		
		
	    
		
		d(json_decode($bats));
		
		die;
		
	}
	
}