<?php
/**
 * @license GPL-2.0-or-later
 * @since 1.44
 *
 * @file
 */

namespace MediaWiki\Collation;

use MediaWiki\Languages\LanguageFactory;

class InariSaamiUppercaseCollation extends CustomUppercaseCollation {

	public function __construct( LanguageFactory $languageFactory ) {
		parent::__construct(
			$languageFactory,
			[
				'A',
				'Á',
				'Ä',
				'Â',
				'Å',
				'Æ',
				'B',
				'C',
				'Č',
				'D',
				'Đ',
				'E',
				'F',
				'G',
				'H',
				'I',
				'J',
				'K',
				'L',
				'M',
				'N',
				'Ŋ',
				'O',
				'Ö',
				'Ø',
				'P',
				'Q',
				'R',
				'S',
				'Š',
				'T',
				'U',
				'V',
				'W',
				'X',
				'Y',
				'Z',
				'Ž',
			],
			'smn'
		);
	}
}

/** @deprecated class alias since 1.46 */
class_alias( InariSaamiUppercaseCollation::class, 'InariSaamiUppercaseCollation' );
