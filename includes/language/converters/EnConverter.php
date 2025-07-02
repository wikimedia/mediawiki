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
 */

use MediaWiki\Language\LanguageConverter;
use Wikimedia\ReplacementArray;

/**
 * English specific converter routines.
 *
 * @ingroup Languages
 */
class EnConverter extends LanguageConverter {

	public function getMainCode(): string {
		return 'en';
	}

	public function getLanguageVariants(): array {
		return [ 'en', 'en-x-piglatin' ];
	}

	public function getVariantsFallbacks(): array {
		return [];
	}

	protected function loadDefaultTables(): array {
		return [
			'en' => new ReplacementArray(),
			'en-x-piglatin' => new ReplacementArray(),
		];
	}

	/**
	 * Translates text into Pig Latin. This allows developers to test the language variants
	 * functionality and user interface without having to switch wiki language away from default.
	 * This method also processes custom conversion rules to allow testing these parts of the
	 * language converter as well.
	 *
	 * @param string $text
	 * @param string $toVariant
	 * @return string
	 */
	public function translate( $text, $toVariant ) {
		if ( $toVariant !== 'en-x-piglatin' ) {
			return $text;
		}
		// Apply any custom rules.  (Primarily for testing.)
		$this->loadTables();
		$customRules = $this->mTables[$toVariant];

		if ( !$customRules->getArray() ) {
			return self::pigLatin( $text );
		}

		// Split on the matches from custom rules, so that we only apply
		// the Pig Latin transformation on output. which is not from a
		// custom rule; this avoids double-conversion.
		// (See SrConverter for similar split-and-process code.)
		$re = '(' .
			implode(
				'|',
				array_map(
					'preg_quote',
					// "Original" texts from the ReplacementArray rules
					array_keys( $customRules->getArray() )
				)
			) . ')';

		$matches = preg_split( $re, $text, -1, PREG_SPLIT_OFFSET_CAPTURE );
		$m = array_shift( $matches );

		// Apply Pig Latin transformation to the initial "non-matching" section.
		$ret = self::pigLatin( $m[0] );
		$mstart = (int)$m[1] + strlen( $m[0] );
		foreach ( $matches as $m ) {
			// Use the ReplacementArray rules to transform any matching sections
			$ret .= $customRules->replace(
				substr( $text, $mstart, (int)$m[1] - $mstart )
			);
			// And Pig Latin transformation on the non-matching sections.
			$ret .= self::pigLatin( $m[0] );
			$mstart = (int)$m[1] + strlen( $m[0] );
		}
		// $mstart will always equal strlen($text) here.

		return $ret;
	}

	/**
	 * Translates words into Pig Latin.
	 *
	 * @param string $text
	 * @return string
	 */
	private static function pigLatin( string $text ): string {
		// Only process words composed of standard English alphabet, leave the rest unchanged.
		// This skips some English words like 'naïve' or 'résumé', but we can live with that.
		// Ignore single letters and words which aren't lowercase or uppercase-first.
		return preg_replace_callback( '/[A-Za-z][a-z\']+/', static function ( $matches ) {
			$word = $matches[0];
			if ( preg_match( '/^[aeiou]/i', $word ) ) {
				return $word . 'way';
			}

			return preg_replace_callback( '/^(s?qu|[^aeiou][^aeiouy]*)(.*)$/i', static function ( $m ) {
				$ucfirst = strtoupper( $m[1][0] ) === $m[1][0];
				if ( $ucfirst ) {
					return ucfirst( $m[2] ) . lcfirst( $m[1] ) . 'ay';
				}

				return $m[2] . $m[1] . 'ay';
			}, $word );
		}, $text );
	}
}
