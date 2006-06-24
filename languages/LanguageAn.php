<?php
/** Aragonese (Aragonés)
*
* @package MediaWiki
* @subpackage Language
*/

require_once( "LanguageUtf8.php" );

class LanguageAn extends LanguageUtf8 {
	private $mNamespaceNamesAn = null;

	function __construct() {
		parent::__construct();

		global $wgMetaNamespace;
		$this->mNamespaceNamesAn = array(
			NS_MEDIA          => 'Media',
			NS_SPECIAL        => 'Espezial',
			NS_MAIN           => '',
			NS_TALK           => 'Descusión',
			NS_USER           => 'Usuario',
			NS_USER_TALK      => 'Descusión_usuario',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => "Descusión_{$wgMetaNamespace}",
			NS_IMAGE          => 'Imachen',
			NS_IMAGE_TALK     => 'Descusión_imachen',
			NS_MEDIAWIKI      => 'MediaWiki',
			NS_MEDIAWIKI_TALK => 'Descusión_MediaWiki',
			NS_TEMPLATE       => 'Plantilla',
			NS_TEMPLATE_TALK  => 'Descusión_plantilla',
			NS_HELP           => 'Aduya',
			NS_HELP_TALK      => 'Descusión_aduya',
			NS_CATEGORY       => 'Categoría',
			NS_CATEGORY_TALK  => 'Descusión_categoría',
		);
	}

	function getNamespaces() {
		return $this->mNamespaceNamesAn + parent::getNamespaces();
	}

	function getAllMessages() {
		return null;
	}

}

?>
