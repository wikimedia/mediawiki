<?php
/** Silesian (ślůnski)
 *
 * @addtogroup Language
 *
 * @author Lajsikonik
 * @author Herr Kriss
 * @author Pimke
 */

$fallback = 'pl';

$messages = array(
'underline-always' => 'Zawdy',

# Dates
'sunday'        => 'Ńedźela',
'monday'        => 'Pyńdźołek',
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
'categories'             => 'Kategoryje',
'category_header'        => 'Zajty w kategorie "$1"',
'subcategories'          => 'Podkategoryje',
'category-media-header'  => 'Pliki w kategoryji "$1"',
'category-empty'         => "''W tyi katygorii ńy ma terozki artikli ańi plikůw''",
'listingcontinuesabbrev' => 'c.d.',

'about'     => 'Uo serwiśe',
'newwindow' => '(odmyko śe w nowym uokńy)',
'cancel'    => 'Odćepnij',
'qbfind'    => 'Šnupej',
'qbedit'    => 'Sprowjéj',
'mytalk'    => 'Mojo godka',

'errorpagetitle'   => 'Feler',
'returnto'         => 'Nazod do zajty $1.',
'tagline'          => 'S {{GRAMMAR:D.lp|{{SITENAME}}}}',
'help'             => 'Pomoc',
'search'           => 'Šnupej',
'searchbutton'     => 'Šnupej',
'searcharticle'    => 'Přéńdź',
'history'          => 'Historia zajty',
'history_short'    => 'Historjo',
'printableversion' => 'Wersyjo do druku',
'permalink'        => 'Bezpośredńi link',
'edit'             => 'sprowjéj',
'editthispage'     => 'Sprowiej ta zajta',
'delete'           => 'Wyciep',
'protect'          => 'Zawřij',
'newpage'          => 'Nowy artikel',
'talkpage'         => 'Godej o tym artiklu',
'talkpagelinktext' => 'Dyskusyjo',
'personaltools'    => 'Osobiste',
'talk'             => 'Godka',
'views'            => 'Widok',
'toolbox'          => 'Werkcojg',
'redirectedfrom'   => '(Překerowano s $1)',
'redirectpagesub'  => 'Zajta překerowujůnca',
'jumpto'           => 'Přéńdź do:',
'jumptonavigation' => 'nawigacyji',
'jumptosearch'     => 'Šnupańe',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Uo {{GRAMMAR:MS.lp|{{SITENAME}}}}',
'aboutpage'         => 'Project:Uo serwiśe',
'bugreports'        => 'Raport o felerach',
'bugreportspage'    => 'Project:Felery',
'copyrightpage'     => '{{ns:project}}:Prawa autorskie',
'currentevents'     => 'Bježůnce wydařyńa',
'currentevents-url' => 'Project:Bježůnce wydařyńa',
'disclaimers'       => 'Informacyje prawne',
'disclaimerpage'    => 'Project:Informacyje prawne',
'edithelp'          => 'Pomoc we pomjyńańu',
'edithelppage'      => 'Help:Jak pomjyńać zajta',
'helppage'          => 'Help:Pomoc',
'mainpage'          => 'Přodńo zajta',
'portal'            => 'Portal užytkowńikůw',
'portal-url'        => 'Project:Portal užytkowńikůw',
'privacy'           => 'Zasady chroniyńo prywatności',
'privacypage'       => 'Project:Zasady uochrůny prywatnośći',
'sitesupport'       => 'Śćepa',
'sitesupport-url'   => 'Project:Wspůmůž nas',

'retrievedfrom'       => 'Zdřůdło "$1"',
'youhavenewmessages'  => 'Mosz $1 ($2).',
'newmessageslink'     => 'nowe wjadůmośći',
'newmessagesdifflink' => 'ostatnio dyferéncyjo',
'editsection'         => 'sprowjéj',
'editold'             => 'sprowjéj',
'editsectionhint'     => 'Sprowjéj sekcjo: $1',
'toc'                 => 'Spis treśći',
'showtoc'             => 'pokož',
'hidetoc'             => 'schrůń',
'site-rss-feed'       => 'Kanał RSS {{GRAMMAR:D.lp|$1}}',
'site-atom-feed'      => 'Kanał Atom {{GRAMMAR:D.lp|$1}}',
'page-rss-feed'       => 'Kanau RSS "$1"',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-user'     => 'Zajta užytkowńika',
'nstab-project'  => 'Zajta projektu',
'nstab-image'    => 'Plik',
'nstab-template' => 'Šablôna',
'nstab-category' => 'Katégoryjo',

# General errors
'badtitle'       => 'Zuy titel',
'badtitletext'   => 'Podano felerny titel zajty. Prawdopodobńy sům w ńjym znoki kierych ńjy wolno užywać we titlach, abo je pusty.',
'viewsource'     => 'Tekst źrůduowy',
'viewsourcefor'  => 'dlo $1',
'viewsourcetext' => 'We tekst źrůduowy tyi zajty možna dali filować, idźe go tyž kopjować.',

# Login and logout pages
'yourname'              => 'Login:',
'yourpassword'          => 'Hasuo:',
'remembermypassword'    => 'Zapamjyntej moje hasuo na tym kůmputře',
'login'                 => 'Zalůguj mie',
'loginprompt'           => 'Muśiš mjeć zouůnčůne cookies coby můc śe sam zalůgować.',
'userlogin'             => 'Logowańe / regišterowańe',
'logout'                => 'Wyloguj mie',
'userlogout'            => 'Wylogowańe',
'nologin'               => 'Niy moš konta? $1.',
'nologinlink'           => 'Regišteruj sie',
'createaccount'         => 'Zouůž nowe kůnto',
'gotaccount'            => 'Moš juž kůnto? $1.',
'gotaccountlink'        => 'Zalůguj śe',
'yourrealname'          => 'Prowdźiwe mjano:',
'prefs-help-realname'   => '* Mjano i nazwisko (opcjůnalńy): jak žeś zdecydowou aže je podoš, bydům užyte, coby Twoja robota mjoua atrybucyjo.',
'loginsuccesstitle'     => 'Logowanie udane',
'loginsuccess'          => "'''Terozki žeś jest zalogůwany do {{SITENAME}} jako \"\$1\".'''",
'nosuchuser'            => 'Ńy ma sam užytkowńika o mjańe "$1".
Sprowdź pisowńja, abo užyj formulařa půńižej coby utwořić nowe kůnto.',
'nosuchusershort'       => 'Ńy ma sam užytkowńika uo mjańe "<nowiki>$1</nowiki>".',
'nouserspecified'       => 'Podej mjano užytkowńika.',
'wrongpassword'         => 'Hasuo kiere žeś naškryflou je felerne. Poprůbůj naškryflać je ješče roz.',
'wrongpasswordempty'    => 'Hasuo kiere žeś podou je puste. Naškryflej je ješče roz.',
'passwordtooshort'      => 'Hasuo kere žeś podou je za krůtke.
Hasuo musi mjyć přinojmńij $1 buchštabůw i być inkše uod mjana užytkowńika.',
'mailmypassword'        => 'Wyślij mi nowe hasuo e-brifem',
'passwordremindertitle' => 'Nowe tymčasowe hasuo dla {{SITENAME}}',
'passwordremindertext'  => 'Ktuś (chyba Ty, s IP $1)
podo, aže chce nowe hasuo do {{SITENAME}} ($4).
Nowe hasuo do užytkowńika "$2" je "$3".
Zalůgůj śe terozki i zmjyń swoje hasuo.

Jak ktuś inkšy chćou nowe hasuo abo jak Ci śe připůmńouo stare i njy chceš nowygo, to źignoruj to i užywyj starygo hasua.',
'noemail'               => 'Njy mo u nos adresu e-mail do "$1".',
'passwordsent'          => 'Nowe hasuo pošuo na e-maila uod užytkowńika "$1".
Zalůguj śe zaś jak dostańyš tygo maila.',

# Edit page toolbar
'bold_sample'     => 'Ruby tekst',
'bold_tip'        => 'Ruby tekst',
'italic_sample'   => 'Tekst pochylůny',
'italic_tip'      => 'Tekst pochylůny',
'link_sample'     => 'Tytuł linka',
'link_tip'        => 'Link wewnyntřny',
'extlink_sample'  => 'http://www.przykuod.szl tytůł zajty',
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
'summary'                => 'Opis pomjéńań',
'subject'                => 'Tymat/naguůwek',
'minoredit'              => 'To je ńjywjelgie sprowjyńe',
'watchthis'              => 'Dej pozor',
'savearticle'            => 'Škryflej',
'preview'                => 'Podglůnd',
'showpreview'            => 'Pokož podglůnd',
'showdiff'               => 'Pokož dyferéncyje',
'anoneditwarning'        => 'Nie jest žeś zalogowany. W historie sprowjyń tyi zajty bydzie naškréflany Twój adres IP.',
'summary-preview'        => 'Podglůnd uopisu',
'blockedtext'            => '<big>\'\'\'Twoje kůnto abo adres IP sům zawarte.\'\'\'</big>

Uo zawarću zdecydowou $1. Pado, aže s kuli: \'\'$2\'\'.

* Zawarte uod: $8
* Uodymkńe śe: $6
* Bez cůž: $7
Coby wyjaśńić sprawa zawarćo, naškryflej do $1 abo inkšygo [[{{MediaWiki:Grouppage-sysop}}|admińistratora]].
Ńy možeš posuać e-brifa bez "poślij e-brifa tymu užytkowńikowi", jak žeś ńy podou dobrygo adresa e-brifa we prefyryncyjach , abo jak e-brify moš tyž zawarte. Terozki moš adres IP $3 a nůmer zawarća to #$5. Prošymy podać jedyn abo uobadwa jak chceš pouosprawjać uo zawarću.',
'newarticle'             => '(Nowy)',
'newarticletext'         => 'Ńy ma sam ješče artikla uo tym tytule. W polu ńižej možeš naškryflać jygo pjeršy fragmynt. Jak chćoužeś zrobić co inkše, naćiś ino knefel "Nazod".',
'noarticletext'          => 'Ńy ma ješče zajty uo tym tytule. Možeš [{{fullurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} wćepać artikel {{FULLPAGENAME}}] abo [[{{ns:special}}:Search/{{FULLPAGENAME}}|šnupać za {{FULLPAGENAME}} w inkšych artiklach]].',
'previewnote'            => '<strong>To je ino podglůnd - artikel ješče ńy je naškryflany!</strong>',
'editing'                => 'Sprowioš $1',
'editingsection'         => 'Sprowjoš $1 (kůnsek)',
'copyrightwarning'       => "Pamjyntej uo tym, aže couki wkuod do {{SITENAME}} udostympńůmy wedle zasad $2 (dokuadńij w $1). Jak ńy chceš, coby koždy můg go zmjyńać i dali rozpowšychńać, ńy wćepuj go sam. Škréflajůnc sam tukej pośwjadčoš tyž, co te pisańy je twoje wuasne, abo žeś go wźůn(a) s materjouůw kere sům na ''public domain'', abo kůmpatybilne.<br />
<strong>PROŠA NIE WCIEPYWAĆ SAM MATERIAUŮW KIERE SUŮM CHRUNIONE PRAWEM AUTORSKIM BEZ DOZWOLENIO WUAŚCICIELA!</strong>",
'longpagewarning'        => '<strong>Dej pozůr: Ta zajta je $1 kilobajt-y/-ůw wjelgo; w ńykierych přyglůndarkach můgům wystąpić problymy w sprowjańu zajtůw kiere majům wjyncyj jak 32 kilobajty. Jak byś ůmjou, podźel tekst na mjyńše tajle.</strong>',
'templatesused'          => 'Šablůny užyte na tyi zajće:',
'templatesusedpreview'   => 'Šablôny užyte w tym podglůńdźe:',
'template-protected'     => '(zawarty před sprowjańym)',
'template-semiprotected' => '(tajlowo zawarte)',
'nocreatetext'           => 'Na {{GRAMMAR:MS.lp|{{SITENAME}}}} twořyńe nowych zajtów uograničono. Možesz sprowjać te co już sóm, abo [[{{ns:special}}:Userlogin|zalogować sie, abo štartnůnć konto]].',
'recreate-deleted-warn'  => "'''Dej pozůr: Průbuješ wćepać nazod zajta kiero juž bůua wyćepano.'''

Zastanůw śe, čy sprowjańy nazod tyi zajty mo uzasadńjyńe. Dla wygody užytkowńikůw, ńižyi pokozano rejestr wyćepńjyńć tyi zajty:",

# History pages
'viewpagelogs'        => 'Uoboč rejery uoperacyji do tyi zajty',
'currentrev'          => 'Aktualno wersyja',
'revisionasof'        => 'Wersyjo z dńa $1',
'revision-info'       => 'Wersyjo z dńa $1; $2',
'previousrevision'    => '← popředńo wersyjo',
'nextrevision'        => 'Naštympno wersyjo→',
'currentrevisionlink' => 'Aktualno wersyjo',
'cur'                 => 'biež',
'last'                => 'popředńo',
'page_first'          => 'počůntek',
'page_last'           => 'kůńyc',
'histlegend'          => 'Wybůr růžńic do porůwnańo: postow kropki we boksach i naćiś enter abo knefel na dole.<br />
Lygynda: (bjež) - růžńice s wersyjo bježůncům, (popř) - růžńice s wersyjo popředzajůncům, d - drobne zmjany',
'histfirst'           => 'od počůntku',
'histlast'            => 'od uostatka',

# Revision feed
'history-feed-item-nocomment' => '$1 uo $2', # user at time

# Diffs
'history-title'           => 'Historyja sprowjyń "$1"',
'difference'              => '(Růžńice mjyndzy škryflańami)',
'lineno'                  => 'Linia $1:',
'compareselectedversions' => 'porůwnej wybrane wersyje',
'editundo'                => 'cofej',
'diff-multi'              => '(Ńy pokazano {{PLURAL:$1|jydnej wersyji pośredńij|$1 wersyji pośredńich}}.)',

# Search results
'noexactmatch' => "'''Niy ma sam zajtów nazwanych \"\$1\".'''
Možyš [[:\$1|tako utwořyć]], abo sprůbować pounygo šnupańa.",
'prevn'        => 'popředńe $1',
'nextn'        => 'nastympne $1',
'viewprevnext' => 'Uobejřij ($1) ($2) ($3)',
'powersearch'  => 'Šnupańe zaawansowane',

# Preferences page
'preferences'   => 'Preferencyje',
'mypreferences' => 'Moje preferéncyje',
'retypenew'     => 'Naškryflej ješče roz nowe hasuo:',
'default'       => 'důmyślnje',

'grouppage-sysop' => '{{ns:project}}:Administratořy',

# User rights log
'rightslog' => 'Uprawńyńa',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|pomjyńańe|pomjyńańa|pomjyńań}}',
'recentchanges'                  => 'Pomjéńane na űostatku',
'recentchanges-feed-description' => 'Dowej pozůr na pomjyńane na uostatku na tyi wiki .',
'rcnote'                         => "Půńižej {{PLURAL:$1|pokozano uostatńo zmjano dokůnano|pokazano uostatńy '''$1''' zmjany naškryflane|pokozano uostatńich '''$1''' škryflań zrobjůnych}} bez {{PLURAL:$2|ostatńi dźyń|ostatńich '''$2''' dńi}}, začynojůnc uod $3.",
'rcnotefrom'                     => 'Půńižej pokazano půmjyńańo zrobjůne po <b>$2</b> (ńy wjyncyj jak <b>$1</b> pozycji).',
'rclistfrom'                     => 'Pokož půmjyńańo uod $1',
'rcshowhideminor'                => '$1 drobne pomjyńańa',
'rcshowhidebots'                 => '$1 boty',
'rcshowhideliu'                  => '$1 zalůgowanych užytkowńikůw',
'rcshowhideanons'                => '$1 anůńimowych',
'rcshowhidepatr'                 => '$1 na kiere dowomy pozůr',
'rcshowhidemine'                 => '$1 beze mie sprowjůne',
'rclinks'                        => 'Pokož uostatńe $1 sprowjyń bez uostatńe $2 dńi.<br />$3',
'diff'                           => 'dyf',
'hist'                           => 'hist',
'hide'                           => 'schrůń',
'show'                           => 'Pokoż',
'minoreditletter'                => 'd',
'newpageletter'                  => 'N',
'boteditletter'                  => 'b',

# Recent changes linked
'recentchangeslinked'          => 'Pomjéńane w dolinkowanych',
'recentchangeslinked-title'    => 'Pomjyńyńo w adrésowanych s $1',
'recentchangeslinked-noresult' => 'Nikt nic niy pomjyńoł w dolinkowanych bez čas uo kiery žeś pytou.',
'recentchangeslinked-summary'  => "To je ekstra zajta, na kerej možeš uobočyć zmjany w artiklach dolinkowanych.
Artikle na pozorli'śće sům '''rube'''.",

# Upload
'upload'        => 'Wćepnij plik',
'uploadbtn'     => 'Wćepnij sam plik',
'uploadlogpage' => 'Wćepane sam',
'uploadedimage' => 'wćepano "[[$1]]"',

# Special:Imagelist
'imagelist' => 'Lista plikůw',

# Image description page
'filehist'                  => 'Historjo pliku',
'filehist-help'             => 'Klikńij na data/čas, coby uobejřeć plik taki jak wtedy wyglůndou.',
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
'noimage'                   => 'Ńjy ma sam plika uo takiej nazwje. Možeš go sam $1.',
'noimage-linktext'          => 'wćepać',
'uploadnewversion-linktext' => 'Wćepńij nowšo wersyjo tygo plika',

# File deletion
'filedelete-comment'          => 'Čymu chceš wyćepnůńć:',
'filedelete-otherreason'      => 'Inkšy powůd:',
'filedelete-reason-otherlist' => 'Inkszy powůd',

# MIME search
'mimesearch' => 'Sznupej MIME',

# List redirects
'listredirects' => 'Lista překerowań',

# Unused templates
'unusedtemplates' => 'Ńyužywane šablôny',

# Random page
'randompage' => 'Losuj zajta',

# Random redirect
'randomredirect' => 'Losowe překerowańy',

# Statistics
'statistics' => 'Statystyka',

'disambiguations' => 'Zajty ujydnoznačńajůnce',

'doubleredirects' => 'Podwůjne překierowańa',

'brokenredirects'      => 'Zuomane překerowańa',
'brokenredirects-edit' => '(sprowjéj)',

'withoutinterwiki' => 'Artikle bez interwiki',

'fewestrevisions' => 'Zajty z nojmńijšom ilośćům wersyji',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bajty|bajtůw}}',
'nlinks'                  => '$1 {{PLURAL:$1|link|linki|linkůw}}',
'nmembers'                => '$1 {{PLURAL:$1|elyment|elymenty|elymentůw}}',
'lonelypages'             => 'Poćepńynte zajty',
'uncategorizedpages'      => 'Zajty bez kategoryje',
'uncategorizedcategories' => 'Kategoryje bez kategoriůw',
'uncategorizedimages'     => 'Pliki bez kategoriůw',
'uncategorizedtemplates'  => 'Šablôny bez kategorii',
'unusedcategories'        => 'Ńyužywane kategoryje',
'unusedimages'            => 'Ńyužywane pliki',
'wantedcategories'        => 'Potřebne katygoryje',
'wantedpages'             => 'Nojpotřebńijše zajty',
'mostlinked'              => 'Nojčyńśćej adrésowane',
'mostlinkedcategories'    => 'Kategoryje we kierych je nojwjyncyi artikli',
'mostlinkedtemplates'     => 'Nojčyńśćej adrésowane šablôny',
'mostcategories'          => 'Zajty kiere majům nojwiyncyi kategoriůw',
'mostimages'              => 'Nojčyńśćij adresowane pliki',
'mostrevisions'           => 'Nojčyńśćej sprowjane artikle',
'prefixindex'             => 'Wšyskie zajty wedle prefiksa',
'shortpages'              => 'Nojkrůtše zajty',
'longpages'               => 'Dugje artikle',
'deadendpages'            => 'Artikle bez linkůw',
'protectedpages'          => 'Zawarte zajty',
'listusers'               => 'Lista užytkowńikůw',
'specialpages'            => 'Extra zajty',
'newpages'                => 'Nowe zajty',
'ancientpages'            => 'Nojstarše artikle',
'move'                    => 'Přećepnij',
'movethispage'            => 'Přećepej ta zajta',

# Book sources
'booksources' => 'Kśąžki',

# Special:Log
'specialloguserlabel'  => 'Užytkowńik:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'Rejery uoperacjůw',
'all-logs-page'        => 'Wšyjstkie uoperacyje',

# Special:Allpages
'allpages'       => 'Wšyskie zajty',
'alphaindexline' => 'úod $1 do $2',
'nextpage'       => 'Nastympno zajta ($1)',
'prevpage'       => 'Popředńo zajta ($1)',
'allpagesfrom'   => 'Zajty začynojůnce śe na:',
'allarticles'    => 'Wšyskie zajty',
'allpagessubmit' => 'Pokož',
'allpagesprefix' => 'Pokož artikle s prefiksym:',

# E-mail user
'emailuser' => 'Wyślij e-brif do tygo užytkowńika',

# Watchlist
'watchlist'            => 'Pozorlista',
'mywatchlist'          => 'Mojo pozorlista',
'watchlistfor'         => "(dla užytkowńika '''$1''')",
'addedwatch'           => 'Dodane do pozorlisty',
'removedwatch'         => 'Wyćepńjynte s pozorlisty',
'removedwatchtext'     => 'Artikel "[[:$1]]" zostou wyćepńjynty s pozorlisty.',
'watch'                => 'Dej pozor',
'watchthispage'        => 'Dej pozor',
'unwatch'              => 'Njy dowej pozoru',
'watchlist-details'    => "{{PLURAL:$1|$1 artikel|$1 artiklůw}} na pozorli'śće bez godek.",
'wlshowlast'           => 'Pokož uostatńy $1 godźin $2 dńi ($3)',
'watchlist-hide-bots'  => 'schowej sprowjyńa botůw',
'watchlist-hide-own'   => 'schowej moje sprawjyńa',
'watchlist-hide-minor' => 'Schowej drobne pomjyńańa',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Dowom pozor...',
'unwatching' => 'Njy dowom pozoru...',

# Delete/protect/revert
'deletepage'              => 'Wyćep artikel',
'historywarning'          => 'Pozor! Ta zajta kerům chceš wyćepnůńć mo historjo:',
'actioncomplete'          => 'Fertig',
'deletedarticle'          => 'wyciepnjynto "[[$1]]"',
'dellogpage'              => 'Wyćepane',
'deletecomment'           => 'Čymu chceš wyćepnůńć:',
'deleteotherreason'       => 'Inkšy powůd:',
'deletereasonotherlist'   => 'Inkszy powůd',
'rollbacklink'            => 'cofej',
'protectlogpage'          => 'Zawarte',
'protectcomment'          => 'Kůmyntoř:',
'protectexpiry'           => 'Wygaso:',
'protect_expiry_invalid'  => 'Čas wygaśńjyńćo je zuy.',
'protect_expiry_old'      => 'Čas wygaśńjyńćo je w downiej ńiž terozki.',
'protect-default'         => '(důmyślny)',
'protect-fallback'        => 'Wymago pozwolynjo "$1"',
'protect-level-sysop'     => 'Ino admini',
'protect-summary-cascade' => 'dźedźičyńy',
'protect-expiring'        => 'wygaso $1 (UTC)',
'restriction-type'        => 'Pozwolyńy:',
'restriction-level'       => 'Poźům:',

# Restrictions (nouns)
'restriction-edit' => 'Sprowjéj',

# Undelete
'undeletebtn' => 'Uodtwůř',

# Namespace form on various pages
'namespace'      => 'Přestřyń nazw:',
'invert'         => 'Wybjer na uopy',
'blanknamespace' => '(přodńo)',

# Contributions
'contributions' => 'Wkuod užytkowńika',
'mycontris'     => 'Bezy mje sprowjône',
'contribsub2'   => 'Do užytkowńika $1 ($2)',
'uctop'         => '(uostatnio)',
'month'         => 'Uod mjeśůnca (i downiyjše):',
'year'          => 'Uod roku (i dowńijše):',

'sp-contributions-newbies-sub' => 'Dlo nowych užytkowńikůw',
'sp-contributions-blocklog'    => 'zawarća',

# What links here
'whatlinkshere'       => 'Co sam linkuje',
'whatlinkshere-title' => 'Zajty kiere sům adrésowane do $1',
'linklistsub'         => '(Lista linków)',
'linkshere'           => "Nastympůjůnce zajty sóm adrésůwane do '''[[:$1]]''':",
'nolinkshere'         => "Žodno zajta ńy je adrésowana do '''[[:$1]]'''.",
'isredirect'          => 'překerowujůnca zajta',
'istemplate'          => 'douůnčona šablôna',
'whatlinkshere-prev'  => '{{PLURAL:$1|popředńe|popředńe $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|nastympne|nastympne $1}}',
'whatlinkshere-links' => '← do adrésata',

# Block/unblock
'blockip'            => 'Zawřij sprowjyńo užytkowńikowi',
'ipbreason'          => 'Čymu:',
'ipbreasonotherlist' => 'Inkszy powůd',
'ipboptions'         => '2 godźiny:2 hours,1 dźyń:1 day,3 dńi:3 days,1 tydźyń:1 week,2 tygodńy:2 weeks,1 mjeśůnc:1 month,3 mjeśůnce:3 months,6 mjeśency:6 months,1 rok:1 year,ńyskůńčůny:infińite', # display1:time1,display2:time2,...
'ipbotherreason'     => 'Inkšy powůd:',
'ipblocklist'        => 'Lista užytkowńikůw i adresůw IP ze zawartymi sprowjyńami',
'blocklink'          => 'zablokuj',
'unblocklink'        => 'uodymknij',
'contribslink'       => 'wkůod',
'blocklogpage'       => 'Historyja zawarć',
'blocklogentry'      => 'zawarto [[$1]], bydźe uodymkńynty: $2 $3',

# Move page
'move-page-legend' => 'Přećiś artikel',
'movearticle'      => 'Přećiś artikel:',
'newtitle'         => 'Nowy titel:',
'move-watch'       => 'Dej pozor',
'movepagebtn'      => 'Přećiś artikel',
'pagemovedsub'     => 'Přećiśńjyńće gotowe',
'movepage-moved'   => '<big>\'\'\'"$1" přećiśńjynto ku "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'    => 'Artikel s takym mjanym juž je, abo mjano je zue.
Wybjer inkše mjano.',
'movedto'          => 'přećiśńjynto ku',
'movetalk'         => 'Přećiś godke, jak možno.',
'talkpagemoved'    => 'Godka artikla zostoua přećiśńjynto.',
'talkpagenotmoved' => 'Godka artikla <strong>njy</strong> zostoua přećiśńjynto.',
'1movedto2'        => '[[$1]] přećepano do [[$2]]',
'movelogpage'      => 'Přećepńynte',
'movereason'       => 'Čymu:',
'revertmove'       => 'cofej',

# Export
'export' => 'Export zajtůw',

# Namespace 8 related
'allmessages' => 'Komunikaty',

# Thumbnails
'thumbnail-more'  => 'Powjynkš',
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
'tooltip-n-mainpage'              => 'Přelyź na Přodńo zajta',
'tooltip-n-portal'                => 'Uo projekće, co sam možeš majštrować, kaj idźe znolyźć informacyje',
'tooltip-n-currentevents'         => 'Informacyje uo aktualnych wydařyńach',
'tooltip-n-recentchanges'         => 'Lista pomjéńanych na űostatku na wiki',
'tooltip-n-randompage'            => 'Pokož losowo zajta',
'tooltip-n-help'                  => 'Zapoznej sie z obsůgą wiki',
'tooltip-n-sitesupport'           => 'Wspůmůž nas',
'tooltip-t-whatlinkshere'         => 'Pokož lista zajtůw kiere sam linkują',
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

# Browsing diffs
'previousdiff' => '← Popředńy sprowjyńy',
'nextdiff'     => 'Nostympno dyferéncyjo →',

# Media information
'file-info-size'       => '($1 × $2 pikseli, rozmior plika: $3, typ MIME: $4)',
'file-nohires'         => '<small>Uobrozek we wjynkšej rozdźelčośći ńy je dostympny.</small>',
'svg-long-desc'        => '(Plik SVG, nůminalńe $1 × $2 pixelůw, rozmior plika: $3)',
'show-big-image'       => 'Oryginalno rozdźelčość',
'show-big-image-thumb' => '<small>Rozmiar podglůndu: $1 × $2 pikseli</small>',

# Special:Newimages
'newimages' => 'Galerjo nowych uobrozkůw',

# Bad image list
'bad_image_list' => 'Dane noležy prowadźić we formaće:

Ino elementy tyi listy (linie kiere majom na přodku *) bierymy pod uwoga.
Pjerwšy link w lińii muśi być linkym do zabrůńůnygo pliku.
Nostympne linki w lińii uwažůmy za wyjůntki, to sům nazwy zajtůw, kaj plik uo zakozanyj nazwje idźe wstawić.',

# Metadata
'metadata'          => 'Metadane',
'metadata-help'     => 'Tyn plik zawjyro extra dane, kiere dodou aparat cyfrowy abo skaner. Jak coś we pliku bůuo půmjyńane, te extra dane můgům być ńyakuratne.',
'metadata-expand'   => 'Pokož ščygůuy',
'metadata-collapse' => 'Schowej ščygůuy',
'metadata-fields'   => 'Pola kere wymjyńůno pońižy pola EXIF bydům wymjyńůne na zajcie grafiki. Inkše pola bydům důmyślńy schowane.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# External editor support
'edit-externally'      => 'Edytuj tyn plik bez zewnyntřno aplikacyjo',
'edit-externally-help' => 'Zoboč wjyncyj informacyji uo užywańu [http://meta.wikimedia.org/wiki/Help:External_editors zewnyntřnych edytorůw].',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'do kupy',
'namespacesall' => 'wšyskie',
'monthsall'     => 'wšyskie',

# Watchlist editing tools
'watchlisttools-view' => 'Pokož wažńijše pomjyńańo',
'watchlisttools-edit' => 'Pokož i zmjyńoj pozorliste',
'watchlisttools-raw'  => 'Zmjyńoj surowo pozorlista',

# Special:Version
'version'                  => 'Wersjo', # Not used as normal message but as header for the special page itself
'version-version'          => 'Wersjo',
'version-software-version' => 'Wersjo',

);
