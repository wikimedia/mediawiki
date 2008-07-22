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
# User preference toggles
'tog-hideminor'           => 'Xiquitlātiāz tlapatlatzintli yancuīc tlapatlalizpan',
'tog-watchcreations'      => 'Xiquinchiyāz zāzaniltin tiquinchīhua',
'tog-watchdefault'        => 'Xiquinchiyāz zāzaniltin tiquimpatla',
'tog-watchmoves'          => 'Xiquinchiyāz zāzaniltin tiquinzaca',
'tog-watchdeletion'       => 'Xiquinchiyāz zāzaniltin tiquimpoloa',
'tog-previewonfirst'      => 'Xiquittāz achtochīhualiztli inic cē tlapatlalizpan',
'tog-enotifusertalkpages' => 'Nēchihtoa ihcuāc tlecpatla motēixnāmiquiliz',
'tog-watchlisthideown'    => 'Xiquitlātiāz mopatlaliz motlachiyalizpan',
'tog-showhiddencats'      => 'Xiquittāz motlātiani neneuhcāyōtl',

'underline-always' => 'Mochipa',
'underline-never'  => 'Aīcmah',

'skinpreview' => '(Xiquittāz quemeh yez)',

# Dates
'sunday'        => 'Tōnatiuhtōnal',
'monday'        => 'Mētztlitōnal',
'tuesday'       => 'Huītzilōpōchtōnal',
'wednesday'     => 'Yacatlipotōnal',
'thursday'      => 'Tezcatlipotōnal',
'friday'        => 'Quetzalcōātōnal',
'saturday'      => 'Tlāloctitōnal',
'sun'           => 'Tōn',
'mon'           => 'Mētz',
'tue'           => 'Huītz',
'wed'           => 'Yac',
'thu'           => 'Tez',
'fri'           => 'Quetz',
'sat'           => 'Tlāl',
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
'pagecategories'                 => '{{PLURAL:$1|Neneuhcāyōtl|Neneuhcāyōtl}}',
'category_header'                => 'Tlahcuilōltin "$1" neneuhcāyōc',
'subcategories'                  => 'Neneuhcāyōtzintli',
'category-media-header'          => 'Media "$1" neneuhcāyōc',
'category-empty'                 => "''Cah ahtlein inīn neneuhcāyōc.''",
'hidden-categories'              => '{{PLURAL:$1|Motlātiani neneuhcāyōtl|Motlātiani neneuhcāyōtl}}',
'hidden-category-category'       => 'Motlātiani neneuhcāyōtl', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|Inīn neneuhcāyōtl zan quipiya|Inīn neneuhcāyōtl quimpiya {{PLURAL:$1|inīn neneuhcāyōtzintli|inīn $1 neneuhcāyōtzintli}}, īhuīcpa $2.}}',
'category-subcat-count-limited'  => 'Inīn {{PLURAL:$1|neneuhcāyōtzintli cah|$1 neneuhcāyōtzintli cateh}} inīn neneuhcāyōc.',
'category-article-count'         => '{{PLURAL:$2|Inīn neneuhcāyōtl zan quipiya|Inīn neneuhcāyōtl quimpiya {{PLURAL:$1|inīn zāzanilli|inīn $1 zāzanilli}}, īhuīcpa $2.}}',
'category-article-count-limited' => 'Inīn {{PLURAL:$1|zāzanilli cah|$1 zāzanilli cateh}} inīn neneuhcāyōc.',
'category-file-count'            => '{{PLURAL:$2|Inīn neneuhcāyōtl zan quipiya|Inīn neneuhcāyōtl quimpiya {{PLURAL:$1|inīn tlahcuilōlli|inīn $1 tlahcuilōltin}}, īhuīcpa $2.}}',
'category-file-count-limited'    => 'Inīn {{PLURAL:$1|tlahcuilōlli cah|$1 tlahcuilōltin cateh}} inīn neneuhcāyōc.',

'about'          => 'Ītechcopa',
'article'        => 'tlahcuilōlli',
'newwindow'      => '(Motlapoāz cē yancuīc tlanexillōtl)',
'cancel'         => 'Ticcuepāz',
'qbfind'         => 'Tlatēmoāz',
'qbedit'         => 'Ticpatlāz',
'qbpageoptions'  => 'Inīn zāzanilli',
'qbmyoptions'    => 'Nozāzanil',
'qbspecialpages' => 'Nōncuahquīzqui āmatl',
'moredotdotdot'  => 'Huehca ōmpa...',
'mypage'         => 'Nozāzanilli',
'mytalk'         => 'Notēixnāmiquiliz',
'anontalk'       => 'Inīn IP ītēixnāmiquiliz',
'navigation'     => 'ācalpapanōliztli',
'and'            => 'īhuān',

'tagline'          => 'Īhuīcpa {{SITENAME}}',
'help'             => 'Tēpalēhuiliztli',
'search'           => 'Tlatēmoāz',
'searchbutton'     => 'Tlatēmoāz',
'go'               => 'Yāuh',
'searcharticle'    => 'Yāuh',
'history'          => 'tlahcuilōlloh',
'history_short'    => 'tlahcuilōlloh',
'info_short'       => 'Tlanōnōtzaliztli',
'print'            => 'Tictepoztlahcuiloāz',
'edit'             => 'Ticpatlāz',
'create'           => 'Ticchīhuāz',
'editthispage'     => 'Ticpatlāz inīn zāzanilli',
'create-this-page' => 'Ticchīhuāz inīn zāzanilli',
'delete'           => 'Ticpoloāz',
'deletethispage'   => 'Ticpoloāz inīn zāzanilli',
'protect'          => 'Ticquīxtiāz',
'protect_change'   => 'ticpatlāz tlaquīxtiliztli',
'protectthispage'  => 'Ticquīxtiāz inīn zāzanilli',
'newpage'          => 'Yancuīc zāzanilli',
'talkpagelinktext' => 'Tēixnāmiquiliztli',
'specialpage'      => 'Nōncuahquīzqui āmatl',
'personaltools'    => 'In tlein nitēquitiltilia',
'articlepage'      => 'Xiquittaz in tlahcuilōlli',
'talk'             => 'tēixnāmiquiliztli',
'userpage'         => 'Xiquittāz tlatēquitiltilīlli zāzanilli',
'mediawikipage'    => 'Xiquittāz tlahcuilōltzin zāzanilli',
'templatepage'     => 'Xiquittāz nemachiyōtīlli zāzanilli',
'viewhelppage'     => 'Xiquittāz tēpalehuiliztli zāzanilli',
'categorypage'     => 'Xiquittāz neneuhcāyōtl zāzanilli',
'viewtalkpage'     => 'Xiquittāz tēixnāmiquiliztli zāzanilli',
'otherlanguages'   => 'Occequīntīn tlahtōlcopa',
'redirectedfrom'   => '(Ōmotlacuep īhuīcpa $1)',
'redirectpagesub'  => 'Ōmotlacuep zāzanilli',
'lastmodifiedat'   => 'Inīn zāzanilli ōtlapatlac catca īpan $2, $1.', # $1 date, $2 time
'jumptonavigation' => 'ācalpapanōliztli',
'jumptosearch'     => 'tlatēmoāz',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Ītechcopa {{SITENAME}}',
'copyright'            => 'Tlahcuilōltzin cah yōllōxoxouhqui īpan $1',
'currentevents'        => 'Āxcāncāyōtl',
'currentevents-url'    => 'Project:Āxcāncāyōtl',
'disclaimers'          => 'Nahuatīllahtōl',
'edithelp'             => 'Patlaliztechcopa tēpalēhuiliztli',
'edithelppage'         => 'Help:¿Quēn motlahcuiloa cē zāzanilli?',
'faq'                  => 'FAQ',
'faqpage'              => 'Project:FAQ',
'mainpage'             => 'Calīxatl',
'mainpage-description' => 'Calīxatl',
'portal'               => 'Calīxcuātl tocalpōl',
'portal-url'           => 'Project:Calīxcuātl tocalpōl',
'privacy'              => 'Tlahcuilōlli piyaliznahuatīlli',

'badaccess-group0' => 'Tehhuātl ahmo tiquichīhua inōn tiquiēlēhuia.',
'badaccess-group1' => 'Inōn tiquiēlēhuia zan quichīhuah tlatēquitiltilīlli oncān: $1.',
'badaccess-group2' => 'Inōn tiquiēlēhuia zan quichīhuah tlatēquitiltilīlli oncān: $1.',
'badaccess-groups' => 'Inōn tiquiēlēhuia zan quichīhuah tlatēquitiltilīlli oncān: $1.',

'ok'                  => 'Cualli',
'youhavenewmessages'  => 'Tiquimpiya $1 ($2).',
'newmessagesdifflink' => 'achto tlapatlaliztli',
'editsection'         => 'ticpatlāz',
'editold'             => 'ticpatlāz',
'viewsourceold'       => 'xiquittāz tlahtōlcaquiliztilōni',
'toc'                 => 'Inīn tlahcuilōlco',
'showtoc'             => 'xiquittāz',
'hidetoc'             => 'Xiquitlātiāz',
'viewdeleted'         => '¿Tiquiēlēhuia tiquitta $1?',
'site-rss-feed'       => '$1 RSS huelītiliztli',
'site-atom-feed'      => '$1 Atom huelītiliztli',
'page-rss-feed'       => '"$1" RSS huelītiliztli',
'page-atom-feed'      => '"$1" RSS huelītiliztli',
'red-link-title'      => '$1 (ticchīhuāz)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Tlahcuilōlli',
'nstab-user'      => 'Tlatēquitiltilīlli',
'nstab-special'   => 'Nōncuahquīzqui',
'nstab-image'     => 'Īxiptli',
'nstab-mediawiki' => 'Huiquimedia tlahcuilōlli',
'nstab-template'  => 'Nemachiyōtīlli',
'nstab-help'      => 'Tēpalēhuiliztli',
'nstab-category'  => 'Neneuhcāyōtl',

# General errors
'badtitle'   => 'Ahcualli tōcāitl',
'viewsource' => 'Xiquittāz tlahtōlcaquiliztilōni',

# Login and logout pages
'logouttitle'             => 'Tiquīzāz',
'loginpagetitle'          => 'Ximomachiyōmaca/Ximocalaqui',
'yourname'                => 'Motlatēquitiltilīltōcā:',
'yourpassword'            => 'Tlahtolichtacayo',
'yourpasswordagain'       => 'Tlahtolichtacayo zapa',
'yourdomainname'          => 'Moāxcāyō',
'login'                   => 'Ximomachiyōmaca/Ximocalaqui',
'nav-login-createaccount' => 'Ximocalaqui / ximomachiyōmaca',
'userlogin'               => 'Ximomachiyōmaca/Ximocalaqui',
'logout'                  => 'Tiquīzāz',
'userlogout'              => 'Tiquīzāz',
'nologin'                 => '¿Ahmo tiquipiya calaquiliztli? $1.',
'gotaccountlink'          => 'Ximocalaqui',
'createaccountmail'       => 'e-mailcopa',
'youremail'               => 'E-mail:',
'username'                => 'Tlatēquitiltilīltōcāitl:',
'uid'                     => 'Tlatēquitiltilīlli ID:',
'yourrealname'            => 'Melāhuac motōcā:',
'yourlanguage'            => 'Motlahtōl:',
'email'                   => 'E-mail',
'loginsuccesstitle'       => 'Cualli ōticalac',
'loginsuccess'            => "'''Ōticalac {{SITENAME}} quemeh \"\$1\".'''",
'loginlanguagelabel'      => 'Tlahtōlli: $1',

# Edit page toolbar
'bold_sample'    => 'Tlīltic tlahcuilōlli',
'bold_tip'       => 'Tlīltic tlahcuilōlli',
'italic_sample'  => 'Italic tlahcuilōlli',
'italic_tip'     => 'Italic tlahcuilōlli',
'extlink_sample' => 'http://www.tlamantli.com Tōcāitl',

# Edit pages
'summary'           => 'Mopatlaliz',
'minoredit'         => 'Inīn cah patlatzintli',
'watchthis'         => 'Titlachiyāz inīn zāzanilli',
'savearticle'       => 'Ticpiyāz',
'preview'           => 'Xiquittāz achtochīhualiztli',
'showpreview'       => 'Xiquittāz achtochīhualiztli',
'showlivepreview'   => 'Niman achtochīhualiztli',
'showdiff'          => 'Xiquittāz tlapatlaliztli',
'whitelistedittext' => 'Tihuīquilia $1 ic ticpatla zāzaniltin.',
'loginreqlink'      => 'ximocalaqui',
'newarticle'        => '(Yancuīc)',
'previewnote'       => '<strong>¡Ca inīn moachtochīhualiz, auh mopatlaliz ahmō cateh ōmochīhuah nozan!</strong>',
'editing'           => 'Ticpatlahua $1',
'editingsection'    => 'Ticpatlahua $1 (tlahtōltzintli)',
'yourtext'          => 'Motlahcuilōl',
'copyrightwarning'  => '<small>Timitztlātlauhtiah xiquitta mochi mopatlaliz cana {{SITENAME}} tlatzayāna īpan $2 (huēhca ōmpa xiquitta $1). Āqueh tlācah quipatlazqueh in motlahcuilōl auh tlatzayāna occeppa, intlā ahmō ticnequi īn, zātēpan ahmō titlahcuilōz nicān. Nō mitzihtoah in ōtitlahcuiloh ahmō quipiya in copyright nozo in yōllōxoxouhqui tlahcuilōlli. <strong>¡AHMŌ XITĒQUITILTILIA AHYŌLLŌXOXOUHQUI TLAHCUILŌLLI!</strong></small>',

# Revision feed
'history-feed-empty' => 'In zāzanilli tiquiēlēhuia ahmo ia.
Hueliz ōmopolo huiqui nozo ōmozacac.
[[Special:Search|Xitēmoa huiquipan]] yancuīc huēyi zāzaniltin.',

# Revision deletion
'rev-delundel'    => 'xiquittāz/xiquitlātiāz',
'pagehist'        => 'Zāzanilli tlahcuilōlloh',
'deletedhist'     => 'Ōtlapolo tlahcuilōlloh',
'revdelete-uname' => 'tlatēquitiltilīltōcāitl',

# Search results
'prevn'     => '$1 achtopa',
'searchall' => 'mochīntīn',

# Preferences page
'saveprefs'         => 'Ticpiyāz',
'textboxsize'       => 'Tlapatlaliztli',
'searchresultshead' => 'Tlatēmoliztli',

# Groups
'group-all' => '(mochīntīn)',

# Recent changes
'recentchanges'     => 'Yancuīc patlaliztli',
'recentchangestext' => 'Xiquitta in achi yancuīc patlaliztli huiquipan inīn zāzanilpan.',
'rcnote'            => "Nicān {{PLURAL:$1|cah '''1''' tlapatlaliaztli|cateh in xōcoyōc '''$1''' tlapatlaliztli}} īpan xōcoyōc {{PLURAL:$2|tōnalli|'''$2''' tōnaltin}} īhuīcpa $5, $4.",
'rclistfrom'        => 'Xiquittaz yancuīc patlaliztli īhuīcpa $1',
'rcshowhidebots'    => '$1 tepoztlatēquitiltilīlli',
'rclinks'           => 'Xiquittaz xōcoyōc $1 patlaliztli xōcoyōc $2 tōnalpan.<br />$3',
'hide'              => 'Xiquitlātiāz',
'show'              => 'Xiquittāz',
'newpageletter'     => 'Y',

# Recent changes linked
'recentchangeslinked-title' => 'Tlapatlaliztli "$1" ītechcopa',
'recentchangeslinked-page'  => 'Zāzanilli ītōcā:',

# Upload
'upload'           => 'Tlahcuilōlquetza',
'uploadbtn'        => 'Tlahcuilōlquetza',
'reupload'         => 'Tiquiquetzāz occeppa',
'minlength1'       => 'Tlahcuilōltōcāitl quihuīlquilia huehca ōmpa cē tlahtōl.',
'successfulupload' => 'Cualli quetzaliztli',
'savefile'         => 'Quipiyāz tlahcuilōlli',
'watchthisupload'  => 'Titlachiyāz inīn zāzanilli',

'upload_source_file' => ' (cē tlahcuilōlli mochīuhpōhualhuazco)',

# Special:Imagelist
'imagelist_name' => 'Tōcāitl',
'imagelist_user' => 'Tlatēquitiltilīlli',

# Image description page
'filehist-deleteall' => 'tiquimpoloāz mochīntīn',
'filehist-deleteone' => 'ticpoloāz',
'filehist-user'      => 'Tlatēquitiltilīlli',

# File deletion
'filedelete'        => 'Ticpoloāz $1',
'filedelete-legend' => 'Ticpoloāz tlahcuilōlli',
'filedelete-submit' => 'Ticpoloāz',

# Random page
'randompage' => 'Zāzotlein zāzanilli',

# Statistics
'sitestatstext' => "{{PLURAL:$1|Cah '''1''' zāzanilli|Cateh '''$1''' zāzaniltin}} nicān.
Inīn quimpiya tēixnāmiquiliztli zāzanilli, {{SITENAME}} ītechcopa zāzanilli, machiyōtōn, tlacuepaliztli auh occequīntīn hueliz ahmo cualli.
Ahtle, in {{PLURAL:$2|cah '''1''' cualli zāzanilli|cateh '''$2''' cualli zāzaniltin}}.

{{PLURAL:$8|Nō cah '''$8''' tlahcuilōlli|Nō cateh '''$8''' tlahcuilōltin}} inīn huēychīuhpōhualhuazco.

In īhuīcpa huiqui īpēhualiz {{PLURAL:$3|ōcatca|ōcatcah}} '''$3''' tlahpaloliztli auh '''$4''' tlapatlaliztli.
Inīn quicētilia huehca '''$5''' tlapatlaliztli cēcem zāzanilli auh '''$6''' tlahpaloliztli cēcem tlapatlaliztli.

Huēiyacaliztli [http://www.mediawiki.org/wiki/Manual:Job_queue tequilcān] cah '''$7'''.",

'brokenredirects-edit'   => '(ticpatlāz)',
'brokenredirects-delete' => '(titlapoloāz)',

'withoutinterwiki-submit' => 'Xiquittāz',

# Miscellaneous special pages
'nbytes'            => '$1 byte',
'longpages'         => 'Huēiyac zāzaniltin',
'newpages'          => 'Yancuīc zāzaniltin',
'newpages-username' => 'Tlatēquitiltilīltōcāitl:',
'ancientpages'      => 'Huēhuehzāzanilli',
'move'              => 'Ticzacāz',
'movethispage'      => 'Ticzacāz inīn zāzanilli',

# Book sources
'booksources-go' => 'Yāuh',

# Special:Log
'specialloguserlabel'  => 'Tlatēquitiltilīlli:',
'speciallogtitlelabel' => 'Tōcāitl:',
'log-search-submit'    => 'Yāuh',

# Special:Allpages
'allpages'          => 'Mochīntīn zāzanilli',
'alphaindexline'    => '$1 oc $2',
'nextpage'          => 'Niman zāzanilli ($1)',
'prevpage'          => 'Achto zāzanilli ($1)',
'allarticles'       => 'Mochīntīn tlahcuilōlli',
'allinnamespace'    => 'Mochīntīn zāzanilli (īpan $1)',
'allnotinnamespace' => 'Mochīntīn zāzanilli (quihcuāc $1)',
'allpagesprev'      => 'Achtopa',
'allpagessubmit'    => 'Xiquittāz',

# Special:Categories
'categories'         => 'Neneuhcāyōtl',
'categoriespagetext' => 'Inīn neneuhcāyōtl īpan inīn huiqui cateh.',
'categoriesfrom'     => 'Xiquittaz neneuhcāyōtl mopēhuah īca:',

# Special:Listusers
'listusers-submit' => 'Xiquittāz',

# E-mail user
'emailfrom'    => 'Īhuīcpa',
'emailto'      => 'Īhuīc',
'emailmessage' => 'Huiquimedia tlahcuilōlli',

# Watchlist
'watchlist'     => 'Notlachiyaliz',
'mywatchlist'   => 'Notlachiyaliz',
'watchlistfor'  => "(ic '''$1''')",
'addedwatch'    => 'Ōmocētili tlachiyalizpan',
'watch'         => 'Ticchiyāz',
'watchthispage' => 'Titlachiyāz inīn zāzanilli',

'changed' => 'ōmotlacuep',
'created' => 'ōmochīuh',

# Delete/protect/revert
'deletepage'       => 'Ticpoloāz inīn zāzanilli',
'exblank'          => 'zāzanilli ōcatca iztāc',
'delete-confirm'   => 'Ticpoloāz "$1"',
'delete-legend'    => 'Ticpoloāz',
'actioncomplete'   => 'Cēntetl',
'rollback_short'   => 'Tlacuepāz',
'rollbacklink'     => 'tlacuepāz',
'rollback-success' => 'Ōmotlacuep $1 īpatlaliz; āxcān achto $2 īpatlaliz.',

# Restrictions (nouns)
'restriction-edit'   => 'Ticpatlāz',
'restriction-move'   => 'Ticzacāz',
'restriction-create' => 'Ticchīhuāz',
'restriction-upload' => 'Tlahcuilōlquetza',

# Undelete
'undelete-search-submit' => 'Tlatēmoāz',

# Namespace form on various pages
'invert' => 'Tlacuepāz motlahtōl',

# Contributions
'mycontris' => 'Nopatlaliz',
'month'     => 'Īhuīcpa mētztli (auh achtopa):',
'year'      => 'Xiuhhuīcpa (auh achtopa):',

'sp-contributions-submit' => 'Tlatēmoāz',

# What links here
'whatlinkshere'       => 'In tlein quitzonhuilia nicān',
'whatlinkshere-title' => 'Zāzanilli quitzonhuilia $1',
'whatlinkshere-page'  => 'Zāzanilli:',
'isredirect'          => 'ōmotlacuep zāzanilli',

# Block/unblock
'ipbotheroption'     => 'occē',
'ipblocklist-submit' => 'Tlatēmoāz',
'infiniteblock'      => 'ahtlamic',
'anononlyblock'      => 'zan ahtōcā',

# Move page
'move-page'        => 'Ticzacāz $1',
'move-page-legend' => 'Ticzacāz zāzanilli',
'movearticle'      => 'Ticzacāz zāzanilli:',
'move-watch'       => 'Titlachiyāz inīn zāzanilli',
'movepagebtn'      => 'Ticzacāz zāzanilli',
'movepage-moved'   => '<big>\'\'\'"$1" ōmotlacuep īhuīc "$2".\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.

# Namespace 8 related
'allmessages'        => 'Mochīntīn Huiquimedia tlahcuilōlli',
'allmessagesname'    => 'Tōcāitl',
'allmessagescurrent' => 'Āxcān tlahcuilōlli',

# Tooltip help for the actions
'tooltip-pt-userpage'       => 'Notlatēquitiltilīlzāzanil',
'tooltip-pt-mytalk'         => 'Notēixnāmiquiliz',
'tooltip-pt-logout'         => 'Tiquīzāz',
'tooltip-ca-protect'        => 'Ticquīxtiāz inīn zāzanilli',
'tooltip-ca-delete'         => 'Ticpoloāz inīn zāzanilli',
'tooltip-ca-move'           => 'Ticzacāz inīn zāzanilli',
'tooltip-p-logo'            => 'Calīxatl',
'tooltip-n-mainpage'        => 'Tictlahpoloāz in Calīxatl',
'tooltip-ca-nstab-main'     => 'Xiquittāz in tlahcuilōlli',
'tooltip-ca-nstab-user'     => 'Xiquittāz tlatēquitiltilīlli īzāzanil',
'tooltip-ca-nstab-template' => 'Xiquittāz in nemachiyōtīlli',
'tooltip-ca-nstab-help'     => 'Xiquittāz in tēpalēhuiliztli zāzanilli',
'tooltip-ca-nstab-category' => 'Xiquittāz in neneuhcāyōtl zāzanilli',
'tooltip-save'              => 'Ticpiyāz mopatlaliz',
'tooltip-preview'           => 'Xiquitta achtopa mopatlaliz, ¡timitztlātlauhtiah quitēquitiltilia achto ticpiya!',
'tooltip-upload'            => 'Ticpēhua quetzaliztli',

# Attribution
'lastmodifiedatby' => 'Inīn zāzanilli ōtlapatlac catca īpan $2, $1 īpal $3.', # $1 date, $2 time, $3 user
'others'           => 'occequīntīn',

# Browsing diffs
'previousdiff' => '← Achtopa',
'nextdiff'     => 'Niman →',

# Media information
'widthheightpage' => '$1×$2, $3 {{PLURAL:|zāzanilli|zāzanilli}}',

# Special:Newimages
'ilsubmit' => 'Tlatēmoāz',
'bydate'   => 'tōnalcopa',

# EXIF tags
'exif-imagedescription' => 'Īxiptli ītōcā',

'exif-meteringmode-255' => 'Occē',

'exif-lightsource-1'   => 'Tōnameyyōtl',
'exif-lightsource-10'  => 'Mixxoh',
'exif-lightsource-255' => 'Occequīntīn tlāhuīlli',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'mochīntīn',
'imagelistall'     => 'mochīntīn',
'watchlistall2'    => 'mochīntīn',
'namespacesall'    => 'mochīntīn',
'monthsall'        => '(mochīntīn)',

# Scary transclusion
'scarytranscludetoolong' => '[Cah URL achi huēiyac; xitēchpohpolhuia]',

# Trackbacks
'trackbackremove' => ' ([$1 Ticpoloāz])',

# Delete conflict
'recreate' => 'Ticchīhuāz occeppa',

# action=purge
'confirm_purge_button' => 'Cualli',

# AJAX search
'articletitles' => "Tlahcuilōlli mopēhuah īca ''$1''",

# Multipage image navigation
'imgmultipageprev' => '← achto zāzanilli',
'imgmultipagenext' => 'niman zāzanilli →',
'imgmultigo'       => '¡Yāuh!',

# Table pager
'table_pager_next'         => 'Niman zāzanilli',
'table_pager_prev'         => 'Achto zāzanilli',
'table_pager_first'        => 'Achtopa zāzanilli',
'table_pager_last'         => 'Xōcoyōc zāzanilli',
'table_pager_limit_submit' => 'Yāuh',

# Auto-summaries
'autosumm-new' => 'Yancuīc zāzanilli: $1',

# Live preview
'livepreview-loading' => 'Tēmohua...',

# Watchlist editing tools
'watchlisttools-view' => 'Xiquittāz huēyi tlapatlaliztli',

# Special:Version
'version-specialpages' => 'Nōncuahquīzqui āmatl',
'version-other'        => 'Occē',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'Tlatēmoāz',

# Special:SpecialPages
'specialpages'             => 'Nōncuahquīzqui āmatl',
'specialpages-group-login' => 'Ximocalaqui / ximomachiyōmaca',

);
