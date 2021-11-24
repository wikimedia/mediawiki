<?php

namespace MediaWiki\Hook;

use OutputPage;
use ParserOutput;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "OutputPageParserOutput" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface OutputPageParserOutputHook {
	/**
	 * This hook is called after adding a parserOutput to $wgOut.
	 *
	 * @since 1.35
	 *
	 * @param OutputPage $outputPage
	 * @param ParserOutput $parserOutput ParserOutput instance being added in $outputPage
	 * @return void This hook must not abort, it must return no value
	 */
	public function onOutputPageParserOutput( $outputPage, $parserOutput ): void;
}
