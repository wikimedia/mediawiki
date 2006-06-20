<?php
/**
 * Hebrew (עברית)
 *
 * @package MediaWiki
 * @subpackage Language
 *
 * @author Rotem Dan (July 2003)
 * @author Rotem Liss (March 2006 on)
 */

require_once("LanguageUtf8.php");

if (!$wgCachedMessageArrays) {
	require_once('MessagesHe.php');
}

class LanguageHe extends LanguageUtf8 {
	private $mMessagesHe, $mNamespaceNamesHe = null;
	
	private $mSkinNamesHe = array(
		"standard"    => "רגיל",
		"nostalgia"   => "נוסטלגי",
		"cologneblue" => "מים כחולים",
		"davinci"     => "דה־וינצ'י",
		"simple"      => "פשוט",
		"mono"        => "מונו",
		"monobook"    => "מונובוק",
		"myskin"      => "הרקע שלי",
		"chick"       => "צ'יק"
	);
	
	private $mQuickbarSettingsHe = array(
		"ללא", "קבוע משמאל", "קבוע מימין", "צף משמאל", "צף מימין"
	);
	
	private $mBookstoreListHe = array(
		"מיתוס"          => "http://www.mitos.co.il/",
		"iBooks"         => "http://www.ibooks.co.il/",
		"Barnes & Noble" => "http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=\$1",
		"Amazon.com"     => "http://www.amazon.com/exec/obidos/ISBN=\$1"
	);
	
	/**
	 * Constructor, setting the namespaces
	 */
	function LanguageHe() {
		LanguageUtf8::LanguageUtf8();
		
		global $wgAllMessagesHe;
		$this->mMessagesHe = &$wgAllMessagesHe;
		
		global $wgMetaNamespace;
		$this->mNamespaceNamesHe = array(
			NS_MEDIA          => "מדיה",
			NS_SPECIAL        => "מיוחד",
			NS_MAIN           => "",
			NS_TALK           => "שיחה",
			NS_USER           => "משתמש",
			NS_USER_TALK      => "שיחת_משתמש",
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => "שיחת_" . $wgMetaNamespace,
			NS_IMAGE          => "תמונה",
			NS_IMAGE_TALK     => "שיחת_תמונה",
			NS_MEDIAWIKI      => "מדיה_ויקי",
			NS_MEDIAWIKI_TALK => "שיחת_מדיה_ויקי",
			NS_TEMPLATE       => "תבנית",
			NS_TEMPLATE_TALK  => "שיחת_תבנית",
			NS_HELP           => "עזרה",
			NS_HELP_TALK      => "שיחת_עזרה",
			NS_CATEGORY       => "קטגוריה",
			NS_CATEGORY_TALK  => "שיחת_קטגוריה",
		);
	}
	
	/**
	 * Changing the default quickbar setting to "2" - right instead of left, as we use RTL interface
	 *
	 * @return array of the default user options
	 */
	function getDefaultUserOptions() {
		$opt = parent::getDefaultUserOptions();
		$opt["quickbar"] = 2;
		return $opt;
	}
	
	/**
	 * @return array of Hebrew bookstore list
	 */
	function getBookstoreList() {
		return $this->mBookstoreListHe;
	}
	
	/**
	 * @return array of Hebrew namespace names
	 */
	function getNamespaces() {
		return $this->mNamespaceNamesHe + parent::getNamespaces();
	}
	
	/**
	 * @return array of Hebrew skin names
	 */
	function getSkinNames() {
		return $this->mSkinNamesHe + parent::getSkinNames();
	}
	
	/**
	 * @return array of Hebrew quickbar settings
	 */
	function getQuickbarSettings() {
		return $this->mQuickbarSettingsHe;
	}
	
	/**
	 * The function returns a message, in Hebrew if exists, in English if not, only from the default translations here.
	 *
	 * @param string the message key
	 *
	 * @return string of the wanted message
	 */
	function getMessage( $key ) {
		if( isset( $this->mMessagesHe[$key] ) ) {
			return $this->mMessagesHe[$key];
		} else {
			return parent::getMessage( $key );
		}
	}
	
	/**
	 * @return array of all the Hebrew messages
	 */
	function getAllMessages() {
		return $this->mMessagesHe;
	}
	
	/**
	 * @return true, as Hebrew is RTL language
	 */
	function isRTL() {
		return true;
	}

	/**
	 * Gets a number and uses the suited form of the word.
	 *
	 * Needed for Hebrew as some words also has a form for two instances - for example, year or shoe -
	 * and the third parameter is used for them.
	 *
	 * When the word has only signular and plural forms, the plural form will be used for 2.
	 *
	 * @param integer $count
	 * @param string $wordform1
	 * @param string $wordform2
	 * @param string $wordform3 (optional)
	 *
	 * @return string of the suited form of word
	 */
	function convertPlural( $count, $wordform1, $wordform2, $wordform3) {
		if ( $count == '1' ) {
			return $wordform1;
		} elseif ( $count == '2' && $wordform3 ) {
			return $wordform3;
		} else {
			return $wordform2;
		}
	}
	
	/**
	 * @return string of the best 8-bit encoding for Hebrew, if UTF-8 cannot be used
	 */
	function fallback8bitEncoding() {
		return "windows-1255";
	}
}

?>
