<?php
/** Latvian (latviešu)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Dark Eagle
 * @author FnTmLV
 * @author Geimeris
 * @author Gleb Borisov
 * @author GreenZeb
 * @author Kaganer
 * @author Karlis
 * @author Kikos
 * @author Knakts
 * @author Marozols
 * @author Papuass
 * @author Reedy
 * @author Xil
 * @author Yyy
 * @author לערי ריינהארט
 */

/**
 * @copyright Copyright © 2006, Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_TALK             => 'Diskusija',
	NS_USER             => 'Lietotājs',
	NS_USER_TALK        => 'Lietotāja_diskusija',
	NS_PROJECT_TALK     => '{{grammar:ģenitīvs|$1}}_diskusija',
	NS_FILE             => 'Attēls',
	NS_FILE_TALK        => 'Attēla_diskusija',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_diskusija',
	NS_TEMPLATE         => 'Veidne',
	NS_TEMPLATE_TALK    => 'Veidnes_diskusija',
	NS_HELP             => 'Palīdzība',
	NS_HELP_TALK        => 'Palīdzības_diskusija',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Kategorijas_diskusija',
);
$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline' => 'Pasvītrot saites:',
'tog-justify' => 'Izlīdzināt rindkopām abas malas',
'tog-hideminor' => 'Paslēpt maznozīmīgus labojumus pēdējo izmaiņu lapā',
'tog-hidepatrolled' => 'Slēpt apstiprinātās izmaņas pēdējo izmaiņu sarakstā',
'tog-newpageshidepatrolled' => 'Paslēpt pārbaudītās lapas jauno lapu sarakstā',
'tog-extendwatchlist' => 'Izvērst uzraugāmo lapu sarakstu, lai parādītu visas veiktās izmaiņas (ne tikai pašas svaigākās)',
'tog-usenewrc' => "Grupēt izmaiņas pēc lapas pēdējās izmaiņās un uzraugāmo lapu sarakstā  (izmanto ''JavaScript'')",
'tog-numberheadings' => 'Automātiski numurēt virsrakstus',
'tog-showtoolbar' => 'Rādīt rediģēšanas rīkjoslu',
'tog-editondblclick' => "Atvērt rediģēšanas lapu ar dubultklikšķi (izmanto ''JavaScript'')",
'tog-editsection' => 'Rādīt sadaļām izmainīšanas saiti "[labot]"',
'tog-editsectiononrightclick' => "Atvērt sadaļas rediģēšanas lapu, uzklikšķinot ar labo peles pogu uz sadaļas virsraksta (izmanto ''JavaScript'')",
'tog-showtoc' => 'Parādīt satura rādītāju (lapām, kurās ir vairāk par 3 virsrakstiem)',
'tog-rememberpassword' => 'Atcerēties manu lietotājvārdu pēc pārlūka aizvēršanas (ne vairāk kā $1 {{PLURAL:$1|diena|dienas}}).',
'tog-watchcreations' => 'Pievienot manis radītās lapas un manis augšuplādētos failus uzraugāmo lapu sarakstam',
'tog-watchdefault' => 'Pievienot manis izmainītās lapas un failus uzraugāmo lapu sarakstam',
'tog-watchmoves' => 'Pievienot manis pārvietotās lapas un failus uzraugāmo lapu sarakstam',
'tog-watchdeletion' => 'Pievienot manis izdzēstās lapas un failus uzraugāmo lapu sarakstam',
'tog-minordefault' => 'Atzīmēt visus labojumus jau sākotnēji par maznozīmīgiem',
'tog-previewontop' => 'Parādīt priekšskatījumu virs rediģēšanas lauka, nevis zem',
'tog-previewonfirst' => 'Parādīt priekšskatījumu jau uzsākot rediģēšanu',
'tog-nocache' => 'Atslēgt pārlūka lapu saglabāšanu kešatmiņā',
'tog-enotifwatchlistpages' => 'Paziņot pa e-pastu par izmaiņām uzraugāmo rakstu sarakstā esošos rakstos un failos',
'tog-enotifusertalkpages' => 'Paziņot pa e-pastu par izmaiņām manā diskusiju lapā',
'tog-enotifminoredits' => 'Paziņot pa e-pastu arī par maznozīmīgiem labojumiem rakstos un failos',
'tog-enotifrevealaddr' => 'Atklāt manu e-pasta adresi paziņojumu vēstulēs',
'tog-shownumberswatching' => 'Rādīt uzraudzītāju skaitu',
'tog-oldsig' => 'Pašreizējais paraksts:',
'tog-fancysig' => 'Vienkāršs paraksts (bez automātiskās saites)',
'tog-externaleditor' => 'Pēc noklusējuma izmantot ārēju programmu lapu izmainīšanai (tikai pieredzējušiem lietotājiem, lai darbotos nepieciešami speciāli uzstādījumi tavā datorā sk. [//www.mediawiki.org/wiki/Manual:External_editor šeit])',
'tog-externaldiff' => 'Pēc noklusējuma izmantot ārēju programmu izmaiņu parādīšanai (tikai pieredzējušiem lietotājiem, lai darbotos nepieciešami speciāli uzstādījumi tavā datorā sk. [//www.mediawiki.org/wiki/Manual:External_editor šeit])',
'tog-showjumplinks' => 'Rādīt pārlēkšanas saites',
'tog-uselivepreview' => "Lietot tūlītējo priekšskatījumu (izmanto ''JavaScript''; eksperimentāla iespēja)",
'tog-forceeditsummary' => 'Atgādināt man, ja kopsavilkuma ailīte ir tukša',
'tog-watchlisthideown' => 'Paslēpt manus labojumus uzraugāmo lapu sarakstā',
'tog-watchlisthidebots' => 'Paslēpt botu labojumus uzraugāmo lapu sarakstā',
'tog-watchlisthideminor' => 'Paslēpt maznozīmīgos labojumus uzraugāmo lapu sarakstā',
'tog-watchlisthideliu' => 'Paslēpt reģistrēto lietotāju labojumus uzraugāmo lapu sarakstā',
'tog-watchlisthideanons' => 'Paslēpt anonīmo lietotāju labojumus uzraugāmo lapu sarakstā',
'tog-watchlisthidepatrolled' => 'Paslēpt pārbaudītās lapas uzraugāmo lapu sarakstā',
'tog-ccmeonemails' => 'Sūtīt sev citiem lietotājiem nosūtīto epastu kopijas',
'tog-diffonly' => 'Nerādīt lapu saturu zem izmaiņām',
'tog-showhiddencats' => 'Rādīt slēptās kategorijas',
'tog-norollbackdiff' => 'Neņemt vērā atšķirības, veicot atriti',

'underline-always' => 'vienmēr',
'underline-never' => 'nekad',
'underline-default' => 'kā pārlūkā vai apdarē',

# Font style option in Special:Preferences
'editfont-style' => 'Fonta veids rediģēšanas laukā:',
'editfont-default' => 'kā pārlūkā',
'editfont-monospace' => 'Vienplatuma fonts',
'editfont-sansserif' => 'Bezserifa fonts',
'editfont-serif' => 'Serifa fonts',

# Dates
'sunday' => 'svētdiena',
'monday' => 'Pirmdiena',
'tuesday' => 'otrdiena',
'wednesday' => 'trešdiena',
'thursday' => 'ceturtdiena',
'friday' => 'piektdiena',
'saturday' => 'sestdiena',
'sun' => 'Sv',
'mon' => 'Pr',
'tue' => 'Ot',
'wed' => 'Tr',
'thu' => 'Ce',
'fri' => 'Pk',
'sat' => 'Se',
'january' => 'janvārī',
'february' => 'februārī',
'march' => 'martā',
'april' => 'aprīlī',
'may_long' => 'maijā',
'june' => 'jūnijā',
'july' => 'jūlijā',
'august' => 'augustā',
'september' => 'septembrī',
'october' => 'oktobrī',
'november' => 'novembrī',
'december' => 'decembrī',
'january-gen' => 'Janvāra',
'february-gen' => 'Februāra',
'march-gen' => 'Marta',
'april-gen' => 'Aprīļa',
'may-gen' => 'Maija',
'june-gen' => 'Jūnija',
'july-gen' => 'Jūlija',
'august-gen' => 'Augusta',
'september-gen' => 'Septembra',
'october-gen' => 'Oktobra',
'november-gen' => 'Novembra',
'december-gen' => 'Decembra',
'jan' => 'janvārī,',
'feb' => 'februārī,',
'mar' => 'martā,',
'apr' => 'aprīlī,',
'may' => 'maijā,',
'jun' => 'jūnijā,',
'jul' => 'jūlijā,',
'aug' => 'augustā,',
'sep' => 'septembrī,',
'oct' => 'oktobrī,',
'nov' => 'novembrī,',
'dec' => 'decembrī,',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Kategorija|Kategorijas}}',
'category_header' => 'Raksti, kas ietverti kategorijā "$1".',
'subcategories' => 'Apakškategorijas',
'category-media-header' => 'Faili kategorijā "$1"',
'category-empty' => "''Šī kategorija šobrīd nesatur ne lapas, ne failus''",
'hidden-categories' => '{{PLURAL:$1|Slēpta kategorija|Slēptas kategorijas}}',
'hidden-category-category' => 'Slēptās kategorijas',
'category-subcat-count' => '{{PLURAL:$2|Šajai kategorijai ir tikai viena apakškategorija.|Šajai kategorijai ir $2 apakškategorijas, no kurām ir {{PLURAL:$1|redzama viena|redzamas $1}}.}}',
'category-subcat-count-limited' => 'Šai kategorijai ir {{PLURAL:$1|viena apakškategorija|$1 apakškategorijas}}.',
'category-article-count' => '{{PLURAL:$2|Šī kategorija satur tikai šo vienu lapu.|Šajā kategorijā kopā ir $2 lapas, šobrīd ir {{PLURAL:$1|redzama viena no tām|redzamas $1 no tām}}.}}',
'category-article-count-limited' => 'Šajā kategorijā ir {{PLURAL:$1|šī viena lapa|šīs $1 lapas}}.',
'category-file-count' => '{{PLURAL:$2|Šī kategorija satur tikai šo vienu failu.|Šajā kategorijā ir $2 faili, no kuriem {{PLURAL:$1|redzams ir viens|ir redzami $1}}.}}',
'category-file-count-limited' => 'Šajā kategorijā atrodas {{PLURAL:$1|tikai šis fails|šie $1 faili}}.',
'listingcontinuesabbrev' => ' (turpinājums)',
'index-category' => 'Indeksētās lapas',
'noindex-category' => 'Neindeksētās lapas',
'broken-file-category' => 'Lapas, kurās ir bojātas failu saites',

'about' => 'Par',
'article' => 'Raksts',
'newwindow' => '(atveras jaunā logā)',
'cancel' => 'Atcelt',
'moredotdotdot' => 'Vairāk...',
'mypage' => 'Lapa',
'mytalk' => 'Diskusijas',
'anontalk' => 'Šīs IP adreses diskusija',
'navigation' => 'Navigācija',
'and' => '&#32;un',

# Cologne Blue skin
'qbfind' => 'Meklēšana',
'qbbrowse' => 'Navigācija',
'qbedit' => 'Izmainīšana',
'qbpageoptions' => 'Šī lapa',
'qbpageinfo' => 'Konteksts',
'qbmyoptions' => 'Manas lapas',
'qbspecialpages' => 'Īpašās lapas',
'faq' => 'BUJ',
'faqpage' => 'Project:BUJ',

# Vector skin
'vector-action-addsection' => 'Jauna sadaļa',
'vector-action-delete' => 'Dzēst',
'vector-action-move' => 'Pārvietot',
'vector-action-protect' => 'Aizsargāt',
'vector-action-undelete' => 'Atjaunot',
'vector-action-unprotect' => 'Mainīt aizsardzību',
'vector-simplesearch-preference' => 'Ieslēgt vienkāršoto meklēšanas joslu (tikai Vector apdarē)',
'vector-view-create' => 'Izveidot',
'vector-view-edit' => 'Labot',
'vector-view-history' => 'Hronoloģija',
'vector-view-view' => 'Skatīt',
'vector-view-viewsource' => 'Aplūkot kodu',
'actions' => 'Darbības',
'namespaces' => 'Vārdtelpas',
'variants' => 'Varianti',

'errorpagetitle' => 'Kļūda',
'returnto' => 'Atgriezties: $1.',
'tagline' => "No ''{{grammar:ģenitīvs|{{SITENAME}}}}''",
'help' => 'Palīdzība',
'search' => 'Meklēt',
'searchbutton' => 'Meklēt',
'go' => 'Aiziet!',
'searcharticle' => 'Aiziet!',
'history' => 'hronoloģija',
'history_short' => 'Vēsture',
'updatedmarker' => 'atjaunināti kopš pēdējā apmeklējuma',
'printableversion' => 'Drukājama versija',
'permalink' => 'Pastāvīgā saite',
'print' => 'Drukāt',
'view' => 'Skatīt',
'edit' => 'Izmainīt šo lapu',
'create' => 'Izveidot',
'editthispage' => 'Izmainīt šo lapu',
'create-this-page' => 'Izveidot šo lapu',
'delete' => 'Dzēst',
'deletethispage' => 'Dzēst šo lapu',
'undelete_short' => 'Atjaunot $1 {{PLURAL:$1|versiju|versijas}}',
'viewdeleted_short' => 'Apskatīt {{PLURAL:$1|vienu dzēstu labojumu|$1 dzēstus labojumus}}',
'protect' => 'Aizsargāt',
'protect_change' => 'izmainīt',
'protectthispage' => 'Aizsargāt šo lapu',
'unprotect' => 'Mainīt aizsardzību',
'unprotectthispage' => 'Mainīt šīs lapas aizsardzību',
'newpage' => 'Jauna lapa',
'talkpage' => 'Diskusija par šo lapu',
'talkpagelinktext' => 'diskusija',
'specialpage' => 'Īpašā Lapa',
'personaltools' => 'Lietotāja rīki',
'postcomment' => 'Pievienot komentāru',
'articlepage' => 'Apskatīt rakstu',
'talk' => 'Diskusija',
'views' => 'Apskates',
'toolbox' => 'Rīki',
'userpage' => 'Skatīt lietotāja lapu',
'projectpage' => 'Skatīt projekta lapu',
'imagepage' => 'Skatīt faila lapu',
'mediawikipage' => 'Skatīt paziņojuma lapu',
'templatepage' => 'Skatīt veidnes lapu',
'viewhelppage' => 'Atvērt palīdzību',
'categorypage' => 'Apskatīt kategorijas lapu',
'viewtalkpage' => 'Skatīt diskusiju',
'otherlanguages' => 'Citās valodās',
'redirectedfrom' => '(Pāradresēts no $1)',
'redirectpagesub' => 'Pāradresācijas lapa',
'lastmodifiedat' => 'Šajā lapā pēdējās izmaiņas izdarītas $2, $1.',
'viewcount' => 'Šī lapa ir tikusi apskatīta $1 {{PLURAL:$1|reizi|reizes}}.',
'protectedpage' => 'Aizsargāta lapa',
'jumpto' => 'Pārlēkt uz:',
'jumptonavigation' => 'navigācija',
'jumptosearch' => 'meklēt',
'view-pool-error' => 'Atvainojiet, šobrīd serveri ir pārslogoti.
Pārāk daudz lietotāju mēģina apskatīt šo lapu.
Lūdzu, brīdi uzgaidiet un mēģiniet šo lapu apskatīties vēlreiz.

$1',
'pool-errorunknown' => 'Nezināma kļūda',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'Par {{grammar:akuzatīvs|{{SITENAME}}}}',
'aboutpage' => 'Project:Par',
'copyright' => 'Saturs ir pieejams saskaņā ar $1.',
'copyrightpage' => '{{ns:project}}:Autortiesības',
'currentevents' => 'Aktualitātes',
'currentevents-url' => 'Project:Aktualitātes',
'disclaimers' => 'Saistību atrunas',
'disclaimerpage' => 'Project:Saistību atrunas',
'edithelp' => 'Rediģēšanas palīdzība',
'edithelppage' => 'Help:Rediģēšana',
'helppage' => 'Help:Saturs',
'mainpage' => 'Sākumlapa',
'mainpage-description' => 'Sākumlapa',
'policy-url' => 'Project:Politika',
'portal' => 'Kopienas portāls',
'portal-url' => 'Project:Kopienas portāls',
'privacy' => 'Privātuma politika',
'privacypage' => 'Project:Privātuma politika',

'badaccess' => 'Atļaujas kļūda',
'badaccess-group0' => 'Tev nav atļauts izpildīt darbību, kuru tu pieprasīji.',
'badaccess-groups' => 'Darbības izpilde, ko Tu pieprasīji, ir pieejama tikai $1 {{PLURAL:$2|lietotāju grupai|lietotāju grupām}}.',

'versionrequired' => "Nepieciešamā ''MediaWiki'' versija: $1.",
'versionrequiredtext' => "Lai lietotu šo lapu, nepieciešama ''MediaWiki'' versija $1. Sk. [[Special:Version|versija]].",

'ok' => 'Labi',
'retrievedfrom' => 'Saturs iegūts no "$1"',
'youhavenewmessages' => 'Tev ir $1 (skatīt $2).',
'newmessageslink' => 'jauns vēstījums',
'newmessagesdifflink' => 'pēdējā izmaiņa',
'newmessageslinkplural' => '{{PLURAL:$1|jauns vēstījums|jauni vēstījumi}}',
'newmessagesdifflinkplural' => '{{PLURAL:$1|pēdējā izmaiņa|pēdējās izmaiņas}}',
'youhavenewmessagesmulti' => 'Tev ir jauns ziņojums: $1',
'editsection' => 'labot',
'editold' => 'labot',
'viewsourceold' => 'aplūkot kodu',
'editlink' => 'labot',
'viewsourcelink' => 'aplūkot kodu',
'editsectionhint' => 'Rediģēt sadaļu: $1',
'toc' => 'Satura rādītājs',
'showtoc' => 'parādīt',
'hidetoc' => 'paslēpt',
'collapsible-collapse' => 'Sakļaut',
'collapsible-expand' => 'Izplest',
'thisisdeleted' => 'Apskatīt vai atjaunot $1?',
'viewdeleted' => 'Skatīt $1?',
'restorelink' => '$1 {{PLURAL:$1|dzēsto versiju|dzēstās versijas}}',
'feedlinks' => 'Barotne:',
'feed-invalid' => 'Nederīgs abonētās barotnes veids.',
'feed-unavailable' => 'Sindicētās plūsmas nav pieejamas',
'site-rss-feed' => '$1 RSS padeve',
'site-atom-feed' => '$1 Atom padeve',
'page-rss-feed' => '"$1" RSS barotne',
'page-atom-feed' => '"$1" Atom barotne',
'red-link-title' => '$1 (lapa neeksistē)',
'sort-descending' => 'Kārtot dilstošā secībā',
'sort-ascending' => 'Kārtot augošā secībā',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Raksts',
'nstab-user' => 'Lietotāja lapa',
'nstab-media' => 'Multivides lapa',
'nstab-special' => 'Īpašā lapa',
'nstab-project' => 'Projekta lapa',
'nstab-image' => 'Attēls',
'nstab-mediawiki' => 'Paziņojums',
'nstab-template' => 'Veidne',
'nstab-help' => 'Palīdzība',
'nstab-category' => 'Kategorija',

# Main script and global functions
'nosuchaction' => 'Šādas darbības nav.',
'nosuchactiontext' => 'Iekš URL norādītā darbība ir nederīga.
Tas var būt no drukas kļūdas URL, vai arī no kļūdainas saites.
Tas arī var būt saistīts ar {{GRAMMAR:ģenitīvs|{{SITENAME}}}} programmatūras kļūdu.',
'nosuchspecialpage' => 'Nav tādas īpašās lapas',
'nospecialpagetext' => 'Tu esi pieprasījis īpašo lapu, ko wiki neatpazīst.
Derīgo īpašo lapu saraksts atrodas te: [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error' => 'Kļūda',
'databaseerror' => 'Datu bāzes kļūda',
'dberrortext' => 'Konstatēta sintakses kļūda datubāzes pieprasījumā.
Iespējams, tā radusies dēļ kļūdas programmatūrā.
Pēdējais datubāzes pieprasījums bija:
<blockquote><code>$1</code></blockquote>
no funkcijas "<code>$2</code>".
Datubāzes atgrieztais kļūdas paziņojums: "<samp>$3: $4</samp>".',
'dberrortextcl' => 'Datubāzes vaicājumā pieļauta sintakses kļūda.
Pēdējais priekšraksts:
"$1"
palaists funkcijā "$2".
Izdotā MySQL kļūda: "$3: $4"',
'laggedslavemode' => 'Uzmanību: Iespējams, šajā lapā nav redzami nesen izdarītie papildinājumi.',
'readonly' => 'Datubāze bloķēta',
'enterlockreason' => 'Ievadiet bloķēšanas iemeslu, ieskaitot aplēses, kad bloķēšana tiks beigta.',
'readonlytext' => 'Datubāze šobrīd ir bloķēta jaunu ierakstu izveidošanai un citām izmaiņām, visticamāk, kārtējā datubāzes uzturēšanas pasākuma dēļ, pēc kura tā tiks atjaunota normālā stāvoklī.

Administrators, kurš nobloķēja datubāzi, norādīja šādu iemeslu: $1',
'missing-article' => 'Teksts lapai ar nosaukumu "$1" $2 datubāzē nav atrodams.

Tas parasti notiek novecojušu saišu gadījumā: pieprasot izmaiņas vai hronoloģiju lapai, kas ir izdzēsta.

Ja lapai ir jābūt, tad, iespējams, ir kļūda programmā.
Par to varat ziņot [[Special:ListUsers/sysop|kādam administratoram]], norādot arī URL.',
'missingarticle-rev' => '(Pārskatīšana #: $1)',
'missingarticle-diff' => '(Salīdz.: $1, $2)',
'internalerror' => 'Iekšēja kļūda',
'internalerror_info' => 'Iekšējā kļūda: $1',
'fileappenderror' => 'Neizdevās pievienot "$1" pie "$2".',
'filecopyerror' => 'Nav iespējams nokopēt failu "$1" uz "$2"',
'filerenameerror' => 'Neizdevās pārdēvēt failu "$1" par "$2".',
'filedeleteerror' => 'Nevar izdzēst failu "$1".',
'directorycreateerror' => 'Nevar izveidot mapi "$1".',
'filenotfound' => 'Neizdevās atrast failu "$1".',
'fileexistserror' => 'Nevar saglabāt failā "$1": fails jau pastāv',
'unexpected' => 'Negaidīta vērtība: "$1"="$2".',
'formerror' => 'Kļūda: neizdevās nosūtīt saturu',
'badarticleerror' => 'Šo darbību nevar veikt šajā lapā.',
'cannotdelete' => 'Nevar izdzēst lapu vai failu $1. Iespējams, to jau ir izdzēsis kāds cits.',
'cannotdelete-title' => 'Nevar izdzēst lapu "$1"',
'badtitle' => 'Nepiemērots nosaukums',
'badtitletext' => 'Pieprasītā lapa ir kļūdaina, tukša, vai nepareizi saistīts starpvalodu vai starp-vikiju virsrakstas. Tas var saturēt vienu vai vairākus simbolus, ko nedrīkst izmantot nosaukumos.',
'perfcached' => 'Šie dati ir no servera kešatmiņas un var būt novecojuši. A maximum of {{PLURAL:$1|one result is|$1 results are}} available in the cache.',
'perfcachedts' => "Šie dati ir no servera kešatmiņas (''cache''), kas pēdējo reizi bija atjaunota $1. A maximum of {{PLURAL:$4|one result is|$4 results are}} available in the cache.",
'querypage-no-updates' => 'Šīs lapas atjaunošana pagaidām ir atslēgta. Te esošie dati tuvākajā laikā netiks atjaunoti.',
'wrong_wfQuery_params' => 'Nekorekti wfQuery() parametri<br />
Funkcija: $1<br />
Vaicājums: $2',
'viewsource' => 'Aplūkot kodu',
'actionthrottled' => 'Darbība netika atļauta',
'protectedpagetext' => 'Šī lapa ir aizsargāta lai novērstu tās izmainīšanu vai citas darbības.',
'viewsourcetext' => 'Tu vari apskatīties un nokopēt šīs lapas vikitekstu:',
'protectedinterface' => 'Šī lapa satur programmatūras interfeisā lietotu tekstu un ir bloķēta pret izmaiņām, lai pasargātu no bojājumiem.',
'editinginterface' => "'''Brīdinājums:''' Tu izmaini lapu, kuras saturu izmanto wiki programmatūras lietotāja saskarnē (''interfeisā''). Šīs lapas izmaiņas ietekmēs lietotāja saskarni citiem lietotājiem. Pēc modificēšanas, šīs izmaiņas būtu lietderīgi pievienot arī [//translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net], kas ir MediaWiki lokalizēšanas projekts.",
'sqlhidden' => '(SQL vaicājums paslēpts)',
'namespaceprotected' => "Tev nav atļaujas izmainīt lapas, kas atrodas '''$1''' ''namespacē''.",
'ns-specialprotected' => 'Nevar izmainīt īpašās lapas.',
'titleprotected' => "Šī lapa ir aizsargāta pret izveidošanu. To aizsargāja [[User:$1|$1]].
Norādītais iemesls bija ''$2''.",

# Virus scanner
'virus-badscanner' => "Nekorekta konfigurācija: nezināms vīrusu skeneris: ''$1''",
'virus-scanfailed' => 'skenēšana neizdevās (kods $1)',
'virus-unknownscanner' => 'nezināms antivīruss:',

# Login and logout pages
'logouttext' => "'''Tu esi izgājis no {{grammar:ģenitīvs|{{SITENAME}}}}.'''

Vari turpināt to izmantot anonīmi, vari [[Special:UserLogin|atgriezties]] kā cits lietotājs vai varbūt tas pats.
Ņem vērā, ka arī pēc iziešanas, dažas lapas var tikt parādītas tā, it kā tu vēl būtu iekšā, līdz tiks iztīrīta pārlūka kešatmiņa.",
'welcomecreation' => '== Laipni lūdzam, $1! ==

Tavs lietotāja konts ir izveidots. Neaizmirsti, ka ir iespējams mainīt [[Special:Preferences|{{grammar:ģenitīvs|{{SITENAME}}}} izmantošanas izvēles]].',
'yourname' => 'Tavs lietotājvārds',
'yourpassword' => 'Tava parole:',
'yourpasswordagain' => 'Atkārto paroli',
'remembermypassword' => 'Atcerēties pēc pārlūka aizvēršanas (spēkā ne vairāk kā $1 {{PLURAL:$1|diena|dienas}}).',
'securelogin-stick-https' => 'Saglabāt HTTPS savienojumu pēc pieslēgšanās',
'yourdomainname' => 'Tavs domēns',
'externaldberror' => 'Notikusi vai nu ārējās autentifikācijas datubāzes kļūda, vai arī tev nav atļauts izmainīt savu ārējo kontu.',
'login' => 'Pieslēgties',
'nav-login-createaccount' => 'Izveidot jaunu lietotāju vai doties iekšā',
'loginprompt' => 'Lai ieietu {{grammar:lokatīvs|{{SITENAME}}}}, tavam datoram ir jāpieņem sīkdatnes (<i>cookies</i>).',
'userlogin' => 'Izveidot jaunu lietotāju vai doties iekšā',
'userloginnocreate' => 'Pieslēgties',
'logout' => 'Iziet',
'userlogout' => 'Iziet',
'notloggedin' => 'Neesi iegājis',
'nologin' => "Nav lietotājvārda? '''$1'''.",
'nologinlink' => 'Reģistrējies',
'createaccount' => 'Izveidot jaunu lietotāju',
'gotaccount' => "Tev jau ir lietotājvārds? '''$1'''!",
'gotaccountlink' => 'Dodies iekšā',
'userlogin-resetlink' => 'Esat aizmirsis savu pieslēgšanās informāciju?',
'createaccountmail' => 'pa e-pastu',
'createaccountreason' => 'Iemesls:',
'badretype' => 'Tevis ievadītās paroles nesakrīt.',
'userexists' => 'Ievadītais lietotājvārds jau ir aizņemts.
Lūdzu, izvēlieties citu vārdu.',
'loginerror' => 'Neveiksmīga ieiešana',
'createaccounterror' => 'Neizdevās izveidot kontu: $1',
'nocookiesnew' => 'Lietotājvārds tika izveidots, bet tu neesi iegājis iekšā. {{SITENAME}} izmanto sīkdatnes (<i>cookies</i>), lai lietotāji varētu tajā ieiet. Tavs pārlūks nepieņem tās. Lūdzu, atļauj to pieņemšanu un tad nāc iekšā ar savu lietotājvārdu un paroli.',
'nocookieslogin' => '{{SITENAME}} izmanto sīkdatnes (<i>cookies</i>), lai lietotāji varētu ieiet tajā. Diemžēl tavs pārlūks tos nepieņem. Lūdzu, atļauj to pieņemšanu un mēģini vēlreiz.',
'noname' => 'Tu neesi norādījis derīgu lietotāja vārdu.',
'loginsuccesstitle' => 'Ieiešana veiksmīga',
'loginsuccess' => 'Tu esi ienācis {{grammar:lokatīvs|{{SITENAME}}}} kā "$1".',
'nosuchuser' => 'Šeit nav lietotāja ar vārdu "$1". Lietotājvārdi ir reģistrjutīgi (lielie un mazie burti nav viens un tas pats) Pārbaudi, vai pareizi uzrakstīts, vai arī [[Special:UserLogin/signup|izveido jaunu kontu]].',
'nosuchusershort' => 'Šeit nav lietotāja ar vārdu "$1". Pārbaudi, vai nav drukas kļūda.',
'nouserspecified' => 'Tev jānorāda lietotājvārds.',
'login-userblocked' => 'Šis lietotājs ir bloķēts. Pieslēgšanās nav atļauta.',
'wrongpassword' => 'Tu ievadīji nepareizu paroli. Lūdzu, mēģini vēlreiz.',
'wrongpasswordempty' => 'Parole bija tukša. Lūdzu mēģini vēlreiz.',
'passwordtooshort' => 'Tava parole ir pārāk īsa.
Tajā jābūt vismaz {{PLURAL:$1|1 zīmei|$1 zīmēm}}.',
'password-name-match' => 'Tava parole nedrīkst būt tāda pati kā tavs lietotājvārds.',
'mailmypassword' => 'Atsūtīt man jaunu paroli',
'passwordremindertitle' => 'Jauna pagaidu parole no {{SITENAME}}s',
'passwordremindertext' => 'Kads (iespejams, Tu pats, no IP adreses $1)
ludza, lai nosutam Tev jaunu {{SITENAME}} ($4) paroli.
Lietotajam $2 pagaidu parole tagad ir $3.
Ludzu, nomaini paroli, kad esi veiksmigi iekluvis ieksa.
Tavas pagaidu paroles deriiguma terminsh beigsies peec {{PLURAL:$5|vienas dienas|$5 dienaam}}.

Ja paroles pieprasījumu bija nosūtījis kāds cits, vai arī tu atcerējies savu veco paroli, šo var ignorēt. Vecā parole joprojām darbojas.',
'noemail' => 'Lietotājs "$1" nav reģistrējis e-pasta adresi.',
'noemailcreate' => 'Tev jānorāda derīgu e-pasta adresi',
'passwordsent' => 'Esam nosūtījuši jaunu paroli uz e-pasta adresi, kuru ir norādījis lietotājs $1. Lūdzu, nāc iekšā ar jauno paroli, kad būsi to saņēmis.',
'blocked-mailpassword' => "Tava IP adrese ir bloķēta un tāpēc nevar lietot paroles atjaunošanas (''recovery'') funkciju, lai nevarētu apiet bloku.",
'eauthentsent' => "Apstiprinājuma e-pasts tika nosūtīts uz norādīto e-pasta adresi. Lai varētu saņemt citus ''meilus'', izpildi vēstulē norādītās instrukcijas, lai apstiprinātu, ka šī tiešām ir tava e-pasta adrese.",
'throttled-mailpassword' => 'Paroles atgādinājums jau ir ticis nosūtīts {{PLURAL:$1|pēdējās stundas|pēdējo $1 stundu}} laikā.
Lai novērstu šīs funkcijas ļaunprātīgu izmantošanu, iespējams nosūtīt tikai vienu paroles atgādinājumu, {{PLURAL:$1|katru stundu|katras $1 stundas}}.',
'mailerror' => 'E-pasta sūtīšanas kļūda: $1',
'acct_creation_throttle_hit' => 'Lietotāji no tavas IP adreses šajā viki pēdējo 24 stundu laikā jau ir izveidojuši {{PLURAL:$1|1 kontu|$1 kontus}}, kas ir maksimālais atļautais skaits šajā laika periodā.
Tādēļ šobrīd no šīs IP adreses vairs nevar izveidot jaunus kontus.',
'emailauthenticated' => 'Tava e-pasta adrese tika apstiprināta $2, $3.',
'emailnotauthenticated' => 'Tava e-pasta adrese <strong>vēl nav apstiprināta</strong> un zemāk norādītās iespējas nav pieejamas.',
'noemailprefs' => 'Norādi e-pasta adresi, lai lietotu šīs iespējas.',
'emailconfirmlink' => 'Apstiprināt tavu e-pasta adresi',
'invalidemailaddress' => 'E-pasta adrese nevar tikt apstiprināta, jo izskatās nederīga. Lūdzu ievadi korekti noformētu e-pasta adresi, vai arī atstāj to lauku tukšu.',
'cannotchangeemail' => 'Konta e-pasta adresi nevar nomainīt šajā wiki.',
'accountcreated' => 'Konts izveidots',
'accountcreatedtext' => 'Lietotāja konts priekš $1 tika izveidots.',
'createaccount-title' => 'Lietotāja konta izveidošana {{grammar:lokatīvs|{{SITENAME}}}}',
'usernamehasherror' => 'Lietotājvārds nevar saturēt hash simbolus',
'login-throttled' => 'Tu esi veicis pārāk daudz ieiešanas mēģinājumus.
Lūdzu uzgaidi pirms mēģini vēlreiz.',
'login-abort-generic' => 'Jūsu pieteikšanās bija neveiksmīga — Darbība pārtraukta',
'loginlanguagelabel' => 'Valoda: $1',

# E-mail sending
'php-mail-error-unknown' => 'Nezināma kļūda PHP mail() funkcijā',

# Change password dialog
'resetpass' => 'Mainīt paroli',
'resetpass_header' => 'Mainīt konta paroli',
'oldpassword' => 'Vecā parole',
'newpassword' => 'Jaunā parole',
'retypenew' => 'Atkārto jauno paroli',
'resetpass_submit' => 'Uzstādīt paroli un ieiet',
'resetpass_success' => 'Parole nomainīta veiksmīgi!
Notiek ieiešana...',
'resetpass_forbidden' => 'Paroles nav iespējams nomainīt',
'resetpass-no-info' => 'Jums ir nepieciešams ieiet, lai tūlīt piekļūtu šai lapai.',
'resetpass-submit-loggedin' => 'Mainīt paroli',
'resetpass-submit-cancel' => 'Atcelt',
'resetpass-wrong-oldpass' => 'Nepareiza pagaidu vai galvenā parole.
Tu jau esi veiksmīgi nomainījis savu galveno paroli, vai arī esi pieprasījis jaunu pagaidu paroli.',
'resetpass-temp-password' => 'Pagaidu parole:',

# Special:PasswordReset
'passwordreset' => 'Paroles atiestatīšana',
'passwordreset-legend' => 'Atiestatīt paroli',
'passwordreset-disabled' => 'Paroles atiestates šajā viki ir atspējotas.',
'passwordreset-username' => 'Lietotājvārds:',
'passwordreset-domain' => 'Domēns:',
'passwordreset-capture' => 'Apskatīt izveidoto e-pastu?',
'passwordreset-email' => 'E-pasta adrese:',
'passwordreset-emailtitle' => 'Konta informācija {{SITENAME}}',
'passwordreset-emailelement' => 'Lietotājvārds: $1
Pagaidu parole: $2',
'passwordreset-emailsent' => 'Atgādinājuma e-pasts ir nosūtīts.',
'passwordreset-emailsent-capture' => 'Atgādinājuma e-pasta ziņojums ir nosūtīts, tas parādīts zemāk.',
'passwordreset-emailerror-capture' => 'Atgādinājuma e-pasta ziņojums tika izveidots, tas parādīts zemāk, bet nosūtīšana lietotājam neizdevās: $1',

# Special:ChangeEmail
'changeemail' => 'Mainīt e-pasta adresi',
'changeemail-header' => 'Mainīt konta e-pasta adresi',
'changeemail-oldemail' => 'Pašreizējā e-pasta adrese:',
'changeemail-newemail' => 'Jaunā e-pasta adrese:',
'changeemail-none' => '(nav)',
'changeemail-submit' => 'Mainīt e-pastu',
'changeemail-cancel' => 'Atcelt',

# Edit page toolbar
'bold_sample' => 'Teksts boldā',
'bold_tip' => 'Teksts treknrakstā',
'italic_sample' => 'Teksts kursīvā',
'italic_tip' => 'Teksts kursīvā',
'link_sample' => 'Lapas nosaukums',
'link_tip' => 'Iekšējā saite',
'extlink_sample' => 'http://www.example.com saites apraksts',
'extlink_tip' => 'Ārējā saite (neaizmirsti sākumā pierakstīt "http://")',
'headline_sample' => 'Virsraksta teksts',
'headline_tip' => '2. līmeņa virsraksts',
'nowiki_sample' => 'Šeit raksti neformatētu tekstu',
'nowiki_tip' => 'Ignorēt wiki formatējumu',
'image_sample' => 'Piemers.jpg',
'image_tip' => 'Ievietots attēls',
'media_sample' => 'Piemers.ogg',
'media_tip' => 'Saite uz multimēdiju failu',
'sig_tip' => 'Tavs paraksts ar laika atzīmi',
'hr_tip' => 'Horizontāla līnija (neizmanto lieki)',

# Edit pages
'summary' => 'Kopsavilkums:',
'subject' => 'Tēma/virsraksts:',
'minoredit' => 'maznozīmīgs labojums',
'watchthis' => 'Uzraudzīt šo lapu',
'savearticle' => 'Saglabāt lapu',
'preview' => 'Pirmskats',
'showpreview' => 'Rādīt pirmskatu',
'showlivepreview' => 'Tūlītējs pirmskats',
'showdiff' => 'Rādīt izmaiņas',
'anoneditwarning' => "'''Uzmanību:''' tu neesi iegājis. Lapas hronoloģijā tiks ierakstīta tava IP adrese.",
'anonpreviewwarning' => "''Tu neesi ienācis. Saglabājot lapu, Tava IP adrese tiks ierakstīta šīs lapas hronoloģijā.''",
'missingsummary' => "'''Atgādinājums''': Tu neesi norādījis izmaiņu kopsavilkumu. Vēlreiz klikšķinot uz \"Saglabāt lapu\", Tavas izmaiņas tiks saglabātas bez kopsavilkuma.",
'missingcommenttext' => 'Lūdzu, ievadi tekstu zemāk redzamajā logā!',
'missingcommentheader' => "'''Atgādinājums:''' Tu šim komentāram neesi norādījis virsrakstu/tematu.
Ja tu vēlreiz spiedīsi uz \"{{int:savearticle}}\", tavas izmaiņas tiks saglabātas bez virsraksta.",
'summary-preview' => 'Kopsavilkuma pirmskats:',
'subject-preview' => 'Kopsavilkuma/virsraksta pirmskats:',
'blockedtitle' => 'Lietotājs ir bloķēts.',
'blockedtext' => "'''Tavs lietotāja vārds vai IP adrese ir nobloķēta.'''

\$1 nobloķēja tavu lietotāja vārdu vai IP adresi.
Bloķējot norādītais iemesls bija: ''\$2''.

*Bloka sākums: \$8
*Bloka beigas: \$6
*Bija domāts nobloķēt: \$7

Tu vari sazināties ar \$1 vai kādu citu [[{{MediaWiki:Grouppage-sysop}}|administratoru]] lai apspriestu šo bloku.

Pievērs uzmanību, tam, ka ja tu neesi norādījis derīgu e-pasta adresi ''[[Special:Preferences|savās izvēlēs]]'', tev nedarbosies \"sūtīt e-pastu\" iespēja.

Tava IP adrese ir \$3 un bloka identifikators ir #\$5. Lūdzu iekļauj vienu no tiem, vai abus, visos turpmākajos pieprasījumos.",
'autoblockedtext' => 'Tava IP adrese ir tikusi automātiski nobloķēta, tāpēc, ka to (nupat kā) ir lietojis cits lietotājs, kuru nobloķēja $1.
Norādītais bloķēšanas iemesls bija:

:\'\'$2\'\'

* Bloka sākums: $8
* Bloka beigas: $6
* Bija domāts nobloķēt: $7

Tu vari sazināties ar $1 vai kādu citu [[{{MediaWiki:Grouppage-sysop}}|adminu]] lai apspriestu šo bloku.

Atceries, ka tu nevari lietot "sūtīt e-pastu šim lietotājam" iespēju, ja tu neesi norādījis derīgu e-pasta adresi savās [[Special:Preferences|lietotāja izvelēs]] un bloķējot tev nav aizbloķēta iespēja sūtīt e-pastu.

Tava pašreizējā IP adrese ir $3 un  bloka ID ir $5.
Lūdzu iekļauj šos visos ziņojumos, kurus sūti adminiem, apspriežot šo bloku.',
'blockednoreason' => 'iemesls nav norādīts',
'whitelistedittext' => 'Tev $1 lai varētu rediģēt lapas.',
'confirmedittext' => 'Lai varētu izmainīt lapas, vispirms jāapstiprina savu e-pasta adresi.
Norādi un apstiprini e-pasta adresi savos [[Special:Preferences|lietotāja uzstādījumos]].',
'nosuchsectiontitle' => 'Nevaru atrast sadaļu',
'nosuchsectiontext' => 'Jūs mēģinājāt rediģēt sadaļu, kas neeksistē.
Tā var būt pārvietota vai dzēsta, kamēr jūs apskatījāt lapu.',
'loginreqtitle' => 'Nepieciešama ieiešana',
'loginreqlink' => 'login',
'loginreqpagetext' => 'Tev nepieciešams $1, lai apskatītu citas lapas.',
'accmailtitle' => 'Parole nosūtīta.',
'accmailtext' => "Nejauši ģenerēta parole lietotājam [[User talk:$1|$1]] tika nosūtīta uz $2.

Šī konta paroli pēc ielogošanās varēs nomainīt ''[[Special:ChangePassword|šeit]]''.",
'newarticle' => '(Jauns raksts)',
'newarticletext' => "Tu šeit nonāci sekojot saitei uz, pagaidām vēl neuzrakstītu, lapu.
Lai izveidotu lapu, sāc rakstīt teksta logā apakšā (par teksta formatēšanu un sīkākai informācija skatīt [[{{MediaWiki:Helppage}}|palīdzības lapu]]).
Ja tu šeit nonāci kļūdas pēc, vienkārši uzspied '''back''' pogu pārlūkprogrammā.",
'anontalkpagetext' => "----''Šī ir diskusiju lapa anonīmam lietotājam, kurš vēl nav kļuvis par reģistrētu lietotāju vai arī neizmanto savu lietotājvārdu. Tādēļ mums ir jāizmanto skaitliskā IP adrese, lai viņu identificētu.
Šāda IP adrese var būt vairākiem lietotājiem.
Ja tu esi anonīms lietotājs un uzskati, ka tev ir adresēti neatbilstoši komentāri, lūdzu, [[Special:UserLogin/signup|kļūsti par lietotāju]] vai arī [[Special:UserLogin|izmanto jau izveidotu lietotājvārdu]], lai izvairītos no turpmākām neskaidrībām un tu netiktu sajaukts ar citiem anonīmiem lietotājiem.''",
'noarticletext' => 'Šajā lapā šobrīd nav nekāda teksta, tu vari [[Special:Search/{{PAGENAME}}|meklēt citās lapās pēc šīs lapas nosaukuma]], <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} meklēt saistītos reģistru ierakstos] vai arī [{{fullurl:{{FULLPAGENAME}}|action=edit}} sākt rediģēt šo lapu]</span>.',
'noarticletext-nopermission' => 'Šajā lapā pašlaik nav nekāda teksta.
Tu vari [[Special:Search/{{PAGENAME}}|meklēt šīs lapas nosaukumu]] citās lapās,
vai <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} meklēt saistītus reģistru ierakstus]</span>.',
'userpage-userdoesnotexist' => 'Lietotājs "<nowiki>$1</nowiki>" nav reģistrēts.
Lūdzu, pārliecinies vai vēlies izveidot/izmainīt šo lapu.',
'userpage-userdoesnotexist-view' => 'Lietotājs "$1" nav reģistrēts.',
'blocked-notice-logextract' => 'Šis lietotājs pašlaik ir nobloķēts.

Pēdējais bloķēšanas reģistra ieraksts ir apskatāms zemāk:',
'clearyourcache' => "'''Piezīme:''' Lai redzētu izmaiņas, pēc saglabāšanas jums var nākties iztīrīt sava pārlūka kešatmiņu.
* '''Firefox / Safari:''' Pieturiet ''Shift'' un klikšķiniet uz ''Pārlādēt'' vai nospiediet ''Ctrl-F5'' vai ''Ctrl-R'' (''Command-R'' uz Mac)
* '''Google Chrome:''' Nospiediet ''Ctrl-Shift-R'' (''Command-Shift-R'' uz Mac)
* '''Internet Explorer:''' Pieturiet ''Ctrl'' un klikšķiniet uz ''Pārlādēt'' vai nospiediet ''Ctrl-F5''
* '''Konqueror:''' Klikšķiniet uz ''Pārlādēt'' vai nospiediet ''F5''
* '''Opera:''' Iztīriet kešatmiņu ''Tools → Preferences''",
'usercssyoucanpreview' => "'''Ieteikums:''' Lieto pogu \"{{int:showpreview}}\", lai pārbaudītu savu jauno CSS pirms saglabāšanas.",
'userjsyoucanpreview' => "'''Ieteikums:''' Lieto pogu \"{{int:showpreview}}\", lai pārbaudītu savu jauno JavaScript pirms saglabāšanas.",
'usercsspreview' => "'''Atceries, ka šis ir tikai tava lietotāja CSS pirmskats, lapa vēl nav saglabāta!'''",
'userjspreview' => "'''Atceries, ka šis ir tikai tava lietotāja JavaScript pirmskats/tests, lapa vēl nav saglabāta!'''",
'sitecsspreview' => "'''Atcerieties, ka jūs veicat tikai šī CSS priekšapskati.'''
'''Tas vēl nav saglabāts!'''",
'sitejspreview' => "'''Atcerieties, ka jūs veicat tikai šī JavaScript koda priekšapskati.'''
'''Tas vēl nav saglabāts!'''",
'updated' => '(Atjaunots)',
'note' => "'''Piezīme: '''",
'previewnote' => "'''Atceries, ka šis ir tikai pirmskats un teksts vēl nav saglabāts!'''",
'continue-editing' => 'Turpināt labošanu',
'session_fail_preview' => "'''Neizdevās apstrādāt tavas izmaiņas, jo tika pazaudēti sesijas dati.
Lūdzu mēģini vēlreiz.
Ja tas joprojām nedarbojas, mēģini [[Special:UserLogout|izlogoties ārā]] un ielogoties no jauna.'''",
'session_fail_preview_html' => "'''Neizdevās apstrādāt tavas izmaiņas, jo tika pazaudēti sesijas dati.'''

''Tā, kā {{grammar:ģenitīvs|{{SITENAME}}}} darbojas neapstrādāts HTML, pirmskats ir paslēpts, lai aizsargātos no JavaScripta  uzbrukumiem.''

'''Ja šis bija parasts rediģēšanas mēģinājums, mēģini vēlreiz.
Ja tas joprojām nedarbojas, mēģini [[Special:UserLogout|izlogoties ārā]] un ielogoties no jauna.'''",
'editing' => 'Izmainīt $1',
'editingsection' => 'Izmainīt $1 (sadaļa)',
'editingcomment' => 'Izmainīt $1 (jauna sadaļa)',
'editconflict' => 'Izmaiņu konflikts: $1',
'explainconflict' => "Kāds cits ir izmainījis šo lapu pēc tam, kad tu sāki to mainīt.
Augšējā teksta logā ir lapas teksts tā pašreizējā versijā.
Tevis veiktās izmaiņas ir redzamas apakšējā teksta logā.
Lai saglabātu savas izmaiņas, tev ir jāapvieno savs teksts ar saglabāto pašreizējo variantu.
Kad spiedīsi pogu \"{{int:savearticle}}\", tiks saglabāts '''tikai''' teksts, kas ir augšējā teksta logā.",
'yourtext' => 'Tavs teksts',
'storedversion' => 'Saglabātā versija',
'nonunicodebrowser' => "'''Brīdinājums: Tavs pārlūks neatbalsta unikodu.
Ir pieejams risinājums, kas ļaus tev droši rediģēt lapas: zīmes, kas nav ASCII, parādīsies izmaiņu logā kā heksadecimāli kodi.'''",
'editingold' => "'''BRĪDINĀJUMS: Saglabājot šo lapu, tu izmainīsi šīs lapas novecojušu versiju, un ar to tiks dzēstas visas izmaiņas, kas izdarītas pēc šīs versijas.'''",
'yourdiff' => 'Atšķirības',
'copyrightwarning' => "Lūdzu, ņem vērā, ka viss ieguldījums, kas veikts {{grammar:lokatīvs|{{SITENAME}}}}, ir uzskatāms par publiskotu saskaņā ar \$2 (vairāk info skat. \$1).
Ja nevēlies, lai Tevis rakstīto kāds rediģē un izplata tālāk, tad, lūdzu, nepievieno to šeit!<br />

Izvēloties \"Saglabāt lapu\", Tu apliecini, ka šo rakstu esi rakstījis vai papildinājis pats vai izmantojis informāciju no darba, ko neaizsargā autortiesības, vai tamlīdzīga brīvi pieejama resursa.
'''BEZ ATĻAUJAS NEPIEVIENO DARBU, KO AIZSARGĀ AUTORTIESĪBAS!'''",
'copyrightwarning2' => "Lūdz ņem vērā, ka visu ieguldījumu {{grammar:lokatīvs|{{SITENAME}}}} var rediģēt, mainīt vai izdzēst citi lietotāji. Ja negribi lai ar tavu rakstīto tā izrīkojas, nepievieno to šeit.

Tu apliecini, ka šo rakstu esi rakstījis vai papildinājis pats vai izmantojis informāciju no darba, ko neaizsargā autortiesības, vai tamlīdzīga brīvi pieejama resursa (sīkāk skatīt $1).

'''BEZ ATĻAUJAS NEPIEVIENO DARBU, KO AIZSARGĀ AUTORTIESĪBAS!'''",
'longpageerror' => "'''Kļūda: Teksts, kuru tu mēģināji saglabāt, ir $1 kilobaitus garš, kas ir vairāk nekā pieļaujamie $2 kilobaiti.
Tas nevar tikt saglabāts.'''",
'readonlywarning' => "'''Brīdinājums: Datubāze ir slēgta apkopei, tāpēc tu tagad nevarēsi saglabāt veiktās izmaiņas.
Tu vari nokopēt tekstu un saglabāt kā teksta failu vēlākam laikam.'''

Admins, kas slēdza datubāzi, norādīja šādu paskaidrojumu: $1",
'protectedpagewarning' => "'''BRĪDINĀJUMS: Šī lapa ir aizsargāta, tikai lietotāji ar administratora privilēģijām var to izmainīt.'''

Pēdējais aizsargāšanas reģistra ieraksts ir apskatāms zemāk:",
'semiprotectedpagewarning' => "'''Piezīme:''' Šī lapa ir aizsargāta, lai to varētu labot tikai reģistrēti lietotāji.
Pēdējais reģistra ieraksts ir apskatāms zemāk:",
'titleprotectedwarning' => "'''Brīdinājums: Šī lapa ir slēgta un to var izveidot tikai [[Special:ListGroupRights|noteikti]] lietotāji.'''",
'templatesused' => 'Šajā lapā {{PLURAL:$1|izmantotā veidne|izmantotās veidnes}}:',
'templatesusedpreview' => 'Šajā pirmskatā {{PLURAL:$1|izmanotā veidne|izmantotās veidnes}}:',
'templatesusedsection' => 'Šajā sadaļā {{PLURAL:$1|izmantotā veidne|izmantotās veidnes}}:',
'template-protected' => '(aizsargāta)',
'template-semiprotected' => '(daļēji aizsargāta)',
'hiddencategories' => 'Šī lapa ietilpst {{PLURAL:$1|1 slēptajā kategorijā|$1 slēptajās kategorijās}}:',
'nocreatetitle' => 'Lapu veidošana ierobežota',
'nocreatetext' => '{{grammar:lokatīvs|{{SITENAME}}}} ir atslēgta iespēja izveidot jauinas lapas.
Tu vari atgriezties atpakaļ un izmainīt esošu lapu, vai arī [[Special:UserLogin|ielogoties, vai izveidot kontu]].',
'nocreate-loggedin' => 'Tev nav atļaujas veidot jaunas lapas.',
'sectioneditnotsupported-title' => 'Sadaļa rediģēšana nav atbalstīta',
'sectioneditnotsupported-text' => 'Sadaļu rediģēsana šajā lapā nav atļauta.',
'permissionserrors' => 'Atļaujas kļūdas',
'permissionserrorstext' => 'Tev nav atļauts veikt šo darbību {{PLURAL:$1|šāda iemesla|šādu iemeslu}} dēļ:',
'permissionserrorstext-withaction' => 'Tev nav atļauts $2 {{PLURAL:$1|šāda iemesla|šādu iemeslu}} dēļ:',
'recreate-moveddeleted-warn' => "'''Brīdinājums: Tu atjauno lapu, kas ir tikusi izdzēsta'''

Tev vajadzētu pārliecināties, vai ir lietderīgi turpināt izmainīt šo lapu.
Te var apskatīties dzēšanas un pārvietošanas reģistrus, kuros jābūt datiem par to kas, kad un kāpēc šo lapu izdzēsa.",
'moveddeleted-notice' => 'Šī lapa ir tikusi izdzēsta.
Te var apskatīties dzēšanas un pārvietošanas reģistru fragmentus, lai noskaidrotu kurš, kāpēc un kad to izdzēsa.',
'log-fulllog' => 'Paskatīties pilnu reģistru',
'edit-hook-aborted' => 'Aizķere pārtrauca labojumu.
Netika sniegts paskaidrojums.',
'edit-gone-missing' => 'Nevar atjaunināt lapu.
Izskatās, ka lapa ir dzēsta.',
'edit-conflict' => 'Labošanas konflikts.',
'edit-no-change' => 'Tavs labojums tika ignorēts, jo tekstā netika izdarītas izmaiņas.',
'edit-already-exists' => 'Nevar izveidot jaunu lapu.
Tā jau eksistē.',
'defaultmessagetext' => 'Noklusētais ziņojuma teksts',

# Parser/template warnings
'expensive-parserfunction-category' => 'Lapas ar pārāk daudz laikietilpīgiem apstrādes funkciju izsaukumiem',
'post-expand-template-inclusion-warning' => "'''Brīdinājums:''' iekļauto veidņu izmērs ir par lielu.
Dažas veidnes netiks iekļautas.",
'post-expand-template-inclusion-category' => 'Lapas, kurām pārsniegts iekļauto veidņu apjoms',
'post-expand-template-argument-category' => 'Lapas, kurās ir izlaisti veidņu argumenti',
'parser-template-loop-warning' => 'Veidne ir ievietota tādā pašā veidnē: [[$1]]',

# "Undo" feature
'undo-success' => 'Šo izmaiņu ir iespējams atcelt.
Lūdzu, pārbaudi zemāk redzamajā salīdzinājumā, vai tu to tiešām vēlies darīt, un pēc tam saglabā lapu, lai pabeigtu izmaiņas atcelšanu.',
'undo-failure' => 'Šo labojumu nevar atcelt, jo ir veikti nozīmīgi labojumi vēl pēc šī labojuma izdarīšanas.',
'undo-norev' => 'Šo izmaiņu nevar atcelt, jo tādas nav vai tā ir izdzēsta.',
'undo-summary' => 'Atcēlu [[Special:Contributions/$2|$2]] ([[User talk:$2|Diskusija]]) izdarīto izmaiņu $1',

# Account creation failure
'cantcreateaccounttitle' => 'Nevar izveidot lietotāju',
'cantcreateaccount-text' => "[[Lietotājs:$3|$3]] ir bloķējis lietotāja izveidošanu no šīs IP adreses ('''$1''').

$3 norādītais iemesls ir ''$2''",

# History pages
'viewpagelogs' => 'Apskatīt ar šo lapu saistītos reģistru ierakstus',
'nohistory' => 'Šai lapai nav pieejama versiju hronoloģija.',
'currentrev' => 'Pašreizējā versija',
'currentrev-asof' => 'Pašreizējā versija, $1',
'revisionasof' => 'Versija, kas saglabāta $1',
'revision-info' => 'Versija $1 laikā, kādu to atstāja $2',
'previousrevision' => '← Senāka versija',
'nextrevision' => 'Jaunāka versija →',
'currentrevisionlink' => 'skatīt pašreizējo versiju',
'cur' => 'ar pašreizējo',
'next' => 'nākamais',
'last' => 'ar iepriekšējo',
'page_first' => 'pirmā',
'page_last' => 'pēdējā',
'histlegend' => 'Atšķirību izvēle: atzīmē vajadzīgo versiju apaļās pogas un spied "Salīdzināt izvēlētās versijas".<br />
Apzīmējumi:
"ar pašreizējo" = salīdzināt ar pašreizējo versiju,
"ar iepriekšējo" = salīdzināt ar iepriekšējo versiju,
m = maznozīmīgs labojums.',
'history-fieldset-title' => 'Meklēt hronoloģijā',
'history-show-deleted' => 'Tikai dzēstās',
'histfirst' => 'Senākās',
'histlast' => 'Jaunākās',
'historysize' => '({{PLURAL:$1|1 baits|$1 baiti}})',
'historyempty' => '(tukša)',

# Revision feed
'history-feed-title' => 'Versiju hronoloģija',
'history-feed-description' => 'Šīs wiki lapas versiju hronoloģija',
'history-feed-item-nocomment' => '$1 : $2',
'history-feed-empty' => 'Pieprasītā lapa nepastāv.
Iespējams, tā ir izdzēsta vai pārdēvēta.
Mēģiniet [[Special:Search|meklēt]], lai atrastu saistītas lapas!',

# Revision deletion
'rev-deleted-comment' => '(labojuma kopsavilkums dzēsts)',
'rev-deleted-user' => '(lietotāja vārds nodzēsts)',
'rev-deleted-event' => '(reģistra ieraksts nodzēsts)',
'rev-deleted-user-contribs' => '[lietotājvārds vai IP adrese ir dzēsta — izmaiņa slēpta no devuma]',
'rev-deleted-text-permission' => "Šī lapas versija ir '''dzēsta'''.
Sīkāku informāciju var atrast [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} dzēšanas reģistrā].",
'rev-deleted-text-view' => "Šī lapas versija ir '''dzēsta'''.
To varat apskatīt, jo esat administrators. Sīkāku informāciju var atrast [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} dzēšanas reģistrā].",
'rev-deleted-no-diff' => "Tu nevari aplūkot šīs izmaiņas, jo viena no versijām ir '''dzēsta'''.
Sīkāku informāciju var atrast [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} dzēšanas reģistrā].",
'rev-suppressed-no-diff' => "Tu nevari aplūkot šīs izmaiņas, jo viena no versijām ir '''dzēsta'''.",
'rev-delundel' => 'rādīt/slēpt',
'rev-showdeleted' => 'parādīt',
'revisiondelete' => 'Dzēst / atjaunot versijas',
'revdelete-nooldid-title' => 'Nederīga mērķa versija',
'revdelete-nologtype-title' => 'Nav dots reģistra veids.',
'revdelete-nologid-title' => 'Nederīgs reģistra ieraksts',
'revdelete-no-file' => 'Norādītais fails neeksistē.',
'revdelete-show-file-submit' => 'Jā',
'revdelete-legend' => 'Uzstādīt redzamības ierobežojumus',
'revdelete-hide-text' => 'Paslēpt versijas tekstu',
'revdelete-hide-image' => 'Paslēpt faila saturu',
'revdelete-hide-name' => 'Paslēpt darbību un tās objektu',
'revdelete-hide-comment' => 'Paslēpt kopsavilkumu',
'revdelete-hide-user' => 'Paslēpt autora lietotājvārdu/IP adresi',
'revdelete-hide-restricted' => 'Paslēpt datus arī no administratoriem',
'revdelete-radio-same' => '(nemainīt)',
'revdelete-radio-set' => 'Jā',
'revdelete-radio-unset' => 'Nē',
'revdelete-suppress' => 'Paslēpt datus arī no administratoriem',
'revdelete-unsuppress' => 'Atcelt ierobežojumus atjaunotajām versijām',
'revdelete-log' => 'Iemesls:',
'revdelete-submit' => 'Piemērot {{PLURAL:$1|izvēlētajai versijai|izvēlētajām versijām}}',
'revdelete-success' => "'''Versiju redzamība veiksmīgi atjaunināta.'''",
'revdelete-failure' => "'''Versiju redzamību nav iespējams atjaunināt:'''
$1",
'logdelete-success' => "'''Reģistra ierakstu redzamība veiksmīgi uzstādīta.'''",
'logdelete-failure' => "'''Reģistra redzamību nevar uzstādīt:'''
$1",
'revdel-restore' => 'mainīt redzamību',
'revdel-restore-deleted' => 'dzēstās versijas',
'revdel-restore-visible' => 'redzamās versijas',
'pagehist' => 'Lapas vēsture',
'deletedhist' => 'Vēsture dzēsta',
'revdelete-modify-missing' => 'Kļūda, mainot vienumu ar ID $1: tas ir pazudis no datubāzes!',
'revdelete-reason-dropdown' => '*Biežākie dzēšanas iemesli
** autortiesību pārkāpums
** nepiemērota personīgā informācija
** potenciāli apmelojoša informācija',
'revdelete-otherreason' => 'Cits/papildu iemesls:',
'revdelete-reasonotherlist' => 'Cits iemesls',
'revdelete-edit-reasonlist' => 'Izmainīt dzēšanas iemeslus',
'revdelete-offender' => 'Versijas autors:',

# History merging
'mergehistory' => 'Apvienot lapu vēstures',
'mergehistory-box' => 'Apvienot versijas no divām lapām:',
'mergehistory-from' => 'Avota lapa:',
'mergehistory-into' => 'Mērķa lapa:',
'mergehistory-list' => 'Apvienojama labojumu vēsture',
'mergehistory-go' => 'Rādīt apvienojamos labojumus',
'mergehistory-submit' => 'Apvienot versijas',
'mergehistory-empty' => 'Neviena versija nevar tikt apvienota',
'mergehistory-fail' => 'Nav iespējams apvienot hronoloģiju, lūdzu, pārbaudiet vēlreiz lapu un laika parametrus.',
'mergehistory-no-source' => 'Avota lapa $1 nepastāv.',
'mergehistory-no-destination' => 'Mērķa lapa $1 nepastāv.',
'mergehistory-invalid-source' => 'Avota lapas nosaukumam jābūt derīgam.',
'mergehistory-invalid-destination' => 'Mērķa lapas nosaukumam jābūt derīgam.',
'mergehistory-autocomment' => 'Apvienots [[:$1]] ar [[:$2]]',
'mergehistory-comment' => 'Apvienots [[:$1]] ar [[:$2]]: $3',
'mergehistory-same-destination' => 'Avota un mērķa lapas nevar būt tās pašas',
'mergehistory-reason' => 'Iemesls:',

# Merge log
'mergelog' => 'Apvienošanas reģistrs',
'pagemerge-logentry' => 'apvienots [[$1]] ar [[$2]] (versijas līdz $3)',
'revertmerge' => 'Atsaukt apvienošanu',

# Diffs
'history-title' => '"$1" versiju hronoloģija',
'difference-title' => 'Atšķirības starp "$1" versijām',
'difference-multipage' => '(Atšķirības starp lapām)',
'lineno' => '$1. rindiņa:',
'compareselectedversions' => 'Salīdzināt izvēlētās versijas',
'showhideselectedversions' => 'Rādīt/slēpt izvēlētās versijas',
'editundo' => 'atcelt',
'diff-multi' => '({{PLURAL:$1|Viena starpversija|$1 starpversijas}} no {{PLURAL:$2|viena lietotāja|$2 lietotājiem}} nav parādīta)',

# Search results
'searchresults' => 'Meklēšanas rezultāti',
'searchresults-title' => 'Meklēšanas rezultāti "$1"',
'searchresulttext' => 'Lai iegūtu vairāk informācijas par meklēšanu {{grammar:akuzatīvs|{{SITENAME}}}}, skat. [[{{MediaWiki:Helppage}}|{{grammar:ģenitīvs|{{SITENAME}}}} meklēšana]].',
'searchsubtitle' => 'Pieprasījums: \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|visas lapas, kas sākas ar "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|visas lapas, kurās ir saite uz "$1"]])',
'searchsubtitleinvalid' => 'Pieprasījums: $1',
'toomanymatches' => 'Tika atgriezti poārāk daudzi rezultāti, lūdzu pamēģini citādāku pieprasījumu',
'titlematches' => 'Rezultāti virsrakstos',
'notitlematches' => 'Neviena rezultāta, meklējot lapas virsrakstā',
'textmatches' => 'Rezultāti lapu tekstos',
'notextmatches' => 'Neviena rezultāta, meklējot lapas tekstā',
'prevn' => 'iepriekšējās {{PLURAL:$1|$1}}',
'nextn' => 'nākamās {{PLURAL:$1|$1}}',
'prevn-title' => '{{PLURAL:$1|Iepriekšējais|Iepriekšējie}} $1 {{PLURAL:$1|rezultāts|rezultāti}}',
'nextn-title' => '{{PLURAL:$1|Nākošais|INākošie}} $1 {{PLURAL:$1|rezultāts|rezultāti}}',
'shown-title' => 'Parādīt $1 {{PLURAL:$1|rezultātu|rezultātus}} vienā lapā',
'viewprevnext' => 'Skatīt ($1 {{int:pipe-separator}} $2) ($3 vienā lapā).',
'searchmenu-legend' => 'Meklēšanas iespējas',
'searchmenu-exists' => "'''Šajā projektā ir raksts ar nosaukumu \"[[:\$1]]\"'''",
'searchmenu-new' => "'''Izveido rakstu \"[[:\$1]]\" šajā projektā!'''",
'searchhelp-url' => 'Help:Saturs',
'searchprofile-articles' => 'Rakstos',
'searchprofile-project' => 'Palīdzības un projektu lapās',
'searchprofile-images' => 'Multivides failos',
'searchprofile-everything' => 'Visur',
'searchprofile-advanced' => 'Izvēlēties sīkāk',
'searchprofile-articles-tooltip' => 'Meklēt iekš $1',
'searchprofile-project-tooltip' => 'Meklēt iekš $1',
'searchprofile-images-tooltip' => 'Meklēt attēlus, audio un video failus',
'searchprofile-everything-tooltip' => 'Meklēt visur (ieskaitot diskusiju lapas)',
'searchprofile-advanced-tooltip' => 'Izvēlēties nosaukumvietas, kurās meklēt',
'search-result-size' => '$1 ({{PLURAL:$2|1 vārds|$2 vārdi}})',
'search-result-score' => 'Atbilstība: $1%',
'search-redirect' => '(pāradresēts no $1)',
'search-section' => '(sadaļa $1)',
'search-suggest' => 'Vai jūs domājāt: $1',
'search-interwiki-caption' => 'Citi projekti',
'search-interwiki-default' => 'Rezultāti no $1:',
'search-interwiki-more' => '(vairāk)',
'search-relatedarticle' => 'Saistītais',
'mwsuggest-disable' => 'Atslēgt AJAX ieteikumus',
'searcheverything-enable' => 'Meklēt visās nosaukumvietās',
'searchrelated' => 'saistītais',
'searchall' => 'viss',
'showingresults' => "Šobrīd ir {{PLURAL:$1|redzama|redzamas}} '''$1''' {{PLURAL:$1|lapa|lapas}}, sākot ar #'''$2'''.",
'showingresultsnum' => "Šobrīd ir {{PLURAL:$3|redzama|redzamas}} '''$3''' {{PLURAL:$3|lapa|lapas}}, sākot ar #'''$2'''.",
'showingresultsheader' => "{{PLURAL:$5|Šobrīd ir redzama '''$1''' lapa no '''$3'''|Šobrīd ir redzamas '''$1 — $2''' lapas no '''$3'''}}, kas satur '''$4'''",
'nonefound' => "'''Piezīme:''' bieži vien meklēšana ir neveiksmīga, meklējot plaši izplatītus vārdus, piemēram, \"un\" vai \"ir\", jo tie netiek iekļauti meklēšanas datubāzē, vai arī meklējot vairāk par vienu vārdu (jo rezultātos parādīsies tikai lapas, kurās ir visi meklētie vārdi). Vēl, pēc noklusējuma, pārmeklē tikai dažas ''namespaces''. Lai meklētu visās, meklēšanas pieprasījumam priekšā jāieliek ''all:'', vai arī analogā veidā jānorāda pārmeklējamo ''namespaci''.",
'search-nonefound' => 'Nav atrasti pieprasījumam atbilstoši rezultāti.',
'powersearch' => 'Izvērstā meklēšana',
'powersearch-legend' => 'Izvērstā meklēšana',
'powersearch-ns' => 'Meklēt šajās lapu grupās:',
'powersearch-redir' => 'Parādīt pāradresācijas',
'powersearch-field' => 'Meklēt',
'powersearch-togglelabel' => 'Pārbaudīt:',
'powersearch-toggleall' => 'Viss',
'powersearch-togglenone' => 'Neviena',
'search-external' => 'Ārējā meklēšana',
'searchdisabled' => 'Meklēšana {{grammar:lokatīvs|{{SITENAME}}}} šobrīd ir atslēgta darbības traucējumu dēļ.
Pagaidām vari meklēt, izmantojot Google vai Yahoo.
Ņem vērā, ka meklētāju indeksētais {{grammar:ģenitīvs|{{SITENAME}}}} saturs var būt novecojis.',

# Quickbar
'qbsettings' => 'Rīku joslas stāvoklis',
'qbsettings-fixedleft' => 'Fiksēts pa kreisi',
'qbsettings-fixedright' => 'Fiksēts pa labi',
'qbsettings-floatingleft' => 'Peldošs pa kreisi',
'qbsettings-floatingright' => 'Peldošs pa labi',

# Preferences page
'preferences' => 'Izvēles',
'mypreferences' => 'Iestatījumi',
'prefs-edits' => 'Izmaiņu skaits:',
'prefsnologin' => 'Neesi iegājis',
'prefsnologintext' => 'Tev jābūt <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} iegājušam]</span>, lai mainītu lietotāja izvēles.',
'changepassword' => 'Mainīt paroli',
'prefs-skin' => 'Apdare',
'skin-preview' => 'Priekšskats',
'datedefault' => 'Vienalga',
'prefs-beta' => 'Beta funkcijas',
'prefs-datetime' => 'Datums un laiks',
'prefs-labs' => 'Laboratorijas funkcijas',
'prefs-user-pages' => 'Lietotāja lapas',
'prefs-personal' => 'Lietotāja dati',
'prefs-rc' => 'Pēdējās izmaiņas',
'prefs-watchlist' => 'Uzraugāmie raksti',
'prefs-watchlist-days' => 'Dienu skaits, kuras parādīt uzraugāmo rakstu sarakstā:',
'prefs-watchlist-days-max' => 'Ne vairāk kā $1 {{PLURAL:$1|dienu|dienas}}',
'prefs-watchlist-edits' => 'Izmaiņu skaits, kuras rādīt izvērstajā uzraugāmo rakstu sarakstā:',
'prefs-watchlist-edits-max' => 'Ne vairāk kā 1000',
'prefs-watchlist-token' => 'Uzraugāmo lapu saraksta marķieris:',
'prefs-misc' => 'Dažādi',
'prefs-resetpass' => 'Mainīt paroli',
'prefs-changeemail' => 'Mainīt e-pastu',
'prefs-setemail' => 'Uzstādīt e-pasta adresi',
'prefs-email' => 'E-pasta uzstādījumi',
'prefs-rendering' => 'Izskats',
'saveprefs' => 'Saglabāt',
'resetprefs' => 'Atcelt nesaglabātās izmaiņas',
'restoreprefs' => 'Atjaunot noklusētos uzstādījumus',
'prefs-editing' => 'Rediģēšana',
'prefs-edit-boxsize' => 'Labošanas loga izmērs.',
'rows' => 'Rindiņu skaits:',
'columns' => 'Simbolu skaits rindiņā:',
'searchresultshead' => 'Meklēšana',
'resultsperpage' => 'Lappusē parādāmo rezultātu skaits',
'stub-threshold-disabled' => 'Atslēgts',
'recentchangesdays' => 'Dienu skaits, kuru rādīt pēdējajās izmaiņās:',
'recentchangesdays-max' => 'Ne vairāk kā $1 {{PLURAL:$1|diena|dienas}}',
'recentchangescount' => 'Izmaiņu skaits, kuru rāda pēc noklusējuma:',
'prefs-help-recentchangescount' => 'Šis parametrs attiecas uz pēdējo izmaiņu un hronoloģijas lapām, kā arī uz sistēmas žurnāliem',
'prefs-help-watchlist-token' => 'Šajā laukā tu vari ievadīt slepenu kodu, lai izveidotu RSS barotni savam uzraugāmo lapu sarakstam.
Izvēlies drošu kodu, jo katrs, kam ir zināms šis kods, varēs redzēt tavu uzraugāmo lapu sarakstu.
Ja vēlies, tu vari izmantot šo nejauši uzģenerēto kodu: $1',
'savedprefs' => 'Tavas izvēles ir saglabātas.',
'timezonelegend' => 'Laika josla:',
'localtime' => 'Vietējais laiks:',
'timezoneuseserverdefault' => 'Lietot viki noklusēto ($1)',
'timezoneuseoffset' => 'Cita (norādi starpību)',
'timezoneoffset' => 'Starpība¹:',
'servertime' => 'Servera laiks šobrīd:',
'guesstimezone' => 'Izmantot datora sistēmas laiku',
'timezoneregion-africa' => 'Āfrika',
'timezoneregion-america' => 'Amerika',
'timezoneregion-antarctica' => 'Antarktīda',
'timezoneregion-arctic' => 'Arktika',
'timezoneregion-asia' => 'Āzija',
'timezoneregion-atlantic' => 'Atlantijas okeāns',
'timezoneregion-australia' => 'Austrālija',
'timezoneregion-europe' => 'Eiropa',
'timezoneregion-indian' => 'Indijas okeāns',
'timezoneregion-pacific' => 'Klusais okeāns',
'allowemail' => 'Atļaut saņemt e-pastus no citiem lietotājiem',
'prefs-searchoptions' => 'Meklēšana',
'prefs-namespaces' => 'Vārdtelpas',
'defaultns' => 'Meklēt šajās palīglapās pēc noklusējuma:',
'default' => 'pēc noklusējuma',
'prefs-files' => 'Attēli',
'prefs-custom-css' => 'Personīgais CSS',
'prefs-custom-js' => 'Personīgais JS',
'prefs-common-css-js' => 'Koplietojams CSS/JavaScript visās apdarēs:',
'prefs-emailconfirm-label' => 'E-pasta statuss:',
'prefs-textboxsize' => 'Rediģēšanas loga izmērs',
'youremail' => 'Tava e-pasta adrese:',
'username' => 'Lietotājvārds:',
'uid' => 'Lietotāja ID:',
'prefs-memberingroups' => 'Pieder {{PLURAL:$1|grupai|grupām}}:',
'prefs-registration' => 'Reģistrēšanās datums:',
'yourrealname' => 'Tavs īstais vārds:',
'yourlanguage' => 'Valoda:',
'yourvariant' => 'Satura valodas variants:',
'yournick' => 'Tava iesauka (parakstam):',
'prefs-help-signature' => 'Komentāri diskusiju lapās ir jāparaksta, pievienojot simbolu virkni "<nowiki>~~~~</nowiki>", kas tiek automātiski aizstāta ar tavu parakstu un parakstīšanās laiku.',
'badsig' => "Kļūdains ''paraksta'' kods; pārbaudi HTML (ja tāds ir lietots).",
'badsiglength' => 'Paraksts ir pārāk garš.
Tam ir jābūt īsākam par  $1 {{PLURAL:$1|simbolu|simboliem}}.',
'yourgender' => 'Dzimums:',
'gender-unknown' => 'Nav norādīts',
'gender-male' => 'Vīrietis',
'gender-female' => 'Sieviete',
'prefs-help-gender' => 'Dzimums nav obligāti jānorāda (šo parametru programmatūra izmanto, lai ģenerētu paziņojumus, kas atkarīgi no lietotāja dzimuma).
Norādītā parametra vērtība būs publiski pieejama.',
'email' => 'E-pasts',
'prefs-help-realname' => 'Īstais vārds nav obligāti jānorāda.
Ja tu izvēlies to norādīt, tas tiks izmantots, lai identificētu tavu darbu (ieguldījumu {{grammar:lokatīvs|{{SITENAME}}}}).',
'prefs-help-email' => 'E-pasta adrese nav obligāta, bet ir nepieciešama nozaudētas paroles atjaunošanai.',
'prefs-help-email-required' => 'E-pasta adrese ir obligāta.',
'prefs-info' => 'Pamatinformācija',
'prefs-i18n' => 'Internacionalizācija',
'prefs-signature' => 'Paraksts',
'prefs-dateformat' => 'Datuma formāts',
'prefs-timeoffset' => 'Laika nobīde',
'prefs-advancedediting' => 'Papildus uzstādījumi',
'prefs-advancedrc' => 'Papildus uzstādījumi',
'prefs-advancedrendering' => 'Papildus uzstādījumi',
'prefs-advancedsearchoptions' => 'Papildus uzstādījumi',
'prefs-advancedwatchlist' => 'Papildus uzstādījumi',
'prefs-displayrc' => 'Pamatuzstādījumi',
'prefs-displaysearchoptions' => 'Pamatuzstādījumi',
'prefs-displaywatchlist' => 'Pamatuzstādījumi',
'prefs-diffs' => 'Izmaiņas',

# User preference: e-mail validation using jQuery
'email-address-validity-valid' => 'E-pasta adrese šķiet derīga',
'email-address-validity-invalid' => 'Ievadiet derīgu e-pasta adresi',

# User rights
'userrights' => 'Lietotāju tiesību pārvaldība',
'userrights-lookup-user' => 'Pārvaldīt lietotāja grupas',
'userrights-user-editname' => 'Ievadi lietotājvārdu:',
'editusergroup' => 'Izmainīt lietotāja grupas',
'editinguser' => "Izmainīt lietotāja '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]]) statusu",
'userrights-editusergroup' => 'Izmainīt lietotāja grupas',
'saveusergroups' => 'Saglabāt lietotāja grupas',
'userrights-groupsmember' => 'Šobrīd ietilpst grupās:',
'userrights-groupsmember-auto' => 'Netiešs dalībnieks:',
'userrights-groups-help' => 'Tu vari izmainīt kādās grupās šis lietotājs ir:
* Ieķeksēts lauciņš noāda, ka lietotājs ir attiecīgajā grupā.
* Neieķeksēts lauciņš norāda, ka lietotājs nav attiecīgajā grupā.
* * norāda, ka šo grupu tu nevarēsi noņemt, pēc tam, kad to būsi pielicis, vai otrādāk (tu nevarēsi atcelt savas izmaiņas).',
'userrights-reason' => 'Iemesls:',
'userrights-no-interwiki' => 'Tev nav atļaujas izmainīt lietotāju tiesības citos wiki.',
'userrights-nodatabase' => 'Datubāze $1 neeksistē vai nav lokāla.',
'userrights-nologin' => 'Tev ir [[Special:UserLogin|jāieiet iekšā]] kā adminam, lai varētu izmainīt lietotāju grupas.',
'userrights-notallowed' => 'Jūsu lietotāja kontam nav atļaujas pievienot vai noņemt lietotāju tiesības.',
'userrights-changeable-col' => 'Grupas, kuras tu vari izmainīt',
'userrights-unchangeable-col' => 'Grupas, kuras tu nevari izmainīt',

# Groups
'group' => 'Grupa:',
'group-user' => 'Lietotāji',
'group-autoconfirmed' => 'Automātiski apstiprinātie lietotāji',
'group-bot' => 'Boti',
'group-sysop' => 'Administratori',
'group-bureaucrat' => 'Birokrāti',
'group-suppress' => 'Novērotāji',
'group-all' => '(visi)',

'group-user-member' => '{{GENDER:$1|lietotājs}}',
'group-autoconfirmed-member' => '{{GENDER:$1|automātiski apstiprināts lietotājs|automātiski apstiprināta lietotāja}}',
'group-bot-member' => '{{GENDER:$1|bots}}',
'group-sysop-member' => '{{GENDER:$1|administrators|administratore}}',
'group-bureaucrat-member' => '{{GENDER:$1|birokrāts|birokrāte}}',
'group-suppress-member' => 'novērotājs',

'grouppage-user' => '{{ns:project}}:Lietotāji',
'grouppage-autoconfirmed' => '{{ns:project}}:Automātiski apstiprināti lietotāji',
'grouppage-bot' => '{{ns:project}}:Boti',
'grouppage-sysop' => '{{ns:project}}:Administratori',
'grouppage-bureaucrat' => '{{ns:project}}:Birokrāti',
'grouppage-suppress' => '{{ns:project}}:Novērotājs',

# Rights
'right-read' => 'Lasīt lapas',
'right-edit' => 'Izmainīt lapas',
'right-createpage' => 'Izveidot lapas (kuras nav diskusiju lapas)',
'right-createtalk' => 'Izveidot diskusiju lapas',
'right-createaccount' => 'Izveidot jaunus lietotāja kontus',
'right-minoredit' => 'Atzīmēt izmaiņas kā maznozīmīgas',
'right-move' => 'Pārvietot lapas',
'right-move-subpages' => 'Pārvietot lapas kopā ar to apakšlapām',
'right-move-rootuserpages' => 'Pārvietot saknes lietotāja lapas',
'right-movefile' => 'Pārvietot failus',
'right-suppressredirect' => 'Neveidot pāradresāciju no vecā nosaukuma, pārvietojot lapu',
'right-upload' => 'Augšuplādēt failus',
'right-reupload' => 'Pārrakstīt esošu failu',
'right-reupload-own' => 'Pārrakstīt paša augšuplādētu esošu failu',
'right-upload_by_url' => 'Augšuplādēt failu no URL',
'right-autoconfirmed' => 'Izmainīt daļēji aizsargātas lapas',
'right-delete' => 'Dzēst lapas',
'right-bigdelete' => 'Dzēst lapas ar lielām hronoloģijām',
'right-deleterevision' => 'Dzēst un atjaunot lapu noteiktas versijas',
'right-deletedhistory' => 'Skatīt izdzēstos hronoloģijas ierakstus, bez tiem piesaistītā teksta',
'right-deletedtext' => 'Apskatīt izdzēsto tekstu un izmaiņas starp izdzēstām versijām',
'right-browsearchive' => 'Meklēt izdzēstās lapas',
'right-undelete' => 'Atjaunot lapu',
'right-suppressrevision' => 'Apskatīt un atjaunot versijas, kas paslēptas no adminiem',
'right-suppressionlog' => 'Skatīt personīgos reģistrus',
'right-block' => 'Bloķēt citus lietotājus (lapu izmainīšana)',
'right-blockemail' => 'Bloķēt citus lietotājus (iespēja sūtīt e-pastu)',
'right-hideuser' => 'Bloķēt lietotājvārdu, slēpjot to no citiem lietotājiem',
'right-ipblock-exempt' => 'Apiet IP bloķēšanu, automātisku bloķēšanu un IP apgabalu bloķēšanu',
'right-proxyunbannable' => "Apiet ''proxy'' automātiskos blokus",
'right-unblockself' => 'Atbloķēt sevi',
'right-protect' => 'Izmainīt aizsargātās lapas un to aizsardzības līmeni',
'right-editprotected' => 'Labot aizsargātās lapas (bez kaskādes aizsardzības)',
'right-editinterface' => 'Izmainīt lietotāja interfeisu',
'right-editusercssjs' => 'Izmainīt citu lietotāju CSS un JS failus',
'right-editusercss' => 'Izmainīt citu lietotāju CSS failus',
'right-edituserjs' => 'Izmainīt citu lietotāju JS failus',
'right-rollback' => 'Ātri veikt atriti pēdējā lietotāja labojumiem, kas veica izmaiņas kādā konkrētā lapā',
'right-markbotedits' => 'Atzīmēt labojumus, kam veikta atrite, kā bota labojumus',
'right-noratelimit' => 'Būt darbību ātruma ierobežojumu neietekmētiem',
'right-import' => 'Importēt lapas no citiem wiki',
'right-importupload' => 'Importēt lapas no failu augšuplādes',
'right-patrol' => 'Atzīmēt citu labojumus kā pārbaudītus',
'right-autopatrol' => 'Iespēja, ka ikviens paša veiktais labojums tiek automātiski atzīmēts kā pārbaudīts',
'right-patrolmarks' => 'Apskatīt pēdējo izmaiņu lapu pārbaužu atzīmes',
'right-unwatchedpages' => 'Apskatīt neuzraudzīto lapu sarakstu',
'right-mergehistory' => 'Apvienot lapu vēsturi',
'right-userrights' => 'Mainīt visu lietotāju tiesības',
'right-userrights-interwiki' => 'Mainīt lietotāju tiesības citās Vikipēdijās',
'right-siteadmin' => 'Bloķēt un atbloķēt datubāzi',
'right-sendemail' => 'Sūtīt e-pastu citiem lietotājiem',
'right-passwordreset' => 'Apskatīt paroles atiestatīšanas e-pasta ziņojumus',

# User rights log
'rightslog' => 'Lietotāju tiesību reģistrs',
'rightslogtext' => 'Šis ir lietotāju tiesību izmaiņu reģistrs.',
'rightslogentry' => 'izmainīja $1 grupas no $2 uz $3',
'rightsnone' => '(nav)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'lasīt šo lapu',
'action-edit' => 'labot šo lapu',
'action-createpage' => 'izveidot lapas',
'action-createtalk' => 'izveidot diskusiju lapas',
'action-createaccount' => 'izveidot šo lietotāja kontu',
'action-minoredit' => 'atzīmēt šo labojumu kā maznozīmīgu',
'action-move' => 'pārvietot šo lapu',
'action-move-subpages' => 'pārvietot šo lapu un tās apakšlapas',
'action-move-rootuserpages' => 'pārvietot saknes lietotāja lapas',
'action-movefile' => 'pārvietot šo failu',
'action-upload' => 'augšupielādēt šo failu',
'action-reupload' => 'pārrakstīt esošo failu',
'action-upload_by_url' => 'augšupielādēt šo failu no URL',
'action-writeapi' => 'izmantot rakstīto lietojumprogrammu saskarni',
'action-delete' => 'izdzēst šo lapu',
'action-deleterevision' => 'izdzēst šo versiju',
'action-deletedhistory' => 'skatīt šīs lapas dzēsto hronoloģiju',
'action-browsearchive' => 'meklēt dzēstās lapas',
'action-undelete' => 'atjaunot šo lapu',
'action-suppressrevision' => 'pārskatīt un atjaunot šo slēpto versiju',
'action-suppressionlog' => 'apskatīt šo privāto reģistru',
'action-block' => 'bloķēt šo lietotāju pret rakstu turpmāku labošanu',
'action-protect' => 'izmainīt aizsardzības līmeņus šai lapai',
'action-import' => 'importēt šo lapu no citas viki',
'action-importupload' => 'importēt šo lapu no failu augšupielādes',
'action-patrol' => 'atzīmēt citu labojumus kā pārbaudītus',
'action-autopatrol' => 'iespēja savus labojumus atzīmēt kā pārbaudītus',
'action-unwatchedpages' => 'apskatīt neuzraudzīto lapu sarakstu',
'action-mergehistory' => 'apvienot šīs lapas vēsturi',
'action-userrights' => 'mainīt visu lietotāju tiesības',
'action-userrights-interwiki' => 'mainīt lietotāju tiesības citās Vikipēdijās',
'action-siteadmin' => 'bloķēt vai atbloķēt datubāzi',
'action-sendemail' => 'sūtīt e-pastus',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|izmaiņa|izmaiņas}}',
'recentchanges' => 'Pēdējās izmaiņas',
'recentchanges-legend' => 'Pēdējo izmaiņu iespējas',
'recentchanges-summary' => 'Šajā lapā ir uzskaitītas pēdējās izdarītās izmaiņas.',
'recentchanges-feed-description' => 'Sekojiet līdzi jaunākajām izmaiņām vikijā izmantojot šo barotni.',
'recentchanges-label-newpage' => 'Šī ir jaunizveidota lapa',
'recentchanges-label-minor' => 'Šī ir maznozīmīga izmaiņa',
'recentchanges-label-bot' => 'Šī ir bota veikta izmaiņa',
'recentchanges-label-unpatrolled' => 'Šis labojums vēl nav pārbaudīts',
'rcnote' => 'Šobrīd ir {{PLURAL:$1|redzama pēdējā <strong>$1</strong> izmaiņa, kas izdarīta|redzamas pēdējās <strong>$1</strong> izmaiņas, kas izdarītas}} {{PLURAL:$2|pēdējā|pēdējās}} <strong>$2</strong> {{PLURAL:$2|dienā|dienās}} (līdz $4, $5).',
'rcnotefrom' => "Šobrīd redzamas izmaiņas kopš '''$2''' (parādītas ne vairāk par '''$1''').",
'rclistfrom' => 'Parādīt jaunas izmaiņas kopš $1',
'rcshowhideminor' => '$1 maznozīmīgos',
'rcshowhidebots' => '$1 botus',
'rcshowhideliu' => '$1 reģistrētos',
'rcshowhideanons' => '$1 anonīmos',
'rcshowhidepatr' => '$1 pārbaudītie labojumi',
'rcshowhidemine' => '$1 manus',
'rclinks' => 'Parādīt pēdējās $1 izmaiņas pēdējās $2 dienās.<br />$3',
'diff' => 'izmaiņas',
'hist' => 'hronoloģija',
'hide' => 'paslēpt',
'show' => 'parādīt',
'minoreditletter' => 'm',
'newpageletter' => 'J',
'boteditletter' => 'b',
'number_of_watching_users_pageview' => '[šo lapu uzrauga $1 {{PLURAL:$1|lietotājs|lietotāji}}]',
'rc_categories' => 'Ierobežot uz kategorijām (atdalīt ar "|")',
'rc_categories_any' => 'Jebkas',
'newsectionsummary' => '/* $1 */ jauna sadaļa',
'rc-enhanced-expand' => 'Rādīt informāciju (nepieciešams JavaScript)',
'rc-enhanced-hide' => 'Paslēpt detaļas',
'rc-old-title' => 'sākotnēji izveidota kā "$1 "',

# Recent changes linked
'recentchangeslinked' => 'Saistītās izmaiņas',
'recentchangeslinked-feed' => 'Saistītās izmaiņas',
'recentchangeslinked-toolbox' => 'Saistītās izmaiņas',
'recentchangeslinked-title' => 'Izmaiņas, kas saistītas ar "$1"',
'recentchangeslinked-noresult' => 'Norādītajā laika periodā saistītajās lapās izmaiņu nebija.',
'recentchangeslinked-summary' => "Šiet ir nesen izdarītās izmaiņas lapās, uz kurām ir saites no norādītās lapas (vai norādītajā kategorijā ietilpstošās lapas).
Lapas, kas ir tavā [[Special:Watchlist|uzraugāmo rakstu sarakstā]] ir '''treknas'''.",
'recentchangeslinked-page' => 'Lapas nosaukums:',
'recentchangeslinked-to' => 'Rādīt izmaiņas lapās, kurās ir saites uz šo lapu (nevis lapās uz kurām ir saites no šīs lapas)',

# Upload
'upload' => 'Augšuplādēt failu',
'uploadbtn' => 'Augšuplādēt',
'reuploaddesc' => 'Atcelt augšupielādi un atgriezties pie augšupielādes veidnes.',
'upload-tryagain' => 'Iesniegt izmainīto faila aprakstu',
'uploadnologin' => 'Neesi iegājis',
'uploadnologintext' => 'Tev jābūt [[Special:UserLogin|iegājušam]], lai augšuplādētu failus.',
'upload_directory_missing' => 'Augšupielādes direktorijs ($1) ir pazudis, un to tīmekļa serveris nevar izveidot.',
'upload_directory_read_only' => 'Augšupielādes direktoriju ($1) tīmekļa serveris nevar labot.',
'uploaderror' => 'Augšupielādes kļūda',
'upload-recreate-warning' => "'''Brīdinājums: Fails ar šādu nosaukumu ir dzēsts vai pārvietots.'''

 Dzēšanas un pārvietošanas reģistri šai lapai ir uzskaitīti šeit:",
'uploadtext' => "Pirms tu kaut ko augšupielādē, noteikti izlasi un ievēro [[Project:Attēlu izmantošanas noteikumi|attēlu izmantošanas noteikumus]].

Lai aplūkotu vai meklētu agrāk augšuplādētus attēlus,
dodies uz [[Special:FileList|augšupielādēto attēlu sarakstu]].
Augšupielādes un dzēšanas tiek reģistrētas [[Special:Log/upload|augšupielādes reģistrā]] un [[Special:Log/delete|dzēšanas reģistrā]].

Izmanto šo veidni, lai augšupielādētu jaunus attēlu failus, ar kuriem ilustrēt tevis izmainītās lapas.
Gandrīz visos pārlūkos tev vajadzētu redzēt pogu '''\"Choose...\",''' kuru spiežot parādīsies faila atvēršanas dialogs.
Izvēloties kādu failu, tā adrese parādīsies ailītē blakus šai pogai.
Tev ir arī jāatzīmē ailīte, kas apstiprina, ka tu nepārkāp nekādas autortiesības, augšupielādējot šo failu.
Spied pogu '''Augšuplādēt''', lai pabeigtu augšupielādi.
Tas var ieilgt, ja tavs interneta pieslēgums ir lēns.

Ieteicamie formāti ir:
* JPEG - ja tā ir fotogrāfija,
* PNG - ja tas ir zīmējums vai kāda ikona, un
* OGG - ja tas ir skaņas fails.

Lūdzu, pārliecinies, ka faila nosaukums ir pietiekami aprakstošs, lai izvairītos no neskaidrībām. Lai attēlu pēc tam ievietotu kādā lapā, izmanto šādi noformētu linkus:
* '''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:Fails.jpg|paskaidrojošs teksts]]</nowiki>'''
* '''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:Fails.png|paskaidrojošs teksts]]</nowiki>'''
vai skaņām
* '''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Fails.ogg]]</nowiki>'''

Lūdzu, ņem vērā, ka tāpat kā citas wiki lapas arī tevis augšuplādētos failus citi var mainīt vai dzēst, ja uzskata, ka tas nāktu par labu šim projektam, kā arī atceries, ka tev var tikt liegta augšupielādes iespēja, ja tu šo sistēmu.",
'upload-permitted' => 'Atļautie failu tipi: $1.',
'upload-preferred' => 'Ieteicamie failu tipi: $1.',
'upload-prohibited' => 'Aizliegtie failu tipi: $1.',
'uploadlog' => 'augšupielādes reģistrs',
'uploadlogpage' => 'Augšupielādes reģistrs',
'uploadlogpagetext' => 'Zemāk ir redzams jaunāko augšuplādēto failu saraksts.
Pārskatāmāka versija ir pieejama [[Special:NewFiles|jauno attēlu galerijā]].',
'filename' => 'Faila nosaukums',
'filedesc' => 'Kopsavilkums',
'fileuploadsummary' => 'Informācija par failu:',
'filereuploadsummary' => 'Faila izmaiņas:',
'filestatus' => 'Autortiesību statuss:',
'filesource' => 'Izejas kods:',
'uploadedfiles' => 'Augšupielādēja failus',
'ignorewarning' => 'Ignorēt brīdinājumu un saglabāt failu',
'ignorewarnings' => 'Ignorēt visus brīdinājumus',
'minlength1' => 'Failu vārdiem jābūt vismaz vienu simbolu gariem.',
'illegalfilename' => 'Faila nosaukumā "$1" ir simboli, kas nav atļauti virsrakstos. Lūdzu, pārdēvē failu un mēģini to vēlreiz augšuplādēt.',
'filename-toolong' => 'Failu nosaukumi nedrīkst pārsniegt 240 baitus.',
'badfilename' => 'Attēla nosaukums ir nomainīts, tagad tas ir "$1".',
'filetype-mime-mismatch' => 'Faila paplašinājums ".$1" neatbilst noteiktajam MIME tipam ($2).',
'filetype-badmime' => 'Šeit nav atļauts augšuplādēt failus ar MIME tipu "$1".',
'filetype-bad-ie-mime' => 'Nevar augšupielādēt šo failu, jo Internet Explorer to uzskatītu kā "$1", kas ir neatļauts un potenciāli bīstams faila tips.',
'filetype-unwanted-type' => "'''\".\$1\"''' ir nevēlams failu tips.  {{PLURAL:\$3|Ieteicamais faila tips|Ieteicamie failu tipi}} ir \$2.",
'filetype-banned-type' => "'''\".\$1\"''' nav atļautais failu tips.  {{PLURAL:\$3|Atļautais faila tips|Atļautie failu tipi}} ir \$2.",
'filetype-missing' => 'Failam nav paplašinājuma (piem. tāda kā ".jpg").',
'empty-file' => 'Fails, ko Tu iesniedzi, bija tukšs.',
'file-too-large' => 'Fails, ko Tu iesniedzi, bija pārāk liels.',
'filename-tooshort' => 'Faila nosaukums ir pārāk īss.',
'filetype-banned' => 'Šis failu tips ir aizliegts.',
'verification-error' => 'Šis fails neizturēja failu pārbaudi.',
'illegal-filename' => 'Faila nosaukums nav atļauts.',
'overwrite' => 'Pārrakstīt jau esošu failu nav atļauts.',
'unknown-error' => 'Nezināma kļūda.',
'tmp-create-error' => 'Neizdevās izveidot pagaidu failu.',
'tmp-write-error' => 'Kļūda veidojot pagaidu failu.',
'large-file' => 'Ieteicams, lai faili nebūtu lielāki par $1;
šī faila izmērs ir $2.',
'largefileserver' => 'Šis fails ir lielāks nekā serveris ņem pretī.',
'emptyfile' => 'Šķiet, ka tu esi augšuplādējis tukšu failu. Iespējams, faila nosaukumā esi pieļāvis kļūdu. Lūdzu, pārbaudi, vai tiešām tu vēlies augšuplādēt tieši šo failu.',
'windows-nonascii-filename' => 'Šī viki neatbalsta failu nosaukumus ar īpašām rakstzīmēm.',
'fileexists' => 'Fails ar šādu nosaukumu jau pastāv, lūdzu, pārbaudi <strong>[[:$1]]</strong>, ja neesi drošs, ka vēlies to mainīt.
[[$1|thumb]]',
'fileexists-extension' => 'Pastāv fails ar līdzīgu nosaukumu: [[$2|thumb]]
* Augšupielādējamā faila nosaukums: <strong>[[:$1]]</strong>
* Esošā faila nosaukums: <strong>[[:$2]]</strong>
Lūdzu, izvēlieties citu nosaukumu.',
'file-thumbnail-no' => "Faila vārds sākas ar <strong>$1</strong>.
Izskatās, ka šis ir samazināts attēls ''(thumbnail)''.
Ja tev ir šis pats attēls pilnā izmērā, augšuplādē to, ja nav, tad nomaini faila vārdu.",
'fileexists-forbidden' => 'Fails ar šādu nosaukumu jau eksistē un to nevar aizvietot ar jaunu.
Ja tu joprojām gribi augšuplādēt šo failu, tad mēģini vēlreiz, ar citu faila vārdu. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'Fails ir kopija {{PLURAL:$1|šim failam|šiem failiem}}:',
'uploadwarning' => 'Augšupielādes brīdinājums',
'uploadwarning-text' => 'Lūdzu, pārveido zemāk esošo faila aprakstu un mēģini vēlreiz.',
'savefile' => 'Saglabāt failu',
'uploadedimage' => 'augšupielādēja "[[$1]]"',
'overwroteimage' => 'augšupielādēta jauna "[[$1]]" versija',
'uploaddisabled' => 'Augšupielāde atslēgta',
'copyuploaddisabled' => 'URL augšupielādes nav atļautas.',
'uploadfromurl-queued' => 'Tava augšupielāde tika pievienota rindā.',
'uploaddisabledtext' => 'Failu augšupielāde ir atslēgta.',
'php-uploaddisabledtext' => 'Failu augšupielāde ir atslēgta PHP.
Lūdzu, pārbaudi file_uploads uzstādījumu.',
'uploadscripted' => 'Šis fails satur HTML vai skriptu kodu, kuru, interneta pārlūks, var kļūdas pēc, mēģināt interpretēt (ar potenciāli sliktām sekām).',
'uploadvirus' => 'Šis fails satur vīrusu! Sīkāk: $1',
'uploadjava' => 'Fails ir ZIP fails, kas satur Java .class failu.
Java failu augšupielāde nav atļauta, jo tas var radīt iespējas apiet drošības ierobežojumus.',
'upload-source' => 'Augšuplādējamais fails',
'sourcefilename' => 'Faila adrese:',
'sourceurl' => 'Avota URL:',
'destfilename' => 'Mērķa faila nosaukums:',
'upload-maxfilesize' => 'Maksimālais faila izmērs: $1',
'upload-description' => 'Faila apraksts',
'upload-options' => 'Augšupielādes iestatījumi',
'watchthisupload' => 'Uzraudzīt šo failu',
'filewasdeleted' => 'Fails ar šādu nosaukumu jau ir bijis augšuplādēts un pēc tam izdzēsts.
Apskaties $1 pirms turpini šo failu augšuplādēt atkārtoti.',
'filename-bad-prefix' => "Faila vārds failam, kuru tu mēģini augšpulādēt, sākas ar '''\"\$1\"''', kas ir neaprakstošs vārds, kādu parasti uzģenerē digitālais fotoaparāts.
Lūdzu izvēlies aprakstošāku vārdu šim failam.",
'upload-success-subj' => 'Augšupielāde veiksmīga',
'upload-success-msg' => 'Jūsu augšupielādēt no [$2] bija veiksmīga. Tā ir pieejama šeit: [[:{{ns:file}}:$1]]',
'upload-failure-subj' => 'Augšupielādes problēma',
'upload-failure-msg' => 'Radās problēma ar jūsu augšupielādi no [$2]:

$1',
'upload-warning-subj' => 'Augšupielādes brīdinājums',
'upload-warning-msg' => 'Radās problēma ar jūsu augšupielādi no [$2]. Lai labotu šo problēmu, jūs varat atgriezties uz [[Special:Upload/stash/$1|augšupielādes formu]].',

'upload-proto-error' => 'Nepareizs protokols',
'upload-proto-error-text' => 'Attālinātai augšupielādei URL ir jāsākas ar <code>http://</code> vai <code>ftp://</code>.',
'upload-file-error' => 'Iekšējā kļūda',
'upload-file-error-text' => 'Iekšējā kļūda, mēģinot izveidot pagaidu failu uz servera.
Lūdzu, sazinieties ar [[Special:ListUsers/sysop|administratoru.]]',
'upload-misc-error' => 'Nezināma augšupielādes kļūda',
'upload-too-many-redirects' => 'URL sastāvēja pārāk daudz pāradresāciju',
'upload-unknown-size' => 'Nezināms izmērs',
'upload-http-error' => 'HTTP kļūda: $1',

# File backend
'backend-fail-stream' => 'Nevar straumēt failu $1.',
'backend-fail-backup' => 'Nevar dublēt failu $1.',
'backend-fail-notexists' => 'Fails $1 nepastāv.',
'backend-fail-hashes' => 'Neizdevās iegūt failu kontrolsummas salīdzināšanai.',
'backend-fail-notsame' => 'Neidentisks fails jau pastāv $1.',
'backend-fail-delete' => 'Nevar izdzēst failu $1.',
'backend-fail-alreadyexists' => 'Fails $1 jau pastāv.',
'backend-fail-store' => 'Neizdevās saglabāt failu "$1" "$2".',
'backend-fail-copy' => 'Nevar kopēt failu $1 uz $2.',
'backend-fail-move' => 'Nevar pārvietot failu $1 uz $2.',
'backend-fail-opentemp' => 'Nevar atvērt pagaidu failu.',
'backend-fail-writetemp' => 'Nevar ierakstīt pagaidu failu.',
'backend-fail-closetemp' => 'Nevar aizvērt pagaidu failu.',
'backend-fail-read' => 'Nevar lasīt failu $1.',
'backend-fail-create' => 'Nevar izveidot failu $1.',

# ZipDirectoryReader
'zip-wrong-format' => 'Norādītais fails nebija ZIP fails.',

# Special:UploadStash
'uploadstash-errclear' => 'Failu tīrīšana bija neveiksmīga.',
'uploadstash-refresh' => 'Atsvaidzināt failu sarakstu',

# img_auth script messages
'img-auth-accessdenied' => 'Pieeja liegta',
'img-auth-nopathinfo' => 'Trūkst PATH_INFO.
Jūsu serveris nav konfigurēts nodot šo informāciju.
Tas var būt bāzēts uz CGI un neatbalstīt img_auth.
Skatīt https://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-nologinnWL' => 'Jūs neesat iegājis un "$1" nav baltajā sarakstā.',
'img-auth-nofile' => 'Fails "$1" nepastāv.',
'img-auth-isdir' => 'Jūs mēģinājāt piekļūt direktorijai "$1".
Atļauta ir tikai failu piekļuve.',
'img-auth-streaming' => 'Straumē "$1".',

# HTTP errors
'http-invalid-url' => 'Nederīgs URL: $1',
'http-read-error' => 'HTTP nolasīšanas kļūda.',
'http-host-unreachable' => 'URL nevarēja sasniegt.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'URL nevarēja sasniegt',
'upload-curl-error28' => 'Augšupielādes noildze',

'license' => 'Licence:',
'license-header' => 'Licence',
'nolicense' => 'Neviena licence nav izvēlēta',
'license-nopreview' => '(Priekšskatījums nav pieejams)',
'upload_source_url' => '(derīgs, publiski pieejams URL)',
'upload_source_file' => '(fails datorā)',

# Special:ListFiles
'listfiles-summary' => 'Šajā īpašajā lapā ir redzami visi augšuplādētie faili.
Filtrējot pēc lietotāja, tiek rādītas tikai pēdējās lietotāja augšupielādētās faila versijas.',
'listfiles_search_for' => 'Meklēt failu pēc vārda:',
'imgfile' => 'fails',
'listfiles' => 'Attēlu uzskaitījums',
'listfiles_thumb' => 'Sīktēls',
'listfiles_date' => 'Datums',
'listfiles_name' => 'Nosaukums',
'listfiles_user' => 'Lietotājs',
'listfiles_size' => 'Izmērs',
'listfiles_description' => 'Apraksts',
'listfiles_count' => 'Versijas',

# File description page
'file-anchor-link' => 'Attēls',
'filehist' => 'Faila hronoloģija',
'filehist-help' => 'Uzklikšķini uz datums/laiks kolonnā esošās saites, lai apskatītos, kā šis fails izskatījās tad.',
'filehist-deleteall' => 'dzēst visus',
'filehist-deleteone' => 'dzēst',
'filehist-revert' => 'atjaunot',
'filehist-current' => 'tagadējais',
'filehist-datetime' => 'Datums/Laiks',
'filehist-thumb' => 'Attēls',
'filehist-thumbtext' => '$1 versijas sīktēls',
'filehist-nothumb' => 'Nav sīktēla',
'filehist-user' => 'Lietotājs',
'filehist-dimensions' => 'Izmēri',
'filehist-filesize' => 'Faila izmērs',
'filehist-comment' => 'Komentārs',
'filehist-missing' => 'Fails pazudis',
'imagelinks' => 'Faila lietojums',
'linkstoimage' => '{{PLURAL:$1|Šajā lapā ir saite|Šajās $1 lapās ir saites}} uz šo failu:',
'nolinkstoimage' => 'Nevienā lapā nav norāžu uz šo attēlu.',
'morelinkstoimage' => 'Skatīt [[Special:WhatLinksHere/$1|vairāk saites]] uz šo failu.',
'linkstoimage-redirect' => '$1 (faila pāradresācija) $2',
'sharedupload' => 'Šis fails ir augšupielādēts no $1 un ir koplietojams citos projektos.',
'sharedupload-desc-there' => 'Fails ir no $1, tāpēc tas var tikt izmantots citos projektos.
Lūdzu, skatīt [$2 faila apraksta lapu] papildu informācijai.',
'sharedupload-desc-here' => 'Fails ir no $1, tāpēc tas var tikt izmantots citos projektos.
Apraksts ir [$2 faila apraksta lapā], kas ir parādīta zemāk.',
'filepage-nofile' => 'Ar šādu nosaukumu nav neviena faila.',
'filepage-nofile-link' => 'Ar šādu nosaukumu nav neviena faila, bet Jūs varat tādu [$1 augšupielādēt].',
'uploadnewversion-linktext' => 'Augšupielādēt jaunu šī faila versiju',
'shared-repo-from' => 'no $1',
'shared-repo' => 'kopējā krātuve',
'upload-disallowed-here' => 'Šo failu nevar pārrakstīt.',

# File reversion
'filerevert' => 'Atjaunot $1',
'filerevert-legend' => 'Atjaunot failu',
'filerevert-intro' => "Tu atjauno failu '''[[Media:$1|$1]]''' uz [$4 versiju kāda bija $3, $2].",
'filerevert-comment' => 'Iemesls:',
'filerevert-defaultcomment' => 'Atjaunots uz $2, $1 versiju',
'filerevert-submit' => 'Atjaunot',
'filerevert-success' => "Fails '''[[Media:$1|$1]]''' tika atjaunots uz [$4 versiju, kāda tā bija $3, $2].",
'filerevert-badversion' => 'Šajam failam nav iepriekšējās versijas, kas atbilstu norādītajam datumam un laikam.',

# File deletion
'filedelete' => 'Dzēst $1',
'filedelete-legend' => 'Dzēst failu',
'filedelete-intro' => "Tu taisies izdzēst '''[[Media:$1|$1]]''', kopā ar visu tā hronoloģiju.",
'filedelete-intro-old' => "Tu tagad taisies izdzēst faila '''[[Media:$1|$1]]''' versiju, kas tika augšuplādēta [$4 $3, $2].",
'filedelete-comment' => 'Iemesls:',
'filedelete-submit' => 'Izdzēst',
'filedelete-success' => "'''$1''' tika veiksmīgi izdzēsts.",
'filedelete-success-old' => "Faila '''[[Media:$1|$1]]''' versija $3, $2 tika izdzēsta.",
'filedelete-nofile' => "'''$1''' nav atrodams.",
'filedelete-nofile-old' => "Failam '''$1''' nav vecas versijas ar norādītajiem parametriem.",
'filedelete-otherreason' => 'Cits/papildu iemesls:',
'filedelete-reason-otherlist' => 'Cits iemesls',
'filedelete-reason-dropdown' => '*Izplatīti dzēšanas iemesli
** Autortiesību pārkāpums
** Viens tāds jau ir',
'filedelete-edit-reasonlist' => 'Izmainīt dzēšanas iemeslus',
'filedelete-maintenance' => 'Failu dzēšana un atjaunošana uzturēšanas laikā ir atslēgta.',
'filedelete-maintenance-title' => 'Nevar izdzēst failu',

# MIME search
'mimesearch' => 'MIME meklēšana',
'mimetype' => 'MIME tips:',
'download' => 'lejupielādēt',

# Unwatched pages
'unwatchedpages' => 'Neuzraudzītās lapas',

# List redirects
'listredirects' => 'Pāradresāciju uzskaitījums',

# Unused templates
'unusedtemplates' => 'Neizmantotās veidnes',
'unusedtemplatestext' => 'Šajā lapā ir uzskaitītas visas veidnes, kas nav iekļautas nevienā citā lapā. Ja tās paredzēts dzēst, pirms dzēšanas jāpārbauda citu veidu saites uz dzēšamajām veidnēm.',
'unusedtemplateswlh' => 'citas saites',

# Random page
'randompage' => 'Nejauša lapa',

# Random redirect
'randomredirect' => 'Nejauša pāradresācijas lapa',

# Statistics
'statistics' => 'Statistika',
'statistics-header-pages' => 'Lapu statistika',
'statistics-header-edits' => 'Izmaiņu statistika',
'statistics-header-views' => 'Apskatīt statistiku',
'statistics-header-users' => 'Statistika par lietotājiem',
'statistics-header-hooks' => 'Cita statistika',
'statistics-articles' => 'Satura lapas',
'statistics-pages' => 'Lapas',
'statistics-pages-desc' => 'Visas šajā wiki esošās lapas, ieskaitot diskusiju lapas, pāradresācijas, utt.',
'statistics-files' => 'Augšuplādētie faili',
'statistics-edits' => 'Lapu izmaiņas kopš {{grammar:ģenitīvs{{SITENAME}}}} izveidošanas',
'statistics-edits-average' => 'Vidējais izmaiņu skaits uz lapu',
'statistics-views-total' => 'Skatījumi kopā',
'statistics-views-peredit' => 'Skatījumu skaits uz labojumu',
'statistics-users' => 'Reģistrēti lietotāji',
'statistics-users-active' => 'Aktīvi lietotāji',
'statistics-users-active-desc' => 'Lietotāji, kas ir veikuši jebkādu darbību {{PLURAL:$1|iepriekšējā dienā|iepriekšējās $1 dienās}}',
'statistics-mostpopular' => 'Visvairāk skatītās lapas',

'disambiguations' => 'Lapas, kuras norāda uz nozīmju atdalīšanas lapām',
'disambiguationspage' => 'Template:Disambig',
'disambiguations-text' => "Šeit esošajās lapās ir saite uz '''nozīmju atdalīšanas lapu'''.
Šīs saites vajadzētu izlabot, lai tās vestu tieši uz attiecīgo lapu.<br />
Lapu uzskata par nozīmju atdalīšanas lapu, ja tā satur veidni, uz kuru ir saite no [[MediaWiki:Disambiguationspage]].",

'doubleredirects' => 'Divkāršas pāradresācijas lapas',
'doubleredirectstext' => 'Šajā lapā ir uzskaitītas pāradresācijas lapas, kuras pāradresē uz citām pāradresācijas lapām.
Katrā rindiņā ir saites uz pirmo un otro pāradresācijas lapu, kā arī pirmā rindiņa no otrās pāradresācijas lapas teksta, kas parasti ir faktiskā "gala" lapa, uz kuru vajadzētu būt saitei pirmajā lapā.
<del>Nosvītrotie</del> ieraksti jau ir tikuši salaboti.',
'double-redirect-fixed-move' => '[[$1]] bija ticis pārvietots, tas tagad ir pāradresācija uz [[$2]]',
'double-redirect-fixed-maintenance' => 'Labota dubultā pāradresācija no [[$1]] uz [[$2]].',
'double-redirect-fixer' => 'Pāradresāciju labotājs',

'brokenredirects' => 'Kļūdainas pāradresācijas',
'brokenredirectstext' => 'Šīs ir pāradresācijas lapas uz neesošām lapām:',
'brokenredirects-edit' => 'labot',
'brokenredirects-delete' => 'dzēst',

'withoutinterwiki' => 'Lapas bez interwiki',
'withoutinterwiki-summary' => "Šajās lapās nav saišu uz citu valodu projektiem (''interwiki''):",
'withoutinterwiki-legend' => 'Prefikss',
'withoutinterwiki-submit' => 'Rādīt',

'fewestrevisions' => 'Lapas, kurām ir vismazāk veco versiju',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|baits|baitu}}',
'ncategories' => '$1 {{PLURAL:$1|kategorija|kategorijas}}',
'nlinks' => '$1 {{PLURAL:$1|saite|saites}}',
'nmembers' => '$1 {{PLURAL:$1|lapa|lapas}}',
'nrevisions' => '$1 {{PLURAL:$1|versija|versijas}}',
'nviews' => 'skatīta $1 {{PLURAL:$1|reizi|reizes}}',
'nimagelinks' => 'Izmantots $1 {{PLURAL:$1|lapā|lapās}}',
'ntransclusions' => 'izmantots $1 {{PLURAL:$1|lapā|lapās}}',
'specialpage-empty' => 'Šim ziņojumam nav rezultātu.',
'lonelypages' => 'Lapas bez saitēm uz tām',
'uncategorizedpages' => 'Nekategorizētās lapas',
'uncategorizedcategories' => 'Nekategorizētās kategorijas',
'uncategorizedimages' => 'Nekategorizētie attēli',
'uncategorizedtemplates' => 'Nekategorizētās veidnes',
'unusedcategories' => 'Neizmantotas kategorijas',
'unusedimages' => 'Neizmantoti attēli',
'popularpages' => 'Populārākās lapas',
'wantedcategories' => 'Sarkanas kategorijas',
'wantedpages' => 'Pieprasītās lapas',
'wantedfiles' => 'Vajadzīgie faili',
'wantedtemplates' => 'Vajadzīgās veidnes',
'mostlinked' => 'Lapas, uz kurām ir visvairāk norāžu',
'mostlinkedcategories' => 'Kategorijas, uz kurām ir visvairāk saišu',
'mostlinkedtemplates' => 'Visvairāk izmantotās veidnes',
'mostcategories' => 'Raksti ar visvairāk kategorijām',
'mostimages' => 'Attēli, uz kuriem ir visvairāk saišu',
'mostrevisions' => 'Raksti, kuriem ir visvairāk iepriekšēju versiju',
'prefixindex' => 'Meklēt pēc virsraksta pirmajiem burtiem',
'prefixindex-namespace' => 'Visas lapas ar prefiksu ($1 vārdtelpa)',
'shortpages' => 'Īsākās lapas',
'longpages' => 'Garākās lapas',
'deadendpages' => 'Lapas bez izejošām saitēm',
'protectedpages' => 'Aizsargātās lapas',
'protectedpages-indef' => 'Tikai bezgalīgas aizsardzības',
'protectedpages-cascade' => 'Tikai kaskādes aizsardzības',
'protectedtitles' => 'Aizsargātie nosaukumi',
'protectedtitlestext' => 'Lapas ar šādiem nosaukumiem ir aizsargātas pret lapas izveidošanu',
'protectedtitlesempty' => 'Pagaidām nevienas lapas nosaukums nav aizsargāts ar šiem paraametriem.',
'listusers' => 'Lietotāju uzskaitījums',
'listusers-editsonly' => 'Rādīt tikai lietotājus, kas ir izdarījuši kādas izmaiņas',
'listusers-creationsort' => 'Kārtot pēc izveidošanas datuma',
'usereditcount' => '$1 {{PLURAL:$1|izmaiņa|izmaiņas}}',
'usercreated' => '{{GENDER:$3|Izveidoja}} $1 plkst. $2',
'newpages' => 'Jaunas lapas',
'newpages-username' => 'Lietotājs:',
'ancientpages' => 'Vecākās lapas',
'move' => 'Pārvietot',
'movethispage' => 'Pārvietot šo lapu',
'unusedcategoriestext' => 'Šīs kategorijas eksistē, tomēr nevienā rakstā vai kategorijās tās nav izmantotas.',
'notargettitle' => 'Bez mērķa',
'nopagetitle' => 'Nav tādas mērķa lapas',
'nopagetext' => 'Mērķa lapa, ko Jūs norādījāt, nepastāv.',
'pager-newer-n' => '{{PLURAL:$1|1 jaunāku|$1 jaunākas}}',
'pager-older-n' => '{{PLURAL:$1|1 vecāku|$1 vecākas}}',
'querypage-disabled' => 'Šī īpašā lapā ir atspējota veiktspējas iemeslu dēļ.',

# Book sources
'booksources' => 'Grāmatu avoti',
'booksources-search-legend' => 'Meklēt grāmatu avotus',
'booksources-go' => 'Meklēt',

# Special:Log
'specialloguserlabel' => 'Izpildītājs:',
'speciallogtitlelabel' => 'Mērķis (nosaukums vai lietotājs):',
'log' => 'Reģistri',
'all-logs-page' => 'Visi publiski pieejamie reģistri',
'alllogstext' => 'Visi pieejamie {{grammar:akuzatīvs{{SITENAME}}}} reģistri.
Tu vari sašaurināt aplūkojamo reģistru, izvēloties reģistra veidu, lietotāja vārdu vai reģistrēto lapu. Visi teksta lauki izšķir lielos un mazos burtus.',
'logempty' => 'Reģistrā nav atbilstošu ierakstu.',
'log-title-wildcard' => 'Meklēt virsrakstus, kas sākas ar šo tekstu',

# Special:AllPages
'allpages' => 'Visas lapas',
'alphaindexline' => 'no $1 līdz $2',
'nextpage' => 'Nākamā lapa ($1)',
'prevpage' => 'Iepriekšējā lapa ($1)',
'allpagesfrom' => 'Parādīt lapas sākot ar:',
'allpagesto' => 'Parādīt lapas līdz:',
'allarticles' => 'Visi raksti',
'allinnamespace' => 'Visas lapas ($1 vārdtelpa)',
'allnotinnamespace' => 'Visas lapas (nav $1 vārdtelpa)',
'allpagesprev' => 'Iepriekšējās',
'allpagesnext' => 'Nākamās',
'allpagessubmit' => 'Aiziet!',
'allpagesprefix' => 'Parādīt lapas ar šādu virsraksta sākumu:',
'allpages-bad-ns' => '{{SITENAME}} nav vārdkopas "$1".',
'allpages-hide-redirects' => 'Paslēpt pāradresācijas',

# SpecialCachedPage
'cachedspecial-refresh-now' => 'Skatīt jaunāko.',

# Special:Categories
'categories' => 'Kategorijas',
'categoriespagetext' => "{{PLURAL:$1|Šī kategorija|Šīs kategorijas}} satur lapas vai failus.
Šeit nav parādītas [[Special:UnusedCategories|neizmantotās kategorijas]].
Skatīt arī [[Special:WantedCategories|''sarkanās'' kategorijas]].",
'categoriesfrom' => 'Parādīt kategorijas sākot ar:',
'special-categories-sort-count' => 'kārtot pēc skaita',
'special-categories-sort-abc' => 'kārtot alfabētiskā secībā',

# Special:DeletedContributions
'deletedcontributions' => 'Izdzēstais lietotāju devums',
'deletedcontributions-title' => 'Izdzēstais lietotāju devums',
'sp-deletedcontributions-contribs' => 'devums',

# Special:LinkSearch
'linksearch' => 'Ārējo saišu meklēšana',
'linksearch-pat' => 'Meklēt:',
'linksearch-ns' => 'Vārdtelpas:',
'linksearch-ok' => 'Meklēt',
'linksearch-text' => 'Atbalstītie protokoli: <code>$1</code>',
'linksearch-line' => '$1 ir izveidota saite no $2',

# Special:ListUsers
'listusersfrom' => 'Parādīt lietotājus sākot ar:',
'listusers-submit' => 'Parādīt',
'listusers-noresult' => 'Neviens lietotājs nav atrasts.',
'listusers-blocked' => '(bloķēts)',

# Special:ActiveUsers
'activeusers' => 'Aktīvo lietotāju saraksts',
'activeusers-intro' => 'Šis ir lietotāju saraksts, kas veikuši kādu darbību {{PLURAL:daudzskaitlī:$1|pēdējā|pēdējās}} $1 {{PLURAL:daudzskaitlī:$1|dienā|dienās}}.',
'activeusers-from' => 'Parādīt lietotājus sākot ar:',
'activeusers-hidebots' => 'Paslēpt botus',
'activeusers-hidesysops' => 'Paslēpt administratorus',
'activeusers-noresult' => 'Neviens lietotājs nav atrasts.',

# Special:Log/newusers
'newuserlogpage' => 'Jauno lietotāju reģistrs',
'newuserlogpagetext' => 'Jauno lietotājvārdu reģistrs.',

# Special:ListGroupRights
'listgrouprights' => 'Lietotāju grupu tiesības',
'listgrouprights-summary' => 'Šis ir šajā wiki definēto lietotāju grupu uskaitījums, kopā ar tām atbilstošajām piekļuves tiesībām.
Papildu informāciju par katru individuālu piekļuves tiesību veidu, iespējams, var atrast [[{{MediaWiki:Listgrouprights-helppage}}|šeit]].',
'listgrouprights-group' => 'Grupa',
'listgrouprights-rights' => 'Tiesības',
'listgrouprights-helppage' => 'Help:Grupu tiesības',
'listgrouprights-members' => '(dalībnieku saraksts)',
'listgrouprights-addgroup' => 'Pievienot {{PLURAL:$2|grupu|grupas}}: $1',
'listgrouprights-removegroup' => 'Noņemt {{PLURAL:$2|grupu|grupas}}: $1',
'listgrouprights-addgroup-all' => 'Pievienot visas grupas',
'listgrouprights-removegroup-all' => 'Noņemt visas grupas',
'listgrouprights-addgroup-self-all' => 'Pievienot visas grupas savam kontam',
'listgrouprights-removegroup-self-all' => 'Noņemt visas grupas no sava konta',

# E-mail user
'mailnologin' => 'Nav adreses, uz kuru sūtīt',
'mailnologintext' => 'Tev jābūt [[Special:UserLogin|iegājušam]], kā arī tev jābūt [[Special:Preferences|norādītai]] derīgai e-pasta adresei, lai sūtītu e-pastu citiem lietotājiem.',
'emailuser' => 'Sūtīt e-pastu šim lietotājam',
'emailpage' => 'Sūtīt e-pastu lietotājam',
'emailpagetext' => 'Ar šo veidni ir iespējams nosūtīt e-pastu šim lietotājam.
Tā e-pasta adrese, kuru tu esi norādījis [[Special:Preferences|savā izvēļu lapā]], parādīsies e-pasta "From" lauciņā, tādejādi saņēmējs varēs tev atbildēt.',
'usermailererror' => 'Pasta objekts atgrieza kļūdu:',
'defemailsubject' => '{{SITENAME}} e-pasts no lietotāja "$1"',
'usermaildisabled' => 'Lietotāja e-pasts atslēgts',
'usermaildisabledtext' => 'Jūs nevarat sūtīt e-pastu citiem lietotājiem šajā viki',
'noemailtitle' => 'Nav e-pasta adreses',
'noemailtext' => 'Šis lietotājs nav norādījis derīgu e-pasta adresi.',
'nowikiemailtitle' => 'E-pasts nav atļauts',
'nowikiemailtext' => 'Šis lietotājs ir vēlējies nesaņemt e-pastu no citiem lietotājiem.',
'emailnotarget' => 'Neeksistējošs vai nederīgs saņēmēja lietotājvārds.',
'emailtarget' => 'Ievadiet saņēmēja lietotājvārdu',
'emailusername' => 'Lietotājvārds:',
'emailusernamesubmit' => 'Iesniegt',
'email-legend' => 'Sūtīt e-pastu citam {{SITENAME}} lietotājam',
'emailfrom' => 'No:',
'emailto' => 'Kam:',
'emailsubject' => 'Temats:',
'emailmessage' => 'Vēstījums:',
'emailsend' => 'Nosūtīt',
'emailccme' => 'Atsūtīt man uz e-pastu mana ziņojuma kopiju.',
'emailsent' => 'E-pasts nosūtīts',
'emailsenttext' => 'Tavs e-pasts ir nosūtīts.',
'emailuserfooter' => 'Šis e-pasts ir lietotāja $1 sūtīts lietotājam $2, izmantojot "Sūtīt e-pastu šim lietotājam" funkciju {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Atstāt sistēmas ziņojumu.',
'usermessage-editor' => 'Sistēmas ziņotājs',

# Watchlist
'watchlist' => 'Mani uzraugāmie raksti',
'mywatchlist' => 'Uzraugāmie raksti',
'nowatchlist' => 'Tavā uzraugāmo rakstu sarakstā nav neviena raksta.',
'watchlistanontext' => 'Lūdzu $1, lai apskatītu vai labotu savu uzraugāmo rakstu saraksta saturu.',
'watchnologin' => 'Neesi iegājis',
'watchnologintext' => 'Tev ir [[Special:UserLogin|jāieiet]], lai mainītu uzraugāmo lapu sarakstu.',
'addwatch' => 'Pievienot uzraugāmo lapu sarakstam',
'addedwatchtext' => "Lapa \"[[:\$1]]\" ir pievienota [[Special:Watchlist|tevis uzraudzītajām lapām]], kur tiks parādītas izmaiņas, kas izdarītas šajā lapā vai šīs lapas diskusiju lapā, kā arī šī lapa tiks iezīmēta '''pustrekna''' [[Special:RecentChanges|pēdējo izmaiņu lapā]], lai to būtu vieglāk pamanīt.

Ja vēlāk pārdomāsi un nevēlēsies vairs uzraudzīt šo lapu, klikšķini uz saites '''neuzraudzīt''' rīku joslā.",
'removewatch' => 'Izņemt no uzraugāmo lapu saraksta',
'removedwatchtext' => 'Lapa "[[:$1]]" ir izņemta no tava [[Special:Watchlist|uzraugāmo lapu saraksta]].',
'watch' => 'Uzraudzīt',
'watchthispage' => 'Uzraudzīt šo lapu',
'unwatch' => 'Neuzraudzīt',
'unwatchthispage' => 'Pārtraukt uzraudzīšanu',
'notanarticle' => 'Nav satura lapa',
'notvisiblerev' => 'Cita lietotāja pēdējā versija ir izdzēsta',
'watchnochange' => 'Neviena no tevis uzraudzītajām lapām nav mainīta parādītajā laika posmā.',
'watchlist-details' => '(Tu uzraugi $1 {{PLURAL:$1|lapu|lapas}}, neieskaitot diskusiju lapas.)',
'wlheader-enotif' => 'E-pasta paziņojumi ir ieslēgti.',
'wlheader-showupdated' => "* Lapas, kuras ir tikušas izmainītas, kopš tu tās pēdējoreiz apskatījies, te rādās ar '''pustrekniem''' burtiem",
'watchlistcontains' => 'Tavā uzraugāmo lapu sarakstā ir $1 {{PLURAL:$1|lapa|lapas}}.',
'iteminvalidname' => "Problēma ar '$1' vienību, nederīgs nosaukums...",
'wlshowlast' => 'Parādīt izmaiņas pēdējo $1 stundu laikā vai $2 dienu laikā, vai arī $3.',
'watchlist-options' => 'Uzraugāmo rakstu saraksta opcijas',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Uzrauga...',
'unwatching' => 'Neuzrauga...',

'enotif_mailer' => '{{SITENAME}} paziņojumu izsūtīšana',
'enotif_reset' => 'Atzīmēt visas lapas kā apskatītas',
'enotif_newpagetext' => 'Šī ir jauna lapa.',
'enotif_impersonal_salutation' => '{{SITENAME}} lietotājs',
'changed' => 'izmainīja',
'created' => 'izveidoja',
'enotif_subject' => '{{grammar:ģenitīvs|{{SITENAME}}}} lapu $PAGETITLE $CHANGEDORCREATED lietotājs $PAGEEDITOR',
'enotif_lastvisited' => '$1 lai apskatītos visas izmaiņas kopš tava pēdējā apmeklējuma.',
'enotif_lastdiff' => '$1 lai apskatītos šo izmaiņu.',
'enotif_anon_editor' => 'anonīms lietotājs $1',
'enotif_body' => '$WATCHINGUSERNAME,


{{grammar:ģenitīvs|{{SITENAME}}}} lapu $PAGETITLE $CHANGEDORCREATED $PAGEEDITOR, $PAGEEDITDATE, pašreizējā versja ir $PAGETITLE_URL.

$NEWPAGE

Izmaiņu kopsavilkums bija: $PAGESUMMARY $PAGEMINOREDIT

Sazināties ar attiecīgo lietotāju:
e-pasts: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Ja šo uzraugāmo lapu izmainīs vēl, turpmāku paziņojumu par to nebūs, kamēr tu to neatvērsi.
Tu arī vari atstatīt visu uzraugāmo lapu paziņojumu statusus uzraugāmo lapu sarakstā.

             {{grammar:ģenitīvs|{{SITENAME}}}} paziņojumu sistēma

--
Lai izmainītu uzraugāmo lapu saraksta uzstādījumus:
{{canonicalurl:{{#special:EditWatchlist}}}}

Lai dzēstu lapu no uzraugāmo lapu saraksta:
$UNWATCHURL

Papildinformācija:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage' => 'Dzēst lapu',
'confirm' => 'Apstiprināt',
'excontent' => "lapas saturs bija: '$1'",
'excontentauthor' => 'saturs bija: "$1" (vienīgais autors: [[Special:Contributions/$2|$2]])',
'exbeforeblank' => "lapas saturs pirms satura dzēšanas bija šāds: '$1'",
'exblank' => 'lapa bija tukša',
'delete-confirm' => 'Dzēst "$1"',
'delete-legend' => 'Dzēšana',
'historywarning' => "'''Brīdinājums:''' Lapai, ko tu gatavojies dzēst, ir vēsture ar aptuveni $1 {{PLURAL:$1|versiju|versijām}}:",
'confirmdeletetext' => 'Tu tūlīt no datubāzes dzēsīsi lapu vai attēlu, kā arī to iepriekšējās versijas. Lūdzu, apstiprini, ka tu tiešām to vēlies darīt, ka tu apzinies sekas un ka tu to dari saskaņā ar [[{{MediaWiki:Policy-url}}|vadlīnijām]].',
'actioncomplete' => 'Darbība pabeigta',
'actionfailed' => 'Darbība neizdevās',
'deletedtext' => 'Lapa "$1" ir izdzēsta.
Šeit var apskatīties pēdējos izdzēstos: "$2".',
'dellogpage' => 'Dzēšanas reģistrs',
'dellogpagetext' => 'Šajā lapā ir pēdējo dzēsto lapu saraksts.',
'deletionlog' => 'dzēšanas reģistrs',
'reverted' => 'Atjaunots uz iepriekšējo versiju',
'deletecomment' => 'Iemesls:',
'deleteotherreason' => 'Cits/papildu iemesls:',
'deletereasonotherlist' => 'Cits iemesls',
'deletereason-dropdown' => '*Izplatīti dzēšanas iemesli
** Autora pieprsījums
** Autortiesību pārkāpums
** Vandālisms',
'delete-edit-reasonlist' => 'Izmainīt dzēšanas iemeslus',
'delete-toobig' => 'Šai lapai ir liela izmaiņu hronoloģija, vairāk nekā $1 {{PLURAL:$1|versija|versijas}}.
Šādu lapu dzēšana ir atslēgta, lai novērstu nejaušus traucējumus {{grammar:lokatīvs|{{SITENAME}}}}.',

# Rollback
'rollback' => 'Novērst labojumus',
'rollback_short' => 'Novērst',
'rollbacklink' => 'novērst',
'rollbackfailed' => 'Novēršana neizdevās',
'cantrollback' => 'Nav iespējams novērst labojumu; iepriekšējais labotājs ir vienīgais lapas autors.',
'alreadyrolled' => 'Nav iespējams novērst pēdējās izmaiņas, ko lapā [[:$1]] saglabāja [[User:$2|$2]] ([[User talk:$2|Diskusija]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]). Kāds cits jau ir rediģējis šo lapu vai novērsis izmaiņas.

Pēdējās izmaiņas saglabāja [[User:$3|$3]] ([[User talk:$3|diskusija]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment' => "Attiecīgās izmaiņas kopsavilkums bija: \"''\$1''\".",
'revertpage' => 'Novērsu izmaiņas, ko izdarīja [[Special:Contributions/$2|$2]] ([[User talk:$2|Diskusija]]), atjaunoju versiju, ko saglabāja [[User:$1|$1]]',
'revertpage-nouser' => 'Novērsu (lietotājvārds slēpts) izmaiņas, atjaunoju [[User:$1|$1]] versiju.',
'rollback-success' => 'Tika novērstas $1 izdarītās izmaiņas;
un tika atjaunota iepriekšējā versija, kuru bija izveidojis $2.',

# Edit tokens
'sessionfailure-title' => 'sesijas kļūda',
'sessionfailure' => "Ir radusies problēma ar sesijas autentifikāciju;
šī darbība ir atcelta, lai novērstu lietotājvārda iespējami ļaunprātīgu izmantošanu.
Lūdzu, spied \"''back''\" un atjaunini iepriekšējo lapu. Tad mēģini vēlreiz.",

# Protect
'protectlogpage' => 'Aizsargāšanas reģistrs',
'protectedarticle' => 'aizsargāja "[[$1]]"',
'modifiedarticleprotection' => 'izmainīja aizsardzības līmeni "[[$1]]"',
'unprotectedarticle' => 'atcēla "[[$1]]" aizsardzību',
'movedarticleprotection' => 'pārcēla aizsardzību no "[[$2]]" uz "[[$1]]"',
'protect-title' => 'Izmainīt "$1" aizsargāšanas līmeni?',
'protect-title-notallowed' => 'Apskatīt "$1" aizsrdzības līmeni',
'prot_1movedto2' => '"[[$1]]" pārdēvēju par "[[$2]]"',
'protect-legend' => 'Apstiprināt aizsargāšanu',
'protectcomment' => 'Iemesls:',
'protectexpiry' => 'Beidzas:',
'protect_expiry_invalid' => 'Beigu termiņš ir nederīgs.',
'protect_expiry_old' => 'Beigu termiņs ir pagātnē.',
'protect-text' => "Šeit var apskatīties un izmainīt lapas '''$1''' aizsardzības līmeni.",
'protect-locked-access' => "Jūsu kontam nav atļaujas mainīt lapas aizsardzības pakāpi.
Pašreizējie lapas '''$1''' iestatījumi ir:",
'protect-cascadeon' => 'Šī lapa pašlaik ir aizsargāta, jo tā ir iekļauta {{PLURAL:$1|sekojošā lapā|sekojošās lapās}} (mainot šīs lapas aizsardzības līmeni aizsardzība netiks noņemta):',
'protect-default' => 'Atļaut visiem lietotājiem',
'protect-fallback' => 'Nepieciešama atļauja "$1"',
'protect-level-autoconfirmed' => 'Bloķēt jauniem un nereģistrētiem lietotājiem',
'protect-level-sysop' => 'Tikai administratoriem',
'protect-summary-cascade' => 'kaskāde',
'protect-expiring' => 'līdz $1 (UTC)',
'protect-expiring-local' => 'beidzas $1',
'protect-expiry-indefinite' => 'bezgalīgs',
'protect-cascade' => "Aizsargāt šajā lapā iekļautās lapas (veidnes) ''(cascading protection)''",
'protect-cantedit' => 'Tu nevari izmainīt šīs lapas aizsardzības līmeņus, tāpēc, ka tur nevari izmainīt šo lapu.',
'protect-othertime' => 'Cits laiks:',
'protect-othertime-op' => 'cits laiks',
'protect-existing-expiry' => 'Esošais beigu termiņš: $3, $2',
'protect-otherreason' => 'Cits/papildu iemesls:',
'protect-otherreason-op' => 'Cits iemesls',
'protect-dropdown' => '*Izplatīti aizsargāšanas iemesli
** Pārmērīgs vandālisms
** Pārmērīgs spams
** Neproduktīvi izmaiņu kari
** Bieži apskatīta lapa',
'protect-edit-reasonlist' => 'Izmainīt aizsargāšanas iemeslus',
'protect-expiry-options' => '1 stunda:1 hour,1 diena:1 day,1 nedēļa:1 week,2 nedēļas:2 weeks,1 mēnesis:1 month,3 mēneši:3 months,6 mēneši:6 months,1 gads:1 year,uz nenoteiktu laiku:infinite',
'restriction-type' => 'Atļauja:',
'restriction-level' => 'Aizsardzības līmenis:',
'minimum-size' => 'Mazākais izmērs',
'maximum-size' => 'Lielākais izmērs:',
'pagesize' => '(baiti)',

# Restrictions (nouns)
'restriction-edit' => 'Izmainīt',
'restriction-move' => 'Pārvietot',
'restriction-create' => 'Izveidot',
'restriction-upload' => 'Augšuplādēt',

# Restriction levels
'restriction-level-sysop' => 'pilnā aizsardzība',
'restriction-level-autoconfirmed' => 'daļējā aizsardzība',
'restriction-level-all' => 'jebkurš līmenis',

# Undelete
'undelete' => 'Atjaunot dzēstu lapu',
'undeletepage' => 'Skatīt un atjaunot dzēstās lapas',
'undeletepagetitle' => "'''Šeit ir [[:$1|$1]] izdzēstās versijas'''.",
'viewdeletedpage' => 'Skatīt izdzēstās lapas',
'undeletepagetext' => '{{PLURAL:$1|Šī lapa ir dzēsta, bet ir saglabāta arhīvā. To ir iespējams atjaunot|Šīs $1 lapas ir dzēstas, bet ir saglabātas arhīvā. Tās ir iespējams atjaunot}}, bet ņemiet vērā, ka arhīvs reizēm tiek tīrīts.',
'undelete-fieldset-title' => 'Atjaunot versijas',
'undeleteextrahelp' => "Lai atjaunotu visu lapu, atstāj visus ķekšus (pie \"Lapas hronoloģija\") neieķeksētus uz uzspied uz '''''Atjaunot!'''''.
Lai atjaunotu tikai noteiktas versijas, ieķeksē vajadzīgās versijas un spied uz '''''Atjaunot!'''''. Uzspiešana uz '''''Notīrīt''''' notīrīs komentāru lauku un visus keķšus.",
'undeleterevisions' => '$1 {{PLURAL:$1|versija|versijas}} {{PLURAL:$1|arhivēta|arhivētas}}',
'undeletehistory' => 'Ja tu atjauno lapu, visas versijas tiks atjaunotas tās hronoloģijā.
Ja pēc dzēšanas ir izveidota jauna lapa ar tādu pašu nosaukumu, atjaunotās versijas tiks ievietotas lapas hronoloģijā attiecīgā secībā un konkrētās lapas pašreizējā versija netiks automātiski nomainīta.',
'undeleterevdel' => 'Atjaunošana nenotiks, ja tas izraisīs jaunākās versijas izdzēšanu.
Šādos gadījumos ir vai nu jāizņem ķeksis no jaunākās versijas, vai arī jāatslēpj jaunākā versija.',
'undeletehistorynoadmin' => 'Šī lapa ir tikusi izdzēsta.
Dzēšanas iemesls ir redzams apakšā, kopsavilkumā, kopā ar informāciju par lietotājiem, kas bija rediģējuši šo lapu pirs tās izdzēšanas.
Šo izdzēsto versiju teksts ir pieejams tikai administratoriem.',
'undelete-revision' => 'Lapas $1 izdzēstā versija (kāda tā bija $4, $5) (autors $3):',
'undeleterevision-missing' => 'Nederīga vai neeksistējoša versija.
Vai nu tu šeit esi nonācis lietojot kļūdainu saiti, vai arī šī versija jau ir tikusi atjaunota, vai arī tā ir izdzēsta pavisam.',
'undelete-nodiff' => 'Netika atrastas iepriekšējās versijas.',
'undeletebtn' => 'Atjaunot!',
'undeletelink' => 'apskatīt/atjaunot',
'undeleteviewlink' => 'skatīt',
'undeletereset' => 'Notīrīt',
'undeleteinvert' => 'Izvēlēties pretēji',
'undeletecomment' => 'Iemesls:',
'undeletedrevisions' => '$1 {{PLURAL:$1|versija|versijas}} {{PLURAL:$1|atjaunota|atjaunotas}}',
'undeletedrevisions-files' => '{{PLURAL:$1|1 versija|$1 versijas}} un {{PLURAL:$2|1 fails|$2 faili}} atjaunoti',
'undeletedfiles' => '{{PLURAL:$1|1 fails atjaunots|$1 faili atjaunoti}}',
'cannotundelete' => 'Atjaunošana neizdevās;
kāds cits iespējams to ir atjaunojis ātrāk.',
'undeletedpage' => "'''$1 tika atjaunots'''

[[Special:Log/delete|Dzēšanas reģistrā]] ir informācija par pēdējām dzēšanām un atjaunošanām.",
'undelete-header' => 'Nesen dzēstajām lapām skatīt [[Special:Log/delete|dzēšanas reģistru]].',
'undelete-search-title' => 'Meklēt izdzēstās lapas',
'undelete-search-box' => 'Meklēt izdzēstās lapas',
'undelete-search-prefix' => 'Rādīt lapas sākot ar:',
'undelete-search-submit' => 'Meklēt',
'undelete-no-results' => 'Dzēšanas arhīvā netika atrasta neviena atbilstoša lapa.',
'undelete-cleanup-error' => 'Kļūda dzēšot neizmantotu arhīva failu "$1".',
'undelete-error-short' => 'Kļūda dzēšot failu: $1',
'undelete-error-long' => 'Dzēšot failu radās kļūdas:

$1',
'undelete-show-file-submit' => 'Jā',

# Namespace form on various pages
'namespace' => 'Vārdtelpa:',
'invert' => 'Izvēlēties pretēji',
'blanknamespace' => '(Pamatlapa)',

# Contributions
'contributions' => 'Lietotāja devums',
'contributions-title' => 'Lietotāja $1 devums',
'mycontris' => 'Devums',
'contribsub2' => 'Lietotājs: $1 ($2)',
'nocontribs' => 'Netika atrastas izmaiņas, kas atbilstu šiem kritērijiem.',
'uctop' => '(pēdējā izmaiņa)',
'month' => 'No mēneša (un senāki):',
'year' => 'No gada (un senāki):',

'sp-contributions-newbies' => 'Rādīt jauno lietotāju devumu',
'sp-contributions-newbies-sub' => 'Jaunie lietotāji',
'sp-contributions-blocklog' => 'Bloķēšanas reģistrs',
'sp-contributions-deleted' => 'Izdzēstais lietotāju devums',
'sp-contributions-uploads' => 'augšupielādes',
'sp-contributions-logs' => 'reģistri',
'sp-contributions-talk' => 'diskusija',
'sp-contributions-userrights' => 'Lietotāju tiesību pārvaldība',
'sp-contributions-blocked-notice' => 'Šis lietotājs pašlaik ir nobloķēts.
Pēdējais bloķēšanas reģistra ieraksts ir apskatāms zemāk:',
'sp-contributions-blocked-notice-anon' => 'Šī IP adrese pašlaik ir nobloķēta.
Pēdējais bloķēšanas reģistra ieraksts ir apskatāms zemāk:',
'sp-contributions-search' => 'Meklēt lietotāju veiktās izmaiņas',
'sp-contributions-username' => 'IP adrese vai lietotāja vārds:',
'sp-contributions-toponly' => 'Rādīt tikai labojumus, kuri ir jaunākās versijas',
'sp-contributions-submit' => 'Meklēt',

# What links here
'whatlinkshere' => 'Norādes uz šo rakstu',
'whatlinkshere-title' => 'Lapas, kurās ir saites uz lapu "$1"',
'whatlinkshere-page' => 'Lapa:',
'linkshere' => "Šajās lapās ir norādes uz lapu '''[[:$1]]''':",
'nolinkshere' => "Nevienā lapā nav norāžu uz lapu '''[[:$1]]'''.",
'nolinkshere-ns' => "Neviena lapa nenorāda uz '''[[:$1]]''' izvēlētajā vārdtelpā.",
'isredirect' => 'pāradresācijas lapa',
'istemplate' => 'izsaukts',
'isimage' => 'faila saite',
'whatlinkshere-prev' => '{{PLURAL:$1|iepriekšējo|iepriekšējos $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|nākamo|nākamos $1}}',
'whatlinkshere-links' => '← saites',
'whatlinkshere-hideredirs' => '$1 pāradresācijas',
'whatlinkshere-hidetrans' => '$1 lapas, kurās šī lapa izmantota kā veidne',
'whatlinkshere-hidelinks' => '$1 saites',
'whatlinkshere-hideimages' => '$1 failu saites',
'whatlinkshere-filters' => 'Filtri',

# Block/unblock
'autoblockid' => 'Autobloķēšana #$1',
'block' => 'Bloķēt lietotāju',
'unblock' => 'Atbloķēt lietotāju',
'blockip' => 'Bloķēt lietotāju',
'blockip-title' => 'Bloķēt lietotāju',
'blockip-legend' => 'Bloķēt lietotāju',
'blockiptext' => 'Šo veidni izmanto, lai bloķētu kādas IP adreses vai lietotājvārda piekļuvi wiki lapu saglabāšanai. Dari to tikai, lai novērstu vandālismu atbilstoši [[{{MediaWiki:Policy-url}}|noteikumiem]].
Norādi konkrētu iemeslu (piemēram, linkus uz vandalizētajām lapām).',
'ipadressorusername' => 'IP adrese vai lietotājvārds',
'ipbexpiry' => 'Termiņš',
'ipbreason' => 'Iemesls:',
'ipbreasonotherlist' => 'Cits iemesls',
'ipbreason-dropdown' => '*Biežākie bloķēšanas iemesli
** Ievieto nepatiesu informāciju
** Dzēš lapu saturu
** Spamo ārējās saitēs
** Ievieto nesakarīgus simbolus sakopojumus
** Nepieņemama uzvedība un apvainojumi
** Vairāku kontu ļaunprātīga izmantošana
** Nepieņemams lietotājvārds',
'ipbcreateaccount' => 'Neļaut izveidot lietotājvārdu',
'ipbemailban' => 'Neļaut lietotājam sūtīt e-pastu',
'ipbenableautoblock' => 'Automātiski bloķēt lietotāja pēdējo IP adresi un jebkuru IP adresi, no kuras šis lietotājs piekļūst šim wiki',
'ipbsubmit' => 'Bloķēt šo lietotāju',
'ipbother' => 'Cits laiks',
'ipboptions' => '2 stundas:2 hours,1 diena:1 day,3 dienas:3 days,1 nedēļa:1 week,2 nedēļas:2 weeks,1 mēnesis:1 month,3 mēneši:3 months,6 mēneši:6 months,1 gads:1 year,uz nenoteiktu laiku:infinite',
'ipbotheroption' => 'cits',
'ipbotherreason' => 'Cits/papildu iemesls:',
'ipbhidename' => "Slēpt lietot'javārdu no labojumiem un sarakstiem",
'ipbwatchuser' => 'Uzraudzīt šī lietotāja lietotāja un lietotāja diskusijas lapas',
'ipb-change-block' => 'Pārbloķēt ar šiem uzstādījumiem',
'ipb-confirm' => 'Apstiprināt bloķēšanu',
'badipaddress' => 'Nederīga IP adrese',
'blockipsuccesssub' => 'Nobloķēts veiksmīgi',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] tika nobloķēts.<br />
Visus blokus var apskatīties [[Special:BlockList|IP bloku sarakstā]].',
'ipb-edit-dropdown' => 'Izmainīt bloķēšanas iemeslus',
'ipb-unblock-addr' => 'Atbloķēt $1',
'ipb-unblock' => 'Atbloķēt lietotāju vai IP adresi',
'ipb-blocklist' => 'Apskatīties esošos blokus',
'ipb-blocklist-contribs' => '$1 devums',
'unblockip' => 'Atbloķēt lietotāju',
'unblockiptext' => 'Šeit var atbloķēt iepriekš nobloķētu IP adresi vai lietotāja vārdu (atjaunot viņiem rakstīšanas piekļuvi).',
'ipusubmit' => 'Noņemt šo bloku',
'unblocked' => '[[User:$1|$1]] tika atbloķēts',
'unblocked-range' => '$1 tika atbloķēts',
'unblocked-id' => 'Bloks $1 tika noņemts',
'blocklist' => 'Bloķētie lietotāji',
'ipblocklist' => 'Bloķētie lietotāji',
'ipblocklist-legend' => 'Meklēt bloķētu lietotāju',
'blocklist-userblocks' => 'Paslēpt kontu bloķējumus',
'blocklist-tempblocks' => 'Paslēpt pagaidu bloķējumus',
'blocklist-addressblocks' => 'Paslēpt vienas IP adreses bloķējumus',
'blocklist-timestamp' => 'Laiks',
'blocklist-target' => 'Mērķis',
'blocklist-params' => 'Bloķēšanas parametri',
'blocklist-reason' => 'Iemesls',
'ipblocklist-submit' => 'Meklēt',
'ipblocklist-localblock' => 'Vietējais bloks',
'ipblocklist-otherblocks' => ' {{PLURAL:$1|Cita|Citas}} {{PLURAL:$1|bloķēšana|bloķēšanas}}',
'infiniteblock' => 'bezgalīgs',
'expiringblock' => 'beidzas $1 $2',
'anononlyblock' => 'tikai anon.',
'noautoblockblock' => 'automātiskā bloķēšana atslēgta',
'createaccountblock' => 'kontu veidošana atslēgta',
'emailblock' => 'e-pasts bloķēts',
'blocklist-nousertalk' => 'nevar izmainīt savu diskusiju lapu',
'ipblocklist-empty' => 'Bloķēšanas saraksts ir tukšs.',
'ipblocklist-no-results' => 'Norādītā IP adrese vai lietotājs nav bloķēts.',
'blocklink' => 'bloķēt',
'unblocklink' => 'atbloķēt',
'change-blocklink' => 'izmainīt bloku',
'contribslink' => 'devums',
'emaillink' => 'nosūtīt e-pastu',
'autoblocker' => 'Tava IP ir nobloķēta automātiski, tāpēc, ka to nesen lietojis "[[User:$1|$1]]".
Viņa bloķēšanas iemesls bija: "$2"',
'blocklogpage' => 'Bloķēšanas reģistrs',
'blocklog-showlog' => 'Šis lietotājs ir bijis bloķēts jau agrāk.
Te apakšā var apskatīties bloķēšanas reģistru:',
'blocklogentry' => 'nobloķēja [[$1]] uz $2 $3',
'reblock-logentry' => 'izmainīja bloķēšanas iestatījumus [[$1]] ar beigu termiņu $2 $3',
'blocklogtext' => 'Šajā lapā ir pēdējo nobloķēto un atbloķēto lietotāju saraksts.
Te neparādās automātiski nobloķētās IP adreses.
Šobrīd aktīvos blokus var apskatīties bloķēto lietotāju [[Special:BlockList|IP adrešu sarakstā]].',
'unblocklogentry' => 'atbloķēja $1',
'block-log-flags-anononly' => 'tikai anonīmiem lietotājiem',
'block-log-flags-nocreate' => 'kontu veidošana atslēgta',
'block-log-flags-noautoblock' => 'automātiskā bloķēšana atslēgta',
'block-log-flags-noemail' => 'e-pasts bloķēts',
'block-log-flags-nousertalk' => 'nevar izmainīt savu diskusiju lapu',
'block-log-flags-hiddenname' => 'lietotājvārds slēpts',
'ipb_expiry_invalid' => 'Nederīgs beigu termiņš',
'ipb_expiry_temp' => 'Slēpto lietotājvārdu bloķēšanai jābūt beztermiņa.',
'ipb_already_blocked' => '"$1" jau ir bloķēts',
'ipb-needreblock' => '$1 jau ir bloķēts.
Vai tu gribi izmainīt bloka uzstādījumus?',
'ipb-otherblocks-header' => '{{PLURAL:$1|Cits bloks|Citi bloki}}',
'unblock-hideuser' => 'Šo lietotāju nevar atbloķēt, jo tā lietotājvārds ir paslēpts.',
'ipb_cant_unblock' => 'Kļūda: Bloka ID $1 nav atrasts.
Tas, iespējams, jau ir atbloķēts.',
'ipb_blocked_as_range' => 'Kļūda: IP $1 nav bloķēta tieši, tāpēc to nevar atbloķēt.
Tā ir bloķēta kā daļa no IP adrešu diapazona $2, kuru var atbloķēt.',
'ip_range_invalid' => 'Nederīgs IP diapazons',
'blockme' => 'Bloķēt mani',
'proxyblocker' => 'Starpniekservera bloķētājs',
'proxyblocker-disabled' => 'Šī funkcija ir atspējota.',
'proxyblocksuccess' => 'Darīts.',
'cant-block-while-blocked' => 'Tu nevari bloķēt citus lietotājus, kamēr pats esi bloķēts.',
'ipbblocked' => 'Tu nevar bloķēt vai atbloķēt lietotājus, jo Tu pats esi bloķēts',
'ipbnounblockself' => 'Tev nav atļauts sevi atbloķēt',

# Developer tools
'lockdb' => 'Bloķēt datubāzi',
'unlockdb' => 'Atbloķēt datubāzi',
'lockconfirm' => 'Jā, es tiešām vēlos bloķēt datubāzi.',
'unlockconfirm' => 'Jā, es tiešām vēlos atbloķēt datubāzi.',
'lockbtn' => 'Bloķēt datubāzi',
'unlockbtn' => 'Atbloķēt datubāzi',
'lockdbsuccesssub' => 'Datubāzes bloķēšana pabeigta',
'unlockdbsuccesssub' => 'Datubāze atbloķēta',
'unlockdbsuccesstext' => 'Datubāze ir atbloķēta.',
'databasenotlocked' => 'Datubāzē nav bloķēta.',

# Move page
'move-page' => 'Pārvietot $1',
'move-page-legend' => 'Pārvietot lapu',
'movepagetext' => "Šajā lapā tu vari pārdēvēt vai pārvietot lapu, kopā tās izmaiņu hronoloģiju pārvietojot to uz citu nosaukumu.
Iepriekšējā lapa kļūs par lapu, kas pāradresēs uz jauno lapu.
Šeit var automātiski izmainīt visas pāradresācijas (redirektus) uz šo lapu (2. ķeksis apakšā).
Saites pārējās lapās uz iepriekšējo lapu netiks mainītas. Ja izvēlies neizmainīt pāradresācijas automātiski, noteikti pārbaudi un izlabo, izskaužot [[Special:DoubleRedirects|dubultu pāradresāciju]] vai [[Special:BrokenRedirects|pāradresāciju uz neesošu lapu]].
Tev ir jāpārliecinās, vai saites vēl aizvien ved tur, kur tās ir paredzētas.

Ņem vērā, ka lapa '''netiks''' pārvietota, ja jau eksistē kāda cita lapa ar vēlamo nosaukumu (izņemot gadījumus, kad tā ir tukša vai kad tā ir pāradresācijas lapa, kā arī tad, ja tai nav izmaiņu hronoloģijas).
Tas nozīmē, ka tu vari pārvietot lapu atpakaļ, no kurienes tu jau reiz to esi pārvietojis, ja būsi kļūdījies, bet tu nevari pārrakstīt jau esošu lapu.

'''BRĪDINĀJUMS!'''
Populārām lapām tā var būt krasa un negaidīta pārmaiņa;
pirms turpināšanas vēlreiz pārdomā, vai tu izproti visas iespējamās sekas.",
'movepagetalktext' => "Saistītā diskusiju lapa, ja tāda eksistē, tiks automātiski pārvietota, '''izņemot gadījumus, kad''':
*tu pārvieto lapu uz citu palīglapu,
*ar jauno nosaukumu jau eksistē diskusiju lapa, vai arī
*atzīmēsi zemāk atrodamo lauciņu.

Ja tomēr vēlēsies, tad tev šī diskusiju lapa būs jāpārvieto vai jāapvieno pašam.",
'movearticle' => 'Pārvietot lapu',
'movenologin' => 'Neesi iegājis kā reģistrēts lietotājs',
'movenologintext' => 'Tev ir jābūt reģistrētam lietotājam un jābūt [[Special:UserLogin|iegājušam]] {{grammar:lokatīvs|{{SITENAME}}}}, lai pārvietotu lapu.',
'movenotallowed' => 'Tev nav atļaujas pārvietot lapas.',
'movenotallowedfile' => 'Tev nav atļaujas pārvietot failus.',
'cant-move-user-page' => 'Tev nav atļaujas pārvietot lietotāju lapas (neskaitot apakšlapas).',
'cant-move-to-user-page' => 'Tev nav atļaujas pārvietot lapu uz lietotāja lapu (neskaitot lietotāja lapas apakšlapu).',
'newtitle' => 'Uz šādu lapu',
'move-watch' => 'Uzraudzīt šo lapu',
'movepagebtn' => 'Pārvietot lapu',
'pagemovedsub' => 'Pārvietošana notikusi veiksmīgi',
'movepage-moved' => '\'\'\'"$1" tika pārvietots uz "$2"\'\'\'',
'movepage-moved-redirect' => 'Tika izveidota pāradresācija.',
'articleexists' => 'Lapa ar tādu nosaukumu jau pastāv vai arī tevis izvēlētais nosaukums ir nederīgs. Lūdzu, izvēlies citu nosaukumu.',
'cantmove-titleprotected' => 'Tu nevari pārvietot lapu uz šo nosaukumu, tāpēc, ka jaunais nosaukums (lapa) ir aizsargāta pret izveidošanu',
'talkexists' => "'''Šī lapa pati tika pārvietota veiksmīgi, bet tās diskusiju lapu nevarēja pārvietot, tapēc, ka jaunā nosaukuma lapai jau ir diskusiju lapa. Lūdzu apvieno šīs diskusiju lapas manuāli.'''",
'movedto' => 'pārvietota uz',
'movetalk' => 'Pārvietot arī diskusiju lapu, ja tāda ir.',
'move-subpages' => 'Pārvietot apakšlapas (līdz $1 gab.)',
'move-talk-subpages' => 'Pārvietot diskusiju lapas apakšlapas (līdz $1 gab.)',
'movepage-page-exists' => 'Lapa $1 jau eksistē un to nevar pārrakstīt automātiski.',
'movepage-page-moved' => 'Lapa $1 tika pārvietota uz $2.',
'movepage-page-unmoved' => 'Lapu $1 nevarēja pārvietot uz $2.',
'movelogpage' => 'Pārvietošanas reģistrs',
'movelogpagetext' => 'Lapu pārvietošanas (pārdēvēšanas) reģistrs.',
'movesubpage' => '{{PLURAL:$1|Apakšlapa|Apakšlapas}}',
'movesubpagetext' => 'Šai lapai ir $1 {{PLURAL:$1|apakšlapa|apakšlapas}}, kas redzamas zemāk.',
'movenosubpage' => 'Šai lapai nav apakšlapu.',
'movereason' => 'Iemesls:',
'revertmove' => 'atcelt',
'delete_and_move' => 'Dzēst un pārvietot',
'delete_and_move_text' => '==Nepieciešama dzēšana==
Mērķa lapa "[[:$1]]" jau eksistē.
Vai tu to gribi izdzēst, lai atbrīvotu vietu pārvietošanai?',
'delete_and_move_confirm' => 'Jā, dzēst lapu',
'delete_and_move_reason' => 'Izdzēsts, lai atbrīvotu vietu pārvietošanai no "[[$1]]"',
'selfmove' => 'Izejas un mērķa lapu nosaukumi ir vienādi;
nevar pārvietot lapu uz sevi.',
'immobile-source-namespace' => 'Nevar pārvietot lapas vārdtelpā "$1"',
'immobile-target-namespace' => 'Nevar pārvietot lapas uz vārdtelpu "$1"',
'immobile-source-page' => 'Šī lapa nav pārvietojama.',
'immobile-target-page' => 'Nevar pārvietot uz mērķa nosaukumu.',
'imagenocrossnamespace' => 'Nevar pārvietot failu uz vārtelpu, kas nav paredzēta failiem.',
'nonfile-cannot-move-to-file' => 'Nevar pārvietot to, kas nav fails, uz failu vārdtelpu.',
'imagetypemismatch' => 'Jaunais faila paplašinājums neatbilst tā tipam',
'imageinvalidfilename' => 'Mērķa faila nosaukums ir nederīgs',
'fix-double-redirects' => 'Automātiski izmainīt visas pāradresācijas, kas ved uz sākotnējo nosaukumu',
'move-leave-redirect' => 'Atstāt pāradresāciju',
'protectedpagemovewarning' => "'''Brīdinājums:''' Šī lapa ir aizsargāta, tikai lietotāji ar administratora privilēģijām var to pārvietot.
Pēdējais reģistra ieraksts ir apskatāms zemāk:",
'semiprotectedpagemovewarning' => "'''Piezīme:''' Šī lapa ir aizsargāta, tikai reģistrētie lietotāji var to pārvietot.
Pēdējais reģistra ieraksts ir apskatāms zemāk:",
'move-over-sharedrepo' => '== Fails jau pastāv ==
[[:$1]] jau pastāv koplietotā repozitorijā. Pārvietošana uz šo nosaukumu aizstās koplietoto failu.',

# Export
'export' => 'Eksportēt lapas',
'exporttext' => 'Šeit var eksportēt kādas noteiktas lapas vai lapu kopas tekstus un rediģēšanas hronoloģijas, XML formātā.
Šādus datus pēc tam varēs ieimportēt citā MediaWiki wiki lietojot [[Special:Import|Importēt lapas]]

Lai eksportētu lapas, šajā laukā ievadi to nosaukumus, katrā rindiņā pa vienam, un izvēlies vai gribi tikai pašreizējo versiju ar informāciju par pēdējo izmaiņu, vai arī pašreizējo versiju kopā ar visām vecajām versijām un hronoloģiju

Pirmajā gadījumā var arī lietot šādu metodi, piem., [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] lapai "[[{{MediaWiki:Mainpage}}]]".',
'exportall' => 'Eksportēt visas lapas',
'exportcuronly' => 'Iekļaut tikai esošo versiju (bez pilnās hronoloģijas)',
'exportnohistory' => "----
'''Piezīme:''' Lapu eksportēšana kopā ar visu hronoloģiju šobrīd ir atslēgta, jo tas bremzē serveri.",
'export-submit' => 'Eksportēt',
'export-addcattext' => 'Pievienot lapas no kategorijas:',
'export-addcat' => 'Pievienot',
'export-addnstext' => 'Pievienot lapas no vārdtelpas:',
'export-addns' => 'Pievienot',
'export-download' => 'Saglabāt kā failu',
'export-templates' => 'Iekļaut veidnes',

# Namespace 8 related
'allmessages' => 'Visi sistēmas paziņojumi',
'allmessagesname' => 'Nosaukums',
'allmessagesdefault' => 'Noklusētais ziņojuma teksts',
'allmessagescurrent' => 'Pašreizējais teksts',
'allmessagestext' => "Šajā lapā ir visu \"'''MediaWiki:'''\" lapās atrodamo sistēmas paziņojumu uzskaitījums.
Šos paziņojumus var izmainīt tikai admini. Izmainot tos šeit, tie tiks izmainīti tikai šajā mediawiki instalācijā. Lai tos izmainītu visām pārējām, apskatieties [//www.mediawiki.org/wiki/Localisation MediaWiki Localisation] un [//translatewiki.net translatewiki.net].",
'allmessagesnotsupportedDB' => "Šī lapa nedarbojas, tāpēc, ka '''wgUseDatabaseMessages''' nedarbojas.",
'allmessages-filter-legend' => 'Filtrs',
'allmessages-filter' => 'Filtrēt pēc izmainīšanas statusa:',
'allmessages-filter-unmodified' => 'Nemodificēti',
'allmessages-filter-all' => 'Visi',
'allmessages-filter-modified' => 'Modificēti',
'allmessages-prefix' => 'Filtrēt pēc prefiksa:',
'allmessages-language' => 'Valoda:',
'allmessages-filter-submit' => 'Parādīt',

# Thumbnails
'thumbnail-more' => 'Palielināt',
'filemissing' => 'Trūkst faila',
'thumbnail_error' => 'Kļūda, veidojot sīktēlu: $1',
'djvu_page_error' => 'DjVu lapa ir ārpus diapazona',
'djvu_no_xml' => 'Neizdevās ielādēt XML DjVu failam',
'thumbnail_invalid_params' => 'Nederīgi sīktēlu parametri',
'thumbnail_dest_directory' => 'Nevar izveidot mērķa direktoriju',
'thumbnail_image-type' => 'Attēla tips nav atbalstīts',
'thumbnail_gd-library' => 'Nepilnīga GD bibliotēkas konfigurācija: trūkst $1 funkcijas',
'thumbnail_image-missing' => 'Šķiet, ka fails ir pazudis: $1',

# Special:Import
'import' => 'Importēt lapas',
'importinterwiki' => 'Starpviki importēšana',
'import-interwiki-source' => 'Avota viki/lapa:',
'import-interwiki-history' => 'Nokopēt visas šīs lapas hronoloģijā atrodamās versijas',
'import-interwiki-templates' => 'Iekļaut visas veidnes',
'import-interwiki-submit' => 'Importēt',
'import-interwiki-namespace' => 'Mērķa vārdtelpa:',
'import-upload-filename' => 'Faila nosaukums:',
'import-comment' => 'Komentārs:',
'importstart' => 'Importē lapas...',
'import-revision-count' => '$1 {{PLURAL:$1|versija|versijas}}',
'importnopages' => 'Nav lapu, ko importēt.',
'imported-log-entries' => '{{PLURAL:$1|Importētais|Importētie}} $1 {{PLURAL:$1|reģistra ieraksts|reģistra ieraksti}}.',
'importfailed' => 'Importēšana neizdevās: <nowiki>$1</nowiki>',
'importunknownsource' => 'Nezināms importēšanas avota veids',
'importcantopen' => 'Nevarēja atvērt importējamo failu',
'importbadinterwiki' => 'Slikta starpviki saite',
'importnotext' => 'Tukšs vai nav teksta',
'importsuccess' => 'Importēšana pabeigta!',
'importnosources' => "Tiešā hronoloģijas augšuplāde ir atslēgta. Nav definēts neviens ''Transwiki'' importa avots (''source'').",
'importnofile' => 'Neviens importējamais fails netika augšupielādēts.',
'importuploaderrorsize' => 'Augšupielādēt importējamo failu neizdevās. 
Šis fails ir lielāks par atļauto augšupielādes lielumu.',
'importuploaderrorpartial' => 'Importējamā faila augšupielāde neizdevās.
Fails tika tikai daļēji importēts.',
'importuploaderrortemp' => 'Importētā faila augšupielāde neizdevās.
Pagaidu mape ir pazudusi.',
'import-parse-failure' => 'XML importēšanas parsēšanas kļūme',
'import-noarticle' => 'Nav lapas, ko importēt.',
'import-nonewrevisions' => 'Visas versijas bija pirms tam importētas.',
'xml-error-string' => '$1 $2. rindā, $3. kolonnā ($4. baits): $5',
'import-upload' => 'Augšupielādēt XML datus',
'import-token-mismatch' => 'Zaudēti sesijas dati.
Lūdzu, mēģiniet vēlreiz.',
'import-invalid-interwiki' => 'Nevar importēt no norādītās viki.',

# Import log
'importlogpage' => 'Importēšanas reģistrs',
'importlogpagetext' => 'Administratīvās lapu importēšanas no citām Vikipēdijām ar lapas hronoloģiju.',
'import-logentry-upload' => 'importēts [[$1]], izmantojot failu augšupielādi',
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|versija|versijas}}',
'import-logentry-interwiki' => 'starpvikizēts $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|versija|versijas}} no $2',

# JavaScriptTest
'javascripttest' => 'JavaScript testēšana',
'javascripttest-title' => 'Darbina $1 testus',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Tava lietotāja lapa',
'tooltip-pt-anonuserpage' => 'Manas IP adreses lietotāja lapa',
'tooltip-pt-mytalk' => 'Tava diskusiju lapa',
'tooltip-pt-anontalk' => 'Diskusija par labojumiem, kas izdarīti no šīs IP adreses',
'tooltip-pt-preferences' => 'Mani uzstādījumi',
'tooltip-pt-watchlist' => 'Manis uzraudzītās lapas.',
'tooltip-pt-mycontris' => 'Tavi ieguldījumi',
'tooltip-pt-login' => 'Aicinām tevi ieiet {{grammar:lokatīvs|{{SITENAME}}}}, tomēr tas nav obligāti.',
'tooltip-pt-anonlogin' => 'Aicinām tevi ieiet {{grammar:lokatīvs|{{SITENAME}}}}, tomēr tas nav obligāti.',
'tooltip-pt-logout' => 'Iziet',
'tooltip-ca-talk' => 'Diskusija par šī raksta lapu',
'tooltip-ca-edit' => 'Izmainīt šo lapu. Lūdzam izmantot pirmskatu pirms lapas saglabāšanas.',
'tooltip-ca-addsection' => 'Sākt jaunu sadaļu',
'tooltip-ca-viewsource' => 'Šī lapa ir aizsargāta. Tu vari apskatīties tās izejas kodu.',
'tooltip-ca-history' => 'Šīs lapas iepriekšējās versijas.',
'tooltip-ca-protect' => 'Aizsargāt šo lapu',
'tooltip-ca-unprotect' => 'Mainīt šīs lapas aizsardzību',
'tooltip-ca-delete' => 'Dzēst šo lapu',
'tooltip-ca-undelete' => 'Atjaunot labojumus, kas izdarīti šajā lapā pirms lapas dzēšanas.',
'tooltip-ca-move' => 'Pārvietot šo lapu',
'tooltip-ca-watch' => 'Pievienot šo lapu uzraugāmo lapu sarakstam',
'tooltip-ca-unwatch' => 'Izņemt šo lapu no uzraudzītajām lapām',
'tooltip-search' => 'Meklēt šajā wiki',
'tooltip-search-go' => 'Aiziet uz lapu ar precīzi šādu nosaukumu, ja tāda pastāv',
'tooltip-search-fulltext' => 'Meklēt lapās šo tekstu',
'tooltip-p-logo' => 'Sākumlapa',
'tooltip-n-mainpage' => 'Iet uz sākumlapu',
'tooltip-n-mainpage-description' => 'Šī projekta sākumlapa',
'tooltip-n-portal' => 'Par šo projektu, par to, ko tu vari šeit darīt un kur ko atrast',
'tooltip-n-currentevents' => 'Uzzini papildinformāciju par šobrīd aktuālajiem notikumiem',
'tooltip-n-recentchanges' => 'Izmaiņas, kas nesen izdarītas šajā wiki.',
'tooltip-n-randompage' => 'Iet uz nejauši izvēlētu lapu',
'tooltip-n-help' => 'Vieta, kur uzzināt.',
'tooltip-t-whatlinkshere' => 'Visas wiki lapas, kurās ir saites uz šejieni',
'tooltip-t-recentchangeslinked' => 'Izmaiņas, kas nesen izdarītas lapās, kurās ir saites uz šo lapu',
'tooltip-feed-rss' => 'Šīs lapas RSS barotne',
'tooltip-feed-atom' => 'Šīs lapas Atom barotne',
'tooltip-t-contributions' => 'Apskatīt šā lietotāja ieguldījumu uzskaitījumu.',
'tooltip-t-emailuser' => 'Sūtīt e-pastu šim lietotājam',
'tooltip-t-upload' => 'Augšuplādēt attēlus vai multimēdiju failus',
'tooltip-t-specialpages' => 'Visu īpašo lapu uzskaitījums',
'tooltip-t-print' => 'Drukājama lapas versija',
'tooltip-t-permalink' => 'Paliekoša saite uz šo lapas versiju',
'tooltip-ca-nstab-main' => 'Apskatīt rakstu',
'tooltip-ca-nstab-user' => 'Apskatīt lietotāja lapu',
'tooltip-ca-nstab-media' => 'Apskatīt multimēdiju lapu',
'tooltip-ca-nstab-special' => 'Šī ir īpašā lapa, tu nevari izmainīt pašu lapu.',
'tooltip-ca-nstab-project' => 'Apskatīt projekta lapu',
'tooltip-ca-nstab-image' => 'Apskatīt attēla lapu',
'tooltip-ca-nstab-mediawiki' => 'Apskatīt sistēmas paziņojumu',
'tooltip-ca-nstab-template' => 'Apskatīt veidni',
'tooltip-ca-nstab-help' => 'Apskatīt palīdzības lapu',
'tooltip-ca-nstab-category' => 'Apskatīt kategorijas lapu',
'tooltip-minoredit' => 'Atzīmēt šo par maznozīmīgu labojumu',
'tooltip-save' => 'Saglabāt veiktās izmaiņas',
'tooltip-preview' => 'Parādīt izmaiņu priekšskatījumu. Lūdzam izmantot šo iespēju pirms saglabāšanas.',
'tooltip-diff' => 'Parādīt, kā esi izmainījis tekstu.',
'tooltip-compareselectedversions' => 'Aplūkot atšķirības starp divām izvēlētajām lapas versijām.',
'tooltip-watch' => 'Pievienot šo lapu uzraugāmo lapu sarakstam',
'tooltip-recreate' => 'Atjaunot lapu, lai arī tā ir bijusi izdzēsta',
'tooltip-upload' => 'Sākt augšuplādi',
'tooltip-rollback' => '"Novērst" atceļ visas šī lietotāja izmaiņas vienā piegājienā',
'tooltip-undo' => '"Atgriezt" atgriež šīs izmaiņas un atver labošanas formu priekšskatījuma veidā.
Tas atļauj pievienot iemeslu kopsavilkumā.',
'tooltip-preferences-save' => 'Saglabāt iestatījumus',
'tooltip-summary' => 'Ievadiet īsu kopsavilkumu',

# Metadata
'notacceptable' => 'Vikipēdijas serveris nevar sniegt datus Jūsu klientam nolasāmā formātā.',

# Attribution
'anonymous' => '{{PLURAL:$1|Anonīmais {{grammar:ģenitīvs|{{SITENAME}}}} lietotājs|Anonīmie {{grammar:ģenitīvs|{{SITENAME}}}} lietotāji}}',
'siteuser' => '{{grammar:ģenitīvs|{{SITENAME}}}} lietotājs $1',
'anonuser' => '{{SITENAME}} anonīms lietotājs $1',
'lastmodifiedatby' => 'Šo lapu pēdējoreiz izmainīja $3, $2, $1.',
'othercontribs' => 'Balstototies uz $1 darbu.',
'others' => 'citi',
'siteusers' => '{{SITENAME}} {{PLURAL:$2|lietotāja|lietotāju}} $1',
'anonusers' => '{{SITENAME}} anonīma {{PLURAL:$2|lietotāja|lietotāju}} $1',
'creditspage' => 'Lapas autori',
'nocredits' => 'Šai lapa nav pieejama informācija par autoriem.',

# Spam protection
'spamprotectiontitle' => 'Spama filtrs',
'spamprotectiontext' => 'Lapu, kuru tu gribēji saglabāt, nobloķēja spama filtrs.
To visticamāk izraisīja ārēja saite uz melnajā sarakstā esošu interneta vietni.',
'spamprotectionmatch' => 'Spama filtram radās iebildumi pret šo tekstu: $1',
'spambot_username' => 'MediaWiki surogātpasta tīrīšana',
'spam_reverting' => 'Atjauno iepriekšējo versiju, kas nesatur saiti uz $1',

# Info page
'pageinfo-title' => 'Informācija par "$1"',
'pageinfo-header-basic' => 'Pamatinformācija',
'pageinfo-header-edits' => 'Labojumu vēsture',
'pageinfo-header-restrictions' => 'Lapas aizsardzība',
'pageinfo-header-properties' => 'Lapas parametri',
'pageinfo-length' => 'Lapas garums (baitos)',
'pageinfo-article-id' => 'Lapas ID',
'pageinfo-views' => 'Skatījumu skaits',
'pageinfo-watchers' => 'Uzraudzītāju skaits',
'pageinfo-redirects-name' => 'Pāradresācijas uz šo lapu',
'pageinfo-subpages-name' => 'Šīs lapas apakšlapas',
'pageinfo-lastuser' => 'Pēdējais labotājs',
'pageinfo-edits' => 'Izmaiņu skaits',
'pageinfo-authors' => 'Atsevišķu autoru skaits',

# Patrolling
'markaspatrolleddiff' => 'Atzīmēt kā pārbaudītu',
'markaspatrolledtext' => 'Atzīmēt šo lapu kā pārbaudītu',
'markedaspatrolled' => 'Atzīmēta kā pārbaudīta',
'rcpatroldisabled' => 'Pēdējo izmaiņu pārbaude atslēgta',
'rcpatroldisabledtext' => 'Pēdējo izmaiņu pārbaudes iespēja šobrīd ir atslēgta.',
'markedaspatrollederror' => 'Nevar atzīmēt kā pārbaudītu',
'markedaspatrollederrortext' => 'Jums jānorāda versija, ko atzīmēt kā pārbaudītu.',
'markedaspatrollederror-noautopatrol' => 'Jums nav atļaujas atzīmēt savas izmaiņas kā pārbaudītas.',

# Patrol log
'patrol-log-page' => 'Pārbaudes reģistrs',
'patrol-log-header' => 'Šis ir pārbaudīto versiju reģistrs.',
'log-show-hide-patrol' => '$1 pārbaudes reģistrs',

# Image deletion
'deletedrevision' => 'Izdzēstā vecā versija $1',
'filedeleteerror-short' => 'Kļūda dzēšot failu: $1',
'filedeleteerror-long' => 'Kļūdas, kas radās failu dzēšanas laikā:

$1',
'filedelete-missing' => 'Failu "$1" nevar izdzēst, jo tas nepastāv.',
'filedelete-old-unregistered' => 'Izvēlētā faila versija "$1" nav datubāzē.',
'filedelete-current-unregistered' => 'Izvēlētais fails "$1" nav datubāzē.',

# Browsing diffs
'previousdiff' => '← Vecāka versija',
'nextdiff' => 'Jaunāka versija →',

# Media information
'mediawarning' => "'''Brīdinājums''': Šis faila tips var saturēt ļaunprātīgu kodu, kuru izpildot, tava datora darbība var tikt traucēta.",
'imagemaxsize' => 'Attēlu apraksta lapās parādāmo attēlu maksimālais izmērs:',
'thumbsize' => 'Sīkbildes izmērs:',
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|lapa|lapas}}',
'file-info' => 'faila izmērs: $1, MIME tips: $2',
'file-info-size' => '$1 × $2 pikseļi, faila izmērs: $3, MIME tips: $4',
'file-info-size-pages' => '$1 × $2 pikseļi, faila izmērs: $3, MIME tips: $4, $5 {{PLURAL:$5|lapa|lapas}}',
'file-nohires' => 'Augstāka izšķirtspēja nav pieejama.',
'svg-long-desc' => 'SVG fails, definētais izmērs $1 × $2 pikseļi, faila izmērs: $3',
'show-big-image' => 'Pilnā izmērā',
'show-big-image-preview' => 'Šī priekšskata izmērs: $1.',
'show-big-image-other' => '{{PLURAL:$2|Cits izmērs|Citi izmēri}}: $1.',
'show-big-image-size' => '$1 × $2 pikseļi',
'file-info-gif-frames' => '$1 {{PLURAL:$1|kadrs|kadri}}',
'file-info-png-repeat' => 'spēlēts $1 {{PLURAL:$1|reizi|reizes}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|kadrs|kadri}}',

# Special:NewFiles
'newimages' => 'Jauno attēlu galerija',
'imagelisttext' => 'Šobrīd redzams $1 {{PLURAL:$1|attēla|attēlu}} uzskaitījums, kas sakārtots $2.',
'newimages-summary' => 'Šeit var apskatīties pēdējos šeit augšuplādētos failus.',
'newimages-legend' => 'Filtrs',
'newimages-label' => 'Faila nosaukums (vai tā daļa):',
'showhidebots' => '($1 botus)',
'noimages' => 'Nav nekā ko redzēt.',
'ilsubmit' => 'Meklēt',
'bydate' => '<b>pēc datuma</b>',
'sp-newimages-showfrom' => 'Rādīt jaunos attēlus sākot no $1, $2',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|$1 sekunde|$1 sekundes}}',
'minutes' => '{{PLURAL:$1|$1 minūte|$1 minūtes}}',
'hours' => '{{PLURAL:$1|$1 stunda|$1 stundas}}',
'days' => '{{PLURAL:$1|$1 diena|$1 dienas}}',
'ago' => 'pirms $1',

# Bad image list
'bad_image_list' => 'Formāts:

Tiek ņemti vērā tikai ieraksti rindiņā kas sākas ar *
Pirmajai saitei rindiņā ir jābūt uz attiecīgo failu
Jebkuras sekojošas saites tiks uzskatītas par izņēmumiem t.i. lapām kurās fails drīkt tikt izmantots',

# Metadata
'metadata' => 'Metadati',
'metadata-help' => 'Šis fails satur papildu informāciju, kuru visticamk ir pievienojis digitālais fotoaparāts vai skeneris, kas šo failu izveidoja. Ja šis fails pēc tam ir ticis modificēts, šie dati var neatbilst izmaiņām (var būt novecojuši).',
'metadata-expand' => 'Parādīt papildu detaļas',
'metadata-collapse' => 'Paslēpt papildu detaļas',
'metadata-fields' => 'Šajā paziņojumā esošie metadatu lauki būs redzami attēla lapā arī tad, kad metadatu tabula būs sakļauta.
Pārējie lauki, pēc noklusējuma, būs paslēpti.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# EXIF tags
'exif-imagewidth' => 'platums',
'exif-imagelength' => 'augstums',
'exif-bitspersample' => 'biti komponentē',
'exif-compression' => 'Saspiešanas veids',
'exif-orientation' => 'Orientācija',
'exif-samplesperpixel' => 'Komponentu skaits',
'exif-planarconfiguration' => 'Datu izkārtojums',
'exif-xresolution' => 'Horizontālā izšķirtspēja',
'exif-yresolution' => 'Vertikālā izšķirtspēja',
'exif-jpeginterchangeformatlength' => 'JPEG datu baiti',
'exif-datetime' => 'Attēla pēdējās izmainīšanas datums un laiks',
'exif-imagedescription' => 'Attēla nosaukums',
'exif-make' => 'Fotoaparāta ražotājs',
'exif-model' => 'Fotoaparāta modelis',
'exif-software' => 'Lietotā programma',
'exif-artist' => 'Autors',
'exif-copyright' => 'Autortiesību īpašnieks',
'exif-exifversion' => 'EXIF versija',
'exif-flashpixversion' => 'Atbalstīta Flashpix versija',
'exif-colorspace' => 'Krāsu telpa',
'exif-componentsconfiguration' => 'Katras sastāvdaļas nozīme',
'exif-compressedbitsperpixel' => 'Attēla kompresijas pakāpe',
'exif-pixelydimension' => 'Attēla platums',
'exif-pixelxdimension' => 'Attēla augstums',
'exif-usercomment' => 'Lietotāja komentāri',
'exif-relatedsoundfile' => 'Saistītais skaņas fails',
'exif-datetimeoriginal' => 'Izveidošanas datums un laiks',
'exif-datetimedigitized' => 'Attēla izveidošanas datums un laiks',
'exif-subsectime' => 'DateTime milisekundes',
'exif-subsectimeoriginal' => 'DateTimeOriginal milisekundes',
'exif-subsectimedigitized' => 'DateTimeDigitized milisekundes',
'exif-exposuretime' => 'Ekspozīcijas laiks',
'exif-exposuretime-format' => '$1 s ($2)',
'exif-fnumber' => 'Diafragmas atvērums',
'exif-exposureprogram' => 'Ekspozīcijas programma',
'exif-spectralsensitivity' => 'Spektrālā jutība',
'exif-isospeedratings' => 'ISO jutība',
'exif-shutterspeedvalue' => 'APEX slēdža ātrums',
'exif-aperturevalue' => 'APEX apertūra',
'exif-brightnessvalue' => 'APEX spilgtums',
'exif-exposurebiasvalue' => 'Ekspozīcijas nobīde',
'exif-subjectdistance' => 'Objekta attālums',
'exif-meteringmode' => 'Mērīšanas režīms',
'exif-lightsource' => 'Gaismas avots',
'exif-flash' => 'Zibspuldze',
'exif-focallength' => 'Fokusa attālums',
'exif-subjectarea' => 'Objekta laukums',
'exif-flashenergy' => 'Zibspuldzes stiprums',
'exif-focalplanexresolution' => 'Fokusa plaknes X izšķirtspēja',
'exif-focalplaneyresolution' => 'Fokusa plaknes Y izšķirtspēja',
'exif-focalplaneresolutionunit' => 'Fokusa plaknes izšķirtspējas vienības',
'exif-subjectlocation' => 'Objekta atrašanās vieta',
'exif-exposureindex' => 'Ekspozīcijas rādītājs',
'exif-sensingmethod' => 'Jutības metode',
'exif-filesource' => 'Faila avots',
'exif-scenetype' => 'Ainas veids',
'exif-customrendered' => 'Individuālā attēlu apstrāde',
'exif-exposuremode' => 'Ekspozīcijas režīms',
'exif-whitebalance' => 'Baltā balanss',
'exif-digitalzoomratio' => 'Digitālās tālummaiņas koeficients',
'exif-focallengthin35mmfilm' => 'Fokusa attālums 35 mm filmā',
'exif-scenecapturetype' => 'Ainas uzņemšanas veids',
'exif-gaincontrol' => 'Ainas kontrole',
'exif-contrast' => 'Kontrasts',
'exif-saturation' => 'Piesātinājums',
'exif-sharpness' => 'Asums',
'exif-devicesettingdescription' => 'Ierīces uzstādījumu apraksts',
'exif-subjectdistancerange' => 'Objekta attāluma diapazons',
'exif-imageuniqueid' => 'Unikālais attēla ID',
'exif-gpsversionid' => 'GPS taga versija',
'exif-gpslatituderef' => 'Ziemeļu vai dienvidu platums',
'exif-gpslatitude' => 'Platums',
'exif-gpslongituderef' => 'Austrumu vai rietumu garums',
'exif-gpslongitude' => 'Garums',
'exif-gpsaltituderef' => 'Augstuma atsauce',
'exif-gpsaltitude' => 'Augstums',
'exif-gpstimestamp' => 'GPS laiks (atompulkstenis)',
'exif-gpssatellites' => 'Mērīšanai izmantotie satelīti',
'exif-gpsstatus' => 'Uztvērēja statuss',
'exif-gpsmeasuremode' => 'Mērīšanas režīms',
'exif-gpsdop' => 'Mērīšanas precizitāte',
'exif-gpsspeedref' => 'Ātruma vienība',
'exif-gpsspeed' => 'GPS uztvērēja ātrums',
'exif-gpstrackref' => 'Kustības virziena atsauce',
'exif-gpstrack' => 'Kustības virziens',
'exif-gpsimgdirectionref' => 'Attēla virziena atsauce',
'exif-gpsimgdirection' => 'Attēla virziens',
'exif-gpsmapdatum' => 'Izmantoti ģeodēziskās mērīšanas dati',
'exif-gpsprocessingmethod' => 'GPS apstrādes metodes nosaukums',
'exif-gpsareainformation' => 'GPS zonas nosaukums',
'exif-gpsdatestamp' => 'GPS datums',
'exif-jpegfilecomment' => 'JPEG faila komentārs',
'exif-keywords' => 'Atslēgas vārdi',
'exif-worldregiondest' => 'Parādītais pasaules reģions',
'exif-countrydest' => 'Parādītā valsts',
'exif-countrycodedest' => 'Parādītās valsts kods',
'exif-provinceorstatedest' => 'Parādītās valsts province',
'exif-citydest' => 'Parādītā pilsēta',
'exif-sublocationdest' => 'Parādītā pilsētas daļa',
'exif-objectname' => 'Īsais nosaukums',
'exif-specialinstructions' => 'Īpašas norādes',
'exif-headline' => 'Virsraksts',
'exif-source' => 'Avots',
'exif-contact' => 'Kontaktinformācija',
'exif-languagecode' => 'Valoda',
'exif-iimversion' => 'IIM versija',
'exif-iimcategory' => 'Kategorija',
'exif-datetimeexpires' => 'Neizmantot pēc',
'exif-identifier' => 'Identifikators',
'exif-lens' => 'Izmantotais objektīvs',
'exif-serialnumber' => 'Fotoaparāta sērijas numurs',
'exif-cameraownername' => 'Fotoaparāta īpašnieks',
'exif-nickname' => 'Neformāls attēla nosaukums',
'exif-rating' => 'Vērtējums (no 5)',
'exif-copyrighted' => 'Autortiesību statuss',
'exif-copyrightowner' => 'Autortiesību īpašnieks',
'exif-usageterms' => 'Izmantošanas noteikumi',
'exif-originaldocumentid' => 'Sākotnējā dokumenta unikālais ID',
'exif-licenseurl' => 'Autortiesību licences URL',
'exif-morepermissionsurl' => 'Alternatīvas licencēšanas informācija',
'exif-attributionurl' => 'Izmantojot šo darbu, lūdzu pievienojiet saiti uz',
'exif-preferredattributionname' => 'Izmantojot šo darbu, lūdzu norādiet autoru',
'exif-pngfilecomment' => 'PNG faila komentārs',
'exif-disclaimer' => 'Atruna',
'exif-contentwarning' => 'Brīdinājums par saturu',
'exif-giffilecomment' => 'GIF faila komentārs',
'exif-event' => 'Attēlotais notikums',
'exif-organisationinimage' => 'Attēlotā organizācija',
'exif-personinimage' => 'Attēlotā persona',

# EXIF attributes
'exif-compression-1' => 'Nekompresēts',

'exif-copyrighted-true' => 'Ar autortiesībām',
'exif-copyrighted-false' => 'Publiski pieejams',

'exif-unknowndate' => 'Nezināms datums',

'exif-orientation-1' => 'Normāls',
'exif-orientation-2' => 'Pagriezts horizontāli',
'exif-orientation-3' => 'Pagriezts par 180°',
'exif-orientation-4' => 'Pagriezts vertikāli',
'exif-orientation-5' => 'Pagriezta 90° CCW un apgriezta vertikāli',
'exif-orientation-6' => 'Pagriezta 90° pretēji pulksteņa rādītājam',
'exif-orientation-7' => 'Pagriezta 90° CW un apgriezta vertikāli',
'exif-orientation-8' => 'Pagriezta 90° pulksteņa rādītāja virzienā',

'exif-colorspace-65535' => 'Nekalibrēts',

'exif-componentsconfiguration-0' => 'neeksistē',

'exif-exposureprogram-0' => 'Nav noteikta',
'exif-exposureprogram-1' => 'Manuāla',
'exif-exposureprogram-2' => 'Normāla programma',
'exif-exposureprogram-3' => 'Diafragmas prioritāte',
'exif-exposureprogram-4' => 'Slēdža prioritāte',
'exif-exposureprogram-8' => 'Ainavu režīms (ainavu fotogrāfijām ar fokusu uz fonu)',

'exif-subjectdistance-value' => '$1 metri',

'exif-meteringmode-0' => 'Nav zināms',
'exif-meteringmode-255' => 'Cits',

'exif-lightsource-0' => 'Nav zināms',
'exif-lightsource-1' => 'Dienas gaisma',
'exif-lightsource-2' => 'Dienasgaismas lampa',
'exif-lightsource-3' => 'Kvēlspuldze',
'exif-lightsource-4' => 'Zibspuldze',
'exif-lightsource-9' => 'Labi laika apstākļi',
'exif-lightsource-10' => 'Mākoņains laiks',
'exif-lightsource-12' => 'Dienasgaismas lampa (D 5700 - 7100K)',
'exif-lightsource-13' => 'Dienasgaismas lampa (N 4600 – 5400K)',
'exif-lightsource-14' => 'Dienasgaismas lampa (W 3900 – 4500K)',
'exif-lightsource-15' => 'Dienasgaismas lampa (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Standarta gaisma A',
'exif-lightsource-18' => 'Standarta gaisma B',
'exif-lightsource-19' => 'Standarta gaisma C',
'exif-lightsource-24' => 'ISO studijas kvēlspuldze',
'exif-lightsource-255' => 'Cits gaismas avots',

# Flash modes
'exif-flash-fired-0' => 'Zibspuldze netika izmantota',
'exif-flash-fired-1' => 'Zibspuldze tika izmantota',
'exif-flash-mode-3' => 'automātiskais režīms',
'exif-flash-redeye-1' => 'sarkano acu efekta samazināšanas režīms',

'exif-focalplaneresolutionunit-2' => 'collas',

'exif-sensingmethod-1' => 'Nav definēts',
'exif-sensingmethod-2' => 'Vienas mikroshēmas krāsu zonas sensors',
'exif-sensingmethod-3' => 'Divu mikroshēmu krāsu zonas sensors',
'exif-sensingmethod-4' => 'Trīs mikroshēmu krāsu zonas sensors',

'exif-customrendered-0' => 'Normāls process',
'exif-customrendered-1' => 'Dažādots process',

'exif-exposuremode-0' => 'Automātiskā ekspozīcija',
'exif-exposuremode-1' => 'Manuālā ekspozīcija',

'exif-whitebalance-0' => 'Automātisks baltā balanss',
'exif-whitebalance-1' => 'Manuāls baltā balanss',

'exif-scenecapturetype-0' => 'Standarta',
'exif-scenecapturetype-1' => 'Ainava',
'exif-scenecapturetype-2' => 'Portrets',
'exif-scenecapturetype-3' => 'Nakts aina',

'exif-contrast-0' => 'Normāls',
'exif-contrast-1' => 'Viegls',
'exif-contrast-2' => 'Pārmērīgs',

'exif-saturation-0' => 'Normāls',
'exif-saturation-1' => 'Zems piesātinājums',
'exif-saturation-2' => 'Augsts piesātinājums',

'exif-sharpness-0' => 'Normāls',
'exif-sharpness-1' => 'Viegls',
'exif-sharpness-2' => 'Pārmērīgs',

'exif-subjectdistancerange-0' => 'Nav zināma',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Tuvs skats',
'exif-subjectdistancerange-3' => 'Tāls skats',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Ziemeļu platums',
'exif-gpslatitude-s' => 'Dienvidu platums',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Austrumu garums',
'exif-gpslongitude-w' => 'Rietumu garums',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|metrs|metri}} virs jūras līmeņa',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|metrs|metri}} zem jūras līmeņa',

'exif-gpsmeasuremode-2' => 'Divdimensionāls mērījums',
'exif-gpsmeasuremode-3' => 'Trīsdimensionāls mērījums',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometri stundā',
'exif-gpsspeed-m' => 'Jūdzes stundā',
'exif-gpsspeed-n' => 'Mezgli',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Kilometri',
'exif-gpsdestdistance-m' => 'Jūdzes',
'exif-gpsdestdistance-n' => 'Jūras jūdzes',

'exif-gpsdop-excellent' => 'Lielisks ($1)',
'exif-gpsdop-good' => 'Labs ($1)',
'exif-gpsdop-moderate' => 'Mērens ($1)',
'exif-gpsdop-fair' => 'Pieņemams ($1)',
'exif-gpsdop-poor' => 'Slikts ($1)',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Patiesais virziens',
'exif-gpsdirection-m' => 'Magnētiskais virziens',

'exif-dc-date' => 'Datums (-i)',
'exif-dc-publisher' => 'Izdevējs',
'exif-dc-rights' => 'Tiesības',

'exif-rating-rejected' => 'Noraidīts',

'exif-isospeedratings-overflow' => 'Lielāks kā 65535',

'exif-iimcategory-ace' => 'Māksla, kultūra un izklaide',
'exif-iimcategory-clj' => 'Noziedzība un likums',
'exif-iimcategory-dis' => 'Katastrofas un negadījumi',
'exif-iimcategory-fin' => 'Ekonomika un komercdarbība',
'exif-iimcategory-edu' => 'Izglītība',
'exif-iimcategory-evn' => 'Vide',
'exif-iimcategory-hth' => 'Veselība',
'exif-iimcategory-hum' => 'Cilvēku intereses',
'exif-iimcategory-lab' => 'Darbs',
'exif-iimcategory-lif' => 'Dzīvesveids un brīvā laika pavadīšana',
'exif-iimcategory-pol' => 'Politika',
'exif-iimcategory-rel' => 'Reliģija un ticība',
'exif-iimcategory-sci' => 'Zinātne un tehnoloģijas',
'exif-iimcategory-soi' => 'Sociālie jautājumi',
'exif-iimcategory-spo' => 'Sports',
'exif-iimcategory-war' => 'Karš, konflikti un nemieri',
'exif-iimcategory-wea' => 'Laika apstākļi',

'exif-urgency-normal' => 'Normāla ($1)',
'exif-urgency-low' => 'Zema ($1)',
'exif-urgency-high' => 'Augsta ($1)',
'exif-urgency-other' => 'Lietotāja definēta prioritāte ($1)',

# External editor support
'edit-externally' => 'Izmainīt šo failu ar ārēju programmu',
'edit-externally-help' => '(Skat. [//www.mediawiki.org/wiki/Manual:External_editors instrukcijas] Mediawiki.org, lai iegūtu vairāk informācijas).',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'visas',
'namespacesall' => 'visas',
'monthsall' => 'visi',
'limitall' => 'visas',

# E-mail address confirmation
'confirmemail' => 'Apstiprini e-pasta adresi',
'confirmemail_noemail' => '[[Special:Preferences|Tavās izvēlēs]] nav norādīta derīga e-pasta adrese.',
'confirmemail_text' => 'Šajā wiki ir nepieciešams apstiprināt savu e-pasta adresi, lai izmantotu e-pasta funkcijas.
Spied uz zemāk esošās pogas, lai uz tavu e-pasta adresi nosūtītu apstiprināšanas e-pastu.
Tajā būs saite ar kodu; spied uz tās saites vai atver to savā interneta pārlūkā,
lai apstiprinātu tavas e-pasta adreses derīgumu.',
'confirmemail_pending' => 'Apstiprināšanas kods jau tev tika nosūtīts pa e-pastu;
ja tu nupat izveidoji savu kontu, varētu drusku pagaidīt, kamēr tas kods pienāk, pirms mēģināt dabūt jaunu.',
'confirmemail_send' => 'Nosūtīt apstiprināšanas kodu',
'confirmemail_sent' => 'Apstiprināšanas e-pasts nosūtīts.',
'confirmemail_oncreate' => 'Apstiprinājuma kods tika nosūtīts uz tavu e-pasta adresi.
Šīs kods nav nepieciešams, lai varētu ielogoties, bet tas būs vajadzīgs lai pieslēgtu visas e-pasta bāzētās funkcijas šajā wiki.',
'confirmemail_sendfailed' => '{{SITENAME}} nevarēja nosūtīt apstiprināšanas e-pastu. Pārbaudi, vai adresē nav kāds nepareizs simbols.

Nosūtīšanas programma atmeta atpakaļ: $1',
'confirmemail_invalid' => 'Nederīgs apstiprināšanas kods. Iespējams, beidzies tā termiņš.',
'confirmemail_needlogin' => 'Lai apstiprinātu e-pasta adresi, tev vispirms jāielogojas ($1).',
'confirmemail_success' => 'Tava e-pasta adrese ir apstiprināta.
Tagad vari [[Special:UserLogin|doties iekšā]] ar savu lietotājvārdu un pilnvērtīgi izmantot wiki iespējas.',
'confirmemail_loggedin' => 'Tava e-pasta adrese tagad ir apstiprināta.',
'confirmemail_error' => 'Notikusi kāda kļūme ar tava apstiprinājuma saglabāšanu.',
'confirmemail_subject' => 'E-pasta adreses apstiprinajums no {{grammar:ģenitīvs|{{SITENAME}}}}',
'confirmemail_body' => 'Kads, iespejams, tu pats, no IP adreses $1 ir registrejis {{grammar:ģenitīvs|{{SITENAME}}}} lietotaja vardu "$2" ar so e-pasta adresi.

Lai apstiprinatu, ka so lietotaja vardu esi izveidojis tu pats, un aktivizetu e-pasta izmantosanu {{SITENAME}}, atver so saiti sava interneta parluka:

$3

Ja tu *neesi* registrejis sadu lietotaja vardu, atver sho saiti savaa interneta browserii, lai atceltu shiis e-pasta adreses apstiprinaashanu:

$5

Si apstiprinajuma koda deriguma termins ir $4.',
'confirmemail_invalidated' => 'E-pasta adreses apstiprināšana atcelta',
'invalidateemail' => 'Atcelt e-pasta adreses apstiprināšanu',

# Scary transclusion
'scarytranscludedisabled' => '[Starpviki saišu iekļaušana ir atspējota.]',
'scarytranscludefailed' => '[Neizdevās ienest veidni $1.]',
'scarytranscludetoolong' => '[URL adrese ir pārāk gara.]',

# Delete conflict
'deletedwhileediting' => "'''Brīdinājums:''' Šī lapa tika izdzēsta, pēc tam, kad tu to sāki izmainīt!",
'confirmrecreate' => "Lietotājs [[User:$1|$1]] ([[User talk:$1|diskusija]]) izdzēsa šo lapu, pēc tam, kad tu to biji sācis rediģēt, ar iemeslu:
: ''$2''
Lūdzu apstiprini, ka tiešām gribi izveidot šo lapu no jauna.",
'recreate' => 'Izveidot no jauna',

# action=purge
'confirm_purge_button' => 'Labi',
'confirm-purge-top' => "Iztīrīt šīs lapas kešu (''cache'')?",

# action=watch/unwatch
'confirm-watch-button' => 'Labi',
'confirm-watch-top' => 'Pievienot šo lapu uzraugāmo lapu sarakstam?',
'confirm-unwatch-button' => 'Labi',

# Multipage image navigation
'imgmultipageprev' => '← iepriekšējā lapa',
'imgmultipagenext' => 'nākamā lapa →',
'imgmultigo' => 'Aiziet!',
'imgmultigoto' => 'Iet uz lapu $1',

# Table pager
'ascending_abbrev' => 'pieaug.',
'descending_abbrev' => 'dilst.',
'table_pager_next' => 'Nākamā lapa',
'table_pager_prev' => 'Iepriekšējā lapa',
'table_pager_first' => 'Pirmā lapa',
'table_pager_last' => 'Pēdējā lapa',
'table_pager_limit' => 'Rādīt $1 ierakstus vienā lapā',
'table_pager_limit_label' => 'Skaits vienā lapā:',
'table_pager_limit_submit' => 'Parādīt',
'table_pager_empty' => 'Neko neatrada',

# Auto-summaries
'autosumm-blank' => 'Nodzēsa lapu',
'autosumm-replace' => "Aizvieto lapas saturu ar '$1'",
'autoredircomment' => 'Pāradresē uz [[$1]]',
'autosumm-new' => 'Jauna lapa: $1',

# Live preview
'livepreview-loading' => 'Ielādē…',
'livepreview-ready' => 'Ielādējas… Gatavs!',
'livepreview-failed' => 'Tūlītējais pirmskats nobruka! Pamēģini parasto pirmskatu.',
'livepreview-error' => 'Neizdevās pievienoties: $1 "$2". Pamēģini parasto pirmskatu.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Izmaiņas, kas ir jaunākas par  $1 {{PLURAL:$1|sekundi|sekundēm}}, var neparādīties šajā sarakstā.',
'lag-warn-high' => 'Sakarā ar lielu datubāzes servera lagu, izmaiņas, kas svaigākas par $1 {{PLURAL:$1|sekundi|sekundēm}}, šajā sarakstā var neparādīties.',

# Watchlist editor
'watchlistedit-numitems' => 'Tavs uzraugāmo lapu saraksts satur {{PLURAL:$1|1 lapu|$1 lapas}}, neieskaitot diskusiju lapas.',
'watchlistedit-noitems' => 'Tavs uzraugāmo rakstu saraksts ir tukšs.',
'watchlistedit-normal-title' => 'Izmainīt uzraugāmo rakstu sarakstu',
'watchlistedit-normal-legend' => 'Noņemt lapas (virsrakstus) no uzraugāmo rakstu saraksta',
'watchlistedit-normal-explain' => 'Tavā uzraugāmo rakstu sarakstā esošās lapas ir redzamas zemāk.
Lai noņemtu lapu, ieķeksē lodziņā pretī lapai un uzspied Noņemt lapas.
Var arī izmainīt [[Special:EditWatchlist/raw|neapstrādātu sarakstu]] (viens liels teksta lauks).',
'watchlistedit-normal-submit' => 'Noņemt lapas',
'watchlistedit-normal-done' => '{{PLURAL:$1|1 lapa tika noņemta|$1 lapas tika noņemtas}} no uzraugāmo rakstu saraksta:',
'watchlistedit-raw-title' => 'Izmainīt uzraugāmo rakstu saraksta kodu',
'watchlistedit-raw-legend' => 'Izmainīt uzraugāmo rakstu saraksta kodu',
'watchlistedit-raw-explain' => 'Uzraugāmo rakstu sarakstā esošās lapas ir redzamas zemāk, un šo sarakstu var izmainīt lapas pievienojot vai izdzēšot no saraksta;
katrai rindai te atbilst viena lapa.
Tad, kad pabeigts, uzspied Atjaunot sarakstu.
Var arī lietot [[Special:EditWatchlist|standarta izmainīšanas lapu]].',
'watchlistedit-raw-titles' => 'Lapas:',
'watchlistedit-raw-submit' => 'Atjaunot sarakstu',
'watchlistedit-raw-done' => 'Tavs uzraugāmo rakstu saraksts tika atjaunots.',
'watchlistedit-raw-added' => '{{PLURAL:$1|1 lapa tika pievienota|$1 lapas tika pievienotas}}:',
'watchlistedit-raw-removed' => '{{PLURAL:$1|1 lapa tika noņemta|$1 lapas tika noņemtas}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Skatīt atbilstošās izmaiņas',
'watchlisttools-edit' => 'Apskatīt un izmainīt uzraugāmo rakstu sarakstu',
'watchlisttools-raw' => 'Izmainīt uzraugāmo rakstu saraksta kodu',

# Core parser functions
'unknown_extension_tag' => 'Nezināma paplašinājuma iezīme "$1"',

# Special:Version
'version' => 'Versija',
'version-extensions' => 'Ieinstalētie paplašinājumi',
'version-specialpages' => 'Īpašās lapas',
'version-variables' => 'Mainīgie',
'version-antispam' => 'Spama aizsardzība',
'version-skins' => 'Apdares',
'version-other' => 'Cita',
'version-hooks' => 'Aizķeres',
'version-hook-name' => 'Aizķeres nosaukums',
'version-version' => '(Versija $1)',
'version-license' => 'Licence',
'version-poweredby-credits' => "Šis viki darbojas ar '''[//www.mediawiki.org/ MediaWiki]''' programmatūru, autortiesības © 2001-$1 $2.",
'version-poweredby-others' => 'citi',
'version-software' => 'Instalētā programmatūra',
'version-software-product' => 'Produkts',
'version-software-version' => 'Versija',
'version-entrypoints-header-url' => 'URL',

# Special:FilePath
'filepath' => 'Failu adreses',
'filepath-page' => 'Fails:',
'filepath-submit' => 'Atrast',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'Meklēt failu kopijas',
'fileduplicatesearch-summary' => 'Meklē dublējošos failus, izmantojot uz jaucējfunkcijas vērtības.',
'fileduplicatesearch-legend' => 'Meklēt kopiju',
'fileduplicatesearch-filename' => 'Faila vārds:',
'fileduplicatesearch-submit' => 'Meklēt',
'fileduplicatesearch-info' => '$1 × $2 pikseļi<br />Faila izmērs: $3<br />MIME tips: $4',
'fileduplicatesearch-result-1' => 'Failam "$1" nav identiskas kopijas.',
'fileduplicatesearch-result-n' => 'Failam "$1" ir {{PLURAL:$2|1 identiska kopija|$2 identiskas kopijas}}.',

# Special:SpecialPages
'specialpages' => 'Īpašās lapas',
'specialpages-note' => '----
* Normālas īpašās lapas.
* <span class="mw-specialpagerestricted">Ierobežotas pieejas īpašās lapas.</span>
* <span class="mw-specialpagecached">Iekešotās īpašās lapas.</span>',
'specialpages-group-maintenance' => 'Uzturēšanas atskaites',
'specialpages-group-other' => 'Citas īpašās lapas',
'specialpages-group-login' => 'Ieiet / piereģistrēties',
'specialpages-group-changes' => 'Pēdējās izmaiņas un reģistri',
'specialpages-group-media' => 'Failu atskaites un augšuplāde',
'specialpages-group-users' => 'Lietotāji un piekļuves tiesības',
'specialpages-group-highuse' => 'Bieži izmantotās lapas',
'specialpages-group-pages' => 'Lapu saraksti',
'specialpages-group-pagetools' => 'Lapu rīki',
'specialpages-group-wiki' => 'Wiki dati un rīki',
'specialpages-group-redirects' => 'Pāradresējošas īpašās lapas',
'specialpages-group-spam' => 'Spama rīki',

# Special:BlankPage
'blankpage' => 'Tukša lapa',
'intentionallyblankpage' => 'Šī lapa ar nodomu ir atstāta tukša.',

# Special:Tags
'tags' => 'Derīgi izmaiņu tagi',
'tag-filter' => '[[Special:Tags|Tagu]] filtrs:',
'tag-filter-submit' => 'Filtrs',
'tags-title' => 'Tagi',
'tags-tag' => 'Taga nosaukums',
'tags-display-header' => 'Izmainīto sarakstu izskats',
'tags-description-header' => 'Nozīmes pilns apraksts',
'tags-hitcount-header' => 'Iezīmētās izmaiņas',
'tags-edit' => 'labot',
'tags-hitcount' => '$1 {{PLURAL:$1|izmaiņa|izmaiņas}}',

# Special:ComparePages
'comparepages' => 'Salīdzināt lapas',
'compare-selector' => 'Salīdzināt lapu versijas',
'compare-page1' => '1. lapa',
'compare-page2' => '2. lapa',
'compare-rev1' => '1. versija',
'compare-rev2' => '2. versija',
'compare-submit' => 'Salīdzināt',
'compare-invalid-title' => 'Norādītais nosaukums nav derīgs.',
'compare-title-not-exists' => 'Norādītais nosaukums neeksistē.',
'compare-revision-not-exists' => 'Norādītā versija neeksistē.',

# Database error messages
'dberr-header' => 'Šim viki ir problēma',
'dberr-problems' => 'Atvainojiet! 
Šai vietnei ir radušās tehniskas problēmas.',
'dberr-again' => 'Uzgaidiet dažas minūtes un pārlādējiet šo lapu.',
'dberr-info' => '(Nevar sazināties ar datubāzes serveri: $1)',
'dberr-usegoogle' => 'Pa to laiku Jūs varat izmantot Google meklēšanu.',
'dberr-outofdate' => 'Ņemiet vērā, ka mūsu satura indeksācija var būt novecojusi.',
'dberr-cachederror' => 'Šī ir lapas agrāk saglabātā kopija, tā var nebūt atjaunināta.',

# HTML forms
'htmlform-invalid-input' => 'Ar dažiem datiem no Jūsu ievades ir problēmas',
'htmlform-select-badoption' => 'Vērtība, ko Jūs norādījāt, nav derīga.',
'htmlform-int-invalid' => 'Vērtība, ko Jūs norādījāt, nav vesels skaitlis.',
'htmlform-float-invalid' => 'Vērtība, ko Jūs norādījāt, nav skaitlis.',
'htmlform-int-toolow' => 'Vērtība, ko Jūs norādījāt, ir mazāka par $1 minimumu',
'htmlform-int-toohigh' => 'Vērtība, ko Jūs norādījāt, ir lielāka par $1 maksimumu',
'htmlform-required' => 'Šī vērtība ir obligāta',
'htmlform-submit' => 'Iesniegt',
'htmlform-reset' => 'Atcelt izmaiņas',
'htmlform-selectorother-other' => 'Citi',

# SQLite database support
'sqlite-has-fts' => '$1 ar pilnteksta meklēšanas atbalstu',
'sqlite-no-fts' => '$1 bez pilnteksta meklēšanas atbalsta',

# New logging system
'logentry-delete-delete' => '$1 izdzēsa lapu $3',
'logentry-delete-restore' => '$1 atjaunoja lapu $3',
'revdelete-content-hid' => 'saturs slēpts',
'revdelete-summary-hid' => 'labojuma kopsavilkums slēpts',
'revdelete-uname-hid' => 'lietotājvārds slēpts',
'revdelete-content-unhid' => 'satura slēpšana atcelta',
'revdelete-summary-unhid' => 'labojuma kopsavilkuma slēpšana atcelta',
'revdelete-uname-unhid' => 'lietotājvārda slēpšana atcelta',
'revdelete-restricted' => 'piemērot administratoriem ierobežojumus',
'revdelete-unrestricted' => 'noņemt administratoriem ierobežojumus',
'logentry-move-move' => '$1 pārvietoja lapu $3 uz $4',
'logentry-move-move-noredirect' => '$1 pārvietoja lapu $3 uz $4, neatstājot pāradresāciju',
'logentry-move-move_redir' => '$1 pārvietoja lapu $3 uz $4, atstājot pāradresāciju',
'logentry-move-move_redir-noredirect' => '$1 pārvietoja lapu $3 uz $4 ar pāradresāciju, neatstājot pāradresāciju',
'logentry-newusers-newusers' => 'Lietotāja konts $1 tika izveidots',
'logentry-newusers-create' => 'Lietotāja konts $1 tika izveidots',
'logentry-newusers-create2' => 'Lietotāja kontu $3 izveidoja $1',
'logentry-newusers-autocreate' => 'Konts $1 tika izveidots automātiski',
'newuserlog-byemail' => 'parole nosūtīta pa e-pastu',

# Feedback
'feedback-subject' => 'Temats:',
'feedback-message' => 'Ziņojums:',
'feedback-cancel' => 'Atcelt',
'feedback-submit' => 'Iesniegt atsauksmes',
'feedback-adding' => 'Atsauksmes tiek pievienotas lapai...',
'feedback-error1' => 'Kļūda: API neatpazīts rezultāts',
'feedback-error2' => 'Kļūda: Labojums neizdevās',
'feedback-error3' => 'Kļūda: Nav atbildes no API',
'feedback-thanks' => 'Paldies! Jūsu atsauksmes ir ievietotas lapā "[$2  $1]".',
'feedback-close' => 'Gatavs',
'feedback-bugnew' => 'Es pārbaudīju. Ziņot par jaunu kļūdu',

# Search suggestions
'searchsuggest-search' => 'Meklēt',
'searchsuggest-containing' => 'Meklējamā frāze:',

# API errors
'api-error-copyuploaddisabled' => 'Augšupielāde no URL šajā serverī ir atspējota.',
'api-error-filename-tooshort' => 'Faila nosaukums ir pārāk īss.',
'api-error-http' => 'Iekšēja kļūda: Nevar izveidot savienojumu ar serveri.',
'api-error-ok-but-empty' => 'Iekšēja kļūda: Nav atbildes no servera.',
'api-error-timeout' => 'Serveris neatbildēja paredzētajā laikā.',
'api-error-unclassified' => 'Nezināma kļūda.',
'api-error-unknown-code' => 'Nezināma kļūda: " $1 "',
'api-error-unknown-warning' => 'Nezināms brīdinājums: $1',
'api-error-uploaddisabled' => 'Augšupielāde šajā wiki  ir atslēgta.',

);
