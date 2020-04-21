<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface WikiPageFactoryHook {
	/**
	 * Override WikiPage class used for a title
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title Title of the page
	 * @param ?mixed &$page Variable to set the created WikiPage to.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWikiPageFactory( $title, &$page );
}
