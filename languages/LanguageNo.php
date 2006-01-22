<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

/* private */ $wgNamespaceNamesNo = array(
	NS_MEDIA          => "Medium",
	NS_SPECIAL        => "Spesial",
	NS_MAIN           => "",
	NS_TALK           => "Diskusjon",
	NS_USER           => "Bruker",
	NS_USER_TALK      => "Brukerdiskusjon",
	NS_PROJECT        => $wgMetaNamespace,
	NS_PROJECT_TALK   => $wgMetaNamespace . "-diskusjon",
	NS_IMAGE          => "Bilde",
	NS_IMAGE_TALK     => "Bildediskusjon",
	NS_MEDIAWIKI      => "MediaWiki",
	NS_MEDIAWIKI_TALK => "MediaWiki-diskusjon",
	NS_TEMPLATE       => "Mal",
	NS_TEMPLATE_TALK  => "Maldiskusjon",
	NS_HELP           => "Hjelp",
	NS_HELP_TALK      => "Hjelpdiskusjon",
	NS_CATEGORY       => "Kategori",
	NS_CATEGORY_TALK  => "Kategoridiskusjon",
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsNo = array(
	"Ingen", "Fast venstre", "Fast høyre", "Flytende venstre"
);

/* private */ $wgSkinNamesNo = array(
	'standard' => "Standard",
	'nostalgia' => "Nostalgi",
	'cologneblue' => "Kölnerblå"
) + $wgSkinNamesEn;


/* private */ $wgBookstoreListNo = array(
	"Antikvariat.net" => "http://www.antikvariat.net/",
	"Bibsys" => "http://www.bibsys.no/",
	"Bokkilden" => "http://www.bokkilden.no/",
	"Haugenbok" => "http://www.haugenbok.no/",
	"Mao.no" => "http://www.mao.no/"
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesNo.php');
}

require_once( "LanguageUtf8.php" );

class LanguageNo extends LanguageUtf8 {

	function getBookstoreList () {
		global $wgBookstoreListNo ;
		return $wgBookstoreListNo ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesNo;
		return $wgNamespaceNamesNo;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsNo;
		return $wgQuickbarSettingsNo;
	}

	function getSkinNames() {
		global $wgSkinNamesNo;
		return $wgSkinNamesNo;
	}

	function formatMonth( $month, $format ) {
		return $this->getMonthAbbreviation( $month );
	}

	function formatDay( $day, $format ) {
		return parent::formatDay( $day, $format ) . '.';
	}

	function timeanddate( $ts, $adj = false, $format = false, $timecorrection = false ) {
		$format = $this->dateFormat( $format );
		if( $format == MW_DATE_ISO ) {
			return parent::timeanddate( $ts, $adj, $format, $timecorrection );
		} else {
			return $this->date( $ts, $adj, $format, $timecorrection ) .
				" kl." .
				$this->time( $ts, $adj, $format, $timecorrection );
		}
	}

	function formatNum( $number, $year = false ) {
		return !$year ? strtr($number, '.,', ',.' ) : $number;
	}

	function getMessage( $key ) {
		global $wgAllMessagesNo;
		if( isset( $wgAllMessagesNo[$key] ) ) {
			return $wgAllMessagesNo[$key];
		} else {
			return parent::getMessage( $key );
		}
	}
}

?>
