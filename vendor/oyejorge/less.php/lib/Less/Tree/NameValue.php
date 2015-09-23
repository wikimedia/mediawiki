<?php

/**
 * A simple css name-value pair
 * ex: width:100px;
 *
 * In bootstrap, there are about 600-1,000 simple name-value pairs (depending on how forgiving the match is) -vs- 6,020 dynamic rules (Less_Tree_Rule)
 * Using the name-value object can speed up bootstrap compilation slightly, but it breaks color keyword interpretation: color:red -> color:#FF0000;
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_NameValue extends Less_Tree{

	public $name;
	public $value;
	public $index;
	public $currentFileInfo;
	public $type = 'NameValue';

	public function __construct($name, $value = null, $index = null, $currentFileInfo = null ){
		$this->name = $name;
		$this->value = $value;
		$this->index = $index;
		$this->currentFileInfo = $currentFileInfo;
	}

    public function genCSS( $output ){

		$output->add(
			$this->name
			. Less_Environment::$_outputMap[': ']
			. $this->value
			. (((Less_Environment::$lastRule && Less_Parser::$options['compress'])) ? "" : ";")
			, $this->currentFileInfo, $this->index);
	}

	public function compile ($env){
		return $this;
	}
}
