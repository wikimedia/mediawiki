<?php
/**
 * Finnish (Suomi) specific code.
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
 * Finnish (Suomi)
 *
 * @ingroup Language
 */
class LanguageFi extends Language {
	/**
	 * Convert from the nominative form of a noun to some other case
	 * Invoked with {{grammar:case|word}}
	 *
	 * @param string $word
	 * @param string $case
	 * @return string
	 */
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms['fi'][$case][$word] ) ) {
			return $wgGrammarForms['fi'][$case][$word];
		}

		# These rules don't cover the whole language.
		# They are used only for site names.

		# wovel harmony flag
		$aou = preg_match( '/[aou][^äöy]*$/i', $word );

		# The flag should be false for compounds where the last word has only neutral vowels (e/i).
		# The general case cannot be handled without a dictionary, but there's at least one notable
		# special case we should check for:

		if ( preg_match( '/wiki$/i', $word ) ) {
			$aou = false;
		}

		# append i after final consonant
		if ( preg_match( '/[bcdfghjklmnpqrstvwxz]$/i', $word ) ) {
			$word .= 'i';
		}

		switch ( $case ) {
			case 'genitive':
				$word .= 'n';
				break;
			case 'elative':
				$word .= ( $aou ? 'sta' : 'stä' );
				break;
			case 'partitive':
				$word .= ( $aou ? 'a' : 'ä' );
				break;
			case 'illative':
				# Double the last letter and add 'n'
				$word = $word . mb_substr( $word, -1 ) . 'n';
				break;
			case 'inessive':
				$word .= ( $aou ? 'ssa' : 'ssä' );
				break;
		}
		return $word;
	}

	/**
	 * @param string $str
	 * @param User $user User object to use timezone from or null for $wgUser
	 * @return string
	 */
	function translateBlockExpiry( $str, User $user = null ) {
		/*
			'ago', 'now', 'today', 'this', 'next',
			'first', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth',
				'tenth', 'eleventh', 'twelfth',
			'tomorrow', 'yesterday'

			$months = 'january:tammikuu,february:helmikuu,march:maaliskuu,april:huhtikuu,' .
				'may:toukokuu,june:kesäkuu,july:heinäkuu,august:elokuu,september:syyskuu,' .
				'october:lokakuu,november:marraskuu,december:joulukuu,' .
				'jan:tammikuu,feb:helmikuu,mar:maaliskuu,apr:huhtikuu,jun:kesäkuu,' .
				'jul:heinäkuu,aug:elokuu,sep:syyskuu,oct:lokakuu,nov:marraskuu,' .
				dec:joulukuu,sept:syyskuu';
		*/
		$weekds = [
			'monday' => 'maanantai',
			'tuesday' => 'tiistai',
			'wednesday' => 'keskiviikko',
			'thursday' => 'torstai',
			'friday' => 'perjantai',
			'saturday' => 'lauantai',
			'sunday' => 'sunnuntai',
			'mon' => 'ma',
			'tue' => 'ti',
			'tues' => 'ti',
			'wed' => 'ke',
			'wednes' => 'ke',
			'thu' => 'to',
			'thur' => 'to',
			'thurs' => 'to',
			'fri' => 'pe',
			'sat' => 'la',
			'sun' => 'su',
			'next' => 'seuraava',
			'tomorrow' => 'huomenna',
			'ago' => 'sitten',
			'seconds' => 'sekuntia',
			'second' => 'sekunti',
			'secs' => 's',
			'sec' => 's',
			'minutes' => 'minuuttia',
			'minute' => 'minuutti',
			'mins' => 'min',
			'min' => 'min',
			'days' => 'päivää',
			'day' => 'päivä',
			'hours' => 'tuntia',
			'hour' => 'tunti',
			'weeks' => 'viikkoa',
			'week' => 'viikko',
			'fortnights' => 'tuplaviikkoa',
			'fortnight' => 'tuplaviikko',
			'months' => 'kuukautta',
			'month' => 'kuukausi',
			'years' => 'vuotta',
			'year' => 'vuosi',
			'infinite' => 'ikuisesti',
			'indefinite' => 'ikuisesti'
		];

		$final = '';
		$tokens = explode( ' ', $str );
		foreach ( $tokens as $item ) {
			if ( !is_numeric( $item ) ) {
				if ( count( explode( '-', $item ) ) == 3 && strlen( $item ) == 10 ) {
					list( $yyyy, $mm, $dd ) = explode( '-', $item );
					$final .= ' ' . $this->date( "{$yyyy}{$mm}{$dd}000000" );
					continue;
				}
				if ( isset( $weekds[$item] ) ) {
					$final .= ' ' . $weekds[$item];
					continue;
				}
			}

			$final .= ' ' . $item;
		}

		return htmlspecialchars( trim( $final ) );
	}
}
