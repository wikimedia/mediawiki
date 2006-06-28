<?php
/** Latvian (Latviešu)
 *
 * @package MediaWiki
 * @subpackage Language
 *
 * @author Niklas Laxström
 *
 * @copyright Copyright © 2006, Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

require_once( 'LanguageUtf8.php' );

if (!$wgCachedMessageArrays) {
	require_once('MessagesLv.php');
}

class LanguageLv extends LanguageUtf8 {
	private $mMessagesLv, $mNamespaceNamesLv = null;

	function __construct() {
		parent::__construct();

		global $wgAllMessagesLv;
		$this->mMessagesLv =& $wgAllMessagesLv;

		global $wgMetaNamespace;
		$this->mNamespaceNamesLv = array(
			NS_MEDIA            => 'Media',
			NS_SPECIAL          => 'Special',
			NS_MAIN             => '',
			NS_TALK             => 'Diskusija',
			NS_USER             => 'Lietotājs',
			NS_USER_TALK        => 'Lietotāja_diskusija',
			NS_PROJECT          => $wgMetaNamespace,
			NS_PROJECT_TALK     => $this->convertGrammar( $wgMetaNamespace, 'ģenitīvs' ) . '_diskusija',
			NS_IMAGE            => 'Attēls',
			NS_IMAGE_TALK       => 'Attēla_diskusija',
			NS_MEDIAWIKI        => 'MediaWiki',
			NS_MEDIAWIKI_TALK   => 'MediaWiki_diskusija',
			NS_TEMPLATE         => 'Veidne',
			NS_TEMPLATE_TALK    => 'Veidnes_diskusija',
			NS_HELP             => 'Palīdzība',
			NS_HELP_TALK        => 'Palīdzības_diskusija',
			NS_CATEGORY         => 'Kategorija',
			NS_CATEGORY_TALK    => 'Kategorijas_diskusija',
		);

	}

	function getNamespaces() {
		return $this->mNamespaceNamesLv + parent::getNamespaces();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesLv[$key] ) ) {
			return $this->mMessagesLv[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesLv;
	}

	/**
	 * Latvian numeric formatting is 123 456,78.
	 */
	function separatorTransformTable() {
		return array(',' => "\xc2\xa0", '.' => ',' );
	}

	/**
	 * Plural form transformations. Using the first form for words with the last digit 1, but not for words with the last digits 11, and the second form for all the others.
	 *
	 * Example: {{plural:{{NUMBEROFARTICLES}}|article|articles}}
	 *
	 * @param integer $count
	 * @param string $wordform1
	 * @param string $wordform2
	 * @param string $wordform3 (not used)
	 * @return string
	 */
	function convertPlural( $count, $wordform1, $wordform2, $wordform3 ) {
		return ( ( $count % 10 == 1 ) && ( $count % 100 != 11 ) ) ? $wordform1 : $wordform2;
	}

	# Convert from the nominative form of a noun to some other case
	# Invoked with {{GRAMMAR:case|word}}
	# ģenitīvs - kā, datīvs - kam, akuzatīvs - ko, lokatīvs - kur.
	/**
	 * Cases: ģenitīvs, datīvs, akuzatīvs, lokatīvs
	 */
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;

		$wgGrammarForms['lv']['ģenitīvs' ]['Vikipēdija']   = 'Vikipēdijas';
		$wgGrammarForms['lv']['ģenitīvs' ]['Vikivārdnīca'] = 'Vikivārdnīcas';
		$wgGrammarForms['lv']['datīvs'   ]['Vikipēdija']   = 'Vikipēdijai';
		$wgGrammarForms['lv']['datīvs'   ]['Vikivārdnīca'] = 'Vikivārdnīcai';
		$wgGrammarForms['lv']['akuzatīvs']['Vikipēdija']   = 'Vikipēdiju';
		$wgGrammarForms['lv']['akuzatīvs']['Vikivārdnīca'] = 'Vikivārdnīcu';
		$wgGrammarForms['lv']['lokatīvs' ]['Vikipēdija']   = 'Vikipēdijā';
		$wgGrammarForms['lv']['lokatīvs' ]['Vikivārdnīca'] = 'Vikivārdnīcā';
	
		if ( isset($wgGrammarForms['lv'][$case][$word]) ) {
			return $wgGrammarForms['lv'][$case][$word];
		}

		return $word;

	}

}

?>
