<?php
/** Turkmen (Türkmençe)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Flrn
 * @author Hanberke
 * @author Runningfridgesrule
 */

$namespaceNames = array(
	NS_SPECIAL          => 'Ýörite',
	NS_TALK             => 'Çekişme',
	NS_USER             => 'Ulanyjy',
	NS_USER_TALK        => 'Ulanyjy_çekişme',
	NS_PROJECT_TALK     => '$1_çekişme',
	NS_FILE             => 'Faýl',
	NS_FILE_TALK        => 'Faýl_çekişme',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_çekişme',
	NS_TEMPLATE         => 'Şablon',
	NS_TEMPLATE_TALK    => 'Şablon_çekişme',
	NS_HELP             => 'Ýardam',
	NS_HELP_TALK        => 'Ýardam_çekişme',
	NS_CATEGORY         => 'Kategoriýa',
	NS_CATEGORY_TALK    => 'Kategoriýa_çekişme',
);

$linkTrail = '/^([a-zÄäÇçĞğŇňÖöŞşÜüÝýŽž]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Çykgytlaryň aşagyny çyz:',
'tog-highlightbroken'         => 'Boş çykgytlary <a href="" class="new">ine şeýle</a> (alternatiw: ine şeýle<a href="" class="internal">?</a>) görkez.',
'tog-justify'                 => 'Teksti iki ýaňa deňle',
'tog-hideminor'               => '"Soňky üýtgeşmeler" sahypasynda ujypsyzja özgerdişleri gizle',
'tog-hidepatrolled'           => '"Soňky üýtgeşmeler" sahypasynda patrullyk edilen özgerdişleri gizle',
'tog-newpageshidepatrolled'   => 'Patrullyk edilen sahypalary täze sahypalaryň sanawynda gizle',
'tog-extendwatchlist'         => 'Gözegçilik sanawyny, diňe soňky däl-de, eýsem ähli üýtgeşmeleri görkezer ýaly edip giňelt',
'tog-usenewrc'                => 'Güýçlendirilen soňky üýtgeşmelerden peýdalanyň (JavaScript bolmaly)',
'tog-numberheadings'          => 'Atlary awtomatik usulda belgile',
'tog-showtoolbar'             => 'Redaktirleme mahalynda gural panelini görkez (JavaScript)',
'tog-editondblclick'          => 'Sahypany jübüt tyklap, redaktirläp başla (JavaScript)',
'tog-editsection'             => 'Her bir bölümde [redaktirle] çykgydyny görkez',
'tog-editsectiononrightclick' => 'Bölümleri bölümiň adyna sag tyklap redaktirlemäge mümkinçilik döret (JavaScript)',
'tog-showtoc'                 => 'Mazmun tablisasyny görkez (3 sanydan köp ady bar bolan sahypalar üçin)',
'tog-rememberpassword'        => 'Parolymy ýatda sakla',
'tog-editwidth'               => 'Özgerdiş gutusyny tutuş ekrany doldurar ýaly edip giňelt',
'tog-watchcreations'          => 'Döreden sahypalarymy gözegçilik sanawyma goş',
'tog-watchdefault'            => 'Redaktirlän sahypalarymy gözegçilik sanawyma goş',
'tog-watchmoves'              => 'Adyny üýtgeden sahypalarymy gözegçilik sanawyma goş',
'tog-watchdeletion'           => 'Öçüren sahypalarymy gözegçilik sanawyma goş',
'tog-minordefault'            => 'Ähli özgerdişleri "ujypsyzja" diýip belle',
'tog-previewontop'            => 'Deslapky syny redaktirleme gutusynyň üstünde görkez',
'tog-previewonfirst'          => 'Özgerdişde deslapky syny görkez',
'tog-nocache'                 => '
Sahypalary keşirleme',
'tog-enotifwatchlistpages'    => 'Gözegçilik sanawymdaky sahypa redaktirlenen mahaly maňa e-poçta iber',
'tog-enotifusertalkpages'     => 'Ulanyjy sahypamda üýtgeşme bolan mahaly maňa e-poçta iber',
'tog-enotifminoredits'        => 'Sahypalardaky ujypsyzja özgerdişlerde-de maňa e-poçta iber',
'tog-enotifrevealaddr'        => 'E-poçta adresimi bildiriş e-poçtalarynda görkez.',
'tog-shownumberswatching'     => 'Gözegçilikde saklaýan ulanyjylaryň sanyny görkez',
'tog-oldsig'                  => 'Bar bolan goluň deslapky syny:',
'tog-fancysig'                => 'Gola wikitekst hökmünde çemeleş (awtomatik çykgytsyz)',
'tog-externaleditor'          => 'Başga programmalar bilen redaktirle (diňe hünärmenler üçin, kompýuteriňizde ýörite sazlamalar talap edilýär)',
'tog-externaldiff'            => 'Başga programmalaryň üsti bilen deňeşdir (diňe hünärmenler üçin, kompýuteriňizde ýörite sazlamalar talap edilýär)',
'tog-showjumplinks'           => '"Git" çykgydyny işlet',
'tog-uselivepreview'          => 'Gönümel deslapky syny ulan (JavaScript) (Synag edilýär)',
'tog-forceeditsummary'        => 'Gysgaça mazmuny boş galdyran mahalym maňa ýatlat',
'tog-watchlisthideown'        => 'Gözegçilik sanawymdan öz özgerdişlerimi gizle',
'tog-watchlisthidebots'       => 'Gözegçilik sanawymdan bot özgerdişlerini gizle',
'tog-watchlisthideminor'      => 'Gözegçilik sanawymdan ujypsyzja özgerdişleri gizle',
'tog-watchlisthideliu'        => 'Gözegçilik sanawymda, sessiýa açan ulanyjylar tarapyndan edilen özgerdişleri görkezme',
'tog-watchlisthideanons'      => 'Gözegçilik sanawymda, anonim ulanyjylar tarapyndan edilen özgerdişleri görkezme',
'tog-watchlisthidepatrolled'  => 'Gözegçilik sanawymdan patrullyk edilen özgerdişleri gizle',
'tog-ccmeonemails'            => 'Beýleki ulanyjylara iberen e-poçtalarymyň nusgalaryny maňa-da iber',
'tog-diffonly'                => 'Sahypanyň mazmunyny wersiýa aratapawutlarynyň aşagynda görkezme',
'tog-showhiddencats'          => 'Gizlin kategoriýalary görkez',
'tog-norollbackdiff'          => '"Öňki katdyna getir" berjaý edilensoň wersiýalaryň aratapawudyny görkezme',

'underline-always'  => 'Hemişe',
'underline-never'   => 'Hiç haçan',
'underline-default' => 'Brauzeriň sazlamalary',

# Font style option in Special:Preferences
'editfont-style'     => 'Özgerdiş meýdançasynyň şriftiniň tipi:',
'editfont-default'   => 'Brauzeriň sazlan şrifti',
'editfont-monospace' => 'Mono-inli şrift',
'editfont-sansserif' => 'Sans-serif şrifti',
'editfont-serif'     => 'Serif şrifti',

# Dates
'sunday'        => 'Ýekşenbe',
'monday'        => 'Duşenbe',
'tuesday'       => 'Sişenbe',
'wednesday'     => 'Çarşenbe',
'thursday'      => 'Penşenbe',
'friday'        => 'Anna',
'saturday'      => 'Şenbe',
'sun'           => 'Ýek',
'mon'           => 'Duş',
'tue'           => 'Siş',
'wed'           => 'Çar',
'thu'           => 'Pen',
'fri'           => 'Ann',
'sat'           => 'Şen',
'january'       => 'ýanwar',
'february'      => 'fewral',
'march'         => 'mart',
'april'         => 'aprel',
'may_long'      => 'maý',
'june'          => 'iýun',
'july'          => 'iýul',
'august'        => 'awgust',
'september'     => 'sentýabr',
'october'       => 'oktýabr',
'november'      => 'noýabr',
'december'      => 'dekabr',
'january-gen'   => 'ýanwar',
'february-gen'  => 'fewral',
'march-gen'     => 'mart',
'april-gen'     => 'aprel',
'may-gen'       => 'maý',
'june-gen'      => 'iýun',
'july-gen'      => 'iýul',
'august-gen'    => 'awgust',
'september-gen' => 'sentýabr',
'october-gen'   => 'oktýabr',
'november-gen'  => 'noýabr',
'december-gen'  => 'dekabr',
'jan'           => 'ýan',
'feb'           => 'few',
'mar'           => 'mar',
'apr'           => 'apr',
'may'           => 'maý',
'jun'           => 'iýun',
'jul'           => 'iýul',
'aug'           => 'awg',
'sep'           => 'sen',
'oct'           => 'okt',
'nov'           => 'noý',
'dec'           => 'dek',

# Categories related messages
'pagecategories'                 => 'Sahypanyň {{PLURAL:$1|kategoriýasy|kategoriýalary}}',
'category_header'                => '"$1" kategoriýasyndaky sahypalar',
'subcategories'                  => 'Kiçi kategoriýalar',
'category-media-header'          => '"$1" kategoriýasyndaky multimediýa faýllary',
'category-empty'                 => "''Bu kategoriýada heniz hiçhili sahypa ýa-da multimediýa faýly ýok.''",
'hidden-categories'              => '{{PLURAL:$1|Gizlin kategoriýa|Gizlin kategoriýalar}}',
'hidden-category-category'       => 'Gizlin kategoriýalar',
'category-subcat-count'          => '{{PLURAL:$2|Bu kategoriýa diňe aşakdaky kiçi kategoriýany öz içine alýar.|Bu kategoriýada jemi $2 sany 
kiçi kategoriýadan {{PLURAL:$1|sany kiçi kategoriýa|$1 sany kiçi kategoriýa}} bardyr}}',
'category-subcat-count-limited'  => 'Bu kategoriýada aşakdaky {{PLURAL:$1|sany kiçi kategoriýa|$1 sany kiçi kategoriýa}} bardyr.',
'category-article-count'         => '{{PLURAL:$2|Bu kategoriýa diňe aşakdaky sahypany öz içine alýar.|Jemi $2 sanysyndan, aşakdaky {{PLURAL:$1|sahypa|$1 sahypa}} bu kategoriýadadyr.}}',
'category-article-count-limited' => 'Aşakdaky {{PLURAL:$1|sahypa|$1 sahypa}} şu kategoriýadadyr.',
'category-file-count'            => '{{PLURAL:$2|Bu kategoriýa diňe aşakdaky faýly öz içine alýar.|Jemi $2 sanydan, aşakdaky {{PLURAL:$1|faýl|$1 faýl}} şu kategoriýadadyr.}}',
'category-file-count-limited'    => 'Aşakdaky {{PLURAL:$1|faýl|$1 faýl}} şu kategoriýadadyr.',
'listingcontinuesabbrev'         => '(dowamy)',

'mainpagetext'      => "<big>'''MediaWiki şowlulyk bilen guruldy.'''</big>",
'mainpagedocfooter' => 'Wiki programmasynyň ulanylyşy hakynda maglumat almak üçin [http://meta.wikimedia.org/wiki/Help:Contents ulanyjy gollanmasyna] serediň.

== Öwrenjeler ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Konfigurasiýa sazlamalary]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki SSS]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki e-poçta sanawy]',

'about'         => 'Hakynda',
'article'       => 'Makala',
'newwindow'     => '(täze penjirede açylýar)',
'cancel'        => 'Goýbolsun et',
'moredotdotdot' => 'Has köp...',
'mypage'        => 'Sahypam',
'mytalk'        => 'Pikir alyşma sahypam',
'anontalk'      => 'Bu IP-niň habarlaşyklary',
'navigation'    => 'Saýtda ugur kesgitleme',
'and'           => '&#32;we',

# Cologne Blue skin
'qbfind'         => 'Tap',
'qbbrowse'       => 'Göz aýla',
'qbedit'         => 'Redaktirle',
'qbpageoptions'  => 'Bu sahypa',
'qbpageinfo'     => 'Kontekst',
'qbmyoptions'    => 'Meniň sahypalarym',
'qbspecialpages' => 'Ýörite sahypalar',
'faq'            => 'KSS',
'faqpage'        => 'Project:KSS',

# Vector skin
'vector-action-addsection'   => 'Tema goş',
'vector-action-delete'       => 'Öçür',
'vector-action-move'         => 'Adyny üýtget',
'vector-action-protect'      => 'Goraga al',
'vector-action-undelete'     => 'Öçürmäni yzyna al',
'vector-action-unprotect'    => 'Goragy aýyr',
'vector-namespace-category'  => 'Kategoriýa',
'vector-namespace-help'      => 'Ýardam sahypasy',
'vector-namespace-image'     => 'Faýl',
'vector-namespace-main'      => 'Sahypa',
'vector-namespace-media'     => 'Media sahypasy',
'vector-namespace-mediawiki' => 'Habarlaşyk',
'vector-namespace-project'   => 'Taslama sahypasy',
'vector-namespace-special'   => 'Ýörite sahypa',
'vector-namespace-talk'      => 'Çekişme',
'vector-namespace-template'  => 'Şablon',
'vector-namespace-user'      => 'Ulanyjy sahypasy',
'vector-view-create'         => 'Döret',
'vector-view-edit'           => 'Redaktirle',
'vector-view-history'        => 'Geçmişi gör',
'vector-view-view'           => 'Oka',
'vector-view-viewsource'     => 'Çeşmäni gör',
'actions'                    => 'Hereketler',
'namespaces'                 => 'At giňişlikleri',
'variants'                   => 'Wariantlar',

# Metadata in edit box
'metadata_help' => 'Meta-maglumat:',

'errorpagetitle'    => 'Säwlik',
'returnto'          => '$1.',
'tagline'           => '{{SITENAME}} saýtyndan',
'help'              => 'Ýardam',
'search'            => 'Gözle',
'searchbutton'      => 'Gözle',
'go'                => 'Git',
'searcharticle'     => 'Git',
'history'           => 'Sahypanyň geçmişi',
'history_short'     => 'Geçmiş',
'updatedmarker'     => 'soňky gezek görelim bäri täzelenen',
'info_short'        => 'Maglumat',
'printableversion'  => 'Print ediş wersiýasy',
'permalink'         => 'Hemişelik çykgyt',
'print'             => 'Print et',
'edit'              => 'Redaktirle',
'create'            => 'Döret',
'editthispage'      => 'Bu sahypany redaktirle',
'create-this-page'  => 'Bu sahypany döret',
'delete'            => 'Öçür',
'deletethispage'    => 'Bu sahypany öçür',
'undelete_short'    => '{{PLURAL:$1|özgerdişi|$1 özgerdişi}} yzyna getir',
'protect'           => 'Goraga al',
'protect_change'    => 'üýtget',
'protectthispage'   => 'Sahypany gorag astyna al',
'unprotect'         => 'Goragy aýyr',
'unprotectthispage' => 'Sahypanyň goragyny aýyr',
'newpage'           => 'Täze sahypa',
'talkpage'          => 'Sahypany ara alyp maslahatlaş',
'talkpagelinktext'  => 'Çekişme',
'specialpage'       => 'Ýörite Sahypa',
'personaltools'     => 'Şahsy gurallar',
'postcomment'       => 'Täze bölüm',
'articlepage'       => 'Makalany görkez',
'talk'              => 'Çekişme',
'views'             => 'Keşpler',
'toolbox'           => 'Gural sandygy',
'userpage'          => 'Ulanyjy sahypasyny görkez',
'projectpage'       => 'Taslama sahypasyny görkez',
'imagepage'         => 'Faýl sahypasyny görkez',
'mediawikipage'     => 'Habarlaşyk sahypasy görkez',
'templatepage'      => 'Şablon sahypasyny gör',
'viewhelppage'      => 'Ýardam sahypasyny gör',
'categorypage'      => 'Kategoriýa sahypasyny gör',
'viewtalkpage'      => 'Çekişme sahypasyny görkez',
'otherlanguages'    => 'Başga dillerde',
'redirectedfrom'    => '($1 sahypasyndan gönükdirildi)',
'redirectpagesub'   => 'Gönükdirme sahypasy',
'lastmodifiedat'    => 'Bu sahypa iň soňky gezek $2, $1 senesinde üýtgedildi.',
'viewcount'         => 'Bu sahypa {{PLURAL:$1|bir|$1 }} gezek görülipdir.',
'protectedpage'     => 'Goragly sahypa',
'jumpto'            => 'Git we:',
'jumptonavigation'  => 'ulan',
'jumptosearch'      => 'gözle',
'view-pool-error'   => 'Gynansak-da, şu wagt serwerler hetdenaşa işli.
Biçak köp ulanyjy şu sahypany görmäge synanyşýar.
Bir sellem garaşyp, soňra synanyşmagyňyzy towakga edýäris. 

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} hakynda',
'aboutpage'            => 'Project:Hakynda',
'copyright'            => 'Mazmun $1 esasynda elýeterlidir.',
'copyrightpage'        => '{{ns:project}}:Awtorlyk hukugy',
'currentevents'        => 'Oba güzeri',
'currentevents-url'    => 'Project:Oba güzeri',
'disclaimers'          => 'Jogapkärçilikden boýun gaçyrma',
'disclaimerpage'       => 'Project:Umumy jogapkärçilikden boýun gaçyrma',
'edithelp'             => 'Nähili redaktirlenýär?',
'edithelppage'         => 'Help:Redaktirleme',
'helppage'             => 'Help:Içindäkiler',
'mainpage'             => 'Baş Sahypa',
'mainpage-description' => 'Baş Sahypa',
'policy-url'           => 'Project:Ýörelge',
'portal'               => 'Çaýhana',
'portal-url'           => 'Project:Çaýhana',
'privacy'              => 'Gizlinlik ýörelgesi',
'privacypage'          => 'Project:Gizlinlik ýörelgesi',

'badaccess'        => 'Rugsat säwligi',
'badaccess-group0' => 'Talap edýän bu işiňizi ýerine ýetirmäge size ygtyýar berilmeýär.',
'badaccess-groups' => 'Bu işi diňe {{PLURAL:$2|toparyndaky|toparyndaky}}: $1  ulanyjylardan biri amala aşyryp bilýär.',

'versionrequired'     => 'MediaWikiniň $1 wersiýasy gerek',
'versionrequiredtext' => 'Version $1 of MediaWiki is required to use this page.
See [[Special:Version|version page]].
Bu sahypany ulanmak üçin MediaWikiniň $1 wersiýasy talap edilýär. [[Special:Version|Wersiýa sahypasyna]] serediň.',

'ok'                      => 'OK',
'retrievedfrom'           => '"$1" adresinden alyndy.',
'youhavenewmessages'      => 'Size <u>$1</u> bar. ($2)',
'newmessageslink'         => 'täze habarlaşyk',
'newmessagesdifflink'     => 'soňky üýtgeşme',
'youhavenewmessagesmulti' => 'Size $1-de täze habarlaşyk bar.',
'editsection'             => 'redaktirle',
'editold'                 => 'redaktirle',
'viewsourceold'           => 'çeşmäni gör',
'editlink'                => 'redaktirle',
'viewsourcelink'          => 'çeşmesini gör',
'editsectionhint'         => '$1 bölümini redaktirle',
'toc'                     => 'Mazmuny',
'showtoc'                 => 'görkez',
'hidetoc'                 => 'gizle',
'thisisdeleted'           => '$1 görmek ýa-da dikeltmek isleýärsiňizmi?',
'viewdeleted'             => '$1 gör?',
'restorelink'             => '{{PLURAL:$1|bir öçürilen özgerdişi|$1 öçürilen özgerdişi}}',
'feedlinks'               => 'Lenta:',
'feed-invalid'            => 'Nädogry ýazylyşyk kanaly görnüşi.',
'feed-unavailable'        => 'Sindikasiýa lentalary elýeterli däl',
'site-rss-feed'           => '$1 RSS lentasy',
'site-atom-feed'          => '$1 Atom lentasy',
'page-rss-feed'           => '"$1" RSS lentasy',
'page-atom-feed'          => '"$1" Atom lentasy',
'red-link-title'          => '$1 (bu sahypa heniz ýazylmandyr)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Sahypa',
'nstab-user'      => 'Ulanyjy sahypasy',
'nstab-media'     => 'Multimediýa',
'nstab-special'   => 'Ýörite sahypa',
'nstab-project'   => 'Taslama sahypasy',
'nstab-image'     => 'Faýl',
'nstab-mediawiki' => 'Interfeýs teksti',
'nstab-template'  => 'şablon',
'nstab-help'      => 'Ýardam sahypasy',
'nstab-category'  => 'Kategoriýa',

# Main script and global functions
'nosuchaction'      => 'Şeýle iş ýok',
'nosuchactiontext'  => 'URL tarapyndan görkezilen iş nädogry.
URL-ni ýalňyş ýazan, ýa-da nädogry çykgydy yzarlan bolmagyňyz ahmal.
Ol {{SITENAME}} saýtyndaky bir näsazlygy hem görkezýän bolup biler.',
'nosuchspecialpage' => 'Şeýle atly ýörite sahypa ýok',
'nospecialpagetext' => "<big>'''Bar bolmadyk bir ýörite sahypa girdiňiz.'''</big> 

Bar bolan ähli ýörite sahypalary [[Special:SpecialPages|ýörite sahypalar]] sahypasynda görüp bilersiňiz.",

# General errors
'error'                => 'Säwlik',
'databaseerror'        => 'Maglumat bazasynyň säwligi',
'dberrortext'          => 'Maglumat bazasy gözleginde sintaksis säwligi ýüze çykdy.
Onuň programmadaky bir säwlik bolmagy ahmal.
"<tt>$2</tt>" funksiýasyndan synalyp görülen iň soňky maglumat bazasy gözlegi:
<blockquote><tt>$1</tt></blockquote>.
Maglumat bazasy tarapyndan yzyna gaýtarylan säwlik "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Maglumat bazasy gözleginde sintaksis säwligi ýüze çykdy.
Iň soňky maglumat bazasy gözlegi:
"$1"
Ulanylan funksiýa "$2".
Maglumat bazasy tarapyndan yzyna gaýtarylan säwlik "$3: $4"',
'laggedslavemode'      => 'Duýduryş: Sahypada soňky täzelemeler ýok bolmagy ahmal.',
'readonly'             => 'Maglumat bazasy gulplandy',
'enterlockreason'      => 'Gulplamak üçin bir sebäp görkeziň. Gulpuň haçan açyljakdygy barada takmynan bir sene ýazyň.',
'readonlytext'         => 'Maglumat bazasyndaky adaty abatlaýyş hyzmatlary zerarly, wagtlaýynça täze makala goşmaklyk hem-de üýtgeşme girizmeklik gulplandy. Gysga salymyň içinde öňküsi ýaly bolar.

Maglumat bazasyny gulplan administratoryň düşündirişi: $1',
'missing-article'      => 'Maglumat bazasy tapylmagy talap edilýän "$1" $2 atly sahypa degişli teksti tapyp bilmedi.

Bu ýagdaý sahypanyň öçürilen bir sahypanyň ozalky wersiýasy bolmaklygyndan ýüze çykýan bolup biler.

Eger sebäp ol däl bolsa, programma serişdesinde bir säwlige duşan bolmagyňyz ahmal. 
Muny bir [[Special:ListUsers/sysop|administratora]] URL-ni belläp alyp ýetirmekligiňizi haýyş edýäris.',
'missingarticle-rev'   => '(wersiýa#: $1)',
'missingarticle-diff'  => '(Tapawut: $1, $2)',
'readonly_lag'         => 'Ätiýaçlyk serwerler esasy serwere boýunça täzelenýärkä, maglumat bazasy awtomatik usulda gulplandy.',
'internalerror'        => 'Içerki säwlik',
'internalerror_info'   => 'Içerki säwlik: $1',
'fileappenderror'      => '"$1" faýlyny "$2" faýlyna goşup bolmady.',
'filecopyerror'        => '"$1" faýlyny  "$2" faýlyna göçürip bolmady.',
'filerenameerror'      => '"$1" faýlynyň adyny "$2" diýip üýtgedip bolmady.',
'filedeleteerror'      => '"$1" faýlyny öçürip bolmady.',
'directorycreateerror' => '"$1" direktoriýasyny döredip bolmady',
'filenotfound'         => '
"$1" faýly tapylmady.',
'fileexistserror'      => '"$1" faýlyna ýazyp bolmady: faýl onsuzam bar',
'unexpected'           => 'Garaşylmadyk baha: "$1"="$2".',
'formerror'            => 'Säwlik: formy iberip bolmady',
'badarticleerror'      => 'Bu işi bu sahypada amala aşyryp bolmaýar.',
'cannotdelete'         => 'Görkezilen sahypany ýa-da faýly öçürip bolmady. 
Başga bir ulanyjy tarapyndan eýýäm öçürilen bolmagy mümkin.',
'badtitle'             => 'Ýolbererliksiz at',
'badtitletext'         => 'Isleýän sahypaňyzyň ady ýa nädogry ýa-da boş. Ýa-da bolmasa dilara ýa-da wikiara çykgyt nädogry berlipdir. Içinde atlarda ulanylmagy gadagan simwollardan biri ýa-da birnäçesi bar bolmagy ahmal.',
'perfcached'           => 'Aşakdaky maglumatlar keşirlenen bolup, könelişen bolmaklary mümkin!',
'perfcachedts'         => 'Aşakdaky maglumat keşirlenen bolup, iň soňky täzelenen wagty: $1',
'querypage-no-updates' => 'Häzirlikçe bu sahypanyň täzelenmegi togtadyldy. Bu ýerdäki maglumatlar häzirlikçe täzelenmeýär.',
'wrong_wfQuery_params' => 'wfQuery() funksiýasyna nädogry parametrler<br />
Funksiýa: $1<br />
Talap: $2',
'viewsource'           => 'Çeşmäni gör',
'viewsourcefor'        => '$1 üçin',
'actionthrottled'      => 'Hereket çäklendirildi',
'actionthrottledtext'  => 'Anti-spam çäresi hökmünde, bu işi az salymyň içinde köp gezek amala aşyrmagyňyz çäklendirildi, we siz bu çäklendirmeden öte geçdiňiz.
Ýene birnäçe minutdan gaýtadan synanyşyp görmegiňizi haýyş edýäris.',
'protectedpagetext'    => 'Bu sahypa redaktirlenmezligi üçin gulp astynda dur.',
'viewsourcetext'       => 'Bu sahypanyň çeşmesini görüp hem-de göçürip bilersiňiz:',
'protectedinterface'   => 'Bu sahypa programma üçin interfeýs tekstini üpjün edýär. Bet niýetli hüjümlerden goramak maksady bilen gulp astyna alnandyr.',
'editinginterface'     => "'''Duýduryş:''' Programma üçin interfeýs tekstini üpjün etmekte ulanylýan bir sahypany redaktirleýärsiňiz. Bu sahypada ediljek üýtgeşmeler beýleki ulanyjylar üçin ulanyjy interfeýsiniň daşky görnüşini üýtgedýändir. Terjimeler üçin, MediaWikiniň lokalizasiýa taslamasy bolan [http://translatewiki.net/wiki/Main_Page?setlang=tr translatewiki.net]i ulanmaklygyňyzy haýyş edýäris.",
'sqlhidden'            => '(SQL gizlin talap)',
'cascadeprotected'     => 'Bu sahypa redaktirlenmekden goralýar, sebäbi ol "kaskadly" opsiýasy işledilip gorag astyna alnan {{PLURAL:$1|sahypada|sahypada}} ulanylýar:
$2',
'namespaceprotected'   => "Siziň '''$1''' giňişligindäki sahypalary redaktirlemäge rugsadyňyz ýok.",
'customcssjsprotected' => 'Bu sahypany redaktirlemegiňize rugsat berilmeýär, çünki ol başga bir ulanyjynyň şahsy sazlamalaryny öz içine alýar.',
'ns-specialprotected'  => '{{ns:special}} at giňişligindäki sahypalary redaktirläp bolmaýar.',
'titleprotected'       => "[[User:$1|$1]] tarapyndan döredilmeginiň öňüni almak maksady bilen bu sahypa gorag astyna alyndy.
Görkezilen sebäp: ''$2''.",

# Virus scanner
'virus-badscanner'     => "Nädogry konfigurasiýa: näbelli wirus skaneri: ''$1''",
'virus-scanfailed'     => 'skanirleme başa barmady (kod $1)',
'virus-unknownscanner' => 'nätanyş antiwirus:',

# Login and logout pages
'logouttext'                 => "'''Sessiýany ýapdyňyz.'''
Indi anonim ýagdaýda {{SITENAME}} saýtyny ulanyp bilersiňiz, ýa-da şol bir ýa-da başga bir at bilen [[Special:UserLogin|sessiýany ýaňadan]] açyp bilersiňiz. Web brauzeriňiziň keşini arassalaýançaňyz käbir sahypalar sessiýaňyzyň açyk wagtkysy ýaly görünip biler.",
'welcomecreation'            => '== Hoş geldiňiz, $1! ==

Hasabyňyz açyldy.  
[[Special:Preferences|{{SITENAME}} saýtyndaky sazlamalaryňyzy]] üýtgetmegi ýatdan çykarmaň.',
'yourname'                   => 'Ulanyjy adyňyz:',
'yourpassword'               => 'Parolyňyz:',
'yourpasswordagain'          => 'Paroly gaýtadan ýaz:',
'remembermypassword'         => 'Parolymy ýatda sakla',
'yourdomainname'             => 'Siziň domeniňiz:',
'externaldberror'            => 'Ýa tassyklama maglumat bazasynyň säwligi bar ýa-da öz ulanyjy hasabyňyzy täzelemegiňize rugsat berilmeýär.',
'login'                      => 'Sessiýa aç',
'nav-login-createaccount'    => 'Sessiýa aç / täze hasap edin',
'loginprompt'                => '{{SITENAME}} saýtynda sessiýa açmak üçin kukileri işletmegiňiz zerurdyr.',
'userlogin'                  => 'Sessiýa aç / täze hasap edin',
'logout'                     => 'Sessiýany ýap',
'userlogout'                 => 'Sessiýany ýap',
'notloggedin'                => 'Sessiýa açyk däl',
'nologin'                    => 'Siziň heniz hasabyňyz ýokmy? $1',
'nologinlink'                => 'Onda özüňize bir hasap ediniň',
'createaccount'              => 'Täze hasap aç',
'gotaccount'                 => 'Eýýäm hasap açdyňyzmy? $1.',
'gotaccountlink'             => 'Onda giriberiň!',
'createaccountmail'          => 'e-poçta bilen',
'badretype'                  => 'Girizen parollaryňyz biri-birine gabat gelmeýär.',
'userexists'                 => 'Girizen ulanyjy adyňyz ulanylýar. 
Başga bir at saýlamagyňyzy haýyş edýäris.',
'loginerror'                 => 'Sessiýa açyş säwligi',
'nocookiesnew'               => 'Ulanyjy hasaby döredildi, ýöne sessiýa açmadyňyz.
{{SITENAME}} sessiýa açmak üçin kukilerden peýdalanýar.
Kukileriňiz togtadylgy dur.
Olary işletmegiňizi, soňra bolsa täze ulanyjy adyňyz we parolyňyz bilen sessiýa açmagyňyzy haýyş edýäris.',
'nocookieslogin'             => '{{SITENAME}} sessiýa açmak üçin kukilerden peýdalanýar.
Siziň kukileriňiz togtadylgy dur.
Olary işletmegiňizi we gaýtadan synanyşyp görmegiňizi haýyş edýäris.',
'noname'                     => 'Dogry bir ulanyjy adyny görkezmediňiz.',
'loginsuccesstitle'          => 'Sessiýa açyldy',
'loginsuccess'               => "'''{{SITENAME}} saýtynda \"\$1\" ulanyjy ady bilen sessiýa açdyňyz.'''",
'nosuchuser'                 => '"$1" diýen at bilen ulanyjy ýok.
Ulanyjy atlary baş hem-de setir harplara duýgurdyr.
Ýazylyşyny barlaň ýa-da [[Special:UserLogin/signup|täze hasap açyň]].',
'nosuchusershort'            => '"<nowiki>$1</nowiki>" dýen at bilen ulanyjy ýok. Ýazylyşyny barlaň.',
'nouserspecified'            => 'Ulanyjy adyny görkezmegiňiz hökmanydyr.',
'wrongpassword'              => 'Paroly nädogry girizdiňiz. Gaýtadan synanyşmagyňyzy haýyş edýäris.',
'wrongpasswordempty'         => 'Paroly boş girizdiňiz. Gaýtadan synanyşmagyňyzy haýyş edýäris.',
'passwordtooshort'           => 'Parollar iň bolmanda {{PLURAL:$1|1 simwoldan|$1 simwoldan}} ybarat bolmalydyr.',
'password-name-match'        => 'Parolyňyz ulanyjy adyňyzdan tapawutly bolmalydyr.',
'mailmypassword'             => 'Maňa e-poçta bilen täze parol iber',
'passwordremindertitle'      => '{{SITENAME}} üçin täze wagtlaýyn parol',
'passwordremindertext'       => 'Kimdir biri (ähtimal özüňiz $1 IP adresinden) {{SITENAME}} ($4) üçin täze parol iberilmegini talap etdi. "$2" ulanyjysy üçin wagtlaýynça "$3" paroly döredildi. Eger bu siziň öz talabyňyz bolsa, onda sessiýa açyp, bir täze parol saýlap almagyňyz zerurdyr. Wagtlaýyn parolyňyzyň möhleti {{PLURAL:$5|1 günden|$5 günden}} gutarjakdyr.

Parol üýtgetmekligi siz talap etmedik bolsaňyz ýa-da parolyňyz ýadyňyza düşen bolsa hem-de indi parolyňyzy üýtgedesiňiz gelmeýän bolsa, onda bu habarlaşyga ähmiýet bermän öňki parolyňyzy ulanmaklygy dowam etdirip bilersiňiz.',
'noemail'                    => '"$1" atly ulanyjynyň hasabynda duran hiç hili e-poçta adresi ýok.',
'passwordsent'               => '"$1" ulanyjysynyň hasabynda duran e-poçta adresine täze parol iberildi. Paroly alanyňyzdan soňra sessiýany ýaňadan açmagyňyzy haýyş edýäris.',
'blocked-mailpassword'       => 'IP adresiňiz blokirlenipdir, şonuň üçinem parol dikeldiş funksiýasyny ulanmaklyga rugsat berilmeýär.',
'eauthentsent'               => 'Görkezilen adrese tassyklama e-poçtasy iberildi.
E-poçtadaky görkezmeleri berjaý edip, adresiň özüňize degişlidigini tassyklaýançaňyz, başga e-poçta iberiljek däldir.',
'throttled-mailpassword'     => 'Parol ýatladyjy soňky {{PLURAL:$1|bir sagadyň|$1 sagadyň}} içinde eýýäm iberildi.
Bu hyzmatyň erbet niýetli ulanylmagynyň öňüni almak üçin, her {{PLURAL:$1|bir sagatda|$1 sagatdan}} diňe ýekeje parol ýatladyjysy iberiljekdir.',
'mailerror'                  => 'E-poçta iberiş säwligi: $1',
'acct_creation_throttle_hit' => 'Siziň IP adresiňizi ulanyp, bu wikini açan ulanyjylar iň soňky gün {{PLURAL:$1|1 hasap|$1 hasap}} döretdi. Bu san şol wagt aralygynda rugsat berilýän maksimum mukdar bolup durýar.
Netijede, bu IP adresini ulanyjylar häzirki pursatda mundan artyk hasap açyp bilmeýärler.',
'emailauthenticated'         => 'E-poçta adresiňiz $2 $3 senesinde tassyklanyldy.',
'emailnotauthenticated'      => 'E-poçta adresiňiz heniz tassyklanmady.
Aşakdaky funksiýalaryň hiç biri üçin e-poçta iberiljek däldir.',
'noemailprefs'               => 'Bu funksiýalaryň işlemegi üçin sazlamalaryňyzda bir e-poçta adresi görkeziň.',
'emailconfirmlink'           => 'E-poçta adresiňizi tassyklaň',
'invalidemailaddress'        => 'Nädogry formatda ýazylandygy üçin bu e-poçta adresini kabul edip bolmaýar.
Dogry formatda e-poçta adresi ýazmagyňyzy ýa-da bu bölümi boş goýmagyňyzy haýyş edýäris.',
'accountcreated'             => 'Hasap döredildi',
'accountcreatedtext'         => '$1 üçin ulanyjy hasaby döredildi.',
'createaccount-title'        => '{{SITENAME}} üçin täze ulanyjy hasabynyň döredilmegi',
'createaccount-text'         => 'Kimdir biri {{SITENAME}} saýtynda ($4) siziň e-poçta adresiňizi ulanyp, paroly "$3" bolan, "$2" atly bir hasap döretdi.

Saýtda sessiýaňyzy açyň we parolyňyzy üýtgediň.

Eger-de ulanyjy hasabyny ýalňyşlyk bilen döreden bolsaňyz, onda bu habarlaşyga ünsem berip oturmaň.',
'login-throttled'            => 'Bu hasabyň paroly boýunça ýaňy-ýakynda aşa köp synanyşyk etdiňiz.
Gaýtadan synanyşmankaňyz garaşmagyňyzy haýyş edýäris.',
'loginlanguagelabel'         => 'Dil: $1',

# Password reset dialog
'resetpass'                 => 'Paroly üýtget',
'resetpass_announce'        => 'Size iberilen wagtlaýyn parol bilen sessiýa açdyňyz.
Sessiýa açmaklygy tamamlamak üçin, bu ýere täze parol ýazyň:',
'resetpass_header'          => 'Hasap parolyny üýtget',
'oldpassword'               => 'Köne parol:',
'newpassword'               => 'Täze parol:',
'retypenew'                 => 'Täze paroly gaýtadan ýaz:',
'resetpass_submit'          => 'Paroly sazlaň we sessiýa açyň',
'resetpass_success'         => 'Parolyňyz şowlulyk bilen üýtgedildi! Häzir sessiýaňyz açylýar...',
'resetpass_forbidden'       => 'Parollary üýtgedip bolmaýar',
'resetpass-no-info'         => 'Bu sahypany gönüden-göni açmak üçin sessiýa açmagyňyz zerurdyr.',
'resetpass-submit-loggedin' => 'Paroly üýtget',
'resetpass-wrong-oldpass'   => 'Nädogry wagtlaýyn ýa-da häzirki parol.
Parolyňyzy eýýäm şowlulyk bilen üýtgeden ýa-da täze wagtlaýyn parol talap eden bolmagyňyz ahmal.',
'resetpass-temp-password'   => 'Wagtlaýyn parol:',

# Edit page toolbar
'bold_sample'     => 'Goýy tekst',
'bold_tip'        => 'Goýy tekst',
'italic_sample'   => 'Kursiw tekst',
'italic_tip'      => 'Kursiw tekst',
'link_sample'     => 'Çykgydyň ady',
'link_tip'        => 'Içerki çykgyt',
'extlink_sample'  => 'http://www.example.com çykgyt düşündirişi',
'extlink_tip'     => 'Daşarky çykgyt (Adresiň öňüne http:// ýazmagy unutmaň)',
'headline_sample' => 'Adyň teksti',
'headline_tip'    => '2-nji derejeli at',
'math_sample'     => 'Matematiki formulany şu ýere ýazyň',
'math_tip'        => 'Matematiki formula (LaTeX formatynda)',
'nowiki_sample'   => 'Erkin formatdaky tekstiňizi şu ýere ýazyň',
'nowiki_tip'      => 'Wiki formatirlemesini hasap etme',
'image_tip'       => 'Surat goşmak',
'media_tip'       => 'Multimediýa faýlyna çykgyt',
'sig_tip'         => 'Goluňyz we senesi',
'hr_tip'          => 'Gorizontal liniýa (ýygy-ýygydan ulanmaň)',

# Edit pages
'summary'                          => 'Gysgaça mazmuny:',
'subject'                          => 'Tema/at:',
'minoredit'                        => 'Ujypsyzja özgerdiş',
'watchthis'                        => 'Sahypany gözegçilikde sakla',
'savearticle'                      => 'Sahypany ýazdyr',
'preview'                          => 'Deslapky syn',
'showpreview'                      => 'Deslapky syny görkez',
'showlivepreview'                  => 'Gönümel deslapky syn',
'showdiff'                         => 'Üýtgeşmeleri görkez',
'anoneditwarning'                  => "'''Duýduryş:''' Sessiýa açmansyňyz. Şonuň üçin hem IP adresiňiz bu sahypanyň özgerdişler geçmişine ýazylyp alynjakdyr.",
'missingsummary'                   => "'''Ýatlatma:''' Redaktirleme mazmunyny ýazmadyňyz.  
Sahypany ýazdyr düwmesine ýene bir gezek bassaňyz, özgerdişiňiz mazmunsyz ýazdyrylar.",
'missingcommenttext'               => 'Aşakda teswir ýazmagyňyzy haýyş edýäris.',
'missingcommentheader'             => "'''Ýatlatma:''' Bu teswir üçin tema/at görkezmediňiz. Eger Ýazdyr düwmesine gaýtadan bassaňyz, özgerdişiňiz temasyz/atsyz ýazdyrylar.",
'summary-preview'                  => 'Deslapky synyň mazmuny:',
'subject-preview'                  => 'Temanyň/adyň deslapky syny:',
'blockedtitle'                     => 'Ulanyjy blokirlenen',
'blockedtext'                      => '<big>\'\'\'Ulanyjy adyňyz ýa-da IP adresiňiz blokirlenipdir.\'\'\'</big>

Sizi blokirlän administrator: $1.<br />
Blokirlemäniň sebäbi: \'\'$2\'\'.

* Blokirlemäniň başlangyjy: $8
* Blokirlemäniň ahyry: $6
* Blokirleme möhleti: $7

Blokirleme barada pikir alyşmak üçin $1 ýa-da başga bir [[{{MediaWiki:Grouppage-sysop}}|administrator]] bilen habarlaşyp bilersiňiz. [[Special:Preferences|Sazlamalarym]] böleginde dogry bir e-poçta adresi görkezilmedik bolsa, "Ulanyja e-poçta iber" amalyndan peýdalanyp bilmersiňiz, ony ulanmaklygyňyz bolsa blokirlenmändir.   
Häzirki IP adresiňiz $3, blokirleme belgiňiz bolsa #$5.
Ýokaryda görkezilen ähli jikme-jiklikleri öz ähli ýüztutmalaryňyza girizmegiňizi haýyş edýäris.',
'autoblockedtext'                  => 'IP adresiňiz awtomatik ýagdaýda blokirlendi, çünki $1 tarapyndan blokirlenen başga bir ulanyjy ony ulanypdyr.  
Görkezilen sebäp şu:

:\'\'$2\'\'

* Blokirlemäniň başlangyjy: $8
* Blokirlemäniň ahyry: $6
* Blokirleme möhleti: $7

Blokirleme barada pikir alyşmak üçin $1 ýa-da başga bir [[{{MediaWiki:Grouppage-sysop}}|administrator]] bilen habarlaşyp bilersiňiz. 

Bellik: [[Special:Preferences|Ulanyjy sazlamalaryňyza]] dogry bir e-poçta adresini bellige aldyrmadyk bolsaňyz, "Ulanyja e-poçta iber" amalyndan peýdalanyp bilmersiňiz, ony ulanmaklygyňyz bolsa blokirlenmändir.   

Häzirki IP adresiňiz $3, blokirleme belgiňiz bolsa #$5.
Ýokaryda görkezilen ähli jikme-jiklikleri ähli ýüztutmalaryňyza girizmegiňizi haýyş edýäris.',
'blockednoreason'                  => 'sebäp görkezilmedik',
'blockedoriginalsource'            => "'''$1''' sahypasynyň çeşmesi aşakda görkezilýär:",
'blockededitsource'                => "'''$1''' sahypasyndaky '''özgerdişleriňize''' degişli tekst aşakdadyr:",
'whitelistedittitle'               => 'Redaktirlemek üçin sessiýa açmaly',
'whitelistedittext'                => 'Sahypa redaktirlemek üçin $1.',
'confirmedittext'                  => 'Sahypa redaktirlemäp başlamankaňyz e-poçta adresiňizi tassyklamalysyňyz. 
[[Special:Preferences|Ulanyjy sazlamalaryňyza]] e-poçta adresiňizi ýazyp tassyklamagyňyzy haýyş edýäris.',
'nosuchsectiontitle'               => 'Beýle bölüm ýok',
'nosuchsectiontext'                => 'Siz ýok bölümi redaktirlejek bolduňyz. $1 atly bölüm bolmansoň, özgerdişiňizi ýazdyrara ýer ýok.',
'loginreqtitle'                    => 'Sessiýa açmagyňyz zerur',
'loginreqlink'                     => 'sessiýa aç',
'loginreqpagetext'                 => 'Siz beýleki sahypalary görmek üçin $1 bolmaly.',
'accmailtitle'                     => 'Parol iberildi.',
'accmailtext'                      => "[[User talk:$1|$1]] üçin ugralla döredilen parol $2 adresine iberildi.

Bu paroly sessiýa açanyňyzdan soňra ''[[Special:ChangePassword|paroly üýtget]]'' sahypasynda üýtgedip bilersiňiz.",
'newarticle'                       => '(Täze)',
'newarticletext'                   => "Häzirlikçe ýazylmadyk bir sahypa goýlan çykgyda tykladyňyz. Bu sahypany döretmek üçin aşakdaky tekst gutusyndan peýdalanyň. Maglumat üçin [[{{MediaWiki:Helppage}}|ýardam sahypasyna]] serediň. Bu ýere ýalňyşlyk bilen gelen bolsaňyz, programmanyň '''Yza''' düwmesine tyklaň.",
'anontalkpagetext'                 => "----''Bu sahypa heniz ulanyjy hasaby edinmedik ýa-da hasabyny ulanmaýan bir anonim ulanyjynyň pikir alyşma sahypasydyr. 
Şonuň üçinem biz ony görkezmek üçin sanlaýyn IP adresini ulanmaly bolýarys. 
Şunuň ýaly IP adresinden ençeme ulanyjy peýdalanýan bolmagy ahmal. 
Eger-de sizem anonim ulanyjy bolsaňyz we size siziň bilen dahyly ýok habarlaşyklar gelýän bolsa, onda mundan beýläk başga anonim ulanyjylar bilen garjaşmazlygyňyz üçin [[Special:UserLogin/signup|özüňize hasap ediniň]] ýa-da [[Special:UserLogin|sessiýa açyň]].''",
'noarticletext'                    => 'Bu sahypa häzirki wagtda boş dur.
Bu ady başga sahypalarda [[Special:Search/{{PAGENAME}}|gözläp bilersiňiz]],
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{urlencode:{{FULLPAGENAME}}}}}} degişli gündeliklerde gözleg geçirip bilersiňiz],
ýa-da bu sahypany [{{fullurl:{{FULLPAGENAME}}|action=edit}} redaktirläp bilersiňiz]</span>.',
'userpage-userdoesnotexist'        => '"$1" ulanyjy hasaby hasaba alynmandyr. 
Bu sahypany döretmek/redaktirlemek isleýän bolsaňyz, onda esewan boluň.',
'clearyourcache'                   => "'''Bellik:''' Ýazdyranyňyzdan soň, üýtgeşmeleri görmek üçin brauzeriňiziň keşini arassalaň: 
'''Mozilla / Firefox / Safari:''' ''Shift''-e basyp, ''Reload''-a tyklaň ýa-da ýa ''Ctrl-F5''-e ýa-da ''Ctrl-R''-a basyň (Macintosh üçin ''Command-R'');
'''Konqueror:''' ''Reload''-a tyklaň ýa-da ''F5''-e basyň; 
'''Opera:''' ''Gurallar → Sazlamalar''dan keşi arassalaň;
'''Internet Explorer:''' ''Ctrl''-a basyp, ''Refresh''-i tyklaň ýa-da ''Ctrl-F5''-e basyň.",
'usercssyoucanpreview'             => "'''Ümleme:''' Täze CSS faýlyny ýazdyrmankaňyz, synap görmek üçin 'Deslapky syn' düwmesine basyň.",
'userjsyoucanpreview'              => "'''Ümleme:''' Täze JS faýlyny ýazdyrmankaňyz, synap görmek üçin 'Deslapky syn' düwmesine basyň.",
'usercsspreview'                   => "'''Bu ulanyjy CSS faýlyňyzyň ýöne bir deslapky syny.''' 
'''Ol heniz ýazdyrylan däldir!'''",
'userjspreview'                    => "'''Bu ulanyjy JavaScriptiňiziň ýöne bir barlagy/deslapky syny.''' 
'''Ol heniz ýazdyrylan däldir!'''",
'updated'                          => '(Täzelenen)',
'note'                             => "'''Bellik:'''",
'previewnote'                      => "'''Bu bir ýöne deslapky syn. Üýtgeşmeleriňiz heniz ýazdyrylan däldir!'''",
'editing'                          => '$1 sahypasyny redaktirleýärsiňiz',
'editingsection'                   => '"$1" sahypasynda bölüm redaktirleýärsiňiz',
'editingcomment'                   => '$1 redaktirlenýär (täze bölüm)',
'editconflict'                     => 'Özgerdiş çaknyşmasy: $1',
'yourtext'                         => 'Siziň tekstiňiz',
'yourdiff'                         => 'Aratapawutlar',
'copyrightwarning'                 => "'''Üns beriň:''' {{SITENAME}} saýtyna edilen ähli goşantlar <i>$2</i> ygtyýarnamasyna laýyklykdadyr (jikme-jiklikler üçin serediň:  $1).
Eden goşandyňyzyň başga ulanyjylar tarapyndan gypynç etmezden redaktirlenmegini ýa-da erkin hem-de çäklendirilmedik tertipde başga ýerlere paýlanmagyny islemeýän bolsaňyz, goşant etmäň.<br />
Mundan hem başga, siz bu ýere goşant goşmak bilen bu goşandyň özüňiz tarapyndan ýazylandygyna, ýa-da jemgyýetçilige açyk bir çeşmeden ýa-da başga bir erkin çeşmeden göçürilip alnandygyna güwä geçýärsiňiz.<br />
'''<center>AWTORLYK HUKUGY BOÝUNÇA GORALÝAN HIÇ BIR IŞI BU ÝERE BIRUGSAT GOŞMAŇ!</center>'''",
'templatesused'                    => 'Bu sahypada ulanylan şablonlar:',
'templatesusedpreview'             => 'Bu deslapky synda ulanylan şablonlar:',
'templatesusedsection'             => 'Şu bölümde ulanylan şablonlar:',
'template-protected'               => '(goragly)',
'template-semiprotected'           => '(ýarym goragly)',
'hiddencategories'                 => 'Bu sahypa {{PLURAL:$1|1 gizlin kategoriýa|$1 gizlin kategoriýa}} degişlidir:',
'nocreatetitle'                    => 'Sahypa döretmeklik çäklendirildi',
'nocreate-loggedin'                => 'Täze sahypa döretmäge rugsadyňyz ýok.',
'permissionserrorstext-withaction' => 'Aşakdaky {{PLURAL:$1|sebäp|sebäpler}} zerarly $2 ygtyýaryňyz ýok:',
'moveddeleted-notice'              => 'Bu sahypa öçürildi.
Sahypanyň öçürme we at üýtgetme gündelikleri salgylanma üçin aşakda görkezilendir.',
'log-fulllog'                      => 'Tutuş gündeligi görkez',
'edit-gone-missing'                => 'Sahypany täzeläp bolanok.
Öçürilen bolarly.',
'edit-conflict'                    => 'Özgerdiş çaknyşmasy.',
'edit-already-exists'              => 'Täze sahypa döredip bolanok.
Ol eýýäm bar.',

# Account creation failure
'cantcreateaccounttitle' => 'Hasap döredip bolmaýar',

# History pages
'viewpagelogs'           => 'Bu sahypanyň gündeliklerini görkez',
'nohistory'              => 'Bu sahypanyň özgerdişler geçmişi ýok.',
'currentrev'             => 'Häzirki wersiýa',
'currentrev-asof'        => '$1 senesinden başlap sahypanyň şu wagtky wersiýasy',
'revisionasof'           => 'Sahypanyň $1 senesindäki wersiýasy',
'revision-info'          => '$2 tarapyndan döredilen $1 seneli wersiýa',
'previousrevision'       => '← Ozalkysy',
'nextrevision'           => 'Indikisi →',
'currentrevisionlink'    => 'Häzirki wersiýany görkez',
'cur'                    => 'häzirki',
'next'                   => 'indiki',
'last'                   => 'öňündäki',
'page_first'             => 'ilkinji',
'page_last'              => 'soňky',
'histlegend'             => "Tapawut saýlama: deňeşdirmek isleýän 2 wersiýaňyzyň öňündäki tegelejiklere tyklap, enter-e basyň ýa-da sahypanyň iň aşagyndaky düwmä basyň.<br />
Kesgitleme: ('''häzirki''') = häzirki wersiýa bilen aradaky tapawut,
('''öňündäki''') = öň ýanyndaky wersiýa bilen aradaky tapawut, '''uj''' = Ujypsyzja özgerdiş.",
'history-fieldset-title' => 'Geçmişe göz aýla',
'histfirst'              => 'Iň irki',
'histlast'               => 'Ýaňy-ýakyndaky',
'historysize'            => '({{PLURAL:$1|1 baýt|$1 baýt}})',
'historyempty'           => '(boş)',

# Revision feed
'history-feed-item-nocomment' => '$1, $2 senesinde',

# Revision deletion
'rev-deleted-comment'    => '(teswir aýyryldy)',
'rev-deleted-user'       => '(ulanyjy ady aýyryldy)',
'rev-deleted-event'      => '(gündelik işi aýyryldy)',
'rev-delundel'           => 'görkez/gizle',
'revdelete-hide-text'    => 'Sahypanyň bu wersiýasyny gizle',
'revdelete-hide-comment' => 'Özgerdiş mazmunyny gizle',
'revdelete-hide-user'    => 'Redaktirläniň ulanyjy adyny/IP-sini gizle',
'revdelete-hide-image'   => 'Faýlyň mazmunyny gizle',
'revdelete-log'          => 'Öçürmekligiň sebäbi:',
'revdel-restore'         => 'Görnüşi üýtget',
'pagehist'               => 'Sahypanyň geçmişi',
'deletedhist'            => 'Öçürilen geçmiş',
'revdelete-content'      => 'mazmun',
'revdelete-summary'      => 'özgerdiş mazmuny',
'revdelete-uname'        => 'ulanyjy ady',
'revdelete-hid'          => 'gizle $1',
'revdelete-unhid'        => 'görkez $1',
'revdelete-log-message'  => '$2 {{PLURAL:$2|wersiýa|wersiýa}} üçin $1',
'logdelete-log-message'  => '$2 {{PLURAL:$2|waka|waka}} üçin $1',

# History merging
'mergehistory-from'   => 'Çeşme sahypa:',
'mergehistory-into'   => 'Maksat sahypa:',
'mergehistory-reason' => 'Sebäp:',

# Merge log
'revertmerge' => 'Böl',

# Diffs
'history-title'            => '"$1" sahypasynyň geçmişi',
'difference'               => '(Wersiýalaryň aratapawudy)',
'lineno'                   => 'Setir $1:',
'compareselectedversions'  => 'Saýlanan wersiýalary deňeşdir',
'showhideselectedversions' => 'Saýlanan wersiýalary görkez/gizle',
'editundo'                 => 'yzyna al',
'diff-movedto'             => '$1 sahypasyna göçürildi',
'diff-added'               => '$1 goşuldy',
'diff-changedto'           => '$1 edilip üýtgedildi',
'diff-src'                 => 'çeşme',
'diff-with'                => '&#32;$1 $2 bilen',
'diff-with-final'          => '&#32;we $1 $2',
'diff-width'               => 'in',
'diff-height'              => 'boý',
'diff-p'                   => "'''abzas'''",
'diff-blockquote'          => "'''sitata'''",
'diff-table'               => "'''tablisa'''",
'diff-tr'                  => "'''setir'''",
'diff-a'                   => "'''çykgyt'''",
'diff-del'                 => "'''öçürilen'''",

# Search results
'searchresults'                    => 'Gözleg netijeleri',
'searchresults-title'              => '"$1" üçin gözleg netijeleri',
'searchresulttext'                 => '{{SITENAME}} boýunça gözleg geçirmek barada jikme-jik maglumat almak üçin [[{{MediaWiki:Helppage}}|{{int:help}}]] sahypasyna göz aýlap bilersiňiz.',
'searchsubtitle'                   => '\'\'\'[[:$1]]\'\'\' üçin gözleg geçirdiňiz. ([[Special:Prefixindex/$1|"$1" bilen başlaýan ähli sahypalar]] {{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1"\' sahypasyna çykgydy bar bolan ähli sahypalar]])',
'searchsubtitleinvalid'            => "Gözlenen: '''$1'''",
'noexactmatch'                     => "'''\"\$1\" diýlip atlandyrylýan sahypa ýok.''' Bu sahypany siz [[:\$1|döredip bilersiňiz]].",
'noexactmatch-nocreate'            => "'''\"\$1\" atly hiç hili sahypa ýok.'''",
'notitlematches'                   => 'Şeýle atly sahypa gabat gelmedi',
'notextmatches'                    => 'Hiç bir sahypada tapylmady',
'prevn'                            => 'öňki {{PLURAL:$1|$1}}',
'nextn'                            => 'indiki {{PLURAL:$1|$1}}',
'prevn-title'                      => 'Öňki $1 {{PLURAL:$1|netije|netije}}',
'nextn-title'                      => 'Indiki $1 {{PLURAL:$1|netije|netije}}',
'shown-title'                      => 'Sahypa başyna $1 {{PLURAL:$1|netije|netije}} görkez',
'viewprevnext'                     => '($1) ($2) ($3).',
'searchmenu-legend'                => 'Gözleg opsiýalary',
'searchmenu-exists'                => "'''Bu wikide \"[[:\$1]]\" atly sahypa bar'''",
'searchmenu-new'                   => "'''Bu wikide \"[[:\$1]]\" sahypasyny döret!'''",
'searchhelp-url'                   => 'Help:Içindäkiler',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Şu prefiksli sahypalara göz aýla]]',
'searchprofile-articles'           => 'Mazmunly sahypalar',
'searchprofile-project'            => 'Ýardam we Taslama sahypalary',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Ähli zatlar',
'searchprofile-advanced'           => 'Giňeldilen',
'searchprofile-articles-tooltip'   => '$1 boýunça gözle',
'searchprofile-project-tooltip'    => '$1 boýunça gözle',
'searchprofile-images-tooltip'     => 'Faýllary gözle',
'searchprofile-everything-tooltip' => 'Ähli sahypalardan gözle (şol sanda çekişme sahypalaryndan)',
'search-result-size'               => '$1 ({{PLURAL:$2|1 söz|$2 söz}})',
'search-result-score'              => 'Kybapdaşlyk: $1%',
'search-redirect'                  => '(gönükdirme $1)',
'search-section'                   => '(bölüm $1)',
'search-suggest'                   => 'Şeýle diýjek bolduňyzmy: $1',
'search-interwiki-caption'         => 'Dogan taslamalar',
'search-interwiki-default'         => '$1 netijeler:',
'search-interwiki-more'            => '(has-da köp)',
'search-mwsuggest-enabled'         => 'teklipler bilen',
'search-mwsuggest-disabled'        => 'teklip ýok',
'searchall'                        => 'ählisi',
'nonefound'                        => "'''Bellik''': Diňe käbir at giňişlikleri gaýybana tertipde gözlenilýär.
Gözlegiňiziň başyna '''all:''' pristawkasyny goşup tutuş mazmuny (şol sanda pikir alyşma sahypalaryny, şablonlary we şm.) gözlemäge synanyşyň ýa-da pristawka hökmünde gerekleýän at giňişligiňizi ulanyň.",
'powersearch'                      => 'Giňişleýin gözleg',
'powersearch-legend'               => 'Giňişleýin gözleg',
'powersearch-ns'                   => 'At giňişliklerinde gözleg:',
'powersearch-redir'                => 'Gönükdirmeleri sanawla',
'powersearch-field'                => 'Gözle',
'powersearch-toggleall'            => 'Ählisi',
'powersearch-togglenone'           => 'Hiç biri',

# Preferences page
'preferences'                 => 'Sazlamalar',
'mypreferences'               => 'Sazlamalarym',
'prefs-edits'                 => 'Özgerdiş sany:',
'prefsnologin'                => 'Sessiýa açylmandyr',
'prefsnologintext'            => 'Ulanyjy sazlamalaryny üýtgetmek üçin <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} sessiýa açmagyňyz gerek]</span>.',
'changepassword'              => 'Paroly üýtget',
'prefs-skin'                  => 'Bezeg',
'skin-preview'                => 'Deslapky syn',
'prefs-math'                  => 'Matematiki formulalar',
'prefs-datetime'              => 'Sene we wagt',
'prefs-personal'              => 'Ulanyjy maglumatlary',
'prefs-rc'                    => 'Soňky üýtgeşmeler',
'prefs-watchlist'             => 'Gözegçilik sanawy',
'prefs-watchlist-days'        => 'Gözegçilik sanawynda görkeziljek gün sany:',
'prefs-watchlist-days-max'    => '(iň köp 7 gün)',
'prefs-watchlist-edits'       => 'Giňeldilen gözegçilik sanawynda görkeziljek üýtgeşmeleriň maksimum sany:',
'prefs-watchlist-edits-max'   => '(maksimum san: 1000)',
'prefs-misc'                  => 'Başga sazlamalar',
'prefs-resetpass'             => 'Paroly üýtget',
'prefs-email'                 => 'E-poçta opsiýalary',
'prefs-rendering'             => 'Daşky görnüş',
'saveprefs'                   => 'Ýazdyr',
'restoreprefs'                => 'Ähli gaýybana sazlamalary dikelt',
'prefs-editing'               => 'Redaktirleme',
'rows'                        => 'Setir:',
'columns'                     => 'Sütün:',
'searchresultshead'           => 'Gözleg',
'recentchangesdays'           => 'Soňky üýtgeşmelerde görkeziljek gün sany:',
'recentchangesdays-max'       => '(maksimum $1 {{PLURAL:$1|gün|gün}})',
'savedprefs'                  => 'Sazlamalaryňyz ýazdyryldy.',
'timezonelegend'              => 'Sagat guşaklygy',
'localtime'                   => 'Ýerli wagt:',
'timezoneoffset'              => 'Süýşme¹:',
'servertime'                  => 'Serwer wagty:',
'timezoneregion-africa'       => 'Afrika',
'timezoneregion-america'      => 'Amerika',
'timezoneregion-antarctica'   => 'Antarktika',
'timezoneregion-arctic'       => 'Arktika',
'timezoneregion-asia'         => 'Aziýa',
'timezoneregion-atlantic'     => 'Atlantik okean',
'timezoneregion-australia'    => 'Awstraliýa',
'timezoneregion-europe'       => 'Ýewropa',
'timezoneregion-indian'       => 'Hindi okeany',
'timezoneregion-pacific'      => 'Ýuwaş okean',
'allowemail'                  => 'Başga ulanyjylar maňa e-poçta iberip bilsin',
'prefs-searchoptions'         => 'Gözleg opsiýalary',
'prefs-namespaces'            => 'At giňişlikleri',
'prefs-files'                 => 'Faýllar',
'prefs-custom-css'            => 'Hususy CSS',
'prefs-custom-js'             => 'Hususy JS',
'prefs-emailconfirm-label'    => 'E-poçta tassyklamasy:',
'prefs-textboxsize'           => 'Redaktirleme penjiresiniň ölçegi',
'youremail'                   => 'E-poçta:',
'username'                    => 'Ulanyjy ady:',
'uid'                         => 'Ulanyjy belgisi:',
'prefs-memberingroups'        => '{{PLURAL:$1|toparyň|toparyň}} agzasy:',
'prefs-registration'          => 'Hasaba alnan wagty:',
'yourrealname'                => 'Hakyky adyňyz:',
'yourlanguage'                => 'Interfeýs dili:',
'yournick'                    => 'Lakamyňyz (gol hökmünde):',
'prefs-help-signature'        => 'Çekişme sahypalaryndaky teswirlere "<nowiki>~~~~</nowiki>" bilen gol çekilmelidir, ol goluňyza we onuň senesine öwrüler.',
'badsig'                      => 'Nädogry işlenmedik gol. HTML belliklerini gözden geçiriň.',
'badsiglength'                => 'Goluňyz çakdanaşa uzyn.
$1 {{PLURAL:$1|simwoldan|simwoldan}} köp bolmaly däl.',
'yourgender'                  => 'Jyns:',
'gender-unknown'              => 'Görkezilmedik',
'gender-male'                 => 'Erkek',
'gender-female'               => 'Aýal-gyz',
'prefs-help-gender'           => 'Hökmany däl: programma tarapyndan ulanyjynyň jynsyna görä ýüzlenmek üçin ulanylýar. Bu maglumat köpçülige açyk boljakdyr.',
'email'                       => 'E-poçta',
'prefs-help-realname'         => 'Hakyky at (hökmany däl): eger hakyky adyňyz berseňiz, onda eden işleriňiz görkezilende ulanyljakdyr.',
'prefs-help-email'            => 'E-poçta adresi hökmany däl; ýöne parolyňyz ýadyňyzdan çykan ýagdaýynda e-poçta adresiňize täze parol iberilmegine mümkinçilik berýär.
Şeýle-de ol başga ulanyjylaryň ulanyjy we pikir alyşma sahypalaryňyzyň üsti bilen kimdigiňizi bilmezden siziň bilen habarlaşyp bilmeklerine şert döredýär.',
'prefs-help-email-required'   => 'E-poçta adresi talap edilýär.',
'prefs-info'                  => 'Esasy maglumat',
'prefs-i18n'                  => 'Halkaralaşdyryş',
'prefs-signature'             => 'Gol',
'prefs-dateformat'            => 'Sene formaty',
'prefs-timeoffset'            => 'Wagt süýşmesi',
'prefs-advancedediting'       => 'Giňeldilen opsiýalar',
'prefs-advancedrc'            => 'Giňeldilen opsiýalar',
'prefs-advancedrendering'     => 'Giňeldilen opsiýalar',
'prefs-advancedsearchoptions' => 'Giňeldilen opsiýalar',
'prefs-advancedwatchlist'     => 'Giňeldilen opsiýalar',
'prefs-display'               => 'Görkeziş opsiýalary',
'prefs-diffs'                 => 'Tapawutlar',

# User rights
'userrights'                  => 'Ulanyjy hukuklary dolandyryşy',
'userrights-lookup-user'      => 'Ulanyjy toparlaryny dolandyr',
'userrights-user-editname'    => 'Ulanyjy adyny giriziň:',
'editusergroup'               => 'Ulanyjy toparlaryny redaktirle',
'userrights-editusergroup'    => 'Ulanyjy toparlaryny redaktirle',
'saveusergroups'              => 'Ulanyjy toparlaryny ýazdyr',
'userrights-groupsmember'     => 'Agza toparlary:',
'userrights-reason'           => 'Üýtgetmäniň sebäbi:',
'userrights-changeable-col'   => 'Üýtgedip bilýän toparlaryňyz',
'userrights-unchangeable-col' => 'Üýtgedip bilmeýän toparlaryňyz',

# Groups
'group'               => 'Topar:',
'group-user'          => 'Ulanyjylar',
'group-autoconfirmed' => 'Awtomatik tassyklanan ulanyjylar',
'group-bot'           => 'Botlar',
'group-sysop'         => 'Administratorlar',
'group-bureaucrat'    => 'Býurokratlar',
'group-suppress'      => 'Gözegçiler',
'group-all'           => '(ählisi)',

'group-user-member'          => 'Ulanyjy',
'group-autoconfirmed-member' => 'Awtomatik tassyklanan ulanyjy',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Administrator',
'group-bureaucrat-member'    => 'Býurokrat',
'group-suppress-member'      => 'Gözegçi',

'grouppage-user'          => '{{ns:project}}:Ulanyjylar',
'grouppage-autoconfirmed' => '{{ns:project}}:Awtomatik tassyklanan ulanyjylar',
'grouppage-bot'           => '{{ns:project}}:Botlar',
'grouppage-sysop'         => '{{ns:project}}:Administratorlar',
'grouppage-bureaucrat'    => '{{ns:project}}:Býurokratlar',
'grouppage-suppress'      => '{{ns:project}}:Gözegçi',

# Rights
'right-read'               => 'Sahypalary oka',
'right-edit'               => 'Sahypalary redaktirle',
'right-createpage'         => 'Sahypa döret (pikir alyşma sahypalary däl)',
'right-createtalk'         => 'Pikir alyşma sahypalaryny döret',
'right-createaccount'      => 'Täze ulanyjy hasaplaryny döret',
'right-minoredit'          => 'Özgerdişleri ujypsyzja diýip belle',
'right-move'               => 'Sahypalaryň adyny üýtget',
'right-move-subpages'      => 'Sahypalaryň adyny kiçi sahypalarynyň atlary bilen bilelikde üýtget',
'right-move-rootuserpages' => 'Düýp ulanyjy sahypalarynyň adyny üýtget',
'right-movefile'           => 'Faýllaryň adyny üýtget',
'right-upload'             => 'Faýllary ýükle',
'right-delete'             => 'Sahypalary öçür',
'right-bigdelete'          => 'Uzyn geçmişli sahypalary öçür',
'right-browsearchive'      => 'Öçürilen sahypalary gözle',
'right-userrights'         => 'Ähli ulanyjy hukuklaryny redaktirle',

# User rights log
'rightslog'  => 'Ulanyjy hukuklarynyň gündeligi',
'rightsnone' => '(hiç biri)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'               => 'bu sahypany okamaga',
'action-edit'               => 'bu sahypany redaktirlemäge',
'action-createpage'         => 'sahypa döretmäge',
'action-createtalk'         => 'pikir alyşma sahypasy döretmäge',
'action-createaccount'      => 'bu ulanyjy hasabyny döretmäge',
'action-minoredit'          => 'bu özgerdişi ujypsyzja diýip bellemäge',
'action-move'               => 'bu sahypanyň adyny üýtgetmäge',
'action-move-subpages'      => 'bu sahypanyň we onuň kiçi sahypalarynyň adyny üýtgetmäge',
'action-move-rootuserpages' => 'düýp ulanyjy sahypalarynyň adyny üýtgetmäge',
'action-movefile'           => 'bu faýlyň adyny üýtgetmäge',
'action-upload'             => 'bu faýly ýüklemäge',
'action-delete'             => 'bu sahypany öçürmäge',
'action-browsearchive'      => 'öçürilen sahypalary gözlemäge',
'action-userrights'         => 'ähli ulanyjy hukuklaryny redaktirlemäge',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|üýtgeşme|üýtgeşme}}',
'recentchanges'                     => 'Soňky üýtgeşmeler',
'recentchanges-legend'              => 'Soňky üýtgeşmeleriň opsiýalary',
'recentchanges-feed-description'    => 'Bu lentadaky wikide edilen iň soňky üýtgeşmeleri yzarlaň.',
'recentchanges-legend-newpage'      => '$1 - täze sahypa',
'recentchanges-label-newpage'       => 'Bu özgerdiş täze bir sahypa döretdi',
'recentchanges-legend-minor'        => '$1 - ujypsyzja özgerdiş',
'recentchanges-label-minor'         => 'Bu bir ujypsyzja özgerdiş',
'recentchanges-legend-bot'          => '$1 - bot özgerdişi',
'recentchanges-label-bot'           => 'Bu özgerdiş bir bot tarapyndan amala aşyryldy',
'rcnote'                            => "Aşakda $5, $4 senesinden başlap, soňky {{PLURAL:$2|1 günde|'''$2''' günde}} edilen {{PLURAL:$1|'''1''' üýtgeşme|'''$1''' üýtgeşme}} görkezilýär.",
'rclistfrom'                        => '$1 senesinden bäri edilen özgerdişleri görkez',
'rcshowhideminor'                   => 'ujypsyzja özgerdişleri $1',
'rcshowhidebots'                    => 'botlary $1',
'rcshowhideliu'                     => 'sessiýasy açyk ulanyjylary $1',
'rcshowhideanons'                   => 'anonim ulanyjylary $1',
'rcshowhidemine'                    => 'özgerdişlerimi $1',
'rclinks'                           => 'Soňky $2 günde edilen iň soňky $1 üýtgeşmäni görkez;<br /> $3',
'diff'                              => 'tapawut',
'hist'                              => 'geçmiş',
'hide'                              => 'gizle',
'show'                              => 'görkez',
'minoreditletter'                   => 'uj',
'newpageletter'                     => 'T',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|ulanyjy|ulanyjy}} gözegçilik edýär]',
'rc-enhanced-expand'                => 'Jikme-jiklikleri görkez (JavaScript gerekli)',
'rc-enhanced-hide'                  => 'Jikme-jiklikleri gizle',

# Recent changes linked
'recentchangeslinked'         => 'Degişli üýtgeşmeler',
'recentchangeslinked-feed'    => 'Degişli üýtgeşmeler',
'recentchangeslinked-toolbox' => 'Degişli üýtgeşmeler',
'recentchangeslinked-title'   => '"$1" bilen baglanyşykly üýtgeşmeler',
'recentchangeslinked-summary' => "Aşakdaky sanaw, görkezilen sahypa (ýa-da görkezilen kategoriýanyň agzalaryna) çykgyt berýän sahypalarda edilen üýtgeşmeleriň sanawydyr.
[[Special:Watchlist|Gözegçilik sanawyňyzdaky]] sahypalar '''goýy''' ýazgy bilen görkezilýär.",
'recentchangeslinked-page'    => 'Sahypanyň ady:',
'recentchangeslinked-to'      => 'Tersine, berlen sahypa çykgyt berýän sahypalary görkez',

# Upload
'upload'              => 'Faýl ýükle',
'uploadbtn'           => 'Faýl ýükle',
'reupload'            => 'Gaýtadan ýükle',
'uploaderror'         => 'Ýükleme säwligi',
'uploadlog'           => 'ýükleme gündeligi',
'uploadlogpage'       => 'Ýükleme gündeligi',
'filename'            => 'Faýl ady',
'filedesc'            => 'Gysgaça düşündiriş',
'fileuploadsummary'   => 'Gysgaça düşündiriş:',
'filereuploadsummary' => 'Faýl üýtgeşmeleri:',
'fileexists-thumb'    => "<center>'''Bu faýl öňdenem bar'''</center>",
'successfulupload'    => 'Şowly ýükleme',
'uploadwarning'       => 'Ýükleme duýduryşy',
'savefile'            => 'Faýly ýazdyr',
'uploadedimage'       => 'Ýüklenen: "[[$1]]"',
'overwroteimage'      => '"[[$1]]" faýlynyň täze wersiýasy ýüklendi',
'upload-maxfilesize'  => 'Maksimum faýl ölçegi: $1',

'upload-file-error'   => 'Içerki säwlik',
'upload-unknown-size' => 'Näbelli ölçeg',

# Special:ListFiles
'imgfile'               => 'faýl',
'listfiles'             => 'Faýl sanawy',
'listfiles_date'        => 'Sene',
'listfiles_name'        => 'At',
'listfiles_user'        => 'Ulanyjy',
'listfiles_size'        => 'Ölçeg',
'listfiles_description' => 'Düşündiriş',
'listfiles_count'       => 'Wersiýalar',

# File description page
'file-anchor-link'          => 'Faýl',
'filehist'                  => 'Faýlyň geçmişi',
'filehist-help'             => 'Faýlyň geçmişini görmek üçin Sene/Wagt bölümündäki senelere tyklaň.',
'filehist-deleteall'        => 'ählisini öçür',
'filehist-deleteone'        => 'öçür',
'filehist-current'          => 'häzirki',
'filehist-datetime'         => 'Sene/Wagt',
'filehist-thumb'            => 'Miniatýura',
'filehist-thumbtext'        => '$1 senesindäki wersiýanyň miniatýurasy',
'filehist-user'             => 'Ulanyjy',
'filehist-dimensions'       => 'Ölçegler',
'filehist-filesize'         => 'Faýl ölçegi',
'filehist-comment'          => 'Teswirleme',
'imagelinks'                => 'Faýlyň çykgytlary',
'linkstoimage'              => 'Bu faýla çykgydy bar bolan {{PLURAL:$1|sahypa|$1 sahypa}}:',
'sharedupload'              => 'Bu faýl $1 ammaryndan, özem beýleki taslamalarda ulanylýan bolmagy ahmal.',
'uploadnewversion-linktext' => 'Bu faýlyň täze wersiýasyny ýükläň',

# File deletion
'filedelete'                  => '$1 faýlyny öçür',
'filedelete-legend'           => 'Faýly öçür',
'filedelete-intro'            => "'''[[Media:$1|$1]]''' faýlyny tutuş geçmişi bilen bilelikde öçürjek bolup dursuňyz.",
'filedelete-intro-old'        => "'''[[Media:$1|$1]]''' faýlynyň [$4 $3, $2] seneli wersiýasyny öçürýärsiňiz.",
'filedelete-comment'          => 'Öçürmekligiň sebäbi:',
'filedelete-submit'           => 'Öçür',
'filedelete-success'          => "'''$1''' öçürildi.",
'filedelete-success-old'      => "'''[[Media:$1|$1]]''' faýlynyň $3, $2 seneli wersiýasy öçürildi.",
'filedelete-nofile'           => "'''$1''' ýok.",
'filedelete-nofile-old'       => "'''$1''' üçin görkezilen aýratynlykda arhiwlenen wersiýa ýok.",
'filedelete-otherreason'      => 'Başga/goşmaça sebäp:',
'filedelete-reason-otherlist' => 'Başga sebäp',

# List redirects
'listredirects' => 'Gönükdirmeleriň sanawy',

# Unused templates
'unusedtemplates'    => 'Ulanylmaýan şablonlar',
'unusedtemplateswlh' => 'başga çykgytlar',

# Random page
'randompage' => 'Mesaýy makala',

# Statistics
'statistics'               => 'Statistika',
'statistics-header-pages'  => 'Sahypa statistikalary',
'statistics-header-edits'  => 'Özgerdiş statistikalary',
'statistics-header-views'  => 'Synlama statistikalary',
'statistics-header-users'  => 'Ulanyjy statistikalary',
'statistics-articles'      => 'Makalalar',
'statistics-pages'         => 'Sahypalar',
'statistics-pages-desc'    => 'Wikidäki ähli sahypalar, şol sanda çekişme sahypalary, gönükdirmeler we ş.m.',
'statistics-files'         => 'Ýüklenen faýllar',
'statistics-edits'         => '{{SITENAME}} gurulaly bäri edilen sahypa özgerdişleri',
'statistics-edits-average' => 'Sahypa başyna ortaça özgerdiş',
'statistics-views-total'   => 'Jemi synlama',
'statistics-views-peredit' => 'Özgerdiş başyna synlama',
'statistics-users'         => 'Hasaba alnan [[Special:ListUsers|ulanyjylar]]',
'statistics-users-active'  => 'Işjeň ulanyjylar',

'brokenredirects-edit'   => 'redaktirle',
'brokenredirects-delete' => 'öçür',

'withoutinterwiki' => 'Başga dillere çykgydy ýok sahypalar',

'fewestrevisions' => 'Iň az wersiýaly sahypalar',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|baýt|baýt}}',
'ncategories'             => '{{PLURAL:$1|kategoriýa|kategoriýalar}}',
'nlinks'                  => '$1 {{PLURAL:$1|çykgyt|çykgyt}}',
'nmembers'                => '{{PLURAL:$1|agza|agzalar}}',
'nrevisions'              => '{{PLURAL:$1|wersiýa|wersiýalar}}',
'nviews'                  => '$1 {{PLURAL:$1|synlama|synlama}}',
'lonelypages'             => 'Hossarsyz sahypalar',
'uncategorizedpages'      => 'Kategoriýa goýulmadyk sahypalar',
'uncategorizedcategories' => 'Kategoriýa goýulmadyk kategoriýalar',
'uncategorizedimages'     => 'Kategoriýa goýulmadyk faýllar',
'uncategorizedtemplates'  => 'Kategoriýa goýulmadyk şablonlar',
'unusedcategories'        => 'Ulanylmaýan kategoriýalar',
'unusedimages'            => 'Ulanylmaýan faýllar',
'wantedcategories'        => 'Talap edilýän kategoriýalar',
'wantedpages'             => 'Talap edilýän sahypalar',
'wantedfiles'             => 'Talap edilýän faýllar',
'wantedtemplates'         => 'Talap edilýän şablonlar',
'mostlinked'              => 'Iň köp çykgyt berilýän sahypalar',
'mostlinkedcategories'    => 'Iň köp çykgyt berilýän kategoriýalar',
'mostlinkedtemplates'     => 'Iň köp çykgyt berilýän şablonlar',
'mostcategories'          => 'Iň köp kategoriýaly sahypalar',
'mostimages'              => 'Iň köp çykgyt berilýän faýllar',
'mostrevisions'           => 'Iň köp wersiýaly sahypalar',
'prefixindex'             => 'Pristawka bilen ähli sahypalar',
'shortpages'              => 'Gysga sahypalar',
'longpages'               => 'Uzyn sahypalar',
'deadendpages'            => 'Petige direýän sahypalar',
'protectedpages'          => 'Goragly sahypalar',
'protectedtitles'         => 'Goragly atlar',
'listusers'               => 'Ulanyjy sanawy',
'usereditcount'           => '$1 {{PLURAL:$1|özgerdiş|özgerdiş}}',
'newpages'                => 'Täze sahypalar',
'newpages-username'       => 'Ulanyjy ady:',
'ancientpages'            => 'Iň köne sahypalar',
'move'                    => 'Adyny üýtget',
'movethispage'            => 'Bu sahypanyň adyny üýtget',
'pager-newer-n'           => '{{PLURAL:$1|1 has täze|$1 has täze}}',
'pager-older-n'           => '{{PLURAL:$1|1 has köne|$1 has köne}}',

# Book sources
'booksources'               => 'Kitap çeşmeleri',
'booksources-search-legend' => 'Kitap çeşmelerini gözle',
'booksources-go'            => 'Git',

# Special:Log
'specialloguserlabel' => 'Ulanyjy:',
'log'                 => 'Gündelikler',

# Special:AllPages
'allpages'       => 'Ähli sahypalar',
'alphaindexline' => '$1 sahypasyndan $2 sahypasyna çenli',
'nextpage'       => 'Indiki sahypa ($1)',
'prevpage'       => 'Öňki sahypa ($1)',
'allpagesfrom'   => 'Sanawy şu harplar bilen başlat:',
'allpagesto'     => 'Şu harp bilen gutarýan sahypalary görkez:',
'allarticles'    => 'Ähli sahypalar',
'allpagesprev'   => 'Öňki',
'allpagesnext'   => 'Indiki',
'allpagessubmit' => 'Git',

# Special:Categories
'categories' => 'Kategoriýalar',

# Special:DeletedContributions
'deletedcontributions'       => 'Öçürilen ulanyjy goşantlary',
'deletedcontributions-title' => 'Öçürilen ulanyjy goşantlary',

# Special:LinkSearch
'linksearch' => 'Daşarky çykgytlar',

# Special:ListUsers
'listusers-submit'   => 'Görkez',
'listusers-noresult' => 'Ulanyjy tapylmady.',

# Special:ActiveUsers
'activeusers'          => 'Işjeň ulanyjylaryň sanawy',
'activeusers-count'    => '$1 sany soňky {{PLURAL:$1|özgerdiş|özgerdiş}}',
'activeusers-from'     => 'Şunuň bilen başlaýan ulanyjylary görkez:',
'activeusers-noresult' => 'Ulanyjy tapylmady.',

# Special:Log/newusers
'newuserlogpage'           => 'Täze ulanyjy gündeligi',
'newuserlog-create-entry'  => 'Täze ulanyjy',
'newuserlog-create2-entry' => 'täze $1 hasabyny döretdi',

# Special:ListGroupRights
'listgrouprights-members' => '(agzalaryň sanawy)',

# E-mail user
'emailuser' => 'Bu ulanyja e-poçta iber',

# Watchlist
'watchlist'         => 'Gözegçilik sanawym',
'mywatchlist'       => 'Gözegçilik sanawym',
'watchlistfor'      => "('''$1''' üçin)",
'addedwatch'        => 'Gözegçilik sanawyna goşuldy',
'addedwatchtext'    => "\"<nowiki>\$1</nowiki>\" atly sahypa [[Special:Watchlist|gözegçilik sanawyňyza]] goşuldy.

Geljekde, bu sahypada we degişli çekişme sahypasynda ediljek üýtgeşmeler şu ýerde sanawlanjakdyr.

Aňsatlyk bilen saýlap almak üçin bolsa, [[Special:RecentChanges|soňky üýtgeşmeleriň sanawynda]] '''goýy''' harp bilen görkeziljekdir.",
'removedwatch'      => 'Gözegçilik sanawyndan aýyryldy',
'removedwatchtext'  => '"<nowiki>$1</nowiki>" sahypasy gözegçilik sanawyňyzdan aýyryldy.',
'watch'             => 'Gözegçilikde sakla',
'watchthispage'     => 'Bu sahypany gözegçilikde sakla',
'unwatch'           => 'Gözegçilikden aýyr',
'watchlist-details' => 'Çekişme sahypalaryny hasap etmäniňde, gözegçilik sanawyňyzda {{PLURAL:$1|$1 sahypa|$1 sahypa}} bar.',
'wlshowlast'        => 'Soňky $1 sagady $2 güni görkez $3',
'watchlist-options' => 'Gözegçilik sanawynyň opsiýalary',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Gözegçilige alynýar...',
'unwatching' => 'Gözegçilikden aýyrylýar...',

'changed' => 'üýtgedildi',

# Delete
'deletepage'            => 'Sahypany öçür',
'delete-legend'         => 'Öçür',
'confirmdeletetext'     => 'Sahypany ýa-da faýly tutuş geçmişi bilen bilelikde öçürjek bolup dursuňyz.
Bu amalyň getirip biljek netijelerine gözüňiz ýetýän bolsa we amalyň [[{{MediaWiki:Policy-url}}|Öçürme kadalaryna]] laýyklykdadygyny bilýän bolsaňyz, amaly tassyklaň.',
'actioncomplete'        => 'Iş ýerine ýetirildi',
'deletedtext'           => '"<nowiki>$1</nowiki>" öçürildi.
Ýaňy-ýakynda öçürilenleri görmek üçin: $2.',
'deletedarticle'        => '"[[$1]]" öçürildi',
'dellogpage'            => 'Öçürme gündeligi',
'deletecomment'         => 'Öçürmäniň sebäbi:',
'deleteotherreason'     => 'Başga/goşmaça sebäp:',
'deletereasonotherlist' => 'Başga sebäpler',

# Rollback
'rollbacklink' => 'öňki katdyna getir',

# Protect
'protectlogpage'              => 'Gorag gündeligi',
'protectedarticle'            => '"[[$1]]" sahypasyny gorag astyna aldy.',
'modifiedarticleprotection'   => '"[[$1]]" üçin gorag derejesi üýtgedildi',
'prot_1movedto2'              => '[[$1]] sahypasy [[$2]] sahypasyna göçürildi',
'protectcomment'              => 'Sebäp:',
'protectexpiry'               => 'Gutaryş senesi:',
'protect_expiry_invalid'      => 'Gutaryş möhleti nädogry.',
'protect_expiry_old'          => 'Geçmişdäki gutaryş möhleti.',
'protect-unchain'             => 'Göçürmegiň gulpuny aç',
'protect-text'                => '[[$1]] sahypasynyň gorag ýagdaýyny şu ýerden görüp hem-de redaktirläp bilersiňiz.',
'protect-locked-access'       => "Ulanyjy hasabyňyzyň sahypanyň gorag derejelerini üýtgetmäge ygtyýary ýok.
'''$1''' sahypasynyň häzirki sazlamalary şulardyr:",
'protect-cascadeon'           => 'Bu sahypa, kaskadly gorag işjeň ýagdaýa geçirilen aşakdaky {{PLURAL:$1|$1 sahypada|$1 sahypada}} ulanylandygy üçin şu mahal gorag astyndadyr.
Bu sahypanyň gorag derejesini üýtgedip bilersiňiz, ýöne ol kaskadly goraga täsir etmez.',
'protect-default'             => 'Ähli ulanyjylara rugsat ber',
'protect-fallback'            => '"$1" rugsady talap edilýär',
'protect-level-autoconfirmed' => 'Täze hem-de hasaba alynmadyk ulanyjylary blokirle',
'protect-level-sysop'         => 'diňe administratorlar',
'protect-summary-cascade'     => 'kaskadly',
'protect-expiring'            => 'gutarýan möhleti $1 (UTC)',
'protect-cascade'             => 'Bu sahypada ulanylan ähli sahypalary goraga al (kaskadly gorag)',
'protect-cantedit'            => 'Bu sahypanyň gorag derejesini üýtgedip bilmeýärsiňiz, çünki ony redaktirlemäge rugsadyňyz ýok.',
'protect-otherreason'         => 'Başga/goşmaça sebäp:',
'restriction-type'            => 'Rugsat:',
'restriction-level'           => 'Çäklendiriş derejesi:',

# Restrictions (nouns)
'restriction-move' => 'Adyny üýtget',

# Undelete
'undeletelink'              => 'görkez/dikelt',
'undeletedarticle'          => '"$1" dikeldildi.',
'undelete-show-file-submit' => 'Hawa',

# Namespace form on various pages
'namespace'      => 'At giňişligi:',
'invert'         => 'Saýlanmadyklary',
'blanknamespace' => '(Baş)',

# Contributions
'contributions'       => 'Ulanyjynyň goşantlary',
'contributions-title' => '$1 üçin ulanyjy goşantlary',
'mycontris'           => 'Goşantlarym',
'contribsub2'         => '$1 ($2)',
'uctop'               => '(iň soňky)',
'month'               => 'Aý:',
'year'                => 'Ýyl:',

'sp-contributions-newbies'  => 'Diňe täze hasap açan ulanyjylaryň goşantlaryny görkez',
'sp-contributions-blocklog' => 'Blokirleme gündeligi',
'sp-contributions-deleted'  => 'öçürilen ulanyjy goşantlary',
'sp-contributions-logs'     => 'gündelikler',
'sp-contributions-talk'     => 'çekişme',
'sp-contributions-search'   => 'Goşantlary gözle',
'sp-contributions-username' => 'IP adresi ýa-da ulanyjy ady:',
'sp-contributions-submit'   => 'Gözle',

# What links here
'whatlinkshere'            => 'Bu sahypa çykgytlar',
'whatlinkshere-title'      => '"$1" makalasyna çykgyt berýän sahypalar',
'whatlinkshere-page'       => 'Sahypa:',
'linkshere'                => "'''[[:$1]]''' sahypasyna çykgyt berýän sahypalar:",
'isredirect'               => 'gönükdirme sahypasy',
'istemplate'               => 'atanaklaýyn girizme',
'isimage'                  => 'faýl çykgydy',
'whatlinkshere-prev'       => '{{PLURAL:$1|öňki|öňki $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|indiki|indiki $1}}',
'whatlinkshere-links'      => '← çykgytlar',
'whatlinkshere-hideredirs' => 'gönükdirmeleri $1',
'whatlinkshere-hidetrans'  => 'Atanaklaýyn girizmeleri $1',
'whatlinkshere-hidelinks'  => 'çykgytlary $1',
'whatlinkshere-filters'    => 'Filtrler',

# Block/unblock
'blockip'                  => 'Ulanyjyny blokirle',
'ipaddress'                => 'IP adresi:',
'ipadressorusername'       => 'IP adresi ýa-da ulanyjy ady:',
'ipbexpiry'                => 'Gutarýan wagty:',
'ipbreason'                => 'Sebäp:',
'ipbreasonotherlist'       => 'Başga sebäp',
'ipbsubmit'                => 'Bu ulanyjyny blokirle',
'ipboptions'               => '2 sagat:2 hours,1 gün:1 day,3 gün:3 days,1 hepde:1 week,2 hepde:2 weeks,1 aý:1 month,3 aý:3 months,6 aý:6 months,1 ýyl:1 year,Möhletsiz:infinite',
'ipbotherreason'           => 'Başga/goşmaça sebäp:',
'badipaddress'             => 'Nädogry IP adresi',
'ipblocklist'              => 'Blokirlenen IP adresleri we ulanyjy atlary',
'blocklink'                => 'blokirle',
'unblocklink'              => 'blokirowkany aýyr',
'change-blocklink'         => 'blokirowkany üýtget',
'contribslink'             => 'goşantlar',
'blocklogpage'             => 'Blokirleme gündeligi',
'blocklogentry'            => ', [[$1]] ulanyjysyny blokirledi, blokirleme möhleti: $2 $3',
'unblocklogentry'          => '$1 ulanyjynyň blokirlemesi aýryldy',
'block-log-flags-nocreate' => 'hasap açmaklyk blokirlendi',
'blockme'                  => 'Meni blokirle',

# Move page
'movepagetext'     => "Aşakdaky form ulanylyp, sahypanyň ady üýtgedilýär. Onuň ýany bilen tutuş geçmişi hem täze ada geçirilýär. Köne at täze adyň gönükdirmesine öwrülýär. Köne ada gönükdirmeleri awtomatik usulda täzeläp bilersiňiz. Bu amaly awtomatik usulda ýerine ýetirmek islemeseňiz, onda ähli [[Special:DoubleRedirects|goşa]] ýa-da [[Special:BrokenRedirects|döwlen]] gönükdirmeleri özüňiz düzetmeli bolýarsyňyz. 
Etjek bu üýtgeşmäňiz boýunça ähli çykgytlaryň bolmalysy ýaly işlemegine siziň özüňiziň jogapkärçilik çekýändigiňizi ýatdan çykarmaň.

Eger-de täze atda ozaldan bir makala bar bolsa, onda '''at üýtgedilmeli däldir'''. Şeýle hem, ady üýtgedeniňize ökünseňiz, üýtgeşmäni yzyna gaýtaryp bilersiňiz we başga hiç bir sahypa degmedigiňiz bolar.

'''DUÝDURYŞ!'''
Bu üýtgeşiklik giňden tanalýan bir sahypa üçin garaşylmaýan netijelere getirip biler; Ady heniz üýtgetmänkäňiz bolup biläýjek ähtimallyklary göz öňünde tutmagyňyzy haýyş edýäris.",
'movepagetalktext' => "Gapdalyndaky çekişme sahypasy hem (eger bar bolsa) awtomatik usulda täze ada geçirilýär. Emma şu ýagdaýlarda '''geçirilmeýär''':

*Täze atda bir çekişme sahypasy öňdenem bar bolsa,
*Aşakdaki gutujygy saýlamadyk bolsaňyz.

Şeýle ýagdaýda sahypany özüňiz ell bilen geçirmeli bolýarsyňyz.",
'movearticle'      => 'Köne at',
'newtitle'         => 'Täze at',
'move-watch'       => 'Bu sahypany gözegçilikde sakla',
'movepagebtn'      => 'Adyny üýtget',
'pagemovedsub'     => 'At üýtgedildi',
'movepage-moved'   => '<big>\'\'\'"$1" sahypasy "$2" sahypasyna geçirildi\'\'\'</big>',
'articleexists'    => 'Şu atda eýýämden bir sahypa bar ýa-da saýlap alan adyňyz nädogry.
Başga bir ady synap görmegiňizi haýyş edýäris.',
'talkexists'       => "'''Sahypanyň özi şowlulyk bilen geçirildi, ýöne çekişme sahypasyny geçirip bolmady sebäbi geçirilmeli adynda öňdenem bir sahypa bar. Çekişme sahypasynyň içindäkileri özüňiziň geçirmegiňizi haýyş edýäris.'''",
'movedto'          => 'geçirildi',
'movetalk'         => 'Degişli "çekişme" sahypasyny hem geçir',
'1movedto2'        => '[[$1]] sahypasynyň täze ady: [[$2]]',
'1movedto2_redir'  => '[[$1]] ady [[$2]] sahypasyna gönükdirildi',
'movelogpage'      => 'At üýtgetme gündeligi',
'movereason'       => 'Sebäp:',
'revertmove'       => 'yzyna al',

# Export
'export'        => 'Sahypa eksportirle',
'export-addcat' => 'Goş',
'export-addns'  => 'Goş',

# Namespace 8 related
'allmessagesname'           => 'At',
'allmessages-filter-legend' => 'Filtr',
'allmessages-filter-all'    => 'Ählisi',

# Thumbnails
'thumbnail-more' => 'Ulalt',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Ulanyjy sahypaňyz',
'tooltip-pt-mytalk'               => 'Pikir alyşma sahypaňyz',
'tooltip-pt-preferences'          => 'Sazlamalaryňyz',
'tooltip-pt-watchlist'            => 'Gözegçilikde saklaýan sahypalarym',
'tooltip-pt-mycontris'            => 'Eden goşantlaryňyzyň sanawy',
'tooltip-pt-login'                => 'Sessiýa açmagyňyz maslahat berilýär, ýöne hökmany däl.',
'tooltip-pt-logout'               => 'Sessiýany ýap',
'tooltip-ca-talk'                 => 'Sahypanyň mazmuny barada garaýşyňy beýan et',
'tooltip-ca-edit'                 => 'Bu sahypany redaktirläp bilersiňiz. Ýazdyrmankaňyz synlap görmekligi ýatdan çykarmaň.',
'tooltip-ca-addsection'           => 'Täze bölüm başlat',
'tooltip-ca-viewsource'           => 'Bu sahypa gorag astynda. 
Onuň çeşmesini görüp bilersiňiz',
'tooltip-ca-history'              => 'Bu sahypanyň ozalky wersiýalary',
'tooltip-ca-protect'              => 'Sahypany goraga al',
'tooltip-ca-delete'               => 'Sahypany öçür',
'tooltip-ca-move'                 => 'Sahypanyň adyny üýtget',
'tooltip-ca-watch'                => 'Bu sahypany gözegçilige al',
'tooltip-ca-unwatch'              => 'Bu sahypany gözegçilik sanawyňdan aýyr',
'tooltip-search'                  => '{{SITENAME}} boýunça gözle',
'tooltip-search-go'               => 'Eger bar bolsa, anyk şu atdaky sahypa git',
'tooltip-search-fulltext'         => 'Şu tekst bar bolan sahypalary gözle',
'tooltip-n-mainpage'              => 'Baş sahypa baryp gör',
'tooltip-n-mainpage-description'  => 'Baş sahypa baryp gör',
'tooltip-n-portal'                => 'Taslama hakynda, nämeler edip bolar, nämeler nirede',
'tooltip-n-currentevents'         => 'Bolup geçýän wakalar barada iň täze maglumatlar',
'tooltip-n-recentchanges'         => 'Wikidäki soňky üýtgeşmeleriň sanawy',
'tooltip-n-randompage'            => 'Çem gelen sahypa git',
'tooltip-n-help'                  => 'Kömek almak üçin',
'tooltip-t-whatlinkshere'         => 'Bu sahypa çykgyt berýän ähli wiki sahypalarynyň sanawy',
'tooltip-t-recentchangeslinked'   => 'Bu sahypa çykgyt berýän sahypalardaky soňky üýtgeşmeler',
'tooltip-feed-rss'                => 'Bu sahypa üçin RSS lentasy',
'tooltip-feed-atom'               => 'Bu sahypa üçin atom lentasy',
'tooltip-t-contributions'         => 'Şu ulanyjynyň goşantlarynyň sanawyny gör',
'tooltip-t-emailuser'             => 'Bu ulanyja e-poçta iber',
'tooltip-t-upload'                => 'Suratlary ýa-da multimediýa faýllaryny ýükläň',
'tooltip-t-specialpages'          => 'Ähli ýörite sahypalaryň sanawyny görkez',
'tooltip-t-print'                 => 'Bu sahypanyň print etmäge taýýar wersiýasy',
'tooltip-t-permalink'             => 'Sahypanyň bu wersiýasyna hemişelik çykgyt',
'tooltip-ca-nstab-main'           => 'Sahypany görkez',
'tooltip-ca-nstab-user'           => 'Ulanyjynyň sahypasyny görkez',
'tooltip-ca-nstab-special'        => 'Bu ýörite sahypa, ony redaktirläp bolmaýar',
'tooltip-ca-nstab-project'        => 'Taslama sahypasyny görkez',
'tooltip-ca-nstab-image'          => 'Suratyň sahypasyny görkez',
'tooltip-ca-nstab-template'       => 'Şablony görkez',
'tooltip-ca-nstab-category'       => 'Kategoriýanyň sahypasyny görkez',
'tooltip-minoredit'               => 'Ujypsyzja özgerdiş hökmünde belle',
'tooltip-save'                    => 'Özgerdişleriňi ýazdyr',
'tooltip-preview'                 => 'Deslapky syn; ýazdyrmankaňyz şuny ulanyp özgerdişleriňizi gözden geçiriň!',
'tooltip-diff'                    => 'Tekstde eden üýtgeşmeleriňizi görkezýär',
'tooltip-compareselectedversions' => 'Saýlanyp alynan iki wersiýanyň arasyndaky tapawutlary gör',
'tooltip-watch'                   => 'Sahypany gözegçilik sanawyňa goş',
'tooltip-rollback'                => '"Öňki katdyna getir" ýeke gezek tyklananda bu sahypa iň soňky goşant goşanyň özgerdişlerini yzyna alýar',
'tooltip-undo'                    => '"Yzyna al" bu özgerdişi yzyna alýar we özgerdiş formuny deslapky syn modunda açýar.
Mazmun üçin bir sebäp goşmaga rugsat berýär',

# Info page
'numedits'     => 'Özgerdiş sany (sahypa): $1',
'numtalkedits' => 'Özgerdiş sany (pikir alyşma sahypasy): $1',

# Math errors
'math_unknown_error' => 'näbelli säwlik',

# Browsing diffs
'previousdiff' => '← Ozalky wersiýa bilen aratapawut',
'nextdiff'     => 'Indiki wersiýa bilen aratapawut →',

# Media information
'file-info-size'       => '($1 × $2 piksel, faýlyň ölçegi: $3, MIME tipli: $4)',
'file-nohires'         => '<small>Wersiýanyň mundan uly ölçegi ýok.</small>',
'svg-long-desc'        => '(SVG faýly, nominal $1 × $2 piksel, faýl ölçegi: $3)',
'show-big-image'       => 'Suratyň doly ölçegi',
'show-big-image-thumb' => '<small>Deslapky synyň ölçegi: $1 × $2 piksel</small>',

# Special:NewFiles
'newimages-summary' => 'Bu ýörite sahypa iň soňky ýüklenen faýllary görkezýär.',
'newimages-legend'  => 'Filtr',
'newimages-label'   => 'Faýlyň ady (ýa-da bir bölegi):',
'showhidebots'      => '(botlary $1)',
'bydate'            => 'sene boýunça',

# Bad image list
'bad_image_list' => 'Format aşakdaky ýaly bolmalydyr:

Diňe sanawyň elementleri (* bilen başlaýanlar) nazara alynýar. Setirdäki ilkinji çykgyt ýaramaz suratyň çykgydy bolmalydyr.
Ondan soňraky çykgyt(lar) kadadan çykma hökmünde kabul edilýär, meselem: surat sahypada setiriçinde görünip biler.',

# Metadata
'metadata'          => 'Meta-maglumat',
'metadata-help'     => 'Bu faýlda, ähtimal, dijital fotoapparat ýa-da skaner tarapyndan goşulan goşmaça maglumatlar bardyr. Eger faýl soňradan redaktirlenen bolsa, onda käbir maglumatlar häzirki redaktirlenen faýly görä köneligine galan bolup biler.',
'metadata-expand'   => 'Jikme-jiklikleri görkez',
'metadata-collapse' => 'Jikme-jiklikleri görkezme',
'metadata-fields'   => 'Bu sahypada sanalýan EXIF meta-maglumat meýdançalary meta-maglumat tablisasy çöken mahaly surat görkeziş sahypalarynda ulanylýar. Galanlary gaýybana tertipde gizlenilýär.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-gpslatituderef'  => 'Demirgazyk ýa-da Günorta giňişlik',
'exif-gpslongituderef' => 'Gündogar ýa-da Günbatar uzaklyk',
'exif-gpslongitude'    => 'Uzaklyk',
'exif-gpsaltitude'     => 'Beýiklik',

'exif-unknowndate' => 'Näbelli sene',

'exif-subjectdistance-value' => '$1 metr',

'exif-meteringmode-0' => 'Näbelli',

'exif-lightsource-0'   => 'Näbelli',
'exif-lightsource-1'   => 'Gündiz ýagtylygy',
'exif-lightsource-2'   => 'Flýuoressent',
'exif-lightsource-3'   => 'Gyzdyryş lampasy',
'exif-lightsource-4'   => 'Wspyşka',
'exif-lightsource-9'   => 'Gowy howa',
'exif-lightsource-10'  => 'Bulutly howa',
'exif-lightsource-11'  => 'Kölegeli',
'exif-lightsource-12'  => 'Gündiz ýagtysy flýuoresent (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Gündizlik ak flýuoresent (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Tebigy ak flýuoresent (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Ak flýuoresent (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Standart ýagtylyk A',
'exif-lightsource-18'  => 'Standart ýagtylyk B',
'exif-lightsource-19'  => 'Standart ýagtylyk C',
'exif-lightsource-24'  => 'ISO studiýa lampasy',
'exif-lightsource-255' => 'Başga ýagtylyk çeşmeleri',

# Flash modes
'exif-flash-fired-0'    => 'Wspyşka işlemedi',
'exif-flash-fired-1'    => 'Waspyşka işledi',
'exif-flash-return-2'   => 'başky wspyşkanyň gaýdyş impulsy kesgitlenmedi',
'exif-flash-return-3'   => 'başky wspyşkanyň gaýdyş impulsy kesgitlendi',
'exif-flash-mode-1'     => 'mejbury wspyşka impulsy',
'exif-flash-mode-2'     => 'mejbury wspyşka ýapyk',
'exif-flash-mode-3'     => 'awtomatik režim',
'exif-flash-function-1' => 'Wspyşka ýapyk',
'exif-flash-redeye-1'   => 'gyzyl göz effektini aýyrmak režimi',

'exif-focalplaneresolutionunit-2' => 'dýuým',

'exif-sensingmethod-1' => 'Kesgitlenmedik',
'exif-sensingmethod-2' => 'Ýeke çip reňkli matrisaly sensor',
'exif-sensingmethod-3' => 'Iki çip reňkli matrisaly sensor',
'exif-sensingmethod-4' => 'Üç çip reňkli matrisaly sensor',
'exif-sensingmethod-5' => 'Reňk yzygiderlikli matrisaly sensor',
'exif-sensingmethod-7' => 'Üç reňkli çyzykly sensor',
'exif-sensingmethod-8' => 'Reňk yzygiderlikli çyzykly sensor',

'exif-scenetype-1' => 'Gönümel düşürilen surat',

'exif-customrendered-0' => 'Adaty',
'exif-customrendered-1' => 'Hususy proses',

'exif-exposuremode-0' => 'Awtomatik ekspozisiýa',
'exif-exposuremode-1' => 'El bilen ekspozisiýa',
'exif-exposuremode-2' => 'Awto brakeraž',

'exif-whitebalance-0' => 'Awtomatik ak balans',
'exif-whitebalance-1' => 'El bilen ak balans',

'exif-scenecapturetype-0' => 'Standart',
'exif-scenecapturetype-1' => 'Landşaft',
'exif-scenecapturetype-2' => 'Portret',
'exif-scenecapturetype-3' => 'Gijeki düşüriş',

'exif-gaincontrol-0' => 'Hiç hili',
'exif-gaincontrol-1' => 'Pes köpelme',
'exif-gaincontrol-2' => 'Güýçli köpelme',
'exif-gaincontrol-3' => 'Pes azalma',
'exif-gaincontrol-4' => 'Güýçli azalma',

'exif-contrast-0' => 'Adaty',
'exif-contrast-1' => 'Ýumşak',
'exif-contrast-2' => 'Gaty',

'exif-saturation-0' => 'Adaty',
'exif-saturation-1' => 'Pes doýgunluk',
'exif-saturation-2' => 'Ýokary doýgunluk',

'exif-sharpness-0' => 'Adaty',
'exif-sharpness-1' => 'Ýumşak',
'exif-sharpness-2' => 'Gaty',

'exif-subjectdistancerange-0' => 'Näbelli',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Ýakyndan görüniş',
'exif-subjectdistancerange-3' => 'Uzakdan görüniş',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Demirgazyk giňişlik',
'exif-gpslatitude-s' => 'Günorta giňişlik',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Gündogar uzaklyk',
'exif-gpslongitude-w' => 'Günbatar uzaklyk',

'exif-gpsstatus-a' => 'Ölçemeklik dowam edýär',
'exif-gpsstatus-v' => 'Ölçegiň funksional sazlaşygy',

'exif-gpsmeasuremode-2' => '2-ölçegli ölçeg',
'exif-gpsmeasuremode-3' => '3-ölçegli ölçeg',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'km/sagat',
'exif-gpsspeed-m' => 'mil/sagat',
'exif-gpsspeed-n' => 'Uzel (deňiz mili)',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Hakyky ugur',
'exif-gpsdirection-m' => 'Magnit ugur',

# External editor support
'edit-externally'      => 'Bu faýly daşarky programmalary ulanyp redaktirläň',
'edit-externally-help' => '(Has köp maglumat üçin metadaky [http://www.mediawiki.org/wiki/Manual:External_editors gurmak boýunça gollanma] sahypasyna göz aýlaň)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ählisi',
'imagelistall'     => 'ählisi',
'watchlistall2'    => 'Ählisini görkez',
'namespacesall'    => 'ählisi',
'monthsall'        => 'ählisi',
'limitall'         => 'ählisi',

# E-mail address confirmation
'confirmemail'             => 'E-poçta adresini tassykla',
'confirmemail_noemail'     => '[[Special:Preferences|Ulanyjy sazlamalaryňyzda]] bellenilen dogry bir e-poçta adresiňiz ýok.',
'confirmemail_text'        => '{{SITENAME}} saýtynyň e-poçta amallaryny ulanmak üçin, ilki bilen e-poçta adresiňiziň tassyklanmagy zerurdyr.
Adresiňize tassyklama e-poçtasyny ibermek üçin aşakdaky düwmä basyň.
Iberiljek habarda içinde kod ýazylan bir çykgyt bolar;
e-poçta adresiňiziň dogrudygyny tassyklamak üçin ol çykgydy öz brauzeriňizde açyň.',
'confirmemail_pending'     => 'Tassyklama kody size eýýäm ugradylypdyr;
eger hasabyňyzy ýaňy-ýakynda açan bolsaňyz, ol gelýänçä biraz tagapyl ediň, bolmasa täze kod sorap bilersiňiz.',
'confirmemail_send'        => 'Tassyklama koduny ugrat',
'confirmemail_sent'        => 'Tassyklama e-poçtasy ugradyldy.',
'confirmemail_oncreate'    => 'Tassyklama kody e-poçta adresiňize iberildi.
Sessiýa açmak üçin bu kod gerek däldir, emma bu wikidäki haýsydyr bir bir e-poçta amalyny işletmek üçin muny üpjün etmegiňiz zerurdyr.',
'confirmemail_sendfailed'  => '{{SITENAME}} tassyklama poçtaňyzy iberip bilmedi. E-poçtaňyz nädogry ýazylan bolmagy ähtimal. Barlap görüň.

Serwer yzyna gaýtardy:$1',
'confirmemail_invalid'     => 'Nädogry tassyklama kody. Koduň möhleti gutaran bolmagy ahmal.',
'confirmemail_needlogin'   => 'E-poçta adresiňizi tassyklamak üçin $1-yň.',
'confirmemail_success'     => 'E-poçta adresiňiz tassyklandy. [[Special:UserLogin|Sessiýa açyň-da]] wikini ulanyberiň.',
'confirmemail_loggedin'    => 'E-poçta adresiňiz tassyklandy.',
'confirmemail_error'       => 'Tassyklamaňyz ýazdyrylanda bir ýalňyşlyk boldy.',
'confirmemail_subject'     => '{{SITENAME}} e-poçta adres tassyklamasy.',
'confirmemail_body'        => 'Kimdir biri, ähtimal özüňiz, $1 IP adresinden,
{{SITENAME}} saýtynda bu e-poçta adresi bilen $2 hasabyny açdy.   

Bu hasabyň hakykatdan-da size degişlidigini tassyklamak hem-de {{SITENAME}} saýtyndaky 
e-poçta amallaryny işjeňleşdirmek üçin aşakdaky çykgydy öz brauzeriňizde açyň.

$3

Eger-de hasaby siz *açmadyk bolsaňyz*, e-poçta adresi tassyklamasyny 
ýatyrmak üçin aşakdaky çykgydy yzarlaň:

$5

Bu tassyklama kody $4 senesine çenli güýjüni saklaýar.',
'confirmemail_invalidated' => 'E-poçta tassyklamasy ýatyryldy',
'invalidateemail'          => 'E-poçta tassyklamasyny ýatyr',

# Scary transclusion
'scarytranscludedisabled' => '[Wikiara atanaklaýyn girizme ýapyk]',
'scarytranscludefailed'   => '[$1 üçin şablon äkelmeklik başa barmady]',
'scarytranscludetoolong'  => '[URL örän uzyn]',

# Trackbacks
'trackbackbox'      => 'Bu sahypa üçin trackbackler:<br />
$1',
'trackbackremove'   => '([$1 Öçür])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback şowly öçürildi.',

# Delete conflict
'deletedwhileediting' => "'''Duýduryş''': Bu sahypa siz redaktirläp başlanyňyzdan soňra öçürildi!",
'confirmrecreate'     => "Bu sahypany [[User:$1|$1]] ([[User talk:$1|çekişme]]) ulanyjysy siz sahypany redaktirläp otyrkaňyz öçürdi, sebäbi:
: ''$2''
Sahypany gaýtadan döretmek isleýän bolsaňyz, tassyklamagyňyzy haýyş edýäris.",
'recreate'            => 'Gaýtadan döret',

# action=purge
'confirm_purge_button' => 'Bolýar',
'confirm-purge-top'    => 'Bu sahypanyň keşini boşatmalymy?',
'confirm-purge-bottom' => 'Bir sahypany arassalamaklyk keşi boşadýar we iň täze wersiýany görüner ýaly edýär.',

# Multipage image navigation
'imgmultipageprev' => '← öňki sahypa',
'imgmultipagenext' => 'indiki sahypa →',
'imgmultigo'       => 'Git!',
'imgmultigoto'     => '$1 sahypasyna git',

# Table pager
'ascending_abbrev'         => 'kiçiden ula',
'descending_abbrev'        => 'uludan kiçä',
'table_pager_next'         => 'Indiki sahypa',
'table_pager_prev'         => 'Öňki sahypa',
'table_pager_first'        => 'Birinji sahypa',
'table_pager_last'         => 'Soňky sahypa',
'table_pager_limit'        => 'Sahypa başyna $1 element görkez',
'table_pager_limit_submit' => 'Git',
'table_pager_empty'        => 'Netije ýok',

# Auto-summaries
'autosumm-blank'   => 'Sahypany boşatdy',
'autosumm-replace' => "Mazmun '$1' bilen çalşyryldy",
'autoredircomment' => '[[$1]] sahypasyna gönükdirildi',
'autosumm-new'     => "Sahypa döretdi, mazmuny: '$1'",

# Live preview
'livepreview-loading' => 'Ýüklenýär...',
'livepreview-ready'   => 'Ýüklenýär... Taýýar!',
'livepreview-failed'  => 'Gönümel deslapky syn şowsuz boldy! Adaty deslapky syny synap görüň.',
'livepreview-error'   => 'Birigip bolmady: $1 "$2".
Adaty deslapky syny synap görüň.',

# Friendlier slave lag warnings
'lag-warn-normal' => '$1 {{PLURAL:$1|sekuntdan|sekuntdan}} täzeki üýtgeşmeler bu sanawda görkezilmän biler.',
'lag-warn-high'   => 'Maglumat bazasyndaky uly gijikme zerarly, $1 {{PLURAL:$1|sekuntdan|sekuntdan}} täzeki üýtgeşmeler bu sanawda görkezilmän biler.',

# Watchlist editor
'watchlistedit-numitems'       => 'Gözegçilik sanawyňyzda çekişme sahypalaryny hasap etmäniňde {{PLURAL:$1|1 sany at|$1 sany at}} bar.',
'watchlistedit-noitems'        => 'Gözegçilik sanawyňyzda hiç hili at ýok.',
'watchlistedit-normal-title'   => 'Gözegçilik sanawyny redaktirle',
'watchlistedit-normal-legend'  => 'Gözegçilik sanawyndan atlary aýyr',
'watchlistedit-normal-explain' => 'Gözegçilik sanawyňyzdaky atlar aşakda görkezilýär.
At aýyrmak üçin gapdalyndaky gutujygy belläp, Atlary aýyr düwmesine basyň.
[[Special:Watchlist/raw|işlenmedik sanawy hem redaktirläp]] bilersiňiz.',
'watchlistedit-normal-submit'  => 'Atlary aýyr',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 sany at|$1 sany at}} gözegçilik sanawyndan aýyryldy:',
'watchlistedit-raw-title'      => 'Işlenmedik gözegçilik sanawyny redaktirle',
'watchlistedit-raw-legend'     => 'Işlenmedik gözegçilik sanawyny redaktirle',
'watchlistedit-raw-explain'    => 'Gözegçilik sanawyňyzdaky atlar aşakda görkezilýär. Hersinde bir at ýerleşýän setirleri goşmak ýa-da aýyrmak arkaly sanawy üýtgedip bilersiňiz. Bolanyňyzdan soňra "Gözegçilik sanawyny täzele" düwmesine basyň. Şeýle-de siz  [[Special:Watchlist/edit|adaty usuldan]] hem peýdalanyp bilersiňiz.',
'watchlistedit-raw-titles'     => 'Atlar:',
'watchlistedit-raw-submit'     => 'Gözegçilik sanawyny täzele',
'watchlistedit-raw-done'       => 'Gözegçilik sanawyňyz täzelendi.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 sany at|$1 sany at}} goşuldy:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 sany at|$1 sany at}} öçürildi:',

# Watchlist editing tools
'watchlisttools-view' => 'Degişli üýtgeşmeleri görkez',
'watchlisttools-edit' => 'Gözegçilik sanawyna göz aýla we redaktirle',
'watchlisttools-raw'  => 'Işlenmedik gözegçilik sanawyny redaktirle',

# Core parser functions
'unknown_extension_tag' => 'Näbelli giňeltme belligi "$1"',
'duplicate-defaultsort' => '\'\'\'Duýduryş\'\'\': Gaýybana "$2" sortlaýyş açary mundan ozalky "$1" sortlaýyş açaryny aradan aýyrýar.',

# Special:Version
'version'                          => 'Wersiýa',
'version-extensions'               => 'Gurulgy giňeltmeler',
'version-specialpages'             => 'Ýörite sahypalar',
'version-parserhooks'              => 'Analizator ilgençekleri',
'version-variables'                => 'Üýtgeýänler',
'version-other'                    => 'Başga',
'version-mediahandlers'            => 'Media işleýjiler',
'version-hooks'                    => 'Ilgençekler',
'version-extension-functions'      => 'Giňeltme funksiýalary',
'version-parser-extensiontags'     => 'Analizator giňeltme bellikleri',
'version-parser-function-hooks'    => 'Analizator funsiýasynyň ilgençekleri',
'version-skin-extension-functions' => 'Bezeg giňeltme funksiýalary',
'version-hook-name'                => 'Ilgençegiň ady',
'version-hook-subscribedby'        => 'Abuna ýazylan',
'version-version'                  => '(Wersiýa $1)',
'version-license'                  => 'Ygtyýarnama',
'version-software'                 => 'Gurlan programma üpjünçiligi',
'version-software-product'         => 'Önüm',
'version-software-version'         => 'Wersiýa',

# Special:FilePath
'filepath'         => 'Faýla barýan ýol',
'filepath-page'    => 'Faýl:',
'filepath-submit'  => 'Ýol',
'filepath-summary' => 'Bu ýörite sahypa faýla barýan doly ýoly gaýtaryp getirýär. 
Suratlar doly ölçegde görkezilýär, beýleki faýl görnüşleri degişli programmalary bilen gönümel başladylýar. 

Faýlyň adyny "{{ns:file}}:" pristawkasyz giriziň.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Dublikat faýllaryň gözlegi',
'fileduplicatesearch-summary'  => 'Heş kodlary boýunça meňzeş faýllary gözle.

Faýlyň adyny "{{ns:file}}:" pristawkasyz giriziň.',
'fileduplicatesearch-legend'   => 'Dublikatyny gözle',
'fileduplicatesearch-filename' => 'Faýlyň ady:',
'fileduplicatesearch-submit'   => 'Gözle',
'fileduplicatesearch-info'     => '$1 × $2 piksel<br />Faýlyň ölçegi: $3<br />MIME tipi: $4',
'fileduplicatesearch-result-1' => '"$1" faýlynyň meňzeş dublikaty ýok.',
'fileduplicatesearch-result-n' => '"$1" faýlynyň {{PLURAL:$2|1 sany meňzeş dublikaty|$2 sany meňzeş dublikaty}} bar.',

# Special:SpecialPages
'specialpages'                   => 'Ýörite sahypalar',
'specialpages-note'              => '----
* Adaty ýörite sahypalar.
* <strong class="mw-specialpagerestricted">Çäklendirilen ýörite sahypalar.</strong>',
'specialpages-group-maintenance' => 'Tehniki abatlaýyş hasabatlary',
'specialpages-group-other'       => 'Başga ýörite sahypalar',
'specialpages-group-login'       => 'Sessiýa aç / hasap edin',
'specialpages-group-changes'     => 'Soňky üýtgeşmeler we gündelikler',
'specialpages-group-media'       => 'Media hasabatlary we ýüklemeler',
'specialpages-group-users'       => 'Ulanyjylar we hukuklar',
'specialpages-group-highuse'     => 'Köp ulanylýan sahypalar',
'specialpages-group-pages'       => 'Sahypalaryň sanawlary',
'specialpages-group-pagetools'   => 'Sahypa gurallary',
'specialpages-group-wiki'        => 'Wiki maglumatlar we gurallar',
'specialpages-group-redirects'   => 'Gönükdirmeli ýörite sahypalar',
'specialpages-group-spam'        => 'Spam gurallary',

# Special:BlankPage
'blankpage'              => 'Boş sahypa',
'intentionallyblankpage' => 'Bu sahypa ýörite boş goýuldy.',

# External image whitelist
'external_image_whitelist' => '#Bu setiri bolşy ýaly goýuň<pre>
#Tertipli aňlatma fragmentlerini (diňe // aralygyndaky bölegi) aşak goşuň
#Bular daşarky (hotlink) suratlaryň URL-leri bilen deňeşdiriljekdir
#Deň gelenler surat bolup görüner, galanlary bolsa diňe suratyň çykgydy hökmünde görkeziler
# # bilen başlaýan setirler teswir hasap ediljekdir
#Setirler baş we setir harplara duýgur däldir

#Ähli tertipli aňlatma fragmentlerini bu setiriň üstüne goşuň. Bu setiri bolşy ýaly goýuň</pre>',

# Special:Tags
'tags'                    => 'Dogry üýtgeşme bellikleri',
'tag-filter'              => '[[Special:Tags|Bellik]] filtri:',
'tag-filter-submit'       => 'Filtr',
'tags-title'              => 'Bellikler',
'tags-intro'              => 'Bu sahypa programmanyň bir üýtgeşmä goýmagy ahmal bolan belliklerini hem-de olaryň manylaryny görkezýär.',
'tags-tag'                => 'Belligiň ady',
'tags-display-header'     => 'Üýtgeşmeler sanawynyň daşky görnüşi',
'tags-description-header' => 'Manynyň doly düşündirişi',
'tags-hitcount-header'    => 'Bellenen üýtgeşmeler',
'tags-edit'               => 'redaktirle',
'tags-hitcount'           => '$1 {{PLURAL:$1|üýtgeşme|üýtgeşme}}',

# Database error messages
'dberr-header'      => 'Bu wikiniň bir problemasy bar',
'dberr-problems'    => 'Bagyşlaň! Bu saýtda tehniki kynçylyklar ýüze çykdy.',
'dberr-again'       => 'Birnäçe minut garaşyň we gaýtadan ýükläp görüň.',
'dberr-info'        => '(Maglumat bazasynyň serwerine birigip bolanok: $1)',
'dberr-usegoogle'   => 'Ýogsa-da, oňa çenli Google bilen gözleg geçirip bilersiňiz.',
'dberr-outofdate'   => 'Olaryň biziň sahypalarymyz baradaky indeksi köne bolmagy mümkin.',
'dberr-cachederror' => 'Bu talap edilen sahypanyň keşirlenen nusgasy bolup, soňky üýtgeşmeleri görkezmezligi mümkin.',

# HTML forms
'htmlform-invalid-input'       => 'Girizen maglumatyňyzyň bir böleginde problema bar',
'htmlform-select-badoption'    => 'Görkezen bahaňyz dogry opsiýa däl.',
'htmlform-int-invalid'         => 'Görkezen bahaňyz bitin san däl.',
'htmlform-float-invalid'       => 'Görkezen bahaňyz san däl.',
'htmlform-int-toolow'          => 'Görkezen bahaňyz $1 minimumyndan pes.',
'htmlform-int-toohigh'         => 'Görkezen bahaňyz $1 maksimumyndan ýokary.',
'htmlform-submit'              => 'Iber',
'htmlform-reset'               => 'Üýtgeşmeleri yzyna al',
'htmlform-selectorother-other' => 'Başga',

);
