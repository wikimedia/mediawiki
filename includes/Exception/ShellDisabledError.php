<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Exception;

use Exception;

/**
 * @newable
 * @since 1.30
 */
class ShellDisabledError extends Exception {

	/**
	 * @stable to call
	 */
	public function __construct() {
		parent::__construct( 'Unable to run external programs, proc_open() is disabled' );
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ShellDisabledError::class, 'MediaWiki\\ShellDisabledError' );
