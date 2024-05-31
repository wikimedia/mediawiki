<?php

declare( strict_types = 1 );

namespace MediaWiki\Composer\PhpUnitSplitter;

/**
 * @license GPL-2.0-or-later
 */
class TestListMissingException extends \Exception {

	public function __construct( string $testListFile ) {
		parent::__construct( "Could not find test list at " . $testListFile );
	}

}
