<?php
namespace phpCalculator;
class operatorType {
	public $operatorClass;
	public $operatorString;
	
	public function __construct($operatorClass, $operatorString) {
		$this->operatorClass = $operatorClass;
		$this->operatorString = $operatorString;
	}	
}

/* 
A new operator type can be added to the list e.g. modulus %, 
but first add the operator class in the calculatorAbstract.php 
The operator list order is important! It is used to determine 
the operator precedence.
*/
$operatorList=[
	new operatorType(Divide::class, "/"),
	new operatorType(Multiply::class, "*"),
	new operatorType(Substract::class, "-"),
	new operatorType(Addition::class, "+"),
];
?>
