<?php
/**
 * Some of these functions are adapted from places in MediaWiki.
 * Should probably merge them for consistency.
 *
 * Copyright © 2004 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
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
 * @ingroup UtfNormal
 */


use UtfNormal\Utils;
/**
 * Return UTF-8 sequence for a given Unicode code point.
 *
 * @param $codepoint Integer:
 * @return String
 * @throws InvalidArgumentException if fed out of range data.
 * @public
 * @deprecated since 1.25, use UtfNormal\Utils directly
 */
function codepointToUtf8( $codepoint ) {
	return Utils::codepointToUtf8( $codepoint );
}

/**
 * Take a series of space-separated hexadecimal numbers representing
 * Unicode code points and return a UTF-8 string composed of those
 * characters. Used by UTF-8 data generation and testing routines.
 *
 * @param $sequence String
 * @return String
 * @throws InvalidArgumentException if fed out of range data.
 * @private
 * @deprecated since 1.25, use UtfNormal\Utils directly
 */
function hexSequenceToUtf8( $sequence ) {
	return Utils::hexSequenceToUtf8( $sequence );
}

/**
 * Take a UTF-8 string and return a space-separated series of hex
 * numbers representing Unicode code points. For debugging.
 *
 * @fixme this is private but extensions + maint scripts are using it
 * @param string $str UTF-8 string.
 * @return string
 * @private
 */
function utf8ToHexSequence( $str ) {
	$buf = '';
	foreach ( preg_split( '//u', $str, -1, PREG_SPLIT_NO_EMPTY ) as $cp ) {
		$buf .= sprintf( '%04x ', UtfNormal\Utils::utf8ToCodepoint( $cp ) );
	}

	return rtrim( $buf );
}

/**
 * Determine the Unicode codepoint of a single-character UTF-8 sequence.
 * Does not check for invalid input data.
 *
 * @param $char String
 * @return Integer
 * @public
 * @deprecated since 1.25, use UtfNormal\Utils directly
 */
function utf8ToCodepoint( $char ) {
	return Utils::utf8ToCodepoint( $char );
}

/**
 * Escape a string for inclusion in a PHP single-quoted string literal.
 *
 * @param string $string string to be escaped.
 * @return String: escaped string.
 * @public
 * @deprecated since 1.25, use UtfNormal\Utils directly
 */
function escapeSingleString( $string ) {
	return Utils::escapeSingleString( $string );
}
