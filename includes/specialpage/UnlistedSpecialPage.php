<?php
/**
 * Shortcut to construct a special page which is unlisted by default.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup SpecialPage
 */

namespace MediaWiki\SpecialPage;

/**
 * Shortcut to construct a special page which is unlisted by default.
 *
 * @stable to extend
 *
 * @ingroup SpecialPage
 */
class UnlistedSpecialPage extends SpecialPage {

	/**
	 * @stable to call
	 *
	 * @param string $name
	 * @param string $restriction
	 * @param bool $function
	 * @param string $file
	 */
	public function __construct( $name, $restriction = '', $function = false, $file = 'default' ) {
		parent::__construct( $name, $restriction, false, $function, $file );
	}

	/** @inheritDoc */
	public function isListed() {
		return false;
	}
}

/** @deprecated class alias since 1.41 */
class_alias( UnlistedSpecialPage::class, 'UnlistedSpecialPage' );
