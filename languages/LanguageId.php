<?php
#
# This localisation is originally based on a file kindly donated by the folks at MIMOS
# http://www.asiaosc.org/enwiki/page/Knowledgebase_Home.html
#


require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesId = array(
	NS_MEDIA            => "Media",
	NS_SPECIAL          => "Istimewa",
	NS_MAIN             => "",
	NS_TALK             => "Bicara",
	NS_USER             => "Pengguna",
	NS_USER_TALK        => "Bicara_Pengguna",
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => "Pembicaraan_" . $wgMetaNamespace,
	NS_IMAGE            => "Gambar",
	NS_IMAGE_TALK       => "Pembicaraan_Gambar",
	NS_MEDIAWIKI        => "MediaWiki",
	NS_MEDIAWIKI_TALK   => "Pembicaraan_MediaWiki",
	NS_TEMPLATE         => "Templat",
	NS_TEMPLATE_TALK    => "Pembicaraan_Templat",
	NS_HELP             => "Bantuan",
	NS_HELP_TALK        => "Pembicaraan_Bantuan",
	NS_CATEGORY         => "Kategori",
	NS_CATEGORY_TALK    => "Pembicaraan_Kategori"
) + $wgNamespaceNamesEn;

# For backwards compatibility: some talk namespaces were
# changed in 1.4.4 from their previous values, here:
$wgNamespaceAlternatesId = array(
	NS_IMAGE_TALK       => "Gambar_Pembicaraan",
	NS_MEDIAWIKI_TALK   => "MediaWiki_Pembicaraan",
	NS_TEMPLATE_TALK    => "Templat_Pembicaraan",
	NS_HELP_TALK        => "Bantuan_Pembicaraan",
	NS_CATEGORY_TALK    => "Kategori_Pembicaraan"
);

/* private */ $wgQuickbarSettingsId = array(
	"Tidak ada", "Tetap sebelah kiri", "Tetap sebelah kanan", "Mengambang sebelah kiri"
);

/* private */ $wgSkinNamesId = array(
	'standard'    => "Standar",
) + $wgSkinNamesEn;

# Validation types
$wgValidationTypesId = array (
	'0' => "Gaya tulisan|Sangat buruk|Sangat baik|5",
	'1' => "Legalitas|Ilegal|Legal|5",
	'2' => "Kelengkapan|Stub|Sangat lengkap|5",
	'3' => "Fakta|Meragukan|Kuat|5",
	'4' => "Layak untuk 1.0 (Cetak)|Tidak|Ya|2",
	'5' => "Layak untuk 1.0 (CD)|Tidak|Ya|2"
);

/* private */ $wgDateFormatsId = array();

/* private */ $wgBookstoreListId = array(
	# Local bookstores
	"Gramedia Cyberstore (via Google)" => 'http://www.google.com/search?q=%22ISBN+:+$1%22+%22product_detail%22+site:www.gramediacyberstore.com+OR+site:www.gramediaonline.com+OR+site:www.kompas.com&hl=id',
	"Bhinneka.com bookstore" => 'http://www.bhinneka.com/Buku/Engine/search.asp?fisbn=$1',

	//# Default (EN) Bookstores
	//"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	//"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	//"Barnes & Noble" => "http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	//"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
) + $wgBookstoreListEn;

/* private */ $wgAllMessagesId = array(
# User preference toggles

'tog-underline' => 'Garisbawahi pautan',
'tog-highlightbroken' => 'Beri tanda pautan yang berpaut ke topik kosong <a href="" class="new">seperti ini</a> (alternatif: seperti ini<a href="" class="internal">?</a>)',
'tog-justify'   => 'Ratakan paragraf',
'tog-hideminor' => 'Sembunyikan suntingan kecil dalam perubahan terbaru',
'tog-usenewrc' => 'Tampilkan perubahan terbaru dalam tampilan yang lebih baik (tidak untuk semua browser)',
'tog-numberheadings' => 'Beri nomor judul secara otomatis',
'tog-showtoolbar' => 'Tampilkan batang alat penyuntingan',
'tog-editondblclick' => 'Sunting halaman dengan klik ganda (JavaScript)',
'tog-editsection'=> 'Sunting bagian melalui pautan [edit]',
'tog-editsectiononrightclick' => 'Sunting bagian dengan mengklik kanan<br />judul bagian (JavaScript)',
'tog-showtoc' => 'Tampilkan daftar isi<br />(untuk artikel yang mempunyai lebih dari 3 judul)',
'tog-rememberpassword' => 'Ingat kata sandi pada setiap sesi',
'tog-editwidth' => 'Kotak sunting memiliki lebar penuh',
'tog-watchdefault' => 'Tambahkan halaman yang Anda sunting ke daftar pemantauan',
'tog-minordefault' => 'Tandai semua suntingan sebagai suntingan kecil secara baku',
'tog-previewontop' => 'Tampilkan pratilik sebelum kotak sunting dan bukan sesudahnya',
'tog-previewonfirst' => 'Tampilkan pratilik pada suntingan pertama',
'tog-nocache' => 'Matikan cache halaman',
'tog-fancysig' => 'Tanda tangan teks murni',

# dates
'sunday' => 'Minggu',
'monday' => 'Senin',
'tuesday' => 'Selasa',
'wednesday' => 'Rabu',
'thursday' => 'Kamis',
'friday' => "Jumat",
'saturday' => 'Sabtu',
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
'jan' => 'Jan',
'feb' => 'Feb',
'mar' => 'Mar',
'apr' => 'Apr',
'may' => 'Mei',
'jun' => 'Jun',
'jul' => 'Jul',
'aug' => 'Agt',
'sep' => 'Sep',
'oct' => 'Okt',
'nov' => 'Nov',
'dec' => 'Des',
# Bits of text used by many pages:
#
"categories" => "Kategori",
"category" => "kategori",
"category_header" => "Artikel dalam kategori \"$1\"",
"subcategories" => "Subkategori",

"linktrail" => "/^([a-z]+)(.*)\$/sD",
"mainpage" => "Halaman Utama",
"mainpagetext" => "Perangkat lunak wiki berhasil dipasang.",
"mainpagedocfooter" => "Silakan lihat [http://meta.wikipedia.org/wiki/MediaWiki_i18n dokumentasi tentang kastamisasi antarmuka] dan [http://meta.wikipedia.org/wiki/MediaWiki_User%27s_Guide petunjuk pengguna] untuk bantuan pemakaian dan konfigurasi.",

'portal' => 'Portal Komunitas',
'portal-url' => 'Project:Portal_Komunitas',
"about" => "Tentang",
"aboutsite" => "Tentang {{SITENAME}}",
"aboutpage" => "Project:Tentang",
'article' => 'Artikel',
"help" => "Bantuan",
"helppage" => "Help:Isi",
"bugreports" => "Laporan Bug",
"bugreportspage" => "Project:Laporan_Bug",
"sitesupport" => "-", # "-" untuk mematikan
"sitesupport-url" => "Project:Sumbangan",
"faq" => "FAQ",
"faqpage" => "Project:FAQ",
"edithelp" => "Bantuan penyuntingan",
"newwindow" => "(terbuka dalam jendela baru)",
"edithelppage" => "Help:Penyuntingan",
"cancel" => "Batalkan",
"qbfind" => "Cari",
"qbbrowse" => "Lihat-lihat",
"qbedit" => "Sunting",
"qbpageoptions" => "Pilihan halaman",
"qbpageinfo" => "Informasi halaman",
"qbmyoptions" => "Pilihan saya",
"qbspecialpages" => "Halaman istimewa",
"mypage" => "Halaman saya",
"moredotdotdot" => "Lebih lanjut...",
"mytalk" => "Pembicaraan saya",
"anontalk" => "Pembicaraan IP ini",
'navigation' => 'Navigasi',
"currentevents" => "Kejadian Terkini",
'currentevents-url' => 'Kejadian Terkini',
"disclaimers" => "Penyangkalan",
"disclaimerpage" => "Project:Penyangkalan_Umum",
"errorpagetitle" => "Kesalahan",
"returnto" => "Kembali ke $1.",
"tagline" => "Dari {{SITENAME}}",
"whatlinkshere" => "Halaman yang berpaut ke sini",
"help" => "Bantuan",
"search" => "Cari",
"go" => "Tampil",
"history" => "Sejarah halaman",
'history_short' => 'Sejarah',
'info_short' => 'Informasi',
"printableversion" => "Versi untuk dicetak",
'edit' => 'Sunting',
"editthispage" => "Sunting halaman ini",
'delete' => 'Hapus',
"deletethispage" => "Hapus halaman ini",
"undelete_short" => "Undelete",
'protect' => 'Lindungi',
"protectthispage" => "Lindungi halaman ini",
'unprotect' => 'Hilangkan Perlindungan',
"unprotectthispage" => "Hilangkan perlindungan",
"newpage" => "(buat) Halaman baru",
"talkpage" => "Diskusikan halaman ini",
'specialpage' => 'Halaman Istimewa',
'personaltools' => 'Alat pribadi',
"postcomment" => "Kirim komentar",
"addsection" => "+",
"articlepage" => "Lihat artikel",
"subjectpage" => "Halaman subjek",
'talk' => 'Diskusi',
'toolbox' => 'Kotak peralatan',
"userpage" => "Lihat halaman pengguna",
"wikipediapage" => "Lihat halaman meta",
"imagepage" => "Lihat halaman gambar",
"viewtalkpage" => "Lihat diskusi",
"otherlanguages" => "Bahasa lain",
"redirectedfrom" => "(Dialihkan dari $1)",
"lastmodified" => "Halaman ini terakhir diubah pada $1.",
"viewcount" => "Halaman ini telah diakses sebanyak $1 kali.",
"copyright" => "Isi tersedia dibawah $1.",
"poweredby" => "{{SITENAME}} berjalan dibawah [http://www.mediawiki.org/ MediaWiki], sebuah engine wiki open-source.",
"printsubtitle" => "(Dari {{SERVER}})",
"protectedpage" => "Halaman yang dilindungi",
"administrators" => "Project:Administrator",
"sysoptitle" => "Akses Sysop Diperlukan",
"sysoptext" => "Tindakan yang Anda minta hanya dapat dilakukan oleh pengguna dengan status \"sysop\". Lihat $1.",
"developertitle" => "Akses Pengembang Diperlukan",
"developertext" => "Tindakan yang Anda minta hanya dapat dilakukan oleh pengguna dengan status \"pengembang\". Lihat $1.",
"nbytes" => "$1 byte",
"ok" => "OK",
"sitetitle" => "{{SITENAME}}",
"pagetitle" => "$1 - {{SITENAME}}",
"sitesubtitle" => "Ensiklopedi Bebas",
"retrievedfrom" => "Diperoleh dari \"$1\"",
"newmessages" => "Anda mendapatkan $1.",
"newmessageslink" => "pesan baru",
"editsection" => "sunting",
"toc" => "Daftar isi",
"showtoc" => "tampilkan",
"hidetoc" => "sembunyikan",
"thisisdeleted" => "Lihat atau kembalikan $1?",
"restorelink" => "$1 suntingan dihapus",
'feedlinks' => 'Feed:',
'sitenotice' => '-', # the equivalent to wgSiteNotice

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'Artikel',
'nstab-user' => 'Halaman pengguna',
'nstab-media' => 'Media',
'nstab-special' => 'Istimewa',
'nstab-wp' => 'Tentang',
'nstab-image' => 'Gambar',
'nstab-mediawiki' => 'Pesan',
'nstab-template' => 'Templat',
'nstab-help' => 'Bantuan',
'nstab-category' => 'Kategori',

# Main script and global functions
#
"nosuchaction" => "Tidak Ada Tindakan Tersebut",
"nosuchactiontext" => "Tindakan yang dispesifikasikan oleh URL tersebut tidak dikenal oleh wiki.",
"nosuchspecialpage" => "Tidak Ada Halaman Istimewa Tersebut",
"nospecialpagetext" => "Anda telah meminta halaman istimewa yang tidak dikenal oleh wiki.",

# General errors
#
"error" => "Kesalahan",
"databaseerror" => "Kesalahan Basis Data",
"dberrortext" => "Ada kesalahan sintaks pada permintaan basis data. Kesalahan ini mungkin menandakan adanya bug dalam perangkat lunak. Permintaan basis data yang terakhir adalah: <blockquote><tt>$1</tt></blockquote> dari dalam fungsi \"<tt>$2</tt>\". Kesalahan MySQL \"<tt>$3: $4</tt>\".",
"dberrortextcl" => "Ada kesalahan sintaks pada permintaan basis data. Permintaan basis data yang terakhir adalah: \"$1\" dari dalam fungsi \"$2\". Kesalahan MySQL \"$3: $4\".\n",
"noconnect" => "Maaf! wiki ini mengalami masalah teknis dan tidak dapat menghubungi basis data.",
"nodb" => "Tidak dapat memilih basis data $1",
"cachederror" => "Berikut ini adalah salinan cache dari halaman yang diminta, yang mungkin tidak up-to-date.",
'laggedslavemode'   => 'Peringatan: Halaman mungkin tidak berisi perubahan terbaru.',
"readonly" => "Basis data dikunci",
"enterlockreason" => "Masukkan alasan penguncian, termasuk perkiraan kapan kunci akan dibuka",
"readonlytext" => "Basis data sedang dikunci terhadap masukan baru. Administrator yang melakukan penguncian memberikan penjelasan sebagai berikut: <p>$1",
"missingarticle" => "Basis data tidak menemukan teks bagi halaman yang seharusnya mempunyai teks, yaitu halaman \"$1\".\n\n<p>Ini biasanya disebabkan karena diff yang kadaluwarsa atau karena pautan sejarah kepada halaman telah dihapus.\n\n<p>Jika ini bukan sebabnya, Anda mungkin menemukan bug dalam perangkat lunak. Silakan laporkan hal ini kepada administrator, dengan mencantumkan URL halaman yang bermasalah tersebut",
"internalerror" => "Kesalahan internal",
"filecopyerror" => "Tidak dapat menyalin file \"$1\" ke \"$2\".",
"filerenameerror" => "Tidak dapat mengubah nama file \"$1\" menjadi \"$2\".",
"filedeleteerror" => "Tidak dapat menghapus file \"$1\".",
"filenotfound" => "Tidak dapat menemukan file \"$1\".",
"unexpected" => "Nilai di luar jangkauan: \"$1\"=\"$2\".",
"formerror" => "Kesalahan: Tidak dapat mengirimkan formulir",
"badarticleerror" => "Tindakan ini tidak dapat dilaksanakan di halaman ini.",
"cannotdelete" => "Tidak dapat menghapus halaman atau gambar yang telah ditentukan.",
"badtitle" => "Judul Tidak Sah",
"badtitletext" => "Judul halaman yang diminta tidak sah, kosong, atau judul antarbahasa atau antarwiki yang salah sambung.",
"perfdisabled" => "Maaf! fitur ini dimatikan sementara karena fitur ini memperlambat pengkalan data sampai-sampai basis data tidak dapat digunakan lagi.",
"perfdisabledsub" => "Ini adalah salinan tersimpan dari $1:",
"perfcached" => "Data berikut ini diambil dari cache dan mungkin tidak up-to-date:",
"wrong_wfQuery_params" => "Parameter salah ke wfQuery()<br />Fungsi: $1<br />Kueri: $2",
"viewsource" => "Lihat sumber",
"protectedtext" => "Halaman ini telah dikunci untuk menghindari penyuntingan; ada beberapa alasan mengapa hal ini terjadi, silakan lihat [[Project:Halaman_dilindungi]].\n\nAnda dapat melihat dan menyalin sumber halaman ini:",
'sqlhidden' => '(Kueri SQL disembunyikan)',

# Login and logout pages
#
"logouttitle" => "Pengguna Logout",
"logouttext" => "Anda telah logout dari sistem. Anda dapat terus menggunakan {{SITENAME}} secara anonim, atau Anda dapat login lagi sebagai pengguna yang sama atau pengguna yang lain. Perhatikan bahwa beberapa halaman mungkin masih terus menunjukkan bahwa Anda masih login sampai Anda membersihkan cache browser Anda\n",

"welcomecreation" => "== Selamat datang, $1! ==

Akun Anda telah dibuat. Jangan lupa mengatur konfigurasi {{SITENAME}} Anda.",

"loginpagetitle" => "Pengguna Login",
"yourname" => "Nama pengguna",
"yourpassword" => "Kata sandi",
"yourpasswordagain" => "Ulangi kata sandi",
"newusersonly" => "(hanya pengguna baru)",
"remembermypassword" => "Selalu ingat kata sandi.",
"loginproblem" => "<b>Ada masalah dengan data login Anda.</b><br />Silakan coba lagi!",
"alreadyloggedin" => "<strong>Pengguna $1, Anda sudah login!</strong><br />\n",

"login" => "Login",
"loginprompt" => "Anda harus mengaktifkan cookies untuk dapat login ke {{SITENAME}}.",
"userlogin" => "Login",
"logout" => "Logout",
"userlogout" => "Logout",
"notloggedin" => "Belum login",
"createaccount" => "Buka akun baru",
"createaccountmail" => "melalui e-mail",
"badretype" => "Kata sandi yang Anda masukkan salah.",
"userexists" => "Nama pengguna yang Anda masukkan telah dipakai. Silakan pilih nama yang lain.",
"youremail" => "e-mail Anda*",
"yourrealname" => "Nama asli Anda*",
'yourlanguage'  => 'Bahasa antarmuka',
'yourvariant'  => 'Varian bahasa',
"yournick" => "Nama samaran (untuk tanda tangan)",
"emailforlost" => "Isian yang bertanda * tidak harus diisi. Walaupun demikian, dengan memberikan alamat e-mail Anda, orang lain dapat menghubungi Anda melalui situs web tanpa perlu memberikan alamat e-mail Anda kepada mereka, dan e-mail Anda juga dapat digunakan untuk mendapatkan kata sandi yang baru (dengan cara dikirimkan ke alamat e-mail Anda) apabila Anda lupa kata sandi Anda.<br /><br />Nama asli, jika Anda memberikannya, akan digunakan untuk memberikan pengenalan atas kerja Anda.",
'prefs-help-realname' => '* <strong>Nama asli</strong> (tidak wajib): jika Anda memberikannya, nama asli Anda akan digunakan untuk memberi pengenalan atas hasil kerja Anda.<br />',
'prefs-help-email' => '* <strong>Email</strong> (tidak wajib): Memungkinkan orang lain untuk menghubungi Anda melalui website tanpa perlu memberikan alamat email Anda kepada mereka, dan juga dapat digunakan untuk mengirimkan kata sandi baru jika Anda lupa password Anda.',
"loginerror" => "Gagal Login",
"nocookiesnew" => "Akun pengguna telah dibuat, tetapi Anda belum login. {{SITENAME}} menggunakan cookies untuk login penggunanya. Cookies pada browser Anda dimatikan. Silakan aktifkan cookies dan login kembali dengan nama pengguna dan kata sandi Anda.",
"nocookieslogin" => "{{SITENAME}} menggunakan cookies untuk login penggunanya. Cookies pada browser Anda dimatikan. Silakan aktifkan cookies dan coba lagi.",
"noname" => "Nama pengguna yang Anda masukkan tidak sah.",
"loginsuccesstitle" => "Login Berhasil",
"loginsuccess" => "Berhasil login ke {{SITENAME}} sebagai \"$1\".",
"nosuchuser" => "Tidak ada pengguna dengan nama \"$1\". Periksalah ejaan Anda, atau gunakan formulir di bawah ini untuk membuka akun baru.",
'nosuchusershort' => "Tidak ada pengguna dengan nama \"$1\". Periksalah ejaan Anda.",
"wrongpassword" => "Kata sandi yang Anda masukkan salah. Silakan coba lagi.",
"mailmypassword" => "e-mailkan kata sandi baru",
"passwordremindertitle" => "Peringatan Kata Sandi dari {{SITENAME}}",
"passwordremindertext" => "Seseorang (mungkin Anda, dari alamat IP $1) meminta kami mengirimkan kata sandi {{SITENAME}} yang baru. Kata sandi untuk pengguna \"$2\" sekarang adalah \"$3\". Anda disarankan segera melakukan login dan mengganti kata sandi.",

"noemail" => "Tidak ada alamat e-mail yang tercatat untuk pengguna \"$1\".",
"passwordsent" => "Kata sandi baru telah dikirimkan kepada e-mail yang didaftarkan untuk \"$1\". Silakan login kembali setelah menerima e-mail tersebut.", #Please log in again after you receive it.",
"loginend" => " ",
"mailerror" => "Kesalahan dalam mengirimkan e-mail: $1",
'acct_creation_throttle_hit' => 'Maaf, Anda telah membuat $1 akun. Anda tidak dapat membuat akun lagi.',

# Edit page toolbar
"bold_sample" => "Teks ini akan dicetak tebal",
"bold_tip" => "Cetak tebal",
"italic_sample" => "Teks ini akan dicetak miring",
"italic_tip" => "Cetak miring",
"link_sample" => "Judul pautan",
"link_tip" => "Pautan internal",
"extlink_sample" => "http://www.contoh.com/ judul pautan",
"extlink_tip" => "Pautan eksternal (ingat awalan http:// )",
"headline_sample" => "Teks headline",
"headline_tip" => "Headline level 2",
"math_sample" => "Masukkan rumus di sini",
"math_tip" => "Rumus matematika (LaTeX)",
"nowiki_sample" => "Teks ini tidak akan diformat",
"nowiki_tip" => "Abaikan pemformatan wiki",
"image_sample" => "Contoh.jpg",
"image_tip" => "Gambar embedded",
"media_sample" => "Contoh.ogg",
"media_tip" => "Pautan file media",
"sig_tip" => "Tanda tangan Anda dengan tanda waktu",
"hr_tip" => "Garis horisontal (gunakan dengan hemat)",
"infobox" => "Klik sebuah tombol untuk mendapatkan teks contoh",
# alert box shown in browsers where text selection does not work, test e.g. with mozilla or konqueror
"infobox_alert" => "Silakan masukkan teks yang ingin diformat.\\n Teks tersebut akan ditampilkan di infobox untuk penyalinan dan penempelan.\\nContoh:\\n$1\\nakan menjadi:\\n$2",

# Edit pages
#
"summary" => "Ringkasan",
"subject" => "Subjek/headline",
"minoredit" => "Ini adalah suntingan kecil.",
"watchthis" => "Pantau artikel ini",
"savearticle" => "Simpan halaman<br />",
"preview" => "Pratilik",
"showpreview" => "Tampilkan pratilik",
"blockedtitle" => "Pengguna Diblokir",
"blockedtext" => "Nama pengguna atau alamat IP Anda telah diblokir oleh $1. Alasannya karena :<br />$2<p>Anda dapat menghubungi [[Project:Administrators|para administrator]] untuk membicarakan blokir ini.\n\nPerhatikan bahwa Anda tidak dapat menggunakan fasilitas \"e-mail pengguna ini\" kecuali Anda mempunyai sebuah alamat e-mail yang sah dan alamat e-mail tersebut tercatat di dalam [[Special:Preferences|konfigurasi Anda]].\n\nAlamat IP Anda adalah $3. Sertakan alamat IP ini pada setiap pertanyaan yang Anda buat",
"whitelistedittitle" => "Login Diperlukan untuk Menyunting",
"whitelistedittext" => "Anda harus [[Special:Userlogin|login]] untuk dapat menyunting artikel.",
"whitelistreadtitle" => "Login Diperlukan untuk Membaca",
"whitelistreadtext" => "Anda harus [[Special:Userlogin|login]] untuk dapat membaca artikel.",
"whitelistacctitle" => "Anda Tidak Diperbolehkan untuk Membuat Akun",
"whitelistacctext" => "Untuk dapat membuat akun dalam Wiki ini, Anda harus [[Special:Userlogin|login]] dan mempunyai izin yang tepat.",
"loginreqtitle" => "Login Diperlukan",
"loginreqtext" => "Anda harus [[Special:Userlogin|login]] untuk dapat melihat halaman lainnya.",
"accmailtitle" => "Kata Sandi Dikirimkan",
"accmailtext" => "Kata sandi untuk '$1' telah dikirimkan ke $2.",
"newarticle" => "(Baru)",
"newarticletext" => "Anda mengikuti pautan ke halaman yang belum ada.\nUntuk membuat halaman tersebut, ketiklah isi halaman di kotak di bawah ini\n(lihat [[Project:Bantuan|halaman bantuan]] untuk informasi lebih lanjut).\nJika Anda tanpa sengaja sampai ke halaman ini, klik tombol '''back''' di browser anda.",
"talkpagetext" => "<!-- MediaWiki:talkpagetext -->",
"anontalkpagetext" => "---- ''Ini adalah halaman diskusi seorang pengguna anonim yang belum membuat akun atau tidak menggunakannya. Karena Ia tidak membuat akun, kami terpaksa harus memakai [[alamat IP]]-nya untuk mengenalinya. Alamat IP seperti ini dapat dipakai oleh beberapa pengguna yang berbeda. Jika Anda adalah seorang pengguna anonim dan merasa mendapatkan komentar-komentar miring, silakan [[Special:Userlogin|membuat akun atau login]] untuk menghindari kerancuan dengan pengguna anonim lain di lain waktu.'' ",
"noarticletext" => "(Tidak ada teks dalam halaman ini)",
'clearyourcache' => "'''Catatan:''' Setelah menyimpan konfigurasi, Anda perlu membersihkan cache browser Anda untuk melihat perubahan: '''Mozilla / Firefox:''' ''Ctrl-Shift-R'', '''IE:''' ''Ctrl-F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.", 
'usercssjsyoucanpreview' => "<strong>Tips:</strong> Gunakan tombol 'Tampilkan pratilik' untuk menguji css/js baru Anda sebelum menyimpannya.", #<strong>Tip:</strong> Use the 'Show preview' button to test your new css/js before saving.
'usercsspreview' => "'''Ingatlah bahwa yang Anda lihat hanyalah pratilik CSS Anda, dan bahwa pratilik tersebut belum disimpan!'''", #'''Remember that you are only previewing your user css, it has not yet been saved!'''
'userjspreview' => "'''Ingatlah bahwa yang Anda lihat hanyalah pratilik JavaScript Anda, dan bahwa pratilik tersebut belum disimpan!'''", #'''Remember that you are only testing/previewing your user javascript, it has not yet been saved!'''
"updated" => "(Diperbarui)",
"note" => "<strong>Catatan:</strong> ",
"previewnote" => "Ingatlah bahwa ini hanyalah pratilik yang belum disimpan!",
"previewconflict" => "Pratilik ini mencerminkan teks pada bagian atas sebagaimana Ia akan terlihat bila Anda menyimpannya.",
"editing" => "Menyunting $1",
"editconflict" => "Konflik penyuntingan: $1",
"explainconflict" => "Orang lain telah menyunting halaman ini sejak Anda mulai menyuntingnya. Bagian atas teks ini mengandung teks halaman saat ini. Perubahan yang Anda lakukan ditunjukkan pada bagian bawah teks. Anda hanya perlu menggabungkan perubahan Anda dengan teks yang telah ada. <b>Hanya</b> teks pada bagian atas halamanlah yang akan disimpan apabila Anda menekan \"Simpan halaman\".\n<p>",
"yourtext" => "Teks Anda",
"storedversion" => "Versi tersimpan",
'nonunicodebrowser' => "<strong>PERINGATAN: Browser Anda tidak mendukung Unicode, silakan ganti browser Anda sebelum menyunting artikel.</strong>",
"editingold" => "<strong>PERINGATAN: Anda menyunting revisi lama suatu halaman. Jika Anda menyimpannya, perubahan-perubahan yang dibuat sejak revisi ini akan hilang.</strong>",
"yourdiff" => "Perbedaan",
"copyrightwarning" => "Perhatikan bahwa semua sumbangan terhadap {{SITENAME}} dianggap dilisensikan di bawah lisensi $2 (lihat $1 untuk informasi lebih lanjut). Jika Anda tidak menginginkan tulisan Anda disunting dan disebarkan ke halaman web yang lain, jangan kirimkan artikel Anda ke sini.<br /> Anda juga berjanji bahwa ini adalah hasil karya Anda sendiri, atau Anda menyalinnya dari sumber milik umum atau sumber bebas yang lain. <p><strong>JANGAN KIRIMKAN KARYA YANG DILINDUNGI HAK CIPTA TANPA IZIN!</strong></p> <p><strong>JANGAN SALIN ARTIKEL DARI HALAMAN WEB LAIN.</strong></p> ",
'copyrightwarning2' => "Perhatikan bahwa semua sumbangan terhadap {{SITENAME}} dapat disunting, diubah, atau dihapus oleh penyumbang lainnya. Jika Anda tidak ingin artikel anda disunting, jangan kirimkan artikel Anda ke sini.<br /> Anda juga berjanji bahwa ini adalah hasil karya Anda sendiri, atau disalin dari sumber milik umum atau sumber bebas yang lain (lihat $1 untuk informasi lebih lanjut).<strong>JANGAN KIRIMKAN KARYA YANG DILINDUNGI HAK CIPTA TANPA IZIN!</strong>",
"longpagewarning" => "<strong>PERINGATAN: Halaman ini panjangnya adalah $1 kilobyte; beberapa browser mungkin mengalami masalah dalam menyunting halaman yang panjangnya 32 kB atau lebih. Pertimbangkan memecah halaman menjadi beberapa halaman kecil.</strong>",
"readonlywarning" => "<strong>PERINGATAN: Basis data sedang dikunci karena pemeliharaan, sehingga saat ini Anda tidak akan dapat menyimpan hasil penyuntingan Anda. Anda mungkin perlu memindahkan hasil penyuntingan Anda ini ke tempat lain untuk disimpan belakangan.</strong>",
"protectedpagewarning" => "<strong>PERINGATAN:  Halaman ini telah dikunci sehingga hanya pemakai dengan status sysop saja yang dapat menyuntingnya. Pastikan Anda mengikuti [[Project:Petunjuk_halaman_dilindungi|aturan halaman yang dilindungi]].</strong>",
'templatesused' => 'Templat yang digunakan di halaman ini:',

# History pages
#
"revhistory" => "Sejarah revisi",
"nohistory" => "Tidak ada sejarah penyuntingan untuk halaman ini",
"revnotfound" => "Revisi tidak ditemukan",
"revnotfoundtext" => "Revisi lama halaman yang Anda minta tidak dapat ditemukan. Silakan periksa URL yang digunakan untuk mengakses halaman ini.\n", #"The old revision of the page you asked for could not be found. Please check the URL you used to access this page.\n",
"loadhist" => "Memuat halaman sejarah",
"currentrev" => "Revisi sekarang",
"revisionasof" => "Revisi per $1",
'revisionasofwithlink'  => 'Revisi per $1; $2<br />$3 | $4',
'previousrevision'      => '←Revisi yang lebih tua',
'nextrevision'          => 'Revisi yang lebih baru→',
'currentrevisionlink'   => 'lihat revisi sekarang',
"cur" => "kini",
"next" => "berikut",
"last" => "akhir",
"orig" => "asli",
"histlegend" => "Cara membandingkan: tandai radio button versi-versi yang ingin dibandingkan, lalu tekan ENTER atau tombol di bawah.<br />Legenda: (kini) = perbedaan dengan versi sekarang, (akhir) = perbedaan dengan versi sebelumnya, m = suntingan kecil",
'history_copyright' => '-',

# Diffs
#
"difference" => "(Perbedaan antarrevisi)",
"loadingrev" => "memuat revisi untuk dibandingkan",
"lineno" => "Baris $1:",
"editcurrent" => "Sunting versi sekarang dari halaman ini",
'selectnewerversionfordiff' => 'Pilih sebuah versi yang lebih baru untuk perbandingan',
'selectolderversionfordiff' => 'Pilih sebuah versi yang lebih tua untuk perbandingan',
'compareselectedversions' => 'Bandingkan versi terpilih',

# Search results
#
"searchresults" => "Hasil Pencarian",
"searchresulttext" => "Untuk informasi lebih lanjut tentang pencarian di {{SITENAME}}, lihat [[Project:Pencarian|Mencari {{SITENAME}}]].",
"searchquery" => "Untuk kueri \"$1\"",
"badquery" => "Format kueri pencarian salah",
"badquerytext" => "Kami tidak dapat memproses kueri Anda. Hal ini mungkin disebabkan karena Anda mencoba mencari kata yang panjangnya kurang dari tiga huruf, yang masih belum didukung oleh sistem ini. Hal ini juga dapat disebabkan oleh kesalahan pengetikan ekspresi, misalnya \"fish and and scales\". Silakan coba kueri yang lain.",
"matchtotals" => "Kueri \"$1\" cocok dengan $2 judul halaman dan teks dari $3 artikel.",
"nogomatch" => "Tidak ada halaman dengan judul persis seperti ini, mencoba pencarian full text.",
"titlematches" => "Judul artikel yang cocok",
"notitlematches" => "Tidak ada judul halaman yang cocok",
"textmatches" => "Teks artikel yang cocok",
"notextmatches" => "Tidak ada teks halaman yang cocok",
"prevn" => "$1 sebelumnya",
"nextn" => "$1 berikutnya",
"viewprevnext" => "Lihat ($1) ($2) ($3).",
"showingresults" => "Di bawah ditampilkan sampai <b>$1</b> hasil pencarian, dimulai dari #<b>$2</b>.",
"showingresultsnum" => "Di bawah ditampilkan <b>$3</b> hasil pencarian, dimulai dari #<b>$2</b>.",
"nonefound" => "'''Catatan''': Kegagalan pencarian biasanya disebabkan oleh pencarian kata-kata umum, seperti \"have\" and \"from\", yang biasanya tidak diindeks, atau dengan menentukan lebih dari satu aturan pencarian (hanya halaman yang mengandung semua aturan pencarianlah yang akan ditampilkan dalam hasil pencarian)",
"powersearch" => "Cari",
"powersearchtext" => "Cari dalam namespace :<br />$1<br />$2 Juga tampilkan peralihan   Cari $3 $9",
"searchdisabled" => '<p style="margin: 1.5em 2em 1em">Pencarian {{SITENAME}} dimatikan sementara karena masalah performa. Dalam pada itu, Anda dapat mencari melalui Google. <span style="font-size: 89%; display: block; margin-left: .2em">Perhatikan bahwa hasil pencarian Google mungkin out-of-date.</span></p>", #"<p>Sorry! Full text search has been disabled temporarily, for performance reasons. In the meantime, you can use the Google search below, which may be out of date.</p>',
'googlesearch' => '
<div style="margin-left: 2em">

<!-- Google search -->
<div style="width:130px;float:left;text-align:center;position:relative;top:-8px"><a href="http://www.google.com/" style="paddin
:0;background-image:none"><img src="http://www.google.com/logos/Logo_40wht.gif" alt="Google" style="border:none" /></a></div>

<form method="get" action="http://www.google.com/search" style="margin-left:135px">
  <div>
    <input type="hidden" name="domains" value="{{SERVER}}" />
    <input type="hidden" name="num" value="50" />
    <input type="hidden" name="hl" value="id" />
    <input type="hidden" name="ie" value="$2" />
    <input type="hidden" name="oe" value="$2" />
    
    <input type="text" name="q" size="31" maxlength="255" value="$1" />
    <input type="submit" name="btnG" value="Mesin Cari Google" />
  </div>
  <div style="font-size:90%">
    <input type="radio" name="sitesearch" id="gwiki" value="{{SERVER}}" checked="checked" /><label for="gwiki">{{SITENAME}}</label>
    <input type="radio" name="sitesearch" id="gWWW" value="" /><label for="gWWW">WWW</label>
  </div>
</form>

</div>',
"blanknamespace" => "(Utama)",

# Preferences page
#
"preferences" => "Konfigurasi",
"prefsnologin" => "Belum login",
"prefsnologintext" => "Anda harus [[Special:Userlogin|login]] untuk menetapkan konfigurasi Anda.",
"prefslogintext" => "Anda telah login sebagai \"$1\".\nNomor ID Anda adalah $2.\n\nLihat [[Project:Bantuan Konfigurasi]] untuk cara mengatur konfigurasi Anda.",

"prefsreset" => "Konfigurasi telah dikembalikan ke asal dari storage.",
"qbsettings" => "Pengaturan quickbar",
"changepassword" => "Ganti kata sandi",
"skin" => "Skin",
"math" => "Penggambaran math",
"dateformat" => "Format tanggal",
"math_failure" => "Gagal memparse",
"math_unknown_error" => "Kesalahan yang tidak diketahui",
"math_unknown_function" => "fungsi yang tidak diketahui ",
"math_lexing_error" => "kesalahan lexing",
"math_syntax_error" => "kesalahan sintaks",
"math_image_error" => "Konversi PNG gagal; periksa apakah latex, dvips, gs, dan convert terinstal dengan benar",
"math_bad_tmpdir" => "Tidak dapat menulisi atau membuat direktori sementara math",
"math_bad_output" => "Tidak dapat menulisi atau membuat direktori keluaran math",
"math_notexvc" => "Executable texvc hilang; silakan lihat math/README untuk cara konfigurasi.",
'prefs-personal' => 'Data pengguna',
'prefs-rc' => 'Tampilan perubahan terbaru dan stub',
'prefs-misc' => 'Pengaturan macam-macam',
"saveprefs" => "Simpan konfigurasi",
"resetprefs" => "Pengaturan baku",
"oldpassword" => "Kata sandi lama",
"newpassword" => "Kata sandi baru",
"retypenew" => "Ketikkan lagi kata sandi yang baru",
"textboxsize" => "Ukuran kotak teks",
"rows" => "Baris",
"columns" => "Kolom",
"searchresultshead" => "Pengaturan hasil pencarian",
"resultsperpage" => "Hasil pencarian per halaman",
"contextlines" => "Baris ditampilkan per hasil",
"contextchars" => "Karakter untuk konteks per baris",
"stubthreshold" => "Threshold tampilan stub",
"recentchangescount" => "Jumlah judul dalam perubahan terbaru",
"savedprefs" => "Konfigurasi Anda telah disimpan",
'timezonelegend' => 'Daerah waktu',
"timezonetext" => "Masukkan perbedaan waktu (dalam jam) antara waktu setempat dengan waktu server (UTC).",
"localtime" => "Waktu setempat",
"timezoneoffset" => "Perbedaan",
"servertime" => "Waktu server sekarang adalah",
"guesstimezone" => "Isikan dari browser",
"emailflag" => "Matikan e-mail dari pengguna lain",
"defaultns" => "Cari dalam namespace berikut ini secara baku:",
'default' => 'baku',

# User levels special page
#

# switching pan
'editgroup' => 'Sunting Kelompok',
'addgroup' => 'Tambah Kelompok',

'editusergroup' => 'Sunting Kelompok Pengguna',

# group editing
'savegroup' => 'Simpan Kelompok',

# user groups editing
'saveusergroups' => 'Simpan Kelompok Pengguna',

# Recent changes
#
"changes" => "perubahan",
"recentchanges" => "Perubahan Terbaru",
'recentchanges-url' => 'Special:Recentchanges',
"recentchangestext" => "Temukan perubahan terbaru dalam wiki di halaman ini.",
"rcloaderr" => "Memuat perubahan terbaru",
"rcnote" => "Di bawah ini adalah <strong>$1</strong> perubahan terakhir dalam <strong>$2</strong> hari terakhir.",
"rcnotefrom" => "Di bawah ini adalah perubahan sejak <b>$2</b> (ditampilkan sampai <b>$1</b> perubahan).",
"rclistfrom" => "Tampilkan perubahan baru sejak $1",
# "rclinks" => "Show last $1 changes in last $2 hours / last $3 days",
# "rclinks" => "Show last $1 changes in last $2 days.",
"showhideminor" => "$1 suntingan kecil | $2 bot | $3 pengguna yang login | $4 suntingan diperiksa",
"rclinks" => "Tampilkan $1 perubahan terakhir dalam $2 hari terakhir<br />$3",
"rchide" => "dalam bentuk $4; $1 suntingan kecil; $2 namespace sekunder; $3 suntingan berganda.",
"rcliu" => "; $1 penyuntingan dari pengguna yang login",
"diff" => "beda",
"hist" => "sejarah",
"hide" => "sembunyikan",
"show" => "tampilkan",
"tableform" => "tabel",
"listform" => "daftar",
"nchanges" => "$1 perubahan",
"minoreditletter" => "m",
"newpageletter" => "B",
'sectionlink' => '→',

# Upload
#
"upload" => "Unggah",
"uploadbtn" => "Unggahkan file",
"uploadlink" => "Unggahkan gambar",
"reupload" => "Unggahkan ulang",
"reuploaddesc" => "Kembali ke formulir pengunggahan",
"uploadnologin" => "Belum login",
"uploadnologintext" => "Anda harus <a href=\"{{localurl:Special:Userlogin}}\">login</a> untuk dapat mengunggahkan file.",
"uploaderror" => "Kesalahan Pengunggahan",
"uploadtext" => "'''STOP!''' Sebelum Anda mengunggahkan di sini, pastikan bahwa Anda telah membaca dan menaati [[Special:Image_use_policy|kebijaksanaan penggunaan gambar]].\n\nJika ada sebuah file dalam wiki yang mempunyai nama yang sama dengan file yang akan Anda unggahkan, file tersebut akan ditimpa tanpa peringatan dengan file yang akan Anda unggahkan. Jadi, kecuali Anda ingin memperbarui sebuah file, Anda sangat disarankan untuk memeriksa apakah sudah ada file dengan nama yang sama dengan file yang akan Anda unggahkan.\n\nUntuk melihat atau mencari gambar-gambar yang telah diunggahkan, silakan kunjungi [[Special:Imagelist|daftar gambar yang diunggahkan]]}}. Semua pengunggahan dan penghapusan dicatat dalam [[Project:Catatan_Unggah|catatan pengunggahan]].\n\nGunakan formulir berikut ini untuk mengunggahkan file gambar baru untuk dipakai dalam mengilustrasikan artikel Anda. Pada kebanyakan browser, Anda akan melihat tombol \"Browse...\", yang akan menampilkan dialog buka file sistem operasi Anda. Memilih sebuah file akan mengisikan namanya dalam kotak teks di sebelah tombol tersebut. Anda juga harus memberi tanda cek pada kotak cek, menandakan bahwa Anda tidak melanggar hak cipta apapun dengan mengunggahkan file tersebut. Tekan tombol \"Unggah\" untuk menyelesaikan proses pengunggahan. Proses ini mungkin akan memakan waktu agak lama jika Anda memiliki koneksi internet yang lambat. <p>Format file yang disukai adalah JPEG untuk foto, PNG untuk gambar dan simbol, dan OGG untuk suara. Mohon beri nama file Anda secara deskriptif untuk menghindari kebingungan. Untuk memasukkan sebuah gambar ke dalam artikel, gunakan link dalam bentuk '''<nowiki>[[{{ns:6}}:file.jpg]]</nowiki>''' atau '''<nowiki>[[{{ns:6}}:file.png|teks alternatif]]</nowiki>''' atau '''<nowiki>[[{{ns:-2}}:file.ogg]]</nowiki>''' untuk suara.\n\nMohon diperhatikan bahwa sebagaimana dengan halaman {{SITENAME}} yang lain, orang lain dapat menyunting atau menghapus file Anda jika mereka menganggap hal itu perlu, dan Anda juga dapat diblokir bila Anda menyalahgunakan sistem.",

"uploadlog" => "catatan pengunggahan",
"uploadlogpage" => "Catatan_Unggah",
"uploadlogpagetext" => "Di bawah ini adalah daftar terkini file yang diunggahkan. Semua waktu yang ditunjukkan adalah waktu server (UTC).",
"filename" => "Nama file",
"filedesc" => "Ringkasan",
"filestatus" => "Status hak cipta",
"filesource" => "Sumber",
"copyrightpage" => "Project:Hak_Cipta",
"copyrightpagename" => "Hak cipta {{SITENAME}}",
"uploadedfiles" => "File yang telah diunggahkan",
"ignorewarning" => "Abaikan peringatan dan simpan file",
"minlength" => "Nama gambar sekurang-kurangnya harus tiga huruf.",
'illegalfilename' => 'Nama file "$1" mengandung karakter yang tidak diizinkan dalam judul halaman. Silakan ubah nama file tersebut dan cobalah mengunggahkannya kembali.',
"badfilename" => "Nama gambar telah diubah menjadi \"$1\".",
"badfiletype" => "\".$1\" ialah format file gambar yang tidak diizinkan.",
"largefile" => "Ukuran gambar disarankan tidak melebihi 100k.",
'emptyfile' => 'File yang Anda unggahkan kelihatannya kosong. Hal ini mungkin disebabkan karena adanya kesalahan ketik pada nama file. Silakan pastikan apakah Anda benar-benar ingin mengunggahkan file ini.',
'fileexists' => 'Sebuah file dengan nama tersebut telah ada, silakan periksa $1 jika Anda ragu-ragu apakah Anda ingin mengubahnya.',
"successfulupload" => "Berhasil diunggahkan",
"fileuploaded" => "File \"$1\" berhasil diunggahkan. Silakan ikuti pautan berikut: $2 ke halaman deskripsi dan isikan informasi tentang file tersebut, seperti dari mana file tersebut berasal, kapan file itu dibuat dan oleh siapa, dan informasi lain yang Anda ketahui.",
"uploadwarning" => "Peringatan pengunggahan",
"savefile" => "Simpan file",
"uploadedimage" => "mengunggahkan \"[[$1]]\"",
"uploaddisabled" => "Maaf, pengunggahan dimatikan.",
'uploadcorrupt' => 'File tersebut rusak atau ekstensinya salah. Silakan periksa file tersebut dan unggahkanlah kembali.',

# Image list
#
"imagelist" => "Daftar Gambar",
"imagelisttext" => "Di bawah ini adalah daftar gambar yang telah diurutkan $2.",
"getimagelist" => "memperoleh daftar gambar",
"ilsubmit" => "Cari",
"showlast" => "Tampilkan $1 gambar terakhir yang telah diurutkan $2.",
"byname" => "berdasarkan nama",
"bydate" => "berdasarkan tanggal",
"bysize" => "berdasarkan ukuran",
"imgdelete" => "hapus",
"imgdesc" => "desc",
"imglegend" => "Legenda: (desc) = lihat/sunting deskripsi gambar.",
"imghistory" => "Sejarah gambar",
"revertimg" => "revert",
"deleteimg" => "hapus",
'deleteimgcompletely' => 'Hapus semua revisi',
"imghistlegend" => "Legend: (kini) = ini adalah gambar yang sekarang, (hapus) = hapus versi lama ini, (rv) = kembalikan ke versi lama ini. <br /><i>Klik pada tanggal untuk melihat gambar yang diunggahkan pada tanggal tersebut</i>.",
"imagelinks" => "Pautan gambar",
"linkstoimage" => "Halaman-halaman berikut berpaut ke gambar ini:",
"nolinkstoimage" => "Tidak ada halaman yang berpaut ke gambar ini.",
"sharedupload" => "File ini adalah unggahan bersama yang mungkin juga dipakai oleh proyek lain.",

# Statistics
#
"statistics" => "Statistik",
"sitestats" => "Statistik situs",
"userstats" => "Statistik pengguna",
"sitestatstext" => "Ada sejumlah <b>$1</b> halaman dalam basis data. Ini termasuk halaman \"pembicaraan\", halaman tentang {{SITENAME}}, halaman minimum \"stub\", peralihan halaman, dan halaman-halaman lain yang mungkin bukan artikel. Selain itu, ada <b>$2</b> halaman yang mungkin adalah artikel yang sah.<p> Ada sejumlah <b>$3</b> penampilan halaman, dan sejumlah <b>$4</b> penyuntingan sejak wiki ini dimulai. Ini berarti rata-rata <b>$5</b> suntingan per halaman, dan <b>$6</b> penampilan per penyuntingan.",
"userstatstext" => "Ada <b>$1</b> pengguna terdaftar. <b>$2</b> diantaranya adalah administrator (lihat $3).",

# Maintenance Page
#
"maintenance" => "Halaman Pemeliharaan",
"maintnancepagetext" => "Halaman ini mengandung beberapa peralatan untuk pemeliharaan harian. Kebanyakan fungsi yang terdapat di sini cenderung membebani basis data, jadi mohon jangan tekan tombol 'reload' setelah melakukan perbaikan ;-)",
"maintenancebacklink" => "Kembali ke halaman pemeliharaan",
"disambiguations" => "Halaman Disambiguation",
"disambiguationspage" => "Project:Pautan_ke_halaman_disambiguation",
"disambiguationstext" => "Halaman-halaman berikut ini berpaut ke sebuah <i>halaman disambiguation</i>. Halaman-halaman tersebut seharusnya berpaut ke topik-topik yang tepat.<br />Satu halaman dianggap sebagai disambiguation apabila halaman tersebut disambung dari $1.<br />Pautan dari namespace lain <i>tidak</i> terdaftar di sini.",
"doubleredirects" => "Peralihan Halaman Berganda",
"doubleredirectstext" => "Setiap baris mengandung pautan ke peralihan pertama dan kedua, dan juga baris pertama dari teks peralihan kedua, yang biasanya memberikan artikel tujuan yang \"sesungguhnya\", yang seharusnya ditunjuk oleh peralihan yang pertama.",
"brokenredirects" => "Peralihan Halaman Rusak",
"brokenredirectstext" => "Peralihan halaman berikut berpaut ke halaman yang tidak ada",
"selflinks" => "Halaman-Halaman dengan Pautan Sendiri",
"selflinkstext" => "Halaman-halaman berikut mengandung pautan ke dirinya sendiri, yang seharusnya tidak diizinkan.",
"mispeelings" => "Halaman-Halaman dengan Kesalahan Ejaan",
"mispeelingstext" => "Halaman-halaman berikut mengandung kesalahan ejaan yang didaftar di $1. Ejaan yang benar mungkin diberikan (seperti ini).",
"mispeelingspage" => "Daftar kesalahan ejaan yang umum",
"missinglanguagelinks" => "Pautan bahasa yang hilang",
"missinglanguagelinksbutton" => "Cari pautan bahasa yang hilang untuk",
"missinglanguagelinkstext" => "Halaman ini <i>tidak</i> menyambung ke halaman rekannya di $1. Peralihan dan sub halaman <i>tidak</i> ditunjukkan.",


# Miscellaneous special pages
#
"orphans" => "Halaman Yatim",
'geo' => 'Koordinat GEO',
'validate' => 'Sahkan halaman',
"lonelypages" => "Halaman Yatim",
'uncategorizedpages' => 'Halaman Tak Berkategori',
'uncategorizedcategories' => 'Kategori Tak Berkategori',
"unusedimages" => "Gambar yang Tidak Digunakan",
"popularpages" => "Halaman Populer",
"nviews" => "$1 penampilan",
"wantedpages" => "Halaman yang Diinginkan",
"nlinks" => "$1 pautan",
"allpages" => "Semua Halaman",
'nextpage' => 'Halaman berikutnya ($1)',
"randompage" => "Sembarang Halaman",
'randompage-url'=> 'Special:Randompage',
"shortpages" => "Halaman Pendek",
"longpages" => "Halaman Panjang",
"deadendpages" => "Halaman Buntu",
"listusers" => "Daftar Pengguna",
"specialpages" => "Halaman Istimewa",
"spheading" => "Halaman Istimewa untuk Semua Pengguna",
"protectpage" => "Lindungi halaman",
"recentchangeslinked" => "Perubahan terkait",
"rclsub" => "(untuk halaman yang berpaut dari \"$1\")",
"debug" => "Debug",
"newpages" => "Halaman Baru",
"ancientpages" => "Artikel Tua",
"intl" => "Pautan Antarbahasa",
'move' => 'Pindahkan',
"movethispage" => "Pindahkan halaman ini",
"unusedimagestext" => "<p>Perhatikan bahwa situs web lain mungkin dapat berpaut ke sebuah file gambar secara langsung, dan file-file gambar seperti itu mungkin terdapat dalam daftar ini meskipun masih digunakan oleh situs web lain.",
"booksources" => "Sumber Buku",
'categoriespagetext' => 'Kategori-kategori berikut ada dalam wiki.',
'data'  => 'Data',
"booksourcetext" => "Di bawah ini adalah daftar pautan ke situs lain yang menjual buku baru dan bekas, dan mungkin juga mempunyai informasi lebih lanjut mengenai buku yang sedang Anda cari. {{SITENAME}} tidak berkepentingan dengan situs-situs web di atas, dan daftar ini seharusnya tidak dianggap sebagai sebuah dukungan.",
"isbn" => "ISBN",
"rfcurl" =>  "http://www.faqs.org/rfcs/rfc$1.html",
'pubmedurl' =>  'http://www.ncbi.nlm.nih.gov/entrez/query.fcgi?cmd=Retrieve&db=pubmed&dopt=Abstract&list_uids=$1',
"alphaindexline" => "$1 ke $2",
"version" => "Versi",
'log' => 'Catatan-Catatan',
'alllogstext' => 'Tampilan gabungan catatan pengunggahan, penghapusan, perlindungan, pemblokiran, dan sysop. Anda dapat melakukan pembatasan tampilan dengan memilih jenis catatan, nama pengguna, atau nama halaman.',

# Special:Allpages
'nextpage'          => 'Halaman berikutnya ($1)',
'allarticles'       => 'Semua artikel',
'allpagesprev'      => 'Sebelumnya',
'allpagesnext'      => 'Selanjutnya',
'allpagessubmit'    => 'Go',

# Email this user
#
"mailnologin" => "Tidak ada alamat e-mail",
"mailnologintext" => "Anda harus [[Special:Userlogin|login]] dan mempunyai alamat e-mail yang sah di dalam [[Special:Preferences|konfigurasi]] untuk mengirimkan e-mail kepada pengguna lain.",

"emailuser" => "e-mail pengguna ini",
"emailpage" => "e-mail pengguna",
"emailpagetext" => "Jika pengguna ini memasukkan alamat e-mail yang sah dalam konfigurasinya, formulir dibawah ini akan mengirimkan sebuah e-mail. Alamat e-mail yg terdapat pada konfigurasi Anda akan muncul sebagai alamat \"From\" dalam e-mail tersebut, sehingga penerima dapat membalas e-mail tersebut.",

"usermailererror" => "Kesalahan objek mail: ",
"defemailsubject" => "e-mail {{SITENAME}}",
"noemailtitle" => "Tidak Ada Alamat e-mail",

"noemailtext" => "Pengguna ini tidak memasukkan alamat e-mail yang sah, atau memilih untuk tidak menerima e-mail dari pengguna yang lain.",

"emailfrom" => "Dari",
"emailto" => "Ke",
"emailsubject" => "Perihal",
"emailmessage" => "Pesan",
"emailsend" => "Kirimkan",
"emailsent" => "e-mail terkirim",
"emailsenttext" => "e-mail Anda telah dikirimkan.",

# Watchlist
#
"watchlist" => "Daftar Pemantauan",
"watchlistsub" => "(untuk pengguna \"$1\")",
"nowatchlist" => "Daftar pemantauan Anda kosong.",
"watchnologin" => "Belum login",
"watchnologintext" => "Anda harus [[Special:Userlogin|login]] untuk mengubah daftar pemantauan.",
"addedwatch" => "Telah Ditambahkan ke Daftar Pemantauan",
"addedwatchtext" => "Halaman \"$1\" telah ditambahkan ke [[Special:Watchlist|daftar pemantauan]]. Pada masa yang akan datang, semua perubahan pada halaman tersebut berikut halaman pembicaraannya akan didaftar di sini, dan halaman tersebut akan <b>dicetak tebal</b> dalam [[Special:Recentchanges|daftar perubahan terbaru]] supaya lebih mudah dilihat.\n\n<p>Apabila nanti Anda ingin menghapus halaman dari daftar pemantauan, klik \"Berhenti memantau\" pada batang sebelah.",
"removedwatch" => "Telah Dihapus dari Daftar Pemantauan",
"removedwatchtext" => "Halaman \"$1\" telah dihapus dari daftar pemantauan.",
'watch' => 'Pantau',
"watchthispage" => "Tambahkan ke daftar pemantauan",
'unwatch' => 'Berhenti memantau',
"unwatchthispage" => "Berhenti memantau",
"notanarticle" => "Bukan sebuah artikel",
"watchnochange" => "Tidak ada item-item yang Anda pantau telah berubah dalam jangka waktu yang ditampilkan.",
"watchdetails" => "($1 halaman dipantau [tidak termasuk halaman pembicaraan]; $2 halaman berubah sejak cutoff; $3... [$4 lihat dan sunting daftar lengkap].)",
"watchmethod-recent"=> "periksa daftar perubahan terbaru terhadap halaman yang dipantau",
"watchmethod-list" => "periksa halaman yang dipantau terhadap perubahan terbaru",
"removechecked" => "Hapus item yang diberi tanda cek dari daftar pemantauan",
"watchlistcontains" => "Daftar pemantauan Anda berisi $1 halaman.",
"watcheditlist" => "Berikut ini adalah daftar halaman-halaman yang Anda pantau. Untuk menghapus halaman dari daftar pemantauan Anda, berikan tanda cek pada kotak cek di sebelah judul halaman yang ingin Anda hapus, lalu klik tombol 'hapus yang dicek' yang terletak di bagian bawah layar.",
"removingchecked" => "Menghapus item-item yang diminta dari daftar pemantauan Anda...",
"couldntremove" => "Tidak dapat menghapus item '$1'...",
"iteminvalidname" => "Ada masalah dengan item '$1' (namanya tidak sah)...",
"wlnote" => "Di bawah ini adalah daftar $1 perubahan terakhir dalam <b>$2</b> jam terakhir.",
"wlshowlast" => "Tampilkan $1 jam $2 hari $3 terakhir",
"wlsaved" => "Ini adalah versi tersimpan dari daftar pemantauan Anda.",

# Delete/protect/revert
#
"deletepage" => "Hapus halaman",
"confirm" => "Konfirmasikan",
"excontent" => "isi sebelumnya: '$1'",
"exbeforeblank" => "isi sebelum dikosongkan: '$1'",
"exblank" => "halaman kosong",
"confirmdelete" => "Konfirmasi Penghapusan",
"deletesub" => "(Menghapus \"$1\")",
"historywarning" => "Peringatan: Halaman yang ingin Anda hapus mempunyai sejarah:",
"confirmdeletetext" => "Anda akan menghapus halaman atau gambar ini secara permanen berikut semua sejarahnya dari basis data.  Pastikan bahwa Anda memang ingin berbuat demikian, mengetahui segala akibatnya, dan apa yang Anda lakukan ini adalah sejalan dengan [[Project:Kebijaksanaan|kebijaksanaan Wikipedia]].",
"actioncomplete" => "Proses selesai",
"deletedtext" => "\"$1\" telah dihapus. Lihat $2 untuk catatan terkini halaman yang telah dihapus.",
"deletedarticle" => "menghapus \"$1\"",
"dellogpage" => "Catatan_Penghapusan",
"dellogpagetext" => "Di bawah ini adalah daftar terkini halaman yang telah dihapus. Semua waktu yang ditunjukkan adalah waktu server (UTC).",
"deletionlog" => "catatan penghapusan",
"reverted" => "Dikembalikan ke revisi sebelumnya",
"deletecomment" => "Alasan penghapusan",
"imagereverted" => "Berhasil mengembalikan ke revisi sebelumnya",
"rollback" => "Rollback penyuntingan",
'rollback_short' => 'Rollback',
"rollbacklink" => "rollback",
"rollbackfailed" => "Gagal Melakukan Rollback",
"cantrollback" => "Tidak dapat mengembalikan penyuntingan; pengguna terakhir adalah satu-satunya penulis artikel ini.",
"alreadyrolled" => "Tidak dapat melakukan rollback terhadap penyuntingan terakhir dari [[$1]] oleh [[Pengguna:$2|$2]] ([[Bicara_pengguna:$2|Pembicaraan); orang lain telah mengedit atau menlakukan rollback terhadap artikel tersebut.\n\nEdit terakhir oleh [[Pengguna:$3|$3]] ([[Bicara_pengguna:$3|Pembicaraan]]).",
"editcomment" => "Komentar penyuntingan adalah: \"<i>$1</i>\".",
"revertpage" => "Dikembalikan oleh $1",
'sessionfailure' => 'Kelihatannya ada masalah dengan sesi login Anda; tindakan ini dibatalkan sebagai pencegahan terhadap pembajakan sesi. Silakan tekan "back", lalu reload-lah halaman yang sebelumnya Anda kunjungi, dan cobalah tindakan tersebut sekali lagi.',
"protectlogpage" => "Catatan_Perlindungan",
"protectlogtext" => "Di bawah ini adalah daftar catatan perlindungan/penghilangan perlindungan halaman. Lihat [[Project:Halaman_dilindungi]] untuk informasi lebih lanjut.",
"protectedarticle" => "melindungi [[$1]]",
"unprotectedarticle" => "menghilangkan perlindungan [[$1]]",
"protectsub" =>"(Melindungi \"$1\")",
"confirmprotecttext" => "Apakah Anda benar-benar ingin melindungi halaman ini?",
"confirmprotect" => "Konfirmasi Perlindungan",
'protectmoveonly' => 'Lindungi dari perpindahan saja',
"protectcomment" => "Alasan perlindungan",
"unprotectsub" =>"(Menghilangkan perlindungan terhadap \"$1\")",
"confirmunprotecttext" => "Apakah Anda benar-benar ingin menghilangkan perlindungan terhadap halaman ini?",
"confirmunprotect" => "Konfirmasi Penghilangan Perlindungan",
"unprotectcomment" => "Alasan penghilangan perlindungan",

# Undelete
"undelete" => "Kembalikan Halaman yang Telah Dihapus",
"undeletepage" => "Lihat dan Kembalikan Halaman yang Telah Dihapus",
"undeletepagetext" => "Halaman-halaman berikut ini telah dihapus tapi masih ada di dalam arsip dan dapat dikembalikan. Arsip tersebut mungkin akan dibersihkan secara berkala.",
"undeletearticle" => "Kembalikan halaman yang telah dihapus",
"undeleterevisions" => "$1 revisi diarsipkan",
"undeletehistory" => "Jika Anda mengembalikan halaman tersebut, semua revisi akan dikembalikan ke dalam sejarah. Jika sebuah halaman baru dengan nama yang sama telah dibuat sejak penghapusan, revisi yang telah dikembalikan akan kelihatan dalam sejarah dahulu, dan revisi terkini halaman tersebut tidak akan ditimpa secara otomatis.",
"undeleterevision" => "Revisi yang telah dihapus per $1",
"undeletebtn" => "Kembalikan!",
"undeletedarticle" => "\"$1\" telah dikembalikan",
'undeletedrevisions' => "$1 revisi telah dikembalikan",
"undeletedtext" => "Halaman [[$1]] berhasil dikembalikan. Lihat [[Project:Catatan/Penghapusan]] untuk catatan terkini penghapusan dan pengembalian halaman.",

# Contributions
#
"contributions" => "Sumbangan Pengguna",
"mycontris" => "Sumbangan saya",
"contribsub" => "Untuk $1",
"nocontribs" => "Tidak ada perubahan yang cocok dengan kriteria-kriteria ini.",
"ucnote" => "Di bawah ini adalah <b>$1</b> perubahan terakhir pengguna dalam <b>$2</b> hari terakhir.",
"uclinks" => "Tampilkan $1 perubahan terbaru; tampilkan $2 hari terakhir",
"uctop" => " (atas)" ,
'newbies' => 'pengguna baru',

# What links here
#
"whatlinkshere" => "Pautan ke halaman ini",
"notargettitle" => "Tidak Ada Sasaran",
"notargettext" => "Anda tidak menentukan halaman atau pengguna tujuan fungsi ini.",
"linklistsub" => "(Daftar pautan)",
"linkshere" => "Halaman-halaman berikut ini berpaut ke sini:",
"nolinkshere" => "Tidak ada halaman yang berpaut ke sini.",
"isredirect" => "halaman peralihan",

# Block/unblock IP
#
"blockip" => "Blokir IP",
"blockiptext" => "Gunakan formulir di bawah untuk memblokir kemampuan menulis sebuah alamat IP atau pengguna tertentu. Ini perlu dilakukan untuk mencegah vandalisme, dan sejalan dengan [[Project:Kebijaksanaan|kebijaksanaan Wikipedia]]. Masukkan alasan Anda di bawah (contohnya mengambil halaman tertentu yang telah dirusak).",
"ipaddress" => "Alamat IP atau pengguna",
"ipbexpiry" => "Kadaluwarsa",
"ipbreason" => "Alasan",
"ipbsubmit" => "Kirimkan",
"badipaddress" => "Format alamat IP atau nama pengguna salah.",
"blockipsuccesssub" => "Pemblokiran sukses",
"blockipsuccesstext" => "Alamat IP atau pengguna \"$1\" telah diblokir. <br />Lihat [[Special:Ipblocklist|Daftar IP dan pengguna diblokir]] untuk melihat kembali pemblokiran.",
"unblockip" => "Hilangkan blokir terhadap alamat IP atau pengguna",
"unblockiptext" => "Gunakan formulir di bawah untuk mengembalikan kemampuan menulis sebuah alamat IP atau pengguna yang sebelumnya telah diblokir.",
"ipusubmit" => "Hilangkan blokir terhadap alamat ini",
"ipusuccess" => "Blokir terhadap alamat IP atau pengguna \"$1\" telah dihilangkan",
"ipblocklist" => "Daftar Alamat IP dan Pengguna yang Diblokir",
"blocklistline" => "$1, $2 memblokir $3 ($4)",
"blocklink" => "blokir",
"unblocklink" => "hilangkan blokir",
"contribslink" => "sumbangan",
"autoblocker" => "Diblokir secara otomatis karena Anda berbagi alamat IP dengan \"$1\". Alasan \"$2\".",
"blocklogpage" => "Catatan_Pemblokiran",
"blocklogentry" => 'memblokir "$1" dengan waktu kadaluwarsa pada $2',
"blocklogtext" => "Ini adalah catatan tindakan pemblokiran dan penghilangan blokir terhadap pengguna. Alamat IP yang diblokir secara otomatis tidak terdapat di dalam daftar ini. Lihat [[Special:Ipblocklist|daftar alamat IP yang diblokir]] untuk daftar blokir terkini yang efektif.",
"unblocklogentry" => 'menghilangkan blokir "$1"',
"range_block_disabled" => "Kemampuan sysop dalam membuat blokir blok IP dimatikan.",
"ipb_expiry_invalid" => "Waktu kadaluwarsa tidak sah.",
"ip_range_invalid" => "Blok IP tidak sah.\n",
"proxyblocker" => "Pemblokir proxy",
"proxyblockreason" => "Alamat IP Anda telah diblokir karena alamat IP Anda adalah proxy terbuka. Silakan hubungi penyedia jasa internet Anda atau dukungan teknis dan beritahukan mereka masalah keamanan serius ini.",
"proxyblocksuccess" => "Selesai.\n",
'sorbs' => 'SORBS DNSBL',
'sorbsreason' => 'Alamat IP anda terdaftar sebagai proxy terbuka di [http://www.sorbs.net SORBS] DNSBL.',
'sorbs_create_account_reason' => 'Alamat IP anda terdaftar sebagai proxy terbuka di [http://www.sorbs.net SORBS] DNSBL. Anda tidak dapat membuat akun.',

# Make sysop
"makesysoptitle" => "Buat Seorang Pengguna Menjadi Sysop",
"makesysoptext" => "Formulir ini digunakan oleh para birokrat untuk menjadikan pengguna biasa menjadi seorang administrator. Ketikkan nama pengguna yang dimaksud dalam kotak dan tekan tombol untuk menjadikan pengguna tersebut seorang administrator",
"makesysopname" => "Nama pengguna:",
"makesysopsubmit" => "Jadikan sysop",
"makesysopok" => "<b>Pengguna \"$1\" sekarang adalah seorang sysop</b>",
"makesysopfail" => "<b>Pengguna \"$1\" tidak dapat dijadikan sysop. (Apakah Anda mengetikkan namanya dengan benar?)</b>",
"setbureaucratflag" => "Atur flag birokrat",
"bureaucratlog" => "Catatan_Birokrat",
'rightslogtext' => 'Ini adalah catatan perubahan terhadap hak-hak pengguna.',
"bureaucratlogentry" => "Hak-hak pengguna \"$1\" diatur \"$2\"",
"rights" => "Hak-hak:",
"set_user_rights" => "Atur hak-hak pengguna",
"user_rights_set" => "<b>Hak-hak pengguna \"$1\" diperbarui</b>",
"set_rights_fail" => "<b>Hak-hak pengguna \"$1\" tidak dapat diatur. (Apakah Anda mengetikkan namanya dengan benar?)</b>",
"makesysop" => "Buat Seorang Pengguna Menjadi Sysop",

# Developer tools
#
"lockdb" => "Kunci Basis Data",
"unlockdb" => "Buka Kunci Basis Data",
"lockdbtext" => "Mengunci basis data akan menghentikan kemampuan semua pengguna dalam menyunting halaman, mengubah konfigurasi pengguna, menyunting daftar pemantauan mereka, dan hal-hal lain yang memerlukan perubahan terhadap basis data. Pastikan bahwa ini adalah yang ingin Anda lakukan, dan bahwa Anda akan membuka kunci basis data setelah pemeliharaan selesai.",
"unlockdbtext" => "Membuka kunci basis data akan mengembalikan kemampuan semua pengguna dalam menyunting halaman, mengubah konfigurasi pengguna, menyunting daftar pemantauan mereka, dan hal-hal lain yang memerlukan perubahan terhadap basis data.  Pastikan bahwa ini adalah yang ingin Anda lakukan.",
"lockconfirm" => "Ya, saya memang ingin mengunci basis data.",
"unlockconfirm" => "Ya, saya memang ingin membuka kunci basis data.",
"lockbtn" => "Kunci basis data",
"unlockbtn" => "Buka kunci basis data",
"locknoconfirm" => "Anda tidak memberikan tanda cek pada kotak konfirmasi.",
"lockdbsuccesssub" => "Penguncian basis data berhasil",
"unlockdbsuccesssub" => "Pembukaan kunci basis data berhasil",
"lockdbsuccesstext" => "Basis data telah dikunci. <br />Pastikan Anda membuka kuncinya setelah pemeliharaan selesai.",
"unlockdbsuccesstext" => "Kunci basis data telah dibuka.",

# Validation
'val_clear_old' => 'Hapus data pengesahan saya yang lainnya untuk $1',
'val_merge_old' => 'Gunakan penilaian saya sebelumnya jika saya memilih \'Abstain\'',
'val_form_note' => '<b>Petunjuk:</b> Menggabungkan data Anda berarti bahwa untuk revisi pengesahan yang Anda pilih, semua pilihan Anda yang <i>Abstain</i> akan diganti dengan pilihan dan komentar dari revisi yang paling baru yang mana untuk pilihan tersebut Anda tidak abstain. Sebagai contoh, jika Anda ingin mengganti pilihan tunggal dengan revisi yang lebih baru, tetapi tidak ingin mengganti pilihan Anda yang lainnya untuk revisi artikel ini, pilih pilihan mana yang ingin anda ubah, dan penggabungan akan mengisi pilihan lainnya dengan pilihan Anda sebelumnya.',
'val_noop' => 'Abstain',
'val_percent' => '<b>$1%</b><br />($2 dari $3 poin<br />oleh $4 pengguna)',
'val_percent_single' => '<b>$1%</b><br />($2 dari $3 poin<br />oleh seorang pengguna)',
'val_total' => 'Total',
'val_version' => 'Versi',
'val_tab' => 'Pengesahan',
'val_this_is_current_version' => 'ini adalah versi terbaru',
'val_version_of' => "Version of $1",
'val_table_header' => "<tr><th>Kelas</th>$1<th colspan=4>Pendapat</th>$1<th>Komentar</th></tr>\n",
'val_stat_link_text' => 'Statistik pengesahan artikel ini',
'val_view_version' => 'Lihat versi ini',
'val_validate_version' => 'Sahkan versi ini',
'val_user_validations' => 'Penggung ini telah mengesahkan $1 halaman.',
'val_no_anon_validation' => 'Anda harus login untuk dapat mengesahkan artikel.',
'val_validate_article_namespace_only' => 'Hanya artikel saja yang dapat disahkan. Halaman ini <i>tidak</i> berada dalam namespace artikel.',
'val_validated' => 'Pengesahan selesai.',
'val_article_lists' => 'Daftar artikel yang telah disahkan',
'val_page_validation_statistics' => 'Statistik pengesahan halaman untuk $1',

# Move page
#
"movepage" => "Pindahkan Halaman",
"movepagetext" => "Formulir di bawah ini digunakan untuk mengubah nama suatu halaman dan memindahkan semua data sejarah ke nama baru. Judul yang lama akan menjadi halaman peralihan menuju judul yang baru. Pautan kepada judul lama tidak akan berubah. Pastikan untuk memeriksa terhadap peralihan halaman yang rusak atau berganda setelah pemindahan. Anda bertanggung jawab untuk memastikan bahwa pautan terus menyambung ke halaman yang seharusnya.\n\nPerhatikan bahwa halaman '''tidak''' akan dipindah apabila telah ada halaman di pada judul yang baru, kecuali bila halaman tersebut kosong atau merupakan halaman peralihan dan tidak mempunyai sejarah penyuntingan. Ini berarti Anda dapat mengubah nama halaman kembali seperti semula apabila Anda membuat kesalahan, dan Anda tidak dapat menimpa halaman yang telah ada.\n\n<b>PERINGATAN!</b> Ini dapat mengakibatkan perubahan yang tak terduga dan drastis  bagi halaman yang populer. Pastikan Anda mengerti konsekuensi dari perbuatan ini sebelum melanjutkan.",
"movepagetalktext" => "Halaman pembicaraan yang berkaitan, jika ada, juga akan dipindahkan secara otomatis '''kecuali apabila:'''\n*Anda memindahkan halaman melintasi namespace,\n*Sebuah halaman pembicaraan yang tidak kosong telah ada di bawah judul baru, atau\n*Anda tidak memberi tanda cek pada kotak cek di bawah ini.\n\nDalam kasus tersebut, apabila diinginkan, Anda dapat memindahkan atau menggabungkan halaman secara manual.",
"movearticle" => "Pindahkan halaman",
"movenologin" => "Belum login",
"movenologintext" => "Anda harus menjadi pengguna terdaftar dan telah [[Special:Userlogin|login]] untuk memindahkan halaman.",
"newtitle" => "Ke judul baru",
"movepagebtn" => "Pindahkan halaman",
"pagemovedsub" => "Pemindahan berhasil",
"pagemovedtext" => "Halaman \"[[$1]]\" dipindahkan ke \"[[$2]]\".",
"articleexists" => "Halaman dengan nama tersebut telah ada atau nama yang dipilih tidak sah. Silakan pilih nama lain.",
"talkexists" => "Halaman tersebut berhasil dipindahkan, tetapi halaman pembicaraan dari halaman tersebut tidak dapat dipindahkan karena telah ada halaman pembicaraan pada judul yang baru. Silakan gabungkan halaman-halaman pembicaraan tersebut secara manual.",
"movedto" => "dipindahkan ke",
"movetalk" => "Pindahkan halaman \"pembicaraan\" juga, jika mungkin.",
"talkpagemoved" => "Halaman pembicaraan yang berkaitan juga ikut dipindahkan.",
"talkpagenotmoved" => "Halaman pembicaraan yang berkaitan <strong>tidak</strong> ikut dipindahkan.",
"1movedto2" => "$1 dipindahkan ke $2",
'1movedto2_redir' => '$1 dipindahkan ke $2 melalui peralihan',

# Export

"export" => "Ekspor Halaman",
'exporttext'    => 'Anda dapat mengekspor teks dan sejarah penyuntingan suatu halaman tertentu atau sejumlah halaman terbungkus dalam XML tertentu. Di masa depan, hasil ekspor ini dapat diimpor di wiki lainnya yang menggunakan perangkat lunak MediaWiki, meskipun fitur impor belum tersedia dalam versi ini.\n\nUntuk mengekspor halaman-halaman artikel, masukkan judul-judul dalam kotak teks di bawah ini, satu judul per baris, dan pilih apakah anda ingin mengekspor versi sekarang dengan versi sebelumnya, dengan catatan sejarah halaman, atau hanya versi sekarang dengan catatan penyuntingan terakhir.\n\nJika Anda hanya ingin mengimpor versi sekarang, Anda juga dapat melakukan hal ini dengan lebih cepat dengan cara menggunakan pautan khusus, sebagai contoh: [[{{ns:Special}}:Export/Train]] untuk mengekspor artikel [[Train]].',
"exportcuronly" => "Hanya ekspor revisi sekarang, bukan seluruh sejarah",

# Namespace 8 related

"allmessages" => "Semua Pesan Sistem",
'allmessagesname' => 'Nama',
'allmessagesdefault' => 'Teks baku',
'allmessagescurrent' => 'Teks sekarang',
'allmessagestext' => 'Ini adalah daftar semua pesan sistem yang tersedia dalam namespace MediaWiki:',
'allmessagesnotsupportedUI' => 'Bahasa antarmuka Anda saat ini, <b>$1</b> tidak didukung oleh Istimewa:AllMessages di situs ini.',
'allmessagesnotsupportedDB' => 'Istimewa:AllMessages tidak didukung karena wgUseDatabaseMessages dimatikan.',

# Thumbnails

"thumbnail-more" => "Perbesar",
"missingimage" => "<b>Gambar hilang</b><br /><i>$1</i>\n",
'filemissing' => 'File hilang',

# Special:Import
"import" => "Impor Halaman",
"importtext" => "Silakan ekspor file dari wiki asal menggunakan utilitas Istimewa:Export, simpan ke disk, dan unggahkan ke sini.",
"importfailed" => "Impor gagal: $1",
"importnotext" => "Kosong atau tidak ada teks",
"importsuccess" => "Impor sukses!",
"importhistoryconflict" => "Terjadi konflik revisi sejarah (mungkin pernah mengimpor halaman ini sebelumnya)",

# Keyboard access keys for power users
'accesskey-search' => 'f',
'accesskey-minoredit' => 'i',
'accesskey-save' => 's',
'accesskey-preview' => 'p',
'accesskey-compareselectedversions' => 'v',

# tooltip help for the main actions
'tooltip-watch' => 'Tambahkan halaman ini ke daftar pemantauan Anda [alt-w]',
'tooltip-search' => 'Cari dalam wiki ini [alt-f]',
'tooltip-minoredit' => 'Tandai ini sebagai suntingan kecil [alt-i]',
'tooltip-save' => 'Simpan perubahan Anda [alt-s]',
'tooltip-preview' => 'Pratilik perubahan Anda -- mohon gunakan ini sebelum menyimpan! [alt-p]',
'tooltip-compareselectedversions' => 'Lihat perbedaan antara dua versi halaman yang dipilih. [alt-v]',

# stylesheets

#'Monobook.css' => '/* edit this file to customize the monobook skin for the entire site */',
#'Monobook.js' => '/* edit this file to change js things in the monobook skin */',

# Metadata
"nodublincore" => "Metadata Dublin Core RDF dimatikan di server ini.",
"nocreativecommons" => "Metadata Creative Commons RDF dimatikan di server ini.",
"notacceptable" => "Server wiki tidak dapat menyediakan data dalam format yang dapat dibaca oleh client Anda.",

# Attribution

"anonymous" => "Pengguna(-pengguna) anonim {{SITENAME}}",
"siteuser" => "Pengguna {{SITENAME}} $1",
"lastmodifiedby" => "Halaman ini terakhir kali diubah $1 oleh $2.",
"and" => "dan",
"othercontribs" => "Didasarkan pada karya $1.",
'others' => 'lainnya',
"siteusers" => "Pengguna(-pengguna) {{SITENAME}} $1",
'creditspage' => 'Penghargaan halaman',
'nocredits' => 'Tidak ada informasi penghargaan yang tersedia untuk halaman ini.',

# Spam protection

'spamprotectiontitle' => 'Filter Pencegah Spam',
'spamprotectiontext' => 'Halaman yang ingin Anda simpan diblokir oleh filter spam. Ini mungkin disebabkan oleh pautan ke situs luar.\n\nAnda dapat memeriksa regular expression berikut terhadap pola-pola yang diblokir:',
'spamprotectionmatch' => 'Teks berikut ini memancing filter spam kami: $1',
'subcategorycount' => "Ada $1 subkategori dalam kategori ini.",
'subcategorycount1' => "Ada $1 subkategori dalam kategori ini.",
'categoryarticlecount' => "Ada $1 artikel dalam kategori ini.",
'categoryarticlecount1' => "Ada $1 artikel dalam kategori ini.",
'usenewcategorypage' => "1\n\nUbah karakter pertama menjadi \"0\" untuk mematikan tata letak halaman kategori yang baru.",
'listingcontinuesabbrev' => " lanjut",

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
'mw_math_source' => "Biarkan sebagai TeX (untuk browser teks)",
'mw_math_modern' => "Disarankan untuk browser modern",
'mw_math_mathml' => "MathML jika mungkin (percobaan)",

# Patrolling
'markaspatrolleddiff'   => "Tandai telah diperiksa",
'markaspatrolledlink'   => "[$1]",
'markaspatrolledtext'   => "Tandai artikel ini telah diperiksa",
'markedaspatrolled'     => "Telah ditandai telah diperiksa",
'markedaspatrolledtext' => "Revisi yang dipilih telah ditandai sebagai telah diperiksa",
'rcpatroldisabled'      => "Recent Changes Patrol dimatikan",
'rcpatroldisabledtext'  => "Fitur Recent Changes Patrol sedang dimatikan.",

# Monobook.js: tooltips and access keys for monobook
'Monobook.js' => '/* tooltips and access keys */
ta = new Object();
ta[\'pt-userpage\'] = new Array(\'.\',\'Halaman pengguna saya\');
ta[\'pt-anonuserpage\'] = new Array(\'.\',\'Halaman pengguna IP Anda\');
ta[\'pt-mytalk\'] = new Array(\'n\',\'Halaman pembicaraan saya\');
ta[\'pt-anontalk\'] = new Array(\'n\',\'Diskusi tentang suntingan dari alamat IP ini\');
ta[\'pt-preferences\'] = new Array(\'\',\'Konfigurasi saya\');
ta[\'pt-watchlist\'] = new Array(\'l\',\'Daftar halaman yang Anda pantau.\');
ta[\'pt-mycontris\'] = new Array(\'y\',\'Daftar sumbangan saya\');
ta[\'pt-login\'] = new Array(\'o\',\'Anda disarankan untuk login, meskipun hal itu tidak diwajibkan.\');
ta[\'pt-anonlogin\'] = new Array(\'o\',\'Anda disarankan untuk login, meskipun hal itu tidak diwajibkan.\');
ta[\'pt-logout\'] = new Array(\'o\',\'Log out\');
ta[\'ca-talk\'] = new Array(\'t\',\'Diskusi tentang artikel\');
ta[\'ca-edit\'] = new Array(\'e\',\'Anda dapat menyunting halaman ini. Silakan gunakan tombol pratilik sebelum menyimpan.\');
ta[\'ca-addsection\'] = new Array(\'+\',\'Tambahkan komentar ke diskusi ini.\');
ta[\'ca-viewsource\'] = new Array(\'e\',\'Halaman ini dilindungi. Anda hanya dapat melihat sumbernya.\');
ta[\'ca-history\'] = new Array(\'h\',\'Versi-versi sebelumnya dari halaman ini.\');
ta[\'ca-protect\'] = new Array(\'=\',\'Lindungi halaman ini\');
ta[\'ca-delete\'] = new Array(\'d\',\'Hapus halaman ini\');
ta[\'ca-undelete\'] = new Array(\'d\',\'Kembalikan suntingan ke halaman ini sebelum halaman ini dihapus\');
ta[\'ca-move\'] = new Array(\'m\',\'Pindahkan halaman ini\');
ta[\'ca-watch\'] = new Array(\'w\',\'Tambahkan halaman ini ke daftar pemantauan Anda\');
ta[\'ca-unwatch\'] = new Array(\'w\',\'Hapus halaman ini dari daftar pemantauan Anda\');
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
ta[\'t-emailuser\'] = new Array(\'\',\'Kirimkan e-mail kepada pengguna ini\');
ta[\'t-upload\'] = new Array(\'u\',\'Unggahkan gambar atau file media\');
ta[\'t-specialpages\'] = new Array(\'q\',\'Daftar semua halaman istimewa\');
ta[\'ca-nstab-main\'] = new Array(\'c\',\'Lihat halaman isi (artikel)\');
ta[\'ca-nstab-user\'] = new Array(\'c\',\'Lihat halaman pengguna\');
ta[\'ca-nstab-media\'] = new Array(\'c\',\'Lihat halaman media\');
ta[\'ca-nstab-special\'] = new Array(\'\',\'Ini adalah halaman istimewa yang tidak dapat disunting.\');
ta[\'ca-nstab-wp\'] = new Array(\'a\',\'Lihat halaman proyek\');
ta[\'ca-nstab-image\'] = new Array(\'c\',\'Lihat halaman gambar\');
ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'Lihat pesan sistem\');
ta[\'ca-nstab-template\'] = new Array(\'c\',\'Lihat templat\');
ta[\'ca-nstab-help\'] = new Array(\'c\',\'Lihat halaman bantuan\');
ta[\'ca-nstab-category\'] = new Array(\'c\',\'Lihat halaman kategori\');
',

# image deletion
'deletedrevision' => 'Menghapus revisi tua $1.',

# browsing diffs
'previousdiff' => '← Ke diff sebelumnya',
'nextdiff' => 'Ke diff selanjutnya →',

'imagemaxsize' => 'Batasi gambar dalam halaman deskripsi gambar sampai: ',
'showbigimage' => 'Download versi resolusi tinggi ($1x$2, $3 KB)',

'newimages' => 'Galeri-Gambar Baru',
'noimages'  => 'Tidak ada yang dilihat.',

# labels for User: and Title: on Special:Log pages
'specialloguserlabel' => 'Pengguna: ',
'speciallogtitlelabel' => 'Judul: ',

);

class LanguageId extends LanguageUtf8 {

	function getBookstoreList () {
		global $wgBookstoreListId;
		return $wgBookstoreListId;
	}

	function getNamespaces() {
		global $wgNamespaceNamesId;
		return $wgNamespaceNamesId;
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesId, $wgNamespaceAlternatesId;

		foreach ( $wgNamespaceNamesId as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		foreach ( $wgNamespaceAlternatesId as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}

		# For backwards compatibility
		global $wgNamespaceNamesEn;
		foreach ( $wgNamespaceNamesEn as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsId;
		return $wgQuickbarSettingsId;
	}

	function getSkinNames() {
		global $wgSkinNamesId;
		return $wgSkinNamesId;
	}

	function getDateFormats() {
		global $wgDateFormatsId;
		return $wgDateFormatsId;
	}

	function getMessage( $key ) {
		global $wgAllMessagesId;
		if( isset( $wgAllMessagesId[$key] ) ) {
			return $wgAllMessagesId[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function formatNum( $number ) {
		global $wgTranslateNumerals;
		return $wgTranslateNumerals ? strtr($number, '.,', ',.' ) : $number;
	}

}

?>
