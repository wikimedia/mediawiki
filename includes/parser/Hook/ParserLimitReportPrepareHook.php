<?php

namespace MediaWiki\Hook;

use Parser;
use ParserOutput;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ParserLimitReportPrepareHook {
	/**
	 * This hook is called at the end of Parser:parse() when the parser
	 * will include comments about size of the text parsed. Hooks should use
	 * $output->setLimitReportData() to populate data. Functions for this hook should
	 * not use $wgLang; do that in ParserLimitReportFormat instead.
	 *
	 * @since 1.35
	 *
	 * @param Parser $parser
	 * @param ParserOutput $output
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserLimitReportPrepare( $parser, $output );
}
