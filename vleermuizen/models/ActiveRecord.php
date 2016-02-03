<?php
namespace app\models;
use app\components\DateTime;
use app\components\Helper;
use yii\db\Expression;
use Yii;

class ActiveRecord extends \yii\db\ActiveRecord {
	public $dateFormats = array();
	
	public function beforeSave($insert) {
		foreach ($this->getTableSchema()->columns as $attribute => $column) {
			if (!$this->isNewRecord || $this->getDirtyAttributes([$attribute])) {
				if (Helper::starts(strtolower($column->dbType), 'timestamp')) {
					if ($this->$attribute instanceof Expression)
						continue;
					elseif ($this->$attribute && !is_string($this->$attribute))
						$this->$attribute = $this->$attribute->format('Y-m-d H:i:s').'+00';
					elseif (!is_string($this->$attribute) || !$this->$attribute)
						$this->$attribute = null;
					else
						$this->$attribute = (new DateTime($this->$attribute))->format('Y-m-d H:i:s');
				}
	
				elseif (strtolower($column->dbType) == 'date') {
					if ($this->$attribute instanceof Expression)
						continue;
					elseif ($this->$attribute && !is_string($this->$attribute))
						$this->$attribute = $this->$attribute->format('Y-m-d');
					elseif (!is_string($this->$attribute) || !$this->$attribute)
						$this->$attribute = null;
					else {
						$this->$attribute = DateTime::createFromFormat('d-m-Y', $this->$attribute)->format('Y-m-d');
					}
				}
				
				else if (Helper::starts(strtolower($column->dbType), 'datetime')) {
					if ($this->$attribute instanceof Expression)
						continue;
					elseif ($this->$attribute && !is_string($this->$attribute))
						$this->$attribute = $this->$attribute->format('Y-m-d H:i:s');
					elseif (!is_string($this->$attribute) || !$this->$attribute)
						$this->$attribute = null;
					else 
						$this->$attribute = DateTime('d-m-Y H:i:s', $this->$attribute)->format('Y-m-d H:i:s');
				}
				
				elseif (Helper::starts(strtolower($column->dbType), 'time')) {
					if ($this->$attribute instanceof Expression)
						continue;
					elseif ($this->$attribute && !is_string($this->$attribute))
						$this->$attribute = $this->$attribute->format('H:i:s');
					elseif (!is_string($this->$attribute) || !$this->$attribute)
						$this->$attribute = null;
				}
			}
		}
		if ($this->getDirtyAttributes() && $this->hasAttribute('date_updated') && !$this->isNewRecord)
			$this->date_updated = new Expression('NOW()');
		if ($this->isNewRecord && $this->hasAttribute('date_created'))
			$this->date_created = new Expression('NOW()');
		return parent::beforeSave($insert);
	}
	
	public function afterFind() {
		$this->_afterFind();
		return parent::afterFind();
	}
	
	public function afterSave($insert, $changedAttributes) {
		$this->refresh();
		$this->_afterFind();
		return parent::afterSave($insert, $changedAttributes);
	}
	
	protected function _afterFind() {
		foreach ($this->getTableSchema()->columns as $attribute => $column) {
			if ($this->$attribute && is_string($this->$attribute)) {
				if (Helper::starts(strtolower($column->dbType), 'timestamp')) {
					$this->$attribute = DateTime::createFromFormat('Y-m-d H:i:s', substr($this->$attribute, 0, 19));
					$this->$attribute->setFormat(isset($this->dateFormats[$attribute]) ? $this->dateFormats[$attribute] : 'd-m-Y H:i');
				}
				elseif ($column->dbType == 'date') {
					$this->$attribute = DateTime::createFromFormat('Y-m-d', $this->$attribute)->modify('midnight');
					$this->$attribute->setFormat(isset($this->dateFormats[$attribute]) ? $this->dateFormats[$attribute] : 'd-m-Y');
				}
				if (Helper::starts(strtolower($column->dbType), 'datetime')) {
					$this->$attribute = DateTime::createFromFormat('Y-m-d H:i:s', $this->$attribute);
					$this->$attribute->setFormat(isset($this->dateFormats[$attribute]) ? $this->dateFormats[$attribute] : 'd-m-Y H:i:s');	
				}
				elseif (Helper::starts(strtolower($column->dbType), 'time(')) {
					$this->$attribute = DateTime::createFromFormat('H:i:s', $this->$attribute);
					$this->$attribute->setFormat(isset($this->dateFormats[$attribute]) ? $this->dateFormats[$attribute] : 'H:i');
				}
			}
		}
		
		// make sure the changes we made above don't mark the attributes as dirty
		$this->setOldAttributes($this->attributes);
	}
	
	protected function getDateTimeFilterRules() {
		$rules = array();
		foreach ($this->getTableSchema()->columns as $attribute => $column) {
			if (Helper::starts(strtolower($column->dbType), 'timestamp')) 
				$rules[] = [$attribute, 'filterDateTime'];
			elseif (strtolower($column->dbType) == 'date') 
				$rules[] = [$attribute, 'filterDate'];
			elseif (strtolower($column->dbType) == 'time') {
				$rules[] = [$attribute, 'filterTime'];
			}
		}
		return $rules;
	}
	
	public function filterDateTime($attribute, $params) {
		if ($this->$attribute && is_string($this->$attribute))
			if ($attr = $this->_filter($attribute, $params, isset($this->dateFormats[$attribute]) ? $this->dateFormats[$attribute] : 'd-m-Y H:i'))
				$this->$attribute = $attr;
			else
				$this->addError($attribute, Yii::t('app', 'Geef een geldige datum en tijd op, bijvoorbeeld 24-07-2014 23:45'));
	}
	
	public function filterDate($attribute, $params) {
		if ($this->$attribute && is_string($this->$attribute))
			if ($attr = $this->_filter($attribute, $params, isset($this->dateFormats[$attribute]) ? $this->dateFormats[$attribute] : 'd-m-Y')) {
				$attr->modify('midnight');
				$this->$attribute = $attr;
			}
			else 
				$this->addError($attribute, Yii::t('app', 'Geef een geldige datum op, bijvoorbeeld 24-07-2014'));
	}
	
	/**
	 * Returns a copy of this active record, without the primary keys set. 
	 * @return yii\db\ActiveRecord This active record
	 */
	public function getCopy() {
		$pks = array_keys($this->getPrimaryKey(true));
		$class = self::className();
		$model = new $class;
		foreach ($this->attributes as $key => $attribute)
			if (!in_array($key, $pks))
				$model->$key = $this->$key;
		return $model; 
	}

	public function filterTime($attribute, $params) {
		if ($this->$attribute && is_string($this->$attribute)) {
			if ($attr = $this->_filter($attribute, $params, isset($this->dateFormats[$attribute]) ? $this->dateFormats[$attribute] : 'H:i'))
				$this->$attribute = $attr;
			else 
				$this->addError($attribute, Yii::t('app', 'Geef een tijd op in 24 uurs formaat, bijvoorbeeld 23:45'));
		}
	}
	
	private function _filter($attribute, $params, $format) {
		if (!($date = DateTime::createFromFormat($format, $this->$attribute)))
			return false;
		$date->setFormat($format);
		return $date;
	}
}
