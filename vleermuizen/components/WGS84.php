<?php
namespace app\components;
use yii\base\Object;

class WGS84 extends Object {
	
	public static function blurCoordinates($distance, $latitude, $longitude, $json = false) {
		$bearing 	= rand(0, 360); 
		$distance 	= rand($distance / 4, $distance / 2);
		$radius 	= 6371;
		$distance 	/= 1000;
	
		$new_latitude 	= rad2deg(asin(sin(deg2rad($latitude)) * cos($distance / $radius) + cos(deg2rad($latitude)) * sin($distance / $radius) * cos(deg2rad($bearing))));
		$new_longitude 	= rad2deg(deg2rad($longitude) + atan2(sin(deg2rad($bearing)) * sin($distance / $radius) * cos(deg2rad($latitude)), cos($distance / $radius) - sin(deg2rad($latitude)) * sin(deg2rad($new_latitude))));
	
		$cords['lat'] = $new_latitude;
		$cords['lng'] = $new_longitude;
		
		return ($json) ? json_encode($cords) : $cords;
	}
	
	public static function inrange($min, $number, $max){
		if(is_numeric($number) && $number >= $min && ($number <= $max))
			return true;
			return false;
	}
	
}