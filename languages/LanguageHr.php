<?php
/** Croatian (hrvatski)
  *
  * @package MediaWiki
  * @subpackage Language
  */

/** */
require_once( 'LanguageUtf8.php' );

/* private */ $wgNamespaceNamesHr = array(
 NS_MEDIA           => "Mediji",
 NS_SPECIAL         => "Posebno",
 NS_MAIN            => "",
 NS_TALK            => "Razgovor",
 NS_USER            => "Suradnik",
 NS_USER_TALK       => "Razgovor_sa_suradnikom",
 NS_PROJECT         => $wgMetaNamespace,
 NS_PROJECT_TALK    => "Razgovor_" . $wgMetaNamespace,
 NS_IMAGE           => "Slika",
 NS_IMAGE_TALK      => "Razgovor_o_slici",
 NS_MEDIAWIKI       => "MediaWiki",
 NS_MEDIAWIKI_TALK  => "MediaWiki_razgovor",
 NS_TEMPLATE        => "Predložak",
 NS_TEMPLATE_TALK   => "Razgovor_o_predlošku",
 NS_HELP            => "Pomoć",
 NS_HELP_TALK       => "Razgovor_o_pomoći",
 NS_CATEGORY        => "Kategorija",
 NS_CATEGORY_TALK   => "Razgovor_o_kategoriji"
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsHr = array(
 "Bez", "Lijevo nepomično", "Desno nepomično", "Lijevo leteće"
);

/* private */ $wgSkinNamesHr = array(
 'standard'  => "Standardna",
 'nostalgia'  => "Nostalgija",
 'cologneblue'  => "Kölnska plava",
 'smarty'  => "Paddington",
 'montparnasse'  => "Montparnasse",
 'davinci'  => "DaVinci",
 'mono'   => "Mono",
 'monobook'  => "MonoBook",
 "myskin"  => "MySkin",
 "chick"  => "Chick"
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesHr.php');
}

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class LanguageHr extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesHr;
		return $wgNamespaceNamesHr;
	}

	function getDateFormats() {
		return false;
	}

	function getQuickbarSettings() {
	 	global $wgQuickbarSettingsHr;
		return $wgQuickbarSettingsHr;
 	}

	function getSkinNames() {
		global $wgSkinNamesHr;
		return $wgSkinNamesHr;
 	}

	function date( $ts, $adj = false, $format = true, $timecorrection = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts, $timecorrection ); }

		$d = (0 + substr( $ts, 6, 2 )) . ". " .
		$this->getMonthName( substr( $ts, 4, 2 ) ) .
		  " " .
		  substr( $ts, 0, 4 ) . "." ;
		return $d;
	}

	function getMessage( $key ) {
		global $wgAllMessagesHr;
		if( isset( $wgAllMessagesHr[$key] ) ) {
			return $wgAllMessagesHr[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

 	function formatNum( $number, $year = false ) {
		return $year ? $number : strtr($this->commafy($number), '.,', ',.' );
 	}

 	function fallback8bitEncoding() {
		return "iso-8859-2";
 	}

	function convertPlural( $count, $wordform1, $wordform2, $wordform3) {
		$count = str_replace ('.', '', $count);
		if ($count > 10 && floor(($count % 100) / 10) == 1) {
			return $wordform3;
		} else {
			switch ($count % 10) {
				case 1: return $wordform1;
				case 2:
				case 3:
				case 4: return $wordform2;
				default: return $wordform3;
			}
		}
	}

}

?>
