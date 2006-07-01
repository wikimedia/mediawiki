<?php
/** Ossetic (Иронау)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageRu.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesOs.php');
}

class LanguageOs extends LanguageRu {
	private $mMessagesOs, $mNamespaceNamesOs = null;

	private $mQuickbarSettingsOs = array(
		'Ма равдис', 'Галиуырдыгæй', 'Рахизырдыгæй', 'Рахизырдыгæй ленккæнгæ'
	);
	
	private $mSkinNamesOs = array(
		'standard' => 'Стандартон',
		'nostalgia' => 'Æнкъард',
		'cologneblue' => 'Кёльны æрхæндæг',
		'davinci' => 'Да Винчи',
		'mono' => 'Моно',
		'monobook' => 'Моно-чиныг',
		'myskin' => 'Мæхи',
		'chick' => 'Карк'
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesOs;
		$this->mMessagesOs =& $wgAllMessagesOs;

		global $wgMetaNamespace;
		$this->mNamespaceNamesOs = array(
			NS_MEDIA            => 'Media', //чтоб не писать "Мультимедия"
			NS_SPECIAL          => 'Сæрмагонд',
			NS_MAIN             => '',
			NS_TALK             => 'Дискусси',
			NS_USER             => 'Архайæг',
			NS_USER_TALK        => 'Архайæджы_дискусси',
			NS_PROJECT          => $wgMetaNamespace,
			NS_PROJECT_TALK     => 'Дискусси_' . $wgMetaNamespace,
			NS_IMAGE            => 'Ныв',
			NS_IMAGE_TALK       => 'Нывы_тыххæй_дискусси',
			NS_MEDIAWIKI        => 'MediaWiki',
			NS_MEDIAWIKI_TALK   => 'Дискусси_MediaWiki',
			NS_TEMPLATE         => 'Шаблон',
			NS_TEMPLATE_TALK    => 'Шаблоны_тыххæй_дискусси',
			NS_HELP             => 'Æххуыс',
			NS_HELP_TALK        => 'Æххуысы_тыххæй_дискусси',
			NS_CATEGORY         => 'Категори',
			NS_CATEGORY_TALK    => 'Категорийы_тыххæй_дискусси',
		);

	}

	function getFallbackLanguage() {
		return 'ru';
	}

	function getNamespaces() {
		return $this->mNamespaceNamesOs + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsOs;
	}

	function getSkinNames() {
		return $this->mSkinNamesOs + parent::getSkinNames();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesOs[$key] ) ) {
			return $this->mMessagesOs[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesOs;
	}

	#'linkprefix' => '/^(.*?)(„|«)$/sD',

	function linkTrail() {
		return '/^((?:[a-z]|а|æ|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я|“|»)+)(.*)$/sDu';
	}

	function fallback8bitEncoding() {
		return 'windows-1251';
	}

}

?>
