<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Exception;

use Exception;

/**
 * @newable
 */
class ProcOpenError extends Exception {
	/**
	 * @stable to call
	 */
	public function __construct() {
		parent::__construct( 'proc_open() returned error!' );
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ProcOpenError::class, 'MediaWiki\\ProcOpenError' );
