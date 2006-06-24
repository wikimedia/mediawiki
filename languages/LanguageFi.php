<?php
/** Finnish (Suomi)
 *
 * @package MediaWiki
 * @subpackage Language
 *
 * @author Niklas Laxström
 */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesFi.php');
}

class LanguageFi extends LanguageUtf8 {
	private $mMessagesFi, $mNamespaceNamesFi = null;
	
	private $mSkinNamesFi = array(
		'standard'          => 'Perus',
		'cologneblue'       => 'Kölnin sininen',
		'myskin'            => 'Oma tyylisivu'
	);
	
	private $mQuickbarSettingsFi = array(
		'Ei mitään', 'Tekstin mukana, vasen', 'Tekstin mukana, oikea', 'Pysyen vasemmalla', 'Pysyen oikealla'
	);
	
	private $mDateFormatsFi = array(
		MW_DATE_DEFAULT => 'Ei valintaa',
		1               => '15. tammikuuta 2001 kello 16.12',
		2               => '15. tammikuuta 2001 kello 16:12:34',
		3               => '15.1.2001 16.12',
		MW_DATE_ISO     => '2001-01-15 16:12:34'
	);
	
	private $mBookstoreListFi = array(
		'Bookplus'                      => 'http://www.bookplus.fi/product.php?isbn=$1',
		'Helsingin yliopiston kirjasto' => 'http://pandora.lib.hel.fi/cgi-bin/mhask/monihask.py?volname=&author=&keyword=&ident=$1&submit=Hae&engine_helka=ON',
		'Pääkaupunkiseudun kirjastot'   => 'http://www.helmet.fi/search*fin/i?SEARCH=$1',
		'Tampereen seudun kirjastot'    => 'http://kirjasto.tampere.fi/Piki?formid=fullt&typ0=6&dat0=$1'
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesFi;
		$this->mMessagesFi =& $wgAllMessagesFi;

		global $wgMetaNamespace;
		$this->mNamespaceNamesFi = array(
			NS_MEDIA            => 'Media',
			NS_SPECIAL          => 'Toiminnot',
			NS_MAIN             => '',
			NS_TALK             => 'Keskustelu',
			NS_USER             => 'Käyttäjä',
			NS_USER_TALK        => 'Keskustelu_käyttäjästä',
			NS_PROJECT          => $wgMetaNamespace,
			NS_PROJECT_TALK     => 'Keskustelu_' . $this->convertGrammar( $wgMetaNamespace, 'elative' ),
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
		);

	}

	function getBookstoreList () {
		return $this->mBookstoreListFi;
	}

	function getNamespaces() {
		return $this->mNamespaceNamesFi + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsFi;
	}

	function getSkinNames() {
		return $this->mSkinNamesFi + parent::getSkinNames();
	}

	function getDateFormats() {
		return $this->mDateFormatsFi;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesFi[$key] ) ) {
			return $this->mMessagesFi[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesFi;
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

	/**
	 * Finnish numeric formatting is 123 456,78.
	 */
	function separatorTransformTable() {
		return array(',' => "\xc2\xa0", '.' => ',' );
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

	function linkTrail() {
		return '/^([a-zäö]+)(.*)$/sDu';
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
