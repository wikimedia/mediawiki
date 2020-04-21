<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface InitializeArticleMaybeRedirectHook {
	/**
	 * MediaWiki check to see if title is a redirect.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title Title object for the current page
	 * @param ?mixed $request WebRequest
	 * @param ?mixed &$ignoreRedirect boolean to skip redirect check
	 * @param ?mixed &$target Title/string of redirect target
	 * @param ?mixed &$article Article object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onInitializeArticleMaybeRedirect( $title, $request,
		&$ignoreRedirect, &$target, &$article
	);
}
