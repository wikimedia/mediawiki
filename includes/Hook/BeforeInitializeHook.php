<?php

namespace MediaWiki\Hook;

use MediaWiki;
use OutputPage;
use Title;
use User;
use WebRequest;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface BeforeInitializeHook {
	/**
	 * This hook is called before anything is initialized in MediaWiki::performRequest().
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title being used for request
	 * @param null $unused Null
	 * @param OutputPage $output
	 * @param User $user
	 * @param WebRequest $request
	 * @param MediaWiki $mediaWiki
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBeforeInitialize( $title, $unused, $output, $user, $request,
		$mediaWiki
	);
}
