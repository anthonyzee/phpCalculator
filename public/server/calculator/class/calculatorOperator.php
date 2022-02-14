<?php
namespace phpcalculator\classes;
class operatorType {
	public $operatorClass;
	public $operatorString;
	
	public function __construct($operatorClass, $operatorString) {
		$this->operatorClass = $operatorClass;
		$this->operatorString = $operatorString;
	}	
}

class operatorList {
    public static $data = array();
}

/* 
A new operator type can be added to the list e.g. modulus %, 
but first add the operator class in the calculatorAbstract.php 
The operator list order is important! It is used to determine 
the operator precedence.
*/
array_push(operatorList::$data, 
	new operatorType(\phpcalculator\abstracts\Divide::class, "/"),
	new operatorType(\phpcalculator\abstracts\Multiply::class, "*"),
	new operatorType(\phpcalculator\abstracts\Substract::class, "-"),
	new operatorType(\phpcalculator\abstracts\Addition::class, "+"),
);

?>
