<?php
namespace phpCalculator;
class EquationBracket {
	public $startBracket=false;
	public $startBracketcount=0;
	public $startBracketpos=-1;
	public $endBracketpos=-1;
	public function __construct() {
		$this->reset();
	}
	public function reset() {
		$this->startBracket=false;
		$this->startBracketcount=0;
		$this->startBracketpos=-1;
		$this->endBracketpos=-1;			
	}
}
class EquationObj {
	public $operandsList;
	public $operatorsList;
	
	public function __construct() {
		$this->operandsList = [];
		$this->operatorsList = [];
	}	
}
?>
