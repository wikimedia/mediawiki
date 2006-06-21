<?php
/** Komi (Коми)
  *
  * @package MediaWiki
  * @subpackage Language
  *
  * @bug 3844
  *
  * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
  * @author Ashar Voultoiz <hashar@altern.org>
  *
  * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason, Ashar Voultoiz
  * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
  */

require_once 'LanguageRu.php';

class LanguageKv extends LanguageRu {

	function getFallbackLanguage() {
		return 'ru';
	}

	function getAllMessages() {
		return null;
	}
}

?>
