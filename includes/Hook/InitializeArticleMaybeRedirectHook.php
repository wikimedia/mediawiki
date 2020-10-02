<?php

namespace MediaWiki\Hook;

use Article;
use Title;
use WebRequest;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "InitializeArticleMaybeRedirect" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface InitializeArticleMaybeRedirectHook {
	/**
	 * Use this hook to check whether a title is a redirect.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title object for the current page
	 * @param WebRequest $request
	 * @param bool &$ignoreRedirect Boolean to skip redirect check
	 * @param Title|string &$target Title/string of redirect target
	 * @param Article &$article
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onInitializeArticleMaybeRedirect( $title, $request,
		&$ignoreRedirect, &$target, &$article
	);
}
