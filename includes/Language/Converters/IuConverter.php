<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use Wikimedia\ReplacementArray;

/**
 * Inuktitut specific code.
 *
 * Conversion script between Latin and Syllabics for Inuktitut.
 * - Syllabics -> lowercase Latin
 * - lowercase/uppercase Latin -> Syllabics
 *
 *
 * Based on:
 *   - https://commons.wikimedia.org/wiki/Image:Inuktitut.png
 *   - LanguageSr.php
 *
 * @ingroup Languages
 */
class IuConverter extends LanguageConverterSpecific {
	private const TO_LATIN = [
		'ᐦ' => 'h', 'ᐃ' => 'i', 'ᐄ' => 'ii', 'ᐅ' => 'u', 'ᐆ' => 'uu', 'ᐊ' => 'a', 'ᐋ' => 'aa',
		'ᑉ' => 'p', 'ᐱ' => 'pi', 'ᐲ' => 'pii', 'ᐳ' => 'pu', 'ᐴ' => 'puu', 'ᐸ' => 'pa', 'ᐹ' => 'paa',
		'ᑦ' => 't', 'ᑎ' => 'ti', 'ᑏ' => 'tii', 'ᑐ' => 'tu', 'ᑑ' => 'tuu', 'ᑕ' => 'ta', 'ᑖ' => 'taa',
		'ᒃ' => 'k', 'ᑭ' => 'ki', 'ᑮ' => 'kii', 'ᑯ' => 'ku', 'ᑰ' => 'kuu', 'ᑲ' => 'ka', 'ᑳ' => 'kaa',
		'ᖅᒃ' => 'qq', 'ᖅᑭ' => 'qqi', 'ᖅᑮ' => 'qqii', 'ᖅᑯ' => 'qqu', 'ᖅᑰ' => 'ᖅqquu', 'ᖅᑲ' => 'qqa',
		'ᖅᑳ' => 'qqaa', 'ᒡ' => 'g', 'ᒋ' => 'gi', 'ᒌ' => 'gii', 'ᒍ' => 'gu', 'ᒎ' => 'guu',
		'ᒐ' => 'ga', 'ᒑ' => 'gaa', 'ᒻ' => 'm', 'ᒥ' => 'mi', 'ᒦ' => 'mii', 'ᒧ' => 'mu', 'ᒨ' => 'muu',
		'ᒪ' => 'ma', 'ᒫ' => 'maa', 'ᓐ' => 'n', 'ᓂ' => 'ni', 'ᓃ' => 'nii', 'ᓄ' => 'nu', 'ᓅ' => 'nuu',
		'ᓇ' => 'na', 'ᓈ' => 'naa', 'ᔅ' => 's', 'ᓯ' => 'si', 'ᓰ' => 'sii', 'ᓱ' => 'su', 'ᓲ' => 'suu',
		'ᓴ' => 'sa', 'ᓵ' => 'saa', 'ᓪ' => 'l', 'ᓕ' => 'li', 'ᓖ' => 'lii', 'ᓗ' => 'lu', 'ᓘ' => 'luu',
		'ᓚ' => 'la', 'ᓛ' => 'laa', 'ᔾ' => 'j', 'ᔨ' => 'ji', 'ᔩ' => 'jii', 'ᔪ' => 'ju', 'ᔫ' => 'juu',
		'ᔭ' => 'ja', 'ᔮ' => 'jaa', 'ᕝ' => 'v', 'ᕕ' => 'vi', 'ᕖ' => 'vii', 'ᕗ' => 'vu', 'ᕘ' => 'vuu',
		'ᕙ' => 'va', 'ᕚ' => 'vaa', 'ᕐ' => 'r', 'ᕆ' => 'ri', 'ᕇ' => 'rii', 'ᕈ' => 'ru', 'ᕉ' => 'ruu',
		'ᕋ' => 'ra', 'ᕌ' => 'raa', 'ᖅ' => 'q', 'ᕿ' => 'qi', 'ᖀ' => 'qii', 'ᖁ' => 'qu', 'ᖂ' => 'quu',
		'ᖃ' => 'qa', 'ᖄ' => 'qaa', 'ᖕ' => 'ng', 'ᖏ' => 'ngi', 'ᖐ' => 'ngii', 'ᖑ' => 'ngu',
		'ᖒ' => 'nguu', 'ᖓ' => 'nga', 'ᖔ' => 'ngaa', 'ᖖ' => 'nng', 'ᙱ' => 'nngi', 'ᙲ' => 'nngii',
		'ᙳ' => 'nngu', 'ᙴ' => 'nnguu', 'ᙵ' => 'nnga', 'ᙶ' => 'nngaa', 'ᖦ' => 'ɫ', 'ᖠ' => 'ɫi',
		'ᖡ' => 'ɫii', 'ᖢ' => 'ɫu', 'ᖣ' => 'ɫuu', 'ᖤ' => 'ɫa', 'ᖥ' => 'ɫaa',
	];

	private const UPPER_TO_LOWER_CASE_LATIN = [
		'A' => 'a', 'B' => 'b', 'C' => 'c', 'D' => 'd', 'E' => 'e',
		'F' => 'f', 'G' => 'g', 'H' => 'h', 'I' => 'i', 'J' => 'j',
		'K' => 'k', 'L' => 'l', 'M' => 'm', 'N' => 'n', 'O' => 'o',
		'P' => 'p', 'Q' => 'q', 'R' => 'r', 'S' => 's', 'T' => 't',
		'U' => 'u', 'V' => 'v', 'W' => 'w', 'X' => 'x', 'Y' => 'y',
		'Z' => 'z',
	];

	private const TO_SYLLABICS = [
		'h' => 'ᐦ', 'i' => 'ᐃ', 'ii' => 'ᐄ', 'u' => 'ᐅ', 'uu' => 'ᐆ', 'a' => 'ᐊ', 'aa' => 'ᐋ',
		'p' => 'ᑉ', 'pi' => 'ᐱ', 'pii' => 'ᐲ', 'pu' => 'ᐳ', 'puu' => 'ᐴ', 'pa' => 'ᐸ', 'paa' => 'ᐹ',
		't' => 'ᑦ', 'ti' => 'ᑎ', 'tii' => 'ᑏ', 'tu' => 'ᑐ', 'tuu' => 'ᑑ', 'ta' => 'ᑕ', 'taa' => 'ᑖ',
		'k' => 'ᒃ', 'ki' => 'ᑭ', 'kii' => 'ᑮ', 'ku' => 'ᑯ', 'kuu' => 'ᑰ', 'ka' => 'ᑲ', 'kaa' => 'ᑳ',
		'g' => 'ᒡ', 'gi' => 'ᒋ', 'gii' => 'ᒌ', 'gu' => 'ᒍ', 'guu' => 'ᒎ', 'ga' => 'ᒐ', 'gaa' => 'ᒑ',
		'm' => 'ᒻ', 'mi' => 'ᒥ', 'mii' => 'ᒦ', 'mu' => 'ᒧ', 'muu' => 'ᒨ', 'ma' => 'ᒪ', 'maa' => 'ᒫ',
		'n' => 'ᓐ', 'ni' => 'ᓂ', 'nii' => 'ᓃ', 'nu' => 'ᓄ', 'nuu' => 'ᓅ', 'na' => 'ᓇ', 'naa' => 'ᓈ',
		's' => 'ᔅ', 'si' => 'ᓯ', 'sii' => 'ᓰ', 'su' => 'ᓱ', 'suu' => 'ᓲ', 'sa' => 'ᓴ', 'saa' => 'ᓵ',
		'l' => 'ᓪ', 'li' => 'ᓕ', 'lii' => 'ᓖ', 'lu' => 'ᓗ', 'luu' => 'ᓘ', 'la' => 'ᓚ', 'laa' => 'ᓛ',
		'j' => 'ᔾ', 'ji' => 'ᔨ', 'jii' => 'ᔩ', 'ju' => 'ᔪ', 'juu' => 'ᔫ', 'ja' => 'ᔭ', 'jaa' => 'ᔮ',
		'v' => 'ᕝ', 'vi' => 'ᕕ', 'vii' => 'ᕖ', 'vu' => 'ᕗ', 'vuu' => 'ᕘ', 'va' => 'ᕙ', 'vaa' => 'ᕚ',
		'r' => 'ᕐ', 'ri' => 'ᕆ', 'rii' => 'ᕇ', 'ru' => 'ᕈ', 'ruu' => 'ᕉ', 'ra' => 'ᕋ', 'raa' => 'ᕌ',
		'qq' => 'ᖅᒃ', 'qqi' => 'ᖅᑭ', 'qqii' => 'ᖅᑮ', 'qqu' => 'ᖅᑯ', 'qquu' => 'ᖅᑰ', 'qqa' => 'ᖅᑲ',
		'qqaa' => 'ᖅᑳ', 'q' => 'ᖅ', 'qi' => 'ᕿ', 'qii' => 'ᖀ', 'qu' => 'ᖁ', 'quu' => 'ᖂ',
		'qa' => 'ᖃ', 'qaa' => 'ᖄ', 'ng' => 'ᖕ', 'ngi' => 'ᖏ', 'ngii' => 'ᖐ', 'ngu' => 'ᖑ',
		'nguu' => 'ᖒ', 'nga' => 'ᖓ', 'ngaa' => 'ᖔ', 'nng' => 'ᖖ', 'nngi' => 'ᙱ', 'nngii' => 'ᙲ',
		'nngu' => 'ᙳ', 'nnguu' => 'ᙴ', 'nnga' => 'ᙵ', 'nngaa' => 'ᙶ', 'ɫ' => 'ᖦ', 'ɫi' => 'ᖠ',
		'ɫii' => 'ᖡ', 'ɫu' => 'ᖢ', 'ɫuu' => 'ᖣ', 'ɫa' => 'ᖤ', 'ɫaa' => 'ᖥ',
	];

	public function getMainCode(): string {
		return 'iu';
	}

	public function getLanguageVariants(): array {
		return [ 'iu', 'ike-cans', 'ike-latn' ];
	}

	public function getVariantsFallbacks(): array {
		return [
			'iu' => 'ike-cans',
			'ike-cans' => 'iu',
			'ike-latn' => 'iu',
		];
	}

	protected function loadDefaultTables(): array {
		return [
			'lowercase' => new ReplacementArray( self::UPPER_TO_LOWER_CASE_LATIN ),
			'ike-cans' => new ReplacementArray( self::TO_SYLLABICS ),
			'ike-latn' => new ReplacementArray( self::TO_LATIN ),
			'iu' => new ReplacementArray()
		];
	}

	/** @inheritDoc */
	public function translate( $text, $toVariant ) {
		// If $text is empty or only includes spaces, do nothing
		// Otherwise translate it
		if ( trim( $text ) ) {
			$this->loadTables();
			// To syllabics, first translate uppercase to lowercase Latin
			if ( $toVariant == 'ike-cans' ) {
				$text = $this->mTables['lowercase']->replace( $text );
			}
			$text = $this->mTables[$toVariant]->replace( $text );
		}
		return $text;
	}
}
