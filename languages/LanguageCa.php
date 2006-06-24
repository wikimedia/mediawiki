<?php
/** Catalan (Català)
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesCa.php');
}

class LanguageCa extends LanguageUtf8 {
	private $mMessagesCa, $mNamespaceNamesCa = null;

	private $mQuickbarSettingsCa = array(
		"Cap", "Fixa a la dreta", "Fixa a l'esquerra", "Surant a l'esquerra"
	);
	
	private $mSkinNamesCa = array(
		'standard' => "Estàndard",
		'nostalgia' => "Nostàlgia",
		'cologneblue' => "Colònia blava",
	);

	private $mBookstoreListCa = array(
		'Catàleg Col·lectiu de les Universitats de Catalunya' => 'http://ccuc.cbuc.es/cgi-bin/vtls.web.gateway?searchtype=control+numcard&searcharg=$1',
		'Totselsllibres.com' => 'http://www.totselsllibres.com/tel/publi/busquedaAvanzadaLibros.do?ISBN=$1',
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesCa;
		$this->mMessagesCa =& $wgAllMessagesCa;

		global $wgMetaNamespace;
		$this->mNamespaceNamesCa = array(
			NS_MEDIA          => 'Media',
			NS_SPECIAL        => 'Especial',
			NS_MAIN           => '',
			NS_TALK           => 'Discussió',
			NS_USER           => 'Usuari',
			NS_USER_TALK      => 'Usuari_Discussió',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => $wgMetaNamespace.'_Discussió',
			NS_IMAGE          => 'Imatge',
			NS_IMAGE_TALK     => 'Imatge_Discussió',
			NS_MEDIAWIKI      => 'MediaWiki',
			NS_MEDIAWIKI_TALK => 'MediaWiki_Discussió',
			NS_TEMPLATE       => 'Plantilla',
			NS_TEMPLATE_TALK  => 'Plantilla_Discussió',
			NS_HELP           => 'Ajuda',
			NS_HELP_TALK      => 'Ajuda_Discussió',
			NS_CATEGORY       => 'Categoria',
			NS_CATEGORY_TALK  => 'Categoria_Discussió'
		);
	}

	function getNamespaces() {
		return $this->mNamespaceNamesCa + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsCa;
	}

	function getSkinNames() {
		return $this->mSkinNamesCa + parent::getSkinNames();
	}

	function getBookstoreList () {
		return $this->mBookstoreListCa + parent::getBookstoreList();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesCa[$key] ) ) {
			return $this->mMessagesCa[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesCa;
	}

	function formatMonth( $month, $format ) {
		return $this->getMonthAbbreviation( $month );
	}

	function separatorTransformTable() {
		return array(',' => '.', '.' => ',' );
	}

	function linkTrail() {
		return '/^([a-zàèéíòóúç·ïü\']+)(.*)$/sDu';
	}

}

?>
