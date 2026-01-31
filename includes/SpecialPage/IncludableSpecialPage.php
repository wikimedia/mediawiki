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
