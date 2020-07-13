<?php

namespace MediaWiki\Hook;

use HistoryPager;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable to implement
 * @ingroup Hooks
 */
interface PageHistoryPager__getQueryInfoHook {
	/**
	 * This hook is called when a history pager query parameter set is constructed.
	 *
	 * @since 1.35
	 *
	 * @param HistoryPager $pager
	 * @param array &$queryInfo The query parameters
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPageHistoryPager__getQueryInfo( $pager, &$queryInfo );
}
