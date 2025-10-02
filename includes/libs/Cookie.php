<?php

/**
 * @license GPL-2.0-or-later
 */

/**
 * Cookie for HTTP requests.
 *
 * @ingroup HTTP
 */
class Cookie {

	private string $name;
	private string $value;
	/** The "expires" attribute as a unix timestamp, 0 for a session cookie (unset or invalid expiry) */
	private int $expires = 0;
	private string $path;
	private ?string $domain = null;

	public function __construct( string $name, string $value, array $attr ) {
		$this->name = $name;
		$this->set( $value, $attr );
	}

	/**
	 * Sets a cookie.  Used before a request to set up any individual
	 * cookies. Used internally after a request to parse the
	 * Set-Cookie headers.
	 *
	 * @param string $value The value of the cookie
	 * @param string[] $attr Possible key/values:
	 *        expires A date string
	 *        path    The path this cookie is used on
	 *        domain  Domain this cookie is used on
	 */
	public function set( string $value, array $attr ): void {
		$this->value = $value;

		if ( isset( $attr['expires'] ) ) {
			// Invalid date strings become 0, same as if not specified
			$this->expires = (int)strtotime( $attr['expires'] );
		}

		$this->path = $attr['path'] ?? '/';

		if ( isset( $attr['domain'] ) ) {
			if ( self::validateCookieDomain( $attr['domain'] ) ) {
				$this->domain = $attr['domain'];
			}
		} else {
			throw new InvalidArgumentException( '$attr must contain a domain' );
		}
	}

	/**
	 * Return the true if the cookie is valid is valid.  Otherwise,
	 * false.  The uses a method similar to IE cookie security
	 * described here:
	 * http://kuza55.blogspot.com/2008/02/understanding-cookie-security.html
	 * A better method might be to use a list like
	 * http://publicsuffix.org/
	 *
	 * @todo fixme fails to detect 3-letter top-level domains
	 * @todo fixme fails to detect 2-letter top-level domains for single-domain use (probably
	 * not a big problem in practice, but there are test cases)
	 *
	 * @param string $domain The domain to validate
	 * @param string|null $originDomain (optional) the domain the cookie originates from
	 */
	public static function validateCookieDomain( string $domain, ?string $originDomain = null ): bool {
		$dc = explode( ".", $domain );

		// Don't allow a trailing dot or addresses without a or just a leading dot
		if ( str_ends_with( $domain, '.' ) ||
			count( $dc ) <= 1 ||
			( count( $dc ) == 2 && $dc[0] === '' )
		) {
			return false;
		}

		// Only allow full, valid IP addresses
		if ( preg_match( '/^[0-9.]+$/', $domain ) ) {
			if ( count( $dc ) !== 4 || ip2long( $domain ) === false ) {
				return false;
			}

			if ( $originDomain === null || $originDomain === $domain ) {
				return true;
			}
		}

		// Don't allow cookies for "co.uk" or "gov.uk", etc, but allow "supermarket.uk"
		if ( strrpos( $domain, "." ) - strlen( $domain ) === -3 ) {
			if ( ( count( $dc ) === 2 && strlen( $dc[0] ) <= 2 )
				|| ( count( $dc ) === 3 && $dc[0] === '' && strlen( $dc[1] ) <= 2 )
			) {
				return false;
			}
			if ( ( count( $dc ) === 2 || ( count( $dc ) === 3 && $dc[0] === '' ) )
				&& preg_match( '/(com|net|org|gov|edu)\...$/', $domain )
			) {
				return false;
			}
		}

		if ( $originDomain !== null ) {
			return $domain === $originDomain
				|| (
					str_starts_with( $domain, '.' )
					&& substr_compare( $originDomain, $domain, -strlen( $domain ),
						case_insensitive: true
					) === 0
				);
		}

		return true;
	}

	/**
	 * Serialize the cookie jar into a format useful for HTTP Request headers.
	 *
	 * @param string $path The path that will be used. Required.
	 * @param string $domain The domain that will be used. Required.
	 */
	public function serializeToHttpRequest( string $path, string $domain ): string {
		$ret = '';

		if ( $this->canServeDomain( $domain )
				&& $this->canServePath( $path )
				&& $this->isUnExpired() ) {
			$ret = $this->name . '=' . $this->value;
		}

		return $ret;
	}

	private function canServeDomain( string $domain ): bool {
		// No valid "domain" attribute was provided on construction time
		if ( !$this->domain ) {
			return false;
		}

		return $domain === $this->domain
			|| (
				str_starts_with( $this->domain, '.' )
				&& substr_compare( $domain, $this->domain, -strlen( $this->domain ),
					case_insensitive: true
				) === 0
			);
	}

	private function canServePath( string $path ): bool {
		return str_starts_with( $path, $this->path );
	}

	private function isUnExpired(): bool {
		return !$this->expires || $this->expires > time();
	}

}
