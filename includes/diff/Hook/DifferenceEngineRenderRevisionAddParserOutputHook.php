<?php

namespace MediaWiki\Diff\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface DifferenceEngineRenderRevisionAddParserOutputHook {
	/**
	 * Allows extensions to change the
	 * parser output. Return false to not add parser output via OutputPage's
	 * addParserOutput method.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $differenceEngine DifferenceEngine object
	 * @param ?mixed $out OutputPage object
	 * @param ?mixed $parserOutput ParserOutput object
	 * @param ?mixed $wikiPage WikiPage object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDifferenceEngineRenderRevisionAddParserOutput(
		$differenceEngine, $out, $parserOutput, $wikiPage
	);
}
