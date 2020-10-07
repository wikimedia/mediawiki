<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ParserLimitReportFormatHook {
	/**
	 * This hook is called for each row in the parser limit report that
	 * needs formatting. If nothing handles this hook, the default is to use "$key" to
	 * get the label, and "$key-value" or "$key-value-text"/"$key-value-html" to
	 * format the value.
	 *
	 * @since 1.35
	 *
	 * @param string $key Key for the limit report item
	 * @param string &$value Value of the limit report item
	 * @param string &$report String onto which to append the data
	 * @param string $isHTML If true, $report is an HTML table with two columns; if false, it's
	 *   text intended for display in a monospaced font
	 * @param bool $localize If false, $report should be output in English
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserLimitReportFormat( $key, &$value, &$report, $isHTML,
		$localize
	);
}
