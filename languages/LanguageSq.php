<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

require_once("LanguageUtf8.php");

/* private */ $wgNamespaceNamesSq = array(
	NS_MEDIA          => "Media",
	NS_SPECIAL        => "Speciale",
	NS_MAIN           => "",
	NS_TALK           => "Diskutim",
	NS_USER           => "Përdoruesi",
	NS_USER_TALK      => "Përdoruesi_diskutim",
	NS_PROJECT        => $wgMetaNamespace,
	NS_PROJECT_TALK   => $wgMetaNamespace . "_diskutim",
	NS_IMAGE          => "Figura",
	NS_IMAGE_TALK     => "Figura_diskutim",
	NS_MEDIAWIKI      => "MediaWiki",
	NS_MEDIAWIKI_TALK => "MediaWiki_diskutim",
	NS_TEMPLATE       => "Stampa",
	NS_TEMPLATE_TALK  => "Stampa_diskutim",
	NS_HELP           => 'Ndihmë',
	NS_HELP_TALK      => 'Ndihmë_diskutim'
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsSq = array(
	"Asgjë", "Lidhur majtas", "Lidhur djathtas", "Fluturo majtas"
);

/* private */ $wgSkinNamesSq = array(
	'standard' => "Standarte",
	'nostalgia' => "Nostalgjike",
	'cologneblue' => "Kolonjë Blu"
) + $wgSkinNamesEn;


/* private */ $wgDateFormatsSq = array(
#	"Pa preferencë",
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesSq.php');
}

class LanguageSq extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesSq;
		return $wgNamespaceNamesSq;
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesSq;
		foreach ( $wgNamespaceNamesSq as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		# Compatbility with alt names:
		if( 0 == strcasecmp( "Perdoruesi", $text ) ) return 2;
		if( 0 == strcasecmp( "Perdoruesi_diskutim", $text ) ) return 3;
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsSq;
		return $wgQuickbarSettingsSq;
	}

	function getSkinNames() {
		global $wgSkinNamesSq;
		return $wgSkinNamesSq;
	}

	function getDateFormats() {
		global $wgDateFormatsSq;
		return $wgDateFormatsSq;
	}

	# localised date and time
	function date( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = substr( $ts, 0, 4 ) . " " .
			$this->getMonthName( substr( $ts, 4, 2 ) ) . " ".
			(0 + substr( $ts, 6, 2 ));
		return $d;
	}

	function time( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$t = substr( $ts, 8, 2 ) . ":" . substr( $ts, 10, 2 );
		return $t;
	}

	function timeanddate( $ts, $adj = false ) {
		return $this->date( $ts, $adj ) . " " . $this->time( $ts, $adj );
	}

	function getMessage( $key ) {
		global $wgAllMessagesSq;
		if(array_key_exists($key, $wgAllMessagesSq))
			return $wgAllMessagesSq[$key];
		else
			return parent::getMessage($key);
	}

	function formatNum( $number ) {
		global $wgTranslateNumerals;
		return $wgTranslateNumerals ? strtr($number, '.,', ',.' ) : $number;
	}

}

?>
