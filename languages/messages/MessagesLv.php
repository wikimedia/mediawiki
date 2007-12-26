<?php
/** Latvian (Latviešu)
 *
 * @addtogroup Language
 *
 * @author Niklas Laxström
 * @author Yyy
 * @author Knakts
 * @author לערי ריינהארט
 * @author Nike
 * @author Siebrand
 */

/*
 * @copyright Copyright © 2006, Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_MAIN             => '',
	NS_TALK             => 'Diskusija',
	NS_USER             => 'Lietotājs',
	NS_USER_TALK        => 'Lietotāja_diskusija',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '{{grammar:ģenitīvs|$1}}_diskusija',
	NS_IMAGE            => 'Attēls',
	NS_IMAGE_TALK       => 'Attēla_diskusija',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_diskusija',
	NS_TEMPLATE         => 'Veidne',
	NS_TEMPLATE_TALK    => 'Veidnes_diskusija',
	NS_HELP             => 'Palīdzība',
	NS_HELP_TALK        => 'Palīdzības_diskusija',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Kategorijas_diskusija',
);
$separatorTransformTable = array(',' => "\xc2\xa0", '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'               => 'Pasvītrot saites:',
'tog-highlightbroken'         => 'Saites uz neesošām lapām rādīt <a href="" class="new">šādi</a> (alternatīva: šādi<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Taisnot rindkopas',
'tog-hideminor'               => 'Paslēpt maznozīmīgus labojumus pēdējo izmaiņu lapā',
'tog-extendwatchlist'         => 'Izvērst uzraugāmo sarakstu, lai parādītu visas veiktās izmaiņas',
'tog-usenewrc'                => "Uzlabotas pēdējās izmaiņas (izmanto ''JavaScript'')",
'tog-numberheadings'          => 'Automātiski numurēt virsrakstus.',
'tog-showtoolbar'             => 'Rādīt rediģēšanas rīkjoslu',
'tog-editondblclick'          => "Atvērt rediģēšanas lapu ar dubultklikšķi (izmanto ''JavaScript'')",
'tog-editsection'             => 'Rādīt sadaļu izmainīšanas saites "izmainīt šo sadaļu"',
'tog-editsectiononrightclick' => 'Atvērt sadaļas izmainīšanas lapu, uzklikšķinot ar labo pogu uz sadaļas virsraksta (JavaScript)',
'tog-showtoc'                 => 'Parādīt satura rādītāju (lapām, kurās ir vairāk par 3 virsrakstiem)',
'tog-rememberpassword'        => 'Atcerēties paroli pēc pārlūka aizvēršanas',
'tog-editwidth'               => 'Parādīt izmainīšanas logu pilnā platumā',
'tog-watchcreations'          => 'Pievienot tevis radītās lapas uzraugāmo lapu sarakstam',
'tog-watchdefault'            => 'Pievienot tevis izmainītās lapas uzraugāmo lapu sarakstam',
'tog-watchmoves'              => 'Pievienot manis pārvietotās lapas uzraugāmajiem rakstiem',
'tog-watchdeletion'           => 'Pievienot manis izdzēstās lapas uzraugāmajiem rakstiem',
'tog-minordefault'            => 'Atzīmēt visus labojumus jau sākotnēji par maznozīmīgiem',
'tog-previewontop'            => 'Parādīt priekšskatījumu virs rediģēšanas loga, nevis zem.',
'tog-previewonfirst'          => 'Parādīt priekšskatījumu jau sākotnējā labošanā.',
'tog-nocache'                 => 'Neļaut pārlūkam saglabāt lapas kešatmiņā',
'tog-enotifwatchlistpages'    => 'Paziņot pa e-pastu par rakstu izmaiņām',
'tog-enotifusertalkpages'     => 'Paziņot pa e-pastu par izmaiņām manā diskusiju lapā',
'tog-enotifminoredits'        => 'Paziņot pa e-pastu arī par maznozīmīgiem rakstu labojumiem',
'tog-enotifrevealaddr'        => 'Atklāt manu e-pasta adresi paziņojumu vēstulēs',
'tog-shownumberswatching'     => 'Rādīt uzraudzītāju skaitu',
'tog-fancysig'                => 'Vienkāršs paraksts (bez automātiskās saites)',
'tog-externaleditor'          => 'Pēc noklusējuma izmantot ārēju programmu lapu izmainīšanai',
'tog-externaldiff'            => 'Pēc noklusējuma izmantot ārēju programmu izmaiņu parādīšanai',
'tog-showjumplinks'           => 'Rādīt pārlēkšanas saites',
'tog-uselivepreview'          => 'Lietot tūlītējo priekšskatījumu (izmanto "JavaScript"; eksperimentāla iespēja).',
'tog-forceeditsummary'        => 'Atgādināt man, ja kopsavilkuma ailīte ir tukša',
'tog-watchlisthideown'        => 'Paslēpt manus labojumus manā uzraugāmo sarakstā.',
'tog-watchlisthidebots'       => 'Paslēpt botu labojumus manā uzraugāmo sarakstā.',
'tog-watchlisthideminor'      => 'Paslēpt maznozīmīgos labojumus manā uzraugāmo sarakstā',
'tog-ccmeonemails'            => 'Sūtīt sev, citiem lietotājiem nosūtīto epastu, kopijas',

'underline-always'  => 'vienmēr',
'underline-never'   => 'nekad',
'underline-default' => 'Kā pārlūkā',

'skinpreview' => '(Priekšskats)',

# Dates
'sunday'    => 'svētdiena',
'monday'    => 'Pirmdiena',
'tuesday'   => 'otrdiena',
'wednesday' => 'trešdiena',
'thursday'  => 'ceturtdiena',
'friday'    => 'piektdiena',
'saturday'  => 'sestdiena',
'january'   => 'janvārī',
'february'  => 'februārī',
'march'     => 'martā',
'april'     => 'aprīlī',
'may_long'  => 'maijā',
'june'      => 'jūnijā',
'july'      => 'jūlijā',
'august'    => 'augustā',
'september' => 'septembrī',
'october'   => 'oktobrī',
'november'  => 'novembrī',
'december'  => 'decembrī',
'jan'       => 'janvārī,',
'feb'       => 'februārī,',
'mar'       => 'martā,',
'apr'       => 'aprīlī,',
'may'       => 'maijā,',
'jun'       => 'jūnijā,',
'jul'       => 'jūlijā,',
'aug'       => 'augustā,',
'sep'       => 'septembrī,',
'oct'       => 'oktobrī,',
'nov'       => 'novembrī,',
'dec'       => 'decembrī,',

# Bits of text used by many pages
'categories'      => '{{PLURAL:$1|Kategorija|Kategorijas}}',
'pagecategories'  => '{{PLURAL:$1|Kategorija|Kategorijas}}',
'category_header' => 'Raksti, kas ietverti kategorijā "$1".',
'subcategories'   => 'Apakškategorijas',

'mainpagetext' => "<big>'''MediaWiki veiksmīgi ieinstalēts'''</big>",

'about'          => 'Par',
'article'        => 'Raksts',
'newwindow'      => '(atveras jaunā logā)',
'cancel'         => 'Atcelt',
'qbfind'         => 'Meklēšana',
'qbbrowse'       => 'Navigācija',
'qbedit'         => 'Izmainīšana',
'qbpageoptions'  => 'Šī lapa',
'qbpageinfo'     => 'Konteksts',
'qbmyoptions'    => 'Manas lapas',
'qbspecialpages' => 'Īpašās lapas',
'moredotdotdot'  => 'Vairāk...',
'mypage'         => 'Mana lapa',
'mytalk'         => 'Mana diskusija',
'anontalk'       => 'Šīs IP adreses diskusija',
'navigation'     => 'Navigācija',

'errorpagetitle'    => 'Kļūda',
'returnto'          => 'Atgriezties: $1.',
'tagline'           => "No ''{{grammar:ģenitīvs|{{SITENAME}}}}''",
'help'              => 'Palīdzība',
'search'            => 'Meklēt',
'searchbutton'      => 'Meklēt',
'go'                => 'Aiziet!',
'searcharticle'     => 'Aiziet!',
'history'           => 'hronoloģija',
'history_short'     => 'Hronoloģija',
'updatedmarker'     => 'atjaunināti kopš pēdējā apmeklējuma',
'info_short'        => 'Informācija',
'printableversion'  => 'Drukājama versija',
'permalink'         => 'Pastāvīgā saite',
'print'             => 'Drukāt',
'edit'              => 'Izmainīt šo lapu',
'editthispage'      => 'Izmainīt šo lapu',
'delete'            => 'Dzēst',
'deletethispage'    => 'Dzēst šo lapu',
'undelete_short'    => 'Atjaunot $1 versijas',
'protect'           => 'Aizsargāt',
'protectthispage'   => 'Aizsargāt šo lapu',
'unprotect'         => 'Neaizsargāt',
'unprotectthispage' => 'Neaizsargāt šo lapu',
'newpage'           => 'Jauna lapa',
'talkpage'          => 'Diskusija par šo lapu',
'specialpage'       => 'Īpašā Lapa',
'personaltools'     => 'Lietotāja rīki',
'postcomment'       => 'Pievienot komentāru',
'articlepage'       => 'Apskatīt rakstu',
'talk'              => 'Diskusija',
'views'             => 'Apskates',
'toolbox'           => 'Rīki',
'userpage'          => 'Skatīt lietotāja lapu',
'projectpage'       => 'Skatīt projekta lapu',
'imagepage'         => 'Aplūkot attēla lapu',
'viewtalkpage'      => 'Skatīt diskusiju',
'otherlanguages'    => 'Citās valodās',
'redirectedfrom'    => '(Pāradresēts no $1)',
'redirectpagesub'   => 'Pāradresācijas lapa',
'lastmodifiedat'    => 'Šajā lapā pēdējās izmaiņas izdarītas $2, $1.', # $1 date, $2 time
'viewcount'         => 'Šī lapa ir tikusi apskatīta $1 reizes.',
'protectedpage'     => 'Aizsargāta lapa',
'jumpto'            => 'Pārlēkt uz:',
'jumptonavigation'  => 'navigācija',
'jumptosearch'      => 'meklēt',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Par {{grammar:akuzatīvs|{{SITENAME}}}}',
'aboutpage'         => 'Project:Par',
'bugreports'        => 'Kļūdu paziņojumi',
'copyright'         => 'Saturs ir pieejams saskaņā ar $1.',
'copyrightpagename' => '{{grammar:ģenitīvs|{{SITENAME}}}} autortiesības',
'copyrightpage'     => '{{ns:project}}:Autortiesības',
'currentevents'     => 'Aktualitātes',
'currentevents-url' => 'Project:Aktualitātes',
'disclaimers'       => 'Saistību atrunas',
'edithelp'          => 'Palīdzība izmaiņām',
'mainpage'          => 'Sākumlapa',
'portal'            => 'Kopienas portāls',
'portal-url'        => 'Project:Kopienas portāls',
'privacy'           => 'Privātuma politika',
'privacypage'       => 'Project:Privātuma politika',
'sitesupport'       => 'Ziedojumi',
'sitesupport-url'   => 'Project:Ziedojumi',

'badaccess' => 'Atļaujas kļūda',

'versionrequired'     => "Nepieciešamā ''MediaWiki'' versija: $1.",
'versionrequiredtext' => "Lai lietotu šo lapu, nepieciešama ''MediaWiki'' versija $1. Sk. [[Special:versija]].",

'ok'                  => 'Labi',
'retrievedfrom'       => 'Saturs iegūts no "$1"',
'youhavenewmessages'  => 'Tev ir $1 (skat. $2).',
'newmessageslink'     => 'jauns vēstījums',
'newmessagesdifflink' => 'izmaiņu lapu, lai redzētu, kas jauns',
'editsection'         => 'izmainīt šo sadaļu',
'editold'             => 'rediģēt',
'editsectionhint'     => 'Rediģēt sadaļu: $1',
'toc'                 => 'Satura rādītājs',
'showtoc'             => 'parādīt',
'hidetoc'             => 'paslēpt',
'thisisdeleted'       => 'Apskatīt vai atjaunot $1?',
'viewdeleted'         => 'Skatīt $1?',
'restorelink'         => '$1 dzēstās versijas',
'feedlinks'           => 'Barotne:',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Raksts',
'nstab-user'      => 'Lietotāja lapa',
'nstab-media'     => 'Multivides lapa',
'nstab-project'   => 'Projekta lapa',
'nstab-image'     => 'Attēls',
'nstab-mediawiki' => 'paziņojums',
'nstab-template'  => 'Veidne',
'nstab-help'      => 'palīdzība',
'nstab-category'  => 'Kategorija',

# Main script and global functions
'nosuchaction'      => 'Šādas darbības nav.',
'nosuchactiontext'  => 'Wiki neatpazīst URL norādīto darbību',
'nosuchspecialpage' => 'Nav tādas īpašās lapas',
'nospecialpagetext' => 'Tu esi pieprasījis īpašo lapu, ko wiki neatpazīst.',

# General errors
'error'              => 'Kļūda',
'databaseerror'      => 'Datu bāzes kļūda',
'noconnect'          => 'Atvainojiet, šajā wiki ir radušās tehniskas grūtības un nav iespējams savienoties ar datubāžu serveri. <br />
$1',
'cachederror'        => 'Šī ir lapas saglabātā versija, iespējams, ka tā nav atjaunināta.',
'laggedslavemode'    => 'Uzmanību: Iespējams, šajā lapā nav redzami nesen izdarītie papildinājumi.',
'readonly'           => 'Datubāze bloķēta',
'readonlytext'       => 'Datubāze šobrīd ir bloķēta pret jauniem ierakstiem un citām izmaiņām. Visdrīzāk iemesls ir parasts datubāzes uzturēšanas pasākums, pēc kura tā tiks atjaunota normālā stāvoklī. Administrators, kurš nobloķēja datubāzi, norādīja šādu iemeslu:
<p>$1',
'internalerror'      => 'Iekšēja kļūda',
'filecopyerror'      => 'Nav iespējams nokopēt failu "$1" uz "$2"',
'filerenameerror'    => 'Neizdevās pārdēvēt failu "$1" par "$2".',
'filedeleteerror'    => 'Nevar izdzēst failu "$1".',
'filenotfound'       => 'Neizdevās atrast failu "$1".',
'formerror'          => 'Kļūda: neizdevās nosūtīt saturu',
'badarticleerror'    => 'Šo darbību nevar veikt šajā lapā.',
'cannotdelete'       => 'Nevar izdzēst norādīto lapu vai failu. (Iespējams, to jau ir izdzēsis kāds cits)',
'badtitle'           => 'Nepiemērots nosaukums',
'perfcached'         => 'Šie dati ir no servera kešatmiņas un var būt novecojuši:',
'viewsource'         => 'Aplūkot kodu',
'viewsourcefor'      => 'Lapa: $1',
'protectedinterface' => 'Šī lapa satur programmatūras interfeisā lietotu tekstu un ir bloķēta pret izmaiņām, lai pasargātu no bojājumiem.',
'editinginterface'   => "'''Brīdinājums:''' Tu izmaini lapu, kuras saturu izmanto wiki programmatūras lietotāja saskarnē (''interfeisā''). Šīs lapas izmaiņas ietekmēs lietotāja saskarni citiem lietotājiem.",

# Login and logout pages
'logouttitle'                => 'Lietotāja iziešana',
'logouttext'                 => 'Tu esi izgājis no {{grammar:ģenitīvs|{{SITENAME}}}}.
Vari turpināt to izmantot anonīmi, vari atgriezties kā cits lietotājs vai varbūt tas pats. 
Ņem vērā, ka arī pēc iziešanas no {{grammar:ģenitīvs|{{SITENAME}}}} dažas lapas var tikt parādītas tā, it kā tu vēl būtu iekšā, līdz tiks iztīrīta pārlūka kešatmiņa.',
'welcomecreation'            => "== Laipni lūdzam, $1! ==

Tavs lietotāja konts ir izveidots. Neaizmirsti, ka ir iespējams mainīt ''{{grammar:ģenitīvs|{{SITENAME}}}}'' izmantošanas izvēles.",
'loginpagetitle'             => 'Lietotāja ieiešana',
'yourname'                   => 'Tavs lietotājvārds',
'yourpassword'               => 'Tava parole',
'yourpasswordagain'          => 'Atkārto paroli',
'remembermypassword'         => 'Atcerēties manu paroli pēc pārlūka aizvēršanas.',
'yourdomainname'             => 'Tavs domēns',
'externaldberror'            => 'Notikusi vai nu ārējās autentifikācijas datubāzes kļūda, vai arī tev nav atļauts izmainīt savu ārējo kontu.',
'loginproblem'               => '<b>Radās problēma ar ieiešanu.</b><br />Mēģini vēlreiz!',
'login'                      => 'Ieiet',
'loginprompt'                => 'Lai ieietu {{grammar:lokatīvs|{{SITENAME}}}}, tavam datoram ir jāpieņem sīkdatnes (<i>cookies</i>).',
'userlogin'                  => 'Izveidot jaunu lietotāju vai doties iekšā',
'logout'                     => 'Iziet',
'userlogout'                 => 'Iziet',
'notloggedin'                => 'Neesi iegājis',
'nologin'                    => 'Nav lietotājvārda? $1.',
'nologinlink'                => 'Reģistrējies',
'createaccount'              => 'Izveidot jaunu lietotāju',
'gotaccount'                 => 'Tev jau ir lietotājvārds? $1!',
'gotaccountlink'             => 'Dodies iekšā',
'createaccountmail'          => 'pa e-pastu',
'badretype'                  => 'Tevis ievadītās paroles nesakrīt.',
'userexists'                 => 'Šāds lietotāja vārds jau eksistē. Lūdzu izvēlies citu vārdu.',
'youremail'                  => 'Tava e-pasta adrese*',
'username'                   => 'Lietotājvārds:',
'uid'                        => 'Lietotāja ID:',
'yourrealname'               => 'Tavs īstais vārds*',
'yourlanguage'               => 'Lietotāja saskarnes valoda:',
'yournick'                   => 'Tavs paraksts (iesauka):',
'badsig'                     => "Kļūdains ''paraksta'' kods; pārbaudi HTML (ja tāds ir lietots).",
'email'                      => 'E-pasts',
'loginerror'                 => 'Neveiksmīga ieiešana',
'prefs-help-email'           => '* E-pasts (nav obligāti jānorāda): Ļauj citiem sazināties ar tevi, izmantojot tavu lietotāja lapu vai lietotāja diskusiju lapu, tev nekur neatklājot savu identitāti.',
'nocookiesnew'               => 'Lietotājvārds tika izveidots, bet tu neesi iegājis iekšā. {{SITENAME}} izmanto sīkdatnes (<i>cookies</i>), lai lietotāji varētu tajā ieiet. Tavs pārlūks nepieņem tās. Lūdzu, atļauj to pieņemšanu un tad nāc iekšā ar savu lietotājvārdu un paroli.',
'nocookieslogin'             => '{{SITENAME}} izmanto sīkdatnes (<i>cookies</i>), lai lietotāji varētu ieiet tajā. Diemžēl tavs pārlūks tos nepieņem. Lūdzu, atļauj to pieņemšanu un mēģini vēlreiz.',
'noname'                     => 'Tu neesi norādījis derīgu lietotāja vārdu.',
'loginsuccesstitle'          => 'Ieiešana veiksmīga',
'loginsuccess'               => 'Tu esi ienācis {{grammar:lokatīvs|{{SITENAME}}}} kā "$1".',
'nosuchuser'                 => 'Šeit nav lietotāja ar vārdu "$1". Pārbaudi, vai pareizi uzrakstīts, vai arī izveido jaunu kontu.',
'nosuchusershort'            => 'Šeit nav lietotāja ar vārdu "$1". Pārbaudi vai pareizi uzrakstīts.',
'nouserspecified'            => 'Tev jānorāda lietotājvārds.',
'wrongpassword'              => 'Tu ievadīji nepareizu paroli. Lūdzu, mēģini vēlreiz.',
'wrongpasswordempty'         => 'Parole bija tukša. Lūdzu mēģini vēlreiz.',
'passwordtooshort'           => 'Tava parole ir pārāk īsa. Tajā jābūt vismaz $1 zīmēm.',
'mailmypassword'             => 'Atsūtīt man jaunu paroli',
'passwordremindertitle'      => 'Paroles atgadinajums no {{SITENAME}}s',
'passwordremindertext'       => 'Kads (iespejams, Tu pats, no IP adreses $1)
ludza, lai nosutam Tev jaunu {{SITENAME}} ({{SERVER}}) ($4) paroli.
Lietotajam $2 parole tagad ir $3.
Ludzu, nomaini paroli, kad esi veiksmigi iekluvis ieksa.',
'noemail'                    => 'Lietotājs "$1" nav reģistrējis e-pasta adresi.',
'passwordsent'               => 'Esam nosūtījuši jaunu paroli uz e-pasta adresi, kuru ir norādījis lietotājs $1. Lūdzu, nāc iekšā ar jauno paroli, kad būsi to saņēmis.',
'eauthentsent'               => "Apstiprinājuma e-pasts tika nosūtīts uz norādīto e-pasta adresi. Lai varētu saņemt citus ''meilus'', izpildi vēstulē norādītās instrukcijas, lai apstiprinātu, ka šī tiešām ir tava e-pasta adrese.",
'mailerror'                  => 'E-pasta sūtīšanas kļūda: $1',
'acct_creation_throttle_hit' => 'Tu jau esi izveidojis $1 kontus. Vairāk nevar.',
'emailauthenticated'         => 'Tava e-pasta adrese tika apstiprināta $1.',
'emailnotauthenticated'      => 'Tava e-pasta adrese <strong>vēl nav apstiprināta</strong> un zemāk norādītās iespējas nav pieejamas.',
'noemailprefs'               => '<strong>Norādi e-pasta adresi, lai lietotu šīs iespējas.</strong>',
'emailconfirmlink'           => 'Apstiprināt tavu e-pasta adresi',
'invalidemailaddress'        => 'E-pasta adrese nevar tikt apstiprināta, jo izskatās nederīga. Lūdzu ievadi korekti noformētu e-pasta adresi, vai arī atstāj to lauku tukšu.',
'accountcreated'             => 'Konts izveidots',
'accountcreatedtext'         => 'Lietotāja konts priekš $1 tika izveidots.',
'loginlanguagelabel'         => 'Valoda: $1',

# Edit page toolbar
'bold_sample'     => 'Teksts boldā',
'bold_tip'        => 'Teksts boldā',
'italic_sample'   => 'Teksts kursīvā',
'italic_tip'      => 'Teksts kursīvā',
'link_sample'     => 'Lapas nosaukums',
'link_tip'        => 'Iekšējā saite',
'extlink_sample'  => 'http://www.piemers.lv saites apraksts',
'extlink_tip'     => 'Ārējā saite (neaizmirsti sākumā pierakstīt "http://")',
'headline_sample' => 'Virsraksta teksts',
'headline_tip'    => '2. līmeņa virsraksts',
'math_sample'     => 'Šeit ievieto formulu',
'math_tip'        => 'Matemātikas formula (LaTeX)',
'nowiki_sample'   => 'Šeit raksti neformatētu tekstu',
'nowiki_tip'      => 'Ignorēt wiki formatējumu',
'image_sample'    => 'Piemers.jpg',
'image_tip'       => 'Ievietots attēls',
'media_sample'    => 'Piemers.ogg',
'media_tip'       => 'Saite uz multimēdiju failu',
'sig_tip'         => 'Tavs paraksts ar laika atzīmi',
'hr_tip'          => 'Horizontāla līnija (neizmanto lieki)',

# Edit pages
'summary'                  => 'Kopsavilkums',
'subject'                  => 'Tēma/virsraksts',
'minoredit'                => 'maznozīmīgs labojums',
'watchthis'                => 'uzraudzīt',
'savearticle'              => 'Saglabāt lapu',
'preview'                  => 'Pirmskats',
'showpreview'              => 'Rādīt pirmskatu',
'showlivepreview'          => 'Tūlītējs pirmskats',
'showdiff'                 => 'Rādīt izmaiņas',
'anoneditwarning'          => "'''Uzmanību:''' tu neesi iegājis. Lapas hronoloģijā tiks ierakstīta tava IP adrese.",
'missingsummary'           => "'''Atgādinājums''': Tu neesi norādījis izmaiņu kopsavilkumu. Vēlreiz klikšķinot uz \"Saglabāt lapu\", Tavas izmaiņas tiks saglabātas bez kopsavilkuma.",
'missingcommenttext'       => 'Lūdzu, ievadi tekstu zemāk redzamajā logā!',
'blockedtitle'             => 'Lietotājs ir bloķēts.',
'blockedtext'              => '$1 ir nobloķējis tavu lietotāja vārdu vai IP adresi. Iemesls tam ir:<br />\'\'$2\'\'<br />. Tu vari sazināties ar $1 vai kādu citu [[{{MediaWiki:Grouppage-sysop}}|administratoru]] lai apspriestu šo bloku.

Pievērs uzmanību, tam, ka ja tu neesi norādījis derīgu e-pasta adresi [[Special:Preferences|user preferences]], tev nedarbosies "sūtīt e-pastu" iespēja.

Tava IP adrese ir $3. Lūdzu iekļauj to visos turpmākajos pieprasījumos.',
'whitelistedittitle'       => 'Lai varētu rediģēt, šeit jāielogojas.',
'whitelistedittext'        => 'Tev $1 lai varētu rediģēt lapas.',
'whitelistreadtitle'       => 'Jāielogojas, lai varētu lasīt',
'whitelistreadtext'        => 'Tev [[Special:Userlogin|jāielogojas]] lai varētu lasīt lapas.',
'whitelistacctitle'        => 'Tev nav atļauts izveidot kontu',
'loginreqtitle'            => 'Nepieciešama ieiešana',
'loginreqlink'             => 'login',
'accmailtitle'             => 'Parole izsūtīta.',
'accmailtext'              => '$1 parole tika nosūtīta uz $2.',
'newarticle'               => '(Jauns raksts)',
'newarticletext'           => "<div style=\"border: 1px solid #ccc; padding: 7px;\">'''{{grammar:lokatīvs|{{SITENAME}}}} vēl nav tāda {{NAMESPACE}} raksta ar virsrakstu \"{{PAGENAME}}\".'''
* Lai izveidotu šo lapu, raksti tekstu zemāk redzamajā logā. Kad esi pabeidzis, spied pogu \"Saglabāt lapu\". Ja viss būs kārtībā, izmaiņām vajadzētu būt tūlīt redzamām.
* '''Ja esi izveidojis šo lapu dažu pēdējo minūšu laikā un nekas nav parādījies, iespējams, ir aizkavējusies informācijas saglabāšana datubāzē.''' Lūdzam mazliet pagaidīt un tad vēlreiz pārbaudīt - visdrīzāk, pēc kāda brīža lapa būs redzama un nebūs jāraksta viss vēlreiz.
* Ja šis ir raksts (nevis, piemēram, diskusiju lapa), tad
** lūdzam neveidot rakstu, kurā būtu reklamēts vai slavināts tu pats, kāda weblapa, produkts vai uzņēmums (skat. \"[[Project:Kas {{SITENAME}} nav|Kas {{SITENAME}} nav]]\").
** ja tie ir tavi pirmie soļi {{grammar:lokatīvs|{{SITENAME}}}}, lūdzam vispirms izlasīt [[Project:Pamācība|pamācību]] un eksperimentiem izmantot tikai [[Project:Smilšu kaste|smilšu kasti]]. Paldies!
** [[Special:Search/{{PAGENAME}}|spied šeit]], lai meklētu {{grammar:lokatīvs|{{SITENAME}}}} informāciju par jēdzienu \"{{PAGENAME}}\".
</div>",
'anontalkpagetext'         => "----''Šī ir diskusiju lapa anonīmam lietotājam, kurš vēl nav kļuvis par reģistrētu lietotāju vai arī neizmanto savu lietotājvārdu. Tādēļ mums ir jāizmanto skaitliskā [[IP adrese]], lai viņu identificētu. Šāda IP adrese var būt vairākiem lietotājiem. Ja tu esi anonīms lietotājs un uzskati, ka tev ir adresēti neatbilstoši komentāri, lūdzu, [[Special:Userlogin|kļūsti par lietotāju vai arī izmanto jau izveidotu lietotājvārdu]], lai izvairītos no turpmākām neskaidrībām un tu netiktu sajaukts ar citiem anonīmiem lietotājiem.''",
'noarticletext'            => '(Šajā lapā šobrīd nav nekāda teksta)',
'clearyourcache'           => "'''Piezīme:''' Pēc saglabāšanas iztīri pārlūka kešatmiņu, lai pārmaiņas būtu redzamas: Mozilla/Safari/Konqueror: turi nospiestu '''Shift''' un klikšķini '''Reload''' (vai spied '''Ctrl-Shift-r'''), IE: spied '''Ctrl-F5''', Opera: spied '''F5'''.",
'usercssjsyoucanpreview'   => '<strong>Ieteikums:</strong> Lieto pirmsskata pogu, lai pārbaudītu savu jauno CSS/JS pirms saglabāšanas.',
'usercsspreview'           => "'''Atceries, ka šis ir tikai tava lietotāja CSS pirmskats, lapa vēl nav saglabāta!'''",
'userjspreview'            => "'''Atceries, ka šis ir tikai tava lietotāja JavaScript pirmskats/tests, lapa vēl nav saglabāta!'''",
'note'                     => '<strong>Piezīme: </strong>',
'previewnote'              => "'''Atceries, ka šis ir tikai pirmskats un vēl nav saglabāts!'''",
'editing'                  => 'Izmainīt $1',
'editinguser'              => 'Izmainīt $1',
'editingsection'           => 'Izmainīt $1 (sadaļa)',
'editingcomment'           => 'Izmainīt $1 (komentārs)',
'editconflict'             => 'Izmaiņu konflikts: $1',
'explainconflict'          => 'Kāds cits ir izmainījis šo lapu pēc tam, kad tu sāki to mainīt. Augšējā teksta logā ir lapas teksts tā pašreizējā versijā. Tevis veiktās izmaiņas ir redzamas apakšējā teksta logā. Lai saglabātu savas izmaiņas, tev ir jāapvieno savs teksts ar saglabāto pašreizējo variantu. Kad spiedīsi pogu "Saglabāt lapu", tiks saglabāts <b>tikai</b> teksts, kas ir augšējā teksta logā.',
'yourtext'                 => 'Tavs teksts',
'storedversion'            => 'Saglabātā versija',
'editingold'               => '<strong>BRĪDINĀJUMS: Saglabājot šo lapu, tu izmainīsi šīs lapas novecojušu versiju, un ar to tiks dzēstas visas izmaiņas, kas izdarītas pēc šīs versijas.</strong>',
'yourdiff'                 => 'Atšķirības',
'copyrightwarning'         => 'Lūdzu, ņem vērā, ka viss ieguldījums, kas veikts {{grammar:lokatīvs|{{SITENAME}}}}, ir uzskatāms par publiskotu saskaņā ar $2 (vairāk info skat. $1). 
Ja nevēlies, lai Tevis rakstīto kāds rediģē un izplata tālāk, tad, lūdzu, nepievieno to šeit!<br />

Izvēloties "Saglabāt lapu", Tu apliecini, ka šo rakstu esi rakstījis vai papildinājis pats vai izmantojis informāciju no darba, ko neaizsargā autortiesības, vai tamlīdzīga brīvi pieejama resursa.<br />

<strong>BEZ ATĻAUJAS NEPIEVIENO DARBU, KO AIZSARGĀ AUTORTIESĪBAS!</strong>',
'copyrightwarning2'        => "Lūdz ņem vērā, ka visu ieguldījumu {{grammar:lokatīvs|{{SITENAME}}}} var rediģēt, mainīt vai izdzēst citi lietotāji. Ja negribi lai ar tavu rakstīto tā izrīkojas, nepievieno to šeit.

Tu apliecini, ka šo rakstu esi rakstījis vai papildinājis pats vai izmantojis informāciju no darba, ko neaizsargā autortiesības, vai tamlīdzīga brīvi pieejama resursa (sīkāk skatīt $1).

'''BEZ ATĻAUJAS NEPIEVIENO DARBU, KO AIZSARGĀ AUTORTIESĪBAS!'''",
'longpagewarning'          => '<strong>Šī lapa ir $1 kilobaitus liela. Tas var būt vairāk par lapas optimālo izmēru. Lūdzu apsver iespēju sašķelt to mazākās sekcijās.</strong>',
'protectedpagewarning'     => "'''BRĪDINĀJUMS: Šī lapa ir bloķēta pret izmaiņām, tikai lietotāji ar admina privilēģijām var to izmainīt. To darot, noteikti ievēro [[Project:Norādījumi par aizsargātajām lapām|norādījumus par aizsargātajām lapām]].'''",
'semiprotectedpagewarning' => "'''Piezīme:''' Izmaiņu veikšana šajā lapā ir atļauta tikai reģistrētiem lietotājiem.",
'templatesused'            => '<br />Šajā lapā izmantotās veidnes:',

# History pages
'nohistory'           => 'Šai lapai nav pieejama versiju hronoloģija.',
'revnotfound'         => 'Versija nav atrasta',
'revnotfoundtext'     => 'Meklētā vecā lapas versija netika atrasta. Lūdzu pārbaudi lietoto URL.',
'loadhist'            => 'Ielādē lapas hronoloģiju',
'currentrev'          => 'Pašreizējā versija',
'revisionasof'        => 'Versija, kas saglabāta $1',
'previousrevision'    => '←Senāka versija',
'nextrevision'        => 'Jaunāka versija→',
'currentrevisionlink' => 'skatīt pašreizējo versiju',
'cur'                 => 'ar pašreizējo',
'next'                => 'nākamais',
'last'                => 'ar iepriekšējo',
'histlegend'          => 'Atšķirību izvēle: atzīmē vajadzīgo versiju apaļās pogas un spied "Salīdzināt izvēlētās versijas".<br />
Apzīmējumi: 
"ar pašreizējo" = salīdzināt ar pašreizējo versiju,
"ar iepriekšējo" = salīdzināt ar iepriekšējo versiju, 
m = maznozīmīgs labojums.',
'deletedrev'          => '[izdzēsta]',
'histfirst'           => 'Senākās',
'histlast'            => 'Jaunākās',

# Revision feed
'history-feed-title'       => 'Versiju hronoloģija',
'history-feed-description' => 'Šīs wiki lapas versiju hronoloģija',

# Revision deletion
'rev-deleted-comment' => '(komentārs nodzēsts)',
'rev-deleted-user'    => '(lietotāja vārds nodzēsts)',
'rev-delundel'        => 'rādīt/slēpt',

# Diffs
'difference'              => '(Atšķirības starp versijām)',
'lineno'                  => '$1. rindiņa:',
'compareselectedversions' => 'Salīdzināt izvēlētās versijas',

# Search results
'searchresults'         => 'Meklēšanas rezultāti',
'searchresulttext'      => 'Lai iegūtu vairāk informācijas par meklēšanu {{grammar:akuzatīvs|{{SITENAME}}}}, skat. [[{{MediaWiki:Helppage}}|{{grammar:ģenitīvs|{{SITENAME}}}} meklēšana]].',
'searchsubtitle'        => 'Pieprasījums: [[$1]] [[Special:Allpages/$1|&#x5B;Indekss&#x5D;]]',
'searchsubtitleinvalid' => 'Pieprasījums: $1',
'noexactmatch'          => "'''Lapas ar nosaukumu \"\$1\" šeit nav.''' Tu vari to [[:\$1|izveidot]].",
'titlematches'          => 'Rezultāti virsrakstos',
'notitlematches'        => 'Neviena rezultāta, meklējot lapas virsrakstā',
'textmatches'           => 'Rezultāti lapu tekstos',
'notextmatches'         => 'Neviena rezultāta, meklējot lapas tekstā',
'prevn'                 => 'iepriekšējās $1',
'nextn'                 => 'nākamās $1',
'viewprevnext'          => 'Skatīt ($1) ($2) ($3 vienā lapā).',
'showingresults'        => 'Šobrīd ir redzamas <b>$1</b> lapas, sākot ar #<b>$2</b>.',
'showingresultsnum'     => 'Šobrīd ir redzamas <b>$3</b> lapas, sākot ar #<b>$2</b>.',
'nonefound'             => '<strong>Piezīme:</strong> bieži vien meklēšana ir neveiksmīga, meklējot plaši izplatītus vārdus, piemēram, "un" vai "ir", jo tie netiek iekļauti meklēšanas datubāzē, vai arī meklējot vairāk par vienu vārdu (jo rezultātos parādīsies tikai lapas, kurās ir visi meklētie vārdi).',
'powersearch'           => 'Meklēt',
'powersearchtext'       => 'Meklēt šādās palīglapās :<br />
$1<br />
$2 Parādīt pāradresācijas lapas   Meklēt $3 $9',
'searchdisabled'        => '<p style="margin: 1.5em 2em 1em">Meklēšana {{grammar:lokatīvs|{{SITENAME}}}} šobrīd ir atslēgta darbības traucējumu dēļ. Pagaidām vari meklēt, izmantojot Google vai Yahoo.
<span style="font-size: 89%; display: block; margin-left: .2em">Ņem vērā, ka meklētāju indeksētais {{grammar:ģenitīvs|{{SITENAME}}}} saturs var būt novecojis.</span></p>',

# Preferences page
'preferences'           => 'Izvēles',
'mypreferences'         => 'manas izvēles',
'prefsnologin'          => 'Neesi iegājis',
'prefsnologintext'      => 'Tev jābūt [[Special:Userlogin|iegājušam]], lai mainītu lietotāja izvēles.',
'prefsreset'            => 'Sākotnējās izvēles ir atjaunotas.',
'qbsettings'            => 'Rīku joslas stāvoklis',
'changepassword'        => 'Mainīt paroli',
'skin'                  => 'Apdare',
'math'                  => 'Formulas',
'dateformat'            => 'Datuma formāts',
'datedefault'           => 'Vienalga',
'datetime'              => 'Datums un laiks',
'math_unknown_error'    => 'nezināma kļūda',
'math_unknown_function' => 'nezināma funkcija',
'math_syntax_error'     => 'sintakses kļūda',
'prefs-personal'        => 'Lietotāja dati',
'prefs-rc'              => 'Pēdējās izmaiņas',
'prefs-watchlist'       => 'Uzraugāmie raksti',
'prefs-watchlist-days'  => 'Dienu skaits, kuras parādīt uzraugāmo rakstu sarakstā:',
'prefs-watchlist-edits' => 'Izmaiņu skaits, kuras rādīt izvērstajā uzraugāmo rakstu sarakstā:',
'prefs-misc'            => 'Dažādi',
'saveprefs'             => 'Saglabāt izvēles',
'resetprefs'            => 'Atjaunot sākotnējās izvēles',
'oldpassword'           => 'Vecā parole',
'newpassword'           => 'Jaunā parole',
'retypenew'             => 'Atkārto jauno paroli',
'textboxsize'           => 'Rediģēšana',
'rows'                  => 'Rindiņas',
'columns'               => 'Simbolu skaits rindiņā',
'searchresultshead'     => 'Meklēšana',
'resultsperpage'        => 'Lappusē parādāmo rezultātu skaits',
'contextlines'          => 'Cik rindiņas parādīt katram atrastajam rezultātam',
'contextchars'          => 'Konteksta simbolu skaits vienā rindiņā',
'recentchangescount'    => 'Virsrakstu skaits pēdējo izmaiņu lapā',
'savedprefs'            => 'Tavas izvēles ir saglabātas.',
'timezonelegend'        => 'Laika josla',
'timezonetext'          => 'Ieraksti, par cik stundām tavs vietējais laiks atšķiras no servera laika (UTC).',
'localtime'             => 'Attēlotais vietējais laiks',
'timezoneoffset'        => 'Starpība¹',
'servertime'            => 'Servera laiks šobrīd',
'guesstimezone'         => 'Izmantot datora sistēmas laiku',
'allowemail'            => 'Atļaut saņemt e-pastus no citiem lietotājiem.',
'defaultns'             => 'Meklēt šajās palīglapās pēc noklusējuma:',
'default'               => 'pēc noklusējuma',
'files'                 => 'Attēli',

# User rights
'userrights-user-editname' => 'Ievadi lietotājvārdu:',

# Recent changes
'recentchanges'                     => 'Pēdējās izmaiņas',
'recentchangestext'                 => '{{Pēdējās izmaiņas}}',
'rcnote'                            => 'Šobrīd ir redzamas pēdējās <strong>$1</strong> izmaiņas, kas izdarītas {{PLURAL:$2|pēdējā|pēdējās}} <strong>$2</strong> {{PLURAL:$2|dienā|dienās}} (līdz $3).',
'rcnotefrom'                        => 'Šobrīd redzamas izmaiņas kopš <b>$2</b> (parādītas ne vairāk par <b>$1</b>).',
'rclistfrom'                        => 'Parādīt jaunas izmaiņas kopš $1',
'rcshowhideminor'                   => '$1 maznozīmīgus',
'rcshowhidebots'                    => '$1 botus',
'rcshowhideliu'                     => '$1 reģistrētos',
'rcshowhideanons'                   => '$1 anonīmos',
'rcshowhidemine'                    => '$1 manus',
'rclinks'                           => 'Parādīt pēdējās $1 izmaiņas {{PLURAL:$2|pēdējā|pēdējās}} $2 {{PLURAL:$2|dienā|dienās}}.<br />$3',
'diff'                              => 'izmaiņas',
'hist'                              => 'hronoloģija',
'hide'                              => 'paslēpt',
'show'                              => 'parādīt',
'newpageletter'                     => 'J',
'number_of_watching_users_pageview' => '[šo lapu uzrauga $1 {{plural:$1|lietotājs|lietotāji}}]',

# Recent changes linked
'recentchangeslinked' => 'Saistītās izmaiņas',

# Upload
'upload'               => 'Augšuplādēt failu',
'uploadbtn'            => 'Augšuplādēt',
'reupload'             => 'Vēlreiz augšuplādēt',
'reuploaddesc'         => 'Atgriezties pie augšupielādes veidnes.',
'uploadnologin'        => 'Neesi iegājis',
'uploadnologintext'    => 'Tev jābūt [[Special:Userlogin|iegājušam]], lai augšuplādētu failus.',
'uploaderror'          => 'Augšupielādes kļūda',
'uploadtext'           => "'''STOP!''' Pirms tu kaut ko augšupielādē, noteikti izlasi un ievēro [[Project:Attēlu izmantošanas noteikumi|attēlu izmantošanas noteikumus]].

Lai aplūkotu vai meklētu agrāk augšuplādētus attēlus,
dodies uz [[Special:Imagelist|augšupielādēto attēlu sarakstu]].
Augšupielādes un dzēšanas tiek reģistrētas [[Special:Log/upload|augšupielādes reģistrā]].

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
* '''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Fails.jpg|paskaidrojošs teksts]]</nowiki>'''
* '''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Fails.png|paskaidrojošs teksts]]</nowiki>'''
vai skaņām
* '''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Fails.ogg]]</nowiki>'''

Lūdzu, ņem vērā, ka tāpat kā citas wiki lapas arī tevis augšuplādētos failus citi var mainīt vai dzēst, ja uzskata, ka tas nāktu par labu šim projektam, kā arī atceries, ka tev var tikt liegta augšupielādes iespēja, ja tu šo sistēmu.",
'uploadlog'            => 'augšupielādes reģistrs',
'uploadlogpage'        => 'Augšupielādes reģistrs',
'uploadlogpagetext'    => 'Failu augšupielādes reģistrs.',
'filename'             => 'Faila nosaukums',
'filedesc'             => 'Kopsavilkums',
'fileuploadsummary'    => 'Informācija par failu:',
'filestatus'           => 'Autortiesību statuss',
'filesource'           => 'Izejas kods',
'uploadedfiles'        => 'Augšupielādēja failus',
'ignorewarning'        => 'Ignorēt brīdinājumu un saglabāt failu.',
'ignorewarnings'       => 'Ignorēt visus brīdinājumus',
'illegalfilename'      => 'Faila nosaukumā "$1" ir simboli, kas nav atļauti virsrakstos. Lūdzu, pārdēvē failu un mēģini to vēlreiz augšuplādēt.',
'badfilename'          => 'Attēla nosaukums ir nomainīts, tagad tas ir "$1".',
'largefileserver'      => 'Šis fails ir lielāks nekā serveris ņem pretī.',
'emptyfile'            => 'Šķiet, ka tu esi augšuplādējis tukšu failu. Iespējams, faila nosaukumā esi pieļāvis kļūdu. Lūdzu, pārbaudi, vai tiešām tu vēlies augšuplādēt tieši šo failu.',
'fileexists'           => 'Fails ar šādu nosaukumu jau pastāv, lūdzu, pārbaudi $1, ja neesi drošs, ka vēlies to mainīt.',
'fileexists-forbidden' => 'Fails ar šādu nosaukumu jau eksistē, mēģini kādu citu nosaukumu. [[Image:$1|thumb|center|$1]]',
'successfulupload'     => 'Augšupielāde veiksmīga',
'uploadwarning'        => 'Augšupielādes brīdinājums',
'savefile'             => 'Saglabāt failu',
'uploadedimage'        => 'augšupielādēju "$1"',
'uploaddisabled'       => 'Augšupielāde atslēgta',
'uploaddisabledtext'   => 'Falu augšupielāde šajā wiki ir atslēgta.',
'uploadcorrupt'        => 'Šis fails ir bojāts, vai arī tam ir nekorekts paplašinājums. Lūdzu pārbaudi failu un augšupielādē vēlreiz.',
'uploadvirus'          => 'Šis fails satur vīrusu! Sīkāk: $1',
'sourcefilename'       => 'Augšuplādējamais fails',
'destfilename'         => 'Vajadzīgais faila nosaukums',
'watchthisupload'      => 'Uzraudzīt šo lapu',

'license' => 'Licence',

# Image list
'imagelist'                 => 'Attēlu uzskaitījums',
'imagelisttext'             => 'Šobrīd redzams $1 attēlu uzskaitījums, kas sakārtots $2.',
'ilsubmit'                  => 'Meklēt',
'showlast'                  => 'Parādīt pēdējos $1 attēlus, kas sakārtoti $2.',
'byname'                    => '<b>pēc nosaukuma</b>',
'bydate'                    => '<b>pēc datuma</b>',
'bysize'                    => '<b>pēc izmēra</b>',
'imgdelete'                 => 'dzēst',
'imgdesc'                   => 'apraksts',
'imagelinks'                => 'Attēlu saites',
'linkstoimage'              => 'Attēls ir izmantots šajās lapās:',
'nolinkstoimage'            => 'Nevienā lapā nav norāžu uz šo attēlu.',
'sharedupload'              => 'Šis fails ir no *** [[literal]] translation',
'noimage'                   => 'Ar šādu nosaukumu nav neviena faila, tu vari [$1].',
'noimage-linktext'          => 'augšuplādēt to',
'uploadnewversion-linktext' => 'Augšupielādēt jaunu šī faila versiju',

# List redirects
'listredirects' => 'Pāradresāciju uzskaitījums',

# Unused templates
'unusedtemplates'     => 'Neizmantotās veidnes',
'unusedtemplatestext' => 'Šajā lapā ir uzskaitītas visas veidnes, kas nav iekļautas nevienā citā lapā. Pirms dzēšanas jāpārbauda citu veidu saites.',
'unusedtemplateswlh'  => 'citas saites',

# Random page
'randompage' => 'Nejauša lapa',

# Statistics
'statistics'    => 'Statistika',
'sitestats'     => '{{grammar:ģenitīvs|{{SITENAME}}}} statistika',
'userstats'     => 'Statistika par lietotājiem',
'sitestatstext' => "Datubāzē kopā ir '''\$1''' {{plural:\$1|lapa|lapas}}, ieskaitot diskusiju lapas, lapas par {{GRAMMAR:akuzatīvs|{{SITENAME}}}}, nelielas \"aizmetņu\" lapas (''stubs''), pāradresācijas lapas, kā arī citas lapas, kuras, iespējams, nevar nosaukt par pilnvērtīgām satura lapām. Neskaitot iepriekš minētās, {{grammar:lokatīvs|{{SITENAME}}}} ir '''\$2''' {{plural:\$2|lapa|lapas}}, {{plural:\$2|kuru|kuras}} var uzskatīt par pamatsatura {{plural:\$2|lapu|lapām}}.

Augšupielādēti '''\$8''' faili.

Kopš {{grammar:ģenitīvs|{{SITENAME}}}} izveidošanas lapas ir tikušas apskatītas '''\$3''' reizes un lietotāji ir izdarījuši '''\$4''' {{plural:\$4|labojumu|labojumus}} (katra lapa ir labota vidēji '''\$5''' reizes).
Vidēji tas ir '''\$5''' labojumi uz lapu un apskatīšanas/labojumu attiecība ir '''\$6'''.

The [http://meta.wikimedia.org/wiki/Help:Job_queue job queue] length is '''\$7'''.",
'userstatstext' => "Reģistrēto lietotāju skaits ir '''$1'''. No tiem '''$2''' (jeb '''$4%''') ir administratori (skat. $3).",

'disambiguations'     => 'Nozīmju atdalīšanas lapas',
'disambiguationspage' => 'Template:Disambig',

'doubleredirects'     => 'Divkāršas pāradresācijas lapas',
'doubleredirectstext' => 'Katrā rindiņā ir saites uz pirmo un otro pāradresācijas lapu, kā arī pirmā rindiņa no otrās pāradresācijas lapas teksta, kas parasti ir faktiskā "gala" lapa, uz kuru vajadzētu būt saitei pirmajā lapā.',

'brokenredirects'     => 'Kļūdainas pāradresācijas',
'brokenredirectstext' => 'Šīs ir pāradresācijas lapas uz neesošām lapām.',

# Miscellaneous special pages
'nbytes'                  => '$1 baitu',
'ncategories'             => '$1 categories',
'nlinks'                  => '$1 {{PLURAL:$1|saite|saites}}',
'nrevisions'              => '$1 {{PLURAL:$1|versija|versijas}}',
'nviews'                  => '$1 views',
'lonelypages'             => 'Lapas bez saitēm uz tām',
'uncategorizedpages'      => 'Nekategorizētās lapas',
'uncategorizedcategories' => 'Nekategorizētās kategorijas',
'uncategorizedimages'     => 'Nekategorizētie attēli',
'unusedcategories'        => 'Neizmantotas kategorijas',
'unusedimages'            => 'Neizmantoti attēli',
'popularpages'            => 'Populārākās lapas',
'wantedcategories'        => 'Sarkanas kategorijas',
'wantedpages'             => 'Pieprasītās lapas',
'mostlinked'              => 'Lapas, uz kurām ir visvairāk norāžu',
'mostlinkedcategories'    => 'Kategorijas, uz kurām ir visvairāk saišu',
'mostcategories'          => 'Raksti ar visvairāk kategorijām',
'mostimages'              => 'Attēli, uz kuriem ir visvairāk saišu',
'mostrevisions'           => 'Raksti, kuriem ir visvairāk iepriekšēju versiju',
'allpages'                => 'Visas lapas',
'prefixindex'             => 'Meklēt pēc virsraksta pirmajiem burtiem',
'shortpages'              => 'Īsākās lapas',
'longpages'               => 'Garākās lapas',
'deadendpages'            => 'Lapas bez izejošām saitēm',
'listusers'               => 'Lietotāju uzskaitījums',
'specialpages'            => 'Īpašās lapas',
'spheading'               => 'Visiem lietotājiem pieejamās īpašās lapas',
'restrictedpheading'      => 'Ierobežotās īpašās lapas',
'newpages'                => 'Jaunas lapas',
'ancientpages'            => 'Senākās lapas',
'move'                    => 'Pārvietot',
'movethispage'            => 'Pārvietot šo lapu',
'unusedcategoriestext'    => 'Šīs kategorijas eksistē, tomēr nevienā rakstā vai kategorijās tās nav izmantotas.',

# Book sources
'booksources' => 'Grāmatu avoti',

'categoriespagetext' => 'Wiki ir atrodamas šādas kategorijas.',
'version'            => 'Versija',

# Special:Log
'specialloguserlabel'  => 'Lietotājs:',
'speciallogtitlelabel' => 'Virsraksts:',
'log'                  => 'Reģistri',
'alllogstext'          => 'Augšupielādes, dzēšanas, aizsargāšanas, bloķēšanas un adminu reģistru apvienotais reģistrs.
Tu vari sašaurināt aplūkojamo reģistru, izvēloties reģistra veidu, lietotāja vārdu vai reģistrēto lapu.',

# Special:Allpages
'allarticles'    => 'Visi raksti',
'allpagessubmit' => 'Aiziet!',
'allpagesprefix' => 'Parādīt lapas ar šādu virsraksta sākumu:',

# E-mail user
'mailnologin'     => 'Nav adreses, uz kuru sūtīt',
'mailnologintext' => 'Tev jābūt [[Special:Userlogin|iegājušam]], kā arī tev jābūt [[Special:Preferences|norādītai]] derīgai e-pasta adresei, lai sūtītu e-pastu citiem lietotājiem.',
'emailuser'       => 'Sūtīt e-pastu šim lietotājam',
'emailpage'       => 'Sūtīt e-pastu lietotājam',
'emailpagetext'   => 'Ja šis lietotājs ir norādījis reālu e-pasta adresi savu izvēļu lapā, tad ar šo veidni ir iespējams tam nosūtīt e-pastu. Tā e-pasta adrese, kuru tu esi norādījis savā izvēļu lapā, parādīsies e-pasta "From" lauciņā, tādēļ saņēmējs varēs tev atbildēt.',
'defemailsubject' => 'E-pasts par {{grammar:akuzatīvs|{{SITENAME}}}}',
'noemailtitle'    => 'Nav e-pasta adreses',
'noemailtext'     => 'Šis lietotājs nav norādījis derīgu e-pasta adresi vai arī ir izvēlējies nesaņemt e-pastu no citiem lietotājiem.',
'emailfrom'       => 'No',
'emailto'         => 'Kam',
'emailsubject'    => 'Temats',
'emailmessage'    => 'Vēstījums',
'emailsend'       => 'Nosūtīt',
'emailsent'       => 'E-pasts nosūtīts',
'emailsenttext'   => 'Tavs e-pasts ir nosūtīts.',

# Watchlist
'watchlist'            => 'Mani uzraugāmie raksti',
'mywatchlist'          => 'Mani uzraugāmie raksti',
'nowatchlist'          => 'Tavā uzraugāmo rakstu sarakstā nav neviena raksta.',
'watchnologin'         => 'Neesi iegājis',
'watchnologintext'     => 'Tev ir [[Special:Userlogin|jāieiet]], lai mainītu uzraugāmo lapu sarakstu.',
'addedwatch'           => 'Pievienots uzraugāmo sarakstam.',
'addedwatchtext'       => "Lapa \"\$1\" ir pievienota [[Special:Watchlist|tevis uzraudzītajām lapām]], kur tiks parādītas izmaiņas, kas izdarītas šajā lapā vai šīs lapas diskusiju lapā, kā arī šī lapa tiks iezīmēta '''pustrekna''' [[Special:Recentchanges|pēdējo izmaiņu lapā]], lai to būtu vieglāk pamanīt.

Ja vēlāk pārdomāsi un nevēlēsies vairs uzraudzīt šo lapu, klikšķini uz saites '''neuzraudzīt''' rīku joslā.",
'removedwatch'         => 'Lapa vairs netiek uzraudzīta',
'removedwatchtext'     => 'Lapa "$1" ir izņemta no tava uzraugāmo lapu saraksta.',
'watch'                => 'Uzraudzīt',
'watchthispage'        => 'Uzraudzīt šo lapu',
'unwatch'              => 'Neuzraudzīt',
'unwatchthispage'      => 'Pārtraukt uzraudzīšanu',
'watchnochange'        => 'Neviena no tevis uzraudzītajām lapām nav mainīta parādītajā laika posmā.',
'watchlist-details'    => '(Tu uzraugi $1 lapas, neieskaitot diskusiju lapas.',
'watchlistcontains'    => 'Tavā uzraugāmo lapu sarakstā ir $1 {{PLURAL:$1|lapa|lapas}}.',
'wlshowlast'           => 'Parādīt izmaiņas pēdējo $1 stundu laikā vai $2 dienu laikā, vai arī $3.',
'watchlist-show-bots'  => 'Parādīt botu izmaiņas',
'watchlist-hide-bots'  => 'Paslēpt botu izmaiņas',
'watchlist-show-own'   => 'Parādīt manas izmaiņas',
'watchlist-hide-own'   => 'Paslēpt manas izmaiņas',
'watchlist-show-minor' => 'Parādīt maznozīmīgās izmaiņas',
'watchlist-hide-minor' => 'Paslēpt maznozīmīgās izmaiņas',

# Displayed when you click the "watch" button and it's in the process of watching
'watching' => 'Uzrauga...',

# Delete/protect/revert
'deletepage'         => 'Dzēst lapu',
'confirm'            => 'Apstiprināt',
'excontent'          => "lapas saturs bija: '$1'",
'excontentauthor'    => 'saturs bija: "$1" (vienīgais autors: [[Special:Contributions/$2|$2]])',
'exbeforeblank'      => "lapas saturs pirms satura dzēšanas bija šāds: '$1'",
'exblank'            => 'lapa bija tukša',
'confirmdelete'      => 'Apstiprināt dzēšanu',
'deletesub'          => '(Dzēst "$1")',
'historywarning'     => 'Brīdinājums: Tu dzēsīsi lapu, kurai ir saglabātas iepriekšējas versijas.',
'confirmdeletetext'  => 'Tu tūlīt no datubāzes dzēsīsi lapu vai attēlu, kā arī to iepriekšējās versijas. Lūdzu, apstiprini, ka tu tiešām to vēlies darīt, ka tu apzinies sekas un ka tu to dari saskaņā ar [[Project:Vadlīnijas|vadlīnijām]].',
'deletedtext'        => 'Lapa "$1" ir izdzēsta.
Šeit var apskatīties pēdējos izdzēstos: "$2".',
'deletedarticle'     => 'izdzēsu "$1"',
'dellogpage'         => 'Dzēšanas reģistrs',
'dellogpagetext'     => 'Šajā lapā ir pēdējo dzēsto lapu saraksts.',
'deletionlog'        => 'dzēšanas reģistrs',
'reverted'           => 'Atjaunots uz iepriekšējo versiju',
'deletecomment'      => 'Dzēšanas iemesls',
'rollback'           => 'Novērst labojumus',
'rollback_short'     => 'Novērst',
'rollbacklink'       => 'novērst',
'rollbackfailed'     => 'Novēršana neizdevās',
'cantrollback'       => 'Nav iespējams novērst labojumu; iepriekšējais labotājs ir vienīgais lapas autors.',
'alreadyrolled'      => 'Nav iespējams novērst pēdējās izmaiņas, ko lapā [[$1]] saglabāja [[User:$2|$2]] ([[User talk:$2|Diskusija]]). Kāds cits jau ir rediģējis šo lapu vai novērsis izmaiņas.

Pēdējās izmaiņas saglabāja [[User:$3|$3]] ([[User talk:$3|diskusija]])',
'revertpage'         => 'Novērsu izmaiņas, ko izdarīja [[Special:Contributions/$2|$2]], atjaunoju versiju, ko saglabāja $1',
'sessionfailure'     => "Ir radusies problēma ar sesijas autentifikāciju;
šī darbība ir atcelta, lai novērstu lietotājvārda iespējami ļaunprātīgu izmantošanu.
Lūdzu, spied \"''back''\" un atjaunini iepriekšējo lapu. Tad mēģini vēlreiz.",
'protectlogpage'     => 'Aizsargāšanas reģistrs',
'protectedarticle'   => 'aizsargāja $1',
'unprotectedarticle' => 'atcēla aizsardzību: $1',
'protectsub'         => '(Aizsargāt "$1"?)',
'confirmprotect'     => 'Apstiprināt aizsargāšanu',
'protectcomment'     => 'Aizsargāšanas iemesls',
'unprotectsub'       => '(Neaizsargāt "$1"?)',

# Undelete
'undelete'           => 'Atjaunot dzēstu lapu',
'undeletepage'       => 'Skatīt un atjaunot dzēstās lapas',
'undeletepagetext'   => 'Šīs lapas ir dzēstas, bet ir saglabātas arhīvā. Tās ir iespējams atjaunot, bet ņemiet vērā, ka arhīvs reizēm tiek tīrīts.',
'undeleterevisions'  => '$1 {{PLURAL:$1|versija|versijas}} {{PLURAL:$1|arhivēta|arhivētas}}',
'undeletehistory'    => 'Ja tu atjauno lapu, visas versijas tiks atjaunotas tās hronoloģijā.
Ja pēc dzēšanas ir izveidota jauna lapa ar tādu pašu nosaukumu, atjaunotās versijas tiks ievietotas lapas hronoloģijā attiecīgā secībā un konkrētās lapas pašreizējā versija netiks automātiski nomainīta.',
'undeletebtn'        => 'Atjaunot!',
'undeletedarticle'   => 'atjaunoju "$1"',
'undeletedrevisions' => '$1 {{PLURAL:$1|versija|versijas}} {{PLURAL:$1|atjaunota|atjaunotas}}',

# Namespace form on various pages
'namespace'      => 'Lapas veids:',
'invert'         => 'Izvēlēties pretēji',
'blanknamespace' => '(Pamatlapa)',

# Contributions
'contributions' => 'Lietotāja devums',
'mycontris'     => 'Mans devums',
'contribsub2'   => 'Lietotājs: $1 ($2)',
'nocontribs'    => 'Netika atrastas izmaiņas, kas atbilstu šiem kritērijiem.',
'uctop'         => '(pēdējā izmaiņa)',

'sp-contributions-username' => 'IP adrese vai lietotāja vārds:',

'sp-newimages-showfrom' => 'Rādīt jaunos attēlus sākot no $1',

# What links here
'whatlinkshere'      => 'Norādes uz šo rakstu',
'linklistsub'        => '(Saišu uzskaitījums)',
'linkshere'          => 'Šajās lapās ir norādes uz šo lapu:',
'nolinkshere'        => 'Nevienā lapā nav norāžu uz šo lapu.',
'isredirect'         => 'pāradresācijas lapa',
'istemplate'         => 'izsaukts',
'whatlinkshere-prev' => '{{PLURAL:$1|iepriekšējo|iepriekšējos $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|nākamo|nākamos $1}}',

# Block/unblock
'blockip'            => 'Bloķēt lietotāju',
'blockiptext'        => 'Šo veidni izmanto, lai bloķētu kādas IP adreses vai lietotājvārda piekļuvi wiki lapu saglabāšanai. Dari to tikai, lai novērstu vandālismu atbilstoši [[Project:Vadlīnijas|noteikumiem]].
Norādi konkrētu iemeslu (piemēram, linkus uz vandalizētajām lapām).',
'ipaddress'          => 'IP adrese/lietotājvārds',
'ipadressorusername' => 'IP adrese vai lietotājvārds',
'ipbexpiry'          => 'Termiņš',
'ipbreason'          => 'Iemesls',
'ipbreasonotherlist' => 'Cits iemesls',
'ipbreason-dropdown' => '*Biežākie bloķēšanas iemesli
** Ievieto nepatiesu informāciju
** Dzēš lapu saturu
** Spamo ārējās saitēs
** Ievieto nesakarīgus simbolus sakopojumus',
'ipbsubmit'          => 'Bloķēt šo lietotāju',
'ipbother'           => 'Cits laiks',
'ipboptions'         => '2 stundas:2 hours,1 diena:1 day,3 dienas:3 days,1 nedēļa:1 week,2 nedēļas:2 weeks,1 mēnesis:1 month,3 mēneši:3 months,6 mēneši:6 months,1 gads:1 year,uz nenoteiktu laiku:infinite', # display1:time1,display2:time2,...
'ipbotheroption'     => 'cits',
'badipaddress'       => 'Nederīga IP adrese',
'blockipsuccesssub'  => 'Nobloķēts veiksmīgi',
'ipblocklist'        => 'Bloķēto IP adrešu un lietotājvārdu uzskaitījums',
'blocklistline'      => '$1 $2 bloķēja $3 (termiņš $4)',
'expiringblock'      => 'beidzas $1',
'blocklink'          => 'bloķēt',
'unblocklink'        => 'atbloķēt',
'contribslink'       => 'devums',
'blocklogpage'       => 'Bloķēšanas reģistrs',
'ipb_expiry_invalid' => 'Nederīgs beigu termiņš',
'ip_range_invalid'   => 'Nederīgs IP diapazons',
'proxyblocker'       => 'Starpniekservera bloķētājs',
'proxyblocksuccess'  => 'Darīts.',

# Move page
'movepage'         => 'Pārvietot lapu',
'movepagetext'     => "Šajā lapā tu vari pārdēvēt vai pārvietot lapu, kopā tās izmaiņu hronoloģiju pārvietojot to uz citu nosaukumu.
Iepriekšējā lapa kļūs par lapu, kas pāradresēs uz jauno lapu.
Saites uz iepriekšējo lapu netiks mainītas, bet noteikti pārbaudi un izlabo, izskaužot dubultu pāradresāciju vai pāradresāciju uz neesošu lapu.
Tev ir jāpārliecinās, vai saites vēl aizvien ved tur, kur tās ir paredzētas.

Ņem vērā, ka lapa '''netiks''' pārvietota, ja jau eksistē kāda cita lapa ar vēlamo nosaukumu (izņemot gadījumus, kad tā ir tukša vai kad tā ir pāradresācijas lapa, kā arī tad, ja tai nav izmaiņu hronoloģijas). Tas nozīmē, ka tu vari pārvietot lapu atpakaļ, no kurienes tu jau reiz to esi pārvietojis, ja būsi kļūdījies, bet tu nevari pārrakstīt jau esošu lapu.

<b>BRĪDINĀJUMS!</b>
Populārām lapām tā var būt krasa un negaidīta pārmaiņa; pirms turpināšanas vēlreiz pārdomā, vai tu izproti visas iespējamās sekas.",
'movepagetalktext' => "Saistītā diskusiju lapa, ja tāda eksistē, tiks automātiski pārvietota, '''izņemot gadījumus, kad''':
*tu pārvieto lapu uz citu palīglapu,
*ar jauno nosaukumu jau eksistē diskusiju lapa, vai arī
*atzīmēsi zemāk atrodamo lauciņu.

Ja tomēr vēlēsies, tad tev šī diskusiju lapa būs jāpārvieto vai jāapvieno pašam.",
'movearticle'      => 'Pārvietot lapu',
'movenologin'      => 'Neesi iegājis kā reģistrēts lietotājs',
'movenologintext'  => 'Tev ir jābūt reģistrētam lietotājam un jābūt [[Special:Userlogin|iegājušam]] {{grammar:lokatīvs|{{SITENAME}}}}, lai pārvietotu lapu.',
'newtitle'         => 'Uz šādu lapu',
'move-watch'       => 'Uzraudzīt šo lapu',
'movepagebtn'      => 'Pārvietot lapu',
'pagemovedsub'     => 'Pārvietošana notikusi veiksmīgi',
'articleexists'    => 'Lapa ar tādu nosaukumu jau pastāv vai arī tevis izvēlētais nosaukums ir nederīgs. Lūdzu, izvēlies citu nosaukumu.',
'movedto'          => 'pārvietota uz',
'movetalk'         => 'Pārvietot arī diskusiju lapu, ja tāda ir.',
'talkpagemoved'    => 'Tika pārvietota arī atbilstošā diskusiju lapa.',
'talkpagenotmoved' => 'Atbilstošā diskusiju lapa <strong>netika</strong> pārvietota.',
'1movedto2'        => '"[[$1]]" pārdēvēju par "[[$2]]"',
'1movedto2_redir'  => '$1 pārdēvēju par $2, izmantojot pāradresāciju',
'movelogpage'      => 'Pārvietošanas reģistrs',
'movelogpagetext'  => 'Lapu pārvietošanas (pārdēvēšanas) reģistrs.',
'movereason'       => 'Iemesls',
'revertmove'       => 'atcelt',

# Export
'export' => 'Eksportēt lapas',

# Namespace 8 related
'allmessages'               => 'Visi sistēmas paziņojumi',
'allmessagesname'           => 'Nosaukums',
'allmessagesdefault'        => 'Sākotnējais teksts',
'allmessagescurrent'        => 'Pašreizējais teksts',
'allmessagestext'           => "Šajā lapā ir visu \"'''Mediawiki:'''\" lapās atrodamo sistēmas paziņojumu uzskaitījums.",
'allmessagesnotsupportedDB' => '{{ns:special}}:Allmessages not supported because wgUseDatabaseMessages is off.',

# Thumbnails
'thumbnail-more' => 'Palielināt',
'missingimage'   => '<b>Trūkst attēla</b><br /><i>$1</i>',
'filemissing'    => 'Trūkst faila',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Mana lietotāja lapa',
'tooltip-pt-anonuserpage'         => 'Manas IP adreses lietotāja lapa',
'tooltip-pt-mytalk'               => 'Mana diskusiju lapa',
'tooltip-pt-anontalk'             => 'Diskusija par labojumiem, kas izdarīti no šīs IP adreses',
'tooltip-pt-preferences'          => 'Manas izvēles',
'tooltip-pt-watchlist'            => 'Manis uzraudzītās lapas.',
'tooltip-pt-mycontris'            => 'Mani ieguldījumi',
'tooltip-pt-login'                => 'Aicinām tevi ieiet {{grammar:lokatīvs|{{SITENAME}}}}, tomēr tas nav obligāti.',
'tooltip-pt-anonlogin'            => 'Aicinām tevi ieiet {{grammar:lokatīvs|{{SITENAME}}}}, tomēr tas nav obligāti.',
'tooltip-pt-logout'               => 'Iziet',
'tooltip-ca-talk'                 => 'Diskusija par šī raksta lapu',
'tooltip-ca-edit'                 => 'Izmainīt šo lapu. Lūdzam izmantot pirmskatu pirms lapas saglabāšanas.',
'tooltip-ca-addsection'           => 'Pievienot komentāru šai diskusijai.',
'tooltip-ca-viewsource'           => 'Šī lapa ir aizsargāta. Tu vari apskatīties tās izejas kodu.',
'tooltip-ca-history'              => 'Šīs lapas iepriekšējās versijas.',
'tooltip-ca-protect'              => 'Aizsargāt šo lapu',
'tooltip-ca-delete'               => 'Dzēst šo lapu',
'tooltip-ca-undelete'             => 'Atjaunot labojumus, kas izdarīti šajā lapā pirms lapas dzēšanas.',
'tooltip-ca-move'                 => 'Pārvietot šo lapu',
'tooltip-ca-watch'                => 'Pievienot šo lapu manis uzraudzītajām lapām',
'tooltip-ca-unwatch'              => 'Izņemt šo lapu no uzraudzītajām lapām',
'tooltip-search'                  => 'Meklēt šajā wiki',
'tooltip-p-logo'                  => 'Sākumlapa',
'tooltip-n-mainpage'              => 'Iet uz sākumlapu',
'tooltip-n-portal'                => 'Par šo projektu, par to, ko tu vari šeit darīt un kur ko atrast',
'tooltip-n-currentevents'         => 'Uzzini papildinformāciju par šobrīd aktuālajiem notikumiem',
'tooltip-n-recentchanges'         => 'Izmaiņas, kas nesen izdarītas šajā wiki.',
'tooltip-n-randompage'            => 'Iet uz nejauši izvēlētu lapu',
'tooltip-n-help'                  => 'Vieta, kur uzzināt.',
'tooltip-n-sitesupport'           => 'Atbalsti mūs',
'tooltip-t-whatlinkshere'         => 'Visas wiki lapas, kurās ir saites uz šejieni',
'tooltip-t-recentchangeslinked'   => 'Izmaiņas, kas nesen izdarītas lapās, kurās ir saites uz šo lapu',
'tooltip-feed-rss'                => 'Šīs lapas RSS barotne',
'tooltip-feed-atom'               => 'Šīs lapas Atom barotne',
'tooltip-t-contributions'         => 'Apskatīt šā lietotāja ieguldījumu uzskaitījumu.',
'tooltip-t-emailuser'             => 'Sūtīt e-pastu šim lietotājam',
'tooltip-t-upload'                => 'Augšuplādēt attēlus vai multimēdiju failus',
'tooltip-t-specialpages'          => 'Visu īpašo lapu uzskaitījums',
'tooltip-ca-nstab-main'           => 'Apskatīt rakstu',
'tooltip-ca-nstab-user'           => 'Apskatīt lietotāja lapu',
'tooltip-ca-nstab-media'          => 'Apskatīt multimēdiju lapu',
'tooltip-ca-nstab-special'        => 'Šī ir īpašā lapa, tu nevari izmainīt pašu lapu.',
'tooltip-ca-nstab-project'        => 'Apskatīt projekta lapu',
'tooltip-ca-nstab-image'          => 'Apskatīt attēla lapu',
'tooltip-ca-nstab-mediawiki'      => 'Apskatīt sistēmas paziņojumu',
'tooltip-ca-nstab-template'       => 'Apskatīt veidni',
'tooltip-ca-nstab-help'           => 'Apskatīt palīdzības lapu',
'tooltip-ca-nstab-category'       => 'Apskatīt kategorijas lapu',
'tooltip-minoredit'               => 'Atzīmēt šo par maznozīmīgu labojumu',
'tooltip-save'                    => 'Saglabāt veiktās izmaiņas',
'tooltip-preview'                 => 'Parādīt izmaiņu priekšskatījumu. Lūdzam izmantot šo iespēju pirms saglabāšanas.',
'tooltip-diff'                    => 'Parādīt, kā esi izmainījis tekstu.',
'tooltip-compareselectedversions' => 'Aplūkot atšķirības starp divām izvēlētajām lapas versijām.',
'tooltip-watch'                   => 'Pievienot šo lapu uzraugāmo lapu sarakstam',

# Attribution
'anonymous' => 'Anonīmie {{grammar:ģenitīvs|{{SITENAME}}}} lietotāji(s)',
'siteuser'  => '{{grammar:ģenitīvs|{{SITENAME}}}} lietotājs $1',
'and'       => 'un',

# Spam protection
'subcategorycount'       => 'Šajā kategorijā ir $1 {{PLURAL:$1|apakškategorija|apakškategorijas}}.',
'categoryarticlecount'   => 'Šajā kategorijā ir $1 {{PLURAL:$1|raksts|raksti}}.',
'listingcontinuesabbrev' => ' (turpinājums)',

# Math options
'mw_math_png'    => 'Vienmēr attēlot PNG',
'mw_math_simple' => 'HTML, ja ļoti vienkārši, vai arī PNG',
'mw_math_html'   => 'HTML, ja iespējams, vai arī PNG',
'mw_math_source' => 'Saglabāt kā TeX (teksta pārlūkiem)',
'mw_math_modern' => 'Moderniem pārlūkiem ieteiktais variants',
'mw_math_mathml' => 'MathML, ja iespējams (eksperimentāla iespēja)',

# Browsing diffs
'previousdiff' => '← Salīdzināt ar iepriekšējo versiju',
'nextdiff'     => 'Salīdzināt ar nākamo versiju →',

# Media information
'imagemaxsize' => 'Attēlu apraksta lappusēs parādāmo attēlu maksimālais izmērs:',
'thumbsize'    => 'Sīkbildes (<i>thumbnail</i>) izmērs:',

# Special:Newimages
'newimages'    => 'Jauno attēlu galerija',
'showhidebots' => '($1 botus)',
'noimages'     => 'Nav nekā ko redzēt.',

# Metadata
'metadata-expand'   => 'Parādīt papildu detaļas',
'metadata-collapse' => 'Paslēpt papildu detaļas',

# EXIF tags
'exif-imagewidth'       => 'platums',
'exif-imagelength'      => 'augstums',
'exif-bitspersample'    => 'biti komponentē',
'exif-compression'      => 'Saspiešanas veids',
'exif-xresolution'      => 'Horizontālā izšķirtspēja',
'exif-yresolution'      => 'Vertikālā izšķirtspēja',
'exif-resolutionunit'   => 'X un Y izšķirtspējas mērvienība',
'exif-make'             => 'Fotoaparāta ražotājs',
'exif-exifversion'      => 'EXIF versija',
'exif-pixelxdimension'  => 'Valind image height',
'exif-datetimeoriginal' => 'Izveidošanas datums un laiks',
'exif-gpslatituderef'   => 'Ziemeļu vai dienvidu platums',
'exif-gpslatitude'      => 'Platums',
'exif-gpslongituderef'  => 'Austrumu vai rietumu garums',
'exif-gpslongitude'     => 'Garums',
'exif-gpsaltitude'      => 'Augstums',

# External editor support
'edit-externally'      => 'Izmainīt šo failu ar ārēju programmu',
'edit-externally-help' => 'Skat. [http://meta.wikimedia.org/wiki/Help:External_editors instrukcijas] Meta-Wiki, lai iegūtu vairāk informācijas.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'visi',
'imagelistall'     => 'visas',
'watchlistall2'    => 'visas',
'namespacesall'    => 'visas',

# E-mail address confirmation
'confirmemail'            => 'Apstiprini e-pasta adresi',
'confirmemail_text'       => 'Šajā wiki ir nepieciešams apstiprināt savu e-pasta adresi, lai izmantotu e-pasta funkcijas. Spied uz zemāk esošās pogas, lai uz tavu e-pasta adresi nosūtītu apstiprināšanas e-pastu. Tajā būs saite ar kodu; spied uz tās saites vai atver to savā interneta pārlūkā, lai apstiprinātu tavas e-pasta adreses derīgumu.',
'confirmemail_send'       => 'Nosūtīt apstiprināšanas kodu',
'confirmemail_sent'       => 'Apstiprināšanas e-pasts nosūtīts.',
'confirmemail_sendfailed' => 'Nevarējām nosūtīt apstiprināšanas e-pastu. Pārbaudi, vai adresē nav kāds nepareizs simbols.',
'confirmemail_invalid'    => 'Nederīgs apstiprināšanas kods. Iespējams, beidzies tā termiņš.',
'confirmemail_success'    => 'Tava e-pasta adrese ir apstiprināta. Tagad vari doties iekšā ar savu lietotājvārdu un pilnvērtīgi izmantot wiki iespējas.',
'confirmemail_loggedin'   => 'Tava e-pasta adrese tagad ir apstiprināta.',
'confirmemail_error'      => 'Notikusi kāda kļūme ar tava apstiprinājuma saglabāšanu.',
'confirmemail_subject'    => 'E-pasta adreses apstiprinajums no {{grammar:ģenitīvs|{{SITENAME}}}}',
'confirmemail_body'       => 'Kads, iespejams, tu pats, no IP adreses $1 ir registrejis {{grammar:ģenitīvs|{{SITENAME}}}} lietotaja vardu "$2" ar so e-pasta adresi.

Lai apstiprinatu, ka so lietotaja vardu esi izveidojis tu pats, un aktivizetu e-pasta izmantosanu {{SITENAME}}, atver so saiti sava interneta parluka:

$3

Ja tu *neesi* registrejis sadu lietotaja vardu, nespied uz saites. Si apstiprinajuma kods deriguma termins ir $4.',

# Scary transclusion
'scarytranscludedisabled' => '[Starpviki saišu iekļaušana ir atspējota.]',
'scarytranscludefailed'   => '[Atvaino, neizdevās ienest veidni $1.]',
'scarytranscludetoolong'  => '[Atvaino, URL adrese ir pārāk gara.]',

# AJAX search
'searchcontaining' => "Meklēt rakstus, kas satur ''$1''.",
'searchnamed'      => "Meklēt rakstus ar nosaukumu ''$1''.",
'articletitles'    => "Raksti, kas sākas ar ''$1''",
'hideresults'      => 'Paslēpt rezultātus',

# Auto-summaries
'autoredircomment' => 'Pāradresē uz [[$1]]',
'autosumm-new'     => 'Jauna lapa: $1',

);
