<?php
/**
 * @license GPL-2.0-or-later
 * @since 1.44
 *
 * @file
 */

use MediaWiki\Languages\LanguageFactory;

/**
 * Custom collation for ckb.
 *
 * Based on feedback at T310051
 */
class CentralKurdishUppercaseCollation extends CustomUppercaseCollation {

	public function __construct( LanguageFactory $languageFactory ) {
		parent::__construct(
			$languageFactory,
			[
				'ئ',
				'ا',
				'ب',
				'پ',
				'ت',
				'ج',
				'چ',
				'ح',
				'خ',
				'د',
				'ر',
				'ڕ',
				'ز',
				'ژ',
				'س',
				'ش',
				'ع',
				'غ',
				'ف',
				'ڤ',
				'ق',
				[
					'ک',
					'ك',
				],
				'گ',
				'ل',
				'ڵ',
				'م',
				'ن',
				[
					'ھ',
					'ه',
				],
				'ە',
				'و',
				'ۆ',
				'ی',
				'ێ'
			],
			'ckb'
		);
	}
}
