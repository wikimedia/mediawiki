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
'tog-underline'               => 'Binadlisan nga mga sumpay:',
'tog-highlightbroken'         => 'Ipormat ang mga buak nga sumpay <a href="" class="new">susama niini</a> (alternatibo: susama niini<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Ihusto ang mga parapo',
'tog-hideminor'               => 'Ipakita ang gamayng pag-usab sa mga bag-ong giusab',
'tog-hidepatrolled'           => 'Tagoa ang mga napatrol nga pag-usab sa mga bag-ong giusab',
'tog-newpageshidepatrolled'   => 'Tagoa ang napatrol nga mga panid gikan sa talaan sa mga bag-ong panid',
'tog-extendwatchlist'         => 'Padak-a ang gibantayan aron mapakita ang tanang kausaban, dili lang ang labing bag-o',
'tog-usenewrc'                => 'Gamita ang na-enhance nga mga bag-ong giusab (JavaScript kinahanglan)',
'tog-numberheadings'          => 'Awtomatiko ang pagnumero sa mga heading',
'tog-showtoolbar'             => 'Ipakita ang toolbar sa pag-edit (JavaScript)',
'tog-editondblclick'          => 'I-edit ang panid inig dobol-klik (JavaScript)',
'tog-editsection'             => 'Mausab ang mga seksiyon gamit ang [usba] nga sumpay',
'tog-editsectiononrightclick' => 'Mahimo ang pag-usab sa seksyon pinaagi sa pag-right click sa titulo sa seksyon (JavaScript kinahanglan)',
'tog-showtoc'                 => 'Ipakita ang talaan sa sulod (alang sa mga panid nga may daghan pa sa 3 ka heading)',
'tog-rememberpassword'        => 'Hinumdomi ako sa kining kompyuter',
'tog-watchcreations'          => 'Bantayi ang akong gisugdang mga panid',
'tog-watchdefault'            => 'Bantayi ang akong giusab nga mga panid',
'tog-watchmoves'              => 'Bantayi ang akong gibalhin nga mga panid',
'tog-watchdeletion'           => 'Bantayi ang mga panid nga akong gipapas',
'tog-previewontop'            => 'Ipakita ang paunang tan-aw bag-o sa edit box',
'tog-previewonfirst'          => 'Ipakita ang paunang tan-aw sa unang pag-usab',
'tog-nocache'                 => 'Ayaw i-cache ang panid',
'tog-enotifwatchlistpages'    => 'I-email ko kon ang panid nga akong gibantayan giusab.',
'tog-enotifusertalkpages'     => 'I-email ko kon nausab ang akong panid sa panaghisgot',
'tog-enotifminoredits'        => 'I-email ko alang sa mga ginagmay nga pag-usab',
'tog-enotifrevealaddr'        => 'Ibandilyo ang akong adress sa e-mail sa mga e-mail nga magbalita',
'tog-shownumberswatching'     => 'Ipakita ang gidaghanon sa mga gumagamit nga nagbantay usab',
'tog-oldsig'                  => 'Paunang tan-aw sa eksisting nga pirma:',
'tog-fancysig'                => 'Hilaw nga pirma (walay awtomatikong sumpay)',
'tog-externaleditor'          => 'Gamita ang eksternal nga editor isip default (para sa mga eksperto lamang, kinahanglan og espesyal nga setting sa imong kompyuter)',
'tog-externaldiff'            => 'Gamita ang eksternal nga diff isip default (para sa mga eksperto lamang, kinahanglan og espesyal nga setting sa imong kompyuter)',
'tog-showjumplinks'           => 'I-enable ang "ambak sa" nga sumpay sa aksesibilidad',
'tog-uselivepreview'          => 'Gamita ang live nga paunang tan-aw (JavaScript kinahanglan) (Eksperimental)',
'tog-forceeditsummary'        => 'Pahibaloi ako kon blangko ang mubong sugid alang sa pag-usab',
'tog-watchlisthideown'        => 'Tagoa ang akong mga giusab',
'tog-watchlisthidebots'       => 'Tagoa ang mga giusab sa bot',
'tog-watchlisthideminor'      => 'Tagoa ang mga gagmay nga pag-usab',
'tog-watchlisthideliu'        => 'Tagoa ang mga pag-usab sa mga gumagamit nga naka-log-in gikan sa gibantayan',
'tog-watchlisthideanons'      => 'Tagoa ang mga pag-usab sa mga wala mailhing gumagamit gikan sa gibantayan',
'tog-watchlisthidepatrolled'  => 'Tagoa ang mga napatrol nga pag-usab gikan sa gibantayan',
'tog-ccmeonemails'            => 'Padalhi ko og kopya sa mga e-mail nga gipadala nako sa ubang gumagamit',
'tog-diffonly'                => 'Ayaw ipakita ang sulod sa panid ubos sa diffs',
'tog-showhiddencats'          => 'Ipakita ang nakatagong mga kategoriya',
'tog-norollbackdiff'          => 'Tangtanga ang diff human mag-rollback',

'underline-always'  => 'Kanunay',
'underline-never'   => 'Ayaw',
'underline-default' => 'Default sa brawser',

# Font style option in Special:Preferences
'editfont-style'     => 'Font style sa edit area:',
'editfont-default'   => 'Default sa brawser',
'editfont-monospace' => 'Monospaced font',
'editfont-sansserif' => 'Sans-serif font',
'editfont-serif'     => 'Serif font',

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
'mar'           => 'Mar',
'apr'           => 'Abr',
'may'           => 'Mayo',
'jun'           => 'Hun',
'jul'           => 'Hul',
'aug'           => 'Ago',
'sep'           => 'Sep',
'oct'           => 'Okt',
'nov'           => 'Nob',
'dec'           => 'Dis',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategoriya|Mga kategoriya}}',
'category_header'                => 'Mga panid sa kategoriyang "$1"',
'subcategories'                  => 'Mga subkategoriya',
'category-media-header'          => 'Medya sa kategoriyang "$1"',
'category-empty'                 => "''Kini nga kategoriya kasamtangang way sulod nga mga panid ug mga medya.''",
'hidden-categories'              => '{{PLURAL:$1|Nakatagong kategoriya|Mga nakatagong kategoriya}}',
'hidden-category-category'       => 'Mga nakatagong kategoriya',
'category-subcat-count'          => '{{PLURAL:$2|Kini nga kategoriya may usa lamang ka subkategoriya.|Kini nga kategoriya may {{PLURAL:$1|subkategoriya|$1 ka mga subkategorya}}, sa total nga $2.}}',
'category-subcat-count-limited'  => 'Kini nga kategoriya adunay {{PLURAL:$1|ka subkategorya|$1 ka mga subkategoriya}}.',
'category-article-count'         => '{{PLURAL:$2|Kini nga kategoriya may usa lang ka panid.|Ang kining {{PLURAL:$1|ka panid|$1 ka mga panid}} nahiapil niining kategoryaha, sa $2 nga total.}}',
'category-article-count-limited' => 'Ang mosunod nga {{PLURAL:$1|panid |$1 mga panid}} anaa sa kasamtangang kategoriya.',
'category-file-count'            => '{{PLURAL:$2|Ang kini nga kategoriya may usa lamang ka payl.|Ang mosunod nga {{PLURAL:$1|payl|$1 mga payl}} anaa niining kategoriya, sa $2 nga total.}}',
'category-file-count-limited'    => 'Ang mosunod nga {{PLURAL:$1|payl|$1 ka mga payl}} anaa niining kategoryaha.',
'listingcontinuesabbrev'         => 'pad.',

'mainpagetext'      => "'''Malamposon ang pag-instalar sa MediaWiki.'''",
'mainpagedocfooter' => 'Konsultaha ang [http://meta.wikimedia.org/wiki/Help:Contents Giya sa mga gumagamit] alang sa impormasyon unsaon paggamit niining wiki nga software.

== Pagsugod ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Listahan sa mga setting sa kompigurasyon]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ sa MediaWiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce  Mailing list sa mga release sa MediaWiki]',

'about'         => 'Mahitungod',
'article'       => 'Panid sa sulod',
'newwindow'     => "(maabli sa laing ''window'')",
'cancel'        => 'I-way bili',
'moredotdotdot' => 'Dugang pa...',
'mypage'        => 'Akong panid',
'mytalk'        => 'Akong hisgot',
'anontalk'      => 'Panghisgot-hisgot alang niining IP',
'navigation'    => 'Tabok-tabok',
'and'           => '&#32;ug',

# Cologne Blue skin
'qbfind'         => 'Pangitaa',
'qbbrowse'       => 'Browse',
'qbedit'         => 'Usba',
'qbpageoptions'  => 'Kini nga panid',
'qbpageinfo'     => 'Konteksto',
'qbmyoptions'    => 'Akong mga panid',
'qbspecialpages' => 'Mga espesyal nga panid',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'   => 'Pagdugang og topiko',
'vector-action-delete'       => 'Papasa',
'vector-action-move'         => 'Ibalhin',
'vector-action-protect'      => 'Protektahi',
'vector-action-undelete'     => 'Ayaw papasa',
'vector-action-unprotect'    => 'Ayaw protektahi',
'vector-namespace-category'  => 'Kategoriya',
'vector-namespace-help'      => 'Panid sa tabang',
'vector-namespace-image'     => 'Payl',
'vector-namespace-main'      => 'Panid',
'vector-namespace-media'     => 'Panid sa medya',
'vector-namespace-mediawiki' => 'Mensahe',
'vector-namespace-project'   => 'Panid sa proyekto',
'vector-namespace-special'   => 'Espesyal nga panid',
'vector-namespace-talk'      => 'Panaghisgot-hisgot',
'vector-namespace-template'  => 'Plantilya',
'vector-namespace-user'      => 'Panid sa tiggamit',
'vector-view-create'         => 'Himoa',
'vector-view-edit'           => 'Usba',
'vector-view-history'        => 'Tan-awa ang kaagi',
'vector-view-view'           => 'Basaha',
'vector-view-viewsource'     => 'Tan-awa ang ginikanan',
'actions'                    => 'Mga lihok',
'namespaces'                 => 'Mga ngalang espasyo',
'variants'                   => 'Mga baryant',

'errorpagetitle'    => 'Sayop',
'returnto'          => 'Balik sa $1.',
'tagline'           => 'Gikan sa {{SITENAME}}',
'help'              => 'Tabang',
'search'            => 'Pangita',
'searchbutton'      => 'Pangitaa',
'go'                => 'Sige',
'searcharticle'     => 'Sige',
'history'           => 'Kaagi ning panid',
'history_short'     => 'Kaagi',
'updatedmarker'     => 'na-update sugod sa akong kataposang bisita',
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
'protect_change'    => 'usba',
'protectthispage'   => 'Protektahi kining panid',
'unprotect'         => 'Ayaw protektahi',
'unprotectthispage' => 'Ayaw na kini protektahi',
'newpage'           => 'Bag-ong panid',
'talkpage'          => 'Hisgoti kining panid',
'talkpagelinktext'  => 'Hisgot',
'specialpage'       => 'Espesyal nga panid',
'personaltools'     => 'Personal nga galamiton',
'postcomment'       => 'Pagbilin og komento',
'articlepage'       => 'Tan-awa ang panid sa sulod',
'talk'              => 'Panaghisgot-hisgot',
'views'             => 'Mga pagtan-aw',
'toolbox'           => 'Galamiton',
'userpage'          => 'Tan-awa ang panid sa gumagamit',
'projectpage'       => 'Tan-awa ang panid sa proyekto',
'imagepage'         => 'Tan-awa ang panid sa medya',
'mediawikipage'     => 'Tan-awa ang panid sa mensahe',
'templatepage'      => 'Tan-awa ang panid sa plantilya',
'viewhelppage'      => 'Tan-awa ang panid sa tabang',
'categorypage'      => 'Tan-awa ang panid sa kategoriya',
'viewtalkpage'      => 'Tan-awa ang panaghisgot-hisgot',
'otherlanguages'    => 'Sa ubang pinulongan',
'redirectedfrom'    => '(Naredirek gikan sa $1)',
'redirectpagesub'   => 'Panid sa redirekta',
'lastmodifiedat'    => 'Kini nga panid kataposang giusab niadtong $2, $1.',
'viewcount'         => 'Naablihan na sa {{PLURAL:$1|maka-usa|$1 ka higayon}} ang kining panid.',
'protectedpage'     => 'Giprotektahang panid',
'jumpto'            => 'Ambak sa:',
'jumptonavigation'  => 'tabok-tabok',
'jumptosearch'      => 'pangita',
'view-pool-error'   => 'Pasensya, overloaded ang mga serber sa kasamtangan.
Sobra ka daghang gumagamit ang misulay og tan-aw niining panid.
Palihog paghulat bag-o nimo sulayan pag-akses og usab ang kining panid.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Mahitungod sa {{SITENAME}}',
'aboutpage'            => 'Project:Mahitungod sa',
'copyright'            => 'Mga sulod makita sa $1.',
'copyrightpage'        => '{{ns:project}}:Mga katungod sa pagpatik',
'currentevents'        => 'Mga bag-ong panghitabo',
'currentevents-url'    => 'Project:Kasamtangang panghitabo',
'disclaimers'          => 'Mga pagpasabot',
'disclaimerpage'       => 'Project:Mga pagpasabot',
'edithelp'             => 'Tabang sa pag-usab',
'edithelppage'         => 'Help:Pag-usab',
'helppage'             => 'Help:Mga sulod',
'mainpage'             => 'Unang Panid',
'mainpage-description' => 'Unang Panid',
'policy-url'           => 'Project:Palisiya',
'portal'               => 'Tubaan',
'portal-url'           => 'Project:Ganghaan sa Komunidad',
'privacy'              => 'Palisiya sa personal nga impormasyon',
'privacypage'          => 'Project:Palisiya sa pribasidad',

'badaccess'        => 'Sayop sa permiso',
'badaccess-group0' => 'Wala ikaw tugoti sa pagpadayon sa aksyon nga imong gipangayo.',
'badaccess-groups' => 'Ang aksyon nga imong gipangayo mahimo lamang ihatag sa mga miyembro sa mga grupong $1.',

'versionrequired'     => 'Gikinahanglan ang Bersyong $1 sa MediaWiki',
'versionrequiredtext' => 'Gikinahanglan ang Bersyong $1 sa MediaWiki aron magamit kining panid. Tan-awa ang [[Special:Version|panid sa bersyon]].',

'ok'                      => 'OK',
'retrievedfrom'           => 'Gikuha gikan sa "$1"',
'youhavenewmessages'      => 'Aduna kay $1 ($2).',
'newmessageslink'         => 'bag-ong mensahe',
'newmessagesdifflink'     => 'ulahing pag-usab',
'youhavenewmessagesmulti' => 'Adunay kay bag-ong mensahe sa $1',
'editsection'             => 'usba',
'editold'                 => 'usba',
'viewsourceold'           => 'tan-awa ang ginikanan',
'editlink'                => 'usba',
'viewsourcelink'          => 'tan-awa ang ginikanan',
'editsectionhint'         => 'Usba ang seksyong: $1',
'toc'                     => 'Mga sulod',
'showtoc'                 => 'ipakita',
'hidetoc'                 => 'tagoa',
'thisisdeleted'           => 'Ipakita o ibalik ang $1?',
'viewdeleted'             => 'Ipakita ang $1?',
'restorelink'             => '{{PLURAL:$1|usa ka napapas nga pag-usab|$1 mga napapas nga pag-usab}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Dili sakto ang klase sa subscription feed',
'feed-unavailable'        => "Wala tugoti ang mga ''Syndication Feeds'' sa {{SITENAME}}",
'site-rss-feed'           => '$1 Feed sa RSS',
'site-atom-feed'          => '$1 Feed sa Atom',
'page-rss-feed'           => '"$1" Feed nga RSS',
'page-atom-feed'          => '"$1" Feed nga Atom',
'red-link-title'          => '$1 (wala pa masulat)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artikulo',
'nstab-user'      => 'Panid sa tiggamit',
'nstab-media'     => 'Panid sa medya',
'nstab-special'   => 'Espesyal nga panid',
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
'nospecialpagetext' => '<strong>Mihangyo ikaw sa inbalidong espesyal nga panid.</strong>

Ang lista sa mga balidong espesyal nga mga panid makita sa [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Sayop',
'databaseerror'        => 'Sayop sa database',
'dberrortext'          => 'May nahitabong sayop sa database query syntax.
Mahimong nagpakita kini og bug sa software.
Ang naulahing gi-attempt nga database query mao ang:
<blockquote><tt>$1</tt></blockquote>
from within function "<tt>$2</tt>".
MySQL returned error "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'May nahitabong sayop sa database query syntax.
Ang naulahing gi-attempt nga database query mao ang:
"$1"
from within function "$2".
MySQL returned error "$3: $4"',
'laggedslavemode'      => 'Pahibalo: Mahimong dili mahiapil sa panid ang mga bag-ong kausaban.',
'readonly'             => 'Gitrangkahan ang database',
'enterlockreason'      => 'Pagbutang og rason para sa lock, apil ang banabana kon kanus-a ma-release ang lock',
'readonlytext'         => 'Kasamtangang naka-lock ang database sa mga bag-ong entrada ug uban pang modipikasyon, siguro tungod sa routine nga pag-meynteyn sa database, human niini mobalik na kini sa normal.

Ang tagdumala nga nag-lock niini mihatag niining eksplanasyon: $1',
'missing-article'      => 'Ang database wala makakita sa teksto sa panid nga unta nakit-an niini, gipangalana\'g "$1" $2.

Kasagaran nahitabo kini tungod sa pagsunod og sumpay nga dugay na nga diff o kaagi sa panid nga natangtang na.

Kon dili kini ang kaso, mamahimong nakatagboka og bug sa software.
Palihog ireport kini sa usa ka [[Special:ListUsers/sysop|administrador]], hinumdomi ang URL.',
'missingarticle-rev'   => '(rebisyon#: $1)',
'missingarticle-diff'  => '(Diff: $1, $2)',
'readonly_lag'         => 'Ang database awtomatikong na-lock samtang ang mga slave database server mo-catch up sa master',
'internalerror'        => 'Internal nga sayop',
'internalerror_info'   => 'Internal nga sayop: $1',
'fileappenderror'      => 'Dili ma-append ang "$1" ngadto sa "$2".',
'filecopyerror'        => 'Dili makopya ang payl nga "$1" ngadto sa "$2".',
'filerenameerror'      => 'Dili mailisag ngalan ang payl "$1" ngadto sa "$2".',
'filedeleteerror'      => 'Dili mapapas ang payl "$1".',
'directorycreateerror' => 'Dili makahimo og direktoryo nga "$1".',
'filenotfound'         => 'Dili makita ang payl nga "$1".',
'fileexistserror'      => 'Dili makasulat sa payl nga "$1": anaa na ang payl',
'unexpected'           => 'Wala gi-ekspek nga value: "$1"="$2".',
'formerror'            => 'Sayop: dili masumiter ang porma',
'badarticleerror'      => 'Ang kining lihok dili puyde mahitabo sa kining panid.',
'cannotdelete'         => 'Dili makapapas sa mao mismong panid o payl.
Puyde kini gipapas na sa uban.',
'badtitle'             => 'Bati nga titulo',
'badtitletext'         => 'Ang gihangyong titulo sa panid mahimong inbalido, walay sulod, o nasayop og sumpay nga inter-pinulongan o inter-wiki nga titulo.
Basin aduna kini usa o daghan pang mga karakter nga dili magamit isip titulo.',
'perfcached'           => 'Ang mosunod nga data naka-cache ug mahimong dili ang labing bag-o.',
'perfcachedts'         => 'Ang mosunod nga data naka-cache, ug kataposang nabag-o sa $1.',
'querypage-no-updates' => 'Ang mga update alang sa kining panid naka-disable sa kasamtangan.
Dili karon dayon ma-refresh ang data dinhi.',
'wrong_wfQuery_params' => 'Sayop nga mga parametro sa wfQuery()<br />
Function: $1<br />
Query: $2',
'viewsource'           => 'Tan-awa ang ginikanan',
'viewsourcefor'        => 'para kang $1',
'actionthrottled'      => 'Na-throttle ang lihok',
'actionthrottledtext'  => "Isip anti-spam, gilimitahan ka sa sinagunson nga paghimo niining lihok sa mubong panahon lang, ikaw misobra na sa maong limit.
Palihog sulayi'g usab sa pipila ka minutos.",
'protectedpagetext'    => 'Naka-lock ang kining panid aron mapugngan ang pag-usab.',
'viewsourcetext'       => 'Puyde nimo tan-awon ug kopyahon ang ginikanan ning panid:',
'protectedinterface'   => 'Ang kining panid mohatag og interface text para sa software, ug naka-lock aron mapugngan ang pag-abuso.',
'editinginterface'     => "'''Pahibalo:''' Imo nang usbon ang panid nga gigamit sa paghatag og interface text para sa software.
Ang mga pag-usab niining panid moapekto sa appearance sa user interface nga alang sa ubang gumagamit.
Para sa mga paghubad, palihog ikonsider ang paggamit sa [http://translatewiki.net/wiki/Main_Page?setlang=ceb translatewiki.net], ang MediaWiki localisation project.",
'sqlhidden'            => '(nakatagong SQL query)',
'cascadeprotected'     => 'Ang kining panid giprotektahan sa pag-usab tungod kay nahiapil kini sa mosunod nga {{PLURAL:$1|panid, nga|mga panid, nga}} giprotektahan pinaagi sa pag-turn on gamit ang "cascading" nga opsyon:
$2',
'namespaceprotected'   => "Wala kay permiso nga mag-usab sa mga panid sa '''$1''' nga ngalang espasyo.",
'customcssjsprotected' => 'Wala kay permiso nga usbon ang kining panid, tungod kay may sulod kini nga personal nga mga setting sa laing gumagamit.',
'ns-specialprotected'  => 'Ang mga espesyal nga panid dili mausban.',
'titleprotected'       => 'Ang kining titulo giprotektahan sa paghimo ni [[User:$1|$1]].
Ang rason nga gihatag mao ang "\'\'$2\'\'".',

# Virus scanner
'virus-badscanner'     => "Daot nga kompigurasyon: wala mailhing virus scanner: ''$1''",
'virus-scanfailed'     => 'scan failed (code $1)',
'virus-unknownscanner' => 'wala mailhing antivirus:',

# Login and logout pages
'logouttext'              => "'''Nakabiya ka na.'''

Mahimo kang magpadayon sa paggamit sa {{SITENAME}} bisan wala ka magpaila o puyde usab nga [[Special:UserLogin|mag-log in ka'g usab]] o isip laing gumagamit. Palihog hinumdomi nga may ubang mga panid nga magpakita sama nga ikaw naka-log in pa; kini tungod kay wala pa nimo malimpiyohi ang cache sa imong brawser.",
'welcomecreation'         => '== Maayong pag-abot, $1! ==
Nahimo na ang imong akawnt.
Ayaw kalimot sa pag-usab sa imong [[Special:Preferences|{{SITENAME}} mga preperensiya]].',
'yourname'                => 'Ngalan sa tiggamit:',
'yourpassword'            => 'Pasword:',
'yourpasswordagain'       => 'Itayp og usab ang pasword:',
'remembermypassword'      => 'Hinumdomi ako niini nga kompyuter',
'yourdomainname'          => 'Ang imong domain:',
'externaldberror'         => 'May nahitabong authentication database error o kaha wala ka tugoti nga mag-update sa imong eksternal nga akawnt.',
'login'                   => 'Sulod',
'nav-login-createaccount' => 'Rehistro / Dayon',
'loginprompt'             => 'Kinahanglang naka-enable ang mga koki aron ikaw maka-log-in sa {{SITENAME}}.',
'userlogin'               => 'Rehistro / Dayon',
'logout'                  => 'Biya',
'userlogout'              => 'Biya',
'notloggedin'             => 'Wala ka pa masulod',
'nologin'                 => "Wala pay akawnt? '''$1'''.",
'nologinlink'             => 'Paghimo og akawnt',
'createaccount'           => "Paghimo'g akawnt",
'gotaccount'              => "Naa ka nay akawnt? '''$1'''.",
'gotaccountlink'          => 'Dayon',
'createaccountmail'       => 'sa e-mail',
'badretype'               => 'Ang mga pasword nga imong gientra wala mag-match.',
'userexists'              => 'Ang ngalan sa tiggamit nga imong gisulat nagamit na.
Palihug pagpili og lain nga ngalan.',
'loginerror'              => 'Sayop sa pagdayon',
'nocookiesnew'            => 'Ang akawnt sa gumagamit nahimo na, pero wala ka pa ma-log-in.
Ang {{SITENAME}} migamit og mga koki aron ma-log in ang mga gumagamit.
Naka-disable ang imong mga koki.
Palihog i-enable kini, unya pag-log-in gamit ang imong bag-ong username ug pasword.',
'nocookieslogin'          => "Ang {{SITENAME}} migamit og mga koki aron ma-log in ang mga gumagamit.
Naka-disable ang imong mga koki.
Palihog i-enable kini, ug sulayi'g balik.",
'noname'                  => 'Wala ikaw mag-specify og valid nga user name.',
'loginsuccesstitle'       => 'Malamposon ang pagpaila',
'loginsuccess'            => "'''Nailhan ka na sa {{SITENAME}} isip \"\$1\".'''",
'nosuchuser'              => 'Walay gumagamit nga may pangalang "$1".
Case sensitive ang mga user name.
I-tsek ang imong espeling, o [[Special:UserLogin/signup|paghimo og bag-ong akawnt]].',
'nosuchusershort'         => 'Walay gumagamit nga may pangalang "<nowiki>$1</nowiki>".
I-tsek ang imong espeling.',
'nouserspecified'         => 'Kinahanglan mag-specify ka og username.',
'wrongpassword'           => "Sayop nga pasword ang naentra.
Palihog sulayi'g usab.",
'mailmypassword'          => 'I-email ang bag-ong pasword',
'loginlanguagelabel'      => 'Pinulongan: $1',

# Edit page toolbar
'bold_sample'     => 'Gilugom nga teksto',
'bold_tip'        => 'Gilugom nga teksto',
'italic_sample'   => 'Gitakilid nga teksto',
'italic_tip'      => 'Gitakilid nga teksto',
'link_sample'     => 'Titulo sa sumpay',
'link_tip'        => 'Sumpay nga internal',
'extlink_sample'  => 'http://www.example.com titulo sa sumpay',
'extlink_tip'     => 'Sumpay sa gawas (hinumdomi http:// prefix)',
'headline_sample' => 'Teksto sa hedlayn',
'headline_tip'    => 'Level 2 nga hedlayn',
'math_sample'     => 'I-insert dinhi ang formula',
'math_tip'        => 'Mathematical formula (LaTeX)',
'nowiki_sample'   => 'Dinhi ang dili-pormaton nga teksto',
'nowiki_tip'      => 'Dili i-wikipormat',
'image_tip'       => 'Embedded nga payl',
'media_tip'       => 'Sumpay sa payl',
'sig_tip'         => 'Ang imong pirma uban ang takna',
'hr_tip'          => 'Pahigda nga linya (palihog usahay ra gamita)',

# Edit pages
'summary'                          => 'Mubong sugid:',
'subject'                          => 'Sabdyek/hedlayn:',
'minoredit'                        => 'Ginagmay lang nga kausaban',
'watchthis'                        => 'Bantayi kining maong panid',
'savearticle'                      => 'Tipigi ang panid',
'preview'                          => 'Paunang tan-aw',
'showpreview'                      => 'Paunang tan-aw',
'showdiff'                         => 'Ipakita ang kalainan',
'anoneditwarning'                  => "'''Pahibalo:''' Wala ikaw maka-login.
Ang imong ''IP address'' maoy itala sa kaagi niini nga panid.",
'summary-preview'                  => 'Paunang tan-aw sa mubong sugid:',
'newarticle'                       => '(Bag-o)',
'newarticletext'                   => 'Mitulpok ka sa sumpay ngadto sa usa ka wala pa masulat nga panid.
Aron mahimo ang maong panid, pagtayp sa kahon sa ubos (tan-awa ang [[{{MediaWiki:Helppage}}|panid sa tabang]] alang sa dugang impormasyon).
Kon miabot ka dinhi pinaagi sa usa ka sayop, palihog tuploka ang back nga tuplokanan sa imong brawser.',
'noarticletext'                    => 'Sa kasamtangan walay sulod nga teksto ang kining panid.
Puyde nimong  [[Special:Search/{{PAGENAME}}|pangitaon kining titulo sa panid]] sa ubang mga panid, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} pangitaa ang related nga mga log],
o [{{fullurl:{{FULLPAGENAME}}|action=edit}} usba kining panid].',
'userpage-userdoesnotexist'        => 'Ang akawnt sa tiggamit nga "$1" wala marehistro. Palihug tan-awa kon buot nimong himoon/usbon ang kining panid.',
'previewnote'                      => "'''Hinumdomi nga kini usa lang ka paunang tan-aw; wala pa matipigi ang imong giusab!'''",
'editing'                          => 'Nagausab sa $1',
'editingsection'                   => 'Nagausab sa $1 (bahin)',
'yourtext'                         => 'Imong gisulat',
'yourdiff'                         => 'Mga kalainan',
'copyrightwarning'                 => "Palihog hinumdomi nga ang tanang kontribusyon sa {{SITENAME}} giisip nga ubos sa $2 (basaha ang $1 alang sa dugang detalye). Kon dili nimo buot nga ang imong mga sinulat mausab ni bisan kinsa ug maapud-apod bisan dili ka pangayoan og pagtugot, ayaw sila ibutang dinhi.<br />
Nagatimaan ka usab nga ikaw mismo ang nagsulat niini, o gikopya nimo kini gikan sa usa ka publikong rekursos o susamang libreng rekursos.
'''AYAW PAGBUTANG DINHI OG MGA BINUHAT NGA MAY NANAG-IYA SA KATUNGOD SA PAGPATIK NGA WA KAY PERMISO!'''",
'templatesused'                    => 'Ang mga plantilyang gigamit niini nga panid:',
'templatesusedpreview'             => 'Mga plantilyang gigamit niining paunang tan-aw:',
'template-protected'               => '(giprotektahan)',
'template-semiprotected'           => '(medyo giprotektahan)',
'hiddencategories'                 => 'Ang kining panid nahiapil sa {{PLURAL:$1|1 ka kategoriya nga nakatago|$1 ka mga kategoriya nga nakatago}}:',
'permissionserrorstext-withaction' => 'Wala kay permiso nga $2, tungod sa mosunod nga {{PLURAL:$1|rason|mga rason}}:',
'edit-conflict'                    => 'Conflict sa pag-usab.',
'edit-no-change'                   => 'Na-ignor ang imong pag-usab, tungod kay walay kausaban sa teksto.',
'edit-already-exists'              => 'Dili makahimo og bag-ong panid.
Anaa na kini.',

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''Pahibalo:''' Ang kining panid adunay daghan kaayong expensive parser function calls.

Kinahanglan mas ubos sa $2 nga {{PLURAL:$2|call|calls}}, there {{PLURAL:$1|is now $1 call|are now $1 calls}}.",
'expensive-parserfunction-category'       => 'Mga panid nga may daghan kaayong expensive parser function calls',
'post-expand-template-inclusion-warning'  => "'''Pahibalo:''' Ang size sa plantilya nga nahiapil dako kaayo.
May ubang plantilya nga dili maapil.",
'post-expand-template-inclusion-category' => 'Mga panid nga ang size sa giapil nga plantilya nasobrahan',
'post-expand-template-argument-warning'   => "'''Pahibalo:''' Ang kining panid may usa o daghan pa nga argumento sa plantilya nga dako kaayo ang expansion size.
Ang maong mga argumento natangtang.",
'post-expand-template-argument-category'  => 'Mga panid nga may natangtang nga mga argumento sa plantilya',
'parser-template-loop-warning'            => 'Nadetek ang loop sa plantilya: [[$1]]',
'parser-template-recursion-depth-warning' => 'Nasobrahan na ang limit sa template recursion depth ($1)',

# "Undo" feature
'undo-success' => 'Ang pag-usab puyde iway-bili.
Palihog og tsek sa komparison sa ubos aron ma-tsek nga kini gyud ang imong gustong mahimo, ug unya tipigi ang mga pag-usab sa ubos aron mahuman ang pag-way-bili sa pag-usab.',
'undo-failure' => 'Ang pag-usab dili puyde mapa-way-bili tungod sa mga naka-conflict nga intermediate nga mga pag-usab.',
'undo-norev'   => 'Ang pag-usab dili puyde mapa-way-bili tungod kay wala pa ni mahimo o kaha natangtang na kini.',
'undo-summary' => 'Giway-bili  ang rebisyon  $1 ni [[Special:Contributions/$2|$2]] ([[User talk:$2|Hisgot]])',

# Account creation failure
'cantcreateaccounttitle' => 'Dili makahimo og akawnt',
'cantcreateaccount-text' => "Ang paghimo og akawnt gikan niining IP address ('''$1''') gi-block ni [[User:$3|$3]].

Ang rason nga gihatag ni $3 mao nga ''$2''",

# History pages
'viewpagelogs'           => 'Tan-awa ang mga log niining panid',
'nohistory'              => 'Walay kaagi sa pag-usab niining panid.',
'currentrev'             => 'Kasamtangang rebisyon',
'currentrev-asof'        => 'Rebisyon sa kasamtangan sa taknang $1',
'revisionasof'           => 'Rebisyon niadtong $1',
'revision-info'          => 'Rebisyon sa $1 ni $2',
'previousrevision'       => '←Mas daang pag-usab',
'nextrevision'           => 'Mas bag-ong rebisyon →',
'currentrevisionlink'    => 'Kasamtangang rebisyon',
'cur'                    => 'kar',
'next'                   => 'sunod',
'last'                   => 'kataposan',
'page_first'             => 'nauna',
'page_last'              => 'kinaulhi',
'histlegend'             => "Pagpili sa kalainan: markahi ang mga radio box sa mga rebisyon aron makompara dayon tuploka ang enter o ang button sa ibabaw.<br />
Leyenda: '''({{int:cur}})''' = kalainan sa kasamtangang rebisyon,
'''({{int:last}})''' = kalainan sa miaging rebisyon, '''{{int:minoreditletter}}''' = menor nga pag-usab.",
'history-fieldset-title' => 'Tan-awa ang kaagi',
'histfirst'              => 'Kinaunahan',
'histlast'               => 'Labing bag-o',
'historysize'            => '({{PLURAL:$1|1 byte|$1 mga byte}})',
'historyempty'           => '(way sulod)',

# Revision feed
'history-feed-title'          => 'Kaagi sa  rebisyon',
'history-feed-description'    => 'Kaagi sa rebisyon para niining panid sa wiki',
'history-feed-item-nocomment' => '$1 sa $2',
'history-feed-empty'          => 'Ang gihangyong panid wala pa mahimo.
Puyde gitangtang kini sa wiki, o gialisdan ang ngalan.
Sulayi og [[Special:Search|pagpangita sa wiki]] para sa mga may kalabotang panid.',

# Revision deletion
'rev-deleted-comment'         => '(ang komento gitangtang)',
'rev-deleted-user'            => '(gitangtang ang username)',
'rev-deleted-event'           => '(lihok sa log gitangtang)',
'rev-deleted-text-permission' => "Ang rebisyon sa panid '''napapas'''.
Puyde nga mga mga detalye sa [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log sa pagpapas].",
'rev-deleted-text-unhide'     => "Ang rebisyon sa panid '''napapas'''.
Puyde nga may mga detalye sa [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log sa pagpapas].
Isip tagdumala, puyde ka pa gihapon [$1 motan-aw niining rebisyon] kon gusto nimo magpadayon.",
'rev-suppressed-text-unhide'  => "Ang rebisyon sa panid '''naka-suppressed'''.
Puyde nga may mga detalye sa [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} log sa supresyon].
Isip tagdumala, puyde ka pa gihapon [$1 motan-aw niining rebisyon] kon gusto nimo magpadayon.",
'rev-deleted-text-view'       => "Ang rebisyon sa panid '''napapas'''.
Isip tagdumala, puyde nimo kini tan-awon; puyde nga may mga detalye sa [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log sa pagpapas].",
'rev-suppressed-text-view'    => "Ang rebisyon sa panid '''naka-suppressed'''.
Isip tagdumala, puyde nimo kini tan-awon; puyde nga may mga detalye sa [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} log sa supresyon].",
'rev-deleted-no-diff'         => "Dili ka puyde motan-aw niining diff tungod kay usa sa mga rebisyon '''napapas'''.
Puyde nga may mga detalye sa [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log sa pagpapas].",
'rev-deleted-unhide-diff'     => "Usa sa mga rebisyon niining diff '''napapas'''.
Puyde nga may mga detalye sa [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log sa pagpapas].
Isip tagdumala, puyde nimo [$1 tan-awon ang diff] kon gusto ka magpadayon.",
'rev-delundel'                => 'ipakita/tagoa',
'revisiondelete'              => 'Papasa/ayaw papasa ang mga rebisyon',
'revdelete-nooldid-title'     => 'Dili sakto ang target nga rebisyon',
'revdelete-nooldid-text'      => 'Puyde nga wala nimo ma-specify ang target nga (mga) rebisyon aron mahimo kini, ang naka-specify nga rebisyon wala pa mahimo, o kaha imo gisulayan og tago ang kasamtangang rebisyon.',
'revdelete-nologtype-title'   => 'Walay gihatag nga klase sa log',
'revdelete-nologtype-text'    => 'Wala ka mag-specify og klase sa log aron mahimo ang kining lihok.',
'revdelete-nologid-title'     => 'Dili saktong entrada sa log',
'revdelete-nologid-text'      => 'Wala ka mag-specify og target log event aron mahimo ang kining lihok o kaha ang gi-specify nga entrada wala pa.',
'revdelete-no-file'           => 'Ang gi-specify nga payl wala pa.',
'revdelete-show-file-confirm' => 'Sigurado ka nga gusto nimong tan-awon ang napapas nga rebisyon sa payl "<nowiki>$1</nowiki>" sugod sa $2 sa $3?',
'revdelete-show-file-submit'  => 'Oo',
'revdelete-selected'          => "'''{{PLURAL:$2|Napiling rebisyon|Napiling mga rebisyon}} sa [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Napiling log event|Mga napiling log event}}:'''",
'revdelete-suppress-text'     => "Ang supresyon gamiton '''lamang''' asa mga mosunod nga kaso:
* Dili maayo nga personal nga impormasyon
*: ''adres sa panimalay ug numero sa telepono, social security number, ubp.''",
'revdelete-legend'            => 'I-set ang restriksyon sa bisibilidad',
'revdelete-hide-text'         => 'Tagoa ang teksto sa rebisyon',
'revdelete-hide-image'        => 'Tagoa ang sulod sa payl',
'revdelete-hide-name'         => 'Tagoa ang lihok ug target',
'revdelete-hide-comment'      => 'Tagoa ang komento sa pag-usab',
'revdelete-hide-user'         => 'Tagoa ang username/IP sa tag-usab',
'revdelete-hide-restricted'   => 'I-suppress ang data gikan sa mga tagdumala ug sa uban pa',
'revdelete-suppress'          => 'I-suppress ang data gikan sa mga tagdumala ug sa uban pa',
'revdelete-unsuppress'        => 'Tangtanga ang mga restriksyon sa nabalik nga mga rebisyon',
'revdelete-log'               => 'Rason:',
'revdelete-submit'            => 'I-aplay sa napiling rebisyon',
'revdelete-logentry'          => 'giusab ang bisibilidad sa rebisyon sa [[$1]]',
'logdelete-logentry'          => 'giusab ang bisibilidad sa event sa [[$1]]',
'revdelete-success'           => "'''Ang bisibilidad sa rebisyon malamposong na-set.'''",
'revdelete-failure'           => "'''Ang bisibilidad sa rebisyon dili ma-set.'''
$1",
'logdelete-success'           => "'''Bisibilidad sa log malamposong na-set.'''",
'logdelete-failure'           => "'''Bisibilidad sa log dili ma-set.'''
$1",
'revdel-restore'              => 'usba ang bisibilidad',
'pagehist'                    => 'Kaagi sa panid',
'deletedhist'                 => 'Napapas nga kaagi',
'revdelete-content'           => 'sulod',
'revdelete-summary'           => 'mubong sugid sa pag-usab',
'revdelete-uname'             => 'username',
'revdelete-restricted'        => 'mga na-aplay nga restriksyon sa mga tagdumala',
'revdelete-unrestricted'      => 'gitangtang ang mga restriksyon alang sa mga tagdumala',
'revdelete-hid'               => 'gitago $1',
'revdelete-unhid'             => 'ayaw itago $1',
'revdelete-log-message'       => '$1 para sa $2 {{PLURAL:$2|rebisyon|mga rebisyon}}',
'logdelete-log-message'       => '$1 para sa $2 {{PLURAL:$2|event|mga event}}',
'revdelete-hide-current'      => 'Sayop sa pagtago sa item sa petsa sa $2, $1: kini ang kasamtangang rebisyon.
Dili puyde kini tagoon.',
'revdelete-show-no-access'    => 'Sayop sa pagtago sa item sa petsa sa $2, $1: namarkahan kini isip "restricted".
Wala kay akses niini.',
'revdelete-modify-no-access'  => 'Sayop sa pagmodipikar sa item sa petsa sa $2, $1: namarkahan kini isip "restricted".
Wala kay akses niini.',
'revdelete-modify-missing'    => 'Sayop sa pagmodipikar sa item ID $1: nawala kini sa database!',
'revdelete-no-change'         => "'''Pahibalo:''' ang item sa petsa sa $2, $1 anaa nay mga setting sa bisibilidad nga gihangyo.",
'revdelete-concurrent-change' => 'Sayop sa pagmodipikar sa item sa petsa $2, $1: ang status basin nausab na sa laing gumagamit samtang imo gi-attempt ang pagmodipikar niini.
Palihog og tsek sa mga log.',
'revdelete-only-restricted'   => 'Dili ka maka-suppress sa mga item gikan sa pagtan-aw sa mga tagdumala kon dili ka mopili og usa sa ubang opsyon sa supresyon.',

# Suppression log
'suppressionlog'     => 'Log sa supresyon',
'suppressionlogtext' => 'Sa ubos mao ang talaan sa mga pagpapas ug sa mga block kabahin sa mga sulod nga nakatago sa mga tagdumala.
Tan-awa ang [[Special:IPBlockList|IP block list]] para sa talaan sa kasamtangang mga ban ug block.',

# History merging
'mergehistory'        => 'I-merge ang mga kaagi sa panid',
'mergehistory-header' => 'Ang kining panid motugot nimo pag-merge sa mga rebisyon sa kaagi sa usa ka panid sa ginikananng panid ngadto sa mas bag-ong panid. Siguradoha nga kining pag-usab mo-meynteyn sa continuity sa kaaging panid.',
'mergehistory-box'    => 'I-merge ang rebisyon sa duha ka panid:',
'mergehistory-from'   => 'Ginikanang panid:',
'mergehistory-into'   => 'Destinasyon nga panid:',
'mergehistory-list'   => 'Puyde ma-merge nga kaagi sa pag-usab',
'mergehistory-merge'  => 'Ang mosunod nga mga rebisyon sa [[:$1]] puyde ma-merge ngadto sa [[:$2]].
Gamita ang radio button column aron mag-merge lamang sa mga rebisyon nga gihimo sa ug sa wala pa sa espesipikong tiyempo.
Hinumdomi nga ang paggamit sa mga sumpay sa nabigasyon mo-reset sa column.',
'mergehistory-go'     => 'Ipakita ang puyde ma-merge nga mga pag-usab',
'mergehistory-submit' => 'I-merge ang mga rebisyon',
'mergehistory-empty'  => 'Walay ma-merge nga mga rebisyon.',

# Merge log
'revertmerge' => 'ayaw i-merge',

# Diffs
'history-title'           => 'Kaagi sa rebisyon sa "$1"',
'difference'              => '(Kalainan sa mga rebisyon)',
'lineno'                  => 'Linya $1:',
'compareselectedversions' => 'Ikompara ang piniling mga bersiyon',
'editundo'                => 'i-way bili',

# Search results
'searchresults'             => 'Mga resulta sa pagpangita',
'searchresults-title'       => 'Mga resulta sa pagpangita para sa "$1"',
'searchresulttext'          => 'Para sa dugang impormasyon mahitungod sa pagpangita sa {{SITENAME}}, tan-awa ang [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'            => 'Gipangita nimo ang \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|tanang panid nga nagsugod sa "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|tanang panid nga misumpay sa "$1"]])',
'searchsubtitleinvalid'     => "Imong gipangita ang '''$1'''",
'notitlematches'            => 'Walay nag-match nga titulo sa panid',
'notextmatches'             => 'Walay misaktong teksto sa panid',
'prevn'                     => 'miaging {{PLURAL:$1|$1}}',
'nextn'                     => 'sunod {{PLURAL:$1|$1}}',
'viewprevnext'              => 'Tan-awa sa ($1 {{int:pipe-separator}} $2) ($3)',
'searchhelp-url'            => 'Help:Mga sulod',
'search-result-size'        => '$1 ({{PLURAL:$2|1 pulong|$2 ka mga pulong}})',
'search-redirect'           => '(redirek $1)',
'search-section'            => '(bahin $1)',
'search-suggest'            => 'Imo bang buot ipasabot: $1',
'search-interwiki-caption'  => 'Mga kaubang proyekto',
'search-interwiki-default'  => '$1 ka mga resulta:',
'search-interwiki-more'     => '(dugang pa)',
'search-mwsuggest-enabled'  => 'may mga sugyot',
'search-mwsuggest-disabled' => 'walay mga sugyot',
'showingresultsheader'      => "{{PLURAL:$5|Resulta '''$1''' sa '''$3'''|Mga resulta '''$1 - $2''' of '''$3'''}} sa '''$4'''",
'nonefound'                 => "'''Bantayi''': Dili tanang ngalang espasyo (namespaces) ang gipangita by default.
Sulayi'g prefix ang imong gipangita gamit ang ''all:'' alang mangita sa tanang sulod (apil ang mga panid sa hisgot, plantilya, ubp), o gamita ang gikinahanglang ngalang espasyo isip prefix.",
'search-nonefound'          => 'Walay mga resulta nga nag-match sa gipangita.',
'powersearch'               => 'Abansadong pagpangita',
'powersearch-legend'        => 'Abansadong pagpangita',
'powersearch-ns'            => 'Pangitaa sa mga ngalang espasyo:',
'powersearch-redir'         => 'Itala ang mga redirek',
'powersearch-field'         => 'Pangitaa ang',
'powersearch-togglelabel'   => 'I-tsek:',
'powersearch-toggleall'     => 'Tanan',
'powersearch-togglenone'    => 'Wala',
'search-external'           => 'Eksternal nga pagpangita',
'searchdisabled'            => 'Pagpangita sa {{SITENAME}} naka-disable.
Puyde ka mangita gamit ang Google sa kasamtangan.
Hinumdomi nga ang ilang indeks sa sulod sa {{SITENAME}} mahimong dugay-dugay na.',

# Quickbar
'qbsettings'               => 'Quickbar',
'qbsettings-none'          => 'Wala',
'qbsettings-fixedleft'     => 'Naka-fix sa wala',
'qbsettings-fixedright'    => 'Naka-fix sa tuo',
'qbsettings-floatingleft'  => 'Floating sa wala',
'qbsettings-floatingright' => 'Floating sa tuo',

# Preferences page
'preferences'                   => 'Mga preperensiya',
'mypreferences'                 => 'Akong preperensiya',
'prefs-edits'                   => 'Gidaghanon sa nausab:',
'prefsnologin'                  => 'Wala maka-log-in',
'prefsnologintext'              => 'Kinahanglan ikaw <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} naka-log-in]</span> aron ma-set ang imong mga preperensiya.',
'changepassword'                => 'Usba ang pasword',
'prefs-skin'                    => 'Panit',
'skin-preview'                  => 'Paunang tan-aw',
'prefs-math'                    => 'Math',
'datedefault'                   => 'Walay preperensiya',
'prefs-datetime'                => 'Petsa ug oras',
'prefs-personal'                => 'Profile sa gumagamit',
'prefs-rc'                      => 'Mga bag-ong pag-usab',
'prefs-watchlist'               => 'Gibantayan',
'prefs-watchlist-days'          => 'Mga adlaw nga ipakita sa gibantayan:',
'prefs-watchlist-days-max'      => 'Maximum 7 ka adlaw',
'prefs-watchlist-edits'         => 'Maximum nga numero sa pag-usab nga ipakita sa ekspanded nga gibantayan:',
'prefs-watchlist-edits-max'     => 'Maximum nga numero: 1000',
'prefs-watchlist-token'         => 'Token sa gibantayan',
'prefs-misc'                    => 'Misc',
'prefs-resetpass'               => 'Usba ang pasword',
'prefs-email'                   => 'Mga opsyon sa e-mail',
'prefs-rendering'               => 'Appearance',
'saveprefs'                     => 'Tipigi',
'resetprefs'                    => 'Limpyohi ang wala matipigi nga mga kausaban',
'restoreprefs'                  => 'Ibalik ang tanang default settings',
'prefs-editing'                 => 'Nagausab',
'prefs-edit-boxsize'            => 'Size sa edit window.',
'rows'                          => 'Mga row:',
'columns'                       => 'Mga kolum:',
'searchresultshead'             => 'Pangitaa',
'resultsperpage'                => 'Mga hit matag panid:',
'contextlines'                  => 'Mga linya matag hit:',
'contextchars'                  => 'Mga konteksto matag linya:',
'stub-threshold'                => 'Threshold para sa <a href="#" class="stub">stub link</a> formatting (bytes):',
'recentchangesdays'             => 'Mga adlaw nga ipakita sa bag-ong giusab:',
'recentchangesdays-max'         => 'Maximum $1 {{PLURAL:$1|ka adlaw|ka mga adlaw}}',
'recentchangescount'            => 'Numero sa mga pag-usab nga ipakita isip default:',
'prefs-help-recentchangescount' => 'Nahiapil dinhi ang mga bag-ong giusab, kaagi sa panid, ug mga log.',
'savedprefs'                    => 'Natipigan na ang imong mga preperensiya.',
'timezonelegend'                => 'Sona sa oras:',
'localtime'                     => 'Oras sa lokal:',
'timezoneuseserverdefault'      => 'Gamita ang default sa server',
'timezoneuseoffset'             => 'Uban pa (i-specify ang offset)',
'timezoneoffset'                => 'Offset¹:',
'servertime'                    => 'Oras sa server:',
'guesstimezone'                 => 'Ibutang gikan sa brawser',
'timezoneregion-africa'         => 'Aprika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antartika',
'timezoneregion-arctic'         => 'Artiko',
'timezoneregion-asia'           => 'Asya',
'timezoneregion-atlantic'       => 'Kadagatang Atlantiko',
'timezoneregion-australia'      => 'Australya',
'timezoneregion-europe'         => 'Uropa',
'timezoneregion-indian'         => 'Kadagatang Indyan',
'timezoneregion-pacific'        => 'Kadagatang Pasipiko',
'allowemail'                    => 'I-enable ang e-mail gikan sa ubang gumagamit',
'prefs-searchoptions'           => 'Mga opsyon sa pagpangita',
'prefs-namespaces'              => 'Ngalang espasyo',
'defaultns'                     => 'Kondili, pangita na lang niining mga ngalang espasyo:',
'default'                       => 'default',
'prefs-files'                   => 'Mga payl',
'prefs-custom-css'              => 'Kustom nga CSS',
'prefs-custom-js'               => 'Kustom nga JS',
'prefs-reset-intro'             => 'Puyde nimo gamiton ang kining panid aron ma-reset ang imong mga preprensiya ngadto sa default sa sayt.
Dili kini puyde mabalik.',
'prefs-emailconfirm-label'      => 'Kompirmasyon sa e-mail:',
'prefs-textboxsize'             => 'Size sa editing window',
'youremail'                     => 'E-mail:',
'username'                      => 'Username:',
'uid'                           => 'ID sa gumagamit:',
'prefs-memberingroups'          => 'Miyembro sa {{PLURAL:$1|grupo|mga grupo}}:',
'prefs-registration'            => 'Oras sa pagparehistro:',
'yourrealname'                  => 'Tinuod nga pangalan:',
'yourlanguage'                  => 'Pinulongan:',
'yournick'                      => 'Bag-ong pirma:',
'prefs-help-signature'          => 'Ang mga komento sa panid sa hisgot kinahanglan pirmahan gamit ang "<nowiki>~~~~</nowiki>" diin kini ma-convert ngadto sa imong pirma ug may timestamp.',
'badsig'                        => 'Dili sakto ang hilaw nga pirma.
I-tsek ang mga tag nga HTML.',
'badsiglength'                  => 'Grabe ka taas ang imong pirma.
Dili kini puyde molapas sa $1 {{PLURAL:$1|ka karakter|ka mga karakter}}.',
'yourgender'                    => 'Gender:',
'gender-unknown'                => 'Wala gi-specify',
'gender-male'                   => 'Lalaki',
'gender-female'                 => 'Babaye',
'prefs-help-gender'             => 'Opsyonal: gigamit para sa gender-correct nga pag-adress sa software.
Publiko kining impormasyon.',
'email'                         => 'E-mail',
'prefs-help-realname'           => 'Opsyonal ang tinuod nga pangalan.
Kon gipili nimo nga ihatag kini, gamiton kini aron pasidunggan ka sa imong mga tampo.',
'prefs-help-email-required'     => 'Ang e-mail address gikinahanglan.',
'prefs-info'                    => 'Basiko nga impormasyon',
'prefs-i18n'                    => 'Internasyonalisasyon',
'prefs-signature'               => 'Pirma',

# Groups
'group-sysop' => 'Mga tagdumala',

'grouppage-sysop' => '{{ns:project}}:Mga tigdumala',

# User rights log
'rightslog'  => 'Log sa mga katungod sa gumagamit',
'rightsnone' => '(wala)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'               => 'basaha kining panid',
'action-edit'               => 'usba kining panid',
'action-createpage'         => "paghimo'g mga panid",
'action-createtalk'         => "paghimo'g mga panid sa hisgot",
'action-createaccount'      => "paghimo'g akawnt niining gumagamit",
'action-minoredit'          => 'markahan ang pag-usab isip menor',
'action-move'               => 'ibalhin kining panid',
'action-move-subpages'      => 'ibalhin kining panid, ug ang iyang mga subpanid',
'action-move-rootuserpages' => 'ibalhin ang ugat nga mga panid sa gumagamit',
'action-movefile'           => 'ibalhin ang kining payl',
'action-upload'             => 'i-upload ang kining payl',
'action-reupload'           => 'i-overwrite ang kining eksisting nga payl',
'action-reupload-shared'    => 'i-override ang kining payl sa shared repository',
'action-upload_by_url'      => 'i-upload ang kining payl gikan sa URL',
'action-writeapi'           => 'gamita ang write API',
'action-delete'             => 'papasa kining panid',
'action-deleterevision'     => 'papasa kining rebisyon',
'action-deletedhistory'     => 'tan-awa ang napapas nga kaagi niining panid',
'action-browsearchive'      => 'pangitaa ang napapas nga mga panid',
'action-undelete'           => 'ayaaw papasa ang kining panid',
'action-suppressrevision'   => 'i-rebyu ug ibalik ang kining nakatagong rebisyon',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|ka pag-usab|ka mga pag-usab}}',
'recentchanges'                  => 'Mga bag-ong giusab',
'recentchanges-legend'           => 'Mga opsyon sa bag-ong pag-usab',
'recentchanges-feed-description' => 'Bantayi ang kinabag-ohang mga pag-usab sa wiki niining feed.',
'rcnote'                         => "Sa ubos {{PLURAL:$1|ang '''1''' kausaban|ang mga bag-ong '''$1''' kausaban}} sa miaging {{PLURAL:$2|ka adlaw|'''$2''' ka mga adlaw}}, sa taknang $5, $4.",
'rclistfrom'                     => 'Ipakita ang mga bag-ong pag-usab gikan $1',
'rcshowhideminor'                => '$1 menor nga pag-usab',
'rcshowhidebots'                 => '$1 mga bot',
'rcshowhideliu'                  => '$1 mga gumagamit nga naka-log-in',
'rcshowhideanons'                => '$1 mga wala mailhing gumagamit',
'rcshowhidemine'                 => '$1 akong mga pag-usab',
'rclinks'                        => 'Ipakita ang miaging $1 ka kausaban sa miaging $2 ka mga adlaw<br />$3',
'diff'                           => 'kalainan',
'hist'                           => 'kaagi',
'hide'                           => 'Tagoi',
'show'                           => 'Ipakita',
'minoreditletter'                => 'm',
'newpageletter'                  => 'B',
'boteditletter'                  => 'b',
'rc-enhanced-expand'             => 'Ipakita ang mga detalye (JavaScript kinahanglan)',
'rc-enhanced-hide'               => 'Tagoa ang mga detalye',

# Recent changes linked
'recentchangeslinked'         => 'Mga may kalabotang kausaban',
'recentchangeslinked-feed'    => 'Mga may kalabotang kausaban',
'recentchangeslinked-toolbox' => 'Mga may kalabotang kausaban',
'recentchangeslinked-title'   => 'Mga pag-usab nga may kalabotan sa "$1"',
'recentchangeslinked-summary' => "Kini ang talaan sa mga bag-ong kausaban sa mga panid nga misumpay sa espesipikong panid (o sa mga sakop sa espesipikong kategoriya).
Ang mga panid sa [[Special:Watchlist|imong gibantayan]] '''nakalugom'''.",
'recentchangeslinked-page'    => 'Ngalan sa panid:',
'recentchangeslinked-to'      => 'Ipakita na lang ang mga pag-usab sa mga panid nga nakasumpay sa nahatag nga panid',

# Upload
'upload'        => 'Pagsumiter og payl',
'uploadlogpage' => 'Log sa upload',
'uploadedimage' => 'na-upload ang "[[$1]]"',

# File description page
'file-anchor-link'          => 'Payl',
'filehist'                  => 'Kaagi sa payl',
'filehist-help'             => 'I-klik ang petsa/oras aron makit-an ang hulagway sa payl niadtong panahona.',
'filehist-current'          => 'kasamtangan',
'filehist-datetime'         => 'Petsa/Takna',
'filehist-thumb'            => 'Thumbnail',
'filehist-thumbtext'        => 'Thumbnail sa bersyon sa $1',
'filehist-user'             => 'Tiggamit',
'filehist-dimensions'       => 'Mga dimensyon',
'filehist-comment'          => 'Komento',
'imagelinks'                => 'Mga sumpay sa payl',
'linkstoimage'              => 'Ang mosunod nga {{PLURAL:$1|mga panid misumpay|$1 panid misumpay}} niining payl:',
'sharedupload'              => 'Ang kining payl gikan sa $1 ug mahimong gigamit sa ubang mga proyekto.',
'uploadnewversion-linktext' => 'Pag-upload og bag-ong bersyon niining payl',

# Random page
'randompage' => 'Bisan unsang panid',

# Statistics
'statistics' => 'Estadistika',

# Miscellaneous special pages
'nbytes'        => '$1 {{PLURAL:$1|byte|mga byte}}',
'nmembers'      => '$1 {{PLURAL:$1|sakop|mga sakop}}',
'prefixindex'   => 'Tanang panid nga may prefix',
'newpages'      => 'Mga bag-ong panid',
'move'          => 'Ibalhin',
'movethispage'  => 'Ibalhin kining panid',
'pager-newer-n' => '{{PLURAL:$1|mas bag-o 1|mas bag-o $1}}',
'pager-older-n' => '{{PLURAL:$1|mas daan1|mas daan $1}}',

# Book sources
'booksources'               => 'Mga reperensiyang libro',
'booksources-search-legend' => 'Pangitaa ang mga reperensiyang libro',
'booksources-go'            => 'Sige',

# Special:Log
'log' => 'Mga log',

# Special:AllPages
'allpages'       => 'Tanang panid',
'alphaindexline' => '$1 hangtod $2',
'prevpage'       => 'Miaging panid ($1)',
'allpagesfrom'   => 'Ipakita ang mga panid nga nagsugod sa:',
'allpagesto'     => 'Ipakita ang mga panid nga nagtapos sa:',
'allarticles'    => 'Tanang panid',
'allpagessubmit' => 'Sige',

# Special:Categories
'categories'                  => 'Mga kategoriya',
'categoriespagetext'          => 'Ang mosunod nga mga kategoriya adunay sulod nga panid o medya.',
'special-categories-sort-abc' => 'han-aya nga paalpabetikal',

# Special:LinkSearch
'linksearch' => 'Mga sumpay sa gawas',

# Special:Log/newusers
'newuserlogpage'           => "Log sa paghimo'g gumagamit",
'newuserlogpagetext'       => "Kini mao ang ''log'' sa bag-ong namugnang mga gumagamit.",
'newuserlog-byemail'       => "ang pasword gipadala na pinaagi sa ''e-mail''",
'newuserlog-create-entry'  => 'Bag-ong gumagamit',
'newuserlog-create2-entry' => "naghimo'g akawnt alang kang $1",

# Special:ListGroupRights
'listgrouprights-members' => '(talaan sa mga miyembro)',

# E-mail user
'emailuser' => 'I-email kaning gumagamit',

# Watchlist
'watchlist'         => 'Akong gibantayan',
'mywatchlist'       => 'Akong gibantayan',
'watchlistfor'      => "(para kang '''$1''')",
'addedwatch'        => 'Nadugang sa gibantayan',
'addedwatchtext'    => "Ang panid \"[[:\$1]]\" nadugang na sa imong [[Special:Watchlist|gibantayan]].
Ang mga pag-usab puhon sa kining panid ug ang kaubang panid sa hisgot dinhi maitala, ug ang panid mopakita nga '''nakalugom''' sa [[Special:RecentChanges|talaan sa mga bag-ong pag-usab]] aron dali kini pilion.",
'removedwatch'      => 'Natangtang sa gibantayan',
'removedwatchtext'  => 'Ang panid nga "[[:$1]]" natangtang na sa imong [[Special:Watchlist|gibantayan]].',
'watch'             => 'Bantayi',
'watchthispage'     => 'Bantayi kining panid',
'unwatch'           => 'Pasagdi',
'watchlist-details' => '{{PLURAL:$1|$1 ka panid|$1 ka mga panid}} ang imong gibantayan, way labot ang mga panid sa hisgot.',
'wlshowlast'        => 'Ipakita ang miaging $1 ka oras $2 ka mga adlaw $3',
'watchlist-options' => 'Mga opsyon sa akong gibantayan',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Gibantayan...',
'unwatching' => 'Gipasagdan...',

# Delete
'deletepage'            => 'Papasa ang panid',
'confirmdeletetext'     => 'Imo nang papason ang panid kauban ang tanang kaagi niini.
Palihog ikompirma nga imo gyud ni buhaton, nga nakasabot ka sa mga puyde idangat niini, ug imo kini gibuhat sumala sa [[{{MediaWiki:Policy-url}}|palisiya]].',
'actioncomplete'        => 'Nahuman na ang lihok',
'deletedtext'           => 'Ang "<nowiki>$1</nowiki>" napapas na.
Tan-awa ang $2 para sa rekord sa mga bag-ong napapas.',
'deletedarticle'        => 'gitangtang "[[$1]]"',
'dellogpage'            => 'Log sa pagtangtang',
'deletecomment'         => 'Rason:',
'deleteotherreason'     => 'Uban pa/dugang nga rason:',
'deletereasonotherlist' => 'Uban pang rason',

# Rollback
'rollbacklink' => 'i-rollback',

# Protect
'protectlogpage'              => 'Log sa proteksyon',
'protectedarticle'            => 'protektado "[[$1]]"',
'modifiedarticleprotection'   => 'giusab ang level sa proteksyon para sa "[[$1]]"',
'protectcomment'              => 'Rason:',
'protectexpiry'               => 'Matapos:',
'protect_expiry_invalid'      => 'Dili puyde ang expiry time.',
'protect_expiry_old'          => 'Ang expiry time miagi na.',
'protect-text'                => "Puyde nimo tan-awon ug usbon ang proteksyon dinhi para sa panid nga '''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "Walay permiso ang imong akawnt nga mag-usab og level sa proteksyon sa panid.
Kini ang kasamtangan nga mga setting sa panid nga '''$1''':",
'protect-cascadeon'           => 'Ang kining panid kasamtangang giprotektahan tungod kay nahiapil kini sa mosunod nga {{PLURAL:$1|panid, nga may|mga panid, nga may}} naka-turn on nga pabuhagay (cascading) nga proteksyon.
Puyde nimo usbon ang level sa proteksyon ning panid, pero dili kini makaapekto sa pabuhagay (cascading) nga proteksyon.',
'protect-default'             => 'Tugotan ang tanang gumagamit',
'protect-fallback'            => 'Nanginahangla\'g "$1" nga permiso',
'protect-level-autoconfirmed' => 'I-block ang bag-o ug wala marehistrong gumagamit',
'protect-level-sysop'         => 'Mga tagdumala lamang',
'protect-summary-cascade'     => 'pabuhagay',
'protect-expiring'            => 'matapos sa $1 (UTC)',
'protect-cascade'             => 'Protektahi ang mga panid nga nahiapil niining panid (pabuhagay nga proteksyon)',
'protect-cantedit'            => 'Dili ka makausab sa level sa proteksyon niining panid, tungod kay wala kay permiso nga usbon kini.',
'restriction-type'            => 'Permiso:',
'restriction-level'           => 'Level sa restriksyon:',

# Undelete
'undeletelink'     => 'tan-awa/ibalik',
'undeletedarticle' => 'nabalik "[[$1]]"',

# Namespace form on various pages
'namespace'      => 'Ngalang espasyo:',
'invert'         => 'Baliha ang gipili',
'blanknamespace' => '(Meyn)',

# Contributions
'contributions'       => 'Mga tampo ning gumagamit',
'contributions-title' => 'Mga tampo sa gumagamit para kang $1',
'mycontris'           => 'Akong tampo',
'contribsub2'         => 'Para $1 ($2)',
'uctop'               => '(hitaas)',
'month'               => 'Gikan sa bulan (ug mas sayo pa):',
'year'                => 'Gikan sa tuig (ug mas sayo pa):',

'sp-contributions-newbies'  => 'Ipakita lamang ang mga tampo sa mga bag-ong gumagamit',
'sp-contributions-blocklog' => 'log sa block',
'sp-contributions-talk'     => 'Hisgot',
'sp-contributions-search'   => 'Pangitaa ang mga tampo',
'sp-contributions-username' => 'Adres sa IP o username:',
'sp-contributions-submit'   => 'Pangitaa',

# What links here
'whatlinkshere'            => 'Unsay mga misumpay dinhi',
'whatlinkshere-title'      => 'Mga panid nga misumpay ngadto sa "$1"',
'whatlinkshere-page'       => 'Panid:',
'linkshere'                => "Ang mosunod nga mga panid misumpay sa '''[[:$1]]''':",
'isredirect'               => 'panid sa redirekta',
'istemplate'               => 'transklusyon',
'isimage'                  => 'sumpay nga imahen',
'whatlinkshere-prev'       => '{{PLURAL:$1|miaging|miaging $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|sunod|sunod $1}}',
'whatlinkshere-links'      => '← mga sumpay',
'whatlinkshere-hideredirs' => '$1 mga redirek',
'whatlinkshere-hidetrans'  => '$1 mga transklusyon',
'whatlinkshere-hidelinks'  => '$1 mga sumpay',
'whatlinkshere-filters'    => 'Mga filter',

# Block/unblock
'blockip'                  => 'I-block ang gumagamit',
'ipboptions'               => '2 ka oras:2 hours,1 ka adlaw:1 day,3 ka adlaw:3 days,1 ka semana:1 week,2 ka semana:2 weeks,1 ka buwan:1 month,3 ka buwan:3 months,6 ka buwan:6 months,1 ka tuig:1 year,hangtod-sa-hangtod:infinite',
'ipblocklist'              => 'Na-block nga mga adres sa IP ug username',
'blocklink'                => 'i-block',
'unblocklink'              => 'ayaw i-block',
'change-blocklink'         => 'bag-oha ang block',
'contribslink'             => 'mga tampo',
'blocklogpage'             => 'Log sa block',
'blocklogentry'            => 'na-block si [[$1]] nga may expiry time nga $2 $3',
'unblocklogentry'          => 'gi-unblock si $1',
'block-log-flags-nocreate' => "ang paghimo'g akawnt gipugngan",

# Move page
'movepagetext'     => "Gamit ang form sa ubos moilis og ngalan sa panid, mabalhin ang tanang kaagi niini ngadto sa bag-ong ngalan.
Ang karaang titulo mahimong panid sa redirekta ngadto sa bag-ong titulo.
Puyde nimo awtomatik nga i-update ang mga redirekta nga mipunterya sa orihinal nga titulo.
Kon dili nimo kini pilion, siguradoha nga i-tsek nimo ang [[Special:DoubleRedirects|duble]] o [[Special:BrokenRedirects|buak nga redirek]].
Ikaw ang responsable sa pagsigurado nga ang mga sumpay padayon nga magpuntirya ngadto sa saktong adtoan.

Bantayi nga ang panid '''dili''' ibalhin kon aduna nay panid sa bag-ong titulo, waylabot kon kini walay sulod o kaha redirek lang ug walay kaagi sa pag-usab.
Buot ipasabot nga puyde nimo ibalik ang pag-ilis sa ngalan ngadto sa karaang ngalan kon ikaw nasayop, ug dili ka maka-overwrite sa panid nga anaa na.

'''Pahibalo!'''
Mahimo nga drastiko ug wala damha nga kausaban kini sa usa ka panid nga popular;
palihog siguradoha nga nasabtan nimo ang idangat niini bag-o nimo kini ipadayon.",
'movepagetalktext' => "Ang kaubang panid sa hisgot awtomatikong mabalhin uban sa meyn nga panid '''waylabot kon:'''
*Usa ka may-sulod nga panid sa hisgot anaa na ubos sa bag-ong ngalan, o
*Imo gi-uncheck ang kahon sa ubos.

Sa maong mga kaso, manwal nga imo ibalhin o i-merge ang panid kon gustohon.",
'movearticle'      => 'Ibalhin ang panid:',
'newtitle'         => 'Ngadto sa bag-ong titulo:',
'move-watch'       => 'Bantayi kining panid',
'movepagebtn'      => 'Ibalhin ang panid',
'pagemovedsub'     => 'Malamposon ang pagbalhin',
'movepage-moved'   => 'Ang \'\'\'"$1" nabalhin na ngadto sa "$2"\'\'\'',
'articleexists'    => 'May panid na sa maong ngalan, o ang ngalan nga imong napili ginadili.
Palihog pagpili og laing ngalan.',
'talkexists'       => "'''Ang panid mismo malamposon nga nabalhin, pero ang panid sa hisgot dili mabalhin tungod kay duna nay sulod ang panid sa hisgot sa bag-ong titulo.
Palihog imanwal ang pag-merge nila.'''",
'movedto'          => 'nabalhin ngadto',
'movetalk'         => 'Ibalhin ang kaubang panid sa hisgot',
'1movedto2'        => 'gibalhin ang [[$1]] ngadto sa [[$2]]',
'1movedto2_redir'  => 'gibalhin ang [[$1]] ngadto sa [[$2]] taas sa redirek',
'movelogpage'      => 'Log sa pagbalhin',
'movereason'       => 'Rason:',
'revertmove'       => 'i-revert',

# Export
'export' => 'Pag-eksport og mga panid',

# Thumbnails
'thumbnail-more' => 'Padak-a',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Ang kaugalingon kong panid',
'tooltip-pt-mytalk'               => 'Ang imong panid sa hisgot',
'tooltip-pt-preferences'          => 'Akong mga preperensiya',
'tooltip-pt-watchlist'            => 'Talaan sa mga panid nga imong gibantayan ang mga pag-usab',
'tooltip-pt-mycontris'            => 'Talaan sa akong mga tampo',
'tooltip-pt-login'                => "Gihangyo ka namo sa pag-''log-in'', apan wala kini gikinahanglan aron makausab ka sa mga panid.",
'tooltip-pt-logout'               => 'Biya',
'tooltip-ca-talk'                 => 'Panaghisgot kabahin sa panid',
'tooltip-ca-edit'                 => "Mahimo mong usbon ang kining panid. Palihog gamita ang ''Paunang tan-aw'' nga tuplokanan bag-o nimotipigan ang panid.",
'tooltip-ca-addsection'           => "Paghimo'g bag-ong seksyon",
'tooltip-ca-viewsource'           => 'Giprotektahan kining panid.
Pwede nimong tan-awon ang ginikanan.',
'tooltip-ca-history'              => 'Mga miaging rebisyon ning panid',
'tooltip-ca-protect'              => 'Protektahi kining panid',
'tooltip-ca-delete'               => 'Papasa kining panid',
'tooltip-ca-move'                 => 'Ibalhin kini nga panid',
'tooltip-ca-watch'                => 'Idugang kining panid sa imong gibantayan',
'tooltip-ca-unwatch'              => 'Tangtanga kining maong panid sa imong mga gibantayang panid',
'tooltip-search'                  => 'Pangitaa {{SITENAME}}',
'tooltip-search-go'               => 'Moadto sa panid nga may saktong ngalan kon anaa kini',
'tooltip-search-fulltext'         => 'Mangita sa mga panid kabahin niining teksto',
'tooltip-n-mainpage'              => 'Bisitaha ang Unang Panid',
'tooltip-n-portal'                => 'Kabahin sa proyekto, unsay imong mahimo, asa mangita sa mga impormasyon',
'tooltip-n-currentevents'         => 'Pangita og nahaunang impormasyon sa mga bag-ong panghitabo',
'tooltip-n-recentchanges'         => 'Ang talaan sa mga bag-ong giusab sa wiki.',
'tooltip-n-randompage'            => 'Pag-abli og bisan unsang panid',
'tooltip-n-help'                  => 'Ang dapit nga angay mong pangitaan.',
'tooltip-t-whatlinkshere'         => 'Talaan sa mga wiki nga panid nga misumpay dinhi',
'tooltip-t-recentchangeslinked'   => 'Mga bag-ong pag-usab sa mga panid gikan ning panid',
'tooltip-feed-rss'                => 'Feed nga RSS niining panid',
'tooltip-feed-atom'               => 'Feed nga Atom niining panid',
'tooltip-t-contributions'         => 'Tan-awa ang talaan sa mga tampo niining gumagamit',
'tooltip-t-emailuser'             => 'Padalhi og e-mail ang kaning gumagamit',
'tooltip-t-upload'                => 'Pagsumiter og mga payl',
'tooltip-t-specialpages'          => 'Talaan sa mga espesyal nga panid',
'tooltip-t-print'                 => 'Mapatik nga bersyon ning panid',
'tooltip-t-permalink'             => 'Permanenteng sumpay niining rebisyon sa panid',
'tooltip-ca-nstab-main'           => 'Tan-awa ang sulod ning panid',
'tooltip-ca-nstab-user'           => 'Tan-awa ang panid sa tiggamit',
'tooltip-ca-nstab-special'        => 'Kini usa ka espesyal nga panid, dili nimo kini puyde usbon',
'tooltip-ca-nstab-project'        => 'Tan-awa ang panid sa proyekto',
'tooltip-ca-nstab-image'          => 'Tan-awa ang panid sa payl',
'tooltip-ca-nstab-template'       => 'Tan-awa ang plantilya',
'tooltip-ca-nstab-category'       => 'Tan-awa ang panid sa kategoriya',
'tooltip-minoredit'               => 'Markahi kini isip ginagmayng pag-usab',
'tooltip-save'                    => 'I-save ang imong gipang-usab',
'tooltip-preview'                 => 'Paunang tan-aw sa imong mga pag-usab, palihog gamita kini usa tipigi ang panid!',
'tooltip-diff'                    => 'Ipakita asa ang imong giusab sa teksto.',
'tooltip-compareselectedversions' => 'Tan-awa ang mga kalainan sa duhang gipiling bersiyon niining panid.',
'tooltip-watch'                   => 'Ipuno kining maong panid sa imong mga gibantayan',
'tooltip-rollback'                => '"Rollback" mo-revert sa (mga) pag-usab niining panid ngadto sa kinaulhing mitampo sa usa lang ka klik',
'tooltip-undo'                    => 'Ang "undo" mo-revert niining pag-usab ug moabli sa edit form sa paunang tan-aw nga mode.
Puyde dugangan og rason sa mubong sugid.',

# Browsing diffs
'previousdiff' => 'Mas daang pag-usab',
'nextdiff'     => 'Mas bag-ong pag-usab →',

# Media information
'file-info-size'       => '($1 × $2 pixels, size sa payl: $3, MIME type: $4)',
'file-nohires'         => '<small>Walay mas taas nga resolusyon.</small>',
'svg-long-desc'        => '(SVG nga payl, nominally $1 × $2 pixels, size sa payl: $3)',
'show-big-image'       => 'Tibuok resolusyon',
'show-big-image-thumb' => '<small>Size niining preview: $1 × $2 pixels</small>',

# Bad image list
'bad_image_list' => 'Ang pormat mao ang mosunod:

Ang mga list items (mga linya nga nagsugod sa*) ang gikonsiderar.
Ang unang sumpay sa linya kinahanglang sumpay sa payl nga daot.
Ang bisan unsang mosunod nga mga sumpay sa parehong linya gikonsiderar nga mga eksepsyon, i.e. mga panid diin ang payl mahimong inline.',

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => 'Ang kining payl adunay dugang nga impormasyon, mahimong nadugang gikan sa digital camera o scanner nga gigamit sa paghimo o pag-digitize niini.
Kon ang payl namodipikar gikan sa orihinal nga estado, ang ubang detalye mamahimong dili moreplek sa namodipikar nga payl.',
'metadata-expand'   => 'Ipakita ang mas daghang detalye',
'metadata-collapse' => 'Tagoa ang mga ekstended nga detalye',
'metadata-fields'   => 'Ang XIF metadata fields nga nakatala niining mensahe iapil sa display sa panid sa imahen kon gi-collapse ang metadata table.
Ang uban default nga nakatago.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# External editor support
'edit-externally'      => 'Usba kining payl gamit ang eksternal nga aplikasyon',
'edit-externally-help' => '(Tan-awa ang [http://www.mediawiki.org/wiki/Manual:External_editors setup instructions] para sa dugang nga impormasyon)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'tanan',
'namespacesall' => 'tanan',
'monthsall'     => 'tanan',

# Watchlist editing tools
'watchlisttools-view' => 'Tan-awa ang may kalabotan nga mga pag-usab',
'watchlisttools-edit' => 'Tan-awa ug usba ang mga gibantayan',
'watchlisttools-raw'  => 'Usba ang hilaw nga talaan sa gibantayan',

# Special:SpecialPages
'specialpages' => 'Espesyal nga mga panid',

);
