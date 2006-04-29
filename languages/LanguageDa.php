<?php
/** Danish (Dansk)
  *
  * @package MediaWiki
  * @subpackage Language
  */

/** */
require_once( 'LanguageUtf8.php' );

/* private */ $wgNamespaceNamesDa = array(
	NS_MEDIA			=> 'Media',
	NS_SPECIAL			=> 'Speciel',
	NS_MAIN				=> '',
	NS_TALK				=> 'Diskussion',
	NS_USER				=> 'Bruger',
	NS_USER_TALK		=> 'Bruger_diskussion',
	NS_PROJECT			=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> $wgMetaNamespace.'_diskussion',
	NS_IMAGE			=> 'Billede',
	NS_IMAGE_TALK		=> 'Billede_diskussion',
	NS_MEDIAWIKI		=> 'MediaWiki',
	NS_MEDIAWIKI_TALK	=> 'MediaWiki_diskussion',
	NS_TEMPLATE  		=> 'Skabelon',
	NS_TEMPLATE_TALK	=> 'Skabelon_diskussion',
	NS_HELP				=> 'Hjælp',
	NS_HELP_TALK		=> 'Hjælp_diskussion',
	NS_CATEGORY			=> 'Kategori',
	NS_CATEGORY_TALK	=> 'Kategori_diskussion'

) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsDa = array(
	'Ingen', 'Fast venstre', 'Fast højre', 'Flydende venstre'
);

/* private */ $wgSkinNamesDa = array(
	'standard' => 'Klassisk',
	'nostalgia' => 'Nostalgi',
	'cologneblue' => 'Cologne-blå',
) + $wgSkinNamesEn;

/* private */ $wgDateFormatsDa = array();


/* private */ $wgBookstoreListDa = array(
	"Bibliotek.dk" => "http://bibliotek.dk/vis.php?base=dfa&origin=kommando&field1=ccl&term1=is=$1&element=L&start=1&step=10",
	"Bogguide.dk" => "http://www.bogguide.dk/find_boeger_bog.asp?ISBN=$1",
) + $wgBookstoreListEn;

if (!$wgCachedMessageArrays) {
	require_once('MessagesDa.php');
}

/** @package MediaWiki */
class LanguageDa extends LanguageUtf8 {

	function getBookstoreList () {
		global $wgBookstoreListDa ;
		return $wgBookstoreListDa ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesDa;
		return $wgNamespaceNamesDa;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsDa;
		return $wgQuickbarSettingsDa;
	}

	function getSkinNames() {
		global $wgSkinNamesDa;
		return $wgSkinNamesDa;
	}

	function getDateFormats() {
		global $wgDateFormatsDa;
		return $wgDateFormatsDa;
	}

	function date( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . ". " .
		  $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) . " " .
		  substr( $ts, 0, 4 );
		return $d;
	}

	function timeanddate( $ts, $adj = false ) {
		return $this->date( $ts, $adj ) . " kl. " . $this->time( $ts, $adj );
	}

	function getMessage( $key ) {
		global $wgAllMessagesDa;
		if( isset( $wgAllMessagesDa[$key] ) ) {
			return $wgAllMessagesDa[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function separatorTransformTable() {
		return array(',' => '.', '.' => ',' );
	}

}

?>
