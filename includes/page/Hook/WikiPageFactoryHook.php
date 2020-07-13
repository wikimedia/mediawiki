<?php

namespace MediaWiki\Page\Hook;

use Title;
use WikiPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface WikiPageFactoryHook {
	/**
	 * Use this hook to override WikiPage class used for a title.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title of the page
	 * @param WikiPage &$page Variable to set the created WikiPage to
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWikiPageFactory( $title, &$page );
}
