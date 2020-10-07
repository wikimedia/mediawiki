<?php
/**
 * Cookie for HTTP requests.
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
 * @ingroup HTTP
 */

class Cookie {
	protected $name;
	protected $value;
	protected $expires;
	protected $path;
	protected $domain;
	protected $isSessionKey = true;
	// TO IMPLEMENT	 protected $secure
	// TO IMPLEMENT? protected $maxAge (add onto expires)
	// TO IMPLEMENT? protected $version
	// TO IMPLEMENT? protected $comment

	public function __construct( $name, $value, $attr ) {
		$this->name = $name;
		$this->set( $value, $attr );
	}

	/**
	 * Sets a cookie.  Used before a request to set up any individual
	 * cookies. Used internally after a request to parse the
	 * Set-Cookie headers.
	 *
	 * @param string $value The value of the cookie
	 * @param array $attr Possible key/values:
	 *        expires A date string
	 *        path    The path this cookie is used on
	 *        domain  Domain this cookie is used on
	 * @throws InvalidArgumentException
	 */
	public function set( $value, $attr ) {
		$this->value = $value;

		if ( isset( $attr['expires'] ) ) {
			$this->isSessionKey = false;
			$this->expires = strtotime( $attr['expires'] );
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
	 * A better method might be to use a blacklist like
	 * http://publicsuffix.org/
	 *
	 * @todo fixme fails to detect 3-letter top-level domains
	 * @todo fixme fails to detect 2-letter top-level domains for single-domain use (probably
	 * not a big problem in practice, but there are test cases)
	 *
	 * @param string $domain The domain to validate
	 * @param string|null $originDomain (optional) the domain the cookie originates from
	 * @return bool
	 */
	public static function validateCookieDomain( $domain, $originDomain = null ) {
		$dc = explode( ".", $domain );

		// Don't allow a trailing dot or addresses without a or just a leading dot
		if ( substr( $domain, -1 ) == '.' ||
			count( $dc ) <= 1 ||
			count( $dc ) == 2 && $dc[0] === ''
		) {
			return false;
		}

		// Only allow full, valid IP addresses
		if ( preg_match( '/^[0-9.]+$/', $domain ) ) {
			if ( count( $dc ) != 4 ) {
				return false;
			}

			if ( ip2long( $domain ) === false ) {
				return false;
			}

			if ( $originDomain == null || $originDomain == $domain ) {
				return true;
			}

		}

		// Don't allow cookies for "co.uk" or "gov.uk", etc, but allow "supermarket.uk"
		if ( strrpos( $domain, "." ) - strlen( $domain ) == -3 ) {
			if ( ( count( $dc ) == 2 && strlen( $dc[0] ) <= 2 )
				|| ( count( $dc ) == 3 && strlen( $dc[0] ) == "" && strlen( $dc[1] ) <= 2 ) ) {
				return false;
			}
			if ( ( count( $dc ) == 2 || ( count( $dc ) == 3 && $dc[0] == '' ) )
				&& preg_match( '/(com|net|org|gov|edu)\...$/', $domain ) ) {
				return false;
			}
		}

		if ( $originDomain != null ) {
			if ( substr( $domain, 0, 1 ) != '.' && $domain != $originDomain ) {
				return false;
			}

			if ( substr( $domain, 0, 1 ) == '.'
				&& substr_compare(
					$originDomain,
					$domain,
					-strlen( $domain ),
					strlen( $domain ),
					true
				) != 0
			) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Serialize the cookie jar into a format useful for HTTP Request headers.
	 *
	 * @param string $path The path that will be used. Required.
	 * @param string $domain The domain that will be used. Required.
	 * @return string
	 */
	public function serializeToHttpRequest( $path, $domain ) {
		$ret = '';

		if ( $this->canServeDomain( $domain )
				&& $this->canServePath( $path )
				&& $this->isUnExpired() ) {
			$ret = $this->name . '=' . $this->value;
		}

		return $ret;
	}

	/**
	 * @param string $domain
	 * @return bool
	 */
	protected function canServeDomain( $domain ) {
		if ( $domain == $this->domain
			|| ( strlen( $domain ) > strlen( $this->domain )
				&& substr( $this->domain, 0, 1 ) == '.'
				&& substr_compare(
					$domain,
					$this->domain,
					-strlen( $this->domain ),
					strlen( $this->domain ),
					true
				) == 0
			)
		) {
			return true;
		}

		return false;
	}

	/**
	 * @param string $path
	 * @return bool
	 */
	protected function canServePath( $path ) {
		return ( $this->path && substr_compare( $this->path, $path, 0, strlen( $this->path ) ) == 0 );
	}

	/**
	 * @return bool
	 */
	protected function isUnExpired() {
		return $this->isSessionKey || $this->expires > time();
	}
}
