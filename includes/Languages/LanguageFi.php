<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author Niklas Laxström
 */

use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserIdentity;

/**
 * Finnish (Suomi)
 *
 * @ingroup Languages
 */
class LanguageFi extends Language {
	/** @inheritDoc */
	public function convertGrammar( $word, $case ) {
		$grammarForms =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::GrammarForms );
		if ( isset( $grammarForms['fi'][$case][$word] ) ) {
			return $grammarForms['fi'][$case][$word];
		}

		# These rules don't cover the whole language.
		# They are used only for site names.

		# vowel harmony flag
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
				$word .= mb_substr( $word, -1 ) . 'n';
				break;
			case 'inessive':
				$word .= ( $aou ? 'ssa' : 'ssä' );
				break;
		}
		return $word;
	}

	/**
	 * @param string $str
	 * @param UserIdentity|null $user User object to use timezone from or null, ignored
	 * @param int $now Current timestamp, for formatting relative block durations
	 * @return string
	 */
	public function translateBlockExpiry( $str, ?UserIdentity $user = null, $now = 0 ) {
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
			'infinite' => 'ikuinen',
			'indefinite' => 'ikuinen',
			'infinity' => 'ikuinen'
		];

		$final = '';
		$tokens = explode( ' ', $str );
		foreach ( $tokens as $item ) {
			if ( !is_numeric( $item ) ) {
				if ( count( explode( '-', $item ) ) == 3 && strlen( $item ) == 10 ) {
					[ $yyyy, $mm, $dd ] = explode( '-', $item );
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

		return trim( $final );
	}
}
