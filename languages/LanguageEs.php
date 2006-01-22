<?php
/** Spanish (Español)
  *
  * @bug 4401
  * @bug 4424
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesEs = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Especial',
	NS_MAIN           => '',
	NS_TALK           => 'Discusión',
	NS_USER           => 'Usuario',
	NS_USER_TALK      => 'Usuario_Discusión',
	NS_PROJECT	      => $wgMetaNamespace,
	NS_PROJECT_TALK   => "{$wgMetaNamespace}_Discusión",
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
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsEs = array(
	'Ninguna', 'Fija a la izquierda', 'Fija a la derecha', 'Flotante a la izquierda'
);

/* private */ $wgSkinNamesEs = array(
	'standard' => 'Estándar',
) +  $wgSkinNamesEn;

/* private */ $wgDateFormatsEs = array();

if (!$wgCachedMessageArrays) {
	require_once('MessagesEs.php');
}

class LanguageEs extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesEs;
		return $wgNamespaceNamesEs;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsEs;
		return $wgQuickbarSettingsEs;
	}

	function getSkinNames() {
		global $wgSkinNamesEs;
		return $wgSkinNamesEs;
	}

	function formatMonth( $month, $format ) {
		return $this->getMonthAbbreviation( $month );
	}

	function timeDateSeparator( $format ) {
		return ' ';
	}


	function getMessage( $key ) {
		global $wgAllMessagesEs;
		if( isset( $wgAllMessagesEs[$key] ) ) {
			return $wgAllMessagesEs[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function formatNum( $number, $year = false ) {
		return $year ? $number : strtr($this->commafy($number), '.,', ',.' );
	}
}

?>
