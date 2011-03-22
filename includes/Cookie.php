<?php
/**
 * @defgroup HTTP HTTP
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

	function __construct( $name, $value, $attr ) {
		$this->name = $name;
		$this->set( $value, $attr );
	}

	/**
	 * Sets a cookie.  Used before a request to set up any individual
	 * cookies.	 Used internally after a request to parse the
	 * Set-Cookie headers.
	 *
	 * @param $value String: the value of the cookie
	 * @param $attr Array: possible key/values:
	 *		expires	 A date string
	 *		path	 The path this cookie is used on
	 *		domain	 Domain this cookie is used on
	 */
	public function set( $value, $attr ) {
		$this->value = $value;

		if ( isset( $attr['expires'] ) ) {
			$this->isSessionKey = false;
			$this->expires = strtotime( $attr['expires'] );
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
	 * @param $domain String: the domain to validate
	 * @param $originDomain String: (optional) the domain the cookie originates from
	 * @return Boolean
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
		if ( strrpos( $domain, "." ) - strlen( $domain )  == -3 ) {
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
				&& substr_compare( $originDomain, $domain, -strlen( $domain ),
								   strlen( $domain ), true ) != 0 ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Serialize the cookie jar into a format useful for HTTP Request headers.
	 *
	 * @param $path String: the path that will be used. Required.
	 * @param $domain String: the domain that will be used. Required.
	 * @return String
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

	protected function canServeDomain( $domain ) {
		if ( $domain == $this->domain
			|| ( strlen( $domain ) > strlen( $this->domain )
				 && substr( $this->domain, 0, 1 ) == '.'
				 && substr_compare( $domain, $this->domain, -strlen( $this->domain ),
									strlen( $this->domain ), true ) == 0 ) ) {
			return true;
		}

		return false;
	}

	protected function canServePath( $path ) {
		if ( $this->path && substr_compare( $this->path, $path, 0, strlen( $this->path ) ) == 0 ) {
			return true;
		}

		return false;
	}

	protected function isUnExpired() {
		if ( $this->isSessionKey || $this->expires > time() ) {
			return true;
		}

		return false;
	}
}

class CookieJar {
	private $cookie = array();

	/**
	 * Set a cookie in the cookie jar.	Make sure only one cookie per-name exists.
	 * @see Cookie::set()
	 */
	public function setCookie ( $name, $value, $attr ) {
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
	 * @param $cookie String
	 * @param $domain String: cookie's domain
	 */
	public function parseCookieResponseHeader ( $cookie, $domain ) {
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
