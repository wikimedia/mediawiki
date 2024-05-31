<?php

declare( strict_types = 1 );

namespace MediaWiki\Composer\PhpUnitSplitter;

/**
 * @license GPL-2.0-or-later
 */
class SuiteGenerationException extends \Exception {

	public function __construct( int $groupId ) {
		parent::__construct( "Unable to find suite split_group_" . $groupId );
	}

}
