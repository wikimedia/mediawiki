<?php
/**
 * Swedish (Svenska)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( "LanguageUtf8.php" );

if (!$wgCachedMessageArrays) {
	require_once('MessagesSv.php');
}

class LanguageSv extends LanguageUtf8 {
	private $mMessagesSv, $mNamespaceNamesSv;
	
	private $mQuickbarSettingsSv = array(
		"Ingen",
		"Fast vänster",
		"Fast höger",
		"Flytande vänster"
	);
	
	private $mSkinNamesSv = array(
		'standard' => "Standard",
		'nostalgia' => "Nostalgi",
		'cologneblue' => "Cologne Blå",
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesSv;
		$this->mMessagesSv =& $wgAllMessagesSv;

		global $wgMetaNamespace;
		$this->mNamespaceNamesSv = array(
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
			NS_CATEGORY         => "Kategori",
			NS_CATEGORY_TALK    => "Kategoridiskussion"
		);
	}

	function getNamespaces() {
		return $this->mNamespaceNamesSv + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsSv;
	}

	function getSkinNames() {
		return $this->mSkinNamesSv + parent::getSkinNames();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesSv[$key] ) ) {
			return $this->mMessagesSv[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesSv;
	}

	function linkTrail() {
		return '/^([a-zåäöéÅÄÖÉ]+)(.*)$/sDu';
	}


	function separatorTransformTable() {
		return array(
			',' => "\xc2\xa0", // @bug 2749
			'.' => ','
		);
	}

	// "." is used as the character to separate the
	// hours from the minutes in the date output
	function timeSeparator( $format ) {
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

}
?>
