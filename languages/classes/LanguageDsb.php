<?php
/**
 * Lower Sorbian (Dolnoserbski) specific code.
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
 * @ingroup Language
 */

/**
 * Lower Sorbian (Dolnoserbski)
 *
 * @ingroup Language
 */
class LanguageDsb extends Language {
	/**
	 * Convert from the nominative form of a noun to some other case
	 * Invoked with {{grammar:case|word}}
	 *
	 * @param string $word
	 * @param string $case
	 * @return string
	 */
	public function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms['dsb'][$case][$word] ) ) {
			return $wgGrammarForms['dsb'][$case][$word];
		}

		switch ( $case ) {
			case 'instrumental': # instrumental
				$word = 'z ' . $word;
			case 'lokatiw': # lokatiw
				$word = 'wo ' . $word;
				break;
		}

		# this will return the original value for 'nominatiw' (nominativ) and
		# all undefined case values.
		return $word;
	}
}
