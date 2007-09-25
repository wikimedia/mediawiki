<?php
/** Malay (Bahasa Melayu)
 *
 * @addtogroup Language
 *
 * CHANGELOG
 * =========
 * Init - This localisation is based on a file kindly donated by the folks at MIMOS
 * http://www.asiaosc.org/enwiki/page/Knowledgebase_Home.html
 * Sep 2007 - Rewritten by the folks at ms.wikipedia.org
 */

# Uncomment line below to use space charecter as thousands separator
# $separatorTransformTable = array(',', ' ');

$defaultDateFormat = 'dmy';

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Khas',
	NS_MAIN           => '',
	NS_TALK           => 'Perbincangan',
	NS_USER           => 'Pengguna',
	NS_USER_TALK      => 'Perbincangan_Pengguna',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => 'Perbincangan_$1',
	NS_IMAGE          => 'Imej',
	NS_IMAGE_TALK     => 'Perbincangan_Imej',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'Perbincangan_MediaWiki',
	NS_TEMPLATE       => 'Templat',
	NS_TEMPLATE_TALK  => 'Perbincangan_Templat',
	NS_HELP           => 'Bantuan',
	NS_HELP_TALK      => 'Perbincangan_Bantuan',
	NS_CATEGORY       => 'Kategori',
	NS_CATEGORY_TALK  => 'Perbincangan_Kategori',
);

$namespaceAliases = array(
	'Istimewa'            => NS_SPECIAL,
	'Perbualan'           => NS_TALK,
	'Perbualan_Pengguna'  => NS_USER_TALK,
	'Perbualan_$1'        => NS_PROJECT_TALK,
	'Imej_Perbualan'      => NS_IMAGE_TALK,
	'MediaWiki_Perbualan' => NS_MEDIAWIKI_TALK,
	'Perbualan_Templat'   => NS_TEMPLATE_TALK,
	'Perbualan_Kategori'  => NS_CATEGORY_TALK,
	'Perbualan_Bantuan'   => NS_HELP_TALK,
);

$skinNames = array(
	'standard' => 'Klasik',
	'simple'   => 'Ringkas',
);

$specialPageAliases = array (
	'DoubleRedirects'         => array('Pelencongan_berganda'),
	'BrokenRedirects'         => array('Pelencongan_rosak'),
	'Disambiguations'         => array('Penyahtaksaan'),
	'Userlogin'               => array('Log_masuk'),
	'Userlogout'              => array('Log_keluar'),
	'Preferences'             => array('Keutamaan'),
	'Watchlist'               => array('Senarai_pantau'),
	'Recentchanges'           => array('Perubahan_terkini'),
	'Upload'                  => array('Muat_naik'),
	'Imagelist'               => array('Senarai_imej'),
	'Newimages'               => array('Imej_baru'),
	'Listusers'               => array('Senarai_pengguna'),
	'Statistics'              => array('Statistik'),
	'Randompage'              => array('Laman_rawak'),
	'Lonelypages'             => array('Laman_yatim'),
	'Uncategorizedpages'      => array('Laman_tanpa_kategori'),
	'Uncategorizedcategories' => array('Kategori_tanpa_kategori'),
	'Uncategorizedimages'     => array('Imej_tanpa_kategori'),
	'Uncategorizedtemplates'  => array('Templat_tanpa_kategori'),
	'Unusedcategories'        => array('Kategori_tak_digunakan'),
	'Unusedimages'            => array('Imej_tak_digunakan'),
	'Wantedpages'             => array('Laman_dikehendaki'),
	'Wantedcategories'        => array('Kategori_dikehendaki'),
	'Mostlinked'              => array('Laman_dipaut_terbanyak'),
	'Mostlinkedcategories'    => array('Kategori_dipaut_terbanyak'),
	'Mostlinkedtemplates'     => array('Templat_dipaut_terbanyak'),
	'Mostcategories'          => array('Kategori_terbanyak'),
	'Mostimages'              => array('Imej_terbanyak'),
	'Mostrevisions'           => array('Semakan_terbanyak'),
	'Fewestrevisions'         => array('Semakan_tersikit'),
	'Shortpages'              => array('Laman_pendek'),
	'Longpages'               => array('Laman_panjang'),
	'Newpages'                => array('Laman_baru'),
	'Ancientpages'            => array('Laman_lapuk'),
	'Deadendpages'            => array('Laman_buntu'),
	'Protectedpages'          => array('Laman_dilindungi'),
	'Allpages'                => array('Semua_laman'),
	'Prefixindex'             => array('Indeks_awalan'),
	'Ipblocklist'             => array('Sekatan_IP'),
	'Specialpages'            => array('Laman_khas'),
	'Contributions'           => array('Sumbangan'),
	'Emailuser'               => array('E-mel_pengguna'),
	'Whatlinkshere'           => array('Pautan_ke'),
	'Recentchangeslinked'     => array('Perubahan_berkaitan'),
	'Movepage'                => array('Pindah_laman'),
	'Blockme'                 => array('Sekat_saya'),
	'Booksources'             => array('Sumber_buku'),
	'Categories'              => array('Kategori'),
	'Export'                  => array('Eksport'),
	'Version'                 => array('Versi'),
	'Allmessages'             => array('Semua_mesej'),
	'Log'                     => array('Log'),
	'Blockip'                 => array('Sekat_IP'),
	'Undelete'                => array('Nyahhapus'),
	'Import'                  => array('Import'),
	'Lockdb'                  => array('Kunci_pangkalan_data'),
	'Unlockdb'                => array('Buka_kunci_pangkalan_data'),
	'Userrights'              => array('Hak_pengguna'),
	'MIMEsearch'              => array('Gelintar_MIME'),
	'Unwatchedpages'          => array('Laman_tak_dipantau'),
	'Listredirects'           => array('Senarai_pelencongan'),
	'Revisiondelete'          => array('Hapus_semakan'),
	'Unusedtemplates'         => array('Templat_tak_digunakan'),
	'Randomredirect'          => array('Pelencongan_rawak'),
	'Mypage'                  => array('Laman_saya'),
	'Mytalk'                  => array('Perbincangan_saya'),
	'Mycontributions'         => array('Sumbangan_saya'),
	'Listadmins'              => array('Senarai_pentadbir'),
	'Popularpages'            => array('Laman_popular'),
	'Search'                  => array('Gelintar'),
	'Resetpass'               => array('Lupa_kata_laluan'),
	'Withoutinterwiki'        => array('Laman_tanpa_pautan_bahasa'),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Gariskan pautan:',
'tog-highlightbroken'         => 'Formatkan pautan rosak <a href="" class="new">seperti ini</a> (ataupun seperti ini<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Laraskan teks perenggan',
'tog-hideminor'               => 'Sembunyikan suntingan kecil dalam laman perubahan terkini',
'tog-extendwatchlist'         => 'Kembangkan senarai pantau',
'tog-usenewrc'                => 'Pertingkatkan laman perubahan terkini (JavaScript)',
'tog-numberheadings'          => 'Nomborkan tajuk secara automatik',
'tog-showtoolbar'             => 'Tunjukkan bar sunting (JavaScript)',
'tog-editondblclick'          => 'Sunting laman ketika dwiklik (JavaScript)',
'tog-editsection'             => 'Aktifkan penyuntingan bahagian melalui pautan [sunting]',
'tog-editsectiononrightclick' => 'Aktifkan penyuntingan bahagian melalui klik kanan<br /> pada tajuk bahagian (JavaScript)',
'tog-showtoc'                 => 'Tunjukkan senarai kandungan bagi rencana melebihi 3 tajuk',
'tog-rememberpassword'        => 'Ingat status log masuk saya pada komputer ini',
'tog-editwidth'               => 'Kotak sunting mencapai lebar penuh',
'tog-watchcreations'          => 'Tambahkan laman yang saya cipta ke dalam senarai pantau',
'tog-watchdefault'            => 'Tambahkan laman yang saya sunting ke dalam senarai pantau',
'tog-watchmoves'              => 'Tambahkan laman yang saya pindahkan ke dalam senarai pantau',
'tog-watchdeletion'           => 'Tambahkan laman yang saya hapuskan ke dalam senarai pantau',
'tog-minordefault'            => 'Tandakan suntingan kecil secara lalai',
'tog-previewontop'            => 'Tunjukkan pratonton di atas kotak sunting',
'tog-previewonfirst'          => 'Tunjukkan pratonton ketika penyuntingan pertama',
'tog-nocache'                 => 'Matikan penyimpanan sementara bagi laman',
'tog-enotifwatchlistpages'    => 'E-melkan saya apabila berlaku perubahan pada laman yang dipantau',
'tog-enotifusertalkpages'     => 'E-melkan saya apabila berlaku perubahan pada laman perbincangan saya',
'tog-enotifminoredits'        => 'Juga e-melkan saya apabila berlaku penyuntingan kecil',
'tog-enotifrevealaddr'        => 'Serlahkan alamat e-mel saya dalam e-mel pemberitahuan',
'tog-shownumberswatching'     => 'Tunjukkan bilangan pemantau',
'tog-fancysig'                => 'Tandatangan mentah (tanpa pautan automatik)',
'tog-externaleditor'          => 'Gunakan penyunting luar secara lalai',
'tog-externaldiff'            => 'Gunakan pembeza luar secara lalai',
'tog-showjumplinks'           => 'Aktifkan pautan boleh capai "lompat kepada"',
'tog-uselivepreview'          => 'Gunakan pratonton langsung (JavaScript) (masih dalam uji kaji)',
'tog-forceeditsummary'        => 'Tanya saya jika ringkasan suntingan kosong',
'tog-watchlisthideown'        => 'Sembunyikan suntingan saya daripada senarai pantau',
'tog-watchlisthidebots'       => 'Sembunyikan suntingan bot daripada senarai pantau',
'tog-watchlisthideminor'      => 'Sembunyikan suntingan kecil daripada senarai pantau',
'tog-nolangconversion'        => 'Matikan penukaran kelainan',
'tog-ccmeonemails'            => 'Kirim kepada saya salinan bagi e-mel yang saya hantar kepada orang lain',
'tog-diffonly'                => 'Jangan tunjukkan kandungan laman di bawah perbezaan',

'underline-always'  => 'Sentiasa',
'underline-never'   => 'Jangan',
'underline-default' => 'Ikut tetapan pelayar',

'skinpreview' => '(Pratonton)',

# Dates
'sunday'        => 'Ahad',
'monday'        => 'Isnin',
'tuesday'       => 'Selasa',
'wednesday'     => 'Rabu',
'thursday'      => 'Khamis',
'friday'        => 'Jumaat',
'saturday'      => 'Sabtu',
'sun'           => 'Aha',
'mon'           => 'Isn',
'tue'           => 'Sel',
'wed'           => 'Rab',
'thu'           => 'Kha',
'fri'           => 'Jum',
'sat'           => 'Sab',
'january'       => 'Januari',
'february'      => 'Februari',
'march'         => 'Mac',
'april'         => 'April',
'may_long'      => 'Mei',
'june'          => 'Jun',
'july'          => 'Julai',
'august'        => 'Ogos',
'september'     => 'September',
'october'       => 'Oktober',
'november'      => 'November',
'december'      => 'Disember',
'january-gen'   => 'Januari',
'february-gen'  => 'Februari',
'march-gen'     => 'Mac',
'april-gen'     => 'April',
'may-gen'       => 'Mei',
'june-gen'      => 'Jun',
'july-gen'      => 'Julai',
'august-gen'    => 'Ogos',
'september-gen' => 'September',
'october-gen'   => 'Oktober',
'november-gen'  => 'November',
'december-gen'  => 'Disember',
'jan'           => 'Jan',
'feb'           => 'Feb',
'mar'           => 'Mac',
'apr'           => 'Apr',
'may'           => 'Mei',
'jun'           => 'Jun',
'jul'           => 'Jul',
'aug'           => 'Ogo',
'sep'           => 'Sep',
'oct'           => 'Okt',
'nov'           => 'Nov',
'dec'           => 'Dis',

# Bits of text used by many pages
'categories'            => 'Kategori',
'pagecategories'        => 'Kategori',
'category_header'       => 'Rencana-rencana dalam kategori "$1"',
'subcategories'         => 'Subkategori',
'category-media-header' => 'Media-media dalam kategori "$1"',
'category-empty'        => "''Kategori ini tidak mengandungi sebarang rencana atau media.''",

'mainpagetext'      => "<big>'''MediaWiki telah dipasang.'''</big>",
'mainpagedocfooter' => 'Sila rujuk [http://meta.wikimedia.org/wiki/Help:Contents Panduan Penggunaan] untuk maklumat mengenai penggunaan perisian wiki ini.

== Untuk bermula ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Senarai tetapan konfigurasi]
* [http://www.mediawiki.org/wiki/Manual:FAQ Soalan Lazim MediaWiki]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Senarai mel bagi keluaran MediaWiki]',

'about'          => 'Perihal',
'article'        => 'Laman kandungan',
'newwindow'      => '(dibuka di tetingkap baru)',
'cancel'         => 'Batal',
'qbfind'         => 'Cari',
'qbbrowse'       => 'Semak imbas',
'qbedit'         => 'Sunting',
'qbpageoptions'  => 'Laman ini',
'qbpageinfo'     => 'Konteks',
'qbmyoptions'    => 'Laman-laman saya',
'qbspecialpages' => 'Laman khas',
'moredotdotdot'  => 'Lagi...',
'mypage'         => 'Laman saya',
'mytalk'         => 'Perbincangan saya',
'anontalk'       => 'Perbincangan bagi IP ini',
'navigation'     => 'Navigasi',

# Metadata in edit box
'metadata_help' => 'Metadata:',

'errorpagetitle'    => 'Ralat',
'returnto'          => 'Kembali ke $1.',
'tagline'           => 'Daripada {{SITENAME}}.',
'help'              => 'Bantuan',
'search'            => 'Gelintar',
'searchbutton'      => 'Cari',
'go'                => 'Pergi',
'searcharticle'     => 'Pergi',
'history'           => 'Sejarah laman',
'history_short'     => 'Sejarah',
'updatedmarker'     => 'dikemaskinikan sejak kunjungan terakhir saya',
'info_short'        => 'Maklumat',
'printableversion'  => 'Versi boleh cetak',
'permalink'         => 'Pautan kekal',
'print'             => 'Cetak',
'edit'              => 'Sunting',
'editthispage'      => 'Sunting laman ini',
'delete'            => 'Hapus',
'deletethispage'    => 'Hapuskan laman ini',
'undelete_short'    => 'Nyahhapus {{PLURAL:$1|satu suntingan|$1 suntingan}}',
'protect'           => 'Lindung',
'protect_change'    => 'ubah perlindungan',
'protectthispage'   => 'Lindungi laman ini',
'unprotect'         => 'Nyahlindung',
'unprotectthispage' => 'Nyahlindung laman ini',
'newpage'           => 'Laman baru',
'talkpage'          => 'Bincangkan laman ini',
'talkpagelinktext'  => 'bincang',
'specialpage'       => 'Laman Khas',
'personaltools'     => 'Alatan peribadi',
'postcomment'       => 'Kirim komen',
'articlepage'       => 'Lihat laman kandungan',
'talk'              => 'Perbincangan',
'views'             => 'Pandangan',
'toolbox'           => 'Kotak alatan',
'userpage'          => 'Lihat laman pengguna',
'projectpage'       => 'Lihat laman projek',
'imagepage'         => 'Lihat laman imej',
'mediawikipage'     => 'Lihat laman mesej',
'templatepage'      => 'Lihat laman templat',
'viewhelppage'      => 'Lihat laman bantuan',
'categorypage'      => 'Lihat laman kategori',
'viewtalkpage'      => 'Lihat perbincangan',
'otherlanguages'    => 'Bahasa lain',
'redirectedfrom'    => '(Dilencongkan dari $1)',
'redirectpagesub'   => 'Laman pelencongan',
'lastmodifiedat'    => 'Laman ini diubah buat kali terakhir pada $2, $1.', # $1 date, $2 time
'viewcount'         => 'Laman ini telah dilihat {{PLURAL:$1|sekali|sebanyak $1 kali}}.',
'protectedpage'     => 'Laman dilindungi',
'jumpto'            => 'Lompat ke:',
'jumptonavigation'  => 'navigasi',
'jumptosearch'      => 'gelintar',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Perihal {{SITENAME}}',
'aboutpage'         => 'Project:Perihal',
'bugreports'        => 'Laporan pepijat',
'bugreportspage'    => 'Project:Laporan pepijat',
'copyright'         => 'Semua kandungan dikeluarkan di bawah $1.',
'copyrightpagename' => 'Hak cipta {{SITENAME}}',
'copyrightpage'     => 'Project:Hak cipta',
'currentevents'     => 'Peristiwa semasa',
'currentevents-url' => 'Peristiwa semasa',
'disclaimers'       => 'Penolak tuntutan',
'disclaimerpage'    => 'Project:Penolak tuntutan',
'edithelp'          => 'Bantuan menyunting',
'edithelppage'      => 'Help:Menyunting',
'faq'               => 'Soalan Lazim',
'faqpage'           => 'Project:Soalan Lazim',
'helppage'          => 'Help:Kandungan',
'mainpage'          => 'Laman Utama',
'policy-url'        => 'Project:Dasar',
'portal'            => 'Portal komuniti',
'portal-url'        => 'Project:Portal Komuniti',
'privacy'           => 'Dasar privasi',
'privacypage'       => 'Project:Dasar privasi',
'sitesupport'       => 'Derma',
'sitesupport-url'   => 'Project:Dana',

'badaccess'        => 'Tidak dibenarkan',
'badaccess-group0' => 'Anda tidak dibenarkan melaksanakan tindakan ini.',
'badaccess-group1' => 'Tindakan ini hanya boleh dilakukan oleh pengguna dalam kumpulan $1.',
'badaccess-group2' => 'Tindakan ini hanya boleh dilakukan oleh pengguna dalam kumpulan $1.',
'badaccess-groups' => 'Tindakan ini hanya boleh dilakukan oleh pengguna dalam kumpulan $1.',

'versionrequired'     => 'MediaWiki versi $1 diperlukan',
'versionrequiredtext' => 'MediaWiki versi $1 diperlukan untuk menggunakan laman ini. Sila lihat [[Special:Version|laman versi]].',

'ok'                           => 'OK',
'retrievedfrom'                => 'Diambil daripada "$1"',
'youhavenewmessages'           => 'Anda mempunyai $1 ($2).',
'newmessageslink'              => 'mesej baru',
'newmessagesdifflink'          => 'perubahan terakhir',
'youhavenewmessagesmulti'      => 'Anda mempunyai mesej baru pada $1',
'editsection'                  => 'sunting',
'editold'                      => 'sunting',
'editsectionhint'              => 'Sunting bahagian: $1',
'toc'                          => 'Senarai kandungan',
'showtoc'                      => 'buka',
'hidetoc'                      => 'tutup',
'thisisdeleted'                => 'Lihat atau pulihkan $1?',
'viewdeleted'                  => 'Lihat $1?',
'restorelink'                  => '{{PLURAL:$1|satu|$1}} suntingan dihapuskan',
'feedlinks'                    => 'Suapan:',
'feed-invalid'                 => 'Jenis suapan langganan tidak sah.',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Rencana',
'nstab-user'      => 'Laman pengguna',
'nstab-media'     => 'Laman media',
'nstab-special'   => 'Khas',
'nstab-project'   => 'Laman projek',
'nstab-image'     => 'Imej',
'nstab-mediawiki' => 'Mesej',
'nstab-template'  => 'Templat',
'nstab-help'      => 'Laman bantuan',
'nstab-category'  => 'Kategori',

# Main script and global functions
'nosuchaction'      => 'Tindakan tidak dikenali',
'nosuchactiontext'  => 'Tindakan yang dinyatakan dalam URL
ini tidak dikenali oleh perisian wiki ini',
'nosuchspecialpage' => 'Laman khas tidak wujud',
'nospecialpagetext' => "'''<big>Anda telah meminta laman khas yang tidak sah.</big>'''

Senarai laman khas yang sah boleh dilihat di [[Special:Specialpages]].",

# General errors
'error'                => 'Ralat',
'databaseerror'        => 'Ralat pangkalan data',
'dberrortext'          => 'Terdapat kesalahan pada sintaks pertanyaan pangkalan data.
Ini mungkin menandakan pepijat dalam perisian wiki ini.
Pertanyaan pangkalan data yang terakhir ialah:
<blockquote><tt>$1</tt></blockquote> 
dari dalam fungsi "<tt>$2</tt>".
MySQL memulangkan ralat "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Terdapat kesalahan pada sintaks pertanyaan pangkalan data.
Pertanyaan pangkalan data yang terakhir ialah:
"$1"
dari dalam fungsi "$2".
MySQL memulangkan ralat "$3: $4"',
'noconnect'            => 'Harap maaf! Wiki ini dilanda sedikit masalah teknikal dan tidak dapat menghubungi pelayan pangkalan data.<br />
$1',
'nodb'                 => 'Tidak dapat memilih pangkalan data $1',
'cachederror'          => 'Berikut ialah salinan simpanan bagi laman yang diminta, dan barangkali bukan yang terkini.',
'laggedslavemode'      => 'Amaran: Laman ini mungkin bukan yang terkini.',
'readonly'             => 'Pangkalan data dikunci',
'enterlockreason'      => 'Sila nyatakan sebab penguncian dan jangkaan
bila kunci ini akan dibuka.',
'readonlytext'         => 'Pangkalan data sedang dikunci. Hal ini mungkin disebabkan oleh penyenggaraan rutin, dan akan dibuka semula selepas proses penyenggaraan ini siap.

Pentadbir yang menguncinya memberi penjelasan ini: $1',
'missingarticle'       => 'Pangkalan data kami tidak menjumpai teks bagi laman "$1".

Perkara ini biasanya berlaku apabila anda mengikuti pautan perbezaan yang
ketinggalan zaman ataupun pautan sejarah ke laman yang telah dihapuskan.

Kalau bukan ini sebabnya, anda mungkin telah menjumpai pepijat dalam perisian
ini. Sila laporkan masalah ini berserta URL yang anda buka kepada seorang pentadbir.',
'readonly_lag'         => 'Pangkalan data telah dikunci secara automatik sementara semua pelayan pangkalan data diselaraskan.',
'internalerror'        => 'Ralat dalaman',
'internalerror_info'   => 'Ralat dalaman: $1',
'filecopyerror'        => 'Fail "$1" tidak dapat disalin kepada "$2".',
'filerenameerror'      => 'Nama fail "$1" tidak dapat ditukarkan kepada "$2".',
'filedeleteerror'      => 'Fail "$1" tidak dapat dihapuskan.',
'directorycreateerror' => 'Directory "$1" gagal diciptakan.',
'filenotfound'         => 'Fail "$1" tidak dijumpai.',
'fileexistserror'      => 'File "$1" tidak dapat ditulis: fail telah pun wujud',
'unexpected'           => 'Nilai tanpa diduga: "$1"="$2".',
'formerror'            => 'Ralat: borang tidak dapat dikirim.',
'badarticleerror'      => 'Tindakan ini tidak boleh dilaksanakan pada laman ini.',
'cannotdelete'         => 'Laman atau imej yang dinyatakan tidak dapat dihapuskan. (Ia mungkin telah pun dihapuskan oleh orang yang lain.)',
'badtitle'             => 'Tajuk tidak sah',
'badtitletext'         => 'Tajuk laman yang diminta tidak sah, kosong, ataupun tajuk antara bahasa atau tajuk antara wiki yang salah dipaut. Ia mungkin mengandungi aksara yang tidak dibenarkan.',
'perfdisabled'         => 'Harap maaf! Ciri ini telah dipadamkan buat sementara kerana ia melambatkan pangkalan data sehingga wiki ini tidak dapat digunakan.',
'perfcached'           => 'Data berikut diambil daripada simpanan sementara dan mungkin bukan yang terkini.',
'perfcachedts'         => 'Data berikut berada dalam simpanan sementara dan dikemaskinikan buat kali terakhir pada $1.',
'querypage-no-updates' => 'Pengkemaskinian bagi laman ini dimatikan. Data yang ditunjukkan di sini tidak disegarkan semula.',
'wrong_wfQuery_params' => 'Parameter salah bagi wfQuery()<br />
Fungsi: $1<br />
Pertanyaan: $2',
'viewsource'           => 'Lihat sumber',
'viewsourcefor'        => 'bagi $1',
'protectedpagetext'    => 'Laman ini telah dikunci untuk menghalang penyuntingan.',
'viewsourcetext'       => 'Anda boleh melihat dan menyalin sumber bagi laman ini:',
'protectedinterface'   => 'Laman ini menyediakan teks antara muka bagi perisian ini, akan tetapi dikunci untuk menghalang penyalahgunaan.',
'editinginterface'     => "'''Amaran:''' Anda sedang menyunting laman yang digunakan untuk menghasilkan teks antara muka bagi perisian ini. Sebarang perubahan terhadap laman ini akan menjejaskan rupa antara muka bagi pengguna-pengguna lain.",
'sqlhidden'            => '(Pertanyaan SQL tidak ditunjukkan)',
'cascadeprotected'     => 'Laman ini telah dilindungi daripada penyuntingan oleh pengguna selain penyelia, kerana ia termasuk dalam {{PLURAL:$1|laman|laman-laman}} berikut, yang dilindungi dengan secara "melata":
$2',
'namespaceprotected'   => "Anda tidak mempunyai keizinan untuk menyunting laman dalam ruang nama '''$1'''.",
'customcssjsprotected' => 'Anda tidak mempunyai keizinan untuk menyunting laman ini kerana ia mengandungi tetapan peribadi pengguna lain.',
'ns-specialprotected'  => 'Laman dalam ruang nama {{ns:special}} tidak boleh disunting.',

# Login and logout pages
'logouttitle'                => 'Log keluar',
'logouttext'                 => "<strong>Anda telah log keluar.</strong><br />
Anda boleh terus menggunakan {{SITENAME}} sebagai pengguna tanpa nama, atau
anda boleh log masuk sekali lagi sebagai pengguna lain. Sila ambil perhatian
bahawa sesetengah laman mungkin dipaparkan seolah-olah anda masih log masuk,
sehinggalah anda mengosongkan simpanan (''cache'') pelayar anda.",
'welcomecreation'            => '== Selamat datang, $1! ==

Akaun anda telah dibuka. Jangan lupa untuk mengubah keutamaan {{SITENAME}} anda.',
'loginpagetitle'             => 'Log masuk',
'yourname'                   => 'Nama pengguna:',
'yourpassword'               => 'Kata laluan:',
'yourpasswordagain'          => 'Ulangi kata laluan:',
'remembermypassword'         => 'Ingat saya pada komputer ini',
'yourdomainname'             => 'Domain anda:',
'externaldberror'            => 'Berlaku ralat pangkalan data bagi pengesahan luar atau anda tidak dibenarkan mengemaskinikan akaun luar anda.',
'loginproblem'               => '<b>Berlaku sedikit masalah ketika log masuk.</b><br />Sila cuba lagi!',
'login'                      => 'Log masuk',
'loginprompt'                => "Anda mesti membenarkan ''cookies'' untuk log masuk ke dalam {{SITENAME}}.",
'userlogin'                  => 'Log masuk / buka akaun',
'logout'                     => 'Log keluar',
'userlogout'                 => 'Log keluar',
'notloggedin'                => 'Belum log masuk',
'nologin'                    => 'Belum mempunyai akaun? $1.',
'nologinlink'                => 'Buka akaun baru',
'createaccount'              => 'Buka akaun',
'gotaccount'                 => 'Sudah mempunyai akaun? $1.',
'gotaccountlink'             => 'Log masuk',
'createaccountmail'          => 'melalui e-mel',
'badretype'                  => 'Sila ulangi kata laluan dengan betul.',
'userexists'                 => 'Nama pengguna yang anda masukkan telah pun digunakan. Sila pilih nama yang lain.',
'youremail'                  => 'E-mel:',
'username'                   => 'Nama pengguna:',
'uid'                        => 'ID pengguna:',
'yourrealname'               => 'Nama sebenar:',
'yourlanguage'               => 'Bahasa:',
'yourvariant'                => 'Kelainan',
'yournick'                   => 'Nama samaran:',
'badsig'                     => 'Tandatangan mentah tidak sah; sila semak tag HTML.',
'badsiglength'               => 'Nama samaran terlalu panjang; ia mestilah tidak melebihi $1 aksara.',
'email'                      => 'E-mel',
'prefs-help-realname'        => 'Nama sebenar adalah tidak wajib. Jika dinyatakan, ia akan digunakan untuk mengiktiraf karya anda.',
'loginerror'                 => 'Ralat log masuk',
'prefs-help-email'           => 'Alamat e-mel adalah tidak wajib, akan tetapi ia membolehkan orang lain menghubungi anda melalui laman pengguna atau laman perbincangan tanpa mendedahkan identiti anda.',
'nocookiesnew'               => "Akaun anda telah dibuka, tetapi anda belum log masuk. {{SITENAME}} menggunakan ''cookies'' untuk mencatat status log masuk pengguna. Sila aktifkan sokongan ''cookies'' pada pelayar anda, kemudian log masuk dengan nama pengguna dan kata laluan baru anda.",
'nocookieslogin'             => "{{SITENAME}} menggunakan ''cookies'' untuk mencatat status log masuk pengguna. Sila aktifkan sokongan ''cookies'' pada pelayar anda dan cuba lagi.",
'noname'                     => 'Nama pengguna tidak sah.',
'loginsuccesstitle'          => 'Berjaya log masuk',
'loginsuccess'               => "'''Anda telah log masuk ke dalam {{SITENAME}} sebagai \"\$1\".'''",
'nosuchuser'                 => 'Pengguna "$1" tidak wujud. Sila semak ejaan anda atau buka akaun baru.',
'nosuchusershort'            => 'Pengguna "$1" tidak wujud. Sila semak ejaan anda.',
'nouserspecified'            => 'Sila nyatakan nama pengguna.',
'wrongpassword'              => 'Kata laluan yang dimasukkan adalah salah. Sila cuba lagi.',
'wrongpasswordempty'         => 'Kata laluan yang dimasukkan adalah kosong. Sila cuba lagi.',
'passwordtooshort'           => 'Kata laluan anda tidak sah atau terlalu pendek. Panjangnya mestilah sekurang-kurangnya $1 aksara dan berbeza daripada nama pengguna anda.',
'mailmypassword'             => 'E-melkan kata laluan baru',
'passwordremindertitle'      => 'Pengingat kata laluan daripada {{SITENAME}}',
'passwordremindertext'       => 'Seseorang (barangkali anda, daripada alamat IP $1)
telah memohon kata laluan baru bagi {{SITENAME}}} ($4).
Kata laluan terkini untuk pengguna "$2" ialah "$3".
Anda disarankan supaya log masuk dan menukarkan kata laluan anda dengan segera.

Jika anda tidak pernah membuat pemohonan ini atau anda telah mengingati kata
laluan lama anda dan tidak mahu menukarnya, anda boleh mengabaikan mesej ini
dan terus menggunakan kata laluan yang lama.',
'noemail'                    => 'Tiada alamat e-mel direkodkan bagi pengguna "$1".',
'passwordsent'               => 'Kata laluan baru telah dikirim kepada alamat
e-mel yang didaftarkan oleh "$1".
Sila log masuk semula setelah anda menerima e-mel tersebut.',
'blocked-mailpassword'       => 'Alamat IP anda telah disekat daripada sebarang penyuntingan, oleh itu, untuk
mengelak penyalahgunaan, anda tidak dibenarkan menggunakan ciri pemulihan kata laluan.',
'eauthentsent'               => 'Sebuah e-mel pengesahan telah dikirim kepada alamat e-mel tersebut.
Sebelum e-emel lain boleh dikirim kepada alamat tersebut, anda perlu mengikuti segala arahan dalam e-mel tersebut
untuk membuktikan bahawa alamat tersebut memang milik anda.',
'throttled-mailpassword'     => 'Sebuah pengingat kata laluan telah pun
dikirim dalam $1 jam yang lalu. Untuk mengelak penyalahgunaan, hanya satu
pengingat kata laluan akan dikirim pada setiap $1 jam.',
'mailerror'                  => 'Ralat ketika mengirim e-mel: $1',
'acct_creation_throttle_hit' => 'Harap maaf, anda telah pun membuka sebanyak $1 akaun. Anda tidak boleh membuka akaun lagi.',
'emailauthenticated'         => 'Alamat e-mel anda telah disahkan pada $1.',
'emailnotauthenticated'      => 'Alamat e-mel anda belum disahkan. Oleh itu,
e-mel bagi ciri-ciri berikut tidak boleh dikirim.',
'noemailprefs'               => 'Anda perlu menetapkan alamat e-mel terlebih dahulu untuk menggunakan ciri-ciri ini.',
'emailconfirmlink'           => 'Sahkan alamat e-mel anda.',
'invalidemailaddress'        => 'Alamat e-mel tersebut tidak boleh diterima kerana ia tidak sah.
Sila masukkan alamat e-mel yang betul atau kosongkan sahaja ruangan tersebut.',
'accountcreated'             => 'Akaun dibuka',
'accountcreatedtext'         => 'Akaun pengguna bagi $1 telah dibuka.',
'loginlanguagelabel'         => 'Bahasa: $1',

# Password reset dialog
'resetpass'               => 'Set semula kata laluan',
'resetpass_announce'      => 'Anda sedang log masuk dengan kata laluan sementara. Untuk log masuk dengan sempurna, sila tetapkan kata laluan baru di sini:',
'resetpass_header'        => 'Set semula kata laluan',
'resetpass_submit'        => 'Tetapkan kata laluan dan log masuk',
'resetpass_success'       => 'Kata laluan anda ditukar dengan jayanya! Sila tunggu...',
'resetpass_bad_temporary' => 'Kata laluan sementara tidak sah. Anda mungkin telah pun menukar kata laluan atau meminta kata laluan sementara yang baru.',
'resetpass_forbidden'     => 'Anda tidak boleh mengubah kata laluan pada wiki ini.',
'resetpass_missing'       => 'Tiada data borang.',

# Edit page toolbar
'bold_sample'     => 'Teks tebal',
'bold_tip'        => 'Teks tebal',
'italic_sample'   => 'Teks condong',
'italic_tip'      => 'Teks condong',
'link_sample'     => 'Tajuk pautan',
'link_tip'        => 'Pautan dalaman',
'extlink_sample'  => 'http://www.example.com tajuk pautan',
'extlink_tip'     => 'Pautan luar (ingat awalan http://)',
'headline_sample' => 'Teks tajuk',
'headline_tip'    => 'Tajuk peringkat 2',
'math_sample'     => 'Masukkan formula di sini',
'math_tip'        => 'Formula matematik (LaTeX)',
'nowiki_sample'   => 'Masukkan teks tak berformat di sini',
'nowiki_tip'      => 'Abaikan pemformatan wiki',
'image_sample'    => 'Contoh.jpg',
'image_tip'       => 'Imej terbenam',
'media_sample'    => 'Contoh.ogg',
'media_tip'       => 'Pautan fail media',
'sig_tip'         => 'Tandatangan dengan cap waktu',
'hr_tip'          => 'Garis melintang (gunakan dengan hemat)',

# Edit pages
'summary'                   => 'Ringkasan',
'subject'                   => 'Tajuk',
'minoredit'                 => 'Ini adalah suntingan kecil',
'watchthis'                 => 'Pantau laman ini',
'savearticle'               => 'Simpan',
'preview'                   => 'Pratonton',
'showpreview'               => 'Pratonton',
'showlivepreview'           => 'Pratonton langsung',
'showdiff'                  => 'Lihat perubahan',
'anoneditwarning'           => "'''Amaran:''' Anda tidak log masuk. Alamat IP anda akan direkodkan dalam sejarah suntingan laman ini.",
'missingsummary'            => "'''Peringatan:''' Anda tidak menyatakan ringkasan suntingan. Klik '''Simpan''' sekali lagi untuk menyimpan suntingan ini tanpa ringkasan.",
'missingcommenttext'        => 'Sila masukkan komen dalam ruangan di bawah.',
'missingcommentheader'      => "'''Peringatan:''' Anda tidak menyatakan tajuk bagi komen ini. Klik '''Simpan''' sekali lagi untuk menyimpan suntingan ini tanpa tajuk.",
'summary-preview'           => 'Pratonton ringkasan',
'subject-preview'           => 'Pratonton tajuk',
'blockedtitle'              => 'Pengguna disekat',
'blockedtext'               => '<big>\'\'\'Nama pengguna atau alamat IP anda telah disekat.\'\'\'</big>

Sekatan ini dilakukan oleh $1 dengan sebab \'\'$2\'\'.

* Mula: $8
* Tamat: $6
* Pengguna yang disekat: $7

Sila hubungi $1 atau [[{{MediaWiki:grouppage-sysop}}|pentadbir]] yang lain untuk untuk berbincang
mengenai sekatan ini. Anda tidak boleh menggunakan ciri "e-melkan pengguna ini" kecuali
sekiranya anda telah menetapkan alamat e-mel yang sah dalam [[Special:Preferences|keutamaan]] anda dan anda tidak disekat daripada menggunakannya.
Alamat IP semasa anda ialah $3, dan ID sekatan ialah #$5. Sila sertakan salah satu atau kedua-duanya sekali dalam pertanyaan nanti.',
'autoblockedtext'           => 'Alamat IP anda telah disekat secara automatik kerana ia digunakan oleh pengguna lain yang disekat oleh $1.
Berikut ialah sebab yang dinyatakan:

:\'\'$2\'\'

* Mula: $8
* Tamat: $6

Anda boleh menghubungi $1 atau
[[{{MediaWiki:grouppage-sysop}}|pentadbir]] lain untuk membincangkan sekatan ini.

Sila ambil perhatian bahawa anda tidak boleh menggunakan ciri "e-melkan pengguna ini" melainkan anda mempunyai alamat e-mel yang sah
dalam [[Special:Preferences|laman keutamaan]] dan anda tidak disekat daripada menggunakannya.

ID sekatan anda ialah $5. Sila sertakan ID ini dalam pertanyaan anda.',
'blockedoriginalsource'     => "Sumber bagi '''$1'''
ditunjukkan di bawah:",
'blockededitsource'         => "Teks bagi '''suntingan anda''' terhadap '''$1''' ditunjukkan di bawah:",
'whitelistedittitle'        => 'Log masuk dahulu untuk menyunting',
'whitelistedittext'         => 'Anda mesti $1 terlebih dahulu untuk menyunting laman.',
'whitelistreadtitle'        => 'Log masuk dahulu untuk membaca',
'whitelistreadtext'         => 'Anda perlu [[Special:Userlogin|log masuk]] terlebih dahulu untuk membaca laman.',
'whitelistacctitle'         => 'Anda tidak dibenarkan membuka akaun.',
'whitelistacctext'          => 'Supaya dibenarkan membuka akaun dalam wiki ini, anda perlu [[Special:Userlogin|log masuk]] dan mempunyai keizinan yang berkaitan.',
'confirmedittitle'          => 'Pengesahan e-mel diperlukan untuk menyunting',
'confirmedittext'           => 'Anda perlu mengesahkan alamat e-mel anda terlebih dahulu untuk menyunting mana-mana laman. Sila tetapkan dan sahkan alamat e-mel anda melalui [[Special:Preferences|laman keutamaan]].',
'nosuchsectiontitle'        => 'Bahagian tidak wujud',
'nosuchsectiontext'         => 'Anda telah mencuba untuk menyunting bahagian "$1" yang tidak wujud. Oleh itu, suntingan anda tidak boleh disimpan.',
'loginreqtitle'             => 'Log masuk diperlukan',
'loginreqlink'              => 'log masuk',
'loginreqpagetext'          => 'Anda harus $1 untuk dapat melihat laman yang lain.',
'accmailtitle'              => 'Kata laluan dikirim.',
'accmailtext'               => 'Kata laluan bagi "$1" telah dikirim kepada $2.',
'newarticle'                => '(Baru)',
'newarticletext'            => "Anda telah mengikuti pautan ke laman yang belum wujud.
Untuk mencipta laman ini, sila taip dalam kotak di bawah
(lihat [[{{MediaWiki:helppage}}|laman bantuan]] untuk maklumat lanjut).
Jika anda tiba di sini secara tak sengaja, hanya klik butang '''back''' pada pelayar anda.",
'anontalkpagetext'          => "----''Ini ialah laman perbincangan bagi pengguna tanpa nama yang belum membuka akaun atau tidak log masuk. Kami terpaksa menggunakan alamat IP untuk mengenal pasti pengguna tersebut. Alamat IP ini boleh dikongsi oleh ramai pengguna. Sekiranya anda adalah seorang pengguna tanpa nama dan berasa bahawa komen yang tidak kena mengena telah ditujui kepada anda, sila [[Special:Userlogin|buka akaun baru atau log masuk]] untuk mengelakkan sebarang kekeliruan dengan pengguna tanpa nama yang lain.''",
'noarticletext'             => 'Tiada teks dalam laman ini pada masa sekarang. Anda boleh [[Special:Search/{{PAGENAME}}|mencari tajuk bagi laman ini]] dalam laman-laman lain atau [{{fullurl:{{FULLPAGENAME}}|action=edit}} menyunting laman ini].',
'clearyourcache'            => "'''Nota:''' Selepas menyimpan, anda mungkin perlu mengosongkan fail simpanan (''cache'') pelayar anda terlebih dahulu untuk melihat perubahan. '''Mozilla /Firefox/Safari:''' tahan kekunci ''Shift'' ketika mengklik ''Reload'', atau tekan ''Ctrl-Shift-R'' (''Cmd-Shift-R'' pada Apple Mac); '''IE:''' tahan kekunci ''Ctrl'' ketika mengklik ''Refresh'', atau tekan ''Ctrl-F5''; '''Konqueror:''' klik butang ''Reload'', atau tekan ''F5''; pengguna '''Opera''' perlu mengosongkan fail simpanan melalui ''Tools→Preferences''.",
'usercssjsyoucanpreview'    => "<strong>Petua:</strong> Gunakan butang 'Pratonton' untuk menguji CSS/JS baru anda sebelum menyimpan.",
'usercsspreview'            => "'''Ingat bahawa anda hanya melihat pratonton CSS anda, ia belum lagi disimpan!'''",
'userjspreview'             => "'''Ingat bahawa anda hanya menguji/melihat pratonton JavaScript anda, ia belum lagi disimpan!'''",
'userinvalidcssjstitle'     => "'''Amaran:''' Rupa \"\$1\" tidak wujud. Ingat bahawa laman tempahan .css dan .js menggunakan tajuk berhuruf kecil, contohnya {{ns:user}}:Anu/monobook.css tidak sama dengan {{ns:user}}:Anu/Monobook.css.",
'updated'                   => '(Dikemaskinikan)',
'note'                      => '<strong>Catatan:</strong>',
'previewnote'               => '<strong>Ini hanyalah pratonton. Perubahan masih belum disimpan!</strong>',
'previewconflict'           => 'Paparan ini merupakan teks di bahagian atas dalam kotak sunting teks. Teks ini akan disimpan sekiranya anda memilih berbuat demikian.',
'session_fail_preview'      => '<strong>Harap maaf! Kami tidak dapat memproses suntingan anda kerana kehilangan data sesi.
Sila cuba lagi. Jika masalah ini berlanjutan, log keluar dahulu, kemudian log masuk sekali lagi.</strong>',
'session_fail_preview_html' => "<strong>Harap maaf! Kami tidak dapat memproses suntingan anda kerana kehilangan data sesi.</strong>

''Kerana wiki ini membenarkan HTML mentah, pratonton dimatikan sebagai perlindungan daripada serangan JavaScript.''

<strong>Jika ini adalah penyuntingan yang sah, sila cuba lagi. Jika masalah ini berlanjutan, log keluar dahulu, kemudian log masuk sekali lagi.</strong>",
'token_suffix_mismatch'     => '<strong>Suntingan anda telah ditolak kerana pelanggan anda memusnahkan aksara tanda baca
dalam token suntingan. Suntingan tersebut telah ditolak untuk menghalang kerosakan teks rencana.
Hal ini kadangkala berlaku apabila anda menggunakan khidmat proksi tanpa nama berdasarkan web yang bermasalah.</strong>',
'editing'                   => 'Menyunting $1',
'editinguser'               => 'Menyunting pengguna <b>$1</b>',
'editingsection'            => 'Menyunting $1 (bahagian)',
'editingcomment'            => 'Menyunting $1 (komen)',
'editconflict'              => 'Percanggahan penyuntingan: $1',
'explainconflict'           => 'Pengguna lain telah menyunting laman ini ketika anda sedang menyuntingnya.
Kawasan teks di atas mengandungi teks semasa.
Perubahan anda dipaparkan dalam kawasan teks di bawah.
Anda perlu menggabungkan perubahan anda dengan teks semasa.
<b>Hanya</b> teks dalam kawasan teks di atas akan disimpan jika anda menekan
"Simpan laman".<br />',
'yourtext'                  => 'Teks anda',
'storedversion'             => 'Versi yang disimpan',
'nonunicodebrowser'         => '<strong>AMARAN: Pelayar anda tidak mematuhi Unicode. Aksara-aksara bukan ASCII akan dipaparkan dalam kotak sunting sebagai kod perenambelasan (asas 16).</strong>',
'editingold'                => '<strong>AMARAN: Anda sedang
menyunting sebuah semakan yang sudah ketinggalan zaman.
Jika anda menyimpannya, sebarang perubahan yang dibuat selepas tarikh semakan ini akan hilang.</strong>',
'yourdiff'                  => 'Perbezaan',
'copyrightwarning'          => 'Sila ambil perhatian bahawa semua sumbangan kepada {{SITENAME}} akan dikeluarkan di bawah $2 (lihat $1 untuk butiran lanjut). Jika anda tidak mahu tulisan anda disunting sewenang-wenangnya oleh orang lain dan diedarkan secara bebas, maka jangan kirim di sini.<br />
Anda juga berjanji bahawa ini adalah hasil kerja tangan anda sendiri, atau disalin daripada domain awam atau mana-mana sumber bebas lain.
<strong>JANGAN KIRIM KARYA HAK CIPTA ORANG LAIN TANPA KEBENARAN!</strong>',
'copyrightwarning2'         => 'Sila ambil perhatian bahawa semua sumbangan terhadap {{SITENAME}} boleh disunting, diubah, atau dipadam oleh penyumbang lain. Jika anda tidak mahu tulisan anda disunting sewenang-wenangnya, maka jangan kirim di sini.<br />
Anda juga berjanji bahawa ini adalah hasil kerja tangan anda sendiri, atau
disalin daripada domain awam atau mana-mana sumber bebas lain (lihat $1 untuk butiran lanjut).
<strong>JANGAN KIRIM KARYA HAK CIPTA ORANG LAIN TANPA KEBENARAN!</strong>',
'longpagewarning'           => '<strong>AMARAN: Panjang laman ini ialah $1 kilobait.
Terdapat beberapa pelayar web yang mempunyai masalah jika digunakan untuk menyunting laman yang menghampiri ataupun melebihi 32kB.
Sila bahagikan rencana ini, jika boleh.</strong>',
'longpageerror'             => '<strong>RALAT: Panjang teks yang dikirim ialah $1 kilobait,
melebihi had maksimum $2 kilobait. Ia tidak boleh disimpan.</strong>',
'readonlywarning'           => '<strong>AMARAN: Pangkalan data telah dikunci untuk penyenggaraan.
Justeru, anda tidak boleh menyimpan suntingan anda pada masa sekarang.
Anda boleh menyalin teks anda ke dalam komputer anda terlebih dahulu dan simpan teks tersebut di sini pada masa akan datang.</strong>',
'protectedpagewarning'      => '<strong>AMARAN: Laman ini telah dikunci supaya hanya penyelia boleh menyuntingnya.</strong>',
'semiprotectedpagewarning'  => "'''Catatan:''' Laman ini telah dikunci supaya hanya pengguna berdaftar sahaja yang boleh menyuntingnya.",
'cascadeprotectedwarning'   => "'''Amaran:''' Laman ini telah dikunci, oleh itu hanya penyelia boleh menyuntingnya. Ini kerana ia termasuk dalam {{PLURAL:$1|laman|laman-laman}} berikut yang dilindungi secara melata:",
'templatesused'             => 'Templat yang digunakan dalam laman ini:',
'templatesusedpreview'      => 'Templat yang digunakan dalam pratonton ini:',
'templatesusedsection'      => 'Templat yang digunakan dalam bahagian ini:',
'template-protected'        => '(dilindungi)',
'template-semiprotected'    => '(dilindungi separa)',
'nocreatetitle'             => 'Penciptaan laman dihadkan',
'nocreatetext'              => 'Penciptaan laman baru dihadkan pada wiki ini.
Anda boleh berundur dan menyunting laman yang sedia ada, atau [[Special:Userlogin|log masuk]].',
'nocreate-loggedin'         => 'Anda tidak mempunyai keizinan untuk mencipta laman baru dalam wiki ini.',
'permissionserrors'         => 'Tidak Dibenarkan',
'permissionserrorstext'     => 'Anda tidak mempunyai keizinan untuk berbuat demikian atas {{PLURAL:$1|sebab|sebab-sebab}} berikut:',
'recreate-deleted-warn'     => "'''Amaran: Anda sedang mencipta semula sebuah laman yang pernah dihapuskan.''',

Anda harus mempertimbangkan perlunya menyunting laman ini.
Untuk rujukan, berikut ialah log penghapusan bagi laman ini:",

# "Undo" feature
'undo-success' => 'Suntingan ini boleh dibatalkan. Sila semak perbandingan di bawah untuk mengesahkan bahawa anda betul-betul mahu melakukan tindakan ini, kemudian simpan perubahan tersebut.',
'undo-failure' => 'Suntingan tersebut tidak boleh dibatalkan kerana terdapat suntingan pertengahan yang bercanggah.',
'undo-summary' => 'Membatalkan semakan $1 oleh [[Special:Contributions/$2|$2]] ([[User talk:$2|Perbincangan]])',

# Account creation failure
'cantcreateaccounttitle' => 'Akaun tidak dapat dibuka',

# History pages
'revhistory'            => 'Sejarah semakan',
'viewpagelogs'          => 'Lihat log bagi laman ini',
'nohistory'             => 'Tiada sejarah suntingan bagi laman ini.',
'revnotfound'           => 'Semakan tidak dijumpai.',
'revnotfoundtext'       => 'Semakan lama untuk laman yang anda minta tidak dijumpai. Sila semak URL yang anda gunakan untuk membuka laman ini.',
'loadhist'              => 'Memuat sejarah laman',
'currentrev'            => 'Semakan semasa',
'revisionasof'          => 'Semakan pada $1',
'revision-info'         => 'Semakan pada $1 oleh $2',
'previousrevision'      => '← Semakan sebelumnya',
'nextrevision'          => 'Semakan berikutnya →',
'currentrevisionlink'   => 'Semakan semasa',
'cur'                   => 'kini',
'next'                  => 'berikutnya',
'last'                  => 'akhir',
'orig'                  => 'asal',
'page_first'            => 'awal',
'page_last'             => 'akhir',
'histlegend'            => "Pemilihan perbezaan: tandakan butang radio bagi versi-versi yang ingin dibandingkan dan tekan butang ''enter'' atau butang di bawah.<br />
Petunjuk: (kini) = perbezaan dengan versi terkini,
(akhir) = perbezaan dengan versi sebelumnya, K = suntingan kecil.",
'deletedrev'            => '[dihapuskan]',
'histfirst'             => 'Terawal',
'histlast'              => 'Terkini',
'historysize'           => '($1 bait)',
'historyempty'          => '(kosong)',

# Revision feed
'history-feed-title'          => 'Sejarah semakan',
'history-feed-description'    => 'Sejarah semakan bagi laman ini',
'history-feed-item-nocomment' => '$1 pada $2', # user at time
'history-feed-empty'          => 'Laman yang diminta tidak wujud.
Mungkin ia telah dihapuskan atau namanya telah ditukar.
Cuba [[Special:Gelintar|cari]] laman lain yang mungkin berkaitan.',

# Revision deletion
'rev-deleted-comment'         => '(komen dibuang)',
'rev-deleted-user'            => '(nama pengguna dibuang)',
'rev-deleted-event'           => '(entri dibuang)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Semakan ini telah dibuang daripada arkib awam.
Butiran lanjut boleh didapati dalam [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} log penghapusan].
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Semakan ini telah dibuang daripada arkib awam.
Sebagai seorang pentadbir, anda boleh melihatnya.
Butiran lanjut boleh didapati dalam [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} log penghapusan].
</div>',
'rev-delundel'                => 'tunjuk/sembunyi',
'revisiondelete'              => 'Hapus/nyahhapus semakan',
'revdelete-nooldid-title'     => 'Tiada semakan sasaran',
'revdelete-nooldid-text'      => 'Anda tidak menyatakan semakan sasaran.',
'revdelete-selected'          => "{{PLURAL:$2|Versi|Versi-versi}} '''$1''' yang dipilih:",
'logdelete-selected'          => "{{PLURAL:$2|Peristiwa|Peristiwa-peristiwa}} log yang dipilih bagi '''$1:'''",
'revdelete-text'              => 'Semakan dan peristiwa yang dihapuskan masih muncul dalam sejarah laman dan log,
akan tetapi kandungannya tidak boleh dilihat oleh orang awam.

Pentadbir wiki ini boleh melihat kandungan tersebut dan menyahhapuskannya
semula melalui laman ini melainkan mempunyai batasan.',
'revdelete-legend'            => 'Tetapkan batasan:',
'revdelete-hide-text'         => 'Sembunyikan teks semakan',
'revdelete-hide-name'         => 'Sembunyikan tindakan dan sasaran',
'revdelete-hide-comment'      => 'Sembunyikan komen suntingan',
'revdelete-hide-user'         => 'Sembunyikan nama pengguna/IP penyunting',
'revdelete-hide-restricted'   => 'Kenakan batasan ini ke atas semua pengguna, termasuk penyelia',
'revdelete-suppress'          => 'Sekat data daripada semua pengguna, termasuk penyelia',
'revdelete-hide-image'        => 'Sembunyikan kandungan fail',
'revdelete-unsuppress'        => 'Buang batasan pada semakan yang dipulihkan',
'revdelete-log'               => 'Komen log:',
'revdelete-submit'            => 'Kenakan ke atas versi yang dipilih',
'revdelete-logentry'          => 'menukar kebolehnampakan semakan [[$1]]',
'logdelete-logentry'          => 'menukar kebolehnampakan peristiwa bagi [[$1]]',
'revdelete-logaction'         => '$1 semakan ditetapkan kepada mod $2',
'logdelete-logaction'         => '$1 peristiwa bagi [[$3]] ditetapkan kepada mod $2',
'revdelete-success'           => 'Kebolehnampakan semakan ditetapkan.',
'logdelete-success'           => 'Kebolehnampakan peristiwa ditetapkan.',

# Oversight log
'oversightlog'    => 'Log pengawas',
'overlogpagetext' => 'Berikut ialah senarai bagi penghapusan dan sekatan terkini yang melibatkan kandungan
yang terlindung daripada penyelia. Lihat [[Special:Ipblocklist|senarai sekatan IP]] untuk senarai sekatan yang sedang aktif.',

# Diffs
'difference'                => '(Perbezaan antara semakan)',
'loadingrev'                => 'memuat semakan untuk pembezaan',
'lineno'                    => 'Baris $1:',
'editcurrent'               => 'Sunting versi semasa bagi laman ini',
'selectnewerversionfordiff' => 'Pilih versi lebih baru untuk perbandingan',
'selectolderversionfordiff' => 'Pilih versi terdahulu untuk perbandingan',
'compareselectedversions'   => 'Bandingkan versi-versi yang dipilih',
'editundo'                  => 'batal',
'diff-multi'                => '({{PLURAL:$1|Satu|$1}} semakan pertengahan tidak ditunjukkan.)',

# Search results
'searchresults'         => 'Keputusan carian',
'searchresulttext'      => 'Untuk maklumat lanjut tentang carian dalam {{SITENAME}}, sila lihat [[{{MediaWiki:helppage}}|{{int:help}}]].',
'searchsubtitle'        => 'Anda mencari "[[$1]]"',
'searchsubtitleinvalid' => 'Anda mencari "[[$1]]"',
'noexactmatch'          => "'''Tiada laman bertajuk \"\$1\".''' Anda boleh [[:\$1|mencipta laman ini]].",
'titlematches'          => 'Padanan tajuk rencana',
'notitlematches'        => 'Tiada tajuk laman yang sepadan',
'textmatches'           => 'Padanan teks laman',
'notextmatches'         => 'Tiada teks laman yang sepadan',
'prevn'                 => '$1 sebelumnya',
'nextn'                 => '$1 berikutnya',
'viewprevnext'          => 'Lihat ($1) ($2) ($3)',
'showingresults'        => 'Terpapar di bawah adalah hasil pencarian dari <b>$1</b> hingga <b>$2</b>.',
'showingresultsnum'     => 'Terpapar di bawah <b>$3</b> adalah hasil pencarian yang bermula dengan #<b>$2</b>.',
'nonefound'             => "'''Catatan''': Kegagalan pencarian biasanya
disebabkan oleh pencarian perkataan-perkataan yang terlalu umum, seperti \"ada\"
dan \"dari\" yang tidak diindekskan, atau disebabkan oleh pencarian lebih
daripada satu kata kunci (hanya laman yang mengandungi kesemua kata kunci akan ditunjukkan).",
'powersearch'           => 'Cari',
'powersearchtext'       => 'Cari dalam ruang nama:<br />$1<br />$2 Senaraikan pelencongan<br />Cari $3 $9',
'searchdisabled'        => 'Ciri pencarian dalam {{SITENAME}} dimatikan. Anda boleh mencari melalui Google. Sila ambil perhatian bahawa indeks dalam Google mungkin bukan yang terkini.',

# Preferences page
'preferences'              => 'Keutamaan',
'mypreferences'            => 'Keutamaan saya',
'prefs-edits'              => 'Jumlah suntingan:',
'prefsnologin'             => 'Belum log masuk',
'prefsnologintext'         => 'Anda mesti [[Special:Userlogin|log masuk]] terlebih dahulu untuk menetapkan keutamaan.',
'prefsreset'               => 'Keutamaan anda telah diset semula dari storan.',
'qbsettings'               => 'Bar pantas',
'qbsettings-none'          => 'Tiada',
'qbsettings-fixedleft'     => 'Tetap sebelah kiri',
'qbsettings-fixedright'    => 'Tetap sebelah kanan',
'qbsettings-floatingleft'  => 'Berubah-ubah sebelah kiri',
'qbsettings-floatingright' => 'Berubah-ubah sebelah kanan',
'changepassword'           => 'Tukar kata laluan',
'skin'                     => 'Rupa',
'math'                     => 'Matematik',
'dateformat'               => 'Format tarikh',
'datedefault'              => 'Tiada keutamaan',
'datetime'                 => 'Tarikh dan waktu',
'math_failure'             => 'Gagal menghurai',
'math_unknown_error'       => 'ralat yang tidak dikenali',
'math_unknown_function'    => 'fungsi yang tidak dikenali',
'math_lexing_error'        => "ralat ''lexing''",
'math_syntax_error'        => 'ralat sintaks',
'math_image_error'         => 'penukaran PNG gagal; sila pastikan bahawa latex, dvips, gs dan convert dipasang dengan betul',
'math_bad_tmpdir'          => 'Direktori temp matematik tidak boleh ditulis atau dicipta',
'math_bad_output'          => 'Direktori output matematik tidak boleh ditulis atau dicipta',
'math_notexvc'             => 'Atur cara texvc hilang; sila lihat fail math/README untuk maklumat konfigurasi.',
'prefs-personal'           => 'Profil',
'prefs-rc'                 => 'Perubahan terkini',
'prefs-watchlist'          => 'Senarai pantau',
'prefs-watchlist-days'     => 'Had maksimum hari untuk ditunjukkan dalam senarai pantau:',
'prefs-watchlist-edits'    => 'Had maksimum perubahan untuk ditunjukkan dalam senarai pantau penuh:',
'prefs-misc'               => 'Pelbagai',
'saveprefs'                => 'Simpan',
'resetprefs'               => 'Set semula',
'oldpassword'              => 'Kata laluan lama:',
'newpassword'              => 'Kata laluan baru:',
'retypenew'                => 'Ulangi kata laluan baru:',
'textboxsize'              => 'Menyunting',
'rows'                     => 'Baris:',
'columns'                  => 'Lajur:',
'searchresultshead'        => 'Cari',
'resultsperpage'           => 'Jumlah padanan bagi setiap halaman:',
'contextlines'             => 'Bilangan baris untuk dipaparkan bagi setiap capaian:',
'contextchars'             => 'Bilangan askara konteks bagi setiap baris:',
'stub-threshold'           => 'Threshold for <a href="#" class="stub">stub link</a> formatting:',
'recentchangesdays'        => 'Bilangan hari dalam perubahan terkini:',
'recentchangescount'       => 'Bilangan suntingan dalam perubahan terkini:',
'savedprefs'               => 'Keutamaan anda disimpan.',
'timezonelegend'           => 'Zon waktu',
'timezonetext'             => 'Beza waktu dalam jam antara waktu tempatan anda dengan waktu UTC (8 untuk Kuala Lumpur).',
'localtime'                => 'Waktu tempatan',
'timezoneoffset'           => 'Imbangan¹',
'servertime'               => 'Waktu pelayan',
'guesstimezone'            => 'Gunakan tetapan pelayar saya',
'allowemail'               => 'Benarkan e-mel daripada pengguna lain',
'defaultns'                => 'Cari dalam ruang nama ini secara lalai:',
'default'                  => 'lalai',
'files'                    => 'Fail',

# User rights
'userrights-lookup-user'      => 'Urus kumpulan pengguna',
'userrights-user-editname'    => 'Masukkan nama pengguna:',
'editusergroup'               => 'Sunting Kumpulan Pengguna',
'userrights-editusergroup'    => 'Ubah kumpulan pengguna',
'saveusergroups'              => 'Simpan Kumpulan Pengguna',
'userrights-groupsmember'     => 'Ahli bagi:',
'userrights-groupsavailable'  => 'Kumpulan sedia ada:',
'userrights-groupshelp'       => 'Sila pilih kumpulan untuk menambah atau membuang pengguna tersebut.
Kumpulan yang tidak dipilih tidak akan diubah. Anda boleh mengecualikan sebarang kumpulan dengan CTRL + klik',
'userrights-reason'           => 'Sebab perubahan:',
'userrights-available-none'   => 'Anda tidak boleh mengubah keahlian kumpulan.',
'userrights-available-add'    => 'Anda boleh menambahkan penggunan ke dalam $1.',
'userrights-available-remove' => 'Anda boleh membuang pengguna daripada $1.',

# Groups
'group'               => 'Kumpulan:',
'group-autoconfirmed' => 'Pengguna yang disahkan secara automatik',
'group-bot'           => 'Bot',
'group-sysop'         => 'Penyelia',
'group-bureaucrat'    => 'Birokrat',
'group-all'           => '(semua)',

'group-autoconfirmed-member' => 'Pengguna yang disahkan secara automatik',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Penyelia',
'group-bureaucrat-member'    => 'Birokrat',

'grouppage-autoconfirmed' => '{{ns:project}}:Pengguna yang disahkan secara automatik',
'grouppage-bot'           => '{{ns:project}}:Bot',
'grouppage-sysop'         => '{{ns:project}}:Pentadbir',
'grouppage-bureaucrat'    => '{{ns:project}}:Birokrat',

# User rights log
'rightslog'      => 'Log hak pengguna',
'rightslogtext'  => 'Ini ialah log bagi perubahan hak pengguna.',
'rightslogentry' => 'menukar keahlian kumpulan bagi $1 daripada $2 kepada $3',
'rightsnone'     => '(tiada)',

# Recent changes
'nchanges'                          => '$1 perubahan',
'recentchanges'                     => 'Perubahan terkini',
'recentchangestext'                 => 'Jejaki perubahan terkini dalam {{SITENAME}} pada laman ini.',
'recentchanges-feed-description'    => 'Jejaki perubahan terkini dalam {{SITENAME}} pada suapan ini.',
'rcnote'                            => "Berikut ialah '''$1''' perubahan terakhir sejak '''$2''' hari yang lalu sehingga $3.",
'rcnotefrom'                        => 'Berikut ialah semua perubahan sejak <b>$2</b> (sehingga <b>$1</b>).',
'rclistfrom'                        => 'Tunjukkan perubahan terbaru bermula dari $1',
'rcshowhideminor'                   => '$1 suntingan kecil',
'rcshowhidebots'                    => '$1 bot',
'rcshowhideliu'                     => '$1 pengguna log masuk',
'rcshowhideanons'                   => '$1 pengguna tanpa nama',
'rcshowhidepatr'                    => '$1 suntingan yang telah diperiksa',
'rcshowhidemine'                    => '$1 suntingan saya',
'rclinks'                           => 'Tunjukkan $1 perubahan terakhir sejak $2 hari yang lalu<br />$3',
'diff'                              => 'beza',
'hist'                              => 'sej',
'hide'                              => 'Sembunyikan',
'show'                              => 'Tunjukkan',
'minoreditletter'                   => 'k',
'newpageletter'                     => 'B',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 pemantau]',
'rc_categories'                     => 'Hadkan kepada kategori (asingkan dengan "|")',
'rc_categories_any'                 => 'Semua',
'newsectionsummary'                 => 'Bahagian baru:',

# Recent changes linked
'recentchangeslinked'          => 'Perubahan berkaitan',
'recentchangeslinked-title'    => 'Perubahan berkaitan dengan $1',
'recentchangeslinked-noresult' => 'Tiada perubahan pada semua laman yang dipaut dalam tempoh yang diberikan.',
'recentchangeslinked-summary'  => "Laman khas ini menyenaraikan perubahan terkini bagi laman-laman yang dipaut. Laman-laman yang terdapat dalam senarai pantau anda ditandakan dengan '''teks tebal'''.",

# Upload
'upload'                      => 'Muat naik fail',
'uploadbtn'                   => 'Muat naik',
'reupload'                    => 'Muat naik sekali lagi',
'reuploaddesc'                => 'Kembali ke borang muat naik',
'uploadnologin'               => 'Belum log masuk',
'uploadnologintext'           => 'Anda perlu [[Special:Userlogin|log masuk]]
terlebih dahulu untuk memuat naik fail.',
'upload_directory_read_only'  => 'Direktori muat naik ($1) tidak boleh ditulis oleh pelayan web.',
'uploaderror'                 => 'Ralat muat naik',
'uploadtext'                  => "Gunakan borang di bawah untuk memuat naik fail. Untuk melihat atau mencari imej yang sudah dimuat naik, sila ke [[Special:Imagelist|senarai fail yang dimuat naik]]. Muat naik dan penghapusan akan direkodkan dalam [[Special:Log/upload|log muat naik]].

Untuk menyertakan imej tersebut dalam sesebuah laman, sila masukkan teks
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Fail.jpg]]</nowiki>''' atau
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Fail.png|teks alternatif]]</nowiki>'''. Anda juga boleh menggunakan
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Fail.ogg]]</nowiki>''' untuk memaut secara terus.",
'uploadlog'                   => 'log muat naik',
'uploadlogpage'               => 'Log muat naik',
'uploadlogpagetext'           => 'Berikut ialah senarai terkini bagi fail yang dimuat naik.',
'filename'                    => 'Nama fail',
'filedesc'                    => 'Ringkasan',
'fileuploadsummary'           => 'Ringkasan:',
'filestatus'                  => 'Status hak cipta',
'filesource'                  => 'Sumber',
'uploadedfiles'               => 'Fail yang telah dimuat naik',
'ignorewarning'               => 'Abaikan amaran dan simpan juga fail ini.',
'ignorewarnings'              => 'Abaikan mana-mana amaran.',
'minlength1'                  => 'Panjang nama fail mestilah sekurang-kurangnya satu huruf.',
'illegalfilename'             => 'Nama fail "$1" mengandungi aksara yang tidak dibenarkan dalam tajuk laman. Sila tukar nama fail ini dan muat naik sekali lagi.',
'badfilename'                 => 'Nama fail telah ditukar kepada "$1".',
'filetype-badmime'            => 'Memuat naik fail jenis MIME "$1" adalah tidak dibenarkan.',
'filetype-badtype'            => "Jenis fail '''\".\$1\"''' adalah dilarang.
: Jenis-jenis fail yang dibenarkan: \$2",
'filetype-missing'            => 'Fail ini tidak mempunyai sambungan (contohnya ".jpg").',
'large-file'                  => 'Saiz fail ini ialah $2. Anda dinasihati supaya memuat naik fail yang tidak melebihi $1.',
'largefileserver'             => 'Fail ini telah melebihi had muat naik pelayan web.',
'emptyfile'                   => 'Fail yang dimuat naik adalah kosong. Ini mungkin disebabkan oleh kesilapan menaip nama fail. Sila pastikan bahawa anda betul-betul mahu memuat naik fail ini.',
'fileexists'                  => 'Sebuah fail dengan nama ini telah pun wujud. Sila semak $1 jika anda tidak pasti bahawa anda mahu menukarnya.',
'fileexists-extension'        => 'Sebuah fail dengan nama yang sama telah pun wujud:<br />
Nama fail yang dimuat naik: <strong><tt>$1</tt></strong><br />
Nama fail yang sedia ada: <strong><tt>$2</tt></strong><br />
Sila pilih nama lain.',
'fileexists-thumb'            => "'''<center>Imej sedia ada</center>'''",
'fileexists-thumbnail-yes'    => 'Fail ini kelihatan seperti sebuah imej yang telah dikecilkan <i>(imej ringkas)</i>. Sila semak fail <strong><tt>$1</tt></strong>.<br />
Jika fail yang disemak itu adalah sama dengan yang saiz asal, maka anda tidak perlu memuat naik imej ringkas tambahan.',
'file-thumbnail-no'           => 'Nama fail ini bermula dengan <strong><tt>$1</tt></strong>. Barangkali ia adalah sebuah imej yang telah dikecilkan <i>(imej ringkas)</i>.
Jika anda memiliki imej ini dalam leraian penuh, sila muat naik fail tersebut, sebaliknya sila tukar nama fail ini.',
'fileexists-forbidden'        => 'Sebuah fail dengan nama ini telah pun wujud. Sila berundur dan muat naik fail ini dengan nama lain. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Sebuah dail dengan nama ini telah pun wujud dalam simpanan fail kongsi. Sila berundur dan muat naik fail ini dengan nama lain. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Muat naik berjaya',
'uploadwarning'               => 'Amaran muat naik',
'savefile'                    => 'Simpan fail',
'uploadedimage'               => 'memuat naik "[[$1]]"',
'overwroteimage'              => 'memuat naik versi baru bagi "[[$1]]"',
'uploaddisabled'              => 'Ciri muat naik dimatikan',
'uploaddisabledtext'          => 'Ciri muat naik fail dimatikan pada wiki ini.',
'uploadscripted'              => 'Fail ini mengandungi kod HTML atau skrip yang boleh disalahtafsirkan oleh pelayar web.',
'uploadcorrupt'               => 'Fail tersebut rosak atau mempunyai sambungan yang salah. Sila periksa fail tersebut dan cuba lagi.',
'uploadvirus'                 => 'Fail tersebut mengandungi virus! Butiran: $1',
'sourcefilename'              => 'Nama fail sumber',
'destfilename'                => 'Nama fail destinasi',
'watchthisupload'             => 'Pantau laman ini',
'filewasdeleted'              => 'Sebuah fail dengan nama ini pernah dimuat naik dan akhirnya dihapuskan. Anda seharusnya menyemak $1 sebelum meneruskan percubaan untuk memuat naik fail ini.',

'upload-proto-error'      => 'Protokol salah',
'upload-proto-error-text' => 'Muat naik jauh memerlukan URL yang dimulakan dengan <code>http://</code> atau <code>ftp://</code>.',
'upload-file-error'       => 'Ralat dalaman',
'upload-file-error-text'  => 'Ralat dalaman telah berlaku ketika mencipta fail sementara pada komputer pelayan. Sila hubungi pentadbir sistem.',
'upload-misc-error'       => 'Ralat muat naik yang tidak diketahui',
'upload-misc-error-text'  => 'Ralat yang tidak diketahui telah berlaku ketika muat naik. Sila pastikan bahawa URL tersebut sah dan boleh dicapai kemudian cuba lagi. Jika masalah ini berterusan, sila hubungi pentadbir sistem.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL tidak dapat dicapai',
'upload-curl-error6-text'  => 'URL yang dinyatakan tidak dapat dicapai. Sila pastikan bahawa URL dan tapak web tersebut hidup.',
'upload-curl-error28'      => 'Waktu henti muat naik',
'upload-curl-error28-text' => 'Tapak web tersebut terlalu lambat bertindak balas. Sila pastikan bahawa tapak web tersebut hidup, tunggu sebentar dan cuba lagi. Anda boleh mencuba lagi pada waktu yang kurang sibuk.',

'license'            => 'Lesen',
'nolicense'          => 'Tidak dipilih',
'license-nopreview'  => '(Tiada pratonton)',
'upload_source_url'  => ' (URL yang boleh diakses oleh orang awam)',
'upload_source_file' => ' (fail dalam komputer anda)',

# Image list
'imagelist'                 => 'Senarai fail',
'imagelisttext'             => "Berikut ialah senarai bagi '''$1''' fail yang disusun secara $2.",
'getimagelist'              => 'mengambil senarai imej',
'ilsubmit'                  => 'Cari',
'showlast'                  => 'Paparkan $1 imej terbaru yang telah diisih $2.',
'byname'                    => 'mengikut nama',
'bydate'                    => 'mengikut tarikh',
'bysize'                    => 'mengikut saiz',
'imgdelete'                 => 'hapus',
'imgdesc'                   => 'hurai',
'imgfile'                   => 'fail',
'filehist'                  => 'Sejarah fail',
'filehist-help'             => 'Klik pada tarikh/waktu untuk melihat rupa fail tersebut pada waktu silam.',
'filehist-deleteall'        => 'hapuskan semua',
'filehist-deleteone'        => 'hapuskan ini',
'filehist-revert'           => 'balik',
'filehist-current'          => 'semasa',
'filehist-datetime'         => 'Tarikh/Waktu',
'filehist-user'             => 'Pengguna',
'filehist-dimensions'       => 'Ukuran',
'filehist-filesize'         => 'Saiz fail',
'filehist-comment'          => 'Komen',
'imagelinks'                => 'Pautan',
'linkstoimage'              => 'Laman-laman berikut mengandungi pautan ke fail ini:',
'nolinkstoimage'            => 'Tiada laman yang mengandungi pautan ke fail ini.',
'sharedupload'              => 'Fail ini adalah fail muat naik kongsi dan boleh digunakan oleh projek lain.',
'shareduploadwiki'          => 'Sila lihat $1 untuk maklumat lanjut.',
'shareduploadwiki-linktext' => 'laman huraian fail',
'noimage'                   => 'Tiada fail dengan nama ini. Anda boleh $1.',
'noimage-linktext'          => 'memuat naik fail baru',
'uploadnewversion-linktext' => 'Muat naik versi baru bagi fail ini',
'imagelist_date'            => 'Tarikh',
'imagelist_name'            => 'Nama',
'imagelist_user'            => 'Pengguna',
'imagelist_size'            => 'Saiz',
'imagelist_description'     => 'Huraian',
'imagelist_search_for'      => 'Cari nama imej:',

# File reversion
'filerevert'                => 'Balikkan $1',
'filerevert-legend'         => 'Balikkan fail',
'filerevert-intro'          => '<span class="plainlinks">Anda sedang menmbalikkan \'\'\'[[Media:$1|$1]]\'\'\' kepada [$4 versi pada $3, $2].</span>',
'filerevert-comment'        => 'Komen:',
'filerevert-defaultcomment' => 'Dibalikkan kepada versi pada $2, $1',
'filerevert-submit'         => 'Balikkan',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' telah dibalikkan kepada [$4 versi pada $3, $2].</span>',
'filerevert-badversion'     => 'There is no previous local version of this file with the provided timestamp.',

# File deletion
'filedelete'             => 'Hapuskan $1',
'filedelete-legend'      => 'Hapuskan fail',
'filedelete-intro'       => "Anda sedang menghapuskan '''[[Media:$1|$1]]'''.",
'filedelete-intro-old'   => '<span class="plainlinks">Anda sedang menghapuskan versi \'\'\'[[Media:$1|$1]]\'\'\' pada [$4 $3, $2].</span>',
'filedelete-comment'     => 'Komen:',
'filedelete-submit'      => 'Hapus',
'filedelete-success'     => "'''$1''' telah dihapuskan.",
'filedelete-success-old' => '<span class="plainlinks">Versi \'\'\'[[Media:$1|$1]]\'\'\' pada $3, $2 telah dihapuskan.</span>',
'filedelete-nofile'      => "'''$1''' tidak wujud dalam tapak web ini.",
'filedelete-nofile-old'  => "Tiada versi arkib bagi '''$1''' dengan sifat-sifat yang dinyatakan.",
'filedelete-iscurrent'   => 'Anda telah mencuba untuk menghapuskan versi terkini bagi fail ini. Sila balikkannya kepada versi yang lama terlebih dahulu.',

# MIME search
'mimesearch'         => 'Carian MIME',
'mimesearch-summary' => 'Anda boleh menggunakan laman ini untuk mencari fail mengikut jenis MIME. Format input ialah "jenis/subjenis", contohnya <tt>image/jpeg</tt>.',
'mimetype'           => 'Jenis MIME:',
'download'           => 'muat turun',

# Unwatched pages
'unwatchedpages'         => 'Laman tidak dipantau',

# List redirects
'listredirects'         => 'Senarai pelencongan',

# Unused templates
'unusedtemplates'         => 'Templat tidak digunakan',
'unusedtemplatestext'     => 'Berikut ialah senarai templat yang tidak disertakan dalam laman lain. Sila pastikan bahawa anda menyemak pautan lain ke templat-templat tersebut sebelum menghapuskannya.',
'unusedtemplateswlh'      => 'pautan-pautan lain',

# Random redirect
'randomredirect'         => 'Pelencongan rawak',
'randomredirect-nopages' => 'Tiada pelencongan dalam ruang nama ini.',

# Statistics
'statistics'             => 'Statistik',
'sitestats'              => 'Statistik {{SITENAME}}',
'userstats'              => 'Statistik pengguna',
'sitestatstext'          => "Terdapat sejumlah '''\$1''' laman dalam pangkalan data kami.
Jumlah ini termasuklah laman \"perbincangan\", laman mengenai {{SITENAME}}, laman ringkas,
pelencongan, dan lain-lain yang tidak layak menjadi laman kandungan.
Dengan mengecualikan laman-laman ini, terdapat sejumlah \$2 laman yang barangkali dikira
sah.

'''\$8''' fail telah dimuat naik.

Terdapat sejumlah '''\$3''' paparan laman, dan '''\$4''' penyuntingan dilakukan
sejak {{SITENAME}} dipasang.
Secara purata terdapat '''\$5''' suntingan bagi setiap laman, dan '''\$6''' paparan bagi suntingan.

Panjang [http://meta.wikimedia.org/wiki/Help:Job_queue barisan tugas] ialah '''\$7'''.",
'userstatstext'          => "Terdapat '''$1''' [[Special:Listusers|pengguna]] berdaftar, dengan
'''$2''' (atau '''$4%''') daripadanya mempunyai hak $5.",
'statistics-mostpopular' => 'Laman dilihat terbanyak',

'disambiguations'         => 'Laman penyahtaksaan',
'disambiguationspage'     => 'Template:disambig',
'disambiguations-text'    => "Laman-laman berikut mengandungi pautan ke '''laman penyahtaksaan'''. Pautan ini sepatutnya ditujukan kepada topik yang sepatutnya.<br />Sesebuah laman dianggap sebagai laman penyahtaksaan jika ia menggunakan templat yang dipaut dari [[MediaWiki:disambiguationspage]]",

'doubleredirects'         => 'Pelencongan berganda',
'doubleredirectstext'     => 'Laman ini menyenaraikan laman yang melencong ke laman pelencongan yang lain. Setiap baris mengandungi pautan ke laman pelencongan pertama dan kedua, serta baris pertama bagi teks pelencongan kedua, lazimnya merupakan laman sasaran "sebenar", yang sepatutnya ditujui oleh pelencongan pertama.',

'brokenredirects'         => 'Pelencongan rosak',
'brokenredirectstext'     => 'Pelencongan-pelencongan berikut memaut ke laman yang tidak wujud:',
'brokenredirects-edit'    => '(sunting)',
'brokenredirects-delete'  => '(hapus)',

'withoutinterwiki'         => 'Laman tanpa pautan bahasa',
'withoutinterwiki-header'  => 'Laman-laman berikut tidak mempunyai pautan ke versi bahasa lain:',

'fewestrevisions'         => 'Rencana dengan semakan tersedikit',

# Miscellaneous special pages
'nbytes'                          => '$1 bait',
'ncategories'                     => '$1 kategori',
'nlinks'                          => '$1 pautan',
'nmembers'                        => '$1 ahli',
'nrevisions'                      => '$1 semakan',
'nviews'                          => 'Dilihat $1 kali',
'specialpage-empty'               => 'Tiada keputusan bagi laporan ini.',
'lonelypages'                     => 'Laman yatim',
'lonelypagestext'                 => 'Laman-laman berikut tidak dipaut dari laman lain dalam wiki ini.',
'uncategorizedpages'              => 'Laman tanpa kategori',
'uncategorizedcategories'         => 'Kategori tanpa kategori',
'uncategorizedimages'             => 'Imej tanpa kategori',
'uncategorizedtemplates'          => 'Templat tanpa kategori',
'unusedcategories'                => 'Kategori tidak digunakan',
'unusedimages'                    => 'Imej tidak digunakan',
'popularpages'                    => 'Laman popular',
'wantedcategories'                => 'Kategori dikehendaki',
'wantedpages'                     => 'Laman dikehendaki',
'mostlinked'                      => 'Laman dipaut terbanyak',
'mostlinkedcategories'            => 'Kategori dipaut terbanyak',
'mostlinkedtemplates'             => 'Templat dipaut terbanyak',
'mostcategories'                  => 'Rencana dengan kategori terbanyak',
'mostimages'                      => 'Imej dipaut terbanyak',
'mostrevisions'                   => 'Rencana dengan semakan terbanyak',
'allpages'                        => 'Semua laman',
'prefixindex'                     => 'Indeks awalan',
'randompage'                      => 'Laman rawak',
'randompage-nopages'              => 'Tiada laman dalam ruang nama ini.',
'shortpages'                      => 'Laman pendek',
'longpages'                       => 'Laman panjang',
'deadendpages'                    => 'Laman buntu',
'deadendpagestext'                => 'Laman-laman berikut tidak mengandungi pautan ke laman lain di wiki ini.',
'protectedpages'                  => 'Laman dilindungi',
'protectedpagestext'              => 'Laman-laman berikut dilindungi daripada pemindahan dan penyuntingan',
'protectedpagesempty'             => 'Tiada laman yang dilindungi dengan kriteria ini.',
'listusers'                       => 'Senarai pengguna',
'specialpages'                    => 'Laman khas',
'spheading'                       => 'Laman khas untuk semua pengguna',
'restrictedpheading'              => 'Laman khas terhad',
'rclsub'                          => '(dengan laman-laman yang dipaut dari "$1")',
'newpages'                        => 'Laman baru',
'newpages-username'               => 'Nama pengguna:',
'ancientpages'                    => 'Laman lapuk',
'intl'                            => 'Pautan antara bahasa',
'move'                            => 'Pindah',
'movethispage'                    => 'Pindahkan laman ini',
'unusedimagestext'                => '<p>Sila ambil perhatian bahawa
mungkin terdapat tapak web lain yang mengandungi pautan ke imej ini
menggunakan URL langsung walaupun ia disenaraikan di sini.</p>',
'unusedcategoriestext'            => 'Laman-laman kategori berikut wujud walaupun tiada rencana atau kategori lain menggunakannya.',

# Book sources
'booksources'               => 'Sumber buku',
'booksources-search-legend' => 'Cari sumber buku',
'booksources-go'            => 'Pergi',
'booksources-text'          => 'Berikut ialah senarai pautan ke tapak web lain yang menjual buku baru dan terpakai,
serta mungkin mempunyai maklumat lanjut mengenai buku yang anda cari:',

'categoriespagetext' => 'Kategori-kategori berikut wujud dalam wiki ini.',
'data'               => 'Data',
'userrights'         => 'Pengurusan hak pengguna',
'groups'             => 'Kumpulan pengguna',
'alphaindexline'     => '$1 hingga $2',
'version'            => 'Versi',

# Special:Log
'specialloguserlabel'  => 'Pengguna:',
'speciallogtitlelabel' => 'Tajuk:',
'log'                  => 'Log',
'all-logs-page'        => 'Semua log',
'log-search-legend'    => 'Cari log',
'log-search-submit'    => 'Pergi',
'alllogstext'          => 'Berikut ialah gabungan bagi semua log yang ada bagi {{SITENAME}}.
Anda boleh menapis senarai ini dengan memilih jenis log, nama pengguna atau nama laman yang terjejas.',
'logempty'             => 'Tiada item yang sepadan dalam log.',
'log-title-wildcard'   => 'Cari semua tajuk yang bermula dengan teks ini',

# Special:Allpages
'nextpage'          => 'Halaman berikutnya ($1)',
'prevpage'          => 'Halaman sebelumnya ($1)',
'allpagesfrom'      => 'Tunjukkan laman bermula pada:',
'allarticles'       => 'Semua rencana',
'allinnamespace'    => 'Semua laman (ruang nama $1)',
'allnotinnamespace' => 'Semua laman (bukan dalam ruang nama $1)',
'allpagesprev'      => 'Sebelumnya',
'allpagesnext'      => 'Berikutnya',
'allpagessubmit'    => 'Pergi',
'allpagesprefix'    => 'Tunjukkan laman dengan awalan:',
'allpagesbadtitle'  => 'Tajuk laman yang dinyatakan tidak sah atau mempunyai awalam antara bahasa atau antara wiki. Ia mungkin mengandungi aksara yang tidak boleh digunakan dalam tajuk laman.',
'allpages-bad-ns'   => '{{SITENAME}} tidak mempunyai ruang nama "$1".',

# Special:Listusers
'listusersfrom'      => 'Tunjukkan pengguna bermula pada:',
'listusers-submit'   => 'Tunjuk',
'listusers-noresult' => 'Tiada pengguna dijumpai.',

# E-mail user
'mailnologin'     => 'Tiada alamat e-mel',
'mailnologintext' => 'Anda perlu [[Special:Userlogin|log masuk]]
terlebih dahulu dan mempunyai alamat e-mel yang sah dalam
[[Special:Preferences|laman keutamaan]] untuk mengirim e-mel kepada pengguna lain.',
'emailuser'       => 'Kirim e-mel kepada pengguna ini',
'emailpage'       => 'E-mel pengguna',
'emailpagetext'   => 'Jika pengguna ini telah memasukkan
alamat e-mel yang sah dalam laman keutamaan, beliau akan menerima sebuah e-mel
yang mengandungi mesej yang diisi dalam borang di bawah. Alamat e-mel yang
ditetapkan dalam keutamaan anda akan muncul dalam e-mel tersebut sebagai
alamat "Daripada" supaya si penerima boleh membalasnya.',
'usermailererror' => 'Objek Mail memulangkan ralat:',
'defemailsubject' => 'E-mel {{SITENAME}}',
'noemailtitle'    => 'Tiada alamat e-mel',
'noemailtext'     => 'Pengguna ini tidak menetapkan alamat e-mel yang sah,
atau telah memilih untuk tidak menerima e-mel daripada pengguna lain.',
'emailfrom'       => 'Daripada',
'emailto'         => 'Kepada',
'emailsubject'    => 'Perkara',
'emailmessage'    => 'Mesej',
'emailsend'       => 'Kirim',
'emailccme'       => 'Kirim salinan mesej ini kepada saya.',
'emailccsubject'  => 'Salinan bagi mesej anda kepada $1: $2',
'emailsent'       => 'E-mel dikirim',
'emailsenttext'   => 'E-mel anda telah dikirim.',

# Watchlist
'watchlist'            => 'Senarai pantau',
'mywatchlist'          => 'Senarai pantau saya',
'watchlistfor'         => "(bagi '''$1''')",
'nowatchlist'          => 'Tiada item dalam senarai pantau anda.',
'watchlistanontext'    => 'Sila $1 terlebih dahulu untuk melihat atau menyunting senarai pantau anda.',
'watchnologin'         => 'Belum log masuk',
'watchnologintext'     => 'Anda mesti [[Special:Userlogin|log masuk]] terlebih dahulu untuk mengubah senarai pantau.',
'addedwatch'           => 'Senarai pantau dikemaskinikan',
'addedwatchtext'       => "Laman \"[[:\$1]]\" telah ditambahkan ke dalam [[Special:Watchlist|senarai pantau]] anda.
Semua perubahan bagi laman tersebut dan laman perbincangannya akan disenaraikan di sana,
dan tajuk laman tersebut juga akan ditonjolkan dalam '''teks tebal''' di [[Special:Recentchanges|senarai perubahan terkini]]
untuk memudahkan anda.

Jika anda mahu membuang laman tersebut daripada senarai pantau, klik \"Nyahpantau\" pada bar sisi.",
'removedwatch'         => 'Dibuang daripada senarai pantau',
'removedwatchtext'     => 'Laman "[[:$1]]" telah dibuang daripada senarai pantau anda.',
'watch'                => 'Pantau',
'watchthispage'        => 'Pantau laman ini',
'unwatch'              => 'Nyahpantau',
'unwatchthispage'      => 'Berhenti memantau',
'notanarticle'         => 'Bukan laman kandungan',
'watchnochange'        => 'Tiada perubahan pada laman-laman yang dipantau dalam tempoh yang ditunjukkan.',
'watchlist-details'    => '$1 laman dipantau (tidak termasuk laman perbincangan).',
'wlheader-enotif'      => '* Pemberitahuan melalui e-mel diaktifkan.',
'wlheader-showupdated' => "* Laman-laman yang telah diubah sejak kunjungan terakhir anda dipaparkan dalam '''teks tebal'''",
'watchmethod-recent'   => 'menyemak laman yang dipantau dalam suntingan-suntingan terkini',
'watchmethod-list'     => 'menyemak suntingan terkini pada laman-laman yang dipantau',
'watchlistcontains'    => 'Terdapat $1 laman dalam senarai pantau anda.',
'iteminvalidname'      => "Terdapat masalah dengan item '$1', nama tidak sah...",
'wlnote'               => "Berikut ialah '''$1''' perubahan terakhir sejak '''$2''' jam yang lalu.",
'wlshowlast'           => 'Tunjukkan $1 jam / $2 hari yang lalu / $3.',
'watchlist-show-bots'  => 'Tunjukkan suntingan bot',
'watchlist-hide-bots'  => 'Sembunyikan suntingan bot',
'watchlist-show-own'   => 'Tunjukkan suntingan saya',
'watchlist-hide-own'   => 'Sembunyikan suntingan saya',
'watchlist-show-minor' => 'Tunjukkan suntingan kecil',
'watchlist-hide-minor' => 'Sembunyikan suntingan kecil',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Memantau...',
'unwatching' => 'Sedang menyahpantau...',

'enotif_mailer'                => 'Sistem Pemberitahuan {{SITENAME}}',
'enotif_reset'                 => 'Tandakan semua laman sebagai telah dikunjungi',
'enotif_newpagetext'           => 'Ini adalah sebuah laman baru.',
'enotif_impersonal_salutation' => 'Pengguna {{SITENAME}}',
'changed'                      => 'diubah',
'created'                      => 'dicipta',
'enotif_subject'               => 'Laman $PAGETITLE di {{SITENAME}} telah $CHANGEDORCREATED oleh $PAGEEDITOR',
'enotif_lastvisited'           => 'Lihat $1 untuk semua perubahan sejak kunjungan terakhir anda.',
'enotif_lastdiff'              => 'Rujuk $1 untuk melihat perubahan ini.',
'enotif_anon_editor'           => 'pengguna tanpa nama $1',
'enotif_body'                  => 'Saudara/saudari $WATCHINGUSERNAME,


Laman $PAGETITLE di {{SITENAME}} telah $CHANGEDORCREATED pada $PAGEEDITDATE oleh $PAGEEDITOR, sila lihat $PAGETITLE_URL untuk versi semasa.

$NEWPAGE

Ringkasan: $PAGESUMMARY $PAGEMINOREDIT

Anda boleh menghubungi si penyunting melalui:
e-mel: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Tiada pemberitahuan lain akan dikirim selagi anda tidak mengunjungi laman tersebut. Anda juga boleh mengeset semula tanda pemberitahuan bagi semua laman dalam senarai pantau anda.

         Sistem pemberitahuan {{SITENAME}}

--
Untuk mengubah tetapan senarai pantau anda, sila kunjungi
{{fullurl:{{ns:special}}:Watchlist/edit}}

Maklum balas dan bantuan:
{{fullurl:{{MediaWiki:helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Hapus laman',
'confirm'                     => 'Sahkan',
'excontent'                   => "kandungan: '$1'",
'excontentauthor'             => "Kandungan: '$1' (dan satu-satunya penyumbang ialah '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'               => "kandungan sebelum pengosongan ialah: '$1'",
'exblank'                     => 'laman tersebut kosong',
'confirmdelete'               => 'Sah hapus',
'deletesub'                   => '(Menghapuskan "$1")',
'historywarning'              => '<b>Amaran</b>: Laman yang ingin anda hapuskan mengandungi sejarah:',
'confirmdeletetext'           => 'Anda sudah hendak menghapuskan sebuah laman atau imej
berserta semua sejarahnya daripada pangkalan data secara kekal.
Sila sahkan bahawa anda memang hendak berbuat demikian, anda faham akan
akibatnya, dan perbuatan anda mematuhi
[[{{MediaWiki:policy-url}}]].',
'actioncomplete'              => 'Tindakan berjaya',
'deletedtext'                 => '"$1" telah dihapuskan.
Sila lihat $2 untuk rekod penghapusan terkini.',
'deletedarticle'              => 'menghapuskan "[[$1]]"',
'dellogpage'                  => 'Log penghapusan',
'dellogpagetext'              => 'Berikut ialah senarai penghapusan terkini.',
'deletionlog'                 => 'log penghapusan',
'reverted'                    => 'Dibalikkan kepada semakan sebelumnya',
'deletecomment'               => 'Sebab penghapusan',
'rollback'                    => 'Undurkan suntingan.',
'rollback_short'              => 'Undur',
'rollbacklink'                => 'undur',
'rollbackfailed'              => 'Pengunduran gagal',
'cantrollback'                => 'Suntingan tersebut tidak dapat dibalikkan: penyumbang terakhir adalah satu-satunya pengarang bagi rencana ini.',
'alreadyrolled'               => 'Tidak dapat membalikkan suntingan terakhir bagi [[:$1]]
oleh [[User:$2|$2]] ([[User talk:$2|Perbincangan]]); terdapat pengguna yang telah berbuat demikian. 

Suntingan terakhir telah dibuat oleh [[User:$3|$3]] ([[User talk:$3|Perbincangan]]).',
'editcomment'                 => 'Komen suntingan: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'Membalikkan suntingan oleh [[Special:Contributions/$2|$2]] ([[User talk:$2|Perbincangan]]) kepada versi terakhir oleh [[User:$1|$1]]',
'rollback-success'            => 'Membalikkan suntingan oleh $1 kepada versi terakhir oleh $2.',
'sessionfailure'              => 'Terdapat sedikit masalah pada sesi log masuk anda.
Tindakan ini telah dibatalkan untuk mencegah perampasan sesi.
Sila tekan butang "back" dan muatkan semula laman yang telah anda kunjungi sebelum ini, kemudian cuba lagi.',
'protectlogpage'              => 'Log perlindungan',
'protectlogtext'              => 'Berikut ialah senarai bagi tindakan penguncian/pembukaan laman. Sila lihat [[Special:Protectedpages|senarai laman dilindungi]] untuk rujukan lanjut.',
'protectedarticle'            => 'melindungi "[[$1]]"',
'modifiedarticleprotection'   => 'menukar peringkat perlindungan bagi "[[$1]]"',
'unprotectedarticle'          => 'menyahlindung "[[$1]]"',
'protectsub'                  => '(Menetapkan peringkat perlindungan bagi "$1")',
'confirmprotect'              => 'Sahkan perlindungan',
'protectcomment'              => 'Komen:',
'protectexpiry'               => 'Tamat pada:',
'protect_expiry_invalid'      => 'Waktu tamat tidak sah.',
'protect_expiry_old'          => 'Waktu tamat telah berlalu.',
'unprotectsub'                => '(Menyahlindung "$1")',
'protect-unchain'             => 'Buka kunci keizinan pemindahan',
'protect-text'                => 'Anda boleh melihat dan menukar peringkat perlindungan bagi laman <strong>$1</strong>.',
'protect-locked-blocked'      => 'Anda telah disekat, justeru tidak boleh menukar peringkat perlindungan.
Ini adalah tetapan semasa bagi laman <strong>$1</strong>:',
'protect-locked-dblock'       => 'Anda tidak boleh menukar peringkat perlindungan kerana pangkalan data sedang dikunci.
Ini adalah tetapan semasa bagi laman <strong>$1</strong>:',
'protect-locked-access'       => 'Anda tidak mempunyai keizinan untuk menukar peringkat perlindungan.
Ini adalah tetapan semasa bagi laman <strong>$1</strong>:',
'protect-cascadeon'           => 'Laman ini dilindungi kerana ia terkandung dalam {{PLURAL:$1|laman|laman-laman}} berikut, yang dilindungi secara melata. Anda boleh menukar peringkat perlindunan laman ini, akan tetapi ia tidak akan menjejaskan perlindungan melata tersebut.',
'protect-default'             => '(lalai)',
'protect-fallback'            => 'Perlukan keizinan "$1"',
'protect-level-autoconfirmed' => 'Sekat pengguna-pengguna tidak berdaftar',
'protect-level-sysop'         => 'Penyelia sahaja',
'protect-summary-cascade'     => 'melata',
'protect-expiring'            => 'tamat pada $1 (UTC)',
'protect-cascade'             => 'Lindungi semua laman yang terkandung dalam laman ini (perlindungan melata)',
'restriction-type'            => 'Keizinan:',
'restriction-level'           => 'Peringkat pembatasan:',
'minimum-size'                => 'Saiz minimum',
'maximum-size'                => 'Saiz maksimum',
'pagesize'                    => '(bait)',

# Restrictions (nouns)
'restriction-edit' => 'Sunting',
'restriction-move' => 'Pindah',

# Restriction levels
'restriction-level-sysop'         => 'perlindungan penuh',
'restriction-level-autoconfirmed' => 'perlindungan separa',
'restriction-level-all'           => 'semua peringkat',

# Undelete
'undelete'                     => 'Lihat laman-laman yang dihapuskan',
'undeletepage'                 => 'Lihat dan pulihkan laman yang dihapuskan.',
'viewdeletedpage'              => 'Lihat laman-laman yang dihapuskan',
'undeletepagetext'             => 'Laman-laman berikut telah dihapuskan tetapi masih disimpan dalam arkib dan
masih boleh dipulihkan. Arkib tersebut akan dibersihkan dari semasa ke semasa.',
'undeleteextrahelp'            => "Untuk memulihkan keseluruhan laman, biarkan semua kotak semak dan
klik '''''Pulih'''''. Untuk melaksanakan pemulihan tertentu, semak kotak yang berkaitan dengan
semakan untuk dipulihkan dan klik '''''Pulih'''''. Klik '''''Set semula''''' untuk mengosongkan
borang ini.",
'undeleterevisions'            => '$1 semakan telah diarkibkan.',
'undeletehistory'              => 'Jika anda memulihkan laman tersebut, semua semakan akan dipulihkan kepada sejarahnya.
Jika sebuah laman baru yang mempunyai nama yang sama telah dicipta sejak penghapusan,
semakan yang dipulihkan akan muncul dalam sejarah terdahulu, dan semakan terkini bagi laman yang wujud tersebut
tidak akan digantikan secara automatik. Sila ambil perhatian bahawa sebarang batasan terhadap semakan fail akan hilang',
'undeleterevdel'               => 'Penyahhapusan tidak akan dilaksanakan sekiranya ia menyebabkan sebahagian semakan puncak dihapuskan.
Dalam hal tersebut, anda perlu membuang semak atau menyemak semakan yang baru dihapuskan. Semakan fail
yang anda tidak dibenarkan melihatnya tidak akan dipulihkan.',
'undeletehistorynoadmin'       => 'Rencana ini telah dihapuskan. Sebab penghapusan
ditunjukkan dalam ringkasan di bawah, berserta butiran bagi pengguna-pengguna yang telah menyunting laman ini
sebelum penghapusan. Teks sebenar bagi semua semakan yang dihapuskan hanya boleh dilihat oleh para pentadbir.',
'undelete-revision'            => 'Menghapuskan semakan bagi $1 (pada $2) oleh $3:',
'undeleterevision-missing'     => 'Semakan tersebut tidak sah atau tidak dijumpai. Mungkin anda telah mengikuti pautan yang rosak
atau semakan tersebut telah dipulihkan atau dibuang daripada arkib.',
'undeletebtn'                  => 'Pulihkan',
'undeletereset'                => 'set semula',
'undeletecomment'              => 'Komen:',
'undeletedarticle'             => 'memulihkan "[[$1]]"',
'undeletedrevisions'           => '$1 semakan dipulihkan',
'undeletedrevisions-files'     => '$1 semakan dan $2 fail dipulihkan',
'undeletedfiles'               => '$1 fail dipulihkan',
'cannotundelete'               => 'Penyahhapusan gagal; mungkin orang lain telah pun mengnyahhapuskannya.',
'undeletedpage'                => "<big>'''$1 telah dipulihkan'''</big>

Sila rujuk [[Special:Log/delete|log penghapusan]] untuk rekod penghapusan terkini.",
'undelete-header'              => 'Lihat [[Special:Log/delete|log penghapusan]] untuk laman-laman yang baru dihapuskan.',
'undelete-search-box'          => 'Cari laman yang dihapuskan',
'undelete-search-prefix'       => 'Tunjukkan laman bermula dengan:',
'undelete-search-submit'       => 'Cari',
'undelete-no-results'          => 'Tiada laman yang sepadan dijumpai dalam arkib penghapusan.',
'undelete-filename-mismatch'   => 'Semakan pada $1 tidak boleh dinyahhapuskan: nama fail tidak sepadan',
'undelete-bad-store-key'       => 'Semakan pada $1 tidak boleh dinyahhapuskan: fail telah hilang.',
'undelete-cleanup-error'       => 'Ralat ketika menyahhhapuskan fail "$1" dalam arkib yang tidak digunakan.',
'undelete-missing-filearchive' => 'Arkib fail dengan ID $1 tidak dapat dipulihkan kerana tiada dalam pangkalan data. Ia mungkin telah pun dinyahhapuskan.',
'undelete-error-short'         => 'Ralat ketika menyahhapuskan fail: $1',
'undelete-error-long'          => 'Berlaku ralat ketika menyahhapuskan fail tersebut:

$1',

# Namespace form on various pages
'namespace'      => 'Ruang nama:',
'invert'         => 'Terbalikkan pilihan',
'blanknamespace' => '(Utama)',

# Contributions
'contributions' => 'Sumbangan pengguna',
'mycontris'     => 'Sumbangan saya',
'contribsub2'   => 'Bagi $1 ($2)',
'nocontribs'    => 'Tiada sebarang perubahan yang sepadan dengan kriteria-kriteria ini.',
'ucnote'        => 'Berikut ialah <b>$1</b> perubahan terakhir yang dilakukan oleh pengguna ini sejak <b>$2</b> hari yang lalu.',
'uclinks'       => 'Lihat $1 perubahan terkini; lihat $2 hari yang lalu.',
'uctop'         => ' (puncak)',
'month'         => 'Sebelum bulan:',
'year'          => 'Sebelum tahun:',

'sp-contributions-newest'      => 'Terbaru',
'sp-contributions-oldest'      => 'Terlama',
'sp-contributions-newer'       => '$1 berikutnya',
'sp-contributions-older'       => '$1 sebelumnya',
'sp-contributions-newbies'     => 'Tunjuk sumbangan daripada akaun baru sahaja',
'sp-contributions-newbies-sub' => 'Bagi akaun-akaun baru',
'sp-contributions-blocklog'    => 'Log sekatan',
'sp-contributions-search'      => 'Cari sumbangan',
'sp-contributions-username'    => 'Alamat IP atau nama pengguna:',
'sp-contributions-submit'      => 'Cari',

'sp-newimages-showfrom' => 'Tunjukkan imej-imej baru bermula daripada $1',

# What links here
'whatlinkshere'         => 'Pautan ke laman ini',
'whatlinkshere-title'   => 'Laman yang mengandungi pautan ke $1',
'notargettitle'         => 'Tiada sasaran',
'notargettext'          => 'Anda tidak menyatakan laman sasaran atau pengguna untuk melaksanakan fungsi ini.',
'linklistsub'           => '(Senarai pautan masuk)',
'linkshere'             => "Laman-laman berikut mengandungi pautan ke '''[[:$1]]''':",
'nolinkshere'           => "Tiada laman yang mengandungi pautan ke '''[[:$1]]'''.",
'nolinkshere-ns'        => "Tiada laman yang mengandungi pautan ke '''[[:$1]]''' dalam ruang nama yang dinyatakan.",
'isredirect'            => 'laman pelencongan',
'istemplate'            => 'penyertaan',
'whatlinkshere-prev'    => '{{PLURAL:$1|sebelumnya|$1 sebelumnya}}',
'whatlinkshere-next'    => '{{PLURAL:$1|berikutnya|$1 berikutnya}}',
'whatlinkshere-links'   => '← pautan',

# Block/unblock
'blockip'                     => 'Sekat pengguna',
'blockiptext'                 => 'Gunakan borang di bawah untuk menyekat
penyuntingan daripada alamat IP atau pengguna tertentu.
Tindakan ini perlu dilakukan untuk menentang vandalisme sahaja dan selaras
dengan [[{{MediaWiki:policy-url}}|dasar {{SITENAME}}]].
Sila masukkan sebab sekatan di bawah (umpamannya, sebutkan laman yang telah
dirosakkan).',
'ipaddress'                   => 'Alamat IP:',
'ipadressorusername'          => 'Alamat IP atau nama pengguna:',
'ipbexpiry'                   => 'Tamat:',
'ipbreason'                   => 'Sebab:',
'ipbreasonotherlist'          => 'Lain-lain',
'ipbreason-dropdown'          => '
*Sebab lazim
** Memasukkan maklumat palsu
** Membuang kandungan daripada laman
** Memmasukkan pautan spam ke tapak web luar
** Memasukkan karut-marut ke dalam laman
** Mengugut/mengganggu pengguna lain
** Menyalahgunakan berbilang akaun
** Nama pengguna yang tidak sesuai',
'ipbanononly'                 => 'Sekat pengguna tanpa nama sahaja',
'ipbcreateaccount'            => 'Halang pembukaan akaun baru',
'ipbemailban'                 => 'Menghalang pengguna daripada mengirim e-mel',
'ipbenableautoblock'          => 'Sekat alamat IP terakhir dan mana-mana alamat berikutnya yang digunakan oleh pengguna ini secara automatik',
'ipbsubmit'                   => 'Sekat pengguna ini',
'ipbother'                    => 'Waktu lain:',
'ipboptions'                  => '2 jam:2 hours,1 hari:1 day,3 hari:3 days,1 minggu:1 week,2 minggu:2 weeks,1 bulan:1 month,3 bulan:3 months,6 bulan:6 months,1 tahun:1 year,selama-lamanya:infinite',
'ipbotheroption'              => 'lain',
'ipbotherreason'              => 'Sebab tambahan/lain:',
'ipbhidename'                 => 'Sembunyikan nama pengguna/alamat IP daripada log sekatan, senarai sekatan aktif, dan senarai pengguna',
'badipaddress'                => 'Alamat IP tidak sah',
'blockipsuccesssub'           => 'Sekatan berjaya',
'blockipsuccesstext'          => '[[Special:Contributions/$1|$1]] telah disekat.
<br />Sila lihat [[Special:Ipblocklist|senarai sekatan IP]] untuk maklumat lanjut.',
'ipb-edit-dropdown'           => 'Sunting sebab sekatan',
'ipb-unblock-addr'            => 'Nyahsekat $1',
'ipb-unblock'                 => 'Nyahsekat nama pengguna atau alamat IP',
'ipb-blocklist-addr'          => 'Lihat sekatan sedia ada bagi $1',
'ipb-blocklist'               => 'Lihat sekatan sedia ada',
'unblockip'                   => 'Nyahsekat pengguna',
'unblockiptext'               => 'Gunakan borang di bawah untuk membuang sekatan bagialamat IP atau nama pengguna yang telah disekat.',
'ipusubmit'                   => 'Nyahsekat alamat ini.',
'unblocked'                   => '[[User:$1|$1]] telah dinyahsekat',
'unblocked-id'                => 'Sekatan $1 telah dibuang',
'ipblocklist'                 => 'Nama pengguna dan alamat IP yang disekat',
'ipblocklist-legend'          => 'Cari pengguna yang disekat',
'ipblocklist-username'        => 'Nama pengguna atau alamat IP:',
'ipblocklist-submit'          => 'Cari',
'blocklistline'               => '$1, $2 menyekat $3 ($4)',
'infiniteblock'               => 'selama-lamanya',
'expiringblock'               => 'luput pada $1',
'anononlyblock'               => 'pengguna tanpa nama sahaja',
'noautoblockblock'            => 'sekatan automatik dipadamkan',
'createaccountblock'          => 'pembukaan akaun baru disekat',
'emailblock'                  => 'e-mail disekat',
'ipblocklist-empty'           => 'Senarai sekatan adalah kosong.',
'ipblocklist-no-results'      => 'Alamat IP atau nama pengguna tersebut tidak disekat.',
'blocklink'                   => 'sekat',
'unblocklink'                 => 'nyahsekat',
'contribslink'                => 'sumb.',
'autoblocker'                 => 'Disekat secara automatik kerana baru-baru ini alamat IP anda digunakan oleh "[[User:$1|$1]]". Sebab sekatan $1 ialah: "$2"',
'blocklogpage'                => 'Log sekatan',
'blocklogentry'               => 'menyekat [[$1]] sehingga $2 $3',
'blocklogtext'                => 'Ini adalah log bagi sekatan dan penyahsekatan.
Alamat IP yang disekat secara automatik tidak disenaraikan di sini. Sila lihat
[[Special:Ipblocklist|senarai sekatan IP]] untuk mengetahui sekatan-sekatan yang sedang dijalankan.',
'unblocklogentry'             => 'menyahsekat $1',
'block-log-flags-anononly'    => 'pengguna tanpa nama sahaja',
'block-log-flags-nocreate'    => 'pembukaan akaun dimatikan',
'block-log-flags-noautoblock' => 'sekatan automatik dimatikan',
'block-log-flags-noemail'     => 'e-mail disekat',
'range_block_disabled'        => 'Kebolehan penyelia untuk membuat sekatan julat dimatikan.',
'ipb_expiry_invalid'          => 'Waktu tamat tidak sah.',
'ipb_already_blocked'         => '"$1" telah pun disekat',
'ip_range_invalid'            => 'Julat IP tidak sah.',
'proxyblocker'                => 'Sekatan proksi',
'ipb_cant_unblock'            => 'Ralat: ID sekatan $1 tidak dijumpai. Barangkali ia telah pun dinyahsekat.',
'proxyblockreason'            => 'Alamat IP anda telah disekat kerana ia merupakan proksi terbuka. Sila hubungi penyedia perkhidmatan Internet anda atau pihak sokongan teknikal dan beritahu mereka mengenai masalah berat ini.',
'proxyblocksuccess'           => 'Siap.',
'sorbsreason'                 => 'Alamat IP anda telah disenaraikan sebagai proksi terbuka dalam DNSBL yang digunakan oleh wiki ini.',
'sorbs_create_account_reason' => 'Alamat IP anda telah disenaraikan sebagai proksi terbuka dalam DNSBL yang digunakan oleh wiki ini. Oleh itu, anda tidak dibenarkan membuka akaun baru.',

# Developer tools
'lockdb'              => 'Kunci pangkalan data',
'unlockdb'            => 'Buka kunci pangkalan data.',
'lockdbtext'          => 'Penguncian pangkalan data akan membekukan kebolehan semua
pengguna untuk menyunting laman, mengubah keutamaan, menyunting senarai
sekatan, dan perkara lain yang memerlukan perubahan dalam pangkalan data.
Sila sahkan bahawa anda memang berniat untuk melakukan tindakan ini, dan
bahawa anda akan membuka semula kunci pangkalan data ini setelah penyenggaraan selesai.',
'unlockdbtext'        => 'Pembukaan kunci pangkalan data boleh
memulihkan kebolehan semua pengguna untuk menyunting laman, keutamaan, senarai
pantau dan sebagainya yang melibatkan perubahan dalam pangkalan data. Sila
sahkan bahawa anda betul-betul mahu melakukan tindakan ini.',
'lockconfirm'         => 'Ya, saya mahu mengunci pangkalan data ini.',
'unlockconfirm'       => 'Ya, saya betul-betul mahu membuka kunci pangkalan data.',
'lockbtn'             => 'Kunci pangkalan data',
'unlockbtn'           => 'Buka kunci pangkalan data',
'locknoconfirm'       => 'Anda tidak menyemak kotak pengesahan.',
'lockdbsuccesssub'    => 'Pangkalan data dikunci.',
'unlockdbsuccesssub'  => 'Kunci pangkalan data dibuka.',
'lockdbsuccesstext'   => 'Pangkalan data telah dikunci.
<br />Pastikan anda [[Special:Unlockdb|membukanya semula]] selepas penyelenggaraan selesai.',
'unlockdbsuccesstext' => 'Kunci pangkalan data {{SITENAME}} telah dibuka.',
'lockfilenotwritable' => 'Fail kunci pangkalan data tidak boleh ditulis. Untuk mengunci atau membuka kunci pangkalan data, fail ini perlu diubah suai supaya boleh ditulis oleh pelayan web ini.',
'databasenotlocked'   => 'Pangkalan data tidak dikunci.',

# Move page
'movepage'                => 'Pindah laman',
'movepagetext'            => "Gunakan borang di bawah untuk mengubah nama laman dan 
memindahkan semua maklumat sejarahnya kepada nama baru.
Tajuk yang lama akan dijadikan pelencongan ke tajuk yang baru.
Pautan ke tajuk yang lama tidak akan diubah; pastikan anda menyemak
sekiranya terdapat pelencongan berganda atau pelencongan rosak.
Anda bertanggungjawab memastikan semua pautan bersambung ke laman yang
sepatutnya.

Sila ambil perhatian bahawa laman tersebut '''tidak''' akan dipindahkan
seandainya sudah wujud laman dengan tajuk yang baru tadi, melainkan apabila
laman tersebut kosong atau merupakan laman pelencongan dan tidak mempunyai
sejarah suntingan. Ini bermakna anda boleh menukar semula nama sesebuah
laman kepada nama yang asal jika anda telah melakukan kesilapan, dan anda tidak boleh menindan laman yang sudah ada.

<b>AMARAN!</b>
Tindakan ini boleh menjadi perubahan yang tidak dijangka dan drastik
bagi laman popular; sila pastikan anda faham akibat yang mungkin timbul
sebelum menyambung.",
'movepagetalktext'        => "Laman perbincangan yang berkaitan, jika ada, akan dipindahkan bersama-sama laman ini secara automatik '''kecuali''':
* Sebuah laman perbincangan dengan nama baru telah pun wujud, atau
* Anda membuang tanda kotak di bawah.

Dalam kes tersebut, anda terpaksa melencongkan atau menggabungkan laman secara manual, jika perlu.",
'movearticle'             => 'Pindah laman:',
'movenologin'             => 'Belum log masuk.',
'movenologintext'         => 'Anda mesti [[Special:Userlogin|log masuk]] terlebih dahulu untuk memindahkan laman.',
'movenotallowed'          => 'Anda tidak mempunyai keizinan untuk memindahkan laman dalam wiki ini.',
'newtitle'                => 'Kepada tajuk baru:',
'move-watch'              => 'Pantau laman ini',
'movepagebtn'             => 'Pindah laman',
'pagemovedsub'            => 'Pemindahan berjaya',
'movepage-moved'          => '<big>\'\'\'"$1" telah dipindahkan ke "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Laman dengan nama tersebut telah pun wujud,
atau nama yang anda pilih tidak sah.
Sila pilih nama lain.',
'talkexists'              => "'''Laman tersebut berjaya dipindahkan, akan tetapi laman perbincangannya tidak dapat dipindahkan kerana laman dengan tajuk baru tersebut telah pun wujud. Anda perlu menggabungkannya secara manual.'''",
'movedto'                 => 'dipindahkan ke',
'movetalk'                => 'Pindahkan laman perbincangan yang berkaitan',
'talkpagemoved'           => 'Laman perbincangan yang mengiringi dipindahkan bersama.',
'talkpagenotmoved'        => 'Laman perbincangan yang mengiringi <strong>tidak</strong> dipindahkan bersama.',
'1movedto2'               => '[[$1]] dipindahkan ke [[$2]]',
'1movedto2_redir'         => '[[$1]] dipindahkan ke [[$2]] menerusi pelencongan',
'movelogpage'             => 'Log pemindahan',
'movelogpagetext'         => 'Berikut ialah senarai pemindahan laman.',
'movereason'              => 'Sebab:',
'revertmove'              => 'balik',
'delete_and_move'         => 'Hapus dan pindah',
'delete_and_move_text'    => '==Penghapusan diperlukan==

Rencana destinasi "[[$1]]" telah pun wujud. Adakah anda mahu menghapuskannya supaya laman ini dapat dipindahkan?',
'delete_and_move_confirm' => 'Ya, hapuskan laman ini',
'delete_and_move_reason'  => 'Dihapuskan supaya laman lain dapat dipindahkan',
'selfmove'                => 'Tajuk sumber dan tajuk destinasi tidak boleh sama.',
'immobile_namespace'      => 'Tajuk sumber atau destinasi adalah jenis khas. Anda tidak memindahkan laman ke luar atau dalam ruang nama tersebut.',

# Export
'export'            => 'Eksport laman',
'exporttext'        => 'Anda boleh mengeksport teks dan sejarah suntingan untuk laman-laman tertentu yang ke dalam fail XML.
Fail ini boleh diimport ke dalam wiki lain yang menggunakan perisian MediaWiki
melalui [[Special:Import|laman import]].

Untuk mengeksport laman, masukkan tajuk dalam kotak teks di bawah (satu tajuk
bagi setiap baris) dan pilih sama ada anda mahukan semua versi dan catatan
sejarah atau hanya versi semasa berserta maklumat mengenai suntingan terakhir.

Dalam pilihan kedua tadi, anda juga boleh menggunakan pautan, umpamanya [[{{ns:Special}}:Eksport/{{MediaWiki:mainpage}}]] untuk laman "[[{{MediaWiki:mainpage}}]]".',
'exportcuronly'     => 'Hanya eksport semakan semasa, bukan keseluruhan sejarah.',
'exportnohistory'   => "----
'''Catatan:''' Ciri eksport sejarah penuh laman melalui borang ini telah dimatikan atas sebab-sebab prestasi.",
'export-submit'     => 'Eksport',
'export-addcattext' => 'Tambah laman daripada kategori:',
'export-addcat'     => 'Tambah',
'export-download'   => 'Tawarkan penyimpanan sebagai fail',

# Namespace 8 related
'allmessages'               => 'Mesej sistem',
'allmessagesname'           => 'Nama',
'allmessagesdefault'        => 'Teks lalai',
'allmessagescurrent'        => 'Teks semasa',
'allmessagestext'           => 'Ini ialah senarai mesej sistem yang tersedia dalam ruang nama MediaWiki.',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:Allmessages''' tidak boleh digunakan kerana '''\$wgUseDatabaseMessages''' dipadamkan.",
'allmessagesfilter'         => 'Tapis nama mesej:',
'allmessagesmodified'       => 'Hanya tunjukkan yang telah diubah',

# Thumbnails
'thumbnail-more'           => 'Besarkan',
'missingimage'             => '<b>Imej hilang</b><br /><i>$1</i>',
'filemissing'              => 'Fail hilang',
'thumbnail_error'          => 'Berlaku ralat ketika mencipta imej ringkas: $1',
'djvu_page_error'          => 'DjVu page out of range',
'djvu_no_xml'              => 'Unable to fetch XML for DjVu file',
'thumbnail_invalid_params' => 'Parameter imej ringkas tidak sah',
'thumbnail_dest_directory' => 'Direktori destinasi gagal diciptakans',

# Special:Import
'import'                     => 'Import laman',
'importinterwiki'            => 'Import transwiki',
'import-interwiki-text'      => 'Sila pilih wiki dan tajuk laman yang ingin diimport.
Semua tarikh semakan dan nama penyunting akan dikekalkan.
Semua tindakan import transwiki dicatatkan dalam [[Special:Log/import|log import]].',
'import-interwiki-history'   => 'Salin semua versi sejarah bagi laman ini',
'import-interwiki-submit'    => 'Import',
'import-interwiki-namespace' => 'Pindahkan laman ke dalam ruang nama:',
'importtext'                 => 'Sila eksport fail daripada sumber wiki menggunakan kemudahan Special:Export, simpan dalam komputer anda dan muat naik di sini.',
'importstart'                => 'Mengimport laman...',
'import-revision-count'      => '$1 semakan',
'importnopages'              => 'Tiada laman untuk diimport.',
'importfailed'               => 'Import gagal: $1',
'importunknownsource'        => 'Jenis sumber import tidak dikenali',
'importcantopen'             => 'Fail import tidak dapat dibuka',
'importbadinterwiki'         => 'Pautan antara wiki rosak',
'importnotext'               => 'Kosong atau tiada teks',
'importsuccess'              => 'Import selesai!',
'importhistoryconflict'      => 'Terdapat percanggahan semakan sejarah (mungkin laman ini pernah diimport sebelum ini)',
'importnosources'            => 'Tiada sumber import transwiki ditentunkan dan ciri muat naik sejarah secara terus dimatikan.',
'importnofile'               => 'Tiada fail import dimuat naik.',
'importuploaderror'          => 'Fail import tidak dapat dimuat naik; mungkin saiz fail tersebut melebihi had muat naik yang dibenarkan.',

# Import log
'importlogpage'                    => 'Log import',
'importlogpagetext'                => 'Senarai tindakan import laman dengan keseluruhan sejarah suntingannya daripada wiki lain.',
'import-logentry-upload'           => 'mengimport [[$1]] dengan memuat naik fail',
'import-logentry-upload-detail'    => '$1 semakan',
'import-logentry-interwiki'        => 'transwikied $1',
'import-logentry-interwiki-detail' => '$1 semakan daripada $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Laman pengguna saya',
'tooltip-pt-anonuserpage'         => 'Laman pengguna bagi alamat IP anda',
'tooltip-pt-mytalk'               => 'Laman perbincangan saya',
'tooltip-pt-anontalk'             => 'Perbincangan mengenai penyuntingan daripada alamat IP anda',
'tooltip-pt-preferences'          => 'Keutamaan saya',
'tooltip-pt-watchlist'            => 'Senarai laman yang anda pantau',
'tooltip-pt-mycontris'            => 'Senarai sumbangan saya',
'tooltip-pt-login'                => 'Walaupun tidak wajib, anda digalakkan supaya log masuk.',
'tooltip-pt-anonlogin'            => 'Walaupun tidak wajib, anda digalakkan supaya log masuk.',
'tooltip-pt-logout'               => 'Log keluar',
'tooltip-ca-talk'                 => 'Perbincangan mengenai laman kandungan',
'tooltip-ca-edit'                 => 'Anda boleh menyunting laman ini. Sila lihat pratonton terlebih dahulu sebelum menyimpan.',
'tooltip-ca-addsection'           => 'Tambah komen bagi perbincangan ini.',
'tooltip-ca-viewsource'           => 'Laman ini dilindungi. Anda boleh melihat sumbernya.',
'tooltip-ca-history'              => 'Versi-versi terdahulu bagi laman ini.',
'tooltip-ca-protect'              => 'Lindungi laman ini',
'tooltip-ca-delete'               => 'Hapuskan laman ini',
'tooltip-ca-undelete'             => 'Balikkan suntingan yang dilakukan kepada laman ini sebelum ia dihapuskan',
'tooltip-ca-move'                 => 'Pindahkan laman ini',
'tooltip-ca-watch'                => 'Tambahkan laman ini ke dalam senarai pantau anda',
'tooltip-ca-unwatch'              => 'Buang laman ini daripada senarai pantau anda',
'tooltip-search'                  => 'Cari dalam {{SITENAME}}',
'tooltip-p-logo'                  => 'Laman Utama',
'tooltip-n-mainpage'              => 'Kunjungi Laman Utama',
'tooltip-n-portal'                => 'Maklumat mengenai projek ini',
'tooltip-n-currentevents'         => 'Cari maklumat latar belakang mengenai peristiwa semasa',
'tooltip-n-recentchanges'         => 'Senarai perubahan terkini dalam wiki ini.',
'tooltip-n-randompage'            => 'Buka laman rawak',
'tooltip-n-help'                  => 'Tempat mencari jawapan.',
'tooltip-n-sitesupport'           => 'Bantu kami',
'tooltip-t-whatlinkshere'         => 'Senarai laman wiki yang mengandungi pautan ke laman ini',
'tooltip-t-recentchangeslinked'   => 'Perubahan terkini bagi semua laman yang dipaut dari laman ini',
'tooltip-feed-rss'                => 'Suapan RSS bagi laman ini',
'tooltip-feed-atom'               => 'Suapan Atom bagi laman ini',
'tooltip-t-contributions'         => 'Lihat senarai sumbangan pengguna ini',
'tooltip-t-emailuser'             => 'Kirim e-mel kepada pengguna ini',
'tooltip-t-upload'                => 'Muat naik imej atau fail media',
'tooltip-t-specialpages'          => 'Senarai laman khas',
'tooltip-t-print'                 => 'Versi boleh cetak bagi laman ini',
'tooltip-t-permalink'             => 'Pautan kekal ke versi ini',
'tooltip-ca-nstab-main'           => 'Lihat laman kandungan',
'tooltip-ca-nstab-user'           => 'Lihat laman pengguna',
'tooltip-ca-nstab-media'          => 'Lihat laman media',
'tooltip-ca-nstab-special'        => 'Ini adalah sebuah laman khas, anda tidak boleh menyunting laman ini secara terus.',
'tooltip-ca-nstab-project'        => 'Lihat laman projek',
'tooltip-ca-nstab-image'          => 'Lihat laman imej',
'tooltip-ca-nstab-mediawiki'      => 'Lihat mesej sistem',
'tooltip-ca-nstab-template'       => 'Lihat templat',
'tooltip-ca-nstab-help'           => 'Lihat laman bantuan',
'tooltip-ca-nstab-category'       => 'Lihat laman kategori',
'tooltip-minoredit'               => 'Tandakan sebagai suntingan kecil',
'tooltip-save'                    => 'Simpan perubahan',
'tooltip-preview'                 => 'Pratonton perubahan yang anda lakukan, sila gunakan butang ini sebelum menyimpan!',
'tooltip-diff'                    => 'Tunjukkan perubahan yang anda telah lakukan kepada teks ini.',
'tooltip-compareselectedversions' => 'Lihat perbezaan antara dua versi laman ini yang dipilih.',
'tooltip-watch'                   => 'Tambahkan laman ini ke dalam senarai pantau anda',
'tooltip-recreate'                => 'Cipta semula laman ini walaupun ia telah dihapuskan',
'tooltip-upload'                  => 'Muat naik',

# Stylesheets
'common.css'   => '/** CSS placed here will be applied to all skins */',
'monobook.css' => '/* CSS placed here will affect users of the Monobook skin */',

# Scripts
'common.js'   => '/* Any JavaScript here will be loaded for all users on every page load. */',
'monobook.js' => '/* Deprecated; use [[MediaWiki:common.js]] */',

# Metadata
'nodublincore'      => 'Metadata RDF Dublin Core dipadamkan bagi pelayan ini.',
'nocreativecommons' => 'Metadata RDF Creative Commons RDF dipadamkan bagi pelayan ini.',
'notacceptable'     => 'Pelayan wiki ini tidak mampu menyediakan data dalam format yang boleh dibaca oleh pelanggan anda.',

# Attribution
'anonymous'        => 'Penguna {{SITENAME}} tanpa nama',
'siteuser'         => 'Pengguna {{SITENAME}}, $1',
'lastmodifiedatby' => 'Laman ini diubah buat kali terakhir pada $2, $1 oleh $3.', # $1 date, $2 time, $3 user
'and'              => 'dan',
'othercontribs'    => 'Berdasarkan karya $1.',
'others'           => 'lain-lain',
'siteusers'        => 'Pengguna-pengguna {{SITENAME}}, $1',
'creditspage'      => 'Penghargaan',
'nocredits'        => 'Tiada maklumat penghargaan bagi laman ini.',

# Spam protection
'spamprotectiontitle'    => 'Penapis spam',
'spamprotectiontext'     => 'Laman yang anda ingin simpan dihalang oleh penapis spam. Hal ini mungkin disebabkan oleh pautan ke tapak web luar.',
'spamprotectionmatch'    => 'Teks berikut dikesan oleh penapis spam kami: $1',
'subcategorycount'       => 'Terdapat $1 subkategori bagi kategori ini.',
'categoryarticlecount'   => 'Terdapat $1 rencana dalam kategori ini.',
'category-media-count'   => 'Terdapat $1 fail dalam kategori ini.',
'listingcontinuesabbrev' => 'samb.',
'spambot_username'       => 'Pembersihan spam MediaWiki',
'spam_reverting'         => 'Membalikkan kepada versi terakhir yang tidak mengandungi pautan ke $1',
'spam_blanking'          => 'Mengosongkan semua semakan yang mengandungi pautan ke $1',

# Info page
'infosubtitle'   => 'Maklumat laman',
'numedits'       => 'Jumlah suntingan (rencana): $1',
'numtalkedits'   => 'Jumlah suntingan (laman perbincangan): $1',
'numwatchers'    => 'Bilangan pemantau: $1',
'numauthors'     => 'Bilangan pengarang (rencana): $1',
'numtalkauthors' => 'Bilangan pengarang (laman perbincangan): $1',

# Math options
'mw_math_png'    => 'Sentiasa lakar PNG',
'mw_math_simple' => 'HTML jika ringkas, sebaliknya PNG',
'mw_math_html'   => 'HTML jika boleh, sebaliknya PNG',
'mw_math_source' => 'Biarkan sebagai TeX (untuk pelayar teks)',
'mw_math_modern' => 'Dicadangkan untuk pelayar moden',
'mw_math_mathml' => 'MathML jika boleh (sedang dalam uji kaji)',

# Patrolling
'markaspatrolleddiff'                 => 'Tandakan sebagai telah diperiksa',
'markaspatrolledtext'                 => 'Tandakan rencana ini sebagai telah diperiksa',
'markedaspatrolled'                   => 'Tandakan sebagai telah diperiksa',
'markedaspatrolledtext'               => 'Semakan tersebut telah ditandakan sebagai telah diperiksa.',
'rcpatroldisabled'                    => 'Pemeriksaan Perubahan Terkini dimatikan',
'rcpatroldisabledtext'                => 'Ciri Pemeriksaan Perubahan Terkini dimatikan.',
'markedaspatrollederror'              => 'Tidak boleh menandakan sebagai telah diperiksa',
'markedaspatrollederrortext'          => 'Anda perlu menyatakan semakan untuk ditandakan sebagai telah diperiksa.',
'markedaspatrollederror-noautopatrol' => 'Anda tidak dibenarkan menandakan perubahan anda sendiri sebagai telah diperiksa.',

# Patrol log
'patrol-log-page'   => 'Log pemeriksaan',
'patrol-log-line'   => 'menandakan $1 bagi $2 sebagai telah diperiksa $3',
'patrol-log-auto'   => '(automatik)',
'patrol-log-diff'   => 's$1',

# Image deletion
'deletedrevision'                 => 'Menghapuskan semakan lama $1.',
'filedeleteerror-short'           => 'Ralat ketika menghapuskan fail: $1',
'filedeleteerror-long'            => 'Berlaku ralat ketika menghapuskan fail tersebut:

$1',
'filedelete-missing'              => 'Fail "$1" tidak boleh dihapuskan kerana ia tidak wujud.',
'filedelete-old-unregistered'     => 'Semakan fail "$1" tiada dalam pangkalan data.',
'filedelete-current-unregistered' => 'Fail "$1" tiada dalam pangkalan data.',
'filedelete-archive-read-only'    => 'Direktori arkib "$1" tidak boleh ditulis oleh pelayan web.',

# Browsing diffs
'previousdiff' => '← Beza sebelumnya',
'nextdiff'     => 'Perbezaan berikutnya →',

# Media information
'mediawarning'         => "'''Amaran''': Fail ini boleh mengandungi kod yang berbahaya dan merosakkan komputer anda.<hr />",
'imagemaxsize'         => 'Hadkan saiz imej pada laman huraian imej kepada:',
'thumbsize'            => 'Saiz imej ringkas:',
'widthheightpage'      => '$1×$2, $3 halaman',
'file-info'            => '(saiz file: $1, jenis MIME: $2)',
'file-info-size'       => '($1 × $2 piksel, saiz fail: $3, jenis MIME: $4)',
'file-nohires'         => '<small>Tiada leraian lebih besar.</small>',
'svg-long-desc'        => '(Fail SVG, ukuran dasar $1 × $2 piksel, saiz fail: $3)',
'show-big-image'       => 'Leraian penuh',
'show-big-image-thumb' => '<small>Saiz pratonton ini: $1 × $2 piksel</small>',

# Special:Newimages
'newimages'         => 'Galeri fail baru',
'showhidebots'      => '($1 bot)',
'noimages'          => 'Tiada imej.',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds-abbrev' => 's',
'minutes-abbrev' => 'm',
'hours-abbrev'   => 'j',

# Bad image list
'bad_image_list' => 'Berikut adalah format yang digunakan:

Hanya item senarai (baris yang dimulakan dengan *) diambil kira. Pautan pertama pada sesebuah baris mestilah merupakan pautan ke sebuah imej rosak.
Sebarang pautan berikutnya pada baris yang sama dikira sebagai pengecualian (rencana yang dibenarkan disertakan imej).',

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => 'Fail ini mengandungi maklumat tambahan daripada kamera digital atau pengimbas yang digunakan untuk menghasilkannya. Jika fail ini telah diubah suai daripada rupa asalnya, beberapa butiran dalam maklumat ini mungkin sudah tidak relevan.',
'metadata-expand'   => 'Tunjukkan butiran penuh',
'metadata-collapse' => 'Sembunyikan butiran penuh',
'metadata-fields'   => 'Ruangan metadata EXIF yang disenaraikan dalam mesej ini
akan ditunjukkan pada laman imej apabila jadual metadata dikecilkan.
Ruangan lain akan disembunyikan.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Lebar',
'exif-imagelength'                 => 'Tinggi',
'exif-bitspersample'               => 'Bit sekomponen',
'exif-compression'                 => 'Skema pemampatan',
'exif-photometricinterpretation'   => 'Komposisi piksel',
'exif-orientation'                 => 'Haluan',
'exif-samplesperpixel'             => 'Bilangan komponen',
'exif-planarconfiguration'         => 'Penyusunan data',
'exif-stripoffsets'                => 'Lokasi data imej',
'exif-jpeginterchangeformatlength' => 'Jumlah bait bagi data JPEG',
'exif-datetime'                    => 'Tarikh dan waktu fail diubah',
'exif-imagedescription'            => 'Tajuk imej',
'exif-make'                        => 'Pengilang kamera',
'exif-model'                       => 'Model kamera',
'exif-software'                    => 'Perisian digunakan',
'exif-artist'                      => 'Artis',
'exif-copyright'                   => 'Pemegang hak cipta',
'exif-exifversion'                 => 'Versi exif',
'exif-flashpixversion'             => 'Versi Flashpix yang disokong',
'exif-colorspace'                  => 'Ruang warna',
'exif-componentsconfiguration'     => 'Maksud setiap komponen',
'exif-compressedbitsperpixel'      => 'Mod pemampatan imej',
'exif-pixelydimension'             => 'Lebar imej',
'exif-pixelxdimension'             => 'Tinggi imej',
'exif-makernote'                   => 'Catatan pengilang',
'exif-usercomment'                 => 'Komen pengguna',
'exif-relatedsoundfile'            => 'Fail audio berkaitan',
'exif-datetimeoriginal'            => 'Tarikh dan waktu penjanaan data',
'exif-datetimedigitized'           => 'Tarikh dan waktu pendigitan',
'exif-exposuretime'                => 'Tempoh pendedahan',
'exif-exposuretime-format'         => '$1 saat ($2)',
'exif-exposureprogram'             => 'Atur cara pendedahan',
'exif-isospeedratings'             => 'Penilaian kelajuan ISO',
'exif-oecf'                        => 'Faktor penukaran optoelektronik',
'exif-shutterspeedvalue'           => 'Kelajuan pengatup',
'exif-aperturevalue'               => 'Bukaan',
'exif-brightnessvalue'             => 'Kecerahan',
'exif-exposurebiasvalue'           => 'Kecenderungan pendedahan',
'exif-maxaperturevalue'            => 'Bukaan tanah maksimum',
'exif-meteringmode'                => 'Mod permeteran',
'exif-lightsource'                 => 'Sumber cahaya',
'exif-flash'                       => 'Denyar',
'exif-focallength'                 => 'Panjang fokus kanta',
'exif-subjectarea'                 => 'Subject area',
'exif-flashenergy'                 => 'Tenaga denyar',
'exif-exposureindex'               => 'Indeks pendedahan',
'exif-sensingmethod'               => 'Sensing method',
'exif-filesource'                  => 'Sumber fail',
'exif-scenetype'                   => 'Jenis latar',
'exif-cfapattern'                  => 'Corak CFA',
'exif-customrendered'              => 'Pemprosesan imej tempahan',
'exif-exposuremode'                => 'Mod pendedahan',
'exif-whitebalance'                => 'Imbangan warna putih',
'exif-digitalzoomratio'            => 'Nisbah zum digital',
'exif-gaincontrol'                 => 'Kawalan latar',
'exif-contrast'                    => 'Kontras',
'exif-saturation'                  => 'Kepekatan',
'exif-sharpness'                   => 'Ketajaman',
'exif-devicesettingdescription'    => 'Huraian tetapan peranti',
'exif-imageuniqueid'               => 'ID imej unik',
'exif-gpslatituderef'              => 'Latitud utara atau selatan',
'exif-gpslatitude'                 => 'Latitud',
'exif-gpslongituderef'             => 'Logitud timur atau barat',
'exif-gpslongitude'                => 'Longitud',
'exif-gpsaltituderef'              => 'Rujukan ketinggian',
'exif-gpsaltitude'                 => 'Ketinggian',
'exif-gpssatellites'               => 'Satelit yang digunakan untuk pengukuran',
'exif-gpsstatus'                   => 'Status penerima',
'exif-gpsmeasuremode'              => 'Mod pengukuran',
'exif-gpsspeedref'                 => 'Unit kelajuan',
'exif-gpsspeed'                    => 'Kelajuan penerima GPS',
'exif-gpsprocessingmethod'         => 'Nama kaedah pemprosesan GPS',
'exif-gpsareainformation'          => 'Nama kawasan GPS',
'exif-gpsdatestamp'                => 'Tarikh GPS',

# EXIF attributes
'exif-compression-1' => 'Tidak dimampat',

'exif-orientation-2' => 'Dibalikkan secara melintang', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Diputar 180Â°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Dibalikkan secara menegak', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Diputarkan 90Â° melawan arah jam dan dibalikkan secara menegak', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Diputarkan 90Â° mengikut arah jam', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Diputarkan 90Â° mengikut arah jam dan dibalikkan secara menegak', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Diputarkan 90Â° melawan arah jam', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'format besar',
'exif-planarconfiguration-2' => 'format satah',

'exif-componentsconfiguration-0' => 'tiada',

'exif-exposureprogram-0' => 'Tidak ditentukan',
'exif-exposureprogram-1' => 'Manual',
'exif-exposureprogram-2' => 'Atur cara normal',
'exif-exposureprogram-3' => 'Keutamaan bukaan',
'exif-exposureprogram-4' => 'Keutamaan pengatup',
'exif-exposureprogram-7' => 'Mod potret (untuk foto jarak dekat dengan latar belakang kabur)',
'exif-exposureprogram-8' => 'Mod landskap (untuk foto landskap dengan latar belakang terfokus)',

'exif-subjectdistance-value' => '$1 meter',

'exif-meteringmode-0'   => 'Tidak diketahui',
'exif-meteringmode-255' => 'Lain-lain',

'exif-lightsource-0'   => 'Tidak diketahui',
'exif-lightsource-1'   => 'Cahaya siang',
'exif-lightsource-2'   => 'Pendarfluor',
'exif-lightsource-3'   => 'Tungsten (lampu pijar)',
'exif-lightsource-4'   => 'Denyar',
'exif-lightsource-9'   => 'Cuaca cerah',
'exif-lightsource-10'  => 'Cuaca mendung',
'exif-lightsource-11'  => 'Gelap',
'exif-lightsource-255' => 'Sumber cahaya lain',

'exif-focalplaneresolutionunit-2' => 'inci',

'exif-customrendered-0' => 'Proses biasa',
'exif-customrendered-1' => 'Proses tempahan',

'exif-exposuremode-0' => 'Pendedahan automatik',
'exif-exposuremode-1' => 'Pendedahan manual',
'exif-exposuremode-2' => 'Braket automatik',

'exif-whitebalance-0' => 'Imbangan warna putih automatik',
'exif-whitebalance-1' => 'Imbangan warna putih manual',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Landskap',
'exif-scenecapturetype-2' => 'Potret',
'exif-scenecapturetype-3' => 'Malam',

'exif-gaincontrol-0' => 'Tiada',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Lembut',
'exif-contrast-2' => 'Keras',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Kepekatan rendah',
'exif-saturation-2' => 'Kepekatan tinggi',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Lembut',
'exif-sharpness-2' => 'Keras',

'exif-subjectdistancerange-0' => 'Tidak diketahui',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Pandangan dekat',
'exif-subjectdistancerange-3' => 'Pandangan jauh',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Latitud utara',
'exif-gpslatitude-s' => 'Latitud selatan',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Longitud timur',
'exif-gpslongitude-w' => 'Longitud barat',

'exif-gpsmeasuremode-2' => 'Pengukuran dua dimensi',
'exif-gpsmeasuremode-3' => 'Pengukuran tiga dimensi',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilometer sejam',
'exif-gpsspeed-m' => 'Batu sejam',
'exif-gpsspeed-n' => 'Knots',

# External editor support
'edit-externally'      => 'Sunting fail ini menggunakan aplikasi luar',
'edit-externally-help' => 'Lihat [http://meta.wikimedia.org/wiki/Help:External_editors arahan pemasangan] untuk maklumat lanjut.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'semua',
'imagelistall'     => 'semua',
'watchlistall2'    => 'semua',
'namespacesall'    => 'semua',
'monthsall'        => 'semua',

# E-mail address confirmation
'confirmemail'            => 'Sahkan alamat e-mel',
'confirmemail_noemail'    => 'Anda belum menetapkan alamat e-mel yang sah dalam [[Special:Preferences|laman keutamaan]] anda.',
'confirmemail_text'       => 'Wiki ini meminta supaya anda mengesahkan alamat e-mel anda sebelum menggunakan ciri-ciri e-mel.
Aktifkan butang di bawah untuk mengirim e-mel pengesahan kepada alamat e-mel
anda. E-mel tersebut akan mengandungi sebuah pautan yang mengandungi sebuah
kod; buka pautan tersebut di pelayar anda untuk mengesahkan bahawa alamat e-mel anda.',
'confirmemail_pending'    => '<div class="error">
Sebuah kod pengesahan telah pun di-e-melkan kepada anda. Jika anda baru sahaja
membuka akaun, sila tunggu kehadiran e-mel tersebut selama beberapa minit
sebelum meminta kod baru.
</div>',
'confirmemail_send'       => 'E-melkan kod pengesahan',
'confirmemail_sent'       => 'E-mel pengesahan dikirim.',
'confirmemail_oncreate'   => 'Sebuah kod pengesahan telah dikirm kepada alamat e-mel anda.
Kod ini tidak diperlukan untuk log masuk, akan tetapi anda perlu menyediakannya untuk
mengaktifkan ciri-ciri e-mel yang terdapat dalam wiki ini.',
'confirmemail_sendfailed' => 'E-mel pengesahan tidak dapat dikirim. Sila semak alamat e-mel tersebut.

Pelayan mel memulangkan: $1',
'confirmemail_invalid'    => 'Kod pengesahan tidak sah. Kod tersebut mungkin sudah luput.',
'confirmemail_needlogin'  => 'Anda perlu $1 terlebih dahulu untuk mengesahkan alamat e-mel anda.',
'confirmemail_success'    => 'Alamat e-mel anda telah disahkan. Sekarang anda boleh melog masuk dan berseronok di wiki ini.',
'confirmemail_loggedin'   => 'Alamat e-mel anda telah disahkan.',
'confirmemail_error'      => 'Sesuatau yang tidak kena berlaku ketika kami menyimpan pengesahan anda.',
'confirmemail_subject'    => 'Pengesahan alamat e-mel di {{SITENAME}}',
'confirmemail_body'       => 'Seseorang, barangkali anda daripada alamat IP $1, telah mendaftarkan sebuah
akaun "$2" dengan alamat e-mel ini di {{SITENAME}}.

Untuk mengesahkan bahawa akaun ini milik anda dan mengaktifkan
ciri-ciri e-mel di {{SITENAME}}, sila buka pautan ini dalam pelayar anda:

$3

Jika ini *bukan* anda, jangan buka pautan tersebut. Kod pengesahan ini
akan luput pada $4.',

# Scary transclusion
'scarytranscludedisabled' => '[Kemasukan pautan interwiki dimatikan]',
'scarytranscludefailed'   => '[Gagal mendapatkan templat $1; harap maaf]',
'scarytranscludetoolong'  => '[URL terlalu panjang; harap maaf]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Jejak balik bagi rencana ini:<br />
$1
</div>',
'trackbackremove'   => ' ([Hapus $1])',
'trackbacklink'     => 'Jejak balik',
'trackbackdeleteok' => 'Jejak balik dihapuskan.',

# Delete conflict
'deletedwhileediting' => 'Amaran: Laman ini dihapuskan ketika anda sedang menyuntingnya!',
'confirmrecreate'     => "Pengguna [[User:$1|$1]] ([[User talk:$1|perbincangan]]) telah menghapuskan laman ini ketika anda sedang menyunting atas sebab berikut:
: ''$2''
Sila sahkan bahawa anda mahu mencipta semula laman ini.",
'recreate'            => 'Cipta semula',

# HTML dump
'redirectingto' => 'Melencong ke [[$1]]...',

# action=purge
'confirm_purge'        => 'Kosongkan fail simpanan bagi laman ini?

$1',
'confirm_purge_button' => 'OK',

# AJAX search
'searchcontaining' => "Cari rencana mengandungi ''$1''.",
'searchnamed'      => "Cari rencana bernama ''$1''.",
'articletitles'    => "Rencana bermula dengan ''$1''",
'hideresults'      => 'Sembunyikan keputusan',

# Multipage image navigation
'imgmultipageprev'   => '← halaman sebelumnya',
'imgmultipagenext'   => 'halaman berikutnya →',
'imgmultigo'         => 'Pergi!',
'imgmultigotopre'    => 'Buka halaman',
'imgmultiparseerror' => 'Imej ini rosak atau salah, oleh itu {{SITENAME}} tidak boleh mendapatkan senarai halaman.',

# Table pager
'ascending_abbrev'         => 'menaik',
'descending_abbrev'        => 'menurun',
'table_pager_next'         => 'Laman berikutnya',
'table_pager_prev'         => 'Laman sebelumnya',
'table_pager_first'        => 'Halaman pertama',
'table_pager_last'         => 'Halaman terakhir',
'table_pager_limit'        => 'Tunjukkan $1 item setiap halaman',
'table_pager_limit_submit' => 'Pergi',
'table_pager_empty'        => 'Tiada keputusan',

# Auto-summaries
'autosumm-blank'   => 'Membuang semua kandungan daripada laman',
'autosumm-replace' => "Menggantikan laman dengan '$1'",
'autoredircomment' => 'Melencong ke [[$1]]',
'autosumm-new'     => 'Laman baru: $1',

# Size units
'size-bytes'     => '$1 B',
'size-kilobytes' => '$1 KB',
'size-megabytes' => '$1 MB',
'size-gigabytes' => '$1 GB',

# Live preview
'livepreview-loading' => 'Memuat …',
'livepreview-ready'   => 'Memuat … Sedia!',
'livepreview-failed'  => 'Pratonton langsung gagal! Sila gunakan pratonton biasa.',
'livepreview-error'   => 'Gagal membuat sambungan: $1 "$2". Sila gunakan pratonton biasa.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Sebarang perubahan baru yang melebihi $1 saat mungkin tidak ditunjukkan dalam senarai ini.',
'lag-warn-high'   => 'Disebabkan oleh kelambatan pelayan pangkalan data, sebarang perubahan baru yang melebihi $1 saat mungkin tidak ditunjukkan dalam senarai ini.',

# Watchlist editor
'watchlistedit-numitems'       => 'Senarai pantau anda mengandungi $1 tajuk (tidak termasuk laman perbincangan).',
'watchlistedit-noitems'        => 'Tiada tajuk dalam senarai pantau anda.',
'watchlistedit-clear-title'    => 'Kosongkan senarai pantau',
'watchlistedit-clear-legend'   => 'Kosongkan senarai pantau',
'watchlistedit-clear-confirm'  => 'Tindakan ini akan membuang semua tajuk daripada senarai pantau anda. Betul anda mahu
berbuat demikian? Anda juga boleh [[Special:Watchlist/edit|membuang tajuk-tajuk tertentu]].',
'watchlistedit-clear-submit'   => 'Kosongkan',
'watchlistedit-clear-done'     => 'Senarai pantau anda telah dikosongkan. Semua tajuk telah dibuang.',
'watchlistedit-normal-title'   => 'Sunting senarai pantau',
'watchlistedit-normal-legend'  => 'Buang tajuk daripada senarai pantau',
'watchlistedit-normal-explain' => 'Berikut ialah tajuk-tajuk dalam senarai pantau anda. Untuk membuang mana-mana tajuk, semak
kotak yang terletak di sebelahnya, dan klik Buang Tajuk. Anda juga boleh [[Special:Watchlist/raw|menyunting senarai mentah]]
atau [[Special:Watchlist/clear|membuang semua tajuk]].',
'watchlistedit-normal-submit'  => 'Buang Tajuk',
'watchlistedit-normal-done'    => '$1 tajuk dibuang daripada senarai pantau anda:',
'watchlistedit-raw-title'      => 'Sunting senarai pantau mentah',
'watchlistedit-raw-legend'     => 'Sunting senarai pantau mentah',
'watchlistedit-raw-explain'    => 'Berikut ialah tajuk-tajuk dalam senarai pantau anda. Anda boleh menyunting mana-mana tajuk
dengan menambah atau membuang daripada senarai tersebut, satu tajuk bagi setiap baris. Apabila selesai, klik Kemas Kini Senarai Pantau.
Anda juga boleh [[Special:Watchlist/edit|menggunakan penyunting standard]].',
'watchlistedit-raw-titles'     => 'Tajuk:',
'watchlistedit-raw-submit'     => 'Kemas Kini Senarai Pantau',
'watchlistedit-raw-done'       => 'Senarai pantau anda telah dikemaskinikan.',
'watchlistedit-raw-added'      => '$1 tajuk ditambah:',
'watchlistedit-raw-removed'    => '$1 tajuk telah dibuang:',

# Watchlist editing tools
'watchlisttools-view'  => 'Lihat perubahan',
'watchlisttools-edit'  => 'Sunting senarai pantau',
'watchlisttools-raw'   => 'Sunting senarai pantau mentah',
'watchlisttools-clear' => 'Kosongkan senarai pantau',
);
