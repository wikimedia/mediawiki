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
'tog-hideminor' => 'Hȳdan lytela adihtunga in nīwra wendinga getæle',
'tog-hidepatrolled' => 'Hȳdan weardoda adihtunga in nīwra andwendinga getæle',
'tog-newpageshidepatrolled' => 'Hȳdan weardode trametas in nīwra andwendinga getæle',
'tog-extendwatchlist' => 'Sprǣdan wæccgetæl tō īwenne ealla andwendinga, nā synderlīce þā nīwostan',
'tog-usenewrc' => 'Settan andwendunge on hēapas æfter tramete on nīwra andwendunga getæle and wæccgetæle (þearf JavaScript)',
'tog-numberheadings' => 'Settan rīm on fōrecwidas selflīce',
'tog-showtoolbar' => 'Īwan þā adihtunge tōlmearce (þearf JavaScript)',
'tog-editondblclick' => 'Adihtan trametas mid twifealdum mȳs swenge (þearf JavaScript)',
'tog-editsection' => 'Þafian dǣla adihtunge mid [ādihtan] hlencum',
'tog-editsectiononrightclick' => 'Þafian dǣla adihtunge þurh swīðran healfe mȳs swengas on dǣla titulum (þearf JavaScript)',
'tog-showtoc' => 'Īwan innunge tabulan (for trametum þe mā þonne 3 fōrecwidas habbaþ)',
'tog-rememberpassword' => 'Gemynan mīne inmeldunge on þissum spearctellende (oþ $1 {{PLURAL:$1|dæg|dagas}} lengest)',
'tog-watchcreations' => 'Ēacnian mīn wæccgetæl mid trametum þā ic scieppe and ymelum þā ic hlade on nett.',
'tog-watchdefault' => 'Ēacnian mīn wæccgetæl mid trametum and ymelum þā ic adihte.',
'tog-watchmoves' => 'Ēacnian mīn wæccgetæl mid trametum and ymelum þā ic wege.',
'tog-watchdeletion' => 'Ēacnian mīn wæccgetæl mid trametum and ymelum þā ic forlēose.',
'tog-minordefault' => 'Mearcian ealla adihtunga lytela tō gewunan',
'tog-previewontop' => 'Īwan fōrebysene ofer adihtunge mearce',
'tog-previewonfirst' => 'Īwan fōrebysene on forman adihtunge',
'tog-nocache' => 'Nā þafian þæt webbsēcend sette trametas on horde',
'tog-enotifwatchlistpages' => 'Sendan mē spearcǣrend þǣr tramet oþþe ymele on mīnum wæccgetæle sīe andwended.',
'tog-enotifusertalkpages' => 'Sendan mē spearcǣrend þǣr mīnes brūcendtrametes mōtung sī awended',
'tog-enotifminoredits' => 'Sendan mē spearcǣrend þǣr trametas oþþe ymelan sīen efne lyt andwended.',
'tog-enotifrevealaddr' => 'Īwan mīnne spearcǣrenda naman on gecȳðendum spearcǣrendum',
'tog-shownumberswatching' => 'Īwan þæt rīm wæccendra brūcenda',
'tog-oldsig' => 'Genge selfmearc:',
'tog-fancysig' => 'Dōn selfmearce tō wikitexte (lēas ǣr gedōnes hlencan)',
'tog-showjumplinks' => 'Þafian "gān tō" gefērra hlencena',
'tog-uselivepreview' => 'Notian rihte īwde fōrebysene (þearf JavaScript) (on costnunge)',
'tog-forceeditsummary' => 'Cȳðan mē þǣr ic ne wrīte ādihtunge sceortnesse',
'tog-watchlisthideown' => 'Hȳdan mīna adihtunga wiþ þæt wæccgetæl',
'tog-watchlisthidebots' => 'Hȳdan searuþrǣla adihtunga wiþ þæt wæccgetæl',
'tog-watchlisthideminor' => 'Hȳdan lytela adihtunga wiþ þæt wæccgetæl',
'tog-watchlisthideliu' => 'Hȳdan adihtungas fram inmeldedum brūcendum wiþ þæt wæccgetæl',
'tog-watchlisthideanons' => 'Hȳdan adihtunga fram uncūðum brūcendum wiþ þæt wæccgetæl',
'tog-watchlisthidepatrolled' => 'Hȳdan weardoda adihtunga wiþ þæt wæccgetæl',
'tog-ccmeonemails' => 'Sendan mē gelīcnessa þāra spearcǣrenda þe ic ōðrum brūcendum sende',
'tog-diffonly' => 'Nā īwan trametes innunge under scādungum',
'tog-showhiddencats' => 'Īwan gehȳdede floccas',
'tog-noconvertlink' => 'Ne lǣt hlencena titula āwendunge',
'tog-norollbackdiff' => 'Forlǣtan scādunge siþþan edweorc sīe gedōn',
'tog-useeditwarning' => 'Cȳðan mē þǣr ic afare fram adihtunge tramete þe gīet hæbbe unhordoda andwendunga.',

'underline-always' => 'Ǣfre',
'underline-never' => 'Nǣfre',
'underline-default' => 'Scinnes oþþe webbsēcendes gewuna',

# Font style option in Special:Preferences
'editfont-style' => 'Stæfcynd for þǣre wrītunge on þǣre adihtunge mearce:',
'editfont-default' => 'Webbsēcendes geƿunelicu gesetedness',
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
'jan' => 'Ǣr Ȝē',
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

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Flocc|Floccas}}',
'category_header' => 'Trametas in flocce "$1"',
'subcategories' => 'Underfloccas',
'category-media-header' => 'Missenmiddel in flocce "$1"',
'category-empty' => "''Þes flocc hæfþ nū nǣngu geƿritu oþþe missenmiddel.''",
'hidden-categories' => '{{PLURAL:$1|Gehȳded flocc|$1 Gehȳdede floccas}}',
'hidden-category-category' => 'Gehȳdede floccas',
'category-subcat-count' => '{{PLURAL:$2|Þes flocc hæfþ synderlīce þone folgiendan underflocc.|Þes flocc hæfþ {{PLURAL:$1|þone folgiendan underflocc|þā folgiendan $1 underflocca}} - þæt fulle rīm is $2.}}',
'category-subcat-count-limited' => 'Þes flocc hæfþ {{PLURAL:$1|þisne underflocc|$1 þās underfloccas}}.',
'category-article-count' => '{{PLURAL:$2|Þes flocc hæfþ synderlīce þone folgiendan ānne tramet.|{{PLURAL:$1|Se folgienda tramet is|Þā folgiendan $1 trametaa sind}} in þissum flocce - þæt fulle rīm is $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|Se folgienda tramet is|$1 Þā folgiendan trametas sind}} on þissum flocce hēr.',
'category-file-count' => '{{PLURAL:$2|Þes flocc hæfþ synderlīce þā folgiendan ymelan.|{{PLURAL:$1|Sēo folgiende ymele is|Þā folgiendan $1 ymelena sind}} in þissum flocce - þæt fulle rīm is $2.}}',
'category-file-count-limited' => '{{PLURAL:$1|Þēos ymele is|$1 Þās ymelan sind}} in þissum flocce hēr.',
'listingcontinuesabbrev' => 'mā',
'index-category' => 'Getǣcnede trametas',
'noindex-category' => 'Ungetǣcnede trametas',
'broken-file-category' => 'Trametas þā habbaþ gebrocene hlencan mid ymelum',

'about' => 'Gecȳþness',
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
'vector-action-addsection' => 'Ēacnian mid mearcunge',
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
'printableversion' => 'Ūtmǣlendlicu fadung',
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

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'Gecȳþness ymbe {{SITENAME}}',
'aboutpage' => 'Project:Gecȳþness',
'copyright' => 'Man mæg innunge under $1 findan.',
'copyrightpage' => '{{ns:project}}:Gelīcnessriht',
'currentevents' => 'Gelimpunga þisses tīman',
'currentevents-url' => 'Project:Gelimpunga þisses tīman',
'disclaimers' => 'Ætsacunga',
'disclaimerpage' => 'Project:Gemǣne ætsacung',
'edithelp' => 'Help on adihtunge',
'edithelppage' => 'Help:Adihtung',
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
'dberrortext' => 'Cȳþþuhordes bēne endebyrdnesse wōh gelamp.
Þis mæg mǣnan wōh on þǣre weorcwrithyrste.
Sēo nīwoste gesōhte cȳþþuhordes bēn wæs:
<blockquote><code>$1</code></blockquote>
fram innan wyrcunge "<code>$2</code>".
Cȳþþuhord ageaf wōh "<code>$3: $4</code>"',
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
'virus-badscanner' => "Јастыра конфигурация: Јарты јок сканер ''$1''",
'virus-unknownscanner' => 'Јарты јок антивирус:',

# Login and logout pages
'logouttext' => "'''Þū eart nū ūtmeldod.'''

Þū canst ætfeolan þǣre nytte {{SITENAME}} tō ungecūðum, oþþe þū canst <span class='plainlinks'>[$1 inmeldian eft]</span> tō þǣm ylcan oþþe ōðrum brūcende.
Cnāw þæt sume trametas mihten gīet wesan geīwde swā þū wǣre gīet inmeldod, oþ þæt þū clǣnsie þīnes sēcendtōles hord.",
'welcomeuser' => 'Кӱӱнзеп кирер, $1!',
'yourname' => 'Þīn brūcendnama:',
'userlogin-yourname' => 'Эдинчиниҥ ады:',
'userlogin-yourname-ph' => 'Эдинчиниҥ адын кийдирер:',
'yourpassword' => 'Þafungword:',
'userlogin-yourpassword' => 'Јажытту сӧс',
'userlogin-yourpassword-ph' => 'Јажытту сӧсӧзрди кийдирер',
'createacct-yourpassword-ph' => 'Јажытту сӧсти кийдирер',
'yourpasswordagain' => 'Wrītan þafungword eft:',
'createacct-yourpasswordagain' => 'Јажытту сӧсти јӧпсинер',
'createacct-yourpasswordagain-ph' => 'Јажытту сӧсти јаҥынаҥ кийдирер',
'remembermypassword' => 'Gemynan mīne inmeldunge on þissum webbsēcende (oþ $1 {{PLURAL:$1|dæg|daga}} lengest)',
'userlogin-remembermypassword' => 'Артырар кирип алганымды',
'yourdomainname' => 'Þīn geweald:',
'password-change-forbidden' => 'Бу викиде, слерде јажытту сӧстӧрди солыыр арга јок.',
'login' => 'Inmeldian',
'nav-login-createaccount' => 'Inmeldian / wyrcan reccinge',
'loginprompt' => 'Слер кукиларды јарадар учурлу {{SITENAME}} сайтка турган болзор.',
'userlogin' => 'Inmeldian / wyrcan reccinge',
'userloginnocreate' => 'Inmeldian',
'logout' => 'Ūtmeldian',
'userlogout' => 'Ūtmeldian',
'notloggedin' => 'Nā ingemeldod',
'userlogin-noaccount' => 'Слерде аккаунт јок по?',
'userlogin-joinproject' => '{{SITENAME}} кирер',
'nologin' => 'Слерде аккаунт јок по? $1.',
'nologinlink' => 'Scieppan reccinge',
'createaccount' => 'Scieppan reccinge',
'gotaccount' => 'Белен аккаунт бар ба? $1.',
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
'mailmypassword' => 'Sendan nīwe þafungword on spearcǣrend',
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
'headline_tip' => 'Emnet 2 hēafodlīn',
'nowiki_sample' => 'Unȝeƿorhtne traht hēr stellan',
'nowiki_tip' => 'Ƿiki ȝeƿeorc forȝietan',
'image_sample' => 'Bisen.jpg',
'image_tip' => 'Impod biliþ',
'media_sample' => 'Bisen.ogg',
'media_tip' => 'Fīlhlenċe',
'sig_tip' => 'Þīn namanseȝn mid tīdstempunge',
'hr_tip' => 'Brād līn (ne oft brūcan)',

# Edit pages
'summary' => 'Scortnes:',
'subject' => 'Ymbe/hēafodlīn:',
'minoredit' => 'Þes is lȳtl ādiht',
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
'accmailtext' => "Hlīetemaced þafungƿord for [[User talk:$1|$1]] ƿæs to $2 sended.

Þū meaht þæt þafungƿord hƿeorfan for þissum nīƿan hordcleofa on þǣre ''[[Special:ChangePassword|change password]]'' sīde æfter inmeldiende.",
'newarticle' => '(Nīwe)',
'newarticletext' => "Þu hæfst bende tō tramete gefolgod þe nū gīet ne stendeþ.
Tō scieppene þone tramet, onginn þyddan in þǣre boxe under (sēo þone [[{{MediaWiki:Helppage}}|helptramet]] for mā gefrǣge).
Gif þu hider misfōn cōme, cnoca þā þīnne webbscēaweres '''on bæc''' cnæpp.",
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
'copyrightwarning2' => "Bidde behielde þæt man mæȝ ealla forðunga tō {{SITENAME}}
ādihtan, hƿeorfan, oþþe forniman.
Ȝif þū ne ƿille man þīn ȝeƿrit ādihtan unmildheorte, þonne hīe hēr ne forþsendan.<br />
Þū behǣtst ēac þæt þū selfa þis ƿrite, oþþe efenlǣhtest of sumre
folcliċum āgnunge oþþe ȝelīċum frēom horde (sēo $1 for āscungum).
'''Ne forþsend efenlǣhtscielded ƿeorc būtan þafunge!'''",
'templatesused' => '{{PLURAL:$1|Þēos bysen is|Þās bysena sind}} gebrocen on þissum tramete:',
'templatesusedpreview' => '{{PLURAL:$1|Þēos bysen is|Þās bysena sind}} gebrocen on þisre fōrebysene:',
'template-protected' => '(geborgen)',
'template-semiprotected' => '(sāmborgen)',
'hiddencategories' => 'Þes tramet is gesibb {{PLURAL:$1|1 gehȳdedum flocce|$1 gehȳdedra flocca}}:',
'nocreate-loggedin' => 'Þū ne hæfst þafunge to scieppenne nīwe trametas.',
'permissionserrors' => 'Þafunga wōh',
'permissionserrorstext-withaction' => 'Þū ne hæfst þafunge tō $2, for {{PLURAL:$1|þisre race|þissum racum}}:',
'recreate-moveddeleted-warn' => "'''Warnung: Þu edsciepst tramet þe wæs ǣr āfeorsod.'''

Þu sceoldest smēagan, hwæðer hit gerādlic sīe, forþ tō gānne mid ādihtunge þisses trametes.
Þæt āfeorsungbred þisses trametes is hēr geīeht for behēfnesse:",

# History pages
'viewpagelogs' => 'Sēon þisses trametes ealdhold',
'nohistory' => 'Nis nān ādihtunge stǣr for þissum tramete.',
'currentrev-asof' => 'Nīwost fadung on $1',
'revisionasof' => 'Nīwung fram $1',
'previousrevision' => '← Ieldre fadung',
'nextrevision' => 'Nīwre fadung →',
'currentrevisionlink' => 'Nīwost fadung',
'cur' => 'nū',
'next' => 'nīehst',
'last' => 'ǣr',
'history-fieldset-title' => 'Sēcan stǣr',
'histfirst' => 'Ǣrest',
'histlast' => 'Nīwost',
'historyempty' => '(æmettig)',

# Revision feed
'history-feed-title' => 'Ednīwunge stǣr',
'history-feed-description' => 'Ednīƿunge stǣr þisse sīdan on þǣre ƿiki',
'history-feed-item-nocomment' => '$1 on $2',

# Revision deletion
'rev-deleted-comment' => '(fornōm cwide)',
'rev-deleted-user' => '(brūcendnama fornōm)',
'rev-delundel' => 'scēaƿian/hȳdan',
'rev-showdeleted' => 'scēaƿan',
'revdelete-show-file-submit' => 'Ȝēa',
'revdelete-hide-text' => 'Ednīƿungtraht hȳdan',
'revdelete-hide-image' => 'Fīlinnoþ hȳdan',
'revdelete-hide-comment' => 'Ādihtcƿide hȳdan',
'revdelete-hide-user' => 'Ādihteres brūcendnama/IP address hȳdan',
'revdelete-radio-same' => '(ne hƿeorfan)',
'revdelete-radio-set' => 'Ȝēa',
'revdelete-radio-unset' => 'Nā',
'revdel-restore' => 'scēaƿnesse hƿeorfan',
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
'yourrealname' => 'Þīn rihtnama*',
'yourlanguage' => 'Brūcendofermearces sprǣc',
'yourvariant' => 'Sprǣce wendung:',
'yourgender' => 'Ȝecynd:',
'gender-male' => 'Ƿer',
'gender-female' => 'Frēo',
'email' => 'E-ǣrende',

# User rights
'userrights-user-editname' => 'Brūcendnama ƿrītan:',
'editusergroup' => 'Ādihtan Brūcendsamþrēatas',
'userrights-editusergroup' => 'Brūcenda clīeƿenas ādihtan:',
'saveusergroups' => 'Brūcenda clīeƿenas sparian',
'userrights-groupsmember' => 'Ȝesīþ þæs:',
'userrights-reason' => 'Racu:',

# Groups
'group' => 'Clīeƿen:',
'group-user' => 'Brūcendas:',
'group-bot' => 'Searuþralas',
'group-sysop' => 'Beƿitendas',
'group-bureaucrat' => 'Tōþeȝnas',
'group-suppress' => 'Oferȝesihta',
'group-all' => '(eall)',

'group-user-member' => '{{GENDER:$1|brūcend}}',
'group-bot-member' => '{{GENDER:$1|searuþrǣl}}',
'group-sysop-member' => '{{GENDER:$1|bewitend}}',
'group-suppress-member' => 'oferȝesiht',

'grouppage-sysop' => '{{ns:project}}:Beƿitendas',

# Special:Log/newusers
'newuserlogpage' => 'Brūcenda ȝesceaft ȝetalu',

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
'rcnote' => "Under {{PLURAL:$1|... '''1''' ...|sind þā æftemestan '''$1''' hweorfunga}} in {{PLURAL:$2|...|þǣm æftemestum '''$2''' dagum}}, . . $5, $4.",
'rcnotefrom' => "Niðer sind þā andwendunga æfter '''$2''' (mǣst īweþ '''$1''').",
'rclistfrom' => 'Īwan nīwa andwendunga fram $1 and siþþan',
'rcshowhideminor' => '$1 lytela adihtunga',
'rcshowhidebots' => '$1 searuþrǣlas',
'rcshowhideliu' => '$1 inmeldede brūcendas',
'rcshowhideanons' => '$1 uncūðe brūcendas',
'rcshowhidemine' => '$1 mīna adihtunga',
'rclinks' => 'Īwan þā nīwostan $1 andwendunga in þissum nīehstum $2 daga<br />$3',
'diff' => 'scēad',
'hist' => 'stǣr',
'hide' => 'hȳdan',
'show' => 'Īwan',
'minoreditletter' => 'ly',
'newpageletter' => 'N',
'boteditletter' => 'þr',
'rc_categories_any' => 'Ǣnig',
'rc-enhanced-expand' => 'Ȝehanda sēon (þearf JavaScript)',
'rc-enhanced-hide' => 'Ȝehanda hȳdan',

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
'filehist-help' => 'Cnæpp on dæȝe/tīde to sēonne þā fīlan sƿā ƿæs hēo on þǣre tīde.',
'filehist-deleteall' => 'forlēosan eall',
'filehist-deleteone' => 'forlēosan',
'filehist-revert' => 'undōn',
'filehist-current' => 'nū',
'filehist-datetime' => 'Dæg/Tīd',
'filehist-thumb' => 'Lytelbiliþ',
'filehist-thumbtext' => 'Lytelbiliþ for fadunge fram $1 and siþþan',
'filehist-nothumb' => 'Nān lytelbiliþ',
'filehist-user' => 'Brūcend',
'filehist-dimensions' => 'Miċela',
'filehist-filesize' => 'Fīlmiċelnes',
'filehist-comment' => 'Ymbsprǣċ',
'filehist-missing' => 'Fīl lēas',
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
'randompage' => 'Gelimplic tramet',

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
'pager-newer-n' => '{{PLURAL:$1|nīwran 1|nīwran $1}}',
'pager-older-n' => '{{PLURAL:$1|ieldran 1|ieldran $1}}',

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
'linksearch-ok' => 'Sēċan',

# Special:ListUsers
'listusers-noresult' => 'Nān brūcend wæs gefunden.',

# Special:ActiveUsers
'activeusers' => 'Hƿata brūcenda ȝetalu',

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
'watchlist-details' => '{{PLURAL:$1|Þǣr is $1 tramet|Þǣr sind $1 trameta}} on þīnum behealdunggetæle, nā arīmedum mōtungum.',
'watchlistcontains' => 'Þīn behealdungtæl hæfþ $1 {{PLURAL:$1|tramet|trameta}}.',
'wlnote' => "Niðer {{PLURAL:$1|is sēo nīwoste andwendung|sind þā nīwostan '''$1''' andwendunga}} in {{PLURAL:$2|þǣre latostan tīde|þǣm latostan '''$2''' tīda}}, fram: $3, $4.",
'wlshowlast' => 'Īwan þā latostan $1 tīda $2 daga $3',
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
'protect-summary-cascade' => 'beflōƿende',
'protect-expiring' => 'endaþ $1 (UTC)',
'protect-cascade' => 'Beorgan ealle trametas þā sind befangen on þissum tramete (forþ brǣdende beorg)',
'protect-cantedit' => 'Þū ne meaht þæt beorges emnet hƿeorfan þisre sīdan, forþǣm ne hæfst þū þafunge to ādihtenne hīe.',
'protect-expiry-options' => '1 stund:1 hour,1 dæg:1 day,1 wucu:1 week,2 wuca:2 weeks,1 mōnaþ:1 month,3 mōnþas:3 months,6 mōnþas:6 months,1 gēar:1 year,unendiendlic:infinite',
'restriction-type' => 'Þafung:',
'restriction-level' => 'Ȝehæftes emnet:',

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
'invert' => 'Cyre edƿendan',
'blanknamespace' => '(Hēafod)',

# Contributions
'contributions' => '{{GENDER:$1|Brūcendes}} forðunga',
'contributions-title' => 'Brūcendforðunga for $1',
'mycontris' => 'Mīna forðunga',
'contribsub2' => 'For $1 ($2)',
'uctop' => '(hēafod)',
'month' => 'Fram mōnþe (and ǣror)',
'year' => 'Fram ȝēare (and ǣror)',

'sp-contributions-talk' => 'ȝespreċ',
'sp-contributions-search' => 'Forðunga sēċan',
'sp-contributions-username' => 'IP address oþþe brūcendnama:',
'sp-contributions-submit' => 'Sēċan',

# What links here
'whatlinkshere' => 'Hƿæt hæfþ hlenċan hider',
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
'ipboptions' => '2 tīda:2 hours,1 dæg:1 day,3 dagas:3 days,1 wucu:1 week,2 wuca:2 weeks,1 mōnaþ:1 month,3 mōnðas:3 months,6 mōnðas:6 months,1 gēar:1 year,unendiende:infinite',
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
'proxyblocksuccess' => 'Gedōn.',

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
'allmessagesdefault' => 'Gewunelic ǣrendgewrites traht',
'allmessagescurrent' => 'Þisses tīman ǣrendgewrites traht',
'allmessages-filter-unmodified' => 'Nā andwended',
'allmessages-filter-all' => 'Eall',
'allmessages-filter-modified' => 'Andwended',
'allmessages-language' => 'Sprǣċ:',
'allmessages-filter-submit' => 'Gān',

# Thumbnails
'thumbnail-more' => 'Mǣrsian',
'filemissing' => 'Þrǣd unandƿeard',

# Special:Import
'import' => 'Sīdan inbringan',
'import-interwiki-submit' => 'Inbringan',
'importstart' => 'Inbringende sīdan...',
'importnopages' => 'Nān sīdan to inbringenne.',
'importfailed' => 'Inbringung tōsǣlede: $1',
'importnotext' => 'Ǣmtiȝ oþþe nān traht',
'importsuccess' => 'Inbringoþ ȝesǣled!',
'import-noarticle' => 'Nān sīde to inbringenne!',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Þīnu brūcendsīde',
'tooltip-pt-mytalk' => 'Þīnu ȝespreċsīde',
'tooltip-pt-preferences' => 'Þīna forebearƿan',
'tooltip-pt-watchlist' => 'Sēo ȝetalu sīdena þe ƿæccest þū for hƿearfum',
'tooltip-pt-mycontris' => 'Ȝetalu þīnra forðunga',
'tooltip-pt-login' => 'Man þē byldeþ to inmeldienne; þēah, þis nis ābeden',
'tooltip-pt-logout' => 'Ūtmeldian',
'tooltip-ca-talk' => 'Ȝespreċ ymbe þǣre innoþsīdan',
'tooltip-ca-edit' => 'Þū meaht þās sīdan ādihtan. Bidde brūc þone forescēaƿecnæpp fore spariende',
'tooltip-ca-addsection' => 'Beginnan nīwne dǣl',
'tooltip-ca-viewsource' => 'Þes tramet is borgen.
Þū canst his fruman sēon.',
'tooltip-ca-history' => 'Ǣrram fadunga þisses trametes',
'tooltip-ca-protect' => 'Beorgan þisne tramet',
'tooltip-ca-unprotect' => 'Andwendan beorgune þisses trametes',
'tooltip-ca-delete' => 'Forlēosan þisne tramet',
'tooltip-ca-move' => 'Wegan þisne tramet',
'tooltip-ca-watch' => 'Ēacnian þīn behealdungtæl mid þissum tramete',
'tooltip-ca-unwatch' => 'Animan þisne tramet fram þīnum behealdungtæle',
'tooltip-search' => 'Sēcan {{SITENAME}}',
'tooltip-search-go' => 'Gān tō tramete þe hæbbe þisne rihte syndigan naman, gif swilc tramet sīe',
'tooltip-search-fulltext' => 'Sēcan þisne traht on þǣm trametum',
'tooltip-p-logo' => 'Sēcan þone hēafodtramet',
'tooltip-n-mainpage' => 'Sēcan þone hēafodtramet',
'tooltip-n-mainpage-description' => 'Sēcan þone hēafodtramet',
'tooltip-n-portal' => 'Ymbe þæt weorc, hwæt meaht þū dōn, hwǣr man finde þing',
'tooltip-n-currentevents' => 'Findan ieldran cȳþþe ymbe nīwu gelimp',
'tooltip-n-recentchanges' => 'Getæl nīwra andwendunga on þǣm wiki',
'tooltip-n-randompage' => 'Hladan gelimplicne tramet',
'tooltip-n-help' => 'Cunnunge stede',
'tooltip-t-whatlinkshere' => 'Getæl eallra wiki trameta þā habbaþ hlencan hider',
'tooltip-t-recentchangeslinked' => 'Nīwa andwendunga in trametum tō þǣm þes tramet hæbbe hlencan',
'tooltip-feed-rss' => 'RSS strēam for þissum tramete',
'tooltip-feed-atom' => 'Atom strēam for þissum tramete',
'tooltip-t-contributions' => 'Getæl forðunga þisses brūcendes',
'tooltip-t-emailuser' => 'Sendan spearcǣrend þissum brūcende',
'tooltip-t-upload' => 'Hladan ymelan forþ',
'tooltip-t-specialpages' => 'Getæl eallra syndrigra trameta',
'tooltip-t-print' => 'Gemǣnendliċu fadung þisses trametes',
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
'tooltip-preview' => 'Seoh fōrebysene þīna andwendunga. Brūc þīs lā ǣr þū hordie!',
'tooltip-diff' => 'Īwan þā andwendunga þā þū dydest þone traht',
'tooltip-compareselectedversions' => 'Þā tōdāl sēon betƿēonan þǣre tƿǣm coren fadungum þisse sīdan',
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
'file-info-size' => '$1 × $2 pixels, fīlmiċelu: $3, MIMEcynn: $4',
'file-nohires' => 'Þǣr nis nǣniȝ mā miċelu.',
'svg-long-desc' => 'SVG fīl, rihte $1 × $2 pixels, fīlmiċelu: $3',
'show-big-image' => 'Fulmiċelu',

# Special:NewFiles
'imagelisttext' => "Niðer is getæl '''$1''' {{PLURAL:$1|ymelan|ymelena}}, endebyrded  on $2.",
'noimages' => 'Nāht tō sēonne.',
'ilsubmit' => 'Sēċan',
'bydate' => 'be tælmearce',

# Metadata
'metadata' => 'Metacȳþþu',
'metadata-expand' => 'Oferȝehanda sēon',
'metadata-collapse' => 'Oferȝehanda hȳdan',

# Exif tags
'exif-imagewidth' => 'Ƿīdnes',
'exif-imagelength' => 'Hīehþ',
'exif-compression' => 'Ȝeþryccungmōd',
'exif-ycbcrpositioning' => 'Y and C ȝesetednes',
'exif-imagedescription' => 'Biliðes nama',
'exif-artist' => 'Fruma',
'exif-usercomment' => 'Brūcendes trahtnunga',
'exif-exposuretime' => 'Blicestīd',
'exif-brightnessvalue' => 'APEX beorhtness',
'exif-lightsource' => 'Lēohtfruma',
'exif-whitebalance' => 'Hƿītefnetta',
'exif-sharpness' => 'Scearpnes',
'exif-gpslatituderef' => 'Norþ oþþe sūþ brǣdu',
'exif-gpslatitude' => 'Brǣdu',
'exif-gpslongituderef' => 'Ēast oþþe ƿest lengu',
'exif-gpslongitude' => 'Lengu',
'exif-gpsmeasuremode' => 'Mētungmōd',
'exif-gpsimgdirection' => 'Rihtung þæs biliðes',

# Exif attributes
'exif-compression-1' => 'Unȝeþrycced',

'exif-meteringmode-0' => 'Uncūþ',
'exif-meteringmode-1' => 'Ȝeþēaƿisc',
'exif-meteringmode-6' => 'Sām',
'exif-meteringmode-255' => 'Ōðer',

'exif-lightsource-0' => 'Uncūþ',
'exif-lightsource-1' => 'Dæȝeslēoht',

# Flash modes
'exif-flash-mode-3' => 'selffremmende mōd',

'exif-focalplaneresolutionunit-2' => 'ynċas',

'exif-exposuremode-1' => 'Handlic blice',

'exif-whitebalance-0' => 'Selffremmende hƿītefnetta',

'exif-scenecapturetype-1' => 'Landsceap',

'exif-gaincontrol-0' => 'Nān',

'exif-contrast-1' => 'Sōfte',
'exif-contrast-2' => 'Heard',

'exif-sharpness-1' => 'Sōfte',
'exif-sharpness-2' => 'Heard',

'exif-subjectdistancerange-2' => 'Nēah hāƿung',
'exif-subjectdistancerange-3' => 'Feorr hāƿung',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Norþ brǣdu',
'exif-gpslatitude-s' => 'Sūþ brǣdu',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Ēast lengu',
'exif-gpslongitude-w' => 'Ƿest lengu',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Sōþ rihtung',

# External editor support
'edit-externally-help' => '(Þā [//www.mediawiki.org/wiki/Manual:External_editors ȝearƿunga tyhtas] sēon for mā cȳþþe)',

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
'watchlistedit-noitems' => 'Þīnu ƿæccȝetalu ne hæfþ nǣniȝ naman.',
'watchlistedit-normal-title' => 'Ƿæccȝetale ādihtan',
'watchlistedit-normal-legend' => 'Naman forniman ƿiþ ƿæccȝetale',
'watchlistedit-normal-submit' => 'Naman forniman',
'watchlistedit-raw-titles' => 'Naman:',
'watchlistedit-raw-done' => 'Þīnu ƿæccȝetalu nīƿode.',

# Watchlist editing tools
'watchlisttools-view' => 'Ƿeorþliċe hƿearfas sēon',
'watchlisttools-edit' => 'Ƿæccȝetale sēon and ādihtan',
'watchlisttools-raw' => 'Grēne ƿæccȝetale ādihtan',

# Special:Version
'version' => 'Fadung',
'version-specialpages' => 'Syndriȝa sīdan',
'version-other' => 'Ōðer',
'version-hooks' => 'Anglas',
'version-hook-name' => 'Angelnama',
'version-version' => '(Fadung $1)',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'Fīlnama:',
'fileduplicatesearch-submit' => 'Sēċan',

# Special:SpecialPages
'specialpages' => 'Syndriȝa sīdan',
'specialpages-group-other' => 'Ōðra syndriȝa sīdan',
'specialpages-group-users' => 'Brūcendas and riht',

# Special:BlankPage
'blankpage' => 'Blæċu sīde',

# Special:Tags
'tags-edit' => 'ādihtan',

# HTML forms
'htmlform-submit' => 'Forþsendan',
'htmlform-reset' => 'Hƿearfas undōn',
'htmlform-selectorother-other' => 'Ōðer',

);
