<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface PageRenderingHashHook {
	/**
	 * NOTE: Consider using ParserOptionsRegister instead.
	 * Alter the parser cache option hash key. A parser extension
	 * which depends on user options should install this hook and append its values to
	 * the key.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$confstr reference to a hash key string which can be modified
	 * @param ?mixed $user User (object) requesting the page
	 * @param ?mixed &$forOptions array of options the hash is for
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPageRenderingHash( &$confstr, $user, &$forOptions );
}
