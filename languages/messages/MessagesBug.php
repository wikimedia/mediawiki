<?php
/** Buginese (ᨅᨔ ᨕᨘᨁᨗ)
 *
 * @ingroup Language
 * @file
 *
 * @author Kurniasan
 */

$fallback = "id";

$messages = array(
# Dates
'sunday'    => 'ᨕᨕᨖ',
'monday'    => 'ᨕᨔᨛᨙᨊ',
'tuesday'   => 'ᨔᨒᨔ',
'wednesday' => 'ᨕᨑᨅ',
'thursday'  => 'ᨀᨆᨗᨔᨗ',
'friday'    => 'ᨍᨘᨆᨕ',
'saturday'  => 'ᨔᨈᨘ',
'january'   => 'ᨙᨍᨊᨘᨕᨑᨗ',
'february'  => 'ᨙᨄᨅᨛᨑᨘᨕᨑᨗ',
'march'     => 'ᨆᨙᨑ',
'april'     => 'ᨕᨄᨛᨑᨗᨒᨗ',
'may_long'  => 'ᨙᨆᨕᨗ',
'june'      => 'ᨍᨘᨊᨗ',
'july'      => 'ᨍᨘᨒᨗ',
'august'    => 'ᨕᨁᨘᨔᨘᨈᨘᨔᨘ',
'september' => 'ᨙᨔᨙᨈᨇᨛᨑᨛ',
'october'   => 'ᨕᨚᨀᨛᨈᨚᨅᨛᨑᨛ',
'november'  => 'ᨊᨚᨙᨅᨇᨛᨑᨛ',
'december'  => 'ᨉᨗᨙᨔᨇᨛᨑᨛ',

# Categories related messages
'category_header' => 'ᨒᨛᨄ ᨑᨗᨒᨒᨛ ᨙᨀᨈᨛᨁᨚᨑᨗ "$1"',
'subcategories'   => 'ᨔᨅᨛᨙᨀᨈᨛᨁᨚᨈᨗ',

'about'          => 'Atajangeng',
'article'        => 'Lontara',
'qbfind'         => 'Assapparang',
'qbbrowse'       => 'Berowoso',
'qbedit'         => 'Padécéŋ',
'qbpageoptions'  => 'Édé leppa',
'qbmyoptions'    => "Leppana iya'",
'qbspecialpages' => 'Leppa spésiala',
'mypage'         => "Leppana iya'",
'mytalk'         => "bicara iya'",
'anontalk'       => 'Bicara IP',
'navigation'     => 'napigasi',
'and'            => 'éréngé',

'tagline'          => 'Polé {{SITENAME}}',
'help'             => 'Panginriŋ',
'search'           => 'assappa',
'searchbutton'     => 'Sappa',
'go'               => 'Lao',
'searcharticle'    => 'Lao',
'history'          => 'Versi riolo leppaë',
'history_short'    => 'versi riolo',
'edit'             => 'padécé',
'create'           => 'Ebbu',
'editthispage'     => 'Padécéŋiki iyé leppa',
'create-this-page' => 'Ebbuiki leppa iyé',
'delete'           => 'peddé',
'deletethispage'   => 'Peddé iyé leppa',
'talkpage'         => 'Bicara iyé leppa',
'talkpagelinktext' => 'Bicara',
'specialpage'      => 'Leppa spésiala',
'articlepage'      => 'Ita lontara',
'talk'             => 'Bicara',
'toolbox'          => 'Toolbox',
'userpage'         => 'Ita leppa papaké',
'projectpage'      => 'Ita leppa proyék',
'imagepage'        => 'Ita leppa rapaŋ',
'mediawikipage'    => 'Ita leppa méséje',
'templatepage'     => 'Ita leppa templata',
'viewhelppage'     => 'Ita leppa panginriŋ',
'categorypage'     => 'Ita leppa kategori',
'viewtalkpage'     => 'Ita leppa bicara',
'redirectedfrom'   => '(Riredirect polé $1)',
'redirectpagesub'  => 'Leppa redirect',
'jumptosearch'     => 'sappa',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Tentang {{SITENAME}}',
'currentevents'        => 'Accanjingeŋ kokkoro',
'currentevents-url'    => 'Project:Accanjingeŋ kokkoro',
'disclaimers'          => 'Diseklaima',
'edithelp'             => 'Panginriŋ mapadécé',
'edithelppage'         => 'Help:Mapadécé',
'faq'                  => 'FAQ',
'faqpage'              => 'Project:FAQ',
'mainpage'             => 'Leppa Indoë',
'mainpage-description' => 'Leppa Indoë',
'portal'               => 'Portal komunitas',

'editsection' => 'ᨙᨕᨉᨗ',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ᨒᨛᨄ',
'nstab-user'      => 'ᨒᨛᨄ ᨄᨁᨘᨊ',
'nstab-special'   => 'ᨔᨛᨙᨄᨔᨗᨕᨒ',
'nstab-image'     => 'Rapang',
'nstab-mediawiki' => 'Méséje',
'nstab-template'  => 'Templata',
'nstab-help'      => 'Leppa panginriŋ',
'nstab-category'  => 'Kategori',

# Main script and global functions
'nosuchspecialpage' => "Iyaro leppa spésiala dé'na eŋka",

# General errors
'error'               => 'Éro',
'databaseerror'       => 'Éro databése',
'readonly'            => 'Databése rikonci',
'missingarticle-diff' => '(Beda: $1, $2)',
'internalerror'       => 'Éro internal',
'internalerror_info'  => 'Éro internal: $1',
'badtitle'            => 'Judul dek essa',
'viewsource'          => 'Ita sorese',
'viewsourcefor'       => 'polé $1',

# Virus scanner
'virus-unknownscanner' => "Antivirus dé' riisseŋ:",

# Login and logout pages
'logouttitle'             => 'Log maessu papaké',
'loginpagetitle'          => 'Log mattama papaké',
'yourname'                => 'Aseŋ papaké:',
'yourpassword'            => 'Pasewodo:',
'login'                   => 'log attama',
'nav-login-createaccount' => 'Log attama / ebbu akun',
'userlogin'               => 'Log attama / ebbu akun',
'logout'                  => 'Log essu',
'userlogout'              => 'Log essu',
'notloggedin'             => 'Déppa log attama',
'nologin'                 => "Dé' gaga akaun? $1.",
'nologinlink'             => 'Ebbu akun',
'createaccount'           => 'Ebbu akun',
'gotaccount'              => 'Purani eŋka akun? $1.',
'gotaccountlink'          => 'Log attama',
'youremail'               => 'E-mail:',
'username'                => 'Aseŋ papaké:',
'uid'                     => 'ID papaké:',
'email'                   => 'E-mail',
'loginerror'              => 'Éro log attama',
'mailmypassword'          => 'E-mail pasewodo baru',

# Edit page toolbar
'bold_tip'    => 'ᨙᨈᨀᨛᨔᨛ ᨆᨕᨘᨇᨛ',
'italic_tip'  => 'Teks Italik',
'extlink_tip' => 'Link risaliweŋ (jangan lupa awalan http:// )',

# Edit pages
'savearticle'      => 'Save leppa',
'preview'          => 'Pribiu',
'showpreview'      => 'Ita pribiu',
'showlivepreview'  => 'Pribiu live',
'showdiff'         => 'Mita perubahan',
'summary-preview'  => 'Pribiu summary',
'blockedtitle'     => 'Papaké riblok',
'accmailtitle'     => 'Ada sandi ni riantarak.',
'accmailtext'      => 'Ada sandi "$1" riantarak ri $2.',
'anontalkpagetext' => "----''Ini adalah halaman diskusi untuk pengguna anonim yang belum membuat rekening atau tidak menggunakannya. Karena tidak membuat rekening, kami terpaksa memakai alamat IP untuk mengenalinya. Alamat IP seperti ini dapat dipakai oleh beberapa pengguna yang berbeda. Jika Anda adalah pengguna anonim dan merasa mendapatkan komentar-komentar yang tidak berkaitan dengan anda, kami anjurkan untuk [[Special:UserLogin|membuat rekening atau masuk log]] untuk menghindari kerancuan dengan pengguna anonim lain.''",
'editing'          => 'ᨙᨕᨉᨗᨈᨗ $1',

# Recent changes
'recentchanges'   => 'Pappakinra tanappa',
'rcshowhidebots'  => '$1 bot',
'rcshowhideliu'   => "$1 papaké mattama' log",
'rcshowhideanons' => '$1 papaké anon',
'diff'            => 'beda',
'hide'            => 'Tapok',
'minoreditletter' => 'k',
'newpageletter'   => 'B',
'boteditletter'   => 'b',

# Recent changes linked
'recentchangeslinked' => 'Pappakapinra terkait',

# Upload
'upload'    => 'Lureng berkas',
'uploadbtn' => 'Lureng berkas',

# Random page
'randompage' => 'Halamang rawak',

# Miscellaneous special pages
'ancientpages' => 'Artikel talloa',
'move'         => 'ᨙᨕᨔᨘ',
'movethispage' => 'ᨙᨕᨔᨘᨀᨗ ᨕᨗᨙᨐᨙᨉ ᨒᨛᨄ',

# Special:Log
'specialloguserlabel' => 'Papaké:',
'log'                 => 'Log',
'all-logs-page'       => 'Maneŋ log',

# Special:AllPages
'allpages'          => 'Maneng halamang',
'alphaindexline'    => '$1 ri $2',
'allpagesfrom'      => 'Mappaitang halamang-halamang rimulai:',
'allarticles'       => 'Maneŋ leppa',
'allinnamespace'    => 'Maneŋ leppa (namespace $1)',
'allnotinnamespace' => 'Maneŋ leppa (tania rilaleŋ namespace $1)',
'allpagesnext'      => 'Selanjutnya',
'allpagessubmit'    => 'Lanre',
'allpagesprefix'    => 'Mappaitang halamang-halamang éngkalinga awang:',

# Special:Categories
'categories' => 'Maneŋ kategori',

# Watchlist
'addedwatch'     => 'Tamba ri jagaan',
'addedwatchtext' => "Halamang \"[[:\$1]]\" ni ritamba ri ida [[Special:Watchlist|watchlist]].
Halamang bicara éréngé gabungan halamang bicara pada wettu depan didaftarkan koe,
éréngé halamang akan wessi '''umpek''' ri [[Special:RecentChanges|daftar pinra tanappa]] barak lebih lemmak ita.

Apak ida ronnak mappedde halamang édé ri daftar jagaan, klik \"Mangedda jaga\" pada kolom ri sedde.",

# Delete
'actioncomplete' => 'Proses makkapo',

# Protect
'prot_1movedto2' => '[[$1]] ésuk ri [[$2]]',

# Namespace form on various pages
'blanknamespace' => '(Utama)',

# What links here
'whatlinkshere' => 'Pranala ri halamang édé',

# Block/unblock
'ipblocklist-submit' => 'Sappa',
'blocklink'          => 'blok',
'contribslink'       => 'kontrib',

# Move page
'articleexists' => 'Halamang béla ida pile ni ujuk, a dek essa.
Silakan pile aseng laing.',
'1movedto2'     => '[[$1]] ésuk ri [[$2]]',

# Namespace 8 related
'allmessages'        => 'Maneng pappaseng',
'allmessagesname'    => 'Aseng',
'allmessagesdefault' => 'Teks totok',
'allmessagescurrent' => 'Teks kokkoro',

# Tooltip help for the actions
'tooltip-pt-userpage'    => "Leppa papaké iya'",
'tooltip-pt-mytalk'      => "Leppa bicara iya'",
'tooltip-pt-preferences' => "Preferencena iya'",
'tooltip-pt-logout'      => 'Log maessu',
'tooltip-ca-talk'        => 'Appabiranna iyé leppa',

# Attribution
'anonymous' => 'Pabbuak anonim {{SITENAME}}',

# Media information
'imagemaxsize' => 'Gangkai rapang pada keterangan rapang ri halamang hingga:',

# Special:NewImages
'ilsubmit' => 'ᨔᨄ',

# 'all' in various places, this might be different for inflected languages
'imagelistall' => 'maneng',

# Special:Version
'version-specialpages' => 'Leppa spésiala',

# Special:SpecialPages
'specialpages' => 'Halamang Istimewa',

);
