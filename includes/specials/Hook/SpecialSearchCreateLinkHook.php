<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialSearchCreateLinkHook {
	/**
	 * Called when making the message to create a page or
	 * go to the existing page.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $t title object searched for
	 * @param ?mixed &$params an array of the default message name and page title (as parameter)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialSearchCreateLink( $t, &$params );
}
