<?php
use Liuggio\StatsdClient\Factory\StatsdDataFactory;

class BufferingStatsdDataFactory extends StatsdDataFactory {
	public $buffer = array();

	public function produceStatsdDataEntity() {
		$statsDataEntity = $this->produceStatsdDataEntity();
		$this->buffer[] = $statsDataEntity;
		return $statsDataEntity;
	}
}
