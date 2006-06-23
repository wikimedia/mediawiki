<?php
/** Norwegian (Norsk (bokmål))
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesNo.php');
}

class LanguageNo extends LanguageUtf8 {
	private $mMessagesNo, $mNamespaceNamesNo;
	
	private $mQuickbarSettingsNo = array(
		'Ingen', 'Fast venstre', 'Fast høyre', 'Flytende venstre'
	);
	
	private $mSkinNamesNo = array(
		'standard'    => 'Standard',
		'nostalgia'   => 'Nostalgi',
		'cologneblue' => 'Kölnerblå'
	);
	
	private $mBookstoreListNo = array(
		'Antikvariat.net' => 'http://www.antikvariat.net/',
		'Frida' => 'http://wo.uio.no/as/WebObjects/frida.woa/wa/fres?action=sok&isbn=$1&visParametre=1&sort=alfabetisk&bs=50',
		'Bibsys' => 'http://ask.bibsys.no/ask/action/result?cmd=&kilde=biblio&fid=isbn&term=$1&op=and&fid=bd&term=&arstall=&sortering=sortdate-&treffPrSide=50',
		'Akademika' => 'http://www.akademika.no/sok.php?ts=4&sok=$1',
		'Haugenbok' => 'http://www.haugenbok.no/resultat.cfm?st=extended&isbn=$1',
		'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1'
	);

	function __construct() {
		parent::__construct();

		global $wgAllMessagesNo;
		$this->mMessagesNo =& $wgAllMessagesNo;

		global $wgMetaNamespace;
		$this->mNamespaceNamesNo = array(
			NS_MEDIA          => 'Medium',
			NS_SPECIAL        => 'Spesial',
			NS_MAIN           => '',
			NS_TALK           => 'Diskusjon',
			NS_USER           => 'Bruker',
			NS_USER_TALK      => 'Brukerdiskusjon',
			NS_PROJECT        => $wgMetaNamespace,
			NS_PROJECT_TALK   => $wgMetaNamespace . '-diskusjon',
			NS_IMAGE          => 'Bilde',
			NS_IMAGE_TALK     => 'Bildediskusjon',
			NS_MEDIAWIKI      => 'MediaWiki',
			NS_MEDIAWIKI_TALK => 'MediaWiki-diskusjon',
			NS_TEMPLATE       => 'Mal',
			NS_TEMPLATE_TALK  => 'Maldiskusjon',
			NS_HELP           => 'Hjelp',
			NS_HELP_TALK      => 'Hjelpdiskusjon',
			NS_CATEGORY       => 'Kategori',
			NS_CATEGORY_TALK  => 'Kategoridiskusjon',
		);
	}

	function getBookstoreList () {
		return $this->mBookstoreListNo;
	}

	function getNamespaces() {
		return $this->mNamespaceNamesNo + parent::getNamespaces();
	}

	function getQuickbarSettings() {
		return $this->mQuickbarSettingsNo;
	}

	function getSkinNames() {
		return $this->mSkinNamesNo + parent::getSkinNames();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesNo[$key] ) ) {
			return $this->mMessagesNo[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesNo;
	}

	function formatMonth( $month, $format ) {
		return $this->getMonthAbbreviation( $month );
	}

	function formatDay( $day, $format ) {
		return parent::formatDay( $day, $format ) . '.';
	}

	function timeBeforeDate() {
		return false;
	}

	function timeDateSeparator( $format ) {
		return ' kl.';
	}

	function separatorTransformTable() {
		return array(',' => "\xc2\xa0", '.' => ',' );
	}
}

?>
