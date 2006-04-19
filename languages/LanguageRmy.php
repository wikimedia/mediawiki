<?php
/** Vlax Romany (Romani)
  *
  * @package MediaWiki
  * @subpackage Language
  *
  * @bug 5422
  *
  * @author Niklas Laxström
  *
  * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
  */

/**
 * Use Romanian as default instead of English
 */
require_once 'LanguageRo.php';


if (!$wgCachedMessageArrays) {
	require_once('MessagesRmy.php');
}

/**
 *
 */
class LanguageRmy extends LanguageRo {
	function getMessage( $key ) {
		 global $wgAllMessagesRmy;
		 if( isset( $wgAllMessagesRmy[$key] ) ) {
			 return $wgAllMessagesRmy[$key];
		 } else {
			 return parent::getMessage( $key );
		}
	}
}
?>