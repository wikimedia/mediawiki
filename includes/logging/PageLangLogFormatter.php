<?php
/**
 * Formatter for changelang log entries.
 *
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
 * @author Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @since 1.22
 */

/**
 * This class formats language change log entries.
 *
 * @since 1.24
 */
class PageLangLogFormatter extends LogFormatter {
	protected function getMessageParameters() {
		global $wgLang;
		$params = parent::getMessageParameters();

		// Convert language codes to names in user language
		$oldLang = $params[3];
		$newLang = $params[4];
		$params[3] = Language::fetchLanguageName( $oldLang, $wgLang->getCode() )
			. ' (' . $oldLang . ')';
		$params[4] = Language::fetchLanguageName( $newLang, $wgLang->getCode() )
			. ' (' . $newLang . ')';
		return $params;
	}
}