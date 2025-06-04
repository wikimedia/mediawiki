<?php

namespace MediaWiki\Composer\PhpUnitSplitter;

use Exception;

/**
 * @license GPL-2.0-or-later
 */
class InvalidSplitGroupCountException extends Exception {
	public function __construct( string $splitGroupCount ) {
		parent::__construct(
			"Invalid split group count (must be an integer > 2): " . $splitGroupCount
		);
	}

}
