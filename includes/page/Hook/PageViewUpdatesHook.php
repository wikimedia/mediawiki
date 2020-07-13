<?php

namespace MediaWiki\Page\Hook;

use User;
use WikiPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface PageViewUpdatesHook {
	/**
	 * Use this hook to make database (or other) changes after a
	 * page view is seen by MediaWiki.  Note this does not capture views made
	 * via external caches such as Squid.
	 *
	 * @since 1.35
	 *
	 * @param WikiPage $wikipage Page being viewed
	 * @param User $user User who is viewing
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPageViewUpdates( $wikipage, $user );
}
