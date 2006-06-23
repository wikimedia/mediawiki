<?php
/** Spanish (Español)
  *
  * @bug 4401
  * @bug 4424
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesEs.php');
}

class LanguageEs extends LanguageUtf8 {
	private $mMessagesEs, $mNamespaceNamesEs = null;

	private $mQuickbarSettingsEs = array(
		'Ninguna', 'Fija a la izquierda', 'Fija a la derecha', 'Flotante a la izquierda'
	);
	
	private $mSkinNamesEs = array(
		'standard' => 'Estándar',
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesEs;
		$this->mMessagesEs =& $wgAllMessagesEs;

		global $wgMetaNamespace;
		$this->mNamespaceNamesEs = array(
			NS_MEDIA          => 'Media',
			NS_SPECIAL        => 'Especial',
			NS_MAIN           => '',
			NS_TALK           => 'Discusión',
			NS_USER           => 'Usuario',
			NS_USER_TALK      => 'Usuario_Discusión',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => $wgMetaNamespace . '_Discusión',
			NS_IMAGE          => 'Imagen',
			NS_IMAGE_TALK     => 'Imagen_Discusión',
			NS_MEDIAWIKI      => 'MediaWiki',
			NS_MEDIAWIKI_TALK => 'MediaWiki_Discusión',
			NS_TEMPLATE       => 'Plantilla',
			NS_TEMPLATE_TALK  => 'Plantilla_Discusión',
			NS_HELP           => 'Ayuda',
			NS_HELP_TALK      => 'Ayuda_Discusión',
			NS_CATEGORY       => 'Categoría',
			NS_CATEGORY_TALK  => 'Categoría_Discusión',
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesEs + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsEs;
	}

	function getSkinNames() {
		return $this->mSkinNamesEs + parent::getSkinNames();
	}

	function getDateFormats() {
		return false;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesEs[$key] ) ) {
			return $this->mMessagesEs[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesEs;
	}

	function formatMonth( $month, $format ) {
		return $this->getMonthAbbreviation( $month );
	}

	function timeDateSeparator( $format ) {
		return ' ';
	}

	function separatorTransformTable() {
		return array(',' => '.', '.' => ',' );
	}

}

?>
