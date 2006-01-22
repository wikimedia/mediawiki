<?php
/** Faroese (Føroyskt)
  *
  * @package MediaWiki
  * @subpackage Language
  */
require_once( 'LanguageUtf8.php');

/* private */ $wgNamespaceNamesFo = array(
	NS_MEDIA            => "Miðil",
	NS_SPECIAL          => "Serstakur",
	NS_MAIN             => "",
	NS_TALK             => "Kjak",
	NS_USER             => "Brúkari",
	NS_USER_TALK        => "Brúkari_kjak",
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => $wgMetaNamespace . '_kjak',
	NS_IMAGE            => "Mynd",
	NS_IMAGE_TALK       => "Mynd_kjak",
	NS_MEDIAWIKI        => "MidiaWiki",
	NS_MEDIAWIKI_TALK   => "MidiaWiki_kjak",
	NS_TEMPLATE         => "Fyrimynd",
	NS_TEMPLATE_TALK    => "Fyrimynd_kjak",
	NS_HELP             => "Hjálp",
	NS_HELP_TALK        => "Hjálp_kjak",
	NS_CATEGORY         => "Bólkur",
	NS_CATEGORY_TALK    => "Bólkur_kjak"
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsFo = array(
	"Eingin", "Fast vinstru", "Fast høgru", "Flótandi vinstru"
);

/* private */ $wgSkinNamesFo = array(
	"Standardur", "Nostalgiskur", "Cologne-bláur", "Paddington", "Montparnasse"
);

/* private */ $wgDateFormatsFo = array(
#	"Ongi forrættindi",
);

/* private */ $wgBookstoreListFo = array(
	"Bokasolan.fo" => "http://www.bokasolan.fo/vleitari.asp?haattur=bok.alfa&Heiti=&Hovindur=&Forlag=&innbinding=Oell&bolkur=Allir&prisur=Allir&Aarstal=Oell&mal=Oell&status=Oell&ISBN=$1",
) + $wgBookstoreListEn;

if (!$wgCachedMessageArrays) {
	require_once('MessagesFo.php');
}

class LanguageFo extends LanguageUtf8 {

	function getBookstoreList () {
		global $wgBookstoreListFo ;
		return $wgBookstoreListFo ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesFo;
		return $wgNamespaceNamesFo;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsFo;
		return $wgQuickbarSettingsFo;
	}

	function getSkinNames() {
		global $wgSkinNamesFo;
		return $wgSkinNamesFo;
	}

	function getDateFormats() {
		global $wgDateFormatsFo;
		return $wgDateFormatsFo;
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
		global $wgAllMessagesFo;
		if( isset( $wgAllMessagesFo[$key] ) ) {
			return $wgAllMessagesFo[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

}

?>
