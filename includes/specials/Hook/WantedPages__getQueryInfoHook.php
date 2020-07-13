<?php

namespace MediaWiki\Hook;

use WantedPagesPage;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable to implement
 * @ingroup Hooks
 */
interface WantedPages__getQueryInfoHook {
	/**
	 * This hook is called in WantedPagesPage::getQueryInfo(). Can be used to alter the SQL query.
	 *
	 * @since 1.35
	 *
	 * @param WantedPagesPage $wantedPages
	 * @param array &$query Query array. See QueryPage::getQueryInfo() for format documentation.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWantedPages__getQueryInfo( $wantedPages, &$query );
}
