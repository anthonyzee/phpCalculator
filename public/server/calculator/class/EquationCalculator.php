<?php
namespace phpcalculator\classes;

require_once(__ROOT__.'/abstract/calculatorAbstract.php');
require_once(__ROOT__.'/class/calculatorSharedClass.php');
require_once(__ROOT__.'/class/EquationParser.php');
require_once(__ROOT__.'/class/calculatorOperator.php');

// single responsibility
class EquationCalculator {
	public function __construct($operatorList) {
		$this->operatorList = $operatorList;
	}
	private function calculateEquation($leftOp, $rightOp, $operatorType):float{
		for ($i=0; $i<count($this->operatorList); $i++){
			if ($operatorType==$this->operatorList[$i]->operatorString){
				$calc=new $this->operatorList[$i]->operatorClass($leftOp, $rightOp);
				return $calc->calculate(); 
			}
		}
		return 0;
	}	
	private function sortOp($eqObj){
		$sortedOp=new EquationObj();
		
		for ($i=0; $i<count($this->operatorList); $i++){
			for ($j=0; $j<count($eqObj->operatorsList); $j++){
				if ($eqObj->operatorsList[$j]==$this->operatorList[$i]->operatorString){
					array_push($sortedOp->operatorsList, $eqObj->operatorsList[$j]);
					if ($eqObj->operandsList[$j]!="x"){
						array_push($sortedOp->operandsList, $eqObj->operandsList[$j]);
						$eqObj->operandsList[$j]="x";
					}
					if ($eqObj->operandsList[$j+1]!="x"){
						array_push($sortedOp->operandsList, $eqObj->operandsList[$j+1]);
						$eqObj->operandsList[$j+1]="x";
					}
				}
			}
		}
		
		return $sortedOp;
	}
	private function reEqstring($leftOp, $rightOp, $operator){
		return $leftOp." ".$operator." ".$rightOp;
	}
	
	/**
	* Recursive function to calculate the value of an operation using the equation string.
	*
	* @return float Sum of the current equation string
	*/
	public function calculateOp($equationString):float{
		$equationParser=new EquationParser($this->operatorList);
		$eqObj=$equationParser->parse($equationString);
		if (count($eqObj->operatorsList)!=count($eqObj->operandsList)-1){
			return 0;
		}
		if (count($eqObj->operatorsList)>1){
			$sortedeqObj=$this->sortOp($eqObj);
			$total=0;
			
			for ($i=0; $i<count($sortedeqObj->operatorsList); $i++){
				if ($i==0){
					$leftOp=$sortedeqObj->operandsList[$i];
				}
				$leftOp=$this->calculateOp($leftOp);
				$rightOp=$this->calculateOp($sortedeqObj->operandsList[$i+1]);
				$newEqstring=$this->reEqstring($leftOp, $rightOp, $sortedeqObj->operatorsList[$i]);
				$leftOp=$this->calculateOp($newEqstring);
				$total=$leftOp;
			}
			
			return $total;
		}else if (count($eqObj->operatorsList)==1){
			$leftOp=$this->calculateOp($eqObj->operandsList[0]);
			$rightOp=$this->calculateOp($eqObj->operandsList[1]);
			$ret=$this->calculateEquation($leftOp, $rightOp, $eqObj->operatorsList[0]);
			return $ret;
		}else{ //no operator but need to handle bracket
			$eqObj1=$equationParser->parse($equationString);
			$val=explode(" ", $eqObj1->operandsList[0]);
			if (count($val)==1){
				return floatval($equationString);
			}else{
				return $this->calculateOp($eqObj1->operandsList[0]);
			}
		}
	}
}
?>
