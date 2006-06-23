<?php
/**
 * Tamil (தமிழ்)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( "LanguageUtf8.php" );

if (!$wgCachedMessageArrays) {
	require_once('MessagesTa.php');
}

class LanguageTa extends LanguageUtf8 {
	private $mMessagesTa, $mNamespaceNamesTa = null;

	private $mQuickbarSettingsTa = array(
		"எதுவுமில்லை", "இடம் நிலைத்த", "வலம் நிலைத்த", "இடம் மிதப்பு"
	);
	
	private $mSkinNamesTa = array(
		'standard' => "இயல்பான",
		'nostalgia' => "பசுமை நினைவு (Nostalgia)",
		'cologneblue' => "கொலோன் (Cologne) நீலம் Blue",
		'smarty' => "பாடிங்டன் (Paddington)",
		'montparnasse' => "மொண்ட்பார்னாசே (Montparnasse)",
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesTa;
		$this->mMessagesTa =& $wgAllMessagesTa;

		global $wgMetaNamespace;
		$this->mNamespaceNamesTa = array(
			NS_MEDIA          => 'ஊடகம்',
			NS_SPECIAL        => 'சிறப்பு',
			NS_MAIN           => '',
			NS_TALK           => 'பேச்சு',
			NS_USER           => 'பயனர்',
			NS_USER_TALK      => 'பயனர்_பேச்சு',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => $wgMetaNamespace . '_பேச்சு',
			NS_IMAGE          => 'படிமம்',
			NS_IMAGE_TALK     => 'படிமப்_பேச்சு',
			NS_MEDIAWIKI      => 'மீடியாவிக்கி',
			NS_MEDIAWIKI_TALK => 'மீடியாவிக்கி_பேச்சு',
			NS_TEMPLATE       => 'வார்ப்புரு',
			NS_TEMPLATE_TALK  => 'வார்ப்புரு_பேச்சு',
			NS_HELP           => 'உதவி',
			NS_HELP_TALK      => 'உதவி_பேச்சு',
			NS_CATEGORY       => 'பகுப்பு',
			NS_CATEGORY_TALK  => 'பகுப்பு_பேச்சு',
		);
	}

	function getNamespaces() {
		return $this->mNamespaceNamesTa + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsTa;
	}

	function getSkinNames() {
		return $this->mSkinNamesTa + parent::getSkinNames();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesTa[$key] ) ) {
			return $this->mMessagesTa[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesTa;
	}

	function getNsIndex( $text ) {
		$ns = $this->getNamespaces();

		foreach ( $ns as $i => $n ) {
			if ( strcasecmp( $n, $text ) == 0)
				return $i;
		}

		if ( strcasecmp( 'விக்கிபீடியா', $text) == 0) return NS_PROJECT;
		if ( strcasecmp( 'விக்கிபீடியா_பேச்சு', $text) == 0) return NS_PROJECT_TALK;
		if ( strcasecmp( 'உருவப்_பேச்சு', $text) == 0) return NS_IMAGE_TALK;

		return false;
	}

	function linkTrail() {
		/* Range from U+0B80 to U+0BFF */
		return "/^([\xE0\xAE\x80-\xE0\xAF\xBF]+)(.*)$/sDu";
	}

}

?>
