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

namespace Psr\Log;

/**
 * @link https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
 */
interface LoggerInterface {
	public function emergency( $message, array $context = array() );
	public function alert( $message, array $context = array() );
	public function critical( $message, array $context = array() );
	public function error( $message, array $context = array() );
	public function warning( $message, array $context = array() );
	public function notice( $message, array $context = array() );
	public function info( $message, array $context = array() );
	public function debug( $message, array $context = array() );
	public function log( $level, $message, array $context = array() );
}

namespace MediaWiki\Logger;

/**
 * PSR-3 logger that wraps wfDebugLog() for backwards compatibility.
 *
 * @since 1.23.10
 * @author Bryan Davis <bd808@wikimedia.org>
 * @copyright © 2015 Bryan Davis and Wikimedia Foundation.
 */
class Logger implements \Psr\Log\LoggerInterface {

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

	public function emergency( $message, array $context = array() ) {
		$this->log( 'emergency', $message, $context );
	}

	public function alert( $message, array $context = array() ) {
		$this->log( 'alert', $message, $context );
	}

	public function critical( $message, array $context = array() ) {
		$this->log( 'critical', $message, $context );
	}

	public function error( $message, array $context = array() ) {
		$this->log( 'error', $message, $context );
	}

	public function warning( $message, array $context = array() ) {
		$this->log( 'warning', $message, $context );
	}

	public function notice( $message, array $context = array() ) {
		$this->log( 'notice', $message, $context );
	}

	public function info( $message, array $context = array() ) {
		$this->log( 'info', $message, $context );
	}

	public function debug( $message, array $context = array() ) {
		$this->log( 'debug', $message, $context );
	}

	public function log( $level, $message, array $context = array() ) {
		\wfDebugLog(
			$this->channel, self::interpolate( $message, $context ), 'log'
		);
	}

	private static function interpolate( $message, array $context = array() ) {
		$replace = array();
		foreach ( $context as $key => $val ) {
			$replace['{' . $key . '}'] = $val;
		}
		return strtr( $message, $replace );
	}
}

/**
 * Backwards compatible PSR-3 logger instance factory.
 *
 * PSR-3 debug logging was introduced to MediaWiki in 1.25. This class
 * provides a backward compatible PSR-3 logging layer to make backporting
 * critical updates from 1.25+ easier. It also serves to allow extensions that
 * maintain backwards compatibility with the 1.23 LTS releases to migrate to
 * the new logging system.
 *
 * @since 1.23.10
 * @author Bryan Davis <bd808@wikimedia.org>
 * @copyright © 2015 Bryan Davis and Wikimedia Foundation.
 */
class LoggerFactory {

	/**
	 * Get a named logger instance.
	 *
	 * @param string $channel Logger channel (name)
	 * @return \Psr\Log\LoggerInterface
	 */
	public static function getInstance( $channel ) {
		return new Logger( $channel );
	}

	/**
	 * Construction of utility class is not allowed.
	 */
	private function __construct() {
		// no-op
	}
}
