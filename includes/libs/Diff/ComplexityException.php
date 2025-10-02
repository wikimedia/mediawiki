<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup DifferenceEngine
 */

namespace Wikimedia\Diff;

use Exception;

/**
 * @newable
 */
class ComplexityException extends Exception {

	/**
	 * @stable to call
	 */
	public function __construct() {
		parent::__construct( 'Diff is too complex to generate' );
	}
}

/** @deprecated class alias since 1.41 */
class_alias( ComplexityException::class, 'MediaWiki\\Diff\\ComplexityException' );
