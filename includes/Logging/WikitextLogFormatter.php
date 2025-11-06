<?php
/**
 * Formatter to allow log entries to contain formatted wikitext.
 *
 * @license GPL-2.0-or-later
 * @file
 * @license GPL-2.0-or-later
 */

namespace MediaWiki\Logging;

/**
 * Log formatter specifically for log entries containing wikitext.
 * @since 1.31
 */
class WikitextLogFormatter extends LogFormatter {
	/**
	 * @return string
	 */
	public function getActionMessage() {
		return parent::getActionMessage()->parse();
	}
}

/** @deprecated class alias since 1.44 */
class_alias( WikitextLogFormatter::class, 'WikitextLogFormatter' );
