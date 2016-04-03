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
	 * @var string $code PHP code (including <?php) to detect class names from
	 * @return array List of FQCN detected within the tokens
	 */
	public function getClasses( $code ) {
		$this->namespace = '';
		$this->classes = [];
		$this->startToken = null;
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
		switch ( $token[0] ) {
			case T_NAMESPACE:
			case T_CLASS:
			case T_INTERFACE:
			case T_TRAIT:
			case T_DOUBLE_COLON:
				$this->startToken = $token;
		}
	}

	/**
	 * Accepts the next token in an expect sequence
	 *
	 * @param array
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
