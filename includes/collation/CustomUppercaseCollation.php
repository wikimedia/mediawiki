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
 * @since 1.30
 *
 * @file
 */

/**
 * Resort normal UTF-8 order by putting a bunch of stuff in PUA
 *
 * This takes a bunch of characters (The alphabet) that should,
 * be together, and converts them all to private-use-area characters
 * so that they are all sorted in the right order relative to each
 * other.
 *
 * This renumbers characters starting at U+F3000 (Chosen to avoid
 * conflicts with other people using private use area)
 *
 * This does not support fancy things like secondary differences, etc.
 *
 * It is expected most people will subclass this and just override the
 * constructor to hard-code an alphabet.
 */
class CustomUppercaseCollation extends NumericUppercaseCollation {

	/** @var array $alphabet Sorted array of letters */
	private $alphabet;

	/** @var array $puaSubset List of private use area codes */
	private $puaSubset;

	/**
	 * @note This assumes $alphabet does not contain U+F3000-U+F303F
	 *
	 * @param array $alphabet Sorted array of uppercase characters.
	 * @param Language $lang What language for number sorting.
	 */
	public function __construct( array $alphabet, Language $lang ) {
		// It'd be trivial to extend this past 64, you'd just
		// need a bit of bit-fiddling. Doesn't seem necessary right
		// now.
		if ( count( $alphabet ) < 1 || count( $alphabet ) >= 64 ) {
			throw new UnexpectedValueException( "Alphabet must be < 64 items" );
		}
		$this->alphabet = $alphabet;

		$this->puaSubset = [];
		$len = count( $alphabet );
		for ( $i = 0; $i < $len; $i++ ) {
			$this->puaSubset[] = "\xF3\xB3\x80" . chr( $i + 128 );
		}
		parent::__construct( $lang );
	}

	private function convertToPua( $string ) {
		return str_replace( $this->alphabet, $this->puaSubset, $string );
	}

	public function getSortKey( $string ) {
		return $this->convertToPua( parent::getSortKey( $string ) );
	}

	public function getFirstLetter( $string ) {
		// In case a title has a PUA code in it, make it sort
		// under the header for the character it would replace
		// to avoid inconsistent behaviour. This class mostly
		// assumes that people will not use PUA codes.
		return parent::getFirstLetter(
			str_replace( $this->puaSubset, $this->alphabet, $string )
		);
	}
}
