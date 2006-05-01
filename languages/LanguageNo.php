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
	"Frida" => "http://wo.uio.no/as/WebObjects/frida.woa/wa/fres?action=sok&isbn=$1&visParametre=1&sort=alfabetisk&bs=50",
	"Bibsys" => "http://ask.bibsys.no/ask/action/result?cmd=&kilde=biblio&fid=isbn&term=$1&op=and&fid=bd&term=&arstall=&sortering=sortdate-&treffPrSide=50",
	"Akademika" => "http://www.akademika.no/sok.php?ts=4&sok=$1",
	"Haugenbok" => "http://www.haugenbok.no/resultat.cfm?st=extended&isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
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

	function separatorTransformTable() {
		return array(',' => "\xc2\xa0", '.' => ',' );
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
