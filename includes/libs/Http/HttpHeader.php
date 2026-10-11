<?php

declare( strict_types = 1 );

namespace Wikimedia\Http;

use NoDiscard;
use RuntimeException;
use Wikimedia\ScopedCallback;

/**
 * This class wraps the native header functions in such a way that no headers are sent during PHPUnit tests, to avoid
 * "headers already sent" warnings due to output (T206476). This avoids the need to use unusual PHPUnit config
 * options (T420009), or further modify the code under test (which is inconvenient), or to use PHPUnit process
 * isolation (which is slow).
 * Note that headers don't really work in CLI, but they still cause warnings, as described in
 * https://github.com/php/php-src/issues/12304. This abstraction would no longer be needed if the upstream issue
 * is fixed.
 *
 * @since 1.47
 */
class HttpHeader {
	private static bool $forceEnable = false;

	public static function send( string $header, bool $replace = true, int $responseCode = 0 ): void {
		if ( self::$forceEnable || !defined( 'MW_PHPUNIT_TEST' ) ) {
			header( $header, $replace, $responseCode );
		}
	}

	public static function headersSent( ?string &$filename = null, ?int &$line = null ): bool {
		if ( self::$forceEnable || !defined( 'MW_PHPUNIT_TEST' ) ) {
			return headers_sent( $filename, $line );
		}
		return false;
	}

	#[NoDiscard]
	public static function enableForTest(): ScopedCallback {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new RuntimeException( __METHOD__ . ' can only be called in tests' );
		}

		self::$forceEnable = true;
		return new ScopedCallback( static function () {
			self::$forceEnable = false;
		} );
	}
}
