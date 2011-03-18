<?php
/** Sundanese (Basa Sunda)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Irwangatot
 * @author Kandar
 * @author Meursault2004
 * @author Mssetiadi
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$fallback = 'id';

$namespaceNames = array(
	NS_MEDIA            => 'Média',
	NS_SPECIAL          => 'Husus',
	NS_TALK             => 'Obrolan',
	NS_USER             => 'Pamaké',
	NS_USER_TALK        => 'Obrolan_pamaké',
	NS_PROJECT_TALK     => 'Obrolan_$1',
	NS_FILE             => 'Gambar',
	NS_FILE_TALK        => 'Obrolan_gambar',
	NS_MEDIAWIKI        => 'MédiaWiki',
	NS_MEDIAWIKI_TALK   => 'Obrolan MédiaWiki',
	NS_TEMPLATE         => 'Citakan',
	NS_TEMPLATE_TALK    => 'Obrolan_citakan',
	NS_HELP             => 'Pitulung',
	NS_HELP_TALK        => 'Obrolan_pitulung',
	NS_CATEGORY         => 'Kategori',
	NS_CATEGORY_TALK    => 'Obrolan_kategori',
);

$namespaceAliases = array(
	'Obrolan_MediaWiki' => NS_MEDIAWIKI_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'AlihanGanda' ),
	'BrokenRedirects'           => array( 'AlihanPegat' ),
	'Disambiguations'           => array( 'Disambiguasi' ),
	'Userlogin'                 => array( 'AsupLog' ),
	'Userlogout'                => array( 'KaluarLog' ),
	'CreateAccount'             => array( 'NyieunRekening' ),
	'Preferences'               => array( 'Preferensi' ),
	'Watchlist'                 => array( 'Awaskeuneun' ),
	'Recentchanges'             => array( 'AnyarRobah' ),
	'Upload'                    => array( 'Kunjal' ),
	'Listfiles'                 => array( 'DaptarGambar' ),
	'Newimages'                 => array( 'GambarAnyar' ),
	'Listusers'                 => array( 'DaptarPamake' ),
	'Listgrouprights'           => array( 'DaptarHakPamake' ),
	'Statistics'                => array( 'Statistika' ),
	'Randompage'                => array( 'Acak', 'KacaAcak' ),
	'Lonelypages'               => array( 'KacaNyorangan' ),
	'Uncategorizedpages'        => array( 'KacaTanpaKategori' ),
	'Uncategorizedcategories'   => array( 'KategoriTanpaKategori' ),
	'Uncategorizedimages'       => array( 'GambarTanpaKategori' ),
	'Uncategorizedtemplates'    => array( 'CitakanTanpaKategori' ),
	'Unusedcategories'          => array( 'KategoriNganggur' ),
	'Unusedimages'              => array( 'GambarNganggur' ),
	'Wantedpages'               => array( 'KacaDiteang', 'TumbuPegat' ),
	'Wantedcategories'          => array( 'KategoriDiteang' ),
	'Mostlinked'                => array( 'PalingDitumbu' ),
	'Mostlinkedcategories'      => array( 'KategoriPalingDitumbu', 'KategoriPalingKapake' ),
	'Mostlinkedtemplates'       => array( 'CitakanPalingDitumbu', 'CitakanPalingKapake' ),
	'Mostimages'                => array( 'GambarPalingKapake' ),
	'Mostcategories'            => array( 'KategoriPalingLoba' ),
	'Mostrevisions'             => array( 'PalingRevisi' ),
	'Fewestrevisions'           => array( 'PangjarangnaRevisi' ),
	'Shortpages'                => array( 'KacaPondok' ),
	'Longpages'                 => array( 'KacaPanjang' ),
	'Newpages'                  => array( 'KacaAnyar' ),
	'Ancientpages'              => array( 'KacaKolot' ),
	'Deadendpages'              => array( 'KacaBuntu' ),
	'Protectedpages'            => array( 'KacaDikonci' ),
	'Protectedtitles'           => array( 'JudulDikonci' ),
	'Allpages'                  => array( 'SadayaKaca' ),
	'Prefixindex'               => array( 'IndeksAwalan' ),
	'BlockList'                 => array( 'IPDipeungpeuk' ),
	'Specialpages'              => array( 'KacaHusus' ),
	'Contributions'             => array( 'Kontribusi' ),
	'Emailuser'                 => array( 'SurelekPamake' ),
	'Confirmemail'              => array( 'KonfirmasiSurelek' ),
	'Whatlinkshere'             => array( 'NumbuKaDieu' ),
	'Recentchangeslinked'       => array( 'ParobahanPatali' ),
	'Movepage'                  => array( 'PindahkeunKaca' ),
	'Blockme'                   => array( 'PeungpeukKuring' ),
	'Booksources'               => array( 'SumberPustaka' ),
	'Categories'                => array( 'Kategori' ),
	'Export'                    => array( 'Ekspor' ),
	'Version'                   => array( 'Versi' ),
	'Allmessages'               => array( 'SadayaTalatah' ),
	'Block'                     => array( 'PeungpeukIP' ),
	'Undelete'                  => array( 'BolayHapus' ),
	'Import'                    => array( 'Impor' ),
	'Lockdb'                    => array( 'KonciDB' ),
	'Unlockdb'                  => array( 'BukaKonciDB' ),
	'Userrights'                => array( 'HakPamake' ),
	'MIMEsearch'                => array( 'SungsiMIME' ),
	'FileDuplicateSearch'       => array( 'SungsiGambarDuplikat' ),
	'Unwatchedpages'            => array( 'KacaTeuDiawaskeun' ),
	'Listredirects'             => array( 'DaptarAlihan' ),
	'Revisiondelete'            => array( 'HapusRevisi' ),
	'Unusedtemplates'           => array( 'CitakanNganggur' ),
	'Randomredirect'            => array( 'AlihanAcak' ),
	'Mypage'                    => array( 'KacaKuring' ),
	'Mytalk'                    => array( 'ObrolanKuring' ),
	'Mycontributions'           => array( 'KontribusiKuring' ),
	'Listadmins'                => array( 'DaptarKuncen' ),
	'Listbots'                  => array( 'DaptarBot' ),
	'Popularpages'              => array( 'KacaPayu' ),
	'Search'                    => array( 'Sungsi' ),
	'Resetpass'                 => array( 'GantiSandi' ),
	'Withoutinterwiki'          => array( 'TanpaInterwiki' ),
	'MergeHistory'              => array( 'GabungJujutan' ),
	'Filepath'                  => array( 'JalurKoropak' ),
	'Invalidateemail'           => array( 'SurelekTeuKaci' ),
	'Blankpage'                 => array( 'KacaKosong' ),
	'Tags'                      => array( 'Tag' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Garis-handapan tumbu',
'tog-highlightbroken'         => 'Format tumbu pegat <a href="" class="new">kawas kieu</a> (atawa: kawas kieu<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Lempengkeun alinéa',
'tog-hideminor'               => 'Sumputkeun éditan minor dina nu anyar robah',
'tog-hidepatrolled'           => 'Sumputkeun anu geus diroris ti béréndélan nu anyar robah',
'tog-newpageshidepatrolled'   => 'Sumputkeun nu geus diroris tina béréndélan kaca anyar',
'tog-extendwatchlist'         => 'Legaan béréndélan ngarah sakabéh parobahanana kaawaskeun',
'tog-usenewrc'                => 'Nu anyar robah dina wanda séjén (maké JavaScript)',
'tog-numberheadings'          => 'Nomeran lulugu sacara otomatis',
'tog-showtoolbar'             => "Témbongkeun ''toolbar'' édit (JavaScript)",
'tog-editondblclick'          => 'Édit kaca ku klik ganda (JavaScript)',
'tog-editsection'             => 'Tambahkeun tumbu [édit] ngarah bisa ngarobah eusi bab',
'tog-editsectiononrightclick' => 'Fungsikeun ngédit sub-bagean kalawan klik-katuhu dina judul bagean (JavaScript)',
'tog-showtoc'                 => 'Témbongkeun daptar eusi<br />(pikeun kaca nu leuwih ti tilu subjudul)',
'tog-rememberpassword'        => 'Apalkeun login kuring dina ieu panyungsi (pikeun paling lila $1 {{PLURAL:$1|poé|poé}})',
'tog-watchcreations'          => 'Awaskeun kaca jieunan kuring',
'tog-watchdefault'            => 'Tambahkeun kaca nu diédit ku anjeun kana awaskeuneun anjeun',
'tog-watchmoves'              => 'Awaskeun kaca nu dipindahkeun ku kuring',
'tog-watchdeletion'           => 'Awaskeun kaca nu dihapus ku kuring',
'tog-minordefault'            => 'Tandaan sadaya éditan salaku minor luyu jeung ti dituna',
'tog-previewontop'            => 'Témbongkeun sawangan méméh kotak édit (lain sanggeusna)',
'tog-previewonfirst'          => 'Témbongkeun sawangan dina éditan munggaran',
'tog-nocache'                 => 'Tumpurkeun <em>cache</em> kaca',
'tog-enotifwatchlistpages'    => 'Surélékan mun robah',
'tog-enotifusertalkpages'     => 'Mun kaca obrolan kuring robah, béjaan ngaliwatan surélék',
'tog-enotifminoredits'        => 'Béjaan ogé (ngaliwatan surélék) mun aya parobahan leutik dina kacana',
'tog-enotifrevealaddr'        => 'Témbongkeun alamat surélék kuring dina surat émbaran',
'tog-shownumberswatching'     => 'Témbongkeun jumlah nu ngawaskeun',
'tog-oldsig'                  => 'Tandatangan nu geus aya:',
'tog-fancysig'                => 'Témbongkeun paraf salaku wikitext (tanpa tumbu otomatis)',
'tog-externaleditor'          => 'Pigunakeun parabot éditor éksternal ti buhunna',
'tog-externaldiff'            => 'Paké pangbeda éksternal ti buhunna',
'tog-showjumplinks'           => 'Aktifkeun tumbu panyambung "luncat ka"',
'tog-uselivepreview'          => 'Paké pramidang saharita (JavaScript) (ujicoba)',
'tog-forceeditsummary'        => 'Mun kotak ringkesan éditan masih kosong, béjaan!',
'tog-watchlisthideown'        => 'Sumputkeun éditan kuring dina daptar awaskeuneun',
'tog-watchlisthidebots'       => 'Sumputkeun éditan bot dina daptar awaskeuneun',
'tog-watchlisthideminor'      => 'Sumputkeun éditan leutik dina daptar awaskeuneun',
'tog-watchlisthideliu'        => 'Sumputkeun éditan pamaké nu asup log tina daptar awaskeun',
'tog-watchlisthideanons'      => 'Sumputkeun éditan pamaké anonim tina daptar awaskeun',
'tog-watchlisthidepatrolled'  => 'Sumputkeun éditan nu geus diroris tina béréndélan awaskeuneun',
'tog-nolangconversion'        => 'Tumpurkeun konversi varian',
'tog-ccmeonemails'            => 'Kirimkeun ogé salinan surélékna ka alamat kuring',
'tog-diffonly'                => 'Ulah némbongkeun eusi kaca di handapeun béda éditan',
'tog-showhiddencats'          => 'Témbongkeun kategori nyumput',
'tog-noconvertlink'           => 'Non-aktifkeun konvérsi judul tumbu',
'tog-norollbackdiff'          => 'Liwat béda sanggeus malikkeun révisi',

'underline-always'  => 'Salawasna',
'underline-never'   => 'Ulah',
'underline-default' => 'Luyu jeung buhunna panyungsi',

# Font style option in Special:Preferences
'editfont-style'     => 'Gaya aksara dina kotak éditan:',
'editfont-default'   => 'Luyu jeung buhunna panyungsi',
'editfont-monospace' => 'Aksara monospasi',
'editfont-sansserif' => 'Aksara Sans-serif',
'editfont-serif'     => 'Aksara Serif',

# Dates
'sunday'        => 'Minggu',
'monday'        => 'Senén',
'tuesday'       => 'Salasa',
'wednesday'     => 'Rebo',
'thursday'      => 'Kemis',
'friday'        => 'Jumaah',
'saturday'      => 'Saptu',
'sun'           => 'Min',
'mon'           => 'Sen',
'tue'           => 'Sal',
'wed'           => 'Reb',
'thu'           => 'Kem',
'fri'           => 'Jum',
'sat'           => 'Sap',
'january'       => 'Januari',
'february'      => 'Pébruari',
'march'         => 'Maret',
'april'         => 'April',
'may_long'      => 'Méi',
'june'          => 'Juni',
'july'          => 'Juli',
'august'        => 'Agustus',
'september'     => 'Séptémber',
'october'       => 'Oktober',
'november'      => 'Nopémber',
'december'      => 'Désémber',
'january-gen'   => 'Januari',
'february-gen'  => 'Pébruari',
'march-gen'     => 'Maret',
'april-gen'     => 'April',
'may-gen'       => 'Méi',
'june-gen'      => 'Juni',
'july-gen'      => 'Juli',
'august-gen'    => 'Agustus',
'september-gen' => 'Séptémber',
'october-gen'   => 'Oktober',
'november-gen'  => 'Nopémber',
'december-gen'  => 'Désémber',
'jan'           => 'Jan',
'feb'           => 'Péb',
'mar'           => 'Mar',
'apr'           => 'Apr',
'may'           => 'Méi',
'jun'           => 'Jun',
'jul'           => 'Jul',
'aug'           => 'Ags',
'sep'           => 'Sép',
'oct'           => 'Okt',
'nov'           => 'Nop',
'dec'           => 'Dés',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategori|Kategori}}',
'category_header'                => 'Artikel-artikel na kategori "$1"',
'subcategories'                  => 'Subkategori',
'category-media-header'          => 'Média dina kategori "$1"',
'category-empty'                 => "''Kategori ieu, ayeuna teu ngabogaan artikel atawa gambar.''",
'hidden-categories'              => '{{PLURAL:$1|Kategori nyumput|Kategori nyumput}}',
'hidden-category-category'       => 'Kategori nu nyarumput',
'category-subcat-count'          => '{{PLURAL:$2|Ieu kategori ngan boga subkategori di handap.|Kategori ieu ngawengku {{PLURAL:$1|subkategori|$1 subkategori}} ti $2.}}',
'category-subcat-count-limited'  => 'Ieu kategori ngawengku {{PLURAL:$1|subkategori|$1 subkategori}}.',
'category-article-count'         => '{{PLURAL:$2|Ieu kategori ngan ngawengku nu di handap.|{{PLURAL:$1|kaca|$1 kaca}} ti $2 di handap asup kana ieu kategori.}}',
'category-article-count-limited' => '{{PLURAL:$1|Kaca|$1 kaca}} di handap kaasup kana kategori.',
'category-file-count'            => '{{PLURAL:$2|Kategori didieu ngan boga gambar ieu.|Tembongkeun {{PLURAL:$1|gambar|$1 gambar}} nukaasup dina kategori ieu ti kabeh $2.}}',
'category-file-count-limited'    => 'Kategori didieu ngabogaan {{PLURAL:$1|gambar|$1 gambar}} ieu.',
'listingcontinuesabbrev'         => '(samb.)',
'index-category'                 => 'Kaca nu diindéks',
'noindex-category'               => 'Kaca nu teu diindéks',

'mainpagetext'      => "'''''Software'' MediaWiki geus diinstal.'''",
'mainpagedocfooter' => "Mangga tingal ''[http://meta.wikimedia.org/wiki/MediaWiki_localisation documentation on customizing the interface]'' jeung [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide Tungtunan Pamaké] pikeun pitulung maké jeung konfigurasi.",

'about'         => 'Ngeunaan',
'article'       => 'Kaca eusi',
'newwindow'     => '(buka na jandéla anyar)',
'cancel'        => 'Bolay',
'moredotdotdot' => 'Deui...',
'mypage'        => 'Kaca kuring',
'mytalk'        => 'Obrolan kuring',
'anontalk'      => 'Obrolan pikeun IP ieu',
'navigation'    => 'Pituduh',
'and'           => '&#32;jeung',

# Cologne Blue skin
'qbfind'         => 'Panggihan',
'qbbrowse'       => 'Sungsi',
'qbedit'         => 'Édit',
'qbpageoptions'  => 'Kaca ieu',
'qbpageinfo'     => 'Kontéks',
'qbmyoptions'    => 'Kaca kuring',
'qbspecialpages' => 'Kaca husus',
'faq'            => 'NLD',
'faqpage'        => 'Project:NLD',

# Vector skin
'vector-action-addsection' => 'Jieun jejer anyar',
'vector-action-delete'     => 'Hapus',
'vector-action-move'       => 'Pindahkeun',
'vector-action-protect'    => 'Konci',
'vector-action-undelete'   => 'Bolaykeun ngahapus',
'vector-action-unprotect'  => 'Buka konci',
'vector-view-create'       => 'Jieun',
'vector-view-edit'         => 'Édit',
'vector-view-history'      => 'Témbongkeun jujutan',
'vector-view-view'         => 'Baca',
'vector-view-viewsource'   => 'Témbongkeun sumber',
'actions'                  => 'Peta',
'namespaces'               => 'Spasi ngaran',
'variants'                 => 'Varian',

'errorpagetitle'    => 'Kasalahan',
'returnto'          => 'Balik deui ka $1.',
'tagline'           => 'Ti {{SITENAME}}',
'help'              => 'Pitulung',
'search'            => 'Sungsi',
'searchbutton'      => 'Téang',
'go'                => 'Jung',
'searcharticle'     => 'Jung',
'history'           => 'Jujutan kaca',
'history_short'     => 'Jujutan',
'updatedmarker'     => 'dirobah saprak pamungkas datangna kuring',
'info_short'        => 'Iber',
'printableversion'  => 'Vérsi citakeun',
'permalink'         => 'Tutumbu permanén',
'print'             => 'Citak',
'view'              => 'Tempo',
'edit'              => 'Édit',
'create'            => 'Jieun',
'editthispage'      => 'Édit kaca ieu',
'create-this-page'  => 'Jieun kaca ieu',
'delete'            => 'Hapus',
'deletethispage'    => 'Hapus kaca ieu',
'undelete_short'    => 'Bolaykeun ngahapus {{PLURAL:$1|hiji éditan|$1 éditan}}',
'viewdeleted_short' => 'Témbongkeun {{PLURAL:$1|hiji éditan nu dihapus|$1 éditan nu dihapus}}',
'protect'           => 'Konci',
'protect_change'    => 'robah',
'protectthispage'   => 'Konci kaca ieu',
'unprotect'         => 'Buka konci',
'unprotectthispage' => 'Buka konci kaca ieu',
'newpage'           => 'Kaca anyar',
'talkpage'          => 'Sawalakeun kaca ieu',
'talkpagelinktext'  => 'Obrolan',
'specialpage'       => 'Kaca Husus',
'personaltools'     => 'Parabot pribadi',
'postcomment'       => 'Bagéan anyar',
'articlepage'       => 'Témbongkeun kaca eusi',
'talk'              => 'Sawala',
'views'             => 'Témbongan',
'toolbox'           => 'Kotak parabot',
'userpage'          => 'Témbongkeun kaca pamaké',
'projectpage'       => 'Témbongkeun kaca proyék',
'imagepage'         => 'Tempo kaca gambar',
'mediawikipage'     => 'Témbongkeun kaca talatah',
'templatepage'      => 'Témbongkeun kaca citakan',
'viewhelppage'      => 'Témbongkeun kaca pitulung',
'categorypage'      => 'Tempo kaca kategori',
'viewtalkpage'      => 'Témbongkeun sawala',
'otherlanguages'    => 'Dina séjén basa',
'redirectedfrom'    => '(Dialihkeun ti $1)',
'redirectpagesub'   => 'Kaca alihan',
'lastmodifiedat'    => 'Kaca ieu panungtungan dirobah $2, $1.',
'viewcount'         => 'Kaca ieu geus dibuka {{PLURAL:$1|sakali|$1 kali}}.<br />',
'protectedpage'     => 'Kaca nu dikonci',
'jumpto'            => 'Luncat ka:',
'jumptonavigation'  => 'pituduh',
'jumptosearch'      => 'sungsi',
'view-pool-error'   => 'Punten, serverna keur pinuh.
Loba teuing nu nyoba muka ieu kaca.
Mangga cobian sanés waktos.

$1',
'pool-errorunknown' => 'Éror teu dipikawanoh',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Ngeunaan {{SITENAME}}',
'aboutpage'            => 'Project:Ngeunaan',
'copyright'            => 'Sadaya kandungan ieu loka ditangtayungan ku $1',
'copyrightpage'        => '{{ns:project}}:Hak cipta',
'currentevents'        => 'Keur lumangsung',
'currentevents-url'    => 'Project:Keur lumangsung',
'disclaimers'          => 'Bantahan',
'disclaimerpage'       => 'Project:Bantahan_umum',
'edithelp'             => 'Pitulung ngédit',
'edithelppage'         => 'Help:Ngédit',
'helppage'             => 'Help:Pitulung',
'mainpage'             => 'Tepas',
'mainpage-description' => 'Tepas',
'policy-url'           => 'Project:Kawijakan',
'portal'               => 'Panglawungan',
'portal-url'           => 'Project:Panglawungan',
'privacy'              => 'Kawijakan privasi',
'privacypage'          => 'Project:Kawijakan privasi',

'badaccess'        => 'Kasalahan widi',
'badaccess-group0' => 'Anjeun teu wenang ngalaksanakeun peta nu dipundut.',
'badaccess-groups' => 'Peta nu dipundut ngan bisa laksana pikeun pamaké ti salah sahiji {{PLURAL:$2|gorombolan|salah sahiji gorombolan}}: $1.',

'versionrequired'     => 'Butuh MediaWiki vérsi $1',
'versionrequiredtext' => 'Butuh MediaWiki vérsi $1 pikeun migunakeun ieu kaca. Mangga tingal [[Special:Version|kaca vérsi]]',

'ok'                      => 'Heug',
'retrievedfrom'           => 'Disalin ti "$1"',
'youhavenewmessages'      => 'Anjeun boga $1 ($2).',
'newmessageslink'         => 'talatah anyar',
'newmessagesdifflink'     => 'bédana ti nu saméméhna',
'youhavenewmessagesmulti' => 'Anjeun boga talatah anyar di $1',
'editsection'             => 'sunting',
'editold'                 => 'édit',
'viewsourceold'           => 'tempo sumbér',
'editlink'                => 'édit',
'viewsourcelink'          => 'témbongkeun sumber',
'editsectionhint'         => 'Édit bab: $1',
'toc'                     => 'Daptar eusi',
'showtoc'                 => 'témbongkeun',
'hidetoc'                 => 'sumputkeun',
'collapsible-collapse'    => 'Tilepkeun',
'collapsible-expand'      => 'Amparkeun',
'thisisdeleted'           => 'Témbongkeun atawa simpen deui $1?',
'viewdeleted'             => 'Témbongkeun $1?',
'restorelink'             => '$1 {{PLURAL:$1|éditan|éditan}} dihapus',
'feedlinks'               => 'Eupan:',
'feed-invalid'            => 'Tipe paménta asupan henteu pas.',
'feed-unavailable'        => 'Eupan sindikasi teu sayaga',
'site-rss-feed'           => 'Eupan RSS $1',
'site-atom-feed'          => 'Eupan Atom $1',
'page-rss-feed'           => 'Eupan RSS "$1"',
'page-atom-feed'          => 'Eupan Atom "$1"',
'red-link-title'          => '$1 (kaca can aya)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artikel',
'nstab-user'      => 'Kaca pamaké',
'nstab-media'     => 'Média',
'nstab-special'   => 'Husus',
'nstab-project'   => 'Ngeunaan',
'nstab-image'     => 'Gambar',
'nstab-mediawiki' => 'Talatah',
'nstab-template'  => 'Citakan',
'nstab-help'      => 'Pitulung',
'nstab-category'  => 'Kategori',

# Main script and global functions
'nosuchaction'      => 'Teu aya peta kitu',
'nosuchactiontext'  => 'Peta nu diketik na URL teu dipikawanoh ku wiki.
Kadé bisi salah ketik atawa nuturkeun tumbu nu salah.
Hal ieu bisa ogé kulantaran ayana kutu (bug) dina sopwér nu dipaké ku {{SITENAME}}.',
'nosuchspecialpage' => 'Teu aya kaca husus nu kitu',
'nospecialpagetext' => '<strong>Anjeun ménta kaca husus nu teu dipikawanoh.</strong>
Kaca husus anu bener bisa ditempo béréndélanana dina [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Kasalahan',
'databaseerror'        => 'Kasalahan gudang data',
'dberrortext'          => 'Éror rumpaka kueri pangkalan data.
Ieu bisa jadi alatan ayana bug dina sopwérna.
Kueri pangkalan data nu panungtung nyaéta:
<blockquote><tt>$1</tt></blockquote>
ti antara fungsi "<tt>$2</tt>".
Éror ti pangkalan data "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Éror rumpaka kueri pangkalan data.
Kueri pangkalan data nu panungtung nyaéta:
"$1"
ti antara fungsi "$2".
Éror ti pangkalan data "$3: $4".',
'laggedslavemode'      => 'Awas: kandungan kaca bisa baé teu mutahir.',
'readonly'             => 'pangkalan data dikonci',
'enterlockreason'      => 'Asupkeun alesan pikeun ngonci, kaasup kira-kira iraha konci ieu rék dibuka',
'readonlytext'         => 'pangkalan data kiwar keur di konci pikeun éntri anyar sarta parobahan séjénna, meureun pikeun pangropéa pangkalan datarutin, nu satutasna mah bakal normal deui. Kuncén nu ngonci ngécéskeun kieu:

$1',
'missing-article'      => 'Pangkalan data teu manggihan téks tina kaca nu sakuduna aya, nyaéta "$1" $2.

Hal ieu biasana disababkeun ku ayana tumbu béda atawa jujutan heubeul ka hiji kaca nu geus dihapus.

Lamun lain ieu sababna, Anjeun meureun geus manggihan bug dina pakakas lemes.
Mangga laporkeun ha ieu ka salasaurang [[Special:ListUsers/sysop|Kuncén]], bari jeung nyebutkeun alamat URL nu dituju.',
'missingarticle-rev'   => '(révisi#: $1)',
'missingarticle-diff'  => '(Béda: $1, $2)',
'readonly_lag'         => 'Pangkalan datana sacara otomatis dikonci nalika server pangkalan data sekundér disalin kana master',
'internalerror'        => 'Kasalahan internal',
'internalerror_info'   => 'Kasalahan internal: $1',
'fileappenderrorread'  => 'Teu bisa maca "$1" nalika nambahkeun.',
'fileappenderror'      => 'Teu bisa nambahkeun "$1" ka "$2".',
'filecopyerror'        => 'Teu bisa nyalin koropak "$1" ka "$2".',
'filerenameerror'      => 'Teu bisa ngaganti ngaran koropak "$1" jadi "$2".',
'filedeleteerror'      => 'Teu bisa ngahapus koropak "$1".',
'directorycreateerror' => 'Henteu bisa nyieun diréktori "$1".',
'filenotfound'         => 'Teu bisa manggihan koropak "$1".',
'fileexistserror'      => 'Henteu bisa muatkeun koropak "$1": gambar geus aya',
'unexpected'           => 'Peunteun di luar hontalan: "$1"="$2".',
'formerror'            => 'Kasalahan: teu bisa ngirim formulir',
'badarticleerror'      => 'Peta ieu teu bisa dipigawé na kaca ieu.',
'cannotdelete'         => 'Kaca atawa berkas "$1" teu bisa dihapus.
Sigana geus dihapus ku nu séjén.',
'badtitle'             => 'Judul goréng',
'badtitletext'         => 'Judul kaca nu dipénta teu sah, kosong, atawa judul antarbasa atawa antarwikina salah tumbu.',
'perfcached'           => 'Data di handap ieu sindangan sahingga--meureun--teu mutahir:',
'perfcachedts'         => 'Data di handap ieu mah sindangan, panungtungan diropéa téh $1.',
'querypage-no-updates' => 'Pangrobahan ahir ti kaca ieu keur dipaéhkeun. Data anu aya di dieu ayeuna moal dimuat deui.',
'wrong_wfQuery_params' => 'Parameter salah ka wfQuery()<br />Fungsi: $1<br />Pamenta: $2',
'viewsource'           => 'Témbongkeun sumber',
'viewsourcefor'        => 'pikeun $1',
'actionthrottled'      => 'Peta diwates',
'actionthrottledtext'  => 'Salaku tetengger anti-spam, anjeun teu diwenangkeun loba kitu peta dina jangka waktu anu sakitu heureutna. Mangga lajengkeun deui sanggeus sababaraha menit ka payun.',
'protectedpagetext'    => 'Ieu kaca dikonci ngarah teu bisa dirobah.',
'viewsourcetext'       => 'Anjeun bisa némbongkeun sarta nyalin sumber ieu kaca:',
'protectedinterface'   => 'Kaca ieu eusina teks antarmuka pikeun dipaké ku pakakas beyé sarta geus dikunci pikeun ngahindar ti kasalahan.',
'editinginterface'     => "'''Awas:''' Anjeun keur ngédit kaca nu dipaké pikeun nyadiakeun téks antarmuka pikeun sopwérna.
Parobahan kana ieu kaca bakal mangaruhan pidangan antarmuka pikeun pamaké séjén.
Pikeun alihbasa, mangga sumping ka [http://translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net], proyék lokalisasi MediaWiki.",
'sqlhidden'            => '(Pamenta SQL disumputkeun)',
'cascadeprotected'     => 'Kaca ieu geus dikonci ti éditan alatan disartakeun di {{PLURAL:$1|kaca|kaca-kaca}} katut anu geus dikonci kalawan pilihan "runtun": $2',
'namespaceprotected'   => "Anjeun teu ngabogaan hak pikeun ngédit kaca di ngaranspasi '''$1'''.",
'customcssjsprotected' => 'Anjeun teu ngabogaan hak ngédit kaca ieu, alatan ngandung pangaturan pribadi pamaké séjén.',
'ns-specialprotected'  => 'Kaca dina ngaranspasi {{ns:special}} teu bisa di édit.',
'titleprotected'       => "Ieu judul dikonci ku [[User:$1|$1]] kalawan alesan ''$2''.",

# Virus scanner
'virus-badscanner'     => "Kasalahan konfigurasi: panyekén virus teu dipikawanoh: ''$1''",
'virus-scanfailed'     => 'nyekén gagal (kode $1)',
'virus-unknownscanner' => 'antivirus teu dipikawanoh:',

# Login and logout pages
'logouttext'                 => "'''Anjeun ayeuna geus kaluar log.'''

Anjeun bisa tetep migunakeun {{SITENAME}} bari anonim, atawa bisa [[Special:UserLogin|asup log deui]] salaku pamaké nu sarua atawa nu séjén deui.
Mangkahadé, sababaraha kaca bakal tetep némbongkeun saolah-olah anjeun asup log kénéh nepi ka anjeun ngosongkeun ''cache'' panyungsi anjeun.",
'welcomecreation'            => '==Wilujeng sumping, $1!==
Rekening anjeun geus dijieun.
Tong hilap ngarobih [[Special:Preferences|{{SITENAME}} préferénsi]] anjeun.',
'yourname'                   => 'Ngaran pamaké anjeun',
'yourpassword'               => 'Sandi anjeun',
'yourpasswordagain'          => 'Ketik deui sandi anjeun',
'remembermypassword'         => 'Apalkeun login kuring dina ieu komputer (pikeun paling lila $1 {{PLURAL:$1|poé|poé}})',
'securelogin-stick-https'    => 'Terus nyambung ka HTTPS sanggeus login',
'yourdomainname'             => 'Domain anjeun',
'externaldberror'            => 'Aya kasalahan dina pangkalan data oténtikasi luar, atawa anjeun mémang teu diwenangkeun pikeun ngaropéa rekening luar anjeun.',
'login'                      => 'Asup log',
'nav-login-createaccount'    => 'Nyieun akun/asup log',
'loginprompt'                => "Anjeun kudu boga ''cookies'' sangkan bisa asup log ka {{SITENAME}}.",
'userlogin'                  => 'Asup log / jieun rekening',
'userloginnocreate'          => 'Asup log',
'logout'                     => 'Kaluar log',
'userlogout'                 => 'Kaluar log',
'notloggedin'                => 'Can asup log',
'nologin'                    => "Teu gaduh rekening? '''$1'''.",
'nologinlink'                => 'Jieun rekening',
'createaccount'              => 'Jieun rekening anyar',
'gotaccount'                 => "Geus boga rekening? '''$1'''.",
'gotaccountlink'             => 'Asup log',
'createaccountmail'          => 'ku surélék',
'createaccountreason'        => 'Alesan:',
'badretype'                  => 'Sandi nu diasupkeun teu cocog.',
'userexists'                 => 'Ngaran pamaké nu diasupkeun ku anjeun geus aya nu maké.
Mangga pilih ngaran nu séjén.',
'loginerror'                 => 'Kasalahan asup log',
'createaccounterror'         => 'Teu bisa nyieun rekening: $1',
'nocookiesnew'               => "Rekening pamaké geus dijieun, tapi anjeun can asup log. {{SITENAME}} maké ''cookies'' pikeun ngasupkeun log pamaké. Anjeun boga ''cookies'' nu ditumpurkeun. Mangga fungsikeun, teras asup log migunakeun ngaran pamaké sarta sandi nu anyar.",
'nocookieslogin'             => "{{SITENAME}} migunakeun ''cookies'' pikeun ngasupkeun pamaké kana log. Anjeun boga ''cookies'' nu ditumpurkeun. Mangga pungsikeun sarta cobian deui.",
'noname'                     => 'Anjeun teu nuliskeun ngaran pamaké nu sah.',
'loginsuccesstitle'          => 'Asup log geus hasil',
'loginsuccess'               => 'Anjeun ayeuna geus asup log ka {{SITENAME}} salaku "$1".',
'nosuchuser'                 => 'Euweuh pamaké nu ngaranna "$1".
Ngaran pamaké ngabedakeun hurup kapital.
Pariksa éjahanana, atawa paké formulir di handap pikeun [[Special:UserLogin/signup|nyieun rekening anyar]].',
'nosuchusershort'            => 'Taya pamaké nu ngaranna "<nowiki>$1</nowiki>", pariksa éjahanana!',
'nouserspecified'            => 'Anjeun kudu ngeusian ngaran landihan.',
'login-userblocked'          => 'Ieu pamaké keur dipeungpeuk, teu diwenangkeun asup log.',
'wrongpassword'              => 'Sandi nu diasupkeun teu cocog. Mangga cobian deui.',
'wrongpasswordempty'         => 'Sandina can kaeusian. Cobaan deui!',
'passwordtooshort'           => 'Sandina kudu diwangun ku sahanteuna {{PLURAL:$1|1 karakter|$1 karakter}}.',
'password-name-match'        => 'Sandi anjeun kudu béda ti landihan.',
'password-login-forbidden'   => 'Sandiasma jeung sandina teu bisa dipaké.',
'mailmypassword'             => 'Kirim sandi anyar ngaliwatan surélék',
'passwordremindertitle'      => 'Pangéling sandi ti {{SITENAME}}',
'passwordremindertext'       => 'Aya (jigana anjeun ti alamat IP $1) nu ménta sangkan dikiriman sandi anyar asup log {{SITENAME}} ($4). Saheulaanan, sandi anyar keur pamaké "$2" ayeuna diganti jadi "$3". Anjeun kudu asup log sarta ngarobah sandi anjeun ayeuna. Ieu sandi bakal kadaluwarsa dina {{PLURAL:$5|sapoé|$5 poé}}.

Mun pamundut ieu datang ti nu séjén, atawa mun anjeun geus inget sandi anu tadina poho, sarta teu hayang ngarobah sandina, anjeun teu kudu ngawaro kana ieu surat sarta bisa tetep maké sandi anu ti heula.',
'noemail'                    => 'Teu aya alamat surélék karékam pikeun "$1".',
'noemailcreate'              => 'Anjeun kudu nyadiakeun alamat surélék anu bener',
'passwordsent'               => 'Sandi anyar geus dikirim ka alamat surélék nu kadaptar pikeun "$1". Mangga asup log deui satutasna katarima.',
'blocked-mailpassword'       => 'Alamat IP anjeun dipeungpeuk, moal bisa ngédit, and so
is not allowed to use the password recovery function to prevent abuse.',
'eauthentsent'               => 'Surélék konfirmasi geus dikirim ka alamat bieu. Méméh aya surat séjén asup ka rekeningna, anjeun kudu nuturkeun pituduh na surélékna pikeun ngonfirmasi yén rekening éta téh bener nu anjeun.',
'throttled-mailpassword'     => 'Hiji panginget kecap sandi geus dikirimkeun dina {{PLURAL:$1|jam|$1 jam}} pamungkas.
Pikeun ngahindar disalahgunakeun, ngan hiji kecap sandi anu baris dikirimkeun saban {{PLURAL:$1|jam|$1 jam}}.',
'mailerror'                  => 'Kasalahan ngirim surat: $1',
'acct_creation_throttle_hit' => 'Punten,pamaké alamat IP anjeun geus nyieun {{PLURAL:$1|1 rekening|$1 rekening}} dina sapoé ieu. mangrupa jumlah nu di idinan dina sapoé.
anjeun teu bisa nyieun deui samentara waktu.',
'emailauthenticated'         => 'Alamat surélék anjeun geus dioténtikasi dina $3, $2.',
'emailnotauthenticated'      => 'Alamat surélék anjeun <strong>can dioténtikasi</strong>. Moal aya surélék nu bakal dikirim pikeun fitur-fitur di handap ieu.',
'noemailprefs'               => 'Teu aya alamat surélék, fitur di handap moal bisa jalan.',
'emailconfirmlink'           => 'Konfirmasi alamat surélék anjeun',
'invalidemailaddress'        => 'Alamat surélék teu bisa ditarima sabab formatna salah.
Mangga lebetkeun alamat nu formatna bener atawa kosongkeun baé.',
'accountcreated'             => 'Rekening geus dijieun.',
'accountcreatedtext'         => 'Rekening pamaké pikeun $1 geus dijieun.',
'createaccount-title'        => 'Nyieun rekening keur {{SITENAME}}',
'createaccount-text'         => 'Aya nu nyieun rekening pikeun alamat surélék anjeun di {{SITENAME}} ($4) maké landihan "$2" sarta sandi "$3". Anjeun kudu asup log sarta ngaganti sandina ayeuna kénéh.

Mun ieu rekening balukar ayana éror, teu kudu diwaro.',
'usernamehasherror'          => "Landihan teu kaci maké karakter pager (''hash'')",
'login-throttled'            => 'Anjeun geus loba teuing nyobaan asup log.
Tungguan heula sakeudeung, laju cobaan deui.',
'loginlanguagelabel'         => 'Basa: $1',

# JavaScript password checks
'password-strength'            => 'Kakuatan sandi: $1',
'password-strength-bad'        => 'AWON',
'password-strength-acceptable' => 'meueusan',
'password-strength-good'       => 'alus',
'password-retype'              => 'Ketik deui sandi anjeun',
'password-retype-mismatch'     => 'Sandi teu cocog',

# Password reset dialog
'resetpass'                 => 'Ganti kecap sandi rekening',
'resetpass_announce'        => 'Anjeun asup log migunakeun sandi samentara. Salajengna, mangga gentos ku sandi anyar di dieu:',
'resetpass_text'            => '<!-- Tambahkeun téks di dieu -->',
'resetpass_header'          => 'Ganti sandi rekening',
'oldpassword'               => 'Sandi heubeul',
'newpassword'               => 'Sandi anyar:',
'retypenew'                 => 'Ketik ulang sandi',
'resetpass_submit'          => 'Setél log asup katut sandina',
'resetpass_success'         => 'Kecap sandi Anjeun geus junun dirobah! Ayeuna proses asup log Anjeun...',
'resetpass_forbidden'       => 'Sandi henteu bisa dirobah',
'resetpass-no-info'         => 'Anjeun kudu asup log pkeun bisa muka ieu kaca sacara langsung.',
'resetpass-submit-loggedin' => 'Ganti kecap sandi rekening',
'resetpass-submit-cancel'   => 'Bolay',
'resetpass-wrong-oldpass'   => 'Salah sandi.
Bisa jadi anjeun geus ngaganti sandina atawa ménta sandi saheulaanan anu anyar.',
'resetpass-temp-password'   => 'Sandi samentara:',

# Edit page toolbar
'bold_sample'     => 'Téks kandel',
'bold_tip'        => 'Téks kandel',
'italic_sample'   => 'Tulisan déngdék',
'italic_tip'      => 'Tulisan déngdék',
'link_sample'     => 'Judul tumbu',
'link_tip'        => 'Tumbu internal',
'extlink_sample'  => 'http://www.example.com Judul tumbu',
'extlink_tip'     => 'Tumbu kaluar (inget awalan http://)',
'headline_sample' => 'Téks judul',
'headline_tip'    => 'Judul tingkat 2',
'math_sample'     => 'Asupkeun rumus di dieu',
'math_tip'        => 'Rumus matematis (LaTeX)',
'nowiki_sample'   => 'Asupkeun téks nu teu diformat di dieu',
'nowiki_tip'      => 'Format wiki tong diwaro',
'image_sample'    => 'Conto.jpg',
'image_tip'       => 'Ngasupkeun gambar',
'media_sample'    => 'Conto.ogg',
'media_tip'       => 'Tumbu koropak média',
'sig_tip'         => 'Tawis leungeun anjeun tur cap wanci',
'hr_tip'          => 'Garis horizontal (use sparingly)',

# Edit pages
'summary'                          => 'Ringkesan:',
'subject'                          => 'Jejer/Judul:',
'minoredit'                        => 'Ieu éditan minor',
'watchthis'                        => 'Awaskeun kaca ieu',
'savearticle'                      => 'Simpen',
'preview'                          => 'Pramidang',
'showpreview'                      => 'Témbongkeun pramidang',
'showlivepreview'                  => 'Pramidang saharita',
'showdiff'                         => 'Témbongkeun parobahan',
'anoneditwarning'                  => "'''Perhatosan:''' Anjeun can asup log. IP anjeun kacatet dina jujutan kaca ieu",
'anonpreviewwarning'               => '"Anjeun can asup log. Mun disimpen, alamat IP anjeun bakal kacatet dina jujutan ieu kaca."',
'missingsummary'                   => "'''Pépéling:''' Anjeun can ngeusian sari éditan. Mun anjeun ngaklik deui Simpen, éditan anjeun bakal disimpen tanpa sari éditan",
'missingcommenttext'               => 'Mangga tulis koméntar di handapeun ieu.',
'missingcommentheader'             => "'''Pépéling:''' Anjeun can ngeusian judul pikeun ieu koméntar. Mun anjeun ngaklik deui Simpen, éditan anjeun bakal disimpen tanpa judul.",
'summary-preview'                  => 'Ringkesan pramidang:',
'subject-preview'                  => 'Sawangan subyek/tajuk:',
'blockedtitle'                     => 'Pamaké dipeungpeuk',
'blockedtext'                      => "'''Ngaran pamaké atawa alamat IP anjeun dipeungpeuk.'''

Dipeungpeuk ku \$1.
Alesanana ''\$2''.

* Mimiti dipeungpeuk : \$8
* dipeungpeuk kadaluarsa dina: \$6
* Sasaran nudipeungpeuk : \$7

Anjeun bisa nepungan \$1 atawa salasahiji [[{{MediaWiki:Grouppage-sysop}}|kuncén]] séjén pikeun nyawalakeun hal ieu.
'''<u>Catet</u>''': yén anjeun teu bisa maké fungsi \"surélékan pamaké ieu\" mun anjeun teu ngadaptarkeun alamat surélék nu sah kana [[Special:Preferences|préferénsi pamaké]] anjeun.

Alamat IP anjeun \$3 jeung ID na #\$5.
Lampirkeun informasi ieu dina unggal ''query'' anjeun.",
'autoblockedtext'                  => 'Alamat IP anjeun otomatis dipeungpeuk sabab dipaké ku pamaké séjén nu geus dipeungpeuk ku $1, kalawan alesan:

:\'\'$2\'\'

*Mimiti dipeungpeuk: $8
*Kadaluwarsa peungpeuk: $6
*Sasaran peungpeuk: $7

Anjeun bisa nepungan $1 atawa [[{{MediaWiki:Grouppage-sysop}}|kuncé]] lianna pikeun ngabadamikeun ieu peungpeukan.

Catet yén anjeun moal bisa migunakeun fitur "surélékan ieu pamaké" mun alamat surélék anu didaptarkeun dina [[Special:Preferences|préferénsi pamaké]]na teu sah, sarta teu dipeungpeuk tina migunakeun ieu fitur.

Alamat IP Anjeun ayeuna nyaéta $3, sarta ID peungpeukan anjeun #$5.
Mangga sebatkeun éta ID dina pamundut-pamundut anjeun.',
'blockednoreason'                  => 'taya alesan',
'blockedoriginalsource'            => "Sumber '''$1''' dipidangkeun di handap ieu:",
'blockededitsource'                => "Tulisan '''éditan anjeun''' dina '''$1''' dipidangkeun di handap ieu:",
'whitelistedittitle'               => 'Perlu asup log sangkan bisa ngédit',
'whitelistedittext'                => 'Anjeun kudu asup $1 sangkan bisa ngédit.',
'confirmedittext'                  => 'Saméméh ngédit, kompirmasikeun heula alamat surélék anjeun.
Mangga setél, lajeng sahkeun alamat surélék anjeun dina [[Special:Preferences|préferénsi pamaké]].',
'nosuchsectiontitle'               => 'Bab teu kapanggih',
'nosuchsectiontext'                => 'Anjeun nyoba ngarobah bagbagan nu sabenerna euweuh.
Bisa jadi geus dipindahkeun atawa geus dihapus nalika anjeun muka kacana.',
'loginreqtitle'                    => 'Kudu asup log',
'loginreqlink'                     => 'asup log',
'loginreqpagetext'                 => 'Mun hayang muka kaca séjénna, Anjeun kudu $1.',
'accmailtitle'                     => 'Sandi geus dikirim.',
'accmailtext'                      => "Sandi acak pikeun [[User talk:$1|$1]] geus dikirimkeun ka $2.

Sandi pikeun rekening anyar ieu bisa diganti dina kaca ''[[Special:ChangePassword|ganti sandi]]'' sanggeus asup log.",
'newarticle'                       => '(Anyar)',
'newarticletext'                   => "Anjeun geus nuturkeun tumbu ka kaca nu can aya.
Pikeun nyieun kaca, mimitian ku ngetik jeroeun kotak di handap
(tempo [[{{MediaWiki:Helppage}}|kaca pitulung]] pikeun leuwih écés).
Mun anjeun ka dieu teu ngahaja, klik baé tombol '''back''' na panyungsi anjeun.",
'anontalkpagetext'                 => "----''Ieu mangrupa kaca sawala pikeun pamaké anonim nu can (henteu) nyieun rekening, kusabab kitu alamat IP dipaké dina hal ieu pikeun nyirikeun anjeunna. Alamat IP ieu bisa dipaké ku sababaraha urang. Mun anjeun salasahiji pamaké anonim sarta ngarasa aya koméntar nu teu pakait geus ditujukeun ka anjeun, leuwih hadé [[Special:UserLogin|nyieun rekening atawa asup log]] sangkan teu pahili jeung pamaké anonim séjén.''",
'noarticletext'                    => 'Kiwari can aya téks dina ieu kaca.
Anjeun bisa [[Special:Search/{{PAGENAME}}|nyusud judul ieu kaca]] dina kaca séjén,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} nyusud log nu tumali],
atawa [{{fullurl:{{FULLPAGENAME}}|action=edit}} ngédit ieu kaca]</span>.',
'noarticletext-nopermission'       => 'Kiwari ieu kaca euweuh eusian.
Anjeun bisa [[Special:Search/{{PAGENAME}}|milari judul ieu kaca]] di kaca séjén,
atawa <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} pilari log nu tumali]</span>.',
'userpage-userdoesnotexist'        => 'Rekening pamaké "$1" tacan kadaptar. Mangga tilikan lamun anjeun hoyong ngadamel/ngédit kaca ieu.',
'userpage-userdoesnotexist-view'   => 'Rekening pamaké "$1" teu aya dina daptar.',
'blocked-notice-logextract'        => 'Ieu pamaké keur dipeungpeuk.
Pikeun rujukan, éntri log panungtung dipidangkeun di handap:',
'clearyourcache'                   => "'''Catetan:''' Sanggeus nyimpen, anjeun perlu ngosongkeun sindangan panyungsi anjeun pikeun nempo parobahanana:
'''Mozilla/Safari/Konqueror:''' pencét & tahan ''Shift'' bari ngaklik ''Reload'' (atawa pencét ''Ctrl-Shift-R''), '''IE:''' pencét ''Ctrl-F5'', '''Opera:''' pencét ''F5''.",
'usercssyoucanpreview'             => "Tip:''' Paké tombol \"{{int:showpreview}}\" pikeun nyobaan CSS anyar anjeun méméh nyimpen.",
'userjsyoucanpreview'              => "Tip:''' Paké tombol \"{{int:showpreview}}\" pikeun nyobaan JavaScript anyar anjeun méméh nyimpen.",
'usercsspreview'                   => "'''Inget yén anjeun ukur nyawang css pamaké anjeun, can disimpen!'''",
'userjspreview'                    => "'''Inget yén anjeun ukur nguji/nyawang ''javascript'' pamaké anjeun, can disimpen!'''",
'sitecsspreview'                   => "'''Inget yén ieu CSS ukur pramidang.'''
'''Can disimpen!'''",
'sitejspreview'                    => "'''Inget yén ieu kodeu JavaScript ukur pramidang.'''
'''Can disimpen!'''",
'userinvalidcssjstitle'            => "'''Awas''': kulit \"\$1\" mah teu aya. Sing émut yén kaca .css jeung .js mah migunakeun aksara leutik dina judulna, contona baé {{ns:user}}:Foo/vector.css lawan {{ns:user}}:Foo/Vector.css.",
'updated'                          => '(Geus diropéa)',
'note'                             => "'''Catetan:'''",
'previewnote'                      => "'''Inget yén ieu ukur sawangan, can disimpen!'''",
'previewconflict'                  => 'Sawangan ieu mangrupa eunteung pikeun téks na rohangan ngédit sakumaha bakal katémbong mun ku anjeun disimpen.',
'session_fail_preview'             => "'''Punten! Kami teu bisa ngolah éditan anjeun alatan leungitna data rintakan. Mangga cobian deui. Mun tetep teu bisa, cobi kaluar log lajeng lebet deui.'''",
'session_fail_preview_html'        => "'''Punten! Kami teu bisa ngolah éditan anjeun sabab leungitna data rintakan.'''

''Kusabab {{SITENAME}} ngawenangkeun dipakéna HTML atah, pramidangna disumputkeun pikeun nyegah panarajang JavaScript.''

'''Mun ieu éditan bener, mangga cobian deui. Mun tetep teu metu, cobi [[Special:UserLogout|kaluar log]] heula, lajeng lebet deui.'''",
'token_suffix_mismatch'            => "'''Éditan anjeun ditolak sabab aplikasi klien Anjeun ngarobah karakter tanda baca dina éditan. Éditan kasebut ditolak keur nyegah kasalahan dina artikel téks. Hal ieu kadang-kadang kajadian lamun Anjeun maké proksi anonim basis web nu masalah.'''",
'editing'                          => 'Ngédit $1',
'editingsection'                   => 'Ngédit $1 (bagian)',
'editingcomment'                   => 'Ngédit $1 (bagian anyar)',
'editconflict'                     => 'Konflik éditan: $1',
'explainconflict'                  => "Aya nu geus ngarobah kaca ieu saprak anjeun mimiti ngédit.
Téks béh luhur ngandung téks kaca nu aya kiwari, parobahan anjeun ditémbongkeun di béh handap.
Anjeun kudu ngagabungkeun parobahan anjeun kana téks nu kiwari.
'''Ngan''' téks nu béh luhur nu bakal disimpen nalika anjeun mencét \"{{int:savearticle}}\".",
'yourtext'                         => 'Tulisan anjeun',
'storedversion'                    => 'Vérsi nu disimpen',
'nonunicodebrowser'                => "'''AWAS: Panyungsi anjeung teu maké unicode, mangga robah heula méméh ngédit artikel.'''",
'editingold'                       => "'''PERHATOSAN: Anjeun ngédit révisi kadaluwarsa kaca ieu. Mun ku anjeun disimpen, sagala parobahan nu dijieun sanggeus révisi ieu bakal leungit.'''",
'yourdiff'                         => 'Béda',
'copyrightwarning'                 => "Perhatikeun yén sadaya kontribusi ka MediaWiki dianggap medal dina panangtayungan lisénsi $2 (tempo $1 pikeun jéntréna). Mun anjeun teu miharep tulisan anjeun dirobah sarta disebarkeun deui, ulah dilebetkeun ka dieu.<br />
Anjeun ogé jangji yén tulisan ieu dijieun ku sorangan, atawa disalin ti ''domain'' umum atawa sumberdaya bébas séjénna. '''ULAH NGASUPKEUN KARYA NU MIBANDA HAK CIPTA TANPA IDIN!'''",
'copyrightwarning2'                => "Catet yén sadaya kontribusi ka {{SITENAME}} bisa diédit, dirobah, atawa dihapus ku kontributor séjén. Mun anjeun teu miharep tulisan anjeun dirobah, ulah ngintunkeun ka dieu.<br />
Anjeun ogé mastikeun yén ieu téh pituin tulisan anjeun, atawa salinan ti domain umum atawa sumberdaya bébas séjénna (tempo $1 pikeun écésna).
'''ULAH NGINTUNKEUN KARYA NU MIBANDA HAK CIPTA TANPA WIDI!'''",
'longpageerror'                    => "'''SALAH: Téks anu dikirimkeun gedéna $1 kb, leuwih ti maksimum $2 kb. Téks teu bisa disimpen.'''",
'readonlywarning'                  => "'''PERHATOSAN''': pangkalan data dikonci pikeun diropéa, anjeun moal bisa nyimpen éditan anjeun ayeuna. Cobi ''cut-n-paste'' téksna ka na koropak téks sarta simpen dina waktu séjén.

kuncén nu ngonci pangkalan data mikeun kajelasan : $1",
'protectedpagewarning'             => "'''AWAS: ieu kaca dikonci sarta ngan bisa dirobah ku pamaké nu statusna kuncén.'''
Éntri log panungtungan ditémbongkeun di handap:",
'semiprotectedpagewarning'         => "'''Catetan''': ieu kaca dikonci sarta ukur bisa dirobah ku pamaké nu geus kadaptar.
Éntri log panungtung dibéréndélkeun di handap:",
'cascadeprotectedwarning'          => "'''Awas''': ieu kaca dikonci sahingga ukur bisa dirobah ku kuncén, sabab kaasup {{PLURAL:$1|kaca|kaca}} dina panyalindungan-ngaruntuy di handap ieu:",
'titleprotectedwarning'            => "'''Awas: ieu kaca dikonci sarta ngan bisa dijieun ku pamaké anu boga [[Special:ListGroupRights|kawenangan husus]].'''
Éntri log panungtung dibéréndélkeun di handap:",
'templatesused'                    => '{{PLURAL:$1|Citakan|Citakan}} nu dipaké dina ieu kaca:',
'templatesusedpreview'             => '{{PLURAL:$1|Citakan|Citakan}} nu dipaké dina ieu pramidang:',
'templatesusedsection'             => '{{PLURAL:$1|Citakan|Citakan}} nu dipaké dina ieu bab:',
'template-protected'               => '(dikunci)',
'template-semiprotected'           => '(semi-dikonci)',
'hiddencategories'                 => 'Ieu kaca kaasup {{PLURAL:$1|1 kategori nyumput|$1 kategori nyumput}}:',
'edittools'                        => '<!-- Téks di dieu bakal némbongan di handapeun formulir édit jeung muat.-->',
'nocreatetitle'                    => 'Nyieun kaca kakara diwatesan',
'nocreatetext'                     => '{{SITENAME}} nutup kabisa nyieun kaca anyar.
Mangga édit artikel nu geus aya, atawa [[Special:UserLogin|asup log/daptar heula]].',
'nocreate-loggedin'                => 'Anjeun teu diwenangkeun pikeun nyieun kaca anyar.',
'sectioneditnotsupported-title'    => 'Teu bisa ngarobah dina sub-judul',
'sectioneditnotsupported-text'     => 'Eusi subjudul di ieu kaca teu bisa diédit.',
'permissionserrors'                => 'Kasalahan Hak Aksés',
'permissionserrorstext'            => 'Anjeung teu boga kawenangan pikeun peta kitu, kalawan {{PLURAL:$1|alesan|alesan}} di handap ieu:',
'permissionserrorstext-withaction' => 'Anjeun teu ngabogaan hak keur $2, kusabab {{PLURAL:$1|alesan|alesan}} katut:',
'recreate-moveddeleted-warn'       => "'''Awas: anjeun keur nyieun deui kaca nu geus kungsi dihapus.'''

Mangga émutan deui perlu/henteuna nyieun deui ieu artikel.
Pikeun leuwih écés, di handap dibéréndélkeun logna:",
'moveddeleted-notice'              => 'Ieu kaca geus dihapus.
Pikeun rujukan, logna tiasa ditingal di handap ieu.',
'log-fulllog'                      => 'Tempo log sakabéhna',
'edit-hook-aborted'                => 'Éditan dibolaykeun tanpa pedaran.',
'edit-gone-missing'                => 'Kaca teu bisa dianyarkeun,
sigana kusabab geus dihapus.',
'edit-conflict'                    => 'Éditan bantrok',
'edit-no-change'                   => 'Éditan anjeun teu diwaro, kusabab taya nu robah dina tulisanana.',
'edit-already-exists'              => 'Teu bisa nyieun kaca anyar.
Artikelna geus aya.',

# Parser/template warnings
'expensive-parserfunction-warning'        => "Inget!: Kaca ieu ngandung réa teuing maké fungsi ''parser''.

Ayeuna aya {{PLURAL:$1|$1 panggilan|$1 panggilan}}, sakuduna kurang ti $2 {{PLURAL:$2|panggilan|panggilan}}.",
'expensive-parserfunction-category'       => 'Kaca kalawan réa teuing maké fungsi parser',
'post-expand-template-inclusion-warning'  => 'Inget! : Ukuran citakan anu dipaké badag teuing.
Sawatara citakan baris teu diasupkeun.',
'post-expand-template-inclusion-category' => 'Eusi kaca citakan anu ukuranna ngaleuwihan wates',
'post-expand-template-argument-warning'   => 'Inget!: Kaca ieu ngandung sahenteuna hiji argumen citakan jeung ukuran ekspansi anu badag teuing. Argumen-argumen kasebut geus diabaikeun.',

# "Undo" feature
'undo-success' => 'Éditan ieu bisa dibolaykeun. Mangga pariksa babandingan di handap pikeun mastikeun mémang anjeun miharep éta parobahan. Mun geus yakin, mangga simpen parobahanana pikeun ngabolaykeun éditan.',
'undo-failure' => 'Éditan teu bisa dibolaykeun alatan kaselang ku éditan séjén.',
'undo-norev'   => 'Éditan ieu henteu bisa bolaykeun alatan kaca henteu kapanggih atawa geus dihapus.',
'undo-summary' => '←Ngabolaykeun révisi $1 ku [[Special:Contributions/$2|$2]] ([[User talk:$2|Obrolan]])',

# Account creation failure
'cantcreateaccounttitle' => 'Rekening teu bisa dijieun',
'cantcreateaccount-text' => "Nyieun rekening ti ieu alamat IP ('''$1''') dipeungpeuk ku [[User:$3|$3]].

Alesanana $3 cenah ''$2''.",

# History pages
'viewpagelogs'           => 'Tempo log kaca ieu',
'nohistory'              => 'Teu aya jujutan édit pikeun kaca ieu.',
'currentrev'             => 'Révisi kiwari',
'currentrev-asof'        => 'Révisi mangsa $1',
'revisionasof'           => 'Révisi nurutkeun $1',
'revision-info'          => 'Révisi per $1; $2',
'previousrevision'       => '← Révisi leuwih heubeul',
'nextrevision'           => 'Révisi nu leuwih anyar →',
'currentrevisionlink'    => 'Témbongkeun révisi kiwari',
'cur'                    => 'kiw',
'next'                   => 'salajengna',
'last'                   => 'ahir',
'page_first'             => 'mimiti',
'page_last'              => 'tung-tung',
'histlegend'             => 'Pilihan béda: tandaan wadah buleud vérsina pikeun ngabandingkeun sarta pencét énter atawa tombol di handap.<br />
Katerangan: (kiw) = bédana jeung vérsi kiwari,
(ahir) = bédana jeung vérsi nu harita, m = éditan minor.',
'history-fieldset-title' => 'Sungsi jujutan',
'history-show-deleted'   => 'Ukur nu dihapus',
'histfirst'              => 'Pangheubeulna',
'histlast'               => 'Pangahirna',
'historysize'            => '($1 {{PLURAL:$1|bit|bit}})',
'historyempty'           => '(kosong)',

# Revision feed
'history-feed-title'          => 'Sajarah révisi',
'history-feed-description'    => 'Sajarah révisi kaca ieu di wiki',
'history-feed-item-nocomment' => '$1 dina $2',
'history-feed-empty'          => 'Kaca nu dipundut teu kapanggih.
Bisa jadi geus dihapus ti wiki atawa diganti ngaranna.
Cobaan [[Special:Search|sungsi di wiki]] pikeun kaca-kaca nu sarimbag.',

# Revision deletion
'rev-deleted-comment'         => '(koméntar dihapus)',
'rev-deleted-user'            => '(ngaran pamaké geus dihapus)',
'rev-deleted-event'           => '(lampah log dihapus)',
'rev-deleted-user-contribs'   => '[Ngaran pamaké atawa alamat IP dihapus - suntingan disumputkeun tina daptar kontribusi]',
'rev-deleted-text-permission' => "Révisi ieu kaca geus '''dihapus'''.
Pedaran lengkepna bisa dipilari di [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log hapusan]",
'rev-deleted-text-unhide'     => "Révisi ieu kaca geus '''dihapus'''.
Lengkepna bisa ditéang dina [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log hapusan].
Salaku kuncén, mun perlu, anjeun bisa kénéh [$1 nempo ieu révisi].",
'rev-deleted-text-view'       => "Révisi ieu kaca geus '''dihapus'''.
Salaku administrator, anjeun angger bisa nempo; pedaran lengkepna bisa dipilari di [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log hapusan].",
'rev-deleted-no-diff'         => "Anjeun teu bisa némbongkeun bédana kusabab révisina geus '''dihapus'''.
Leuwih lengkepna bisa ditéang di [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log hapusan].",
'rev-deleted-unhide-diff'     => "Salahsahiji révisi dina ieu béda geus '''dihapus'''.
Lengkepna bisa dipilari di [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log hapusan].
Salaku kuncen, mun perlu, anjeun bisa kénéh [$1 nempo bédana].",
'rev-delundel'                => 'témbongkeun/sumputkeun',
'rev-showdeleted'             => 'témbongkeun',
'revisiondelete'              => 'Hapus/bolay ngahapus révisi',
'revdelete-nooldid-title'     => 'Udagan révisi salah',
'revdelete-nooldid-text'      => 'Anjeun can nangtukeun atawa méré révisi pikeun ngajalankeun ieu fungsi, révisi nu di tangtukeun can aya, atawa anjeun nyoba nyumputkeun  révisi kiwari.',
'revdelete-nologtype-text'    => 'Anjeun can nangtukeun tipeu log pikeun ieu peta.',
'revdelete-nologid-title'     => 'Éntri log teu bener',
'revdelete-no-file'           => 'Berkas anu ditujul teu kapanggih.',
'revdelete-show-file-confirm' => 'Anjeun yakin rék nempo révisi anu geus dihapus dina koropak "<nowiki>$1</nowiki>" ti $2 nepi ka $3?',
'revdelete-show-file-submit'  => 'Enya',
'revdelete-selected'          => "'''{{PLURAL:$2|Révisi pilihan|Révisi pilihan}} pikeun '''$1''''''",
'logdelete-selected'          => "'''{{PLURAL:$1|pilihan keur log|pilihan keur log}}:'''",
'revdelete-text'              => "'''Revisi sarta tindakan anu geus dihapus baris tetep mecenghul di kaca vérsi tiheula, tapi teks eusi henteu bisa diakses ku publik.'''
Kuncén séjén bakalan bisa ngakses eusi nu nyumput sarta bisa ngabolaykeun hapusan ngaliwatan antarmuka anu sarua, kajaba lamun aya pangbates séjén anu dijieun ku operator loka",
'revdelete-confirm'           => 'Mangga geura konfirmasi yen Anjeun gaduh maksad pikeun ngalakukeun hal ieu, paham kana konsekwensina, tur nu dilakukeun ieu teh luyu sareng [[{{MediaWiki:Policy-url}}|kawijakanana]]',
'revdelete-suppress-text'     => "Nyumputkeun revisi '''ukur''' bisa digunakeun keur kasus-kasus di handap ieu:
* Informasi nu boga potensi mitenah
* Informasi pribadi nu teu pantes
*: ''alamat imah katut nomer telepon, nomer kartu identitas, jeung lian-liana.''",
'revdelete-legend'            => 'Setél réstriksi révisi:',
'revdelete-hide-text'         => 'Sumputkeun téks révisi',
'revdelete-hide-image'        => 'Sumputkeun eusi gambar',
'revdelete-hide-name'         => 'Sumputkeun lampah sarta udagan',
'revdelete-hide-comment'      => 'Sumputkeun koméntar ngédit',
'revdelete-hide-user'         => 'Sumputkeun ngaran pamaké/IP éditor',
'revdelete-hide-restricted'   => 'Sumputkeun data boh ti kuncén atawa nu séjénna',
'revdelete-radio-same'        => '(ulah dirobah)',
'revdelete-radio-set'         => 'Enya',
'revdelete-radio-unset'       => 'Ulah',
'revdelete-suppress'          => 'Sumputkeun ogé ti kuncén',
'revdelete-unsuppress'        => 'Hapus watesan kana révisi anu geus dipulangkeun',
'revdelete-log'               => 'Alesan:',
'revdelete-submit'            => 'Larapkeun kana {{PLURAL:$1|révisi|révisi}} nu dipilih',
'revdelete-logentry'          => 'robah tampilan révisi pikeun [[$1]]',
'logdelete-logentry'          => 'Robah pangatur nyumputkeun tina [[$1]]',
'revdelete-success'           => 'Pangaturan nyumpukeun révisi junun dilarapkeun.',
'logdelete-success'           => 'Log pangatur nyumputkeun junun dilarapkeun.',
'revdel-restore'              => 'robah panémbong',
'revdel-restore-deleted'      => 'révisi nu dihapus',
'revdel-restore-visible'      => 'révisi nu katémbong',
'pagehist'                    => 'Sajarah kaca',
'deletedhist'                 => 'Sajarah nu dihapus',
'revdelete-content'           => 'eusi',
'revdelete-summary'           => 'ringkesan ngédit',
'revdelete-uname'             => 'Landihan:',
'revdelete-restricted'        => 'akses geus dibatesan ukur keur kuncén',
'revdelete-unrestricted'      => 'Watesan akses kuncén dihapuskeun',
'revdelete-hid'               => 'sumputkeun $1',
'revdelete-unhid'             => 'tembongkeun $1',
'revdelete-log-message'       => '$1 Keur $2 {{PLURAL:$2|révisi}}',
'logdelete-log-message'       => '$1 keur $2 {{PLURAL:$2|kajadian|kajadian}}',
'revdelete-hide-current'      => 'Éror nyumputkeun anu titimangsana $2, $1: ieu téh révisi kiwari.
Teu bisa disumputkeun.',
'revdelete-show-no-access'    => 'Éror némbongkeun anu titimangsana $2, $1: geus ditandaan "diwates".
Anjeun teu diwenangkeun asup.',
'revdelete-modify-no-access'  => 'Éror ngarobah anu titimangsana $2, $1: geus ditandaan "diwates".
Anjeun teu diwenangkeun asup.',
'revdelete-modify-missing'    => 'Éror ngarobah anu IDna $2, $1: leungit ti pangkalan data!',
'revdelete-reason-dropdown'   => '*Alesan ngahapus umumna
** Ngarumpak hakcipta
** Émbaran pribadi anu teu perlu
** Émbaranana bisa jadi pitnah',
'revdelete-otherreason'       => 'Alesan lianna:',
'revdelete-reasonotherlist'   => 'Alesan séjén',
'revdelete-edit-reasonlist'   => 'Alesan ngahapus éditan',
'revdelete-offender'          => 'Panulis révisi:',

# Suppression log
'suppressionlog'     => 'Log nyumputken',
'suppressionlogtext' => 'Katut, nyaéta daptar hapusan sarta dipeungpeuk kaasup eusi anu disumputkeun ti para kuncén.
Tempo [[Special:IPBlockList|daptar IP anu dipeungpeuk]] pikeun daptar larangan sarta dipeungpeuk ayeuna.',

# Revision move
'revmove-reasonfield'       => 'Alesan:',
'revmove-norevisions-title' => 'Udagan révisi salah',
'revmove-nullmove-title'    => 'Judul goréng',

# History merging
'mergehistory'                     => 'Gabungkeun jujutan kaca',
'mergehistory-header'              => 'Di dieu anjeun bisa ngagabungkeun révisi tina jujutan hiji kaca ka kaca séjén nu leuwih anyar.
Pastikeun yén ieu parobahan bisa miara jujutan kaca sagemblengna.',
'mergehistory-box'                 => 'Gabungkeun révisi ti dua kaca:',
'mergehistory-from'                => 'Kaca asal :',
'mergehistory-into'                => 'Kaca tujuan:',
'mergehistory-list'                => 'Jujutan édit nu bisa digabungkeun',
'mergehistory-go'                  => 'Témbongkeun éditan nu bisa digabungkeun',
'mergehistory-submit'              => 'Gabungkeun révisi',
'mergehistory-empty'               => 'Euweuh révisi nu bisa digabungkeun.',
'mergehistory-success'             => '$3 {{PLURAL:$3|révisi|révisi}} tina [[:$1]] parantos digabung ka [[:$2]].',
'mergehistory-fail'                => 'Jujutan teu bisa digabungkeun! Mangga pariiksa deui paraméter kaca jeung titimangsana.',
'mergehistory-no-source'           => 'Sumber kaca $1 teu aya.',
'mergehistory-no-destination'      => 'Kaca nu dituju ($1) teu aya.',
'mergehistory-invalid-source'      => 'Kaca sumber kudu sohéh judulna.',
'mergehistory-invalid-destination' => 'Kaca nu dituju judulna kudu sohéh.',
'mergehistory-autocomment'         => '[[:$1]] geus digabungkeun ka [[:$2]]',
'mergehistory-comment'             => '[[:$1]] geus digabungkeun ka [[:$2]]: $3',
'mergehistory-same-destination'    => 'Kaca sumber jeung tujulna teu bisa sarua',
'mergehistory-reason'              => 'Alesan:',

# Merge log
'mergelog'           => 'Log gabung',
'pagemerge-logentry' => '[[$1]] geus ngagabung jeung [[$2]] (révisi nepi ka $3)',
'revertmerge'        => 'Pisahkeun',
'mergelogpagetext'   => 'Di handap ieu béréndélan prosés gabung jujutan kaca.',

# Diffs
'history-title'            => 'Jujutan révisi "$1"',
'difference'               => '(Béda antarrévisi)',
'lineno'                   => 'Baris ka-$1:',
'compareselectedversions'  => 'Bandingkeun vérsi nu dipilih',
'showhideselectedversions' => 'Témbongkeun/sumputkeun révisi nu dipilih',
'editundo'                 => 'bolaykeun',
'diff-multi'               => '({{PLURAL:$1|Hiji|$1}} révisi antara teu ditembongkeun.)',

# Search results
'searchresults'                    => 'Hasil néangan',
'searchresults-title'              => 'Hasil nyusud "$1"',
'searchresulttext'                 => 'Pikeun iber nu leuwih lengkep ngeunaan nyaksrak di {{SITENAME}}, buka [[{{MediaWiki:Helppage}}|Nyaksrak {{SITENAME}}]].',
'searchsubtitle'                   => 'Anjeun nyusud \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|sakabéh kaca nu dimimitian ku "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|sakabéh kaca nu numbu ka "$1"]])',
'searchsubtitleinvalid'            => 'Pikeun pamundut "$1"',
'toomanymatches'                   => 'Loba teuing nu cocog, mangga cobi mundut nu sanésna',
'titlematches'                     => 'Judul artikel nu cocog',
'notitlematches'                   => 'Teu aya judul kaca nu cocog',
'textmatches'                      => 'Téks kaca nu cocog',
'notextmatches'                    => 'Teu aya téks kaca nu cocog',
'prevn'                            => '{{PLURAL:$1|$1}} saméméhna',
'nextn'                            => '{{PLURAL:$1|$1}} salajengna',
'prevn-title'                      => '$1 {{PLURAL:$1|hasil|hasil}} saméméhna',
'nextn-title'                      => '$1 {{PLURAL:$1|hasil|hasil}} salajengna',
'shown-title'                      => 'Témbongkeun $1 {{PLURAL:$1|hasil|hasil}} per kaca',
'viewprevnext'                     => 'Témbongkeun ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Pilihan nyungsi',
'searchmenu-exists'                => "'''Dina wiki ieu geus aya kaca nu ngaranna \"[[:\$1]]\"'''",
'searchmenu-new'                   => "'''Jieun kaca \"[[:\$1]]\" dina ieu wiki!'''",
'searchhelp-url'                   => 'Help:Pitulung',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Sungsi kaca-kaca nu dimimitian ku ieu awalan]]',
'searchprofile-articles'           => 'Kaca eusi',
'searchprofile-project'            => 'Kaca Pitulung jeung Proyék',
'searchprofile-images'             => 'Multimédia',
'searchprofile-everything'         => 'Sagala',
'searchprofile-articles-tooltip'   => 'Pilari di $1',
'searchprofile-project-tooltip'    => 'Pilari di $1',
'searchprofile-images-tooltip'     => 'Pilari koropak/file',
'searchprofile-everything-tooltip' => 'Pilari di sakabéh eusi (kaasup kaca obrolan)',
'search-result-size'               => '$1 ({{PLURAL:$2|1 kecap|$2 kecap}})',
'search-result-score'              => 'Kacocogan: $1%',
'search-redirect'                  => '(alihan $1)',
'search-section'                   => '(bagean $1)',
'search-suggest'                   => 'Meureun maksud Anjeun nyaéta: $1',
'search-interwiki-caption'         => 'Proyék sawargi',
'search-interwiki-default'         => '$1 hasil:',
'search-interwiki-more'            => '(saterusna)',
'search-mwsuggest-enabled'         => 'jeung bongbolongan',
'search-mwsuggest-disabled'        => 'euweuh bongbolongan',
'search-relatedarticle'            => 'Patula-patali',
'mwsuggest-disable'                => 'Tumpurkeun usulan AJAX',
'searcheverything-enable'          => 'Pilari di sakabéh rohangaran',
'searchrelated'                    => 'patula-patali',
'searchall'                        => 'sadayana',
'showingresults'                   => "Di handap ieu némbongkeun {{PLURAL:$1|'''1''' hasil|'''$1''' hasil}}, dimimitianku  #'''$2'''.",
'showingresultsnum'                => "Di handap ieu némbongkeun {{PLURAL:$3|'''1''' hasil|'''$3''' hasil}}, dimimitian #'''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Hasil '''$1''' ti '''$3'''|Hasil '''$1 - $2''' ti '''$3'''}} pikeun '''$4'''",
'nonefound'                        => "'''Catetan''': Ti buhunna (default), panyusud téh ukur ngawengku sababaraha ngaran rohang.
Coba susud dimimitian ku ''all:'' pikeun nyusud sakabéh kandunganana (kaasup kaca obrolan, citakan, jsb.) atawa pilih rohang ngaran dina awalanana luyu jeung nu dimaksud.",
'search-nonefound'                 => 'Euweuh hasil nu cocog jeung kueri.',
'powersearch'                      => 'Sungsi',
'powersearch-legend'               => 'Panéangan tuluy',
'powersearch-ns'                   => 'Téangan di ngaranspasi:',
'powersearch-redir'                => 'Daptar alihan',
'powersearch-field'                => 'Téangan keur',
'powersearch-togglelabel'          => 'Pariksa:',
'powersearch-toggleall'            => 'Kabéh',
'powersearch-togglenone'           => 'Henteu aya',
'search-external'                  => 'Panéangan luar',
'searchdisabled'                   => 'Punten! Néangan téks lengkep di {{SITENAME}} kanggo samentawis ditumpurkeun pikeun alesan kinerja. Jalaran kitu, saheulaanan anjeun bisa nyungsi di Google di handap ieu. Catet yén indéxna ngeunaan eusi {{SITENAME}} bisa jadi teu mutahir.',

# Quickbar
'qbsettings'               => 'Bar gancang',
'qbsettings-none'          => 'Henteu aya',
'qbsettings-fixedleft'     => 'Angger beulah kenca',
'qbsettings-fixedright'    => 'Angger beulah katuhu',
'qbsettings-floatingleft'  => 'Ngambang ka kenca',
'qbsettings-floatingright' => 'Ngambang ka katuhu',

# Preferences page
'preferences'                   => 'Préferénsi',
'mypreferences'                 => 'Préferénsi kuring',
'prefs-edits'                   => 'Jumlah éditan:',
'prefsnologin'                  => 'Can asup log',
'prefsnologintext'              => 'Anjeun kudu <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} asup log]</span> pikeun ngatur préferénsi pamaké.',
'changepassword'                => 'Robah sandi',
'prefs-skin'                    => 'Kulit',
'skin-preview'                  => 'Pramidang',
'prefs-math'                    => 'Maté',
'datedefault'                   => 'Tanpa préferénsi',
'prefs-datetime'                => 'Titimangsa jeung wanci',
'prefs-personal'                => 'Data pamaké',
'prefs-rc'                      => 'Panémbong robahan anyar jeung tukung',
'prefs-watchlist'               => 'Awaskeuneun',
'prefs-watchlist-days'          => 'Jumlah poé anu ditémbongkeun dina daptar awaskeuneun:',
'prefs-watchlist-days-max'      => 'Panglilana 7 poé',
'prefs-watchlist-edits'         => 'Jumlah parobahan maksimum nu ditémbongkeun dina daptar panjang awaskeuneun:',
'prefs-watchlist-edits-max'     => 'Panglobana: 1000',
'prefs-misc'                    => 'Pangaturan rupa-rupa',
'prefs-resetpass'               => 'Ganti sandi',
'prefs-email'                   => 'Pilihan surélék',
'saveprefs'                     => 'Simpen préferénsi',
'resetprefs'                    => 'Sét ulang préferénsi',
'restoreprefs'                  => 'Larapkeun setélan buhun',
'prefs-editing'                 => 'Ukuran kotak téks',
'prefs-edit-boxsize'            => 'Ukuran jandéla édit.',
'rows'                          => 'Baris',
'columns'                       => 'Kolom',
'searchresultshead'             => 'Aturan hasil néang',
'resultsperpage'                => 'Hasil nu ditémbongkeun per kaca',
'contextlines'                  => 'Jumlah baris sakali némbongan',
'contextchars'                  => 'Karakter kontéks per baris',
'stub-threshold'                => 'Wates ambang pikeun format <a href="#" class="stub">tumbu taratas</a> (bit):',
'recentchangesdays'             => 'Jumlah poé nu dipidangkeun dina Nu anyar robah:',
'recentchangesdays-max'         => 'Panglilana $1 {{PLURAL:$1|poé|poé}}',
'recentchangescount'            => 'Jumlah éditan anu ditémbongkeun ceuk bawaanana:',
'prefs-help-recentchangescount' => 'Ieu ngawengku parobahan anyar, jujutan kaca, jeung log.',
'savedprefs'                    => 'Préferénsi anjeun geus disimpen.',
'timezonelegend'                => 'Wewengkon wanci:',
'localtime'                     => 'Wanci satempat:',
'timezoneuseserverdefault'      => 'Paké bawaan server',
'timezoneuseoffset'             => 'Lianna (tangtukeun bédana)',
'timezoneoffset'                => 'Béda:',
'servertime'                    => 'Wanci server:',
'guesstimezone'                 => 'Eusian ti panyungsi',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amérika',
'timezoneregion-antarctica'     => 'Antartika',
'timezoneregion-arctic'         => 'Arktik',
'timezoneregion-asia'           => 'Asia',
'timezoneregion-atlantic'       => 'Samudra Atlantik',
'timezoneregion-australia'      => 'Australia',
'timezoneregion-europe'         => 'Éropah',
'timezoneregion-indian'         => 'Samudra Indonésia',
'timezoneregion-pacific'        => 'Samudra Pasifik',
'allowemail'                    => 'Buka koropak pikeun nampa surélék ti nu séjén',
'prefs-searchoptions'           => 'Piliheun Panéangan',
'prefs-namespaces'              => 'Ngaranspasi',
'defaultns'                     => 'Téang ti antara spasingaran ieu luyu jeung ti dituna:',
'default'                       => 'ti dituna',
'prefs-files'                   => 'Koropak',
'prefs-emailconfirm-label'      => 'Konfirmasi surélék:',
'prefs-textboxsize'             => 'Lega jandéla ngédit',
'youremail'                     => 'Surélék:',
'username'                      => 'Landihan:',
'uid'                           => 'ID pamaké:',
'prefs-memberingroups'          => 'Anggota {{PLURAL:$1|jumplukan|jumplukan}}:',
'yourrealname'                  => 'Ngaran anjeun*',
'yourlanguage'                  => 'Basa antarbeungeut',
'yourvariant'                   => 'Varian basa',
'yournick'                      => 'Landihan anjeun (pikeun tawis leungeun)',
'badsig'                        => 'Parafna teu valid; pariksa tag HTML-na geura.',
'badsiglength'                  => 'Tawis leungeun panjang teuing. Kuduna kurang ti $1 {{PLURAL:$1|karaktér|karaktér}}.',
'gender-male'                   => 'Lalaki',
'gender-female'                 => 'Awéwé',
'email'                         => 'Surélék',
'prefs-help-realname'           => '* Ngaran asli (pilihan): mun anjeun milih ngeusian, bakal dipaké pikeun nandaan kontribusi anjeun.',
'prefs-help-email'              => 'Surélék sipatna pilihan, tapi ngawenangkeun hiji sandi anyar pikeun dikirimkeun ka anjeun lamun anjeun poho kana sandi anjeun. Anjeung ogé bisa milih pikeun ngawenangkeun batur bisa ngontak anjeun tina kaca pamaké atawa obrolanana tanpa kudu nyebutkeun idéntitas anjeun.',
'prefs-help-email-required'     => 'Alamat surélék dibutuhkeun.',
'prefs-info'                    => 'Émbaran dasar',
'prefs-i18n'                    => 'Internasionalisasi',
'prefs-signature'               => 'Tandatangan',
'prefs-dateformat'              => 'Format titimangsa',
'prefs-timeoffset'              => 'Format waktu',
'prefs-diffs'                   => 'Béda',

# User rights
'userrights'                  => 'Manajemén hak pamaké',
'userrights-lookup-user'      => 'Atur gorombolan pamaké',
'userrights-user-editname'    => 'Asupkeun landihan:',
'editusergroup'               => 'Édit Golongan Pamaké',
'editinguser'                 => "Ngarobah hak pamaké '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => 'Édit gorombolan pamaké',
'saveusergroups'              => 'Simpen Grup Pamaké',
'userrights-groupsmember'     => 'Anggota ti:',
'userrights-groups-help'      => 'Anjeun bisa ngarobah jumplukan pamaké ieu:
* Kotak jeung tanda cék mangrupa jumplukan pamaké anu dimaksud
* Kotak tanpa tanda cék hartosna pamaké ieu lain anggota jumplukan kasebut
* Tanda * nandakeun yén Anjeun henteu bisa ngabolaykeun jumplukan kasebut lamun Anjeun geus nambahanana, atawa sabalikna.',
'userrights-reason'           => 'Alesan:',
'userrights-no-interwiki'     => 'Anjeung teu diwenangkeun ngarobah hak pamaké dina wiki séjén.',
'userrights-nodatabase'       => 'Pangkalan data $1 euweuh atawa henteu lokal.',
'userrights-nologin'          => 'Pikeun ngatur hak pamaké, anjeun kudu [[Special:UserLogin|asup log]] migunakeun rekening kuncén.',
'userrights-notallowed'       => 'Rekening anjeun teu diwenangkeun ngatur hak pamaké.',
'userrights-changeable-col'   => 'Jumplukan anu bisa Anjeun robah',
'userrights-unchangeable-col' => 'Jumplukan anu teu bisa Anjeun robah',

# Groups
'group'               => 'Gorombolan:',
'group-user'          => 'Pamaké',
'group-autoconfirmed' => 'Pamaké anu otomatis dikonfirmasi',
'group-bot'           => 'Bot',
'group-sysop'         => 'Kuncén',
'group-bureaucrat'    => 'Birokrat',
'group-suppress'      => 'Oversights',
'group-all'           => '(sadayana)',

'group-user-member'          => 'Pamaké',
'group-autoconfirmed-member' => 'Pamaké anu otomatis dikonfirmasi',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Kuncén',
'group-bureaucrat-member'    => 'Birokrat',
'group-suppress-member'      => 'Oversight',

'grouppage-user'          => '{{ns:project}}:Pamaké',
'grouppage-autoconfirmed' => '{{ns:project}}:Pamaké anu otomatis dikonfirmasi',
'grouppage-bot'           => '{{ns:project}}:Bot',
'grouppage-sysop'         => '{{ns:project}}:Kuncén',
'grouppage-bureaucrat'    => '{{ns:project}}:Birokrat',
'grouppage-suppress'      => '{{ns:project}}:Oversight',

# Rights
'right-read'             => 'Maca kaca',
'right-edit'             => 'Ngédit kaca',
'right-createpage'       => 'Nyieun kaca anyar (nu lain kaca obrolan)',
'right-createtalk'       => 'Nyieun kaca obrolan',
'right-createaccount'    => 'Nyieun rekening anyar',
'right-minoredit'        => 'Nandaan éditan minor',
'right-move'             => 'Mindahkeun kaca',
'right-move-subpages'    => 'Pindahkeun kaca katut sakabéh subkacana',
'right-movefile'         => 'Mindahkeun berkas',
'right-suppressredirect' => 'Henteu nyieun hiji alihan ti ngaran lila sabot mindahkeun kaca',
'right-upload'           => 'Muatkeun koropak',
'right-reupload'         => 'Nimpah koropak nu geus aya',
'right-reupload-own'     => 'Nimpah koropak nu geus aya nu dimuat ku sorangan',
'right-reupload-shared'  => 'Nampik gambar-gambar dina média lokal babarengan',
'right-upload_by_url'    => 'Muatkeun koropak ti hiji alamat URL',
'right-purge'            => 'Ngahapus sindangan tina kaca tanpa kaca konfirmasi',
'right-autoconfirmed'    => 'Ngédit kaca nu semi dikonci',
'right-writeapi'         => 'Maké nulis API',
'right-delete'           => 'Ngahapus kaca',
'right-bigdelete'        => 'Ngahapus kaca nu loba vérsina',
'right-browsearchive'    => 'Sungsi kaca nu geus dihapus',
'right-undelete'         => 'Balikeun deui kaca',
'right-suppressionlog'   => 'Nempo log privat',
'right-block'            => 'Peungpeuk pamaké lain tina ngédit',
'right-blockemail'       => 'Halangan pamaké keur ngirim Surélék',
'right-hideuser'         => 'Peungpeuk pamaké, tong ditingalikeun ka nulain',
'right-proxyunbannable'  => 'Abaikeun pengpeuk otomatis keur proxy',
'right-editinterface'    => 'Édit antarbenget pamaké',
'right-editusercssjs'    => 'Édit berkas CSS jeung JS pamaké séjén',
'right-editusercss'      => 'Édit berkas CSS pamaké séjén',
'right-edituserjs'       => 'Ngédit berkas JS pamaké séjén',
'right-import'           => 'Ngimpor kaca ti wiki séjén',
'right-importupload'     => 'Ngimpor kaca tina hiji koropak nu dimuat',
'right-patrol'           => 'Nandaan éditan pamaké séjén minangka geus dipatroli',
'right-autopatrol'       => 'Ngédit kalayan status éditan sacara otomatis ditandaan geus dipatroli',
'right-patrolmarks'      => 'Tempo panandaan patroli nuanyar robah',
'right-mergehistory'     => 'Ngagabungkeun jujutan kaca',
'right-userrights'       => 'Édit kabeh hak pamaké',
'right-sendemail'        => 'Kirim surélék ka pamaké séjén',

# User rights log
'rightslog'      => 'Log hak pamaké',
'rightslogtext'  => 'Ieu mangrupa log parobahan hak-hak pamaké.',
'rightslogentry' => 'ngarobah kaanggotaan grup pikeun $1 tina $2 jadi $3',
'rightsnone'     => '(euweuh)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'             => 'maca ieu kaca',
'action-edit'             => 'édit kaca ieu',
'action-createpage'       => 'mitembeyan kaca anyar',
'action-createtalk'       => 'mitembeyan kaca obrolan',
'action-createaccount'    => 'nyieun rekening pamaké ieu',
'action-minoredit'        => 'nandaan ieu éditan salaku minor',
'action-move'             => 'mindahkeun ieu kaca',
'action-move-subpages'    => 'mindahkeun ieu kaca katut bagian-bagianana',
'action-movefile'         => 'mindahkeun ieu berkas',
'action-upload'           => 'ngamuat ieu berkas',
'action-reupload'         => 'nimpah berkas nu geus aya ieu',
'action-reupload-shared'  => 'nimpah ier berkas dina gudang sadaya',
'action-upload_by_url'    => 'ngamuat ieu berkas ti URL',
'action-writeapi'         => 'maké API',
'action-delete'           => 'ngahapus ieu kaca',
'action-deleterevision'   => 'ngahapus ieu révisi',
'action-deletedhistory'   => 'nempo jujutan anu geus dihapus ti ieu kaca',
'action-browsearchive'    => 'milari kaca nu geus dihapus',
'action-undelete'         => 'ngabolaykeun hapusan ieu kaca',
'action-suppressrevision' => 'mariksa jeung nyimpen deui ieu révisi nyumput',
'action-suppressionlog'   => 'nempo ieu log pribadi',
'action-block'            => 'meungpeuk ieu pamaké tina ngédit',
'action-protect'          => 'ngarobah hambalan konci ieu kaca',
'action-unwatchedpages'   => 'témbongkeun béréndélan kaca nu teu diawaskeun',
'action-mergehistory'     => 'ngagabungkeun jujutan ieu kaca',
'action-userrights'       => 'Édit hak sakabéh pamaké',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|parobahan|parobahan}}',
'recentchanges'                     => 'Anyar robah',
'recentchanges-legend'              => 'Pilihan parobahan anyar',
'recentchangestext'                 => 'Lacak parobahan ka wiki panganyarna na kaca ieu.',
'recentchanges-feed-description'    => 'Manggihan parobahan panganyarna dina wiki di asupan ieu.',
'recentchanges-label-newpage'       => 'Ieu parobahan bakal ngahasillkeun kaca anyar',
'recentchanges-label-minor'         => 'Ieu éditan minor',
'recentchanges-label-bot'           => 'Ieu parobahan dijieun ku bot',
'rcnote'                            => "Di handap ieu {{PLURAL:$1|'''1''' parobahan| '''$1''' parobahan anyar}} dina  {{PLURAL:$2|poé|'''$2''' poé}} ahir, nepi $5, $4.",
'rcnotefrom'                        => 'Di handap ieu parobahan saprak <b>$2</b> (nu ditémbongkeun nepi ka <b>$1</b>).',
'rclistfrom'                        => 'Témbongkeun nu anyar robah nepi ka $1',
'rcshowhideminor'                   => '$1 éditan minor',
'rcshowhidebots'                    => '$1 bot',
'rcshowhideliu'                     => '$1 pamaké nu asup log',
'rcshowhideanons'                   => '$1 pamaké anonim',
'rcshowhidepatr'                    => '$1 éditan kapatroli',
'rcshowhidemine'                    => '$1 éditan kuring',
'rclinks'                           => 'Témbongkeun $1 parobahan ahir dina $2 poé ahir<br />$3',
'diff'                              => 'béda',
'hist'                              => 'juj',
'hide'                              => 'sumputkeun',
'show'                              => 'témbongkeun',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'A',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|ngawaskeun|ngawaskeun}}]',
'rc_categories'                     => 'Watesan nepi ka kategori (dipisah ku "|")',
'rc_categories_any'                 => 'Naon bae',
'newsectionsummary'                 => '/* $1 */ bagean anyar',
'rc-enhanced-expand'                => 'Témbongkeun rincian (butuh JavaScript)',
'rc-enhanced-hide'                  => 'Sumputkeun rincian',

# Recent changes linked
'recentchangeslinked'          => 'Parobahan nu patali',
'recentchangeslinked-feed'     => 'Parobahan nu patali',
'recentchangeslinked-toolbox'  => 'Parobahan nu patali',
'recentchangeslinked-title'    => 'Parobahan patali ka "$1"',
'recentchangeslinked-noresult' => 'Dina selang waktu anu dipénta, euweuh parobahan dina kaca-kaca anu numbu.',
'recentchangeslinked-summary'  => "Ieu kaca husus ngabéréndélkeun parobahan anyar anu numbu ti kaca husus (atawa uesi katagori husus). Kaca anu [[Special:Watchlist|diawaskeun]] némbongan '''kandel'''.",
'recentchangeslinked-page'     => 'Ngaran kaca:',
'recentchangeslinked-to'       => 'Témbongkeun parobahan ka kaca-kaca nu ditumbukeun ka kaca nu dimaksud',

# Upload
'upload'                      => 'Unggah berkas',
'uploadbtn'                   => 'Muatkeun koropak',
'reuploaddesc'                => 'Balik ka formulir muatan.',
'uploadnologin'               => 'Can asup log',
'uploadnologintext'           => 'Anjeun kudu [[Special:UserLogin|asup log]] pikeun ngamuat koropak.',
'upload_directory_read_only'  => 'Diréktori muatan ($1) teu bisa ditulis ku server ramat.',
'uploaderror'                 => 'Kasalahan muat',
'uploadtext'                  => "<strong>HEUP!</strong> Méméh anjeun ngamuat di dieu, pastikeun yén anjeun geus maca sarta tumut ka kawijakan maké gambar.

Mun geus aya koropak na wiki nu ngaranna sarua jeung nu disebutkeun ku anjeun, koropak nu geus lila bakal diganti otomatis. Mangka, iwal ti pikeun ngaropéa hiji koropak, tangtu leuwih hadé mun anjeun mariksa heula bisi koropak nu sarupa geus aya.

Pikeun némbongkeun atawa néang gambar-gambar nu pernah dimuat saméméhna, mangga lebet ka [[Special:FileList|daptar gambar nu dimuat]]. Muatan sarta hapusan kadaptar dina log [[Special:Log/upload|log muatan]].

Paké formulir di handap pikeun ngamuat koropak gambar anyar pikeun ilustrasi kaca anjeun. Na kalolobaan panyungsi, anjeun bakal manggihan tombol \"Sungsi/''Browse''...\", nu bakal nganteur ka dialog muka-koropak nu baku na sistim operasi anjeun. Milih hiji koropak bakal ngeusian ngaran koropakna kana rohangan téks gigireun tombol nu tadi. Anjeun ogé kudu nyontréng kotak nu nandakeun yén anjeun teu ngarumpak hak cipta batur ku dimuatna ieu koropak. Pencét tombol \"Muatkeun/''Upload''\" pikeun ngeréngsékeun muatan. Prosés ieu bisa lila mun anjeun migunakeun sambungan internét nu lambat.

Format nu dianjurkeun nyéta JPEG pikeun gambar fotografik, PNG pikeun hasil ngagambar sarta gambar séjénna, sarta OGG pikeun sora. Pilih ngaran koropak nu déskriptif sangkan teu ngalieurkeun. Pikeun ngasupkeun gambarna na kaca séjén, pigunakeun tumbu dina wujud
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Gambar.jpg]]</nowiki></tt>''' pikeun gambar dina ukuran aslina
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Berkas.ogg]]</nowiki></tt>''' pikeun sora.

Catet yén salaku kaca wiki, nu séjén bisa ngarobah atawa ngahapus muatan anjeun mun maranéhna nganggap ieu saluyu jeung kapentingan proyék, sarta anjeun bisa waé dipeungpeuk ti ngamuat koropak mun anjeun ngaruksak/ngaganggu sistim.",
'upload-permitted'            => 'Tipeu koropak nu diwidian: $1.',
'upload-preferred'            => 'Tipeu koropak nu dianjurkeun: $1.',
'upload-prohibited'           => 'Tipeu koropak nu dicaram: $1.',
'uploadlog'                   => 'log muatan',
'uploadlogpage'               => 'Log_muatan',
'uploadlogpagetext'           => 'Di handap mangrupa daptar muatan koropak nu panganyarna. Titimangsa nu katémbong dumasar titimangsa server.',
'filename'                    => 'Ngaran koropak',
'filedesc'                    => 'Ringkesna',
'fileuploadsummary'           => 'Ringkesan:',
'filereuploadsummary'         => 'Parobahan berkas:',
'filestatus'                  => 'Status hak cipta:',
'filesource'                  => 'Sumber:',
'uploadedfiles'               => 'Koropak nu geus dimuat',
'ignorewarning'               => 'Ulah diwaro, simpen baé koropakna.',
'ignorewarnings'              => 'Tong diwaro panginget naon baé',
'minlength1'                  => 'Ngaran koropak sahanteuna kudu diwangun ku hiji aksara.',
'illegalfilename'             => 'Ngaran koropak "$1" ngandung aksara nu teu diwenangkeun pikeun judul kaca. Mangga gentos ngaranna tur cobi muatkeun deui.',
'badfilename'                 => 'Ngaran gambar geus dirobah jadi "$1".',
'filetype-badmime'            => 'Koropak tipeu MIME "$1" teu meunang dimuatkeun.',
'filetype-bad-ie-mime'        => 'Ieu berkas teu bisa dimuat sabab ku Internet Explorer dianggap "$1" anu dipahing sarta tipeu berkas anu pibahyaeun.',
'filetype-unwanted-type'      => "'''\".\$1\"''' kaasup tipeu koropak nu teu dipiharep.
{{PLURAL:\$3|Nu dianjurkeun nyaéta|Nu dianjurkeun nyaéta}} \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' kaasup tipeu koropak nu teu dicaram.
{{PLURAL:\$3|Nu diwidian nyaéta|Nu diwidian nyaéta}} \$2.",
'filetype-missing'            => 'Koropakna teu boga éksténsi (misalna ".jpg").',
'large-file'                  => 'Hadéna mah koropak nu dimuat téh teu leuwih ti $1 bit; ieu koropak gedéna $2 bit.',
'largefileserver'             => 'Ieu koropak badag teuing, ngaleuwihan wates nu diwenangkeun ku server.',
'emptyfile'                   => "Koropak nu dimuatkeun ku anjeun jigana kosong. Hal ieu bisa jadi alatan sarupaning ''typo'' na ngaran koropakna. Mangga parios deui yén anjeun leres-leres hoyong ngamuat koropak éta.",
'fileexists'                  => "Koropak nu ngaranna kieu geus aya, mangga parios '''<tt>[[:$1]]</tt>''' mun anjeun teu yakin rék ngaganti.
[[$1|thumb]]",
'fileexists-forbidden'        => 'Koropak nu ngaranna ieu geus aya; mangga balik deui sarta muatkeun koropakna maké ngaran nu béda. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => "Koropak nu ngaranna ieu geus aya dina gudang koropak babagi (''shared file repository''); mangga balik deui sarta muatkeun koropak ieu maké ngaran nu béda. [[File:$1|thumb|center|$1]]",
'file-exists-duplicate'       => 'Gambar ieu duplikat sareng {{PLURAL:$1|gambar|gambar}}:',
'uploadwarning'               => 'Pépéling ngamuat',
'savefile'                    => 'Simpen koropak',
'uploadedimage'               => 'ngamuat "[[$1]]"',
'overwroteimage'              => 'Muatkeun koropak nu anyar ti "[[$1]]"',
'uploaddisabled'              => 'Punten, ngamuat ayeuna ditumpurkeun.',
'uploaddisabledtext'          => 'Fungsi ngamuat koropak ditumpurkeun.',
'uploadscripted'              => "Koropak ieu ngandung kode HTML atawa skrip nu bisa dibaca ngaco ku panyungsi ramat (''web browser'').",
'uploadvirus'                 => 'Koropakna ngandung virus! Katrangan: $1',
'upload-source'               => 'Koropak sumber',
'sourcefilename'              => 'Ngaran koropak sumber:',
'sourceurl'                   => 'URL sumber:',
'destfilename'                => 'Ngaran koropak tujuan:',
'upload-maxfilesize'          => 'Ukuran koropak panggedéna: $1',
'upload-description'          => 'Pedaran koropak',
'upload-options'              => 'Pilihan muat',
'watchthisupload'             => 'Awaskeun ieu kaca',
'filewasdeleted'              => 'Ngaran koropak ieu geus di hapus. Anjeun kudu ningali ka $1 sa acan muatkeun koropak deui',
'upload-success-subj'         => 'Ngamuat geus hasil',

'upload-proto-error'  => 'Salah protokol',
'upload-file-error'   => 'Kasalahan internal',
'upload-misc-error'   => 'Kasalahan muat anu teu kanyahoan',
'upload-unknown-size' => 'Ukuran teu kanyahoan',

# img_auth script messages
'img-auth-nofile' => 'Berkas "$1" henteu aya.',
'img-auth-noread' => 'Pamaké teu boga kawenangan maca "$1".',

# HTTP errors
'http-invalid-url'      => 'URL teu bener: $1',
'http-invalid-scheme'   => 'URL anu skémana "$ 1" teu karojong',
'http-host-unreachable' => 'URL teu kahontal.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'  => 'URL teu kahontal',
'upload-curl-error28' => 'Seep waktos kanggo muatkeun',

'license'            => 'Lisénsi:',
'license-header'     => 'Lisénsi',
'nolicense'          => 'Taya nu dipilih',
'license-nopreview'  => '(euweuh pramidang)',
'upload_source_url'  => '(URL nu sohéh sarta bisa dibuka ku umum)',
'upload_source_file' => ' (koropak dina komputer salira)',

# Special:ListFiles
'listfiles_search_for'  => 'Sungsi ngaran média:',
'imgfile'               => 'koropak',
'listfiles'             => 'Daptar gambar',
'listfiles_date'        => 'Titimangsa',
'listfiles_name'        => 'Ngaran',
'listfiles_user'        => 'Pamaké',
'listfiles_size'        => 'Badagna',
'listfiles_description' => 'Pedaran',
'listfiles_count'       => 'Vérsi',

# File description page
'file-anchor-link'          => 'Gambar',
'filehist'                  => 'Sajarah gambar',
'filehist-help'             => 'Klik dina titimangsa pikeun nempo koropak nu aya dina mangsa éta.',
'filehist-deleteall'        => 'hapus kabéh',
'filehist-deleteone'        => 'hapus',
'filehist-revert'           => 'balikeun',
'filehist-current'          => 'kiwari',
'filehist-datetime'         => 'Titimangsa',
'filehist-thumb'            => 'Thumbnail',
'filehist-thumbtext'        => "'Thumbnail' pikeun vérsi mangsa $1",
'filehist-user'             => 'Pamaké',
'filehist-dimensions'       => 'Ukuran',
'filehist-filesize'         => 'Ukuran koropak',
'filehist-comment'          => 'Kamandang',
'filehist-missing'          => 'Berkas leungit',
'imagelinks'                => 'Tumbu koropak',
'linkstoimage'              => 'Kaca ieu  {{PLURAL:$1|numbu|$1 numbu}} ka gambar ieu :',
'nolinkstoimage'            => 'Teu aya kaca nu numbu ka gambar ieu.',
'sharedupload'              => 'Ieu koropak téh ti $1 nu bisa jadi dipaké ku proyék-proyék lianna.',
'uploadnewversion-linktext' => 'ngamuatkeun vérsi anyar koropak ieu',
'shared-repo-from'          => 'ti $1',

# File reversion
'filerevert'                => 'balikkeun $1',
'filerevert-legend'         => 'Balikkeun gambar',
'filerevert-intro'          => "Anjeun keur mulangkeun '''[[Media:$1|$1]]''' ka [vérsi $4, $3, $2].",
'filerevert-comment'        => 'Alesan:',
'filerevert-defaultcomment' => 'Dipulangkeun ka vérsi $2, $1',
'filerevert-submit'         => 'Balikkeun',
'filerevert-success'        => "'''[[Media:$1|$1]]''' geus dipulangkeun ka [vérsi $4, $3, $2].",
'filerevert-badversion'     => 'Euweuh vérsi lokal tiheula ti koropak ieu kalawan cap waktu anu dimaksud.',

# File deletion
'filedelete'                  => 'Ngahapus $1',
'filedelete-legend'           => 'Ngahapus gambar',
'filedelete-intro'            => "Anjeun rék ngahapus koropak '''[[Media:$1|$1]]''' katut sagala jujutanana.",
'filedelete-intro-old'        => "Anjeun keur ngahapus vérsi '''[[Media:$1|$1]]''', [$4 $3, $2].",
'filedelete-comment'          => 'Alesan:',
'filedelete-submit'           => 'Hapus',
'filedelete-success'          => "'''$1''' geus dihapus.",
'filedelete-success-old'      => "Vérsi '''[[Media:$1|$1]]''' $3, $2 geus dihapus.",
'filedelete-nofile'           => "Teu aya '''$1'''.",
'filedelete-nofile-old'       => "Euweuh arsip vérsi '''$1''' nu tetenggerna kawas kitu.",
'filedelete-otherreason'      => 'Alesan séjén/panambah:',
'filedelete-reason-otherlist' => 'Alesan séjén',
'filedelete-reason-dropdown'  => '*Alesan nu ilahar
** Ngarumpak hak cipta
** Koropak geus aya',
'filedelete-edit-reasonlist'  => 'Alesan ngahapus éditan',

# MIME search
'mimesearch'         => 'Sungsi MIME',
'mimesearch-summary' => 'Ieu kaca bisa dipaké nyaring koropak dumasar tipeu MIME-na. Asupan: contenttype/subtype, contona <tt>image/jpeg</tt>.',
'mimetype'           => 'Tipeu MIME:',
'download'           => 'pulut',

# Unwatched pages
'unwatchedpages' => 'Kaca nu teu diawaskeun',

# List redirects
'listredirects' => 'Daptar alihan',

# Unused templates
'unusedtemplates'     => 'Citakan nu teu kapaké',
'unusedtemplatestext' => 'Ieu kaca ngabéréndélkeun sakabéh kaca dina spasi ngaran {{ns:template}} nu teu diwengku ku kaca séjén.
Saméméh ngahapus, pariksa heula bisi aya tumbu ka ieu citakan.',
'unusedtemplateswlh'  => 'tumbu lianna',

# Random page
'randompage'         => 'Kaca acak',
'randompage-nopages' => 'Euweuh kaca dina ieu spasi ngaran "$1".',

# Random redirect
'randomredirect'         => 'Alihan acak',
'randomredirect-nopages' => 'Euweuh alihan dina ieu spasi ngaran "$1".',

# Statistics
'statistics'              => 'Statistik',
'statistics-header-pages' => 'Statistik kaca',
'statistics-header-edits' => 'Statistik éditan',
'statistics-header-views' => 'Statistik pidangan',
'statistics-header-users' => 'Statistik pamaké',
'statistics-header-hooks' => 'Statistika lianna',
'statistics-articles'     => 'Halaman eusi',
'statistics-pages'        => 'Kaca',
'statistics-pages-desc'   => 'Sakabéh kaca di ieu wiki, kaasup kaca obrolan, alihan, jeung nu lianna.',
'statistics-files'        => 'Koropak nu geus dimuat',
'statistics-users-active' => 'Pamaké getol',
'statistics-mostpopular'  => 'Kaca nu pangmindengna dibuka',

'disambiguations'      => 'Kaca disambiguasi',
'disambiguationspage'  => 'Project:Tumbu_ka_kaca_disambiguasi',
'disambiguations-text' => "Kaca-kaca ieu ngabogaan tumbu ka hiji ''kaca disambiguasi''.
Kaca eta sakuduna numbu ka topik-topik anu luyu.<br />
Sahiji kaca dianggap minangka kaca disambiguasi lamun kaca kasebut ngagunakeun citakan anu nyambung ka [[MediaWiki:Disambiguationspage]].",

'doubleredirects'            => 'Alihan ganda',
'doubleredirectstext'        => 'Unggal baris ngandung tumbu ka pangalihan kahiji jeung kadua, kitu ogé téks dina baris kahiji pangalihan kadua, nu biasana méré kaca tujuan nu bener, nu sakuduna ditujul dina pangalihan kahiji.',
'double-redirect-fixed-move' => '[[$1]] geus pindah, dialihkeun ka [[$2]].',

'brokenredirects'        => 'Alihan buntu',
'brokenredirectstext'    => 'Alihan di handap numbu ka kaca nu teu aya:',
'brokenredirects-edit'   => 'édit',
'brokenredirects-delete' => 'hapus',

'withoutinterwiki'         => 'Kaca-kaca tanpa tumbu basa',
'withoutinterwiki-summary' => 'Kaca-kaca di handap ieu teu numbu ka vérsi basa séjén:',
'withoutinterwiki-legend'  => 'Prefiks',
'withoutinterwiki-submit'  => 'Témbongkeun',

'fewestrevisions' => 'Artikel nu pangjarangna dirévisi',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bit|bit}}',
'ncategories'             => '$1 {{PLURAL:$1|kategori|kategori}}',
'nlinks'                  => '$1 {{PLURAL:$1|tumbu|tumbu}}',
'nmembers'                => '$1 {{PLURAL:$1|pamaké|pamaké}}',
'nrevisions'              => '$1 {{PLURAL:$1|révisi|révisi}}',
'nviews'                  => '$1 {{PLURAL:$1|témbongan|témbongan}}',
'specialpage-empty'       => 'Kaca ieu kosong.',
'lonelypages'             => 'Kaca-kaca nunggelis',
'lonelypagestext'         => 'Kaca-kaca di handap ieu teu numbu ti kaca séjén di {{SITENAME}}.',
'uncategorizedpages'      => 'Kaca nu can dikategorikeun',
'uncategorizedcategories' => 'Kategori nu can dikategorikeun',
'uncategorizedimages'     => 'Gambar nu can dikategorikeun',
'uncategorizedtemplates'  => 'Citakan nu can boga kategori',
'unusedcategories'        => 'Kategori nu teu kapaké',
'unusedimages'            => 'Gambar-gambar nu teu kapaké',
'popularpages'            => 'Kaca-kaca kawentar',
'wantedcategories'        => 'Kategori nu dipikabutuh',
'wantedpages'             => 'Kaca nu dipikabutuh',
'wantedfiles'             => 'Berkas nu dipikabutuh',
'mostlinked'              => 'Nu panglobana numbu ka kaca séjén',
'mostlinkedcategories'    => 'Paling loba ditumbukeun ka kategori',
'mostlinkedtemplates'     => 'Citakan nu panglobana ditumbu',
'mostcategories'          => 'Artikel nu paling loba ngandung kategori',
'mostimages'              => 'Nu panglobana numbu ka gambar',
'mostrevisions'           => 'Artikel nu pangmindengna dirévisi',
'prefixindex'             => 'Kabeh kaca maké awalan',
'shortpages'              => 'Kaca-kaca parondok',
'longpages'               => 'Kaca-kaca paranjang',
'deadendpages'            => 'Kaca buntu',
'deadendpagestext'        => 'Kaca-kaca di handap ieu teu numbu ka kaca séjén di {{SITENAME}}:',
'protectedpages'          => 'Kaca-kaca nu dikonci',
'protectedpages-indef'    => 'Ngan pikeun panangtayungan kalawan waktu nuteu kawates',
'protectedpagestext'      => 'Kaca-kaca di handap ieu teu bisa dialihkeun atawa diédit',
'protectedpagesempty'     => 'Dina danget ieu, teu aya kaca nu dikonci dumasar kana ieu paraméter.',
'protectedtitles'         => 'Judul nu dikonci',
'protectedtitlestext'     => 'Judul-judul di handap ieu teu bisa dijieun:',
'protectedtitlesempty'    => 'Dina danget ieu, euweuh judul nu keur dikonci tina paraméter-paraméter éta.',
'listusers'               => 'Daptar pamaké',
'usereditcount'           => '$1 {{PLURAL:$1|édit|édit}}',
'usercreated'             => 'Dijieun ping $1 jam $2',
'newpages'                => 'Kaca anyar',
'newpages-username'       => 'Landihan:',
'ancientpages'            => 'Kaca pangheubeulna',
'move'                    => 'Pindahkeun',
'movethispage'            => 'Pindahkeun kaca ieu',
'unusedimagestext'        => 'Perhatikeun yén jalaloka séjén bisa numbukeun ka hiji gambar ku URL langsung, sahingga masih didaptarkeun di dieu najan sabenerna dipaké.',
'unusedcategoriestext'    => 'Kaca kategori di handap ieu aya, tapi taya artikel nu diasupkeun kana kategori ieu.',
'notargettitle'           => 'Taya tujuleun',
'notargettext'            => 'Anjeun can nangtukeun hiji targét atawa pamaké pikeun migawé sangkan fungsi ieu jalan.',
'nopagetitle'             => 'Kaca anu dituju henteu kapanggih.',
'nopagetext'              => 'Kaca anu Anjeun maksud henteu kapanggih.',
'pager-newer-n'           => '{{PLURAL:$1|leuwih anyar 1|leuwih anyar $1}}',
'pager-older-n'           => '{{PLURAL:$1|leuwih heubeul 1|leuwih heubeul $1}}',
'suppress'                => 'Oversight',

# Book sources
'booksources'               => 'Sumber pustaka',
'booksources-search-legend' => 'Sungsi sumber buku',
'booksources-go'            => 'Jung',
'booksources-text'          => 'Di handap ieu ngabéréndélkeun tumbu ka loka-loka nu ngical buku, boh nu anyar atawa loakan, nu sugan uninga kana buku anu nuju dipilari:',
'booksources-invalid-isbn'  => 'ISBN-na sigana henteu bener; pariksa deui bisi aya salah salin ti sumber aslina.',

# Special:Log
'specialloguserlabel'  => 'Pamaké:',
'speciallogtitlelabel' => 'Judul:',
'log'                  => 'Log',
'all-logs-page'        => 'Sakabéh log umum',
'alllogstext'          => 'Béréndélan sakabéh log nu aya di {{SITENAME}}.
Bisa dipondokkeun ku cara milih tipe log, ngaran pamaké, atawa kaca nu dimaksud.',
'logempty'             => 'Taya item nu cocog dina log.',
'log-title-wildcard'   => 'Téangan judul nu dimimitian ku tulisan ieu',

# Special:AllPages
'allpages'          => 'Sadaya kaca',
'alphaindexline'    => '$1 ka $2',
'nextpage'          => 'Kaca salajengna ($1)',
'prevpage'          => 'Kaca saméméhna ($1)',
'allpagesfrom'      => 'Pintonkeun kaca ti mimiti:',
'allpagesto'        => 'Témbongkeun kaca-kaca nepi ka:',
'allarticles'       => 'Sadaya artikel',
'allinnamespace'    => 'Sadaya kaca (ngaranspasi $1)',
'allnotinnamespace' => 'Sadaya kaca (teu na $1 ngaranspasi)',
'allpagesprev'      => 'Saméméhna',
'allpagesnext'      => 'Salajengna',
'allpagessubmit'    => 'Jung',
'allpagesprefix'    => 'Pintonkeun kaca dimimitian ku:',
'allpagesbadtitle'  => 'Judul kaca nu dibikeun teu bener atawa mibanda awalan antarbasa atawa antarwiki, nu ngandung karakter nu teu bisa dipaké dina judul.',
'allpages-bad-ns'   => '{{SITENAME}} teu boga spasi ngaran "$1".',

# Special:Categories
'categories'                    => 'Kategori',
'categoriespagetext'            => 'Kategori-kategori di handap ieu ngandung kaca & média.
[[Special:UnusedCategories|Kategori nu teu kapaké]] henteu ditémbongkeun.
Baca ogé [[Special:WantedCategories|kategori nu dipikabutuh]].',
'categoriesfrom'                => 'Tembongkeun kategori-kategori dimimitian ku:',
'special-categories-sort-count' => 'ngurut numutkeun jumlah',

# Special:DeletedContributions
'deletedcontributions'             => 'Kontribusi nu dihapus',
'deletedcontributions-title'       => 'Kontribusi nu dihapus',
'sp-deletedcontributions-contribs' => 'kontribusi',

# Special:LinkSearch
'linksearch'       => 'Tumbu kaluar',
'linksearch-pat'   => 'Pola sungsi:',
'linksearch-ns'    => 'Spasi ngaran:',
'linksearch-ok'    => 'Sungsi',
'linksearch-text'  => "''Wildcard'' sarupaning \"*.wikipedia.org\" bisa dipaké.<br />Protokol nu dirojong: <tt>\$1</tt>",
'linksearch-line'  => '$1 ditumbu ti $2',
'linksearch-error' => "''Wildcard'' ngan bisa némbongan dina awal ngaran indung (''host'').",

# Special:ListUsers
'listusersfrom'      => 'Témbongkeun pamaké nu dimimitian ku',
'listusers-submit'   => 'Témbongkeun',
'listusers-noresult' => 'Teu kapendak.',
'listusers-blocked'  => '(diblokir)',

# Special:ActiveUsers
'activeusers'            => 'Béréndélan pamaké nu getol',
'activeusers-hidebots'   => 'Sumputkeun bot',
'activeusers-hidesysops' => 'Sumputkeun kuncén',

# Special:Log/newusers
'newuserlogpage'              => 'Log pamaké anyar',
'newuserlogpagetext'          => 'Di handap ieu béréndélan log pamaké anyar.',
'newuserlog-byemail'          => 'Sandi geus dikirim maké surélék.',
'newuserlog-create-entry'     => 'Pamaké anyar',
'newuserlog-create2-entry'    => 'rekening anyar pikeun $1 geus dijieun',
'newuserlog-autocreate-entry' => 'rekening pamaké dijieun sacara otomatis',

# Special:ListGroupRights
'listgrouprights'          => 'Hak-hak grup pamaké',
'listgrouprights-summary'  => 'Ieu mangrupa daptar jumplukan pamaké anu aya di wiki ieu, kalawan daptar hak aksés maranéhanana.
Émbaran leuwih luyu ngeunaan hak pamaké bisa ditingali di [[{{MediaWiki:Listgrouprights-helppage}}|dieu]].',
'listgrouprights-group'    => 'Jumplukan',
'listgrouprights-rights'   => 'Hak',
'listgrouprights-helppage' => 'Help:Hak Jumplukan',
'listgrouprights-members'  => '(daptar anggota)',
'listgrouprights-addgroup' => 'Tambahan {{PLURAL:$2|grup|grup}}: $1',

# E-mail user
'mailnologin'     => 'Euweuh alamat ngirim',
'mailnologintext' => "Anjeun kudu '''[[Special:UserLogin|asup log]]''' sarta boga alamat surélék nu sah na [[Special:Preferences|préferénsi]] anjeun sangkan bisa nyurélékan pamaké séjén.",
'emailuser'       => 'Surélékan pamaké ieu',
'emailpage'       => 'Surélékan pamaké',
'emailpagetext'   => 'Anjeun bisa maké formulir di handap pikeun ngirim surélék ka ieu pamaké.
Alamat surélék nu diasupkeun kana [[Special:Preferences|préferénsi pamaké anjeun]] bakal katémbong salaku alamat "Ti" dina surélékna, sahingga nu dituju bisa males langsung.',
'defemailsubject' => 'Surélék {{SITENAME}}',
'noemailtitle'    => 'Teu aya alamat surélék',
'noemailtext'     => 'Ieu pamaké ieu teu méré alamat surélék nu sah.',
'emailfrom'       => 'Ti:',
'emailto'         => 'Ka:',
'emailsubject'    => 'Ngeunaan:',
'emailmessage'    => 'Surat:',
'emailsend'       => 'Kirim',
'emailccme'       => 'Tembuskeun surat kuring kana surélék.',
'emailccsubject'  => 'Tembusan surat anjeun keur $1: $2',
'emailsent'       => 'Surélék geus dikirim',
'emailsenttext'   => 'Surélék anjeun geus dikirim.',
'emailuserfooter' => 'Ieu surélék dikirim ku $1 ka $2 migunakeun fungsi "Surélékan pamaké ieu" di {{SITENAME}}.',

# Watchlist
'watchlist'            => 'Awaskeuneun',
'mywatchlist'          => 'Awaskeuneun',
'nowatchlist'          => 'Anjeun teu boga awaskeuneun.',
'watchlistanontext'    => 'Mangga $1 pikeun némbongkeun atawa ngarobah béréndélan awaskeuneun anjeun.',
'watchnologin'         => 'Can asup log',
'watchnologintext'     => 'Anjeun kudu [[Special:UserLogin|asup log]] pikeun ngarobah awaskeuneun.',
'addedwatch'           => 'Geus ditambahkeun ka awaskeuneun',
'addedwatchtext'       => "Kaca \"[[:\$1]]\" geus ditambahkeun ka [[Special:Watchlist|awaskeuneun]] anjeun.
Jaga, parobahan na kaca ieu katut kaca obrolanana bakal dibéréndélkeun di dinya, sarta kacana bakal katémbong '''dikandelan''' dina kaca [[Special:RecentChanges|Nu anyar robah]] sangkan leuwih gampang ngawaskeunana.

<p>Mun jaga anjeun moal deui ngawaskeun parobahan na kaca éta, klik tumbu \"Eureun ngawaskeun\" na lajursisi.",
'removedwatch'         => 'Dikaluarkeun ti awaskeuneun',
'removedwatchtext'     => 'Kaca "[[:$1]]" geus dikaluarkeun tina [[Special:Watchlist|daptar awaskeuneun]] anjeun.',
'watch'                => 'awaskeun',
'watchthispage'        => 'Awaskeun kaca ieu',
'unwatch'              => 'Eureun ngawaskeun',
'unwatchthispage'      => 'Eureun ngawaskeun',
'notanarticle'         => 'Sanés kaca eusi',
'notvisiblerev'        => 'Révisi geus dihapus',
'watchnochange'        => 'Sadaya awaseun anjeun taya nu diédit dina jangka wanci nu ditémbongkeun.',
'watchlist-details'    => 'Aya {{PLURAL:$1|$1 kaca|$1 kaca}} nu ku anjeun diawaskeun, teu kaasup kaca obrolan/sawala.',
'wlheader-enotif'      => '* Pangémbar surélék difungsikeun.',
'wlheader-showupdated' => "* Kaca nu robah ti panungtungan anjeun sindang ditémbongkeun kalawan '''kandel'''",
'watchmethod-recent'   => 'mariksa nu anyar robah na kaca nu diawaskeun',
'watchmethod-list'     => 'mariksa nu anyar robah na kaca nu diawaskeun',
'watchlistcontains'    => 'Anjeun ngawaskeun $1 {{PLURAL:$1|kaca|kaca}}.',
'iteminvalidname'      => "Masalah dina '$1', ngaran teu bener...",
'wlnote'               => "Di handap ieu mangrupa $1 {{PLURAL:$1|robahan|robahan}} ahir salila '''$2''' jam.",
'wlshowlast'           => 'Témbongkeun $1 jam $2 poé $3 ahir',
'watchlist-options'    => 'Pilihan awaskeuneun',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ngawaskeun...',
'unwatching' => 'Eureun ngawaskeun...',

'enotif_mailer'                => 'Surat Émbaran {{SITENAME}}',
'enotif_reset'                 => 'Tandaan sadaya kaca nu geus dilongok',
'enotif_newpagetext'           => 'Kaca ieu anyar.',
'enotif_impersonal_salutation' => 'Pamaké {{SITENAME}}',
'changed'                      => 'geus robah',
'created'                      => 'geus dijieun',
'enotif_subject'               => 'Kaca $PAGETITLE {{SITENAME}} geus $CHANGEDORCREATED ku $PAGEEDITOR',
'enotif_lastvisited'           => 'Tempo $1 pikeun sadaya parobahan ti saprak anjeun ninggalkeun ieu kaca.',
'enotif_lastdiff'              => 'Buka $1 pikeun nempo ieu parobahan.',
'enotif_anon_editor'           => 'pamaké anonim $1',
'enotif_body'                  => 'Sadérék $WATCHINGUSERNAME,

Kaca $PAGETITLE na {{SITENAME}} geus $CHANGEDORCREATED tanggal $PAGEEDITDATE ku $PAGEEDITOR. Mangga tingal {{SERVER}}{{localurl:$PAGETITLE}} pikeun vérsi kiwari.

$NEWPAGE

Ringkesan éditor: $PAGESUMMARY $PAGEMINOREDIT

Kontak éditor:
surat {{SERVER}}{{localurl:Husus:Emailuser|target=$PAGEEDITOR}}
wiki {{SERVER}}{{localurl:Pamaké:$PAGEEDITOR}}

Mun anjeun teu sindang deui ka ieu kaca, parobahan salajengna moal diémbarkeun. Anjeun bisa ogé nyetél deui umbul-umbul pikeun sadaya kaca nu aya na daptar awaseun anjeun.

             Sistim émbaran {{SITENAME}} pikeun anjeun

--
Pikeun ngarobah setélan dabtar awaseun anjeun, sindang ka {{SERVER}}{{localurl:Husus:Watchlist|edit=yes}}

Asupan jeung bantuan salajengna:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Hapus kaca',
'confirm'                => 'Konfirmasi',
'excontent'              => "eusina nu heubeul: '$1'",
'excontentauthor'        => "eusina: '$1' (nu ditulis ku '$2' wungkul)",
'exbeforeblank'          => "eusi méméh dikosongkeun nyéta: '$1'",
'exblank'                => 'kaca ieu kosong',
'delete-confirm'         => 'Hapus "$1"',
'delete-legend'          => 'Hapus',
'historywarning'         => "'''Awas:''' kaca nu rék dihapus mibanda jujutan kira $1  {{PLURAL:$1|révisi|révisi}}:",
'confirmdeletetext'      => 'Anjeun rék ngahapus hiji kaca atawa gambar katut jujutanana tina pangkalan data, mangga yakinkeun yén anjeun mémang niat midamel ieu, yén anjeun ngartos kana sagala konsékuénsina, sarta yén anjeun ngalakukeun ieu saluyu jeung [[{{MediaWiki:Policy-url}}|kawijakan {{SITENAME}}]].',
'actioncomplete'         => 'Peta geus réngsé',
'deletedtext'            => '"<nowiki>$1</nowiki>" geus dihapus. Tempo $2 pikeun rékaman hapusan anyaran ieu.',
'deletedarticle'         => 'ngahapus "[[$1]]"',
'dellogpage'             => 'Log_hapusan',
'dellogpagetext'         => 'Di handap ieu daptar hapusan nu ahir-ahir, sakabéh wanci dumasar wanci server.',
'deletionlog'            => 'log hapusan',
'reverted'               => 'Malikkeun ka révisi nu ti heula',
'deletecomment'          => 'Alesan:',
'deleteotherreason'      => 'Alesan séjén/panambih:',
'deletereasonotherlist'  => 'Alesan séjén',
'deletereason-dropdown'  => '*Alesan ilahar
** Paménta pamaké
** Ngarumpak hak cipta
** Vandalismeu',
'delete-edit-reasonlist' => 'Alesan ngahapus éditan',
'delete-toobig'          => 'Jujutan édit ieu kaca panjang pisan, leuwih ti {{PLURAL:$1|révisi|révisi}}.
Hal ieu teu diwenangkeun pikeun nyegah karuksakan {{SITENAME}} nu teu dihaja.',
'delete-warning-toobig'  => 'Jujutan ieu kaca panjang pisan, leuwih ti{{PLURAL:$1|révisi|révisi}}. Dihapusna ieu kaca bisa ngaruksak jalanna pangkalan data {{SITENAME}}; sing ati-ati.',

# Rollback
'rollback'         => 'Balikkeun éditan',
'rollback_short'   => 'Balikkeun',
'rollbacklink'     => 'balikkeun',
'rollbackfailed'   => 'Gagal malikkeun',
'cantrollback'     => 'Éditan teu bisa dibalikkeun; kontribusi panungtung ngarupakeun hiji-hijina panulis kaca ieu.',
'alreadyrolled'    => 'Teu bisa mulangkeun édit ahir [[$1]] ku [[User:$2|$2]] ([[User talk:$2|Obrolan]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); geus aya nu ngédit atawa mulangkeun kacana.

Édit ahir ku [[User:$3|$3]] ([[User talk:$3|Obrolan]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'      => "Komentar ngéditna: \"''\$1''\".",
'revertpage'       => 'Malikkeun éditan $2, diganti deui ka vérsi ahir ku $1',
'rollback-success' => 'Mulangkeun éditan $1; balik deui ka vérsi panungtung ku $2.',

# Edit tokens
'sessionfailure' => 'Sigana aya masalah jeung termin log anjeun; peta ieu geus dibolaykeun salaku pépéling pikeun ngalawan ayana pangbajak. Mangga pencét "back" jeung muat ulang ti kaca asal anjeun, lajeng cobaan deui.',

# Protect
'protectlogpage'              => 'Log_koncian',
'protectlogtext'              => 'Di handap ieu mangrupa daptar koncian kaca. Tempo [[Special:ProtectedPages|kaca nu dikonci]] pikeun iber leuwih lengkep.',
'protectedarticle'            => 'ngonci $1',
'modifiedarticleprotection'   => 'hambalan koncian "[[$1]]" geus dirobah',
'unprotectedarticle'          => 'muka konci $1',
'protect-title'               => 'Ngonci "$1"',
'prot_1movedto2'              => 'mindahkeun [[$1]] ka [[$2]]',
'protect-legend'              => 'Konfirmasi ngonci',
'protectcomment'              => 'Alesan:',
'protectexpiry'               => 'Kadaluwarsa',
'protect_expiry_invalid'      => 'Waktu kadaluwarsa teu sah.',
'protect_expiry_old'          => 'Waktu kadaluwarsa geus kaliwat.',
'protect-text'                => "Di dieu anjeun bisa nempo sarta ngarobah hambalan pangonci pikeun kaca '''$1'''.",
'protect-locked-blocked'      => "Anjeun teu bisa ngarobah hambalan koncian sabab keur dipeungpeuk. Setélan ayeuna pikeun kaca '''$1''' nyaéta:",
'protect-locked-access'       => "Rekening anjeun teu wenang ngarobah hambalan pangonci kaca. Kaca '''$1''' disetél:",
'protect-cascadeon'           => 'Ieu kaca dikonci sabab kaasup {{PLURAL:$1|kaca nu|kaca-kaca nu}} ngajalankeun pangonci ngaruntuy. Anjeun bisa ngarobah hambalan koncian ieu kaca, tapi ieu moal mangaruhan pangonci ngaruntuyna.',
'protect-default'             => 'Bisa ku sakabeh pamaké',
'protect-fallback'            => 'Kudu aya kawenangan "$1"',
'protect-level-autoconfirmed' => 'Peungpeuk pamaké anyar jeung nu teu daptar',
'protect-level-sysop'         => 'Ngan bisa ku kuncén',
'protect-summary-cascade'     => 'ngaruntuykeun',
'protect-expiring'            => 'kadaluwarsa $1',
'protect-expiry-indefinite'   => 'tanpa wates',
'protect-cascade'             => 'Konci kaca nu kawengku dina ieu kaca (pangonci ngaruntuy).',
'protect-cantedit'            => 'Anjeung teu wenang ngarobah hambalan ngonci ieu kaca.',
'protect-othertime'           => 'Séjén waktu',
'protect-existing-expiry'     => 'Waktu mungkas nu aya: $3, $2',
'protect-otherreason'         => 'Alesan panambah/lianna:',
'protect-otherreason-op'      => 'Alesan séjén',
'protect-dropdown'            => '*Alesan ngonci nu ilahar
** Vandalismeu kamalinaan
** Spamming kamalinaan
** Perang éditan
** Kaca loba pisan diédit',
'protect-edit-reasonlist'     => 'Alesan ngonci éditan',
'protect-expiry-options'      => 'sajam:1 hour,sapoé:1 day,saminggu:1 week,2 minggu:2 weeks,sabulan:1 month,3 bulan:3 months,6 bulan:6 months,sataun:1 year,salilana:infinite',
'restriction-type'            => 'Ngonci:',
'restriction-level'           => 'Hambalan ngonci:',
'minimum-size'                => 'Ukuran minimum',
'maximum-size'                => 'Ukuran maksimum:',
'pagesize'                    => '(bit)',

# Restrictions (nouns)
'restriction-edit'   => 'Édit',
'restriction-move'   => 'Pindahkeun',
'restriction-create' => 'Jieun',
'restriction-upload' => 'Muatkeun koropak',

# Restriction levels
'restriction-level-sysop'         => 'konci rékép',
'restriction-level-autoconfirmed' => 'konci logor',
'restriction-level-all'           => 'sadaya hambalan',

# Undelete
'undelete'                     => 'Simpen deui kaca nu dihapus',
'undeletepage'                 => 'Témbongkeun atawa simpen deui kaca nu geus dihapus',
'undeletepagetitle'            => "'''Béréndélan révisi [[:$1]]''' anu dihapus.",
'viewdeletedpage'              => 'Témbongkeun kaca nu dihapus',
'undeletepagetext'             => '{{PLURAL:$1|Kaca di handap ieu|Aya $1 kaca}}  geus dihapus tapi masih kénéh aya na arsip sarta bisa disimpen deui. Arsip aya kalana dibersihan.',
'undeleterevisions'            => '$1 {{PLURAL:$1|révisi|révisi}} diarsipkeun',
'undeletehistory'              => 'Mun anjeun nyimpen deui kacana, sadaya révisi bakal disimpen deui dina jujutan.
Mun aya kaca anyar nu ngaranna sarua dijieun deui satutasna dihapus, révisi nu disimpen tadi bakal némbongan salaku jujutan nu ti heula, sarta révisi kiwari kaca nu hirup moal otomatis kaganti.',
'undeletehistorynoadmin'       => 'Artikel ieu geus dihapus.
Alesanana bisa dibaca dina katrangan di handap, katut saha waé nu geus ngédit ieu artikel saméméh dihapus.
Téks aktual révisi nu geus dihapus ieu ngan bisa dibuka ku kuncén.',
'undelete-revision'            => 'Révisi nu dihapus ti $1 (dina $5, $4) ku $3:',
'undelete-nodiff'              => 'Euweuh revisi nu lewih lila',
'undeletebtn'                  => 'Simpen deui!',
'undeletelink'                 => 'tempo/pulangkeun',
'undeleteviewlink'             => 'tempo',
'undeletecomment'              => 'Alesan:',
'undeletedarticle'             => 'disimpen "$1"',
'undeletedrevisions'           => '$1 {{PLURAL:$1|révisi|révisi}} disimpen deui',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 révisi|$1 révisi}} jeung {{PLURAL:$2|1 koropak|$2 koropak}} geus dipulangkeun',
'undeletedfiles'               => '$1 {{PLURAL:$1|koropak}} dibalikeun',
'cannotundelete'               => 'Gagal ngabolaykeun hapusan; sigana kapiheulaan ngabolaykeun hapusan ku nu séjén.',
'undeletedpage'                => "'''$1 hasil dibalikeun'''

Tempo [[Special:Log/delete|log hapusan]] keur data ngahapus jeung malikeun.",
'undelete-header'              => 'Tempo [[Special:Log/delete|log hapusan]] pikeun béréndélan kaca nu anyar dihapus.',
'undelete-search-box'          => 'Téang kaca nu dihapus',
'undelete-search-prefix'       => 'Témbongkeun kaca dimimitian ku',
'undelete-search-submit'       => 'Téang',
'undelete-no-results'          => 'Euweuh kaca nu cocog dina arsip hapusan.',
'undelete-filename-mismatch'   => 'Teu bisa ngabolaykeun hapusan révisi koropak nu titimangsana $1: ngaran teu cocog.',
'undelete-bad-store-key'       => 'Teu bisa ngabolaykeun hapusan révisi koropak nu titimangsana $1: leungit méméh dihapus.',
'undelete-cleanup-error'       => 'Éror ngahapus koropak arsip "$1" nu teu kapaké.',
'undelete-missing-filearchive' => 'Gagal mulangkeun arsip koropak ID $1 kusabab teu kapanggih dina pangkalan data. Bisa jadi éta koropak bolay dihapus.',
'undelete-error-short'         => 'Éror ngabolaykeun hapusan: $1',
'undelete-error-long'          => 'Aya éror nalika ngabolaykeun hapusan:

$1',
'undelete-show-file-submit'    => 'Enya',

# Namespace form on various pages
'namespace'      => 'Ngaranspasi:',
'invert'         => 'Balikkeun pilihan',
'blanknamespace' => '(Utama)',

# Contributions
'contributions'       => 'Tulisan pamaké',
'contributions-title' => 'Sumbangan tulisan ti $1',
'mycontris'           => 'Tulisan kuring',
'contribsub2'         => 'Pikeun $1 ($2)',
'nocontribs'          => 'Taya robahan nu kapanggih cocog jeung patokan ieu.',
'uctop'               => ' (tempo)',
'month'               => 'Ti bulan (jeung saméméhna):',
'year'                => 'Ti taun (jeung saméméhna):',

'sp-contributions-newbies'        => 'Témbongkeun kontribusi ti rekening anyar',
'sp-contributions-newbies-sub'    => 'Pikeun rekening anyar',
'sp-contributions-blocklog'       => 'log peungpeuk',
'sp-contributions-deleted'        => 'kontribusi nu dihapus',
'sp-contributions-logs'           => 'log',
'sp-contributions-talk'           => 'obrolan',
'sp-contributions-userrights'     => 'ngatur hak pamaké',
'sp-contributions-blocked-notice' => 'Ieu pamaké keur dipeungpeuk.
Pikeun rujukan, éntri log panungtung dipidangkeun di handap:',
'sp-contributions-search'         => 'Téang kontribusi',
'sp-contributions-username'       => 'Alamat IP atawa landihan:',
'sp-contributions-submit'         => 'Téang',

# What links here
'whatlinkshere'            => 'Nu numbu ka dieu',
'whatlinkshere-title'      => 'Kaca-kaca nu numbu ka "$1"',
'whatlinkshere-page'       => 'Kaca:',
'linkshere'                => "Kaca di handap ieu numbu ka '''[[:$1]]''':",
'nolinkshere'              => "Euweuh kaca nu numbu ka '''[[:$1]]'''.",
'nolinkshere-ns'           => "Euweuh kaca nu numbu ka '''[[:$1]]''' dina namespace nu dipilih.",
'isredirect'               => 'Kaca alihan',
'istemplate'               => 'ku citakan',
'isimage'                  => 'tumbu gambar',
'whatlinkshere-prev'       => '$1 {{PLURAL:$1|saméméhna}}',
'whatlinkshere-next'       => '$1 {{PLURAL:$1|salajengna}}',
'whatlinkshere-links'      => '← tumbu',
'whatlinkshere-hideredirs' => '$1 alihan',
'whatlinkshere-hidetrans'  => '$1 transklusi',
'whatlinkshere-hidelinks'  => '$1 tumbu',
'whatlinkshere-hideimages' => '$1 tumbu gambar',
'whatlinkshere-filters'    => 'Saringan',

# Block/unblock
'blockip'                     => 'Peungpeuk pamaké',
'blockip-title'               => 'Peungpeuk pamaké',
'blockip-legend'              => 'Peungpeuk pamaké',
'blockiptext'                 => 'Paké formulir di handap pikeun meungpeuk aksés nulis ti alamat IP atawa ngaran pamaké husus. Ieu sakuduna ditujukeun pikeun nyegah vandalisme, sarta saluyu jeung [[{{MediaWiki:Policy-url}}|kawijakan]]. Eusi alesan nu jéntré (misal, ngarujuk kaca tinangtu nu geus diruksak).',
'ipadressorusername'          => 'Alamat IP atawa ngaran pamaké',
'ipbexpiry'                   => 'Kadaluarsa',
'ipbreason'                   => 'Alesan:',
'ipbreasonotherlist'          => 'Alesan séjén',
'ipbcreateaccount'            => 'Tong bisa nyieun rekening',
'ipbemailban'                 => 'Henteu kaci pamaké ngirimkeun surélék',
'ipbenableautoblock'          => 'Peungpeuk sacara otomatis alamat IP anu panungtungan dipaké ku pamaké sarta sakabéh alamat IP anu kungsi dipaké.',
'ipbsubmit'                   => 'Peungpeuk pamaké ieu',
'ipbother'                    => 'Waktu séjén',
'ipboptions'                  => '2 jam:2 hours,sapoé:1 day,3 poé:3 days,saminggu:1 week,2 minggu:2 weeks,sabulan:1 month,3 bulan:3 months,6 bulan:6 months,sataun:1 year,tanpa wates:infinite',
'ipbotheroption'              => 'séjénna',
'ipbotherreason'              => 'Alesan séjén/tambahan',
'ipbhidename'                 => 'Sumputkeun ngaran pamaké tina éditan jeung daptar pamaké',
'ipbwatchuser'                => 'Awaskeun kaca pamaké jeung kaca obrolan pamaké ieu',
'badipaddress'                => 'Alamat IP teu sah',
'blockipsuccesssub'           => 'Meungpeuk geus hasil',
'blockipsuccesstext'          => '"$1" dipeungpeuk.
<br />Tempo [[Special:IPBlockList|daptar peungpeuk IP]] pikeun nempoan deui peungpeuk.',
'ipb-edit-dropdown'           => 'Édit alesan meungpeuk',
'ipb-unblock-addr'            => 'Buka peungpeuk $1',
'ipb-unblock'                 => 'Nyabut peungpeuk pamaké atawa alamat IP',
'ipb-blocklist'               => 'Tempo peungpeuk nu diteurapkeun',
'ipb-blocklist-contribs'      => 'Ilubiung $1',
'unblockip'                   => 'Buka peungpeuk pamaké',
'unblockiptext'               => 'Paké formulir di handap pikeun mulangkeun aksés nulis ka alamat IP atawa ngaran pamaké nu saméméhna dipeungpeuk.',
'ipusubmit'                   => 'Buka peungpeuk pikeun pamaké ieu',
'unblocked'                   => 'peungpeuk ka [[User:$1|$1]] geus dicabut',
'unblocked-id'                => 'peungpeuk $1 geus dicabut',
'ipblocklist'                 => 'Daptar alamat IP jeung pamaké nu dipeungpeuk',
'ipblocklist-legend'          => 'Téang pamaké nu dipeungpeuk',
'ipblocklist-submit'          => 'Téang',
'ipblocklist-localblock'      => 'Blok lokal',
'infiniteblock'               => 'tanpa wates',
'expiringblock'               => 'kadaluwarsa $1',
'anononlyblock'               => 'ngan nu teu daptar',
'noautoblockblock'            => 'Otopeungpeuk ditumpurkeun',
'createaccountblock'          => 'nyieun rekening dipeungpeuk',
'emailblock'                  => 'surélek di peungpeuk',
'blocklist-nousertalk'        => 'teu bisa ngarobah kaca obrolan sorangan',
'ipblocklist-empty'           => 'Daptar peungpeuk kosong.',
'ipblocklist-no-results'      => 'Alamat IP atawa landihan pamaké nu dipundut teu dipeungpeuk.',
'blocklink'                   => 'blokir',
'unblocklink'                 => 'buka blokir',
'change-blocklink'            => 'Robah status blokir',
'contribslink'                => 'kontribusi',
'autoblocker'                 => 'Otomatis dipeungpeuk sabab alamat IP anjeun sarua jeung "$1". Alesan "$2".',
'blocklogpage'                => 'Log_peungpeuk',
'blocklog-showlog'            => 'Ieu pamaké saméméhna geus kungsi dipeungpeuk.
Pikeun rujukan, logna dipidangkeun di handap ieu:',
'blocklogentry'               => 'meungpeuk [[$1]] nepi ka $2 $3',
'blocklogtext'                => 'Ieu mangrupa log peta meungpeuk jeung muka peungpeuk pamaké, teu kaasup alamat IP nu dipeungpeukna otomatis. Tempo [[Special:IPBlockList|daptar peungpeuk IP]] pikeun daptar cegahan jeung peungpeuk.',
'unblocklogentry'             => 'peungpeuk dibuka "$1"',
'block-log-flags-anononly'    => 'pamaké anonim wungkul',
'block-log-flags-nocreate'    => 'Nyieun rekening ditumpurkeun',
'block-log-flags-noautoblock' => 'meungpeuk otomatis dipaéhan',
'block-log-flags-noemail'     => 'surélek di peungpeuk',
'block-log-flags-hiddenname'  => 'sandiasma disumputkeun',
'range_block_disabled'        => 'Pangabisa kuncén pikeun nyieun sarupaning peungpeuk geus ditumpurkeun.',
'ipb_expiry_invalid'          => 'Wanci daluwarsa teu bener.',
'ipb_already_blocked'         => '"$1" geus dipeungpeuk',
'ipb_cant_unblock'            => 'Éror: ID peungpeuk $1 teu kapanggih. Sigana mah geus dibuka.',
'ip_range_invalid'            => 'Angka IP teu bener.',
'blockme'                     => 'Peungpeuk kuring',
'proxyblocker'                => 'Pameungpeuk proxy',
'proxyblocker-disabled'       => 'Ieu fungsi keur ditumpurkeun.',
'proxyblockreason'            => "Alamat IP anjeun dipeungpeuk sabab mangrupa proxy muka. Mangga tepungan ''Internet service provider'' atanapi ''tech support'' anjeun, béjakeun masalah serius ieu.",
'proxyblocksuccess'           => 'Réngsé.',
'sorbsreason'                 => "Alamat IP anjeun kadaptar salaku ''open proxy'' dina DNSBL.",
'sorbs_create_account_reason' => "Alamat IP anjeun kadaptar salaku ''open proxy'' dina DNSBL. Anjeun teu bisa nyieun rekening",

# Developer tools
'lockdb'              => 'Konci pangkalan data',
'unlockdb'            => 'Buka konci pangkalan data',
'lockdbtext'          => 'Ngonci gudang data bakal numpurkeun kabisa sakabéh pamaké pikeun ngédit kaca, ngarobah préferénsina, ngédit awaskeuneunana, sarta hal séjén nu merlukeun parobahan na gudang data. Konfirmasikeun yén ieu nu dimaksud ku anjeun, sarta anjeun bakal muka konci gudang data nalika pangropéa anjeun geus réngsé.',
'unlockdbtext'        => 'Muka konci pangkalan data bakal mulangkeun kabisa sakabéh pamaké pikeun ngédit kaca, ngarobah préferénsina, ngédit awaskeuneunana, sarta hal-hal séjén nu merlukeun parobahan na pangkalan data. Pastikeun yén ieu ngarupakeun hal nu diniatkeun ku anjeun.',
'lockconfirm'         => 'Leres pisan, simkuring hoyong ngonci pangkalan data.',
'unlockconfirm'       => 'Muhun, kuring hayang muka konci pangkalan data.',
'lockbtn'             => 'Konci pangkalan data',
'unlockbtn'           => 'Buka konci pangkalan data',
'locknoconfirm'       => 'Anjeun teu nyontréngan kotak konfirmasi.',
'lockdbsuccesssub'    => 'pangkalan data geus hasil dikonci',
'unlockdbsuccesssub'  => 'Konci pangkalan data geus dibuka',
'lockdbsuccesstext'   => 'pangkalan data dikonci.
<br />Ulah poho muka konci mun geus bérés diropéa.',
'unlockdbsuccesstext' => 'pangkalan data geus teu dikonci.',
'databasenotlocked'   => 'Gudang data teu kakonci.',

# Move page
'move-page'                    => 'Pindahkeun $1',
'move-page-legend'             => 'Pindahkeun kaca',
'movepagetext'                 => "Migunakeun formulir di handap bakal ngaganti ngaran hiji kaca, mindahkeun sadaya jujutanana ka ngaran anyar.
Judul nu heubeul bakal jadi kaca alihan ka judul nu anyar.
Anjeun bisa menerkeun alihan nu nujul ka judul asli sacara otomatis.
Mun henteu, pariksa bisi aya [[Special:DoubleRedirects|alihan ganda]] atawa [[Special:BrokenRedirects|buntu]]
Anjeun tanggel waler pikeun mastikeun yén tumbu-tumbu tetep nujul ka tempat nu sakuduna dituju.

Catet yén kacana '''moal''' pindah mun judul nu anyar geus aya eusian, kajaba mangrupa alihan sarta teu mibanda jujutan.
Ieu ngandung harti yén anjeun bisa ngaganti ngaran hiji kaca balik deui ka nu cikénéh diganti ngaranna mun anjeun nyieun kasalahan, sarta anjeun teu bisa nimpah kaca nu geus aya.

'''AWAS!'''
Ieu parobahan bisa gedé pangaruhna mun dilarapkeun kana kaca anu payu;
anjeun kudu bener-bener ngarti kana hal ieu.",
'movepagetalktext'             => "Kaca obrolan nu patali, mun aya, bakal sacara otomatis kapindahkeun, '''iwal:'''
*Anjeun mindahkeun kacana meuntas spasingaran nu béda,
*Kaca obrolan dina ngaran nu anyar geus aya eusian, atawa
*Anjeun teu nyontréngan kotak di handap.

Dina kajadian kitu, mun hayang (jeung perlu) anjeun kudu mindahkeun atawa ngagabungkeun kacana sacara manual.",
'movearticle'                  => 'Pindahkeun kaca',
'movenologin'                  => 'Can asup log',
'movenologintext'              => 'Anjeun kudu jadi pamaké nu kadaptar tur [[Special:UserLogin|asup log]] pikeun mindahkeun kaca.',
'movenotallowed'               => 'Anjeung teu boga kawenangan mindahkeun kaca.',
'movenotallowedfile'           => 'Anjeung teu boga kawenangan mindahkeun kaca.',
'newtitle'                     => 'Ka judul anyar',
'move-watch'                   => 'Awaskeuneun kaca ieu',
'movepagebtn'                  => 'Pindahkeun kaca',
'pagemovedsub'                 => 'Mindahkeun geus hasil!',
'movepage-moved'               => '\'\'\'"$1" geus dipindahkeun ka "$2"\'\'\'',
'articleexists'                => 'Kaca nu ngaranna kitu geus aya, atawa ngaran nu dipilih ku anjeun teu sah. Mangga pilih ngaran séjén.',
'cantmove-titleprotected'      => 'Anjeun teu bisa mindahkeun kaca ka dieu, sabab éta judul dikonci',
'talkexists'                   => 'Kacana geus hasil dipindahkeun, ngan kaca obrolanana teu bisa dipindahkeun sabab geus aya nu anyar na judul anyar. Mangga gabungkeun sacara manual.',
'movedto'                      => 'dipindahkeun ka',
'movetalk'                     => 'Mun bisa, kaca "obrolan" ogé pindahkeun.',
'move-subpages'                => 'Pindahkeun kabéh sub-kaca (nepika $1)',
'move-talk-subpages'           => 'Pindahkeun kabéh sub-kaca obrolan (nepika $1)',
'movepage-page-exists'         => 'Kaca $1 geus aya tur teu bisa ditimpah sacara otomatis.',
'movepage-page-moved'          => 'Kaca $1 geus dipindahkeun ka $2.',
'movepage-page-unmoved'        => 'Kaca $1 teu bisa dipindahkeun ka $2.',
'movepage-max-pages'           => 'Sajumlah maksimum $1 {{PLURAL:$1|kaca|kaca}} geus dipindahkeun tur moal aya deui nu bakal dipindahkeun sacara otomatis.',
'1movedto2'                    => 'mindahkeun [[$1]] ka [[$2]]',
'1movedto2_redir'              => '[[$1]] dipindahkeun ka [[$2]]',
'movelogpage'                  => 'Log mindahkeun',
'movelogpagetext'              => 'Di handap ieu béréndélan kaca nu dipindahkeun.',
'movesubpage'                  => '{{PLURAL:$1|Subkaca|Subkaca}}',
'movesubpagetext'              => 'Ieu kaca mibanda $1 {{PLURAL:$1|subkaca|subkaca}} anu dipidangkeun di handap.',
'movenosubpage'                => 'Ieu kaca teu boga subkaca.',
'movereason'                   => 'Alesan:',
'revertmove'                   => 'balikkeun',
'delete_and_move'              => 'Hapus jeung pindahkeun',
'delete_and_move_text'         => '==Merlukeun hapusan==

Artikel nu dituju "[[:$1]]" geus aya. Badé dihapus baé sangkan bisa mindahkeun?',
'delete_and_move_confirm'      => 'Enya, hapus kaca éta',
'delete_and_move_reason'       => 'Hapus sangkan bisa mindahkeun',
'selfmove'                     => 'Judul sumber jeung tujuanana sarua, lain gé mindahkeun atuh!',
'immobile-target-namespace-iw' => 'Tutumbu interwiki teu bisa dipaké salaku tujul pamindahan.',
'immobile-source-page'         => 'Ieu kaca teu bisa dipindahkeun.',
'immobile-target-page'         => 'Teu bisa mindahkeun ka judul nu ditujul.',
'imagenocrossnamespace'        => 'Teu bisa mindahkeun gambar ka rohangan ngaran nu lain gambar',
'imagetypemismatch'            => 'Éksténsi koropak anyar teu cocog jeung tipena',
'imageinvalidfilename'         => 'Ngaran koropak tujuan teu sah',
'fix-double-redirects'         => 'Hadéan sakabéh alihan ganda nu mungkin kajadian',
'protectedpagemovewarning'     => "'''Awas:''' ieu kaca geus dikonci sarta ngan bisa dipindahkeun ku pamaké nu boga kawenangan kuncén.
Pikeun rujukan, éntri log panungtung dipidangkeun di handap:",
'semiprotectedpagemovewarning' => "'''Awas:''' ieu kaca geus dikonci sarta ukur bisa dipindahkeun ku pamaké nu geus asup log.
Pikeun rujukan, éntri log panungtung dipidangkeun di handap:",

# Export
'export'            => 'Ékspor kaca',
'exporttext'        => 'Anjeun bisa ngékspor téks sarta jujutan éditan ti kaca tinangtu atawa ti sababaraha kaca nu ngagunduk na sababaraha XML; ieu salajengna tiasa diimpor ka wiki séjén nu ngajalankeun software MediaWiki, ditransformasikeun, atawa ukur disimpen pikeun kaperluan anjeun pribadi.',
'exportcuronly'     => 'Asupkeun ukur révisi kiwari, teu sakabéh jujutan',
'exportnohistory'   => "----
'''Catetan:''' Ngékspor sakabéh jujutan éditan kaca ngaliwatan form ieu geus henteu diaktifkeun alatan alesan performance.",
'export-submit'     => 'Ékspor',
'export-addcattext' => 'Tambahkeun kaca tina kategori:',
'export-addcat'     => 'Tambahkeun',
'export-addns'      => 'Tambahkeun',
'export-download'   => 'Simpen dina koropak',
'export-templates'  => 'Kaasup citakan',

# Namespace 8 related
'allmessages'               => 'Talatah sistim',
'allmessagesname'           => 'Ngaran',
'allmessagesdefault'        => 'Téks ti dituna',
'allmessagescurrent'        => 'Téks kiwari',
'allmessagestext'           => 'Ieu mangrupa daptar talatah sistim nu aya na spasi ngaran MediaWiki:.',
'allmessagesnotsupportedDB' => "Kaca ieu teu dirojong sabab '''\$wgUseDatabaseMessages''' pareum.",
'allmessages-filter-legend' => 'Ayakan',
'allmessages-filter-all'    => 'Kabéh',
'allmessages-language'      => 'Basa:',
'allmessages-filter-submit' => 'Jung',

# Thumbnails
'thumbnail-more'           => 'Gedéan',
'filemissing'              => 'Koropak leungit',
'thumbnail_error'          => 'Kasalahan sawaktu nyieun gambar leutik (thumbnail): $1',
'djvu_page_error'          => 'Kaca DjVu teu kawadahan',
'djvu_no_xml'              => 'XML keur koropak DjVu teu bisa dicokot',
'thumbnail_invalid_params' => 'Kasalahan paraméter miniatur',
'thumbnail_dest_directory' => 'Diréktori nu dituju teu bisa dijieun',

# Special:Import
'import'                     => 'Impor kaca',
'importinterwiki'            => 'Impor transwiki',
'import-interwiki-text'      => 'Pilih wiki jeung judul kaca nu rék diimpor.
Tanggal révisi katut ngaran nu ngédit bakal dipertahankeun.
Sadaya aktivitas impor transwiki baris kacatet dina [[Special:Log/import|log impor]].',
'import-interwiki-source'    => 'Wiki/kaca sumber:',
'import-interwiki-history'   => 'Salin sakabéh vérsi jujutan pikeun ieu kaca',
'import-interwiki-submit'    => 'Impor',
'import-interwiki-namespace' => 'Spasi ngaran tujuan:',
'import-comment'             => 'Ringkesan:',
'importtext'                 => 'Mangga ékspor koropakna ti sumber nu dipaké ku wiki migunakeun fungsi Special:Export, simpen na piringan anjeun, teras muatkeun di dieu.',
'importstart'                => 'Ngimpor kaca...',
'import-revision-count'      => '$1 {{PLURAL:$1|vérsi heubeul}}',
'importnopages'              => 'henteu aya kaca keur diimpor.',
'importfailed'               => 'Ngimpor gagal: $1',
'importunknownsource'        => 'Tipeu sumber impor teu dipikawanoh',
'importcantopen'             => 'Teu bisa muka koropak impor',
'importbadinterwiki'         => 'Tumbu interwiki ruksak',
'importnotext'               => 'Kosong atawa teu aya téks',
'importsuccess'              => 'Ngimpor geus hasil!',
'importhistoryconflict'      => 'Aya révisi jujutan nu béntrok (boa-boa kungsi ngimpor kaca ieu)',
'importnosources'            => 'Teu aya sumber impor transwiki nu geus dijieun tur ngamuat jujutan sacara langsung geus dinon-aktifkeun.',
'importnofile'               => 'Euweuh koropak impor nu dimuat.',
'importuploaderrorsize'      => 'Koropak impor gagal dimuat. Ukuranana ngaleuwihan wates nu diwenangkeun.',
'importuploaderrorpartial'   => 'Koropak impor gagal dimuat sagemblengna.',
'importuploaderrortemp'      => 'Koropak impor gagal dimuat. Folder samentarana leungit.',
'import-parse-failure'       => 'Prosés impor XML teu hasil',
'import-noarticle'           => 'Euweuh kaca imporeun!',
'import-nonewrevisions'      => 'Sakabéh révisi geus kungsi diimpor saméméhna.',
'xml-error-string'           => '$1 dina baris $2, kolom $3 (bit $4): $5',
'import-upload'              => 'Ngamuat data XML',

# Import log
'importlogpage'                    => 'Log impor',
'importlogpagetext'                => 'Impor administratif kaca-kaca ti wiki séjén katut jujutanana.',
'import-logentry-upload'           => 'Muatkeun [[$1]] make pamuatan koropak',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|vérsi heubeul}}',
'import-logentry-interwiki'        => '$1 geus ditranswikikeun',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|vérsi heubel}} ti $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Kaca pamaké Anjeun',
'tooltip-pt-anonuserpage'         => 'Kaca pamaké pikeun IP nu ku anjeun keur diédit',
'tooltip-pt-mytalk'               => 'Kaca obrolan Anjeun',
'tooltip-pt-anontalk'             => 'Sawala ngeunaan éditan ti alamat IP ieu',
'tooltip-pt-preferences'          => 'Préferénsi kuring',
'tooltip-pt-watchlist'            => 'Daptar kaca nu diawaskeun ku anjeun parobahanana.',
'tooltip-pt-mycontris'            => 'Daptar tulisan Anjeun',
'tooltip-pt-login'                => 'Leuwih hadé asup log, sanajan teu wajib',
'tooltip-pt-anonlogin'            => 'Anjeun leuwih hadé asup log, sanajan teu wajib.',
'tooltip-pt-logout'               => 'Kaluar log',
'tooltip-ca-talk'                 => 'Sawala ngeunaan eusi kaca',
'tooltip-ca-edit'                 => 'Anjeun bisa ngédit kaca ieu. Paké tombol pratayang méméh nyimpen.',
'tooltip-ca-addsection'           => 'Jieun bahasan anyar',
'tooltip-ca-viewsource'           => 'Kaca ieu dikonci, tapi anjeun masih bisa muka sumberna.',
'tooltip-ca-history'              => 'Vérsi heubeul ieu kaca',
'tooltip-ca-protect'              => 'Konci kaca ieu',
'tooltip-ca-delete'               => 'Hapus kaca ieu',
'tooltip-ca-undelete'             => 'Simpen deui éditan kaca ieu nu geus dijieun saméméh dihapus',
'tooltip-ca-move'                 => 'Pindahkeun kaca ieu',
'tooltip-ca-watch'                => 'Tambahkeun kaca ieu kana awaskeuneun kuring',
'tooltip-ca-unwatch'              => 'Kaluarkeun kaca ieu tina awaskeuneun kuring',
'tooltip-search'                  => 'Sungsi di {{SITENAME}}',
'tooltip-search-go'               => 'Jugjug kaca nu ngaranna ciples kieu (mun aya)',
'tooltip-search-fulltext'         => 'Sungsi kaca-kaca nu ngandung tulisan ieu',
'tooltip-p-logo'                  => 'Tepas',
'tooltip-n-mainpage'              => 'Sindang ka Tepas',
'tooltip-n-mainpage-description'  => 'Jugjug Tepas',
'tooltip-n-portal'                => 'Ngeunaan proyékna, naon nu bisa dipigawé, di mana néangan naon',
'tooltip-n-currentevents'         => 'Panggihan iber ngeunaan naon baé nu keur lumangsung',
'tooltip-n-recentchanges'         => 'Béréndélan nu anyar robah dina wiki',
'tooltip-n-randompage'            => 'Muatkeun kaca naon baé',
'tooltip-n-help'                  => 'Tempat pikeun néangan pitulung',
'tooltip-t-whatlinkshere'         => 'Daptar kaca-kaca wiki nu numbu ka dieu',
'tooltip-t-recentchangeslinked'   => 'Nu anyar robah na kaca-kaca nu numbu ka dieu',
'tooltip-feed-rss'                => 'Asupan RSS pikeun kaca ieu',
'tooltip-feed-atom'               => 'Asupan atom pikeun kaca ieu',
'tooltip-t-contributions'         => 'Témbongkeun béréndélan kontribusi pamaké ieu',
'tooltip-t-emailuser'             => 'Kirim surélék ka pamaké ieu',
'tooltip-t-upload'                => 'Ngunggahkeun berkas',
'tooltip-t-specialpages'          => 'Daptar sadaya kaca husus',
'tooltip-t-print'                 => 'Vérsi citakeun ieu kaca',
'tooltip-t-permalink'             => 'Tumbu permanén ka vérsi ieu',
'tooltip-ca-nstab-main'           => 'Témbongkeun eusi kaca',
'tooltip-ca-nstab-user'           => 'Témbongkeun kaca pamaké',
'tooltip-ca-nstab-media'          => 'Témbongkeun kaca média',
'tooltip-ca-nstab-special'        => 'Ieu kaca husus, anjeun teu bisa nyunting langsung',
'tooltip-ca-nstab-project'        => 'Témbongkeun kaca proyék',
'tooltip-ca-nstab-image'          => 'Témbongkeun kaca gambar',
'tooltip-ca-nstab-mediawiki'      => 'Témbongkeun pesen sistim',
'tooltip-ca-nstab-template'       => 'Témbongkeun citakan',
'tooltip-ca-nstab-help'           => 'Témbongkeun kaca pitulung',
'tooltip-ca-nstab-category'       => 'Témbongkeun kaca kategori',
'tooltip-minoredit'               => 'Tandaan ieu salaku éditan minor',
'tooltip-save'                    => 'Simpen parobahan anjeun',
'tooltip-preview'                 => 'Sawang heula robahan anjeun, pami tos leres mangga simpen!',
'tooltip-diff'                    => 'Témbongkeun parobahan mana nu geus dijieun.',
'tooltip-compareselectedversions' => 'Tempo béda antara dua vérsi kaca ieu nu dipilih.',
'tooltip-watch'                   => 'Tambahkeun kaca ieu kana awaskeuneun kuring',
'tooltip-upload'                  => 'Muatkeun',
'tooltip-rollback'                => 'Mulangkeun éditan ka panulis panungtung dina sakali klik',
'tooltip-undo'                    => '"Bolay" malikkeun ieu éditan sarta muka kotak édit dina modeu pramidang.
Cara kieu bisa nambahkeun alesan dina ringkesanana.',

# Stylesheets
'common.css'   => "/* CSS nu di angé ku kabeh ''skin'' */",
'monobook.css' => "/* édit koropak ieu pikeun nyaluyukeun kulit ''monobook'' pikeun sakabéh situs */",

# Scripts
'common.js' => "/* JavaScript nu aya didieu di angé ku kabeh ''skin'' */",

# Metadata
'notacceptable' => "''Server'' wiki teu bisa nyadiakeun data dina format nu bisa dibaca ku klien anjeun.",

# Attribution
'anonymous'        => '{{PLURAL:$1|Pamaké|Pamaké-pamaké}} anonim di {{SITENAME}}',
'siteuser'         => 'Pamaké $1 {{SITENAME}}',
'lastmodifiedatby' => 'Kaca ieu panungtungan dirobah $2, $1 ku $3.',
'othercontribs'    => 'Dumasar karya $1.',
'others'           => 'Séjénna',
'siteusers'        => '{{PLURAL:$2|Pamaké|Pamaké-pamaké}} {{SITENAME}} $1',
'creditspage'      => 'Pangajén kaca',
'nocredits'        => 'Teu aya émbaran pangajén pikeun kaca ieu.',

# Spam protection
'spamprotectiontitle' => 'Saringan spam',
'spamprotectiontext'  => 'Kaca nu rék disimpen dipeungpeuk ku saringan spam.
Sigana mah ieu téh alatan tumbu ka loka luar.',

# Info page
'infosubtitle'   => 'Iber pikeun kaca',
'numedits'       => 'Jumlah éditan (artikel): $1',
'numtalkedits'   => 'Jumlah éditan (kaca sawala): $1',
'numwatchers'    => 'Jumlah nu ngawaskeun: $1',
'numauthors'     => 'Jumlah pangarang nu béda (artikel): $1',
'numtalkauthors' => 'Jumlah pangarang nu béda (kaca sawala): $1',

# Math options
'mw_math_png'    => 'Jieun wae PNG',
'mw_math_simple' => 'Mun basajan HTML, mun henteu PNG',
'mw_math_html'   => 'Mun bisa HTML, mun henteu PNG',
'mw_math_source' => 'Antep salaku TeX (pikeun panyungsi tulisan)',
'mw_math_modern' => 'Dianjurkeun pikeun panyungsi modérn',
'mw_math_mathml' => 'Mun bisa MathML (uji coba)',

# Math errors
'math_failure'          => "Peta ''parse'' gagal",
'math_unknown_error'    => 'Kasalahan teu kanyahoan',
'math_unknown_function' => 'fungsi teu kanyahoan',
'math_lexing_error'     => 'kasalahan lexing',
'math_syntax_error'     => 'Kasalahan rumpaka',
'math_image_error'      => 'Konversi PNG gagal; pastikeun yén latex, dvips, gs, jeung convert geus bener nginstalna',
'math_bad_tmpdir'       => 'Henteu bisa nulis atawa nyieun direktori samentara math',
'math_bad_output'       => 'Henteu bisa nulisikeun atawa nyieun direktori keluaran math',

# Patrolling
'markaspatrolleddiff'                 => 'Tandaan salaku geus diriksa',
'markaspatrolledtext'                 => 'Tandaan artikel ieu salaku geus diriksa',
'markedaspatrolled'                   => 'Tandaan salaku geus diriksa',
'markedaspatrolledtext'               => 'Révisi nu dipilih geus ditandaan salaku geus diriksa.',
'rcpatroldisabled'                    => 'Ronda Nu Anyar Robah ditumpurkeun',
'rcpatroldisabledtext'                => 'Fitur Ronda Nu Anyar Robah kiwari ditumpurkeun.',
'markedaspatrollederror'              => 'Teu bisa nandaan geus dipatroli',
'markedaspatrollederror-noautopatrol' => 'Anjeung teu diwenangkeun nandaan pangriksa ka éditan sorangan.',

# Patrol log
'patrol-log-page' => 'Log patroli',
'patrol-log-line' => 'nandaan $1 ti $2 kapatrol $3',
'patrol-log-auto' => '(otomatis)',
'patrol-log-diff' => 'révisi: $1',

# Image deletion
'deletedrevision'                 => 'Révisi heubeul nu dihapus $1',
'filedeleteerror-short'           => 'Éror nalika ngahapus koropak $1',
'filedeleteerror-long'            => 'Aya kasalahan sawaktu ngahapus koropak:

$1',
'filedelete-missing'              => 'Koropak "$1" teu kapanggih, sahingga teu bisa dihapus.',
'filedelete-old-unregistered'     => 'Révisi "$1" nu dimaksud euweuh dina pangkalan data.',
'filedelete-current-unregistered' => 'Koropak "$1" euweuh dina pangkalan data.',

# Browsing diffs
'previousdiff' => '← Éditan saméméhna',
'nextdiff'     => 'Éditan salajengna →',

# Media information
'imagemaxsize'    => 'Watesan gambar na kaca dadaran gambar nepi ka:',
'widthheightpage' => '$1×$2, $3 {{PLURAL:$3|kaca|kaca}}',
'file-info'       => 'ukuran koropak: $1, tipeu MIME: $2',
'file-info-size'  => '$1 × $2 piksel, ukuran koropak: $3, tipeu MIME: $4',
'file-nohires'    => '<small>Euweuh résolusi nu leuwih luhur.</small>',
'svg-long-desc'   => 'Koropak SVG, nominalna $1 × $2 piksel, ukuranana $3',
'show-big-image'  => 'Résolusi pinuh',

# Special:NewFiles
'newimages'             => 'Galeri gambar anyar',
'imagelisttext'         => "Di handap ieu daptar '''$1''' {{PLURAL:$1|gambar|gambar}} nu disusun $2.",
'newimages-summary'     => 'Ieu kaca husus ngabéréndélkeun koropak nu alanyar dimuat.',
'newimages-label'       => 'Ngaran berkas (atawa sawaréh tina ngaranna):',
'showhidebots'          => '($1 bot)',
'noimages'              => 'Taya nanaon.',
'ilsubmit'              => 'Sungsi',
'bydate'                => 'dumasar titimangsa',
'sp-newimages-showfrom' => 'Témbongkeun gambar anyar ti $2, $1',

# Bad image list
'bad_image_list' => 'Formatna kieu:

Ngan daptar butiran (jajar anu dimimitian ku tanda *) anu diitung. Tumbu kahiji dina hiji jajar dianggap numbu ka koropak anu goréng. Tumbu-tumbu sanggeusna dina jajar anu sarua dianggap bener, nyaéta artikel anu midangkeun koropak kasebut.',

# Metadata
'metadata'          => 'Métadata',
'metadata-help'     => 'Ieu koropak ngandung émbaran tambahan, nu sigana asalna tina kaméra digital atawa paminday nu dipaké pikeun ngadigitalkeunana. Mun ieu koropak geus dirobah tina bentuk aslina, datana bisa jadi teu bener.',
'metadata-expand'   => 'Témbongkeun wincikan panambah',
'metadata-collapse' => 'Sumputkeun wincikan panambah',
'metadata-fields'   => 'Widang métadata EXIF nu dibéréndélkeun di handap bakal dipidangkeun dina kaca gambar mun tabél métadata nyumput. Nu séjénna bakal disumputkeun luyu jeung buhunna (default).
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Lega',
'exif-imagelength'                 => 'Luhur',
'exif-compression'                 => 'Skéma komprési',
'exif-photometricinterpretation'   => 'Komposisi piksel',
'exif-orientation'                 => 'Oriéntasi',
'exif-samplesperpixel'             => 'Jumlah komponén',
'exif-planarconfiguration'         => 'Susunan data',
'exif-ycbcrpositioning'            => 'Perenah Y jeung C',
'exif-xresolution'                 => 'Résolusi horizontal',
'exif-yresolution'                 => 'Résolusi tangtung',
'exif-resolutionunit'              => 'Satuan résolusi X jeung Y',
'exif-stripoffsets'                => 'Perenah data gambar',
'exif-jpeginterchangeformat'       => 'Ofset ka JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bit data JPEG',
'exif-transferfunction'            => 'Fungsi transfer',
'exif-referenceblackwhite'         => 'Pasangan ajen rujukan hideung jeung bodas',
'exif-datetime'                    => 'Wanci jeung titimangsa parobahan koropak',
'exif-imagedescription'            => 'Judul gambar',
'exif-make'                        => 'Produsén kaméra',
'exif-model'                       => 'Modél kaméra',
'exif-software'                    => 'Sopwér nu dipaké',
'exif-artist'                      => 'Pangarang',
'exif-copyright'                   => 'Nu nyepeng hak cipta',
'exif-exifversion'                 => 'Vérsi Exif',
'exif-colorspace'                  => 'Rohangan warna',
'exif-compressedbitsperpixel'      => 'Mode komprési gambar',
'exif-pixelydimension'             => 'Lébar gambar nu sah',
'exif-pixelxdimension'             => 'Jangkung gambar nu sah',
'exif-makernote'                   => 'Catétan produsen',
'exif-usercomment'                 => 'Koméntar pamaké',
'exif-datetimeoriginal'            => 'Titimangsa jeung wanci dijieunna data',
'exif-datetimedigitized'           => 'Titimangsa jeung wanci digitisasi',
'exif-exposuretime'                => 'Waktu pajanan',
'exif-exposuretime-format'         => '$1 detik ($2)',
'exif-fnumber'                     => 'Nomer F',
'exif-exposureprogram'             => 'Program pajanan',
'exif-spectralsensitivity'         => 'Sénsitivitas spéktral',
'exif-brightnessvalue'             => 'Lenglang',
'exif-exposurebiasvalue'           => 'Bias pajanan',
'exif-subjectdistance'             => 'Jarak subjék',
'exif-lightsource'                 => 'Sumber cahya',
'exif-focallength'                 => 'Panjang fokus lénsa',
'exif-spatialfrequencyresponse'    => 'Réspons frékuénsi wawaréhan',
'exif-focalplanexresolution'       => 'Résolusi X datar fokus',
'exif-focalplaneyresolution'       => 'Résolusi Y datar fokus',
'exif-focalplaneresolutionunit'    => 'Unit résolusi datar fokus',
'exif-subjectlocation'             => 'Perenah subjék',
'exif-filesource'                  => 'Sumber berkas',
'exif-scenetype'                   => 'Tipe adegan',
'exif-exposuremode'                => 'Modeu pajanan',
'exif-digitalzoomratio'            => 'Rasio zum digital',
'exif-focallengthin35mmfilm'       => 'Panjang fokus dina film 35 mm',
'exif-contrast'                    => 'Kontras',
'exif-sharpness'                   => 'Seukeutna',
'exif-gpsversionid'                => 'Vérsi tag GPS',
'exif-gpslatituderef'              => 'Gurat Kalér atawa Kidul',
'exif-gpslatitude'                 => 'Gurat Lintang',
'exif-gpslongituderef'             => 'Gurat Wétan atawa Kulon',
'exif-gpslongitude'                => 'Gurat Bujur',
'exif-gpstimestamp'                => 'Wanci GPS (jam atomik)',
'exif-gpsstatus'                   => 'Status panampa',
'exif-gpsspeedref'                 => 'Unit kecepatan',
'exif-gpsprocessingmethod'         => 'Ngaran métodeu olah GPS',
'exif-gpsareainformation'          => 'Ngaran wewengkon GPS',
'exif-gpsdatestamp'                => 'Titimangsa GPS',
'exif-gpsdifferential'             => 'Koréksi diferensial GPS',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'Dibalikkeun horizontal',
'exif-orientation-3' => 'Diputer 180°',
'exif-orientation-4' => 'Dibalikkeun vértikal',
'exif-orientation-5' => 'Diputer 90° CCW jeung dibalikkeun vértikal',
'exif-orientation-6' => 'Diputer 90° CW',
'exif-orientation-7' => 'Diputer 90° CW jeung dibalikkeun vértikal',
'exif-orientation-8' => 'Diputer 90° CCW',

'exif-planarconfiguration-2' => 'format datar',

'exif-componentsconfiguration-0' => 'euweuh',

'exif-exposureprogram-1' => 'Manual',
'exif-exposureprogram-2' => 'Program normal',
'exif-exposureprogram-7' => 'Modeu potrét (pikeun poto deukeut nu tukangna di luar fokus)',
'exif-exposureprogram-8' => 'Modeu Lanskap (pikeun poto lanskap nu tukangna asup fokus)',

'exif-subjectdistance-value' => '$1 méter',

'exif-meteringmode-0'   => 'Duka',
'exif-meteringmode-1'   => 'Rata-rata',
'exif-meteringmode-3'   => 'Spot',
'exif-meteringmode-4'   => 'MultiSpot',
'exif-meteringmode-5'   => 'Pola',
'exif-meteringmode-6'   => 'Wawaréhan',
'exif-meteringmode-255' => 'Lianna',

'exif-lightsource-0'   => 'Duka',
'exif-lightsource-1'   => 'Tengah poé',
'exif-lightsource-2'   => 'Fluoreséns',
'exif-lightsource-3'   => 'Tungsten',
'exif-lightsource-4'   => 'Burinyay',
'exif-lightsource-9'   => 'Béngras',
'exif-lightsource-10'  => 'Ceuceum',
'exif-lightsource-11'  => 'Kalangkang',
'exif-lightsource-12'  => "Fluoreséns ca'ang siang (''daylight'', D 5700 – 7100K)",
'exif-lightsource-13'  => "Fluoreséns bodas siang (''day white'', N 4600 – 5400K)",
'exif-lightsource-14'  => "Fluoreséns bodas tiis (''cool white'', W 3900 – 4500K)",
'exif-lightsource-15'  => 'Fluoreséns bodas (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Cahya baku A',
'exif-lightsource-18'  => 'Cahya baku B',
'exif-lightsource-19'  => 'Cahya baku C',
'exif-lightsource-24'  => 'Tungsten studio ISO',
'exif-lightsource-255' => 'Sumber cahya séjén',

'exif-focalplaneresolutionunit-2' => 'inci',

'exif-scenetype-1' => 'Gambar poto langsung',

'exif-customrendered-0' => 'Prosés normal',
'exif-customrendered-1' => 'Prosés biasa',

'exif-exposuremode-0' => 'Pajanan otomatis',
'exif-exposuremode-1' => 'Pajanan manual',

'exif-whitebalance-0' => 'Kasaimbangan bodas otomatis',
'exif-whitebalance-1' => 'Kasaimbangan bodas manual',

'exif-scenecapturetype-0' => 'Baku',
'exif-scenecapturetype-1' => 'Ngagolér (landscape)',
'exif-scenecapturetype-2' => 'Nangtung (portrait)',
'exif-scenecapturetype-3' => 'Tetempoan peuting',

'exif-gaincontrol-0' => 'Kosong',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Leuleus',
'exif-contrast-2' => 'Heuras',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Kaleyuran handap',
'exif-saturation-2' => 'Kaleyuran luhur',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Lemes',
'exif-sharpness-2' => 'Heuras',

'exif-subjectdistancerange-0' => 'Teu kanyahoan',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Panémbong deukeut',
'exif-subjectdistancerange-3' => 'Panémbong jauh',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Gurat Kalér',
'exif-gpslatitude-s' => 'Gurat Kidul',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Gurat Wétan',
'exif-gpslongitude-w' => 'Gurat Kulon',

'exif-gpsmeasuremode-2' => 'Ukuran 2-diménsi',
'exif-gpsmeasuremode-3' => 'Ukuran 3-diménsi',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilométer per jam',
'exif-gpsspeed-m' => 'Mil per jam',
'exif-gpsspeed-n' => 'Knot',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-m' => 'Arah magnétik',

# External editor support
'edit-externally'      => 'Édit koropak ieu migunakeun aplikasi éksternal',
'edit-externally-help' => 'Baca [http://www.mediawiki.org/wiki/Manual:External_editors pituduh ngatur] pikeun émbaran leuwih jéntré.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'sadaya',
'imagelistall'     => 'kabéh',
'watchlistall2'    => 'sadaya',
'namespacesall'    => 'kabéh',
'monthsall'        => 'kabéh',
'limitall'         => 'kabéh',

# E-mail address confirmation
'confirmemail'             => 'Konfirmasi alamat surélék',
'confirmemail_noemail'     => 'Alamat surélék anu didaptarkeun dina [[Special:Preferences|préferénsi pamaké]] anjeun teu sah.',
'confirmemail_text'        => 'Wiki ieu merlukeun anjeun sangkan méré konfirmasi alamat surélék saméméh migunakeun fitur surélék. Aktifkeun tombol di handap pikeun ngirimkeun surat konfirmasi ka alamat anjeun. Suratna ngandung tumbu nu ngandung sandina; muatkeun tumbuna kana panyungsi anjeun pikeun ngonfirmasi yén alamat surélék anjeun sah.',
'confirmemail_pending'     => 'Sandi konfirmasi geus dikirimkeun ka alamat surélék anjeun; mun kakara nyieun rekening, mangga antos sababaraha menit saméméh mundut sandi anyar.',
'confirmemail_send'        => 'Kirimkeun surat konfirmasi sandi',
'confirmemail_sent'        => 'Surélék konfirmasi geus dikirim.',
'confirmemail_oncreate'    => 'Sandi konfirmasi geus dikirim ka alamat surélék anjeun. Éta sandi dipaké pikeun ngajalankeun fitur-fitur nu maké surélék di ieu wiki.',
'confirmemail_sendfailed'  => 'Surat konfirmasi teu kakirim. Pariksa alamatna, bisi salah. Informasi: $1',
'confirmemail_invalid'     => 'Sandi konfirmasi salah, meureun alatan sandina geus kadaluwarsa.',
'confirmemail_needlogin'   => 'Sangkan bisa ngonfirmasi alamat surélék, anjeun kudu $1.',
'confirmemail_success'     => 'Alamat surélék anjeun geus dikonfirmasi, ayeuna anjeun geus bisa migunakeun wikina.',
'confirmemail_loggedin'    => 'Alamat surélék anjeun geus dikonfirmasi.',
'confirmemail_error'       => 'Aya nu salah nalika nyimpen konfirmasi anjeun.',
'confirmemail_subject'     => 'Konfirmasi alamat surélék {{SITENAME}}',
'confirmemail_body'        => 'Aya, sigana mah anjeun ti alamat IP $1, geus ngadaptarkeun rekening "$2" maké alamat surélék ieu na {{SITENAME}}.

Pikeun mastikeun yén rekening ieu mémang kagungan sarta ngakifkeun fitur surélék di {{SITENAME}}, buka tumbu di handap ieu kana panyungsi/\'\'browser\'\' anjeun:

$3

Lamun anjeun teu ngadaptarkeun rekening, turutkeun tumbu ieu pikeun ngabolaykeun konfirmasi alamat surélék:

$5

Sandi konfirmasi ieu bakal kadaluwarsa dina $4.',
'confirmemail_invalidated' => 'Konfirmasi alamat surélék dibolaykeun',
'invalidateemail'          => 'Bolaykeun konfirmasi surélék',

# Scary transclusion
'scarytranscludedisabled' => '[Transklusi interwiki ditumpurkeun]',
'scarytranscludefailed'   => '[Gagal nyokot citakan $1]',
'scarytranscludetoolong'  => '[URLna panjang teuing]',

# Trackbacks
'trackbackbox'      => 'Lacak balik keur artikel ieu:<br />
$1',
'trackbackremove'   => '([$1 Hapus])',
'trackbacklink'     => 'Ngalacak balik',
'trackbackdeleteok' => 'Ngalacak balik, hasil dihapus!.',

# Delete conflict
'deletedwhileediting' => "'''Awas''': ieu kaca geus dihapus nalika anjeun mitembeyan ngédit!",
'confirmrecreate'     => "Pamaké [[User:$1|$1]] ([[User talk:$1|ngobrol]]) geus ngahapus artikel ieu nalika anjeun ngédit kalawan alesan:
: ''$2''
mangga pastikeun yén anjeun rék nyieun deui artikel ieu.",
'recreate'            => 'Jieun deui',

# action=purge
'confirm_purge_button' => 'Heug',
'confirm-purge-top'    => 'Hapus sindangan kaca ieu?',

# Multipage image navigation
'imgmultipageprev' => '&larr; kaca saacana',
'imgmultipagenext' => 'kaca salajeungna &rarr;',
'imgmultigo'       => 'Téang!',
'imgmultigoto'     => 'Jung ka kaca $1',

# Table pager
'ascending_abbrev'         => 'naék',
'descending_abbrev'        => 'turun',
'table_pager_next'         => 'Kaca salajeungna',
'table_pager_prev'         => 'Kaca saacana',
'table_pager_first'        => 'Kaca mimiti',
'table_pager_last'         => 'Kaca tung-tung',
'table_pager_limit'        => 'Pidangkeun $1 éntri pér halaman',
'table_pager_limit_submit' => 'Téang',
'table_pager_empty'        => 'Nyamos',

# Auto-summaries
'autosumm-blank'   => 'Ngosongkeun eusi kaca',
'autosumm-replace' => "Ngaganti kaca ku '$1'",
'autoredircomment' => 'Mindahkeun ka [[$1]]',
'autosumm-new'     => 'Nyieun kaca anyar  $1',

# Live preview
'livepreview-loading' => 'Ngamuat…',
'livepreview-ready'   => 'Ngamuat… Siap!',
'livepreview-failed'  => 'Sawangan langsung gagal!
Coba ku sawangan normal.',
'livepreview-error'   => 'Gagal nyambungkeun: $1 "$2"
Coba ku sawangan normal.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Parobahan nu leuwih anyar ti $1 {{PLURAL:$1|detik|detik}} moal ditémbongkeun dina ieu béréndélan.',
'lag-warn-high'   => 'Kusabab kasibukan lag server pangkalan data, parobahan nu leuwih anyar $1 {{PLURAL:$1|detik|detik}} moal ditémbongkeun dina ieu béréndélan.',

# Watchlist editor
'watchlistedit-numitems'      => 'Daptar awaskeuneun anjeun ngandung {{PLURAL:$1|1 judul|$1 judul}}, teu kaasup kaca obrolan.',
'watchlistedit-noitems'       => 'Daftar awaskeuneun anjeun kosong.',
'watchlistedit-normal-title'  => 'Édit daptar awaskeuneun',
'watchlistedit-normal-legend' => 'Kaluarkeun judul ti daftar awaskeuneun',
'watchlistedit-normal-submit' => 'Hapus judul',
'watchlistedit-normal-done'   => '{{PLURAL:$1|1 judul geus|$1 judul geus}} dikaluarkeun tina daptar:',
'watchlistedit-raw-title'     => 'Édit daptar atah awaskeuneun',
'watchlistedit-raw-legend'    => 'Édit daptar atah awaskeuneun',
'watchlistedit-raw-titles'    => 'Judul:',
'watchlistedit-raw-submit'    => 'Ropéa Awaskeuneun',
'watchlistedit-raw-done'      => 'Daptar awaskeuneun geus diropéa.',
'watchlistedit-raw-added'     => '{{PLURAL:$1|1 judul geus|$1 judul geus}} ditambahkeun:',
'watchlistedit-raw-removed'   => '{{PLURAL:$1|1 judul geus|$1 judul geus}} dikaluarkeun:',

# Watchlist editing tools
'watchlisttools-view' => 'Témbongkeun parobahan nu patali',
'watchlisttools-edit' => 'Témbongkeun sarta édit béréndélan awaskeuneun',
'watchlisttools-raw'  => 'Robah béréndélan awaskeuneun',

# Core parser functions
'unknown_extension_tag' => 'Tag éksténsi "$1" teu dipikawanoh',

# Special:Version
'version'                          => 'Vérsi',
'version-extensions'               => 'Éksténsi nu diinstal',
'version-specialpages'             => 'Kaca husus',
'version-parserhooks'              => 'Kait parser',
'version-variables'                => 'Variabel',
'version-other'                    => 'Séjén',
'version-hooks'                    => 'Kait',
'version-extension-functions'      => 'Fungsi éksténsi',
'version-parser-extensiontags'     => 'Tag éksténsi parser',
'version-skin-extension-functions' => 'Fungsi éksténsi kulit',
'version-hook-name'                => 'Ngaran kait',
'version-hook-subscribedby'        => 'Didaptarkeun ku',
'version-version'                  => '(Vérsi $1)',
'version-license'                  => 'Lisénsi',
'version-software'                 => 'Sopwér nu geus diinstal',
'version-software-product'         => 'Produk',
'version-software-version'         => 'Vérsi',

# Special:FilePath
'filepath'        => 'Jalur koropak',
'filepath-page'   => 'Koropak:',
'filepath-submit' => 'Jalur',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Sungsi gambar duplikat',
'fileduplicatesearch-legend'   => 'Sungsi duplikat',
'fileduplicatesearch-filename' => 'Ngaran koropak:',
'fileduplicatesearch-submit'   => 'Sungsi',
'fileduplicatesearch-info'     => '$1 × $2 piksel<br />Ukuran koropak: $3<br />Tipeu MIME: $4',
'fileduplicatesearch-result-1' => 'Koropak "$1" teu boga duplikat idéntik.',
'fileduplicatesearch-result-n' => 'Koropak "$1" mibanda {{PLURAL:$2|1 duplikat idéntik|$2 duplikat idéntik}}.',

# Special:SpecialPages
'specialpages'                   => 'Kaca husus',
'specialpages-note'              => '----
* Kaca husus bisa di buka ku umum.
* <strong class="mw-specialpagerestricted">Cetak kandel kaca husus nu kawates.</strong>',
'specialpages-group-maintenance' => 'Laporan pigawéeun',
'specialpages-group-other'       => 'Kaca husus lainna',
'specialpages-group-login'       => 'Asup log / Nyieun rekening',
'specialpages-group-changes'     => 'Nuanyar robah sarta log',
'specialpages-group-media'       => 'Laporan sarta muatkeun koropak',
'specialpages-group-users'       => 'Pamaké sarta hak pamaké',
'specialpages-group-highuse'     => 'Pamakéan kaca nu badag',
'specialpages-group-pages'       => 'Daptar kaca',
'specialpages-group-pagetools'   => 'Parabot kaca',
'specialpages-group-wiki'        => 'Data wiki jeung parabot',
'specialpages-group-redirects'   => 'Alihan kaca husus',
'specialpages-group-spam'        => 'Parabot Spam',

# Special:BlankPage
'blankpage' => 'Kaca kosong',

# Special:Tags
'tag-filter-submit' => 'Saring',
'tags-title'        => 'Tag',
'tags-tag'          => 'Ngaran tag',
'tags-edit'         => 'édit',
'tags-hitcount'     => '$1 {{PLURAL:$1|parobahan|parobahan}}',

# Database error messages
'dberr-header'    => 'Aya masalah dina ieu wiki',
'dberr-problems'  => 'Punten! Nuju aya gangguan téhnis.',
'dberr-again'     => 'Cobi antos sababaraha menit, lajeng dimuat ulang.',
'dberr-info'      => '(Teu bisa nyambung jeung server pangkalan data: $1)',
'dberr-usegoogle' => 'Kanggo samentawis, tiasa dicobi milari di Google.',

# HTML forms
'htmlform-select-badoption'    => 'Niléy anu diasupkeun teu bener.',
'htmlform-float-invalid'       => 'Niléy anu diasupkeun lain angka.',
'htmlform-int-toolow'          => 'Niléy anu diasupkeun kurang ti $1 anu pangleutikna',
'htmlform-int-toohigh'         => 'Niléy anu diasupkeun leuwih ti $1 anu panggedéna',
'htmlform-submit'              => 'Kirim',
'htmlform-reset'               => 'Bolaykeun parobahan',
'htmlform-selectorother-other' => 'Lianna',

);
