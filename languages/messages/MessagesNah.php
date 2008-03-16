<?php
/** Nahuatl (Nahuatl)
 *
 * @addtogroup Language
 *
 * @author Rob Church <robchur@gmail.com>
 * @author Fluence
 * @author Nike
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
'newwindow'  => '(Motlapoāz cē yancuīc tlanexillōtl)',
'navigation' => 'ācalpapanōliztli',

'history'        => 'tlahcuilōlloh',
'history_short'  => 'tlahcuilōlloh',
'edit'           => 'ticpatlaz',
'delete'         => 'tlapoloaz',
'talk'           => 'tēixnāmiquiliztli',
'lastmodifiedat' => 'Inīn zāzanilli ōtlapatlac catca īpan $2, $1.', # $1 date, $2 time

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'copyright'   => 'Tlahcuilōltzin cah yōllōxoxouhqui īpan $1',
'disclaimers' => 'Nahuatīllahtōl',
'edithelp'    => 'Patlaliztechcopa tēpalēhuiliztli',
'mainpage'    => 'Calīxatl',
'portal'      => 'Calīxcuātl tocalpōl',
'portal-url'  => 'Project:Calīxcuātl tocalpōl',
'privacy'     => 'Tlahcuilōlli piyaliznahuatīlli',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'tlahcuilōlli',

# Login and logout pages
'loginpagetitle'    => 'Ximomachiyōmaca/Ximocalaqui',
'yourpassword'      => 'Tlahtolichtacayo',
'yourpasswordagain' => 'Tlahtolichtacayo zapa',
'login'             => 'Ximomachiyōmaca/Ximocalaqui',
'userlogin'         => 'Ximomachiyōmaca/Ximocalaqui',
'yourlanguage'      => 'Tlahtolli:',

# Edit pages
'summary'          => 'Mopatlaliz',
'copyrightwarning' => '<small>Timitztlātlauhtiah xiquitta mochi mopatlaliz cana {{SITENAME}} tlatzayāna īpan [[GNU]]. Āqueh tlācah quipatlazqueh in motlahcuilōl auh tlatzayāna occeppa, intlā ahmō ticnequi īn, zātēpan ahmō titlahcuilōz nicān. Nō mitzihtoah in ōtitlahcuiloh ahmō quipiya in copyright nozo in yōllōxoxouhqui tlahcuilōlli. <strong>¡AHMŌ XITĒQUITILTILIA AHYŌLLŌXOXOUHQUI TLAHCUILŌLLI!</strong></small>',

# Recent changes
'recentchangestext' => 'Xiquitta in achi yancuīc patlaliztli huiquipan inīn zāzanilpan.',
'rcnote'            => 'Nicān in xōcoyōc <b>$1</b> patlaliztli īpan in xōcoyōc <b>$2</b> tōnalli cah, ōāxcānic $3',
'rclistfrom'        => 'Xiquittaz yancuīc patlaliztli īhuīcpa $1',
'rcshowhidebots'    => '$1 tepoztlatēquitiltilīlli',
'rclinks'           => 'Xiquittaz xōcoyōc $1 patlaliztli xōcoyōc $2 tōnalpan.<br />$3',

# Random page
'randompage' => 'Zāzotlein zāzanilli',

# Miscellaneous special pages
'allpages'     => 'Mochīntīn zāzanilli',
'ancientpages' => 'Huēhuehzāzanilli',

# Attribution
'lastmodifiedatby' => 'Inīn zāzanilli ōtlapatlac catca īpan $2, $1 īpal $3.', # $1 date, $2 time, $3 user

);
