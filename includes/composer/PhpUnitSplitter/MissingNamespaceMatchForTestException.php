<?php

declare( strict_types = 1 );

namespace MediaWiki\Composer\PhpUnitSplitter;

use Exception;

/**
 * @license GPL-2.0-or-later
 */
class MissingNamespaceMatchForTestException extends Exception {
	public function __construct( TestDescriptor $testDescriptor ) {
		parent::__construct(
			"Could not match " . $testDescriptor->getFullClassname() . " to a namespace in a php test file"
		);
	}

}
