<?php
namespace app\components;

class DateTime extends \DateTime {
	public $format = 'd-m-Y H:i:s';
	
	public function __toString() {
		return $this->format($this->format);
	}
	
	public function setFormat($format) {
		$this->format = $format;
	}
	
	public function getString() {
		return $this->__toString();
	}
	
	static public function createFromFormat($format, $time, $timezone = null) {
		$date = new static();
		if (!parent::createFromFormat($format, $time))
			return false;
		$date->setTimestamp(parent::createFromFormat($format, $time)->getTimestamp());
		return $date;
	}
}