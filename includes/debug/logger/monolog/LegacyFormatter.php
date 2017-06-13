<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Logger\Monolog;

use MediaWiki\Logger\LegacyLogger;
use Monolog\Formatter\NormalizerFormatter;

/**
 * Log message formatter that mimics the legacy log message formatting of
 * `wfDebug`, `wfDebugLog`, `wfLogDBError` and `wfErrorLog` global functions by
 * delegating the formatting to \MediaWiki\Logger\LegacyLogger.
 *
 * @since 1.25
 * @copyright © 2013 Wikimedia Foundation and contributors
 * @see \MediaWiki\Logger\LegacyLogger
 */
class LegacyFormatter extends NormalizerFormatter {

	public function __construct() {
		parent::__construct( 'c' );
	}

	public function format( array $record ) {
		$normalized = parent::format( $record );
		return LegacyLogger::format(
			$normalized['channel'], $normalized['message'], $normalized
		);
	}
}
