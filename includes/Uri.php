<?php

/**
 * Class for simple URI parsing and manipulation.
 * Intended to simplify things that were using wfParseUrl and
 * had to do manual concatenation for various needs.
 *
 * @file
 * @author Daniel Friesen
 * @author Tyler Romeo
 * @since 1.22
 */
class Uri {
	/**
	 * The full string URI
	 */
	protected $uri = null;

	/**
	 * The parsed components of the URI
	 */
	protected $components = null;

	/**
	 * Valid URI components.
	 */
	protected static $validComponents = array(
		'scheme',
		'host',
		'port',
		'user',
		'pass',
		'path',
		'query',
		'fragment'
	);

	/**
	 * Aliases for component names.
	 */
	protected static $componentAliases = array(
		'protocol' => 'scheme',
		'password' => 'pass'
	);

	/**
	 * Characters to unescape (urlencode does too much).
	 */
	protected static $unescapes = array(
		';' => '%3B',
		'@' => '%40',
		'$' => '%24',
		'!' => '%21',
		'*' => '%2A',
		'(' => '%28',
		')' => '%29',
		',' => '%2C',
		'/' => '%2F'
	);

	/**
	 * parse_url() work-alike, but non-broken.  Differences:
	 *
	 * 1) Does not raise warnings on bad URLs (just returns false)
	 * 2) Handles protocols that don't use :// (e.g., mailto: and news: , as well as protocol-relative URLs) correctly
	 *
	 * @param $url String: a URL to parse
	 * @return Array: bits of the URL in an associative array, per PHP docs
	 */
	protected static function parseUri( $url ) {
		// parse_url() cannot handle URIs without schemes, so add one for protocol-relative URIs.
		$wasRelative = substr( $url, 0, 2 ) == '//';
		if( $wasRelative ) {
			$url = "http:$url";
		}

		wfSuppressWarnings();
		$bits = parse_url( $url );
		wfRestoreWarnings();

		// parse_url() returns an array without scheme for some invalid URLs, e.g.
		// parse_url("%0Ahttp://example.com") == array( 'host' => '%0Ahttp', 'path' => 'example.com' )
		if(
			!$bits ||
			!isset( $bits['scheme'] ) && strpos( $url, "://" ) !== false
		) {
			wfDebug( __METHOD__ . ": Invalid URL: $url" );
			return false;
		}

		// If the URL was protocol-relative, fix scheme
		if( $wasRelative ) {
			unset( $bits['scheme'] );
		}
		return $bits;
	}

	/**
	 * Encode a string for use in a component of a URI.
	 * Similar to urlencode(), but does not escape as much so that it does
	 * not break wiki page titles.
	 *
	 * We want some things to be included as literal characters in our title URLs
	 * for prettiness, which urlencode encodes by default.  According to RFC 1738,
	 * all of the following should be safe:
	 *
	 * ;:@&=$-_.+!*'(),
	 *
	 * But + is not safe because it's used to indicate a space; &= are only safe in
	 * paths and not in queries (and we don't distinguish here); ' seems kind of
	 * scary; and urlencode() doesn't touch -_. to begin with.  Plus, although /
	 * is reserved, we don't care.  So the list we unescape is:
	 *
	 * ;:@$!*(),/
	 *
	 * However, IIS7 redirects fail when the url contains a colon (Bug 22709),
	 * so no fancy : for IIS7.
	 *
	 * %2F in the page titles seems to fatally break for some reason.
	 *
	 * @param $string Component to encode
	 * @return string
	 */
	public static function encode( $str ) {
		if( $str === null ) {
			return null;
		}

		$unescaped = array_keys( self::$unescapes );
		$escaped = array_values( self::$unescapes );

		$unescaped[] = ':';
		if( !isset( $_SERVER['SERVER_SOFTWARE'] ) || ( strpos( $_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS/7' ) === false ) ) {
			$escaped[] = '%3A';
		}

		$str = urlencode( $str );
		$str = str_ireplace( $escaped, $unescaped, $str );
		return $str;
	}

	/**
	 * Decode a URI component. Only a wrapper for urldecode(). It is used
	 * for symmetry purposes, and in case there is a future change in how
	 * MediaWiki decodes URI components.
	 *
	 * @param $str Component to decode
	 * @return string
	 */
	public static function decode( $str ) {
		return urldecode( $str );
	}

	/**
	 * Make a new Uri.
	 * @param $uri mixed URI string or array
	 */
	public function __construct( $uri = null ) {
		$this->components = array();
		$this->setUri( $uri );
	}

	/**
	 * Set the Uri to the value of some other URI.
	 *
	 * @param $uri mixed URI string or array
	 */
	public function setUri( $uri ) {
		if( $uri === null ) {
			return;
		} elseif( is_string( $uri ) ) {
			$this->uri = $uri;
			$this->components = null;
		} elseif( is_array( $uri ) ) {
			$this->setComponents( $uri );
		} elseif( $uri instanceof Uri ) {
			$this->uri = $uri->uri;
			$this->components = $uri->components;
		} else {
			throw new MWException( __METHOD__ . ": $uri is not of a valid type." );
		}
	}

	/**
	 * Load the components from the string Uri.
	 */
	public function load() {
		if( $this->components !== null ) {
			return true;
		}

		$parsed = self::parseUri( $this->uri );
		if( $parsed === false ) {
			$this->components = array();
			return false;
		}

		$this->setComponents( $parsed );
	}

	/**
	 * Return the components for this Uri
	 * @return Array
	 */
	public function getComponents() {
		$this->load();
		return $this->components;
	}

	/**
	 * Return the value of a specific component
	 *
	 * @param $name string The name of the component to return
	 * @param string|null
	 */
	public function getComponent( $name ) {
		$this->load();
		if( isset( self::$componentAliases[$name] ) ) {
			// Component is an alias. Get the actual name.
			$alias = $name;
			$name = self::$componentAliases[$name];
			wfDebug( __METHOD__ . ": Converting alias $alias to canonical $name." );
		}

		if( !in_array( $name, self::$validComponents ) ) {
			// Component is invalid
			throw new MWException( __METHOD__ . ": $name is not a valid component." );
		} elseif( !empty( $this->components[$name] ) ) {
			// Component is valid and has a value.
			return $this->components[$name];
		} else {
			// Component is empty
			return null;
		}
	}

	/**
	 * Set the components of this array.
	 * Will output warnings when invalid components or aliases are found.
	 *
	 * @param $components Array The components to set on this Uri.
	 * @param $blank Whether to erase all current components first
	 * @param $override Whether the given components should override existing components
	 */
	public function setComponents( array $components, $blank = true, $override = false ) {
		if( !$blank ) {
			$this->load();
		}

		foreach( $components as $name => $value ) {
			if( isset( self::$componentAliases[$name] ) ) {
				$canonical = self::$componentAliases[$name];
				wfDebug( __METHOD__ . ": Converting alias $name to canonical $canonical." );
				$components[$canonical] = $value;
				unset( $components[$name] );
			} elseif( !in_array( $name, self::$validComponents ) ) {
				throw new MWException( __METHOD__ . ": $name is not a valid component." );
			} elseif( !$override && isset( $this->components[$name] ) ) {
				unset( $components[$name] );
			}
		}

		$this->uri = null;
		$components = array_map( array( 'self', 'decode' ), $components );
		if( $blank ) {
			$this->components = $components;
		} else {
			$this->components = array_merge( $this->components, $components );
		}
	}

	/**
	 * Set a component for this Uri
	 * @param $name string The name of the component to set
	 * @param $value string|null The value to set
	 * @param $decode Whether to urldecode the value or not
	 */
	public function setComponent( $name, $value, $decode = true ) {
		$this->load();
		if( isset( self::$componentAliases[$name] ) ) {
			$alias = $name;
			$name = self::$componentAliases[$name];
			wfDebug( __METHOD__ . ": Converting alias $alias to canonical $name." );
		} elseif( !in_array( $name, self::$validComponents ) ) {
			throw new MWException( __METHOD__ . ": $name is not a valid component." );
		}
		$this->uri = null;
		$this->components[$name] = $decode && is_string( $value ) ? self::decode( $value ) : $value;
	}

	public function getScheme() { return $this->getComponent( 'scheme' ); }
	public function getUser() { return $this->getComponent( 'user' ); }
	public function getPassword() { return $this->getComponent( 'pass' ); }
	public function getHost() { return $this->getComponent( 'host' ); }
	public function getPort() { return $this->getComponent( 'port' ); }
	public function getPath() { return $this->getComponent( 'path' ); }
	public function getQuery() { return $this->getComponent( 'query' ); }
	public function getFragment() { return $this->getComponent( 'fragment' ); }

	public function setScheme( $scheme ) { $this->setComponent( 'scheme', $scheme ); return $this; }
	public function setUser( $user ) { $this->setComponent( 'user', $user ); return $this; }
	public function setPassword( $pass ) { $this->setComponent( 'pass', $pass ); return $this; }
	public function setHost( $host ) { $this->setComponent( 'host', $host ); return $this; }
	public function setPort( $port ) { $this->setComponent( 'port', $port ); return $this; }
	public function setPath( $path ) { $this->setComponent( 'path', $path ); return $this; }
	public function setFragment( $fragment ) { $this->setComponent( 'fragment', $fragment ); return $this; }

	/**
	 * Gets query portion of a URI.
	 * @param string|array $query
	 */
	public function setQuery( $query ) {
		if( is_array( $query ) ) {
			$query = new UriPhpFormQuery( $query );
		}
		$this->setComponent( 'query', $query );
		return $this;
	}

	/**
	 * Extend the query -- supply query parameters to override or add to ours
	 * @param Array|string $parameters query parameters to override or add
	 * @return Uri this URI object
	 */
	public function extendQuery( $parameters ) {
		if( empty( $parameters ) ) {
			return;
		}

		$query = $this->getQuery();

		if( $query instanceof UriQuery ) {
			$query->extendQuery( $parameters );
		} else {
			// Not a custom query class, emulate wfAppendQuery.
			if( is_array( $parameters ) ) {
				$parameters = new UriPhpFormQuery( $parameters );
			}
			$query .= "&$parameters";
		}

		$this->setQuery( $query );
		return $this;
	}

	/**
	 * Expand the current URI using another URI. This will take any
	 * components from the given URI and add them, without overriding,
	 * to the current URI.
	 *
	 * @param $server mixed String or URI object to expand with
	 */
	public function expand( $server = null ) {
		global $wgServer;
		if( !$server ) {
			$server = $wgServer;
		}
		if( !$server instanceof Uri ) {
			$server = new self( $server );
		}
		$this->setComponents( $server->getComponents(), false, false );
		return $this;
	}

	/**
	 * Resolve a path with dot segments, i.e., '.' and/or '..', to its absolute
	 * form. This uses the algorithm outlined in RFC 3986 section 5.2.4.
	 */
	public function resolvePath() {
		$input = $this->getComponent( 'path' );
		$output = array();

		while( $input ) {
			$pref1 = substr( $input, 0, 1 );
			$pref2 = substr( $input, 0, 2 );
			$pref3 = substr( $input, 0, 3 );
			$pref4 = substr( $input, 0, 4 );

			// Step A
			if( $pref3 == '../' ) {
				$input = substr( $input, 3 );
			} elseif( $pref2 == './' ) {
				$input = substr( $input, 2 );
			// Step B
			} elseif( $pref3 == '/./' ) {
				$input = substr( $input, 2 );
			} elseif( $pref2 == '/.' && strlen( $input ) == 2 ) {
				$input = '/' . substr( $input, 2 );
			// Step C
			} elseif( $pref4 == '/../' ) {
				$input = substr( $input, 3 );
				array_pop( $output );
			} elseif( $pref3 == '/..' && strlen( $input ) == 3 ) {
				$input = '/' . substr( $input, 3 );
				array_pop( $output );
			// Step D
			} elseif( $input == '.' || $input == '..' ) {
				$input = '';
			// Step E
			} else {
				$limit = $pref1 == '/' ? strpos( $input, '/', 1 ) : strpos( $input, '/' );
				if( $limit === false ) {
					$limit = strlen( $input );
				}
				$output[] = substr( $input, 0, $limit );
				$input = substr( $input, $limit );
			}
		}

		$this->setComponent( 'path', implode( '', $output ) );
		return $this;
	}

	/**
	 * Returns user and password portion of a URI.
	 * @return string
	 */
	public function getUserInfo() {
		$user = urlencode( $this->getComponent( 'user' ) );
		$pass = urlencode( $this->getComponent( 'pass' ) );
		return $pass ? "$user:$pass" : $user;
	}

	/**
	 * Gets host and port portion of a URI.
	 * @return string
	 */
	public function getHostPort() {
		$host = urlencode( $this->getComponent( 'host' ) );
		$port = urlencode( $this->getComponent( 'port' ) );
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
		$path = self::encode( $this->getComponent( 'path' ) );
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
	 * Gets the entire URI string.
	 * @return String the URI string
	 */
	public function toString() {
		if( $this->uri !== null ) {
			return $this->uri;
		}

		$url = '';

		$scheme = $this->getComponent( 'scheme' );
		$authority = $this->getAuthority();
		$path = $this->getRelativePath();

		if( $scheme ) {
			$url .= "$scheme:";
		}
		if( $authority && in_array( substr( $path, 0, 1 ), array( false, '?', '#', '/' ) ) ) {
			$url .= "//$authority";
		}
		$url .= $path;

		return $this->url = $url;
	}

	/**
	 * Gets the entire URI string.
	 * @return String the URI string
	 */
	public function __toString() {
		return $this->toString();
	}

	/**
	 * Make a copy of the current Uri.
	 * @return Uri
	 */
	public function copy() {
		return new self( $this );
	}
}

/**
 * Object representing the query component of a URI.
 *
 * @since 1.22
 * @author Tyler Romeo
 */
abstract class UriQuery implements Serializable {
	/**
	 * Make a new query object. Default functionality is to call
	 * UriQuery::setQuery to do so.
	 *
	 * @param $query Query to wrap
	 */
	public function __construct( $query ) { $this->setQuery( $query ); }

	/**
	 * Get the query as a string.
	 * @return string
	 */
	abstract public function getQueryString();

	/**
	 * Set the query using the given input.
	 *
	 * @param mixed $query Query to set (string/array/object)
	 */
	abstract public function setQuery( $query );

	/**
	 * Add more information to the query.
	 *
	 * @param mixed $parameters Data to add (string/array/object)
	 */
	abstract public function extendQuery( $parameters );

	/**
	 * Convenience function to convert to string. Default
	 * functionality is to call UriQuery::getQueryString.
	 * @return string
	 */
	public function __toString() { return $this->getQueryString(); }

	/* Serializable */
	function serialize() {
		return $this->getQueryString();
	}

	function unserialize( $serialized ) {
		$this->setQuery( $serialized );
	}
}

/**
 * The URI query format used in HTML forms (www-x-urlencoded) submitted to PHP.
 * Ex: a=b&d=c
 *
 * @since 1.22
 * @author Tyler Romeo
 */
class UriPhpFormQuery extends UriQuery implements ArrayAccess, Iterator {
	private $elements;

	function getQueryString() {
		return http_build_query( $this->elements );
	}

	/**
	 * Get the array of query elements.
	 * @return array
	 */
	public function getArray() {
		return $this->elements;
	}

	function setQuery( $query ) {
		if( is_array( $query ) ) {
			foreach( $query as $key => $val ) {
				if( $val === null || $val === false ) {
					unset( $query[$key] );
				}
			}
			$this->elements = $query;
		} else {
			$this->elements = array();
			parse_str( $query, $this->elements );
		}
	}

	function extendQuery( $parameters ) {
		if( is_array( $parameters ) ) {
			foreach( $parameters as $key => $val ) {
				if( $val === null || $val === false ) {
					unset( $parameters[$key] );
				}
			}
			$this->elements = array_merge( $this->elements, $parameters );
		} else {
			parse_str( $parameters, $this->elements );
		}
	}

	/* Array Access */
	function offsetExists( $offset ) {
		return array_key_exists( $offset, $this->elements );
	}

	function offsetGet( $offset ) {
		return $this->elements[$offset];
	}

	function offsetSet( $offset, $value ) {
		$this->elements[$offset] = $value;
	}

	function offsetUnset( $offset ) {
		unset( $this->elements[$offset] );
	}

	/* Iterator */
	function current() {
		return current( $this->elements );
	}

	function key() {
		return key( $this->elements );
	}

	function next() {
		next( $this->elements );
	}

	function rewind() {
		reset( $this->elements );
	}

	function valid() {
		return $this->current() !== false;
	}
}
