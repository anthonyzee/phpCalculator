<?php
namespace phpCalculator;

class EquationChecker {
	public function __construct($operatorList) {
		$this->operatorList = $operatorList;
	}	
	public $errorMsg;

	public function checkIsoperator($val){
		for ($i=0; $i<count($this->operatorList); $i++){
			if ($val==$this->operatorList[$i]->operatorString){
				return true;
			}
		}
		return false;
	}
	public function checkIsbracket($val){
		if ($val=="("||$val==")"){
			return true;
		}
		return false;
	}
	public function checkIsvalue($val){
		return is_numeric($val);
	}	
	private function checkBracket($equationString){
		$equationBracket=new EquationBracket();
		$equationArray=explode(" ", $equationString);
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
		}
		if ($equationBracket->startBracketcount==0){
			return true;
		}else{
			return false;
		}
	}
	
	private function checkValue($val){
		if ($this->checkIsoperator($val)){
			return true;
		}else if ($this->checkIsvalue($val)){
			return true;
		}else if ($this->checkIsbracket($val)){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	* Function to perform basic check on the format of the equation string.
	*
	* @return boolean Result of the current equation string check
	*/	
	public function check($equationString){
		if ($equationString==""){
			$this->errorMsg+="Empty equation string.";
			return false;
		}
		$equationArray=explode(" ", $equationString);
		$ret=true;
		$this->errorMsg="";
		
		// check bracket count
		$checkBracket=$this->checkBracket($equationString);
		if (!$checkBracket){
			$this->errorMsg.="Bracket check failed.";
		}
		$ret=$ret&&$checkBracket;
		for ($i=0; $i<count($equationArray); $i++){
			$eqval=$equationArray[$i];
			$checkValue=$this->checkValue($eqval);
			if (!$checkValue){
				$this->errorMsg.="Value check failed.";
			}
			$ret=$ret&&$checkValue;		
		}
		
		return $ret;
	}
}
?>
