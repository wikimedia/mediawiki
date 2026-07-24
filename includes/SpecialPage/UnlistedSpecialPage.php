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
abstract class UnlistedSpecialPage extends SpecialPage {

	/**
	 * @stable to call
	 *
	 * @param string $name
	 * @param string $restriction
	 *  Deprecated since 1.46, override the method getRestriction() instead.
	 * @param bool $function Unused. Deprecated since 1.46.
	 * @param string $file Unused. Deprecated since 1.46.
	 */
	public function __construct( $name, $restriction = '', $function = false, $file = 'default' ) {
		$parentParams = [ $name ];
		if ( func_num_args() > 1 ) {
			wfDeprecated( __CLASS__ . ' constructor parameters $restriction, $function and $file', '1.46' );
			$parentParams[] = $restriction;
		}
		parent::__construct( ...$parentParams );
	}

	/**
	 * @codeCoverageIgnore Merely declarative
	 * @inheritDoc
	 */
	public function isListed() {
		return false;
	}
}

// @codeCoverageIgnoreStart
/** @deprecated class alias since 1.41 */
class_alias( UnlistedSpecialPage::class, 'UnlistedSpecialPage' );
// @codeCoverageIgnoreEnd
