<?php
/** Tetum (Tetun)
 *
 * @addtogroup Language
 *
 * @author MF-Warburg
 */

$skinNames = array(
	'standard'    => 'Klásiku',
	'cologneblue' => 'Kolónia azúl',
	'myskin'      => 'MySkin',
	'chick'       => 'Manu',
);

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Espesiál',
	NS_MAIN	          => '',
	NS_TALK	          => 'Diskusaun',
	NS_USER           => "Uza-na'in",
	NS_USER_TALK      => "Diskusaun_Uza-na'in",
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => 'Diskusaun_$1',
	NS_IMAGE          => 'Imajen',
	NS_IMAGE_TALK     => 'Diskusaun_Imajen',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'Diskusaun_MediaWiki',
	NS_TEMPLATE       => 'Template',
	NS_TEMPLATE_TALK  => 'Diskusaun_Template',
	NS_HELP           => 'Ajuda',
	NS_HELP_TALK      => 'Diskusaun_Ajuda',
	NS_CATEGORY       => 'Kategoría',
	NS_CATEGORY_TALK  => 'Diskusaun_Kategoría',
);

$specialPageAliases = array(
	'Watchlist'                 => array( "Lista_hateke" ),
	'Recentchanges'             => array( "Mudansa_foufoun_sira" ),
	'Upload'                    => array( "Tau_iha_arkivu_laran" ),
	'Imagelist'                 => array( "Lista_imajen" ),
	'Newimages'                 => array( "Imajen_foun" ),
	'Listusers'                 => array( "Lista_uza-na'in" ),
	'Statistics'                => array( "Estatístika" ),
	'Randompage'                => array( "Pájina_ruma" ),
	'Shortpages'                => array( "Pájina_badak" ),
	'Longpages'                 => array( "Pájina_naruk" ),
	'Protectedpages'            => array( "Pájina_sira-ne'ebé_proteje_tiha" ),
	'Allpages'                  => array( "Pájina_hotu" ),
	'Ipblocklist'               => array( "Lista_ema_sira-ne'ebé_blokeiu_tiha" ),
	'Specialpages'              => array( "Pájina_espesiál_sira" ),
	'Whatlinkshere'             => array( "Pájina_sira_ne'ebé_bá_iha_ne'e" ),
	'Movepage'                  => array( "Book" ),
	'Categories'                => array( "Kategoría" ),
	'Version'                   => array( "Versaun" ),
	'Allmessages'               => array( "Mensajen_hotu" ),
	'Blockip'                   => array( "Blokeiu" ),
	'Undelete'                  => array( "Restaurar" ),
	'Userrights'                => array( "Kuana" ),
	'Mypage'                    => array( "Ha'u-nia_pájina" ),
	'Listadmins'                => array( "Lista_administradór" ),
	'Search'                    => array( "Buka" ),
);

$messages = array(
# User preference toggles
'tog-hideminor' => "Lá'os hatudu osan-rahun sira iha mudansa foufoun sira",

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

# Bits of text used by many pages
'categories'      => 'Kategoría',
'pagecategories'  => '{{PLURAL:$1|Kategoría|Kategoría}}',
'category_header' => 'Artigu iha kategoría "$1"',
'category-empty'  => "''Iha kategoría ne'e agora pájina lá'os.''",

'about'          => 'Kona-ba',
'article'        => 'Pájina',
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

'errorpagetitle'   => 'Sala',
'tagline'          => 'Husi {{SITENAME}}',
'help'             => 'Ajuda',
'search'           => 'Buka',
'searchbutton'     => 'Buka',
'go'               => 'Bá',
'history'          => 'Istória pájina',
'history_short'    => 'Istória',
'info_short'       => 'Informasaun',
'printableversion' => 'Versaun ba impresaun',
'permalink'        => 'Ligasaun mahelak',
'print'            => 'Imprime',
'edit'             => 'Edita',
'editthispage'     => "Edita pájina ne'e",
'delete'           => 'Halakon',
'deletethispage'   => "Halakon pájina ne'e",
'undelete_short'   => 'Restaurar {{PLURAL:$1|versaun ida|$1 versaun}}',
'protect'          => 'Proteje',
'protectthispage'  => "Proteje pájina ne'e",
'newpage'          => 'Pájina foun',
'talkpagelinktext' => 'Diskusaun',
'specialpage'      => 'Pájina espesiál',
'talk'             => 'Diskusaun',
'toolbox'          => 'Kaixa besi nian',
'userpage'         => "Haree pájina uza-na'in",
'projectpage'      => 'Haree pájina projetu nian',
'imagepage'        => 'Haree pájina imajen nian',
'mediawikipage'    => 'Haree pájina mensajen nian',
'viewhelppage'     => 'Haree pájina ajuda',
'categorypage'     => 'Haree pájina kategoría nian',
'viewtalkpage'     => 'Haree diskusaun',
'otherlanguages'   => 'Iha lian seluk',
'jumptonavigation' => 'hatudu-dalan',
'jumptosearch'     => 'buka',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Kona-ba {{SITENAME}}',
'aboutpage'         => 'Project:Kona-ba',
'copyright'         => 'Testu pájina nian iha $1 okos.',
'currentevents'     => 'Mamosuk atuál sira',
'currentevents-url' => '{{ns:project}}:Mamosuk atuál sira',
'disclaimers'       => 'Avisu legál',
'disclaimerpage'    => '{{ns:project}}:Avisu legál',
'edithelppage'      => '{{ns:help}}:Edita',
'mainpage'          => 'Pájina Mahuluk',
'portal'            => 'Portál komunidade nian',
'portal-url'        => '{{ns:project}}:Portál komunidade nian',
'privacy'           => 'Polítika privasidade nian',
'privacypage'       => '{{ns:project}}:Polítika privasidade nian',
'sitesupport'       => 'Fó donativu ida',

'badaccess-group0' => "Ó lalika halo ne'e.",
'badaccess-group1' => "Ba halo ne'e tenke iha lubu $1.",

'versionrequired'     => 'Presiza MediaWiki versaun $1',
'versionrequiredtext' => "Presiza MediaWiki versaun $1 ba uza pájina ne'e. Haree [[Special:Version|pájina versaun]].",

'ok'                      => 'OK',
'youhavenewmessages'      => 'Ó iha $1 ($2).',
'newmessageslink'         => 'mensajen foun',
'youhavenewmessagesmulti' => 'Ó iha mensajen foun sira iha $1',
'editsection'             => 'edita',
'editold'                 => 'edita',
'showtoc'                 => 'hatudu',
'hidetoc'                 => 'subar',
'thisisdeleted'           => 'Haree ka restaurar $1?',
'viewdeleted'             => 'Haree $1?',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Pájina',
'nstab-user'      => "Pájina uza-na'in",
'nstab-special'   => 'Espesiál',
'nstab-project'   => 'Pájina projetu nian',
'nstab-mediawiki' => 'Mensajen',
'nstab-help'      => 'Pájina ajuda',
'nstab-category'  => 'Kategoría',

# General errors
'error' => 'Sala',

# Login and logout pages
'yourname'           => "Naran uza-n'in:",
'nologinlink'        => "Registrar uza-na'in ida",
'createaccount'      => "Registrar uza-na'in",
'username'           => "Naran uza-na'in:",
'yourlanguage'       => 'Lian:',
'accountcreated'     => "Registrar tiha uza-na'in",
'loginlanguagelabel' => 'Lian: $1',

# Password reset dialog
'resetpass_text' => "<!-- Hakerek testu iha ne'e -->",

# Edit page toolbar
'image_sample' => 'Ezemplu.jpg',
'media_sample' => 'Ezemplu.ogg',

# Edit pages
'minoredit'         => "Ne'e osan-rahun",
'watchthis'         => "Hateke pájina ne'e",
'anoneditwarning'   => 'Ó lá\'os "log-in" iha momentu.',
'blockedtitle'      => "Uza-na'in nablokeiu",
'noarticletext'     => "Iha momentu lá'os testu iha pájina ne'e, bele [[Special:Search/{{PAGENAME}}|buka naran pájina nian]] iha pájina seluk ka [{{fullurl:{{FULLPAGENAME}}|action=edit}} edita pájina ne'e].",
'editing'           => 'Edita $1',
'editingcomment'    => 'Edita $1 (komentáriu)',
'yourtext'          => 'Ó-nia testu',
'yourdiff'          => 'Diferensa sira',
'nocreate-loggedin' => "Ó la iha kuana kria pájina foun iha wiki ne'e.",

# Account creation failure
'cantcreateaccounttitle' => "La bele registrar uza-na'in",

# History pages
'currentrev'          => 'Versaun atuál',
'previousrevision'    => '←Versaun tuan liu',
'nextrevision'        => 'Versaun foun liu→',
'currentrevisionlink' => 'Versaun atuál',
'cur'                 => 'atuál',
'next'                => 'oinmai',
'page_first'          => 'uluk',
'histfirst'           => 'sedu liu hotu',
'historyempty'        => '(mamuk)',

# Revision feed
'history-feed-item-nocomment' => '$1 iha $2', # user at time

# Revision deletion
'rev-delundel'        => 'hatudu/subar',
'revisiondelete'      => 'Halakon/restaurar versaun',
'revdelete-hide-user' => "Subar naran edita-na'in/IP",

# Diffs
'editcurrent' => "Edita versaun atuál pájina ne'e nian",

# Search results
'powersearch' => 'Buka',

# Preferences page
'prefs-rc'          => 'Mudansa foufoun sira',
'prefs-watchlist'   => 'Lista hateke',
'searchresultshead' => 'Buka',

# User rights
'userrights-user-editname' => "Hakerek naran uza-na'in ida-nian:",
'editusergroup'            => "Filak lubu uza-na'in",
'userrights-editusergroup' => "Filak lubu uza-na'in",
'userrights-groupsmember'  => 'Membru iha:',

# Groups
'group'            => 'Lubu:',
'group-bot'        => 'Bot sira',
'group-sysop'      => 'Administradór sira',
'group-bureaucrat' => 'Burokrata sira',
'group-all'        => '(hotu)',

'group-bot-member'        => 'Bot',
'group-sysop-member'      => 'Administradór',
'group-bureaucrat-member' => 'Burokrata',

'grouppage-bot'        => '{{ns:project}}:Bot sira',
'grouppage-sysop'      => '{{ns:project}}:Administradór sira',
'grouppage-bureaucrat' => '{{ns:project}}:Burokrata sira',

# User rights log
'rightsnone' => '(mamuk)',

# Recent changes
'recentchanges'   => 'Mudansa foufoun sira',
'rcshowhidebots'  => '$1 bot sira',
'diff'            => 'diferensa',
'hist'            => 'istória',
'hide'            => 'Hamsumik',
'show'            => 'Hatudu',
'minoreditletter' => 'o',
'newpageletter'   => 'F',
'boteditletter'   => 'b',

# Recent changes linked
'recentchangeslinked' => 'Muda sira',

# Upload
'upload'          => 'Tau iha arkivu laran',
'uploadbtn'       => 'Tau iha arkivu laran',
'watchthisupload' => "Hateke pájina ne'e",

# Image list
'ilsubmit'           => 'Buka',
'filehist-deleteall' => 'halakon hotu',
'filehist-deleteone' => "halakon ne'e",
'filehist-user'      => "Uza-na'in",
'imagelist_name'     => 'Naran',
'imagelist_user'     => "Uza-na'in",

# File reversion
'filerevert-comment' => 'Komentáriu:',

# File deletion
'filedelete'         => 'Halakon $1',
'filedelete-comment' => 'Komentáriu:',
'filedelete-submit'  => 'Halakon',

# Statistics
'statistics' => 'Estátistika',

'brokenredirects-edit'   => '(edita)',
'brokenredirects-delete' => '(halakon)',

# Miscellaneous special pages
'allpages'          => 'Pájina hotu',
'randompage'        => 'Pájina ruma',
'listusers'         => "Lista uza-na'in",
'specialpages'      => 'Pájina espesiál sira',
'newpages'          => 'Pájina foun',
'newpages-username' => "Naran uza-na'in:",
'move'              => 'Book',
'movethispage'      => "Book pájina ne'e",

'groups'  => "Lubu uza-na'in",
'version' => 'Versaun',

# Special:Log
'specialloguserlabel' => "Uza-na'in:",

# Watchlist
'watchlist'            => "Ha'u-nia lista hateke",
'mywatchlist'          => "Ha'u-nia lista hateke",
'watch'                => 'Hateke',
'watchthispage'        => "Hateke pájina ne'e",
'unwatch'              => 'La hateke',
'watchlist-hide-bots'  => 'Hamsumik edita "bot" sira',
'watchlist-hide-own'   => "Hamsumik edita ha'u-nia",
'watchlist-show-minor' => 'Hatudu osan-rahun',
'watchlist-hide-minor' => 'Subar osan-rahun',

'enotif_newpagetext' => "Ne'e pájina foun.",

# Delete/protect/revert
'deletepage'          => 'Halakon pájina',
'deletedarticle'      => 'halakon "[[$1]]"',
'dellogpage'          => 'Lista halakon',
'deletionlog'         => 'lista halakon',
'protectedarticle'    => 'proteje "[[$1]]"',
'protectcomment'      => 'Komentáriu:',
'protect-level-sysop' => "de'it administradór",

# Restrictions (nouns)
'restriction-edit' => 'Edita',
'restriction-move' => 'Book',

# Undelete
'undelete'           => 'Haree pájina halakon tiha',
'undeletebtn'        => 'Restaurar',
'undeletecomment'    => 'Komentáriu:',
'undeletedarticle'   => 'restaurar "[[$1]]"',
'undeletedrevisions' => 'restaurar $1 versaun',

'sp-contributions-newest' => 'Foun liu hotu',
'sp-contributions-oldest' => 'Tuan liu hotu',
'sp-contributions-newer'  => 'Foun liu $1',
'sp-contributions-older'  => 'Tuan liu $1',
'sp-contributions-submit' => 'Buka',

# What links here
'whatlinkshere'      => "Artigu sira ne'ebé bá iha ne'e",
'whatlinkshere-page' => 'Pájina:',
'linkshere'          => "Pájina sira ne'e link ba '''[[:$1]]''':",
'whatlinkshere-prev' => '{{PLURAL:$1|oinmai|oinmai $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|molok|molok $1}}',

# Block/unblock
'blockip'                 => "Blokeiu uza-na'in",
'ipbreason-dropdown'      => '*Common block reasons
** Inserting false information
** Removing content from pages
** Spamming links to external sites
** Inserting nonsense/gibberish into pages
** Intimidating behaviour/harassment
** Abusing multiple accounts
** Unacceptable username
** W/index.php vandal
** vandalism
** creating English pages in Main namespace',
'ipbotheroption'          => 'seluk',
'ipblocklist-username'    => "Naran uza-na'in ka IP:",
'ipblocklist-submit'      => 'Buka',
'blocklink'               => 'blokeiu',
'block-log-flags-noemail' => 'korreiu eletróniku blokeiu',
'ipb_already_blocked'     => '"$1" nablokeiu tiha ona',

# Move page
'movepage'                => 'Book pájina',
'movearticle'             => 'Book pájina:',
'movepagebtn'             => 'Book pájina',
'movepage-moved'          => '<big>\'\'\'Ó book "$1" ba "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'1movedto2'               => 'book tiha [[$1]] ba [[$2]]',
'movelogpage'             => 'Lista book',
'delete_and_move'         => 'Halakon ho book',
'delete_and_move_confirm' => 'Sin, halakon pájina',

# Namespace 8 related
'allmessagesname'    => 'Naran',
'allmessagescurrent' => 'Testu atuál',

# Browsing diffs
'previousdiff' => '←Diferensa molok',
'nextdiff'     => 'Diferensa oinmai→',

# Special:Newimages
'showhidebots' => '($1 bot sira)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'hotu',
'imagelistall'     => 'hotu',
'watchlistall2'    => 'hotu',
'namespacesall'    => 'hotu',
'monthsall'        => 'hotu',

# Table pager
'table_pager_next'  => 'Pájina oinmai',
'table_pager_prev'  => 'Pájina molok',
'table_pager_first' => 'Pájina uluk',

# Auto-summaries
'autosumm-new' => 'Pájina foun: $1',

);
