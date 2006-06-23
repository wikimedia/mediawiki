<?php
/** Dutch Lower Saxon (Nedersaksisch)
  *
  * @package MediaWiki
  * @subpackage Language
  *
  * @bug 4497
  *
  * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>, Jens Frank
  * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason, Jens Frank
  * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
  */

require_once 'LanguageNds.php';

class LanguageNds_nl extends LanguageNds {
	private $mNamespaceNamesNds_nl = null;

	private $mSkinNamesNds_nl = array(
		'standard'      => 'Klassiek',
		'nostalgia'     => 'Nostalgie',
		'cologneblue'   => 'Keuls blauw',
		'smarty'        => 'Paddington',
		'chick'         => 'Sjiek'
	);

	function __construct() {
		parent::__construct();

		global $wgMetaNamespace;
		$this->mNamespaceNamesNds_nl = array(
			NS_MEDIA            => 'Media',
			NS_SPECIAL          => 'Speciaol',
			NS_MAIN             => '',
			NS_TALK             => 'Overleg',
			NS_USER             => 'Gebruker',
			NS_USER_TALK        => 'Overleg_gebruker',
			NS_PROJECT          => $wgMetaNamespace,
			NS_PROJECT_TALK     => 'Overleg_' . $wgMetaNamespace,
			NS_IMAGE            => 'Ofbeelding',
			NS_IMAGE_TALK       => 'Overleg_ofbeelding',
			NS_MEDIAWIKI        => 'MediaWiki',
			NS_MEDIAWIKI_TALK   => 'Overleg_MediaWiki',
			NS_TEMPLATE         => 'Sjabloon',
			NS_TEMPLATE_TALK    => 'Overleg_sjabloon',
			NS_HELP             => 'Help',
			NS_HELP_TALK        => 'Overleg_help',
			NS_CATEGORY         => 'Categorie',
			NS_CATEGORY_TALK    => 'Overleg_categorie'
		);

	}

	function getFallbackLanguage() {
		return 'nds';
	}

	function getNamespaces() {
		return $this->mNamespaceNamesNds_nl;
	}

	function getSkinNames() {
		return $this->mSkinNamesNds_nl + parent::getSkinNames();
	}

	function getAllMessages() {
		return null;
	}

	function formatDay( $day, $format ) {
		return Language::formatDay( $day, $format );
	}

}

?>
