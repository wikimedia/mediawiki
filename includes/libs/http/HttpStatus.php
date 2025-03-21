<?php
/**
 * List of HTTP status codes.
 *
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

namespace Wikimedia\Http;

use InvalidArgumentException;

/**
 * @todo document
 */
class HttpStatus {

	/**
	 * @var null|callable
	 */
	private static $headersSentCallback = null;

	public static function registerHeadersSentCallback( callable $callback ): ?callable {
		$old = self::$headersSentCallback;
		self::$headersSentCallback = $callback;

		return $old;
	}

	/**
	 * Get the message associated with an HTTP response status code
	 *
	 * @param int $code Status code
	 * @return string|null Message, or null if $code is not known
	 */
	public static function getMessage( $code ) {
		static $statusMessage = [
			100 => 'Continue',
			101 => 'Switching Protocols',
			102 => 'Processing',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			207 => 'Multi-Status',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Large',
			415 => 'Unsupported Media Type',
			416 => 'Request Range Not Satisfiable',
			417 => 'Expectation Failed',
			422 => 'Unprocessable Entity',
			423 => 'Locked',
			424 => 'Failed Dependency',
			428 => 'Precondition Required',
			429 => 'Too Many Requests',
			431 => 'Request Header Fields Too Large',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported',
			507 => 'Insufficient Storage',
			511 => 'Network Authentication Required',
		];
		return $statusMessage[$code] ?? null;
	}

	/**
	 * Construct an HTTP status code header
	 *
	 * @since 1.42
	 * @param int $code Status code
	 * @return string
	 */
	public static function getHeader( $code ): string {
		static $version = null;
		$message = self::getMessage( $code );
		if ( $message === null ) {
			throw new InvalidArgumentException( "Unknown HTTP status code $code" );
		}

		if ( $version === null ) {
			$version = isset( $_SERVER['SERVER_PROTOCOL'] ) &&
			$_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.0' ?
				'1.0' :
				'1.1';
		}

		return "HTTP/$version $code $message";
	}

	/**
	 * Output an HTTP status code header
	 *
	 * @since 1.26
	 * @param int $code Status code
	 */
	public static function header( $code ) {
		if ( headers_sent() ) {
			if ( self::$headersSentCallback ) {
				( self::$headersSentCallback )();
				return;
			}

			// NOTE: If there is no custom callback, we continue normally and
			//       rely on the implementation of header() to emit a warning.
		}

		try {
			header( self::getHeader( $code ) );
		} catch ( InvalidArgumentException $ex ) {
			trigger_error( "Unknown HTTP status code $code", E_USER_WARNING );
		}
	}

}

/** @deprecated class alias since 1.44 */
class_alias( HttpStatus::class, 'HttpStatus' );
