<?php
/** German (Deutsch)
  *
  * @package MediaWiki
  * @subpackage Language
  *
  * @bug 4563
  */

/** */
require_once( 'LanguageUtf8.php' );

/* private */ $wgNamespaceNamesDe = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Spezial',
	NS_MAIN             => '',
	NS_TALK             => 'Diskussion',
	NS_USER             => 'Benutzer',
	NS_USER_TALK        => 'Benutzer_Diskussion',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => $wgMetaNamespace . '_Diskussion',
	NS_IMAGE            => 'Bild',
	NS_IMAGE_TALK       => 'Bild_Diskussion',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_Diskussion',
	NS_TEMPLATE         => 'Vorlage',
	NS_TEMPLATE_TALK    => 'Vorlage_Diskussion',
	NS_HELP             => 'Hilfe',
	NS_HELP_TALK        => 'Hilfe_Diskussion',
	NS_CATEGORY         => 'Kategorie',
	NS_CATEGORY_TALK    => 'Kategorie_Diskussion'
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsDe = array(
	'Keine', 'Links, fest', 'Rechts, fest', 'Links, schwebend'
);

/* private */ $wgSkinNamesDe = array(
	'standard'      => 'Klassik',
	'nostalgia'     => 'Nostalgie',
	'cologneblue'   => 'Kölnisch Blau',
	'smarty'        => 'Paddington',
	'montparnasse'  => 'Montparnasse',
	'davinci'       => 'DaVinci',
	'mono'          => 'Mono',
	'monobook'      => 'MonoBook',
	'myskin'        => 'MySkin',
	'chick'         => 'Küken'
);


/* private */ $wgBookstoreListDe = array(
	'Verzeichnis lieferbarer Bücher' => 'http://www.buchhandel.de/vlb/vlb.cgi?type=voll&isbn=$1',
	'abebooks.de' => 'http://www.abebooks.de/servlet/BookSearchPL?ph=2&isbn=$1',
	'Amazon.de' => 'http://www.amazon.de/exec/obidos/ISBN=$1',
	'buch.de' => 'http://www.buch.de/de.buch.shop/shop/1/home/schnellsuche/buch/?fqbi=$1',
	'Lehmanns Fachbuchhandlung' => 'http://www.lob.de/cgi-bin/work/suche?flag=new&stich1=$1',
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesDe.php');
}

/** @package MediaWiki */
class LanguageDe extends LanguageUtf8 {

	function getBookstoreList() {
		global $wgBookstoreListDe ;
		return $wgBookstoreListDe ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesDe;
		return $wgNamespaceNamesDe;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsDe;
		return $wgQuickbarSettingsDe;
	}

	function getSkinNames() {
		global $wgSkinNamesDe;
		return $wgSkinNamesDe;
	}

	function formatMonth( $month, $format ) {
		return $this->getMonthAbbreviation( $month );
	}

	function formatDay( $day, $format ) {
		return parent::formatDay( $day, $format ) . '.';
	}

	function getMessage( $key ) {
		global $wgAllMessagesDe;
		if( isset( $wgAllMessagesDe[$key] ) ) {
			return $wgAllMessagesDe[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function separatorTransformTable() {
		return array(',' => '.', '.' => ',' );
	}

	function linkTrail() {
		return '/^([äöüßa-z]+)(.*)$/sDu';
	}


}

?>
