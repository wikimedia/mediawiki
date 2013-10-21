<?php
/** Old English (Ænglisc)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Altai uul
 * @author Espreon
 * @author Gott wisst
 * @author JJohnson
 * @author Omnipaedista
 * @author Spacebirdy
 * @author Tsepelcory
 * @author Wōdenhelm
 */

$namespaceNames = array(
	NS_SPECIAL          => 'Syndrig',
	NS_TALK             => 'Gesprec',
	NS_FILE             => 'Biliþ',
	NS_FILE_TALK        => 'Biliþgesprec',
	NS_TEMPLATE         => 'Bysen',
	NS_TEMPLATE_TALK    => 'Bysengesprec',
	NS_HELP             => 'Help',
	NS_HELP_TALK        => 'Helpgesprec',
	NS_CATEGORY         => 'Flocc',
	NS_CATEGORY_TALK    => 'Floccgesprec',
);

$messages = array(
# User preference toggles
'tog-underline' => 'Mearc under hlencan:',
'tog-justify' => 'Macian cwidfloccas rihte',
'tog-hideminor' => 'Hȳdan lytela adihtunga in nīwra andwendinga getæle',
'tog-hidepatrolled' => 'Hȳdan weardoda adihtunga in nīwra andwendinga getæle',
'tog-newpageshidepatrolled' => 'Hȳdan weardode trametas in nīwra andwendinga getæle',
'tog-extendwatchlist' => 'Sprǣdan behealdungtæl tō īwenne ealla andwendinga, nā synderlīce þā nīwostan',
'tog-usenewrc' => 'Settan andwendunga on hēapas on trametum on nīwra andwendunga getæle and behealdungtæle',
'tog-numberheadings' => 'Settan rīm on fōrecwidas selflīce',
'tog-showtoolbar' => 'Īwan þā adihtunge tōlmearce',
'tog-editondblclick' => 'Adihtan trametas mid twifealdum mȳs swenge',
'tog-editsection' => 'Þafian dǣla adihtunge mid [adihtan] hlencum',
'tog-editsectiononrightclick' => 'Þafian dǣla adihtunge þurh swīðran healfe mȳs swengas on dǣla titulum',
'tog-showtoc' => 'Īwan innunge tabulan (for trametum þe mā þonne 3 fōrecwidas habbaþ)',
'tog-rememberpassword' => 'Gemynan mīne inmeldunge on þissum spearctellende (oþ $1 {{PLURAL:$1|dæg|dagas}} lengest)',
'tog-watchcreations' => 'Ēacnian mīn behealdungtæl mid trametum þā ic scieppe and ymelum þā ic hlade on nett.',
'tog-watchdefault' => 'Ēacnian mīn behealdungtæl mid trametum and ymelum þā ic adihte.',
'tog-watchmoves' => 'Ēacnian mīn behealdungtæl mid trametum and ymelum þā ic wege.',
'tog-watchdeletion' => 'Ēacnian mīn behealdungæl mid trametum and ymelum þā ic forlēose.',
'tog-minordefault' => 'Mearcian ealla adihtunga lytela tō gewunan',
'tog-previewontop' => 'Īwan fōrebysene ofer adihtunge mearce',
'tog-previewonfirst' => 'Īwan fōrebysene on forman adihtunge',
'tog-nocache' => 'Nā þafian þætte webbsēcend sette trametas on horde',
'tog-enotifwatchlistpages' => 'Sendan mē spearcǣrend þǣr tramet oþþe ymele on mīnum behealdungtæle sīe andwended.',
'tog-enotifusertalkpages' => 'Sendan mē spearcǣrend þǣr mīnes brūcendtrametes mōtung sī andwended',
'tog-enotifminoredits' => 'Sendan mē spearcǣrend þǣr trametas oþþe ymelan sīen efne lyt andwended.',
'tog-enotifrevealaddr' => 'Īwan mīnne spearcǣrenda naman on gecȳðendum spearcǣrendum',
'tog-shownumberswatching' => 'Īwan þæt rīm behealdendra brūcenda',
'tog-oldsig' => 'Genge selfmearc:',
'tog-fancysig' => 'Dōn selfmearce tō wikitexte (lēas ǣr gedōnes hlencan)',
'tog-uselivepreview' => 'Notian rihte īwde fōrebysene (on costnunge)',
'tog-forceeditsummary' => 'Cȳðan mē þǣr ic ne wrīte adihtunge sceortnesse',
'tog-watchlisthideown' => 'Hȳdan mīna adihtunga wiþ þæt behealdungtæl',
'tog-watchlisthidebots' => 'Hȳdan searuþrǣla adihtunga wiþ þæt behealdungtæl',
'tog-watchlisthideminor' => 'Hȳdan lytela adihtunga wiþ þæt behealdungtæl',
'tog-watchlisthideliu' => 'Hȳdan adihtunga fram inmeldodum brūcendum wiþ þæt behealdungtæl',
'tog-watchlisthideanons' => 'Hȳdan adihtunga fram uncūðum brūcendum wiþ þæt behealdungtæl',
'tog-watchlisthidepatrolled' => 'Hȳdan weardoda adihtunga wiþ þæt behealdungtæl',
'tog-ccmeonemails' => 'Sendan mē gelīcnessa þāra spearcǣrenda þe ic ōðrum brūcendum sende',
'tog-diffonly' => 'Nā īwan trametes innunge under scādungum',
'tog-showhiddencats' => 'Īwan gehȳdede floccas',
'tog-noconvertlink' => 'Ne lǣt hlencena titula āwendunge',
'tog-norollbackdiff' => 'Forlǣtan scādunge siþþan edweorc sīe gedōn',
'tog-useeditwarning' => 'Cȳðan mē þǣr ic afare fram adihtunge tramete þe gīet hæbbe unhordoda andwendunga.',
'tog-prefershttps' => 'Brūc ā sicore þēodednesse þā þū sī inmeldod',

'underline-always' => 'Ǣfre',
'underline-never' => 'Nǣfre',
'underline-default' => 'Scinnes oþþe webbsēcendes gewuna',

# Font style option in Special:Preferences
'editfont-style' => 'Stæfcynd for þǣre wrītunge on þǣre adihtunge mearce:',
'editfont-default' => 'Webbsēcendes gewunelicu gesetedness',
'editfont-monospace' => 'Ānes gemetes gebrǣded stæfcynd',
'editfont-sansserif' => 'Tægellēas stæfcynd',
'editfont-serif' => 'Tægelbǣre stæfcynd',

# Dates
'sunday' => 'Sunnandæg',
'monday' => 'Mōnandæg',
'tuesday' => 'Tīwesdæg',
'wednesday' => 'Wēdnesdæg',
'thursday' => 'Þunresdæg',
'friday' => 'Frigedæg',
'saturday' => 'Sæterndæg',
'sun' => 'Sun',
'mon' => 'Mōn',
'tue' => 'Tīw',
'wed' => 'Wēd',
'thu' => 'Þun',
'fri' => 'Fri',
'sat' => 'Sæt',
'january' => 'Æfterra Gēola',
'february' => 'Solmōnaþ',
'march' => 'Hrēþmōnaþ',
'april' => 'Ēastermōnaþ',
'may_long' => 'Þrimilcemōnaþ',
'june' => 'Sēarmōnaþ',
'july' => 'Mǣdmōnaþ',
'august' => 'Wēodmōnaþ',
'september' => 'Hāligmōnaþ',
'october' => 'Winterfylleþ',
'november' => 'Blōtmōnaþ',
'december' => 'Ǣrra Gēola',
'january-gen' => 'Æfterran Gēolan',
'february-gen' => 'Solmōnþes',
'march-gen' => 'Hrēþmōnþes',
'april-gen' => 'Ēastermōnþes',
'may-gen' => 'Þrimilcemōnþes',
'june-gen' => 'Sēarmōnþes',
'july-gen' => 'Mǣdmōnþes',
'august-gen' => 'Wēodmōnþes',
'september-gen' => 'Hāligmōnþes',
'october-gen' => 'Winterfylleðes',
'november-gen' => 'Blōtmōnþes',
'december-gen' => 'Ǣrran Gēolan',
'jan' => 'Ǣr Gē',
'feb' => 'Sol',
'mar' => 'Hrē',
'apr' => 'Ēas',
'may' => 'Þri',
'jun' => 'Sēar',
'jul' => 'Mǣd',
'aug' => 'Wēo',
'sep' => 'Hāl',
'oct' => 'Winterf',
'nov' => 'Blō',
'dec' => 'Æf Gē',
'january-date' => '$1. Æfterran Gēolan',
'february-date' => '$1. Solmōnaðes',
'march-date' => '$1. Hrēðmōnaðes',
'april-date' => '$1. Ēastermōnaðes',
'may-date' => '$1. Þrimilces',
'june-date' => '$1. Ǣrran Līðan',
'july-date' => '$1. Æfterran Līðan',
'august-date' => '$1. Wēodmōnaðes',
'september-date' => '$1. Hāligmōnaðes',
'october-date' => '$1. Winterfylleðes',
'november-date' => '$1. Blōtmōnaðes',
'december-date' => '$1. Ǣrran Gēolan',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Flocc|Floccas}}',
'category_header' => 'Trametas in "$1" flocce',
'subcategories' => 'Underfloccas',
'category-media-header' => 'Missenmiddel in "$1" flocce',
'category-empty' => "''Þes flocc hæfþ nū nǣngu gewritu oþþe missenmiddel.''",
'hidden-categories' => '{{PLURAL:$1|Gehȳded flocc|$1 Gehȳdedra flocca}}',
'hidden-category-category' => 'Gehȳdede floccas',
'category-subcat-count' => '{{PLURAL:$2|Þes flocc hæfþ synderlīce þone folgiendan underflocc.|Þes flocc hæfþ {{PLURAL:$1|þone folgiendan underflocc|þā folgiendan $1 underflocca}} - þæt fulle rīm is $2.}}',
'category-subcat-count-limited' => 'Þes flocc hæfþ {{PLURAL:$1|þisne underflocc|$1 þās underfloccas}}.',
'category-article-count' => '{{PLURAL:$2|Þes flocc hæfþ synderlīce þone folgiendan tramet.|{{PLURAL:$1|Se folgienda tramet is|Þā folgiendan $1 trameta sind}} in þissum flocce - þæt fulle rīm is $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|Se folgienda tramet is|$1 Þā folgiendan trametas sind}} on þissum flocce hēr.',
'category-file-count' => '{{PLURAL:$2|Þes flocc hæfþ synderlīce þā folgiendan ymelan.|{{PLURAL:$1|Sēo folgiende ymele is|Þā folgiendan $1 ymelena sind}} in þissum flocce - þæt fulle rīm is $2.}}',
'category-file-count-limited' => '{{PLURAL:$1|Þēos ymele is|$1 Þās ymelan sind}} in þissum flocce hēr.',
'listingcontinuesabbrev' => 'mā',
'index-category' => 'Getǣcnede trametas',
'noindex-category' => 'Ungetǣcnede trametas',
'broken-file-category' => 'Trametas þā habbaþ gebrocene hlencan mid ymelum',

'about' => 'Cȳþþu',
'article' => 'Innunge tramet',
'newwindow' => '(openaþ in nīwum ēagþyrele)',
'cancel' => 'Undōn',
'moredotdotdot' => 'Mā...',
'morenotlisted' => 'Mā þe nis on getæle...',
'mypage' => 'Mīn tramet',
'mytalk' => 'Mīn mōtung',
'anontalk' => 'Þisses IP naman mōtung',
'navigation' => 'Þurhfōr',
'and' => '&#32;and',

# Cologne Blue skin
'qbfind' => 'Findan',
'qbbrowse' => 'Þurhsēcan',
'qbedit' => 'Adihtan',
'qbpageoptions' => 'Þes tramet',
'qbmyoptions' => 'Mīne trametas',
'qbspecialpages' => 'Syndrige trametas',
'faq' => 'Oftost ascoda ascunga',
'faqpage' => 'Project:FAQ',

# Vector skin
'vector-action-addsection' => 'Besettan mearcunge',
'vector-action-delete' => 'Forlēosan',
'vector-action-move' => 'Wegan',
'vector-action-protect' => 'Beorgan',
'vector-action-undelete' => 'Scieppan tramet eft',
'vector-action-unprotect' => 'Andwendan beorgunge',
'vector-simplesearch-preference' => 'Ānfealdlīc sēcunge mearc (synderlīce on Vector scinne)',
'vector-view-create' => 'Scieppan',
'vector-view-edit' => 'Adihtan',
'vector-view-history' => 'Stǣr',
'vector-view-view' => 'Rǣdan',
'vector-view-viewsource' => 'Sēon fruman',
'actions' => 'Fremmunga',
'namespaces' => 'Namstedas',
'variants' => 'Missenlīcnessa',

'errorpagetitle' => 'Wōh',
'returnto' => 'Gān eft tō $1',
'tagline' => 'Fram {{SITENAME}}',
'help' => 'Help',
'search' => 'Sēcan',
'searchbutton' => 'Sēcan',
'go' => 'Gān',
'searcharticle' => 'Gān',
'history' => 'Trametes stǣr',
'history_short' => 'Stǣr',
'updatedmarker' => 'nīwod æfter mīnre lætestan sōcne',
'printableversion' => 'Ūtmǣlendlīc fadung',
'permalink' => 'Fæst hlenca',
'print' => 'Ūtmǣlan',
'view' => 'Sihþ',
'edit' => 'Adihtan',
'create' => 'Scieppan',
'editthispage' => 'Adihtan þisne tramet',
'create-this-page' => 'Scieppan þisne tramet',
'delete' => 'Forlēosan',
'deletethispage' => 'Forlēosan þisne tramet',
'undelete_short' => 'Scieppan {{PLURAL:$1|āne adihtunge|$1 adihtunga}} eft',
'viewdeleted_short' => 'Sēon {{PLURAL:$1|āne forlorene adihtunge|$1 forlorenra adihtunga}}',
'protect' => 'Beorgan',
'protect_change' => 'Wendan',
'protectthispage' => 'Beorgan þisne tramet',
'unprotect' => 'Andwendan beorgunge',
'unprotectthispage' => 'Andwendan beorgune þisses trametes',
'newpage' => 'Nīwe tramet',
'talkpage' => 'Sprecan ymbe þisne tramet',
'talkpagelinktext' => 'Mōtung',
'specialpage' => 'Syndrig tramet',
'personaltools' => 'Āgne tōlas',
'postcomment' => 'Nīwe dǣl',
'articlepage' => 'Sēon innunge tramet',
'talk' => 'Mōtung',
'views' => 'Sihþa',
'toolbox' => 'Tōlmearc',
'userpage' => 'Sēon brūcendes tramet',
'projectpage' => 'Sēon weorces tramet',
'imagepage' => 'Sēon ymelan tramet',
'mediawikipage' => 'Sēon ǣrendgewrita tramet',
'templatepage' => 'Sēon bysene tramet',
'viewhelppage' => 'Sēon helpes tramet',
'categorypage' => 'Sēon flocces tramet',
'viewtalkpage' => 'Sēon mōtunge',
'otherlanguages' => 'On ōðrum sprǣcum',
'redirectedfrom' => '(Edlǣded fram $1)',
'redirectpagesub' => 'Edlǣdunge tramet',
'lastmodifiedat' => 'Man nīwanost wende þisne tramet on þǣre $2 tīde þæs $1.',
'viewcount' => 'Þes tramet wæs gesawen {{PLURAL:$1|āne|$1 mǣla}}.',
'protectedpage' => 'Geborgen tramet',
'jumpto' => 'Gān tō:',
'jumptonavigation' => 'þurhfōr',
'jumptosearch' => 'sēcan',
'view-pool-error' => 'Wālā, þā þegntōlas nū oferlīce wyrcaþ.
Tō mænige brūcendas gesēcaþ tō sēonne þisne tramet.
Wē biddaþ þæt þū abīde scortne tīman ǣr þū gesēce to sēonne þisne tramet eft.

$1',
'pool-errorunknown' => 'Uncūþ wōh',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => 'Gecȳþness ymbe {{SITENAME}}',
'aboutpage' => 'Project:Gecȳþness',
'copyright' => 'Man mæg innunge under $1 findan.',
'copyrightpage' => '{{ns:project}}:Gelīcnessriht',
'currentevents' => 'Gelimpunga þisses tīman',
'currentevents-url' => 'Project:Gelimpunga þisses tīman',
'disclaimers' => 'Ætsacunga',
'disclaimerpage' => 'Project:Gemǣne ætsacung',
'edithelp' => 'Help on adihtunge',
'helppage' => 'Help:Innung',
'mainpage' => 'Hēafodtramet',
'mainpage-description' => 'Hēafodtramet',
'policy-url' => 'Project:Rǣd',
'portal' => 'Gemǣnscipes ingang',
'portal-url' => 'Project:Gemǣnscipes ingang',
'privacy' => 'Ānlēpnesse rǣd',
'privacypage' => 'Project:Ānlēpnesse rǣd',

'badaccess' => 'Þafunge wōh',
'badaccess-group0' => 'Þū ne mōst dōn þā dǣde þǣre þe þū hafast abede.',
'badaccess-groups' => 'Þēos dǣd þǣre þū hafast abeden is synderlīce alȳfedlic brūcendum on {{PLURAL:$2|þissum þrēate|ānum þāra þrēata}}: $1.',

'versionrequired' => '$1 fadung of MediaWiki is behēfe',
'versionrequiredtext' => '$1 fadung MediaWiki is behēfe tō notienne þisne tramet.
Seoh þone [[Special:Version|fadunge tramet]].',

'ok' => 'Gōd lā',
'retrievedfrom' => 'Fram "$1" begeten',
'youhavenewmessages' => 'Þū hæfst $1 ($2).',
'newmessageslink' => 'nīwu ǣrendgewritu',
'newmessagesdifflink' => 'nīwost andwendung',
'youhavenewmessagesfromusers' => 'Þū hafast $1 fram {{PLURAL:$3|ōðrum brūcende|$3 brūcenda}} ($2).',
'youhavenewmessagesmanyusers' => 'Þū hafast $1 fram manigum brūcendum ($2).',
'newmessageslinkplural' => '{{PLURAL:$1|nīwe ǣrendgewrit|nīwra ǣrendgewrita}}',
'youhavenewmessagesmulti' => 'Þū hæfst nīwu ǣrendu on $1',
'editsection' => 'adihtan',
'editold' => 'adihtan',
'viewsourceold' => 'Sēon fruman',
'editlink' => 'adihtan',
'viewsourcelink' => 'Sēon fruman',
'editsectionhint' => 'Adihtan dǣl: $1',
'toc' => 'Innung',
'showtoc' => 'īwan',
'hidetoc' => 'hȳdan',
'thisisdeleted' => 'Sēon oþþe nīwian $1?',
'viewdeleted' => 'Sēon $1 lā?',
'restorelink' => '{{PLURAL:$1|ān forloren ādihtung|$1 forlorenra adihtunga}}',
'feedlinks' => 'Ǣrendstrēam:',
'feed-invalid' => 'Ungenge underwrītunge ǣrendstrēames gecynd.',
'feed-unavailable' => 'Fruman ǣrendstrēamas ne sind gearwa',
'site-rss-feed' => '$1 RSS strēam',
'site-atom-feed' => '$1 Atom strēam',
'page-rss-feed' => '$1 RSS strēam',
'page-atom-feed' => '$1 Atom strēam',
'red-link-title' => '$1 (tramet ne biþ)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Tramet',
'nstab-user' => 'Brūcendes tramet',
'nstab-media' => 'Missendendebyrdnesse tramet',
'nstab-special' => 'Syndrig tramet',
'nstab-project' => 'Weorces tramet',
'nstab-image' => 'Ymele',
'nstab-mediawiki' => 'Ǣrendgewrit',
'nstab-template' => 'Bysen',
'nstab-help' => 'Helpes tramet',
'nstab-category' => 'Flocc',

# Main script and global functions
'nosuchaction' => 'Swilc dǣd ne biþ nā',
'nosuchactiontext' => 'Sēo þe se nettfrumfinded wile dōn nis genge.
Þū wēninga miswrite þone nettfrumfindend, oþþe folgode unrihtne hlencan.
Þis mæg ēac tācnian wōh on þǣre weorcwrithyrste þe is gebrocen fram {{SITENAME}}.',
'nosuchspecialpage' => 'Swilc syndrig tramet ne biþ nā',
'nospecialpagetext' => '<strong>Þū hafast abiden ungenges syndriges trametes.</strong>

Getæl gengra syndrigra trameta cann man findan be [[Special:SpecialPages|þǣm syndrigra trameta getæle]].',

# General errors
'error' => 'Wōh',
'databaseerror' => 'Cȳþþuhordes wōh',
'laggedslavemode' => "'''Warnung:''' Wēnunga næbbe se tramet nīwlīca nīwunga.",
'enterlockreason' => 'Wrīt race þǣre forwiernunge and apinsunge þæs tīman on þǣm bēo sēo forwiernung forlǣten',
'missingarticle-rev' => '(nīwung#: $1)',
'internalerror' => 'Inweard wōh',
'internalerror_info' => 'Inweard wōh: $1',
'fileappenderrorread' => 'Ne cūðe "$1" rǣdan under ēacnunge.',
'fileappenderror' => 'Ne cūðe "$2" mid "$1" ēacnian.',
'filerenameerror' => 'Ne cūðe ednemnan ymelan "$1" tō "$2".',
'filenotfound' => 'Ne cūðe findan ymelan "$1".',
'formerror' => 'Wōh: ne cūðe cȳþþugewrit forþsendan.',
'badarticleerror' => 'Þēos dǣd ne cann bēon gefremed on þissum tramete.',
'badtitle' => 'Nā genge titul',
'viewsource' => 'Sēon fruman',
'cascadeprotected' => 'Þes trament wæs geborgen wiþ adihtunge, for þǣm þe hē is befangen in þissum {{PLURAL:$1|tramente, þe is| tramentum, þe sind}} geborgen settum wyrcende þǣm cyre "cascading": $2',

# Virus scanner
'virus-badscanner' => '',
'virus-unknownscanner' => '',

# Login and logout pages
'logouttext' => "'''Þū eart nū ūtmeldod.'''

Þū canst ætfeolan þǣre nytte {{SITENAME}} tō ungecūðum, oþþe þū canst <span class='plainlinks'>[$1 inmeldian eft]</span> tō þǣm ylcan oþþe ōðrum brūcende.
Cnāw þæt sume trametas mihten gīet wesan geīwde swā þū wǣre gīet inmeldod, oþ þæt þū clǣnsie þīnes sēcendtōles hord.",
'welcomeuser' => 'Wilcume, $1!',
'yourname' => 'Þīn brūcendnama:',
'userlogin-yourname' => 'Brūcendnama:',
'userlogin-yourname-ph' => 'Inwrīt þīnne brūcendnaman',
'yourpassword' => 'Þafungword:',
'userlogin-yourpassword' => 'Þafungword',
'userlogin-yourpassword-ph' => 'Inwrīt þīn þafungword',
'createacct-yourpassword-ph' => 'Inwrīt þafungword',
'yourpasswordagain' => 'Wrītan þafungword eft:',
'createacct-yourpasswordagain' => 'Asēð þafungword',
'createacct-yourpasswordagain-ph' => 'Wrīt þafungword eft',
'remembermypassword' => 'Gemynan mīne inmeldunge on þissum webbsēcende (oþ $1 {{PLURAL:$1|dæg|daga}} lengest)',
'userlogin-remembermypassword' => 'Ætfeolan mīnre inmeldunge',
'yourdomainname' => 'Þīn geweald:',
'password-change-forbidden' => 'Þū ne canst awendan þafungword on þissum wiki.',
'login' => 'Inmeldian',
'nav-login-createaccount' => 'Inmeldian / wyrcan reccinge',
'loginprompt' => 'Þū scealt þafian cȳþþu grētunga tō inmeldienne in {{SITENAME}}.',
'userlogin' => 'Inmeldian / wyrcan reccinge',
'userloginnocreate' => 'Inmeldian',
'logout' => 'Ūtmeldian',
'userlogout' => 'Ūtmeldian',
'notloggedin' => 'Nā ingemeldod',
'userlogin-noaccount' => '',
'userlogin-joinproject' => '',
'nologin' => 'Næfst þū reccinge? $1',
'nologinlink' => 'Scieppan reccinge',
'createaccount' => 'Scieppan reccinge',
'gotaccount' => 'Hafast þū reccinge ǣr? $1.',
'gotaccountlink' => 'Inmeldian',
'createaccountmail' => 'Notian sceortne tīman hlētlic þafungword and sendan hit to þǣm spearcǣrenda naman þe is niðer',
'createaccountreason' => 'Racu:',
'badretype' => 'Þā þafungword þe write þū, bēoþ ungelīc.',
'userexists' => 'Se brūcendnama is ǣr gebrocen. Cēos lā ōðerne naman.',
'loginerror' => 'Inmeldunge wōh',
'createaccounterror' => 'Ne cūðe scieppan reccinge: $1',
'nocookiesnew' => 'Sēo brūcendreccing wæs gemacod, ac þū neart inmeldod.
{{SITENAME}} brȳcþ cȳþþu grētunga tō inmeldienne brūcendas.
Þū hafast forwierned cȳþþu grētunga.
Līef him lā, and siþþan inmelda þīnne nīwan brūcendnaman and þīn nīwe þafungword.',
'loginsuccesstitle' => 'Inmeldung gesǣlde',
'loginsuccess' => "'''Þu eart nū inmeldod tō {{SITENAME}} tō \"\$1\".'''",
'nosuchuser' => 'Þǣr nis nān brūcend þe hæfþ þone naman "$1".
Stafena micelnessa sind hefiga and ānlica on brūcendnamum.
Scēawa þīne wrītunge eft, oþþe [[Special:UserLogin/signup|sciepp nīwe reccinge]].',
'nosuchusershort' => 'Þǣr nis nān brūcend mid þǣm naman "$1".  Scēawa þīne wrītunge.',
'passwordtooshort' => 'Þafungword sculon habban læst {{PLURAL:$1|1 stafan|$1 stafena}}.',
'mailmypassword' => 'Sendan nīwe þafungword on spearcǣrende',
'acct_creation_throttle_hit' => 'Hwæt, þu hæfst gēo geseted {{PLURAL:$1|1 hordcleofan|$1 -}}. Þu ne canst settan ǣnige māran.',
'accountcreated' => 'Scōp reccinge',
'loginlanguagelabel' => 'Sprǣc: $1',

# Change password dialog
'resetpass' => 'Andwendan þafungword',
'oldpassword' => 'Eald þafungword:',
'newpassword' => 'Nīwe þafungword:',
'retypenew' => 'Wrīt nīwe þafungword eft:',
'resetpass-submit-loggedin' => 'Andwendan þafungword',
'resetpass-submit-cancel' => 'Undōn',

# Edit page toolbar
'bold_sample' => 'Þicce traht',
'bold_tip' => 'Þicce traht',
'italic_sample' => 'Flōwende traht',
'italic_tip' => 'Flōwende traht',
'link_sample' => 'Hlencan nama',
'link_tip' => 'Innanweard hlenca',
'extlink_sample' => 'http://www.example.com hlencan nama',
'extlink_tip' => 'Ūtanweard hlenca (beþenc þone http:// foredǣl)',
'headline_sample' => 'Hēafodlīnan traht',
'headline_tip' => '2. emnettes hēafodlīn',
'nowiki_sample' => 'Unendebyrdodne traht hēr settan',
'nowiki_tip' => 'Wiki endebyrdunge forgietan',
'image_sample' => 'Bisen.jpg',
'image_tip' => 'Ingesett ymele',
'media_sample' => 'Bisen.ogg',
'media_tip' => 'Ymelan hlenca',
'sig_tip' => 'Þīn selfmearc mid tīdmearce',
'hr_tip' => 'Brād līn (ne brūc oft)',

# Edit pages
'summary' => 'Scortness:',
'subject' => 'Ymbe/hēafodlīn:',
'minoredit' => 'Þēos is lytel adihtung',
'watchthis' => 'Behealdan þisne tramet',
'savearticle' => 'Hordian tramet',
'preview' => 'Fōrebysen',
'showpreview' => 'Īwan fōrebysene',
'showlivepreview' => 'Rihte geīwed fōrebysen',
'showdiff' => 'Īwan andwendunga',
'summary-preview' => 'Scortnesse fōrebysen:',
'blockednoreason' => 'nān racu gifen',
'whitelistedittext' => 'Þū scealt $1 to adihtenne trametas.',
'nosuchsectiontitle' => 'Ne cann dǣl findan',
'loginreqtitle' => 'Inmeldung ābeden',
'loginreqlink' => 'inmeldian',
'loginreqpagetext' => 'Þū scealt $1 tō sēonne ōðre trametas.',
'accmailtitle' => 'Þafungword wæs gesended.',
'accmailtext' => "Nā eahtodlīce geworht þafungword for [[User talk:$1|$1]] wæs tō $2 gesended.

Þū mōst þæt þafungword andwendan for þisse nīwan reccinge on þǣm ''[[Special:ChangePassword|andwendan þafungword]]'' tramete siþþan þū inmeldie.",
'newarticle' => '(Nīwe)',
'newarticletext' => "Þū hæfst hlencan tō tramete þe nū gīet ne stent gefolgod.
Tō scieppene þone tramet, onginn tō wrītenne in þǣre mearce þe is beneoþan (seoh þone [[{{MediaWiki:Helppage}}|helpes tramet]] ymb mā cȳþþu).
Gif þū hider be misfēnge cōme, cnoca þīnes webbsēcendes '''on bæc''' cnæpp.",
'usercssyoucanpreview' => "'''Rǣd:''' Brūc þone \"{{int:Forescēaƿian}}\" cnæpp tō costnienne þīne nīwan css/js wrītunge ǣr hit sīe hordod.",
'userjsyoucanpreview' => "'''Rǣd:''' Brūc þone 'Forescēawian' cnæpp tō āfandienne þīne nīwe css/js beforan sparunge.",
'updated' => '(Ednīwed)',
'note' => "'''Gewritincel:'''",
'previewnote' => "'''Beþenc þe þis is gīet efne fōrebysen.'''
Þīna andwendunga gīet ne sind hordoda!",
'editing' => 'Adihtende $1',
'editingsection' => 'Adihtende $1 (dǣl)',
'editingcomment' => 'Adihtende $1 (nīwe dǣl)',
'editconflict' => 'Adihtunge wiþdǣd: $1',
'yourtext' => 'Þīn traht',
'editingold' => "'''WARNUNG: Þū adihtest ealde fadunge þisses trametes.'''
Gif þū hine hordie, ǣnga andwendunga þā wǣron gedōn æfter þisse fadunge bēoþ sōðes forloren.",
'yourdiff' => 'Fǣgnessa',
'copyrightwarning2' => "Bidde behielde þæt man mæg ealla forðunga tō {{SITENAME}}
ādihtan, hweorfan, oþþe forniman.
Gif þū ne wille man þīn gewrit ādihtan unmildheorte, þonne hīe hēr ne forþsendan.<br />
Þū behǣtst ēac þæt þū selfa þis write, oþþe efenlǣhtest of sumre
folclicum āgnunge oþþe gelīcum frēom horde (sēo $1 for āscungum).
'''Ne forþsend efenlǣhtscielded weorc būtan þafunge!'''",
'templatesused' => '{{PLURAL:$1|Þēos bysen is|Þās bysena sind}} gebrocen on þissum tramete:',
'templatesusedpreview' => '{{PLURAL:$1|Þēos bysen is|Þās bysena sind}} gebrocen on þisre fōrebysene:',
'template-protected' => '(geborgen)',
'template-semiprotected' => '(sāmborgen)',
'hiddencategories' => 'Þes tramet is gesibb {{PLURAL:$1|1 gehȳdedum flocce|$1 gehȳdedra flocca}}:',
'nocreate-loggedin' => 'Þū ne hæfst þafunge to scieppenne nīwe trametas.',
'permissionserrors' => 'Þafunga wōh',
'permissionserrorstext-withaction' => 'Þū ne hæfst þafunge tō $2, for {{PLURAL:$1|þisre race|þissum racum}}:',
'recreate-moveddeleted-warn' => "'''Warnung: Þū edsciepst tramet þe wæs ǣr forloren.'''

Þu sceoldest smēagan, hwæðer hit gerādlīc sīe, forþ tō gānne mid þǣre adihtunge þisses trametes.
Þæt forlēosunge and wegunge ealdhord þisses trametes is hēr geīeht for behēfnesse:",

# History pages
'viewpagelogs' => 'Sēon þisses trametes ealdhold',
'nohistory' => 'Nis nān adihtunge stǣr for þissum tramete.',
'currentrev-asof' => 'Nīwost fadung on þǣre $3. tīde þæs $2.',
'revisionasof' => 'Nīwung fram $1',
'previousrevision' => '← Ieldre fadung',
'nextrevision' => 'Nīwre fadung →',
'currentrevisionlink' => 'Nīwost fadung',
'cur' => 'nū',
'next' => 'nīehst',
'last' => 'ǣr',
'history-fieldset-title' => 'Sēcan stǣr',
'histfirst' => 'ieldeste',
'histlast' => 'nīwoste',
'historyempty' => '(æmettig)',

# Revision feed
'history-feed-title' => 'Ednīwunge stǣr',
'history-feed-description' => 'Ednīwunge stǣr þisses trametes on þǣre wiki',
'history-feed-item-nocomment' => '$1 on $2',

# Revision deletion
'rev-deleted-comment' => '(fornōm adihtunge sceortnesse)',
'rev-deleted-user' => '(brūcendnaman fornōm)',
'rev-delundel' => 'īwan/hȳdan',
'rev-showdeleted' => 'īwan',
'revdelete-show-file-submit' => 'Gēa',
'revdelete-hide-text' => 'Hȳdan ednīwunge traht',
'revdelete-hide-image' => 'Hȳdan ymelan innunge',
'revdelete-hide-comment' => 'Hȳdan adihtunge sceortnesse',
'revdelete-hide-user' => 'Hȳdan adihtendes brūcendnaman/IP address',
'revdelete-radio-same' => '(nā andwendan)',
'revdelete-radio-set' => 'Gēa',
'revdelete-radio-unset' => 'Nese',
'revdel-restore' => 'andwendan īwunge',
'pagehist' => 'Trametes stǣr',
'revdelete-reasonotherlist' => 'Ōðru racu',

# History merging
'mergehistory-from' => 'Fruman tramet:',
'mergehistory-submit' => 'Geānlǣcan ednīwunga',
'mergehistory-reason' => 'Racu:',

# Merge log
'revertmerge' => 'Settan þā geānlǣcinge on bæc',

# Diffs
'history-title' => 'Ednīwunga stǣr for "$1"',
'lineno' => '$1. līne:',
'compareselectedversions' => 'Bemetan gecorena ednīwunga',
'editundo' => 'undōn',

# Search results
'searchresults' => 'Sōcne wæstmas',
'searchresults-title' => 'Sōcne wæstmas for "$1"',
'searchresulttext' => 'Gif þū wille mā leornian ymbe þā sēcunge on {{SITENAME}}, seoh [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle' => "Þū sōhtest '''[[:$1]]'''",
'searchsubtitleinvalid' => "Þū sōhtest '''$1'''",
'notitlematches' => 'Nis þǣr nǣnig swilc tramet mid þǣm naman',
'notextmatches' => 'Nis þǣr nǣnig swilc traht on nǣngum trametum',
'prevn' => 'ǣror {{PLURAL:$1|$1}}',
'nextn' => 'nīehst {{PLURAL:$1|$1}}',
'viewprevnext' => 'Sēon ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-new' => "'''Scieppan þone tramet \"[[:\$1]]\" on þissum wiki!'''",
'searchprofile-articles' => 'Innunge trametas',
'searchprofile-project' => 'Helpes and Weorca trametas',
'searchprofile-images' => 'Missenendebyrdness',
'searchprofile-everything' => 'Gehwæt',
'searchprofile-articles-tooltip' => 'Sēcan in $1',
'searchprofile-project-tooltip' => 'Sēcan in $1',
'searchprofile-images-tooltip' => 'Sēcan ymelan',
'search-result-size' => '$1 ({{PLURAL:$2|1 word|$2 worda}})',
'search-redirect' => '(edlǣded fram "$1")',
'search-section' => '(dǣl $1)',
'search-suggest' => 'Mǣnst þū: $1',
'search-interwiki-caption' => 'Sweostorweorc',
'search-interwiki-default' => '$1 becymas:',
'search-interwiki-more' => '(mā)',
'searchrelated' => 'gesibb',
'searchall' => 'eall',
'showingresults' => 'Īewan under oþ <b>$1</b> tōhīgunga onginnenda mid #<b>$2</b>.',
'showingresultsnum' => 'Under sind <b>$3</b> tóhígunga onginnende mid #<b>$2</b>.',
'powersearch' => 'Sēcan forþ',
'powersearch-legend' => 'Manigfeald sēcung',
'powersearch-ns' => 'Sēcan in namstedum:',
'powersearch-redir' => 'Settan edlǣdunge on getæle',
'powersearch-field' => 'Sēcan',
'search-external' => 'Ūtanweard sōcn',

# Preferences page
'preferences' => 'Fōreberunga',
'mypreferences' => 'Mīna fōreberunga',
'prefsnologin' => 'Nā inmeldod',
'prefs-skin' => 'Scynn',
'skin-preview' => 'Fōrebysen',
'prefs-datetime' => 'Tælmearc and tīd',
'prefs-rc' => 'Nīwa andwendunga',
'prefs-watchlist' => 'Wæccgetæl',
'saveprefs' => 'Hordian',
'rows' => 'Rǣwa:',
'columns' => 'Sȳla:',
'searchresultshead' => 'Sōcn',
'resultsperpage' => 'Tōhrīgunga tō īewenne for ǣlcum tramete:',
'recentchangescount' => 'Hū mæniga adihtunga to īwenne gewunelīce:',
'savedprefs' => 'Þīna fōreberunga wurdon gehordod.',
'timezonelegend' => 'Tīdgeard',
'servertime' => 'Þegntōles tīd is nū:',
'defaultns' => 'Elles sēcan on þissum namstedum:',
'default' => 'gewunelic',
'youremail' => 'Spearcǣrenda nama:',
'username' => '{{GENDER:$1|Brūcendnama}}:',
'yourrealname' => 'Þīn sōða nama:',
'yourlanguage' => 'Brūcendofermearces sprǣc',
'yourvariant' => 'Sprǣce wendung:',
'yourgender' => 'Gecynd:',
'gender-male' => 'Wer',
'gender-female' => 'Wīf',
'email' => 'Spearcǣrend',

# User rights
'userrights-user-editname' => 'Wrīt brūcendnaman:',
'editusergroup' => 'Adihtan brūcendhēapas',
'userrights-editusergroup' => 'Adihtan brūcendhēapas',
'saveusergroups' => 'Hordian brūcendhēapas',
'userrights-groupsmember' => 'Gesīþ lōcaþ tō:',
'userrights-reason' => 'Racu:',

# Groups
'group' => 'Hēap:',
'group-user' => 'Brūcendas:',
'group-bot' => 'Searuþrǣlas',
'group-sysop' => 'Bewitendas',
'group-bureaucrat' => 'Þegnas',
'group-suppress' => 'Ofergesihta',
'group-all' => '(eall)',

'group-user-member' => '{{GENDER:$1|brūcend|brūcicge}}',
'group-bot-member' => '{{GENDER:$1|searuþrǣl}}',
'group-sysop-member' => '{{GENDER:$1|bewitend|bewiticge}}',
'group-suppress-member' => 'oferȝesiht',

'grouppage-sysop' => '{{ns:project}}:Bewitendas',

# Special:Log/newusers
'newuserlogpage' => 'Brūcenda scieppunge ealdhord',

# User rights log
'rightslog' => 'Brūcenda riht cranic',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'adihtan þisne tramet',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|andwendung|andwendunga}}',
'recentchanges' => 'Nīwa andwendunga',
'recentchanges-legend' => 'Nīwra andwendunga cyras',
'recentchanges-feed-description' => 'Īwan þā nīwostan andwendunga þæs wiki mid þissum strēame',
'recentchanges-label-newpage' => 'Þēos adihtung scōp nīwne tramet',
'recentchanges-label-minor' => 'Þēos is lytel adihtung',
'recentchanges-label-bot' => 'Searuþrǣl fremede þās adihtunge',
'rcnote' => "Beneoðan {{PLURAL:$1|is '''1''' andwendung|sind þā æftemestan '''$1''' andwendunga}} in {{PLURAL:$2|þǣm æftermestan dæge|þǣm æftemestum '''$2''' daga}}, fram $5 on $4.",
'rcnotefrom' => "Niðer sind þā andwendunga fram '''$2''' (mǣst īweþ '''$1''').",
'rclistfrom' => 'Īwan nīwa andwendunga fram $1 and siþþan',
'rcshowhideminor' => '$1 lytela adihtunga',
'rcshowhidebots' => '$1 searuþrǣlas',
'rcshowhideliu' => '$1 inmeldode brūcendas',
'rcshowhideanons' => '$1 uncūðe brūcendas',
'rcshowhidemine' => '$1 mīna adihtunga',
'rclinks' => 'Īwan þā nīwostan $1 andwendunga in þissum nīehstum $2 daga<br />$3',
'diff' => 'scēad',
'hist' => 'stǣr',
'hide' => 'Hȳdan',
'show' => 'Īwan',
'minoreditletter' => 'ly',
'newpageletter' => 'N',
'boteditletter' => 'þr',
'rc_categories_any' => 'Ǣnig',
'rc-enhanced-expand' => 'Īwan stafas',
'rc-enhanced-hide' => 'Hȳdan stafas',

# Recent changes linked
'recentchangeslinked' => 'Sibba andwendunga',
'recentchangeslinked-feed' => 'Sibba andwendunga',
'recentchangeslinked-toolbox' => 'Sibba andwendunga',
'recentchangeslinked-title' => 'Andwendunga þā sind gesibba "$1"',
'recentchangeslinked-page' => 'Trametes nama:',
'recentchangeslinked-to' => 'Īwan andwendunga trameta þā habbaþ hlencan tō þissum tramete',

# Upload
'upload' => 'Hladan ymelan forþ',
'uploadbtn' => 'Hladan ymelan forþ',
'uploadnologin' => 'Nā inmeldod',
'uploaderror' => 'Wōh on forþhladunge',
'upload-permitted' => 'Geþafod ymelena cynn: $1.',
'upload-preferred' => 'Fōreboren ymelena cynn: $1.',
'upload-prohibited' => 'Forboden ymelena cynn: $1.',
'uploadlogpage' => 'Hladan ealdhord forþ',
'filename' => 'Ymelan nama',
'filedesc' => 'Scortness',
'filesource' => 'Fruma:',
'badfilename' => 'Ymelan nama wearþ gewend tō "$1".',
'savefile' => 'Hordian ymelan',
'uploadedimage' => 'forþhlōd "[[$1]]"',
'sourcefilename' => 'Fruman ymelan nama:',

'license' => 'Lēaf:',
'license-header' => 'Lēaf:',
'nolicense' => 'Nān is gecoren',
'license-nopreview' => '(Fōrebysen nis gearu)',

# Special:ListFiles
'listfiles-summary' => 'Þes syndriga tramet īweþ ealla forþ gehladena ymelan.
Gif se brūcend asifte hine. synderlīce sind ymelan geīwda þǣre þe se brūcend forþ hlōd þā nīwostan fadunge.',
'listfiles_search_for' => 'Sēcan missenendebyrdnesse naman:',
'imgfile' => 'ymele',
'listfiles' => 'Ymelena getæl',
'listfiles_date' => 'Tælmearc',
'listfiles_name' => 'Nama',
'listfiles_user' => 'Brūcend',
'listfiles_size' => 'Micelness',
'listfiles_description' => 'Tōwritenness',
'listfiles_count' => 'Fadunga',

# File description page
'file-anchor-link' => 'Ymele',
'filehist' => 'Ymelan stǣr',
'filehist-help' => 'Swing dæg/tīde mid mȳs to sēonne þā ymelan swā wæo hēo on þǣre tīde.',
'filehist-deleteall' => 'forlēosan eall',
'filehist-deleteone' => 'forlēosan',
'filehist-revert' => 'undōn',
'filehist-current' => 'nū',
'filehist-datetime' => 'Dæg/Tīd',
'filehist-thumb' => 'Lytelbiliþ',
'filehist-thumbtext' => 'Lytelbiliþ þǣre fadunge fram $3 on $2',
'filehist-nothumb' => 'Nān lytelbiliþ',
'filehist-user' => 'Brūcend',
'filehist-dimensions' => 'Micelnesse gemetu',
'filehist-filesize' => 'Ymelan micelness',
'filehist-comment' => 'Ymbsprǣc',
'filehist-missing' => 'Yemele is æfweard',
'imagelinks' => 'Hlencan tō ymelan',
'linkstoimage' => '{{PLURAL:$1|Se folgienda tramet hæfþ|Þā folgiendan trametas habbaþ}} hlencan tō þisre ymelan:',
'nolinkstoimage' => 'Þǣr ne sind nǣnge trametas þe habbaþ hlencan tō þisre ymelan.',
'morelinkstoimage' => 'Sēon [[Special:WhatLinksHere/$1|mā hlencan]] tō þisre ymelan.',
'duplicatesoffile' => '{{PLURAL:$1|Sēol folgiende ymele is gelīcnes|Þā folgiendan ymelan sind gelīcnessa}} þisse ymelan (seoh [[Special:FileDuplicateSearch/$2|mā cȳþþe ymbe þis]]):',
'sharedupload' => 'Þēos ymele is fram $1 and man mæg hīe brūcan on ōðrum weorcum.',
'uploadnewversion-linktext' => 'Hladan nīwe fadunge þisse ymelan forþ',

# File reversion
'filerevert-legend' => 'Settan ymelan on bæc',

# File deletion
'filedelete-submit' => 'Forlēosan',

# Unused templates
'unusedtemplateswlh' => 'ōðre hlencan',

# Random page
'randompage' => 'Gelimplīc tramet',

# Statistics
'statistics' => 'Cȳþþu',
'statistics-articles' => 'Innunge trametas',
'statistics-pages' => 'Trametas',
'statistics-users-active' => 'Hwate brūcendas',
'statistics-mostpopular' => 'Gesawenoste trametas',

'doubleredirects' => 'Twifealda edlǣdunga',

'brokenredirects' => 'Gebrocena edlǣdunga',
'brokenredirectstext' => 'Þā folgiendan edlǣdunga gāþ tō æfweardum trametum.',
'brokenredirects-edit' => 'adihtan',
'brokenredirects-delete' => 'forlēosan',

'withoutinterwiki' => 'Trametas būtan sprǣchlencum',
'withoutinterwiki-summary' => 'Þā folgiendan trametas nabbaþ hlencan tō ōðrum sprǣcfadungum.',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|bita|bitena}}',
'ncategories' => '$1 {{PLURAL:$1|flocca|flocca}}',
'nlinks' => '$1 {{PLURAL:$1|hlenca|hlencena}}',
'nmembers' => '$1 {{PLURAL:$1|gesīþ|gesīða}}',
'specialpage-empty' => 'Nis þǣr nāht þe āh cȳðan þes tramet.',
'lonelypages' => 'Ealdorlēase trametas',
'unusedimages' => 'Īdela ymelan',
'popularpages' => 'Folclīce trametas',
'wantedcategories' => 'Gewilnode floccas',
'wantedpages' => 'Gewilnode trametas',
'mostlinked' => 'Trametas mid þǣm mǣstan rīme hlencena',
'mostlinkedcategories' => 'Floccas mid þǣm mǣstan rīme hlencena',
'mostlinkedtemplates' => 'Bysena mid þǣm mǣstan rīme hlencena',
'prefixindex' => 'Ealle trametas mid fōredǣle',
'shortpages' => 'Scorte trametas',
'longpages' => 'Lange trametas',
'listusers' => 'Brūcenda getæl',
'newpages' => 'Nīwe trametas',
'newpages-username' => 'Brūcendes nama:',
'ancientpages' => 'Ieldestan trametas',
'move' => 'Wegan',
'movethispage' => 'Wegan þisne tramet',
'pager-newer-n' => '{{PLURAL:$1|nīwre 1|nīwran $1}}',
'pager-older-n' => '{{PLURAL:$1|ieldre 1|ieldran $1}}',

# Book sources
'booksources' => 'Bōcfruman',
'booksources-search-legend' => 'Sēcan bōcfruman',
'booksources-go' => 'Gān',
'booksources-text' => 'Niðer is getæl hlencena tō ōðrum webstedum þe cīpaþ nīwa and gebrocena bēc, and wēninga hæbben ēac mā cȳþþu ymbe bēc þe þu sēcst:',

# Special:Log
'specialloguserlabel' => 'Gelǣstende brūcend:',
'speciallogtitlelabel' => 'Ende (trametes titul oþþe brūcendes nama):',
'log' => 'Ealdhord',

# Special:AllPages
'allpages' => 'Ealle trametas',
'alphaindexline' => '$1 oþ $2',
'nextpage' => 'Nīehst tramet ($1)',
'prevpage' => 'Ǣrra tramet ($1)',
'allpagesfrom' => 'Īwan trametas fram:',
'allpagesto' => 'Īwan trametas oþ:',
'allarticles' => 'Ealle trametas',
'allinnamespace' => 'Ealle trametas (namstede: $1)',
'allpagesprev' => 'Ǣr',
'allpagesnext' => 'Nīehst',
'allpagessubmit' => 'Gān',

# Special:Categories
'categories' => 'Floccas',
'categoriespagetext' => '{{PLURAL:$1|Se folgienda flocc befēhþ|Þā folgiendan floccas befōþ}} trametas oþþe missenendebyrdmessa. [[Special:UnusedCategories|Nā gebrocene floccas]] ne sind geīwde hēr. Ēac seoh [[Special:WantedCategories|gewilnode floccas]].',

# Special:DeletedContributions
'sp-deletedcontributions-contribs' => 'forðunga',

# Special:LinkSearch
'linksearch' => 'Sēcung ūtanweardra hlencena',
'linksearch-ok' => 'Sēcan',

# Special:ListUsers
'listusers-noresult' => 'Nān brūcend wæs gefunden.',

# Special:ActiveUsers
'activeusers' => 'Getæl hwætra brūcenda',

# Special:ListGroupRights
'listgrouprights-group' => 'Hēap',
'listgrouprights-rights' => 'Riht',
'listgrouprights-helppage' => 'Help:Hēapes riht',
'listgrouprights-members' => '(getæl gesīða)',
'listgrouprights-removegroup' => 'Animan {{PLURAL:$2|þisne hēap|þās hēapas}}: $1',
'listgrouprights-addgroup-all' => 'Ēacnian mid eallum hēapum',
'listgrouprights-removegroup-all' => 'Animan ealle hēapas',

# Email user
'emailuser' => 'Wrītan spearcǣrend þissum brūcende',
'emailfrom' => 'Fram:',
'emailto' => 'Tō:',
'emailsubject' => 'Forþsetedness:',
'emailmessage' => 'Ǣrendgewrit:',
'emailsend' => 'Sendan',
'emailsent' => 'Ǣrendgewrit wæs gesend',
'emailsenttext' => 'Þīn ǣrendgewrit wæs gesend on spearcǣrende.',

# Watchlist
'watchlist' => 'Mīn behealdunggetæl',
'mywatchlist' => 'Mīn behealdunggetæl',
'removedwatchtext' => 'Se tramet "[[:$1]]" wæs fram [[Special:Watchlist|þīnum behealdunggetæle]] anumen.',
'watch' => 'Behealdan',
'watchthispage' => 'Behealdan þisne tramet',
'unwatch' => 'Ablinnan behealdunge',
'unwatchthispage' => 'Ablinnan behealdunge',
'watchlist-details' => '{{PLURAL:$1|Þǣr is $1 tramet|Þǣr sind $1 trameta}} on þīnum behealdunggetæle, nā arīmedum mōtunga trametum.',
'watchlistcontains' => 'Þīn behealdungtæl hæfþ $1 {{PLURAL:$1|tramet|trameta}}.',
'wlnote' => "Niðer {{PLURAL:$1|is sēo nīwoste andwendung|sind þā nīwostan '''$1''' andwendunga}} in {{PLURAL:$2|þǣre latostan tīde|þǣm latostan '''$2''' tīda}}, fram: $3, $4.",
'wlshowlast' => 'Īwan þā nīwostan $1 tīda $2 daga $3',
'watchlist-options' => 'Behealdungtæles cyras',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Behealdende...',
'unwatching' => 'Ablinnende behealdunge...',

'enotif_impersonal_salutation' => '{{SITENAME}} brūcend',
'enotif_lastvisited' => 'Sēon $1 for eallum andwendungum fram þīnum latostan cyme.',
'enotif_lastdiff' => 'Sēon $1 to sēonne þās andwendunge.',
'enotif_anon_editor' => 'uncūþ brūcend $1',
'created' => 'ȝescapen',
'changed' => 'hƿorfen',

# Delete
'deletepage' => 'Forlēosan tramet',
'excontent' => 'innung wæs: "$1"',
'excontentauthor' => 'innung wæs: \'$1\' (and se āna forðiend wæs "[[Special:Contributions/$2|$2]")',
'exblank' => 'tramet wæs æmettig',
'historywarning' => "'''Warnung''': Se tramet þe þū wilt forlēosan hafaþ stǣr mid nēan $1 {{PLURAL:$1|fadunge|fadunga}}:",
'actioncomplete' => 'Dǣd  is fulfyled',
'dellogpage' => 'Forlēosunge ealdhord',
'deletionlog' => 'forlēosunge ealdhord',
'deletecomment' => 'Racu:',
'deleteotherreason' => 'Ōðra/nīehst racu:',
'deletereasonotherlist' => 'Ōðru racu',

# Rollback
'rollback_short' => 'Settan on bæc',
'rollbacklink' => 'settan on bæc',
'rollbackfailed' => 'Bæcsettung tōsǣlde',
'editcomment' => "Þǣre adihtunge se cwide wæs: \"''\$1''\".",
'revertpage' => 'Onhwearf adihtunga fram [[Special:Contributions/$2|$2]] ([[User talk:$2|mōtung]]); wendede on bæc tō ǣrran fadunge fram [[User:$1|$1]]',

# Protect
'protectlogpage' => 'Beorges ealdhord',
'protectedarticle' => 'bearg "[[$1]]"',
'unprotectedarticle' => 'anōm beorgunge fram "[[$1]]"',
'protect-title' => 'Andwendan beorges emnet for "$1"',
'prot_1movedto2' => 'Wæg [[$1]] tō [[$2]]',
'protectcomment' => 'Racu:',
'protectexpiry' => 'Endaþ:',
'protect_expiry_invalid' => 'Endes tīd is unriht.',
'protect_expiry_old' => 'Endes tīd is in gēardagum.',
'protect-text' => "Þū meaht þæt beorges emnet sēon and hƿeorfan hēr for þǣre sīdan '''$1'''.",
'protect-default' => 'Eall brūcendas þafian',
'protect-fallback' => 'Synderlīce līefan brūcendum þā habbaþ "$1" lēafe',
'protect-level-autoconfirmed' => 'Līefan synderlīce selflīce afæstnodum brūcendum',
'protect-level-sysop' => 'Līefan synderlīce bewitendum',
'protect-summary-cascade' => 'beflōwende',
'protect-expiring' => 'endaþ $1 (UTC)',
'protect-cascade' => 'Beorgan ealle trametas þā sind befangen on þissum tramete (forþ brǣdende beorg)',
'protect-cantedit' => 'Þū ne meaht þæt beorges emnet hƿeorfan þisre sīdan, forþǣm ne hæfst þū þafunge to ādihtenne hīe.',
'protect-expiry-options' => '1 stund:1 hour,1 dæg:1 day,1 wucu:1 week,2 wuca:2 weeks,1 mōnaþ:1 month,3 mōnþas:3 months,6 mōnþas:6 months,1 gēar:1 year,unendiendlic:infinite',
'restriction-type' => 'Þafung:',
'restriction-level' => 'Gehæftes emnet:',

# Restrictions (nouns)
'restriction-edit' => 'Adihtan',
'restriction-move' => 'Wegan',
'restriction-create' => 'Scieppan',
'restriction-upload' => 'Hladan forþ',

# Restriction levels
'restriction-level-sysop' => 'full borgen',
'restriction-level-autoconfirmed' => 'sāmborgen',
'restriction-level-all' => 'ǣnig emnet',

# Undelete
'undeletebtn' => 'Edstaðola!',
'undeletelink' => 'sēon/nīwian',
'undeleteviewlink' => 'sēon',
'undelete-search-submit' => 'Sēcan',

# Namespace form on various pages
'namespace' => 'Namstede:',
'invert' => 'Onhwirfan gecorennesse',
'blanknamespace' => '(Hēafod)',

# Contributions
'contributions' => '{{GENDER:$1|Brūcendes}} forðunga',
'contributions-title' => 'Brūcendes forðunga for $1',
'mycontris' => 'Mīna forðunga',
'contribsub2' => 'For $1 ($2)',
'uctop' => '(genge)',
'month' => 'Fram mōnþe (and ǣr)',
'year' => 'Fram iēare (and ǣr)',

'sp-contributions-talk' => 'mōtung',
'sp-contributions-search' => 'Sēcan forðunga',
'sp-contributions-username' => 'IP nama oþþe brūcendes nama:',
'sp-contributions-submit' => 'Sēcan',

# What links here
'whatlinkshere' => 'Hwæt hæfþ hlencan hider',
'whatlinkshere-title' => 'Trametas þā habbaþ hlencan tō "$1"',
'whatlinkshere-page' => 'Tramet:',
'linkshere' => "Þā folgiendan trametas habbaþ hlencan tō: '''[[:$1]]'''",
'nolinkshere' => "Nǣnge trametas habbaþ hlencan tō '''[[:$1]]'''.",
'isredirect' => 'edlǣdunge tramet',
'istemplate' => 'bysene nytt',
'isimage' => 'ymelan hlenca',
'whatlinkshere-links' => '← hlencan',
'whatlinkshere-hideredirs' => '$1 edlǣdunga',
'whatlinkshere-hidetrans' => '$1 bysene nytta',
'whatlinkshere-hidelinks' => '$1 hlencan',
'whatlinkshere-filters' => 'Sifan',

# Block/unblock
'blockip' => 'Fortȳnan brūcend',
'ipbreason' => 'Racu:',
'ipbreasonotherlist' => 'Ōðru racu',
'ipbreason-dropdown' => '*Gemǣna fortȳnungraca
** Insettung falsre cȳþþe
** Animung innunge of trametum
** Spammlice hlencab tō ūtweardum webbstedum
** Insettung gedofes oþþe dwolunge in trametas
** Hwōpende gebǣru oþþe tirgung
** Miswendung manigra reccinga
** Uncwēme brūcendnama',
'ipbsubmit' => 'Fortȳnan þisne brūcend',
'ipbother' => 'Ōðeru tīd',
'ipboptions' => '2 tīda:2 hours,1 dæg:1 day,3 dagas:3 days,1 wucu:1 week,2 wuca:2 weeks,1 mōnaþ:1 month,3 mōnðas:3 months,6 mōnða:6 months,1 gēar:1 year,unendiende:infinite',
'ipbotheroption' => 'ōðer',
'ipbotherreason' => 'Ōðru oþþe nīehst racu:',
'ipblocklist-submit' => 'Sēcan',
'infiniteblock' => 'unendiende',
'expiringblock' => 'forealdaþ on $1 on $2',
'blocklink' => 'fortȳnan',
'unblocklink' => 'unfortȳnan',
'change-blocklink' => 'Andwendan fortȳnunge',
'contribslink' => 'forðunga',
'unblocklogentry' => 'unfortȳnde $1',
'block-log-flags-nocreate' => 'Forbēad tō scieppenne reccinge',

# Move page
'movearticle' => 'Wegan tramet:',
'newtitle' => 'Tō nīwum naman:',
'move-watch' => 'Behealdan frumtramet and endetramet',
'movepagebtn' => 'Wegan tramet',
'pagemovedsub' => 'Wegung spēdde',
'movepage-moved' => '\'\'\'"$1" wæs tō "$2"\'\'\' gewegen',
'articleexists' => 'Tramet on þǣm naman ǣr is, oþþe se nama þe þū cure nis riht.
Cēos ōðerne naman lā.',
'movedto' => 'gewegen tō',
'movetalk' => 'Wegan gesibbe mōtunge',
'movelogpage' => 'Wegan ealdhord',
'movereason' => 'Racu:',
'revertmove' => 'settan on bæc',

# Export
'export' => 'Ūtsendan trametas',

# Namespace 8 related
'allmessagesname' => 'Nama',
'allmessagesdefault' => 'Gewunelīc ǣrendgewrites traht',
'allmessagescurrent' => 'Þisses tīman ǣrendgewrites traht',
'allmessages-filter-unmodified' => 'Nā andwended',
'allmessages-filter-all' => 'Eall',
'allmessages-filter-modified' => 'Andwended',
'allmessages-language' => 'Sprǣc:',
'allmessages-filter-submit' => 'Gān',

# Thumbnails
'thumbnail-more' => 'Mǣrsian',
'filemissing' => 'Ymele is æfweard',

# Special:Import
'import' => 'Inbringan trametas',
'import-interwiki-submit' => 'Inbringan',
'importstart' => 'Inbringende trametas...',
'importnopages' => 'Nǣnge trametas to inbringenne.',
'importfailed' => 'Inbringung tōsǣlede: $1',
'importnotext' => 'Æmettig oþþe nān traht',
'importsuccess' => 'Inbringoþ gesǣlde!',
'import-noarticle' => 'Nān tramet tō inbringenne!',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Þīn brūcendtramet',
'tooltip-pt-mytalk' => 'Þīn mōtung',
'tooltip-pt-preferences' => 'Þīna fōreberunga',
'tooltip-pt-watchlist' => 'Getæl trameta þā behieltst þū ymbe andwendunga',
'tooltip-pt-mycontris' => 'Getæl þīnra forðunga',
'tooltip-pt-login' => 'Man þē byldeþ to inmeldienne; þēah, þis nis abeden',
'tooltip-pt-logout' => 'Ūtmeldian',
'tooltip-ca-talk' => 'Mōtung ymbe þone innunge tramet',
'tooltip-ca-edit' => 'Þū meaht þisne tramet adihtan. Brūc lā þone fōrebysene cnæpp ǣr þū hordie.',
'tooltip-ca-addsection' => 'Beginnan nīwne dǣl',
'tooltip-ca-viewsource' => 'Þes tramet is borgen.
Þū canst his fruman sēon.',
'tooltip-ca-history' => 'Ǣrran fadunga þisses trametes',
'tooltip-ca-protect' => 'Beorgan þisne tramet',
'tooltip-ca-unprotect' => 'Andwendan beorgune þisses trametes',
'tooltip-ca-delete' => 'Forlēosan þisne tramet',
'tooltip-ca-move' => 'Wegan þisne tramet',
'tooltip-ca-watch' => 'Ēacnian þīn behealdungtæl mid þissum tramete',
'tooltip-ca-unwatch' => 'Animan þisne tramet fram þīnum behealdungtæle',
'tooltip-search' => 'Sēcan {{SITENAME}}',
'tooltip-search-go' => 'Gān tō tramete þe hæbbe þisne rihte syndrigan naman, gif swilc tramet sīe',
'tooltip-search-fulltext' => 'Sēcan þisne traht on þǣm trametum',
'tooltip-p-logo' => 'Sēcan þone hēafodtramet',
'tooltip-n-mainpage' => 'Sēcan þone hēafodtramet',
'tooltip-n-mainpage-description' => 'Sēcan þone hēafodtramet',
'tooltip-n-portal' => 'Ymbe þæt weorc, hwæt meaht þū dōn, hwǣr man finde þing',
'tooltip-n-currentevents' => 'Findan ieldran cȳþþe ymbe nīwu gelimp',
'tooltip-n-recentchanges' => 'Getæl nīwra andwendunga on þǣm wiki',
'tooltip-n-randompage' => 'Hladan gelimplīcne tramet',
'tooltip-n-help' => 'Cunnunge stede',
'tooltip-t-whatlinkshere' => 'Getæl eallra wiki trameta þā habbaþ hlencan hider',
'tooltip-t-recentchangeslinked' => 'Nīwa andwendunga in trametum tō þǣm þes tramet hæbbe hlencan',
'tooltip-feed-rss' => 'RSS strēam for þissum tramete',
'tooltip-feed-atom' => 'Atom strēam for þissum tramete',
'tooltip-t-contributions' => 'Getæl forðunga þisses brūcendes',
'tooltip-t-emailuser' => 'Sendan spearcǣrend þissum brūcende',
'tooltip-t-upload' => 'Hladan ymelan forþ',
'tooltip-t-specialpages' => 'Getæl eallra syndrigra trameta',
'tooltip-t-print' => 'Gemǣnendlīc fadung þisses trametes',
'tooltip-t-permalink' => 'Fæst hlenca tō þisre fadunge þæs trametes',
'tooltip-ca-nstab-main' => 'Sēon þone innunge tramet',
'tooltip-ca-nstab-user' => 'Sēon þone brūcendes tramet',
'tooltip-ca-nstab-special' => 'Þes is syndrig tramet; þū ne meaht þone tramet hine selfne adihtan',
'tooltip-ca-nstab-project' => 'Sēon þone weorces tramet',
'tooltip-ca-nstab-image' => 'Sēon þone ymelan tramet',
'tooltip-ca-nstab-template' => 'Sēon þā bysene',
'tooltip-ca-nstab-category' => 'Sēon þone flocces tramet',
'tooltip-minoredit' => 'Mearcian þās tō lytelre adihtunge',
'tooltip-save' => 'Hordian þīna andwendunga',
'tooltip-preview' => 'Seoh fōrebysene þīnra andwendunga. Brūc þis lā ǣr þū hordie!',
'tooltip-diff' => 'Īwan þā andwendunga þā þū dydest wiþ þone traht',
'tooltip-compareselectedversions' => 'Sēon þā gescēad betweonan þǣm twǣm gecorenum fadungum þisses trametes',
'tooltip-watch' => 'Ēacnian þīn behealdungtæl mid þissum tramete',
'tooltip-undo' => '"Undōn" undēþ þās adihtunge and openaþ þǣre adihtunge bysene tō fōrebysene. Man cann secgan race on þǣre sceortnesse.',

# Attribution
'anonymous' => '{{PLURAL:$1|uncūþ brūcend|uncūðra brūcenda}} of {{SITENAME}}',
'siteuser' => '{{SITENAME}}n brūcend $1',
'others' => 'ōðru',
'anonusers' => '{{SITENAME}} {{PLURAL:$2|uncūþ brūcend|uncūðra brūcenda}} $1',

# Browsing diffs
'previousdiff' => '← Ieldre adihtung',
'nextdiff' => 'Nīwre adihtung →',

# Media information
'imagemaxsize' => "Mǣst biliðes micelness:<br />''(for ymelena amearcunga trametum)''",
'thumbsize' => 'Þumannæglmicelnes:',
'file-info-size' => '$1 × $2 pixels, ymelan micelu: $3, MIME cynn: $4',
'file-nohires' => 'Þǣr nis nǣnig māre micelness.',
'svg-long-desc' => 'SVG ymele, rihte $1 × $2 pixela, ymelan micelness: $3',
'show-big-image' => 'Full micelness',

# Special:NewFiles
'imagelisttext' => "Niðer is getæl '''$1''' {{PLURAL:$1|ymelan|ymelena}}, endebyrded on $2.",
'noimages' => 'Nāht tō sēonne.',
'ilsubmit' => 'Sēcan',
'bydate' => 'be tælmearce',

# Metadata
'metadata' => 'Metacȳþþu',
'metadata-expand' => 'Īwan ēacnode stafas',
'metadata-collapse' => 'Hȳdan ēacnode stafas',

# Exif tags
'exif-imagewidth' => 'Wīdnes',
'exif-imagelength' => 'Hīehþ',
'exif-compression' => 'Ȝeþryccungmōd',
'exif-ycbcrpositioning' => 'Y and C gesetednes',
'exif-imagedescription' => 'Biliðes nama',
'exif-artist' => 'Fruma',
'exif-usercomment' => 'Brūcendes trahtnunga',
'exif-exposuretime' => 'Blicestīd',
'exif-brightnessvalue' => 'APEX beorhtness',
'exif-lightsource' => 'Lēohtfruma',
'exif-whitebalance' => 'Hwītes blēos emnett',
'exif-sharpness' => 'Scearpnes',
'exif-gpslatituderef' => 'Norþ oþþe sūþ brǣdu',
'exif-gpslatitude' => 'Brǣdu',
'exif-gpslongituderef' => 'Ēast oþþe west lengu',
'exif-gpslongitude' => 'Lengu',
'exif-gpsmeasuremode' => 'Mētungmōd',
'exif-gpsimgdirection' => 'Rihtung þæs biliðes',

# Exif attributes
'exif-compression-1' => 'Unȝeþrycced',

'exif-meteringmode-0' => 'Uncūþ',
'exif-meteringmode-1' => 'Geþēawisc',
'exif-meteringmode-6' => 'Sām',
'exif-meteringmode-255' => 'Ōðer',

'exif-lightsource-0' => 'Uncūþ',
'exif-lightsource-1' => 'Dægeslēoht',

# Flash modes
'exif-flash-mode-3' => 'selffremmende mōd',

'exif-focalplaneresolutionunit-2' => 'yncas',

'exif-exposuremode-1' => 'Handlic blice',

'exif-whitebalance-0' => 'Selffremmende hwītefnett',

'exif-scenecapturetype-1' => 'Landsceap',

'exif-gaincontrol-0' => 'Nān',

'exif-contrast-1' => 'Sōfte',
'exif-contrast-2' => 'Heard',

'exif-sharpness-1' => 'Sōfte',
'exif-sharpness-2' => 'Heard',

'exif-subjectdistancerange-2' => 'Nēah hāwung',
'exif-subjectdistancerange-3' => 'Feorr hāwung',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Norþ brǣdu',
'exif-gpslatitude-s' => 'Sūþ brǣdu',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Ēast lengu',
'exif-gpslongitude-w' => 'West lengu',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Sōþ rihtung',

# External editor support
'edit-externally-help' => '(Sēon þā [//www.mediawiki.org/wiki/Manual:External_editors gearwunge gewissunga] ymb mā cȳþþe)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'eall',
'namespacesall' => 'eall',
'monthsall' => 'eall',
'limitall' => 'eall',

# Email address confirmation
'confirmemail_body' => 'Hwilchwega, gewēne þu of IP stōwe $1, hæfþ in namanbēc gestt ǣnne hordcleofan
"$2" mid þissum e-ǣrendes naman on {{SITENAME}}n.

Tō āsēðenne þæt þes hordcleofa tō þē gebyraþ and tō openienne
e-ǣrenda hwilcnessa on {{SITENAME}}n, opena þisne bend in þīnum webbscēawere:

$3

Gif þis is *nā* þū, ne folga þisne bend.

$5

Þēos āsēðungrūn forealdaþ æt $4.',

# Scary transclusion
'scarytranscludefailed' => '[Bysene feccung trucode for $1]',
'scarytranscludetoolong' => '[URL is tō lang]',

# Multipage image navigation
'imgmultigo' => 'Gān!',

# Table pager
'table_pager_first' => 'Forma tramet',
'table_pager_last' => 'Hindemesta tramet',
'table_pager_limit_submit' => 'Gān',
'table_pager_empty' => 'Nān becymas',

# Auto-summaries
'autosumm-blank' => 'Þā sīdan blæċode',
'autosumm-new' => "Sīdan mid '$1' ȝescapen",

# Watchlist editor
'watchlistedit-noitems' => 'Þīn behealdungtæl næfþ nǣnga ymelan.',
'watchlistedit-normal-title' => 'Adihtan behealdungtæl',
'watchlistedit-normal-legend' => 'Forniman naman fram behealdungtæle',
'watchlistedit-normal-submit' => 'Forniman naman',
'watchlistedit-raw-titles' => 'Naman:',
'watchlistedit-raw-done' => 'Þīn behealdungtæl wæs ednīwod.',

# Watchlist editing tools
'watchlisttools-view' => 'Sēon andwendunga',
'watchlisttools-edit' => 'Sēon and adihtan behealdungtæl',
'watchlisttools-raw' => 'Adihtan hrēaw behealdungtæl',

# Special:Version
'version' => 'Fadung',
'version-specialpages' => 'Syndrige trametas',
'version-other' => 'Ōðer',
'version-hooks' => 'Anglas',
'version-hook-name' => 'Angelnama',
'version-version' => '($1. fadung)',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'Ymelan nama:',
'fileduplicatesearch-submit' => 'Sēcan',

# Special:SpecialPages
'specialpages' => 'Syndrige trametas',
'specialpages-group-other' => 'Ōðre syndrige trametas',
'specialpages-group-users' => 'Brūcendas and riht',

# Special:BlankPage
'blankpage' => 'Tramet is æmettig',

# Special:Tags
'tags-edit' => 'adihtan',

# HTML forms
'htmlform-submit' => 'Forþsendan',
'htmlform-reset' => 'Undōn andwendunga',
'htmlform-selectorother-other' => 'Ōðer',

);
