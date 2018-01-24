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

class AbkhazUppercaseCollation extends CustomUppercaseCollation {

	public function __construct() {
		parent::__construct( [
			'А',
			'Б',
			'В',
			'Г',
			'Гь',
			'Гә',
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
		], Language::factory( 'ab' ) );
	}
}
