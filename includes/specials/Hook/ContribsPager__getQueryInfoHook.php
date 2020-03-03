<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ContribsPager__getQueryInfoHook {
	/**
	 * Before the contributions query is about to run
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $pager Pager object for contributions
	 * @param ?mixed &$queryInfo The query for the contribs Pager
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContribsPager__getQueryInfo( $pager, &$queryInfo );
}
