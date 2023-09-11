<?php

namespace MediaWiki\Hook;

use Article;
use MediaWiki\Request\WebRequest;
use MediaWiki\Title\Title;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "InitializeArticleMaybeRedirect" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface InitializeArticleMaybeRedirectHook {
	/**
	 * Use this hook to override whether a title is a redirect.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title object for the current page
	 * @param WebRequest $request
	 * @param bool &$ignoreRedirect When set to true, the title will not redirect.
	 * @param Title|string &$target Set to an URL to do a HTTP redirect, or a Title to
	 *   use that title instead of the original, without doing a HTTP redirect.
	 * @param Article &$article The Article object that belongs to $title. Passed as a reference
	 *   for legacy reasons, but should not be changed.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onInitializeArticleMaybeRedirect( $title, $request,
		&$ignoreRedirect, &$target, &$article
	);
}
