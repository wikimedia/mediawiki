<?php
/**
 * Belarusian in Taraškievica orthography (Беларуская тарашкевіца) specific code.
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
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @license GPL-2.0-or-later
 * @license GFDL-1.3-or-later
 * @ingroup Language
 */

/**
 * Belarusian in Taraškievica orthography (Беларуская тарашкевіца)
 *
 * @ingroup Language
 * @see http://be-x-old.wikipedia.org/wiki/Project_talk:LanguageBe_tarask.php
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class LanguageBe_tarask extends Language {
	/**
	 * The Belarusian language uses apostrophe sign,
	 * but the characters used for this could be both U+0027 and U+2019.
	 * This function unifies apostrophe sign in search index values
	 * to enable seach using both apostrophe signs.
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	function normalizeForSearch( $string ) {
		# MySQL fulltext index doesn't grok utf-8, so we
		# need to fold cases and convert to hex

		# Replacing apostrophe sign U+2019 with U+0027
		$s = preg_replace( '/\xe2\x80\x99/', '\'', $string );

		$s = parent::normalizeForSearch( $s );

		return $s;
	}
}
