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
 * @author Reedy
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Espesiál',
	NS_TALK             => 'Diskusaun',
	NS_USER             => 'Uza-na\'in',
	NS_USER_TALK        => 'Diskusaun_Uza-na\'in',
	NS_PROJECT_TALK     => 'Diskusaun_$1',
	NS_FILE             => 'Imajen',
	NS_FILE_TALK        => 'Diskusaun_Imajen',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Diskusaun_MediaWiki',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Diskusaun_Template',
	NS_HELP             => 'Ajuda',
	NS_HELP_TALK        => 'Diskusaun_Ajuda',
	NS_CATEGORY         => 'Kategoria',
	NS_CATEGORY_TALK    => 'Diskusaun_Kategoria',
);

$namespaceAliases = array(
	"Kategoría"           => NS_CATEGORY,
	"Diskusaun_Kategoría" => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Allmessages'               => array( 'Mensajen_hotu' ),
	'Allpages'                  => array( 'Pájina_hotu' ),
	'Block'                     => array( 'Blokeiu' ),
	'Categories'                => array( 'Kategoria' ),
	'Contributions'             => array( 'Kontribuisaun' ),
	'CreateAccount'             => array( 'Rejista' ),
	'Emailuser'                 => array( 'Haruka_korreiu_eletróniku' ),
	'Export'                    => array( 'Esporta' ),
	'Import'                    => array( 'Importa' ),
	'BlockList'                 => array( 'Lista_ema_sira-ne\'ebé_blokeiu_tiha' ),
	'Listadmins'                => array( 'Lista_administradór' ),
	'Listfiles'                 => array( 'Lista_imajen' ),
	'Listusers'                 => array( 'Lista_uza-na\'in' ),
	'Longpages'                 => array( 'Pájina_naruk' ),
	'Movepage'                  => array( 'Book' ),
	'Mypage'                    => array( 'Ha\'u-nia_pájina' ),
	'Newimages'                 => array( 'Imajen_foun' ),
	'Preferences'               => array( 'Preferénsia' ),
	'Protectedpages'            => array( 'Pájina_sira-ne\'ebé_proteje_tiha' ),
	'Randompage'                => array( 'Pájina_ruma' ),
	'Recentchanges'             => array( 'Mudansa_foufoun_sira' ),
	'Search'                    => array( 'Buka' ),
	'Shortpages'                => array( 'Pájina_badak' ),
	'Specialpages'              => array( 'Pájina_espesiál_sira' ),
	'Statistics'                => array( 'Estatístika' ),
	'Undelete'                  => array( 'Restaurar' ),
	'Upload'                    => array( 'Tau_iha_arkivu_laran' ),
	'Userrights'                => array( 'Priviléjiu' ),
	'Version'                   => array( 'Versaun' ),
	'Watchlist'                 => array( 'Lista_hateke' ),
	'Whatlinkshere'             => array( 'Pájina_sira_ne\'ebé_bá_iha_ne\'e' ),
	'Withoutinterwiki'          => array( 'Laiha_interwiki' ),
);

$messages = array(
# User preference toggles
'tog-underline'          => 'Subliña ligasaun sira:',
'tog-highlightbroken'    => 'Formatu ligasaun sira-ne\'ebé bá pájina maka wiki la iha: <a href="" class="new">ne\'e</a> ka <a href="" class="internal">ne\'e</a>.',
'tog-justify'            => 'Justifika parágrafu sira',
'tog-hideminor'          => "Lá'os hatudu muda ki-ki'ik iha mudansa foufoun sira",
'tog-usenewrc'           => 'Uza lista "Mudansa foufoun sira" di\'ak liu (JavaScript)',
'tog-showtoolbar'        => 'Hatudu kaixa edita (presiza JavaScript)',
'tog-watchcreations'     => "Hateke pájina sira-ne'ebé ha'u kria",
'tog-watchdefault'       => "Hateke pájina sira-ne'ebé ha'u edita",
'tog-watchmoves'         => "Hateke pájina sira-ne'ebé ha'u book",
'tog-watchdeletion'      => "Hateke pájina sira-ne'ebé ha'u halakon",
'tog-watchlisthideown'   => "La hatudu ha'u-nia edita iha lista hateke",
'tog-watchlisthidebots'  => 'Hamsumik bot iha lista hateke',
'tog-watchlisthideminor' => "Hamsumik muda ki-ki'ik iha lista hateke",
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
'jan'           => 'Jan.',
'feb'           => 'Fev.',
'mar'           => 'Mar.',
'apr'           => 'Abr.',
'may'           => 'Maiu',
'jun'           => 'Jun.',
'jul'           => 'Jul.',
'aug'           => 'Ago.',
'sep'           => 'Set.',
'oct'           => 'Out.',
'nov'           => 'Nov.',
'dec'           => 'Dez.',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|Kategoria|Kategoria}}',
'category_header'        => 'Artigu iha kategoría "$1"',
'subcategories'          => 'Sub-kategoria sira',
'category-empty'         => "''Kategoria ne'e agora la iha pájina sira.''",
'listingcontinuesabbrev' => 'kont.',

'about'         => 'Kona-ba',
'article'       => 'Pájina',
'cancel'        => 'Para',
'moredotdotdot' => 'Barak liu...',
'mypage'        => "Ha'u-nia pájina",
'mytalk'        => "Ha'u-nia diskusaun",
'anontalk'      => "Diskusaun ba IP ne'e",
'navigation'    => 'Hatudu-dalan',
'and'           => '&#32;ho',

# Cologne Blue skin
'qbfind'         => 'Hetan',
'qbedit'         => 'Edita',
'qbpageoptions'  => "Pájina ne'e",
'qbmyoptions'    => "Ha'u-nia pájina sira",
'qbspecialpages' => 'Pájina espesiál sira',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-view-create'  => 'Kria',
'vector-view-edit'    => 'Edita',
'vector-view-history' => 'Haree istória',

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
'protect_change'    => 'muda',
'protectthispage'   => "Proteje pájina ne'e",
'unprotect'         => 'Muda protesaun',
'unprotectthispage' => "Muda protesaun pájina ne'e nian",
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
'lastmodifiedat'    => "Mudansa ba dala ikus pájina ne'e nian iha $1, $2.",
'protectedpage'     => 'Pájina maka ema ruma proteje tiha',
'jumpto'            => 'Bá:',
'jumptonavigation'  => 'hatudu-dalan',
'jumptosearch'      => 'buka',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Kona-ba {{SITENAME}}',
'aboutpage'            => 'Project:Kona-ba',
'copyright'            => 'Testu pájina nian iha $1 okos.',
'copyrightpage'        => '{{ns:project}}:Direitu_autór_nian',
'currentevents'        => 'Mamosuk atuál sira',
'currentevents-url'    => 'Project:Mamosuk atuál sira',
'disclaimers'          => 'Avisu legál',
'disclaimerpage'       => 'Project:Avisu legál',
'edithelp'             => 'Ajuda kona-ba edita',
'edithelppage'         => 'Help:Edita',
'helppage'             => 'Help:Konteúdu',
'mainpage'             => 'Pájina Mahuluk',
'mainpage-description' => 'Pájina Mahuluk',
'portal'               => 'Portál komunidade nian',
'portal-url'           => 'Project:Portál komunidade nian',
'privacy'              => 'Polítika privasidade nian',
'privacypage'          => 'Project:Polítika privasidade nian',

'badaccess'        => 'Ita la iha permisaun...',
'badaccess-group0' => "Ó la bele halo ne'e.",
'badaccess-groups' => "De'it uza-na'in sira-ne'ebé iha {{PLURAL:$2|grupu|grupu ida husi}} $1 bele halo ne'e.",

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
'red-link-title'          => '$1 (pájina la iha)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Pájina',
'nstab-user'      => "Pájina uza-na'in",
'nstab-special'   => 'Pájina espesiál',
'nstab-project'   => 'Pájina projetu nian',
'nstab-image'     => 'Fail',
'nstab-mediawiki' => 'Mensajen',
'nstab-help'      => 'Pájina ajuda',
'nstab-category'  => 'Kategoria',

# Main script and global functions
'nosuchspecialpage' => "Pájina espesiál ne'e la iha",
'nospecialpagetext' => "<strong>Pájina espesiál ne'e la iha.</strong>

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
'welcomecreation'         => "== Loron di'ak, $1! ==
Agora Ita iha konta iha ne'e.
La haluha muda Ita-nia [[Special:Preferences|preferénsia]].",
'yourname'                => "Naran uza-na'in:",
'login'                   => 'Log in',
'nav-login-createaccount' => 'Log in / kriar konta ida',
'userlogin'               => 'Log in / kriar konta ida',
'logout'                  => 'Husik',
'userlogout'              => 'Husik',
'nologin'                 => "La iha konta ida? '''$1'''.",
'nologinlink'             => 'Registrar',
'createaccount'           => "Registrar uza-na'in",
'gotaccount'              => "Ó iha konta ona? '''$1'''.",
'gotaccountlink'          => 'Log in',
'userexists'              => "Naran uza-na'in ne'e ona iha wiki.
Favór ida lori naran seluk.",
'nosuchuser'              => 'Konta uza-na\'in (naran "$1") la iha.
User names are case sensitive.
Check your spelling, ka [[Special:UserLogin/signup|kria konta foun]].',
'nouserspecified'         => "Ó tenke espesífiku naran uza-na'in ida.",
'accountcreated'          => "Registrar tiha uza-na'in",
'loginlanguagelabel'      => 'Lian: $1',

# Change password dialog
'resetpass_text' => "<!-- Hakerek testu iha ne'e -->",

# Special:PasswordReset
'passwordreset-username' => "Naran uza-na'in:",
'passwordreset-email'    => 'Diresaun korreiu eletróniku:',

# Edit page toolbar
'link_tip'       => 'Ligasaun ba laran',
'extlink_sample' => "http://www.example.com ligasaun ba li'ur",
'extlink_tip'    => "Ligasaun ba li'ur (tau tan http://)",
'image_sample'   => 'Ezemplu.jpg',
'media_sample'   => 'Ezemplu.ogg',
'sig_tip'        => 'Ita-nia asinatura ho data/oras',

# Edit pages
'summary'                          => 'Rezumu:',
'minoredit'                        => "Ne'e muda ki'ik",
'watchthis'                        => "Hateke pájina ne'e",
'savearticle'                      => 'Muda pájina',
'showdiff'                         => 'Hatudu diferensa sira',
'anoneditwarning'                  => 'Ó lá\'os "log-in" iha momentu.',
'blockedtitle'                     => "Uza-na'in la bele edita (blokeiu)",
'blockednoreason'                  => 'laiha motivu',
'whitelistedittext'                => 'Ó tenke $1 ba edita pájina sira.',
'loginreqpagetext'                 => 'Ó tenke $1 ba haree pájina seluk.',
'newarticle'                       => '(Foun)',
'noarticletext'                    => "Iha momentu lá'os testu iha pájina ne'e, bele [[Special:Search/{{PAGENAME}}|buka naran pájina nian]] iha pájina seluk, <span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} search the related logs], ka [{{fullurl:{{FULLPAGENAME}}|action=edit}} edita pájina ne'e]</span>.",
'previewnote'                      => "'''Ne'e de'it pájina ba kontrola; Ita-nia mudansa la armazenadu seidauk!'''",
'editing'                          => 'Edita $1',
'editingsection'                   => 'Edita $1 (seksaun)',
'editingcomment'                   => 'Edita $1 (seksaun foun)',
'yourtext'                         => 'Ó-nia testu',
'yourdiff'                         => 'Diferensa sira',
'template-protected'               => '(proteje tiha)',
'template-semiprotected'           => '(proteje tiha balun)',
'hiddencategories'                 => "Pájina ne'e iha {{PLURAL:$1|kategoria ida-ne'ebé subar|kategoria $1 sira-ne'ebé subar}}:",
'nocreatetext'                     => "Ó la bele kria pájina foun iha {{SITENAME}}.
Ó bele edita pájina sira-ne'ebé {{SITENAME}} iha ona ka [[Special:UserLogin|log in ka kria konta uza-na'in]].",
'nocreate-loggedin'                => 'Ó la bele kria pájina foun.',
'permissionserrorstext'            => "Ó la bele halo ne'e; {{PLURAL:$1|motivu|motivu sira}}:",
'permissionserrorstext-withaction' => 'Ita la bele $2. {{PLURAL:$1|Razaun|Razaun sira}}:',

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
'history-feed-item-nocomment' => '$1 iha $2',

# Revision deletion
'rev-delundel'               => 'hatudu/subar',
'revisiondelete'             => 'Halakon/restaurar versaun',
'revdelete-show-file-submit' => 'Sin',
'revdelete-hide-user'        => "Subar naran edita-na'in/IP",
'revdelete-radio-set'        => 'Sin',
'revdelete-radio-unset'      => 'Lae',
'revdelete-uname'            => "naran uza-na'in",
'revdelete-hid'              => 'subar $1',
'revdelete-edit-reasonlist'  => 'Edita lista motivu nian',

# Diffs
'lineno' => 'Liña $1:',

# Search results
'searchsubtitleinvalid'     => "Ita buka tiha ona '''$1'''",
'prevn'                     => 'molok {{PLURAL:$1|$1}}',
'nextn'                     => 'oinmai {{PLURAL:$1|$1}}',
'viewprevnext'              => 'Haree ($1 {{int:pipe-separator}} $2) ($3)',
'search-result-size'        => '$1 ({{PLURAL:$2|liafuan ida|liafuan $2}})',
'search-section'            => '(seksaun $1)',
'search-interwiki-caption'  => 'Projetu seluseluk sira',
'search-mwsuggest-enabled'  => 'fó sujestaun',
'search-mwsuggest-disabled' => 'la hatudu sujestaun',
'searchall'                 => 'hotu',
'powersearch'               => 'Buka',
'powersearch-field'         => 'Buka',
'powersearch-toggleall'     => 'Hotu',

# Preferences page
'preferences'               => 'Preferénsia',
'mypreferences'             => "Ha'u-nia preferénsia",
'prefs-rc'                  => 'Mudansa foufoun sira',
'prefs-watchlist'           => 'Lista hateke',
'prefs-editing'             => 'Edita',
'searchresultshead'         => 'Buka',
'timezoneregion-africa'     => 'Áfrika',
'timezoneregion-america'    => 'Amérika',
'timezoneregion-antarctica' => 'Antártika',
'timezoneregion-asia'       => 'Ázia',
'timezoneregion-australia'  => 'Austrália',
'timezoneregion-europe'     => 'Europa',
'youremail'                 => 'Korreiu eletróniku:',
'username'                  => "Naran uza-na'in:",
'uid'                       => "Uza-na'in ID:",
'yourlanguage'              => 'Lian:',
'gender-male'               => 'Mane',
'gender-female'             => 'Feto',
'email'                     => 'Korreiu eletróniku',
'prefs-help-email-required' => 'Haruka diresaun korreiu eletróniku.',

# User rights
'userrights'               => "Muda priviléjiu uza-na'in sira",
'userrights-lookup-user'   => "Muda grupu uza-na'in",
'userrights-user-editname' => "Fó naran uza-na'in ida:",
'editusergroup'            => "Muda grupu uza-na'in",
'userrights-editusergroup' => "Muda grupu uza-na'in",
'userrights-groupsmember'  => 'Membru iha:',
'userrights-reason'        => 'Motivu:',
'userrights-no-interwiki'  => "Ó la bele muda priviléjiu uza-na'in iha wiki seluk.",

# Groups
'group'            => 'Grupu:',
'group-user'       => "Uza-na'in sira",
'group-bot'        => 'Bot sira',
'group-sysop'      => 'Administradór sira',
'group-bureaucrat' => 'Burokrata sira',
'group-suppress'   => "Oversight-na'in sira",
'group-all'        => '(hotu)',

'group-user-member'       => "{{GENDER:$1|Uza-na'in}}",
'group-bot-member'        => 'Bot',
'group-sysop-member'      => '{{GENDER:$1|Administradór|Administradóra}}',
'group-bureaucrat-member' => '{{GENDER:$1|Burokrata}}',
'group-suppress-member'   => "{{GENDER:$1|Oversight-na'in}}",

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
'rightslog'      => "Lista mudansa priviléjiu uza-na'in",
'rightslogtext'  => "Ne'e lista mudansa priviléjiu uza-na'in sira nian.",
'rightslogentry' => 'muda grupu $1 nian husi $2 ba $3',
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
'nchanges'           => '$1 {{PLURAL:$1|diferensa|diferensa}}',
'recentchanges'      => 'Mudansa foufoun sira',
'rcshowhideminor'    => "$1 muda ki-ki'ik",
'rcshowhidebots'     => '$1 bot sira',
'rcshowhideliu'      => '$1 ema rejista',
'rcshowhideanons'    => '$1 ema anónimu',
'rcshowhidemine'     => "$1 ha'u-nia edita",
'diff'               => 'diferensa',
'hist'               => 'istória',
'hide'               => 'Hamsumik',
'show'               => 'Hatudu',
'minoreditletter'    => 'k',
'newpageletter'      => 'F',
'boteditletter'      => 'b',
'rc-enhanced-expand' => 'Hatudu detalle (presiza JavaScript)',

# Recent changes linked
'recentchangeslinked'         => 'Muda sira',
'recentchangeslinked-feed'    => 'Muda sira',
'recentchangeslinked-toolbox' => 'Muda sira',
'recentchangeslinked-title'   => 'Mudansa iha pájina sira-ne\'ebé iha ligasaun husi "$1"',
'recentchangeslinked-page'    => 'Naran pájina nian:',

# Upload
'upload'            => 'Tau iha arkivu laran',
'uploadbtn'         => 'Tau iha arkivu laran',
'fileuploadsummary' => 'Rezumu:',
'watchthisupload'   => "Hateke pájina ne'e",

'license'        => 'Lisensa:',
'license-header' => 'Lisensa:',

# Special:ListFiles
'imgfile'        => 'fail',
'listfiles_date' => 'Tempu',
'listfiles_name' => 'Naran',
'listfiles_user' => "Uza-na'in",

# File description page
'file-anchor-link'   => 'Fail',
'filehist-deleteall' => 'halakon hotu',
'filehist-deleteone' => 'halakon',
'filehist-current'   => 'atuál',
'filehist-datetime'  => 'Loron/Tempu',
'filehist-user'      => "Uza-na'in",
'filehist-comment'   => 'Komentáriu',
'imagelinks'         => "Pájina iha ne'ebá fixeiru ne'e",

# File reversion
'filerevert-comment' => 'Razaun:',

# File deletion
'filedelete'                  => 'Halakon $1',
'filedelete-comment'          => 'Motivu:',
'filedelete-submit'           => 'Halakon',
'filedelete-otherreason'      => 'Motivu seluk/ida tan:',
'filedelete-reason-otherlist' => 'Motivu seluk',
'filedelete-edit-reasonlist'  => 'Edita lista motivu nian',

# Random page
'randompage' => 'Pájina ruma',

# Statistics
'statistics' => 'Estatístika',

'brokenredirects-edit'   => 'edita',
'brokenredirects-delete' => 'halakon',

'withoutinterwiki' => "Pájina sira-ne'ebé la iha ligasaun ba lian seluk",

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|byte|byte sira}}',
'nlinks'            => '{{PLURAL:$1|Ligasaun|Ligasaun}} $1',
'nmembers'          => '$1 {{PLURAL:$1|membru|membru}}',
'nrevisions'        => '$1 {{PLURAL:$1|versaun|versaun}}',
'unusedcategories'  => "Kategoria sira-ne'ebé la uza",
'prefixindex'       => 'Pájina hotu ho prefiksu',
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
'specialloguserlabel'  => "Uza-na'in ne'ebé halo:",
'speciallogtitlelabel' => "Objetivu (títulu ka uza-na'in):",

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
'categories'                  => 'Kategoria sira',
'special-categories-sort-abc' => 'tau tuir alfabetu',

# Special:LinkSearch
'linksearch-ns' => 'Espasu pájina nian:',
'linksearch-ok' => 'Buka',

# Special:ListUsers
'listusers-submit' => 'Hatudu',

# Special:ActiveUsers
'activeusers-hidebots'   => 'Subar bot sira',
'activeusers-hidesysops' => 'Subar administradór sira',

# Special:Log/newusers
'newuserlogpage'           => "Lista kria uza-na'in",
'newuserlogpagetext'       => "Ne'e lista kria uza-na'in.",
'newuserlog-create-entry'  => "Uza-na'in foun",
'newuserlog-create2-entry' => 'registrar tiha konta foun $1',

# Special:ListGroupRights
'listgrouprights-group'   => 'Grupu',
'listgrouprights-rights'  => 'Priviléjiu',
'listgrouprights-members' => '(lista membru nian)',

# E-mail user
'emailuser'       => "Haruka korreiu eletróniku ba uza-na'in ne'e",
'defemailsubject' => '{{SITENAME}} korreiu eletróniku',
'noemailtitle'    => "Lá'os diresaun korreiu eletróniku",
'emailusername'   => "Naran uza-na'in:",
'emailsend'       => 'Haruka',

# Watchlist
'watchlist'         => "Ha'u-nia lista hateke",
'mywatchlist'       => "Ha'u-nia lista hateke",
'removedwatchtext'  => 'La hateke pájina "[[:$1]]" ona (haree [[Special:Watchlist|"lista hateke"]]).',
'watch'             => 'Hateke',
'watchthispage'     => "Hateke pájina ne'e",
'unwatch'           => 'La hateke ona',
'watchlist-details' => '{{PLURAL:$1|Pájina ida (1)|Pájina $1}} iha Ita-nia "lista hateke" (la ho pájina diskusaun).',
'wlshowlast'        => 'Hatudu $1 hora $2 loron ikus $3',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Hateke...',
'unwatching' => 'La hateke...',

'enotif_newpagetext'           => "Ne'e pájina foun.",
'enotif_impersonal_salutation' => "Uza-na'in {{SITENAME}} nian",
'changed'                      => 'muda ona',
'created'                      => 'kria ona',
'enotif_subject'               => '$PAGEEDITOR $CHANGEDORCREATED pájina $PAGETITLE iha {{SITENAME}}',

# Delete
'deletepage'             => 'Halakon pájina',
'excontent'              => "testu iha pájina: '$1'",
'excontentauthor'        => "testu iha pájina: '$1' (no ema ida de'it ne'ebé kontribui '[[Special:Contributions/$2|$2]]')",
'exblank'                => 'pájina mamuk',
'delete-legend'          => 'Halakon',
'actioncomplete'         => 'operasaun remata',
'deletedtext'            => 'Ita foin halakon pájina "$1". Haree $2 ba "operasaun halakon" seluk.',
'deletedarticle'         => 'halakon "[[$1]]"',
'dellogpage'             => 'Lista halakon',
'deletionlog'            => 'lista halakon',
'deletecomment'          => 'Motivu:',
'deleteotherreason'      => 'Motivu seluk/ida tan:',
'deletereasonotherlist'  => 'Motivu seluk',
'delete-edit-reasonlist' => 'Edita lista motivu nian',

# Protect
'protectedarticle'            => 'proteje "[[$1]]"',
'prot_1movedto2'              => 'book tiha [[$1]] ba [[$2]]',
'protectcomment'              => 'Motivu:',
'protectexpiry'               => "to'o:",
'protect-fallback'            => 'Presiza priviléjiu "$1"',
'protect-level-autoconfirmed' => "Blokeiu ema anónimu ho uza-na'in foun",
'protect-level-sysop'         => "de'it administradór",
'protect-expiring'            => "to'o $1 (UTC)",
'protect-cantedit'            => "Ó la bele filak proteje pájina ne'e nian, tan ba ó la bele edita pájina ne'e.",
'protect-othertime'           => 'Tempu seluk:',
'protect-othertime-op'        => 'tempu seluk',
'protect-otherreason'         => 'Motivu seluk/ida tan:',
'protect-otherreason-op'      => 'Motivu seluk',
'protect-edit-reasonlist'     => 'Edita lista motivu nian',
'protect-expiry-options'      => '1 oras:1 hour,1 loron:1 day,1 semana:1 week,2 semana:2 weeks,1 fulan:1 month,3 fulan:3 months,6 fulan:6 months,1 tinan:1 year,infinite:infinite',
'restriction-type'            => 'Permisaun:',

# Restrictions (nouns)
'restriction-edit'   => 'Edita',
'restriction-move'   => 'Book',
'restriction-create' => 'Kria',

# Undelete
'undelete'                  => 'Haree pájina halakon tiha',
'undeletebtn'               => 'Restaurar',
'undeletelink'              => 'lee/restaurar',
'undeleteviewlink'          => 'haree',
'undeletecomment'           => 'Razaun:',
'undeletedarticle'          => 'restaurar "[[$1]]"',
'undeletedrevisions'        => 'restaurar {{PLURAL:$1|versaun|versaun}} $1',
'undelete-search-submit'    => 'Buka',
'undelete-show-file-submit' => 'Sin',

# Namespace form on various pages
'namespace'      => 'Espasu pájina nian:',
'blanknamespace' => '(Prinsipál)',

# Contributions
'contributions'       => "Kontribuisaun uza-na'in",
'contributions-title' => 'Kontribuisaun "$1" nian',
'mycontris'           => "Ha'u-nia kontribuisaun",
'contribsub2'         => 'Ba $1 ($2)',
'uctop'               => '(versaun atuál)',
'month'               => 'Fulan (ho molok):',
'year'                => 'Tinan (ho molok):',

'sp-contributions-newbies'  => "Hatudu de'it kontribuisaun uza-na'in foun sira-nia",
'sp-contributions-talk'     => 'diskusaun',
'sp-contributions-search'   => 'Buka kontribuisaun',
'sp-contributions-username' => "Diresaun IP ka naran uza-na'in:",
'sp-contributions-submit'   => 'Buka',

# What links here
'whatlinkshere'           => "Artigu sira ne'ebé bá iha ne'e",
'whatlinkshere-title'     => 'Pájina sira ne\'ebé bá "$1".',
'whatlinkshere-page'      => 'Pájina:',
'linkshere'               => "Pájina sira ne'e link ba '''[[:$1]]''':",
'isimage'                 => 'ligasaun ba fixeiru',
'whatlinkshere-prev'      => '{{PLURAL:$1|oinmai|oinmai $1}}',
'whatlinkshere-next'      => '{{PLURAL:$1|molok|molok $1}}',
'whatlinkshere-links'     => '← ligasaun',
'whatlinkshere-hidelinks' => '$1 ligasaun',

# Block/unblock
'blockip'                  => "Blokeiu uza-na'in",
'blockip-legend'           => "Blokeiu uza-na'in",
'ipbreason'                => 'Motivu:',
'ipbreasonotherlist'       => 'Motivu seluk',
'ipbsubmit'                => "Blokeiu uza-na'in ne'e",
'ipbother'                 => 'Tempu seluk:',
'ipboptions'               => '2 hours:2 hours,1 loron:1 day,3 Loron:3 days,1 semana:1 week,2 semana:2 weeks,1 fulan:1 month,3 fulan:3 months,6 fulan:6 months,1 tinan:1 year,infinite:infinite',
'ipbotheroption'           => 'seluk',
'ipblocklist'              => "Uza-na'in sira-ne'ebé la bele edita",
'blocklist-reason'         => 'Razaun',
'ipblocklist-submit'       => 'Buka',
'anononlyblock'            => "ema anónimu de'it",
'blocklink'                => 'blokeiu',
'unblocklink'              => 'la blokeiu',
'contribslink'             => 'kontribuisaun',
'block-log-flags-nocreate' => 'la bele kria konta foun',
'block-log-flags-noemail'  => 'korreiu eletróniku blokeiu',
'ipb_already_blocked'      => 'Ema ruma blokeiu "$1" tiha ona',

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
'export-addns'  => 'Tau tan',

# Namespace 8 related
'allmessagesname'        => 'Naran',
'allmessagescurrent'     => 'Testu atuál',
'allmessages-filter-all' => 'Hotu',
'allmessages-language'   => 'Lian:',

# Special:Import
'import-interwiki-submit' => 'Importa',
'import-comment'          => 'Komentáriu:',
'import-revision-count'   => '{{PLURAL:$1|versaun ida|versaun $1}}',

# Import log
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|versaun|versaun}} husi $2',

# Tooltip help for the actions
'tooltip-pt-userpage'            => "Ó-nia pájina uza-na'in",
'tooltip-pt-mytalk'              => 'Ó-nia pájina diskusaun',
'tooltip-pt-preferences'         => "Ha'u-nia preferénsia",
'tooltip-pt-mycontris'           => 'Ó-nia kontribuisaun (lista)',
'tooltip-pt-login'               => 'Ami rekomenda identifikasaun ("log in"), maibé Ita-Boot la presiza halo ne\'e',
'tooltip-pt-logout'              => 'Husik',
'tooltip-ca-talk'                => 'Diskusaun kona-ba konteúdu pájina nian',
'tooltip-ca-edit'                => "Ita bele edita pájina ne'e. Please use the preview button before saving.",
'tooltip-ca-addsection'          => 'Tau tan seksaun foun ida.',
'tooltip-ca-viewsource'          => "Ema ruma proteje tiha pájina ne'e.
Ó bele lee testu.",
'tooltip-ca-protect'             => "Proteje pájina ne'e",
'tooltip-ca-delete'              => "Halakon pájina ne'e",
'tooltip-ca-move'                => "Book pájina ne'e",
'tooltip-ca-watch'               => 'Tau pájina ne\'e ba Ita-nia "lista hateke"',
'tooltip-ca-unwatch'             => 'Hasai pájina ne\'e husi Ita-nia "lista hateke"',
'tooltip-search'                 => 'Buka iha {{SITENAME}}',
'tooltip-search-go'              => "Bá pájina ho naran ne'e (se iha)",
'tooltip-p-logo'                 => 'Pájina Mahuluk',
'tooltip-n-mainpage'             => 'Vizita Pájina Mahuluk',
'tooltip-n-mainpage-description' => 'Vizita Pájina Mahuluk',
'tooltip-n-portal'               => "Kona-ba projetu, ne'ebé ó bele halo, iha ne'ebé ó hetan saida",
'tooltip-n-recentchanges'        => "Lista mudansa foufoun sira iha wiki ne'e.",
'tooltip-n-randompage'           => 'Hola pájina ruma',
'tooltip-n-help'                 => 'Hatudu pájina ajuda.',
'tooltip-t-whatlinkshere'        => "Lista pájina nian ne'ebé bá iha ne'e",
'tooltip-t-contributions'        => "Haree lista kontribuisaun uza-na'in ne'e nian",
'tooltip-t-emailuser'            => 'Haruka korreiu eletróniku',
'tooltip-t-upload'               => 'Tau iha arkivu laran',
'tooltip-t-specialpages'         => 'Lista pájina espesiál hotu nian',
'tooltip-t-print'                => "Versaun pájina ne'e nian ba imprime/prin",
'tooltip-ca-nstab-main'          => 'Haree konteúdu pájina nian',
'tooltip-ca-nstab-user'          => "Haree pájina uza-na'in",
'tooltip-ca-nstab-special'       => "Ne'e pájina espesiál – Ita la bele edita",
'tooltip-ca-nstab-project'       => 'Haree pájina projetu nian',
'tooltip-ca-nstab-category'      => 'Lee pájina kategoria',
'tooltip-minoredit'              => "Marka ne'e hanesan muda ki'ik",
'tooltip-watch'                  => 'Tau pájina ne\'e ba Ita-nia "lista hateke"',

# Attribution
'siteuser'         => "uza-na'in {{SITENAME}} nian $1",
'lastmodifiedatby' => "Pájina ne'e $3 mak muda ba dala ikus iha $1, $2.",
'siteusers'        => "{{PLURAL:$2|uza-na'in|uza-na'in}} {{SITENAME}} nian $1",

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
'exif-make'         => 'Fabrikante kámara nian',
'exif-model'        => 'Kámara',
'exif-artist'       => 'Autór',
'exif-flash'        => 'Flax',
'exif-languagecode' => 'Lian',
'exif-iimcategory'  => 'Kategoria',

'exif-meteringmode-255' => 'Seluk',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'km iha oras',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'kilómetru',

'exif-gpsdop-good' => "Di'ak ($1)",

'exif-dc-date' => 'Data',

'exif-iimcategory-clj' => 'Krime no lei',
'exif-iimcategory-edu' => 'Edukasaun',
'exif-iimcategory-evn' => 'Meiu ambiente',
'exif-iimcategory-hth' => 'Saúde',
'exif-iimcategory-lab' => 'Traballu',
'exif-iimcategory-pol' => 'Polítika',
'exif-iimcategory-rel' => 'Relijiaun no fiar',
'exif-iimcategory-sci' => 'Siénsia i teknolojia',
'exif-iimcategory-spo' => 'Desportu',
'exif-iimcategory-war' => 'Funu no konflitu',
'exif-iimcategory-wea' => 'Tempu',

'exif-urgency-normal' => 'Normál ($1)',
'exif-urgency-low'    => 'Kraik ($1)',
'exif-urgency-high'   => 'Aas ($1)',

# External editor support
'edit-externally-help' => "(Haree [//www.mediawiki.org/wiki/Manual:External_editors iha ne'e] ba informasaun barak liu)",

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'hotu',
'namespacesall' => 'hotu',
'monthsall'     => 'hotu',
'limitall'      => 'hotu',

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
'watchlistedit-normal-title' => 'Edita lista hateke',
'watchlistedit-raw-titles'   => 'Títulu sira:',
'watchlistedit-raw-added'    => '{{PLURAL:$1|Títulu ida|Títulu $1}} tau tan tiha:',
'watchlistedit-raw-removed'  => '{{PLURAL:$1|Títulu ida|Títulu $1}} hasai tiha:',

# Watchlist editing tools
'watchlisttools-edit' => 'Haree no edita lista hateke',

# Special:Version
'version'                  => 'Versaun',
'version-specialpages'     => 'Pájina espesiál',
'version-other'            => 'Seluk',
'version-version'          => '(Versaun $1)',
'version-license'          => 'Lisensa',
'version-software-product' => 'Produtu',
'version-software-version' => 'Versaun',

# Special:FilePath
'filepath-page'   => 'Fail:',
'filepath-submit' => 'Bá',

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

# Special:Tags
'tags-edit' => 'edita',

# Special:ComparePages
'compare-page1'  => 'Pájina 1',
'compare-page2'  => 'Pájina 2',
'compare-rev1'   => 'Versaun 1',
'compare-rev2'   => 'Versaun 2',
'compare-submit' => 'Halo komparasaun',

# Database error messages
'dberr-header' => "Wiki ne'e iha problema",

);
