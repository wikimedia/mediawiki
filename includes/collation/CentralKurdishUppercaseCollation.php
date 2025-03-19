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
