<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ParserLimitReportFormatHook {
	/**
	 * Called for each row in the parser limit report that
	 * needs formatting. If nothing handles this hook, the default is to use "$key" to
	 * get the label, and "$key-value" or "$key-value-text"/"$key-value-html" to
	 * format the value.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $key Key for the limit report item (string)
	 * @param ?mixed &$value Value of the limit report item
	 * @param ?mixed &$report String onto which to append the data
	 * @param ?mixed $isHTML If true, $report is an HTML table with two columns; if false, it's
	 *   text intended for display in a monospaced font.
	 * @param ?mixed $localize If false, $report should be output in English.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onParserLimitReportFormat( $key, &$value, &$report, $isHTML,
		$localize
	);
}
