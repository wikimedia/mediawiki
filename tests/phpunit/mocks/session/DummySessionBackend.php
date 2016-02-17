<?php

namespace MediaWiki\Session;

/**
 * Dummy session backend
 *
 * This isn't a real backend, but implements some methods that SessionBackend
 * does so tests can run.
 */
class DummySessionBackend {
	public $data = [
		'foo' => 1,
		'bar' => 2,
		0 => 'zero',
	];
	public $dirty = false;

	public function &getData() {
		return $this->data;
	}

	public function dirty() {
		$this->dirty = true;
	}

	public function deregisterSession( $index ) {
	}
}
