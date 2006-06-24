<?php
/** Asturian (Asturianu)
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

class LanguageAst extends LanguageUtf8 {
	private $mNamespaceNamesAst = null;

	function __construct() {
		parent::__construct();

		global $wgMetaNamespace;
		$this->mNamespaceNamesAst = array(
			NS_MEDIA            => 'Media',
			NS_SPECIAL          => 'Especial',
			NS_MAIN             => '',
			NS_TALK             => 'Discusión',
			NS_USER             => 'Usuariu',
			NS_USER_TALK        => 'Usuariu_discusión',
			NS_PROJECT          => $wgMetaNamespace,
			NS_PROJECT_TALK     => $wgMetaNamespace . '_discusión',
			NS_IMAGE            => 'Imaxen',
			NS_IMAGE_TALK       => 'Imaxen_discusión',
			NS_MEDIAWIKI        => 'MediaWiki',
			NS_MEDIAWIKI_TALK   => 'MediaWiki_discusión',
			NS_TEMPLATE         => 'Plantilla',
			NS_TEMPLATE_TALK    => 'Plantilla_discusión',
			NS_HELP             => 'Ayuda',
			NS_HELP_TALK        => 'Ayuda_discusión',
			NS_CATEGORY         => 'Categoría',
			NS_CATEGORY_TALK    => 'Categoría_discusión',
		);
	}

	function getNamespaces() {
		return $this->mNamespaceNamesAst + parent::getNamespaces();
	}

	function getAllMessages() {
		return null;
	}

}

?>
