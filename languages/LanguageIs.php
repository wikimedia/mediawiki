<?php
/** Icelandic (Íslenska)
 *
 * @package MediaWiki
 * @subpackage Language
 */

# Most of this was written by Ævar Arnfjörð Bjarmason <avarab@gmail.com>

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesIs.php');
}

class LanguageIs extends LanguageUtf8 {
	private $mMessagesIs, $mNamespaceNamesIs = null;

	private $mQuickbarSettingsIs = array(
		'Sleppa', 'Fast vinstra megin', 'Fast hægra megin', 'Fljótandi til vinstri'
	);
	
	private $mSkinNamesIs = array(
		'standard'	=> 'Klassískt',
		'nostalgia'	=> 'Gamaldags',
		'cologneblue'	=> 'Kölnarblátt',
		'myskin'	=> 'Mitt þema',
	);
	
	private $mDateFormatsIs = array(
		'Sjálfgefið',
		'15. janúar 2001 kl. 16:12',
		'15. jan. 2001 kl. 16:12',
		'16:12, 15. janúar 2001',
		'16:12, 15. jan. 2001',
		'ISO 8601' => '2001-01-15 16:12:34'
	);

	private $mMagicWordsIs = array(
		MAG_REDIRECT => array( 0, '#tilvísun', '#TILVÍSUN', '#redirect' ), // MagicWord::initRegex() sucks
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesIs;
		$this->mMessagesIs =& $wgAllMessagesIs;

		global $wgMetaNamespace;
		$this->mNamespaceNamesIs = array(
			NS_MEDIA          => 'Miðill',
			NS_SPECIAL        => 'Kerfissíða',
			NS_MAIN           => '',
			NS_TALK           => 'Spjall',
			NS_USER           => 'Notandi',
			NS_USER_TALK      => 'Notandaspjall',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => $wgMetaNamespace . 'spjall',
			NS_IMAGE          => 'Mynd',
			NS_IMAGE_TALK     => 'Myndaspjall',
			NS_MEDIAWIKI      => 'Melding',
			NS_MEDIAWIKI_TALK => 'Meldingarspjall',
			NS_TEMPLATE       => 'Snið',
			NS_TEMPLATE_TALK  => 'Sniðaspjall',
			NS_HELP           => 'Hjálp',
			NS_HELP_TALK      => 'Hjálparspjall',
			NS_CATEGORY       => 'Flokkur',
			NS_CATEGORY_TALK  => 'Flokkaspjall'
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesIs + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsIs;
	}

	function getSkinNames() {
		return $this->mSkinNamesIs + parent::getSkinNames();
	}

	function getDateFormats() {
		return $this->mDateFormatsIs;
	}

	function &getMagicWords()  {
		$t = $this->mMagicWordsIs + parent::getMagicWords();
		return $t;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesIs[$key] ) ) {
			return $this->mMessagesIs[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesIs;
	}

	function date( $ts, $adj = false, $format = true) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); } # Adjust based on the timezone setting.
		$format = $this->dateFormat($format);

		switch( $format ) {
			# 15. jan. 2001 kl. 16:12 || 16:12, 15. jan. 2001
			case '2': case '4': return (0 + substr( $ts, 6, 2 )) . '. ' .
				$this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) . '. ' .
				substr($ts, 0, 4);
			# 2001-01-15 16:12:34
			case 'ISO 8601': return substr($ts, 0, 4). '-' . substr($ts, 4, 2). '-' .substr($ts, 6, 2);

			# 15. janúar 2001 kl. 16:12 || 16:12, 15. janúar 2001
			default: return (0 + substr( $ts, 6, 2 )) . '. ' .
				$this->getMonthName( substr( $ts, 4, 2 ) ) . ' ' .
				substr($ts, 0, 4);
		}

	}

	function time($ts, $adj = false, $format = true) {
		global $wgUser;
		if ( $adj ) { $ts = $this->userAdjust( $ts ); } # Adjust based on the timezone setting.

		$format = $this->dateFormat($format);

		switch( $format ) {
			# 2001-01-15 16:12:34
			case 'ISO 8601': return substr( $ts, 8, 2 ) . ':' . substr( $ts, 10, 2 ) . ':' . substr( $ts, 12, 2 );
			default: return substr( $ts, 8, 2 ) . ':' . substr( $ts, 10, 2 );
		}

	}

	function timeanddate( $ts, $adj = false, $format = true) {
		global $wgUser;

		$format = $this->dateFormat($format);

		switch ( $format ) {
			# 16:12, 15. janúar 2001 || 16:12, 15. jan. 2001
			case '3': case '4': return $this->time( $ts, $adj, $format ) . ', ' . $this->date( $ts, $adj, $format );
			# 2001-01-15 16:12:34
			case 'ISO 8601': return $this->date( $ts, $adj, $format ) . ' ' . $this->time( $ts, $adj, $format );
			# 15. janúar 2001 kl. 16:12 || 15. jan. 2001 kl. 16:12
			default: return $this->date( $ts, $adj, $format ) . ' kl. ' . $this->time( $ts, $adj, $format );

		}

	}

	/**
	 * The Icelandic number style uses dots where English would use commas
	 * and commas where English would use dots, e.g. 201.511,17 not 201,511.17
	 */
	function separatorTransformTable() {
		return array(',' => '.', '.' => ',' );
	}

	function linkPrefixExtension() {
		// return '/^(.*?)([áÁðÐéÉíÍóÓúÚýÝþÞæÆöÖA-Za-z-–]+)$/sDu';
		return true;
	}

	function linkTrail() {
		return '/^([áðéíóúýþæöa-z-–]+)(.*)$/sDu';
	}

}

?>
