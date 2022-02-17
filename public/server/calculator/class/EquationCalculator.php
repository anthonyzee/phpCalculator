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
		$sortedOp=[];
		$seqNo=0;
		for ($i=0; $i<count($this->operatorList); $i++){
			for ($j=0; $j<count($eqObj->operatorsList); $j++){
				if ($eqObj->operatorsList[$j]==$this->operatorList[$i]->operatorString){
					$seqNo++;
					$item=[
						"posno"=>$j, 
						"seqno"=>$seqNo, 
						"opstring"=>$eqObj->operatorsList[$j], 
						"opleft"=>$eqObj->operandsList[$j], 
						"opright"=>$eqObj->operandsList[$j+1],
						"result"=>0,
						"mergeposno"=>0,
						"done"=>false
					];
					array_push($sortedOp, $item);
				}
			}
		}
		return $sortedOp;
	}
	private function reEqstring($leftOp, $rightOp, $operator){
		return $leftOp." ".$operator." ".$rightOp;
	}
	
	function searchForId($id, $array) {
		foreach ($array as $key => $val) {
		   if ($val['posno'] === $id) {
			   return $key;
		   }
		}
		return null;
	}
	function searchForIdgreater($id, $array, $count) {
		for ($i=$id+1; $i<$count; $i++){
			$key=$this->searchForId($i, $array);
			if (!is_null($key)){
				return $key;
			}
		}
		return null;
	}	
	function searchForIdlesser($id, $array) {
		for ($i=$id-1; $i>=0; $i--){
			$key=$this->searchForId($i, $array);
			if (!is_null($key)){
				return $key;
			}
		}
		return null;
	}		
	function searchForId2($id, $array) {
		foreach ($array as $key => $val) {
		   if ($val['seqno'] === $id) {
			   return $key;
		   }
		}
		return null;
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

			uasort($sortedeqObj, function($a, $b){
				return strcmp($a['posno'], $b['posno']);
			});
			
			$seqno=0;
			$countOp=count($sortedeqObj);
			while (count($sortedeqObj)>1){
				$seqno++;
				$keySeq=$this->searchForId2($seqno, $sortedeqObj);
				
				$item=$sortedeqObj[$keySeq];

				$leftOp=$this->calculateOp($item["opleft"]);
				$rightOp=$this->calculateOp($item["opright"]);
				$ret=$this->calculateEquation($leftOp, $rightOp, $item["opstring"]);
				
				$key = $this->searchForIdlesser($item["posno"], $sortedeqObj);
				
				if (is_null($key)){
					$key = $this->searchForIdgreater($item["posno"], $sortedeqObj, $countOp);
					$sortedeqObj[$key]["opleft"]=$ret;
				}else{
					$sortedeqObj[$key]["opright"]=$ret;
				}
				unset($sortedeqObj[$keySeq]);
			}
			
			$total=$this->calculateEquation($sortedeqObj[$seqno]["opleft"], $sortedeqObj[$seqno]["opright"], $sortedeqObj[$seqno]["opstring"]);
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
