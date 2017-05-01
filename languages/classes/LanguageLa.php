<?php
/**
 * Latin (lingua Latina) specific code.
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
 * @ingroup Language
 */

/**
 * Latin (lingua Latina)
 *
 * @ingroup Language
 */
class LanguageLa extends Language {
	/**
	 * Convert from the nominative form of a noun to some other case
	 *
	 * Just used in a couple places for sitenames; special-case as necessary.
	 * Rules are far from complete.
	 *
	 * Cases: genitive, accusative, ablative
	 *
	 * @param string $word
	 * @param string $case
	 *
	 * @return string
	 */
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms['la'][$case][$word] ) ) {
			return $wgGrammarForms['la'][$case][$word];
		}

		switch ( $case ) {
		case 'genitive':
			// only a few declensions, and even for those mostly the singular only
			$in = [
				'/u[ms]$/',                	# 2nd declension singular
				'/ommunia$/',              	# 3rd declension neuter plural (partly)
				'/a$/',                    	# 1st declension singular
				'/libri$/', '/nuntii$/', '/datae$/', # 2nd declension plural (partly)
				'/tio$/', '/ns$/', '/as$/',	# 3rd declension singular (partly)
				'/es$/'                    	# 5th declension singular
			];
			$out = [
				'i',
				'ommunium',
				'ae',
				'librorum', 'nuntiorum', 'datorum',
				'tionis', 'ntis', 'atis',
				'ei'
			];
			return preg_replace( $in, $out, $word );
		case 'accusative':
			// only a few declensions, and even for those mostly the singular only
			$in = [
				'/u[ms]$/',                	# 2nd declension singular
				'/a$/',                    	# 1st declension singular
				'/ommuniam$/',              # 3rd declension neuter plural (partly)
				'/libri$/', '/nuntii$/', '/datam$/', # 2nd declension plural (partly)
				'/tio$/', '/ns$/', '/as$/',	# 3rd declension singular (partly)
				'/es$/'                    	# 5th declension singular
			];
			$out = [
				'um',
				'am',
				'ommunia',
				'libros', 'nuntios', 'data',
				'tionem', 'ntem', 'atem',
				'em'
			];
			return preg_replace( $in, $out, $word );
		case 'ablative':
			// only a few declensions, and even for those mostly the singular only
			$in = [
				'/u[ms]$/',                	# 2nd declension singular
				'/ommunia$/',              	# 3rd declension neuter plural (partly)
				'/a$/',                    	# 1st declension singular
				'/libri$/', '/nuntii$/', '/data$/', # 2nd declension plural (partly)
				'/tio$/', '/ns$/', '/as$/',	# 3rd declension singular (partly)
				'/es$/'                    	# 5th declension singular
			];
			$out = [
				'o',
				'ommunibus',
				'a',
				'libris', 'nuntiis', 'datis',
				'tione', 'nte', 'ate',
				'e'
			];
			return preg_replace( $in, $out, $word );
		default:
			return $word;
		}
	}
}
