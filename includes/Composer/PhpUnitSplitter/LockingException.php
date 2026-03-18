<?php
declare( strict_types = 1 );

namespace MediaWiki\Composer\PhpUnitSplitter;

use Exception;

/**
 * @license GPL-2.0-or-later
 */
class LockingException extends Exception {
	public function __construct( string $lockFilePath ) {
		parent::__construct(
			"Unable to acquire lock for file at path '" . $lockFilePath . "'"
		);
	}

}
