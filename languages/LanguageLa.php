<?php
/** Latin (lingua Latina)
  *
  * @package MediaWiki
  * @subpackage Language
  */

/* private */ $wgNamespaceNamesLa = array(
	NS_SPECIAL        => 'Specialis',
	NS_MAIN           => '',
	NS_TALK           => 'Disputatio',
	NS_USER           => 'Usor',
	NS_USER_TALK      => 'Disputatio_Usoris',
	NS_PROJECT        => $wgMetaNamespace,
	NS_PROJECT_TALK   => FALSE,  # Set in constructor
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
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsLa = array(
	"Nullus", "Constituere a sinistra", "Constituere a dextra", "Innens a sinistra"
);

/* private */ $wgSkinNamesLa = array(
	'standard' => 'Norma',
	'nostalgia' => 'Nostalgia',
	'cologneblue' => 'Caerulus Colonia'
) + $wgSkinNamesEn;

if (!$wgCachedMessageArrays) {
	require_once('MessagesLa.php');
}

require_once( "LanguageUtf8.php" );

class LanguageLa extends LanguageUtf8 {
	function LanguageLa() {
		global $wgNamespaceNamesLa, $wgMetaNamespace;
		LanguageUtf8::LanguageUtf8();
		$wgNamespaceNamesLa[NS_PROJECT_TALK] = 'Disputatio_' .
			$this->convertGrammar( $wgMetaNamespace, 'genitive' );
	}

	function getNamespaces() {
		global $wgNamespaceNamesLa;
		return $wgNamespaceNamesLa;
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesLa;
		global $wgMetaNamespace;

		foreach ( $wgNamespaceNamesLa as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}

		# Backwards compatibility hacks
		if( $wgMetaNamespace == 'Vicipaedia' || $wgMetaNamespace == 'Victionarium' ) {
			if( 0 == strcasecmp( 'Disputatio_Wikipedia', $text ) ) return NS_PROJECT_TALK;
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsLa;
		return $wgQuickbarSettingsLa;
	}

	function getSkinNames() {
		global $wgSkinNamesLa;
		return $wgSkinNamesLa;
	}

	function getMessage( $key ) {
		global $wgAllMessagesLa;
		if( isset( $wgAllMessagesLa[$key] ) ) {
			return $wgAllMessagesLa[$key];
		}
		return parent::getMessage( $key );
	}

	/**
	 * Convert from the nominative form of a noun to some other case
	 *
	 * Just used in a couple places for sitenames; special-case as necessary.
	 * Rules are far from complete.
	 */
	function convertGrammar( $word, $case ) {
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
