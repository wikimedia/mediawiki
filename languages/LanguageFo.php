<?php
/** Faroese (Føroyskt)
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php');

if (!$wgCachedMessageArrays) {
	require_once('MessagesFo.php');
}

class LanguageFo extends LanguageUtf8 {
	private $mMessagesFo, $mNamespaceNamesFo = null;
	
	private $mQuickbarSettingsFo = array(
		'Eingin', 'Fast vinstru', 'Fast høgru', 'Flótandi vinstru'
	);
	
	private $mSkinNamesFo = array(
		'Standardur', 'Nostalgiskur', 'Cologne-bláur', 'Paddington', 'Montparnasse'
	);
	
	private $mBookstoreListFo = array(
		'Bokasolan.fo' => 'http://www.bokasolan.fo/vleitari.asp?haattur=bok.alfa&Heiti=&Hovindur=&Forlag=&innbinding=Oell&bolkur=Allir&prisur=Allir&Aarstal=Oell&mal=Oell&status=Oell&ISBN=$1',
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesFo;
		$this->mMessagesFo =& $wgAllMessagesFo;

		global $wgMetaNamespace;
		$this->mNamespaceNamesFo = array(
			NS_MEDIA            => 'Miðil',
			NS_SPECIAL          => 'Serstakur',
			NS_MAIN             => '',
			NS_TALK             => 'Kjak',
			NS_USER             => 'Brúkari',
			NS_USER_TALK        => 'Brúkari_kjak',
			NS_PROJECT          => $wgMetaNamespace,
			NS_PROJECT_TALK     => $wgMetaNamespace . '_kjak',
			NS_IMAGE            => 'Mynd',
			NS_IMAGE_TALK       => 'Mynd_kjak',
			NS_MEDIAWIKI        => 'MidiaWiki',
			NS_MEDIAWIKI_TALK   => 'MidiaWiki_kjak',
			NS_TEMPLATE         => 'Fyrimynd',
			NS_TEMPLATE_TALK    => 'Fyrimynd_kjak',
			NS_HELP             => 'Hjálp',
			NS_HELP_TALK        => 'Hjálp_kjak',
			NS_CATEGORY         => 'Bólkur',
			NS_CATEGORY_TALK    => 'Bólkur_kjak'
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesFo + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsFo;
	}

	function getSkinNames() {
		return $this->mSkinNamesFo + parent::getSkinNames();
	}

	function getBookstoreList() {
		return $this->mBookstoreListFo + parent::getBookstoreList();
	}

	function getDateFormats() {
		return false;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesFo[$key] ) ) {
			return $this->mMessagesFo[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesFo;
	}

	function timeDateSeparator( $format ) {
		return ' kl. ';
	}

	function timeBeforeDate() {
		return false;
	}

	function formatMonth( $month, $format ) {
		return $this->getMonthAbbreviation( $month );
	}

	function formatDay( $day, $format ) {
		return $this->formatNum( 0 + $day, true ) . '.';
	}

}

?>
