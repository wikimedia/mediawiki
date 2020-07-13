<?php

namespace MediaWiki\Hook;

use ParserCache;
use ParserOptions;
use ParserOutput;
use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ParserCacheSaveCompleteHook {
	/**
	 * This hook is called after a ParserOutput has been committed to
	 * the parser cache.
	 *
	 * @since 1.35
	 *
	 * @param ParserCache $parserCache ParserCache object $parserOutput was stored in
	 * @param ParserOutput $parserOutput ParserOutput object that was stored
	 * @param Title $title Title of the page that was parsed to generate $parserOutput
	 * @param ParserOptions $popts ParserOptions used for generating $parserOutput
	 * @param int $revId ID of the revision that was parsed to create $parserOutput
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserCacheSaveComplete( $parserCache, $parserOutput, $title,
		$popts, $revId
	);
}
