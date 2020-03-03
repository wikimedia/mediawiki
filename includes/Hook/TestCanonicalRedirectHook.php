<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface TestCanonicalRedirectHook {
	/**
	 * Called when about to force a redirect to a canonical
	 * URL for a title when we have no other parameters on the URL. Gives a chance for
	 * extensions that alter page view behavior radically to abort that redirect or
	 * handle it manually.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $request WebRequest
	 * @param ?mixed $title Title of the currently found title obj
	 * @param ?mixed $output OutputPage object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTestCanonicalRedirect( $request, $title, $output );
}
