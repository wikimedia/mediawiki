<?php
/** Banjar (Bahasa Banjar)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Alamnirvana
 * @author Ezagren
 * @author J Subhi
 * @author Kaganer
 * @author Riemogerz
 */

$fallback = 'id';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Istimiwa',
	NS_TALK             => 'Pamandiran',
	NS_USER             => 'Pamakai',
	NS_USER_TALK        => 'Pamandiran_Pamakai',
	NS_PROJECT_TALK     => 'Pamandiran_$1',
	NS_FILE             => 'Barakas',
	NS_FILE_TALK        => 'Pamandiran_Barakas',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Pamandiran_MediaWiki',
	NS_TEMPLATE         => 'Citakan',
	NS_TEMPLATE_TALK    => 'Pamandiran_Citakan',
	NS_HELP             => 'Patulung',
	NS_HELP_TALK        => 'Pamandiran_Patulung',
	NS_CATEGORY         => 'Tumbung',
	NS_CATEGORY_TALK    => 'Pamandiran_Tumbung',
);

$namespaceAliases = array(
	// 'id' namespace names at the time 'bjn' namespaces were localised.
	'Istimewa'              => NS_SPECIAL,
	'Pembicaraan'           => NS_TALK,
	'Pengguna'              => NS_USER,
	'Pembicaraan_Pengguna'  => NS_USER_TALK,
	'Pembicaraan_$1'        => NS_PROJECT_TALK,
	'Berkas'                => NS_FILE,
	'Pembicaraan_Berkas'    => NS_FILE_TALK,
	'Pembicaraan_MediaWiki' => NS_MEDIAWIKI_TALK,
	'Templat'               => NS_TEMPLATE,
	'Pembicaraan_Templat'   => NS_TEMPLATE_TALK,
	'Bantuan'               => NS_HELP,
	'Pembicaraan_Bantuan'   => NS_HELP_TALK,
	'Kategori'              => NS_CATEGORY,
	'Pembicaraan_Kategori'  => NS_CATEGORY_TALK,
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Garisi di bawah tautan',
'tog-highlightbroken'         => 'Bantuk tautan pagat <a href="" class="puga">nangkaya ini</a> (pilihan: nangkaya ini<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Ratakan paragraf',
'tog-hideminor'               => 'Sungkupakan babakan sapalih dalam parubahan tahanyar',
'tog-hidepatrolled'           => 'Sungkupakan babakan taawasi dalam parubahan tahanyar',
'tog-newpageshidepatrolled'   => 'Sungkupakan tungkaran nang diitihi matan daptar tungkaran hanyar',
'tog-extendwatchlist'         => 'Singkaiakan daptar itihan hagan manampaiakan samunyaan parubahan, kada nang hanyar haja.',
'tog-usenewrc'                => 'Purukakan panampaian parubahan tahanyar tingkat tinggi (parlu ada JavaScript)',
'tog-numberheadings'          => 'Bari numur judul utumatis',
'tog-showtoolbar'             => 'Tampaiakan bilah-pakakas babak (parlu ada JavaScript)',
'tog-editondblclick'          => 'Babak tutungkaran wan klik ganda (parlu ada JavaScript)',
'tog-editsection'             => "Kawa'akan pambabakan sub-hagian malalui tautan [babak]",
'tog-editsectiononrightclick' => "Kawa'akan mambabak sub-hagian lawan mang-klik kanan pada judul hagian (parlu ada JavaScript)",
'tog-showtoc'                 => 'Tampaiakan daptar isi (gasan tungkaran-tungkaran nang baisi labih dari 3 subbagian)',
'tog-rememberpassword'        => 'Ingatakan babuat log ulun pada panjalajah web ini (gasan salawas $1{{PLURAL:$1|hari|hahari}})',
'tog-watchcreations'          => 'Tambahi tungkaran nang ulun ulah ka daptar itihan',
'tog-watchdefault'            => 'Tambahi tungkaran nang ulun babak ka daptar itihan ulun',
'tog-watchmoves'              => 'Tambahi tungkaran nang ulun pindah ka daptar itihan ulun',
'tog-watchdeletion'           => 'Tambahi tungkaran nang ulun hapus ka daptar itihan ulun',
'tog-minordefault'            => 'Tandai samunyaan babakan sawagai babakan sapalih sacara baku',
'tog-previewontop'            => 'Tampaiakan titilikan sabalum kutak babak',
'tog-previewonfirst'          => 'Tampaiakan titilikan pada babakan panambaian',
'tog-nocache'                 => 'Nonaktifkan panyinggahan tungkaran paramban',
'tog-enotifwatchlistpages'    => 'Kirimi ulun sur-él amun sabuting tungkaran dalam daptar itihan ulun baubah',
'tog-enotifusertalkpages'     => 'Surili ulun amun tungkaran pamandiran ulun baubah',
'tog-enotifminoredits'        => 'Kirimi ulun sur-él jua amun ada babakan sapalih matan tungkaran-tungkaran',
'tog-enotifrevealaddr'        => 'Tampaiakan alamat sur-él ulun pada sur-él pamadahan',
'tog-shownumberswatching'     => 'Tampaiakan barapa pamakai nang maitihi',
'tog-oldsig'                  => 'Tandateken nang sudah ada:',
'tog-fancysig'                => 'Tapsirakan tandatangan sawagai naskah wiki (kada batautan utumatis)',
'tog-externaleditor'          => 'Puruk pambabak luar sawagai default (hagan nang harat haja, musti ada setélan istimiwa pada komputer Pian.[//www.mediawiki.org/wiki/Manual:External_editors More information.])',
'tog-externaldiff'            => 'Puruk palainan luar sawagai default (hagan nang harat haja, musti ada setélan istimiwa pada komputer Pian. [//www.mediawiki.org/wiki/Manual:External_editors More information.])',
'tog-showjumplinks'           => 'Kawa\'akan "lacung ka" tautan kakawaan-masuk',
'tog-uselivepreview'          => 'Puruk titilikan langsung (parlu ada JavaScript) (cacubaan)',
'tog-forceeditsummary'        => 'Ingatakan ulun wayah babuat sabuah kasimpulan babakan kusung',
'tog-watchlisthideown'        => 'Sungkupakan babakan ulun di daptar itihan',
'tog-watchlisthidebots'       => 'Sungkupakan babakan bot di daptar itihan',
'tog-watchlisthideminor'      => 'Sungkupakan babakan sapalih di daptar itihan',
'tog-watchlisthideliu'        => 'Sungkupakan babakan pamakai babuat log di daptar itihan',
'tog-watchlisthideanons'      => 'Sungkupakan babakan pamakai kada bangaran di daptar itihan',
'tog-watchlisthidepatrolled'  => 'Sungkupakan babakan taawasi di daptar itihan',
'tog-ccmeonemails'            => 'Surili ulun salinan suril nang ulun kirim ka pamakai lain',
'tog-diffonly'                => 'Kada usah manampaiakan isi tungkaran di bawah balain',
'tog-showhiddencats'          => 'Tampaiakan tutumbung tasungkup',
'tog-norollbackdiff'          => 'Kada usah manampaiakan lainan imbah mambulikakan',

'underline-always'  => 'Tarus',
'underline-never'   => 'Kada suah',
'underline-default' => 'Default Panjalajahan web',

# Font style option in Special:Preferences
'editfont-style'     => 'Babak wilayah gaya tulisan',
'editfont-default'   => 'Default Panjalajahan web',
'editfont-monospace' => 'Tulisan Monospace',
'editfont-sansserif' => 'Tulisan Sans-serif',
'editfont-serif'     => 'Tulisan Serif',

# Dates
'sunday'        => 'Ahad',
'monday'        => 'Sanayan',
'tuesday'       => 'Salasa',
'wednesday'     => 'Arba',
'thursday'      => 'Kamis',
'friday'        => 'Jumahat',
'saturday'      => 'Saptu',
'sun'           => 'Aha',
'mon'           => 'San',
'tue'           => 'Sal',
'wed'           => 'Arb',
'thu'           => 'Kam',
'fri'           => 'Jum',
'sat'           => 'Sap',
'january'       => 'Januari',
'february'      => 'Pibuari',
'march'         => 'Marit',
'april'         => 'April',
'may_long'      => 'Mai',
'june'          => 'Juni',
'july'          => 'Juli',
'august'        => 'Agustus',
'september'     => 'Siptimbir',
'october'       => 'Uktubir',
'november'      => 'Nupimbir',
'december'      => 'Disimbir',
'january-gen'   => 'Januari',
'february-gen'  => 'Pibuari',
'march-gen'     => 'Marit',
'april-gen'     => 'April',
'may-gen'       => 'Mai',
'june-gen'      => 'Juni',
'july-gen'      => 'Juli',
'august-gen'    => 'Agustus',
'september-gen' => 'Siptimbir',
'october-gen'   => 'Uktubir',
'november-gen'  => 'Nupimbir',
'december-gen'  => 'Disimbir',
'jan'           => 'Jan',
'feb'           => 'Pib',
'mar'           => 'Mar',
'apr'           => 'Apr',
'may'           => 'Mai',
'jun'           => 'Jun',
'jul'           => 'Jul',
'aug'           => 'Agu',
'sep'           => 'Sip',
'oct'           => 'Ukt',
'nov'           => 'Nup',
'dec'           => 'Dis',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Tumbung|Tutumbung}}',
'category_header'                => "Tutungkaran dalam tumbung ''$1''",
'subcategories'                  => 'Sub-tumbung',
'category-media-header'          => 'Média dalam tumbung "$1"',
'category-empty'                 => '"Kada tadapat artikal maupun média dalam tumbung ini."',
'hidden-categories'              => '{{PLURAL:$1|Tumbung tasungkup|Tutumbung tasungkup}}',
'hidden-category-category'       => 'Tumbung tasungkup',
'category-subcat-count'          => '{{PLURAL:$2|Tumbung ngini baisi asa sub-tumbung barikut.|Tumbung ngini baisi {{PLURAL:$1|sub-tumbung|$1 sub-tutumbung}} barikut, matan sabarataan $2.}}',
'category-subcat-count-limited'  => 'Tumbung ini baisi {{PLURAL:$1|sub-tumbung|$1 sub-tutumbung}} barikut.',
'category-article-count'         => '{{PLURAL:$2|Tumbung ni baisi asa tungkaran barikut haja.|Tutumbung ngini baisi {{PLURAL:$1|tungkaran|$1 tutungkaran}}, matan $2 sabarataan.}}',
'category-article-count-limited' => 'Tumbung ini baisi {{PLURAL:$1|asa tungkaran|$1 tutungkaran}} barikut.',
'category-file-count'            => '{{PLURAL:$2|Tumbung ngini hanya baisi asa barakas barikut.|Tumbung ngini baisi {{PLURAL:$1|barakas|$1 babarakas}} barikut, matan $2 sabarataan.}}',
'category-file-count-limited'    => 'Tumbung ini baisi {{PLURAL:$1|barakas|$1 barakas}} barikut.',
'listingcontinuesabbrev'         => 'samb.',
'index-category'                 => 'Tungkaran tasusun bapadalakan kata',
'noindex-category'               => 'Tungkaran kada tasusun bapadalakan kata',
'broken-file-category'           => 'Tutungkaran lawan tatautan barakas pagat',

'about'         => 'Pasal',
'article'       => 'Tungkaran isi',
'newwindow'     => '(buka di lalungkang hanyar)',
'cancel'        => 'Walangi',
'moredotdotdot' => 'Lainnya...',
'mypage'        => 'Tungkaran ulun',
'mytalk'        => 'Pamandiran ulun',
'anontalk'      => 'Pamandiran hagan alamat IP ini',
'navigation'    => 'Napigasi',
'and'           => '&#32;wan',

# Cologne Blue skin
'qbfind'         => 'Paugaian',
'qbbrowse'       => 'Tangadahi',
'qbedit'         => 'Babak',
'qbpageoptions'  => 'Tungkaran ini',
'qbpageinfo'     => 'Naskah aluran',
'qbmyoptions'    => 'Tungkaran ulun',
'qbspecialpages' => 'Tungkaran istimiwa',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => 'Tambahi tupik',
'vector-action-delete'           => 'Hapus',
'vector-action-move'             => 'Pindahakan',
'vector-action-protect'          => 'Lindungi',
'vector-action-undelete'         => 'Pawalangan pahapusan',
'vector-action-unprotect'        => 'Palindungan',
'vector-simplesearch-preference' => 'Kawa-akan saran panggagaian tingkat lanjut (Vector skin haja)',
'vector-view-create'             => 'Ulah',
'vector-view-edit'               => 'Babak',
'vector-view-history'            => 'Tiringi halam',
'vector-view-view'               => 'Baca',
'vector-view-viewsource'         => 'Tiringi asal mula',
'actions'                        => 'Tindakan',
'namespaces'                     => 'Ngarankamar',
'variants'                       => 'Macam',

'errorpagetitle'    => 'Kasalahan',
'returnto'          => 'Bulik ka $1.',
'tagline'           => 'Matan {{SITENAME}}',
'help'              => 'Patulung',
'search'            => 'Gagai',
'searchbutton'      => 'Gagai',
'go'                => 'Tulak',
'searcharticle'     => 'Tulak',
'history'           => 'Tungkaran halam',
'history_short'     => 'Tungkaran halam',
'updatedmarker'     => 'Dihanyari tumatan ilangan pauncitan ulun',
'printableversion'  => 'Nang kawa dicitak',
'permalink'         => 'Tautan tatap',
'print'             => 'Citak',
'view'              => 'Tiringi',
'edit'              => 'Babak',
'create'            => 'Ulah',
'editthispage'      => 'Babak tungkaran ini',
'create-this-page'  => 'Ulah tungkaran ini',
'delete'            => 'Hapus',
'deletethispage'    => 'Hapus tungkaran ini',
'undelete_short'    => 'Walang mahapus {{PLURAL:$1|asa babakan|$1 bababakan}}',
'viewdeleted_short' => 'Tiringi {{PLURAL:$1|asa babakan tahapus|$1 bababakan tahapus}}',
'protect'           => 'Lindungi',
'protect_change'    => 'ubah',
'protectthispage'   => 'Lindungi tungkaran ini',
'unprotect'         => 'Palindungan',
'unprotectthispage' => 'Buka palindungan tungkaran ini',
'newpage'           => 'Tungkaran hanyar',
'talkpage'          => 'Pandirakan tungkaran ini',
'talkpagelinktext'  => 'Pandir',
'specialpage'       => 'Tungkaran istimiwa',
'personaltools'     => 'Pakakas surang',
'postcomment'       => 'Palih hanyar',
'articlepage'       => 'Tiringi isi tungkaran',
'talk'              => 'Pamandiran',
'views'             => 'Titiringan',
'toolbox'           => 'Wadah pakakas',
'userpage'          => 'Tiringi tungkaran pamakai',
'projectpage'       => 'Tiringi tungkaran rangka gawian',
'imagepage'         => 'Tiringi tungkaran barakas',
'mediawikipage'     => 'Tiringi tungkaran pasan sistim',
'templatepage'      => 'Tiringi tungkaran citakan',
'viewhelppage'      => 'Tiringi tungkaran patulung',
'categorypage'      => 'Tiringi tungkaran tumbung',
'viewtalkpage'      => 'Tiringi tungkaran pamandiran',
'otherlanguages'    => 'Dalam bahasa lain',
'redirectedfrom'    => '(Diugahakan matan $1)',
'redirectpagesub'   => 'Tungkaran paugahan',
'lastmodifiedat'    => 'Tungkaran ngini tauncit diubah pada $1, $2.',
'viewcount'         => 'Tungkaran ini sudah diungkai {{PLURAL:$1|kali|$1 kali}}.',
'protectedpage'     => 'Tungkaran nang dilindungi',
'jumpto'            => 'Malacung ka',
'jumptonavigation'  => 'napigasi',
'jumptosearch'      => 'gagai',
'view-pool-error'   => "Ampuni, server lagi limpuar kabaratan wayah ini.
Kabanyakan pamakai nang handak maniringi tungkaran ini.
Muhun hadangi ha' sapandang sabalum Pian cubai pulang maungkai tungkaran ini.

$1",
'pool-timeout'      => 'Habis waktu mahadangi gasan tasunduk',
'pool-queuefull'    => 'Antrian hibak',
'pool-errorunknown' => 'Kada tahu napa nang salah',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Pasal {{SITENAME}}',
'aboutpage'            => 'Project:Pasal',
'copyright'            => 'Isi tasadia sasuai lawan $1.',
'copyrightpage'        => '{{ns:project}}:Hak cipta',
'currentevents'        => 'Paristiwa damini',
'currentevents-url'    => 'Project:Paristiwa damini',
'disclaimers'          => 'Panyangkalan',
'disclaimerpage'       => 'Project:Panyangkalan umum',
'edithelp'             => 'Patulung mambabak',
'edithelppage'         => 'Help:Pambabakan',
'helppage'             => 'Help:Isi',
'mainpage'             => 'Tungkaran Tatambaian',
'mainpage-description' => 'Tungkaran Tatambaian',
'policy-url'           => 'Project:Kaaripan',
'portal'               => 'Saképéng bubuhan',
'portal-url'           => 'Project:Saképéng bubuhan',
'privacy'              => 'Kaaripan paribadi',
'privacypage'          => 'Project:Kaaripan paribadi',

'badaccess'        => 'Parijinan tasalah',
'badaccess-group0' => 'Pian kadada ijin hagan malakuakan nang Pian mintai.',
'badaccess-groups' => 'Tindakan nang Pian mintai diwatasi hagan pamakai dalam {{PLURAL:$2|galambang|salah asa matan galambang}}: $1.',

'versionrequired'     => 'Parlu MediaWiki mudil $1',
'versionrequiredtext' => 'MediaWiki mudil $1 diparluakan hagan mamuruk tungkaran ini.
Lihati [[Special:Version|Tungkaran mudil]]',

'ok'                      => 'OK',
'retrievedfrom'           => 'Dijumput matan "$1"',
'youhavenewmessages'      => 'Pian baisi $1 ($2)',
'newmessageslink'         => 'pasan hanyar',
'newmessagesdifflink'     => 'parubahan tauncit',
'youhavenewmessagesmulti' => 'Pian baisi pasan hanyar dalam $1',
'editsection'             => 'babak',
'editold'                 => 'babak',
'viewsourceold'           => 'tiringi asal mulanya',
'editlink'                => 'babak',
'viewsourcelink'          => 'tiringi asal mulanya',
'editsectionhint'         => 'Babak hagian: $1',
'toc'                     => 'Isi',
'showtoc'                 => 'tampaiakan',
'hidetoc'                 => 'sungkupakan',
'collapsible-collapse'    => 'Siup',
'collapsible-expand'      => 'Kambangakan',
'thisisdeleted'           => 'Tiringi atawa mambulikakan $1?',
'viewdeleted'             => 'Tiringi $1?',
'restorelink'             => '$1 {{PLURAL:$1|babakan|babakan}} nang sudah dihapus',
'feedlinks'               => 'Kitihan',
'feed-invalid'            => 'Macam pamintaan kitihan kada pas.',
'feed-unavailable'        => 'Kitihan sindikasi kadada',
'site-rss-feed'           => 'Kitihan RSS $1',
'site-atom-feed'          => 'Kitihan Atum $1',
'page-rss-feed'           => "Kitihan RSS ''$1''",
'page-atom-feed'          => "Kitihan Atum ''$1''",
'red-link-title'          => '$1 (tungkaran baluman ada)',
'sort-descending'         => 'Surtir baturun',
'sort-ascending'          => 'Surtir banaik',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Tungkaran',
'nstab-user'      => 'Pamakai',
'nstab-media'     => 'Média',
'nstab-special'   => 'Tungkaran istimiwa',
'nstab-project'   => 'Tungkaran rangka gawian',
'nstab-image'     => 'Barakas',
'nstab-mediawiki' => 'Pasan',
'nstab-template'  => 'Citakan',
'nstab-help'      => 'Patulung',
'nstab-category'  => 'Tumbung',

# Main script and global functions
'nosuchaction'      => 'Kadada palakuan nangkaitu',
'nosuchactiontext'  => 'Palakuan nang diminta URL kada sah.
Pian pinanya salah katik URL, atawa maumpati sabuah tautan nang kada bujur.
Ngini jua bisa ai ada bug di parangkat lunak nang dipuruk {{SITENAME}}.',
'nosuchspecialpage' => 'Kadada tungkaran istimiwa nangitu',
'nospecialpagetext' => '<strong>Pian maminta tungkaran istimiwa nang kada sah.</strong>
Daptar tungkaran istimiwa sah kawa diugai pada [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Kasalahan',
'databaseerror'        => 'Kasalahan Basisdata',
'dberrortext'          => 'Ada kasalahan sintaks pada parmintaan basisdata.
Kasalahan ngini pina manandai adanya sabuah bug dalam parangkat lunak.
Parmintaan basisdata yang tadudi adalah:
<blockquote><tt>$1</tt></blockquote>
matan dalam pungsi "<tt>$2</tt>".
Basisdata kasalahan  babulik "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Ada kasalahan sintaks pada parmintaan basisdata.
Parmintaan basisdata nang tadudi adalah:
"$1"
matan dalam pungsi "$2".
Basisdata kasalahan  babulik "$3: $4".',
'laggedslavemode'      => "'''Paringatan:''' Tungkaran ngini pinanya kada baisi parubahan tahanyar.",
'readonly'             => 'Basisdata tasunduk',
'enterlockreason'      => 'Buati sabuah alasan manyunduk, tamasuk jua wayah apa handak dibuka pulang sundukannya.',
'readonlytext'         => 'Basisdata lagi basunduk hagan masukan hanyar wan parubahan lain, pinanya pang lagi ada jadwal paharaguan basis data, imbah ini akan babulik nangkaya biasa.

Pambakal nang manyunduk mamadahi nangkaini: $1',
'missing-article'      => 'Basisdata kada ulihan manggagai kata matan tungkaran nang saharusnya ada, bangaran "$1" $2.

Nangkaini biasanya dimargakan tautan lawas ka sabuah tungkaran nang halamnya sudah dihapus.

Munnya lainan ngini pasalnya, Pian mungkin batamu bug dalam perangkat lunak.
Silakan lapurakan ngini ka saurang [[Special:ListUsers/sysop|pambakal]], ulah catatan URL nang ditulaki',
'missingarticle-rev'   => '(ralatan#: $1)',
'missingarticle-diff'  => '(Lain: $1, $2)',
'readonly_lag'         => 'Basisdata sudah utumatis tasunduk pas server basisdata dua lagi marungkuti basisdata tatambaian',
'internalerror'        => 'Kasalahan di dalam',
'internalerror_info'   => 'Kasalahan di dalam: $1',
'fileappenderrorread'  => 'Kada kawa mambaca "$1" parhatan manambahi.',
'fileappenderror'      => 'Kada kawa mamasukakan "$1" ka "$2".',
'filecopyerror'        => 'Kada kawa manyalin "$1" ka "$2".',
'filerenameerror'      => 'Kada kawa maubah ngaran barakas "$1" manjadi "$2".',
'filedeleteerror'      => 'Kada kawa mahapus barakas "$1".',
'directorycreateerror' => 'Kada kawa maulah direktori "$1".',
'filenotfound'         => 'Kada kawa maugai barakas "$1".',
'fileexistserror'      => 'Kada kawa manulis ka barakas "$1": barakas sudah ada',
'unexpected'           => 'Nilai kada sasuai harapan: "$1"="$2".',
'formerror'            => 'Kasalahan: kada kawa mangirim purmulir',
'badarticleerror'      => 'Palakuan ngini kada kawa dicungulakan pada tungkaran ngini.',
'cannotdelete'         => "Tungkaran atawa barakas ''$1'' kada kawa dihapus. Pinanya sudah dihapus urang lain badahulu.",
'cannotdelete-title'   => 'Kada kawa mahapus tungkaran "$1"',
'badtitle'             => 'Judul buruk',
'badtitletext'         => 'Judul tungkaran nang diminta kada sah, kada baisi, atawa kada pasnya tautan judul antar-bahasa atawa antar-wiki.
Nangini bisa baisi satu atawa labih hurup nang saharusnya kadada di judul.',
'perfcached'           => 'Data barikut adalah timbuluk wan pina kada mutakhir. A maximum of {{PLURAL:$1|one result is|$1 results are}} available in the cache.',
'perfcachedts'         => 'Data nang dudi ni adalah timbuluk, wan tauncit dihahanyari pada $1. A maximum of {{PLURAL:$4|one result is|$4 results are}} available in the cache.',
'querypage-no-updates' => 'Pamugaan matan tungkaran ngini rahat dipajahkan. Data nang ada di sia wayahini kada akan dimuat ulang.',
'wrong_wfQuery_params' => 'Kada bujur ukuran ka wfQuery ()<br />
Pungsi: $1<br />
Parmintaan: $2',
'viewsource'           => 'Tiringi asal mulanya',
'viewsource-title'     => 'Tiringi asalmula matan $1',
'actionthrottled'      => 'Kalakuan dikiripi',
'actionthrottledtext'  => 'Sawagai sabuah takaran anti-spam, Pian dibabatasi hagan balalaku kababanyakan dalam parhatan handap, wan Pian sudah limpuari batasan ngini.
Muhun cubai pulang dalam babarapa minit.',
'protectedpagetext'    => 'Tungkaran ngini sudah dilindungi hagan mancagah babakan.',
'viewsourcetext'       => 'Pian kawa maniringi wan manyalin asal mula tungkaran ngini:',
'viewyourtext'         => "Pian kawa maniringi wan salain asalmula matan '''babakan pian''' ka tungkaran ngini:",
'protectedinterface'   => 'Tungkaran ini manyadiakan naskah antarmuha gasan parangkat lunak, wan dilindungi hagan mancagah tasalah puruk.',
'editinginterface'     => "'''Paringatan:''' Pian mambabak sabuah tungkaran nang dipuruk hagan manyadiakan naskah antarmuha gasan parangkat lunak.
Parubahan ka tungkaran ngini akan bapangaruh matan tampaian antarmuha gasan pamakai lain.
Gasan tarjamahan, muhun puruk [//translatewiki.net/wiki/Main_Page?setlang=bjn translatewiki.net], rangka gawian palokalan MediaWiki.",
'sqlhidden'            => '(Parmintaan SQL disungkupakan)',
'cascadeprotected'     => 'Tungkaran ini sudah dilindungi matan pambabakan, marga nangini tamasuk dalam {{PLURAL:$1|tungkaran|tutungkaran}} dudi nang dilindungi "barénténg": $2',
'namespaceprotected'   => "Pian kada baisi ijin hagan mambabak tutungkaran dalam ngaran kamar '''$1'''.",
'customcssprotected'   => 'Pian kada baisi ijin mambabak tungkaran CSS ngini, karana ngini baisi setelan paribadi pamakai lain.',
'customjsprotected'    => 'Pian kada baisi ijin mambabak tungkaran JavaScript ngini, karana ngini baisi setelan paribadi pamakai lain.',
'ns-specialprotected'  => 'Tungkaran istimiwa kada kawa dibabak.',
'titleprotected'       => "Judul ngini dilindungi matan paulahan ulih [[User:$1|$1]].
Alasan nang dibariakan adalah ''$2''.",

# Virus scanner
'virus-badscanner'     => "Konpigurasi buruk: pamindai virus kada dipinandui: ''$1''",
'virus-scanfailed'     => 'Pamindaian gagal (kudi $1)',
'virus-unknownscanner' => 'Antivirus kada dipinandui:',

# Login and logout pages
'logouttext'                 => "'''Pian parhatan ni sudah kaluar log.'''

Pian kawa manyambung hagan mangguna'akan {{SITENAME}} kada bangaran, atawa Pian kawa [[Special:UserLogin|babuat log pulang]] sawagai pamakai nang sama atawa sawagai pamakai balain.
Catatan bahwasa babarapa tungkaran pinanya masih ha tarus manampaiakan Pian masih babuat log, sampai Pian mahabisakan timbuluk panjalajah web Pian.",
'welcomecreation'            => '==Salamat datang, $1!==
Akun Pian sudah diulah.
Jangan kada ingat hagan maubah [[Special:Preferences|kakatujuan {{SITENAME}}]] Pian.',
'yourname'                   => 'Ngaran pamakai:',
'yourpassword'               => 'Katasunduk:',
'yourpasswordagain'          => 'Katik pulang katasunduk:',
'remembermypassword'         => 'Ingatakan log babuat ulun dalam komputer naya (salawas $1 {{PLURAL:$1|hari|hari}})',
'securelogin-stick-https'    => 'Tatap tasambung awan HTTPS imbah babuat-log',
'yourdomainname'             => 'Domain Pian:',
'externaldberror'            => 'Ada kasalahan apakah kacucukan basis data atawa Pian kada bulih mamutakhirakan akun luar.',
'login'                      => 'Babuat',
'nav-login-createaccount'    => 'Babuat log / ulah akun',
'loginprompt'                => "Pian harus mangaktipakan ''cookies'' hagan kawa babuat log ka {{SITENAME}}.",
'userlogin'                  => 'Babuat log / ulah akun',
'userloginnocreate'          => 'Babuat log',
'logout'                     => 'Kaluar',
'userlogout'                 => 'Kaluar',
'notloggedin'                => 'Balum babuat log',
'nologin'                    => 'Kada baisi sabuah akun? $1.',
'nologinlink'                => 'Ulah sabuting akun',
'createaccount'              => 'Ulah akun',
'gotaccount'                 => 'Hudah baisi sabuting akun? $1.',
'gotaccountlink'             => 'Babuat log',
'userlogin-resetlink'        => 'Kada ingat rarincian babuat log Pian?',
'createaccountmail'          => 'Malalui suril',
'createaccountreason'        => 'Alasan:',
'badretype'                  => 'Katasunduk nang Pian buati kada pas.',
'userexists'                 => 'Ngaran pamakai nang dibuati hudah dipuruk urang lain.
Muhun pilih sabuting ngaran lain.',
'loginerror'                 => 'Kasalahan babuat log',
'createaccounterror'         => 'Kada kawa maulah akun: $1',
'nocookiesnew'               => "Akun pamakai hudah diulah, tagal Pian kada babuat log.
{{SITENAME}} mamakai ''cookies'' hagan pamakai babuat log.
''Cookies'' Pian lagi kada kawa.
Muhun kawa'akan nangitu, hanyar babuat log awan ngaran pamakai hanyar wan katasunduk Pian.",
'nocookieslogin'             => "{{SITENAME}} mangguna'akan ''cookies'' hagan pamakai babuat log.
''Cookies'' Pian lagi kada kawa.
Muhun kawa'akan nang itu wan cubai pulang.",
'nocookiesfornew'            => "Akun pamakai kada ta'ulah, sualnya kami kada kawa mamastiakan asal mula.
Yakinakan Pian hudah mangkawa-akan cookies, muat pulang tungkaran naya wan cubai ja lagi.",
'noname'                     => 'Ngaran pamakai nang Pian ajuakan kada sah.',
'loginsuccesstitle'          => 'Kulihan babuat log',
'loginsuccess'               => "'''Pian parhatan ni babuat log dalam {{SITENAME}} sawagai \"\$1\".'''",
'nosuchuser'                 => 'Kadada pamakai bangaran "$1".
Ngaran pamakai adalah kasus marinci.
Lihati pulang ijaan Pian, atawa [[Special:UserLogin/signup|ulah sabuting akun hanyar]]',
'nosuchusershort'            => 'Kadada pamakai bangaran "$1".
Lihati pulang ijaan Pian.',
'nouserspecified'            => "Pian harus ma'ajuakan sabuting ngaran pamakai.",
'login-userblocked'          => 'Pamakai naya diblukir. Babuat log kada dibulihakan.',
'wrongpassword'              => 'Kada sunduk kada bujur nang dibuati.
Muhun cubai pulang.',
'wrongpasswordempty'         => 'Kata sunduk nang dibuati puang.
Muhun cubai pulang.',
'passwordtooshort'           => 'Kata sunduk musti paling sadikit {{PLURAL:$1|1 karaktir|$1 karaktir}}.',
'password-name-match'        => 'Kata sunduk Pian musti babida lawan ngaran pamakai Pian.',
'password-login-forbidden'   => 'Mamakai ngaran wan katasunduk nangini hudah ditangati.',
'mailmypassword'             => 'Kirimi kata sunduk hanyar',
'passwordremindertitle'      => 'Kata sunduk pahadangan gasan {{SITENAME}}',
'passwordremindertext'       => 'Ada urang (pinanya Pian, matan alamat IP $1) maminta sabuting katasunduk hanyar gasan {{SITENAME}} ($4). Sabuting katasunduk pahadangan gasan pamakai "$2" hudah diulah wan disetel ka "$3". Amun bujur Pian nang maminta, Pian parlu babuat log wan mamilih katasunduk hanyar wayah ni jua. Katasunduk pahadangan Pian akan kadaluarsa dalam {{PLURAL:$5|asa hari|$5 hari}}.

Amun urang lain nang maminta ngini, atawa amun Pian sudah paingatan awan katasunduk Pian, wan Pian kada handak maubahnya, Pian kawa kada mahuwal pasan ngini wan manyambung mamakai katasunduk lawas Pian.',
'noemail'                    => 'Kadada alamat suril tarakam gasan pamakai "$1".',
'noemailcreate'              => 'Pian parlu manyadiakan sabuah alamat suril nang sah',
'passwordsent'               => 'Sabuting kata sunduk hanyar sudah dikirim ka suril tadaptar gasan "$1".
Muhun babuat log pulang habis Pian manarima nangini.',
'blocked-mailpassword'       => 'Alamat IP Pian diblukir hagan mambabak, wan kada dibulihakan mamakai pungsi pamulihan kata sunduk hagan mancagah salah puruk.',
'eauthentsent'               => 'Sabuting suril payakinan hudah dikirim ka alamat suril.
Sabalum ada suril lain nang takirim ka akun, Pian akan parlu maumpati anjuran dalam suril nangitu, hagan mayakinakan bahwasanya akun nangitu bujur-bujur ampun Pian.',
'throttled-mailpassword'     => 'Sabuting pangingat kata sunduk hudah takirim, dalam {{PLURAL:$1|jam|$1 jam}} tauncit. Hagan mancagah salah puruk, asa pangingat kata sunduk haja nang dikirim saban {{PLURAL:$1|jam|$1 jam}}.',
'mailerror'                  => 'Kasalahan pangiriman suril: $1',
'acct_creation_throttle_hit' => "Pa'ilang wiki nangini mamuruk alamat IP Pian hudah maulah {{PLURAL:$1|1 akun|$1 akun}} dalam asa harian ini, dimana nangitu jumlah paling banyak nang diijinakan. Sawagai hasilnya, pa'ilang awan alamat IP nangini kada kawa maulah akun pulang gasan pahadangan.",
'emailauthenticated'         => 'Alamat suril Pian rasuk pada  $2, $3',
'emailnotauthenticated'      => 'Alamat suril Pian baluman dirasukakan.
Kadada suril nang akan dikirim maumpati pitur.',
'noemailprefs'               => 'Ajuakan sabuting alamat suril dalam kakatujuan Pian gasan pitur-pitur ini bagawi.',
'emailconfirmlink'           => 'Yakinakan alamat suril Pian',
'invalidemailaddress'        => 'Alamat suril ini kada kawa ditarima karana pormat kada sah.
Muhun buati sabuting alamat suril nang bujur pormatnya atawa puangkan haja isian itu.',
'cannotchangeemail'          => 'Akun alamat suril kada kawa diganti pada wiki ngini.',
'accountcreated'             => 'Akun diulah',
'accountcreatedtext'         => 'Akun pamakai gasan $1 sudah diulah.',
'createaccount-title'        => 'Paulahan akun gasan {{SITENAME}}',
'createaccount-text'         => 'Ada urang nang maulah akun gasan alamat suril Pian pada {{SITENAME}} ($4) bangaran "$2", awan kata sunduk "$3".
Pian dianjurakan babuat log wan maubah kata sunduk Pian parhatan ni.

Pian kawa kada mahual pasan ngini, amun paulahan akun ini adalah kasalahan.',
'usernamehasherror'          => 'Ngaran pamakai kada kawa mangandung tanda kurung',
'login-throttled'            => 'Pian sudah kabanyakan mancuba babuat log.
Muhun hadangi dahulu sapandang hanyar cubai pulang.',
'login-abort-generic'        => 'Pian kada ruhui babuat  log - Diwalangi',
'loginlanguagelabel'         => 'Bahasa: $1',
'suspicious-userlogout'      => 'Parmintaan Pian hagan kaluar log kada ditarima karana nangkaya dikirim matan panjalajah web rakai atawa tatangkap proxy.',

# E-mail sending
'php-mail-error-unknown' => 'Kasalahan kada dipinandui dalam pungsi surat () PHP',
'user-mail-no-addy'      => 'Mancuba mangirim suril kada baalamat suril.',

# Change password dialog
'resetpass'                 => 'Ubah katasunduk',
'resetpass_announce'        => 'Pian babuat log awan sabuah kudi samantara nang disurili.
Hagan manuntungakan babuat log, Pian musti manyetel sabuah katasunduk hanyar di sia:',
'resetpass_header'          => 'Ubah katasunduk akun',
'oldpassword'               => 'Katasunduk lawas:',
'newpassword'               => 'Katasunduk hanyar:',
'retypenew'                 => 'Katik pulang katasunduk hanyar:',
'resetpass_submit'          => 'Setel katasunduk wan babuat log',
'resetpass_success'         => 'Katasunduk Pian bahasil diubah!
Wayah ni Pian sudah babuat log...',
'resetpass_forbidden'       => 'Katasunduk kada kawa diubah',
'resetpass-no-info'         => 'Pian musti babuat log hagan babuat ka tungkaran ini langsung.',
'resetpass-submit-loggedin' => 'Ubah katasunduk',
'resetpass-submit-cancel'   => 'Walangi',
'resetpass-wrong-oldpass'   => 'Katasunduk samantara atawa wayah ni kada sah.
Pian pinanya sudah bahasil maubah katasunduk Pian atawa maminta sabuah katasunduk samantara hanyar.',
'resetpass-temp-password'   => 'Katasunduk samantara:',

# Special:PasswordReset
'passwordreset'                    => 'Bulikakan setelan katasunduk',
'passwordreset-text'               => 'Tuntungakan purmulir ngini gasan manarima sabuah suril pangingat rarincian akun Pian.',
'passwordreset-legend'             => 'Bulikakan setelan katasunduk',
'passwordreset-disabled'           => 'Mambulikakan setelan katasunduk dipajahakan hagan wiki ngini.',
'passwordreset-pretext'            => '{{PLURAL:$1||Buati asa data di bawah ngini}}',
'passwordreset-username'           => 'Ngaran pamakai:',
'passwordreset-domain'             => 'Dumain:',
'passwordreset-capture'            => 'Tiringikah kulihan suril?',
'passwordreset-capture-help'       => 'Amun Pian cintang kutak ngini, suril (awan katasunduk pahadangan) akan ditampaiakan ka Pian bahwasa lagi dikirim ka pamakai.',
'passwordreset-email'              => 'Alamat suril:',
'passwordreset-emailtitle'         => 'Rarincian akun pada {{SITENAME}}',
'passwordreset-emailtext-ip'       => 'Ada urang (pinanya Pian, matan alamat IP $1) maminta sabuah pangingat hagan rarincian akun Pian gasan {{SITENAME}} ($4). pPamakai barikut {{PLURAL:$3|akun|akun}}
tarait awan suril:

$2

{{PLURAL:$3|katasunduk pahadangan ngini|kakatasunduk pahadangan ngini}} akan kadaluarsa dalam {{PLURAL:$5|asa hari|$5 hari}}.
Pian parlu babuat log wan mamilih katasunduk hanyar wayah ni jua. Amun urang lain nang maminta ngini, atawa amun Pian sudah paingatan awan katasunduk Pian, wan Pian kada handak maubahnya, Pian kawa kada mahuwal pasan ngini wan manyambung mamuruk katasunduk lawas Pian.',
'passwordreset-emailtext-user'     => 'Ada urang (pinanya Pian, matan alamat IP $1) maminta sabuting pangingat hagan rarincian akun Pian gasan {{SITENAME}} ($4). Pamakai barikut {{PLURAL:$3|akun|akun}}
tarait awan suril:

$2

{{PLURAL:$3|katasunduk pahadangan ngini|kakatasunduk pahadangan ngini}} akan kadaluarsa dalam {{PLURAL:$5|asa hari|$5 hari}}.
Pian parlu babuat log wan mamilih katasunduk hanyar wayah ini jua. Amun urang lain nang maminta ngini, atawa amun Pian sudah paingatan awan katasunduk Pian, wan Pian kada handak maubahnya, Pian kawa kada mahuwal pasan ngini wan manyambung mamuruk katasunduk lawas Pian.',
'passwordreset-emailelement'       => 'Ngaran pamakai: $1
Katasunduk pahadangan: $2',
'passwordreset-emailsent'          => 'Sabuah suril pangingat sudah takirim.',
'passwordreset-emailsent-capture'  => 'Sabuah suril pangingat sudah dikirim, nangkaya ditampaiakan di bawah.',
'passwordreset-emailerror-capture' => 'Suril paugingat, nang ditampaikan di bawah, hudah dihasilakan, tagal gagal mangirimakannya ka pamakai: $1',

# Special:ChangeEmail
'changeemail'          => 'Ganti alamat suril',
'changeemail-header'   => 'Ganti akun alamat suril',
'changeemail-text'     => 'Manuntungakan purmulir ngini hagan mangganti alamat suril Pian. Pian akan parlu mamasukakan katasunduk Pian hagan mayakinakan parubahan ngini.',
'changeemail-no-info'  => 'Pian musti babuat log hagan babuat ka tungkaran ngini langsung.',
'changeemail-oldemail' => 'Alamat suril wayah ni:',
'changeemail-newemail' => 'Alamat suril puga:',
'changeemail-none'     => '(kadada)',
'changeemail-submit'   => 'Ganti suril',
'changeemail-cancel'   => 'Walangi',

# Edit page toolbar
'bold_sample'     => 'Naskah kandal',
'bold_tip'        => 'Naskah kandal',
'italic_sample'   => 'Naskah hiring',
'italic_tip'      => 'Naskah hiring',
'link_sample'     => 'Judul tautan',
'link_tip'        => 'Tautan dalam',
'extlink_sample'  => 'http://www.example.com judul tautan',
'extlink_tip'     => 'Tautan luar (Ingatakan bamula wan http://)',
'headline_sample' => 'Naskah judul',
'headline_tip'    => 'Judul tingkat 2',
'nowiki_sample'   => 'Masukakan naskah kada babantuk di sia',
'nowiki_tip'      => 'Halinakan pambantukan/purmat wiki',
'image_tip'       => "Maktub'akan barakas",
'media_tip'       => 'Tautan barakas',
'sig_tip'         => 'Tandatangan Pian lawan tanda waktu',
'hr_tip'          => 'Garis horisontal',

# Edit pages
'summary'                          => 'Kasimpulan:',
'subject'                          => 'Subyek/judul:',
'minoredit'                        => 'Ngini adalah babakan sapalih',
'watchthis'                        => 'Itihi tungkaran ngini',
'savearticle'                      => 'Simpan tungkaran',
'preview'                          => 'Tilik',
'showpreview'                      => 'Tampaiakan titilikan',
'showlivepreview'                  => 'Titilikan langsung',
'showdiff'                         => 'Tampaiakan parubahan',
'anoneditwarning'                  => "'''Paringatan:''' Pian baluman babuat log.
Alamat IP Pian akan dirakam dalam tungkaran babakan halam",
'anonpreviewwarning'               => "''Pian baluman babuat log. Manyimpan akan tarakam alamat IP Pian pada sajarah bahari tungkaran ngini.''",
'missingsummary'                   => "'''Pangingat:''' Pian kada manyadiakan sabuah kasimpulan babakan.
Amun Pian klik \"{{int:savearticle}}\" pulang, babakan Pian tasimpan kada bakasimpulan.",
'missingcommenttext'               => 'Muhun buati sabuah kumintar di bawah ngini.',
'missingcommentheader'             => "'''Pangingat:''' Pian kada manyadiakan sabuah subjek/judul gasan kumin ngini.
Amun Pian klik \"{{int:savearticle}}\" pulang, babakan Pian tasimpan kada basubjek/bajudul.",
'summary-preview'                  => 'Tilikan kasimpulan:',
'subject-preview'                  => 'Titilikan subyek/judul:',
'blockedtitle'                     => 'Pamakai diblukir',
'blockedtext'                      => "'''Ngaran pamakai Pian atawa alamat IP sudah diblukir.'''

Pamblukiran diulah ulih $1.
Alasannya ''$2''.

* Mulai diblukir: $8
* Kadaluarsa blukir: $6
* Tujuan pamblukiran: $7

Pian kawa mangiyau $1 atawa [[{{MediaWiki:Grouppage-sysop}}|pambakal lainnya]] hagan mamandirakan pamblukiran nangini.
Pian kada kawa mamakai pitur 'surili pamakai naya' amun kadada sabuting alamat suril nang sah nang diajukan dalam [[Special:Preferences|kakatujuan akun]] Pian wan Pian kada lagi diblukir mamakai nangini. 
Alamat IP Pian parhatan ini $3, wan ID nang diblukir adalah $5.
Muhun sampaiakan samunyaan rarinci di atas dalam parmintaan nang Pian ulah.",
'autoblockedtext'                  => "Alamat IP Pian sudah utumatis diblukir karana dipuruk ulih pamakai lain, nang diblukir ulih $1.
Alasannya: ''$2''.

* Mulai diblukir: $8
* Kadaluarsa blukir: $6
* Tujuan pamblukiran: $7

Pian kawa mangiwau $1 atawa nang lain [[{{MediaWiki:Grouppage-sysop}}|pambakal]] hagan mamandirakan pamblukiran nangini.

Catatan Pian kada kawa mamuruk pitur 'surili pamakai naya' amun kadada sabuah alamat suril nang sah nang tadaptar dalam [[Special:Preferences|kakatujuan akun]] Pian wan  Pian kada lagi diblukir mamuruk nangini. 

Alamat IP Pian parhatan ini $3, wan ID nang diblukir adalah $5.
Muhun sampaiakan samunyaan rarinci di atas dalam parmintaan nag Pian ulah.",
'blockednoreason'                  => 'kadada alasan nang diunjukakan',
'whitelistedittext'                => 'Pian harus $1 hagan mambabak tungkaran.',
'confirmedittext'                  => 'Pian musti mayakinakan alamat suril Pian sabalum mambabak tungkaran-tungkaran. Muhun disetel wan disakakan alamat suril Pian tumatan [[Special:Preferences|kakatujuan pamakai]] Pian.',
'nosuchsectiontitle'               => 'Hagian kada tadapat',
'nosuchsectiontext'                => 'Pian habis mancuba mambabak sabuting hagian nang kadada.
Pinanya ini sudah diugahakan atawa dihapus parhatan Pian maniringi tungkaran nangitu.',
'loginreqtitle'                    => 'Parlu babuat log',
'loginreqlink'                     => 'Babuat log',
'loginreqpagetext'                 => 'Pian musti $1 hagan maniringi rungkaran-tungkaran lain.',
'accmailtitle'                     => 'Katasunduk takirim.',
'accmailtext'                      => "Sabuah katasunduk babarang gasan [[User talk:$1|$1]] sudah dikirim ka $2.

Katasunduk gasan pamakai hanyar nangini kawa diubah pintang tungkaran ''[[Special:ChangePassword|ubah katasunduk]]'' wayah babuat log.",
'newarticle'                       => '(Hanyar)',
'newarticletext'                   => "Pian maumpati sabuah tautan ka tungkaran nang baluman ada lagi. Gasan maulah tungkaran, mulai ja mangatik pada kutak di bawah (lihati [[{{MediaWiki:Helppage}}|tungkaran patulung]] gasan panjalasan labih). Amun Pian ka sia cagaran tasalah, klik picikan '''back''' di panjalajah web Pian.",
'anontalkpagetext'                 => "----''Ngini adalah tungkaran pamandiran gasan pamakai kada bangaran nang baluman ma-ulah akun pulang, atawa  kada mamakainya. Kami tapaksa mamakai numurik alamat IP hagan maminanduinya.
Alamat IP nangkaini kawaai dipuruk ulih babarapa pamakai.
Amun Pian adalah pamuruk kada bangaran wan marasa kumin nang kada pas ta ka Pian, muhun [[Special:UserLogin/signup|ulah sabuah akun]] or [[Special:UserLogin|babuat log]] hagan mahindari kabingungan awan pamuruk kada bangaran lain kaina.",
'noarticletext'                    => 'Parhatan ni kadada naskah di tungkaran ngini.
Pian kawa [[Special:Search/{{PAGENAME}}|manggagai gasan judul ngini]] pintang tungkaran lain,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} manggagai log barait].</span>,
atawa [{{fullurl:{{FULLPAGENAME}}|action=edit}} mambabak tungkaran ngini]</span>.',
'noarticletext-nopermission'       => 'Parhatan ni kadada naskah di tungkaran ngini.
Pian kawa [[Special:Search/{{PAGENAME}}|manggagai gasan judul ngini]] pintang tungkaran lain,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} manggagai log barait].</span>.',
'userpage-userdoesnotexist'        => 'Akun pamuruk "<nowiki>$1</nowiki>" kada tadaptar.
Muhun pariksa amun Pian handak maulah/mambabak tungkaran ini.',
'userpage-userdoesnotexist-view'   => 'Akun pamuruk "$1" kada tadaptar.',
'blocked-notice-logextract'        => 'Pamuruk nangini parhatan ini diblukir.
Log blukir pahabisannya tasadia di bawah ini gasan rujukan:',
'clearyourcache'                   => "'''Catatan: Habis manyimpan, Pian harus malingarakan cache panjalajah web Pian hagan malihat parubahan.'''
*'''Firefox/Safari:''' tahan ''Shift'' parhatan klik ''Reload'', atawa picik ''Ctrl-F5'' atawa ''Ctrl-R'' (''Command-R'' pada sabuah Mac);
* '''Google Chrome:''' picik ''Ctrl-Shift-R'' (''Command-Shift-R''  pada sabuah Mac)
*'''Internet Explorer:''' tahan ''Ctrl'' parhatan klik ''Refresh,'' atawa picik ''Ctrl-F5''
* '''Konqueror:''' klik ''Reload'' atawa picik ''F5''
*'''Opera:''' barasihakan cache pada ''Tools → Preferences''",
'usercssyoucanpreview'             => "'''Tip:''' Puruk picikan \"{{int:showpreview}}\" hagan tis CSS hanyar Pian sabalum manyimpan.",
'userjsyoucanpreview'              => "'''Tip:''' Puruk picikan \"{{int:showpreview}}\" hagan tis JavaScript hanyar Pian sabalum manyimpan.",
'usercsspreview'                   => "'''Ingatakan bahwasa Pian manilik pamakai CSS Pian haja.'''
'''Nangini baluman tasimpan pulang!'''",
'userjspreview'                    => "'''Ingatakan bahwasa Pian tis/manilik pamakai JavaScript Pian.'''
'''Nangini baluman tasimpan pulang!'''",
'sitecsspreview'                   => "'''Ingatakan bahwasa Pian manilik CSS ini haja.'''
'''Nangini lagi baluman tasimpan!'''",
'sitejspreview'                    => "'''Ingatakan bahwasa Pian manilik JavaScript code ini haja.'''
'''Nangini lagi baluman tasimpan!'''",
'userinvalidcssjstitle'            => "'''Paringatan:''' Kadada kulit \"\$1\".
Inatakan bahwasa saragam  tungkaran-tungkaran .css wan .js mamuruk aksara halus, cuntuh {{ns:user}}:Foo/vector.css sawagai tandingan {{ns:user}}:Foo/Vector.css.",
'updated'                          => '(Dihanyarakan)',
'note'                             => "'''Catatan:'''",
'previewnote'                      => "'''Ingatakanlah bahwasa ngini titilikan haja''' Parubahan Pian nang baluman disimpan!",
'previewconflict'                  => 'Titilikan ini mancarminakan naskah dalam naskah wilayah atas babakan sawagai mana ini akan mancungul amun disimpan.',
'session_fail_preview'             => "'''Ampun! Kami kada kawa manarusakan babakan Pian karana kahilangan sési data.'''
Cubai pang sa'asa pulang.
Amun magun kada kulihan, cubai [[Special:UserLogout|kaluar log]] wan imbah itu babuat log pulang.",
'session_fail_preview_html'        => "'''Kami kada kawa manarusakan babakan Pian karana kahilangan wayah data.'''

''Marga {{SITENAME}} mangkawa'akan HTML mantah, titilikan disungkupakan sawagai pancahagahan sarangan Javascript.''

'''Amun nangini adalah sabuah parcubaan babakan nang sabujurnya, muhun cubai lagi.'''
Amun ini masih haja kada bagawi, cubai [[Special:UserLogout|kaluar log]] wan babuat log pulang.",
'token_suffix_mismatch'            => "'''Babakan Pian sudah kada ditarima karana aplikasi Pian mahancuri tanda baca pada babakan token.'''
Babakan ini kada ditarima hagan mancagah kasalahan pada naskah tungkaran.
Nangini ambahanu tajadi amun Pian mamuruk sabuah layanan proxy buggy bapandal web kada bangaran.",
'edit_form_incomplete'             => "'''Babarapa hagian matan purmulir babakan kada sampai server; pariksa pulang apakah babakan Pian tatap utuh wan cubai lagi.'''",
'editing'                          => 'Mambabak $1',
'editingsection'                   => 'Mambabak $1 (hagian)',
'editingcomment'                   => 'Mambabak $1 (hagian hanyar)',
'editconflict'                     => 'Babakan bacakut: $1',
'explainconflict'                  => "Ada urang lain nang sudah maubah tungkaran ini parhatan Pian mula mambabak ini.
Naskah atas baisi naskah tungkaran sawagai dimapa ini ada hahanyaran ini.
Parubahan Pian ditampaiakan pada naskah di bawah.
Pian pinanya harus manggabungakan parubahan Pian ka dalam naskah nang ada.
Naskah nang di atas '''haja''' nang akan tasimpan amung Pian manikin \"{{int:savearticle}}\".",
'yourtext'                         => 'Naskah Pian',
'storedversion'                    => 'Ralatan tasimpan',
'nonunicodebrowser'                => "'''Paringatan: Panjalajah web Pian kada manyukung unicode.'''
Sabuah pambulatan gawian di wadah ini mambulihakan Pian aman mambabak tutungkaran: karaktir non-ASCII akan cungul pada kutak babakan sawagai kudi hiksadisimal.",
'editingold'                       => "'''Paringatan: Pian lagi mambabak ralatan lawas matan tungkaran ini.'''
Amun Pian manyimpan ini, babarapa paparubahan dulah imbah ralatan nangini akan tanggal.",
'yourdiff'                         => 'Nang balain',
'copyrightwarning'                 => "Muhun dicatat bahwasanya samunyaan sumbangan ka {{SITENAME}} adalah sudah dipartimbangkan disabarakan di bawah $2 (lihati $1 gasan rincian). Amun Pian kada handak tulisan Pian dibabak wan disabarakan, kada usah mangirim ini ka sia. <br />
Pian jua bajanji ka kami amun Pian manulis ini saurangan, atawa manjumput ini matan sabuah asal mula ampun umum atawa asal mula lainnya nang samacam.
'''Jangan kirimkan gawian bahak cipta kada baijin!'''",
'copyrightwarning2'                => "Muhun dicatat bahwasanya samunyaan sumbangan ka {{SITENAME}} kawa dibabak, diubah, atawa dibuang awan panyumbang lainnya.
Amun Pian kada hakun tulisan Pian dibabak kada baumpat lalu, lalu ai kada usah manyumbang di sia.<br />
Pian jua bajanji ka kami amun Pian manulis ini saurangan, atawa manjumput ini matan sabuah asal mula ampun umum atawa nang samacam asal mula bibas (lihati $1 gasan rarincian).
'''Jangan kirimkan gawian bahak cipta kada baijin!'''",
'longpageerror'                    => "'''Kasalahan: Naskah nang Pian kirim panjangnya {{PLURAL:$1|asa kilubita|$1 kilubita}}, nangapa tapanjang pada pamanjangnya nang kawa {{PLURAL:$2|asa kilubita|$2 kilubita}}.'''
Nangini kada kawa disimpan.",
'readonlywarning'                  => "'''Paringatan: Basis data sudah tasunduk gasan diharagu, jadinya Pian kada kawa manyimpan babakab Pian parhatan ini.'''
Pian kawa amun handak cut-n-paste naskah ka sabuah barakas naskah wan simpan ini gasan kaina.

Pambakal nang manyunduk manjalasakan kaini: $1",
'protectedpagewarning'             => "'''Paringatan: Tungkaran ini sudah dilindungi laluai pamuruk awan hak istimiwa pambakal nang kawa mambabak ini.'''
Log masuk pauncitan disadiakan di bawah gasan rujukan:",
'semiprotectedpagewarning'         => "'''Catatan:''' Tungkaran ini sudah dilindungi laluai pamuruk tadaptar haja nang kawa mambabak.
Log masuk pauncitan disadiakan di bawah gasan rujukan:",
'cascadeprotectedwarning'          => "'''Paringatan:''' Tungkaran ini sudah dilindungi laluai pamuruk awan hak istimiwa pambakal haja nang kawa mambabak, karana ini tamasuk dalam baumpat parlindungan barénténg {{PLURAL: $1|tungkaran|tutungkaran}}:",
'titleprotectedwarning'            => "'''Paringatan: Tungkaran ini sudah dilindungi laluai [[Special:ListGroupRights|hak khas]] diparluakan hagan maulah ini.'''
Log masuk pauncitan disadiakan di bawah gasan rujukan:",
'templatesused'                    => '{{PLURAL:$1|Citakan|Citakan}} nang digunakan di tungkaran ini:',
'templatesusedpreview'             => '{{PLURAL:$1|Citakan|Citakan}} nang digunakan di titilikan ini:',
'templatesusedsection'             => "{{PLURAL:$1|Citakan|Cicitakan}} nang diguna'akan di hagian ini:",
'template-protected'               => '(dilindungi)',
'template-semiprotected'           => '(semi-dilindungi)',
'hiddencategories'                 => 'Tungkaran ini adalah angguta matan {{PLURAL:$1|1 tumbung tasungkup|$1 tumbung tasungkup}}:',
'nocreatetitle'                    => 'Maulah tungkaran dibatasi',
'nocreatetext'                     => '{{SITENAME}} lagi mambatasi kakawaan maulah tungkaran hanyar.
Pian kawa babulik wan mambabak sabuah tungkaran nag ada, atawa [[Special:UserLogin|lbabuat log atawa baulah sabuah akun]]',
'nocreate-loggedin'                => 'Pian kada baisi ijin hagan maulah tungkaran-tungkaran hanyar.',
'sectioneditnotsupported-title'    => 'Pambabakan hagian kada didukung',
'sectioneditnotsupported-text'     => 'Pambabakan hagian kada didukung pada tungkaran ini.',
'permissionserrors'                => 'Parijinan tasalah',
'permissionserrorstext'            => 'Pian kada baisi ijin gasan malakuakan itu, karana {{PLURAL:$1|alasan|alasan}} ini:',
'permissionserrorstext-withaction' => 'Pian kada baisi ijin gasan $2, karana {{PLURAL:$1|alasan|alasan}} ini:',
'recreate-moveddeleted-warn'       => "'''Paringatan: Pian maulah pulang sabuah tungkaran nang sabalumnya dihapus.'''

Pian partimbangakan dahulu sasuaikah hagan manarusakan pambabakan tungkaran ini.
Log pahapusan wan paugahan gasan tungkaran ini disadiakan di sia:",
'moveddeleted-notice'              => 'Tungkaran ini sudah dihapus.
Log pahapusan wan paugahan gasan tungkaran ini disadiakan di bawah ini gasan rujukan.',
'log-fulllog'                      => 'Tiringi samunyaan log',
'edit-hook-aborted'                => 'Babakan ditinggalakan ulih kakait parser.
Ini kadada panjalasan.',
'edit-gone-missing'                => 'Kada kawa mamutakhirakan tungkaran ini.
Ini cungul pinanya sudah tahapus.',
'edit-conflict'                    => 'Babakan bacakut.',
'edit-no-change'                   => 'Babakan Pian diabaiakan, karana kadada parubahan diulah ka naskah ini.',
'edit-already-exists'              => 'Kada kawa maulah sabuah tungkaran hanyar.
Nangini sudah ada.',

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''Paringatan:''' Tungkaran ini mangandung kabanyakan pungsi parser kiauan.

Nangini harusnya takurang matan $2 {{PLURAL:$2|kiauan|kiauan-kiauan}}, ada {{PLURAL:$1|wayah ini $1 kiauan|wayah ini $1 kiauan-kiauan}}.",
'expensive-parserfunction-category'       => 'Tungkaran-tungkaran awan pungsi parser kiauan-kiauan kabanyakan',
'post-expand-template-inclusion-warning'  => "'''Paringatan:''' Citakan nang diumpatakan takarannya kaganalan.
Babarapa citakan akan kada taumpatakan.",
'post-expand-template-inclusion-category' => 'Tungkaran-tungkaran nang citakan baumpat limpuar',
'post-expand-template-argument-warning'   => "'''Paringatan:''' Tungkaran ini mangandung paling kada sabuah kalimat citakan wan ukuran panyingkaiannya kaganalan. Kalimat-kalimat nangitu sudah diabaiakan.",
'post-expand-template-argument-category'  => 'Tungkaran-tungkaran nang mangandung kalimat-kalimat citakan diabaiakan',
'parser-template-loop-warning'            => 'Citakan baulang takantup: [[$1]]',
'parser-template-recursion-depth-warning' => 'Citakan batas kadalaman recursi limpuar ($1)',
'language-converter-depth-warning'        => 'Batas kadalaman pakonversi bahasa limpuar ($1)',

# "Undo" feature
'undo-success' => 'Babakan kawa diwalangi.
Muhun pariksa panandingan di bawah hagan mayakinakan ini apa nang Pian handak gawi, wan imbah itu simpan parubahan di bawah hagan manuntungakan pawalangan babakan.',
'undo-failure' => 'Babakan ini kada kawa diwalangi karana ada cakutan di tangah babakan-babakan.',
'undo-norev'   => 'Babakan kada kawa diwalangi karana ini kadada atawa tahapus.',
'undo-summary' => '←Mawalangakan ralatan $1 ulih [[Special:Contributions/$2|$2]] ([[User talk:$2|Pandir]])',

# Account creation failure
'cantcreateaccounttitle' => 'Akun kada kawa diulah',
'cantcreateaccount-text' => "Paulahan akun matan alamat IP ('''$1''') sudah diblukir ulih [[User:$3|$3]].

Alasan nang dibari ulih $3 adalah ''$2''",

# History pages
'viewpagelogs'           => 'Tiringi log tungkaran ini',
'nohistory'              => 'Kadada halam babakan gasan tungkaran ini.',
'currentrev'             => 'Ralatan pahabisannya',
'currentrev-asof'        => 'Ralatan pahanyarnya pada $1',
'revisionasof'           => 'Ralatan matan $1',
'revision-info'          => 'Ralatan pada $1 ulih $2',
'previousrevision'       => '←Ralatan talawas',
'nextrevision'           => 'Ralatan salanjutnya→',
'currentrevisionlink'    => 'Ralatan wayahini',
'cur'                    => 'dmn',
'next'                   => 'dudi',
'last'                   => 'sblm',
'page_first'             => 'Panambaian',
'page_last'              => 'Pauncitan',
'histlegend'             => "Pilihan mananding: tandai kutak-kutak radiu ralatan-ralatan nang handak ditanding wan picik enter atawa picikan di bawah.<br />Legend: '''({{int:cur}})''' =lainnya awan ralatan pahanyarnya, '''({{int:last}})''' = lainnya awan ralatan sabalumnya, '''{{int:minoreditletter}}''' = babakan sapalih.",
'history-fieldset-title' => 'Tangadahi halam',
'history-show-deleted'   => 'Nang dihapus haja',
'histfirst'              => 'Palawasnya',
'histlast'               => 'Pahanyarnya',
'historysize'            => '($1 {{PLURAL:$1|bita|bibita}})',
'historyempty'           => '(kusung)',

# Revision feed
'history-feed-title'          => 'Ralatan halam',
'history-feed-description'    => 'Ralatan halam gasan tungkaran ini pada wiki',
'history-feed-item-nocomment' => '$1 wayah $2',
'history-feed-empty'          => 'Tungkaran nang diminta kadada.
Ini pinanya sudah dihapus matan wiki ini, atawa dingarani lain.
Cubai [[Special:Search|gagai di wiki ini]] gasan tungkaran hanyar bakarabat.',

# Revision deletion
'rev-deleted-comment'         => '(kasimpulan babakan dibuang)',
'rev-deleted-user'            => '(ngaran pamuruk dibuang)',
'rev-deleted-event'           => '(log palakuan dibuang)',
'rev-deleted-user-contribs'   => '[ngaran pamuruk atawa alamat IP dibuang - babakan disungkupakan matan daptar sumbangan]',
'rev-deleted-text-permission' => "Ralatan tungkaran ini sudah '''dihapus'''.
Rarincian kawa diugai dalam [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log pahapusan].",
'rev-deleted-text-unhide'     => "Ralatan tungkaran ini sudah '''dihapus'''.
Rarincian kawa diugai dalam [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log pahapusan].
Sawagai pambakal Pian masih kawa [$1 maniringi ralatan ini] amun Pian hakun manarusakan.",
'rev-suppressed-text-unhide'  => "Ralatan tungkaran ini sudah '''ditikin'''.
Rarincian kawa diugai dalam [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} log panikinan].
Sawagai pambakal Pian masih kawa [$1 maniringi ralatan ini] amun Pian hakun manarusakan.",
'rev-deleted-text-view'       => "Ralatan tungkaran ini sudah '''dihapus'''.
Sawagai saurang pambakal Pian kawa maniringi ini, rarincian kawa diugai dalam [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log pahapusan].",
'rev-suppressed-text-view'    => "Ralatan tungkaran ini sudah '''ditikin'''.
Sawagai saurang pambakal Pian kawa maniringi ini, rarincian kawa diugai dalam [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} log panikinan].",
'rev-deleted-no-diff'         => "Pian kada kawa maniringi nang balain ini karana asa matan ralatan-ralatan sudah '''dihapus'''.
Rarincian kawa diugai dalam [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log pahapusan].",
'rev-suppressed-no-diff'      => "Pian kada kawa maniringi nang balain ini karana asa matan ralatan-ralatan sudah '''dihapus'''.",
'rev-deleted-unhide-diff'     => "Asa matan ralatan-ralatan nang balain ini sudah '''dihapus'''.
Rarincian kawa diugai dalam [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log pahapusan].
Sawagai pambakal Pian masih kawa [$1 maniringi nang balain ini] amun Pian hakun manarusakan.",
'rev-suppressed-unhide-diff'  => "Asa matan ralatan-ralatan nang balain ini sudah '''ditikin'''.
Rarincian kawa diugai dalam [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} log panikinan].
Sawagai pambakal Pian masih kawa [$1 maniringi nang balain ini] amun Pian hakun manarusakan.",
'rev-deleted-diff-view'       => "Asa matan ralatan-ralatan nang balain ini sudah '''dihapus'''.
Sawagai saurang pambakal Pian kawa nang balain ini; rarincian kawa diugai dalam [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log pahapusan].",
'rev-suppressed-diff-view'    => "Asa matan ralatan-ralatan nang balain ini sudah '''ditikin'''.
Sawagai saurang pambakal Pian kawa nang balain ini; rarincian kawa diugai dalam [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} log panikinan].",
'rev-delundel'                => 'tampaiakan/sungkupakan',
'rev-showdeleted'             => 'tampaiakan',
'revisiondelete'              => 'Hapus/kada mahapus ralatan-ralatan',
'revdelete-nooldid-title'     => 'Ralatan nag dituju kada sah',
'revdelete-nooldid-text'      => 'Pian kada maajuakan sabuah ralatan(-ralatan) tatuju hagan malakuakan pungsi ini, ralatan nang dituju kadada, atawa Pian mancuba manyungkupakan ralatan parhatan ini.',
'revdelete-nologtype-title'   => 'Kadada macam log dibari',
'revdelete-nologtype-text'    => 'Pian kada maajuakan sabuah macam log hagan malakuakan palakuan ini.',
'revdelete-nologid-title'     => 'Log buat kada sah',
'revdelete-nologid-text'      => 'Pian kada maajuakan sabuah log kajadian tatuju hagan malakuakan pungsi ini atawa buat nang diajuakan kadada.',
'revdelete-no-file'           => 'Barakas nang diajuakan kadada.',
'revdelete-show-file-confirm' => 'Pian bujurkah handak maniringi sabuah ralatan tahapus matan barakas "<nowiki>$1</nowiki>" $2 pada $3?',
'revdelete-show-file-submit'  => 'Iya-ai',
'revdelete-selected'          => "'''{{PLURAL:$2|Ralatan tapilih|Raralatan tapilih}} matan [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Log kajadian tapilih|Log kakajadian tapilih}}:'''",
'revdelete-text'              => "'''Raralatan tahapus wan kakajadian akan magun cungul dalam halam tungkaran wan log, tagal hagian matan isinya akan kada kawa diungkai umum.'''
Pambakal lain pada {{SITENAME}} akan magun kawa maungkai isi tasungkup wan kawa mambulikakan hapusan pulang mangguna'akan antarmuha sama, kacuali ada panambahan pahalatan lain nang disetél.",
'revdelete-confirm'           => 'Muhun yakinakan bahwasa Pian handak manggawi ini, bahwasa Pian paham sabab akibat, wan bahwasa Pian manggawi ini bapandal awan [[{{MediaWiki:Policy-url}}|kaaripan]].',
'revdelete-suppress-text'     => "Panikinan parlu dipuruk gasan kakasus baumpat ini:
* Pina kawa jadi panjalasan pitnah
* balalabihan panjalasan kada sasuai paribadi
*: ''alamat badiam wan numur talipun, numur kaamanan susial, dll.''",
'revdelete-legend'            => 'Setel panampaian tabatas',
'revdelete-hide-text'         => 'Sungkupakan naskah ralatan',
'revdelete-hide-image'        => 'Sungkupakan isi barakas',
'revdelete-hide-name'         => 'Sungkupakan palakuan wan nang dituju',
'revdelete-hide-comment'      => 'Sungkupakan kasimpulan babakan',
'revdelete-hide-user'         => 'Sungkupakan ngaran pamuruk/alamat IP pambabak',
'revdelete-hide-restricted'   => 'Sungkupakan data matan pambakal nangkaya nang lain',
'revdelete-radio-same'        => '(Ditangati maubah)',
'revdelete-radio-set'         => 'Iya-ai',
'revdelete-radio-unset'       => 'Kada',
'revdelete-suppress'          => 'Sungkupakan data matan pambakal nangkaya nang lain',
'revdelete-unsuppress'        => 'Buang pambatasan pada ralatan-ralatan nang dibulikakan',
'revdelete-log'               => 'Alasan:',
'revdelete-submit'            => 'Lamar hagan mamilih {{PLURAL:$1|ralatan|ralatan-ralatan}}',
'revdelete-success'           => "'''Panampaian ralatan bakulihan dimutakhirakan.'''",
'revdelete-failure'           => "'''Panampaian ralatan kada kawa dimutakhirakan:'''
$1",
'logdelete-success'           => "'''Log panampaian bahasil disetel.'''",
'logdelete-failure'           => "'''Log panampaian kada kawa disetel:'''
$1",
'revdel-restore'              => 'Ubah tampilan',
'revdel-restore-deleted'      => 'Ralatan-ralatan tahapus',
'revdel-restore-visible'      => 'Ralatan-ralatan kalihatan',
'pagehist'                    => 'Sajarah tungkaran',
'deletedhist'                 => 'Halam tahapus',
'revdelete-hide-current'      => 'Tasalah manyungkupakan nang batanggal $1, $2: ini adalah ralatan tahanyar.
Ini kada kawa disungkupakan.',
'revdelete-show-no-access'    => 'Tasalah manampaiakan nang batanggal $1, $2: nangini sudah ditandai "tabatas".
Pian kada kawa malihati ini.',
'revdelete-modify-no-access'  => 'Tasalah magaganti nang batanggal $1, $2: nangini sudah ditandai "tabatas".
Pian kada kawa maungkai ini.',
'revdelete-modify-missing'    => 'Kasalahan magaganti nang ba-ID $1: Ini bahilang matan basis data!',
'revdelete-no-change'         => "'''Paringatan:''' nang batanggal $1, $2 sudah baisi setélan kakawaan-dilihati.",
'revdelete-concurrent-change' => 'Kasalahan magaganti nang batanggal $1, $2: nangini cungulnya suah diubah ulih urang lain pas Pian handak magaganti ini.
Muhun pariksa lolog.',
'revdelete-only-restricted'   => 'Kasalahan manyungkup ngan batanggal $1, $2: Pian kada kawa manikin matan tiringan ulih papambakal kadada jua mamilih asa matan pilihan kawa-malihati.',
'revdelete-reason-dropdown'   => '*Aalasan umum pahapusan
** Palangaran hak cipta
** Kakadasasuaian panjalasan paribadi
** Pina kawa jadi panjalasan pitnah',
'revdelete-otherreason'       => 'Alasan lain/tatambahan:',
'revdelete-reasonotherlist'   => 'Alasan lain',
'revdelete-edit-reasonlist'   => 'Aalasan pahapusan babakan',
'revdelete-offender'          => 'Ralatan panulis:',

# Suppression log
'suppressionlog'     => 'Log panikinan',
'suppressionlogtext' => 'Nang di bawah adalah sabuting daptar matan pahapusan wan pamblukiran tamasuk isi tasungkup matan pambakal. Lihati [[Special:BlockList|Daptar diblukir]] gasan daptar matan uprasi tahanyar tatangatan wan blukir.',

# History merging
'mergehistory'                     => 'Gabungakan hahalam tungkaran',
'mergehistory-header'              => 'Tungkaran ngini mambulihakan Pian manggabungakan raralatan matan asa tungkaran asal mula ka sabuah tungkaran tahanyar.
Yakini bahwasa parubahan ngini masih maharagu tarus halam lawas tungkaran.',
'mergehistory-box'                 => 'Gabungakan raralatan matan dua tungkaran:',
'mergehistory-from'                => 'Tungkaran asal mula:',
'mergehistory-into'                => 'Tungkaran tatuju:',
'mergehistory-list'                => 'Halam babakan nang kawa digabungakan',
'mergehistory-merge'               => 'Raralatan barikut matan [[:$1]] kawa digabungakan ka[[:$2]].
Puruk picikan radiu hagan manggabungakan raralatan nang diulah pada wan sabalum wayah tartantu haja.
Catatan bahwasa mamuruk tautan napigasi akan mambulikakan setelan kolum ngini.',
'mergehistory-go'                  => 'Tampaiakan bababakan nang kawa digabungakan',
'mergehistory-submit'              => 'Gabungakan raralatan',
'mergehistory-empty'               => 'Kadada raralatan nang kawa digabungakan',
'mergehistory-success'             => '$3 {{PLURAL:$3|ralatan|raralatan}} matan [[:$1]] ruhui digabungakan ka [[:$2]].',
'mergehistory-fail'                => 'Kada kawa manggabungakan halam, muhun pariksa pulang tungkaran wan parameter wayah.',
'mergehistory-no-source'           => 'Tungkaran asal mula $1 kadada.',
'mergehistory-no-destination'      => 'Tungkaran tatuju $1 kadada.',
'mergehistory-invalid-source'      => 'Asal mula tungkaran musti sabuah judul sah.',
'mergehistory-invalid-destination' => 'Tungkaran tatuju musti sabuah judul sah.',
'mergehistory-autocomment'         => 'Sudah digabungakan [[:$1]] ka dalam [[:$2]]',
'mergehistory-comment'             => 'Sudah digabungakan [[:$1]] ka dalam [[:$2]]: $3',
'mergehistory-same-destination'    => 'Tungkaran-tungkaran asal mula wan tatuju kada kawa sama',
'mergehistory-reason'              => 'Alasan:',

# Merge log
'mergelog'           => 'Log panggabungan',
'pagemerge-logentry' => 'Sudah digabungakan [[$1]] ka dalam [[$2]] (ralatan-ralatan sampai $3)',
'revertmerge'        => 'Walang panggabungan',
'mergelogpagetext'   => 'Di bawah adalah daptar nang paling hanyar panggabungan matan sabuah tungkaran halam ka dalam nang lain.',

# Diffs
'history-title'            => "Ralatan halam matan ''$1''",
'difference'               => '(Nang balain antar ralatan)',
'difference-multipage'     => '(Nang balain antar tungkaran-tungkaran)',
'lineno'                   => 'Baris $1:',
'compareselectedversions'  => 'Tandingakan ralatan nang dipilih',
'showhideselectedversions' => 'Tampaiakan/sungkupakan ralatan-ralatan',
'editundo'                 => 'walangi',
'diff-multi'               => '({{PLURAL:$1|Asa ralatan tangah|$1 raralatan tangah}} ulih {{PLURAL:$2|asa pamakai|$2 papamakai}} kada ditampaiakan)',
'diff-multi-manyusers'     => '({{PLURAL:$1|Asa ralatan tangah|$1 raralatan tangah}} ulih labih pada $2 {{PLURAL:$2|pamuruk|papamuruk}} kada ditampaiakan)',

# Search results
'searchresults'                    => 'Kulihan panggagaian',
'searchresults-title'              => 'Kulihan gagai gasan "$1"',
'searchresulttext'                 => 'Gasan panjalasan labih lanjut pasal panggagaian pintangan {{SITENAME}}, lihati [[{{MediaWiki:Helppage}}|tungkaran patulung]].',
'searchsubtitle'                   => 'Pian manggagai \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|samunyaan tungkaran bamula wan "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|samunyaan tungkaran nang bataut ka "$1"]])',
'searchsubtitleinvalid'            => "Pian manggagai '''$1'''",
'toomanymatches'                   => 'Kabanyakan nang cucuk kulihan, muhun cubai parmintaan lain',
'titlematches'                     => 'Judul tungkaran pas',
'notitlematches'                   => 'Kadada tungkaran bajudul pas',
'textmatches'                      => 'Naskah tungkaran pas',
'notextmatches'                    => 'Kadada tungkaran banaskah pas',
'prevn'                            => '{{PLURAL:$1|$1}} tadahulu',
'nextn'                            => '{{PLURAL:$1|$1}} dudinya',
'prevn-title'                      => 'Tadahulu $1 {{PLURAL:$1|kulihan|kulihan-kulihan}}',
'nextn-title'                      => 'Tadudi $1 {{PLURAL:$1|kulihan|kulihan-kulihan}}',
'shown-title'                      => 'Tampaiakan $1 {{PLURAL:$1|kulihan|kukulihan}} par tungkatan',
'viewprevnext'                     => 'Tiringi ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Papilihan manggagai',
'searchmenu-exists'                => "'''Ada tungkaran bangaran \"[[:\$1]]\" dalam wiki ini.'''",
'searchmenu-new'                   => "'''Maulah tungkaran \"[[:\$1]]\" dalam wiki ngini!'''",
'searchhelp-url'                   => 'Help:Isi',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Janaki daptar tungkaran lawan awalan ngini]]',
'searchprofile-articles'           => 'Tungkaran isi',
'searchprofile-project'            => 'Tutungkaran Patulung wan Rangka gawian',
'searchprofile-images'             => 'Multimadia',
'searchprofile-everything'         => 'Samunyaan',
'searchprofile-advanced'           => 'Haratan',
'searchprofile-articles-tooltip'   => 'Panggagaian pada $1',
'searchprofile-project-tooltip'    => 'Panggagaian pada $1',
'searchprofile-images-tooltip'     => 'Panggagaian barakas',
'searchprofile-everything-tooltip' => 'Panggagaian sabarataan isi (tamasuk tutungkaran pamandiran)',
'searchprofile-advanced-tooltip'   => 'Panggagaian pada ragam ngaran kakamar',
'search-result-size'               => '$1 ({{PLURAL:$2|1 ujar|$2 uujar}})',
'search-result-category-size'      => '{{PLURAL:$1|1 angguta|$1 aangguta}} ({{PLURAL:$2|1 subtumbung|$2 subtutumbung}}, {{PLURAL:$3|1 barakas|$3 babarakas}})',
'search-result-score'              => 'Kacucukan: $1%',
'search-redirect'                  => '(Paugahan $1)',
'search-section'                   => '(hagian $1)',
'search-suggest'                   => 'Nginikah maksud Pian: $1',
'search-interwiki-caption'         => 'Dingsanak rangka gawian',
'search-interwiki-default'         => 'Kulihan $1',
'search-interwiki-more'            => '(lagi)',
'search-mwsuggest-enabled'         => 'awan saran',
'search-mwsuggest-disabled'        => 'kadada saran',
'search-relatedarticle'            => 'Bakulaan',
'mwsuggest-disable'                => "Kada kawa'akan sasaran AJAX",
'searcheverything-enable'          => 'Panggagaian pada samunyaan ngaran kakamar',
'searchrelated'                    => 'bakulaan',
'searchall'                        => 'samunyaan',
'showingresults'                   => "Di bawah ngini ditampaiakan hingga {{PLURAL:$1|'''1''' kulihan|'''$1''' kukulihan}}, dimulai matan #'''$2'''.",
'showingresultsnum'                => "Di bawah ngini ditampaiakan hingga {{PLURAL:$3|'''1''' kulihan|'''$3''' kukulihan}}, dimulai matan #'''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Kulihan '''$1''' matan '''$3'''|Kukulihan '''$1 - $2''' matan '''$3'''}} gasan '''$4'''",
'nonefound'                        => "'''Catatan''': babarapa ngaran kamar haja nang baku digagai.
Tarai pamintaan Pian lawan ''all:'' gasan manggagai samunyaan isi (tamasuk tungkaran pamandiran, citakan, dll), atawa puruk ngaran kamar nang dihandaki sabagai awalan.",
'search-nonefound'                 => 'Kadada kulihan nang pas awan parmintaan.',
'powersearch'                      => 'Panggagaian mahir',
'powersearch-legend'               => 'Panggagaian mahir',
'powersearch-ns'                   => 'Manggagai di ngaran kamar:',
'powersearch-redir'                => 'Daptar paugahan',
'powersearch-field'                => 'Manggagai',
'powersearch-togglelabel'          => 'Pilihi:',
'powersearch-toggleall'            => 'Samunyaan',
'powersearch-togglenone'           => 'Kadada',
'search-external'                  => 'Panggagaian luar',
'searchdisabled'                   => '{{SITENAME}} panggagaian kada kawa
Pian kawa manggagai lung Google parhatan ini.
Catatan nang dihaharnya matan isi {{SITENAME}} kawa-ai sudah kadaluarsa.',

# Quickbar
'qbsettings'                => 'Bilahhancap',
'qbsettings-none'           => 'Kadada',
'qbsettings-fixedleft'      => 'Tatap di kiwa',
'qbsettings-fixedright'     => 'Tatap di kanan',
'qbsettings-floatingleft'   => 'Mangambang sabalah kiwa',
'qbsettings-floatingright'  => 'Mangambang sabalah kanan',
'qbsettings-directionality' => 'Tatap, tagantung pada ampah skrip matan bahasa Pian',

# Preferences page
'preferences'                   => 'Kakatujuan',
'mypreferences'                 => 'Nang ulun katuju',
'prefs-edits'                   => 'Rikinan babakan-babakan:',
'prefsnologin'                  => 'Balum babuat log',
'prefsnologintext'              => 'Pian harus <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} babuat log]</span> gasat mengeset kakatujuan Pian.',
'changepassword'                => 'Ubah katasunduk',
'prefs-skin'                    => 'Kulimbit',
'skin-preview'                  => 'Titilikan',
'datedefault'                   => 'Kadada katujuan',
'prefs-beta'                    => 'Fitur Beta',
'prefs-datetime'                => 'Tanggal wan waktu',
'prefs-labs'                    => 'Fitur Labs',
'prefs-personal'                => 'Data awak',
'prefs-rc'                      => 'Parubahan tahanyar',
'prefs-watchlist'               => 'Paitihan',
'prefs-watchlist-days'          => 'Rikinan hari-hari ditampaiakan di daptar itihan:',
'prefs-watchlist-days-max'      => 'Maksimum $1 {{PLURAL:$1|hari|hahari}}',
'prefs-watchlist-edits'         => 'Rikinan paningginya matan parubahan hagan ditampaiakan pada singkaian daptar itihan:',
'prefs-watchlist-edits-max'     => 'Rikinan paningginya:1000',
'prefs-watchlist-token'         => 'Token itihan:',
'prefs-misc'                    => 'Balalain',
'prefs-resetpass'               => 'Ubah katasunduk',
'prefs-changeemail'             => 'Ganti suril',
'prefs-setemail'                => 'Setel sabuah alamat suril',
'prefs-email'                   => 'Pipilihan suril',
'prefs-rendering'               => 'Pancungulan',
'saveprefs'                     => 'Simpan',
'resetprefs'                    => 'Kusungakan paparubahan kada tasimpan',
'restoreprefs'                  => 'Bulikakan samunyaan sesetélan default',
'prefs-editing'                 => 'Pambabakan',
'prefs-edit-boxsize'            => 'Ukuran lalungkang babakan',
'rows'                          => 'Baris:',
'columns'                       => 'Kolom:',
'searchresultshead'             => 'Gagai',
'resultsperpage'                => 'Hantukan par tungkaran:',
'stub-threshold'                => 'Ambang watas gasan pormat <a href="#" class="stub">taautan rintisan</a>:',
'stub-threshold-disabled'       => 'Kada kawa-akan',
'recentchangesdays'             => 'Hahari nang manampaiakan parubahan tahanyar:',
'recentchangesdays-max'         => 'Paling lawas $1 {{PLURAL:$1|hari|hahari}}',
'recentchangescount'            => 'Rikinan babakan nang ditampaiakan default:',
'prefs-help-recentchangescount' => 'Ini tamasuk parubahan tahanyar, halam-halam tungkaran, wan log-log.',
'prefs-help-watchlist-token'    => 'Maisi kutak ngini lawan kunci rahasia (PIN) akan mahasilakan sindikasi RSS hagan daptar pantauan Anda. Siapa gin nang tahu kunci ngini kawa mambaca daptar itihan Pian, jadi pilihi nilainya bahati-hati
Barikut ngini adalah nilai acak nang kawa Pian puruk: $1',
'savedprefs'                    => 'Kakatujuan Pian sudah ham disimpan.',
'timezonelegend'                => 'Waktu banua:',
'localtime'                     => 'Waktu damintu:',
'timezoneuseserverdefault'      => "Guna'akan bawaan wiki ($1)",
'timezoneuseoffset'             => 'Nang lain (ajuakan nang luput setel)',
'timezoneoffset'                => 'Luput setel¹:',
'servertime'                    => 'Waktu server:',
'guesstimezone'                 => 'Isiakan matan panjalajah web',
'timezoneregion-africa'         => 'Aprika',
'timezoneregion-america'        => 'Amirika',
'timezoneregion-antarctica'     => 'Antarktika',
'timezoneregion-arctic'         => 'Arktik',
'timezoneregion-asia'           => 'Asia',
'timezoneregion-atlantic'       => 'Lalautan Atlantik',
'timezoneregion-australia'      => 'Australia',
'timezoneregion-europe'         => 'Irupa',
'timezoneregion-indian'         => 'Lalautan Hindia',
'timezoneregion-pacific'        => 'Lalautan Pasipik',
'allowemail'                    => "Kawa'akan pamakai lain mangirim suril",
'prefs-searchoptions'           => 'Papilihan manggagai',
'prefs-namespaces'              => 'Ngaran kamar',
'defaultns'                     => 'Atawa-lah manggagai dalam ngaran kakamar nangini:',
'default'                       => 'default',
'prefs-files'                   => 'Barakas',
'prefs-custom-css'              => 'Saragamakan CSS',
'prefs-custom-js'               => 'Saraganakan JavaScript',
'prefs-common-css-js'           => 'Babagi CSS/JavaScript gasan samunyaan skin:',
'prefs-reset-intro'             => 'Pian kawa mamuruk tungkaran ini hagan setel bulik kakatujuan Pian ka default situs.
Ini kada kawa diwalangi.',
'prefs-emailconfirm-label'      => 'Payakinakan suril:',
'prefs-textboxsize'             => 'Ukuran kutak ubahan',
'youremail'                     => 'Suril:',
'username'                      => 'Ngaran pamuruk:',
'uid'                           => 'ID pamuruk:',
'prefs-memberingroups'          => 'Angguta matan {{PLURAL:$1|galambang|gagalambang}}:',
'prefs-registration'            => 'Waktu pandaptaran:',
'yourrealname'                  => 'Ngaran asli:',
'yourlanguage'                  => 'Bahasa:',
'yourvariant'                   => 'Variasi bahasa isi:',
'prefs-help-variant'            => 'Variasi kakatujuan Pian atawa ortugrafi panampaian isi tutungkaran matan wiki ngini dalam.',
'yournick'                      => 'Tandatangan:',
'prefs-help-signature'          => 'Kumintar pada tungkaran pamandiran parlu ditandatangani awan "<nowiki>~~~~</nowiki>" nangapa akan taubah jadi tandatangan Pian wan waktu wayahini.',
'badsig'                        => 'Tandatangan mantah kada sah.
Pariksa tag HTML.',
'badsiglength'                  => 'Tapak tangan Sampian talalu panjang. Jangan malabihi pada $1 {{PLURAL:$1|karakter|karakter}}.',
'yourgender'                    => 'Janis kalamin:',
'gender-unknown'                => 'Kada diajuakan',
'gender-male'                   => 'Lalakian',
'gender-female'                 => 'Bibinian',
'prefs-help-gender'             => 'Opsional: dipuruk gasan mambaiki manyambat gindir ulih parangkat lunak. Panjalasan ngini akan tasingkai hagan umum.',
'email'                         => 'Suril',
'prefs-help-realname'           => 'Ngaran bujur adalah pilihan haja.
Amun Pian mamilih manyadiakan ini, ini akan dipuruk gasan paminanduan kulihan gawian Pian.',
'prefs-help-email'              => 'Alamat suril adalah opsional, tagal pun parlu gasan mambulikakan setelan katasunduk, amunai Pian kada ingatan.',
'prefs-help-email-others'       => 'Pian kawa jua maijinakan urang mangiau Pian lung tungkaran pamakai atawa pamandiran Pian kada parlu manampaiakan identitas Pian.',
'prefs-help-email-required'     => 'Alamat suril diparluakan.',
'prefs-info'                    => 'Panjalasan pandal',
'prefs-i18n'                    => 'Intarnasionalisasi',
'prefs-signature'               => 'Tandatangan',
'prefs-dateformat'              => 'Purmat tanggal',
'prefs-timeoffset'              => 'Setélan waktu',
'prefs-advancedediting'         => 'Pilihan harat',
'prefs-advancedrc'              => 'Pilihan harat',
'prefs-advancedrendering'       => 'Pilihan harat',
'prefs-advancedsearchoptions'   => 'Pilihan harat',
'prefs-advancedwatchlist'       => 'Pilihan harat',
'prefs-displayrc'               => 'Pilihan tampilan',
'prefs-displaysearchoptions'    => 'Pilihan tampilan',
'prefs-displaywatchlist'        => 'Pilihan tampilan',
'prefs-diffs'                   => 'Bida',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'Alamat suril tacungul sah',
'email-address-validity-invalid' => 'Buati sabuah alamat suril nang sah',

# User rights
'userrights'                   => 'Pangalulaan hak-hak pamuruk',
'userrights-lookup-user'       => 'Mangalula gagalambang pamuruk',
'userrights-user-editname'     => 'Buati sabuah ngaran pamuruk:',
'editusergroup'                => 'Babak galambang pamuruk',
'editinguser'                  => "Ma-ubah hak ungkai pamuruk '''[[User:$1|$1]]''' $2",
'userrights-editusergroup'     => 'Babak galambang pamuruk',
'saveusergroups'               => 'Simpan galambang pamuruk',
'userrights-groupsmember'      => 'Angguta matan:',
'userrights-groupsmember-auto' => 'Angguta tasirat matan:',
'userrights-groups-help'       => 'Pian kawa maubah galambang pamuruk ngini:
* Kutak awan tanda cek artnya galambang pamuruk nang basangkutan
* Kutak kada batanda cek artinya pamuruk ngini lainan angguta galambang ngitu
* Tanda * manandai bahwasa Pian kada kawa mawalangi galambang ngitu amun Pian sudah manambahinya, atawa kabalikannya.',
'userrights-reason'            => 'Alasan:',
'userrights-no-interwiki'      => 'Pian kada baisi ijin hagan mambabak hak pamuruk di wiki lain.',
'userrights-nodatabase'        => 'Basis data $1 kadada atawa lainan lukal.',
'userrights-nologin'           => 'Pian musti [[Special:UserLogin|lbabuat log]] awan sabuah akun pambakal hagan mambari hak pamuruk.',
'userrights-notallowed'        => 'Akun Pian kada baisi ijin hagan manambahi atawa malapas hak pamuruk.',
'userrights-changeable-col'    => 'Gagalambang nang Pian kawa ubah',
'userrights-unchangeable-col'  => 'Gagalambang nang Pian kada kawa ubah',

# Groups
'group'               => 'Galambang:',
'group-user'          => 'Pamakai',
'group-autoconfirmed' => 'Pamakai utumatis diyakinakan',
'group-bot'           => 'Bot',
'group-sysop'         => 'Pambakal',
'group-bureaucrat'    => 'Birukrat',
'group-suppress'      => 'Pangawas',
'group-all'           => '(samunyaan)',

'group-user-member'          => '{{GENDER:$1|pamakai}}',
'group-autoconfirmed-member' => '{{GENDER:$1|pamakai utumatis diyakinakan}}',
'group-bot-member'           => '{{GENDER:$1|bot}}',
'group-sysop-member'         => '{{GENDER:$1|pambakal}}',
'group-bureaucrat-member'    => '{{GENDER:$1|birukrat}}',
'group-suppress-member'      => '{{GENDER:$1|pangawas}}',

'grouppage-user'          => '{{ns:project}}: Pamuruk',
'grouppage-autoconfirmed' => '{{ns:project}}: Pamuruk utumatis diyakinakan',
'grouppage-bot'           => '{{ns:project}}:Bot',
'grouppage-sysop'         => '{{ns:project}}:Pambakal',
'grouppage-bureaucrat'    => '{{ns:project}}:Birukrat',
'grouppage-suppress'      => '{{ns:project}}:Pangawas',

# Rights
'right-read'                  => 'Mambaca tungkaran',
'right-edit'                  => 'Mambaiki tungkaran',
'right-createpage'            => 'Ulah tutungkaran (nang lainan tutungkaran pamandiran)',
'right-createtalk'            => 'Maulah tutungkaran pamandiran',
'right-createaccount'         => 'Ulah akun pamuruk hanyar',
'right-minoredit'             => 'Tandai bababakan sawagai sapalih',
'right-move'                  => 'Mamindahakan tungkaran',
'right-move-subpages'         => 'Ugahakan tutungkaran awan subtumgkaran-nya',
'right-move-rootuserpages'    => 'Ugahakan akar tutungkaran pamuruk',
'right-movefile'              => 'Mamindahakan barakas',
'right-suppressredirect'      => 'Kada maulah paugahan matan tutungkaran asal mula parhatan tutungkan pindahan',
'right-upload'                => 'Unggahakan barakas',
'right-reupload'              => 'Manulistindih barakas nang ada',
'right-reupload-own'          => 'Manulistindih barakas nang ada unggahan ulih urang nang sama',
'right-reupload-shared'       => 'Manulak babarakas pada panyimpanan media lokal basamaan',
'right-upload_by_url'         => 'Hunggahakan babarakas matan sabuah URL',
'right-purge'                 => 'Limpuarakan timbuluk situs gasan asa tungkaran kada pambaritahuan',
'right-autoconfirmed'         => 'Mambabak tutungkaran sami-dilindungi',
'right-bot'                   => 'Ditindak sawagai sabuah proses utumatis',
'right-nominornewtalk'        => 'Kadada babakan sapalih di tutungkaran pamandiran nang mancungulakan tampaian pasan puga',
'right-apihighlimits'         => 'Mamuruk watas kueri API tatinggi',
'right-writeapi'              => 'Puruk panulisan API',
'right-delete'                => 'Mahapus tungkaran',
'right-bigdelete'             => 'Hapus tutungkaran awan hahalam ganal',
'right-deleterevision'        => 'Mahapus wan mawalangi hapus raralatan tatantu matan tutungkaran',
'right-deletedhistory'        => 'Tiringi mamasukan halam tahapus, kada banaskah tarait',
'right-deletedtext'           => 'Tiringi naskah tahapus wan parubahan antar raralatan tahapus',
'right-browsearchive'         => 'Manggagai tungkaran nang sudah dihapus',
'right-undelete'              => 'Mambulikakan sabuah tungkaran tahapus',
'right-suppressrevision'      => 'Maniring pulang wan mambulikakan raralatan matan papambakal',
'right-suppressionlog'        => 'Tiringi log paribadi',
'right-block'                 => 'Blukir pamuruk lain mambabak',
'right-blockemail'            => 'Blukir saurang pamuruk mangirimi suril',
'right-hideuser'              => 'Blukir sabuah ngaranpamuruk, sungkupakan ini matan umum',
'right-ipblock-exempt'        => 'Liwati blukir IP, blukir-utumatis wan aria blukir',
'right-proxyunbannable'       => 'Liwati utumatis blukir matan pruksi',
'right-unblockself'           => 'Lapas blukirnya surang',
'right-protect'               => 'Ubah tingkat parlindungan wan babakan tutungkaran nang diindungi',
'right-editprotected'         => 'Babak tungkaran nang dilindungi (kada parlindungan barenteng)',
'right-editinterface'         => 'Babak antarmuha pamuruk ini',
'right-editusercssjs'         => 'Babak pamuruk lain babarakas CSS wan JavaScript',
'right-editusercss'           => 'Babak pamruk lain babarakas CSS',
'right-edituserjs'            => 'Babak pamuruk lain babarakas JavaScript',
'right-rollback'              => 'Mambulikakan hancap bababakan matan pamuruk tauncit nang mambabak sabuah tungkaran tatantu',
'right-markbotedits'          => 'Tandai bababakan dibulikakan sawagai bababakan bot',
'right-noratelimit'           => 'Kada pangaruh awan watas rating',
'right-import'                => 'Impur tutungkaran matan wiwiki lain',
'right-importupload'          => 'Iimpur tutungkaran matan sabuah barakas hunggahan',
'right-patrol'                => "Tandai bababakan nang lain sawagai ta'awasi",
'right-autopatrol'            => "Babakan ampun surang utumatis ditandai sawagai ta'awasi",
'right-patrolmarks'           => 'Tiringi tanda parubahan tahanyar',
'right-unwatchedpages'        => 'Tiringi sabuah daptar tutungkaran nang kada diitihi',
'right-mergehistory'          => 'Gabungakan halam matan tutungkaran',
'right-userrights'            => 'Babak sabarataan hak pamuruk',
'right-userrights-interwiki'  => 'Babak hahak pamuruk matan papamuruk wiwiki balain',
'right-siteadmin'             => 'Sunduk wan buka sunduk basis data',
'right-override-export-depth' => 'Ekspur tutungkaran tamasuk tutungkaran tataut sampai kadalaman 5',
'right-sendemail'             => 'Mangirim suril ka papamuruk lain',
'right-passwordreset'         => 'Tiringi setelan-pulang katasunduk suril',

# User rights log
'rightslog'                  => 'Log parubahan hak masuk',
'rightslogtext'              => 'Nangini adalah sabuah log paparubahan ka hahak pamuruk.',
'rightslogentry'             => 'Ubah galambang angguta gasan $1 matan $2 ka $3',
'rightslogentry-autopromote' => 'sudah utumatis diangkat matan $2 ka $3',
'rightsnone'                 => '(kadada)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'baca tungkaran ini',
'action-edit'                 => 'babak tungkaran ini',
'action-createpage'           => 'ulah tutungkaran',
'action-createtalk'           => 'ulah tutungkaran pamandiran',
'action-createaccount'        => 'ulah akun pamuruk ini',
'action-minoredit'            => 'tandai babakan ini sawagai sapalih',
'action-move'                 => 'pindahakan tungkaran ini',
'action-move-subpages'        => 'pindahakan tungkaran ini, wan sub-tutungkarannya',
'action-move-rootuserpages'   => 'mamindahakan akar tutungkaran pamuruk',
'action-movefile'             => 'pindahakan barakas ini',
'action-upload'               => 'hunggahakan barakas ini',
'action-reupload'             => 'manimpa barakas nang ada',
'action-reupload-shared'      => 'manindih barakas nang sudah ada di wadah payimpanan barakas basamaan',
'action-upload_by_url'        => 'hunggahakan barakas ini matan sabuah URL',
'action-writeapi'             => 'puruk panulisan API',
'action-delete'               => 'hapus tungkaran ini',
'action-deleterevision'       => 'hapus ralatan ini',
'action-deletedhistory'       => 'tiringi sajarah tungkaran tahapus naya',
'action-browsearchive'        => 'gagai tutungkaran tahapus',
'action-undelete'             => 'kada jadi mahapus tungkaran ini',
'action-suppressrevision'     => 'tilik wan bulikakan ralatan tasungkup ini',
'action-suppressionlog'       => 'tiringi log paribadi ini',
'action-block'                => 'blukir pamuruk ini matan mambabak',
'action-protect'              => 'Ubah tingkat parlindungan tungkaran ngini',
'action-rollback'             => 'Mambulikakan hancap bababakan matan pamuruk tauncit nang mambabak sabuah tungkaran tatantu.',
'action-import'               => 'Impur tungkaran ngini matan wiki lain',
'action-importupload'         => 'Impur tungkaran ngini matan sabuah barakas hunggahan',
'action-patrol'               => "tandai babakan nang lain sawagai ta'awasi",
'action-autopatrol'           => "Tandai babakan Pian sawagai ta'awasi",
'action-unwatchedpages'       => 'tiringi daptar tutungkaran nang kada diitihi',
'action-mergehistory'         => 'gabungakan halam matan tungkaran ngini',
'action-userrights'           => 'babak sabarataan hak pamuruk',
'action-userrights-interwiki' => 'babak hak pamuruk matan papamuruk dalam wiwiki lain',
'action-siteadmin'            => 'sunduk atawa bukasunduk basisdata',
'action-sendemail'            => 'Kirim suril',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|parubahan|parubahan}}',
'recentchanges'                     => 'Paubahan pahanyarnya',
'recentchanges-legend'              => 'Pilihan paubahan pahanyarnya',
'recentchangestext'                 => 'Jajak parubahan wiki pahanyarnya pada tungkaran ngini',
'recentchanges-feed-description'    => 'Susuri parubahan pahanyarnya dalam wiki di kitihan ini',
'recentchanges-label-newpage'       => 'Babakan ngini maulah sabuah tungkaran hanyar',
'recentchanges-label-minor'         => 'Ngini adalah sabuah babakan sapalih',
'recentchanges-label-bot'           => 'Babakan ngini digawi ulih saikung bot',
'recentchanges-label-unpatrolled'   => "Babakan ngini baluman ta'awasi",
'rcnote'                            => "Di bawah ni {{PLURAL:$1|'''1'''|'''$1'''}} paubahan pahanyarnya dalam {{PLURAL:$2|'''1''' hari|'''$2''' hari}} tauncit, sampai $4 pukul $5.",
'rcnotefrom'                        => "Di bawah ngini parubahan tumatan '''$2''' (ditampaiakan sampai '''$1''' parubahan)",
'rclistfrom'                        => 'Tampaiakan paubahan pahanyarnya matan $1',
'rcshowhideminor'                   => '$1 pambabakan sapalih',
'rcshowhidebots'                    => '$1 bot',
'rcshowhideliu'                     => '$1 pamakai nang babuat di log',
'rcshowhideanons'                   => '$1 pamakai kada bangaran',
'rcshowhidepatr'                    => "$1 babakan ta'awasi",
'rcshowhidemine'                    => '$1 babakan ulun',
'rclinks'                           => 'Tampaiakan $1 paubahan pahanyarnya dalam $2 hari tauncit<br />$3',
'diff'                              => 'bida',
'hist'                              => 'halam',
'hide'                              => 'Sungkupakan',
'show'                              => 'Tampaiakan',
'minoreditletter'                   => 's',
'newpageletter'                     => 'H',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => "[$1 {{PLURAL:$1|pa'itihi|papa'itihi}}]",
'rc_categories'                     => 'Watasi tutumbung (pisahakan lawan "|")',
'rc_categories_any'                 => 'Napa gin',
'rc-change-size-new'                => '$1 {{PLURAL:$1|bita|bita}} limbah paubahan',
'newsectionsummary'                 => '/* $1 */ hagian hanyar',
'rc-enhanced-expand'                => 'Tampaiakan rincian (parlu ada JavaScript)',
'rc-enhanced-hide'                  => 'Sungkupakan ririncian',
'rc-old-title'                      => 'aslinya diulah sawagai "$1"',

# Recent changes linked
'recentchangeslinked'          => 'Parubahan tarait',
'recentchangeslinked-feed'     => 'Parubahan tarait',
'recentchangeslinked-toolbox'  => 'Parubahan tarait',
'recentchangeslinked-title'    => 'Parubahan nang tarait lawan "$1"',
'recentchangeslinked-noresult' => 'Kadada parubahan pada tautan tutungkaran salawas wayah ditantuakan',
'recentchangeslinked-summary'  => "Ngini adalah sabuah daptar parubahan nang diulah hahanyar ngini pada tungkaran batautan matan sabuah tungkaran tartantu (atawa ka angguta matan sabuah tumbung tartantu).
Tutungkaran dalam [[Special:Watchlist|daptar itihan Pian]] ditandai '''kandal'''.",
'recentchangeslinked-page'     => 'Ngaran tungkaran:',
'recentchangeslinked-to'       => 'Tampaiakan parubahan matan tungkaran-tungkaran nang tataut lawan tungkaran nang disurungakan',

# Upload
'upload'                      => 'Hunggahakan barakas',
'uploadbtn'                   => 'Hunggahakan barakas',
'reuploaddesc'                => 'Walang mahunggah wan babulik ka purmulir hunggahan',
'upload-tryagain'             => 'Kirim katarangan barakas taubah',
'uploadnologin'               => 'Baluman babuat log',
'uploadnologintext'           => 'Pian musti [[Special:UserLogin|babuat log]] amun handak mahunggah babarakas.',
'upload_directory_missing'    => 'Direktori hunggahan ($1) hilang wan kada kawa diulah ulih webserver.',
'upload_directory_read_only'  => 'Direktori hunggahan ($1) kada kawa ditulisi ulih webserver.',
'uploaderror'                 => 'Hunggah tasalah',
'upload-recreate-warning'     => "'''Paringatan: Sabuah barakas bangaran ngini sudah dihapus atawa dipindahakan.'''
Log pahapusan wan pamindahan hagan tungkarran ngini adalah sawagai barikut:",
'uploadtext'                  => "Puruk purmulir di bawah gasan mahunggah barakas.
Gasan manampaiakan atawa manggagai barakas nang sabalumnya dimuat, puruk [[Special:FileList|daptar barakas]]. Pahunggahan (lagi) jua tacatat dalam [[Special:Log/upload|log pahunggahan]], samantara pahapusan tacatat dalam [[Special:Log/delete|log pahapusan]].

Gasan manampaiakn atawa maumpatakan barakas di dalam suatu tungkaran, puruk tautan lawan salah asa purmat di bawah ngini:
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Barakas.jpg]]</nowiki></code>''' hagan manampaiakan barakas dalam takaran aslinya
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Barakas.png|200px|thumb|left|naskah alternatip]]</nowiki></code>''' hagan manampaiakan barakas lawan libar 200px dalam sabuah kutak di kiwa tungkaran lawan 'naskah alternatip' sawagai katarangan gambar
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Barakas.ogg]]</nowiki></code>''' sawagai tautan langsung ka barakas nang dimaksud kada batampaiakan barakas ngitu lung wiki",
'upload-permitted'            => 'Macam barakas nang diijinakan: $1.',
'upload-preferred'            => 'Macam barakas nang dikatujui: $1.',
'upload-prohibited'           => 'Macam barakas nang ditangati: $1.',
'uploadlog'                   => 'log hunggah',
'uploadlogpage'               => 'Log mambuati',
'uploadlogpagetext'           => 'Di bawah ngini adalah sabuah daptar matan barakas nang hanyar dihuhunggah.
Janaki [[Special:NewFiles|galeri babarakas hanyar]] gasan  tampaian visual.',
'filename'                    => 'Ngaran barakas',
'filedesc'                    => 'Kasimpulan',
'fileuploadsummary'           => 'Kasimpulan:',
'filereuploadsummary'         => 'Parubahan barakas:',
'filestatus'                  => 'Status hak-rekap:',
'filesource'                  => 'Asal mula:',
'uploadedfiles'               => 'Babarakas tahunggah',
'ignorewarning'               => 'Kada mahuwal paringatan wan simpan haja barakas langsung.',
'ignorewarnings'              => 'Kada mahuwal apapun paringatan',
'minlength1'                  => 'Ngaran barakas musti sahikitnya asa abjad.',
'illegalfilename'             => 'Ngaran barakas "$1" mangandung karaktir nang kada dibulihakan dalam tungkaran jujudul.
Muhan ganti ngaran barakas wan cubai mahunggah pulang.',
'filename-toolong'            => 'Ngaran barakas kada bulih tapanjang pada 240 bita.',
'badfilename'                 => 'Ngaran barakas sudah diganti ka "$1".',
'filetype-mime-mismatch'      => 'Ekstensi barakas ".$1" kada pas lawan macam MIME matan barakas ($2).',
'filetype-badmime'            => 'Babarakas macam MIME "$1" kada bulih dihunggah.',
'filetype-bad-ie-mime'        => 'Kada kawa mahunggah barakas ngini karana Internet Explorer manangguh ngini sawagai "$1", nang kada dibulihakan wan samacam barakas babahaya.',
'filetype-unwanted-type'      => "'''\".\$1\"''' adalah samacam barakas nagn kada dihandaki.
Dikatujui {{PLURAL:\$3|macam barakas|mamacam barakas}} \$2.",
'filetype-banned-type'        => '\'\'\'".$1"\'\'\' {{PLURAL:$4|adalah macam barakas kada diijinakan|adalah mamacam barakas kada diijinakan}}.
Nang diijinakan {{PLURAL:$3|adalah macam barakas|adalah mamacam barakas}} $2.',
'filetype-missing'            => 'Barakas kada baisi ekstensi (nangkaya ",jpg").',
'empty-file'                  => 'Barakas nang Pian buatakan puang.',
'file-too-large'              => 'Barakas nang Pian buatakan kaganalan.',
'filename-tooshort'           => 'Ngaran barakas kahandapan.',
'filetype-banned'             => 'Macam barakas ini ditangati.',
'verification-error'          => 'Barakas nangini kada lulus paitihan.',
'hookaborted'                 => 'Parubahan nang Pian cuba ulah sudah digagalakan ulih unjun ekstensi.',
'illegal-filename'            => 'Ngaranbarakas kada dibulihakan.',
'overwrite'                   => 'Manindih tulis sabuah barakas nang ada kada dibulihakan.',
'unknown-error'               => 'Kasalahan kada dipinandui tajadi.',
'tmp-create-error'            => 'Kada kawa maulah barakas pahadangan.',
'tmp-write-error'             => 'Kasalahan sawaktu manulis barakas pahadangan.',
'large-file'                  => 'Disaranakan babarakas kada tapanjang pada $1;
barakas ngini $2.',
'largefileserver'             => 'Barakas ngini taganal pada nang dibulihakan server.',
'emptyfile'                   => 'Barakas nang Pian hunggah kusung pinanya,
Ngini pinanya ada salah katik ngaran barakas.
Muhun pariksa apa bubujuran Pian handak mahunggah barakas ngini.',
'windows-nonascii-filename'   => 'Wiki ngini kada manyukung ngaranbarakas awan karaktir isitimiwa.',
'fileexists'                  => 'Sabuah barakas bangaran ngin sudah ada, muhun pariksa <strong>[[:$1]]</strong> amun Pian kada musti amun Pian handak mangganti ngini.
[[$1|thumb]]',
'filepageexists'              => 'Tungkaran diskripsi gasan barakas ngini suda diulah di <strong>[[:$1]]</strong>, tagal kadada barakas bangaran ngini tasadia.
Kasimpulan nang Pian masukakan kada ham cungul pada tungkran diskripsi.
Hagan maulah kasimpulan Pian cungul di sana, Pian musti mambabaknya manual.
[[$1|thumb]]',
'fileexists-extension'        => 'Sabuah barakas bangaran sama sudah tasadia: [[$2|thumb]]
*Ngaran barakas hunggahan: <strong>[[:$1]]</strong>
* Ngaran barakas sudah tasadia: <strong>[[:$2]]</strong>
Muhun pilihi sabuah ngaran babida.',
'fileexists-thumbnail-yes'    => "Barakas ngini kajanakannya sabuah pancitraan nang dihalusi takarannya ''(thumbnail)''.
[[$1|thumb]]
Muhun pariksa barakas <strong>[[:$1]]</strong>.
Amun barakas dipariksa sama awan pancitraan takaran aslinya kada parlu mahunggah sabuah tambahan thumbnail.",
'file-thumbnail-no'           => "Ngaran barakas bamula awan <strong>$1</strong>.
Ngini kajanakannya sabuah pancitraan nang dihalusi takaran ''(thumbnail)''.
Amun Pian baisi pancitraan ngini barisulusi hibak hunggah nang ngini, amun kada muhun ubah ngaran barakas.",
'fileexists-forbidden'        => 'Sabuah barakas bangaran sama sudah tasadia, wan kada kawa ditindihtulis.
Amun Pian handak jua mahunggah barakas Pian, muhun babulik wan puruk sabuah ngaran hanyar.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Sabuah barakas bangaran ngini sudah tasadia di repositori barakas babagi
Amun Pian handak jua mahunggah barakas Pian, muhun babulik wan puruk sabuah ngaran hanyar.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Barakas ngini adalah sabuah duplikat matan {{PLURAL:$1|barakas|babarakas}} barikut:',
'file-deleted-duplicate'      => 'Sabuah barakas identik awan barakas ngini ([[:$1]]) suah dihapus sabalumnya.
Pian musti mamariksa halam pahapusan barakas ngitu hanyar manarusakan mahunggah pulang ngini.',
'uploadwarning'               => 'Paringatan hunggah',
'uploadwarning-text'          => 'Muhun mangganti katarangan barakas di bawah wan cubai pulang.',
'savefile'                    => 'Simpan barakas',
'uploadedimage'               => 'mamuat "[[$1]]"',
'overwroteimage'              => 'mahunggah sabuah pérsi hanyar matan "[[$1]]"',
'uploaddisabled'              => 'Pahunggahan dipajahakan.',
'copyuploaddisabled'          => 'Hunggah lawan URL pajah.',
'uploadfromurl-queued'        => "Pahunggahan Pian sudah ba'antri.",
'uploaddisabledtext'          => 'Hunggah barakas kada kawa.',
'php-uploaddisabledtext'      => 'Mahunggah barakas di PHP dipajahakan.
Muhun pariksa setelan file_uploads.',
'uploadscripted'              => 'Barakas ngini mangandung HTML atawa kudi script nang kawa ditarjamahakan tasalah ulih sabuah panjalajah web.',
'uploadvirus'                 => 'Barakas baisi pirus!
Ririncian: $1',
'uploadjava'                  => 'Barakas ngini adalah sabuah barakas ZIP nang mangandung sabuah barakas Java .class.
Mahunggah babarakas java kada dibulihakan, karana inya kawa maluncati wawatas kaamanan.',
'upload-source'               => 'Asal mula barakas',
'sourcefilename'              => 'Ngaranbarakas asal mula:',
'sourceurl'                   => 'Asal mula URL:',
'destfilename'                => 'Ngaranbarakas tujuan:',
'upload-maxfilesize'          => 'Takaran barakas maksimum: $1',
'upload-description'          => 'Diskripsi barakas',
'upload-options'              => 'Pilihan hunggah',
'watchthisupload'             => 'Itihi barakas ini',
'filewasdeleted'              => 'Sabuah barakas bangaran ngini suah dihunggah wan abis tu dihapus.
Pian musti pariksa $1 hanyar mahunggah ngini pulang.',
'filename-bad-prefix'         => "Ngaran barakas nang Pian hunggah bamula lawan '''\"\$1\"''', nang lainan-ngaran diskriptip biasanya utumatis dibari ulih kudakan digital.
Muhun pilih ngaran labih diskriptip lain gasan barakas Pian.",
'upload-success-subj'         => 'Kulihan mahunggah',
'upload-success-msg'          => 'Hunggahan Pian matan [$2] ruhui. Ngini tasadia di sia: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Hunggah bamasalah',
'upload-failure-msg'          => 'Ada sabauah masalah tadi tu pas Pian hunggah matan [$2]:
$1',
'upload-warning-subj'         => 'Paringatan mahunggah',
'upload-warning-msg'          => 'Ada masalah lawan hunggahan Pian matan [$2]. Pian bulih babulik ka [[Special:Upload/stash/$1|purmulir hunggah]] hagan mambujuri masalah ngini.',

'upload-proto-error'        => 'Protokol kada bujur',
'upload-proto-error-text'   => 'Hunggahan rimut parlu URL bamula lawan <code>http://</code> atawa <code>ftp://</code>.',
'upload-file-error'         => 'Kasalahan di dalam',
'upload-file-error-text'    => 'Sabuah kasalahan dalam tajadi wayah mancubai maulah sabuah barakas samantara dalam server.
Muhun kiau saurang [[Special:ListUsers/sysop|pambakal]].',
'upload-misc-error'         => 'Tasalah buat nang kada dipinandui',
'upload-misc-error-text'    => 'Nyunyuk kada dikatahui tajadi pas mahunggah.
Muhun pastiakan URL sah wan kawa diuangkai wan cubai pulang.
Amun masih haja bamasalah, kiau saurang [[Special:ListUsers/sysop|pambakal]].',
'upload-too-many-redirects' => 'URL mangandung kabanyakan paugahan.',
'upload-unknown-size'       => 'Ukuran kada dikatahui',
'upload-http-error'         => 'Sabuah kasalahan HTTP tajadi: $1',

# File backend
'backend-fail-stream'        => 'Kada kawa manyalarasakan barakas $1.',
'backend-fail-backup'        => 'Kada kawa mambackup barakas $1.',
'backend-fail-notexists'     => 'Si barakas $1 kadada.',
'backend-fail-hashes'        => 'Kada kawa kulihan barakas kasar hagan mananding.',
'backend-fail-notsame'       => 'Sabuah barakas kada dipinandui sudah ada pintangan $1.',
'backend-fail-invalidpath'   => '$1 adalah sabuah jalur panyimpanan kada sah.',
'backend-fail-delete'        => 'Kada kawa mahapus barakas $1.',
'backend-fail-alreadyexists' => 'Si barakas $1 sudah ada.',
'backend-fail-store'         => 'Kada kawa manyimpan $1 pintangan $2.',
'backend-fail-copy'          => 'Kada kawa manyimpan $1 ka $2.',
'backend-fail-move'          => 'Kada kawa mamindahakan $1 ka $2.',
'backend-fail-opentemp'      => 'Kada kawa maungkai barakas samantara.',
'backend-fail-writetemp'     => 'Kada kawa manulis ka barakas samantara.',
'backend-fail-closetemp'     => 'Kada kawa manungkup barakas samantara.',
'backend-fail-read'          => 'Kada kawa mambaca barakas $1.',
'backend-fail-create'        => 'Kada kawa maulah barakas $1.',

# Lock manager
'lockmanager-notlocked'        => 'Kada kawa mambuka-sunduk "$1"; ngini kada basunduk.',
'lockmanager-fail-closelock'   => 'Kada kawa manungkup "$1".',
'lockmanager-fail-deletelock'  => 'Kada kawa mahapus barakas tasunduk "$1".',
'lockmanager-fail-acquirelock' => 'Kada kawa bakulihan sunduk gasan "$1".',
'lockmanager-fail-openlock'    => 'Kada kawa maungkai sunduk barakas gasan "$1".',
'lockmanager-fail-releaselock' => 'Kada kawa malapas sunduk gasan "$1".',
'lockmanager-fail-db-bucket'   => 'Kada kawa santuk cukup sunduk databasis dalam wadah $1.',
'lockmanager-fail-db-release'  => 'Kada kawa malapas sunduk pintang databasis $1.',
'lockmanager-fail-svr-release' => 'Kada kawa malapas sunduk pintang sarvar $1.',

# ZipDirectoryReader
'zip-file-open-error' => 'Sabuah kasalahan tarikin wayah mambuka barakas gasan pariksa ZIP.',
'zip-wrong-format'    => 'Barakas nang diajuakan lainan sabuah barakas ZIP.',
'zip-bad'             => 'Barakas ngini korup atawa pinanya barakas ZIP nang kada kawa dibaca.
Barakas ngini kada kawa dipariksa  gasan kaamanan.',
'zip-unsupported'     => 'Barakas ngini adalah sabuah barakas ZIP nang dipuruk pitur ZIP nang kada disukung ulih MediaWiki.
Ngini kada kawa dipariksa gasan kaamanan.',

# Special:UploadStash
'uploadstash'          => 'Simpanan hunggahan',
'uploadstash-summary'  => 'Tungkaran ngini manyadiakan ungkaian ka babarakas nang tahunggah (atawa dalam proses hunggahan) tapi baluman ditarbitakan ka wiki.
Babarakas ngini kada kawa dilihat ka siapa pun kacuali pamuruk nang mahunggahnya.',
'uploadstash-clear'    => 'Kalarakan babarakas simpanan.',
'uploadstash-nofiles'  => 'Pian kada baisi babarakas simpanan.',
'uploadstash-badtoken' => 'Aksi kada ruhui dilaksanaakan, pinanya karana babakan Pian sudah kadaluarsa. Cubai pulang.',
'uploadstash-errclear' => 'Pangalaran babarakas kada ruhui.',
'uploadstash-refresh'  => "Sagar'akan daptar babarakas",
'invalid-chunk-offset' => 'Putungan kada sah diimbangi',

# img_auth script messages
'img-auth-accessdenied'     => 'Ungkaian ditolak',
'img-auth-nopathinfo'       => 'PATH_INFO hilang.
Server Pian kada disetel hagan malimpatakan panjalasan ngini.
Ngini karana CGI-based wan kada manyukung img_auth.
Janaki https://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'         => "Jalur nang diminta kada ta'atur lawan direktori hunggahan.",
'img-auth-badtitle'         => 'Kada kawa mambangun sabuah judul sah matan "$1".',
'img-auth-nologinnWL'       => 'Pian kada kawa babuat log wan \'$1" kadada dalam daptar putih.',
'img-auth-nofile'           => 'Barakas "$1" kadada.',
'img-auth-isdir'            => 'Pian mancuba hagan maungkai sabuah direktori "$1".
Hanya maungkai barakas dibulihakan.',
'img-auth-streaming'        => 'Streaming "$1".',
'img-auth-public'           => 'Pungsi img_auth.php mangaluarakan babarakas matan sabuah wiki paribadi.
Wiki ngini diatur sawagai wiki umum.
Gasan kaamanan baik, img_auth.php dipajahakan.',
'img-auth-noread'           => 'Pamuruk kada baisi hak ungkai hagan mambaca "$1".',
'img-auth-bad-query-string' => 'URL baisi sabuah string kueri kada sah.',

# HTTP errors
'http-invalid-url'      => 'URL kada sah: $1',
'http-invalid-scheme'   => 'URL lawan skema "$1" kada disukung.',
'http-request-error'    => 'Parmintaan HTTP gagal karana kasalah kada dikatahui.',
'http-read-error'       => 'Kasalahan baca HTTP.',
'http-timed-out'        => 'Parmintaan HTTP habis wayahnya.',
'http-curl-error'       => 'Kasalahan pas maambil URL: $1',
'http-host-unreachable' => 'Kada kawa mancapai URL.',
'http-bad-status'       => 'Ada sabuah masalah pas maminta HTTP: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Kada kawa mancapai URL.',
'upload-curl-error6-text'  => 'URL tasadia kada kawa dicapai.
Muhun pariksa ganda bahwasa URL bujur wan situs nyala.',
'upload-curl-error28'      => 'Wayah pahunggahan habis',
'upload-curl-error28-text' => 'Situs ngini kalawasan mananggapi.
Muhun pariksa manyalakah situs ngini, hadangi satumat wan cubai pulang.
Pian amun handak cubai pas wayah kada tapi haur.',

'license'            => 'Lisensi:',
'license-header'     => 'Lisensi',
'nolicense'          => 'Kadada mamilih',
'license-nopreview'  => '(titilikan kada tasadia)',
'upload_source_url'  => '(sabuah URL sah umum nang kawa diungkai)',
'upload_source_file' => '(sabuah barakas pada kumputir Pian)',

# Special:ListFiles
'listfiles-summary'     => 'Tungkaran istimiwa ngini manampaiakan samunyaan barakas hunggahan.
Parhatan disaring ulih pamuruk, babarakas nang pamuruk hunggah ralatan pahanyarnya matan barakas nang ditampaiakan.',
'listfiles_search_for'  => 'Gagai ngaran barakas:',
'imgfile'               => 'barakas',
'listfiles'             => 'Daptar barakas',
'listfiles_thumb'       => 'Pahalusan',
'listfiles_date'        => 'Tanggal',
'listfiles_name'        => 'Ngaran',
'listfiles_user'        => 'Pamuruk',
'listfiles_size'        => 'Ukuran',
'listfiles_description' => 'Pamaparan',
'listfiles_count'       => 'Janis',

# File description page
'file-anchor-link'          => 'Barakas',
'filehist'                  => 'Barakas halam',
'filehist-help'             => 'Klik pada tanggal/waktu gasan maniringi barakas ngini pada wayah itu.',
'filehist-deleteall'        => 'hapus samunyaan',
'filehist-deleteone'        => 'hapus',
'filehist-revert'           => 'bulikakan',
'filehist-current'          => 'daminian',
'filehist-datetime'         => 'Tanggal/Waktu',
'filehist-thumb'            => 'Pahalusan',
'filehist-thumbtext'        => 'Pahalusan gasan bantuk per $1',
'filehist-nothumb'          => 'Kadada thumbnail',
'filehist-user'             => 'Pamakai',
'filehist-dimensions'       => 'Matra',
'filehist-filesize'         => 'Ukuran barakas',
'filehist-comment'          => 'Ulasan',
'filehist-missing'          => 'Barakas hilang',
'imagelinks'                => 'Tautan barakas',
'linkstoimage'              => '{{PLURAL:$1|tautan tungkaran|$1 tautan tungkaran}} dudi ka barakas ngini:',
'linkstoimage-more'         => 'Labihan pada $1 {{PLURAL:$1|tatautan tungkaran|tautan tutungkaran}} ka barakas ngini.
Daptar barikut manampaiakan {{PLURAL:$1|tautan panambaian tungkaran|$1 panambaian tatautan tungkaran}} ka barakas ngini haja.
Sabuah [[Special:WhatLinksHere/$2|daptar hibak]] tasadia.',
'nolinkstoimage'            => 'Kadada tutungkaran nang bataut ka barakas ngini.',
'morelinkstoimage'          => 'Tiringi [[Special:WhatLinksHere/$1|tautan lagi]] ka barakas ngini.',
'linkstoimage-redirect'     => '$1 (barakas paugahan) $2',
'duplicatesoffile'          => 'Barikut {{PLURAL:$1|barakas panggandaan|$1 babarakas panggandaan}} matan barakas ngini ([[Special:FileDuplicateSearch/$2|rarincian labih]]):',
'sharedupload'              => 'Barakas ini matan $1 wan mungkin dipuruk rangka-rangka gawian lain.',
'sharedupload-desc-there'   => 'Barakas ngini matan $1 wan pina dipuruk ulih rarangka-gawi lain.
Muhun janaki [$2 tungkaran diskripsi barakas] gasan panjalasan labih.',
'sharedupload-desc-here'    => 'Barakas ngini matan $1 wan pina dipakai ulih rarangka-gawi lain.
Diskripsi ngini [$2 tungkaran diskripsi barakas] ditampaiakan di bawah.',
'filepage-nofile'           => 'Kadada barakas bangaran ngini.',
'filepage-nofile-link'      => 'Kadada barakas bangaran ngini tasadia, tagal Pian kawa [$1 mahunggah ngini].',
'uploadnewversion-linktext' => 'Buatakan bantuk nang labih hanyar matan barakas ini',
'shared-repo-from'          => 'matan $1',
'shared-repo'               => 'suatu repositori basama',

# File reversion
'filerevert'                => 'Bulikakan $1',
'filerevert-legend'         => 'Bulikakan barakas',
'filerevert-intro'          => "Pian mambulikakan '''[[Media:$1|$1]]''' ka macam [$4 pada $3, $2].",
'filerevert-comment'        => 'Alasan:',
'filerevert-defaultcomment' => 'Dibulikakan ka macam pada $2, $1',
'filerevert-submit'         => 'Bulikakan',
'filerevert-success'        => "'''[[Media:$1|$1]]''' sudah dibulikakan ka macam [$4 pada $3, $2]",
'filerevert-badversion'     => 'Kadada janis lokal bahari tumatan barakas ini lawan bacap waktu nang dimaksud.',

# File deletion
'filedelete'                   => 'Mahapus $1',
'filedelete-legend'            => 'Hapus barakas',
'filedelete-intro'             => "Pian huwal mahapus barakas '''[[Media:$1|$1]]''' awan barataan halamnya.",
'filedelete-intro-old'         => "Pian mahapus pirsi matan '''[[Media:$1|$1]]''' sawagai matan [$4 $3, $2].",
'filedelete-comment'           => 'Alasan:',
'filedelete-submit'            => 'Hapus',
'filedelete-success'           => "'''$1''' sudah tahapus.",
'filedelete-success-old'       => "Pirsi matan '''[[Media:$1|$1]]''' sawagai matan $3, $2 sudah tahapus.",
'filedelete-nofile'            => "'''$1''' kadada.",
'filedelete-nofile-old'        => "Kadada arsip pirsi matan '''$1''' lawan atribut diajuakan.",
'filedelete-otherreason'       => 'Alasan lain/tambahan:',
'filedelete-reason-otherlist'  => 'Alasan nang lain',
'filedelete-reason-dropdown'   => '*Alasan pahapusan
** Palanggaran hak cipta
** Barakas duplikat',
'filedelete-edit-reasonlist'   => 'Aalasan pahapusan babakan',
'filedelete-maintenance'       => 'Pahapusan wan pambulikakan babarakas kada-kawa samantara paharaguan.',
'filedelete-maintenance-title' => 'Kada kawa mahapus barakas',

# MIME search
'mimesearch'         => 'Panggagaian MIME',
'mimesearch-summary' => 'Tungkaran ngini kawa manyaring babarakas bamacam MIME.
Buati: contenttype/subtype, misal <code>image/jpeg</code>.',
'mimetype'           => 'Macam MIME',
'download'           => 'hunduh',

# Unwatched pages
'unwatchedpages' => 'Tungkaran nang kada diitihi',

# List redirects
'listredirects' => 'Daptar paugahan',

# Unused templates
'unusedtemplates'     => 'Citakan nang kada dipuruk',
'unusedtemplatestext' => "Daptar barikut adalah samua tungkaran pada ngaran kamar {{ns:template}} nang kada dipuruk di tungkaran manapun.
Pariksa 'hulu tautan lain ka citakan itu sabalum mahapusnya.",
'unusedtemplateswlh'  => 'tautan lain',

# Random page
'randompage'         => 'Tungkaran babarang',
'randompage-nopages' => 'Kadada tungkaran pada {{PLURAL:$2||}}kamar ngaran ini: $1.',

# Random redirect
'randomredirect'         => 'Paugahan babarang',
'randomredirect-nopages' => 'Kada tadapat paugahan pada ngaran kamar "$1".',

# Statistics
'statistics'                   => 'Statistik',
'statistics-header-pages'      => 'Statistik tungkaran',
'statistics-header-edits'      => 'Statistik babakan',
'statistics-header-views'      => 'Statistik panampaian',
'statistics-header-users'      => 'Statistik pamuruk',
'statistics-header-hooks'      => 'Statistik lainnya',
'statistics-articles'          => 'Tungkaran isi',
'statistics-pages'             => 'Jumlah tungkaran',
'statistics-pages-desc'        => 'Sabarataan tungkaran di wiki ini, tamasuk tungkaran pamandiran, paugahan, wan lain-lain.',
'statistics-files'             => 'Barakas nang dihunggahakan',
'statistics-edits'             => 'Jumlah babakan tumatan {{SITENAME}} dimulai',
'statistics-edits-average'     => 'Rata-rata babakan par tungkaran',
'statistics-views-total'       => 'Jumlah panampaian tungkaran',
'statistics-views-total-desc'  => 'Tilik ka tutungkaran nang kadada wan tungkaran istimiwa kada tamasuk',
'statistics-views-peredit'     => 'Titiringan par babakan',
'statistics-users'             => 'Jumlah [[Special:ListUsers|pamuruk tadaptar]]',
'statistics-users-active'      => 'Jumlah pamuruk aktip',
'statistics-users-active-desc' => 'Pamuruk nang sudah malakukan suatu aksi dalam {{PLURAL:$1|sahari|$1 hari}} tauncit.',
'statistics-mostpopular'       => 'Tungkaran nang paling banyak ditampaiakan',

'disambiguations'      => 'Tungkaran nang tahubung ka tungkaran disambiguasi',
'disambiguationspage'  => 'Template:Disambigu',
'disambiguations-text' => "Tutungkaran barikut bataut ka sabuah '''tungkaran disambigu'''.
Tutungkaran ngitu harusnya ka tupik nang sasuai.<br />
Sabuah tungkaran dianggap sawagai tungkaran disambigu amun ngini mamuruk sabuah citakan nang tataut matan [[MediaWiki:Disambiguationspage]]",

'doubleredirects'                   => 'Paugahan ganda',
'doubleredirectstext'               => 'Tungkaran ngini mandaptar tutungkaran nang maugah ka tutungkaran ugahan lain.
Tiap baris mangandung tautan ka ugahan panambaian wan kadua, sasarannya adalah ugahn kadua, nang biasanya tungkaran sasaran "sabujurnya", nang ugahan partama tuju.
Masukan nang <del>Disilangi</del> sudah dibaiki.',
'double-redirect-fixed-move'        => '[[$1]] sudah dipindahakan.
Ngini wayah ini sudah diugahakan ka [[$2]].',
'double-redirect-fixed-maintenance' => 'Mambaiki paugahan ganda matan [[$1]] ka [[$2]].',
'double-redirect-fixer'             => 'Ralatan paugahan',

'brokenredirects'        => 'Paugahan rakai',
'brokenredirectstext'    => 'Tautan paugahan barikut manuju ka tutungkaran non-tasadia:',
'brokenredirects-edit'   => 'babak',
'brokenredirects-delete' => 'hapus',

'withoutinterwiki'         => 'Tutungkaran kada batatautan bahasa',
'withoutinterwiki-summary' => 'Tutungkaran barikut kada batautan ka pipirsi bahasa lain.',
'withoutinterwiki-legend'  => 'Mulaan',
'withoutinterwiki-submit'  => 'Tampaiakan',

'fewestrevisions' => 'Tutungkaran lawan parubahan paling sadikit',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bit|bit}}',
'ncategories'             => '{{PLURAL:$1|tumbung|tutumbung}}',
'nlinks'                  => '$1 {{PLURAL:$1|tautan|tautan}}',
'nmembers'                => '$1 {{PLURAL:$1|angguta|angguta}}',
'nrevisions'              => '$1 {{PLURAL:$1|ralatan|raralatan}}',
'nviews'                  => 'dilihat $1 {{PLURAL:$1|kali|kali}}',
'nimagelinks'             => "Diguna'akan pada $1 {{PLURAL:$1|tungkaran|tutungkaran}}",
'ntransclusions'          => 'dipuruk pada $1 {{PLURAL:$1|tungkaran|tutungkaran}}',
'specialpage-empty'       => 'Kadada kulihan gasan lapuran ngini.',
'lonelypages'             => 'Tutungkaran yatim',
'lonelypagestext'         => 'Tutungkaran barikut kada bataut matab atawa ditranklusi ka dalam tutungkaran lain pada {{SITENAME}}.',
'uncategorizedpages'      => 'Tutungkaran kada batumbung',
'uncategorizedcategories' => 'Tutumbung kada batumbung',
'uncategorizedimages'     => 'Barakas nang kada batumbung',
'uncategorizedtemplates'  => 'Citakan nang kada batumbung',
'unusedcategories'        => 'Tutumbung kada tapuruk',
'unusedimages'            => 'Barakas nang kada tapakai',
'popularpages'            => 'Tutungkaran popular',
'wantedcategories'        => 'Tutumbung naang dihandaki',
'wantedpages'             => 'Tutungkaran nang dihandaki',
'wantedpages-badtitle'    => 'Judul kada sah dalam setelan kulihan: $1',
'wantedfiles'             => 'Barakas nang dihandaki',
'wantedfiletext-nocat'    => 'Data-data naya dipakai tagal kada ada. Data matan ripusituri asing kawa tadaptar biar haja ada. Satiap 
File-file berikut digunakan tapi tidak ada. File dari repositori asing dapat terdaftar meskipun ada. Any such false positives will be <del>manyarang</del>.',
'wantedtemplates'         => 'Citakan nang dihandaki',
'mostlinked'              => 'Tutungkaran tatuju tautan pambanyaknya',
'mostlinkedcategories'    => 'Tutumbung tatuju tautan pambanyaknya',
'mostlinkedtemplates'     => 'Cicitakan tatuju tautan pambanyaknya',
'mostcategories'          => 'Tutungkaran lawan pambanyaknya tutumbung',
'mostimages'              => "Barakas nang rancak diguna'akan",
'mostrevisions'           => 'Tutungkaran lawan parubahan paling banyak',
'prefixindex'             => 'Samunyaan tungkaran wan awalan',
'prefixindex-namespace'   => 'Samunyaan tutungkaran baawalan ($1 ngaran-kamar)',
'shortpages'              => 'Tutungkaran handap',
'longpages'               => 'Tutungkaran panjang',
'deadendpages'            => 'Tutungkaran buntu',
'deadendpagestext'        => 'Tutungkaran barikut kada bataut ka tutungkaran lain pada {{SITENAME}}.',
'protectedpages'          => 'Tutungkaran nang dilindungi',
'protectedpages-indef'    => 'Hanya gasan palindungan lawan jangka waktu kada tabatas',
'protectedpages-cascade'  => 'Palindungan barénténg haja',
'protectedpagestext'      => 'Tutungkaran barikut dilindungi matan pamindahan atawa pambabakan',
'protectedpagesempty'     => 'Kadada tutungkaran nang masih dilindungi awan paramitir ngitu.',
'protectedtitles'         => 'Jujudul nang dilindungi',
'protectedtitlestext'     => 'Jujudul barikut dilindungi gasan diulah',
'protectedtitlesempty'    => 'Kadada jujudul nang masih dilindungi awan paramitir ngitu.',
'listusers'               => 'Daptar pamuruk',
'listusers-editsonly'     => 'Tiringi papamuruk awan babakan',
'listusers-creationsort'  => 'Susun ulih tanggal paulahan',
'usereditcount'           => '$1 {{PLURAL:$1|babakan|bababakan}}',
'usercreated'             => '{{GENDER:$3|Diulah}} pada $1 pukul $2',
'newpages'                => 'Tungkaran hanyar',
'newpages-username'       => 'Ngaran pamuruk:',
'ancientpages'            => 'Tutungkaran panuhanya',
'move'                    => 'Pindahakan',
'movethispage'            => 'Pindahakan tungkaran ini',
'unusedimagestext'        => 'Babarakas barikut ada tagal kada diumpatakan di tungkaran mamana.
Muhun catat bahwasa situs web lain pina-ai bataut ka sabuah barakas awan sabuah URL langsung, wan karana ngini masih-ha didaptar di sia biar gin aktip dipuruk.',
'unusedcategoriestext'    => 'Tumbung tutungkaran barikut ada, walaupun kadada tungkaran lain atawa tumbung mamuruknya.',
'notargettitle'           => 'Kadada tujuan',
'notargettext'            => 'Pian kada maajuakan sabuah tungkaran atawa pamuruk sasaran malakuakan palakuan ini.',
'nopagetitle'             => 'Kadada tungkaran sasaran',
'nopagetext'              => 'Tungkaran sasaran nang Pian ajuakan kadada.',
'pager-newer-n'           => '{{PLURAL:$1|tahanyar 1|tahanyar $1}}',
'pager-older-n'           => '{{PLURAL:$1|talawas 1|talawas $1}}',
'suppress'                => 'Pangawasan',
'querypage-disabled'      => 'Tungkaran istimiwa ngini dikada-kawakan gasan alasan ginawi.',

# Book sources
'booksources'               => 'Buku bamula',
'booksources-search-legend' => 'Gagai gasan buku asal mula',
'booksources-go'            => 'Tulak ka',
'booksources-text'          => 'Di bawah adalah sabuah daptar tautan ka situs lain nang manjual bubuku hanyar wan bakas, wan jua baisi panjalasan labih pasal bubuku nang Pian ugai:',
'booksources-invalid-isbn'  => 'ISBN nang dibari mancungul kada sah; pariksa kalua-ai tasalah marekap matan asal-mula aslinya.',

# Special:Log
'specialloguserlabel'  => 'Pamakai:',
'speciallogtitlelabel' => 'Tujuan (judul atawa pamakai):',
'log'                  => 'Log',
'all-logs-page'        => 'Samunyaan log umum',
'alllogstext'          => 'Tampaian baimbai matan sabataan log nang ada matan {{SITENAME}}.
Pian kada mawatasi tiringan lawan mamilih sabuah macam log, ngaran-pamuruk (sansitip kapital), atawa tungkaran tapangaruh (sansitip kapital jua).',
'logempty'             => 'Kadada barang nang parsis pintang log.',
'log-title-wildcard'   => 'Gagai judul ba-awalan awan naskah ngini',

# Special:AllPages
'allpages'          => 'Samunyaan tungkaran',
'alphaindexline'    => '$1 sampai $2',
'nextpage'          => 'Tungkaran salanjutnya ($1)',
'prevpage'          => 'Tungkaran sabalumnya ($1)',
'allpagesfrom'      => 'Manampaiakan tungkaran mulai matan:',
'allpagesto'        => 'Manampaiakan ujung pahabisan tungkaran:',
'allarticles'       => 'Samunyaan tungkaran',
'allinnamespace'    => 'Sabarataan tutungkaran (ngaran-kamar $1)',
'allnotinnamespace' => 'Sabarataan tutungkaran (lainan di ngaran-kamar $1)',
'allpagesprev'      => 'Sabalumnya',
'allpagesnext'      => 'Dudi',
'allpagessubmit'    => 'Tulak',
'allpagesprefix'    => 'Tampilakan tutungkaran bamula lawan:',
'allpagesbadtitle'  => 'Judul tungkaran nang dibari kada sah atawa baisi sabuah awalan antar-bahasa atawa antar-wiki.
Nangini bisa baisi satu atawa labih karaktir nang saharusnya kadada di judul.',
'allpages-bad-ns'   => '{{SITENAME}} kada baisi ngaran-kamar "$1".',

# Special:Categories
'categories'                    => 'Tutumbung',
'categoriespagetext'            => '{{PLURAL:$1|tumbung mangandung|tutumbung mangandung}} barikut baisi tutungkaran atawa midia.
[[Special:UnusedCategories|Tumbung kada dipuruk]] kada ditampaiakan di sia.
Janaki jua [[Special:WantedCategories|tutumbung nang dihandaki]].',
'categoriesfrom'                => 'Manampaiakan tutumbung mulai matan:',
'special-categories-sort-count' => 'susun ulih rikinan',
'special-categories-sort-abc'   => 'susun abjad',

# Special:DeletedContributions
'deletedcontributions'             => 'Hapus sumbangan pamuruk',
'deletedcontributions-title'       => 'Hapus sumbangan pamuruk',
'sp-deletedcontributions-contribs' => 'Sumbangan',

# Special:LinkSearch
'linksearch'       => 'Manggagai tautan luar',
'linksearch-pat'   => 'Gagai bapola:',
'linksearch-ns'    => 'Ngaran-kamar:',
'linksearch-ok'    => 'Gagai',
'linksearch-text'  => 'Kartu liar nangkaya "*.wikipedia.org" hingkat diguna\'akan.
Mamarlukan sadikitnya asa ranah tingkat atas, misalnya "*.org".<br />
Protokol nang didukung: <code>$1</code> (jangan tambahakan dalam panggagaian Pian)',
'linksearch-line'  => '$1 ditautakan matan $2',
'linksearch-error' => 'Kartu-liar mancungul pintang awalan matan ngaranhost.',

# Special:ListUsers
'listusersfrom'      => 'Manampaiakan papamuruk mulai matan:',
'listusers-submit'   => 'Tampaiakan',
'listusers-noresult' => 'Kadada pamuruk tatamu.',
'listusers-blocked'  => '(diblukir)',

# Special:ActiveUsers
'activeusers'            => 'Daptar pamuruk aktip',
'activeusers-intro'      => 'Ngini adalah sabuah daptar papamuruk sabuah bantuk kagiatan dalam tauncit $1 {{PLURAL:$1|hari|hahari}}.',
'activeusers-count'      => '$1 {{PLURAL:$1|babak|babakan}} dalam tauncit {{PLURAL:$3|hari|$3 hahari}}',
'activeusers-from'       => 'Manampaiakan papamuruk mulai matan:',
'activeusers-hidebots'   => 'Sungkupakan bot',
'activeusers-hidesysops' => 'Sungkupakan pambakal',
'activeusers-noresult'   => 'Kadada papamuruk tatamu.',

# Special:Log/newusers
'newuserlogpage'     => 'Log pamakai hanyar',
'newuserlogpagetext' => 'Ngini adalah sabuah log paulahan pamuruk.',

# Special:ListGroupRights
'listgrouprights'                      => 'Galambang hak pamuruk',
'listgrouprights-summary'              => 'Barikut adalah sabuah daptar matan galambang pamuruk nang ada di wiki ngini, lawan hak ungkai masing-masing.
Ada di [[{{MediaWiki:Listgrouprights-helppage}}|tambahan panjalasan]] pasal hak par urangan.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Hak nang balaku</span>
* <span class="listgrouprights-revoked">Hak nang dicukut</span>',
'listgrouprights-group'                => 'Galambang',
'listgrouprights-rights'               => 'Hak',
'listgrouprights-helppage'             => 'Help:Galambang hak',
'listgrouprights-members'              => '(daptar angguta)',
'listgrouprights-addgroup'             => 'Tambahi {{PLURAL:$2|galambang|gagalambang}}: $1',
'listgrouprights-removegroup'          => 'Hapus {{PLURAL:$2|galambang|gagalambang}}: $1',
'listgrouprights-addgroup-all'         => 'Tambahi samunyaan gagalambang',
'listgrouprights-removegroup-all'      => 'Hapus samunyaan gagalambang',
'listgrouprights-addgroup-self'        => 'Tambahi {{PLURAL:$2|galambang|gagalambang}} ka akun surang: $1',
'listgrouprights-removegroup-self'     => 'Hapus {{PLURAL:$2|galambang|gagalambang}} matan akun surang: $1',
'listgrouprights-addgroup-self-all'    => 'Tambahi samunyaan gagalambang ka akun surang',
'listgrouprights-removegroup-self-all' => 'Hapus samunyaan gagalambang matan akun surang',

# E-mail user
'mailnologin'          => 'Kadada alamat kirim',
'mailnologintext'      => 'Pian musti [[Special:UserLogin|babuat log]] wan baisi sabuah alamat suril sah di [[Special:Preferences|kakatujuan]] Pian hagan mangirim suril ka papamuruk lain.',
'emailuser'            => 'Suril pamakai',
'emailpage'            => 'Surili pamakai',
'emailpagetext'        => 'Pian kawa mamuruk purmulir di bawah hagan mangirim sabuah suril ka pamuruk ngini.
Alamat sril Pian pintang [[Special:Preferences|kakatujuan pamuruk Pian]] akan cungul  sawagai "Matan" alamat suril, lalu-ai panarima akan kawa langsung mambalas ka Pian.',
'usermailererror'      => 'Objek surat ada kasalahan dibulikakan:',
'defemailsubject'      => 'Suril {{SITENAME}} matan pamuruk "$1"',
'usermaildisabled'     => 'Suril pamuruk dipajahakan',
'usermaildisabledtext' => 'Pian kada kawa mangirim suril ka papamuruk lain di wiki ngini',
'noemailtitle'         => 'Kadada alamat suril',
'noemailtext'          => 'Pamuruk ngini kada baisi sabuah alamat suril sah nang diajuakan.',
'nowikiemailtitle'     => 'Kadada suril dibulihakan.',
'nowikiemailtext'      => 'Pamuruk ngini sudah mamilih kada manarima suril matan papamuruk lain.',
'emailnotarget'        => 'Kada-tasadia atawa ngaranpamuruk kada sah gasan panarima.',
'emailtarget'          => 'Buati ngaranpamuruk panarima',
'emailusername'        => 'Ngaranpamuruk:',
'emailusernamesubmit'  => 'Kirim',
'email-legend'         => 'Kirimi sabuah suril ka pamuruk {{SITENAME}} lain',
'emailfrom'            => 'Matan:',
'emailto'              => 'Hagan:',
'emailsubject'         => 'Parihal:',
'emailmessage'         => 'Pasan:',
'emailsend'            => 'Kirim',
'emailccme'            => 'Surili ulun sabuah salinan pasan ulun.',
'emailccsubject'       => 'Salinan pasan Pian hagan: $1: $2',
'emailsent'            => 'Suril takirim',
'emailsenttext'        => 'Suril pasan Pian sudah takirim.',
'emailuserfooter'      => 'Suril ngini dikirim ulih $1 hagan $2 lung pungsi "Suril pamuruk" pada {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Tinggalakan sistim pasan.',
'usermessage-editor'  => ' Sistim panyampai pasan',

# Watchlist
'watchlist'            => 'Daptar itihan ulun',
'mywatchlist'          => 'Daptar itihan ulun',
'watchlistfor2'        => 'Gasan $1 $2',
'nowatchlist'          => 'Pian kada baisi apa pun pada daptar itihan Pian.',
'watchlistanontext'    => 'Muhun $1 hagan maniringi atawa mambabak nang dalam daptar itihan Pian.',
'watchnologin'         => 'Baluman babuat log',
'watchnologintext'     => 'Pian musti [[Special:UserLogin|babuat log]] amun handak magaganti daptar itihan Pian.',
'addwatch'             => 'Tambahi ka daptar itihan',
'addedwatchtext'       => "Tungkaran \"[[:\$1]]\" sudah ditambahakan ke [[Special:Watchlist|daptar itihan]] Pian.
Parubahan-parubahan salanjutnya pada tungkaran ini dan tungkaran pamandiran taraitnya akan takambit di sia, wan tungkaran itu akan ditampaiakan '''kandal''' pada [[Special:RecentChanges|daptar parubahan tahanyar]] cagar labih mudah diitihi.",
'removewatch'          => 'Buang matan daptar itihan',
'removedwatchtext'     => 'Tungkaran "[[:$1]]" sudah dihapus matan [[Special:Watchlist|daptar itihan]] Pian.',
'watch'                => 'Itih',
'watchthispage'        => 'Itihi tungkaran ini',
'unwatch'              => 'walang maitihi',
'unwatchthispage'      => 'Mandak maitihi',
'notanarticle'         => 'Lainan sabuah tungkaran isi',
'notvisiblerev'        => 'Ralatan tauncit ulih saurang pamuruk babida sudah dihapus',
'watchnochange'        => 'Kadada nang Pian itihi dibabak parhatan jangka wayah ngitu.',
'watchlist-details'    => '{{PLURAL:$1|$1 tungkaran|$1 tungkaran}} dalam daptar itihan Pian, kada mahitung tungkaran pamandiran.',
'wlheader-enotif'      => 'Suril pamadahan dipajahi.',
'wlheader-showupdated' => "* Tutungkaran nang ba-ubah tumatan ilangan tauncit Pian ditampaiakan dalam '''hurup kandal'''",
'watchmethod-recent'   => 'pariksa bababakan tahanyar gasan tungkaran nang diitihi.',
'watchmethod-list'     => 'pariksa tutungkaran nang diitihi gasan bababakan tahanyar',
'watchlistcontains'    => 'Paitihan Pian mangandung $1 {{PLURAL:$1|tungkaran|tutungkaran}}.',
'iteminvalidname'      => "Masalah awan barang '$1', bangaran kada sah...",
'wlnote'               => "Di bawah naya adalah {{PLURAL:$1|paubahan|'''$1''' paubahan}} tauncit dalam '''$2''' jam tauncit, par $3, $4.",
'wlshowlast'           => 'Tampaiakan $1 jam $2 hari pahabisan $3',
'watchlist-options'    => 'Pilihan daptar itihan',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'       => 'Maitihi...',
'unwatching'     => 'Kada jadi maitihi...',
'watcherrortext' => 'Sabuah kasalahan tajadi parhatan setelan paitihan Pian diubah gasan "$1".',

'enotif_mailer'                => 'Panyurili pamadahan {{SITENAME}}',
'enotif_reset'                 => 'Tandai samunyaan tutungkaran sudah diilangi',
'enotif_newpagetext'           => 'Ngini adalah sabuah tungkaran hanyar.',
'enotif_impersonal_salutation' => 'Pamuruk {{SITENAME}}',
'changed'                      => "ta'ubah",
'created'                      => "ta'ulah",
'enotif_subject'               => 'Tungkaran $PAGETITLE pintang {{SITENAME}} sudah $CHANGEDORCREATED ulih $PAGEEDITOR',
'enotif_lastvisited'           => 'Janaki $1 gasan samunyaan parubahan mula Pian pauncitan tadi bailang.',
'enotif_lastdiff'              => 'Janaki $1 hagaan maniringi parubahan ngini.',
'enotif_anon_editor'           => 'pamuruk kada-bangaran $1',
'enotif_body'                  => 'Halo $WATCHINGUSERNAME,


Tungkaran $PAGETITLE di {{SITENAME}} sudah $CHANGEDORCREATED pada $PAGEEDITDATE ulih $PAGEEDITOR, janaki $PAGETITLE_URL gasan ralatan wayah ini.

$NEWPAGE

Kasimpulan pambabak: $PAGESUMMARY $PAGEMINOREDIT

Hubungi pambabak:
suril: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Kami kada akan mangirim pambaritahuan lain amun ada parubahan labih lanjut sampai Pian mailangi tungkaran ngini.
Pian kawa jua manyetel-pulang bandira pambaritahuan hagan samunyaan tungkaran nang Pian itihi dalam paitihan Pian.

Sistem kakawalan pambaritahuan {{SITENAME}} Pian

--
Hagan maubah setelan suril pambaritahuan Piann, ilangi
{{canonicalurl:{{#special:Preferences}}}}

Hagan maubah setelan paitihan Pian, ilangi
$UNWATCHURL

kitihan-bulik wan pangganian labih jauh:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Hapus tungkaran',
'confirm'                => 'Yakinakan',
'excontent'              => "isi sabalumnya: '$1'",
'excontentauthor'        => "isinya: '$1' haja (wan aasaannya panyumbang adalah '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "isi sabalum dikusungakan: '$1'",
'exblank'                => 'tungkaran dikusungakan',
'delete-confirm'         => 'Hapus "$1"',
'delete-legend'          => 'Hapus',
'historywarning'         => "'''Paringatan:''' Tungkaran nang Pian pasal hagan hapus baisi sabuah halam sakitar $1 {{PLURAL:$1|ralatan|raralatan}}:",
'confirmdeletetext'      => 'Pian handak mahapus sabuah tungkaran awan samunyaan halamnya.
Muhun mamastiakan amun Pian handak manggawi ini, bahwasa Pian paham akibatnya, wan apa nang Pian gawi ini sasuai awan [[{{MediaWiki:Policy-url}}|kabijakan {{SITENAME}}]].',
'actioncomplete'         => 'Pa-ulahan tuntung',
'actionfailed'           => 'Palakuan luput',
'deletedtext'            => '"$1" sudah tahapus. Lihati $2 sabuah rakaman gasan nang hanyar ni tahapus.',
'dellogpage'             => 'Log pahapusan',
'dellogpagetext'         => 'Di bawah ngini adalah sabuah daptar matan pahapusan hahanyar ni.',
'deletionlog'            => 'log pahapusan',
'reverted'               => 'Dibulikakan ka raralatan tadamini',
'deletecomment'          => 'Alasan:',
'deleteotherreason'      => 'Alasan lain/tambahan:',
'deletereasonotherlist'  => 'Alasan lain',
'deletereason-dropdown'  => '*Alasan awam pahapusan
** Parmintaan panulis
** Parumpakan hak rekap
** Vandalisma',
'delete-edit-reasonlist' => 'Babak alasan pahapusan',
'delete-toobig'          => 'Tungkaran ngini baisi sabuah halam ganal, labih pada $1 {{PLURAL:$1|ralatan|raralatan}}.
Pahapusan tutungkaran kaini dibatasi hagan mancagah parusakan mandadak di {{SITENAME}}.',
'delete-warning-toobig'  => 'Tungkaran ngini baisi halam babakan ganal, labih pada $1 {{PLURAL:$1|ralatan|raralatan}}.
Mahapus ngini kawa mangaruhi databasis oparasi {{SITENAME}};
jalanakan awan ba-a-awas.',

# Rollback
'rollback'          => 'Gulung bulik babakan',
'rollback_short'    => 'Gulung-bulik',
'rollbacklink'      => 'bulikakan',
'rollbackfailed'    => 'Guling-bulik luput',
'cantrollback'      => 'Kada kawa mambalikakan babakan;
panyumbang tauncit adalah asa-asanya panulis tungkaran ngini.',
'alreadyrolled'     => 'Kada kawa malakukan pambulikan ka ralatan tauncit [[:$1]] ulih [[User:$2|$2]] ([[User talk:$2|pandir]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
pamuruk lain sudah mambabak atawa malakukan pambulikan lawan tungkaran ini.

Babakan tauncit dilakukan ulih [[User:$3|$3]] ([[User talk:$3|pandir]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Kumintar pambabakan adalah: \"''\$1''\".",
'revertpage'        => '←Babakan [[Special:Contributions/$2|$2]] ([[User talk:$2|pandir]]) dibulikakan ka ralatan tauncit ulih [[User:$1|$1]]',
'revertpage-nouser' => 'Pambulikan babakan ulih (pamuruk dihapus) ka babakan tauncit ulih [[User:$1|$1]]',
'rollback-success'  => 'Pambalikakan babakab ulih $1;
diubah bulik ka ralatan tauncit ulih $2.',

# Edit tokens
'sessionfailure-title' => 'Sesi luput',
'sessionfailure'       => 'Pinanya ada sabuah masalah awan sesi babuat loh Pian;
Palakuan ngini sudah diwalangi sawagai pra-awasan malawan sesi pambajakan.
Tulak babulik ka tungkaran sabalumnya, muat-pulang tungkaran ngitu wan lalu-ai cuba pulang.',

# Protect
'protectlogpage'              => 'Log palindungan',
'protectlogtext'              => 'Di bawah adalah sabuah daptar parubahan ka parlindungan tungkaran.
Janaki [[Special:ProtectedPages|daptar tungkaran talindungi]] gasan daptar parlindungan tungkaran tadamini.',
'protectedarticle'            => "malindungi ''[[$1]]''",
'modifiedarticleprotection'   => 'maubah tingkat perlindungan "[[$1]]"',
'unprotectedarticle'          => 'mahilangakan palindungan "[[$1]]"',
'movedarticleprotection'      => 'mamindahakan pangaturan protéksi matan "[[$2]]" ka "[[$1]]"',
'protect-title'               => 'Malindungi "$1"',
'protect-title-notallowed'    => 'Tiringi tingkat parlindungan matan "$1"',
'prot_1movedto2'              => '[[$1]] dipindahakan ka [[$2]]',
'protect-badnamespace-title'  => 'Ngaran-kamar nang kada-dilindungi',
'protect-badnamespace-text'   => 'Tutungkaran dalam ngaran-kamar ngini kada kawa dilindungi.',
'protect-legend'              => 'Konpirmasi palindungan',
'protectcomment'              => 'Alasan:',
'protectexpiry'               => 'Kadaluwarsa:',
'protect_expiry_invalid'      => 'Waktu kadaluwarsa kada sah.',
'protect_expiry_old'          => 'Waktu kadaluwarsa adalah pada masa bahari.',
'protect-unchain-permissions' => 'Lapas-sunduk pilihan parlindungan labih jauh',
'protect-text'                => "Pian kawa maniring atawa mangganti tingkatan palindungan gasan tungkaran '''$1''' di sia.",
'protect-locked-blocked'      => "Pian kada kawa maubah tingkat parlindungan parhatan diblukir.
Di sia adalah setelan tadamini gasan tungkaran '''$1''':",
'protect-locked-dblock'       => "Tingkat parlindungan kada kawa diubah karana ada sabuah sunduk databasis aktip.
Di sia adalah setelan tadamini gasan tungkaran '''$1''':",
'protect-locked-access'       => "Akun Pian kada baisi ijin gasan maubah tingkatan palindungan tungkaran.
Di sia adalah pangaturan wayah ini gasan tungkaran '''$1''':",
'protect-cascadeon'           => 'Tungkaran ini rahatan dilindungi lantaran diumpatakan dalam {{PLURAL:$1|tungkaran|tungkaran-tungkaran}} barikut nang sudah aktip palindungan barénténgnya.
Pian kawa maubah tingkatan palindungan gasan tungkaran ini, tagal ini kada pacang mangaruhi palindungan barénténg.',
'protect-default'             => 'Bulihakan samua pamuruk',
'protect-fallback'            => 'Mamarluakan ijin "$1"',
'protect-level-autoconfirmed' => 'Blukir pamuruk hanyar wan kada tadaptar',
'protect-level-sysop'         => 'Hanya pambakal',
'protect-summary-cascade'     => 'barénténg',
'protect-expiring'            => 'kadaluwarsa $1 (UTC)',
'protect-expiring-local'      => 'kadaluwarsa $1',
'protect-expiry-indefinite'   => 'kada bawatas',
'protect-cascade'             => 'Lindungi tungkaran-tungkaran nang tamasuk dalam tungkaran ini (palindungan barénténg)',
'protect-cantedit'            => 'Pian kada kawa maubah tingkatan palindungan tungkaran ini marga Pian kada baisi hak gasan itu.',
'protect-othertime'           => 'Wayah lain:',
'protect-othertime-op'        => 'wayah lain',
'protect-existing-expiry'     => 'Wayah kadaluwarsa nang ada: $2, $3',
'protect-otherreason'         => 'Alasan lain/tambahan:',
'protect-otherreason-op'      => 'Alasan nang lain',
'protect-dropdown'            => '*Alasan awam parlindungan
** Limpuar vandalisma
** Limpuar spam
** Parang babakan nang kada-produktip
** Tungkaran balalulintas pancau',
'protect-edit-reasonlist'     => 'Babak alasan palindungan',
'protect-expiry-options'      => '1 jam:1 hour,1 hari:1 day,1 minggu:1 week,2 minggu:2 weeks,1 bulan:1 month,3 bulan:3 months,6 bulan:6 months,1 tahun:1 year,salawasan:infinite',
'restriction-type'            => 'Parijinan:',
'restriction-level'           => 'Tingkatan hinggan:',
'minimum-size'                => 'Ukuran minimum',
'maximum-size'                => 'Ukuran maksimum',
'pagesize'                    => '(bita)',

# Restrictions (nouns)
'restriction-edit'   => 'Babak',
'restriction-move'   => 'Pindahakan',
'restriction-create' => 'Ulah',
'restriction-upload' => 'Unggah',

# Restriction levels
'restriction-level-sysop'         => 'palindungan hibak',
'restriction-level-autoconfirmed' => '(semi-dilindungi)',
'restriction-level-all'           => 'samunyaan tingkatan',

# Undelete
'undelete'                     => 'Tiringi tutungkaran tahapus',
'undeletepage'                 => 'Tiringi wan bulikakan tutungkaran tahapus',
'undeletepagetitle'            => "'''Barikut mangandung raralatan tahapus matan [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Tiringi tutungkaran tahapus',
'undeletepagetext'             => 'Barikut {{PLURAL:$1|tungkaran sudah dihapus tagal|$1 tutungkaran sudah dihapus tagal}} masih dalam arkip wan kawa disimpan-pulang.
Arkip kawa dibarasihakan bajangka.',
'undelete-fieldset-title'      => 'Mambulikakan ralatan',
'undeleteextrahelp'            => "Hagan manyimpan-pulang sabarataan halam tungkaran, tingalakan samunyaan kutak-pariksa kada-dipilih wan klik '''''{{int:undeletebtn}}'''''.
Hagan manggawi sabuah simpan-pulang, pariksa kukutak tahubung ka raralatan nang handak disimpan-pulang, wan klik
'''''{{int:undeletebtn}}'''''.",
'undeleterevisions'            => "$1 {{PLURAL:$1|ralatan|raralatan}} ta'arsip",
'undeletehistory'              => 'Amun Pian manyimpan-pulang tungkaran ngini, samunyaan raralatan akan tasimpan-pulang ka halamnya.
Amun sabuah tungkaran puga awan ngaran sama diulah parhatan pahapusan, raralatan nang disimpan-pulang akan cungul dalam halam sabalumnya.',
'undeleterevdel'               => 'Lapas-hapusan kada akan digawi amun ngini akan kulihan di tungkaran atas atawa barakas ralatan sapalih tahapus.
Dalam kasus kaini, Pian musti malapas-pariksa atawa lapas-sambunyi pahapusan ralatan pahanyarnya.',
'undeletehistorynoadmin'       => 'Tungkaran ngini sudah tahapus.
Alasan pahapusan ditampaiakan dalam kasimpulan di bawah, baimbai awan rarincian papamuruk nang sudah mambabak tungkaran ngini sabalum pahapusan.
Naskah aktual pada raralatan pahapusan ngini ada hagan pambakal haja.',
'undelete-revision'            => 'Ralatan tahapus matan $1 (pada $4, $5) ulih $3:',
'undeleterevision-missing'     => 'Raralatan kada sah atawa hilang.
Pian kalu-ai baisi tautan buruk, atawa ralatan sudah disimpan-pulang atau dibuang matan arkip.',
'undelete-nodiff'              => 'Kadada ralatan sabalumnya tatamu.',
'undeletebtn'                  => 'Bulikakan',
'undeletelink'                 => 'tiring/bulikakan',
'undeleteviewlink'             => 'tiringi',
'undeletereset'                => 'Bulikakan setelan',
'undeleteinvert'               => 'Bulikakan pilihan',
'undeletecomment'              => 'Alasan:',
'undeletedrevisions'           => '{{PLURAL:$1|1 ralatan|$1 raralatan}} dibulikakan',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 ralatan|$1 raralatan}} and {{PLURAL:$2|1 barakas|$2 babarakas}} dibulikakan',
'undeletedfiles'               => '$1 {{PLURAL:$1|barakas|babarakas}} dibulikakan',
'cannotundelete'               => 'Walang mahapus gagal;
ada urang lain nang badahulu mawalangi pahapusan tungkaran ngini.',
'undeletedpage'                => "'''$1 sudah disimpan-pulang'''
Janaki [[Special:Log/delete|log pahapusan]] gasan sabuah rakaman matan pahapusan wan panyimpanan-pulang.",
'undelete-header'              => 'Janaki [[Special:Log/delete|log pahapusan]] gasan tutungkaran hanyar tahapus.',
'undelete-search-title'        => 'Gagai tutungkaran tahapus',
'undelete-search-box'          => 'Gagai tutungkaran tahapus',
'undelete-search-prefix'       => 'Tampaiakan tutungkaran bamula lawan:',
'undelete-search-submit'       => 'Gagai',
'undelete-no-results'          => 'Kadada tutungkaran nang parsis ta-ugai dalam arkip pahapusan.',
'undelete-filename-mismatch'   => 'Kada kawa malapas-hapusan ralatan barakas awan cap wayah $1: ngaran-barakas kada-parsis',
'undelete-bad-store-key'       => 'Kada kawa malapas-hapusan awan cap wayah $1: barakas hilang sabalum pahapusan.',
'undelete-cleanup-error'       => 'Kasalahan mahapus barakas arkip kada-dipuruk "$1".',
'undelete-missing-filearchive' => 'Kada kawa manyimpan-pulang barakas arkip ID $1 karana ngini kadada dalam data basis.
Pinanya barakas ngini sudah dilapas-hapusannya.',
'undelete-error'               => 'Kasalahan kada-mahapus tungkaran',
'undelete-error-short'         => 'Kasalahan malapas-hapusan barakas: $1',
'undelete-error-long'          => 'Kasalahan tarikin parhatan malapas-hapusan barakas:

$1',
'undelete-show-file-confirm'   => 'Pian handak maniringi ralatan tahapus matan barakas "<nowiki>$1</nowiki>" matan $2 pada $3 kah?',
'undelete-show-file-submit'    => 'Iya-ai',

# Namespace form on various pages
'namespace'                     => 'Ngaran-kamar:',
'invert'                        => 'Bulikakan pilihan',
'tooltip-invert'                => 'Pariksa kutak ngini hagan manyungkupakan parubahan tutungkaran dalam ngaran-kamar tapilih (wan ngaran-kamar tarait anub dipariksa)',
'namespace_association'         => 'Ngaran-kamat tarait',
'tooltip-namespace_association' => 'Pariksa kutak ngini hagan maumpatakan jua ngarn-kamar pamandiran atawa judul tarait awan ngaran-kamar tapilih',
'blanknamespace'                => '(Tatambaian)',

# Contributions
'contributions'       => 'Sumbangan pamakai',
'contributions-title' => 'Sumbangan pamakai gasan $1',
'mycontris'           => 'Sumbangan ulun',
'contribsub2'         => 'Gasan $1 ($2)',
'nocontribs'          => 'Kadada parubahan taugai parsis awan karitaria ngini.',
'uctop'               => ' (atas)',
'month'               => 'Matan bulan (wan sabalumnya):',
'year'                => 'Matan tahun (wan sabalumnya):',

'sp-contributions-newbies'             => 'Tampaiakan sumbangan papamakai hanyar haja',
'sp-contributions-newbies-sub'         => 'Gasan akun hanyar',
'sp-contributions-newbies-title'       => 'Sumbangan pamuruk gasan akun hanyar',
'sp-contributions-blocklog'            => 'Log blukir',
'sp-contributions-deleted'             => 'Tahapus sumbangan pamuruk',
'sp-contributions-uploads'             => 'hunggahan',
'sp-contributions-logs'                => 'log',
'sp-contributions-talk'                => 'pandir',
'sp-contributions-userrights'          => 'pangalulaan hak-hak pamuruk',
'sp-contributions-blocked-notice'      => 'Pamuruk ngini parhatan diblukir.
Log blukir pahabisannya tasadia di bawah ni gasan rujukan:',
'sp-contributions-blocked-notice-anon' => 'Alamat IP ngini parhatan ini diblukir.
Log blukir pahabisannya tasadia di bawah ngini gasan rujukan:',
'sp-contributions-search'              => 'Gagai gasan sumbangan',
'sp-contributions-username'            => 'Alamat IP atawa ngaran-pamakai:',
'sp-contributions-toponly'             => 'Tampaiakan hanya ralatan tauncit',
'sp-contributions-submit'              => 'Gagai',

# What links here
'whatlinkshere'            => 'Tautan apa di sia',
'whatlinkshere-title'      => "Tungkaran-tungkaran nang batautan ka ''$1''",
'whatlinkshere-page'       => 'Tungkaran:',
'linkshere'                => "Tungkaran-tungkaran barikut batautan ka '''[[:$1]]''':",
'nolinkshere'              => "Kadada tutungkaran tataut ka '''[[:$1]]'''.",
'nolinkshere-ns'           => "Kadada tutungkaran tataut ka '''[[:$1]]''' dalam ngaran-kamar nang dipilih.",
'isredirect'               => 'tungkaran paugahan',
'istemplate'               => 'transklusi',
'isimage'                  => 'tautan barakas',
'whatlinkshere-prev'       => '$1 {{PLURAL:$1|sabalumnya|sabalumnya}}',
'whatlinkshere-next'       => '{{PLURAL:$1|dudi|dudi $1}}',
'whatlinkshere-links'      => '← tautan',
'whatlinkshere-hideredirs' => '$1 paugahan',
'whatlinkshere-hidetrans'  => '$1 transklusi',
'whatlinkshere-hidelinks'  => '$1 tautan',
'whatlinkshere-hideimages' => '$1 tautan pancitraan',
'whatlinkshere-filters'    => 'Saringan',

# Block/unblock
'autoblockid'                     => 'Blukir utumatis #$1',
'block'                           => 'Blukir pamuruk',
'unblock'                         => 'Lapas blukir pamuruk',
'blockip'                         => 'Blukir pamuruk',
'blockip-title'                   => 'Blukir pamuruk',
'blockip-legend'                  => 'Blukir pamuruk',
'blockiptext'                     => 'Puruk purmulir di bawah hagan mamblukir hak ungkai manulis matan sabuah alamat IP atawa ngaran-pamuruk.
Ngini dipuruk hagan mancagah vandalisma haja, wan sasuai awan [[{{MediaWiki:Policy-url}}|kabijakan]].
Isi sabuah alasan khas di bawah (gasan cuntuh, manulisakan tutungkaran nang suah divandal)',
'ipadressorusername'              => 'Alamat IP atawa ngaran pamuruk:',
'ipbexpiry'                       => 'Kadaluwarsa:',
'ipbreason'                       => 'Alasan:',
'ipbreasonotherlist'              => 'Alasan nang lain',
'ipbreason-dropdown'              => '*Alasan awam pamblukiran
** Mambuati panjalasan salah
** Mambuang isi matan tutungkaran
** Spam tautan ka luar
** Mambuati pandiran kusung/ratik ka tutungkaran
** Parilaku palecehan/intimidasi
** Panyalahpurukan akun banyak
** Ngaran-pamuruk kada-kawa-ditarima',
'ipb-hardblock'                   => 'Cagah pamuruk tadaptar gasan mambabak matan alamat IP ngini',
'ipbcreateaccount'                => 'Tangkal paulahan akun',
'ipbemailban'                     => 'Tangkal pamuruk mangirimi suril',
'ipbenableautoblock'              => 'Utumatis blukir alamat IP tauncit dipuruk ulih pamuruk ngini, wan sabarataan aalamat IP nang cuba dipuruk matan',
'ipbsubmit'                       => 'Blukir pamuruk ngini',
'ipbother'                        => 'Wayah lain:',
'ipboptions'                      => '2 jam:2 hours,1 hari:1 day,3 hari:3 days,1 minggu:1 week,2 minggu:2 weeks,1 bulan:1 month,3 bulan:3 months,6 bulan:6 months,1 tahun:1 year,salawasan:infinite',
'ipbotheroption'                  => 'lainnya',
'ipbotherreason'                  => 'Alasan lain/tambahan:',
'ipbhidename'                     => 'Sungkupakan ngaranpamuruk matan babakan wan dadaptar',
'ipbwatchuser'                    => 'Itihi tutungkaran pamuruk wan pamandiran pamuruk ngini',
'ipb-disableusertalk'             => 'Tangkal pamuruk ngini mambabak tungkaran pamandirannya wayah diblukir',
'ipb-change-block'                => 'Blukir pulang pamuruk lawan setelan ngingini',
'ipb-confirm'                     => 'Yakinakan blukir',
'badipaddress'                    => 'Alamat IP kada sah',
'blockipsuccesssub'               => 'Pamblukiran ruhui',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] hudah diblukir.<br />
Janaki [[Special:BlockList|daptar dibluk]] hagan maniring-pulang blukir.',
'ipb-blockingself'                => 'Pian pasal mamblukir Pian surang! Bujurkah Pian mahandaki ngitu?',
'ipb-confirmhideuser'             => 'Pian pasal mamblukir saurang pamuruk awan "sungkupakan pamuruk" di-kawa-akan. Ngini akan manikin/kadada ngaran pamuruk dalam samunyaan daptar wan log masukan. Pian yakin kah handak manggawi ngitu?',
'ipb-edit-dropdown'               => 'Aalasan pamblukiran babakan',
'ipb-unblock-addr'                => 'Mahilangakan blukir $1',
'ipb-unblock'                     => 'Lapas blukir sabuah ngaranpamuruk atawa alamat IP',
'ipb-blocklist'                   => 'Tiringi blukir nang ada',
'ipb-blocklist-contribs'          => 'Sumbangan gasan $1',
'unblockip'                       => 'Lapas blukir pamuruk',
'unblockiptext'                   => 'Puruk purmulir di bawah hagan manyimpan-pulang hak ungkai manulai sabuah alamat IP atawa ngaran-pamuruk nang sabalumnya diblukir.',
'ipusubmit'                       => 'Buang blukir ngini',
'unblocked'                       => '[[User:$1|$1]] sudah dicabut blukirnya',
'unblocked-range'                 => '$1 sudah dilapas blukirnya',
'unblocked-id'                    => 'Blukir $1 sudah dibuang',
'blocklist'                       => 'Pamuruk tablukir',
'ipblocklist'                     => 'Pamakai tablukir',
'ipblocklist-legend'              => 'Ugai saurang pamuruk tablukir',
'blocklist-userblocks'            => 'Sungkupakan pamblukiran akun',
'blocklist-tempblocks'            => 'Sungkupakan pamblukiran samantara',
'blocklist-addressblocks'         => 'Sungkupakan pamblukiran asa IP',
'blocklist-rangeblocks'           => 'Sungkupakan wilayah blukir',
'blocklist-timestamp'             => 'Capwayah',
'blocklist-target'                => 'Tuju',
'blocklist-expiry'                => 'Kadaluwarsa',
'blocklist-by'                    => 'Pambakal pamblukir',
'blocklist-params'                => 'Takaran pamblukiran',
'blocklist-reason'                => 'Alasan',
'ipblocklist-submit'              => 'Gagai',
'ipblocklist-localblock'          => 'Blukir lokal',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|pamblukiran|papamblukiran}} lain',
'infiniteblock'                   => 'Kada bawatas',
'expiringblock'                   => 'kadaluwarsa pada $1, $2',
'anononlyblock'                   => 'kadabangaran haja',
'noautoblockblock'                => 'pamblukiran utumatis dipajahakan',
'createaccountblock'              => 'paulahan akun diblukir',
'emailblock'                      => 'suril diblukir',
'blocklist-nousertalk'            => 'kada kawa mambabak tungkaran pamandiran surang',
'ipblocklist-empty'               => 'Daptar pamblukiran kusung.',
'ipblocklist-no-results'          => 'Alamat IP nang diminta atawa ngaran pamuruk kada diblukir.',
'blocklink'                       => 'blukir',
'unblocklink'                     => 'hilangakan blukir',
'change-blocklink'                => 'ubah blukir',
'contribslink'                    => 'sumbangan',
'emaillink'                       => 'kirim suril',
'autoblocker'                     => 'Utumatis blukir karana alamat IP Pian hahanyar ni dipuruk ulih "[[User:$1|$1]]".
Alasan nang dibari gasan pamblukiran $1 adalah: "$2"',
'blocklogpage'                    => 'Log blukir',
'blocklog-showlog'                => 'Pamuruk ngini diblukir sabalumnya.
Log blukir disadiakan di bawah gasan rujukan:',
'blocklog-showsuppresslog'        => 'Pamuruk ngini diblukir wan disungkupakan sabalumnya.
Log panikinan disadiakan di bawah gasan rujukan:',
'blocklogentry'                   => 'mamblukir [[$1]] sampai wayah $2 $3',
'reblock-logentry'                => 'setelan blukir diubah gasan [[$1]] awan sabuah wayah kadaluarsa $2 $3',
'blocklogtext'                    => 'Ngini adalah log matan blukir wan lapas-blukir pamuruk.
Blukir alamat IP utumatis kada tadaptar.
Janaki [[Special:BlockList|daptar diblukir]] gasan daptar uprasi dibabat wan pamblukiran pahanyarnya.',
'unblocklogentry'                 => 'Mahilangakan blukir "$1"',
'block-log-flags-anononly'        => 'papamuruk kada bangaran haja',
'block-log-flags-nocreate'        => 'Paulahan akun dipajahakan',
'block-log-flags-noautoblock'     => 'pamblukiran utumatis dipajahakan',
'block-log-flags-noemail'         => 'suril diblukir',
'block-log-flags-nousertalk'      => 'kada kawa mambabak tungkaran pamandiran surang',
'block-log-flags-angry-autoblock' => 'paningkatan utumatis-blukir dikawa-akan',
'block-log-flags-hiddenname'      => 'ngaran-pamuruk tasungkup',
'range_block_disabled'            => 'Ka-kawa-an pambakal hagan maulah blukir wilayah dikada-kawakan.',
'ipb_expiry_invalid'              => 'Wayah kadaluwarsa kada sah.',
'ipb_expiry_temp'                 => 'Pamblukiran ngaran-pamuruk tasungkup musti tatap.',
'ipb_hide_invalid'                => 'Kada kawa manikin akun ngini; ngini pinanya baisi banyak banar babakan.',
'ipb_already_blocked'             => '"$1" sudah diblukir',
'ipb-needreblock'                 => '$1 sudah diblukir. Pian handakkah maubah setelan ngini?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|pamblukiran|papamblukiran}} lain',
'unblock-hideuser'                => 'Pian kada kawa malapas blukir  pamuruk ngini, karana ngaran pamuruknya sudah disungkupakan.',
'ipb_cant_unblock'                => 'Kasalahan: ID tablukir $1 kada ta-ugai. Ngini pinanya sudah dilapas-blukirnya.',
'ipb_blocked_as_range'            => 'Kasalahan: Alamat IP $1 kada diblukir langsung wan kada kawa dilaps-blukirnya.
Ngini, kayapa pun, diblukir sawagai palihan wilayah $2, nang kawa-ai dilapas-blukirnya.',
'ip_range_invalid'                => 'Jarak IP kada sah.',
'ip_range_toolarge'               => 'Jarak blukir taganal pada /$1 kada dibulihakan.',
'blockme'                         => 'Blokir ulun',
'proxyblocker'                    => 'Pamblukir pruksi',
'proxyblocker-disabled'           => 'Pungsi ngini dipajahakan.',
'proxyblockreason'                => 'Alamat IP Pian diblukir karana ngini sabuah pruksi tabuka.
Muhun hubungi Panyadia Layan Internet Pian atawa sukungan tiknik wan padahi sidin pasal masalah ka-amanan sarius ngini.',
'proxyblocksuccess'               => 'Sudah.',
'sorbsreason'                     => 'Alamat IP Pian tadaptar sawagai pruksi tabuka dalam DNSBL dipuruk ulih {{SITENAME}}.',
'sorbs_create_account_reason'     => 'Alamat IP Pian tadaptar sawagai pruksi tabuka dalam DNSBL dipuruk ulih {{SITENAME}}.
Pian kada kawa maulah sabuah akun',
'cant-block-while-blocked'        => 'Pian kada kawa mamblukir pamuruk lain parhatan Pian diblukir.',
'cant-see-hidden-user'            => 'Pamuruk nang Pian cuba blukir sudah diblukir wan tasungkup.
Tumatan Pian kada baisi hak mnyungkupakan pamuruk, Pian kada kawa manjanaki atawa mambabak pamblukiran pamuruk.',
'ipbblocked'                      => 'Pian kada kawa mamblukir atau malapas blukir pamuruk lain, karana Pian surang diblukir',
'ipbnounblockself'                => 'Pian kada dibulihakan malapas blukir Pian surang',

# Developer tools
'lockdb'              => 'Sunduk basisdata',
'unlockdb'            => 'Lapas sunduk basisdata',
'lockdbtext'          => 'Manyunduk data basis akan mamandakakan kakawa-an samunyaan pamuruk mambabak tutungkaran, maubah kakatujuan sidin, mambabak paitihin sidin, wan nang lainnya nang parlu maubah dalam data basis.
Muhun yakinakan nang ngini bujur nang handak Pian gawi, wan Pian akan malapas-sunduk data basis amun paharaguan Pian tuntung.',
'unlockdbtext'        => 'Malapas-sunduk data basis akan manyimpan-pulang kakawa-an samunyaan pamuruk hagan mambabak tutungkaran, maubah kakatujuan sidin, mambabak paitihan sidin, wan nang lainnya nang parlu maubah dalam data basis.
Muhun yakinakan nang ngini nang Pian handak gawi.',
'lockconfirm'         => "I'ih, ulun bujuran handak manyunduk basisdata.",
'unlockconfirm'       => "I'ih, ulun bujuran handak malapas sunduk basisdata.",
'lockbtn'             => 'Sunduk basisdata',
'unlockbtn'           => 'Lapas sunduk basisdata',
'locknoconfirm'       => 'Pian kada mamariksa kutak payakinan.',
'lockdbsuccesssub'    => 'Basisdata ruhui disunduk',
'unlockdbsuccesssub'  => 'Sunduk basisdata dibuang',
'lockdbsuccesstext'   => 'Data basis sudah disunduk.<br />
Ingatakan hagan [[Special:UnlockDB|malapas sunduk]] habis paharaguan Pian tuntung.',
'unlockdbsuccesstext' => 'Basisdata sudah dilapas sunduknya.',
'lockfilenotwritable' => 'Barakas data basis disunduk kada kawa ditulisi.
Hagan manyunduk atawa malapas-sunduk data basis, parlu dkawa-akan manulis ulih web server.',
'databasenotlocked'   => 'Data basis kada basunduk.',
'lockedbyandtime'     => '(ulih {{GENDER:$1|$1}} pada $2, $3)',

# Move page
'move-page'                    => 'Pindahakan $1',
'move-page-legend'             => 'Pindahakan tungkaran',
'movepagetext'                 => "Mamuruk purmulir di bawah akan mangganti ngaran sabuah tungkaran, mamindahakan samunyaan halam ka ngaran nang hanyar. Judul lawas akan jadi sabuah tungkaran paugahan ka judul hanyar. Pian kawa mahanyari bahwasanya paugahan-paugahan manuju ka judul nang samustinya langsung. Amun kada, pastiakan pariksa gasan [[Special:DoubleRedirects|ganda]] atawa [[Special:BrokenRedirects|paugahan pagat]]. Pian batanggung jawab gasan mamastiakan tautan-tautan tatarusan manuju ka mana nang samustinya.

Catatan bahwasanya tungkaran '''kada''' akan tapindah amun sudah ada tungkaran nang bangaran hanyar ngitu, kacuali amun tungkaran itu puang atawa sabuah paugahan wan kadada halam babakan.

'''Paringatan!'''
Ini kawa maakibatakan parubahan kada taduga wan drastis gasan sabuah tungkaran rami; muhun mamastiakan Pian paham akibatnya sabalum manarusakan.",
'movepagetext-noredirectfixer' => "Mamuruk purmulir di bawah akan mangganti ngaran sabuah tungkaran, mamindahakan samunyaan halam ka ngaran nang hanyar.
Judul lawas akan jadi sabuah tungkaran paugahan ka judul hanyar.
Pastiakan pariksa gasan [[Special:DoubleRedirects|ganda]] atawa [[Special:BrokenRedirects|paugahan pagat]].
Pian batanggung jawab gasan mamastiakan tautan-tautan tatarusan manuju ka mana nang samustinya.

Catatan bahwasanya tungkaran '''kada''' akan tapindah amun sudah ada tungkaran nang bangaran hanyar ngitu, kacuali amun tungkaran itu puang atawa sabuah paugahan wan kadada halam babakan.

'''Paringatan!'''
Ini kawa maakibatakan parubahan kada taduga wan drastis gasan sabuah tungkaran rami; 
muhun mamastiakan Pian paham akibatnya sabalum manarusakan.",
'movepagetalktext'             => "Tungkaran pamandiran tarait akan langsung dipindahakan baimbai wan ini '''kacuali amun:'''
*Sabuah tungkaran pamandiran nang kada puang sudah baisi awan judul hanyar, atawa
*Pian kada manyuntring kutak di bawah.",
'movearticle'                  => 'Pindahakan tungkaran:',
'moveuserpage-warning'         => "'''Paringatan:''' Pian pasal mamindahakan sabuah tungkaran pamuruk. Muhun catat tungkaran ngitu haja nang dipindah wan pamuruknya gin akan ''kada'' dingarani-pulang.",
'movenologin'                  => 'Baluman babuat log',
'movenologintext'              => 'Pian musti saurang pamuruk tadaptar wan [[Special:UserLogin|babuat log]] hagan mamindahakan sabuah tungkaran.',
'movenotallowed'               => 'Pian kada baisi ijin hagan mamindahakan tutungkaran.',
'movenotallowedfile'           => 'Pian kada baisi ijin hagan mamindahakan babarakas.',
'cant-move-user-page'          => 'Pian kada baisi ijin hagan mamindahakan tutungkaran pamuruk (hagian matan sub-tutungkaran).',
'cant-move-to-user-page'       => 'Pian kada baisi ijin hagan mamindahakan tutungkaran pamuruk (kacuali hagan sabuah sub-tutungkaran pamuruk).',
'newtitle'                     => 'Ka judul hanyar:',
'move-watch'                   => 'Itihi tungkaran asal mula wan tungkaran tujuan',
'movepagebtn'                  => 'Pindahakan tungkaran',
'pagemovedsub'                 => 'Pamindahan ruhui',
'movepage-moved'               => '\'\'\'"$1" sudah dipindahakan ka "$2"\'\'\'',
'movepage-moved-redirect'      => 'Tungkaran paugahan sudah diulah.',
'movepage-moved-noredirect'    => 'Paulahan sabuah paugahan ditikin.',
'articleexists'                => 'Tungkaran lawan ngaran itu sudah ada atawa ngaran nang dipilih kada sah. Silakan pilih ngaran lain.',
'cantmove-titleprotected'      => 'Pian kada kawa mamindahakan sabuah tungkaran ka lukasi ngini, karana sabuah judul hanyar dilindungi gasan diulah.',
'talkexists'                   => "'''Tungkaran itu sudah ruhui dipindahakan, tapi tungkaran pamandirannya kada kawa tapindah karana sudah ada tungkaran pamandiran bajudul hanyar. Muhun gabungakan manual haja tungkaran-tungkaran itu.'''",
'movedto'                      => 'dipindahakan ka',
'movetalk'                     => 'Pindahakan tungkaran pamandiran nang tarait',
'move-subpages'                => 'Pindahakan sub-tutungkaran (sampai $1)',
'move-talk-subpages'           => 'Pindahakan sub-tutungkaran matan tungkaran pamandiran (sampai $1)',
'movepage-page-exists'         => 'Tungkaran $1 sudah ada wan kada kawa utumatis ditindih.',
'movepage-page-moved'          => 'Tungkaran $1 sudah dipindahakan ka $2.',
'movepage-page-unmoved'        => 'Tungkaran $1 kada kawa dipindahakan ka $2.',
'movepage-max-pages'           => 'Maksimal $1 {{PLURAL:$1|tungkaran|tutungkaran}} sudah dipindahakan wan kada kawa lagi dipindahakan utumatis.',
'movelogpage'                  => 'Log pamindahan',
'movelogpagetext'              => 'Di bawah ngini adalah sabuah daptar matan samunyaan pamindahan tungkaran.',
'movesubpage'                  => '{{PLURAL:$1|Subtungkaran|Subtutungkaran}}',
'movesubpagetext'              => 'Tungkaran ngini baisi $1 {{PLURAL:$1|subtungkaran|subtutungkaran}} ditampaiakan di bawah.',
'movenosubpage'                => 'Tungkaran ngini kada baisi subtutungkaran.',
'movereason'                   => 'Alasan:',
'revertmove'                   => 'bulikakan',
'delete_and_move'              => 'Hapus wan pindahakan',
'delete_and_move_text'         => '==pahapusan diparluakan==
Tungkaran tatuju"[[:$1]]" sadauh tasadia.
Pian handakkah hagan mahapus ngini maulah jalan gasan pamindahan?',
'delete_and_move_confirm'      => "I'ih, hapus tungkaran ngini",
'delete_and_move_reason'       => 'Dihapus hagan mangantisipasiakan pamindahan tungkaran matan "[[$1]]"',
'selfmove'                     => 'Asal mula wan tujuan bajudul sama;
kada kawa mamindah sabuah tungkaran ka tungkaran ngitu jua.',
'immobile-source-namespace'    => 'Kada kawa mamindahakan tutungkaran pada ngarankamar "$1"',
'immobile-target-namespace'    => 'Kada kawa mamindahakan tutungkaran ka ngarankamar "$1"',
'immobile-target-namespace-iw' => 'Tautan interwiki adalah lainan sabuah tujuan sah gasan mamindahakan tungkaran.',
'immobile-source-page'         => 'Tungkaran ngini kada kawa dipindahakan.',
'immobile-target-page'         => 'Kada kawa mamindahakan ka judul tujuan ngitu.',
'imagenocrossnamespace'        => 'Kada kawa mamindahakan barakas ka ngaran-kamar lainan-barakas.',
'nonfile-cannot-move-to-file'  => 'Kada kawa mamindahakan lainan-barakas ka ngaran-kamar barakas',
'imagetypemismatch'            => 'Ekstensi barakas hanyar kada cucuk lawa macamnya.',
'imageinvalidfilename'         => 'Ngaran barakas tujuan kada sah',
'fix-double-redirects'         => 'Mutakhirakan babarapa paugahan nang manitik ka judul asli',
'move-leave-redirect'          => 'Ulah paugahan ka judul hanyar',
'protectedpagemovewarning'     => "'''Paringatan''': Tungkaran ngini sudah dilindungi laluai pamuruk awan hak istimiwa pambakal haja nang kawa mamindahakan ngini.
Log masuk pauncitan disadiakan di bawah gasan rujukan:",
'semiprotectedpagemovewarning' => "'''Catatan:''' Tungkaran ngini sudah dilindungi laluai pamuruk tadaptar haja nang kawa mamindahakan ngini.
Log masuk pauncitan disadiakan di bawah gasan rujukan:",
'move-over-sharedrepo'         => '==Barakas ada==
[[:$1]] ada pintangan panyimpanan babagi. Mamindahakan sabuah barakas ka judul ngini akan manulis-tindih barakas babagi.',
'file-exists-sharedrepo'       => 'Ngaran barakas nang dipilih sudah dipuruk pintangan panyimpanan babagi.
Muhun pilih ngaran lain.',

# Export
'export'            => 'Kirimi tungkaran ka luar',
'exporttext'        => 'Pian kawa ma-ikspur naskah wan halam babakan matan sabuah tungkaran tartantu atawa sarangkai tutungkaran tabungkus dalam bantuk XML.
Ngini kawa di-impur dalam wiki lain mamuruk MediaWiki lung [[Special:Import|tungkaran impur]].

Hagan ma-ikspur tutungkaran, buati judul dalam kutak naskah di bawah, asa judul par garis, wan pilihi nang mana Pian handak ralatan tadamini nangkaitu jua samunyaan raralatan lawas, awan garis tungkaran halam, atawa ralatan tadamini awan panjalasan pasal babakan ta-uncit.

Dalam kasus tahanyar Pian kawa jua mamuruk sabuah tautanm gasan cuntuh [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] gasan tungkaran "[[{{MediaWiki:Mainpage}}]]".',
'exportall'         => 'Ekspor samunyaan tungkaran.',
'exportcuronly'     => 'Tamasuk ralatan tadamini haja, kada sahibakan halam',
'exportnohistory'   => "----
''Catatan:''' Ma-ikspur sahibakan halam matan tutungkaran lung purmulir ngini sudah dipajahakan lawan alasan ginawi.",
'exportlistauthors' => 'Tarmasuk sabuah daptar hibak panyumbang gasan tiap tungkaran',
'export-submit'     => 'Pangaluar',
'export-addcattext' => 'Tambahi tutungkaran matan tumbung:',
'export-addcat'     => 'Tambahi',
'export-addnstext'  => 'Tambahi tutungkaran matan ngaran-kamar:',
'export-addns'      => 'Tambahi',
'export-download'   => 'Simpan sawagai barakas',
'export-templates'  => 'Tamasuk cicitakan',
'export-pagelinks'  => 'Tamasuk tutungkaran tataut sampai kadalaman:',

# Namespace 8 related
'allmessages'                   => ' Sistim papasanan',
'allmessagesname'               => 'Ngaran',
'allmessagesdefault'            => 'Naskah baku pasan',
'allmessagescurrent'            => 'Naskah pasan wayahini.',
'allmessagestext'               => 'Ngini adalah sabuah daptar pasan sistem tasadia dalam ngaran-kamar MediaWiki.
Muhun ilangi [//www.mediawiki.org/wiki/Localisation MediaWiki Localisation] wan [//translatewiki.net translatewiki.net] amun Pian hakun manyumbang palukalan ganarik MediaWiki.',
'allmessagesnotsupportedDB'     => "Tungkaran ngini kada kawa dipuruk karana '''\$wgUseDatabaseMessages''' sudah dipajahakan.",
'allmessages-filter-legend'     => 'Saringan',
'allmessages-filter'            => 'Saringan lawan kaadaan kustom:',
'allmessages-filter-unmodified' => 'Kada digaganti',
'allmessages-filter-all'        => 'Samunyaan',
'allmessages-filter-modified'   => 'Digaganti',
'allmessages-prefix'            => 'Sarinngan lawan mulaan:',
'allmessages-language'          => 'Bahasa:',
'allmessages-filter-submit'     => 'Tulak',

# Thumbnails
'thumbnail-more'           => 'Ganali',
'filemissing'              => 'Barakas hilang',
'thumbnail_error'          => 'Kasalahan maulah thumbnail: $1',
'djvu_page_error'          => 'Tungkaran DJVu di luar jarak',
'djvu_no_xml'              => 'Kada kawa kulihan XML gasan barakas DJVu',
'thumbnail-temp-create'    => 'Kada kawa maulah barakas kuku-umajari samantara',
'thumbnail-dest-create'    => 'Kada kawa manyimpan kuku-umajari ka tujuan',
'thumbnail_invalid_params' => 'Takaran thumbnail kada sah',
'thumbnail_dest_directory' => 'Kada kawa maulah direktori tujuan',
'thumbnail_image-type'     => 'Macam pancitraan kada disukung',
'thumbnail_gd-library'     => 'Kunpigurasi parpustakaan GD kada tuntung: pungsi $1 hilang',
'thumbnail_image-missing'  => 'Barakas janakannya hilang: $1',

# Special:Import
'import'                     => 'Pamasuk tungkaran',
'importinterwiki'            => 'Impur transwiki',
'import-interwiki-text'      => 'Pilihi sabuah wiki wan judul tungkaran hagan di-impur.
Tanggal raralatan wan ngaran pambabak akan di partahanakan.
Samunyaan gawi impur transwiki akan dicatat pada [[Special:Log/import|log impur]].',
'import-interwiki-source'    => 'Wiki/tungkaran asal mula:',
'import-interwiki-history'   => 'Salin sabarataan halam raralatan gasan tungkaran ngini',
'import-interwiki-templates' => 'Tamasuk samunyaan cicitakan',
'import-interwiki-submit'    => 'Impur',
'import-interwiki-namespace' => 'Ngaran-kamar tujuan:',
'import-upload-filename'     => 'Ngaran barakas:',
'import-comment'             => 'Kumintar:',
'importtext'                 => 'Muhun ma-ikspur tungkaran matan asal mula wiki mamuruk [[Special:Export|sarana ikspur]].
Simpan ngini dalam komputar Pian wan hunggah di sia.',
'importstart'                => 'Mangimpur tutungkaran...',
'import-revision-count'      => '$1 {{PLURAL:$1|ralatan|raralatan}}',
'importnopages'              => 'Kadada tutungkaran hagan diimpur.',
'imported-log-entries'       => 'Ta-impur $1 {{PLURAL:$1|log masukan|log mamasukan}}.',
'importfailed'               => 'Impur gagal: <nowiki>$1</nowiki>',
'importunknownsource'        => 'Macam asal mula impur kada ditahui',
'importcantopen'             => 'Kada kawa mambuka barakas impur',
'importbadinterwiki'         => 'Tautan interwiki buruk',
'importnotext'               => 'Kusung atawa kadada naskah',
'importsuccess'              => 'Impur tuntung!',
'importhistoryconflict'      => 'Halam ralatan nang ada bacakut (pina suah diimpur tungkaran ngini sabalumnya)',
'importnosources'            => 'Kadada asal mula traswiki impur nang diulah wan halam hunggahan langsung dipajahakan.',
'importnofile'               => 'Kadada barakas impur tahunggah.',
'importuploaderrorsize'      => 'Hunggahan barakas impur gagal.
Barakas ngini kaganalan pada takaran hunggahan nang dibulihakan.',
'importuploaderrorpartial'   => 'Hunggahan barakas impur gagal.
Barakas ngini tahunggah sapalih haja.',
'importuploaderrortemp'      => 'Hunggahan barakas impur gagal.
Sabuah puldar samantara hilang.',
'import-parse-failure'       => 'Kagagalan prusis impur XML',
'import-noarticle'           => 'Kadada tungkaran hagan diimpur!',
'import-nonewrevisions'      => 'Sabarataan raralatan suah diimpur sabalumnya.',
'xml-error-string'           => '$1 pintang baris $2, kolom $3 (bita $4): $5',
'import-upload'              => 'Hunggah data XML',
'import-token-mismatch'      => 'Kahilangan sesi data.
Muhun cubai pulang.',
'import-invalid-interwiki'   => 'Kada kawa maimpur matan wiki nang diajuakan.',
'import-error-edit'          => 'Tungkaran "$1" kada diumpur karana Pian kada bulih mambabak ngini.',
'import-error-create'        => 'Tungkaran "$1" kada diumpur karana Pian kada bulih maulah ngini.',

# Import log
'importlogpage'                    => 'Log impur',
'importlogpagetext'                => 'Impur administratip matan tutungkaran awan babakan halam matan wiwiki lain.',
'import-logentry-upload'           => '[[$1]] diimpur lung hunggah barakas',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|ralatan|raralatan}}',
'import-logentry-interwiki'        => 'ditranswiki $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|ralatan|raralatan}} matan $2',

# JavaScriptTest
'javascripttest'                => 'Mantis JavaScript',
'javascripttest-pagetext-skins' => 'Pilih kulit nang cagar Pian cubai:',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Tungkaran pamakai Pian',
'tooltip-pt-anonuserpage'         => 'Tungkaran pamuruk matan alamat IP Pian mambabak sawagai',
'tooltip-pt-mytalk'               => 'Tungkaran pamandiran Pian',
'tooltip-pt-anontalk'             => 'Pamandiran pasal bababakan matan alamat IP ngini',
'tooltip-pt-preferences'          => 'Nang Pian katuju',
'tooltip-pt-watchlist'            => 'Daptar tungkaran-tungkaran nang Pian itihi parubahannya',
'tooltip-pt-mycontris'            => 'Daptar sumbangan Pian',
'tooltip-pt-login'                => 'Pian sabaiknya babuat ka dalam log; tagal ngini kada kawajiban pang',
'tooltip-pt-anonlogin'            => 'Pian sabaiknya babuat ka dalam log; tagal ini kada kawajiban pang',
'tooltip-pt-logout'               => 'Kaluar',
'tooltip-ca-talk'                 => 'Pamandiran pasal isi tungkaran',
'tooltip-ca-edit'                 => 'Pian kawa mambabak tungkaran ngini. Tabéngkéng amun mamakai picikan titilikan sabalum manyimpan',
'tooltip-ca-addsection'           => 'Mulai hagian hanyar',
'tooltip-ca-viewsource'           => 'Tungkaran ngini dilindungi. Pian kawa maniring asal mulanya.',
'tooltip-ca-history'              => 'Raralatan bahari tungkaran ngini',
'tooltip-ca-protect'              => 'Lindungi tungkaran ini',
'tooltip-ca-unprotect'            => 'Ganti parlindungan tungkaran ngini',
'tooltip-ca-delete'               => 'Hapus tungkaran ini',
'tooltip-ca-undelete'             => 'Bulikakan babakan ka tungkaran ini sabalum tungkaran ini dihapus',
'tooltip-ca-move'                 => 'Pindahakan tungkaran ngini',
'tooltip-ca-watch'                => 'Tambahi tungkaran ngini ka daptar itihan Pian',
'tooltip-ca-unwatch'              => 'Buang tungkaran ngini matan daptar itihan Pian',
'tooltip-search'                  => 'Gagai {{SITENAME}}',
'tooltip-search-go'               => 'Tulak ka sabuah tungkaran bangaran sama munnya sudah ada',
'tooltip-search-fulltext'         => 'Gagai tungkaran nang baisi naskah nangkaya ngini',
'tooltip-p-logo'                  => 'Ilangi tungkaran tatambaian',
'tooltip-n-mainpage'              => 'Ilangi tungkaran tatambaian',
'tooltip-n-mainpage-description'  => 'Ilangi Tungkaran Tatambaian',
'tooltip-n-portal'                => 'Pasal rangka-gawian, apa nang kawa pian gawi, di mana maugai sasuatu',
'tooltip-n-currentevents'         => 'Gagai panjalasan prihal paristiwa damini',
'tooltip-n-recentchanges'         => 'Daptar paubahan pahanyarnya dalam wiki',
'tooltip-n-randompage'            => 'Tampaiakan sabuah babarang tungkaran',
'tooltip-n-help'                  => 'Wadah maugai patulung',
'tooltip-t-whatlinkshere'         => 'Daptar samunyaan tungkaran wiki nang ada tautan ka sia',
'tooltip-t-recentchangeslinked'   => 'Paubahan pahanyarnya dalam tutungkaran tataut matan tungkaran ngini',
'tooltip-feed-rss'                => 'Kitihan RSS gasan tungkaran ini',
'tooltip-feed-atom'               => 'Kitihan Atum gasan tungkaran ngini',
'tooltip-t-contributions'         => 'Sabuah daptar sumbangan pamakai ngini',
'tooltip-t-emailuser'             => 'Kirimi surel ka pamakai ini',
'tooltip-t-upload'                => 'Hunggahakan babarakas',
'tooltip-t-specialpages'          => 'Daptar samunyaan tungkaran istimiwa',
'tooltip-t-print'                 => 'Nang kawa dicitaknya tungkaran ngini',
'tooltip-t-permalink'             => 'Tautan tatap ka raralatan tungkaran ngini',
'tooltip-ca-nstab-main'           => 'Tiringi tungkaran isi',
'tooltip-ca-nstab-user'           => 'Tiring tungkaran pamakai',
'tooltip-ca-nstab-media'          => 'Tiringi tungkaran media',
'tooltip-ca-nstab-special'        => 'Nangini sabuah tungkaran istimiwa nang kada kawa dibabak.',
'tooltip-ca-nstab-project'        => 'Tiringi tungkaran rangka gawian',
'tooltip-ca-nstab-image'          => 'Tiringi barakas tungkaran',
'tooltip-ca-nstab-mediawiki'      => 'Tiring sistim pasan',
'tooltip-ca-nstab-template'       => 'Tiringi citakan',
'tooltip-ca-nstab-help'           => 'Tiringi tungkaran patulung',
'tooltip-ca-nstab-category'       => 'Lihati tungkaran tumbung',
'tooltip-minoredit'               => 'Tandai ini sabagai sabuah pambabakan sapalih',
'tooltip-save'                    => 'Simpan parubahan Pian',
'tooltip-preview'                 => 'Tilik parubahan Pian, muhun pakai ngini sabalum manyimpan!',
'tooltip-diff'                    => 'Tampaiakan nang apa parubahan nang Pian ulah',
'tooltip-compareselectedversions' => 'Lihati nang balain antara dua ralatan tungkaran tapilih ngini',
'tooltip-watch'                   => 'Tambahakan tungkaran ini ka daptar itihan Pian',
'tooltip-recreate'                => 'Ulah pulang tungkaran biar gin suah dihapus',
'tooltip-upload'                  => 'Mulai pangunggahan',
'tooltip-rollback'                => 'Bulikakan ka babakan-babakan tungkaran ngini matan panyumbang tauncit dalam sakali klik.',
'tooltip-undo'                    => 'Mamantukakan ralatan ngini wan mambuka kutak pambabakan lawan mode tilik. Alasan kawa ditambahakan di kutak kasimpulan.',
'tooltip-preferences-save'        => 'Simpan kakatujuan',
'tooltip-summary'                 => 'Buati sabuah kasimpulan handap',

# Metadata
'notacceptable' => 'Server wiki kada kawa manyadiakan data dalam sabuah purmat nang client Pian kawa baca.',

# Attribution
'anonymous'        => '{{PLURAL:$1|panuruk|papamuruk}} kada-bangaran {{SITENAME}}',
'siteuser'         => 'Pamuruk {{SITENAME}} $1',
'anonuser'         => 'Pamuruk kada bangaran {{SITENAME}} $1',
'lastmodifiedatby' => 'Tungkaran ngini tauncit diubah pada $1, $2 ulih $3',
'othercontribs'    => 'Dipandalakan pada gawian ulih $1.',
'others'           => 'lainnya',
'siteusers'        => '{{PLURAL:$2|pamuruk|papamuruk}} {{SITENAME}} $1',
'anonusers'        => '{{PLURAL:$2|pamuruk|papamuruk}} kada bangaran {{SITENAME}} $1',
'creditspage'      => 'Tungkaran kridit',
'nocredits'        => 'Kadada panjalasan kridit tasadia gasan tungkaran ngini.',

# Spam protection
'spamprotectiontitle' => 'Saringan pancagah spam',
'spamprotectiontext'  => 'Naskah nang handak Pian simpan diblukir ulih saringan spam.
Ngini pinanya dikaranakan ulih sabuah tautan ka sabuah situs daptar-hirang luar.',
'spamprotectionmatch' => 'Naskah barikut nang mamicu saringan spam kami: $1',
'spambot_username'    => 'Pambarasihan spam MediaWiki',
'spam_reverting'      => 'Mambulikakan ka ralatan tauncit nang kada mangandung tatautan ka $1',
'spam_blanking'       => 'Samunyaan raralatan mangandung tatautan ka $1, dikusungakan',

# Info page
'pageinfo-title'            => "Panjalasan gasan ''$1''",
'pageinfo-header-edits'     => 'Babakan',
'pageinfo-header-watchlist' => 'Paitihan',
'pageinfo-header-views'     => 'Titiringan',
'pageinfo-subjectpage'      => 'Tungkaran',
'pageinfo-talkpage'         => 'Tungkaran pamandiran',
'pageinfo-watchers'         => 'Jumlah papaitih',
'pageinfo-edits'            => 'Rikinan babakan',
'pageinfo-authors'          => 'Rikinan panulis balain',
'pageinfo-views'            => 'Rikinan titiringan',
'pageinfo-viewsperedit'     => 'Titiringan par babakan',

# Patrolling
'markaspatrolleddiff'                 => 'Ciri-i sawagai ta-awasi',
'markaspatrolledtext'                 => 'Ciri-i tungkaran ngini sawagai ta-awasi',
'markedaspatrolled'                   => 'taciri-i sawagai ta-awasi',
'markedaspatrolledtext'               => 'Ralatan tapilih matan [[:$1]] sudah diciri-i sawagai ta-awasi.',
'rcpatroldisabled'                    => 'Parubahan pangawasan tadamini dipajahakan.',
'rcpatroldisabledtext'                => 'Pitur parubahan pangawasan tadamini parhatan ni dipajahakan.',
'markedaspatrollederror'              => 'Kada kawa diciri-i sawagai ta-awasi',
'markedaspatrollederrortext'          => 'Pian parlu manantuakan sabuah ralatan hagan diciri-i sawagai ta-awasi.',
'markedaspatrollederror-noautopatrol' => 'Pian kada dibulihakan manyiri-i parubahan Pian surang sawagai ta-awasi.',

# Patrol log
'patrol-log-page'      => 'Log pa-awasan',
'patrol-log-header'    => 'Ngini adalah sabuah log matan raralatan nang ta-awasi.',
'log-show-hide-patrol' => '$1 log pa-awasan',

# Image deletion
'deletedrevision'                 => 'Raralatan lawas tahapus: $1',
'filedeleteerror-short'           => 'Kasalahan mahapus barakas: $1',
'filedeleteerror-long'            => 'Kasalahan tarikin parhatan mahapus barakas:

$1',
'filedelete-missing'              => 'Bakas "$1" kada kawa dihapus, karana ngini kadada.',
'filedelete-old-unregistered'     => 'Ralatan barakas nang diajuakan "$1" kadada dalam data basis.',
'filedelete-current-unregistered' => 'Barakas nang diajuakan "$1" kadada dalam data basis.',
'filedelete-archive-read-only'    => 'Direktori arkip "$1" kada kawa ditulisi ulih webserver.',

# Browsing diffs
'previousdiff' => '← Ralatan talawas',
'nextdiff'     => 'Ralatan tahanyar →',

# Media information
'mediawarning'           => "'''Paringatan''': Barakas ngini pinanya mangandung kudi babahaya.
Manarusakan ngini, kawa manyarang sistem Pian.",
'imagemaxsize'           => "Watas takaran gambar: <br />''(gasan barakas tutungkaran diskripsi)''",
'thumbsize'              => 'Ukuran kuku-uma-jari:',
'widthheightpage'        => '$1 × $2, $3 {{PLURAL:$3|tungkaran|tutungkaran}}',
'file-info'              => 'takaran barakas: $1, macam MIME: $2',
'file-info-size'         => '$1 × $2 piksel, ukuran barakas: $3, tipe MIME: $4',
'file-info-size-pages'   => '$1 × $2 piksal, takaran barakas: $3, macam MIME: $4, $5 {{PLURAL:$5|tungkaran|tutungkaran}}',
'file-nohires'           => 'Kadada tasadia resolusi tapancau.',
'svg-long-desc'          => 'Barakas SVG, nominal $1 × $2 piksel, basar barakas: $3',
'show-big-image'         => 'Ukuran hibak',
'show-big-image-preview' => 'Takaran tilikan ngini: $1.',
'show-big-image-other'   => '{{PLURAL:$2|Risulusi|Risulusi}} lain: $1.',
'show-big-image-size'    => '$1 × $2 piksal',
'file-info-gif-looped'   => 'mambulat',
'file-info-gif-frames'   => '$1 {{PLURAL:$1|pigura|pipigura}}',
'file-info-png-looped'   => 'mambulat',
'file-info-png-repeat'   => 'dimainakan $1 {{PLURAL:$1|kali|kakali}}',
'file-info-png-frames'   => '$1 {{PLURAL:$1|pigura|pipigura}}',

# Special:NewFiles
'newimages'             => 'Balai babarakas hanyar',
'imagelisttext'         => "Di bawah ngini adalah daptar '''$1''' {{PLURAL:$1|barakas|babarakas}} diurutakan $2.",
'newimages-summary'     => 'Tungkaran istimiwa ngini manampaikan babarakas nang hahanyar dihunggah.',
'newimages-legend'      => 'Saringan',
'newimages-label'       => 'Ngaran barakas (atawa sapalihnya):',
'showhidebots'          => '($1 bot)',
'noimages'              => 'Kadada nang dijanaki.',
'ilsubmit'              => 'Gagai',
'bydate'                => 'ulih tanggal',
'sp-newimages-showfrom' => 'Tampaiakan babarakas hanyar mulai matan $1, $2',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|$1 datik|$1 dadatik}}',
'minutes' => '{{PLURAL:$1|$1 manit|$1 mamanit}}',
'hours'   => '{{PLURAL:$1|$1 jam|$1 jam}}',
'days'    => '{{PLURAL:$1|$1 hari|$1 hahari}}',
'ago'     => '$1 lalu',

# Bad image list
'bad_image_list' => "Purmatnya nangkaya di bawah ni:

Daptar buting (baris bamula wan *) haja nang dipartimbangkan.
Tautan ta'asa dalam sabuah baris mustinya sabuah tautan ka barakas nang buruk.
Tautan-tautan abis tu pada baris sama dipartimbangkan sabagai pangacualian, nangkaya tungkaran-tungkaran di mana barakas itu ada.",

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => 'Barakas ngini mangandung panjalasan tambahan, mungkin ditambahakan ulih kudakan atawa paundai nang dipurukakan gasan maulah atawa digitalisasi barakas. Amun barakas ngini sudah diubah, parincian nang ada mungkin kada sapanuhnya sasuai lawan barakas nang diubah.',
'metadata-expand'   => 'Tampaiakan tambahan rincian',
'metadata-collapse' => 'Sungkupakan tambahan rincian',
'metadata-fields'   => 'Pancitraan metadata tadaptar dalam pasan ngini akan masuk dalam tungkaran pancitraan wayah tabel metadata tasungkup. Nang lainnya cagaran babaku tasungkup.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# EXIF tags
'exif-imagewidth'                  => 'Libar',
'exif-imagelength'                 => 'Pancau',
'exif-bitspersample'               => 'Bit par kumpunin',
'exif-compression'                 => 'Skima kumprasi',
'exif-photometricinterpretation'   => 'Kumpusisi piksal',
'exif-orientation'                 => 'Uriantasi',
'exif-samplesperpixel'             => 'Rikinan kumpunin',
'exif-planarconfiguration'         => 'Pa-aturan data',
'exif-ycbcrsubsampling'            => 'Rasiu sub-cuntuh matan Y ka C',
'exif-ycbcrpositioning'            => 'Pawadahan Y wan C',
'exif-xresolution'                 => 'Risulusi horisontal',
'exif-yresolution'                 => 'Risulusi pertikal',
'exif-stripoffsets'                => 'Data lukasi gambar',
'exif-rowsperstrip'                => 'Rikinan baris par strip',
'exif-stripbytecounts'             => 'Bita par strip kumprasi',
'exif-jpeginterchangeformat'       => 'Ofset ka JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bita matan data JPEG',
'exif-whitepoint'                  => 'Puin putih krumatisitas',
'exif-primarychromaticities'       => 'Krumatisitas matan warna primar',
'exif-ycbcrcoefficients'           => 'Kuipisian kamar kelir transpurmasi matriks',
'exif-referenceblackwhite'         => 'Nilai rujukan sapasang hirang wan putih',
'exif-datetime'                    => 'Tanggal wan wayah parubahan barakas',
'exif-imagedescription'            => 'Judul gambar',
'exif-make'                        => 'Pabrikan kudakan',
'exif-model'                       => 'Mudil kudakan',
'exif-software'                    => 'Parangkat lunak dipuruk',
'exif-artist'                      => 'Pa-ulah',
'exif-copyright'                   => 'Pamingkut hak-rekap',
'exif-exifversion'                 => 'Parsi Exif',
'exif-flashpixversion'             => 'Manyukung parsi Flashpix',
'exif-colorspace'                  => 'Kamar kalir',
'exif-componentsconfiguration'     => 'Arti matan tiap kumpunin',
'exif-compressedbitsperpixel'      => 'Muda kumprasi gambar',
'exif-pixelydimension'             => 'Lingai gambar',
'exif-pixelxdimension'             => 'Pancau gambar',
'exif-usercomment'                 => 'Kumintar pamuruk',
'exif-relatedsoundfile'            => 'Barkas suara bahubung',
'exif-datetimeoriginal'            => 'Tanggal wan wayah paulahan data',
'exif-datetimedigitized'           => 'Tanggal wan wayah digitalisasi',
'exif-subsectime'                  => 'DateTime sub-datik',
'exif-subsectimeoriginal'          => 'DateTimeOriginal sub-datik',
'exif-subsectimedigitized'         => 'DateTimeDigitized sub-datik',
'exif-exposuretime'                => 'Wayah paparan',
'exif-exposuretime-format'         => '$1 dat ($2)',
'exif-fnumber'                     => 'Rikinan F',
'exif-exposureprogram'             => 'Parugram Paparan',
'exif-spectralsensitivity'         => 'Sansitipitas spektral',
'exif-isospeedratings'             => 'Dabit kahancapan ISO',
'exif-shutterspeedvalue'           => 'Kahancapan rana APEX',
'exif-aperturevalue'               => 'Singkaian APEX',
'exif-brightnessvalue'             => 'Kacarahan APEX',
'exif-exposurebiasvalue'           => 'Bias paparan APEX',
'exif-maxaperturevalue'            => 'Singkaian maksimal tanah',
'exif-subjectdistance'             => 'Halat subjek',
'exif-meteringmode'                => 'Muda panakaran',
'exif-lightsource'                 => 'Asal mula sinar',
'exif-flash'                       => 'Kilat',
'exif-focallength'                 => 'Panjang linsa pukal',
'exif-subjectarea'                 => 'Wilayah subjek',
'exif-flashenergy'                 => 'Inargi kilat',
'exif-focalplanexresolution'       => 'Risulusi bidang pukal X',
'exif-focalplaneyresolution'       => 'Risulusi bidang pukal Y',
'exif-focalplaneresolutionunit'    => 'Unit risulusi bidang pukal',
'exif-subjectlocation'             => 'Lukasi subjek',
'exif-exposureindex'               => 'Indiks paparan',
'exif-sensingmethod'               => 'Mituda pangindraan',
'exif-filesource'                  => 'Asal-mula barakas',
'exif-scenetype'                   => 'Macam pamandangan',
'exif-customrendered'              => 'Parusis ulahan gambar',
'exif-exposuremode'                => 'Mode paparan',
'exif-whitebalance'                => 'Kasaimbangan putih',
'exif-digitalzoomratio'            => 'Rasiu pangganalan digital',
'exif-focallengthin35mmfilm'       => 'Panjang pukal dalam pilem 35 mm',
'exif-scenecapturetype'            => 'Macam panangkapan pamandangan',
'exif-gaincontrol'                 => 'Kandali pamandangan',
'exif-contrast'                    => 'Kuntras',
'exif-saturation'                  => 'Saturasi',
'exif-sharpness'                   => 'Kalandapan',
'exif-devicesettingdescription'    => 'Diskripsi setelan pakakas',
'exif-subjectdistancerange'        => 'Wilayah halat subjek',
'exif-imageuniqueid'               => 'ID unik gambar',
'exif-gpsversionid'                => 'Pirsi gantungan GPS',
'exif-gpslatituderef'              => 'Lintang Utara atawa Selatan',
'exif-gpslatitude'                 => 'Lintang',
'exif-gpslongituderef'             => 'Bujur timur ataw barat',
'exif-gpslongitude'                => 'Bujur',
'exif-gpsaltituderef'              => 'Rujukan kapancauan',
'exif-gpsaltitude'                 => 'Kapancauan',
'exif-gpstimestamp'                => 'Wayah GPS (jam atumik)',
'exif-gpssatellites'               => 'Satelit dipuruk gasan panakaran',
'exif-gpsstatus'                   => 'Status panarima',
'exif-gpsmeasuremode'              => 'Muda panakaran',
'exif-gpsdop'                      => 'Katapatan panakaran',
'exif-gpsspeedref'                 => 'Unit kahancapan',
'exif-gpsspeed'                    => 'Kahancapan panarima GPS',
'exif-gpstrackref'                 => 'Rujukan gasan ampah bagarak',
'exif-gpstrack'                    => 'Ampah bagarak',
'exif-gpsimgdirectionref'          => 'Rujukan gasan ampah gambar',
'exif-gpsimgdirection'             => 'Ampah gambar',
'exif-gpsmapdatum'                 => 'Data surpai giudasi dipuruk',
'exif-gpsdestlatituderef'          => 'Rujukan gasan lintang matan tujuan',
'exif-gpsdestlatitude'             => 'Lintang tujuan',
'exif-gpsdestlongituderef'         => 'Rujukan gasan bujur matan tujuan',
'exif-gpsdestlongitude'            => 'Bujur tujuan',
'exif-gpsdestbearingref'           => 'Rujukan gasan bantalan hubung matan tujuan',
'exif-gpsdestbearing'              => 'Bantalan hubung tujuan',
'exif-gpsdestdistanceref'          => 'Rujukan gasan halat ka tujuan',
'exif-gpsdestdistance'             => 'Halat ka tujuan',
'exif-gpsprocessingmethod'         => 'Ngaran mituda parusis GPS',
'exif-gpsareainformation'          => 'Ngaran wilayah GPS',
'exif-gpsdatestamp'                => 'Tanggal GPS',
'exif-gpsdifferential'             => 'Pambujuran bibidaan GPS',
'exif-jpegfilecomment'             => 'Kumintar barakas JPEG',
'exif-keywords'                    => 'Ujaran-sunduk',
'exif-worldregioncreated'          => 'Wilayah dunia wadah gambar diambil',
'exif-countrycreated'              => 'Nagara wadah gambar diambil',
'exif-countrycodecreated'          => 'Kudi gasan nagara wadah gambar diambil',
'exif-provinceorstatecreated'      => 'Parupinsi atawa nagara hagian wadah gambar diambil',
'exif-citycreated'                 => 'Kuta wadah gambar diambil',
'exif-sublocationcreated'          => 'Sublukasi kuta wadah gambar diambil',
'exif-worldregiondest'             => 'Wilayah dunia ditampaiakan',
'exif-countrydest'                 => 'Nagara ditampaiakan',
'exif-countrycodedest'             => 'Kudi gasan nagara ditampaiakan',
'exif-provinceorstatedest'         => 'Parupinsi atawa nagara hagian ditampaiakan',
'exif-citydest'                    => 'Kuta ditampaiakan',
'exif-sublocationdest'             => 'Sub-lukasi kuta ditampaiakan',
'exif-objectname'                  => 'Judul handap',
'exif-specialinstructions'         => 'Instruksi istimiwa',
'exif-headline'                    => 'Kapala-garis',
'exif-credit'                      => 'Kradit/Panyadia',
'exif-source'                      => 'Asal-mula',
'exif-editstatus'                  => 'Status editorial gambar',
'exif-urgency'                     => 'Urgansi',
'exif-fixtureidentifier'           => 'Ngaran pikstur',
'exif-locationdest'                => 'Lukasi digambarakan',
'exif-locationdestcode'            => 'Kudi lukasi digambarakan',
'exif-objectcycle'                 => 'Wayah matan hari nang madia diambil',
'exif-contact'                     => 'Kuntak panjalasan',
'exif-writer'                      => 'Panulis',
'exif-languagecode'                => 'Bahasa',
'exif-iimversion'                  => 'Parsi IIM',
'exif-iimcategory'                 => 'Tumbung',
'exif-iimsupplementalcategory'     => 'Tumbung tambahan',
'exif-datetimeexpires'             => 'Ditangati mamuruk sasudah',
'exif-datetimereleased'            => 'Dirilis pada',
'exif-originaltransmissionref'     => 'Kudi lukasi transmisi asli',
'exif-identifier'                  => 'Paminandu',
'exif-lens'                        => 'Linsa dipuruk',
'exif-serialnumber'                => 'Rikinan seri kudakan',
'exif-cameraownername'             => 'Ampunnya kudakan',
'exif-label'                       => 'Label',
'exif-datetimemetadata'            => 'Tanggal mitadata pauncitnya diubah',
'exif-nickname'                    => 'Galaran gambar',
'exif-rating'                      => 'Dabit (matan 5)',
'exif-rightscertificate'           => 'Sartipikat hak kalula',
'exif-copyrighted'                 => 'Status hak-rekap',
'exif-copyrightowner'              => 'Pangampunnya hak-rekap',
'exif-usageterms'                  => 'Katantuan mamuruk',
'exif-webstatement'                => 'Parnyataan hak-rekap daring',
'exif-originaldocumentid'          => 'ID unik dukumin asli',
'exif-licenseurl'                  => 'URL lisansi hak-rekap',
'exif-morepermissionsurl'          => 'Panjalasan lisansi altarnatip',
'exif-attributionurl'              => 'Rahatan mamuruk-pulang gawian ngini, muhun tautakan ka',
'exif-preferredattributionname'    => 'Rahatan mamuruk-pulang gawian ngini, muhun bari kradit',
'exif-pngfilecomment'              => 'Kumintar barakas PNG',
'exif-disclaimer'                  => 'Panyangkalan',
'exif-contentwarning'              => 'Paringatan isi',
'exif-giffilecomment'              => 'Kumintar barakas GIF',
'exif-intellectualgenre'           => 'Macanm barang',
'exif-subjectnewscode'             => 'Kudi subjek',
'exif-scenecode'                   => 'Kudi pamandangan IPTC',
'exif-event'                       => 'Kajadian nang digambarakan',
'exif-organisationinimage'         => 'Urganisasi nang digambarakan',
'exif-personinimage'               => 'Urang nang digambarakan',
'exif-originalimageheight'         => 'Pancau gambar sabalum dihandapi',
'exif-originalimagewidth'          => 'Lingai gambar sabalum dihandapi',

# EXIF attributes
'exif-compression-1' => 'Kada dikumpris',
'exif-compression-2' => 'Galambang CCITT 3 1-Dimensional Modified Huffman manjalankan panjang encoding',
'exif-compression-3' => 'Galambang CCITT 3 paks encoding',
'exif-compression-4' => 'Galambang CCITT 4 paks encoding',

'exif-copyrighted-true'  => 'Bahak-rekap',
'exif-copyrighted-false' => 'Dumain publik',

'exif-unknowndate' => 'Tanggal kada dikatahui',

'exif-orientation-1' => 'Nurmal',
'exif-orientation-2' => 'Dibalik hurisuntal',
'exif-orientation-3' => 'Diputarakan 180°',
'exif-orientation-4' => 'Dibalik partikal',
'exif-orientation-5' => 'Diputarakan 90° CCW wan dibalik partikal',
'exif-orientation-6' => 'Diputarakan 90° CCW',
'exif-orientation-7' => 'Diputarakan 90° CW wan dibalik partikal',
'exif-orientation-8' => 'Diputarakan 90° CW',

'exif-planarconfiguration-1' => 'purmat chunky',
'exif-planarconfiguration-2' => 'purmat planar',

'exif-colorspace-65535' => 'Kada-dikalibrasi',

'exif-componentsconfiguration-0' => 'Kadada tasadia',

'exif-exposureprogram-0' => 'Kada tadapinisi',
'exif-exposureprogram-1' => 'Manual',
'exif-exposureprogram-2' => 'Nurmal parugram',
'exif-exposureprogram-3' => 'Priuritas singkaian',
'exif-exposureprogram-4' => 'Priuritas panungkup',
'exif-exposureprogram-5' => 'Parugram kariatip (hiring ka kadalaman lapangan)',
'exif-exposureprogram-6' => 'Parugram lakuan (hiring ka kahancapan singkaian)',
'exif-exposureprogram-7' => 'Muda putrait (gasan putu parak awan latar-balakang kaluar matan pukus)',
'exif-exposureprogram-8' => 'Moda pamandangan (gasan poto pamandangan awan latar balakang pokus)',

'exif-subjectdistance-value' => '$1 mitir',

'exif-meteringmode-0'   => 'Kada dikatahui',
'exif-meteringmode-1'   => 'Rarata',
'exif-meteringmode-2'   => 'Rarata pusat barat',
'exif-meteringmode-3'   => 'Titik',
'exif-meteringmode-4'   => 'Banyak-Titik',
'exif-meteringmode-5'   => 'Pula',
'exif-meteringmode-6'   => 'Sahagian',
'exif-meteringmode-255' => 'Lain',

'exif-lightsource-0'   => 'Kada dikatahui',
'exif-lightsource-1'   => 'Sinar-siang',
'exif-lightsource-2'   => 'Fluorescent',
'exif-lightsource-3'   => 'Tungsten (sinar incandescent)',
'exif-lightsource-4'   => 'Kilat',
'exif-lightsource-9'   => 'Cuaca baik',
'exif-lightsource-10'  => 'Cuaca ba-awan',
'exif-lightsource-11'  => 'Bayangan',
'exif-lightsource-12'  => 'Sinar siang fluorescent (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Putih siang fluorescent (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Putih taduh fluorescent (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Putih fluorescent (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Sinar standar A',
'exif-lightsource-18'  => 'Sinar standar B',
'exif-lightsource-19'  => 'Sinar standar C',
'exif-lightsource-24'  => 'studiu ISO tungsten',
'exif-lightsource-255' => 'Asal-mula sinar lain',

# Flash modes
'exif-flash-fired-0'    => 'Kilat kada banyala',
'exif-flash-fired-1'    => 'Kilat banyala',
'exif-flash-return-0'   => 'Kadada strobo/kilat tabulik bapungsi',
'exif-flash-return-2'   => 'sinar stobo tabulik/kilat kada tadeteksi',
'exif-flash-return-3'   => 'sinar stobo tabulik/kilat tadeteksi',
'exif-flash-mode-1'     => 'wajib banyala kilat',
'exif-flash-mode-2'     => 'lampu kilat ditikin',
'exif-flash-mode-3'     => 'moda utumatis',
'exif-flash-function-1' => 'Kadada pungsi lampu kilat',
'exif-flash-redeye-1'   => 'moda kurangi mata-habang',

'exif-focalplaneresolutionunit-2' => 'inci',

'exif-sensingmethod-1' => 'Kada-tajalasi',
'exif-sensingmethod-2' => 'Sinsur wilayah warna asa-chip',
'exif-sensingmethod-3' => 'Sinsur wilayah warna dua-chip',
'exif-sensingmethod-4' => 'Sinsur wilayah warna talu-chip',
'exif-sensingmethod-5' => 'Sinsur wilayah warna baurut',
'exif-sensingmethod-7' => 'Sinsur talu-garisan (trilinear)',
'exif-sensingmethod-8' => 'Sinsur wilayah warna baurut sagaris',

'exif-filesource-3' => 'Kudakan hinip digital',

'exif-scenetype-1' => 'Sabuah gambar poto langsung',

'exif-customrendered-0' => 'Parusis nurmal',
'exif-customrendered-1' => 'Parusis kustum',

'exif-exposuremode-0' => 'Paparan utumatis',
'exif-exposuremode-1' => 'Paparan manual',
'exif-exposuremode-2' => 'Kurungan utumatis',

'exif-whitebalance-0' => 'Kasaimbangan putih utumatis',
'exif-whitebalance-1' => 'Kasaimbangan putih manual',

'exif-scenecapturetype-0' => 'Standar',
'exif-scenecapturetype-1' => 'Balingai',
'exif-scenecapturetype-2' => 'Putrait',
'exif-scenecapturetype-3' => 'Pamandangan malam',

'exif-gaincontrol-0' => 'Kadada',
'exif-gaincontrol-1' => 'Naikakan sahikit',
'exif-gaincontrol-2' => 'Naikakan tabanyak',
'exif-gaincontrol-3' => 'Turunakan sahikit',
'exif-gaincontrol-4' => 'Turunakan fokus atas',

'exif-contrast-0' => 'Nurmal',
'exif-contrast-1' => 'Hapuk',
'exif-contrast-2' => 'Karas',

'exif-saturation-0' => 'Nurmal',
'exif-saturation-1' => 'Saturasi randah',
'exif-saturation-2' => 'Saturasi pancau',

'exif-sharpness-0' => 'Nurmal',
'exif-sharpness-1' => 'Hapuk',
'exif-sharpness-2' => 'Karas',

'exif-subjectdistancerange-0' => 'Kada dikatahui',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Tiringan rapat',
'exif-subjectdistancerange-3' => 'Tiringan bajarak',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Lintang utara',
'exif-gpslatitude-s' => 'Lintang selatan',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Bujur timur',
'exif-gpslongitude-w' => 'Bujur barat',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|mitir|mitir}} di atas parmukaan laut',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|mitir|mitir}} di bawah parmukaan laut',

'exif-gpsstatus-a' => 'Lagi ada pangukuran',
'exif-gpsstatus-v' => 'Pangukuran intaruparabilitas',

'exif-gpsmeasuremode-2' => 'Pangukuran 2-dimansi',
'exif-gpsmeasuremode-3' => 'Pangukuran 3-dimansi',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilumitir par jam',
'exif-gpsspeed-m' => 'Mil par jam',
'exif-gpsspeed-n' => 'Knot',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Kilumitir',
'exif-gpsdestdistance-m' => 'Mil',
'exif-gpsdestdistance-n' => 'Mil laut',

'exif-gpsdop-excellent' => 'Bungas banar ($1)',
'exif-gpsdop-good'      => 'Bungas ($1)',
'exif-gpsdop-moderate'  => 'Sadang ($1)',
'exif-gpsdop-fair'      => 'Cukup ($1)',
'exif-gpsdop-poor'      => 'Buruk ($1)',

'exif-objectcycle-a' => 'Sungsung haja',
'exif-objectcycle-p' => 'Malam haja',
'exif-objectcycle-b' => 'Sungsung wan malam',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Ampah bujur',
'exif-gpsdirection-m' => 'Ampah wasi wani',

'exif-ycbcrpositioning-1' => 'Tangahi',
'exif-ycbcrpositioning-2' => 'Atas (co-sited)',

'exif-dc-contributor' => 'Panyumbang',
'exif-dc-coverage'    => 'Lingkup timpural atawa spasial madia',
'exif-dc-date'        => 'Tanggal',
'exif-dc-publisher'   => 'Panarbit',
'exif-dc-relation'    => 'Madia tarait',
'exif-dc-rights'      => 'Hak',
'exif-dc-source'      => 'Madia asalmula',
'exif-dc-type'        => 'Macam madia',

'exif-rating-rejected' => 'Ditulak',

'exif-isospeedratings-overflow' => 'Labih pada 65535',

'exif-iimcategory-ace' => 'Seni, budaya, wan hiburan',
'exif-iimcategory-clj' => 'Kajahatan wan hukum',
'exif-iimcategory-dis' => 'Bancana wan kacalakaan',
'exif-iimcategory-fin' => 'Ekonomi wan bisnis',
'exif-iimcategory-edu' => 'Pandidikan',
'exif-iimcategory-evn' => 'Lingkungan',
'exif-iimcategory-hth' => 'Kasihatan',
'exif-iimcategory-hum' => 'Minat insani',
'exif-iimcategory-lab' => 'Katanagagawian',
'exif-iimcategory-lif' => 'Gaya hidup wan rikriasi',
'exif-iimcategory-pol' => 'Pulitik',
'exif-iimcategory-rel' => 'Agama wan kaparcayaan',
'exif-iimcategory-sci' => 'Ilmu wan tiknulugi',
'exif-iimcategory-soi' => 'Isu susial',
'exif-iimcategory-spo' => 'Ulur-urat',
'exif-iimcategory-war' => 'Parang, cakut wan karasahan',
'exif-iimcategory-wea' => 'Cuaca',

'exif-urgency-normal' => 'Nurmal ($1)',
'exif-urgency-low'    => 'Randah ($1)',
'exif-urgency-high'   => 'Pancau ($1)',
'exif-urgency-other'  => 'Ganti-suai utamaan ($1)',

# External editor support
'edit-externally'      => 'Babak barakas ngini puruk sabuah aplikasi luar',
'edit-externally-help' => '(Lihati [//www.mediawiki.org/wiki/Manual:External_editors setup instructions] untuk panjalasan labih)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'samunyaan',
'namespacesall' => 'samunyaan',
'monthsall'     => 'samunyaan',
'limitall'      => 'samunyaan',

# E-mail address confirmation
'confirmemail'              => 'Yakinakan alamat suril',
'confirmemail_noemail'      => 'Pian kada baisi sabuah alamat suril nang sah dalam [[Special:Preferences|kakatujuan pamuruk]] Pian.',
'confirmemail_text'         => '{{SITENAME}} mawajibakan Pian mayakinakan alamat suril Pian sabalum pitur-pitur suril dipurukakan.
Picik tumbul di bawah ngini hagan mangirimi Pian sabuah suril payakinan ka alamat Pian.
Si suril akan ada di dalam sabuah tautan bakudi;
Handak si tautan ka panjalajah Pian hagan mayakinakan bahwasa alamat suril Pian sah.',
'confirmemail_pending'      => 'Sabuah kudi payakinan sudah tasuril ka Pian;
Amun Pian hahanyar ni maulah akun Pian, Pian kawa lah mahadangi babarapa minit gasan ngini hagan sampai sabalum mancuba maminta sabuah kudi hanyar.',
'confirmemail_send'         => 'Surili sabua kudi payakinan',
'confirmemail_sent'         => 'Suril payakinan takirim.',
'confirmemail_oncreate'     => 'Sabuah kudi payakinan sudah takirim ka alamat suril Pian.
Kudi ngini kada parlu babuat log, tagal Pian akan parlu mayadiakan ngini sabalum mangkawakan babarapa pitur bapadal suril dalam wiki ngini.',
'confirmemail_sendfailed'   => '{{SITENAME}} kada kulihan mangirim suril payakinan Pian.
Muhun pariksa alamat suril Pian matan karaktir kada sah.

Mailer mambulikakan: $1',
'confirmemail_invalid'      => 'Kudi payakinan kada sah.
Si kudi pinanya sudah kadaluarsa.',
'confirmemail_needlogin'    => 'Pian parlu $1 hagan mayakinakan alamat suril Pian.',
'confirmemail_success'      => 'Alamat suril Pian sudah diyakinakan.
Rahatan ni Pian kawa [[Special:UserLogin|babuat log]] wan bahimung wiki.',
'confirmemail_loggedin'     => 'Alamat suril Pian rahatan ni sudah diyakinakan.',
'confirmemail_error'        => 'Ada nang tasalah rahatan manyimpan payakinan Pian.',
'confirmemail_subject'      => '{{SITENAME}} alamat suril payakinan',
'confirmemail_body'         => 'Sasaurang, pinanya Pian, malan alamat IP $1,
sudah mandaptarakan sabuah akun "$2" awan alamat suril ngini pada {{SITENAME}}.

Hagan mayakinakan bahwasa akun ngini bujur ampun Pian wan ma-aktip-akan
pipitur suril pada {{SITENAME}}, ungkai tautan ngini ka panjalajah Pian;

$3

Amun Pian *kada* mandaptarakan si akun, umpati tautan ngini
hagan mawalangi payakinan alamat suril:

$5

Kudi payakinan ngini akan kadaluarsa pada $4.',
'confirmemail_body_changed' => 'Sasaurang, pinanya Pian, malan alamat IP $1,
sudah mangganti alamat suril sabuah akun "$2" awan alamat suril ngini pada {{SITENAME}}.

Hagan mayakinakan bahwasa akun ngini bujur ampun Pian wan ma-aktip-akan pulang
pipitur suril pada {{SITENAME}}, ungkai tautan ngini ka panjalajah Pian;

$3

Amun si akun *kada* bujur ampun Pian, umpati tautan ngini
hagan mawalangi payakinan alamat suril:

$5

Kudi payakinan ngini akan kadaluarsa pada $4.',
'confirmemail_body_set'     => 'Sasaurang, pinanya Pian, malan alamat IP $1,
sudah manyetel alamat suril sabuah akun "$2" awan alamat suril ngini pada {{SITENAME}}.

Hagan mayakinakan bahwasa akun ngini bujur ampun Pian wan ma-aktip-akan pulang
pipitur suril pada {{SITENAME}}, ungkai tautan ngini ka panjalajah Pian;

$3

Amun si akun *kada* bujur ampun Pian, umpati tautan ngini
hagan mawalangi payakinan alamat suril:

$5

Kudi payakinan ngini akan kadaluarsa pada $4.',
'confirmemail_invalidated'  => 'Payakinan alamat suril diwalangi',
'invalidateemail'           => 'Walangi suril payakinan',

# Scary transclusion
'scarytranscludedisabled' => '[Transklusi intarwiki dipajahakan]',
'scarytranscludefailed'   => '[Pangambilan citakan $1 gagal]',
'scarytranscludetoolong'  => '[URL kapanjangan]',

# Delete conflict
'deletedwhileediting'      => "'''Paringatan''': Tungkaran ngini sudah dihapus satalah Pian bamula mambabak!",
'confirmrecreate'          => "Pamuruk [[User:$1|$1]] ([[User talk:$1|pandir]]) sudah mahapus tungkaran ngini satalah Pian bamula mambabak awan alasan:
: ''$2''
Silakan yakinakan bahwasa Pian handak banar maulah pulang tungkaran ngini.",
'confirmrecreate-noreason' => 'Pamuruk [[User:$1|$1]] ([[User talk:$1|pandir]]) sudah mahapus tungkaran ngini satalah Pian bamula mambabak. Muhun yakinakan bahwasa Pian handak banar maulah pulang tungkaran ngini.',
'recreate'                 => 'Ulah pulang',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => 'Barsihakan timbuluk matan tungkaran ngini?',
'confirm-purge-bottom' => 'Mambarasihakan timbuluk tungkaran wan manunjul ralatan pahanyarnya cungul.',

# action=watch/unwatch
'confirm-watch-button'   => 'OK',
'confirm-watch-top'      => 'Tambahi tungkaran ngini ka paitihan Pian?',
'confirm-unwatch-button' => 'OK',
'confirm-unwatch-top'    => 'Buang tungkaran ini matan paitihan Pian?',

# Multipage image navigation
'imgmultipageprev' => '← tungkaran sabalumnya',
'imgmultipagenext' => 'tungkaran barikutnya →',
'imgmultigo'       => 'Tulak!',
'imgmultigoto'     => 'Tulak ka tungkaran $1',

# Table pager
'ascending_abbrev'         => 'naik',
'descending_abbrev'        => 'turun',
'table_pager_next'         => 'Tungkaran salanjutnya',
'table_pager_prev'         => 'Tungkaran sabalumnya',
'table_pager_first'        => 'Tungkaran panambaian',
'table_pager_last'         => 'Tungkaran pauncitnya',
'table_pager_limit'        => 'Tampaiakan $1 buatan par tungkaran',
'table_pager_limit_label'  => 'Barang par tungkaran:',
'table_pager_limit_submit' => 'Tulak ka',
'table_pager_empty'        => 'Kadada kulihan',

# Auto-summaries
'autosumm-blank'   => 'Kusungakan tungkaran',
'autosumm-replace' => "Mangganti isi wan ''$1''",
'autoredircomment' => 'Paugahan tungkaran ka [[$1]]',
'autosumm-new'     => "Ma-ulah tungkaran nang isinya ''$1''",

# Live preview
'livepreview-loading' => "Ma'unggah...",
'livepreview-ready'   => "Ma'unggah...Tuntung!",
'livepreview-failed'  => 'Titilikan langsung gagal!
Cubai titilikan nurmal.',
'livepreview-error'   => 'Gagal tasambung: $1 "$2".
Cubai titilikan nurmal.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Parubahan tahanyar pada $1 {{PLURAL:$1|ditik|diditik}} pinanya kada cungul di daptar ngini.',
'lag-warn-high'   => 'Karana pancaunya kalambatan sarvar databasis, parubahan tahanyar pada {{PLURAL:$1|datik|dadatik}} pina kada ditampaiakan dalam daptar ngini.',

# Watchlist editor
'watchlistedit-numitems'       => 'Daptar itihan Pian baisi {{PLURAL:$1|1 judul|$1 judul}}, kada tabuat tutungkaran pamandiran.',
'watchlistedit-noitems'        => 'Daptar itihan Pian kada baisi jujudul.',
'watchlistedit-normal-title'   => 'Babak daptar itihan',
'watchlistedit-normal-legend'  => 'Buang jujudul matan daptar itihan',
'watchlistedit-normal-explain' => 'Jujudul dalam daptar itihan Pian ditampaiakan di bawah ngini.
Hagan mambuang sabuah judul, cintang kutak dudi ka ngini, wan klik "{{int:Watchlistedit-normal-submit}}".
Pian kawa jua [[Special:EditWatchlist/raw|mambabak daptar mantah]].',
'watchlistedit-normal-submit'  => 'Buang jujudul',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 judul|$1 jujudul}} dibuang matan daptar itihan Pian:',
'watchlistedit-raw-title'      => 'Babak daptar itihan mantah',
'watchlistedit-raw-legend'     => 'Babak daptar itihan mantah',
'watchlistedit-raw-explain'    => 'Jujudul pada daptar itihan Pian ditampaiakan di bawah ngini, wan kawa dibabak manambahi wan mambuang matan si daptar;
asa judul par baris.
Rahatan tuntung, klik "{{int:Watchlistedit-raw-submit}}".
Pian kawa jua [[Special:EditWatchlist|mamuruk si pambabak standar]].',
'watchlistedit-raw-titles'     => 'Jujudul:',
'watchlistedit-raw-submit'     => 'Pugai daptar itihan',
'watchlistedit-raw-done'       => 'Daptar itihan Pian sudah dipugai',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 judul|$1 jujudul}} ditambahi:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 judul|$1 jujudul}} dibuangi:',

# Watchlist editing tools
'watchlisttools-view' => 'Tampaiakan parubahan tarait',
'watchlisttools-edit' => 'Tiringi wan babak daptar itihan',
'watchlisttools-raw'  => 'Babak daptar itihan mantah',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|pandir]])',

# Core parser functions
'unknown_extension_tag' => 'Tag ekstensi kada dipinandui "$1"',
'duplicate-defaultsort' => 'Paringatan: Sunduk pangurutan baku "$2" mangabaikan sunduk pangurutan baku "$1" sabalumnya.',

# Special:Version
'version'                       => 'Virsi',
'version-extensions'            => 'Ekstensi tapasang',
'version-specialpages'          => 'Tungkaran istimiwa',
'version-parserhooks'           => 'Kait parser',
'version-variables'             => 'Pariabal',
'version-antispam'              => 'Pancagahan spam',
'version-skins'                 => 'Kukulimbit',
'version-other'                 => 'Lain-lain',
'version-mediahandlers'         => 'Pananganan madia',
'version-hooks'                 => 'Kait',
'version-extension-functions'   => 'Pungsi ekstensi',
'version-parser-extensiontags'  => 'Tag ekstensi parser',
'version-parser-function-hooks' => 'Kait pungsi parser',
'version-hook-name'             => 'Ngaran kait',
'version-hook-subscribedby'     => 'Dilanggani ulih',
'version-version'               => '(Pirsi $1)',
'version-license'               => 'Lisansi',
'version-poweredby-credits'     => "Wiki ngini disukung ulih '''[//www.mediawiki.org/ MediaWiki]''', hak salin © 2001-$1 $2.",
'version-poweredby-others'      => 'lainnya',
'version-license-info'          => 'MediaWiki adalah parangkat lunak bibas; Pian kawa manyabarakan wan/atawa maubahi ngini di bawah syarat Lisansi Publik Umum sawagai tarbitan ulih Free Software Foundation; apakah Lisansi virsi 2, atawa (pilihan Pian) tahanyar.

MediaWiki disabarakan awan harapan akan baguna, tagal KADA BAJAMINAN; kada jaminan PANIAGAAN atawa KATAPATAN HAGAN TUJUAN TARTANTU. Janaki Lisansi Publik Umum GNU gasan panjalasan rinci.

Pian saharusnya [{{SERVER}}{{SCRIPTPATH}}/COPYING sabuah salinan Lisansi Publik Umum GNU] baimbai awan prugram ngini; amun kada, tulis ka Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA atawa [//www.gnu.org/licenses/old-licenses/gpl-2.0.html baca ngini daring].',
'version-software'              => 'Parangkat lunak tapasang',
'version-software-product'      => 'Produk',
'version-software-version'      => 'Virsi',

# Special:FilePath
'filepath'         => 'Wadah barakas',
'filepath-page'    => 'Barakas:',
'filepath-submit'  => 'Gagai',
'filepath-summary' => 'Tungkaran istimiwa ngini mambulikakan jalur panuntungan sabuah barakas.
Gambar ditampaiakan dalam risulusi hibak, janis barakas lain dimula lawan prugram taraitnya langsung.',

# Special:FileDuplicateSearch
'fileduplicatesearch'           => 'Gagai gasan babarakas baganda',
'fileduplicatesearch-summary'   => 'Gagai gasan babarakas baganda bapandal nilai hash.',
'fileduplicatesearch-legend'    => 'Gagai gasan sabuah panggandaan',
'fileduplicatesearch-filename'  => 'Ngaran barakas:',
'fileduplicatesearch-submit'    => 'Gagai',
'fileduplicatesearch-info'      => '$1 × $2 piksel, <br />takaran barakas: $3, <br />macam MIME: $4',
'fileduplicatesearch-result-1'  => "Barakas ''$1'' kada baisi panggandaan parsis.",
'fileduplicatesearch-result-n'  => "Barakas ''$1'' baisi {{PLURAL:$2|1 panggandaan parsis|$2 papanggandaan parsis}}.",
'fileduplicatesearch-noresults' => "Kadada barakas bangaran ''$1'' taugai.",

# Special:SpecialPages
'specialpages'                   => 'Tungkaran istimiwa',
'specialpages-note'              => '----
* Tutungkaran istimiwa normal
* <span class="mw-specialpagerestricted">Tutungkaran istimiwa tabatas.</span>
* <span class="mw-specialpagecached">Tutungkaran istimiwa timbuluk (pinanya bakulat).</span>',
'specialpages-group-maintenance' => 'Lapuran pamaliharaan',
'specialpages-group-other'       => 'Tungkaran istimiwa lainnya',
'specialpages-group-login'       => 'Babuat log / mandaptar',
'specialpages-group-changes'     => 'Parubahan tahanyar wan log',
'specialpages-group-media'       => 'Lapuran wan pamuatan barakas',
'specialpages-group-users'       => 'Pamuruk wan hak pamuruk',
'specialpages-group-highuse'     => 'Tungkaran pamakaian tinggi',
'specialpages-group-pages'       => 'Daptar tungkaran',
'specialpages-group-pagetools'   => 'Pakakas tungkaran',
'specialpages-group-wiki'        => 'Data wan pakakas wiki',
'specialpages-group-redirects'   => 'Maugahakan tungkaran istimiwa',
'specialpages-group-spam'        => 'Pakakas spam',

# Special:BlankPage
'blankpage'              => 'Tungkaran puang',
'intentionallyblankpage' => "Tungkaran ini kurinah dibiarakan puang wan diguna'akan di antaranya gasan paukuran kinerja, wan lain-lain.",

# External image whitelist
'external_image_whitelist' => '#Tinggalakan baris ngini parsis kaya ngini haja <pre>
#Handak fragmen ekspresi umum (hagian antara haja //) di bawah
#Ngini akan dipasakan awan  gambar URL luar (hotlinked)
#Ngitu nang pas akan ditampaiakan sawagai gambar, salain ngitu sabuah tautan ka gambar akan ditampaiakan
#Baris ba-awalan awan # adalah sawagai kumintar
#Ngini kada mambidakan hurup ganal wan halus

#Handak samunyaan fragmen regex di atas baris ngini. Tinggalakan baris ngini parsis kaya ngini haja </pre>',

# Special:Tags
'tags'                    => 'Tag parubahan sah',
'tag-filter'              => 'Saringan [[Special:Tags|Tag]]:',
'tag-filter-submit'       => 'Saringan',
'tags-title'              => 'Gantungan',
'tags-intro'              => 'Tungkaran ngini mandaptar gantungan nang diciri-i parangkat lunak sabuah babakan, wan artinya.',
'tags-tag'                => 'Gantungan ngaran',
'tags-display-header'     => 'Pancungulan pada daptar parubahan.',
'tags-description-header' => 'Diskripsi hibak matan arti',
'tags-hitcount-header'    => 'Gantungan diganti',
'tags-edit'               => 'babak',
'tags-hitcount'           => '$1 {{PLURAL:$1|parubahan|paparubahan}}',

# Special:ComparePages
'comparepages'                => 'Bandingakan tutungkaran',
'compare-selector'            => 'Tanding raralatan tungkaran',
'compare-page1'               => 'Tungkaran 1',
'compare-page2'               => 'Tungkaran 2',
'compare-rev1'                => 'Ralatan 1',
'compare-rev2'                => 'Ralatan 2',
'compare-submit'              => 'Tanding',
'compare-invalid-title'       => 'Judul nang Pian bari kada sah.',
'compare-title-not-exists'    => 'Si judul nang Pian ajuakan kadada.',
'compare-revision-not-exists' => 'Si ralatan nang Pian ajuakan kadada.',

# Database error messages
'dberr-header'      => 'Wiki ngini baisi sabuah masalah',
'dberr-problems'    => 'Ampun!
Situs ngini mangalami kangalihan teknik.',
'dberr-again'       => 'Cuba hadangi babarapa manit wan muat-pulang.',
'dberr-info'        => '(Kada kawa tasambung ka server databasis: $1)',
'dberr-usegoogle'   => 'Pian kawa mancuba manggagai lung Google wayah pahadangan ngini.',
'dberr-outofdate'   => 'Catat nang sidin indéks matan isi kami pinanya hudah kadaluarsa.',
'dberr-cachederror' => 'Ngini adalah sabuah rekap timbuluk tungkaran nang dipinta, wan pinanya kada pahanyarnya.',

# HTML forms
'htmlform-invalid-input'       => 'Ada mamasalah awan babarapa masukan Pian',
'htmlform-select-badoption'    => 'Nilai nang Pian ajuakan kada sah.',
'htmlform-int-invalid'         => 'Nilai nang Pian ajuakan kada sabuah bilangan bulat.',
'htmlform-float-invalid'       => 'Nilai nang Pian ajuakan kada sabuah angka.',
'htmlform-int-toolow'          => 'Nilai nang Pian ajuakan karandahan pada minimal $1',
'htmlform-int-toohigh'         => 'Nilai nang Pian ajuakan kapancauan pada maksimal $1',
'htmlform-required'            => 'Nilai ngini nang diparluakan',
'htmlform-submit'              => 'Kirim',
'htmlform-reset'               => 'Walangi parubahan',
'htmlform-selectorother-other' => 'Lain-lain',

# SQLite database support
'sqlite-has-fts' => '$1 awan sukungan panggagaian naskah-hibak',
'sqlite-no-fts'  => '$1 kada-awan sukungan panggagaian naskah-hibak',

# New logging system
'logentry-delete-delete'              => '$1 mahapus tungkaran $3',
'logentry-delete-restore'             => '$1 dibulikakan tungkaran $3',
'logentry-delete-event'               => '$1 mangganti kakawaan dijanaki {{PLURAL:$5|sabuah log kajadian|$5 log kajadian}} pintangan $3: $4',
'logentry-delete-revision'            => '$1 mangganti kakawaan dijanaki {{PLURAL:$5|sabuah ralatan|$5 ralatan}} pintangan tungkaran $3: $4',
'logentry-delete-event-legacy'        => '$1 mangganti kakawaan dijanaki log kajadian pintangan $3',
'logentry-delete-revision-legacy'     => '$1 mangganti kakawaan dijanaki ralatan pintangan tungkaran $3',
'logentry-suppress-delete'            => '$1 ditikin tungkaran $3',
'logentry-suppress-event'             => '$1 mangganti kakawaan dijanaki {{PLURAL:$5|sabuah log kajadian|$5 log kajadian}} pintangan $3: $4 lawan rahasia',
'logentry-suppress-revision'          => '$1 mangganti kakawaan dijanaki {{PLURAL:$5|sabuah ralatan|$5 ralatan}} pintangan tungkaran $3: $4 lawan rahasia',
'logentry-suppress-event-legacy'      => '$1 mangganti kakawaan dijanaki log kajadian pintangan $3 lawan rahasia',
'logentry-suppress-revision-legacy'   => '$1 mangganti kakawaan dijanaki ralatan pintangan tungkaran $3 lawan rahasia',
'revdelete-content-hid'               => 'Isi disungkupakan',
'revdelete-summary-hid'               => 'babak kasimpulan tasungkup',
'revdelete-uname-hid'                 => 'ngaran-pamuruk tasungkup',
'revdelete-content-unhid'             => 'Isi kada disungkupakan',
'revdelete-summary-unhid'             => 'babak kasimpulan kada tasungkup',
'revdelete-uname-unhid'               => 'ngaran-pamuruk kada tasungkup',
'revdelete-restricted'                => 'Talamar pambatasan hagan pambakal-pambakal',
'revdelete-unrestricted'              => 'Buang pambatasan gasan pambakal-pambakal',
'logentry-move-move'                  => '$1 mamindahakan tungkaran $3 ka $4',
'logentry-move-move-noredirect'       => '$1 diugah tungkaran $3 ka $4 awan-kada maninggalakan sabuah paugahan',
'logentry-move-move_redir'            => '$1 diugah tungkaran $3 ka $4 lung paugahan',
'logentry-move-move_redir-noredirect' => '$1 diugah tungkaran $3 ka $4 lung sabuah paugahan awan-kada maninggalakan sabuah paugahan',
'logentry-patrol-patrol'              => "$1 diciri'i ralatan $4 matan tungkaran $3 taawasi",
'logentry-patrol-patrol-auto'         => "$1 utumatis diciri'i ralatan $4 matan tungkaran $3 taawasi",
'logentry-newusers-newusers'          => '$1 ma-ulah sabuting akun pamakai',
'logentry-newusers-create'            => '$1 ma-ulah sabuting akun pamakai',
'logentry-newusers-create2'           => '$1 ma-ulah sabuting akun pamakai $3',
'logentry-newusers-autocreate'        => 'Akun $1 utumatis diulah',
'newuserlog-byemail'                  => 'Katasunduk dikirimakan lung suril.',

# Feedback
'feedback-bugornote' => 'Pabila Pian siap manjalasakan sabuah masalah taknik rinci muhun [lapurakan sabuah bug $1].
Salain ngitu, Pian kawa mamuruk prmulir nyaman di bawah ngini. Kumintar Pian akan ditambahi ka si tungkaran "[$3 $2]", baimbai awan ngaran-pamuruk Pian wan panjalajah nagn Pian puruk.',
'feedback-subject'   => 'Parihal:',
'feedback-message'   => 'Pasan:',
'feedback-cancel'    => 'Walangi',
'feedback-submit'    => 'Kirimi Kitihanbalik',
'feedback-adding'    => 'Manambahi kitihanbalik ka tungkaran...',
'feedback-error1'    => 'Kasalahan: kulihan matan API kada-dipinandui',
'feedback-error2'    => 'Kasalahan: Babakan gagal',
'feedback-error3'    => 'Kasalahan: Kadada tanggapan matan API',
'feedback-thanks'    => 'Tarimakasih! jitihanbalik Pian sudah dipusakan ka si tungkaran "[$2 $1]".',
'feedback-close'     => 'Sudah',
'feedback-bugcheck'  => 'Harat! hanyar dipariksa bahwasa ngini lainan salah asa [$1 bug nang dipinandui].',
'feedback-bugnew'    => 'Ulun mamariksa. Malapurakan sabuah bug hanyar',

# API errors
'api-error-missingresult'      => 'Kasalahan intarnal: kada kawa manantuakan napakah panyalinan tuntung.',
'api-error-mustbeloggedin'     => 'Pian harus babuat ka log gasan maunggah barakas.',
'api-error-mustbeposted'       => 'Ada bug di parangkat lamah naya; kada mamakai mituda HTTP nang bujur.',
'api-error-noimageinfo'        => 'Paunggahan tuntung, tagal paladen kada mambarii inpurmasi napa haja masalah barakas.',
'api-error-nomodule'           => 'Kasalahan intarnal: kada ada modul unggahan nang ditatapakan.',
'api-error-ok-but-empty'       => 'Kasalahan intarnal: kada ada tanggapan matan paladen.',
'api-error-overwrite'          => 'Kada dibariakan manindihi barakas nang sudah ada.',
'api-error-stashfailed'        => 'Kasalahan intarnal: server gagal manyimban barakas samantara.',
'api-error-timeout'            => 'Peladen kada marispun di waktu nang diharapakan',
'api-error-unclassified'       => 'Tajadi kasalahan nang kada dikatahui.',
'api-error-unknown-code'       => 'Kasalahan kada dipinandui: "$1".',
'api-error-unknown-error'      => 'Kasalahan intarnal: tajadi kasalahan pas mancuba maunggah barakas Pian.',
'api-error-unknown-warning'    => 'Paringatan kada dipinandui: "$1".',
'api-error-unknownerror'       => 'Kasalahan kada dipinandui: "$1".',
'api-error-uploaddisabled'     => 'Paunggahan dinunaktipakan di wiki naya.',
'api-error-verification-error' => 'Barakas naya kira-kira rusak atawa baisi ikstinsi nang salah.',

);
