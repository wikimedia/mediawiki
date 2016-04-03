<?php
/**
 * Database row sorting.
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

/**
 * @since 1.16.3
 * @author Tim Starling
 */
abstract class Collation {
	private static $instance;

	/**
	 * @since 1.16.3
	 * @return Collation
	 */
	public static function singleton() {
		if ( !self::$instance ) {
			global $wgCategoryCollation;
			self::$instance = self::factory( $wgCategoryCollation );
		}
		return self::$instance;
	}

	/**
	 * @since 1.16.3
	 * @throws MWException
	 * @param string $collationName
	 * @return Collation
	 */
	public static function factory( $collationName ) {
		switch ( $collationName ) {
			case 'uppercase':
				return new UppercaseCollation;
			case 'identity':
				return new IdentityCollation;
			case 'uca-default':
				return new IcuCollation( 'root' );
			case 'xx-uca-ckb':
				return new CollationCkb;
			case 'xx-uca-et':
				return new CollationEt;
			default:
				$match = [];
				if ( preg_match( '/^uca-([a-z@=-]+)$/', $collationName, $match ) ) {
					return new IcuCollation( $match[1] );
				}

				# Provide a mechanism for extensions to hook in.
				$collationObject = null;
				Hooks::run( 'Collation::factory', [ $collationName, &$collationObject ] );

				if ( $collationObject instanceof Collation ) {
					return $collationObject;
				}

				// If all else fails...
				throw new MWException( __METHOD__ . ": unknown collation type \"$collationName\"" );
		}
	}

	/**
	 * Given a string, convert it to a (hopefully short) key that can be used
	 * for efficient sorting.  A binary sort according to the sortkeys
	 * corresponds to a logical sort of the corresponding strings.  Current
	 * code expects that a line feed character should sort before all others, but
	 * has no other particular expectations (and that one can be changed if
	 * necessary).
	 *
	 * @since 1.16.3
	 *
	 * @param string $string UTF-8 string
	 * @return string Binary sortkey
	 */
	abstract function getSortKey( $string );

	/**
	 * Given a string, return the logical "first letter" to be used for
	 * grouping on category pages and so on.  This has to be coordinated
	 * carefully with convertToSortkey(), or else the sorted list might jump
	 * back and forth between the same "initial letters" or other pathological
	 * behavior.  For instance, if you just return the first character, but "a"
	 * sorts the same as "A" based on getSortKey(), then you might get a
	 * list like
	 *
	 * == A ==
	 * * [[Aardvark]]
	 *
	 * == a ==
	 * * [[antelope]]
	 *
	 * == A ==
	 * * [[Ape]]
	 *
	 * etc., assuming for the sake of argument that $wgCapitalLinks is false.
	 *
	 * @since 1.16.3
	 *
	 * @param string $string UTF-8 string
	 * @return string UTF-8 string corresponding to the first letter of input
	 */
	abstract function getFirstLetter( $string );

}
