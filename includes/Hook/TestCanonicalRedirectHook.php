<?php

namespace MediaWiki\Hook;

use OutputPage;
use Title;
use WebRequest;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface TestCanonicalRedirectHook {
	/**
	 * This hook is called when about to force a redirect to a canonical
	 * URL for a title when we have no other parameters on the URL. Use this
	 * hook to alter page view behavior radically to abort that redirect or
	 * handle it manually.
	 *
	 * @since 1.35
	 *
	 * @param WebRequest $request
	 * @param Title $title Title of the currently found Title object
	 * @param OutputPage $output
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTestCanonicalRedirect( $request, $title, $output );
}
