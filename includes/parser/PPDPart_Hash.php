<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Parser
 */

namespace MediaWiki\Parser;

/**
 * @ingroup Parser
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class PPDPart_Hash {
	/**
	 * @var string[] Output accumulator
	 */
	public $out;

	/**
	 * @var int|null Index of equals sign, if found
	 */
	public $eqpos;

	/**
	 * @var int|null
	 */
	public $commentEnd;

	/**
	 * @var int|null
	 */
	public $visualEnd;

	public function __construct( string $out = '' ) {
		$this->out = [];

		if ( $out !== '' ) {
			$this->out[] = $out;
		}
	}
}

/** @deprecated class alias since 1.43 */
class_alias( PPDPart_Hash::class, 'PPDPart_Hash' );
