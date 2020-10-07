<?php
/**
 * Shilha specific code.
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
 * @ingroup Language
 */

/**
 * Conversion script between Latin and Tifinagh for Tachelhit.
 * - Tifinagh -> lowercase Latin
 * - lowercase/uppercase Latin -> Tifinagh
 *
 *
 * Based on:
 *   - https://en.wikipedia.org/wiki/Shilha_language
 *   - LanguageSr.php
 *
 * @ingroup Language
 */
class ShiConverter extends LanguageConverterSpecific {

	protected $mDoContentConvert;

	public $mToLatin = [
		'ⴰ' => 'a', 'ⴱ' => 'b', 'ⴳ' => 'g', 'ⴷ' => 'd', 'ⴹ' => 'ḍ', 'ⴻ' => 'e',
		'ⴼ' => 'f', 'ⴽ' => 'k', 'ⵀ' => 'h', 'ⵃ' => 'ḥ', 'ⵄ' => 'ε', 'ⵅ' => 'x',
		'ⵇ' => 'q', 'ⵉ' => 'i', 'ⵊ' => 'j', 'ⵍ' => 'l', 'ⵎ' => 'm', 'ⵏ' => 'n',
		'ⵓ' => 'u', 'ⵔ' => 'r', 'ⵕ' => 'ṛ', 'ⵙ' => 's', 'ⵚ' => 'ṣ',
		'ⵛ' => 'š', 'ⵜ' => 't', 'ⵟ' => 'ṭ', 'ⵡ' => 'w', 'ⵢ' => 'y', 'ⵣ' => 'z',
		'ⵥ' => 'ẓ', 'ⵯ' => 'ʷ', 'ⵖ' => 'ɣ', 'ⵠ' => 'v', 'ⵒ' => 'p',
	];

	public $mUpperToLowerCaseLatin = [
		'A' => 'a', 'B' => 'b', 'C' => 'c', 'D' => 'd', 'E' => 'e',
		'F' => 'f', 'G' => 'g', 'H' => 'h', 'I' => 'i', 'J' => 'j',
		'K' => 'k', 'L' => 'l', 'M' => 'm', 'N' => 'n', 'O' => 'o',
		'P' => 'p', 'Q' => 'q', 'R' => 'r', 'S' => 's', 'T' => 't',
		'U' => 'u', 'V' => 'v', 'W' => 'w', 'X' => 'x', 'Y' => 'y',
		'Z' => 'z', 'Ɣ' => 'ɣ',
	];

	public $mToTifinagh = [
		'a' => 'ⴰ', 'b' => 'ⴱ', 'g' => 'ⴳ', 'd' => 'ⴷ', 'ḍ' => 'ⴹ', 'e' => 'ⴻ',
		'f' => 'ⴼ', 'k' => 'ⴽ', 'h' => 'ⵀ', 'ḥ' => 'ⵃ', 'ε' => 'ⵄ', 'x' => 'ⵅ',
		'q' => 'ⵇ', 'i' => 'ⵉ', 'j' => 'ⵊ', 'l' => 'ⵍ', 'm' => 'ⵎ', 'n' => 'ⵏ',
		'u' => 'ⵓ', 'r' => 'ⵔ', 'ṛ' => 'ⵕ', 'γ' => 'ⵖ', 's' => 'ⵙ', 'ṣ' => 'ⵚ',
		'š' => 'ⵛ', 't' => 'ⵜ', 'ṭ' => 'ⵟ', 'w' => 'ⵡ', 'y' => 'ⵢ', 'z' => 'ⵣ',
		'ẓ' => 'ⵥ', 'ʷ' => 'ⵯ', 'ɣ' => 'ⵖ', 'v' => 'ⵠ', 'p' => 'ⵒ',
	];

	/**
	 * @param Language $langobj
	 */
	public function __construct( $langobj ) {
		$variants = [ 'shi', 'shi-tfng', 'shi-latn' ];
		$variantfallbacks = [
			'shi' => 'shi-tfng',
			'shi-tfng' => 'shi',
			'shi-latn' => 'shi',
		];

		$flags = [];
		parent::__construct( $langobj, 'shi', $variants, $variantfallbacks, $flags );
	}

	protected function loadDefaultTables() {
		$this->mTables = [
			'lowercase' => new ReplacementArray( $this->mUpperToLowerCaseLatin ),
			'shi-tfng' => new ReplacementArray( $this->mToTifinagh ),
			'shi-latn' => new ReplacementArray( $this->mToLatin ),
			'shi' => new ReplacementArray()
		];
	}

	/**
	 * It translates text into variant
	 *
	 * @param string $text
	 * @param string $toVariant
	 *
	 * @return string
	 */
	public function translate( $text, $toVariant ) {
		// If $text is empty or only includes spaces, do nothing
		// Otherwise translate it
		if ( trim( $text ) ) {
			$this->loadTables();
			// To Tifinagh, first translate uppercase to lowercase Latin
			if ( $toVariant == 'shi-tfng' ) {
				$text = $this->mTables['lowercase']->replace( $text );
			}
			$text = $this->mTables[$toVariant]->replace( $text );
		}
		return $text;
	}
}
