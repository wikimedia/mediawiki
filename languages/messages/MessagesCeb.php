<?php
/** Cebuano (Cebuano)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Abastillas
 * @author Jordz
 * @author Palang hernan
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Medya',
	NS_SPECIAL          => 'Espesyal',
	NS_TALK             => 'Hisgot',
	NS_USER             => 'Gumagamit',
	NS_USER_TALK        => 'Hisgot_sa_Gumagamit',
	NS_PROJECT_TALK     => 'Hisgot_sa_$1',
	NS_FILE             => 'Payl',
	NS_FILE_TALK        => 'Hisgot_sa_Payl',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Hisgot_sa_MediaWiki',
	NS_TEMPLATE         => 'Plantilya',
	NS_TEMPLATE_TALK    => 'Hisgot_sa_Plantilya',
	NS_HELP             => 'Tabang',
	NS_HELP_TALK        => 'Hisgot_sa_Tabang',
	NS_CATEGORY         => 'Kategoriya',
	NS_CATEGORY_TALK    => 'Hisgot_sa_Kategoriya',
);

$namespaceAliases = array(
	'Hisgot_sa$1' => NS_PROJECT_TALK,
	'Imahen' => NS_FILE,
	'Hisgot_sa_Imahen' => NS_FILE_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'DoblengRedirekta' ),
	'BrokenRedirects'           => array( 'BuakngaRedirekta' ),
	'Disambiguations'           => array( 'Mga Pagklaro' ),
	'CreateAccount'             => array( 'Paghimo\'gAkawnt' ),
	'Preferences'               => array( 'Mga Preperensya' ),
	'Watchlist'                 => array( 'Gibantayan' ),
	'Recentchanges'             => array( 'Bag-ongGiusab' ),
	'Upload'                    => array( 'Pagsumiter' ),
	'Listfiles'                 => array( 'Listahan sa Imahen' ),
	'Newimages'                 => array( 'Bag-ongImahen' ),
	'Statistics'                => array( 'Estadistika' ),
	'Randompage'                => array( 'Bisan-unsa', 'Bisan-unsangPanid' ),
	'Lonelypages'               => array( 'Nag-inusarangPanid', 'Sinagop nga Panid' ),
	'Allpages'                  => array( 'TanangPanid' ),
	'Contributions'             => array( 'Mga Tampo' ),
	'Categories'                => array( 'Mga Kategoriya' ),
	'Version'                   => array( 'Bersiyon' ),
	'Mypage'                    => array( 'AkongPanid' ),
	'Mytalk'                    => array( 'AkongHisgot' ),
	'Mycontributions'           => array( 'AkongTampo' ),
	'Search'                    => array( 'Pangita' ),
);

$messages = array(
# User preference toggles
'tog-underline'            => 'Binadlisan nga mga sumpay:',
'tog-hideminor'            => 'Ipakita ang gamayng pag-usab sa mga bag-ong giusab',
'tog-showtoolbar'          => 'Ipakita ang toolbar sa pag-edit (JavaScript)',
'tog-editondblclick'       => 'I-edit ang panid inig dobol-klik (JavaScript)',
'tog-editsection'          => 'Mausab ang mga seksiyon gamit ang [usba] nga sumpay',
'tog-rememberpassword'     => 'Hinumdomi ako sa kining kompyuter',
'tog-watchcreations'       => 'Bantayi ang akong gisugdang mga panid',
'tog-watchdefault'         => 'Bantayi ang akong giusab nga mga panid',
'tog-watchmoves'           => 'Bantayi ang akong gibalhin nga mga panid',
'tog-watchdeletion'        => 'Bantayi ang mga panid nga akong gipapas',
'tog-minordefault'         => 'Markahi ang tanang pag-usab isip ginagmay',
'tog-previewonfirst'       => 'Ipakita ang paunang tan-aw sa unang pag-usab',
'tog-nocache'              => 'Ayaw i-cache ang panid',
'tog-enotifwatchlistpages' => 'I-email ko kon ang panid nga akong gibantayan giusab.',
'tog-enotifusertalkpages'  => 'I-email ko kon nausab ang akong panid sa panaghisgot',
'tog-enotifminoredits'     => 'I-email ko alang sa mga ginagmay nga pag-usab',
'tog-shownumberswatching'  => 'Ipakita ang gidaghanon sa mga gumagamit nga nagbantay usab',
'tog-fancysig'             => 'Hilaw nga pirma (walay awtomatikong sumpay)',
'tog-forceeditsummary'     => 'Pahibaloi ako kon blangko ang mubong sugid alang sa pag-usab',
'tog-watchlisthideown'     => 'Tagoa ang akong mga giusab',
'tog-watchlisthidebots'    => 'Tagoa ang mga giusab sa bot',
'tog-watchlisthideminor'   => 'Tagoa ang mga gagmay nga pag-usab',
'tog-showhiddencats'       => 'Ipakita ang nakatagong mga kategoriya',

'underline-always' => 'Kanunay',
'underline-never'  => 'Ayaw',

# Dates
'sunday'        => 'Dominggo',
'monday'        => 'Lunes',
'tuesday'       => 'Martes',
'wednesday'     => 'Miyerkules',
'thursday'      => 'Huwebes',
'friday'        => 'Biyernes',
'saturday'      => 'Sabado',
'sun'           => 'Dom',
'mon'           => 'Lun',
'tue'           => 'Mar',
'wed'           => 'Miy',
'thu'           => 'Huw',
'fri'           => 'Biy',
'sat'           => 'Sab',
'january'       => 'Enero',
'february'      => 'Pebrero',
'march'         => 'Marso',
'april'         => 'Abril',
'may_long'      => 'Mayo',
'june'          => 'Hunyo',
'july'          => 'Hulyo',
'august'        => 'Agosto',
'september'     => 'Septiyembre',
'october'       => 'Oktubre',
'november'      => 'Nobiyembre',
'december'      => 'Disyembre',
'january-gen'   => 'Enero',
'february-gen'  => 'Pebrero',
'march-gen'     => 'Marso',
'april-gen'     => 'Abril',
'may-gen'       => 'Mayo',
'june-gen'      => 'Hunyo',
'july-gen'      => 'Hulyo',
'august-gen'    => 'Agosto',
'september-gen' => 'Septiyembre',
'october-gen'   => 'Oktubre',
'november-gen'  => 'Nobyembre',
'december-gen'  => 'Disyembre',
'jan'           => 'Ene',
'feb'           => 'Peb',
'apr'           => 'Abr',
'may'           => 'Mayo',
'jun'           => 'Hun',
'jul'           => 'Hul',
'aug'           => 'Ago',
'oct'           => 'Okt',
'nov'           => 'Nob',
'dec'           => 'Dis',

# Categories related messages
'pagecategories'                => '{{PLURAL:$1|Kategoriya|Mga kategoriya}}',
'category_header'               => 'Mga panid sa kategoriyang "$1"',
'subcategories'                 => 'Mga subkategoriya',
'category-media-header'         => 'Medya sa kategoriyang "$1"',
'category-empty'                => "''Kini nga kategoriya kasamtangang way sulod nga mga panid ug mga medya.''",
'hidden-categories'             => '{{PLURAL:$1|Nakatagong kategoriya|Mga nakatagong kategoriya}}',
'hidden-category-category'      => 'Mga nakatagong kategoriya', # Name of the category where hidden categories will be listed
'category-subcat-count'         => '{{PLURAL:$2|Kini nga kategoriya may usa lamang ka subkategoriya.|Kini nga kategoriya may {{PLURAL:$1|subkategoriya|$1 ka mga subkategorya}}, sa total nga $2.}}',
'category-subcat-count-limited' => 'Kini nga kategoriya adunay {{PLURAL:$1|ka subkategorya|$1 ka mga subkategoriya}}.',
'category-article-count'        => '{{PLURAL:$2|Kini nga kategoriya may usa lang ka panid.|Ang kining {{PLURAL:$1|ka panid|$1 ka mga panid}} nahiapil niining kategoryaha, sa $2 nga total.}}',
'category-file-count-limited'   => 'Ang mosunod nga {{PLURAL:$1|payl|$1 ka mga payl}} anaa niining kategoryaha.',

'mainpagetext'      => "<big>'''Malamposon ang pag-instalar sa MediaWiki.'''</big>",
'mainpagedocfooter' => 'Konsultaha ang [http://meta.wikimedia.org/wiki/Help:Contents Giya sa mga gumagamit] alang sa impormasyon unsaon paggamit niining wiki nga software.

== Pagsugod ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Listahan sa mga setting sa kompigurasyon]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ sa MediaWiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce  Mailing list sa mga release sa MediaWiki]',

'about'          => 'Mahitungod',
'newwindow'      => "(maabli sa laing ''window'')",
'cancel'         => 'I-way bili',
'qbfind'         => 'Pangitaa',
'qbedit'         => 'Usba',
'qbpageoptions'  => 'Kini nga panid',
'qbmyoptions'    => 'Akong mga panid',
'qbspecialpages' => 'Mga espesyal nga panid',
'moredotdotdot'  => 'Dugang pa...',
'mypage'         => 'Akong panid',
'mytalk'         => 'Akong hisgot',
'anontalk'       => 'Panghisgot-hisgot alang niining IP',
'navigation'     => 'Tabok-tabok',
'and'            => '&#32;ug',

'returnto'          => 'Balik sa $1.',
'tagline'           => 'Gikan sa {{SITENAME}}',
'help'              => 'Tabang',
'search'            => 'Pangita',
'searchbutton'      => 'Pangitaa',
'go'                => 'Sige',
'searcharticle'     => 'Sige',
'history'           => 'Kaagi ning panid',
'history_short'     => 'Kaagi',
'info_short'        => 'Impormasyon',
'printableversion'  => 'Mapatik nga bersiyon',
'permalink'         => 'Permanenteng sumpay',
'print'             => 'I-print',
'edit'              => 'Usba',
'create'            => 'Himoa',
'editthispage'      => 'Usba kining panid',
'create-this-page'  => 'Himoa kining panid',
'delete'            => 'Papasa',
'deletethispage'    => 'Papasa kining panid',
'undelete_short'    => 'Ibalik ang {{PLURAL:$1|usa ka pag-usab|$1 ka mga pag-usab}}',
'protect'           => 'Protektahi',
'protect_change'    => 'usba ang proteksyon',
'protectthispage'   => 'Protektahi kining panid',
'unprotect'         => 'Ayaw protektahi',
'unprotectthispage' => 'Ayaw na kini protektahi',
'newpage'           => 'Bag-ong panid',
'talkpage'          => 'Hisgoti kining panid',
'talkpagelinktext'  => 'Hisgot',
'specialpage'       => 'Espesyal nga panid',
'personaltools'     => 'Personal nga galamiton',
'postcomment'       => 'Pagbilin og komento',
'talk'              => 'Panaghisgot-hisgot',
'toolbox'           => 'Galamiton',
'userpage'          => 'Tan-awa ang panid sa gumagamit',
'imagepage'         => 'Tan-awa ang panid sa medya',
'mediawikipage'     => 'Tan-awa ang panid sa mensahe',
'templatepage'      => 'Tan-awa ang panid sa plantilya',
'viewhelppage'      => 'Tan-awa ang panid sa tabang',
'categorypage'      => 'Tan-awa ang panid sa kategoriya',
'viewtalkpage'      => 'Tan-awa ang panaghisgot-hisgot',
'otherlanguages'    => 'Sa ubang pinulongan',
'redirectedfrom'    => '(Naredirek gikan sa $1)',
'redirectpagesub'   => 'Panid sa redirekta',
'lastmodifiedat'    => 'Kini nga panid kataposang giusab niadtong $2, $1.', # $1 date, $2 time
'viewcount'         => 'Naablihan na sa {{PLURAL:$1|maka-usa|$1 ka higayon}} ang kining panid.',
'protectedpage'     => 'Giprotektahang panid',
'jumpto'            => 'Ambak sa:',
'jumptonavigation'  => 'tabok-tabok',
'jumptosearch'      => 'pangita',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Mahitungod sa {{SITENAME}}',
'aboutpage'            => 'Project:Mahitungod sa',
'copyright'            => 'Mga sulod makita sa $1.',
'copyrightpagename'    => '{{SITENAME}} katungod sa pagpatik',
'currentevents'        => 'Mga bag-ong panghitabo',
'currentevents-url'    => 'Project:Kasamtangang panghitabo',
'disclaimers'          => 'Mga pagpasabot',
'disclaimerpage'       => 'Project:Mga pagpasabot',
'edithelp'             => 'Tabang sa pag-usab',
'edithelppage'         => 'Help:Pag-usab',
'helppage'             => 'Help:Mga sulod',
'mainpage'             => 'Unang Panid',
'mainpage-description' => 'Unang Panid',
'portal'               => 'Tubaan',
'portal-url'           => 'Project:Ganghaan sa Komunidad',
'privacy'              => 'Palisiya sa personal nga impormasyon',
'privacypage'          => 'Project:Palisiya sa pribasidad',

'badaccess-group0' => 'Wala ikaw tugoti sa pagpadayon sa aksyon nga imong gipangayo.',
'badaccess-groups' => 'Ang aksyon nga imong gipangayo mahimo lamang ihatag sa mga miyembro sa mga grupong $1.',

'versionrequired'     => 'Gikinahanglan ang Bersyong $1 sa MediaWiki',
'versionrequiredtext' => 'Gikinahanglan ang Bersyong $1 sa MediaWiki aron magamit kining panid. Tan-awa ang [[Special:Version|panid sa bersyon]].',

'retrievedfrom'           => 'Gikuha gikan sa "$1"',
'youhavenewmessages'      => 'Aduna kay $1 ($2).',
'newmessageslink'         => 'bag-ong mensahe',
'newmessagesdifflink'     => 'ulahing pag-usab',
'youhavenewmessagesmulti' => 'Adunay kay bag-ong mensahe sa $1',
'editsection'             => 'usba',
'editold'                 => 'usba',
'viewsourceold'           => 'tan-awa ang ginikanan',
'editsectionhint'         => 'Usba ang seksyong: $1',
'toc'                     => 'Mga sulod',
'showtoc'                 => 'ipakita',
'hidetoc'                 => 'tagoa',
'thisisdeleted'           => 'Ipakita o ibalik ang $1?',
'viewdeleted'             => 'Ipakita ang $1?',
'feed-unavailable'        => "Wala tugoti ang mga ''Syndication Feeds'' sa {{SITENAME}}",
'red-link-title'          => '$1 (wala pa masulat)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artikulo',
'nstab-user'      => 'Panid sa tiggamit',
'nstab-special'   => 'Espesyal',
'nstab-project'   => 'Panid sa proyekto',
'nstab-image'     => 'Payl',
'nstab-mediawiki' => 'Mensahe',
'nstab-template'  => 'Plantilya',
'nstab-help'      => 'Panid sa tabang',
'nstab-category'  => 'Kategoriya',

# Main script and global functions
'nosuchaction'      => 'Walay maong aksyon',
'nosuchactiontext'  => 'Ang aksyon nga anaa sa URL wala gi-ila sa wiki',
'nosuchspecialpage' => 'Walay maong espesyal nga panid',
'nospecialpagetext' => "<big>'''Mihangyo ikaw sa inbalidong espesyal nga panid.'''</big>

Ang lista sa mga balidong espesyal nga mga panid makita sa [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error'               => 'Sayop',
'noconnect'           => "Pasayloa, adunay problemang teknikal sa karon ang kini nga wiki, ug dili makakontak sa serber sa ''database''.<br />
$1",
'cachederror'         => "Ang mosunod usa ka gi-''cache'' nga kopya sa gihangyong panid, ug mahimong karaan na.",
'laggedslavemode'     => 'Pahibalo: Mahimong dili mahiapil sa panid ang mga bag-ong kausaban.',
'readonly'            => 'Gitrangkahan ang database',
'internalerror'       => 'Internal nga sayop',
'internalerror_info'  => 'Internal nga sayop: $1',
'filecopyerror'       => 'Dili makopya ang payl nga "$1" ngadto sa "$2".',
'filerenameerror'     => 'Dili mailisag ngalan ang payl "$1" ngadto sa "$2".',
'filenotfound'        => 'Dili makita ang payl nga "$1".',
'formerror'           => 'Sayop: dili masumiter ang porma',
'badtitle'            => 'Bati nga titulo',
'badtitletext'        => 'Ang gihangyong titulo sa panid mahimong inbalido, walay sulod, o nasayop og sumpay nga inter-pinulongan o inter-wiki nga titulo.
Basin aduna kini usa o daghan pang mga karakter nga dili magamit isip titulo.',
'viewsource'          => 'Tan-awa ang ginikanan',
'ns-specialprotected' => 'Ang mga espesyal nga panid dili mausban.',

# Login and logout pages
'logouttitle'             => 'Pagbiya sa tiggamit',
'welcomecreation'         => '== Maayong pag-abot, $1! ==
Nahimo na ang imong akawnt.
Ayaw kalimot sa pag-usab sa imong [[Special:Preferences|{{SITENAME}} mga preperensiya]].',
'loginpagetitle'          => 'Pagdayon sa tiggamit',
'yourname'                => 'Ngalan sa tiggamit:',
'remembermypassword'      => 'Hinumdomi ako niini nga kompyuter',
'login'                   => 'Sulod',
'nav-login-createaccount' => 'Rehistro / Dayon',
'userlogin'               => 'Rehistro / Dayon',
'logout'                  => 'Biya',
'userlogout'              => 'Biya',
'notloggedin'             => 'Wala ka pa masulod',
'nologinlink'             => 'Paghimo og akawnt',
'userexists'              => 'Ang ngalan sa tiggamit nga imong gisulat nagamit na.
Palihug pagpili og lain nga ngalan.',
'loginerror'              => 'Sayop sa pagdayon',
'loginsuccesstitle'       => 'Malamposon ang pagpaila',
'loginlanguagelabel'      => 'Pinulongan: $1',

# Edit page toolbar
'bold_tip'      => 'Gilugom nga teksto',
'italic_sample' => 'Gitakilid nga teksto',
'italic_tip'    => 'Gitakilid nga teksto',
'nowiki_sample' => 'Dinhi ang dili-pormaton nga teksto',
'nowiki_tip'    => 'Dili i-wikipormat',
'sig_tip'       => 'Ang imong pirma uban ang takna',
'hr_tip'        => 'Pahigda nga linya (palihog usahay ra gamita)',

# Edit pages
'summary'                   => 'Mubong sugid:',
'minoredit'                 => 'Ginagmay lang nga kausaban',
'watchthis'                 => 'Bantayi kining maong panid',
'savearticle'               => 'Tipigi ang panid',
'preview'                   => 'Paunang tan-aw',
'showpreview'               => 'Paunang tan-aw',
'showdiff'                  => 'Ipakita ang kalainan',
'anoneditwarning'           => "'''Pahibalo:''' Wala ikaw maka-login.
Ang imong ''IP address'' maoy itala sa kaagi niini nga panid.",
'newarticle'                => '(Bag-o)',
'newarticletext'            => 'Mitulpok ka sa sumpay ngadto sa usa ka wala pa masulat nga panid.
Aron mahimo ang maong panid, pagtayp sa kahon sa ubos (tan-awa ang [[{{MediaWiki:Helppage}}|panid sa tabang]] alang sa dugang impormasyon).
Kon miabot ka dinhi pinaagi sa usa ka sayop, palihog tuploka ang back nga tuplokanan sa imong brawser.',
'noarticletext'             => 'Sa kasamtangan walay sulod nga teksto ang kining panid, pwede nimong  [[Special:Search/{{PAGENAME}}|pangitaon kining titulo sa panid]] sa ubang mga panid o [{{fullurl:{{FULLPAGENAME}}|action=edit}} usba kining panid].',
'userpage-userdoesnotexist' => 'Ang akawnt sa tiggamit nga "$1" wala marehistro. Palihug tan-awa kon buot nimong himoon/usbon ang kining panid.',
'previewnote'               => "'''Hinumdomi nga kini usa lang ka paunang tan-aw; wala pa matipigi ang imong giusab!'''",
'editing'                   => 'Nagausab sa $1',
'yourtext'                  => 'Imong gisulat',
'yourdiff'                  => 'Mga kalainan',
'copyrightwarning'          => "Palihog hinumdomi nga ang tanang kontribusyon sa {{SITENAME}} giisip nga ubos sa $2 (basaha ang $1 alang sa dugang detalye). Kon dili nimo buot nga ang imong mga sinulat mausab ni bisan kinsa ug maapud-apod bisan dili ka pangayoan og pagtugot, ayaw sila ibutang dinhi.<br />
Nagatimaan ka usab nga ikaw mismo ang nagsulat niini, o gikopya nimo kini gikan sa usa ka publikong rekursos o susamang libreng rekursos.
'''AYAW PAGBUTANG DINHI OG MGA BINUHAT NGA MAY NANAG-IYA SA KATUNGOD SA PAGPATIK NGA WA KAY PERMISO!'''",
'templatesused'             => 'Ang mga plantilyang gigamit niini nga panid:',
'template-protected'        => '(giprotektahan)',

# History pages
'revisionasof'     => 'Rebisyon niadtong $1',
'previousrevision' => '←Mas daang pag-usab',
'last'             => 'kataposan',
'histfirst'        => 'Kinaunahan',

# Diffs
'compareselectedversions' => 'Ikompara ang piniling mga bersiyon',
'editundo'                => 'i-way bili',

# Search results
'noexactmatch'   => "'''Walay panid nga ginganla'g \"\$1\".'''
Mahimo mong [[:\$1|isulat kini nga panid]].",
'prevn'          => 'miaging $1',
'nextn'          => 'sunod $1',
'viewprevnext'   => 'Tan-awa sa ($1) ($2) ($3)',
'searchhelp-url' => 'Help:Mga sulod',
'powersearch'    => 'Abansadong pagpangita',

# Preferences page
'mypreferences' => 'Akong preperensiya',
'prefs-edits'   => 'Gidaghanon sa nausab:',
'skin-preview'  => 'Paunang tan-aw',
'saveprefs'     => 'Tipigi',

# Recent changes
'recentchanges'   => 'Mga bag-ong giusab',
'rcnote'          => "Sa ubos {{PLURAL:$1|ang '''1''' kausaban|ang mga bag-ong '''$1''' kausaban}} sa miaging {{PLURAL:$2|ka adlaw|'''$2''' ka mga adlaw}}, sa taknang $5, $4.",
'rcshowhideminor' => '$1 menor nga pag-usab',
'rclinks'         => 'Ipakita ang miaging $1 ka kausaban sa miaging $2 ka mga adlaw<br />$3',
'diff'            => 'kalainan',
'hist'            => 'kaagi',
'hide'            => 'Tagoi',
'newpageletter'   => 'B',

# Recent changes linked
'recentchangeslinked'         => 'Mga may kalabotang kausaban',
'recentchangeslinked-title'   => 'Mga pag-usab nga may kalabotan sa "$1"',
'recentchangeslinked-summary' => "Kini ang talaan sa mga bag-ong kausaban sa mga panid nga misumpay sa espesipikong panid (o sa mga sakop sa espesipikong kategoriya).
Ang mga panid sa [[Special:Watchlist|imong gibantayan]] '''nakalugom'''.",

# Upload
'upload' => 'Pagsumiter og payl',

# File description page
'filehist'          => 'Kaagi sa payl',
'filehist-help'     => 'I-klik ang petsa/oras aron makit-an ang hulagway sa payl niadtong panahona.',
'filehist-current'  => 'kasamtangan',
'filehist-datetime' => 'Petsa/Takna',
'filehist-user'     => 'Tiggamit',
'filehist-comment'  => 'Komento',
'imagelinks'        => 'Mga sumpay',
'linkstoimage'      => 'Ang mosunod nga {{PLURAL:$1|mga panid misumpay|$1 panid misumpay}} niining payl:',
'sharedupload'      => 'Ang kining payl usa ka shared upload ug mahimong gigamit sa ubang mga proyekto.', # $1 is the repo name, $2 is shareduploadwiki(-desc)

# Random page
'randompage' => 'Bisan unsang panid',

# Miscellaneous special pages
'nmembers' => '$1 {{PLURAL:$1|sakop|mga sakop}}',
'move'     => 'Ibalhin',

# Special:AllPages
'alphaindexline' => '$1 hangtod $2',
'allpagessubmit' => 'Sige',

# Special:Categories
'categories'                  => 'Mga kategoriya',
'categoriespagetext'          => 'Ang mosunod nga mga kategoriya adunay sulod nga panid o medya.',
'special-categories-sort-abc' => 'han-aya nga paalpabetikal',

# Special:Log/newusers
'newuserlogpagetext'       => "Kini mao ang ''log'' sa bag-ong namugnang mga gumagamit.",
'newuserlog-byemail'       => "ang pasword gipadala na pinaagi sa ''e-mail''",
'newuserlog-create-entry'  => 'Bag-ong gumagamit',
'newuserlog-create2-entry' => "naghimo'g akawnt alang kang $1",

# Watchlist
'mywatchlist' => 'Akong gibantayan',
'watch'       => 'Bantayi',
'unwatch'     => 'Pasagdi',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Gibantayan...',
'unwatching' => 'Gipasagdan...',

# Delete
'deletedarticle' => 'gitangtang "[[$1]]"',

# Contributions
'contributions' => 'Mga tampo ning gumagamit',
'mycontris'     => 'Akong tampo',

# What links here
'whatlinkshere'       => 'Unsay mga misumpay dinhi',
'whatlinkshere-title' => 'Mga panid nga misumpay ngadto sa "$1"',
'linkshere'           => "Ang mosunod nga mga panid misumpay sa '''[[:$1]]''':",
'isredirect'          => 'panid sa redirekta',
'whatlinkshere-prev'  => '{{PLURAL:$1|miaging|miaging $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|sunod|sunod $1}}',
'whatlinkshere-links' => '← mga sumpay',

# Block/unblock
'contribslink' => 'mga tampo',

# Thumbnails
'thumbnail-more' => 'Padak-a',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Akong panid',
'tooltip-pt-mytalk'               => 'Akong hisgot',
'tooltip-pt-preferences'          => 'Akong mga preperensiya',
'tooltip-pt-watchlist'            => 'Talaan sa mga panid nga imong gibantayan ang mga pag-usab',
'tooltip-pt-mycontris'            => 'Akong mga tampo',
'tooltip-pt-login'                => "Gihangyo ka namo sa pag-''log-in'', apan wala kini gikinahanglan aron makausab ka sa mga panid.",
'tooltip-pt-logout'               => 'Biya',
'tooltip-ca-talk'                 => 'Panaghisgot kabahin sa panid',
'tooltip-ca-edit'                 => "Mahimo mong usbon ang kining panid. Palihog gamita ang ''Paunang tan-aw'' nga tuplokanan bag-o nimotipigan ang panid.",
'tooltip-ca-addsection'           => 'Pagdugang og komento niining panaghisgot-hisgot.',
'tooltip-ca-viewsource'           => 'Giprotektahan kining panid.
Pwede nimong tan-awon ang ginikanan.',
'tooltip-ca-move'                 => 'Ibalhin kini nga panid',
'tooltip-ca-watch'                => 'Idugang kining panid sa imong gibantayan',
'tooltip-search'                  => 'Pangitaa {{SITENAME}}',
'tooltip-n-mainpage'              => 'Bisitaha ang Unang Panid',
'tooltip-n-portal'                => 'Kabahin sa proyekto, unsay imong mahimo, asa mangita sa mga impormasyon',
'tooltip-n-currentevents'         => 'Pangita og nahaunang impormasyon sa mga bag-ong panghitabo',
'tooltip-n-recentchanges'         => 'Ang talaan sa mga bag-ong giusab sa wiki.',
'tooltip-n-randompage'            => 'Pag-abli og bisan unsang panid',
'tooltip-n-help'                  => 'Ang dapit nga angay mong pangitaan.',
'tooltip-t-whatlinkshere'         => 'Talaan sa mga wiki nga panid nga misumpay dinhi',
'tooltip-t-upload'                => 'Pagsumiter og mga payl',
'tooltip-t-specialpages'          => 'Talaan sa mga espesyal nga panid',
'tooltip-ca-nstab-image'          => 'Tan-awa ang panid sa payl',
'tooltip-ca-nstab-category'       => 'Tan-awa ang panid sa kategoriya',
'tooltip-save'                    => 'I-save ang imong gipang-usab',
'tooltip-preview'                 => 'Paunang tan-aw sa imong mga pag-usab, palihog gamita kini usa tipigi ang panid!',
'tooltip-diff'                    => 'Ipakita asa ang imong giusab sa teksto.',
'tooltip-compareselectedversions' => 'Tan-awa ang mga kalainan sa duhang gipiling bersiyon niining panid.',

# Metadata
'metadata-expand' => 'Ipakita ang mas daghang detalye',

# External editor support
'edit-externally'      => 'Usba kining payl gamit ang eksternal nga aplikasyon',
'edit-externally-help' => '(Tan-awa ang [http://www.mediawiki.org/wiki/Manual:External_editors setup instructions] para sa dugang nga impormasyon)',

# 'all' in various places, this might be different for inflected languages
'namespacesall' => 'tanan',

# Special:SpecialPages
'specialpages' => 'Espesyal nga mga panid',

);
