<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

/* private */ $wgNamespaceNamesCa = array(
	NS_MEDIA			=> 'Media',
	NS_SPECIAL			=> 'Especial',
	NS_MAIN				=> '',
	NS_TALK				=> 'Discussió',
	NS_USER				=> 'Usuari',
	NS_USER_TALK		=> 'Usuari_Discussió',
	NS_PROJECT			=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> $wgMetaNamespace.'_Discussió',
	NS_IMAGE			=> 'Imatge',
	NS_IMAGE_TALK		=> 'Imatge_Discussió',
	NS_MEDIAWIKI		=> 'MediaWiki',
	NS_MEDIAWIKI_TALK	=> 'MediaWiki_Discussió',
	NS_TEMPLATE			=> 'Template',
	NS_TEMPLATE_TALK	=> 'Template_Discussió',
	NS_HELP				=> 'Ajuda',
	NS_HELP_TALK		=> 'Ajuda_Discussió',
	NS_CATEGORY			=> 'Categoria',
	NS_CATEGORY_TALK	=> 'Categoria_Discussió'
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsCa = array(
	"Cap", "Fixa a la dreta", "Fixa a l'esquerra", "Surant a l'esquerra"
);

/* private */ $wgSkinNamesCa = array(
	'standard' => "Estàndard",
	'nostalgia' => "Nostàlgia",
	'cologneblue' => "Colònia blava",
) + $wgSkinNamesEn;

/* private */ $wgDateFormatsCa = array(
#	"No preference",
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesCa.php');
}

/** This is an UTF8 language */
require_once( 'LanguageUtf8.php' );

/** @package MediaWiki */
class LanguageCa extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesCa;
		return $wgNamespaceNamesCa;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsCa;
		return $wgQuickbarSettingsCa;
	}

	function getSkinNames() {
		global $wgSkinNamesCa;
		return $wgSkinNamesCa;
	}

	function formatMonth( $month, $format ) {
		return $this->getMonthAbbreviation( $month );
	}

	function getMessage( $key ) {
		global $wgAllMessagesCa;
		if( isset( $wgAllMessagesCa[$key] ) ) {
			return $wgAllMessagesCa[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function formatNum( $number, $year = false ) {
		return $year ? $number : strtr($this->commafy($number), '.,', ',.' );
	}

}

?>
