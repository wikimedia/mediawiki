<?php

namespace MediaWiki\Request;

use MediaWiki\Debug\MWDebug;
use MediaWiki\Http\Telemetry;
use MediaWiki\Logger\LoggerFactory;
use RuntimeException;

/**
 * @since 1.29
 */
class HeaderCallback {
	/** @var RuntimeException */
	private static $headersSentException;
	/** @var bool */
	private static $messageSent = false;

	/**
	 * Register a callback to be called when headers are sent. There can only
	 * be one of these handlers active, so all relevant actions have to be in
	 * here.
	 *
	 * @since 1.29
	 */
	public static function register() {
		// T261260 load some classes which will be needed in callback().
		// Autoloading seems unreliable in header callbacks, and in the case of a web
		// request (ie. in all cases where the request might be performance-sensitive)
		// these classes will have to be loaded at some point anyway.
		class_exists( WebRequest::class );
		class_exists( LoggerFactory::class );
		class_exists( Telemetry::class );

		header_register_callback( self::callback( ... ) );
	}

	/**
	 * The callback, which is called by the transport
	 *
	 * @since 1.29
	 */
	public static function callback() {
		// Prevent caching of responses with cookies (T127993)
		$headers = [];
		foreach ( headers_list() as $header ) {
			$header = explode( ':', $header, 2 );

			// Note: The code below (currently) does not care about value-less headers
			if ( isset( $header[1] ) ) {
				$headers[ strtolower( trim( $header[0] ) ) ][] = trim( $header[1] );
			}
		}

		if ( isset( $headers['set-cookie'] ) ) {
			$cacheControl = isset( $headers['cache-control'] )
				? implode( ', ', $headers['cache-control'] )
				: '';

			if ( !preg_match( '/(?:^|,)\s*(?:private|no-cache|no-store)\s*(?:$|,)/i',
				$cacheControl )
			) {
				header( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
				header( 'Cache-Control: private, max-age=0, s-maxage=0' );
				LoggerFactory::getInstance( 'cache-cookies' )->warning(
					'Cookies set on {url} with Cache-Control "{cache-control}"', [
						'url' => WebRequest::getGlobalRequestURL(),
						'set-cookie' => self::sanitizeSetCookie( $headers['set-cookie'] ),
						'cache-control' => $cacheControl ?: '<not set>',
					]
				);
			}
		}

		$telemetryHeaders = Telemetry::getInstance()->getRequestHeaders();
		// Set the request ID/trace prams on the response, so edge infrastructure can log it.
		// FIXME this is not an ideal place to do it, but the most reliable for now.
		foreach ( $telemetryHeaders as $header => $value ) {
			if ( !isset( $headers[strtolower( $header )] ) ) {
				header( "$header: $value" );
			}
		}

		// Save a backtrace for logging in case it turns out that headers were sent prematurely
		self::$headersSentException = new RuntimeException( 'Headers already sent from this point' );
	}

	/**
	 * Log a warning message if headers have already been sent. This can be
	 * called before flushing the output.
	 *
	 * @since 1.29
	 */
	public static function warnIfHeadersSent() {
		if ( !self::$messageSent && headers_sent( $filename, $line ) ) {
			self::$messageSent = true;
			MWDebug::warning( 'Headers already sent, should send headers earlier than ' .
				wfGetCaller( 3 ) );
			$logger = LoggerFactory::getInstance( 'headers-sent' );
			$logger->error( 'Warning: headers were already sent (output started at ' . $filename . ':' . $line . ')', [
				'exception' => self::$headersSentException,
				'detection-trace' => new RuntimeException( 'Detected here' ),
			] );
		}
	}

	/**
	 * Sanitize Set-Cookie headers for logging.
	 * @param array $values List of header values.
	 * @return string
	 */
	public static function sanitizeSetCookie( array $values ) {
		$sanitizedValues = [];
		foreach ( $values as $value ) {
			// Set-Cookie header format: <cookie-name>=<cookie-value>; <non-sensitive attributes>
			$parts = explode( ';', $value );
			[ $name, $value ] = explode( '=', $parts[0], 2 );
			if ( strlen( $value ) > 8 ) {
				$value = substr( $value, 0, 8 ) . '...';
				$parts[0] = "$name=$value";
			}
			$sanitizedValues[] = implode( ';', $parts );
		}
		return implode( "\n", $sanitizedValues );
	}
}
