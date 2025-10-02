<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author Derick Alangi
 */

namespace MediaWiki\Page;

use MediaWiki\Linker\LinkTarget;

/**
 * Service for resolving a wiki page redirect.
 *
 * Default implementation is RedirectStore.
 *
 * @unstable
 * @since 1.38
 */
interface RedirectLookup {
	/**
	 * Get the redirect destination.
	 *
	 * @since 1.38
	 * @param PageIdentity $page
	 * @return LinkTarget|null Returns null if this page is not a redirect
	 * @throws PageAssertionException If page does not represent an editable page
	 */
	public function getRedirectTarget( PageIdentity $page ): ?LinkTarget;
}
