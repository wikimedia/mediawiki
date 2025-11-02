<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use Wikimedia\ReplacementArray;

/**
 * Shilha specific code.
 *
 * Conversion script between Latin and Tifinagh for Tachelhit.
 * - Tifinagh -> lowercase Latin
 * - lowercase/uppercase Latin -> Tifinagh
 *
 *
 * Based on:
 *   - https://en.wikipedia.org/wiki/Shilha_language
 *   - LanguageSr.php
 *
 * @ingroup Languages
 */
class ShiConverter extends LanguageConverterSpecific {
	/**
	 * The Tifinagh alphabet sequence is based on
	 * "Dictionnaire Général de la Langue Amazighe Informatisé"
	 * by IRCAM (https://tal.ircam.ma/dglai/lexieam.php, DGLAi),
	 * with the labio-velarization mark in the end
	 */
	private const TO_LATIN = [
		'ⴰ' => 'a',
		'ⴱ' => 'b',
		'ⴳ' => 'g',
		'ⴷ' => 'd',
		'ⴹ' => 'ḍ',
		'ⴻ' => 'e',
		'ⴼ' => 'f',
		'ⴽ' => 'k',
		'ⵀ' => 'h',
		'ⵃ' => 'ḥ',
		'ⵄ' => 'ɛ',
		'ⵅ' => 'x',
		'ⵇ' => 'q',
		'ⵉ' => 'i',
		'ⵊ' => 'j',
		'ⵍ' => 'l',
		'ⵎ' => 'm',
		'ⵏ' => 'n',
		'ⵓ' => 'u',
		'ⵔ' => 'r',
		'ⵕ' => 'ṛ',
		'ⵖ' => 'ɣ',
		'ⵙ' => 's',
		'ⵚ' => 'ṣ',
		'ⵛ' => 'c',
		'ⵜ' => 't',
		'ⵟ' => 'ṭ',
		'ⵡ' => 'w',
		'ⵢ' => 'y',
		'ⵣ' => 'z',
		'ⵥ' => 'ẓ',
		'ⵯ' => 'ʷ',
	];

	/** The sequence is based on DGLAi, with the non-standard letters in the end */
	private const UPPER_TO_LOWER_CASE_LATIN = [
		'A' => 'a',
		'B' => 'b',
		'G' => 'g',
		'D' => 'd',
		'Ḍ' => 'ḍ',
		'E' => 'e',
		'F' => 'f',
		'K' => 'k',
		'H' => 'h',
		'Ḥ' => 'ḥ',
		'Ɛ' => 'ɛ',
		'X' => 'x',
		'Q' => 'q',
		'I' => 'i',
		'J' => 'j',
		'L' => 'l',
		'M' => 'm',
		'N' => 'n',
		'U' => 'u',
		'R' => 'r',
		'Ṛ' => 'ṛ',
		'Ɣ' => 'ɣ',
		'S' => 's',
		'Ṣ' => 'ṣ',
		'C' => 'c',
		'T' => 't',
		'Ṭ' => 'ṭ',
		'W' => 'w',
		'Y' => 'y',
		'Z' => 'z',
		'Ẓ' => 'ẓ',
		'O' => 'o',
		'P' => 'p',
		'V' => 'v',
	];

	/**
	 * The sequence is based on DGLAi, with the labio-velarization mark and
	 * the non-standard letters in the end
	 */
	private const TO_TIFINAGH = [
		'a' => 'ⴰ',
		'b' => 'ⴱ',
		'g' => 'ⴳ',
		'd' => 'ⴷ',
		'ḍ' => 'ⴹ',
		'e' => 'ⴻ',
		'f' => 'ⴼ',
		'k' => 'ⴽ',
		'h' => 'ⵀ',
		'ḥ' => 'ⵃ',
		'ɛ' => 'ⵄ',
		'x' => 'ⵅ',
		'q' => 'ⵇ',
		'i' => 'ⵉ',
		'j' => 'ⵊ',
		'l' => 'ⵍ',
		'm' => 'ⵎ',
		'n' => 'ⵏ',
		'u' => 'ⵓ',
		'r' => 'ⵔ',
		'ṛ' => 'ⵕ',
		'ɣ' => 'ⵖ',
		's' => 'ⵙ',
		'ṣ' => 'ⵚ',
		'c' => 'ⵛ',
		't' => 'ⵜ',
		'ṭ' => 'ⵟ',
		'w' => 'ⵡ',
		'y' => 'ⵢ',
		'z' => 'ⵣ',
		'ẓ' => 'ⵥ',
		'ʷ' => 'ⵯ',
		'o' => 'ⵓ',
		'p' => 'ⴱ',
		'v' => 'ⴼ',
	];

	public function getMainCode(): string {
		return 'shi';
	}

	public function getLanguageVariants(): array {
		return [ 'shi', 'shi-tfng', 'shi-latn' ];
	}

	public function getVariantsFallbacks(): array {
		return [
			'shi' => [ 'shi-latn', 'shi-tfng' ],
			'shi-tfng' => 'shi',
			'shi-latn' => 'shi',
		];
	}

	protected function loadDefaultTables(): array {
		return [
			'lowercase' => new ReplacementArray( self::UPPER_TO_LOWER_CASE_LATIN ),
			'shi-tfng' => new ReplacementArray( self::TO_TIFINAGH ),
			'shi-latn' => new ReplacementArray( self::TO_LATIN ),
			'shi' => new ReplacementArray()
		];
	}

	/** @inheritDoc */
	public function translate( $text, $toVariant ) {
		// If $text is empty or only includes spaces, do nothing
		// Otherwise translate it
		if ( trim( $text ) ) {
			$this->loadTables();
			// For Tifinagh, first translate uppercase to lowercase Latin
			if ( $toVariant == 'shi-tfng' ) {
				$text = $this->mTables['lowercase']->replace( $text );
			}
			$text = $this->mTables[$toVariant]->replace( $text );
		}
		return $text;
	}
}
