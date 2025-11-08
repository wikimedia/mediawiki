<?php

namespace MediaWiki\Tests\Session;

use MediaWiki\User\User;

/**
 * Dummy session backend
 *
 * This isn't a real backend, but implements some methods that SessionBackend
 * does so tests can run.
 *
 * FIXME This class is a huge hack, and it won't work e.g. as methods in Session(Backend) are typehinted.
 * SessionBackend should be mocked directly instead, but that's currently impossible because the class is final.
 * At the very least, there should be an interface that SessionBackend implements, and that could also be used
 * for mocks.
 */
class DummySessionBackend {
	/** @var array */
	public $data = [
		'foo' => 1,
		'bar' => 2,
		0 => 'zero',
	];
	/** @var bool */
	public $dirty = false;

	/** @inheritDoc */
	public function &getData() {
		return $this->data;
	}

	public function dirty() {
		$this->dirty = true;
	}

	public function getUser(): User {
		return new User();
	}

	/** @inheritDoc */
	public function deregisterSession( $index ) {
	}
}
