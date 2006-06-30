<?php
/** Piedmontese (Piemontèis)
  * Users are bilingual in Piedmontese and Italian, using Italian as template.
  *
  * @package MediaWiki
  * @subpackage Language
  *
  * @bug 5362
  *
  * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>, Jens Frank
  * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason, Jens Frank
  * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
  */

require_once 'LanguageIt.php';

if (!$wgCachedMessageArrays) {
	require_once('MessagesPms.php');
}

class LanguagePms extends LanguageIt {

	function __construct() {
		parent::__construct();

		global $wgAllMessagesPms;
		$this->mMessagesPms =& $wgAllMessagesPms;

		global $wgMetaNamespace;
		$this->mNamespaceNamesPms = array(
			NS_MEDIA            => 'Media',
			NS_SPECIAL          => 'Special',
			NS_MAIN             => '',
			NS_TALK             => 'Discussion',
			NS_USER             => 'Utent',
			NS_USER_TALK        => 'Ciaciarade',
			NS_PROJECT          => $wgMetaNamespace,
			NS_PROJECT_TALK     => 'Discussion_ant_sla_' . $wgMetaNamespace,
			NS_IMAGE            => 'Figura',
			NS_IMAGE_TALK       => 'Discussion_dla_figura',
			NS_MEDIAWIKI        => 'MediaWiki',
			NS_MEDIAWIKI_TALK   => 'Discussion_dla_MediaWiki',
			NS_TEMPLATE         => 'Stamp',
			NS_TEMPLATE_TALK    => 'Discussion_dlë_stamp',
			NS_HELP             => 'Agiut',
			NS_HELP_TALK        => 'Discussion_ant_sl\'agiut',
			NS_CATEGORY         => 'Categorìa',
			NS_CATEGORY_TALK    => 'Discussion_ant_sla_categorìa'
		);

	}

	function getFallbackLanguage() {
		return 'it';
	}

	function getNamespaces() {
		return $this->mNamespaceNamesPms + parent::getNamespaces();
	}

	function getMessage( $key ) {
		if( isset( $this->mMessagesPms[$key] ) ) {
			return $this->mMessagesPms[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function getAllMessages() {
		return $this->mMessagesPms;
	}

}

?>
