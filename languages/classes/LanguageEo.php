<?php
/**
 * Esperanto (Esperanto) specific code.
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
 * @author Brion Vibber <brion@pobox.com>
 * @ingroup Language
 */

/**
 * Esperanto (Esperanto)
 *
 * @ingroup Language
 */
class LanguageEo extends Language {
	/**
	 * Wrapper for charset conversions.
	 *
	 * In most languages, this calls through to standard system iconv(), but
	 * for Esperanto we're also adding a special pseudo-charset to convert
	 * accented characters to/from the ASCII-friendly "X" surrogate coding:
	 *
	 *     cx = ĉ     cxx = cx
	 *     gx = ĝ     gxx = gx
	 *     hx = ĥ     hxx = hx
	 *     jx = ĵ     jxx = jx
	 *     sx = ŝ     sxx = sx
	 *     ux = ŭ     uxx = ux
	 *     xx = x
	 *
	 *   http://en.wikipedia.org/wiki/Esperanto_orthography#X-system
	 *   http://eo.wikipedia.org/wiki/X-sistemo
	 *
	 * X-conversion is applied, in either direction, between "utf-8" and "x" charsets;
	 * this comes into effect when input is run through $wgRequest->getText() and the
	 * $wgEditEncoding is set to 'x'.
	 *
	 * In the long run, this should be moved out of here and into the client-side
	 * editor behavior; the original server-side translation system dates to 2002-2003
	 * when many browsers with really bad Unicode support were still in use.
	 *
	 * @param string $in input character set
	 * @param string $out output character set
	 * @param string $string text to be converted
	 * @return string
	 */
	function iconv( $in, $out, $string ) {
		if ( strcasecmp( $in, 'x' ) == 0 && strcasecmp( $out, 'utf-8' ) == 0 ) {
			return preg_replace_callback (
				'/([cghjsu]x?)((?:xx)*)(?!x)/i',
				array( $this, 'strrtxuCallback' ), $string	);
		} elseif ( strcasecmp( $in, 'UTF-8' ) == 0 && strcasecmp( $out, 'x' ) == 0 ) {
			# Double Xs only if they follow cxapelutaj literoj.
			return preg_replace_callback(
				'/((?:[cghjsu]|\xc4[\x88\x89\x9c\x9d\xa4\xa5\xb4\xb5]|\xc5[\x9c\x9d\xac\xad])x*)/i',
				array( $this, 'strrtuxCallback' ), $string );
		}
		return parent::iconv( $in, $out, $string );
	}

	/**
	 * @param $matches array
	 * @return string
	 */
	function strrtuxCallback( $matches ) {
		static $ux = array (
			'x' => 'xx' , 'X' => 'Xx' ,
			"\xc4\x88" => "Cx" , "\xc4\x89" => "cx" ,
			"\xc4\x9c" => "Gx" , "\xc4\x9d" => "gx" ,
			"\xc4\xa4" => "Hx" , "\xc4\xa5" => "hx" ,
			"\xc4\xb4" => "Jx" , "\xc4\xb5" => "jx" ,
			"\xc5\x9c" => "Sx" , "\xc5\x9d" => "sx" ,
			"\xc5\xac" => "Ux" , "\xc5\xad" => "ux"
		);
		return strtr( $matches[1], $ux );
	}

	/**
	 * @param $matches array
	 * @return string
	 */
	function strrtxuCallback( $matches ) {
		static $xu = array (
			'xx' => 'x' , 'xX' => 'x' ,
			'Xx' => 'X' , 'XX' => 'X' ,
			"Cx" => "\xc4\x88" , "CX" => "\xc4\x88" ,
			"cx" => "\xc4\x89" , "cX" => "\xc4\x89" ,
			"Gx" => "\xc4\x9c" , "GX" => "\xc4\x9c" ,
			"gx" => "\xc4\x9d" , "gX" => "\xc4\x9d" ,
			"Hx" => "\xc4\xa4" , "HX" => "\xc4\xa4" ,
			"hx" => "\xc4\xa5" , "hX" => "\xc4\xa5" ,
			"Jx" => "\xc4\xb4" , "JX" => "\xc4\xb4" ,
			"jx" => "\xc4\xb5" , "jX" => "\xc4\xb5" ,
			"Sx" => "\xc5\x9c" , "SX" => "\xc5\x9c" ,
			"sx" => "\xc5\x9d" , "sX" => "\xc5\x9d" ,
			"Ux" => "\xc5\xac" , "UX" => "\xc5\xac" ,
			"ux" => "\xc5\xad" , "uX" => "\xc5\xad"
		);
		return strtr( $matches[1], $xu ) . strtr( $matches[2], $xu );
	}

	/**
	 * @param $s string
	 * @return string
	 */
	function checkTitleEncoding( $s ) {
		# Check for X-system backwards-compatibility URLs
		$ishigh = preg_match( '/[\x80-\xff]/', $s );
		$isutf = preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
			'[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})+$/', $s );

		if ( $ishigh and !$isutf ) {
			# Assume Latin1
			$s = utf8_encode( $s );
		} else {
			if ( preg_match( '/(\xc4[\x88\x89\x9c\x9d\xa4\xa5\xb4\xb5]' .
				'|\xc5[\x9c\x9d\xac\xad])/', $s ) )
			return $s;
		}

		// if( preg_match( '/[cghjsu]x/i', $s ) )
		//	return $this->iconv( 'x', 'utf-8', $s );
		return $s;
	}

	function initEncoding() {
		global $wgEditEncoding;
		$wgEditEncoding = 'x';
	}
}
