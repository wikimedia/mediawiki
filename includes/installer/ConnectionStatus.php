<?php

namespace MediaWiki\Installer;

use MediaWiki\Status\Status;
use Wikimedia\Rdbms\Database;

/**
 * @internal
 */
class ConnectionStatus extends Status {
	public function __construct( ?Database $db = null ) {
		$this->value = $db;
	}

	public function setDB( Database $db ) {
		$this->value = $db;
	}

	public function getDB(): Database {
		return $this->value;
	}
}
