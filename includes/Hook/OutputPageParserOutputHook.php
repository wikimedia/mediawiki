<?php

namespace MediaWiki\Hook;

use OutputPage;
use ParserOutput;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface OutputPageParserOutputHook {
	/**
	 * This hook is called after adding a parserOutput to $wgOut.
	 *
	 * @since 1.35
	 *
	 * @param OutputPage $out
	 * @param ParserOutput $parserOutput ParserOutput instance being added in $out
	 * @return void This hook must not abort, it must return no value
	 */
	public function onOutputPageParserOutput( $out, $parserOutput ) : void;
}
