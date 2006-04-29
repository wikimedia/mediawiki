<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */
#
# Hungarian localisation for MediaWiki
#

require_once("LanguageUtf8.php");

# suffixed project name (Wikipédia -> Wikipédiá) -- ról, ba, k
$wgSitenameROL = $wgSitename . "ról";
$wgSitenameBA = $wgSitename . "ba";
$wgSitenameK = $wgSitename . "k";
if( 0 == strcasecmp( "Wikipédia", $wgSitename ) ) {
	$wgSitenameROL = "Wikipédiáról";
	$wgSitenameBA  = "Wikipédiába";
	$wgSitenameK   = "Wikipédiák";

} elseif( 0 == strcasecmp( "Wikidézet", $wgSitename ) ) {
	$wgSitenameROL = "Wikidézetről";
	$wgSitenameBA  = "Wikidézetbe";
	$wgSitenameK   = "Wikidézetek";

} elseif( 0 == strcasecmp( "Wikiszótár", $wgSitename ) ) {
	$wgSitenameROL = "Wikiszótárról";
	$wgSitenameBA  = "Wikiszótárba";
	$wgSitenameK   = "Wikiszótárak";

} elseif( 0 == strcasecmp( "Wikikönyvek", $wgSitename ) ) {
	$wgSitenameROL = "Wikikönyvekről";
	$wgSitenameBA  = "Wikikönyvekbe";
	$wgSitenameK   = "Wikikönyvek";
}

/* private */ $wgNamespaceNamesHu = array(
	NS_MEDIA			=> "Média",
	NS_SPECIAL			=> "Speciális",
	NS_MAIN				=> "",
	NS_TALK				=> "Vita",
	NS_USER				=> "User",
	NS_USER_TALK		=> "User_vita",
	NS_PROJECT			=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> $wgMetaNamespace . "_vita",
	NS_IMAGE			=> "Kép",
	NS_IMAGE_TALK		=> "Kép_vita",
	NS_MEDIAWIKI		=> "MediaWiki",
	NS_MEDIAWIKI_TALK 	=> "MediaWiki_vita",
	NS_TEMPLATE			=> "Sablon",
	NS_TEMPLATE_TALK 	=> "Sablon_vita",
	NS_HELP				=> "Segítség",
	NS_HELP_TALK		=> "Segítség_vita",
	NS_CATEGORY			=> "Kategória",
	NS_CATEGORY_TALK	=> "Kategória_vita"
) + $wgNamespaceNamesEn;


/* private */ $wgQuickbarSettingsHu = array(
	"Nincs", "Fix baloldali", "Fix jobboldali", "Lebegő baloldali"
);

/* private */ $wgSkinNamesHu = array(
	'standard' => "Alap",
	'nostalgia' => "Nosztalgia",
	'cologneblue' => "Kölni kék"
) + $wgSkinNamesEn;


/* private */ $wgDateFormatsHu = array(
#	"Mindegy",
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesHu.php');
}

class LanguageHu extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesHu;
		return $wgNamespaceNamesHu;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsHu;
		return $wgQuickbarSettingsHu;
	}

	function getSkinNames() {
		global $wgSkinNamesHu;
		return $wgSkinNamesHu;
	}

	function getDateFormats() {
		global $wgDateFormatsHu;
		return $wgDateFormatsHu;
	}

	function getMessage( $key ) {
		global $wgAllMessagesHu;
		if(array_key_exists($key, $wgAllMessagesHu))
			return $wgAllMessagesHu[$key];
		else
			return parent::getMessage($key);
	}

	function fallback8bitEncoding() {
		return "iso8859-2";
	}

	# localised date and time
	function date( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = substr( $ts, 0, 4 ) . ". " .
		$this->getMonthName( substr( $ts, 4, 2 ) ) . " ".
			(0 + substr( $ts, 6, 2 )) . ".";
		return $d;
	}

	function timeanddate( $ts, $adj = false ) {
		return $this->date( $ts, $adj ) . ", " . $this->time( $ts, $adj );
	}

	function separatorTransformTable() {
		return array(',' => "\xc2\xa0", '.' => ',' );
	}

}

?>
