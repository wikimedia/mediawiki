<?php
/**
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
 * Reads PHP code and returns the FQCN of every class defined within it.
 */
class ClassCollector {

	/**
	 * @var string Current namespace
	 */
	protected $namespace = '';

	/**
	 * @var array List of FQCN detected in this pass
	 */
	protected $classes;

	/**
	 * @var array|null Token from token_get_all() that started an expect sequence
	 */
	protected $startToken;

	/**
	 * @var array[]|string[] List of tokens that are members of the current expect sequence
	 */
	protected $tokens;

	/**
	 * @var array|null Class alias with target/name fields
	 */
	protected $alias;

	/**
	 * @param string $code PHP code (including <?php) to detect class names from
	 * @return array List of FQCN detected within the tokens
	 */
	public function getClasses( $code ) {
		$this->namespace = '';
		$this->classes = [];
		$this->startToken = null;
		$this->alias = null;
		$this->tokens = [];

		// HACK: The PHP tokenizer is slow (T225730).
		// Speed it up by reducing the input to the three kinds of statement we care about:
		// - namespace X;
		// - [final] [abstract] class X … {}
		// - class_alias( … );
		$lines = [];
		$matches = null;
		preg_match_all(
			// phpcs:ignore Generic.Files.LineLength.TooLong
			'#^\t*(?:namespace |(final )?(abstract )?(class|interface|trait|enum) |class_alias\()[^;{]+[;{]\s*\}?#m',
			$code,
			$matches
		);
		if ( isset( $matches[0][0] ) ) {
			foreach ( $matches[0] as $match ) {
				$match = trim( $match );
				if ( str_ends_with( $match, '{' ) ) {
					// Keep it balanced
					$match .= '}';
				}
				$lines[] = $match;
			}
		}
		$code = '<?php ' . implode( "\n", $lines ) . "\n";

		foreach ( token_get_all( $code ) as $token ) {
			if ( $this->startToken === null ) {
				$this->tryBeginExpect( $token );
			} else {
				$this->tryEndExpect( $token );
			}
		}

		return $this->classes;
	}

	/**
	 * Determine if $token begins the next expect sequence.
	 *
	 * @param array $token
	 */
	protected function tryBeginExpect( $token ) {
		if ( is_string( $token ) ) {
			return;
		}
		// Note: When changing class name discovery logic,
		// AutoLoaderStructureTest.php may also need to be updated.
		switch ( $token[0] ) {
			case T_NAMESPACE:
			case T_CLASS:
			case T_ENUM:
			case T_INTERFACE:
			case T_TRAIT:
			case T_DOUBLE_COLON:
			case T_NEW:
				$this->startToken = $token;
				break;
			case T_STRING:
				if ( $token[1] === 'class_alias' ) {
					$this->startToken = $token;
					$this->alias = [];
				}
		}
	}

	/**
	 * Accepts the next token in an expect sequence
	 *
	 * @param array|string $token
	 */
	protected function tryEndExpect( $token ) {
		// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
		switch ( $this->startToken[0] ) {
			case T_DOUBLE_COLON:
				// Skip over T_CLASS after T_DOUBLE_COLON because this is something like
				// "ClassName::class" that evaluates to a fully qualified class name. It
				// doesn't define a new class.
				$this->startToken = null;
				break;
			case T_NEW:
				// Skip over T_CLASS after T_NEW because this is an anonymous class.
				if ( !is_array( $token ) || $token[0] !== T_WHITESPACE ) {
					$this->startToken = null;
				}
				break;
			case T_NAMESPACE:
				if ( $token === ';' || $token === '{' ) {
					$this->namespace = $this->implodeTokens() . '\\';
				} else {
					$this->tokens[] = $token;
				}
				break;

			case T_STRING:
				if ( $this->alias !== null ) {
					// Flow 1 - Two string literals:
					// - T_STRING  class_alias
					// - '('
					// - T_CONSTANT_ENCAPSED_STRING 'TargetClass'
					// - ','
					// - T_WHITESPACE
					// - T_CONSTANT_ENCAPSED_STRING 'AliasName'
					// - ')'
					// Flow 2 - Use of ::class syntax for first parameter
					// - T_STRING  class_alias
					// - '('
					// - T_STRING TargetClass
					// - T_DOUBLE_COLON ::
					// - T_CLASS class
					// - ','
					// - T_WHITESPACE
					// - T_CONSTANT_ENCAPSED_STRING 'AliasName'
					// - ')'
					if ( $token === '(' ) {
						// Start of a function call to class_alias()
						$this->alias = [ 'target' => false, 'name' => false ];
					} elseif ( $token === ',' ) {
						// Record that we're past the first parameter
						if ( $this->alias['target'] === false ) {
							$this->alias['target'] = true;
						}
					} elseif ( is_array( $token ) && $token[0] === T_CONSTANT_ENCAPSED_STRING ) {
						if ( $this->alias['target'] === true ) {
							// We already saw a first argument, this must be the second.
							// Strip quotes from the string literal.
							$this->alias['name'] = self::stripQuotes( $token[1] );
						}
					} elseif ( $token === ')' ) {
						// End of function call
						$this->classes[] = $this->alias['name'];
						$this->alias = null;
						$this->startToken = null;
					} elseif ( !is_array( $token ) || (
							$token[0] !== T_STRING &&
							$token[0] !== T_DOUBLE_COLON &&
							$token[0] !== T_CLASS &&
							$token[0] !== T_WHITESPACE
						) ) {
						// Ignore this call to class_alias() - compat/Timestamp.php
						$this->alias = null;
						$this->startToken = null;
					}
				}
				break;

			case T_CLASS:
			case T_ENUM:
			case T_INTERFACE:
			case T_TRAIT:
				$this->tokens[] = $token;
				if ( is_array( $token ) && $token[0] === T_STRING ) {
					$this->classes[] = $this->namespace . $this->implodeTokens();
				}
		}
	}

	/**
	 * Decode a quoted PHP string, interpreting escape sequences, like eval($str).
	 * The implementation is half-baked, but the character set allowed in class
	 * names is pretty small. This could be replaced by a call to a fully-baked
	 * utility function.
	 *
	 * @param string $str
	 * @return string
	 */
	private static function stripQuotes( $str ) {
		return str_replace( '\\\\', '\\', substr( $str, 1, -1 ) );
	}

	/**
	 * Returns the string representation of the tokens within the
	 * current expect sequence and resets the sequence.
	 *
	 * @return string
	 */
	protected function implodeTokens() {
		$content = [];
		foreach ( $this->tokens as $token ) {
			$content[] = is_string( $token ) ? $token : $token[1];
		}

		$this->tokens = [];
		$this->startToken = null;

		return trim( implode( '', $content ), " \n\t" );
	}
}
