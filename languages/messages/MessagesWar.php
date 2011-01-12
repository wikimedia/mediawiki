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
 * @author JinJian
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
'tog-rememberpassword'        => 'Hinumdomi an akon pan-sakob dinhi nga komputadora (para hin maximum nga $1 {{PLURAL:$1|nga adlaw|nga mga adlaw}})',
'tog-watchcreations'          => 'Igdugang in mga pakli nga akon ginhimo ngadto han akon angay timan-an',
'tog-watchdefault'            => 'Igdugang in mga pakli nga akon ginliwat ngadto han akon angay timan-an',
'tog-watchmoves'              => 'Igdugang in mga pakli nga akon ginpamalhin ngadto han akon angay timan-an',
'tog-watchdeletion'           => 'Igdugang in mga pakli nga akon ginpamara ngadto han akon angay timan-an',
'tog-minordefault'            => 'Tigamni an ngatanan nga mga pagliwat nga gudti hin default',
'tog-previewontop'            => 'Igpakita in prevista o pan-ugsa-nga-lantaw ugsa hiton pagliwat nga kahon',
'tog-previewonfirst'          => 'Igpakita in prevista o pan-ugsa-nga-lantaw ha syahan nga pagliwat',
'tog-nocache'                 => 'Ayaw patiroka an mga pakli nga pamiling',
'tog-enotifwatchlistpages'    => 'Ig-e-mail ako kun may nagbag-o ha pakli nga akon gintitiman-an (watchlist)',
'tog-enotifusertalkpages'     => 'Ig-e-mail ako kun may nagbag-o han akon pakli-himangrawon',
'tog-enotifminoredits'        => 'Ig-e-mail liwat ako ha mga gudti nga mga pagliwat hin mga pakli',
'tog-enotifrevealaddr'        => 'Igpakita an akon e-mail nga adres ha mga e-mail hin pagsumat',
'tog-shownumberswatching'     => 'Igpakita an ihap han mga nangingita nga mga nagamit',
'tog-oldsig'                  => 'Pahiuna nga pagawas han aada nga pirma:',
'tog-fancysig'                => 'Tratuha it pirma komo uska wikitext (nga waray automatiko nga sumpay)',
'tog-uselivepreview'          => 'Gamita an buhi nga pahiuna nga pagawas (nagkikinahanglan hin JavaScript) (eksperimental)',
'tog-forceeditsummary'        => 'Pasabti ako kun waray ko ginsurat ha dalikyat-nga-tigaman han pagliwat (edit summary)',
'tog-watchlisthideown'        => 'Tago-a an akon mga ginliwat tikang han angay timan-an',
'tog-watchlisthidebots'       => 'Tago-a an ginliwat hin bot tikang han angay timan-an',
'tog-watchlisthideminor'      => 'Tago-a an mga gagmay nga pagliwat tikang han angay timan-an',
'tog-watchlisthideanons'      => 'Igtago an mga ginliwat han mga waray nagpakilala nga nagamit tikang ha gintitiman-an',
'tog-ccmeonemails'            => 'Padad-i ak hin mga kopya hin mga email nga akon ginpapadara ha iba nga mga nágámit',
'tog-diffonly'                => 'Ayaw igpakita an sulod han pakli ha ilarom han pagkakaiba',
'tog-showhiddencats'          => 'Igpakita an mga tinago nga mga kategorya',

'underline-always' => 'Pirme',
'underline-never'  => 'Diri',

# Font style option in Special:Preferences
'editfont-sansserif' => 'Sans-serif nga agi',
'editfont-serif'     => 'Serif nga agi',

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
'hidden-category-category'       => 'Tinago nga mga kaarangay',
'category-subcat-count'          => '{{PLURAL:$2|Ini nga kaarangay mayda amo la nga nasunod nga ubos-nga-kaarangay.|Ini nga kaarangay mayda han mga nasunod nga {{PLURAL:$1|ubos-nga-kaarangay|$1 nga mga ubos-nga-kaarangay}}, tikang hin $2 nga kabug-osan.}}',
'category-subcat-count-limited'  => 'Ini nga kaarangay mayda han nasunod nga {{PLURAL:$1|ubos-nga-kaarangay|$1 nga mga ubos-nga-kaarangay}}.',
'category-article-count'         => '{{PLURAL:$2|Ini nga kaarangay mayda han amo la nga nasunod nga pakli.|An mga nasunod nga {{PLURAL:$1|ka pakli|$1 ka mga pakli}} aada hini nga kaarangay, tikang hin $2 nga kabug-osan.}}',
'category-article-count-limited' => 'An mga nasunod nga {{PLURAL:$1|ka pakli|$1 ka mga pakli aada}} han yana nga kaarangay.',
'category-file-count'            => '{{PLURAL:$2|Ini nga kaarangay mayda hin amo la nga fayl.|An mga nasunod nga {{PLURAL:$1|ka fayl|$1 ka mga fayl aada}} han hini nga kaarangay, tikang hin $2 nga kabug-osan.}}',
'category-file-count-limited'    => 'An mga nasunod nga {{PLURAL:$1|ka fayl|$1 ka mga faly aada}} han yana nga kaarangay.',
'listingcontinuesabbrev'         => 'pdyn.',

'linkprefix'        => '/^(.*?)([a-zA-Z\\x80-\\xff]+)$/sD',
'mainpagetext'      => "'''Malinamposon an pag-instalar han MediaWiki.'''",
'mainpagedocfooter' => "Kitaa an [http://meta.wikimedia.org/wiki/Help:Contents User's Guide] para hin impormasyon ha paggamit han wiki nga softweyr.

== Ha pagtikang==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'about'         => 'Mahitungod han',
'article'       => 'Pakli hin sulod',
'newwindow'     => '(nabuklad hin bag-o nga tamboan o bintana)',
'cancel'        => 'Igkanselar',
'moredotdotdot' => 'Damo pa nga…',
'mypage'        => 'Akon pakli',
'mytalk'        => 'Akon paghingay',
'anontalk'      => 'Paghingay para hini nga IP',
'navigation'    => 'Paglayag',
'and'           => '&#32;ngan',

# Cologne Blue skin
'qbfind'         => 'Bilnga',
'qbbrowse'       => 'Igdalikyat',
'qbedit'         => 'Igliwat',
'qbpageoptions'  => 'Ini nga pakli',
'qbpageinfo'     => 'Kontexto',
'qbmyoptions'    => 'Akon mga pakli',
'qbspecialpages' => 'Mga ispisyal nga pakli',
'faq'            => 'AGG',
'faqpage'        => 'Project:AGG',

# Vector skin
'vector-action-addsection' => 'Igdugang hin himangrawon',
'vector-action-delete'     => 'Para-a',
'vector-action-move'       => 'Balhina',
'vector-action-protect'    => 'Panalipda',
'vector-action-undelete'   => 'Igbalik an ginpara',
'vector-action-unprotect'  => 'Kuhaa an panalipod',
'vector-view-create'       => 'Himo-a',
'vector-view-edit'         => 'Igliwat',
'vector-view-history'      => 'Kitaa an kaagi',
'vector-view-view'         => 'Basaha',
'vector-view-viewsource'   => 'Kitaa an ginkuhaan',
'actions'                  => 'Mga buhat',
'namespaces'               => "Mga ngaran-lat'ang",
'variants'                 => 'Mga pagkadirudilain',

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
'updatedmarker'     => 'ginbag-ohan tikang han akon urhi nga pagbisita',
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
'lastmodifiedat'    => 'Ini nga pakli kataposan ginliwat dida han $1, han $2.',
'viewcount'         => 'Ini nga pakli ginkanhi hin {{PLURAL:$1|makausa|$1 ka beses}}.',
'protectedpage'     => 'Ginpanalipdan nga pakli',
'jumpto'            => 'Laktaw ngadto ha:',
'jumptonavigation'  => 'paglayag',
'jumptosearch'      => 'bilnga',
'view-pool-error'   => 'Pasayloa, an mga server diri na kaya yana nga takna.
Damo nga nagamit in gusto sinmulod hini nga pakli.
Alayon paghulat makadali san-o ka inmutro pagsulod hin nga pakli utro.

$1',
'pool-queuefull'    => 'Puno an katitirok nga pila',
'pool-errorunknown' => 'Waray kasabti nga kasaypanan',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Mahitungod han {{SITENAME}}',
'aboutpage'            => 'Project:Mahitungod han',
'copyright'            => 'In sulod mabiblingan ha ilarom han $1.',
'copyrightpage'        => '{{ns:project}}:Mga kopirayt',
'currentevents'        => 'Mga panhitabo',
'currentevents-url'    => 'Project:Mga panhitabo',
'disclaimers'          => 'Mga Disclaimer',
'disclaimerpage'       => 'Project:Kasahiran nga disclaimer',
'edithelp'             => 'Bulig hin pagliwat',
'edithelppage'         => 'Help:Pagliwat',
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
'feedlinks'               => 'Igsulod:',
'site-rss-feed'           => '$1 RSS nga feed',
'site-atom-feed'          => '$1 Atom nga feed',
'page-rss-feed'           => '"$1" RSS nga feed',
'page-atom-feed'          => '"$1" Atom nga feed',
'red-link-title'          => '$1 (waray dida ini nga pakli)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Pakli',
'nstab-user'      => 'Pakli hin nágámit',
'nstab-media'     => 'Pakli hin medya',
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
'nospecialpagetext' => '<strong>Naghangyo ka hin diri-puyde nga ispisyal nga pakli.</strong>

In lista o talaan hin puyde nga mga ispisyal nga pakli mabibilngan ha [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Sayop',
'databaseerror'        => 'Sayop hin database',
'dberrortext'          => 'Mayda nahinabo nga sayop hin syntax ha database nga kwery.
Bangin ini nagpapakita hin bug dida han softweyr.
An kataposan nga ginsari nga database nga kweri amo in:
<blockquote><tt>$1</tt></blockquote>
tikang ha sakob han funsyon nga "<tt>$2</tt>".
Nagbalik an database hin sayop nga "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Mayda nahitabo nga sayop hin syntax ha database nga kwery.
An kataposan nga ginsari nga kweri han database amo an:
"$1"
tikang ha sakob han funsyon nga "$2".
Nagbalik hin sayop an database nga "$3: $4"',
'laggedslavemode'      => 'Bantay: It pakli bangin waray mga kabag-ohan nga bag-o.',
'readonly'             => 'Gintrankahan an database',
'enterlockreason'      => 'Pagbutang hin rason para han pagtrangka. upod hin banabana kon san-o kukuha-on an pagtrangka',
'internalerror'        => 'Sayop ha sulod',
'internalerror_info'   => 'Sayop ha sulod: $1',
'badarticleerror'      => 'Ini nga pagbuhat diri mahihimo dinhi nga pakli',
'cannotdelete'         => 'An pakli o an fayl nga "$1" diri napapara.
Bangin na ini ginpara hin iba.',
'badtitle'             => 'Maraot nga titulo',
'badtitletext'         => 'An ginhangyo nga pakli diri puyde, waray sulod, o sayop nga nasumpay nga inter-pinunongan o inter-wiki nga titulo.
Bangin mayda usa o damo nga mga agi nga diri puyde magamit ha mga titulo.',
'perfcached'           => 'An nasunod nga data gin-cache ngan bangin diri amo an yana.',
'perfcachedts'         => 'An nasunod nga data gin-cache, ngan kataposan ginbag-o dida han $1.',
'querypage-no-updates' => 'An mga kabag-ohan para hini nga pakli ha yana diri mahihimo.
An data dini diri mahihimo nga bag-o.',
'viewsource'           => 'Kitaa an ginkuhaan',
'viewsourcefor'        => 'para han $1',
'viewsourcetext'       => 'Puydi ka kinmita ngan kinmopya han gintikangan han pakli:',
'ns-specialprotected'  => 'Diri maliliwat an mga ispisyal nga pakli.',
'titleprotected'       => 'Ini nga titulo pinasalipod ha paghimo ni [[User:$1|$1]].
An katadungan nga ginhatag amo in "\'\'$2\'\'".',

# Virus scanner
'virus-unknownscanner' => 'diri-nasasabtan nga antivirus:',

# Login and logout pages
'welcomecreation'         => '== ¡Uswag ngan Dayon, $1! ==
Ginhimo an imo akawnt.
Ayaw paghingalimot hin pagbalyo han imo [[Special:Preferences|{{SITENAME}} mga ginpipili]].',
'yourname'                => 'Agnay hit nagamit:',
'yourpassword'            => 'Tigaman-pagsulod:',
'login'                   => 'Sakob',
'nav-login-createaccount' => 'Sakob / paghimo hin bag-o nga akawnt',
'userlogin'               => 'Sakob/Pagrehistro',
'userloginnocreate'       => 'Sakob',
'logout'                  => 'Gawas',
'userlogout'              => 'Gawas',
'notloggedin'             => 'Diri sakob',
'nologin'                 => 'Waray ka akawnt? $1.',
'nologinlink'             => 'Paghimo hin akawnt',
'createaccount'           => 'Himo-a an akwant',
'gotaccount'              => '¿Mayda kana akawnt? $1.',
'gotaccountlink'          => 'Sakob',
'createaccountmail'       => 'Ha e-mail',
'createaccountreason'     => 'Rason:',
'badretype'               => 'Diri naangay an mga tigaman-pagsulod nga im ginbutang',
'userexists'              => 'An agnay-hit-nagamit nga im ginbutang in gingamit na.
Alayon pagpili hin lain nga ngaran.',
'loginerror'              => 'Sayop hin pagsakob',
'loginsuccesstitle'       => 'An pagsulod malinamposon',
'nosuchusershort'         => 'Waray nagamit it may ngaran nga "<nowiki>$1</nowiki>".
Kitaa kun amo it im pagbaybay.',
'nouserspecified'         => 'Dapat nim magbutang hin agnay-hit-nagamit.',
'wrongpassword'           => 'Sayop nga tigaman-pagsulod an nahibutang.
Alayon pagutro pagbutang.',
'wrongpasswordempty'      => 'An tigaman-pagsulod nga ginbutang in waray sulod.
Alayon pagutro pagbutang.',
'passwordtooshort'        => 'An tigaman-pagsulod dapat diri maubos hit {{PLURAL:$1|1 nga agi|$1 nga agi}}.',
'mailmypassword'          => 'Ig-e-mail an bag-o nga tigaman-pagsulod',
'passwordremindertitle'   => 'Bag-o nga diri-pirmihan nga tigaman-pagsulod para han {{SITENAME}}',
'noemail'                 => 'Waray e-mail nga adres nga ginrekord para han nágámit "$1".',
'noemailcreate'           => 'Kinahanglan nim maghatag hin may hinungdan nga e-mail address',
'accountcreated'          => 'Nahimo an akawant',
'loginlanguagelabel'      => 'Yinaknan: $1',

# JavaScript password checks
'password-strength'            => 'Banabana nga kabaskog han tigaman-pagsulod: $1',
'password-strength-bad'        => 'MARAOT',
'password-strength-mediocre'   => 'maluya',
'password-strength-acceptable' => 'kakara-karawat',
'password-strength-good'       => 'maupay',
'password-retype'              => 'Utroha pagbutang an tigaman-pagsulod dinhi',
'password-retype-mismatch'     => 'An mga tigaman-pagsulod in diri naangay',

# Password reset dialog
'resetpass'           => 'Igliwat an tigaman-pagsulod',
'resetpass_header'    => 'Igliwan an akawnt nga tigaman-pagsulod',
'oldpassword'         => 'Daan nga tigaman-pagsulod:',
'newpassword'         => 'Bag-o nga tigaman-pagsulod:',
'retypenew'           => 'Utroha pagbutang an bag-o nga tigaman-pagsulod:',
'resetpass_forbidden' => 'Diri mababalyoan an mga tigaman-pagsulod',

# Edit page toolbar
'bold_sample' => 'dakmola an agi',
'bold_tip'    => 'Dakmola an agi',

# Edit pages
'summary'               => 'Dalikyat nga sumat hit pagliwat (Summary):',
'minoredit'             => 'Gutiay ini nga pagliwat',
'watchthis'             => 'Bantayi ini nga pakli',
'savearticle'           => 'Igtipig an pakli',
'preview'               => 'Pahiuna nga pagawas',
'showpreview'           => 'Pakit-a an pahiuna nga pagawas',
'showlivepreview'       => 'Buhi nga pahiuna nga pagawas',
'showdiff'              => 'Igpakita an mga ginliwat',
'summary-preview'       => 'Pahiuna nga pagawas han dalikyat nga pulong:',
'subject-preview'       => 'Pahiuna nga pagawas hit himangrawon:',
'blockednoreason'       => 'waray katadungan nga ginhatag',
'blockedoriginalsource' => "An tinikangan han '''$1''' amo in ginpapakita ha ubos:",
'accmailtitle'          => 'Ginpadara na an tigaman-pagsulod.',
'newarticle'            => '(Bag-o)',
'editing'               => 'Ginliliwat an $1',
'editingsection'        => 'Ginliliwat an $1 (bahin)',
'editingcomment'        => 'Ginliliwat an $1 (bag-o nga bahin)',
'storedversion'         => 'Nakahipos nga pagbag-o',
'yourdiff'              => 'Mga kaibhan',
'copyrightwarning'      => "Iginpapasabot nga an ngatanan nga imo gin-amot ha {{SITENAME}} iginhatag mo ha ilarom han $2 (kitaa an $1 para han mga detalye).  Kun diri mo igkakalipay nga an imo ginsurat waray kalooy nga liliwaton ngan igpapakalat hit bisan hin-o nga it may gusto, alayon ayaw hiton igsumitir dinhi. <br />
Nasaad ka liwat nga imo ini kalugaringon nga ginsurat, o ginkopya nimo ini tikang ha panimongto nga dominyo o kapareho nga waray-sabit nga kuruhaon.
'''Ayaw igsumitir an mga buhat nga may ''copyright'' hin waray sarit!'''",
'nocreate-loggedin'     => 'Diri ka gintutugotan paghimo hin mga bag-o nga pakli.',
'permissionserrorstext' => 'Diri ka gintutugotan pagbuhat hito, mahitungod han mga nasunod nga {{PLURAL:$1|katadungan|mga katadungan}}:',
'edit-conflict'         => 'Diri pagkakauroyon han pagliwat.',
'edit-no-change'        => 'Ginpabay-an an im pagliwat, mahitungod nga waray pagbalyo nga nabuhat ha nakasurat.',
'edit-already-exists'   => 'Diri nakakahimo hin bag-o nga pakli.
Aada na ito.',

# History pages
'viewpagelogs'         => 'Kitaa an mga log para hini nga pakli',
'currentrev'           => 'Giuurhii nga pagliwat',
'previousrevision'     => '← Durudaan nga pagliwat',
'nextrevision'         => 'Burubag-o nga pagliwat →',
'currentrevisionlink'  => 'Giuurhii nga pagliwat',
'cur'                  => 'yana',
'next'                 => 'sunod',
'last'                 => 'kataposan',
'page_first'           => 'syahan',
'page_last'            => 'kataposan',
'history-show-deleted' => 'Ginpara la',
'histfirst'            => 'Giuunhani',
'histlast'             => 'Giuurhii',

# Revision deletion
'rev-deleted-user'            => '(gintanggal an agnay-hit-nagamit)',
'rev-delundel'                => 'igpakita/igtago',
'revdelete-show-file-confirm' => 'Sigurado ka nga gusto mo makita an ginpara nga pagliwat han file "<nowiki>$1</nowiki>" tikang $2 ha $3?',
'revdelete-show-file-submit'  => 'Oo',
'revdelete-radio-same'        => '(ayaw balyu-e)',
'revdelete-radio-set'         => 'Oo',
'revdelete-radio-unset'       => 'Ayaw',
'revdelete-uname'             => 'Agnay-hit-nagamit',
'revdelete-otherreason'       => 'Lain/dugang nga katadungan:',

# Revision move
'revmove-reasonfield' => 'Katadungan:',

# History merging
'mergehistory'                  => 'Igtampo an mga kasaysayan han pakli',
'mergehistory-from'             => 'Ginkuhaan nga pakli:',
'mergehistory-into'             => 'Kakadtoan nga pakli:',
'mergehistory-same-destination' => 'An gintikangan ngan kakadtoan nga mga pakli in diri puydi magkaparo',
'mergehistory-reason'           => 'Katadungan:',

# Merge log
'revertmerge' => 'Igbulag an gintampo',

# Search results
'searchresults'             => 'Mga nabilingan han pagbiling',
'searchresults-title'       => 'Mga nabilngan han pagbiling para han "$1"',
'prevn'                     => 'naha-una nga {{PLURAL:$1|$1}}',
'nextn'                     => 'sunod nga {{PLURAL:$1|$1}}',
'viewprevnext'              => 'Kitaa an ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'         => 'Mga pagpipilian han pamiling',
'searchmenu-exists'         => "'''May-ada pakli nga nakangaran hin \"[[:\$1]]\" hini nga wiki.'''",
'searchhelp-url'            => 'Help:Sulod',
'searchprofile-everything'  => 'Ngatanan',
'search-result-size'        => '$1 ({{PLURAL:$2|1 nga pulong|$2 nga mga pulong}})',
'search-suggest'            => 'Buot sidngon mo ba: $1',
'search-interwiki-caption'  => 'Mga bugto nga proyekto',
'search-interwiki-default'  => '$1 nga resulta:',
'search-mwsuggest-enabled'  => 'upod hin mga suhestyon',
'search-mwsuggest-disabled' => 'waray mga suhestyon',
'searchall'                 => 'ngatanan',
'powersearch'               => 'Abansado nga pagbiling',
'powersearch-legend'        => 'Abansado nga pagbiling',
'powersearch-field'         => 'Bilnga an',
'powersearch-toggleall'     => 'Ngatanan',
'powersearch-togglenone'    => 'Waray',
'search-external'           => 'Gawas nga pamiling',

# Quickbar
'qbsettings-none'          => 'Waray',
'qbsettings-fixedleft'     => 'Ginayad an wala',
'qbsettings-fixedright'    => 'Gin-ayad an to-o',
'qbsettings-floatingleft'  => 'Nalutaw pawala',
'qbsettings-floatingright' => 'Nalutaw pato-o',

# Preferences page
'preferences'               => 'Mga karuyag',
'mypreferences'             => 'Akon mga karuyag',
'prefs-edits'               => 'Ihap han mga pagliwat:',
'changepassword'            => 'Igliwan an tigaman-pagsulod',
'prefs-skin'                => 'Panit',
'skin-preview'              => 'Pahiuna nga pagawas',
'datedefault'               => 'Waray pinaurog nga karuyag',
'prefs-datetime'            => 'Pitsa ngan oras',
'prefs-personal'            => 'Pangilal-an han nagamit',
'prefs-rc'                  => 'Kalalabay la nga mga pagbabag-o',
'prefs-watchlist-days'      => 'Mga adlaw nga makikita ha barantayan:',
'prefs-resetpass'           => 'Igliwan an tigaman-pagsulod',
'rows'                      => 'Mga rumbay pahigda:',
'columns'                   => 'Mga rumbay patindog:',
'searchresultshead'         => 'Bilnga',
'resultsperpage'            => 'Mga igo kada pakli:',
'contextlines'              => 'Mga bagis kada igo:',
'savedprefs'                => 'Gintipig an im karuyag.',
'timezonelegend'            => 'Zona hin oras',
'localtime'                 => 'Oras nga lokal',
'timezoneregion-africa'     => 'Aprika',
'timezoneregion-america'    => 'Amerika',
'timezoneregion-arctic'     => 'Artika',
'timezoneregion-asia'       => 'Asya',
'timezoneregion-atlantic'   => 'Kalawdan Atlantika',
'timezoneregion-australia'  => 'Australya',
'timezoneregion-europe'     => 'Europa',
'timezoneregion-indian'     => 'Kalawdan Indyana',
'timezoneregion-pacific'    => 'Kalawdan Pasipiko',
'prefs-searchoptions'       => 'Mga pagpipilian han pamiling',
'youremail'                 => 'E-mail:',
'username'                  => 'Agnay-hit-nagamit:',
'yourrealname'              => 'Tinuod nga ngaran:',
'yourlanguage'              => 'Yinaknan:',
'yournick'                  => 'Bag-o nga pirma:',
'badsiglength'              => 'Hilaba hin duro it im pirma.
Dapat diri malabaw ha $1 {{PLURAL:$1|agi|mga agi}} nga kahilaba.',
'gender-male'               => 'Lalaki',
'gender-female'             => 'Babaye',
'prefs-help-email-required' => 'Kinahanglanon it e-mail address.',
'prefs-info'                => 'Panguna nga pananabotan',
'prefs-signature'           => 'Pirma',

# User rights
'userrights-reason' => 'Katadungan:',

# Groups
'group'       => 'Hugpo:',
'group-user'  => 'Mga nagamit',
'group-bot'   => 'Mga bot',
'group-sysop' => 'Mga nagdudumara',
'group-all'   => '(ngatanan)',

# Rights
'right-read'       => 'Igbasa an mga pakli',
'right-edit'       => 'Igliwat an mga pakli',
'right-createpage' => 'Paghimo hin mga pakli (nga diri an mga hiruhimangraw nga mga pakli)',
'right-createtalk' => 'Paghimo hin hiruhimangraw nga mga pakli',
'right-minoredit'  => 'Igmarka an mga ginliwat komo gutiay la',
'right-move'       => 'Igbalhin an mga pakli',
'right-delete'     => 'Igpara an mga pakli',

# User rights log
'rightsnone' => '(waray)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'       => 'basaha ini nga pakli',
'action-edit'       => 'liwata ini nga pakli',
'action-createpage' => 'pahimo hin mga pakli',
'action-move'       => 'balhina ini nga pakli',
'action-delete'     => 'paraa ini nga pakli',

# Recent changes
'recentchanges'   => 'Mga kabag-ohan',
'rclistfrom'      => 'Pakit-a an mga ginbag-ohan tikang han $1',
'rcshowhideanons' => '$1 waray nagpakilala nga mga nagamit',
'rcshowhidemine'  => '$1 akon mga ginliwat',
'hist'            => 'kaagi',
'hide'            => 'Tago-a',
'show'            => 'Igpakita',
'minoreditletter' => 'g',
'newpageletter'   => 'B',

# Recent changes linked
'recentchangeslinked'         => 'Mga may kalabotan nga binag-o',
'recentchangeslinked-feed'    => 'Mga may kalabotan nga binag-o',
'recentchangeslinked-toolbox' => 'Mga may kalabotan nga binag-o',
'recentchangeslinked-page'    => 'Ngaran han pakli:',

# Upload
'upload'            => 'Pagkarga hin file',
'uploadbtn'         => 'Igkarga an file',
'filedesc'          => 'Dalikyat nga pulong',
'fileuploadsummary' => 'Dalikyat nga pulong:',
'filesource'        => 'Tinikangan:',

# Special:ListFiles
'listfiles_date' => 'Pitsa',
'listfiles_name' => 'Ngaran',
'listfiles_user' => 'Nagamit',
'listfiles_size' => 'Kadako',

# File description page
'file-anchor-link'        => 'Fayl',
'filehist-deleteall'      => 'Paraa ngatanan',
'filehist-deleteone'      => 'paraa',
'filehist-datetime'       => 'Pitsa/Oras',
'filehist-user'           => 'Nagamit',
'imagelinks'              => 'Mga sumpay hin fayl',
'linkstoimage'            => 'An nasunod nga {{PLURAL:$1|pakli nasumpay|$1 mga pakli nasumpay}} hini nga fayl:',
'nolinkstoimage'          => 'Waray mga pakli nga nasumpay hini nga fayl.',
'sharedupload'            => 'Ini nga fayl tikang han $1 ngan puyde magamit ha iba nga mga proyekto.',
'sharedupload-desc-there' => 'Ini nga fayl tikang han $1 ngan puyde magamit ha iba nga mga proyekto.
Alayon pagkita han [$2 nga pakli hin pagpahayag mahitungod hini nga fayl] para hin dugang nga kasayuran.',

# File deletion
'filedelete-comment' => 'Katadungan:',
'filedelete-submit'  => 'Paraa',

# Unused templates
'unusedtemplateswlh' => 'iba nga mga sumpay',

# Random page
'randompage' => 'Bisan ano nga pakli',

# Statistics
'statistics'                   => 'Mga estadistika',
'statistics-header-pages'      => 'Mga estadistika han pakli',
'statistics-header-edits'      => 'Mga estadistika han pagliwat',
'statistics-header-views'      => 'Mga estadistika han nakita',
'statistics-header-users'      => 'Mga estadistika hit nagamit',
'statistics-header-hooks'      => 'Lain nga mga estadistika',
'statistics-articles'          => 'Unod nga mga pakli',
'statistics-pages'             => 'Mga pakli',
'statistics-pages-desc'        => 'Ngatanan nga mga pakli ha sulod hini nga wiki, lakip an hiruhimangraw nga mga pakli, mga redirect, ngan iba pa',
'statistics-files'             => 'Ginkarga nga mga file',
'statistics-edits'             => 'Mga pagliwat hit pakli tikang gintukod hini nga {{SITENAME}}',
'statistics-edits-average'     => 'Average nga mga pagliwat kada pakli',
'statistics-views-total'       => 'Ngatanan nga mga panginano',
'statistics-views-peredit'     => 'Mga panginano kada pagliwat',
'statistics-users-active'      => 'Nagios nga mga nagamit',
'statistics-users-active-desc' => 'Mga nagamit nga may-ada iginbuhat ha urhi nga {{PLURAL:$1|ka adlaw|$1 ka mga adlaw}}',
'statistics-mostpopular'       => 'Gidadamoi nga ginpanginanohan nga mga pakli',

'disambiguations'     => 'Mga pansayod nga mga pakli',
'disambiguationspage' => 'Template:pansayod',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|nga byte|nga mga byte}}',
'ncategories'       => '$1 {{PLURAL:$1|nga kaarangay|nga mga kaarangay}}',
'nlinks'            => '$1 {{PLURAL:$1|nga sumpay|nga mga sumpay}}',
'nmembers'          => '$1 {{PLURAL:$1|nga api|nga mga api}}',
'nrevisions'        => '$1 {{PLURAL:$1|nga pagliwat|nga mga pagliwat}}',
'nviews'            => '$1 {{PLURAL:$1|nga pangita|nga mga pangita}}',
'specialpage-empty' => 'Waray mga resulta para hini nga report.',
'lonelypages'       => 'Mga nahibulag nga mga pakli',
'unusedcategories'  => 'Waray kagamit nga mga kaarangay',
'unusedimages'      => 'Waray kagamit nga mga fayl',
'shortpages'        => 'Haglipot nga mga pakli',
'longpages'         => 'Haglaba nga mga pakli',
'listusers'         => 'Lista han mga nagamit',
'newpages'          => 'Bag-o nga mga pakli',
'newpages-username' => 'Agnay-hit-nagamit:',
'move'              => 'Balhina',
'movethispage'      => 'Balhina ini nga pakli',

# Book sources
'booksources-go' => 'Kadto-a',

# Special:Log
'specialloguserlabel' => 'Nagamit:',

# Special:AllPages
'allpages'       => 'Ngatanan nga mga pakli',
'alphaindexline' => '$1 tubtob ha $2',
'nextpage'       => 'Sunod nga pakli ($1)',
'prevpage'       => 'Nahiuna nga pakli ($1)',
'allpagesfrom'   => 'Igpakita an mga pakli nga nagtitikang ha:',
'allpagesto'     => 'Igpakita an mga pakli nga nahuhuman ha:',
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
'deletedcontributions'       => 'Mga ginpara nga mga ámot hin nágámit',
'deletedcontributions-title' => 'Ginpara nga mga amot han nagamit',

# Special:ListUsers
'listusersfrom' => 'Igpakita an mga nagamit nga nagtitikang ha:',

# Special:ActiveUsers
'activeusers'          => 'Lista han nagios nga mga nagamit',
'activeusers-hidebots' => 'Igtago an mga bot',

# Special:Log/newusers
'newuserlog-byemail' => 'Ginpadangat an tigaman-pagsulod pinaagi han e-mail',

# Special:ListGroupRights
'listgrouprights-group'       => 'Hugpo',
'listgrouprights-rights'      => 'Mga katungod',
'listgrouprights-helppage'    => 'Help:Mga katungod han hugpo',
'listgrouprights-addgroup'    => 'Dugnga {{PLURAL:$2|hugpo|mga hugpo}}: $1',
'listgrouprights-removegroup' => 'Tanggala {{PLURAL:$2|hugpo|mga hugpo}}: $1',

# Watchlist
'watchlist'     => 'Akon barantayan',
'mywatchlist'   => 'Akon angay timan-an',
'watch'         => 'Bantayi',
'watchthispage' => 'Bantayi ini nga pakli',

# Delete
'deletepage'     => 'Igpara an pakli',
'exblank'        => 'waray sulod an pakli',
'delete-confirm' => 'Igpara "$1"',
'delete-legend'  => 'Igpara',
'deletedtext'    => 'Ginpara an "<nowiki>$1</nowiki>".
Kitaa an $2 para hin talaan han mga gibag-ohi nga mga ginpamara.',

# Protect
'protectcomment'         => 'Katadongan:',
'protect-default'        => 'Togota an ngatanan nga mga nagamit',
'protect-otherreason'    => 'Lain/dugang nga katadongan:',
'protect-otherreason-op' => 'Lain nga katadongan',
'restriction-type'       => 'Pagtugot:',

# Contributions
'mycontris' => 'Akon mga ámot',

'sp-contributions-talk' => 'hiruhimangraw',

# What links here
'whatlinkshere'           => 'Mga nasumpay dinhi',
'whatlinkshere-title'     => 'Mga pakli nga nasumpay ngadto ha "$1"',
'whatlinkshere-hidelinks' => '$1 an mga sumpay',

# Block/unblock
'ipblocklist-submit' => 'Bilnga',
'contribslink'       => 'mga ámot',
'proxyblocksuccess'  => 'Human na.',

# Move page
'movearticle'          => 'Balhina an pakli:',
'moveuserpage-warning' => "'''Bantayi:''' Tibalhin ka hin pakli hin nágámit. Alayon pagtigaman nga an pakli là an mababalhin ngan an nágámit ''diri'' mababalyoan hin ngaran.",

# Namespace 8 related
'allmessagesname'        => 'Ngaran',
'allmessages-filter-all' => 'Ngatanan',

# Thumbnails
'thumbnail-more' => 'Padako-a',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'An imo pakli hin nágámit',
'tooltip-pt-mytalk'              => 'An imo pakli hin hiruhimangraw',
'tooltip-pt-preferences'         => 'An imo mga karuyag',
'tooltip-pt-watchlist'           => 'An talaan hin mga pakli nga imo ginsisinubay para hin mga kabag-ohan',
'tooltip-pt-mycontris'           => 'Talaan han imo mga ámot',
'tooltip-pt-logout'              => 'gawas',
'tooltip-ca-talk'                => 'Hiruhimangraw mahiunong han sulod nga pakli',
'tooltip-ca-addsection'          => 'Pagtikang hin bag-o nga bahin',
'tooltip-ca-history'             => 'Mga kahadto nga mga pagliwat hini nga pakli',
'tooltip-ca-delete'              => 'Para-a ini nga pakli',
'tooltip-ca-move'                => 'Balhina ini nga pakli',
'tooltip-ca-watch'               => 'Dugnga ini nga pakli ngadto han imo talaan hin ginbibinantayan',
'tooltip-search'                 => 'Bilnga ha {{SITENAME}}',
'tooltip-search-go'              => 'Kadto hin pakli nga mayda hin gud nga exakto ngaran kon aadà',
'tooltip-search-fulltext'        => 'Bilnga ha mga pakli para hini nga texto',
'tooltip-n-mainpage'             => 'Bisitaha an syahan nga pakli',
'tooltip-n-mainpage-description' => 'Bisitaha an syahan nga pakli',
'tooltip-n-portal'               => 'Mahiunong han proyekto, ano an imo mahihimo, diin makabiling hin mga butang',
'tooltip-n-recentchanges'        => 'An talaan hin mga urhe nga mga kabag-ohan han wiki',
'tooltip-n-randompage'           => 'Pagkaraga hin bisan ano nga pakli',
'tooltip-n-help'                 => 'An lugar hin pagbiling',
'tooltip-t-whatlinkshere'        => 'Talaan han ngatanan nga wiki nga mga pakli nga nasumpay dinhe',
'tooltip-t-recentchangeslinked'  => 'Mga bag-o nga kabag-ohan ha mga pakli nga nahasumpay tikang hini nga pakli',
'tooltip-feed-rss'               => 'RSS nga pangarga para hini nga pakli',
'tooltip-feed-atom'              => 'Atom nga pangarga para hini nga pakli',
'tooltip-t-contributions'        => 'Kitaa an talaan hin mga amot hini nga nágámit',
'tooltip-t-emailuser'            => 'Padad-i hin e-mail ini nga nágámit',
'tooltip-t-upload'               => 'Pagkarga hin mga fayl',
'tooltip-t-specialpages'         => 'Talaan hin mga ispisyal nga pakli',
'tooltip-t-print'                => 'Maipapatik nga bersyon hini nga pakli',
'tooltip-t-permalink'            => 'Sumpay nga unob ha hini nga pagliwat han pakli',
'tooltip-ca-nstab-main'          => 'Kitaa an sulod nga pakli',
'tooltip-ca-nstab-user'          => 'Kitaa an pakli han nágámit',
'tooltip-ca-nstab-media'         => 'Kitaa an pakli hin media',
'tooltip-ca-nstab-special'       => 'Ispisyal nga pakli ini, diri ka makaliwat han pakli ngahaw',
'tooltip-ca-nstab-project'       => 'Kitaa an pakli han proyekto',
'tooltip-ca-nstab-image'         => 'Kitaa an pakli han fayl',
'tooltip-ca-nstab-mediawiki'     => 'Kitaa an mensahe han sistema',
'tooltip-ca-nstab-template'      => 'Kitaa an plantilya',
'tooltip-ca-nstab-help'          => 'Kitaa an pakli hin bulig',
'tooltip-ca-nstab-category'      => 'Kitaa an pakli hin kaarangay',
'tooltip-minoredit'              => 'Tigamni ini nga gamay nga pagliwat',
'tooltip-save'                   => 'Ig-seyb an imo mga pagbabag-o',

# Media information
'file-info-size' => '$1 × $2 nga pixel, kadako han fayl: $3, MIME nga tipo: $4',
'show-big-image' => 'Bug-os nga resolusyon',

# Special:NewFiles
'ilsubmit' => 'Bilnga',

# Metadata
'metadata' => 'Metadata',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ngatanan',
'imagelistall'     => 'ngatanan',
'watchlistall2'    => 'ngatanan',
'namespacesall'    => 'ngatanan',
'monthsall'        => 'ngatanan',
'limitall'         => 'ngatanan',

# Delete conflict
'recreate' => 'Himo-a utro',

# Multipage image navigation
'imgmultipageprev' => '← naha-una nga pakli',
'imgmultipagenext' => 'sunod nga pakli →',
'imgmultigo'       => 'Pakadto!',
'imgmultigoto'     => 'Pakadto ha pakli $1',

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

# Special:BlankPage
'blankpage'              => 'Blanko nga pakli',
'intentionallyblankpage' => 'Ini nga pakli gintuyo pagpabilin nga blanko.',

# Database error messages
'dberr-header' => 'Ini nga wiki mayda problema',

# HTML forms
'htmlform-reset' => 'Igbalik an mga pinamalyuan',

);
