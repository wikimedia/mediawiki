<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use MediaWiki\Title\Title;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "GetLocalURL::Internal" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface GetLocalURL__InternalHook {
	/**
	 * Use this hook to modify local URLs to internal pages.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title object of page
	 * @param string &$url String value as output (out parameter, can modify)
	 * @param string $query Query options as string passed to Title::getLocalURL()
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetLocalURL__Internal( $title, &$url, $query );
}
