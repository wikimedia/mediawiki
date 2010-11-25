<?php
/** Malay (Bahasa Melayu)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Aurora
 * @author Aviator
 * @author CoolCityCat
 * @author Diagramma Della Verita
 * @author Izzudin
 * @author Kurniasan
 * @author Meno25
 * @author Putera Luqman Tunku Andre
 * @author Urhixidur
 * @author Yosri
 * @author Zamwan
 * @author לערי ריינהארט
 */

/**
 * CHANGELOG
 * =========
 * Init - This localisation is based on a file kindly donated by the folks at MIMOS
 * http://www.asiaosc.org/enwiki/page/Knowledgebase_Home.html
 * Sep 2007 - Rewritten by the folks at ms.wikipedia.org
 */

$defaultDateFormat = 'dmy';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Khas',
	NS_TALK             => 'Perbincangan',
	NS_USER             => 'Pengguna',
	NS_USER_TALK        => 'Perbincangan_Pengguna',
	NS_PROJECT_TALK     => 'Perbincangan_$1',
	NS_FILE             => 'Fail',
	NS_FILE_TALK        => 'Perbincangan_Fail',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Perbincangan_MediaWiki',
	NS_TEMPLATE         => 'Templat',
	NS_TEMPLATE_TALK    => 'Perbincangan_Templat',
	NS_HELP             => 'Bantuan',
	NS_HELP_TALK        => 'Perbincangan_Bantuan',
	NS_CATEGORY         => 'Kategori',
	NS_CATEGORY_TALK    => 'Perbincangan_Kategori',
);

$namespaceAliases = array(
	'Imej' => NS_FILE,
	'Perbincangan_Imej' => NS_FILE_TALK,
	'Istimewa'            => NS_SPECIAL,
	'Perbualan'           => NS_TALK,
	'Perbualan_Pengguna'  => NS_USER_TALK,
	'Perbualan_$1'        => NS_PROJECT_TALK,
	'Imej_Perbualan'      => NS_FILE_TALK,
	'MediaWiki_Perbualan' => NS_MEDIAWIKI_TALK,
	'Perbualan_Templat'   => NS_TEMPLATE_TALK,
	'Perbualan_Kategori'  => NS_CATEGORY_TALK,
	'Perbualan_Bantuan'   => NS_HELP_TALK,
);


$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Lencongan_berganda', 'Pelencongan_berganda' ),
	'BrokenRedirects'           => array( 'Lencongan_rosak', 'Pelencongan_rosak' ),
	'Disambiguations'           => array( 'Penyahtaksaan' ),
	'Userlogin'                 => array( 'Log_masuk' ),
	'Userlogout'                => array( 'Log_keluar' ),
	'CreateAccount'             => array( 'Buka_akaun' ),
	'Preferences'               => array( 'Keutamaan' ),
	'Watchlist'                 => array( 'Senarai_pantau' ),
	'Recentchanges'             => array( 'Perubahan_terkini' ),
	'Upload'                    => array( 'Muat_naik' ),
	'Listfiles'                 => array( 'Senarai_imej' ),
	'Newimages'                 => array( 'Imej_baru' ),
	'Listusers'                 => array( 'Senarai_pengguna' ),
	'Listgrouprights'           => array( 'Senarai_hak_kumpulan' ),
	'Statistics'                => array( 'Statistik' ),
	'Randompage'                => array( 'Laman_rawak' ),
	'Lonelypages'               => array( 'Laman_yatim' ),
	'Uncategorizedpages'        => array( 'Laman_tanpa_kategori' ),
	'Uncategorizedcategories'   => array( 'Kategori_tanpa_kategori' ),
	'Uncategorizedimages'       => array( 'Imej_tanpa_kategori' ),
	'Uncategorizedtemplates'    => array( 'Templat_tanpa_kategori' ),
	'Unusedcategories'          => array( 'Kategori_tak_digunakan' ),
	'Unusedimages'              => array( 'Imej_tak_digunakan' ),
	'Wantedpages'               => array( 'Laman_dikehendaki' ),
	'Wantedcategories'          => array( 'Kategori_dikehendaki' ),
	'Wantedfiles'               => array( 'Fail_dikehendaki' ),
	'Wantedtemplates'           => array( 'Templat_dikehendaki' ),
	'Mostlinked'                => array( 'Laman_dipaut_terbanyak' ),
	'Mostlinkedcategories'      => array( 'Kategori_dipaut_terbanyak' ),
	'Mostlinkedtemplates'       => array( 'Templat_dipaut_terbanyak' ),
	'Mostimages'                => array( 'Imej_terbanyak' ),
	'Mostcategories'            => array( 'Kategori_terbanyak' ),
	'Mostrevisions'             => array( 'Semakan_terbanyak' ),
	'Fewestrevisions'           => array( 'Semakan_tersikit' ),
	'Shortpages'                => array( 'Laman_pendek' ),
	'Longpages'                 => array( 'Laman_panjang' ),
	'Newpages'                  => array( 'Laman_baru' ),
	'Ancientpages'              => array( 'Laman_lapuk' ),
	'Deadendpages'              => array( 'Laman_buntu' ),
	'Protectedpages'            => array( 'Laman_dilindungi' ),
	'Protectedtitles'           => array( 'Tajuk_dilindungi' ),
	'Allpages'                  => array( 'Semua_laman' ),
	'Prefixindex'               => array( 'Indeks_awalan' ),
	'Ipblocklist'               => array( 'Sekatan_IP' ),
	'Specialpages'              => array( 'Laman_khas' ),
	'Contributions'             => array( 'Sumbangan' ),
	'Emailuser'                 => array( 'E-mel_pengguna' ),
	'Confirmemail'              => array( 'Sahkan_e-mel' ),
	'Whatlinkshere'             => array( 'Pautan_ke' ),
	'Recentchangeslinked'       => array( 'Perubahan_berkaitan' ),
	'Movepage'                  => array( 'Pindah_laman' ),
	'Blockme'                   => array( 'Sekat_saya' ),
	'Booksources'               => array( 'Sumber_buku' ),
	'Categories'                => array( 'Kategori' ),
	'Export'                    => array( 'Eksport' ),
	'Version'                   => array( 'Versi' ),
	'Allmessages'               => array( 'Semua_pesanan', 'Semua_mesej' ),
	'Blockip'                   => array( 'Sekat_IP' ),
	'Undelete'                  => array( 'Nyahhapus' ),
	'Lockdb'                    => array( 'Kunci_pangkalan_data' ),
	'Unlockdb'                  => array( 'Buka_kunci_pangkalan_data' ),
	'Userrights'                => array( 'Hak_pengguna' ),
	'MIMEsearch'                => array( 'Gelintar_MIME' ),
	'FileDuplicateSearch'       => array( 'Cari_fail_berganda' ),
	'Unwatchedpages'            => array( 'Laman_tak_dipantau' ),
	'Listredirects'             => array( 'Senarai_lencongan', 'Senarai_pelencongan' ),
	'Revisiondelete'            => array( 'Hapus_semakan' ),
	'Unusedtemplates'           => array( 'Templat_tak_digunakan' ),
	'Randomredirect'            => array( 'Lencongan_rawak', 'Pelencongan_rawak' ),
	'Mypage'                    => array( 'Laman_saya' ),
	'Mytalk'                    => array( 'Perbincangan_saya' ),
	'Mycontributions'           => array( 'Sumbangan_saya' ),
	'Listadmins'                => array( 'Senarai_pentadbir' ),
	'Listbots'                  => array( 'Senarai_bot' ),
	'Popularpages'              => array( 'Laman_popular' ),
	'Search'                    => array( 'Gelintar' ),
	'Resetpass'                 => array( 'Lupa_kata_laluan' ),
	'Withoutinterwiki'          => array( 'Laman_tanpa_pautan_bahasa' ),
	'MergeHistory'              => array( 'Gabung_sejarah' ),
	'Filepath'                  => array( 'Laluan_fail' ),
	'Invalidateemail'           => array( 'Batalkan_pengesahan_e-mel' ),
	'Blankpage'                 => array( 'Laman_kosong' ),
	'LinkSearch'                => array( 'Cari_pautan' ),
	'DeletedContributions'      => array( 'Sumbangan_dihapuskan' ),
	'Tags'                      => array( 'Label' ),
	'Activeusers'               => array( 'Pengguna_aktif' ),
	'RevisionMove'              => array( 'Pindah_semakan' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Gariskan pautan:',
'tog-highlightbroken'         => 'Formatkan pautan rosak <a href="" class="new">seperti ini</a> (ataupun seperti ini<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Laraskan perenggan',
'tog-hideminor'               => 'Sembunyikan suntingan kecil dalam laman perubahan terkini',
'tog-hidepatrolled'           => 'Sorokkan suntingan yang telah dironda daripada senarai perubahan terkini',
'tog-newpageshidepatrolled'   => 'Sorokkan laman yang telah dironda daripada senarai laman baru',
'tog-extendwatchlist'         => 'Kembangkan senarai pantau untuk memaparkan semua perubahan, bukan hanya yang terkini',
'tog-usenewrc'                => 'Guna peningkatan perubahan terkini (perlukan JavaScript)',
'tog-numberheadings'          => 'Nomborkan tajuk secara automatik',
'tog-showtoolbar'             => 'Tunjukkan bar sunting (JavaScript)',
'tog-editondblclick'          => 'Sunting laman ketika dwiklik (JavaScript)',
'tog-editsection'             => 'Bolehkan penyuntingan bahagian melalui pautan [sunting]',
'tog-editsectiononrightclick' => 'Bolehkan penyuntingan bahagian dengan mengklik kanan pada tajuk bahagian (JavaScript)',
'tog-showtoc'                 => 'Tunjukkan isi kandungan (bagi rencana yang melebihi 3 tajuk)',
'tog-rememberpassword'        => 'Ingat log masuk saya di pelayar ini (tidak melebihi $1 {{PLURAL:$1|hari|hari}})',
'tog-watchcreations'          => 'Tambahkan laman yang saya cipta ke dalam senarai pantau',
'tog-watchdefault'            => 'Tambahkan laman yang saya sunting ke dalam senarai pantau',
'tog-watchmoves'              => 'Tambahkan laman yang saya pindahkan ke dalam senarai pantau',
'tog-watchdeletion'           => 'Tambahkan laman yang saya hapuskan ke dalam senarai pantau',
'tog-minordefault'            => 'Tandakan suntingan kecil secara lalai',
'tog-previewontop'            => 'Tunjukkan pratonton di atas kotak sunting',
'tog-previewonfirst'          => 'Tunjukkan pratonton pada suntingan pertama',
'tog-nocache'                 => 'Lumpuhkan pengagregatan laman',
'tog-enotifwatchlistpages'    => 'E-melkan saya apabila berlaku perubahan pada laman yang dipantau',
'tog-enotifusertalkpages'     => 'E-melkan saya apabila berlaku perubahan pada laman perbincangan saya',
'tog-enotifminoredits'        => 'Juga e-melkan saya apabila berlaku penyuntingan kecil',
'tog-enotifrevealaddr'        => 'Serlahkan alamat e-mel saya dalam e-mel pemberitahuan',
'tog-shownumberswatching'     => 'Tunjukkan bilangan pemantau',
'tog-oldsig'                  => 'Pratonton bagi tanda tangan yang sedia ada:',
'tog-fancysig'                => 'Anggap tandatangan sebagai teks wiki (tanpa pautan automatik)',
'tog-externaleditor'          => 'Gunakan penyunting luar secara lalai',
'tog-externaldiff'            => 'Gunakan pembeza luar secara lalai (untuk pakar sahaja, perlu penetapan khas pada komputer anda)',
'tog-showjumplinks'           => 'Bolehkan pautan ketercapaian "lompat ke"',
'tog-uselivepreview'          => 'Gunakan pratonton langsung (JavaScript) (masih dalam uji kaji)',
'tog-forceeditsummary'        => 'Tanya saya jika ringkasan suntingan kosong',
'tog-watchlisthideown'        => 'Sembunyikan suntingan saya daripada senarai pantau',
'tog-watchlisthidebots'       => 'Sembunyikan suntingan bot daripada senarai pantau',
'tog-watchlisthideminor'      => 'Sembunyikan suntingan kecil daripada senarai pantau',
'tog-watchlisthideliu'        => 'Sembunyikan suntingan oleh pengguna log masuk daripada senarai pantau',
'tog-watchlisthideanons'      => 'Sembunyikan suntingan oleh pengguna tanpa nama daripada senarai pantau',
'tog-watchlisthidepatrolled'  => 'Sorokkan suntingan yang telah dironda daripada senarai pantau',
'tog-nolangconversion'        => 'Lumpuhkan penukaran kelainan',
'tog-ccmeonemails'            => 'Kirimkan saya salinan e-mel yang saya hantar kepada pengguna lain',
'tog-diffonly'                => 'Jangan tunjukkan kandungan laman di bawah perbezaan',
'tog-showhiddencats'          => 'Tunjukkan kategori tersembunyi',
'tog-noconvertlink'           => 'Lumpuhkan penukaran tajuk pautan',
'tog-norollbackdiff'          => 'Abaikan perbezaan selepas melakukan pengunduran suntingan.',

'underline-always'  => 'Sentiasa',
'underline-never'   => 'Jangan',
'underline-default' => 'Pelayar asal',

# Font style option in Special:Preferences
'editfont-style'     => 'Gaya fon ruang sunting:',
'editfont-default'   => 'Pelayar asal',
'editfont-monospace' => 'Fon monospace',
'editfont-sansserif' => 'Fon sans-serif',
'editfont-serif'     => 'Fon serif',

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

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategori|Kategori}}',
'category_header'                => 'Laman-laman dalam kategori "$1"',
'subcategories'                  => 'Subkategori',
'category-media-header'          => 'Media-media dalam kategori "$1"',
'category-empty'                 => "''Kategori ini tidak mengandungi sebarang laman atau media.''",
'hidden-categories'              => '{{PLURAL:$1|Kategori tersembunyi|Kategori-kategori tersembunyi}}',
'hidden-category-category'       => 'Kategori tersembunyi',
'category-subcat-count'          => '{{PLURAL:$2|Kategori ini mengandungi sebuah subkategori berikut.|Yang berikut ialah $1 daripada $2 buah subkategori dalam kategori ini.}}',
'category-subcat-count-limited'  => 'Katergori ini mengandungi {{PLURAL:$1|subkategori|$1 subkategori}}.',
'category-article-count'         => '{{PLURAL:$2|Kategori ini mengandungi sebuah laman berikut.|Yang berikut ialah $1 daripada $2 buah laman dalam kategori ini.}}',
'category-article-count-limited' => '{{PLURAL:$1|Laman berikut|$1 laman berikut}} kini terkandung dalam kategori terkini.',
'category-file-count'            => '{{PLURAL:$2|Kategori ini mengandungi sebuah fail berikut.|Yang berikut ialah $1 daripada $2 buah fail dalam kategori ini.}}',
'category-file-count-limited'    => '$1 fail berikut terdapat dalam kategori ini.',
'listingcontinuesabbrev'         => 'samb.',
'index-category'                 => 'Laman terindeks',
'noindex-category'               => 'Laman tak diindeks',

'mainpagetext'      => "'''MediaWiki telah berjaya dipasang.'''",
'mainpagedocfooter' => 'Sila rujuk [http://meta.wikimedia.org/wiki/Help:Contents Panduan Penggunaan] untuk maklumat mengenai penggunaan perisian wiki ini.

== Untuk bermula ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Senarai tetapan konfigurasi]
* [http://www.mediawiki.org/wiki/Manual:FAQ Soalan Lazim MediaWiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Senarai mel bagi keluaran MediaWiki]',

'about'         => 'Perihal',
'article'       => 'Laman kandungan',
'newwindow'     => '(dibuka di tetingkap baru)',
'cancel'        => 'Batal',
'moredotdotdot' => 'Lagi...',
'mypage'        => 'Laman saya',
'mytalk'        => 'Perbualan saya',
'anontalk'      => 'Perbualan bagi IP ini',
'navigation'    => 'Pandu arah',
'and'           => '&#32;dan',

# Cologne Blue skin
'qbfind'         => 'Cari',
'qbbrowse'       => 'Semak imbas',
'qbedit'         => 'Sunting',
'qbpageoptions'  => 'Laman ini',
'qbpageinfo'     => 'Konteks',
'qbmyoptions'    => 'Laman-laman saya',
'qbspecialpages' => 'Laman khas',
'faq'            => 'Soalan Lazim',
'faqpage'        => 'Project:Soalan Lazim',

# Vector skin
'vector-action-addsection'       => 'Tambah topik',
'vector-action-delete'           => 'Hapus',
'vector-action-move'             => 'Pindah',
'vector-action-protect'          => 'Lindungi',
'vector-action-undelete'         => 'Batal hapus',
'vector-action-unprotect'        => 'Nyahlindung',
'vector-simplesearch-preference' => 'Bolehkan cadangan gelintar maju (rupa Vector sahaja)',
'vector-view-create'             => 'Cipta',
'vector-view-edit'               => 'Sunting',
'vector-view-history'            => 'Lihat sejarah',
'vector-view-view'               => 'Baca',
'vector-view-viewsource'         => 'Lihat sumber',
'actions'                        => 'Tindakan',
'namespaces'                     => 'Ruang nama',
'variants'                       => 'Kelainan',

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
'create'            => 'Cipta',
'editthispage'      => 'Sunting laman ini',
'create-this-page'  => 'Cipta laman ini',
'delete'            => 'Hapus',
'deletethispage'    => 'Hapuskan laman ini',
'undelete_short'    => 'Nyahhapus {{PLURAL:$1|satu suntingan|$1 suntingan}}',
'protect'           => 'Lindung',
'protect_change'    => 'ubah',
'protectthispage'   => 'Lindungi laman ini',
'unprotect'         => 'Nyahlindung',
'unprotectthispage' => 'Nyahlindung laman ini',
'newpage'           => 'Laman baru',
'talkpage'          => 'Bincangkan laman ini',
'talkpagelinktext'  => 'Perbualan',
'specialpage'       => 'Laman Khas',
'personaltools'     => 'Alatan peribadi',
'postcomment'       => 'Bahagian baru',
'articlepage'       => 'Lihat laman kandungan',
'talk'              => 'Perbincangan',
'views'             => 'Pandangan',
'toolbox'           => 'Alatan',
'userpage'          => 'Lihat laman pengguna',
'projectpage'       => 'Lihat laman projek',
'imagepage'         => 'Lihat laman fail',
'mediawikipage'     => 'Lihat laman pesanan',
'templatepage'      => 'Lihat laman templat',
'viewhelppage'      => 'Lihat laman bantuan',
'categorypage'      => 'Lihat laman kategori',
'viewtalkpage'      => 'Lihat perbincangan',
'otherlanguages'    => 'Bahasa lain',
'redirectedfrom'    => '(Dilencongkan dari $1)',
'redirectpagesub'   => 'Laman lencongan',
'lastmodifiedat'    => 'Laman ini diubah buat kali terakhir pada $2, $1.',
'viewcount'         => 'Laman ini telah dilihat {{PLURAL:$1|sekali|sebanyak $1 kali}}.',
'protectedpage'     => 'Laman dilindungi',
'jumpto'            => 'Lompat ke:',
'jumptonavigation'  => 'pandu arah',
'jumptosearch'      => 'gelintar',
'view-pool-error'   => 'Maaf, pelayan terlebih bebanan pada masa ini.
Terlalu ramai pengguna cuba melihat laman ini.
Sila tunggu sebentar sebelum cuba mencapai laman ini lagi.

$1',
'pool-timeout'      => 'Menunggu sebentar untuk dikunci',
'pool-queuefull'    => 'Giliran kolam telah penuh',
'pool-errorunknown' => 'Ralat yang tidak diketahui',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Perihal {{SITENAME}}',
'aboutpage'            => 'Project:Perihal',
'copyright'            => 'Kandungan disediakan dengan $1.',
'copyrightpage'        => '{{ns:project}}:Hak cipta',
'currentevents'        => 'Hal semasa',
'currentevents-url'    => 'Project:Hal semasa',
'disclaimers'          => 'Penolak tuntutan',
'disclaimerpage'       => 'Project:Penolak tuntutan umum',
'edithelp'             => 'Bantuan menyunting',
'edithelppage'         => 'Help:Menyunting',
'helppage'             => 'Help:Kandungan',
'mainpage'             => 'Laman Utama',
'mainpage-description' => 'Laman Utama',
'policy-url'           => 'Project:Dasar',
'portal'               => 'Portal masyarakat',
'portal-url'           => 'Project:Portal Masyarakat',
'privacy'              => 'Dasar privasi',
'privacypage'          => 'Project:Dasar privasi',

'badaccess'        => 'Tidak dibenarkan',
'badaccess-group0' => 'Anda tidak dibenarkan melaksanakan tindakan ini.',
'badaccess-groups' => 'Tindakan ini hanya boleh dilakukan oleh pengguna dari {{PLURAL:$2|kumpulan|kumpulan-kumpulan}} berikut: $1.',

'versionrequired'     => 'MediaWiki versi $1 diperlukan',
'versionrequiredtext' => 'MediaWiki versi $1 diperlukan untuk menggunakan laman ini. Sila lihat [[Special:Version|laman versi]].',

'ok'                      => 'Baik',
'retrievedfrom'           => 'Diambil daripada "$1"',
'youhavenewmessages'      => 'Anda mempunyai $1 ($2).',
'newmessageslink'         => 'pesanan baru',
'newmessagesdifflink'     => 'perubahan terakhir',
'youhavenewmessagesmulti' => 'Anda telah menerima pesanan baru pada $1',
'editsection'             => 'sunting',
'editold'                 => 'sunting',
'viewsourceold'           => 'lihat sumber',
'editlink'                => 'sunting',
'viewsourcelink'          => 'lihat sumber',
'editsectionhint'         => 'Sunting bahagian: $1',
'toc'                     => 'Isi kandungan',
'showtoc'                 => 'tunjuk',
'hidetoc'                 => 'sorok',
'thisisdeleted'           => 'Lihat atau pulihkan $1?',
'viewdeleted'             => 'Lihat $1?',
'restorelink'             => '{{PLURAL:$1|satu|$1}} suntingan dihapuskan',
'feedlinks'               => 'Suapan:',
'feed-invalid'            => 'Jenis suapan langganan tidak sah.',
'feed-unavailable'        => 'Tiada suapan pensindiketan',
'site-rss-feed'           => 'Suapan RSS $1',
'site-atom-feed'          => 'Suapan Atom $1',
'page-rss-feed'           => 'Suapan RSS "$1"',
'page-atom-feed'          => 'Suapan Atom "$1"',
'red-link-title'          => '$1 (tidak wujud)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Laman',
'nstab-user'      => 'Laman pengguna',
'nstab-media'     => 'Laman media',
'nstab-special'   => 'Laman khas',
'nstab-project'   => 'Laman projek',
'nstab-image'     => 'Imej',
'nstab-mediawiki' => 'Pesanan',
'nstab-template'  => 'Templat',
'nstab-help'      => 'Laman bantuan',
'nstab-category'  => 'Kategori',

# Main script and global functions
'nosuchaction'      => 'Tindakan tidak dikenali',
'nosuchactiontext'  => 'Tindakan yang dinyatakan dalam URL ini tidak sah. Anda mungkin telah menaip URL yang salah atau mengikuti pautan yang tidak sah. Ini juga mungkin bererti terdapat pepijat dalam perisian yang digunakan oleh {{SITENAME}}.',
'nosuchspecialpage' => 'Laman khas tidak wujud',
'nospecialpagetext' => '<strong>Anda telah meminta laman khas yang tidak sah.</strong>

Senarai laman khas yang sah boleh dilihat di [[Special:SpecialPages]].',

# General errors
'error'                => 'Ralat',
'databaseerror'        => 'Ralat pangkalan data',
'dberrortext'          => 'Ralat sintaks pertanyaan pangkalan data telah terjadi.
Ini mungkin menandakan pepijat dalam perisian wiki ini.
Pertanyaan pangkalan data yang terakhir ialah:
<blockquote><tt>$1</tt></blockquote>
daripada fungsi "<tt>$2</tt>".
Pangkalan data memulangkan ralat "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Terdapat ralat sintaks pertanyaan pangkalan data.
Pertanyaan terakhir ialah:
"$1"
daripada fungsi "$2".
Pangkalan data memulangkan ralat "$3: $4".',
'laggedslavemode'      => 'Amaran: Laman ini mungkin bukan yang terkini.',
'readonly'             => 'Pangkalan data dikunci',
'enterlockreason'      => 'Sila nyatakan sebab penguncian dan jangkaan
bila kunci ini akan dibuka.',
'readonlytext'         => 'Pangkalan data sedang dikunci. Hal ini mungkin disebabkan oleh penyenggaraan rutin, dan akan dibuka semula selepas proses penyenggaraan ini siap.

Pentadbir yang menguncinya memberi penjelasan ini: $1',
'missing-article'      => 'Teks bagi laman "$1" $2 tidak dijumpai dalam pangkalan data.

Perkara ini biasanya disebabkan oleh perbuatan mengikuti pautan perbezaan yang lama atau pautan ke laman yang telah dihapuskan.

Jika bukan ini sebabnya, anda mungkin telah menjumpai pepijat dalam perisian ini.
Sila catat URL bagi laman ini dan laporkan perkara ini kepada seorang [[Special:ListUsers/sysop|pentadbir]].',
'missingarticle-rev'   => '(semakan $1)',
'missingarticle-diff'  => '(Beza: $1, $2)',
'readonly_lag'         => 'Pangkalan data telah dikunci secara automatik sementara semua pelayan pangkalan data diselaraskan.',
'internalerror'        => 'Ralat dalaman',
'internalerror_info'   => 'Ralat dalaman: $1',
'fileappenderrorread'  => 'Tidak dapat membaca "$1" semasa tambah.',
'fileappenderror'      => 'Tidak dapat menambah "$1" kepada "$2".',
'filecopyerror'        => 'Fail "$1" tidak dapat disalin kepada "$2".',
'filerenameerror'      => 'Nama fail "$1" tidak dapat ditukarkan kepada "$2".',
'filedeleteerror'      => 'Fail "$1" tidak dapat dihapuskan.',
'directorycreateerror' => 'Direktori "$1" gagal diciptakan.',
'filenotfound'         => 'Fail "$1" tidak dijumpai.',
'fileexistserror'      => 'File "$1" tidak dapat ditulis: fail telah pun wujud',
'unexpected'           => 'Nilai tanpa diduga: "$1"="$2".',
'formerror'            => 'Ralat: borang tidak dapat dikirim.',
'badarticleerror'      => 'Tindakan ini tidak boleh dilaksanakan pada laman ini.',
'cannotdelete'         => 'Laman atau fail $1 tidak dapat dihapuskan.
Ia mungkin telah pun dihapuskan oleh orang lain.',
'badtitle'             => 'Tajuk tidak sah',
'badtitletext'         => 'Tajuk laman yang diminta tidak sah, kosong, ataupun tajuk antara bahasa atau tajuk antara wiki yang salah dipaut. Ia mungkin mengandungi aksara yang tidak dibenarkan.',
'perfcached'           => 'Data berikut adalah teragregat dan mungkin bukan yang terkini.',
'perfcachedts'         => 'Data berikut adalah teragregat dan dikemaskinikan buat kali terakhir pada $1.',
'querypage-no-updates' => 'Buat masa ini, pengkemaskinian laman ini telah dilumpuhkan.
Data yang ada di sini tidak akan disegarkan semula sekarang.',
'wrong_wfQuery_params' => 'Parameter salah bagi wfQuery()<br />
Fungsi: $1<br />
Pertanyaan: $2',
'viewsource'           => 'Lihat sumber',
'viewsourcefor'        => 'bagi $1',
'actionthrottled'      => 'Tindakan didikitkan',
'actionthrottledtext'  => 'Untuk mencegah spam, anda dihadkan daripada melakukan tindakan ini berulang kali dalam ruang waktu yang singkat, dan anda telah melebihi had tersebut. Sila cuba lagi selepas beberapa minit.',
'protectedpagetext'    => 'Laman ini telah dikunci untuk menghalang penyuntingan.',
'viewsourcetext'       => 'Anda boleh melihat dan menyalin sumber bagi laman ini:',
'protectedinterface'   => 'Laman ini menyediakan teks antara muka bagi perisian ini, akan tetapi dikunci untuk menghalang penyalahgunaan.',
'editinginterface'     => "'''Amaran:''' Anda sedang menyunting laman yang digunakan untuk menghasilkan teks antara muka bagi perisian ini. Sebarang perubahan terhadap laman ini akan menjejaskan rupa antara muka bagi pengguna-pengguna lain. Untuk melakukan penterjemahan, anda boleh menggunakan [http://translatewiki.net/wiki/Main_Page?setlang=ms translatewiki.net], sebuah projek penyetempatan MediaWiki.",
'sqlhidden'            => '(Pertanyaan SQL disorokkan)',
'cascadeprotected'     => 'Laman ini telah dilindungi daripada penyuntingan oleh pengguna selain penyelia, kerana ia termasuk dalam {{PLURAL:$1|laman|laman-laman}} berikut, yang dilindungi dengan secara "melata": $2',
'namespaceprotected'   => "Anda tidak mempunyai keizinan untuk menyunting laman dalam ruang nama '''$1'''.",
'customcssjsprotected' => 'Anda tidak mempunyai keizinan untuk menyunting laman ini kerana ia mengandungi tetapan peribadi pengguna lain.',
'ns-specialprotected'  => 'Laman khas tidak boleh disunting.',
'titleprotected'       => "Tajuk ini telah dilindungi oleh [[User:$1|$1]] daripada dicipta. Sebab yang diberikan ialah ''$2''.",

# Virus scanner
'virus-badscanner'     => "Konfigurasi rosak: pengimbas virus yang tidak diketahui: ''$1''",
'virus-scanfailed'     => 'pengimbasan gagal (kod $1)',
'virus-unknownscanner' => 'antivirus tidak dikenali:',

# Login and logout pages
'logouttext'                 => "'''Anda telah log keluar.'''

Anda boleh terus menggunakan {{SITENAME}} sebagai pengguna tanpa nama, atau anda boleh [[Special:UserLogin|log masuk sekali lagi]] sebagai pengguna lain. Anda boleh membersihkan cache pelayar web anda sekiranya terdapat laman yang memaparkan seolah-olah anda masih log masuk.",
'welcomecreation'            => '== Selamat datang, $1! ==

Akaun anda telah dibuka. Jangan lupa untuk mengubah [[Special:Preferences|keutamaan {{SITENAME}}]] anda.',
'yourname'                   => 'Nama pengguna:',
'yourpassword'               => 'Kata laluan:',
'yourpasswordagain'          => 'Ulangi kata laluan:',
'remembermypassword'         => '↓ Ingat kata laluan saya dalam komputer ini (maximum of $1 {{PLURAL:$1|hari|hari}})',
'yourdomainname'             => 'Domain anda:',
'externaldberror'            => 'Berlaku ralat pangkalan data bagi pengesahan luar atau anda tidak dibenarkan mengemaskinikan akaun luar anda.',
'login'                      => 'Log masuk',
'nav-login-createaccount'    => 'Log masuk / buka akaun',
'loginprompt'                => 'Anda mesti membenarkan kuki untuk log masuk ke dalam {{SITENAME}}.',
'userlogin'                  => 'Log masuk / buka akaun',
'userloginnocreate'          => 'Log masuk',
'logout'                     => 'Log keluar',
'userlogout'                 => 'Log keluar',
'notloggedin'                => 'Belum log masuk',
'nologin'                    => "Belum mempunyai akaun? '''$1'''.",
'nologinlink'                => 'Buka akaun baru',
'createaccount'              => 'Buka akaun',
'gotaccount'                 => "Sudah mempunyai akaun? '''$1'''.",
'gotaccountlink'             => 'Log masuk',
'createaccountmail'          => 'melalui e-mel',
'createaccountreason'        => 'Sebab:',
'badretype'                  => 'Sila ulangi kata laluan dengan betul.',
'userexists'                 => 'Nama pengguna yang anda masukkan telah pun digunakan. Sila pilih nama yang lain.',
'loginerror'                 => 'Ralat log masuk',
'createaccounterror'         => 'Tidak dapat mencipta akaun: $1',
'nocookiesnew'               => 'Akaun anda telah dibuka, tetapi anda belum log masuk. {{SITENAME}} menggunakan kuki untuk mencatat status log masuk pengguna. Sila aktifkan sokongan kuki pada pelayar anda, kemudian log masuk dengan nama pengguna dan kata laluan baru anda.',
'nocookieslogin'             => "{{SITENAME}} menggunakan ''cookies'' untuk mencatat status log masuk pengguna. Sila aktifkan sokongan ''cookies'' pada pelayar anda dan cuba lagi.",
'noname'                     => 'Nama pengguna tidak sah.',
'loginsuccesstitle'          => 'Berjaya log masuk',
'loginsuccess'               => "'''Anda telah log masuk ke dalam {{SITENAME}} sebagai \"\$1\".'''",
'nosuchuser'                 => 'Pengguna "$1" tidak wujud. Nama pengguna adalah peka huruf besar. Sila semak ejaan anda, atau anda boleh [[Special:UserLogin/signup|membuka akaun baru]].',
'nosuchusershort'            => 'Pengguna "<nowiki>$1</nowiki>" tidak wujud. Sila semak ejaan anda.',
'nouserspecified'            => 'Sila nyatakan nama pengguna.',
'login-userblocked'          => 'Pengguna ini disekat. Log masuk tidak dibenarkan.',
'wrongpassword'              => 'Kata laluan yang dimasukkan adalah salah. Sila cuba lagi.',
'wrongpasswordempty'         => 'Kata laluan yang dimasukkan adalah kosong. Sila cuba lagi.',
'passwordtooshort'           => 'Kata laluan mestilah sekurang-kurangnya {{PLURAL:$1|1 aksara|$1 aksara}}.',
'password-name-match'        => 'Kata laluan anda mesti berbeza daripada nama pengguna anda.',
'mailmypassword'             => 'E-melkan kata laluan baru',
'passwordremindertitle'      => 'Pengingat kata laluan daripada {{SITENAME}}',
'passwordremindertext'       => 'Seseorang (mungkin anda, dari alamat IP $1) telah meminta kata laluan baru untuk {{SITENAME}} ($4). Kata laluan sementara baru untuk pengguna "$2" ialah "$3". Untuk menamatkan prosedur ini, anda perlu log masuk dan tetapkan kata laluan yang baru dengan segera. Kata laluan sementara anda akan luput dalam $5 hari.

Jika anda tidak membuat permintaan ini, atau anda telah pun mengingati semula kata laluan anda dan tidak mahu menukarnya, anda boleh mengabaikan pesanan ini dan terus menggunakan kata laluan yang sedia ada.',
'noemail'                    => 'Tiada alamat e-mel direkodkan bagi pengguna "$1".',
'noemailcreate'              => 'Anda perlu memberikan alamat e-mel sah',
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
'acct_creation_throttle_hit' => 'Pengunjung wiki ini yang menggunakan alamat IP anda telah membuka sebanyak $1 akaun semenjak sehari lepas, iaitu merupakan had maksimum yang dibenarkan dalam tempoh tersebut.
Akibatknya, pengunjung dari alamat IP ini tidak boleh membuka akaun lagi pada masa sekarang.',
'emailauthenticated'         => 'Alamat e-mel anda telah disahkan pada $2, $3.',
'emailnotauthenticated'      => 'Alamat e-mel anda belum disahkan. Oleh itu,
e-mel bagi ciri-ciri berikut tidak boleh dikirim.',
'noemailprefs'               => 'Anda perlu menetapkan alamat e-mel terlebih dahulu untuk menggunakan ciri-ciri ini.',
'emailconfirmlink'           => 'Sahkan alamat e-mel anda.',
'invalidemailaddress'        => 'Alamat e-mel tersebut tidak boleh diterima kerana ia tidak sah. Sila masukkan alamat e-mel yang betul atau kosongkan sahaja ruangan tersebut.',
'accountcreated'             => 'Akaun dibuka',
'accountcreatedtext'         => 'Akaun pengguna bagi $1 telah dibuka.',
'createaccount-title'        => 'Pembukaan akaun {{SITENAME}}',
'createaccount-text'         => 'Seseorang telah membuka akaun untuk
alamat e-mel anda di {{SITENAME}} ($4) dengan nama "$2" dan kata laluan "$3".
Anda boleh log masuk dan tukar kata laluan anda sekarang.

Sila abaikan mesej ini jika anda tidak meminta untuk membuka akaun tersebut.',
'usernamehasherror'          => 'Nama pengguna tidak boleh memiliki aksara cincangan',
'login-throttled'            => 'Anda telah mencuba log masuk berulang kali.
Sila tunggu sebentar dan cuba lagi.',
'loginlanguagelabel'         => 'Bahasa: $1',
'suspicious-userlogout'      => 'Permintaan anda untuk log keluar ditolak kerana ia kelihatan seperti dihantar oleh pelayar rosak atau proksi pengagregatan.',

# JavaScript password checks
'password-strength'            => 'Anggaran kekebalan kata laluan: $1',
'password-strength-bad'        => 'LEMAH',
'password-strength-mediocre'   => 'sederhana',
'password-strength-acceptable' => 'memuaskan',
'password-strength-good'       => 'baik',
'password-retype'              => 'Ulangi kata laluan',
'password-retype-mismatch'     => 'Kata laluan tidak sama',

# Password reset dialog
'resetpass'                 => 'Tukar kata laluan',
'resetpass_announce'        => 'Anda sedang log masuk dengan kata laluan sementara. Untuk log masuk dengan sempurna, sila tetapkan kata laluan baru di sini:',
'resetpass_text'            => '<!-- Tambah teks di sini -->',
'resetpass_header'          => 'Tukar kata laluan',
'oldpassword'               => 'Kata laluan lama:',
'newpassword'               => 'Kata laluan baru:',
'retypenew'                 => 'Ulangi kata laluan baru:',
'resetpass_submit'          => 'Tetapkan kata laluan dan log masuk',
'resetpass_success'         => 'Kata laluan anda ditukar dengan jayanya! Sila tunggu...',
'resetpass_forbidden'       => 'Kata laluan tidak boleh ditukar',
'resetpass-no-info'         => 'Anda hendaklah log masuk terlebih dahulu untuk mencapai laman ini secara terus.',
'resetpass-submit-loggedin' => 'Tukar kata laluan',
'resetpass-submit-cancel'   => 'Batal',
'resetpass-wrong-oldpass'   => 'Kata laluan sementara atau semasa tidak sah.
Anda mungkin telah pun berjaya menukar kata laluan anda atau meminta kata laluan sementara yang baru.',
'resetpass-temp-password'   => 'Kata laluan sementara:',

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
'math_sample'     => 'Masukkan rumus di sini',
'math_tip'        => 'Rumus matematik (LaTeX)',
'nowiki_sample'   => 'Masukkan teks tak berformat di sini',
'nowiki_tip'      => 'Abaikan pemformatan wiki',
'image_sample'    => 'Contoh.jpg',
'image_tip'       => 'Imej terbenam',
'media_sample'    => 'Contoh.ogg',
'media_tip'       => 'Pautan fail media',
'sig_tip'         => 'Tandatangan dengan cap waktu',
'hr_tip'          => 'Garis melintang (gunakan dengan hemat)',

# Edit pages
'summary'                          => 'Ringkasan:',
'subject'                          => 'Tajuk:',
'minoredit'                        => 'Ini adalah suntingan kecil',
'watchthis'                        => 'Pantau laman ini',
'savearticle'                      => 'Simpan',
'preview'                          => 'Pratonton',
'showpreview'                      => 'Pratonton',
'showlivepreview'                  => 'Pratonton langsung',
'showdiff'                         => 'Lihat perubahan',
'anoneditwarning'                  => "'''Amaran:''' Anda tidak log masuk. Alamat IP anda akan direkodkan dalam sejarah suntingan laman ini.",
'anonpreviewwarning'               => "''Anda belum log masuk. Jika anda menyimpan laman ini, alamat IP anda akan direkodkan dalam sejarah penyuntingan laman ini.''",
'missingsummary'                   => "'''Peringatan:''' Anda tidak menyatakan ringkasan suntingan. Klik '''Simpan''' sekali lagi untuk menyimpan suntingan ini tanpa ringkasan.",
'missingcommenttext'               => 'Sila masukkan komen dalam ruangan di bawah.',
'missingcommentheader'             => "'''Peringatan:''' Anda tidak menyatakan tajuk bagi komen ini. Klik '''{{int:savearticle}}''' sekali lagi untuk menyimpan suntingan ini tanpa tajuk.",
'summary-preview'                  => 'Pratonton ringkasan:',
'subject-preview'                  => 'Pratonton tajuk:',
'blockedtitle'                     => 'Pengguna disekat',
'blockedtext'                      => '\'\'\'Nama pengguna atau alamat IP anda telah disekat.\'\'\'

Sekatan ini dilakukan oleh $1 dengan sebab \'\'$2\'\'.

* Mula: $8
* Tamat: $6
* Pengguna sasaran: $7

Sila hubungi $1 atau [[{{MediaWiki:Grouppage-sysop}}|pentadbir]] yang lain untuk untuk berunding mengenai sekatan ini.

Anda tidak boleh menggunakan ciri "e-melkan pengguna ini" kecuali sekiranya anda telah menetapkan alamat e-mel yang sah dalam [[Special:Preferences|keutamaan]] anda dan anda tidak disekat daripada menggunakannya.

Alamat IP semasa anda ialah $3, dan ID sekatan ialah #$5. Sila sertakan maklumat-maklumat ini dalam pertanyaan nanti.',
'autoblockedtext'                  => 'Alamat IP anda telah disekat secara automatik kerana ia digunakan oleh pengguna lain yang disekat oleh $1.
Yang berikut ialah sebab yang dinyatakan:

:\'\'$2\'\'

* Mula: $8
* Tamat: $6
* Pengguna sasaran: $7

Anda boleh menghubungi $1 atau [[{{MediaWiki:Grouppage-sysop}}|pentadbir]] lain untuk berunding mengenai sekatan ini.

Sila ambil perhatian bahawa anda tidak boleh menggunakan ciri "e-melkan pengguna ini" kecuali sekiranya anda telah menetapkan alamat e-mel yang sah dalam [[Special:Preferences|laman keutamaan]] anda dan anda tidak disekat daripada menggunakannya.

Alamat IP semasa anda ialah $3, dan ID sekatan ialah #$5. Sila sertakan maklumat-maklumat ini dalam pertanyaan nanti.',
'blockednoreason'                  => 'tiada sebab diberikan',
'blockedoriginalsource'            => "Sumber bagi '''$1'''
ditunjukkan di bawah:",
'blockededitsource'                => "Teks bagi '''suntingan anda''' terhadap '''$1''' ditunjukkan di bawah:",
'whitelistedittitle'               => 'Log masuk dahulu untuk menyunting',
'whitelistedittext'                => 'Anda hendaklah $1 terlebih dahulu untuk menyunting laman.',
'confirmedittext'                  => 'Anda perlu mengesahkan alamat e-mel anda terlebih dahulu untuk menyunting mana-mana laman. Sila tetapkan dan sahkan alamat e-mel anda melalui [[Special:Preferences|laman keutamaan]].',
'nosuchsectiontitle'               => 'Tidak ada bahagian ini',
'nosuchsectiontext'                => 'Anda cuba untuk menyunting bahagian yang tidak wujud.
Ia mungkin telah dialih atau dihapus semasa anda melihat laman ini.',
'loginreqtitle'                    => 'Log masuk diperlukan',
'loginreqlink'                     => 'log masuk',
'loginreqpagetext'                 => 'Anda harus $1 untuk dapat melihat laman yang lain.',
'accmailtitle'                     => 'Kata laluan dikirim.',
'accmailtext'                      => "Kata laluan rawak yang dijanakan untuk [[User talk:$1|$1]] telah dikirim kepada $2.

Kata laluan bagi akaun baru ini boleh ditukar di laman ''[[Special:ChangePassword|tukar kata laluan]]'' setelah pengguna tersebut melog masuk.",
'newarticle'                       => '(Baru)',
'newarticletext'                   => "Anda telah mengikuti pautan ke laman yang belum wujud.
Untuk mencipta laman ini, sila taip dalam kotak di bawah
(lihat [[{{MediaWiki:Helppage}}|laman bantuan]] untuk maklumat lanjut).
Jika anda tiba di sini secara tak sengaja, hanya klik butang '''back''' pada pelayar anda.",
'anontalkpagetext'                 => "----''Ini ialah laman perbincangan bagi pengguna tanpa nama yang belum membuka akaun atau tidak log masuk.
Oleh itu kami terpaksa menggunakan alamat IP untuk mengenal pasti pengguna tersebut. Alamat IP ini boleh dikongsi oleh ramai pengguna.
Sekiranya anda adalah seorang pengguna tanpa nama dan berasa bahawa komen yang tidak kena mengena telah ditujukan kepada anda, sila [[Special:UserLogin/signup|buka akaun baru]] atau [[Special:UserLogin|log masuk]] untuk mengelakkan sebarang kekeliruan dengan pengguna tanpa nama yang lain.''",
'noarticletext'                    => 'Tiada teks dalam laman ini pada masa sekarang. Anda boleh [[Special:Search/{{PAGENAME}}|mencari tajuk bagi laman ini]] dalam laman-laman lain, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} menggelintar log-log yang berkaitan], atau [{{fullurl:{{FULLPAGENAME}}|action=edit}} menyunting laman ini]</span>.',
'noarticletext-nopermission'       => 'Tiada teks dalam laman ini ketika ini.
Anda boleh [[Special:Search/{{PAGENAME}}|mencari tajuk laman ini]] dalam laman lain,
atau <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} menggelintar log yang berkaitan]</span>.',
'userpage-userdoesnotexist'        => 'Akaun pengguna "$1" tidak berdaftar. Sila pastikan sama ada anda mahu mencipta/menyunting laman ini.',
'userpage-userdoesnotexist-view'   => 'Akaun pengguna "$1" tidak berdaftar.',
'blocked-notice-logextract'        => 'Pengguna ini sedang disekat.
Masukan log sekatan terakhir disediakan di bawah sebagai rujukan:',
'clearyourcache'                   => "'''Catatan: Selepas menyimpan laman ini, anda mungkin perlu membersihkan cache pelayar web anda terlebih dahulu untuk mengenakan perubahan.'''
'''Mozilla/Firefox/Safari:''' tahan ''Shift'' ketika mengklik ''Reload'' atau tekan ''Ctrl+F5'' atau tekan ''Ctrl+R'' (''Command+R'' dalam komputer Macintosh).
'''Konqueror:''' klik butang ''Reload'' atau tekan ''F5''.
'''Opera:''' bersihkan cache melalui menu ''Tools → Preferences''.
'''Internet Explorer:''' tahan ''Ctrl'' ketika mengklik ''Refresh'' atau tekan ''Ctrl+F5''.",
'usercssyoucanpreview'             => "'''Petua:''' Gunakan butang \"{{int:showpreview}}\" untuk menguji CSS baru anda sebelum menyimpan.",
'userjsyoucanpreview'              => "'''Petua:''' Gunakan butang \"{{int:showpreview}}\" untuk menguji JavaScript baru anda sebelum menyimpan.",
'usercsspreview'                   => "'''Ingat bahawa anda hanya sedang melihat pratonton CSS peribadi anda. Laman ini belum lagi disimpan!'''",
'userjspreview'                    => "'''Ingat bahawa anda hanya menguji/melihat pratonton JavaScript anda, ia belum lagi disimpan!'''",
'userinvalidcssjstitle'            => "'''Amaran:''' Rupa \"\$1\" tidak wujud. Ingat bahawa laman tempahan .css dan .js menggunakan tajuk berhuruf kecil, contohnya {{ns:user}}:Anu/monobook.css tidak sama dengan {{ns:user}}:Anu/Monobook.css.",
'updated'                          => '(Dikemaskinikan)',
'note'                             => "'''Catatan:'''",
'previewnote'                      => "'''Ini hanyalah pratonton. Perubahan masih belum disimpan!'''",
'previewconflict'                  => 'Paparan ini merupakan teks di bahagian atas dalam kotak sunting teks. Teks ini akan disimpan sekiranya anda memilih berbuat demikian.',
'session_fail_preview'             => "'''Kami tidak dapat memproses suntingan anda kerana kehilangan data sesi. Sila cuba lagi. Jika masalah ini berlanjutan, [[Special:UserLogout|log keluar]] dahulu, kemudian log masuk sekali lagi.'''",
'session_fail_preview_html'        => "'''Kami tidak dapat memproses suntingan anda kerana kehilangan data sesi.'''

''Kerana {{SITENAME}} membenarkan HTML mentah, pratonton dimatikan sebagai perlindungan daripada serangan JavaScript.''

'''Jika ini adalah penyuntingan yang sah, sila cuba lagi. Jika masalah ini berlanjutan, [[Special:UserLogout|log keluar]] dahulu, kemudian log masuk sekali lagi.'''",
'token_suffix_mismatch'            => "'''Suntingan anda telah ditolak kerana pelanggan anda memusnahkan aksara tanda baca
dalam token suntingan. Suntingan tersebut telah ditolak untuk menghalang kerosakan teks laman.
Hal ini kadangkala berlaku apabila anda menggunakan khidmat proksi tanpa nama berdasarkan web yang bermasalah.'''",
'editing'                          => 'Menyunting $1',
'editingsection'                   => 'Menyunting $1 (bahagian)',
'editingcomment'                   => 'Menyunting $1 (bahagian baru)',
'editconflict'                     => 'Percanggahan penyuntingan: $1',
'explainconflict'                  => "Pengguna lain telah menyunting laman ini ketika anda sedang menyuntingnya.
Kawasan teks di atas mengandungi teks semasa.
Perubahan anda dipaparkan dalam kawasan teks di bawah.
Anda perlu menggabungkan perubahan anda dengan teks semasa.
'''Hanya''' teks dalam kawasan teks di atas akan disimpan jika anda menekan \"{{int:savearticle}}\".",
'yourtext'                         => 'Teks anda',
'storedversion'                    => 'Versi yang disimpan',
'nonunicodebrowser'                => "'''AMARAN: Pelayar anda tidak mematuhi Unicode. Aksara-aksara bukan ASCII akan dipaparkan dalam kotak sunting sebagai kod perenambelasan.'''",
'editingold'                       => "'''AMARAN: Anda sedang
menyunting sebuah semakan yang sudah ketinggalan zaman.
Jika anda menyimpannya, sebarang perubahan yang dibuat selepas tarikh semakan ini akan hilang.'''",
'yourdiff'                         => 'Perbezaan',
'copyrightwarning'                 => "Sila ambil perhatian bahawa semua sumbangan kepada {{SITENAME}} akan dikeluarkan di bawah $2 (lihat $1 untuk butiran lanjut). Jika anda tidak mahu tulisan anda disunting sewenang-wenangnya oleh orang lain dan diedarkan secara bebas, maka jangan kirim di sini.<br />
Anda juga berjanji bahawa ini adalah hasil kerja tangan anda sendiri, atau disalin daripada domain awam atau mana-mana sumber bebas lain.
'''JANGAN KIRIM KARYA HAK CIPTA ORANG LAIN TANPA KEBENARAN!'''",
'copyrightwarning2'                => "Sila ambil perhatian bahawa semua sumbangan terhadap {{SITENAME}} boleh disunting, diubah, atau dipadam oleh penyumbang lain. Jika anda tidak mahu tulisan anda disunting sewenang-wenangnya, maka jangan kirim di sini.<br />
Anda juga berjanji bahawa ini adalah hasil kerja tangan anda sendiri, atau
disalin daripada domain awam atau mana-mana sumber bebas lain (lihat $1 untuk butiran lanjut).
'''JANGAN KIRIM KARYA HAK CIPTA ORANG LAIN TANPA KEBENARAN!'''",
'longpageerror'                    => "'''RALAT: Panjang teks yang dikirim ialah $1 kilobait,
melebihi had maksimum $2 kilobait. Ia tidak boleh disimpan.'''",
'readonlywarning'                  => "'''AMARAN: Pangkalan data telah dikunci untuk penyenggaraan. Justeru, anda tidak boleh menyimpan suntingan anda pada masa sekarang.
Anda boleh menyalin teks anda ke dalam komputer anda terlebih dahulu dan simpan teks tersebut di sini pada masa akan datang.'''

Yang berikut ialah penjelasan yang diberikan: $1",
'protectedpagewarning'             => "'''Amaran: Laman ini telah dikunci supaya hanya mereka yang mempunyai keistimewaan penyelia boleh menyuntingnya.'''
Masukan log terakhir ditunjukkan di bawah untuk rujukan:",
'semiprotectedpagewarning'         => "'''Nota:''' Laman ini telah dikunci agar hanya pengguna berdaftar sahaja boleh menyuntingnya.
Masukan log terakhir ditunjukkan di bawah untuk rujukan:",
'cascadeprotectedwarning'          => "'''Amaran:''' Laman ini telah dikunci, oleh itu hanya penyelia boleh menyuntingnya. Ini kerana ia termasuk dalam {{PLURAL:$1|laman|laman-laman}} berikut yang dilindungi secara melata:",
'titleprotectedwarning'            => "'''Amaran: Laman ini telah dikunci hingga [[Special:ListGroupRights|hak-hak tertentu]] diperlukan untuk menciptanya.'''
Masukan log terakhir ditunjukkan di bawah untuk rujukan:",
'templatesused'                    => '{{PLURAL:$1|Templat|Templat}} yang digunakan dalam laman ini:',
'templatesusedpreview'             => '{{PLURAL:$1|Templat|Templat}} yang digunakan dalam pratonton ini:',
'templatesusedsection'             => '{{PLURAL:$1|Templat|Templat}} digunakan dalam bahagian ini:',
'template-protected'               => '(dilindungi)',
'template-semiprotected'           => '(dilindungi separa)',
'hiddencategories'                 => 'Laman ini terdapat dalam $1 kategori tersembunyi:',
'edittools'                        => '<!-- Teks di sini akan ditunjukkan bawah borang sunting dan muat naik. -->',
'nocreatetitle'                    => 'Penciptaan laman dihadkan',
'nocreatetext'                     => 'Penciptaan laman baru dihadkan pada {{SITENAME}}.
Anda boleh berundur dan menyunting laman yang sedia ada, atau [[Special:UserLogin|log masuk]].',
'nocreate-loggedin'                => 'Anda tidak mempunyai keizinan untuk mencipta laman baru.',
'sectioneditnotsupported-title'    => 'Suntingan bahagian tidak disokong',
'sectioneditnotsupported-text'     => 'Suntingan bahagian tidak disokong di laman ini.',
'permissionserrors'                => 'Tidak Dibenarkan',
'permissionserrorstext'            => 'Anda tidak mempunyai keizinan untuk berbuat demikian atas {{PLURAL:$1|sebab|sebab-sebab}} berikut:',
'permissionserrorstext-withaction' => 'Anda tidak mempunyai keizinan untuk $2, atas {{PLURAL:$1|sebab|sebab-sebab}} berikut:',
'recreate-moveddeleted-warn'       => "'''Amaran: Anda sedang mencipta semula sebuah laman yang pernah dihapuskan.'''

Anda harus mempertimbangkan perlunya menyunting laman ini.
Untuk rujukan, yang berikut ialah log penghapusan bagi laman ini:",
'moveddeleted-notice'              => 'Laman ini telah dihapuskan.
Log penghapusan bagi laman ini dilampirkan di bawah untuk rujukan.',
'log-fulllog'                      => 'Lihat log lengkap',
'edit-hook-aborted'                => 'Suntingan anda telah dibatalkan oleh penyangkuk. Tiada sebab diberikan.',
'edit-gone-missing'                => 'Laman tersebut telah dihapuskan dan tidak dapat dikemaskinikan.',
'edit-conflict'                    => 'Percanggahan penyuntingan.',
'edit-no-change'                   => 'Suntingan anda diabaikan kerana tiada perubahan dibuat pada teks tersebut.',
'edit-already-exists'              => 'Tidak dapat mencipta laman baru kerana ia telah wujud.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Amaran: Laman ini mengandungi terlalu banyak panggilan fungsi penghurai yang intensif.

Had panggilan ialah $2, sekarang terdapat $1 panggilan.',
'expensive-parserfunction-category'       => 'Laman yang mengandungi terlalu banyak panggilan fungsi penghurai yang intensif',
'post-expand-template-inclusion-warning'  => 'Amaran: Saiz penyertaan templat terlalu besar.
Sesetengah templat tidak akan disertakan.',
'post-expand-template-inclusion-category' => 'Laman-laman yang melebihi had saiz penyertaan templat',
'post-expand-template-argument-warning'   => 'Amaran: Laman ini mengandungi sekurang-kurangnya satu argumen templat yang mempunyai saiz pengembangan yang terlalu besar.
Argumen-argumen ini telah ditinggalkan.',
'post-expand-template-argument-category'  => 'Laman yang mengandungi templat dengan argumen yang tidak lengkap',
'parser-template-loop-warning'            => 'Gelung templat dikesan: [[$1]]',
'parser-template-recursion-depth-warning' => 'Had pengulangan templat dilebihi ($1)',
'language-converter-depth-warning'        => 'Had kedalaman penukar bahasa dilepasi ($1)',

# "Undo" feature
'undo-success' => 'Suntingan ini boleh dibatalkan. Sila semak perbandingan di bawah untuk mengesahkan bahawa anda betul-betul mahu melakukan tindakan ini, kemudian simpan perubahan tersebut.',
'undo-failure' => 'Suntingan tersebut tidak boleh dibatalkan kerana terdapat suntingan pertengahan yang bercanggah.',
'undo-norev'   => 'Suntingan tersebut tidak boleh dibatalkan kerana tidak wujud atau telah dihapuskan.',
'undo-summary' => 'Membatalkan semakan $1 oleh [[Special:Contributions/$2|$2]] ([[User talk:$2|Perbincangan]])',

# Account creation failure
'cantcreateaccounttitle' => 'Akaun tidak dapat dibuka',
'cantcreateaccount-text' => "Pembukaan akaun daripada alamat IP ini (<b>$1</b>) telah disekat oleh [[User:$3|$3]].

Sebab yang diberikan oleh $3 ialah ''$2''",

# History pages
'viewpagelogs'           => 'Lihat log bagi laman ini',
'nohistory'              => 'Tiada sejarah suntingan bagi laman ini.',
'currentrev'             => 'Semakan semasa',
'currentrev-asof'        => 'Semakan semasa pada $1',
'revisionasof'           => 'Semakan pada $1',
'revision-info'          => 'Semakan pada $1 oleh $2',
'previousrevision'       => '←Semakan sebelumnya',
'nextrevision'           => 'Semakan berikutnya→',
'currentrevisionlink'    => 'Semakan semasa',
'cur'                    => 'kini',
'next'                   => 'berikutnya',
'last'                   => 'akhir',
'page_first'             => 'awal',
'page_last'              => 'akhir',
'histlegend'             => "Pemilihan perbezaan: tandakan butang radio bagi versi-versi yang ingin dibandingkan dan tekan butang ''enter'' atau butang di bawah.<br />
Petunjuk: (kini) = perbezaan dengan versi terkini,
(akhir) = perbezaan dengan versi sebelumnya, K = suntingan kecil.",
'history-fieldset-title' => 'Lihat sejarah',
'history-show-deleted'   => 'Dihapuskan sahaja',
'histfirst'              => 'Terawal',
'histlast'               => 'Terkini',
'historysize'            => '($1 bait)',
'historyempty'           => '(kosong)',

# Revision feed
'history-feed-title'          => 'Sejarah semakan',
'history-feed-description'    => 'Sejarah semakan bagi laman ini',
'history-feed-item-nocomment' => '$1 pada $2',
'history-feed-empty'          => 'Laman yang diminta tidak wujud.
Mungkin ia telah dihapuskan atau namanya telah ditukar.
Cuba [[Special:Search|cari]] laman lain yang mungkin berkaitan.',

# Revision deletion
'rev-deleted-comment'         => '(komen dibuang)',
'rev-deleted-user'            => '(nama pengguna dibuang)',
'rev-deleted-event'           => '(entri dibuang)',
'rev-deleted-user-contribs'   => '[nama pengguna atau alamat IP dibuang - suntingan disembunyikan daripada sumbangan]',
'rev-deleted-text-permission' => "Semakan laman ini telah '''dihapuskan'''.
Perinciannya mungkin ada di dalam [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log penghapusan].",
'rev-deleted-text-unhide'     => "Semakan laman ini telah '''dihapuskan'''.
Butiran lanjut mungkin boleh didapati dalam [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log penghapusan].
Sebagai seorang pentadbir anda masih boleh [$1 melihat semakan ini] jika anda ingin teruskan.",
'rev-suppressed-text-unhide'  => "Semakan laman ini telah '''diselindungkan'''.<br />Butiran lanjut mungkin boleh didapati dalam [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} log penyelindungan].<br />Sebagai pentadbir anda masih boleh [$1 melihat semakan ini] jika anda ingin.",
'rev-deleted-text-view'       => "Semakan laman ini telah '''dihapuskan'''.
Sebagai seorang pentadbir anda boleh melihatnya; butiran lanjut mungkin boleh didapati dalam [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log penghapusan].",
'rev-suppressed-text-view'    => "Semakan bagi laman ini telah '''diselindungkan'''.
Sebagai seorang pentadbir, anda boleh memaparkannya; butiran lanjut mungkin ada di [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} log penyelindungan].",
'rev-deleted-no-diff'         => "Anda tidak boleh melihat perbezaan ini kerana salah satu daripada semakannya telah '''dihapuskan'''.
Mungkin terdapat butiran lanjut di dalam [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log penghapusan].",
'rev-suppressed-no-diff'      => "Anda tidak boleh melihat perbezaan ini kerana salah satu semakannya telah '''dihapuskan'''.",
'rev-deleted-unhide-diff'     => "Salah satu semakan laman ini telah '''dihapuskan'''.
Butiran lanjut mungkin boleh didapati dalam [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log penghapusan].
Sebagai seorang pentadbir anda masih boleh [$1 melihat semakan ini] jika anda ingin teruskan.",
'rev-suppressed-unhide-diff'  => "Salah satu semakan perbezaan ini telah '''diselindungkan'''.
Butiran lanjut mungkin boleh didapati dalam [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} log penyelindungan].
Sebagai pentadbir anda masih boleh [$1 melihat perbezaan ini] jika anda ingin teruskan.",
'rev-deleted-diff-view'       => "Salah satu semakan perbezaan ini telah '''dihapuskan'''.
Sebagai pentadbir anda boleh melihat perbezaan ini; mungkin terdapat perincian pada [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log penghapusan].",
'rev-suppressed-diff-view'    => "Salah satu semakan perbezaan ini telah '''dipendam'''.
Sebagai pentadbir anda boleh melihat perbezaan ini; mungkin terdapat perincian pada [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log penyelindungan].",
'rev-delundel'                => 'tunjuk/sembunyi',
'rev-showdeleted'             => 'tunjuk',
'revisiondelete'              => 'Hapus/nyahhapus semakan',
'revdelete-nooldid-title'     => 'Tiada semakan sasaran',
'revdelete-nooldid-text'      => 'Anda tidak menyatakan semakan sasaran.',
'revdelete-nologtype-title'   => 'Jenis log tidak diberi',
'revdelete-nologtype-text'    => 'Anda tidak menyatakan jenis log untuk tindakan ini.',
'revdelete-nologid-title'     => 'Entri log tidak sah',
'revdelete-nologid-text'      => 'Anda tidak menyatakan peristiwa log sasaran perkara untuk melakukan fungsi ini atau entri ynag dinyatakan tidak wujud.',
'revdelete-no-file'           => 'Fail yang dinyatakan tidak wujud.',
'revdelete-show-file-confirm' => 'Anda pasti anda mahu paparkan semakan yang telah dihapuskan bagi fail "<nowiki>$1</nowiki>" dari $2 pada $3?',
'revdelete-show-file-submit'  => 'Ya',
'revdelete-selected'          => "'''{{PLURAL:$2|Versi|Versi-versi}} '''$1''' yang dipilih:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Peristiwa|Peristiwa-peristiwa}} log yang dipilih:'''",
'revdelete-text'              => "'''Semakan dan peristiwa yang dihapuskan akan tetap muncul dalam sejarah laman dan log, tetapi kandungannya tidak boleh diakses awam.'''
Pentadbir {{SITENAME}} boleh melihat kandungan tersebut dan menyahhapuskannya semula melalui laman ini melainkan mempunyai batasan.",
'revdelete-confirm'           => 'Sila sahkan bahawa anda bertujuan melakukan ini, bahawa anda faham akibatnya, dan anda melakukannya menurut [[{{MediaWiki:Policy-url}}| polisi]].',
'revdelete-suppress-text'     => "Pembatasan ini '''hanya''' untuk digunakan dalam kes-kes berikut:
* Maklumat peribadi tidak sesuai
*: ''alamat rumah dan nombor telefon, nombor keselamatan sosial, dsbg.''",
'revdelete-legend'            => 'Tetapkan batasan:',
'revdelete-hide-text'         => 'Sembunyikan teks semakan',
'revdelete-hide-image'        => 'Sembunyikan kandungan fail',
'revdelete-hide-name'         => 'Sembunyikan tindakan dan sasaran',
'revdelete-hide-comment'      => 'Sembunyikan komen suntingan',
'revdelete-hide-user'         => 'Sembunyikan nama pengguna/IP penyunting',
'revdelete-hide-restricted'   => 'Sekat data daripada penyelia dan pengguna lain',
'revdelete-radio-same'        => '(jangan tukar)',
'revdelete-radio-set'         => 'Ya',
'revdelete-radio-unset'       => 'Tidak',
'revdelete-suppress'          => 'Sekat data daripada semua pengguna, termasuk penyelia',
'revdelete-unsuppress'        => 'Buang batasan pada semakan yang dipulihkan',
'revdelete-log'               => 'Sebab:',
'revdelete-submit'            => 'Kenakan ke atas {{PLURAL:$1|versi|versi}} yang dipilih',
'revdelete-logentry'          => 'menukar kebolehnampakan semakan [[$1]]',
'logdelete-logentry'          => 'menukar kebolehnampakan peristiwa bagi [[$1]]',
'revdelete-success'           => "'''Kebolehnampakan semakan berjaya ditetapkan.'''",
'revdelete-failure'           => "'''Keterlihatan semakan tidak dapat dikemaskini:'''
$1",
'logdelete-success'           => 'Kebolehnampakan peristiwa ditetapkan.',
'logdelete-failure'           => "'''Log nampak tidak dapat diset:'''
$1",
'revdel-restore'              => 'Tukar kebolehnampakan',
'revdel-restore-deleted'      => 'semakan dihapuskan',
'revdel-restore-visible'      => 'semakan nampak',
'pagehist'                    => 'Sejarah laman',
'deletedhist'                 => 'Sejarah yang dihapuskan',
'revdelete-content'           => 'kandungan',
'revdelete-summary'           => 'ringkasan',
'revdelete-uname'             => 'nama pengguna',
'revdelete-restricted'        => 'mengenakan sekatan pada penyelia',
'revdelete-unrestricted'      => 'menarik sekatan daripada penyelia',
'revdelete-hid'               => 'menyembunyikan $1',
'revdelete-unhid'             => 'memunculkan $1',
'revdelete-log-message'       => '$1 bagi {{PLURAL:$2|sebuah|$2 buah}} semakan',
'logdelete-log-message'       => '$1 bagi $2 peristiwa',
'revdelete-hide-current'      => 'Ralat menyembunyikan item bertarikh $2, $1: ini adalah versi semasa.
Ia tidak dapat disembunyikan.',
'revdelete-show-no-access'    => 'Ralat menunjukkan item bertarikh $2, $1: item ini telah ditanda "larangan".
Anda tidak memiliki capaian padanya.',
'revdelete-modify-no-access'  => 'Ralat menyunting item bertarikh $2, $1: item ini telah ditanda "larangan".
Anda tidak memiliki capaian padanya.',
'revdelete-modify-missing'    => 'Ralat menyunting item ID $1: ia tiada dalam pangkalan data!',
'revdelete-no-change'         => "'''Amaran:''' item bertarikh $2, $1 telah mempunyai aturan penglihatan yang diminta.",
'revdelete-concurrent-change' => 'Ralat ketika mengubahsuai item bertarikh $2, $1: kelihatan statusnya telah diubah oleh orang lain ketika anda cuba untuk mengubahsuainya.
Mohon semak log.',
'revdelete-only-restricted'   => 'Ralat menyembunyikan item bertarikh $2, $1: anda tidak boleh menyekat item-item dari pandangan pentadbir-pentadbir tanpa memilih juga salah satu pilihan pandangan lain.',
'revdelete-reason-dropdown'   => '*Sebab penghapusan biasa
** Pencabulan hak cipta
** Maklumat peribadi tidak sesuai
** Maklumat berpotensi fitnah',
'revdelete-otherreason'       => 'Sebab lain/tambahan:',
'revdelete-reasonotherlist'   => 'Sebab lain',
'revdelete-edit-reasonlist'   => 'Ubah sebab-sebab hapus',
'revdelete-offender'          => 'Pengarang semakan:',

# Suppression log
'suppressionlog'     => 'Log penahanan',
'suppressionlogtext' => 'Yang berikut ialah senarai penghapusan dan sekatan yang membabitkan kandungan yang terselindung daripada penyelia.
Sila lihat juga [[Special:IPBlockList|senarai sekatan IP]] yang sedang berkuatkuasa.',

# Revision move
'moverevlogentry'              => 'memindahkan $3 {{PLURAL:$3|semakan|semakan}} dari $1 ke $2',
'revisionmove'                 => 'Pindah semakan dari "$1"',
'revmove-explain'              => 'Semakan-semakan berikut akan dipindahkan dari $1 ke laman sasaran yang telah ditentukan. Jika laman sasaran tersebut tidak wujud, laman baru akan dicipta. Sebaliknya, semakan-semakan ini akan digabungkan ke dalam sejarah laman tersebut.',
'revmove-legend'               => 'Tetapkan laman sasaran dan ringkasan',
'revmove-submit'               => 'Pindah semakan ke laman yang dipilih',
'revisionmoveselectedversions' => 'Pindah semakan-semakan yang dipilih',
'revmove-reasonfield'          => 'Sebab:',
'revmove-titlefield'           => 'Laman sasaran:',
'revmove-badparam-title'       => 'Parameter buruk',
'revmove-badparam'             => 'Permintaan anda mengandungi parameter tidak sah atau tidak lengkap. Sila kembali ke laman sebelumnya dan cuba lagi.',
'revmove-norevisions-title'    => 'Tiada semakan sasaran',
'revmove-norevisions'          => 'Anda tidak menentukan mana-mana semakan atau semakan yang telah ditentukan tidak wujud.',
'revmove-nullmove-title'       => 'Tajuk tidak sah',
'revmove-nullmove'             => 'Laman sasaran tidak boleh sama dengan laman sumber.
Kembali ke laman sebelumnya dan pilih nama yang berbeza daripada "[[$1]]".',
'revmove-success-existing'     => '1 {{PLURAL:$1|semakan|semakan}} telah dipindahkan dari ke laman [[$2]] ke laman wujud [[$3]].',
'revmove-success-created'      => '1 {{PLURAL:$1|semakan|semakan}} telah dipindahkan dari ke laman [[$2]] ke laman baru [[$3]].',

# History merging
'mergehistory'                     => 'Gabungkan sejarah laman',
'mergehistory-header'              => "Anda boleh menggabungkan semua semakan dalam sejarah bagi sesebuah laman sumber ke dalam laman lain.
Sila pastikan bahawa perubahan ini akan mengekalkan kesinambungan sejarah laman.

'''Setidak-tidaknya semakan semasa bagi laman sumber akan ditinggalkan.'''",
'mergehistory-box'                 => 'Gabungkan semakan bagi dua laman:',
'mergehistory-from'                => 'Laman sumber:',
'mergehistory-into'                => 'Laman destinasi:',
'mergehistory-list'                => 'Sejarah suntingan yang boleh digabungkan',
'mergehistory-merge'               => 'Semakan-semakan bagi [[:$1]] yang berikut boleh digabungkan ke dalam [[:$2]]. Gunakan lajur butang radio sekiranya anda hanya mahu menggabungkan semakan-semakan yang dibuat pada dan sebelum waktu yang ditetapkan. Sila ambil perhatian bahawa penggunaan pautan-pautan navigasi akan mengeset semula lajur ini.',
'mergehistory-go'                  => 'Tunjukkan suntingan yang boleh digabungkan',
'mergehistory-submit'              => 'Gabungkan semakan',
'mergehistory-empty'               => 'Tiada semakan yang boleh digabungkan',
'mergehistory-success'             => '$3 semakan bagi [[:$1]] telah digabungkan ke dalam [[:$2]].',
'mergehistory-fail'                => 'Gagal melaksanakan penggabungan sejarah, sila semak semula laman tersebut dan parameter waktu.',
'mergehistory-no-source'           => 'Laman sumber $1 tidak wujud.',
'mergehistory-no-destination'      => 'Laman destinasi $1 tidak wujud.',
'mergehistory-invalid-source'      => 'Laman sumber mestilah merupakan tajuk yang sah.',
'mergehistory-invalid-destination' => 'Laman destinasi mestilah merupakan tajuk yang sah.',
'mergehistory-autocomment'         => 'Menggabungkan [[:$1]] dengan [[:$2]]',
'mergehistory-comment'             => 'Menggabungkan [[:$1]] dengan [[:$2]]: $3',
'mergehistory-same-destination'    => 'Laman sasaran tidak boleh sama dengan laman sumber',
'mergehistory-reason'              => 'Alasan:',

# Merge log
'mergelog'           => 'Log penggabungan',
'pagemerge-logentry' => 'menggabungkan [[$1]] ke dalam [[$2]] (semakan sehingga $3)',
'revertmerge'        => 'Batalkan',
'mergelogpagetext'   => 'Yang berikut ialah senarai terkini bagi penggabungan sejarah sesebuah laman ke dalam lamana yang lain.',

# Diffs
'history-title'            => 'Sejarah semakan bagi "$1"',
'difference'               => '(Perbezaan antara semakan)',
'difference-multipage'     => '(Perbezaan antara laman)',
'lineno'                   => 'Baris $1:',
'compareselectedversions'  => 'Bandingkan versi-versi yang dipilih',
'showhideselectedversions' => 'Tunjuk/sorok versi yang dipilih',
'editundo'                 => 'batal',
'diff-multi'               => '($1 {{PLURAL:$1|semakan pertengahan|semakan pertengahan}} oleh $2 {{PLURAL:$2|pengguna|pengguna}} tidak dipaparkan)',
'diff-multi-manyusers'     => '($1 {{PLURAL:$1|semakan pertengahan|semakan pertengahan}} oleh lebih daripada $2 {{PLURAL:$2|pengguna|pengguna}} tidak dipaparkan)',

# Search results
'searchresults'                    => 'Keputusan carian',
'searchresults-title'              => 'Keputusan carian "$1"',
'searchresulttext'                 => 'Untuk maklumat lanjut tentang carian dalam {{SITENAME}}, sila lihat [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Anda mencari \'\'\'[[$1]]\'\'\' ([[Special:Prefixindex/$1|semua laman dengan awalan "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|semua laman yang mengandungi pautan ke "$1"]])',
'searchsubtitleinvalid'            => 'Untuk pertanyaan "$1"',
'toomanymatches'                   => 'Terlalu banyak padanan dipulangkan, sila cuba pertanyaan lain',
'titlematches'                     => 'Padanan tajuk laman',
'notitlematches'                   => 'Tiada tajuk laman yang sepadan',
'textmatches'                      => 'Padanan teks laman',
'notextmatches'                    => 'Tiada teks laman yang sepadan',
'prevn'                            => '{{PLURAL:$1|$1 sebelumnya}}',
'nextn'                            => '{{PLURAL:$1|$1 berikutnya}}',
'prevn-title'                      => '$1 hasil sebelumnya',
'nextn-title'                      => '$1 hasil berikutnya',
'shown-title'                      => 'Papar $1 hasil setiap laman',
'viewprevnext'                     => 'Lihat ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Pilihan gelintar',
'searchmenu-exists'                => "* Laman '''[[$1]]'''",
'searchmenu-new'                   => "'''Cipta laman \"[[:\$1]]\" di wiki ini!'''",
'searchhelp-url'                   => 'Help:Kandungan',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Senarai laman dengan awalan ini]]',
'searchprofile-articles'           => 'Laman kandungan',
'searchprofile-project'            => 'Laman bantuan dan projek',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Semua',
'searchprofile-advanced'           => 'Maju',
'searchprofile-articles-tooltip'   => 'Cari dalam $1',
'searchprofile-project-tooltip'    => 'Cari dalam $1',
'searchprofile-images-tooltip'     => 'Cari fail',
'searchprofile-everything-tooltip' => 'Gelintar semua kandungan (termasuk laman perbincangan)',
'searchprofile-advanced-tooltip'   => 'Gelintar ruang nama tempahan',
'search-result-size'               => '$1 ({{PLURAL:$2|$2 patah perkataan}})',
'search-result-category-size'      => '$1 {{PLURAL:$1|ahli|ahli}} ($2 {{PLURAL:$2|subkategori|subkategori}}, $3 {{PLURAL:$3|fail|fail}})',
'search-result-score'              => 'Kaitan: $1%',
'search-redirect'                  => '(pelencongan $1)',
'search-section'                   => '(bahagian $1)',
'search-suggest'                   => 'Maksud anda, $1?',
'search-interwiki-caption'         => 'Projek-projek lain',
'search-interwiki-default'         => 'Keputusan daripada $1:',
'search-interwiki-more'            => '(lagi)',
'search-mwsuggest-enabled'         => 'berserta cadangan',
'search-mwsuggest-disabled'        => 'tiada cadangan',
'search-relatedarticle'            => 'Berkaitan',
'mwsuggest-disable'                => 'Matikan ciri cadangan AJAX',
'searcheverything-enable'          => 'Gelintar semua ruang nama',
'searchrelated'                    => 'berkaitan',
'searchall'                        => 'semua',
'showingresults'                   => "Yang berikut ialah '''$1''' hasil bermula daripada yang {{PLURAL:$2|pertama|ke-'''$2'''}}.",
'showingresultsnum'                => "Yang berikut ialah '''$3''' hasil bermula daripada yang {{PLURAL:$2|pertama|ke-'''$2'''}}.",
'showingresultsheader'             => "{{PLURAL:$5|Keputusan '''$1''' daripada '''$3'''|Keputusan '''$1 - $2''' daripada '''$3'''}} untuk '''$4'''",
'nonefound'                        => "'''Catatan''': Ketika lalai, hanya sesetengah ruang nama digelintar.
Cuba berikan awalan ''all:'' untuk menggelintar semua kandungan (termasuk laman perbincangan, templat, dan lain-lain), atau gunakan ruang nama yang dikehendaki sebagai awalan.",
'search-nonefound'                 => 'Tiada hasil yang sepadan dengan pertanyaan tersebut.',
'powersearch'                      => 'Cari',
'powersearch-legend'               => 'Gelintar maju',
'powersearch-ns'                   => 'Gelintar ruang nama:',
'powersearch-redir'                => 'Termasuk lencongan',
'powersearch-field'                => 'Cari',
'powersearch-togglelabel'          => 'Semak:',
'powersearch-toggleall'            => 'Semua',
'powersearch-togglenone'           => 'Tiada',
'search-external'                  => 'Carian luar',
'searchdisabled'                   => 'Ciri pencarian dalam {{SITENAME}} dimatikan. Anda boleh mencari melalui Google. Sila ambil perhatian bahawa indeks dalam Google mungkin bukan yang terkini.',

# Quickbar
'qbsettings'               => 'Bar pantas',
'qbsettings-none'          => 'Tiada',
'qbsettings-fixedleft'     => 'Tetap sebelah kiri',
'qbsettings-fixedright'    => 'Tetap sebelah kanan',
'qbsettings-floatingleft'  => 'Berubah-ubah sebelah kiri',
'qbsettings-floatingright' => 'Berubah-ubah sebelah kanan',

# Preferences page
'preferences'                   => 'Keutamaan',
'mypreferences'                 => 'Keutamaan saya',
'prefs-edits'                   => 'Jumlah suntingan:',
'prefsnologin'                  => 'Belum log masuk',
'prefsnologintext'              => 'Anda hendaklah <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} log masuk]</span> terlebih dahulu untuk menetapkan keutamaan.',
'changepassword'                => 'Tukar kata laluan',
'prefs-skin'                    => 'Rupa',
'skin-preview'                  => 'Pratonton',
'prefs-math'                    => 'Matematik',
'datedefault'                   => 'Tiada keutamaan',
'prefs-datetime'                => 'Tarikh dan waktu',
'prefs-personal'                => 'Profil',
'prefs-rc'                      => 'Perubahan terkini',
'prefs-watchlist'               => 'Senarai pantau',
'prefs-watchlist-days'          => 'Had bilangan hari dalam senarai pantau:',
'prefs-watchlist-days-max'      => '(had 7 hari)',
'prefs-watchlist-edits'         => 'Had maksimum perubahan untuk ditunjukkan dalam senarai pantau penuh:',
'prefs-watchlist-edits-max'     => '(had: 1000)',
'prefs-watchlist-token'         => 'Token senarai pantau:',
'prefs-misc'                    => 'Pelbagai',
'prefs-resetpass'               => 'Tukar kata laluan',
'prefs-email'                   => 'Pilihan e-mel',
'prefs-rendering'               => 'Penampilan',
'saveprefs'                     => 'Simpan',
'resetprefs'                    => 'Set semula',
'restoreprefs'                  => 'Pulihkan semua tetapan lalai',
'prefs-editing'                 => 'Menyunting',
'prefs-edit-boxsize'            => 'Saiz kotak sunting.',
'rows'                          => 'Baris:',
'columns'                       => 'Lajur:',
'searchresultshead'             => 'Cari',
'resultsperpage'                => 'Jumlah padanan bagi setiap halaman:',
'contextlines'                  => 'Bilangan baris untuk dipaparkan bagi setiap capaian:',
'contextchars'                  => 'Bilangan askara konteks bagi setiap baris:',
'stub-threshold'                => 'Ambang bagi pemformatan <a href="#" class="stub">pautan ke rencana ringkas</a> (bait):',
'stub-threshold-disabled'       => 'Dilumpuhkan',
'recentchangesdays'             => 'Bilangan hari dalam perubahan terkini:',
'recentchangesdays-max'         => '(had $1 hari)',
'recentchangescount'            => 'Bilangan suntingan yang dipaparkan mengikut ketetapan:',
'prefs-help-recentchangescount' => 'Ini termasuklah perubahan terkini, sejarah laman dan log.',
'prefs-help-watchlist-token'    => 'Mengisi medan ini dengan kunci rahsia akan menghasilkan suapan RSS untuk senarai pantau anda.
Sesiapa yang mengetahui kunci dalam medan ini akan dapat membaca senarai pantau anda, jadi pilihlah nilai selamat.
Di sini ada nilai yang dihasilkan secara rawak yang boleh anda guna: $1',
'savedprefs'                    => 'Keutamaan anda disimpan.',
'timezonelegend'                => 'Zon waktu:',
'localtime'                     => 'Waktu tempatan:',
'timezoneuseserverdefault'      => 'Gunakan nilai pelayan',
'timezoneuseoffset'             => 'Lain-lain (nyatakan imbangan)',
'timezoneoffset'                => 'Imbangan¹:',
'servertime'                    => 'Waktu pelayan:',
'guesstimezone'                 => 'Gunakan tetapan pelayar saya',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antartika',
'timezoneregion-arctic'         => 'Artik',
'timezoneregion-asia'           => 'Asia',
'timezoneregion-atlantic'       => 'Lautan Atlantik',
'timezoneregion-australia'      => 'Australia',
'timezoneregion-europe'         => 'Eropah',
'timezoneregion-indian'         => 'Lautan Hindi',
'timezoneregion-pacific'        => 'Lautan Pasifik',
'allowemail'                    => 'Benarkan e-mel daripada pengguna lain',
'prefs-searchoptions'           => 'Pilihan gelintar',
'prefs-namespaces'              => 'Ruang nama',
'defaultns'                     => 'Jika tidak cari dalam ruang nama ini:',
'default'                       => 'lalai',
'prefs-files'                   => 'Fail',
'prefs-custom-css'              => 'CSS tempahan',
'prefs-custom-js'               => 'JS tempahan',
'prefs-reset-intro'             => 'Anda boleh menggunakan laman ini untuk set semula keutamaan anda kepada lalaian tapak ini.',
'prefs-emailconfirm-label'      => 'Pengesahan e-mail:',
'prefs-textboxsize'             => 'Saiz tetingkap penyuntingan',
'youremail'                     => 'E-mel:',
'username'                      => 'Nama pengguna:',
'uid'                           => 'ID pengguna:',
'prefs-memberingroups'          => 'Ahli {{PLURAL:$1|kumpulan|kumpulan}}:',
'prefs-registration'            => 'Masa pendaftaran:',
'yourrealname'                  => 'Nama sebenar:',
'yourlanguage'                  => 'Bahasa:',
'yourvariant'                   => 'Varian',
'yournick'                      => 'Nama samaran:',
'prefs-help-signature'          => 'Komen di laman perbincangan harus ditandatangani dengan "<nowiki>~~~~</nowiki>" yang akan ditukar menjadi tandatangan anda dan cap waktu.',
'badsig'                        => 'Tandatangan mentah tidak sah; sila semak tag HTML.',
'badsiglength'                  => 'Tandatangan anda tidak boleh melebihi $1 aksara.',
'yourgender'                    => 'Jantina:',
'gender-unknown'                => 'Tidak dinyatakan',
'gender-male'                   => 'Lelaki',
'gender-female'                 => 'Perempuan',
'prefs-help-gender'             => 'Pilihan: digunakan oleh perisian ini untuk merujuk diri anda dengan betul. Maklumat ini akan didedahkan kepada orang awam.',
'email'                         => 'E-mel',
'prefs-help-realname'           => 'Nama sebenar adalah tidak wajib. Jika dinyatakan, ia akan digunakan untuk mengiktiraf karya anda.',
'prefs-help-email'              => 'Alamat e-mel adalah tidak wajib. Akan tetapi, jika anda terlupa kata laluan, anda boleh meminta kata laluan yang baru dikirim kepada e-mel anda. Anda juga boleh membenarkan orang lain menghubungi anda melalui laman pengguna atau laman perbualan anda tanpa mendedahkan identiti anda.',
'prefs-help-email-required'     => 'Alamat e-mel adalah wajib.',
'prefs-info'                    => 'Maklumat asas',
'prefs-i18n'                    => 'Pengantarabangsaan',
'prefs-signature'               => 'Tandatangan',
'prefs-dateformat'              => 'Format tarikh',
'prefs-timeoffset'              => 'Imbangan masa',
'prefs-advancedediting'         => 'Pilihan terperinci',
'prefs-advancedrc'              => 'Pilihan maju',
'prefs-advancedrendering'       => 'Pilihan maju',
'prefs-advancedsearchoptions'   => 'Pilihan maju',
'prefs-advancedwatchlist'       => 'Pilihan maju',
'prefs-displayrc'               => 'Papar pilihan',
'prefs-displaysearchoptions'    => 'Papar pilihan',
'prefs-displaywatchlist'        => 'Papar pilihan',
'prefs-diffs'                   => 'Beza',

# User rights
'userrights'                   => 'Pengurusan hak pengguna',
'userrights-lookup-user'       => 'Urus kumpulan pengguna',
'userrights-user-editname'     => 'Masukkan nama pengguna:',
'editusergroup'                => 'Sunting Kumpulan Pengguna',
'editinguser'                  => "Mengubah hak pengguna '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Ubah kumpulan pengguna',
'saveusergroups'               => 'Simpan Kumpulan Pengguna',
'userrights-groupsmember'      => 'Ahli bagi:',
'userrights-groupsmember-auto' => 'Ahli automatik bagi:',
'userrights-groups-help'       => 'Anda boleh mengubah keahlian kumpulan bagi pengguna ini:
* Petak yang bertanda bererti pengguna tersebut adalah ahli kumpulan itu.
* Petak yang tidak bertanda bererti bahawa pengguna tersebut bukan ahli kumpulan itu.
* Tanda bintang (*) menandakan bahawa anda tidak boleh melucutkan keahlian pengguna tersebut setelah anda melantiknya, dan begitulah sebaliknya.',
'userrights-reason'            => 'Sebab:',
'userrights-no-interwiki'      => 'Anda tidak mempunyai keizinan untuk mengubah hak-hak pengguna di wiki lain.',
'userrights-nodatabase'        => 'Pangkalan data $1 tiada atau bukan tempatan.',
'userrights-nologin'           => 'Anda mesti [[Special:UserLogin|log masuk]] dengan akaun pentadbir terlebih dahulu untuk memperuntukkan hak-hak pengguna.',
'userrights-notallowed'        => 'Anda tidak mempunyai keizinan untuk memperuntukkan hak-hak pengguna.',
'userrights-changeable-col'    => 'Kumpulan yang anda boleh ubah',
'userrights-unchangeable-col'  => 'Kumpulan yang anda tak boleh ubah',

# Groups
'group'               => 'Kumpulan:',
'group-user'          => 'Pengguna',
'group-autoconfirmed' => 'Pengguna yang disahkan secara automatik',
'group-bot'           => 'Bot',
'group-sysop'         => 'Penyelia',
'group-bureaucrat'    => 'Birokrat',
'group-suppress'      => 'Pengawas',
'group-all'           => '(semua)',

'group-user-member'          => 'Pengguna',
'group-autoconfirmed-member' => 'Pengguna yang disahkan secara automatik',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Penyelia',
'group-bureaucrat-member'    => 'Birokrat',
'group-suppress-member'      => 'Pengawas',

'grouppage-user'          => '{{ns:project}}:Pengguna',
'grouppage-autoconfirmed' => '{{ns:project}}:Pengguna yang disahkan secara automatik',
'grouppage-bot'           => '{{ns:project}}:Bot',
'grouppage-sysop'         => '{{ns:project}}:Pentadbir',
'grouppage-bureaucrat'    => '{{ns:project}}:Birokrat',
'grouppage-suppress'      => '{{ns:project}}:Pengawas',

# Rights
'right-read'                  => 'Membaca laman',
'right-edit'                  => 'Menyunting laman',
'right-createpage'            => 'Mencipta laman (selain laman perbincangan)',
'right-createtalk'            => 'Mencipta laman perbincangan',
'right-createaccount'         => 'Membuka akaun pengguna baru',
'right-minoredit'             => 'Menanda suntingan kecil',
'right-move'                  => 'Memindah laman',
'right-move-subpages'         => 'Memindahkan laman berserta sublaman',
'right-move-rootuserpages'    => 'Memindahkan laman induk pengguna',
'right-movefile'              => 'Memindahkan fail',
'right-suppressredirect'      => 'Memindahkan sesebuah laman tanpa mencipta lencongan',
'right-upload'                => 'Memuat naik fail',
'right-reupload'              => 'Menulis ganti fail sedia ada',
'right-reupload-own'          => 'Menulis ganti fail sedia ada yang dimuat naik sendiri',
'right-reupload-shared'       => 'Mengatasi fail di gedung media kongsi',
'right-upload_by_url'         => 'Memuat naik fail daripada alamat URL',
'right-purge'                 => 'Membersihkan cache bagi sesebuah laman tanpa pengesahan',
'right-autoconfirmed'         => 'Menyunting laman yang dilindungi separa',
'right-bot'                   => 'Dianggap melakukan tugas-tugas automatik',
'right-nominornewtalk'        => 'Suntingan kecil pada laman perbincangan seseorang pengguna tidak menghidupkan isyarat pesanan baru untuk pengguna itu',
'right-apihighlimits'         => 'Meninggikan had dalam pertanyaan API',
'right-writeapi'              => 'Menggunakan API tulis',
'right-delete'                => 'Menghapuskan laman',
'right-bigdelete'             => 'Menghapuskan laman bersejarah',
'right-deleterevision'        => 'Menghapuskan dan memulihkan semula mana-mana semakan bagi sesebuah laman',
'right-deletedhistory'        => 'Melihat senarai entri sejarah yang telah dihapuskan, tetapi tanpa teks yang berkaitan',
'right-deletedtext'           => 'Lihat teks yang telah dihapuskan dan perubahan antara semakan-semakan yang telah dihapuskan',
'right-browsearchive'         => 'Menggelintar laman-laman yang telah dihapuskan',
'right-undelete'              => 'Mengembalikan laman yang telah dihapuskan (nyahhapus)',
'right-suppressrevision'      => 'Memeriksa dan memulihkan semakan yang terselindung daripada penyelia',
'right-suppressionlog'        => 'Melihat log rahsia',
'right-block'                 => 'Menyekat pengguna lain daripada menyunting',
'right-blockemail'            => 'Menyekat pengguna lain daripada mengirim e-mel',
'right-hideuser'              => 'Menyekat sesebuah nama pengguna, menyembunyikannya daripada orang ramai',
'right-ipblock-exempt'        => 'Melangkau sekatan IP, sekatan automatik dan sekatan julat',
'right-proxyunbannable'       => 'Melangkau sekatan proksi automatik',
'right-unblockself'           => 'Menyahsekat diri sendiri',
'right-protect'               => 'Menukar peringkat perlindungan dan menyunting laman yang dilindungi',
'right-editprotected'         => 'Menyunting laman yang dilindungi (tanpa perlindungan melata)',
'right-editinterface'         => 'Menyunting antara muka pengguna',
'right-editusercssjs'         => 'Menyunting fail CSS dan JavaScript pengguna lain',
'right-editusercss'           => 'Menyunting fail CSS pengguna lain',
'right-edituserjs'            => 'Menyunting fail JavaScript pengguna lain',
'right-rollback'              => 'Mengundurkan suntigan terakhir bagi laman tertentu',
'right-markbotedits'          => 'Menanda suntingan yang diundurkan sebagai suntingan bot',
'right-noratelimit'           => 'Tidak dikenakan had kadar penyuntingan',
'right-import'                => 'Mengimport laman dari wiki lain',
'right-importupload'          => 'Mengimport laman dengan memuat naik fail',
'right-patrol'                => 'Memeriksa suntingan orang lain',
'right-autopatrol'            => 'Suntingannya ditandakan sebagai telah diperiksa secara automatik',
'right-patrolmarks'           => 'Melihat tanda pemeriksaan dalam senarai perubahan terkini',
'right-unwatchedpages'        => 'Melihat senarai laman yang tidak dipantau',
'right-trackback'             => 'Mengirim jejak balik',
'right-mergehistory'          => 'Menggabungkan sejarah laman',
'right-userrights'            => 'Menyerahkan dan menarik balik sebarang hak pengguna',
'right-userrights-interwiki'  => 'Menyerahkan dan menarik balik hak pengguna di wiki lain',
'right-siteadmin'             => 'Mengunci dan membuka kunci pangkalan data',
'right-reset-passwords'       => 'Mengeset semula kata laluan pengguna lain',
'right-override-export-depth' => 'Eksport laman termasuk laman dipaut sehingga kedalaman 5',
'right-sendemail'             => 'Kirim e-mel kepada pengguna-pengguna lain',
'right-revisionmove'          => 'Memindahkan semakan',

# User rights log
'rightslog'      => 'Log hak pengguna',
'rightslogtext'  => 'Ini ialah log bagi perubahan hak pengguna.',
'rightslogentry' => 'menukar keahlian kumpulan bagi $1 daripada $2 kepada $3',
'rightsnone'     => '(tiada)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'membaca laman ini',
'action-edit'                 => 'menyunting laman ini',
'action-createpage'           => 'mencipta laman',
'action-createtalk'           => 'mencipta laman perbincangan',
'action-createaccount'        => 'mencipta akaun pengguna ini',
'action-minoredit'            => 'menanda suntingan ini sebagai suntingan kecil',
'action-move'                 => 'memindahkan laman ini',
'action-move-subpages'        => 'memindahkan laman ini dan sublaman-sublamannya',
'action-move-rootuserpages'   => 'memindahkan laman induk pengguna',
'action-movefile'             => 'pindah fail ini',
'action-upload'               => 'memuat naik fail ini',
'action-reupload'             => 'menulis ganti fail ini',
'action-reupload-shared'      => 'mengatasi fail dari gedung kongsi ini',
'action-upload_by_url'        => 'memuat naik fail ini dari alamat URL',
'action-writeapi'             => 'menggunakan API tulis',
'action-delete'               => 'menghapuskan laman ini',
'action-deleterevision'       => 'menghapuskan semakan ini',
'action-deletedhistory'       => 'melihat sejarah yang telah dihapuskan bagi laman ini',
'action-browsearchive'        => 'menggelintar laman-laman yang telah dihapuskan',
'action-undelete'             => 'menyahhapuskan laman ini',
'action-suppressrevision'     => 'menyemak semula dan memulihkan semakan tersembunyi ini',
'action-suppressionlog'       => 'melihat log sulit ini',
'action-block'                => 'menyekat pengguna ini daripada menyunting',
'action-protect'              => 'mengubah aras perlindungan bagi laman ini',
'action-import'               => 'mengimport laman ini dari wiki lain',
'action-importupload'         => 'mengimport laman ini dengan memuat naik fail',
'action-patrol'               => 'menanda ronda suntingan orang lain',
'action-autopatrol'           => 'menanda ronda suntingan anda sendiri',
'action-unwatchedpages'       => 'melihat senarai laman tidak dipantau',
'action-trackback'            => 'mengirim jejak balik',
'action-mergehistory'         => 'menggabungkan sejarah laman ini',
'action-userrights'           => 'mengubah semua hak pengguna',
'action-userrights-interwiki' => 'mengubah hak pengguna dari wiki lain',
'action-siteadmin'            => 'mengunci atau membuka kunci pangkalan data wiki ini',
'action-revisionmove'         => 'memindahkan semakan',

# Recent changes
'nchanges'                          => '$1 perubahan',
'recentchanges'                     => 'Perubahan terkini',
'recentchanges-legend'              => 'Pilihan perubahan terkini',
'recentchangestext'                 => 'Jejaki perubahan terkini dalam {{SITENAME}} pada laman ini.',
'recentchanges-feed-description'    => 'Jejaki perubahan terkini dalam {{SITENAME}} pada suapan ini.',
'recentchanges-label-newpage'       => 'Suntingan ini mencipta laman baru',
'recentchanges-label-minor'         => 'Ini ialah suntingan kecil',
'recentchanges-label-bot'           => 'Suntingan ini dilakukan oleh bot',
'recentchanges-label-unpatrolled'   => 'Suntingan ini belum dirondai',
'rcnote'                            => "Yang berikut ialah '''$1''' perubahan terakhir sejak '''$2''' hari yang lalu sehingga $5, $4.",
'rcnotefrom'                        => 'Yang berikut ialah semua perubahan sejak <b>$2</b> (sehingga <b>$1</b>).',
'rclistfrom'                        => 'Papar perubahan sejak $1',
'rcshowhideminor'                   => '$1 suntingan kecil',
'rcshowhidebots'                    => '$1 bot',
'rcshowhideliu'                     => '$1 pengguna log masuk',
'rcshowhideanons'                   => '$1 pengguna tanpa nama',
'rcshowhidepatr'                    => '$1 suntingan dirondai',
'rcshowhidemine'                    => '$1 suntingan saya',
'rclinks'                           => 'Paparkan $1 perubahan terakhir sejak $2 hari yang lalu<br />$3',
'diff'                              => 'beza',
'hist'                              => 'sej',
'hide'                              => 'Sembunyi',
'show'                              => 'Papar',
'minoreditletter'                   => 'k',
'newpageletter'                     => 'B',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 pemantau]',
'rc_categories'                     => 'Hadkan kepada kategori (asingkan dengan "|")',
'rc_categories_any'                 => 'Semua',
'newsectionsummary'                 => '/* $1 */ bahagian baru',
'rc-enhanced-expand'                => 'Papar butiran (JavaScript diperlukan)',
'rc-enhanced-hide'                  => 'Sembunyi butiran',

# Recent changes linked
'recentchangeslinked'          => 'Perubahan berkaitan',
'recentchangeslinked-feed'     => 'Perubahan berkaitan',
'recentchangeslinked-toolbox'  => 'Perubahan berkaitan',
'recentchangeslinked-title'    => 'Perubahan berkaitan dengan $1',
'recentchangeslinked-noresult' => 'Tiada perubahan pada semua laman yang dipaut dalam tempoh yang diberikan.',
'recentchangeslinked-summary'  => "Laman khas ini menyenaraikan perubahan terkini bagi laman-laman yang dipaut. Laman-laman yang terdapat dalam senarai pantau anda ditandakan dengan '''teks tebal'''.",
'recentchangeslinked-page'     => 'Nama laman:',
'recentchangeslinked-to'       => 'Paparkan perubahan pada laman yang mengandungi pautan ke laman yang diberikan',

# Upload
'upload'                      => 'Muat naik fail',
'uploadbtn'                   => 'Muat naik',
'reuploaddesc'                => 'Kembali ke borang muat naik',
'upload-tryagain'             => 'Serah keterangan fail yang telah diubah',
'uploadnologin'               => 'Belum log masuk',
'uploadnologintext'           => 'Anda perlu [[Special:UserLogin|log masuk]]
terlebih dahulu untuk memuat naik fail.',
'upload_directory_missing'    => 'Direktori muat naik ($1) hilang dan tidak dapat dicipta oleh pelayan web.',
'upload_directory_read_only'  => 'Direktori muat naik ($1) tidak boleh ditulis oleh pelayan web.',
'uploaderror'                 => 'Ralat muat naik',
'upload-recreate-warning'     => "'''Amaran: Sebuah fail dengan nama tersebut telah pun dihapuskan atau dipindahkan.'''

Log penghapusan dan pemindahan untuk laman ini disediakan di bawah ini untuk kemudahan:",
'uploadtext'                  => "Gunakan borang di bawah untuk memuat naik fail.
Untuk melihat atau mencari imej yang sudah dimuat naik, sila ke [[Special:FileList|senarai fail yang dimuat naik]]. Tindakan muat naik akan direkodkan dalam [[Special:Log/upload|log muat naik]], manakala penghapusan dalam [[Special:Log/delete|log penghapusan]].

Untuk menyertakan sebarang fail ke dalam sesebuah laman, gunakan pautan dengan satu daripada bentuk-bentuk berikut:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Fail.jpg]]</nowiki></tt>''' untuk menggunakan versi penuh bagi fail itu
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Fail.png|200px|thumb|left|teks alternatif]]</nowiki></tt>''' untuk menggunakan lakaran 200 piksel lebar di dalam sebuah kotak yang diletakkan di jidar kiri dengan keterangan 'teks alternatif'
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Fail.ogg]]</nowiki></tt>''' untuk memaut secara terus tanpa memaparkan fail itu",
'upload-permitted'            => 'Jenis fail yang dibenarkan: $1.',
'upload-preferred'            => 'Jenis fail yang diutamakan: $1.',
'upload-prohibited'           => 'Jenis fail yang dilarang: $1.',
'uploadlog'                   => 'log muat naik',
'uploadlogpage'               => 'Log muat naik',
'uploadlogpagetext'           => 'Yang berikut ialah senarai terkini bagi fail yang dimuat naik.',
'filename'                    => 'Nama fail',
'filedesc'                    => 'Ringkasan',
'fileuploadsummary'           => 'Ringkasan:',
'filereuploadsummary'         => 'Perubahan fail:',
'filestatus'                  => 'Status hak cipta:',
'filesource'                  => 'Sumber:',
'uploadedfiles'               => 'Fail yang telah dimuat naik',
'ignorewarning'               => 'Abaikan amaran dan simpan juga fail ini.',
'ignorewarnings'              => 'Abaikan mana-mana amaran.',
'minlength1'                  => 'Panjang nama fail mestilah sekurang-kurangnya satu huruf.',
'illegalfilename'             => 'Nama fail "$1" mengandungi aksara yang tidak dibenarkan dalam tajuk laman. Sila tukar nama fail ini dan muat naik sekali lagi.',
'badfilename'                 => 'Nama fail telah ditukar kepada "$1".',
'filetype-mime-mismatch'      => 'Sambungan fail tidak berpadanan dengan jenis MIME.',
'filetype-badmime'            => 'Memuat naik fail jenis MIME "$1" adalah tidak dibenarkan.',
'filetype-bad-ie-mime'        => 'Fail ini tidak boleh dimuat naik kerana Internet Explorer mengesannya sebagai fail jenis "$1" yang tidak dibenarkan dan berbahaya.',
'filetype-unwanted-type'      => "'''\".\$1\"''' adalah jenis fail yang tidak dikehendaki. {{PLURAL:\$3|Jenis|Jenis-jenis}} fail yang diutamakan ialah \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' adalah jenis fail yang dilarang. {{PLURAL:\$3|Jenis|Jenis-jenis}} fail yang dibenarkan ialah \$2.",
'filetype-missing'            => 'Fail ini tidak mempunyai sambungan (contohnya ".jpg").',
'empty-file'                  => 'Fail yang anda serahkan adalah kosong.',
'file-too-large'              => 'Fail yang anda serahkan adalah terlalu besar.',
'filename-tooshort'           => 'Nama fail ini terlalu pendek.',
'filetype-banned'             => 'Fail jenis ini adalah dilarang.',
'verification-error'          => 'Fail ini tidak lulus pengesahan fail.',
'hookaborted'                 => 'Pengubahsuaian yang anda buat telah disekat oleh sebuah cangkuk penyambung.',
'illegal-filename'            => 'Nama fail tidak dibenarkan.',
'overwrite'                   => 'Menulis ganti fail yang telah wujud adalah tidak dibenarkan.',
'unknown-error'               => 'Berlaku ralat yang tidak diketahui.',
'tmp-create-error'            => 'Fail sementara tidak dapat dicipta.',
'tmp-write-error'             => 'Fail sementara tidak dapat ditulis.',
'large-file'                  => 'Saiz fail ini ialah $2. Anda dinasihati supaya memuat naik fail yang tidak melebihi $1.',
'largefileserver'             => 'Fail ini telah melebihi had muat naik pelayan web.',
'emptyfile'                   => 'Fail yang dimuat naik adalah kosong. Ini mungkin disebabkan oleh kesilapan menaip nama fail. Sila pastikan bahawa anda betul-betul mahu memuat naik fail ini.',
'fileexists'                  => "Sebuah fail dengan nama ini telah pun wujud.
Sila semak '''<tt>[[:$1]]</tt>''' sekiranya anda tidak pasti bahawa anda mahu menukarnya atau tidak.
[[$1|thumb]]",
'filepageexists'              => "Laman penerangan untuk fail ini telah pun dicipta di '''<tt>[[:$1]]</tt>''', tetapi tiada fail dengan nama ini wujud.
Ringkasan yang anda masukkan tidak akan muncul di laman penerangan tersebut. Untuk memastikannya muncul, anda perlu menyuntingnya secara manual.
[[$1|thumb]]",
'fileexists-extension'        => "Sebuah fail dengan nama yang sama telah pun wujud: [[$2|thumb]]
* Nama fail yang dimuat naik: '''<tt>[[:$1]]</tt>'''
* Nama fail yang sedia ada: '''<tt>[[:$2]]</tt>'''
Sila pilih nama lain.",
'fileexists-thumbnail-yes'    => "Fail ini kelihatan seperti sebuah imej yang telah dikecilkan ''(imej ringkas)''. [[$1|thumb]]
Sila semak fail '''<tt>[[:$1]]</tt>'''.
Jika fail yang disemak itu adalah sama dengan yang saiz asal, maka anda tidak perlu memuat naik imej ringkas tambahan.",
'file-thumbnail-no'           => "Nama fail ini bermula dengan '''<tt>$1</tt>'''. Barangkali ia adalah sebuah imej yang telah dikecilkan ''(imej ringkas)''.
Jika anda memiliki imej ini dalam leraian penuh, sila muat naik fail tersebut. Sebaliknya, sila tukar nama fail ini.",
'fileexists-forbidden'        => 'Sebuah fail dengan nama ini telah pun wujud, dan tidak boleh ditulis ganti. Jika anda masih mahu memuat naik fail ini, sila berundur dan muat naik fail ini dengan nama lain. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Sebuah fail dengan nama ini telah pun wujud dalam gedung fail kongsi. Jika anda masih mahu memuat naik fail ini, sila kembali ke borang muat naik dan gunakan nama lain. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Fail ini adalah salinan bagi {{PLURAL:$1|fail|fail-fail}} berikut:',
'file-deleted-duplicate'      => 'Sebuah fail yang serupa dengan fail ini ([[$1]]) telah pun dihapuskan sebelum ini. Anda seharusnya memeriksa sejarah penghapusan fail itu terlebih dahulu sebelum memuat naiknya sekali lagi.',
'uploadwarning'               => 'Amaran muat naik',
'uploadwarning-text'          => 'Sila ubah keterangan fail di bawah dan cuba lagi.',
'savefile'                    => 'Simpan fail',
'uploadedimage'               => 'memuat naik "[[$1]]"',
'overwroteimage'              => 'memuat naik versi baru bagi "[[$1]]"',
'uploaddisabled'              => 'Ciri muat naik dimatikan',
'copyuploaddisabled'          => 'Ciri muat naik melalui URL telah dilumpuhkan.',
'uploaddisabledtext'          => 'Ciri muat naik fail dimatikan.',
'php-uploaddisabledtext'      => 'Pemuatnaikan fail PHP dilumpuhkan. Sila semak tetapan file_uploads.',
'uploadscripted'              => 'Fail ini mengandungi kod HTML atau skrip yang boleh disalahtafsirkan oleh pelayar web.',
'uploadvirus'                 => 'Fail tersebut mengandungi virus! Butiran: $1',
'upload-source'               => 'Fail sumber',
'sourcefilename'              => 'Nama fail sumber:',
'sourceurl'                   => 'URL sumber:',
'destfilename'                => 'Nama fail destinasi:',
'upload-maxfilesize'          => 'Had saiz fail: $1',
'upload-description'          => 'Keterangan fail',
'upload-options'              => 'Pilihan muat naik',
'watchthisupload'             => 'Pantau fail ini',
'filewasdeleted'              => 'Sebuah fail dengan nama ini pernah dimuat naik, tetapi kemudiannya dihapuskan. Anda seharusnya menyemak $1 sebelum meneruskan percubaan untuk memuat naik fail ini.',
'upload-wasdeleted'           => "'''Amaran: Anda sedang memuat naik sebuah fail yang pernah dihapuskan.'''

Anda harus mempertimbangkan perlunya memuat naik fail ini.
Untuk rujukan, yang berikut ialah log penghapusan bagi fail ini:",
'filename-bad-prefix'         => "Nama bagi fail yang dimuat naik bermula dengan '''\"\$1\"''', yang mana merupakan nama yang tidak deskriptif yang biasanya ditetapkan oleh kamera digital secara automatik. Sila berikan nama yang lebih deskriptif bagi fail tersebut.",
'upload-success-subj'         => 'Muat naik berjaya',
'upload-failure-subj'         => 'Masalah muat naik',
'upload-failure-msg'          => 'Terdapat masalah dengan muat naik anda daripada [$2]:

$1',
'upload-warning-subj'         => 'Amaran muat naik',
'upload-warning-msg'          => 'Terdapat masalah dengan muat naik anda daripada [$2]. Anda boleh kembali ke [[Special:Upload/stash/$1|borang muat naik]] untuk mengatasi masalah ini.',

'upload-proto-error'        => 'Protokol salah',
'upload-proto-error-text'   => 'Muat naik jauh memerlukan URL yang dimulakan dengan <code>http://</code> atau <code>ftp://</code>.',
'upload-file-error'         => 'Ralat dalaman',
'upload-file-error-text'    => 'Ralat dalaman telah berlaku ketika cuba mencipta fail sementara pada komputer pelayan.
Sila hubungi [[Special:ListUsers/sysop|pentadbir sistem]].',
'upload-misc-error'         => 'Ralat muat naik yang tidak diketahui',
'upload-misc-error-text'    => 'Ralat yang tidak diketahui telah berlaku ketika muat naik. Sila pastikan bahawa URL tersebut sah dan boleh dicapai kemudian cuba lagi. Jika masalah ini berterusan, sila hubungi pentadbir sistem.',
'upload-too-many-redirects' => 'URL ini mengandungi terlalu banyak lencongan',
'upload-unknown-size'       => 'Saiz tidak diketahui',
'upload-http-error'         => 'Berlaku ralat HTTP: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Capaian ditolak',
'img-auth-nopathinfo'   => 'Tiada PATH_INFO.
Pelayan anda tidak ditetapkan untuk menyampaikan maklumat ini.
Ia barangkali berdasarkan CGI dan tidak boleh menyokong img_auth.
Lihat http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'Laluan yang diminta tiada dalam direktori muat naik yang telah dikonfigurasikan.',
'img-auth-badtitle'     => 'Tajuk yang sah tidak dapat dibina daripada "$1".',
'img-auth-nologinnWL'   => 'Anda belum log masuk dan "$1" tiada dalam senarai putih.',
'img-auth-nofile'       => 'Fail "$1" tiada.',
'img-auth-isdir'        => 'Anda telah mencuba mencapai direktori "$1". Hanya capaian fail dibenarkan.',
'img-auth-streaming'    => '"$1" sedang disalurkan.',
'img-auth-public'       => 'Fungsi img_auth.php ialah mengoutput fail-fail daripada wiki peribadi.
Wiki ini telah dikonfigurasikan sebagai wiki awam.
Untuk keselamatan optimum, img_auth.php telah dilumpuhkan.',
'img-auth-noread'       => 'Pengguna tidak mempunyai capaian membaca "$1".',

# HTTP errors
'http-invalid-url'      => 'URL tidak sah: $1',
'http-invalid-scheme'   => 'URL dengan skema "$1" tidak disokong.',
'http-request-error'    => 'Permintaan HTTP gagal kerana ralat yang tidak diketahui.',
'http-read-error'       => 'Ralat baca HTTP.',
'http-timed-out'        => 'Permintaan HTTP melebihi waktu tamat.',
'http-curl-error'       => 'Ralat mendapatkan URL: $1',
'http-host-unreachable' => 'URL tidak dapat dicapai.',
'http-bad-status'       => 'Berlaku masalah ketika permintaan HTTP: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL tidak dapat dicapai',
'upload-curl-error6-text'  => 'URL yang dinyatakan tidak dapat dicapai. Sila pastikan bahawa URL dan tapak web tersebut hidup.',
'upload-curl-error28'      => 'Waktu henti muat naik',
'upload-curl-error28-text' => 'Tapak web tersebut terlalu lambat bertindak balas. Sila pastikan bahawa tapak web tersebut hidup, tunggu sebentar dan cuba lagi. Anda boleh mencuba lagi pada waktu yang kurang sibuk.',

'license'            => 'Lesen:',
'license-header'     => 'Perlesenan',
'nolicense'          => 'Tidak dipilih',
'license-nopreview'  => '(Tiada pratonton)',
'upload_source_url'  => ' (URL yang boleh diakses oleh orang awam)',
'upload_source_file' => ' (fail dalam komputer anda)',

# Special:ListFiles
'listfiles-summary'     => 'Laman khas ini memaparkan senarai fail yang telah dimuat naik.
Klik di atas mana-mana lajur yang berkenaan untuk menukar tertib susunan.',
'listfiles_search_for'  => 'Cari nama imej:',
'imgfile'               => 'fail',
'listfiles'             => 'Senarai fail',
'listfiles_date'        => 'Tarikh',
'listfiles_name'        => 'Nama',
'listfiles_user'        => 'Pengguna',
'listfiles_size'        => 'Saiz',
'listfiles_description' => 'Huraian',
'listfiles_count'       => 'Versi',

# File description page
'file-anchor-link'          => 'Imej',
'filehist'                  => 'Sejarah fail',
'filehist-help'             => 'Klik pada tarikh/waktu untuk melihat rupa fail tersebut pada waktu silam.',
'filehist-deleteall'        => 'hapuskan semua',
'filehist-deleteone'        => 'hapuskan ini',
'filehist-revert'           => 'balik',
'filehist-current'          => 'semasa',
'filehist-datetime'         => 'Tarikh/Waktu',
'filehist-thumb'            => 'Imej ringkas',
'filehist-thumbtext'        => 'Imej ringkas bagi versi pada $1',
'filehist-nothumb'          => 'Tiada imej ringkas',
'filehist-user'             => 'Pengguna',
'filehist-dimensions'       => 'Ukuran',
'filehist-filesize'         => 'Saiz fail',
'filehist-comment'          => 'Komen',
'filehist-missing'          => 'Fail tidak dapat dijumpai',
'imagelinks'                => 'Pautan fail',
'linkstoimage'              => '{{PLURAL:$1|Laman|$1 buah laman}} berikut mengandungi pautan ke fail ini:',
'linkstoimage-more'         => 'Lebih daripada $1 laman mengandungi pautan ke fail ini.
Yang berikut ialah {{PLURAL:$1||$1}} pautan pertama ke fail ini.
Anda boleh melihat [[Special:WhatLinksHere/$2|senarai penuh]].',
'nolinkstoimage'            => 'Tiada laman yang mengandungi pautan ke fail ini.',
'morelinkstoimage'          => 'Lihat [[Special:WhatLinksHere/$1|semua pautan]] ke fail ini.',
'redirectstofile'           => '{{PLURAL:$1|Fail|$1 buah fail}} berikut melencong ke fail ini:',
'duplicatesoffile'          => '{{PLURAL:$1|Fail|$1 buah fail}} berikut adalah salinan bagi fail ini ([[Special:FileDuplicateSearch/$2|butiran lanjut]]):',
'sharedupload'              => 'Fail ini daripada $1 dan boleh digunakan oleh projek lain.',
'sharedupload-desc-there'   => 'Fail ini dari $1 dan mungkin digunakan oleh projek lain.
Sila lihat [$2 laman penerangan fail] untuk maklumat lanjut.',
'sharedupload-desc-here'    => 'Fail ini dari $1 dan mungkin digunakan oleh projek lain.
Penerangan pada [$2 laman penerangan failnya] di sana ditunjukkan di bawah.',
'filepage-nofile'           => 'Fail dengan nama ini tidak wujud.',
'filepage-nofile-link'      => 'Fail dengan nama ini tidak wujud, tetapi boleh [$1 dimuat naik].',
'uploadnewversion-linktext' => 'Muat naik versi baru bagi fail ini',
'shared-repo-from'          => 'dari $1',
'shared-repo'               => 'sebuah gedung kongsi',

# File reversion
'filerevert'                => 'Balikkan $1',
'filerevert-legend'         => 'Balikkan fail',
'filerevert-intro'          => '<span class="plainlinks">Anda sedang menmbalikkan \'\'\'[[Media:$1|$1]]\'\'\' kepada [$4 versi pada $3, $2].</span>',
'filerevert-comment'        => 'Sebab:',
'filerevert-defaultcomment' => 'Dibalikkan kepada versi pada $2, $1',
'filerevert-submit'         => 'Balikkan',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' telah dibalikkan kepada [$4 versi pada $3, $2].</span>',
'filerevert-badversion'     => 'Tiada versi tempatan bagi fail ini dengan cap waktu yang dinyatakan.',

# File deletion
'filedelete'                  => 'Hapuskan $1',
'filedelete-legend'           => 'Hapuskan fail',
'filedelete-intro'            => "Anda sudah hendak menghapuskan fail '''[[Media:$1|$1]]''' berserta semua sejarahnya.",
'filedelete-intro-old'        => '<span class="plainlinks">Anda sedang menghapuskan versi \'\'\'[[Media:$1|$1]]\'\'\' pada [$4 $3, $2].</span>',
'filedelete-comment'          => 'Sebab:',
'filedelete-submit'           => 'Hapus',
'filedelete-success'          => "'''$1''' telah dihapuskan.",
'filedelete-success-old'      => "Versi '''[[Media:$1|$1]]''' pada $3, $2 telah dihapuskan.",
'filedelete-nofile'           => "'''$1''' tidak wujud.",
'filedelete-nofile-old'       => "Tiada versi arkib bagi '''$1''' dengan sifat-sifat yang dinyatakan.",
'filedelete-otherreason'      => 'Sebab lain/tambahan:',
'filedelete-reason-otherlist' => 'Sebab lain',
'filedelete-reason-dropdown'  => '
*Sebab-sebab lazim
** Melanggar hak cipta
** Fail berulang',
'filedelete-edit-reasonlist'  => 'Ubah sebab-sebab hapus',
'filedelete-maintenance'      => 'Ciri penghapusan dan pemulihan fail telah dilumpuhkan buat sementara sepanjang proses penyenggaraan.',

# MIME search
'mimesearch'         => 'Carian MIME',
'mimesearch-summary' => 'Anda boleh menggunakan laman ini untuk mencari fail mengikut jenis MIME. Format input ialah "jenis/subjenis", contohnya <tt>image/jpeg</tt>.',
'mimetype'           => 'Jenis MIME:',
'download'           => 'muat turun',

# Unwatched pages
'unwatchedpages' => 'Laman tidak dipantau',

# List redirects
'listredirects' => 'Senarai lencongan',

# Unused templates
'unusedtemplates'     => 'Templat tidak digunakan',
'unusedtemplatestext' => 'Yang berikut ialah senarai laman dalam ruang nama {{ns:template}} yang tidak disertakan dalam laman lain. Sila pastikan bahawa anda menyemak pautan lain ke templat-templat tersebut sebelum menghapuskannya.',
'unusedtemplateswlh'  => 'pautan-pautan lain',

# Random page
'randompage'         => 'Laman rawak',
'randompage-nopages' => 'Tiada laman dalam {{PLURAL:$2|ruang|ruang-ruang}} nama berikut: $1.',

# Random redirect
'randomredirect'         => 'Lencongan rawak',
'randomredirect-nopages' => 'Tiada lencongan dalam ruang nama "$1".',

# Statistics
'statistics'                   => 'Statistik',
'statistics-header-pages'      => 'Statistik laman',
'statistics-header-edits'      => 'Statistik suntingan',
'statistics-header-views'      => 'Statistik pandangan',
'statistics-header-users'      => 'Statistik pengguna',
'statistics-header-hooks'      => 'Statistik lain',
'statistics-articles'          => 'Laman kandungan',
'statistics-pages'             => 'Laman',
'statistics-pages-desc'        => 'Semua laman di wiki ini, termasuk laman perbincangan, lencongan, dan lain-lain.',
'statistics-files'             => 'Fail dimuat naik',
'statistics-edits'             => 'Suntingan laman sejak {{SITENAME}} dibuka',
'statistics-edits-average'     => 'Purata suntingan bagi setiap laman',
'statistics-views-total'       => 'Jumlah pandangan',
'statistics-views-peredit'     => 'Pandangan setiap suntingan',
'statistics-users'             => '[[Special:ListUsers|Pengguna]] berdaftar',
'statistics-users-active'      => 'Pengguna aktif',
'statistics-users-active-desc' => 'Pengguna yang aktif sejak {{PLURAL:$1|semalam|$1 hari lalu}}',
'statistics-mostpopular'       => 'Laman dilihat terbanyak',

'disambiguations'      => 'Laman penyahtaksaan',
'disambiguationspage'  => 'Template:disambig',
'disambiguations-text' => "Laman-laman berikut mengandungi pautan ke '''laman penyahtaksaan'''. Pautan ini sepatutnya ditujukan kepada topik yang sepatutnya.<br />Sesebuah laman dianggap sebagai laman penyahtaksaan jika ia menggunakan templat yang dipaut dari [[MediaWiki:Disambiguationspage]]",

'doubleredirects'            => 'Lencongan berganda',
'doubleredirectstext'        => 'Yang berikut ialah senarai laman yang melencong ke laman lencongan lain. Setiap baris mengandungi pautan ke laman lencongan pertama dan kedua, serta baris pertama bagi teks lencongan kedua, lazimnya merupakan laman sasaran "sebenar", yang sepatutnya ditujui oleh lencongan pertama.
Masukan yang <del>dipotong</del> telah diselesaikan.',
'double-redirect-fixed-move' => '[[$1]] dilencongkan ke [[$2]]',
'double-redirect-fixer'      => 'Pembaiki lencongan',

'brokenredirects'        => 'Lencongan rosak',
'brokenredirectstext'    => 'Lencongan-lencongan berikut menuju ke laman yang tidak wujud:',
'brokenredirects-edit'   => 'sunting',
'brokenredirects-delete' => 'hapus',

'withoutinterwiki'         => 'Laman tanpa pautan bahasa',
'withoutinterwiki-summary' => 'Laman-laman berikut tidak mempunyai pautan ke versi bahasa lain:',
'withoutinterwiki-legend'  => 'Awalan',
'withoutinterwiki-submit'  => 'Tunjuk',

'fewestrevisions' => 'Laman dengan semakan tersedikit',

# Miscellaneous special pages
'nbytes'                  => '{{PLURAL:$1|$1 bait}}',
'ncategories'             => '$1 kategori',
'nlinks'                  => '$1 pautan',
'nmembers'                => '$1 ahli',
'nrevisions'              => '$1 semakan',
'nviews'                  => 'Dilihat $1 kali',
'specialpage-empty'       => 'Tiada keputusan bagi laporan ini.',
'lonelypages'             => 'Laman yatim',
'lonelypagestext'         => 'Laman-laman berikut tidak dipaut atau disertakan dari laman lain dalam {{SITENAME}}.',
'uncategorizedpages'      => 'Laman tanpa kategori',
'uncategorizedcategories' => 'Kategori tanpa kategori',
'uncategorizedimages'     => 'Imej tanpa kategori',
'uncategorizedtemplates'  => 'Templat tanpa kategori',
'unusedcategories'        => 'Kategori tidak digunakan',
'unusedimages'            => 'Imej tidak digunakan',
'popularpages'            => 'Laman popular',
'wantedcategories'        => 'Kategori dikehendaki',
'wantedpages'             => 'Laman dikehendaki',
'wantedpages-badtitle'    => 'Tajuk tidak sah dalam set keputusan: $1',
'wantedfiles'             => 'Fail dikehendaki',
'wantedtemplates'         => 'Templat dikehendaki',
'mostlinked'              => 'Laman dipaut terbanyak',
'mostlinkedcategories'    => 'Kategori dipaut terbanyak',
'mostlinkedtemplates'     => 'Templat dipaut terbanyak',
'mostcategories'          => 'Rencana dengan kategori terbanyak',
'mostimages'              => 'Imej dipaut terbanyak',
'mostrevisions'           => 'Rencana dengan semakan terbanyak',
'prefixindex'             => 'Indeks awalan',
'shortpages'              => 'Laman pendek',
'longpages'               => 'Laman panjang',
'deadendpages'            => 'Laman buntu',
'deadendpagestext'        => 'Laman-laman berikut tidak mengandungi pautan ke laman lain di {{SITENAME}}.',
'protectedpages'          => 'Laman dilindungi',
'protectedpages-indef'    => 'Perlindungan tanpa had sahaja',
'protectedpages-cascade'  => 'Perlindungan separa sahaja',
'protectedpagestext'      => 'Laman-laman berikut dilindungi daripada pemindahan dan penyuntingan',
'protectedpagesempty'     => 'Tiada laman yang dilindungi dengan kriteria ini.',
'protectedtitles'         => 'Tajuk dilindungi',
'protectedtitlestext'     => 'Tajuk-tajuk berikut dilindungi daripada dicipta',
'protectedtitlesempty'    => 'Tiada tajuk yang dilindungi yang sepadan dengan kriteria yang diberikan.',
'listusers'               => 'Senarai pengguna',
'listusers-editsonly'     => 'Hanya papar pengguna yang telah membuat suntingan',
'listusers-creationsort'  => 'Susun mengikut tarikh penciptaan',
'usereditcount'           => '$1 suntingan',
'usercreated'             => 'Dicipta pada $1, $2',
'newpages'                => 'Laman baru',
'newpages-username'       => 'Nama pengguna:',
'ancientpages'            => 'Laman lapuk',
'move'                    => 'Alih',
'movethispage'            => 'Pindahkan laman ini',
'unusedimagestext'        => 'Fail-fail berikut wujud tetapi tidak digunakan dalam mana-mana laman.
Sila ambil perhatian bahawa mungkin terdapat tapak web lain yang memaut ke fail ini menggunakan URL langsung, dan masih disenaraikan di sini walapun berada dalam kegunaan aktif.',
'unusedcategoriestext'    => 'Laman-laman kategori berikut wujud walaupun tiada laman atau kategori lain menggunakannya.',
'notargettitle'           => 'Tiada sasaran',
'notargettext'            => 'Anda tidak menyatakan laman atau pengguna sebagai sasaran bagi tindakan ini.',
'nopagetitle'             => 'Laman sasaran tidak wujud',
'nopagetext'              => 'Laman sasaran yang anda nyatakan tidak wujud.',
'pager-newer-n'           => '{{PLURAL:$1|$1 berikutnya}}',
'pager-older-n'           => '{{PLURAL:$1|$1 sebelumnya}}',
'suppress'                => 'Kawalan',

# Book sources
'booksources'               => 'Sumber buku',
'booksources-search-legend' => 'Cari sumber buku',
'booksources-go'            => 'Pergi',
'booksources-text'          => 'Yang berikut ialah senarai pautan ke tapak web lain yang menjual buku baru dan terpakai,
serta mungkin mempunyai maklumat lanjut mengenai buku yang anda cari:',
'booksources-invalid-isbn'  => 'ISBN yang dinyatakan tidak sah. Sila semak sekali lagi.',

# Special:Log
'specialloguserlabel'  => 'Pengguna:',
'speciallogtitlelabel' => 'Tajuk:',
'log'                  => 'Log',
'all-logs-page'        => 'Semua log awam',
'alllogstext'          => 'Yang berikut ialah gabungan bagi semua log yang ada bagi {{SITENAME}}. Anda boleh menapis senarai ini dengan memilih jenis log, nama pengguna (peka huruf besar), atau nama laman yang terjejas (juga peka huruf besar).',
'logempty'             => 'Tiada item yang sepadan dalam log.',
'log-title-wildcard'   => 'Cari semua tajuk yang bermula dengan teks ini',

# Special:AllPages
'allpages'          => 'Semua laman',
'alphaindexline'    => '$1 hingga $2',
'nextpage'          => 'Halaman berikutnya ($1)',
'prevpage'          => 'Halaman sebelumnya ($1)',
'allpagesfrom'      => 'Tunjukkan laman bermula pada:',
'allpagesto'        => 'Tunjukkan laman berakhir pada:',
'allarticles'       => 'Semua laman',
'allinnamespace'    => 'Semua laman (ruang nama $1)',
'allnotinnamespace' => 'Semua laman (bukan dalam ruang nama $1)',
'allpagesprev'      => 'Sebelumnya',
'allpagesnext'      => 'Berikutnya',
'allpagessubmit'    => 'Pergi',
'allpagesprefix'    => 'Tunjukkan laman dengan awalan:',
'allpagesbadtitle'  => 'Tajuk laman yang dinyatakan tidak sah atau mempunyai awalam antara bahasa atau antara wiki. Ia mungkin mengandungi aksara yang tidak boleh digunakan dalam tajuk laman.',
'allpages-bad-ns'   => '{{SITENAME}} tidak mempunyai ruang nama "$1".',

# Special:Categories
'categories'                    => 'Kategori',
'categoriespagetext'            => '{{PLURAL:$1|Kategori|Kategori-kategori}} berikut mengandungi laman-laman atau media.
[[Special:UnusedCategories|Kategori yang tidak digunakan]] tidak dipaparkan di sini.
Lihat juga [[Special:WantedCategories|kategori yang dikehendaki]].',
'categoriesfrom'                => 'Paparkan kategori bermula daripada:',
'special-categories-sort-count' => 'susun mengikut tertib bilangan',
'special-categories-sort-abc'   => 'susun mengikut tertib abjad',

# Special:DeletedContributions
'deletedcontributions'             => 'Sumbangan dihapuskan',
'deletedcontributions-title'       => 'Sumbangan dihapuskan',
'sp-deletedcontributions-contribs' => 'sumbangan',

# Special:LinkSearch
'linksearch'       => 'Pautan luar',
'linksearch-pat'   => 'Corak carian:',
'linksearch-ns'    => 'Ruang nama:',
'linksearch-ok'    => 'Cari',
'linksearch-text'  => 'Kad bebas seperti "*.wikipedia.org" dibenarkan.<br />
Protokol yang disokong: <tt>$1</tt>',
'linksearch-line'  => '$1 dipaut dari $2',
'linksearch-error' => 'Kad bebas hanya boleh digunakan pada permulaan nama hos.',

# Special:ListUsers
'listusersfrom'      => 'Tunjukkan pengguna bermula pada:',
'listusers-submit'   => 'Tunjuk',
'listusers-noresult' => 'Tiada pengguna dijumpai.',
'listusers-blocked'  => '(disekat)',

# Special:ActiveUsers
'activeusers'            => 'Senarai pengguna aktif',
'activeusers-intro'      => 'Yang berikut ialah senarai pengguna yang bergiat sejak {{PLURAL:$1|semalam|$1 hari lalu}}.',
'activeusers-count'      => '$1 {{PLURAL:$1|suntingan|suntingan}} sejak {{PLURAL:$3|semalam|$3 hari lalu}}',
'activeusers-from'       => 'Tunjukkan pengguna bermula pada:',
'activeusers-hidebots'   => 'Sembunyi bot',
'activeusers-hidesysops' => 'Sembunyi pentadbir',
'activeusers-noresult'   => 'Tiada pengguna dijumpai.',

# Special:Log/newusers
'newuserlogpage'              => 'Log akaun baru',
'newuserlogpagetext'          => 'Yang berikut ialah log penciptaan pengguna.',
'newuserlog-byemail'          => 'kata laluan dihantar melalui e-mel',
'newuserlog-create-entry'     => 'Pengguna baru',
'newuserlog-create2-entry'    => 'membuka akaun $1',
'newuserlog-autocreate-entry' => 'Akaun dibuka secara automatik',

# Special:ListGroupRights
'listgrouprights'                      => 'Hak kumpulan pengguna',
'listgrouprights-summary'              => 'Yang berikut ialah senarai kumpulan pengguna yang ditubuhkan di wiki ini dengan hak-hak masing-masing.
Anda boleh mengetahui [[{{MediaWiki:Listgrouprights-helppage}}|maklumat tambahan]] mengenai setiap hak.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Hak ditunaikan</span>
* <span class="listgrouprights-revoked">Hak dibatalkan</span>',
'listgrouprights-group'                => 'Kumpulan',
'listgrouprights-rights'               => 'Hak',
'listgrouprights-helppage'             => 'Help:Hak kumpulan',
'listgrouprights-members'              => '(senarai ahli)',
'listgrouprights-addgroup'             => 'Tambah {{PLURAL:$2|kumpulan|kumpulan}}: $1',
'listgrouprights-removegroup'          => 'Buang {{PLURAL:$2|kumpulan|kumpulan}}: $1',
'listgrouprights-addgroup-all'         => 'Boleh menambah semua kumpulan',
'listgrouprights-removegroup-all'      => 'Boleh membuang semua kumpulan',
'listgrouprights-addgroup-self'        => 'Menyertai {{PLURAL:$2|kumpulan|kumpulan-kumpulan}}: $1',
'listgrouprights-removegroup-self'     => 'Keluar daripada {{PLURAL:$2|kumpulan|kumpulan-kumpulan}}: $1',
'listgrouprights-addgroup-self-all'    => 'Menyertai semua kumpulan',
'listgrouprights-removegroup-self-all' => 'Keluar daripada semua kumpulan',

# E-mail user
'mailnologin'          => 'Tiada alamat e-mel',
'mailnologintext'      => 'Anda perlu [[Special:UserLogin|log masuk]]
terlebih dahulu dan mempunyai alamat e-mel yang sah dalam
[[Special:Preferences|laman keutamaan]] untuk mengirim e-mel kepada pengguna lain.',
'emailuser'            => 'Kirim e-mel kepada pengguna ini',
'emailpage'            => 'E-mel pengguna',
'emailpagetext'        => 'Gunakan borang berikut untuk mengirim pesanan e-mel kepada pengguna ini.

Alamat e-mel yang ditetapkan dalam [[Special:Preferences|keutamaan anda]] akan digunakan sebagai alamat "Daripada" dalam e-mel tersebut supaya si penerima boleh membalasnya.',
'usermailererror'      => 'Objek Mail memulangkan ralat:',
'defemailsubject'      => 'E-mel {{SITENAME}}',
'usermaildisabled'     => 'E-mel pengguna telah dilumpuhkan',
'usermaildisabledtext' => 'Anda tidak boleh mengirim e-mel kepada pengguna lain di wiki ini',
'noemailtitle'         => 'Tiada alamat e-mel',
'noemailtext'          => 'Pengguna ini tidak menetapkan alamat e-mel yang sah.',
'nowikiemailtitle'     => 'E-mel tidak dibenarkan',
'nowikiemailtext'      => 'Pengguna ini tidak mahu menerima e-mel daripada pengguna lain.',
'email-legend'         => 'Kirim e-mel kepada pengguna {{SITENAME}} lain',
'emailfrom'            => 'Daripada:',
'emailto'              => 'Kepada:',
'emailsubject'         => 'Perkara:',
'emailmessage'         => 'Pesanan:',
'emailsend'            => 'Kirim',
'emailccme'            => 'Kirim salinan mesej ini kepada saya.',
'emailccsubject'       => 'Salinan bagi mesej anda kepada $1: $2',
'emailsent'            => 'E-mel dikirim',
'emailsenttext'        => 'E-mel anda telah dikirim.',
'emailuserfooter'      => 'E-mel ini telah dikirim oleh $1 kepada $2 menggunakan alat "E-mel pengguna" di {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Meninggalkan pesanan sistem.',
'usermessage-editor'  => 'Utusan sistem',

# Watchlist
'watchlist'            => 'Senarai pantau',
'mywatchlist'          => 'Senarai pantau saya',
'watchlistfor2'        => 'Bagi $1 $2',
'nowatchlist'          => 'Tiada item dalam senarai pantau anda.',
'watchlistanontext'    => 'Sila $1 terlebih dahulu untuk melihat atau menyunting senarai pantau anda.',
'watchnologin'         => 'Belum log masuk',
'watchnologintext'     => 'Anda mesti [[Special:UserLogin|log masuk]] terlebih dahulu untuk mengubah senarai pantau.',
'addedwatch'           => 'Senarai pantau dikemaskinikan',
'addedwatchtext'       => "Laman \"[[:\$1]]\" telah ditambahkan ke dalam [[Special:Watchlist|senarai pantau]] anda.
Semua perubahan bagi laman tersebut dan laman perbincangannya akan disenaraikan di sana,
dan tajuk laman tersebut juga akan ditonjolkan dalam '''teks tebal''' di [[Special:RecentChanges|senarai perubahan terkini]]
untuk memudahkan anda.

Jika anda mahu membuang laman tersebut daripada senarai pantau, klik \"Nyahpantau\" pada bar sisi.",
'removedwatch'         => 'Dibuang daripada senarai pantau',
'removedwatchtext'     => 'Laman "[[:$1]]" telah dibuang daripada [[Special:Watchlist|senarai pantau anda]].',
'watch'                => 'Pantau',
'watchthispage'        => 'Pantau laman ini',
'unwatch'              => 'Nyahpantau',
'unwatchthispage'      => 'Berhenti memantau',
'notanarticle'         => 'Bukan laman kandungan',
'notvisiblerev'        => 'Semakan ini telah dihapuskan',
'watchnochange'        => 'Tiada perubahan pada laman-laman yang dipantau dalam tempoh yang ditunjukkan.',
'watchlist-details'    => '$1 laman dipantau (tidak termasuk laman perbincangan).',
'wlheader-enotif'      => '* Pemberitahuan melalui e-mel diaktifkan.',
'wlheader-showupdated' => "* Laman-laman yang telah diubah sejak kunjungan terakhir anda dipaparkan dalam '''teks tebal'''",
'watchmethod-recent'   => 'menyemak laman yang dipantau dalam suntingan-suntingan terkini',
'watchmethod-list'     => 'menyemak suntingan terkini pada laman-laman yang dipantau',
'watchlistcontains'    => 'Terdapat $1 laman dalam senarai pantau anda.',
'iteminvalidname'      => "Terdapat masalah dengan item '$1', nama tidak sah...",
'wlnote'               => "Yang berikut ialah '''$1''' perubahan terakhir sejak '''$2''' jam yang lalu.",
'wlshowlast'           => 'Tunjukkan $1 jam / $2 hari yang lalu / $3.',
'watchlist-options'    => 'Pilihan senarai pantau',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Memantau...',
'unwatching' => 'Menyahpantau...',

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

Hubungi penyunting tersebut:
mel: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Tiada pemberitahuan lain akan dikirim berkaitan perubahan selanjutnya melainkan anda mengunjungi laman tersebut.
Anda juga boleh menetapkan semula penanda pemberitahuan bagi semua laman dalam senarai pantau anda.

         Sistem pemberitahuan {{SITENAME}} anda yang ramah

--
Untuk mengubah tetapan senarai pantau anda, lawati
{{fullurl:{{#special:Watchlist}}/edit}}

Untuk memadam laman ini dari senarai pantau anda, lawati
$UNWATCHURL

Maklum balas dan bantuan:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Hapus laman',
'confirm'                => 'Sahkan',
'excontent'              => "kandungan: '$1'",
'excontentauthor'        => "Kandungan: '$1' (dan satu-satunya penyumbang ialah '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "kandungan sebelum pengosongan ialah: '$1'",
'exblank'                => 'laman tersebut kosong',
'delete-confirm'         => 'Hapus "$1"',
'delete-legend'          => 'Hapus',
'historywarning'         => "'''Amaran:''' Laman yang ingin anda hapuskan mengandungi sejarah dengan kira-kira $1 {{PLURAL:$1|semakan|semakan}}:",
'confirmdeletetext'      => 'Anda sudah hendak menghapuskan sebuah laman berserta semua sejarahnya.
Sila sahkan bahawa anda memang hendak berbuat demikian, anda faham akan
akibatnya, dan perbuatan anda mematuhi [[{{MediaWiki:Policy-url}}|dasar kami]].',
'actioncomplete'         => 'Tindakan berjaya',
'actionfailed'           => 'Tindakan gagal',
'deletedtext'            => '"<nowiki>$1</nowiki>" telah dihapuskan.
Sila lihat $2 untuk rekod penghapusan terkini.',
'deletedarticle'         => 'menghapuskan "[[$1]]"',
'suppressedarticle'      => 'menahan "[[$1]]"',
'dellogpage'             => 'Log penghapusan',
'dellogpagetext'         => 'Yang berikut ialah senarai penghapusan terkini.',
'deletionlog'            => 'log penghapusan',
'reverted'               => 'Dibalikkan kepada semakan sebelumnya',
'deletecomment'          => 'Sebab:',
'deleteotherreason'      => 'Sebab lain/tambahan:',
'deletereasonotherlist'  => 'Sebab lain',
'deletereason-dropdown'  => '* Sebab-sebab lazim
** Permintaan pengarang
** Melanggar hak cipta
** Vandalisme',
'delete-edit-reasonlist' => 'Ubah sebab-sebab hapus',
'delete-toobig'          => 'Laman ini mempunyai sejarah yang besar, iaitu melebihi $1 jumlah semakan. Oleh itu, laman ini dilindungi daripada dihapuskan untuk mengelak kerosakan di {{SITENAME}} yang tidak disengajakan.',
'delete-warning-toobig'  => 'Laman ini mempunyai sejarah yang besar, iaitu melebihi $1 jumlah semakan. Menghapuskannya boleh mengganggu perjalanan pangkalan data {{SITENAME}}. Sila berhati-hati.',

# Rollback
'rollback'          => 'Undurkan suntingan.',
'rollback_short'    => 'Undur',
'rollbacklink'      => 'undur',
'rollbackfailed'    => 'Pengunduran gagal',
'cantrollback'      => 'Suntingan tersebut tidak dapat dibalikkan: penyumbang terakhir adalah satu-satunya pengarang bagi rencana ini.',
'alreadyrolled'     => 'Suntingan terakhir bagi [[:$1]] oleh [[User:$2|$2]] ([[User talk:$2|Perbualan]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) tidak dapat dibalikkan; terdapat pengguna lain yang telah menyunting atau membalikkan laman itu.

Suntingan terakhir telah dibuat oleh [[User:$3|$3]] ([[User talk:$3|Perbualan]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Ringkasan sutingan: \"''\$1''\".",
'revertpage'        => 'Membalikkan suntingan oleh [[Special:Contributions/$2|$2]] ([[User talk:$2|Perbincangan]]) kepada versi terakhir oleh [[User:$1|$1]]',
'revertpage-nouser' => 'Membalikkan suntingan oleh (nama pengguna dibuang) kepada semakan terakhir oleh [[User:$1|$1]]',
'rollback-success'  => 'Membalikkan suntingan oleh $1 kepada versi terakhir oleh $2.',

# Edit tokens
'sessionfailure-title' => 'Kegagalan sesi',
'sessionfailure'       => 'Terdapat sedikit masalah pada sesi log masuk anda.
Tindakan ini telah dibatalkan untuk mencegah perampasan sesi.
Sila tekan butang "back" dan muatkan semula laman yang telah anda kunjungi sebelum ini, kemudian cuba lagi.',

# Protect
'protectlogpage'              => 'Log perlindungan',
'protectlogtext'              => 'Yang berikut ialah senarai bagi tindakan penguncian/pembukaan laman. Sila lihat [[Special:ProtectedPages|senarai laman dilindungi]] untuk rujukan lanjut.',
'protectedarticle'            => 'melindungi "[[$1]]"',
'modifiedarticleprotection'   => 'menukar peringkat perlindungan bagi "[[$1]]"',
'unprotectedarticle'          => 'menyahlindung "[[$1]]"',
'movedarticleprotection'      => 'memindahkan tetapan perlindungan dari "[[$2]]" ke "[[$1]]"',
'protect-title'               => 'Menetapkan peringkat perlindungan bagi "$1"',
'prot_1movedto2'              => '[[$1]] dipindahkan ke [[$2]]',
'protect-legend'              => 'Sahkan perlindungan',
'protectcomment'              => 'Sebab:',
'protectexpiry'               => 'Sehingga:',
'protect_expiry_invalid'      => 'Waktu tamat tidak sah.',
'protect_expiry_old'          => 'Waktu tamat telah berlalu.',
'protect-unchain-permissions' => 'Aktifkan pilihan perlindungan selanjutnya',
'protect-text'                => "Anda boleh melihat dan menukar peringkat perlindungan bagi laman '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Anda telah disekat, justeru tidak boleh menukar peringkat perlindungan.
Ini adalah tetapan semasa bagi laman '''$1''':",
'protect-locked-dblock'       => "Anda tidak boleh menukar peringkat perlindungan kerana pangkalan data sedang dikunci.
Ini adalah tetapan semasa bagi laman '''$1''':",
'protect-locked-access'       => "Anda tidak mempunyai keizinan untuk menukar peringkat perlindungan.
Ini adalah tetapan semasa bagi laman '''$1''':",
'protect-cascadeon'           => 'Laman ini dilindungi kerana ia terkandung dalam {{PLURAL:$1|laman|laman-laman}} berikut, yang dilindungi secara melata. Anda boleh menukar peringkat perlindunan laman ini, akan tetapi ia tidak akan menjejaskan perlindungan melata tersebut.',
'protect-default'             => 'Benarkan semua pengguna',
'protect-fallback'            => 'Perlukan keizinan "$1"',
'protect-level-autoconfirmed' => 'Sekat pengguna baru dan pengguna tidak berdaftar',
'protect-level-sysop'         => 'Penyelia sahaja',
'protect-summary-cascade'     => 'melata',
'protect-expiring'            => 'sehingga $1 (UTC)',
'protect-expiry-indefinite'   => 'tak terbatas',
'protect-cascade'             => 'Lindungi semua laman yang terkandung dalam laman ini (perlindungan melata)',
'protect-cantedit'            => 'Anda tidak dibenarkan menukar peringkat perlindungan bagi laman ini.',
'protect-othertime'           => 'Waktu lain:',
'protect-othertime-op'        => 'waktu lain',
'protect-existing-expiry'     => 'Waktu tamat yang telah ditetapkan: $2, $3',
'protect-otherreason'         => 'Sebab lain/tambahan:',
'protect-otherreason-op'      => 'Sebab lain',
'protect-dropdown'            => '*Sebab lazim
** Vandalisme
** Spam
** Perang sunting
** Laman popular',
'protect-edit-reasonlist'     => 'Ubah sebab-sebab perlindungan',
'protect-expiry-options'      => '1 jam:1 hour,1 hari:1 day,1 minggu:1 week,2 minggu:2 weeks,1 bulan:1 month,3 bulan:3 months,6 bulan:6 months,1 tahun:1 year,selama-lamanya:infinite',
'restriction-type'            => 'Keizinan:',
'restriction-level'           => 'Peringkat pembatasan:',
'minimum-size'                => 'Saiz minimum',
'maximum-size'                => 'Saiz maksimum',
'pagesize'                    => '(bait)',

# Restrictions (nouns)
'restriction-edit'   => 'Sunting',
'restriction-move'   => 'Pindah',
'restriction-create' => 'Cipta',
'restriction-upload' => 'Muat naik',

# Restriction levels
'restriction-level-sysop'         => 'perlindungan penuh',
'restriction-level-autoconfirmed' => 'perlindungan separa',
'restriction-level-all'           => 'semua peringkat',

# Undelete
'undelete'                     => 'Lihat laman yang dihapuskan',
'undeletepage'                 => 'Lihat dan pulihkan laman yang dihapuskan',
'undeletepagetitle'            => "'''Yang berikut ialah semakan-semakan [[:$1|$1]] yang telah dihapuskan'''.",
'viewdeletedpage'              => 'Lihat laman yang dihapuskan',
'undeletepagetext'             => '{{PLURAL:$1|Laman|$1 laman}} berikut telah dihapuskan tetapi masih disimpan dalam arkib dan masih boleh dipulihkan. Arkib tersebut akan dibersihkan dari semasa ke semasa.',
'undelete-fieldset-title'      => 'Pulihkan semakan',
'undeleteextrahelp'            => "Untuk memulihkan keseluruhan laman, biarkan semua kotak semak dan klik '''''Pulih'''''. Untuk melaksanakan pemulihan tertentu, tanda di setiap kotak yang bersebelahan dengan semakan untuk dipulihkan dan klik '''''Pulih'''''. Klik '''''Set semula''''' untuk mengosongkan ruangan komen dan membuang tanda semua kotak.",
'undeleterevisions'            => '$1 semakan telah diarkibkan.',
'undeletehistory'              => 'Jika anda memulihkan laman tersebut, semua semakan akan dipulihkan kepada sejarahnya. Jika sebuah laman baru yang mempunyai nama yang sama telah dicipta sejak penghapusan, semakan yang dipulihkan akan muncul dalam sejarah terdahulu.',
'undeleterevdel'               => 'Penyahhapusan tidak akan dilaksanakan sekiranya ia menyebabkan sebahagian semakan puncak dihapuskan.
Dalam hal tersebut, anda perlu membuang semak atau menyemak semakan yang baru dihapuskan. Semakan fail
yang anda tidak dibenarkan melihatnya tidak akan dipulihkan.',
'undeletehistorynoadmin'       => 'Rencana ini telah dihapuskan. Sebab penghapusan
ditunjukkan dalam ringkasan di bawah, berserta butiran bagi pengguna-pengguna yang telah menyunting laman ini
sebelum penghapusan. Teks sebenar bagi semua semakan yang dihapuskan hanya boleh dilihat oleh para pentadbir.',
'undelete-revision'            => 'Menghapuskan semakan bagi $1 (pada $4, $5) oleh $3:',
'undeleterevision-missing'     => 'Semakan tersebut tidak sah atau tidak dijumpai. Mungkin anda telah mengikuti pautan yang rosak
atau semakan tersebut telah dipulihkan atau dibuang daripada arkib.',
'undelete-nodiff'              => 'Tiada semakan sebelumnya.',
'undeletebtn'                  => 'Pulihkan',
'undeletelink'                 => 'lihat/pulih',
'undeleteviewlink'             => 'papar',
'undeletereset'                => 'set semula',
'undeleteinvert'               => 'Kecualikan pilihan',
'undeletecomment'              => 'Sebab:',
'undeletedarticle'             => '"[[$1]]" telah dipulihkan',
'undeletedrevisions'           => '$1 semakan dipulihkan',
'undeletedrevisions-files'     => '$1 semakan dan $2 fail dipulihkan',
'undeletedfiles'               => '$1 fail dipulihkan',
'cannotundelete'               => 'Penyahhapusan gagal; mungkin orang lain telah pun mengnyahhapuskannya.',
'undeletedpage'                => "'''$1 telah dipulihkan'''

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
'undelete-show-file-confirm'   => 'Betul anda mahu melihat semakan bagi fail "<nowiki>$1</nowiki>" yang telah dihapuskan pada $2, $3?',
'undelete-show-file-submit'    => 'Ya',

# Namespace form on various pages
'namespace'      => 'Ruang nama:',
'invert'         => 'Kecualikan pilihan',
'blanknamespace' => '(Utama)',

# Contributions
'contributions'       => 'Sumbangan',
'contributions-title' => 'Sumbangan oleh $1',
'mycontris'           => 'Sumbangan saya',
'contribsub2'         => 'Oleh $1 ($2)',
'nocontribs'          => 'Tiada sebarang perubahan yang sepadan dengan kriteria-kriteria ini.',
'uctop'               => ' (puncak)',
'month'               => 'Sebelum bulan:',
'year'                => 'Sebelum tahun:',

'sp-contributions-newbies'             => 'Tunjuk sumbangan daripada akaun baru sahaja',
'sp-contributions-newbies-sub'         => 'Bagi akaun-akaun baru',
'sp-contributions-newbies-title'       => 'Sumbangan oleh pengguna baru',
'sp-contributions-blocklog'            => 'Log sekatan',
'sp-contributions-deleted'             => 'sumbangan dihapuskan',
'sp-contributions-logs'                => 'log',
'sp-contributions-talk'                => 'perbincangan',
'sp-contributions-userrights'          => 'pengurusan hak pengguna',
'sp-contributions-blocked-notice'      => 'Pengguna ini sedang disekat. Masukan log sekatan terakhir disediakan di bawah sebagai rujukan:',
'sp-contributions-blocked-notice-anon' => 'Alamat IP ini sedang disekat. Masukan log sekatan terakhir disediakan di bawah sebagai rujukan:',
'sp-contributions-search'              => 'Cari sumbangan',
'sp-contributions-username'            => 'Alamat IP atau nama pengguna:',
'sp-contributions-toponly'             => 'Hanya paparkan suntingan yang merupakan semakan terkini',
'sp-contributions-submit'              => 'Cari',

# What links here
'whatlinkshere'            => 'Pautan ke laman ini',
'whatlinkshere-title'      => 'Laman yang mengandungi pautan ke "$1"',
'whatlinkshere-page'       => 'Laman:',
'linkshere'                => "Laman-laman berikut mengandungi pautan ke '''[[:$1]]''':",
'nolinkshere'              => "Tiada laman yang mengandungi pautan ke '''[[:$1]]'''.",
'nolinkshere-ns'           => "Tiada laman yang mengandungi pautan ke '''[[:$1]]''' dalam ruang nama yang dinyatakan.",
'isredirect'               => 'laman lencongan',
'istemplate'               => 'penyertaan',
'isimage'                  => 'pautan imej',
'whatlinkshere-prev'       => '{{PLURAL:$1|sebelumnya|$1 sebelumnya}}',
'whatlinkshere-next'       => '{{PLURAL:$1|berikutnya|$1 berikutnya}}',
'whatlinkshere-links'      => '← pautan',
'whatlinkshere-hideredirs' => '$1 pelencongan',
'whatlinkshere-hidetrans'  => '$1 penyertaan',
'whatlinkshere-hidelinks'  => '$1 pautan',
'whatlinkshere-hideimages' => '$1 pautan imej',
'whatlinkshere-filters'    => 'Tapis',

# Block/unblock
'blockip'                         => 'Sekat pengguna',
'blockip-title'                   => 'Sekat pengguna',
'blockip-legend'                  => 'Sekat pengguna',
'blockiptext'                     => 'Gunakan borang di bawah untuk menyekat
penyuntingan daripada alamat IP atau pengguna tertentu.
Tindakan ini perlu dilakukan untuk menentang vandalisme sahaja dan selaras
dengan [[{{MediaWiki:Policy-url}}|dasar {{SITENAME}}]].
Sila masukkan sebab sekatan di bawah (umpamannya, sebutkan laman yang telah
dirosakkan).',
'ipaddress'                       => 'Alamat IP:',
'ipadressorusername'              => 'Alamat IP atau nama pengguna:',
'ipbexpiry'                       => 'Tempoh:',
'ipbreason'                       => 'Sebab:',
'ipbreasonotherlist'              => 'Lain-lain',
'ipbreason-dropdown'              => '*Sebab lazim
** Memasukkan maklumat palsu
** Membuang kandungan daripada laman
** Memmasukkan pautan spam ke tapak web luar
** Memasukkan karut-marut ke dalam laman
** Mengugut/mengganggu pengguna lain
** Menyalahgunakan berbilang akaun
** Nama pengguna yang tidak sesuai',
'ipbanononly'                     => 'Sekat pengguna tanpa nama sahaja',
'ipbcreateaccount'                => 'Tegah pembukaan akaun',
'ipbemailban'                     => 'Halang pengguna tersebut daripada mengirim e-mel',
'ipbenableautoblock'              => 'Sekat alamat IP terakhir dan mana-mana alamat berikutnya yang digunakan oleh pengguna ini secara automatik',
'ipbsubmit'                       => 'Sekat pengguna ini',
'ipbother'                        => 'Waktu lain:',
'ipboptions'                      => '2 jam:2 hours,1 hari:1 day,3 hari:3 days,1 minggu:1 week,2 minggu:2 weeks,1 bulan:1 month,3 bulan:3 months,6 bulan:6 months,1 tahun:1 year,selama-lamanya:infinite',
'ipbotheroption'                  => 'lain',
'ipbotherreason'                  => 'Sebab tambahan/lain:',
'ipbhidename'                     => 'Sembunyikan nama pengguna daripada senarai suntingan dan pengguna',
'ipbwatchuser'                    => 'Pantau laman pengguna dan laman perbincangan bagi pengguna ini',
'ipballowusertalk'                => 'Benarkan pengguna ini menyunting laman perbincangannya sendiri ketika disekat',
'ipb-change-block'                => 'Sekat semula pengguna tersebut dengan tetapan ini',
'badipaddress'                    => 'Alamat IP tidak sah',
'blockipsuccesssub'               => 'Sekatan berjaya',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] telah disekat.
<br />Sila lihat [[Special:IPBlockList|senarai sekatan IP]] untuk maklumat lanjut.',
'ipb-edit-dropdown'               => 'Sunting sebab sekatan',
'ipb-unblock-addr'                => 'Nyahsekat $1',
'ipb-unblock'                     => 'Nyahsekat nama pengguna atau alamat IP',
'ipb-blocklist'                   => 'Lihat sekatan sedia ada',
'ipb-blocklist-contribs'          => 'Sumbangan oleh $1',
'unblockip'                       => 'Nyahsekat pengguna',
'unblockiptext'                   => 'Gunakan borang di bawah untuk membuang sekatan bagialamat IP atau nama pengguna yang telah disekat.',
'ipusubmit'                       => 'Tarik sekatan ini',
'unblocked'                       => '[[User:$1|$1]] telah dinyahsekat',
'unblocked-id'                    => 'Sekatan $1 telah dibuang',
'ipblocklist'                     => 'Alamat IP dan nama pengguna yang disekat',
'ipblocklist-legend'              => 'Cari pengguna yang disekat',
'ipblocklist-username'            => 'Nama pengguna atau alamat IP:',
'ipblocklist-sh-userblocks'       => '$1 sekatan akaun',
'ipblocklist-sh-tempblocks'       => '$1 sekatan sementara',
'ipblocklist-sh-addressblocks'    => '$1 sekatan IP tunggal',
'ipblocklist-submit'              => 'Cari',
'ipblocklist-localblock'          => 'Sekatan tempatan',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Sekatan|Sekatan-sekatan}} lain',
'blocklistline'                   => '$1, $2 menyekat $3 ($4)',
'infiniteblock'                   => 'selama-lamanya',
'expiringblock'                   => 'sehingga $1 pada $2',
'anononlyblock'                   => 'pengguna tanpa nama sahaja',
'noautoblockblock'                => 'sekatan automatik dipadamkan',
'createaccountblock'              => 'pembukaan akaun baru disekat',
'emailblock'                      => 'e-mail disekat',
'blocklist-nousertalk'            => 'tidak boleh menyunting laman perbincangan sendiri',
'ipblocklist-empty'               => 'Senarai sekatan adalah kosong.',
'ipblocklist-no-results'          => 'Alamat IP atau nama pengguna tersebut tidak disekat.',
'blocklink'                       => 'sekat',
'unblocklink'                     => 'nyahsekat',
'change-blocklink'                => 'ubah sekatan',
'contribslink'                    => 'sumb.',
'autoblocker'                     => 'Disekat secara automatik kerana baru-baru ini alamat IP anda digunakan oleh "[[User:$1|$1]]". Sebab sekatan $1 ialah: "$2"',
'blocklogpage'                    => 'Log sekatan',
'blocklog-showlog'                => 'Pengguna ini pernah disekat sebelum ini. Log sekatan disediakan di bawah sebagai rujukan:',
'blocklog-showsuppresslog'        => 'Pengguna ini pernah disekat dan tersembunyi sebelum ini.
Log sekatan disediakan di bawah sebagai rujukan:',
'blocklogentry'                   => 'menyekat [[$1]] sehingga $2 $3',
'reblock-logentry'                => 'menukar tetapan sekatan [[$1]] yang tamat pada $2 $3',
'blocklogtext'                    => 'Ini adalah log bagi sekatan dan penyahsekatan.
Alamat IP yang disekat secara automatik tidak disenaraikan di sini.
Sila lihat juga [[Special:IPBlockList|senarai sekatan IP]] yang sedang berkuatkuasa.',
'unblocklogentry'                 => 'menyahsekat $1',
'block-log-flags-anononly'        => 'pengguna tanpa nama sahaja',
'block-log-flags-nocreate'        => 'pembukaan akaun dimatikan',
'block-log-flags-noautoblock'     => 'sekatan automatik dimatikan',
'block-log-flags-noemail'         => 'e-mail disekat',
'block-log-flags-nousertalk'      => 'tidak boleh menyunting laman perbincangan sendiri',
'block-log-flags-angry-autoblock' => 'sekatan automatik tambahan diaktifkan',
'block-log-flags-hiddenname'      => 'nama pengguna tersembunyi',
'range_block_disabled'            => 'Kebolehan penyelia untuk membuat sekatan julat dimatikan.',
'ipb_expiry_invalid'              => 'Waktu tamat tidak sah.',
'ipb_expiry_temp'                 => 'Sekatan nama pengguna terselindung sepatutnya kekal.',
'ipb_hide_invalid'                => 'Tidak dapat menahan akaun ini; ia mungkin mempunyai terlalu banyak suntingan.',
'ipb_already_blocked'             => '"$1" telah pun disekat',
'ipb-needreblock'                 => '== Telah pun disekat ==
$1 telah pun disekat Adakah anda mahu menukar tetapan sekatan pengguna ini?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|Sekatan|Sekatan-sekatan}} lain',
'ipb_cant_unblock'                => 'Ralat: ID sekatan $1 tidak dijumpai. Barangkali ia telah pun dinyahsekat.',
'ipb_blocked_as_range'            => 'Ralat: IP $1 tidak boleh dinyahsekat kerana ia tidak disekat secara langsung. Sebaliknya, ia disekat kerana merupakan sebahagian daripada sekatan julat $2, yang mana boleh dinyahsekat.',
'ip_range_invalid'                => 'Julat IP tidak sah.',
'ip_range_toolarge'               => 'Sekatan julat yang lebih luas daripada /$1 adalah tidak dibenarkan.',
'blockme'                         => 'Sekat saya',
'proxyblocker'                    => 'Sekatan proksi',
'proxyblocker-disabled'           => 'Fungsi ini dimatikan.',
'proxyblockreason'                => 'Alamat IP anda telah disekat kerana ia merupakan proksi terbuka. Sila hubungi penyedia perkhidmatan Internet anda atau pihak sokongan teknikal dan beritahu mereka mengenai masalah berat ini.',
'proxyblocksuccess'               => 'Berjaya.',
'sorbsreason'                     => 'Alamat IP anda telah disenaraikan sebagai proksi terbuka dalam DNSBL yang digunakan oleh {{SITENAME}}.',
'sorbs_create_account_reason'     => 'Alamat IP anda telah disenaraikan sebagai proksi terbuka dalam DNSBL yang digunakan oleh {{SITENAME}}. Oleh itu, anda tidak dibenarkan membuka akaun baru.',
'cant-block-while-blocked'        => 'Anda tidak boleh menyekat orang lain sedangkan anda disekat.',
'cant-see-hidden-user'            => 'Pengguna yang anda cuba sekat telahpun disekat dan tersembunyi.
Disebabkan anda tidak mempunyai hak sembunyi pengguna, anda tidak boleh melihat atau menyunting sekatan pengguna tersebut.',
'ipbblocked'                      => 'Anda tidak boleh menyekat atau menyahsekat pengguna lain kerana anda sendiri telah disekat',
'ipbnounblockself'                => 'Anda tidak dibenarkan menyahsekat diri sendiri',

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
<br />Pastikan anda [[Special:UnlockDB|membukanya semula]] selepas penyelenggaraan selesai.',
'unlockdbsuccesstext' => 'Kunci pangkalan data {{SITENAME}} telah dibuka.',
'lockfilenotwritable' => 'Fail kunci pangkalan data tidak boleh ditulis. Untuk mengunci atau membuka kunci pangkalan data, fail ini perlu diubah suai supaya boleh ditulis oleh pelayan web ini.',
'databasenotlocked'   => 'Pangkalan data tidak dikunci.',

# Move page
'move-page'                    => 'Pindah $1',
'move-page-legend'             => 'Pindah laman',
'movepagetext'                 => "Gunakan borang di bawah untuk menukar nama laman dan memindahkan semua maklumat sejarahnya ke nama baru. Tajuk yang lama akan dijadikan lencongan ke tajuk yang baru. Anda juga boleh mengemaskinikan semua lencongan yang menuju ke tajuk asal supaya menuju ke tajuk baru. Sebaliknya, anda boleh menyemak sekiranya terdapat [[Special:DoubleRedirects|lencongan berganda]] atau [[Special:BrokenRedirects|lencongan rosak]]. Anda bertanggungjawab memastikan semua pautan bersambung ke laman yang sepatutnya.

Sila ambil perhatian bahawa laman tersebut '''tidak''' akan dipindahkan sekiranya laman dengan tajuk yang baru tadi telah wujud, melainkan apabila
laman tersebut kosong atau merupakan laman lencongan dan tidak mempunyai sejarah penyuntingan. Ini bermakna anda boleh menukar semula nama sesebuah
laman kepada nama yang asal jika anda telah melakukan kesilapan, dan anda tidak boleh menulis ganti laman yang telah wujud.

'''AMARAN!''' Tindakan ini boleh menjadi perubahan yang tidak dijangka dan drastik bagi laman popular. Oleh itu, sila pastikan anda faham akibat yang mungkin timbul sebelum meneruskannya.",
'movepagetalktext'             => "Laman perbincangan yang berkaitan, jika ada, akan dipindahkan bersama-sama laman ini secara automatik '''kecuali''':
* Sebuah laman perbincangan dengan nama baru telah pun wujud, atau
* Anda membuang tanda kotak di bawah.

Dalam kes tersebut, anda terpaksa melencongkan atau menggabungkan laman secara manual, jika perlu.",
'movearticle'                  => 'Pindah laman:',
'moveuserpage-warning'         => "'''Amaran:''' Anda sudah hendak memindahkan suatu laman pengguna. Sila ambil perhatian bahawa hanya laman tersebut akan dipindahkan dan nama pengguna yang berkenaan ''tidak'' berubah.",
'movenologin'                  => 'Belum log masuk.',
'movenologintext'              => 'Anda mesti [[Special:UserLogin|log masuk]] terlebih dahulu untuk memindahkan laman.',
'movenotallowed'               => 'Anda tidak mempunyai keizinan untuk memindahkan laman.',
'movenotallowedfile'           => 'Anda tidak mempunyai keizinan untuk memindahkan fail.',
'cant-move-user-page'          => 'Anda tidak mempunyai keizinan untuk memindahkan laman pengguna (tidak termasuk sublaman-sublamannya).',
'cant-move-to-user-page'       => 'Anda tidak mempunyai keizinan untuk memindahkan sesebuah laman ke mana-mana laman pengguna (kecuali sebagai sublamannya sahaja).',
'newtitle'                     => 'Kepada tajuk baru:',
'move-watch'                   => 'Pantau laman ini',
'movepagebtn'                  => 'Pindah laman',
'pagemovedsub'                 => 'Pemindahan berjaya',
'movepage-moved'               => '\'\'\'"$1" telah dipindahkan ke "$2"\'\'\'',
'movepage-moved-redirect'      => 'Sebuah lencongan telah dicipta.',
'movepage-moved-noredirect'    => 'Penciptaan lencongan telah dihalang.',
'articleexists'                => 'Laman dengan nama tersebut telah pun wujud,
atau nama yang anda pilih tidak sah.
Sila pilih nama lain.',
'cantmove-titleprotected'      => 'Anda tidak boleh memindah sebarang laman ke sini kerana tajuk ini telah dilindungi daripada dicipta',
'talkexists'                   => "'''Laman tersebut berjaya dipindahkan, akan tetapi laman perbincangannya tidak dapat dipindahkan kerana laman dengan tajuk baru tersebut telah pun wujud. Anda perlu menggabungkannya secara manual.'''",
'movedto'                      => 'dipindahkan ke',
'movetalk'                     => 'Pindahkan laman perbincangan yang berkaitan',
'move-subpages'                => 'Pindahkan semua sublaman sekali (sehingga $1)',
'move-talk-subpages'           => 'Pindahkan semua sublaman bagi laman perbincangan sekali (sehingga $1)',
'movepage-page-exists'         => 'Laman $1 telah pun wujud dan tidak boleh ditulis ganti secara automatik.',
'movepage-page-moved'          => 'Laman $1 telah dipindahkan ke $2.',
'movepage-page-unmoved'        => 'Laman $1 tidak dapat dipindahkan ke $2.',
'movepage-max-pages'           => 'Jumlah maksimum $1 laman telah dipindahkan secara automatik.',
'1movedto2'                    => '[[$1]] dipindahkan ke [[$2]]',
'1movedto2_redir'              => '[[$1]] dipindahkan ke [[$2]] menerusi pelencongan',
'move-redirect-suppressed'     => 'halang pelencongan',
'movelogpage'                  => 'Log pemindahan',
'movelogpagetext'              => 'Yang berikut ialah senarai pemindahan laman.',
'movesubpage'                  => '{{PLURAL:$1|Sublaman|Sublaman}}',
'movesubpagetext'              => 'Laman ini mempunyai $1 sublaman berikut.',
'movenosubpage'                => 'Laman ini tidak mempunyai sublaman.',
'movereason'                   => 'Sebab:',
'revertmove'                   => 'balik',
'delete_and_move'              => 'Hapus dan pindah',
'delete_and_move_text'         => '==Penghapusan diperlukan==

Laman destinasi "[[:$1]]" telah pun wujud. Adakah anda mahu menghapuskannya supaya laman ini dapat dipindahkan?',
'delete_and_move_confirm'      => 'Ya, hapuskan laman ini',
'delete_and_move_reason'       => 'Dihapuskan supaya laman lain dapat dipindahkan',
'selfmove'                     => 'Tajuk sumber dan tajuk destinasi tidak boleh sama.',
'immobile-source-namespace'    => 'Anda tidak boleh memindahkan laman dari ruang nama "$1"',
'immobile-target-namespace'    => 'Anda tidak boleh memindahkan mana-mana laman ke dalam ruang nama "$1"',
'immobile-target-namespace-iw' => 'Pautan interwiki tidak boleh dijadikan sasaran untuk pemindahan laman.',
'immobile-source-page'         => 'Anda tidak boleh memindahkan laman ini.',
'immobile-target-page'         => 'Anda tidak boleh memindahkan laman ke tajuk itu.',
'imagenocrossnamespace'        => 'Tidak boleh memindah fail ke ruang nama lain',
'nonfile-cannot-move-to-file'  => 'Laman bukan fail tidak boleh dipindahkan ke ruang nama fail',
'imagetypemismatch'            => 'Sambungan baru fail tersebut tidak sepadan dengan jenisnya',
'imageinvalidfilename'         => 'Nama fail imej sasaran tidak sah',
'fix-double-redirects'         => 'Kemas kinikan semua lencongan yang menuju ke tajuk asal',
'move-leave-redirect'          => 'Lencongkan ke tajuk baru',
'protectedpagemovewarning'     => "'''Amaran:''' Laman ini telah dikunci supaya hanya mereka yang mempunyai keistimewaan penyelia boleh mengalihkannya.
Masukan log terakhir ditunjukkan di bawah untuk rujukan:",
'semiprotectedpagemovewarning' => "'''Nota:''' Laman ini telah dikunci agar hanya pengguna berdaftar sahaja boleh memindahkannya.
Masukan log terakhir ditunjukkan di bawah untuk rujukan:",
'move-over-sharedrepo'         => '== Fail wujud ==
[[:$1]] telah wujud di gedung kongsi. Fail yang menggunakan tajuk ini akan mengatasi fail di gedung kongsi.',
'file-exists-sharedrepo'       => 'Nama fail yang dipilih telah pun digunakan dalam gedung kongsi. Sila pilih nama lain.',

# Export
'export'            => 'Eksport laman',
'exporttext'        => 'Anda boleh mengeksport teks dan sejarah suntingan untuk laman-laman tertentu yang ke dalam fail XML.
Fail ini boleh diimport ke dalam wiki lain yang menggunakan perisian MediaWiki melalui [[Special:Import|laman import]].

Untuk mengeksport laman, masukkan tajuk dalam kotak teks di bawah (satu tajuk bagi setiap baris) dan pilih sama ada anda mahukan semua versi dan catatan sejarah atau hanya versi semasa berserta maklumat mengenai suntingan terakhir.

Dalam pilihan kedua tadi, anda juga boleh menggunakan pautan, umpamanya [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] untuk laman "[[{{MediaWiki:Mainpage}}]]".',
'exportcuronly'     => 'Hanya eksport semakan semasa, bukan keseluruhan sejarah.',
'exportnohistory'   => "----
'''Catatan:''' Ciri eksport sejarah penuh laman melalui borang ini telah dimatikan atas sebab-sebab prestasi.",
'export-submit'     => 'Eksport',
'export-addcattext' => 'Tambah laman daripada kategori:',
'export-addcat'     => 'Tambah',
'export-addnstext'  => 'Tambah laman dari ruang nama:',
'export-addns'      => 'Tambah',
'export-download'   => 'Simpan sebagai fail',
'export-templates'  => 'Sertakan templat',
'export-pagelinks'  => 'Sertakan laman-laman yang dipaut sedalam:',

# Namespace 8 related
'allmessages'                   => 'Pesanan sistem',
'allmessagesname'               => 'Nama',
'allmessagesdefault'            => 'Teks lalai',
'allmessagescurrent'            => 'Teks semasa',
'allmessagestext'               => 'Ini ialah senarai pesanan sistem yang terdapat dalam ruang nama MediaWiki.
Sila lawat [http://www.mediawiki.org/wiki/Localisation Penyetempatan MediaWiki] dan [http://translatewiki.net translatewiki.net] sekiranya anda mahu menyumbang dalam menyetempatkan dan menterjemah perisian MediaWiki.',
'allmessagesnotsupportedDB'     => "'''{{ns:special}}:Allmessages''' tidak boleh digunakan kerana '''\$wgUseDatabaseMessages''' dipadamkan.",
'allmessages-filter-legend'     => 'Penapisan',
'allmessages-filter'            => 'Tapis berdasarkan keadaan penempahan:',
'allmessages-filter-unmodified' => 'Tidak diubah',
'allmessages-filter-all'        => 'Semua',
'allmessages-filter-modified'   => 'Diubah',
'allmessages-prefix'            => 'Tapis berdasarkan awalan:',
'allmessages-language'          => 'Bahasa:',
'allmessages-filter-submit'     => 'Pergi',

# Thumbnails
'thumbnail-more'           => 'Besarkan',
'filemissing'              => 'Fail hilang',
'thumbnail_error'          => 'Berlaku ralat ketika mencipta imej ringkas: $1',
'djvu_page_error'          => 'Laman DjVu di luar julat',
'djvu_no_xml'              => 'Gagal mendapatkan data XML bagi fail DjVu',
'thumbnail_invalid_params' => 'Parameter imej ringkas tidak sah',
'thumbnail_dest_directory' => 'Direktori destinasi gagal diciptakans',
'thumbnail_image-type'     => 'Jenis imej tidak disokong',
'thumbnail_gd-library'     => 'Tatarajah perpustakaan GD tidak lengkap: kehilangan fungsi $1',
'thumbnail_image-missing'  => 'Fail hilang: $1',

# Special:Import
'import'                     => 'Import laman',
'importinterwiki'            => 'Import transwiki',
'import-interwiki-text'      => 'Sila pilih wiki dan tajuk laman yang ingin diimport.
Semua tarikh semakan dan nama penyunting akan dikekalkan.
Semua tindakan import transwiki dicatatkan dalam [[Special:Log/import|log import]].',
'import-interwiki-source'    => 'Sumber wiki/halaman:',
'import-interwiki-history'   => 'Salin semua versi sejarah bagi laman ini',
'import-interwiki-templates' => 'Sertakan semua templat',
'import-interwiki-submit'    => 'Import',
'import-interwiki-namespace' => 'Ruang nama destinasi:',
'import-upload-filename'     => 'Nama fail:',
'import-comment'             => 'Komen:',
'importtext'                 => 'Sila eksport fail daripada sumber wiki menggunakan kemudahan Special:Export, simpan dalam komputer anda dan muat naik di sini.',
'importstart'                => 'Mengimport laman...',
'import-revision-count'      => '$1 semakan',
'importnopages'              => 'Tiada laman untuk diimport.',
'imported-log-entries'       => '$1 {{PLURAL:$1|entri log|entri log}} telah diimport.',
'importfailed'               => 'Import gagal: $1',
'importunknownsource'        => 'Jenis sumber import tidak dikenali',
'importcantopen'             => 'Fail import tidak dapat dibuka',
'importbadinterwiki'         => 'Pautan antara wiki rosak',
'importnotext'               => 'Kosong atau tiada teks',
'importsuccess'              => 'Import selesai!',
'importhistoryconflict'      => 'Terdapat percanggahan semakan sejarah (mungkin laman ini pernah diimport sebelum ini)',
'importnosources'            => 'Tiada sumber import transwiki ditentunkan dan ciri muat naik sejarah secara terus dimatikan.',
'importnofile'               => 'Tiada fail import dimuat naik.',
'importuploaderrorsize'      => 'Fail import tidak dapat dimuat naik kerana melebihi had muat naik yang dibenarkan.',
'importuploaderrorpartial'   => 'Fail import tidak dapat dimuat naik kerana tidak dimuat naik sampai habis.',
'importuploaderrortemp'      => 'Fail import tidak dapat dimuat naik kerana tiada direktori sementara.',
'import-parse-failure'       => 'Gagal menghurai fail XML yang diimport',
'import-noarticle'           => 'Tiada laman untuk diimport!',
'import-nonewrevisions'      => 'Semua semakan telah pun diimport sebelum ini.',
'xml-error-string'           => '$1 pada baris $2, lajur $3 (bait $4): $5',
'import-upload'              => 'Muat naik data XML',
'import-token-mismatch'      => 'Data sesi telah hilang. Sila cuba lagi.',
'import-invalid-interwiki'   => 'Wiki yang dinyatakan tidak boleh diimport.',

# Import log
'importlogpage'                    => 'Log import',
'importlogpagetext'                => 'Senarai tindakan import laman dengan keseluruhan sejarah suntingannya daripada wiki lain.',
'import-logentry-upload'           => 'mengimport [[$1]] dengan memuat naik fail',
'import-logentry-upload-detail'    => '$1 semakan',
'import-logentry-interwiki'        => '$1 dipindahkan ke wiki lain',
'import-logentry-interwiki-detail' => '$1 semakan daripada $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Laman pengguna anda',
'tooltip-pt-anonuserpage'         => 'Laman pengguna bagi alamat IP anda',
'tooltip-pt-mytalk'               => 'Laman perbualan anda',
'tooltip-pt-anontalk'             => 'Perbincangan mengenai penyuntingan daripada alamat IP anda',
'tooltip-pt-preferences'          => 'Keutamaan saya',
'tooltip-pt-watchlist'            => 'Senarai laman yang anda pantau',
'tooltip-pt-mycontris'            => 'Senarai sumbangan anda',
'tooltip-pt-login'                => 'Walaupun tidak wajib, anda digalakkan supaya log masuk.',
'tooltip-pt-anonlogin'            => 'Walaupun tidak wajib, anda digalakkan supaya log masuk.',
'tooltip-pt-logout'               => 'Log keluar',
'tooltip-ca-talk'                 => 'Perbincangan mengenai laman kandungan',
'tooltip-ca-edit'                 => 'Anda boleh menyunting laman ini. Sila lihat pratonton terlebih dahulu sebelum menyimpan.',
'tooltip-ca-addsection'           => 'Buka bahagian baru',
'tooltip-ca-viewsource'           => 'Laman ini dilindungi. Anda boleh melihat sumbernya.',
'tooltip-ca-history'              => 'Versi-versi terdahulu bagi laman ini.',
'tooltip-ca-protect'              => 'Lindungi laman ini',
'tooltip-ca-unprotect'            => 'Nyahlindung laman ini',
'tooltip-ca-delete'               => 'Hapuskan laman ini',
'tooltip-ca-undelete'             => 'Balikkan suntingan yang dilakukan kepada laman ini sebelum ia dihapuskan',
'tooltip-ca-move'                 => 'Pindahkan laman ini',
'tooltip-ca-watch'                => 'Tambahkan laman ini ke dalam senarai pantau anda',
'tooltip-ca-unwatch'              => 'Buang laman ini daripada senarai pantau anda',
'tooltip-search'                  => 'Cari dalam {{SITENAME}}',
'tooltip-search-go'               => 'Pergi ke laman dengan nama tepat ini, jika ada',
'tooltip-search-fulltext'         => 'Cari laman dengan teks ini',
'tooltip-p-logo'                  => 'Laman Utama',
'tooltip-n-mainpage'              => 'Kunjungi Laman Utama',
'tooltip-n-mainpage-description'  => 'Kunjungi laman utama',
'tooltip-n-portal'                => 'Maklumat mengenai projek ini',
'tooltip-n-currentevents'         => 'Cari maklumat latar belakang mengenai peristiwa semasa',
'tooltip-n-recentchanges'         => 'Senarai perubahan terkini dalam wiki ini.',
'tooltip-n-randompage'            => 'Buka laman rawak',
'tooltip-n-help'                  => 'Tempat mencari jawapan.',
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
'tooltip-ca-nstab-mediawiki'      => 'Lihat pesanan sistem',
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
'tooltip-rollback'                => 'Balikkan semua suntingan oleh penyumbang terakhir pada laman ini dengan satu klik.',
'tooltip-undo'                    => 'Balikkan suntingan ini dan buka borang sunting dalam mod pratonton. Sebab boleh dinyatakan dalam ruangan ringkasan.',
'tooltip-preferences-save'        => 'Simpan keutamaan',
'tooltip-summary'                 => 'Masukkan ringkasan pendek',

# Metadata
'nodublincore'      => 'Metadata RDF Dublin Core dipadamkan bagi pelayan ini.',
'nocreativecommons' => 'Metadata RDF Creative Commons RDF dipadamkan bagi pelayan ini.',
'notacceptable'     => 'Pelayan wiki ini tidak mampu menyediakan data dalam format yang boleh dibaca oleh pelanggan anda.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Pengguna|Pengguna-pengguna}} {{SITENAME}} tanpa nama',
'siteuser'         => 'Pengguna {{SITENAME}}, $1',
'anonuser'         => 'Pengguna {{SITENAME}} tanpa nama $1',
'lastmodifiedatby' => 'Laman ini diubah buat kali terakhir pada $2, $1 oleh $3.',
'othercontribs'    => 'Berdasarkan karya $1.',
'others'           => 'lain-lain',
'siteusers'        => '{{PLURAL:$2|Pengguna|Pengguna-pengguna}} {{SITENAME}}, $1',
'anonusers'        => '{{PLURAL:$2|Pengguna|Pengguna-pengguna}} {{SITENAME}} tanpa nama $1',
'creditspage'      => 'Penghargaan',
'nocredits'        => 'Tiada maklumat penghargaan bagi laman ini.',

# Spam protection
'spamprotectiontitle' => 'Penapis spam',
'spamprotectiontext'  => 'Laman yang anda ingin simpan telah dihalang oleh penapis spam. Hal ini mungkin disebabkan oleh pautan ke tapak web luar yang telah disenaraihitamkan.',
'spamprotectionmatch' => 'Teks berikut dikesan oleh penapis spam kami: $1',
'spambot_username'    => 'Pembersihan spam MediaWiki',
'spam_reverting'      => 'Membalikkan kepada versi terakhir yang tidak mengandungi pautan ke $1',
'spam_blanking'       => 'Mengosongkan semua semakan yang mengandungi pautan ke $1',

# Info page
'infosubtitle'   => 'Maklumat laman',
'numedits'       => 'Jumlah suntingan (laman): $1',
'numtalkedits'   => 'Jumlah suntingan (laman perbincangan): $1',
'numwatchers'    => 'Bilangan pemantau: $1',
'numauthors'     => 'Bilangan pengarang (page): $1',
'numtalkauthors' => 'Bilangan pengarang (laman perbincangan): $1',

# Skin names
'skinname-standard' => 'Klasik',
'skinname-simple'   => 'Ringkas',
'skinname-modern'   => 'Moden',

# Math options
'mw_math_png'    => 'Sentiasa lakar PNG',
'mw_math_simple' => 'HTML jika ringkas, sebaliknya PNG',
'mw_math_html'   => 'HTML jika boleh, sebaliknya PNG',
'mw_math_source' => 'Biarkan sebagai TeX (untuk pelayar teks)',
'mw_math_modern' => 'Dicadangkan untuk pelayar moden',
'mw_math_mathml' => 'MathML jika boleh (sedang dalam uji kaji)',

# Math errors
'math_failure'          => 'Gagal menghurai',
'math_unknown_error'    => 'ralat yang tidak dikenali',
'math_unknown_function' => 'fungsi yang tidak dikenali',
'math_lexing_error'     => "ralat ''lexing''",
'math_syntax_error'     => 'ralat sintaks',
'math_image_error'      => 'penukaran PNG gagal; sila pastikan bahawa latex, dvips, gs dan convert dipasang dengan betul',
'math_bad_tmpdir'       => 'Direktori temp matematik tidak boleh ditulis atau dicipta',
'math_bad_output'       => 'Direktori output matematik tidak boleh ditulis atau dicipta',
'math_notexvc'          => 'Atur cara texvc hilang; sila lihat fail math/README untuk maklumat konfigurasi.',

# Patrolling
'markaspatrolleddiff'                 => 'Tanda ronda',
'markaspatrolledtext'                 => 'Tanda ronda laman ini',
'markedaspatrolled'                   => 'Tanda ronda',
'markedaspatrolledtext'               => 'Semakan [[:$1]] tersebut telah ditanda sebagai telah diperiksa.',
'rcpatroldisabled'                    => 'Rondaan Perubahan Terkini dimatikan',
'rcpatroldisabledtext'                => 'Ciri Rondaan Perubahan Terkini dimatikan.',
'markedaspatrollederror'              => 'Tidak boleh menanda ronda',
'markedaspatrollederrortext'          => 'Anda perlu menyatakan semakan untuk ditanda ronda.',
'markedaspatrollederror-noautopatrol' => 'Anda tidak dibenarkan menanda ronda perubahan anda sendiri.',

# Patrol log
'patrol-log-page'      => 'Log pemeriksaan',
'patrol-log-header'    => 'Yang berikut ialah log rondaan bagi semakan.',
'patrol-log-line'      => 'menandakan $1 bagi $2 sebagai telah diperiksa $3',
'patrol-log-auto'      => '(automatik)',
'patrol-log-diff'      => 's$1',
'log-show-hide-patrol' => '$1 log rondaan',

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
'previousdiff' => '← Suntingan sebelumnya',
'nextdiff'     => 'Suntingan berikutnya →',

# Media information
'mediawarning'         => "'''Amaran''': Fail jenis ini mungkin mengandungi kod berbahaya.
Dengan menjalankannya, komputer anda mungkin akan terjejas.",
'imagemaxsize'         => "Had saiz imej:<br />''(untuk laman keterangan fail)''",
'thumbsize'            => 'Saiz imej ringkas:',
'widthheightpage'      => '$1×$2, $3 halaman',
'file-info'            => '(saiz file: $1, jenis MIME: $2)',
'file-info-size'       => '($1 × $2 piksel, saiz fail: $3, jenis MIME: $4)',
'file-nohires'         => '<small>Tiada leraian lebih besar.</small>',
'svg-long-desc'        => '(Fail SVG, ukuran dasar $1 × $2 piksel, saiz fail: $3)',
'show-big-image'       => 'Leraian penuh',
'show-big-image-thumb' => '<small>Saiz pratonton ini: $1 × $2 piksel</small>',
'file-info-gif-looped' => 'berulang',
'file-info-gif-frames' => '$1 bingkai',
'file-info-png-looped' => 'berulang',
'file-info-png-repeat' => 'dimainkan {{PLURAL:$1|sekali|sebanyak $1 kali}}',
'file-info-png-frames' => '$1 bingkai',

# Special:NewFiles
'newimages'             => 'Galeri fail baru',
'imagelisttext'         => "Yang berikut ialah senarai bagi '''$1''' fail yang disusun secara $2.",
'newimages-summary'     => 'Laman khas ini memaparkan senarai fail muat naik terakhir.',
'newimages-legend'      => 'Nama fail',
'newimages-label'       => 'Nama fail (atau sebahagian daripadanya):',
'showhidebots'          => '($1 bot)',
'noimages'              => 'Tiada imej.',
'ilsubmit'              => 'Cari',
'bydate'                => 'mengikut tarikh',
'sp-newimages-showfrom' => 'Tunjukkan imej baru bermula daripada $2, $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'hours-abbrev' => 'j',

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
* isospeedratings
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
'exif-ycbcrsubsampling'            => 'Nisbah subpensampelan Y kepada C',
'exif-ycbcrpositioning'            => 'Kedudukan Y dan C',
'exif-xresolution'                 => 'Leraian mengufuk',
'exif-yresolution'                 => 'Leraian menegak',
'exif-resolutionunit'              => 'Unit leraian X dan Y',
'exif-stripoffsets'                => 'Lokasi data imej',
'exif-rowsperstrip'                => 'Baris sejalur',
'exif-stripbytecounts'             => 'Bait sejalur termampat',
'exif-jpeginterchangeformat'       => 'Ofset ke SOI JPEG',
'exif-jpeginterchangeformatlength' => 'Jumlah bait bagi data JPEG',
'exif-transferfunction'            => 'Fungsi pindah',
'exif-whitepoint'                  => 'Kekromatan takat putih',
'exif-primarychromaticities'       => 'Kekromatan warna primer',
'exif-ycbcrcoefficients'           => 'Pekali matriks penukaran ruang warna',
'exif-referenceblackwhite'         => 'Nilai rujukan pasangan hitam dan putih',
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
'exif-subsectime'                  => 'TarikhWaktu subsaat',
'exif-subsectimeoriginal'          => 'TarikhWaktuAsal subsaat',
'exif-subsectimedigitized'         => 'TarikhWaktuPendigitan subsaat',
'exif-exposuretime'                => 'Tempoh pendedahan',
'exif-exposuretime-format'         => '$1 saat ($2)',
'exif-fnumber'                     => 'Nombor F',
'exif-exposureprogram'             => 'Atur cara pendedahan',
'exif-spectralsensitivity'         => 'Kepekaan spektrum',
'exif-isospeedratings'             => 'Penilaian kelajuan ISO',
'exif-oecf'                        => 'Faktor penukaran optoelektronik',
'exif-shutterspeedvalue'           => 'Kelajuan pengatup',
'exif-aperturevalue'               => 'Bukaan',
'exif-brightnessvalue'             => 'Kecerahan',
'exif-exposurebiasvalue'           => 'Kecenderungan pendedahan',
'exif-maxaperturevalue'            => 'Bukaan tanah maksimum',
'exif-subjectdistance'             => 'Jarak subjek',
'exif-meteringmode'                => 'Mod permeteran',
'exif-lightsource'                 => 'Sumber cahaya',
'exif-flash'                       => 'Denyar',
'exif-focallength'                 => 'Panjang fokus kanta',
'exif-subjectarea'                 => 'Luas subjek',
'exif-flashenergy'                 => 'Tenaga denyar',
'exif-spatialfrequencyresponse'    => 'Sambutan frekuensi ruang',
'exif-focalplanexresolution'       => 'Leraian X satah fokus',
'exif-focalplaneyresolution'       => 'Leraian Y satah fokus',
'exif-focalplaneresolutionunit'    => 'Unit leraian satah fokus',
'exif-subjectlocation'             => 'Lokasi subjek',
'exif-exposureindex'               => 'Indeks pendedahan',
'exif-sensingmethod'               => 'Kaedah penderiaan',
'exif-filesource'                  => 'Sumber fail',
'exif-scenetype'                   => 'Jenis latar',
'exif-cfapattern'                  => 'Corak CFA',
'exif-customrendered'              => 'Pemprosesan imej tempahan',
'exif-exposuremode'                => 'Mod pendedahan',
'exif-whitebalance'                => 'Imbangan warna putih',
'exif-digitalzoomratio'            => 'Nisbah zum digital',
'exif-focallengthin35mmfilm'       => 'Panjang fokus dalam filem 35 mm',
'exif-scenecapturetype'            => 'Jenis penangkapan latar',
'exif-gaincontrol'                 => 'Kawalan latar',
'exif-contrast'                    => 'Kontras',
'exif-saturation'                  => 'Kepekatan',
'exif-sharpness'                   => 'Ketajaman',
'exif-devicesettingdescription'    => 'Huraian tetapan peranti',
'exif-subjectdistancerange'        => 'Julat jarak subjek',
'exif-imageuniqueid'               => 'ID unik imej',
'exif-gpsversionid'                => 'Versi label GPS',
'exif-gpslatituderef'              => 'Latitud utara atau selatan',
'exif-gpslatitude'                 => 'Latitud',
'exif-gpslongituderef'             => 'Logitud timur atau barat',
'exif-gpslongitude'                => 'Longitud',
'exif-gpsaltituderef'              => 'Rujukan ketinggian',
'exif-gpsaltitude'                 => 'Ketinggian',
'exif-gpstimestamp'                => 'Waktu GPS (jam atom)',
'exif-gpssatellites'               => 'Satelit yang digunakan untuk pengukuran',
'exif-gpsstatus'                   => 'Status penerima',
'exif-gpsmeasuremode'              => 'Mod pengukuran',
'exif-gpsdop'                      => 'Kepersisan pengukuran',
'exif-gpsspeedref'                 => 'Unit kelajuan',
'exif-gpsspeed'                    => 'Kelajuan penerima GPS',
'exif-gpstrackref'                 => 'Rujukan bagi arah pergerakan',
'exif-gpstrack'                    => 'Arah pergerakan',
'exif-gpsimgdirectionref'          => 'Rujukan bagi arah imej',
'exif-gpsimgdirection'             => 'Arah imej',
'exif-gpsmapdatum'                 => 'Data ukur geodesi yang digunakan',
'exif-gpsdestlatituderef'          => 'Rujukan bagi latitud destinasi',
'exif-gpsdestlatitude'             => 'Latitud destinasi',
'exif-gpsdestlongituderef'         => 'Rujukan bagi longitud destinasi',
'exif-gpsdestlongitude'            => 'Longitud destinasi',
'exif-gpsdestbearingref'           => 'Rujukan bagi bearing destinasi',
'exif-gpsdestbearing'              => 'Bearing destinasi',
'exif-gpsdestdistanceref'          => 'Rujukan bagi jarak destinasi',
'exif-gpsdestdistance'             => 'Jarak destinasi',
'exif-gpsprocessingmethod'         => 'Nama kaedah pemprosesan GPS',
'exif-gpsareainformation'          => 'Nama kawasan GPS',
'exif-gpsdatestamp'                => 'Tarikh GPS',
'exif-gpsdifferential'             => 'Pembetulan pembezaan GPS',

# EXIF attributes
'exif-compression-1' => 'Tidak dimampat',

'exif-unknowndate' => 'Tarikh tidak diketahui',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'Dibalikkan secara mengufuk',
'exif-orientation-3' => 'Diputar 180°',
'exif-orientation-4' => 'Dibalikkan secara menegak',
'exif-orientation-5' => 'Diputarkan 90° melawan arah jam dan dibalikkan secara menegak',
'exif-orientation-6' => 'Diputarkan 90° mengikut arah jam',
'exif-orientation-7' => 'Diputarkan 90° mengikut arah jam dan dibalikkan secara menegak',
'exif-orientation-8' => 'Diputarkan 90° melawan arah jam',

'exif-planarconfiguration-1' => 'format besar',
'exif-planarconfiguration-2' => 'format satah',

'exif-componentsconfiguration-0' => 'tiada',

'exif-exposureprogram-0' => 'Tidak ditentukan',
'exif-exposureprogram-1' => 'Manual',
'exif-exposureprogram-2' => 'Atur cara normal',
'exif-exposureprogram-3' => 'Mengutamakan bukaan',
'exif-exposureprogram-4' => 'Mengutamakan pengatup',
'exif-exposureprogram-5' => 'Atur cara kreatif (cenderung kepada kedalaman lapangan)',
'exif-exposureprogram-6' => 'Atur cara aksi (cenderung kepada kelajuan pengatup yang tinggi)',
'exif-exposureprogram-7' => 'Mod potret (untuk foto jarak dekat dengan latar belakang kabur)',
'exif-exposureprogram-8' => 'Mod landskap (untuk foto landskap dengan latar belakang terfokus)',

'exif-subjectdistance-value' => '$1 meter',

'exif-meteringmode-0'   => 'Tidak diketahui',
'exif-meteringmode-1'   => 'Purata',
'exif-meteringmode-2'   => 'Purata cenderung ke pusat',
'exif-meteringmode-3'   => 'Titik',
'exif-meteringmode-4'   => 'Berbilang titik',
'exif-meteringmode-5'   => 'Corak',
'exif-meteringmode-6'   => 'Separa',
'exif-meteringmode-255' => 'Lain-lain',

'exif-lightsource-0'   => 'Tidak diketahui',
'exif-lightsource-1'   => 'Cahaya siang',
'exif-lightsource-2'   => 'Pendarfluor',
'exif-lightsource-3'   => 'Tungsten (lampu pijar)',
'exif-lightsource-4'   => 'Denyar',
'exif-lightsource-9'   => 'Cuaca cerah',
'exif-lightsource-10'  => 'Cuaca mendung',
'exif-lightsource-11'  => 'Gelap',
'exif-lightsource-12'  => 'Pendarfluor cahaya siang (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Pendarfluor putih siang (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Pendarfluor putih sejuk (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Pendarfluor putih (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Cahaya standard A',
'exif-lightsource-18'  => 'Cahaya standard B',
'exif-lightsource-19'  => 'Cahaya standard C',
'exif-lightsource-24'  => 'Tungsten studio ISO',
'exif-lightsource-255' => 'Sumber cahaya lain',

# Flash modes
'exif-flash-fired-0'    => 'Denyar tidak bernyala',
'exif-flash-fired-1'    => 'Denyar dinyalakan',
'exif-flash-return-0'   => 'tiada pengesan pulangan strob',
'exif-flash-return-2'   => 'cahaya pulang strob tidak dikesan',
'exif-flash-return-3'   => 'cahaya pulang strob dikesan',
'exif-flash-mode-1'     => 'nyalaan denyar wajib',
'exif-flash-mode-2'     => 'tindasan denyar wajib',
'exif-flash-mode-3'     => 'mod automatik',
'exif-flash-function-1' => 'Tiada fungsi denyar',
'exif-flash-redeye-1'   => 'mod penurunan mata merah',

'exif-focalplaneresolutionunit-2' => 'inci',

'exif-sensingmethod-1' => 'Tidak ditentukan',
'exif-sensingmethod-2' => 'Penderia kawasan warna cip tunggal',
'exif-sensingmethod-3' => 'Penderia kawasan warna dwicip',
'exif-sensingmethod-4' => 'Penderia kawasan warna tricip',
'exif-sensingmethod-5' => 'Penderia kawasan warna berjujukan',
'exif-sensingmethod-7' => 'Penderia trilinear',
'exif-sensingmethod-8' => 'Penderia linear warna berjujukan',

'exif-scenetype-1' => 'Gambar yang diambil secara terus',

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
'exif-gaincontrol-1' => 'Gandaan rendah atas',
'exif-gaincontrol-2' => 'Gandaan tinggi atas',
'exif-gaincontrol-3' => 'Gandaan rendah bawah',
'exif-gaincontrol-4' => 'Gandaan tinggi bawah',

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
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Pandangan dekat',
'exif-subjectdistancerange-3' => 'Pandangan jauh',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Latitud utara',
'exif-gpslatitude-s' => 'Latitud selatan',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Longitud timur',
'exif-gpslongitude-w' => 'Longitud barat',

'exif-gpsstatus-a' => 'Pengukuran sedang dijalankan',
'exif-gpsstatus-v' => 'Interoperabiliti pengukuran',

'exif-gpsmeasuremode-2' => 'Pengukuran dua dimensi',
'exif-gpsmeasuremode-3' => 'Pengukuran tiga dimensi',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometer sejam',
'exif-gpsspeed-m' => 'Batu sejam',
'exif-gpsspeed-n' => 'Knot',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Arah benar',
'exif-gpsdirection-m' => 'Arah magnet',

# External editor support
'edit-externally'      => 'Sunting fail ini menggunakan perisian luar',
'edit-externally-help' => '(Lihat [http://www.mediawiki.org/wiki/Manual:External_editors arahan pemasangan] untuk maklumat lanjut)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'semua',
'imagelistall'     => 'semua',
'watchlistall2'    => 'semua',
'namespacesall'    => 'semua',
'monthsall'        => 'semua',
'limitall'         => 'semua',

# E-mail address confirmation
'confirmemail'              => 'Sahkan alamat e-mel',
'confirmemail_noemail'      => 'Anda belum menetapkan alamat e-mel yang sah dalam [[Special:Preferences|laman keutamaan]] anda.',
'confirmemail_text'         => '{{SITENAME}} menghendaki supaya anda mengesahkan alamat e-mel anda sebelum menggunakan ciri-ciri e-mel.
Aktifkan butang di bawah untuk mengirim e-mel pengesahan kepada alamat e-mel
anda. E-mel tersebut akan mengandungi sebuah pautan yang mengandungi sebuah
kod; buka pautan tersebut di pelayar anda untuk mengesahkan bahawa alamat e-mel anda.',
'confirmemail_pending'      => 'Sebuah kod pengesahan telah pun di-e-melkan kepada anda. Jika anda baru sahaja
membuka akaun, sila tunggu kehadiran e-mel tersebut selama beberapa minit
sebelum meminta kod baru.',
'confirmemail_send'         => 'E-melkan kod pengesahan',
'confirmemail_sent'         => 'E-mel pengesahan dikirim.',
'confirmemail_oncreate'     => 'Sebuah kod pengesahan telah dikirim kepada alamat e-mel anda.
Kod ini tidak diperlukan untuk log masuk, akan tetapi anda perlu menyediakannya untuk
mengaktifkan ciri-ciri e-mel yang terdapat dalam wiki ini.',
'confirmemail_sendfailed'   => '{{SITENAME}} tidak dapat menghantar e-mel pengesahan anda. Sila semak alamat e-mel tersebut.

Pelayan mel memulangkan: $1',
'confirmemail_invalid'      => 'Kod pengesahan tidak sah. Kod tersebut mungkin sudah luput.',
'confirmemail_needlogin'    => 'Anda perlu $1 terlebih dahulu untuk mengesahkan alamat e-mel anda.',
'confirmemail_success'      => 'Alamat e-mel anda telah disahkan. Sekarang anda boleh melog masuk dan berseronok di wiki ini.',
'confirmemail_loggedin'     => 'Alamat e-mel anda telah disahkan.',
'confirmemail_error'        => 'Sesuatau yang tidak kena berlaku ketika kami menyimpan pengesahan anda.',
'confirmemail_subject'      => 'Pengesahan alamat e-mel di {{SITENAME}}',
'confirmemail_body'         => 'Seseorang, barangkali anda, dari alamat IP $1, telah mendaftarkan akaun "$2" dengan alamat e-mel ini di {{SITENAME}}.

Untuk mengesahkan bahawa akaun ini milik anda dan untuk mengaktifkan kemudahan e-mel di {{SITENAME}}, sila buka pautan ini dalam pelayar web anda:

$3

Jika anda tidak mendaftar di {{SITENAME}} (atau anda telah mendaftar menggunakan alamat e-mel lain), ikuti pautan ini untuk membatalkan pengesahan alamat e-mel:

$5

Kod pengesahan ini akan luput pada $4.',
'confirmemail_body_changed' => 'Seseorang, barangkali anda, dari alamat IP $1, telah menukarkan alamat e-mel bagi akaun "$2" menjadi alamat e-mel ini di {{SITENAME}}.

Untuk mengesahkan bahawa akaun ini milik anda dan untuk mengaktifkan semula kemudahan e-mel di {{SITENAME}}, sila buka pautan ini dalam pelayar web anda:

$3

Jika akaun ini *bukan* milik anda, ikuti pautan ini untuk membatalkan pengesahan alamat e-mel:

$5

Kod pengesahan ini akan luput pada $4.',
'confirmemail_invalidated'  => 'Pengesahan alamat e-mel telah dibatalkan',
'invalidateemail'           => 'Batalkan pengesahan e-mel',

# Scary transclusion
'scarytranscludedisabled' => '[Penyertaan pautan interwiki dilumpuhkan]',
'scarytranscludefailed'   => '[Gagal mendapatkan templat $1]',
'scarytranscludetoolong'  => '[URL terlalu panjang]',

# Trackbacks
'trackbackbox'      => 'Jejak balik bagi laman ini:<br />
$1',
'trackbackremove'   => '([$1 Hapus])',
'trackbacklink'     => 'Jejak balik',
'trackbackdeleteok' => 'Jejak balik dihapuskan.',

# Delete conflict
'deletedwhileediting' => "'''Amaran''': Laman ini dihapuskan ketika anda sedang menyuntingnya!",
'confirmrecreate'     => "Pengguna [[User:$1|$1]] ([[User talk:$1|perbincangan]]) telah menghapuskan laman ini ketika anda sedang menyunting atas sebab berikut:
: ''$2''
Sila sahkan bahawa anda mahu mencipta semula laman ini.",
'recreate'            => 'Cipta semula',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => 'Kosongkan fail simpanan bagi laman ini?',
'confirm-purge-bottom' => 'Kosongkan cache dan papar versi semasa.',

# Multipage image navigation
'imgmultipageprev' => '← halaman sebelumnya',
'imgmultipagenext' => 'halaman berikutnya →',
'imgmultigo'       => 'Pergi!',
'imgmultigoto'     => 'Pergi ke halaman $1',

# Table pager
'ascending_abbrev'         => 'menaik',
'descending_abbrev'        => 'menurun',
'table_pager_next'         => 'Muka berikutnya',
'table_pager_prev'         => 'Muka sebelumnya',
'table_pager_first'        => 'Muka pertama',
'table_pager_last'         => 'Muka terakhir',
'table_pager_limit'        => 'Papar $1 item setiap muka',
'table_pager_limit_label'  => 'Bilangan item setiap laman:',
'table_pager_limit_submit' => 'Pergi',
'table_pager_empty'        => 'Tiada keputusan',

# Auto-summaries
'autosumm-blank'   => 'Mengosongkan laman',
'autosumm-replace' => "Menggantikan laman dengan '$1'",
'autoredircomment' => 'Melencong ke [[$1]]',
'autosumm-new'     => "Mencipta laman baru dengan kandungan '$1'",

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
'watchlistedit-normal-title'   => 'Sunting senarai pantau',
'watchlistedit-normal-legend'  => 'Buang tajuk daripada senarai pantau',
'watchlistedit-normal-explain' => 'Tajuk-tajuk dalam senarai pantau anda ditunjukkan di bawah.
Untuk membuang mana-mana tajuk, tanda kotak yang terletak di sebelahnya, dan klik "Buang Tajuk". Anda juga boleh [[Special:Watchlist/raw|menyunting senarai mentah]].',
'watchlistedit-normal-submit'  => 'Buang Tajuk',
'watchlistedit-normal-done'    => '$1 tajuk dibuang daripada senarai pantau anda:',
'watchlistedit-raw-title'      => 'Sunting senarai pantau mentah',
'watchlistedit-raw-legend'     => 'Sunting senarai pantau mentah',
'watchlistedit-raw-explain'    => 'Tajuk-tajuk dalam senarai pantau anda dipaparkan di bawah, dan boleh disunting dengan menambah atau membuang daripada senarai tersebut;
satu tajuk bagi setiap baris.
Apabila selesai, klik "{{int:Watchlistedit-raw-submit}}".
Anda juga boleh [[Special:Watchlist/edit|menggunakan penyunting piawai]].',
'watchlistedit-raw-titles'     => 'Tajuk:',
'watchlistedit-raw-submit'     => 'Kemas Kini Senarai Pantau',
'watchlistedit-raw-done'       => 'Senarai pantau anda telah dikemaskinikan.',
'watchlistedit-raw-added'      => '$1 tajuk ditambah:',
'watchlistedit-raw-removed'    => '$1 tajuk telah dibuang:',

# Watchlist editing tools
'watchlisttools-view' => 'Lihat perubahan',
'watchlisttools-edit' => 'Sunting senarai pantau',
'watchlisttools-raw'  => 'Sunting senarai pantau mentah',

# Hijri month names
'hijri-calendar-m1'  => 'Muharam',
'hijri-calendar-m2'  => 'Safar',
'hijri-calendar-m3'  => 'Rabiulawal',
'hijri-calendar-m4'  => 'Rabiulakhir',
'hijri-calendar-m5'  => 'Jamadilawal',
'hijri-calendar-m6'  => 'Jamadilakhir',
'hijri-calendar-m7'  => 'Rejab',
'hijri-calendar-m8'  => 'Syaaban',
'hijri-calendar-m9'  => 'Ramadan',
'hijri-calendar-m10' => 'Syawal',
'hijri-calendar-m11' => 'Zulkaedah',
'hijri-calendar-m12' => 'Zulhijah',

# Core parser functions
'unknown_extension_tag' => 'Tag penyambung "$1" tidak dikenali',
'duplicate-defaultsort' => 'Amaran: Kunci susunan lalai "$2" mengatasi kunci susunan lalai "$1" sebelumnya.',

# Special:Version
'version'                          => 'Versi',
'version-extensions'               => 'Penyambung yang dipasang',
'version-specialpages'             => 'Laman khas',
'version-parserhooks'              => 'Penyangkuk penghurai',
'version-variables'                => 'Pemboleh ubah',
'version-other'                    => 'Lain-lain',
'version-mediahandlers'            => 'Pengelola media',
'version-hooks'                    => 'Penyangkuk',
'version-extension-functions'      => 'Fungsi penyambung',
'version-parser-extensiontags'     => 'Tag penyambung penghurai',
'version-parser-function-hooks'    => 'Penyangkuk fungsi penghurai',
'version-skin-extension-functions' => 'Fungsi penyangkuk rupa',
'version-hook-name'                => 'Nama penyangkuk',
'version-hook-subscribedby'        => 'Dilanggan oleh',
'version-version'                  => '(Versi $1)',
'version-license'                  => 'Lesen',
'version-poweredby-credits'        => "Wiki ini dikuasakan oleh '''[http://www.mediawiki.org/ MediaWiki]''', hak cipta © 2001-$1 $2.",
'version-poweredby-others'         => 'penyumbang-penyumbang lain',
'version-license-info'             => 'MediaWiki adalah perisian bebas; anda boleh mengedarkannya semula dan/atau mengubah suainya di bawah terma-terma Lesen Awam GNU sebagai mana yang telah diterbitkan oleh Yayasan Perisian Bebas, sama ada versi 2 bagi Lesen tersebut, atau (berdasarkan pilihan anda) mana-mana versi selepasnya.

MediaWiki diedarkan dengan harapan bahawa ia berguna, tetapi TANPA SEBARANG WARANTI; hatta waranti yang tersirat bagi KEBOLEHDAGANGAN mahupun KESESUAIAN UNTUK TUJUAN TERTENTU. Sila lihat Lesen Awam GNU untuk butiran lanjut.

Anda patut telah menerima [{{SERVER}}{{SCRIPTPATH}}/COPYING sebuah salinan bagi Lesen Awam GNU] bersama-sama dengan atur cara ini; jika tidak, tulis ke Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA atau [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html baca dalam talian].',
'version-software'                 => 'Perisian yang dipasang',
'version-software-product'         => 'Produk',
'version-software-version'         => 'Versi',

# Special:FilePath
'filepath'         => 'Laluan fail',
'filepath-page'    => 'Fail:',
'filepath-submit'  => 'Laluan',
'filepath-summary' => 'Laman khas ini mengembalikan laluan penuh bagi sesebuah fail.
Imej ditunjuk dalam leraian penuh, jenis fail yang lain dibuka dengan atur cara yang berkenaan secara terus.

Sila masukkan nama fail tanpa awalan "{{ns:file}}:".',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Cari fail serupa',
'fileduplicatesearch-summary'  => 'Anda boleh mencari fail serupa berdasarkan nilai cincangannya.

Sila masukkan nama fail tanpa awalan "{{ns:file}}:".',
'fileduplicatesearch-legend'   => 'Cari fail serupa',
'fileduplicatesearch-filename' => 'Nama fail:',
'fileduplicatesearch-submit'   => 'Gelintar',
'fileduplicatesearch-info'     => '$1 × $2 piksel<br />Saiz fail: $3<br />Jenis MIME: $4',
'fileduplicatesearch-result-1' => 'Tiada fail yang serupa dengan "$1".',
'fileduplicatesearch-result-n' => 'Terdapat $2 fail yang serupa dengan "$1".',

# Special:SpecialPages
'specialpages'                   => 'Laman khas',
'specialpages-note'              => '----
* Laman khas biasa.
* <strong class="mw-specialpagerestricted">Laman khas terhad.</strong>',
'specialpages-group-maintenance' => 'Laporan penyenggaraan',
'specialpages-group-other'       => 'Laman khas lain',
'specialpages-group-login'       => 'Log masuk / daftar',
'specialpages-group-changes'     => 'Perubahan terkini dan log',
'specialpages-group-media'       => 'Laporan media dan muat naik',
'specialpages-group-users'       => 'Pengguna dan hak',
'specialpages-group-highuse'     => 'Laman popular',
'specialpages-group-pages'       => 'Senarai laman',
'specialpages-group-pagetools'   => 'Alatan laman',
'specialpages-group-wiki'        => 'Data dan alatan wiki',
'specialpages-group-redirects'   => 'Laman khas yang melencong',
'specialpages-group-spam'        => 'Alatan spam',

# Special:BlankPage
'blankpage'              => 'Laman kosong',
'intentionallyblankpage' => 'Laman ini sengaja dibiarkan kosong dan digunakan untuk kerja-kerja ujian dan sebagainya.',

# External image whitelist
'external_image_whitelist' => ' #Jangan ubah baris ini<pre>
#Letakkan senarai ungkapan nalar (tidak termasuk apitan //) di baris kosong di bawah
#Setiap ungkapan akan dipadankan dengan pautan imej luar
#Pautan yang sepadan sahaja akan dijadikan imej, jika tidak hanya pautan kepada imej akan muncul
#Baris yang bermula dengan aksara # diabaikan
#Ini sensitif kepada atur huruf

#Jangan letak ungkapan nalar di bawah baris ini dan jangan ubah baris ini</pre>',

# Special:Tags
'tags'                    => 'Label perubahan yang sah',
'tag-filter'              => 'Tapis [[Special:Tags|label]]:',
'tag-filter-submit'       => 'Tapis',
'tags-title'              => 'Label',
'tags-intro'              => 'Yang berikut ialah senarai label yang digunakan untuk menanda suntingan, berserta maknanya.',
'tags-tag'                => 'Nama label',
'tags-display-header'     => 'Rupa dalam senarai perubahan',
'tags-description-header' => 'Keterangan makna',
'tags-hitcount-header'    => 'Perubahan',
'tags-edit'               => 'sunting',
'tags-hitcount'           => '$1 perubahan',

# Special:ComparePages
'comparepages'     => 'Perbandingan laman',
'compare-selector' => 'Bandingkan semakan laman',
'compare-page1'    => 'Laman 1',
'compare-page2'    => 'Laman 2',
'compare-rev1'     => 'Semakan 1',
'compare-rev2'     => 'Semakan 2',
'compare-submit'   => 'Bandingkan',

# Database error messages
'dberr-header'      => 'Wiki ini dilanda masalah',
'dberr-problems'    => 'Harap maaf. Tapak web ini dilanda masalah teknikal.',
'dberr-again'       => 'Cuba tunggu selama beberapa minit dan muat semula.',
'dberr-info'        => '(Tidak dapat menghubungi pelayan pangkalan data: $1)',
'dberr-usegoogle'   => 'Buat masa ini, anda boleh cuba menggelintar melalui Google.',
'dberr-outofdate'   => 'Sila ambil perhatian bahawa indeks mereka bagi kandungan kami mungkin sudah ketinggalan zaman.',
'dberr-cachederror' => 'Yang berikut ialah salinan bagi laman yang diminta yang diambil daripada cache, dan mungkin bukan yang terkini.',

# HTML forms
'htmlform-invalid-input'       => 'Terdapat beberapa masalah dengan input anda',
'htmlform-select-badoption'    => 'Nilai yang anda tentukan bukan satu pilihan yang sah.',
'htmlform-int-invalid'         => 'Nilai yang anda tetapkan bukan satu integer.',
'htmlform-float-invalid'       => 'Nilai yang anda nyatakan bukan nombor.',
'htmlform-int-toolow'          => 'Nilai yang anda nyatakan berada di bawah minimum bagi $1',
'htmlform-int-toohigh'         => 'Nilai yang anda nyatakan berada di atas maksimum bagi $1',
'htmlform-required'            => 'Nilai ini adalah wajib',
'htmlform-submit'              => 'Hantar',
'htmlform-reset'               => 'Undur perubahan',
'htmlform-selectorother-other' => 'Lain-lain',

);
