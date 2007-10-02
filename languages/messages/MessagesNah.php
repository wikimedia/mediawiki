<?php
/** Nahuatl
  *
  * @addtogroup Language
  *
  * @author Rob Church <robchur@gmail.com>
  * @author Fluence
  *
  * @copyright Copyright © 2006-2007, Rob Church, Fluence
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

'article'    => 'tlahcuilōlli',
'navigation' => 'ācalpapanōliztli',

'history' => 'tlahcuilōlloh',
'edit'    => 'ticpatlaz',
'delete'  => 'tlapoloaz',
'talk'    => 'tēixnāmiquiliztli',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'mainpage' => 'Calīxatl',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'tlahcuilōlli',

# Login and logout pages
'yourpassword'      => 'Tlahtolichtacayo',
'yourpasswordagain' => 'Tlahtolichtacayo zapa',
'userlogin'         => 'Calaqui / Registrarse',
'yourlanguage'      => 'Tlahtolli:',

# Edit pages
'summary'          => 'Mopatlaliz',
'copyrightwarning' => '<small>Timitztlātlauhtiah xiquitta mochi mopatlaliz cana {{SITENAME}} tlatzayāna īpan [[GNU]]. Āqueh tlācah quipatlazqueh in motlahcuilōl auh tlatzayāna occeppa, intlā ahmō ticnequi īn, zātēpan ahmō titlahcuilōz nicān. Nō mitzihtoah in ōtitlahcuiloh ahmō quipiya in copyright nozo in yōllōxoxouhqui tlahcuilōlli. <strong>¡AHMŌ XITĒQUITILTILIA AHYŌLLŌXOXOUHQUI TLAHCUILŌLLI!</strong></small>',

);
