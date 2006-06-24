<?php
/** Latin (lingua Latina)
  *
  * @package MediaWiki
  * @subpackage Language
  */

if (!$wgCachedMessageArrays) {
	require_once('MessagesLa.php');
}

class LanguageLa extends LanguageUtf8 {
	private $mMessagesLa, $mNamespaceNamesLa = null;

	private $mQuickbarSettingsLa = array(
		'Nullus', 'Constituere a sinistra', 'Constituere a dextra', 'Innens a sinistra'
	);
	
	private $mSkinNamesLa = array(
		'standard' => 'Norma',
		'nostalgia' => 'Nostalgia',
		'cologneblue' => 'Caerulus Colonia'
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesLa;
		$this->mMessagesLa =& $wgAllMessagesLa;

		global $wgMetaNamespace;
		$this->mNamespaceNamesLa = array(
			NS_SPECIAL        => 'Specialis',
			NS_MAIN           => '',
			NS_TALK           => 'Disputatio',
			NS_USER           => 'Usor',
			NS_USER_TALK      => 'Disputatio_Usoris',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => 'Disputatio_' . $this->convertGrammar( $wgMetaNamespace, 'genitive' ),
			NS_IMAGE          => 'Imago',
			NS_IMAGE_TALK     => 'Disputatio_Imaginis',
			NS_MEDIAWIKI      => 'MediaWiki',
			NS_MEDIAWIKI_TALK => 'Disputatio_MediaWiki',
			NS_TEMPLATE       => 'Formula',
			NS_TEMPLATE_TALK  => 'Disputatio_Formulae',
			NS_HELP           => 'Auxilium',
			NS_HELP_TALK      => 'Disputatio_Auxilii',
			NS_CATEGORY       => 'Categoria',
			NS_CATEGORY_TALK  => 'Disputatio_Categoriae',
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesLa + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsLa;
	}

	function getSkinNames() {
		return $this->mSkinNamesLa + parent::getSkinNames();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesLa[$key] ) ) {
			return $this->mMessagesLa[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesLa;
	}

	function getNsIndex( $text ) {
		global $wgMetaNamespace;

		foreach ( $this->mNamespaceNamesLa as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}

		# Backwards compatibility hacks
		if( $wgMetaNamespace == 'Vicipaedia' || $wgMetaNamespace == 'Victionarium' ) {
			if( 0 == strcasecmp( 'Disputatio_Wikipedia', $text ) ) return NS_PROJECT_TALK;
		}
		return false;
	}

	/**
	 * Convert from the nominative form of a noun to some other case
	 *
	 * Just used in a couple places for sitenames; special-case as necessary.
	 * Rules are far from complete.
	 *
	 * Cases: genitive
	 */
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset($wgGrammarForms['la'][$case][$word]) ) {
			return $wgGrammarForms['la'][$case][$word];
		}

		switch ( $case ) {
		case 'genitive':
			// 1st and 2nd declension singular only.
			$in  = array( '/a$/', '/u[ms]$/', '/tio$/' );
			$out = array( 'ae',   'i',        'tionis' );
			return preg_replace( $in, $out, $word );
		default:
			return $word;
		}
	}

}


?>
