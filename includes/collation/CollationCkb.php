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
 * @file
 */

/**
 * Workaround for the lack of support of Sorani Kurdish / Central Kurdish language ('ckb') in ICU.
 *
 * Uses the same collation rules as Persian / Farsi ('fa'), but different characters for digits.
 *
 * @since 1.23
 */
class CollationCkb extends IcuCollation {
	public function __construct() {
		// This will set $locale and collators, which affect the actual sorting order
		parent::__construct( 'fa' );
		// Override the 'fa' language set by parent constructor, which affects #getFirstLetterData()
		$this->digitTransformLanguage = Language::factory( 'ckb' );
	}
}
