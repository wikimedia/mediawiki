<?php
/** Finnish (Suomi)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

# Revised 2005-12-24 for MediaWiki 1.6dev -- Nikerabbit

/* private */ $wgNamespaceNamesFi = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Toiminnot',
	NS_MAIN             => '',
	NS_TALK             => 'Keskustelu',
	NS_USER             => 'Käyttäjä',
	NS_USER_TALK        => 'Keskustelu_käyttäjästä',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => FALSE,  # Set in constructor
	NS_IMAGE            => 'Kuva',
	NS_IMAGE_TALK       => 'Keskustelu_kuvasta',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
	NS_TEMPLATE         => 'Malline',
	NS_TEMPLATE_TALK    => 'Keskustelu_mallineesta',
	NS_HELP             => 'Ohje',
	NS_HELP_TALK        => 'Keskustelu_ohjeesta',
	NS_CATEGORY         => 'Luokka',
	NS_CATEGORY_TALK    => 'Keskustelu_luokasta'

) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsFi = array(
	'Ei mitään', 'Tekstin mukana, vasen', 'Tekstin mukana, oikea', 'Pysyen vasemmalla'
);

/* private */ $wgSkinNamesFi = array(
	'standard'          => 'Perus',
	'cologneblue'       => 'Kölnin sininen',
	'myskin'            => 'Oma tyylisivu'
) + $wgSkinNamesEn;

/* private */ $wgDateFormatsFi = array(
	MW_DATE_DEFAULT => 'Ei valintaa',
	1               => '15. tammikuuta 2001 kello 16.12',
	2               => '15. tammikuuta 2001 kello 16:12:34',
	3               => '15.1.2001 16.12',
	MW_DATE_ISO     => '2001-01-15 16:12:34'
);

/* private */ $wgBookstoreListFi = array(
	'Akateeminen kirjakauppa'       => 'http://www.akateeminen.com/search/tuotetieto.asp?tuotenro=$1',
	'Bookplus'                      => 'http://www.bookplus.fi/product.php?isbn=$1',
	'Helsingin yliopiston kirjasto' => 'http://pandora.lib.hel.fi/cgi-bin/mhask/monihask.py?volname=&author=&keyword=&ident=$1&submit=Hae&engine_helka=ON',
	'Pääkaupunkiseudun kirjastot'   => 'http://www.helmet.fi/search*fin/i?SEARCH=$1',
	'Tampereen seudun kirjastot'    => 'http://pandora.lib.hel.fi/cgi-bin/mhask/monihask.py?volname=&author=&keyword=&ident=$1-1&lang=kaikki&mat_type=kaikki&submit=Hae&engine_tampere=ON'
) + $wgBookstoreListEn;

# Current practices (may be changed if not good ones)
# Refer namespaces with the English name or 'Project' in case of project namespace
# Avoid any hard coded references to any particular subject which may not apply everywhere, e.g. artikkeli, wikipedia
# Don't use participial phrases (lauseenkastikkeita) incorrectly
# Avoid unnecessary parenthesis, quotes and html code
#

if (!$wgCachedMessageArrays) {
	require_once('MessagesFi.php');
}

#-------------------------------------------------------------------
# Translated messages
#-------------------------------------------------------------------



	#--------------------------------------------------------------------------
	# Internationalisation code
	#--------------------------------------------------------------------------

class LanguageFi extends LanguageUtf8 {
	function LanguageFi() {
		global $wgNamespaceNamesFi, $wgMetaNamespace;
		LanguageUtf8::LanguageUtf8();
		$wgNamespaceNamesFi[NS_PROJECT_TALK] = 'Keskustelu_' . $this->convertGrammar( $wgMetaNamespace, 'elative' );
	}

	function getBookstoreList () {
		global $wgBookstoreListFi ;
		return $wgBookstoreListFi ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesFi;
		return $wgNamespaceNamesFi;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsFi;
		return $wgQuickbarSettingsFi;
	}

	function getSkinNames() {
		global $wgSkinNamesFi;
		return $wgSkinNamesFi;
	}

	function getDateFormats() {
		global $wgDateFormatsFi;
		return $wgDateFormatsFi;
	}

	/**
	 * See Language.php for documentation
	 */
	function date( $ts, $adj = false, $format = true, $timecorrection = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts, $timecorrection ); }

		$yyyy = substr( $ts, 0, 4 );
		$mm = substr( $ts, 4, 2 );
		$m = 0 + $mm;
		$mmmm = $this->getMonthName( $mm ) . 'ta';
		$dd = substr( $ts, 6, 2 );
		$d = 0 + $dd;

		$datePreference = $this->dateFormat($format);
		switch( $datePreference ) {
			case '3': return "$d.$m.$yyyy";
			case MW_DATE_ISO: return "$yyyy-$mm-$dd";
			default: return "$d. $mmmm $yyyy";
		}
	}

	/**
	 * See Language.php for documentation
	 */
	function time( $ts, $adj = false, $format = true, $timecorrection = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts, $timecorrection ); }

		$hh = substr( $ts, 8, 2 );
		$mm =  substr( $ts, 10, 2 );
		$ss = substr( $ts, 12, 2 );

		$datePreference = $this->dateFormat($format);
		switch( $datePreference ) {
			case '2':
			case MW_DATE_ISO: return "$hh:$mm:$ss";
			default: return "$hh.$mm";
		}
	}

	/**
	 * See Language.php for documentation
	 */
	function timeanddate( $ts, $adj = false, $format = true, $timecorrection = false) {
		$date = $this->date( $ts, $adj, $format, $timecorrection );
		$time = $this->time( $ts, $adj, $format, $timecorrection );

		$datePreference = $this->dateFormat($format);
		switch( $datePreference ) {
			case '3':
			case MW_DATE_ISO: return "$date $time";
			default: return "$date kello $time";
		}
	}

	function getMessage( $key ) {
		global $wgAllMessagesFi;
		if( isset( $wgAllMessagesFi[$key] ) ) {
			return $wgAllMessagesFi[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	/**
	 * Finnish numeric formatting is 123 456,78.
	 * Notice that the space is non-breaking.
	 */
	function formatNum( $number, $year = false ) {
		return $year ? $number : strtr($this->commafy($number), '.,', ", " );
	}

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
