<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Parser
 */

namespace MediaWiki\Parser;

/**
 * Expansion frame with custom arguments
 * @ingroup Parser
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class PPCustomFrame_Hash extends PPFrame_Hash {

	/** @var array */
	public $args;

	/**
	 * @param Preprocessor $preprocessor
	 * @param array $args
	 */
	public function __construct( $preprocessor, $args ) {
		parent::__construct( $preprocessor );
		$this->args = $args;
	}

	public function __toString() {
		$s = 'cstmframe{';
		$first = true;
		foreach ( $this->args as $name => $value ) {
			if ( $first ) {
				$first = false;
			} else {
				$s .= ', ';
			}
			$s .= "\"$name\":\"" .
				str_replace( '"', '\\"', $value->__toString() ) . '"';
		}
		$s .= '}';
		return $s;
	}

	/**
	 * @return bool
	 */
	public function isEmpty() {
		return !count( $this->args );
	}

	/**
	 * @param int|string $index
	 * @return string|false
	 */
	public function getArgument( $index ) {
		return $this->args[$index] ?? false;
	}

	/** @inheritDoc */
	public function getArguments() {
		return $this->args;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( PPCustomFrame_Hash::class, 'PPCustomFrame_Hash' );
