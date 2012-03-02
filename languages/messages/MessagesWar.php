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
 * @author Kaganer
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
'tog-rememberpassword'        => 'Hinumdomi an akon pan-sakob dinhi nga browser (para hin maximum nga $1 {{PLURAL:$1|nga adlaw|nga mga adlaw}})',
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
'tog-oldsig'                  => 'Aada nga pirma:',
'tog-fancysig'                => 'Tratuha it pirma komo uska wikitext (nga waray automatiko nga sumpay)',
'tog-showjumplinks'           => 'Enable "jump to" accessibility links',
'tog-uselivepreview'          => 'Gamita an buhi nga pahiuna nga pagawas (nagkikinahanglan hin JavaScript) (eksperimental)',
'tog-forceeditsummary'        => 'Pasabti ako kun waray ko ginsurat ha dalikyat-nga-tigaman han pagliwat (edit summary)',
'tog-watchlisthideown'        => 'Tago-a an akon mga ginliwat tikang han angay timan-an',
'tog-watchlisthidebots'       => 'Tago-a an ginliwat hin bot tikang han angay timan-an',
'tog-watchlisthideminor'      => 'Tago-a an mga gagmay nga pagliwat tikang han angay timan-an',
'tog-watchlisthideliu'        => 'Igatag an mga ginliwat han naka log-in nga mga gumaramit tikang ha gintitiman-an',
'tog-watchlisthideanons'      => 'Igtago an mga ginliwat han mga waray nagpakilala nga nagamit tikang ha gintitiman-an',
'tog-watchlisthidepatrolled'  => 'Igatag an mga pinatrolya nga mga pagliwat tikang ha angay timan-an',
'tog-ccmeonemails'            => 'Padad-i ak hin mga kopya hin mga email nga akon ginpapadara ha iba nga mga gumaramit',
'tog-diffonly'                => 'Ayaw igpakita an sulod han pakli ha ilarom han pagkakaiba',
'tog-showhiddencats'          => 'Igpakita an mga tinago nga mga kaarangay',
'tog-norollbackdiff'          => 'Iglat-ang an kaiban kahuman himoa an libot-pabalik',

'underline-always'  => 'Pirme',
'underline-never'   => 'Diri',
'underline-default' => 'An aada-nga-daan nga panngaykayan',

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
'index-category'                 => 'Mga nakatudlokan nga pagkli',
'noindex-category'               => 'Mga diri nakatudlokan nga pagkli',

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
'cancel'        => 'Pasagdi',
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
'qbspecialpages' => 'Mga pinaurog nga pakli',
'faq'            => 'AGG',
'faqpage'        => 'Project:AGG',

# Vector skin
'vector-action-addsection' => 'Igdugang hin himangrawon',
'vector-action-delete'     => 'Para-a',
'vector-action-move'       => 'Balhina',
'vector-action-protect'    => 'Panalipda',
'vector-action-undelete'   => 'Igbalik an ginpara',
'vector-action-unprotect'  => 'Liwani an panalipod',
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
'unprotect'         => 'Liwani an panalipod',
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
'userpage'          => 'Kitaa in pakli hin gumaramit',
'projectpage'       => 'Kitaa in pakli hin proyekto',
'imagepage'         => 'Kitaa in pakli hin fayl',
'mediawikipage'     => 'Kitaa in pakli hin mensahe',
'templatepage'      => 'Kitaa in pakli hin plantilya',
'viewhelppage'      => 'Kitaa in pakli hin bulig',
'categorypage'      => 'Kitaa in pakli hin kaarangay',
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
'nstab-user'      => 'Pakli hin gumaramit',
'nstab-media'     => 'Pakli hin medya',
'nstab-special'   => 'Pinaurog nga pakli',
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
'nosuchspecialpage' => 'Waray sugad nga pinaurog nga pakli',
'nospecialpagetext' => '<strong>Naghangyo ka hin diri-puyde nga pinaurog nga pakli.</strong>

Listahan o talaan hin puyde nga mga pinaurog nga pakli in mabibilngan ha [[Special:SpecialPages|{{int:specialpages}}]].',

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
'laggedslavemode'      => 'Pahimatngon: It pakli bangin waray mga kabag-ohan nga bag-o.',
'readonly'             => 'Gintrankahan an database',
'enterlockreason'      => 'Pagbutang hin rason para han pagtrangka, upod hin banabana kon san-o kukuha-on an pagtrangka',
'missing-article'      => 'Ini nga database in waray nakaagi han teksto han pakli nga dapat mabilngan, nga ginngaranan nga "$1" $2.

Ini in agsob hinungdan han pagsunod han kadaan nga kaibhan o sumpay han kaagi ngadto ha pakli nga ginpara.

Kun diri ini an kaso, bangin ka nakabiling hin bug ha software.
Alayon la igsumat ini ha [[Special:ListUsers/sysop|administrator]], igsurat la an URL.',
'missingarticle-rev'   => '(pagbag-o#: $1)',
'missingarticle-diff'  => '(Kaibhan: $1, $2)',
'internalerror'        => 'Sayop ha sulod',
'internalerror_info'   => 'Sayop ha sulod: $1',
'fileappenderrorread'  => "Diri nababasahan an ''$1'' han pagdugang.",
'fileappenderror'      => "Diri nadudugngan an ''$1'' ha ''$2''.",
'filecopyerror'        => "Diri nakokopya an fayl nga ''$1'' ha ''$2''.",
'filerenameerror'      => "Diri nababalyuan an ngaran han fayl nga ''$1'' ha ''$2''.",
'filedeleteerror'      => "Diri napapara an fayl nga ''$1''.",
'fileexistserror'      => "Diri nasusuratan ha fayl ''$1'': An fayl aada na.",
'formerror'            => 'Sayop: Diri nasusumite an porma.',
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
'namespaceprotected'   => "Diri ka gintutugutan pagliwat han mga pakli ha ngaran-lat'ang nga '''$1'''.",
'ns-specialprotected'  => 'Diri maliliwat an mga ispisyal nga pakli.',
'titleprotected'       => 'Ini nga titulo pinasalipod ha paghimo ni [[User:$1|$1]].
An katadungan nga ginhatag amo in "\'\'$2\'\'".',

# Virus scanner
'virus-unknownscanner' => 'diri-nasasabtan nga antivirus:',

# Login and logout pages
'welcomecreation'         => '== ¡Uswag ngan Dayon, $1! ==
Ginhimo an imo akawnt.
Ayaw paghingalimot hin pagbalyo han imo [[Special:Preferences|{{SITENAME}} mga ginpipili]].',
'yourname'                => 'Agnay hit gumaramit:',
'yourpassword'            => 'Tigaman-pagsulod:',
'yourpasswordagain'       => 'Utroha pagbutang an tigaman-han-pagsakob:',
'remembermypassword'      => "Hinumdumi an akon pan-sakob dinhi nga panngaykay ''(browser)'' (para ha pinakamaiha $1 {{PLURAL:$1|ka adlaw|ka mga adlaw}})",
'yourdomainname'          => 'Imo dominyo:',
'login'                   => 'Sakob',
'nav-login-createaccount' => 'Sakob / paghimo hin bag-o nga akawnt',
'loginprompt'             => "Kinahanglan mo hin mga kuki (''cookie'') para makapag log-in ha {{SITENAME}}.",
'userlogin'               => 'Sakob/Pagrehistro',
'userloginnocreate'       => 'Sakob',
'logout'                  => 'Gawas',
'userlogout'              => 'Gawas',
'notloggedin'             => 'Diri sakob',
'nologin'                 => 'Waray ka akawnt? $1.',
'nologinlink'             => 'Paghimo hin akawnt',
'createaccount'           => 'Himo-a an akawnt',
'gotaccount'              => '¿Mayda kana akawnt? $1.',
'gotaccountlink'          => 'Sakob',
'createaccountmail'       => 'Ha e-mail',
'createaccountreason'     => 'Rason:',
'badretype'               => 'Diri naangay an mga tigaman-pagsulod nga im ginbutang',
'userexists'              => 'An agnay hiton gumaramit nga im ginbutang in gingamit na.
Alayon pagpili hin lain nga ngaran.',
'loginerror'              => 'Sayop hin pagsakob',
'createaccounterror'      => 'Diri makakahimo hin akawnt: $1',
'loginsuccesstitle'       => 'Malinamposon an pagsulod',
'nosuchusershort'         => 'Waray nagamit it may ngaran nga "<nowiki>$1</nowiki>".
Kitaa kun amo it im pagbaybay.',
'nouserspecified'         => 'Dapat nim magbutang hin agnay hit gumaramit.',
'login-userblocked'       => 'Ini nga gumaramit in ginpugngan.  Diri gintutugutan an pagsakob.',
'wrongpassword'           => 'Sayop nga tigaman-pagsulod an nahibutang.
Alayon pagutro pagbutang.',
'wrongpasswordempty'      => 'An tigaman-pagsulod nga ginbutang in waray sulod.
Alayon pagutro pagbutang.',
'passwordtooshort'        => 'An tigaman-pagsulod dapat diri maubos hit {{PLURAL:$1|1 nga agi|$1 nga agi}}.',
'password-name-match'     => 'An imo tigaman-pagsulod in kinahanglan iba ha imo agnay-hiton-gumaramit.',
'mailmypassword'          => 'Ig-e-mail an bag-o nga tigaman-pagsulod',
'passwordremindertitle'   => 'Bag-o nga diri-pirmihan nga tigaman-pagsulod para han {{SITENAME}}',
'noemail'                 => 'Waray e-mail nga adres nga ginrekord para han nágámit "$1".',
'noemailcreate'           => 'Kinahanglan nim maghatag hin may hinungdan nga e-mail address',
'mailerror'               => 'Sayop han pagpadangat hin surat: $1',
'emailauthenticated'      => 'Ginpamatuod an imo e-mail adres han $2 ha $3.',
'emailconfirmlink'        => 'Igkompirma an imo e-mail address',
'accountcreated'          => 'Nahimo an akawnt',
'accountcreatedtext'      => 'An akwant han gumaramit para kan $1 in ginhimo.',
'createaccount-title'     => 'Paghimo hin akawnt para han {{SITENAME}}',
'loginlanguagelabel'      => 'Yinaknan: $1',

# Password reset dialog
'resetpass'                 => 'Igliwat an tigaman-pagsulod',
'resetpass_header'          => 'Igliwan an akawnt nga tigaman-pagsulod',
'oldpassword'               => 'Daan nga tigaman-pagsulod:',
'newpassword'               => 'Bag-o nga tigaman-pagsulod:',
'retypenew'                 => 'Utroha pagbutang an bag-o nga tigaman-pagsulod:',
'resetpass_forbidden'       => 'Diri mababalyoan an mga tigaman-pagsulod',
'resetpass-submit-loggedin' => 'Igbal-iw an tigaman-pagsulod',
'resetpass-submit-cancel'   => 'Pasagdi',
'resetpass-temp-password'   => 'Temporaryo nga tigaman-pagsakob:',

# Edit page toolbar
'bold_sample'     => 'dakmola an agi',
'bold_tip'        => 'Dakmola an agi',
'italic_sample'   => 'Pakiling nga agi',
'italic_tip'      => 'Pakiling nga agi',
'link_sample'     => 'Titulo han sumpay',
'link_tip'        => 'Sumpay ha sulod',
'extlink_sample'  => 'http://www.example.com sumpay nga titulo',
'extlink_tip'     => 'Sumpay ha gawas (hinumdomi http:// pahiuna-nga-panumpay)',
'headline_sample' => 'teksto han katukiban',
'headline_tip'    => 'Katupngan 2 nga katukiban',
'nowiki_sample'   => 'Igsuksok an diri-nakaayos nga mga teksto dinhi',
'nowiki_tip'      => 'Pabay-i la an pagfoformat nga wiki',
'image_tip'       => 'Nakatampo nga fayl',
'media_tip'       => 'sumpay han fayl',
'sig_tip'         => 'Imo pirma nga may-ada marka hin oras',
'hr_tip'          => 'Patumba nga bagis (hinay-hinay la it paggamit)',

# Edit pages
'summary'                          => 'Dalikyat nga sumat hiton pagliwat:',
'subject'                          => 'Katukiban:',
'minoredit'                        => 'Gutiay ini nga pagliwat',
'watchthis'                        => 'Bantayi ini nga pakli',
'savearticle'                      => 'Igtipig an pakli',
'preview'                          => 'Pahiuna nga pagawas',
'showpreview'                      => 'Pakit-a an pahiuna nga pagawas',
'showlivepreview'                  => 'Buhi nga pahiuna nga pagawas',
'showdiff'                         => 'Igpakita an mga ginliwat',
'anoneditwarning'                  => "'''Pahimatngon:''' Diri ka pa naka log-in.
An imo IP address in maitatala ha kaagi hinin pakli han pagliwat.",
'missingsummary'                   => "'''Pahinumdom:''' Waray ka nagbutang hin dalikyat nga sumat han pagliwat.
Kun pidliton mo an \"{{int:savearticle}}\" utro, an imo ginliwat in matitipig bisan waray hini.",
'missingcommenttext'               => 'Alayon pagbutang hin komento ha ilarom.',
'summary-preview'                  => 'Pahiuna nga pagawas han dalikyat nga pulong:',
'subject-preview'                  => 'Pahiuna nga pagawas hit himangrawon:',
'blockedtitle'                     => 'Ginpugngan ini nga gumaramit',
'blockednoreason'                  => 'waray katadungan nga ginhatag',
'blockedoriginalsource'            => "An tinikangan han '''$1''' amo in ginpapakita ha ubos:",
'blockededitsource'                => "An mga sinurat han imo '''mga pagliwat''' ha '''$1''' in ginpapakita ha ubos:",
'whitelistedittitle'               => 'Kinahanglan mag-log-in para makaliwat',
'whitelistedittext'                => 'Kinahanglan mo mag-$1 para makaliwat han mga pakli.',
'nosuchsectiontitle'               => 'Waray kaagi-i an bahin',
'loginreqtitle'                    => 'Nagkikinahanglan hin pan-sakob',
'loginreqlink'                     => 'Pansakob',
'loginreqpagetext'                 => 'Kinahanglan mo mag-$1 para makakita ha iba nga mga pakli.',
'accmailtitle'                     => 'Ginpadara na an tigaman-pagsulod.',
'newarticle'                       => '(Bag-o)',
'newarticletext'                   => "Ginsunod mo an pakli nga waray pa kahihimo.  Para ighimo an pakli, tikanga pagmakinilya ha kahon nga aada ha ubos (kitaa an [[{{MediaWiki:Helppage}}|nabulig nga pakli]] para han kadugangan nga pananabutan).  Kun sayop an imo pagkanhi, igpidlit an imo kanan panngaykay (''browser'') '''balik''' (''back'') nga piridlitan.",
'noarticletext-nopermission'       => 'Ha yana waray surat ini nga pakli.
Puydi nimo [[Special:Search/{{PAGENAME}}|pamilngon ini nga titulo han pakli]] ha iba nga mga pakli,
o <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} pamilngon ha mga kasumpay nga talaan]</span>.',
'userpage-userdoesnotexist-view'   => "An akawnt han gumaramit ni ''$1'' in diri nakarehistro.",
'updated'                          => '(Ginbag-ohan)',
'note'                             => "'''Pahibaro:'''",
'previewnote'                      => "'''Hinumdumi nga pahiuna-nga-paggawas la ini.'''
An imo mga ginbag-o in waray pa katipig!",
'editing'                          => 'Ginliliwat an $1',
'editingsection'                   => 'Ginliliwat an $1 (bahin)',
'editingcomment'                   => 'Ginliliwat an $1 (bag-o nga bahin)',
'editconflict'                     => 'Diri pagkakauroyon han pagliwat: $1',
'yourtext'                         => 'Imo sinurat',
'storedversion'                    => 'Nakahipos nga pagbag-o',
'yourdiff'                         => 'Mga kaibhan',
'copyrightwarning'                 => "Iginpapasabot nga an ngatanan nga imo gin-amot ha {{SITENAME}} iginhatag mo ha ilarom han $2 (kitaa an $1 para han mga detalye).  Kun diri mo igkakalipay nga an imo ginsurat waray kalooy nga liliwaton ngan igpapakalat hit bisan hin-o nga it may gusto, alayon ayaw hiton igsumitir dinhi. <br />
Nasaad ka liwat nga imo ini kalugaringon nga ginsurat, o ginkopya nimo ini tikang ha panimongto nga dominyo o kapareho nga waray-sabit nga kuruhaon.
'''Ayaw igsumitir an mga buhat nga may ''copyright'' hin waray sarit!'''",
'templatesused'                    => '{{PLURAL:$1|Batakan|Mga batakan}} nga gingamit dinhi nga pakli:',
'template-protected'               => '(pinaliporan)',
'template-semiprotected'           => '(katunga nga pinasaliporan)',
'hiddencategories'                 => 'Ini nga pakli in api han {{PLURAL:$1|1 nakatago nga kaarangay|$1 nakatago nga kaarangay}}:',
'nocreate-loggedin'                => 'Diri ka gintutugotan paghimo hin mga bag-o nga pakli.',
'permissionserrors'                => 'Mga sayop hin mga pagtugot',
'permissionserrorstext'            => 'Diri ka gintutugotan pagbuhat hito, mahitungod han mga nasunod nga {{PLURAL:$1|katadungan|mga katadungan}}:',
'permissionserrorstext-withaction' => 'Waray ka permiso han $2, tungod han masunod nga {{PLURAL:$1|rason|mga rason}}:',
'recreate-moveddeleted-warn'       => "'''Pahimatngon: Naghihimo ka hin pakli nga ginpara na.'''

Angay mo hunahunaon kon naangay ba nga magpadayon hin pagliwat hini nga pakli.
An talaan hin pagpara ngan pagbalhin hini nga pakli ginhahatag dinhi para hin masayon nga pagkita:",
'moveddeleted-notice'              => 'Ini nga pakli in ginpara.
An taramdan han pagpara ngan pagbalhin para han pakli in ginhahatag ha ubos para han kasarigan.',
'log-fulllog'                      => 'Kitaa an bug-os nga taramdan',
'edit-conflict'                    => 'Diri pagkakauroyon han pagliwat.',
'edit-no-change'                   => 'Ginpabay-an an im pagliwat, mahitungod nga waray pagbalyo nga nabuhat ha nakasurat.',
'edit-already-exists'              => 'Diri nakakahimo hin bag-o nga pakli.
Aada na ito.',

# Parser/template warnings
'post-expand-template-inclusion-warning'  => "'''Pahimatngon:''' An batakan nga ginlakip in sobra kadako.
An iba nga mga batakan in diri mauupod.",
'post-expand-template-inclusion-category' => 'Mga pakli kun diin an mga nahilalakip nga kadako han batakan in nalabaw.',
'post-expand-template-argument-warning'   => "'''Pahimatngaon:''' Ini nga pakli in nagsusulod hin pinakaguti usa nga argumento hin batakan nga may-ada sobra nga dako it padako nga kadako.
Ini nga mga argumento in ginlaktawan.",
'post-expand-template-argument-category'  => 'Mga pakli nga nagsusulod hin ginlaktawan nga mga argumento hin batakan',

# Account creation failure
'cantcreateaccounttitle' => 'Diri makakahimo hin akawnt',

# History pages
'viewpagelogs'           => 'Kitaa an mga log para hini nga pakli',
'currentrev'             => 'Giurhii nga pagliwat',
'currentrev-asof'        => 'Giuurhii nga pagliwat han $1',
'revisionasof'           => 'Pagbabag-o han $1',
'revision-info'          => 'Pagbag-o han $1 ni $',
'previousrevision'       => '← Durudaan nga pagliwat',
'nextrevision'           => 'Burubag-o nga pagliwat →',
'currentrevisionlink'    => 'Giurhii nga pagliwat',
'cur'                    => 'yana',
'next'                   => 'sunod',
'last'                   => 'urhi',
'page_first'             => 'syahan',
'page_last'              => 'katapusan',
'histlegend'             => "Kaibhan nga pirilion: Igmarka an mga radyo nga kahon han mga pagbag-o para maikumpara ngan igu-a an ''enter'' o an piridlitan ha ubos.<br />
Leyenda: '''({{int:cur}})''' = kaibhan ha giuurhii nga pag-bag-o, '''({{int:last}})''' = kaibhan ha nahiuna nga pag-bag-o, '''{{int:minoreditletter}}''' = gagmay nga pagliwat.",
'history-fieldset-title' => 'Kaagi han panngaykay',
'history-show-deleted'   => 'Ginpara la',
'histfirst'              => 'Giunhani',
'histlast'               => 'Giurhii',
'historyempty'           => '(waray sulod)',

# Revision feed
'history-feed-title'          => 'Kaagi han pagliwat',
'history-feed-description'    => 'Kaagi han pagliwat para hini nga pakli ha wiki',
'history-feed-item-nocomment' => '$1 ha $2',

# Revision deletion
'rev-deleted-user'            => '(gintanggal an agnay hiton gumaramit)',
'rev-delundel'                => 'igpakita/igtago',
'rev-showdeleted'             => 'igpakita',
'revdelete-show-file-confirm' => 'Sigurado ka nga gusto mo makita an ginpara nga pagliwat han file "<nowiki>$1</nowiki>" tikang $2 ha $3?',
'revdelete-show-file-submit'  => 'Oo',
'revdelete-radio-same'        => '(ayaw balyu-e)',
'revdelete-radio-set'         => 'Oo',
'revdelete-radio-unset'       => 'Ayaw',
'revdelete-log'               => 'Rason:',
'revdel-restore'              => 'igliwat an nakikit-an',
'revdel-restore-deleted'      => 'napara nga mga pagbag-o',
'revdel-restore-visible'      => 'Mga nakikit-an nga pagbabag-o',
'revdelete-content'           => 'sulod',
'revdelete-uname'             => 'agnay hiton gumaramit',
'revdelete-hid'               => 'igtago an $1',
'revdelete-unhid'             => 'iggawas an $1',
'revdelete-log-message'       => '$1 para ha $2 {{PLURAL:$2|pagliwat|mga pagliwat}}',
'logdelete-log-message'       => '$1 para ha $2 {{PLURAL:$2|panhitabo|mga panhitabo}}',
'revdelete-otherreason'       => 'Lain/dugang nga katadungan:',
'revdelete-reasonotherlist'   => 'Lain nga katadongan',
'revdelete-edit-reasonlist'   => 'Igliwat an mga katadungan han pagpara',
'revdelete-offender'          => 'An nagliwat:',

# History merging
'mergehistory'                  => 'Igtampo an mga kasaysayan han pakli',
'mergehistory-from'             => 'Ginkuhaan nga pakli:',
'mergehistory-into'             => 'Kakadtoan nga pakli:',
'mergehistory-no-source'        => 'Waray pa an tinikangan nga pakli nga $1.',
'mergehistory-no-destination'   => 'Waray pa an kakadtuan nga pakli nga $1.',
'mergehistory-same-destination' => 'An gintikangan ngan kakadtoan nga mga pakli in diri puydi magkaparo',
'mergehistory-reason'           => 'Katadungan:',

# Merge log
'revertmerge' => 'Igbulag an gintampo',

# Diffs
'history-title'           => "Kaagi han pagbag-o han ''$1''",
'difference'              => '(Kaibhan han kabutngaan han mga pagliwat)',
'lineno'                  => 'Bagis $1:',
'compareselectedversions' => 'Igkumpara an mga pinili nga pagbabag-o',
'editundo'                => 'Igpawara an ginbuhat',
'diff-multi'              => '({{PLURAL:$1|Usa nga panbutnga nga pagbag-o|$1 nga panbutnga nga pagbag-o}} ni {{PLURAL:$2|usa nga gumaramit|$2 nga mga gumaramit}} waray ginpakita)',

# Search results
'searchresults'                    => 'Mga nabilingan han pagbiling',
'searchresults-title'              => 'Mga nabilngan han pagbiling para han "$1"',
'prevn'                            => 'naha-una nga {{PLURAL:$1|$1}}',
'nextn'                            => 'sunod nga {{PLURAL:$1|$1}}',
'prevn-title'                      => 'Nahiuna $1 {{PLURAL:$1|resulta|mga resulta}}',
'nextn-title'                      => 'Sunod nga $1 {{PLURAL:$1|resulta|mga resulta}}',
'shown-title'                      => 'Kitaa $1 {{PLURAL:$1|resulta|mga resulta}} kada pakli',
'viewprevnext'                     => 'Kitaa an ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Mga pagpipilian han pamiling',
'searchmenu-exists'                => "'''May-ada pakli nga nakangaran hin \"[[:\$1]]\" hini nga wiki.'''",
'searchmenu-new'                   => "'''Himoa an pakli \"[[:\$1]]\" hini nga wiki!'''",
'searchhelp-url'                   => 'Help:Sulod',
'searchprofile-articles'           => 'Mga unod nga pakli',
'searchprofile-project'            => 'Mga Bulig ngan Proyekto nga pakli',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Ngatanan',
'searchprofile-advanced'           => 'Abansado',
'searchprofile-articles-tooltip'   => 'Pamiling ha $1',
'searchprofile-project-tooltip'    => 'Pamiling ha $1',
'searchprofile-images-tooltip'     => 'Pamiling hin mga fayl',
'searchprofile-everything-tooltip' => 'Pamiling ha ngatanan nga sulod (lakip an mga hiruhimangraw nga pakli)',
'searchprofile-advanced-tooltip'   => "Pamilnga ha mga nabatasan nga ngaran-lat'ang",
'search-result-size'               => '$1 ({{PLURAL:$2|1 nga pulong|$2 nga mga pulong}})',
'search-result-category-size'      => '{{PLURAL:$1|1 nga api|$1 nga mga api}} ({{PLURAL:$2|1 nga ubos-nga-kaarangay|$2 nga mga ubos-nga-kaarangay}}, {{PLURAL:$3| 1 nga fayl|$3 nga mga fayl}})',
'search-redirect'                  => '(redirekta $1)',
'search-section'                   => '(bahin $1)',
'search-suggest'                   => 'Buot sidngon mo ba: $1',
'search-interwiki-caption'         => 'Mga bugto nga proyekto',
'search-interwiki-default'         => '$1 nga resulta:',
'search-mwsuggest-enabled'         => 'upod hin mga suhestyon',
'search-mwsuggest-disabled'        => 'waray mga suhestyon',
'searchrelated'                    => 'kadugtong',
'searchall'                        => 'ngatanan',
'showingresultsheader'             => "{{PLURAL:$5|Resulta '''$1''' han '''$3'''|Mga resulta '''$1 - $2''' han '''$3'''}} para ha '''$4'''",
'search-nonefound'                 => 'Waray resulta an nakakabaton han pakiana.',
'powersearch'                      => 'Abansado nga pagbiling',
'powersearch-legend'               => 'Abansado nga pagbiling',
'powersearch-field'                => 'Bilnga an',
'powersearch-toggleall'            => 'Ngatanan',
'powersearch-togglenone'           => 'Waray',
'search-external'                  => 'Gawas nga pamiling',

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
'prefs-editing'             => 'Ginliliwat',
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
'timezoneregion-antarctica' => 'Antarktika',
'timezoneregion-arctic'     => 'Arktika',
'timezoneregion-asia'       => 'Asya',
'timezoneregion-atlantic'   => 'Kalawdan Atlantika',
'timezoneregion-australia'  => 'Australya',
'timezoneregion-europe'     => 'Europa',
'timezoneregion-indian'     => 'Kalawdan Indyana',
'timezoneregion-pacific'    => 'Kalawdan Pasipiko',
'prefs-searchoptions'       => 'Mga pagpipilian han pamiling',
'prefs-namespaces'          => "Ngaran-lat'ang",
'youremail'                 => 'E-mail:',
'username'                  => 'Agnay hiton gumaramit:',
'uid'                       => 'ID han gumaramit:',
'yourrealname'              => 'Tinuod nga ngaran:',
'yourlanguage'              => 'Yinaknan:',
'yournick'                  => 'Bag-o nga pirma:',
'badsiglength'              => 'Hilaba hin duro it im pirma.
Dapat diri malabaw ha $1 {{PLURAL:$1|agi|mga agi}} nga kahilaba.',
'gender-male'               => 'Lalaki',
'gender-female'             => 'Babaye',
'email'                     => 'E-mail',
'prefs-help-email-required' => 'Kinahanglanon it e-mail address.',
'prefs-info'                => 'Panguna nga pananabotan',
'prefs-signature'           => 'Pirma',
'prefs-diffs'               => 'Mga kaibhan',

# User rights
'userrights-groupsmember' => 'Api han:',
'userrights-reason'       => 'Katadungan:',

# Groups
'group'            => 'Hugpo:',
'group-user'       => 'Mga gumaramit',
'group-bot'        => 'Mga bot',
'group-sysop'      => 'Mga magdudumara',
'group-bureaucrat' => 'Mga burokrata',
'group-suppress'   => 'Mga mananahon',
'group-all'        => '(ngatanan)',

'group-user-member'  => 'gumaramit',
'group-sysop-member' => 'magdudumara',

'grouppage-user'  => '{{ns:project}}:Mga gumaramit',
'grouppage-sysop' => '{{ns:project}}:Mga magdudumara',

# Rights
'right-read'       => 'Igbasa an mga pakli',
'right-edit'       => 'Igliwat an mga pakli',
'right-createpage' => 'Paghimo hin mga pakli (nga diri an mga hiruhimangraw nga mga pakli)',
'right-createtalk' => 'Paghimo hin hiruhimangraw nga mga pakli',
'right-minoredit'  => 'Igmarka an mga ginliwat komo gutiay la',
'right-move'       => 'Igbalhin an mga pakli',
'right-movefile'   => 'Balhina an mga fayl',
'right-delete'     => 'Igpara an mga pakli',
'right-undelete'   => 'Igpawara an pagpara han pakli',

# User rights log
'rightsnone' => '(waray)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'           => 'basaha ini nga pakli',
'action-edit'           => 'liwata ini nga pakli',
'action-createpage'     => 'pahimo hin mga pakli',
'action-minoredit'      => 'butanga hin tigaman hinin nga pagliwat komo gutiay',
'action-move'           => 'balhina ini nga pakli',
'action-delete'         => 'paraa ini nga pakli',
'action-deleterevision' => 'igpara ini nga pagbag-o',

# Recent changes
'nchanges'                        => '$1 {{PLURAL:$1|pagbag-o|mga pagbabag-o}}',
'recentchanges'                   => 'Mga kabag-ohan',
'recentchanges-legend'            => 'Mga pirilion han mga lab-as nga pagbag-o',
'recentchangestext'               => 'Nasubay han pinakalab-as nga pagbag-o ha wiki dinhi nga pakli.',
'recentchanges-feed-description'  => 'Panultol han pinakalab-as nga pagbabag-o ha wiki dinhi nga panubong.',
'recentchanges-label-newpage'     => 'Ini nga pagliwat hin naghimo hin bag-o nga pakli',
'recentchanges-label-minor'       => 'Gutiay ini nga pagliwat',
'recentchanges-label-bot'         => 'Ini nga pagliwat in ginbuhat han bot',
'recentchanges-label-unpatrolled' => 'Ini nga pagliwat in diri pa nakapatrol',
'rcnote'                          => "An ha ubos {{PLURAL:$1|in '''1''' nga pagbag-o|in kaurhian nga mga '''$1''' nga pagbag-o}} ha kaurhian nga {{PLURAL:$2|ka adlaw|'''$2''' ka mga adlaw}}, han $5, $4.",
'rcnotefrom'                      => "An ha ubos in mga pagbabag-o tikanng '''$''' (kutob ngadto ha '''$1''' nga ginpakita).",
'rclistfrom'                      => 'Pakit-a an mga ginbag-ohan tikang han $1',
'rcshowhideminor'                 => '$1 gudti nga mga pagliwat',
'rcshowhidebots'                  => '$1 mga bot',
'rcshowhideliu'                   => '$1 mga naka-log-in nga gumaramit',
'rcshowhideanons'                 => '$1 waray nagpakilala nga mga gumaramit',
'rcshowhidepatr'                  => '$1 mga pinatrolya nga mga paliwat',
'rcshowhidemine'                  => '$1 akon mga ginliwat',
'rclinks'                         => 'Igpakita an katapusan nga $1 nga pagbabag-o ha sulod han urhi nga $2 ka mga adlaw<br />$3',
'diff'                            => 'kaibhan',
'hist'                            => 'kaagi',
'hide'                            => 'Tago-a',
'show'                            => 'Igpakita',
'minoreditletter'                 => 'g',
'newpageletter'                   => 'B',
'boteditletter'                   => 'b',
'rc_categories_any'               => 'Bisan ano nga',
'rc-enhanced-expand'              => 'Igpakita an detalye (nagkikinahanglan hin JavaScript)',
'rc-enhanced-hide'                => 'Igtago an mga detalye',

# Recent changes linked
'recentchangeslinked'          => 'Mga may kalabotan nga binag-o',
'recentchangeslinked-feed'     => 'Mga may kalabotan nga binag-o',
'recentchangeslinked-toolbox'  => 'Mga may kalabotan nga binag-o',
'recentchangeslinked-title'    => "Mga pagbag-o kasumpay ha ''$1''",
'recentchangeslinked-noresult' => 'Waray pagbabag-o ha mga nakasumpay nga pakli han ginhatag nga oras.',
'recentchangeslinked-summary'  => "Ini nga taramdan hin pagbag-o nga lab-as nga hinimo ha mga pakli nga nakasumpay tikang a naka-specifico nga pakli (o ha api han uska specifico nga kaarangay).
Mga pakli ha [[Special:Watchlist|imo angay timan-an]] in naka-'''bold'''.",
'recentchangeslinked-page'     => 'Ngaran han pakli:',
'recentchangeslinked-to'       => 'Igpakita lugod an mga pagbabag-o han mga pakli nga nahisumpay ha ginhatag nga pakli',

# Upload
'upload'                  => 'Pagkarga hin file',
'uploadbtn'               => 'Igkarga an file',
'upload-recreate-warning' => "'''Pahimatngon:  An fayl nga may-ada hiton nga ngaran in ginpara o ginbalhin.'''

An taramdan han pagpara ngan pagbalhin para hini nga pakli in ginhahatag para han imo kamurayaw:",
'uploadlogpage'           => 'Talaan han mga ginkarga-paigbaw',
'filename'                => 'Ngaran han fayl',
'filedesc'                => 'Dalikyat nga pulong',
'fileuploadsummary'       => 'Dalikyat nga pulong:',
'filereuploadsummary'     => 'Mga pagbal-iw ha fayl:',
'filesource'              => 'Tinikangan:',
'ignorewarning'           => 'Pabay-i an pahimatngon ngan igtipig la ngahaw',
'filename-tooshort'       => 'An ngaran han fayl in halipot hin duro.',
'illegal-filename'        => 'An ngaran han fayl in diri gintutugutan.',
'unknown-error'           => 'Nahitabo an waray kasasabti nga sayop.',
'uploadwarning'           => 'Pahimatngon han pagkarga paigbaw',
'uploadedimage'           => 'ginkarga-paigbaw "[[$1]]"',
'uploadvirus'             => 'Ini nga fayl may-ada sulod nga bayrus!
Mga detalye: $1',
'upload-source'           => 'Tinikangan nga fayl',
'sourcefilename'          => 'Tinikangan nga ngaran han fayl:',
'upload-warning-subj'     => 'Pahimatngon han pagkarga paigbaw',

'upload-unknown-size' => 'Waray kasabti an kadako',

# img_auth script messages
'img-auth-accessdenied' => 'Diri gintutugutan makasulod',

# HTTP errors
'http-host-unreachable' => 'Diri nakakaabot ha URL.',

'license'        => 'Palilisensya:',
'license-header' => 'Palilisensya:',
'nolicense'      => 'Waray napili',

# Special:ListFiles
'imgfile'         => 'fayl',
'listfiles'       => 'Listahan han fayl',
'listfiles_date'  => 'Pitsa',
'listfiles_name'  => 'Ngaran',
'listfiles_user'  => 'Nagamit',
'listfiles_size'  => 'Kadako',
'listfiles_count' => 'Mga bersyon',

# File description page
'file-anchor-link'        => 'Fayl',
'filehist'                => 'Kaagi han fayl',
'filehist-help'           => 'Pidlita an adlaw/oras para makit-an an fayl nga naggawas hito nga oras.',
'filehist-deleteall'      => 'Paraa ngatanan',
'filehist-deleteone'      => 'paraa',
'filehist-revert'         => 'igbalik',
'filehist-current'        => 'yana',
'filehist-datetime'       => 'Pitsa/Oras',
'filehist-thumb'          => 'Thumbnail',
'filehist-thumbtext'      => 'Bersyon han thumbnail han $1',
'filehist-user'           => 'Gumaramit',
'filehist-dimensions'     => 'Mga dimensyon',
'filehist-filesize'       => 'Kadako han fayl',
'filehist-comment'        => 'Komento',
'filehist-missing'        => 'Nawawara an fayl',
'imagelinks'              => 'Mga gamit hin fayl',
'linkstoimage'            => 'An nasunod nga {{PLURAL:$1|pakli nasumpay|$1 mga pakli nasumpay}} hini nga fayl:',
'nolinkstoimage'          => 'Waray mga pakli nga nasumpay hini nga fayl.',
'sharedupload'            => 'Ini nga fayl tikang han $1 ngan puyde magamit ha iba nga mga proyekto.',
'sharedupload-desc-there' => 'Ini nga fayl tikang han $1 ngan puyde magamit ha iba nga mga proyekto.
Alayon pagkita han [$2 nga pakli hin pagpahayag mahitungod hini nga fayl] para hin dugang nga kasayuran.',
'sharedupload-desc-here'  => 'An fayl in tikang ha $1 ngan puydi mahigamitan para han iba nga mga proyekto.
An paglaladawan han iya [$2 fayl han paglaladawan nga pakli] didto in ginpapakita ha sirong.',
'shared-repo-from'        => 'tikang $1',

# File reversion
'filerevert-comment' => 'Rason:',

# File deletion
'filedelete'                  => 'Igpara $1',
'filedelete-legend'           => 'Igpara an file',
'filedelete-comment'          => 'Katadungan:',
'filedelete-submit'           => 'Paraa',
'filedelete-success'          => "Napara an '''$1'''",
'filedelete-otherreason'      => 'Lain/dugang nga katadungan:',
'filedelete-reason-otherlist' => 'Lain nga katadungan',

# MIME search
'download' => 'pagkarga paubos',

# Unused templates
'unusedtemplateswlh' => 'iba nga mga sumpay',

# Random page
'randompage' => 'Bisan ano nga pakli',

# Statistics
'statistics'                   => 'Mga estadistika',
'statistics-header-pages'      => 'Mga estadistika han pakli',
'statistics-header-edits'      => 'Mga estadistika han pagliwat',
'statistics-header-views'      => 'Mga estadistika han nakita',
'statistics-header-users'      => 'Mga estadistika han gumaramit',
'statistics-header-hooks'      => 'Lain nga mga estadistika',
'statistics-articles'          => 'Unod nga mga pakli',
'statistics-pages'             => 'Mga pakli',
'statistics-pages-desc'        => 'Ngatanan nga mga pakli ha sulod hini nga wiki, lakip an hiruhimangraw nga mga pakli, mga redirect, ngan iba pa',
'statistics-files'             => 'Ginkarga nga mga file',
'statistics-edits'             => 'Mga pagliwat hit pakli tikang gintukod hini nga {{SITENAME}}',
'statistics-edits-average'     => 'Average nga mga pagliwat kada pakli',
'statistics-views-total'       => 'Ngatanan nga mga panginano',
'statistics-views-peredit'     => 'Mga panginano kada pagliwat',
'statistics-users-active'      => 'Mga nanggigios nga gumaramit',
'statistics-users-active-desc' => 'Mga gumaramit nga may-ada iginbuhat ha urhi nga {{PLURAL:$1|ka adlaw|$1 ka mga adlaw}}',
'statistics-mostpopular'       => 'Gidamoi nga ginpanginanohan nga mga pakli',

'disambiguations'     => 'Mga pakli nga nasumpay ha mga pansayod nga pakli',
'disambiguationspage' => 'Template:pansayod',

'brokenredirects-edit'   => 'igliwat',
'brokenredirects-delete' => 'paraa',

'withoutinterwiki-submit' => 'Igpakita',

# Miscellaneous special pages
'nbytes'               => '$1 {{PLURAL:$1|nga byte|nga mga byte}}',
'ncategories'          => '$1 {{PLURAL:$1|nga kaarangay|nga mga kaarangay}}',
'nlinks'               => '$1 {{PLURAL:$1|nga sumpay|nga mga sumpay}}',
'nmembers'             => '$1 {{PLURAL:$1|nga api|nga mga api}}',
'nrevisions'           => '$1 {{PLURAL:$1|nga pagliwat|nga mga pagliwat}}',
'nviews'               => '$1 {{PLURAL:$1|nga pangita|nga mga pangita}}',
'nimagelinks'          => 'Gingamit ha $1 {{PLURAL:$1|nga pakli|nga mga pakli}}',
'ntransclusions'       => 'gingamit ha $1 {{PLURAL:$1|nga pakli|nga mga pakli}}',
'specialpage-empty'    => 'Waray mga resulta para hini nga report.',
'lonelypages'          => 'Mga nahibulag nga mga pakli',
'uncategorizedpages'   => 'Mga nagkikinahanglan hin pakli',
'unusedcategories'     => 'Waray kagamit nga mga kaarangay',
'unusedimages'         => 'Waray kagamit nga mga fayl',
'popularpages'         => 'Mga sikat nga pakli',
'wantedcategories'     => 'Mga nagkikinahanglan hin kaarangay',
'wantedpages'          => 'Mga ginkikinahanglan nga pakli',
'wantedfiles'          => 'Mga nagkikinahanglan hin file',
'wantedtemplates'      => 'Mga ginkikinahanglan nga batakan',
'mostlinked'           => 'Pinakadamo nga mga ginsumpayan nga pakli',
'mostlinkedcategories' => 'Pinakadamo nga mga ginsumpayan nga kaarangay',
'mostlinkedtemplates'  => 'Pinakadamo nga mga ginsumpayan nga batakan',
'prefixindex'          => 'Ngatanan nga pakli nga may-ada pahiuna-nga-sumpay',
'shortpages'           => 'Haglipot nga mga pakli',
'longpages'            => 'Haglaba nga mga pakli',
'listusers'            => 'Lista han mga gumaramit',
'usercreated'          => 'Ginhimo han $1 ha $2',
'newpages'             => 'Bag-o nga mga pakli',
'newpages-username'    => 'Agnay hiton gumaramit:',
'ancientpages'         => 'Mga gidaani nga pakli',
'move'                 => 'Balhina',
'movethispage'         => 'Balhina ini nga pakli',
'notargettitle'        => 'Waray iiguon',
'pager-newer-n'        => '{{PLURAL:$1|burubag-o 1|burubag-o $1}}',
'pager-older-n'        => '{{PLURAL:$1|durudaan 1|durudaan $1}}',

# Book sources
'booksources'               => 'Mga libro nga tinikangan',
'booksources-search-legend' => 'Pamilnga an mga libro nga gintikangan',
'booksources-go'            => 'Kadto-a',

# Special:Log
'specialloguserlabel'  => 'Magburuhat:',
'speciallogtitlelabel' => 'iiguon (titulo o gumarami):',
'log'                  => 'Mga talaan',

# Special:AllPages
'allpages'          => 'Ngatanan nga mga pakli',
'alphaindexline'    => '$1 tubtob ha $2',
'nextpage'          => 'Sunod nga pakli ($1)',
'prevpage'          => 'Nahiuna nga pakli ($1)',
'allpagesfrom'      => 'Igpakita an mga pakli nga nagtitikang ha:',
'allpagesto'        => 'Igpakita an mga pakli nga nahuhuman ha:',
'allarticles'       => 'Ngatanan nga mga artikulo',
'allinnamespace'    => "Ngatanan nga mga pakli ($1 ngaran-lat'ang)",
'allnotinnamespace' => "Ngatanan nga mga pakli (waray ha $1 ngaran-lat'ang)",
'allpagesprev'      => 'Naha-una',
'allpagesnext'      => 'Sunod',
'allpagessubmit'    => 'Kadto-a',

# Special:Categories
'categories'                    => 'Mga kaarangay',
'categoriesfrom'                => 'Igpakita in mga kaarangay nga natikang ha:',
'special-categories-sort-count' => 'igtalaan ha pag-ihap',
'special-categories-sort-abc'   => 'igtalaan ha abakadahan',

# Special:DeletedContributions
'deletedcontributions'             => 'Mga ginpara nga mga ámot hin nágámit',
'deletedcontributions-title'       => 'Ginpara nga mga amot han nagamit',
'sp-deletedcontributions-contribs' => 'mga amot',

# Special:LinkSearch
'linksearch'      => 'Pamiling ha mga sumpay ha gawas',
'linksearch-ns'   => "Ngaran-lat'ang:",
'linksearch-ok'   => 'Pamilnga',
'linksearch-line' => 'An $1 in nahasumpay tikang ha $2',

# Special:ListUsers
'listusersfrom'      => 'Igpakita an mga nagamit nga nagtitikang ha:',
'listusers-submit'   => 'Pakit-a',
'listusers-noresult' => 'Waray gumaramit nga nahiagian.',
'listusers-blocked'  => '(ginpugngan)',

# Special:ActiveUsers
'activeusers'            => 'Lista han mga nanggigios nga gumaramit',
'activeusers-hidebots'   => 'Igtago an mga bot',
'activeusers-hidesysops' => 'Igtago an mga magdudumara',
'activeusers-noresult'   => 'Waray gumaramit nga nahiagian.',

# Special:Log/newusers
'newuserlogpage'              => 'Talaan han paghimo hin gumaramit',
'newuserlogpagetext'          => 'Ini an talaan han mga nagkahihimo nga mga gumaramit.',
'newuserlog-byemail'          => 'Ginpadangat an tigaman-pagsulod pinaagi han e-mail',
'newuserlog-create-entry'     => 'Bag-o nga gumaramit nga akawnt',
'newuserlog-create2-entry'    => 'Nahimo an bag-o nga akawnt $1',
'newuserlog-autocreate-entry' => 'Awtomatiko nga nahimo an akawnt',

# Special:ListGroupRights
'listgrouprights-group'           => 'Hugpo',
'listgrouprights-rights'          => 'Mga katungod',
'listgrouprights-helppage'        => 'Help:Mga katungod han hugpo',
'listgrouprights-members'         => '(taramdan hiton mga api)',
'listgrouprights-addgroup'        => 'Dugnga {{PLURAL:$2|hugpo|mga hugpo}}: $1',
'listgrouprights-removegroup'     => 'Tanggala {{PLURAL:$2|hugpo|mga hugpo}}: $1',
'listgrouprights-addgroup-all'    => 'Igdugang ngatanan nga mga hugpo',
'listgrouprights-removegroup-all' => 'Igtanggal ngatanan nga mga hugpo',

# E-mail user
'emailuser'    => 'Ig-e-mail ini nga gumaramit',
'emailfrom'    => 'Tikang kan:',
'emailto'      => 'Para kan:',
'emailsubject' => 'Himangrawon:',
'emailmessage' => 'Buot igpasabot:',
'emailsend'    => 'Igpadara',
'emailccme'    => 'Igemail ako hini nga kopya hit ak buot igpasabot.',
'emailsent'    => 'Napadara an e-mail',

# Watchlist
'watchlist'         => 'Akon barantayan',
'mywatchlist'       => 'Akon angay timan-an',
'watchlistfor2'     => 'Para ha $1 $2',
'watch'             => 'Bantayi',
'watchthispage'     => 'Bantayi ini nga pakli',
'unwatch'           => 'Pabay-i an pagbantay',
'watchlist-details' => '{{PLURAL:$1|$1 nga pakli|$1 nga mga pakli}} nga aada ha imo talaan nga binabantayan, diri lakip an mga hiruhimangraw-nga-pakli.',
'wlshowlast'        => 'Igpakita an katapusan nga $1 nga mga oras $2 nga mga adlaw $3',
'watchlist-options' => 'Mga pirilian han talaan han binabantayan',

'enotif_newpagetext' => 'Ini in bag-o nga pakli.',
'created'            => 'nahimo',
'enotif_anon_editor' => 'waray magpakilala nga gumaramit $1',

# Delete
'deletepage'            => 'Igpara an pakli',
'excontent'             => "An sulod in: ''$1''",
'excontentauthor'       => 'an sulod in: \'\'$1\'\' (ngan hi "[[Special:Contributions/$2|$2]]" la an nag-amot)',
'exblank'               => 'waray sulod an pakli',
'delete-confirm'        => 'Igpara "$1"',
'delete-legend'         => 'Igpara',
'actioncomplete'        => 'Malinampuson an ginbuhat',
'actionfailed'          => 'Napakyas an ginbuhat',
'deletedtext'           => 'Ginpara an "<nowiki>$1</nowiki>".
Kitaa an $2 para hin talaan han mga gibag-ohi nga mga ginpamara.',
'deletedarticle'        => 'napara "[[$1]]"',
'dellogpage'            => 'Talaan han mga ginpara',
'deletecomment'         => 'Katadungan:',
'deletereasonotherlist' => 'Lain nga katadungan',
'deletereason-dropdown' => "*Agsob nga rason hin pagpara
** Tugon han manunurat
** Pagtalapas ha katungod hin pagtatag-iya (''copyright'')
** Bandalismo",

# Rollback
'rollback'       => 'Mga libot-pabalik nga pagliwat',
'rollback_short' => 'Libot-pabalik',
'rollbacklink'   => 'libot-pabalik',
'rollbackfailed' => 'Diri malinamposon an paglibot-pabalik',

# Protect
'protectlogpage'         => 'Talaan han pinasaliporan',
'protectedarticle'       => 'pinasaliporan "[[$1]]"',
'prot_1movedto2'         => '[[$1]] in ginbalhin ngadto ha [[$2]]',
'protectcomment'         => 'Katadongan:',
'protect-default'        => 'Togota an ngatanan nga mga gumaramit',
'protect-level-sysop'    => 'Mga magdudumara la',
'protect-othertime'      => 'Lain nga oras:',
'protect-othertime-op'   => 'lain nga oras',
'protect-otherreason'    => 'Lain/dugang nga katadongan:',
'protect-otherreason-op' => 'Lain nga katadongan',
'restriction-type'       => 'Pagtugot:',

# Restrictions (nouns)
'restriction-edit'   => 'Igliwat',
'restriction-move'   => 'Balhina',
'restriction-create' => 'Himo-a',

# Undelete
'undeletelink'              => 'igpakita/igbalik',
'undeleteviewlink'          => 'kitaa',
'undeletecomment'           => 'Katadungan:',
'undelete-search-submit'    => 'Bilnga',
'undelete-show-file-submit' => 'Oo',

# Namespace form on various pages
'namespace'      => "Ngaran-lat'ang",
'invert'         => 'Baliskara an pirilion',
'blanknamespace' => '(Panguna)',

# Contributions
'contributions'       => 'Mga amot han gumaramit',
'contributions-title' => 'Mga amot han gumaramit para ha $1',
'mycontris'           => 'Akon mga ámot',
'contribsub2'         => 'Para ha $1 $2',
'uctop'               => '(bawbaw)',
'month'               => 'Tikang ha bulan (ngan uruunhan):',
'year'                => 'Tikang ha tuig (ngan uruunhan):',

'sp-contributions-newbies'  => 'Igpakita an mga amot hin mga bag-o nga akawnt la',
'sp-contributions-blocklog' => 'Talaan han pagpugong',
'sp-contributions-uploads'  => 'mga ginkarga-paigbaw',
'sp-contributions-logs'     => 'Mga talaan',
'sp-contributions-talk'     => 'hiruhimangraw',
'sp-contributions-search'   => 'Pamiling hin mga ámot',
'sp-contributions-username' => 'IP nga adres o nágámit:',
'sp-contributions-toponly'  => 'Igpakita la an mga pagliwat nga giuurhii an pagbag-o',
'sp-contributions-submit'   => 'Bilnga',

# What links here
'whatlinkshere'            => 'Mga nasumpay dinhi',
'whatlinkshere-title'      => 'Mga pakli nga nasumpay ngadto ha "$1"',
'whatlinkshere-page'       => 'Pakli:',
'linkshere'                => "An masunod nga mga pakli in nasumpay ha '''[[:$1]]''':",
'nolinkshere'              => "Waray mga pakli nga nasumpay ha '''[[:$1]]'''",
'isredirect'               => 'Redirek nga pakli',
'istemplate'               => 'transklusyon',
'isimage'                  => 'sumpay han fayl',
'whatlinkshere-prev'       => '{{PLURAL:$1|nahiuna|nahiuna $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|masunod|masunod $1}}',
'whatlinkshere-links'      => '← mga sumpay',
'whatlinkshere-hideredirs' => '$1 nga mga redirek',
'whatlinkshere-hidetrans'  => '$1 nga mga transklusyon',
'whatlinkshere-hidelinks'  => '$1 an mga sumpay',
'whatlinkshere-hideimages' => '$1 sumpay hin hulagway',
'whatlinkshere-filters'    => 'Mga panara',

# Block/unblock
'blockip'                  => 'Pugngi an gumaramit',
'blockip-title'            => 'Pugngi an gumaramit',
'blockip-legend'           => 'Pugngi an gumaramit',
'ipbreason-dropdown'       => '*Agsob nga mga rason hit pagpugong
** Pagsusuksok hin sayop nga pananabutan
** Pagtatangtang hin sulod tikang ha mga pakli
** Bisan la ano nga pansusumpay ngadto ha gawas nga mga dapit
** Pansusuksok hin inamasang/buro-buro ngadto ha mga pakli
** Panhahadlok nga pamatasan/makakalilisang nga pansamok
** Pan-abusar hin dirudilain nga mga akawnt
** Diri makakarawat nga agnay-hit-gumaramit',
'ipbsubmit'                => 'Pugngi ini nga gumaramit',
'ipboptions'               => '2 ka oras:2 hours,1 ka adlaw:1 day,3 ka adlaw:3 days,1 ka semana:1 week,2 ka semana:2 weeks,1 ka bulan:1 month,3 ka bulan:3 months,6 ka bulan:6 months,1 ka tuig:1 year,waray katapusan:infinite',
'ipbotheroption'           => 'iba',
'ipbotherreason'           => 'Lain/dugang nga katadungan:',
'blockipsuccesssub'        => 'Malinamposon an pagpugong',
'ipblocklist'              => 'Mga ginpugngan nga gumaramit',
'ipblocklist-submit'       => 'Bilnga',
'blocklink'                => 'igpugong',
'unblocklink'              => 'igtanggal an pagpugong',
'change-blocklink'         => 'igliwan an papugong',
'contribslink'             => 'mga ámot',
'blocklogpage'             => 'Talaan han pagpugong',
'blocklogentry'            => 'ginpugngan hi [[$1]] nga natatapos ha takna hin $2 $3',
'block-log-flags-nocreate' => 'diri gintutugutan an paghimo hin akawnt',
'proxyblocksuccess'        => 'Human na.',

# Developer tools
'databasenotlocked' => 'An database in diri nakatrangka.',

# Move page
'move-page'            => 'Mabalhin an $1',
'movearticle'          => 'Balhina an pakli:',
'moveuserpage-warning' => "'''Pahimatngon:''' Tibalhin ka hin pakli hin gumaramit. Alayon pagtigaman nga an pakli là an mababalhin ngan an gumaramit in ''diri'' mababalyoan hin ngaran.",
'newtitle'             => 'Para ha bag-o nga titulo:',
'movepagebtn'          => 'Igbalhin an pakli',
'pagemovedsub'         => 'Malinamposon an pagbalhin',
'movedto'              => 'ginbalhin ngadto',
'movelogpage'          => 'Talaan han pagbalhin',
'movereason'           => 'Rason:',
'revertmove'           => 'igbalik',
'delete_and_move'      => 'Igapara ngan igbalhin',

# Export
'export'            => 'Mga pakli hit pagexport',
'export-addcattext' => 'Igdugang an mga pakli tikang ha kaarangay:',
'export-addcat'     => 'Dugngi',
'export-addnstext'  => "Igdugang an mga pakli tikang ha ngaran-lat'ang:",

# Namespace 8 related
'allmessagesname'           => 'Ngaran',
'allmessagesdefault'        => 'Daan aada nga teksto hiton mensahe',
'allmessages-filter-all'    => 'Ngatanan',
'allmessages-language'      => 'Yinaknan:',
'allmessages-filter-submit' => 'Kadto-a',

# Thumbnails
'thumbnail-more'       => 'Padako-a',
'filemissing'          => 'Nawawara an fayl',
'thumbnail_error'      => 'Sayo han paghihimo hin thumbnail: $1',
'thumbnail_image-type' => 'An klase han hulagway in diri suportado',

# Special:Import
'import-comment' => 'Komento:',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'An imo pakli hin gumaramit',
'tooltip-pt-mytalk'               => 'An imo pakli hin hiruhimangraw',
'tooltip-pt-preferences'          => 'An imo mga karuyag',
'tooltip-pt-watchlist'            => 'An talaan hin mga pakli nga imo ginsisinubay para hin mga kabag-ohan',
'tooltip-pt-mycontris'            => 'Talaan han imo mga ámot',
'tooltip-pt-login'                => 'Gin-aaghat ka nga mag log-in, pero diri ini ginpipirit.',
'tooltip-pt-logout'               => 'gawas',
'tooltip-ca-talk'                 => 'Hiruhimangraw mahiunong han sulod nga pakli',
'tooltip-ca-edit'                 => 'Puydi ka makaliwat hini nga pakli.  Alayon la paggamit han pahiuna nga paggawas nga piridlitan san-o an pagtipig',
'tooltip-ca-addsection'           => 'Pagtikang hin bag-o nga bahin',
'tooltip-ca-viewsource'           => 'Ini nga pakli in pinaliporan.
Makikit-an nimo an ginkuhaaan',
'tooltip-ca-history'              => 'Mga kahadto nga mga pagliwat hini nga pakli',
'tooltip-ca-protect'              => 'Panalipda ini nga pakli',
'tooltip-ca-delete'               => 'Para-a ini nga pakli',
'tooltip-ca-move'                 => 'Balhina ini nga pakli',
'tooltip-ca-watch'                => 'Dugnga ini nga pakli ngadto han imo talaan hin ginbibinantayan',
'tooltip-ca-unwatch'              => 'Igtanggal ini nga pakli tikang han imo tala hin binabantayan',
'tooltip-search'                  => 'Bilnga ha {{SITENAME}}',
'tooltip-search-go'               => 'Kadto hin pakli nga mayda hin gud nga exakto ngaran kon aadà',
'tooltip-search-fulltext'         => 'Bilnga ha mga pakli para hini nga texto',
'tooltip-p-logo'                  => 'Bisitaha an syahan nga pakli',
'tooltip-n-mainpage'              => 'Bisitaha an syahan nga pakli',
'tooltip-n-mainpage-description'  => 'Bisitaha an syahan nga pakli',
'tooltip-n-portal'                => 'Mahiunong han proyekto, ano an imo mahihimo, diin makabiling hin mga butang',
'tooltip-n-currentevents'         => 'Pamiling hin panginsayod ha mga kinabag-ohan nga mga panhitabo',
'tooltip-n-recentchanges'         => 'An talaan hin mga urhe nga mga kabag-ohan han wiki',
'tooltip-n-randompage'            => 'Pagkaraga hin bisan ano nga pakli',
'tooltip-n-help'                  => 'An lugar hin pagbiling',
'tooltip-t-whatlinkshere'         => 'Talaan han ngatanan nga wiki nga mga pakli nga nasumpay dinhe',
'tooltip-t-recentchangeslinked'   => 'Mga bag-o nga kabag-ohan ha mga pakli nga nahasumpay tikang hini nga pakli',
'tooltip-feed-rss'                => 'RSS nga pangarga para hini nga pakli',
'tooltip-feed-atom'               => 'Atom nga pangarga para hini nga pakli',
'tooltip-t-contributions'         => 'Kitaa an talaan hin mga amot hini nga nágámit',
'tooltip-t-emailuser'             => 'Padad-i hin e-mail ini nga nágámit',
'tooltip-t-upload'                => 'Pagkarga hin mga fayl',
'tooltip-t-specialpages'          => 'Talaan hin mga pinaurog nga pakli',
'tooltip-t-print'                 => 'Maipapatik nga bersyon hini nga pakli',
'tooltip-t-permalink'             => 'Sumpay nga unob ha hini nga pagliwat han pakli',
'tooltip-ca-nstab-main'           => 'Kitaa an sulod nga pakli',
'tooltip-ca-nstab-user'           => 'Kitaa an pakli han gumaramit',
'tooltip-ca-nstab-media'          => 'Kitaa an pakli hin media',
'tooltip-ca-nstab-special'        => 'Pinaurog nga pakli ini, diri ka ngahaw makakapagliwat han pakli',
'tooltip-ca-nstab-project'        => 'Kitaa an pakli han proyekto',
'tooltip-ca-nstab-image'          => 'Kitaa an pakli han fayl',
'tooltip-ca-nstab-mediawiki'      => 'Kitaa an mensahe han sistema',
'tooltip-ca-nstab-template'       => 'Kitaa an plantilya',
'tooltip-ca-nstab-help'           => 'Kitaa an pakli hin bulig',
'tooltip-ca-nstab-category'       => 'Kitaa an pakli hin kaarangay',
'tooltip-minoredit'               => 'Tigamni ini nga gamay nga pagliwat',
'tooltip-save'                    => 'Ig-seyb an imo mga pagbabag-o',
'tooltip-preview'                 => 'Pahiuna-a nga paggawas an imo mga pagliwat, alayon paggamit hini san-o mo igtipig!',
'tooltip-diff'                    => 'Igpakita an mga pagbabag-o nga imo ginbuhat ha teksto',
'tooltip-compareselectedversions' => 'Kitaa an mga kaibhan ha butnga han duha nga pinili nga mga pagliwat hini nga pakli',
'tooltip-watch'                   => 'Dugnga ini nga pakli ngadto han imo talaan hin ginbibinantayan',
'tooltip-rollback'                => 'An "libot-pabalik" in nabalik han (mga) pagliwat hini nga pakli ngadto han kataposan nga nag-amot hin usa ka pidlit',
'tooltip-undo'                    => '"Igpawara an ginbuhat (undo)" in nagbabalik hinin nga pagliwat ngan nabuklad hin pagliwat nga porma ha pahiuna-nga-paggawas nga kahimtang.  Natugot liwat pagdugang hin katadungan ha dinalikyat nga sumat.',
'tooltip-summary'                 => 'Pagbutang hin dalikyat nga sumat',

# Attribution
'othercontribs' => 'Ginbasihan ha binuhat ni $1.',

# Browsing diffs
'previousdiff' => '← Durudaan nga pagliwat',
'nextdiff'     => 'Burubag-o nga pagliwat',

# Media information
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|pakli|mga pakli}}',
'file-info-size'  => '$1 × $2 nga pixel, kadako han fayl: $3, MIME nga tipo: $4',
'svg-long-desc'   => 'SVG nga fayl, ginbabanabanahan nga $1 × $2 nga mga pixel, kadako han fayl: $3',
'show-big-image'  => 'Bug-os nga resolusyon',

# Special:NewFiles
'newimages-legend' => 'Panara',
'noimages'         => 'Waray makikit-an.',
'ilsubmit'         => 'Bilnga',

# Bad image list
'bad_image_list' => 'An kabutangan in masunod:

An nakatalala la nga mga butang (mga bagis nga nagtitikang hin *) in mahiuupod paglabot.
An syahan nga sumpay ha uska bagis in dapat may-ada sumpay ngadto ha maraot nga fayl.
An bisan ano nga masunod nga mga sumpay ha kapareho nga bagis in igtratrato nga eksepsyon, sugad hin, mga pakli kun diin an mga fayl in puydi mabubutang ha sulod han bagis.',

# Metadata
'metadata' => 'Metadata',

# EXIF tags
'exif-imagewidth'       => 'Kahaluag',
'exif-imagelength'      => 'Kahitaas',
'exif-ycbcrpositioning' => 'Pagpoposisyon han Y ngan C',
'exif-imagedescription' => 'Titulo han hulagway',
'exif-artist'           => 'Tag-iya',
'exif-sharpness'        => 'Pagkatarom',
'exif-gpsspeedref'      => 'Sukol han kalaksi',

'exif-exposureprogram-1' => 'Mano-mano',

'exif-subjectdistance-value' => '$1 ka mga metro',

'exif-lightsource-0'  => 'Waray kasabti',
'exif-lightsource-9'  => 'Maupay nga panahon',
'exif-lightsource-10' => 'Madampog nga panahon',

'exif-focalplaneresolutionunit-2' => 'pulgadas',

'exif-contrast-1' => 'Mahumok',
'exif-contrast-2' => 'Matig-a',

'exif-sharpness-1' => 'Mahumok',
'exif-sharpness-2' => 'Matig-a',

'exif-subjectdistancerange-0' => 'Waray kasabti',
'exif-subjectdistancerange-1' => 'Macro',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Tinood nga direksyon',

# External editor support
'edit-externally' => 'Igliwat ini nga fayl gamit han gawas nga aplikasyon',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ngatanan',
'imagelistall'     => 'ngatanan',
'watchlistall2'    => 'ngatanan',
'namespacesall'    => 'ngatanan',
'monthsall'        => 'ngatanan',
'limitall'         => 'ngatanan',

# Delete conflict
'recreate' => 'Himo-a utro',

# action=purge
'confirm_purge_button' => 'OK',

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
'table_pager_empty'        => 'Waray mga resulta',

# Size units
'size-bytes'     => '$1 nga B',
'size-kilobytes' => '$1 nga KB',
'size-megabytes' => '$1 nga MB',
'size-gigabytes' => '$1 nga GB',

# Live preview
'livepreview-loading' => 'Ginkakarga. . .',
'livepreview-ready'   => 'Ginkakarga. . . Pag-andam!',

# Watchlist editor
'watchlistedit-normal-submit' => 'Igtanggal an mga titulo',
'watchlistedit-raw-titles'    => 'Mga titulo:',

# Watchlist editing tools
'watchlisttools-view' => 'Kitaa an mga nanginginlabot nga mga pagbabag-o',
'watchlisttools-edit' => 'Kitaa ngan igliwat an talaan han binabantayan',
'watchlisttools-raw'  => 'Igliwat an hilaw nga talaan han binabantayan',

# Core parser functions
'duplicate-defaultsort' => '\'\'\'Pahimatngon:\'\'\' An daan-aada nga paglainlain nga piridlitan nga "$2" in igsasapaw an durudaan nga daan-aada nga paglainlain nga piridlitan nga "$1".',

# Special:Version
'version'                  => 'Bersyon',
'version-license'          => 'Lisensya',
'version-software-product' => 'Produkto',

# Special:FilePath
'filepath-submit' => 'Kadto-a',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'Pamilnga',

# Special:SpecialPages
'specialpages'                 => 'Mga pinaurog nga pakli',
'specialpages-group-pagetools' => 'Mga higamit han pakli',

# Special:BlankPage
'blankpage'              => 'Blanko nga pakli',
'intentionallyblankpage' => 'Ini nga pakli gintuyo pagpabilin nga blanko.',

# Special:Tags
'tag-filter'        => '[[Special:Tags|Tag]] panara:',
'tag-filter-submit' => 'Panara',
'tags-edit'         => 'igliwat',

# Special:ComparePages
'comparepages'     => 'Igkumpara an mga pakli',
'compare-selector' => 'Igkumpara an mga pagliwat han pakli',
'compare-page1'    => 'Pakli 1',
'compare-page2'    => 'Pakli 2',
'compare-rev1'     => 'Pagliwat 1',
'compare-rev2'     => 'Pagliwat 2',
'compare-submit'   => 'Igkumpara',

# Database error messages
'dberr-header' => 'Ini nga wiki mayda problema',

# HTML forms
'htmlform-reset'               => 'Igbalik an mga pinamalyuan',
'htmlform-selectorother-other' => 'iba',

);
