<?php
namespace phpcalculator\classes;

class EquationChecker {
	public function __construct($operatorList) {
		$this->operatorList = $operatorList;
	}	
	public $errorMsg;
	private $checkOpstatus;

	/**
	* Function to check validity of operation in the equation string.
	*
	* @return boolean Status of the current equation string operator checking
	*/	
	public function checkIsoperator($val){
		for ($i=0; $i<count($this->operatorList); $i++){
			if ($val==$this->operatorList[$i]->operatorString){
				return true;
			}
		}
		return false;
	}

	/**
	* Function to check the bracket validity in the equation string.
	*
	* @return boolean Status of the current equation string bracket checking
	*/		
	public function checkIsbracket($val){
		if ($val=="("||$val==")"){
			return true;
		}
		return false;
	}
	
	/**
	* Function to check the value validity in the equation string.
	*
	* @return boolean Status of the current equation string value checking
	*/		
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

	private function reEqstring($leftOp, $rightOp, $operator){
		return $leftOp." ".$operator." ".$rightOp;
	}
	
	/**
	* Recursive function to check the value of an operation using the equation string.
	*
	* @return float Sum of the current equation string
	*/
	private function checkOp($equationString):float{
		$equationParser=new EquationParser($this->operatorList);
		$eqObj=$equationParser->parse($equationString);
		if (count($eqObj->operatorsList)!=count($eqObj->operandsList)-1){
			$this->checkOpstatus=false;
			$this->errorMsg.="Check operators failed at [".$equationString."]; ";
			return 0; // return 0 to continue.
		}
		if (count($eqObj->operatorsList)>1){
			$total=0;
			
			for ($i=0; $i<count($eqObj->operatorsList); $i++){
				if ($i==0){
					$leftOp=$eqObj->operandsList[$i];
				}
				$leftOp=$this->checkOp($leftOp);
				$rightOp=$this->checkOp($eqObj->operandsList[$i+1]);
				$newEqstring=$this->reEqstring($leftOp, $rightOp, $eqObj->operatorsList[$i]);
				$leftOp=$this->checkOp($newEqstring);
				$total=$leftOp;
			}
			
			return $total;
		}else if (count($eqObj->operatorsList)==1){
			$leftOp=$this->checkOp($eqObj->operandsList[0]);
			$rightOp=$this->checkOp($eqObj->operandsList[1]);
			$ret=0;
			return $ret;
		}else{ //no operator but need to handle bracket
			$eqObj1=$equationParser->parse($equationString);
			$val=explode(" ", $eqObj1->operandsList[0]);
			if (count($val)==1){
				return floatval($equationString);
			}else{
				return $this->checkOp($eqObj1->operandsList[0]);
			}
		}
	}
	
	/**
	* Function to perform basic check on the format of the equation string.
	*
	* @return boolean Result of the current equation string check
	*/	
	public function check($equationString){
		if ($equationString==""){
			$this->errorMsg+="Empty equation string; ";
			return false;
		}
		$equationArray=explode(" ", $equationString);
		$ret=true;
		$this->errorMsg="";
		
		// check bracket count
		$checkBracket=$this->checkBracket($equationString);
		if (!$checkBracket){
			$this->errorMsg.="Bracket check failed; ";
		}
		$ret=$ret&&$checkBracket;
		
		// check value
		for ($i=0; $i<count($equationArray); $i++){
			$eqval=$equationArray[$i];
			$checkValue=$this->checkValue($eqval);
			if (!$checkValue){
				$this->errorMsg.="Value check failed; ";
			}
			$ret=$ret&&$checkValue;		
		}
		
		// check op (recursive checking) if all basic checking is ok
		if ($ret){
			$this->checkOpstatus=true;
			$this->checkOp($equationString);
			$ret=$ret&&$this->checkOpstatus;
		}
		return $ret;
	}
}
?>
