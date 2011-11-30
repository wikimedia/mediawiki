<?php
/**
 * PathRouter class.
 * This class can take patterns such as /wiki/$1 and use them to
 * parse query parameters out of REQUEST_URI paths.
 *
 * $router->add( "/wiki/$1" );
 *   - Matches /wiki/Foo style urls and extracts the title
 * $router->add( array( 'edit' => "/edit/$1" ), array( 'action' => '$key' ) );
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
 * $router->add( '/help/$1', array( 'title' => 'Help:$1' ) );
 *   - Matches 
 * $router->add( '/$1', array( 'foo' => array( 'value' => 'bar$2' ) );
 *   - Matches /Foo and sets 'foo=bar$2' without $2 being replaced
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
 *   - If you used a keyed array as a path pattern $key will be replaced with the relevant contents
 *   - The default behavior is equivalent to `array( 'title' => '$1' )`, if you don't want the title parameter you can explicitly use `array( 'title' => false )`
 *   - You can specify a value that won't have replacements in it using `'foo' => array( 'value' => 'bar' );`
 *
 * Options:
 *   - The option keys $1, $2, etc... can be specified to restrict the possible values of that variable.
 *     A string can be used for a single value, or an array for multiple.
 *   - When the option key 'strict' is set (Using addStrict is simpler than doing this directly)
 *     the path won't have $1 implicitly added to it.
 *   - The option key 'callback' can specify a callback that will be run when a path is matched.
 *     The callback will have the arguments ( &$matches, $data ) and the matches array can be modified.
 *
 * @since 1.19
 * @author Daniel Friesen
 */
class PathRouter {
	
	protected function doAdd( $path, $params, $options, $key = null ) {
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

		if ( !isset( $params['title'] ) && strpos( $path, '$1' ) !== false ) {
			$params['title'] = '$1';
		}
		if ( isset( $params['title'] ) && $params['title'] === false ) {
			unset( $params['title'] );
		}

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
	 * @param $path The path pattern to add
	 * @param $params The params for this path pattern
	 * @param $options The options for this path pattern
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
	 */
	public function addStrict( $path, $params = array(), $options = array() ) {
		$options['strict'] = true;
		$this->add( $path, $params, $options );
	}

	protected function sortByWeight() {
		$weights = array();
		foreach( $this->patterns as $key => $pattern ) {
			$weights[$key] = $pattern->weight;
		}
		array_multisort( $weights, SORT_DESC, SORT_NUMERIC, $this->patterns );
	}

	public static function makeWeight( $pattern ) {
		# Start with a weight of 0
		$weight = 0;

		// Explode the path to work with
		$path = explode( '/', $pattern->path );

		# For each level of the path
		foreach( $path as $piece ) {
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
	 * @param $path The path to parse
	 * @return Array The array of matches for the path
	 */
	public function parse( $path ) {
		$this->sortByWeight();
		
		$matches = null;

		foreach ( $this->patterns as $pattern ) {
			$matches = self::extractTitle( $path, $pattern );
			if ( !is_null( $matches ) ) {
				break;
			}
		}

		return is_null( $matches ) ? array() : $matches;
	}

	protected static function extractTitle( $path, $pattern ) {
		$regexp = preg_quote( $pattern->path, '#' );
		$regexp = preg_replace( '#\\\\\$1#u', '(?P<par1>.*)', $regexp );
		$regexp = preg_replace( '#\\\\\$(\d+)#u', '(?P<par$1>.+?)', $regexp );
		$regexp = "#^{$regexp}$#";

		$matches = array();
		$data = array();

		if ( preg_match( $regexp, $path, $m ) ) {
			foreach ( $pattern->options as $key => $option ) {
				if ( preg_match( '/^\$\d+$/u', $key ) ) {
					$n = intval( substr( $key, 1 ) );
					$value = rawurldecode( $m["par{$n}"] );
					if ( !in_array( $value, $option ) ) {
						return null;
					}
				}
			}

			foreach ( $m as $matchKey => $matchValue ) {
				if ( preg_match( '/^par\d+$/u', $matchKey ) ) {
					$n = intval( substr( $matchKey, 3 ) );
					$data['$'.$n] = rawurldecode( $matchValue );
				}
			}
			if ( isset( $pattern->key ) ) {
				$data['$key'] = $pattern->key;
			}

			foreach ( $pattern->params as $paramName => $paramData ) {
				$value = null;
				if ( preg_match( '/^data:/u', $paramName ) ) {
					$isData = true;
					$key = substr( $paramName, 5 );
				} else {
					$isData = false;
					$key = $paramName;
				}

				if ( isset( $paramData['value'] ) ) {
					$value = $paramData['value'];
				} elseif ( isset( $paramData['pattern'] ) ) {
					$value = $paramData['pattern'];
					foreach ( $m as $matchKey => $matchValue ) {
						if ( preg_match( '/^par\d+$/u', $matchKey ) ) {
							$n = intval( substr( $matchKey, 3 ) );
							$value = str_replace( '$' . $n, rawurldecode( $matchValue ), $value );
						}
					}
					if ( isset( $pattern->key ) ) {
						$value = str_replace( '$key', $pattern->key, $value );
					}
					if ( preg_match( '/\$(\d+|key)/u', $value ) ) {
						// Still contains $# or $key patterns after replacement
						// Seams like we don't have all the data, abort
						return null;
					}
				}

				if ( $isData ) {
					$data[$key] = $value;
				} else {
					$matches[$key] = $value;
				}
			}

			if ( isset( $pattern->options['callback'] ) ) {
				call_user_func_array( $pattern->options['callback'], array( &$matches, $data ) );
			}
		} else {
			return null;
		}
		return $matches;
	}

}
