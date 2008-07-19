<?php
/** Nahuatl (Nahuatl)
 *
 * @ingroup Language
 * @file
 *
 * @author Fluence
 * @author Ricardo gs
 * @author Rob Church <robchur@gmail.com>
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
'sunday'        => 'Tonatiutonal',
'monday'        => 'Metztlitonal',
'tuesday'       => 'Huitzilopochtonal',
'wednesday'     => 'Yacatlipotonal',
'thursday'      => 'Tezcatlipotonal',
'friday'        => 'Quetzalcoatonal',
'saturday'      => 'Tlaloctitonal',
'january'       => 'Tlacēnti',
'february'      => 'Tlaōnti',
'march'         => 'Tlayēti',
'april'         => 'Tlanāuhti',
'may_long'      => 'Tlamācuīlti',
'june'          => 'Tlachicuazti',
'july'          => 'Tlachicōnti',
'august'        => 'Tlachicuēiti',
'september'     => 'Tlachiucnāuhti',
'october'       => 'Tlamahtlācti',
'november'      => 'Tlamahtlāccēti',
'december'      => 'Tlamahtlācōnti',
'january-gen'   => 'Tlacēnti',
'february-gen'  => 'Tlaōnti',
'march-gen'     => 'Tlayēti',
'april-gen'     => 'Tlanāuhti',
'may-gen'       => 'Tlamācuīlti',
'june-gen'      => 'Tlachicuazti',
'july-gen'      => 'Tlachicōnti',
'august-gen'    => 'Tlachicuēiti',
'september-gen' => 'Tlachīucnāuhti',
'october-gen'   => 'Tlamahtlācti',
'november-gen'  => 'Tlamahtlāccēti',
'december-gen'  => 'Tlamahtlācōnti',
'jan'           => 'Cēn',
'feb'           => 'Ōnt',
'mar'           => 'Yēt',
'apr'           => 'Nāuh',
'may'           => 'Mācuīl',
'jun'           => 'Chicuacē',
'jul'           => 'Chicōn',
'aug'           => 'Chicuēi',
'sep'           => 'Chiucnāuh',
'oct'           => 'Mahtlāc',
'nov'           => 'Īhuāncē',
'dec'           => 'Īhuānōme',

# Categories related messages
'category_header'       => 'Tlahcuilōltin "$1" neneuhcāyōc',
'subcategories'         => 'Neneuhcāyōtzintli',
'category-media-header' => 'Media "$1" neneuhcāyōc',
'category-empty'        => "''Cah ahtlein inīn neneuhcāyōc.''",

'about'      => 'Ītechcopa',
'article'    => 'tlahcuilōlli',
'newwindow'  => '(Motlapoāz cē yancuīc tlanexillōtl)',
'qbfind'     => 'Tlatēmoāz',
'qbedit'     => 'Ticpatlāz',
'anontalk'   => 'Inīn IP ītēixnāmiquiliz',
'navigation' => 'ācalpapanōliztli',
'and'        => 'īhuān',

'search'         => 'Tlatēmoāz',
'searchbutton'   => 'Tlatēmoāz',
'go'             => 'Yāuh',
'searcharticle'  => 'Yāuh',
'history'        => 'tlahcuilōlloh',
'history_short'  => 'tlahcuilōlloh',
'edit'           => 'Ticpatlāz',
'create'         => 'Ticchīhuāz',
'editthispage'   => 'Ticpatlāz inīn zāzanilli',
'delete'         => 'tlapoloaz',
'newpage'        => 'Yancuīc zāzanilli',
'articlepage'    => 'Xiquittaz in tlahcuilōlli',
'talk'           => 'tēixnāmiquiliztli',
'lastmodifiedat' => 'Inīn zāzanilli ōtlapatlac catca īpan $2, $1.', # $1 date, $2 time
'jumptosearch'   => 'tlatēmoāz',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Ītechcopa {{SITENAME}}',
'copyright'            => 'Tlahcuilōltzin cah yōllōxoxouhqui īpan $1',
'disclaimers'          => 'Nahuatīllahtōl',
'edithelp'             => 'Patlaliztechcopa tēpalēhuiliztli',
'edithelppage'         => 'Tēpalēhuiliztli:¿Quēn motlahcuiloa cē zāzanilli?',
'mainpage'             => 'Calīxatl',
'mainpage-description' => 'Calīxatl',
'portal'               => 'Calīxcuātl tocalpōl',
'portal-url'           => 'Project:Calīxcuātl tocalpōl',
'privacy'              => 'Tlahcuilōlli piyaliznahuatīlli',

'editsection' => 'ticpatlāz',
'editold'     => 'ticpatlāz',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'tlahcuilōlli',

# Login and logout pages
'loginpagetitle'    => 'Ximomachiyōmaca/Ximocalaqui',
'yourpassword'      => 'Tlahtolichtacayo',
'yourpasswordagain' => 'Tlahtolichtacayo zapa',
'login'             => 'Ximomachiyōmaca/Ximocalaqui',
'userlogin'         => 'Ximomachiyōmaca/Ximocalaqui',
'yourlanguage'      => 'Tlahtōlli:',

# Edit pages
'summary'          => 'Mopatlaliz',
'minoredit'        => 'Inīn cah patlatzintli',
'previewnote'      => '<strong>¡Ca inīn moachtochīhualiz, auh mopatlaliz ahmō cateh ōmochīhuah nozan!</strong>',
'editing'          => 'Ticpatlahua',
'copyrightwarning' => '<small>Timitztlātlauhtiah xiquitta mochi mopatlaliz cana {{SITENAME}} tlatzayāna īpan $2 (huēhca ōmpa xiquitta $1). Āqueh tlācah quipatlazqueh in motlahcuilōl auh tlatzayāna occeppa, intlā ahmō ticnequi īn, zātēpan ahmō titlahcuilōz nicān. Nō mitzihtoah in ōtitlahcuiloh ahmō quipiya in copyright nozo in yōllōxoxouhqui tlahcuilōlli. <strong>¡AHMŌ XITĒQUITILTILIA AHYŌLLŌXOXOUHQUI TLAHCUILŌLLI!</strong></small>',

# Preferences page
'searchresultshead' => 'Tlatēmoliztli',

# Recent changes
'recentchanges'     => 'Yancuīc patlaliztli',
'recentchangestext' => 'Xiquitta in achi yancuīc patlaliztli huiquipan inīn zāzanilpan.',
'rcnote'            => 'Nicān in xōcoyōc <b>$1</b> patlaliztli īpan in xōcoyōc <b>$2</b> tōnalli cah, ōāxcānic $3',
'rclistfrom'        => 'Xiquittaz yancuīc patlaliztli īhuīcpa $1',
'rcshowhidebots'    => '$1 tepoztlatēquitiltilīlli',
'rclinks'           => 'Xiquittaz xōcoyōc $1 patlaliztli xōcoyōc $2 tōnalpan.<br />$3',
'newpageletter'     => 'Y',

# Special:Imagelist
'imagelist_name' => 'Tōcāitl',

# Random page
'randompage' => 'Zāzotlein zāzanilli',

'brokenredirects-edit' => '(ticpatlāz)',

# Miscellaneous special pages
'ancientpages' => 'Huēhuehzāzanilli',

# Book sources
'booksources-go' => 'Yāuh',

# Special:Log
'log-search-submit' => 'Yāuh',

# Special:Allpages
'allpages'          => 'Mochīntīn zāzanilli',
'alphaindexline'    => '$1 oc $2',
'allarticles'       => 'Mochīntīn tlahcuilōlli',
'allinnamespace'    => 'Mochīntīn zāzanilli (īpan $1)',
'allnotinnamespace' => 'Mochīntīn zāzanilli (quihcuāc $1)',

# Special:Categories
'categoriespagetext' => 'Inīn neneuhcāyōtl īpan inīn huiqui cateh.',
'categoriesfrom'     => 'Xiquittaz neneuhcāyōtl mopēhuah īca:',

# Delete/protect/revert
'actioncomplete' => 'Cēntetl',

# Restrictions (nouns)
'restriction-edit' => 'Ticpatlāz',

# Undelete
'undelete-search-submit' => 'Tlatēmoāz',

# Namespace form on various pages
'invert' => 'Tlacuepāz motlahtōl',

'sp-contributions-submit' => 'Tlatēmoāz',

# Block/unblock
'ipblocklist-submit' => 'Tlatēmoāz',
'anononlyblock'      => 'zan ahtōcā',

# Namespace 8 related
'allmessages'        => 'Mochīntīn Huiquimedia tlahcuilōlli',
'allmessagesname'    => 'Tōcāitl',
'allmessagescurrent' => 'Āxcān tlahcuilōlli',

# Tooltip help for the actions
'tooltip-p-logo' => 'Calīxatl',

# Attribution
'lastmodifiedatby' => 'Inīn zāzanilli ōtlapatlac catca īpan $2, $1 īpal $3.', # $1 date, $2 time, $3 user

# Special:Newimages
'ilsubmit' => 'Tlatēmoāz',
'bydate'   => 'tōnalcopa',

# EXIF tags
'exif-imagedescription' => 'Īxiptli ītōcā',

# AJAX search
'articletitles' => "Tlahcuilōlli mopēhuah īca ''$1''",

# Multipage image navigation
'imgmultigo' => '¡Yāuh!',

# Table pager
'table_pager_limit_submit' => 'Yāuh',

# Auto-summaries
'autosumm-new' => 'Yancuīc zāzanilli: $1',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'Tlatēmoāz',

);
