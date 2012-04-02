<?php
/**
 * Class for simple URI parsing and manipulation.
 * Intended to simplify things that were using wfParseUrl and
 * had to do manual concatenation for various needs.
 * Built to match our JS mw.Uri in naming patterns.
 * @file
 * @author Daniel Friesen
 * @since 1.20
 */

class Uri {

	/**
	 * The parsed components of the URI
	 */
	protected $components;

	protected static $validComponents = array( 'scheme', 'delimiter', 'host', 'port', 'user', 'pass', 'path', 'query', 'fragment' );
	protected static $componentAliases = array( 'protocol' => 'scheme', 'password' => 'pass' );

	/**
	 * parse_url() work-alike, but non-broken.  Differences:
	 *
	 * 1) Does not raise warnings on bad URLs (just returns false)
	 * 2) Handles protocols that don't use :// (e.g., mailto: and news: , as well as protocol-relative URLs) correctly
	 * 3) Adds a "delimiter" element to the array, either '://', ':' or '//' (see (2))
	 *
	 * @param $url String: a URL to parse
	 * @return Array: bits of the URL in an associative array, per PHP docs
	 */
	protected static function parseUri( $url ) {
		global $wgUrlProtocols; // Allow all protocols defined in DefaultSettings/LocalSettings.php

		// Protocol-relative URLs are handled really badly by parse_url(). It's so bad that the easiest
		// way to handle them is to just prepend 'http:' and strip the protocol out later
		$wasRelative = substr( $url, 0, 2 ) == '//';
		if ( $wasRelative ) {
			$url = "http:$url";
		}
		wfSuppressWarnings();
		$bits = parse_url( $url );
		wfRestoreWarnings();
		// parse_url() returns an array without scheme for some invalid URLs, e.g.
		// parse_url("%0Ahttp://example.com") == array( 'host' => '%0Ahttp', 'path' => 'example.com' )
		if ( !$bits ||
		     !isset( $bits['scheme'] ) && strpos( $url, "://" ) !== false ) {
			wfWarn( __METHOD__ . ": Invalid URL: $url" );
			return false;
		} else {
			$scheme = isset( $bits['scheme'] ) ? $bits['scheme'] : null;
		}

		// most of the protocols are followed by ://, but mailto: and sometimes news: not, check for it
		if ( in_array( $scheme . '://', $wgUrlProtocols ) ) {
			$bits['delimiter'] = '://';
		} elseif ( !is_null( $scheme ) && !in_array( $scheme . ':', $wgUrlProtocols ) ) {
			wfWarn( __METHOD__ . ": Invalid scheme in URL: $scheme" );
			return false;
		} elseif( !is_null( $scheme ) ) {
			if( !in_array( $scheme . ':', $wgUrlProtocols ) ) {
				// For URLs that don't have a scheme, but do have a user:password, parse_url
				// detects the user as the scheme.
				unset( $bits['scheme'] );
				$bits['user'] = $scheme;
			} else {
				$bits['delimiter'] = ':';
				// parse_url detects for news: and mailto: the host part of an url as path
				// We have to correct this wrong detection
				if ( isset( $bits['path'] ) ) {
					$bits['host'] = $bits['path'];
					$bits['path'] = '';
				}
			}
		}

		/* Provide an empty host for eg. file:/// urls (see bug 28627) */
		if ( !isset( $bits['host'] ) && $scheme == "file" ) {
			$bits['host'] = '';

			/* parse_url loses the third / for file:///c:/ urls (but not on variants) */
			if ( isset( $bits['path'] ) && substr( $bits['path'], 0, 1 ) !== '/' ) {
				$bits['path'] = '/' . $bits['path'];
			}
		}

		// If the URL was protocol-relative, fix scheme and delimiter
		if ( $wasRelative ) {
			$bits['scheme'] = '';
			$bits['delimiter'] = '//';
		}
		return $bits;
	}

	/**
	 *
	 * @param $uri mixed URI string or array
	 */
	public function __construct( $uri ) {
		$this->components = array();
		$this->setUri( $uri );
	}

	/**
	 * Set the Uri to the value of some other URI.
	 *
	 * @param $uri mixed URI string or array
	 */
	public function setUri( $uri ) {
		if ( is_string( $uri ) ) {
			$parsed = self::parseUri( $uri );
			if( $parsed === false ) {
				return false;
			}
			$this->setComponents( $parsed );
		} elseif ( is_array( $uri ) ) {
			$this->setComponents( $uri );
		} elseif ( $uri instanceof Uri ) {
			$this->setComponents( $uri->getComponents() );
		} else {
			throw new MWException( __METHOD__ . ': $uri is not of a valid type.' );
		}
	}

	/**
	 * Set the components of this array.
	 * Will output warnings when invalid components or aliases are found.
	 *
	 * @param $components Array The components to set on this Uri.
	 */
	public function setComponents( array $components ) {
		foreach ( $components as $name => $value ) {
			if ( isset( self::$componentAliases[$name] ) ) {
				wfWarn( __METHOD__ . ": Converting alias $name to canonical {self::$componentAliases[$name]}." );
				$components[self::$componentAliases[$name]] = $value;
				unset( $components[$name] );
			} elseif ( !in_array( $name, self::$validComponents ) ) {
				wfWarn( __METHOD__ . ": $name is not a valid component." );
				unset( $components[$name] );
			}
		}

		$this->components = $components;
	}

	/**
	 * Return the components for this Uri
	 * @return Array
	 */
	public function getComponents() {
		return $this->components;
	}

	/**
	 * Return the value of a specific component
	 *
	 * @param $name string The name of the component to return
	 * @param string|null
	 */
	public function getComponent( $name ) {
		if ( isset( self::$componentAliases[$name] ) ) {
			wfWarn( __METHOD__ . ": Converting alias $name to canonical {self::$componentAliases[$name]}." );
			$name = self::$componentAliases[$name];
		} elseif ( !in_array( $name, self::$validComponents ) ) {
			throw new MWException( __METHOD__ . ": $name is not a valid component." );
		} elseif( isset( $this->components[$name] ) && !empty( $this->components[$name] ) ) {
			return $this->components[$name];
		} else {
			return null;
		}
	}

	/**
	 * Set a component for this Uri
	 * @param $name string The name of the component to set
	 * @param $value string|null The value to set
	 */
	public function setComponent( $name, $value ) {
		if ( isset( self::$componentAliases[$name] ) ) {
			wfWarn( __METHOD__ . ": Converting alias $name to canonical {self::$componentAliases[$name]}." );
			$name = self::$componentAliases[$name];
		} elseif ( !in_array( $name, self::$validComponents ) ) {
			throw new MWException( __METHOD__ . ": $name is not a valid component." );
		}
		$this->components[$name] = $value;
	}

	public function getProtocol() { return $this->getComponent( 'scheme' ); }
	public function getUser() { return $this->getComponent( 'user' ); }
	public function getPassword() { return $this->getComponent( 'pass' ); }
	public function getHost() { return $this->getComponent( 'host' ); }
	public function getPort() { return $this->getComponent( 'port' ); }
	public function getPath() { return $this->getComponent( 'path' ); }
	public function getQueryString() { return $this->getComponent( 'query' ); }
	public function getFragment() { return $this->getComponent( 'fragment' ); }

	public function setProtocol( $scheme ) { $this->setComponent( 'scheme', $scheme ); }
	public function setUser( $user ) { $this->setComponent( 'user', $user ); }
	public function setPassword( $pass ) { $this->setComponent( 'pass', $pass ); }
	public function setHost( $host ) { $this->setComponent( 'host', $host ); }
	public function setPort( $port ) { $this->setComponent( 'port', $port ); }
	public function setPath( $path ) { $this->setComponent( 'path', $path ); }
	public function setFragment( $fragment ) { $this->setComponent( 'fragment', $fragment ); }

	/**
	 * Gets the protocol-authority delimiter of a URI (:// or //).
	 * @return string|null
	 */
	public function getDelimiter() {
		$delimiter = $this->getComponent( 'delimiter' );
		if ( $delimiter ) {
			return $delimiter;
		}
		if ( $this->getAuthority() && $this->getProtocol() ) {
			return '://';
		}
		return null;
	}

	/**
	 * Gets query portion of a URI in array format.
	 * @return string
	 */
	public function getQuery() {
		return wfCgiToArray( $this->getQueryString() );
	}

	/**
	 * Gets query portion of a URI.
	 * @param string|array $query
	 */
	public function setQuery( $query ) {
		if ( is_array( $query ) ) {
			$query = wfArrayToCGI( $query );
		}
		$this->setComponent( 'query', $query );
	}

	/**
	 * Extend the query -- supply query parameters to override or add to ours
	 * @param Array|string $parameters query parameters to override or add
	 * @return Uri this URI object
	 */
	public function extendQuery( $parameters ) {
		if ( is_string( $parameters ) ) {
			$parameters = wfCgiToArray( $parameters );
		}

		$query = $this->getQuery();
		foreach( $parameters as $key => $value ) {
			$query[$key] = $value;
		}

		$this->setQuery( $query );
		return $this;
	}

	/**
	 * Returns user and password portion of a URI.
	 * @return string
	 */
	public function getUserInfo() {
		$user = $this->getComponent( 'user' );
		$pass = $this->getComponent( 'pass' );
		return $pass ? "$user:$pass" : $user;
	}

	/**
	 * Gets host and port portion of a URI.
	 * @return string
	 */
	public function getHostPort() {
		$host = $this->getComponent( 'host' );
		$port = $this->getComponent( 'port' );
		return $port ? "$host:$port" : $host;
	}

	/**
	 * Returns the userInfo and host and port portion of the URI.
	 * In most real-world URLs, this is simply the hostname, but it is more general.
	 * @return string
	 */
	public function getAuthority() {
		$userinfo = $this->getUserInfo();
		$hostinfo = $this->getHostPort();
		return $userinfo ? "$userinfo@$hostinfo" : $hostinfo;
	}

	/**
	 * Returns everything after the authority section of the URI
	 * @return String
	 */
	public function getRelativePath() {
		$path = $this->getComponent( 'path' );
		$query = $this->getComponent( 'query' );
		$fragment = $this->getComponent( 'fragment' );

		$retval = $path;
		if( $query ) {
			$retval .= "?$query";
		}
		if( $fragment ) {
			$retval .= "#$fragment";
		}
		return $retval;
	}

	/**
	 * Gets the entire URI string. May not be precisely the same as input due to order of query arguments.
	 * @return String the URI string
	 */
	public function toString() {
		return $this->getComponent( 'scheme' ) . $this->getDelimiter() . $this->getAuthority() . $this->getRelativePath();
	}

	/**
	 * Gets the entire URI string. May not be precisely the same as input due to order of query arguments.
	 * @return String the URI string
	 */
	public function __toString() {
		return $this->toString();
	}

}
