<?php
/** French (Français)
 *
 * @package MediaWiki
 * @subpackage Language
 *
 */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesFr.php');
}

class LanguageFr extends LanguageUtf8 {
	private $mMessagesFr, $mNamespaceNamesFr = null;

	private $mQuickbarSettingsFr = array(
		'Aucune', 'Gauche', 'Droite', 'Flottante à gauche'
	);
	
	private $mSkinNamesFr = array(
		'standard'  => 'Standard',
		'nostalgia' => 'Nostalgie',
	);
	
	private $mBookstoreListFr = array(
		'Amazon.fr'    => 'http://www.amazon.fr/exec/obidos/ISBN=$1',
		'alapage.fr'   => 'http://www.alapage.com/mx/?tp=F&type=101&l_isbn=$1&donnee_appel=ALASQ&devise=&',
		'fnac.com'     => 'http://www3.fnac.com/advanced/book.do?isbn=$1',
		'chapitre.com' => 'http://www.chapitre.com/frame_rec.asp?isbn=$1',
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesFr;
		$this->mMessagesFr =& $wgAllMessagesFr;

		global $wgMetaNamespace;
		$this->mNamespaceNamesFr = array(
			NS_MEDIA          => 'Media',
			NS_SPECIAL        => 'Special',
			NS_MAIN           => '',
			NS_TALK           => 'Discuter',
			NS_USER           => 'Utilisateur',
			NS_USER_TALK      => 'Discussion_Utilisateur',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => 'Discussion_' . $wgMetaNamespace,
			NS_IMAGE          => 'Image',
			NS_IMAGE_TALK     => 'Discussion_Image',
			NS_MEDIAWIKI      => 'MediaWiki',
			NS_MEDIAWIKI_TALK => 'Discussion_MediaWiki',
			NS_TEMPLATE       => 'Modèle',
			NS_TEMPLATE_TALK  => 'Discussion_Modèle',
			NS_HELP           => 'Aide',
			NS_HELP_TALK      => 'Discussion_Aide',
			NS_CATEGORY       => 'Catégorie',
			NS_CATEGORY_TALK  => 'Discussion_Catégorie'
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesFr + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsFr;
	}

	function getSkinNames() {
		return $this->mSkinNamesFr + parent::getSkinNames();
	}

	function getBookstoreList() {
		return $this->mBookstoreListFr;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesFr[$key] ) ) {
			return $this->mMessagesFr[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesFr;
	}

	function getNsIndex( $text ) {
		global $wgSitename;

		foreach ( $this->mNamespaceNamesFr as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		if( $wgSitename == 'Wikipédia' ) {
			if( 0 == strcasecmp( 'Wikipedia', $text ) ) return NS_PROJECT;
			if( 0 == strcasecmp( 'Discussion_Wikipedia', $text ) ) return NS_PROJECT_TALK;
		}
		return false;
	}

	function timeBeforeDate( $format ) {
		return false;
	}

	function timeDateSeparator( $format ) {
		return " à ";
	}

	function separatorTransformTable() {
		return array(',' => "\xc2\xa0", '.' => ',' );
	}

}

?>
