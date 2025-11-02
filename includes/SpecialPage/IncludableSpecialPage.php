<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\SpecialPage;

/**
 * Shortcut to construct an includable special page.
 *
 * @ingroup SpecialPage
 */
class IncludableSpecialPage extends SpecialPage {
	/**
	 * @stable to call
	 *
	 * @param string $name
	 * @param string $restriction
	 * @param bool $listed
	 * @param callable|bool $function Unused
	 * @param string $file Unused
	 */
	public function __construct(
		$name, $restriction = '', $listed = true, $function = false, $file = 'default'
	) {
		parent::__construct( $name, $restriction, $listed, $function, $file, true );
	}

	/** @inheritDoc */
	public function isIncludable() {
		return true;
	}
}

/** @deprecated class alias since 1.41 */
class_alias( IncludableSpecialPage::class, 'IncludableSpecialPage' );
