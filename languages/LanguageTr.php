<?php
require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesTr = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Özel',
	NS_MAIN             => '',
	NS_TALK             => 'Tartışma',
	NS_USER             => 'Kullanıcı',
	NS_USER_TALK        => 'Kullanıcı_mesaj',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => $wgMetaNamespace . '_tartışma',
	NS_IMAGE            => 'Resim',
	NS_IMAGE_TALK       => 'Resim_tartışma',
	NS_MEDIAWIKI        => 'MedyaViki',
	NS_MEDIAWIKI_TALK   => 'MedyaViki_tartışma',
	NS_TEMPLATE         => 'Şablon',
	NS_TEMPLATE_TALK    => 'Şablon_tartışma',
	NS_HELP             => 'Yardım',
	NS_HELP_TALK        => 'Yardım_tartışma',
	NS_CATEGORY         => 'Kategori',
	NS_CATEGORY_TALK    => 'Kategori_tartışma',
) + $wgNamespaceNamesEn;

class LanguageTr extends LanguageUtf8 {
	function getNamespaces() {
		global $wgNamespaceNamesTr;
		return $wgNamespaceNamesTr;
	}

	function formatNum( $number, $year = false ) {
		return $year ? $number : strtr($this->commafy($number), '.,', ',.' );
	}

	function ucfirst ( $string ) {
		if ( $string[0] == 'i' ) {
			return 'İ' . substr( $string, 1 );
		} else {
			return parent::ucfirst( $string );
		}
	}
}
?>
