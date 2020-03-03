<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface PageViewUpdatesHook {
	/**
	 * Allow database (or other) changes to be made after a
	 * page view is seen by MediaWiki.  Note this does not capture views made
	 * via external caches such as Squid.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $wikipage WikiPage (object) for the page being viewed.
	 * @param ?mixed $user User (object) for the user who is viewing.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPageViewUpdates( $wikipage, $user );
}
