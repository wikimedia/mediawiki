<?php

# This localisation is based on a file kindly donated by the folks at MIMOS
# http://www.asiaosc.org/enwiki/page/Knowledgebase_Home.html

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesMs = array(
	-2	=> "Media",
	-1	=> "Istimewa", #Special
	0	=> "",
	1	=> "Perbualan",#Talk
	2	=> "Pengguna",#User
	3	=> "Perbualan_Pengguna",#User_talk
	4	=> "Wikipedia",#Wikipedia
	5	=> "Perbualan_Wikipedia",#Wikipedia_talk
	6	=> "Imej",#Image
	7	=> "Imej_Perbualan",#Image_talk
	8	=> "MediaWiki",
	9	=> "MediaWiki_Perbualan",
	10  => "Template",
	11  => "Template_talk"

) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsMs = array(
	"Tiada", "Tetap sebelah kiri", "Tetap sebelah kanan", "Berubah-ubah sebelah kiri"
);

/* private */ $wgSkinNamesMs = array(
	'standard' => "Standard",
	'nostalgia' => "Nostalgia",
	'cologneblue' => "Cologne Blue",
	'smarty' => "Paddington",
	'montparnasse' => "Montparnasse",
	'davinci' => "DaVinci",
	'mono' => "Mono",
	'monobook' => "MonoBook",
 "myskin" => "MySkin"
);

/* private */ $wgDateFormatsMs = array(
	"Tiada pilihan", # "No preference",
	"15 Januari 2001", # "Januari 15, 2001",
	"15 Januari 2001", # "2001 Januari 15"
);


/* private */ $wgBookstoreListMs = array(
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);


# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesMs = array(
	"Userlogin"		=> "Laluan masuk pengguna", #"Login Pengguna",
	"Userlogout"	=> "Laluan keluar pengguna", #"Logout Pengguna",
	"Preferences"	=> "Ubah konfigurasi saya", #"Set my user preferences",
	"Watchlist"		=> "Senarai pilihan",#My watchlist",
	"Recentchanges" => "Halaman terkini setelah diubah",#Recently updated pages",
	"Upload"		=> "Muatnaik fail imej", #"Upload image files"
	"Imagelist"		=> "Senarai imej",#Image list",
	"Listusers"		=> "Senarai pengguna",#List of users",
	"Statistics"	=> "Statistik halaman",#site statistics",
	"Randompage"	=> "Halaman Rawak",#Random article",

	"Lonelypages"	=> "Halaman yatim", # pages",
	"Unusedimages"	=> "Imej yatim", #"Orphaned images",
	"Popularpages"	=> "Halaman popular", #"Popular pages",
	"Wantedpages"	=> "Halaman yang paling dikehendaki", #"Most wanted pages",
	"Shortpages"	=> "Halaman pendek", #"Short pages",
	"Longpages"		=> "Halaman panjang", #"Long pages",
	"Newpages"		=> "Halaman yang baru dicipta", #"Newly created pages",
	"Ancientpages"	=> "Rencana tertua", #Oldest articles
	"Intl"		=> "Pautan antarabahasa", #Interlanguage links
	"Allpages"		=> "Semua halaman mengikut tajuk", #"All pages by title",

	"Ipblocklist"	=> "IP dan pengguna yang diblok", #"Blocked IPs and users",
	"Maintenance" => "Halaman penyelenggaraan", #"Maintenance page",
	"Specialpages"  => "",
	"Contributions" => "",
	"Emailuser"		=> "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"		=> "",
	"Booksources"	=> "Sumber buku luaran", #"External book sources"
	"Export"	=> "XML export",
	"Version"	=> "Version",
);

/* private */ $wgSysopSpecialPagesMs = array(
	"Blockip"		=> "Sekat IP dan pengguna", #"Block IP or user",
	"Asksql"		=> "Kueri terhadap pangkalan data", #"Query the database",
	"Undelete"		=> "Papar dan masukkan semula halaman yang telah dibuang", #"View and restore deleted pages",
);

/* private */ $wgDeveloperSpecialPagesEn = array(
	"Lockdb"		=> "Membuat pangkalan data hanya untuk dibaca", #"Make database read-only",
	"Unlockdb"		=> "Memperbaharui cara kemasukan pangkalan data", #"Restore database write access",
);

/* private */ $wgAllMessagesMs = array(
'special_version_prefix' => '',
'special_version_postfix' => '',

# User Toggles

"tog-hover"		=> "Papar hoverbox atas pautan", #"Show hoverbox over links",
"tog-underline" => "Pautan bergaris", #"Underline links",
"tog-highlightbroken" => "Pautan bertanda ke topik kosong",
#TODO: <a href=\"\" class=\"new\">like this</a> (alternative: like this<a href=\"\" class=\"internal\">?</a>)
"tog-justify"	=> "Justifikasikan perenggan", #"Justify paragraphs",
"tog-hideminor" => "Sembunyi perubahan minor dalam perubahan terkini", #"Hide minor edits in recent changes",
"tog-usenewrc" => "Peningkatan terbaru (bukan untuk semua pelayar)",
"tog-numberheadings" => "Auto-number headings",
"tog-showtoolbar" => "Show edit toolbar",
"tog-editsection"=>"Papar pautan untuk sunting seksyen individu",
"tog-showtoc"=>"Papar jadual kandungan bagi rencana melebihi 3 tajuk",# "Show table of contents for articles with more than 3 headings",
"tog-rememberpassword" => "Ingat kata laluan bagi setiap sessi", #"Remember password across sessions",
"tog-editwidth" => "Kotak sunting telah lebar", #"Edit box has full width",
"tog-editondblclick" => "Sunting halaman dengan klik berganda (JavaScript)", #"Edit pages on double click (JavaScript)"
"tog-watchdefault" => "Tambah halaman yang anda sunting pada senarai perhati",# "Add pages you edit to your watchlist",
"tog-minordefault" => "Tanda semua suntingan ringkas secara ingkar",# "Mark all edits minor by default"
"tog-previewontop" => "Papar pratonton sebelum kotak sunting dan bukan selepasnya", #Show preview before edit box and not after it",
"tog-nocache" => "Matikan simpanan laman",

# Dates

'sunday' => 'Ahad',
'monday' => 'Isnin',
'tuesday' => 'Selasa',
'wednesday' => 'Rabu',
'thursday' => 'Khamis',
'friday' => 'Jumaat',
'saturday' => 'Sabtu',
'january' => 'Januari',
'february' => 'Februari',
'march' => 'Mac',
'april' => 'April',
'may_long' => 'Mei',
'june' => 'Jun',
'july' => 'Julai',
'august' => 'Ogos',
'september' => 'September',
'october' => 'Oktober',
'november' => 'November',
'december' => 'Disember',
'jan' => 'Jan',
'feb' => 'Feb',
'mar' => 'Mac',
'apr' => 'Apr',
'may' => 'May',
'jun' => 'Jun',
'jul' => 'Jul',
'aug' => 'Aug',
'sep' => 'Sep',
'oct' => 'Okt',
'nov' => 'Nov',
'dec' => 'Dis',


# Bits of text used by many pages:
#
"linktrail"		=> "/^([a-z]+)(.*)\$/sD",
"mainpage"		=> "Laman Utama Pangkalan Pengetahuan", #"Knowledgebase Home",
"mainpagetext"	        => "Aturcara Wiki berjaya dipasang.",
"about"			=> "Maklumat",
"aboutwikipedia"        => "Maklumat Wikipedia",
"aboutpage"		=> "Wikipedia:Perihal", #"Wikipedia:About",
"help"			=> "Bantuan",
"helppage"		=> "Wikipedia:Bantuan",
"wikititlesuffix"       => "Wikipedia",
"bugreports"	        => "Laporan Pepijat",
"bugreportspage"        => "Wikipedia:Laporan Pepijat",
"faq"			=> "FAQ",
"faqpage"		=> "Wikipedia:FAQ",
"edithelp"		=> "Menyunting bantuan",
"edithelppage"	        => "Wikipedia:Cara menyunting halaman",
"cancel"		=> "Batal",
"qbfind"		=> "Cari",
"qbbrowse"		=> "Baca sepintas lalu", #"Browse",
"qbedit"		=> "Sunting",
"qbpageoptions"         => "Pilihan halaman",
"qbpageinfo"	        => "Maklumat halaman",
"qbmyoptions"	        => "Pilihan saya",
"mypage"		=> "Halaman saya",
"mytalk"		=> "Perbualan saya",
"currentevents"         => "Status Terkini",
"errorpagetitle"        => "Ralat",
"returnto"		=> "Kembali ke $1.", #"Return to $1.",
"fromwikipedia"	        => "Dari Wikipedia, ensaiklopedia bebas", # "From Wikipedia, the free encylcopedia
"whatlinkshere"	        => "Halaman yang dihubungkan ke sini", #"Pages that link here",
"help"			=> "Bantuan",
"search"		=> "Cari",
"go"		        => "Pergi",
"history"		=> "Versi terdahulu",
"printableversion"      => "Versi untuk dicetak",
"editthispage"	        => "Sunting halaman ini",
"deletethispage"        => "Buang halaman ini", #"Delete this page",
"protectthispage"       => "Lindungi halaman ini", #"Protect this page",
"unprotectthispage"     => "Nyah lindung halaman ini", #"Unprotect this page",
"newpage"               => "(cipta) Halaman baru", # (create) "New page"
"talkpage"		=> "Bincang halaman ini",
"articlepage"	        => "Lihat rencana",# "View article",
"subjectpage"	        => "Halaman subjek",
"userpage"              => "Lihat laman pengguna",# "View user page",
"wikipediapage"         => "Lihat laman meta",# "View meta page",
"imagepage"             => "Papar halaman imej",
"viewtalkpage"          => "Lihat perbincangan",# "View discussion",
"otherlanguages"        => "Lain-lain bahasa",
"redirectedfrom"        => "(Dialih dari $1)", #"(Redirected from $1)",
"lastmodified"	        => "Halaman ini diubah kali terakhir pada $1.", #"The page was last modified $1.",
"viewcount"		=> "Halaman ini telah diakses sebanyak $1 kali.", #"This page has been accessed $1 times.",
"gnunote"		=> "Halaman ini tertakluk di bawah istilah <a class=internal href='$wgScriptPath/GNU_FDL'>GNU Free Documentation License</a>.", #"This page is released under the terms of the $1.",
"printsubtitle"         => "(From http://www.aposc.org)",
"protectedpage"         => "Halaman yang dilindungi", #"Protected page",
"administrators"        => "Istimewa:Listadministrators", #"Special:Listadministrators",
"sysoptitle"	        => "Kemasukan sysop diperlukan", #"Sysop access required",
"sysoptext"		=> "Hanya <a href=\"" .
  wfLocalUrl( "Istimewa:Listadministrators" ) . "\">Sistem Pentadbir</a>
yang boleh melakukannya.", #"Only the <a href=\"" .wfLocalUrl( "Special:Listadministrators" ) . "\">administrators</a>can do that.",
"developertitle"        => "Kemasukan pembangun diperlukan", # "Developer access required",
"developertext"	        => "Tindakan/arahan yg di minta/diperlukanhanya boleh di tunjukan oleh pengguna dengan status \"developer\".
Lihat $1.", # "The action you have requested can only be performed by users with \"developer\" status.See $1.",
"nbytes"		=> "$1 bait",
"go"			=> "Pergi",
"ok"			=> "OK",
"sitetitle"		=> "Wikipedia",
"sitesubtitle"	        => "Ensaiklopedia Bebas",
"retrievedfrom"         => "Diperolehi daripada \"$1\"", #"Retrieved from \"$1\"",
"newmessages"           => "Anda ada $1.",
"newmessageslink"       => "pesanan baru",
"editsection"           =>"sunting",
"toc"                   => "Jadual kandungan",
"showtoc"               => "tunjuk",
"hidetoc"               => "sorok",

# Main script and global functions
#
"nosuchaction"	        => "Tiada tindakan tersebut", #"No such action",
"nosuchactiontext"      => "Tindakan yang dispesifikasikan oleh URL tersebut tidak dikenalpasti oleh perisian Wikipedia.", #"The action specified by the URL is not recognized by the Wikipedia software",
"nosuchspecialpage"     => "Tiada halaman istimewa tersebut", #"No such special page",
"nospecialpagetext"     => "Anda telah meminta halaman istimewa yang tidak dikenalpasti oleh perisian Wikipedia.", #"You have requested a special page that is not recognized by the Wikipedia software.",

# General errors        =>Ralat umum
#
"error"			=> "Ralat",
"databaseerror"         => "Ralat pangkalan data", #"Database error",
"dberrortext"	        => "Berlaku ralat sintaksis pada permintaan pangkalan data.
Percubaan terakhir permintaan pangkalan data ialah :
<blockquote><tt>$1</tt></blockquote>
from within function \"<tt>$2</tt>\".
MySQL returned error \"<tt>$3: $4</tt>\".",
"dberrortextcl"	=> "Berlaku ralat sintaksis pada permintaan pangkalan data.
Percubaan terakhir permintaan pangkalan data ialah :
\"$1\"
from within function \"$2\".
MySQL returned error \"$3: $4\".\n",
"noconnect"		=> "Tidak dapat menghubungkan ke DB oleh $1", #"Could not connect to DB on $1",
"nodb"			=> "Tidak dapat memilih pangkalan data $1", #"Could not select database $1",
"readonly"		=> "Pangkalan data dikunci", #"Database locked",
"enterlockreason"       => "Masukan sebab/alasan untuk dikunci,
termasuk anggaran apabila kunci telah di buka",
"readonlytext"	        => "Pangkalan data Wikipedia sedang di kunci kepada
kemasukan baru.
Pentadbir yang menutup
memberikan penjelasan berikut:
<p>$1",
"rencana hilang"=>"Pengkalan data tidak menjumpai teks bagi laman yang patut dijumpai, bernama\"$1\".

<p>Ini biasanya disebabkan oleh lewat tarikh diff atau sejarah pautan kepada laman telah dipadamkan.

<p>Jika ini bukan sebabnya, anda mungkin menjumpai pepijat dalam aturcara. Sila lapurkan kepada penyelia, catitkan URL, # If this is not the case, you may have found a bug in the software. Please report this to an administrator, making note of the URL.",
# "missingarticle" => "The database did not find the text of a page that it should have found, named \"$1\".
# This is usually caused by following an outdated diff or history link to a page that has been deleted.
"internalerror" => "Ralat dalaman", # Internal error",
"filecopyerror" => "Tidak dapat menyalin fail \"$1\" kepada \"$2\".", #Could not copy file \"$1\" to \"$2\".",
"filerenameerror" => "Tidak dapat menukar nama fail \"$1\" kepada \"$2\".", #Could not rename file \"$1\" to \"$2\".",
"filedeleteerror" => "Tidak dapat memadamkan fail \"$1\".", #Could not delete file \"$1\".",
"filenotfound"	=> "Tidak dapat mencari fail \"$1\".",  #Could not find file \"$1\".",
"unexpected"	=> "Nilai di luar jangkaan: \"$1\"=\"$2\".", # Unexpected value: \"$1\"=\"$2\".",
"formerror"		=> "Ralat: Tidak dapat  menghantar borang", #Error: could not submit form",
"badarticleerror" => "Arahan ini tidak boleh dilaksanakan di halaman ini.", #This action cannot be performed on this page.",
"cannotdelete"	=> "Tidak dapat menghilangkan halaman atau imej yang telah ditetapkan.",
"badtitle"		=> "Tajuk tidak sesuai", #Bad title",
"badtitletext"	        => "Tajuk halaman yang diminta tidak sah, kosong atau salah sambungan dengan antara-bahasa atau tajuk antara-wiki.", #The requested page title was invalid, empty, or an incorrectly linked inter-language or inter-wiki title.",
"perfdisabled" => "Maaf! keupayaan ini dilumpuhkan seketika kerana ia melambatkan pengkalan data sehingga tidak dapat digunakan.", # "Sorry! This feature has been temporarily disabled because it slows the database down to the point that no one can use the wiki.",
"perfdisabledsub"       => "Di sini merupakan salinan yang tersimpan di $1:", # "Here's a saved copy from $1:",

# Login and logout pages
#
"logouttitle"	=> "Pengguna Keluar", #User logout
"logouttext"	=> "Anda telah keluar dari sistem.
Anda boleh terus membaca maklumat, tetapi tidak boleh mengemaskininya atau anda boleh masuk semula sebagai pengguna yang sama atau pengguna berbeza.\n",

"welcomecreation" => "<h2>Selamat datang, $1!</h2><p>Akaun anda telah dibuka.
Sila kemaskini konfigurasi butir-butir diri anda.", #"<h2>Welcome, $1!</h2><p>Your account has been created.Don't forget to personalize your preferences.",

"loginpagetitle" => "Pengguna Masuk", #User login
"yourname"	 => "Nama Pengguna", #Your user name",
"yourpassword"	 => "Kata Laluan", #Your password",
"yourpasswordagain" => "Ulang Kata Laluan", #Retype password",
"newusersonly"	 => "(Hanya pengguna baru)", # (new users only)",
"remembermypassword" => "Sentiasa ingatan kata laluan.", # Remember my password across sessions.",
"loginproblem"	=> "<b>Terdapat masalah dengan data kemasukan.</b><br>Cuba semula!", # There has been a problem with your login.</b><br>Try again!",
"alreadyloggedin" => "<font color=red><b>Pengguna $1, anda telah berjaya masuk!</b></font><br>\n",

"areyounew"		=>"Sekiranya anda baru dalam Wikipedia dan ingin mendapatkan akaun pengguna,
masukan nama pengguna, kemudia taip kata laluan dan ulang semula kata laluan.
Alamat email anda adalah tidak diwajibkan;sekiranya kehilangan kata laluan
boleh diminta melalui email yg diberikan.<br>\n",

"login"			=> "Masuk", #Log in
"userlogin"		=> "Laluan masuk", #Log in
"logout"		=> "Keluar",
"userlogout"	=> "Laluan Keluar", #Log out
"notloggedin"	=> "Not logged in",
"createaccount"	=> "Buka akaun baru", #Create new account",
"badretype"		=> "Kata laluan yang dimasukkan adalah salah.", #The passwords you entered do not match.",
"userexists"	=> "Nama pengguna yang dimasukkan telah digunakan.Sila gunakan nama lain.",
"youremail"		=> "Email anda", #Your e-mail",
"yournick"		=> "Nama samaran (untuk pengenalan)", #Your nickname (for signatures)",
"emailforlost"	=> "Sekiranya terlupa kata laluan, anda boleh dapatkan
yang baru yang akan
diemail ke email
anda.",
"loginerror"	=> "Salah masuk", #Login error",
"noname"		=> "Nama pengguna tidak sah.", #You have not specified a valid user name.",
"loginsuccesstitle" => "Berjaya masuk",  #Login successful",
"loginsuccess"	=> "Berjaya masuk dalam Wikipedia sebagai \"$1\".",  #You are now logged in to Wikipedia as \"$1\".",
"nosuchuser"	=> "Tiada pengguna seperti nama \"$1\". #There is no user by the name \"$1\".
Periksa ejaan, atau guna borang di bawah untuk membuka akaun baru.",
"wrongpassword"	=> "Kata laluan yang dimasukan adalah salah.Sila cuba semula.",
"mailmypassword" => "Emailkan kata laluan baru", #Mail me a new password",
"passwordremindertitle" => "Peringatan kata laluan dari Wikipedia", #Password reminder from Wikipedia",
"passwordremindertext" => "Seseorang (mungkin anda, dari alamat IP $1) #IP  Someone (probably you, from IP address $1)
meminta di hantar kata laluan Wikipedia yang baru.#requested that we send you a new Wikipedia login password.
Kata laluan untuk pengguna \"$2\" ialah \"$3\". #The password for user \"$2\" is now \"$3\".
Anda perlu masuk semula dan tukar kata laluan dengan segera.",  #You should log in and change your password now.",
"noemail"		=> "Tiada alamat email yang direkodkan untuk pengguna \"$1\".",
"passwordsent"	=> "Kata laluan baru telah di emailkan kepada email yang
didaftarkan untuk \"$1\".
Sila masuk setelah menerima email tersebut.", #Please log in again after you receive it.",

# Edit pages
#
"summary"		=> "Ringkasan", #"Summary",
"minoredit"		=> "Hanya sedikit pengemaskinian dilakukan.","This is a minor edit.",
"watchthis"		=> "Watch this article",
"savearticle"	=> "Simpan", #"Save page",
"preview"		=> "Papar", #"Preview",
"showpreview"	=> "Tunjuk Paparan", #"Show preview",
"blockedtitle"	=> "Pengguna diblok", #"User is blocked",
"blockedtext"	=> "Kata nama anda atau alamat IP telah diblok oleh $1.
Alasannya kerana :<br>$2<p>Anda boleh menghubungi sistem admin untuk
membincangkan sebab-sebabnya.",
"newarticle"	=> "(Baru)", #"(New)",
"newarticletext" =>
"Letakkan teks anda
di sini
untuk
halaman baru.",
"anontalkpagetext" => "---- ''This is the discussion page for an anonymous user who has not created an account yet or who does not use it. We therefore have to use the numerical [[IP address]] to identify him/her. Such an IP address can be shared by several users. If you are an anonymous user and feel that irrelevant comments have been directed at you, please [[Special:Userlogin|create an account or log in]] to avoid future confusion with other anonymous users.'' ",
"noarticletext" => "(Tidak ada teks dalam halaman ini)",
"updated"		=> "(Dikemaskini)", #"(Updated)",
"note"			=> "<strong>Nota:</strong> ",
"previewnote"	=> "Ingatan bahawa ini hanyalah paparan, belum disimpan lagi!", #"Remember that this is only a preview, and has not yet been saved!",
"previewconflict" => "Paparan ini ada kaitan dengan teks pada bahagian atas dan
akan terpapar apabila anda memilih untuk menyimpannya.",
"editing"		=> "Menyunting $1", #"Editing $1",
"sectionedit"	=> " (section)",
"editconflict"	=> "Sunting konflik", #"Edit conflict: $1",
"explainconflict" => "Orang lain telah mengemaskini halaman ini sejak anda
mula mengemaskininya.
Bahagian atas teks mengandungi halaman teks yang terkini.
Perubahan yang anda lakukan akan ditunjukkan pada bahagian bawah teks.
Anda hanya perlu mencantumkan perubahan-perubahan dalam teks.
<b>Hanya</b> teks pada bahagian atas akan hanya disimpan apabila anda
menekan \"Simpan halaman\".\n<p>",
"yourtext"		=> "Teks anda", #"Your text",
"storedversion" => "Simpan versi", #"Stored version",
"editingold"	=> "<strong>AMARAN: Anda mengemaskini halaman revisi yang ketinggalan tarikh.
Jika anda menyimpannya,
sebarang perubahan yang dibuat sejak revisi ini akan hilang.</strong>\n",
"yourdiff"		=> "Perbezaan", #"Differences",
"copyrightwarning" => "Semua sumbangan terhadap Wikipedia adalah
tertakluk di bawah GNU Free Documentation License
(lihat $1 untuk maklumat lebih lanjut).
Jika anda tidak mahu tulisan anda disunting dan/atau disebarkan ke halaman
web percuma yang lain, jangan hantarnya ke sini.
Anda juga perlu akui bahawa ini adalah hasil tulisan anda sendiri, atau anda
menyalinnya daripada domain awam atau mana-mana sumber percuma yang sama.
<p><strong><font color=\"red\">JANGAN HANTAR SEBARANG KARYA HAK CIPTA ORANG LAIN TANPA KEBENARAN.</font></strong></p> <p><strong><font color=\"red\">JANGAN SALIN DARIPADA HALAMAN WEB YANG LAIN.</font></strong></p> ",
"longpagewarning" => "WARNING: This page is $1 kilobytes long; some
browsers may have problems editing pages approaching or longer than 32kb.
Please consider breaking the page into smaller sections.",
"readonlywarning" => "WARNING: The database has been locked for maintenance,
so you will not be able to save your edits right now. You may wish to cut-n-paste
the text into a text file and save it for later.",
"protectedpagewarning" => "WARNING:  This page has been locked so that only
users with sysop privileges can edit it. Be sure you are following the
<a href='$wgScriptPath/$wgMetaNamespace:Protected_page_guidelines'>protected page
guidelines</a>.",

# History pages
#
"revhistory"	=> "Sejarah revisi", #"Revision history",
"nohistory"		=> "Tidak ada sejarah kemaskini untuk halaman ini", #"There is no edit history for this page.",
"revnotfound"	=> "Revisi tidak dijumpai", #"Revision not found",
"revnotfoundtext" => "Revisi lama untuk halaman ini yang anda minta tidak dapat dijumpai.
Sila semak URL yang digunakan untuk mengakses halaman ini.\n", #"The old revision of the page you asked for could not be found. Please check the URL you used to access this page.\n",
"loadhist"		=> "Muatturun halaman sejarah", #"Loading page history",
"currentrev"	=> "Revisi terkini", #"Current revision",
"revisionasof"	=> "Revisi sebagai $1", #"Revision as of $1",
"cur"			=> "cur",
"next"			=> "next",
"last"			=> "last",
"orig"			=> "orig",
"histlegend"	=> "Lagenda: (cur) = perbezaan dengan versi terkini,
(last) = perbezaan dengan versi terdahulu", #"Legend: (cur) = difference with current version,(last) = difference with preceding version",

# Diffs
#
"difference"	=> "Perbezaan antara revisi", #"(Difference between revisions)",
"loadingrev"	=> "muatturun revisi diff", #"loading revision for diff",
"lineno"		=> "Baris $1:", #"Line $1:",
"editcurrent"	=> "Sunting versi terkini untuk halaman ini", #"Edit the current version of this page",

# Search results
#
"searchresults" => "Hasil Carian",
"searchresulttext" => "Untuk maklumat lanjut tentang carian dalam {{SITENAME}}, lihat [[Project:Carian|Carian Dalam {{SITENAME}}]].", "searchquery"	=> "Untuk kueri \"$1\"", #"For query \"$1\"",
"badquery"		=> "Format kueri carian salah", #"Badly formed search query",
"badquerytext"	=> "Kami tidak dapat memproses kueri anda.
Ini mungkin disebabkan anda cuba mencari perkataan yang kurang
daripada tiga huruf, yang mana masih belum disokong oleh sistem ini.
Ataupun anda tersalah taip ekspresi untuk kueri,
contohnya \"fish and and scales\".
Sila cuba kueri yang lain.",
"matchtotals"	=> "Kueri \"$1\" sepadan dengan tajuk halaman $2 dan teks
halaman $3.", #"The query \"$1\" matched $2 page titles and the text of $3 pages.",
"nogomatch" => "No page with this exact title exists, trying full text search.",
"titlematches"	=> "Tajuk artikel yang sepadan", #"Article title matches",
"notitlematches" => "Tiada tajuk halaman yang sepadan", #"No page title matches",
"textmatches"	=> "Teks artikel yang sepadan", #"Article text matches",
"notextmatches"	=> "Tiada teks halaman yang sepadan", #No page text matches",
"prevn"			=> "sebelum $1", #"previous $1",
"nextn"			=> "selepas $1", #"next $1",
"viewprevnext"	=> "Papar ($1) ($2) ($3).", #"View ($1) ($2) ($3).",
"showingresults" => "Di bawah menunjukkan <b>$1</b> hasil carian bermula dengan #<b>$2</b>.", #"Showing below <b>$1</b> results starting with #<b>$2</b>.",
"showingresultsnum" => "Di bawah menunjukkan <b>$3</b> hasil carian bermula dengan #<b>$2</b>.", #"Showing below <b>$3</b> results starting with #<b>$2</b>.",
"nonefound"		=> "<strong>Nota</strong>:Kegagalan enjin carian
biasanya disebabkan pencarian perkataan seperti \"have\" and \"from\",
yang mana bukan dalam senarai indeks atau dengan dikhususkan lebih dari
satu istilah carian(hanya halaman).", # "<strong>Note</strong>: unsuccessful searches are often caused by searching for common words like \"have\" and \"from\",which are not indexed, or by specifying more than one search term (only pages containing all of the search terms will appear in the result).",
"powersearch" => "Cari", #"Search",
"powersearchtext" => "
Search in namespaces :<br>
$1<br>
$2 List redirects &nbsp; Search for $3 $9",


# Preferences page
#
"preferences"	=> "Konfigurasi", #"Preferences",
"prefsnologin" => "Belum mendaftar masuk", #"Not logged in",
"prefsnologintext"	=> "Anda mesti <a href=\"" .
  wfLocalUrl( "Istimewa:Userlogin" ) . "\">mendaftar masuk</a>
untuk tetapkan butir-butir diri anda.", #"You must be <a href=\"" .
"prefslogintext" => "Anda telah masuk sebagai \"$1\".
Nombor ID anda ialah $2.",


"prefsreset"	=> "Konfigurasi telah diubah ke asal dari storan", #"Preferences have been reset from storage.",
"qbsettings"	=> "Konfigurasi quickbar", #"Quickbar settings",
"changepassword" => "Tukar kata laluan", #"Change password",
"skin"			=> "Skin",
"math"			=> "Rendering math",
"dateformat"	=> "Date format",
"math_failure"		=> "Failed to parse",
"math_unknown_error"	=> "unknown error",
"math_unknown_function"	=> "unknown function ",
"math_lexing_error"	=> "lexing error",
"math_syntax_error"	=> "syntax error",
"saveprefs"		=> "Simpan konfigurasi", #"Save preferences",
"resetprefs"	=> "Ubah konfigurasi ke asal", #"Reset preferences",
"oldpassword"	=> "Kata laluan lama", #"Old password",
"newpassword"	=> "Kata laluan baru", #"New password",
"retypenew"		=> "Taip semula kata laluan baru", #"Retype new password",
"textboxsize"	=> "Dimensi kotak teks", #"Editing",
"rows"			=> "Baris", #"Rows",
"columns"		=> "Lajur", #"Columns",
"searchresultshead" => "Konfigurasi hasil carian", #"Search result settings",
"resultsperpage" => "Bilangan capaian untuk satu halaman", #"Hits to show per page",
"contextlines"	=> "Bilangan baris untuk satu capaian", #"Lines to show per hit",
"contextchars"	=> "Bilangan aksara konteks untuk satu baris", #"Characters of context per line",
"stubthreshold" => "Threshold for stub display",
"recentchangescount" => "Bilangan tajuk dalam perubahan terkini", #"Number of titles in recent changes",
"savedprefs"	=> "Konfigurasi butir-butir diri anda telah disimpan", #"Your preferences have been saved.",
"timezonetext"	=> "Masukkan perbezaan jam antara waktu
tempatan anda dengan waktu pelayan (UTC).", #"Enter number of hours your local time differs from server time (Kuala Lumpur, which is GMT+8).",
"localtime"	=> "Waktu tempatan", #"Local time",
"timezoneoffset" => "Imbang", #"Offset",
"servertime"	=> "Server time is now",
"guesstimezone" => "Fill in from browser",
"emailflag"		=> "Halang e-mail daripada pengguna lain", #"Disable e-mail from other users",
"defaultns"		=> "Search in these namespaces by default:",

# Recent changes
#
"changes" => "perubahan",
"recentchanges" => "Perubahan Terkini",
"recentchangestext" => "Kenalpasti perubahan terkini dalam Wikipedia di halaman ini.
[[Wikipedia:Selamat datang,_pengguna baru|Selamat datang, pengguna baru]]!
Sila lihat halaman-halaman ini: [[Wikipedia:FAQ|Wikipedia FAQ]],
[[Wikipedia:Polisi dan garis panduan|Polisi Wikipedia]]
(terutamanya [[Wikipedia:Konvensyen penamaan|konvensyen penamaan]],
[[Wikipedia:Pandangan semulajadi|pandangan semulajadi]]),
dan [[Wikipedia: Kesalahan biasa Wikipedia|kesalahan biasa Wikipedia]].

Jika anda mahu melihat Wikipedia berjaya, adalah sangat penting anda
tidak memasukkan material [[Wikipedia:Hak cipta|hak cipta]] orang lain.
Perkara ini boleh memusnahkan laman web ini,
jadi sila patuhi amaran ini.",
"rcloaderr"		=> "Muatturun perubahan terkini", #"Loading recent changes",
"rcnote"		=> "Di bawah adalah <strong>$1</strong> kemaskini terakhir dalam <strong>$2</strong> hari lepas.", #"Below are the last <strong>$1</strong> changes in last <strong>$2</strong> days.",
"rcnotefrom"	=> "Below are the changes since <b>$2</b> (up to <b>$1</b> shown).",
"rclistfrom"	=> "Show new changes starting from $1",
# "rclinks"		=> "Show last $1 changes in last $2 hours / last $3 days",
# "rclinks"		=> "Show last $1 changes in last $2 days.",
"rclinks"		=> "Tunjuk $1 kemaskini terakhir dalam $2 hari lepas.", #"Show last $1 changes in last $2 days.",
"rchide"		=> "dalam $4 bentuk; $1 perubahan kecil; $2 ruang nama kedua; $3 perubahan berganda.",  #"in $4 form; $1 minor edits; $2 secondary namespaces; $3 multiple edits.",
"rcliu"			=> "; $1 edits from logged in users",
"diff"			=> "diff",
"hist"			=> "hist",
"hide"			=> "hide",
"show"			=> "show",
"tableform"		=> "table",
"listform"		=> "list",
"nchanges"		=> "$1 changes",
"minoreditletter" => "m",
"newpageletter" => "B",

# Upload
#
"upload"		=> "Muatnaik",
"uploadbtn"		=> "Muatnaik fail", #"Upload file",
"uploadlink"	=> "Muatnaik imej", #"Upload images",
"reupload"		=> "Muatnaik semula", #"Re-upload",
"reuploaddesc"	=> "Kembali ke borang muatnaik", #"Return to the upload form.",
"uploadnologin" => "Tidak mendaftar masuk", #"Not logged in",
"uploadnologintext"	=> "Anda mesti <a href=\"" .
  wfLocalUrl( "Istimewa:Userlogin" ) . "\">pengguna</a>
untuk muatnaik fail.",
"uploadfile"	=> "Muatnaik fail", #"Upload file",
"uploaderror"	=> "Muatnaik ralat", #"Upload error",
"uploadlog"		=> "muatnaik log", #"upload log",
"uploadlogpage" => "Muatnaik_log", #"Upload_log",
"uploadlogpagetext" => "Di bawah ini adalah senarai terkini fail yang dimuatnaik.
Semua waktu yang ditunjukkan adalah waktu pelayan (UTC).
<ul>
</ul>
",
"filename"		=> "Filename",
"filedesc"		=> "Ringkasan", #"Summary",
"affirmation"	=> "Saya berikrar bahawa pemilik hak cipta fail ini
telah bersetuju untuk melesenkannya di bawah terma $1.", #"I affirm that the copyright holder of this file agrees to license it under the terms of the $1.",
"copyrightpage" => "Wikipedia:Hak cipta",
"copyrightpagename" => "Hak cipta Wikipedia",
"uploadedfiles"	=> "Fail yang telah dimuatnaik", #"Uploaded files",
"noaffirmation" => "Anda mesti berikrar bahawa fail yang dimuatnaik tidak
tertakluk di bawah sebarang hak cipta.", #"You must affirm that your upload does not violate any copyrights.",
"ignorewarning"	=> "Abaikan amaran dan simpan fail sahaja", #"Ignore warning and save file anyway.",
"minlength"		=> "Nama imej mesti sekurang-kurangnya tiga huruf.", #"Image names must be at least three letters.",
"badfilename"	=> "Nama imej telah ditukar kepada \"$1\".", #"Image name has been changed to \"$1\".",
"badfiletype"	=> "\".$1\" ialah fail format imej yg tidak di sarankan.",
"largefile"		=> "Disarankan agar saiz imej tidak melebihi 100k.",
"successfulupload" => "Berjaya dimuaturun",    #"Successful upload",
"fileuploaded"	=> "Fail \"$1\" berjaya dimuaturun.
Sila patuhi pautan : ($2) pada halaman deskripsi dan isikan maklumat
tentang fail tersebut, seperti fail itu dari mana, bila ia dibuat dan
oleh siapa, dan lain-lain yang anda ketahui.",
"uploadwarning" => "Amaran muaturun", # Upload warning",
"savefile"		=> "Simpan fail", #"Save file",
"uploadedimage" => "Telah dimuaturun \"$1\"",     #"uploaded \"$1\"",

# Image list
#
"imagelist"	=> "Senarai imej",   #"Image list",
"imagelisttext"	=> "Di bawah ialah senarai imej yang telah diasingkan $2.", #"Below is a list of $1 images sorted $2.",
"getimagelist"	=> "senarai imej yang diperolehi",    #"fetching image list",
"ilshowmatch"	=> "Paparan semua imej dengan nama yang berpadanan",    #"Show all images with names matching",
"ilsubmit"		=> "Carian", #"Search",
"showlast"		=> "Paparan imej terakhir $1 yang telah diasingkan $2.", #"Show last $1 images sorted $2.",
"all"			=> "semua", #"all",
"byname"		=> "mengikut nama",   #"by name",
"bydate"		=> "mengikut tarikh", #by date",
"bysize"		=> "mengikut saiz",  #"by size",
"imgdelete"		=> "del",
"imgdesc"		=> "desc",
"imglegend"		=> "Lagenda: (desc) = papar/sunting deskripsi imej.", #"Legend: (desc) = show/edit image description.",
"imghistory"	=> "Sejarah imej",  #"Image history",
"revertimg"		=> "rev",
"deleteimg"		=> "del",
"deleteimgcompletely"		=> "del",
"imghistlegend" => "Legend: (cur) = this is the current image, (del) = delete
this old version, (rev) = revert to this old version.
<br><i>Click on date to see image uploaded on that date</i>.",
"imagelinks"	=> "Pautan imej",  #"Image links",
"linkstoimage"	=> "Halaman berikut berpaut pada imej ini:", #"The following pages link to this image:",
"nolinkstoimage" => "Tiada halaman yang berpaut pada imej ini.",   #"There are no pages that link to this image.",

# Statistics
#
"statistics"	=> "Statistik", #"Statistics",
"sitestats"		=> "Laman statistik",    #"Site statistics",
"userstats"		=> "Statistik pengguna",  #"User statistics",
"sitestatstext" => "Terdapat <b>$1</b> jumlah halaman dalam pangkalan data.
Ini termasuk halaman \"talk\", halaman tentang Wikipedia, halaman minimum \"stub\",
alih semula dan lain-lain yang mungkin tidak termasuk dalam
halaman penuh.
Selain itu, terdapat halaman <b>$2</b> yang mungkin halaman penuh. <p>
Terdapat sejumlah <b>$3</b> halaman yang disiarkan, dan sejumlah <b>$4</b>
halaman yang telah diedit sejak ia dihasilkan.
Ini menjadikan <b>$5</b> purata halaman yang disunting, dan <b>$6</b> paparan yang disunting.",
"userstatstext" => "Terdapat <b>$1<b/> pengguna berdaftar.
<b>$2<b/> daripadanya adalah pentadbir (lihat $3).",

# Maintenance Page
#
"maintenance"		=> "Halaman penyelenggaraan",  #"Maintenance page",
"maintnancepagetext"	=> "Halaman ini termasuk beberapa peralatan untuk penyelenggaraan setiap hari.  Terdapat sesetengah fungsi yang berkecenderungan untuk mengganggu pangkalan data, jadi tolong jangan tekan kekunci 'reload' selepas membuat pembetulan;-)",
"maintenancebacklink"	=> "Kembali ke halaman penyelenggaran", #"Back to Maintenance Page",
"disambiguations"	=> "Halaman yang tidak samar", #"Disambiguation pages",
"disambiguationspage"	=> "Wikipedia:Pautan_ke_halaman_yang_tidak_samar",    #"Wikipedia:Links_to_disambiguating_pages",
"disambiguationstext"	=> "Halaman-halaman yang berikutnya bersambung ke satu <i>halaman yang tidak samar</i>. Halaman-halaman tersebut sepatutnya bersambung ke topik-topik yang berkenaan.<br>Satu halaman dianggap sebagai tidak samar jika ia disambung dari $1.<br>Pautan dari ruang nama yang lain <i>tidak</i> tersenarai di sini.",
"doubleredirects"	=> "Peralihan Halaman Berganda", #"Double Redirects",
"doubleredirectstext"	=> "<b>Perhatian:</b> Senarai ini mungkin tidak tepat. Ini biasanya bermaksud terdapat tambahan teks dengan pautan di bawah #REDIRECT yang pertama.<br>\nSetiap baris mengandungi pautan kepada peralihan halaman yang pertama dan kedua, sebagaimana baris pertama bagi teks peralihan halaman kedua, biasanya memberikan halaman sasaran \"sebenar\" yang sepatutnya peralihan pertama disambungkan.",
"brokenredirects"	=> "Peralihan Halaman Rosak", #"Broken Redirects",
"brokenredirectstext"	=> "Peralihan halaman berikut bersambung ke satu halaman yang tidak wujud", #"The following redirects link to a non-existing page.",
"selflinks"		=> "Halaman-halaman dengan pautan sendiri", #"Pages with Self Links",
"selflinkstext"		=> "Halaman-halaman berikut mengandungi pautan ke mereka sendiri, yang sepatutnya tidak berlaku.", #"The following pages contain a link to themselves, which they should not.",
"mispeelings"           => "Halaman-halaman yang mempunyai kesilapan ejaan", #"Pages with misspellings",
"mispeelingstext"               => "Halaman-halaman berikut mengandungi kesilapan ejaan, yang disenaraikan di $1. Ejaan yang betul mungkin diberi (seperti ini).", # "The following pages contain a common misspelling, which are listed on $1. The correct spelling might be given (like this).",
"mispeelingspage"       => "Senarai kesilapan ejaan", #"List of common misspellings",
"missinglanguagelinks"  => "Pautan bahasa yang hilang", #"Missing Language Links",
"missinglanguagelinksbutton"    => "Cari pautan bahasa yang hilang untuk", #"Find missing language links for",
"missinglanguagelinkstext"      => "Halaman ini <i>tidak boleh</i> berpaut pada bahagian pusingan dalam $1. Mengarah semula dan sub halaman <i>tidak</i>akan  ditunjukkan.",


# Miscellaneous special pages
#
"orphans"	=> "Halaman orphaned", #"Orphaned pages",
"lonelypages"	=> "Halaman orphaned", #"Orphaned pages",
"unusedimages"	=> "Imej tidak digunakan",
"popularpages"	=> "Halaman Popular",
"nviews"		=> "Dipamerkan $1", #"$1 views",
"wantedpages"	=> "Halaman dikehendaki",
"nlinks"		=> "Pautan $1",  #"$1 links",
"allpages"		=> "Halaman Keseluruhan",
"randompage"	=> "Halaman Rawak",
"shortpages"	=> "Halaman Ringkas",
"longpages"		=> "Halaman Lengkap",
"listusers"		=> "Senarai Pengguna",
"specialpages"	=> "Halaman Istimewa",
"spheading"		=> "Halaman Istimewa", #"Special pages",
"sysopspheading" => "Halaman Istimewa Admin",
"developerspheading" => "",
"protectpage"	=> "Halaman dilindungi",
"recentchangeslinked" => "Pautan pilihan",
"rclsub"		=> "(Untuk halaman yang berpaut dari \"$1\")",   #"(to pages linked from \"$1\")",
"debug"			=> "Pepijat",   #"Debug",
"newpages"		=> "Halaman Baru",
"ancientpages"		=> "Oldest articles",
"intl"		=> "Interlanguage links",
"movethispage"	=> "Pindah halaman ini",
"unusedimagestext" => "<p>Sila ambil perhatian laman web
yang lain mungkin boleh berpaut secara
terus dengan URL, dan mungkin masih di
senaraikan selain masih aktif digunakan.",
"booksources"	=> "Sumber buku",
"booksourcetext" => "Di bawah ini merupakan senarai untuk ke
pautan lain yang menjual buku baru dan yang telah digunakan,
dan mungkin mempunyai maklumat lanjut tentang buku yang anda sedang cari.
Wikipedia tidak bergabung dengan mana-mana perniagaan di atas,
dan senarai ini sepatutnya tidak ditafsirkan sebagai sokongan.",
"alphaindexline" => "$1 to $2",

# Email this user
#
"mailnologin"	=> "Tidak dibenarkan hantar alamat", #"No send address",
"mailnologintext" => "Anda mesti <a href=\"" . wfLocalUrl( "Istimewa:Userlogin"). "\logged in</a> dan mempunyai alamat email yang sah di <a href=\"". wfLocalUrl( "Istimewa:Preferences") . "\"preferences</a> untuk menghantar email kepada pengguna lain.",
#"You must be <a href=\"" .   wfLocalUrl( "Special:Userlogin" ) . "\">logged in</a> and have a valid e-mail address in your <#a href=\"" .   wfLocalUrl( "Special:Preferences" ) . "\">preferences</a> to send e-mail to other users.",

"emailuser"		=> "E-mail pengguna ini", #"E-mail this user",
"emailpage"		=> "E-mail pengguna", #"E-mail user",
"emailpagetext"	=> "Sekiranya pengguna ini memasuki alamat email yang sah dalam kecenderungan pengguna, borang dibawah akan menghantar satu pesanan. Alamat email yg diisi pada kecenderungan pengguna akan muncul seperti \"From\"alamat email, jadi penerima boleh membalas email tersebut.",

#"If this user has entered a valid e-mail address in is user preferences, the form below will send a single message.
#The e-mail address you entered in your user preferences will appear as the \"From\" address of the mail, so the recipient wi#ll be able to reply.",

"noemailtitle"	=> "Tiada alamat email", #"No e-mail address",

"noemailtext"	=> "Pengguna ini tidak mengkhususkan alamat email yang sah, atau telah memilih untuk tidak mendapat email dari pengguna yang lain.",
#"This user has not specified a valid e-mail address, or has chosen not to receive e-mail from other users.",

"emailfrom"		=> "Daripada", #"From",
"emailto"		=> "Kepada",  #"To",
"emailsubject"	=> "Perkara",  #"Subject",
"emailmessage"	=> "Mesej", #"Message",
"emailsend"		=> "Hantar", #"Send",
"emailsent"		=> "Email dihantar", #"E-mail sent",
"emailsenttext" => "Email anda telah dihantar.", #"Your e-mail message has been sent.",

# Watchlist
#
"watchlist"		=> "Senarai Pilihan",
"watchlistsub"	=> "( untuk pengguna \"$1\")",   #"(for user \"$1\")",
"nowatchlist"	=> "Tiada apa-apa dalam senarai pilihan.",    #"You have no items on your watchlist.",
"watchnologin"	=> "Tidak memasuki sistem", #"Not logged in",
"watchnologintext"	=> "Anda mesti < a href=\"".
  wfLocalUrl( "Istimewa:Userlogin" ) ."\">mendaftar masuk<a/>
untuk mengubah senarai pilihan.",
"addedwatch"	=> "Tambah ke senarai pilihan",
"addedwatchtext" => "Halaman \"$1\" telah ditambah pada <a href=\"".
  wfLocalUrl( "Istimewa:Watchlist") . "\">senarai pilihan</a>.
Perubahan halaman dan gabungan halaman Perbualan pada masa akan datang
akan disenaraikan di sini, dan halaman itu akan muncul <b>bolded</b> dalam <a href=\"".
 wfLocalUrl( "Istimewa:Recentchanges" ) . "\">senarai perubahan terkini</a>
 supaya lebih mudah dibawa keluar.</p>

<p>Sekiranya anda ingin menghapuskan halaman dalam senarai pilihan kemudian, klik \"Berhenti dari senarai pilihan\" pada bar disebelah.",
"removedwatch"	=> "Keluarkan dari senarai pilihan",   #"Removed from watchlist",
"removedwatchtext" => "Halaman \"$1\"telah dikeluarkan dari senarai pilihan.",
"watchthispage"	=> "Tambah ke senarai pilihan",
"unwatchthispage" => "Berhenti dari senarai pilihan", #"Stop watching",
"notanarticle"	=> "Not a page",

# Delete/protect/revert
#
"deletepage"	=> "Keluarkan halaman", #"Delete page",
"confirm"		=> "Sah", #"Confirm",
"excontent" => "Kandungan dahulu:",
"exbeforeblank" => "kandungan sebelum dikosongankan adalah:",
"exblank" => "laman kosong",
"confirmdelete" => "Sah keluarkan", #"Confirm delete",
"deletesub"		=> "(Keluarkan \"$1\")", #"(Deleting \"$1\")",
"historywarning" => "Amaran: Laman yang anda ingin padamkan mempunyai sejarah:",
"confirmdeletetext" => "Anda akan mengeluarkan terus halaman atau
imej ini dengan semua sejarahnya dari pangkalan data.  Sila pastikan
yang anda memang mahu berbuat demikian, bahawa anda faham segala
akibatnya, dan apa yang anda lakukan ini adalah mengikut
[[Wikipedia:Polisi]].",
"confirmcheck"	=> "Ya, saya mahu keluarkan halaman ini", #"Yes, I really want to delete this.",
"actioncomplete" => "Proses selesai", #"Action complete",
"deletedtext"	=> "\"$1\" telah dikeluarkan.
Lihat $2 untuk rekod terkini halaman yang telah dikeluarkan.",
"deletedarticle" => "telah dikeluarkan", #"deleted \"$1\"",
"dellogpage"	=> "Log_penghapusan", #"Deletion_log",
"dellogpagetext" => "Di bawah ini adalah senarai terkini halaman yang telah dikeluarkan.
Semua waktu yang ditunjukkan adalah waktu pelayan (UTC).
<ul>
</ul>
",
"deletionlog"	=> "Log penghapusan", #"deletion log",
"reverted"		=> "Kembali kepada rujukan yang sebelumnya", #"Reverted to earlier revision",
"deletecomment"	=> "Sebab penghapusan", #"Reason for deletion",
"imagereverted" => "Kembali kepada rujukan yang sebelumnya berjaya", #"Revert to earlier version was successful.",
"rollback"		=> "Sunting kembali asal",
"rollbacklink"	=> "kembali asal",
"rollbackfailed" => "gagal kembali asal",
"cantrollback"	=> "Cannot revert edit; last contributor is only author of this article.",
"alreadyrolled"	=> "Cannot rollback last edit of [[$1]]
by [[User:$2|$2]] ([[User talk:$2|Talk]]); someone else has edited or rolled back the article already.

Last edit was by [[User:$3|$3]] ([[User talk:$3|Talk]]). ",
#   only shown if there is an edit comment
"editcomment" => "The edit comment was: \"<i>$1</i>\".",
"revertpage"	=> "Kembali kesuntingan akhir oleh $1",

# Undelete
"undelete" => "Masukkan semula halaman yang telah dikeluarkan", #"Restore deleted page",
"undeletepage" => "Papar dan masukkan semula halaman yang telah dikeluarkan", #"View and restore deleted pages",
"undeletepagetext" => "Halaman-halaman berikutnya telah dikeluarkan tapi masih ada di dalam capaian dan boleh dimasukkan semula. Capaian tersebut mungkin akan dikemaskini secara berkala", #"The following pages have been deleted but are still in the archive and can be restored. The archive may be periodically cleaned out.",
"undeletearticle" => "Masukkan semula halaman yang telah dikeluarkan", #"Restore deleted article",
"undeleterevisions" => "$1 revisi dicapai", #"$1 revisions archived",
"undeletehistory" => "Jika anda masukkan semula halaman tersebut,
semua revisi akan dimasukkan semula ke dalam sejarah.
Jika satu halaman telah dibuat dengan nama yang sama setelah penghapusan,
revisi yang telah dimasukkan semula akan kelihatan dalam sejarah dahulu,
dan revisi terkini bagi halaman baru tidak akan digantikan secara automatik.", #"If you restore the page, all revisions will be restored to the history.If a new page with the same name has been created since the deletion, the restored revisions will appear in the prior history, and the current revision of the live page will not be automatically replaced.",
"undeleterevision" => "Revisi yang telah dikeluarkan seperti $1", #"Deleted revision as of $1",
"undeletebtn" => "Masukkan semula!", #"Restore!",
"undeletedarticle" => "telah dimasukkan", #"restored \"$1\"",
"undeletedtext"   => "Halaman [[$1]] telah berjaya dimasukkan semula.
Lihat [[Wikipedia:Log_penghapusan]] untuk rekod terkini penghapusan dan kemasukan semula halaman.",

# Contributions
#
"contributions"	=> "Sumbangan pengguna", #"User contributions",
"mycontris" => "Sumbangan saya", #"My contributions",
"contribsub"	=> "Untuk $1", #"For $1",
"nocontribs"	=> "Tidak ada sebarang perubahan yang sepadan dengan kriteria-kriteria ini.", #"No changes were found matching these criteria.",
"ucnote"		=> "Di bawah ini adalah <b>$1</b> perubahan terakhir pengguna dalam tempoh <b>$2</b> hari lepas.", #"Below are this user's last <b>$1</b> changes in the last <b>$2</b> days.",
"uclinks"		=> "Paparkan $1 perubahan terkini; paparkan $2 hari lepas", #"View the last $1 changes; view the last $2 days.",
"uctop"		=> " (top)" ,

# What links here
#
"whatlinkshere"	=> "Pautan ke halaman ini",
"notargettitle" => "Tiada sasaran", #"No target",
"notargettext"	=> "Anda tidak menspesifikasikan halaman
atau pengguna sasaran untuk melaksanakan fungsi ini.",
"linklistsub"	=> "(Senarai pautan)", #"(List of links)",
"linkshere"		=> "Halaman-halaman berikut mempunyai pautan ke sini:", #"The following pages link to here:",
"nolinkshere"	=> "Tiada halaman mempunyai pautan ke sini.", #"No pages link to here.",
"isredirect"	=> "alih semula halaman", #"redirect page",

# Block/unblock IP
#
"blockip"		=> "Blok IP", #"Block IP",
"blockiptext"	=> "Gunakan borang di bawah untuk blok
capaian kemaskini daripada alamat IP atau pengguna tertentu.
Ini perlu dilakukan untuk mencegah vandalisme,
dan mengikut [[Wikipedia:Polisi|Polisi Wikipedia]].
Masukkan alasan anda di bawah (contohnya mengambil
halaman tertentu yang telah dirosakkan).",
"ipaddress"		=> "Alamat IP atau pengguna", #"IP Address or Username",
"ipbreason"		=> "Alasan", #"Reason",
"ipbsubmit"		=> "Hantar", #"Submit",
"badipaddress"	=> "Alamat IP atau pengguna ini dalam format yang tidak betul.", #"The IP address or username is badly formed.",
"noblockreason" => "Anda mesti sediakan alasan untuk pemblokan tersebut.", #"You must supply a reason for the block.",
"blockipsuccesssub" => "Pemblokan berjaya", #"Block succeeded",
"blockipsuccesstext" => "Alamat IP atau pengguna \"$1\" telah diblok.
<br>Lihat [[Istimewa:Ipblocklist|IP and user block list]] untuk semak pemblokan.", #"The IP address or username \"$1\" has been blocked.
"unblockip"		=> "Lepaskan semula alamat IP atau pengguna dari diblok", #"Unblock IP address or user",
"unblockiptext"	=> "Gunakan borang di bawah untuk masukkan semula
capaian kemaskini ke alamat IP atau pengguna yang telah diblok sebelumnya.",
"ipusubmit"		=> "Lepaskan semula alamat ini dari diblok", #"Unblock this address",
"ipusuccess"	=> "Alamat IP atau pengguna \"$1\" dilepaskan dari diblok", #"IP address or user \"$1\" unblocked",
"ipblocklist"	=> "Senarai alamat IP dan pengguna yang diblok", #"List of blocked IP addresses and users",
"blocklistline"	=> "$1, $2 blok $3", #"$1, $2 blocked $3",
"blocklink"		=> "blok", #"block",
"unblocklink"	=> "lepaskan dari diblok", #"unblock",
"contribslink"	=> "contribs",

# Developer tools
#
"lockdb"		=> "Kunci pangkalan data", #"Lock database",
"unlockdb"		=> "Buka semula pangkalan data dari dikunci", #"Unlock database",
"lockdbtext"	=> "Mengunci pangkalan data akan menghalang pengguna dari
menyunting halaman, menukar konfigurasi pengguna, sunting senarai pilihan mereka,
dan lain-lain perkara yang melibatkan kemaskini terhadap pangkalan data.
Sila pastikan anda mahu berbuat demikian, dan anda akan buka semula pangkalan
data apabila penyelenggaraan telah selesai.",
"unlockdbtext"	=> "Membuka semula pangkalan data dari dikunci akan
membolehkan pengguna mengedit halaman, menukar konfigurasi pengguna,
menyunting senarai pilihan mereka dan lain-lain perkara yang melibatkan
kemaskini terhadap pangkalan data.  Sila pastikan anda mahu berbuat demikian.",
"lockconfirm"	=> "Ya, saya memang mahu mengunci pangkalan data ini.", #"Yes, I really want to lock the database.",
"unlockconfirm"	=> "Ya, saya memang mahu membuka semula pangkalan data ini dari dikunci.", #"Yes, I really want to unlock the database.",
"lockbtn"		=> "Kunci pangkalan data", #"Lock database",
"unlockbtn"		=> "Buka semula pangkalan data dari dikunci", #"Unlock database",
"locknoconfirm" => "Anda tidak semak semula kotak pengesahan.", #"You did not check the confirmation box.",
"lockdbsuccesssub" => "Penguncian pangkalan data berjaya", #"Database lock succeeded",
"unlockdbsuccesssub" => "Kunci pangkalan data telah dihapuskan", #"Database lock removed",
"lockdbsuccesstext" => "Pangkalan data Wikipedia telah dikunci.
<br>Pastikan anda membukanya semula dari dikunci setelah penyelenggaraan selesai.",
"unlockdbsuccesstext" => "Pangkalan data Wikipedia telah dibuka semula dari dikunci.", #"The Wikipedia database has been unlocked.",

# SQL query
#
"asksql"		=> "Kueri SQL", #"SQL query",
"asksqltext"	=> "Gunakan borang di bawah untuk membuat kueri langsung
bagi pangkalan data Wikipedia.
Gunakan pembuka kata tunggal ('seperti ini') untuk menghadkan rangkaian
string.  Ini selalunya menambah beban terhadap pelayan, jadi sila gunakan
fungsi ini dengan cermat.",
"sqlislogged"	=> "Harap maklum semua pertanyaan dilogkan.",
"sqlquery"		=> "Masukkan kueri", #"Enter query",
"querybtn"		=> "Hantar kueri", #"Submit query",
"selectonly"	=> "Kueri selain dari \"SELECT\" adalah tidak dibenarkan
kepada pembangun Wikipedia.", #"Queries other than \"SELECT\" are restricted to
"querysuccessful" => "Kueri berjaya", #"Query successful",

# Move page
#
"movepage"	=> "Alih Halaman",  #"Move page",
"movepagetext"	=> "Borang di bawah digunakan untuk menukar halaman,
mengalihkan semua data kepada nama baru.
Tajuk yang lama akan terus dialih kepada tajuk yang baru.
Pautan kepada tajuk yang lama tidak akan berubah, dan halaman
perbualan tidak akan dikeluarkan sekiranya perlu.









<b>AMARAN!</b>
Ini menjadikan perubahan yang tidak dijangka dan drastik
bagi halaman popular.  Sila pastikan anda faham selok
belok borang ini sebelum anda teruskan.",
"movepagetalktext" => "The associated talk page, if any, will be automatically moved along with it '''unless:'''
*You are moving the page across namespaces,
*A non-empty talk page already exists under the new name, or
*You uncheck the box below.

In those cases, you will have to move or merge the page manually if desired.",
"movearticle"	=> "Alih halaman", #"Move page",
"movenologin"	=> "Tidak masuk sistem", #"Not logged in",
"movenologintext" => "Anda mesti menjadi pengguna berdaftar dan <a href=\"" .
  wfLocalUrl( "Istimewa:Userlogin") . "\">logged in</a>
untuk mengalihkan halaman.",
"newtitle"	=> "Tajuk baru",   #"To new title",
"movepagebtn"	=> "Alih halaman", #"Move page",
"pagemovedsub"	=> "Pengalihan berjaya",  #"Move succeeded",
"pagemovedtext" => "Halaman \"[[$1]]\" ditukar kepada \"[[$2]]\".",   #"Page \"[[$1]]\" moved to \"[[$2]]\".",
"articleexists" => "Halaman dengan nama tersebut telah wujud,
atau nama yang dipilih tidak sah.
Sila pilih nama lain.",
"talkexists"	=> "The page itself was moved successfully, but the
talk page could not be moved because one already exists at the new
title. Please merge them manually.",
"movedto"		=> "Tukar kepada",  #"moved to",
"movetalk"		=> "Tukar halaman \"perbualan\", sekiranya sesuai.",   #"Move \"talk\" page as well, if applicable.",
"talkpagemoved" => "Halaman perbualan yang sama turut dialihkan.",    #"The corresponding talk page was also moved.",
"talkpagenotmoved" => "Halaman perbualan yang sama <strong>not</strong> turut dialihkan.",
# Math
	'mw_math_png' => "Sentiasa lakar PNG", # "Always render PNG",
	'mw_math_simple' => "HTML jika ringkas atau PNG", # "HTML if very simple or else PNG",
	'mw_math_html' => "HTML jika boleh atau PNG", # "HTML if possible or else PNG",
	'mw_math_source' => "Biarkan sebagai TeX (untuk pelayar teks)", # "Leave it as TeX (for text browsers)",
	'mw_math_modern' => "Dicadang untuk pelayar moden", # "Recommended for modern browsers"
	'mw_math_mathml' => 'MathML',

);

require_once( "LanguageUtf8.php" );

class LanguageMs extends LanguageUtf8 {

	function getBookstoreList () {
		global $wgBookstoreListMs;
		return $wgBookstoreListMs;
	}

	function getNamespaces() {
		global $wgNamespaceNamesMs;
		return $wgNamespaceNamesMs;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesMs;
		return $wgNamespaceNamesMs[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesMs;

		foreach ( $wgNamespaceNamesMs as $i => $n ) {
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
		global $wgQuickbarSettingsMs;
		return $wgQuickbarSettingsMs;
	}

	function getSkinNames() {
		global $wgSkinNamesMs;
		return $wgSkinNamesMs;
	}

	function getDateFormats() {
		global $wgDateFormatsMs;
		return $wgDateFormatsMs;
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesMs;
		return $wgValidSpecialPagesMs;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesMs;
		return $wgSysopSpecialPagesMs;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesMs;
		return $wgDeveloperSpecialPagesMs;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesMs;
		return $wgAllMessagesMs[$key];
	}
}

?>
