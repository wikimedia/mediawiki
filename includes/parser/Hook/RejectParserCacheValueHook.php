<?php

namespace MediaWiki\Hook;

use ParserOptions;
use ParserOutput;
use WikiPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface RejectParserCacheValueHook {
	/**
	 * Use this hook to reject an otherwise usable cached value from the Parser cache.
	 * NOTE: CARELESS USE OF THIS HOOK CAN HAVE CATASTROPHIC CONSEQUENCES
	 * FOR HIGH-TRAFFIC INSTALLATIONS. USE WITH EXTREME CARE.
	 *
	 * @since 1.35
	 *
	 * @param ParserOutput $parserOutput ParserOutput value
	 * @param WikiPage $wikiPage
	 * @param ParserOptions $parserOptions
	 * @return bool|void True or no return value to continue, or false to reject
	 *   an otherwise usable cached value from the Parser cache
	 */
	public function onRejectParserCacheValue( $parserOutput, $wikiPage,
		$parserOptions
	);
}
