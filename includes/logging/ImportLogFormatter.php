<?php
/**
 * Formatter for import log entries.
 *
 * @license GPL-2.0-or-later
 * @file
 * @license GPL-2.0-or-later
 * @since 1.27
 */

namespace MediaWiki\Logging;

/**
 * This class formats import log entries.
 *
 * @since 1.27
 */
class ImportLogFormatter extends LogFormatter {
	/** @inheritDoc */
	protected function getMessageKey() {
		$key = parent::getMessageKey();
		$params = $this->extractParameters();
		if ( isset( $params[3] ) ) {
			// New log items with more details
			// Messages: logentry-import-upload-details, logentry-import-interwiki-details
			$key .= '-details';
		}

		return $key;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ImportLogFormatter::class, 'ImportLogFormatter' );
