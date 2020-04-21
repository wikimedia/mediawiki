<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface WantedPages__getQueryInfoHook {
	/**
	 * Called in WantedPagesPage::getQueryInfo(), can be
	 * used to alter the SQL query which gets the list of wanted pages.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $wantedPages WantedPagesPage object
	 * @param ?mixed &$query query array, see QueryPage::getQueryInfo() for format documentation
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWantedPages__getQueryInfo( $wantedPages, &$query );
}
