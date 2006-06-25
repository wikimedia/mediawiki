<?php
/** Estonian (Eesti)
 *
 * @package MediaWiki
 * @subpackage Language
 *
 */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesEt.php');
}

class LanguageEt extends LanguageUtf8 {
	private $mMessagesEt, $mNamespaceNamesEt = null;

	private $mSkinNamesEt = array(
		'standard' => 'Standard',
		'nostalgia' => 'Nostalgia',
		'cologneblue' => 'Kölni sinine',
		'smarty' => 'Paddington',
		'montparnasse' => 'Montparnasse',
		'davinci' => 'DaVinci',
		'mono' => 'Mono',
		'monobook' => 'MonoBook',
		'myskin' => 'Mu oma nahk'
	);
	
	private $mDateFormatsEt = array(
		'Eelistus puudub',
		'15.01.2001, kell 16.12',
		'15. jaanuar 2001, kell 16.12',
		'15. I 2005, kell 16.12',
		'ISO 8601' => '2001-01-15 16:12:34'
	);
	
	private $mQuickbarSettingsEt = array(
		'Ei_ole', 'Püsivalt_vasakul', 'Püsivalt paremal', 'Ujuvalt vasakul'
	);
	
	#Lisasin eestimaised poed, aga võõramaiseid ei julenud kustutada.
	
	private $mBookstoreListEt = array(
		'Apollo' => 'http://www.apollo.ee/search.php?keyword=$1&search=OTSI',
		'minu Raamat' => 'http://www.raamat.ee/advanced_search_result.php?keywords=$1',
		'Raamatukoi' => 'http://www.raamatukoi.ee/cgi-bin/index?valik=otsing&paring=$1',
		'AddALL' => 'http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN',
		'PriceSCAN' => 'http://www.pricescan.com/books/bookDetail.asp?isbn=$1',
		'Barnes & Noble' => 'http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1',
		'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1'
	);
	
	
	private $mMagicWordsEt = array(
	#   ID                                 CASE  SYNONYMS
		MAG_REDIRECT             => array( 0,    '#redirect', "#suuna"    ),
	);
	
	function __construct() {
		parent::__construct();

		global $wgAllMessagesEt;
		$this->mMessagesEt =& $wgAllMessagesEt;

		global $wgMetaNamespace;
		$this->mNamespaceNamesEt = array(
			NS_MEDIA            => 'Meedia',
			NS_SPECIAL          => 'Eri',
			NS_MAIN             => '',
			NS_TALK             => 'Arutelu',
			NS_USER             => 'Kasutaja',
			NS_USER_TALK        => 'Kasutaja_arutelu',
			NS_PROJECT          => $wgMetaNamespace,
			NS_PROJECT_TALK     => $wgMetaNamespace . '_arutelu',
			NS_IMAGE            => 'Pilt',
			NS_IMAGE_TALK       => 'Pildi_arutelu',
			NS_MEDIAWIKI        => 'MediaWiki',
			NS_MEDIAWIKI_TALK   => 'MediaWiki_arutelu',
			NS_TEMPLATE         => 'Mall',
			NS_TEMPLATE_TALK    => 'Malli_arutelu',
			NS_HELP             => 'Juhend',
			NS_HELP_TALK        => 'Juhendi_arutelu',
			NS_CATEGORY         => 'Kategooria',
			NS_CATEGORY_TALK    => 'Kategooria_arutelu'
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesEt + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsEt;
	}

	function getSkinNames() {
		return $this->mSkinNamesEt + parent::getSkinNames();
	}

	function getDateFormats() {
		return $this->mDateFormatsEt;
	}

	function getBookstoreList() {
		return $this->mBookstoreListEt;
	}

	function &getMagicWords()  {
		$t = $this->mMagicWordsEt + parent::getMagicWords();
		return $t;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesEt[$key] ) ) {
			return $this->mMessagesEt[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesEt;
	}

	/**
	 * Estonian numeric formatting is 123 456,78.
	 * Notice that the space is non-breaking.
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

	/**
	 * @access public
	 * @param mixed  $ts the time format which needs to be turned into a
	 *               date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	 * @param bool   $adj whether to adjust the time output according to the
	 *               user configured offset ($timecorrection)
	 * @param mixed  $format what format to return, if it's false output the
	 *               default one.
	 * @param string $timecorrection the time offset as returned by
	 *               validateTimeZone() in Special:Preferences
	 * @return string
	 */
	function date( $ts, $adj = false, $format = true, $timecorrection = false ) {
		global $wgAmericanDates;

		if ( $adj ) { $ts = $this->userAdjust( $ts, $timecorrection ); }

		$datePreference = $this->dateFormat($format);

		if ($datePreference == '0'
		    || $datePreference == '' ) {$datePreference = $wgAmericanDates ? '0' : '2';}

		$month = $this->getMonthName( substr( $ts, 4, 2 ) );
		$day = $this->formatNum( 0 + substr( $ts, 6, 2 ) );
		$year = $this->formatNum( substr( $ts, 0, 4 ), true );
		$lat_month = $this->monthByLatinNumber( substr ($ts, 4, 2));

		switch( $datePreference ) {
			case '2': return "$day. $month $year";
			case '3': return "$day. $lat_month $year";
			case 'ISO 8601': return substr($ts, 0, 4). '-' . substr($ts, 4, 2). '-' .substr($ts, 6, 2);
			default: return substr($ts, 6, 2). '.' . substr($ts, 4, 2). '.' .substr($ts, 0, 4);
		}
	}

	/**
	* @access public
	* @param mixed  $ts the time format which needs to be turned into a
	*	       date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	* @param bool   $adj whether to adjust the time output according to the
	*	       user configured offset ($timecorrection)
	* @param mixed  $format what format to return, if it's false output the
	*	       default one (default true)
	* @param string $timecorrection the time offset as returned by
	*	       validateTimeZone() in Special:Preferences
	* @return string
	*/
	function time( $ts, $adj = false, $format = true, $timecorrection = false ) {
		global $wgUser, $wgAmericanDates;

		if ( $adj ) { $ts = $this->userAdjust( $ts, $timecorrection ); }
		$datePreference = $this->dateFormat($format);

		if ($datePreference == '0') {$datePreference = $wgAmericanDates ? '0' : '2';}

		if ( $datePreference === 'ISO 8601' ) {
			$t = substr( $ts, 8, 2 ) . ':' . substr( $ts, 10, 2 );
			$t .= ':' . substr( $ts, 12, 2 );
		} else {
			$t = substr( $ts, 8, 2 ) . '.' . substr( $ts, 10, 2 );
		}
		return $t;
	}

	/**
	* @access public
	* @param mixed  $ts the time format which needs to be turned into a
	*	       date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	* @param bool   $adj whether to adjust the time output according to the
	*	       user configured offset ($timecorrection)
	* @param mixed  $format what format to return, if it's false output the
	*	       default one (default true)
	* @param string $timecorrection the time offset as returned by
	*	       validateTimeZone() in Special:Preferences
	* @return string
	*/
	function timeanddate( $ts, $adj = false, $format = true, $timecorrection = false) {
		global $wgUser, $wgAmericanDates;

		$datePreference = $this->dateFormat($format);
		switch ( $datePreference ) {
			case 'ISO 8601': return $this->date( $ts, $adj, $datePreference, $timecorrection ) . ' ' .
				$this->time( $ts, $adj, $datePreference, $timecorrection );
			default: return $this->date( $ts, $adj, $datePreference, $timecorrection ) . ', kell ' .
				$this->time( $ts, $adj, $datePreference, $timecorrection );

		}

	}

	/**
	* retuns latin number corresponding to given month number
	* @access public
	* @param number
	* @return string
	*/
	function monthByLatinNumber( $key ) {
		$latinNumbers= array(
			'I', 'II', 'III', 'IV', 'V', 'VI',
			'VII','VIII','IX','X','XI','XII'
		);

		return $latinNumbers[$key-1];
	}


}
?>
