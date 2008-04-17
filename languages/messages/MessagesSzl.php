<?php
/** Silesian (ślůnski)
 *
 * @addtogroup Language
 *
 * @author Lajsikonik
 * @author Herr Kriss
 * @author Pimke
 * @author Siebrand
 * @author Jon Harald Søby
 * @author SPQRobin
 */

$fallback = 'pl';

$messages = array(
# User preference toggles
'tog-underline'               => 'Podkreślynie linkůw:',
'tog-highlightbroken'         => 'Uoznocz <a href="" class="new">tak</a> linki do zajtůw kere brakůjům (abo tyž: doůončany pytajnik<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Wyrůwnůj tekst w akapitach do uobu strůn',
'tog-hideminor'               => 'Schowej drobne pomjyńańo w "Pomjyńanych na uostatku"',
'tog-extendwatchlist'         => 'Rozšežůno lista artikli, na kere dowoš pozůr',
'tog-usenewrc'                => 'Rozšyžynie pomiyńanych na uostatku (JavaScript)',
'tog-numberheadings'          => 'Automatyčno numeracjo naguůwkůw',
'tog-showtoolbar'             => 'Pokož pasek werkcojgůw (JavaScript)',
'tog-editondblclick'          => 'Přyńdź do sprowjańo po podwůjnym klikńyńću (JavaScript)',
'tog-editsection'             => 'Možliwość sprowjańo poščegůlnych tajlůw zajty',
'tog-editsectiononrightclick' => 'Klikńyńće prawym přyćiskiym myšy na titlu tajla<br />začyno jego sprowjańe(JavaScript)',
'tog-showtoc'                 => 'Pokož spis treści (na zajtach kere majům wjencyi jak trziy naguůwki)',
'tog-rememberpassword'        => 'Pamjyntej moje hasuo na tym komputře',
'tog-editwidth'               => 'Uobšar sprowjańo uo poůnyi šyrokośći',
'tog-watchcreations'          => 'Doćepuj zajty kere žech naškréfloů do zajtůw, na kere dowom pozůr',
'tog-watchdefault'            => 'Doćepuj zajty, kere žech sprowjoů, do zajtůw na kere dowom pozůr',
'tog-watchmoves'              => 'Doćepuj zajty, kere žech přećepywoů, do zajtůw, na kere dowom pozůr',
'tog-watchdeletion'           => 'Doćepuj zajty, kere žech wyćep, do zajtůw, na kere dowom pozůr',
'tog-minordefault'            => 'Uoznačej wšyskie moje sprowjyńo domyślńy jako drobne',
'tog-previewontop'            => 'Pokazůj podglůnd před uobšarym sprowjańo',
'tog-previewonfirst'          => 'Pokož podglůnd zajty přy pjyršym sprowjańu',
'tog-nocache'                 => 'Wyuůnč pamjyńć podrynčno',
'tog-enotifwatchlistpages'    => 'Wyślij e-brifa jak jako zajta z tych na kere dowom pozůr bydzie zmjyńono',
'tog-enotifusertalkpages'     => 'Wyślij e-brifa jak zajta mojiy godki bydzie zmjyńono',
'tog-enotifminoredits'        => 'Wyślij e-brifa tyž w takiym razie, jak by chodziyuo o drobne pomjyńańa',
'tog-enotifrevealaddr'        => 'Ńy chowej adresa mojygo e-brifa w powjadomjyńach',
'tog-shownumberswatching'     => 'Pokož wjela užytkownikůw dowo pozůr',
'tog-fancysig'                => 'Šrajbowańe bez automatyčnego linka',
'tog-externaleditor'          => 'Domyślńe užywej zewnytřny edytor',
'tog-externaldiff'            => 'Domyślńe užywej zewnyntřny program do filowańo w pomjyńańa',
'tog-showjumplinks'           => 'Zauůnč cajchnůndzki "přéńdź do"',
'tog-uselivepreview'          => 'Užywej dynamičnego podglůndu (JavaScript) (experymentalny)',
'tog-forceeditsummary'        => 'Dej znać jakbych nic ńy naškréflou w opiśe pomjyńań',
'tog-watchlisthideown'        => 'Schowej moje pomjyńańa w artiklach na kere dowom pozůr',
'tog-watchlisthidebots'       => 'Schowej pomjyńańa sprowjone bez boty w artiklach na kere dowom pozůr',
'tog-watchlisthideminor'      => 'Schowej drobne pomjyńańa w artiklach na kere dowom pozůr',
'tog-ccmeonemails'            => 'Přesyuej mi kopie e-brifůw co žech je posuoů inkšym užytkownikom',
'tog-diffonly'                => 'Ńy pokozůj treśći zajtůw půnižy porůwnańo pomjyńań',
'tog-showhiddencats'          => 'Pokož schowane kategoryje',

'underline-always'  => 'Zawdy',
'underline-never'   => 'Nigdy',
'underline-default' => 'Wedle štalowańo přeglůndarki',

'skinpreview' => '(podglůnd)',

# Dates
'sunday'        => 'Ńedźela',
'monday'        => 'Pyńdźouek',
'tuesday'       => 'Wtorek',
'wednesday'     => 'Střoda',
'thursday'      => 'Štwortek',
'friday'        => 'Pjůntek',
'saturday'      => 'Sobota',
'sun'           => 'Ńed',
'mon'           => 'Pyń',
'tue'           => 'Wto',
'wed'           => 'Stř',
'thu'           => 'Štw',
'fri'           => 'Pjů',
'sat'           => 'Sob',
'january'       => 'styčyń',
'february'      => 'luty',
'march'         => 'mařec',
'april'         => 'kwjećyń',
'may_long'      => 'moj',
'june'          => 'čyrwjec',
'july'          => 'lipjec',
'august'        => 'śyrpjyń',
'september'     => 'wřeśyń',
'october'       => 'paźdźerńik',
'november'      => 'listopad',
'december'      => 'grudźyń',
'january-gen'   => 'styčńa',
'february-gen'  => 'lutygo',
'march-gen'     => 'marca',
'april-gen'     => 'kwjetńa',
'may-gen'       => 'maja',
'june-gen'      => 'čyrwca',
'july-gen'      => 'lipca',
'august-gen'    => 'śyrpńa',
'september-gen' => 'wřeśńa',
'october-gen'   => 'paźdźerńika',
'november-gen'  => 'listopada',
'december-gen'  => 'grudńa',
'jan'           => 'sty',
'feb'           => 'lut',
'mar'           => 'mař',
'apr'           => 'kwj',
'may'           => 'moj',
'jun'           => 'čyr',
'jul'           => 'lip',
'aug'           => 'śyr',
'sep'           => 'wře',
'oct'           => 'paź',
'nov'           => 'lis',
'dec'           => 'gru',

# Categories related messages
'categories'                     => 'Kategoryje',
'categoriespagetext'             => 'Ponižy wymjeńone kategoryje sům na wiki.',
'special-categories-sort-count'  => 'sortowanie wedle ličby',
'special-categories-sort-abc'    => 'sortowanie wedle alfabyta',
'pagecategories'                 => '{{PLURAL:$1|Kategoryja|Kategoryje|Kategorjůw}}',
'category_header'                => 'Zajty w kategorie "$1"',
'subcategories'                  => 'Podkategoryje',
'category-media-header'          => 'Pliki w kategoryji "$1"',
'category-empty'                 => "''W tyi katygorii ńy ma terozki artikli ańi plikůw''",
'hidden-categories'              => '{{PLURAL:$1|Schowano kategoryja|Schowane kategoryje|Schowanych kategorjůw}}',
'hidden-category-category'       => 'Schowane kategoryje', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|Ta kategoryja mo ino jedna podkategorja.|Ta kategoryja mo {{PLURAL:$1|nastympůjąco podkategorja|$1 podkategorje|$1 podkategorjůw}} s ličby kategorjów ogůuem: $2.}}',
'category-subcat-count-limited'  => 'Ta kategoryja mo {{PLURAL:$1|nastympůjąco podkategorja|$1 podkategorje|$1 podkategorjůw}}.',
'category-article-count'         => '{{PLURAL:$2|W kategoryji jest jedno zajta.|W kategoryji {{PLURAL:$1|zostoua pokazana $1 zajta|zostouy pokazane $1 zajty|zostouo pokazanych $1 zajtůw}} z uončny ličby $2 zajtůw.}}',
'category-article-count-limited' => 'W kategoryji {{PLURAL:$1|zostoua pokozano $1 zajta|zostouy pokozane $1 zajty|zostauo pokozanych $1 zajtůw}}.',
'category-file-count'            => '{{PLURAL:$2|W kategoryji znajdowo sie jedyn plik.|W kategoryji {{PLURAL:$1|zostou pokozany $1 plik|zostouy pokozane $1 pliki|zostouo pokozanych $1 plikůw}} z uončny ličby $2 plikůw.}}',
'category-file-count-limited'    => 'W kategoryji {{PLURAL:$1|zostou pokozany $1 plik|zostouy pokozane $1 pliki|zostouo pokazanych $1 plikůw}}.',
'listingcontinuesabbrev'         => 'c.d.',

'mainpagetext'      => "<big>'''Inštalacjo MediaWiki powiodua sie.'''</big>",
'mainpagedocfooter' => 'Uobejřij [http://meta.wikimedia.org/wiki/Help:Contents přewodńik užytkownika], kaj sům informacje uo dziauańu uoprogramowanio MediaWiki.

== Na štart ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Lista štalowań konfiguracyji]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Komůnikaty uo nowych wersjach MediaWiki]',

'about'          => 'Uo serwiśe',
'article'        => 'zajta',
'newwindow'      => '(odmyko śe w nowym uokńy)',
'cancel'         => 'Odćepnij',
'qbfind'         => 'Šnupej',
'qbbrowse'       => 'Přeglůndańe',
'qbedit'         => 'Sprowjéj',
'qbpageoptions'  => 'Ta zajta',
'qbpageinfo'     => 'Kontekst',
'qbmyoptions'    => 'Moje zajty',
'qbspecialpages' => 'Extra zajty',
'moredotdotdot'  => 'Wincyj...',
'mypage'         => 'Mojo zajta',
'mytalk'         => 'Mojo godka',
'anontalk'       => 'Godka tego IP',
'navigation'     => 'Nawigacjo',
'and'            => 'i',

# Metadata in edit box
'metadata_help' => 'Metadane:',

'errorpagetitle'    => 'Feler',
'returnto'          => 'Nazod do zajty $1.',
'tagline'           => 'S {{GRAMMAR:D.lp|{{SITENAME}}}}',
'help'              => 'Pomoc',
'search'            => 'Šnupej',
'searchbutton'      => 'Šnupej',
'go'                => 'Přéńdź',
'searcharticle'     => 'Přéńdź',
'history'           => 'Historia zajty',
'history_short'     => 'Historjo',
'updatedmarker'     => 'pomjeńane uod uostatniy wizyty',
'info_short'        => 'Informacjo',
'printableversion'  => 'Wersyjo do druku',
'permalink'         => 'Bezpośredńi link',
'print'             => 'Drukuj',
'edit'              => 'sprowjéj',
'create'            => 'Utwůř',
'editthispage'      => 'Sprowiej ta zajta',
'create-this-page'  => 'Utwůř ta zajta',
'delete'            => 'Wyćep',
'deletethispage'    => 'Wyćep ta zajta',
'undelete_short'    => 'Wćep nazod {{PLURAL:$1|jedna wersja|$1 wersje|$1 wersji}}',
'protect'           => 'Zawřij',
'protect_change'    => 'zmień',
'protectthispage'   => 'Zawřij ta zajta',
'unprotect'         => 'Uodymknij',
'unprotectthispage' => 'Uodymknij ta zajta',
'newpage'           => 'Nowy artikel',
'talkpage'          => 'Godej o tym artiklu',
'talkpagelinktext'  => 'Godka',
'specialpage'       => 'Extra zajta',
'personaltools'     => 'Osobiste',
'postcomment'       => 'Skomyntuj',
'articlepage'       => 'Zajta artikla',
'talk'              => 'Godka',
'views'             => 'Widok',
'toolbox'           => 'Werkcojg',
'userpage'          => 'Zajta užytkownika',
'projectpage'       => 'Zajta projekta',
'imagepage'         => 'Zajta grafiki',
'mediawikipage'     => 'Zajta komunikata',
'templatepage'      => 'Zajta šablůna',
'viewhelppage'      => 'Zajta pomocy',
'categorypage'      => 'Zajta kategoryji',
'viewtalkpage'      => 'Zajta godki',
'otherlanguages'    => 'W inkšych godkach',
'redirectedfrom'    => '(Překerowano s $1)',
'redirectpagesub'   => 'Zajta překerowujůnca',
'lastmodifiedat'    => 'Ta zajta uostatnio sprowjano $2, $1.', # $1 date, $2 time
'viewcount'         => 'W ta zajta filowano {{PLURAL:$1|tylko roz|$1 rozůw}}.',
'protectedpage'     => 'Zajta zawarto',
'jumpto'            => 'Přéńdź do:',
'jumptonavigation'  => 'nawigacyji',
'jumptosearch'      => 'šnupańe',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Uo {{GRAMMAR:MS.lp|{{SITENAME}}}}',
'aboutpage'            => 'Project:Uo serwiśe',
'bugreports'           => 'Raport o felerach',
'bugreportspage'       => 'Project:Felery',
'copyright'            => 'Tekst udostympniany na licencje $1.',
'copyrightpagename'    => 'prawami autorskimi {{GRAMMAR:D.lp|{{SITENAME}}}}',
'copyrightpage'        => '{{ns:project}}:Prawa autorskie',
'currentevents'        => 'Bježůnce wydařyńa',
'currentevents-url'    => 'Project:Bježůnce wydařyńa',
'disclaimers'          => 'Informacyje prawne',
'disclaimerpage'       => 'Project:Informacyje prawne',
'edithelp'             => 'Pomoc we pomjyńańu',
'edithelppage'         => 'Help:Jak pomjyńać zajta',
'faq'                  => 'FAQ',
'faqpage'              => 'Project:FAQ',
'helppage'             => 'Help:Pomoc',
'mainpage'             => 'Přodńo zajta',
'mainpage-description' => 'Přodńo zajta',
'policy-url'           => 'Project:Zasady',
'portal'               => 'Portal užytkowńikůw',
'portal-url'           => 'Project:Portal užytkowńikůw',
'privacy'              => 'Zasady chrońyńo prywatności',
'privacypage'          => 'Project:Zasady chrońyńo prywatnośći',
'sitesupport'          => 'Śćepa',
'sitesupport-url'      => 'Project:Śćepa',

'badaccess'        => 'Felerne uprawńyńo',
'badaccess-group0' => 'Ńy moš uprawńyń coby wykůnać ta uoperacjo.',
'badaccess-group1' => 'Ta uoperacjo mogům wykůnać ino užytkownicy z grupy $1',
'badaccess-group2' => 'Ta uoperacjo mogům wykůnać ino užytkownicy s keryjś z grup $1.',
'badaccess-groups' => 'Ta uoperacjo mogům wykůnać ino užytkownicy s keryjś z grup $1.',

'versionrequired'     => 'Wymagano MediaWiki we wersji $1',
'versionrequiredtext' => 'Wymagano jest MediaWiki we wersji $1 coby skořystać z ty zajty. Uoboč [[Special:Version]]',

'ok'                      => 'OK',
'retrievedfrom'           => 'Zdřůduo "$1"',
'youhavenewmessages'      => 'Mosz $1 ($2).',
'newmessageslink'         => 'nowe wjadůmośći',
'newmessagesdifflink'     => 'ostatnio dyferéncyjo',
'youhavenewmessagesmulti' => 'Moš nowe wjadomości: $1',
'editsection'             => 'sprowjéj',
'editold'                 => 'sprowjéj',
'viewsourceold'           => 'pokož zdřuduo',
'editsectionhint'         => 'Sprowjéj tajla: $1',
'toc'                     => 'Spis treśći',
'showtoc'                 => 'pokož',
'hidetoc'                 => 'schrůń',
'thisisdeleted'           => 'Pokož/wćepej nazod $1',
'viewdeleted'             => 'Uobejžij $1',
'restorelink'             => '{{PLURAL:$1|jedna wyćepano wersja|$1 wyćepane wersje|$1 wyćepanych wersjůw}}',
'feedlinks'               => 'Kanau:',
'feed-invalid'            => 'Ńywuaściwy typ kanauů informacyjnego.',
'feed-unavailable'        => 'Kanauy informacyjne ńy sům dostympne na {{GRAMMAR:MS.lp|{{SITENAME}}}}',
'site-rss-feed'           => 'Kanau RSS {{GRAMMAR:D.lp|$1}}',
'site-atom-feed'          => 'Kanau Atom {{GRAMMAR:D.lp|$1}}',
'page-rss-feed'           => 'Kanau RSS "$1"',
'page-atom-feed'          => 'Kanau Atom "$1"',
'red-link-title'          => '$1 (ješče ńy utwořono)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Zajta',
'nstab-user'      => 'Zajta užytkowńika',
'nstab-media'     => 'Media',
'nstab-special'   => 'Extra zajta',
'nstab-project'   => 'Zajta projektu',
'nstab-image'     => 'Plik',
'nstab-mediawiki' => 'Komunikat',
'nstab-template'  => 'Šablôna',
'nstab-help'      => 'Zajta pomocy',
'nstab-category'  => 'Kategoryja',

# Main script and global functions
'nosuchaction'      => 'Ńy ma takiy uoperacje',
'nosuchactiontext'  => 'Uoprogramowańe ńy rozpoznowo uoperacje takiy jak podano w URL',
'nosuchspecialpage' => 'Ńy ma takiy extra zajty',
'nospecialpagetext' => 'Uoprogramowańe ńy rozpoznaje takiy extra zajty. Lista extra zajtůw znejdzieš na [[{{ns:special}}:Specialpages]]',

# General errors
'error'                => 'Feler',
'databaseerror'        => 'Feler bazy danych',
'dberrortext'          => 'Zdožyu sie feler we skuadńe zapytańa do bazy danych. Uostatńe, ńyudane zapytańe to:
<blockquote><tt>$1</tt></blockquote>
wysuane bez funkcja "<tt>$2</tt>".
MySQL zguosiů bůond "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Zdožyu sie feler we skuadńe zapytańo do bazy danych. Uostatńe, ńyudane zapytańe to:
"$1"
kere wywouoůa fukcjo "$2".
MySQL zguosiyů bůond "$3: $4"',
'noconnect'            => 'Přeprašomy! {{SITENAME}} mo chwilowo problemy technične. Ńy možna pouůnčyć sie ze serwerym bazy danych.<br />$1',
'nodb'                 => 'Ńy idźe znejść bazy danych $1',
'cachederror'          => 'To co sam nanaškréflane to ino kopia z pamjyńći podrynčnyi i može ńy być aktualne.',
'laggedslavemode'      => 'Dej pozůr: Ta zajta može ńy mjeć nojnowšych aktualizacjůw.',
'readonly'             => 'Baza danych jest zawarto',
'enterlockreason'      => 'Naškréflej sam powůd zawarća bazy danych i za wiela (mniy-wiyncyi) jom uodymkńeš',
'readonlytext'         => 'Baza danych jest terozki zawarto
- ńy možna wćepywać nowych artikli ani sprowjać juž wćepanych. Powodem
sům prawdopodobńe czynności administracyjne. Po jeich zakůńčeńu peuno funkcjonalność bazy bydźe přywrůcono.
Administrator, kery zablokowou baza, podou takie wyjaśńyńe:<br /> $1',
'missingarticle'       => 'Uoprogramowanie ńy znoduo tekstu zajty "$1", kero powinna sie znajdować w bazie.

Zwykle sie to trefio, jak zostańe wybrany link do wyćepanej zajty,
np. do staršej wersji inkšyi z zajtów.

Jak to ńy je powůd, to možeś znod feler w uoprogramowańu. W takiym raźe zguoś, proša, tyn fakt
administratorowi, końečńe podej w zguošyńu adres URL tyi zajty.',
'readonly_lag'         => 'Baza danych zostoua automatyčnie zawarto na čas potřebny na synchronizacja zmian miyndzy serwerem guůwnym i serwerami pośredničůncymi.',
'internalerror'        => 'Wewnyntřny feler',
'internalerror_info'   => 'Wewnytřny feler: $1',
'filecopyerror'        => 'Ńy idźe skopiować plika "$1" do "$2".',
'filerenameerror'      => 'Ńy idźe zmjynić nazwy plika "$1" na "$2".',
'filedeleteerror'      => 'Ńy idźe wyćepać plika "$1".',
'directorycreateerror' => 'Ńy idźe utwořyć katalogu "$1".',
'filenotfound'         => 'Ńy idźe znejść plika "$1".',
'fileexistserror'      => 'Ńy idźe sprowjać we pliku "$1": plik istńeje',
'unexpected'           => 'Ńyspodźewano wartość: "$1"="$2".',
'formerror'            => 'Feler: ńy idźe wysuać formulařa',
'badarticleerror'      => 'Tyi uoperacyji ńy idźe zrobiyć dlo tyi zajty.',
'cannotdelete'         => 'Ńy idźe wyćepać podanyi zajty abo grafiki.',
'badtitle'             => 'Zuy titel',
'badtitletext'         => 'Podano felerny titel zajty. Prawdopodobńy sům w ńym znoki kerych ńy wolno užywać we titlach, abo je pusty.',
'perfdisabled'         => 'Přeprašomy! Coby uodciůnžyć serwer w godźinach ščytu czasowo zawarlimy wykůnanie tyi čynności.',
'perfcached'           => 'To co sam nanaškréflane to ino kopia z pamjyńći podrynčnyi i može ńy być aktualne.',
'perfcachedts'         => 'To co sam nanaškréflane to ino kopia z pamjyńći podrynčnyi i boůo uaktualniůne $1.',
'querypage-no-updates' => 'Uaktualńyńo dlo tyi zajty sům terozki zawarte. Dane kere sam sům nie zostouy uodświežůne.',
'wrong_wfQuery_params' => 'Felerne parametry překozane do wfQuery()<br />
Funkcjo: $1<br />
Zapytanie: $2',
'viewsource'           => 'Tekst źrůduowy',
'viewsourcefor'        => 'dlo $1',
'actionthrottled'      => 'Akcjo wstřiymano',
'actionthrottledtext'  => 'Mechanizm uobrůny před spamym uograničo liczba wykonań tyi čynnośći w jednostce času. Průbowoužeś go uocyganić. Proša sprůbuj na nowo za pora minut.',
'protectedpagetext'    => 'Ta zajta jest zawarto před sprowjańym.',
'viewsourcetext'       => 'We tekst źrůduowy tyi zajty možna dali filować, idźe go tyž kopjować.',
'protectedinterface'   => 'Na tyi zajcie znojdowo sie tekst interfejsa uoprogramowanio, bez tůž uona jest zawarto uod sprowjańo.',
'editinginterface'     => "'''Dej pozůr:''' Sprowjoš zajta na kery jest tekst interfejsa uoprogramowanio. Pomjyńyńa na tyi zajcie zmiyńom wyglůnd interfejsa dlo inkšych užytkownikůw.",
'sqlhidden'            => '(schowano zapytanie SQL)',
'cascadeprotected'     => 'Ta zajta jest zawarto od sprowjańo, po takiymů co uona je zauončono na {{PLURAL:$1|nastympujůncej zajcie, kero zostaua zawarto|nastympujůncych zajtach, kere zostauy zawarte}} z zauončonom opcjom dźedźiczynio:
$2',
'namespaceprotected'   => "Ńy moš uprowńyń coby sprowjać zajty w přestřeńi mjan '''$1'''.",
'customcssjsprotected' => 'Ńy moš uprawńyń do sprowjańo tyi zajty, bo na ńyi sům uosobiste štalowańo inkšego užytkownika.',
'ns-specialprotected'  => 'Ńy idźe sprowjać zajtów we přestřyni mjan {{ns:special}}.',
'titleprotected'       => "Wćepanie sam zajty o takiym mjańe zawar [[User:$1|$1]].
Powůd zawarćo: ''$2''.",

# Login and logout pages
'logouttitle'                => 'Wylogůwańe užytkownika',
'logouttext'                 => '<strong>Terozki jestžeś wylůgowany</strong>.<br />Možeš dali sam sprowjać zajty jako niezalůgowany užytkownik, abo zalůgować sie nazod jako tyn som abo inkšy užytkownik.',
'welcomecreation'            => '== Witej, $1! ==

Uotwarli my sam dlo Ćebje kůnto. Ńy zapomńij poštalować [[{{ns:special}}:Preferences|prefyrencyji]].',
'loginpagetitle'             => 'Logůwańe',
'yourname'                   => 'Login:',
'yourpassword'               => 'Hasuo:',
'yourpasswordagain'          => 'Naškréflej hasuo zaś',
'remembermypassword'         => 'Zapamjyntej moje hasuo na tym kůmputře',
'yourdomainname'             => 'Twojo domyna',
'externaldberror'            => 'Jest jakiś feler w zywnyntřnyj baźe autentyfikacyjnyi, abo ńy moš uprawńyń potřebnych do aktualizacji zewnyntřnego kůnta.',
'loginproblem'               => '<b>Zdořyu sie problym při průbie zalůgowanio.</b><br />Sprůbuj zaś!',
'login'                      => 'Zalůguj mie',
'loginprompt'                => 'Muśiš mjeć zouůnčůne cookies coby můc śe sam zalůgować.',
'userlogin'                  => 'Logowańe / regišterowańe',
'logout'                     => 'Wyloguj mie',
'userlogout'                 => 'Wylogowańe',
'notloggedin'                => 'Ńy jest žeś zalůgowany',
'nologin'                    => 'Niy moš konta? $1.',
'nologinlink'                => 'Regišteruj sie',
'createaccount'              => 'Zouůž nowe kůnto',
'gotaccount'                 => 'Moš juž kůnto? $1.',
'gotaccountlink'             => 'Zalůguj śe',
'createaccountmail'          => 'e-brifym',
'badretype'                  => 'Hasua kere žeś naškréfloů sie ńy zgodzajom jedne z drugiym.',
'userexists'                 => 'Mjano užytkownika, kere žeś wybrou, jest zajynte. Wybjer proša inkše mjano.',
'youremail'                  => 'E-brif:',
'username'                   => 'Mjano užytkownika:',
'uid'                        => 'ID užytkownika:',
'yourrealname'               => 'Prowdźiwe mjano:',
'yourlanguage'               => 'Godka interfejsa',
'yournick'                   => 'Twojo šrajba',
'badsig'                     => 'Felerno šrajba, sprowdź značńiki HTML.',
'badsiglength'               => 'Mjano užytkowńika jest za duůgie. Maksymalno jego duůgość to $1 buchštabůw.',
'email'                      => 'E-brif',
'prefs-help-realname'        => '* Mjano i nazwisko (opcjůnalńy): jak žeś zdecydowou aže je podoš, bydům užyte, coby Twoja robota mjoua atrybucyjo.',
'loginerror'                 => 'Feler při logůwańu',
'prefs-help-email'           => '* E-brif (opcjůnalńe): Podańe e-brifa pozwolo inkšym užytkownikom kůntaktować sie z Toboů bez Twoja zajta užytkownika abo zajta godki i ńy třa při tymu podować swojiych danych identyfikacyjnych.',
'prefs-help-email-required'  => 'Wymogany jest adres e-brifa.',
'nocookiesnew'               => 'Kůnto užytkowńika zostoůo utwořůne, ale nie jestžeś zalůgowany. {{SITENAME}} užywo ćosteček do logůwańo. Moš wyuůnčone ćostečka. Coby sie zalůgować, uodymknij ćostečka i podej nazwa i hasuo swojego kůnta.',
'nocookieslogin'             => '{{SITENAME}} užywo ćosteček do lůgowańo užytkownikůw. Moš zablokowano jeich obsuůga. Sprůbuj zaś jak zauůnčyš obsuůga ćosteček.',
'noname'                     => 'To ńy jest poprowne mjano užytkownika.',
'loginsuccesstitle'          => 'Logůwańe udane',
'loginsuccess'               => "'''Terozki žeś jest zalogůwany do {{SITENAME}} jako \"\$1\".'''",
'nosuchuser'                 => 'Ńy ma sam užytkowńika o mjańe "$1".
Sprowdź pisowńja, abo užyj formulařa půńižej coby utwořić nowe kůnto.',
'nosuchusershort'            => 'Ńy ma sam užytkowńika uo mjańe "<nowiki>$1</nowiki>".',
'nouserspecified'            => 'Podej mjano užytkowńika.',
'wrongpassword'              => 'Hasuo kere žeś naškryflou je felerne. Poprůbůj naškryflać je ješče roz.',
'wrongpasswordempty'         => 'Hasuo kere žeś podou je puste. Naškryflej je ješče roz.',
'passwordtooshort'           => 'Hasuo kere žeś podou je za krůtke.
Hasuo musi mjyć přinojmńij $1 buchštabůw i być inkše uod mjana užytkowńika.',
'mailmypassword'             => 'Wyślij mi nowe hasuo e-brifem',
'passwordremindertitle'      => 'Nowe tymčasowe hasuo dla {{SITENAME}}',
'passwordremindertext'       => 'Ktůś (chyba Ty, s IP $1)
pado, aže chce nowe hasuo do {{SITENAME}} ($4).
Nowe hasuo do užytkowńika "$2" je "$3".
Zalůgůj śe terozki i zmjyń swoje hasuo.

Jak ktůś inkšy chćou nowe hasuo abo jak Ci śe připůmńouo stare i njy chceš nowygo, to zignoruj to i užywyj starygo hasua.',
'noemail'                    => 'Ńy mo u nos adresu e-brifa do "$1".',
'passwordsent'               => 'Nowe hasuo pošuo na e-brifa uod užytkowńika "$1".
Zalůguj śe zaś jak dostańyš tygo brifa.',
'blocked-mailpassword'       => 'Twůj adres IP zostou zawarty i ńy možeš užywać funkcje odzyskiwańo hasua s kuli možliwośći jei nadužywańo.',
'eauthentsent'               => 'E-mail potwjerdzajůncy je wysuany na e-maila.
Jak bydźeš chćou, coby wysůuouo Ci maile, pirwyj go přečytoj. Bydźeš tam mjou instrukcyjo co moš zrobić, coby pokazać, aže tyn adres je Twůj.',
'throttled-mailpassword'     => 'Připůmńeńe hasua boůo juž wysůane bez uostatnie $1 godzin.
Coby powstřimać nadužyća možliwość wysyuańa připůmńeń naštalowano na jedne bez $1 godziny.',
'mailerror'                  => 'Při wysyůańu e-brifa zdožyů sie feler: $1',
'acct_creation_throttle_hit' => 'Přikro nom, zauožyu(a)žeś juž $1 kont(a). Ńy možeš zauožyć kolejnygo.',
'emailauthenticated'         => 'Twůj adres e-brifa zostou uwjeřitelńůny $1.',
'emailnotauthenticated'      => 'Twůj adres e-brifa ńy jest uwjeřitelńůny. Půnižše funkcyje počty ńy bydom dźauać.',
'noemailprefs'               => 'Muśiš podać adres e-brifa, coby te funkcyje dziouauy.',
'emailconfirmlink'           => 'Potwjerdź swůj adres e-brifa',
'invalidemailaddress'        => 'E-brif nie bydźe zaakceptůwany: jego format nie speunio formalnych wymagań. Proša naškryflać poprowny adres e-brifa abo wyčyścić pole.',
'accountcreated'             => 'Utwůřůno kůnto',
'accountcreatedtext'         => 'Kůnto dla $1 zostouo utwůřůne.',
'createaccount-title'        => 'Stwořyńe kůnta na {{GRAMMAR:MS.lp|{{SITENAME}}}}',
'createaccount-text'         => 'Ktoś utwořyu na {{GRAMMAR:MS.lp|{{SITENAME}}}} ($4) dla Twojego adresa e-brif kůnto "$2". Aktualne hasuo to "$3". Powiniežeś sie terozki zalogůwać i je zmjenić.',
'loginlanguagelabel'         => 'Godka: $1',

# Password reset dialog
'resetpass'               => 'Resetuj hasuo',
'resetpass_announce'      => 'Zalůgowoužeś sie z tymčasowym kodym uotřimanym bez e-brif. Aby zakůńčyć proces logůwanio muśiš naštalować nowe hasuo:',
'resetpass_header'        => 'Resetuj hasuo',
'resetpass_submit'        => 'Naštaluj hasuo i zalůguj',
'resetpass_success'       => 'Twoje hasuo zostouo půmyślńe pomjyńone! Trwo logůwańe...',
'resetpass_bad_temporary' => 'Felerne hasuo tymčasowe. Abo možeś juž zakůńčyu proces pomjyńańo hasua, abo poprosiůžeś uo nowe hasuo tymčasowe.',
'resetpass_forbidden'     => 'Na {{GRAMMAR:MS.lp|{{SITENAME}}}} ńy idźe pomjyńyć hasuůw.',
'resetpass_missing'       => 'Formulař ńy mo danych.',

# Edit page toolbar
'bold_sample'     => 'Ruby tekst',
'bold_tip'        => 'Ruby tekst',
'italic_sample'   => 'Tekst pochylůny',
'italic_tip'      => 'Tekst pochylůny',
'link_sample'     => 'Tytuł linka',
'link_tip'        => 'Link wewnyntřny',
'extlink_sample'  => 'http://www.przykuod.szl titel zajty',
'extlink_tip'     => 'Link zewnyntřny (pamjyntej uo prefikśe http:// )',
'headline_sample' => 'Tekst naguůwka',
'headline_tip'    => 'Naguůwek 2. poźůma',
'math_sample'     => 'Sam tukej wprowadź wzůr',
'math_tip'        => 'Wzůr matymatyčny (LaTeX)',
'nowiki_sample'   => 'Wćepej sam tekst bez formatowańo',
'nowiki_tip'      => 'Zignoruj formatowańe wiki',
'image_tip'       => 'Plik uosadzůny',
'media_tip'       => 'Link do plika',
'sig_tip'         => 'Twoje šrajbowańy s datům i časym',
'hr_tip'          => 'Lińo poźůmo (užywej s ůmjarym)',

# Edit pages
'summary'                           => 'Uopis pomjéńań',
'subject'                           => 'Tymat/naguůwek',
'minoredit'                         => 'To je ńjywjelgie sprowjyńe',
'watchthis'                         => 'Dej pozor',
'savearticle'                       => 'Škryflej',
'preview'                           => 'Podglůnd',
'showpreview'                       => 'Pokož podglůnd',
'showlivepreview'                   => 'Dynamičny podglůnd',
'showdiff'                          => 'Pokož dyferéncyje',
'anoneditwarning'                   => 'Nie jest žeś zalogowany. W historie sprowjyń tyi zajty bydzie naškréflany Twůj adres IP.',
'missingsummary'                    => "'''Připomńyńe:''' Ńy wprowadźiužeś uopisu pomjyńań. Jak go nie chceš wprowadzać, naciś knefel Škryflej ješče roz.",
'missingcommenttext'                => 'Wćepej kůmyntoř půnižyi.',
'missingcommentheader'              => "'''Dej pozůr:''' Treść naguůwka jest pusto - uzupeuńyj go! Jeśli tego ńy zrobiš, Twůj kůmyntoř bydzie naškryflany bez naguůwka.",
'summary-preview'                   => 'Podglůnd uopisu',
'subject-preview'                   => 'Podglůnd tematu/naguůwka',
'blockedtitle'                      => 'Užytkownik jest zawarty uod sprowjyń',
'blockedtext'                       => '<big>\'\'\'Twoje kůnto abo adres IP sům zawarte.\'\'\'</big>

Uo zawarću zdecydowou $1. Pado, aže s kuli: \'\'$2\'\'.

* Zawarte uod: $8
* Uodymkńe śe: $6
* Bez cůž: $7
Coby wyjaśńić sprawa zawarćo, naškryflej do $1 abo inkšygo [[{{MediaWiki:Grouppage-sysop}}|admińistratora]].
Ńy možeš posuać e-brifa bez "poślij e-brifa tymu užytkowńikowi", jak žeś ńy podou dobrygo adresa e-brifa we prefyryncyjach , abo jak e-brify moš tyž zawarte. Terozki moš adres IP $3 a nůmer zawarća to #$5. Prošymy podać jedyn abo uobadwa jak chceš pouosprawjać uo zawarću.',
'autoblockedtext'                   => 'Tyn adres IP zostou zawarty automatyčńe, gdyž kořysto ś ńygo inkšy užytkowńik, zawarty uod sprowjyń bez administratora $1.
Powůd zawarćo:

:\'\'$2\'\'

Zawarće uod $8 wygaso $6

Možyš skůntaktować sie z $1 abo jednym z pozostauych [[{{MediaWiki:Grouppage-sysop}}|administratorůw]] jakbyś chiou uzyskać informacyje uo zawarću.

Uwaga: Jakžeś w [[Special:Preferences|preferencjach]] ńy naštalowou prowiduowygo adresa e-brifa, abo e-brify moš tyž zawarte, ńy možeš skožystać z opcje "Poślij e-brifa tymu užytkownikowi".

Identyfikator Twojiy blokady to $5. Zanotuj sie go i podej administratorowi.',
'blockednoreason'                   => 'ńy podano s kuli čego',
'blockedoriginalsource'             => "Zdřůduo '''$1''' zostouo pokozane půnižyj:",
'blockededitsource'                 => "Tekst '''Twojiych sprowjyń''' na '''$1''' zostou pokozany půnižyj:",
'whitelistedittitle'                => 'Ńym začńeš sprowiać ta zajta, muśiš być zalůgowany.',
'whitelistedittext'                 => 'Muśiš $1 coby můc sprowjać artikle.',
'whitelistreadtitle'                => 'Před přečytańym muśiš sie zalůgować',
'whitelistreadtext'                 => 'Muśiš sie [[Special:Userlogin|zalůgować]], coby čytać zajty.',
'whitelistacctitle'                 => 'Ńy wolno Ci zakuodać kůnta',
'whitelistacctext'                  => 'Zakuodanie kůnt na {{GRAMMAR:MS.lp|{{SITENAME}}}} wymogo [[Special:Userlogin|zalůgowańo]] oraz pośadańo uodpowjednich uprowńyń.',
'confirmedittitle'                  => 'Wymogane potwiyrdzynie e-brifa cobyś můg sam sprowjać',
'confirmedittext'                   => 'Muśiš podać i potwjerdźić swůj e-brif coby můc sam sprowjać.
Možeš to zrobić w [[Special:Preferences|swojych štalowańach]].',
'nosuchsectiontitle'                => 'Ńy ma takiy tajli',
'nosuchsectiontext'                 => 'Průbowoužeś sprowjać tajla kero ńy istńeje. Jak sam ńy ma tajli $1, ńy ma tyž kaj naškryflać twojego sprowjyńo.',
'loginreqtitle'                     => 'Muśiš sie zalůgować',
'loginreqlink'                      => 'zalůguj sie',
'loginreqpagetext'                  => 'Muśiš $1 coby můc přeglůndać inkše zajty.',
'accmailtitle'                      => 'Hasuo wysuane.',
'accmailtext'                       => 'Hasuo užytkowńika "$1" zostauo wysuane pod adres $2.',
'newarticle'                        => '(Nowy)',
'newarticletext'                    => 'Ńy ma sam ješče artikla uo tym tytule. W polu ńižej možeš naškryflać jygo pjeršy fragmynt. Jak chćoužeś zrobić co inkše, naćiś ino knefel "Nazod".',
'anontalkpagetext'                  => "---- ''To jest zajta godki dla užytkownikůw anůnimowych - takich, keři ńy majům ješče swojygo kůnta abo ńy chcům go terozki užywać. By ich identyfikować užywomy numerůw IP. Jeśli žeś jest anůnimowym užytkowńikiem i wydowo Ci sie, že zamjyščůne sam kůmentoře ńy sům skiyrowane do Ćebie, [[{{ns:special}}:Userlogin|utwůř proša kůnto abo zalůguj sie]] - bez tůž uńikńeš potym podobnych ńyporozumień.''",
'noarticletext'                     => 'Ńy ma ješče zajty uo tym tytule. Možeš [{{fullurl:{{FULLPAGENAME}}|action=edit}} wćepać artikel {{FULLPAGENAME}}] abo [[Special:Search/{{FULLPAGENAME}}|šnupać za {{FULLPAGENAME}} w inkšych artiklach]].',
'userpage-userdoesnotexist'         => 'Užytkowńik "$1" ńy jest zareještrowany. Sprowdź čy na pewno chcioužeś stwořyć/pomjynić genau ta zajta.',
'clearyourcache'                    => "'''Dej pozůr:''' Coby uobejřeć pomjyńańo po naškryflańu nowych štalowań poleć přeglůndarce wyčyścić zawartość pamiyńći podrynčnyi (cache). '''Mozilla / Firefox / Safari:''' přitřimej ''Shift'' klikajůnc na ''Uodświyž'' abo wciś ''Ctrl-Shift-R'' (''Cmd-Shift-R'' na Macintoshu), '''IE :''' přitřimej ''Ctrl'' klikajůnc na ''Uodświyž'' abo wciś ''Ctrl-F5''; '''Konqueror:''': kliknij knefel ''Uodświyž'' abo wciś ''F5''; užytkowńicy '''Opery''' mogům być zmušeńi coby coukiym wyčyśćić jeich pamjyńć podrynčno we menu ''Werkcojgi→Preferencyje''.",
'usercssjsyoucanpreview'            => '<strong>Podpowiydź:</strong> Užyj knefla "Podglůnd", coby přetestować Twůj nowy arkuš stylůw CSS abo kod JavaScript před jego zašrajbowańym.',
'usercsspreview'                    => "'''Pamjyntej, že to na raźe ino podglůnd Twojego arkuša stylůw - nic ješče ńy zostouo naškreflane!'''",
'userjspreview'                     => "'''Pamjyntej, že to na raźe ino podglůnd Twojego JavaScriptu - nic ješče ńy zostouo naškreflane!'''",
'userinvalidcssjstitle'             => "'''Pozůr:''' Ńy ma skůrki uo nozwie \"\$1\". Pamjyntyj, že zajty užytkownika zawiyrajůnce CSS i JavaScript powinny začynać sie s mouy buchštaby, np. {{ns:user}}:Foo/monobook.css.",
'updated'                           => '(Pomjyńano)',
'note'                              => '<strong>Pozůr:</strong>',
'previewnote'                       => '<strong>To je ino podglůnd - artikel ješče ńy je naškryflany!</strong>',
'previewconflict'                   => 'Wersjo podglůmdano uodnośi się do tekstu z pola edycyji na wjyrchu. Tak bydźe wyglůndać zajta jeśli zdecyduješ sie jům naškryflać.',
'session_fail_preview'              => '<strong>Přeprašomy! Serwer ńy može přetwořyć tygo sprowjyńo s kuli utraty danych ze sesyi. Sprůbuj ješče roz. Kieby to ńy pomoguo - wylůguj sie i zalogůj uod nowa.</strong>',
'session_fail_preview_html'         => "<strong>Přeprašomy! Serwer ńy može přetwořyć tygo srowjyńo s kuli utraty danych ze sesyji.</strong>

''Jako že na {{GRAMMAR:MS.lp|{{SITENAME}}}} wuůnčono zostoua opcja \"raw HTML\", podglůnd zostou schrůńony w celu zabezpiečyńo před atakami JavaScript.''

<strong>Jeśli to jest prawiduowo průba srowjańo, sprůbuj ješče roz. Jakby to ńy pomoguo - wylůguj sie i zalůguj na nowo.</strong>",
'token_suffix_mismatch'             => '<strong>Twoje sprowjyńe zostouo uodćepńynte s kuli tego, co twůj klijynt pomiyšou znaki uod interpůnkcje w žetůńe sprowjyń. Twoje sprowjyńe zostauo uodćepńynte coby zapobiec zńyščyńu tekstu zajty. Takie průblymy zdořajům sie w raźe kůřystańo z felernych anůnimowych śećowych usuůg proxy.</strong>',
'editing'                           => 'Sprowioš $1',
'editingsection'                    => 'Sprowjoš $1 (kůnsek)',
'editingcomment'                    => 'Sprowjoš "$1" (kůmyntoř)',
'editconflict'                      => 'Kůnflikt sprowjyń: $1',
'explainconflict'                   => 'Ktoś zdůnžyu wćepać swoja wersja artikla zanim žeś naškryflou sprowjyńe.
W polu edycyi na wiyrchu moš tekst zajty aktůalńe naškreflany w baźe danych.
Twoje pomjyńańo sům w polu edycyji půnižyi.
By wćepać swoje pomjyńańo muśiš pomjyńać tekst w polu na wiyrchu.
<b>Tylko</b> tekst z pola na wiyrchu bydźe naškreflany we baźe jak wciśńeš "Škryflej".<br />',
'yourtext'                          => 'Twůj tekst',
'storedversion'                     => 'Naškryflano wersyja',
'nonunicodebrowser'                 => '<strong>Pozůr! Twoja přeglůndarka ńy umje poprowńe rozpoznować kodowanio UTF-8 (Unicode). Bez tůž wšyskie znaki, ktůrych Twoja přeglůndarka ńy umje rozpoznować, zamieńůno na jeich kody heksadecymalne.</strong>',
'editingold'                        => '<strong>Dej pozůr: Sprowjoš inkšo wersyja zajty kej bježůnco. Jeśli jům naškryfloš, wšyskie půźniyjše pomjyńańa bydom wyćepane.</strong>',
'yourdiff'                          => 'Dyferencyje',
'copyrightwarning'                  => "Pamjyntej uo tym, aže couki wkuod do {{SITENAME}} udostympńůmy wedle zasad $2 (dokuadńij w $1). Jak ńy chceš, coby koždy můg go zmjyńać i dali rozpowšychńać, ńy wćepuj go sam. Škréflajůnc sam tukej pośwjadčoš tyž, co te pisańy je twoje wuasne, abo žeś go wźůn(a) s materjouůw kere sům na ''public domain'', abo kůmpatybilne.<br />
<strong>PROŠA NIE WĆEPYWAĆ SAM MATERIAUŮW KERE SŮM CHRŮNIONE PRAWEM AUTORSKIM BEZ DOZWOLENIO WUAŚCICIELA!</strong>",
'copyrightwarning2'                 => 'Pamjyntej uo tym, aže couki wkuod do {{GRAMMAR:MS.lp|{{SITENAME}}}} može być sprowjany, pomjyńany abo wyćepany bez inkšych užytkownikůw. Jak ńy chceš, coby koždy můg go zmjyńać i dali rozpowšychńać bez uograničyń, ńy wćepuj go sam.<br />
Škréflajůnc sam tukej pośwjadčoš tyž, co te pisańy je twoje wuasne, abo žeś go wźůn(a) s materjouůw kere sům na public domain, abo kůmpatybilne (kuknij tyž: $1).
<strong>PROŠA NIE WĆEPYWAĆ SAM MATERIAUŮW KERE SŮM CHRŮNIONE PRAWEM AUTORSKIM BEZ DOZWOLENIO WUAŚCICIELA!</strong>',
'longpagewarning'                   => '<strong>Dej pozůr: Ta zajta je $1 kilobajt-y/-ůw wjelgo; w ńykerych přyglůndarkach můgům wystąpić problymy w sprowjańu zajtůw kere majům wjyncyj jak 32 kilobajty. Jak byś ůmjou, podźel tekst na mjyńše tajle.</strong>',
'longpageerror'                     => '<strong>Feler: Tekst kery žeś sam wćepywou mo $1 kilobajtůw. Maksymalno duůgość tekstu ńy može być wiynkšo kej $2 kilobajtůw. Twůj tekst ńy bydźe sam naškryflany.</strong>',
'readonlywarning'                   => '<strong>Dej pozůr: Baza danych zostoua chwilowo zawarto s kuli potřeb administracyjnych. Bez tůž ńy možna terozki naškryflać twojych pomjyńań. Radzymy přećepać nowy tekst kajś do plika tekstowego (wytnij/wklej) i wćepać sam zaś po oudymkńyńću bazy.</strong>',
'protectedpagewarning'              => '<strong>Dej pozůr: Sprowjańe tyi zajty zostouo zawarte. Mogům jům sprowjać ino užytkownicy z uprawńyńami administratora.</strong>',
'semiprotectedpagewarning'          => "'''Uwaga:''' Ta zajta zostoua zawarto i ino zaregištrowani užytkownicy mogům jům sprowjać.",
'cascadeprotectedwarning'           => "'''Dej pozůr:''' Ta zajta zostoua zawarto i ino užytkownicy z uprawńyńami administratora mogům jům sprowjać. Zajta ta jest podpjynto pod {{PLURAL:$1|nastympujůnco zajta, kero zostoua zawarto|nastympujůncych zajtach, kere zostouy zawarte}} ze zauůnčonom opcjům dźedźiczynio:",
'titleprotectedwarning'             => '<strong>DEJ POZŮR: Zajta o tym titlu zostoua zawarto i ino ńykeřy užytkownicy mogům jům wćepać.</strong>',
'templatesused'                     => 'Šablůny užyte na tyi zajće:',
'templatesusedpreview'              => 'Šablôny užyte w tym podglůńdźe:',
'templatesusedsection'              => 'Šablôny užyte w tym tajlu:',
'template-protected'                => '(zawarty před sprowjańym)',
'template-semiprotected'            => '(tajlowo zawarte)',
'hiddencategories'                  => 'Ta zajta jest {{PLURAL:$1|w jednyi schrůńuny kategorje|we $1 schrůńunych kategorjach}}:',
'nocreatetitle'                     => 'Uograničůno wćepywanie zajtůw',
'nocreatetext'                      => 'Na {{GRAMMAR:MS.lp|{{SITENAME}}}} twořyńe nowych zajtów uograničono. Možesz sprowjać te co już sóm, abo [[{{ns:special}}:Userlogin|zalogować sie, abo štartnůnć konto]].',
'nocreate-loggedin'                 => 'Ńy moš uprowńyń do škryflańo zajtów na {{GRAMMAR:MS.lp|{{SITENAME}}}}.',
'permissionserrors'                 => 'Felerne uprowńyńa',
'permissionserrorstext'             => 'Ńy moš uprowńyń do takiy akcje {{PLURAL:$1|s kuli tego, co:|bez tůž, co:}}',
'recreate-deleted-warn'             => "'''Dej pozůr: Průbuješ wćepać nazod zajta kero juž bůua wyćepano.'''

Zastanůw śe, čy sprowjańy nazod tyi zajty mo uzasadńjyńe. Dla wygody užytkowńikůw, ńižyi pokozano rejestr wyćepńjyńć tyi zajty:",
'expensive-parserfunction-warning'  => 'Dej pozůr: ta zajta mo za dužo uodwouań do fůnkcyji parsera, kere mocno uobćůnžajům systym.

Powinno jeich być myńi jak $2, a terozki je $1.',
'expensive-parserfunction-category' => 'Zajty kere majům za dužo uodwouań do fůnkcyji parsera, kere mocno uobćůnžajům systym.',

# "Undo" feature
'undo-success' => 'Sprowjyńe zostouo wycůfane. Proša pomiarkować ukozane půnižy dyferencyje miyndzy wersjami coby zweryfikować jeich poprawność, potym zaś naškryflać pomjyńańo coby zakońčyć uoperacjo.',
'undo-failure' => 'Sprowjyńo ńy idźe wycofać s kuli kůnflikta ze wersjami pośrednimi.',
'undo-summary' => 'Wycůfańe wersji $1 naškryflanej bez [[Special:Contributions/$2]] ([[User talk:$2]])',

# Account creation failure
'cantcreateaccounttitle' => 'Ńy idźe utwořić kůnta',
'cantcreateaccount-text' => "Twořyńy kůnta s tygo adresu IP ('''$1''') zostouo zawarte bez užytkowńika [[User:$3|$3]].

S kuli: ''$2''",

# History pages
'viewpagelogs'        => 'Uoboč rejery uoperacyji do tyi zajty',
'nohistory'           => 'Ta zajta ńy mo swojej historii sprowjyń.',
'revnotfound'         => 'Wersyjo ńy zostoua znejdźůno',
'revnotfoundtext'     => 'Ńy idźe znejść staršej wersyji zajty. Sprawdź, proša, URL kery žeś užůu coby uzyskać dostymp do tej zajty.',
'currentrev'          => 'Aktualno wersyja',
'revisionasof'        => 'Wersyjo z dńa $1',
'revision-info'       => 'Wersyjo z dńa $1; $2',
'previousrevision'    => '← popředńo wersyjo',
'nextrevision'        => 'Naštympno wersyjo→',
'currentrevisionlink' => 'Aktualno wersyjo',
'cur'                 => 'biež',
'next'                => 'nastympno',
'last'                => 'popř',
'page_first'          => 'počůntek',
'page_last'           => 'kůńyc',
'histlegend'          => 'Wybůr růžńic do porůwnańo: postow kropki we boksach i naćiś enter abo knefel na dole.<br />
Lygynda: (bjež) - růžńice s wersyjo bježůncům, (popř) - růžńice s wersyjo popředzajůncům, d - drobne zmjany',
'deletedrev'          => '[wyćepano]',
'histfirst'           => 'od počůntku',
'histlast'            => 'od uostatka',
'historysize'         => '({{PLURAL:$1|1 bajt|$1 bajty|$1 bajtůw}})',
'historyempty'        => '(pusto)',

# Revision feed
'history-feed-title'          => 'Historia wersji',
'history-feed-description'    => 'Historio wersji tej zajty wiki',
'history-feed-item-nocomment' => '$1 uo $2', # user at time
'history-feed-empty'          => 'Wybrano zajta ńy istńije. Můgua zostać wyćepano abo přećepano pod inkše mjano. Možeš tyž [[{{ns:special}}:Search|šnupać]] za tům zajtům.',

# Revision deletion
'rev-deleted-comment'         => '(komyntorz wyćepany)',
'rev-deleted-user'            => '(užytkowńik wyćepany)',
'rev-deleted-event'           => '(škryflańe wyćepane)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Wersyjo tej zajty zostoua wyćepano i ńy je dostympna publičńy. Ščygůuy idźe znejść we [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} rejeře wyćepań].
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Ta wersyjo zajty zostoua wyćepano i ńy je dostympna publičńy.
Ale jako admińistrator {{GRAMMAR:MS.lp|{{SITENAME}}}} možeš jům uobejřeć.
Powody wyćepańo idźe znejść we [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} rejeře wyćepań]
</div>',
'rev-delundel'                => 'pokož/schrůń',
'revisiondelete'              => 'Wyćep/wćep nazod wersyje',
'revdelete-nooldid-title'     => 'Ńy wybrano wersji',
'revdelete-nooldid-text'      => 'Ńy wybrano wersyji na kerych mo zostać wykůnano ta uoperacyjo.',
'revdelete-selected'          => '{{PLURAL:$2|Wybrano wersyja|Wybrane wersyje}} zajty [[:$1]]:',
'logdelete-selected'          => "{{PLURAL:$2|Wybrane zdařyńy s rejeru|Wybrane zdařyńa s rejeru}} dlo '''$1:'''",
'revdelete-text'              => 'Wyćepane wersyje bydům dali widočne w historii zajty, ale jeich treść ńy bydźe publičńy dostympna.

Inkśi admińistratoři {{GRAMMAR:D.lp|{{SITENAME}}}} dali bydům mjeć dostymp do schrůńůnych wersyji i bydům můgli je wćepać nazod, chyba aže uoperator serwisu nouožůu dodatkowe uůgrańičyńo.',
'revdelete-legend'            => 'Naštaluj uůgrańičyńo do wersyji:',
'revdelete-hide-text'         => 'Schrůń tekst wersyji',
'revdelete-hide-name'         => 'Schrůń akcyjo i cel',
'revdelete-hide-comment'      => 'Schrůń kůmyntař sprowjyńa',
'revdelete-hide-user'         => 'Schrůń mjano užytkowńika/adres IP',
'revdelete-hide-restricted'   => 'Wprowadź te uůgrańičyńo zarůwno do admińistratorůw jak i do inkšych',
'revdelete-suppress'          => 'Schrůń informacyje zarůwno před admińistratorůma jak i před inkšymi',
'revdelete-hide-image'        => 'Schrůń zawartość plika',
'revdelete-unsuppress'        => 'Uosůń uůgrańičyńo do wćepanej nazod historii pomjyńań',
'revdelete-log'               => 'Kůmyntoř:',
'revdelete-submit'            => 'Zaakceptuj do wybranych wersyji',
'revdelete-logentry'          => 'půmjyńůno widočność wersyji w [[$1]]',
'logdelete-logentry'          => 'půmjyńůno widočność zdořyńůw w [[$1]]',
'revdelete-success'           => 'Půmyślńy zmjyńůno widočność wersyji.',
'logdelete-success'           => 'Půmyślńy půmjyńůno widočność zdařyń',
'revdel-restore'              => 'Půmjyń widočność',
'pagehist'                    => 'Historia sprowjyń zajty',
'deletedhist'                 => 'Wyćepano historyja sprowjyń',
'revdelete-content'           => 'zawartość',
'revdelete-summary'           => 'uopis pomjyńań',
'revdelete-uname'             => 'mjano užytkowńika',
'revdelete-restricted'        => 'naštaluj uograničyńo do administratorůw',
'revdelete-unrestricted'      => 'wycofej uograničyńo do administratorůw',
'revdelete-hid'               => 'schrůń $1',
'revdelete-unhid'             => 'ńy schrůńyj $1',
'revdelete-log-message'       => '$1 - $2 {{PLURAL:$2|wersyjo|wersyji|wersjůw}}',
'logdelete-log-message'       => '$1 - $2 {{PLURAL:$2|zdařyńe|zdařyńa|zdařyń}}',

# Suppression log
'suppressionlog'     => 'Log schrůńyńć',
'suppressionlogtext' => 'Půńižej je lista nojnowšych wyćepań i zawarć s uwzglyndńyńym treśći schrůńůnej do admińistratorůw. Coby přejřeć lista aktualnych banůw i zawarć, uobejřij [[Special:Ipblocklist|IP block list]].',

# History merging
'mergehistory'                     => 'Pouůnč historja půmjyńań zajtůw',
'mergehistory-header'              => 'Ta zajta dozwolo pouůnčyć historje půmjyńań jydnej zajty s inkšům, nowša zajtą. Dej pozůr, coby sprawjyńy douo ćůnguo historja půmjyńań zajty w jei historii.',
'mergehistory-box'                 => 'Pouůnč historja sprowjyń dwůch zajtůw:',
'mergehistory-from'                => 'Zajta zdřůduowo:',
'mergehistory-into'                => 'Zajta docelowo:',
'mergehistory-list'                => 'Histroja půmjyńań idźe pouůnčyć',
'mergehistory-merge'               => 'Nastympujůnce půmjyńyńo zajty [[:$1]] idźe scalić s [[:$2]]. Uoznač w kolůmńy kropkům kero zmjano, uůnčńy s wčeśńijšymi, mo być scalůno. Uožyće cajchůndzkůw uod nawigacyji kasuje wybůr we kolůmńy.',
'mergehistory-go'                  => 'Pokož půmjyńańo kere idźe scalić',
'mergehistory-submit'              => 'Scal historja půmjyńań',
'mergehistory-empty'               => 'Ńy ma historji zmjan do scalyńa.',
'mergehistory-success'             => '$3 {{PLURAL:$3|pomjyńańe|pomjyńańa|pomjyńań}} w [[:$1]] ze sukcesym zostouo scalonych ze [[:$2]].',
'mergehistory-fail'                => 'Ńy idźe scalić historii půmjyńań. Zmjyń štalowańo parametrůw tej uoperacyji.',
'mergehistory-no-source'           => 'Ńy ma sam zajty zdřůduowy $1.',
'mergehistory-no-destination'      => 'Ńy ma sam zajty docelowyj $1.',
'mergehistory-invalid-source'      => 'Zajta zdřůduowo muśi mjeć poprowne mjano.',
'mergehistory-invalid-destination' => 'Zajta docelowo muśi mjeć poprowne mjano.',
'mergehistory-autocomment'         => 'Historia [[:$1]] scalono ze [[:$2]]',
'mergehistory-comment'             => 'Historja [[:$1]] pouůnčůno ze [[:$2]]: $3',

# Merge log
'mergelog'           => 'Pouůnčůne',
'pagemerge-logentry' => 'Pouůnčůno [[$1]] ze [[$2]] (historja pomjyńań aže do $3)',
'revertmerge'        => 'Uoduůnč (rozdźel)',
'mergelogpagetext'   => 'Půńižej znojdowo śe lista uostatńich pouůnčyń historii půmjyńań zajtůw.',

# Diffs
'history-title'           => 'Historyja sprowjyń "$1"',
'difference'              => '(Růžńice mjyndzy škryflańami)',
'lineno'                  => 'Linia $1:',
'compareselectedversions' => 'porůwnej wybrane wersyje',
'editundo'                => 'cofej',
'diff-multi'              => '(Ńy pokazano {{PLURAL:$1|jydnej wersyji pośredńij|$1 wersyji pośredńich}}.)',

# Search results
'searchresults'             => 'Wyniki šnupańo',
'searchresulttext'          => 'Coby dowjydźeć śe wjyncyj uo šnupańu w {{GRAMMAR:D.lp|{{SITENAME}}}}, uobejři [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'            => 'Wyniki šnupańo za "[[:$1]]"',
'searchsubtitleinvalid'     => 'Do zapytańo "$1"',
'noexactmatch'              => "'''Niy ma sam zajtów nazwanych \"\$1\".'''
Možyš [[:\$1|tako utwořyć]], abo sprůbować pounygo šnupańa.",
'noexactmatch-nocreate'     => "'''Ńy ma sam zajty uo mjanie \"\$1\".'''",
'toomanymatches'            => 'Za dužo elymyntůw kere pasujům do wzorca, wćep inkše zapytańy',
'titlematches'              => 'Znejdźono we titlach:',
'notitlematches'            => 'Ńy znejdźono we titlach',
'textmatches'               => 'Znejdźono na zajtach:',
'notextmatches'             => 'Ńy znejdźono we tekście zajtůw',
'prevn'                     => 'popředńe $1',
'nextn'                     => 'nastympne $1',
'viewprevnext'              => 'Uobejřij ($1) ($2) ($3)',
'search-result-size'        => '$1 ({{PLURAL:$2|1 suowo|$2 suowa|$2 suůw}})',
'search-result-score'       => 'Akuratność: $1%',
'search-redirect'           => '(překerowańy $1)',
'search-section'            => '(tajla $1)',
'search-suggest'            => 'Mioužeś na myśli: $1 ?',
'search-interwiki-caption'  => 'Śostřane projekty',
'search-interwiki-default'  => '$1 wyńiki:',
'search-interwiki-more'     => '(wjyncy)',
'search-mwsuggest-enabled'  => 'ze sůgestjami',
'search-mwsuggest-disabled' => 'ńy ma sůgestyji',
'search-relatedarticle'     => 'Podobne',
'mwsuggest-disable'         => 'Wyuůnč sůgestyje AJAX',
'searchrelated'             => 'podobne',
'searchall'                 => 'wšyskie',
'showingresults'            => "Oto lista na kery jest {{PLURAL:$1|'''1''' wynik|'''$1''' wynikůw}}, počynojůnc uod nůmeru '''$2'''.",
'showingresultsnum'         => "Oto lista na kery jest {{PLURAL:$3|'''1''' wynik|'''$3''' wynikůw}}, počynojůnc uod nůmeru '''$2'''.",
'showingresultstotal'       => "Půńižej znojdujům śe wyńiki šnupańo '''$1 - $2''' ze '''$3'''",
'nonefound'                 => "'''Uwaga''': brak rezultatůw šnupańo čynsto wystympuje bez tůž, co šnupańy je za čynsto užywanymi suowůma, jak \"je\" abo \"ńy\", kere ńy sům indeksowane, abo w raźe wklupańo wjyncyj jak jydnygo suowa uoroz we pole \"šnupej\" (na li'śće znejdźůnych zajtůn sům ino te, do kerych pasujům wšyjstke suowa užyte we šnupańu).",
'powersearch'               => 'Šnupańe zaawansowane',
'powersearch-legend'        => 'Šnupańe zaawansowane',
'powersearchtext'           => 'Šnupej w přestřyńach mjan:<br />$1<br />$2 Pokož překerowańa<br />Šukany tekst $3 $9',
'search-external'           => 'Šnupańy zewnyntřne',
'searchdisabled'            => 'Šnupańy we {{GRAMMAR:MS.lp|{{SITENAME}}}} zostouo zawarte. Zańim go zouůnčům, možeš sprůbować šnupańo bez Google. Ino zauwaž, co informacyje uo treśći {{GRAMMAR:MS.lp|{{SITENAME}}}} můgům być we Google ńyakuratne.',

# Preferences page
'preferences'              => 'Preferencyje',
'mypreferences'            => 'Moje preferéncyje',
'prefs-edits'              => 'Ličba sprowjyń:',
'prefsnologin'             => 'Ńy ježeś zalůgowany',
'prefsnologintext'         => 'Muśiš śe [[Special:Userlogin|zalůgować]] coby štalować swoje preferyncyje.',
'prefsreset'               => 'Preferyncyje důmyślne zostouy uodtwořůne.',
'qbsettings'               => 'Gurt šybkigo dostympu',
'qbsettings-none'          => 'Brak',
'qbsettings-fixedleft'     => 'Stouy, s lewyj',
'qbsettings-fixedright'    => 'Stouy, s prawyj',
'qbsettings-floatingleft'  => 'Uůnošůncy śe, s lewyj',
'qbsettings-floatingright' => 'Uůnošůncy śe, s prawyj',
'changepassword'           => 'Zmjana hasua',
'skin'                     => 'Skůrka',
'math'                     => 'Wzory',
'dateformat'               => 'Format daty',
'datedefault'              => 'Důmyślny',
'datetime'                 => 'Data i čos',
'math_failure'             => 'Parser ńy můg rozpoznać',
'math_unknown_error'       => 'ńyznany feler',
'math_unknown_function'    => 'ńyznano funkcyja',
'math_lexing_error'        => 'feler leksera',
'math_syntax_error'        => 'felerno skuůadńa',
'math_image_error'         => 'kůnwersyjo do formatu PNG ńy powjodua śe; uobadej, čy poprawńy zainštalowane sům lotex, dvips, gs i convert',
'math_bad_tmpdir'          => 'Ńy idźe utwořić abo naškryflać w tymčasowym katalůgu do wzorůw matymatyčnych',
'math_bad_output'          => 'Ńy idźe utwořić abo naškryflać we wyjśćowym katalůgu do wzorůw matymatyčnych',
'math_notexvc'             => 'Ńy ma sam texvc; zapoznej śe z math/README w celu kůnfiguracyji.',
'prefs-personal'           => 'Dane užytkowńika',
'prefs-rc'                 => 'Pomjyńane na uostatku',
'prefs-watchlist'          => 'Pozorlista',
'prefs-watchlist-days'     => 'Ličba dńi widočnych na liśće artikli, na kere dowoš pozůr:',
'prefs-watchlist-edits'    => 'Ličba půmjyńań pokazywanych we rozšeřůnej liśće artiklůw, na kere dowoš pozůr:',
'prefs-misc'               => 'Roztomajte',
'saveprefs'                => 'Naškryflej',
'resetprefs'               => 'Preferencyje důmyślne',
'oldpassword'              => 'Stare hasuo',
'newpassword'              => 'Nowe hasuo',
'retypenew'                => 'Naškryflej ješče roz nowe hasuo:',
'textboxsize'              => 'Sprowjańy',
'rows'                     => 'Wjerše:',
'columns'                  => 'Kůlumny:',
'searchresultshead'        => 'Šnupańe',
'resultsperpage'           => 'Ličba wyńikůw na zajće',
'contextlines'             => 'Pjyrwše wjerše artikla',
'contextchars'             => 'Buchštaby kůnteksta we lińijce',
'stub-threshold'           => 'Maksymalny rozmjar artikla uoznačanygo kej <a href="#" class="stub">stub (zalůnžek)</a>',
'recentchangesdays'        => 'Ličba dńi do pokazańo we půmjyńanych na uostatku:',
'recentchangescount'       => 'Ličba pozycyji na liśće půmjyńanych na uostatku :',
'savedprefs'               => 'Twoje štalowańo we preferyncyjach zostouy naškryflane.',
'timezonelegend'           => 'Strefa časowo',
'timezonetext'             => 'Podej uo wjela godźin růžńi śe Twůj čas uod ůńiwersalnygo (UTC).',
'localtime'                => 'Twůj čas:',
'timezoneoffset'           => 'Dyferencyjo ¹',
'servertime'               => 'Aktualny čas serwera',
'guesstimezone'            => 'Pobier z přeglůndarki',
'allowemail'               => 'Inkśi užytkowńicy můgům přesůuać mie e-brify',
'defaultns'                => 'Důmyślńy šnupej we nastympujůncych přestřyńach nazw:',
'default'                  => 'důmyślnje',
'files'                    => 'Pliki',

# User rights
'userrights'                       => 'Zařůndzańe prowami užytkowńikůw', # Not used as normal message but as header for the special page itself
'userrights-lookup-user'           => 'Zažůndzej prawami užytkownika',
'userrights-user-editname'         => 'Wklepej sam nazwa užytkowńika:',
'editusergroup'                    => 'Sprowjej grupy užytkowńika',
'editinguser'                      => "Zmjana uprawńyń užytkowńika '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'         => 'Sprowjej grupy užytkowńika',
'saveusergroups'                   => 'Naškryflej',
'userrights-groupsmember'          => 'Noležy do:',
'userrights-groupsremovable'       => 'Idźe go wyćepać ze grup:',
'userrights-groupsavailable'       => 'Dostympne grupy :',
'userrights-groups-help'           => 'Možeš půmjyńać přinoležność tygo užytkowńika do podanych grup.
Zaznačůne pole uoznača přinoležność užytkowńika do danej grupy.
Ńy zaznačůne pole uoznača, aže užytkowńik ńy noležy do danej grupy.',
'userrights-reason'                => 'S kuli čego je půmjeńeńe:',
'userrights-available-none'        => 'Ńy možeš půmjyńać přinoležnośći do grup.',
'userrights-available-add'         => 'Možeš dodać jakigokolwjek užytkowńika do {{PLURAL:$2|grupy|grup}}: $1.',
'userrights-available-remove'      => 'Možeš wyćepać jakigokolwjek užytkowńika ze {{PLURAL:$2|grupy|grup}}: $1.',
'userrights-available-add-self'    => 'Ńy možeš dodać śebje do {{PLURAL:$2|grupy|grup}}: $1.',
'userrights-available-remove-self' => 'Ńy možeš wyćepać śebje ze {{PLURAL:$2|grupy|grup}}: $1.',
'userrights-no-interwiki'          => 'Ńy moš dostympu do sprowjańo uprawńyń.',
'userrights-nodatabase'            => 'Baza danych $1 ńy istńije abo ńy je lokalno.',
'userrights-nologin'               => 'Muśyš [[Special:Userlogin|zalůgować śe]] na kůnto admińistratora, coby nadować uprawńyńo užytkowńikům.',
'userrights-notallowed'            => 'Ńy moš dostympu do nadawańo uprawńyń užytkowńikům.',
'userrights-changeable-col'        => 'Grupy, kere možeš wybrać',
'userrights-unchangeable-col'      => 'Grupy, kerych ńy možeš wybrać',

# Groups
'group'               => 'Grupa:',
'group-autoconfirmed' => 'Autůmatyčńy zatwjerdzyńi užytkowńiki',
'group-bot'           => 'Boty',
'group-sysop'         => 'Admini',
'group-bureaucrat'    => 'Bjurokraty',
'group-suppress'      => 'Uoversajteřy',
'group-all'           => '(wšyscy)',

'group-autoconfirmed-member' => 'Autůmatyčńy zatwjerdzůny užytkowńik',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Admin',
'group-bureaucrat-member'    => 'Bjurokrata',
'group-suppress-member'      => 'Ouversajter',

'grouppage-autoconfirmed' => '{{ns:project}}:Autůmatyčńy zatwjerdzyńi užytkowńiki',
'grouppage-bot'           => '{{ns:project}}:Boty',
'grouppage-sysop'         => '{{ns:project}}:Administratořy',
'grouppage-bureaucrat'    => '{{ns:project}}:Bjurokraty',
'grouppage-suppress'      => '{{ns:project}}:Ouversajteřy',

# User rights log
'rightslog'      => 'Uprawńyńa',
'rightslogtext'  => 'Rejer půmjyńań uprawńyń užytkowńikůw.',
'rightslogentry' => 'půmjyńiu/a uprawńyńo užytkowńika $1 ($2 → $3)',
'rightsnone'     => 'podstawowo',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|pomjyńańe|pomjyńańa|pomjyńań}}',
'recentchanges'                     => 'Pomjéńane na űostatku',
'recentchangestext'                 => 'Ta zajta předstawjo historja uostatńich půmjyńań na tej wiki',
'recentchanges-feed-description'    => 'Dowej pozůr na pomjyńane na uostatku na tyi wiki .',
'rcnote'                            => "Půńižej {{PLURAL:$1|pokozano uostatńo zmjano dokůnano|pokazano uostatńy '''$1''' zmjany naškryflane|pokozano uostatńich '''$1''' škryflań zrobjůnych}} bez {{PLURAL:$2|ostatńi dźyń|ostatńich '''$2''' dńi}}, začynojůnc uod $3.",
'rcnotefrom'                        => 'Půńižej pokazano půmjyńańo zrobjůne po <b>$2</b> (ńy wjyncyj jak <b>$1</b> pozycji).',
'rclistfrom'                        => 'Pokož půmjyńańo uod $1',
'rcshowhideminor'                   => '$1 drobne pomjyńańa',
'rcshowhidebots'                    => '$1 boty',
'rcshowhideliu'                     => '$1 zalůgowanych užytkowńikůw',
'rcshowhideanons'                   => '$1 anůńimowych',
'rcshowhidepatr'                    => '$1 na kere dowomy pozůr',
'rcshowhidemine'                    => '$1 beze mie sprowjůne',
'rclinks'                           => 'Pokož uostatńe $1 sprowjyń bez uostatńe $2 dńi.<br />$3',
'diff'                              => 'dyf',
'hist'                              => 'hist',
'hide'                              => 'schrůń',
'show'                              => 'Pokoż',
'minoreditletter'                   => 'd',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|dowajůncy pozůr užytkowńik|dowajůncych pozůr užytkowńikůw}}]',
'rc_categories'                     => 'Uůgrańič do katygorii (oddźelej za půmocům "|")',
'rc_categories_any'                 => 'Wšyskie',
'newsectionsummary'                 => '/* $1 */ nowo tajla',

# Recent changes linked
'recentchangeslinked'          => 'Pomjéńane w adrésowanych',
'recentchangeslinked-title'    => 'Pomjyńyńo w adrésowanych s "$1"',
'recentchangeslinked-noresult' => 'Nikt nic niy pomjyńoł w dolinkowanych bez čas uo kery žeś pytou.',
'recentchangeslinked-summary'  => "To je ekstra zajta, na kerej možeš uobočyć zmjany w artiklach adresowanych do podanyj zajty.
Jak podano zajta je katygoriům, wyśwjetlane sům uostatńy zmjany we wšyjstkych zajtach noležůncych do tej katygorii.
Artikle na [[Special:Watchlist|pozorliśće]] sům '''rube'''.",
'recentchangeslinked-page'     => 'Mjano zajty:',
'recentchangeslinked-to'       => 'Pokož pomjyńańa na zajtach adresowanych do podany zajty',

# Upload
'upload'                      => 'Wćepnij plik',
'uploadbtn'                   => 'Wćepnij sam plik',
'reupload'                    => 'Wćepnij zaś',
'reuploaddesc'                => 'Nazod do formulařa uod wćepywańo.',
'uploadnologin'               => 'Ńy jest žeś zalogůwany',
'uploadnologintext'           => 'Muśyš śe [[Special:Userlogin|zalůgować]] ńim wćepńeš pliki.',
'upload_directory_read_only'  => 'Serwer ńy može škryflać do katalůgu ($1) kery je přeznačůny na wćepywane pliki.',
'uploaderror'                 => 'Feler při wćepywańu',
'uploadtext'                  => "Ůžyj formulařa půńižej do wćepywańo plikůw.
Jak chceš přejřeć dotychčas wćepane pliki, abo w ńich šnupać, přeńdź do [[Special:Imagelist|listy douůnčůnych plikůw]]. Wšyjstke wćepańo uodnotowane sům we [[Special:Log/upload|rejeře přesůuanych plikůw]].

Plik pojawi śe na zajće, jak užyješ linka wedle jydnygo s nastympujůncych wzorůw:
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Plik.jpg]]</nowiki>''',
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Plik.png|tekst uopisu]]</nowiki>''' abo
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Plik.ogg]]</nowiki>''' coby uzyskać bezpośredńi link do plika.",
'upload-permitted'            => 'Dopuščalne formaty plikůw: $1.',
'upload-preferred'            => 'Zalecane formaty plikůw: $1.',
'upload-prohibited'           => 'Zakozane formaty plikůw: $1.',
'uploadlog'                   => 'Wykoz wćepywań',
'uploadlogpage'               => 'Wćepane sam',
'uploadlogpagetext'           => 'Půńižej znojdowo śe lista plikůw wćepanych na uostatku.',
'filename'                    => 'Mjano pliku',
'filedesc'                    => 'Uopis',
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
'filetype-unwanted-type'      => "'''\".\$1\"''' ńy je zalecanym typym plika. Preferowane sům pliki we formatach \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' je ńydozwolůnym typym plika. Dostympne sům pliki we formatach \$2.",
'filetype-missing'            => 'Plik ńy mo rozšyřyńo (np. ".jpg").',
'large-file'                  => 'Zaleco śe coby rozmjar plika ńy bůu wjynkšy jak $1 bajtůw. Tyn plik mo rozmjar $2 bajtůw.',
'largefileserver'             => 'Plik je wjynkšy ńiž maksymalny dozwolůny rozmjar.',
'emptyfile'                   => 'Wćepywany plik cheba je pusty. Može to być bez tůž, co žeś wklepou zuo buchštaba w jygo mjańe. Sprowdź, čy mjano kere žeś wklepou je poprawne.',
'fileexists'                  => 'Plik uo takym mjańe juž je sam wćepany! Wćepańe nowyj grafiki ńyodwracalńe wyćepńe ta kero sam juž je wćepano ($1)! Sprowdź čy žeś je pewny co chceš tyn plik sam wćepać.',
'filepageexists'              => 'Je juž sam zajta uopisu tygo plika utwořůno <strong><tt>$1</tt></strong>, ino ńy ma terozki plika uo tym mjańy. Informacyje uo pliku, kere žeś wćepou, ńy bydům pokozane na zajće uopisu. Jakbyś chćou coby te informacyje zostouy pokozane, muśyš jeich sprowjać rynčńy.',
'fileexists-extension'        => 'Plik uo podobnym mjańe juž sam je:<br />
Mjano wćepywanygo plika: <strong><tt>$1</tt></strong><br />
Mjano plika kery juž sam je: <strong><tt>$2</tt></strong><br />
Wybjer proša inkše mjano.',
'fileexists-thumb'            => "<center>'''Istniejůnco grafika'''</center>",
'fileexists-thumbnail-yes'    => 'Zdowo śe co tyn plik je půmńijšůnům wersyjom grafiki <i>(mińjaturkom)</i>. Uobejřij plik: <strong><tt>$1</tt></strong>.<br />
Jak to je ta sama grafika, ino wjelgo, ńy muśiš juž jei sam zaś wćepywać.',
'file-thumbnail-no'           => 'Mjano plika začyno śe uod <strong><tt>$1</tt></strong>. Zdowo śe, co to je půmńijšůna grafika <i>(mińaturka)</i>.
Jak moš ta grafika we peunym rozmjaře - wćepej ja sam, abo bydźeš muśou zmjyńić mjano wćepywanygo terozki plika.',
'fileexists-forbidden'        => 'Plik uo takym mjańy juž sům můmy! Idź nazod i wćepej tyn plik pod inkšym mjanym. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Plik uo takym mjańe juž sam momy! Idź nazod i wćepej tyn plik pod inkšym mjanym. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Wćepańe plika udouo śe',
'uploadwarning'               => 'Uostřežyńe uo wćepywańu',
'savefile'                    => 'Naškryflej plik',
'uploadedimage'               => 'wćepano "[[$1]]"',
'overwroteimage'              => 'wćepano nowšo wersyjo "[[$1]]"',
'uploaddisabled'              => 'Wćepywanie sam plikůw je zawarte',
'uploaddisabledtext'          => 'Funkcjo wćepywańo plikůw zostoua zawarto.',
'uploadscripted'              => 'Tyn plik zawjyro kod HTML abo skrypt kery može zostać felerńe zinterpretowany bez přyglůndarka internetowo.',
'uploadcorrupt'               => 'Tyn plik je uškodzůny abo mo felerne rozšeřyńy. Proša sprawdźić plik i wćepać sam poprawno wersja.',
'uploadvirus'                 => 'W tym pliku je wirus! Ščygůuy: $1',
'sourcefilename'              => 'Mjano oryginalne:',
'destfilename'                => 'Mjano docylowe:',
'upload-maxfilesize'          => 'Maksymalny rozmior plika: $1',
'watchthisupload'             => 'Dowej pozůr na ta zajta',
'filewasdeleted'              => 'Plik uo takym mjańy juž bůu sam wćepany, ale zostou wyćepńjynty. Ńim wćepńeš go zaś, sprowdź $1.',
'upload-wasdeleted'           => "'''Uostřežyńy: Wćepuješ sam plik, kery bůu popředńo wyćepany.'''

Zastanůw śe, čy powinno śe go sam wćepywać.
Rejer wyćepań tygo plika je podany půńižej, cobyś miou wygoda:",
'filename-bad-prefix'         => 'Mjano plika, kery wćepuješ, začyno śe uod <strong>"$1"</strong> &ndash; je to mjano nojčynśćy připisywane autůmatyčńy bez cyfrowe fotoaparaty, uůno ńy dowo žodnych informacyji uo zawartośći plika. Prošymy cobyś nadou plikowi inkše, lepij zrozůmjaue mjano.',

'upload-proto-error'      => 'Ńyprowiduowy protokůu',
'upload-proto-error-text' => 'Zdalne přesůuańy plikůw wymago podańo adresu URL kery začyno śe na <code>http://</code> abo <code>ftp://</code>.',
'upload-file-error'       => 'Wewnyntřny feler',
'upload-file-error-text'  => 'Wystůmpiu wewnyntřny feler kej průbowano naškryflać tymčasowy plik na serweře. Skůntaktuj śe s admińistratorym systemu',
'upload-misc-error'       => 'Ńyznany feler při wćepywańu',
'upload-misc-error-text'  => 'Zašou ńyznany feler při wćepywańu. Sprawdź poša čy podany URL je poprawny i dostympny, a potym poprůbuj zaś. Jak problym bydźe śe powtařou dalej dej znoć ku admińistratorowi systymu.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL je ńyosůngalny',
'upload-curl-error6-text'  => 'Podany URL je ńyosiůngalny. Proša, sprowdź dokuadńy čy podany URL je prawidouwy i čy dano zajta dźauo.',
'upload-curl-error28'      => 'Překročůny čas kery bůu na wćepywańe',
'upload-curl-error28-text' => 'Zajta uodpowjado za powoli. Proša, sprawdź čy zajta dźauo, uodčekej pora minut i sprůbuj zaś. Možeš tyž sprůbować wončas kej zajta bydźe mńij uobćůnžůno.',

'license'            => 'Licencyjo:',
'nolicense'          => 'Ńy wybrano (naškryflej rynčńy!)',
'license-nopreview'  => '(Podglůnd ńydostympny)',
'upload_source_url'  => ' (poprowny, publičńy dostympny URL)',
'upload_source_file' => ' (plik na twojym komputře)',

# Special:Imagelist
'imagelist-summary'     => 'To je ekstra zajta na kery sům pokazywane wšyske pliki wćepane na serwer. Důmyślńy na wiyrchu listy wyśwjetlajům śe pliki wćepane na uostatku. Coby půmjyńić sposůb sortowańo, klikńij na naguůwek kolůmny.',
'imagelist_search_for'  => 'Šnupej za grafikům uo mjańe:',
'imgdesc'               => 'uopis',
'imgfile'               => 'plik',
'imagelist'             => 'Lista plikůw',
'imagelist_date'        => 'Data',
'imagelist_name'        => 'Mjano',
'imagelist_user'        => 'Užytkowńik',
'imagelist_size'        => 'Rozmior (bajty)',
'imagelist_description' => 'Uopis',

# Image description page
'filehist'                  => 'Historjo pliku',
'filehist-help'             => 'Klikńij na data/čas, coby uobejřeć plik taki jak wtedy wyglůndou.',
'filehist-deleteall'        => 'wyćep wšyskie',
'filehist-deleteone'        => 'wyćep ta wersyjo',
'filehist-revert'           => 'cofej',
'filehist-current'          => 'aktůalny',
'filehist-datetime'         => 'Data/čas',
'filehist-user'             => 'Užytkowńyk',
'filehist-dimensions'       => 'Wymiary',
'filehist-filesize'         => 'Rozmior plika',
'filehist-comment'          => 'Komyntorz',
'imagelinks'                => 'Co sam linkuje',
'linkstoimage'              => 'Nastympujůnce zajty sům adrésowane do tygo plika:',
'nolinkstoimage'            => 'Žodno zajta ńy je adrésowano do tygo plika.',
'sharedupload'              => 'Tyn plik je wćepńjynty na wspůlny serwer i inkše projekty tyž můgům go užywać.',
'shareduploadwiki'          => 'Wjyncyj informacyji znojdźeš we $1',
'shareduploadwiki-desc'     => 'Uopis kery je na $1 možeš uobejřeć půńižej.',
'shareduploadwiki-linktext' => 'zajte uopisu grafiki',
'noimage'                   => 'Ńjy ma sam plika uo takiej nazwje. Možeš go sam $1.',
'noimage-linktext'          => 'wćepać',
'uploadnewversion-linktext' => 'Wćepńij nowšo wersyjo tygo plika',
'imagepage-searchdupe'      => 'šnupej za plikůma kere śe powtařajům',

# File reversion
'filerevert'                => 'Přiwracańy $1',
'filerevert-legend'         => 'Přiwracańy poprzedńy wersje plika',
'filerevert-intro'          => '<span class="plainlinks">Zamjeřoš přiwrůćić \'\'\'[[Media:$1|$1]]\'\'\' do wersje z [$4 $3, $2].</span>',
'filerevert-comment'        => 'Kůmyntorz:',
'filerevert-defaultcomment' => 'Přiwrůcůno wersyjo z $2, $1',
'filerevert-submit'         => 'Přiwrůć',
'filerevert-success'        => '<span class="plainlinks">Plik \'\'\'[[Media:$1|$1]]\'\'\' zostou cofniynty do [wersyje $4 ze $3, $2].</span>',
'filerevert-badversion'     => 'Ńy ma sam popředńij lokalnyj wersyji tygo plika s podanům datům.',

# File deletion
'filedelete'                  => 'Wyćepańe $1',
'filedelete-legend'           => 'Wyćep plik',
'filedelete-intro'            => "Wyćepuješ '''[[Media:$1|$1]]'''.",
'filedelete-intro-old'        => '<span class="plainlinks">Wyćepuješ wersyja plika \'\'\'[[Media:$1|$1]]\'\'\' s datům [$4 $3, $2].</span>',
'filedelete-comment'          => 'Čymu chceš wyćepnůńć:',
'filedelete-submit'           => 'Wyćep',
'filedelete-success'          => "Wyćepano plik '''$1'''.",
'filedelete-success-old'      => '<span class="plainlinks">Wyćepano plik \'\'\'[[Media:$1|$1]]\'\'\' we wersyje ze $3, $2.</span>',
'filedelete-nofile'           => "Plika '''$1''' ńy ma we {{GRAMMAR:MS.pl|{{SITENAME}}}}.",
'filedelete-nofile-old'       => "Ńy ma sam zarchiwizowanyj wersje '''$1''' o atrybutach jake žeś podou.",
'filedelete-iscurrent'        => 'Průbuješ wyćepać nojnowšo wersyjo tygo plika. Muśyš wpjyrw přiwrůćić staršo wersyjo.',
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
'unusedtemplatestext' => 'Půńižej znojdowo śe lista šablůnůw kerych inkše zajty ńy užywajům',
'unusedtemplateswlh'  => 'ku adresatu',

# Random page
'randompage'         => 'Losuj zajta',
'randompage-nopages' => 'W tej přestřyńi nazw ńy ma žodnych zajtůw.',

# Random redirect
'randomredirect'         => 'Losowe překerowańy',
'randomredirect-nopages' => 'W tej přestřyńi nazw ńy ma překerowań.',

# Statistics
'statistics'             => 'Statystyka',
'sitestats'              => 'Statystyki {{SITENAME}}',
'userstats'              => 'Statystyka užytkowńikůw',
'sitestatstext'          => "We baźe danych je cuzamyn '''\$1''' {{PLURAL:\$1|zajta|zajty|zajtůw}}.

Ta ličba uwzglyndńo zajty godki, zajty na tymat {{GRAMMAR:D.lp|{{SITENAME}}}}, zajty prowizoryčne (\"stuby\"), zajty překerowujůnce, a inkše, kere trudno uwažać za artikle. Wůuůnčajůnc powyžše, je prawdopodobńy '''\$2''' {{PLURAL:\$2|zajta, kero idźe uwažać za artikel|zajty, kere idźe uwažać za artikle|zajtůw, kere idźe uwažać za artikle}}.

Wćepano sam \$8 {{PLURAL:\$8|plik|pliki|plikůw}}.

Uod uruchůmjyńo {{GRAMMAR:D.lp|{{SITENAME}}}} {{PLURAL:\$3|'''1''' raz filowano w zajty|'''\$3''' razy filowano w zajty|bůuo '''\$3''' filowań w zajty}} i wykůnano '''\$4''' {{PLURAL:\$4|sprowjyńy|sprowjyńa|sprowjyń}}. To dowo średńo '''\$5''' {{PLURAL:\$5|sprowjyńy|sprowjyńa|sprowjyń}} na zajta i '''\$6''' {{PLURAL:\$4|filowańy|filowańa|filowań}} na sprawjyńy.

Duůgość [http://meta.wikymedja.org/wiki/Help:Job_queue kolejki zadań] je '''\$7'''.",
'userstatstext'          => "Je sam {{PLURAL:$1|'''1''' zarejerowany užytkowńik|'''$1''' zarejerowanych užytkowńikůw}}. {{PLURAL:$1|Užytkowńik tyn|Spośrůd ńich '''$2''' ('''$4%''')}} mo status $5.",
'statistics-mostpopular' => 'Zajty we kere nojčyńśći sam filujom',

'disambiguations'      => 'Zajty ujydnoznačńajůnce',
'disambiguationspage'  => '{{ns:template}}:disambig',
'disambiguations-text' => "Artikle půńižej uodwouujům śe do '''zajtůw ujydnoznačńajůncych''', a powinny uodwouywać śe bezpośredńo do hasua kere je zwjůnzane ze treśćům artikla. Zajta uznawano je za ujydnoznačńajůnco kej zawiyro šablůn uokreślůny we [[MediaWiki:disambiguationspage]].",

'doubleredirects'     => 'Podwůjne překierowańa',
'doubleredirectstext' => 'Na tyi liśće mogům znojdować śe překerowańo pozorne. Uoznača to, aže půńižej pjyrwšej lińii artikla, zawjerajůncyj "#REDIRECT ...", može znojdować śe dodotkowy tekst. Koždy wjerš listy zawjero uodwouańo do pjyrwšygo i drůgygo překerowańo a pjyrwšom lińjům tekstu drůgygo překerowańo. Uůmožliwjo to na ogůu uodnaleźyńy wuaśćiwygo artikla, do kerygo powinno śe překerowywać.',

'brokenredirects'        => 'Zuomane překerowańa',
'brokenredirectstext'    => 'Překerowańo půńižej wskazujům na artikle kerych sam ńy ma.',
'brokenredirects-edit'   => '(sprowjéj)',
'brokenredirects-delete' => '(wyćep)',

'withoutinterwiki'        => 'Artikle bez interwiki',
'withoutinterwiki-header' => 'Zajty půńižej ńy majům uodwouań do wersjůw w inkšych godkach.',
'withoutinterwiki-submit' => 'Pokož',

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
'lonelypagestext'         => 'Do zajtůw půńižej ńy adresuje žodno inkšo zajta we {{GRAMMAR:MS.lp|{{SITENAME}}}}.',
'uncategorizedpages'      => 'Zajty bez kategoryje',
'uncategorizedcategories' => 'Kategoryje bez kategoriůw',
'uncategorizedimages'     => 'Pliki bez kategoriůw',
'uncategorizedtemplates'  => 'Šablôny bez kategorii',
'unusedcategories'        => 'Ńyužywane kategoryje',
'unusedimages'            => 'Ńyužywane pliki',
'popularpages'            => 'Zajty we kere nojčynśćej sam filujům',
'wantedcategories'        => 'Potřebne katygoryje',
'wantedpages'             => 'Nojpotřebńijše zajty',
'mostlinked'              => 'Nojčyńśćej adrésowane',
'mostlinkedcategories'    => 'Kategoryje we kerych je nojwjyncyi artikli',
'mostlinkedtemplates'     => 'Nojčyńśćej adrésowane šablôny',
'mostcategories'          => 'Zajty kere majům nojwiyncyi kategoriůw',
'mostimages'              => 'Nojčyńśćij adresowane pliki',
'mostrevisions'           => 'Nojčyńśćej sprowjane artikle',
'prefixindex'             => 'Wšyskie zajty wedle prefiksa',
'shortpages'              => 'Nojkrůtše zajty',
'longpages'               => 'Dugje artikle',
'deadendpages'            => 'Artikle bez linkůw',
'deadendpagestext'        => 'Zajty wymjyńůne půńižej ńy majům uodnośńikůw do žodnych inkšych zajtůw kere sům na tej wiki.',
'protectedpages'          => 'Zawarte zajty',
'protectedpages-indef'    => 'Yno zabezpječyńo ńyokreślůne',
'protectedpagestext'      => 'Zajty wymjyńůne půńižej sům zawarte uod prećepywańo i sprowjańo.',
'protectedpagesempty'     => 'Žodno zajta ńy je terozki zawarto s podanymi parametrami.',
'protectedtitles'         => 'Zawarte mjana artikli',
'protectedtitlestext'     => 'Ůtwořyńy artikli uo nastympujůncych mjanach je zawarte',
'protectedtitlesempty'    => 'Do tych štalowań utwořyńy artikla uo dowolnym mjańy ńy je zawarte',
'listusers'               => 'Lista užytkowńikůw',
'specialpages'            => 'Extra zajty',
'spheading'               => 'Extra zajty do wšyjstkych užytkowńikůw',
'restrictedpheading'      => 'Extra zajty ze ůgrańičůnym dostympym',
'newpages'                => 'Nowe zajty',
'newpages-username'       => 'Mjano užytkowńika:',
'ancientpages'            => 'Nojstarše artikle',
'move'                    => 'Přećep',
'movethispage'            => 'Přećepej ta zajta',
'unusedimagestext'        => 'Pamjyntej, proša, aže inkše witryny, np. projekty Wikimedja w inkšych godkach, můgům adresować do tych plikůw užywajůnc bezpośredńo URL. Bez tůž ńykere ze plikůw můgům sam być na tej liśće pokozane mimo, aže žodna zajta ńy adresuje do ńich.',
'unusedcategoriestext'    => 'Katygorje pokazane půńižej istńejům, choć ńy kořisto s ńich žadyn artikel ańi katygorja.',
'notargettitle'           => 'Wskazywano zajta ńy istńeje',
'notargettext'            => 'Ńy podano zajty abo užytkowńika, do kerych ta uoperacyjo mo być wykůnano.',
'pager-newer-n'           => '{{PLURAL:$1|1 nowšy|$1 nowše|$1 nowšych}}',
'pager-older-n'           => '{{PLURAL:$1|1 staršy|$1 starše|$1 staršych}}',
'suppress'                => 'Oversight',

# Book sources
'booksources'               => 'Kśąžki',
'booksources-search-legend' => 'Šnupej za zdřůduůma kśiůnžkowymi',
'booksources-go'            => 'Pokož',
'booksources-text'          => 'Půńižej znojdowo śe lista uodnośńikůw do inkšych witryn, kere pośredńičům we spředažy nowych i užywanych kśiąžek, a tyž můgům mjeć dalše informacyje uo pošukiwany bez ćebje kśůnžce',

# Special:Log
'specialloguserlabel'  => 'Užytkowńik:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'Rejery uoperacjůw',
'all-logs-page'        => 'Wšyjstkie uoperacyje',
'log-search-legend'    => 'Šnupej w rejeře',
'log-search-submit'    => 'Šnupej',
'alllogstext'          => 'Wspůlny rejer wšyjstkych typůw uoperacyji do {{GRAMMAR:D.lp|{{SITENAME}}}}.
Možeš zawyńźić ličba wyńikůw wybjerajůnc typ rejeru, mjano užytkowńika abo titel zajty',
'logempty'             => 'Ńy ma wpisůw we rejeře',
'log-title-wildcard'   => 'Šnupej za titlami kere začynojům śe uod tygo tekstu',

# Special:Allpages
'allpages'          => 'Wšyskie zajty',
'alphaindexline'    => 'úod $1 do $2',
'nextpage'          => 'Nostympno zajta ($1)',
'prevpage'          => 'Popředńo zajta ($1)',
'allpagesfrom'      => 'Zajty začynojůnce śe na:',
'allarticles'       => 'Wšyskie zajty',
'allinnamespace'    => 'Wšyjstke zajty (we přestřyńi mjan $1)',
'allnotinnamespace' => 'Wšyjstke zajty (ino bes přestřyńi mjan $1)',
'allpagesprev'      => 'Popředńo',
'allpagesnext'      => 'Nastympno',
'allpagessubmit'    => 'Pokož',
'allpagesprefix'    => 'Pokož artikle s prefiksym:',
'allpagesbadtitle'  => 'Podane mjano je felerne, zawjera prefiks mjyndzyprojektowy abo mjyndzyjynzykowy. Može uůne tyž zawjerać jako buchštaba abo inkše znaki, kerych ńy wolno užywać we titlach.',
'allpages-bad-ns'   => '{{GRAMMAR:MS.lp|{{SITENAME}}}} ńy mo přestřyńi mjan „$1”.',

# Special:Listusers
'listusersfrom'      => 'Pokaž užytkowńikůw začynojůnc uod:',
'listusers-submit'   => 'Pokož',
'listusers-noresult' => 'Ńy znejdźůno žodnygo užytkowńika.',

# Special:Listgrouprights
'listgrouprights'          => 'Uprawńyńo grup užytkowńikůw',
'listgrouprights-summary'  => 'Půńižy znojdowo śe spis grup užytkowńikůw zdefińowanych na tej wiki, s wyščygůlńyńym přidźelůnych im praw dostympu.',
'listgrouprights-group'    => 'Grupa',
'listgrouprights-rights'   => 'Uprawńyńo',
'listgrouprights-helppage' => 'Help:Uprawńyńo grup užytkowńikůw',

# E-mail user
'mailnologin'     => 'Brak adresu',
'mailnologintext' => 'Muśyš śe [[Special:Userlogin|zalůgować]] i mjeć wpisany aktualny adres e-brif w swojich [[Specjal:Preferences|preferyncyjach]], coby můc wysuać e-brif do inkšygo užytkowńika.',
'emailuser'       => 'Wyślij e-brif do tygo užytkowńika',
'emailpage'       => 'Wyślij e-brif do užytkowńika',
'emailpagetext'   => 'Půńižšy formulař pozwala na wysuańy jydnej wjadůmośći do užytkowńika pod warůnkym, aže wpisou uůn poprawny adres e-brif w swojich preferyncyjoch. Adres e-brif, kery zostou bez Ćebje wprowadzůny w Twoich preferyncyjoch ukaže śe w polu „Uod”, bez tůž uodbjorca bydźe můg Ci uodpowjydźeć.',
'usermailererror' => 'Moduu uobsůgi počty zwrůćiu feler:',
'defemailsubject' => 'Wjadůmość uod {{GRAMMAR:D.pl|{{SITENAME}}}}',
'noemailtitle'    => 'Brak adresu e-brif',
'noemailtext'     => 'Tyn užytkowńik ńy podou poprawnygo adresu e-brif, albo zadecydowou, co ńy chce uotřimywać wjadůmośći e-brif uod inkšych užytkowńikůw',
'emailfrom'       => 'Uod',
'emailto'         => 'Do',
'emailsubject'    => 'Tymat',
'emailmessage'    => 'Wjadůmość',
'emailsend'       => 'Wyślij',
'emailccme'       => 'Wyślij mi kopja moiy wjadomości.',
'emailccsubject'  => 'Kopja Twojej wjadůmośći do $1: $2',
'emailsent'       => 'Wjadůmość zostoua wysuano',
'emailsenttext'   => 'Twoja wjadůmość zostoua wysuano.',

# Watchlist
'watchlist'            => 'Pozorlista',
'mywatchlist'          => 'Mojo pozorlista',
'watchlistfor'         => "(dla užytkowńika '''$1''')",
'nowatchlist'          => 'Ńy ma žodnych pozycyji na liśće zajtůw, na kere dowoš pozůr.',
'watchlistanontext'    => '$1 coby uobejřeć abo sprowjać elymynty listy zajtůw, na kere dowoš pozůr',
'watchnologin'         => 'Ńy jest žeś zalůgowany',
'watchnologintext'     => 'Muśyš śe [[Special:Userlogin|zalůgować]] coby modyfikować lista zajtůw, na kere dowoš pozůr.',
'addedwatch'           => 'Dodane do pozorlisty',
'addedwatchtext'       => 'Zajta "[[:$1|$1]]" zostoua dodano do Twojyj [[{{ns:special}}:Watchlist|listy artikli, na kere dowoš pozůr]].
Na tyi liśće bydzieš miou rejer přišuych sprawjyń tyi zajty i jei zajty godki, a mjano zajty bydziesz miou škryflane \'\'\'tůustym\'\'\' na [[{{ns:special}}:Recentchanges|liśće pomjyńanych na ůostatku]],
cobyś miou wygoda w jei pomjyńańa filować. 

Kiejbyś chciou wyćepać ta zajta z Twojiy listy artikli, na kere dowoš pozůr, kliknij na zakuadka "skůńč dować pozůr".',
'removedwatch'         => 'Wyćepńjynte s pozorlisty',
'removedwatchtext'     => 'Artikel "[[:$1]]" zostou wyćepńjynty s pozorlisty.',
'watch'                => 'Dej pozor',
'watchthispage'        => 'Dej pozor',
'unwatch'              => 'Njy dowej pozoru',
'unwatchthispage'      => 'Přestoń dować pozůr',
'notanarticle'         => 'To ńy je artikel',
'notvisiblerev'        => 'Wersyja zostoua wyćepano',
'watchnochange'        => 'Žodno ze zajtůw, na kere dowoš pozůr, ńy bůua sprowjano w podanym uokreśe.',
'watchlist-details'    => "{{PLURAL:$1|$1 artikel|$1 artiklůw}} na pozorli'śće bez godek.",
'wlheader-enotif'      => '* Wysůuańy powjadůmjyń na adres e-brif je zouůnčůne',
'wlheader-showupdated' => "* Zajty, kere bouy sprowjane uod Twoi uostatńi wizyty na ńych zostoy naškryflane '''tuustym'''",
'watchmethod-recent'   => 'šnupańy za půmjyńanymi na uostatku w zajtach, na kere dowoš pozůr',
'watchmethod-list'     => 'šnupańy w zajtach, na kere dowoš pozůr pośrůd půmjyńanych na uostatku',
'watchlistcontains'    => 'Lista zajtůw, na kere dowoš pozůr mo {{PLURAL:$1|jedna pozycja|$1 pozycje|$1 pozycyji}}.',
'iteminvalidname'      => 'Problym ze pozycjům „$1”, felerne mjano...',
'wlnote'               => "Půńižy pokazano {{PLURAL:$1|ostatńy sprawjyńy dokůnane|ostatńy '''$1''' sprawjyńe dokůnane|ostatńych '''$1''' sprawjyń dokůnanych}} bez {{PLURAL:$2|uostatńo godźina|uostatńich '''$2''' godźin}}.",
'wlshowlast'           => 'Pokož uostatńy $1 godźin $2 dńi ($3)',
'watchlist-show-bots'  => 'pokaž sprowjyńo botůw',
'watchlist-hide-bots'  => 'schowej sprowjyńa botůw',
'watchlist-show-own'   => 'pokož bezy mje sprowjůne',
'watchlist-hide-own'   => 'schowej moje sprawjyńa',
'watchlist-show-minor' => 'pokož drobne pomjyńańa',
'watchlist-hide-minor' => 'Schowej drobne pomjyńańa',

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
{{fullurl:{{ns:special}}:Watchlist/edit}}

Pomoc:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Wyćep artikel',
'confirm'                     => 'Potwjyrdź',
'excontent'                   => 'zawartość zajty „$1”',
'excontentauthor'             => 'treść: „$1” (jedyny aůtor: [[Special:Contributions/$2|$2]])',
'exbeforeblank'               => 'popředńo zawartość uobecńy pustej zajty: „$1”',
'exblank'                     => 'Zajta byua pusto',
'delete-confirm'              => 'Wyćep „$1”',
'delete-legend'               => 'Wyćep',
'historywarning'              => 'Pozor! Ta zajta kerům chceš wyćepnůńć mo historjo:',
'confirmdeletetext'           => 'Chceš wyćepnůńć trwale artikel abo plik s bazy danych s historią. Pokož, aže wjyš co robdza, i to aže to je tak jak godojům [[{{MediaWiki:Policy-url}}|zasady]].',
'actioncomplete'              => 'Fertig',
'deletedtext'                 => 'Wyćepano "<nowiki>$1</nowiki>". Rejer uostatnio zrobiůnych wyćepań možeš uobejžyć tukej: $2.',
'deletedarticle'              => 'wyciepnjynto "[[$1]]"',
'suppressedarticle'           => 'utajńjyu [[$1]]',
'dellogpage'                  => 'Wyćepane',
'dellogpagetext'              => 'To je lista uostatńo wykůnanych wyćepań.',
'deletionlog'                 => 'rejer wyćepań',
'reverted'                    => 'Přiwrůcůno popředńo wersyja',
'deletecomment'               => 'Čymu chceš wyćepnůńć:',
'deleteotherreason'           => 'Inkšy powůd:',
'deletereasonotherlist'       => 'Inkszy powůd',
'deletereason-dropdown'       => '* Nojčynstše přičyny wyćepańa
** Prośba autora
** Narušyńy praw autorskych
** Wandalizm',
'delete-edit-reasonlist'      => 'Sprowjańe listy powodůw wyćepańo zajty',
'delete-toobig'               => 'Ta zajta mo bardzo dugo historja sprowjyń, wjyncy jak $1 {{PLURAL:$1|pomjeńańe|pomjeńańa|pomjeńań}}.
Jei wyćepańe moguo by spowodować zakuůcyńo w pracy {{GRAMMAR:D.lp|{{SITENAME}}}} i bez tůž zostouo uůgrańičůne.',
'delete-warning-toobig'       => 'Ta zajta mo bardzo dugo historia sprowjyń, wjyncy kej $1 {{PLURAL:$1|pomjyńeńe|pomjyńańo|pomjyńań}}.
Dej pozůr, bo jei wyćepańe može spowodować zakuůcyńo w pracy {{GRAMMAR:D.lp|{{SITENAME}}}}.',
'rollback'                    => 'Wycofej sprowjyńe',
'rollback_short'              => 'Cofej',
'rollbacklink'                => 'cofej',
'rollbackfailed'              => 'Ńy idźe wycofać sprowjyńo',
'cantrollback'                => 'Ńy idże cofnůńć pomjyńeńo, sam je ino jedna wersyja tyi zajty.',
'alreadyrolled'               => 'Ńy idźe do zajty [[:$1|$1]] cofnůńć uostatńygo pomjyńeńa, kere wykonau [[User:$2|$2]] ([[User talk:$2|godka]]).
Kto inkšy zdůnžůu juž to zrobić abo wprowadźiu wuasne poprowki do treśći zajty.

Autorym ostatńygo pomjyńyńo je terozki [[User:$3|$3]] ([[User talk:$3|godka]]).',
'editcomment'                 => 'Sprowjyńe uopisano: „<i>$1</i>”.', # only shown if there is an edit comment
'revertpage'                  => 'Wycofano sprowjyńe užytkowńika [[Special:Contributions/$2|$2]] ([[User talk:$2|godka]]).
Autor přiwrůcůnej wersyji to [[User:$1|$1]].', # Additional available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'rollback-success'            => 'Wycofano sprowjyńa užytkowńika $1.
Přiwrůcůno uostatńo wersyja autorstwa  $2.',
'sessionfailure'              => 'Feler weryfikacyji zalůgowańo.
Polecyńy zostouo anulowane, aby ůńiknůńć přechwycyńo sesyji.

Naćiś „cofej”, přeuaduj zajta, a potym zaś wydej polecyńy',
'protectlogpage'              => 'Zawarte',
'protectlogtext'              => 'Půńižej znojdowo śe lista zawarć i uodymkńjyńć pojydynčych zajtůw.
Coby přejřeć lista uobecńy zawartych zajtůw, přeńdź na zajta wykazu [[Special:Protectedpages|zawartych zajtůw]].',
'protectedarticle'            => 'zawar [[$1]]',
'modifiedarticleprotection'   => 'pomjyńiu poziům zawarćo [[$1]]',
'unprotectedarticle'          => 'uodymknyu [[$1]]',
'protect-title'               => 'Pomjyńeńe poźomu zawarćo „$1”',
'protect-legend'              => 'Potwjyrdź zawarće',
'protectcomment'              => 'Kůmyntoř:',
'protectexpiry'               => 'Wygaso:',
'protect_expiry_invalid'      => 'Čas wygaśńjyńćo je zuy.',
'protect_expiry_old'          => 'Čas wygaśńjyńćo je w downiej ńiž terozki.',
'protect-unchain'             => 'Uodymknij možliwość přećiśńjyńcio artikla.',
'protect-text'                => 'Sam tukej možyš uobejžeć i pomjyńyć poźům zawarcia zajty <strong><nowiki>$1</nowiki></strong>.',
'protect-locked-blocked'      => 'Ńy možeš půmjyńać poźůmůw zawarćo kej žeś sům je zawarty uod sprowjyń. Terozki štalowańa dla zajty <strong>$1</strong> to:',
'protect-locked-dblock'       => 'Ńy idźe půmjyńić poźůmu zawarća s kuli tygo co baza danych tyž je zawarto. Uobecne štalowańa dla zajty <strong>$1</strong> to:',
'protect-locked-access'       => 'Ńy moš uprowńyń coby pomjyńyć poziům zawarcia zajty. Uobecne ustawjyńo dlo zajty <strong>$1</strong> to:',
'protect-cascadeon'           => 'Ta zajta je zawarto od pomjyńań, po takjymu, co jei užywo {{PLURAL:$1|ta zajta, kero je zawarto|nastympůjůnce zajty, kere zostauy zawarte}} a opcyjo dźedźičyńo je zaůončono. Možeš pomjyńyć poziům zawarcia tyi zajty, ale dlo dźedźičyńo zawarcia to ńy mo wpuywu.',
'protect-default'             => '(důmyślny)',
'protect-fallback'            => 'Wymago pozwolynjo "$1"',
'protect-level-autoconfirmed' => 'tylko zaregišterůwani',
'protect-level-sysop'         => 'Ino admini',
'protect-summary-cascade'     => 'dźedźičyńy',
'protect-expiring'            => 'wygaso $1 (UTC)',
'protect-cascade'             => 'Dźedźyčyńe zawarćo - zawřij wšyskie zajty kere sům na tyi zajće.',
'protect-cantedit'            => 'Ńy možeš pomjyńyć poziůmu zawarća tyi zajty, po takiymu, co uona je dlo Ćebje zawarto uod pomjyńańa.',
'restriction-type'            => 'Pozwolyńy:',
'restriction-level'           => 'Poźům:',
'minimum-size'                => 'Min. wjelgość',
'maximum-size'                => 'Maksymalno wjelgość',
'pagesize'                    => '(bajtůw)',

# Restrictions (nouns)
'restriction-edit'   => 'Sprowjéj',
'restriction-move'   => 'Přećepńjyńće',
'restriction-create' => 'Stwůř',

# Restriction levels
'restriction-level-sysop'         => 'poune zawarće',
'restriction-level-autoconfirmed' => 'tajlowe zawarće',
'restriction-level-all'           => 'dowolny poziům',

# Undelete
'undelete'                     => 'Pokož wyćepńjynte zajty',
'undeletepage'                 => 'Pokož a odtwůř wyćepńjynte zajty',
'undeletepagetitle'            => "'''Půńižej znojdujům śe wyćepane wersyje zajty  [[:$1]]'''.",
'viewdeletedpage'              => 'Pokož wyćepńjynte zajty',
'undeletepagetext'             => 'Půńižše zajty zostouy wyćepane, nale jeich kopja wćůnž znojduje śe w archiwům.
Archiwům co jakiś čas može być uočyščane.',
'undeleteextrahelp'            => "Jak chceš wćepać nazod couko zajta, pozostaw wšyjstke pola ńyzaznačůne i naćiś '''Uodtwůř'''.
Aby wybrać tajlowe uodtwořyńy noležy zaznačyć uodpowjydńe pole.
Naćiśńjyńće '''Wyčyść''' usůńy wšyjstke zaznačyńo i wyčyśći pole komyntořa.",
'undeleterevisions'            => '$1 {{PLURAL:$1|zarchiwizowano wersyja|zarchiwizowane wersyje|zarchiwizowanych wersyji}}',
'undeletehistory'              => 'Wćepańy nazod zajty spowoduje přiwrůcyńy tyž jei wšyjstkych popředńich wersyji.
Jak uod času wyćepańo ktoś utwořůu nowo zajta uo idyntyčnym mjańy, uodtwařane wersyje znojdům śe w jei historii, a uobecna wersyjo pozostańy bez zmjan.
Uůgrańičyńo kere dotyčům wersyji pliku zostanům wyćepane w trakće wćepywańo nazod.',
'undeleterevdel'               => 'Wćepańy nazod zajty ńy bydźe přeprowadzůne kej by můguo spowodować tajlowe wyćepańy aktualnej wersyji.
W takej sytuacyji noležy uodznačyć abo přiwrůćić widočność nojnowšym wyćepanym wersjům.',
'undeletehistorynoadmin'       => 'Ta zajta zostoua wyćepano.
Powůd wyćepańo je podany w podsůmowańu půńižej, razym s danymi užytkowńika, kery sprawjou zajta před jei wyćepańym.
Sama treść wyćepanych wersyji je dostympna ino do admińistratorůw',
'undelete-revision'            => 'Wyćiepnjynto wersjo $1 z $2 uod $3:',
'undeleterevision-missing'     => 'Felerno abo brakujůnco wersyjo.
Možeš mjeć felerny link abo wersyjo můgua zostać wćepano nazod, abo wyćepano s archiwům.',
'undelete-nodiff'              => 'Ńy znejdźono popřednich wersyji.',
'undeletebtn'                  => 'Uodtwůř',
'undeletelink'                 => 'uodtwůř',
'undeletereset'                => 'Wyčyść',
'undeletecomment'              => 'Powůd wćepańo nazod:',
'undeletedarticle'             => 'wćepou nazod [[$1]]',
'undeletedrevisions'           => 'Wćepano nazod {{PLURAL:$1|1 wersyja|$1 wersyje|$1 wersyji}}',
'undeletedrevisions-files'     => 'Wćepano nazod $1 {{PLURAL:$1|wersyja|wersyje|wersyji}} i $2 {{PLURAL:$2|plik|pliki|plikůw}}',
'undeletedfiles'               => 'wćepou nazod $1 {{PLURAL:$1|plik|pliki|plikůw}}',
'cannotundelete'               => 'Wćepańy nazod ńy powjodo śe.
Kto inkšy můgu wćepać nazod zajta pjyrwšy.',
'undeletedpage'                => "<big>'''Wćepano nazod zajta $1.'''</big>

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

# Namespace form on various pages
'namespace'      => 'Přestřyń nazw:',
'invert'         => 'Wybjer na uopy',
'blanknamespace' => '(přodńo)',

# Contributions
'contributions' => 'Wkuod užytkowńika',
'mycontris'     => 'Bezy mje sprowjône',
'contribsub2'   => 'Do užytkowńika $1 ($2)',
'nocontribs'    => 'Brak pomjyńań uodpowjadajůncych tym kryterjům.',
'uctop'         => '(uostatnio)',
'month'         => 'Uod mjeśůnca (i downiyjše):',
'year'          => 'Uod roku (i dowńijše):',

'sp-contributions-newbies'     => 'Pokož wkuod ino uod nowych užytkowńikůw',
'sp-contributions-newbies-sub' => 'Dlo nowych užytkowńikůw',
'sp-contributions-blocklog'    => 'zawarća',
'sp-contributions-search'      => 'Šnupej za wkuodym',
'sp-contributions-username'    => 'Adres IP abo mjano užytkowńika',
'sp-contributions-submit'      => 'Šnupej',

# What links here
'whatlinkshere'            => 'Co sam linkuje',
'whatlinkshere-title'      => 'Zajty kere sům adrésowane do $1',
'whatlinkshere-page'       => 'Zajta:',
'linklistsub'              => '(Lista linków)',
'linkshere'                => "Nastympůjůnce zajty sóm adrésůwane do '''[[:$1]]''':",
'nolinkshere'              => "Žodno zajta ńy je adrésowana do '''[[:$1]]'''.",
'nolinkshere-ns'           => "Žodno zajta ńy je adresowano do '''[[:$1]]''' we wybrany přestřyni mjan.",
'isredirect'               => 'překerowujůnca zajta',
'istemplate'               => 'douůnčona šablôna',
'whatlinkshere-prev'       => '{{PLURAL:$1|popředńe|popředńe $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|nastympne|nastympne $1}}',
'whatlinkshere-links'      => '← do adrésata',
'whatlinkshere-hideredirs' => '$1 {{PLURAL:$1|překerowańe|překerowańa|překerowań}}',
'whatlinkshere-hidetrans'  => '$1 {{PLURAL:$1|douůnčyńe|douůnčyńa|douůnčyń}}',
'whatlinkshere-hidelinks'  => '$1 {{PLURAL:$1|link|linki|linkůw}}',

# Block/unblock
'blockip'                     => 'Zawřij sprowjyńo užytkowńikowi',
'blockip-legend'              => 'Zawřij sprowjyńo do užytkowńika',
'blockiptext'                 => 'Tyn formulař suužy do zawjerańo sprowjyń spod uokreślůnygo adresu IP abo kůnkretnymu užytkowńikowi.
Zawjerać noležy jydyńy po to, by zapobjec wandalizmům, zgodńy s [[{{MediaWiki:Policy-url}}|přijyntymi zasadami]].
Podej powůd (np. umješčajůnc mjana zajtůw, na kerych dopuščůno śe wandalizmu).',
'ipaddress'                   => 'Adres IP',
'ipadressorusername'          => 'Adres IP abo mjano užytkowńika',
'ipbexpiry'                   => 'Wygaso:',
'ipbreason'                   => 'Čymu:',
'ipbreasonotherlist'          => 'Inkszy powůd',
'ipbreason-dropdown'          => '*Nojčynstše powody zawjerańo uod sprawjyń
** Ataki na inkšych užytkowńikůw
** Narušyńy praw autorskych
** Ńydozwolůne mjano užytkowńika
** Upen proxy/Tor
** Spamowańy
** Ůsuwańy treśći zajtůw
** Wprowadzańy foušywych informacyji
** Wulgaryzmy
** Wypisywańy guůpot na zajtach',
'ipbanononly'                 => 'Zawřij sprawjyńo ino anůńimowym užytkowńikům',
'ipbcreateaccount'            => 'Ńy dozwůl utwožyć kůnta',
'ipbemailban'                 => 'Zawřij možliwość wysůuańo e-brifůw',
'ipbenableautoblock'          => 'Zawřij uostatńi adres IP tygo užytkowńika i autůmatyčńy wšyjstke kolejne, s kerych bydźe průbowou sprowjać zajty',
'ipbsubmit'                   => 'Zawřij uod sprowjyń tygo užytkowńika',
'ipbother'                    => 'Ikšy čas',
'ipboptions'                  => '2 godźiny:2 hours,1 dźyń:1 day,3 dńi:3 days,1 tydźyń:1 week,2 tygodńy:2 weeks,1 mjeśůnc:1 month,3 mjeśůnce:3 months,6 mjeśency:6 months,1 rok:1 year,ńyskůńčůny:infińite', # display1:time1,display2:time2,...
'ipbotheroption'              => 'inkšy',
'ipbotherreason'              => 'Inkšy powůd:',
'ipbhidename'                 => 'Schrůń mjano užytkowńika/adres IP w rejeře zawarć, na liśće aktywnych zawarć i liśće užytkowńikůw',
'badipaddress'                => 'Felerny adres IP',
'blockipsuccesssub'           => 'Zawarće uod sprowjyń udane',
'blockipsuccesstext'          => 'Užytkowńik [[Special:Contributions/$1|$1]] zostou zawarty uod sprowjyń.<br />
Přyńdź do [[Special:Ipblocklist|listy zawartych adresůw IP]] coby přejřeć zawarća.',
'ipb-edit-dropdown'           => 'Sprowjej powody zawjyrańo uod sprowjyń',
'ipb-unblock-addr'            => 'Uodymknij $1',
'ipb-unblock'                 => 'Uodymknij užytkowńika abo adres IP',
'ipb-blocklist-addr'          => 'Zoboč istńejůnce zawarća $1',
'ipb-blocklist'               => 'Zoboč istńijůnce zawarća',
'unblockip'                   => 'Uodymkńij sprowjyńo užytkowńikowi',
'unblockiptext'               => 'Ůžyj formulořa půńižej coby přiwrůćić možliwość sprowjańo s wčeśńij zawartygo adresu IP abo užytkowńikowi.',
'ipusubmit'                   => 'Uodymkńij sprowjyńo užytkowńikowi',
'unblocked'                   => '[[User:$1|$1]] zostou uodymkńynty.',
'unblocked-id'                => 'Zawarće $1 zostouo zdjynte',
'ipblocklist'                 => 'Lista užytkowńikůw i adresůw IP ze zawartymi sprowjyńami',
'ipblocklist-legend'          => 'Znejdź zawartygo uod sprawjyń užytkowńika',
'ipblocklist-username'        => 'Mjano užytkowńika abo adres IP',
'ipblocklist-submit'          => 'Šnupej',
'blocklistline'               => '$1, $2 zawar uod sprowjyń $3 ($4)',
'infiniteblock'               => 'na zawše',
'expiringblock'               => 'wygaso $1',
'anononlyblock'               => 'ino ńyzalůgowańy',
'noautoblockblock'            => 'autůmatyčne zawjyrańy uod sprowjyń wůuůnčůne',
'createaccountblock'          => 'zawarto twořyńe kont',
'emailblock'                  => 'zawarty e-brif',
'ipblocklist-empty'           => 'Lista zawarć je pusto.',
'ipblocklist-no-results'      => 'Podany adres IP abo užytkowńik ńy je zawarty uod sprowjyń.',
'blocklink'                   => 'zablokuj',
'unblocklink'                 => 'uodymknij',
'contribslink'                => 'wkůod',
'autoblocker'                 => 'Zawarto Ci sprowjyńo autůmatyčńy, bez tůž co užywaš tygo samygo adresu IP, co užytkowńik „[[User:$1|$1]]”.
Powůd zawarća $1 to: „$2”',
'blocklogpage'                => 'Historyja zawarć',
'blocklogentry'               => 'zawarto [[$1]], bydźe uodymkńynty: $2 $3',
'blocklogtext'                => "Půńižej znojdowo śe lista zawarć zouožůnych i zdjyntych s poščygůlnych adresůw IP.
Na li'śće ńy mo adresůw IP, kere zawarto w sposůb autůmatyčny.
Coby přejřeć lista uobecńy aktywnych zawarć, přyńdź na zajta [[Special:Ipblocklist|zawartych adresůw i užytkowńikůw]].",
'unblocklogentry'             => 'uodymknyu $1',
'block-log-flags-anononly'    => 'ino anůnimowi',
'block-log-flags-nocreate'    => 'twořyńe kůnta je zawarte',
'block-log-flags-noautoblock' => 'autůmatyčne zawjerańy uod sprawjyń wůuůnčůne',
'block-log-flags-noemail'     => 'e-brif zawarty',
'range_block_disabled'        => 'Možliwość zawjerańo zakresu adresůw IP zostoua wůuůnčůno.',
'ipb_expiry_invalid'          => 'Felerny čas wygaśńjyńćo zawarća.',
'ipb_already_blocked'         => '„$1” je juž zawarty uod sprowjyń',
'ipb_cant_unblock'            => 'Feler: Zawarće uo ID $1 ńy zostouo znejdźone. Moguo uone zostać oudymkńynte wčeśnij.',
'ipb_blocked_as_range'        => 'Feler: Adres IP $1 ńy zostou zawarty bezpośredńo i ńy može zostać uodymkńjynty.
Noležy uůn do zawartygo zakresu adresůw $2. Uodymknůńć možno ino couki zakres.',
'ip_range_invalid'            => 'Ńypoprowny zakres adresów IP.',
'blockme'                     => 'Zawryj mi sprowjyńa',
'proxyblocker'                => 'Zawjyrańe proxy',
'proxyblocker-disabled'       => 'Ta fůnkcyjo je wůuůnčůna.',
'proxyblockreason'            => 'Twůj adres IP zostou zawarty, bo je to adres uotwartygo proxy.
Sprawa noležy wyjaśńić s dostawcům Internetu abo půmocům techńičnům informujůnc uo tym powažnym problymje s bezpječyństwym.',
'proxyblocksuccess'           => 'Wykůnane.',
'sorbsreason'                 => 'Twůj adres IP znojdowo śe na liśće serwerůw open proxy w DNSBL, užywanej bez {{GRAMMAR:B.lp|{{SITENAME}}}}.',
'sorbs_create_account_reason' => 'Twůj adres IP znojdowo śe na liśće serwerůw open proxy w DNSBL, užywanej bez {{GRAMMAR:B.lp|{{SITENAME}}}}.
Ńy možeš utwořić kůnta',

# Developer tools
'lockdb'    => 'Zawryj baza danych',
'unlockdb'  => 'Uodymkńij baza danych',
'lockbtn'   => 'Zawřij baza danych',
'unlockbtn' => 'Uodymkńij baza danych',

# Move page
'move-page'               => 'Přećep $1',
'move-page-legend'        => 'Přećiś artikel',
'movepagetext'            => "Při půmocy formulařa půńižej možeš půmjyńyć nazwa zajty i přećepnůńć jei historja. Pod downym titlym uostańe zajta překerowujůnca. Zajty adresowane na stary titel uostanům jak bůuy. Sprowdź, čy žeś ńy uostawieu kajś podwůjne abo zerwane překerowańy. Žeś je uodpedźalny za to, coby adresowańy bůuo do wuaśćiwych artiklůw!

Zajta '''ńy''' bydźe přećepano, jak:
*je pusto i ńy bůua sprowjano
*je zajtům překerowujůncą
*zajta uo takym titlu juž sam jest

'''DEJ POZŮR!'''
To može być drastyčno abo i ńypřewidywalno zmjano, jak přećepńyš jako popularno zajta. Bydź pewny, aže wješ co robiyš, ńim klikńyš knefel \"přećep\"!",
'movepagetalktext'        => 'Uodpowiednio zajta godki, jeśli jest, bydzie přećepano automatyčńe, pod warůnkiem, že:
*ńy přećepuješ zajty do inkšy přestřeńy mjan
*ńy ma sam zajty godki o takiym mjańe
W takiych razach tekst godki třa přećepać, a jak třeba to i pouůnčyć z tym co juž sam jest, rynčńe. Abo možeš sie namyślić i nie přećepywać wcale ("checkbox" půnižyi).',
'movearticle'             => 'Přećiś artikel:',
'movenologin'             => 'Ńy jestžeś zalůgowany',
'newtitle'                => 'Nowy titel:',
'move-watch'              => 'Dej pozor',
'movepagebtn'             => 'Přećiś artikel',
'pagemovedsub'            => 'Přećiśńjyńće gotowe',
'movepage-moved'          => '<big>\'\'\'"$1" přećiśńjynto ku "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Artikel s takym mjanym juž je, abo mjano je zue.
Wybjer inkše mjano.',
'talkexists'              => 'Zajta artikla zostaua přećepano, ale zajta godki ńy - zajta godki uo nowym mjańe juž sam jest. Poůunč, proša, teksty oubydwůch godek rynčńe.',
'movedto'                 => 'přećiśńjynto ku',
'movetalk'                => 'Přećiś godke, jak možno.',
'talkpagemoved'           => 'Godka artikla zostoua přećiśńjynto.',
'talkpagenotmoved'        => 'Godka artikla <strong>njy</strong> zostoua přećiśńjynto.',
'1movedto2'               => '[[$1]] přećepano do [[$2]]',
'movelogpage'             => 'Přećepńynte',
'movereason'              => 'Čymu:',
'revertmove'              => 'cofej',
'delete_and_move'         => 'Wyćep i przećep',
'delete_and_move_confirm' => 'Toć, wyćep zajta',

# Export
'export'        => 'Export zajtůw',
'export-addcat' => 'Dodej',

# Namespace 8 related
'allmessages'     => 'Komunikaty',
'allmessagesname' => 'Mjano',

# Thumbnails
'thumbnail-more'  => 'Powjynkš',
'filemissing'     => 'Njy mo pliku',
'thumbnail_error' => 'Feler při gynerowańu mińatury: $1',

# Import log
'importlogpage' => 'Rejer importa',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Mojo osobisto zajta',
'tooltip-pt-mytalk'               => 'Mojo godka',
'tooltip-pt-preferences'          => 'Moje preferéncyje',
'tooltip-pt-watchlist'            => 'Lista artiklůw na kere daješ pozor',
'tooltip-pt-mycontris'            => 'Lista bezy mje sprowjônych',
'tooltip-pt-login'                => 'My by chćeli cobyś śe zalůgowou, ale to ńy je uobowjůnzek.',
'tooltip-pt-logout'               => 'Wyloguj sie z wiki',
'tooltip-ca-talk'                 => 'Godej uo tym artiklu',
'tooltip-ca-edit'                 => 'Možeš sprowjać ta zajta. Ńim naškryfloš půmjyńańo, klikńij we knefel "podglůnd".',
'tooltip-ca-addsection'           => 'Dodej kůmyntoř do godki',
'tooltip-ca-viewsource'           => 'Ta zajta je zabezpječůno. Možeš śe uofilować tekst źrůduowy.',
'tooltip-ca-protect'              => 'Zawřij ta zajta',
'tooltip-ca-delete'               => 'Wyćep ta zajta',
'tooltip-ca-move'                 => 'Přećepnij ta zajta kaj indziy.',
'tooltip-ca-watch'                => 'Dodej artikel do pozorlisty',
'tooltip-ca-unwatch'              => 'Wyciep ten artikel z pozorlisty',
'tooltip-search'                  => 'Šnupej we serwisie {{SITENAME}}',
'tooltip-p-logo'                  => 'Přodńo zajta',
'tooltip-n-mainpage'              => 'Přelyź na Přodńo zajta',
'tooltip-n-portal'                => 'Uo projekće, co sam možeš majštrować, kaj idźe znolyźć informacyje',
'tooltip-n-currentevents'         => 'Informacyje uo aktualnych wydařyńach',
'tooltip-n-recentchanges'         => 'Lista pomjéńanych na űostatku na wiki',
'tooltip-n-randompage'            => 'Pokož losowo zajta',
'tooltip-n-help'                  => 'Zapoznej sie z obsůgą wiki',
'tooltip-n-sitesupport'           => 'Wspůmůž nas',
'tooltip-t-whatlinkshere'         => 'Pokož lista zajtůw kere sam sům adrésowane',
'tooltip-feed-rss'                => 'Kanau RSS do tyj zajty',
'tooltip-t-contributions'         => 'Pokož lista sprowjyń tygo užytkowńika',
'tooltip-t-emailuser'             => 'Wyślij e-brif do tygo užytkowńika',
'tooltip-t-upload'                => 'Wćepńij plik na serwer',
'tooltip-t-specialpages'          => 'Lista wšyskich extra zajtów',
'tooltip-ca-nstab-user'           => 'Pokož uosobisto zajta užytkowńika',
'tooltip-ca-nstab-project'        => 'Uobejřij zajta projektu',
'tooltip-ca-nstab-image'          => 'Pokož zajta grafiki',
'tooltip-ca-nstab-template'       => 'Uobejřij šablôna',
'tooltip-ca-nstab-help'           => 'Pokož zajte s půmocą',
'tooltip-ca-nstab-category'       => 'Pokož zajta kategorji',
'tooltip-minoredit'               => 'Uoznač ta zmjana za drobno',
'tooltip-save'                    => 'Naškréflej pomjyńańa',
'tooltip-preview'                 => 'Uobejřij jak bydźe wyglůndać zajta po twojym sprawjyńu, zańim naškryfloš!',
'tooltip-diff'                    => 'Pokozuje kere dyferéncyje žeś zrobjou artiklowi.',
'tooltip-compareselectedversions' => 'Zoboč růžńica mjyndzy wybranymi wersyjami zajty.',
'tooltip-watch'                   => 'Dodej tyn artikel do pozorlisty',

# Attribution
'others' => 'inkśi',

# Browsing diffs
'previousdiff' => '← Popředńy sprowjyńy',
'nextdiff'     => 'Nostympno dyferéncyjo →',

# Media information
'widthheightpage'      => '$1×$2, $3 zajt',
'file-info-size'       => '($1 × $2 pikseli, rozmior plika: $3, typ MIME: $4)',
'file-nohires'         => '<small>Uobrozek we wjynkšej rozdźelčośći ńy je dostympny.</small>',
'svg-long-desc'        => '(Plik SVG, nůminalńe $1 × $2 pixelůw, rozmior plika: $3)',
'show-big-image'       => 'Oryginalno rozdźelčość',
'show-big-image-thumb' => '<small>Rozmiar podglůndu: $1 × $2 pikseli</small>',

# Special:Newimages
'newimages'    => 'Galerjo nowych uobrozkůw',
'showhidebots' => '($1 boty)',
'ilsubmit'     => 'Šnupej',

# Bad image list
'bad_image_list' => 'Dane noležy prowadźić we formaće:

Ino elementy tyi listy (linie kere majům na přodku *) bierymy pod uwoga.
Pjerwšy link w lińii muśi być linkym do zabrůńůnygo pliku.
Nostympne linki w lińii uwažůmy za wyjůntki, to sům nazwy zajtůw, kaj plik uo zakozanyj nazwje idźe wstawić.',

# Metadata
'metadata'          => 'Metadane',
'metadata-help'     => 'Tyn plik zawjyro extra dane, kere dodou aparat cyfrowy abo skaner. Jak coś we pliku bůuo půmjyńane, te extra dane můgům być ńyakuratne.',
'metadata-expand'   => 'Pokož ščygůuy',
'metadata-collapse' => 'Schowej ščygůuy',
'metadata-fields'   => 'Pola kere wymjyńůno pońižy pola EXIF bydům wymjyńůne na zajcie grafiki. Inkše pola bydům důmyślńy schowane.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# EXIF tags
'exif-imagedescription'    => 'Titel uobrozka',
'exif-artist'              => 'Autor',
'exif-exposuretime-format' => '$1 s ($2)',

'exif-meteringmode-255' => 'inkšy',

'exif-contrast-1' => 'Lichy',
'exif-contrast-2' => 'Srogi',

'exif-sharpness-0' => 'Normalno',
'exif-sharpness-1' => 'Licho',
'exif-sharpness-2' => 'Srogo',

'exif-subjectdistancerange-1' => 'Makro',

# External editor support
'edit-externally'      => 'Edytuj tyn plik bez zewnyntřno aplikacyjo',
'edit-externally-help' => 'Zoboč wjyncyj informacyji uo užywańu [http://meta.wikimedia.org/wiki/Help:External_editors zewnyntřnych edytorůw].',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'do kupy',
'namespacesall' => 'wšyskie',
'monthsall'     => 'wšyskie',

# action=purge
'confirm_purge_button' => 'OK',

# AJAX search
'hideresults' => 'Schowej wyniki',

# Multipage image navigation
'imgmultipageprev' => '← popředńo zajta',
'imgmultipagenext' => 'nostympno zajta →',

# Table pager
'table_pager_next' => 'Nostympno zajta',
'table_pager_prev' => 'Popředńo zajta',

# Watchlist editor
'watchlistedit-raw-titles' => 'Title:',

# Watchlist editing tools
'watchlisttools-view' => 'Pokož wažńijše pomjyńańo',
'watchlisttools-edit' => 'Pokož i zmjyńoj pozorliste',
'watchlisttools-raw'  => 'Zmjyńoj surowo pozorlista',

# Special:Version
'version'                  => 'Wersjo', # Not used as normal message but as header for the special page itself
'version-specialpages'     => 'Ekstra zajty',
'version-other'            => 'Inkše',
'version-version'          => 'Wersjo',
'version-license'          => 'Licencjo',
'version-software-version' => 'Wersjo',

# Special:Filepath
'filepath-page' => 'Plik:',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Šnupej za duplikatym plika',
'fileduplicatesearch-filename' => 'Mjano pliku:',
'fileduplicatesearch-submit'   => 'Šnupej',

);
