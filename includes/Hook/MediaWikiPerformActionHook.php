<?php

namespace MediaWiki\Hook;

use Article;
use MediaWiki;
use OutputPage;
use Title;
use User;
use WebRequest;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface MediaWikiPerformActionHook {
	/**
	 * Use this hook to override MediaWiki::performAction(). Use this to do
	 * something completely different, after the basic globals have been set up, but
	 * before ordinary actions take place.
	 *
	 * @since 1.35
	 *
	 * @param OutputPage $output Context output
	 * @param Article $article Article on which the action will be performed
	 * @param Title $title Title on which the action will be performed
	 * @param User $user Context user
	 * @param WebRequest $request Context request
	 * @param MediaWiki $mediaWiki
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMediaWikiPerformAction( $output, $article, $title, $user,
		$request, $mediaWiki
	);
}
