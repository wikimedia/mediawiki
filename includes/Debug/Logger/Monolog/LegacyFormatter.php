<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Logger\Monolog;

use MediaWiki\Logger\LegacyLogger;
use Monolog\Formatter\NormalizerFormatter;

/**
 * Log message formatter that mimics the legacy log message formatting of `wfDebug`, `wfDebugLog`,
 * `wfLogDBError` and the former `wfErrorLog` global functions by delegating the formatting to
 * \MediaWiki\Logger\LegacyLogger.
 *
 * @deprecated since 1.32
 * @since 1.25
 * @ingroup Debug
 * @copyright © 2013 Wikimedia Foundation and contributors
 */
class LegacyFormatter extends NormalizerFormatter {

	public function __construct() {
		parent::__construct( 'c' );
	}

	public function format( array $record ): string {
		$normalized = parent::format( $record );
		return LegacyLogger::format(
			$normalized['channel'], $normalized['message'], $normalized
		);
	}
}
