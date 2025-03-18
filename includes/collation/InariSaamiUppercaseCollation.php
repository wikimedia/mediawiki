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
