<?php
class ObjectComparator {
	private $fields = array();
	protected $ascending = true;

	public function __construct($fields, $asc = true){
		if (!is_array($fields)) {
			$fields = array($fields => 'strnatcasecmp');
		}
		$this->fields = $fields;
		$this->ascending = $asc;
	}

	public function compare($a, $b){
		$result = 0;
		foreach ($this->fields as $field => $compare) {
			if ($compare == "intcmp") {
				$result = $this->intcmp($a->$field, $b->$field);
			} else {
				$result = call_user_func($compare, $a->$field, $b->$field);
			}
			if ($result == 0) {
				return $result;
			}
		}
		return $this->ascending ? $result : -$result;
	}

	private function intcmp($a, $b){
		if ($a == $b) return 0;
		return $a < $b ? 1 : -1; 
	}

	public function sort($array){
		usort($array, array($this, 'compare'));
		return $array;
	}
}