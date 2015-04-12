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

namespace MediaWiki\Logger;

use Psr\Log\AbstractLogger;

/**
 * PSR-3 logger that wraps wfDebugLog() for backwards compatibility.
 *
 * All levels are passed to wfDebugLog() with the specific level being ignored.
 *
 * @see \MediaWiki\Logger\LoggerFactory
 * @since 1.23.10
 * @author Bryan Davis <bd808@wikimedia.org>
 * @copyright © 2015 Bryan Davis and Wikimedia Foundation.
 */
class BackcompatLogger extends AbstractLogger {

	/**
	 * @var string $channel
	 */
	private $channel;

	/**
	 * @param string $channel
	 */
	public function __construct( $channel ) {
		$this->channel = $channel;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * Log events are converted into wfDebugLog() calls after interpolating
	 * the message with the provided context.
	 *
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 */
	public function log( $level, $message, array $context = array() ) {
		\wfDebugLog(
			$this->channel, self::interpolate( $message, $context ), 'log'
		);
	}

	/**
	 * Interpolate context values into the message placeholders.
	 *
	 * Copied from PSR-3 specification.
	 *
	 * @link https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#12-message
	 * @param string $message
	 * @param array $context
	 * @return string
	 */
	private static function interpolate( $message, array $context = array() ) {
		$replace = array();
		foreach ( $context as $key => $val ) {
			$replace['{' . $key . '}'] = $val;
		}
		return strtr( $message, $replace );
	}
}
