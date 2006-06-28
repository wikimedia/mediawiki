<?php
/**
 * Turkish (Türkçe)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesTr.php');
}

class LanguageTr extends LanguageUtf8 {

	private $mDateFormatsTr = array(
		MW_DATE_DEFAULT => 'Tercih yok',
		MW_DATE_MDY => '16:12, Ocak 15, 2001',
		MW_DATE_DMY => '16:12, 15 Ocak 2001',
		MW_DATE_YMD => '16:12, 2001 Ocak 15',
		MW_DATE_ISO => '2001-01-15 16:12:34'
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesTr;
		$this->mMessagesTr =& $wgAllMessagesTr;

		global $wgMetaNamespace;
		$this->mNamespaceNamesTr = array(
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
		);
	}

	function getNamespaces() {
		return $this->mNamespaceNamesTr + parent::getNamespaces();
	}

	function getDateFormats() {
		return $this->mDateFormatsTr;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesTr[$key] ) ) {
			return $this->mMessagesTr[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesTr;
	}

	function separatorTransformTable() {
		return array(',' => '.', '.' => ',' );
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
