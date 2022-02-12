<?php
define('__ROOT__', realpath(dirname(__FILE__)));
require_once(__ROOT__.'/class/EquationCalculator.php');

$calc=new phpCalculator\EquationCalculator($operatorList);
$ret=$calc->calculateOp("1 + 1 * 3");
echo "[".$ret."]";

?>

