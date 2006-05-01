<?php
/** Sundanese language file (Basa Sunda)
  *
  * Source: http://su.wikipedia.org/
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

/* private */ $wgNamespaceNamesSu = array(
	NS_MEDIA			=> 'Média',
	NS_SPECIAL			=> 'Husus',
	NS_MAIN				=> '',
	NS_TALK				=> 'Obrolan',
	NS_USER				=> 'Pamaké',
	NS_USER_TALK		=> 'Obrolan_pamaké',
	NS_PROJECT			=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> 'Obrolan_' . $wgMetaNamespace,
	NS_IMAGE			=> 'Gambar',
	NS_IMAGE_TALK		=> 'Obrolan_gambar',
	NS_MEDIAWIKI		=> 'MediaWiki',
	NS_MEDIAWIKI_TALK	=> 'Obrolan_MediaWiki',
	NS_TEMPLATE			=> 'Citakan',
	NS_TEMPLATE_TALK	=> 'Obrolan_citakan',
	NS_HELP				=> 'Pitulung',
	NS_HELP_TALK		=> 'Obrolan_pitulung',
	NS_CATEGORY			=> 'Kategori',
	NS_CATEGORY_TALK	=> 'Obrolan_kategori',
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesSu.php');
}

class LanguageSu extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesSu;
		return $wgNamespaceNamesSu;
	}

	function getMessage( $key ) {
		global $wgAllMessagesSu;
		if( isset( $wgAllMessagesSu[$key] ) ) {
			return $wgAllMessagesSu[$key];
		} else {
			return parent::getMessage( $key );
		}
	}
}
?>
