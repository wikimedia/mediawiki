<?php
/** Azerbaijani (Azərbaycan)
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( "LanguageUtf8.php" );

if (!$wgCachedMessageArrays) {
	require_once('MessagesAz.php');
}

class LanguageAz extends LanguageUtf8 {
	private $mMessagesAz, $mNamespaceNamesAz = null;
	
	private $mDateFormatsAz = array(
		MW_DATE_DEFAULT => 'Tərcih yox',
		MW_DATE_MDY => '16:12, Yanvar  15, 2001',
		MW_DATE_DMY => '16:12, 15 Yanvar  2001',
		MW_DATE_YMD => '16:12, 2001 Yanvar  15',
		MW_DATE_ISO => '2001-01-15 16:12:34'
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesAz;
		$this->mMessagesAz =& $wgAllMessagesAz;

		global $wgMetaNamespace;
		$this->mNamespaceNamesAz = array(
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
		);

	}
	function getNamespaces() {
		return $this->mNamespaceNamesAz + parent::getNamespaces();
	}

	function getDateFormats() {
		return $this->mDateFormatsAz;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesAz[$key] ) ) {
			return $this->mMessagesAz[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesAz;
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
