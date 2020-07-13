<?php

namespace MediaWiki\Hook;

use OutputPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface OutputPageBeforeHTMLHook {
	/**
	 * This hook is called when a page has been processed by the parser and the
	 * resulting HTML is about to be displayed.
	 *
	 * @since 1.35
	 *
	 * @param OutputPage $out OutputPage object that corresponds to the page
	 * @param string &$text Text that will be displayed, in HTML
	 * @return bool|void This hook must not abort, it must return true or null.
	 */
	public function onOutputPageBeforeHTML( $out, &$text );
}
