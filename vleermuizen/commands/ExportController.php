<?php
namespace app\commands;
use yii\console\Controller;
use app\models\Observations;

class ExportController extends Controller {
	
	public function actionCreate() {
		echo "=== Starting export sequence at ".date("d-m-Y h:i")." ===\r\n";
		
		echo "Fetching all records from the 'occurences' view\r\n";
		$query = (new \yii\db\Query())->from('occurences');
		
		if($query->count()) {
			echo "Found ".$query->count()." records, continuing\r\n";
			$occurences = $query->all();
			
			$fileName = "occurences-".date("Y-m-d-H:i");
			echo "Creating the export csv with identifier ".$fileName.".zip\r\n";
			$csvFile = fopen(__DIR__."/../web/nlbif/".$fileName.".csv", "w");
			
			echo "Writing csv structure on first line of document \r\n";
			fputcsv($csvFile, array_keys($occurences[0]), ',', "'")."\r\n";
			
			echo "Looping trough all the results\r\n";
			foreach($occurences as $occurence) {
				foreach($occurence as $key => &$value) {
					if(is_null($value) && $key != "scientificName") continue;
					switch($key) {
						case 'sex': 					$value = Observations::getSexById($value); break;
						case 'lifeStage': 				$value = Observations::getAgeById($value); break;
						case 'reproductiveCondition': 	$value = Observations::getSexualStatusById($value); break;
						case 'scientificName':			(!empty($value) && $value != " ") ? $value : "Chiroptera";
					}
				}
				array_map('utf8_encode', $occurence);
				fputcsv($csvFile, $occurence, ',', "'")."\r\n";
			}
			
			echo "Bundling and compressing..\r\n";
			$zip = new \ZipArchive();
			$zip->open(__DIR__."/../web/nlbif/".$fileName.".zip", \ZipArchive::CREATE);
			$zip->addFile(__DIR__."/../web/nlbif/EML.xml", "EML.xml");
			$zip->addFile(__DIR__."/../web/nlbif/meta.xml", "meta.xml");
			$zip->addFile(__DIR__."/../web/nlbif/".$fileName.".csv", $fileName.".csv");
			$zip->close();
			
			echo "Cleaning up..\r\n";
			unlink(__DIR__."/../web/nlbif/".$fileName.".csv");
			
			echo "All done!\r\n";
			
		} else {
			echo "Couldn't find any records, exiting\r\n";
		}
		
		echo "=== Finished export sequence at ".date("d-m-Y h:i")." ===\r\n";
	}
	
	public function actionNdff() {
		echo "=== Starting export sequence at ".date("d-m-Y h:i")." ===\r\n";
		
		echo "Fetching all records from the 'observations' table where ndff_id is empty or ndiff_failed is filled\r\n";
		$observations = Observations::find()->where(['or', ['is', 'ndff_id', NULL], ['not', ['ndff_failed' => NULL]]]);
		
		echo "Found ".$observations->count()." records, continuing\r\n";
		
		echo "Looping trough all the results\r\n";
		foreach($observations->all() as $observation) {
			
			if($observation->observation_method == Observations::OBSERVATION_TYPE_NULL) continue;
			
			$ndffObservation = new \NDFF\Observation();
			
			$ndffObservation->setLifestage('http://ndff-ecogrid.nl/codes/domainvalues/observation/lifestages/unknown');
			$ndffObservation->setLocation(0, '', 'http://ndff-ecogrid.nl/codes/locationtypes/point');

			$ndffObservation->setPeriodstart(date('o-m-d\TH:i:s', strtotime($observation->visit->date)));
			$ndffObservation->setScaleidentity('http://ndff-ecogrid.nl/codes/scales/exact_count');
			
			if($observation->catch_sex != Observations::CATCH_SEX_UNKNOWN) {
				$sexUrlParameter = ($observation->catch_sex == Observations::CATCH_SEX_MALE) ? 'male' : 'female';
				$ndffObservation->setSex('http://ndff-ecogrid.nl/codes/domainvalues/observation/sexes/' . $sexUrlParameter);
			}				
			
			
			$ndffObservation->setSubjecttypeidentity('http://ndff-ecogrid.nl/codes/subjecttypes/live/individual');
			
			echo "Attempting to write observation ".$observation->id." to NDFF database\r\n";
			$ndffRequest = new \NDFF\ApiRequest('telmee', 'onsjos', '********');
			$ndffRequest->set_request_data($ndffObservation);
			$ndffRequest->resource_post('observation');
			
		}
		
			
		echo "All done!\r\n";
	}
	
}