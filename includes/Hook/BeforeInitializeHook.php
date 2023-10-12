<?php

namespace MediaWiki\Hook;

use MediaWiki\Actions\ActionEntryPoint;
use MediaWiki\Output\OutputPage;
use MediaWiki\Request\WebRequest;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "BeforeInitialize" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface BeforeInitializeHook {
	/**
	 * This hook is called before anything is initialized in ActionEntryPoint::performRequest().
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title being used for request
	 * @param null $unused
	 * @param OutputPage $output
	 * @param User $user
	 * @param WebRequest $request
	 * @param ActionEntryPoint $mediaWikiEntryPoint (changed from MediaWiki
	 *        to MediaWikiEntryPoint in MW 1.42)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBeforeInitialize(
		$title,
		$unused,
		$output,
		$user,
		$request,
		$mediaWikiEntryPoint
	);
}
