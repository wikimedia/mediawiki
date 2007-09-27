<?php
/** Nahuatl
  *
  * @addtogroup Language
  *
  * @author Rob Church <robchur@gmail.com>
  *
  * @copyright Copyright © 2006, Rob Church
  * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

# Per conversation with a user in IRC, we inherit from Spanish and work from there
# Nahuatl was the language of the Aztecs, and a modern speaker is most likely to
# understand Spanish if a Nah translation is not available
$fallback = 'es';

$messages = array(
# Dates
'sunday'    => 'Tonatiutonal',
'monday'    => 'Metztlitonal',
'tuesday'   => 'Huitzilopochtonal',
'wednesday' => 'Yacatlipotonal',
'thursday'  => 'Tezcatlipotonal',
'friday'    => 'Quetzalcoatonal',
'saturday'  => 'Tlaloctitonal',
'january'   => 'Tlacēnti',
'february'  => 'Tlaōnti',
'march'     => 'Tlayēti',
'april'     => 'Tlanāuhti',
'may_long'  => 'Tlamācuīlti',
'june'      => 'Tlachicuazti',
'july'      => 'Tlachicōnti',
'august'    => 'Tlachicuēiti',
'september' => 'Tlachiucnāuhti',
'october'   => 'Tlamahtlācti',
'november'  => 'Tlamahtlāccēti',
'december'  => 'Tlamahtlācōnti',
'may'       => 'Tlamacuilti',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'mainpage' => 'Calīxatl',

# Login and logout pages
'yourpassword'      => 'Tlahtolichtacayo',
'yourpasswordagain' => 'Tlahtolichtacayo zapa',
'userlogin'         => 'Calaqui / Registrarse',
'yourlanguage'      => 'Tlahtolli:',
);
