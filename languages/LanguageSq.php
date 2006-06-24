<?php
/** Albanian (Shqip)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( "LanguageUtf8.php" );

if (!$wgCachedMessageArrays) {
	require_once('MessagesSq.php');
}

class LanguageSq extends LanguageUtf8 {
	private $mMessagesSq, $mNamespaceNamesSq = null;

	private $mQuickbarSettingsSq = array(
		'Asnjë', 'Lidhur majtas', 'Lidhur djathtas', 'Pezull majtas', 'Pezull djathtas'
	);
	
	private $mSkinNamesSq = array(
		'standard' => "Standarte",
		'nostalgia' => "Nostalgjike",
		'cologneblue' => "Kolonjë Blu"
	);

	private $mDateFormatsSq = array(
		MW_DATE_DEFAULT => 'No preference',
		MW_DATE_DMY => '16:12, 15 January 2001',
		MW_DATE_ISO => '2001-01-15 16:12:34'
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesSq;
		$this->mMessagesSq =& $wgAllMessagesSq;

		global $wgMetaNamespace;
		$this->mNamespaceNamesSq = array(
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
		);
	}

	function getNamespaces() {
		return $this->mNamespaceNamesSq + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsSq;
	}

	function getSkinNames() {
		return $this->mSkinNamesSq + parent::getSkinNames();
	}

	function getDateFormats() {
		return $this->mDateFormatsSq;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesSq[$key] ) ) {
			return $this->mMessagesSq[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesSq;
	}

	function getNsIndex( $text ) {
		foreach ( $this->mNamespaceNamesSq as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		# Compatbility with alt names:
		if( 0 == strcasecmp( "Perdoruesi", $text ) ) return NS_USER;
		if( 0 == strcasecmp( "Perdoruesi_diskutim", $text ) ) return NS_USER_TALK;
		return false;
	}

	function timeDateSeparator( $format ) {
		return ' ';
	}


	function timeBeforeDate( $format ) {
		return false;
	}

	function separatorTransformTable() {
		return array(',' => '.', '.' => ',' );
	}

}
?>
