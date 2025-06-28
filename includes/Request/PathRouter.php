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

namespace MediaWiki\Request;

use MediaWiki\Exception\FatalError;
use MediaWiki\Utils\UrlUtils;
use stdClass;

/**
 * MediaWiki\Request\PathRouter class.
 * This class can take patterns such as /wiki/$1 and use them to
 * parse query parameters out of REQUEST_URI paths.
 *
 * $router->add( "/wiki/$1" );
 *   - Matches /wiki/Foo style urls and extracts the title
 * $router->add( [ 'edit' => "/edit/$key" ], [ 'action' => '$key' ] );
 *   - Matches /edit/Foo style urls and sets action=edit
 * $router->add( '/$2/$1',
 *   [ 'variant' => '$2' ],
 *   [ '$2' => [ 'zh-hant', 'zh-hans' ] ]
 * );
 *   - Matches /zh-hant/Foo or /zh-hans/Foo
 * $router->addStrict( "/foo/Bar", [ 'title' => 'Baz' ] );
 *   - Matches /foo/Bar explicitly and uses "Baz" as the title
 * $router->add( '/help/$1', [ 'title' => 'Help:$1' ] );
 *   - Matches /help/Foo with "Help:Foo" as the title
 * $router->add( '/$1', [ 'foo' => [ 'value' => 'bar$2' ] ] );
 *   - Matches /Foo and sets 'foo' to 'bar$2' without $2 being replaced
 * $router->add( '/$1', [ 'data:foo' => 'bar' ], [ 'callback' => 'functionname' ] );
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
 *   - The default behavior is equivalent to `[ 'title' => '$1' ]`,
 *     if you don't want the title parameter you can explicitly use `[ 'title' => false ]`
 *   - You can specify a value that won't have replacements in it
 *     using `'foo' => [ 'value' => 'bar' ];`
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
	 * @var stdClass[]
	 */
	private $patterns = [];

	/**
	 * Protected helper to do the actual bulk work of adding a single pattern.
	 * This is in a separate method so that add() can handle the difference between
	 * a single string $path and an array $path that contains multiple path
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
			if ( !str_contains( $path, '$1' ) ) {
				if ( $path[-1] !== '/' ) {
					$path .= '/';
				}
				$path .= '$1';
			}
		}

		// If 'title' is not specified and our path pattern contains a $1
		// Add a default 'title' => '$1' rule to the parameters.
		if ( !isset( $params['title'] ) && str_contains( $path, '$1' ) ) {
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
				$params[$paramName] = [
					$paramArrKey => $paramData
				];
			}
		}

		// Loop over our options and convert any single value $# restrictions
		// into an array so we only have to do in_array tests.
		foreach ( $options as $optionName => $optionData ) {
			if ( preg_match( '/^\$\d+$/u', $optionName ) && !is_array( $optionData ) ) {
				$options[$optionName] = [ $optionData ];
			}
		}

		$pattern = (object)[
			'path' => $path,
			'params' => $params,
			'options' => $options,
			'key' => $key,
		];
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
	public function add( $path, $params = [], $options = [] ) {
		if ( is_array( $path ) ) {
			foreach ( $path as $key => $onePath ) {
				$this->doAdd( $onePath, $params, $options, $key );
			}
		} else {
			$this->doAdd( $path, $params, $options );
		}
	}

	/**
	 * @param string $path To be given to add()
	 * @param string $varName Full name of configuration variable for use
	 *  in error message and url to mediawiki.org Manual (e.g. "wgExample").
	 * @throws FatalError If path is invalid
	 * @internal For use by WebRequest::getPathInfo
	 */
	public function validateRoute( $path, $varName ) {
		if ( $path && !preg_match( '/^(https?:\/\/|\/)/', $path ) ) {
			// T48998: Bail out early if path is non-absolute
			throw new FatalError(
				"If you use a relative URL for \$$varName, it must start " .
				'with a slash (<code>/</code>).<br><br>See ' .
				"<a href=\"https://www.mediawiki.org/wiki/Manual:\$$varName\">" .
				"https://www.mediawiki.org/wiki/Manual:\$$varName</a>."
			);
		}
	}

	/**
	 * Add a new path pattern to the path router with the strict option on
	 * @param string|array $path
	 * @param array $params
	 * @param array $options
	 * @see self::add
	 */
	public function addStrict( $path, $params = [], $options = [] ) {
		$options['strict'] = true;
		$this->add( $path, $params, $options );
	}

	/**
	 * Protected helper to re-sort our patterns so that the most specific
	 * (most heavily weighted) patterns are at the start of the array.
	 */
	protected function sortByWeight() {
		$weights = [];
		foreach ( $this->patterns as $key => $pattern ) {
			$weights[$key] = $pattern->weight;
		}
		array_multisort( $weights, SORT_DESC, SORT_NUMERIC, $this->patterns );
	}

	/**
	 * @param stdClass $pattern
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
				$weight++;
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

		$matches = $this->internalParse( $path );
		if ( $matches === null ) {
			// Try with the normalized path (T100782)
			$path = UrlUtils::removeDotSegments( $path );
			$path = preg_replace( '#/+#', '/', $path );
			$matches = $this->internalParse( $path );
		}

		// We know the difference between null (no matches) and
		// [] (a match with no data) but our WebRequest caller
		// expects [] even when we have no matches so return
		// a [] when we have null
		return $matches ?? [];
	}

	/**
	 * Match a path against each defined pattern
	 *
	 * @param string $path
	 * @return array|null
	 */
	protected function internalParse( $path ) {
		$matches = null;

		foreach ( $this->patterns as $pattern ) {
			$matches = self::extractTitle( $path, $pattern );
			if ( $matches !== null ) {
				break;
			}
		}
		return $matches;
	}

	/**
	 * @param string $path
	 * @param stdClass $pattern
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

		$matches = [];
		$data = [];

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
					$value = self::expandParamValue( $m, $pattern->key ?? null,
						$paramData['pattern'] );
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
				$pattern->options['callback']( $matches, $data );
			}
		} else {
			// Our regexp didn't match, return null to signify no match.
			return null;
		}
		// Fall through, everything went ok, return our matches array
		return $matches;
	}

	/**
	 * Replace $key etc. in param values with the matched strings from the path.
	 *
	 * @param array $pathMatches The match results from the path
	 * @param string|null $key The key of the matching pattern
	 * @param string $value The param value to be expanded
	 * @return string|false
	 */
	protected static function expandParamValue( $pathMatches, $key, $value ) {
		$error = false;

		$replacer = static function ( $m ) use ( $pathMatches, $key, &$error ) {
			if ( $m[1] == "key" ) {
				if ( $key === null ) {
					$error = true;

					return '';
				}

				return $key;
			} else {
				$d = $m[1];
				if ( !isset( $pathMatches["par$d"] ) ) {
					$error = true;

					return '';
				}

				return rawurldecode( $pathMatches["par$d"] );
			}
		};

		$value = preg_replace_callback( '/\$(\d+|key)/u', $replacer, $value );
		if ( $error ) {
			return false;
		}

		return $value;
	}

	/**
	 * @param array $actionPaths
	 * @param string $articlePath
	 * @return string[]|false
	 * @internal For use by Title and WebRequest only.
	 */
	public static function getActionPaths( array $actionPaths, $articlePath ) {
		if ( !$actionPaths ) {
			return false;
		}
		// Processing of urls for this feature requires that 'view' is set.
		// By default, set it to the pretty article path.
		if ( !isset( $actionPaths['view'] ) ) {
			$actionPaths['view'] = $articlePath;
		}
		return $actionPaths;
	}
}
