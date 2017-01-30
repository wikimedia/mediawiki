<?php

class SerializationChangedException extends Exception {
	public function __construct( $className ) {
		parent::__construct( "Serialization of class '$className' has changed" );
	}
}
