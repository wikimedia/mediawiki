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
 * @ingroup Language
 */

/**
 * Methods for dealing with language codes.
 * @todo Move some of the code-related static methods out of Language into this class
 *
 * @since 1.29
 * @ingroup Language
 */
class LanguageCode {
	/**
	 * Mapping from MediaWiki internal language codes to BCP 47 conform language codes.
	 *
	 * @since 1.30
	 */
	private static $bcp47Mapping = [
		'de-formal' => 'de',
		'en-rtl' => 'en',
		'es-formal' => 'es',
		'hu-formal' => 'hu',
		'nl-informal' => 'nl',
	];

	/**
	 * Get the normalised IETF language tag
	 * See unit test for examples.
	 *
	 * @param string $code The language code.
	 * @return string The language code which complying with BCP 47 standards.
	 *
	 * @since 1.30
	 */
	public static function bcp47( $code ) {
		// Replace some special internal used language codes to BCP 47 conform language codes.
		// T106367
		if ( isset( self::$bcp47Mapping[$code] ) ) {
			return self::$bcp47Mapping[$code];
		}

		$codeSegment = explode( '-', $code );
		$codeBCP = [];
		foreach ( $codeSegment as $segNo => $seg ) {
			// when previous segment is x, it is a private segment and should be lc
			if ( $segNo > 0 && strtolower( $codeSegment[( $segNo - 1 )] ) == 'x' ) {
				$codeBCP[$segNo] = strtolower( $seg );
			// ISO 3166 country code
			} elseif ( ( strlen( $seg ) == 2 ) && ( $segNo > 0 ) ) {
				$codeBCP[$segNo] = strtoupper( $seg );
			// ISO 15924 script code
			} elseif ( ( strlen( $seg ) == 4 ) && ( $segNo > 0 ) ) {
				$codeBCP[$segNo] = ucfirst( strtolower( $seg ) );
			// Use lowercase for other cases
			} else {
				$codeBCP[$segNo] = strtolower( $seg );
			}
		}
		$langCode = implode( '-', $codeBCP );
		return $langCode;
	}

	/**
	 * Returns a mapping of deprecated language codes that were used in previous
	 * versions of MediaWiki to up-to-date, current language codes.
	 *
	 * This array is merged into $wgDummyLanguageCodes in Setup.php, along with
	 * the fake language codes 'qqq' and 'qqx', which are used internally by
	 * MediaWiki's localisation system.
	 *
	 * @return string[]
	 *
	 * @since 1.29
	 */
	public static function getDeprecatedCodeMapping() {
		return [
			// Note that als is actually a valid ISO 639 code (Tosk Albanian), but it
			// was previously used in MediaWiki for Alsatian, which comes under gsw
			'als' => 'gsw',
			'bat-smg' => 'sgs',
			'be-x-old' => 'be-tarask',
			'fiu-vro' => 'vro',
			'roa-rup' => 'rup',
			'zh-classical' => 'lzh',
			'zh-min-nan' => 'nan',
			'zh-yue' => 'yue',
		];
	}
}
