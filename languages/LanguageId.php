<?php 

# This localisation is based on a file kindly donated by the folks at MIMOS 
# http://www.asiaosc.org/enwiki/page/Knowledgebase_Home.html 

# NOTE: To turn off "Current Events" in the sidebar, 
# set "currentevents" => "-" 

# The names of the namespaces can be set here, but the numbers 
# are magical, so don't change or move them!  The Namespace class 
# encapsulates some of the magic-ness. 
# 

require_once( "LanguageUtf8.php" );

if($wgMetaNamespace === FALSE)
	$wgMetaNamespace = str_replace( " ", "_", $wgSitename );

/* private */ $wgNamespaceNamesId = array(
	NS_MEDIA            => "Media",
	NS_SPECIAL	        => "Istimewa",
	NS_MAIN             => "",
	NS_TALK             => "Bicara",
	NS_USER             => "Pengguna",
	NS_USER_TALK	    => "Bicara_Pengguna",
	NS_WIKIPEDIA	    => $wgMetaNamespace, 
	NS_WIKIPEDIA_TALK   => "Pembicaraan_" . $wgMetaNamespace, 
	NS_IMAGE            => "Gambar",
	NS_IMAGE_TALK       => "Gambar_Pembicaraan",
	NS_MEDIAWIKI        => "MediaWiki",
	NS_MEDIAWIKI_TALK   => "MediaWiki_Pembicaraan",
	NS_TEMPLATE	        => "Templat",
	NS_TEMPLATE_TALK    => "Templat_Pembicaraan",
	NS_HELP	            => "Bantuan",
	NS_HELP_TALK        => "Bantuan_Pembicaraan",
	NS_CATEGORY	        => "Kategori",
	NS_CATEGORY_TALK    => "Kategori_Pembicaraan" 
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsId = array(
	"Tidak Ada", "Tetap sebelah kiri", "Tetap sebelah kanan", "Mengambang sebelah kiri" 
);

/* private */ $wgSkinNamesId = array(
	'standard' => "Standar",
	'nostalgia' => "Nostalgia",
	'cologneblue' => "Cologne Blue",
	'davinci' => "DaVinci",
	'mono' => "Mono",
	'monobook' => "MonoBook" 
);

/* private */ $wgMathNamesId = array(
	MW_MATH_PNG => "Selalu buat PNG",
	MW_MATH_SIMPLE => "HTML jika sangat sederhana atau PNG",
	MW_MATH_HTML => "HTML jika mungkin atau PNG",
	MW_MATH_SOURCE => "Biarkan sebagai TeX (untuk browser teks)",
	MW_MATH_MODERN => "Disarankan untuk browser modern",
	MW_MATH_MATHML => "MathML jika mungkin (percobaan)",
);

/* private */ $wgDefaultUserOptionsId = array(
	"quickbar" => 1, "underline" => 1, "hover" => 1, 
	"cols" => 80, "rows" => 25, "searchlimit" => 20, 
	"contextlines" => 5, "contextchars" => 50, 
	"skin" => $wgDefaultSkin, "math" => 1, "rcdays" => 7, "rclimit" => 50, 
	"highlightbroken" => 1, "stubthreshold" => 0, 
	"previewontop" => 1, "editsection"=>1,"editsectiononrightclick"=>0, "showtoc"=>1, 
	"showtoolbar" =>1, 
	"date" => 0 
);

/* private */ $wgDateFormatsId = array(
	"Tiada pilihan",
	"Januari 15, 2001",
	"15 Januari 2001",
	"2001 Januari 15",
	"2001-01-15" 
);

/* private */ $wgUserTogglesId = array(
	"hover"		      => "Tampilkan hoverbox di atas pautan wiki", #"Show hoverbox over links",
	"underline" => "Garisbawahi pautan", #"Underline links",
	"highlightbroken" => "Beri tanda pautan yang berpaut ke topik kosong <a href=\"\" class=\"new\">seperti ini</a> (alternatif: seperti ini<a href=\"\" class=\"internal\">?</a>)",
	"justify"     => "Ratakan paragraf", #"Justify paragraphs",
	"hideminor" => "Sembunyikan suntingan kecil dalam perubahan terkini", #"Hide minor edits in recent changes",
	"usenewrc" => "Tampilkan perubahan terkini dalam tampilan baru (tidak untuk semua browser)",
	"numberheadings" => "Beri nomor heading secara otomatis",
	"showtoolbar"=>"Tampilkan batang alat penyuntingan",
	"editondblclick" => "Sunting halaman dengan klik ganda (JavaScript)", #"Edit pages on double click (JavaScript)" 
	"editsection"=>"Tampilkan pautan untuk menyunting bagian tertentu",
	"editsectiononrightclick"=>"Sunting bagian dengan mengklik kanan judul bagian (JavaScript)", #"Enable section editing by right clicking<br /> on section titles (JavaScript)",
	"showtoc"=>"Tampilkan daftar isi untuk artikel yang mempunyai lebih dari 3 heading",# "Show table of contents for articles with more than 3 headings",
	"rememberpassword" => "Ingat kata sandi pada setiap sesi", #"Remember password across sessions",
	"editwidth" => "Kotak sunting memiliki lebar penuh", #"Edit box has full width",
	"watchdefault" => "Tambahkan halaman yang Anda sunting ke daftar pengamatan",# "Add pages you edit to your watchlist",
	"minordefault" => "Tandai semua suntingan kecil secara baku",# "Mark all edits minor by default" 
	"previewontop" => "Tampilkan pratilik sebelum kotak sunting dan tidak sesudahnya", #Show preview before edit box and not after it",
	"nocache" => "Matikan cache halaman" 
);

/* private */ $wgBookstoreListId = array(
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1" 
);

/* private */ $wgWeekdayNamesId = array(
	"Minggu", "Senin", "Selasa", "Rabu", "Kamis",
	"Jumat", "Sabtu" 
);

/* private */ $wgMonthNamesId = array(
	"Januari", "Februari", "Maret", "April", "Mei", "Juni",
	"Juli", "Agustus", "September", "Oktober", "November",
	"Desember" 
);

/* private */ $wgMonthAbbreviationsId = array(
	"Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agt",
	"Sep", "Okt", "Nov", "Des" 
);

# All special pages have to be listed here: a description of "" 
# will make them not show up on the "Special Pages" page, which 
# is the right thing for some of them (such as the "targeted" ones). 

/* private */ $wgValidSpecialPagesId = array(
	"Userlogin"   => "Login pengguna", #"Login Pengguna",
	"Userlogout"  => "Logout pengguna", #"Logout Pengguna",
	"Preferences" => "Atur konfigurasi saya", #"Set my user preferences",
	"Watchlist"   => "Daftar pengamatan",#My watchlist",
	"Recentchanges" => "Halaman yang terakhir berubah",#Recently updated pages",
	"Upload"      => "Unggahkan file gambar", #"Upload image files" 
	"Imagelist"   => "Daftar gambar",#Image list",
	"Listusers"   => "Daftar pengguna",#List of users",
	"Statistics"  => "Statistik situs",#site statistics",
	"Randompage"  => "Sembarang halaman",#Random article",

	"Lonelypages" => "Halaman yatim", # pages",
	"Unusedimages"	      => "Gambar yatim", #"Orphaned images",
	"Popularpages"	      => "Halaman populer", #"Popular pages",
	"Wantedpages" => "Halaman yang paling diinginkan", #"Most wanted pages",
	"Shortpages"  => "Halaman pendek", #"Short pages",
	"Longpages"   => "Halaman panjang", #"Long pages",
	"Newpages"    => "Halaman yang baru dibuat", #"Newly created pages",
	"Ancientpages"	      => "Artikel tertua", #Oldest articles 
	"Deadendpages"	=> "Halaman buntu",
	"Intl"		      => "Pautan antarbahasa", #Interlanguage links 
	"Allpages"    => "Semua halaman berdasar judul", #"All pages by title",

	"Ipblocklist" => "IP dan pengguna yang diblokir", #"Blocked IPs and users",
	"Maintenance" => "Halaman pemeliharaan", #"Maintenance page",
	"Specialpages"	=> "",
	"Contributions" => "",
	"Emailuser"   => "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"    => "",
	"Blockme"	=> "",
	"Booksources" => "Sumber buku luar", #"External book sources" 
	"Categories"  => "Kategori halaman",
	"Export"	      => "Ekspor halaman XML",
	"Version"	      => "Tampilkan versi MediaWiki",
);

/* private */ $wgSysopSpecialPagesId = array(
	"Blockip"	      => "Blokir IP atau pengguna", #"Block IP or user",
	"Asksql"	      => "Lakukan kueri terhadap pangkalan data", #"Query the database",
	"Undelete"	      => "Lihat dan kembalikan halaman yang telah dihapus", #"View and restore deleted pages",
	"Makesysop"	      => "Buat seorang pengguna menjadi sysop" 
);

/* private */ $wgDeveloperSpecialPagesId = array(
	"Lockdb"	      => "Hilangkan akses tulis terhadap pangkalan data (kunci)", #"Make database read-only",
	"Unlockdb"	      => "Kembalikan akses tulis terhadap pangkalan data (buka kunci)", #"Restore database write access",
	"Debug"			      => "Informasi debugging", # "Debugging information" 
);		

/* private */ $wgAllMessagesId = array(

# Bits of text used by many pages: 
# 
"categories" => "Kategori", #"Categories",
"category" => "kategori", #"category",
"category_header" => "Artikel dalam kategori \"$1\"", #"Articles in category \"$1\"",
"subcategories" => "Subkategori", #"Subcategories",

"linktrail"	      => "/^([a-z]+)(.*)\$/sD",
"mainpage"	      => "Halaman Utama",  
"mainpagetext"		      => "Perangkat Lunak Wiki berhasil dipasang.",
"mainpagedocfooter"   => "Silakan lihat [http://meta.wikipedia.org/wiki/MediaWiki_i18n dokumentasi tentang kastamisasi antarmuka] dan [http://meta.wikipedia.org/wiki/MediaWiki_User%27s_Guide Petunjuk Pengguna] untuk bantuan pemakaian dan konfigurasi.",
'portal'		=> 'Portal komunitas',
'portal-url'		=> '{{ns:4}}:Portal_Komunitas',
"about"			      => "Tentang",
"aboutwikipedia"	=> "Tentang {{SITENAME}}",
"aboutpage"	      => "{{ns:4}}:Tentang", #"Wikipedia:About",
'article'		=> 'Artikel',
"help"			      => "Bantuan",
"helppage"	      => "{{ns:12}}:Isi",
"wikititlesuffix"	=> "{{SITENAME}}",
"bugreports"	      => "Laporan Bug",
"bugreportspage"	=> "{{ns:4}}:Laporan_Bug",
"sitesupport"	      => "Sumbangan", # Set a URL in $wgSiteSupportPage in LocalSettings.php 
"faq"		      => "FAQ",
"faqpage"	      => "{{ns:4}}:FAQ",
"edithelp"	      => "Bantuan penyuntingan",
"edithelppage"		      => "{{ns:12}}:Penyuntingan",
"cancel"	      => "Batalkan",
"qbfind"	      => "Cari",
"qbbrowse"	      => "Lihat-lihat", #"Browse",
"qbedit"	      => "Sunting",
"qbpageoptions"		=> "Pilihan halaman",
"qbpageinfo"	      => "Informasi halaman",
"qbmyoptions"	      => "Pilihan saya",
"qbspecialpages"      => "Halaman istimewa",
"mypage"	      => "Halaman saya",
"moredotdotdot"		      => "Lebih lanjut...",
"mytalk"	      => "Pembicaraan saya",
"anontalk"	      => "Pembicaraan untuk IP ini",
'navigation'		=> 'Navigasi',
"currentevents"		=> "Kejadian terkini",
"disclaimers"		      => "Penafian",
"disclaimerpage"      => "{{ns:4}}:Penafian_Umum",
"errorpagetitle"	=> "Kesalahan",
"returnto"	      => "Kembali ke $1.", #"Return to $1.",
"fromwikipedia"		      => "Dari {{SITENAME}}, ensiklopedi bebas", # "From Wikipedia, the free encylcopedia 
"whatlinkshere"		      => "Halaman yang berpaut ke sini", #"Pages that link here",
"help"			      => "Bantuan",
"search"	      => "Cari",
"go"		      => "Pergi",
"history"	      => "Sejarah halaman",
'history_short'		=> 'Sejarah',
"printableversion"	=> "Versi untuk dicetak",
'edit'			=> 'Sunting',
"editthispage"		      => "Sunting halaman ini",
'delete'		=> 'Hapus',
"deletethispage"	=> "Hapus halaman ini", #"Delete this page",
"undelete_short"      => "Undelete",
'protect'		=> 'Lindungi',
"protectthispage"	=> "Lindungi halaman ini", #"Protect this page",
'unprotect'		=> 'Hilangkan Perlindungan',
"unprotectthispage"	=> "Hilangkan perlindungan", #"Unprotect this page",
"newpage"		=> "(buat) Halaman baru", # (create) "New page" 
"talkpage"	      => "Diskusikan halaman ini",
'specialpage'		=> 'Halaman Istimewa',
'personaltools'		=> 'Alat pribadi',
"postcomment"	      => "Kirim komentar",
"addsection"		      => "+",
"articlepage"	      => "Lihat artikel",# "View article",
"subjectpage"	      => "Halaman subjek",
'talk'			=> 'Diskusi',
'toolbox'		=> 'Kotak alat',
"userpage"		=> "Lihat halaman pengguna",# "View user page",
"wikipediapage"		=> "Lihat halaman meta",# "View meta page",
"imagepage"		=> "Lihat halaman gambar",
"viewtalkpage"		=> "Lihat diskusi",# "View discussion",
"otherlanguages"	=> "Bahasa lain",
"redirectedfrom"	=> "(Dialihkan dari $1)", #"(Redirected from $1)",
"lastmodified"		      => "Halaman ini terakhir diubah pada $1.", #"The page was last modified $1.",
"viewcount"	      => "Halaman ini telah diakses sebanyak $1 kali.", #"This page has been accessed $1 times.",
"copyright"	      => "Isi tersedia dibawah $1.",
"poweredby"	      => "{{SITENAME}} berjalan dibawah [http://www.mediawiki.org/ MediaWiki], sebuah engine wiki open source.",
"printsubtitle"		=> "(Dari {{SERVER}})", #"(From http://www.aposc.org)",
"protectedpage"		=> "Halaman yang dilindungi", #"Protected page",
"administrators"	=> "{{ns:4}}:Administrator",
"sysoptitle"	      => "Akses Sysop Diperlukan", #"Sysop access required",
"sysoptext"	      => "Tindakan yang Anda minta hanya dapat dilakukan oleh pengguna dengan status \"sysop\". Lihat $1.",
"developertitle"	=> "Akses Pengembang Diperlukan", # "Developer access required",
"developertext"		      => "Tindakan yang Anda minta hanya dapat dilakukan oleh pengguna dengan status \"pengembang\". Lihat $1.", # "The action you have requested can only be performed by users with \"developer\" status.See $1.",
"bureaucrattitle"     => "Akses Birokrat Diperlukan",
"bureaucrattext"      => "Tindakan yang Anda minta hanya dapat dilakukan 
oleh sysop dengan status \"birokrat\"",
"nbytes"	      => "$1 byte",
"go"		      => "Pergi",
"ok"		      => "OK",
"sitetitle"	      => "{{SITENAME}}",
"pagetitle"	      => "$1 - {{SITENAME}}",
"sitesubtitle"		      => "Ensiklopedi Bebas",
"retrievedfrom"		=> "Diperoleh dari \"$1\"", #"Retrieved from \"$1\"",
"newmessages"		=> "Anda mendapatkan $1.",
"newmessageslink"	=> "pesan baru",
"editsection"		=> "sunting",
"toc"			=> "Daftar isi",
"showtoc"		=> "tampilkan",
"hidetoc"		=> "sembunyikan",
"thisisdeleted"       => "Lihat atau kembalikan $1?",
"restorelink"		      => "$1 suntingan dihapus",
'feedlinks'		=> 'Feed:',

# Short words for each namespace, by default used in the 'article' tab in monobook 
'nstab-main'		=> 'Artikel',
'nstab-user'		=> 'Halaman pengguna',
'nstab-media'		=> 'Media',
'nstab-special'		=> 'Istimewa',
'nstab-wp'		=> 'Tentang',
'nstab-image'		=> 'Gambar',
'nstab-mediawiki'	=> 'Pesan',
'nstab-template'	=> 'Templat',
'nstab-help'		=> 'Bantuan',
'nstab-category'	=> 'Kategori',

# Main script and global functions 
# 
"nosuchaction"		      => "Tiada tindakan tersebut", #"No such action",
"nosuchactiontext"	=> "Tindakan yang dispesifikasikan oleh URL tersebut tidak dikenal oleh wiki.", #"The action specified by the URL is not recognized by the Wikipedia software",
"nosuchspecialpage"	=> "Tiada halaman istimewa tersebut", #"No such special page",
"nospecialpagetext"	=> "Anda telah meminta halaman istimewa yang tidak dikenal oleh wiki.", #"You have requested a special page that is not recognized by the Wikipedia software.",

# General errors 
# 
"error"			      => "Kesalahan",
"databaseerror"		=> "Kesalahan pangkalan data", #"Database error",
"dberrortext"	      => "Ada kesalahan sintaks pada permintaan pangkalan data. 
Kesalahan ini dapat terjadi karena adanya permintaan pencarian yang tidak sah (lihat $5), 
atau mungkin ada bug dalam perangkat lunak. 
Permintaan pangkalan data yang terakhir adalah: 
<blockquote><tt>$1</tt></blockquote> 
dari dalam fungsi \"<tt>$2</tt>\". 
Kesalahan MySQL \"<tt>$3: $4</tt>\".",
"dberrortextcl"       => "Ada kesalahan sintaks pada permintaan pangkalan data. 
Permintaan pangkalan data yang terakhir adalah: 
\"$1\" 
dari dalam fungsi \"$2\". 
Kesalahan MySQL \"$3: $4\".\n",
"noconnect"	      => "Maaf! wiki ini mengalami masalah teknis dan tidak dapat menghubungi pangkalan data.", #"Sorry! The wiki is experiencing some technical difficulties, and cannot contact the database server.",
"nodb"			      => "Tidak dapat memilih pangkalan data $1", #"Could not select database $1",
"cachederror"	      => "Berikut ini adalah salinan cache dari halaman yang diminta, dan mungkin tidak up-to-date.",
"readonly"	      => "Pangkalan data dikunci", #"Database locked",
"enterlockreason"	=> "Masukkan alasan penguncian, 
termasuk perkiraan kapan kunci akan dibuka",
"readonlytext"		      => "Pangkalan data sedang dikunci terhadap masukan baru. 
Administrator yang melakukan penguncian memberikan penjelasan sebagai berikut: 
<p>$1",
"missingarticle"=>"Pangkalan data tidak menemukan teks bagi halaman yang seharusnya mempunyai teks, yaitu halaman \"$1\". 

<p>Ini biasanya disebabkan karena diff yang kadaluwarsa atau karena pautan sejarah kepada halaman telah dihapus. 

<p>Jika ini bukan sebabnya, Anda mungkin menemukan bug dalam perangkat lunak. Silakan laporkan hal ini kepada administrator, dengan mencantumkan URL halaman yang bermasalah tersebut", # If this is not the case, you may have found a bug in the perangkat lunak. Please report this to an administrator, making note of the URL.",
# "missingarticle" => "The database did not find the text of a page that it should have found, named \"$1\". 
# This is usually caused by following an outdated diff or history link to a page that has been deleted. 
"internalerror" => "Kesalahan internal", # Internal error",
"filecopyerror" => "Tidak dapat menyalin file \"$1\" ke \"$2\".", #Could not copy file \"$1\" to \"$2\".",
"filerenameerror" => "Tidak dapat mengubah nama file \"$1\" menjadi \"$2\".", #Could not rename file \"$1\" to \"$2\".",
"filedeleteerror" => "Tidak dapat menghapus file \"$1\".", #Could not delete file \"$1\".",
"filenotfound"	      => "Tidak dapat menemukan file \"$1\".",	#Could not find file \"$1\".",
"unexpected"  => "Nilai di luar jangkauan: \"$1\"=\"$2\".", # Unexpected value: \"$1\"=\"$2\".",
"formerror"	      => "Kesalahan: Tidak dapat mengirimkan formulir", #Error: could not submit form",
"badarticleerror" => "Tindakan ini tidak dapat dilaksanakan di halaman ini.", #This action cannot be performed on this page.",
"cannotdelete"	      => "Tidak dapat menghapus halaman atau gambar yang telah ditentukan.",
"badtitle"	      => "Judul Tidak Sesuai", #Bad title",
"badtitletext"		      => "Judul halaman yang diminta tidak sah, kosong, atau salah sambung dengan antarbahasa atau judul antarwiki.", #The requested page title was invalid, empty, or an incorrectly linked inter-language or inter-wiki title.",
"perfdisabled" => "Maaf! fitur ini dimatikan sementara karena fitur ini memperlambat pengkalan data sampai-sampai pangkalan data tidak dapat digunakan lagi.", # "Sorry! This feature has been temporarily disabled because it slows the database down to the point that no one can use the wiki.",
"perfdisabledsub"	=> "Ini adalah salinan tersimpan dari $1:", # "Here's a saved copy from $1:",
"perfcached" => "Data berikut ini diambil dari cache dan mungkin tidak up-to-date:",
"wrong_wfQuery_params" => "Parameter salah ke wfQuery()<br /> 
Fungsi: $1<br /> 
Kueri: $2 
",
"viewsource" => "Lihat sumber",
"protectedtext" => "Halaman ini telah dikunci untuk menghindari penyuntingan; ada beberapa 
alasan mengapa hal ini terjadi, silakan lihat 
[[{{ns:4}}:Halaman_dilindungi]]. 

Anda dapat melihat dan menyalin sumber halaman ini:",
'seriousxhtmlerrors' => 'Ada kesalahan markah xhtml serius yang dideteksi oleh tidy.',

# Login and logout pages 
# 
"logouttitle" => "Pengguna Logout", #User logout 
"logouttext"  => "Anda telah logout dari sistem. 
Anda dapat terus menggunakan {{SITENAME}} secara anonim, atau Anda dapat 
login lagi sebagai pengguna yang sama atau pengguna yang lain. Perhatikan 
bahwa beberapa halaman mungkin masih terus menunjukkan bahwa Anda masih 
login sampai Anda membersihkan cache browser Anda\n",

"welcomecreation" => "<h2>Selamat datang, $1!</h2><p>Akun Anda telah dibuka. 
Jangan lupa untuk mengatur konfigurasi {{SITENAME}} Anda.", #"<h2>Welcome, $1!</h2><p>Your account has been created. Don't forget to personalize your preferences.",

"loginpagetitle" => "Pengguna Login", #User login 
"yourname"     => "Nama Pengguna", #Your user name",
"yourpassword"	       => "Kata Sandi", #Your password",
"yourpasswordagain" => "Ulangi Kata Sandi", #Retype password",
"newusersonly"	       => "(hanya pengguna baru)", # (new users only)",
"remembermypassword" => "Selalu ingat kata sandi.", # Remember my password across sessions.",
"loginproblem"	      => "<b>Ada masalah dengan data login Anda.</b><br />Silakan coba lagi!", # There has been a problem with your login.</b><br />Try again!",
"alreadyloggedin" => "<font color=red><b>Pengguna $1, Anda sudah login!</b></font><br />\n",

"areyounew"   => "Apabila Anda baru dalam {{SITENAME}} dan ingin mendapatkan akun pengguna, 
masukkanlah nama pengguna, kemudian ketikkan kata sandi, dan ulang kembali kata sandi. 
Alamat e-mail tidak diwajibkan. Walaupun begitu, apabila Anda lupa kata sandi Anda, Anda dapat memperolehnya kembali dengan cara meminta kami mengirimkan kata sandi ke e-mail yang diberikan.<br />\n",

"login"			      => "Login", #Log in 
"loginprompt"		=> "Anda harus mengaktifkan cookies untuk login ke {{SITENAME}}.",
"userlogin"	      => "Login", #Log in 
"logout"	      => "Logout",
"userlogout"  => "Logout", #Log out 
"notloggedin" => "Belum login",
"createaccount"       => "Buka akun baru", #Create new account",
"createaccountmail"   => "melalui e-mail",
"badretype"	      => "Kata sandi yang Anda masukkan salah.", #The passwords you entered do not match.",
"userexists"  => "Nama pengguna yang Anda masukkan telah dipakai. Silakan pilih nama yang lain.",
"youremail"	      => "e-mail Anda*", #Your e-mail",
"yourrealname"		      => "Nama sebenarnya*",
"yournick"	      => "Nama samaran (untuk tanda tangan)", #Your nickname (for signatures)",
"emailforlost"	      => "Isian yang bertanda * tidak harus diisi. Walaupun demikian, dengan memberikan alamat e-mail Anda, orang lain dapat menghubungi Anda melalui situs web tanpa perlu memberikan alamat 
e-mail Anda kepada mereka, dan e-mail Anda juga dapat digunakan untuk mendapatkan kata sandi yang baru (dengan cara dikirimkan ke alamat e-mail Anda) apabila Anda lupa kata sandi Anda.<br /><br />Nama sebenarnya, jika Anda memberikannya, akan digunakan untuk memberikan pengenalan atas kerja Anda.",
"loginerror"  => "Kesalahan login", #Login error",
"nocookiesnew"		      => "Akun pengguna telah dibuat, tetapi Anda belum login. {{SITENAME}} menggunakan cookies untuk login penggunanya. Cookies pada browser Anda dimatikan. Silakan nyalakan cookies dan login kembali dengan nama pengguna dan kata sandi Anda.",
"nocookieslogin"      => "{{SITENAME}} menggunakan cookies untuk login penggunanya. Cookies pada browser Anda dimatikan. Silakan nyalakan cookies dan coba lagi.",
"noname"	      => "Nama pengguna yang Anda masukkan tidak sah.", #You have not specified a valid user name.",
"loginsuccesstitle" => "Login Berhasil",  #Login successful",
"loginsuccess"	      => "Berhasil login ke {{SITENAME}} sebagai \"$1\".",  #You are now logged in to Wikipedia as \"$1\".",
"nosuchuser"  => "Tiada pengguna dengan nama \"$1\". #There is no user by the name \"$1\". 
Periksalah ejaan Anda, atau gunakan formulir di bawah ini untuk membuka akun baru.",
"wrongpassword"       => "Kata sandi yang Anda masukkan salah. Silakan coba lagi.",
"mailmypassword" => "e-mailkan kata sandi baru", #Mail me a new password",
"passwordremindertitle" => "Peringatan Kata Sandi dari {{SITENAME}}", #Password reminder from Wikipedia",
"passwordremindertext" => "Seseorang (mungkin Anda, dari alamat IP $1) 
meminta kami untuk mengirimkan kata sandi {{SITENAME}} yang baru. 
Kata sandi untuk pengguna \"$2\" sekarang adalah \"$3\". #The password for user \"$2\" is now \"$3\". 
Anda disarankan untuk segera melakukan login dan mengganti kata sandi.",  #You should log in and change your password now.",

#IP  Someone (probably you, from IP address $1) 
#requested that we send you a new Wikipedia login password. 
"noemail"	      => "Tiada alamat e-mail yang tercatat untuk pengguna \"$1\".",
"passwordsent"	      => "Kata sandi baru telah dikirimkan kepada e-mail yang didaftarkan untuk \"$1\". 
Silakan login kembali setelah menerima e-mail tersebut.", #Please log in again after you receive it.",
"loginend"	      => " ",
"mailerror" => "Kesalahan dalam mengirimkan e-mail: $1",

# Edit page toolbar 
"bold_sample"=>"Teks ini akan dicetak tebal",
"bold_tip"=>"Cetak tebal",
"italic_sample"=>"Teks ini akan dicetak miring",
"italic_tip"=>"Cetak miring",
"link_sample"=>"Judul pautan",
"link_tip"=>"Pautan internal",
"extlink_sample"=>"http://www.contoh.com/ judul pautan",
"extlink_tip"=>"Pautan eksternal (ingat awalan http:// )",
"headline_sample"=>"Teks headline",
"headline_tip"=>"Headline level 2",
"math_sample"=>"Masukkan rumus di sini",
"math_tip"=>"Rumus matematika (LaTeX)",
"nowiki_sample"=>"Teks ini tidak akan diformat",
"nowiki_tip"=>"Abaikan pemformatan wiki",
"image_sample"=>"Contoh.jpg",
"image_tip"=>"Gambar embedded",
"media_sample"=>"Contoh.ogg",
"media_tip"=>"Pautan file media",
"sig_tip"=>"Tanda tangan Anda dengan tanda waktu",
"hr_tip"=>"Garis horisontal (gunakan dengan hemat)",
"infobox"=>"Klik sebuah tombol untuk mendapatkan teks contoh",
# alert box shown in browsers where text selection does not work, test e.g. with mozilla or konqueror 
"infobox_alert"=>"Silakan masukkan teks yang ingin diformat.\\n Teks tersebut akan ditampilkan di infobox untuk penyalinan dan penempelan.\\nContoh:\\n$1\\nakan menjadi:\\n$2",

# Edit pages 
# 
"summary"	      => "Ringkasan", #"Summary",
"subject"	      => "Subjek/headline",
"minoredit"	      => "Ini adalah suntingan kecil.", #"This is a minor edit." 
"watchthis"	      => "Amati artikel ini",
"savearticle" => "Simpan halaman", #"Save page",
"preview"	      => "Pratilik", #"Preview",
"showpreview" => "Tampilkan pratilik", #"Show preview",
"blockedtitle"	      => "Pengguna Diblokir", #"User is blocked",
"blockedtext" => "Nama pengguna atau alamat IP Anda telah diblokir oleh $1. 
Alasannya karena :<br />$2<p>Anda dapat menghubungi 
[[{{ns:4}}:Administrators|para administrator]] untuk membicarakan blokir ini. 

Perhatikan bahwa Anda tidak dapat menggunakan fasilitas \"e-mail pengguna ini\" kecuali Anda mempunyai sebuah alamat e-mail yang sah dan alamat e-mail tersebut tercatat di dalam [[Istimewa:Preferences|konfigurasi Anda]]. 

Alamat IP Anda adalah $3. Sertakan alamat IP ini pada setiap pertanyaan yang Anda buat",
"whitelistedittitle" => "Login Diperlukan untuk Penyuntingan",
"whitelistedittext" => "Anda harus [[Istimewa:Userlogin|login]] untuk dapat menyunting artikel.",
"whitelistreadtitle" => "Login Diperlukan untuk Membaca",
"whitelistreadtext" => "Anda harus [[Istimewa:Userlogin|login]] untuk dapat membaca artikel.",
"whitelistacctitle" => "Anda Tidak Diperbolehkan untuk Membuat Akun",
"whitelistacctext" => "Untuk dapat membuat akun dalam Wiki ini, Anda harus [[Istimewa:Userlogin|login]] dan mempunyai izin yang tepat.",
"loginreqtitle"       => "Login Diperlukan",
"loginreqtext"	      => "Anda harus [[Istimewa:Userlogin|login]] untuk dapat melihat halaman lainnya.",
"accmailtitle" => "Kata Sandi Dikirimkan",
"accmailtext" => "Kata sandi untuk '$1' telah dikirimkan ke $2.",
"newarticle"  => "(Baru)", #"(New)",
"newarticletext" => "Letakkan teks Anda di sini untuk halaman baru.",
"talkpagetext" => "<!-- MediaWiki:talkpagetext -->",
"anontalkpagetext" => "---- ''Ini adalah halaman diskusi untuk seorang pengguna anonim yang belum membuat akun atau tidak menggunakannya. Karena Ia tidak membuat akun, kami terpaksa harus memakai [[alamat IP]]-nya untuk mengenalinya. Alamat IP seperti ini dapat dipakai oleh beberapa pengguna yang berbeda. Jika Anda adalah seorang pengguna anonim dan merasa mendapatkan komentar-komentar miring, silakan [[Istimewa:Userlogin|membuat akun atau login]] untuk menghindari kerancuan dengan pengguna anonim lain di lain waktu.'' ",
"noarticletext" => "(Tidak ada teks dalam halaman ini)",
"updated"	      => "(Diperbarui)", #"(Updated)",
"note"			      => "<strong>Catatan:</strong> ",
"previewnote" => "Ingatlah bahwa ini hanya pratilik dan belum disimpan!", #"Remember that this is only a preview, and has not yet been saved!",
"previewconflict" => "Pratilik ini mencerminkan teks pada bagian atas sebagaimana Ia 
akan terlihat bila Anda memilih untuk menyimpannya.",  
"editing"	      => "Menyunting $1", #"Editing $1",
"sectionedit" => " (bagian)",
"commentedit" => " (komentar)",
"editconflict"	      => "Konflik penyuntingan: $1", #"Edit conflict: $1",
"explainconflict" => "Orang lain telah menyunting halaman ini sejak Anda 
mulai menyuntingnya. 
Bagian atas teks ini mengandung teks halaman saat ini. 
Perubahan yang Anda lakukan ditunjukkan pada bagian bawah teks. 
Anda hanya perlu menggabungkan perubahan Anda dengan teks yang telah ada. 
<b>Hanya</b> teks pada bagian atas halamanlah yang akan disimpan apabila Anda 
menekan \"Simpan halaman\".\n<p>",
"yourtext"	      => "Teks Anda", #"Your text",
"storedversion" => "Versi tersimpan", #"Stored version",
"editingold"  => "<strong>PERINGATAN: Anda menyunting revisi halaman yang kadaluwarsa. 
Jika Anda menyimpannya, perubahan-perubahan yang dibuat sejak revisi ini akan hilang.</strong>\n",
"yourdiff"	      => "Perbedaan", #"Differences",
"copyrightwarning" => "Perhatikan bahwa semua sumbangan terhadap {{SITENAME}} dianggap 
dilisensikan di bawah lisensi GNU Free Documentation License 
(lihat $1 untuk informasi lebih lanjut). 
Jika Anda tidak menginginkan tulisan Anda disunting dan disebarkan ke halaman 
web yang lain, jangan kirimkan artikel Anda ke sini.<br /> 
Anda juga berjanji bahwa ini adalah hasil karya Anda sendiri, atau Anda 
menyalinnya dari sumber milik umum atau sumber bebas yang lain. 
<p><strong><font color=\"red\">JANGAN KIRIMKAN KARYA YANG MEMPUNYAI HAK CIPTA TANPA IZIN!</font></strong></p> <p><strong><font color=\"red\">JANGAN SALIN ARTIKEL DARI HALAMAN WEB LAIN.</font></strong></p> ",
"longpagewarning" => "PERINGATAN: Halaman ini panjangnya adalah $1 kilobytes; beberapa 
browser mungkin mengalami masalah dalam menyunting halaman yang panjangnya 32 kB atau lebih. 
Pertimbangkan untuk memecah halaman menjadi beberapa halaman kecil.",
"readonlywarning" => "PERINGATAN: Pangkalan data sedang dikunci karena pemeliharaan, 
sehingga saat ini Anda tidak akan dapat menyimpan hasil suntingan Anda. Anda mungkin perlu 
untuk memindahkan hasil suntingan Anda ini ke tempat lain untuk disimpan belakangan.",
"protectedpagewarning" => "PERINGATAN:	Halaman ini telah dikunci sehingga hanya pemakai 
dengan status sysop saja yang dapat menyuntingnya. Pastikan Anda mengikuti 
<a href='$wgScript/{{ns:4}}:Petunjuk_halaman_dilindungi'>aturan halaman yang dilindungi</a>.",

# History pages 
# 
"revhistory"  => "Sejarah revisi", #"Revision history",
"nohistory"	      => "Tidak ada sejarah penyuntingan untuk halaman ini", #"There is no edit history for this page.",
"revnotfound" => "Revisi tidak ditemukan", #"Revision not found",
"revnotfoundtext" => "Revisi lama untuk halaman yang Anda minta tidak dapat ditemukan. 
Silakan periksa URL yang digunakan untuk mengakses halaman ini.\n", #"The old revision of the page you asked for could not be found. Please check the URL you used to access this page.\n",
"loadhist"	      => "Memuat halaman sejarah", #"Loading page history",
"currentrev"  => "Revisi sekarang", #"Current revision",
"revisionasof"	      => "Revisi sebagaimana $1", #"Revision as of $1",
"cur"		      => "cur",
"next"			      => "next",
"last"			      => "akhir",
"orig"			      => "orig",
"histlegend"  => "Legenda: (cur) = perbedaan dengan versi sekarang, (akhir) = perbedaan dengan versi sebelumnya", #"Legend: (cur) = difference with current version,(last) = difference with preceding version",

# Diffs 
# 
"difference"  => "(Perbedaan antarrevisi)", #"(Difference between revisions)",
"loadingrev"  => "memuat revisi diff", #"loading revision for diff",
"lineno"	      => "Baris $1:", #"Line $1:",
"editcurrent" => "Sunting versi sekarang dari halaman ini", #"Edit the current version of this page",
'selectnewerversionfordiff' => 'Pilih sebuah versi yang lebih baru untuk perbandingan',
'selectolderversionfordiff' => 'Pilih sebuah versi yang lebih tua untuk perbandingan',
'compareselectedversions' => 'Perbandingkan versi terpilih',

# Search results 
# 
"searchresults" => "Hasil Pencarian",
"searchhelppage" => "{{ns:4}}:Pencarian", #"Wikipedia:Searching",
"searchingwikipedia" => "Mencari {{SITENAME}}", #"Searching Wikipedia",
"searchresulttext" => "Untuk informasi lebih lanjut tentang pencarian dalam {{SITENAME}}, lihat $1.", #"For more information about searching Wikipedia, see $1.",
"searchquery" => "Untuk kueri \"$1\"", #"For query \"$1\"",
"badquery"	      => "Format kueri pencarian salah", #"Badly formed search query",
"badquerytext"	      => "Kami tidak dapat memproses kueri Anda. 
Ini mungkin disebabkan karena Anda mencoba mencari perkataan yang kurang 
dari tiga huruf, yang masih belum didukung oleh sistem ini. 
Hal ini mungkin juga disebabkan oleh kesalahan pengetikan ekspresi, 
misalnya \"fish and and scales\". Silakan coba kueri yang lain.",
"matchtotals" => "Kueri \"$1\" cocok dengan $2 judul halaman dan teks dari $3 artikel.", #"The query \"$1\" matched $2 page titles and the text of $3 pages.",
"nogomatch" => "Tidak ada halaman dengan judul persis seperti ini, mencoba pencarian full text.", #"No page with this exact title exists, trying full text search.",
"titlematches"	      => "Judul artikel yang cocok", #"Article title matches",
"notitlematches" => "Tidak ada judul halaman yang cocok", #"No page title matches",
"textmatches" => "Teks artikel yang cocok", #"Article text matches",
"notextmatches"       => "Tiada teks halaman yang cocok", #No page text matches",
"prevn"			      => "$1 sebelumnya", #"previous $1",
"nextn"			      => "$1 berikutnya", #"next $1",
"viewprevnext"	      => "Lihat ($1) ($2) ($3).", #"View ($1) ($2) ($3).",
"showingresults" => "Di bawah ditunjukkan <b>$1</b> hasil pencarian yang bermula dengan #<b>$2</b>.", #"Showing below <b>$1</b> results starting with #<b>$2</b>.",
"showingresultsnum" => "Di bawah ditunjukkan <b>$3</b> hasil pencarian yang bermula dengan #<b>$2</b>.", #"Showing below <b>$3</b> results starting with #<b>$2</b>.",
"nonefound"	      => "<strong>Catatan</strong>: Kegagalan pencarian 
biasanya disebabkan oleh pencarian kata-kata umum, seperti \"have\" and \"from\", 
yang biasanya tidak diindeks, atau dengan menentukan lebih dari satu aturan 
pencarian (hanya halaman yang mengandung semua aturan pencarianlah yang akan 
ditampilkan dalam hasil pencarian)",
# "<strong>Note</strong>: unsuccessful searches are often caused by searching for common words like \"have\" and \"from\",which are not indexed, or by specifying more than one search term (only pages containing all of the search terms will appear in the result).",
"powersearch" => "Cari", #"Search",
"powersearchtext" => " 
Cari dalam namespace :<br /> 
$1<br /> 
$2 List redirects   Search for $3 $9",
"searchdisabled" => "<p>Maaf! pencarian full text sementara dimatikan karena masalah performa. Dalam pada itu, Anda dapat menggunakan pencarian Google berikut ini, yang mungkin hasilnya out-of-date.</p>", #"<p>Sorry! Full text search has been disabled temporarily, for performance reasons. In the meantime, you can use the Google search below, which may be out of date.</p>",
"googlesearch" => " 
<!-- SiteSearch Google --> 
<FORM method=GET action=\"http://www.google.com/search\"> 
<TABLE bgcolor=\"#FFFFFF\"><tr><td> 
<A HREF=\"http://www.google.com/webhp?hl=id\"> 
<IMG SRC=\"http://www.google.com/logos/Logo_40wht.gif\" 
border=\"0\" ALT=\"Google\"></A> 
</td> 
<td> 
<INPUT TYPE=text name=q size=31 maxlength=255 value=\"$1\"> 
<INPUT type=submit name=btnG VALUE=\"Mesin Cari Google\"> 
<font size=-1> 
<input type=hidden name=domains value=\"{{SERVER}}\"><br /><input type=radio name=sitesearch value=\"\"> WWW <input type=radio name=sitesearch value=\"{{SERVER}}\" checked> {{SERVER}} <br /> 
<input type='hidden' name='hl' value='id'> 
<input type='hidden' name='ie' value='$2'> 
<input type='hidden' name='oe' value='$2'> 
</font> 
</td></tr></TABLE> 
</FORM> 
<!-- SiteSearch Google -->",
"blanknamespace" => "(Utama)",

# Preferences page 
# 
"preferences" => "Konfigurasi", #"Preferences",
"prefsnologin" => "Belum login", #"Not logged in",
"prefsnologintext" => "Anda harus <a href=\"{{localurl:Istimewa:Userlogin}}\">login</a> 
untuk menetapkan konfigurasi Anda.", #"You must be <a href=\"" . 
"prefslogintext" => "Anda telah login sebagai \"$1\". Nomor ID Anda ialah $2.",


"prefsreset"  => "Konfigurasi telah diubah ke asal dari storage.", #"Preferences have been reset from storage.",
"qbsettings"  => "Konfigurasi quickbar", #"Quickbar settings",
"changepassword" => "Ganti kata sandi", #"Change password",
"skin"		      => "Skin",
"math"		      => "Menggambar math",
"dateformat"  => "Format tanggal",
"math_failure"		      => "Gagal memparse",
"math_unknown_error"  => "Kesalahan yang tidak diketahui",
"math_unknown_function"       => "fungsi yang tidak diketahui ",
"math_lexing_error"   => "kesalahan lexing",
"math_syntax_error"   => "kesalahan sintaks",
"math_image_error"    => "Konversi PNG gagal; periksa apakah latex, dvips, gs, dan convert terinstal dengan benar",
"math_bad_tmpdir"     => "Tidak dapat menulisi atau membuat directori sementara math",
"math_bad_output"     => "Tidak dapat menulisi atau membuat direktori keluaran math",
"math_notexvc"	      => "Executable texvc hilang; silakan lihat math/README untuk cara konfigurasi.",
"saveprefs"	      => "Simpan konfigurasi", #"Save preferences",
"resetprefs"  => "Atur kembali konfigurasi", #"Reset preferences",
"oldpassword" => "Kata sandi lama", #"Old password",
"newpassword" => "Kata sandi baru", #"New password",
"retypenew"	      => "Ketikkan lagi kata sandi yang baru", #"Retype new password",
"textboxsize" => "Dimensi kotak teks", #"Textbox dimensions",
"rows"			      => "Baris", #"Rows",
"columns"	      => "Kolom", #"Columns",
"searchresultshead" => "Konfigurasi hasil pencarian", #"Search result settings",
"resultsperpage" => "Hasil pencarian per halaman", #"Hits to show per page",
"contextlines"	      => "Baris ditampilkan per hasil", #"Lines to show per hit",
"contextchars"	      => "Karakter untuk konteks per baris", #"Characters of context per line",
"stubthreshold" => "Threshold untuk tampilan stub",
"recentchangescount" => "Jumlah judul dalam perubahan terkini", #"Number of titles in recent changes",
"savedprefs"  => "Konfigurasi Anda telah disimpan", #"Your preferences have been saved.",
"timezonetext"	      => "Masukkan perbedaan waktu dalam jam antara waktu setempat dengan waktu server (UTC).", #"Enter number of hours your local time differs from server time (Kuala Lumpur, which is GMT+8).",
"localtime"   => "Waktu setempat", #"Local time",
"timezoneoffset" => "Perbedaan", #"Offset",
"servertime"  => "Waktu server sekarang adalah",
"guesstimezone" => "Isikan dari browser",
"emailflag"	      => "Matikan e-mail dari pengguna lain", #"Disable e-mail from other users",
"defaultns"	      => "Cari dalam namespace berikut ini secara baku:",

# Recent changes 
# 
"changes" => "perubahan",
"recentchanges" => "Perubahan terkini",
"recentchangestext" => "Lacak perubahan terkini dalam wiki di halaman ini.",
"rcloaderr"	      => "Memuat perubahan terkini", #"Loading recent changes",
"rcnote"	      => "Di bawah ini adalah <strong>$1</strong> perubahan terakhir dalam <strong>$2</strong> hari terakhir.", #"Below are the last <strong>$1</strong> changes in last <strong>$2</strong> days.",
"rcnotefrom"  => "Di bawah ini adalah perubahan sejak <b>$2</b> (ditampilkan sampai <b>$1</b> perubahan).", #Below are the changes since <b>$2</b> (up to <b>$1</b> shown). 
"rclistfrom"  => "Tampilkan perubahan baru sejak $1", #"Show new changes starting from $1" 
# "rclinks"	      => "Show last $1 changes in last $2 hours / last $3 days",
# "rclinks"	      => "Show last $1 changes in last $2 days.",
"showhideminor"		=> "$1 penyuntingan kecil | $2 robot | $3 pengguna yang login ",
"rclinks"	      => "Tampilkan $1 perubahan terakhir dalam $2 hari terakhir<br />$3", #"Show last $1 changes in last $2 days<br />$3",
"rchide"	      => "dalam bentuk $4; $1 penyuntingan kecil; $2 namespace sekunder; $3 penyuntingan berganda.",  #"in $4 form; $1 minor edits; $2 secondary namespaces; $3 multiple edits.",
"rcliu"			      => "; $1 penyuntingan dari pengguna yang login",
"diff"			      => "diff",
"hist"			      => "hist",
"hide"			      => "sembunyikan",
"show"			      => "tampilkan",
"tableform"	      => "tabel",
"listform"	      => "daftar",
"nchanges"	      => "$1 perubahan",
"minoreditletter" => "m",
"newpageletter" => "B",

# Upload 
# 
"upload"	      => "Unggah",
"uploadbtn"	      => "Unggahkan file", #"Upload file",
"uploadlink"  => "Unggahkan gambar", #"Upload images",
"reupload"	      => "Unggahkan ulang", #"Re-upload",
"reuploaddesc"	      => "Kembali ke formulir pengunggahan", #"Return to the upload form.",
"uploadnologin" => "Belum login", #"Not logged in",
"uploadnologintext"   => "Anda harus <a href=\"{{localurl:Istimewa:Userlogin}}\">login</a> untuk dapat mengunggahkan file.",
"uploadfile"  => "Unggahkan file", #"Upload file",
"uploaderror" => "Kesalahan Pengunggahan", #"Upload error",
"uploadtext"  => "<strong>STOP!</strong> Sebelum Anda mengunggahkan di sini, 
pastikan bahwa Anda telah membaca dan menaati <a href=\"{{localurle:Istimewa:Image_use_policy}}\">kebijaksanaan penggunaan gambar</a>. 
<p>Jika ada sebuah file dalam wiki yang mempunyai nama yang sama dengan 
file yang akan Anda unggahkan, file tersebut akan ditimpa tanpa peringatan 
dengan file yang akan Anda unggahkan. Jadi, kecuali Anda ingin memperbarui sebuah 
file, Anda sangat disarankan untuk memeriksa apakah sudah ada file dengan 
nama yang sama dengan file yang akan Anda unggahkan. 
<p>Untuk melihat atau mencari gambar-gambar yang telah diunggahkan, 
silakan kunjungi <a href=\"{{localurle:Istimewa:Imagelist}}\">daftar gambar yang diunggahkan</a>. 
Semua pengunggahan dan penghapusan dicatat dalam <a href=\"{{localurle:Project:Catatan_Unggah}}\">catatan pengunggahan</a>. 
<p>Gunakan formulir berikut ini untuk mengunggahkan file gambar baru untuk 
dipakai dalam mengilustrasikan artikel Anda. 
Pada kebanyakan browser, Anda akan melihat tombol \"Lihat-lihat...\", 
yang akan menampilkan dialog buka file sistem operasi Anda. 
Memilih sebuah file akan mengisikan namanya dalam kotak teks di 
sebelah tombol tersebut. 
Anda juga harus memberi tanda cek pada kotak cek, menandakan bahwa Anda 
tidak melanggar hak cipta apapun dengan mengunggahkan file tersebut. 
Tekan tombol \"Unggah\" untuk menyelesaikan proses pengunggahan. 
Proses ini mungkin akan memakan waktu agak lama jika Anda memiliki 
koneksi internet yang lambat. 
<p>Format file yang disukai adalah JPEG untuk foto, PNG 
untuk gambar dan simbol, dan OGG untuk suara. 
Mohon beri nama file Anda secara deskriptif untuk menghindari 
kebingungan. 
Untuk memasukkan sebuah gambar ke dalam artikel, gunakan link 
dalam bentuk <b>[[{{ns:6}}:file.jpg]]</b> atau <b>[[{{ns:6}}:file.png|teks alternatif]]</b> 
atau <b>[[{{ns:-2}}:file.ogg]]</b> untuk suara. 
<p>Mohon diperhatikan bahwa sebagaimana dengan halaman {{SITENAME}} 
yang lain, orang lain dapat menyunting atau menghapus file Anda jika 
mereka menganggap hal itu perlu, dan Anda juga dapat diblokir 
bila Anda menyalahgunakan sistem.",

"uploadlog"	      => "catatan pengunggahan", #"upload log",
"uploadlogpage" => "Catatan_Unggah", #"Upload_log",
"uploadlogpagetext" => "Di bawah ini adalah daftar terkini file yang diunggahkan. 
Semua waktu yang ditunjukkan adalah waktu server (UTC). 
<ul> 
</ul> 
",
"filename"	      => "Nama file",
"filedesc"	      => "Ringkasan", #"Summary",
"filestatus" => "Status hak cipta",
"filesource" => "Sumber",
"affirmation" => "Saya menegaskan bahwa pemilik hak cipta file ini 
telah setuju untuk melisensikan file tersebut di bawah aturan $1.", #"I affirm that the copyright holder of this file agrees to license it under the terms of the $1.",
"copyrightpage" => "{{ns:4}}:Hak_Cipta",
"copyrightpagename" => "Hak cipta {{SITENAME}}",
"uploadedfiles"       => "File yang telah diunggahkan", #"Uploaded files",
"noaffirmation" => "Anda harus menegaskan bahwa file yang akan diunggahkan tidak melanggar hak cipta apapun.", #"You must affirm that your upload does not violate any copyrights.",
"ignorewarning"       => "Abaikan peringatan dan simpan file", #"Ignore warning and save file anyway.",
"minlength"	      => "Nama gambar sekurang-kurangnya harus tiga huruf.", #"Image names must be at least three letters.",
"badfilename" => "Nama gambar telah diubah menjadi \"$1\".", #"Image name has been changed to \"$1\".",
"badfiletype" => "\".$1\" ialah format file gambar yang tidak disarankan.",
"largefile"	      => "Ukuran gambar disarankan tidak melebihi 100k.",
"successfulupload" => "Berhasil diunggahkan",	 #"Successful upload",
"fileuploaded"	      => "File \"$1\" berhasil diunggahkan. 
Silakan ikuti pautan berikut: ($2) ke halaman deskripsi dan isikan informasi 
tentang file tersebut, seperti file itu dari mana, kapan ia dibuat dan 
oleh siapa, dan informasi lain yang Anda ketahui.",
"uploadwarning" => "Peringatan pengunggahan", # Upload warning",
"savefile"	      => "Simpan file", #"Save file",
"uploadedimage" => "mengunggahkan \"$1\"",     #"uploaded \"$1\"",
"uploaddisabled" => "Maaf, pengunggahan dimatikan.",

# Image list 
# 
"imagelist"   => "Daftar gambar",   #"Image list",
"imagelisttext"       => "Di bawah ini adalah daftar gambar yang telah diurutkan $2.", #"Below is a list of $1 images sorted $2.",
"getimagelist"	      => "memperoleh daftar gambar",	#"fetching image list",
"ilshowmatch" => "Tampilkan semua gambar dengan nama yang cocok dengan",    #"Show all images with names matching",
"ilsubmit"	      => "Pencarian", #"Search",
"showlast"	      => "Tampilkan $1 gambar terakhir yang telah diurutkan $2.", #"Show last $1 images sorted $2.",
"all"		      => "semua", #"all",
"byname"	      => "berdasarkan nama",   #"by name",
"bydate"	      => "berdasarkan tanggal", #by date",
"bysize"	      => "berdasarkan ukuran",	#"by size",
"imgdelete"	      => "del",
"imgdesc"	      => "desc",
"imglegend"	      => "Legenda: (desc) = lihat/sunting deskripsi gambar.", #"Legend: (desc) = show/edit image description.",
"imghistory"  => "Sejarah gambar",  #"Image history",
"revertimg"	      => "rev",
"deleteimg"	      => "del",
"imghistlegend" => "Legend: (cur) = ini adalah gambar yang sekarang, (del) = hapus 
versi lama ini, (rev) = kembalikan ke versi lama ini. 
<br /><i>Klik pada tanggal untuk melihat gambar yang diunggahkan pada tanggal tersebut</i>.",
#"imghistlegend" => "Legend: (cur) = this is the current image, (del) = delete 
#this old version, (rev) = revert to this old version. 
#<br /><i>Click on date to see image uploaded on that date</i>.",
"imagelinks"  => "Pautan gambar",  #"Image links",
"linkstoimage"	      => "Halaman-halaman berikut berpaut pada gambar ini:", #"The following pages link to this image:",
"nolinkstoimage" => "Tiada halaman yang berpaut pada gambar ini.",   #"There are no pages that link to this image.",

# Statistics 
# 
"statistics"  => "Statistik", #"Statistics",
"sitestats"	      => "Statistik situs",    #"Site statistics",
"userstats"	      => "Statistik pengguna",	#"User statistics",
"sitestatstext" => "Ada sejumlah <b>$1</b> halaman dalam pangkalan data. 
Ini termasuk halaman \"pembicaraan\", halaman tentang {{SITENAME}}, halaman minimum \"stub\", 
peralihan halaman, dan halaman-halaman lain yang mungkin bukan artikel. 
Selain itu, ada <b>$2</b> halaman yang mungkin adalah artikel yang sah.<p> 
Ada sejumlah <b>$3</b> penampilan halaman, dan sejumlah <b>$4</b> penyuntingan sejak wiki ini dimulai. 
Ini berarti rata-rata <b>$5</b> suntingan per halaman, dan <b>$6</b> penampilan per penyuntingan.",
"userstatstext" => "Ada <b>$1<b/> pengguna terdaftar. <b>$2<b/> diantaranya adalah administrator (lihat $3).",	

# Maintenance Page 
# 
"maintenance"	      => "Halaman pemeliharaan",  #"Maintenance page",
"maintnancepagetext"  => "Halaman ini mengandung beberapa peralatan untuk pemeliharaan harian. Kebanyakan fungsi yang terdapat di sini cenderung untuk membebani pangkalan data, jadi mohon jangan tekan tombol 'reload' setelah melakukan perbaikan ;-)",
"maintenancebacklink" => "Kembali ke halaman pemeliharaan", #"Back to Maintenance Page",
"disambiguations"     => "Halaman disambiguation", #"Disambiguation pages",
"disambiguationspage" => "{{ns:4}}:Pautan_ke_halaman_disambiguation",	 #"Wikipedia:Links_to_disambiguating_pages",
"disambiguationstext" => "Halaman-halaman berikut ini berpaut ke sebuah <i>halaman disambiguation</i>. Halaman-halaman tersebut seharusnya berpaut ke topik-topik yang tepat.<br />Satu halaman dianggap sebagai disambiguation apabila halaman tersebut disambung dari $1.<br />Pautan dari namespaces lain <i>tidak</i> terdaftar di sini.",
"doubleredirects"     => "Peralihan Halaman Berganda", #"Double Redirects",
"doubleredirectstext" => "<b>Perhatian:</b> Daftar ini mungkin tidak tepat. Ketidaktepatan 
itu mungkin disebabkan adanya halaman yang mengandung teks lain dengan pautan di bawah #REDIRECT 
yang pertama.<br />\nSetiap baris mengandung pautan ke peralihan pertama dan kedua, sebagaimana 
baris pertama dari teks peralihan kedua, yang biasanya memberikan artikel target yang 
\"sesungguhnya\", yang seharusnya ditunjuk oleh peralihan yang pertama.",
"brokenredirects"     => "Peralihan Halaman Rusak", #"Broken Redirects",
"brokenredirectstext" => "Peralihan halaman berikut berpaut ke halaman yang tidak ada", #"The following redirects link to a non-existing page.",
"selflinks"	      => "Halaman-halaman dengan pautan sendiri", #"Pages with Self Links",
"selflinkstext"		      => "Halaman-halaman berikut mengandung pautan ke dirinya sendiri, yang seharusnya tidak diizinkan.", #"The following pages contain a link to themselves, which they should not.",
"mispeelings"		=> "Halaman-halaman dengan kesalahan ejaan", #"Pages with misspellings",
"mispeelingstext"	=> "Halaman-halaman berikut mengandung kesalahan ejaan yang didaftar di $1. Ejaan yang benar mungkin diberikan (seperti ini).", # "The following pages contain a common misspelling, which are listed on $1. The correct spelling might be given (like this).",
"mispeelingspage"	=> "Daftar kesalahan ejaan yang umum", #"List of common misspellings",
"missinglanguagelinks"	=> "Pautan bahasa yang hilang", #"Missing Language Links",
"missinglanguagelinksbutton"	=> "Cari pautan bahasa yang hilang untuk", #"Find missing language links for",
"missinglanguagelinkstext"	=> "Halaman ini <i>tidak</i> menyambung ke halaman rekannya di $1. Peralihan dan sub halaman <i>tidak</i> ditunjukkan.",


# Miscellaneous special pages 
# 
"orphans"     => "Halaman yatim", #"Orphaned pages",
"lonelypages" => "Halaman yatim", #"Orphaned pages",
"unusedimages"	      => "Gambar yang tidak digunakan",
"popularpages"	      => "Halaman Populer",
"nviews"      => "$1 penampilan", #"$1 views",
"wantedpages" => "Halaman yang Diinginkan",
"nlinks"      => "$1 pautan",  #"$1 links",
"allpages"    => "Semua Halaman",
"randompage"  => "Sembarang Halaman",
"shortpages"  => "Halaman Pendek",
"longpages"   => "Halaman Panjang",
"deadendpages"	=> "Halaman Buntu",					
"listusers"   => "Daftar Pengguna",
"specialpages"	      => "Halaman Istimewa",
"spheading"   => "Halaman Istimewa untuk semua pengguna", #"Special pages",
"sysopspheading" => "Hanya untuk sysop",
"developerspheading" => "Hanya untuk pengembang",
"protectpage" => "Lindungi halaman",
"recentchangeslinked" => "Perubahan berkaitan",
"rclsub"	      => "(untuk halaman yang berpaut dari \"$1\")",   #"(to pages linked from \"$1\")",
"debug"			      => "Debug",   #"Debug",
"newpages"	      => "Halaman Baru",
"ancientpages"		      => "Artikel Tua",
"intl"		      => "Pautan Antarbahasa",
'move' => 'Pindahkan',
"movethispage"	      => "Pindahkan halaman ini",
"unusedimagestext" => "<p>Perhatikan bahwa situs web lain mungkin dapat berpaut 
dengan sebuah file gambar secara langsung, dan file-file gambar seperti itu mungkin 
terdapat dalam daftar ini meskipun masih digunakan oleh situs web lain.",
"booksources" => "Sumber buku",
"booksourcetext" => "Di bawah ini adalah daftar pautan ke situs lain 
yang menjual buku baru dan bekas, dan mungkin juga mempunyai informasi lebih lanjut 
mengenai buku yang sedang Anda cari. 
{{SITENAME}} tidak berkepentingan dengan situs-situs web di atas, 
dan daftar ini seharusnya tidak dianggap sebagai sebuah dukungan.",
"isbn"	      => "ISBN",
"rfcurl" =>  "http://www.faqs.org/rfcs/rfc$1.html",
"alphaindexline" => "$1 ke $2",
"version" => "Versi",

# Email this user 
# 
"mailnologin" => "Tidak ada alamat e-mail", #"No send address",
"mailnologintext" => "Anda harus <a href=\"{{localurl:Istimewa:Userlogin\">login</a> dan mempunyai alamat e-mail yang sah di dalam <a href=\"{{localurl:Istimewa:Preferences}}\">konfigurasi</a> untuk mengirimkan e-mail kepada pengguna lain.",  #"You must be <a href=\"" .	 wfLocalUrl( "Istimewa:Userlogin" ) . "\">logged in</a> and have a valid e-mail address in your <#a href=\"" .	 wfLocalUrl( "Istimewa:Preferences" ) . "\">preferences</a> to send e-mail to other users.",

"emailuser"	      => "e-mail pengguna ini", #"E-mail this user",
"emailpage"	      => "e-mail pengguna", #"E-mail user",
"emailpagetext"       => "Jika pengguna ini memasukkan alamat e-mail yang sah dalam konfigurasinya, formulir dibawah ini akan mengirimkan sebuah e-mail. Alamat e-mail yg terdapat pada konfigurasi Anda akan muncul sebagai alamat \"From\" dalam e-mail tersebut, sehingga penerima dapat membalas e-mail tersebut.",

#"If this user has entered a valid e-mail address in is user preferences, the form below will send a single message. 
#The e-mail address you entered in your user preferences will appear as the \"From\" address of the mail, so the recipient wi#ll be able to reply.",

"usermailererror" => "Kesalahan objek mail: ",
"defemailsubject"  => "e-mail {{SITENAME}}",
"noemailtitle"	      => "Tiada Alamat e-mail", #"No e-mail address",

"noemailtext" => "Pengguna ini tidak memasukkan alamat e-mail yang sah, atau memilih untuk tidak menerima e-mail dari pengguna yang lain.",
#"This user has not specified a valid e-mail address, or has chosen not to receive e-mail from other users.",

"emailfrom"	      => "Dari", #"From",
"emailto"	      => "Ke",	#"To",
"emailsubject"	      => "Perihal",  #"Subject",
"emailmessage"	      => "Pesan", #"Message",
"emailsend"	      => "Kirimkan", #"Send",
"emailsent"	      => "e-mail terkirim", #"E-mail sent",
"emailsenttext" => "e-mail Anda telah dikirimkan.", #"Your e-mail message has been sent.",

# Watchlist 
# 
"watchlist"	      => "Daftar Pengamatan",
"watchlistsub"	      => "(untuk pengguna \"$1\")",   #"(for user \"$1\")",
"nowatchlist" => "Tiada apa-apa dalam daftar pengamatan.",    #"You have no items on your watchlist.",
"watchnologin"	      => "Belum login", #"Not logged in",
"watchnologintext"    => "Anda harus <a href=\"{{localurl:Istimewa:Userlogin}}\">login</a> 
untuk mengubah daftar pengamatan.",
"addedwatch"  => "Tambahkan ke daftar pengamatan",
"addedwatchtext" => "Halaman \"$1\" telah ditambahkan ke [[{{ns:-1}}:Daftar_pengamatan|daftar pengamatan]]. 
Pada masa yang akan datang, semua perubahan pada halaman tersebut berikut halaman pembicaraannya 
akan didaftar di sini, dan halaman tersebut akan <b>dicetak tebal</b> dalam 
[[Istimewa:Recentchanges|daftar perubahan terkini]] supaya lebih mudah dilihat.</p> 

<p>Apabila nanti Anda ingin menghapus halaman dari daftar pengamatan, klik \"Berhenti mengamati\" pada batang sebelah.",
"removedwatch"	      => "Telah dihapus dari daftar pengamatan",   #"Removed from watchlist",
"removedwatchtext" => "Halaman \"$1\" telah dihapus dari daftar pengamatan.",
'watch' => 'Amati',
"watchthispage"       => "Tambahkan ke daftar pengamatan",
'unwatch' => 'Berhenti mengamati',
"unwatchthispage" => "Berhenti mengamati", #"Stop watching",
"notanarticle"	      => "Bukan sebuah artikel",
"watchnochange" => "Tiada dari item-item yang Anda amati telah berubah dalam jangka waktu yang ditampilkan.",
"watchdetails"		      => "($1 halaman diamati [tidak termasuk halaman pembicaraan]; 
$2 halaman berubah sejak cutoff; $3... 
<a href='$4'>lihat dan sunting daftar lengkap</a>.)",
"watchmethod-recent"=> "periksa daftar perubahan terkini terhadap halaman yang diamati",
"watchmethod-list"    => "periksa halaman yang diamati terhadap perubahan terkini",
"removechecked"       => "Hapus item yang diberi tanda cek dari daftar pengamatan",
"watchlistcontains" => "Daftar pengamatan Anda berisi $1 halaman.",
"watcheditlist"		      => "Berikut ini adalah daftar halaman-halaman yang 
Anda amati. Untuk menghapus halaman dari daftar pengamatan Anda, berikan tanda cek pada kotak cek di sebelah judul halaman yang ingin Anda hapus, lalu klik tombol 'hapus yang dicek' yang terletak di bagian bawah layar.",
"removingchecked"     => "Menghapus item-item yang diminta dari daftar pengamatan Anda...",
"couldntremove"       => "Tidak dapat menghapus item '$1'...",
"iteminvalidname"     => "Ada masalah dengan item '$1' (namanya tidak sah)...",
"wlnote"	      => "Di bawah ini adalah daftar $1 perubahan terakhir dalam <b>$2</b> jam terakhir.",
"wlshowlast"	      => "Tampilkan $1 jam $2 hari $3 terakhir",
"wlsaved"	      => "Ini adalah versi tersimpan dari daftar pengamatan Anda.",

# Delete/protect/revert 
# 
"deletepage"  => "Hapus halaman", #"Delete page",
"confirm"     => "Konfirmasikan", #"Confirm",
"excontent" => "isi sebelumnya:",
"exbeforeblank" => "isi sebelum dikosongankan adalah:",
"exblank" => "halaman kosong",
"confirmdelete" => "Konfirmasi penghapusan", #"Confirm delete",
"deletesub"	      => "(Menghapus \"$1\")", #"(Deleting \"$1\")",
"historywarning" => "Peringatan: Halaman yang ingin Anda hapus mempunyai sejarah:",
"confirmdeletetext" => "Anda akan menghapus halaman atau 
gambar ini secara permanen berikut semua sejarahnya dari pangkalan data.  
Pastikan bahwa Anda memang ingin berbuat demikian, mengetahui segala 
akibatnya, dan apa yang Anda lakukan ini adalah sejalan dengan 
[[{{ns:4}}:Kebijaksanaan|kebijaksanaan Wikipedia]].",
"confirmcheck"	      => "Ya, saya benar-benar ingin menghapus halaman ini", #"Yes, I really want to delete this.",
"actioncomplete" => "Proses selesai", #"Action complete",
"deletedtext" => "\"$1\" telah dihapus. 
Lihat $2 untuk catatan terkini halaman yang telah dihapus.",
"deletedarticle" => "menghapus \"$1\"", #"deleted \"$1\"",
"dellogpage"  => "Catatan_Penghapusan", #"Deletion_log",
"dellogpagetext" => "Di bawah ini adalah daftar terkini halaman yang telah dihapus. 
Semua waktu yang ditunjukkan adalah waktu server (UTC). 
<ul> 
</ul> 
",
"deletionlog" => "catatan penghapusan", #"deletion log",
"reverted"    => "Dikembalikan ke revisi sebelumnya", #"Reverted to earlier revision",
"deletecomment"       => "Alasan penghapusan", #"Reason for deletion",
"imagereverted" => "Berhasil mengembalikan ke revisi sebelumnya", #"Revert to earlier version was successful.",
"rollback"    => "Rollback penyuntingan",
'rollback_short' => 'Rollback',
"rollbacklink"	      => "rollback",
"rollbackfailed" => "gagal melakukan rollback",
"cantrollback"	      => "Tidak dapat mengembalikan penyuntingan; pengguna terakhir adalah satu-satunya penulis artikel ini.",
"alreadyrolled"       => "Tidak dapat melakukan rollback terhadap penyuntingan terakhir dari [[$1]] 
oleh [[Pengguna:$2|$2]] ([[Bicara_pengguna:$2|Pembicaraan); orang lain telah mengedit atau menlakukan rollback terhadap artikel tersebut. 

Edit terakhir oleh [[Pengguna:$3|$3]] ([[Bicara_pengguna:$3|Pembicaraan]]). ",
#   only shown if there is an edit comment 
"editcomment" => "Komentar penyuntingan adalah: \"<i>$1</i>\".",
"revertpage"  => "Dikembalikan oleh $1",

"protectlogpage" => "Catatan_Perlindungan",
"protectlogtext" => "Di bawah ini adalah daftar catatan perlindungan/penghilangan perlindungan halaman. 
Lihat [[{{ns:4}}:Protected page]] untuk informasi lebih lanjut.",
"protectedarticle" => "melindungi [[$1]]",
"unprotectedarticle" => "menghilangkan perlindungan [[$1]]",
"protectsub" =>"(Melindungi \"$1\")",
"confirmprotecttext" => "Apakah Anda benar-benar ingin melindungi halaman ini?",
"confirmprotect" => "Konfirmasi perlindungan",
"protectcomment" => "Alasan perlindungan",
"unprotectsub" =>"(Menghilangkan perlindungan terhadap \"$1\")",
"confirmunprotecttext" => "Apakah Anda benar-benar ingin menghilangkan perlindungan terhadap halaman ini?",
"confirmunprotect" => "Konfirmasi penghilangan perlindungan",
"unprotectcomment" => "Alasan penghilangan perlindungan",
"protectreason" => "(berikan sebuah alasan)",

# Undelete 
"undelete" => "Kembalikan halaman yang telah dihapus", #"Restore deleted page",
"undeletepage" => "Lihat dan kembalikan halaman yang telah dihapus", #"View and restore deleted pages",
"undeletepagetext" => "Halaman-halaman berikut ini telah dihapus tapi masih ada di dalam arsip dan dapat dikembalikan. Arsip tersebut mungkin akan dibersihkan secara berkala.", #"The following pages have been deleted but are still in the archive and can be restored. The archive may be periodically cleaned out.",
"undeletearticle" => "Kembalikan halaman yang telah dihapus", #"Restore deleted article",
"undeleterevisions" => "$1 revisi diarsipkan", #"$1 revisions archived",
"undeletehistory" => "Jika Anda mengembalikan halaman tersebut, semua revisi akan 
dikembalikan ke dalam sejarah. 
Jika sebuah halaman baru dengan nama yang sama telah dibuat sejak penghapusan, 
revisi yang telah dikembalikan akan kelihatan dalam sejarah dahulu, dan 
revisi terkini dari halaman tersebut tidak akan ditimpa secara otomatis.", #"If you restore the page, all revisions will be restored to the history.If a new page with the same name has been created since the deletion, the restored revisions will appear in the prior history, and the current revision of the live page will not be automatically replaced.",
"undeleterevision" => "Revisi yang telah dihapus per $1", #"Deleted revision as of $1",
"undeletebtn" => "Kembalikan!", #"Restore!",
"undeletedarticle" => "\"$1\" telah dikembalikan", #"restored \"$1\"",
"undeletedtext"   => "Halaman [[$1]] berhasil dikembalikan. 
Lihat [[{{ns:4}}:Catatan_Penghapusan]] untuk catatan terkini penghapusan dan pengembalian halaman.",

# Contributions 
# 
"contributions"       => "Sumbangan pengguna", #"User contributions",
"mycontris"   => "Sumbangan saya", #"My contributions",
"contribsub"  => "Untuk $1", #"For $1",
"nocontribs"  => "Tidak ada perubahan yang cocok dengan kriteria-kriteria ini.", #"No changes were found matching these criteria.",
"ucnote"      => "Di bawah ini adalah <b>$1</b> perubahan terakhir pengguna dalam <b>$2</b> hari terakhir.", #"Below are this user's last <b>$1</b> changes in the last <b>$2</b> days.",
"uclinks"     => "Tampilkan $1 perubahan terkini; tampilkan $2 hari terakhir", #"View the last $1 changes; view the last $2 days.",
"uctop"		      => " (atas)" , 

# What links here 
# 
"whatlinkshere"       => "Pautan ke halaman ini",
"notargettitle"       => "Tiada Sasaran", #"No target",
"notargettext"	      => "Anda tidak menentukan halaman atau pengguna sasaran untuk melaksanakan fungsi ini.",
"linklistsub" => "(Daftar pautan)", #"(List of links)",
"linkshere"   => "Halaman-halaman berikut ini berpaut ke sini:", #"The following pages link to here:",
"nolinkshere" => "Tiada halaman berpaut ke sini.", #"No pages link to here.",
"isredirect"  => "halaman peralihan", #"redirect page",

# Block/unblock IP 
# 
"blockip"     => "Blokir IP", #"Block IP",
"blockiptext" => "Gunakan formulir di bawah untuk memblokir 
kemampuan menulis dari sebuah alamat IP atau pengguna tertentu. 
Ini perlu dilakukan untuk mencegah vandalisme, dan sejalan dengan 
[[{{ns:4}}:Kebijaksanaan|kebijaksanaan Wikipedia]]. Masukkan 
alasan Anda di bawah (contohnya mengambil halaman tertentu yang 
telah dirusak).",
"ipaddress"   => "Alamat IP atau pengguna", #"IP Address or Username",
"ipbexpiry"   => "Kadaluwarsa",
"ipbreason"   => "Alasan", #"Reason",
"ipbsubmit"   => "Kirimkan", #"Submit",
"badipaddress"	      => "Format alamat IP atau nama pengguna salah.", #"The IP address or username is badly formed.",
"noblockreason" => "Anda harus memberikan alasan untuk pemblokiran tersebut.", #"You must supply a reason for the block.",
"blockipsuccesssub" => "Pemblokiran sukses", #"Block succeeded",
"blockipsuccesstext" => "Alamat IP atau pengguna \"$1\" telah diblokir. 
<br />Lihat [[Istimewa:Ipblocklist|Daftar IP dan pengguna diblokir]] untuk melihat kembali pemblokiran.", #"The IP address or username \"$1\" has been blocked. 
"unblockip"	      => "Hilangkan blokir terhadap alamat IP atau pengguna", #"Unblock IP address or user",
"unblockiptext"       => "Gunakan formulir di bawah untuk mengembalikan 
kemampuan menulis dari sebuah alamat IP atau pengguna yang sebelumnya telah diblokir.",
"ipusubmit"	      => "Hilangkan blokir terhadap alamat ini", #"Unblock this address",
"ipusuccess"  => "Blokir terhadap alamat IP atau pengguna \"$1\" telah dihilangkan", #"IP address or user \"$1\" unblocked",
"ipblocklist" => "Daftar alamat IP dan pengguna yang diblokir", #"List of blocked IP addresses and users",
"blocklistline"       => "$1, $2 memblokir $3", #"$1, $2 blocked $3",
"blocklink"	      => "blokir", #"block",
"unblocklink" => "hilangkan blokir", #"unblock",
"contribslink"	      => "sumbangan",
"autoblocker" => "Diblokir secara otomatis karena Anda berbagi alamat IP dengan \"$1\". Alasan \"$2\".",
"blocklogpage"	      => "Catatan_Pemblokiran",
"blocklogentry"       => 'memblokir "$1" dengan waktu kadaluwarsa pada $2',
"blocklogtext"	      => "Ini adalah catatan tindakan pemblokiran dan penghilangan blokir terhadap pengguna. 
Alamat IP yang diblokir secara otomatis tidak terdapat di dalam daftar ini. Lihat [[Istimewa:Ipblocklist|daftar alamat IP yang diblokir]] untuk 
daftar blokir terkini yang efektif.",
"unblocklogentry"     => 'menghilangkan blokir "$1"',
"range_block_disabled"	      => "Kemampuan sysop untuk membuat blokir blok IP dimatikan.",
"ipb_expiry_invalid"  => "Waktu kadaluwarsa tidak sah.",
"ip_range_invalid"    => "IP range tidak sah.\n",
"proxyblocker"	      => "Pemblokir proxy",
"proxyblockreason"    => "Alamat IP Anda telah diblokir karena alamat IP Anda adalah proxy terbuka. Silakan hubungi penyedia jasa internet Anda atau dukungan teknis dan beritahukan mereka masalah keamanan serius ini.",
"proxyblocksuccess"   => "Selesai.\n",

# Make sysop 
"makesysoptitle"      => "Buat Seorang Pengguna Menjadi Sysop",
"makesysoptext"		      => "Formulir ini digunakan oleh para birokrat untuk menjadikan pengguna biasa menjadi seorang administrator. 
Ketikkan nama pengguna yang dimaksud dalam kotak dan tekan tombol untuk menjadikan pengguna tersebut seorang administrator",
"makesysopname"		      => "Nama pengguna:",
"makesysopsubmit"     => "Jadikan sysop",
"makesysopok"	      => "<b>Pengguna \"$1\" sekarang adalah seorang sysop</b>",
"makesysopfail"		      => "<b>Pengguna \"$1\" tidak dapat dijadikan sysop. (Apakah Anda mengetikkan namanya dengan benar?)</b>",
"setbureaucratflag" => "Atur flag birokrat",
"bureaucratlog"		      => "Catatan_Birokrat",
"bureaucratlogentry"  => "Hak-hak untuk pengguna \"$1\" atur \"$2\"",
"rights"		      => "Hak-hak:",
"set_user_rights"     => "Atur hak-hak pengguna",
"user_rights_set"     => "<b>Hak-hak untuk pengguna \"$1\" diperbarui</b>",
"set_rights_fail"     => "<b>Hak-hak untuk pengguna \"$1\" tidak dapat diatur. (Apakah Anda mengetikkan namanya dengan benar?)</b>",
"makesysop"	    => "Buat seorang pengguna menjadi sysop",

# Developer tools 
# 
"lockdb"	      => "Kunci pangkalan data", #"Lock database",
"unlockdb"	      => "Buka kunci pangkalan data", #"Unlock database",
"lockdbtext"  => "Mengunci pangkalan data akan menghentikan kemampuan semua pengguna untuk 
menyunting halaman, mengubah konfigurasi pengguna, menyunting daftar pengamatan mereka, 
dan hal-hal lain yang memerlukan perubahan terhadap pangkalan data. 
Pastikan bahwa ini adalah yang ingin Anda lakukan, dan bahwa Anda akan membuka 
kunci pangkalan data setelah pemeliharaan selesai.",
"unlockdbtext"	      => "Membuka kunci pangkalan data akan mengembalikan kemampuan semua pengguna 
untuk menyunting halaman, mengubah konfigurasi pengguna, menyunting daftar pengamatan mereka, 
dan hal-hal lain yang memerlukan perubahan terhadap pangkalan data.  
Pastikan bahwa ini adalah yang ingin Anda lakukan.",
"lockconfirm" => "Ya, saya memang ingin mengunci pangkalan data.", #"Yes, I really want to lock the database.",
"unlockconfirm"       => "Ya, saya memang ingin membuka kunci pangkalan data.", #"Yes, I really want to unlock the database.",
"lockbtn"	      => "Kunci pangkalan data", #"Lock database",
"unlockbtn"	      => "Buka kunci pangkalan data", #"Unlock database",
"locknoconfirm" => "Anda tidak memberikan tanda cek pada kotak konfirmasi.", #"You did not check the confirmation box.",
"lockdbsuccesssub" => "Penguncian pangkalan data berhasil", #"Database lock succeeded",
"unlockdbsuccesssub" => "Pembukaan kunci pangkalan data berhasil", #"Database lock removed",
"lockdbsuccesstext" => "Pangkalan data telah dikunci. 
<br />Pastikan Anda membuka kuncinya setelah pemeliharaan selesai.",
"unlockdbsuccesstext" => "Kunci pangkalan data telah dibuka.", #"The Wikipedia database has been unlocked.",

# SQL query 
# 
"asksql"	      => "Kueri SQL", #"SQL query",
"asksqltext"  => "Gunakan formulir di bawah untuk membuat kueri langsung 
terhadap pangkalan data. 
Gunakan tanda kutip tunggal ('seperti ini') untuk membatasi literal 
string.  Fungsi ini sering membebani server, jadi silakan gunakan 
fungsi ini dengan hemat.",
"sqlislogged" => "Perhatikan bahwa semua kueri akan dicatat.",
"sqlquery"	      => "Masukkan kueri", #"Enter query",
"querybtn"	      => "Kirimkan kueri", #"Submit query",
"selectonly"  => "Kueri selain dari \"SELECT\" hanya dapat dilakukan oleh 
pengembang Wikipedia.", #"Queries other than \"SELECT\" are restricted to 
"querysuccessful" => "Kueri berhasil", #"Query successful",

# Move page 
# 
"movepage"    => "Pindahkan Halaman",  #"Move page",
"movepagetext"	      => "Formulir di bawah ini digunakan untuk mengubah nama suatu halaman dan memindahkan semua data sejarah ke nama baru. 
Judul yang lama akan menjadi halaman peralihan menuju judul yang baru. 
Pautan kepada judul lama tidak akan berubah. Pastikan untuk memeriksa 
terhadap peralihan halaman yang rusak atau berganda setelah pemindahan. 
Anda bertanggung jawab untuk memastikan bahwa pautan terus menyambung 
ke halaman yang seharusnya. 

Perhatikan bahwa halaman '''tidak''' akan dipindah apabila telah ada halaman di pada judul yang baru, kecuali bila halaman tersebut kosong atau merupakan 
halaman peralihan dan tidak mempunyai sejarah penyuntingan. Ini berarti Anda 
dapat mengubah nama halaman kembali seperti semula apabila Anda membuat 
kesalahan, dan Anda tidak dapat menimpa halaman yang telah ada. 

<b>PERINGATAN!</b> 
Ini dapat mengakibatkan perubahan yang tak terduga dan drastis	
bagi halaman yang populer. Pastikan Anda mengerti konsekuensi 
dari perbuatan ini sebelum melanjutkan.",   
"movepagetalktext" => "Halaman pembicaraan yang berkaitan, jika ada, akan dipindahkan secara otomatis '''kecuali apabila:''' 
*Anda memindahkan halaman melintasi namespace, 
*Sebuah halaman pembicaraan yang tidak kosong telah ada di bawah judul baru, atau 
*Anda tidak memberi tanda cek pada kotak cek di bawah ini. 

In those cases, you will have to move or merge the page manually if desired.",
"movearticle" => "Pindahkan halaman", #"Move page",
"movenologin" => "Belum login", #"Not logged in",
"movenologintext" => "Anda harus menjadi pengguna terdaftar dan telah <a href=\"{{localurl:Istimewa:Userlogin}}\">login</a> 
untuk memindahkan halaman.",
"newtitle"    => "Ke judul baru",   #"To new title",
"movepagebtn" => "Pindahkan halaman", #"Move page",
"pagemovedsub"	      => "Pemindahan berhasil",  #"Move succeeded",
"pagemovedtext" => "Halaman \"[[$1]]\" dipindahkan ke \"[[$2]]\".",   #"Page \"[[$1]]\" moved to \"[[$2]]\".",
"articleexists" => "Halaman dengan nama tersebut telah ada 
atau nama yang dipilih tidak sah. Silakan pilih nama lain.",
"talkexists"  => "Halaman tersebut berhasil dipindahkan, tetapi 
halaman pembicaraan dari halaman tersebut tidak dapat dipindahkan karena 
telah ada halaman pembicaraan pada judul yang baru. Silakan gabungkan 
halaman-halaman pembicaraan tersebut secara manual.",
"movedto"	      => "dipindahkan ke",  #"moved to",
"movetalk"	      => "Pindahkan halaman \"pembicaraan\" juga, jika mungkin.",   #"Move \"talk\" page as well, if applicable.",
"talkpagemoved" => "Halaman pembicaraan yang berkaitan juga ikut dipindahkan.",    #"The corresponding talk page was also moved.",
"talkpagenotmoved" => "Halaman pembicaraan yang berkaitan <strong>tidak</strong> dipindahkan.",
"1movedto2"	      => "$1 dipindahkan ke $2",

# Export 

"export"	      => "Ekspor halaman",
"exporttext"  => "Anda dapat mengekspor teks dan sejarah penyuntingan dari sebuah halaman 
tertentu atau sejumlah halaman yang dibungkus dalam XML tertentu; hasil ekspor ini kemudian 
dapat diimpor ke wiki lain dengan perangkat lunak MediaWiki, diubah, atau hanya disimpan 
untuk kepentingan Anda sendiri.",
#You can export the text and editing history of a particular 
#page or set of pages wrapped in some XML; this can then be imported into another 
#wiki running MediaWiki software, transformed, or just kept for your private 
#amusement.",
"exportcuronly"       => "Hanya ekspor revisi sekarang, tidak seluruh sejarah", #"Include only the current revision, not the full history",

# Namespace 8 related 

"allmessages" => "Semua pesan sistem", #"All system messages",
"allmessagestext"     => "Ini adalah daftar semua pesan sistem yang tersedia dalam namespace MediaWiki:.", #"This is a list of all system messages available in the MediaWiki: namespace.",

# Thumbnails 

"thumbnail-more"      => "Perbesar", #"Enlarge",
"missingimage"		      => "<b>Gambar hilang</b><br /><i>$1</i>\n", #"<b>Missing image</b><br /><i>$1</i>\n",

# Istimewa:Import 
"import"      => "Impor halaman", #"Import pages",
"importtext"  => "Silakan ekspor file dari wiki asal menggunakan utilitas Istimewa:Export, simpan ke disk, dan unggahkan ke sini.", #"Please export the file from the source wiki using the Istimewa:Export utility, save it to your disk and upload it here.",
"importfailed"	      => "Impor gagal: $1", #"Import failed: $1",
"importnotext"	      => "Kosong atau tidak ada teks", #"Empty or no text",
"importsuccess"       => "Impor sukses!", #"Import succeeded!",
"importhistoryconflict" => "Terjadi konflik revisi sejarah (mungkin pernah mengimpor halaman ini sebelumnya)", #"Conflicting history revision exists (may have imported this page before)",

# Keyboard access keys for power users 
'accesskey-article' => 'a',
'accesskey-talk' => 't',
'accesskey-edit' => 'e',
'accesskey-addsection' => '+',
'accesskey-viewsource' => 'e',
'accesskey-history' => 'h',
'accesskey-protect' => '=',
'accesskey-delete' => 'd',
'accesskey-undelete' => 'd',
'accesskey-move' => 'm',
'accesskey-watch' => 'w',
'accesskey-unwatch' => 'w',
'accesskey-watchlist' => 'l',
'accesskey-userpage' => '.',
'accesskey-anonuserpage' => '.',
'accesskey-mytalk' => 'n',
'accesskey-anontalk' => 'n',
'accesskey-preferences' => '',
'accesskey-mycontris' => 'y',
'accesskey-login' => 'o',
'accesskey-logout' => 'o',
'accesskey-search' => 'f',
'accesskey-mainpage' => 'z',
'accesskey-portal' => '',
'accesskey-randompage' => 'x',
'accesskey-currentevents' => '',
'accesskey-sitesupport' => '',
'accesskey-help' => '',
'accesskey-recentchanges' => 'r',
'accesskey-recentchangeslinked' => 'c',
'accesskey-whatlinkshere' => 'b',
'accesskey-specialpages' => 'q',
'accesskey-specialpage' => '',
'accesskey-upload' => 'u',
'accesskey-minoredit' => 'i',
'accesskey-save' => 's',
'accesskey-preview' => 'p',
'accesskey-contributions' => '',
'accesskey-emailuser' => '',

# tooltip help for the main actions 
'tooltip-atom'	=> 'Atom feed untuk halaman ini',
'tooltip-article' => 'Lihat artikel [alt-a]',
'tooltip-talk' => 'Diskusi mengenai artikel [alt-t]',
'tooltip-edit' => 'Anda dapat menyunting halaman ini. Silakan pakai tombol pratilik sebelum menyimpan. [alt-e]',
'tooltip-addsection' => 'Tambahkan komenter ke halaman ini. [alt-+]',
'tooltip-viewsource' => 'Halaman ini dilindungi. Anda hanya dapat melihat sumbernya. [alt-e]',
'tooltip-history' => 'Versi-versi sebelumnya dari halaman ini, [alt-h]',
'tooltip-protect' => 'Lindungi halaman ini [alt-=]',
'tooltip-delete' => 'Hapus halaman ini [alt-d]',
'tooltip-undelete' => "Kembalikan $1 suntingan yang dihapus ke halaman ini [alt-d]",
'tooltip-move' => 'Pindahkan halaman ini [alt-m]',
'tooltip-nomove' => 'Anda tidak mempunyai izin untuk memindahkan halaman ini',
'tooltip-watch' => 'Tambahkan halaman ini ke daftar pengamatan Anda [alt-w]',
'tooltip-unwatch' => 'Hapus halaman ini dari daftar pengamatan Anda [alt-w]',
'tooltip-watchlist' => 'Daftar halaman yang Anda amati perubahannya. [alt-l]',
'tooltip-userpage' => 'Halaman pengguna saya [alt-.]',
'tooltip-anonuserpage' => 'Halaman pengguna untuk IP Anda [alt-.]',
'tooltip-mytalk' => 'Halaman pembicaraan saya [alt-n]',
'tooltip-anontalk' => 'Diskusi mengenai penyuntingan oleh alamat IP ini [alt-n]',
'tooltip-preferences' => 'Konfigurasi saya',
'tooltip-mycontris' => 'Daftar sumbangan saya [alt-y]',
'tooltip-login' => 'Anda disarankan untuk login, meskipun hal itu tidak diwajibkan. [alt-o]',
'tooltip-logout' => 'Tombol mulai [alt-o]',
'tooltip-search' => 'Cari dalam wiki ini [alt-f]',
'tooltip-mainpage' => 'Kunjungi Halaman Utama [alt-z]',
'tooltip-portal' => 'Tentang proyek ini, apa yang dapat Anda lakukan, di mana mencari sesuatu',
'tooltip-randompage' => 'Muat sebuah halaman acak [alt-x]',
'tooltip-currentevents' => 'Cari informasi latar belakang mengenai kejadian terkini',
'tooltip-sitesupport' => 'Dukung {{SITENAME}}',
'tooltip-help' => 'Tempat untuk mencari tahu.',
'tooltip-recentchanges' => 'Daftar perubahan terkini dalam wiki ini. [alt-r]',
'tooltip-recentchangeslinked' => 'Perubahan terkini dari halaman yang berpaut ke halaman ini [alt-c]',
'tooltip-whatlinkshere' => 'Daftar semua halaman wiki yang berpaut ke sini [alt-b]',
'tooltip-specialpages' => 'Daftar semua halaman istimewa [alt-q]',
'tooltip-upload' => 'Unggahkan gambar atau file media [alt-u]',
'tooltip-specialpage' => 'Halaman ini adalah halaman istimewa, Anda tidak dapat mengeditnya.',
'tooltip-minoredit' => 'Tandai ini sebagai penyuntingan kecil [alt-i]',
'tooltip-save' => 'Simpan perubahan Anda [alt-s]',
'tooltip-preview' => 'Pratilik perubahan Anda -- mohon gunakan ini sebelum menyimpan! [alt-p]',
'tooltip-contributions' => 'Lihat daftar sumbangan pengguna ini',
'tooltip-emailuser' => 'Kirimkan e-mail kepada pengguna ini',
'tooltip-rss' => 'RSS feed untuk halaman ini',

# Metadata 
"nodublincore" => "Metadata Dublin Core RDF dimatikan untuk server ini.", #"Dublin Core RDF metadata disabled for this server.",
"nocreativecommons" => "Metadata Creative Commons RDF dimatikan untuk server ini.", #"Creative Commons RDF metadata disabled for this server.",
"notacceptable" => "Server wiki tidak dapat menyediakan data dalam format yang dapat dibaca oleh client Anda.", #"The wiki server can't provide data in a format your client can read.",

# Attribution 

"anonymous" => "Pengguna(-pengguna) anonim dari $wgSitename", #"Anonymous user(s) of $wgSitename",
"siteuser" => "Pengguna $wgSitename $1",
"lastmodifiedby" => "Halaman ini terakhir kali diubah $1 oleh $2.", #"This page was last modified $1 by $2.",
"and" => "dan",
"othercontribs" => "Didasarkan pada karya $1.", #"Based on work by $1.",
"siteusers" => "Pengguna(-pengguna) $wgSitename $1", #"$wgSitename user(s) $1" 
'creditspage' => 'Penghargaan halaman',
'nocredits' => 'Tidak ada informasi penghargaan yang tersedia untuk halaman ini.',

# Spam protection

'spamprotectiontitle' => 'Filter Perlindungan Spam',
'spamprotectiontext' => 'Halaman yang anda inginkan untuk disimpan diblokir oleh filter spam. Ini mungkin disebabkan oleh pautan ke situs luar.

Anda dapat memeriksa regular expression berikut terhadap pola-pola yang diblokir:',
'subcategorycount' => "Ada $1 subkategori untuk kategori ini.",
'categoryarticlecount' => "Ada $1 artikel dalam kategori ini.",
'usenewcategorypage' => "1\n\nUbah karakter pertama menjadi \"0\" untuk mematikan tata letak halaman kategori yang baru.",

# Info page
"infosubtitle" => "Informasi untuk halaman",
"numedits" => "Jumlah penyuntingan (artikel): ",
"numtalkedits" => "Jumlah penyuntingan (halaman diskusi): ",
"numwatchers" => "Jumlah pengamat: ",
"numauthors" => "Jumlah pengarang yang berbeda (artikel): ",
"numtalkauthors" => "Jumlah pengarang yang berbeda (halaman diskusi): ",

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

	function getNsText( $index ) {
		global $wgNamespaceNamesId;
		return $wgNamespaceNamesId[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesId;

		foreach ( $wgNamespaceNamesId as $i => $n ) {
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

	function getMathNames() {
		global $wgMathNamesId;
		return $wgMathNamesId;
	}
	
	function getDateFormats() {
		global $wgDateFormatsId;
		return $wgDateFormatsId;
	}

	function getUserToggles() {
		global $wgUserTogglesId;
		return $wgUserTogglesId;
	}

	function getMonthName( $key )
	{
		global $wgMonthNamesId;
		return $wgMonthNamesId[$key-1];
	}

	/* by default we just return base form */
	function getMonthNameGen( $key )
	{
		global $wgMonthNamesId;
		return $wgMonthNamesId[$key-1];
	}
	
	function getMonthRegex()
	{
		global $wgMonthNamesId;
		return implode( "|", $wgMonthNamesId );
	}

	function getMonthAbbreviation( $key )
	{
		global $wgMonthAbbreviationsId;
		return $wgMonthAbbreviationsId[$key-1];
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesId;
		return $wgWeekdayNamesId[$key-1];
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesId;
		return $wgValidSpecialPagesId;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesId;
		return $wgSysopSpecialPagesId;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesId;
		return $wgDeveloperSpecialPagesId;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesId;
		if( isset( $wgAllMessagesId[$key] ) ) {
			return $wgAllMessagesId[$key];
		} else {
			return Language::getMessage( $key );
		}
	}
}

?>
