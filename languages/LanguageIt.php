<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

/* private */ $wgNamespaceNamesIt = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Speciale',
	NS_MAIN             => '',
	NS_TALK             => 'Discussione',
	NS_USER             => 'Utente',
	NS_USER_TALK        => 'Discussioni_utente',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => 'Discussioni_'.$wgMetaNamespace,
	NS_IMAGE            => 'Immagine',
	NS_IMAGE_TALK       => 'Discussioni_immagine',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Discussioni_MediaWiki',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Discussioni_template',
	NS_HELP             => 'Aiuto',
	NS_HELP_TALK        => 'Discussioni_aiuto',
	NS_CATEGORY         => 'Categoria',
	NS_CATEGORY_TALK    => 'Discussioni_categoria'

) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsIt = array(
	"Nessuno", "Fisso a sinistra", "Fisso a destra", "Fluttuante a sinistra"
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesIt.php');
}

require_once( "LanguageUtf8.php" );

class LanguageIt extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesIt;
		return $wgNamespaceNamesIt;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsIt;
		return $wgQuickbarSettingsIt;
	}

	function formatMonth( $month, $format ) {
		return $this->getMonthAbbreviation( $month );
	}

	function getMessage( $key ) {
		global $wgAllMessagesIt;
		if(array_key_exists($key, $wgAllMessagesIt))
			return $wgAllMessagesIt[$key];
		else
			return parent::getMessage($key);
	}

	/**
	 * Italian numeric format is 201.511,17
	 */
	function formatNum( $number, $year = false ) {
		return $year ? $number : strtr($this->commafy($number), '.,', ',.' );
	}

}

?>
