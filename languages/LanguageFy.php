<?php
/** Frisian (Frysk)
 *
 * @package MediaWiki
 * @subpackage Language
 *
 * @author Niklas Laxström
 */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesFy.php');
}

class LanguageFy extends LanguageUtf8 {
	private $mMessagesFy, $mNamespaceNamesFy = null;

	private $mQuickbarSettingsFy = array(
		'Ut', 'Lofts fêst', 'Rjochts fêst', 'Lofts sweevjend'
	);
	
	private $mSkinNamesFy = array(
		'standard' => 'Standert',
		'nostalgia' => 'Nostalgy',
	);
		
	private $mDateFormatsFy = array(
		'Gjin foarkar',
		'16.12, jan 15, 2001',
		'16.12, 15 jan 2001',
		'16.12, 2001 jan 15',
		'ISO 8601' => '2001-01-15 16:12:34'
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesFy;
		$this->mMessagesFy =& $wgAllMessagesFy;

		global $wgMetaNamespace;
		$this->mNamespaceNamesFy = array(
			NS_MEDIA          => 'Media',
			NS_SPECIAL        => 'Wiki',
			NS_MAIN           => '',
			NS_TALK           => 'Oerlis',
			NS_USER           => 'Meidogger',
			NS_USER_TALK      => 'Meidogger_oerlis',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => $wgMetaNamespace . '_oerlis',
			NS_IMAGE          => 'Ofbyld',
			NS_IMAGE_TALK     => 'Ofbyld_oerlis',
			NS_MEDIAWIKI      => 'MediaWiki',
			NS_MEDIAWIKI_TALK => 'MediaWiki_oerlis',
			NS_TEMPLATE       => 'Berjocht',
			NS_TEMPLATE_TALK  => 'Berjocht_oerlis',
			NS_HELP           => 'Hulp',
			NS_HELP_TALK      => 'Hulp_oerlis',
			NS_CATEGORY       => 'Kategory',
			NS_CATEGORY_TALK  => 'Kategory_oerlis'
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesFy + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsFy;
	}

	function getSkinNames() {
		return $this->mSkinNamesFy + parent::getSkinNames();
	}

	function getDateFormats() {
		return $this->mDateFormatsFy;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesFy[$key] ) ) {
			return $this->mMessagesFy[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesFy;
	}

	function getNsIndex( $text ) {
		foreach ( $this->mNamespaceNamesFy as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		if ( 0 == strcasecmp( "Brûker", $text ) ) return 2;
		if ( 0 == strcasecmp( "Brûker_oerlis", $text ) ) return 3;
		return false;
	}

	function timeSeparator( $format ) {
		return '.';
	}

	function formatMonth( $month, $format ) {
		return $this->getMonthAbbreviation( $month );
	}

	function separatorTransformTable() {
		return array(',' => '.', '.' => ',' );
	}

	function linkTrail() {
		return '/^([a-zàáèéìíòóùúâêîôûäëïöü]+)(.*)$/sDu';
	}

}

?>
