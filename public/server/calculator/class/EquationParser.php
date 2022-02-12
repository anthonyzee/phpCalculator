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

// single responsibility
class EquationParser {
	public function __construct($operatorList) {
		$this->operatorList = $operatorList;
	}
	private function checkIsoperator($val){
		for ($i=0; $i<count($this->operatorList); $i++){
			if ($val==$this->operatorList[$i]->operatorString){
				return true;
			}
		}
		return false;
	}
	public function parse($equationString){
		$equationArray=explode(" ", $equationString);
		$equationBracket=new EquationBracket();
		$equationObj=new EquationObj();
	
		for ($i=0; $i<count($equationArray); $i++){
			$eqval=$equationArray[$i];
			if ($eqval=="("){
				$equationBracket->startBracket=true;
				if ($equationBracket->startBracketcount==0){
					$equationBracket->startBracketpos=$i;
				}
				$equationBracket->startBracketcount++;
			}
			if ($eqval==")"){
				$equationBracket->startBracketcount--;
				if ($equationBracket->startBracketcount==0){
					$equationBracket->startBracket=false;
					$equationBracket->endBracketpos=$i;
				}
			}
			if (!$equationBracket->startBracket){
				if ($this->checkIsoperator($eqval)){
					array_push($equationObj->operatorsList, $eqval);
				}else{
					if ($equationBracket->startBracketpos!=-1){
						$bracketVal="";
						for ($j=$equationBracket->startBracketpos; $j<=$equationBracket->endBracketpos; $j++){
							if (!($equationBracket->startBracketpos==$j || $equationBracket->endBracketpos==$j)){ // omit the bracket								
								if ($j==$equationBracket->startBracketpos+1) {
									$bracketVal=$equationArray[$j];
								}else {
									$bracketVal=$bracketVal." ".$equationArray[$j];
								}
							}
						}
						$equationBracket->reset();
						array_push($equationObj->operandsList, $bracketVal);
					}else{
						array_push($equationObj->operandsList, $eqval);
					}
				}				
			}
		}
			
		return $equationObj;
	} // end of parse
	
}
?>
