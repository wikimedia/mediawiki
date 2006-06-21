<?php
/** Tahitian (Reo Mā`ohi)
  *
  * @package MediaWiki
  * @subpackage Language
  *
  * @bug 3844
  *
  * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
  * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
  * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
  */

require_once 'LanguageFr.php';

class LanguageTy extends LanguageFr {

	function getFallbackLanguage() {
		return 'fr';
	}

	function getAllMessages() {
		return null;
	}

}

?>
