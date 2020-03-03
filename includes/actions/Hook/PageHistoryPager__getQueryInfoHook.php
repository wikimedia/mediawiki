<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface PageHistoryPager__getQueryInfoHook {
	/**
	 * when a history pager query parameter set is
	 * constructed.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $pager the pager
	 * @param ?mixed &$queryInfo the query parameters
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPageHistoryPager__getQueryInfo( $pager, &$queryInfo );
}
