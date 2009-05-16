<?php
/** Tetum (Tetun)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author MF-Warburg
 */

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Espesiál',
	NS_TALK           => 'Diskusaun',
	NS_USER           => 'Uza-na\'in',
	NS_USER_TALK      => 'Diskusaun_Uza-na\'in',
	NS_PROJECT_TALK   => 'Diskusaun_$1',
	NS_FILE           => 'Imajen',
	NS_FILE_TALK      => 'Diskusaun_Imajen',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'Diskusaun_MediaWiki',
	NS_TEMPLATE       => 'Template',
	NS_TEMPLATE_TALK  => 'Diskusaun_Template',
	NS_HELP           => 'Ajuda',
	NS_HELP_TALK      => 'Diskusaun_Ajuda',
	NS_CATEGORY       => 'Kategoria',
	NS_CATEGORY_TALK  => 'Diskusaun_Kategoria',
);

$namespaceAliases = array(
	"Kategoría"           => NS_CATEGORY,
	"Diskusaun_Kategoría" => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'CreateAccount'             => array( 'Rejista' ),
	'Preferences'               => array( 'Preferénsia' ),
	'Watchlist'                 => array( 'Lista hateke' ),
	'Recentchanges'             => array( 'Mudansa foufoun sira' ),
	'Upload'                    => array( 'Tau iha arkivu laran' ),
	'Listfiles'                 => array( 'Lista imajen' ),
	'Newimages'                 => array( 'Imajen foun' ),
	'Listusers'                 => array( 'Lista uza-na\'in' ),
	'Statistics'                => array( 'Estatístika' ),
	'Randompage'                => array( 'Pájina ruma' ),
	'Shortpages'                => array( 'Pájina badak' ),
	'Longpages'                 => array( 'Pájina naruk' ),
	'Protectedpages'            => array( 'Pájina sira-ne\'ebé proteje tiha' ),
	'Allpages'                  => array( 'Pájina hotu' ),
	'Ipblocklist'               => array( 'Lista ema sira-ne\'ebé blokeiu tiha' ),
	'Specialpages'              => array( 'Pájina espesiál sira' ),
	'Contributions'             => array( 'Kontribuisaun' ),
	'Emailuser'                 => array( 'Haruka korreiu eletróniku' ),
	'Whatlinkshere'             => array( 'Pájina sira ne\'ebé bá iha ne\'e' ),
	'Movepage'                  => array( 'Book' ),
	'Categories'                => array( 'Kategoria' ),
	'Export'                    => array( 'Esporta' ),
	'Version'                   => array( 'Versaun' ),
	'Allmessages'               => array( 'Mensajen hotu' ),
	'Blockip'                   => array( 'Blokeiu' ),
	'Undelete'                  => array( 'Restaurar' ),
	'Userrights'                => array( 'Kuana', 'Priviléjiu' ),
	'Mypage'                    => array( 'Ha\'u-nia pájina' ),
	'Listadmins'                => array( 'Lista administradór' ),
	'Search'                    => array( 'Buka' ),
	'Withoutinterwiki'          => array( 'Laiha interwiki' ),
);

$messages = array(
# User preference toggles
'tog-underline'          => 'Subliña ligasaun sira:',
'tog-highlightbroken'    => 'Formatu ligasaun sira-ne\'ebé bá pájina maka wiki la iha: <a href="" class="new">ne\'e</a> ka <a href="" class="internal">ne\'e</a>.',
'tog-justify'            => 'Justifika parágrafu sira',
'tog-hideminor'          => "Lá'os hatudu osan-rahun sira iha mudansa foufoun sira",
'tog-usenewrc'           => 'Uza lista "Mudansa foufoun sira" di\'ak liu (JavaScript)',
'tog-showtoolbar'        => 'Hatudu kaixa edita (JavaScript)',
'tog-watchcreations'     => "Hateke pájina sira-ne'ebé ha'u kria",
'tog-watchdefault'       => "Hateke pájina sira-ne'ebé ha'u edita",
'tog-watchmoves'         => "Hateke pájina sira-ne'ebé ha'u book",
'tog-watchdeletion'      => "Hateke pájina sira-ne'ebé ha'u halakon",
'tog-watchlisthideown'   => "La hatudu ha'u-nia edita iha lista hateke",
'tog-watchlisthidebots'  => 'Hamsumik bot iha lista hateke',
'tog-watchlisthideminor' => 'Hamsumik osan-rahun iha lista hateke',
'tog-watchlisthideliu'   => 'La hatudu edita ema rejista nian iha lista hateke',
'tog-watchlisthideanons' => 'La hatudu edita ema anónimu nian iha lista hateke',
'tog-showhiddencats'     => "Hatudu kategoria sira-ne'ebé subar",

'underline-always' => 'Sempre',
'underline-never'  => 'Nunka',

# Dates
'sunday'        => 'Loron-domingu',
'monday'        => 'Loron-segunda',
'tuesday'       => 'Loron-tersa',
'wednesday'     => 'Loron-kuarta',
'thursday'      => 'Loron-kinta',
'friday'        => 'Loron-sesta',
'saturday'      => 'Loron-sábadu',
'sun'           => 'Dom',
'mon'           => 'Seg',
'tue'           => 'Ter',
'wed'           => 'Kua',
'thu'           => 'Kin',
'fri'           => 'Ses',
'sat'           => 'Sáb',
'january'       => 'Janeiru',
'february'      => 'Fevereiru',
'march'         => 'Marsu',
'april'         => 'Abríl',
'may_long'      => 'Maiu',
'june'          => 'Juñu',
'july'          => 'Jullu',
'august'        => 'Agostu',
'september'     => 'Setembru',
'october'       => 'Outubru',
'november'      => 'Novembru',
'december'      => 'Dezembru',
'january-gen'   => 'Janeiru nian',
'february-gen'  => 'Fevereiru nian',
'march-gen'     => 'Marsu nian',
'april-gen'     => 'Abríl nian',
'may-gen'       => 'Maiu nian',
'june-gen'      => 'Juñu nian',
'july-gen'      => 'Jullu nian',
'august-gen'    => 'Agostu nian',
'september-gen' => 'Setembru nian',
'october-gen'   => 'Outubru nian',
'november-gen'  => 'Novembru nian',
'december-gen'  => 'Dezembru nian',
'jan'           => 'Jan',
'feb'           => 'Fev',
'mar'           => 'Mar',
'apr'           => 'Abr',
'may'           => 'Mai',
'jun'           => 'Jun',
'jul'           => 'Jul',
'aug'           => 'Ago',
'sep'           => 'Set',
'oct'           => 'Out',
'nov'           => 'Nov',
'dec'           => 'Dez',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|Kategoría|Kategoría}}',
'category_header'        => 'Artigu iha kategoría "$1"',
'subcategories'          => 'Sub-kategoria sira',
'category-empty'         => "''Kategoria ne'e agora la iha pájina sira.''",
'listingcontinuesabbrev' => 'kont.',

'about'          => 'Kona-ba',
'article'        => 'Pájina',
'cancel'         => 'Para',
'qbfind'         => 'Hetan',
'qbedit'         => 'Edita',
'qbpageoptions'  => "Pájina ne'e",
'qbmyoptions'    => "Ha'u-nia pájina sira",
'qbspecialpages' => 'Pájina espesiál sira',
'moredotdotdot'  => 'Barak liu...',
'mypage'         => "Ha'u-nia pájina",
'mytalk'         => "Ha'u-nia diskusaun",
'anontalk'       => "Diskusaun ba IP ne'e",
'navigation'     => 'Hatudu-dalan',
'and'            => '&#32;ho',

'errorpagetitle'    => 'Sala',
'returnto'          => 'Fali ba $1.',
'tagline'           => 'Husi {{SITENAME}}',
'help'              => 'Ajuda',
'search'            => 'Buka',
'searchbutton'      => 'Buka',
'go'                => 'Bá',
'searcharticle'     => 'Pájina',
'history'           => 'Istória pájina',
'history_short'     => 'Istória',
'info_short'        => 'Informasaun',
'printableversion'  => 'Versaun ba impresaun',
'permalink'         => 'Ligasaun mahelak',
'print'             => 'Imprime',
'edit'              => 'Edita',
'create'            => 'Kria',
'editthispage'      => "Edita pájina ne'e",
'create-this-page'  => "Kria pájina ne'e",
'delete'            => 'Halakon',
'deletethispage'    => "Halakon pájina ne'e",
'undelete_short'    => 'Restaurar {{PLURAL:$1|versaun ida|versaun $1}}',
'protect'           => 'Proteje',
'protect_change'    => 'filak',
'protectthispage'   => "Proteje pájina ne'e",
'unprotect'         => 'La proteje',
'unprotectthispage' => "La proteje pájina ne'e",
'newpage'           => 'Pájina foun',
'talkpage'          => "Diskusaun kona-ba pájina ne'e",
'talkpagelinktext'  => 'Diskusaun',
'specialpage'       => 'Pájina espesiál',
'postcomment'       => 'Seksaun foun',
'talk'              => 'Diskusaun',
'toolbox'           => 'Kaixa besi nian',
'userpage'          => "Haree pájina uza-na'in",
'projectpage'       => 'Haree pájina projetu nian',
'imagepage'         => 'Haree pájina imajen nian',
'mediawikipage'     => 'Haree pájina mensajen nian',
'viewhelppage'      => 'Haree pájina ajuda',
'categorypage'      => 'Haree pájina kategoría nian',
'viewtalkpage'      => 'Haree diskusaun',
'otherlanguages'    => 'Iha lian seluk',
'lastmodifiedat'    => "Ema ruma filak ikus pájina ne'e iha $1, $2.", # $1 date, $2 time
'protectedpage'     => 'Pájina maka ema ruma proteje tiha',
'jumpto'            => 'Bá:',
'jumptonavigation'  => 'hatudu-dalan',
'jumptosearch'      => 'buka',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Kona-ba {{SITENAME}}',
'aboutpage'            => 'Project:Kona-ba',
'copyright'            => 'Testu pájina nian iha $1 okos.',
'copyrightpagename'    => 'Direitu autór sira nian iha {{SITENAME}}',
'copyrightpage'        => '{{ns:project}}:Direitu_autór_nian',
'currentevents'        => 'Mamosuk atuál sira',
'currentevents-url'    => 'Project:Mamosuk atuál sira',
'disclaimers'          => 'Avisu legál',
'disclaimerpage'       => 'Project:Avisu legál',
'edithelp'             => 'Ajuda kona-ba edita',
'edithelppage'         => 'Help:Edita',
'faq'                  => 'FAQ',
'faqpage'              => 'Project:FAQ',
'helppage'             => 'Help:Konteúdu',
'mainpage'             => 'Pájina Mahuluk',
'mainpage-description' => 'Pájina Mahuluk',
'portal'               => 'Portál komunidade nian',
'portal-url'           => 'Project:Portál komunidade nian',
'privacy'              => 'Polítika privasidade nian',
'privacypage'          => 'Project:Polítika privasidade nian',

'badaccess'        => 'Sala priviléjiu nian',
'badaccess-group0' => "Ó la bele halo ne'e.",
'badaccess-groups' => "Ba halo ne'e tenke iha {{PLURAL:$2|lubu|lubu ida husi}} $1.",

'versionrequired'     => 'Presiza MediaWiki versaun $1',
'versionrequiredtext' => "Presiza MediaWiki versaun $1 ba uza pájina ne'e. Haree [[Special:Version|pájina versaun]].",

'ok'                      => 'OK',
'retrievedfrom'           => 'Husi "$1"',
'youhavenewmessages'      => 'Ó iha $1 ($2).',
'newmessageslink'         => 'mensajen foun',
'newmessagesdifflink'     => 'diferensa foun liu hotu',
'youhavenewmessagesmulti' => 'Ó iha mensajen foun sira iha $1',
'editsection'             => 'edita',
'editold'                 => 'edita',
'viewsourceold'           => 'lee testu',
'editlink'                => 'edita',
'viewsourcelink'          => 'lee testu',
'editsectionhint'         => 'Edita parte $1 pájina nian',
'toc'                     => 'Tabela konteúdu',
'showtoc'                 => 'hatudu',
'hidetoc'                 => 'subar',
'thisisdeleted'           => 'Haree ka restaurar $1?',
'viewdeleted'             => 'Haree $1?',
'site-rss-feed'           => 'Feed RSS $1',
'site-atom-feed'          => 'Feed Atom $1',
'red-link-title'          => "$1 (pájina ne'e la iha)",

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Pájina',
'nstab-user'      => "Pájina uza-na'in",
'nstab-special'   => 'Pájina espesiál',
'nstab-project'   => 'Pájina projetu nian',
'nstab-mediawiki' => 'Mensajen',
'nstab-help'      => 'Pájina ajuda',
'nstab-category'  => 'Kategoria',

# Main script and global functions
'nosuchspecialpage' => "Pájina espesiál ne'e la iha",
'nospecialpagetext' => "<big>'''Pájina espesiál ne'e la iha.'''</big>

Lista ida pájina espesiál nian [[Special:SpecialPages|iha ne'e]].",

# General errors
'error'               => 'Sala',
'missingarticle-rev'  => '(version#: $1)',
'missingarticle-diff' => '(Dif.: $1, $2)',
'viewsource'          => 'Lee testu',
'viewsourcefor'       => 'ba $1',
'viewsourcetext'      => 'Ó bele lee no kopia testu pájina nian:',
'namespaceprotected'  => "Ó la iha priviléjiu ba edita pájina sira iha espasu '''$1'''.",
'ns-specialprotected' => 'La ema ida bele edita pájina espesiál sira.',

# Login and logout pages
'logouttitle'               => 'Husik',
'welcomecreation'           => "== Loron di'ak, $1! ==
Ó kria konta ó-nia.
La haluha filak ó-nia [[Special:Preferences|preferénsia]].",
'loginpagetitle'            => 'Log in',
'yourname'                  => "Naran uza-n'in:",
'login'                     => 'Log in',
'nav-login-createaccount'   => 'Log in / kriar konta ida',
'userlogin'                 => 'Log in / kriar konta ida',
'logout'                    => 'Husik',
'userlogout'                => 'Husik',
'nologin'                   => 'La iha konta ida? $1.',
'nologinlink'               => 'Registrar',
'createaccount'             => "Registrar uza-na'in",
'gotaccount'                => 'Ó iha konta ona? $1.',
'gotaccountlink'            => 'Log in',
'userexists'                => "Uza-na'in ne'e ona iha wiki. Favór ida lori naran seluk.",
'youremail'                 => 'Korreiu eletróniku:',
'username'                  => "Naran uza-na'in:",
'uid'                       => "Uza-na'in ID:",
'yourlanguage'              => 'Lian:',
'email'                     => 'Korreiu eletróniku',
'prefs-help-email-required' => 'Haruka diresaun korreiu eletróniku.',
'nosuchuser'                => 'Konta uza-na\'in (naran "$1") la iha.
User names are case sensitive.
Check your spelling, ka [[Special:UserLogin/signup|kria konta foun]].',
'nouserspecified'           => "Ó tenke espesífiku naran uza-na'in ida.",
'accountcreated'            => "Registrar tiha uza-na'in",
'loginlanguagelabel'        => 'Lian: $1',

# Password reset dialog
'resetpass_text' => "<!-- Hakerek testu iha ne'e -->",

# Edit page toolbar
'link_tip'     => 'Ligasaun ba laran',
'extlink_tip'  => "Ligasaun ba li'ur (tau tan http://)",
'image_sample' => 'Ezemplu.jpg',
'media_sample' => 'Ezemplu.ogg',

# Edit pages
'minoredit'              => "Ne'e osan-rahun",
'watchthis'              => "Hateke pájina ne'e",
'savearticle'            => 'Filak pájina',
'showdiff'               => 'Hatudu diferensa sira',
'anoneditwarning'        => 'Ó lá\'os "log-in" iha momentu.',
'blockedtitle'           => "Uza-na'in la bele edita (blokeiu)",
'blockednoreason'        => 'laiha motivu',
'whitelistedittext'      => 'Ó tenke $1 ba edita pájina sira.',
'loginreqpagetext'       => 'Ó tenke $1 ba haree pájina seluk.',
'newarticle'             => '(Foun)',
'noarticletext'          => "Iha momentu lá'os testu iha pájina ne'e, bele [[Special:Search/{{PAGENAME}}|buka naran pájina nian]] iha pájina seluk, <span class=\"plainlinks\">[{{fullurl:Special:Log|page={{urlencode:{{FULLPAGENAME}}}}}} search the related logs], ka [{{fullurl:{{FULLPAGENAME}}|action=edit}} edita pájina ne'e].",
'editing'                => 'Edita $1',
'editingcomment'         => 'Edita $1 (seksaun foun)',
'yourtext'               => 'Ó-nia testu',
'yourdiff'               => 'Diferensa sira',
'template-protected'     => '(proteje tiha)',
'template-semiprotected' => '(proteje tiha balun)',
'nocreatetext'           => "Ó la bele kria pájina foun iha {{SITENAME}}.
Ó bele edita pájina sira-ne'ebé {{SITENAME}} iha ona ka [[Special:UserLogin|log in ka kria konta uza-na'in]].",
'nocreate-loggedin'      => 'Ó la bele kria pájina foun.',
'permissionserrorstext'  => "Ó la bele halo ne'e; {{PLURAL:$1|motivu|motivu sira}}:",

# Account creation failure
'cantcreateaccounttitle' => "La bele registrar uza-na'in",

# History pages
'currentrev'          => 'Versaun atuál',
'revisionasof'        => 'Versaun $1 nian',
'previousrevision'    => '←Versaun tuan liu',
'nextrevision'        => 'Versaun foun liu→',
'currentrevisionlink' => 'Versaun atuál',
'cur'                 => 'atuál',
'next'                => 'oinmai',
'last'                => 'ikus',
'page_first'          => 'uluk',
'page_last'           => 'ikus',
'histfirst'           => 'sedu liu hotu',
'histlast'            => 'Foun liu hotu',
'historyempty'        => '(mamuk)',

# Revision feed
'history-feed-item-nocomment' => '$1 iha $2', # user at time

# Revision deletion
'rev-delundel'        => 'hatudu/subar',
'revisiondelete'      => 'Halakon/restaurar versaun',
'revdelete-hide-user' => "Subar naran edita-na'in/IP",

# Diffs
'lineno' => 'Liña $1:',

# Search results
'noexactmatch'       => "'''Pájina ''$1'' la iha.''' Ó bele [[:$1|kria pájina ne'e]].",
'prevn'              => 'molok $1',
'nextn'              => 'oinmai $1',
'viewprevnext'       => 'Haree ($1) ($2) ($3)',
'search-result-size' => '$1 ({{PLURAL:$2|liafuan ida|liafuan $2}})',
'searchall'          => 'hotu',
'powersearch'        => 'Buka',
'powersearch-field'  => 'Buka',

# Preferences page
'preferences'       => 'Preferénsia',
'mypreferences'     => "Ha'u-nia preferénsia",
'dateformat'        => 'Formatu tempu nian',
'prefs-rc'          => 'Mudansa foufoun sira',
'prefs-watchlist'   => 'Lista hateke',
'textboxsize'       => 'Edita',
'searchresultshead' => 'Buka',

# User rights
'userrights'               => "Filak priviléjiu uza-na'in sira", # Not used as normal message but as header for the special page itself
'userrights-lookup-user'   => "Filak lubu uza-na'in",
'userrights-user-editname' => "Hakerek naran uza-na'in ida-nian:",
'editusergroup'            => "Filak lubu uza-na'in",
'userrights-editusergroup' => "Filak lubu uza-na'in",
'userrights-groupsmember'  => 'Membru iha:',
'userrights-reason'        => 'Motivu ba filak:',
'userrights-no-interwiki'  => "Ó la bele filak priviléjiu uza-na'in iha wiki seluk.",

# Groups
'group'            => 'Lubu:',
'group-user'       => "Uza-na'in sira",
'group-bot'        => 'Bot sira',
'group-sysop'      => 'Administradór sira',
'group-bureaucrat' => 'Burokrata sira',
'group-suppress'   => "Oversight-na'in sira",
'group-all'        => '(hotu)',

'group-user-member'       => "Uza-na'in",
'group-bot-member'        => 'Bot',
'group-sysop-member'      => 'Administradór',
'group-bureaucrat-member' => 'Burokrata',
'group-suppress-member'   => "Oversight-na'in",

'grouppage-user'       => "{{ns:project}}:Uza-na'in sira",
'grouppage-bot'        => '{{ns:project}}:Bot sira',
'grouppage-sysop'      => '{{ns:project}}:Administradór sira',
'grouppage-bureaucrat' => '{{ns:project}}:Burokrata sira',
'grouppage-suppress'   => '{{ns:project}}:Oversight',

# Rights
'right-read'                 => 'Lee pájina',
'right-edit'                 => 'Edita pájina sira',
'right-createpage'           => "Kria pájina (sira-ne'ebé pájina diskusaun lá'os)",
'right-createtalk'           => 'Kria pájina diskusaun sira',
'right-createaccount'        => "Kria konta uza-na'in foun sira",
'right-move'                 => 'Book pájina sira',
'right-delete'               => 'Halakon pájina sira',
'right-bigdelete'            => "Halakon pájina sira-ne'ebé iha istória boot",
'right-undelete'             => 'Restaurar pájina ida',
'right-userrights'           => "Edita priviléjiu uza-na'in hotu",
'right-userrights-interwiki' => "Edita priviléjiu uza-na'in iha wiki seluk sira",

# User rights log
'rightslog'      => "Lista filak priviléjiu uza-na'in",
'rightslogtext'  => "Ne'e lista ba filak lubu uza-na'in.",
'rightslogentry' => 'filak lubu $1 nian husi $2 ba $3',
'rightsnone'     => '(mamuk)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => "lee pájina ne'e",
'action-edit'                 => "edita pájina ne'e",
'action-createpage'           => 'kria pájina sira',
'action-createtalk'           => 'kria pájina diskusaun sira',
'action-move'                 => "book pájina ne'e",
'action-move-subpages'        => "book pájina ne'e ho sub-pájina",
'action-delete'               => "halakon pájina ne'e",
'action-undelete'             => "restaurar pájina ne'e",
'action-userrights'           => "edita priviléjiu uza-na'in hotu",
'action-userrights-interwiki' => "edita priviléjiu uza-na'in iha wiki seluk sira",

# Recent changes
'nchanges'        => '$1 {{PLURAL:$1|diferensa|diferensa}}',
'recentchanges'   => 'Mudansa foufoun sira',
'rcshowhideminor' => '$1 osan-rahun sira',
'rcshowhidebots'  => '$1 bot sira',
'rcshowhideliu'   => '$1 ema rejista',
'rcshowhideanons' => '$1 ema anónimu',
'rcshowhidemine'  => "$1 ha'u-nia edita",
'diff'            => 'diferensa',
'hist'            => 'istória',
'hide'            => 'Hamsumik',
'show'            => 'Hatudu',
'minoreditletter' => 'o',
'newpageletter'   => 'F',
'boteditletter'   => 'b',

# Recent changes linked
'recentchangeslinked'       => 'Muda sira',
'recentchangeslinked-title' => 'Mudansa iha pájina sira-ne\'ebé iha ligasaun husi "$1"',

# Upload
'upload'          => 'Tau iha arkivu laran',
'uploadbtn'       => 'Tau iha arkivu laran',
'watchthisupload' => "Hateke pájina ne'e",

'license' => 'Lisensa:',

# Special:ListFiles
'listfiles_date' => 'Tempu',
'listfiles_name' => 'Naran',
'listfiles_user' => "Uza-na'in",

# File description page
'filehist-deleteall' => 'halakon hotu',
'filehist-deleteone' => 'halakon',
'filehist-current'   => 'atuál',
'filehist-datetime'  => 'Loron/Tempu',
'filehist-user'      => "Uza-na'in",
'filehist-comment'   => 'Komentáriu',
'imagelinks'         => 'Ligasaun',

# File reversion
'filerevert-comment' => 'Komentáriu:',

# File deletion
'filedelete'                  => 'Halakon $1',
'filedelete-comment'          => 'Motivu ba halakon:',
'filedelete-submit'           => 'Halakon',
'filedelete-otherreason'      => 'Motivu seluk/ida tan:',
'filedelete-reason-otherlist' => 'Motivu seluk',
'filedelete-edit-reasonlist'  => 'Edita lista motivu nian',

# Random page
'randompage' => 'Pájina ruma',

# Statistics
'statistics' => 'Estátistika',

'brokenredirects-edit'   => '(edita)',
'brokenredirects-delete' => '(halakon)',

'withoutinterwiki' => "Pájina sira-ne'ebé la iha ligasaun ba lian seluk",

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|byte|byte sira}}',
'nlinks'            => '{{PLURAL:$1|Ligasaun|Ligasaun}} $1',
'nmembers'          => '$1 {{PLURAL:$1|membru|membru}}',
'nrevisions'        => '$1 {{PLURAL:$1|versaun|versaun}}',
'unusedcategories'  => "Kategoria sira-ne'ebé la uza",
'shortpages'        => 'Pájina badak',
'longpages'         => 'Pájina naruk',
'listusers'         => "Lista uza-na'in",
'newpages'          => 'Pájina foun',
'newpages-username' => "Naran uza-na'in:",
'ancientpages'      => 'Pájina tuan liu hotu sira',
'move'              => 'Book',
'movethispage'      => "Book pájina ne'e",

# Book sources
'booksources-go' => 'Bá',

# Special:Log
'specialloguserlabel'  => "Uza-na'in:",
'speciallogtitlelabel' => 'Títulu:',

# Special:AllPages
'allpages'          => 'Pájina hotu',
'alphaindexline'    => "$1 to'o $2",
'nextpage'          => 'Pájina oinmai ($1)',
'prevpage'          => 'Pájina molok ($1)',
'allpagesfrom'      => 'Hatudu pájina sira; hahú iha:',
'allpagesto'        => 'Hatudu pájina sira; para iha:',
'allarticles'       => 'Pájina hotu',
'allinnamespace'    => 'Pájina hotu (iha espasu $1)',
'allnotinnamespace' => 'Pájina hotu (la iha espasu $1)',
'allpagesprev'      => 'Molok',
'allpagesnext'      => 'Oinmai',
'allpagessubmit'    => 'Bá',
'allpagesprefix'    => 'Hatudu pájina sira ho prefiksu:',

# Special:Categories
'categories' => 'Kategoria sira',

# Special:LinkSearch
'linksearch-ns' => 'Espasu pájina nian:',
'linksearch-ok' => 'Buka',

# Special:ListUsers
'listusers-submit' => 'Hatudu',

# Special:Log/newusers
'newuserlogpage'           => "Lista kria uza-na'in",
'newuserlogpagetext'       => "Ne'e lista kria uza-na'in.",
'newuserlog-create-entry'  => "Uza-na'in foun",
'newuserlog-create2-entry' => 'registrar tiha konta foun $1',

# Special:ListGroupRights
'listgrouprights-group'  => 'Lubu',
'listgrouprights-rights' => 'Priviléjiu',

# E-mail user
'emailuser'       => "Haruka korreiu eletróniku ba uza-na'in ne'e",
'defemailsubject' => '{{SITENAME}} korreiu eletróniku',
'noemailtitle'    => "Lá'os diresaun korreiu eletróniku",
'emailsend'       => 'Haruka',

# Watchlist
'watchlist'        => "Ha'u-nia lista hateke",
'mywatchlist'      => "Ha'u-nia lista hateke",
'watchlistfor'     => "('''$1''' nian)",
'addedwatch'       => 'tau tan tiha ba lista hateke',
'removedwatch'     => 'La hateke pájina ona',
'removedwatchtext' => 'La hateke pájina "[[:$1]]" ona.',
'watch'            => 'Hateke',
'watchthispage'    => "Hateke pájina ne'e",
'unwatch'          => 'La hateke ona',
'wlshowlast'       => 'Hatudu $1 hora $2 loron ikus $3',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Hateke...',
'unwatching' => 'La hateke...',

'enotif_newpagetext'           => "Ne'e pájina foun.",
'enotif_impersonal_salutation' => "Uza-na'in {{SITENAME}} nian",
'changed'                      => 'filak',
'created'                      => 'kria',

# Delete
'deletepage'             => 'Halakon pájina',
'excontent'              => "testu iha pájina: '$1'",
'excontentauthor'        => "testu iha pájina: '$1' (no ema ida de'it ne'ebé kontribui '[[Special:Contributions/$2|$2]]')",
'exblank'                => 'pájina mamuk',
'delete-legend'          => 'Halakon',
'deletedarticle'         => 'halakon "[[$1]]"',
'dellogpage'             => 'Lista halakon',
'deletionlog'            => 'lista halakon',
'deletecomment'          => 'Motivu ba halakon:',
'deleteotherreason'      => 'Motivu seluk/ida tan:',
'deletereasonotherlist'  => 'Motivu seluk',
'delete-edit-reasonlist' => 'Edita lista motivu nian',

# Protect
'protectedarticle'            => 'proteje "[[$1]]"',
'prot_1movedto2'              => 'book tiha [[$1]] ba [[$2]]',
'protectcomment'              => 'Komentáriu:',
'protectexpiry'               => "to'o:",
'protect-fallback'            => 'Presiza priviléjiu "$1"',
'protect-level-autoconfirmed' => "Blokeiu ema anónimu ho uza-na'in foun",
'protect-level-sysop'         => "de'it administradór",
'protect-expiring'            => "to'o $1 (UTC)",
'protect-cantedit'            => "Ó la bele filak proteje pájina ne'e nian, tan ba ó la bele edita pájina ne'e.",
'protect-othertime'           => 'Tempu seluk:',
'protect-othertime-op'        => 'tempu seluk',
'protect-otherreason'         => 'Motivu seluk/ida tan:',
'protect-otherreason-op'      => 'motivu seluk/ida tan',
'protect-edit-reasonlist'     => 'Edita lista motivu nian',
'protect-expiry-options'      => '1 hour:1 hours,1 loron:1 day,1 semana:1 week,2 semana:2 weeks,1 fulan:1 month,3 fulan:3 months,6 fulan:6 months,1 tinan:1 year,infinite:infinite', # display1:time1,display2:time2,...
'restriction-type'            => 'Permisaun:',

# Restrictions (nouns)
'restriction-edit'   => 'Edita',
'restriction-move'   => 'Book',
'restriction-create' => 'Kria',

# Undelete
'undelete'               => 'Haree pájina halakon tiha',
'undeletebtn'            => 'Restaurar',
'undeletelink'           => 'lee/restaurar',
'undeletecomment'        => 'Komentáriu:',
'undeletedarticle'       => 'restaurar "[[$1]]"',
'undeletedrevisions'     => 'restaurar {{PLURAL:$1|versaun|versaun}} $1',
'undelete-search-submit' => 'Buka',

# Namespace form on various pages
'namespace'      => 'Espasu pájina nian:',
'blanknamespace' => '(Prinsipál)',

# Contributions
'contributions' => "Kontribuisaun uza-na'in",
'mycontris'     => "Ha'u-nia kontribuisaun",
'uctop'         => '(versaun atuál)',
'month'         => 'Fulan (ho molok):',
'year'          => 'Tinan (ho molok):',

'sp-contributions-newbies'  => "Hatudu de'it kontribuisaun uza-na'in foun sira-nia",
'sp-contributions-search'   => 'Buka kontribuisaun',
'sp-contributions-username' => "Diresaun IP ka naran uza-na'in:",
'sp-contributions-submit'   => 'Buka',

# What links here
'whatlinkshere'           => "Artigu sira ne'ebé bá iha ne'e",
'whatlinkshere-title'     => 'Pájina sira ne\'ebé bá "$1".',
'whatlinkshere-page'      => 'Pájina:',
'linkshere'               => "Pájina sira ne'e link ba '''[[:$1]]''':",
'whatlinkshere-prev'      => '{{PLURAL:$1|oinmai|oinmai $1}}',
'whatlinkshere-next'      => '{{PLURAL:$1|molok|molok $1}}',
'whatlinkshere-links'     => '← ligasaun',
'whatlinkshere-hidelinks' => '$1 ligasaun',

# Block/unblock
'blockip'                 => "Blokeiu uza-na'in",
'blockip-legend'          => "Blokeiu uza-na'in",
'ipaddress'               => 'Diresaun IP:',
'ipbreason'               => 'Motivu:',
'ipbreasonotherlist'      => 'Motivu seluk',
'ipbsubmit'               => "Blokeiu uza-na'in ne'e",
'ipbother'                => 'Tempu seluk:',
'ipboptions'              => '2 hours:2 hours,1 loron:1 day,3 Loron:3 days,1 semana:1 week,2 semana:2 weeks,1 fulan:1 month,3 fulan:3 months,6 fulan:6 months,1 tinan:1 year,infinite:infinite', # display1:time1,display2:time2,...
'ipbotheroption'          => 'seluk',
'ipblocklist'             => 'Ema anónimu no rejista maka la bele edita',
'ipblocklist-username'    => "Naran uza-na'in ka IP:",
'ipblocklist-submit'      => 'Buka',
'anononlyblock'           => "ema anónimu de'it",
'blocklink'               => 'blokeiu',
'unblocklink'             => 'la blokeiu',
'contribslink'            => 'kontribuisaun',
'block-log-flags-noemail' => 'korreiu eletróniku blokeiu',
'ipb_already_blocked'     => 'Ema ruma blokeiu "$1" tiha ona',

# Move page
'move-page'               => 'Book $1',
'move-page-legend'        => 'Book pájina',
'movearticle'             => 'Book pájina:',
'movenotallowed'          => 'Ó la bele book pájina sira.',
'newtitle'                => 'Naran foun:',
'move-watch'              => "Hateke pájina ne'e",
'movepagebtn'             => 'Book pájina',
'movedto'                 => 'book tiha ba',
'movetalk'                => 'Book pájina diskusaun mós',
'1movedto2'               => 'book tiha [[$1]] ba [[$2]]',
'movelogpage'             => 'Lista book',
'movereason'              => 'Motivu:',
'delete_and_move'         => 'Halakon ho book',
'delete_and_move_confirm' => 'Sin, halakon pájina',

# Export
'export'        => 'Esporta pájina sira',
'export-submit' => 'Esporta',
'export-addcat' => 'Tau tan',

# Namespace 8 related
'allmessagesname'    => 'Naran',
'allmessagescurrent' => 'Testu atuál',

# Special:Import
'import-comment' => 'Komentáriu:',

# Import log
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|versaun|versaun}} husi $2',

# Tooltip help for the actions
'tooltip-pt-userpage'       => "Ó-nia pájina uza-na'in",
'tooltip-pt-mytalk'         => 'Ó-nia pájina diskusaun',
'tooltip-pt-preferences'    => "Ha'u-nia preferénsia",
'tooltip-pt-mycontris'      => 'Ó-nia kontribuisaun (lista)',
'tooltip-pt-logout'         => 'Husik',
'tooltip-ca-talk'           => 'Diskusaun kona-ba konteúdu pájina nian',
'tooltip-ca-edit'           => "Ó bele filak pájina ne'e. Please use the preview button before saving.",
'tooltip-ca-addsection'     => 'Tau tan seksaun foun ida.',
'tooltip-ca-viewsource'     => "Ema ruma proteje tiha pájina ne'e.
Ó bele lee testu.",
'tooltip-ca-protect'        => "Proteje pájina ne'e",
'tooltip-ca-delete'         => "Halakon pájina ne'e",
'tooltip-ca-move'           => "Book pájina ne'e",
'tooltip-ca-watch'          => "Tau tan pájina ne'e ba ó-nia lista hateke",
'tooltip-search'            => 'Buka iha {{SITENAME}}',
'tooltip-p-logo'            => 'Pájina Mahuluk',
'tooltip-n-mainpage'        => 'Vizita Pájina Mahuluk',
'tooltip-n-portal'          => "Kona-ba projetu, ne'ebé ó bele halo, iha ne'ebé ó hetan saida",
'tooltip-n-recentchanges'   => "Lista mudansa foufoun sira iha wiki ne'e.",
'tooltip-n-randompage'      => 'Hola pájina ruma',
'tooltip-n-help'            => 'Hatudu pájina ajuda.',
'tooltip-t-whatlinkshere'   => "Lista pájina nian ne'ebé bá iha ne'e",
'tooltip-t-contributions'   => "Haree lista kontribuisaun uza-na'in ne'e nian",
'tooltip-t-emailuser'       => 'Haruka korreiu eletróniku',
'tooltip-t-upload'          => 'Tau iha arkivu laran',
'tooltip-t-specialpages'    => 'Lista pájina espesiál hotu nian',
'tooltip-ca-nstab-user'     => "Haree pájina uza-na'in",
'tooltip-ca-nstab-project'  => 'Haree pájina projetu nian',
'tooltip-ca-nstab-category' => 'Lee pájina kategoria',
'tooltip-minoredit'         => "Halo ne'e osan-rahun",
'tooltip-watch'             => "Tau tan pájina ne'e ba ó-nia lista hateke",

# Attribution
'lastmodifiedatby' => "$3 filak ikus pájina ne'e iha $1, $2.", # $1 date, $2 time, $3 user

# Skin names
'skinname-standard'    => 'Klásiku',
'skinname-cologneblue' => 'Kolónia azúl',
'skinname-myskin'      => 'MySkin',
'skinname-chick'       => 'Manu',

# Browsing diffs
'previousdiff' => '←Versaun molok',
'nextdiff'     => 'Versaun oinmai→',

# Media information
'show-big-image' => 'Boot liu',

# Special:NewFiles
'showhidebots' => '($1 bot sira)',
'ilsubmit'     => 'Buka',

# EXIF tags
'exif-artist' => 'Autór',

'exif-meteringmode-255' => 'Seluk',

# External editor support
'edit-externally-help' => "(Haree [http://www.mediawiki.org/wiki/Manual:External_editors iha ne'e] ba informasaun barak liu)",

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'hotu',
'imagelistall'     => 'hotu',
'watchlistall2'    => 'hotu',
'namespacesall'    => 'hotu',
'monthsall'        => 'hotu',

# action=purge
'confirm_purge_button' => 'OK',

# Multipage image navigation
'imgmultipageprev' => '← pájina molok',
'imgmultipagenext' => 'pájina oinmai →',
'imgmultigo'       => 'Bá!',

# Table pager
'table_pager_next'         => 'Pájina oinmai',
'table_pager_prev'         => 'Pájina molok',
'table_pager_first'        => 'Pájina uluk',
'table_pager_limit_submit' => 'Bá',

# Auto-summaries
'autosumm-new' => "Pájina foun: '$1'",

# Watchlist editor
'watchlistedit-normal-title' => 'Filak lista hateke',
'watchlistedit-raw-titles'   => 'Títulu sira:',
'watchlistedit-raw-added'    => '{{PLURAL:$1|Títulu ida|Títulu $1}} tau tan tiha:',
'watchlistedit-raw-removed'  => '{{PLURAL:$1|Títulu ida|Títulu $1}} hasai tiha:',

# Watchlist editing tools
'watchlisttools-edit' => 'Haree no edita lista hateke',

# Special:Version
'version'                  => 'Versaun', # Not used as normal message but as header for the special page itself
'version-specialpages'     => 'Pájina espesiál',
'version-other'            => 'Seluk',
'version-version'          => 'Versaun',
'version-license'          => 'Lisensa',
'version-software-product' => 'Produtu',
'version-software-version' => 'Versaun',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'Buka',

# Special:SpecialPages
'specialpages'               => 'Pájina espesiál sira',
'specialpages-group-other'   => 'Pájina espesiál seluk',
'specialpages-group-login'   => 'Login / kria konta',
'specialpages-group-changes' => 'Mudansa foufoun sira no lista sira',
'specialpages-group-users'   => "Uza-na'in no priviléjiu sira",
'specialpages-group-pages'   => 'Lista pájina nian',

# Special:BlankPage
'blankpage' => 'Pájina mamuk',

);
