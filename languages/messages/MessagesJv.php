<?php
/** Javanese (Basa Jawa)
 *
 * @addtogroup Language
 *
 * @author Niklas Laxström
 *
 * @copyright Copyright © 2006, Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */
$fallback = 'id';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Astamiwa',
	NS_MAIN             => '',
	NS_TALK             => 'Dhiskusi',
	NS_USER             => 'Panganggo',
	NS_USER_TALK        => 'Dhiskusi_Panganggo',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => 'Dhiskusi_$1',
	NS_IMAGE            => 'Gambar',
	NS_IMAGE_TALK       => 'Dhiskusi_Gambar',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Dhiskusi_MediaWiki',
	NS_TEMPLATE         => 'Cithakan',
	NS_TEMPLATE_TALK    => 'Dhiskusi_Cithakan',
	NS_HELP             => 'Pitulung',
	NS_HELP_TALK        => 'Dhiskusi_Pitulung',
	NS_CATEGORY         => 'Kategori',
	NS_CATEGORY_TALK    => 'Dhiskusi_Kategori'
);

$namespaceAliases = array(
	'Gambar_Dhiskusi' => NS_IMAGE_TALK,
	'MediaWiki_Dhiskusi' => NS_MEDIAWIKI_TALK,
	'Cithakan_Dhiskusi' => NS_TEMPLATE_TALK,
	'Pitulung_Dhiskusi' => NS_HELP_TALK,
	'Kategori_Dhiskusi' => NS_CATEGORY_TALK,
);

$messages = array(
# User preference toggles
'tog-rememberpassword' => 'Éling tembung sandhi ing saben sèsi',

'underline-always' => 'Tansah',

# Dates
'sunday'       => 'Minggu',
'monday'       => 'Senèn',
'tuesday'      => 'Slasa',
'wednesday'    => 'Rebo',
'thursday'     => 'Kemis',
'friday'       => 'Jemuwah',
'saturday'     => 'Setu',
'sun'          => 'Min',
'mon'          => 'Sen',
'tue'          => 'Sel',
'wed'          => 'Rab',
'thu'          => 'Kam',
'fri'          => 'Jum',
'sat'          => 'Sab',
'january'      => 'Januari',
'february'     => 'Februari',
'march'        => 'Maret',
'may_long'     => 'Mei',
'june'         => 'Juni',
'july'         => 'Juli',
'august'       => 'Agustus',
'october'      => 'Oktober',
'december'     => 'Desember',
'january-gen'  => 'Januari',
'february-gen' => 'Februari',
'march-gen'    => 'Maret',
'may-gen'      => 'Mei',
'june-gen'     => 'Juni',
'july-gen'     => 'Juli',
'august-gen'   => 'Agustus',
'october-gen'  => 'Oktober',
'december-gen' => 'Desember',
'may'          => 'Mei',
'aug'          => 'Agu',
'oct'          => 'Okt',
'dec'          => 'Des',

# Bits of text used by many pages
'categories'      => 'Kategori Kaca',
'category_header' => 'Artikel-artikel wonten ing kategori "$1"',

'about'          => 'Prakawis',
'cancel'         => 'Batal',
'qbfind'         => 'Golèk',
'qbspecialpages' => 'Kaca-kaca Astamiwa',
'mypage'         => 'Panggonanku',
'mytalk'         => 'Gunemanku',
'anontalk'       => 'Dhiskusi IP puniki',
'navigation'     => 'Pandhu Arah',

'returnto'         => 'Wangsul dumugi $1.',
'tagline'          => 'Saka {{SITENAME}}',
'help'             => 'Pitulung',
'search'           => 'Golek',
'searchbutton'     => 'Golèk',
'go'               => 'Menyang',
'searcharticle'    => 'Tumuju',
'history'          => 'Sejarah Kaca',
'history_short'    => 'Sejarah Kaca',
'printableversion' => 'Versi Cithak',
'editthispage'     => 'Sunting kaca iki',
'delete'           => 'Ilangana',
'deletethispage'   => 'Busak kaca iki',
'protect'          => 'Reksanen',
'unprotect'        => 'Apus reksa',
'newpage'          => 'Kaca Anyar',
'talkpage'         => 'Diskuseke kaca iki',
'specialpage'      => 'Kaca Astamiwa',
'articlepage'      => 'Mirsani isinipun kaca',
'talk'             => 'Dhiskusi',
'toolbox'          => 'kothak piranti',
'categorypage'     => 'Cobi pirsani kaca kategori',
'otherlanguages'   => 'Basa liyane',
'redirectedfrom'   => '(Dipindhah saka $1)',
'lastmodifiedat'   => 'Kaca iki pungkasan diowahi nalika $2, $1.', # $1 date, $2 time
'jumptonavigation' => 'pandhu arah',
'jumptosearch'     => 'golèk',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Prakawis {{SITENAME}}',
'aboutpage'         => 'Project:Prakawis',
'bugreports'        => 'Laporan kasalahan',
'copyright'         => 'Kabèh teks kasedyaaké ing ngisoré $1.',
'currentevents'     => 'Warta wigati',
'currentevents-url' => 'Warta wigati',
'disclaimers'       => 'Panyangkalan',
'edithelp'          => 'Pitulung panyuntingan',
'mainpage'          => 'Kaca Utama',
'portal'            => 'Gapura komunitas',
'sitesupport'       => 'Nyumbang dana',

'badaccess'        => 'mBoten angsal',
'badaccess-group0' => 'Panjenengan mboten pareng nglakoaken tindhakan ingkang panjenengan gayuh.',
'badaccess-group1' => 'Pratingkah ingkang panjenengan suwun namung saged kangge pangguna kelompok $1.',
'badaccess-group2' => 'Pratingkah ingkang panjenengan suwun dipun-watesi kanggé pangguna ing kelompok $1.',
'badaccess-groups' => 'Pratingkah panjenengan dipun-watesi tumrap panganggé ing kelompokipun $1.',

'retrievedfrom'       => 'Sumber artikel iki saka kaca situs web: "$1"',
'youhavenewmessages'  => 'Panjenengan gadhah $1 ($2).',
'newmessageslink'     => 'warta enggal',
'newmessagesdifflink' => 'mirsani bédanipun saking revisi sadèrèngipun',
'toc'                 => 'Bab lan Paragraf',
'hidetoc'             => 'delikna',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-user'      => 'Kaca panganggo',
'nstab-mediawiki' => 'Pariwara',

# General errors
'filerenameerror' => 'Mboten saged ngowahi saking "$1" dados "$2".',
'badarticleerror' => 'Aksi punika mboten saged katindhaaken ing kaca punika.',
'cannotdelete'    => 'mBoten saged mbusak kaca utawi berkas ingkang dipunsuwun.',
'badtitle'        => 'Judhulipun mboten sah',
'badtitletext'    => 'Judhul kaca ingkang panjenengan suwun mboten saged kacakaken, kosong, utawi dados judhul antar-basa utawi judhul antar-wiki. Punika saged ugi wonten satunggal utawi luwih aksara ingkang mboten saged kadadosaken judhul.',
'viewsource'      => 'Tuduhna Sumber',

# Login and logout pages
'logouttitle'                => 'Metu log panganggo',
'logouttext'                 => "Panjenengan sampun medal (oncat) saking cathetan sistem. Panjenengan saged migunaaken {{SITENAME}} kanthi anonim, utawi panjenengan saged mlebet malih . Supados dipun mangertosi bilih wonten kaca ingkang taksih nganggpe panjenengan kacathet ing sistem amargi panjenengan dèrèng mbusak <em>cache</em> ''browser'' panjenengan.",
'loginpagetitle'             => 'Mlebu log panganggo',
'yourname'                   => 'Asma pangageman',
'yourpassword'               => 'tembung sandhi',
'remembermypassword'         => 'Éling tembung sandhi',
'loginproblem'               => '<strong>Ana masalah ing proses mlebu log panjenengan.</strong><br />Sumangga nyoba manèh!',
'alreadyloggedin'            => '<strong>Penganggé $1, panjenengan sejatosipun sampun mlebet!</strong><br />',
'login'                      => 'Mlebu',
'loginprompt'                => "Panjenengan kudu ngaktifaké ''cookies'' supaya bisa mlebu (log in) ing {{SITENAME}}.",
'userlogin'                  => 'Mlebu log / gawé rékening (akun)',
'logout'                     => 'Oncat',
'userlogout'                 => 'Metu log',
'nologin'                    => 'Durung kagungan asma panganggo? $1.',
'createaccount'              => 'Damel akun énggal',
'gotaccount'                 => 'Sampun gadhah akun? $1.',
'gotaccountlink'             => 'Mlebet',
'createaccountmail'          => "liwat layang-e (''e-mail'')",
'badretype'                  => 'Sandhi panjenengan mboten gathuk',
'yourrealname'               => 'Asma sejatosipun *',
'yourlanguage'               => 'Basa ingkang kaginaaken:',
'yournick'                   => 'Asma sesinglon/samaran (kagem tapak asma):',
'badsig'                     => 'Tapak asmanipun klentu; cek tag HTML.',
'loginerror'                 => 'Kesalahan mlebu log',
'nocookiesnew'               => "Rékening utawa akun panganggo panjenengan wis digawé, nanging panjenengan durung mlebu log. {{SITENAME}} nggunakaké ''cookies'' kanggo  log panganggo. ''Cookies'' ing panjelajah web panjengengan dipatèni. Mangga diaktifaké lan mlebu log manèh mawa jeneng panganggo lan tembung sandhi panjenengan.",
'loginsuccesstitle'          => 'Bisa suksès mlebu log',
'loginsuccess'               => "'''Panjenengan sapunika mlebet ing {{SITENAME}} kanthi asma \"\$1\".'''",
'nosuchuser'                 => 'Mboten wonten panganggé mawi nami "$1". Cobi dipunpriksa malih éjaanipun, utawi mangga ngagem formulir ing andhap punika kanggé mbikak akun/rékening énggal.',
'passwordsent'               => 'Tembung sandhi anyar wis dikirim menyang alamat e-mail panjenengan sing wis didaftar kanggo "$1". Mangga mlebu log manèh sawisé nampa e-mail iku.',
'acct_creation_throttle_hit' => 'Nuwun sèwu, panjenengan sampun damel akun $1. Panjenengan mboten saged damel malih.',
'accountcreated'             => 'Akun sampun kacipta.',
'accountcreatedtext'         => 'Akun kanggé $1 sampun kacipta.',

# Edit page toolbar
'bold_sample' => 'Seratan puniki bakal dipun-cithak kandel',
'bold_tip'    => 'Cithak kandel',

# Edit pages
'summary'               => 'Ringkesan',
'minoredit'             => 'Suntingan sithik',
'watchthis'             => 'Tonton artikel iki',
'savearticle'           => 'Simpen',
'preview'               => 'Pratilik',
'showpreview'           => 'Tuduhna dhisik',
'anoneditwarning'       => "'''Kedah dipun-gatèaken:''' Panjenengan mboten mlebet dados panganggé. Alamat internet (IP) panjenengan kacathet wonten ing sajarah kaca punika.",
'blockedtitle'          => 'Panganggem (anggota) punika dipun-blok.',
'blockedtext'           => 'Asma panjenengan utawi alamat IP-nipun sampun dipun-blok dening  $1.
Alesanipun:<br />\'\'$2\'\'<p>Panjengengan saged ngubungi $1 utawi salah satunggalipun saking
[[Project:Administrators|pengurus]] kanggé ngrembag prakawis blok punika.
Cathetan bilih panjenengan mboten kepareng nganggé fitur "ngirim layang elektronik panganggé punika" kejawi panjenengan sampun validasi layak elektronik ing [[{{ns:special}}:Preferences|preferensiku]].
Alamat IP panjenengan inggih punika $3. Dipun-aturi nglebetaken alamat punika ing sedanten pitakènan ingkang dipun-ajoaken.',
'blockedoriginalsource' => "Isi sumber saking '''$1''' kapacak kados ing ngandhap punika:",
'blockededitsource'     => "Teks '''suntingan panjenengan''' ing '''$1''' kapacak kados ing ngandhap punika:",
'whitelistedittitle'    => 'Perlu mlebu log kanggo nyunting',
'whitelistreadtitle'    => 'Perlu mlebu log kanggo maca',
'loginreqlink'          => 'mlebu log',
'loginreqpagetext'      => 'Panjenengan kudu $1 bèn bisa ndeleng kaca liyané.',
'accmailtitle'          => 'Sandhinipun sampun kakirim',
'accmailtext'           => 'Sandhi kanggé "$1" sampun kakirim dugi $2.',
'newarticle'            => '(Anyar)',
'newarticletext'        => "Katonane panjenengan ngetutake pranala artikel sing durung ana.
Manawa arep manulis artikel iki, manggaa. (Tontonen
[[{{ns:project}}:Help|Pitulung]] kanggo informasi sabanjure).
Yen ora sengaja tekan kene, bisa ngeklik pencetan '''back''' wae.",
'previewnote'           => 'Mugi dipun gatekaken menawi punika namung pratilik kemawon, dereng dipun simpen!',
'session_fail_preview'  => '<strong>Nuwun sèwu, suntingan panjenengan ora bisa diolah amarga dhata sèsi kabusak. Coba kirim dhata manèh. Yèn tetep ora bisa, coba log metua lan mlebu log manèh.</strong>',
'editing'               => 'Nyunting $1',
'editconflict'          => 'Konflik sunting: $1',
'yourtext'              => 'Seratan Panjenengan',
'yourdiff'              => 'Bentenipun',
'copyrightwarning'      => 'Tulung dipun-gatosaken menawi sedaya kontribusi kanggé {{SITENAME}} punika dipunanggep dipunluncuraken miturut $2 GNU (mangga priksanen $1 kangge detailipun).
Menawi panjenengan mboten kersa menawi seratan panjenengan bakal dipunsunting kaliyan dipunsebar, sampun dipundèkèk ing ngriki.<br>
Panjenengan ugi janji menawi punapa-punapa ingkang kaserat ing ngriki, karyanipun panjenengan piyambak, utawi dipunsalin saking sumber bébas. <strong>SAMPUN NDEKEK KARYA INGKANG DIPUNREKSA DENING UNDANG-UNDANG HAK CIPTA TANPA IDIN!</strong>',
'protectedpagewarning'  => '<strong>PÈNGET:  Kaca puniki dipunkunci dados namung para pangurus kémawon ingkang saged nyunting puniki.</strong>',
'nocreatetext'          => 'Situs iki ngwatesi panjengan ndamel kaca anyar. Panjenengan bisa bali lan nyunting kaca sing wis ana, utawa mangga [[{{ns:special}}:Userlogin|mlebu log utawa ndaftar]]',

# History pages
'deletedrev' => '[kabusak]',
'histfirst'  => 'Paling lawas',

# Diffs
'difference'              => '(Bedané antarrevisi)',
'compareselectedversions' => 'Mbandhingaken versi ingkang kapilih',

# Search results
'searchresults'     => 'Pituwas pamadosan',
'searchsubtitle'    => "Panjengan madosi '''[[:$1]]'''",
'badquery'          => 'Format panjaluk pamadosan panjenengan klentu',
'showingresults'    => 'Ing ngandhap punika dipuntuduhaken <strong>$1</strong> kasil, wiwitanipun saking #<strong>$2</strong>.',
'showingresultsnum' => 'Ing ngandhap punika dipuntuduhaken <strong>$3</strong> kasil, wiwitanipun saking #<strong>$2</strong>.',
'powersearch'       => 'Golek',
'powersearchtext'   => "Golèk ing bilik jeneng (''namespace''):<br />$1<br />$2 Uga tuduhna kaca pangalihan<br />Golèk $3 $9",

# Preferences page
'preferences'             => 'Konfigurasi',
'mypreferences'           => 'Preferensiku',
'prefsnologin'            => 'Durung mlebu log',
'prefsnologintext'        => 'Panjenengan kudu [[{{ns:special}}:Userlogin|mlebu log]] kanggo nyimpen préférèsi njenengan.',
'qbsettings-none'         => 'Ora ana',
'qbsettings-fixedleft'    => 'Tetep sisih kiwa',
'qbsettings-fixedright'   => 'Tetep sisih tengen',
'qbsettings-floatingleft' => 'Ngambang sisih kiwa',
'changepassword'          => 'Ganti tembung sandhi',
'searchresultshead'       => 'Pamadosan',
'allowemail'              => 'Marengaken panganggé sanèsipun ngirim layang èlèktronik (email).',
'defaultns'               => "Golèk ing bilik jeneng (''namespace'') iki mawa baku:",

# User rights log
'rightsnone' => '(mboten wonten)',

# Recent changes
'recentchanges'   => 'Owah-owahan',
'rcnote'          => 'Ing ngisor iki kapacak owahan-owahan <strong>$1</strong> pungkasan ing  <strong>$2</strong> dina pungkasan iki $3.',
'rcnotefrom'      => 'Ing ngisor iki owah-owahan wiwit <strong>$2</strong> (kapacak nganti <strong>$1</strong> owah-owahan).',
'rclistfrom'      => 'Saiki nuduhaké owah-owahan wiwit tanggal $1',
'rcshowhideminor' => '$1 suntingan sithik',
'rcshowhideliu'   => '$1 panganggo mlebu log',
'rcshowhidemine'  => '$1 suntinganku',
'rclinks'         => 'Tuduhna owah-owahan pungkasan $1 ing $2 dina pungkasan iki.<br />$3',
'diff'            => 'béda',
'hist'            => 'sajarah',
'hide'            => 'Delikna',
'minoreditletter' => 's',
'newpageletter'   => 'A',

# Recent changes linked
'recentchangeslinked' => 'Pranala Pilihan',

# Upload
'upload'            => 'Unggah',
'reuploaddesc'      => 'Wangsul ing formulir pamotan',
'uploadnologin'     => 'Durung mlebu log',
'uploadnologintext' => 'Panjenengan kudu [[{{ns:special}}:Userlogin|mlebu log]] supaya olèh ngunggahaké gambar utawa berkas liyané.',
'filedesc'          => 'Ringkesan',
'badfilename'       => 'Berkas sampun dipunowahi dados "$1".',
'largefileserver'   => 'Berkas puniki langkung ageng tinimbang ingkang saged kaparengaken server.',
'uploadedimage'     => 'gambar "[[$1]]" kaminggahaken',
'destfilename'      => 'Asma berkas ingkang dipun tuju',

# Image list
'ilsubmit'             => 'Golek',
'byname'               => 'miturut jeneng',
'bydate'               => 'miturut tanggal',
'bysize'               => 'miturut ukuran',
'deleteimg'            => 'bsk',
'imagelist_search_for' => 'Golèk jeneng berkas:',

# MIME search
'download' => 'undhuh',

# Statistics
'userstats'     => 'Statistik panganggé',
'sitestatstext' => "Sapunika wonten '''\$1''' kaca total ing ''database''. Ing punika kalebet kaca-kaca \"talk\", prakawis {{SITENAME}}, artikel \"stub\" (rintisan), kaca pangalih (''redirect''), kaliyan kaca-kaca ingkang sanès kaca isi.
Sasanèsipun punika, wonten '''\$2''' kaca ingkang mbokmenawi sah.
Sampun naté wonten '''\$3''' kaca dipun tontonaken kaliyan '''\$4''' kaca naté dipun sunting sasampunipun wiki punika dipun adegaken.
Dados tegesipun rata-rata wonten '''\$5''' suntingan per kaca kaliyan '''\$6''' tayangan per suntingan.",
'userstatstext' => "Wonten '''$1''' panganggé ingkang sampun ndaftar. '''$2''' (utawi '''$4%''') antawisipun punika $5.",

'disambiguations' => 'Kaca disambiguasi',

'brokenredirects'     => 'Pengalihanipun risak',
'brokenredirectstext' => 'Pengalihanipun kaca punika mboten kepanggih sambunganipun.',

# Miscellaneous special pages
'lonelypages'         => 'Kaca tanpa dijagani',
'allpages'            => 'Kabèh kaca',
'prefixindex'         => 'Indeks awalan',
'randompage'          => 'Sembarang Kaca',
'deadendpages'        => 'Kaca-kaca buntu (tanpa pranala)',
'deadendpagestext'    => 'kaca-kaca punika mboten gadhah pranala dumugi pundi mawon wonten ing wiki puniki..',
'protectedpagesempty' => 'Saat ini tidak ada halaman yang sedang dilindungi.',
'specialpages'        => 'Kaca-kaca astamiwa',
'newpages'            => 'Kaca énggal',
'newpages-username'   => 'Asma panganggo:',
'ancientpages'        => 'Kaca-kaca langkung sepuh',
'move'                => 'pindhahen',

'categoriespagetext' => 'Kategori-kategori punika wonten ing wiki.',
'alphaindexline'     => '$1 tumuju $2',

# Special:Allpages
'allpagesfrom'      => 'Kaca-kaca kawiwitan kanthi:',
'allarticles'       => 'Kabèh artikel',
'allinnamespace'    => 'Kabeh kaca ($1 namespace)',
'allnotinnamespace' => 'Sedaya kaca (mboten panggènan asma $1)',
'allpagesprev'      => 'Sadèrèngipun',
'allpagesnext'      => 'Salajengipun',
'allpagessubmit'    => 'Madosi',
'allpagesprefix'    => 'Kapacak kaca-kaca ingkang mawi ater-ater:',
'allpagesbadtitle'  => 'Irah-irahan (judhul) ingkang dipun-gunaaken boten sah utawi nganggé ater-ater (awalan) antar-basa utawi antar-wiki. Irah-irahan punika saged ugi nganggé setunggal aksara utawi luwih ingkang boten saged kagunaaken dados irah-irahan.',

# E-mail user
'mailnologintext' => 'Panjenengan kudu [[{{ns:special}}:Userlogin|mlebu log]] lan kagungan alamat e-mail sing sah ing [[{{ns:special}}:Preferences|preféèrensi]] yèn kersa ngirim layang e-mail kanggo panganggo liya.',

# Watchlist
'watchnologin'       => 'Durung mlebu log',
'watchnologintext'   => 'Panjenengan kudu [[{{ns:special}}:Userlogin|mlebu log]] kanggo ngowahi daftar artikel pilihan.',
'addedwatch'         => 'Sampun katambahaken wonten ing daftar artikel pilihan.',
'watch'              => 'tutana',
'watchthispage'      => 'Periksa kaca iki',

'changed' => 'kaubah',
'created' => 'kadamel',

# Delete/protect/revert
'deletepage'      => 'Busak kaca',
'confirm'         => 'Dhedhes (konfirmasi)',
'excontent'       => "isi sadurungé: '$1'",
'excontentauthor' => "isiné mung arupa: '$1' (lan siji-sijiné sing nyumbang yaiku '$2')",
'confirmdelete'   => 'Konfirmasi pambusakan',
'deletesub'       => '(mBusak "$1")',
'actioncomplete'  => 'Proses tuntas',
'deletedtext'     => '"$1" sampun kabusak. Coba pirsani $2 kanggé log paling énggal kaca ingkang kabusak.',
'deletedarticle'  => 'mbusak "[[$1]]"',
'dellogpage'      => 'Cathetan pambusakan',
'deletionlog'     => 'Cathetan sing dibusak',
'deletecomment'   => 'Alesan dipun-busak',
'rollback'        => 'Mangsulaken suntingan',
'rollbacklink'    => 'balèaké',
'revertpage'      => 'Suntingan [[{{ns:special}}:Contributions/$2|$2]] ([[{{ns:user_talk}}:$2|dhiskusi]]) dipunwangsulaken dhateng ing vèrsi pungkasan déning [[{{ns:user}}:$1|$1]]',
'sessionfailure'  => 'Katoné ana masalah karo sèsi log panjenengan; log panjenengan wis dibatalaké kanggo nyegah pambajakan. Mangga mencèt tombol "back" lan unggahaké manèh kaca sadurungé mlebu log, lan coba manèh.',

# Restrictions (nouns)
'restriction-edit' => 'Panyuntingan',
'restriction-move' => 'Pamindhahan',

# Undelete
'undelete'        => 'Kembalikan halaman yang telah dihapus',
'undeletepage'    => 'Lihat dan kembalikan halaman yang telah dihapus',
'undeletehistory' => 'Jika Anda mengembalikan halaman tersebut, semua revisi akan dikembalikan ke dalam sejarah. Jika sebuah halaman baru dengan nama yang sama telah dibuat sejak penghapusan, revisi yang telah dikembalikan akan kelihatan dalam sejarah dahulu, dan revisi terkini halaman tersebut tidak akan ditimpa secara otomatis.',

# Contributions
'contributions' => 'Sumbangan panganggo',
'mycontris'     => 'Kontribusiku',
'contribsub2'    => 'Kagem $1 ($2)',

# What links here
'whatlinkshere' => 'Pranala menyang kaca iki',

# Block/unblock
'badipaddress'       => 'Alamat IP klèntu',
'blocklistline'      => '$1, $2 mblokir $3 ($4)',
'anononlyblock'      => 'namung anon',
'createaccountblock' => 'ndamelipun akun dipunblokir',
'contribslink'       => 'sumbangan',
'autoblocker'        => 'Panjenengan otomatis dipun-blok amargi nganggé alamat protokol internet (IP) ingkang sami kaliyan "[[User:$1|$1]]". Alesanipun $1 dipun blok inggih punika "\'\'\'$2\'\'\'"',
'blocklogentry'      => 'mblokir "[[$1]]" dipun watesi wedalipun $2 $3',
'blocklogtext'       => 'This is a log of user blocking and unblocking actions. Automatically
blocked IP addresses are not listed. See the [[{{ns:special}}:Ipblocklist|IP block list]] for
the list of currently operational bans and blocks.',

# Move page
'movepage'                => 'Mindhah kaca',
'movearticle'             => 'Pindhah kaca',
'movenologin'             => 'Durung mlebu log',
'movenologintext'         => 'Panjenengan kudu dadi panganggo sing wis ndaftar lan wis [[{{ns:special}}:Userlogin|mlebu log]] kanggo mindhah kaca.',
'articleexists'           => 'Satunggalipun kaca kanthi asma punika sampun wonten, utawi asma ingkang panjenengan pendhet mboten leres. Sumangga nyobi asma sanèsipun.',
'movedto'                 => 'dipindhah menyang',
'1movedto2'               => '$1 dipun-alihaken menyang $2',
'1movedto2_redir'         => '[[$1]] dipunalihaken menyang [[$2]] via pangalihan',
'revertmove'              => 'balèaké',
'delete_and_move'         => 'busak lan kapindahaken',
'delete_and_move_confirm' => 'Ya, busak kaca iku.',

# Namespace 8 related
'allmessages'               => 'Kabeh Laporan',
'allmessagesname'           => 'Asma (jeneng)',
'allmessagescurrent'        => 'Teks saiki',
'allmessagestext'           => 'Punika pesen-pesen saking sistem ingkang kacawisaken wonten ing  MediaWiki namespace.',
'allmessagesnotsupportedUI' => 'Basa tampilan panjenengan saiki, <strong>$1</strong> mboten kareksa dèning {{ns:special}}:AllMessages ing situs punika.',
'allmessagesfilter'         => 'Saringan jeneng pesen:',
'allmessagesmodified'       => 'Namung tampilanipun ingkang owah',

# Attribution
'anonymous'        => 'Panganggé {{SITENAME}} ingkang mboten kinawruhan.',
'lastmodifiedatby' => 'Kaca iki pungkasan diowahi  $2, $1 déning $3.', # $1 date, $2 time, $3 user
'and'              => 'lan',

# Spam protection
'categoryarticlecount' => 'Wonten $1 artikel ing kategori punika.',

# Image deletion
'deletedrevision' => 'Revisi dangu ingkang dipunbusak $1',

# E-mail address confirmation
'confirmemail_success' => 'Alamat e-mail panjenengan wis dikonfirmasi. Saiki panjenengan bisa log mlebu lan wiwit nganggo wiki.',

# Delete conflict
'deletedwhileediting' => 'Wara-wara: Kaca punika sampun kabusak sasampunipun panjenengan miwiti nyunting!',

# HTML dump
'redirectingto' => 'Dipun-alihaken tumuju [[$1]]...',

# action=purge
'confirm_purge' => "Ngilangaken ''cache'' kaca punika?

$1",

'youhavenewmessagesmulti' => 'Panjenengan angsal pesen-pesen ènggal $1',

'articletitles' => "Artikel ingkang dipun-wiwiti nganggé ''$1''",

'loginlanguagelabel' => 'Basa: $1',

# Multipage image navigation
'imgmultigo' => 'Golèk!',

# Table pager
'ascending_abbrev'         => 'minggah',
'table_pager_limit_submit' => 'Golèk',

# Auto-summaries
'autoredircomment' => 'Kaalihaken tumuju [[$1]]', # This should be changed to the new naming convention, but existed beforehand
'autosumm-new'     => 'Kaca énggal: $1',

);


