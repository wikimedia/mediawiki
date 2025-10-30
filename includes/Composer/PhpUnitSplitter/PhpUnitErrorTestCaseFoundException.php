<?php

declare( strict_types = 1 );

namespace MediaWiki\Composer\PhpUnitSplitter;

/**
 * @license GPL-2.0-or-later
 */
class PhpUnitErrorTestCaseFoundException extends \Exception {

	public function __construct() {
		parent::__construct( "Encountered PHPUnit ErrorTestCase - check for a syntax error in the test " .
			"suite or an error in a dataProvider!" );
	}

}
