<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Language\LanguageConverter;
use Wikimedia\ReplacementArray;

/**
 * Converts Serbo-Croatian from Latin script to Cyrillic script
 *
 * @ingroup Languages
 */
class ShConverter extends LanguageConverter {

	private const TO_CYRILLIC = [
		'konjug' => 'конјуг', // T385768
		'konjun' => 'конјун', // T385768
		'Konjug' => 'Конјуг', // T385768
		'Konjun' => 'Конјун', // T385768
		'wiki' => 'вики', // T385826
		'Wiki' => 'Вики', // T385826

		'dž' => 'џ',
		'lj' => 'љ',
		'nj' => 'њ',
		'Dž' => 'Џ',
		'DŽ' => 'Џ',
		'Lj' => 'Љ',
		'LJ' => 'Љ',
		'Nj' => 'Њ',
		'NЈ' => 'Њ',

		'a' => 'а',
		'b' => 'б',
		'c' => 'ц',
		'č' => 'ч',
		'ć' => 'ћ',
		'd' => 'д',
		'đ' => 'ђ',
		'e' => 'е',
		'f' => 'ф',
		'g' => 'г',
		'h' => 'х',
		'i' => 'и',
		'j' => 'ј',
		'k' => 'к',
		'l' => 'л',
		'm' => 'м',
		'n' => 'н',
		'o' => 'о',
		'p' => 'п',
		'r' => 'р',
		's' => 'с',
		'š' => 'ш',
		't' => 'т',
		'u' => 'у',
		'v' => 'в',
		'z' => 'з',
		'ž' => 'ж',

		'A' => 'А',
		'B' => 'Б',
		'C' => 'Ц',
		'Č' => 'Ч',
		'Ć' => 'Ћ',
		'D' => 'Д',
		'Đ' => 'Ђ',
		'E' => 'Е',
		'F' => 'Ф',
		'G' => 'Г',
		'H' => 'Х',
		'I' => 'И',
		'J' => 'Ј',
		'K' => 'К',
		'L' => 'Л',
		'M' => 'М',
		'N' => 'Н',
		'O' => 'О',
		'P' => 'П',
		'R' => 'Р',
		'S' => 'С',
		'Š' => 'Ш',
		'T' => 'Т',
		'U' => 'У',
		'V' => 'В',
		'Z' => 'З',
		'Ž' => 'Ж',
	];

	public function getMainCode(): string {
		return 'sh';
	}

	public function getLanguageVariants(): array {
		return [ 'sh-latn', 'sh-cyrl' ];
	}

	public function getVariantsFallbacks(): array {
		return [
			'sh-cyrl' => 'sh-latn',
		];
	}

	protected function loadDefaultTables(): array {
		return [
			'sh-cyrl' => new ReplacementArray( self::TO_CYRILLIC ),
			'sh-latn' => new ReplacementArray(),
		];
	}

	/**
	 * Omits roman numbers
	 *
	 * @inheritDoc
	 */
	public function translate( $text, $variant ) {
		return $this->translateWithoutRomanNumbers( $text, $variant );
	}
}
