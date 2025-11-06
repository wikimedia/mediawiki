<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\SpecialPage\UnlistedSpecialPage;

/**
 * Blank page designed for basic benchmarking of MediaWiki.
 *
 * Intentionally does not do much.
 *
 * @ingroup SpecialPage
 */
class SpecialBlankpage extends UnlistedSpecialPage {
	public function __construct() {
		parent::__construct( 'Blankpage' );
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$this->setHeaders();
		$this->getOutput()->addWikiMsg( 'intentionallyblankpage' );
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialBlankpage::class, 'SpecialBlankpage' );
