<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Page;

/**
 * Service for page rename actions.
 *
 * Default implementation is MediaWiki\Page\PageCommandFactory.
 *
 * @since 1.35
 */
interface MovePageFactory {

	/**
	 * @param PageIdentity $from
	 * @param PageIdentity $to
	 * @return MovePage
	 */
	public function newMovePage( PageIdentity $from, PageIdentity $to ): MovePage;
}
