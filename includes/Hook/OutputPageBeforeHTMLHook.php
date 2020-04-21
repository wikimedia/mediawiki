<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface OutputPageBeforeHTMLHook {
	/**
	 * A page has been processed by the parser and the
	 * resulting HTML is about to be displayed.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $out the OutputPage (object) that corresponds to the page
	 * @param ?mixed &$text the text that will be displayed, in HTML (string)
	 * @return bool|void This hook must not abort, it must return true or null.
	 */
	public function onOutputPageBeforeHTML( $out, &$text );
}
