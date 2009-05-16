<?php
/** Waray (Winaray)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Estudyante
 * @author Harvzsf
 * @author לערי ריינהארט
 */

$specialPageAliases = array(
	'Watchlist'                 => array( 'Angay Timan-an' ),
	'Upload'                    => array( 'Pagkarga' ),
	'Statistics'                => array( 'Mga Estadistika' ),
	'Randompage'                => array( 'Bisan Ano', 'BisanAnongaPakli' ),
	'Mostlinked'                => array( 'Gidamo-iHinSumpay' ),
	'Shortpages'                => array( 'HaglipotngamgaPakli' ),
	'Longpages'                 => array( 'HaglabangamgaPakli' ),
	'Newpages'                  => array( 'Bag-ongamgaPakli' ),
	'Allpages'                  => array( 'NgatananngaPakli' ),
	'Specialpages'              => array( 'MgaIspisyalngaPakli' ),
	'Contributions'             => array( 'Mga Ámot' ),
	'Movepage'                  => array( 'BalhinaAnPakli' ),
	'Categories'                => array( 'Mga Kategorya' ),
	'Version'                   => array( 'Bersyon' ),
	'Mypage'                    => array( 'AkonPakli' ),
	'Mytalk'                    => array( 'AkonHiruhimangraw' ),
	'Search'                    => array( 'Bilnga' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Bagisa ha ilarom an mga sumpay:',
'tog-highlightbroken'         => 'Ig-format in gubâ nga mga sumpay <a href="" class="new">hin sugad hini</a> (alternatibo: hin sugad hini<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Ig-justify an mga parrapho',
'tog-hideminor'               => 'Tago-a an mga gagmay nga pagliwat ha mga bag-o pa la nga mga kabag-ohan',
'tog-hidepatrolled'           => 'Tago-a in mga gin-patrol o binantayan nga mga pagliwat ha mga dipala naiha nga mga kabag-ohan',
'tog-newpageshidepatrolled'   => 'Tago-a an mga gin-patrol o binantayan nga mga pakli tikang han talaan hin bag-o nga pakli',
'tog-extendwatchlist'         => 'Padako-a an angay timan-an agod makita an tanan nga kabag-ohan, diri la an gibag-ohi',
'tog-usenewrc'                => 'Gamit hin mga gin-enhans o gindugngan nga gibag-ohi nga mga kabag-ohan (nakinahanglan hin JavaScript)',
'tog-numberheadings'          => 'Auto-nga-ihap nga mga pagngaran',
'tog-showtoolbar'             => 'Igpakita an edit toolbar (nakinahanglan hin JavaScript)',
'tog-editondblclick'          => 'Igliwat in mga pakli ha doble nga klik (nakinahanglan hin JavaScript)',
'tog-editsection'             => 'Tugoti in seksyon nga pagliwat pinaagi hin [igliwat] nga mga sumpay',
'tog-editsectiononrightclick' => 'Tugoti in pagliwat hin seksyon ha pag klik-ha-tuo dida hin mga ngaran o titulo hin seksyon (nakinahanglan hin JavaScript)',
'tog-showtoc'                 => 'Igpakita in tabla hin sulod (para hin mga pakli nga sobra hin 3 ka titulo o pagngaran)',
'tog-rememberpassword'        => 'Hinumdomi an akon pan-sakob dinhi nga komputadora',
'tog-editwidth'               => 'Igpahaluag an kahon hin pagliwat agod magamitan an bug-os nga pabyon o pantalya',
'tog-watchcreations'          => 'Igdugang in mga pakli nga akon ginhimo ngadto han akon angay timan-an',
'tog-watchdefault'            => 'Igdugang in mga pakli nga akon ginliwat ngadto han akon angay timan-an',
'tog-watchmoves'              => 'Igdugang in mga pakli nga akon ginpamalhin ngadto han akon angay timan-an',
'tog-watchdeletion'           => 'Igdugang in mga pakli nga akon ginpamara ngadto han akon angay timan-an',
'tog-minordefault'            => 'Tigamni an ngatanan nga mga pagliwat nga gudti hin default',
'tog-previewontop'            => 'Igpakita in prevista o pan-ugsa-nga-lantaw ugsa hiton pagliwat nga kahon',
'tog-previewonfirst'          => 'Igpakita in prevista o pan-ugsa-nga-lantaw ha syahan nga pagliwat',
'tog-shownumberswatching'     => 'Igpakita an ihap han mga nangingita nga mga nagamit',
'tog-watchlisthideown'        => 'Tago-a an akon mga ginliwat tikang han angay timan-an',
'tog-watchlisthidebots'       => 'Tago-a an ginliwat hin bot tikang han angay timan-an',
'tog-watchlisthideminor'      => 'Tago-a an mga gagmay nga pagliwat tikang han angay timan-an',
'tog-ccmeonemails'            => 'Padad-i ak hin mga kopya hin mga email nga akon ginpapadara ha iba nga mga nágámit',
'tog-showhiddencats'          => 'Igpakita an mga tinago nga mga kategorya',

'underline-always' => 'Pirme',
'underline-never'  => 'Diri',

# Dates
'sunday'        => 'Dominggo',
'monday'        => 'Lunes',
'tuesday'       => 'Martes',
'wednesday'     => 'Miyerkoles',
'thursday'      => 'Huwebes',
'friday'        => 'Biyernes',
'saturday'      => 'Sabado',
'sun'           => 'Dom',
'mon'           => 'Lun',
'tue'           => 'Mar',
'wed'           => 'Mi',
'thu'           => 'Hu',
'fri'           => 'Bi',
'sat'           => 'Sab',
'january'       => 'Enero',
'february'      => 'Pebrero',
'march'         => 'Marso',
'april'         => 'Abril',
'may_long'      => 'Mayo',
'june'          => 'Hunyo',
'july'          => 'Hulyo',
'august'        => 'Agosto',
'september'     => 'Septyembre',
'october'       => 'Oktubre',
'november'      => 'Nobyembre',
'december'      => 'Disyembre',
'january-gen'   => 'han Enero',
'february-gen'  => 'han Pebrero',
'march-gen'     => 'han Marso',
'april-gen'     => 'han Abril',
'may-gen'       => 'han Mayo',
'june-gen'      => 'han Hunyo',
'july-gen'      => 'han Hulyo',
'august-gen'    => 'han Agosto',
'september-gen' => 'han Septyembre',
'october-gen'   => 'han Oktubre',
'november-gen'  => 'han Nobyembre',
'december-gen'  => 'han Disyembre',
'jan'           => 'Ene',
'feb'           => 'Peb',
'mar'           => 'Mar',
'apr'           => 'Abr',
'may'           => 'May',
'jun'           => 'Hun',
'jul'           => 'Hul',
'aug'           => 'Ago',
'sep'           => 'Sep',
'oct'           => 'Okt',
'nov'           => 'Nob',
'dec'           => 'Dis',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kaarangay|Mga kaarangay}}',
'category_header'                => 'Mga pakli ha kaarangay nga "$1"',
'subcategories'                  => 'Mga ubos-nga-kaarangay',
'category-media-header'          => 'Media ha kaarangay nga "$1"',
'category-empty'                 => "''Ini nga kaarangay ha yana waray mga pakli o media.''",
'hidden-categories'              => '{{PLURAL:$1|Tinago nga kaarangay|Tinago nga mga kaarangay}}',
'hidden-category-category'       => 'Tinago nga mga kaarangay', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|Ini nga kaarangay mayda amo la nga nasunod nga ubos-nga-kaarangay.|Ini nga kaarangay mayda han mga nasunod nga {{PLURAL:$1|ubos-nga-kaarangay|$1 nga mga ubos-nga-kaarangay}}, tikang hin $2 nga kabug-osan.}}',
'category-subcat-count-limited'  => 'Ini nga kaarangay mayda han nasunod nga {{PLURAL:$1|ubos-nga-kaarangay|$1 nga mga ubos-nga-kaarangay}}.',
'category-article-count'         => '{{PLURAL:$2|Ini nga kaarangay mayda han amo la nga nasunod nga pakli.|An mga nasunod nga {{PLURAL:$1|ka pakli|$1 ka mga pakli}} aada hini nga kaarangay, tikang hin $2 nga kabug-osan.}}',
'category-article-count-limited' => 'An mga nasunod nga {{PLURAL:$1|ka pakli|$1 ka mga pakli aada}} han yana nga kaarangay.',
'category-file-count'            => '{{PLURAL:$2|Ini nga kaarangay mayda hin amo la nga fayl.|An mga nasunod nga {{PLURAL:$1|ka fayl|$1 ka mga fayl aada}} han hini nga kaarangay, tikang hin $2 nga kabug-osan.}}',
'category-file-count-limited'    => 'An mga nasunod nga {{PLURAL:$1|ka fayl|$1 ka mga faly aada}} han yana nga kaarangay.',
'listingcontinuesabbrev'         => 'pdyn.',

'linkprefix'        => '/^(.*?)([a-zA-Z\\x80-\\xff]+)$/sD',
'mainpagetext'      => "<big>'''Malinamposon an pag-instalar han MediaWiki.'''</big>",
'mainpagedocfooter' => "Kitaa an [http://meta.wikimedia.org/wiki/Help:Contents User's Guide] para hin impormasyon ha paggamit han wiki nga softweyr.

== Ha pagtikang==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'about'          => 'Mahitungod han',
'article'        => 'Pakli hin sulod',
'newwindow'      => '(nabuklad hin bag-o nga tamboan o bintana)',
'cancel'         => 'Igkanselar',
'qbfind'         => 'Bilnga',
'qbbrowse'       => 'Igdalikyat',
'qbedit'         => 'Igliwat',
'qbpageoptions'  => 'Ini nga pakli',
'qbpageinfo'     => 'Kontexto',
'qbmyoptions'    => 'Akon mga pakli',
'qbspecialpages' => 'Mga ispisyal nga pakli',
'moredotdotdot'  => 'Damo pa nga…',
'mypage'         => 'Akon pakli',
'mytalk'         => 'Akon paghingay',
'anontalk'       => 'Paghingay para hini nga IP',
'navigation'     => 'Paglayag',
'and'            => '&#32;ngan',

# Metadata in edit box
'metadata_help' => 'Metadata:',

'errorpagetitle'    => 'Sayop',
'returnto'          => 'Balik ngadto ha $1.',
'tagline'           => 'Tikang ha {{SITENAME}}',
'help'              => 'Bulig',
'search'            => 'Bilnga',
'searchbutton'      => 'Bilnga',
'go'                => 'Kadto-a',
'searcharticle'     => 'Kadto-a',
'history'           => 'Kaagi han pakli',
'history_short'     => 'Kaagi',
'info_short'        => 'Impormasyon',
'printableversion'  => 'Maipapatik nga bersyon',
'permalink'         => 'Sumpay nga unob',
'print'             => 'Igpatik',
'edit'              => 'Igliwat',
'create'            => 'Himo-a',
'editthispage'      => 'Igliwat ini nga pakli',
'create-this-page'  => 'Himo-a ini nga pakli',
'delete'            => 'Para-a',
'deletethispage'    => 'Para-a ini nga pakli',
'undelete_short'    => 'Igkansela an pagpara {{PLURAL:$1|usa nga pagliwat|$1 nga mga pagliwat}}',
'protect'           => 'Panalipdi',
'protect_change'    => 'balyo-a',
'protectthispage'   => 'Panalipdi ini nga pakli',
'unprotect'         => 'Kuhaa an panalipod',
'unprotectthispage' => 'Kuhaa an panalipod hini nga pakli',
'newpage'           => 'Bag-o nga pakli',
'talkpage'          => 'Pakighimangraw hiunong hini nga pakli',
'talkpagelinktext'  => 'Hiruhimangraw',
'specialpage'       => 'Ispisyal nga Pakli',
'personaltools'     => 'Kalugaringon nga mga garamiton',
'postcomment'       => 'Bag-o nga bahin',
'articlepage'       => 'Kitaa in may sulod nga pakli',
'talk'              => 'Hiruhimangraw',
'views'             => 'Mga paglantaw',
'toolbox'           => 'Garamiton',
'userpage'          => 'Kitaa in pakli hin nágámit',
'projectpage'       => 'Kitaa in pakli hin proyekto',
'imagepage'         => 'Kitaa in pakli hin fayl',
'mediawikipage'     => 'Kitaa in pakli hin mensahe',
'templatepage'      => 'Kitaa in pakli hin plantilya',
'viewhelppage'      => 'Kitaa in pakli hin bulig',
'categorypage'      => 'Kitaa in pakli hin kategorya',
'viewtalkpage'      => 'Kitaa in hiruhimangraw',
'otherlanguages'    => 'Ha iba nga mga yinaknan',
'redirectedfrom'    => '(Ginredirekta tikang ha $1)',
'redirectpagesub'   => 'Redirek nga pakli',
'lastmodifiedat'    => 'Ini nga pakli kataposan ginliwat dida han $1, han $2.', # $1 date, $2 time
'viewcount'         => 'Ini nga pakli ginkanhi hin {{PLURAL:$1|makausa|$1 ka beses}}.',
'protectedpage'     => 'Ginpanalipdan nga pakli',
'jumpto'            => 'Laktaw ngadto ha:',
'jumptonavigation'  => 'paglayag',
'jumptosearch'      => 'bilnga',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Mahitungod han {{SITENAME}}',
'aboutpage'            => 'Project:Mahitungod han',
'copyright'            => 'In sulod mabiblingan ha ilarom han $1.',
'copyrightpagename'    => '{{SITENAME}} kopirayt',
'copyrightpage'        => '{{ns:project}}:Mga kopirayt',
'currentevents'        => 'Mga panhitabo',
'currentevents-url'    => 'Project:Mga panhitabo',
'disclaimers'          => 'Mga Disclaimer',
'disclaimerpage'       => 'Project:Kasahiran nga disclaimer',
'edithelp'             => 'Bulig hin pagliwat',
'edithelppage'         => 'Help:Pagliwat',
'faq'                  => 'AGG',
'faqpage'              => 'Project:AGG',
'helppage'             => 'Help:Sulod',
'mainpage'             => 'Syahan nga Pakli',
'mainpage-description' => 'Syahan nga Pakli',
'policy-url'           => 'Project:Polisiya',
'portal'               => 'Ganghaan han Komunidad',
'portal-url'           => 'Project:Ganghaan han Komunidad',
'privacy'              => 'Polisiya hin pribasidad',
'privacypage'          => 'Project:Polisiya hin pribasidad',

'badaccess'        => 'Pagtugot nga sayop',
'badaccess-group0' => 'Diri ka gintutugutan pagbuhat han buruhaton nga imo ginhangyo.',
'badaccess-groups' => 'An buruhaton nga imo ginhangyo gintutugotan la ha mga nágámit {{PLURAL:$2|han grupo|hin usa han mga grupo}}: $1.',

'versionrequired'     => 'Kinahanglan an Bersion $1 han MediaWiki',
'versionrequiredtext' => 'Kinahanglan an Bersyon $1 han MediaWiki ha paggamit hini nga pakli.  Kitaa an [[Special:Version|bersyon nga pakli]].',

'ok'                      => 'OK',
'pagetitle'               => '$1 - {{SITENAME}}',
'pagetitle-view-mainpage' => '{{SITENAME}}',
'retrievedfrom'           => 'Ginkuha tikang ha "$1"',
'youhavenewmessages'      => 'Mayda ka $1 ($2).',
'newmessageslink'         => 'bag-o nga mga mensahe',
'newmessagesdifflink'     => 'kataposan nga pagbag-o',
'youhavenewmessagesmulti' => 'Mayda ka mga bag-o nga mensahe ha $1',
'editsection'             => 'igliwat',
'editsection-brackets'    => '[$1]',
'editold'                 => 'igliwat',
'viewsourceold'           => 'kitaa an ginkuhaan',
'editlink'                => 'igliwat',
'viewsourcelink'          => 'kitaa an ginkuhaan',
'editsectionhint'         => 'Igliwat in bahin: $1',
'toc'                     => 'Sulod',
'showtoc'                 => 'igpakita',
'hidetoc'                 => 'tago-a',
'thisisdeleted'           => '¿Kitaa o balika in $1?',
'viewdeleted'             => '¿Kitaa in $1?',
'restorelink'             => '{{PLURAL:$1|usa nga ginpara nga pagliwat|$1 ka ginpara nga mga pagliwat}}',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artikulo',
'nstab-special'   => 'Ispisyal nga pakli',
'nstab-project'   => 'Pakli han proyekto',
'nstab-image'     => 'Fayl',
'nstab-mediawiki' => 'Mensahe',
'nstab-template'  => 'Plantilya',
'nstab-help'      => 'Pakli hin bulig',
'nstab-category'  => 'Kaarangay',

# Main script and global functions
'nosuchaction'      => 'Waray sugad nga buhat',
'nosuchactiontext'  => 'An buhat nga gin-ispisipikar han URL diri puyde.
Bangin la, nagsayop ka pagmakinilya han URL, o sinmunod hin sayop nga sumpay.
Bangin liwat ini usa nga bug dida han software nga ginagamit han {{SITENAME}}.',
'nosuchspecialpage' => 'Waray sugad nga ispisyal nga pakli',
'nospecialpagetext' => "<big>'''Naghangyo ka hin diri-puyde nga ispisyal nga pakli.'''</big>

In lista o talaan hin puyde nga mga ispisyal nga pakli mabibilngan ha [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error'           => 'Sayop',
'databaseerror'   => 'Sayop hin database',
'dberrortext'     => 'Mayda nahinabo nga database nga kwery nga syntax nga sayop.
Bangin ini nagpapakita hin bug dida han softweyr.
An kataposan nga ginsari nga database nga kweri amo in:
<blockquote><tt>$1</tt></blockquote>
tikang ha sakob han funsyon nga "<tt>$2</tt>".
MySQL nagbalik hin sayop nga "<tt>$3: $4</tt>".',
'dberrortextcl'   => 'Mayda nahinabo nga database nga kwery nga syntax nga sayop. 
An kataposan nga ginsari nga database nga kweri amo in:
"$1"
tikang ha sakob han funsyon nga "$2". 
MySQL nagbalik hin sayop nga "$3: $4".',
'laggedslavemode' => 'Bantay: It pakli bangin waray mga kabag-ohan nga bag-o.',
'readonly'        => 'Gintrankahan an database',
'enterlockreason' => 'Pagbutang hin rason para han pagtrangka. upod hin banabana kon san-o kukuha-on an pagtrangka',

# Login and logout pages
'login'                   => 'Sakob',
'nav-login-createaccount' => 'Sakob / paghimo hin bag-o nga akawnt',
'userlogin'               => 'Sakob/Pagrehistro',
'logout'                  => 'Gawas',
'userlogout'              => 'Gawas',
'yourlanguage'            => 'Yinaknan:',
'gender-male'             => 'Lalaki',
'loginerror'              => 'Sayop hin pagsakob',
'loginlanguagelabel'      => 'Yinaknan: $1',

# History pages
'viewpagelogs' => 'Kitaa an mga log para hini nga pakli',
'next'         => 'sunod',
'last'         => 'kataposan',
'page_first'   => 'syahan',
'page_last'    => 'kataposan',

# Search results
'prevn'          => 'naha-una nga $1',
'nextn'          => 'sunod nga $1',
'viewprevnext'   => 'Kitaa an ($1) ($2) ($3)',
'searchhelp-url' => 'Help:Sulod',
'powersearch'    => 'Bilnga',

# Preferences page
'preferences'       => 'Mga karuyag',
'mypreferences'     => 'Akon mga karuyag',
'datetime'          => 'Pitsa ngan oras',
'searchresultshead' => 'Bilnga',
'timezonelegend'    => 'Zona hin oras',
'localtime'         => 'Oras nga lokal',

# Recent changes
'recentchanges'   => 'Mga kabag-ohan',
'hide'            => 'Tago-a',
'minoreditletter' => 'g',
'newpageletter'   => 'B',

# Recent changes linked
'recentchangeslinked' => 'Mga may kalabotan nga binag-o',

# Upload
'upload'    => 'Pagkarga hin file',
'uploadbtn' => 'Igkarga an file',

# Special:ListFiles
'listfiles_date' => 'Pitsa',
'listfiles_name' => 'Ngaran',

# File description page
'filehist-datetime' => 'Pitsa/Oras',
'imagelinks'        => 'Mga sumpay',
'linkstoimage'      => 'Nasumpay hini nga fayl an mga nasunod nga mga pakli:',
'nolinkstoimage'    => 'Waray mga pakli nga nasumpay hini nga fayl.',
'sharedupload'      => 'Ini nga fayl tikang han $1 ngan puyde magamit ha iba nga mga proyekto.', # $1 is the repo name, $2 is shareduploadwiki(-desc)
'shareduploadwiki'  => 'Alayon pagkita han $1 para hin dugang nga impormasyon.',

# Unused templates
'unusedtemplateswlh' => 'iba nga mga sumpay',

# Random page
'randompage' => 'Bisan ano nga pakli',

# Statistics
'statistics' => 'Mga estadistika',

# Miscellaneous special pages
'unusedcategories' => 'Waray kagamit nga mga kaarangay',
'unusedimages'     => 'Waray kagamit nga mga fayl',
'longpages'        => 'Haglaba nga mga pakli',
'move'             => 'Balhina',

# Book sources
'booksources-go' => 'Kadto-a',

# Special:AllPages
'allpages'       => 'Ngatanan nga mga pakli',
'allarticles'    => 'Ngatanan nga mga artikulo',
'allpagesprev'   => 'Naha-una',
'allpagesnext'   => 'Sunod',
'allpagessubmit' => 'Kadto-a',

# Special:Categories
'categories'                    => 'Mga kaarangay',
'categoriesfrom'                => 'Igpakita in mga kaarangay nga natikang ha:',
'special-categories-sort-count' => 'igtalaan ha pag-ihap',
'special-categories-sort-abc'   => 'igtalaan ha abakadahan',

# Special:DeletedContributions
'deletedcontributions' => 'Mga ginpara nga mga ámot hin nágámit',

# Watchlist
'watchlist'     => 'Akon barantayan',
'mywatchlist'   => 'Akon angay timan-an',
'watch'         => 'Bantayi',
'watchthispage' => 'Bantayi ini nga pakli',

# Delete
'deletedtext' => 'Ginpara an "<nowiki>$1</nowiki>".
Kitaa an $2 para hin talaan han mga gibag-ohi nga mga ginpamara.',

# Contributions
'mycontris' => 'Akon mga ámot',

# What links here
'whatlinkshere' => 'Mga nasumpay dinhi',

# Block/unblock
'ipblocklist-submit' => 'Bilnga',

# Tooltip help for the actions
'tooltip-pt-logout' => 'gawas',

# Special:NewFiles
'ilsubmit' => 'Bilnga',

# Multipage image navigation
'imgmultipageprev' => '← naha-una nga pakli',
'imgmultipagenext' => 'sunod nga pakli →',

# Table pager
'table_pager_next'         => 'Sunod nga pakli',
'table_pager_prev'         => 'Naha-una nga pakli',
'table_pager_first'        => 'Una nga pakli',
'table_pager_last'         => 'Kataposan nga pakli',
'table_pager_limit'        => 'Igpakita in $1 nga mga item ha tagsa pakli',
'table_pager_limit_submit' => 'Kadto-a',

# Size units
'size-bytes'     => '$1 nga B',
'size-kilobytes' => '$1 nga KB',
'size-megabytes' => '$1 nga MB',
'size-gigabytes' => '$1 nga GB',

# Special:SpecialPages
'specialpages' => 'Mga Ispisyal nga Pakli',

);
