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
 * @ingroup Parser
 */

namespace MediaWiki\Parser;

/**
 * @ingroup Parser
 *
 * @property PPDPart_Hash[] $parts
 * @property int $startPos
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class PPDStackElement_Hash {
	/**
	 * @var string Opening character (\n for heading)
	 */
	public $open;

	/**
	 * @var string Matching closing character
	 */
	public $close;

	/**
	 * @var string Saved prefix that may affect later processing,
	 *  e.g. to differentiate `-{{{{` and `{{{{` after later seeing `}}}`.
	 */
	public $savedPrefix = '';

	/**
	 * @var int Start offset of this element in the source wikitext
	 */
	public $startPos;

	/**
	 * @var int Number of opening characters found (number of "=" for heading)
	 */
	public $count;

	/**
	 * @var PPDPart_Hash[] Array of PPDPart objects describing pipe-separated parts.
	 */
	public $parts;

	/**
	 * @var bool True if the open char appeared at the start of the input line.
	 *  Not set for headings.
	 */
	public $lineStart;

	/** @var string */
	public $partClass = PPDPart_Hash::class;

	public function __construct( array $data = [] ) {
		$class = $this->partClass;
		$this->parts = [ new $class ];

		foreach ( $data as $name => $value ) {
			$this->$name = $value;
		}
	}

	public function &getAccum() {
		return $this->parts[count( $this->parts ) - 1]->out;
	}

	public function addPart( $s = '' ) {
		$class = $this->partClass;
		$this->parts[] = new $class( $s );
	}

	/**
	 * @return PPDPart_Hash
	 */
	public function getCurrentPart() {
		return $this->parts[count( $this->parts ) - 1];
	}

	/**
	 * @return array
	 */
	public function getFlags() {
		$partCount = count( $this->parts );
		$findPipe = $this->open != "\n" && $this->open != '[';
		return [
			'findPipe' => $findPipe,
			'findEquals' => $findPipe && $partCount > 1 && !isset( $this->parts[$partCount - 1]->eqpos ),
			'inHeading' => $this->open == "\n",
		];
	}

	/**
	 * Get the accumulator that would result if the close is not found.
	 *
	 * @param int|false $openingCount
	 * @return array
	 */
	public function breakSyntax( $openingCount = false ) {
		if ( $this->open == "\n" ) {
			$accum = array_merge( [ $this->savedPrefix ], $this->parts[0]->out );
		} else {
			if ( $openingCount === false ) {
				$openingCount = $this->count;
			}
			$s = substr( $this->open, 0, -1 );
			$s .= str_repeat(
				substr( $this->open, -1 ),
				$openingCount - strlen( $s )
			);
			$accum = [ $this->savedPrefix . $s ];
			$lastIndex = 0;
			$first = true;
			foreach ( $this->parts as $part ) {
				if ( $first ) {
					$first = false;
				} elseif ( is_string( $accum[$lastIndex] ) ) {
					$accum[$lastIndex] .= '|';
				} else {
					$accum[++$lastIndex] = '|';
				}

				foreach ( $part->out as $node ) {
					if ( is_string( $node ) && is_string( $accum[$lastIndex] ) ) {
						$accum[$lastIndex] .= $node;
					} else {
						$accum[++$lastIndex] = $node;
					}
				}
			}
		}
		return $accum;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( PPDStackElement_Hash::class, 'PPDStackElement_Hash' );
