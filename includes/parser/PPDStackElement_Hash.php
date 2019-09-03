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

/**
 * @ingroup Parser
 * @property PPDPart_Hash[] $parts
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class PPDStackElement_Hash extends PPDStackElement {

	public function __construct( $data = [] ) {
		$this->partClass = PPDPart_Hash::class;
		parent::__construct( $data );
	}

	/**
	 * Get the accumulator that would result if the close is not found.
	 *
	 * @param int|bool $openingCount
	 * @return array
	 * @suppress PhanParamSignatureMismatch
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
