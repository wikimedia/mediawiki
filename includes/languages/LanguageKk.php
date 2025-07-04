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
 * Kazakh (Қазақша) specific code.
 *
 * This handles Cyrillic, Latin and Arabic scripts for Kazakh.
 * Right now, we distinguish `kk_cyrl`, `kk_latn`, `kk_arab`, `kk_kz`, `kk_tr`,
 * and `kk_cn`.
 *
 * @ingroup Languages
 */
class LanguageKk extends LanguageKk_cyrl {
	/** @inheritDoc */
	public function convertGrammar( $word, $case ) {
		// T277689: If there's no word, then there's nothing to convert.
		if ( $word === '' ) {
			return '';
		}
		return parent::convertGrammarKk_cyrl( $word, $case );
	}
}
