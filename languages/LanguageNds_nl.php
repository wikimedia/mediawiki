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

/**
 *
 */
require_once 'LanguageNds.php';

/* private */ $wgSkinNamesNds_nl = array(
	'standard'      => 'Klassiek',
	'nostalgia'     => 'Nostalgie',
	'cologneblue'   => 'Keuls blauw',
	'smarty'        => 'Paddington',
	'chick'         => 'Sjiek'
) + $wgSkinNamesEn;


/**
 *
 */
class LanguageNds_nl extends LanguageNds {

	function getSkinNames() {
		global $wgSkinNamesNds_nl;
		return $wgSkinNamesNds_nl;
	}
}

?>
