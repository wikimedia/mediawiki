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
	 * Mapping of deprecated language codes that were used in previous
	 * versions of MediaWiki to up-to-date, current language codes.
	 *
	 * @var array Mapping from language code to language code
	 *
	 * @since 1.30
	 */
	private static $deprecatedLanguageCodeMapping = [
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
		return self::$deprecatedLanguageCodeMapping;
	}

	/**
	 * Replace deprecated language codes that were used in previous
	 * versions of MediaWiki to up-to-date, current language codes.
	 * Other values will returned unchanged.
	 *
	 * @param string $code Old language code
	 * @return string New language code
	 *
	 * @since 1.30
	 */
	public static function replaceDeprecatedCodes( $code ) {
		if ( isset( self::$deprecatedLanguageCodeMapping[$code] ) ) {
			return self::$deprecatedLanguageCodeMapping[$code];
		}
		return $code;
	}
}
