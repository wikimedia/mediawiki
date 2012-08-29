<?php

class MockOutputPage {

	public $message;

	function debug( $message ) {
		$this->message = "JAJA is a stupid error message. Anyway, here's your message: $message";
	}
}
