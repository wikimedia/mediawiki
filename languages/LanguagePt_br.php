<?php
/** Brazilian Portugese (Portuguêsi do Brasil)
  * @package MediaWiki
  * @subpackage Language
  */
#
# This translation was made by Yves Marques Junqueira
# and Rodrigo Calanca Nishino from Portuguese Wikipedia
#

require_once( 'LanguagePt.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesPt_br.php');
}

class LanguagePt_br extends LanguagePt {
	private $mMessagesPt_br, $mNamespaceNamesPt_br = null;

	private $mSkinNamesPt_br = array(
		'standard' => 'Padrão',
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesPt_br;
		$this->mMessagesPt_br =& $wgAllMessagesPt_br;

		global $wgMetaNamespace;
		$this->mNamespaceNamesPt_br = array(
			NS_MEDIA          => 'Media',
			NS_SPECIAL        => 'Especial',
			NS_MAIN           => '',
			NS_TALK           => 'Discussão',
			NS_USER           => 'Usuário',
			NS_USER_TALK      => 'Usuário_Discussão',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => $wgMetaNamespace . '_Discussão',
			NS_IMAGE          => 'Imagem',
			NS_IMAGE_TALK     => 'Imagem_Discussão',
			NS_MEDIAWIKI      => 'MediaWiki',
			NS_MEDIAWIKI_TALK => 'MediaWiki_Discussão',
			NS_TEMPLATE       => 'Predefinição',
			NS_TEMPLATE_TALK  => 'Predefinição_Discussão',
			NS_HELP           => 'Ajuda',
			NS_HELP_TALK      => 'Ajuda_Discussão',
			NS_CATEGORY       => 'Categoria',
			NS_CATEGORY_TALK  => 'Categoria_Discussão'
		);

	}

	function getFallbackLanguage() {
		return 'pt';
	}

	function getNamespaces() {
		return $this->mNamespaceNamesPt_br + parent::getNamespaces();
	}

	function getSkinNames() {
		return $this->mSkinNamesPt_br + parent::getSkinNames();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesPt_br[$key] ) ) {
			return $this->mMessagesPt_br[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesPt_br;
	}

}

?>
