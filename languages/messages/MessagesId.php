<?php
/** Indonesian (Bahasa Indonesia)
 *
 * @package MediaWiki
 * @subpackage Language
 */

$quickbarSettings = array(
	'Tidak ada', 'Tetap sebelah kiri', 'Tetap sebelah kanan', 'Mengambang sebelah kiri'
);

$skinNames = array(
	'standard'    => 'Standar',
);

$bookstoreList = array(
	'Gramedia Cyberstore (via Google)' => 'http://www.google.com/search?q=%22ISBN+:+$1%22+%22product_detail%22+site:www.gramediacyberstore.com+OR+site:www.gramediaonline.com+OR+site:www.kompas.com&hl=id',
	'Bhinneka.com bookstore' => 'http://www.bhinneka.com/Buku/Engine/search.asp?fisbn=$1',
);
$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Istimewa',
	NS_MAIN             => '',
	NS_TALK             => 'Pembicaraan',
	NS_USER             => 'Pengguna',
	NS_USER_TALK        => 'Pembicaraan_Pengguna',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => 'Pembicaraan_$1',
	NS_IMAGE            => 'Berkas',
	NS_IMAGE_TALK       => 'Pembicaraan_Berkas',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Pembicaraan_MediaWiki',
	NS_TEMPLATE         => 'Templat',
	NS_TEMPLATE_TALK    => 'Pembicaraan_Templat',
	NS_HELP             => 'Bantuan',
	NS_HELP_TALK        => 'Pembicaraan_Bantuan',
	NS_CATEGORY         => 'Kategori',
	NS_CATEGORY_TALK    => 'Pembicaraan_Kategori'
);

$namespaceAliases = array(
	'Gambar_Pembicaraan'    => NS_IMAGE_TALK,
	'MediaWiki_Pembicaraan' => NS_MEDIAWIKI_TALK,
	'Templat_Pembicaraan'   => NS_TEMPLATE_TALK,
	'Bantuan_Pembicaraan'   => NS_HELP_TALK,
	'Kategori_Pembicaraan'  => NS_CATEGORY_TALK,
	'Gambar'                => NS_IMAGE,
	'Pembicaraan_Gambar'    => NS_IMAGE_TALK,
	'Bicara'                => NS_TALK,
	'Bicara_Pengguna'       => NS_USER_TALK,
);

$separatorTransformTable = array(',' => '.', '.' => ',' );
$datePreferences = false;

$messages = array(

# User preference toggles
'tog-underline' => 'Garis bawahi pranala',
'tog-highlightbroken' => 'Format pranala patah <a href="" class="new">seperti ini</a> (pilihan: seperti ini<a href="" class="internal">?</a>).',
'tog-justify'   => 'Ratakan paragraf',
'tog-hideminor' => 'Sembunyikan suntingan kecil dalam perubahan terbaru',
'tog-extendwatchlist' => 'Tampilkan daftar pantauan yang menunjukkan semua perubahan',
'tog-usenewrc' => 'Tampilan perubahan terbaru alternatif (JavaScript)',
'tog-numberheadings' => 'Beri nomor judul secara otomatis',
'tog-showtoolbar' => "Perlihatkan <i>toolbar</i> (batang alat) penyuntingan",
'tog-editondblclick' => 'Sunting halaman dengan klik ganda (JavaScript)',
'tog-editsection'=> 'Fungsikan penyuntingan sub-bagian melalui pranala [sunting]',
'tog-editsectiononrightclick' => 'Fungsikan penyuntingan sub-bagian dengan klik-kanan pada judul bagian (JavaScript)',
'tog-showtoc' => 'Perlihatkan daftar isi (untuk halaman yang mempunyai lebih dari 3 sub-bagian)',
'tog-rememberpassword' => 'Ingat kata sandi pada setiap sesi',
'tog-editwidth' => 'Kotak sunting berukuran maksimum',
'tog-watchcreations' => 'Tambahkan halaman yang baru dibuat ke daftar pantauan',
'tog-watchdefault' => 'Tambahkan halaman yang disunting ke dalam daftar pantauan',
'tog-minordefault' => 'Tandai semua suntingan sebagai suntingan kecil secara baku',
'tog-previewontop' => 'Perlihatkan pratayang sebelum kotak sunting dan tidak sesudahnya',
'tog-previewonfirst' => 'Perlihatkan pratayang pada suntingan pertama',
'tog-nocache' => 'Matikan <em>cache</em> halaman',
'tog-enotifwatchlistpages' 	=> 'Surat-e saya jika suatu halaman yang saya pantau berubah',
'tog-enotifusertalkpages' 	=> 'Surat-e saya jika halaman bicara saya berubah',
'tog-enotifminoredits' 		=> 'Surat-e saya juga pada perubahan kecil',
'tog-enotifrevealaddr' 		=> 'Berikan surat-e saya pada surat notifikasi',
'tog-shownumberswatching' 	=> 'Tunjukkan jumlah pemantau',
'tog-fancysig' => 'Paraf kasar (tanpa pranala otomatis)',
'tog-externaleditor' => 'Gunakan perangkat lunak pengolah kata luar',
'tog-externaldiff' => 'Gunakan perangkat lunak luar untuk melihat perbedaan suntingan',
'tog-showjumplinks' => 'Aktifkan pranala pembantu "langsung ke"',
'tog-uselivepreview' => 'Gunakan pratayang langsung (JavaScript) (eksperimental)',
'tog-autopatrol' => 'Tandai suntingan yang saya lakukan telah dipatroli/diperiksa',
'tog-forceeditsummary' => 'Ingatkan saya bila kotak ringkasan suntingan masih kosong',
'tog-watchlisthideown' => 'Sembunyikan suntingan saya di daftar pantauan',
'tog-watchlisthidebots' => 'Sembunyikan suntingan bot di daftar pantauan',
'tog-nolangconversion'		=> 'Matikan konversi varian',

'underline-always' => 'Selalu',
'underline-never' => 'Tidak',
'underline-default' => 'Sesuai konfigurasi penjelajah web',

'skinpreview' => '(Pratayang)',

# dates
'sunday' => 'Minggu',
'monday' => 'Senin',
'tuesday' => 'Selasa',
'wednesday' => 'Rabu',
'thursday' => 'Kamis',
'friday' => "Jumat",
'saturday' => 'Sabtu',
'sun' => 'Min',
'mon' => 'Sen',
'tue' => 'Sel',
'wed' => 'Rab',
'thu' => 'Kam',
'fri' => 'Jum',
'sat' => 'Sab',
'january' => 'Januari',
'february' => 'Februari',
'march' => 'Maret',
'april' => 'April',
'may_long' => 'Mei',
'june' => 'Juni',
'july' => 'Juli',
'august' => 'Agustus',
'september' => 'September',
'october' => 'Oktober',
'november' => 'November',
'december' => 'Desember',
'january-gen' => 'Januari',
'february-gen' => 'Februari',
'march-gen' => 'Maret',
'april-gen' => 'April',
'may-gen' => 'Mei',
'june-gen' => 'Juni',
'july-gen' => 'Juli',
'august-gen' => 'Agustus',
'september-gen' => 'September',
'october-gen' => 'Oktober',
'november-gen' => 'November',
'december-gen' => 'Desember',
'jan' => 'Jan',
'feb' => 'Feb',
'mar' => 'Mar',
'apr' => 'Apr',
'may' => 'Mei',
'jun' => 'Jun',
'jul' => 'Jul',
'aug' => 'Agu',
'sep' => 'Sep',
'oct' => 'Okt',
'nov' => 'Nov',
'dec' => 'Des',

# Bits of text used by many pages:
#
'categories' => 'Kategori',
'pagecategories' => '{{PLURAL:$1|Kategori|Kategori}}',
"category_header" => "Artikel dalam kategori \"$1\"",
"subcategories" => "Subkategori",

"mainpage" => "Halaman Utama",
"mainpagetext" => "Perangkat lunak wiki berhasil dipasang.",
'mainpagedocfooter' => "Silakan baca [http://meta.wikimedia.org/wiki/Help:Contents User's Guide] untuk informasi penggunaan perangkat lunak wiki

== Memulai penggunaan ==

* [http://www.mediawiki.org/wiki/Help:Configuration_settings Daftar pengaturan preferensi]
* [http://www.mediawiki.org/wiki/Help:FAQ MediaWiki FAQ]
* [http://mail.wikimedia.org/mailman/listinfo/mediawiki-announce Milis rilis MediaWiki]",

'portal' => 'Portal komunitas',
'portal-url' => 'Project:Portal komunitas',
"about" => "Tentang",
"aboutsite" => "Tentang {{SITENAME}}",
"aboutpage" => "Project:Tentang",
'article' => 'Artikel',
"help" => "Bantuan",
"helppage" => "{{ns:help}}:Isi",
"bugreports" => "Laporan bug",
"bugreportspage" => "Project:Laporan bug",
'sitesupport'   => 'Sumbangan dana',
"sitesupport-url" => "Project:Sumbangan dana",
"faq" => "FAQ",
"faqpage" => "Project:FAQ",
"edithelp" => "Bantuan penyuntingan",
"newwindow" => "(buka di jendela baru)",
"edithelppage" => "{{ns:help}}:Penyuntingan",
"cancel" => "Batalkan",
"qbfind" => "Cari",
"qbbrowse" => "Panduan arah",
"qbedit" => "Sunting",
"qbpageoptions" => "Halaman ini",
"qbpageinfo" => "Konteks halaman",
"qbmyoptions" => "Halaman saya",
"qbspecialpages" => "Halaman istimewa",
"moredotdotdot" => "Lainnya...",
"mypage" => "Halaman saya",
"mytalk" => "Pembicaraan saya",
"anontalk" => "Pembicaraan IP ini",
'navigation' => 'Panduan arah',

# Metadata in edit box
'metadata_help' => 'Metadata (lihat [[{{ns:project}}:Metadata]] untuk penjelasan lanjut):',

"currentevents" => "Peristiwa terkini",
'currentevents-url' => 'Peristiwa terkini',

"disclaimers" => "Penyangkalan",
"disclaimerpage" => "Project:Penyangkalan umum",
'privacy' => 'Kebijakan kerahasiaan',
'privacypage' => 'Project:Kebijakan kerahasiaan',
"errorpagetitle" => "Kesalahan",
"returnto" => "Kembali ke $1.",
"tagline" => "Dari {{SITENAME}}",
"whatlinkshere" => "Pranala ke halaman ini",
"help" => "Bantuan",
"search" => "Cari",
"searchbutton" => "Cari",
"go" => "Tuju ke",
'searcharticle' => "Tuju ke",
"history" => "Versi terdahulu",
'history_short' => 'Versi terdahulu',
'updatedmarker' => 'diubah sejak kunjungan terakhir saya',
'info_short' => 'Informasi',
"printableversion" => "Versi cetak",
'permalink'     => 'Pranala permanen',
'print' => 'Cetak',
'edit' => 'Sunting',
"editthispage" => "Sunting halaman ini",
'delete' => 'Hapus',
"deletethispage" => "Hapus halaman ini",
'undelete_short' => 'Batal hapus {{PLURAL:$1|satu suntingan|$1 suntingan}}',
'protect' => 'Lindungi',
"protectthispage" => "Lindungi halaman ini",
'unprotect' => 'Ubah perlindungan',
"unprotectthispage" => "Ubah perlindungan halaman ini",
"newpage" => "Halaman baru",
"talkpage" => "Diskusikan halaman ini",
'specialpage' => 'Halaman istimewa',
'personaltools' => 'Peralatan pribadi',
"postcomment" => "Kirim komentar",
"articlepage" => "Lihat artikel",
'talk' => 'Diskusi',
'views' => 'Tampilan',
'toolbox' => 'Kotak peralatan',
"userpage" => "Lihat halaman pengguna",
"projectpage" => "Lihat halaman proyek",
"imagepage" => "Lihat halaman berkas",
'mediawikipage' => 	'Lihat halaman pesan sistem',
'templatepage' => 	'Lihat halaman templat',
'viewhelppage' => 	'Lihat halaman bantuan',
'categorypage' => 	'Lihat halaman kategori',
"viewtalkpage" => "Lihat diskusi",
"otherlanguages" => "Bahasa lain",
"redirectedfrom" => "(Dialihkan dari $1)",
'autoredircomment' => 'Alihkan ke [[$1]]',
'redirectpagesub' => 'Halaman peralihan',
"lastmodifiedat" => "Halaman ini terakhir diubah pada $2, $1.",
"viewcount" => "Halaman ini telah diakses sebanyak $1 kali.<br />",
"copyright" => "Seluruh teks tersedia dalam naungan $1.",
"protectedpage" => "Halaman yang dilindungi",
'jumpto' => 'Langsung ke:',
'jumptonavigation' => 'panduan arah',
'jumptosearch' => 'cari',

'badaccess'     => 'Kesalahan hak akses',
'badaccess-group0' => 'Anda tidak diizinkan untuk melakukan tindakan yang Anda minta.',
'badaccess-group1' => 'Tindakan yang Anda minta dibatasi untuk pengguna kelompok $1.',
'badaccess-group2' => 'Tindakan yang Anda minta dibatasi untuk pengguna dalam kelompok $1.',
'badaccess-groups' => 'Tindakan yang Anda minta dibatasi untuk pengguna dalam kelompok $1.',

'versionrequired' => 'Dibutuhkan MediaWiki versi $1',
'versionrequiredtext' => 'MediaWiki versi $1 dibutuhkan untuk menggunakan halaman ini. Lihat [[{{ns:special}}:Version]]',

"ok" => "OK",

'pagetitle'		=> '$1 - {{SITENAME}}',

"retrievedfrom" => "Diperoleh dari \"$1\"",
'youhavenewmessages' => 'Anda mempunyai $1 ($2).',
"newmessageslink" => "pesan baru",
'newmessagesdifflink' => 'perubahan terakhir',
"editsection" => "sunting",
"editold" => "sunting",
'editsectionhint' => 'Sunting bagian: $1',
"toc" => "Daftar isi",
"showtoc" => "tampilkan",
"hidetoc" => "sembunyikan",
"thisisdeleted" => "Lihat atau kembalikan $1?",
'viewdeleted' => 'Lihat $1?',
'restorelink' => '$1 suntingan yang telah dihapus', # no need for plural
'feedlinks' => 'Asupan:',
'feed-invalid' => 'Tipe permintaan asupan tidak tepat.',
'feed-atom' => 'Atom',
'feed-rss' => 'RSS',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'Artikel',
'nstab-user' => 'Halaman pengguna',
'nstab-media' => 'Halaman media',
'nstab-special' => 'Istimewa',
'nstab-project' => 'Halaman proyek',
'nstab-image' => 'Berkas',
'nstab-mediawiki' => 'Pesan sistem',
'nstab-template' => 'Templat',
'nstab-help' => 'Bantuan',
'nstab-category' => 'Kategori',

# Main script and global functions
#
"nosuchaction" => "Tidak ada tindakan tersebut",
"nosuchactiontext" => "Tindakan yang dispesifikasikan oleh URL tersebut tidak dikenal oleh wiki.",
"nosuchspecialpage" => "Tidak ada halaman istimewa tersebut",
"nospecialpagetext" => "Anda telah meminta halaman istimewa yang tidak dikenal oleh wiki.",

# General errors
#
"error" => "Kesalahan",
"databaseerror" => "Kesalahan basis data",
"dberrortext" => "Ada kesalahan sintaks pada permintaan basis data. Kesalahan ini mungkin menandakan adanya ''bug'' dalam perangkat lunak. Permintaan basis data yang terakhir adalah: <blockquote><tt>$1</tt></blockquote> dari dalam fungsi \"<tt>$2</tt>\". Kesalahan MySQL \"<tt>$3: $4</tt>\".",
"dberrortextcl" => "Ada kesalahan sintaks pada permintaan basis data. Permintaan basis data yang terakhir adalah: \"$1\" dari dalam fungsi \"$2\". Kesalahan MySQL \"$3: $4\".",
'noconnect' => 'Maaf! Wiki mengalami masalah teknis dan tidak dapat menghubungi basis data.<br />$1',
"nodb" => "Tidak dapat memilih basis data $1",
"cachederror" => "Berikut ini adalah salinan <em>cache</em> dari halaman yang diminta, yang mungkin tidak up-to-date.",
'laggedslavemode'   => 'Peringatan: Halaman mungkin tidak berisi perubahan terbaru.',
"readonly" => "Basis data dikunci",
"enterlockreason" => "Masukkan alasan penguncian, termasuk perkiraan kapan kunci akan dibuka",
"readonlytext" => "Basis data sedang dikunci terhadap masukan baru. Pengurus yang melakukan penguncian memberikan penjelasan sebagai berikut: <p>$1",
"missingarticle" => "Basis data tidak menemukan teks bagi halaman yang seharusnya mempunyai teks, yaitu halaman \"$1\".

Ini biasanya disebabkan karena diff yang kadaluwarsa atau karena pranala lama kepada halaman telah dihapus.

Jika ini bukan sebabnya, Anda mungkin menemukan bug dalam perangkat lunak. Silakan laporkan hal ini kepada pengurus, dengan mencantumkan URL halaman yang bermasalah tersebut",
'readonly_lag' => 'Basis data telah dikunci otomatis selagi basis data sekunder melakukan sinkronisasi dengan basis data utama',
"internalerror" => "Kesalahan internal",
"filecopyerror" => "Tidak dapat menyalin berkas \"$1\" ke \"$2\".",
"filerenameerror" => "Tidak dapat mengubah nama berkas \"$1\" menjadi \"$2\".",
"filedeleteerror" => "Tidak dapat menghapus berkas \"$1\".",
"filenotfound" => "Tidak dapat menemukan berkas \"$1\".",
"unexpected" => "Nilai di luar jangkauan: \"$1\"=\"$2\".",
"formerror" => "Kesalahan: Tidak dapat mengirimkan formulir",
"badarticleerror" => "Tindakan ini tidak dapat dilaksanakan di halaman ini.",
"cannotdelete" => "Tidak dapat menghapus halaman atau berkas yang diminta.",
"badtitle" => "Judul tidak sah",
"badtitletext" => "Judul halaman yang diminta tidak sah, kosong, atau judul antarbahasa atau antarwiki yang salah sambung.",
"perfdisabled" => "Maaf! Fitur ini dimatikan sementara karena memperlambat basis data hingga tidak ada yang dapat menggunakan wiki ini.",
"perfdisabledsub" => "Ini adalah salinan tersimpan dari $1:",
"perfcached" => "Data berikut ini diambil dari <em>cache</em> dan mungkin bukan data mutakhir:",
'perfcachedts' => 'Data berikut ini diambil dari <em>cache</em>, dan terakhir diperbarui pada $1.',
"wrong_wfQuery_params" => "Parameter salah ke wfQuery()<br />Fungsi: $1<br />Permintaan: $2",
"viewsource" => "Lihat sumber",
'viewsourcefor' => 'dari $1',
'protectedtext' => 'Halaman ini telah dikunci untuk menghindari penyuntingan.

Anda dapat melihat atau menyalin sumber halaman ini:',
'protectedinterface' => 'Halaman ini berisi teks antarmuka untuk digunakan oleh perangkat lunak dan telah dikunci untuk menghindari kesalahan.',
'editinginterface' => "'''Peringatan:''' Anda menyunting halaman yang digunakan untuk menyediakan teks antarmuka dengan perangkat lunak. Perubahan teks ini akan mempengaruhi tampilan pada pengguna lain.",
'sqlhidden' => '(Permintaan SQL disembunyikan)',

# Login and logout pages
#
"logouttitle" => "Keluar log pengguna",
"logouttext" => "Anda telah keluar log dari sistem. Anda dapat terus menggunakan {{SITENAME}} secara anonim, atau Anda dapat masuk log lagi sebagai pengguna yang sama atau pengguna yang lain. Perhatikan bahwa beberapa halaman mungkin masih terus menunjukkan bahwa Anda masih masuk log sampai Anda membersihkan <em>cache</em> penjelajah web Anda",

"welcomecreation" => "== Selamat datang, $1! ==

Akun Anda telah dibuat. Jangan lupa mengatur konfigurasi {{SITENAME}} Anda.",

"loginpagetitle" => "Masuk log pengguna",
"yourname" => "Nama pengguna",
"yourpassword" => "Kata sandi",
"yourpasswordagain" => "Ulangi kata sandi",
"remembermypassword" => "Ingat kata sandi",
'yourdomainname'       => 'Domain Anda',
'externaldberror'      => 'Telah terjadi kesalahan otentikasi basis data eksternal atau Anda tidak diizinkan melakukan kemaskini terhadap akun eksternal Anda.',
"loginproblem" => "<strong>Ada masalah dengan proses masuk log Anda.</strong><br />Silakan coba lagi!",
"alreadyloggedin" => "<strong>Pengguna $1, Anda sudah masuk log!</strong><br />",

"login" => "Masuk log",
"loginprompt" => "Anda harus mengaktifkan ''cookies'' untuk dapat masuk log ke {{SITENAME}}.",
"userlogin" => "Masuk log / buat akun",
"logout" => "Keluar log",
"userlogout" => "Keluar log",
"notloggedin" => "Belum masuk log",
'nologin'	=> 'Belum mempunyai nama pengguna? $1.',
'nologinlink'	=> 'Daftarkan akun baru',
"createaccount" => "Buat akun baru",
'gotaccount'	=> 'Sudah terdaftar sebagai pengguna? $1.',
'gotaccountlink'	=> 'Masuk log',
"createaccountmail" => "melalui surat-e",
"badretype" => "Kata sandi yang Anda masukkan salah.",
"userexists" => "Nama pengguna yang Anda masukkan telah dipakai. Silakan pilih nama yang lain.",
"youremail" => "Surat elektronik *:",
'username'		=> 'Nama pengguna:',
'uid'			=> 'ID pengguna:',
"yourrealname" => "Nama asli *:",
'yourlanguage'  => 'Bahasa antarmuka:',
'yourvariant'  => 'Varian bahasa',
"yournick" => "Nama samaran (untuk tanda tangan):",
'badsig'		=> 'Tanda tangan teks murni tak tepat; periksa tag HTML.',
'email'			=> 'Surat elektronik',
'prefs-help-email-enotif' => 'Alamat ini juga digunakan untuk mengirim surat-e notifikasi pada Anda jika Anda memilih pilihan tersebut.',
'prefs-help-realname' => '* <strong>Nama asli</strong> (tidak wajib): jika Anda memberikannya, nama asli Anda akan digunakan untuk memberi pengenalan atas hasil kerja Anda.',
'loginerror' => 'Kesalahan masuk log',
'prefs-help-email' => '* <strong>Surat elektronik</strong> (tidak wajib): Memungkinkan orang lain untuk menghubungi Anda melalui situs tanpa perlu memberikan alamat email Anda kepada mereka, dan juga dapat digunakan untuk mengirimkan kata sandi baru jika Anda lupa kata sandi Anda.',
"nocookiesnew" => "Akun pengguna telah dibuat, tetapi Anda belum masuk log. {{SITENAME}} menggunakan ''cookies'' untuk log pengguna. ''Cookies'' pada penjelajah web Anda dimatikan. Silakan aktifkan dan masuk log kembali dengan nama pengguna dan kata sandi Anda.",
"nocookieslogin" => "{{SITENAME}} menggunakan ''cookies'' untuk log penggunanya. ''Cookies'' pada penjelajah web Anda dimatikan. Silakan aktifkan dan coba lagi.",
"noname" => "Nama pengguna yang Anda masukkan tidak sah.",
"loginsuccesstitle" => "Berhasil masuk log",
"loginsuccess" => "'''Anda sekarang masuk log di {{SITENAME}} sebagai \"$1\".'''",
"nosuchuser" => "Tidak ada pengguna dengan nama \"$1\". Periksalah ejaan Anda, atau gunakan formulir di bawah ini untuk membuka akun baru.",
'nosuchusershort' => "Tidak ada pengguna dengan nama \"$1\". Periksalah ejaan Anda.",
'nouserspecified'	=> 'Anda harus memasukkan nama pengguna.',
"wrongpassword" => "Kata sandi yang Anda masukkan salah. Silakan coba lagi.",
'wrongpasswordempty'		=> 'Anda tidak memasukkan kata sandi. Silakan coba lagi.',
"mailmypassword" => "Kirimkan kata sandi baru",
"passwordremindertitle" => "Peringatan kata sandi dari {{SITENAME}}",
"passwordremindertext" => "Seseorang (mungkin Anda, dari alamat IP $1) meminta kami mengirimkan kata sandi yang baru untuk {{SITENAME}} ($4). Kata sandi untuk pengguna \"$2\" sekarang adalah \"$3\". Anda disarankan segera masuk log dan mengganti kata sandi.",

"noemail" => "Tidak ada alamat surat-e yang tercatat untuk pengguna \"$1\".",
"passwordsent" => "Kata sandi baru telah dikirimkan ke surat-e yang didaftarkan untuk \"$1\". Silakan masuk log kembali setelah menerima surat-e tersebut.",
'blocked-mailpassword' => 'Alamat IP Anda diblokir dari penyuntingan dan karenanya tidak diizinkan menggunakan fungsi pengingat kata sandi untuk mencegah penyalahgunaan.',
'eauthentsent' =>  'Sebuah surat elektronik untuk konfirmasi telah dikirim ke alamat surat elektronik Anda. Anda harus mengikuti instruksi di dalam surat elektronik tersebut untuk melakukan konfirmasi bahwa alamat tersebut adalah benar kepunyaan Anda. {{SITENAME}} tidak akan mengaktifkan fitur surat elektronik jika langkah ini belum dilakukan.',
'throttled-mailpassword' => 'Suatu pengingat kata sandi telah dikirimkan dalam $1 jam terakhir. Untuk menghindari penyalahgunaan, hanya satu kata sandi yang akan dikirimkan setiap $1 jam.',
"mailerror" => "Kesalahan dalam mengirimkan surat-e: $1",
'acct_creation_throttle_hit' => 'Maaf, Anda telah membuat $1 akun. Anda tidak dapat membuat akun lagi.',
'emailauthenticated'        => 'Alamat surat-e Anda telah dikonfirmasi pada $1.',
'emailnotauthenticated'     => 'Alamat surat-e Anda belum dikonfirmasi. Sebelum dikonfirmasi Anda tidak bisa menggunakan fitur surat elektronik.',
'noemailprefs'              => 'Anda harus memasukkan suatu alamat surat-e untuk dapat menggunakan fitur ini.',
'emailconfirmlink' => 'Konfirmasikan alamat surat-e Anda',
'invalidemailaddress'	=> 'Alamat surat-e ini tidak dapat diterima karena formatnya tidak sesuai. Harap masukkan alamat surat-e dalam format yang benar atau kosongkan isian tersebut.',
'accountcreated' => 'Akun dibuat',
'accountcreatedtext' => 'Akun pengguna untuk $1 telah dibuat.',

# Edit page toolbar
"bold_sample" => "Teks ini akan dicetak tebal",
"bold_tip" => "Cetak tebal",
"italic_sample" => "Teks ini akan dicetak miring",
"italic_tip" => "Cetak miring",
"link_sample" => "Judul pranala",
"link_tip" => "Pranala internal",
"extlink_sample" => "http://www.contoh.com/ judul pranala",
"extlink_tip" => "Pranala luar (jangan lupa awalan http:// )",
"headline_sample" => "Teks judul",
"headline_tip" => "Judul aras 2",
"math_sample" => "Masukkan rumus di sini",
"math_tip" => "Rumus matematika (LaTeX)",
"nowiki_sample" => "Teks ini tidak akan diformat",
"nowiki_tip" => "Abaikan pemformatan wiki",
"image_sample" => "Contoh.jpg",
"image_tip" => "Cantumkan berkas",
"media_sample" => "Contoh.ogg",
"media_tip" => "Pranala berkas media",
"sig_tip" => "Tanda tangan Anda dengan tanda waktu",
"hr_tip" => "Garis horisontal",

# Edit pages
#
"summary" => "Ringkasan",
"subject" => "Subyek/judul",
"minoredit" => "Ini adalah suntingan kecil.",
"watchthis" => "Pantau artikel ini",
"savearticle" => "Simpan halaman",
"preview" => "Pratayang",
"showpreview" => "Lihat pratayang",
'showlivepreview'	=> 'Pratayang langsung',
'showdiff'	=> 'Perlihatkan perubahan',
'anoneditwarning' => 'Anda tidak terdaftar masuk. Alamat IP Anda akan tercatat dalam sejarah (versi terdahulu) halaman ini.',
'missingsummary' => "'''Peringatan:''' Anda tidak memasukkan ringkasan penyuntingan. Jika Anda kembali menekan tombol Simpan, suntingan Anda akan disimpan tanpa ringkasan penyuntingan.",
'missingcommenttext' => 'Harap masukkan komentar di bawah ini.',
"blockedtitle" => "Pengguna diblokir",
'blockedtext' => "<big>'''Nama pengguna atau alamat IP Anda telah diblokir.'''</big>

Blokir dilakukan oleh $1. Alasan yang diberikan adalah ''$2''. 

Anda dapat menghubungi $1 atau [[{{ns:project}}:Pengurus|pengurus lainnya]] untuk membicarakan hal ini.

Anda tidak dapat menggunakan fitur 'Kirim surat-e pengguna ini' kecuali Anda telah memasukkan alamat surat-e yang sah di [[{{ns:project}}:Preferences|preferensi]] Anda.

Alamat IP Anda adalah $3. Sertakan alamat IP ini pada setiap pertanyaan yang Anda buat",
'blockedoriginalsource' => "Isi sumber '''$1''' ditunjukkan berikut ini:",
'blockededitsource' => "Teks '''suntingan Anda''' terhadap '''$1''' ditunjukkan berikut ini:",
"whitelistedittitle" => "Perlu masuk log untuk menyunting",
"whitelistedittext" => "Anda harus $1 untuk dapat menyunting artikel.",
"whitelistreadtitle" => "Perlu masuk log untuk membaca",
"whitelistreadtext" => "Anda harus [[{{ns:special}}:Userlogin|masuk log]] untuk dapat membaca artikel.",
"whitelistacctitle" => "Anda tidak diperbolehkan untuk membuat akun",
"whitelistacctext" => "Untuk dapat membuat akun dalam Wiki ini, Anda harus [[{{ns:special}}:Userlogin|login]] dan mempunyai izin yang tepat.",
'confirmedittitle' => 'Konfirmasi surat-e diperlukan untuk melakukan penyuntingan',
'confirmedittext' => 'Anda harus mengkonfirmasikan dulu alamat surat-e Anda sebelum menyunting halaman. Harap masukkan dan validasikan alamat surat-e Anda sebelum melakukan penyuntingan. Alamat surat-e dapat diubah melalui [[{{ns:special}}:Preferences|halaman preferensi]]',
"loginreqtitle" => "Harus masuk log",
'loginreqlink' => 'masuk log',
"loginreqpagetext" => "Anda harus $1 untuk dapat melihat halaman lainnya.",
"accmailtitle" => "Kata sandi telah terkirim.",
"accmailtext" => "Kata sandi untuk '$1' telah dikirimkan ke $2.",
"newarticle" => "(Baru)",
"newarticletext" => "Anda mengikuti pranala ke halaman yang belum ada. Untuk membuat halaman tersebut, ketiklah isi halaman di kotak di bawah ini (lihat [[{{ns:help}}:Isi|halaman bantuan]] untuk informasi lebih lanjut). Jika Anda tanpa sengaja sampai ke halaman ini, klik tombol '''back''' di penjelajah web anda.",

"anontalkpagetext" => "---- ''Ini adalah halaman diskusi seorang pengguna anonim yang belum membuat akun atau tidak menggunakannya. Karena ia tidak membuat akun, kami terpaksa harus memakai alamat IP-nya untuk mengenalinya. Alamat IP seperti ini dapat dipakai oleh beberapa pengguna yang berbeda. Jika Anda adalah seorang pengguna anonim dan merasa mendapatkan komentar-komentar miring, silakan [[{{ns:special}}:Userlogin|membuat akun atau masuk log]] untuk menghindari kerancuan dengan pengguna anonim lain di lain waktu.''",
'noarticletext' => 'Saat ini tidak ada teks dalam halaman ini. Anda dapat [[{{ns:special}}:Search/{{PAGENAME}}|melakukan pencarian untuk judul halaman ini]] di halaman-halaman lain atau [{{fullurl:{{FULLPAGENAME}}|action=edit}} sunting halaman ini].',

'clearyourcache' => "'''Catatan:''' Setelah menyimpan preferensi, Anda perlu membersihkan <em>cache</em> penjelajah web Anda untuk melihat perubahan. '''Mozilla / Firefox / Safari:''' tekan ''Ctrl-Shift-R'' (''Cmd-Shift-R'' pada Apple Mac); '''IE:''' tekan ''Ctrl-F5''; '''Konqueror:''': tekan ''F5''; '''Opera''' bersihkan <em>cache</em> melalui menu ''Tools→Preferences''.",
'usercssjsyoucanpreview' => "<strong>Tips:</strong> Gunakan tombol 'Lihat pratayang' untuk menguji CSS/JS baru Anda sebelum menyimpannya.",
'usercsspreview' => "'''Ingatlah bahwa yang Anda lihat hanyalah pratayang CSS Anda, dan bahwa pratayang tersebut belum disimpan!'''",
'userjspreview' => "'''Ingatlah bahwa yang Anda lihat hanyalah pratayang JavaScript Anda, dan bahwa pratayang tersebut belum disimpan!'''",
'userinvalidcssjstitle' => "'''Peringatan:''' Kulit \"$1\" tidak ditemukan. Harap diingat bahwa halaman .css dan .js menggunakan huruf kecil, contoh {{ns:user}}:Foo/monobook.css dan bukannya {{ns:user}}:Foo/Monobook.css.",
"updated" => "(Diperbarui)",
"note" => "<strong>Catatan:</strong>",
"previewnote" => "Ingatlah bahwa ini hanyalah pratayang yang belum disimpan!",
'session_fail_preview' => '<strong>Maaf, kami tidak dapat mengolah suntingan Anda akibat terhapusnya data sesi. Silakan coba sekali lagi. Jika masih tidak berhasil, cobalah keluar log dan masuk log kembali.</strong>',
"previewconflict" => "Pratayang ini mencerminkan teks pada bagian atas kotak suntingan teks sebagaimana akan terlihat bila Anda menyimpannya.",
'session_fail_preview_html' => '<strong>Maaf! Kami tidak dapat memproses suntingan Anda karena hilangnya data sesi.</strong>

\'\'Karena wiki ini mengizinkan penggunaan HTML mentah, pratayang disembunyikan sebagai pencegahan terhadap serangan JavaScript.\'\'

<strong>Jika ini merupakan upaya suntingan yang sahih, silakan coba lagi. Jika masih tetap tidak berhasil, cobalah keluar log dan masuk kembali.</strong>',
'importing' => 'Sedang mengimpor $1',
"editing" => "Menyunting $1",
'editinguser' => "Menyunting $1",
'editingsection' => 'Menyunting $1 (bagian)',
'editingcomment' => 'Menyunting $1 (komentar)',
"editconflict" => "Konflik penyuntingan: $1",
"explainconflict" => "Orang lain telah menyunting halaman ini sejak Anda mulai menyuntingnya. Bagian atas teks ini mengandung teks halaman saat ini. Perubahan yang Anda lakukan ditunjukkan pada bagian bawah teks. Anda hanya perlu menggabungkan perubahan Anda dengan teks yang telah ada. <strong>Hanya</strong> teks pada bagian atas halamanlah yang akan disimpan apabila Anda menekan \"Simpan halaman\".<p>",
"yourtext" => "Teks Anda",
"storedversion" => "Versi tersimpan",
'nonunicodebrowser' => "<strong>PERINGATAN: Penjelajah web Anda tidak mendukung Unicode, silakan ganti penjelajah web Anda sebelum menyunting artikel.</strong>",
"editingold" => "'''Peringatan:''' Anda menyunting revisi lama suatu halaman. Jika Anda menyimpannya, perubahan-perubahan yang dibuat sejak revisi ini akan hilang.",
"yourdiff" => "Perbedaan",
"copyrightwarning" => "Perhatikan bahwa semua sumbangan terhadap {{SITENAME}} dianggap dilisensikan di bawah lisensi $2 (lihat $1 untuk informasi lebih lanjut). Jika Anda tidak ingin tulisan Anda disunting dan disebarkan ke halaman web yang lain, jangan kirimkan artikel Anda ke sini.<br />Anda juga berjanji bahwa ini adalah hasil karya Anda sendiri, atau disalin dari sumber milik umum atau sumber bebas yang lain. <strong>JANGAN KIRIMKAN KARYA YANG DILINDUNGI HAK CIPTA TANPA IZIN!</strong>",
'copyrightwarning2' => "Perhatikan bahwa semua sumbangan terhadap {{SITENAME}} dapat disunting, diubah, atau dihapus oleh penyumbang lainnya. Jika Anda tidak ingin tulisan Anda disunting orang lain, jangan kirimkan artikel Anda ke sini.<br />Anda juga berjanji bahwa ini adalah hasil karya Anda sendiri, atau disalin dari sumber milik umum atau sumber bebas yang lain (lihat $1 untuk informasi lebih lanjut). <strong>JANGAN KIRIMKAN KARYA YANG DILINDUNGI HAK CIPTA TANPA IZIN!</strong>",
"longpagewarning" => "'''PERINGATAN: Halaman ini panjangnya adalah $1 kilobita; beberapa penjelajah web mungkin mengalami masalah dalam menyunting halaman yang panjangnya 32 kb atau lebih. Harap pertimbangkan untuk memecah halaman menjadi beberapa bagian yang lebih kecil.'''",
'longpageerror' => "<strong>KESALAHAN: Teks yang Anda kirimkan sebesar $1 kilobita, yang berarti lebih besar dari jumlah maksimum $2 kilobita. Teks tidak dapat disimpan.</strong>",
"readonlywarning" => "<strong>PERINGATAN: Basis data sedang dikunci karena pemeliharaan, sehingga saat ini Anda tidak akan dapat menyimpan hasil penyuntingan Anda. Anda mungkin perlu memindahkan hasil penyuntingan Anda ini ke tempat lain untuk disimpan belakangan.</strong>",
"protectedpagewarning" => "<strong>PERINGATAN:  Halaman ini telah dikunci sehingga hanya pemakai dengan hak akses pengurus saja yang dapat menyuntingnya.</strong>",
'semiprotectedpagewarning' => "'''Catatan:''' Halaman ini sedang dilindungi, sehingga hanya pengguna terdaftar yang bisa menyuntingnya.",
'templatesused' => 'Templat yang digunakan di halaman ini:',
'edittools' => '<!-- Teks di sini akan dimunculkan dibawah isian suntingan dan pemuatan.-->',
'nocreatetitle' => 'Pembuatan halaman baru dibatasi',
'nocreatetext' => 'Situs ini membatasi kemampuan membuat halaman baru. Anda dapat kembali dan menyunting halaman yang telah ada, atau silakan [[{{ns:special}}:Userlogin|masuk log atau mendaftar]]',
'cantcreateaccounttitle' => 'Akun tak dapat dibuat',
'cantcreateaccounttext' => 'Pembuatan akun dari alamat IP ini (<b>$1</b>) diblokir. 
Hal ini mungkin disebabkan adanya vandalisme berulang yang berasal dari sekolah atau penyedia jasa Internet Anda.',

# History pages
#
"revhistory" => "Sejarah revisi",
'viewpagelogs' => 'Lihat log halaman ini',
"nohistory" => "Tidak ada sejarah penyuntingan untuk halaman ini",
"revnotfound" => "Revisi tidak ditemukan",
"revnotfoundtext" => "Revisi lama halaman yang Anda minta tidak dapat ditemukan. Silakan periksa URL yang digunakan untuk mengakses halaman ini.",
"loadhist" => "Memuat halaman sejarah",
"currentrev" => "Revisi sekarang",
"revisionasof" => "Revisi per $1",
'revision-info' => 'Revisi per $1; $2',
'previousrevision'      => '← Revisi sebelumnya',
'nextrevision'          => 'Revisi selanjutnya →',
'currentrevisionlink'   => 'Revisi sekarang',
"cur" => "skr",
"next" => "selanjutnya",
"last" => "akhir",
"orig" => "asli",
"histlegend" => "Cara membandingkan: tandai ''radio button'' versi-versi yang ingin dibandingkan, lalu tekan ENTER atau tombol di bawah.<br />Keterangan: (skr) = perbedaan dengan versi sekarang, (akhir) = perbedaan dengan versi sebelumnya, m = suntingan kecil",
'deletedrev' => '[dihapus]',
'histfirst' => 'Paling lama',
'histlast' => 'Paling baru',
'rev-deleted-comment' => '(komentar dihapus)',
'rev-deleted-user' => '(nama pengguna dihapus)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">Riwayat revisi halaman ini telah dihapus dari arsip publik. Detil mungkin tersedia di [{{fullurl:{{ns:special}}:Log/delete|page={{PAGENAMEE}}}} log penghapusan].</div>',
'rev-deleted-text-view' => '<div class="mw-warning plainlinks">Riwayat revisi halaman ini telah dihapus dari arsip publik. Sebagai seorang pengurus situs, Anda dapat melihatnya; detil mungkin tersedia di [{{fullurl:{{ns:special}}:Log/delete|page={{PAGENAMEE}}}} log penghapusan].</div>',
'rev-delundel' => 'tampilkan/sembunyikan',

'history-feed-title' => 'Riwayat revisi',
'history-feed-description'	=> 'Riwayat revisi halaman ini di wiki',
'history-feed-item-nocomment' => '$1 pada $2', # user at time
'history-feed-empty' => 'Halaman yang diminta tak ditemukan. Kemungkinan telah dihapus dari wiki, atau diberi nama baru. Coba [[{{ns:special}}:Search|lakukan pencarian di wiki]] untuk halaman baru yang relevan.',

# Revision deletion
#
'revisiondelete' => 'Hapus/batal hapus revisi',
'revdelete-nooldid-title' => 'Target revisi tak ditemukan',
'revdelete-nooldid-text' => 'Anda belum memberikan target revisi untuk menjalankan fungsi ini.',
'revdelete-selected' => 'Revisi terpilih dari [[:$1]]:',
'revdelete-text' => "Revisi yang telah dihapus akan tetap muncul di halaman versi terdahulu, tapi teks isi tidak bisa diakses publik.

Pengurus lain akan dapat mengakses isi tersebunyi dan dapat membatalkan penghapusan melalui antarmuka yang sama, kecuali jika ada pembatasan lain yang dibuat oleh operator situs",
'revdelete-legend' => 'Atur batasan revisi:',
'revdelete-hide-text' => 'Sembunyikan teks revisi',
'revdelete-hide-comment' => 'Tampilkan/sembunyikan ringkasan suntingan',
'revdelete-hide-user' => 'Sembunyikan nama pengguna/IP penyunting',
'revdelete-hide-restricted' => 'Terapkan pembatasan bagi pengurus dan pengguna lainnya',
'revdelete-log' => 'Log ringkasan:',
'revdelete-submit' => 'Terapkan pada revisi terpilih',
'revdelete-logentry' => 'ubah tampilan revisi untuk [[$1]]',

# Diffs
#
"difference" => "(Perbedaan antarrevisi)",
"loadingrev" => "memuat revisi untuk dibandingkan",
"lineno" => "Baris $1:",
"editcurrent" => "Sunting versi sekarang dari halaman ini",
'selectnewerversionfordiff' => 'Pilih sebuah versi yang lebih baru untuk perbandingan',
'selectolderversionfordiff' => 'Pilih sebuah versi yang lebih lama untuk perbandingan',
'compareselectedversions' => 'Bandingkan versi terpilih',

# Search results
#
"searchresults" => "Hasil pencarian",
"searchresulttext" => "Untuk informasi lebih lanjut tentang pencarian di {{SITENAME}}, lihat [[{{ns:project}}:Pencarian|Melakukan pencarian di {{SITENAME}}]].",
'searchsubtitle' => "Anda mencari '''[[:$1]]'''",
'searchsubtitleinvalid' => "Anda mencari '''$1'''",
"badquery" => "Format permintaan pencarian salah",
"badquerytext" => "Kami tidak dapat memproses permintaan Anda. Hal ini mungkin disebabkan karena Anda mencoba mencari kata yang panjangnya kurang dari tiga huruf, yang masih belum didukung oleh sistem ini. Hal ini juga dapat disebabkan oleh kesalahan pengetikan ekspresi, misalnya \"fish and and scales\". Silakan coba permintaan yang lain.",
"matchtotals" => "Permintaan \"$1\" cocok dengan $2 judul halaman dan teks dari $3 artikel.",
'noexactmatch' => "'''Tidak ada halaman yang berjudul \"$1\".''' Anda dapat [[:$1|membuat halaman ini]].",
"titlematches" => "Judul artikel yang sama",
"notitlematches" => "Tidak ada judul halaman yang cocok",
"textmatches" => "Teks artikel yang cocok",
"notextmatches" => "Tidak ada teks halaman yang cocok",
"prevn" => "$1 sebelumnya",
"nextn" => "$1 selanjutnya",
"viewprevnext" => "Lihat ($1) ($2) ($3).",
"showingresults" => "Di bawah ini ditampilkan <strong>$1</strong> hasil, dimulai dari #<strong>$2</strong>.",
"showingresultsnum" => "Di bawah ini ditampilkan <strong>$3</strong> hasil, dimulai dari #<strong>$2</strong>.",
"nonefound" => "'''Catatan''': Kegagalan pencarian biasanya disebabkan oleh pencarian kata-kata umum, seperti \"have\" dan \"from\", yang biasanya tidak diindeks, atau dengan menentukan lebih dari satu aturan pencarian (hanya halaman yang mengandung semua aturan pencarianlah yang akan ditampilkan dalam hasil pencarian)",
"powersearch" => "Cari",
'powersearchtext' => "Cari dalam ruang nama:<br />$1<br />$2 Juga tampilkan peralihan<br />Cari $3 $9",
"searchdisabled" => '<p style="margin: 1.5em 2em 1em">Mesin pencari {{SITENAME}} sementara dimatikan karena masalah kinerja. Anda dapat mencari melalui Google untuk sementara waktu. <span style="font-size: 89%; display: block; margin-left: .2em">Indeks Google untuk {{SITENAME}} mungkin belum diperbaharui. Jika istilah pencarian berisi garis bawah, gantikan dengan spasi.</span></p>',
"blanknamespace" => "(Utama)",

# Preferences page
#
"preferences" => "Preferensi",
'mypreferences'	=> 'Preferensi saya',
"prefsnologin" => "Belum masuk log",
"prefsnologintext" => "Anda harus [[{{ns:special}}:Userlogin|masuk log]] untuk menetapkan preferensi Anda.",

"prefsreset" => "Preferensi telah dikembalikan ke konfigurasi baku.",
"qbsettings" => "Pengaturan quickbar",
"changepassword" => "Ganti kata sandi",
"skin" => "Kulit",
"math" => "Penggambaran math",
"dateformat" => "Format tanggal",
'datedefault'		=> 'Tak ada preferensi',
'datetime' => 'Tanggal dan waktu',
"math_failure" => "Gagal memparse",
"math_unknown_error" => "Kesalahan yang tidak diketahui",
"math_unknown_function" => "fungsi yang tidak diketahui",
"math_lexing_error" => "kesalahan lexing",
"math_syntax_error" => "kesalahan sintaks",
"math_image_error" => "Konversi PNG gagal; periksa apakah latex, dvips, gs, dan convert terinstal dengan benar",
"math_bad_tmpdir" => "Tidak dapat menulisi atau membuat direktori sementara math",
"math_bad_output" => "Tidak dapat menulisi atau membuat direktori keluaran math",
"math_notexvc" => "Executable texvc hilang; silakan lihat math/README untuk cara konfigurasi.",
'prefs-personal' => 'Profil',
'prefs-rc' => 'Perubahan terbaru',
'prefs-watchlist' => 'Daftar pantauan',
'prefs-watchlist-days' => 'Jumlah hari untuk ditampilkan di daftar pantauan:',
'prefs-watchlist-edits' => 'Jumlah hari untuk ditampilkan di daftar pantauan yang lebih lengkap:',
'prefs-misc' => 'Lain-lain',
"saveprefs" => "Simpan preferensi",
"resetprefs" => "Pengaturan baku",
"oldpassword" => "Kata sandi lama",
"newpassword" => "Kata sandi baru",
"retypenew" => "Ketik ulang kata sandi baru",
"textboxsize" => "Penyuntingan",
"rows" => "Baris",
"columns" => "Kolom",
"searchresultshead" => "Pencarian",
"resultsperpage" => "Hasil per halaman",
"contextlines" => "Baris ditampilkan per hasil",
"contextchars" => "Karakter untuk konteks per baris",
"stubthreshold" => "Ambang batas tampilan rintisan",
"recentchangescount" => "Jumlah judul dalam perubahan terbaru",
"savedprefs" => "Preferensi Anda telah disimpan",
'timezonelegend' => 'Zona waktu',
"timezonetext" => "Masukkan perbedaan waktu (dalam jam) antara waktu setempat dengan waktu server (UTC).",
"localtime" => "Waktu setempat",
"timezoneoffset" => "Perbedaan",
"servertime" => "Waktu server sekarang adalah",
"guesstimezone" => "Isikan dari penjelajah web",
'allowemail'		=> 'Ijinkan pengguna lain mengirim surat-e',
"defaultns" => "Cari dalam ruang nama berikut ini secara baku:",
'default' => 'baku',
'files'			=> 'Berkas',

# User rights
'userrights-lookup-user' => 'Mengatur grup pengguna',
'userrights-user-editname' => 'Masukkan nama pengguna:',
'editusergroup' => 'Sunting kelompok pengguna',

# user groups editing
'userrights-editusergroup' => 'Sunting grup pengguna',
'saveusergroups' => 'Simpan kelompok pengguna',
'userrights-groupsmember' => 'Anggota dari:',
'userrights-groupsavailable' => 'Grup yang tersedia:',
'userrights-groupshelp' => "Pilih grup yang Anda ingin hapus dari atau tambahkan pada pengguna. Grup yang tak dipilih tak akan diganti. Anda dapat membatalkan pilihan dengan menekan tombol CTRL + Klik kiri",

# Groups
'group'                   => 'Grup:',
'group-bot'               => 'Bot',
'group-sysop'             => 'Pengurus',
'group-bureaucrat'        => 'Birokrat',
'group-all'               => '(semua)',

'group-bot-member'        => 'Bot',
'group-sysop-member'      => 'Pengurus',
'group-bureaucrat-member' => 'Birokrat',

'grouppage-bot' => '{{ns:project}}:Bot',
'grouppage-sysop' => '{{ns:project}}:Pengurus',
'grouppage-bureaucrat' => '{{ns:project}}:Birokrat',

# Recent changes
#
"changes" => "perubahan",
"recentchanges" => "Perubahan terbaru",

'recentchangestext' => 'Temukan perubahan terbaru dalam wiki di halaman ini.,',
'rcnote' => "Di bawah ini adalah <strong>$1</strong> perubahan terbaru dalam <strong>$2</strong> hari terakhir sampai $3.",
"rcnotefrom" => "Di bawah ini adalah perubahan sejak <strong>$2</strong> (ditampilkan sampai <strong>$1</strong> perubahan).",
"rclistfrom" => "Perlihatkan perubahan terbaru sejak $1",
'rcshowhideminor' => '$1 suntingan kecil',
'rcshowhidebots' => '$1 bot',
'rcshowhideliu' => '$1 pengguna masuk log',
'rcshowhideanons' => '$1 pengguna anon',
'rcshowhidepatr' => '$1 suntingan terpatroli',
'rcshowhidemine' => '$1 suntingan saya',
"rclinks" => "Perlihatkan $1 perubahan terbaru dalam $2 hari terakhir<br />$3",
"diff" => "beda",
"hist" => "versi terdahulu",
"hide" => "Sembunyikan",
"show" => "Tampilkan",
"minoreditletter" => "m",
"newpageletter" => "B",
'boteditletter' => 'b',
'sectionlink' => '→',
'number_of_watching_users_pageview' 	=> '[$1 pemantau]',
'rc_categories'	=> 'Batasi sampai kategori (dipisah dengan "|")',
'rc_categories_any'	=> 'Apapun',

# Upload
#
"upload" => "Pemuatan",
"uploadbtn" => "Muatkan berkas",
"reupload" => "Muat ulang",
"reuploaddesc" => "Kembali ke formulir pemuatan",
"uploadnologin" => "Belum masuk log",
"uploadnologintext" => "Anda harus [[{{ns:special}}:Userlogin|masuk log]] untuk dapat memuatkan berkas.",
'upload_directory_read_only' => 'Direktori pemuatan ($1) tidak dapat ditulis oleh server web.',
"uploaderror" => "Kesalahan pemuatan",
'uploadtext'	=> "Gunakan isian di bawah untuk memuat berkas. Gunakan [[{{ns:special}}:Imagelist|daftar berkas]] atau [[{{ns:special}}:Log/upload|log pemuatan]] untuk menampilkan atau mencari berkas atau gambar yang telah dimuat sebelumnya.

Untuk menampilkan atau menyertakan berkas atau gambar pada suatu halaman, gunakan pranala dengan format
'''<nowiki>[[{{ns:image}}:Berkas.jpg]]</nowiki>''',
'''<nowiki>[[{{ns:image}}:Berkas.png|teks alternatif]]</nowiki>''' atau
'''<nowiki>[[{{ns:media}}:Berkas.ogg]]</nowiki>''' untuk langsung menuju berkas yang dimaksud.",
"uploadlog" => "log pemuatan",
"uploadlogpage" => "Log pemuatan",
"uploadlogpagetext" => "Di bawah ini adalah log pemuatan berkas. Semua waktu yang ditunjukkan adalah waktu server (UTC).",
"filename" => "Nama berkas",
"filedesc" => "Ringkasan",
'fileuploadsummary' => 'Ringkasan:',
"filestatus" => "Status hak cipta",
"filesource" => "Sumber",
"copyrightpage" => "Project:Hak cipta",
"copyrightpagename" => "Hak cipta {{SITENAME}}",
"uploadedfiles" => "Berkas yang telah dimuat",
'ignorewarning'        => 'Abaikan peringatan dan langsung simpan berkas.',
'ignorewarnings'	=> 'Abaikan peringatan apapun',
"minlength" => "Nama berkas sekurang-kurangnya harus tiga huruf.",
'illegalfilename' => 'Nama berkas "$1" mengandung aksara yang tidak diperbolehkan ada dalam judul halaman. Silakan ubah nama berkas tersebut dan cobalah memuatkannya kembali.',
"badfilename" => "Nama berkas telah diubah menjadi \"$1\".",
"badfiletype" => "\".$1\" adalah format berkas yang tidak diizinkan.",
'largefile'		=> 'Ukuran berkas disarankan untuk tidak melebihi $1 bita; berkas ini berukuran $2 bita',
'largefileserver' => 'Berkas ini lebih besar dari pada yang diizinkan server.',
'emptyfile' => 'Berkas yang Anda muatkan kelihatannya kosong. Hal ini mungkin disebabkan karena adanya kesalahan ketik pada nama berkas. Silakan pastikan apakah Anda benar-benar ingin memuatkan berkas ini.',
'fileexists' => 'Berkas dengan nama tersebut telah ada, harap periksa $1 jika Anda tidak yakin untuk mengubahnya.',
'fileexists-forbidden' => 'Ditemukan berkas dengan nama yang sama; harap kembali dan muatkan berkas dengan nama lain. [[{{ns:image}}:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Ditemukan berkas lain dengan nama yang sama di repositori bersama; harap kembali dan muatkan berkas dengan nama lain. [[{{ns:image}}:$1|thumb|center|$1]]',
"successfulupload" => "Berhasil dimuat",
"fileuploaded" => "Berkas \"$1\" berhasil dimuatkan. Silakan ikuti pranala berikut: $2 ke halaman deskripsi dan isikan informasi tentang berkas tersebut, seperti dari mana berkas tersebut berasal, kapan berkas itu dibuat dan oleh siapa, dan informasi lain yang Anda ketahui.",
"uploadwarning" => "Peringatan pemuatan",
"savefile" => "Simpan berkas",
"uploadedimage" => "memuat \"[[$1]]\"",
"uploaddisabled" => "Maaf, pemuatan dimatikan.",
'uploaddisabledtext' => 'Pemuatan berkas di tidak diizinkan di wiki ini.',
'uploadscripted' => 'Berkas ini mengandung HTML atau kode yang mungkin mungkin diinterpretasikan dengan keliru oleh pelayar web.',
'uploadcorrupt' => 'Berkas tersebut rusak atau ekstensinya salah. Silakan periksa berkas tersebut dan muatkan kembali.',
'uploadvirus' => 'Berkas tersebut mengandung virus! Detil: $1',
'sourcefilename' => 'Nama berkas sumber',
'destfilename' => 'Nama berkas tujuan',
'watchthisupload'	=> 'Pantau halaman ini',
'filewasdeleted' => 'Suatu berkas dengan nama ini pernah dimuat dan selanjutnya dihapus. Harap cek $1 sebelum memuat lagi berkas tersebut.',

'license' => 'Jenis lisensi',
'nolicense' => 'Belum dipilih',
'upload_source_url' => ' (suatu URL valid yang dapat diakses publik)',
'upload_source_file' => ' (suatu berkas di komputer Anda)',

# Image list
#
"imagelist"		=> "Daftar berkas",
'imagelisttext' => "Di bawah ini adalah daftar '''$1''' {{plural:$1|berkas|berkas}} diurutkan $2.",
'imagelistforuser'	=> "Hanya berkas yang dimuat oleh $1.",
"getimagelist"	=> "mengambil daftar berkas",
"ilsubmit"		=> "Cari",
"showlast"		=> "Tampilkan $1 berkas terakhir diurutkan $2.",
"byname"		=> "berdasarkan nama",
"bydate"		=> "berdasarkan tanggal",
"bysize"		=> "berdasarkan ukuran",
"imgdelete"		=> "hps",
"imgdesc"		=> "desk",
'imgfile'       => 'berkas',
"imglegend"		=> "Keterangan: (desk) = lihat/sunting deskripsi berkas.",
"imghistory"	=> "Riwayat berkas",
"revertimg"		=> "kbl",
"deleteimg"		=> "hps",
'deleteimgcompletely'	=> 'Hapus semua revisi',
"imghistlegend"	=> "Keterangan: (skr) = ini adalah berkas yang sekarang, (hps) = hapus versi lama ini, (kbl) = kembalikan ke versi lama ini. <br /><em>Klik pada tanggal untuk melihat berkas yang dimuat pada tanggal tersebut</em>.",
"imagelinks"	=> "Pautan",
"linkstoimage"	=> "Halaman-halaman berikut berpaut ke berkas ini:",
"nolinkstoimage"	=> "Tidak ada halaman yang berpaut ke berkas ini.",
"sharedupload"	=> "Berkas ini adalah pemuatan bersama yang mungkin juga dipakai oleh proyek lain.",
'shareduploadwiki'	=> 'Lihat $1 untuk informasi detil.',
'shareduploadwiki-linktext' => 'halaman deskripsi berkas',
'noimage'		=> 'Tidak ada berkas dengan nama tersebut, Anda dapat $1.',
'noimage-linktext'	=> 'memuat berkas',
'uploadnewversion-linktext'	=> 'Muatkan versi yang lebih baru dari berkas ini',
'imagelist_date' => 'Tanggal',
'imagelist_name' => 'Nama',
'imagelist_user' => 'Pengguna',
'imagelist_size' => 'Ukuran (bita)',
'imagelist_description' => 'Deskripsi',
'imagelist_search_for' => 'Cari nama berkas:',

# Mime search
#
'mimesearch' => 'Pencarian MIME',
'mimetype' => 'Tipe MIME:',
'download' => 'unduh',

# Unwatchedpages
#
'unwatchedpages' => 'Halaman yang tak dipantau',

# List redirects
'listredirects' => 'Daftar pengalihan',

# Unused templates
'unusedtemplates' => 'Templat yang tak digunakan',
'unusedtemplatestext' => 'Daftar berikut adalah halaman pada ruang nama templat yang tidak dipakai di halaman manapun. Cek dahulu pranala lain ke templat tersebut sebelum menghapusnya.',
'unusedtemplateswlh' => 'pranala lain',

# Random redirect
'randomredirect' => 'Pengalihan sembarang',

# Statistics
#
"statistics" => "Statistik",
"sitestats" => "Statistik situs",
"userstats" => "Statistik pengguna",
"sitestatstext" => "Terdapat total '''$1''' halaman dalam basis data. Ini termasuk halaman \"pembicaraan\", halaman tentang {{SITENAME}}, halaman  \"rintisan\" minimum, halaman peralihan, dan halaman-halaman lain yang mungkin tidak masuk kriteria artikel. Selain itu, ada '''$2''' halaman yang mungkin termasuk artikel yang sah.

'''$8''' berkas telah dimuat.

Ada sejumlah '''$3''' penampilan halaman, dan sejumlah '''$4''' penyuntingan sejak wiki ini dimulai. Ini berarti rata-rata '''$5''' suntingan per halaman, dan '''$6''' penampilan per penyuntingan.

[http://meta.wikimedia.org/wiki/Help:Job_queue Antrian job] adalah sebanyak '''$7'''.",
"userstatstext" => "Terdapat '''$1''' pengguna terdaftar. '''$2''' (atau '''$4%''') diantaranya adalah $5.",
'statistics-mostpopular' => 'Halaman yang paling banyak ditampilkan',

"disambiguations" => "Halaman disambiguasi",
'disambiguationspage'	=> '{{ns:template}}:Disambig',
"disambiguationstext" => "Halaman-halaman berikut ini berpaut ke sebuah halaman disambiguasi. Halaman-halaman tersebut seharusnya berpaut ke topik-topik yang tepat.<br />Satu halaman dianggap sebagai disambiguation apabila halaman tersebut disambung dari $1.<br />Pranala dari ruang nama lain <em>tidak</em> terdaftar di sini.",

"doubleredirects" => "Pengalihan ganda",
"doubleredirectstext" => "Setiap baris mengandung pranala ke peralihan pertama dan kedua, dan juga baris pertama dari teks peralihan kedua, yang biasanya memberikan artikel tujuan yang \"sesungguhnya\", yang seharusnya ditunjuk oleh peralihan yang pertama.",

"brokenredirects" => "Pengalihan rusak",
"brokenredirectstext" => "Peralihan halaman berikut berpaut ke halaman yang tidak ada.",


# Miscellaneous special pages
#
'nbytes'      => '$1 bita',  # no need for plural
'ncategories' => '$1 kategori',  # no need for plural
'nlinks'      => '$1 pranala',  # no need for plural
'nmembers'    => '$1 pengguna',  # no need for plural
'nrevisions'  => '$1 revisi',  # no need for plural
'nviews'      => '$1 penampilan',  # no need for plural

"lonelypages" => "Halaman tak bertuan",
'lonelypagestext'	=> 'Halaman-halaman berikut tidak memiliki pranala dari halaman manapun di wiki ini.',
'uncategorizedpages' => 'Halaman yang tak terkategori',
'uncategorizedcategories' => 'Kategori yang tak terkategori',
'uncategorizedimages' => 'Berkas yang tak terkategori',
'unusedcategories' => 'Kategori yang tak digunakan',
"unusedimages" => "Berkas yang tak digunakan",
"popularpages" => "Halaman populer",
'wantedcategories' => 'Kategori yang diinginkan',
"wantedpages" => "Halaman yang diinginkan",
'mostlinked'	=> 'Halaman yang tersering dituju',
'mostlinkedcategories' => 'Kategori dengan halaman terbanyak',
'mostcategories' => 'Artikel dengan kategori terbanyak',
'mostimages'	=> 'Berkas yang tersering digunakan',
'mostrevisions' => 'Artikel dengan perubahan terbanyak',
"allpages" => "Semua halaman",
'nextpage' => 'Halaman selanjutnya ($1)',
'prefixindex'   => 'Indeks awalan',
"randompage" => "Halaman sembarang",
"shortpages" => "Halaman pendek",
"longpages" => "Halaman panjang",
"deadendpages" => "Halaman buntu",
'deadendpagestext'	=> 'Halaman-halaman berikut tidak memiliki pranala ke halaman manapun di wiki ini.',
"listusers" => "Daftar pengguna",
"specialpages" => "Halaman istimewa",
"spheading" => "Halaman istimewa untuk semua pengguna",
'restrictedpheading'	=> 'Halaman istimewa terbatas',
"recentchangeslinked" => "Perubahan terkait",
"rclsub" => "(untuk halaman yang berpaut dari \"$1\")",
"newpages" => "Halaman baru",
'newpages-username' => 'Nama pengguna:',
"ancientpages" => "Artikel tertua",
"intl" => "Pranala antarbahasa",
'move' => 'Pindahkan',
"movethispage" => "Pindahkan halaman ini",
"unusedimagestext" => "<p>Perhatikan bahwa situs web lain mungkin dapat berpaut ke sebuah berkas secara langsung, dan berkas-berkas seperti itu mungkin terdapat dalam daftar ini meskipun masih digunakan oleh situs web lain.",
'unusedcategoriestext' => 'Kategori berikut ada walaupun tidak ada artikel atau kategori lain yang menggunakannya.',

"booksources" => "Sumber buku",
'categoriespagetext' => 'Kategori-kategori berikut ada dalam wiki.',
'data'  => 'Data',
'userrights' => 'Manajemen hak pengguna',
'groups' => 'Grup pengguna',
"booksourcetext" => "Di bawah ini adalah daftar pranala ke situs lain yang menjual buku baru dan bekas, dan mungkin juga mempunyai informasi lebih lanjut mengenai buku yang sedang Anda cari. {{SITENAME}} tidak berkepentingan dengan situs-situs web di atas, dan daftar ini seharusnya tidak dianggap sebagai sebuah dukungan.",
"isbn" => "ISBN",


"alphaindexline" => "$1 ke $2",
"version" => "Versi",
'log' => 'Log',
'alllogstext' => 'Di bawah ini adalah gabungan log pemblokiran, perlindungan, perubahan hak akses, penghapusan, pemuatan, pemindahan, impor, dll. Anda dapat melakukan pembatasan tampilan dengan memilih jenis log, nama pengguna, atau nama halaman yang terpengaruh.',
'logempty' => 'Tidak ditemukan entri log yang sesuai.',


# Special:Allpages
'nextpage'          => 'Halaman berikutnya ($1)',
'allpagesfrom'		=> 'Tampilkan halaman dimulai dengan:',
'allarticles'       => 'Semua artikel',
'allinnamespace'	=> 'Semua halaman (ruang nama $1)',
'allnotinnamespace'	=> 'Semua halaman (bukan ruang nama $1)',
'allpagesprev'      => 'Sebelumnya',
'allpagesnext'      => 'Selanjutnya',
'allpagessubmit'    => 'Cari',
'allpagesprefix'	=> 'Tampilkan halaman dengan awalan:',
'allpagesbadtitle'	=> 'Judul halaman yang diberikan tidak sah atau memiliki awalan antar-bahasa atau antar-wiki. Judul tersebut mungkin juga mengandung satu atau lebih aksara yang tidak dapat digunakan dalam judul.',

# Special:Listusers
'listusersfrom' => 'Tampilkan pengguna diawali dengan:',

# Email this user
#
"mailnologin" => "Tidak ada alamat surat-e",
"mailnologintext" => "Anda harus [[{{ns:special}}:Userlogin|masuk log]] dan mempunyai alamat surat-e yang sah di dalam [[{{ns:special}}:Preferences|preferensi]] untuk mengirimkan surat-e kepada pengguna lain.",

"emailuser" => "Kirimi pengguna ini surat-e",
"emailpage" => "Kirimi pengguna ini surat-e",
"emailpagetext" => "Jika pengguna ini memasukkan alamat surat-e yang sah dalam preferensinya, formulir dibawah ini akan mengirimkan sebuah surat-e. Alamat surat-e yg terdapat pada preferensi Anda akan muncul sebagai alamat \"Dari\" dalam surat-e tersebut, sehingga penerima dapat membalas surat-e tersebut.",

"usermailererror" => "Kesalahan obyek surat:",
"defemailsubject" => "Surat-e {{SITENAME}}",
"noemailtitle" => "Tidak ada alamat surat-e",

"noemailtext" => "Pengguna ini tidak memasukkan alamat surat-e yang sah, atau memilih untuk tidak menerima surat-e dari pengguna yang lain.",

"emailfrom" => "Dari",
"emailto" => "Untuk",
"emailsubject" => "Perihal",
"emailmessage" => "Pesan",
"emailsend" => "Kirim",
"emailsent" => "Surat-e terkirim",
"emailsenttext" => "Surat-e Anda telah dikirimkan.",

# Watchlist
"watchlist" => "Daftar pantauan",
'watchlistfor' => "(untuk '''$1''')",
"nowatchlist" => "Daftar pantauan Anda kosong.",
'watchlistanontext' => 'Silakan $1 untuk melihat atau menyunting daftar pantauan Anda.',
'watchlistcount' 	=> "'''Anda memiliki $1 entri di daftar pantauan Anda, termasuk halaman diskusi/bicara.'''",
'clearwatchlist' 	=> 'Kosongkan daftar pantauan',
'watchlistcleartext' => 'Apakah Anda yakin untuk menghapusnya?',
'watchlistclearbutton' => 'Kosongkan daftar pantauan',
'watchlistcleardone' => 'Daftar pantauan Anda telah dikosongkan. $1 entri telah dihapus.',
"watchnologin" => "Belum masuk log",
"watchnologintext" => "Anda harus [[{{ns:special}}:Userlogin|masuk log]] untuk mengubah daftar pantauan.",
"addedwatch" => "Telah ditambahkan ke daftar pantauan",
"addedwatchtext" => "Halaman \"[[:$1]]\" telah ditambahkan ke [[{{ns:special}}:Watchlist|daftar pantauan]]. Perubahan yang terjadi di masa yang akan datang pada halaman tersebut dan halaman bicara terkaitnya akan tercantum di sini, dan halaman itu akan ditampilkan ''tebal'' pada [[{{ns:special}}:Recentchanges|daftar perubahan terbaru]] agar lebih mudah terlihat.<br /><br />Jika Anda ingin menghapus halaman ini dari daftar pantauan, klik \"Berhenti memantau\" pada menu.",
"removedwatch" => "Telah dihapus dari daftar pantauan",
"removedwatchtext" => "Halaman \"$1\" telah dihapus dari daftar pantauan.",
'watch' => 'Pantau',
"watchthispage" => "Pantau halaman ini",
'unwatch' => 'Batal pantau',
"unwatchthispage" => "Batal pantau halaman ini",
"notanarticle" => "Bukan sebuah artikel",
"watchnochange" => "Tak ada halaman pantauan Anda yang telah berubah dalam jangka waktu yang dipilih.",
'watchdetails'		=> '* $1 halaman dipantau, tidak termasuk halaman bicara
* [[{{ns:special}}:Watchlist/edit|Lihat dan sunting daftar pantauan]]
* [[{{ns:special}}:Watchlist/clear|Hapus semua halaman dari daftar]]',
'wlheader-enotif' 		=> "* Notifikasi surat-e diaktifkan.",
'wlheader-showupdated'   => "* Halaman-halaman yang telah berubah sejak kunjungan terakhir Anda ditampilkan dengan '''huruf tebal'''",
"watchmethod-recent"=> "periksa daftar perubahan terbaru terhadap halaman yang dipantau",
"watchmethod-list" => "periksa halaman yang dipantau terhadap perubahan terbaru",
"removechecked" => "Keluarkan halaman yang ditandai dari daftar pantauan",
"watchlistcontains" => "Daftar pantauan Anda berisi $1 halaman.",
"watcheditlist" => "Berikut ini adalah daftar halaman-halaman yang Anda pantau. Untuk menghapus halaman dari daftar pantauan Anda, berikan tanda cek pada kotak cek di sebelah judul halaman yang ingin Anda hapus, lalu klik tombol 'Keluarkan halaman yang ditandai dari daftar pantauan' yang terletak di bagian bawah layar.",
"removingchecked" => "Menghapus halaman yang diminta dari daftar pantauan Anda...",
"couldntremove" => "Tidak dapat menghapus halaman '$1' dari daftar pantauan...",
"iteminvalidname" => "Ada masalah dengan '$1', namanya tidak sah...",
"wlnote" => "Di bawah ini adalah daftar $1 perubahan terakhir dalam <strong>$2</strong> jam terakhir.",
"wlshowlast" => "Tampilkan $1 jam $2 hari $3 terakhir",
"wlsaved" => "Ini adalah versi tersimpan dari daftar pantauan Anda.",
'wlhideshowown'   	=> '$1 suntingan saya',
'wlhideshowbots'   	=> '$1 suntingan bot',
'wldone'			=> 'Selesai.',

'enotif_mailer' 		=> 'Pengirim Notifikasi {{SITENAME}}',
'enotif_reset'			=> 'Tandai semua halaman sebagai telah dikunjungi',
'enotif_newpagetext'=> 'Ini adalah halaman baru.',
'changed'			=> 'diubah',
'created'			=> 'dibuat',
'enotif_subject' 	=> 'Halaman $PAGETITLE di {{SITENAME}} telah $CHANGEDORCREATED oleh $PAGEEDITOR',
'enotif_lastvisited' => 'Lihat $1 untuk semua perubahan sejak kunjungan terakhir Anda.',
'enotif_body' => 'Dear $WATCHINGUSERNAME,

Halaman $PAGETITLE di {{SITENAME}} telah $CHANGEDORCREATED pada $PAGEEDITDATE oleh $PAGEEDITOR, lihat $PAGETITLE_URL untuk versi terakhir.

$NEWPAGE

Riwayat suntingan: $PAGESUMMARY $PAGEMINOREDIT

Hubungi penyunting:
mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Kami tidak akan mengirimkan pemberitahuan lain jika terjadi perubahan lagi, kecuali Anda jika Anda telah mengunjungi halaman tersebut. Anda juga dapat menghapus tanda notifikasi untuk semua halaman pantauan Anda pada daftar pantauan Anda.

             Sistem notifikasi {{SITENAME}}

--
Untuk mengubah preferensi daftar pantauan Anda, kunjungi
{{fullurl:{{ns:special}}:Watchlist/edit}}

Masukan dan bantuan lanjutan:
{{fullurl:{{ns:help}}:Isi}}',


# Delete/protect/revert
#
"deletepage" => "Hapus halaman",
"confirm" => "Konfirmasikan",
"excontent" => "isi sebelumnya: '$1'",
'excontentauthor' => "isinya hanya berupa: '$1' (dan satu-satunya penyumbang adalah '[[Special:Contributions/$2|$2]]')",
"exbeforeblank" => "isi sebelum dikosongkan: '$1'",
"exblank" => "halaman kosong",
"confirmdelete" => "Konfirmasi penghapusan",
"deletesub" => "(Menghapus \"$1\")",
"historywarning" => "Peringatan: Halaman yang ingin Anda hapus mempunyai sejarah:",
"confirmdeletetext" => "Anda akan menghapus halaman atau berkas ini secara permanen berikut semua sejarahnya dari basis data.  Pastikan bahwa Anda memang ingin berbuat demikian, mengetahui segala akibatnya, dan apa yang Anda lakukan ini adalah sejalan dengan [[{{ns:project}}:Kebijakan|kebijakan {{SITENAME}}]].",
"actioncomplete" => "Proses selesai",
"deletedtext" => "\"$1\" telah dihapus. Lihat $2 untuk log terkini halaman yang telah dihapus.",
"deletedarticle" => "menghapus \"[[$1]]\"",
"dellogpage" => "Log penghapusan",
"dellogpagetext" => "Di bawah ini adalah log penghapusan halaman. Semua waktu yang ditunjukkan adalah waktu server (UTC).",
"deletionlog" => "log penghapusan",
"reverted" => "Dikembalikan ke revisi sebelumnya",
"deletecomment" => "Alasan penghapusan",
"imagereverted" => "Berhasil mengembalikan ke revisi sebelumnya",
"rollback" => "Kembalikan suntingan",
'rollback_short' => 'Kembalikan',
"rollbacklink" => "kembalikan",
"rollbackfailed" => "Pengembalian gagal dilakukan",
"cantrollback" => "Tidak dapat mengembalikan suntingan; pengguna terakhir adalah satu-satunya penulis artikel ini.",
"alreadyrolled" => "Tidak dapat melakukan pengembalian ke suntingan terakhir [[$1]] oleh [[{{ns:user}}:$2|$2]] ([[{{ns:user_talk}}:$2|Bicara]]); orang lain telah menyunting atau melakukan pengembalian terhadap artikel tersebut. Suntingan terakhir oleh [[{{ns:user}}:$3|$3]] ([[{{ns:user_talk}}:$3|Bicara]]).",
"editcomment" => "Komentar penyuntingan adalah: \"<em>$1</em>\".",
'revertpage'	=> "Suntingan [[{{ns:special}}:Contributions/$2|$2]] ([[{{ns:user_talk}}:$2|Bicara]]) dikembalikan ke versi terakhir oleh [[{{ns:user}}:$1|$1]]",
'sessionfailure' => 'Sepertinya ada masalah dengan sesi log anda; log anda telah dibatalkan untuk mencegah pembajakan. Silahkan tekan tombol "back" dan muat kembali halaman sebelum anda masuk, lalu coba lagi.',
"protectlogpage" => "Log perlindungan",
"protectlogtext" => "Di bawah ini adalah daftar log perlindungan dan penghilangan perlindungan halaman.",
"protectedarticle" => "melindungi [[$1]]",
"unprotectedarticle" => "menghilangkan perlindungan [[$1]]",
"protectsub" =>"(Melindungi \"$1\")",
"confirmprotecttext" => "Apakah Anda benar-benar ingin melindungi halaman ini?",
"confirmprotect" => "Konfirmasi perlindungan",
'protectmoveonly' => 'Lindungi dari pemindahan saja',
"protectcomment" => "Alasan perlindungan",
"unprotectsub" =>"(Menghilangkan perlindungan terhadap \"$1\")",
"confirmunprotecttext" => "Apakah Anda benar-benar ingin menghilangkan perlindungan terhadap halaman ini?",
"confirmunprotect" => "Konfirmasi penghilangan perlindungan",
"unprotectcomment" => "Alasan penghilangan perlindungan",
'protect-unchain' => 'Buka proteksi pemindahan',
'protect-text' => 'Anda dapat melihat atau mengganti tingkatan perlindungan untuk halaman <strong>$1</strong> di sini.',
'protect-viewtext' => 'Akun Anda tidak memiliki akses untuk mengganti tingkat perlindungan halaman. Berikut adalah konfigurasi saat ini untuk halaman <strong>$1</strong>:',
'protect-default' => '(baku)',
'protect-level-autoconfirmed' => 'Hanya pengguna terdaftar',
'protect-level-sysop' => 'Hanya pengurus',

# restrictions (nouns)
'restriction-edit' => 'Penyuntingan',
'restriction-move' => 'Pemindahan',

# Undelete
"undelete" => "Kembalikan halaman yang telah dihapus",
"undeletepage" => "Lihat dan kembalikan halaman yang telah dihapus",
'viewdeletedpage' => 'Lihat halaman yang telah dihapus',
"undeletepagetext" => "Halaman-halaman berikut ini telah dihapus tapi masih ada di dalam arsip dan dapat dikembalikan. Arsip tersebut mungkin akan dibersihkan secara berkala.",
'undeleteextrahelp' => "Untuk mengembalikan keseruhan halaman, biarkan seluruh ''check box'' tidak terpilih dan klik '''''Restore'''''. Untuk melakukan pengembalian seletif, cek kotak revisi yang diinginkan dan klik '''''Restore'''''. Menekan tombol '''''Reset''''' akan mengosongkan isian komentar dan semua ''cek box''",
"undeletearticle" => "Kembalikan halaman yang telah dihapus",
"undeleterevisions" => "$1 revisi diarsipkan",
"undeletehistory" => "Jika Anda mengembalikan halaman tersebut, semua revisi akan dikembalikan ke dalam sejarah. Jika sebuah halaman baru dengan nama yang sama telah dibuat sejak penghapusan, revisi yang telah dikembalikan akan kelihatan dalam sejarah dahulu, dan revisi terkini halaman tersebut tidak akan ditimpa secara otomatis.",
'undeletehistorynoadmin' => 'Artikel ini telah dihapus. Alasan penghapusan diberikan pada ringkasan di bawah ini, berikut detil pengguna yang telah melakukan penyuntingan pada halaman ini sebelum dihapus. Isi terakhir dari revisi yang telah dihapus ini hanya tersedia untuk pengurus.',
"undeleterevision" => "Revisi yang telah dihapus per $1",
'undeletebtn' => "Kembalikan!",
'undeletereset' => 'Reset',
'undeletecomment' => 'Komentar:',
"undeletedarticle" => "\"$1\" telah dikembalikan",
'undeletedrevisions' => "$1 revisi telah dikembalikan",
'undeletedrevisions-files' => "$1 revisi and $2 berkas dikembalikan",
'undeletedfiles' => "$1 berkas dikembalikan",
'cannotundelete' => 'Pembatalan penghapusan gagal; mungkin ada orang lain yang telah terlebih dahulu melakukan pembatalan.',
'undeletedpage' => "<big>'''$1 berhasil dikembalikan'''</big>

Lihat [[{{ns:special}}:Log/delete|log penghapusan]] untuk data penghapusan dan pengembalian.",

# Namespace form on various pages
'namespace' => 'Ruang nama:',
'invert' => 'Balikkan pilihan',

# Contributions
#
"contributions" => "Sumbangan pengguna",
"mycontris" => "Sumbangan saya",
"contribsub" => "Untuk $1",
"nocontribs" => "Tidak ada perubahan yang cocok dengan kriteria-kriteria ini.",
"ucnote" => "Di bawah ini adalah <strong>$1</strong> perubahan terakhir pengguna dalam <strong>$2</strong> hari terakhir.",
"uclinks" => "Tampilkan $1 perubahan terbaru; tampilkan $2 hari terakhir",
"uctop" => " (atas)" ,
'newbies' => 'pengguna baru',

'sp-newimages-showfrom' => 'Tampilkan berkas baru dimulai dari $1',

'sp-contributions-newest' => 'Terbaru',
'sp-contributions-oldest' => 'Terlama',
'sp-contributions-newer'  => 'Lebih baru $1',
'sp-contributions-older'  => 'Lebih lama $1',
'sp-contributions-newbies-sub' => 'Untuk pengguna baru',

# What links here
#
"whatlinkshere" => "Pranala ke halaman ini",
"notargettitle" => "Tidak ada sasaran",
"notargettext" => "Anda tidak menentukan halaman atau pengguna tujuan fungsi ini.",
"linklistsub" => "(Daftar pranala)",
"linkshere" => "Halaman-halaman berikut ini berpaut ke '''[[:$1]]''':",
"nolinkshere" => "Tidak ada halaman yang berpaut ke '''[[:$1]]'''.",
"isredirect" => "halaman peralihan",
'istemplate'	=> 'dengan templat',

# Block/unblock IP
#
"blockip" => "Blokir IP",
"blockiptext" => "Gunakan formulir di bawah untuk memblokir kemampuan menulis sebuah alamat IP atau pengguna tertentu. Ini perlu dilakukan untuk mencegah vandalisme, dan sejalan dengan [[{{ns:project}}:Kebijakan|kebijakan {{SITENAME}}]]. Masukkan alasan Anda di bawah (contohnya mengambil halaman tertentu yang telah dirusak).",
"ipaddress" => "Alamat IP",
'ipadressorusername' => 'Alamat IP atau nama pengguna',
"ipbexpiry" => "Kadaluwarsa",
"ipbreason" => "Alasan",
'ipbanononly'   => 'Hanya blokir pengguna anonim',
'ipbcreateaccount' => 'Cegah pembuatan akun',
"ipbsubmit" => "Kirimkan",
'ipbother'		=> 'Waktu lain',
'ipboptions'		=> '2 jam:2 hours,1 hari:1 day,3 hari:3 days,1 minggu:1 week,2 minggu:2 weeks,1 bulan:1 month,3 bulan:3 months,6 bulan:6 months,1 tahun:1 year,selamanya:infinite',
'ipbotheroption'	=> 'lainnya',
"badipaddress" => "Format alamat IP atau nama pengguna salah.",
"blockipsuccesssub" => "Pemblokiran sukses",
"blockipsuccesstext" => "Alamat IP atau pengguna \"$1\" telah diblokir. <br />Lihat [[{{ns:special}}:Ipblocklist|Daftar IP dan pengguna diblokir]] untuk melihat kembali pemblokiran.",
"unblockip" => "Hilangkan blokir terhadap alamat IP atau pengguna",
"unblockiptext" => "Gunakan formulir di bawah untuk mengembalikan kemampuan menulis sebuah alamat IP atau pengguna yang sebelumnya telah diblokir.",
"ipusubmit" => "Hilangkan blokir terhadap alamat ini",
'unblocked' => 'Blokir terhadap [[User:$1|$1]] telah dihilangkan',
"ipblocklist" => "Daftar alamat IP dan pengguna yang diblokir",
"blocklistline" => "$1, $2 memblokir $3 ($4)",
'infiniteblock' => 'tak terbatas',
'expiringblock' => 'kadaluwarsa $1',
'anononlyblock' => 'hanya anon',
'createaccountblock' => 'pembuatan akun diblokir',
'ipblocklistempty'	=> 'Daftar pemblokiran kosong.',
"blocklink" => "blokir",
"unblocklink" => "hilangkan blokir",
"contribslink" => "sumbangan",
"autoblocker" => "Diblokir secara otomatis karena Anda berbagi alamat IP dengan \"$1\". Alasan \"$2\".",
"blocklogpage" => "Log pemblokiran",
"blocklogentry" => 'memblokir "[[$1]]" dengan waktu kadaluwarsa $2',
"blocklogtext" => "Di bawah ini adalah log pemblokiran dan penghilangan blokir terhadap pengguna. Alamat IP yang diblokir secara otomatis tidak terdapat di dalam daftar ini. Lihat [[{{ns:special}}:Ipblocklist|daftar alamat IP yang diblokir]] untuk daftar blokir terkini yang efektif.",
"unblocklogentry" => 'menghilangkan blokir "$1"',
"range_block_disabled" => "Kemampuan pengurus dalam membuat blokir blok IP dimatikan.",
"ipb_expiry_invalid" => "Waktu kadaluwarsa tidak sah.",
'ipb_already_blocked' => '"$1" telah diblokir',
"ip_range_invalid" => "Blok IP tidak sah.",
"proxyblocker" => "Pemblokir proxy",
'ipb_cant_unblock' => 'Kesalahan: Blokir dengan ID $1 tidak ditemukan. Blokir tersebut kemungkinan telah dibuka.',
"proxyblockreason" => "Alamat IP Anda telah diblokir karena alamat IP Anda adalah proxy terbuka. Silakan hubungi penyedia jasa internet Anda atau dukungan teknis dan beritahukan mereka masalah keamanan serius ini.",
"proxyblocksuccess" => "Selesai.",
'sorbs' => 'SORBS DNSBL',
'sorbsreason' => 'Alamat IP anda terdaftar sebagai proxy terbuka di [http://www.sorbs.net SORBS] DNSBL.',
'sorbs_create_account_reason' => 'Alamat IP anda terdaftar sebagai proxy terbuka di [http://www.sorbs.net SORBS] DNSBL. Anda tidak dapat membuat akun.',

# Make sysop
"makesysoptitle" => "Buat seorang pengguna menjadi pengurus",
"makesysoptext" => "Formulir ini digunakan oleh para birokrat untuk menjadikan pengguna biasa menjadi seorang pengurus. Ketikkan nama pengguna yang dimaksud dalam kotak dan tekan tombol untuk menjadikan pengguna tersebut seorang pengurus",
"makesysopname" => "Nama pengguna:",
"makesysopsubmit" => "Jadikan pengurus",
"makesysopok" => "<strong>Pengguna \"$1\" sekarang adalah seorang pengurus</strong>",
"makesysopfail" => "<strong>Pengguna \"$1\" tidak dapat dijadikan pengurus. (Apakah Anda mengetikkan namanya dengan benar?)</strong>",
"setbureaucratflag" => "Beri tanda birokrat",
'rightslog'		=> 'Log perubahan hak akses',
'rightslogtext' => 'Di bawah ini adalah log perubahan terhadap hak-hak pengguna.',
'rightslogentry'	=> 'mengganti keanggotaan group untuk $1 dari $2 menjadi $3',
"rights" => "Hak-hak:",
"set_user_rights" => "Atur hak-hak pengguna",
"user_rights_set" => "<strong>Hak-hak pengguna \"$1\" diperbarui</strong>",
"set_rights_fail" => "<strong>Hak-hak pengguna \"$1\" tidak dapat diatur. (Apakah Anda mengetikkan namanya dengan benar?)</strong>",
"makesysop" => "Buat seorang pengguna menjadi pengurus",
'already_sysop'     => 'Pengguna ini sudah menjadi pengurus',
'already_bureaucrat' => 'Pengguna ini sudah menjadi birokrat',
'rightsnone' 		=> '(tidak ada)',

# Developer tools
#
"lockdb" => "Kunci basis data",
"unlockdb" => "Buka kunci basis data",
"lockdbtext" => "Mengunci basis data akan menghentikan kemampuan semua pengguna dalam menyunting halaman, mengubah preferensi pengguna, menyunting daftar pantauan mereka, dan hal-hal lain yang memerlukan perubahan terhadap basis data. Pastikan bahwa ini adalah yang ingin Anda lakukan, dan bahwa Anda akan membuka kunci basis data setelah pemeliharaan selesai.",
"unlockdbtext" => "Membuka kunci basis data akan mengembalikan kemampuan semua pengguna dalam menyunting halaman, mengubah preferensi pengguna, menyunting daftar pantauan mereka, dan hal-hal lain yang memerlukan perubahan terhadap basis data.  Pastikan bahwa ini adalah yang ingin Anda lakukan.",
"lockconfirm" => "Ya, saya memang ingin mengunci basis data.",
"unlockconfirm" => "Ya, saya memang ingin membuka kunci basis data.",
"lockbtn" => "Kunci basis data",
"unlockbtn" => "Buka kunci basis data",
"locknoconfirm" => "Anda tidak memberikan tanda cek pada kotak konfirmasi.",
"lockdbsuccesssub" => "Penguncian basis data berhasil",
"unlockdbsuccesssub" => "Pembukaan kunci basis data berhasil",
'lockdbsuccesstext' => 'Basis data telah dikunci.
<br />Pastikan Anda [[Special:Unlockdb|membuka kuncinya]] setelah pemeliharaan selesai.',
"unlockdbsuccesstext" => "Kunci basis data telah dibuka.",
'lockfilenotwritable' => 'Berkas kunci basis data tidak dapat ditulis. Untuk mengunci atau membuka basis data, berkas ini harus dapat ditulis oleh server web.',
'databasenotlocked' => 'Basis data tidak terkunci.',

# Move page
#
"movepage" => "Pindahkan halaman",
"movepagetext" => "Formulir di bawah ini digunakan untuk mengubah nama suatu halaman dan memindahkan semua data sejarah ke nama baru. Judul yang lama akan menjadi halaman peralihan menuju judul yang baru. Pranala kepada judul lama tidak akan berubah. Pastikan untuk memeriksa terhadap peralihan halaman yang rusak atau berganda setelah pemindahan. Anda bertanggung jawab untuk memastikan bahwa pranala terus menyambung ke halaman yang seharusnya.

Perhatikan bahwa halaman '''tidak''' akan dipindah apabila telah ada halaman di pada judul yang baru, kecuali bila halaman tersebut kosong atau merupakan halaman peralihan dan tidak mempunyai sejarah penyuntingan. Ini berarti Anda dapat mengubah nama halaman kembali seperti semula apabila Anda membuat kesalahan, dan Anda tidak dapat menimpa halaman yang telah ada.

<strong>PERINGATAN!</strong> Ini dapat mengakibatkan perubahan yang tak terduga dan drastis  bagi halaman yang populer. Pastikan Anda mengerti konsekuensi dari perbuatan ini sebelum melanjutkan.",
"movepagetalktext" => "Halaman pembicaraan yang berkaitan juga akan dipindahkan secara otomatis '''kecuali apabila:'''

*Sebuah halaman pembicaraan yang tidak kosong telah ada di bawah judul baru, atau
*Anda tidak memberi tanda cek pada kotak di bawah ini

Dalam kasus tersebut, apabila diinginkan, Anda dapat memindahkan atau menggabungkan halaman secara manual.",
"movearticle" => "Pindahkan halaman",
"movenologin" => "Belum masuk log",
"movenologintext" => "Anda harus menjadi pengguna terdaftar dan telah [[{{ns:special}}:Userlogin|masuk log]] untuk memindahkan halaman.",
"newtitle" => "Ke judul baru",
"movepagebtn" => "Pindahkan halaman",
"pagemovedsub" => "Pemindahan berhasil",
"pagemovedtext" => "<div class=\"plainlinks\">Halaman \"[{{fullurl:<includeonly></includeonly>$1|redirect=no}} $1]\" dipindahkan ke \"[[$2]]\". Jangan lupa untuk memperbaiki [[{{ns:special}}:Whatlinkshere/$1|pengalihan ganda]] yang mungkin terjadi.</div>",
"articleexists" => "Halaman dengan nama tersebut telah ada atau nama yang dipilih tidak sah. Silakan pilih nama lain.",
"talkexists" => "Halaman tersebut berhasil dipindahkan, tetapi halaman pembicaraan dari halaman tersebut tidak dapat dipindahkan karena telah ada halaman pembicaraan pada judul yang baru. Silakan gabungkan halaman-halaman pembicaraan tersebut secara manual.",
"movedto" => "dipindahkan ke",
"movetalk" => "Pindahkan halaman \"pembicaraan\" juga, jika mungkin.",
"talkpagemoved" => "Halaman pembicaraan yang berkaitan juga ikut dipindahkan.",
"talkpagenotmoved" => "Halaman pembicaraan yang berkaitan <strong>tidak</strong> ikut dipindahkan.",
'1movedto2' => '[[$1]] dipindahkan ke [[$2]]',
'1movedto2_redir' => '[[$1]] dipindahkan ke [[$2]] melalui peralihan',
'movelogpage' => 'Log pemindahan',
'movelogpagetext' => 'Di bawah ini adalah log pemindahan halaman.',
'movereason'	=> 'Alasan',
'revertmove'	=> 'kembalikan',
'delete_and_move' => 'Hapus dan pindahkan',
'delete_and_move_text'	=>
'==Penghapusan diperlukan==

Artikel yang dituju, "[[$1]]", telah mempunyai isi. Apakah Anda hendak menghapusnya untuk memberikan ruang bagi pemindahan?',
'delete_and_move_confirm' => 'Ya, hapus halaman tersebut',
'delete_and_move_reason' => 'Dihapus untuk mengantisipasikan pemindahan halaman',
'selfmove' => "Pemindahan halaman tidak dapat dilakukan karena judul sumber dan judul tujuan sama.",
'immobile_namespace' => "Judul sumber atau tujuan termasuk tipe khusus; tidak dapat memindahkan halaman ke ruang nama tersebut.",

# Export

"export" => "Ekspor halaman",
'exporttext'    => 'Anda dapat mengekspor teks dan sejarah penyuntingan suatu halaman tertentu atau suatu set halaman dalam bentuk XML tertentu. Hasil ekspor ini selanjutnya dapat diimpor ke wiki lainnya yang menggunakan perangkat lunak MediaWiki, dengan menggunakan fasilitas [[{{ns:special}}:Import]].

Untuk mengekspor halaman-halaman artikel, masukkan judul-judul dalam kotak teks di bawah ini, satu judul per baris, dan pilih apakah anda ingin mengekspor lengkap dengan versi terdahulunya, atau hanya versi sekarang dengan catatan penyuntingan terakhir.

Jika Anda hanya ingin mengimpor versi sekarang, Anda juga dapat melakukan hal ini dengan lebih cepat dengan cara menggunakan pranala khusus, sebagai contoh: [[{{ns:special}}:Export/{{int:mainpage}}]] untuk mengekspor artikel {{int:mainpage}}.',
"exportcuronly" => "Hanya ekspor revisi sekarang, bukan seluruh versi terdahulu",
'exportnohistory' => "----
'''Catatan:''' Mengekspor keseluruhan riwayat suntingan halaman melalui isian ini telah dinon-aktifkan karena alasan kinerja.",
'export-submit' => 'Ekspor',

# Namespace 8 related

"allmessages" => "Pesan sistem",
'allmessagesname' => 'Nama',
'allmessagesdefault' => 'Teks baku',
'allmessagescurrent' => 'Teks sekarang',
'allmessagestext' => 'Ini adalah daftar semua pesan sistem yang tersedia dalam ruang nama MediaWiki:',
'allmessagesnotsupportedUI' => 'Bahasa antarmuka Anda saat ini, <strong>$1</strong> tidak didukung oleh {{ns:special}}:AllMessages di situs ini.',
'allmessagesnotsupportedDB' => '\'\'\'{{ns:special}}:Allmessages\'\'\' tidak didukung karena wgUseDatabaseMessages dimatikan.',
'allmessagesfilter' => 'Filter nama pesan:',
'allmessagesmodified' => 'Hanya tampilkan yang diubah',

# Thumbnails

"thumbnail-more" => "Perbesar",
"missingimage" => "<strong>Berkas tak ditemukan</strong><br /><em>$1</em>",
'filemissing' => 'Berkas tak ditemukan',
'thumbnail_error'   => 'Kesalahan sewaktu pembuatan gambar kecil (thumbnail): $1',

# Special:Import
"import" => "Impor halaman",
'importinterwiki' => 'Impor transwiki',
'import-interwiki-text' => 'Pilih suatu wiki dan judul halaman yang akan di impor. Tanggal revisi dan nama penyunting akan dipertahankan. Semua aktivitas impor transwiki akan dilog di [[{{ns:special}}:Log/import|log impor]].',
'import-interwiki-history' => 'Salin semua versi terdahulu dari halaman ini',
'import-interwiki-submit' => 'Impor',
'import-interwiki-namespace' => 'Transfer halaman ke dalam ruang nama:',
"importtext" => "Silakan ekspor berkas dari wiki asal dengan menggunakan utilitas [[{{ns:special}}:Export]], simpan ke cakram digital, dan muatkan ke sini.",
'importstart'	=> "Mengimpor halaman...",
'import-revision-count' => '$1 versi terdahulu',
'importnopages'	=> "Tidak ada halaman untuk diimpor.",
"importfailed" => "Impor gagal: $1",
'importunknownsource'	=> "Sumber impor tidak dikenali",
'importcantopen'	=> "Berkas impor tidak dapat dibuka",
'importbadinterwiki'	=> "Pranala interwiki rusak",
"importnotext" => "Kosong atau tidak ada teks",
"importsuccess" => "Impor sukses!",
"importhistoryconflict" => "Terjadi konflik revisi sejarah (mungkin pernah mengimpor halaman ini sebelumnya)",
'importnosources' => 'Tidak ada sumber impor transwiki yang telah dibuat dan pemuatan riwayat secara langsung telah di non-aktifkan.',
'importnofile' => 'Tidak ada berkas sumber impor yang telah dimuat.',
'importuploaderror' => 'Pemuatan berkas impor gagal; mungkin ukuran berkas lebih besar dari pada yang diizinkan.',

# import log
'importlogpage' => 'Log impor',
'importlogpagetext' => 'Di bawah ini adalah log import administratif dari halaman-halaman berikut riwayat suntingannya dari wiki lain.',
'import-logentry-upload' => 'mengimpor [[$1]] melalui pemuatan berkas',
'import-logentry-upload-detail' => '$1 versi terdahulu',
'import-logentry-interwiki' => 'men-transwiki $1',
'import-logentry-interwiki-detail' => '$1 versi terdahulu dari $2',

# Keyboard access keys for power users
'accesskey-search' => 'f',
'accesskey-minoredit' => 'i',
'accesskey-save' => 's',
'accesskey-preview' => 'p',
'accesskey-diff' => 'v',
'accesskey-compareselectedversions' => 'v',
'accesskey-watch' => 'w',

# tooltip help for the main actions
'tooltip-search' => 'Cari dalam wiki ini [alt-f]',
'tooltip-minoredit' => 'Tandai ini sebagai suntingan kecil [alt-i]',
'tooltip-save' => 'Simpan perubahan Anda [alt-s]',
'tooltip-preview' => 'Pratayang perubahan Anda -- mohon gunakan ini sebelum menyimpan! [alt-p]',
'tooltip-diff' => 'Lihat perubahan yang telah Anda lakukan. [alt-v]',
'tooltip-compareselectedversions' => 'Lihat perbedaan antara dua versi halaman yang dipilih. [alt-v]',
'tooltip-watch' => 'Tambahkan halaman ini ke daftar pantauan Anda [alt-w]',

# stylesheets

'Common.css' => '/** CSS yang berada di sini akan diterapkan untuk semua kulit */',
'Monobook.css' => '/* CSS yang berada di sini akan mempengaruhi semua pengguna yang menggunakan kulit Monobook */',

# Metadata
"nodublincore" => "Metadata Dublin Core RDF dimatikan di server ini.",
"nocreativecommons" => "Metadata Creative Commons RDF dimatikan di server ini.",
"notacceptable" => "Server wiki tidak dapat menyediakan data dalam format yang dapat dibaca oleh client Anda.",

# Attribution

"anonymous" => "Pengguna(-pengguna) anonim {{SITENAME}}",
"siteuser" => "Pengguna {{SITENAME}} $1",
"lastmodifiedatby" => "Halaman ini terakhir kali diubah $2, $1 oleh $3.",
"and" => "dan",
"othercontribs" => "Didasarkan pada karya $1.",
'others' => 'lainnya',
"siteusers" => "Pengguna(-pengguna) {{SITENAME}} $1",
'creditspage' => 'Penghargaan halaman',
'nocredits' => 'Tidak ada informasi penghargaan yang tersedia untuk halaman ini.',

# Spam protection

'spamprotectiontitle' => 'Filter pencegah spam',
'spamprotectiontext' => 'Halaman yang ingin Anda simpan diblokir oleh filter spam. Ini mungkin disebabkan oleh pranala ke situs luar.',
'spamprotectionmatch' => 'Teks berikut ini memancing filter spam kami: $1',
'subcategorycount' => "Ada $1 subkategori dalam kategori ini.",  # no need for plural
'categoryarticlecount' => "Ada $1 artikel dalam kategori ini.",  # no need for plural
'listingcontinuesabbrev' => " samb.",
'spambot_username' => 'Pembersihan span MediaWiki',
'spam_reverting' => 'Mengembalikan ke versi terakhir yang tak memiliki pranala ke $1',
'spam_blanking' => 'Semua revisi yang memiliki pranala ke $1, pengosongan',

# Info page
"infosubtitle" => "Informasi halaman",
"numedits" => "Jumlah penyuntingan (artikel): $1",
"numtalkedits" => "Jumlah penyuntingan (halaman diskusi): $1",
"numwatchers" => "Jumlah pengamat: $1",
"numauthors" => "Jumlah pengarang yang berbeda (artikel): $1",
"numtalkauthors" => "Jumlah pengarang yang berbeda (halaman diskusi): $1",

# Math options
'mw_math_png' => "Selalu buat PNG",
'mw_math_simple' => "HTML jika sangat sederhana atau PNG",
'mw_math_html' => "HTML jika mungkin atau PNG",
'mw_math_source' => "Biarkan sebagai TeX (untuk penjelajah web teks)",
'mw_math_modern' => "Disarankan untuk penjelajah web modern",
'mw_math_mathml' => "MathML jika mungkin (percobaan)",

# Patrolling
'markaspatrolleddiff'   => "Tandai telah dipatroli",
'markaspatrolledtext'   => "Tandai artikel ini telah dipatroli",
'markedaspatrolled'     => "Ditandai telah dipatroli",
'markedaspatrolledtext' => "Revisi yang dipilih telah ditandai terpatroli",
'rcpatroldisabled'      => "Patroli perubahan terbaru dimatikan",
'rcpatroldisabledtext'  => "Fitur patroli perubahan terbaru sedang dimatikan.",
'markedaspatrollederror'  => "Tidak dapat menandai telah dipatroli",
'markedaspatrollederrortext' => "Anda harus menentukan satu revisi untuk ditandai sebagai yang dipatroli.",

# Monobook.js: tooltips and access keys for monobook
'Monobook.js' => '/* bantuan peralatan dan kunci akses */
var ta = new Object();
ta[\'pt-userpage\'] = new Array(\'.\',\'Halaman pengguna saya\');
ta[\'pt-anonuserpage\'] = new Array(\'.\',\'Halaman pengguna IP Anda\');
ta[\'pt-mytalk\'] = new Array(\'n\',\'Halaman pembicaraan saya\');
ta[\'pt-anontalk\'] = new Array(\'n\',\'Diskusi tentang suntingan dari alamat IP ini\');
ta[\'pt-preferences\'] = new Array(\'\',\'Preferensi saya\');
ta[\'pt-watchlist\'] = new Array(\'l\',\'Daftar halaman yang Anda pantau.\');
ta[\'pt-mycontris\'] = new Array(\'y\',\'Daftar sumbangan saya\');
ta[\'pt-login\'] = new Array(\'o\',\'Anda disarankan untuk masuk log, meskipun hal itu tidak diwajibkan.\');
ta[\'pt-anonlogin\'] = new Array(\'o\',\'Anda disarankan untuk masuk log, meskipun hal itu tidak diwajibkan.\');
ta[\'pt-logout\'] = new Array(\'o\',\'Keluar log\');
ta[\'ca-talk\'] = new Array(\'t\',\'Diskusi tentang artikel\');
ta[\'ca-edit\'] = new Array(\'e\',\'Anda dapat menyunting halaman ini. Silakan gunakan tombol pratayang sebelum menyimpan.\');
ta[\'ca-addsection\'] = new Array(\'+\',\'Tambahkan komentar ke diskusi ini.\');
ta[\'ca-viewsource\'] = new Array(\'e\',\'Halaman ini dilindungi. Anda hanya dapat melihat sumbernya.\');
ta[\'ca-history\'] = new Array(\'h\',\'Versi-versi sebelumnya dari halaman ini.\');
ta[\'ca-protect\'] = new Array(\'=\',\'Lindungi halaman ini\');
ta[\'ca-delete\'] = new Array(\'d\',\'Hapus halaman ini\');
ta[\'ca-undelete\'] = new Array(\'d\',\'Kembalikan suntingan ke halaman ini sebelum halaman ini dihapus\');
ta[\'ca-move\'] = new Array(\'m\',\'Pindahkan halaman ini\');
ta[\'ca-watch\'] = new Array(\'w\',\'Tambahkan halaman ini ke daftar pantauan Anda\');
ta[\'ca-unwatch\'] = new Array(\'w\',\'Hapus halaman ini dari daftar pantauan Anda\');
ta[\'search\'] = new Array(\'f\',\'Cari dalam wiki ini\');
ta[\'p-logo\'] = new Array(\'\',\'Halaman Utama\');
ta[\'n-mainpage\'] = new Array(\'z\',\'Kunjungi Halaman Utama\');
ta[\'n-portal\'] = new Array(\'\',\'Tentang proyek, apa yang dapat anda lakukan, di mana mencari sesuatu\');
ta[\'n-currentevents\'] = new Array(\'\',\'Temukan informasi tentang kejadian terkini\');
ta[\'n-recentchanges\'] = new Array(\'r\',\'Daftar perubahan terbaru dalam wiki.\');
ta[\'n-randompage\'] = new Array(\'x\',\'Tampilkan sembarang halaman\');
ta[\'n-help\'] = new Array(\'\',\'Tempat mencari bantuan.\');
ta[\'n-sitesupport\'] = new Array(\'\',\'Dukung kami\');
ta[\'t-whatlinkshere\'] = new Array(\'j\',\'Daftar semua halaman wiki yang berpaut ke sini\');
ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'Perubahan terbaru halaman-halaman yang berpaut dengan halaman ini\');
ta[\'feed-rss\'] = new Array(\'\',\'RSS feed untuk halaman ini\');
ta[\'feed-atom\'] = new Array(\'\',\'Atom feed untuk halaman ini\');
ta[\'t-contributions\'] = new Array(\'\',\'Lihat daftar sumbangan pengguna ini\');
ta[\'t-emailuser\'] = new Array(\'\',\'Kirimkan surat-e kepada pengguna ini\');
ta[\'t-upload\'] = new Array(\'u\',\'Muatkan gambar atau berkas media\');
ta[\'t-specialpages\'] = new Array(\'q\',\'Daftar semua halaman istimewa\');
ta[\'ca-nstab-main\'] = new Array(\'c\',\'Lihat halaman isi (artikel)\');
ta[\'ca-nstab-user\'] = new Array(\'c\',\'Lihat halaman pengguna\');
ta[\'ca-nstab-media\'] = new Array(\'c\',\'Lihat halaman media\');
ta[\'ca-nstab-special\'] = new Array(\'\',\'Ini adalah halaman istimewa yang tidak dapat disunting.\');
ta[\'ca-nstab-project\'] = new Array(\'a\',\'Lihat halaman proyek\');
ta[\'ca-nstab-image\'] = new Array(\'c\',\'Lihat halaman berkas\');
ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'Lihat pesan sistem\');
ta[\'ca-nstab-template\'] = new Array(\'c\',\'Lihat templat\');
ta[\'ca-nstab-help\'] = new Array(\'c\',\'Lihat halaman bantuan\');
ta[\'ca-nstab-category\'] = new Array(\'c\',\'Lihat halaman kategori\');',

# image deletion
'deletedrevision' => 'Revisi lama yang dihapus $1.',

# browsing diffs
'previousdiff' => '← Perbedaan sebelumnya',
'nextdiff' => 'Perbedaan selanjutnya →',

'imagemaxsize' => 'Batasi ukuran gambar dalam halaman deskripsi berkas sampai:',
'thumbsize'	=> 'Ukuran gambar kecil (thumbnail):',
'showbigimage' => 'Unduhkan versi resolusi tinggi ($1x$2, $3 KB)',

'newimages'		=> 'Galeri berkas baru',
'showhidebots'	=> '($1 bot)',
'noimages'		=> 'Tidak ada yang dilihat.',

# short names for language variants used for language conversion links.
# to disable showing a particular link, set it to 'disable', e.g.
# 'variantname-zh-sg' => 'disable',
'variantname-zh-cn' => 'cn',
'variantname-zh-tw' => 'tw',
'variantname-zh-hk' => 'hk',
'variantname-zh-sg' => 'sg',
'variantname-zh' => 'zh',
# variants for Serbian language
'variantname-sr-ec' => 'sr-ec',
'variantname-sr-el' => 'sr-el',
'variantname-sr-jc' => 'sr-jc',
'variantname-sr-jl' => 'sr-jl',
'variantname-sr' => 'sr',
# variants for Kazakh language
'variantname-kk-tr' => 'kk-tr',
'variantname-kk-kz' => 'kk-kz',
'variantname-kk-cn' => 'kk-cn',
'variantname-kk' => 'kk',

# labels for User: and Title: on Special:Log pages
'specialloguserlabel' => 'Pengguna:',
'speciallogtitlelabel' => 'Judul:',

'passwordtooshort' => 'Kata sandi Anda terlalu pendek. Kata sandi minimum terdiri dari $1 karakter.',

# Media Warning
'mediawarning' => '\'\'\'Peringatan\'\'\': Berkas ini mungkin mengandung kode berbahaya yang jika dijalankan dapat mempengaruhi sistem Anda.<hr />',

'fileinfo' => '$1KB, tipe MIME: <code>$2</code>',

# Metadata
'metadata' => 'Metadata',
'metadata-help' => 'Berkas ini mengandung informasi tambahan yang mungkin ditambahkan oleh kamera digital atau pemindai yang digunakan untuk membuat atau mendigitalisasi berkas. Jika berkas ini telah mengalami modifikasi, detil yang ada mungkin tidak secara penuh merefleksikan informasi dari gambar yang sudah dimodifikasi ini.',
'metadata-expand' => 'Tampilkan detil tambahan',
'metadata-collapse' => 'Sembunyikan detil tambahan',
'metadata-fields' => 'Entri metadata EXIF berikut akan ditampilkan pada halaman informasi gambar jika tabel metadata disembunyikan. Entri lain secara baku akan disembunyikan
* make
* model
* datetimeoriginal
* exposuretime
* fnumber',

# Exif tags
'exif-imagewidth' =>'Lebar',
'exif-imagelength' =>'Tinggi',
'exif-bitspersample' =>'Bit per komponen',
'exif-compression' =>'Skema kompresi',
'exif-photometricinterpretation' =>'Komposisi piksel',
'exif-orientation' =>'Orientasi',
'exif-samplesperpixel' =>'Jumlah komponen',
'exif-planarconfiguration' =>'Pengaturan data',
'exif-ycbcrsubsampling' =>'Rasio subsampling Y ke C',
'exif-ycbcrpositioning' =>'Penempatan Y dan C',
'exif-xresolution' =>'Resolusi horizontal',
'exif-yresolution' =>'Resolusi vertikal',
'exif-resolutionunit' =>'Satuan resolusi X dan Y',
'exif-stripoffsets' =>'Lokasi data gambar',
'exif-rowsperstrip' =>'Jumlah baris per strip',
'exif-stripbytecounts' =>'Bita per strip kompresi',
'exif-jpeginterchangeformat' =>'Ofset ke JPEG SOI',
'exif-jpeginterchangeformatlength' =>'Bita data JPEG',
'exif-transferfunction' =>'Fungsi transfer',
'exif-whitepoint' =>'Kromatisitas titik putih',
'exif-primarychromaticities' =>'Kromatisitas warna primer',
'exif-ycbcrcoefficients' =>'Koefisien matriks transformasi ruang warna',
'exif-referenceblackwhite' =>'Nilai referensi pasangan hitam putih',
'exif-datetime' =>'Tanggal dan waktu perubahan berkas',
'exif-imagedescription' =>'Judul gambar',
'exif-make' =>'Produsen kamera',
'exif-model' =>'Model kamera',
'exif-software' =>'Perangkat lunak',
'exif-artist' =>'Pembuat',
'exif-copyright' =>'Pemilik hak cipta',
'exif-exifversion' =>'Versi Exif',
'exif-flashpixversion' =>'Dukungan versi Flashpix',
'exif-colorspace' =>'Ruang warna',
'exif-componentsconfiguration' =>'Arti tiap komponen',
'exif-compressedbitsperpixel' =>'Mode kompresi gambar',
'exif-pixelydimension' =>'Lebar gambar yang sah',
'exif-pixelxdimension' =>'Tinggi gambar yang sah',
'exif-makernote' =>'Catatan produsen',
'exif-usercomment' =>'Komentar pengguna',
'exif-relatedsoundfile' =>'Berkas audio yang berhubungan',
'exif-datetimeoriginal' =>'Tanggal dan waktu pembuatan data',
'exif-datetimedigitized' =>'Tanggal dan waktu digitalisasi',
'exif-subsectime' =>'Subdetik DateTime',
'exif-subsectimeoriginal' =>'Subdetik DateTimeOriginal',
'exif-subsectimedigitized' =>'Subdetik DateTimeDigitized',
'exif-exposuretime' =>'Waktu pajanan',
'exif-exposuretime-format' => '$1 detik ($2)',
'exif-fnumber' =>'Nilai F',
'exif-fnumber-format' =>'f/$1',
'exif-exposureprogram' =>'Program pajanan',
'exif-spectralsensitivity' =>'Sensitivitas spektral',
'exif-isospeedratings' =>'ISO speed rating',
'exif-oecf' =>'Faktor konversi optoelektronik',
'exif-shutterspeedvalue' =>'Kecepatan shutter',
'exif-aperturevalue' =>'Aperture',
'exif-brightnessvalue' =>'Brightness',
'exif-exposurebiasvalue' =>'Bias pajanan',
'exif-maxaperturevalue' =>'Maximum land aperture',
'exif-subjectdistance' =>'Jarak subyek',
'exif-meteringmode' =>'Metering mode',
'exif-lightsource' =>'Sumber cahaya',
'exif-flash' =>'Flash',
'exif-focallength' =>'Lens focal length',
'exif-focallength-format' =>'$1 mm',
'exif-subjectarea' =>'Wilayah subyek',
'exif-flashenergy' =>'Flash energy',
'exif-spatialfrequencyresponse' =>'Respons frekuensi spasial',
'exif-focalplanexresolution' =>'Resolusi focal plane X',
'exif-focalplaneyresolution' =>'Resolusi focal plane Y',
'exif-focalplaneresolutionunit' =>'Unit resolusi focal plane',
'exif-subjectlocation' =>'Lokasi subyek',
'exif-exposureindex' =>'Indeks pajanan',
'exif-sensingmethod' =>'Metode sensing',
'exif-filesource' =>'Sumber berkas',
'exif-scenetype' =>'Tipe scene',
'exif-cfapattern' =>'Pola CFA',
'exif-customrendered' =>'Proses buatan gambar',
'exif-exposuremode' =>'Mode pajanan',
'exif-whitebalance' =>'White Balance',
'exif-digitalzoomratio' =>'Rasio pembesaran digital',
'exif-focallengthin35mmfilm' =>'Focal length in 35 mm film',
'exif-scenecapturetype' =>'Tipe scene capture',
'exif-gaincontrol' =>'Kontrol scene',
'exif-contrast' =>'Kontras',
'exif-saturation' =>'Saturasi',
'exif-sharpness' =>'Ketajaman',
'exif-devicesettingdescription' =>'Deskripsi pengaturan alat',
'exif-subjectdistancerange' =>'Jarak subyek',
'exif-imageuniqueid' =>'ID unik gambar',
'exif-gpsversionid' =>'Versi tag GPS',
'exif-gpslatituderef' =>'Lintang Utara atau Selatan',
'exif-gpslatitude' =>'Lintang',
'exif-gpslongituderef' =>'Bujur Timur atau Barat',
'exif-gpslongitude' =>'Bujur',
'exif-gpsaltituderef' =>'Referensi ketinggian',
'exif-gpsaltitude' =>'Ketinggian',
'exif-gpstimestamp' =>'Waktu GPS (jam atom)',
'exif-gpssatellites' =>'Satelit untuk pengukuran',
'exif-gpsstatus' =>'Status penerima',
'exif-gpsmeasuremode' =>'Mode pengukuran',
'exif-gpsdop' =>'Ketepatan pengukuran',
'exif-gpsspeedref' =>'Unit kecepatan',
'exif-gpsspeed' =>'Kecepatan penerima GPS',
'exif-gpstrackref' =>'Referensi arah gerakan',
'exif-gpstrack' =>'Arah gerakan',
'exif-gpsimgdirectionref' =>'Referensi arah gambar',
'exif-gpsimgdirection' =>'Arah gambar',
'exif-gpsmapdatum' =>'Data survei geodesi',
'exif-gpsdestlatituderef' =>'Referensi lintang dari tujuan',
'exif-gpsdestlatitude' =>'Lintang tujuan',
'exif-gpsdestlongituderef' =>'Referensi bujur dari tujuan',
'exif-gpsdestlongitude' =>'Bujur tujuan',
'exif-gpsdestbearingref' =>'Referensi bearing of destination',
'exif-gpsdestbearing' =>'Bearing of destination',
'exif-gpsdestdistanceref' =>'Referensi jarak dari tujuan',
'exif-gpsdestdistance' =>'Jarak dari tujuan',
'exif-gpsprocessingmethod' =>'Nama metode proses GPS',
'exif-gpsareainformation' =>'Nama wilayah GPS',
'exif-gpsdatestamp' =>'Tanggal GPS',
'exif-gpsdifferential' =>'Koreksi diferensial GPS',

# Exif attributes

'exif-compression-1' => 'Tak terkompresi',
'exif-compression-6' => 'JPEG',

'exif-photometricinterpretation-2' => 'RGB',
'exif-photometricinterpretation-6' => 'YCbCr',

'exif-orientation-1' => 'Normal', // 0th row: top; 0th column: left
'exif-orientation-2' => 'Dibalik horizontal', // 0th row: top; 0th column: right
'exif-orientation-3' => 'Diputar 180°', // 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Dibalik vertikal', // 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Diputar 90° CCW dan dibalik vertical', // 0th row: left; 0th column: top
'exif-orientation-6' => 'Diputar 90° CW', // 0th row: right; 0th column: top
'exif-orientation-7' => 'Diputar 90° CW dan dibalik vertical', // 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Diputar 90° CCW', // 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'format chunky',
'exif-planarconfiguration-2' => 'format planar',

'exif-xyresolution-i' => '$1 dpi',
'exif-xyresolution-c' => '$1 dpc',

'exif-colorspace-1' => 'sRGB',
'exif-colorspace-ffff.h' => 'FFFF.H',

'exif-componentsconfiguration-0' => 'tak tersedia',
'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',
'exif-componentsconfiguration-4' => 'R',
'exif-componentsconfiguration-5' => 'G',
'exif-componentsconfiguration-6' => 'B',

'exif-exposureprogram-0' => 'Tak terdefinisi',
'exif-exposureprogram-1' => 'Manual',
'exif-exposureprogram-2' => 'Program normal',
'exif-exposureprogram-3' => 'Prioritas aperture',
'exif-exposureprogram-4' => 'Prioritas shutter',
'exif-exposureprogram-5' => 'Program kreatif (condong ke depth of field)',
'exif-exposureprogram-6' => 'Program aksi (condong ke fast shutter speed)',
'exif-exposureprogram-7' => 'Mode potret (untuk foto closeup dengan latar belakang tak fokus)',
'exif-exposureprogram-8' => 'Mode pemandangan (untuk foto pemandangan dengan latar belakang fokus)',

'exif-subjectdistance-value' => '$1 meter',

'exif-meteringmode-0' => 'Tak diketahui',
'exif-meteringmode-1' => 'Average',
'exif-meteringmode-2' => 'CenterWeightedAverage',
'exif-meteringmode-3' => 'Spot',
'exif-meteringmode-4' => 'MultiSpot',
'exif-meteringmode-5' => 'Pattern',
'exif-meteringmode-6' => 'Partial',
'exif-meteringmode-255' => 'Lain-lain',

'exif-lightsource-0' => 'Tak diketahui',
'exif-lightsource-1' => 'Daylight',
'exif-lightsource-2' => 'Fluorescent',
'exif-lightsource-3' => 'Tungsten (incandescent light)',
'exif-lightsource-4' => 'Flash',
'exif-lightsource-9' => 'Fine weather',
'exif-lightsource-10' => 'Cloudy weather',
'exif-lightsource-11' => 'Shade',
'exif-lightsource-12' => 'Daylight fluorescent (D 5700 – 7100K)',
'exif-lightsource-13' => 'Day white fluorescent (N 4600 – 5400K)',
'exif-lightsource-14' => 'Cool white fluorescent (W 3900 – 4500K)',
'exif-lightsource-15' => 'White fluorescent (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Standard light A',
'exif-lightsource-18' => 'Standard light B',
'exif-lightsource-19' => 'Standard light C',
'exif-lightsource-20' => 'D55',
'exif-lightsource-21' => 'D65',
'exif-lightsource-22' => 'D75',
'exif-lightsource-23' => 'D50',
'exif-lightsource-24' => 'ISO studio tungsten',
'exif-lightsource-255' => 'Sumber cahaya lain',

'exif-focalplaneresolutionunit-2' => 'inci',

'exif-sensingmethod-1' => 'Tak terdefinisi',
'exif-sensingmethod-2' => 'One-chip color area sensor',
'exif-sensingmethod-3' => 'Two-chip color area sensor',
'exif-sensingmethod-4' => 'Three-chip color area sensor',
'exif-sensingmethod-5' => 'Color sequential area sensor',
'exif-sensingmethod-7' => 'Trilinear sensor',
'exif-sensingmethod-8' => 'Color sequential linear sensor',

'exif-filesource-3' => 'DSC',

'exif-scenetype-1' => 'Gambar foto langsung',

'exif-customrendered-0' => 'Proses normal',
'exif-customrendered-1' => 'Proses kustom',

'exif-exposuremode-0' => 'Pajanan otomatis',
'exif-exposuremode-1' => 'Pajanan manual',
'exif-exposuremode-2' => 'Braket otomatis',

'exif-whitebalance-0' => 'Auto white balance',
'exif-whitebalance-1' => 'Manual white balance',

'exif-scenecapturetype-0' => 'Standar',
'exif-scenecapturetype-1' => 'Melebar',
'exif-scenecapturetype-2' => 'Potret',
'exif-scenecapturetype-3' => 'Scene malam',

'exif-gaincontrol-0' => 'Tak ada',
'exif-gaincontrol-1' => 'Low gain up',
'exif-gaincontrol-2' => 'High gain up',
'exif-gaincontrol-3' => 'Low gain down',
'exif-gaincontrol-4' => 'High gain down',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Lembut',
'exif-contrast-2' => 'Keras',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Saturasi rendah',
'exif-saturation-2' => 'Saturasi tinggi',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Lembut',
'exif-sharpness-2' => 'Keras',

'exif-subjectdistancerange-0' => 'Tak diketahui',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Close view',
'exif-subjectdistancerange-3' => 'Distant view',

// Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Lintang utara',
'exif-gpslatitude-s' => 'Lintang selatan',

// Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Bujur timur',
'exif-gpslongitude-w' => 'Bujur barat',

'exif-gpsstatus-a' => 'Pengukuran sedang berlangsung',
'exif-gpsstatus-v' => 'Interoperabilitas pengukuran',

'exif-gpsmeasuremode-2' => 'Pengukuran 2-dimensi',
'exif-gpsmeasuremode-3' => 'Pengukuran 3-dimensi',

// Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilometer per jam',
'exif-gpsspeed-m' => 'Mil per jam',
'exif-gpsspeed-n' => 'Knot',

// Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Arah sejati',
'exif-gpsdirection-m' => 'Arah magnetis',

# external editor support
'edit-externally' => 'Sunting berkas ini dengan aplikasi luar',
'edit-externally-help' => 'Lihat [http://meta.wikimedia.org/wiki/Help:External_editors instruksi pengaturan] untuk informasi lebih lanjut.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'semua',
'imagelistall' => 'semua',
'watchlistall1' => 'semua',
'watchlistall2' => 'semua',
'namespacesall' => 'semua',

# E-mail address confirmation
'confirmemail' => 'Konfirmasi alamat surat-e',
'confirmemail_noemail' => 'Anda tidak memberikan alamat surat-e yang sah di [[Special:Preferences|preferensi pengguna]] Anda.',
'confirmemail_text' => "{{ns:project}} mengharuskan Anda untuk melakukan konfirmasi atas alamat surat elektronik Anda sebelum fitur-fitur surat elektronik dapat digunakan. Tekan tombol di bawah ini untuk mengirimi Anda sebuah surat elektronik yang berisi kode konfirmasi yang berupa sebuah alamat internet. Salin alamat tersebut ke penjelajah web Anda dan buka alamat tersebut untuk melakukan konfirmasi sehingga menginformasikan {{ns:project}} bahwa alamat surat elektronik Anda valid.",
'confirmemail_send' => 'Kirim kode konfirmasi',
'confirmemail_sent' => 'Surat elektronik berisi kode konfirmasi telah dikirim.',
'confirmemail_sendfailed' => 'Surat-e konfirmasi tidak berhasil dikirim. Harap cek kemungkinan karakter ilegal pada alamat surat-e. Pengirim menginformasikan: $1',
'confirmemail_invalid' => 'Kode konfirmasi salah. Kode tersebut mungkin sudah kadaluwarsa.',
'confirmemail_needlogin' => 'Anda harus melakukan $1 untuk mengkonfirmasikan alamat surat-e Anda.',
'confirmemail_success' => 'Alamat surat-e Anda telah dikonfirmasi. Sekarang Anda dapat masuk log dan mulai menggunakan wiki.',
'confirmemail_loggedin' => 'Alamat surat elektronik Anda telah dikonfirmasi.',
'confirmemail_error' => 'Terjadi kesalahan sewaktu menyimpan konfirmasi Anda.',

'confirmemail_subject' => 'Konfirmasi alamat surat-e {{SITENAME}}',
'confirmemail_body' => "Seseorang, mungkin Anda, dari alamat IP $1, telah mendaftarkan akun \"$2\" dengan alamat surat-e ini di {{SITENAME}}.

Untuk mengkonfirmasikan bahwa akun ini benar dimiliki oleh Anda sekaligus mengaktifkan fitur surat-e di {{SITENAME}}, ikuti pranala berikut pada penjelajah web Anda:

$3

Jika Anda merasa *tidak pernah* mendaftar, jangan ikuti pranala di atas. Kode konfirmasi ini akan kadaluwarsa pada $4.",

# Inputbox extension, may be useful in other contexts as well
'tryexact' => 'Coba pencocokan eksak',
'searchfulltext' => 'Cari di teks lengkap',
'createarticle' => 'Buat artikel',

# Scary transclusion
'scarytranscludedisabled' => '[Transklusi interwiki dimatikan]',
'scarytranscludefailed' => '[Pengambilan templat $1 gagal; maaf]',
'scarytranscludetoolong' => '[URL terlalu panjang; maaf]',

# Trackbacks
'trackbackbox' => '<div id="mw_trackbacks">
Pelacakan balik untuk artikel ini:<br />
$1
</div>',
'trackbackremove' => ' ([$1 Hapus])',
'trackbacklink' => 'Lacak balik',
'trackbackdeleteok' => 'Pelacakan balik berhasil dihapus.',


# delete conflict

'deletedwhileediting' => 'Perhatian: Halaman ini telah dihapus setelah Anda mulai melakukan penyuntingan!',
'confirmrecreate' => 'Pengguna [[{{ns:user}}:$1|$1]] ([[{{ns:user_talk}}:$1|talk]]) telah menghapus halaman selagi Anda mulai melakukan penyuntingan dengan alasan:
: \'\'$2\'\'
Silakan konfirmasi jika Anda ingin membuat ulang halaman ini.',
'recreate' => 'Buat ulang',
'tooltip-recreate' => 'Buat ulang halaman walaupun sebenarnya telah dihapus',

'unit-pixel' => 'px',

# HTML dump
'redirectingto' => 'Sedang dialihkan ke [[$1]]...',

# action=purge
'confirm_purge' => "Hapus ''cache'' halaman ini?

$1",
'confirm_purge_button' => 'OK',

'youhavenewmessagesmulti' => "Anda mendapat pesan-pesan baru $1",
'newtalkseperator' => ',_',
'searchcontaining' => "Mencari artikel yang mengandung ''$1''.",
'searchnamed' => "Mencari artikel yang berjudul ''$1''.",
'articletitles' => "Artikel yang diawali ''$1''",
'hideresults' => 'Sembunyikan hasil',

# DISPLAYTITLE
'displaytitle' => '(Pranala ke halaman ini sebagai [[$1]])',

'loginlanguagelabel' => 'Bahasa: $1',

# Multipage image navigation
'imgmultipageprev' => '&larr; halaman sebelumnya',
'imgmultipagenext' => 'halaman selanjutnya &rarr;',
'imgmultigo' => 'Cari!',
'imgmultigotopre' => 'Ke halaman',

# Table pager
'ascending_abbrev' => 'naik',
'descending_abbrev' => 'turun',
'table_pager_next' => 'Halaman selanjutnya',
'table_pager_prev' => 'Halaman sebelumnya',
'table_pager_first' => 'Halaman pertama',
'table_pager_last' => 'Halaman terakhir',
'table_pager_limit' => 'Tampilkan $1 entri per halaman',
'table_pager_limit_submit' => 'Cari',
'table_pager_empty' => 'Tidak ditemukan',

);

?>
