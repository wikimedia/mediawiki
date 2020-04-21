<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface RejectParserCacheValueHook {
	/**
	 * Return false to reject an otherwise usable
	 * cached value from the Parser cache. NOTE: CARELESS USE OF THIS HOOK CAN
	 * HAVE CATASTROPHIC CONSEQUENCES FOR HIGH-TRAFFIC INSTALLATIONS. USE WITH
	 * EXTREME CARE.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $parserOutput ParserOutput value.
	 * @param ?mixed $wikiPage WikiPage object.
	 * @param ?mixed $parserOptions ParserOptions object.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onRejectParserCacheValue( $parserOutput, $wikiPage,
		$parserOptions
	);
}
