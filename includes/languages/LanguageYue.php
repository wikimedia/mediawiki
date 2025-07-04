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

use MediaWiki\Language\Language;

/**
 * Cantonese (粵語)
 *
 * @ingroup Languages
 */
class LanguageYue extends Language {

	private const WORD_SEGMENTATION_REGEX = '/([\xc0-\xff][\x80-\xbf]*)/';

	/** @inheritDoc */
	public function hasWordBreaks() {
		return false;
	}

	/**
	 * Eventually, this should be a word segmentation;
	 * but for now just treat each character as a word.
	 * @todo FIXME: Only do this for Han characters...
	 *
	 * @param string $string
	 * @return string
	 */
	public function segmentByWord( $string ) {
		return self::insertSpace( $string, self::WORD_SEGMENTATION_REGEX );
	}
}
