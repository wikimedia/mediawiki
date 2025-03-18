<?php

declare( strict_types = 1 );

namespace MediaWiki\Composer\PhpUnitSplitter;

/**
 * @license GPL-2.0-or-later
 */
class PhpUnitResultsCachingException extends \RuntimeException {

	public function __construct( string $message ) {
		parent::__construct( "Error processing PHPUnit results cache: " . $message );
	}

}
