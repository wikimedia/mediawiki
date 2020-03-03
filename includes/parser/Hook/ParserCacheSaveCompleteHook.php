<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ParserCacheSaveCompleteHook {
	/**
	 * Called after a ParserOutput has been committed to
	 * the parser cache.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $parserCache ParserCache object $parserOutput was stored in
	 * @param ?mixed $parserOutput ParserOutput object that was stored
	 * @param ?mixed $title Title of the page that was parsed to generate $parserOutput
	 * @param ?mixed $popts ParserOptions used for generating $parserOutput
	 * @param ?mixed $revId ID of the revision that was parsed to create $parserOutput
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserCacheSaveComplete( $parserCache, $parserOutput, $title,
		$popts, $revId
	);
}
