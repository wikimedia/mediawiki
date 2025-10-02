<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki;

/**
 * Utility class wrapping PHP runtime state.
 *
 * @internal For use by MediaWikiEntryPoint subclasses.
 *           Should be revised before wider use.
 */
class EntryPointEnvironment {

	public function isCli(): bool {
		return PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg';
	}

	/**
	 * @see fastcgi_finish_request
	 */
	public function hasFastCgi(): bool {
		return function_exists( 'fastcgi_finish_request' );
	}

	/**
	 * @see fastcgi_finish_request
	 */
	public function fastCgiFinishRequest(): bool {
		if ( $this->hasFastCgi() ) {
			return fastcgi_finish_request();
		}
		return false;
	}

	/**
	 * @param string $key
	 * @param mixed|null $default
	 * @return mixed|null
	 */
	public function getServerInfo( string $key, $default = null ) {
		return $_SERVER[$key] ?? $default;
	}

	/**
	 * @param int $code
	 *
	 * @return never
	 */
	public function exit( int $code = 0 ): never {
		exit( $code );
	}

	public function disableModDeflate(): void {
		if ( function_exists( 'apache_setenv' ) ) {
			// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
			@apache_setenv(
				'no-gzip',
				'1'
			);
		}
	}

	/**
	 * Triggers a PHP runtime error
	 *
	 * @see trigger_error
	 */
	public function triggerError( string $message, int $level = E_USER_NOTICE ): bool {
		return trigger_error( $message, $level );
	}

	/**
	 * Returns the value of an environment variable.
	 *
	 * @see getenv
	 *
	 * @param string $name
	 *
	 * @return array|false|string
	 */
	public function getEnv( string $name ) {
		return getenv( $name );
	}

	/**
	 * Returns the value of an ini option.
	 *
	 * @see ini_get
	 *
	 * @param string $name
	 *
	 * @return false|string
	 */
	public function getIni( string $name ) {
		return ini_get( $name );
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 *
	 * @return false|string
	 */
	public function setIniOption( string $name, $value ) {
		return ini_set( $name, $value );
	}

}
