<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface MediaWikiPerformActionHook {
	/**
	 * Override MediaWiki::performAction(). Use this to do
	 * something completely different, after the basic globals have been set up, but
	 * before ordinary actions take place.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $output Context output
	 * @param ?mixed $article Article on which the action will be performed
	 * @param ?mixed $title Title on which the action will be performed
	 * @param ?mixed $user Context user
	 * @param ?mixed $request Context request
	 * @param ?mixed $mediaWiki The $mediawiki object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMediaWikiPerformAction( $output, $article, $title, $user,
		$request, $mediaWiki
	);
}
