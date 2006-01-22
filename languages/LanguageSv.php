<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */
#
# Swedish localisation for MediaWiki
#

require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesSv = array(
	NS_MEDIA            => "Media",
	NS_SPECIAL          => "Special",
	NS_MAIN	            => "",
	NS_TALK	            => "Diskussion",
	NS_USER             => "Användare",
	NS_USER_TALK        => "Användardiskussion",
	NS_PROJECT	        => $wgMetaNamespace,
	NS_PROJECT_TALK     => $wgMetaNamespace . "diskussion",
	NS_IMAGE            => "Bild",
	NS_IMAGE_TALK       => "Bilddiskussion",
	NS_MEDIAWIKI        => "MediaWiki",
	NS_MEDIAWIKI_TALK   => "MediaWiki_diskussion",
	NS_TEMPLATE         => "Mall",
	NS_TEMPLATE_TALK    => "Malldiskussion",
	NS_HELP             => "Hjälp",
	NS_HELP_TALK        => "Hjälp_diskussion",
	NS_CATEGORY	    => "Kategori",
	NS_CATEGORY_TALK    => "Kategoridiskussion"
) + $wgNamespaceNamesEn;

/* inherit standard defaults */

/* private */ $wgQuickbarSettingsSv = array(
	"Ingen",
	"Fast vänster",
	"Fast höger",
	"Flytande vänster"
);

/* private */ $wgSkinNamesSv = array(
	'standard' => "Standard",
	'nostalgia' => "Nostalgi",
	'cologneblue' => "Cologne Blå",
) + $wgSkinNamesEn;


if (!$wgCachedMessageArrays) {
	require_once('MessagesSv.php');
}

class LanguageSv extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesSv;
		return $wgNamespaceNamesSv;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsSv;
		return $wgQuickbarSettingsSv;
	}

	function getSkinNames() {
		global $wgSkinNamesSv;
		return $wgSkinNamesSv;
	}

	// "." is used as the character to separate the
	// hours from the minutes in the date output
	function timeSeparator() {
		return '.';
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

	function getMessage( $key ) {
		global $wgAllMessagesSv;
		if( isset( $wgAllMessagesSv[$key] ) ) {
			return $wgAllMessagesSv[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	var $digitTransTable = array(
		',' => "\xc2\xa0", // @bug 2749
		'.' => ','
	);

	function formatNum( $number, $year = false ) {
		return $year ? $number : strtr($this->commafy($number), $this->digitTransTable);
	}
}

?>
