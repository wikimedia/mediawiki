<?php

namespace MediaWiki\Hook;

use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialSearchCreateLinkHook {
	/**
	 * This hook is called when making the message to create a page or go to the existing page.
	 *
	 * @since 1.35
	 *
	 * @param Title $t Title object searched for
	 * @param array &$params An array of the default message name and page title (as parameter)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialSearchCreateLink( $t, &$params );
}
