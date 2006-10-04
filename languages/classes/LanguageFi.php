<?php
/** Finnish (Suomi)
 *
 * @package MediaWiki
 * @subpackage Language
 *
 * @author Niklas Laxström
 */
class LanguageFi extends Language {
	/**
	 * Avoid grouping whole numbers between 0 to 9999
	 */
	function commafy($_) {
		if (!preg_match('/^\d{1,4}$/',$_)) {
			return strrev((string)preg_replace('/(\d{3})(?=\d)(?!\d*\.)/','$1,',strrev($_)));
		} else {
			return $_;
		}
	}

	# Convert from the nominative form of a noun to some other case
	# Invoked with {{GRAMMAR:case|word}}
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset($wgGrammarForms['fi'][$case][$word]) ) {
			return $wgGrammarForms['fi'][$case][$word];
		}

		# These rules are not perfect, but they are currently only used for site names so it doesn't
		# matter if they are wrong sometimes. Just add a special case for your site name if necessary.
		switch ( $case ) {
			case 'genitive':
				if ( $word == 'Wikisitaatit' ) {
					$word = 'Wikisitaattien';
				} else {
					$word .= 'n';
				}
			break;
			case 'elative':
				if ( $word == 'Wikisitaatit' ) {
					$word = 'Wikisitaateista';
				} else {
					if ( mb_substr($word, -1) == 'y' ) {
						$word .= 'stä';
					} else {
						$word .= 'sta';
					}
				}
				break;
			case 'partitive':
				if ( $word == 'Wikisitaatit' ) {
					$word = 'Wikisitaatteja';
				} else {
					if ( mb_substr($word, -1) == 'y' ) {
						$word .= 'ä';
					} else {
						$word .= 'a';
					}
				}
				break;
			case 'illative':
				# Double the last letter and add 'n'
				# mb_substr has a compatibility function in GlobalFunctions.php
				if ( $word == 'Wikisitaatit' ) {
					$word = 'Wikisitaatteihin';
				} else {
					$word = $word . mb_substr($word,-1) . 'n';
				}
				break;
			case 'inessive':
				if ( $word == 'Wikisitaatit' ) {
					$word = 'Wikisitaateissa';
				} else {
					if ( mb_substr($word, -1) == 'y' ) {
						$word .= 'ssä';
					} else {
						$word .= 'ssa';
					}
				}
				break;

		}
		return $word;
	}

	function translateBlockExpiry( $str ) {
		/*
			'ago', 'now', 'today', 'this', 'next',
			'first', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth', 'eleventh', 'twelfth',
			'tomorrow', 'yesterday'

			$months = 'january:tammikuu,february:helmikuu,march:maaliskuu,april:huhtikuu,may:toukokuu,june:kesäkuu,' .
				'july:heinäkuu,august:elokuu,september:syyskuu,october:lokakuu,november:marraskuu,december:joulukuu,' .
				'jan:tammikuu,feb:helmikuu,mar:maaliskuu,apr:huhtikuu,jun:kesäkuu,jul:heinäkuu,aug:elokuu,sep:syyskuu,'.
				'oct:lokakuu,nov:marraskuu,dec:joulukuu,sept:syyskuu';
		*/
		$weekds = array(
			'monday' => 'maanantai',
			'tuesday' => 'tiistai',
			'wednesday' => 'keskiviikko',
			'thursay' => 'torstai',
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
		);

		$final = '';
		$tokens = explode ( ' ', $str);
		foreach( $tokens as $item ) {
			if ( !is_numeric($item) ) {
				if ( count ( explode( '-', $item ) ) == 3 && strlen($item) == 10 ) {
					list( $yyyy, $mm, $dd ) = explode( '-', $item );
					$final .= ' ' . $this->date( "{$yyyy}{$mm}{$dd}00000000");
					continue;
				}
				if( isset( $weekds[$item] ) ) {
					$final .= ' ' . $weekds[$item];
					continue;
				}
			}

			$final .= ' ' . $item;
		}
	   	return '<span class="blockexpiry" title="' . htmlspecialchars($str). '">”' . trim( $final ) . '”</span>';
	}

}

?>
