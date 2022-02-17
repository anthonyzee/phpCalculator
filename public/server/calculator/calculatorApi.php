<?php
define('__ROOT__', realpath(dirname(__FILE__)));
require_once(__ROOT__.'/class/EquationCalculator.php');
require_once(__ROOT__.'/class/EquationChecker.php');

function http_400() {
    header($_SERVER['SERVER_PROTOCOL'] . ' 400 Invalid Request');
    exit(0);
}
header('Content-Type: application/json');

switch (strtoupper($_SERVER['REQUEST_METHOD'])) {
    case 'GET':
		$equationString=$_GET["fequation"];
		$equationString=trim($equationString);
		$checker=new phpcalculator\classes\EquationChecker(phpcalculator\classes\operatorList::$data);
		$checkr=$checker->check($equationString);
		if ($checkr){
			$calc=new phpcalculator\classes\EquationCalculator(phpcalculator\classes\operatorList::$data);
			$ret=$calc->calculateOp($equationString);
			$changes=["status"=>"Done","result"=>round($ret,4)];
			echo json_encode(array('now' => round(microtime(true) * 1000), "updates" => $changes));
		}else{
			$changes=["status"=>"Error","result"=>$checker->errorMsg];
			echo json_encode(array('now' => round(microtime(true) * 1000), "updates" => $changes));
		}
		break;
	case 'POST':
		$body = file_get_contents('php://input');
		$changes = json_decode($body);
		$now = floor(microtime(true)*1000);
		$id=$_GET['id'];
		echo json_encode(array('now' => $now, "status" => 'ok'));
		break;	
	default:
		header($_SERVER['SERVER_PROTOCOL'] . ' 405 Invalid Request');
}
?>
