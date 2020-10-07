<?php

namespace MediaWiki\Hook;

use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialSearchGoResultHook {
	/**
	 * Use this hook to alter the behaviour of the 'go' feature when searching
	 *
	 * If a hook returns false the 'go' feature will be canceled and a normal search will be
	 * performed. Returning true without setting $url does a standard redirect to $title.
	 * Setting $url redirects to the specified URL.
	 *
	 * @since 1.35
	 *
	 * @param string $term The string the user searched for
	 * @param Title $title The title the 'go' feature has decided to forward the user to
	 * @param string|null &$url Initially null, hook subscribers can set this to specify
	 *   the final url to redirect to
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialSearchGoResult( $term, $title, &$url );
}
