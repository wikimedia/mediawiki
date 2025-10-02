<?php
/**
 * @license GPL-2.0-or-later
 * @since 1.31
 *
 * @file
 */

use MediaWiki\Languages\LanguageFactory;

class AbkhazUppercaseCollation extends CustomUppercaseCollation {

	public function __construct( LanguageFactory $languageFactory ) {
		parent::__construct(
			$languageFactory,
			[
				'А',
				'Б',
				'В',
				'Г',
				'Гь',
				'Гә',
				'Ӷ',
				'Ҕ',
				'Ҕь',
				'Ҕә',
				'Д',
				'Дә',
				'Е',
				'Ж',
				'Жь',
				'Жә',
				'З',
				'Ӡ',
				'Ӡә',
				'И',
				'К',
				'Кь',
				'Кә',
				'Қ',
				'Қь',
				'Қә',
				'Ҟ',
				'Ҟь',
				'Ҟә',
				'Л',
				'М',
				'Н',
				'О',
				'П',
				'Ԥ',
				'Ҧ',
				'Р',
				'С',
				'Т',
				'Тә',
				'Ҭ',
				'Ҭә',
				'У',
				'Ф',
				'Х',
				'Хь',
				'Хә',
				'Ҳ',
				'Ҳә',
				'Ц',
				'Цә',
				'Ҵ',
				'Ҵә',
				'Ч',
				'Ҷ',
				'Ҽ',
				'Ҿ',
				'Ш',
				'Шь',
				'Шә',
				'Ы',
				'Ҩ',
				'Џ',
				'Џь',
				'ь',
				'ә',
			],
			'ab'
		);
	}
}
