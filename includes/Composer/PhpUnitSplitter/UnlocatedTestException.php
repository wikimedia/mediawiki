<?php

declare( strict_types = 1 );

namespace MediaWiki\Composer\PhpUnitSplitter;

/**
 * @license GPL-2.0-or-later
 */
class UnlocatedTestException extends \Exception {

	public function __construct( TestDescriptor $testDescriptor, string $filename ) {
		parent::__construct(
			"Could not find file for class " . $testDescriptor->getFullClassname() .
			" (expected .../$filename)"
		);
	}

}
