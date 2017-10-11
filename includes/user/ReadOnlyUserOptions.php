<?php

class ReadOnlyUserOptions {
	protected $data;

	public function __construct( array $data ) {
		$this->data = $data;
	}

	public function has( $optionName ) {
		return isset( $this->data[$optionName] );
	}

	public function get( $optionName ) {
		return isset( $this->data[$optionName] ) ? $this->data[$optionName] : null;
	}

	public function getAll() {
		return $this->data;
	}
}
