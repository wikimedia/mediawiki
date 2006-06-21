<?php
/** Zhuang (壮语)
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

require_once 'LanguageZh_cn.php';

class LanguageZa extends LanguageZh_cn {

	function getFallbackLanguage() {
		return 'zh-cn';
	}

	function getAllMessages() {
		return null;
	}

}

?>
