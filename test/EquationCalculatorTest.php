<?php
namespace phpCalculator;
use PHPUnit\Framework\TestCase;

define('__ROOT__', realpath(dirname(__FILE__))."/../public/server/calculator");
require_once(__ROOT__.'/class/EquationCalculator.php');
require_once(__ROOT__.'/class/calculatorOperator.php');

class EquationCalculatorTest extends TestCase
{
    public function testEquation1()
    {		
		$operatorList=[
			new operatorType(Divide::class, "/"),
			new operatorType(Multiply::class, "*"),
			new operatorType(Substract::class, "-"),
			new operatorType(Addition::class, "+"),
		];

		$calc=new EquationCalculator($operatorList);
		$equations=["1 + 1",
			"2 * 2",
			"1 + 2 + 3",
			"6 / 2",
			"11 + 23",
			"11.1 + 23",
			"1 + 1 * 3",
			"( 11.5 + 15.4 ) + 10.1",
			"23 - ( 29.3 - 12.5 )",
			"10 - ( 2 + 3 * ( 7 - 5 ) )"
		];
		$equationsResult=[2,4,6,3,34,34.1,4,37,6.199999999999999,2];

		for ($i=0; $i<count($equations); $i++){
			$ret=$calc->calculateOp($equations[$i]);		
			$this->assertEquals($equationsResult[$i], $ret);
		}
    }
}
?>