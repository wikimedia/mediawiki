<?php

namespace MediaWiki\Hook;

use Title;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "GetInternalURL" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface GetInternalURLHook {
	/**
	 * Use this hook to modify fully-qualified URLs used for squid cache purging.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title object of page
	 * @param string &$url String value as output (out parameter, can modify)
	 * @param string $query Query options as string passed to Title::getInternalURL()
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetInternalURL( $title, &$url, $query );
}
