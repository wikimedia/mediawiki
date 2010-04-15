<?php
/** Kashubian (Kaszëbsczi)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Kaszeba
 * @author Kuvaly
 * @author Leinad
 * @author MinuteElectron
 * @author Warszk
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Specjalnô',
	NS_TALK             => 'Diskùsëjô',
	NS_USER             => 'Brëkòwnik',
	NS_USER_TALK        => 'Diskùsëjô_brëkòwnika',
	NS_PROJECT_TALK     => 'Diskùsëjô_$1',
	NS_FILE             => 'Òbrôzk',
	NS_FILE_TALK        => 'Diskùsëjô_òbrôzków',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Diskùsëjô_MediaWiki',
	NS_TEMPLATE         => 'Szablóna',
	NS_TEMPLATE_TALK    => 'Diskùsëjô_Szablónë',
	NS_HELP             => 'Pòmòc',
	NS_HELP_TALK        => 'Diskùsëjô_Pòmòcë',
	NS_CATEGORY         => 'Kategòrëjô',
	NS_CATEGORY_TALK    => 'Diskùsëjô_Kategòrëji',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Pòdsztrëchiwùjë lënczi:',
'tog-highlightbroken'         => 'Fòrmatëje pùsti lënczi <a href="" class="new">jak nen</a> (alternatiwno: jak nen<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Wërównanié (justifikacëjô) paragrafów',
'tog-hideminor'               => 'Zatacë môłi edicëje w slédnëch zmianach',
'tog-extendwatchlist'         => 'Rozszérzë lëstã ùzérónëch artiklów bë pòkazac wszëtczé zmianë',
'tog-usenewrc'                => 'Rozszérzenié slédnëch zmianów (JavaScript)',
'tog-numberheadings'          => 'Aùtomatné numerowanié nôgłówków',
'tog-showtoolbar'             => 'Pòkażë lëstwã nôrzãdzów edicëji (JavaScript)',
'tog-editondblclick'          => 'Editëjë starnë bez dëbeltné klëkniãcé (JavaScript)',
'tog-editsection'             => 'Włącziwô edicëjã sekcëjów bez lënczi [edicëjô]',
'tog-editsectiononrightclick' => 'Włączë edicëjã sekcëji bez klëkniãcé prawą knąpą mëszë<br />na titlu sekcëji (JavaScript)',
'tog-showtoc'                 => 'Pòkażë spisënk zamkłoscë (dlô starnów z wicy jak 3 nôgłówkama)',
'tog-rememberpassword'        => 'Wdôrzë mòjé miono brëkòwnika na tim kòmpùtrze',
'tog-watchcreations'          => 'Dodôwôj starnë jaczé ùsôdzã do mòji lëstë ùzérónëch artiklów',
'tog-watchdefault'            => 'Dodôwôj starnë jaczé editëjã do mòji lëstë ùzérónëch artiklów',
'tog-watchmoves'              => 'Dodôwôj starnë jaczé przenoszã do mòji lëstë ùzérónëch artiklów',
'tog-watchdeletion'           => 'Dodôwôj starnë jaczé rëmóm do mòji lëstë ùzérónëch artiklów',
'tog-minordefault'            => 'Zaznaczë wszëtczé edicëje domëslno jakno môłé',
'tog-previewontop'            => 'Pòkażë pòdzérk przed kastką edicëji',
'tog-previewonfirst'          => 'Pòkażë pòdzérk ju przed pierszą edicëją',
'tog-nocache'                 => 'Wëłączë trzëmanié starnów w pamiãcë (caching)',
'tog-enotifwatchlistpages'    => 'Wëslë mie e-mail czedë starna jaką ùzéróm je zmieniwónô',
'tog-enotifusertalkpages'     => 'Wëslë mie e-mail czedë zmieniwónô je mòja starna diskùsëji',
'tog-enotifminoredits'        => 'Wëslë mie e-mail téż dlô môłich zmianów starnów',
'tog-enotifrevealaddr'        => 'Pòkażë mòją adresã e-mail w òdkôzëwùjącym mailu',
'tog-shownumberswatching'     => 'Pòkażë lëczba ùzérającëch brëkòwników',
'tog-fancysig'                => 'Prosti pòdpisënk (bez aùtomatnëch lënków)',
'tog-externaleditor'          => 'Brëkùjë domëslno bùtnowégò editora (blós dlô ekspertów, je nót ekstra nastôwów w twòjim kòmpùtrze)',
'tog-externaldiff'            => 'Brëkùjë domëslno bùtnowégò nôrzãdza diff (blós dlô ekspertów.je nót ekstra nastôwów w twòjim kòmpùtrze)',
'tog-showjumplinks'           => 'Włączë lënczi przëstãpù "òbaczë téż"',
'tog-uselivepreview'          => 'Brëkùjë wtimczasnegò pòdzérkù (JavaScript) (eksperimentalné)',
'tog-forceeditsummary'        => 'Pëtôj przed wéńdzenim do pùstégò pòdrechòwania edicëji',
'tog-watchlisthideown'        => 'Zatacë mòjé edicëje z lëstë ùzérónëch artiklów',
'tog-watchlisthidebots'       => 'Zatacë edicëje botów z lëstë ùzérónëch artiklów',
'tog-watchlisthideminor'      => 'Zatacë môłi zmianë z lëstë ùzérónëch artiklów',
'tog-nolangconversion'        => 'Wëłączë kònwersëjã wariantów',
'tog-ccmeonemails'            => 'Wëslë mie kòpije e-mailów jaczi sélóm do jinëch brëkòwników',
'tog-diffonly'                => 'Nie pòkazëjë zamkłoscë starnë niżi różnic',

'underline-always'  => 'Wiedno',
'underline-never'   => 'Nigdë',
'underline-default' => 'Domëslny przezérnik',

# Dates
'sunday'        => 'niedzéla',
'monday'        => 'pòniédzôłk',
'tuesday'       => 'wtórk',
'wednesday'     => 'strzoda',
'thursday'      => 'czwiôrtk',
'friday'        => 'piątk',
'saturday'      => 'sobòta',
'sun'           => 'nie',
'mon'           => 'pòn',
'tue'           => 'wtó',
'wed'           => 'str',
'thu'           => 'czw',
'fri'           => 'pią',
'sat'           => 'sob',
'january'       => 'stëcznik',
'february'      => 'gromicznik',
'march'         => 'strëmiannik',
'april'         => 'łżëkwiôt',
'may_long'      => 'môj',
'june'          => 'czerwińc',
'july'          => 'lëpinc',
'august'        => 'zélnik',
'september'     => 'séwnik',
'october'       => 'rujan',
'november'      => 'lëstopadnik',
'december'      => 'gòdnik',
'january-gen'   => 'stëcznika',
'february-gen'  => 'gromicznika',
'march-gen'     => 'strumiannika',
'april-gen'     => 'łżëkwiôta',
'may-gen'       => 'maja',
'june-gen'      => 'czerwińca',
'july-gen'      => 'lëpinca',
'august-gen'    => 'zélnika',
'september-gen' => 'séwnika',
'october-gen'   => 'rujana',
'november-gen'  => 'lëstopadnika',
'december-gen'  => 'gòdnika',
'jan'           => 'stë',
'feb'           => 'gro',
'mar'           => 'str',
'apr'           => 'łżë',
'may'           => 'maj',
'jun'           => 'cze',
'jul'           => 'lëp',
'aug'           => 'zél',
'sep'           => 'séw',
'oct'           => 'ruj',
'nov'           => 'lës',
'dec'           => 'gòd',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|Kategòrëjô|Kategòrëje}}',
'category_header'        => 'Artikle w kategòrëji "$1"',
'subcategories'          => 'Pòdkategòrëje',
'category-media-header'  => 'Media w kategòrëji "$1"',
'category-empty'         => "''Ta ktegòrëja nie zamëkô w se terô niżódnëch artiklów ni mediów.''",
'listingcontinuesabbrev' => 'kònt.',

'mainpagetext' => "'''MediaWiki òsta zainstalowónô.'''",

'about'         => 'Ò serwise',
'article'       => 'Artikel',
'newwindow'     => '(òtmëkô sã w nowim òczenkù)',
'cancel'        => 'Anulujë',
'moredotdotdot' => 'Wicy...',
'mypage'        => 'Mòja starna',
'mytalk'        => 'Diskùsëjô',
'anontalk'      => 'Diskùsëjô dlô ti IP-adresë',
'navigation'    => 'Nawigacëjô',
'and'           => '&#32;ë',

# Cologne Blue skin
'qbfind'         => 'Nalézë',
'qbbrowse'       => 'Przezeranié',
'qbedit'         => 'Edicëjô',
'qbpageoptions'  => 'Òptacëje starnë',
'qbpageinfo'     => 'Ò starnie',
'qbmyoptions'    => 'Mòje òptacëje',
'qbspecialpages' => 'Specjalné starnë',

# Vector skin
'vector-action-delete'     => 'Rëmôj',
'vector-namespace-help'    => 'Pòmòc',
'vector-namespace-image'   => 'Òbrôzk',
'vector-namespace-main'    => 'Artikel',
'vector-namespace-special' => 'Specjalnô starna',
'vector-namespace-user'    => 'Starna brëkòwnika',
'vector-view-edit'         => 'Edicëjô',
'vector-view-view'         => 'Czëtaniô',

'errorpagetitle'    => 'Brida',
'returnto'          => 'Wôrcë sã do starnë: $1.',
'tagline'           => 'Z {{SITENAME}}',
'help'              => 'Pòmòc',
'search'            => 'Szëkba',
'searchbutton'      => 'Szëkba',
'go'                => 'Biôj!',
'searcharticle'     => 'Biôj!',
'history'           => 'Historëjô starnë',
'history_short'     => 'Historëjô',
'updatedmarker'     => 'aktualizowóne òd mòji slédny wizytë',
'info_short'        => 'Wëdowiédza',
'printableversion'  => 'Wersëjô do drëkù',
'permalink'         => 'Prosti lënk',
'print'             => 'Drëkùjë',
'edit'              => 'Edicëjô',
'create'            => 'Ùsôdzë',
'editthispage'      => 'Editëjë ną starnã',
'create-this-page'  => 'Ùsôdzë nã starnã',
'delete'            => 'Rëmôj',
'deletethispage'    => 'Rëmôj tã starnã',
'protect'           => 'Zazychrëjë',
'protect_change'    => 'zmieni',
'protectthispage'   => 'Zazychrëjë nã starnã',
'unprotect'         => 'Òdzychrëjë',
'unprotectthispage' => 'Òdzychrëjë nã starnã',
'newpage'           => 'Nowô starna',
'talkpage'          => 'Diskùsëjô starnë',
'talkpagelinktext'  => 'Diskùsëjô',
'specialpage'       => 'Specjalnô starna',
'personaltools'     => 'Priwatné przërëchtënczi',
'postcomment'       => 'Dôj dopòwiesc',
'articlepage'       => 'Starna artikla',
'talk'              => 'Diskùsëjô',
'views'             => 'Pòdzérków',
'toolbox'           => 'Przërëchtënczi',
'userpage'          => 'Wëskrzëni starnã brëkòwnika',
'projectpage'       => 'Wëskrzëni stranã ùdbë',
'imagepage'         => 'Starna òbrôzka',
'mediawikipage'     => 'Wëskrzëni starnã wiadła',
'templatepage'      => 'Wëskrzëni starnã wëzdrzatkù',
'viewhelppage'      => 'Wëskrzëni starnã pòmòcë',
'categorypage'      => 'Wëskrzëni starnã kategòrëji',
'viewtalkpage'      => 'Starna diskùsëji',
'otherlanguages'    => 'W jinëch jãzëkach',
'redirectedfrom'    => '(Przeczerowóné z $1)',
'redirectpagesub'   => 'Przeczerëjë starnã',
'lastmodifiedat'    => 'Na starna bëła slédno editowónô ò $2, $1;',
'viewcount'         => 'Na starna je òbzéranô ju {{PLURAL:$1|jeden rôz|$1 razy}}',
'protectedpage'     => 'Starna je zazychrowónô',
'jumpto'            => 'Skòczë do:',
'jumptonavigation'  => 'nawigacëji',
'jumptosearch'      => 'szëkbë',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Ò {{SITENAME}}',
'aboutpage'            => 'Project:Ò_{{SITENAME}}',
'copyright'            => 'Zamkłosc hewòtny starnë je ùżëczónô wedle reglów $1.',
'copyrightpage'        => '{{ns:project}}:Ùsôdzkòwé_prawa',
'disclaimers'          => 'Prawné zastrzedżi',
'disclaimerpage'       => 'Project:General_disclaimer',
'edithelp'             => 'Pòmòc do edicëji',
'edithelppage'         => 'Help:Editowanié starnë',
'mainpage'             => 'Przédnô starna',
'mainpage-description' => 'Przédnô starna',
'portal'               => 'Pòrtal wëcmaniznë',
'portal-url'           => 'Project:Pòrtal wëcmaniznë',
'privacy'              => 'Priwatnota',
'privacypage'          => 'Project:Priwatnota',

'badaccess' => 'Procëmprawne ùdowierzenie',

'versionrequired'     => 'Wëmôgónô wersëjô $1 MediaWiki',
'versionrequiredtext' => 'Bë brëkòwac ną starnã wëmôgónô je wersëjô $1 MediaWiki. Òbaczë starnã [[Special:Version]]',

'ok'                      => 'Jo!',
'retrievedfrom'           => 'Z "$1"',
'youhavenewmessages'      => 'Môsz $1 ($2).',
'newmessageslink'         => 'nowe wiadła',
'newmessagesdifflink'     => 'slédnô zmiana',
'youhavenewmessagesmulti' => 'Môsz nowé klëczi: $1',
'editsection'             => 'Edicëjô',
'editold'                 => 'Edicëjô',
'viewsourceold'           => 'wëskrzëni zdrój',
'editlink'                => 'editëje',
'viewsourcelink'          => 'wëskrzëni zdrój',
'editsectionhint'         => 'Editëjë dzél: $1',
'toc'                     => 'Spisënk zamkłoscë',
'showtoc'                 => 'pokôż',
'hidetoc'                 => 'zatacë',
'viewdeleted'             => 'Òbaczë $1',
'feedlinks'               => 'Pòwrózk:',
'site-rss-feed'           => 'Pòwrózk RSS dlô $1',
'site-atom-feed'          => 'Pòwrózk Atom dlô $1',
'page-rss-feed'           => 'Pòwrózk RSS dlô "$1"',
'page-atom-feed'          => 'Pòwrózk Atom dlô "$1"',
'feed-atom'               => 'Atom',
'feed-rss'                => 'RSS',
'red-link-title'          => '$1 (felëje starna)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artikel',
'nstab-user'      => 'Starna brëkòwnika',
'nstab-special'   => 'Specjalnô starna',
'nstab-project'   => 'meta-starna',
'nstab-image'     => 'Òbrôzk',
'nstab-mediawiki' => 'ògłosënk',
'nstab-template'  => 'Szablóna',
'nstab-help'      => 'Pòmòc',
'nstab-category'  => 'Kategòrëjô',

# Main script and global functions
'nosuchactiontext'  => 'Programa MediaWiki nie rozpòznôwô taczi òperacëji jakô je w URL',
'nosuchspecialpage' => 'Nie da taczi specjalny starnë',

# General errors
'error'            => 'Fela',
'databaseerror'    => 'Fela w pòdôwkòwi baze',
'readonly'         => 'Baza pòdôwków je zablokòwónô',
'missing-article'  => 'W baze pòdôwków felëje zamkłosc starnë "$1" $2.

Zwëczajno je to sparłãczoné òdsélaniém do nieaktualnégò lënka nierównoscë dwóch wersëjów starnë abò do rëmniãti wersëji starnë.

Jeżlë tak nie je, mòżlëwé je, że je to problem sparłãczony z felą w softwôrze.
Mòże to zgłoszëc [[Special:ListUsers/sysop|sprôwnikòwi]], pòdając adresã URL.',
'internalerror'    => 'Bënowô fela',
'filecopyerror'    => 'Ni mòże skòpérowac lopka "$1" do "$2".',
'filerenameerror'  => 'Ni mòże zmienic miona lopka "$1" na "$2".',
'filedeleteerror'  => 'Ni mòże rëmac lopka "$1".',
'filenotfound'     => 'Ni mòże nalezc lopka "$1".',
'formerror'        => 'Fela: ni mòże wëslac fòrmùlara',
'badarticleerror'  => 'Nie dô zrobic ti akcëji na ti starnie.',
'badtitle'         => 'Òchëbny titel',
'badtitletext'     => 'Pòdóny titel starnë je òchëbny. Gwësno są w nim znaczi, chtërnëch brëkòwanié je zakôzané abò je pùsti.',
'viewsource'       => 'Zdrojowi tekst',
'viewsourcefor'    => 'dlô $1',
'editinginterface' => "'''ÒSTRZÉGA:''' Editëjesz starnã, jakô zamëkô w se tekst interfejsu softwôrë. Wszëtczé zmianë tu zrobioné bãdze widzec na interfejse jinszëch brëkòwników.
Przemëszlë dolmaczënié na [http://translatewiki.net/wiki/Main_Page?setlang=csb translatewiki.net], ekstra ùdbie lokalizacëji softwôrë MediaWiki.",

# Login and logout pages
'logouttext'                 => 'Të jes ju wëlogòwóny.
Mòżesz prôcowac z {{SITENAME}} jakno anonimòwi brëkòwnik abò wlogòwac sã jakno zaregistrowóny brëkòwnik.',
'yourname'                   => 'Miono brëkòwnika',
'yourpassword'               => 'Twòja parola',
'yourpasswordagain'          => 'Pòwtórzë parolã',
'remembermypassword'         => 'Wdôrzë mòjé miono brëkòwnika na tim kòmpùtrze',
'yourdomainname'             => 'Twòjô domena',
'login'                      => 'Wlogùjë mie',
'nav-login-createaccount'    => 'Logòwanié',
'loginprompt'                => "Brëkùjesz miec ''cookies'' (kùszczi) włączoné bë sã wlogòwac do {{SITENAME}}.",
'userlogin'                  => 'Logòwanié',
'userloginnocreate'          => 'Wlogùjë mie',
'logout'                     => 'Wëlogùjë mie',
'userlogout'                 => 'Wëlogòwanié',
'notloggedin'                => 'Felëje logòwóniô',
'nologin'                    => "Ni môsz kònta? '''$1'''.",
'nologinlink'                => 'Ùsôdzë kònto',
'createaccount'              => 'Założë nowé kònto',
'gotaccount'                 => "Masz ju kònto? '''$1'''.",
'gotaccountlink'             => 'Wlogùjë',
'createaccountmail'          => 'òb e-mail',
'badretype'                  => 'Wprowadzone parole jinaczą sã midze sobą.',
'userexists'                 => 'To miono brëkòwnika je ju w ùżëcym. Proszã wëbrac jiné miono.',
'loginerror'                 => 'Fela logòwaniô',
'loginsuccesstitle'          => 'ùdałé logòwanié',
'loginsuccess'               => 'Të jes wlogòwóny do {{SITENAME}} jakno "$1".',
'nosuchuser'                 => 'Nie dô brëkòwnika ò mionie "$1". 
Sprôwdzë pisënk abò [[Special:UserLogin/signup|ùsôdzë nowé kònto]].',
'nouserspecified'            => 'Mùszisz pòdac miono brëkòwnika.',
'wrongpassword'              => 'Lëchô parola.
Spróbùjë znowa.',
'wrongpasswordempty'         => 'Wpisónô parola je pùstô
Spróbùjë znowa.',
'passwordtooshort'           => 'Twòjô parola je lëchô abò za krótkô.
Mùszi zamëkac w se co nômni $1 {{PLURAL:$1|céch|céchë|céchów}} ë bëc jinszô jakno Twòje miono brëkòwnika.',
'mailmypassword'             => 'Wëslë nową parolã e-mailą',
'passwordremindertitle'      => 'Nowô doczasnô parola dlô {{SITENAME}}',
'passwordremindertext'       => 'Chtos (gwës Të, z adresë $1) pòprosëł ò wësłanié nowi 
parolë dlô {{SITENAME}} ($4). Aktualnô parola dlô brëkòwnika 
"$2" òsta ùsôdzonô ë nastôwionô jakno "$3". Jeżlë to bëło twòją 
jintencëją, mùszisz sã terô wlogòwac ë zmienic swòją parolã. 
Nowô parola je wôznô {{PLURAL:$5|dzéń|$5 dni}}. 
Jeżlë chto jinszi wësłôł to zapëtanié, abò pamiãtôsz swòją parolã 
ë chcesz jã dali bez zmianë brëkòwac, zjignorëje to wiadło ë 
robi dali ze starną parolą.',
'noemail'                    => 'W baze ni ma email-adresë dlô brëkòwnika "$1".',
'acct_creation_throttle_hit' => 'Môsz ùsôdzoné ju {{PLURAL:$1|1 kònto|$1 kontów}}. 
Ni mòżesz miec ju wicy.',
'emailauthenticated'         => 'Twòjô adresa e-mail òsta pòcwierdzonô $2 ò $3.',
'accountcreated'             => 'Konto założone',
'accountcreatedtext'         => 'Konto brëkòwnika dlô $1 je założone.',
'createaccount-title'        => 'Kònto ùsôdzoné dlô {{SITENAME}}',
'loginlanguagelabel'         => 'Jãzëk: $1',

# Password reset dialog
'oldpassword'               => 'Stôrô parola:',
'newpassword'               => 'Nowô parola',
'retypenew'                 => 'Napiszë nową parolã jesz rôz',
'resetpass-submit-loggedin' => 'Zmiana parolë',
'resetpass-submit-cancel'   => 'Anulujë',

# Edit page toolbar
'bold_sample'     => 'Wëtłëszczony drëk',
'bold_tip'        => 'Wëtłëszczony drëk',
'italic_sample'   => 'Ùchëłi tekst',
'italic_tip'      => 'Ùchëłi tekst (italic)',
'link_sample'     => 'Titel lënka',
'link_tip'        => 'Bënowi lënk',
'extlink_sample'  => 'http://www.example.com titel lënka',
'extlink_tip'     => 'Bùtnowi lënk (pamiãtôj ò http:// prefiks)',
'headline_sample' => 'Tekst nagłówka',
'headline_tip'    => 'Nagłówk 2 lédżi',
'math_sample'     => 'Wstôwi tuwò fòrmùłã',
'math_tip'        => 'Matematicznô fòrmùła (LaTeX)',
'nowiki_sample'   => 'Wstôw tuwò niesfòrmatowóny tekst',
'nowiki_tip'      => 'Ignorëjë wiki-fòrmatowanié',
'image_sample'    => 'Przëmiôr.jpg',
'image_tip'       => 'Òbsôdzony lopk (n.p. òbrôzk)',
'media_sample'    => 'Przëmiôr.ogg',
'media_tip'       => 'Lënk lopka',
'sig_tip'         => 'Twój pòdpisënk z datumã a czasã',
'hr_tip'          => 'Hòrizontalnô linijô (brëkùjë szpórowno)',

# Edit pages
'summary'                => 'Pòdrechòwanié:',
'subject'                => 'Téma/nagłówk:',
'minoredit'              => 'To je drobnô edicëjô',
'watchthis'              => 'Ùzérôj',
'savearticle'            => 'Zapiszë artikel',
'preview'                => 'Pòdzérk',
'showpreview'            => 'Wëskrzëni pòdzérk',
'showlivepreview'        => 'Pòdzérk',
'showdiff'               => 'Wëskrzëni zmianë',
'anoneditwarning'        => "'''Bôczë:''' Të nie je wlogòwóny. Twòjô adresa IP mdze zapisónô w historëji edicëji ti starnë.",
'blockedtitle'           => 'Brëkòwnik je zascëgóny',
'blockedtext'            => "'''Twòje kònto abò ë IP-adresa òstałë zablokòwóné.'''

Zablokòwôł je $1. 
Pòdónô przëczëna to:''$2''.

 * Zôczątk blokadë: $8
 * Kùńc blokadë: $6
 * Cél blokadë: $7


Bë zgwësnic sprawã zablokòwaniô mòżesz skòntaktowac sã z $1 abò jińszim [[{{MediaWiki:Grouppage-sysop}}|administratorã]].
Boczë, że të ni mòżesz stądka sélac e-mailów, jeżlë nié môsz jesz zaregisterowóné e-mailowé adresë w [[Special:Preferences|nastôwach]].
Twòjô aktualnô adresa IP to $3, a zablokòwónô adresa ID to #$5. 
Proszëmë pòdac wëższé pòdôłczi przë wszëtczich pëtaniach.",
'blockedoriginalsource'  => "Zdrój '''$1''' je niżi:",
'blockededitsource'      => "Tekst '''Twòjëch edicëji''' do '''$1''' je niżi:",
'whitelistedittitle'     => 'Bë editowac je nót sã wlogòwac',
'accmailtitle'           => 'Parola wësłónô.',
'accmailtext'            => 'Parola dlô "$1" je wësłónô do $2.',
'newarticle'             => '(Nowi)',
'newarticletext'         => "Môsz przëszłi z lënkù do starnë jaka jesz nie òbstoji.
Bë ùsôdzëc artikel, naczni pisac w kastce niżi (òb. [[{{MediaWiki:Helppage}}|starnã pòmòcë]]
dlô wicy wëdowiédzë).
Jeżlë jes të tuwò bez zmiłkã, le klëkni w swòjim przezérnikù knąpã '''nazôd'''.",
'anontalkpagetext'       => "----''To je starna dyskùsëji anonimòwiégò brëkòwnika, chtëren nie zrobił jesz kònta dlô se, abò gò nie brëkùje.
Abë gò rozpòznac, ùżëwómë adresów IP.
Takô adresa IP, mòże bëc brëkòwónô òb wiele lëdzy.
Jeżlë jes anonimòwim brëkòwnikã ë ùwôżôsz, że te wiadła nie są do ce sczerowóne, tedë [[Special:UserLogin|ùsôdzë nowé kònto]] abò zalogùje sã, bë niechac niezrozmeiniô z jinëma anonimòwima brëkòwnikama.''",
'clearyourcache'         => "'''Bôczë: Pò zapisanim, mòże bãdzesz mùszôł òminąc pamiãc przezérnika bë òbaczëc zmianë.''' 
'''Mozilla / Firefox / Safari:''' przëtrzëmôj ''Shift'' òbczas klëkaniô na ''Zladëjë znowa'', abò wcësni ''Ctrl-F5'' abò ''Ctrl-R'' (''Command-R'' na kòmpùtrach Mac); 
'''Konqueror:''': klëkni na knąpã ''Zladëjë znowa'', abò wcësni ''F5''; 
'''Opera:''' wëczëszczë pòdrãczną pamiãc w ''Tools→Preferences''; 
'''Internet Explorer:'''przëtrzëmôj ''Ctrl'' òbczas klëkaniô na ''Zladëjë znowa'', abò wcësni ''Ctrl-F5''.",
'updated'                => '(Zaktualnioné)',
'previewnote'            => "'''To je blós pòdzérk - artikel jesz nie je zapisóny!'''",
'editing'                => 'Edicëjô $1',
'editingsection'         => 'Edicëjô $1 (dzél)',
'explainconflict'        => 'Chtos sfórtowôł wprowadzëc swòją wersëjã artikla òbczôs Twòji edicëji. Górné pòle edicëji zamëkô w se tekst starnë aktualno zapisóny w pòdôwkòwi baze. Twòje zmianë są w dólnym pòlu edicëji. Bë wprowadzëc swòje zmianë mùszisz zmòdifikòwac tekst z górnégò pòla. <b>Blós</b> tekst z górnégò pòla mdze zapisóny w baze czej wcësniesz "Zapiszë".',
'yourtext'               => 'Twój tekst',
'yourdiff'               => 'Zjinaczi',
'copyrightwarning'       => "Bôczë, że wszëtczé edicëje w {{SITENAME}} są wprowadzané pòd zastrzégą $2 (òb. $1 dlô detalów). Jeżlë nie chcesz bë to co napiszesz bëło editowóné czë kòpijowóné, tedë nie zacwierdzôj nëch edicëjów.<br />Zacwierdzając zmianë dôwôsz parolã, że to co môsz napisóné je Twòjégò aùtorstwa, abò skòpijowóné z dostónków public domain abò jinëch wòlnëch licencëjów. '''NIE DODÔWÔJ CËZËCH TEKSTÓW BEZ ZEZWÒLENIÔ!'''",
'copyrightwarning2'      => "Bôczë, że wszëtczé edicëje w {{SITENAME}} mògą bëc editowóné, zmienióné abò rëmniãté bez jinëch brëkòwników. 
Jeżlë nie chcesz bë Twòja robòta bëła editowónô, tedë nie dodôwôj ji tuwò.<br />
Zacwierdzając zmianë dôwôsz zgòdã na to, że to co môsz napisóné je Twòjégò aùtorstwa, abò skòpijowóné z dostónków public domain abò jinëch wòlnëch licencëjów (zdrzë za detalama na $1). 
'''NIE DODÔWÔJ ROBÒTË CHRONIONY ÙSÔDZKÒWIMA PRAWAMA BEZ ZEZWÒLENIÔ!'''",
'readonlywarning'        => "'''BÔCZËNK: Pòdôwkòwô baza òsta sztërkòwô zablokòwónô dlô administracjowich célów. Ni mòże tej timczasã zapisac nowi wersëji artikla. 
Bédëjemë przeniesc ji tekst do priwatnégò lopka (wëtnij/wstôw) ë ùchòwac na pózni.'''

Administrator, chtëren jã zablokòwôł, pòdôł przëczënã: $1",
'templatesused'          => 'Szablónë ùżëti w tim artiklu:',
'template-protected'     => '(zazychrowónô)',
'template-semiprotected' => '(dzélowò zazychrowóné)',

# History pages
'currentrev'             => 'Aktualnô wersëjô',
'revisionasof'           => 'Wersëjô z $1',
'previousrevision'       => '← Stôrszô wersëjô',
'nextrevision'           => 'Nowszô wersëjô →',
'currentrevisionlink'    => 'Aktualnô wersëjô',
'cur'                    => 'aktualnô',
'last'                   => 'pòslédnô',
'page_first'             => 'zôczątk',
'page_last'              => 'kùńc',
'histlegend'             => 'Legenda: (aktualnô) = różnice w przërównanim do aktualny wersëje,
(wczasniészô) = różnice w przërównanim do wczasniészi wersëje, D = drobné edicëje',
'history-fieldset-title' => 'Przezérôj historëjã',
'histfirst'              => 'Stôrszé',
'histlast'               => 'Nowszé',

# Revision feed
'history-feed-item-nocomment' => '$1 ò $2',

# Revision deletion
'rev-delundel'   => 'pòkażë/zatacë',
'revdel-restore' => 'Zjinaczë widzawnotã',
'pagehist'       => 'Historëjô starnë',

# Merge log
'revertmerge' => 'Rozdzélë',

# Diffs
'history-title'           => 'Historëjô wersëji dlô "$1"',
'difference'              => '(różnice midzë wersëjama)',
'lineno'                  => 'Lëniô $1:',
'compareselectedversions' => 'Przërównôj wëbróné wersëje',
'editundo'                => 'doprowadzë nazôd',

# Search results
'searchresults'             => 'Skùtczi szëkbë',
'searchresults-title'       => 'Skùtczi szëkbë za "$1"',
'searchresulttext'          => 'Dlô dobëcô wicy wëdowiédzë ò szëkbie na {{GRAMMAR:D.lp|{{SITENAME}}}}, zdrzë na [[{{MediaWiki:Helppage}}|starnë pòmòcë]].',
'searchsubtitle'            => 'Skùtczi szëkbë za \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|starnë naczënającé sã òd "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|starnë, chtërné lënkùją do "$1"]])',
'notitlematches'            => 'Ni nalazłé w titlach',
'prevn'                     => 'wczasniészé {{PLURAL:$1|$1}}',
'nextn'                     => 'nôslédné {{PLURAL:$1|$1}}',
'viewprevnext'              => 'Òbaczë ($1 {{int:pipe-separator}} $2) ($3).',
'search-result-size'        => '$1 ({{PLURAL:$2|1 słowò|$2 słowa|$2 słów}})',
'search-section'            => '(dzél $1)',
'search-interwiki-caption'  => 'Sosterné ùdbë',
'search-interwiki-more'     => '(wicy)',
'search-mwsuggest-enabled'  => 'z sugestëjama',
'search-mwsuggest-disabled' => 'bez sugestëjów',
'powersearch'               => 'Szëkba',
'powersearch-legend'        => 'Awansowónô szëkba',
'powersearch-ns'            => 'Szëkba w rumach mionów:',
'powersearch-redir'         => 'Lësta przeczerowaniów',
'powersearch-field'         => 'Szëkba za',

# Quickbar
'qbsettings' => 'Sztrépk chùtczégò przistãpù',

# Preferences page
'preferences'           => 'Preferencëje',
'mypreferences'         => 'Mòje nastôwë',
'prefs-edits'           => 'Lëczba edicëjów:',
'prefsnologin'          => 'Felënk logòwóniô',
'changepassword'        => 'Zmiana parolë',
'prefs-skin'            => 'Wëzdrzatk',
'skin-preview'          => 'Pòdzérk',
'prefs-math'            => 'Matematika',
'datedefault'           => 'Felëje preferencëji',
'prefs-datetime'        => 'Datum ë czas',
'prefs-personal'        => 'Pòdôwczi brëkòwnika',
'prefs-rc'              => 'Slédné edicëje',
'prefs-watchlist'       => 'Lësta ùzérónëch artiklów',
'prefs-watchlist-days'  => 'Wielëna dniów dlô wëskrzëniwaniô na lësce ùzérónëch artiklów:',
'prefs-watchlist-edits' => 'Maksymalnô lëczba edicëjów do pòkazaniô w rozszérzoné lësce ùzérónëch artiklów:',
'prefs-misc'            => 'Jine',
'saveprefs'             => 'Zapiszë',
'resetprefs'            => 'Wëczëszczë niezapisóné zmianë',
'prefs-editing'         => 'Edicëjô',
'rows'                  => 'Régów:',
'columns'               => 'Kòlumnów:',
'searchresultshead'     => 'Szëkba',
'resultsperpage'        => 'Rezultatów na starnã:',
'contextlines'          => 'Régów na rezultat:',
'contextchars'          => 'Kòntekstów na régã:',
'stub-threshold'        => 'Greńca dlô fòrmatowaniô <a href="#" class="stub">lënków stubów</a>:',
'recentchangesdays'     => 'Kùli dni pòkazëwac w slédnëch edicëjach:',
'recentchangescount'    => 'Wielëna pòzycëji na lësce slédnëch edicëji',
'savedprefs'            => 'Twòjé nastôwë òstałë zapisóné.',
'timezonelegend'        => 'Czasowô cona',
'localtime'             => 'Môlowi czas:',
'timezoneoffset'        => 'Różnica¹:',
'servertime'            => 'Czas serwera:',
'guesstimezone'         => 'Wezmi z przezérnika',
'allowemail'            => 'Włączë mòżlewòtã sélaniô e-mailów òd jinëch brëkòwników',
'defaultns'             => 'Domëslno przeszëkùjë nôslédné rëmnotë mionów:',
'default'               => 'domëszlné',
'prefs-files'           => 'Lopczi',
'youremail'             => 'E-mail:',
'username'              => 'Miono brëkòwnika:',
'uid'                   => 'ID brëkòwnika:',
'yourrealname'          => 'Twòje jistné miono*',
'yourlanguage'          => 'Twój jãzëk:',
'yourvariant'           => 'Wariant:',
'yournick'              => 'Pòdpisënk:',
'badsig'                => 'Òchëbny pòdpisënk, sprôwdzë tadżi HTML.',
'badsiglength'          => 'Pòdpisënk je za dłudżi. 
Mô bëc mni jakno $1 {{PLURAL:$1|znak|znaczi/znaków}}.',
'gender-male'           => 'Chłop',
'gender-female'         => 'Białka',
'email'                 => 'E-mail',
'prefs-help-realname'   => 'Prôwdzewi miono je òptacjowé a czej je dôsz, òstanié ùżëté do pòdpisaniô Twòjégò wkłôdu',
'prefs-help-email'      => 'Adresa e-mail je òptacëjnô, zezwôlô równak sélac do ce nową parolã jak tã zabëjesz.
Mòżesz zezwòlëc jinszim brëkòwniką na łączbã z Tobą przez Twòją starnã abò starnã diskùsëji, bez mùszebnotë wëskrzënianiô swòjich pòdôwków.',

# User rights
'editinguser' => "Zmiana praw brëkòwnika '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",

# Groups
'group-sysop' => 'Sprôwnicë',

'group-user-member'  => 'Brëkòwnik',
'group-sysop-member' => 'Sprôwnicë',

'grouppage-sysop' => '{{ns:project}}:Administratorzë',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'editëjë tã starnã',

# Recent changes
'recentchanges'                  => 'Slédné edicëje',
'recentchangestext'              => 'Na starna prezentérëje historëjã slédnëch edicëjów w {{SITENAME}}.',
'recentchanges-feed-description' => 'Pòdstrzegô slédny zmianë w tim pòwrózkù.',
'rcnote'                         => "Niżi {{PLURAL:$1|nachôdô sã '''1''' slédnô zjinaka zrobionô|nachôdają sã '''$1''' slédné zjinaczi zrobioné|nachôdô sã '''$1''' slédnych zjinaków zrobionëch}} w {{PLURAL:$2|slédnégò dnia|slédnych '''$2''' dniach}}, rëchùjąc òd $5 dnia $4.",
'rcnotefrom'                     => "Niżi są zmianë òd '''$2''' (pòkazóné do '''$1''').",
'rclistfrom'                     => 'Pòkażë nowé zmianë òd $1',
'rcshowhideminor'                => '$1 môłé zmianë',
'rcshowhidebots'                 => '$1 botë',
'rcshowhideliu'                  => '$1 zalogòwónëch brëkòwników',
'rcshowhideanons'                => '$1 anonymòwëch brëkòwników',
'rcshowhidepatr'                 => '$1 òbzérónë edicëje',
'rcshowhidemine'                 => '$1 mòjé edicëje',
'rclinks'                        => 'Pòkażë slédnëch $1 zmianów zrobionëch òb slédné $2 dniów<br />$3',
'diff'                           => 'jinosc',
'hist'                           => 'hist.',
'hide'                           => 'zatacë',
'show'                           => 'pokôż',
'minoreditletter'                => 'D',
'newpageletter'                  => 'N',
'boteditletter'                  => 'b',
'rc-enhanced-expand'             => 'Pòkażë detale (wëmôgô JavaScript)',
'rc-enhanced-hide'               => 'Zatacë detale',

# Recent changes linked
'recentchangeslinked'          => 'Zmianë w dolënkòwónëch',
'recentchangeslinked-feed'     => 'Zmianë w dolënkòwónëch',
'recentchangeslinked-toolbox'  => 'Zmianë w dolënkòwónëch',
'recentchangeslinked-title'    => 'Zjinaczi w lënkòwónëch z "$1"',
'recentchangeslinked-backlink' => '← $1',
'recentchangeslinked-summary'  => "Niżi nachôdô sã lësta slédnëch zjinaków na lënkòwónëch starnach z pòdóny starnë (abò we wszëtczich starnach przënôleżącëch do pòdóny kategòrëji).
Starnë z [[Special:Watchlist|lëstë ùzérónëch artiklów]] są '''pògrëbioné'''.",
'recentchangeslinked-page'     => 'Miono starnë:',
'recentchangeslinked-to'       => 'Wëskrzëni zjinaczi nié na lënkòwónëch starnach, blós na starnach lënkùjącëch do pòdóny starnë',

# Upload
'upload'            => 'Wladënk lopka',
'uploadbtn'         => 'Wladëjë lopk',
'uploadnologin'     => 'Felënk logòwaniô',
'uploadtext'        => "Brëkùjë negò fòrmùlara do wladënkù lopków.
Jeżlë chcesz przezdrzec abò szëkac w dotenczas wladowónëch lopkach, biéj do [[Special:FileList|lësta lopków]]. Kòżdi wladënk je registrowóny w [[Special:Log/upload|registrze wladënkù]], a rëmniãcé w [[Special:Log/delete|registrze rëmaniô]].

Abë dodac lopk do starnë, ùżëjë ùniższegò lënka wedle nôslédnëch mùstrów:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Lopk.jpg]]</nowiki></tt>''' wëskrzëni całi lopk
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Lopk.png|200px|thumb|left|pòdpisënk òbrôzka]]</nowiki></tt>''' wëskrzëni z lewi starnë, przë ùbrzégù, miniaturkã w szérzë 200 pikslów w ramie, z nôdpisã 'pòdpisënk òbrôzka'
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Lopk.ogg]]</nowiki></tt>''' òtemknie prosti lënk do lopka bez wëskrzënianiô sómegò lopka",
'uploadlog'         => 'Lësta wladënków',
'uploadlogpage'     => 'Dołączoné',
'uploadlogpagetext' => 'Hewò je lësta slédno wladowónëch lopków.
Wszëtczé gòdzënë tikają conë ùniwersalnégò czasë.',
'filename'          => 'Miono lopka',
'filedesc'          => 'Òpisënk',
'fileuploadsummary' => 'Pòdrechòwanié:',
'filesource'        => 'Zdrój:',
'uploadedfiles'     => 'Wladowóné lopczi',
'badfilename'       => 'Miono òbrôzka zmienioné na "$1".',
'successfulupload'  => 'Wladënk darzëł sã',
'uploadwarning'     => 'Òstrzega ò wladënkù',
'savefile'          => 'Zapiszë lôpk',
'uploadedimage'     => 'wladënk: "$1"',
'uploaddisabled'    => 'Przeprôszómë! Mòżlëwòta wladënkù lopków na nen serwer òsta wëłączonô.',

# Special:ListFiles
'listfiles'      => 'Lësta òbrôzków',
'listfiles_user' => 'Brëkòwnik',

# File description page
'file-anchor-link'          => 'Òbrôzk',
'filehist'                  => 'Historëjô lopka',
'filehist-current'          => 'aktualny',
'filehist-datetime'         => 'Datum/Czas',
'filehist-user'             => 'Brëkòwnik',
'filehist-dimensions'       => 'Miara',
'filehist-filesize'         => 'Miara lopka',
'filehist-comment'          => 'Òpisënk',
'imagelinks'                => 'Lënczi lopka',
'linkstoimage'              => '{{PLURAL:$1|Hewò je starna jakô òdwòłëje|Hewò są starnë jaczé òdwòłëją}} sã do negò lopka:',
'nolinkstoimage'            => 'Niżódnô starna nie òdwòłëje sã do negò lopka.',
'uploadnewversion-linktext' => 'Wladëjë nową wersëjã negò lopka',

# List redirects
'listredirects' => 'Lësta przeczerowaniów',

# Unused templates
'unusedtemplates' => 'Pùsté szablónë',

# Random page
'randompage' => 'Kawlowô starna',

# Statistics
'statistics'              => 'Statisticzi',
'statistics-header-users' => 'Statistika brëkòwników',

'disambiguationspage' => 'Template:Starnë_ùjednoznacznieniô',

'doubleredirects' => 'Dëbeltné przeczérowania',

'brokenredirects' => 'Zerwóné przeczerowania',

'withoutinterwiki' => 'Starnë bez jãzëkòwich lënków',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bajt|bajtë|bajtów}}',
'nlinks'                  => '$1 {{PLURAL:$1|lënk|lënków}}',
'lonelypages'             => 'Niechóné starnë',
'uncategorizedpages'      => 'Starnë bez kategòrëji',
'uncategorizedcategories' => 'Kategòrëje bez kategòrizacëji',
'uncategorizedimages'     => 'Lopczi bez kategòrëji',
'uncategorizedtemplates'  => 'Szablónë bez kategòrëji',
'unusedcategories'        => 'Pùsté kategòrëje',
'unusedimages'            => 'Nie wëzwëskóné òbrôzczi',
'popularpages'            => 'Nôwidzalszé starnë',
'wantedpages'             => 'Nônótniészé starnë',
'shortpages'              => 'Nôkrótszé starnë',
'longpages'               => 'Nôdłëgszé starnë',
'protectedpages'          => 'Zazychrowóné starnë',
'listusers'               => 'Lësta brëkòwników',
'newpages'                => 'Nowé starnë',
'newpages-username'       => 'Miono brëkòwnika:',
'ancientpages'            => 'Nôstarszé starnë',
'move'                    => 'Przeniesë',
'movethispage'            => 'Przeniesë',
'notargettitle'           => 'Nie da taczi starnë',
'pager-newer-n'           => '{{PLURAL:$1|1 nowszi|$1 nowszé|$1 nowszich}}',
'pager-older-n'           => '{{PLURAL:$1|1 stôrszi|$1 stôrszé|$1 stôrszich}}',

# Book sources
'booksources'    => 'Ksążczi',
'booksources-go' => 'Biéj',

# Special:Log
'specialloguserlabel'  => 'Brëkòwnik:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'Lodżi',
'alllogstext'          => 'Sparłãczoné registrë wszëtczich ôrtów dzejaniô dlô {{SITENAME}}.
Mòżesz zawãżëc wëszłosc przez wëbranié ôrtu registru, miona brëkòwnika abò miona zajimny dlô ce starnë.',

# Special:AllPages
'allpages'          => 'Wszëtczé starnë',
'alphaindexline'    => '$1 --> $2',
'nextpage'          => 'Nôslédnô starna ($1)',
'allpagesfrom'      => 'Starnë naczënające sã na:',
'allarticles'       => 'Wszëtczé artikle',
'allinnamespace'    => 'Wszëtczé starnë (w rumie $1)',
'allnotinnamespace' => 'Wszëtczé starnë (nie w rumie $1)',
'allpagesprev'      => 'Przódnô',
'allpagesnext'      => 'Pòsobnô',
'allpagessubmit'    => 'Pòkôżë',
'allpagesprefix'    => 'Pòkôżë naczënającë sã òd:',

# Special:Categories
'categories' => 'Kategòrëje',

# Special:LinkSearch
'linksearch' => 'Bùtnowé lënczi',

# E-mail user
'emailuser'       => 'Wëslë e-maila do negò brëkòwnika',
'emailpage'       => 'Sélajë e-mail do brëkòwnika',
'defemailsubject' => 'E-mail òd {{SITENAME}}',
'noemailtitle'    => 'Felënk email-adresë',
'emailfrom'       => 'Òd:',
'emailto'         => 'Do:',
'emailsubject'    => 'Téma:',
'emailmessage'    => 'Wiadło:',
'emailsend'       => 'Wëslë',
'emailccme'       => 'Sélôj mie e-mailã kòpijã wiadła.',

# Watchlist
'watchlist'            => 'Lësta ùzérónëch artiklów',
'mywatchlist'          => 'Lësta ùzérónëch artiklów',
'watchlistfor'         => "(dlô '''$1''')",
'watchnologin'         => 'Felënk logòwóniô',
'addedwatch'           => 'Dodónô do lëstë ùzérónëch',
'addedwatchtext'       => "Starna \"[[:\$1]]\" òsta dodónô do twòji [[Special:Watchlist|lëstë ùzérónëch artiklów]].
Na ti lësce są registre przińdnëch zjinak ti starne ë na ji starnie dyskùsëji, a samò miono starnë mdze '''wëtłëszczone''' na [[Special:RecentChanges|lësce slédnich edicëji]], bë të mògł to òbaczëc. 

Czej chcesz remôc starnã z lëste ùzéronëch artiklów, klikni ''Òprzestôj ùzérac''.",
'removedwatch'         => 'Rëmóné z lëstë ùzérónëch',
'watch'                => 'Ùzérôj',
'watchthispage'        => 'Ùzérôj ną starnã',
'unwatch'              => 'Òprzestôj ùzerac',
'unwatchthispage'      => 'Òprzestôj ùzerac ną starnã',
'notanarticle'         => 'To nie je artikel',
'watchlist-details'    => 'Ùzérôsz {{PLURAL:$1|$1 artikel|$1 artikle/-ów}}, nie rechùjąc diskùsëjów.',
'wlheader-showupdated' => "* Artiklë jakczé òsta zmienioné òd Twòji slédny wizytë są wëapratnioné '''pògrëbieniém'''",
'watchmethod-list'     => 'szëkba ùzérónëch artiklów westrzód pòslédnëch edicëjów',
'watchlistcontains'    => 'Na twòji lësce ùzérónëch artiklów {{PLURAL:$1|je 1 strana|są $1 starnë|je $1 starnów}}.',
'wlnote'               => "Niżi môsz wëskrzënioné {{PLURAL:$1|slédną zmianã|'''$1''' slédnëch zmianów}} zrobioné òb {{PLURAL:$2|gòdzënã|'''$2''' gòdzënë/gòdzënów}}.",
'wlshowlast'           => 'Pòkażë zmianë z $1 gòdzënów $2 dni $3',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ùzéróm...',
'unwatching' => 'Ju ni ùzéróm...',

'enotif_reset' => 'Òznaczë wszëtczé artiklë jakno òbëzdrzóné',
'changed'      => 'zmienioné',
'created'      => 'zrobionô',

# Delete
'deletepage'     => 'Rëmôj starnã',
'confirm'        => 'Pòcwierdzë',
'excontent'      => 'Zamkłosc starnë "$1"',
'actioncomplete' => 'Òperacëjô wëkònónô',
'deletedarticle' => 'rëmniãté przez "[[$1]]"',
'dellogpage'     => 'Rëmóné',
'deletionlog'    => 'register rëmaniów',
'deletecomment'  => 'Przëczëna rëmaniô',

# Rollback
'rollback'       => 'Copnij edicëjã',
'rollbacklink'   => 'copnij',
'rollbackfailed' => 'Nie szło copnąc zmianë',
'alreadyrolled'  => 'Ni mòże copnąc slédny edicëji starnë [[:$1]], chtërny ùsôdzcą je [[User:$2|$2]] ([[User talk:$2|Diskùsëjô]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
chtos jiny ju zeditowôł starnã abò copnął zmianë.

Slédnym ùsódzcą starnë bëł [[User:$3|$3]] ([[User talk:$3|Diskùsëjô]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',

# Protect
'protectedarticle'            => 'zazychrowónô [[$1]]',
'unprotectedarticle'          => 'òdzychrowóny [[$1]]',
'prot_1movedto2'              => '$1 przeniesłé do $2',
'protect-legend'              => 'Pòcwierdzë zazychrowanié',
'protectcomment'              => 'Przëczëna zazychrowóniô',
'protectexpiry'               => 'Wëgasô pò:',
'protect_expiry_invalid'      => 'Lëchi czas wëgasniãcô.',
'protect_expiry_old'          => 'Czas wëgasniãcô leżi w przińdnocë.',
'protect-default'             => 'Zezwòlë wszëtczim brëkòwnikòm',
'protect-fallback'            => 'Wëmôgô prawów "$1"',
'protect-level-autoconfirmed' => 'Blokùjë nowich ë nieregistrowónëch brëkòwników',
'protect-level-sysop'         => 'blós sprôwnicë (sysopë)',
'protect-summary-cascade'     => 'kaskadowanié',
'protect-expiring'            => 'wëgasô $1 (UTC)',
'protect-cascade'             => 'Zazychrëjë wszëtczé starnë zamkłé na ti starnie (kaskadowé zazychrowanié)',
'protect-cantedit'            => 'Ni mòżesz zmieniac lédżi zazychrowaniô ti starnë, kò ni môsz dosc prawa do ji edicëji.',
'restriction-type'            => 'Przistãp:',
'restriction-level'           => 'Léga bezpieczi:',

# Undelete
'viewdeletedpage' => 'Òbaczë rëmóne starnë',
'undeletebtn'     => 'Doprowôdzë nazôd',
'undeletelink'    => 'doprowôdzë nazôd',

# Namespace form on various pages
'namespace'      => 'Rum mionów:',
'invert'         => 'Òdwrócë zaznaczenié',
'blanknamespace' => '(Przédnô)',

# Contributions
'contributions' => 'Wkłôd brëkòwników',
'mycontris'     => 'Mòje edicëje',
'contribsub2'   => 'Dlô brëkòwnika $1 ($2)',
'uctop'         => '(slédnô)',
'month'         => 'Òd miesąca (ë wczasni):',
'year'          => 'Òd rokù (ë wczasni):',

'sp-contributions-newbies'     => 'Pòkażë edicëjã blós nowich brëkòwników',
'sp-contributions-newbies-sub' => 'Dlô nowich brëkòwników',
'sp-contributions-talk'        => 'Diskùsëjô',
'sp-contributions-search'      => 'Szëkba za edicëjama',
'sp-contributions-username'    => 'Adresa IP abò miono brëkòwnika:',
'sp-contributions-submit'      => 'Szëkôj',

# What links here
'whatlinkshere'         => 'Lënkùjącé',
'whatlinkshere-title'   => 'Starnë lënkùjącé do "$1"',
'whatlinkshere-page'    => 'Starna:',
'linkshere'             => "Do '''[[:$1]]''' lënkùją hewòtné starnë:",
'nolinkshere'           => "Niżódnô starna nie lënkùje do '''[[:$1]]'''.",
'isredirect'            => 'starna przeczerowaniô',
'whatlinkshere-links'   => '← lëkùjącé',
'whatlinkshere-filters' => 'Filtrë',

# Block/unblock
'blockip'            => 'Zascëgôj IP-adresã',
'blockiptext'        => 'Brëkùje formùlarza niżi abë zascëgòwac prawò zapisënkù spòd gwësny adresë IP. To robi sã blós dlôte abë zascëgnąc wandalëznom, a bëc w zgòdze ze [[{{MediaWiki:Policy-url}}|wskôzama]]. Pòdôj przëczënã (np. dając miona starn, na chtërnëch dopùszczono sã wandalëzny).',
'ipbreason'          => 'Przëczëna',
'badipaddress'       => 'IP-adresa nie je richtich pòdónô.',
'blockipsuccesssub'  => 'Zascëgónié dało sã',
'blockipsuccesstext' => 'Brëkòwnik [[Special:Contributions/$1|$1]] òstał zascëgóny.<br />
Biéj do [[Special:IPBlockList|lëstë zascëgónëch adresów IP]] abë òbaczëc zascëdżi.',
'ipblocklist'        => 'Lësta zablokòwónëch adresów IP ë mionów brëkòwników',
'blocklistline'      => '$1, $2 zascëgôł $3 ($4)',
'blocklink'          => 'blokùjë',
'unblocklink'        => 'òdblokùjë',
'change-blocklink'   => 'zmieni blokòwanié',
'contribslink'       => 'wkłôd',
'autoblocker'        => 'Zablokòwóno ce aùtomatnie, ga brëkùjesz ti sami adresë IP co brëkòwnik "[[User:$1|$1]]". Przëczënô blokòwóniô $1 to: "\'\'\'$2\'\'\'".',
'proxyblocksuccess'  => 'Fertich.',

# Developer tools
'lockbtn' => 'Zascëgôj bazã pòdôwków',

# Move page
'move-page-legend'        => 'Przeniesë starnã',
'movearticle'             => 'Przeniesë artikel',
'movenologin'             => 'Felënk logòwaniô',
'newtitle'                => 'Nowi titel:',
'move-watch'              => 'Ùzérôj tã starnã',
'movepagebtn'             => 'Przeniesë starnã',
'pagemovedsub'            => 'Przeniesenié darzëło sã',
'movepage-moved'          => '\'\'\'"$1" òsta przeniosłô do "$2"\'\'\'',
'articleexists'           => 'Starna ò taczim mionie ju je abò nie je òno bezzmiłkòwé. Wëbierzë nowé miono.',
'movedto'                 => 'przeniesłô do',
'movetalk'                => 'Przeniesë téż starnã <i>Diskùsëje</i>, jeżle je to mòżlëwé.',
'1movedto2'               => '$1 przeniesłé do $2',
'1movedto2_redir'         => '[[$1]] przeniesłé do [[$2]] nad przeczérowanim',
'movereason'              => 'Przëczëna:',
'revertmove'              => 'copnij',
'delete_and_move'         => 'Rëmôj ë przeniesë',
'delete_and_move_confirm' => 'Jo, rëmôj ną starnã',

# Export
'export' => 'Ekspòrt starnów',

# Namespace 8 related
'allmessages'               => 'Wszëtczé systemòwé ògłosë',
'allmessagesname'           => 'Miono',
'allmessagesdefault'        => 'Domëslny tekst',
'allmessagescurrent'        => 'Terny tekst',
'allmessagestext'           => 'To je zestôwk systemòwëch ògłosów przistãpnëch w rumie mion MediaWiki.
Proszã zazdrzë na [http://www.mediawiki.org/wiki/Localisation Lokalizacëjô MediaWiki] ë [http://translatewiki.net translatewiki.net] jeżlë chcesz dolmaczëc softwôrã MediaWiki.',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:Allmessages''' nie mòże bëc brëkòwónô, temù że '''\$wgUseDatabaseMessages''' je wëłączony.",

# Thumbnails
'thumbnail-more' => 'Zwiszi',

# Special:Import
'import' => 'Impòrtëjë starnë',

# Tooltip help for the actions
'tooltip-pt-userpage'           => 'Twòja starna brëkòwnika',
'tooltip-pt-mytalk'             => 'Twòjô starna diskùsëji',
'tooltip-pt-preferences'        => 'Mòje nastôwë',
'tooltip-pt-watchlist'          => 'Lësta artiklów jaczé òbzérôsz za zmianama',
'tooltip-pt-mycontris'          => 'Lësta twòjich edicëjów',
'tooltip-pt-login'              => 'Rôczimë do wlogòwaniô sã, nie je to równak mùszebné.',
'tooltip-pt-logout'             => 'Wëlogòwanié',
'tooltip-ca-talk'               => 'Diskùsëjô zamkłoscë ti starnë',
'tooltip-ca-edit'               => 'Mòżesz editowac nã starnã.
Proszã brëkòwac knąpë pòdzérkù przed zapisaniém.',
'tooltip-ca-viewsource'         => 'Na starna je zazychrowónô.
Mòżesz òbaczëc ji zdrój.',
'tooltip-ca-history'            => 'Stôrszé wersëje ti starnë',
'tooltip-ca-protect'            => 'Zazychrëjë nã starnã',
'tooltip-ca-delete'             => 'Rëmôj nã starnã',
'tooltip-ca-move'               => 'Przeniesë starnã',
'tooltip-ca-watch'              => 'Dodôj nã starnã do twòji lëstë ùzéraniô',
'tooltip-ca-unwatch'            => 'Rëmôj nã starnã z twòji lëstë ùzéraniô',
'tooltip-search'                => 'Szëkba {{SITENAME}}',
'tooltip-search-go'             => 'Biéj do starnë z akùratno taczim mionã, jeżlë takô je',
'tooltip-search-fulltext'       => 'Szëkba za wpisónym tesktã na starnach',
'tooltip-n-mainpage'            => 'Òbôczë przédną starnã',
'tooltip-n-portal'              => 'Ò ti ùdbie, co mòżesz zrobic, co a gdze mòżesz nalezc.',
'tooltip-n-currentevents'       => 'Dobëjë spódkòwą wëdowiédzã ò slédnych wëdarzeniach',
'tooltip-n-recentchanges'       => 'Lësta slédnych zjinaków na ti wikipedijë.',
'tooltip-n-randompage'          => 'Wëskrzëni kawlową starnã',
'tooltip-n-help'                => 'Wëskrzëni starnë pòmòcë.',
'tooltip-t-whatlinkshere'       => 'Lësta wszëtczich starnów wiki lënkùjącëch tuwò',
'tooltip-t-recentchangeslinked' => 'Slédné zjinaczi na starnach, do chtërnëch na starna lënkùje',
'tooltip-feed-rss'              => 'Pòwrózk RSS dlô ti starnë',
'tooltip-feed-atom'             => 'Pòwrôzk Atom dlô ti starnë',
'tooltip-t-emailuser'           => 'Wëslë e-mail do tegò brëkòwnika',
'tooltip-t-upload'              => 'Wladëjë lopczi',
'tooltip-t-specialpages'        => 'Lësta specjalnëch starnów',
'tooltip-t-print'               => 'Wersëjô ti starnë do drëkù',
'tooltip-t-permalink'           => 'Prosti lënk do ti wersëji starnë',
'tooltip-ca-nstab-main'         => 'Wëskrzëni starnã zamkłoscë',
'tooltip-ca-nstab-user'         => 'Wëskrzëni starnã brëkòwnika',
'tooltip-ca-nstab-special'      => 'To je specjlanô starna, chtërny ni mòżesz editowac',
'tooltip-ca-nstab-project'      => 'Òbôczë starnã ùdbë',
'tooltip-ca-nstab-image'        => 'Wëskrzëni starnã lopka',
'tooltip-ca-nstab-help'         => 'Wëskrzëni starnã pòmòcë',
'tooltip-ca-nstab-category'     => 'Wëskrzëni starnã kategòrëji',
'tooltip-save'                  => 'Zapiszë zmianë',
'tooltip-preview'               => 'Proszã òbôczëc zmianë w pòdzérkù przed jich zapisaniém!',
'tooltip-diff'                  => 'Wëskrzëni zjinaczi wprowôdzoné w teksce.',
'tooltip-watch'                 => 'Dodôj ną starnã do lëstë ùzérónëch',

# Attribution
'anonymous'        => 'Anonimòwi {{PLURAL:$1|brëkòwnik|brëkòwnicë}} na {{SITENAME}}',
'siteuser'         => 'Brëkòwnik {{SITENAME}} $1',
'lastmodifiedatby' => 'Na starna bëła slédno editowónô $2, $1 przez $3.',
'othercontribs'    => 'Òpiarté na prôcë $1.',
'others'           => 'jiné',

# Spam protection
'spamprotectiontitle' => 'Anti-spamòwi filter',

# Math options
'mw_math_png'    => 'Wiedno wëskrzëniwôj jakno PNG',
'mw_math_simple' => 'Jeżlë prosti wëskrzëniwôj jakno HTML, w jinëm przëtrôfkù jakno PNG',
'mw_math_html'   => 'HTML czej mòżlewé a w jinëm przëtrôfkù PNG',
'mw_math_source' => 'Òstawi jakno TeX (dlô tekstowich przezérników)',
'mw_math_modern' => 'Zalécóné dlô nowoczasnëch przezérników',
'mw_math_mathml' => 'Wëskrzëniwôj jakno MathML jeżlë mòżlëwé (eksperimentalné)',

# Math errors
'math_failure' => 'Parser nie rozmiôł rozpòznac',

# Browsing diffs
'previousdiff' => '← Pòprzédnô edicëjô',
'nextdiff'     => 'Nôslédnô edicëjô →',

# Media information
'imagemaxsize'   => 'Ògrańczë na starnie òpisënkù òbrôzków jich miarã do:',
'thumbsize'      => 'Miara miniaturków:',
'file-info-size' => '($1 × $2 pikslów, miara lopka: $3, ôrt MIME: $4)',
'file-nohires'   => '<small>Felëje wikszô miara.</small>',
'show-big-image' => 'Fùl miara',

# Special:NewFiles
'newimages' => 'Galerëjô nowich lopków',
'ilsubmit'  => 'Szëkôj',
'bydate'    => 'wedle datumù',

# Bad image list
'bad_image_list' => 'Fòrmat do wpisaniô je jakno niżi:

Blós elementë lëstë (réżczi naczynającé sã òd *) bãdą ùwzglãdniwóné.
Pierszi lënk w réżczi mùãzi bëc lënkã do zakazónegò lopka. 
Nôslédné lënczi w réżce bãdą ùwzglãdniwóné jakno wëjimczi – są to miona starnów, na chtërnëch lopk ò zakazónym mionie mòze bëc brëkòwóny.',

# Metadata
'metadata' => 'Pòdôwczi meta',

# External editor support
'edit-externally' => 'Editëjë nen lopk brëkùjąc bùtnowi aplikacëji',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'wszëtczé',
'imagelistall'     => 'wszëtczé',
'watchlistall2'    => 'wszëtczé',
'namespacesall'    => 'wszëtczé',
'monthsall'        => 'wszëtczé',

# E-mail address confirmation
'confirmemail_loggedin' => 'Twòjô adresa e-mail òsta pòcwierdzona.',

# Multipage image navigation
'imgmultigo' => 'Biéj!',

# Auto-summaries
'autoredircomment' => 'Przeczérowanié do [[$1]]',

# Watchlist editing tools
'watchlisttools-view' => 'Òbaczë wôżnészé zmianë',
'watchlisttools-edit' => 'Òbaczë a editëjë lëstã ùzérónëch artiklów',
'watchlisttools-raw'  => 'Editëjë sërą lëstã',

# Special:Version
'version' => 'Wersëjô',

# Special:SpecialPages
'specialpages' => 'Specjalné starnë',

);
