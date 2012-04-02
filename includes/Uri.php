<?php
/**
 * Class for simple URI parsing and manipulation.
 * Intended to simplify things that were using wfParseUrl and
 * had to do manual concatenation for various needs.
 * Built to match our JS mw.Uri in naming patterns.
 * @file
 * @author Daniel Friesen
 */

class Uri {
	
	/**
	 * The parsed components of the URI
	 */
	protected $components;

	protected static $validComponents = array( 'scheme', 'delimiter', 'host', 'port', 'user', 'pass', 'path', 'query', 'fragment' );
	protected static $componentAliases = array( 'protocol' => 'scheme', 'password' => 'pass' );

	/**
	 *
	 * @param $uri mixed URI string or array
	 */
	public function __construct( $uri ) {
		$this->setUri( $uri );
	}

	/**
	 * Set the Uri to the value of some other URI.
	 *
	 * @param $uri mixed URI string or array
	 */
	public function setUri( $uri ) {
		if ( is_string( $uri ) ) {
			$parsed = wfParseUrl( $uri );
			// Our wfParseUrl doesn't like query relatives like '?a=b'
			// But parse_url is fine with these, so try parse_url if $parsed is false
			if ( $parsed === false ) {
				$parsed = parse_url( $uri );
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
	function setComponents( array $components ) {
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
	function getComponents() {
		return $this->components;
	}

	/**
	 * Return the value of a specific component
	 *
	 * @param $name string The name of the component to return
	 * @param string|null
	 */
	function getComponent( $name ) {
		if ( isset( self::$componentAliases[$name] ) ) {
			wfWarn( __METHOD__ . ": Converting alias $name to canonical {self::$componentAliases[$name]}." );
			$name = self::$componentAliases[$name];
		} elseif ( !in_array( $name, self::$validComponents ) ) {
			throw new MWException( __METHOD__ . ": $name is not a valid component." );
		}
		return isset( $this->components[$name] ) ? $this->components[$name] : null;
	}

	/**
	 * Set a component for this Uri
	 * @param $name string The name of the component to set
	 * @param $value string|null The value to set
	 */
	function setComponent( $name, $value ) {
		if ( isset( self::$componentAliases[$name] ) ) {
			wfWarn( __METHOD__ . ": Converting alias $name to canonical {self::$componentAliases[$name]}." );
			$name = self::$componentAliases[$name];
		} elseif ( !in_array( $name, self::$validComponents ) ) {
			throw new MWException( __METHOD__ . ": $name is not a valid component." );
		}
		$this->components[$name] = $value;
	}

	function getProtocol() { return $this->getComponent( 'scheme' ); }
	function getUser() { return $this->getComponent( 'user' ); }
	function getPassword() { return $this->getComponent( 'pass' ); }
	function getHost() { return $this->getComponent( 'host' ); }
	function getPort() { return $this->getComponent( 'port' ); }
	function getPath() { return $this->getComponent( 'path' ); }
	function getQueryString() { return $this->getComponent( 'query' ); }
	function getFragment() { return $this->getComponent( 'fragment' ); }

	function setProtocol( $scheme ) { $this->setComponent( 'scheme', $scheme ); }
	function setUser( $user ) { $this->setComponent( 'user', $user ); }
	function setPassword( $pass ) { $this->setComponent( 'pass', $pass ); }
	function setHost( $host ) { $this->setComponent( 'host', $host ); }
	function setPort( $port ) { $this->setComponent( 'port', $port ); }
	function setPath( $path ) { $this->setComponent( 'path', $path ); }
	function setFragment( $fragment ) { $this->setComponent( 'fragment', $fragment ); }

	/**
	 * Gets the protocol-authority delimiter of a URI (:// or //).
	 * @return string|null
	 */
	public function getDelimiter() {
		$delimiter = $this->getComponent( 'delimiter' );
		if ( $delimiter ) {
			return $delimiter;
		}
		if ( $this->getAuthority() ) {
			if ( $this->getProtocol() ) {
				return '://';
			} else {
				return '//';
			}
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
	 * @param $query string|array
	 */
	public function setQuery( $query ) {
		if ( is_array( $query ) ) {
			$query = wfArrayToCGI( $query );
		}
		$this->setComponent( 'query', $query );
	}

	/**
	 * Extend the query -- supply query parameters to override or add to ours
	 * @param Array|string query parameters to override or add
	 * @return Uri this URI object
	 */
	public function extend( $parameters ) {
		if ( is_string( $parameters ) ) {
			$parameters = wfCgiToArray( $parameters );
		}
		$query = $this->getQuery();
		foreach ( $parameters as $key => $value ) {
			$query[$key] = $value;
		}
		$this->setQuery( $query );
		return $this;
	}

	private function cat() {
		$pieces = func_get_args();
		$isNull = true;
		$str = '';
		foreach ( $pieces as $piece ) {
			if ( is_array( $piece ) ) {
				$piece = call_user_func_array( array( $this, 'cat' ), $piece );
				if ( !is_null( $piece ) ) {
					$isNull = false;
					$str .= $piece;
				}
			} elseif ( in_array( $piece, self::$validComponents ) ) {
				$piece = $this->getComponent( $piece );
				if ( !is_null( $piece ) ) {
					$isNull = false;
					$str .= $piece;
				}
			} elseif ( method_exists( $this, $piece ) ) {
				$piece = $this->{$piece}();
				if ( !is_null( $piece ) ) {
					$isNull = false;
					$str .= $piece;
				}
			} else {
				$str .= $piece;
			}
		}
		return $isNull ? null : $str;
	}

	/**
	 * Returns user and password portion of a URI.
	 * @return string
	 */
	public function getUserInfo() {
		return $this->cat( 'user', array( ':', 'pass' ) );
	}

	/**
	 * Gets host and port portion of a URI.
	 * @return string
	 */
	public function getHostPort() {
		return $this->cat( 'host', array( ':', 'port' ) );
	}

	/**
	 * Returns the userInfo and host and port portion of the URI.
	 * In most real-world URLs, this is simply the hostname, but it is more general.
	 * @return string
	 */
	public function getAuthority() {
		return $this->cat( array( 'getUserInfo', '@' ), 'getHostPort' );
	}

	/**
	 * Returns everything after the authority section of the URI
	 * @return String
	 */
	public function getRelativePath() {
		return $this->cat( 'path', array( '?', 'getQueryString' ), array( '#', 'fragment' ) );
	}

	/**
	 * Gets the entire URI string. May not be precisely the same as input due to order of query arguments.
	 * @return String the URI string
	 */
	public function toString() {
		return $this->cat( 'scheme', 'getDelimiter', 'getAuthority', 'getRelativePath' );
	}

	public function __toString() {
		return $this->toString();
	}

}