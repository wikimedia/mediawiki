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
