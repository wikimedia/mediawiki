<?php

/**
 * Localisation files for Azeri language
 *
 */

require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesAz = array(
	NS_MEDIA            => 'Mediya',
	NS_SPECIAL          => 'Xüsusi',
	NS_MAIN             => '',
	NS_TALK             => 'Müzakirə',
	NS_USER             => 'İstifadəçi',
	NS_USER_TALK        => 'İstifadəçi_müzakirəsi',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => $wgMetaNamespace . '_müzakirəsi',
	NS_IMAGE            => 'Şəkil',
	NS_IMAGE_TALK       => 'Şəkil_müzakirəsi',
	NS_MEDIAWIKI        => 'MediyaViki',
	NS_MEDIAWIKI_TALK   => 'MediyaViki_müzakirəsi',
	NS_TEMPLATE         => 'Şablon',
	NS_TEMPLATE_TALK    => 'Şablon_müzakirəsi',
	NS_HELP             => 'Kömək',
	NS_HELP_TALK        => 'Kömək_müzakirəsi',
	NS_CATEGORY         => 'Kateqoriya',
	NS_CATEGORY_TALK    => 'Kateqoriya_müzakirəsi',
) + $wgNamespaceNamesEn;

# Whether to use user or default setting in Language::date()

/* private */ $wgDateFormatsAz = array(
	MW_DATE_DEFAULT => 'Tərcih yox',
	MW_DATE_MDY => '16:12, Yanvar  15, 2001',
	MW_DATE_DMY => '16:12, 15 Yanvar  2001',
	MW_DATE_YMD => '16:12, 2001 Yanvar  15',
	MW_DATE_ISO => '2001-01-15 16:12:34'
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesAz.php');
}



class LanguageAz extends LanguageUtf8 {
	function getNamespaces() {
		global $wgNamespaceNamesAz;
		return $wgNamespaceNamesAz;
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
		global $wgAllMessagesAz;
		if( isset( $wgAllMessagesAz[$key] ) ) {
			return $wgAllMessagesAz[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getDateFormats() {
		global $wgDateFormatsAz;
		return $wgDateFormatsAz;
	}

}


?>