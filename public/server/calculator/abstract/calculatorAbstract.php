<?php
namespace phpCalculator;
abstract class Equation {
	public $leftoperand;
	public $rightoperand;
	
	public function __construct($leftoperand, $rightoperand) {
		$this->leftoperand = $leftoperand;
		$this->rightoperand = $rightoperand;
	}
	abstract public function calculate():float;
}

/* A new equation class can be added */ 
class Addition extends Equation {
	public function calculate():float{
		return $this->leftoperand + $this->rightoperand;
	}
}
class Substract extends Equation {
	public function calculate():float{
		return $this->leftoperand - $this->rightoperand;
	}
}
class Multiply extends Equation {
	public function calculate():float{
		return $this->leftoperand * $this->rightoperand;
	}
}
class Divide extends Equation {
	public function calculate():float{
		return $this->leftoperand / $this->rightoperand;
	}
}
?>