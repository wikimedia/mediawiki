<?php
/** Georgian (ქართული)
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

class LanguageKa extends LanguageUtf8 {
	private $mNamespaceNamesKa = null;

	function __construct() {
		parent::__construct();

		global $wgMetaNamespace;
		$this->mNamespaceNamesKa = array(
			NS_MEDIA            => 'მედია',
			NS_SPECIAL          => 'სპეციალური',
			NS_MAIN             => '',
			NS_TALK             => 'განხილვა',
			NS_USER             => 'მომხმარებელი',
			NS_USER_TALK        => 'მომხმარებელი_განხილვა',
			NS_PROJECT          => $wgMetaNamespace,
			NS_PROJECT_TALK     => $wgMetaNamespace . '_განხილვა',
			NS_IMAGE            => 'სურათი',
			NS_IMAGE_TALK       => 'სურათი_განხილვა',
			NS_MEDIAWIKI        => 'მედიავიკი',
			NS_MEDIAWIKI_TALK   => 'მედიავიკი_განხილვა',
			NS_TEMPLATE         => 'თარგი',
			NS_TEMPLATE_TALK    => 'თარგი_განხილვა',
			NS_HELP             => 'დახმარება',
			NS_HELP_TALK        => 'დახმარება_განხილვა',
			NS_CATEGORY         => 'კატეგორია',
			NS_CATEGORY_TALK    => 'კატეგორია_განხილვა'
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesKa + parent::getNamespaces();
	}

}

?>
