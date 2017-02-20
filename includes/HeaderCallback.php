<?php

namespace MediaWiki;

class HeaderCallback {
	private static $headersSentException;
	private static $messageSent = false;

	/**
	 * Register a callback to be called when headers are sent. There can only
	 * be one of these handlers active, so all relevant actions have to be in
	 * here.
	 */
	public static function register() {
		header_register_callback( [ __CLASS__, 'callback' ] );
	}

	/**
	 * The callback, which is called by the transport
	 */
	public static function callback() {
		// Prevent caching of responses with cookies (T127993)
		$headers = [];
		foreach ( headers_list() as $header ) {
			list( $name, $value ) = explode( ':', $header, 2 );
			$headers[strtolower( trim( $name ) )][] = trim( $value );
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
				\MediaWiki\Logger\LoggerFactory::getInstance( 'cache-cookies' )->warning(
					'Cookies set on {url} with Cache-Control "{cache-control}"', [
						'url' => \WebRequest::getGlobalRequestURL(),
						'cookies' => $headers['set-cookie'],
						'cache-control' => $cacheControl ?: '<not set>',
					]
				);
			}
		}

		// Save a backtrace for logging in case it turns out that headers were sent prematurely
		self::$headersSentException = new \Exception( 'Headers already sent from this point' );
	}

	/**
	 * Log a warning message if headers have already been sent. This can be
	 * called before flushing the output.
	 */
	public static function warnIfHeadersSent() {
		if ( headers_sent() && !self::$messageSent ) {
			self::$messageSent = true;
			\MWDebug::warning( 'Headers already sent, should send headers earlier than ' .
				wfGetCaller( 3 ) );
			$logger = \MediaWiki\Logger\LoggerFactory::getInstance( 'headers-sent' );
			$logger->error( 'Warning: headers were already sent from the location below', [
				'exception' => self::$headersSentException,
				'detection-trace' => new \Exception( 'Detected here' ),
			] );
		}
	}
}
