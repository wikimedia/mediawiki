<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\SpecialPage;

/**
 * Shortcut to construct an includable special page.
 *
 * @stable to extend
 * @ingroup SpecialPage
 */
abstract class IncludableSpecialPage extends SpecialPage {
	/**
	 * @stable to call
	 *
	 * @param string $name
	 * @param string $restriction
	 * @param bool $listed
	 *  Deprecated since 1.46, override the method isListed() instead.
	 * @param callable|bool $function Unused. Deprecated since 1.46.
	 * @param string $file Unused. Deprecated since 1.46.
	 */
	public function __construct(
		$name, $restriction = '', $listed = true, $function = false, $file = 'default'
	) {
		parent::__construct( ...func_get_args() );
		$this->mIncludable = true;
	}

	/**
	 * @codeCoverageIgnore Merely declarative
	 * @inheritDoc
	 */
	public function isIncludable() {
		return true;
	}
}

// @codeCoverageIgnoreStart
/** @deprecated class alias since 1.41 */
class_alias( IncludableSpecialPage::class, 'IncludableSpecialPage' );
// @codeCoverageIgnoreEnd
