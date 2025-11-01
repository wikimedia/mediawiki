<?php
/**
 * @license GPL-2.0-or-later
 * @since 1.30
 *
 * @file
 */

use MediaWiki\Languages\LanguageFactory;

class BashkirUppercaseCollation extends CustomUppercaseCollation {

	public function __construct( LanguageFactory $languageFactory ) {
		parent::__construct(
			$languageFactory,
			[
				'А',
				'Б',
				'В',
				'Г',
				'Ғ',
				'Д',
				'Ҙ',
				'Е',
				'Ё',
				'Ж',
				'З',
				'И',
				'Й',
				'К',
				'Ҡ',
				'Л',
				'М',
				'Н',
				'Ң',
				'О',
				'Ө',
				'П',
				'Р',
				'С',
				'Ҫ',
				'Т',
				'У',
				'Ү',
				'Ф',
				'Х',
				'Һ',
				'Ц',
				'Ч',
				'Ш',
				'Щ',
				'Ъ',
				'Ы',
				'Ь',
				'Э',
				'Ә',
				'Ю',
				'Я',
			],
			'ba'
		);
	}
}
