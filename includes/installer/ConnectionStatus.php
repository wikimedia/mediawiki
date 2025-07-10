<?php

namespace MediaWiki\Installer;

use MediaWiki\Status\Status;
use Wikimedia\Rdbms\IMaintainableDatabase;

/**
 * @internal
 * @extends Status<?IMaintainableDatabase>
 */
class ConnectionStatus extends Status {
	public function __construct( ?IMaintainableDatabase $db = null ) {
		$this->value = $db;
	}

	public function setDB( IMaintainableDatabase $db ) {
		$this->value = $db;
	}

	public function getDB(): IMaintainableDatabase {
		return $this->value;
	}
}
