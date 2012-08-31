<?php
/**
 * Classes for URI-related handling
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @file
 */

/**
 * Class for simple URI parsing and manipulation.
 * Intended to simplify things that were using wfParseUrl and
 * had to do manual concatenation for various needs.
 *
 * @file
 * @author Daniel Friesen
 * @author Tyler Romeo
 * @since 1.23
 */
class Uri
{
	/**
	 * The full string URI
	 *
	 * @var null|string
	 */
	protected $uri = null;

	/**
	 * The parsed components of the URI, null if not loaded, or false for invalid
	 *
	 * @var string[]|null|bool
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
	 *
	 * @return array Bits of the URL in an associative array, per PHP docs
	 */
	protected static function parseUri( $url ) {
		// parse_url() cannot handle URIs without schemes, so add one for protocol-relative URIs.
		$wasRelative = substr( $url, 0, 2 ) == '//';
		if ( $wasRelative ) {
			$url = "http:$url";
		}

		$bits = parse_url( $url );

		// parse_url() returns an array without scheme for some invalid URLs, e.g.
		// parse_url("%0Ahttp://example.com") == array( 'host' => '%0Ahttp', 'path' => 'example.com' )
		if (
			!$bits ||
			!isset( $bits[ 'scheme' ] ) && strpos( $url, "://" ) !== false
		) {
			return false;
		}

		// If the URL was protocol-relative, fix scheme
		if ( $wasRelative ) {
			unset( $bits[ 'scheme' ] );
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
	 * @param string $str Component to encode
	 *
	 * @return string
	 */
	public static function encode( $str ) {
		if ( $str === null ) {
			return '';
		}

		$unescaped = array_keys( self::$unescapes );
		$escaped = array_values( self::$unescapes );

		$unescaped[ ] = ':';
		if ( !isset( $_SERVER[ 'SERVER_SOFTWARE' ] ) ||
			( strpos( $_SERVER[ 'SERVER_SOFTWARE' ], 'Microsoft-IIS/7' ) === false )
		) {
			$escaped[ ] = '%3A';
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
	 * @param string $str Component to decode
	 *
	 * @return string
	 */
	public static function decode( $str ) {
		return urldecode( $str );
	}

	/**
	 * Make a new Uri.
	 *
	 * @param $uri mixed URI string or array
	 */
	public function __construct( $uri = null ) {
		$this->components = array();
		$this->setUri( $uri );
	}

	/**
	 * Set the Uri to the value of some other URI.
	 *
	 * @param Uri|string|array|null $uri URI string, array of components, existing Uri object, or null
	 *
	 * @throws InvalidArgumentException if $uri is an invalid type
	 */
	public function setUri( $uri ) {
		if ( $uri === null ) {
			return;
		} elseif ( is_string( $uri ) ) {
			$this->uri = $uri;
			$this->components = null;
		} elseif ( is_array( $uri ) ) {
			$this->setComponents( $uri );
		} elseif ( $uri instanceof Uri ) {
			$this->uri = $uri->uri;
			$this->components = $uri->components;
		} else {
			throw new InvalidArgumentException( __METHOD__ . ": $uri is not of a valid type." );
		}
	}

	/**
	 * Load the components from the string Uri.
	 */
	public function load() {
		if ( $this->components !== null ) {
			return;
		}

		$parsed = self::parseUri( $this->uri );

		if ( $parsed === false ) {
			$this->components = false;
		} else {
			$this->setComponents( $parsed );
		}
	}

	/**
	 * Return the components for this Uri
	 *
	 * @return array|bool Array of components, or false if the object is invalid
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
	 *
	 * @return string|null Component value, or null if not found
	 * @throws OutOfBoundsException if name is not a valid key
	 */
	public function getComponent( $name ) {
		$this->load();

		if ( isset( self::$componentAliases[ $name ] ) ) {
			// Component is an alias. Get the actual name.
			$name = self::$componentAliases[ $name ];
		}

		if ( !in_array( $name, self::$validComponents ) ) {
			// Component is invalid
			throw new OutOfBoundsException( "$name is not a valid component." );
		} elseif ( !empty( $this->components[ $name ] ) ) {
			// Component is valid and has a value.
			return $this->components[ $name ];
		} else {
			// Component is empty
			return null;
		}
	}

	/**
	 * Set the components of this array.
	 * Will output warnings when invalid components or aliases are found.
	 *
	 * @param array $components The components to set on this Uri.
	 * @param bool $blank Whether to erase all current components first
	 * @param bool $override Whether the given components should override existing components
	 *
	 * @throws OutOfBoundsException if any component names are invalid
	 */
	public function setComponents( array $components, $blank = true, $override = false ) {
		if ( !$blank ) {
			$this->load();
		}

		foreach ( $components as $name => $value ) {
			if ( isset( self::$componentAliases[ $name ] ) ) {
				$canonical = self::$componentAliases[ $name ];
				$components[ $canonical ] = $value;
				unset( $components[ $name ] );
			} elseif ( !in_array( $name, self::$validComponents ) ) {
				throw new OutOfBoundsException( "$name is not a valid component." );
			} elseif ( !$override && isset( $this->components[ $name ] ) ) {
				unset( $components[ $name ] );
			}
		}

		$this->uri = null;
		$components = array_map( array( 'self', 'decode' ), $components );
		if ( $blank ) {
			$this->components = $components;
		} else {
			$this->components = array_merge( $this->components, $components );
		}
	}

	/**
	 * Set a component for this URI
	 *
	 * @param string $name The name of the component to set
	 * @param string|null $value The value to set
	 * @param bool $decode Whether to urldecode the value or not
	 *
	 * @throws OutOfBoundsException if component name is invalid
	 * @throws LogicException if object is in invalid state
	 */
	public function setComponent( $name, $value, $decode = true ) {
		$this->load();

		if ( !is_array( $this->components ) ) {
			throw new LogicException( 'Object is in an invalid state. Try using Uri::setComponents' );
		}

		if ( isset( self::$componentAliases[ $name ] ) ) {
			$name = self::$componentAliases[ $name ];
		} elseif ( !in_array( $name, self::$validComponents ) ) {
			throw new OutOfBoundsException( "$name is not a valid component." );
		}

		$this->uri = null;
		$this->components[ $name ] = $decode && is_string( $value ) ? self::decode( $value ) : $value;
	}

	/**
	 * Get the scheme, a.k.a. the protocol, of the URI
	 *
	 * @return null|string
	 */
	public function getScheme() {
		return $this->getComponent( 'scheme' );
	}

	/**
	 * Get the username part of the URI
	 *
	 * @return null|string
	 */
	public function getUser() {
		return $this->getComponent( 'user' );
	}

	/**
	 * Get the password part of the URI
	 *
	 * It is not recommended to use this function, as the RFC on URIs does not recommend putting
	 * passwords in URIs.
	 *
	 * @return null|string
	 */
	public function getPassword() {
		return $this->getComponent( 'pass' );
	}

	/**
	 * Get the host portion of the URI, without the port
	 *
	 * @return null|string
	 */
	public function getHost() {
		return $this->getComponent( 'host' );
	}

	/**
	 * Get the port number of the URI
	 *
	 * This will return the actual port that is stored in the URI. If the URI does not
	 * have an explicit port, return null. This function will *not* return any default
	 * port if the URI does not explicitly specify one.
	 *
	 * @return null|string
	 */
	public function getPort() {
		return $this->getComponent( 'port' );
	}

	/**
	 * Get the path portion of the URI (including the leading slash, if applicable)
	 *
	 * @return null|string
	 */
	public function getPath() {
		return $this->getComponent( 'path' );
	}

	/**
	 * Get the query portion of the URI (without the leading question mark)
	 *
	 * @return null|string
	 */
	public function getQuery() {
		return $this->getComponent( 'query' );
	}

	/**
	 * Get the fragment portion of the URI (without the leading hash)
	 *
	 * @return null|string
	 */
	public function getFragment() {
		return $this->getComponent( 'fragment' );
	}

	/**
	 * Set or remove the scheme/protocol of the URI
	 *
	 * @param string|null $scheme Scheme, or null to make protocol-relative
	 *
	 * @return Uri $this
	 */
	public function setScheme( $scheme ) {
		$this->setComponent( 'scheme', $scheme );

		return $this;
	}

	/**
	 * Set or remove the user portion of the URI
	 *
	 * @param string|null $user Username, or null to remove the user component
	 *
	 * @return Uri $this
	 */
	public function setUser( $user ) {
		$this->setComponent( 'user', $user );

		return $this;
	}

	/**
	 * Set or remove the password portion of the Uri
	 *
	 * @param string|null $pass
	 *
	 * @return Uri $this
	 */
	public function setPassword( $pass ) {
		$this->setComponent( 'pass', $pass );

		return $this;
	}

	/**
	 * Set or remove the hostname of the URI
	 *
	 * Removing the hostname will also implicitly remove the user, password, and port parts
	 * of the URI, i.e., the entire authority. This is because the host is a required part
	 * of the authority.
	 *
	 * @param string|null $host Hostname, or null to remove
	 *
	 * @return Uri $this
	 */
	public function setHost( $host ) {
		$this->setComponent( 'host', $host );

		return $this;
	}

	/**
	 * Set or remove the port of the URI
	 *
	 * @param string|null $port Port number, or null to remove
	 *
	 * @return Uri $this
	 */
	public function setPort( $port ) {
		$this->setComponent( 'port', $port );

		return $this;
	}

	/**
	 * Set or remove the path portion of the URI
	 *
	 * @param string|null $path Absolute path, or null to remove
	 *
	 * @return Uri $this
	 */
	public function setPath( $path ) {
		$this->setComponent( 'path', $path );

		return $this;
	}

	/**
	 * Set or remove the fragment portion of the URI
	 *
	 * @param string|null $fragment Fragment, or null to remove
	 *
	 * @return Uri $this
	 */
	public function setFragment( $fragment ) {
		$this->setComponent( 'fragment', $fragment );

		return $this;
	}

	/**
	 * Gets query portion of a URI.
	 *
	 * @param string|array $query
	 *
	 * @return Uri $this for method chaining
	 */
	public function setQuery( $query ) {
		if ( is_array( $query ) ) {
			$query = new UriPhpFormQuery( $query );
		}
		$this->setComponent( 'query', $query );

		return $this;
	}

	/**
	 * Extend the query -- supply query parameters to override or add to ours
	 *
	 * @param array|string $parameters query parameters to override or add
	 *
	 * @return Uri this URI object
	 */
	public function extendQuery( $parameters ) {
		if ( $parameters === null || $parameters === '' || $parameters === array() ) {
			return $this;
		}

		$query = $this->getQuery();

		if ( $query instanceof UriQuery ) {
			$query->extendQuery( $parameters );
		} else {
			// Not a custom query class, emulate wfAppendQuery.
			if ( is_array( $parameters ) ) {
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
	 * @param string|Uri $server String or URI object to expand with
	 *
	 * @return Uri $this for method chaining
	 */
	public function expand( $server = null ) {
		global $wgServer;

		if ( !$server ) {
			$server = $wgServer;
		}
		if ( !$server instanceof Uri ) {
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

		while ( $input ) {
			$pref1 = substr( $input, 0, 1 );
			$pref2 = substr( $input, 0, 2 );
			$pref3 = substr( $input, 0, 3 );
			$pref4 = substr( $input, 0, 4 );

			// Step A
			if ( $pref3 == '../' ) {
				$input = substr( $input, 3 );
			} elseif ( $pref2 == './' ) {
				$input = substr( $input, 2 );
				// Step B
			} elseif ( $pref3 == '/./' ) {
				$input = substr( $input, 2 );
			} elseif ( $pref2 == '/.' && strlen( $input ) == 2 ) {
				$input = '/' . substr( $input, 2 );
				// Step C
			} elseif ( $pref4 == '/../' ) {
				$input = substr( $input, 3 );
				array_pop( $output );
			} elseif ( $pref3 == '/..' && strlen( $input ) == 3 ) {
				$input = '/' . substr( $input, 3 );
				array_pop( $output );
				// Step D
			} elseif ( $input == '.' || $input == '..' ) {
				$input = '';
				// Step E
			} else {
				$limit = $pref1 == '/' ? strpos( $input, '/', 1 ) : strpos( $input, '/' );
				if ( $limit === false ) {
					$limit = strlen( $input );
				}
				$output[ ] = substr( $input, 0, $limit );
				$input = substr( $input, $limit );
			}
		}

		$this->setComponent( 'path', implode( '', $output ) );

		return $this;
	}

	/**
	 * Returns user and password portion of a URI.
	 *
	 * @return string
	 */
	public function getUserInfo() {
		$user = urlencode( $this->getComponent( 'user' ) );
		$pass = urlencode( $this->getComponent( 'pass' ) );

		return $pass ? "$user:$pass" : $user;
	}

	/**
	 * Gets host and port portion of a URI.
	 *
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
	 *
	 * @return string
	 */
	public function getAuthority() {
		$userinfo = $this->getUserInfo();
		$hostinfo = $this->getHostPort();

		return $userinfo ? "$userinfo@$hostinfo" : $hostinfo;
	}

	/**
	 * Returns everything after the authority section of the URI
	 *
	 * @return String
	 */
	public function getRelativePath() {
		$path = self::encode( $this->getComponent( 'path' ) );
		$query = $this->getComponent( 'query' );
		$fragment = $this->getComponent( 'fragment' );

		$retval = $path;
		if ( $query ) {
			$retval .= "?$query";
		}
		if ( $fragment ) {
			$retval .= "#$fragment";
		}

		return $retval;
	}

	/**
	 * Gets the entire URI string.
	 *
	 * @return String the URI string
	 */
	public function toString() {
		if ( $this->uri !== null ) {
			return $this->uri;
		}

		$uri = '';

		$scheme = $this->getComponent( 'scheme' );
		$authority = $this->getAuthority();
		$path = $this->getRelativePath();

		if ( $scheme ) {
			$uri .= "$scheme:";
		}
		if ( $authority && in_array( substr( $path, 0, 1 ), array( false, '?', '#', '/' ) ) ) {
			$uri .= "//$authority";
		}
		$uri .= $path;

		$this->uri = $uri;

		return $uri;
	}

	/**
	 * Gets the entire URI string.
	 *
	 * @return String the URI string
	 */
	public function __toString() {
		return $this->toString();
	}

	/**
	 * Make a copy of the current Uri.
	 *
	 * @return Uri
	 */
	public function copy() {
		return new self( $this );
	}
}
