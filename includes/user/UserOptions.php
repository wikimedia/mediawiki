<?php

class UserOptions extends ReadOnlyUserOptions {
	protected $saveCallback;
	protected $changed = false;

	public function __construct( array $data, callback $saveCallback ) {
		parent::__construct( $data );
		$this->saveCallback = $saveCallback;
	}

	public function isChanged() {
		return $this->changed;
	}

	public function set( $optionName, $value ) {
		$this->changed = true;
		$this->data[$optionName] = $value;
	}

	public function setMulti( array $values ) {
		$this->changed = true;
		$this->data = $values + $this->data;
	}

	public function save() {
		$callback = $this->saveCallback;
		$callback();
		$this->changed = false;
	}
}
