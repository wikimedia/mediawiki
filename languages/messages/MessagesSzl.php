<?php
/** Silesian (Ślůnski)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Britscher
 * @author Danny B.
 * @author Djpalar
 * @author Gaj777
 * @author Herr Kriss
 * @author Lajsikonik
 * @author Leinad
 * @author Ozi64
 * @author Pimke
 * @author Przemub
 * @author Tchoř
 * @author Timpul
 */

$fallback = 'pl';

$messages = array(
# User preference toggles
'tog-underline'               => 'Podsztrychńyńcy linkůw:',
'tog-highlightbroken'         => 'Uoznocz <a href="" class="new">tak</a> linki do zajtůw kere ńy trefjům (abo: dołůncz pytajńik<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Wyrůwnowej tekst we akapitach (justowańy)',
'tog-hideminor'               => 'Schow drobne pomjyńańa we ńydowno pomjyńanych',
'tog-hidepatrolled'           => 'Schow sprowdzůne sprowjyńa we ńydowno pomjyńanych',
'tog-newpageshidepatrolled'   => 'Schow sprowdzůne zajty na wykoźe nowych zajtůw',
'tog-extendwatchlist'         => 'Pokoż na mojij pozůrliśće wszyjske, a ńyjyno uostatńe sprowjyńa',
'tog-usenewrc'                => 'Używej poszyrzyńo ńydowno pomjyńanych (JavaScript)',
'tog-numberheadings'          => 'Automatyczno numeracyjo titlůw',
'tog-showtoolbar'             => 'Pokoż gurt werkcojgůw (JavaScript)',
'tog-editondblclick'          => 'Edycja napoczynajům dwa klikńyńća (JavaScript)',
'tog-editsection'             => 'Kożdo tajla zajty sprowjano uosobno',
'tog-editsectiononrightclick' => 'Klikńyńće prawym kneflym myszy na titlu tajli<br />napoczyno jego sprowjańy(JavaScript)',
'tog-showtoc'                 => 'Pokoż spis treśći (na zajtach, kere majům wjyncy jak trzi tajle)',
'tog-rememberpassword'        => 'Pamjyntej můj ausdruk na tym kůmputrze (nojdalij bez $1 {{PLURAL:$1|dźyń|dńůw}})',
'tog-watchcreations'          => 'Dowům pozůr na zajty, kere żech naszkryfloł',
'tog-watchdefault'            => 'Dowům pozůr na zajty, kere żech sprowjoł',
'tog-watchmoves'              => 'Dowům pozůr na zajty, kere żech przećepnył',
'tog-watchdeletion'           => 'Dowům pozůr na zajty, kere żech wyćepnył',
'tog-minordefault'            => 'Kożde moje sprowjańy je ńywjelge',
'tog-previewontop'            => 'Uobźyrej przed placym sprowjańo',
'tog-previewonfirst'          => 'Obźyrej zajta przi pjyrszym sprowjańu',
'tog-nocache'                 => 'Wypńij podrynczno pamjyńć',
'tog-enotifwatchlistpages'    => 'Wyślij e-brifa, kej ftoś zmjyńi zajta, na kero dowom pozůr',
'tog-enotifusertalkpages'     => 'Wyślij e-brifa, kej zajta mojij godki bydźe pomjyńono',
'tog-enotifminoredits'        => 'Wyślij e-brifa tyż, kej by szło uo drobne pomjyńańa',
'tog-enotifrevealaddr'        => 'Ńy chow mojigo e-brifa we powjadomjyńach',
'tog-shownumberswatching'     => 'Pokoż, wjela sprowjorzy dowo pozůr',
'tog-oldsig'                  => 'Teroźni wyglůnd Twojygo szrajbowańo',
'tog-fancysig'                => 'Szrajbńij s kodůma wiki (bez autůmatycznygo linka)',
'tog-externaleditor'          => 'Sztandardowo używej zewnyntrzny edytor (jyno do ekspertůw, trza mjyć ekstra sztalowańy we systymje)',
'tog-externaldiff'            => 'Sztandardowo używej zewnyntrzny program do filowańo we pomjyńańach (jyno do ekspertůw, trza mjyć ekstra sztalowańy we systymje)',
'tog-showjumplinks'           => 'Zapńij cajchnůndzki "przyńdź do"',
'tog-uselivepreview'          => 'Używej dynamiczne uobźyrańy (JavaScript) (eksperymentalny)',
'tog-forceeditsummary'        => 'Pedź, kejbych ńic ńy naszkryfloł we uopiśe pomjyńań',
'tog-watchlisthideown'        => 'Schow moje pomjyńańa we artiklach, na kere dowom pozůr',
'tog-watchlisthidebots'       => 'Schow pomjyńańa sprowjone bez boty we artiklach, na kere dowom pozůr',
'tog-watchlisthideminor'      => 'Schow ńywjelge pomjyńańa w artiklach, na kere dowom pozůr',
'tog-watchlisthideliu'        => 'Schow sprowjyńo zalůgowanych sprowjaczy na pozorliśće',
'tog-watchlisthideanons'      => 'Schow sprowjyńa anůńimowych sprowjoczy na liśće artikli, na kere dowom pozůr',
'tog-watchlisthidepatrolled'  => 'Schowej sprowdzůne sprowjyńa na pozorliśće',
'tog-ccmeonemails'            => 'Przesyłej mi kopje e-brifůw co żech je posłoł inkszym sprowjaczom',
'tog-diffonly'                => 'Ńy pokozuj treśći zajtůw půnižyj porůwnańo pomjyńań',
'tog-showhiddencats'          => 'Pokoż schowane kategoryje',
'tog-norollbackdiff'          => 'Uomiń pokozywańy pomjyńań po użyću funkcyje „cofej”',

'underline-always'  => 'Dycki',
'underline-never'   => 'Ńigdy',
'underline-default' => 'Podug sztalowańo uoglůndarki',

# Font style option in Special:Preferences
'editfont-style'     => 'Styl czćůnki we placu sprowjyń:',
'editfont-default'   => 'Domyślno przeglůndarki',
'editfont-monospace' => 'Monotypowe krojło',
'editfont-sansserif' => 'Bezszeryfowe krojło',
'editfont-serif'     => 'Szeryfowe krojło',

# Dates
'sunday'        => 'Ńydźela',
'monday'        => 'Pyńdźołek',
'tuesday'       => 'Wtorek',
'wednesday'     => 'Strzoda',
'thursday'      => 'Sztwortek',
'friday'        => 'Pjůntek',
'saturday'      => 'Sobota',
'sun'           => 'Ńyd',
'mon'           => 'Pyń',
'tue'           => 'Wto',
'wed'           => 'Str',
'thu'           => 'Szt',
'fri'           => 'Pjů',
'sat'           => 'Sob',
'january'       => 'styczyń',
'february'      => 'luty',
'march'         => 'merc',
'april'         => 'kwjećyń',
'may_long'      => 'moj',
'june'          => 'czyrwjyń',
'july'          => 'lipjyń',
'august'        => 'śyrpjyń',
'september'     => 'wrześyń',
'october'       => 'paźdźerńik',
'november'      => 'listopad',
'december'      => 'grudźyń',
'january-gen'   => 'styczńa',
'february-gen'  => 'lutygo',
'march-gen'     => 'merca',
'april-gen'     => 'kwjetńa',
'may-gen'       => 'maja',
'june-gen'      => 'czyrwńa',
'july-gen'      => 'lipńa',
'august-gen'    => 'śyrpńa',
'september-gen' => 'wrześńa',
'october-gen'   => 'paźdźerńika',
'november-gen'  => 'listopada',
'december-gen'  => 'grudńa',
'jan'           => 'sty',
'feb'           => 'lut',
'mar'           => 'mer',
'apr'           => 'kwj',
'may'           => 'moj',
'jun'           => 'czy',
'jul'           => 'lip',
'aug'           => 'śyr',
'sep'           => 'wrz',
'oct'           => 'paź',
'nov'           => 'lis',
'dec'           => 'gru',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategoryjo|Kategoryje|Kategoryj}}',
'category_header'                => 'Zajty we katygoryji "$1"',
'subcategories'                  => 'Podkatygoryje',
'category-media-header'          => 'Pliki w katygoryji "$1"',
'category-empty'                 => "''Terozki w tyj katygoryji sům żodne artikle a pliki''",
'hidden-categories'              => '{{PLURAL:$1|Schowano katygoryjo|Schowane katygoryje|Schowanych katygoryj}}',
'hidden-category-category'       => 'Schowane katygoryje',
'category-subcat-count'          => '{{PLURAL:$2|Ta katygoryjo mo jyno jedno podkatygoryjo.|Ta katygoryjo mo {{PLURAL:$1|tako podkatygoryjo|$1 podkatygoryje|$1 podkatygoryj}} ze wjelośći wszyjskich katygoryj: $2.}}',
'category-subcat-count-limited'  => 'Ta katygoryjo mo {{PLURAL:$1|tako podkatygoryjo|$1 podkatygoryje|$1 podkatygoryji}}.',
'category-article-count'         => '{{PLURAL:$2|W tyj katygoryji je jyno jydno zajta.|W katygoryji {{PLURAL:$1|je ukozano $1 zajta|sům ukozane $1 zajty|je ukozanych $1 zajtůw}} ze cołkij wjelośći $2 zajtůw.}}',
'category-article-count-limited' => 'W katygoryji {{PLURAL:$1|je pokozano $1 zajta|sům pokozane $1 zajty|je pokazanych $1 zajtůw}}.',
'category-file-count'            => '{{PLURAL:$2|W katygoryji snojduje śe jydyn plik.|W katygoryji {{PLURAL:$1|je pokozany $1 plik|sům pokozane $1 pliki|je pokozanych $1 plikůw}} s cołkyj liczby $2 plikůw.}}',
'category-file-count-limited'    => 'W katygoryji {{PLURAL:$1|je pokozany $1 plik|sům pokozane $1 pliki|je pokozanych $1 plikůw}}.',
'listingcontinuesabbrev'         => 'ć.d.',
'index-category'                 => 'Indeksowane zajty',
'noindex-category'               => 'Ńyindeksowane zajty',
'broken-file-category'           => 'Zajty ze linkomi lo ńyistniejących ůobrozkůw',

'about'         => 'Uo serwiśe',
'article'       => 'zajta',
'newwindow'     => '(uodwjyro śe we nowym uokńe)',
'cancel'        => 'Uodćepej',
'moredotdotdot' => 'Wjyncy...',
'mypage'        => 'Moja zajta',
'mytalk'        => 'Mojo dyskusyjo',
'anontalk'      => 'Godka tygo IP',
'navigation'    => 'Nawigacyjo',
'and'           => '&#32;a',

# Cologne Blue skin
'qbfind'         => 'Nojdź',
'qbbrowse'       => 'Uoglůndańy',
'qbedit'         => 'Sprowjej',
'qbpageoptions'  => 'Ta zajta',
'qbpageinfo'     => 'Kontekst',
'qbmyoptions'    => 'Moje zajty',
'qbspecialpages' => 'Szpecyjalne zajty',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => 'Nowo tajla',
'vector-action-delete'           => 'Wyćepej',
'vector-action-move'             => 'Przećep',
'vector-action-protect'          => 'Zawrzij',
'vector-action-undelete'         => 'Wćep',
'vector-action-unprotect'        => 'Uodymkńij',
'vector-simplesearch-preference' => 'Włącz zaawansowane podpowiedzi wyszukiwania (tylko dla skórki Wektor)',
'vector-view-create'             => 'Stwůrz',
'vector-view-edit'               => 'Sprowjej',
'vector-view-history'            => 'Uobocz gyszichta',
'vector-view-view'               => 'Czytej',
'vector-view-viewsource'         => 'Zdrzůdłowy tekst',
'actions'                        => 'Akcyje',
'namespaces'                     => 'Raumy mjan',
'variants'                       => 'Warjanty',

'errorpagetitle'    => 'Feler',
'returnto'          => 'Nazod do zajty $1.',
'tagline'           => 'Ze {{GRAMMAR:D.lp|{{SITENAME}}}}',
'help'              => 'Půmoc',
'search'            => 'Sznupej',
'searchbutton'      => 'Sznupej',
'go'                => 'Przyńdź',
'searcharticle'     => 'Przyńdź',
'history'           => 'Gyszichta zajty',
'history_short'     => 'Gyszichta',
'updatedmarker'     => 'pomjyńane uod uostatńij wizyty',
'printableversion'  => 'Wersyjo do durku',
'permalink'         => 'Link do tyj wersyje zajty',
'print'             => 'Durkuj',
'view'              => 'Podglůnd',
'edit'              => 'Sprowjej',
'create'            => 'Stwůrz',
'editthispage'      => 'Sprowjej ta zajta',
'create-this-page'  => 'Stwůrz ta zajta',
'delete'            => 'Wyćep',
'deletethispage'    => 'Wyćep ta zajta',
'undelete_short'    => 'Wćep nazod {{PLURAL:$1|jedna wersyjo|$1 wersyje|$1 wersyji}}',
'viewdeleted_short' => '{{PLURAL:$1|jedna wyćepano wersyjo|$1 wyćepane wersyje|$1 wyćepanych wersyjůw}}',
'protect'           => 'Zawrzij',
'protect_change'    => 'půmjyń',
'protectthispage'   => 'Zawřij ta zajta',
'unprotect'         => 'Uodymkńij',
'unprotectthispage' => 'Uodymkńij ta zajta',
'newpage'           => 'Nowy artikel',
'talkpage'          => 'Godej uo tym artiklu',
'talkpagelinktext'  => 'dyskusyjo',
'specialpage'       => 'Špecyjalno zajta',
'personaltools'     => 'Perzůnolne',
'postcomment'       => 'Skůmyntuj',
'articlepage'       => 'Zajta artikla',
'talk'              => 'Dyskusyjo',
'views'             => 'Widok',
'toolbox'           => 'Werkcojg',
'userpage'          => 'Zajta sprowjorza',
'projectpage'       => 'Zajta projekta',
'imagepage'         => 'Zobejrz zajte pliku',
'mediawikipage'     => 'Zajta komuńikata',
'templatepage'      => 'Zajta šablůna',
'viewhelppage'      => 'Zajta pomocy',
'categorypage'      => 'Zajta katygoryji',
'viewtalkpage'      => 'Zajta godki',
'otherlanguages'    => 'We inkszych godkach',
'redirectedfrom'    => '(Punkńyńto s $1)',
'redirectpagesub'   => 'Zajta překerowujůnco',
'lastmodifiedat'    => 'Ta zajta bůła uostatńo sprowjano $2, $1.',
'viewcount'         => 'W ta zajta filowano {{PLURAL:$1|tylko roz|$1 rozůw}}.',
'protectedpage'     => 'Zajta zawarto',
'jumpto'            => 'Przyńdź do:',
'jumptonavigation'  => 'nawigacyje',
'jumptosearch'      => 'sznupańo',
'view-pool-error'   => 'Felerńe, syrwyry sům przecionżone.

$1',
'pool-timeout'      => 'Zbyt długi czas oczekiwania na blokadę',
'pool-queuefull'    => 'Kolejka zadań jest pełna',
'pool-errorunknown' => 'Feler ńyznany',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Uo {{GRAMMAR:MS.lp|{{SITENAME}}}}',
'aboutpage'            => 'Project:Uo serwiśe',
'copyright'            => 'Tekst udostympńany na licencyji $1.',
'copyrightpage'        => '{{ns:project}}:Autorske prawa',
'currentevents'        => 'Aktualne przitrefjyńa',
'currentevents-url'    => 'Project:Aktualne przitrefjyńa',
'disclaimers'          => 'Prawne informacyje',
'disclaimerpage'       => 'Project:Prawne informacyje',
'edithelp'             => 'Půmoc we půmjyńańy',
'edithelppage'         => 'Help:Jak půmjyńać zajta',
'helppage'             => 'Help:Treść',
'mainpage'             => 'Przodńo zajta',
'mainpage-description' => 'Przodńo zajta',
'policy-url'           => 'Project:Prawidua',
'portal'               => 'Portal używoczůw',
'portal-url'           => 'Project:Portal używoczůw',
'privacy'              => 'Prawidła chrůńyńo prywotnośći',
'privacypage'          => 'Project:Prawidła chrůńyńo prywotnośći',

'badaccess'        => 'Felerne uprawńyńo',
'badaccess-group0' => 'Ńy moš uprawńyń coby wykůnać ta uoperacyjo.',
'badaccess-groups' => 'Ta uoperacyjo mogům wykůnać ino užytkownicy s keryjś z grup {{PLURAL:$2|grupa|grupy}}:$1.',

'versionrequired'     => 'Wymagano MediaWiki we wersyji $1',
'versionrequiredtext' => 'Wymagano jest MediaWiki we wersji $1 coby skořistać s tyj zajty. Uoboč [[Special:Version]]',

'ok'                      => 'OK',
'retrievedfrom'           => 'Zdrzůdło "$1"',
'youhavenewmessages'      => 'Mosz $1 ($2).',
'newmessageslink'         => 'nowe powjadůmjyńa',
'newmessagesdifflink'     => 'uostatńe pomjyńyńy',
'youhavenewmessagesmulti' => 'Mosz nowe powjadůmjyńa: $1',
'editsection'             => 'Sprowjej',
'editold'                 => 'sprowjej',
'viewsourceold'           => 'pokoż zdrzůdło',
'editlink'                => 'sprowjej',
'viewsourcelink'          => 'zdrzůdłowy tekst',
'editsectionhint'         => 'Sprowjej tajla: $1',
'toc'                     => 'Treść',
'showtoc'                 => 'pokož',
'hidetoc'                 => 'schrůń',
'collapsible-collapse'    => 'Zwjyń',
'collapsible-expand'      => 'Rozwjyń',
'thisisdeleted'           => 'Pokož/wćepej nazod $1',
'viewdeleted'             => 'Uobejřij $1',
'restorelink'             => '{{PLURAL:$1|jedna wyćepano wersyjo|$1 wyćepane wersyje|$1 wyćepanych wersyjůw}}',
'feedlinks'               => 'Kanauy:',
'feed-invalid'            => 'Ńywuaściwy typ kanauů informacyjnygo.',
'feed-unavailable'        => 'Kanouy informacyjne ńy sům dostympne',
'site-rss-feed'           => 'Kanau RSS {{GRAMMAR:D.lp|$1}}',
'site-atom-feed'          => 'Kanoł Atom {{GRAMMAR:D.lp|$1}}',
'page-rss-feed'           => 'Kanau RSS "$1"',
'page-atom-feed'          => 'Kanoł Atom "$1"',
'red-link-title'          => '$1 (ńy mo zajty)',
'sort-descending'         => 'Sortuj malejąco',
'sort-ascending'          => 'Sortuj rosnąco',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Zajta',
'nstab-user'      => '{{GENDER:{{BASEPAGENAME}}|Zajta używocza|Zajta używoczki}}',
'nstab-media'     => 'Medja',
'nstab-special'   => 'Ekstra zajta',
'nstab-project'   => 'Zajta projektu',
'nstab-image'     => 'Plik',
'nstab-mediawiki' => 'Komuńikat',
'nstab-template'  => 'Muster',
'nstab-help'      => 'Zajta půmocy',
'nstab-category'  => 'Kategoryjo',

# Main script and global functions
'nosuchaction'      => 'Ńy mo takij uoperacyji',
'nosuchactiontext'  => 'Uoprogramowańy ńy rozpoznowo uoperacyji takij kej podano w URL.',
'nosuchspecialpage' => 'Ńy mo takij špecyjalnyj zajty',
'nospecialpagetext' => '<strong>Uoprogramowańy ńy rozpoznowo takij špecyjalnyj zajty.</strong>

Lista špecyjalnych zajtůw znejdźeš na [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Feler',
'databaseerror'        => 'Feler bazy danych',
'dberrortext'          => 'Zdorziu sie feler we skuadńi zapytańo do bazy danych. Uostatńy, ńyudane zapytańy to:
<blockquote><tt>$1</tt></blockquote>
wysuane bez funkcja "<tt>$2</tt>".
MySQL zguośiu feler "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Zdorziu śe feler we skuadńi zapytańo do bazy danych. Uostatńy, ńyudane zapytańy to:
"$1"
kere wywououa funkcyjo "$2".
MySQL zguośiu feler "$3: $4"',
'laggedslavemode'      => 'Dej pozůr: Ta zajta može ńy mjeć nojnowšych aktualizacyjůw.',
'readonly'             => 'Baza danych je zawarto',
'enterlockreason'      => 'Naškryflej sam powůd zawarća bazy danych a za wjela (myńi-wjyncyj) ja uodymkńeš',
'readonlytext'         => 'Baza danych jest terozki zawarto
- ńy do śe wćepywać nowych artikli ńi sprowjać juž wćepanych. Powodym
sům prawdopodańy čynnośći admińistracyjne. Po jejich zakůńčeńu pouno funkcjonalność bazy bydźe přywrůcono.
Administrator, kery zablokowou baza, podou takie wyjaśńyńy:<br /> $1',
'missing-article'      => 'W databaźe ńy idzie nolyźć treść zajty „$1” $2.

Uobwykle je to spůsobiůne tym, że sznupoł żeś po ńyaktualnym linku na zmjyny mjyndzy půmjyńańami, abo do wyćepanyj wersyje z gyszichty sprowjyń zajty.

Eli tak ńy je, możno śe trefił feler we softwaru MediaWiki. Kej ja, pedz uo tym [[Special:ListUsers/sysop|admińistratorowi]] a podej mu adres URL.',
'missingarticle-rev'   => '(wersyjo#: $1)',
'missingarticle-diff'  => '(dyferencyjo: $1, $2)',
'readonly_lag'         => 'Baza danych zostoua automatyčńy zawarto na čas potřebny na synchrońizacyjo zmjan mjyndzy serwerym guůwnym a serwerami postředńičůncymi.',
'internalerror'        => 'Wewnyntřny feler',
'internalerror_info'   => 'Wewnytřny feler: $1',
'fileappenderrorread'  => 'Feler uodczytu "$1".',
'fileappenderror'      => 'Ńy idźe skopjować plika "$1" do "$2".',
'filecopyerror'        => 'Ńy idźe skopjować plika "$1" do "$2".',
'filerenameerror'      => 'Ńy idźe zmjyńić mjana plika "$1" na "$2".',
'filedeleteerror'      => 'Ńy idźe wyćepać plika "$1".',
'directorycreateerror' => 'Ńy idźe utwořić katalogu "$1".',
'filenotfound'         => 'Ńy idźe znejść plika "$1".',
'fileexistserror'      => 'Ńy idźe sprowjać we pliku "$1": plik istńeje',
'unexpected'           => 'Ńyspodźewano wartość: "$1"="$2".',
'formerror'            => 'Feler: ńy idźe wysuać formulařa',
'badarticleerror'      => 'Tyj uoperacyje ńy idźe zrobić lo tyj zajty.',
'cannotdelete'         => 'Ńy idźe wyćepać podanyj zajty abo grafiki $1.',
'badtitle'             => 'Felerno tytůua',
'badtitletext'         => 'Podano felerny titel zajty. Prawdopodańy sům w ńim znoki, kerych ńy wolno užywać we titlach abo je pusty.',
'perfcached'           => 'To co sam je naškryflane, to ino kopja s pamjyńći podrynčnyj a može ńy być aktualne.',
'perfcachedts'         => 'To co sam je naškryflane, to ino kopja s pamjyńći podrynčnyj a bůuo uaktualńůne $1.',
'querypage-no-updates' => 'Uaktualńyńo lo tyj zajty sům terozki zawarte. Dane, kere sam sům, ńy zostouy uodśwjyžůne.',
'wrong_wfQuery_params' => 'Felerne parametry překozane do wfQuery()<br />
Funkcyjo: $1<br />
Zapytańy: $2',
'viewsource'           => 'Zdrzůdłowy tekst',
'actionthrottled'      => 'Akcyjo wstřimano',
'actionthrottledtext'  => 'Mechańizm uobrůny před spamym uograńičo ličba wykonań tyj čynnośći we jednostce času. Průbowoužeś go uocygańić. Proša, sprůbuj na nowo za pora minut.',
'protectedpagetext'    => 'Ta zajta je zawarto před sprowjańym.',
'viewsourcetext'       => 'We tekst zdřůduowy tyj zajty možno dali filować, idźe go tyž kopjować.',
'protectedinterface'   => 'Na tyj zajće znojduje śe tekst interfejsu uoprogramowańo, bestož uůna je zawarto uod sprowjańo.',
'editinginterface'     => "''''Dej pozůr:''' Sprowjosz zajta, na keryj je tekst interfejsu uoprogramowańo. Pomjyńyńa na tyj zajće zmjyńům wyglůnd interfejsu lo inkšych užytkowńikůw.",
'sqlhidden'            => '(schowano zapytańy SQL)',
'cascadeprotected'     => 'Ta zajta je zawarto uod sprowjańo, po takymu, co uůna je zauončono na {{PLURAL:$1|nastympujůncyj zajće, kero zostaua zawarto|nastympujůncych zajtach, kere zostauy zawarte}} ze zauončonům opcyjům dźedźičyńo:
$2',
'namespaceprotected'   => "Ńy moš uprowńyń, coby sprowjać zajty we přestřeńi mjan '''$1'''.",
'customcssprotected'   => 'Ńy mosz uprawńyń do sprowjańo tyj zajty, bo na ńij sům uosobiste sztalowańo inkszego użytkowńika.',
'customjsprotected'    => 'Ńy mosz uprawńyń do sprowjańo tyj zajty, bo na ńij sům uosobiste sztalowańo inkszego użytkowńika.',
'ns-specialprotected'  => 'Ńy idźe sprowjać zajtůw we přestřyni mjan {{ns:special}}.',
'titleprotected'       => "Wćepańy sam zajty uo takim mjańe zawar [[User:$1|$1]].
Powůd zawarćo: ''$2''.",

# Virus scanner
'virus-badscanner'     => "Felerno konfiguracyjo – ńyznany skaner antywirusowy ''$1''",
'virus-scanfailed'     => 'skanowańy ńyudone (feler $1)',
'virus-unknownscanner' => 'ńyznajůmy průgram antywirusowy',

# Login and logout pages
'logouttext'                 => "'''Terozki ježeś wylůgowany'''.

Možeš dali sam sprowjać zajty we {{SITENAME}} kej ńyzalůgowany užytkowńik, abo [[Special:UserLogin|zalůgować śe nazod]] kej tyn som abo inkšy užytkowńik.
Dej pozůr, co na ńykerych zajtach přeglůndarka može dali pokozywać co ježeś zalůgowany, a bydźe tak aže uodśwjyžyš jeij cache.",
'welcomecreation'            => '== Witej, $1! ==
Uotwarli my sam lo Ćebje kůnto.
Ńy zapomńij poštalować [[Special:Preferences|preferencyji lo {{GRAMMAR:D.lp|{{SITENAME}}}}]].',
'yourname'                   => 'Mjano užytkowńika:',
'yourpassword'               => 'Hasuo:',
'yourpasswordagain'          => 'Naszkryflej ausdruk zaś',
'remembermypassword'         => 'Pamjyntej můj ausdruk na tym kůmputrze (nojdalij bez $1 {{PLURAL:$1|dźyń|dńůw}})',
'securelogin-stick-https'    => 'Po zalogowańy mjyj połonczenie bez HTTPS',
'yourdomainname'             => 'Twoja domyna',
'externaldberror'            => 'Je jaki feler we zewnyntřnyj baźe autentyfikacyjnyj, abo ńy moš uprawńyń potřebnych do aktualizacyji zewnyntřnego kůnta.',
'login'                      => 'Zaloguj śe',
'nav-login-createaccount'    => 'Logowańy / tworzińy kůnta',
'loginprompt'                => 'Muśiš mjeć zouůnčůne cookies coby můc śe sam zalůgować.',
'userlogin'                  => 'Lůgowańy / Twořyńy kůnta',
'userloginnocreate'          => 'Zalůguj śe',
'logout'                     => 'Wyloguj',
'userlogout'                 => 'Uodloguj śe',
'notloggedin'                => 'Ńy ježeś zalůgowany',
'nologin'                    => "Ńy moš kůnta? '''$1'''.",
'nologinlink'                => 'Twůř kůnto',
'createaccount'              => 'Zouůž nowe kůnto',
'gotaccount'                 => "Mosz już kůnto? '''$1'''.",
'gotaccountlink'             => 'Naloguj śe',
'userlogin-resetlink'        => 'Zapomńoł żeś dane lo nalogowańo?',
'createaccountmail'          => 'e-brifym',
'createaccountreason'        => 'Kůmyntorz:',
'badretype'                  => 'Hasua kere žeś naškryflou ńy zgodzajům śe jydne s drugim.',
'userexists'                 => 'Mjano użytkowńika, kere żeś wybroł, je zajynte. Wybjer, prosza, inksze mjano.',
'loginerror'                 => 'Feler při logůwańu',
'createaccounterror'         => 'Ńy możno stworzić konta $1',
'nocookiesnew'               => 'Kůnto užytkowńika zostouo utwořůne, nale ńy ježeś zalůgowany. {{SITENAME}} užywo ćosteček do logůwańo. Moš wyuůnčone ćostečka. Coby śe zalůgować, uodymknij ćostečka a podej mjano a hasuo swojigo kůnta.',
'nocookieslogin'             => '{{SITENAME}} užywo ćosteček do lůgowańo užytkowńikůw. Moš zablokowano jejich uobsuůga. Sprůbuj zaś jak zauůnčyš uobsuůga ćosteček.',
'nocookiesfornew'            => 'Konto sprowjorza ńy uostoło stworzone. Sprawdź, cze mosz uodymkńynto obsługe cookies.',
'noname'                     => 'To ńy je půprowne mjano užytkowńika.',
'loginsuccesstitle'          => 'Lůgowańy udane',
'loginsuccess'               => "'''Terozki ježeś zalůgowany do {{SITENAME}} jako \"\$1\".'''",
'nosuchuser'                 => 'Ńy ma sam użytkowńika uo mjańe "$1".
Sprowdź szrajbůng, abo [[Special:UserLogin/signup|utwůrz nowe kůnto]].',
'nosuchusershort'            => 'Ńy mo sam užytkowńika uo mjańe "$1".',
'nouserspecified'            => 'Podej mjano užytkowńika.',
'login-userblocked'          => 'Tyn sprowjorz ma zawrzite sprowjyńa. Ńy możno sie zalgować.',
'wrongpassword'              => 'Hasuo kere žeś naškryflou je felerne. Poprůbůj naškryflać je ješče roz.',
'wrongpasswordempty'         => 'Hasuo kere žeś podou je puste. Naškryflej je ješče roz.',
'passwordtooshort'           => 'Hasło kere żeś podoł je felerne abo za krůtke.
Hasło muśi mjeć przinojmńij {{PLURAL:$1|1 buchsztaba|$1 buchsztabůw}} a być inksze od mjana użytkowńika.',
'password-name-match'        => 'Hasło musi być inne niż nazwa użytkownika.',
'password-login-forbidden'   => 'Ńy wolno mjyć takij nazwy a hasua.',
'mailmypassword'             => 'Wyślij mi nowe hasuo bez e-brif',
'passwordremindertitle'      => 'Nowe tymčasowe hasuo dla {{SITENAME}}',
'passwordremindertext'       => 'Ftůś (cheba Ty, s IP $1)
pado, aże chce nowe hasło do {{SITENAME}} ($4).
Lo użytkowńika "$2" wygenyrowano nowe hasło a je ńim "$3".
Jak chćołżeś gynał to zrobjyć, to zalůgůj śe terozki a podej swoje hasło.
Hasło wygaśnie za {{PLURAL:$5|1 dzień|$5 dni}}.

Jak ktůś inkszy chćoł nowe hasło abo jak Ci śe przipůmńouo stare a ńy chcesz nowygo, to zignoruj to a używej starygo hasła.',
'noemail'                    => 'Ńy mo u nos adresu e-brifa do "$1".',
'noemailcreate'              => 'Podaj dobry e-mail ausdruk',
'passwordsent'               => 'Nowe hasuo pošuo na e-brifa uod užytkowńika "$1".
Zalůguj śe zaś jak dostańyš tygo brifa.',
'blocked-mailpassword'       => 'Twůj adres IP zostou zawarty a ńy možeš užywać funkcyje odzyskiwańo hasua skuli možliwośći jeji nadužywańo.',
'eauthentsent'               => 'Potwjerdzeńy zostouo wysuane na e-brifa.
Jak bydźeš chćou, coby wysyuouo Ći e-brify, pjyrwyj go přečytej. Bydźeš tam mjou instrukcyjo co moš zrobić, coby pokozać, aže tyn adres je Twůj.',
'throttled-mailpassword'     => 'Připůmńyńy hasua bůuo juž wysuane bez {{PLURAL:$1|uostatńo godźina|uostatńe $1 godźin}}.
Coby powstřimać nadužyća, možliwość wysyuańa připůmńeń naštalowano na jydne bez {{PLURAL:$1|godźina|$1 godźiny}}.',
'mailerror'                  => 'Při wysyuańu e-brifa zdořiu śe feler: $1',
'acct_creation_throttle_hit' => 'Przikro nom, założył(a)żeś już {{PLURAL:$1|1 kůnto|$1 kůnta}}. Ńy możesz założyć kolejnygo.',
'emailauthenticated'         => 'Twůj adres e-brifa zostou uwjeřitelńůny $2 uo $3.',
'emailnotauthenticated'      => 'Twůj adres e-brifa ńy je uwjeřitelńůny. Půnižše funkcyje počty ńy bydům dźauać.',
'noemailprefs'               => 'Muśiš podać adres e-brifa, coby te funkcyje dźouauy.',
'emailconfirmlink'           => 'Potwjerdź swůj adres e-brifa',
'invalidemailaddress'        => 'E-brif ńy bydźe zaakceptůwany skiž tygo co jego format ńy speuńo formalnych wymagań. Proša naškryflać poprowny adres e-brifa abo wyčyśćić pole.',
'cannotchangeemail'          => 'Ńy możno pomjyńyc ausdruku e-mail.',
'accountcreated'             => 'Utwůřůno kůnto',
'accountcreatedtext'         => 'Kůnto lo $1 zostouo utwůřůne.',
'createaccount-title'        => 'Stwořyńy kůnta na {{GRAMMAR:MS.lp|{{SITENAME}}}}',
'createaccount-text'         => 'Ktoś utwořiu na {{GRAMMAR:MS.lp|{{SITENAME}}}} ($4) dla Twojego adresa e-brif kůnto "$2". Aktualne hasuo to "$3". Powińežeś śe terozki zalogůwać a je zmjyńić.',
'usernamehasherror'          => 'Nazwa sprowjorza ńy może mjyć buchsztaby "#".',
'login-throttled'            => '!Wykonołżeś za wjela průb zalůgowańo śe na te kůnto. Poczekej chwila ńym zaś sprůbujesz.',
'login-abort-generic'        => 'Felerne logowańe',
'loginlanguagelabel'         => 'Godka: $1',
'suspicious-userlogout'      => 'Żądanie wylogowania zostało odrzucone ponieważ wygląda na to, że zostało wysłane przez uszkodzoną przeglądarkę lub buforujący serwer proxy.',

# E-mail sending
'php-mail-error-unknown' => 'Ńyznany feler we funkcyji mail()',
'user-mail-no-addy'      => 'Próba wysłania e‐maila bez adresu odbiorcy',

# Change password dialog
'resetpass'                 => 'Zmjyń hasło',
'resetpass_announce'        => 'Zalůgowoužeś śe s tymčasowym kodym uotřimanym bez e-brif. Coby zakůńčyć proces logůwańo muśiš naštalować nowe hasuo:',
'resetpass_header'          => 'Zmjyń hasło lů swojygo kůnta',
'oldpassword'               => 'Stare hasuo',
'newpassword'               => 'Nowe hasuo',
'retypenew'                 => 'Naškryflej ješče roz nowe hasuo:',
'resetpass_submit'          => 'Naštaluj hasuo a zalůguj',
'resetpass_success'         => 'Twoje hasuo zostouo půmyślńy pomjyńone! Trwo logůwańe...',
'resetpass_forbidden'       => 'Ńy idźe sam půmjyńyć hasuůw.',
'resetpass-no-info'         => 'Muśysz być zalogowany, coby uzyskać bezpostrzedńi dostymp do tyj zajty.',
'resetpass-submit-loggedin' => 'Zmjyń hasło',
'resetpass-submit-cancel'   => 'Uodćepej',
'resetpass-wrong-oldpass'   => 'Felerne tymczasowe abo aktualne hasło.
Możliwe co właśńy zmjyńiłżeś swoje hasło abo poprosiłżeś uo nowe tymczasowe hasło.',
'resetpass-temp-password'   => 'Tymczasowe hasło:',

# Special:PasswordReset
'passwordreset'                    => 'Wyczyść hasło',
'passwordreset-text'               => 'Wypełnij formularz, aby otrzymać e‐mail z przypomnieniem danych Twojego konta.',
'passwordreset-legend'             => 'Wyczyść hasło',
'passwordreset-disabled'           => 'No tyj wiki zamkńynto resytowańy hasył.',
'passwordreset-pretext'            => '{{PLURAL:$1||Wćep jydną z danych}}',
'passwordreset-username'           => 'Mjano używacza:',
'passwordreset-domain'             => 'Domyna:',
'passwordreset-capture'            => 'Czy pokazywać treść wiadomości e‐mail?',
'passwordreset-capture-help'       => 'Eli zaznaczysz to pole, łoboczysz wjadomość e-mail z hasłem.',
'passwordreset-email'              => 'E-brif:',
'passwordreset-emailtitle'         => 'Kůnto na {{GRAMMAR:MS.lp|{{SITENAME}}}}',
'passwordreset-emailtext-ip'       => 'Ftůś (cheba Ty, s IP $1)
pado, aże chce informacyji lo konta do {{GRAMMAR:MS.lp{{SITENAME}}}} ($4).
Z tem ausdrukiem sum powjonzyne konta:
$2

{{PLURAL:$3|Tymczasowygo hasła|Tymczasowych haseł}} możno użyć w ciągu {{PLURAL:$5|jednego dnia|$5 dni}}.

Jak chćołżeś gynał to zrobjyć, to zalůgůj śe terozki a podej swoje hasło.

Jak ktůś inkszy chćoł nowe hasło abo jak Ci śe przipůmńouo stare a ńy chcesz nowygo, to zignoruj to a używej starygo hasła.',
'passwordreset-emailelement'       => 'Nazwa sprowjorza: $1
Tymczasowe hasło: $2',
'passwordreset-emailsent'          => 'E-brif posłany.',
'passwordreset-emailsent-capture'  => 'E-brif posłony, kerego widać niżej.',
'passwordreset-emailerror-capture' => 'Ńy udało sie wysłać wjadomości lo sprowjorza: $1',

# Special:ChangeEmail
'changeemail'          => 'Pomjyno ausdruka e-mail',
'changeemail-header'   => 'Pomjyno ausduku e-mail',
'changeemail-text'     => 'Wypełnij formularz, podej nowy ausdruk a hasło.',
'changeemail-no-info'  => 'Muśysz być zalogowany, coby uzyskać bezpostrzedńi dostymp do tyj zajty.',
'changeemail-oldemail' => 'Łobecny ausdruk:',
'changeemail-newemail' => 'Nowy adresu e-brif',
'changeemail-none'     => 'podstawowo',
'changeemail-submit'   => 'Zapisz nowy',
'changeemail-cancel'   => 'Uodćepej',

# Edit page toolbar
'bold_sample'     => 'Ruby tekst',
'bold_tip'        => 'Ruby tekst',
'italic_sample'   => 'Przechylůny tekst',
'italic_tip'      => 'Przechylůny tekst',
'link_sample'     => 'Titel linka',
'link_tip'        => 'Wewnytrzny link',
'extlink_sample'  => 'http://www.example.com titla linku',
'extlink_tip'     => 'Eksterny link (pamjyntej uo prefikśe http:// )',
'headline_sample' => 'Tekst iberszryftu',
'headline_tip'    => 'Iberszryft 2. stůpńo',
'nowiki_sample'   => 'Wćepej sam tekst bez formatowańo',
'nowiki_tip'      => 'Zignoruj formatowańy wiki',
'image_tip'       => 'Plik uosadzůny we zajće',
'media_tip'       => 'Link do plika',
'sig_tip'         => 'Twůj podpis z datumym i czasym',
'hr_tip'          => 'Poźůmo lińijo (używej mjyrńy)',

# Edit pages
'summary'                          => 'Popis půmjyńań:',
'subject'                          => 'Tymat/naguůwek:',
'minoredit'                        => 'To je ńywjelge sprowjyńy',
'watchthis'                        => 'Dej pozůr',
'savearticle'                      => 'Spamjyntej',
'preview'                          => 'Uobźyrańy',
'showpreview'                      => 'Uobźyrej',
'showlivepreview'                  => 'Dynamičny podglůnd',
'showdiff'                         => 'Pozdrzyj na půmjyńańy',
'anoneditwarning'                  => 'Ńy jeżeś nalogowany. We gyszichće sprowjyń tyj zajty bydźe naszkryflůny twůj adres IP.',
'anonpreviewwarning'               => 'Ńy jeżeś zalogowany. Twój adres IP łostonie zapisany, eli ty bydzies sprowjać zajte.',
'missingsummary'                   => "'''Připomńyńy:''' Ńy wprowadźiužeś uopisu pomjyńań. Kej go ńy chceš wprowadzać, naćiś knefel Škryflej ješče roz.",
'missingcommenttext'               => 'Wćepej kůmyntoř půńižyj.',
'missingcommentheader'             => "'''Dej pozůr:''' Treść nagłůwka je pusto - uzupeuńij go! Jeli tego ńy zrobisz, Twůj kůmyntorz bydźe naszkryflany bez naguůwka.",
'summary-preview'                  => 'Podglůnd uopisu:',
'subject-preview'                  => 'Podglůnd tematu/naguůwka:',
'blockedtitle'                     => 'Užytkowńik je zawarty uod sprowjyń',
'blockedtext'                      => '\'\'\'Twoje kůnto abo adres IP sům zawarte.\'\'\'

Uo zawarću zdecydowou $1. Pado, aže skuli: \'\'$2\'\'.

* Zawarte uod: $8
* Uodymkńe śe: $6
* Bez cůž: $7

Coby wyjaśńić sprawa zawarćo, naškryflej do $1 abo inkšygo [[{{MediaWiki:Grouppage-sysop}}|admińistratora]].
Ńy možeš posuać e-brifa bez "poślij e-brifa tymu užytkowńikowi", jak žeś ńy podou dobrygo adresa e-brifa we [[Special:Preferences|preferencyjach kůnta]], abo jak e-brify moš tyž zawarte. Terozki moš adres IP $3 a nůmer zawarća to #$5. Prošymy podać jedyn abo uobadwa jak chceš pouosprawjać uo zawarću.',
'autoblockedtext'                  => 'Tyn adres IP zostou zawarty automatyčńy, gdyž kořisto s ńygo inkšy užytkowńik, zawarty uod sprowjyń bez administratora $1.
Powůd zawarćo:

:\'\'$2\'\'

* Počůntek zawarćo: $8
* Zawarće wygaso: $6
* Zawarće je skiž: $7

Možyš skůntaktować śe s $1 abo jednym s pozostauych [[{{MediaWiki:Grouppage-sysop}}|admińistratorůw]] kejbyś chćou uzyskać informacyje uo zawarću.

Pozůr: Kejžeś we [[Special:Preferences|preferencyjach]] ńy naštalowou prowiduowygo adresa e-brifa, abo e-brify moš tyž zawarte, ńy možeš skožystać s uopcyje "Poślij e-brifa tymu užytkowńikowi".

Twůj adres IP je terozki $3. Idyntyfikator Twojij blokady to $5. Zanotuj śe go a podej admińistratorowi.',
'blockednoreason'                  => 'ńy podano skuli čego',
'whitelistedittext'                => 'Muśiš $1 coby můc sprowjać artikle.',
'confirmedittext'                  => 'Muśiš podać a potwjerdźić swůj e-brif, coby můc sam sprowjać.
Možeš to zrobić we [[Special:Preferences|swojich štalowańach]].',
'nosuchsectiontitle'               => 'Ńy mo takij tajli',
'nosuchsectiontext'                => 'Průbowołżeś sprowjać tajla kero ńy istńeje.',
'loginreqtitle'                    => 'Muśiš śe zalůgować',
'loginreqlink'                     => 'zalůguj śe',
'loginreqpagetext'                 => 'Muśiš $1 coby můc přeglůndać inkše zajty.',
'accmailtitle'                     => 'Hasuo wysuane.',
'accmailtext'                      => '!Hasło użytkowńika "[[User talk:$1|$1]]" zostauo wysłane pod adres $2.

Hasło można pomjyńyć [[Special:ChangePassword|tu]].',
'newarticle'                       => '(Nowy)',
'newarticletext'                   => 'Ńy mo sam jeszcze artikla uo takijj titli. Eli chcesz go sprowjać, naszkryflej niżyj jego tekst (wjyncy informacyj nojdźesz [[{{MediaWiki:Helppage}}|na zajće půmocy]]). Eli żeś chćoł zrobić cosik inksze, naćiś ino knefel "Nazod".',
'anontalkpagetext'                 => "---- ''To jest zajta godki lo użytkowńikůw anůnimowych - takich, kerzi ńy majům jeszcze swojigo kůnta abo ńy chcům go terozki užywać.
By jeich idyntyfikować, używomy numerůw IP.
Eli jeżeś anůnimowym użytkowńikym a wydowo Ći śe, aże zamjyszczůne sam kůmyntorze ńy sům skjyrowane do Ćebje, [[Special:UserLogin|utwůrz prosza kůnto]] abo [[Special:UserLogin|zalůguj śe]] - bez tůż uńikńesz potym podobnych ńyporozumjyń.''",
'noarticletext'                    => 'Ńy můmy zajta uo takij titli. Mogesz [{{fullurl:{{FULLPAGENAME}}|action=edit}} wćepać artikel {{FULLPAGENAME}}] abo [[Special:Search/{{PAGENAME}}|sznupać {{PAGENAME}} we inkszych]].',
'noarticletext-nopermission'       => 'Na tyj zajće ńy mo jeszcze artikla.
Mogesz [[Special:Search/{{PAGENAME}}|wysznupać ta titla]] we treśći inkszych zajtůw
abo <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} przesznupać powjůnzane logi].</span>',
'userpage-userdoesnotexist'        => 'Užytkowńik "<nowiki>$1</nowiki>" ńy je zareještrowany. Sprowdź eli na pewno chćoužeś stwořyć/pomjynić gynau ta zajta.',
'userpage-userdoesnotexist-view'   => "Konto sprowjorza ''$1'' ńy istnieje.",
'blocked-notice-logextract'        => '{{GENDER:$1|Tyn sprowjorz|Ta sprowjorka}} mo zawrzite sprowjyńa.',
'clearyourcache'                   => "!'''Dej pozůr:''' Coby łobejrzeć pomjyńańo pů naszkryflańu nowych sztalowań poleć przeglůndorce wyczyśćić zawartość pamjyńći podryncznyj (cache). '''Mozilla / Firefox / Safari:''' przitrzimej ''Shift'' klikajůnc na ''Łodśwjyž'' abo wciś ''Ctrl-Shift-R'' (''Cmd-Shift-R'' na Macu), '''IE :''' przitrzimej ''Ctrl'' klikajůnc na ''Łodśwjyž'' abo wciś ''Ctrl-F5''; '''Konqueror:''': kliknij knefel ''Łodśwjyž'' abo wciś ''F5''; użytkowńicy '''Opery''' mogům być zmuszeńi coby cołkym wyczyśćić jejich pamjyńć podrynczno we menu ''Werkcojgi→Preferencyje''.; '''Internet Explorer:''' trzim ''Ctrl'' a wćiś ''Łodśwjyż'', abo wćiś ''Ctrl-F5''.",
'usercssyoucanpreview'             => "!'''Podpowjydź:''' Użyj knefla \"Podglůnd\", coby przetestować Twůj nowy arkusz stylůw CSS abo kod JavaScript przed jego zaszrajbowańym.",
'userjsyoucanpreview'              => "!'''Podpowjydź:''' Użyj knefla \"Podglůnd\", coby przetestować Twůj nowy arkusz stylůw CSS abo kod JavaScript przed jego zaszrajbowańym.",
'usercsspreview'                   => "'''Pamjyntej, aže to je na raźe ino podglůnd Twojego arkuša stylůw CSS.'''
'''Ńic ješče ńy zostouo naškryflone!'''",
'userjspreview'                    => "'''Pamjyntej, aže to je na raźe ino podglůnd Twojego JavaScriptu - nic ješče ńy zostouo naškryflone!'''",
'sitecsspreview'                   => "'''Pamjyntej, aże to je na raźe ino podglůnd Twojego arkusza stylůw CSS.'''
'''Ńic jeszczče ńy zostoło naszkryflone!'''",
'sitejspreview'                    => "'''Pamjyntej, aże to je na raźe ino podglůnd Twojego JavaScriptu - nic jeszcze ńy zostoło naškryflone!'''",
'userinvalidcssjstitle'            => "'''Pozůr:''' Ńy mo skůrki uo mjańe \"\$1\". Pamjyntej, aže zajty užytkowńika zawjyrajůnce CSS i JavaScript powinny začynać śe mouům buchštabům, np. {{ns:user}}:Foo/vector.css.",
'updated'                          => '(Pomjyńano)',
'note'                             => "'''Pozůr:'''",
'previewnote'                      => "'''To je ino podglůnd - artikel ješče ńy je naškryflany!'''",
'previewconflict'                  => 'Wersyjo podglůndano uodnośi śe do tekstu s pola edycyje na wjyrchu. Tak bydźe wyglůndać zajta jeli zdecyduješ śe jům naškryflać.',
'session_fail_preview'             => "'''Přeprašomy! Serwer ńy može přetwořyć tygo sprowjyńo skuli utraty danych ze sesyji. Sprůbuj ješče roz. Kejby to ńy pomoguo - wylůguj śe i zalogůj uod nowa.'''",
'session_fail_preview_html'        => "'''Přeprašomy! Serwer ńy može přetwořyć tygo sprowjyńo skuli utraty danych ze sesyji.'''

''Jako iže na {{GRAMMAR:MS.lp|{{SITENAME}}}} wuůnčono zostoua uopcyjo \"raw HTML\", podglůnd zostou schrůńony coby zabezpječyć před atakami JavaScript.''

'''Jeli to je prawiduowo průba sprowjańo, sprůbuj ješče roz. Kejby to ńy pomoguo - wylůguj śe a zalůguj na nowo.'''",
'token_suffix_mismatch'            => "'''Twoje sprowjyńy zostouo uodćepńynte skuli tego, co twůj klijynt pomjyšou znaki uod interpůnkcyji w žetůńe sprowjyń. Twoje sprowjyńy zostouo uodćepńynte coby zapobjec zńyščyńu tekstu zajty. Take průblymy zdořajům śe w roźe kůřistańo s felernych anůnimowych śećowych usuůg proxy.'''",
'editing'                          => 'Sprowjosz $1',
'editingsection'                   => 'Sprowjosz $1 (sekcyjo)',
'editingcomment'                   => 'Sprowjosz "$1" (kůmyntorz)',
'editconflict'                     => 'Kůnflikt sprowjyń: $1',
'explainconflict'                  => "Ktoś zdůnžyu wćepać swoja wersyjo artikla ńim žeś naškryflou sprowjyńy.
We polu edycyji na wjyrchu moš tekst zajty aktůalńy naškryflany w baźe danych.
Twoje pomjyńańo sům we polu edycyji půnižyj.
By wćepać swoje pomjyńańo muśiš pomjyńać tekst w polu na wjyrchu.
'''Tylko''' tekst z pola na wjyrchu bydźe naškryflany we baźe jak wciśńeš \"{{int:savearticle}}\".",
'yourtext'                         => 'Twůj tekst',
'storedversion'                    => 'Naškryflano wersyjo',
'nonunicodebrowser'                => "'''Pozůr! Twoja přeglůndorka ńy umje poprowńy rozpoznować kodowańo UTF-8 (Unicode). Bestož wšyjske znoki, kerych Twoja přeglůndorka ńy umje rozpoznować, zamjeńůno na jejich kody heksadecymalne.'''",
'editingold'                       => "'''Dej pozůr: Sprowjoš inkšo wersyjo zajty kej bježůnco. Jeli jům naškryfloš, wšyjske půźńyjše pomjyńańa bydům wyćepane.'''",
'yourdiff'                         => 'Růžńice',
'copyrightwarning'                 => "Pamjyntej uo tym, aže couki wkuod do {{SITENAME}} udostympńůmy wedle zasad $2 (dokuadńij w $1). Jak ńy chceš, coby koždy můg go zmjyńać i dali rozpowšychńać, ńy wćepuj go sam. Škryflajůnc sam tukej pośwjadčoš tyž, co te pisańy je twoje wuasne, abo žeś go wźůn(a) s materjouůw kere sům na ''public domain'', abo kůmpatybilne.<br />
'''PROŠA ŃY WĆEPYWAĆ SAM MATYRJOUŮW KERE SŮM CHRŮŃONE PRAWYM AUTORSKIM BEZ DOZWOLEŃO WUAŚĆIĆELA!'''",
'copyrightwarning2'                => "Pamjyntej uo tym, aže couki wkuod do {{GRAMMAR:MS.lp|{{SITENAME}}}} može być sprowjany, pomjyńany abo wyćepany bez inkšych užytkownikůw. Jak ńy chceš, coby koždy můg go zmjyńać i dali rozpowšychńać bez uograničyń, ńy wćepuj go sam.<br />
Škryflajůnc sam tukej pośwjadčoš tyž, co te pisańy je twoje wuasne, abo žeś go wźůn(a) s matyrjouůw kere sům na public domain, abo kůmpatybilne (kuknij tyž: $1).
'''PROŠA ŃY WĆEPYWAĆ SAM MATYRJOUŮW KERE SŮM CHRŮŃONE PRAWYM AUTORSKIM BEZ DOZWOLEŃO WUAŚĆIĆELA!'''",
'longpageerror'                    => "'''Feler: Tekst kery žeś sam wćepywou mo $1 kilobajtůw. Maksymalno dugość tekstu ńy može być wjynkšo kej $2 kilobajtůw. Twůj tekst ńy bydźe sam naškryflany.'''",
'readonlywarning'                  => "'''Dej pozůr: Baza danych zostoua filowo zawarto skuli potřeb admińistracyjnych. Bestůž ńy do śe terozki naškryflać Twojich pomjyńań. Radzymy přećepać nowy tekst kajś do plika tekstowego (wytnij/wklej) a wćepać sam zaś po uodymkńyńću bazy.'''

Admińistrator kery zawar baza dou take wyjaśńyńe: $1",
'protectedpagewarning'             => "'''Dej pozůr: Sprowjańe tyj zajty zostouo zawarte. Mogům jům sprowjać ino užytkowńicy s uprawńyńami admińistratora.'''",
'semiprotectedpagewarning'         => "'''Pozůr:''' Ta zajta zostoua zawarto a ino zaregišterowani užytkownicy mogům jům sprowjać.",
'cascadeprotectedwarning'          => "'''Dej pozůr:''' Ta zajta zostoua zawarto a ino užytkowńicy s uprawńyńami admińistratora mogům jům sprowjać. Zajta ta je podpjynto pod {{PLURAL:$1|nastympujůnco zajta, kero zostoua zawarto|nastympujůncych zajtach, kere zostouy zawarte}} ze zauůnčonům opcjům dźedźičyńo:",
'titleprotectedwarning'            => "'''DEJ POZŮR: Zajta uo tym titlu zostoua zawarto a ino ńykeři užytkowńicy mogům jům wćepać.'''",
'templatesused'                    => '{{PLURAL:$1|Szablon|Szablůny}} użyte na tyj zajće:',
'templatesusedpreview'             => 'Šablůny užyte we tym podglůńdźe:',
'templatesusedsection'             => 'Šablůny užyte w tyj tajli:',
'template-protected'               => '(zawrzity uod sprowjańo)',
'template-semiprotected'           => '(tajlowo zawarte)',
'hiddencategories'                 => 'Ta zajta je {{PLURAL:$1|w jednyj schrůńunyj katygoryji|we $1 schrůńunych katygoryjach}}:',
'nocreatetitle'                    => 'Uograńičůno wćepywańy zajtůw',
'nocreatetext'                     => 'Na {{GRAMMAR:MS.lp|{{SITENAME}}}} twořyńy nowych zajtůw uograńičůno.
Možeš sprowjać te co juž sům, abo [[Special:UserLogin|zalogować śe, abo zauožyć konto]].',
'nocreate-loggedin'                => 'Ńy moš uprowńyń do twořyńo nowych zajtůw.',
'permissionserrors'                => 'Felerne uprowńyńa',
'permissionserrorstext'            => 'Ńy moš uprowńyń do takij akcyje {{PLURAL:$1|skuli tego, co:|bestůž, co:}}',
'permissionserrorstext-withaction' => 'Ńy mogesz $2, ze {{PLURAL:$1|takigo powodu|takich powodůw}}:',
'recreate-moveddeleted-warn'       => "'''ůostrzeżyńy: Wćepujesz samo zajta, kery bůu poprzedńo wyćepany.'''

Zastanůw śe, czy powinno śe go sam wćepywać.
Rejer wyćepań tyj zajty je podany půńiżej, cobyś mioł wygoda:",
'moveddeleted-notice'              => 'Ta zajta zostoua wyćepńynto. Rejer wyćepań tyj zajty je pokozany půńižyj.',
'log-fulllog'                      => 'Ukoż rejer',
'edit-hook-aborted'                => 'Sprowjyńy štopńynte skiž hoka.
Ńy je wjadůme pů jakymu.',
'edit-gone-missing'                => 'Ńy idźe zaktualizować zajty.
Zdowo śe, co zostoua wyćepano.',
'edit-conflict'                    => 'Kůnflikt sprowjyń.',
'edit-no-change'                   => 'Twoje sprowjyńe uostouo zignorowane pů takymu, co ńic žeś we tekśće ńy zmjyńiu.',
'edit-already-exists'              => 'Ńy idźe utwořić nowyj zajty.
Tako zajta juž sam je.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Dej pozůr: ta zajta mo za dužo uodwouań do funkcyji parsera, kere mocno uobćůnžajům systym.

Powinno być myńi jak $2 {{PLURAL:$2|wywouańy|wywouańo|wywouań}}, a terozki {{PLURAL:$1|je $1 wywouańy|sům $1 wywouańo|je $1 wywouań}}.',
'expensive-parserfunction-category'       => 'Zajty kere majům za dužo uodwouań do funkcyji parsera, kere mocno uobćůnžajům systym.',
'post-expand-template-inclusion-warning'  => 'Dej pozůr: Dokuplowane mustry sům moc wjelge.
Ńykere mustry ńy bydům dokuplowane.',
'post-expand-template-inclusion-category' => 'Zajty, na kerych dokuplowane mustry sům moc wjelge',
'post-expand-template-argument-warning'   => 'Dej pozůr: Ta zajta zawjyro přinojmyńi jedyn argument we šablůńe kery powoduje co je ůun za wjelgi. Te argumynty bydům pomińynte.',
'post-expand-template-argument-category'  => 'Zajty na kerych sům šablůny s pomińyntymi argumyntůma.',
'parser-template-loop-warning'            => 'Wykryto szablůn zapyntlyńo: [[$1]]',
'parser-template-recursion-depth-warning' => 'Przekroczůno limit głymbokośći rekurencyji szablona ($1)',

# "Undo" feature
'undo-success' => 'Sprowjyńy zostouo wycůfane. Proša pomjarkować ukozane půnižyj dyferencyje mjyndzy wersyjami, coby zweryfikować jejich poprawność, potym zaś naškryflać pomjyńańo coby zakońčyć uoperacyjo.',
'undo-failure' => 'Sprowjyńo ńy idźe wycofać skuli kůnflikta ze wersyjůma postřednimi.',
'undo-norev'   => 'Sprowjyńo ńy idźe cofnůńć skuli tego, co ńy istńije abo zostouo wyćepane.',
'undo-summary' => 'Wycůfańy wersyji $1 naškryflanej bez [[Special:Contributions/$2|$2]] ([[User talk:$2|godka]])',

# Account creation failure
'cantcreateaccounttitle' => 'Ńy idźe utwořić kůnta',
'cantcreateaccount-text' => "Twořyńy kůnta s tygo adresu IP ('''$1''') zostouo zawarte bez užytkowńika [[User:$3|$3]].

Skuli: ''$2''",

# History pages
'viewpagelogs'           => 'Uoboč rejery uoperacyji lo tyj zajty',
'nohistory'              => 'Ta zajta ńy mo swojij historyje sprowjyń.',
'currentrev'             => 'Aktualno wersyjo',
'currentrev-asof'        => 'Aktualno wersyjo na dźyń $1',
'revisionasof'           => 'Wersyjo ze dńa $1',
'revision-info'          => 'Wersyjo s dńa $1; $2',
'previousrevision'       => '← starszo wersyjo',
'nextrevision'           => 'Nastympno wersyjo→',
'currentrevisionlink'    => 'Aktualno wersyjo',
'cur'                    => 'akt.',
'next'                   => 'nastympno',
'last'                   => 'poprz.',
'page_first'             => 'počůnek',
'page_last'              => 'kůńec',
'histlegend'             => 'Wybůr růžńic do porůwnańo: postow kropki we boksach a naćiś enter abo knefel na dole.<br />
Legynda: (bjež.) - růžńice s wersyjům bježůncům, (popř.) - růžńice s wersyjům popředzajůncům, d - drobne zmjany',
'history-fieldset-title' => 'Přeglůndej historyjo',
'history-show-deleted'   => 'Jyno wyćepane',
'histfirst'              => 'uod počůnku',
'histlast'               => 'uod uostatka',
'historysize'            => '({{PLURAL:$1|1 bajt|$1 bajty|$1 bajtůw}})',
'historyempty'           => '(pusto)',

# Revision feed
'history-feed-title'          => 'Gyszichta wersyjůw',
'history-feed-description'    => 'Historyjo wersyje tyj zajty wiki',
'history-feed-item-nocomment' => '$1 uo $2',
'history-feed-empty'          => 'Wybrano zajta ńy istńije.
Můgua zostać wyćepano abo přećepano pod inkše mjano.
Možeš tyž [[Special:Search|šnupać]] za tům zajtům.',

# Revision deletion
'rev-deleted-comment'         => '(kůmyntorz wyćepany)',
'rev-deleted-user'            => '(užytkowńik wyćepany)',
'rev-deleted-event'           => '(škryflańy wyćepane)',
'rev-deleted-text-permission' => 'Wersyjo tyj zajty uostoua wyćepano a ńy je dostympna publičńy. Ščygůuy idźe znejść we [{{fullurl:{{#Special:Log}}/suppress|page={{PAGENAMEE}}}} rejeře wyćepań].',
'rev-deleted-text-view'       => 'Ta wersyjo zajty uostoua wyćepano a ńy je dostympna publičńy.
Atoli kej admińistrator {{GRAMMAR:MS.lp|{{SITENAME}}}} možeš jům uobejřeć.
Powody wyćepańo idźe znejść we [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} rejeře wyćepań]',
'rev-delundel'                => 'ukoż/schrůń',
'revisiondelete'              => 'Wyćep/wćep nazod wersyje',
'revdelete-nooldid-title'     => 'Ńy wybrano wersyji',
'revdelete-nooldid-text'      => 'Ńy wybrano wersyji na kerych mo zostać wykůnano ta uoperacyjo.',
'revdelete-selected'          => "'''{{PLURAL:$2|Wybrano wersyja|Wybrane wersyje}} zajty [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Wybrane zdařyńy s rejeru|Wybrane zdařyńa s rejeru}}:'''",
'revdelete-text'              => "'''Wyćepane wersyje bydům dali widočne w historyji zajty, nale jejich treść ńy bydźe publičńy dostympna.'''

Inkśi admińistratoři {{GRAMMAR:D.lp|{{SITENAME}}}} dali bydům mjeć dostymp do schrůńůnych wersyji a bydům můgli je wćepać nazod, chyba aže uoperator serwisu nouožůu dodatkowe uograńičyńo.",
'revdelete-legend'            => 'Naštaluj uograńičyńo lo wersyji:',
'revdelete-hide-text'         => 'Schrůń tekst wersyji',
'revdelete-hide-image'        => 'Schrůń zawartość plika',
'revdelete-hide-name'         => 'Schrůń akcyjo a cyl',
'revdelete-hide-comment'      => 'Schrůń kůmyntoř sprowjyńo',
'revdelete-hide-user'         => 'Schrůń mjano užytkowńika/adres IP',
'revdelete-hide-restricted'   => 'Wprowadź te uograńičyńo zarůwno lo admińistratorůw jak i lo inkšych',
'revdelete-radio-set'         => 'Ja',
'revdelete-radio-unset'       => 'Ńy',
'revdelete-suppress'          => 'Schrůń informacyje zarůwno před admińistratorůma jak i před inkšymi',
'revdelete-unsuppress'        => 'Usůń uograńičyńo lo wćepanej nazod historyje pomjyńań',
'revdelete-log'               => 'Čymu:',
'revdelete-submit'            => 'Zaakceptuj do wybrany{{PLURAL:$1|j wersyji|ch wersyji}}',
'revdelete-success'           => 'Půmyślńy zmjyńůno widoczność wersyji.',
'revdelete-failure'           => 'Feler przi zmjyńůńu widoczności wersyji.
$1',
'logdelete-success'           => 'Půmyślńy půmjyńůno widočność zdařyń',
'logdelete-failure'           => 'Feler przi zmjyńe widoczości rejera.
$1',
'revdel-restore'              => 'půmjyń widoczność',
'revdel-restore-deleted'      => 'wyćepane wersyje',
'revdel-restore-visible'      => 'widoczne wersyje',
'pagehist'                    => 'Historyjo sprowjyń zajty',
'deletedhist'                 => 'Wyćepano historyjo sprowjyń',
'revdelete-hide-current'      => 'Feler przi wyćepywańu wersyji $2, $1.',
'revdelete-show-no-access'    => 'Feler przy ukozoniu wersyji $2, $1. Ńy mosz uprawńyń lo njygo.',
'revdelete-modify-no-access'  => 'Feler przy zmjyńe widoczności wersyji $2, $1. Ńy mosz uprawńeń lo njygo.',
'revdelete-modify-missing'    => 'Feler. Ńy mo elementu $1 w bazie.',
'revdelete-no-change'         => "''''Dej pozůr''': element $2, $1 mo już ustawjonům widoczność.",
'revdelete-concurrent-change' => 'Feler. Pomjyno już element $2, $1. Prosza uoboczyć to w rejerze.',
'revdelete-only-restricted'   => 'Ńy możno ukryć elementu $2, $1 przed administracyjom. Wybjer jydnom ze opcyji.',
'revdelete-reason-dropdown'   => '* Kůmyntorze lo wyćepańa
** NPA
** Prywatność',
'revdelete-otherreason'       => 'Inkszy/dodatkowy powůd:',
'revdelete-reasonotherlist'   => 'Inkszy powůd',
'revdelete-edit-reasonlist'   => 'Sprowjańe powodůw wyćepańo zajty',
'revdelete-offender'          => 'Autor wersyji:',

# Suppression log
'suppressionlog'     => 'Log schrůńyńć',
'suppressionlogtext' => 'Půńiżyj je lista nojnowszych wyćepań i zawarć s uwzglyndńyńym treśći schrůńůnej lo admińistratorůw. Coby przřejrzeć lista aktualnych banůw i zawarć, uobezdrzij [[Special:BlockList|IP block list]].',

# History merging
'mergehistory'                     => 'Pouůnč historyjo půmjyńań zajtůw',
'mergehistory-header'              => 'Ta zajta dozwolo pouůnčyć historyje půmjyńań jydnyj zajty s inkšům, nowšům zajtům. Dej pozůr, coby sprawjyńy douo ćůnguo historyjo půmjyńań zajty w jeji historyji.',
'mergehistory-box'                 => 'Pouůnč historyjo sprowjyń dwůch zajtůw:',
'mergehistory-from'                => 'Zajta zdřůduowo:',
'mergehistory-into'                => 'Zajta docelowo:',
'mergehistory-list'                => 'Historyjo půmjyńań idźe pouůnčyć',
'mergehistory-merge'               => 'Nastympujůnce půmjyńyńo zajty [[:$1]] idźe scalić s [[:$2]]. Uoznač w kolůmńy kropkům kero zmjana, uůnčńy s wčeśńijšymi, mo być scalůno. Užyće cajchůndzkůw uod nawigacyji kasuje wybůr we kolůmńy.',
'mergehistory-go'                  => 'Pokož půmjyńańo kere idźe scalić',
'mergehistory-submit'              => 'Scal historyjo půmjyńań',
'mergehistory-empty'               => 'Ńy mo historyje zmjan do scalyńa.',
'mergehistory-success'             => '$3 {{PLURAL:$3|pomjyńańe|pomjyńańa|pomjyńań}} w [[:$1]] ze sukcesym zostouo scalonych ze [[:$2]].',
'mergehistory-fail'                => 'Ńy idźe scalić historyje půmjyńań. Zmjyń štalowańo parametrůw tyj uoperacyji.',
'mergehistory-no-source'           => 'Ńy mo sam zajty zdřůduowyj $1.',
'mergehistory-no-destination'      => 'Ńy ma sam zajty docelowyj $1.',
'mergehistory-invalid-source'      => 'Zajta zdřůduowo muśi mjeć poprowne mjano.',
'mergehistory-invalid-destination' => 'Zajta docelowo muśi mjeć poprowne mjano.',
'mergehistory-autocomment'         => 'Historyjo [[:$1]] scalono ze [[:$2]]',
'mergehistory-comment'             => 'Historyjo [[:$1]] pouůnčůno ze [[:$2]]: $3',
'mergehistory-same-destination'    => 'Zajta zdřuduowo a docelowo ńy mogům być te same.',
'mergehistory-reason'              => 'Kůmyntorz:',

# Merge log
'mergelog'           => 'Pouůnčůne',
'pagemerge-logentry' => 'Pouůnčůno [[$1]] ze [[$2]] (historja pomjyńań aže do $3)',
'revertmerge'        => 'Uodkupluj',
'mergelogpagetext'   => 'Půńižej znojduje śe lista uostatńich pouůnčyń historyji půmjyńań zajtůw.',

# Diffs
'history-title'            => 'Historyjo sprowjyń "$1"',
'difference'               => '(Růžńice mjyndzy škryflańami)',
'difference-multipage'     => '(Porůwnańje zajt)',
'lineno'                   => 'Lińijo $1:',
'compareselectedversions'  => 'zrůwnej uobrane wersyje',
'showhideselectedversions' => 'Ukoż/ukryj uobrane wersyje',
'editundo'                 => 'uodćepej',
'diff-multi'               => '(Ńy pokozano {{PLURAL:$1|jydnyj wersyji postrzedńij|$1 wersyji postrzedńich}}, sprowjanej bez {{PLURAL:$2|jydnygo sprowjorza|$2 sprowjorzow}} .)',
'diff-multi-manyusers'     => '(Ńy pokozano {{PLURAL:$1|jydnyj wersyji postrzedńij|$1 wersyji postrzedńich}}, sprowjanej bez {{PLURAL:$2|jydnygo sprowjorza|$2 sprowjorzow}} .)',

# Search results
'searchresults'                    => 'Wyńiki sznupańo',
'searchresults-title'              => 'Wyniki sznupańo za „$1”',
'searchresulttext'                 => 'Coby dowjydźeć śe wjyncyj uo šnupańu w {{GRAMMAR:D.lp|{{SITENAME}}}}, uobezdřij [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Wyńiki šnupańo za "[[:$1]]" ([[Special:Prefixindex/$1|zajty kere začynajům śe uod „$1”]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|zajty kere sům adresowane do „$1”]])',
'searchsubtitleinvalid'            => 'Lo zapytańo "$1"',
'toomanymatches'                   => 'Za dužo elymyntůw kere pasujům do wzorca, wćep inkše zapytańy',
'titlematches'                     => 'Znejdźono we titlach:',
'notitlematches'                   => 'Ńy znejdźono we titlach',
'textmatches'                      => 'Znejdźono na zajtach:',
'notextmatches'                    => 'Ńy znejdźono we tekście zajtůw',
'prevn'                            => 'poprzedńe {{PLURAL:$1|$1}}',
'nextn'                            => 'nastympne {{PLURAL:$1|$1}}',
'prevn-title'                      => '{{PLURAL:$1|Poprzedńi|Poprzedńe}} $1 {{PLURAL:$1|wyńik|wyńiki|wyńikůw}}',
'nextn-title'                      => '{{PLURAL:$1|Dolszy|Dolsze|Dolszych}} $1 {{PLURAL:$1|wyńik|wyńiki|wyńikůw}}',
'shown-title'                      => 'Ukoż $1 {{PLURAL:$1|wynik|wyniki|wynikůw}} lo zajta',
'viewprevnext'                     => 'Uobźyrej ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Uopcyje sznupańo',
'searchmenu-exists'                => "'''Ńy ma zajty uo mjańy \"[[:\$1]]\" na tyj wiki'''",
'searchmenu-new'                   => "'''Stwůrz zajta „[[:$1|$1]]” na tyj wiki!'''",
'searchhelp-url'                   => 'Help:Pomoc',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Przeglůndej zajty kere s anfanga majům tyn przedrostek]]',
'searchprofile-articles'           => 'Zajty',
'searchprofile-project'            => 'Zajty půmocy a projektu',
'searchprofile-images'             => 'Multimedyja',
'searchprofile-everything'         => 'Wszyjsko',
'searchprofile-advanced'           => 'Rozszerzůne',
'searchprofile-articles-tooltip'   => 'Sznupańy we raumje mjan $1',
'searchprofile-project-tooltip'    => 'Sznupańy we raumach mjan $1',
'searchprofile-images-tooltip'     => 'Sznupańy za plikůma',
'searchprofile-everything-tooltip' => 'Sznupej we cołku (i ze zajtůma dyskusyje)',
'searchprofile-advanced-tooltip'   => 'Sznupańy we uobranych raumach mjan',
'search-result-size'               => '$1 ({{PLURAL:$2|1 słowo|$2 słowa|$2 słůw}})',
'search-result-category-size'      => '{{PLURAL:$1|1 element|$1 elementy|$1 elementów}} ({{PLURAL:$2|1 kategoryjo|$2 kategoryje|$2 kategoryje}}, {{PLURAL:$3|1 uobrozek|$3 uobrozki|$3 uobrozkow}})',
'search-result-score'              => 'Akuratność: $1%',
'search-redirect'                  => '(půnkńyńćy $1)',
'search-section'                   => '(tajla $1)',
'search-suggest'                   => 'Myśloł żeś: $1 ?',
'search-interwiki-caption'         => 'Śostřane projekty',
'search-interwiki-default'         => '$1 wyńiki:',
'search-interwiki-more'            => '(wjyncyj)',
'search-mwsuggest-enabled'         => 'ze sůgestyjůma',
'search-mwsuggest-disabled'        => 'ńy mo sůgestyji',
'search-relatedarticle'            => 'Podane',
'mwsuggest-disable'                => 'Wyuůnč sůgestyje AJAX',
'searcheverything-enable'          => 'Sznupej we wszech mjan',
'searchrelated'                    => 'podane',
'searchall'                        => 'wszyjske',
'showingresults'                   => "To lista na keryj je {{PLURAL:$1|'''1''' wyńik|'''$1''' wyńikůw}}, počynojůnc uod nůmeru '''$2'''.",
'showingresultsnum'                => "To lista na keryj je {{PLURAL:$3|'''1''' wyńik|'''$3''' wyńikůw}}, počynojůnc uod nůmeru '''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Wyńik '''$1''' z '''$3'''|Wyńiki '''$1 – $2''' z '''$3'''}} lo '''$4'''",
'nonefound'                        => "'''Dej pozůr''': Důmyślńy přešukiwane sům ino ńykere přestřyńy mjan. Poprůbuj popředźić wyšukiwano fraza předrostkym ''all:'', co spowoduje přešukańy coukij zawartośći {{GRAMMAR:D.lp|{{SITENAME}}}} (wůunčńy ze zajtami godki, šablůnůma atp.), abo poprůbuj užyć kej předrostka wybranyj, jydnyj přestřyńi mjan.",
'search-nonefound'                 => 'Ńy mo wynikůw, kere uodpadajům kryterjům zapytańo.',
'powersearch'                      => 'Sznupańy zaawansowane',
'powersearch-legend'               => 'Šnupańy zaawansowane',
'powersearch-ns'                   => 'Šnupej we přestřyńach mjan:',
'powersearch-redir'                => 'Pokož překerowańa',
'powersearch-field'                => 'Šnupej',
'powersearch-togglelabel'          => 'Zaznocz:',
'powersearch-toggleall'            => 'Wszyjsko',
'powersearch-togglenone'           => 'żodno',
'search-external'                  => 'Šnupańy zewnyntřne',
'searchdisabled'                   => 'Šnupańy we {{GRAMMAR:MS.lp|{{SITENAME}}}} zostouo zawarte. Zańim go zouůnčům, možeš sprůbować šnupańo bez Google. Ino zauwaž, co informacyje uo treśći {{GRAMMAR:MS.lp|{{SITENAME}}}} můgům być we Google ńyakuratne.',

# Quickbar
'qbsettings'               => 'Gurt šybkigo dostympu',
'qbsettings-none'          => 'Brak',
'qbsettings-fixedleft'     => 'Stouy, s lewyj',
'qbsettings-fixedright'    => 'Stouy, s prawyj',
'qbsettings-floatingleft'  => 'Unošůncy śe, s lewyj',
'qbsettings-floatingright' => 'Unošůncy śe, s prawyj',

# Preferences page
'preferences'                   => 'Preferyncyje',
'mypreferences'                 => 'Moje preferyncyje',
'prefs-edits'                   => 'Ličba sprowjyń:',
'prefsnologin'                  => 'Ńy ježeś zalůgowany',
'prefsnologintext'              => 'Muśiš śe <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} zalůgować]</span> coby štalować swoje preferyncyje.',
'changepassword'                => 'Zmjana hasua',
'prefs-skin'                    => 'Skůrka',
'skin-preview'                  => 'podglůnd',
'datedefault'                   => 'Důmyślny',
'prefs-beta'                    => 'Testowe funkcyje',
'prefs-datetime'                => 'Data a czas',
'prefs-labs'                    => 'Funkcyje "labs"',
'prefs-personal'                => 'Dane užytkowńika',
'prefs-rc'                      => 'Ńydowno pomjyńane',
'prefs-watchlist'               => 'Pozůrlista',
'prefs-watchlist-days'          => 'Ličba dńi widočnych na liśće artikli, na kere dowoš pozůr:',
'prefs-watchlist-days-max'      => 'Maksimum 7 dńi',
'prefs-watchlist-edits'         => 'Ličba půmjyńań pokazywanych we rozšyřůnyj liśće artiklůw, na kere dowoš pozůr:',
'prefs-watchlist-edits-max'     => 'Maksymalno liczba: 1000',
'prefs-watchlist-token'         => 'ID půzorlisty:',
'prefs-misc'                    => 'Roztůmajte',
'prefs-resetpass'               => 'Zmjyń hasło',
'prefs-email'                   => 'E-brif',
'prefs-rendering'               => 'Wyglůnd',
'saveprefs'                     => 'Naškryflej',
'resetprefs'                    => 'Preferencyje důmyślne',
'restoreprefs'                  => 'Wćep wszyjskie důmyślne preferencyje',
'prefs-editing'                 => 'Sprowjańy',
'prefs-edit-boxsize'            => 'Rozmjor uokna edycyji.',
'rows'                          => 'Wjerše:',
'columns'                       => 'Kůlumny:',
'searchresultshead'             => 'Šnupańy',
'resultsperpage'                => 'Ličba wyńikůw na zajće',
'stub-threshold'                => 'Maksymalny rozmjar artikla uoznačanygo kej <a href="#" class="stub">stub (kůnsek)</a>',
'stub-threshold-disabled'       => 'Uodymkńynte',
'recentchangesdays'             => 'Ličba dńi do pokazańo we půmjyńanych na uostatku:',
'recentchangesdays-max'         => '(maksymalńy $1 {{PLURAL:$1|dźyń|dńi}})',
'recentchangescount'            => 'Liczba pozycyji na liśće půmjyńanych na uostatku, we historyje zajtůw a zajtach rejerůw:',
'prefs-help-recentchangescount' => 'Ze listům ńydawnych pomjyńan, gyszichta zajt a rejer.',
'savedprefs'                    => 'Twoje štalowańo we preferyncyjach zostouy naškryflane.',
'timezonelegend'                => 'Strefa czasowo',
'localtime'                     => 'Lokalny czas:',
'timezoneuseserverdefault'      => 'Użyj domyślnygo czasu serwera ($1)',
'timezoneuseoffset'             => 'Inkszo (uokryśl różnica czasu)',
'timezoneoffset'                => 'Dyferencyjo ¹:',
'servertime'                    => 'Czas serwera:',
'guesstimezone'                 => 'Pobjer z přeglůndarki',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Ameryka',
'timezoneregion-antarctica'     => 'Antarktyda',
'timezoneregion-arctic'         => 'Arktyka',
'timezoneregion-asia'           => 'Azyjo',
'timezoneregion-atlantic'       => 'Uoceon Atlantycki',
'timezoneregion-australia'      => 'Australyjo',
'timezoneregion-europe'         => 'Ojropa',
'timezoneregion-indian'         => 'Ocean Indyjski',
'timezoneregion-pacific'        => 'Uocean Spokojny',
'allowemail'                    => 'Inkśi užytkowńicy můgům přesyuać mje e-brify',
'prefs-searchoptions'           => 'Uopcyje šnupańo',
'prefs-namespaces'              => 'Přystřyńe mjan',
'defaultns'                     => 'Důmyślńy sznupej we nastympujůncych przystrzyńach mjan:',
'default'                       => 'důmyślńy',
'prefs-files'                   => 'Pliki',
'youremail'                     => 'E-brif:',
'username'                      => 'Mjano užytkowńika:',
'uid'                           => 'ID užytkowńika:',
'prefs-memberingroups'          => 'Naležy do {{PLURAL:$1|grupy|grup:}}',
'yourrealname'                  => 'Prawdźiwe mjano',
'yourlanguage'                  => 'Godka interfejsu',
'yournick'                      => 'Twoja šrajba:',
'badsig'                        => 'Felerno šrajba, sprowdź značńiki HTML.',
'badsiglength'                  => 'Twůj šrajbůng je za dugi. Maksymalno jego dugość to $1 {{PLURAL:$1|buchštaby|buchštabůw}}',
'gender-male'                   => 'chop',
'gender-female'                 => 'baba',
'email'                         => 'E-brif',
'prefs-help-realname'           => '* Mjano a nazwisko (uopcjůnalńy): jak žeś zdecydowou aže je podoš, bydům užyte, coby Twoja robota mjoua atrybucyjo.',
'prefs-help-email'              => 'Ukozańy e-brifowygo adresu ńy je powinne, nale nutne, coby resetować ausdruk, eli zapomńisz.',
'prefs-help-email-others'       => 'Mogesz tyż uůmożnić inkszym używoczům posłać ci e-brif bez twojo zajta używocza abo zajta dyskusyje. Twůj e-brifowy adres śe ńy ukoże.',
'prefs-help-email-required'     => 'Wymogany je adres e-brifa.',

# User rights
'userrights'                  => 'Zařůndzańy prowami užytkowńikůw',
'userrights-lookup-user'      => 'Zařůndzej prowami užytkownika',
'userrights-user-editname'    => 'Wklepej sam nazwa užytkowńika:',
'editusergroup'               => 'Sprowjej grupy užytkowńika',
'editinguser'                 => "Zmjana uprawńyń užytkowńika '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => 'Sprowjej grupy užytkowńika',
'saveusergroups'              => 'Zapisz',
'userrights-groupsmember'     => 'Noležy do:',
'userrights-groups-help'      => 'Možeš půmjyńać přinoležność tygo užytkowńika do podanych grup.
*Zaznačůne pole uoznačo přinoležność užytkowńika do danej grupy.
*Ńy zaznačůne pole uoznačo, aže užytkowńik ńy noležy do danej grupy.
* Gwjozdka * infomuje, co ńy možeš wyćepać s grupy po dodańu do ńij abo dodać po wyćepańu s grupy.',
'userrights-reason'           => 'Čymu:',
'userrights-no-interwiki'     => 'Ńy moš dostympu do sprowjańo uprawńyń.',
'userrights-nodatabase'       => 'Baza danych $1 ńy istńije abo ńy je lokalno.',
'userrights-nologin'          => 'Muśiš [[Special:UserLogin|zalůgować śe]] na kůnto admińistratora, coby nadować uprawńyńo užytkowńikům.',
'userrights-notallowed'       => 'Ńy moš dostympu do nadawańo uprawńyń užytkowńikům.',
'userrights-changeable-col'   => 'Grupy, kere možeš wybrać',
'userrights-unchangeable-col' => 'Grupy, kerych ńy možeš wybrać',

# Groups
'group'               => 'Grupa:',
'group-user'          => 'Užytkowńiki',
'group-autoconfirmed' => 'Autůmatyčńy zatwjerdzůne užytkowńiki',
'group-bot'           => 'Boty',
'group-sysop'         => 'Admińi',
'group-bureaucrat'    => 'Bjurokraty',
'group-suppress'      => 'Rewizoře',
'group-all'           => '(wšyjscy)',

'group-user-member'          => 'Sprowjorz',
'group-autoconfirmed-member' => 'Autůmatyčńy zatwjerdzůny užytkowńik',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Admin',
'group-bureaucrat-member'    => 'Bjurokrata',
'group-suppress-member'      => 'Rewizůr',

'grouppage-user'          => '{{ns:project}}:Sprowjorze',
'grouppage-autoconfirmed' => '{{ns:project}}:Autůmatyčńy zatwjerdzyńi užytkowńiki',
'grouppage-bot'           => '{{ns:project}}:Boty',
'grouppage-sysop'         => '{{ns:project}}:Admińistratory',
'grouppage-bureaucrat'    => '{{ns:project}}:Bjurokraty',
'grouppage-suppress'      => '{{ns:project}}:Rewizoře',

# Rights
'right-read'                 => 'Čytej zajty',
'right-edit'                 => 'Sprowjej zajty',
'right-createpage'           => 'Utwořůne zajty (kere ńy sům zajtůma godki)',
'right-createtalk'           => 'Utwořůne zajty godki',
'right-createaccount'        => 'Utwořůne nowe kůnta užytkowńikůw',
'right-minoredit'            => 'Uoznoč půmjyńańo kej drobne',
'right-move'                 => 'Přećepane zajty',
'right-move-subpages'        => 'Přećep zajty wroz s jejich podzajtůma',
'right-move-rootuserpages'   => 'Překludzańy zajtůw uod užytkowńikůw',
'right-suppressredirect'     => 'Ńy twůř překerowańo ze starygo mjana jak přećepuješ zajta',
'right-upload'               => 'Wćepane pliki',
'right-reupload'             => 'Nadpisuj pliki kere sam juž sům wćepane',
'right-reupload-own'         => 'Nadpisuj plik wćepany sam bez tygo somygo užytkowńika',
'right-reupload-shared'      => 'Nadpisuj pliki ůmješčůne w repozytorjům dźelůnych plikůw na lokalnyj kopje',
'right-upload_by_url'        => 'Wćepńij sam plik ze adresa URL',
'right-purge'                => 'Wyčyść pamjyńć podrynčno do zajty za wyjůntkym zajty potwjerdzyńo',
'right-autoconfirmed'        => 'Sprowjej zajty zawarte lo ńyzalůgowanych',
'right-bot'                  => 'Traktuj kej proces autůmatyčny',
'right-nominornewtalk'       => 'Wyuůnčyńy uopcyje powjadamjańo uo drobnych půmjyńańach na zajće godki',
'right-apihighlimits'        => 'Užywej uograńičyń wjelgości we zapytańach do API',
'right-writeapi'             => 'Zapisuj bez interfejs API',
'right-delete'               => 'Wyćep zajty',
'right-bigdelete'            => 'Wyćep zajty s dugům historyjům půmjyńań',
'right-deleterevision'       => 'Wyćepywańy a wćepywańy nazod wskazanych sprowjyń zajtůw',
'right-deletedhistory'       => 'Pokazuj historyjo usuńyntych sprowjyń, bez tekstu uopisu',
'right-browsearchive'        => 'Šnupej za wyćepanymi zajtůma',
'right-undelete'             => 'Wćepej nazod wyćepano zajta',
'right-suppressrevision'     => 'Přyglůndańy i uodtwařańy sprowjyń schrůńůnych před admińistratorami',
'right-suppressionlog'       => 'Pokož prywatne lůgi',
'right-block'                => 'Zawjyrańy sprowjorzům możebnośći edytowańo',
'right-blockemail'           => 'Zablokuj užytkowńikowi wysyuańy e-brifůw',
'right-hideuser'             => 'Zablokuj mjano užytkowńika i schrůń to před publičnym dostympym',
'right-ipblock-exempt'       => 'Uobejdź zawarća uod sprowjyń do IP, autozawarća i zawarća zakresůw',
'right-proxyunbannable'      => 'Uobejdź autůmatyčne zawarća uod sprowjyń do proxy',
'right-protect'              => 'Zmjyń poźůmy zawarć i sprowjej zawarte zajty',
'right-editprotected'        => 'Sprowjej zawarte zajty (ze zawarćym kaskadowym)',
'right-editinterface'        => 'Sprowjej interfejs užytkowńika',
'right-editusercssjs'        => 'Sprowjej pliki CSS i JS inkšych užytkowńikůw',
'right-editusercss'          => 'Sprowjej pliki CSS inkšych užytkowńikůw',
'right-edituserjs'           => 'Sprowjej pliki JS inkšych užytkowńikůw',
'right-rollback'             => 'Rewert drap sprawjyńo uostatńygo užytkowńika kery sprawjou dano zajta',
'right-markbotedits'         => 'Uoznoč rewertowane sprawjyńo kej sprawjyńo botůw',
'right-noratelimit'          => 'Ńy mo uograńičyń přepustowośći',
'right-import'               => 'Import zajtůw s inkšych Wiki',
'right-importupload'         => 'Import zajtůw ze wćepanygo plika',
'right-patrol'               => 'Uoznoč sprowjyńo kej přezdřane',
'right-autopatrol'           => 'Naštaluj na autůmatyčne uoznačańy sprowjyń kej přezdřane',
'right-patrolmarks'          => 'Podglůnd značnikůw patrolowańo pomjeńanych na uostatku – uoznačańo kej „sprawdzůne”',
'right-unwatchedpages'       => 'Pokož lista zajtůw na kere žodyn ńy dowo pozoru',
'right-trackback'            => 'Přeślij trackback',
'right-mergehistory'         => 'Pouůnč historyjo sprowjyń do zajtůw',
'right-userrights'           => 'Sprowjej wšyjske uprawńyńo užytkowńikůw',
'right-userrights-interwiki' => 'Sprowjej uprawńyńo užytkowńikůw na zajtach inkšych Wiki',
'right-siteadmin'            => 'Zawjerańy i uodmykańy bazy danych',

# User rights log
'rightslog'      => 'Uprawńyńa',
'rightslogtext'  => 'Rejer půmjyńań uprawńyń užytkowńikůw.',
'rightslogentry' => 'půmjyńiu/a uprawńyńo užytkowńika $1 ($2 → $3)',
'rightsnone'     => 'podstawowo',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'přeglůndańo tyj zajty',
'action-edit'                 => 'sprowjańo tyj zajty',
'action-createpage'           => 'twořyńo zajtůw',
'action-createtalk'           => 'twořyńo zajtůw godki',
'action-createaccount'        => 'utwořyńo tygo kůnta užytkowńika',
'action-minoredit'            => 'do uoznačyńo tygo sprowjyńo kej drobne půmjyńańe',
'action-move'                 => 'přećepańe tyj zajty',
'action-move-subpages'        => 'přećepańo tyj zajty uoroz s jeij podzajtůma',
'action-move-rootuserpages'   => 'Překludzańy zajtůw uod užytkowńikůw (nale bes jeich podzajtůw)',
'action-upload'               => 'wćepńyńćo tygo plika',
'action-reupload'             => 'nadpisańo tygo plika',
'action-reupload-shared'      => 'nadpisańo tygo plika we wspůlnym repozytorjům',
'action-upload_by_url'        => 'wćepańo tygo plika s adresa URL',
'action-writeapi'             => 'naškryflańo bez interfejs API',
'action-delete'               => 'wyćepańo tyj zajty',
'action-deleterevision'       => 'wyćepańo tyj wersyje',
'action-deletedhistory'       => 'wejzdřyńo we historyjo wyćepań tyj zajty',
'action-browsearchive'        => 'šnupańo za wyćepanymi zajtami',
'action-undelete'             => 'wćepańo nazod tyj zajty',
'action-suppressrevision'     => 'podglůndu a wćepańo nazod tyj wersyje schrůńůnyj',
'action-suppressionlog'       => 'podglůndu rejera schrůńańo',
'action-block'                => 'zawarća uod sprowjyń tygo spowjořa',
'action-protect'              => 'půmjyńań poźůmu zawarćo tyj zajty',
'action-import'               => 'importu tyj zajty s inkšyj wiki',
'action-importupload'         => 'importu tyj zajty bez wćepańe plika',
'action-patrol'               => 'označyńo sprowjyńo kej „sprowdzůne”',
'action-autopatrol'           => 'uoznačyńo wuasnygo sprowjyńo kej „sprawdzonygo”',
'action-unwatchedpages'       => 'podglůndu listy zajtůw na kere ńikt ńy dowo pozoru',
'action-trackback'            => 'wysyuańo trackbacka',
'action-mergehistory'         => 'skuplowańo historyje sprowjyń tyj zajty',
'action-userrights'           => 'sprowjańo uprowńyń wšyjstkich sprowjořy',
'action-userrights-interwiki' => 'sprowjańo uprowńyń sprowjořy na inkšych witrynach wiki',
'action-siteadmin'            => 'zawarćo a uodymkńyńćo bazy danych',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|pomjyńańe|pomjyńańa|pomjyńań}}',
'recentchanges'                     => 'Ńydowno půmjyńane',
'recentchanges-legend'              => 'Uopcyje ńydowno půmjyńanych',
'recentchangestext'                 => 'Ta zajta předstawjo historyjo uostatńich půmjyńań na tyj wiki',
'recentchanges-feed-description'    => 'Dowej pozůr na půmjyńane na uostatku na tyj wiki.',
'recentchanges-label-newpage'       => 'Tym sprowjyńym stworzůno nowa zajta',
'recentchanges-label-minor'         => 'To je ńywjelge sprowjyńy',
'recentchanges-label-bot'           => 'To sprowjyńy bůło zrobjůne uod bota',
'recentchanges-label-unpatrolled'   => 'To sprowjyńy ńy bůło jeszcze uowjerzůne',
'rcnote'                            => "Půńižej {{PLURAL:$1|pokozano uostatńo zmjano dokůnano|pokazano uostatńy '''$1''' zmjany naškryflane|pokozano uostatńich '''$1''' škryflań zrobjůnych}} bez {{PLURAL:$2|uostatńi dźyń|uostatńich '''$2''' dńi}}, začynojůnc uod $5 dńa $4.",
'rcnotefrom'                        => 'Půńižej pokazano půmjyńańo zrobjůne pů <b>$2</b> (ńy wjyncyj jak <b>$1</b> pozycji).',
'rclistfrom'                        => 'Ukoż půmjyńańa uod $1',
'rcshowhideminor'                   => '$1 drobne půmjyńańa',
'rcshowhidebots'                    => '$1 boty',
'rcshowhideliu'                     => '$1 nalogowanych używoczůw',
'rcshowhideanons'                   => '$1 anůńimowych',
'rcshowhidepatr'                    => '$1 uowjerzůne',
'rcshowhidemine'                    => '$1 uody mje sprowjůne',
'rclinks'                           => 'Ukoż uostatńe $1 sprowjyń bez uostatńe $2 dńi.<br />$3',
'diff'                              => 'zmj.',
'hist'                              => 'gysz.',
'hide'                              => 'Schrůń',
'show'                              => 'Ukoż',
'minoreditletter'                   => 'd',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|dowajůncy pozůr užytkowńik|dowajůncych pozůr užytkowńikůw}}]',
'rc_categories'                     => 'Uůgrańič do katygorii (oddźelej za půmocům "|")',
'rc_categories_any'                 => 'Wšyskie',
'newsectionsummary'                 => '/* $1 */ nowo tajla',
'rc-enhanced-expand'                => 'Pokož ščygůuy (wymogo JavaScript)',
'rc-enhanced-hide'                  => 'Schrůń detajle',

# Recent changes linked
'recentchangeslinked'          => 'Půmjyńańa we nalinkowanych',
'recentchangeslinked-feed'     => 'Pomjyńańa we adresowanych',
'recentchangeslinked-toolbox'  => 'Půmjyńańa we nalinkowanych',
'recentchangeslinked-title'    => 'Pomjyńyńo w adrésowanych s "$1"',
'recentchangeslinked-noresult' => 'Nikt nic niy pomjyńoł w dolinkowanych bez čas uo kery žeś pytou.',
'recentchangeslinked-summary'  => "Ńiżyj je lista ńydowno půmjyńanych na zajtach, na kere uobrano zajta linkuje (abo wszyjskich zajtach patrzůncych do uobranyj kategoryje).
Zajty z [[Special:Watchlist|pozůrlisty]] sům '''rube'''",
'recentchangeslinked-page'     => 'Mjano zajty',
'recentchangeslinked-to'       => 'Ukoż půmjyńańa na zajtach, kere linkujům na uobrano zajta',

# Upload
'upload'                      => 'Wćepej plik',
'uploadbtn'                   => 'Wćepej sam plik',
'reuploaddesc'                => 'Nazod do formulařa uod wćepywańo.',
'uploadnologin'               => 'Ńy jest žeś zalogůwany',
'uploadnologintext'           => 'Muśyš śe [[Special:UserLogin|zalůgować]] ńim wćepńeš pliki.',
'upload_directory_missing'    => 'Katalog lo wćepywanych plikůw ($1) ńy istńeje a serwer WWW ńy poradźi go utwořić.',
'upload_directory_read_only'  => 'Serwer ńy može škryflać do katalůgu ($1) kery je přeznačůny na wćepywane pliki.',
'uploaderror'                 => 'Feler při wćepywańu',
'uploadtext'                  => "Ůžyj formulařa půńižej do wćepywańo plikůw.
Jak chceš přejřeć dotychčas wćepane pliki, abo w ńich šnupać, přeńdź do [[Special:FileList|listy douůnčůnych plikůw]]. Wšyjstke wćepańo uodnotowane sům we [[Special:Log/upload|rejeře přesůuanych plikůw]], a jygo wyćepańy we [[Special:Log/delete|rejeře wyćepanych]].

Plik pojawi śe na zajće, jak užyješ linka wedle jydnygo s nastympujůncych wzorůw:
'''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:Plik.jpg]]</nowiki>''' pokože plik we pounyj postaći,
'''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:Plik.png|tekst uopisu]]</nowiki>''' pokože šyroko na 200 pikseli mińjaturka umjyščůno při lewym margineśe, uotočůno bez ramka, s podpisym „podpis grafiki”
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Plik.ogg]]</nowiki>''' dowo bezpostředńi link do plika ńy pokozujůnc go.",
'upload-permitted'            => 'Dopuščalne formaty plikůw: $1.',
'upload-preferred'            => 'Zalecane formaty plikůw: $1.',
'upload-prohibited'           => 'Zakozane formaty plikůw: $1.',
'uploadlog'                   => 'Wykoz wćepywań',
'uploadlogpage'               => 'Wćepane sam',
'uploadlogpagetext'           => 'Půńižej znojdowo śe lista plikůw wćepanych na uostatku.
Přelyź na zajta [[Special:NewFiles|galeryje nowych plikůw]], coby uobejzdřeć pliki kej mińjatůrki.',
'filename'                    => 'Mjano pliku',
'filedesc'                    => 'Popis',
'fileuploadsummary'           => 'Uopis:',
'filestatus'                  => 'Status prawny:',
'filesource'                  => 'Kod zdřůduowy:',
'uploadedfiles'               => 'Wćepane pliki',
'ignorewarning'               => 'Zignoruj uostřežyńo i wymuś wćepańe pliku.',
'ignorewarnings'              => 'Ignoruj uostřežyńo',
'minlength1'                  => 'Mjano plika muśi mjeć aby jedna buchštaba.',
'illegalfilename'             => 'Mjano plika ("$1") mo znoki zakozane we titlach zajtůw. Proša zmjyńić mjano plika i wćepać go zaś.',
'badfilename'                 => 'Mjano plika zostouo zmjyńone na "$1".',
'filetype-badmime'            => 'Wćepywanie plikůw ou typje MIME "$1" je sam zakozane.',
'filetype-bad-ie-mime'        => 'Ńy idźe załadować tygo plika, skiż tygo co Internet Explorer wykryje go kej „$1”, a tako zorta plika je zakozano kej potyncjolńy ńybezpjeczno.',
'filetype-unwanted-type'      => "'''\".\$1\"''' ńy je zalecanym typym plika. Preferowane sům pliki we {{PLURAL:\$3|formaće|formatach}} \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' je ńydozwolůnym typym plika. Dopuščalne sům pliki we {{PLURAL:\$3|formaće|formatach}} \$2.",
'filetype-missing'            => 'Plik ńy mo rozšyřyńo (np. ".jpg").',
'large-file'                  => 'Zaleco śe coby rozmjar plika ńy bůu wjynkšy jak $1 bajtůw. Tyn plik mo rozmjar $2 bajtůw.',
'largefileserver'             => 'Plik je wjynkšy ńiž maksymalny dozwolůny rozmjar.',
'emptyfile'                   => 'Wćepywany plik cheba je pusty. Može to być bez tůž, co žeś wklepou zuo buchštaba w jygo mjańe. Sprowdź, čy mjano kere žeś wklepou je poprawne.',
'fileexists'                  => "Plik uo takym mjańe juž je sam wćepany! Wćepańe nowyj grafiki ńyodwracalńe wyćepńe ta kero sam juž je wćepano ('''<tt>[[:$1]]</tt>''')! Sprowdź čy žeś je pewny co chceš tyn plik sam wćepać.
[[$1|thumb]]",
'filepageexists'              => "Je juž sam zajta uopisu tygo plika utwořůno '''<tt>[[:$1]]</tt>''', ino ńy ma terozki plika uo tym mjańy. Informacyje uo pliku, kere žeś wćepou, ńy bydům pokozane na zajće uopisu. Jakbyś chćou coby te informacyje zostouy pokozane, muśyš jeich sprowjać rynčńy.",
'fileexists-extension'        => "Plik uo podobnym mjańe juž sam je: [[$2|thumb]]
* Mjano wćepywanygo plika: '''<tt>[[:$1]]</tt>'''
* Mjano plika kery juž sam je: '''<tt>[[:$2]]</tt>'''
Wybjer proša inkše mjano.",
'fileexists-thumbnail-yes'    => "Zdowo śe co tyn plik je půmńijšůnům wersyjom grafiki ''(mińjaturkom)''. [[$1|thumb]]
Uobejřij plik: '''<tt>[[:$1]]</tt>'''.
Jak to je ta sama grafika, ino wjelgo, ńy muśiš juž jei sam zaś wćepywać.",
'file-thumbnail-no'           => "Mjano plika začyno śe uod '''<tt>$1</tt>'''. Zdowo śe, co to je půmńijšůna grafika ''(mińaturka)''.
Jak moš ta grafika we peunym rozmjaře - wćepej ja sam.
Jak chceš wćepać ta, bydźeš muśou zmjyńić mjano wćepywanygo terozki plika.",
'fileexists-forbidden'        => 'Plik uo takym mjańy juž sům můmy! Idź nazod i wćepej tyn plik pod inkšym mjanym. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Plik uo takym mjańe juž je wćepany na wspůlne repozytorjům plikůw.
Idź nazod i wćepej tyn plik pod inkšym mjanym. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Tyn plik je kopjům {{PLURAL:$1|plika|nastypujůncych plikůw:}}',
'file-deleted-duplicate'      => 'Identyczny plik jak tyn plik ([[:$1]]) zostoł wyćepany. Sprowdź historyja wyćepań tamtygo plika ńim wćepńesz go nazod.',
'uploadwarning'               => 'Uostřežyńe uo wćepywańu',
'savefile'                    => 'Naškryflej plik',
'uploadedimage'               => 'wćepano "[[$1]]"',
'overwroteimage'              => 'wćepano nowšo wersyjo "[[$1]]"',
'uploaddisabled'              => 'Wćepywanie sam plikůw je zawarte',
'uploaddisabledtext'          => 'Wćepywańe plikůw je zawarte.',
'uploadscripted'              => 'Tyn plik zawjyro kod HTML abo skrypt kery može zostać felerńe zinterpretowany bez přyglůndarka internetowo.',
'uploadvirus'                 => 'W tym pliku je wirus! Ščygůuy: $1',
'sourcefilename'              => 'Mjano oryginalne:',
'destfilename'                => 'Mjano docylowe:',
'upload-maxfilesize'          => 'Maksymalny rozmior plika: $1',
'watchthisupload'             => 'Dowej pozůr na ta zajta',
'filewasdeleted'              => 'Plik uo takym mjańy juž bůu sam wćepany, ale zostou wyćepńjynty. Ńim wćepńeš go zaś, sprowdź $1.',
'filename-bad-prefix'         => "Mjano plika, kery wćepuješ, začyno śe uod '''\"\$1\"''' &ndash; je to mjano nojčynśćy připisywane autůmatyčńy bez cyfrowe fotoaparaty, uůno ńy dowo žodnych informacyji uo zawartośći plika. Prošymy cobyś nadou plikowi inkše, lepij zrozůmjaue mjano.",
'upload-success-subj'         => 'Wćepańe plika udouo śe',

'upload-proto-error'      => 'Ńyprowiduowy protokůu',
'upload-proto-error-text' => 'Zdalne přesůuańy plikůw wymago podańo adresu URL kery začyno śe na <code>http://</code> abo <code>ftp://</code>.',
'upload-file-error'       => 'Wewnyntřny feler',
'upload-file-error-text'  => 'Wystůmpiu wewnyntřny feler kej průbowano naškryflać tymčasowy plik na serweře. Skůntaktuj śe s [[Special:ListUsers/sysop|admińistratorym systemu]].',
'upload-misc-error'       => 'Ńyznany feler při wćepywańu',
'upload-misc-error-text'  => 'Zašou ńyznůmy feler při wćepywańu. Sprawdź proša čy podany URL je poprawny a dostympny, a potym poprůbuj zaś. Jak problym bydźe śe powtařou dalij dej znoć ku [[Special:ListUsers/sysop|admińistratorowi systymu]].',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL je ńyosůngalny',
'upload-curl-error6-text'  => 'Podany URL je ńyosiůngalny. Proša, sprowdź dokuadńy čy podany URL je prawidouwy i čy dano zajta dźauo.',
'upload-curl-error28'      => 'Překročůny čas kery bůu na wćepywańe',
'upload-curl-error28-text' => 'Zajta uodpowjado za powoli. Proša, sprawdź čy zajta dźauo, uodčekej pora minut i sprůbuj zaś. Možeš tyž sprůbować wončas kej zajta bydźe mńij uobćůnžůno.',

'license'            => 'Licencyjo:',
'license-header'     => 'Licencyjo',
'nolicense'          => 'Ńy wybrano (naškryflej rynčńy!)',
'license-nopreview'  => '(Podglůnd ńydostympny)',
'upload_source_url'  => ' (poprowny, publičńy dostympny URL)',
'upload_source_file' => ' (plik na twojym komputře)',

# Special:ListFiles
'listfiles-summary'     => 'To je ekstra zajta na kery sům pokazywane wšyske pliki wćepane na serwer. Důmyślńy na wiyrchu listy wyśwjetlajům śe pliki wćepane na uostatku. Coby půmjyńić sposůb sortowańo, klikńij na naguůwek kolůmny.',
'listfiles_search_for'  => 'Šnupej za grafikům uo mjańe:',
'imgfile'               => 'plik',
'listfiles'             => 'Lista plikůw',
'listfiles_date'        => 'Data',
'listfiles_name'        => 'Mjano',
'listfiles_user'        => 'Užytkowńik',
'listfiles_size'        => 'Rozmior (bajty)',
'listfiles_description' => 'Uopis',

# File description page
'file-anchor-link'          => 'Plik',
'filehist'                  => 'Gyszichta pliku',
'filehist-help'             => 'Klikńij na datum/cas, coby uwidzieć, jak plik w tyn czas wypadoł.',
'filehist-deleteall'        => 'wyćep wszyske',
'filehist-deleteone'        => 'Wyćep',
'filehist-revert'           => 'cofej',
'filehist-current'          => 'aktualny',
'filehist-datetime'         => 'Datum a czas',
'filehist-thumb'            => 'Mińiwersyjo',
'filehist-thumbtext'        => 'Mińiwersyje $1',
'filehist-nothumb'          => 'Ńy ma mińjaturki',
'filehist-user'             => 'Sprowjorz',
'filehist-dimensions'       => 'Wymjyry',
'filehist-filesize'         => 'Rozmior plika',
'filehist-comment'          => 'Komyntorz',
'imagelinks'                => 'Używańy pliku',
'linkstoimage'              => '{{PLURAL:$1|Tako zajta linkuje|Take zajty linkujům}} do tygo plika:',
'linkstoimage-more'         => 'Wjyncyj jak $1 {{PLURAL:$1|zajta je adresowano|zajty sům adresowane|zajtůw je adresowanych}} do tygo plika.
Půńižšo lista pokozuje yno {{PLURAL:$1|pjyršy link|pjyrše $1 linki|pjyršych $1 linkůw}} do tygo plika.
Dostympno je tyž [[Special:WhatLinksHere/$2|pouno lista]].',
'nolinkstoimage'            => 'Žodno zajta ńy je adrésowano do tygo plika.',
'morelinkstoimage'          => 'Pokož [[Special:WhatLinksHere/$1|wjyncy uodnośnikůw]] do tygo plika.',
'duplicatesoffile'          => '{{PLURAL:$1|Nastympujůncy plik je kopjům|Nastympujůnce pliki sům kopjůma}} tygo plika:',
'sharedupload'              => 'Tyn plik je wćepńynty na $1 a inksze projekty tyż go mogům używać.',
'sharedupload-desc-here'    => 'Tyn plik śe nałoźi na $1 a idzie go użyć we inkszych projektach.
Niżyj sům informacyje ze [$2 zajty popisu] tygo pliku.',
'uploadnewversion-linktext' => 'Wćepńij nowšo wersyjo tygo plika',

# File reversion
'filerevert'                => 'Přiwracańy $1',
'filerevert-legend'         => 'Přiwracańy poprzedńy wersje plika',
'filerevert-intro'          => "Zamjeřoš přiwrůćić '''[[Media:$1|$1]]''' do wersje z [$4 $3, $2].",
'filerevert-comment'        => 'Kůmyntorz:',
'filerevert-defaultcomment' => 'Přiwrůcůno wersyjo z $2, $1',
'filerevert-submit'         => 'Přiwrůć',
'filerevert-success'        => "Plik '''[[Media:$1|$1]]''' zostou cofńynty do [wersyje $4 ze $3, $2].",
'filerevert-badversion'     => 'Ńy ma sam popředńij lokalnyj wersyji tygo plika s podanům datům.',

# File deletion
'filedelete'                  => 'Wyćepańe $1',
'filedelete-legend'           => 'Wyćep plik',
'filedelete-intro'            => "Wyćepuješ '''[[Media:$1|$1]]'''.",
'filedelete-intro-old'        => "Wyćepuješ wersyja plika '''[[Media:$1|$1]]''' s datům [$4 $3, $2].",
'filedelete-comment'          => 'Čymu:',
'filedelete-submit'           => 'Wyćep',
'filedelete-success'          => "Wyćepano plik '''$1'''.",
'filedelete-success-old'      => "Wyćepano plik '''[[Media:$1|$1]]''' we wersyje ze $3, $2.",
'filedelete-nofile'           => "Plika '''$1''' ńy ma.",
'filedelete-nofile-old'       => "Ńy ma sam zarchiwizowanyj wersje '''$1''' o atrybutach jake žeś podou.",
'filedelete-otherreason'      => 'Inkšy powůd:',
'filedelete-reason-otherlist' => 'Inkszy powůd',
'filedelete-reason-dropdown'  => '* Nojčynstše powody wyćepańa
** Narušyńy praw autorskych
** Kopja plika kery juž sam jest',
'filedelete-edit-reasonlist'  => 'Sprowjańe powodůw wyćepańo zajty',

# MIME search
'mimesearch'         => 'Sznupej MIME',
'mimesearch-summary' => 'Ta zajta ůmožliwjo šnupańe za plikůma wedle jeich typu MIME. Užyće: typtreśći/podtyp, np. <tt>image/jpeg</tt>.',
'mimetype'           => 'Typ MIME:',
'download'           => 'pobier',

# Unwatched pages
'unwatchedpages' => 'Zajty na kere ńy je dowany pozůr',

# List redirects
'listredirects' => 'Lista překerowań',

# Unused templates
'unusedtemplates'     => 'Ńyužywane šablôny',
'unusedtemplatestext' => 'Půńižej znojdowo śe lista wšyjstkich zajtůw s přestřyńi mjan {{ns:template}}, kere ńy sům užywane bez inkše zajty. Sprowdź inkše adresowańa ku šablůnům, ńim wyćepńeš ta zajta.',
'unusedtemplateswlh'  => 'ku adresatu',

# Random page
'randompage'         => 'Cufalno zajta',
'randompage-nopages' => 'We przestrzyńi mjan "$1" ńy ma żodnych zajtůw.',

# Random redirect
'randomredirect'         => 'Losowe překerowańy',
'randomredirect-nopages' => 'We przestrzyńi mjan "$1" ńy ma przekerowań.',

# Statistics
'statistics'                   => 'Statystyka',
'statistics-header-pages'      => 'Statystyka zajtůw',
'statistics-header-edits'      => 'Statystyka sprowjyń',
'statistics-header-views'      => 'Statystyka bezuchůw',
'statistics-header-users'      => 'Statystyka užytkowńikůw',
'statistics-articles'          => 'Zajty',
'statistics-pages'             => 'Zajty',
'statistics-pages-desc'        => 'Wszyjstke zajty na wiki, wroz ze zajtami godki, przekerowańami a t.p.',
'statistics-files'             => 'Wćepane pliki',
'statistics-edits'             => 'Sprowjyńa wykůnane uod powstańo {{grammar:D.lp|{{SITENAME}}}}',
'statistics-edits-average'     => 'Strzedńo liczba sprowjyń na zajta',
'statistics-views-total'       => 'Cołkowito liczba bezuchůw',
'statistics-views-peredit'     => 'Liczba bezuchůw na sprowjyńy',
'statistics-users'             => 'Zarejerowanych [[Special:ListUsers|użytkowńikůw]]',
'statistics-users-active'      => 'Aktywnych użytkowńikůw',
'statistics-users-active-desc' => 'Użytkowńiki, kere bůły aktywne bez {{PLURAL:$1|uostatńi dźyń|uostatńich $1 dńi}}',
'statistics-mostpopular'       => 'Zajty we kere nojčyńśći sam filujom',

'disambiguations'      => 'Zajty ujydnoznačńajůnce',
'disambiguationspage'  => '{{ns:template}}:disambig',
'disambiguations-text' => "Artikle půńižej uodwouůjům śe do '''zajtůw ujydnoznačńajůncych''', a powinny uodwouywać śe bezpostředńo do hasua kere je zwjůnzane ze treśćům artikla.<br />
Zajta uznawano je za ujydnoznačńajůnco kej zawiyro šablůn uokreślůny we [[MediaWiki:Disambiguationspage]].",

'doubleredirects'            => 'Podwůjne překierowańa',
'doubleredirectstext'        => 'Na tyi liśće mogům znojdować śe překerowańo pozorne. Uoznača to, aže půńižej pjyrwšej lińii artikla, zawjerajůncyj "#REDIRECT ...", može znojdować śe dodotkowy tekst. Koždy wjerš listy zawjero uodwouańo do pjyrwšygo i drůgygo překerowańo a pjyrwšom lińjům tekstu drůgygo překerowańo. Uůmožliwjo to na ogůu uodnaleźyńy wuaśćiwygo artikla, do kerygo powinno śe překerowywać.',
'double-redirect-fixed-move' => 'zajta [[$1]] zostoła zastůmpjůno bez przekerowańy, skiż jeij przekludzyńo ku [[$2]]',
'double-redirect-fixer'      => 'Korektor przekerowań',

'brokenredirects'        => 'Zuomane překerowańa',
'brokenredirectstext'    => 'Překerowańo půńižej wskazujům na artikle kerych sam ńy ma.',
'brokenredirects-edit'   => 'sprowjéj',
'brokenredirects-delete' => 'wyćep',

'withoutinterwiki'         => 'Artikle bez interwiki',
'withoutinterwiki-summary' => 'Zajty půńižej ńy majům uodwouań do wersjůw w inkšych godkach.',
'withoutinterwiki-legend'  => 'Prefiks',
'withoutinterwiki-submit'  => 'Pokož',

'fewestrevisions' => 'Zajty z nojmńijšom ilośćům wersyji',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bajty|bajtůw}}',
'ncategories'             => '$1 {{PLURAL:$1|kategoryja|kategorje|kategorjůw}}',
'nlinks'                  => '$1 {{PLURAL:$1|link|linki|linkůw}}',
'nmembers'                => '$1 {{PLURAL:$1|elyment|elymenty|elymentůw}}',
'nrevisions'              => '$1 {{PLURAL:$1|wersja|wersje|wersjůw}}',
'nviews'                  => 'filowano $1 {{PLURAL:$1|roz|rozůw}}',
'specialpage-empty'       => 'Ta zajta je pusto.',
'lonelypages'             => 'Poćepńynte zajty',
'lonelypagestext'         => 'Do zajtůw půńiżyj ńy adresuje żodno inkszo zajta we {{SITENAME}}.',
'uncategorizedpages'      => 'Zajty bez kategoryje',
'uncategorizedcategories' => 'Kategoryje bez kategoriůw',
'uncategorizedimages'     => 'Pliki bez kategoriůw',
'uncategorizedtemplates'  => 'Šablôny bez kategorii',
'unusedcategories'        => 'Ńyužywane kategoryje',
'unusedimages'            => 'Ńyužywane pliki',
'popularpages'            => 'Zajty we kere nojčynśćej sam filujům',
'wantedcategories'        => 'Potřebne katygoryje',
'wantedpages'             => 'Nojpotřebńijše zajty',
'wantedfiles'             => 'Potrzebne pliki',
'wantedtemplates'         => 'Potrzebne szablůny',
'mostlinked'              => 'Nojčyńśćej adrésowane',
'mostlinkedcategories'    => 'Kategoryje we kerych je nojwjyncyi artikli',
'mostlinkedtemplates'     => 'Nojčyńśćej adrésowane šablôny',
'mostcategories'          => 'Zajty kere majům nojwiyncyi kategoriůw',
'mostimages'              => 'Nojčyńśćij adresowane pliki',
'mostrevisions'           => 'Nojčyńśćej sprowjane artikle',
'prefixindex'             => 'Wszyskie zajty wedle prefiksa',
'shortpages'              => 'Nojkrůtše zajty',
'longpages'               => 'Duge artikle',
'deadendpages'            => 'Artikle bez linkůw',
'deadendpagestext'        => 'Zajty wymjyńůne půńižej ńy majům uodnośńikůw do žodnych inkšych zajtůw kere sům na tej wiki.',
'protectedpages'          => 'Zawarte zajty',
'protectedpages-indef'    => 'Yno zabezpječyńo ńyokreślůne',
'protectedpages-cascade'  => 'Yno zajty zabezpjeczůne rekursywńy',
'protectedpagestext'      => 'Zajty wymjyńůne půńižej sům zawarte uod prećepywańo i sprowjańo.',
'protectedpagesempty'     => 'Žodno zajta ńy je terozki zawarto s podanymi parametrami.',
'protectedtitles'         => 'Zawarte mjana artikli',
'protectedtitlestext'     => 'Ůtwořyńy artikli uo nastympujůncych mjanach je zawarte',
'protectedtitlesempty'    => 'Do tych štalowań utwořyńy artikla uo dowolnym mjańy ńy je zawarte',
'listusers'               => 'Lista užytkowńikůw',
'listusers-editsonly'     => 'Pokoż yno użytkowńikůw kere majům sprowjyńa',
'usereditcount'           => '$1 {{PLURAL:$1|sprowjyńe|sprowjyńa|sprowjyń}}',
'usercreated'             => '{{GENDER:$3:Utworzono}} $1 uo $2',
'newpages'                => 'Nowe zajty',
'newpages-username'       => 'Mjano užytkowńika:',
'ancientpages'            => 'Nojstarše artikle',
'move'                    => 'Przećep',
'movethispage'            => 'Přećepej ta zajta',
'unusedimagestext'        => 'Pamjyntej, proša, aže inkše witryny, np. projekty Wikimedja w inkšych godkach, můgům adresować do tych plikůw užywajůnc bezpośredńo URL. Bez tůž ńykere ze plikůw můgům sam być na tej liśće pokozane mimo, aže žodna zajta ńy adresuje do ńich.',
'unusedcategoriestext'    => 'Katygorje pokazane půńižej istńejům, choć ńy kořisto s ńich žadyn artikel ańi katygorja.',
'notargettitle'           => 'Wskazywano zajta ńy istńeje',
'notargettext'            => 'Ńy podano zajty abo užytkowńika, do kerych ta uoperacyjo mo być wykůnano.',
'nopagetitle'             => 'Ńy ma sam zajty docelowyj',
'nopagetext'              => 'Wybrano zajta docelowo ńy istńeje.',
'pager-newer-n'           => '{{PLURAL:$1|1 nowšy|$1 nowše|$1 nowšych}}',
'pager-older-n'           => '{{PLURAL:$1|1 staršy|$1 starše|$1 staršych}}',
'suppress'                => 'Oversight',

# Book sources
'booksources'               => 'Kśůnžki',
'booksources-search-legend' => 'Šnupej za zdřůduůma kśiůnžkowymi',
'booksources-go'            => 'Pokož',
'booksources-text'          => 'Půńižej znojdowo śe lista uodnośńikůw do inkšych witryn, kere pośredńičům we spředažy nowych i užywanych kśiąžek, a tyž můgům mjeć dalše informacyje uo pošukiwany bez ćebje kśůnžce',
'booksources-invalid-isbn'  => 'Podany numer ISBN zostoł rozpoznany kej felerny. Sprowdź aże podany numer je zgodny s numerym kery je we zdrzůdle.',

# Special:Log
'specialloguserlabel'  => 'Užytkowńik:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'Register dźołano',
'all-logs-page'        => 'Wšyjstkie uoperacyje',
'alllogstext'          => 'Wspůlny rejer wszyjstkych typůw uoperacyji do {{SITENAME}}.
Możesz zawyńźić liczba wyńikůw wybjerajůnc typ rejeru, mjano użytkowńika abo titel zajty (wjelge a mołe buchsztaby majům znoczyńy).',
'logempty'             => 'Ńy ma wpisůw we rejeře',
'log-title-wildcard'   => 'Šnupej za titlami kere začynojům śe uod tygo tekstu',

# Special:AllPages
'allpages'          => 'Wšyskie zajty',
'alphaindexline'    => 'uod $1 do $2',
'nextpage'          => 'Nostympno zajta ($1)',
'prevpage'          => 'Popředńo zajta ($1)',
'allpagesfrom'      => 'Zajty začynojůnce śe na:',
'allpagesto'        => 'Zajty uo titlach kere na zadku majům:',
'allarticles'       => 'Wšyskie zajty',
'allinnamespace'    => 'Wšyjstke zajty (we přestřyńi mjan $1)',
'allnotinnamespace' => 'Wšyjstke zajty (ino bes přestřyńi mjan $1)',
'allpagesprev'      => 'Popředńo',
'allpagesnext'      => 'Nastympno',
'allpagessubmit'    => 'Ukoż',
'allpagesprefix'    => 'Pokož artikle s prefiksym:',
'allpagesbadtitle'  => 'Podane mjano je felerne, zawjera prefiks mjyndzyprojektowy abo mjyndzyjynzykowy. Može uůne tyž zawjerać jako buchštaba abo inkše znaki, kerych ńy wolno užywać we titlach.',
'allpages-bad-ns'   => '{{GRAMMAR:MS.lp|{{SITENAME}}}} ńy mo přestřyńi mjan „$1”.',

# Special:Categories
'categories'                    => 'Kategoryje',
'categoriespagetext'            => 'Zajta przedstowjo lista katygoryji s zajtůma a plikůma.
[[Special:UnusedCategories|Ńyużywane kategoryj]] ńy zostoły tukej pokozane.
Kukńij tyż [[Special:WantedCategories|ńyistńyjůnce kategoryje]].',
'categoriesfrom'                => 'Pokož kategoryje začynajůnc uod:',
'special-categories-sort-count' => 'sortowanie wedle ličby',
'special-categories-sort-abc'   => 'sortowanie wedle alfabyta',

# Special:DeletedContributions
'deletedcontributions'       => 'Wyćepane sprowjyńa użytkowńika',
'deletedcontributions-title' => 'Wyćepane sprowjyńa użytkowńika',

# Special:LinkSearch
'linksearch'       => 'Necowe uodwołańa',
'linksearch-pat'   => 'Wzorzec sznupańo',
'linksearch-ns'    => 'Przestrzyń mjan',
'linksearch-ok'    => 'Šnupej',
'linksearch-text'  => 'Idźe użyć symbola wjeloznacznygo „*”. Lů bajszpila „*.wikipedia.org” spowoduje sznupańy za wszyjstkimi linkůma kere prowadzům ku důmyńy „wikipedia.org” a jeij poddůmyn.<br />
Uobsůgiwane protokoły: <tt>$1</tt>',
'linksearch-line'  => '$1 link na zajće $2',
'linksearch-error' => 'Symbola wjeloznacznygo idźe użyć yno na anfangu mjana hosta.',

# Special:ListUsers
'listusersfrom'      => 'Pokaž užytkowńikůw začynojůnc uod:',
'listusers-submit'   => 'Pokož',
'listusers-noresult' => 'Ńy znejdźůno žodnygo užytkowńika.',

# Special:Log/newusers
'newuserlogpage'     => 'Nowe użytkowniki',
'newuserlogpagetext' => 'To je rejer uostatńo utworzůnych kůnt użytkowńikůw',

# Special:ListGroupRights
'listgrouprights'                 => 'Uprawńyńo grup užytkowńikůw',
'listgrouprights-summary'         => 'Půńiży znojdowo śe spis grup użytkowńikůw zdefińjowanych na tyj wiki, s wyszczygůlńyńym przidźelůnych im prow dostympu.
Sprowdź zajta [[{{MediaWiki:Listgrouprights-helppage}}|s dodatkowymi informacjami]] uo uprowńyńach użytkowńikůw.',
'listgrouprights-group'           => 'Grupa',
'listgrouprights-rights'          => 'Uprawńyńo',
'listgrouprights-helppage'        => 'Help:Uprawńyńo grup užytkowńikůw',
'listgrouprights-members'         => '(lista čuůnkůw grupy)',
'listgrouprights-addgroup'        => 'Idźe dodać do {{PLURAL:$2|grupy|grup}}: $1',
'listgrouprights-removegroup'     => 'Idźe wyćepać s {{PLURAL:$2|grupy|grup}}: $1',
'listgrouprights-addgroup-all'    => 'Idźe dodać do kożdyj grupy',
'listgrouprights-removegroup-all' => 'Idźe wyćepać s wszyjstkich grup',

# E-mail user
'mailnologin'     => 'Brak adresu',
'mailnologintext' => 'Muśyš śe [[Special:UserLogin|zalůgować]] i mjeć wpisany aktualny adres e-brif w swojich [[Special:Preferences|preferyncyjach]], coby můc wysuać e-brif do inkšygo užytkowńika.',
'emailuser'       => 'Poślij tymu używoczowi e-brif',
'emailpage'       => 'Wyślij e-brif do užytkowńika',
'emailpagetext'   => 'Możesz użyć půńiższygo formularza, coby wysłać wjadůmość e-brif do tygo użytkowńika.
Adres e-brifa, kery zostoł bez Ćebje wkludzůny we [[Special:Preferences|Twojich sztalowańach]], pojawi śe we polu „Uod”, bez cůż uodbjorca bydźe můg Ći uodpedźeć.',
'usermailererror' => 'Moduu uobsůgi počty zwrůćiu feler:',
'defemailsubject' => 'Wjadůmość uod {{GRAMMAR:D.pl|{{SITENAME}}}}',
'noemailtitle'    => 'Brak adresu e-brif',
'noemailtext'     => 'Tyn užytkowńik ńy podou poprawnygo adresu e-brif, albo zadecydowou, co ńy chce uotřimywać wjadůmośći e-brif uod inkšych užytkowńikůw',
'email-legend'    => 'Wyślij e-brif ku inkszymu użytkowńikowi {{GRAMMAR:MS.lp|{{SITENAME}}}}',
'emailfrom'       => 'Uod:',
'emailto'         => 'Ku:',
'emailsubject'    => 'Tyjma:',
'emailmessage'    => 'Nowina:',
'emailsend'       => 'Wyślij',
'emailccme'       => 'Wyślij mi kopja moiy wjadomości.',
'emailccsubject'  => 'Kopja Twojej wjadůmośći do $1: $2',
'emailsent'       => 'Wjadůmość zostoua wysuano',
'emailsenttext'   => 'Twoja wjadůmość zostoua wysuano.',
'emailuserfooter' => 'Wjadůmość e-brif zostoła wysłano s {{GRAMMAR:D.lp|{{SITENAME}}}} ku $2 bez $1 s użyćym „Wyślij e-brif ku tym użytkowńikowi”.',

# Watchlist
'watchlist'            => 'Pozorlista',
'mywatchlist'          => 'Mojo pozůrlista',
'watchlistfor2'        => 'Lo $1 ($2)',
'nowatchlist'          => 'Ńy ma žodnych pozycyji na liśće zajtůw, na kere dowoš pozůr.',
'watchlistanontext'    => '$1 coby uobejřeć abo sprowjać elymynty listy zajtůw, na kere dowoš pozůr',
'watchnologin'         => 'Ńy jest žeś zalůgowany',
'watchnologintext'     => 'Muśyš śe [[Special:UserLogin|zalůgować]] coby modyfikować lista zajtůw, na kere dowoš pozůr.',
'addedwatchtext'       => "Zajta \"[[:\$1]]\" zostoua dodano do Twojij [[Special:Watchlist|listy artiklůw, na kere dowoš pozůr]].
Na tyi liśće bydźeš mjou rejer přišuych sprowjyń tyi zajty i jeji zajty godki, a mjano zajty bydźeš mjou škryflane '''tustym''' na [[Special:RecentChanges|liśće půmjyńanych na ůostatku]], cobyś mjou wygoda w jei pomjyńańa filować.",
'removedwatchtext'     => 'Artikel "[[:$1]]" zostou wyćepńjynty s [[Special:Watchlist|Twojij pozorlisty]].',
'watch'                => 'Dej pozůr',
'watchthispage'        => 'Dej pozůr',
'unwatch'              => 'Njy dowej pozoru',
'unwatchthispage'      => 'Přestoń dować pozůr',
'notanarticle'         => 'To ńy je artikel',
'notvisiblerev'        => 'Wersyja zostoua wyćepano',
'watchnochange'        => 'Žodno ze zajtůw, na kere dowoš pozůr, ńy bůua sprowjano w podanym uokreśe.',
'watchlist-details'    => 'Na pozorliśće {{PLURAL:$1|je 1 artikel|sům $1 artikle|je $1 artikli}} ńy rachujůnc zajtůw godek.',
'wlheader-enotif'      => '* Wysůuańy powjadůmjyń na adres e-brif je zouůnčůne',
'wlheader-showupdated' => "* Zajty, kere bouy sprowjane uod Twoi uostatńi wizyty na ńych zostoy naškryflane '''tuustym'''",
'watchmethod-recent'   => 'šnupańy za půmjyńanymi na uostatku w zajtach, na kere dowoš pozůr',
'watchmethod-list'     => 'šnupańy w zajtach, na kere dowoš pozůr pośrůd půmjyńanych na uostatku',
'watchlistcontains'    => 'Lista zajtůw, na kere dowoš pozůr mo {{PLURAL:$1|jedna pozycja|$1 pozycje|$1 pozycyji}}.',
'iteminvalidname'      => 'Problym ze pozycjům „$1”, felerne mjano...',
'wlnote'               => "Půńižy pokazano {{PLURAL:$1|ostatńy sprawjyńy dokůnane|ostatńy '''$1''' sprawjyńe dokůnane|ostatńych '''$1''' sprawjyń dokůnanych}} bez {{PLURAL:$2|uostatńo godźina|uostatńich '''$2''' godźin}}.",
'wlshowlast'           => 'Pokož uostatńy $1 godźin $2 dńi ($3)',
'watchlist-options'    => 'Uopcyje artikli na kere dowosz pozůr',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Dowom pozor...',
'unwatching' => 'Njy dowom pozoru...',

'enotif_mailer'                => 'Powjadomjyńe s {{GRAMMAR:D.lp|{{SITENAME}}}}',
'enotif_reset'                 => 'Uoznoč wšyjstke zajty kej uodwjydzůne',
'enotif_newpagetext'           => 'To je nowo zajta.',
'enotif_impersonal_salutation' => 'užytkowńik {{GRAMMAR:D.lp|{{SITENAME}}}}',
'changed'                      => 'pomjyńono',
'created'                      => 'utwořono',
'enotif_subject'               => 'Zajta $PAGETITLE we {{GRAMMAR:MS.lp|{{SITENAME}}}} zostoua $CHANGEDORCREATED bez užytkowńika $PAGEEDITOR',
'enotif_lastvisited'           => 'Uobejřij na zajće $1 wšyjstke půmjyńańo uod Twojej uostatńij wizyty.',
'enotif_lastdiff'              => 'Uobejřij na zajće $1 te pomjyńeńe.',
'enotif_anon_editor'           => 'užytkowńik anůnimowy $1',
'enotif_body'                  => 'Drogi/o $WATCHINGUSERNAME,

zajta $PAGETITLE we {{GRAMMAR:MS.lp|{{SITENAME}}}} zostoua $CHANGEDORCREATED $PAGEEDITDATE bez užytkowńika $PAGEEDITOR. Uobejřij na zajće $PAGETITLE_URL aktualno wersja.

$NEWPAGE

Opis pomjyńeńa: $PAGESUMMARY $PAGEMINOREDIT

Skůntaktuj śe s autorym:
e-brif: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

W připadku kolejnych půmjyńań nowe powjadůmjyńo ńy bydům wysuane, dopůki ńy uodwjydziš tyi zajty.
Možeš tyž zresetować wšyjstke flagi powjadůmjyń na swojej liśće zajtůw, na kere dowoš pozůr.

	Wjadůmość systymu powjadůmjyń {{GRAMMAR:D.lp|{{SITENAME}}}}

--
Kejbyś chćou půmjyńić štalowańo swojej listy zajtůw, na kere dowoš pozůr, uodwjydź
{{canonicalurl:{{#special:EditWatchlist}}}}

Pomoc:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Wyćep artikel',
'confirm'                => 'Potwjyrdź',
'excontent'              => 'zawartość zajty „$1”',
'excontentauthor'        => 'treść: „$1” (jedyny aůtor: [[Special:Contributions/$2|$2]])',
'exbeforeblank'          => 'popředńo zawartość uobecńy pustej zajty: „$1”',
'exblank'                => 'Zajta byua pusto',
'delete-confirm'         => 'Wyćep „$1”',
'delete-legend'          => 'Wyćep',
'historywarning'         => 'Pozor! Ta zajta kerům chceš wyćepnůńć mo historjo:',
'confirmdeletetext'      => 'Chceš wyćepnůńć trwale artikel abo plik s bazy danych s historią. Pokož, aže wjyš co robdza, i to aže to je tak jak godojům [[{{MediaWiki:Policy-url}}|zasady]].',
'actioncomplete'         => 'Fertig',
'actionfailed'           => 'Ńy udało sie.',
'deletedtext'            => 'Wyćepano "$1". Rejer uostatnio zrobiůnych wyćepań možeš uobejžyć tukej: $2.',
'dellogpage'             => 'Wyćepane',
'dellogpagetext'         => 'To je lista uostatńo wykůnanych wyćepań.',
'deletionlog'            => 'rejer wyćepań',
'reverted'               => 'Přiwrůcůno popředńo wersyja',
'deletecomment'          => 'Čymu:',
'deleteotherreason'      => 'Inkšy powůd:',
'deletereasonotherlist'  => 'Inkszy powůd',
'deletereason-dropdown'  => '* Nojčynstše přičyny wyćepańa
** Prośba autora
** Narušyńy praw autorskych
** Wandalizm',
'delete-edit-reasonlist' => 'Sprowjańe listy powodůw wyćepańo zajty',
'delete-toobig'          => 'Ta zajta mo fest dugo historyja sprowjyń, wjyncyj jak $1 {{PLURAL:$1|půmjyńańy|půmjyńańo|půmjyńań}}.
Jeij wyćepańy mogło by spowodować zakłucyńo we dźołańu {{GRAMMAR:D.lp|{{SITENAME}}}} a bez tůż zostało uograńiczůne.',
'delete-warning-toobig'  => 'Ta zajta mo fest dugo historia sprowjyń, wjyncy kej $1 {{PLURAL:$1|půmjyńeńe|půmjyńańo|půmjyńań}}.
Dej pozůr, bo jei wyćepańe może spowodować zakłůcyńo w pracy {{GRAMMAR:D.lp|{{SITENAME}}}}.',

# Rollback
'rollback'         => 'Wycofej sprowjyńe',
'rollback_short'   => 'Cofej',
'rollbacklink'     => 'cofej',
'rollbackfailed'   => 'Ńy idźe wycofać sprowjyńo',
'cantrollback'     => 'Ńy idże cofnůńć pomjyńeńo, sam je ino jedna wersyja tyi zajty.',
'alreadyrolled'    => 'Ńy idźe lů zajty [[:$1|$1]] cofnůńć uostatńygo pomjyńeńa, kere wykonoł [[User:$2|$2]] ([[User talk:$2|godka]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]).
Kto inkszy zdůnżůł już to zrobić abo wprowadźił własne poprowki do treśći zajty.

Autorym ostatńygo pomjyńyńo je terozki [[User:$3|$3]] ([[User talk:$3|godka]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'      => "Sprowjyńe uopisano: „''$1''”.",
'revertpage'       => 'Wycofano sprowjyńe użytkowńika [[Special:Contributions/$2|$2]] ([[User talk:$2|godka]]). Autor prziwrůcůnej wersyji to [[User:$1|$1]].',
'rollback-success' => 'Wycofano sprowjyńa užytkowńika $1.
Přiwrůcůno uostatńo wersyja autorstwa  $2.',

# Edit tokens
'sessionfailure' => 'Feler weryfikacyji zalůgowańo.
Polecyńy zostouo anulowane, aby ůńiknůńć přechwycyńo sesyji.

Naćiś „cofej”, přeuaduj zajta, a potym zaś wydej polecyńy',

# Protect
'protectlogpage'              => 'Zawarte',
'protectlogtext'              => 'Půńižej znojdowo śe lista zawarć i uodymkńjyńć pojydynčych zajtůw.
Coby přejřeć lista uobecńy zawartych zajtůw, přeńdź na zajta wykazu [[Special:ProtectedPages|zawartych zajtůw]].',
'protectedarticle'            => 'zawar [[$1]]',
'modifiedarticleprotection'   => 'pomjyńiu poziům zawarćo [[$1]]',
'unprotectedarticle'          => 'uodymknyu [[$1]]',
'movedarticleprotection'      => 'przekludzůno sztalowańa zabezpjeczyńo s „[[$2]]” ku „[[$1]]”',
'protect-title'               => 'Pomjyńeńe poźomu zawarćo „$1”',
'prot_1movedto2'              => '[[$1]] přećepano do [[$2]]',
'protect-legend'              => 'Potwjyrdź zawarće',
'protectcomment'              => 'Čymu:',
'protectexpiry'               => 'Wygaso:',
'protect_expiry_invalid'      => 'Čas wygaśńjyńćo je zuy.',
'protect_expiry_old'          => 'Čas wygaśńjyńćo je w downiej ńiž terozki.',
'protect-text'                => "Sam tukej možyš uobejžeć i pomjyńyć poźům zawarcia zajty '''$1'''.",
'protect-locked-blocked'      => "Ńy možeš půmjyńać poźůmůw zawarćo kej žeś sům je zawarty uod sprowjyń. Terozki štalowańa dla zajty '''$1''' to:",
'protect-locked-dblock'       => "Ńy idźe půmjyńić poźůmu zawarća s kuli tygo co baza danych tyž je zawarto. Uobecne štalowańa dla zajty '''$1''' to:",
'protect-locked-access'       => "Ńy moš uprowńyń coby pomjyńyć poziům zawarcia zajty. Uobecne ustawjyńo dlo zajty '''$1''' to:",
'protect-cascadeon'           => 'Ta zajta je zawarto od pomjyńań, po takjymu, co jei užywo {{PLURAL:$1|ta zajta, kero je zawarto|nastympůjůnce zajty, kere zostauy zawarte}} a opcyjo dźedźičyńo je zaůončono. Možeš pomjyńyć poziům zawarcia tyi zajty, ale dlo dźedźičyńo zawarcia to ńy mo wpuywu.',
'protect-default'             => 'Wszyjske używocze mogům sprowjać',
'protect-fallback'            => 'Wymago pozwolynjo "$1"',
'protect-level-autoconfirmed' => 'Blokuj nowe a ńyregistrowane używocze',
'protect-level-sysop'         => 'Ino admini',
'protect-summary-cascade'     => 'dźedźičyńy',
'protect-expiring'            => 'wygaso $1 (UTC)',
'protect-expiry-indefinite'   => 'na zowdy',
'protect-cascade'             => 'Dźedźyčyńe zawarćo - zawřij wšyskie zajty kere sům na tyi zajće.',
'protect-cantedit'            => 'Ńy možeš pomjyńyć poziůmu zawarća tyi zajty, po takiymu, co uona je dlo Ćebje zawarto uod pomjyńańa.',
'protect-othertime'           => 'Inkszy uokres:',
'protect-othertime-op'        => 'inkszy uokres',
'protect-existing-expiry'     => 'Czas wygaśńyńćo nasztalowany terozki: $2 uo $3',
'protect-otherreason'         => 'Inkszy/dodatkowy powůd:',
'protect-otherreason-op'      => 'inkszy/dodatkowy powůd',
'protect-dropdown'            => '*Nojczynstsze powody zawarćo uod sprowjyń
** Czynste wandalizmy
** Czynste spamowańy
** Wojna edycyjno
** Wygupy',
'protect-edit-reasonlist'     => 'Sprowjej powody zawarćo uod sprowjyń',
'protect-expiry-options'      => '2 godźiny:2 hours,1 dźyń:1 day,3 dńi:3 days,1 tydźyń:1 week,2 tygodńy:2 weeks,1 mjeśůnc:1 month,3 mjeśůnce:3 months,6 mjeśency:6 months,1 rok:1 year,ńyskůńčůny:infińite',
'restriction-type'            => 'Pozwolyńy:',
'restriction-level'           => 'Poźům:',
'minimum-size'                => 'Min. wjelgość',
'maximum-size'                => 'Maksymalno wjelgość',
'pagesize'                    => '(bajtůw)',

# Restrictions (nouns)
'restriction-edit'   => 'Sprowjéj',
'restriction-move'   => 'Přećepńjyńće',
'restriction-create' => 'Stwůř',
'restriction-upload' => 'Wćep',

# Restriction levels
'restriction-level-sysop'         => 'poune zawarće',
'restriction-level-autoconfirmed' => 'tajlowe zawarće',
'restriction-level-all'           => 'dowolny poziům',

# Undelete
'undelete'                     => 'Pokož wyćepńjynte zajty',
'undeletepage'                 => 'Pokož a odtwůř wyćepńjynte zajty',
'undeletepagetitle'            => "'''Půńižej znojdujům śe wyćepane wersyje zajty  [[:$1]]'''.",
'viewdeletedpage'              => 'Pokož wyćepńjynte zajty',
'undeletepagetext'             => '{{PLURAL:$1|Nastympujůnco zajta uosroła wyćepano, nale jeij|Nastympujůnce $1 zajty uostoły wyćepane, nale jejich}} kopja dalij znojdujy śe we archiwum. Aechiwum roz za kedy trza uoczyszczać.',
'undelete-fieldset-title'      => 'Wćepywańy nazod wersyji',
'undeleteextrahelp'            => "Jak chcesz wćepać nazod couko zajta, pozostaw wszyjstke pola ńyzaznaczůne a naćiś '''Uodtwůrz'''.
Aby wybrać tajlowe uodtworzyńy noleży zaznaczyć '''Uodtwůrz'''.
Naćiśńyńće '''Wyczyść''' usůńy wszyjstke zaznaczyńo a wyczyśći pole kůmyntorza.",
'undeleterevisions'            => '$1 {{PLURAL:$1|zarchiwizowano wersyja|zarchiwizowane wersyje|zarchiwizowanych wersyji}}',
'undeletehistory'              => 'Wćepańy nazod zajty spowoduje prziwrůcyńy tyż jeij wszyjstkych poprzedńich wersyji.
Jak uod czasu wyćepańo ktoś utworzůł nowo zajta uo idyntycznym mjańy, uodtwarzane wersyje znojdům śe w jeij historyji, a uobecno wersyjo pozostańy bez půmjyńań.',
'undeleterevdel'               => 'Wćepańy nazod zajty ńy bydźe přeprowadzůne kej by můguo spowodować tajlowe wyćepańy aktualnej wersyji.
W takej sytuacyji noležy uodznačyć abo přiwrůćić widočność nojnowšym wyćepanym wersjům.',
'undeletehistorynoadmin'       => 'Ta zajta zostoua wyćepano.
Powůd wyćepańo je podany w podsůmowańu půńižej, razym s danymi užytkowńika, kery sprawjou zajta před jei wyćepańym.
Sama treść wyćepanych wersyji je dostympna ino do admińistratorůw',
'undelete-revision'            => 'Wyćepńynto wersyjo $1 (s $5 $4) keryj autorym je $3:',
'undeleterevision-missing'     => 'Felerno abo brakujůnco wersyjo.
Možeš mjeć felerny link abo wersyjo můgua zostać wćepano nazod, abo wyćepano s archiwům.',
'undelete-nodiff'              => 'Ńy znejdźono popřednich wersyji.',
'undeletebtn'                  => 'Uodtwůř',
'undeletelink'                 => 'ukoż abo uodtwůrz',
'undeleteviewlink'             => 'ukoż',
'undeletereset'                => 'Wyčyść',
'undeleteinvert'               => 'Zaznocz na uopy',
'undeletecomment'              => 'Powůd wćepańo nazod:',
'undeletedrevisions'           => 'Wćepano nazod {{PLURAL:$1|1 wersyja|$1 wersyje|$1 wersyji}}',
'undeletedrevisions-files'     => 'Wćepano nazod $1 {{PLURAL:$1|wersyja|wersyje|wersyji}} i $2 {{PLURAL:$2|plik|pliki|plikůw}}',
'undeletedfiles'               => 'wćepou nazod $1 {{PLURAL:$1|plik|pliki|plikůw}}',
'cannotundelete'               => 'Wćepańy nazod ńy powjodo śe.
Kto inkšy můgu wćepać nazod zajta pjyrwšy.',
'undeletedpage'                => "'''Wćepano nazod zajta $1.'''

Uobejřij [[Special:Log/delete|rejer wyćepań]], kejbyś chćou přeglůndnůnć uostatnie uoperacyje wyćepywańo i wćepywańo nazod zajtůw.",
'undelete-header'              => 'Uobejřij [[Special:Log/delete|rejer wyćepań]] coby sprawdźić uostatńo wyćepane zajty.',
'undelete-search-box'          => 'Šnupej za wyćepńjyntymi zajtami',
'undelete-search-prefix'       => 'Zajty začynajůnce śe uod:',
'undelete-search-submit'       => 'Šnupej',
'undelete-no-results'          => 'Ńy znejdźono wskazanych zajtůw we archiwum wyćepanych.',
'undelete-filename-mismatch'   => 'Ńy idźe wćepać nazod wersyji plika z datům $1: ńyzgodność mjana plika',
'undelete-bad-store-key'       => 'Ńy idźe wćepać nazod wersyji plika z datům $1: před wyćepańem brakowouo plika.',
'undelete-cleanup-error'       => 'Wystůmpiu feler při wyćepywańu ńyužywanygo archiwalnygo plika „$1”.',
'undelete-missing-filearchive' => 'Ńy idźe wćepać nazod s archiwum plika uo ID $1, s kuli tygo co ńy ma go w baźe danych.
Być može plik zostou juž wćepany nazod.',
'undelete-error-short'         => 'Wystůmpiu feler při wćepywańu nazod plika: $1',
'undelete-error-long'          => 'Napotkano felery při wćepywańu nazod plika:

$1',
'undelete-show-file-confirm'   => 'Jeżeś echt pewny co chcesz uobejzdrzeć wyćepano wersyjo plika „<nowiki>$1</nowiki>” s $2 $3?',
'undelete-show-file-submit'    => 'Ja',

# Namespace form on various pages
'namespace'      => 'Raum mjan:',
'invert'         => 'Wybjer na uopy',
'blanknamespace' => '(przodńo)',

# Contributions
'contributions'       => 'Ajnzac sprowjorza',
'contributions-title' => 'Wkłod użytkowńika $1',
'mycontris'           => 'Uody mje sprowjane',
'contribsub2'         => 'Do užytkowńika $1 ($2)',
'nocontribs'          => 'Brak pomjyńań uodpowjadajůncych tym kryterjům.',
'uctop'               => '(uostatnio)',
'month'               => 'Uod mjeśůnca (i downiyjše):',
'year'                => 'Uod roku (i dowńijše):',

'sp-contributions-newbies'       => 'Pokož wkuod ino uod nowych užytkowńikůw',
'sp-contributions-newbies-sub'   => 'Dlo nowych užytkowńikůw',
'sp-contributions-newbies-title' => 'Wkłod nowych użytkowńików',
'sp-contributions-blocklog'      => 'zawarća',
'sp-contributions-deleted'       => 'Wyćepane sprowjyńa użytkowńika',
'sp-contributions-uploads'       => 'wćepane uobrozki',
'sp-contributions-logs'          => 'rejer dźołońo',
'sp-contributions-talk'          => '↓ dyskusyjo',
'sp-contributions-userrights'    => 'Zařůndzańy prowami užytkowńikůw',
'sp-contributions-search'        => 'Šnupej za wkuodym',
'sp-contributions-username'      => 'Adres IP abo mjano užytkowńika',
'sp-contributions-toponly'       => 'Ukoż jyno ůostanie wersyje',
'sp-contributions-submit'        => 'Šnupej',

# What links here
'whatlinkshere'            => 'Co sam linkuje',
'whatlinkshere-title'      => 'Zajty, kere linkujům na "$1"',
'whatlinkshere-page'       => 'Zajta:',
'linkshere'                => "Nastympůjůnce zajty sóm adrésůwane do '''[[:$1]]''':",
'nolinkshere'              => "Žodno zajta ńy je adrésowana do '''[[:$1]]'''.",
'nolinkshere-ns'           => "Žodno zajta ńy je adresowano do '''[[:$1]]''' we wybrany přestřyni mjan.",
'isredirect'               => 'překerowujůnca zajta',
'istemplate'               => 'douůnčona šablôna',
'isimage'                  => 'Link do plika',
'whatlinkshere-prev'       => '{{PLURAL:$1|popředńe|popředńe $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|nastympne|nastympne $1}}',
'whatlinkshere-links'      => '← do adrésata',
'whatlinkshere-hideredirs' => '$1 {{PLURAL:$1|punkńyńćy|punkńyńćo|puńkńyńć}}',
'whatlinkshere-hidetrans'  => '$1 {{PLURAL:$1|dokuplowańy|dokuplowańo|dokuplowań}}',
'whatlinkshere-hidelinks'  => '$1 {{PLURAL:$1|link|linki|linkůw}}',
'whatlinkshere-hideimages' => '$1 linki s grafik',
'whatlinkshere-filters'    => 'Filtery',

# Block/unblock
'blockip'                         => 'Zawrzij sprowjorza',
'blockip-legend'                  => 'Zawrzij sprowjorza',
'blockiptext'                     => 'Tyn formulař suužy do zawjerańo sprowjyń spod uokreślůnygo adresu IP abo kůnkretnymu užytkowńikowi.
Zawjerać noležy jydyńy po to, by zapobjec wandalizmům, zgodńy s [[{{MediaWiki:Policy-url}}|přijyntymi zasadami]].
Podej powůd (np. umješčajůnc mjana zajtůw, na kerych dopuščůno śe wandalizmu).',
'ipadressorusername'              => 'Adres IP abo mjano užytkowńika',
'ipbexpiry'                       => 'Wygaso:',
'ipbreason'                       => 'Čymu:',
'ipbreasonotherlist'              => 'Inkszy powůd',
'ipbreason-dropdown'              => '*Nojčynstše powody zawjerańo uod sprawjyń
** Ataki na inkšych užytkowńikůw
** Narušyńy praw autorskych
** Ńydozwolůne mjano užytkowńika
** Upen proxy/Tor
** Spamowańy
** Ůsuwańy treśći zajtůw
** Wprowadzańy foušywych informacyji
** Wulgaryzmy
** Wypisywańy guůpot na zajtach',
'ipbcreateaccount'                => 'Ńy dozwůl utwožyć kůnta',
'ipbemailban'                     => 'Zawřij možliwość wysůuańo e-brifůw',
'ipbenableautoblock'              => 'Zawřij uostatńi adres IP tygo užytkowńika i autůmatyčńy wšyjstke kolejne, s kerych bydźe průbowou sprowjać zajty',
'ipbsubmit'                       => 'Zawřij uod sprowjyń tygo užytkowńika',
'ipbother'                        => 'Ikšy čas',
'ipboptions'                      => '2 godźiny:2 hours,1 dźyń:1 day,3 dńi:3 days,1 tydźyń:1 week,2 tydńe:2 weeks,1 mjeśůnc:1 month,3 mjeśůnce:3 months,6 mjeśůncůw:6 months,1 rok:1 year,nawdy:infinite',
'ipbotheroption'                  => 'inkšy',
'ipbotherreason'                  => 'Inkšy powůd:',
'ipbhidename'                     => 'Schrůń mjano užytkowńika/adres IP w rejeře zawarć, na liśće aktywnych zawarć i liśće užytkowńikůw',
'ipbwatchuser'                    => 'Dowej pozůr na zajta uosobisto i zajta godki tygo užytkowńika',
'ipb-change-block'                => 'Zmjyń sztalowańa zawarća uod sprowjyń',
'badipaddress'                    => 'Felerny adres IP',
'blockipsuccesssub'               => 'Zawarće uod sprowjyń udane',
'blockipsuccesstext'              => 'Užytkowńik [[Special:Contributions/$1|$1]] zostou zawarty uod sprowjyń.<br />
Přyńdź do [[Special:IPBlockList|listy zawartych adresůw IP]] coby přejřeć zawarća.',
'ipb-edit-dropdown'               => 'Sprowjej powody zawjyrańo uod sprowjyń',
'ipb-unblock-addr'                => 'Uodymknij $1',
'ipb-unblock'                     => 'Uodymknij užytkowńika abo adres IP',
'ipb-blocklist'                   => 'Zoboč istńijůnce zawarća',
'ipb-blocklist-contribs'          => 'Wkłod $1',
'unblockip'                       => 'Uodymkńij sprowjyńo užytkowńikowi',
'unblockiptext'                   => 'Ůžyj formulořa půńižej coby přiwrůćić možliwość sprowjańo s wčeśńij zawartygo adresu IP abo užytkowńikowi.',
'ipusubmit'                       => 'Uodymkńij sprowjyńo užytkowńikowi',
'unblocked'                       => '[[User:$1|$1]] zostou uodymkńynty.',
'unblocked-id'                    => 'Zawarće $1 zostouo zdjynte',
'ipblocklist'                     => 'Zawarte używocze',
'ipblocklist-legend'              => 'Znejdź zawartygo uod sprawjyń užytkowńika',
'ipblocklist-submit'              => 'Šnupej',
'infiniteblock'                   => 'na zawše',
'expiringblock'                   => 'wygaso $1',
'anononlyblock'                   => 'ino ńyzalůgowańy',
'noautoblockblock'                => 'autůmatyčne zawjyrańy uod sprowjyń wůuůnčůne',
'createaccountblock'              => 'zawarto twořyńe kont',
'emailblock'                      => 'zawarty e-brif',
'blocklist-nousertalk'            => 'ńy mogům sprowjać własnych zajtůw godki',
'ipblocklist-empty'               => 'Lista zawarć je pusto.',
'ipblocklist-no-results'          => 'Podany adres IP abo užytkowńik ńy je zawarty uod sprowjyń.',
'blocklink'                       => 'blokuj',
'unblocklink'                     => 'uodymknij',
'change-blocklink'                => 'půmjyń zawarće uod sprowjyń',
'contribslink'                    => 'ajnzace',
'autoblocker'                     => 'Zawarto Ci sprowjyńo autůmatyčńy, bez tůž co užywaš tygo samygo adresu IP, co užytkowńik „[[User:$1|$1]]”.
Powůd zawarća $1 to: „$2”',
'blocklogpage'                    => 'Gyszichta zawjyrańo',
'blocklogentry'                   => 'zawarto [[$1]], bydźe uodymkńynty: $2 $3',
'reblock-logentry'                => 'půmjyńił sztalowańa zawarća uod sprowjyń lů [[$1]], czas zawarćo: $2 $3',
'blocklogtext'                    => "Půńižej znojdowo śe lista zawarć zouožůnych i zdjyntych s poščygůlnych adresůw IP.
Na li'śće ńy mo adresůw IP, kere zawarto w sposůb autůmatyčny.
Coby přejřeć lista uobecńy aktywnych zawarć, přyńdź na zajta [[Special:BlockList|zawartych adresůw i užytkowńikůw]].",
'unblocklogentry'                 => 'uodymknyu $1',
'block-log-flags-anononly'        => 'ino anůnimowi',
'block-log-flags-nocreate'        => 'tworzińy kůnta je zawrzite',
'block-log-flags-noautoblock'     => 'autůmatyčne zawjerańy uod sprawjyń wůuůnčůne',
'block-log-flags-noemail'         => 'e-brif zawarty',
'block-log-flags-nousertalk'      => 'ńy może sprowjać włosnyj zajty godki',
'block-log-flags-angry-autoblock' => 'rozszerzůne automatyczne zawjyrańe załůnczůne',
'range_block_disabled'            => 'Možliwość zawjerańo zakresu adresůw IP zostoua wůuůnčůno.',
'ipb_expiry_invalid'              => 'Felerny čas wygaśńjyńćo zawarća.',
'ipb_expiry_temp'                 => 'Schrůńůne mjano użytkowńika noleży zawrzić trwale.',
'ipb_already_blocked'             => '"$1" je już zawarty uod sprowjyń',
'ipb-needreblock'                 => '$1 je już zawarty uod sprowjyń. Chcesz půmjyńić sztalowańa zawarćo?',
'ipb_cant_unblock'                => 'Feler: Zawarće uo ID $1 ńy zostouo znejdźone. Moguo uone zostać oudymkńynte wčeśnij.',
'ipb_blocked_as_range'            => 'Feler: Adres IP $1 ńy zostou zawarty bezpośredńo i ńy može zostać uodymkńjynty.
Noležy uůn do zawartygo zakresu adresůw $2. Uodymknůńć možno ino couki zakres.',
'ip_range_invalid'                => 'Ńypoprowny zakres adresów IP.',
'blockme'                         => 'Zawryj mi sprowjyńa',
'proxyblocker'                    => 'Zawjyrańe proxy',
'proxyblocker-disabled'           => 'Ta fůnkcyjo je wůuůnčůna.',
'proxyblockreason'                => 'Twůj adres IP zostou zawarty, bo je to adres uotwartygo proxy.
Sprawa noležy wyjaśńić s dostawcům Internetu abo půmocům techńičnům informujůnc uo tym powažnym problymje s bezpječyństwym.',
'proxyblocksuccess'               => 'Wykůnane.',
'sorbsreason'                     => 'Twůj adres IP znojdowo śe na liśće serwerůw open proxy w DNSBL, užywanej bez {{GRAMMAR:B.lp|{{SITENAME}}}}.',
'sorbs_create_account_reason'     => 'Twůj adres IP znojdowo śe na liśće serwerůw open proxy w DNSBL, užywanej bez {{GRAMMAR:B.lp|{{SITENAME}}}}.
Ńy možeš utwořić kůnta',
'cant-block-while-blocked'        => 'Ńy możesz zawjyrać uod sprowjyń inkszych użytkowńikůw, jak sam jeżeś uod ńich zawarty.',

# Developer tools
'lockdb'              => 'Zawryj baza danych',
'unlockdb'            => 'Uodymkńij baza danych',
'lockdbtext'          => 'Zawarće bazy danych ůńymožliwi wšyjstkym užytkowńikům sprowjańy zajtůw, zmjana preferyncyji, edycyjo pozorlistůw a inkše čynnośći wymagajůnce dostympu do bazy danych.
Potwjerdź, proša, aže to je zgodne s Twoimi zamjarůma, i aže uodymkńyš baza danych, gdy ino zakůńčyš zadańa admińistracyjne.',
'unlockdbtext'        => 'Uodymkńjyńće bazy danych ůmožliwi wšyjstkym užytkowńikům sprawjańy zajtůw, zmjano preferyncyji, štalowańe pozorlistůw a inkše čynnośći zwjůnzane s půmjyńańami we baźe danych. Potwjerdź, proša, aže to je zgodne s Twoimi zamjarůma.',
'lockconfirm'         => 'No toć, riśtiś chca zawryć baza danych.',
'unlockconfirm'       => 'No toć, riśtiś chca uodymknůnć baza danych.',
'lockbtn'             => 'Zawřij baza danych',
'unlockbtn'           => 'Uodymkńij baza danych',
'locknoconfirm'       => 'Ńy zaznačůužeś potwjerdzyńo.',
'lockdbsuccesssub'    => 'Baza danych zostoua půmyślńy zawarto',
'unlockdbsuccesssub'  => 'Baza danych zostoua půmyślńy uodymkńynto',
'lockdbsuccesstext'   => 'Baza danych zostoua zawarto.<br />
Pamjyntej coby [[Special:UnlockDB|jům uodymknůńć]] po zakůńčyńu dźouań admińistracyjnych.',
'unlockdbsuccesstext' => 'Baza danych zostoua uodymkńynto.',
'lockfilenotwritable' => 'Ńy idźe naškreflać plika zawarća bazy danych.
Zawjerańy i uodmykańy bazy danych wymogo coby plik můgu być naškreflany bez web serwer.',
'databasenotlocked'   => 'Baza danych ńy je zawarto.',

# Move page
'move-page'                    => 'Przećep $1',
'move-page-legend'             => 'Přećiś artikel',
'movepagetext'                 => "Při půmocy formulařa půńižej možeš půmjyńyć nazwa zajty i přećepnůńć jei historja. Pod downym titlym uostańe zajta překerowujůnca. Zajty adresowane na stary titel uostanům jak bůuy.

Jak śe na to decyduješ, sprowdź, eli ńy je to [[Special:DoubleRedirects|podwůjne]] abo [[Special:BrokenRedirects|zuomane překerowańy]].
Uodpowjadoš za to, coby linki wjoduy ku prawiduowym artiklům!

Zajta '''ńy''' bydźe přećepano, jak:
*je pusto i ńy bůua sprowjano
*je zajtům překerowujůncą
*zajta uo takym titlu juž sam jest

'''DEJ POZŮR!'''
To može być drastyčno abo i ńypřewidywalno zmjano, jak přećepńyš jako popularno zajta. Bydź pewny, aže wješ co robiyš, ńim klikńyš knefel \"přećep\"!",
'movepagetalktext'             => 'Uodpowiednio zajta godki, jeśli jest, bydzie přećepano automatyčńe, pod warůnkiem, že:
*ńy přećepuješ zajty do inkšy přestřeńy mjan
*ńy ma sam zajty godki o takiym mjańe
W takiych razach tekst godki třa přećepać, a jak třeba to i pouůnčyć z tym co juž sam jest, rynčńe. Abo možeš sie namyślić i nie přećepywać wcale ("checkbox" půnižyi).',
'movearticle'                  => 'Přećiś artikel:',
'movenologin'                  => 'Ńy jestžeś zalůgowany',
'movenologintext'              => 'Muśyš być zarejerowanym i [[Special:UserLogin|zalůgowanym]] užytkowńikym coby můc přećepnůńć zajta.',
'movenotallowed'               => 'Ńy moš uprownień do přećepywańo zajtůw.',
'cant-move-user-page'          => 'Ńy mosz uprowńyń do przekludzańo zajtůw użytkowńikůw (wyjůntkym sům jejich podstrony).',
'cant-move-to-user-page'       => 'Ńy mosz uprowńyń coby przekludźić zajta na plac kaj je zajta użytkowńika (wyjůntkym sům podzajty użytkowńika).',
'newtitle'                     => 'Nowy titel:',
'move-watch'                   => 'Dej pozůr',
'movepagebtn'                  => 'Přećiś artikel',
'pagemovedsub'                 => 'Přećiśńjyńće gotowe',
'movepage-moved'               => '\'\'\'"$1" přećiśńjynto ku "$2"\'\'\'',
'articleexists'                => 'Artikel s takym mjanym juž je, abo mjano je zue.
Wybjer inkše mjano.',
'cantmove-titleprotected'      => 'Ńy možeš přećepnůńć zajty, bez tůž co jei nowe mjano je ńydozwolůne s kuli zabezpječyńo před utwořyńym',
'talkexists'                   => 'Zajta artikla zostaua přećepano, ale zajta godki ńy - zajta godki uo nowym mjańe juž sam jest. Poůunč, proša, teksty oubydwůch godek rynčńe.',
'movedto'                      => 'přećiśńjynto ku',
'movetalk'                     => 'Přećiś godke, jak možno.',
'move-subpages'                => 'Přećepńij podzajty',
'move-talk-subpages'           => 'Jeli je to możliwe przekludź wszyjstke zajty godki podzajtůw',
'movepage-page-exists'         => 'Zajta $1 już istńeje a ńy idźe jeij autůmatyczńy nadszkryflać.',
'movepage-page-moved'          => 'Zajta $1 uostoła przekludzůno ku $2.',
'movepage-page-unmoved'        => 'Mjana zajty $1 ńy idźe půmjyńić na $2.',
'movepage-max-pages'           => 'Przekludzůnych uostało $1 {{PLURAL:$1|zajta|zajty|zajtůw}}. Wjynkszyj liczby ńy idźe przekludźić automatyczńy.',
'movelogpage'                  => 'Přećepńynte',
'movelogpagetext'              => 'Uoto lista zajtůw, kere uostatńo zostouy přećepane.',
'movereason'                   => 'Čymu:',
'revertmove'                   => 'cofej',
'delete_and_move'              => 'Wyćep i przećep',
'delete_and_move_text'         => '== Přećepańy wymaga wyćepańo inkšyj zajty ==
Zajta docelowo „[[:$1]]” juž sam jest.
Čy chceš jům wyćepać, coby zrobić plac do přećepywanej zajty?',
'delete_and_move_confirm'      => 'Toć, wyćep zajta',
'delete_and_move_reason'       => 'Wyćepano coby zrobić plac do přećepywanyj zajty',
'selfmove'                     => 'Mjana zajtůw zdřůdowyj i docelowyj sům take same.
Zajty ńy idźe přećepać na ńa samo.',
'immobile-source-namespace'    => 'Ńy idźe przekludzać zajtůw we przestrzyńi mjan "$1"',
'immobile-target-namespace'    => 'Ńy idźe przekludzić zajtůw ku przestrzyńi mjan "$1"',
'immobile-target-namespace-iw' => 'Link interwiki je felernym titlem pod kery mjołaby być przekludzůno zajta.',
'immobile-source-page'         => 'Tyj zajty ńy idźe przekludźić.',
'immobile-target-page'         => 'Ńy idźe przekludzić pod wskozany titel.',
'imagenocrossnamespace'        => 'Ńy idźe přećepać grafiki do přestřyni mjan ńy přeznočonyj do grafik',
'imagetypemismatch'            => 'Nowe rozšeřyńe mjana plika je inkšego typu kej jygo zawartość',
'imageinvalidfilename'         => 'Mjano plika docelowygo je felerne',
'fix-double-redirects'         => 'Poprow przekerowańa kere adresujům ku uoryginalnymu titlowi zajty',
'move-leave-redirect'          => 'Uostow przekerowańy pode dotychczasowym titlem',

# Export
'export'            => 'Eksport zajtůw',
'exporttext'        => 'Možeš wyeksportować treść i historja sprowjyń jednyj zajty abo zestawu zajtůw we formaće XML.
Wyeksportowane informacyje možna půźńij zaimportować do inkšej wiki, dźouajůncyj na uoprůgramowańu MediaWiki, kořistajůnc ze [[Special:Import|zajty importu]].

Wyeksportowańy wjelu zajtůw wymogo wpisańo půńižej titli zajtůw, po jednym titlu we wjeršu a uokreślyńo čy mo zostać wyeksportowano bježůnco čy wšyjstke wersyje zajty s uopisůma sprawjyń abo tyž ino bježůnca wersyjo s uopisym uostatńygo sprawjyńo.

Možeš tyž užyć linku, np.[[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] do zajty „[[{{MediaWiki:Mainpage}}]]”.',
'exportcuronly'     => 'Ino bježůnco wersyjo, bes historji',
'exportnohistory'   => "----
'''Pozůr:''' Wůuůnčůno možliwość eksportowańo peunej historii zajtůw s užyćym tygo nařyńdźa s kuli kuopotůw s wydajnośćůn",
'export-submit'     => 'Eksportuj',
'export-addcattext' => 'Doćep zajty z kategorji',
'export-addcat'     => 'Dodej',
'export-download'   => 'Zapiš do plika',
'export-templates'  => 'Douůnč šablůny',

# Namespace 8 related
'allmessages'               => 'Komunikaty',
'allmessagesname'           => 'Mjano',
'allmessagesdefault'        => 'Tekst důmyślny',
'allmessagescurrent'        => 'Tekst uobecny',
'allmessagestext'           => 'Uoto lista wšyjstkych kůmůńikatůw systymowych dostympnych w přestřyńi mjan MedjaWiki.
Uodwjydź [//www.mediawiki.org/wiki/Localisation Tuůmačyńy MediaWiki] a tyž [//translatewiki.net translatewiki.net] kejbyś chćou učestńičyć w tuůmačyńu uoprůgramowańo MediaWiki.',
'allmessagesnotsupportedDB' => "Ta zajta ńy može być užyta, bez tůž co zmjynna '''\$wgUseDatabaseMessages''' je wůuůnčůno.",

# Thumbnails
'thumbnail-more'           => 'Zwjynksz',
'filemissing'              => 'Njy mo pliku',
'thumbnail_error'          => 'Feler při gynerowańu mińatury: $1',
'djvu_page_error'          => 'Zajta DjVu poza zakresym',
'djvu_no_xml'              => 'Ńy idźe pobrać danych we formaće XML do plika DjVu',
'thumbnail_invalid_params' => 'Felerne parametry mińjatury',
'thumbnail_dest_directory' => 'Ńy idźe utwořić katalogu docelůwygo',

# Special:Import
'import'                     => 'Importuj zajty',
'importinterwiki'            => 'Import transwiki',
'import-interwiki-text'      => 'Wybjer wiki i nmjano zajty do importowańo.
Daty a tyž mjana autorůw bydům zachowane.
Wšyjstke uoperacyje importu transwiki sům uodnotowywane w [[Special:Log/import|rejeře importu]].',
'import-interwiki-source'    => 'Zdrzůdło wiki/zajty:',
'import-interwiki-history'   => 'Kopjuj couko historja sprowjyń tyi zajty',
'import-interwiki-submit'    => 'Importuj',
'import-interwiki-namespace' => 'Docelowo przestrzyń mjan:',
'import-upload-filename'     => 'Mjano plika:',
'import-comment'             => 'Kůmyntorz:',
'importtext'                 => 'Używajůnc werkcojga [[Special:Export|eksportuj]] wyeksportuj plik ze zdrzůdłowyj wiki, naszkryflej go na swojym dysku, a potym wćepńij go tukej.',
'importstart'                => 'Trwo importowańe zajtůw...',
'import-revision-count'      => '$1 {{PLURAL:$1|wersyja|wersyje|wersyji}}',
'importnopages'              => 'Ńy ma zajtůw do importu.',
'importfailed'               => 'Import ńy udou śe: $1',
'importunknownsource'        => 'Ńyznany format importowanych danych',
'importcantopen'             => 'Ńy idźe uodymknůńć importowanego plika',
'importbadinterwiki'         => 'Felerny link interwiki',
'importnotext'               => 'Brak tekstu abo zawartośći',
'importsuccess'              => 'Import zakůńčůny powodzyńym!',
'importhistoryconflict'      => 'Wystůmpiu kůnflikt wersyji (ta zajta můgua zostać importowano juž wčeśńij)',
'importnosources'            => 'Možliwość bezpośredńygo importu historii zostoua wůuůnčůna, s kuli tygo co ńy zdefińowano zdřudua.',
'importnofile'               => 'Importowany plik ńy zostou sam wćepńynty.',
'importuploaderrorsize'      => 'Wćepańy sam importowanygo plika śe ńy udouo. Je uůn wjynkšy kej dopuščalny rozmjor do wćepywanych plikůw.',
'importuploaderrorpartial'   => 'Wćepańy sam importowanygo plika śe ńy udouo. Zostou wćepany ino w tajli.',
'importuploaderrortemp'      => 'Wćepańy sam importowanygo plika śe ńy udouo. Ńy ma sam katalogu do plikůw tymčasowych.',
'import-parse-failure'       => 'ńyudano analiza skuadńi importowanygo XML',
'import-noarticle'           => 'Ńy ma zajtůw do zaimportowańo!',
'import-nonewrevisions'      => 'Wšyjstke wersyje zostouy juž wčeśńij zaimportowane.',
'xml-error-string'           => '$1 lińa $2, kolůmna $3 (bajt $4): $5',
'import-upload'              => 'Wćepej dane XML',
'import-token-mismatch'      => 'Straćiły śe dane ze sesyje. Prosza spróbować zaś.',
'import-invalid-interwiki'   => 'Ńy idźe importować s podanyj wiki.',

# Import log
'importlogpage'                    => 'Rejer importa',
'importlogpagetext'                => 'Rejer přeprowadzůnych importůw zajtůw s inkšych serwisůw wiki.',
'import-logentry-upload'           => 'zaimportowou [[$1]] bez wćepańe sam plika',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|wersyja|wersyje|wersyji}}',
'import-logentry-interwiki'        => 'zaimportowou $1 užywajůnc transwiki',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|wersyja|wersyje|wersyji}} ze $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Mojo perzůnalno zajta',
'tooltip-pt-anonuserpage'         => 'Zajta užytkowńika do adresu IP spod kerygo sprowjoš',
'tooltip-pt-mytalk'               => 'Mojo zajta dyskusyje',
'tooltip-pt-anontalk'             => 'Godka užytkowńika do adresu IP spod kerygo sprowjoš',
'tooltip-pt-preferences'          => 'Moje preferyncyje',
'tooltip-pt-watchlist'            => 'Lista artiklůw, na kere dowosz pozůr',
'tooltip-pt-mycontris'            => 'Lista uody mje sprowjonych',
'tooltip-pt-login'                => 'Chćeli by my, cobyś śe nalogowoł, nale to ńy je powinne',
'tooltip-pt-anonlogin'            => 'Zachyncůmy do zalůgowańo śe, nale to ńy je uobowjůnzek',
'tooltip-pt-logout'               => 'Uodloguj śe ze wiki',
'tooltip-ca-talk'                 => 'Dyskusyjo uo tym artiklu',
'tooltip-ca-edit'                 => 'Mogesz sprowjać ta zajta. Podwjela spamjyntosz půmjyńańo, klikńij we knefel "Uobźyrej".',
'tooltip-ca-addsection'           => 'Przidej nowy temat',
'tooltip-ca-viewsource'           => 'Ta zajta je zawrzito. Mogesz uobźyrać zdrzůdłowy tekst.',
'tooltip-ca-history'              => 'Storsze wersyje tyj zajty',
'tooltip-ca-protect'              => 'Zawřij ta zajta',
'tooltip-ca-delete'               => 'Wyćep ta zajta',
'tooltip-ca-undelete'             => 'Přiwrůć wersyja tyi zajty spřed wyćepańo',
'tooltip-ca-move'                 => 'Przećep ta zajta kaj indzij.',
'tooltip-ca-watch'                => 'Przidej artikel na pozůrlista',
'tooltip-ca-unwatch'              => 'Wyciep tyn artikel ze pozůrlisty',
'tooltip-search'                  => 'Sznupej we serwiśe {{SITENAME}}',
'tooltip-search-go'               => 'Przyńdź na zajta uo gynał takij titli, eli sam je',
'tooltip-search-fulltext'         => 'Sznupej wćepany tekst na zajće',
'tooltip-p-logo'                  => 'Przodńo zajta',
'tooltip-n-mainpage'              => 'Przelyź na przodńo zajta',
'tooltip-n-mainpage-description'  => 'Przelyź na przodńo zajta',
'tooltip-n-portal'                => 'Uo projekće, co mogesz robić, kaj mogesz nolyźć informacyje',
'tooltip-n-currentevents'         => 'Informacyje uo aktualnych przitrefjyńach',
'tooltip-n-recentchanges'         => 'Lista ńydowno půmjyńanych we wiki',
'tooltip-n-randompage'            => 'Ukoż cufalno zajta',
'tooltip-n-help'                  => 'Sam śe mogesz moc przewjedźeć',
'tooltip-t-whatlinkshere'         => 'Ukoż zajty, kere sam linkujům',
'tooltip-t-recentchangeslinked'   => 'Ńydowno půmjyńane na zajtach, do kerych ta zajta linkuje',
'tooltip-feed-rss'                => 'Kanau RSS do tyj zajty',
'tooltip-feed-atom'               => 'Kanoł Atom lo tyj zajty',
'tooltip-t-contributions'         => 'Ukoż ajnzace tygo używocza',
'tooltip-t-emailuser'             => 'Wyślij e-brif do tygo užytkowńika',
'tooltip-t-upload'                => 'Wćepej plik na serwer',
'tooltip-t-specialpages'          => 'Lista wszyjskich ekstra zajtůw',
'tooltip-t-print'                 => 'Wersyjo do durku',
'tooltip-t-permalink'             => 'Pewny link do tyj wersyje zajty',
'tooltip-ca-nstab-main'           => 'Uobźyrej zajta artikla',
'tooltip-ca-nstab-user'           => 'Ukoż perzůnalno zajta używocza',
'tooltip-ca-nstab-media'          => 'Uobejřij zajta artikla',
'tooltip-ca-nstab-special'        => 'To je ekstra zajta. Ńy možeš jei sprowjać.',
'tooltip-ca-nstab-project'        => 'Uobejřij zajta projektu',
'tooltip-ca-nstab-image'          => 'Ukoż zajta grafiki',
'tooltip-ca-nstab-mediawiki'      => 'Zoboč komunikat systymowy',
'tooltip-ca-nstab-template'       => 'Uobźyrej muster',
'tooltip-ca-nstab-help'           => 'Pokož zajte s půmocą',
'tooltip-ca-nstab-category'       => 'Ukoż zajta kategoryje',
'tooltip-minoredit'               => 'Uoznač ta zmjana za drobno',
'tooltip-save'                    => 'Naszkryflej půmjyńańa',
'tooltip-preview'                 => 'Ńiż naszkryflosz, uobźyrej efekt twojigo sprowjyńo.',
'tooltip-diff'                    => 'Ukozuje twoje půmjyńańa we tekśće',
'tooltip-compareselectedversions' => 'Uobźyrej zmjyny mjyndzy dwůma uobranymi wersyjůma tyj zajty',
'tooltip-watch'                   => 'Dodej tyn artikel do pozorlisty',
'tooltip-recreate'                => 'Wćepej nazod zajta mimo aže bůua wčeśńij wyćepano.',
'tooltip-upload'                  => 'Rozpočyńće wćepywańa',
'tooltip-rollback'                => '"cofej" jednym klikńyńćym rewertuje půmjyńańa uod uostatnigo sprowjorza.',
'tooltip-undo'                    => '"anuluj půmjyńańa" cofo to půmjyńańy a uodwjyro uokno sprowjańa we trybje widoku.
Idzie naszkryflać powůd we popiśe půmjyńań.',
'tooltip-summary'                 => 'Krůtko popisz',

# Metadata
'notacceptable' => 'Serwer wiki ńy je w stańy dostarčyć danych we formaće, kerygo Twoja přyglůndarka uočekuje.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Anůńimowy użytkowńik|Anůńimowe użytkowńiki}} {{SITENAME}}',
'siteuser'         => 'Užytkowńik {{GRAMMAR:D.lp|{{SITENAME}}}} – $1',
'lastmodifiedatby' => 'Uostatńy sprowjyńy tej zajty: $2, $1 (autor půmjyńań: $3)',
'othercontribs'    => 'Inkše autory: $1.',
'others'           => 'inkśi',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|użytkowńik|użytkowńiki}} $1',
'creditspage'      => 'Autořy',
'nocredits'        => 'Brak informacyji uo autorach tyi zajty.',

# Spam protection
'spamprotectiontitle' => 'Filter antyspamowy',
'spamprotectiontext'  => 'Zajta, kero żeś průbowou naszkryflać, uostoła zawarta bez filter antyspamowy.
Nojprawdopodobńij zostoło to spowodowane bez link do zewnyntrznyj zajty internecowyj kero je na czornyj liśće.',
'spamprotectionmatch' => 'Filtr antyspamowy śe zouůnčůu s kuli tygo co znod tekst: $1',
'spambot_username'    => 'MediaWiki – wyćepywańe spamu',
'spam_reverting'      => 'Přiwracańy uostatńij wersyji we kerej ńy bůuo linkůw do $1',
'spam_blanking'       => 'Wšyjstke wersyje zawjerouy uodnośńiki do $1. Čyščyńy zajty.',

# Patrolling
'markaspatrolleddiff'                 => 'uoznoč sprawjyńy kej „sprawdzůne”',
'markaspatrolledtext'                 => 'Uoznoč tyn artikel kej „sprawdzůny”',
'markedaspatrolled'                   => 'Sprawdzůne',
'markedaspatrolledtext'               => 'Ta wersyjo zostoua uoznačůna kej „sprawdzůno”.',
'rcpatroldisabled'                    => 'Wůuůnčůno fůnkcjůnalność patrolowańo we půmjyńanych na uostatku',
'rcpatroldisabledtext'                => 'Patrolowańy půmjyńanych na uostatku je terozki wůuůnčůne.',
'markedaspatrollederror'              => 'Ńy idźe uoznačyć kej „sprawdzůne”',
'markedaspatrollederrortext'          => 'Muśyš wybrać wersyja coby uoznačyć jům kej „sprawdzůna”.',
'markedaspatrollederror-noautopatrol' => 'Ńy moš uprawńyń wymaganych do uoznačańo swojich sprawjyń kej „sprawdzůne”.',

# Patrol log
'patrol-log-page'      => 'Dźynńik patrolowańo',
'patrol-log-header'    => 'Půniżej je dźeńńik patrolowańo zajtůw.',
'log-show-hide-patrol' => '$1 rejer sprawdzańo',

# Image deletion
'deletedrevision'                 => 'Wyćepano popředńy wersyje $1',
'filedeleteerror-short'           => 'Feler při wyćepywańu plika $1',
'filedeleteerror-long'            => 'Wystůmpiuy felery při wyćepywańu pliku:

$1',
'filedelete-missing'              => 'Plika „$1” ńy idźe wyćepać, bo ńy istńije.',
'filedelete-old-unregistered'     => 'Žůndanyj wersyji plika „$1” ńy ma w baźe danych.',
'filedelete-current-unregistered' => 'Plika „$1” ńy ma w baźe danych.',
'filedelete-archive-read-only'    => 'Serwer WWW ńy može naškryflać w katalůgu s archiwůma „$1”.',

# Browsing diffs
'previousdiff' => '← Popředńy sprowjyńy',
'nextdiff'     => 'Nostympne sprowjyńy →',

# Media information
'mediawarning'    => "'''Pozůr!''' Tyn plik može zawjerać zuośliwy kod. Jak go uodymkńyš možeš zaraźić swůj systym.",
'imagemaxsize'    => 'Na zajtach uopisu plikůw uůgrańič rozmjar uobrazkůw do:',
'thumbsize'       => 'Rozmjar mińjatůrki',
'widthheightpage' => '$1×$2, $3 {{PLURAL:$3|zajta|zajty|zajtůw}}',
'file-info'       => 'rozmjor plika: $1, typ MIME: $2',
'file-info-size'  => '$1 × $2 pikselůw, wjelgość plika: $3, zorta MIME: $4',
'file-nohires'    => 'Wjynksze wymjyry ńy sům dostympne',
'svg-long-desc'   => 'Plik SVG, nůminalńe $1 × $2 pixelůw, rozmior plika: $3',
'show-big-image'  => 'Pełne wymjyry',

# Special:NewFiles
'newimages'             => 'Galerjo nowych uobrozkůw',
'imagelisttext'         => "Půnižyj na {{PLURAL:$1||posortowanyj $2}} liśće {{PLURAL:$1|znojdowo|znojdujům|znojdowo}} śe '''$1''' {{PLURAL:$1|plik|pliki|plikůw}}.",
'newimages-summary'     => 'Na tyi ekstra zajće prezyntowane sům uostatńo wćepńynte pliki.',
'newimages-legend'      => 'Filtruj',
'newimages-label'       => 'Mjano plika (abo jygo tajla):',
'showhidebots'          => '($1 boty)',
'noimages'              => 'Brak plikůw do pokozańo.',
'ilsubmit'              => 'Šnupej',
'bydate'                => 'wedle daty',
'sp-newimages-showfrom' => 'pokož nowe pliki začynajůnc uod $2, $1',

# Bad image list
'bad_image_list' => 'Dane trza wćepać we formaće:

Jyno tajle listy (lińije, kere śe napoczynajům uod *) absztychujemy.
Pjyrszy link we lińiji muśi być linkym do zakozanygo pliku.
Dolsze linki we lińiji sům uwożane za wyjimki  – sům to mjana zajtůw, na kerych idzie użyć plik ze zakozanym mjanym.',

# Metadata
'metadata'          => 'Metadane',
'metadata-help'     => 'Tyn plik mo ekstra informacyje na isto przidane uod fotoaparata abo skanera, kere bůły użite lo powstańo tygo pliku.
Eli plik był modyfikowany, dane mogům w tajli ńy być we zgodźe ze parametrůma modyfikowanego pliku.',
'metadata-expand'   => 'Pokož ščygůuy',
'metadata-collapse' => 'Schowej ščygůuy',
'metadata-fields'   => 'Wyszkryflůne niżyj pola EXIF bydům wyszkryflůne na zajcie plika. Inksze pola bydům mjarkowańy schrůńůne.
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
'exif-imagewidth'                  => 'Šyrokość',
'exif-imagelength'                 => 'Wysokość',
'exif-bitspersample'               => 'Bitůw na průbka',
'exif-compression'                 => 'Metoda kompresyji',
'exif-photometricinterpretation'   => 'Interpretacyjo fotůmetryčno',
'exif-orientation'                 => 'Uorjyntacyjo uobrozu',
'exif-samplesperpixel'             => 'Průbek na piksel',
'exif-planarconfiguration'         => 'Rozkuod danych',
'exif-ycbcrsubsampling'            => 'Podprůbkowańe Y do C',
'exif-ycbcrpositioning'            => 'Rozmješčyńy Y i C',
'exif-xresolution'                 => 'Rozdźelčość w poźůmje',
'exif-yresolution'                 => 'Rozdźelčość w pjůńy',
'exif-stripoffsets'                => 'Přesůńjyńće pasůw uobrazu',
'exif-rowsperstrip'                => 'Ličba wjeršy na pas uobrazu',
'exif-stripbytecounts'             => 'Ličba bajtůw na pas uobrazu',
'exif-jpeginterchangeformat'       => 'Pouožyńy pjyrwšygo bajtu mińaturki uobrazu',
'exif-jpeginterchangeformatlength' => 'Ličba bajtůw mińaturki JPEG',
'exif-whitepoint'                  => 'Půnkt bjeli',
'exif-primarychromaticities'       => 'Kolory třech barw guůwnych',
'exif-ycbcrcoefficients'           => 'Maćeř wspůučynńikůw transformacyji barw ze RGB na YCbCr',
'exif-referenceblackwhite'         => 'Wartość půnktu uodńyśyńo čerńi i bjeli',
'exif-datetime'                    => 'Data i čas modyfikacyji plika',
'exif-imagedescription'            => 'Titel uobrozka',
'exif-make'                        => 'Producynt fotoaparatu',
'exif-model'                       => 'Model fotoaparatu',
'exif-software'                    => 'Ůžyte uoprůgramowańy',
'exif-artist'                      => 'Autor',
'exif-copyright'                   => 'Wuaśćićel praw autorskych',
'exif-exifversion'                 => 'Wersyja standardu Exif',
'exif-flashpixversion'             => 'Uobsůgiwano wersyjo Flashpix',
'exif-colorspace'                  => 'Přestřyń kolorůw',
'exif-componentsconfiguration'     => 'Značyńy skuadowych',
'exif-compressedbitsperpixel'      => 'Skůmpresowanych bitůw na piksel',
'exif-pixelydimension'             => 'Prawiduowa šyrokość uobrozu',
'exif-pixelxdimension'             => 'Prawiduowo wysokość uobrozu',
'exif-usercomment'                 => 'Kůmyntoř užytkowńika',
'exif-relatedsoundfile'            => 'Powjůnzany plik audjo',
'exif-datetimeoriginal'            => 'Data i čas utwořyńo uoryginouu',
'exif-datetimedigitized'           => 'Data i čas zeskanowańo',
'exif-subsectime'                  => 'Data i čas modyfikacyji pliku – uuamki sekůnd',
'exif-subsectimeoriginal'          => 'Data i čas utwořyńo uoryginouu – uuamki sekůnd',
'exif-subsectimedigitized'         => 'Data i čas zeskanowańo – uuamki sekůnd',
'exif-exposuretime'                => 'Čas ekspozycyji',
'exif-exposuretime-format'         => '$1 s ($2)',
'exif-fnumber'                     => 'Wartość přisuůny',
'exif-exposureprogram'             => 'Progrům ekspozycyji',
'exif-spectralsensitivity'         => 'Čuuość widmowa',
'exif-isospeedratings'             => 'Šybkość aparatu zgodńy ze ISO12232',
'exif-shutterspeedvalue'           => 'Šybkość migawki',
'exif-aperturevalue'               => 'Přisuůna uobjektywu',
'exif-brightnessvalue'             => 'Jasność',
'exif-exposurebiasvalue'           => 'Uodchylyńy ekspozycyji',
'exif-maxaperturevalue'            => 'Maksymalno wartość přisuůny',
'exif-subjectdistance'             => 'Uodlygość uod uobjektu',
'exif-meteringmode'                => 'Tryb půmjaru',
'exif-lightsource'                 => 'Rodzej zdřudua śwjatua',
'exif-flash'                       => 'Lampa buyskowo',
'exif-focallength'                 => 'Duůgość uůgńiskowyj uobjektywu',
'exif-subjectarea'                 => 'Pouožyńy i uobšar guůwnygo motywu uobrozu',
'exif-flashenergy'                 => 'Ynergja lampy buyskowyj',
'exif-focalplanexresolution'       => 'Rozdźelčość w poźůmje puaščyzny uodwzorowańo uobjektywu',
'exif-focalplaneyresolution'       => 'Rozdźelčość w pjůńe puaščyzny uodwzorowańo uobjektywu',
'exif-focalplaneresolutionunit'    => 'Jednostka rozdźelčośći puaščyzny uodwzorowańo uobjektywu',
'exif-subjectlocation'             => 'Pouožyńy guůwnygo motywu uobrozu',
'exif-exposureindex'               => 'Indeks ekspozycyji',
'exif-sensingmethod'               => 'Metoda půmjaru (rodzaj přetworńika)',
'exif-filesource'                  => 'Typ zdřudua plika',
'exif-scenetype'                   => 'Rodzaj scyny',
'exif-customrendered'              => 'Wstympńy přetwořůny (poddany uobrůbce)',
'exif-exposuremode'                => 'Tryb ekspozycyji',
'exif-whitebalance'                => 'Balans bjeli',
'exif-digitalzoomratio'            => 'Wspůučynńik powjynkšyńo cyfrowygo',
'exif-focallengthin35mmfilm'       => 'Duůgość uůgńiskowyj, uodpowjydńik do filmu 35mm',
'exif-scenecapturetype'            => 'Rodzaj uchwycyńo scyny',
'exif-gaincontrol'                 => 'Wzmocńyńy jasnośći uobrazu',
'exif-contrast'                    => 'Kůntrast uobrozu',
'exif-saturation'                  => 'Nasycyńy kolorůw uobrozu',
'exif-sharpness'                   => 'Uostrość obrozu',
'exif-devicesettingdescription'    => 'Uopis ustawjyń uřůndzyńo',
'exif-subjectdistancerange'        => 'Uodleguość uod uobjektu',
'exif-imageuniqueid'               => 'Uůńikalny idyntyfikator uobrozu',
'exif-gpsversionid'                => 'Wersyjo formatu danych GPS',
'exif-gpslatituderef'              => 'Šyrokość geůgrafično (půunoc/pouedńe)',
'exif-gpslatitude'                 => 'Šyrokość geůgrafično',
'exif-gpslongituderef'             => 'Duůgość geůgrafično (wschůd/zachůd)',
'exif-gpslongitude'                => 'Duůgość geůgrafično',
'exif-gpsaltituderef'              => 'Wysokość nad poźůmym mořa (odńyśyńy)',
'exif-gpsaltitude'                 => 'Wysokość nad poźůmym mořa',
'exif-gpstimestamp'                => 'Čas GPS (zygor atůmowy)',
'exif-gpssatellites'               => 'Satelity užyte do půmjaru',
'exif-gpsstatus'                   => 'Status uodjorcy',
'exif-gpsmeasuremode'              => 'Tryb půmjaru',
'exif-gpsdop'                      => 'Precyzjo půmjaru',
'exif-gpsspeedref'                 => 'Jydnostka gibkości',
'exif-gpsspeed'                    => 'Gibkość poźomo',
'exif-gpstrackref'                 => 'Poprawka půmjyndzy kerůnkym i celym',
'exif-gpstrack'                    => 'Kerunek ruchu',
'exif-gpsimgdirectionref'          => 'Poprawka do kerůnku zdjyńćo',
'exif-gpsimgdirection'             => 'Kerůnek zdjyńćo',
'exif-gpsmapdatum'                 => 'Model půmjaru geodezyjnygo',
'exif-gpsdestlatituderef'          => 'Půunocno abo pouedńowo šyrokość geůgrafično celu',
'exif-gpsdestlatitude'             => 'Šyrokość geůgrafično celu',
'exif-gpsdestlongituderef'         => 'Wschodńo abo zachodńo dugość geůgrafično celu',
'exif-gpsdestlongitude'            => 'Dugość geůgrafično celu',
'exif-gpsdestbearingref'           => 'Značńik namjaru na cel (kerůnku)',
'exif-gpsdestbearing'              => 'Namjar na cel (kerůnek)',
'exif-gpsdestdistanceref'          => 'Značńik uodlygośći do celu',
'exif-gpsdestdistance'             => 'Uodlygość do celu',
'exif-gpsprocessingmethod'         => 'Mjano metody GPS',
'exif-gpsareainformation'          => 'Mjano přestřyńi GPS',
'exif-gpsdatestamp'                => 'Data GPS',
'exif-gpsdifferential'             => 'Korekcyjo růžńicy GPS',

# EXIF attributes
'exif-compression-1' => 'ńyskůmpresowany',

'exif-unknowndate' => 'ńyznano data',

'exif-orientation-1' => 'normalno',
'exif-orientation-2' => 'odbiće we źřadle w poźůmje',
'exif-orientation-3' => 'uobroz uobrůcůny uo 180°',
'exif-orientation-4' => 'uodbiće we źřadle w pjůńy',
'exif-orientation-5' => 'uobroz uobrůcůny uo 90° přećiwńy do ruchu wskazůwek zygora i uodbiće we źřadle w pjůńy',
'exif-orientation-6' => 'uobroz uobrůcůny uo 90° zgodńy s ruchym wskazůwek zygora',
'exif-orientation-7' => 'uobrůt uo 90° zgodńy ze wskazůwkůma zygora i uodbiće we źřadle w pjůńy',
'exif-orientation-8' => 'uobrůt uo 90° přećiwńy do wskazůwek zygora',

'exif-planarconfiguration-1' => 'format masywny',
'exif-planarconfiguration-2' => 'format powjeřchńowy',

'exif-componentsconfiguration-0' => 'ńy istńeje',

'exif-exposureprogram-0' => 'ńyzdefińjůwany',
'exif-exposureprogram-1' => 'rynčny',
'exif-exposureprogram-2' => 'standardowy',
'exif-exposureprogram-3' => 'preselekcyjo přisuůny',
'exif-exposureprogram-4' => 'preselekcyjo migawki',
'exif-exposureprogram-5' => 'kreatywny (duža guymbja uostrośći)',
'exif-exposureprogram-6' => 'aktywny (dužo gibkość migawki)',
'exif-exposureprogram-7' => 'tryb portretowy (do zdjyńć s bliska, s ńyuostrym tuym)',
'exif-exposureprogram-8' => 'tryb krajobrazowy (dla zdjęć wykůnywanych s dužej uodlyguośći s uostrośćům ustavjůnům na tuo)',

'exif-subjectdistance-value' => '$1 metrůw',

'exif-meteringmode-0'   => 'ńyuokryślůny',
'exif-meteringmode-1'   => 'średńo',
'exif-meteringmode-2'   => 'średńo važůno',
'exif-meteringmode-3'   => 'punktowy',
'exif-meteringmode-4'   => 'wjelopunktowy',
'exif-meteringmode-5'   => 'průbkowańy',
'exif-meteringmode-6'   => 'tajlowy',
'exif-meteringmode-255' => 'inkšy',

'exif-lightsource-0'   => 'ńyznany',
'exif-lightsource-1'   => 'dźynne',
'exif-lightsource-2'   => 'jařyńowe',
'exif-lightsource-3'   => 'štučne (žarowe)',
'exif-lightsource-4'   => 'lampa bůyskowo (fleš)',
'exif-lightsource-9'   => 'dźynne (gryfno pogoda)',
'exif-lightsource-10'  => 'dźynne (pochmurno)',
'exif-lightsource-11'  => 'cyń',
'exif-lightsource-12'  => 'jařyńowe dźynne (tymperatura barwowa 5700 – 7100K)',
'exif-lightsource-13'  => 'jařyńowe ćepue (tymperatura barwowo 4600 – 5400K)',
'exif-lightsource-14'  => 'jařyńowe źimne (tymperatura barwowo 3900 – 4500K)',
'exif-lightsource-15'  => 'jařyńowe bjoue (tymperatura barwowo 3200 – 3700K)',
'exif-lightsource-17'  => 'standardowe A',
'exif-lightsource-18'  => 'standardowe B',
'exif-lightsource-19'  => 'standardowe C',
'exif-lightsource-24'  => 'žarowe studyjne ISO',
'exif-lightsource-255' => 'Inkše zdřuduo śwjotua',

# Flash modes
'exif-flash-fired-0'    => 'Bes błyska flesza',
'exif-flash-fired-1'    => 'S błyskym flesza',
'exif-flash-return-0'   => 'bes funkcyji wykrywańo śwjotła uodbitygo',
'exif-flash-return-2'   => 'ńy wykryto śwjotła uodbitygo',
'exif-flash-return-3'   => 'wykryto śwjotło uodbite',
'exif-flash-mode-1'     => 'wymuszůny błysk flesza',
'exif-flash-mode-2'     => 'wymuszůny brak błyska flesza',
'exif-flash-mode-3'     => 'tryb autůmatyczny',
'exif-flash-function-1' => 'Ńy ma funkcyji flesza',
'exif-flash-redeye-1'   => 'tryb redukowańo efektu czyrwůnych ślypjůw',

'exif-focalplaneresolutionunit-2' => 'cole',

'exif-sensingmethod-1' => 'ńyzdefińjowano',
'exif-sensingmethod-2' => 'jydnoukuodowy přetworńik uobrozu kolorowygo',
'exif-sensingmethod-3' => 'dwůukuudowy přetworńik uobrozu kolorowygo',
'exif-sensingmethod-4' => 'třiukuodowy přetworńik uobrozu kolorowygo',
'exif-sensingmethod-5' => 'přetworńik uobrozu s sekwyncyjnym přetwařańym kolorůw',
'exif-sensingmethod-7' => 'třilińowy přetworńik uobrozu',
'exif-sensingmethod-8' => 'lińowy přetworńik uobrozu s sekwyncyjnym přetwařańym kolorůw',

'exif-scenetype-1' => 'uobjekt fotůgrafowany bezpośredńo',

'exif-customrendered-0' => 'ńy',
'exif-customrendered-1' => 'tak',

'exif-exposuremode-0' => 'autůmatyčne ustalyńy parametrůw naśwjetlańa',
'exif-exposuremode-1' => 'rynčne ustalyńy parametrůw naśwjetlańo',
'exif-exposuremode-2' => 'wjelokrotno ze zmjanům parametrůw naśwjetlańo',

'exif-whitebalance-0' => 'autůmatyčny',
'exif-whitebalance-1' => 'rynčny',

'exif-scenecapturetype-0' => 'standardowy',
'exif-scenecapturetype-1' => 'krajobroz',
'exif-scenecapturetype-2' => 'portret',
'exif-scenecapturetype-3' => 'scyna nocno',

'exif-gaincontrol-0' => 'brak',
'exif-gaincontrol-1' => 'ńiske wzmocńyńe',
'exif-gaincontrol-2' => 'wysoke wzmocńyńe',
'exif-gaincontrol-3' => 'ńiske uosuabjyńy',
'exif-gaincontrol-4' => 'wysoke uosuabjyńy',

'exif-contrast-0' => 'normalny',
'exif-contrast-1' => 'Lichy',
'exif-contrast-2' => 'Srogi',

'exif-saturation-0' => 'normalne',
'exif-saturation-1' => 'ńiske',
'exif-saturation-2' => 'wysoke',

'exif-sharpness-0' => 'Normalno',
'exif-sharpness-1' => 'Licho',
'exif-sharpness-2' => 'Srogo',

'exif-subjectdistancerange-0' => 'ńyznano',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'widok z bliska',
'exif-subjectdistancerange-3' => 'widok z daleka',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'půunocno',
'exif-gpslatitude-s' => 'pouedńowo',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'wschodńo',
'exif-gpslongitude-w' => 'zachodńo',

'exif-gpsstatus-a' => 'půmjar w trakće',
'exif-gpsstatus-v' => 'wyńiki půmjaru dostympne na bježůnco',

'exif-gpsmeasuremode-2' => 'dwuwymjarowy',
'exif-gpsmeasuremode-3' => 'trůjwymjarowy',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'kilometrůw na godzina',
'exif-gpsspeed-m' => 'mil na godzina',
'exif-gpsspeed-n' => 'wynzuůw',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'kerůnek geůgrafičny',
'exif-gpsdirection-m' => 'kerůnek magnetyčny',

# External editor support
'edit-externally'      => 'Sprowjej tyn plik bez eksterno aplikacyjo',
'edit-externally-help' => '(Zobocz [//www.mediawiki.org/wiki/Manual:External_editors instrukcyje sztalowańo eksternych edytorůw], kaj je uo tym wjyncy naszkryflůne)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'do kupy',
'namespacesall' => 'wszyjske',
'monthsall'     => 'wšyskie',

# E-mail address confirmation
'confirmemail'             => 'Potwjerdź adres e-brif',
'confirmemail_noemail'     => 'Ńy podoužeś prawiduowygo adresa e-brifa we [[Special:Preferences|preferencyjach]].',
'confirmemail_text'        => 'Projekt {{SITENAME}} wymago weryfikacyji adresa e-brif před užyćym fůnkcyji kořistajůncych s počty.
Wćiś knefel půńižy coby wysúać na swůj adres list s linkym do zajty WWW.
List bydźe zawjeroú link do zajty, w kerym zakodowany bydźe idyntyfikator.
Uodymkńij tyn link we přyglůndarce, čym potwjerdźiš, co ježeś užytkowńikym tygo adresa e-brif.',
'confirmemail_pending'     => 'Kod potwjerdzyńo zostou wuaśńy do Ćebje wysúany. Jak žeś śe rejerowou ńydowno, počekej pora minut na dostarčyńy wjadůmośći ńim zaś wyśleš prośba uo wysuańy kodu.',
'confirmemail_send'        => 'Wyślij kod potwjerdzyńo',
'confirmemail_sent'        => 'Wjadůmość e-brif s kodym uwjeřitelńajůncym zostoua wysuano.',
'confirmemail_oncreate'    => 'Link s kodym potwjerdzyńo zostou wysuany na Twůj adres e-brif.
Kod tyn ńy je wymagany coby śe sam lůgować, ale bydźeš muśou go aktywować uodmykajůnc uotřimany link we přyglůndarce ńim zouůnčyš ńykere uopcyje e-brif na wiki.',
'confirmemail_sendfailed'  => '{{SITENAME}} ńy poradźiła wysłać potwjerdzajůncyj wjadůmośći e-brif.
Sprowdź aże we adreśe ńy ma felernyj buchsztaby.

Systym pocztowy zwrůćił kůmůńikat: $1',
'confirmemail_invalid'     => 'Felerny kod potwjerdzyńo.
Kod može być předawńůny',
'confirmemail_needlogin'   => 'Muśyš $1 coby potwjerdzić adres e-brif.',
'confirmemail_success'     => 'Adres e-brif zostou potwjerdzůny.
Možeš śe zalůgować i kořistać s šeršygo wachlařa fůnkcjůnalnośći wiki.',
'confirmemail_loggedin'    => 'Twůj adres e-brif zostou zweryfikowany.',
'confirmemail_error'       => 'Wystůmpiuy felery při škryflańu potwjerdzyńo.',
'confirmemail_subject'     => '{{SITENAME}} - weryfikacyjo adresa e-brif',
'confirmemail_body'        => 'Ktoś, uůnčůnc śe s kůmputra uo adreśe IP $1,
zarejerowoú we {{GRAMMAR:MS.lp|{{SITENAME}}}} kůnto „$2” i podou ńińijšy adres e-mail.

Coby potwjerdźić, aže Ty zarejerowoužeś te kůnto, a tyž coby zauůnčyć
wšyjstke fůnkcyje kořistajůnce s počty e-brif, uodymkńij w swoij
přyglůndarce tyn link:

$3

Jak to *ńy* Ty zarejerowoueś kůnto, uodymkńij we swoij přyglůndarce link sam nižy, coby anulować potwjerdzyńy adresu e-brif:

$5

Kod zawarty w linku straći wažność $4.',
'confirmemail_invalidated' => 'Potwjerdzyńy adresa e-brif zostouo anulowane',
'invalidateemail'          => 'Anulowańy potwjerdzyńo adresa e-brif',

# Scary transclusion
'scarytranscludedisabled' => '[Douůnčańy bez interwiki je wůuůnčůne]',
'scarytranscludefailed'   => '[Ńy powjoduo śe pobrańy szablůna lů $1]',
'scarytranscludetoolong'  => '[za dugo adresa URL]',

# Trackbacks
'trackbackbox'      => 'Kůmůńikaty TrackBack do tygo artikla:<br />
$1',
'trackbackremove'   => '([$1 Wyćep])',
'trackbacklink'     => 'TrackBack',
'trackbackdeleteok' => 'TrackBack zostou wyćepany.',

# Delete conflict
'deletedwhileediting' => "'''Pozůr''': Ta zajta zostoła wyćepano po tym, jak żeś rozpoczůł jei sprowjańy!",
'confirmrecreate'     => "Užytkowńik [[User:$1|$1]] ([[User talk:$1|godka]]) wyćepnůu tyn artikel po tym jak žeś rozpočůu(eua) jygo sprowjańe, podajůnc kej powůd wyćepańo:
: ''$2''
Potwjerdź chęć wćepańo nazod tygo artikla.",
'recreate'            => 'Wćepej nazod',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => 'Wyčyśćić pamjyńć podrynčnům do tyi zajty?',
'confirm-purge-bottom' => 'Uodśwjyżeńy zajty wyczyśći pamjyńć podrynczno a wymuśi pokozańy jeij aktualnyj wersyji.',

# Multipage image navigation
'imgmultipageprev' => '← popředńo zajta',
'imgmultipagenext' => 'nostympno zajta →',
'imgmultigo'       => 'Přyńdź',
'imgmultigoto'     => 'Přyńdź do zajty $1',

# Table pager
'ascending_abbrev'         => 'rosn.',
'descending_abbrev'        => 'mal.',
'table_pager_next'         => 'Nostympno zajta',
'table_pager_prev'         => 'Popředńo zajta',
'table_pager_first'        => 'Pjyrwšo zajta',
'table_pager_last'         => 'Uostatńo zajta',
'table_pager_limit'        => 'Pokož $1 pozycyji na zajće',
'table_pager_limit_submit' => 'Pokož',
'table_pager_empty'        => 'Brak wynikůw',

# Auto-summaries
'autosumm-blank'   => 'POZŮR! Usůńjyńće treśći (zajta pozostoua pusto)!',
'autosumm-replace' => 'POZŮR! Zastůmpjyńy treśći hasua bardzo krůtkym tekstym: „$1”',
'autoredircomment' => 'Překerowańy do [[$1]]',
'autosumm-new'     => 'Nowo zajta: $1',

# Live preview
'livepreview-loading' => 'Trwo uadowańy…',
'livepreview-ready'   => 'Trwo uadowańe… Gotowe!',
'livepreview-failed'  => 'Podglůnd na žywo ńy zadźouou! Poprůbuj podglůndu standardowygo.',
'livepreview-error'   => 'Ńyudane pouůnčyńe: $1 „$2”. Poprůbuj podglůndu standardowygo.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Na tyj liśće zmjany nowsze jak {{PLURAL:$1|sekůnda|sekůnd}} můgům ńy być widoczne.',
'lag-warn-high'   => 'S kuli srogigo uobćůnżyńo serwerůw bazy danych, na tyj liśće zmjany nowše jak {{PLURAL:$1|sekůnda|sekůnd}} můgům ńy być widoczne.',

# Watchlist editor
'watchlistedit-numitems'       => 'Twoja lista artikli na kere dowoš pozůr mo {{PLURAL:$1|1 titel|$1 title|$1 titlůw}}, ńy ličůnc zajtůw godki.',
'watchlistedit-noitems'        => 'Twoja lista artikli na kere dowoš pozůr je pusto.',
'watchlistedit-normal-title'   => 'Sprowjej lista zajtůw na kere dowom pozůr',
'watchlistedit-normal-legend'  => 'Wyćep zajty s listy artikli na kere dowoš pozůr',
'watchlistedit-normal-explain' => 'Půńižy moš lista artikli na kere dowoš pozůr.
Coby wyćepać s ńij jako zajta,zaznač pole při ńij i naćiś knefel "Wyćep zaznačůne pozycyje".
Možeš tyž skořistać ze [[Special:EditWatchlist/raw|tekstowygo edytora listy artikli na kere dowoš pozůr]].',
'watchlistedit-normal-submit'  => 'Wyćep s listy',
'watchlistedit-normal-done'    => 'Z Twoi listy artikli na kere dowoš pozůr {{PLURAL:$1|zostoua wyćepano 1 zajta|zostouy wyćepane $1 zajty|zostouo wyćepanych $1 zajtůw}}:',
'watchlistedit-raw-title'      => 'Tekstowy edytor listy artikli na kere dowoš pozůr',
'watchlistedit-raw-legend'     => 'Tekstowy edytor listy artikli na kere dowoš pozůr',
'watchlistedit-raw-explain'    => 'Půńižy moš lista artikli na kere dowoš pozůr. W koždej lińii znojdowo śe titel jydnygo artikla. Lista možeš sprowjać dodajůnc nowe zajty i wyćepujůnc te kere na ńij sům. Jak skůńčyš, naćiś knefel „Uaktualńij lista zajtůw na kere dowům pozůr”.
Možeš tyž [[Special:EditWatchlist|užyć standardowygo edytora]].',
'watchlistedit-raw-titles'     => 'Title:',
'watchlistedit-raw-submit'     => 'Uaktualńij lista',
'watchlistedit-raw-done'       => 'Lista zajtůw na kere dowoš pozůr zostoua uaktualńůna',
'watchlistedit-raw-added'      => 'Dodano {{PLURAL:$1|1 pozycyja|$1 pozycyje|$1 pozycyji}} do listy artikli na kere dowoš pozůr:',
'watchlistedit-raw-removed'    => 'Wyćepano {{PLURAL:$1|1 pozycyja|$1 pozycyje|$1 pozycyji}} z listy zajtůw na kere dowoš pozůr:',

# Watchlist editing tools
'watchlisttools-view' => 'Pokož wažńijše pomjyńańo',
'watchlisttools-edit' => 'Pokož i zmjyńoj pozorliste',
'watchlisttools-raw'  => 'Zmjyńoj surowo pozorlista',

# Core parser functions
'unknown_extension_tag' => 'Ńyznany značńik rozšeřyńo „$1”',
'duplicate-defaultsort' => 'Pozůr: Zmjarkowanym kluczym sortowańo bydźe "$2" a zastůmpi uůn zawczasu używany klucz "$1".',

# Special:Version
'version'                       => 'Wersjo',
'version-extensions'            => 'Zainstalowane rozšeřyńa',
'version-specialpages'          => 'Szpecjalne zajty',
'version-parserhooks'           => 'Haki analizatora skuadńi (ang. parser hooks)',
'version-variables'             => 'Zmjynne',
'version-other'                 => 'Inkše',
'version-mediahandlers'         => 'Wtyčki uobsůgi medjůw',
'version-hooks'                 => 'Haki (ang. hooks)',
'version-extension-functions'   => 'Fůnkcyje rozšyřyń',
'version-parser-extensiontags'  => 'Značńiki rozšeřyń do analizatora skuadńi',
'version-parser-function-hooks' => 'Fůnkcyje hokůw analizatora skuadńi (ang. parser function hooks)',
'version-hook-name'             => 'Mjano haka (ang. hook name)',
'version-hook-subscribedby'     => 'Zapotřebowany bez',
'version-version'               => '(Wersjo $1)',
'version-license'               => 'Licencjo',
'version-software'              => 'Zainstalowane uoprůgramowańy',
'version-software-product'      => 'Mjano',
'version-software-version'      => 'Wersjo',

# Special:FilePath
'filepath'         => 'Śćežka do plika',
'filepath-page'    => 'Plik:',
'filepath-submit'  => 'Śćežka',
'filepath-summary' => 'Ta ekstra zajta zwraco peuno śćyžka do plika.
Grafiki sům pokazywane w peunyj rozdźelčośći, inkše typy plikůw sům uodmykane we skojařůnym ś ńimi průgramje.

Naškryflej sam mjano plika bez prefiksu „{{ns:file}}:”.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Šnupej za duplikatym plika',
'fileduplicatesearch-summary'  => 'Šnupej za duplikatůma plika na podstawje wartośći fůnkcyji skrůtu.',
'fileduplicatesearch-legend'   => 'Šnupej za duplikatůma plika',
'fileduplicatesearch-filename' => 'Mjano pliku:',
'fileduplicatesearch-submit'   => 'Šnupej',
'fileduplicatesearch-info'     => '$1 × $2 pikseli<br />Wjelgość plika: $3<br />Typ MIME: $4',
'fileduplicatesearch-result-1' => 'Ńy ma duplikatu pliku „$1”.',
'fileduplicatesearch-result-n' => 'We {{GRAMMAR:MS.lp|{{SITENAME}}}} {{PLURAL:$2|je dodatkowo kopia|sům $2 dodatkowe kopje|je $2 dodatkowych kopii}} plika „$1”.',

# Special:SpecialPages
'specialpages'                   => 'Szpecjalne zajty',
'specialpages-note'              => '----
* Ekstra zajty uogůlńy dostympne.
* <strong class="mw-specialpagerestricted">Ekstra zajty do kerych dostymp je uograńiczůny.</strong>',
'specialpages-group-maintenance' => 'Raporty kůnserwacyjne',
'specialpages-group-other'       => 'Inkše ekstra zajty',
'specialpages-group-login'       => 'Lůgowańy / rejerowańy',
'specialpages-group-changes'     => 'Pomjyńane na uostatku a rejery',
'specialpages-group-media'       => 'Pliki',
'specialpages-group-users'       => 'Užytkowńiki i uprawńyńa',
'specialpages-group-highuse'     => 'Zajty čynsto užywane',
'specialpages-group-pages'       => 'Zajty',
'specialpages-group-pagetools'   => 'Nořyńdźa zajtůw',
'specialpages-group-wiki'        => 'Informacyje a nořyńdźa wiki',
'specialpages-group-redirects'   => 'Ekstra zajty, kere kerujům',
'specialpages-group-spam'        => 'Nořyńdźa do wyćepywanio spamu',

# Special:BlankPage
'blankpage'              => 'Pusto zajta',
'intentionallyblankpage' => 'Ta zajta nauůmyślńy uostoua śe pusto',

# External image whitelist
'external_image_whitelist' => '#Leave this line exactly as it is<pre>
#Wstow půńiżyj tajle wyrażyń regularnych (jyno to, co znojduje śe mjyndzy //)
#Wyrażyńa te uostanům przipasowane ku URL-ům zewnyntrznym (bezpostrzredńo linkowanych) grafik
#Dopasowane URL-e zostanům wyśwjetlůne kej grafiki, w przećiwnym raźe bydźe pokozany yno link ku grafice
#Lińje kere s anfanga majům # sům traktowane kej kůmyntorze

#Put all regex fragments above this line. Leave this line exactly as it is</pre>',

# Special:Tags
'tag-filter' => 'Filter [[Special:Tags|tagůw]]',

# New logging system
'revdelete-restricted'   => 'naštaluj uograničyńo do administratorůw',
'revdelete-unrestricted' => 'wycofej uograničyńo do administratorůw',
'newuserlog-byemail'     => 'hasło uostało wysłane e-brifym',

);
