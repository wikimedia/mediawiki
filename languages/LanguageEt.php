<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

$wgNamespaceNamesEt = array(
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
) + $wgNamespaceNamesEn;

/* private */ $wgSkinNamesEt = array(
	'standard' => "Standard",
	'nostalgia' => "Nostalgia",
	'cologneblue' => "Kölni sinine",
	'smarty' => "Paddington",
	'montparnasse' => "Montparnasse",
	'davinci' => "DaVinci",
	'mono' => "Mono",
	'monobook' => "MonoBook",
	 "myskin" => "Mu oma nahk"
);


/* private */ $wgDateFormatsEt = array(
        'Eelistus puudub',
        '15.01.2001, kell 16.12',
        '15. jaanuar 2001, kell 16.12',
        '15. I 2005, kell 16.12',
        'ISO 8601' => '2001-01-15 16:12:34'
);


/* private */ $wgQuickbarSettingsEt = array(
	"Ei_ole", "Püsivalt_vasakul", "Püsivalt paremal", "Ujuvalt vasakul"
);

#Lisasin eestimaised poed, aga võõramaiseid ei julenud kustutada.


/* private */ $wgBookstoreListEt = array(
	"Apollo" => "http://www.apollo.ee/search.php?keyword=$1&search=OTSI",
	"minu Raamat" => "http://www.raamat.ee/advanced_search_result.php?keywords=$1",
	"Raamatukoi" => "http://www.raamatukoi.ee/cgi-bin/index?valik=otsing&paring=$1",
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);


/* private */ $wgMagicWordsEt = array(
#   ID                                 CASE  SYNONYMS
	MAG_REDIRECT             => array( 0,    '#redirect', "#suuna"    ),
	MAG_NOTOC                => array( 0,    '__NOTOC__'              ),
	MAG_FORCETOC             => array( 0,    '__FORCETOC__'           ),
	MAG_TOC                  => array( 0,    '__TOC__'                ),
	MAG_NOEDITSECTION        => array( 0,    '__NOEDITSECTION__'      ),
	MAG_START                => array( 0,    '__START__'              ),
	MAG_CURRENTMONTH         => array( 1,    'CURRENTMONTH'           ),
	MAG_CURRENTMONTHNAME     => array( 1,    'CURRENTMONTHNAME'       ),
	MAG_CURRENTDAY           => array( 1,    'CURRENTDAY'             ),
	MAG_CURRENTDAYNAME       => array( 1,    'CURRENTDAYNAME'         ),
	MAG_CURRENTYEAR          => array( 1,    'CURRENTYEAR'            ),
	MAG_CURRENTTIME          => array( 1,    'CURRENTTIME'            ),
	MAG_NUMBEROFARTICLES     => array( 1,    'NUMBEROFARTICLES'       ),
	MAG_CURRENTMONTHNAMEGEN  => array( 1,    'CURRENTMONTHNAMEGEN'    ),
	MAG_PAGENAME             => array( 1,    'PAGENAME'               ),
	MAG_PAGENAMEE                    => array( 1,    'PAGENAMEE'              ),
	MAG_NAMESPACE            => array( 1,    'NAMESPACE'              ),
	MAG_SUBST                => array( 0,    'SUBST:'                 ),
	MAG_MSGNW                => array( 0,    'MSGNW:'                 ),
	MAG_END                  => array( 0,    '__END__'                ),
	MAG_IMG_THUMBNAIL        => array( 1,    'thumbnail', 'thumb'     ),
	MAG_IMG_RIGHT            => array( 1,    'right'                  ),
	MAG_IMG_LEFT             => array( 1,    'left'                   ),
	MAG_IMG_NONE             => array( 1,    'none'                   ),
	MAG_IMG_WIDTH            => array( 1,    '$1px'                   ),
	MAG_IMG_CENTER           => array( 1,    'center', 'centre'       ),
	MAG_IMG_FRAMED           => array( 1,    'framed', 'enframed', 'frame' ),
	MAG_INT                  => array( 0,    'INT:'                   ),
	MAG_SITENAME             => array( 1,    'SITENAME'               ),
	MAG_NS                   => array( 0,    'NS:'                    ),
	MAG_LOCALURL             => array( 0,    'LOCALURL:'              ),
	MAG_LOCALURLE            => array( 0,    'LOCALURLE:'             ),
	MAG_SERVER               => array( 0,    'SERVER'                 ),
	MAG_GRAMMAR              => array( 0,    'GRAMMAR:'               )
);


if (!$wgCachedMessageArrays) {
	require_once('MessagesEt.php');
}

require_once( "LanguageUtf8.php" );

class LanguageEt extends LanguageUtf8 {


	function getBookstoreList () {
		global $wgBookstoreListEt ;
		return $wgBookstoreListEt ;
	}

	function getDateFormats() {
		global $wgDateFormatsEt;
		return $wgDateFormatsEt;
	}

	function getNamespaces() {
		global $wgNamespaceNamesEt;
		return $wgNamespaceNamesEt;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsEt;
		return $wgQuickbarSettingsEt;
	}

	function getSkinNames() {
		global $wgSkinNamesEt;
		return $wgSkinNamesEt;
	}

	function getMessage( $key ) {
		global $wgAllMessagesEt;
		if( isset( $wgAllMessagesEt[$key] ) ) {
			return $wgAllMessagesEt[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	/**
	 * Estonian numeric formatting is 123 456,78.
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
		global $wgAmericanDates, $wgUser;

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
