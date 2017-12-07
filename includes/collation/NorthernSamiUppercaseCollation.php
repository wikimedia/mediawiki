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
 * @since 1.31
 *
 * @file
 */

/**
 * Temporary workaround for incorrect collation of Northern Sami
 * language ('se') in Wikimedia servers (see bug T181503).
 *
 * When the ICU's 'se' collation has been included in PHP-intl and Wikimedia
 * servers updated to that new version of PHP, this file should be deleted
 * and the collation for 'se' set to 'uca-se'.
 *
 * @since 1.31
 */

class NorthernSamiUppercaseCollation extends CustomUppercaseCollation {

	public function __construct() {
		parent::__construct( [
			'A',
			'Á',
			'B',
			'C',
			'Č',
			'Ʒ', // Not part of modern alphabet, but part of ICU
			'Ǯ', // Not part of modern alphabet, but part of ICU
			'D',
			'Đ',
			'E',
			'F',
			'G',
			'Ǧ', // Not part of modern alphabet, but part of ICU
			'Ǥ', // Not part of modern alphabet, but part of ICU
			'H',
			'I',
			'J',
			'K',
			'Ǩ', // Not part of modern alphabet, but part of ICU
			'L',
			'M',
			'N',
			'Ŋ',
			'O',
			'P',
			'Q',
			'R',
			'S',
			'Š',
			'T',
			'Ŧ',
			'U',
			'V',
			'W',
			'X',
			'Y',
			'Z',
			'Ž',
			'Ø', // Not part of native alphabet, but part of ICU
			'Æ', // Not part of native alphabet, but part of ICU
			'Å', // Not part of native alphabet, but part of ICU
			'Ä', // Not part of native alphabet, but part of ICU
			'Ö', // Not part of native alphabet, but part of ICU
		], Language::factory( 'se' ) );
	}
}
