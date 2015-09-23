<?php
/**
 * Parser to extract query parameters out of REQUEST_URI paths.
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
 */

/**
 * PathRouter class.
 * This class can take patterns such as /wiki/$1 and use them to
 * parse query parameters out of REQUEST_URI paths.
 *
 * $router->add( "/wiki/$1" );
 *   - Matches /wiki/Foo style urls and extracts the title
 * $router->add( array( 'edit' => "/edit/$key" ), array( 'action' => '$key' ) );
 *   - Matches /edit/Foo style urls and sets action=edit
 * $router->add( '/$2/$1',
 *   array( 'variant' => '$2' ),
 *   array( '$2' => array( 'zh-hant', 'zh-hans' )
 * );
 *   - Matches /zh-hant/Foo or /zh-hans/Foo
 * $router->addStrict( "/foo/Bar", array( 'title' => 'Baz' ) );
 *   - Matches /foo/Bar explicitly and uses "Baz" as the title
 * $router->add( '/help/$1', array( 'title' => 'Help:$1' ) );
 *   - Matches /help/Foo with "Help:Foo" as the title
 * $router->add( '/$1', array( 'foo' => array( 'value' => 'bar$2' ) );
 *   - Matches /Foo and sets 'foo' to 'bar$2' without $2 being replaced
 * $router->add( '/$1', array( 'data:foo' => 'bar' ), array( 'callback' => 'functionname' ) );
 *   - Matches /Foo, adds the key 'foo' with the value 'bar' to the data array
 *     and calls functionname( &$matches, $data );
 *
 * Path patterns:
 *   - Paths may contain $# patterns such as $1, $2, etc...
 *   - $1 will match 0 or more while the rest will match 1 or more
 *   - Unless you use addStrict "/wiki" and "/wiki/" will be expanded to "/wiki/$1"
 *
 * Params:
 *   - In a pattern $1, $2, etc... will be replaced with the relevant contents
 *   - If you used a keyed array as a path pattern, $key will be replaced with
 *     the relevant contents
 *   - The default behavior is equivalent to `array( 'title' => '$1' )`,
 *     if you don't want the title parameter you can explicitly use `array( 'title' => false )`
 *   - You can specify a value that won't have replacements in it
 *     using `'foo' => array( 'value' => 'bar' );`
 *
 * Options:
 *   - The option keys $1, $2, etc... can be specified to restrict the possible values
 *     of that variable. A string can be used for a single value, or an array for multiple.
 *   - When the option key 'strict' is set (Using addStrict is simpler than doing this directly)
 *     the path won't have $1 implicitly added to it.
 *   - The option key 'callback' can specify a callback that will be run when a path is matched.
 *     The callback will have the arguments ( &$matches, $data ) and the matches array can
 *     be modified.
 *
 * @since 1.19
 * @author Daniel Friesen
 */
class PathRouter {

	/**
	 * @var array
	 */
	private $patterns = array();

	/**
	 * Protected helper to do the actual bulk work of adding a single pattern.
	 * This is in a separate method so that add() can handle the difference between
	 * a single string $path and an array() $path that contains multiple path
	 * patterns each with an associated $key to pass on.
	 * @param string $path
	 * @param array $params
	 * @param array $options
	 * @param null|string $key
	 */
	protected function doAdd( $path, $params, $options, $key = null ) {
		// Make sure all paths start with a /
		if ( $path[0] !== '/' ) {
			$path = '/' . $path;
		}

		if ( !isset( $options['strict'] ) || !$options['strict'] ) {
			// Unless this is a strict path make sure that the path has a $1
			if ( strpos( $path, '$1' ) === false ) {
				if ( substr( $path, -1 ) !== '/' ) {
					$path .= '/';
				}
				$path .= '$1';
			}
		}

		// If 'title' is not specified and our path pattern contains a $1
		// Add a default 'title' => '$1' rule to the parameters.
		if ( !isset( $params['title'] ) && strpos( $path, '$1' ) !== false ) {
			$params['title'] = '$1';
		}
		// If the user explicitly marked 'title' as false then omit it from the matches
		if ( isset( $params['title'] ) && $params['title'] === false ) {
			unset( $params['title'] );
		}

		// Loop over our parameters and convert basic key => string
		// patterns into fully descriptive array form
		foreach ( $params as $paramName => $paramData ) {
			if ( is_string( $paramData ) ) {
				if ( preg_match( '/\$(\d+|key)/u', $paramData ) ) {
					$paramArrKey = 'pattern';
				} else {
					// If there's no replacement use a value instead
					// of a pattern for a little more efficiency
					$paramArrKey = 'value';
				}
				$params[$paramName] = array(
					$paramArrKey => $paramData
				);
			}
		}

		// Loop over our options and convert any single value $# restrictions
		// into an array so we only have to do in_array tests.
		foreach ( $options as $optionName => $optionData ) {
			if ( preg_match( '/^\$\d+$/u', $optionName ) ) {
				if ( !is_array( $optionData ) ) {
					$options[$optionName] = array( $optionData );
				}
			}
		}

		$pattern = (object)array(
			'path' => $path,
			'params' => $params,
			'options' => $options,
			'key' => $key,
		);
		$pattern->weight = self::makeWeight( $pattern );
		$this->patterns[] = $pattern;
	}

	/**
	 * Add a new path pattern to the path router
	 *
	 * @param string|array $path The path pattern to add
	 * @param array $params The params for this path pattern
	 * @param array $options The options for this path pattern
	 */
	public function add( $path, $params = array(), $options = array() ) {
		if ( is_array( $path ) ) {
			foreach ( $path as $key => $onePath ) {
				$this->doAdd( $onePath, $params, $options, $key );
			}
		} else {
			$this->doAdd( $path, $params, $options );
		}
	}

	/**
	 * Add a new path pattern to the path router with the strict option on
	 * @see self::add
	 * @param string|array $path
	 * @param array $params
	 * @param array $options
	 */
	public function addStrict( $path, $params = array(), $options = array() ) {
		$options['strict'] = true;
		$this->add( $path, $params, $options );
	}

	/**
	 * Protected helper to re-sort our patterns so that the most specific
	 * (most heavily weighted) patterns are at the start of the array.
	 */
	protected function sortByWeight() {
		$weights = array();
		foreach ( $this->patterns as $key => $pattern ) {
			$weights[$key] = $pattern->weight;
		}
		array_multisort( $weights, SORT_DESC, SORT_NUMERIC, $this->patterns );
	}

	/**
	 * @param object $pattern
	 * @return float|int
	 */
	protected static function makeWeight( $pattern ) {
		# Start with a weight of 0
		$weight = 0;

		// Explode the path to work with
		$path = explode( '/', $pattern->path );

		# For each level of the path
		foreach ( $path as $piece ) {
			if ( preg_match( '/^\$(\d+|key)$/u', $piece ) ) {
				# For a piece that is only a $1 variable add 1 points of weight
				$weight += 1;
			} elseif ( preg_match( '/\$(\d+|key)/u', $piece ) ) {
				# For a piece that simply contains a $1 variable add 2 points of weight
				$weight += 2;
			} else {
				# For a solid piece add a full 3 points of weight
				$weight += 3;
			}
		}

		foreach ( $pattern->options as $key => $option ) {
			if ( preg_match( '/^\$\d+$/u', $key ) ) {
				# Add 0.5 for restrictions to values
				# This way given two separate "/$2/$1" patterns the
				# one with a limited set of $2 values will dominate
				# the one that'll match more loosely
				$weight += 0.5;
			}
		}

		return $weight;
	}

	/**
	 * Parse a path and return the query matches for the path
	 *
	 * @param string $path The path to parse
	 * @return array The array of matches for the path
	 */
	public function parse( $path ) {
		// Make sure our patterns are sorted by weight so the most specific
		// matches are tested first
		$this->sortByWeight();

		$matches = null;

		foreach ( $this->patterns as $pattern ) {
			$matches = self::extractTitle( $path, $pattern );
			if ( !is_null( $matches ) ) {
				break;
			}
		}

		// We know the difference between null (no matches) and
		// array() (a match with no data) but our WebRequest caller
		// expects array() even when we have no matches so return
		// a array() when we have null
		return is_null( $matches ) ? array() : $matches;
	}

	/**
	 * @param string $path
	 * @param string $pattern
	 * @return array|null
	 */
	protected static function extractTitle( $path, $pattern ) {
		// Convert the path pattern into a regexp we can match with
		$regexp = preg_quote( $pattern->path, '#' );
		// .* for the $1
		$regexp = preg_replace( '#\\\\\$1#u', '(?P<par1>.*)', $regexp );
		// .+ for the rest of the parameter numbers
		$regexp = preg_replace( '#\\\\\$(\d+)#u', '(?P<par$1>.+?)', $regexp );
		$regexp = "#^{$regexp}$#";

		$matches = array();
		$data = array();

		// Try to match the path we were asked to parse with our regexp
		if ( preg_match( $regexp, $path, $m ) ) {
			// Ensure that any $# restriction we have set in our {$option}s
			// matches properly here.
			foreach ( $pattern->options as $key => $option ) {
				if ( preg_match( '/^\$\d+$/u', $key ) ) {
					$n = intval( substr( $key, 1 ) );
					$value = rawurldecode( $m["par{$n}"] );
					if ( !in_array( $value, $option ) ) {
						// If any restriction does not match return null
						// to signify that this rule did not match.
						return null;
					}
				}
			}

			// Give our $data array a copy of every $# that was matched
			foreach ( $m as $matchKey => $matchValue ) {
				if ( preg_match( '/^par\d+$/u', $matchKey ) ) {
					$n = intval( substr( $matchKey, 3 ) );
					$data['$' . $n] = rawurldecode( $matchValue );
				}
			}
			// If present give our $data array a $key as well
			if ( isset( $pattern->key ) ) {
				$data['$key'] = $pattern->key;
			}

			// Go through our parameters for this match and add data to our matches and data arrays
			foreach ( $pattern->params as $paramName => $paramData ) {
				$value = null;
				// Differentiate data: from normal parameters and keep the correct
				// array key around (ie: foo for data:foo)
				if ( preg_match( '/^data:/u', $paramName ) ) {
					$isData = true;
					$key = substr( $paramName, 5 );
				} else {
					$isData = false;
					$key = $paramName;
				}

				if ( isset( $paramData['value'] ) ) {
					// For basic values just set the raw data as the value
					$value = $paramData['value'];
				} elseif ( isset( $paramData['pattern'] ) ) {
					// For patterns we have to make value replacements on the string
					$value = $paramData['pattern'];
					$replacer = new PathRouterPatternReplacer;
					$replacer->params = $m;
					if ( isset( $pattern->key ) ) {
						$replacer->key = $pattern->key;
					}
					$value = $replacer->replace( $value );
					if ( $value === false ) {
						// Pattern required data that wasn't available, abort
						return null;
					}
				}

				// Send things that start with data: to $data, the rest to $matches
				if ( $isData ) {
					$data[$key] = $value;
				} else {
					$matches[$key] = $value;
				}
			}

			// If this match includes a callback, execute it
			if ( isset( $pattern->options['callback'] ) ) {
				call_user_func_array( $pattern->options['callback'], array( &$matches, $data ) );
			}
		} else {
			// Our regexp didn't match, return null to signify no match.
			return null;
		}
		// Fall through, everything went ok, return our matches array
		return $matches;
	}

}

class PathRouterPatternReplacer {

	public $key, $params, $error;

	/**
	 * Replace keys inside path router patterns with text.
	 * We do this inside of a replacement callback because after replacement we can't tell the
	 * difference between a $1 that was not replaced and a $1 that was part of
	 * the content a $1 was replaced with.
	 * @param string $value
	 * @return string
	 */
	public function replace( $value ) {
		$this->error = false;
		$value = preg_replace_callback( '/\$(\d+|key)/u', array( $this, 'callback' ), $value );
		if ( $this->error ) {
			return false;
		}
		return $value;
	}

	/**
	 * @param array $m
	 * @return string
	 */
	protected function callback( $m ) {
		if ( $m[1] == "key" ) {
			if ( is_null( $this->key ) ) {
				$this->error = true;
				return '';
			}
			return $this->key;
		} else {
			$d = $m[1];
			if ( !isset( $this->params["par$d"] ) ) {
				$this->error = true;
				return '';
			}
			return rawurldecode( $this->params["par$d"] );
		}
	}

}
