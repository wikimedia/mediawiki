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

# Whether to use user or default setting in Language::date()

/* private */ $wgDateFormatsTr = array(
	MW_DATE_DEFAULT => 'Tercih yok',
	MW_DATE_MDY => '16:12, Ocak 15, 2001',
	MW_DATE_DMY => '16:12, 15 Ocak 2001',
	MW_DATE_YMD => '16:12, 2001 Ocak 15',
	MW_DATE_ISO => '2001-01-15 16:12:34'
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesTr.php');
}



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

	function getMessage( $key ) {
		global $wgAllMessagesTr;
		if( isset( $wgAllMessagesTr[$key] ) ) {
			return $wgAllMessagesTr[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getDateFormats() {
		global $wgDateFormatsTr;
		return $wgDateFormatsTr;
	}

}


?>
