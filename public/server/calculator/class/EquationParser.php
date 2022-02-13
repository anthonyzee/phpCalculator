<?php
namespace phpCalculator;

// single responsibility
class EquationParser {
	public function __construct($operatorList) {
		$this->operatorList = $operatorList;
	}

	/**
	* Function to parse the equation string into equation object.
	*
	* @return EquationObj of the current equation string
	*/		
	public function parse($equationString){
		$equationArray=explode(" ", $equationString);
		$equationBracket=new EquationBracket();
		$equationObj=new EquationObj();
		$equationChecker=new EquationChecker($this->operatorList);
		
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
				if ($equationChecker->checkIsoperator($eqval)){
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
