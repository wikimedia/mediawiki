<?php

namespace MediaWiki\Hook;

use MediaWiki;
use OutputPage;
use Title;
use User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "BeforeInitialize" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface BeforeInitializeHook {
	/**
	 * This hook is called before anything is initialized in MediaWiki::performRequest().
	 *
	 * @param Title $title Title being used for request
	 * @param null $unused
	 * @param OutputPage $output
	 * @param User $user
	 * @param \MediaWiki\Request\WebRequest $request
	 * @param MediaWiki $mediaWiki
	 * @return bool|void True or no return value to continue or false to abort
	 * @since 1.35
	 *
	 */
	public function onBeforeInitialize( $title, $unused, $output, $user, $request,
		$mediaWiki
	);
}
