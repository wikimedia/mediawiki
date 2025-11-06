<?php

namespace MediaWiki\Hook;

use MediaWiki\Specials\SpecialWantedPages;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "WantedPages::getQueryInfo" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface WantedPages__getQueryInfoHook {
	/**
	 * This hook is called in SpecialWantedPages::getQueryInfo(). Can be used to alter the SQL query.
	 *
	 * @since 1.35
	 *
	 * @param SpecialWantedPages $wantedPages
	 * @param array &$query Query array. See QueryPage::getQueryInfo() for format documentation.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWantedPages__getQueryInfo( $wantedPages, &$query );
}
