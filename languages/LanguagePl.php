<?php
/** Polish (Polski)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesPl.php');
}

class LanguagePl extends LanguageUtf8 {
	private $mMessagesPl, $mNamespaceNamesPl = null;

	private $mQuickbarSettingsPl = array(
		'Brak', 'Stały, z lewej', 'Stały, z prawej', 'Unoszący się, z lewej'
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesPl;
		$this->mMessagesPl =& $wgAllMessagesPl;

		global $wgMetaNamespace;
		# Yucky hardcoding hack as polish grammar need tweaking :o)
		switch( $wgMetaNamespace ) {
		case 'Wikipedia':
						$wgMetaTalkNamespace = 'Dyskusja_Wikipedii';
						$wgMetaUserNamespace = 'Wikipedysta';
				$wgMetaUserTalkNamespace = 'Dyskusja_Wikipedysty'; break;
		case 'Wikisłownik':
						$wgMetaTalkNamespace = 'Wikidyskusja';
						$wgMetaUserNamespace = 'Wikipedysta';
				$wgMetaUserTalkNamespace = 'Dyskusja_Wikipedysty'; break;
		case 'Wikicytaty':
						$wgMetaTalkNamespace = 'Dyskusja_Wikicytatów';
						$wgMetaUserNamespace = 'Wikipedysta';
				$wgMetaUserTalkNamespace = 'Dyskusja_Wikipedysty'; break;
		case 'Wikiźródła':
						$wgMetaTalkNamespace = 'Dyskusja_Wikiźródeł';
						$wgMetaUserNamespace = 'Wikiskryba';
				$wgMetaUserTalkNamespace = 'Dyskusja_Wikiskryby'; break;
		case 'Wikibooks':
						$wgMetaTalkNamespace = 'Dyskusja_Wikibooks';
						$wgMetaUserNamespace = 'Wikipedysta';
				$wgMetaUserTalkNamespace = 'Dyskusja_Wikipedysty'; break;
		case 'Wikinews':
						$wgMetaTalkNamespace = 'Dyskusja_Wikinews';
						$wgMetaUserNamespace = 'Wikireporter';
				$wgMetaUserTalkNamespace = 'Dyskusja_Wikireportera'; break;
		default:
						$wgMetaTalkNamespace = 'Dyskusja_' . $wgMetaNamespace;
						$wgMetaUserNamespace = 'Użytkownik';
				$wgMetaUserTalkNamespace = 'Dyskusja_użytkownika'; break;
		}

		$this->mNamespaceNamesPl = array(
			NS_MEDIA            => 'Media',
			NS_SPECIAL          => 'Specjalna',
			NS_MAIN             => '',
			NS_TALK             => 'Dyskusja',
			NS_USER             => $wgMetaUserNamespace,
			NS_USER_TALK        => $wgMetaUserTalkNamespace,
			NS_PROJECT          => $wgMetaNamespace,
			NS_PROJECT_TALK     => $wgMetaTalkNamespace,   // see above
			NS_IMAGE            => 'Grafika',
			NS_IMAGE_TALK       => 'Dyskusja_grafiki',
			NS_MEDIAWIKI        => 'MediaWiki',
			NS_MEDIAWIKI_TALK   => 'Dyskusja_MediaWiki',
			NS_TEMPLATE         => 'Szablon',
			NS_TEMPLATE_TALK    => 'Dyskusja_szablonu',
			NS_HELP             => 'Pomoc',
			NS_HELP_TALK        => 'Dyskusja_pomocy',
			NS_CATEGORY         => 'Kategoria',
			NS_CATEGORY_TALK    => 'Dyskusja_kategorii'
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesPl + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsPl;
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesPl[$key] ) ) {
			return $this->mMessagesPl[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesPl;
	}

	function getMonthNameGen( $key ) {
		global $wgMonthNamesGenEn, $wgContLang;
		// see who called us and use the correct message function
		if( get_class( $wgContLang->getLangObj() ) == get_class( $this ) )
			return wfMsgForContent( $wgMonthNamesGenEn[$key-1] );
		else
			return wfMsg( $wgMonthNamesGenEn[$key-1] );
	}

	function formatMonth( $month, $format ) {
		return $this->getMonthAbbreviation( $month );
	}

	# Check for Latin-2 backwards-compatibility URLs
	function fallback8bitEncoding() {
		return 'iso-8859-2';
	}

	function separatorTransformTable() {
		return array(
			',' => "\xc2\xa0", // @bug 2749
			'.' => ','
		);
	}

	function linkTrail() {
		return '/^([a-zęóąśłżźćńĘÓĄŚŁŻŹĆŃ]+)(.*)$/sDu';
	}

}
?>
