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
	/**
	 * Name of the cookie.
	 * @var string $name
	 */
	protected $name;
	/**
	 * Value of the cookie.
	 * @var mixed $value
	 */
	protected $value = '';
	/**
	 * The time that the cookie expires, expressed as a Unix timestamp.
	 * @var int $expires
	 */
	protected $expiry = 0;
	/**
	 * The path on the server the cookie is available on.
	 * @var string $path
	 */
	protected $path = '/';
	/**
	 * The website domain that the cookie is accessible to.
	 * @var string $domain
	 */
	protected $domain = '';
	/**
	 * Whether the cookie is set to only transmit over HTTPS from the client.
	 * @var bool $secure
	 */
	protected $secure = false;
	/**
	 * Whether the cookie is made accessible only through HTTP.
	 * @var bool $httpOnly
	 */
	protected $httpOnly = false;
	protected $isSessionKey = true;
	// TO IMPLEMENT? protected $maxAge (add onto expires)
	// TO IMPLEMENT? protected $version
	// TO IMPLEMENT? protected $comment

	/** Constructors **/

	public function __construct( $name, $value, $attr ) {
		$this->name = $name;
		$this->set( $value, $attr );
	}

	/**
	 * Attempt to load the cookie and its value from a WebRequest
	 * @param string $name
	 * @param WebRequest $request
	 *
	 * @return Cookie|null
	 */
	public static function newFromRequest( $name, WebRequest $request ) {
		$value = $request->getCookie( $name, null, null );
		if ( $value ) {
			return new self( $name, $value, array() );
		}
		return null;
	}

	/** Mutators **/

	/**
	 * Set the name of the cookie.
	 * @param string $n
	 */
	public function setName( $n ) {
		$this->name = $n;
	}

	/**
	 * Set the value of the cookie.
	 * @param mixed $v
	 */
	public function setValue( $v ) {
		if ( $v === false || $v === '' ) {
			wfDebug( "Warning! Setting cookie value to false or empty string may delete the cookie." );
		}
		$this->value = $v;
	}

	/**
	 * Set the time for when the cookie expires, as a Unix timestamp $e seconds from now.
	 * @param int|string $e
	 */
	public function setExpiry( $e = 0 ) {
		if ( is_string( $e ) ) {
			$e = strtotime( $e );
		}
		$this->expiry = time() + $e;
	}

	/**
	 * Set the time for when the cookie expires, as an absolute date.
	 * @param int|string $e
	 */
	public function setExpiryDate( $e = 0 ) {
		if ( is_string( $e ) ) {
			$e = strtotime( $e );
		}
		$this->expiry = $e;
	}

	/**
	 * Set the path on the server in which the cookie is available.
	 * Default is '/' (the entire domain).
	 * @param string $p
	 */
	public function setPath( $p = '/' ) {
		$this->path = $p;
	}

	/**
	 * Set the domain on which the cookie is available.
	 * @param string $d
	 */
	public function setDomain( $d = '' ) {
		if ( self::validateCookieDomain( $d ) ) {
			$this->domain = $d;
		}
	}

	/**
	 * Set to true to indicate that the cookie should only be transmitted over
	 * a client's HTTPS connection.
	 * @param bool $s
	 */
	public function setSecure( $s ) {
		$this->secure = $s;
	}

	/**
	 * Set to true to have the cookie restrict it's accessibility to only HTTP(S).
	 * The cookie cannot be accessed by scripting languages like JavaScript, and
	 * this has the potential to protect against XSS attacks.
	 */
	public function setHttpOnly( $http ) {
		$this->httpOnly = $http;
	}

	/**
	 * Actually send the cookie in the HTTP header.
	 * @param bool $raw If true, use setrawcookie() instead of setcookie()
	 *
	 * @return bool Whether the cookie was sent successfully or not
	 */
	public function send( $raw = false ) {
		if ( !$this->domain ) {
			throw new MWException( 'You must specify a valid domain.' );
		}
		$func = $raw ? 'setrawcookie' : 'setcookie';
		return call_user_func( $func,
			$this->name,
			$this->value,
			$this->expiry,
			$this->path,
			$this->domain,
			$this->secure,
			$this->httpOnly
		);
	}

	/** Accessors **/

	public function getName() {
		return $this->name;
	}

	public function getValue() {
		return $this->value;
	}

	public function getExpiry() {
		return $this->expiry;
	}

	public function getPath() {
		return $this->path;
	}

	public function getDomain() {
		return $this->domain;
	}

	public function isSecure() {
		return $this->secure;
	}

	public function isHttpOnly() {
		return $this->httpOnly;
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
	 * @throws MWException
	 */
	public function set( $value, $attr ) {
		$this->value = $value;

		if ( isset( $attr['expires'] ) ) {
			$this->isSessionKey = false;
			$this->expiry = strtotime( $attr['expires'] );
		}

		if ( isset( $attr['path'] ) ) {
			$this->path = $attr['path'];
		} else {
			$this->path = '/';
		}

		if ( isset( $attr['domain'] ) ) {
			if ( self::validateCookieDomain( $attr['domain'] ) ) {
				$this->domain = $attr['domain'];
			}
		} else {
			throw new MWException( 'You must specify a domain.' );
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
	 * @param string $originDomain (optional) the domain the cookie originates from
	 * @return bool
	 */
	public static function validateCookieDomain( $domain, $originDomain = null ) {
		// Don't allow a trailing dot
		if ( substr( $domain, -1 ) == '.' ) {
			return false;
		}

		$dc = explode( ".", $domain );

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
		return $this->isSessionKey || $this->expiry > time();
	}
}

class CookieJar {
	private $cookie = array();

	/**
	 * Set a cookie in the cookie jar. Make sure only one cookie per-name exists.
	 * @see Cookie::set()
	 * @param string $name
	 * @param string $value
	 * @param array $attr
	 */
	public function setCookie( $name, $value, $attr ) {
		/* cookies: case insensitive, so this should work.
		 * We'll still send the cookies back in the same case we got them, though.
		 */
		$index = strtoupper( $name );

		if ( isset( $this->cookie[$index] ) ) {
			$this->cookie[$index]->set( $value, $attr );
		} else {
			$this->cookie[$index] = new Cookie( $name, $value, $attr );
		}
	}

	/**
	 * @see Cookie::serializeToHttpRequest
	 * @param string $path
	 * @param string $domain
	 * @return string
	 */
	public function serializeToHttpRequest( $path, $domain ) {
		$cookies = array();

		foreach ( $this->cookie as $c ) {
			$serialized = $c->serializeToHttpRequest( $path, $domain );

			if ( $serialized ) {
				$cookies[] = $serialized;
			}
		}

		return implode( '; ', $cookies );
	}

	/**
	 * Parse the content of an Set-Cookie HTTP Response header.
	 *
	 * @param string $cookie
	 * @param string $domain Cookie's domain
	 * @return null
	 */
	public function parseCookieResponseHeader( $cookie, $domain ) {
		$len = strlen( 'Set-Cookie:' );

		if ( substr_compare( 'Set-Cookie:', $cookie, 0, $len, true ) === 0 ) {
			$cookie = substr( $cookie, $len );
		}

		$bit = array_map( 'trim', explode( ';', $cookie ) );

		if ( count( $bit ) >= 1 ) {
			list( $name, $value ) = explode( '=', array_shift( $bit ), 2 );
			$attr = array();

			foreach ( $bit as $piece ) {
				$parts = explode( '=', $piece );
				if ( count( $parts ) > 1 ) {
					$attr[strtolower( $parts[0] )] = $parts[1];
				} else {
					$attr[strtolower( $parts[0] )] = true;
				}
			}

			if ( !isset( $attr['domain'] ) ) {
				$attr['domain'] = $domain;
			} elseif ( !Cookie::validateCookieDomain( $attr['domain'], $domain ) ) {
				return null;
			}

			$this->setCookie( $name, $value, $attr );
		}
	}
}
