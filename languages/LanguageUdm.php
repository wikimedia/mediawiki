<?php
/** Udmurt (Удмурт)
 *
 * @package MediaWiki
 * @subpackage Language
 *
 */

require_once( 'LanguageRu.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesUdm.php');
}

class LanguageUdm extends LanguageUtf8 {
	private $mMessagesUdm, $mNamespaceNamesUdm = null;
	
	function __construct() {
		parent::__construct();

		global $wgAllMessagesUdm;
		$this->mMessagesUdm =& $wgAllMessagesUdm;

		global $wgMetaNamespace;
		$this->mNamespaceNamesUdm = array(
			NS_MEDIA            => 'Медиа',
			NS_SPECIAL          => 'Панель',
			NS_MAIN             => '',
			NS_TALK             => 'Вераськон',
			NS_USER             => 'Викиавтор',
			NS_USER_TALK        => 'Викиавтор_сярысь_вераськон',
			NS_PROJECT          => $wgMetaNamespace,
			NS_PROJECT_TALK     => $wgMetaNamespace . '_сярысь_вераськон',
			NS_IMAGE            => 'Суред',
			NS_IMAGE_TALK       => 'Суред_сярысь_вераськон',
			NS_MEDIAWIKI        => 'MediaWiki',
			NS_MEDIAWIKI_TALK   => 'MediaWiki_сярысь_вераськон',
			NS_TEMPLATE         => 'Шаблон',
			NS_TEMPLATE_TALK    => 'Шаблон_сярысь_вераськон',
			NS_HELP             => 'Валэктон',
			NS_HELP_TALK        => 'Валэктон_сярысь_вераськон',
			NS_CATEGORY         => 'Категория',
			NS_CATEGORY_TALK    => 'Категория_сярысь_вераськон',
		);

	}

	function getFallbackLanguage() {
		return 'ru';
	}

	function getNamespaces() {
		return $this->mNamespaceNamesUdm + parent::getNamespaces();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesUdm[$key] ) ) {
			return $this->mMessagesUdm[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesUdm;
	}

	function linkTrail() {
		return '/^([a-zа-яёӝӟӥӧӵ“»]+)(.*)$/sDu';
	}

	function fallback8bitEncoding() {
		return 'windows-1251';
	}

	function separatorTransformTable() {
		return array(',' => ' ', '.' => ',' );
	}

}
?>
