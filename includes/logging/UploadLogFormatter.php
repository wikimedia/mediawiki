<?php
/**
 * Formatter for upload log entries.
 *
 * @license GPL-2.0-or-later
 * @file
 * @license GPL-2.0-or-later
 * @since 1.25
 */

namespace MediaWiki\Logging;

/**
 * This class formats upload log entries.
 *
 * @since 1.25
 */
class UploadLogFormatter extends LogFormatter {

	/** @inheritDoc */
	protected function getParametersForApi() {
		$entry = $this->entry;
		$params = $entry->getParameters();

		static $map = [
			'img_timestamp' => ':timestamp:img_timestamp',
		];
		foreach ( $map as $index => $key ) {
			if ( isset( $params[$index] ) ) {
				$params[$key] = $params[$index];
				unset( $params[$index] );
			}
		}

		return $params;
	}

}

/** @deprecated class alias since 1.44 */
class_alias( UploadLogFormatter::class, 'UploadLogFormatter' );
