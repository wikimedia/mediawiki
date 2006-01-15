<?php

/** Nahuatl
  *
  * @package MediaWiki
  * @subpackage Language
  *
  * @author Rob Church <robchur@gmail.com>
  *
  * @copyright Copyright © 2006, Rob Church
  * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
  */

require_once( 'LanguageEs.php' );

$wgAllMessagesNah = array(

	# Month names
	'january' => 'Tlacenti',
	'february' => 'Tlaonti',
	'march' => 'Tlayeti',
	'april' => 'Tlanauhtl',
	'may' => 'Tlamacuilti',
	'june' => 'Tlachicuazti',
	'august' => 'Tlachiconti',
	'september' => 'Tlachicnauhti',
	'october' => 'Tlamatlacti',
	'november' => 'Tlamactlihuanceti',
	'december' => 'Tlamactlihuanonti',
	
	# Days of the week
	'monday' => 'Metztlitonal',
	'tuesday' => 'Huitzilopochtonal',
	'wednesday' => 'Yacatlipotonal',
	'thursday' => 'Tezcatlipotonal',
	'friday' => 'Quetzalcoatonal',
	'saturday' => 'Tlaloctitonal',
	'sunday' => 'Tonatiutonal'

);

class LanguageNah extends LanguageEs {

	# Per conversation with a user in IRC, we inherit from Spanish and work from there
	# Nahuatl was the language of the Aztecs, and a modern speaker is most likely to
	# understand Spanish if a Nah translation is not available

	function getMessage( $key ) {
		global $wgAllMessagesNah;
		return isset( $wgAllMessagesNah[$key] ) ? $wgAllMessagesNah[$key] : parent::getMessage( $key );
	}

}

?>