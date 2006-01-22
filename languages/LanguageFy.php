<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesFy = array(
	NS_MEDIA          => "Media",
	NS_SPECIAL        => "Wiki",
	NS_MAIN           => "",
	NS_TALK           => "Oerlis",
	NS_USER           => "Meidogger",
	NS_USER_TALK      => "Meidogger_oerlis",
	NS_PROJECT        => $wgMetaNamespace,
	NS_PROJECT_TALK   => $wgMetaNamespace . "_oerlis",
	NS_IMAGE          => "Ofbyld",
	NS_IMAGE_TALK     => "Ofbyld_oerlis",
	NS_MEDIAWIKI      => "MediaWiki",
	NS_MEDIAWIKI_TALK => "MediaWiki_oerlis",
	NS_TEMPLATE       => "Berjocht",
	NS_TEMPLATE_TALK  => "Berjocht_oerlis",
	NS_HELP           => "Hulp",
	NS_HELP_TALK      => "Hulp_oerlis",
	NS_CATEGORY       => "Kategory",
	NS_CATEGORY_TALK  => "Kategory_oerlis"
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsFy = array(
	"Ut", "Lofts fêst", "Rjochts fêst", "Lofts sweevjend"
);

/* private */ $wgSkinNamesFy = array(
	'standard' => "Standert",
	'nostalgia' => "Nostalgy",
) + $wgSkinNamesEn;


/* private */ $wgDateFormatsFy = array(
	'Gjin foarkar',
	'16.12, jan 15, 2001',
	'16.12, 15 jan 2001',
	'16.12, 2001 jan 15',
	'ISO 8601' => '2001-01-15 16:12:34'
);

/* private */ $wgBookstoreListFy = array(
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesFy.php');
}

class LanguageFy extends LanguageUtf8 {

	function getBookstoreList () {
		global $wgBookstoreListFy ;
		return $wgBookstoreListFy ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesFy;
		return $wgNamespaceNamesFy;
	}


	function getNsIndex( $text ) {
		global $wgNamespaceNamesFy;

		foreach ( $wgNamespaceNamesFy as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		if ( 0 == strcasecmp( "Brûker", $text ) ) return 2;
		if ( 0 == strcasecmp( "Brûker_oerlis", $text ) ) return 3;
		return false;
	}


	function getQuickbarSettings() {
		global $wgQuickbarSettingsFy;
		return $wgQuickbarSettingsFy;
	}

	function getSkinNames() {
		global $wgSkinNamesFy;
		return $wgSkinNamesFy;
	}


	var $digitTransTable = array(
		',' => '.',
		'.' => ','
	);

	function formatNum( $number, $year = false ) {
		return !$year ? strtr($this->commafy($number), $this->digitTransTable ) : $number;
	}

	function getDateFormats() {
		global $wgDateFormatsFy;
		return $wgDateFormatsFy;
	}

	/**
	 * @access public
	 * @param mixed  $ts the time format which needs to be turned into a
	 *               date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	 * @param bool   $adj whether to adjust the time output according to the
	 *               user configured offset ($timecorrection)
	 * @param bool   $format true to use user's date format preference
	 * @param string $timecorrection the time offset as returned by
	 *               validateTimeZone() in Special:Preferences
	 * @return string
	 */
	function date( $ts, $adj = false, $format = true, $timecorrection = false ) {
		global $wgUser;

		if ( $adj ) { $ts = $this->userAdjust( $ts, $timecorrection ); }

		$datePreference = $this->dateFormat( $format );

		$month = $this->getMonthAbbreviation( substr( $ts, 4, 2 ) );
		$day = 0 + substr( $ts, 6, 2 );
		$year = substr( $ts, 0, 4 );

		switch( $datePreference ) {
			case MW_DATE_DMY: return "$day $month $year";
			case MW_DATE_YMD: return "$year $month $day";
			case MW_DATE_ISO: return substr($ts, 0, 4). '-' . substr($ts, 4, 2). '-' .substr($ts, 6, 2);
			default: return "$month $day, $year";
		}
	}

	/**
	* @access public
	* @param mixed  $ts the time format which needs to be turned into a
	*               date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	* @param bool   $adj whether to adjust the time output according to the
	*               user configured offset ($timecorrection)
	* @param bool   $format true to use user's date format preference
	* @param string $timecorrection the time offset as returned by
	*               validateTimeZone() in Special:Preferences
	* @return string
	*/
	function time( $ts, $adj = false, $format = true, $timecorrection = false ) {
		global $wgUser;

		if ( $adj ) { $ts = $this->userAdjust( $ts, $timecorrection ); }
		$datePreference = $this->dateFormat( $format );

		if ( $datePreference == MW_DATE_ISO ) {
			$sep = ':';
		} else {
			$sep = '.';
		}

		$t = substr( $ts, 8, 2 ) . $sep . substr( $ts, 10, 2 );

		if ( $datePreference == MW_DATE_ISO ) {
			$t .= $sep . substr( $ts, 12, 2 );
		}
		return $t;
	}

	function getMessage( $key ) {
		global $wgAllMessagesFy;
		if( isset( $wgAllMessagesFy[$key] ) ) {
			return $wgAllMessagesFy[$key];
		} else {
			return parent::getMessage( $key );
		}
	}
}

?>
