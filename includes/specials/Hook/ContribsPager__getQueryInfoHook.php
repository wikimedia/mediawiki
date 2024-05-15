<?php

namespace MediaWiki\Hook;

use MediaWiki\Pager\ContributionsPager;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ContribsPager::getQueryInfo" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ContribsPager__getQueryInfoHook {
	/**
	 * This hook is called before the contributions query is about to run
	 *
	 * @since 1.35
	 *
	 * @param ContributionsPager $pager Pager object for contributions
	 * @param array &$queryInfo The query for the contribs Pager
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContribsPager__getQueryInfo( $pager, &$queryInfo );
}
