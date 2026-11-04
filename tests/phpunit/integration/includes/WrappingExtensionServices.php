<?php

declare( strict_types=1 );

namespace MediaWiki\Tests;

use MediaWiki\MediaWikiServices;
use MediaWiki\Search\TitleMatcher;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\Title\TitleParser;

/**
 * An ExtensionServices class with a wrap() method, for WrappingExtensionServicesTest.
 *
 * @license GPL-2.0-or-later
 */
class WrappingExtensionServices {

	public function __construct( private readonly MediaWikiServices $services ) {
	}

	public static function wrap( MediaWikiServices $services ): self {
		return new self( $services );
	}

	public function getTitleFormatter(): TitleFormatter {
		return $this->services->getService( 'TitleFormatter' );
	}

	public function getTitleMatcher(): TitleMatcher {
		return $this->services->get( 'TitleMatcher' );
	}

	public function getParser(): TitleParser {
		return $this->services->get( 'TitleParser' );
	}

}
