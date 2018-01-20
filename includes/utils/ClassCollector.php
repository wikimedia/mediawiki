<?php

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
	 * @var array Token from token_get_all() that started an expect sequence
	 */
	protected $startToken;

	/**
	 * @var array List of tokens that are members of the current expect sequence
	 */
	protected $tokens;

	/**
	 * @var array Class alias with target/name fields
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
		// AutoLoaderTest.php may also need to be updated.
		switch ( $token[0] ) {
			case T_NAMESPACE:
			case T_CLASS:
			case T_INTERFACE:
			case T_TRAIT:
			case T_DOUBLE_COLON:
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
	 * @param array $token
	 */
	protected function tryEndExpect( $token ) {
		switch ( $this->startToken[0] ) {
			case T_DOUBLE_COLON:
				// Skip over T_CLASS after T_DOUBLE_COLON because this is something like
				// "self::static" which accesses the class name. It doens't define a new class.
				$this->startToken = null;
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
							$this->alias['name'] = substr( $token[1], 1, -1 );
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
			case T_INTERFACE:
			case T_TRAIT:
				$this->tokens[] = $token;
				if ( is_array( $token ) && $token[0] === T_STRING ) {
					$this->classes[] = $this->namespace . $this->implodeTokens();
				}
		}
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
