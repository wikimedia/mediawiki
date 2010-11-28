<?php
/** Indonesian (Bahasa Indonesia)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author -iNu-
 * @author Bennylin
 * @author Borgx
 * @author Farras
 * @author Irwangatot
 * @author IvanLanin
 * @author Iwan Novirion
 * @author Kenrick95
 * @author McDutchie
 * @author Meursault2004
 * @author Remember the dot
 * @author Rex
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$separatorTransformTable = array( ',' => '.', '.' => ',' );

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Istimewa',
	NS_TALK             => 'Pembicaraan',
	NS_USER             => 'Pengguna',
	NS_USER_TALK        => 'Pembicaraan_Pengguna',
	NS_PROJECT_TALK     => 'Pembicaraan_$1',
	NS_FILE             => 'Berkas',
	NS_FILE_TALK        => 'Pembicaraan_Berkas',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Pembicaraan_MediaWiki',
	NS_TEMPLATE         => 'Templat',
	NS_TEMPLATE_TALK    => 'Pembicaraan_Templat',
	NS_HELP             => 'Bantuan',
	NS_HELP_TALK        => 'Pembicaraan_Bantuan',
	NS_CATEGORY         => 'Kategori',
	NS_CATEGORY_TALK    => 'Pembicaraan_Kategori',
);

$namespaceAliases = array(
	'Gambar_Pembicaraan'    => NS_FILE_TALK,
	'MediaWiki_Pembicaraan' => NS_MEDIAWIKI_TALK,
	'Templat_Pembicaraan'   => NS_TEMPLATE_TALK,
	'Bantuan_Pembicaraan'   => NS_HELP_TALK,
	'Kategori_Pembicaraan'  => NS_CATEGORY_TALK,
	'Gambar'                => NS_FILE,
	'Pembicaraan_Gambar'    => NS_FILE_TALK,
	'Bicara'                => NS_TALK,
	'Bicara_Pengguna'       => NS_USER_TALK,
);

$bookstoreList = array(
	'AddALL' => 'http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN',
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1',
	'Barnes & Noble' => 'http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1',
	'Bhinneka.com bookstore' => 'http://www.bhinneka.com/Buku/Engine/search.asp?fisbn=$1',
	'Gramedia Cyberstore (via Google)' => 'http://www.google.com/search?q=%22ISBN+:+$1%22+%22product_detail%22+site:www.gramediacyberstore.com+OR+site:www.gramediaonline.com+OR+site:www.kompas.com&hl=id',
	'PriceSCAN' => 'http://www.pricescan.com/books/bookDetail.asp?isbn=$1',
);

$magicWords = array(
	'redirect'              => array( '0', '#ALIH', '#REDIRECT' ),
	'notoc'                 => array( '0', '__TANPADAFTARISI__', '__NIRDASI__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__TANPAGALERI__', '__NIRGAL__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__PAKSADAFTARISI__', '__PAKSADASI__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__DAFTARISI__', '__DASI__', '__TOC__' ),
	'noeditsection'         => array( '0', '__TANPASUNTINGANBAGIAN__', '__NIRSUBA__', '__NOEDITSECTION__' ),
	'noheader'              => array( '0', '__TANPAKEPALA__', '__NIRLA__', '__NOHEADER__' ),
	'currentmonth'          => array( '1', 'BULANKINI', 'BULANKINI2', 'BUKIN', 'BUKIN2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'         => array( '1', 'BULANKINI1', 'BUKIN1', 'CURRENTMONTH1' ),
	'currentmonthname'      => array( '1', 'NAMABULANKINI', 'NAMBUKIN', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'NAMAJENDERBULANKINI', 'NAMJENBUKIN', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'NAMASINGKATBULANKINI', 'BULANINISINGKAT', 'NAMSINGBUKIN', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'HARIKINI', 'HARKIN', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'HARIKINI2', 'HARKIN2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'NAMAHARIKINI', 'NAMHARKIN', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'TAHUNKINI', 'TAKIN', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'WAKTUKINI', 'WAKIN', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'JAMKINI', 'JAKIN', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'BULANLOKAL', 'BULANLOKAL2', 'BULOK', 'BULOK2', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'           => array( '1', 'BULANLOKAL1', 'BULOK1', 'LOCALMONTH1' ),
	'localmonthname'        => array( '1', 'NAMABULANLOKAL', 'NAMBULOK', 'LOCALMONTHNAME' ),
	'localmonthnamegen'     => array( '1', 'NAMAJENDERBULANLOKAL', 'NAMJENBULOK', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'      => array( '1', 'NAMASINGKATBULANLOKAL', 'NAMSINGBULOK', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'HARILOKAL', 'HALOK', 'LOCALDAY' ),
	'localday2'             => array( '1', 'HARILOKAL2', 'HALOK2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'NAMAHARILOKAL', 'NAMHALOK', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'TAHUNLOKAL', 'TALOK', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'WAKTULOKAL', 'WALOK', 'LOCALTIME' ),
	'localhour'             => array( '1', 'JAMLOKAL', 'JALOK', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'JUMLAHHALAMAN', 'JUMMAN', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'JUMLAHARTIKEL', 'JUMKEL', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'JUMLAHBERKAS', 'JUMKAS', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'JUMLAHPENGGUNA', 'JUMPENG', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'JUMLAHPENGGUNAAKTIF', 'JUMPENGTIF', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', 'JUMLAHSUNTINGAN', 'JUMTING', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'JUMLAHTAMPILAN', 'JUMTAM', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'NAMAHALAMAN', 'NAMMAN', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'NAMAHALAMANE', 'NAMMANE', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'RUANGNAMA', 'RUNAM', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'RUANGNAMAE', 'RUNAME', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'RUANGBICARA', 'RUBIR', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'RUANGBICARAE', 'RUBIRE', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'RUANGUTAMA', 'RUANGARTIKEL', 'RUTAMA', 'RUTIKEL', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', 'RUANGUTAMAE', 'RUANGARTIKELE', 'RUTAMAE', 'RUKELE', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'NAMAHALAMANLENGKAP', 'NAMALENGKAPHALAMAN', 'NAMMANKAP', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'AMAHALAMANLENGKAPE', 'NAMALENGKAPHALAMANE', 'NAMMANKAPE', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'NAMASUBHALAMAN', 'NAMAUPAHALAMAN', 'NAMUMAN', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'NAMASUBHALAMANE', 'NAMAUPAHALAMANE', 'NAMUMANE', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'NAMAHALAMANDASAR', 'NAMADASARHALAMAN', 'NAMMANSAR', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'NAMAHALAMANDASARE', 'NAMADASARHALAMANE', 'NAMMANSARE', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'NAMAHALAMANBICARA', 'NAMMANBIR', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'NAMAHALAMANBICARAE', 'NAMMANBIRE', 'TALKPAGENAMEE' ),
	'subjectpagename'       => array( '1', 'NAMAHALAMANUTAMA', 'NAMAHALAMANARTIKEL', 'NAMMANTAMA', 'NAMMANTIKEL', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'      => array( '1', 'NAMAHALAMANUTAMAE', 'NAMAHALAMANARTIKELE', 'NAMMANTAMAE', 'NAMMANTIKELE', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                   => array( '0', 'PSN:', 'PESAN:', 'MSG:' ),
	'subst'                 => array( '0', 'GNT:', 'GANTI:', 'SUBST:' ),
	'msgnw'                 => array( '0', 'TPL:', 'MSGNW:' ),
	'img_thumbnail'         => array( '1', 'jmpl', 'jempol', 'mini', 'miniatur', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'jmpl=$1', 'jempol=$1', 'mini=$1', 'miniatur=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'ka', 'kanan', 'right' ),
	'img_left'              => array( '1', 'ki', 'kiri', 'left' ),
	'img_none'              => array( '1', 'nir', 'tanpa', 'none' ),
	'img_center'            => array( '1', 'pus', 'pusat', 'center', 'centre' ),
	'img_framed'            => array( '1', 'bing', 'bingkai', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'nirbing', 'tanpabingkai', 'frameless' ),
	'img_page'              => array( '1', 'hal=$1', 'halaman=$1', 'hal $1', 'halaman $1', 'page=$1', 'page $1' ),
	'img_upright'           => array( '1', 'lurus', 'lurus=$1', 'lurus $1', 'tegak', 'tegak=$1', 'tegak $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'            => array( '1', 'tepi', 'batas', 'border' ),
	'img_baseline'          => array( '1', 'gada', 'garis_dasar', 'baseline' ),
	'img_sub'               => array( '1', 'upa', 'sub' ),
	'img_top'               => array( '1', 'atas', 'top' ),
	'img_text_top'          => array( '1', 'atek', 'atas-teks', 'text-top' ),
	'img_middle'            => array( '1', 'tengah', 'middle' ),
	'img_bottom'            => array( '1', 'bawah', 'bottom' ),
	'img_text_bottom'       => array( '1', 'batek', 'bawah-teks', 'text-bottom' ),
	'img_link'              => array( '1', 'pra=$1', 'pranala=$1', 'link=$1' ),
	'img_alt'               => array( '1', 'al=$1', 'alternatif=$1', 'alt=$1' ),
	'sitename'              => array( '1', 'NAMASITUS', 'NAMSIT', 'SITENAME' ),
	'ns'                    => array( '0', 'RN:', 'RUNAM:', 'NS:' ),
	'localurl'              => array( '0', 'URLLOKAL', 'LOCALURL:' ),
	'localurle'             => array( '0', 'URLLOKALE', 'LOCALURLE:' ),
	'server'                => array( '0', 'PELADEN', 'SERVER' ),
	'servername'            => array( '0', 'NAMAPELADEN', 'NAMASERVER', 'NAMPEL', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'LOKASISKRIP', 'SCRIPTPATH' ),
	'grammar'               => array( '0', 'TATABAHASA', 'TASA', 'GRAMMAR:' ),
	'gender'                => array( '0', 'JANTINA', 'GENDER:' ),
	'notitleconvert'        => array( '0', '__TANPAKONVERSIJUDUL__', '__NIRKODUL__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'      => array( '0', '__TANPAKONVERSIISI__', '__NIRKOSI__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'           => array( '1', 'MINGGUKINI', 'MIKIN', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'HARIDALAMMINGGU', 'HADAMI', 'CURRENTDOW' ),
	'localweek'             => array( '1', 'MINGGULOKAL', 'MIKAL', 'LOCALWEEK' ),
	'localdow'              => array( '1', 'HARIDALAMMINGGULOKAL', 'HADAMIKAL', 'LOCALDOW' ),
	'revisionid'            => array( '1', 'IDREVISI', 'IREV', 'REVISIONID' ),
	'revisionday'           => array( '1', 'HARIREVISI', 'HAREV', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'HARIREVISI2', 'HAREV2', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'BULANREVISI', 'BUREV', 'REVISIONMONTH' ),
	'revisionyear'          => array( '1', 'TAHUNREVISI', 'TAREV', 'REVISIONYEAR' ),
	'revisiontimestamp'     => array( '1', 'STEMPELWAKTUREVISI', 'REKAMWAKTUREVISI', 'REVISIONTIMESTAMP' ),
	'revisionuser'          => array( '1', 'PENGGUNAREVISI', 'REVISIONUSER' ),
	'plural'                => array( '0', 'JAMAK:', 'PLURAL:' ),
	'fullurl'               => array( '0', 'URLLENGKAP:', 'FULLURL:' ),
	'fullurle'              => array( '0', 'URLLENGKAPE', 'FULLURLE:' ),
	'lcfirst'               => array( '0', 'AKC:', 'AWALKECIL:', 'LCFIRST:' ),
	'ucfirst'               => array( '0', 'ABS:', 'AWALBESAR:', 'UCFIRST:' ),
	'lc'                    => array( '0', 'KC:', 'KECIL:', 'HURUFKECIL:', 'LC:' ),
	'uc'                    => array( '0', 'BS:', 'BESAR:', 'HURUFBESAR:', 'UC:' ),
	'raw'                   => array( '0', 'MENTAH:', 'RAW:' ),
	'displaytitle'          => array( '1', 'JUDULTAMPILAN', 'JUTAM', 'DISPLAYTITLE' ),
	'rawsuffix'             => array( '1', 'M', 'R' ),
	'newsectionlink'        => array( '1', '__PRANALABAGIANBARU__', '__PRABABA__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'      => array( '1', '_TANPAPRANALABAGIANBARU__', '__NIRPRABABA__', '__NONEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'VERSIKINI', 'VERKIN', 'CURRENTVERSION' ),
	'urlencode'             => array( '0', 'KODEURL:', 'KODU:', 'URLENCODE:' ),
	'anchorencode'          => array( '0', 'KODEJANGKAR', 'KOJANG', 'ANCHORENCODE' ),
	'currenttimestamp'      => array( '1', 'STEMPELWAKTUKINI', 'STEMWAKIN', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', 'STEMPELWAKTULOKAL', 'STEMWAKAL', 'LOCALTIMESTAMP' ),
	'directionmark'         => array( '1', 'MARKAARAH', 'MARRAH', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'              => array( '0', '#BAHASA:', '#BHS:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'BAHASAISI', 'BHSISI', 'BASI', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', 'HALAMANDIRUANGNAMA:', 'HALDIRN', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', 'JUMLAHADMIN', 'JUMLAHPENGURUS', 'JUMAD', 'JURUS', 'NUMBEROFADMINS' ),
	'formatnum'             => array( '0', 'FORMATANGKA', 'FORANG', 'FORMATNUM' ),
	'padleft'               => array( '0', 'ISIKIRI', 'IKI', 'PADLEFT' ),
	'padright'              => array( '0', 'ISIKANAN', 'IKA', 'PADRIGHT' ),
	'special'               => array( '0', 'istimewa', 'spesial', 'special' ),
	'defaultsort'           => array( '1', 'URUTANBAKU:', 'UBUR:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'              => array( '0', 'LOKASIBERKAS:', 'LOBER:', 'FILEPATH:' ),
	'tag'                   => array( '0', 'kata_kunci', 'takun', 'tag' ),
	'hiddencat'             => array( '1', '__KATEGORITERSEMBUNYI__', '__KATSEM__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'HALAMANDIKATEGORI', 'HALDIKAT', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'BESARHALAMAN', 'BESMAN', 'PAGESIZE' ),
	'index'                 => array( '1', '__INDEKS__', '__INDEX__' ),
	'noindex'               => array( '1', '__TANPAINDEKS__', '__NIRDEKS__', '__NOINDEX__' ),
	'numberingroup'         => array( '1', 'JUMLAHDIKELOMPOK', 'JULDIPOK', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'        => array( '1', '__PENGALIHANSTATIK__', '__PENGALIHANSTATIS__', '__PETIK__', '__PETIS__', '__STATICREDIRECT__' ),
	'protectionlevel'       => array( '1', 'TINGKATPERLINDUNGAN', 'TIPER', 'PROTECTIONLEVEL' ),
	'formatdate'            => array( '0', 'formattanggal', 'formatdate', 'dateformat' ),
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Pengalihan_ganda', 'PengalihanGanda' ),
	'BrokenRedirects'           => array( 'Pengalihan_rusak', 'PengalihanRusak' ),
	'Disambiguations'           => array( 'Disambiguasi' ),
	'Userlogin'                 => array( 'Masuk_log', 'MasukLog' ),
	'Userlogout'                => array( 'Keluar_log', 'KeluarLog' ),
	'CreateAccount'             => array( 'Buat_akun', 'BuatAkun' ),
	'Preferences'               => array( 'Preferensi' ),
	'Watchlist'                 => array( 'Daftar_pantauan', 'DaftarPantauan' ),
	'Recentchanges'             => array( 'Perubahan_terbaru', 'PerubahanTerbaru', 'RC', 'PT' ),
	'Upload'                    => array( 'Pengunggahan', 'Pemuatan', 'Unggah' ),
	'Listfiles'                 => array( 'Daftar_berkas', 'DaftarBerkas' ),
	'Newimages'                 => array( 'Berkas_baru', 'BerkasBaru' ),
	'Listusers'                 => array( 'Daftar_pengguna', 'DaftarPengguna' ),
	'Listgrouprights'           => array( 'Daftar_hak_kelompok', 'DaftarHakKelompok', 'DaftarHak' ),
	'Statistics'                => array( 'Statistik' ),
	'Randompage'                => array( 'Halaman_sembarang', 'HalamanSembarang' ),
	'Lonelypages'               => array( 'Halaman_yatim', 'Halaman_tak_bertuan', 'HalamanYatim', 'HalamanTakBertuan' ),
	'Uncategorizedpages'        => array( 'Halaman_tak_terkategori', 'HalamanTakTerkategori' ),
	'Uncategorizedcategories'   => array( 'Kategori_tak_terkategori', 'KategoriTakTerkategori' ),
	'Uncategorizedimages'       => array( 'Berkas_tak_terkategori', 'BerkasTakTerkategori' ),
	'Uncategorizedtemplates'    => array( 'Templat_tak_terkategori', 'TemplatTakTerkategori' ),
	'Unusedcategories'          => array( 'Kategori_kosong', 'KategoriKosong', 'Kategori_tak_terpakai', 'KategoriTakTerpakai' ),
	'Unusedimages'              => array( 'Berkas_tak_terpakai', 'BerkasTakTerpakai', 'Berkas_tak_digunakan', 'BerkasTakDigunakan' ),
	'Wantedpages'               => array( 'Halaman_yang_diinginkan', 'HalamanDiinginkan' ),
	'Wantedcategories'          => array( 'Kategori_yang_diinginkan', 'KategoriDiinginkan' ),
	'Wantedfiles'               => array( 'Berkas_yang_diinginkan', 'BerkasDiinginkan' ),
	'Wantedtemplates'           => array( 'Templat_yang_diinginkan', 'TemplatDiinginkan' ),
	'Mostlinked'                => array( 'Halaman_paling_digunakan', 'HalamanPalingDigunakan' ),
	'Mostlinkedcategories'      => array( 'Kategori_paling_digunakan', 'KategoriPalingDigunakan' ),
	'Mostlinkedtemplates'       => array( 'Templat_paling_digunakan', 'TemplatPalingDigunakan' ),
	'Mostimages'                => array( 'Berkas_paling_digunakan', 'BerkasPalingDigunakan' ),
	'Mostcategories'            => array( 'Kategori_terbanyak', 'KategoriTerbanyak' ),
	'Mostrevisions'             => array( 'Perubahan_terbanyak', 'PerubahanTerbanyak' ),
	'Fewestrevisions'           => array( 'Perubahan_tersedikit', 'PerubahanTersedikit' ),
	'Shortpages'                => array( 'Halaman_pendek', 'HalamanPendek' ),
	'Longpages'                 => array( 'Halaman_panjang', 'HalamanPanjang' ),
	'Newpages'                  => array( 'Halaman_baru', 'HalamanBaru' ),
	'Ancientpages'              => array( 'Halaman_lama', 'HalamanLama', 'Artikel_lama' ),
	'Deadendpages'              => array( 'Halaman_buntu', 'HalamanBuntu' ),
	'Protectedpages'            => array( 'Halaman_yang_dilindungi', 'HalamanDilindungi' ),
	'Protectedtitles'           => array( 'Judul_yang_dilindungi', 'JudulDilindungi' ),
	'Allpages'                  => array( 'Daftar_halaman', 'DaftarHalaman' ),
	'Prefixindex'               => array( 'Indeks_awalan', 'IndeksAwalan' ),
	'Ipblocklist'               => array( 'Daftar_pemblokiran', 'DaftarPemblokiran' ),
	'Unblock'                   => array( 'Pembatalan_pemblokiran', 'PembatalanPemblokiran' ),
	'Specialpages'              => array( 'Halaman_istimewa', 'HalamanIstimewa' ),
	'Contributions'             => array( 'Kontribusi_pengguna', 'KontribusiPengguna', 'Kontribusi' ),
	'Emailuser'                 => array( 'Surel_pengguna', 'SurelPengguna' ),
	'Confirmemail'              => array( 'Konfirmasi_surel', 'KonfirmasiSurel' ),
	'Whatlinkshere'             => array( 'Pranala_balik', 'PranalaBalik' ),
	'Recentchangeslinked'       => array( 'Perubahan_terkait', 'PerubahanTerkait' ),
	'Movepage'                  => array( 'Pindahkan_halaman', 'PindahkanHalaman' ),
	'Blockme'                   => array( 'Blokir_saya', 'BlokirSaya' ),
	'Booksources'               => array( 'Sumber_buku', 'SumberBuku' ),
	'Categories'                => array( 'Daftar_kategori', 'DaftarKategori', 'Kategori' ),
	'Export'                    => array( 'Ekspor' ),
	'Version'                   => array( 'Versi' ),
	'Allmessages'               => array( 'Pesan_sistem', 'PesanSistem' ),
	'Log'                       => array( 'Catatan' ),
	'Blockip'                   => array( 'Blokir_pengguna', 'BlokirPengguna' ),
	'Undelete'                  => array( 'Pembatalan_penghapusan', 'PembatalanPenghapusan' ),
	'Import'                    => array( 'Impor' ),
	'Lockdb'                    => array( 'Kunci_basis_data', 'KunciBasisData' ),
	'Unlockdb'                  => array( 'Buka_kunci_basis_data', 'BukaKunciBasisData' ),
	'Userrights'                => array( 'Hak_pengguna', 'HakPengguna' ),
	'MIMEsearch'                => array( 'Pencarian_MIME', 'PencarianMIME' ),
	'FileDuplicateSearch'       => array( 'Pencarian_berkas_duplikat', 'PencarianBerkasDuplikat' ),
	'Unwatchedpages'            => array( 'Halaman_tak_terpantau', 'HalamanTakTerpantau' ),
	'Listredirects'             => array( 'Daftar_pengalihan', 'DaftarPengalihan' ),
	'Revisiondelete'            => array( 'Hapus_revisi', 'HapusRevisi' ),
	'Unusedtemplates'           => array( 'Templat_tak_terpakai', 'TemplatTakTerpakai' ),
	'Randomredirect'            => array( 'Pengalihan_sembarang', 'PengalihanSembarang' ),
	'Mypage'                    => array( 'Halaman_saya', 'HalamanSaya' ),
	'Mytalk'                    => array( 'Pembicaraan_saya', 'PembicaraanSaya' ),
	'Mycontributions'           => array( 'Kontribusi_saya', 'KontribusiSaya' ),
	'Listadmins'                => array( 'Daftar_pengurus', 'DaftarPengurus' ),
	'Listbots'                  => array( 'Daftar_bot', 'DaftarBot' ),
	'Popularpages'              => array( 'Halaman_populer', 'HalamanPopuler' ),
	'Search'                    => array( 'Pencarian', 'Cari' ),
	'Resetpass'                 => array( 'Ganti_sandi', 'GantiSandi' ),
	'Withoutinterwiki'          => array( 'Tanpa_interwiki', 'TanpaInterwiki' ),
	'MergeHistory'              => array( 'Riwayat_penggabungan', 'RiwayatPenggabungan' ),
	'Filepath'                  => array( 'Lokasi_arsip', 'LokasiArsip' ),
	'Invalidateemail'           => array( 'Batalkan_validasi_surel', 'BatalkanValidasiSurel' ),
	'Blankpage'                 => array( 'Halaman_kosong', 'HalamanKosong' ),
	'LinkSearch'                => array( 'Pranala_luar', 'PranalaLuar', 'Pencarian_pranala', 'PencarianPranala' ),
	'DeletedContributions'      => array( 'Kontribusi_yang_dihapus', 'KontribusiDihapus' ),
	'Tags'                      => array( 'Tag' ),
	'Activeusers'               => array( 'Pengguna_aktif', 'PenggunaAktif' ),
	'RevisionMove'              => array( 'Revisi_pemindahan', 'RevisiPemindahan' ),
	'ComparePages'              => array( 'Bandingkan_halaman', 'BandingkanHalaman' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Garis bawahi pranala:',
'tog-highlightbroken'         => 'Format pranala patah <a href="" class="new">seperti ini</a> (pilihan: seperti ini<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Ratakan paragraf',
'tog-hideminor'               => 'Sembunyikan suntingan kecil di perubahan terbaru',
'tog-hidepatrolled'           => 'Sembunyikan suntingan terpatroli di perubahan terbaru',
'tog-newpageshidepatrolled'   => 'Sembunyikan halaman terpatroli dari daftar halaman baru',
'tog-extendwatchlist'         => 'Kembangkan daftar pantauan untuk menunjukkan semua perubahan, tidak hanya yang terbaru',
'tog-usenewrc'                => 'Gunakan tampilan perubahan terbaru tingkat lanjut (memerlukan JavaScript)',
'tog-numberheadings'          => 'Beri nomor judul secara otomatis',
'tog-showtoolbar'             => 'Perlihatkan bilah alat penyuntingan',
'tog-editondblclick'          => 'Sunting halaman dengan klik ganda (JavaScript)',
'tog-editsection'             => 'Fungsikan penyuntingan subbagian melalui pranala [sunting]',
'tog-editsectiononrightclick' => 'Fungsikan penyuntingan subbagian dengan mengeklik kanan pada judul bagian (JavaScript)',
'tog-showtoc'                 => 'Perlihatkan daftar isi (untuk halaman yang mempunyai lebih dari 3 subbagian)',
'tog-rememberpassword'        => 'Ingat kata sandi saya di peramban ini (selama $1 {{PLURAL:$1|hari|hari}})',
'tog-watchcreations'          => 'Tambahkan halaman yang saya buat ke daftar pantauan',
'tog-watchdefault'            => 'Tambahkan halaman yang saya sunting ke daftar pantauan',
'tog-watchmoves'              => 'Tambahkan halaman yang saya pindahkan ke daftar pantauan',
'tog-watchdeletion'           => 'Tambahkan halaman yang saya hapus ke daftar pantauan',
'tog-minordefault'            => 'Tandai semua suntingan sebagai suntingan kecil secara baku',
'tog-previewontop'            => 'Perlihatkan pratayang sebelum kotak sunting dan tidak sesudahnya',
'tog-previewonfirst'          => 'Perlihatkan pratayang pada suntingan pertama',
'tog-nocache'                 => 'Nonaktifkan penyinggahan halaman peramban',
'tog-enotifwatchlistpages'    => 'Kirimkan saya surel jika suatu halaman yang saya pantau berubah',
'tog-enotifusertalkpages'     => 'Kirimkan saya surel jika halaman pembicaraan saya berubah',
'tog-enotifminoredits'        => 'Kirimkan saya surel juga pada perubahan kecil',
'tog-enotifrevealaddr'        => 'Tampilkan alamat surel saya pada surel notifikasi',
'tog-shownumberswatching'     => 'Tunjukkan jumlah pemantau',
'tog-oldsig'                  => 'Pratayang tanda tangan:',
'tog-fancysig'                => 'Perlakukan tanda tangan sebagai teks wiki (tanpa suatu pranala otomatis)',
'tog-externaleditor'          => 'Gunakan perangkat lunak pengolah kata luar',
'tog-externaldiff'            => 'Gunakan perangkat lunak luar untuk melihat perbedaan suntingan',
'tog-showjumplinks'           => 'Aktifkan pranala pembantu "langsung ke"',
'tog-uselivepreview'          => 'Gunakan pratayang langsung (JavaScript) (eksperimental)',
'tog-forceeditsummary'        => 'Ingatkan saya bila kotak ringkasan suntingan masih kosong',
'tog-watchlisthideown'        => 'Sembunyikan suntingan saya di daftar pantauan',
'tog-watchlisthidebots'       => 'Sembunyikan suntingan bot di daftar pantauan',
'tog-watchlisthideminor'      => 'Sembunyikan suntingan kecil di daftar pantauan',
'tog-watchlisthideliu'        => 'Sembunyikan suntingan pengguna masuk log di daftar pantauan',
'tog-watchlisthideanons'      => 'Sembunyikan suntingan pengguna anonim di daftar pantauan',
'tog-watchlisthidepatrolled'  => 'Sembunyikan suntingan terpatroli di daftar pantauan',
'tog-nolangconversion'        => 'Matikan konversi varian',
'tog-ccmeonemails'            => 'Kirimkan saya salinan surel yang saya kirimkan ke orang lain',
'tog-diffonly'                => 'Jangan tampilkan isi halaman di bawah perbedaan suntingan',
'tog-showhiddencats'          => 'Tampilkan kategori tersembunyi',
'tog-norollbackdiff'          => 'Jangan tampilkan perbedaan setelah melakukan pengembalian',

'underline-always'  => 'Selalu',
'underline-never'   => 'Tidak pernah',
'underline-default' => 'Bawaan penjelajah web',

# Font style option in Special:Preferences
'editfont-style'     => 'Gaya tulisan komputer pada kotak penyuntingan:',
'editfont-default'   => 'Bawaan penjelajah web',
'editfont-monospace' => 'Tulisan Monospace',
'editfont-sansserif' => 'Tulisan Sans-serif',
'editfont-serif'     => 'Tulisan Serif',

# Dates
'sunday'        => 'Minggu',
'monday'        => 'Senin',
'tuesday'       => 'Selasa',
'wednesday'     => 'Rabu',
'thursday'      => 'Kamis',
'friday'        => 'Jumat',
'saturday'      => 'Sabtu',
'sun'           => 'Min',
'mon'           => 'Sen',
'tue'           => 'Sel',
'wed'           => 'Rab',
'thu'           => 'Kam',
'fri'           => 'Jum',
'sat'           => 'Sab',
'january'       => 'Januari',
'february'      => 'Februari',
'march'         => 'Maret',
'april'         => 'April',
'may_long'      => 'Mei',
'june'          => 'Juni',
'july'          => 'Juli',
'august'        => 'Agustus',
'september'     => 'September',
'october'       => 'Oktober',
'november'      => 'November',
'december'      => 'Desember',
'january-gen'   => 'Januari',
'february-gen'  => 'Februari',
'march-gen'     => 'Maret',
'april-gen'     => 'April',
'may-gen'       => 'Mei',
'june-gen'      => 'Juni',
'july-gen'      => 'Juli',
'august-gen'    => 'Agustus',
'september-gen' => 'September',
'october-gen'   => 'Oktober',
'november-gen'  => 'November',
'december-gen'  => 'Desember',
'jan'           => 'Jan',
'feb'           => 'Feb',
'mar'           => 'Mar',
'apr'           => 'Apr',
'may'           => 'Mei',
'jun'           => 'Jun',
'jul'           => 'Jul',
'aug'           => 'Agu',
'sep'           => 'Sep',
'oct'           => 'Okt',
'nov'           => 'Nov',
'dec'           => 'Des',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategori|Kategori}}',
'category_header'                => 'Halaman dalam kategori "$1"',
'subcategories'                  => 'Subkategori',
'category-media-header'          => 'Media dalam kategori "$1"',
'category-empty'                 => "''Saat ini, tidak terdapat halaman ataupun media dalam kategori ini.''",
'hidden-categories'              => '{{PLURAL:$1|Kategori tersembunyi|Kategori tersembunyi}}',
'hidden-category-category'       => 'Kategori tersembunyi',
'category-subcat-count'          => '{{PLURAL:$2|Kategori ini hanya memiliki satu subkategori berikut.|Kategori ini memiliki {{PLURAL:$1|subkategori|$1 subkategori}} berikut, dari total $2.}}',
'category-subcat-count-limited'  => 'Kategori ini memiliki {{PLURAL:$1|subkategori|$1 subkategori}} berikut.',
'category-article-count'         => '{{PLURAL:$2|Kategori ini hanya memiliki satu halaman berikut.|Kategori ini memiliki {{PLURAL:$1|halaman|$1 halaman}}, dari total $2.}}',
'category-article-count-limited' => 'Kategori ini memiliki {{PLURAL:$1|satu halaman|$1 halaman}} berikut.',
'category-file-count'            => '{{PLURAL:$2|Kategori ini hanya memiliki satu berkas berikut.|Kategori ini memiliki {{PLURAL:$1|berkas|$1 berkas}} berikut, dari total $2.}}',
'category-file-count-limited'    => 'Kategori ini memiliki {{PLURAL:$1|berkas|$1 berkas}} berikut.',
'listingcontinuesabbrev'         => 'samb.',
'index-category'                 => 'Halaman yang diindeks',
'noindex-category'               => 'Halaman yang tidak diindeks',

'mainpagetext'      => "'''MediaWiki telah terpasang dengan sukses'''.",
'mainpagedocfooter' => 'Silakan baca [http://www.mediawiki.org/wiki/Help:Contents/id Panduan Pengguna] untuk cara penggunaan perangkat lunak wiki ini.

== Memulai penggunaan ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings/id Daftar pengaturan konfigurasi]
* [http://www.mediawiki.org/wiki/Manual:FAQ/id Daftar pertanyaan yang sering diajukan mengenai MediaWiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Milis rilis MediaWiki]',

'about'         => 'Tentang',
'article'       => 'Halaman isi',
'newwindow'     => '(buka di jendela baru)',
'cancel'        => 'Batalkan',
'moredotdotdot' => 'Lainnya...',
'mypage'        => 'Halaman saya',
'mytalk'        => 'Pembicaraan saya',
'anontalk'      => 'Pembicaraan IP ini',
'navigation'    => 'Navigasi',
'and'           => '&#32;dan',

# Cologne Blue skin
'qbfind'         => 'Pencarian',
'qbbrowse'       => 'Navigasi',
'qbedit'         => 'Sunting',
'qbpageoptions'  => 'Halaman ini',
'qbpageinfo'     => 'Konteks halaman',
'qbmyoptions'    => 'Halaman saya',
'qbspecialpages' => 'Halaman istimewa',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => 'Bagian baru',
'vector-action-delete'           => 'Hapus',
'vector-action-move'             => 'Pindahkan',
'vector-action-protect'          => 'Lindungi',
'vector-action-undelete'         => 'Pembatalan penghapusan',
'vector-action-unprotect'        => 'Perlindungan',
'vector-simplesearch-preference' => 'Aktifkan pencarian saran yang disempurnakan (hanya kulit Vector)',
'vector-view-create'             => 'Buat',
'vector-view-edit'               => 'Sunting',
'vector-view-history'            => 'Versi terdahulu',
'vector-view-view'               => 'Baca',
'vector-view-viewsource'         => 'Lihat sumber',
'actions'                        => 'Tindakan',
'namespaces'                     => 'Ruang nama',
'variants'                       => 'Varian',

'errorpagetitle'    => 'Kesalahan',
'returnto'          => 'Kembali ke $1.',
'tagline'           => 'Dari {{SITENAME}}',
'help'              => 'Bantuan',
'search'            => 'Cari',
'searchbutton'      => 'Cari',
'go'                => 'Tuju ke',
'searcharticle'     => 'Tuju ke',
'history'           => 'Versi terdahulu halaman',
'history_short'     => 'Versi terdahulu',
'updatedmarker'     => 'diubah sejak kunjungan terakhir saya',
'info_short'        => 'Informasi',
'printableversion'  => 'Versi cetak',
'permalink'         => 'Pranala permanen',
'print'             => 'Cetak',
'edit'              => 'Sunting',
'create'            => 'Buat',
'editthispage'      => 'Sunting halaman ini',
'create-this-page'  => 'Buat halaman ini',
'delete'            => 'Hapus',
'deletethispage'    => 'Hapus halaman ini',
'undelete_short'    => 'Batal hapus $1 {{PLURAL:$1|suntingan|suntingan}}',
'protect'           => 'Lindungi',
'protect_change'    => 'ubah',
'protectthispage'   => 'Lindungi halaman ini',
'unprotect'         => 'Perlindungan',
'unprotectthispage' => 'Buka perlindungan halaman ini',
'newpage'           => 'Halaman baru',
'talkpage'          => 'Bicarakan halaman ini',
'talkpagelinktext'  => 'Bicara',
'specialpage'       => 'Halaman istimewa',
'personaltools'     => 'Peralatan pribadi',
'postcomment'       => 'Bagian baru',
'articlepage'       => 'Lihat halaman isi',
'talk'              => 'Pembicaraan',
'views'             => 'Tampilan',
'toolbox'           => 'Kotak peralatan',
'userpage'          => 'Lihat halaman pengguna',
'projectpage'       => 'Lihat halaman proyek',
'imagepage'         => 'Lihat halaman berkas',
'mediawikipage'     => 'Lihat halaman pesan sistem',
'templatepage'      => 'Lihat halaman templat',
'viewhelppage'      => 'Lihat halaman bantuan',
'categorypage'      => 'Lihat halaman kategori',
'viewtalkpage'      => 'Lihat halaman pembicaran',
'otherlanguages'    => 'Bahasa lain',
'redirectedfrom'    => '(Dialihkan dari $1)',
'redirectpagesub'   => 'Halaman pengalihan',
'lastmodifiedat'    => 'Halaman ini terakhir diubah pada $2, $1.',
'viewcount'         => 'Halaman ini telah diakses sebanyak {{PLURAL:$1|satu kali|$1 kali}}.<br />',
'protectedpage'     => 'Halaman yang dilindungi',
'jumpto'            => 'Langsung ke:',
'jumptonavigation'  => 'navigasi',
'jumptosearch'      => 'cari',
'view-pool-error'   => 'Maaf, peladen sedang sibuk pada saat ini.
Terlalu banyak pengguna berusaha melihat halaman ini.
Tunggu sebentar sebelum Anda mencoba lagi mengakses halaman ini.

$1',
'pool-timeout'      => 'Lewat waktu menunggu kunci',
'pool-queuefull'    => 'Kumpulan antrean penuh',
'pool-errorunknown' => 'Kesalahan yang tidak diketahui',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Tentang {{SITENAME}}',
'aboutpage'            => 'Project:Perihal',
'copyright'            => 'Seluruh teks tersedia sesuai dengan $1.',
'copyrightpage'        => '{{ns:project}}:Hak cipta',
'currentevents'        => 'Peristiwa terkini',
'currentevents-url'    => 'Project:Peristiwa terkini',
'disclaimers'          => 'Penyangkalan',
'disclaimerpage'       => 'Project:Penyangkalan umum',
'edithelp'             => 'Bantuan penyuntingan',
'edithelppage'         => 'Help:Penyuntingan',
'helppage'             => 'Help:Isi',
'mainpage'             => 'Halaman Utama',
'mainpage-description' => 'Halaman Utama',
'policy-url'           => 'Project:Kebijakan',
'portal'               => 'Portal komunitas',
'portal-url'           => 'Project:Portal komunitas',
'privacy'              => 'Kebijakan privasi',
'privacypage'          => 'Project:Kebijakan privasi',

'badaccess'        => 'Kesalahan hak akses',
'badaccess-group0' => 'Anda tidak diizinkan untuk melakukan tindakan yang Anda minta.',
'badaccess-groups' => 'Tindakan yang Anda minta dibatasi untuk pengguna dalam {{PLURAL:$2|kelompok|salah satu dari kelompok}}: $1.',

'versionrequired'     => 'Dibutuhkan MediaWiki versi $1',
'versionrequiredtext' => 'MediaWiki versi $1 dibutuhkan untuk menggunakan halaman ini. Lihat [[Special:Version|halaman versi]]',

'ok'                      => 'OK',
'retrievedfrom'           => 'Diperoleh dari "$1"',
'youhavenewmessages'      => 'Anda mempunyai $1 ($2).',
'newmessageslink'         => 'pesan baru',
'newmessagesdifflink'     => 'perubahan terakhir',
'youhavenewmessagesmulti' => 'Anda mendapat beberapa pesan baru pada $1',
'editsection'             => 'sunting',
'editold'                 => 'sunting',
'viewsourceold'           => 'lihat sumber',
'editlink'                => 'sunting',
'viewsourcelink'          => 'lihat sumber',
'editsectionhint'         => 'Sunting bagian: $1',
'toc'                     => 'Daftar isi',
'showtoc'                 => 'tampilkan',
'hidetoc'                 => 'sembunyikan',
'thisisdeleted'           => 'Lihat atau kembalikan $1?',
'viewdeleted'             => 'Lihat $1?',
'restorelink'             => '$1 {{PLURAL:$1|suntingan|suntingan}} yang telah dihapus',
'feedlinks'               => 'Umpan:',
'feed-invalid'            => 'Tipe permintaan umpan tidak tepat.',
'feed-unavailable'        => 'Umpan sindikasi tidak tersedia',
'site-rss-feed'           => 'Umpan RSS $1',
'site-atom-feed'          => 'Umpan Atom $1',
'page-rss-feed'           => 'Umpan RSS "$1"',
'page-atom-feed'          => 'Umpan Atom "$1"',
'red-link-title'          => '$1 (halaman belum tersedia)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Halaman',
'nstab-user'      => 'Pengguna',
'nstab-media'     => 'Media',
'nstab-special'   => 'Istimewa',
'nstab-project'   => 'Proyek',
'nstab-image'     => 'Berkas',
'nstab-mediawiki' => 'Pesan',
'nstab-template'  => 'Templat',
'nstab-help'      => 'Bantuan',
'nstab-category'  => 'Kategori',

# Main script and global functions
'nosuchaction'      => 'Tidak ada tindakan tersebut',
'nosuchactiontext'  => 'Tindakan yang diminta oleh URL tersebut tidak valid. Anda mungkin salah mengetikkan URL, atau mengikuti suatu pranala yang tak betul. Hal ini juga mungkin mengindikasikan suatu bug pada perangkat lunak yang digunakan oleh {{SITENAME}}.',
'nosuchspecialpage' => 'Tidak ada halaman istimewa tersebut',
'nospecialpagetext' => '<strong>Anda meminta halaman istimewa yang tidak sah.</strong>

Daftar halaman istimewa yang sah dapat dilihat di [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Kesalahan',
'databaseerror'        => 'Kesalahan basis data',
'dberrortext'          => 'Ada kesalahan sintaks pada permintaan basis data.
Kesalahan ini mungkin menandakan adanya sebuah \'\'bug\'\' dalam perangkat lunak.
Permintaan basis data yang terakhir adalah:
<blockquote><tt>$1</tt></blockquote>
dari dalam fungsi "<tt>$2</tt>".
Basis data menghasilkan kesalahan "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Ada kesalahan sintaks pada permintaan basis data.
Permintaan basis data yang terakhir adalah:
"$1"
dari dalam fungsi "$2".
Basis data menghasilkan kesalahan "$3: $4".',
'laggedslavemode'      => 'Peringatan: Halaman mungkin tidak berisi perubahan terbaru.',
'readonly'             => 'Basis data dikunci',
'enterlockreason'      => 'Masukkan alasan penguncian, termasuk perkiraan kapan kunci akan dibuka',
'readonlytext'         => 'Basis data sedang dikunci terhadap masukan baru. Pengurus yang melakukan penguncian memberikan penjelasan sebagai berikut: <p>$1',
'missing-article'      => 'Basis data tidak dapat menemukan teks dari halaman yang seharusnya ada, yaitu "$1" $2.

Hal ini biasanya disebabkan oleh pranala usang ke revisi terdahulu halaman yang telah dihapuskan.

Jika bukan ini penyebabnya, Anda mungkin telah menemukan sebuah bug dalam perangkat lunak.
Silakan laporkan hal ini kepada salah seorang [[Special:ListUsers/sysop|Pengurus]], dengan menyebutkan alamat URL yang dituju.',
'missingarticle-rev'   => '(revisi#: $1)',
'missingarticle-diff'  => '(Beda: $1, $2)',
'readonly_lag'         => 'Basis data telah dikunci otomatis selagi basis data sekunder melakukan sinkronisasi dengan basis data utama',
'internalerror'        => 'Kesalahan internal',
'internalerror_info'   => 'Kesalahan internal: $1',
'fileappenderrorread'  => 'Tidak dapat membaca "$1" saat penambahan.',
'fileappenderror'      => 'Tidak dapat memasukkan "$1" ke "$2".',
'filecopyerror'        => 'Tidak dapat menyalin berkas "$1" ke "$2".',
'filerenameerror'      => 'Tidak dapat mengubah nama berkas "$1" menjadi "$2".',
'filedeleteerror'      => 'Tidak dapat menghapus berkas "$1".',
'directorycreateerror' => 'Tidak dapat membuat direktori "$1".',
'filenotfound'         => 'Tidak dapat menemukan berkas "$1".',
'fileexistserror'      => 'Tidak dapat menulis berkas "$1": berkas sudah ada',
'unexpected'           => 'Nilai di luar jangkauan: "$1"="$2".',
'formerror'            => 'Kesalahan: Tidak dapat mengirimkan formulir',
'badarticleerror'      => 'Tindakan ini tidak dapat dilaksanakan di halaman ini.',
'cannotdelete'         => 'Halaman atau berkas "$1" tidak dapat dihapus.
Mungkin telah dihapus oleh orang lain.',
'badtitle'             => 'Judul tidak sah',
'badtitletext'         => 'Judul halaman yang diminta tidak sah, kosong, atau judul antarbahasa atau antarwiki yang salah sambung.',
'perfcached'           => 'Data berikut ini diambil dari <em>cache</em> dan mungkin bukan data mutakhir:',
'perfcachedts'         => 'Data berikut ini diambil dari <em>cache</em>, dan terakhir diperbarui pada $1.',
'querypage-no-updates' => 'Pemutakhiran dari halaman ini sedang dimatikan. Data yang ada di sini saat ini tidak akan dimuat ulang.',
'wrong_wfQuery_params' => 'Parameter salah ke wfQuery()<br />Fungsi: $1<br />Permintaan: $2',
'viewsource'           => 'Lihat sumber',
'viewsourcefor'        => 'untuk $1',
'actionthrottled'      => 'Tindakan dibatasi',
'actionthrottledtext'  => 'Anda dibatasi untuk melakukan tindakan ini terlalu banyak dalam waktu pendek. Silakan mencoba lagi setelah beberapa menit.',
'protectedpagetext'    => 'Halaman ini telah dikunci untuk menghindari penyuntingan.',
'viewsourcetext'       => 'Anda dapat melihat atau menyalin sumber halaman ini:',
'protectedinterface'   => 'Halaman ini berisi teks antarmuka untuk digunakan oleh perangkat lunak dan telah dikunci untuk menghindari kesalahan.',
'editinginterface'     => "'''Peringatan:''' Anda menyunting suatu halaman yang digunakan untuk menyediakan teks antarmuka untuk perangkat lunak situs ini. Perubahan teks ini akan mempengaruhi tampilan pada antarmuka pengguna untuk pengguna lain.
Untuk terjemahan, harap gunakan [http://translatewiki.net/wiki/Main_Page?setlang=id translatewiki.net], proyek pelokalan MediaWiki.",
'sqlhidden'            => '(Permintaan SQL disembunyikan)',
'cascadeprotected'     => 'Halaman ini telah dilindungi dari penyuntingan karena disertakan di {{PLURAL:$1|halaman|halaman-halaman}} berikut yang telah dilindungi dengan opsi "runtun":
$2',
'namespaceprotected'   => "Anda tak memiliki hak akses untuk menyunting halaman di ruang nama '''$1'''.",
'customcssjsprotected' => 'Anda tak memiliki hak menyunting halaman ini karena mengandung pengaturan pribadi pengguna lain.',
'ns-specialprotected'  => 'Halaman pada ruang nama {{ns:special}} tidak dapat disunting.',
'titleprotected'       => "Judul ini dilindungi dari pembuatan oleh [[User:$1|$1]].
Alasan yang diberikan adalah ''$2''.",

# Virus scanner
'virus-badscanner'     => "Kesalahan konfigurasi: pemindai virus tidak dikenal: ''$1''",
'virus-scanfailed'     => 'Pemindaian gagal (kode $1)',
'virus-unknownscanner' => 'Antivirus tidak dikenal:',

# Login and logout pages
'logouttext'                 => "'''Anda telah keluar log dari sistem.'''

Anda dapat terus menggunakan {{SITENAME}} secara anonim, atau Anda dapat [[Special:UserLogin|masuk log lagi]] sebagai pengguna yang sama atau pengguna yang lain.
Perhatikan bahwa beberapa halaman mungkin masih terus menunjukkan bahwa Anda masih masuk log sampai Anda membersihkan singgahan penjelajah web Anda",
'welcomecreation'            => '== Selamat datang, $1! ==

Akun Anda telah dibuat. Jangan lupa mengatur konfigurasi [[Special:Preferences|preferensi {{SITENAME}}]] Anda.',
'yourname'                   => 'Nama pengguna:',
'yourpassword'               => 'Kata sandi:',
'yourpasswordagain'          => 'Ulangi kata sandi:',
'remembermypassword'         => 'Ingat kata sandi saya di komputer ini (selama $1 {{PLURAL:$1|hari|hari}})',
'securelogin-stick-https'    => 'Tetap terhubung ke HTTPS setelah masuk',
'yourdomainname'             => 'Domain Anda:',
'externaldberror'            => 'Telah terjadi kesalahan otentikasi basis data eksternal atau Anda tidak diizinkan melakukan kemaskini terhadap akun eksternal Anda.',
'login'                      => 'Masuk log',
'nav-login-createaccount'    => 'Masuk log / buat akun',
'loginprompt'                => "Anda harus mengaktifkan ''cookies'' untuk dapat masuk log ke {{SITENAME}}.",
'userlogin'                  => 'Masuk log / buat akun',
'userloginnocreate'          => 'Masuk log',
'logout'                     => 'Keluar log',
'userlogout'                 => 'Keluar log',
'notloggedin'                => 'Belum masuk log',
'nologin'                    => "Belum mempunyai akun? '''$1'''.",
'nologinlink'                => 'Daftarkan akun baru',
'createaccount'              => 'Buat akun baru',
'gotaccount'                 => "Sudah terdaftar sebagai pengguna? '''$1'''.",
'gotaccountlink'             => 'Masuk log',
'createaccountmail'          => 'melalui surel',
'createaccountreason'        => 'Alasan:',
'badretype'                  => 'Kata sandi yang Anda masukkan salah.',
'userexists'                 => 'Nama pengguna yang Anda pilih sudah dipakai oleh orang lain.
Silakan pilih nama yang lain.',
'loginerror'                 => 'Kesalahan masuk log',
'createaccounterror'         => 'Tidak dapat membuat akun: $1',
'nocookiesnew'               => "Akun pengguna telah dibuat, tetapi Anda belum masuk log. {{SITENAME}} menggunakan ''cookies'' untuk log pengguna. ''Cookies'' pada penjelajah web Anda dimatikan. Silakan aktifkan dan masuk log kembali dengan nama pengguna dan kata sandi Anda.",
'nocookieslogin'             => "{{SITENAME}} menggunakan ''cookies'' untuk log penggunanya. ''Cookies'' pada penjelajah web Anda dimatikan. Silakan aktifkan dan coba lagi.",
'noname'                     => 'Nama pengguna yang Anda masukkan tidak sah.',
'loginsuccesstitle'          => 'Berhasil masuk log',
'loginsuccess'               => "'''Anda sekarang masuk log di {{SITENAME}} sebagai \"\$1\".'''",
'nosuchuser'                 => 'Tidak ada pengguna dengan nama "$1".
Nama pengguna membedakan kapitalisasi.
Periksa kembali ejaan Anda, atau [[Special:UserLogin/signup|buat akun baru]].',
'nosuchusershort'            => 'Tidak ada pengguna dengan nama "<nowiki>$1</nowiki>".
Silakan periksa kembali ejaan Anda.',
'nouserspecified'            => 'Anda harus memasukkan nama pengguna.',
'login-userblocked'          => 'Pengguna ini diblokir. Tidak diizinkan/diperbolehkan untuk masuk log.',
'wrongpassword'              => 'Kata sandi yang Anda masukkan salah. Silakan coba lagi.',
'wrongpasswordempty'         => 'Anda tidak memasukkan kata sandi. Silakan coba lagi.',
'passwordtooshort'           => 'Kata sandi paling tidak harus terdiri dari {{PLURAL:$1|1 karakter|$1 karakter}}.',
'password-name-match'        => 'Kata sandi Anda harus berbeda dari nama pengguna Anda.',
'password-too-weak'          => 'Sandi yang diberikan terlalu lemah dan tidak dapat digunakan.',
'mailmypassword'             => 'Kirim kata sandi baru',
'passwordremindertitle'      => 'Peringatan kata sandi dari {{SITENAME}}',
'passwordremindertext'       => 'Seseorang (mungkin Anda, dari alamat IP $1) meminta kata sandi baru untuk {{SITENAME}} ($4). Kata sandi sementara untuk pengguna "$2" telah dibuatkan dan diset menjadi "$3". Jika memang Anda yang mengajukan permintaan ini, Anda perlu masuk log dan memilih kata sandi baru sekarang. Kata sandi sementara Anda akan kedaluwarsa dalam waktu {{PLURAL:$5|satu hari|$5 hari}}.

Jika orang lain yang melakukan permintaan ini, atau jika Anda telah mengingat kata sandi Anda dan akan tetap menggunakan kata sandi tersebut, silakan abaikan pesan ini dan tetap gunakan kata sandi lama Anda.',
'noemail'                    => 'Tidak ada alamat surel yang tercatat untuk pengguna "$1".',
'noemailcreate'              => 'Anda perlu menyediakan alamat surel yang sah',
'passwordsent'               => 'Kata sandi baru telah dikirimkan ke alamat surel yang didaftarkan untuk "$1".
Silakan masuk log kembali setelah menerima surel tersebut.',
'blocked-mailpassword'       => 'Alamat IP Anda diblokir dari penyuntingan dan karenanya tidak diizinkan menggunakan fungsi pengingat kata sandi untuk mencegah penyalahgunaan.',
'eauthentsent'               => 'Sebuah surel untuk konfirmasi telah dikirim ke alamat surel.
Anda harus mengikuti instruksi di dalam surel tersebut untuk melakukan konfirmasi bahwa alamat tersebut adalah benar kepunyaan Anda. {{SITENAME}} tidak akan mengaktifkan fitur surel jika langkah ini belum dilakukan.',
'throttled-mailpassword'     => 'Suatu pengingat kata sandi telah dikirimkan dalam {{PLURAL:$1|jam|$1 jam}} terakhir.
Untuk menghindari penyalahgunaan, hanya satu kata sandi yang akan dikirimkan setiap {{PLURAL:$1|jam|$1 jam}}.',
'mailerror'                  => 'Kesalahan dalam mengirimkan surel: $1',
'acct_creation_throttle_hit' => 'Pengunjung wiki ini dengan alamat IP yang sama dengan Anda telah membuat {{PLURAL:$1|1 akun|$1 akun}} dalam satu hari terakhir, hingga jumlah maksimum yang diijinkan.
Karenanya, pengunjung dengan alamat IP ini tidak dapat lagi membuat akun lain untuk sementara.',
'emailauthenticated'         => 'Alamat surel Anda telah dikonfirmasi pada $3, $2.',
'emailnotauthenticated'      => 'Alamat surel Anda belum dikonfirmasi. Sebelum dikonfirmasi Anda tidak bisa menggunakan fitur surel.',
'noemailprefs'               => 'Anda harus memasukkan alamat surel di preferensi Anda untuk dapat menggunakan fitur-fitur ini.',
'emailconfirmlink'           => 'Konfirmasikan alamat surel Anda',
'invalidemailaddress'        => 'Alamat surel ini tidak dapat diterima karena formatnya tidak sesuai.
Harap masukkan alamat surel dalam format yang benar atau kosongkan isian tersebut.',
'accountcreated'             => 'Akun dibuat',
'accountcreatedtext'         => 'Akun pengguna untuk $1 telah dibuat.',
'createaccount-title'        => 'Pembuatan akun untuk {{SITENAME}}',
'createaccount-text'         => 'Seseorang telah membuat sebuah akun untuk alamat surel Anda di {{SITENAME}} ($4) dengan nama "$2" dan kata sandi "$3". Anda dianjurkan untuk masuk log dan mengganti kata sandi Anda sekarang.

Anda dapat mengabaikan pesan ini jika akun ini dibuat karena suatu kesalahan.',
'usernamehasherror'          => 'Nama pengguna tidak bisa mengandung tanda pagar',
'login-throttled'            => 'Anda telah berkali-kali mencoba masuk log.
Silakan menunggu sebelum mencoba lagi.',
'loginlanguagelabel'         => 'Bahasa: $1',
'suspicious-userlogout'      => 'Permintaan Anda untuk keluar log ditolak karena tampaknya dikirim oleh penjelajah yang rusak atau proksi penyinggah.',

# JavaScript password checks
'password-strength'            => 'Kekuatan sandi: $1',
'password-strength-bad'        => 'BURUK',
'password-strength-mediocre'   => 'sedang',
'password-strength-acceptable' => 'cukup',
'password-strength-good'       => 'baik',
'password-retype'              => 'Ulangi kata sandi di sini',
'password-retype-mismatch'     => 'Sandi tidak cocok',

# Password reset dialog
'resetpass'                 => 'Ganti kata sandi',
'resetpass_announce'        => 'Anda telah masuk log dengan kode sementara yang dikirim melalui surel. Untuk melanjutkan, Anda harus memasukkan kata sandi baru di sini:',
'resetpass_text'            => '<!-- Tambahkan teks di sini -->',
'resetpass_header'          => 'Ganti kata sandi akun',
'oldpassword'               => 'Kata sandi lama:',
'newpassword'               => 'Kata sandi baru:',
'retypenew'                 => 'Ketik ulang kata sandi baru:',
'resetpass_submit'          => 'Atur kata sandi dan masuk log',
'resetpass_success'         => 'Kata sandi Anda telah berhasil diubah! Sekarang memproses masuk log Anda...',
'resetpass_forbidden'       => 'Kata sandi tidak dapat diubah',
'resetpass-no-info'         => 'Anda harus masuk log untuk mengakses halaman ini secara langsung.',
'resetpass-submit-loggedin' => 'Ganti kata sandi',
'resetpass-submit-cancel'   => 'Batalkan',
'resetpass-wrong-oldpass'   => 'Kata sandi tidak sah.
Anda mungkin telah berhasil mengganti kata sandi Anda atau telah meminta kata sandi sementara yang baru.',
'resetpass-temp-password'   => 'Kata sandi sementara:',

# Edit page toolbar
'bold_sample'     => 'Teks ini akan dicetak tebal',
'bold_tip'        => 'Teks tebal',
'italic_sample'   => 'Teks ini akan dicetak miring',
'italic_tip'      => 'Teks miring',
'link_sample'     => 'Judul pranala',
'link_tip'        => 'Pranala internal',
'extlink_sample'  => 'http://www.example.com judul pranala',
'extlink_tip'     => 'Pranala luar (jangan lupa awalan http:// )',
'headline_sample' => 'Teks judul',
'headline_tip'    => 'Subbagian tingkat 1',
'math_sample'     => 'Masukkan rumus di sini',
'math_tip'        => 'Rumus matematika (LaTeX)',
'nowiki_sample'   => 'Masukkan teks yang tidak akan diformat di sini',
'nowiki_tip'      => 'Abaikan pemformatan wiki',
'image_sample'    => 'Contoh.jpg',
'image_tip'       => 'Cantumkan berkas',
'media_sample'    => 'Contoh.ogg',
'media_tip'       => 'Pranala berkas media',
'sig_tip'         => 'Tanda tangan Anda dengan tanda waktu',
'hr_tip'          => 'Garis horisontal',

# Edit pages
'summary'                          => 'Ringkasan:',
'subject'                          => 'Subjek/judul:',
'minoredit'                        => 'Ini adalah suntingan kecil.',
'watchthis'                        => 'Pantau halaman ini',
'savearticle'                      => 'Simpan halaman',
'preview'                          => 'Pratayang',
'showpreview'                      => 'Lihat pratayang',
'showlivepreview'                  => 'Pratayang langsung',
'showdiff'                         => 'Perlihatkan perubahan',
'anoneditwarning'                  => 'Anda tidak terdaftar masuk. Alamat IP Anda akan tercatat dalam sejarah (versi terdahulu) halaman ini.',
'anonpreviewwarning'               => "''Anda belum masuk log. Menyimpan halaman akan menyebabkan alamat IP Anda tercatat pada riwayat suntingan laman ini.''",
'missingsummary'                   => "'''Peringatan:''' Anda tidak memasukkan ringkasan penyuntingan. Jika Anda kembali menekan tombol Simpan, suntingan Anda akan disimpan tanpa ringkasan penyuntingan.",
'missingcommenttext'               => 'Harap masukkan komentar di bawah ini.',
'missingcommentheader'             => "''Peringatan:''' Anda belum memberikan subjek atau judul untuk komentar Anda. Jika Anda kembali menekan \"{{int:savearticle}}\", suntingan Anda akan disimpan tanpa komentar tersebut.",
'summary-preview'                  => 'Pratayang ringkasan:',
'subject-preview'                  => 'Pratayang subyek/tajuk:',
'blockedtitle'                     => 'Pengguna diblokir',
'blockedtext'                      => "'''Nama pengguna atau alamat IP Anda telah diblokir.'''

Blokir dilakukan oleh $1.
Alasan yang diberikan adalah ''$2''.

* Diblokir sejak: $8
* Blokir kedaluwarsa pada: $6
* Sasaran pemblokiran: $7

Anda dapat menghubungi $1 atau [[{{MediaWiki:Grouppage-sysop}}|pengurus lainnya]] untuk membicarakan hal ini.

Anda tidak dapat menggunakan fitur 'Kirim surel ke pengguna ini' kecuali Anda telah memasukkan alamat surel yang sah di [[Special:Preferences|preferensi akun]] dan Anda tidak diblokir untuk menggunakannya.

Alamat IP Anda adalah $3, dan ID pemblokiran adalah $5.
Tolong sertakan salah satu atau kedua informasi ini pada setiap pertanyaan yang Anda buat.",
'autoblockedtext'                  => 'Alamat IP Anda telah terblokir secara otomatis karena digunakan oleh pengguna lain, yang diblokir oleh $1. Pemblokiran dilakukan atas alasan:

:\'\'$2\'\'

* Diblokir sejak: $8
* Blokir kedaluwarsa pada: $6
* Sasaran pemblokiran: $7

Anda dapat menghubungi $1 atau [[{{MediaWiki:Grouppage-sysop}}|pengurus lainnya]] untuk membicarakan hal ini.

Anda tidak dapat menggunakan fitur "kirim surel ke pengguna ini" kecuali Anda telah memasukkan alamat surel yang sah di [[Special:Preferences|preferensi akun]] Anda dan Anda tidak diblokir untuk menggunakannya.

Alamat IP Anda saat ini adalah $3, dan ID pemblokiran adalah #$5.
Tolong sertakan informasi-informasi ini dalam setiap pertanyaan Anda.',
'blockednoreason'                  => 'tidak ada alasan yang diberikan',
'blockedoriginalsource'            => "Isi sumber '''$1''' ditunjukkan berikut ini:",
'blockededitsource'                => "Teks '''suntingan Anda''' terhadap '''$1''' ditunjukkan berikut ini:",
'whitelistedittitle'               => 'Perlu masuk log untuk menyunting',
'whitelistedittext'                => 'Anda harus $1 untuk dapat menyunting halaman.',
'confirmedittext'                  => 'Anda harus mengkonfirmasikan dulu alamat surel Anda sebelum menyunting halaman.
Harap masukkan dan validasikan alamat surel Anda melalui [[Special:Preferences|halaman preferensi pengguna]] Anda.',
'nosuchsectiontitle'               => 'Bagian tidak ditemukan',
'nosuchsectiontext'                => 'Anda mencoba menyunting suatu subbagian yang tidak ada.
Subbagian ini mungkin dipindahkan atau dihapus ketika Anda membukanya.',
'loginreqtitle'                    => 'Harus masuk log',
'loginreqlink'                     => 'masuk log',
'loginreqpagetext'                 => 'Anda harus $1 untuk dapat melihat halaman lainnya.',
'accmailtitle'                     => 'Kata sandi telah terkirim.',
'accmailtext'                      => "Sebuah kata sandi acak untuk [[User talk:$1|$1]] telah dibuat dan dikirimkan ke $2.

Kata sandi untuk akun baru ini dapat diubah di halaman ''[[Special:ChangePassword|pengubahan kata sandi]]'' setelah masuk log.",
'newarticle'                       => '(Baru)',
'newarticletext'                   => "Anda mengikuti pranala ke halaman yang belum tersedia. Untuk membuat halaman tersebut, ketiklah isi halaman di kotak di bawah ini (lihat [[{{MediaWiki:Helppage}}|halaman bantuan]] untuk informasi lebih lanjut). Jika Anda tanpa sengaja sampai ke halaman ini, klik tombol '''back''' di penjelajah web anda.",
'anontalkpagetext'                 => "----''Ini adalah halaman pembicaraan seorang pengguna anonim yang belum membuat akun atau tidak menggunakannya.
Dengan demikian, kami terpaksa harus memakai alamat IP yang bersangkutan untuk mengidentifikasikannya.
Alamat IP seperti ini mungkin dipakai bersama oleh beberapa pengguna yang berbeda.
Jika Anda adalah seorang pengguna anonim dan merasa mendapatkan komentar-komentar yang tidak relevan yang ditujukan langsung kepada Anda, silakan [[Special:UserLogin/signup|membuat akun]] atau [[Special:UserLogin|masuk log]] untuk menghindari kerancuan dengan pengguna anonim lainnya di lain waktu.''",
'noarticletext'                    => 'Saat ini tidak ada teks di halaman ini.
Anda dapat [[Special:Search/{{PAGENAME}}|melakukan pencarian untuk judul halaman ini]] di halaman-halaman lain, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} mencari log terkait], atau [{{fullurl:{{FULLPAGENAME}}|action=edit}} menyunting halaman ini]</span>.',
'noarticletext-nopermission'       => 'Saat ini tidak ada teks di halaman ini.
Anda dapat [[Special:Search/{{PAGENAME}}|melakukan pencarian untuk judul halaman ini]] di halaman-halaman lain, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} mencari log terkait], atau [{{fullurl:{{FULLPAGENAME}}|action=edit}} menyunting halaman ini]</span>.',
'userpage-userdoesnotexist'        => 'Akun pengguna "$1" tidak terdaftar.',
'userpage-userdoesnotexist-view'   => 'Pengguna "$1" tidak terdaftar.',
'blocked-notice-logextract'        => 'Pengguna ini sedang diblokir.
Entri log pemblokiran terakhir tersedia di bawah ini sebagai rujukan.',
'clearyourcache'                   => "'''Catatan:''' Setelah menyimpan preferensi, Anda perlu membersihkan <em>cache</em> penjelajah web Anda untuk melihat perubahan. '''Mozilla / Firefox / Safari:''' tekan ''Ctrl-Shift-R'' (''Cmd-Shift-R'' pada Apple Mac); '''IE:''' tekan ''Ctrl-F5''; '''Konqueror:''': tekan ''F5''; '''Opera''' bersihkan <em>cache</em> melalui menu ''Tools→Preferences''.",
'usercssyoucanpreview'             => "'''Tips:''' Gunakan tombol \"{{int:showpreview}}\" untuk menguji CSS baru Anda sebelum menyimpannya.",
'userjsyoucanpreview'              => "'''Tips:''' Gunakan tombol \"{{int:showpreview}}\" untuk menguji JS baru Anda sebelum menyimpannya.",
'usercsspreview'                   => "'''Ingatlah bahwa Anda sedang menampilkan pratayang dari CSS Anda.
Pratayang ini belum disimpan!'''",
'userjspreview'                    => "'''Ingatlah bahwa yang Anda lihat hanyalah pratayang JavaScript Anda, dan bahwa pratayang tersebut belum disimpan!'''",
'userinvalidcssjstitle'            => "'''Peringatan:''' Kulit \"\$1\" tidak ditemukan. Harap diingat bahwa halaman .css dan .js menggunakan huruf kecil, contoh {{ns:user}}:Foo/monobook.css dan bukannya {{ns:user}}:Foo/Monobook.css.",
'updated'                          => '(Diperbarui)',
'note'                             => "'''Catatan:'''",
'previewnote'                      => "'''Ingatlah bahwa ini hanyalah pratayang yang belum disimpan!'''",
'previewconflict'                  => 'Pratayang ini mencerminkan teks pada bagian atas kotak suntingan teks sebagaimana akan terlihat bila Anda menyimpannya.',
'session_fail_preview'             => "'''Maaf, kami tidak dapat mengolah suntingan Anda akibat terhapusnya data sesi.
Silakan coba sekali lagi.
Jika masih tidak berhasil, cobalah [[Special:UserLogout|keluar lo]]g dan masuk log kembali.'''",
'session_fail_preview_html'        => "'''Kami tidak dapat memproses suntingan Anda karena hilangnya data sesi.'''

''Karena {{SITENAME}} mengizinkan penggunaan HTML mentah, pratayang telah disembunyikan sebagai pencegahan terhadap serangan JavaScript.''

'''Jika ini merupakan upaya suntingan yang sahih, silakan coba lagi.
Jika masih tetap tidak berhasil, cobalah [[Special:UserLogout|keluar log]] dan masuk kembali.'''",
'token_suffix_mismatch'            => "'''Suntingan Anda ditolak karena aplikasi klien Anda mengubah karakter tanda baca pada suntingan.'''
Suntingan tersebut ditolak untuk mencegah kesalahan pada teks halaman.
Hal ini kadang terjadi jika Anda menggunakan layanan proxy anonim berbasis web yang bermasalah.",
'editing'                          => 'Menyunting $1',
'editingsection'                   => 'Menyunting $1 (bagian)',
'editingcomment'                   => 'Menyunting $1 (bagian baru)',
'editconflict'                     => 'Konflik penyuntingan: $1',
'explainconflict'                  => "Orang lain telah menyunting halaman ini sejak Anda mulai menyuntingnya.
Bagian atas teks ini mengandung teks halaman saat ini.
Perubahan yang Anda lakukan ditunjukkan pada bagian bawah teks.
Anda hanya perlu menggabungkan perubahan Anda dengan teks yang telah ada.
'''Hanya''' teks pada bagian atas halamanlah yang akan disimpan apabila Anda menekan \"{{int:savearticle}}\".",
'yourtext'                         => 'Teks Anda',
'storedversion'                    => 'Versi tersimpan',
'nonunicodebrowser'                => "'''Peringatan: Penjelajah web Anda tidak mendukung unicode.'''
Telah terdapat sebuah solusi agar Anda dapat menyunting halaman dengan aman: karakter non-ASCII akan muncul dalam kotak edit sebagai kode heksadesimal.",
'editingold'                       => "'''Peringatan:
Anda menyunting revisi lama suatu halaman.
Jika Anda menyimpannya, perubahan-perubahan yang dibuat sejak revisi ini akan hilang.'''",
'yourdiff'                         => 'Perbedaan',
'copyrightwarning'                 => "Perhatikan bahwa semua kontribusi terhadap {{SITENAME}} dianggap dilisensikan sesuai dengan $2 (lihat $1 untuk informasi lebih lanjut). Jika Anda tidak ingin tulisan Anda disunting dan disebarkan ke halaman web yang lain, jangan kirimkan ke sini.<br />Anda juga berjanji bahwa ini adalah hasil karya Anda sendiri, atau disalin dari sumber milik umum atau sumber bebas yang lain. '''JANGAN KIRIMKAN KARYA YANG DILINDUNGI HAK CIPTA TANPA IZIN!'''",
'copyrightwarning2'                => "Perhatikan bahwa semua kontribusi terhadap {{SITENAME}} dapat disunting, diubah, atau dihapus oleh penyumbang lainnya. Jika Anda tidak ingin tulisan Anda disunting orang lain, jangan kirimkan ke sini.<br />Anda juga berjanji bahwa ini adalah hasil karya Anda sendiri, atau disalin dari sumber milik umum atau sumber bebas yang lain (lihat $1 untuk informasi lebih lanjut). '''JANGAN KIRIMKAN KARYA YANG DILINDUNGI HAK CIPTA TANPA IZIN!'''",
'longpageerror'                    => "'''KESALAHAN: Teks yang Anda kirimkan sebesar $1 kilobita, yang berarti lebih besar dari jumlah maksimum $2 kilobita. Teks tidak dapat disimpan.'''",
'readonlywarning'                  => "'''PERINGATAN: Basis data sedang dikunci karena pemeliharaan, sehingga saat ini Anda tidak dapat menyimpan hasil suntingan Anda.
Anda mungkin perlu menyalin teks suntingan Anda ini dan menyimpannya ke sebuah berkas teks dan memuatkannya lagi setelah pemeliharaan selesai.'''

Pengurus yang mengunci basis data memberikan penjelasan berikut: $1",
'protectedpagewarning'             => "'''Peringatan: Halaman ini sedang dilindungi sehingga hanya pengguna dengan hak akses pengurus yang dapat menyuntingnya.'''
Entri catatan terakhir disediakan di bawah untuk referensi:",
'semiprotectedpagewarning'         => "'''Catatan:''' Halaman ini sedang dilindungi, sehingga hanya pengguna terdaftar yang bisa menyuntingnya.
Entri catatan terakhir disediakan di bawah untuk referensi:",
'cascadeprotectedwarning'          => "'''PERINGATAN:''' Halaman ini sedang dilindungi sehingga hanya pengguna dengan hak akses pengurus saja yang dapat menyuntingnya karena disertakan dalam {{PLURAL:$1|halaman|halaman-halaman}} berikut yang telah dilindungi dengan opsi 'perlindungan runtun':",
'titleprotectedwarning'            => "'''Peringatan: Halaman ini telah dilindungi sehingga diperlukan [[Special:ListGroupRights|hak khusus]] untuk membuatnya.'''
Entri catatan terakhir disediakan di bawah untuk referensi:",
'templatesused'                    => '{{PLURAL:$1|Templat|Templat}} yang digunakan di halaman ini:',
'templatesusedpreview'             => '{{PLURAL:$1|Templat|Templat}} yang digunakan di pratayang ini:',
'templatesusedsection'             => '{{PLURAL:$1|Templat|Templat}} yang digunakan di bagian ini:',
'template-protected'               => '(dilindungi)',
'template-semiprotected'           => '(semi-perlindungan)',
'hiddencategories'                 => 'Halaman ini adalah anggota dari {{PLURAL:$1|1 kategori tersembunyi|$1 kategori tersembunyi}}:',
'edittools'                        => '<!-- Teks di sini akan dimunculkan di bawah isian suntingan dan pemuatan.-->',
'nocreatetitle'                    => 'Pembuatan halaman baru dibatasi',
'nocreatetext'                     => '{{SITENAME}} telah membatasi pembuatan halaman-halaman baru.
Anda dapat kembali dan menyunting halaman yang telah ada, atau silakan [[Special:UserLogin|masuk log atau membuat akun]].',
'nocreate-loggedin'                => 'Anda tak memiliki hak akses untuk membuat halaman baru.',
'sectioneditnotsupported-title'    => 'Penyuntingan bagian tidak didukung',
'sectioneditnotsupported-text'     => 'Penyuntingan bagian tidak didukung di halaman sunting ini.',
'permissionserrors'                => 'Kesalahan Hak Akses',
'permissionserrorstext'            => 'Anda tak memiliki hak untuk melakukan hal itu karena {{PLURAL:$1|alasan|alasan-alasan}} berikut:',
'permissionserrorstext-withaction' => 'Anda tidak memiliki hak akses untuk $2, karena {{PLURAL:$1|alasan|alasan}} berikut:',
'recreate-moveddeleted-warn'       => "'''Peringatan: Anda membuat ulang suatu halaman yang sudah pernah dihapus.'''

Harap pertimbangkan apakah layak untuk melanjutkan suntingan Anda.
Berikut adalah log penghapusan dan pemindahan dari halaman ini:",
'moveddeleted-notice'              => 'Halaman ini telah dihapus.
Sebagai referensi, berikut adalah log penghapusan dan pemindahan halaman ini.',
'log-fulllog'                      => 'Lihat seluruh log',
'edit-hook-aborted'                => 'Suntingan dibatalkan oleh kait parser
tanpa ada keterangan.',
'edit-gone-missing'                => 'Tidak dapat memperbaharui halaman.
Halaman kemungkinan telah dihapus.',
'edit-conflict'                    => 'Konflik penyuntingan.',
'edit-no-change'                   => 'Suntingan diabaikan, karena Anda tidak melakukan perubahan atas teks.',
'edit-already-exists'              => 'Tidak dapat membuat halaman baru
karena telah ada.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Peringatan: Halaman ini mengandung terlalu banyak panggilan fungsi parser.

Saat ini terdapat {{PLURAL:$1|$1 panggilan|$1 panggilan}}, seharusnya kurang dari $2 {{PLURAL:$2|panggilan|panggilan}}.',
'expensive-parserfunction-category'       => 'Halaman dengan terlalu banyak panggilan fungsi parser',
'post-expand-template-inclusion-warning'  => 'Peringatan: Ukuran templat yang digunakan terlalu besar.
Beberapa templat akan diabaikan.',
'post-expand-template-inclusion-category' => 'Halaman dengan ukuran templat yang melebihi batas',
'post-expand-template-argument-warning'   => 'Peringatan: Halaman ini mengandung setidaknya satu argumen templat dengan ukuran ekspansi yang terlalu besar. Argumen-argumen tersebut telah diabaikan.',
'post-expand-template-argument-category'  => 'Halaman dengan argumen templat yang diabaikan',
'parser-template-loop-warning'            => 'Hubungan berulang templat terdeteksi: [[$1]]',
'parser-template-recursion-depth-warning' => 'Limit kedalaman hubungan berulang templat terlampaui ($1)',
'language-converter-depth-warning'        => 'Batas kedalaman pengonversi bahasa terlampaui ($1)',

# "Undo" feature
'undo-success' => 'Suntingan ini dapat dibatalkan. Tolong cek perbandingan di bawah untuk meyakinkan bahwa benar itu yang Anda ingin lakukan, lalu simpan perubahan tersebut untuk menyelesaikan pembatalan suntingan.',
'undo-failure' => 'Suntingan ini tidak dapat dibatalkan karena konflik penyuntingan antara.',
'undo-norev'   => 'Suntingan ini tidak dapat dibatalkan karena halaman tidak ditemukan atau telah dihapuskan.',
'undo-summary' => '←Membatalkan revisi $1 oleh [[Special:Contributions/$2|$2]] ([[User talk:$2|Bicara]])',

# Account creation failure
'cantcreateaccounttitle' => 'Akun tak dapat dibuat',
'cantcreateaccount-text' => "Pembuatan akun dari alamat IP ini (<strong>$1</strong>) telah diblokir oleh [[User:$3|$3]].

Alasan yang diberikan oleh $3 adalah ''$2''",

# History pages
'viewpagelogs'           => 'Lihat log halaman ini',
'nohistory'              => 'Tidak ada sejarah penyuntingan untuk halaman ini',
'currentrev'             => 'Revisi terkini',
'currentrev-asof'        => 'Revisi terkini pada $1',
'revisionasof'           => 'Revisi per $1',
'revision-info'          => 'Revisi per $1; $2',
'previousrevision'       => '←Revisi sebelumnya',
'nextrevision'           => 'Revisi selanjutnya→',
'currentrevisionlink'    => 'Revisi terkini',
'cur'                    => 'skr',
'next'                   => 'selanjutnya',
'last'                   => 'sebelum',
'page_first'             => 'pertama',
'page_last'              => 'terakhir',
'histlegend'             => "Pilih dua tombol radio lalu tekan tombol ''bandingkan'' untuk membandingkan versi. Klik suatu tanggal untuk melihat versi halaman pada tanggal tersebut.<br />(skr) = perbedaan dengan versi sekarang, (akhir) = perbedaan dengan versi sebelumnya, '''k''' = suntingan kecil, '''b''' = suntingan bot, → = suntingan bagian, ← = ringkasan otomatis",
'history-fieldset-title' => 'Menjelajah versi terdahulu',
'history-show-deleted'   => 'Hanya yang dihapus',
'histfirst'              => 'Terlama',
'histlast'               => 'Terbaru',
'historysize'            => '($1 {{PLURAL:$1|bita|bita}})',
'historyempty'           => '(kosong)',

# Revision feed
'history-feed-title'          => 'Riwayat revisi',
'history-feed-description'    => 'Riwayat revisi halaman ini di wiki',
'history-feed-item-nocomment' => '$1 pada $2',
'history-feed-empty'          => 'Halaman yang diminta tak ditemukan.
Kemungkinan telah dihapus dari wiki, atau diberi nama baru.
Coba [[Special:Search|lakukan pencarian di wiki]] untuk halaman baru yang relevan.',

# Revision deletion
'rev-deleted-comment'         => '(komentar dihapus)',
'rev-deleted-user'            => '(nama pengguna dihapus)',
'rev-deleted-event'           => '(isi dihapus)',
'rev-deleted-user-contribs'   => '[nama pengguna atau alamat IP dihapus - suntingan disembunyikan pada daftar kontribusi]',
'rev-deleted-text-permission' => "Revisi halaman ini telah '''dihapus'''.
Rinciannya mungkin tersedia di [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log penghapusan]",
'rev-deleted-text-unhide'     => "Revisi ini telah '''dihapus'''.
Rinciannya mungkin tersedia di [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log penghapusan].
Sebagai seorang pengurus Anda masih dapat [$1 melihat revisi ini] jika Anda mau.",
'rev-suppressed-text-unhide'  => "Revisi halaman ini telah '''disupresi'''.
Rinciannya mungkin tersedia di [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} log supresi].
Sebagai seorang pengurus, Anda masih dapat [$1 melihat revisi ini] jika Anda mau.",
'rev-deleted-text-view'       => "Revisi ini telah '''dihapus'''.
Sebagai seorang pengurus, Anda dapat melihatnya; rinciannya mungkin tersedia di [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log penghapusan].",
'rev-suppressed-text-view'    => "Revisi halaman ini telah '''disupresi'''.
Sebagai seorang pengurus, Anda masih dapat melihatnya; rinciannya mungkin tersedia di [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} log supresi].",
'rev-deleted-no-diff'         => "Anda tidak dapat melihat perbedaan ini karena salah satu dari revisinya telah '''dihapus'''.
Rinciannya mungkin tersedia di [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log penghapusan].",
'rev-suppressed-no-diff'      => "Anda tidak dapat melihat perubahan ini karena salah satu dari revisi telah '''dihapus'''.",
'rev-deleted-unhide-diff'     => "Salah satu revisi pada tampilan perbedaan ini telah '''dihapus'''.
Rinciannya mungkin tersedia di [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log penghapusan].
Sebagai seorang pengurus, Anda masih dapat [$1 melihat perbedaan ini] jika Anda ingin.",
'rev-suppressed-unhide-diff'  => "Salah satu perbedaan revisi telah di '''tekan'''.
Penjelasan mungkin terdapat di [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} log penekanan].
Sebagai pengurus anda masih dapat [$1 melihat perbedaan ini] jika anda ingin melanjutkan.",
'rev-deleted-diff-view'       => "Salah satu revisi perbedaan ini telah '''dihapus'''.
Sebagai seorang pengurus, Anda dapat melihat perbedaan ini; detail mungkin tersedia di [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log penghapusan].",
'rev-suppressed-diff-view'    => "Salah satu revisi perbedaan ini telah '''disembunyikan'''.
Sebagai seorang pengurus, Anda dapat melihat perbedaan ini; detail mungkin tersedia di [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log penyembunyian].",
'rev-delundel'                => 'tampilkan/sembunyikan',
'rev-showdeleted'             => 'tampilkan',
'revisiondelete'              => 'Hapus/batal hapus revisi',
'revdelete-nooldid-title'     => 'Target revisi tak ditemukan',
'revdelete-nooldid-text'      => 'Anda belum memberikan target revisi untuk menjalankan fungsi ini.',
'revdelete-nologtype-title'   => 'Tipe log tak diberikan',
'revdelete-nologtype-text'    => 'Anda tidak memberikan suatu tipe log untuk menerapkan tindakan ini.',
'revdelete-nologid-title'     => 'Entri log tak valid',
'revdelete-nologid-text'      => 'Anda mungkin tidak menyebutkan suatu log peristiwa target untuk menjalankan fungsi ini atau entri yang dimaksud tak ditemukan.',
'revdelete-no-file'           => 'Berkas yang dituju tidak ditemukan.',
'revdelete-show-file-confirm' => 'Apakah Anda yakin ingin melihat revisi yang telah dihapus dari berkas "<nowiki>$1</nowiki>" per $3, $2?',
'revdelete-show-file-submit'  => 'Ya',
'revdelete-selected'          => "'''{{PLURAL:$2|Revisi|Revisi-revisi}} pilihan dari '''$1''''''",
'logdelete-selected'          => "'''{{PLURAL:$1|Log|Log-log}} pilihan untuk:'''",
'revdelete-text'              => "'''Revisi dan tindakan yang telah dihapus akan tetap muncul di versi terdahulu dan log halaman, tapi bagian dari isinya tidak bisa diakses publik.'''
Pengurus {{SITENAME}} lain akan tetap dapat mengakses isi yang tersembunyi ini dan dapat membatalkan penghapusannya menggunakan antarmuka yang sama, kecuali ada pembatasan lain yang dibuat oleh operator situs.",
'revdelete-confirm'           => 'Tolong konfirmasi bahwa Anda memang bermaksud melakukan ini, memahami konsekuensinya, dan bahwa Anda melakukannya sesuai dengan [[{{MediaWiki:Policy-url}}|kebijakan]].',
'revdelete-suppress-text'     => "Penyembunyian revisi '''hanya''' boleh digunakan untuk kasus-kasus berikut:
* Informasi pribadi yang tak sepantasnya
*: ''alamat rumah dan nomor telepon, nomor kartu identitas, dan lain-lain.''",
'revdelete-legend'            => 'Atur batasan:',
'revdelete-hide-text'         => 'Sembunyikan teks revisi',
'revdelete-hide-image'        => 'Sembunyikan isi berkas',
'revdelete-hide-name'         => 'Sembunyikan tindakan dan target',
'revdelete-hide-comment'      => 'Sembunyikan ringkasan suntingan',
'revdelete-hide-user'         => 'Sembunyikan nama pengguna/IP penyunting',
'revdelete-hide-restricted'   => 'Sembunyikan data dari opsis juga',
'revdelete-radio-same'        => '(jangan diubah)',
'revdelete-radio-set'         => 'Ya',
'revdelete-radio-unset'       => 'Tidak',
'revdelete-suppress'          => 'Sembunyikan juga dari pengurus',
'revdelete-unsuppress'        => 'Hapus batasan pada revisi yang dikembalikan',
'revdelete-log'               => 'Alasan:',
'revdelete-submit'            => 'Terapkan pada {{PLURAL:$1|revisi|revisi}} terpilih',
'revdelete-logentry'          => 'mengubah tampilan revisi untuk [[$1]]',
'logdelete-logentry'          => 'mengubah aturan penyembunyian dari [[$1]]',
'revdelete-success'           => "'''Keterlihatan revisi berhasil diperbarui.'''",
'revdelete-failure'           => "'''Keterlihatan revisi tak dapat diperbarui:'''
$1",
'logdelete-success'           => 'Aturan penyembunyian tindakan berhasil diterapkan.',
'logdelete-failure'           => "'''Aturan penyembunyian tidak dapat diterapkan:'''
$1",
'revdel-restore'              => 'Ubah tampilan',
'revdel-restore-deleted'      => 'Suntingan yang telah dihapus',
'revdel-restore-visible'      => 'tampilan revisi',
'pagehist'                    => 'Versi terdahulu halaman',
'deletedhist'                 => 'Sejarah yang dihapus',
'revdelete-content'           => 'konten',
'revdelete-summary'           => 'ringkasan',
'revdelete-uname'             => 'nama pengguna',
'revdelete-restricted'        => 'akses telah dibatasi untuk opsis',
'revdelete-unrestricted'      => 'pembatasan akses opsis dihapuskan',
'revdelete-hid'               => 'sembunyikan $1',
'revdelete-unhid'             => 'tampilkan $1',
'revdelete-log-message'       => '$1 untuk $2 {{PLURAL:$2|revisi|revisi}}',
'logdelete-log-message'       => '$1 untuk $2 {{PLURAL:$2|peristiwa|peristiwa}}',
'revdelete-hide-current'      => 'Gagal menyembunyikan revisi tertanggal $2, $1: ini adalah revisi terkini.
Revisi ini tidak dapat disembunyikan.',
'revdelete-show-no-access'    => 'Gagal menampilkan revisi tertanggal $2, $1: revisi ini telah ditandai "terbatas".
Anda tidak memiliki akses ke revisi ini.',
'revdelete-modify-no-access'  => 'Gagal mengubah revisi tertanggal $2, $1: revisi ini telah ditandai "terbatas".
Anda tidak memiliki akses ke revisi ini.',
'revdelete-modify-missing'    => 'Gagal mengubah revisi ID $1: revisi ini tidak ditemukan di basis data!',
'revdelete-no-change'         => "'''Peringatan:''' revisi per $2, $1 telah memiliki aturan penyembunyian tersebut.",
'revdelete-concurrent-change' => 'Gagal mengubah revisi per $2, $1: statusnya kemungkinan telah diubah oleh pengguna lain bersamaan dengan Anda.
Silakan periksa catatan log.',
'revdelete-only-restricted'   => 'Kesalahan sewaktu menyembunyikan butir bertanggal $2, $1: Anda tidak dapat menyembunyikan butir dari pengurus tanpa memilih juga salah satu opsi penyembunyian lainnya.',
'revdelete-reason-dropdown'   => '*Alasan penghapusan
** Pelanggaran hak cipta
** Informasi pribadi yang tidak pantas
** Berpotensi mencemarkan nama baik',
'revdelete-otherreason'       => 'Alasan lain/tambahan:',
'revdelete-reasonotherlist'   => 'Alasan lain',
'revdelete-edit-reasonlist'   => 'Alasan penghapusan suntingan',
'revdelete-offender'          => 'Revisi penulis:',

# Suppression log
'suppressionlog'     => 'Log penyembunyian',
'suppressionlogtext' => 'Berikut adalah daftar penghapusan dan pemblokiran, termasuk konten yang disembunyikan dari para opsis.
Lihat [[Special:IPBlockList|daftar IP yang diblokir]] untuk daftar terkininya.',

# Revision move
'moverevlogentry'              => '{{PLURAL:$3|satu revisi |$3 revisi}} pindah dari $1 ke $2',
'revisionmove'                 => 'Revisi pindah dari "$1"',
'revmove-explain'              => 'Revisi berikut akan dipindahkan dari $1 ke halaman tujuan yang ditetapkane. Jika tujuan tidak ada, akan dibuatkan. Sebaliknya, revisi ini akan di gabungkan dalam sejarah halaman.',
'revmove-legend'               => 'Tetapkan halaman tujuan dan ringkasan',
'revmove-submit'               => 'Pindahkan revisi ke halaman yang dipilih',
'revisionmoveselectedversions' => 'Pindahkan revisi yang dipilih',
'revmove-reasonfield'          => 'Alasan:',
'revmove-titlefield'           => 'Halaman tujuan:',
'revmove-badparam-title'       => 'Parameter Buruk',
'revmove-badparam'             => 'Permintaan Anda mengandung parameter yang tidak sah atau kurang. Silahkan tekan "kembali" dan coba lagi.',
'revmove-norevisions-title'    => 'Revisi target tak sah',
'revmove-norevisions'          => 'Anda belum menetapkan satu atau lebih revisi tujuan untuk menjalankan fungsi atau revisi yang diberikan tidak ada.',
'revmove-nullmove-title'       => 'Judul tidak sah',
'revmove-nullmove'             => 'Halaman sumber dan tujuan sama. Silahkan tekan "kembali" dan masukan nama halaman yang berbeda dari "[[$1]]".',
'revmove-success-existing'     => '{{PLURAL:$1|satu revisi dari [[$2]] telah|$1 revisi dari [[$2]] telah}} dipindahkan ke halaman yang ada [[$3]].',
'revmove-success-created'      => '{{PLURAL:$1|satu revisi dari [[$2]] telah|$1 revisi dari [[$2]] telah}} dipindahkan ke halaman baru yang dibuat [[$3]].',

# History merging
'mergehistory'                     => 'Gabung sejarah halaman',
'mergehistory-header'              => 'Halaman ini memperbolehkan Anda untuk menggabungkan revisi-revisi dari satu halaman sumber ke halaman yang lebih baru.
Pastikan bahwa perubahan ini tetap mempertahankan kontinuitas versi terdahulu halaman.',
'mergehistory-box'                 => 'Gabung revisi-revisi dari dua halaman:',
'mergehistory-from'                => 'Halaman sumber:',
'mergehistory-into'                => 'Halaman tujuan:',
'mergehistory-list'                => 'Mergeable edit history',
'mergehistory-merge'               => 'Revisi-revisi berikut dari [[:$1]] dapat digabungkan ke [[:$2]]. Gunakan tombol radio untuk menggabungkan revisi-revisi yang dibuat sebelum waktu tertentu. Perhatikan, menggunakan pranala navigasi akan mengeset ulang kolom.',
'mergehistory-go'                  => 'Tampilkan suntingan-suntingan yang dapat digabung',
'mergehistory-submit'              => 'Gabung revisi',
'mergehistory-empty'               => 'Tidak ada revisi yang dapat digabung.',
'mergehistory-success'             => '$3 {{PLURAL:$3|revisi|revisi}} dari [[:$1]] berhasil digabungkan ke [[:$2]].',
'mergehistory-fail'                => 'Tidak dapat melakukan penggabungan, harap periksa kembali halaman dan parameter waktu.',
'mergehistory-no-source'           => 'Halaman sumber $1 tidak ada.',
'mergehistory-no-destination'      => 'Halaman tujuan $1 tidak ada.',
'mergehistory-invalid-source'      => 'Judul halaman sumber haruslah judul yang berlaku.',
'mergehistory-invalid-destination' => 'Judul halaman tujuan haruslah judul yang valid.',
'mergehistory-autocomment'         => '[[:$1]] telah digabungkan ke [[:$2]]',
'mergehistory-comment'             => '[[:$1]] telah digabungkan ke [[:$2]]: $3',
'mergehistory-same-destination'    => 'Nama halaman sumber dan tujuan tidak boleh sama',
'mergehistory-reason'              => 'Alasan:',

# Merge log
'mergelog'           => 'Log penggabungan',
'pagemerge-logentry' => 'menggabungkan [[$1]] ke [[$2]] (revisi sampai dengan $3)',
'revertmerge'        => 'Batal penggabungan',
'mergelogpagetext'   => 'Di bawah ini adalah daftar penggabungan sejarah halaman ke halaman yang lain.',

# Diffs
'history-title'            => 'Riwayat revisi dari "$1"',
'difference'               => '(Perbedaan antarrevisi)',
'difference-multipage'     => '(Perbedaan antarhalaman)',
'lineno'                   => 'Baris $1:',
'compareselectedversions'  => 'Bandingkan versi terpilih',
'showhideselectedversions' => 'Tampilkan/sembunyikan versi terpilih',
'editundo'                 => 'batalkan',
'diff-multi'               => '({{PLURAL:$1|Satu|$1}} revisi antara oleh {{PLURAL:$2|satu|$2}} pengguna tak ditampilkan)',
'diff-multi-manyusers'     => '({{PLURAL:$1|Satu|$1}} revisi antara oleh lebih dari $2 {{PLURAL:$2|satu|$2}} pengguna tak ditampilkan)',

# Search results
'searchresults'                    => 'Hasil pencarian',
'searchresults-title'              => 'Hasil pencarian untuk "$1"',
'searchresulttext'                 => 'Untuk informasi lebih lanjut tentang pencarian di {{SITENAME}}, lihat [[{{MediaWiki:Helppage}}|halaman bantuan]].',
'searchsubtitle'                   => 'Anda mencari \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|semua halaman yang dimulai dengan "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|semua halaman yang terpaut ke "$1"]])',
'searchsubtitleinvalid'            => "Anda mencari '''$1'''",
'toomanymatches'                   => 'Pencarian menghasilkan terlalu banyak hasil, silakan masukkan kueri lain',
'titlematches'                     => 'Judul halaman yang sama',
'notitlematches'                   => 'Tidak ada judul halaman yang cocok',
'textmatches'                      => 'Teks halaman yang cocok',
'notextmatches'                    => 'Tidak ada teks halaman yang cocok',
'prevn'                            => '{{PLURAL:$1|$1}} sebelumnya',
'nextn'                            => '{{PLURAL:$1|$1}} selanjutnya',
'prevn-title'                      => '$1 {{PLURAL:$1|hasil|hasil}} sebelumnya',
'nextn-title'                      => '$1 {{PLURAL:$1|hasil|hasil}} selanjutnya',
'shown-title'                      => 'Tampilkan $1 {{PLURAL:$1|hasil|hasil}} per halaman',
'viewprevnext'                     => 'Lihat ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Opsi pencarian',
'searchmenu-exists'                => "* Halaman '''[[$1]]'''",
'searchmenu-new'                   => "'''Buat halaman \"[[:\$1]]\" di wiki ini!'''",
'searchhelp-url'                   => 'Help:Isi',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Lihat daftar halaman dengan awalan ini]]',
'searchprofile-articles'           => 'Halaman isi',
'searchprofile-project'            => 'Halaman Bantuan dan Proyek',
'searchprofile-images'             => 'Berkas Multimedia',
'searchprofile-everything'         => 'Semua',
'searchprofile-advanced'           => 'Lanjutan',
'searchprofile-articles-tooltip'   => 'Pencarian di $1',
'searchprofile-project-tooltip'    => 'Pencarian di $1',
'searchprofile-images-tooltip'     => 'Pencarian berkas',
'searchprofile-everything-tooltip' => 'Pencarian di seluruh situs (termasuk halaman pembicaraan)',
'searchprofile-advanced-tooltip'   => 'Pencarian di ruang nama tertentu',
'search-result-size'               => '$1 ({{PLURAL:$2|1 kata|$2 kata}})',
'search-result-category-size'      => '{{PLURAL:$1|1 anggota|$1 anggota}} ({{PLURAL:$2|1 subkategori|$2 subkategori}}, {{PLURAL:$3|1 berkas|$3 berkas}})',
'search-result-score'              => 'Relevansi: $1%',
'search-redirect'                  => '(pengalihan $1)',
'search-section'                   => '(bagian $1)',
'search-suggest'                   => 'Mungkin maksud Anda adalah: $1',
'search-interwiki-caption'         => 'Proyek lain',
'search-interwiki-default'         => 'Hasil $1:',
'search-interwiki-more'            => '(selanjutnya)',
'search-mwsuggest-enabled'         => 'dengan saran',
'search-mwsuggest-disabled'        => 'tidak ada saran',
'search-relatedarticle'            => 'Berkaitan',
'mwsuggest-disable'                => 'Non-aktifkan saran AJAX',
'searcheverything-enable'          => 'Cari di semua ruang nama',
'searchrelated'                    => 'berkaitan',
'searchall'                        => 'semua',
'showingresults'                   => "Di bawah ini ditampilkan hingga {{PLURAL:$1|'''1''' hasil|'''$1''' hasil}}, dimulai dari #'''$2'''.",
'showingresultsnum'                => "Di bawah ini ditampilkan {{PLURAL:$3|'''1'''|'''$3'''}} hasil, dimulai dari #'''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Hasil '''$1''' dari '''$3'''|Hasil '''$1 - $2''' dari '''$3'''}} untuk '''$4'''",
'nonefound'                        => "'''Catatan''': Hanya beberapa ruang nama yang secara baku dimasukkan dalam pencarian. Coba awali permintaan Anda dengan ''all:'' untuk mencari semua isi (termasuk halaman pembicaraan, templat, dll), atau gunakan ruang nama yang diinginkan sebagai awalan.",
'search-nonefound'                 => 'Tidak ada hasil yang sesuai dengan kriteria.',
'powersearch'                      => 'Pencarian lanjut',
'powersearch-legend'               => 'Pencarian lanjut',
'powersearch-ns'                   => 'Mencari di ruang nama:',
'powersearch-redir'                => 'Daftar pengalihan',
'powersearch-field'                => 'Mencari',
'powersearch-togglelabel'          => 'Pilih:',
'powersearch-toggleall'            => 'Semua',
'powersearch-togglenone'           => 'Tidak ada',
'search-external'                  => 'Pencarian eksternal',
'searchdisabled'                   => 'Pencarian {{SITENAME}} sementara dimatikan.
Anda dapat mencari melalui Google untuk sementara waktu.
Perlu diingat bahwa indeks Google untuk konten {{SITENAME}} mungkin belum mencakup perubahan-perubahan terakhir.',

# Quickbar
'qbsettings'               => 'Pengaturan bar pintas',
'qbsettings-none'          => 'Tidak ada',
'qbsettings-fixedleft'     => 'Tetap sebelah kiri',
'qbsettings-fixedright'    => 'Tetap sebelah kanan',
'qbsettings-floatingleft'  => 'Mengambang sebelah kiri',
'qbsettings-floatingright' => 'Mengambang sebelah kanan',

# Preferences page
'preferences'                   => 'Preferensi',
'mypreferences'                 => 'Preferensi saya',
'prefs-edits'                   => 'Jumlah suntingan:',
'prefsnologin'                  => 'Belum masuk log',
'prefsnologintext'              => 'Anda harus <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} masuk log]</span> untuk mengeset preferensi Anda.',
'changepassword'                => 'Ganti kata sandi',
'prefs-skin'                    => 'Kulit',
'skin-preview'                  => 'Pratayang',
'prefs-math'                    => 'Matematika',
'datedefault'                   => 'Tak ada preferensi',
'prefs-datetime'                => 'Tanggal dan waktu',
'prefs-personal'                => 'Profil',
'prefs-rc'                      => 'Perubahan terbaru',
'prefs-watchlist'               => 'Pemantauan',
'prefs-watchlist-days'          => 'Jumlah hari maksimum yang ditampilkan di daftar pantauan:',
'prefs-watchlist-days-max'      => '(maksimum 7 hari)',
'prefs-watchlist-edits'         => 'Jumlah suntingan maksimum yang ditampilkan di daftar pantauan yang lebih lengkap:',
'prefs-watchlist-edits-max'     => '(nilai maksimum: 1000)',
'prefs-watchlist-token'         => 'Token pantauan:',
'prefs-misc'                    => 'Lain-lain',
'prefs-resetpass'               => 'Ganti kata sandi',
'prefs-email'                   => 'Opsi surel',
'prefs-rendering'               => 'Tampilan',
'saveprefs'                     => 'Simpan',
'resetprefs'                    => 'Batalkan perubahan',
'restoreprefs'                  => 'Kembalikan semua setelan bawaan',
'prefs-editing'                 => 'Penyuntingan',
'prefs-edit-boxsize'            => 'Ukuran kotak penyuntingan.',
'rows'                          => 'Baris:',
'columns'                       => 'Kolom:',
'searchresultshead'             => 'Cari',
'resultsperpage'                => 'Hasil per halaman:',
'contextlines'                  => 'Baris ditampilkan per hasil:',
'contextchars'                  => 'Karakter untuk konteks per baris:',
'stub-threshold'                => 'Ambang batas untuk format <a href="#" class="stub">pranala rintisan</a>:',
'stub-threshold-disabled'       => 'Dinonaktifkan',
'recentchangesdays'             => 'Jumlah hari yang ditampilkan di perubahan terbaru:',
'recentchangesdays-max'         => '(maksimum $1 {{PLURAL:$1|hari|hari}})',
'recentchangescount'            => 'Standar jumlah suntingan yang ditampilkan:',
'prefs-help-recentchangescount' => 'Opsi ini berlaku untuk perubahan terbaru, versi terdahulu halaman, dan log.',
'prefs-help-watchlist-token'    => 'Mengisi kotak ini dengan kunci rahasia (PIN) akan menghasilkan sindikasi RSS untuk daftar pantauan Anda. Siapa pun yang mengetahui kunci ini dapat membaca daftar pantauan Anda, jadi pilihlah nilainya dengan hati-hati
Berikut ini adalah nilai acak yang dapat Anda gunakan: $1',
'savedprefs'                    => 'Preferensi Anda telah disimpan',
'timezonelegend'                => 'Zona waktu:',
'localtime'                     => 'Waktu setempat:',
'timezoneuseserverdefault'      => 'Gunakan standar server',
'timezoneuseoffset'             => 'Lainnya (tentukan perbedaannya)',
'timezoneoffset'                => 'Perbedaan¹:',
'servertime'                    => 'Waktu server:',
'guesstimezone'                 => 'Isikan dari penjelajah web',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antarktika',
'timezoneregion-arctic'         => 'Arktik',
'timezoneregion-asia'           => 'Asia',
'timezoneregion-atlantic'       => 'Samudera Atlantik',
'timezoneregion-australia'      => 'Australia',
'timezoneregion-europe'         => 'Eropa',
'timezoneregion-indian'         => 'Samudera Hindia',
'timezoneregion-pacific'        => 'Samudera Pasifik',
'allowemail'                    => 'Izinkan pengguna lain mengirim surel',
'prefs-searchoptions'           => 'Pencarian',
'prefs-namespaces'              => 'Ruang nama',
'defaultns'                     => 'Atau cari dalam ruang-ruang nama berikut:',
'default'                       => 'baku',
'prefs-files'                   => 'Berkas',
'prefs-custom-css'              => 'CSS pribadi',
'prefs-custom-js'               => 'JS pribadi',
'prefs-common-css-js'           => 'CSS/JS berbagi untuk semua kulit:',
'prefs-reset-intro'             => 'Anda dapat menggunakan halaman ini untuk mengembalikan preferensi Anda ke setelan baku situs.
Pengembalian preferensi tidak dapat dibatalkan.',
'prefs-emailconfirm-label'      => 'Konfirmasi surel:',
'prefs-textboxsize'             => 'Ukuran kotak suntingan',
'youremail'                     => 'Surel:',
'username'                      => 'Nama pengguna:',
'uid'                           => 'ID pengguna:',
'prefs-memberingroups'          => 'Anggota {{PLURAL:$1|kelompok|kelompok}}:',
'prefs-registration'            => 'Waktu pendaftaran:',
'yourrealname'                  => 'Nama asli:',
'yourlanguage'                  => 'Bahasa:',
'yourvariant'                   => 'Varian bahasa',
'yournick'                      => 'Tanda tangan:',
'prefs-help-signature'          => 'Komentar pada halaman pembicaraan perlu ditandatangani dengan "<nowiki>~~~~</nowiki>" yang akan diubah menjadi tanda tangan Anda dan waktu saat ini.',
'badsig'                        => 'Tanda tangan mentah tak sah; periksa tag HTML.',
'badsiglength'                  => 'Tanda tangan Anda terlalu panjang.
Jangan lebih dari $1 {{PLURAL:$1|karakter|karakter}}.',
'yourgender'                    => 'Jenis kelamin:',
'gender-unknown'                => 'Tak dinyatakan',
'gender-male'                   => 'Laki-laki',
'gender-female'                 => 'Perempuan',
'prefs-help-gender'             => 'Opsional: digunakan untuk perbaikan penyebutan gender oleh perangkat lunak. Informasi ini akan terbuka untuk umum.',
'email'                         => 'Surel',
'prefs-help-realname'           => 'Nama asli bersifat opsional.
Jika Anda memberikannya, nama asli Anda akan digunakan untuk memberi pengenalan atas hasil kerja Anda.',
'prefs-help-email'              => 'Alamat surel bersifat opsional, namun bila sewaktu-waktu Anda lupa akan kata sandi Anda, kami dapat mengirimkannya melalui surel tersebut.
Anda juga dapat memilih untuk memungkinkan orang lain menghubungi Anda melalui halaman pengguna atau halaman pembicaraan pengguna Anda tanpa perlu membuka identitas Anda.',
'prefs-help-email-required'     => 'Alamat surel dibutuhkan.',
'prefs-info'                    => 'Informasi dasar',
'prefs-i18n'                    => 'Internasionalisasi',
'prefs-signature'               => 'Tanda tangan',
'prefs-dateformat'              => 'Format tanggal',
'prefs-timeoffset'              => 'Format waktu',
'prefs-advancedediting'         => 'Opsi lanjutan',
'prefs-advancedrc'              => 'Opsi lanjutan',
'prefs-advancedrendering'       => 'Opsi lanjutan',
'prefs-advancedsearchoptions'   => 'Opsi lanjutan',
'prefs-advancedwatchlist'       => 'Opsi lanjutan',
'prefs-displayrc'               => 'Pilihan tampilan',
'prefs-displaysearchoptions'    => 'Pilihan tampilan',
'prefs-displaywatchlist'        => 'Pilihan tampilan',
'prefs-diffs'                   => 'Beda',

# User rights
'userrights'                   => 'Manajemen hak pengguna',
'userrights-lookup-user'       => 'Mengatur kelompok pengguna',
'userrights-user-editname'     => 'Masukkan nama pengguna:',
'editusergroup'                => 'Sunting kelompok pengguna',
'editinguser'                  => "Mengganti hak akses pengguna '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Sunting kelompok pengguna',
'saveusergroups'               => 'Simpan kelompok pengguna',
'userrights-groupsmember'      => 'Anggota dari:',
'userrights-groupsmember-auto' => 'Anggota implisit dari:',
'userrights-groups-help'       => 'Anda dapat mengubah kelompok pengguna ini:
* Kotak dengan tanda cek merupakan kelompok pengguna yang bersangkutan
* Kotak tanpa tanda cek berarti pengguna ini bukan anggota kelompok tersebut
* Tanda * menandai bahwa Anda tidak dapat membatalkan kelompok tersebut bila Anda telah menambahkannya, atau sebaliknya.',
'userrights-reason'            => 'Alasan:',
'userrights-no-interwiki'      => 'Anda tidak memiliki hak untuk mengubah hak pengguna di wiki yang lain.',
'userrights-nodatabase'        => 'Basis data $1 tidak ada atau bukan lokal.',
'userrights-nologin'           => 'Anda harus [[Special:UserLogin|masuk log]] dengan menggunakan akun pengurus untuk dapat mengubah hak pengguna.',
'userrights-notallowed'        => 'Anda tidak berhak untuk mengubah hak pengguna',
'userrights-changeable-col'    => 'Kelompok yang dapat Anda ubah',
'userrights-unchangeable-col'  => 'Kelompok yang tidak dapat Anda ubah',

# Groups
'group'               => 'Kelompok:',
'group-user'          => 'Pengguna',
'group-autoconfirmed' => 'Pengguna terkonfirmasi otomatis',
'group-bot'           => 'Bot',
'group-sysop'         => 'Pengurus',
'group-bureaucrat'    => 'Birokrat',
'group-suppress'      => 'Pengawas',
'group-all'           => '(semua)',

'group-user-member'          => 'Pengguna',
'group-autoconfirmed-member' => 'Pengguna terkonfirmasi otomatis',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Pengurus',
'group-bureaucrat-member'    => 'Birokrat',
'group-suppress-member'      => 'Pengawas',

'grouppage-user'          => '{{ns:project}}:Pengguna',
'grouppage-autoconfirmed' => '{{ns:project}}:Pengguna terkonfirmasi otomatis',
'grouppage-bot'           => '{{ns:project}}:Bot',
'grouppage-sysop'         => '{{ns:project}}:Pengurus',
'grouppage-bureaucrat'    => '{{ns:project}}:Birokrat',
'grouppage-suppress'      => '{{ns:project}}:Pengawas',

# Rights
'right-read'                  => 'Membaca halaman',
'right-edit'                  => 'Menyunting halaman',
'right-createpage'            => 'Membuat halaman baru (yang bukan halaman pembicaraan)',
'right-createtalk'            => 'Membuat halaman pembicaraan',
'right-createaccount'         => 'Membuat akun baru',
'right-minoredit'             => 'Menandai suntingan sebagai minor',
'right-move'                  => 'Memindahkan halaman',
'right-move-subpages'         => 'Memindahkan halaman dengan seluruh subhalamannya',
'right-move-rootuserpages'    => 'Memindahkan halaman utama pengguna',
'right-movefile'              => 'Memindahkan berkas',
'right-suppressredirect'      => 'Tidak membuat pengalihan dari nama lama ketika memindahkan halaman',
'right-upload'                => 'Memuat berkas',
'right-reupload'              => 'Menimpa berkas yang sudah ada',
'right-reupload-own'          => 'Menimpa berkas yang sudah ada yang dimuat oleh pengguna yang sama',
'right-reupload-shared'       => 'Menolak berkas-berkas pada penyimpanan media lokal bersama',
'right-upload_by_url'         => 'Memuatkan berkas dari sebuah alamat URL',
'right-purge'                 => 'Menghapus singgahan suatu halaman tanpa halaman konfirmasi',
'right-autoconfirmed'         => 'Menyunting halaman yang semi dilindungi',
'right-bot'                   => 'Diperlakukan sebagai sebuah proses otomatis',
'right-nominornewtalk'        => 'Ketiadaan suntingan kecil di halaman pembicaraan memicu tampilan pesan baru',
'right-apihighlimits'         => 'Menggunakan batasan yang lebih tinggi dalam kueri API',
'right-writeapi'              => 'Menggunakan API penulisan',
'right-delete'                => 'Menghapus halaman',
'right-bigdelete'             => 'Menghapus halaman dengan banyak versi terdahulu',
'right-deleterevision'        => 'Menghapus dan membatalkan penghapusan revisi tertentu suatu halaman',
'right-deletedhistory'        => 'Melihat entri-entri revisi yang dihapus, tanpa teks yang berhubungan',
'right-deletedtext'           => 'Melihat teks yang dihapus dan perubahan antara revisi yang dihapus',
'right-browsearchive'         => 'Mencari halaman yang telah dihapus',
'right-undelete'              => 'Mengembalikan halaman yang telah dihapus',
'right-suppressrevision'      => 'Memeriksa dan mengembalikan revisi-revisi yang disembunyikan dari Opsis',
'right-suppressionlog'        => 'Melihat log privat',
'right-block'                 => 'Memblokir penyuntingan oleh pengguna lain',
'right-blockemail'            => 'Memblokir pengiriman surel oleh pengguna',
'right-hideuser'              => 'Memblokir nama pengguna dan menyembunyikannya dari publik',
'right-ipblock-exempt'        => 'Mengabaikan pemblokiran IP, pemblokiran otomatis, dan rentang pemblokiran',
'right-proxyunbannable'       => 'Mengabaikan pemblokiran otomatis atas proksi',
'right-unblockself'           => 'Melepaskan blokir diri sendiri',
'right-protect'               => 'Mengubah tingkat perlindungan dan menyunting halaman yang dilindungi',
'right-editprotected'         => 'Menyunting halaman yang dilindungi (tanpa perlindungan runtun)',
'right-editinterface'         => 'Menyunting antarmuka pengguna',
'right-editusercssjs'         => 'Menyunting arsip CSS dan JS pengguna lain',
'right-editusercss'           => 'Menyunting berkas CSS pengguna lain',
'right-edituserjs'            => 'Menyunting berkas JS pengguna lain',
'right-rollback'              => 'Mengembalikan dengan cepat suntingan-suntingan pengguna terakhir yang menyunting halaman tertentu',
'right-markbotedits'          => 'Menandai pengembalian revisi sebagai suntingan bot',
'right-noratelimit'           => 'Tidak dipengaruhi oleh pembatasan jumlah suntingan',
'right-import'                => 'Mengimpor halaman dari wiki lain',
'right-importupload'          => 'Mengimpor halaman dari sebuah berkas yang dimuatkan',
'right-patrol'                => 'Menandai suntingan pengguna lain sebagai terpatroli',
'right-autopatrol'            => 'Menyunting dengan status suntingan secara otomatis ditandai terpantau',
'right-patrolmarks'           => 'Melihat penandaan patroli perubahan terbaru',
'right-unwatchedpages'        => 'Melihat daftar halaman-halaman yang tidak dipantau',
'right-trackback'             => 'Mengirimkan sebuah penjejakan balik',
'right-mergehistory'          => 'Menggabungkan versi terdahulu halaman-halaman',
'right-userrights'            => 'Menyunting seluruh hak pengguna',
'right-userrights-interwiki'  => 'Menyunting hak para pengguna di wiki lain',
'right-siteadmin'             => 'Mengunci dan membuka kunci basis data',
'right-reset-passwords'       => 'Mereset kata sandi pengguna lain',
'right-override-export-depth' => 'Ekspor halaman termasuk halaman-halaman terkait hingga kedalaman 5',
'right-sendemail'             => 'Mengirim surel ke pengguna lain',
'right-revisionmove'          => 'Pindah revisi',

# User rights log
'rightslog'      => 'Log perubahan hak akses',
'rightslogtext'  => 'Di bawah ini adalah log perubahan terhadap hak-hak pengguna.',
'rightslogentry' => 'mengganti keanggotaan kelompok untuk $1 dari $2 menjadi $3',
'rightsnone'     => '(tidak ada)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'membaca halaman ini',
'action-edit'                 => 'menyunting halaman ini',
'action-createpage'           => 'membuat halaman baru',
'action-createtalk'           => 'membuat halaman pembicaraan baru',
'action-createaccount'        => 'membuat akun pengguna ini',
'action-minoredit'            => 'menandai sebagai suntingan kecil',
'action-move'                 => 'memindahkan halaman ini',
'action-move-subpages'        => 'memindahkan halaman ini, dan semua sub-halamannya',
'action-move-rootuserpages'   => 'memindahkan halaman utama pengguna',
'action-movefile'             => 'pindahkan berkas ini',
'action-upload'               => 'memuatkan berkas ini',
'action-reupload'             => 'menimpa berkas yang telah ada',
'action-reupload-shared'      => 'menimpa berkas yang telah ada di tempat penyimpanan berkas bersama',
'action-upload_by_url'        => 'memuatkan berkas ini dari sebuah alamat URL',
'action-writeapi'             => 'menggunakan API penulisan',
'action-delete'               => 'menghapus halaman ini',
'action-deleterevision'       => 'menghapus revisi ini',
'action-deletedhistory'       => 'melihat versi terdahulu halaman yang telah dihapus ini',
'action-browsearchive'        => 'mencari halaman-halaman yang telah dihapus',
'action-undelete'             => 'membatalkan penghapusan halaman ini',
'action-suppressrevision'     => 'meninjau dan mengembalikan revisi yang disembunyikan ini',
'action-suppressionlog'       => 'melihat log privat ini',
'action-block'                => 'memblokir pengguna ini dari menyunting',
'action-protect'              => 'mengganti tingkat perlindungan halaman ini',
'action-import'               => 'mengimpor halaman ini dari wiki lain',
'action-importupload'         => 'mengimpor halaman ini dari pemuatan berkas',
'action-patrol'               => 'menandai suntingan pengguna lain sebagai terpatroli',
'action-autopatrol'           => 'menandai suntingan Anda sendiri sebagai terpatroli',
'action-unwatchedpages'       => 'melihat daftar halaman yang tidak dipantau',
'action-trackback'            => 'mengirimkan penjejak balik',
'action-mergehistory'         => 'menggabungkan revisi-revisi terdahulu halaman ini',
'action-userrights'           => 'menyunting semua hak pengguna',
'action-userrights-interwiki' => 'menyunting hak akses dari pengguna di wiki lain',
'action-siteadmin'            => 'mengunci atau membuka kunci basis data',
'action-revisionmove'         => 'pindah revisi',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|perubahan|perubahan}}',
'recentchanges'                     => 'Perubahan terbaru',
'recentchanges-legend'              => 'Opsi perubahan terbaru',
'recentchangestext'                 => "Temukan perubahan terbaru dalam wiki di halaman ini. Keterangan: (beda) = perubahan, (versi) = sejarah revisi, '''B''' = halaman baru, '''k''' = suntingan kecil, '''b''' = suntingan bot, (± ''bita'') = jumlah penambahan/pengurangan isi, → = suntingan bagian, ← = ringkasan otomatis.
----",
'recentchanges-feed-description'    => 'Temukan perubahan terbaru dalam wiki di umpan ini.',
'recentchanges-label-newpage'       => 'Suntingan ini membuat halaman baru',
'recentchanges-label-minor'         => 'Ini adalah suntingan kecil',
'recentchanges-label-bot'           => 'Suntingan ini dilakukan oleh bot',
'recentchanges-label-unpatrolled'   => 'Suntingan ini belum terpatroli',
'rcnote'                            => "Berikut adalah {{PLURAL:$1|'''1'''|'''$1'''}} perubahan terbaru dalam {{PLURAL:$2|'''1''' hari|'''$2''' hari}} terakhir, sampai $4 pukul $5.",
'rcnotefrom'                        => 'Di bawah ini adalah perubahan sejak <strong>$2</strong> (ditampilkan sampai <strong>$1</strong> perubahan).',
'rclistfrom'                        => 'Perlihatkan perubahan terbaru sejak $1',
'rcshowhideminor'                   => '$1 suntingan kecil',
'rcshowhidebots'                    => '$1 bot',
'rcshowhideliu'                     => '$1 pengguna masuk log',
'rcshowhideanons'                   => '$1 pengguna anon',
'rcshowhidepatr'                    => '$1 suntingan terpatroli',
'rcshowhidemine'                    => '$1 suntingan saya',
'rclinks'                           => 'Perlihatkan $1 perubahan terbaru dalam $2 hari terakhir<br />$3',
'diff'                              => 'beda',
'hist'                              => 'versi',
'hide'                              => 'Sembunyikan',
'show'                              => 'Tampilkan',
'minoreditletter'                   => 'k',
'newpageletter'                     => 'B',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|pemantau|pemantau}}]',
'rc_categories'                     => 'Batasi sampai kategori (dipisah dengan "|")',
'rc_categories_any'                 => 'Apa pun',
'newsectionsummary'                 => '/* $1 */ bagian baru',
'rc-enhanced-expand'                => 'Tampilkan rincian (memerlukan JavaScript)',
'rc-enhanced-hide'                  => 'Sembunyikan rincian',

# Recent changes linked
'recentchangeslinked'          => 'Perubahan terkait',
'recentchangeslinked-feed'     => 'Perubahan terkait',
'recentchangeslinked-toolbox'  => 'Perubahan terkait',
'recentchangeslinked-title'    => 'Perubahan yang terkait dengan "$1"',
'recentchangeslinked-noresult' => 'Tidak terjadi perubahan pada halaman-halaman terkait selama periode yang telah ditentukan.',
'recentchangeslinked-summary'  => "Halaman istimewa ini memberikan daftar perubahan terakhir pada halaman-halaman terkait. Halaman yang Anda pantau ditandai dengan '''cetak tebal'''.",
'recentchangeslinked-page'     => 'Nama halaman:',
'recentchangeslinked-to'       => 'Perlihatkan perubahan dari halaman-halaman yang terhubung dengan halaman yang disajikan',

# Upload
'upload'                      => 'Unggah berkas',
'uploadbtn'                   => 'Muatkan berkas',
'reuploaddesc'                => 'Kembali ke formulir pemuatan',
'upload-tryagain'             => 'Kirim perubahan keterangan berkas',
'uploadnologin'               => 'Belum masuk log',
'uploadnologintext'           => 'Anda harus [[Special:UserLogin|masuk log]] untuk dapat memuatkan berkas.',
'upload_directory_missing'    => 'Direktori pemuatan ($1) tidak ditemukan dan tidak dapat dibuat oleh server web.',
'upload_directory_read_only'  => 'Direktori pemuatan ($1) tidak dapat ditulis oleh server web.',
'uploaderror'                 => 'Kesalahan pemuatan',
'upload-recreate-warning'     => "'''Peringatan: Berkas dengan nama itu telah dihapus atau dipindahkan.'''

Log penghapusan dan pemindahan laman ini adalah sebagai berikut:",
'uploadtext'                  => "Gunakan formulir di bawah untuk mengunggah berkas.
Untuk menampilkan atau mencari berkas yang sebelumnya dimuat, gunakan [[Special:FileList|daftar berkas]]. Pengunggahan (ulang) juga tercatat dalam [[Special:Log/upload|log pengunggahan]], sementara penghapusan tercatat dalam [[Special:Log/delete|log penghapusan]].

Untuk menampilkan atau menyertakan berkas di dalam suatu halaman, gunakan pranala dengan salah satu format di bawah ini:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Berkas.jpg]]</nowiki></tt>''' untuk menampilkan berkas dalam ukuran aslinya
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Berkas.png|200px|thumb|left|teks alternatif]]</nowiki></tt>''' untuk menampilkan berkas dengan lebar 200px dalam sebuah kotak di kiri halaman dengan 'teks alternatif' sebagai keterangan gambar
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Berkas.ogg]]</nowiki></tt>''' sebagai pranala langsung ke berkas yang dimaksud tanpa menampilkan berkas tersebut melalui wiki",
'upload-permitted'            => 'Jenis berkas yang diperbolehkan: $1.',
'upload-preferred'            => 'Jenis berkas yang disarankan: $1.',
'upload-prohibited'           => 'Jenis berkas yang dilarang: $1.',
'uploadlog'                   => 'log pengunggahan',
'uploadlogpage'               => 'Log pengunggahan',
'uploadlogpagetext'           => 'Berikut adalah daftar unggahan berkas terbaru. 
Lihat [[Special:NewFiles|galeri berkas baru]] untuk tampilan visual.',
'filename'                    => 'Nama berkas',
'filedesc'                    => 'Ringkasan',
'fileuploadsummary'           => 'Ringkasan:',
'filereuploadsummary'         => 'Perubahan berkas:',
'filestatus'                  => 'Status hak cipta:',
'filesource'                  => 'Sumber:',
'uploadedfiles'               => 'Berkas yang telah dimuat',
'ignorewarning'               => 'Abaikan peringatan dan langsung simpan berkas.',
'ignorewarnings'              => 'Abaikan peringatan apa pun',
'minlength1'                  => 'Nama berkas paling tidak harus terdiri dari satu huruf.',
'illegalfilename'             => 'Nama berkas "$1" mengandung aksara yang tidak diperbolehkan ada dalam judul halaman. Silakan ubah nama berkas tersebut dan cobalah memuatkannya kembali.',
'badfilename'                 => 'Nama berkas telah diubah menjadi "$1".',
'filetype-mime-mismatch'      => 'Ekstensi berkas tidak cocok dengan tipe MIME.',
'filetype-badmime'            => 'Berkas dengan tipe MIME "$1" tidak diperkenankan untuk dimuat.',
'filetype-bad-ie-mime'        => 'Tidak dapat memuat berkas ini karena Internet Explorer mendeteksinya sebagai "$1", yang tak diizinkan dan merupakan tipe berkas yang memiliki potensi bahaya.',
'filetype-unwanted-type'      => "'''\".\$1\"''' termasuk jenis berkas yang tidak diijinkan.
{{PLURAL:\$3|Jenis berkas yang disarankan adalah|Jenis berkas yang disarankan adalah}} \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' termasuk dalam jenis berkas yang tidak diijinkan.
{{PLURAL:\$3|Jenis berkas yang diijinkan adalah|Jenis berkas yang diijinkan adalah}} \$2.",
'filetype-missing'            => 'Berkas tak memiliki ekstensi (misalnya ".jpg").',
'empty-file'                  => 'Berkas yang Anda kirim kosong.',
'file-too-large'              => 'Ukuran berkas yang Anda muat terlalu besar.',
'filename-tooshort'           => 'Nama berkas terlalu pendek.',
'filetype-banned'             => 'Jenis berkas ini dilarang.',
'verification-error'          => 'Berkas ini tidak lulus verifikasi.',
'hookaborted'                 => 'Modifikasi yang coba Anda lakukan dibatalkan oleh suatu kaitan ekstensi.',
'illegal-filename'            => 'Nama berkas tidak diperbolehkan.',
'overwrite'                   => 'Tidak diizinkan untuk menimpa berkas yang telah ada.',
'unknown-error'               => 'Terjadi sebuah kesalahan yang tidak diketahui.',
'tmp-create-error'            => 'Tidak dapat membuat berkas sementara.',
'tmp-write-error'             => 'Kesalahan sewaktu menulis berkas sementara.',
'large-file'                  => 'Ukuran berkas disarankan untuk tidak melebihi $1 bita; berkas ini berukuran $2 bita.',
'largefileserver'             => 'Berkas ini lebih besar dari pada yang diizinkan server.',
'emptyfile'                   => 'Berkas yang Anda muatkan kelihatannya kosong. Hal ini mungkin disebabkan karena adanya kesalahan ketik pada nama berkas. Silakan pastikan apakah Anda benar-benar ingin memuatkan berkas ini.',
'fileexists'                  => "Suatu berkas dengan nama tersebut telah ada, harap periksa '''<tt>[[:$1]]</tt>''' jika Anda tidak yakin untuk mengubahnya.
[[$1|thumb]]",
'filepageexists'              => "Halaman deskripsi untuk berkas ini telah dibuat di '''<tt>[[:$1]]</tt>''', tapi saat ini tak ditemukan berkas dengan nama tersebut. Ringkasan yang Anda masukkan tidak akan tampil pada halaman deskripsi. Untuk memunculkannya, Anda perlu untuk menyuntingnya secara manual.
[[$1|thumb]]",
'fileexists-extension'        => "Berkas dengan nama serupa telah ada: [[$2|thumb]]
* Nama berkas yang akan dimuat: '''<tt>[[:$1]]</tt>'''
* Nama berkas yang telah ada: '''<tt>[[:$2]]</tt>'''
Mohon gunakan nama yang berbeda.",
'fileexists-thumbnail-yes'    => "Berkas ini tampaknya merupakan gambar yang ukurannya diperkecil ''(miniatur)''. [[$1|thumb]]
Harap periksa berkas '''<tt>[[:$1]]</tt>''' tersebut.
Jika berkas tersebut memang merupakan gambar dalam ukuran aslinya, Anda tidak perlu untuk memuat kembali miniatur lainnya.",
'file-thumbnail-no'           => "Nama berkas dimulai dengan '''<tt>$1</tt>'''.
Tampaknya berkas ini merupakan gambar dengan ukuran diperkecil ''(miniatur)''.
Jika Anda memiliki versi resolusi penuh dari gambar ini, harap muatkan berkas tersebut. Jika tidak, harap ubah nama berkas ini.",
'fileexists-forbidden'        => 'Suatu berkas dengan nama ini telah ada dan tak dapat ditimpa.
Jika Anda masih ingin memuat berkas Anda, silakan kembali dan gunakan nama baru. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Ditemukan berkas lain dengan nama yang sama di repositori bersama.
Jika Anda tetap ingin memuatkan berkas Anda, harap kembali dan gunakan nama lain. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Berkas ini berduplikasi dengan {{PLURAL:$1|berkas|berkas-berkas}} berikut:',
'file-deleted-duplicate'      => 'Sebuah berkas yang identik dengan berkas ini ([[$1]]) sudah pernah dihapuskan sebelumnya. Anda harus memeriksa sejarah penghapusan berkas tersebut sebelum melanjutkan memuat ulang berkas ini.',
'uploadwarning'               => 'Peringatan pemuatan',
'uploadwarning-text'          => 'Mohon perbaiki keterangan berkas di bawah dan coba lagi.',
'savefile'                    => 'Simpan berkas',
'uploadedimage'               => 'memuat "[[$1]]"',
'overwroteimage'              => 'memuat versi baru dari "[[$1]]"',
'uploaddisabled'              => 'Maaf, fasilitas pemuatan dimatikan.',
'copyuploaddisabled'          => 'Pengunggahan dengan URL dimatikan.',
'uploadfromurl-queued'        => 'Pengunggahan Anda telah terantri.',
'uploaddisabledtext'          => 'Pemuatan berkas tidak diizinkan.',
'php-uploaddisabledtext'      => 'Pemuatan berkas dimatikan di PHP. Silakan cek pengaturan file_uploads.',
'uploadscripted'              => 'Berkas ini mengandung HTML atau kode yang dapat diinterpretasikan dengan keliru oleh penjelajah web.',
'uploadvirus'                 => 'Berkas tersebut mengandung virus! Rincian: $1',
'upload-source'               => 'Berkas sumber',
'sourcefilename'              => 'Nama berkas sumber:',
'sourceurl'                   => 'URL sumber:',
'destfilename'                => 'Nama berkas tujuan:',
'upload-maxfilesize'          => 'Ukuran berkas maksimum: $1',
'upload-description'          => 'Keterangan berkas',
'upload-options'              => 'Opsi pengunduhan',
'watchthisupload'             => 'Pantau berkas ini',
'filewasdeleted'              => 'Suatu berkas dengan nama ini pernah dimuat dan selanjutnya dihapus. Harap cek $1 sebelum memuat lagi berkas tersebut.',
'upload-wasdeleted'           => "'''Peringatan: Anda memuat suatu berkas yang telah pernah dihapus.'''

Anda harus mempertimbangkan apakah perlu untuk melanjutkan pemuatan berkas ini.
Log penghapusan berkas adalah sebagai berikut:",
'filename-bad-prefix'         => "Nama berkas yang Anda muat diawali dengan '''\"\$1\"''', yang merupakan nama non-deskriptif yang biasanya diberikan secara otomatis oleh kamera digital. Harap pilih nama lain yang lebih deskriptif untuk berkas Anda.",
'upload-success-subj'         => 'Berhasil dimuat',
'upload-success-msg'          => 'Pengunggahan Anda dari [$2] berhasil. Hasilnya tersedia di sini: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Masalah pengunggahan',
'upload-failure-msg'          => 'Ada masalah dengan unggahan Anda dari [$2]:

$1',
'upload-warning-subj'         => 'Peringatan pemuatan',
'upload-warning-msg'          => 'Terjadi masalah dengan unggahan Anda dari [$2]. Anda dapat kembali ke [[Special:Upload/stash/$1|formulir pengunggahan]] untuk memerbaiki masalah ini.',

'upload-proto-error'        => 'Protokol tak tepat',
'upload-proto-error-text'   => 'Pemuatan jarak jauh membutuhkan URL yang diawali dengan <code>http://</code> atau <code>ftp://</code>.',
'upload-file-error'         => 'Kesalahan internal',
'upload-file-error-text'    => 'Suatu kesalahan internal terjadi sewaktu mencoba membuat berkas sementara di peladen.
Silakan hubungi salah seorang [[Special:ListUsers/sysop|pengurus]].',
'upload-misc-error'         => 'Kesalahan pemuatan yang tak dikenal',
'upload-misc-error-text'    => 'Suatu kesalahan yang tak dikenal terjadi sewaktu pemuatan. Harap pastikan bahwa URL tersebut valid dan dapat diakses dan silakan coba lagi. Jika masalah ini tetap terjadi, kontak administrator sistem.',
'upload-too-many-redirects' => 'URL mengandung terlalu banyak pengalihan',
'upload-unknown-size'       => 'Ukuran tidak diketahui',
'upload-http-error'         => 'Kesalahan HTTP terjadi: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Akses ditolak',
'img-auth-nopathinfo'   => 'PATH_INFO hilang.
Peladen anda tidak diatur untuk melewatkan informasi ini.
Ini mungkin CGI-based dan tidak ditunjang img_auth.
Lihat http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'Alur yang diminta tidak diatur dalam direktori ungahan.',
'img-auth-badtitle'     => 'Tidak dapat membangun judul yang sah dari "$1".',
'img-auth-nologinnWL'   => 'Anda tidak masuk log dan "$1" tidak dalam daftar putih.',
'img-auth-nofile'       => 'Berkas "$1" tidak ada.',
'img-auth-isdir'        => 'Anda mencoba mengakses direktori "$1".
Hanya akses berkas di bolehkan.',
'img-auth-streaming'    => 'Streaming "$1".',
'img-auth-public'       => 'Fungsi dari img_auth.php adalah mengeluarkan berkas dari wiki pribadi.
Wiki ini di atur sebagai wiki umum.
Untuk pilihan keamanan, img_auth.php dinonaktifkan.',
'img-auth-noread'       => 'Pengguna tidak memiliki akses untuk membaca "$1".',

# HTTP errors
'http-invalid-url'      => 'URL tidak sah: $1',
'http-invalid-scheme'   => 'URL dengan skema "$1" tidak didukung.',
'http-request-error'    => 'Permintaan HTTP gagal karena kesalahan yang tidak diketahui.',
'http-read-error'       => 'Kesalahan pembacaan HTTP',
'http-timed-out'        => 'Permintaan HTTP lewat tenggat.',
'http-curl-error'       => 'Kesalahan saat mengambil URL: $1',
'http-host-unreachable' => 'Tidak dapat mencapai URL.',
'http-bad-status'       => 'Ada masalah saat permintaan halaman HTTP: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL tidak dapat dihubungi',
'upload-curl-error6-text'  => 'URL yang diberikan tak dapat dihubungi. Harap periksa ulang bahwa URL tersebut tepat dan situs itu sedang aktif.',
'upload-curl-error28'      => 'Pemuatan lewat waktu',
'upload-curl-error28-text' => 'Situs yang dituju terlalu lambat merespon. Tolong cek apakah situs tersebut aktif, tunggu sebentar, dan coba lagi. Mungkin Anda perlu mencobanya di saat yang lebih longgar.',

'license'            => 'Jenis lisensi:',
'license-header'     => 'Jenis lisensi',
'nolicense'          => 'Tidak ada yang dipilih',
'license-nopreview'  => '(Pratayang tak tersedia)',
'upload_source_url'  => ' (suatu URL valid yang dapat diakses publik)',
'upload_source_file' => ' (suatu berkas di komputer Anda)',

# Special:ListFiles
'listfiles-summary'     => 'Halaman istimewa ini menampilkan semua berkas yang telah dimuat.
Secara baku, berkas yang terakhir dimuat berada pada urutan teratas.
Klik pada kepala kolom untuk mengubah urutan.',
'listfiles_search_for'  => 'Cari nama berkas:',
'imgfile'               => 'berkas',
'listfiles'             => 'Daftar berkas',
'listfiles_thumb'       => 'Miniatur',
'listfiles_date'        => 'Tanggal',
'listfiles_name'        => 'Nama',
'listfiles_user'        => 'Pengguna',
'listfiles_size'        => 'Ukuran',
'listfiles_description' => 'Deskripsi',
'listfiles_count'       => 'Versi',

# File description page
'file-anchor-link'          => 'Berkas',
'filehist'                  => 'Riwayat berkas',
'filehist-help'             => 'Klik pada tanggal/waktu untuk melihat berkas ini pada saat tersebut.',
'filehist-deleteall'        => 'hapus semua',
'filehist-deleteone'        => 'hapus',
'filehist-revert'           => 'kembalikan',
'filehist-current'          => 'terkini',
'filehist-datetime'         => 'Tanggal/Waktu',
'filehist-thumb'            => 'Miniatur',
'filehist-thumbtext'        => 'Miniatur untuk versi per $1',
'filehist-nothumb'          => 'Miniatur tidak tersedia',
'filehist-user'             => 'Pengguna',
'filehist-dimensions'       => 'Dimensi',
'filehist-filesize'         => 'Besar berkas',
'filehist-comment'          => 'Komentar',
'filehist-missing'          => 'Berkas tidak ditemukan',
'imagelinks'                => 'Pranala berkas',
'linkstoimage'              => '{{PLURAL:$1||}}$1 halaman berikut memiliki pranala ke berkas ini:',
'linkstoimage-more'         => 'Lebih dari $1 {{PLURAL:$1|halaman|halaman}} memiliki pranala ke berkas ini.
Daftar berikut menampilkan {{PLURAL:$1|halaman dengan pranala langsung|$1 halaman dengan pranala langsung}} ke berkas ini.
Juga tersedia [[Special:WhatLinksHere/$2|daftar selengkapnya]].',
'nolinkstoimage'            => 'Tidak ada halaman yang memiliki pranala ke berkas ini.',
'morelinkstoimage'          => 'Lihat [[Special:WhatLinksHere/$1|pranala lainnya]] ke berkas ini.',
'redirectstofile'           => 'Berkas berikut {{PLURAL:$1|dialihkan|$1 dialihkan}} ke berkas ini:',
'duplicatesoffile'          => '{{PLURAL:$1|Ada satu berkas yang|Sebanyak $1 berkas berikut}} merupakan duplikat dari berkas ini ([[Special:FileDuplicateSearch/$2|rincian lebih lanjut]]):',
'sharedupload'              => 'Berkas ini berasal dari $1 dan mungkin digunakan oleh proyek-proyek lain.',
'sharedupload-desc-there'   => 'Berkas ini berasal dari $1 dan mungkin digunakan oleh proyek-proyek lain.
Silakan lihat [$2 halaman deskripsi berkas] untuk informasi lebih lanjut.',
'sharedupload-desc-here'    => 'Berkas ini berasal dari $1 dan mungkin digunakan oleh proyek-proyek lain.
Deskripsi dari [$2 halaman deskripsinya] ditunjukkan di bawah ini.',
'filepage-nofile'           => 'Tidak ada berkas dengan nama ini.',
'filepage-nofile-link'      => 'Tidak ada berkas dengan nama ini, tetapi Anda dapat [$1 mengunggahnya].',
'uploadnewversion-linktext' => 'Muatkan versi yang lebih baru dari berkas ini',
'shared-repo-from'          => 'dari $1',
'shared-repo'               => 'suatu repositori bersama',

# File reversion
'filerevert'                => 'Kembalikan $1',
'filerevert-legend'         => 'Kembalikan berkas',
'filerevert-intro'          => "Anda mengembalikan '''[[Media:$1|$1]]''' ke versi [$4 pada $3, $2].",
'filerevert-comment'        => 'Alasan:',
'filerevert-defaultcomment' => 'Dikembalikan ke versi pada $2, $1',
'filerevert-submit'         => 'Kembalikan',
'filerevert-success'        => "'''[[Media:$1|$1]]''' telah dikembalikan ke versi [$4 pada $3, $2]",
'filerevert-badversion'     => 'Tidak ada versi lokal terdahulu dari berkas ini dengan stempel waktu yang dimaksud.',

# File deletion
'filedelete'                  => 'Menghapus $1',
'filedelete-legend'           => 'Menghapus berkas',
'filedelete-intro'            => "Anda akan menghapus berkas '''[[Media:$1|$1]]''' berikut semua riwayatnya.",
'filedelete-intro-old'        => '<span class="plainlinks">Anda menghapus versi \'\'\'[[Media:$1|$1]]\'\'\' hingga [$4 $3, $2].</span>',
'filedelete-comment'          => 'Alasan:',
'filedelete-submit'           => 'Hapus',
'filedelete-success'          => "'''$1''' telah dihapus.",
'filedelete-success-old'      => "Berkas '''[[Media:$1|$1]]''' versi $3, $2 telah dihapus.",
'filedelete-nofile'           => "'''$1''' tak ditemukan.",
'filedelete-nofile-old'       => "Tak ditemukan arsip versi dari '''$1''' dengan atribut yang diberikan.",
'filedelete-otherreason'      => 'Alasan lain/tambahan:',
'filedelete-reason-otherlist' => 'Alasan lain',
'filedelete-reason-dropdown'  => '*Alasan penghapusan
** Pelanggaran hak cipta
** Berkas duplikat',
'filedelete-edit-reasonlist'  => 'Alasan penghapusan suntingan',
'filedelete-maintenance'      => 'Penghapusan dan pengembalian berkas sementara dinonaktifkan selama perawatan.',

# MIME search
'mimesearch'         => 'Pencarian MIME',
'mimesearch-summary' => 'Halaman ini menyediakan fasilitas menyaring berkas berdasarkan tipe MIME nya. Masukkan: contenttype/subtype, misalnya <tt>image/jpeg</tt>.',
'mimetype'           => 'Tipe MIME:',
'download'           => 'unduh',

# Unwatched pages
'unwatchedpages' => 'Halaman yang tak dipantau',

# List redirects
'listredirects' => 'Daftar pengalihan',

# Unused templates
'unusedtemplates'     => 'Templat yang tak digunakan',
'unusedtemplatestext' => 'Daftar berikut adalah semua halaman pada ruang nama {{ns:template}} yang tidak dipakai di halaman manapun.
Cek dahulu pranala lain ke templat tersebut sebelum menghapusnya.',
'unusedtemplateswlh'  => 'pranala lain',

# Random page
'randompage'         => 'Halaman sembarang',
'randompage-nopages' => 'Tidak ada halaman pada {{PLURAL:$2||}}ruang nama berikut: $1.',

# Random redirect
'randomredirect'         => 'Pengalihan sembarang',
'randomredirect-nopages' => 'Tak terdapat pengalihan pada ruang nama "$1".',

# Statistics
'statistics'                   => 'Statistik',
'statistics-header-pages'      => 'Statistik halaman',
'statistics-header-edits'      => 'Statistik suntingan',
'statistics-header-views'      => 'Statistik penampilan',
'statistics-header-users'      => 'Statistik pengguna',
'statistics-header-hooks'      => 'Statistik lainnya',
'statistics-articles'          => 'Halaman konten',
'statistics-pages'             => 'Jumlah halaman',
'statistics-pages-desc'        => 'Semua halaman di wiki ini, termasuk halaman pembicaraan, pengalihan, dan lain-lain.',
'statistics-files'             => 'Berkas yang dimuatkan',
'statistics-edits'             => 'Jumlah suntingan sejak {{SITENAME}} dimulai',
'statistics-edits-average'     => 'Rata-rata suntingan per halaman',
'statistics-views-total'       => 'Jumlah penampilan halaman',
'statistics-views-total-desc'  => 'Tampilan ke halaman yang tidak ada dan halaman khusus yang tidak dimasukkan',
'statistics-views-peredit'     => 'Jumlah penampilan per suntingan',
'statistics-users'             => 'Jumlah [[Special:ListUsers|pengguna terdaftar]]',
'statistics-users-active'      => 'Jumlah pengguna aktif',
'statistics-users-active-desc' => 'Pengguna yang telah melakukan suatu aktivitas dalam {{PLURAL:$1|sehari|$1 hari}} terakhir.',
'statistics-mostpopular'       => 'Halaman yang paling banyak ditampilkan',

'disambiguations'      => 'Disambiguasi',
'disambiguationspage'  => 'Template:Disambig',
'disambiguations-text' => "Halaman-halaman berikut memiliki pranala ke suatu '''halaman disambiguasi'''.
Halaman-halaman tersebut seharusnya berpaut ke topik-topik yang sesuai.<br />
Suatu halaman dianggap sebagai halaman disambiguasi apabila halaman tersebut menggunakan templat yang terhubung ke [[MediaWiki:Disambiguationspage]].",

'doubleredirects'            => 'Pengalihan ganda',
'doubleredirectstext'        => 'Halaman ini memuat daftar halaman yang dialihkan ke halaman pengalihan yang lain.
Setiap baris memuat pranala ke pengalihan pertama dan pengalihan kedua serta target dari pengalihan kedua yang umumnya adalah halaman yang "sebenarnya". Halaman peralihan pertama seharusnya dialihkan ke halaman yang bukan merupakan halaman peralihan.
Nama yang telah <del>dicoret</del> berarti telah dibetulkan.',
'double-redirect-fixed-move' => '[[$1]] telah dipindahkan menjadi halaman peralihan ke [[$2]]',
'double-redirect-fixer'      => 'Revisi pengalihan',

'brokenredirects'        => 'Pengalihan rusak',
'brokenredirectstext'    => 'Pengalihan-pengalihan berikut merujuk pada halaman yang tidak ada:',
'brokenredirects-edit'   => 'sunting',
'brokenredirects-delete' => 'hapus',

'withoutinterwiki'         => 'Halaman tanpa interwiki',
'withoutinterwiki-summary' => 'Halaman-halaman berikut tidak memiliki pranala ke versi dalam bahasa lain:',
'withoutinterwiki-legend'  => 'Prefiks',
'withoutinterwiki-submit'  => 'Tampilkan',

'fewestrevisions' => 'Halaman dengan perubahan tersedikit',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bita|bita}}',
'ncategories'             => '$1 {{PLURAL:$1|kategori|kategori}}',
'nlinks'                  => '$1 {{PLURAL:$1|pranala|pranala}}',
'nmembers'                => '$1 {{PLURAL:$1|isi|isi}}',
'nrevisions'              => '$1 {{PLURAL:$1|revisi|revisi}}',
'nviews'                  => 'dilihat $1 {{PLURAL:$1|kali|kali}}',
'nimagelinks'             => 'Digunakan pada $1 {{PLURAL:$1|halaman|halaman}}',
'ntransclusions'          => 'digunakan pada $1 {{PLURAL:$1|halaman|halaman}}',
'specialpage-empty'       => 'Tak ada yang perlu dilaporkan.',
'lonelypages'             => 'Halaman tanpa pranala balik',
'lonelypagestext'         => 'Halaman-halaman berikut tidak memiliki pranala dari atau ditransklusikan ke halaman manapun di {{SITENAME}}.',
'uncategorizedpages'      => 'Halaman yang tak terkategori',
'uncategorizedcategories' => 'Kategori yang tak terkategori',
'uncategorizedimages'     => 'Berkas yang tak terkategori',
'uncategorizedtemplates'  => 'Templat yang tak terkategori',
'unusedcategories'        => 'Kategori yang tak digunakan',
'unusedimages'            => 'Berkas yang tak digunakan',
'popularpages'            => 'Halaman populer',
'wantedcategories'        => 'Kategori yang diinginkan',
'wantedpages'             => 'Halaman yang diinginkan',
'wantedpages-badtitle'    => 'Judul tak valid dalam himpunan hasil: $1',
'wantedfiles'             => 'Berkas yang diinginkan',
'wantedtemplates'         => 'Templat yang diinginkan',
'mostlinked'              => 'Halaman yang tersering dituju',
'mostlinkedcategories'    => 'Kategori yang tersering digunakan',
'mostlinkedtemplates'     => 'Templat yang tersering digunakan',
'mostcategories'          => 'Halaman dengan kategori terbanyak',
'mostimages'              => 'Berkas yang tersering digunakan',
'mostrevisions'           => 'Halaman dengan perubahan terbanyak',
'prefixindex'             => 'Semua halaman dengan awalan',
'shortpages'              => 'Halaman pendek',
'longpages'               => 'Halaman panjang',
'deadendpages'            => 'Halaman buntu',
'deadendpagestext'        => 'Halaman-halaman berikut tidak memiliki pranala ke halaman manapun di wiki ini.',
'protectedpages'          => 'Halaman yang dilindungi',
'protectedpages-indef'    => 'Hanya untuk perlindungan dengan jangka waktu tak terbatas',
'protectedpages-cascade'  => 'Hanya perlindungan runtun',
'protectedpagestext'      => 'Halaman-halaman berikut dilindungi dari pemindahan atau penyuntingan.',
'protectedpagesempty'     => 'Saat ini tidak ada halaman yang sedang dilindungi dengan parameter-parameter tersebut.',
'protectedtitles'         => 'Judul yang dilindungi',
'protectedtitlestext'     => 'Judul berikut ini dilindungi dari pembuatan',
'protectedtitlesempty'    => 'Tidak ada judul yang dilindungi.',
'listusers'               => 'Daftar pengguna',
'listusers-editsonly'     => 'Tampilkan hanya pengguna yang memiliki kontribusi',
'listusers-creationsort'  => 'Urutkan menurut tanggal pembuatan',
'usereditcount'           => '$1 {{PLURAL:$1|suntingan|suntingan}}',
'usercreated'             => 'sejak $2, $1',
'newpages'                => 'Halaman baru',
'newpages-username'       => 'Nama pengguna:',
'ancientpages'            => 'Halaman terlama',
'move'                    => 'Pindahkan',
'movethispage'            => 'Pindahkan halaman ini',
'unusedimagestext'        => 'Berkas berikut ada tapi tidak disertakan di halaman manapun.
Harap perhatikan bahwa situs web lain mungkin memiliki pranala ke suatu berkas dengan URL langsung, dan karenanya masih terdaftar di sini meskiput sudah tidak digunakan aktif.',
'unusedcategoriestext'    => 'Kategori berikut ada, walaupun tidak ada halaman atau kategori lain yang menggunakannya.',
'notargettitle'           => 'Tidak ada sasaran',
'notargettext'            => 'Anda tidak menentukan halaman atau pengguna tujuan fungsi ini.',
'nopagetitle'             => 'Halaman tujuan tidak ditemukan',
'nopagetext'              => 'Halaman yang Anda tuju tidak ditemukan.',
'pager-newer-n'           => '{{PLURAL:$1|1 lebih baru|$1 lebih baru}}',
'pager-older-n'           => '{{PLURAL:$1|1 lebih lama|$1 lebih lama}}',
'suppress'                => 'Pengawas',

# Book sources
'booksources'               => 'Sumber buku',
'booksources-search-legend' => 'Cari di sumber buku',
'booksources-go'            => 'Tuju ke',
'booksources-text'          => 'Di bawah ini adalah daftar pranala ke situs lain yang menjual buku baru dan bekas, dan mungkin juga mempunyai informasi lebih lanjut mengenai buku yang sedang Anda cari:',
'booksources-invalid-isbn'  => 'ISBN yang diberikan tampaknya tidak valid; periksa kesalahan penyalinan dari sumber asli.',

# Special:Log
'specialloguserlabel'  => 'Pengguna:',
'speciallogtitlelabel' => 'Judul:',
'log'                  => 'Log',
'all-logs-page'        => 'Semua log publik',
'alllogstext'          => 'Gabungan tampilan semua log yang tersedia di {{SITENAME}}.
Anda dapat melakukan pembatasan tampilan dengan memilih jenis log, nama pengguna (sensitif kapital), atau judul halaman (juga sensitif kapital).',
'logempty'             => 'Tidak ditemukan entri log yang sesuai.',
'log-title-wildcard'   => 'Cari judul yang diawali dengan teks tersebut',

# Special:AllPages
'allpages'          => 'Semua halaman',
'alphaindexline'    => '$1 hingga $2',
'nextpage'          => 'Halaman selanjutnya ($1)',
'prevpage'          => 'Halaman sebelumnya ($1)',
'allpagesfrom'      => 'Tampilkan halaman mulai dari:',
'allpagesto'        => 'Tampilkan halaman hingga:',
'allarticles'       => 'Semua halaman',
'allinnamespace'    => 'Daftar halaman (ruang nama $1)',
'allnotinnamespace' => 'Daftar halaman (bukan ruang nama $1)',
'allpagesprev'      => 'Sebelumnya',
'allpagesnext'      => 'Selanjutnya',
'allpagessubmit'    => 'Cari',
'allpagesprefix'    => 'Tampilkan halaman dengan awalan:',
'allpagesbadtitle'  => 'Judul halaman yang diberikan tidak sah atau memiliki awalan antar-bahasa atau antar-wiki. Judul tersebut mungkin juga mengandung satu atau lebih aksara yang tidak dapat digunakan dalam judul.',
'allpages-bad-ns'   => '{{SITENAME}} tidak memiliki ruang nama "$1".',

# Special:Categories
'categories'                    => 'Kategori',
'categoriespagetext'            => '{{PLURAL:$1|Kategori berikut|Kategori-kategori berikut}} memiliki isi halaman atau media.
[[Special:UnusedCategories|Kategori yang tak digunakan]] tidak ditampilkan di sini.
Lihat pula [[Special:WantedCategories|kategori yang diinginkan]].',
'categoriesfrom'                => 'Tampilkan kategori-kategori dimulai dengan:',
'special-categories-sort-count' => 'urutkan menurut jumlah',
'special-categories-sort-abc'   => 'urutkan menurut abjad',

# Special:DeletedContributions
'deletedcontributions'             => 'Kontribusi yang dihapus',
'deletedcontributions-title'       => 'Kontribusi yang dihapus',
'sp-deletedcontributions-contribs' => 'kontribusi',

# Special:LinkSearch
'linksearch'       => 'Pranala luar',
'linksearch-pat'   => 'Pola pencarian:',
'linksearch-ns'    => 'Ruang nama:',
'linksearch-ok'    => 'Cari',
'linksearch-text'  => "Bentuk pencarian ''wildcards'' seperti \"*.wikipedia.org\" dapat digunakan.<br />Protokol yang didukung: <tt>\$1</tt>",
'linksearch-line'  => '$1 memiliki pranala dari $2',
'linksearch-error' => "''Wildcards'' hanya dapat digunakan di bagian awal dari nama host.",

# Special:ListUsers
'listusersfrom'      => 'Tampilkan pengguna mulai dari:',
'listusers-submit'   => 'Tampilkan',
'listusers-noresult' => 'Pengguna tidak ditemukan.',
'listusers-blocked'  => '(diblokir)',

# Special:ActiveUsers
'activeusers'            => 'Daftar pengguna aktif',
'activeusers-intro'      => 'Berikut adalah daftar pengguna yang memiliki suatu bentuk aktivitas selama paling tidak $1 {{PLURAL:$1|hari|hari}} terakhir.',
'activeusers-count'      => '$1 {{PLURAL:$1||}}suntingan selama {{PLURAL:$3||}}$3 hari terakhir',
'activeusers-from'       => 'Tampilkan pengguna mulai dari:',
'activeusers-hidebots'   => 'Sembunyikan bot',
'activeusers-hidesysops' => 'Sembunyikan pengurus',
'activeusers-noresult'   => 'Pengguna tidak ditemukan.',

# Special:Log/newusers
'newuserlogpage'              => 'Log pengguna baru',
'newuserlogpagetext'          => 'Di bawah ini adalah log pendaftaran pengguna baru',
'newuserlog-byemail'          => 'kata sandi dikirim melalui surel',
'newuserlog-create-entry'     => 'mendaftar sebagai pengguna',
'newuserlog-create2-entry'    => 'membuat akun baru $1',
'newuserlog-autocreate-entry' => 'akun pengguna dibuat secara otomatis',

# Special:ListGroupRights
'listgrouprights'                      => 'Hak kelompok pengguna',
'listgrouprights-summary'              => 'Berikut adalah daftar kelompok pengguna yang terdapat di wiki ini, dengan daftar hak akses mereka masing-masing. Informasi lebih lanjut mengenai hak masing-masing dapat ditemukan di [[{{MediaWiki:Listgrouprights-helppage}}|halaman bantuan hak pengguna]].',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Hak yang berlaku</span>
* <span class="listgrouprights-revoked">Hak yang dicabut</span>',
'listgrouprights-group'                => 'Kelompok',
'listgrouprights-rights'               => 'Hak',
'listgrouprights-helppage'             => 'Help:Hak akses',
'listgrouprights-members'              => '(daftar anggota)',
'listgrouprights-addgroup'             => 'Menambahkan {{PLURAL:$2|kelompok|kelompok}}: $1',
'listgrouprights-removegroup'          => 'Menghapus {{PLURAL:$2|kelompok|kelompok}}: $1',
'listgrouprights-addgroup-all'         => 'Menambahkan semua kelompok',
'listgrouprights-removegroup-all'      => 'Menghapus semua kelompok',
'listgrouprights-addgroup-self'        => 'Dapat menambahkan {{PLURAL:$2|grup| grup}} ke akun sendiri: $1',
'listgrouprights-removegroup-self'     => 'Menghapus {{PLURAL:$2|kelompok|kelompok}} dari akun sendiri: $1',
'listgrouprights-addgroup-self-all'    => 'Dapat menambahkan semua grup ke akun sendiri',
'listgrouprights-removegroup-self-all' => 'Menghapus semua kelompok dari akun sendiri',

# E-mail user
'mailnologin'          => 'Tidak ada alamat surel',
'mailnologintext'      => 'Anda harus [[Special:UserLogin|masuk log]] dan mempunyai alamat surel yang sah di dalam [[Special:Preferences|preferensi]] untuk mengirimkan surel kepada pengguna lain.',
'emailuser'            => 'Surel pengguna',
'emailpage'            => 'Kirim surel ke pengguna ini',
'emailpagetext'        => 'Anda dapat menggunakan formulir di bawah ini untuk mengirimkan surel ke pengguna ini.
Alamat surel yang Anda masukkan di [[Special:Preferences|preferensi akun Anda]] akan muncul sebagai alamat "Dari" dalam surel tersebut, sehingga penerima dapat langsung membalas kepada Anda.',
'usermailererror'      => 'Kesalahan objek surat:',
'defemailsubject'      => 'Surel {{SITENAME}}',
'usermaildisabled'     => 'Surel pengguna dinonaktifkan',
'usermaildisabledtext' => 'Anda tidak dapat mengirim surel pada pengguna lain di wiki ini',
'noemailtitle'         => 'Tidak ada alamat surel',
'noemailtext'          => 'Pengguna ini tidak memberikan suatu alamat surel yang valid.',
'nowikiemailtitle'     => 'Surel tak diizinkan',
'nowikiemailtext'      => 'Pengguna ini telah memilih untuk tidak menerima surel dari pengguna lain.',
'email-legend'         => 'Kirim surel ke pengguna {{SITENAME}} lainnya',
'emailfrom'            => 'Dari:',
'emailto'              => 'Untuk:',
'emailsubject'         => 'Perihal:',
'emailmessage'         => 'Pesan:',
'emailsend'            => 'Kirim',
'emailccme'            => 'Kirimkan saya salinan pesan saya.',
'emailccsubject'       => 'Salinan pesan Anda untuk $1: $2',
'emailsent'            => 'Surel terkirim',
'emailsenttext'        => 'Surel Anda telah dikirimkan.',
'emailuserfooter'      => 'Surel ini dikirimkan oleh $1 kepada $2 menggunakan fungsi "Suratepengguna" di {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Tinggalkan pesan sistem.',
'usermessage-editor'  => 'Penyampai pesan sistem',

# Watchlist
'watchlist'            => 'Daftar pantauan',
'mywatchlist'          => 'Pantauan saya',
'watchlistfor2'        => 'Untuk $1 $2',
'nowatchlist'          => 'Daftar pantauan Anda kosong.',
'watchlistanontext'    => 'Silakan $1 untuk melihat atau menyunting daftar pantauan Anda.',
'watchnologin'         => 'Belum masuk log',
'watchnologintext'     => 'Anda harus [[Special:UserLogin|masuk log]] untuk mengubah daftar pantauan Anda.',
'addedwatch'           => 'Telah ditambahkan ke daftar pantauan',
'addedwatchtext'       => "Halaman \"[[:\$1]]\" telah ditambahkan ke [[Special:Watchlist|daftar pantauan]] Anda.
Perubahan-perubahan berikutnya pada halaman tersebut dan halaman pembicaraan terkaitnya akan tercantum di sini, dan halaman itu akan ditampilkan '''tebal''' pada [[Special:RecentChanges|daftar perubahan terbaru]] agar lebih mudah terlihat.",
'removedwatch'         => 'Telah dihapus dari daftar pantauan',
'removedwatchtext'     => 'Halaman "[[:$1]]" telah dihapus dari [[Special:Watchlist|daftar pantauan]] Anda.',
'watch'                => 'Pantau',
'watchthispage'        => 'Pantau halaman ini',
'unwatch'              => 'Batal pantau',
'unwatchthispage'      => 'Batal pantau halaman ini',
'notanarticle'         => 'Bukan sebuah halaman isi',
'notvisiblerev'        => 'Revisi telah dihapus',
'watchnochange'        => 'Tak ada halaman pantauan Anda yang telah berubah dalam jangka waktu yang dipilih.',
'watchlist-details'    => 'Terdapat {{PLURAL:$1|$1 halaman|$1 halaman}} di daftar pantauan Anda, tidak termasuk halaman pembicaraan.',
'wlheader-enotif'      => '* Notifikasi surel diaktifkan.',
'wlheader-showupdated' => "* Halaman-halaman yang telah berubah sejak kunjungan terakhir Anda ditampilkan dengan '''huruf tebal'''",
'watchmethod-recent'   => 'periksa daftar perubahan terbaru terhadap halaman yang dipantau',
'watchmethod-list'     => 'periksa halaman yang dipantau terhadap perubahan terbaru',
'watchlistcontains'    => 'Daftar pantauan Anda berisi $1 {{PLURAL:$1|halaman|halaman}}.',
'iteminvalidname'      => "Ada masalah dengan '$1', namanya tidak sah...",
'wlnote'               => "Di bawah ini adalah $1 {{PLURAL:$1|perubahan|perubahan}} terakhir dalam '''$2''' jam terakhir.",
'wlshowlast'           => 'Tampilkan $1 jam $2 hari terakhir $3',
'watchlist-options'    => 'Opsi daftar pantauan',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Memantau...',
'unwatching' => 'Menghilangkan pemantauan...',

'enotif_mailer'                => 'Pengirim Notifikasi {{SITENAME}}',
'enotif_reset'                 => 'Tandai semua halaman sebagai telah dikunjungi',
'enotif_newpagetext'           => 'Ini adalah halaman baru.',
'enotif_impersonal_salutation' => 'Pengguna {{SITENAME}}',
'changed'                      => 'diubah',
'created'                      => 'dibuat',
'enotif_subject'               => 'Halaman $PAGETITLE di {{SITENAME}} telah $CHANGEDORCREATED oleh $PAGEEDITOR',
'enotif_lastvisited'           => 'Lihat $1 untuk semua perubahan sejak kunjungan terakhir Anda.',
'enotif_lastdiff'              => 'Kunjungi $1 untuk melihat perubahan ini.',
'enotif_anon_editor'           => 'pengguna anonim $1',
'enotif_body'                  => 'Halo $WATCHINGUSERNAME,


Halaman $PAGETITLE di {{SITENAME}} telah $CHANGEDORCREATED pada $PAGEEDITDATE oleh $PAGEEDITOR, lihat $PAGETITLE_URL untuk revisi terakhir.

$NEWPAGE

Ringkasan suntingan: $PAGESUMMARY $PAGEMINOREDIT

Hubungi penyunting:
surel: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Tidak akan ada pemberitahuan lainnya dalam rangka perubahan lebih lanjut kecuali anda mengunjungi halaman ini.
Anda juga dapat menset ulang tanda pemberitahuan untuk semua halaman pantauan anda pada daftar pantauan anda.

             Sistem pemberitahuan anda di {{SITENAME}}

--
Untuk mengubah preferensi daftar pantauan anda, kunjungi
{{fullurl:{{#special:Watchlist}}/edit}}

Untuk menghapus halaman dari daftar pantauan anda, kunjungi
$UNWATCHURL

Umpan balik dan bantuan lebih lanjut:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Hapus halaman',
'confirm'                => 'Konfirmasi',
'excontent'              => "isi sebelumnya: '$1'",
'excontentauthor'        => "isinya hanya berupa: '$1' (dan satu-satunya penyumbang adalah '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "isi sebelum dikosongkan: '$1'",
'exblank'                => 'halaman kosong',
'delete-confirm'         => 'Hapus "$1"',
'delete-legend'          => 'Hapus',
'historywarning'         => "'''Peringatan:''' Halaman yang akan Anda hapus mempunyai sejarah dengan $1 {{PLURAL:$1|revisi|revisi}}:",
'confirmdeletetext'      => 'Anda akan menghapus halaman atau berkas ini secara permanen berikut semua sejarahnya dari basis data. Pastikan bahwa Anda memang ingin melakukannya, mengetahui segala akibatnya, dan apa yang Anda lakukan ini adalah sejalan dengan [[{{MediaWiki:Policy-url}}|kebijakan {{SITENAME}}]].',
'actioncomplete'         => 'Proses selesai',
'actionfailed'           => 'Eksekusi gagal',
'deletedtext'            => '"<nowiki>$1</nowiki>" telah dihapus. Lihat $2 untuk log terkini halaman yang telah dihapus.',
'deletedarticle'         => 'menghapus "[[$1]]"',
'suppressedarticle'      => '"[[$1]]" disembunyikan',
'dellogpage'             => 'Log penghapusan',
'dellogpagetext'         => 'Di bawah ini adalah log penghapusan halaman. Semua waktu yang ditunjukkan adalah waktu server.',
'deletionlog'            => 'log penghapusan',
'reverted'               => 'Dikembalikan ke revisi sebelumnya',
'deletecomment'          => 'Alasan:',
'deleteotherreason'      => 'Alasan lain/tambahan:',
'deletereasonotherlist'  => 'Alasan lain',
'deletereason-dropdown'  => '*Alasan penghapusan
** Permintaan pengguna
** Pelanggaran hak cipta
** Vandalisme',
'delete-edit-reasonlist' => 'Alasan penghapusan suntingan',
'delete-toobig'          => 'Halaman ini memiliki sejarah penyuntingan yang panjang, melebihi {{PLURAL:$1|revisi|revisi}}.
Penghapusan halaman dengan sejarah penyuntingan yang panjang tidak diperbolehkan untuk mencegah kerusakan di {{SITENAME}}.',
'delete-warning-toobig'  => 'Halaman ini memiliki sejarah penyuntingan yang panjang, melebihi {{PLURAL:$1|revisi|revisi}}.
Menghapus halaman ini dapat menyebabkan masalah dalam operasional basis data {{SITENAME}}.',

# Rollback
'rollback'          => 'Kembalikan suntingan',
'rollback_short'    => 'Kembalikan',
'rollbacklink'      => 'kembalikan',
'rollbackfailed'    => 'Pengembalian gagal dilakukan',
'cantrollback'      => 'Tidak dapat mengembalikan suntingan;
kontributor terakhir adalah satu-satunya penulis halaman ini.',
'alreadyrolled'     => 'Tidak dapat melakukan pengembalian ke revisi terakhir [[:$1]] oleh [[User:$2|$2]] ([[User talk:$2|bicara]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
pengguna lain telah menyunting atau melakukan pengembalian terhadap halaman ini.

Suntingan terakhir dilakukan oleh [[User:$3|$3]] ([[User talk:$3|bicara]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Komentar penyuntingan adalah: \"''\$1''\".",
'revertpage'        => '←Suntingan [[Special:Contributions/$2|$2]] ([[User talk:$2|bicara]]) dikembalikan ke versi terakhir oleh [[User:$1|$1]]',
'revertpage-nouser' => 'Pengembalian suntingan oleh (pengguna dihapus) ke suntingan terakhir oleh [[User:$1|$1]]',
'rollback-success'  => 'Pengembalian suntingan oleh $1; dikembalikan ke versi terakhir oleh $2.',

# Edit tokens
'sessionfailure-title' => 'Kegagalan sesi',
'sessionfailure'       => 'Sepertinya ada masalah dengan sesi log Anda; log Anda telah dibatalkan untuk mencegah pembajakan. Silakan tekan tombol "kembali" dan muat kembali halaman sebelum Anda masuk, lalu coba lagi.',

# Protect
'protectlogpage'              => 'Log perlindungan',
'protectlogtext'              => 'Di bawah ini adalah log perlindungan halaman dan pembatalannya.
Lihat [[Special:ProtectedPages|daftar halaman yang dilindungi]] untuk daftar terkini.',
'protectedarticle'            => 'melindungi "[[$1]]"',
'modifiedarticleprotection'   => 'mengubah tingkat perlindungan "[[$1]]"',
'unprotectedarticle'          => 'menghilangkan perlindungan "[[$1]]"',
'movedarticleprotection'      => 'memindahkan pengaturan proteksi dari "[[$2]]" ke "[[$1]]"',
'protect-title'               => 'Melindungi "$1"',
'prot_1movedto2'              => '[[$1]] dipindahkan ke [[$2]]',
'protect-legend'              => 'Konfirmasi perlindungan',
'protectcomment'              => 'Alasan:',
'protectexpiry'               => 'Kedaluwarsa:',
'protect_expiry_invalid'      => 'Waktu kedaluwarsa tidak sah.',
'protect_expiry_old'          => 'Waktu kedaluwarsa adalah pada masa lampau.',
'protect-unchain-permissions' => 'Aktifkan opsi perlindungan lanjutan',
'protect-text'                => "Anda dapat melihat atau mengganti tingkatan perlindungan untuk halaman '''<nowiki>$1</nowiki>''' di sini.",
'protect-locked-blocked'      => "Anda tak dapat mengganti tingkat perlindungan selagi diblokir. Berikut adalah konfigurasi saat ini untuk halaman '''$1''':",
'protect-locked-dblock'       => "Tingkat perlindungan tak dapat diganti karena aktifnya penguncian basis data. Berikut adalah konfigurasi saat ini untuk halaman '''$1''':",
'protect-locked-access'       => "Akun Anda tidak dapat memiliki hak untuk mengganti tingkat perlindungan halaman. Berikut adalah konfigurasi saat ini untuk halaman '''$1''':",
'protect-cascadeon'           => 'Halaman ini sedang dilindungi karena disertakan dalam {{PLURAL:$1|halaman|halaman-halaman}} berikut yang telah dilindungi dengan pilihan perlindungan runtun diaktifkan. Anda dapat mengganti tingkat perlindungan untuk halaman ini, tapi hal tersebut tidak akan mempengaruhi perlindungan runtun.',
'protect-default'             => 'Izinkan semua pengguna',
'protect-fallback'            => 'Memerlukan hak akses "$1"',
'protect-level-autoconfirmed' => 'Blokir pengguna baru dan tak terdaftar',
'protect-level-sysop'         => 'Hanya pengurus',
'protect-summary-cascade'     => 'runtun',
'protect-expiring'            => 'kedaluwarsa $1 (UTC)',
'protect-expiry-indefinite'   => 'selamanya',
'protect-cascade'             => 'Lindungi semua halaman yang termasuk dalam halaman ini (perlindungan runtun)',
'protect-cantedit'            => 'Anda tidak dapat mengubah tingkatan perlindungan halaman ini karena Anda tidak memiliki hak untuk itu.',
'protect-othertime'           => 'Waktu lain:',
'protect-othertime-op'        => 'waktu lain',
'protect-existing-expiry'     => 'Waktu kedaluwarsa saat ini: $2 $3',
'protect-otherreason'         => 'Alasan lain/tambahan:',
'protect-otherreason-op'      => 'Alasan lain',
'protect-dropdown'            => '*Alasan umum perlindungan
** Vandalisme berulang
** Spam berulang
** Perang suntingan
** Halaman dengan lalu-lintas tinggi',
'protect-edit-reasonlist'     => 'Sunting alasan perlindungan',
'protect-expiry-options'      => '1 jam:1 hour,1 hari:1 day,1 minggu:1 week,2 minggu:2 weeks,1 bulan:1 month,3 bulan:3 months,6 bulan:6 months,1 tahun:1 year,selamanya:infinite',
'restriction-type'            => 'Perlindungan:',
'restriction-level'           => 'Tingkatan:',
'minimum-size'                => 'Ukuran minimum',
'maximum-size'                => 'Ukuran maksimum',
'pagesize'                    => '(bita)',

# Restrictions (nouns)
'restriction-edit'   => 'Sunting',
'restriction-move'   => 'Pindahkan',
'restriction-create' => 'Buat',
'restriction-upload' => 'Unggah',

# Restriction levels
'restriction-level-sysop'         => 'perlindungan penuh',
'restriction-level-autoconfirmed' => 'perlindungan semi',
'restriction-level-all'           => 'semua tingkatan',

# Undelete
'undelete'                     => 'Lihat halaman yang telah dihapus',
'undeletepage'                 => 'Pembatalan penghapusan',
'undeletepagetitle'            => "'''Berikut daftar revisi yang dihapus dari [[:$1]]'''.",
'viewdeletedpage'              => 'Lihat halaman yang telah dihapus',
'undeletepagetext'             => '{{PLURAL:$1|Halaman berikut|Sejumlah $1 halaman}} telah dihapus tapi masih ada di dalam arsip dan dapat dikembalikan. Arsip tersebut mungkin akan dibersihkan secara berkala.',
'undelete-fieldset-title'      => 'Mengembalikan revisi',
'undeleteextrahelp'            => "Untuk mengembalikan seluruh revisi-revisi terdahulu halaman, biarkan seluruh kotak cek tidak terpilih dan klik '''''Kembalikan'''''.
Untuk melakukan pengembalian selektif, cek kotak revisi yang diinginkan dan klik '''''Kembalikan'''''.
Menekan tombol '''''Reset''''' akan mengosongkan isian komentar dan semua kotak cek.",
'undeleterevisions'            => '$1 {{PLURAL:$1|revisi|revisi}} diarsipkan',
'undeletehistory'              => 'Jika Anda mengembalikan halaman tersebut, semua revisi juga akan dikembalikan ke dalam daftar versi terdahulu halaman.
Jika sebuah halaman baru dengan nama yang sama telah dibuat sejak penghapusan, revisi-revisi yang dikembalikan tersebut akan ditampilkan dalam daftar versi terdahulu.',
'undeleterevdel'               => 'Pembatalan penghapusan tidak akan dilakukan jika hal tersebut akan mengakibatkan revisi terkini halaman terhapus sebagian. Pada kondisi tersebut, Anda harus menghilangkan cek atau menghilangkan penyembunyian revisi yang dihapus terakhir. Revisi berkas yang tidak dapat Anda lihat tidak akan dipulihkan.',
'undeletehistorynoadmin'       => 'Halaman ini telah dihapus.
Alasan penghapusan diberikan pada ringkasan di bawah ini, berikut rincian pengguna yang telah melakukan penyuntingan pada halaman ini sebelum dihapus. Isi terakhir dari revisi yang telah dihapus ini hanya tersedia untuk pengurus.',
'undelete-revision'            => 'Revisi yang telah dihapus dari $1 (pada $5, $4) oleh $3:',
'undeleterevision-missing'     => 'Revisi salah atau tak ditemukan. Anda mungkin mengikuti pranala yang salah, atau revisi tersebut telah dipulihkan atau dibuang dari arsip.',
'undelete-nodiff'              => 'Tidak ada revisi yang lebih lama.',
'undeletebtn'                  => 'Kembalikan',
'undeletelink'                 => 'lihat/kembalikan',
'undeleteviewlink'             => 'lihat',
'undeletereset'                => 'Reset',
'undeleteinvert'               => 'Balikkan pilihan',
'undeletecomment'              => 'Alasan:',
'undeletedarticle'             => '"$1" telah dikembalikan',
'undeletedrevisions'           => '$1 {{PLURAL:$1|revisi|revisi}} telah dikembalikan',
'undeletedrevisions-files'     => '$1 {{PLURAL:$1|revisi|revisi}} and $2 berkas dikembalikan',
'undeletedfiles'               => '$1 {{PLURAL:$1|berkas|berkas}} dikembalikan',
'cannotundelete'               => 'Pembatalan penghapusan gagal; mungkin ada orang lain yang telah terlebih dahulu melakukan pembatalan.',
'undeletedpage'                => "'''$1 berhasil dikembalikan'''

Lihat [[Special:Log/delete|log penghapusan]] untuk data penghapusan dan pengembalian.",
'undelete-header'              => 'Lihat [[Special:Log/delete|log penghapusan]] untuk daftar halaman yang baru dihapus.',
'undelete-search-box'          => 'Cari halaman yang dihapus',
'undelete-search-prefix'       => 'Tampilkan halaman dimulai dari:',
'undelete-search-submit'       => 'Cari',
'undelete-no-results'          => 'Tidak ditemukan halaman yang sesuai di arsip penghapusan.',
'undelete-filename-mismatch'   => 'Tidak dapat membatalkan penghapusan revisi berkas dengan tanda waktu $1: nama berkas tak sesuai',
'undelete-bad-store-key'       => 'Tidak dapat membatalkan penghapusan revisi berkas dengan tanda waktu $1: berkas hilang sebelum dihapus.',
'undelete-cleanup-error'       => 'Kesalahan sewaktu menghapus arsip berkas "$1" yang tak digunakan.',
'undelete-missing-filearchive' => 'Tidak dapat mengembalikan arsip berkas dengan ID $1 karena tak ada di basis data. Berkas tersebut mungkin telah dihapus..',
'undelete-error-short'         => 'Kesalahan membatalkan penghapusan: $1',
'undelete-error-long'          => 'Terjadi kesalahan sewaktu membatalkan penghapusan berkas:

$1',
'undelete-show-file-confirm'   => 'Apakah Anda yakin ingin melihat revisi yang telah dihapus dari berkas "<nowiki>$1</nowiki>" per $3, $2?',
'undelete-show-file-submit'    => 'Ya',

# Namespace form on various pages
'namespace'      => 'Ruang nama:',
'invert'         => 'Balikkan pilihan',
'blanknamespace' => '(Utama)',

# Contributions
'contributions'       => 'Kontribusi pengguna',
'contributions-title' => 'Kontribusi pengguna untuk $1',
'mycontris'           => 'Kontribusi saya',
'contribsub2'         => 'Untuk $1 ($2)',
'nocontribs'          => 'Tidak ada perubahan yang sesuai dengan kriteria tersebut.',
'uctop'               => ' (atas)',
'month'               => 'Sejak bulan (dan sebelumnya):',
'year'                => 'Sejak tahun (dan sebelumnya):',

'sp-contributions-newbies'             => 'Hanya pengguna-pengguna baru',
'sp-contributions-newbies-sub'         => 'Untuk pengguna baru',
'sp-contributions-newbies-title'       => 'Kontribusi pengguna baru',
'sp-contributions-blocklog'            => 'Log pemblokiran',
'sp-contributions-deleted'             => 'kontribusi pengguna yang dihapus',
'sp-contributions-logs'                => 'log',
'sp-contributions-talk'                => 'bicara',
'sp-contributions-userrights'          => 'pengelolaan hak pengguna',
'sp-contributions-blocked-notice'      => 'Pengguna ini sedang di blok. log pemblokiran terakhir ditampilkan berikut untuk referensi:',
'sp-contributions-blocked-notice-anon' => 'Alamat IP ini diblokir pada saat ini.
Catatan log pemblokiran terakhir tersedia di bawah ini sebagai rujukan:',
'sp-contributions-search'              => 'Cari kontribusi',
'sp-contributions-username'            => 'Alamat IP atau nama pengguna:',
'sp-contributions-toponly'             => 'Tampilkan hanya revisi teratas',
'sp-contributions-submit'              => 'Cari',

# What links here
'whatlinkshere'            => 'Pranala balik',
'whatlinkshere-title'      => 'Halaman yang memiliki pranala ke "$1"',
'whatlinkshere-page'       => 'Halaman:',
'linkshere'                => "Halaman-halaman berikut ini memiliki pranala ke '''[[:$1]]''':",
'nolinkshere'              => "Tidak ada halaman yang memiliki pranala ke '''[[:$1]]'''.",
'nolinkshere-ns'           => "Tidak ada halaman yang memiliki pranala ke '''[[:$1]]''' pada ruang nama yang dipilih.",
'isredirect'               => 'halaman pengalihan',
'istemplate'               => 'dengan templat',
'isimage'                  => 'pranala berkas',
'whatlinkshere-prev'       => '$1 {{PLURAL:$1|sebelumnya|sebelumnya}}',
'whatlinkshere-next'       => '$1 {{PLURAL:$1|selanjutnya|selanjutnya}}',
'whatlinkshere-links'      => '← pranala',
'whatlinkshere-hideredirs' => '$1 pengalihan',
'whatlinkshere-hidetrans'  => '$1 transklusi',
'whatlinkshere-hidelinks'  => '$1 pranala',
'whatlinkshere-hideimages' => '$1 pranala berkas',
'whatlinkshere-filters'    => 'Penyaring',

# Block/unblock
'blockip'                         => 'Blokir pengguna',
'blockip-title'                   => 'Blokir pengguna',
'blockip-legend'                  => 'Blokir pengguna',
'blockiptext'                     => 'Gunakan formulir di bawah untuk memblokir akses penulisan dari sebuah alamat IP atau pengguna tertentu.
Ini hanya boleh dilakukan untuk mencegah vandalisme, dan sejalan dengan [[{{MediaWiki:Policy-url}}|kebijakan]].
Masukkan alasan Anda di bawah (contoh, menuliskan nama halaman yang telah divandalisasi).',
'ipaddress'                       => 'Alamat IP:',
'ipadressorusername'              => 'Alamat IP atau nama pengguna:',
'ipbexpiry'                       => 'Kedaluwarsa:',
'ipbreason'                       => 'Alasan:',
'ipbreasonotherlist'              => 'Alasan lain',
'ipbreason-dropdown'              => '*Alasan umum
** Vandalisme
** Memberikan informasi palsu
** Menghilangkan isi halaman
** Spam pranala ke situs luar
** Memasukkan omong kosong ke halaman
** Perilaku intimidasi/pelecehan
** Menyalahgunakan beberapa akun
** Nama pengguna tak layak',
'ipbanononly'                     => 'Hanya blokir pengguna anonim',
'ipbcreateaccount'                => 'Cegah pembuatan akun',
'ipbemailban'                     => 'Cegah pengguna mengirimkan surel',
'ipbenableautoblock'              => 'Blokir alamat IP terakhir yang digunakan pengguna ini secara otomatis, dan semua alamat berikutnya yang mereka coba gunakan untuk menyunting.',
'ipbsubmit'                       => 'Blokir pengguna ini',
'ipbother'                        => 'Waktu lain:',
'ipboptions'                      => '2 jam:2 hours,1 hari:1 day,3 hari:3 days,1 minggu:1 week,2 minggu:2 weeks,1 bulan:1 month,3 bulan:3 months,6 bulan:6 months,1 tahun:1 year,selamanya:infinite',
'ipbotheroption'                  => 'lainnya',
'ipbotherreason'                  => 'Alasan lain/tambahan:',
'ipbhidename'                     => 'Sembunyikan nama pengguna dari suntingan dan daftar',
'ipbwatchuser'                    => 'Pantau halaman pengguna dan pembicaraan pengguna ini',
'ipballowusertalk'                => 'Izinkan pengguna ini untuk menyunting halaman pembicaraan sendiri ketika diblokir',
'ipb-change-block'                => 'Blokir kembali pengguna dengan set konfigurasi berikut',
'badipaddress'                    => 'Format alamat IP atau nama pengguna salah.',
'blockipsuccesssub'               => 'Pemblokiran sukses',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] telah diblokir.<br />
Lihat [[Special:IPBlockList|Daftar IP]] untuk meninjau kembali pemblokiran.',
'ipb-edit-dropdown'               => 'Sunting alasan pemblokiran',
'ipb-unblock-addr'                => 'Hilangkan blokir $1',
'ipb-unblock'                     => 'Hilangkan blokir seorang pengguna atau suatu alamat IP',
'ipb-blocklist'                   => 'Lihat blokir yang diterapkan',
'ipb-blocklist-contribs'          => 'Kontribusi untuk $1',
'unblockip'                       => 'Hilangkan blokir terhadap alamat IP atau pengguna',
'unblockiptext'                   => 'Gunakan formulir di bawah untuk mengembalikan kemampuan menulis sebuah alamat IP atau pengguna yang sebelumnya telah diblokir.',
'ipusubmit'                       => 'Hilangkan blokir ini',
'unblocked'                       => 'Blokir terhadap [[User:$1|$1]] telah dicabut',
'unblocked-id'                    => 'Blokir $1 telah dicabut',
'ipblocklist'                     => 'Daftar pemblokiran alamat IP dan nama penguna',
'ipblocklist-legend'              => 'Cari pengguna yang diblokir',
'ipblocklist-username'            => 'Nama pengguna atau alamat IP:',
'ipblocklist-sh-userblocks'       => '$1 pemblokiran akun',
'ipblocklist-sh-tempblocks'       => '$1 pemblokiran sementara',
'ipblocklist-sh-addressblocks'    => '$1 pemblokiran IP tunggal',
'ipblocklist-submit'              => 'Cari',
'ipblocklist-localblock'          => 'Blok lokal',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|pemblokiran|pemblokiran}} lain',
'blocklistline'                   => '$1, $2 memblokir $3 ($4)',
'infiniteblock'                   => 'tak terbatas',
'expiringblock'                   => 'kedaluwarsa pada $1 $2',
'anononlyblock'                   => 'hanya pengguna anonim',
'noautoblockblock'                => 'pemblokiran otomatis dimatikan',
'createaccountblock'              => 'pembuatan akun diblokir',
'emailblock'                      => 'surel diblokir',
'blocklist-nousertalk'            => 'tidak dapat menyunting halaman pembicaraan sendiri',
'ipblocklist-empty'               => 'Daftar pemblokiran kosong.',
'ipblocklist-no-results'          => 'alamat IP atau pengguna yang diminta tidak diblokir.',
'blocklink'                       => 'blokir',
'unblocklink'                     => 'hilangkan blokir',
'change-blocklink'                => 'ubah blokir',
'contribslink'                    => 'kontrib',
'autoblocker'                     => 'Diblokir secara otomatis karena alamat IP Anda digunakan oleh "[[User:$1|$1]]".
Alasan yang diberikan untuk pemblokiran $1 adalah: "$2"',
'blocklogpage'                    => 'Log pemblokiran',
'blocklog-showlog'                => 'Pengguna ini telah diblokir sebelumnya. Log pemblokiran di sediakan dibawah untuk referensi:',
'blocklog-showsuppresslog'        => 'Pengguna ini telah diblokir dan disembunyikan sebelumnya. Log penekanan disediakan dibawah untuk referensi:',
'blocklogentry'                   => 'memblokir [[$1]] dengan waktu kedaluwarsa $2 $3',
'reblock-logentry'                => 'mengubah pemblokiran [[$1]] dengan waktu kedaluwarsa $2 $3',
'blocklogtext'                    => 'Di bawah ini adalah log pemblokiran dan pembukaan blokir terhadap pengguna.
Alamat IP yang diblokir secara otomatis tidak terdapat di dalam daftar ini.
Lihat [[Special:IPBlockList|daftar alamat IP yang diblokir]] untuk daftar pemblokiran terkini.',
'unblocklogentry'                 => 'menghilangkan blokir "$1"',
'block-log-flags-anononly'        => 'hanya pengguna anonim',
'block-log-flags-nocreate'        => 'pembuatan akun dimatikan',
'block-log-flags-noautoblock'     => 'pemblokiran otomatis dimatikan',
'block-log-flags-noemail'         => 'surel diblokir',
'block-log-flags-nousertalk'      => 'tidak dapat menyunting halaman pembicaraan sendiri',
'block-log-flags-angry-autoblock' => 'peningkatan sistem pemblokiran otomatis telah diaktifkan',
'block-log-flags-hiddenname'      => 'nama pengguna tersembunyi',
'range_block_disabled'            => 'Kemampuan pengurus dalam membuat blokir blok IP dimatikan.',
'ipb_expiry_invalid'              => 'Waktu kedaluwarsa tidak sah.',
'ipb_expiry_temp'                 => 'Pemblokiran atas nama pengguna yang disembunyikan harus permanen.',
'ipb_hide_invalid'                => 'Tak dapat menutup akun ini; mungkin akun tersebut memiliki terlalu banyak suntingan.',
'ipb_already_blocked'             => '"$1" telah diblokir',
'ipb-needreblock'                 => '== Sudah diblokir ==
$1 sudah diblokir. Apakah Anda ingin mengubah set pemblokiran yang bersangkutan?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|Blok|Blok}} lain',
'ipb_cant_unblock'                => 'Kesalahan: Blokir dengan ID $1 tidak ditemukan. Blokir tersebut kemungkinan telah dibuka.',
'ipb_blocked_as_range'            => 'Kesalahan: IP $1 tidak diblok secara langsung dan tidak dapat dilepaskan. IP $1 diblok sebagai bagian dari pemblokiran kelompok IP $2, yang dapat dilepaskan.',
'ip_range_invalid'                => 'Blok IP tidak sah.',
'ip_range_toolarge'               => 'Rentang blok lebih besar dari /$1 tidak diperbolehkan.',
'blockme'                         => 'Blokir saya',
'proxyblocker'                    => 'Pemblokir proxy',
'proxyblocker-disabled'           => 'Fitur ini sedang tidak diakfifkan.',
'proxyblockreason'                => 'Alamat IP Anda telah diblokir karena alamat IP Anda adalah proxy terbuka. Silakan hubungi penyedia jasa internet Anda atau dukungan teknis dan beritahukan mereka masalah keamanan serius ini.',
'proxyblocksuccess'               => 'Selesai.',
'sorbsreason'                     => 'Alamat IP anda terdaftar sebagai proxy terbuka di DNSBL.',
'sorbs_create_account_reason'     => 'Alamat IP anda terdaftar sebagai proxy terbuka di DNSBL. Anda tidak dapat membuat akun.',
'cant-block-while-blocked'        => 'Anda tidak dapat memblokir pengguna lain ketika Anda sendiri sedang diblokir.',
'cant-see-hidden-user'            => 'Pengguna yang anda coba blokir telah di blokir dan di sembunyikan. Selama anda tidak memiliki hak sembunyikan pengguna, anda tidak dapat melihat atau menyunting pemblokiran pengguna ini.',
'ipbblocked'                      => 'Anda tidak dapat memblokir atau membuka blokir pengguna lain, karena anda sendiri diblokir',
'ipbnounblockself'                => 'Anda tidak diizinkan untuk membuka blokir sendiri',

# Developer tools
'lockdb'              => 'Kunci basis data',
'unlockdb'            => 'Buka kunci basis data',
'lockdbtext'          => 'Mengunci basis data akan menghentikan kemampuan semua pengguna dalam menyunting halaman, mengubah preferensi pengguna, menyunting daftar pantauan mereka, dan hal-hal lain yang memerlukan perubahan terhadap basis data. Pastikan bahwa ini adalah yang ingin Anda lakukan, dan bahwa Anda akan membuka kunci basis data setelah pemeliharaan selesai.',
'unlockdbtext'        => 'Membuka kunci basis data akan mengembalikan kemampuan semua pengguna dalam menyunting halaman, mengubah preferensi pengguna, menyunting daftar pantauan mereka, dan hal-hal lain yang memerlukan perubahan terhadap basis data.  Pastikan bahwa ini adalah yang ingin Anda lakukan.',
'lockconfirm'         => 'Ya, saya memang ingin mengunci basis data.',
'unlockconfirm'       => 'Ya, saya memang ingin membuka kunci basis data.',
'lockbtn'             => 'Kunci basis data',
'unlockbtn'           => 'Buka kunci basis data',
'locknoconfirm'       => 'Anda tidak memberikan tanda cek pada kotak konfirmasi.',
'lockdbsuccesssub'    => 'Penguncian basis data berhasil',
'unlockdbsuccesssub'  => 'Pembukaan kunci basis data berhasil',
'lockdbsuccesstext'   => 'Basis data telah dikunci.<br />
Pastikan Anda [[Special:UnlockDB|membuka kuncinya]] setelah pemeliharaan selesai.',
'unlockdbsuccesstext' => 'Kunci basis data telah dibuka.',
'lockfilenotwritable' => 'Berkas kunci basis data tidak dapat ditulis. Untuk mengunci atau membuka basis data, berkas ini harus dapat ditulis oleh server web.',
'databasenotlocked'   => 'Basis data tidak terkunci.',

# Move page
'move-page'                    => 'Pindahkan $1',
'move-page-legend'             => 'Pindahkan halaman',
'movepagetext'                 => "Formulir di bawah ini digunakan untuk mengubah nama suatu halaman dan memindahkan semua data sejarah ke nama baru. Judul yang lama akan menjadi halaman peralihan menuju judul yang baru. Pranala kepada judul lama tidak akan berubah. Pastikan untuk memeriksa terhadap peralihan halaman yang rusak atau berganda setelah pemindahan. Anda bertanggung jawab untuk memastikan bahwa pranala terus menyambung ke halaman yang seharusnya.

Perhatikan bahwa halaman '''tidak''' akan dipindah apabila telah ada halaman yang menggunakan judul yang baru, kecuali bila halaman tersebut kosong atau merupakan halaman peralihan dan tidak mempunyai sejarah penyuntingan. Ini berarti Anda dapat mengubah nama halaman kembali seperti semula apabila Anda membuat kesalahan, dan Anda tidak dapat menimpa halaman yang telah ada.

'''Peringatan:''' Ini dapat mengakibatkan perubahan yang tak terduga dan drastis  bagi halaman yang populer. Pastikan Anda mengerti konsekuensi dari perbuatan ini sebelum melanjutkan.",
'movepagetext-noredirectfixer' => "Formulir di bawah ini digunakan untuk mengubah nama suatu halaman dan memindahkan semua data sejarah ke nama baru.
Judul yang lama akan menjadi halaman peralihan menuju judul yang baru.
Pastikan untuk memeriksa pengalihan [[Special:DoubleRedirects|ganda]] atau [[Special:BrokenRedirects|rusak]].
Anda bertanggung jawab untuk memastikan bahwa pranala terus menyambung ke halaman yang seharusnya.

Perhatikan bahwa halaman '''tidak''' akan dipindah apabila telah ada halaman yang menggunakan judul yang baru, kecuali bila halaman tersebut kosong atau merupakan halaman peralihan dan tidak mempunyai sejarah penyuntingan.
Ini berarti Anda dapat mengubah nama halaman kembali seperti semula apabila Anda membuat kesalahan, dan Anda tidak dapat menimpa halaman yang telah ada.

'''Peringatan:'''
Hal ini dapat mengakibatkan perubahan yang tak terduga dan drastis bagi halaman yang populer;
Pastikan Anda mengerti konsekuensi dari perbuatan ini sebelum melanjutkan.",
'movepagetalktext'             => "Halaman pembicaraan yang berkaitan juga akan dipindahkan secara otomatis '''kecuali apabila:'''

*Sebuah halaman pembicaraan yang tidak kosong telah ada di bawah judul baru, atau
*Anda tidak memberi tanda cek pada kotak di bawah ini

Dalam kasus tersebut, apabila diinginkan, Anda dapat memindahkan atau menggabungkan halaman secara manual.",
'movearticle'                  => 'Pindahkan halaman:',
'moveuserpage-warning'         => "'''Peringatan:''' Anda tengah memindahkan halaman pengguna. Perlu diketahui bahwa hanya halaman yang akan dipindahkan namun pengguna ''tidak akan'' berganti nama.",
'movenologin'                  => 'Belum masuk log',
'movenologintext'              => 'Anda harus menjadi pengguna terdaftar dan telah [[Special:UserLogin|masuk log]] untuk dapat memindahkan suatu halaman.',
'movenotallowed'               => 'Anda tak memiliki hak akses untuk memindahkan halaman.',
'movenotallowedfile'           => 'Anda tak memiliki hak untuk memindahkan berkas.',
'cant-move-user-page'          => 'Anda tidak memiliki hak akses untuk memindahkan halaman pengguna (terpisah dari subhalaman).',
'cant-move-to-user-page'       => 'Anda tidak memiliki hak akses untuk memindahkan halaman ke suatu halaman pengguna (kecuali ke subhalaman pengguna).',
'newtitle'                     => 'Ke judul baru:',
'move-watch'                   => 'Pantau halaman ini',
'movepagebtn'                  => 'Pindahkan halaman',
'pagemovedsub'                 => 'Pemindahan berhasil',
'movepage-moved'               => '\'\'\'"$1" telah dipindahkan ke "$2"\'\'\'',
'movepage-moved-redirect'      => 'Halaman pengalihan telah dibuat.',
'movepage-moved-noredirect'    => 'Pengalihan tidak dibuat.',
'articleexists'                => 'Halaman dengan nama tersebut telah ada atau nama yang dipilih tidak sah. Silakan pilih nama lain.',
'cantmove-titleprotected'      => 'Anda tidak dapat memindahkan halaman ke lokasi ini, karena judul tujuan sedang dilindungi dari pembuatan',
'talkexists'                   => 'Halaman tersebut berhasil dipindahkan, tetapi halaman pembicaraan dari halaman tersebut tidak dapat dipindahkan karena telah ada halaman pembicaraan pada judul yang baru. Silakan gabungkan halaman-halaman pembicaraan tersebut secara manual.',
'movedto'                      => 'dipindahkan ke',
'movetalk'                     => 'Pindahkan halaman pembicaraan yang terkait',
'move-subpages'                => 'Pindahkan subhalaman (sampai $1)',
'move-talk-subpages'           => 'Pindahkan semua subhalaman pembicaraan (sampai $1)',
'movepage-page-exists'         => 'Halaman $1 telah ada dan tidak dapat ditimpa secara otomatis.',
'movepage-page-moved'          => 'Halaman $1 telah dipindahkan ke $2.',
'movepage-page-unmoved'        => 'Halaman $1 tidak dapat dipindahkan ke $2.',
'movepage-max-pages'           => 'Sejumlah maksimum $1 {{PLURAL:$1|halaman|halaman}} telah dipindahkan dan tidak ada lagi yang akan dipindahkan secara otomatis.',
'1movedto2'                    => 'memindahkan [[$1]] ke [[$2]]',
'1movedto2_redir'              => 'memindahkan [[$1]] ke [[$2]] melalui peralihan',
'move-redirect-suppressed'     => 'pengalihan tidak dibuat',
'movelogpage'                  => 'Log pemindahan',
'movelogpagetext'              => 'Di bawah ini adalah log pemindahan halaman.',
'movesubpage'                  => '{{PLURAL:$1|Subhalaman|Subhalaman}}',
'movesubpagetext'              => 'Halaman ini memiliki $1 {{PLURAL:$1|subhalaman|subhalaman}} seperti ditampilkan berikut.',
'movenosubpage'                => 'Halaman ini tak memiliki subhalaman.',
'movereason'                   => 'Alasan:',
'revertmove'                   => 'kembalikan',
'delete_and_move'              => 'Hapus dan pindahkan',
'delete_and_move_text'         => '==Penghapusan diperlukan==
Halaman yang dituju, "[[:$1]]", telah mempunyai isi. Apakah Anda hendak menghapusnya untuk memberikan ruang bagi pemindahan?',
'delete_and_move_confirm'      => 'Ya, hapus halaman tersebut',
'delete_and_move_reason'       => 'Dihapus untuk mengantisipasikan pemindahan halaman',
'selfmove'                     => 'Pemindahan halaman tidak dapat dilakukan karena judul sumber dan judul tujuan sama.',
'immobile-source-namespace'    => 'Tidak dapat memindahkan halaman dalam ruang nama "$1"',
'immobile-target-namespace'    => 'Tidak dapat memindahkan halaman ke ruang nama "$1"',
'immobile-target-namespace-iw' => 'Pranala interwiki bukanlah target yang valid untuk pemindahan halaman.',
'immobile-source-page'         => 'Halaman ini tidak dapat dipindahkan.',
'immobile-target-page'         => 'Tidak dapat memindahkan ke judul tujuan tersebut.',
'imagenocrossnamespace'        => 'Tidak dapat memindahkan berkas ke ruang nama non-berkas',
'nonfile-cannot-move-to-file'  => 'Tidak dapat memindahkan non-berkas ke ruang nama berkas',
'imagetypemismatch'            => 'Ekstensi yang diberikan tidak cocok dengan tipe berkas',
'imageinvalidfilename'         => 'Nama berkas tujuan tidak sah',
'fix-double-redirects'         => 'Perbaiki semua pengalihan ganda yang mungkin terjadi',
'move-leave-redirect'          => 'Buat pengalihan ke judul baru',
'protectedpagemovewarning'     => "'''Peringatan''': Halaman ini telah dikunci sehingga hanya pengguna dengan hak akses pengurus yang bisa memindahkannya.
Entri catatan terakhir disediakan dibawah untuk referensi:",
'semiprotectedpagemovewarning' => "'''Catatan:''' Halaman ini telah dikunci sehingga hanya pengguna terdaftar yang dapat memindahkannya.
Entri catatan terakhir disediakan dibawah untuk referensi:",
'move-over-sharedrepo'         => '== Berkas sudah ada ==

[[:$1]] sudah ada pada penyimpanan bersama. Memindahkan berkas ke judul ini akan menimpa berkas bersama.',
'file-exists-sharedrepo'       => 'Nama berkas yang dipilih sudah digunakan pada suatu penyimpanan bersama.
Silakan pilih nama lain.',

# Export
'export'            => 'Ekspor halaman',
'exporttext'        => 'Anda dapat mengekspor teks dan sejarah penyuntingan suatu halaman tertentu atau suatu set halaman dalam bentuk XML tertentu.
Hasil ekspor ini selanjutnya dapat diimpor ke wiki lainnya yang menggunakan perangkat lunak MediaWiki, dengan menggunakan fasilitas [[Special:Import|halaman impor]].

Untuk mengekspor halaman, masukkan judul dalam kotak teks di bawah ini, satu judul per baris, dan pilih apakah Anda ingin mengekspor lengkap dengan versi terdahulunya, atau hanya versi terbaru dengan catatan penyuntingan terakhir.

Jika Anda hanya ingin mengimpor versi terbaru, Anda melakukannya lebih cepat dengan cara menggunakan pranala khusus, sebagai contoh: [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] untuk mengekspor halaman "[[{{MediaWiki:Mainpage}}]]".',
'exportcuronly'     => 'Hanya ekspor revisi sekarang, bukan seluruh versi terdahulu',
'exportnohistory'   => "----
'''Catatan:''' Mengekspor keseluruhan riwayat suntingan halaman melalui isian ini telah dinon-aktifkan karena alasan kinerja.",
'export-submit'     => 'Ekspor',
'export-addcattext' => 'Tambahkan halaman dari kategori:',
'export-addcat'     => 'Tambahkan',
'export-addnstext'  => 'Tambahkan halaman dari ruang nama:',
'export-addns'      => 'Tambahkan',
'export-download'   => 'Tawarkan untuk menyimpan sebagai suatu berkas',
'export-templates'  => 'Termasuk templat',
'export-pagelinks'  => 'Sertakan halaman terkait hingga kedalaman:',

# Namespace 8 related
'allmessages'                   => 'Pesan sistem',
'allmessagesname'               => 'Nama',
'allmessagesdefault'            => 'Teks baku',
'allmessagescurrent'            => 'Teks sekarang',
'allmessagestext'               => 'Ini adalah daftar semua pesan sistem yang tersedia dalam ruang nama MediaWiki.
Silakan kunjungi [http://www.mediawiki.org/wiki/Localisation Pelokalan MediaWiki] dan [http://translatewiki.net translatewiki.net] jika Anda ingin berkontribusi untuk pelokalan generik MediaWiki.',
'allmessagesnotsupportedDB'     => "Halaman ini tidak dapat digunakan karena '''\$wgUseDatabaseMessages''' telah dimatikan.",
'allmessages-filter-legend'     => 'Penyaring',
'allmessages-filter'            => 'Saring dengan keadaan kustomisasi:',
'allmessages-filter-unmodified' => 'Tidak diubah',
'allmessages-filter-all'        => 'Semua',
'allmessages-filter-modified'   => 'Diubah',
'allmessages-prefix'            => 'Saring dengan awalan:',
'allmessages-language'          => 'Bahasa:',
'allmessages-filter-submit'     => 'Tuju ke',

# Thumbnails
'thumbnail-more'           => 'Perbesar',
'filemissing'              => 'Berkas tak ditemukan',
'thumbnail_error'          => 'Gagal membuat miniatur: $1',
'djvu_page_error'          => 'Halaman DjVu di luar rentang',
'djvu_no_xml'              => 'XML untuk berkas DjVu tak dapat diperoleh',
'thumbnail_invalid_params' => 'Kesalahan parameter miniatur',
'thumbnail_dest_directory' => 'Direktori tujuan tak dapat dibuat',
'thumbnail_image-type'     => 'Tipe gambar tidak didukung',
'thumbnail_gd-library'     => 'Konfigurasi pustaka GD tak lengkap: tak ada fungsi $1',
'thumbnail_image-missing'  => 'Berkas yang tampaknya hilang: $1',

# Special:Import
'import'                     => 'Impor halaman',
'importinterwiki'            => 'Impor transwiki',
'import-interwiki-text'      => 'Pilih suatu wiki dan judul halaman yang akan di impor.
Tanggal revisi dan nama penyunting akan dipertahankan.
Semua aktivitas impor transwiki akan dicatat di [[Special:Log/import|log impor]].',
'import-interwiki-source'    => 'Wiki/halaman sumber:',
'import-interwiki-history'   => 'Salin semua versi terdahulu dari halaman ini',
'import-interwiki-templates' => 'Sertakan semua templat',
'import-interwiki-submit'    => 'Impor',
'import-interwiki-namespace' => 'Ruang nama tujuan:',
'import-upload-filename'     => 'Nama berkas:',
'import-comment'             => 'Komentar:',
'importtext'                 => 'Silakan ekspor berkas dari wiki asal dengan menggunakan [[Special:Export|fasilitas ekspor]].
Simpan ke komputer Anda lalu muatkan di sini.',
'importstart'                => 'Mengimpor halaman...',
'import-revision-count'      => '$1 {{PLURAL:$1|revisi|revisi}}',
'importnopages'              => 'Tidak ada halaman untuk diimpor.',
'imported-log-entries'       => 'Telah diimpor $1 {{PLURAL:$1|entri log|entri log}}.',
'importfailed'               => 'Impor gagal: $1',
'importunknownsource'        => 'Sumber impor tidak dikenali',
'importcantopen'             => 'Berkas impor tidak dapat dibuka',
'importbadinterwiki'         => 'Pranala interwiki rusak',
'importnotext'               => 'Kosong atau tidak ada teks',
'importsuccess'              => 'Impor sukses!',
'importhistoryconflict'      => 'Terjadi konflik revisi sejarah (mungkin pernah mengimpor halaman ini sebelumnya)',
'importnosources'            => 'Tidak ada sumber impor transwiki yang telah dibuat dan pemuatan riwayat secara langsung telah di non-aktifkan.',
'importnofile'               => 'Tidak ada berkas sumber impor yang telah dimuat.',
'importuploaderrorsize'      => 'Pemuatan berkas impor gagal. Ukuran berkas melebihi ukuran yang diperbolehkan.',
'importuploaderrorpartial'   => 'Pemuatan berkas impor gagal. Hanya sebagian berkas yang berhasil dimuat.',
'importuploaderrortemp'      => 'Pemuatan berkas gagal. Sebuah direktori sementara dibutuhkan.',
'import-parse-failure'       => 'Proses impor XML gagal',
'import-noarticle'           => 'Tak ada halaman yang dapat diimpor!',
'import-nonewrevisions'      => 'Semua revisi telah pernah diimpor sebelumnya.',
'xml-error-string'           => '$1 pada baris $2, kolom $3 (bita $4): $5',
'import-upload'              => 'Memuat data XML',
'import-token-mismatch'      => 'Kehilangan data sesi. Silakan mencoba kembali.',
'import-invalid-interwiki'   => 'Tidak dapat mengimpor dari wiki tersebut.',

# Import log
'importlogpage'                    => 'Log impor',
'importlogpagetext'                => 'Di bawah ini adalah log impor administratif dari halaman-halaman, berikut riwayat suntingannya dari wiki lain.',
'import-logentry-upload'           => 'mengimpor [[$1]] melalui pemuatan berkas',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|revisi|revisi}}',
'import-logentry-interwiki'        => 'men-transwiki $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|revisi}} dari $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Halaman pengguna Anda',
'tooltip-pt-anonuserpage'         => 'Halaman pengguna IP Anda',
'tooltip-pt-mytalk'               => 'Halaman pembicaraan Anda',
'tooltip-pt-anontalk'             => 'Pembicaraan tentang suntingan dari alamat IP ini',
'tooltip-pt-preferences'          => 'Preferensi saya',
'tooltip-pt-watchlist'            => 'Daftar halaman yang saya pantau.',
'tooltip-pt-mycontris'            => 'Daftar kontribusi Anda',
'tooltip-pt-login'                => 'Anda disarankan untuk masuk log, meskipun hal itu tidak diwajibkan.',
'tooltip-pt-anonlogin'            => 'Anda disarankan untuk masuk log, meskipun hal itu tidak diwajibkan.',
'tooltip-pt-logout'               => 'Keluar log',
'tooltip-ca-talk'                 => 'Pembicaraan halaman isi',
'tooltip-ca-edit'                 => 'Sunting halaman ini. Gunakan tombol pratayang sebelum menyimpan.',
'tooltip-ca-addsection'           => 'Mulai bagian baru',
'tooltip-ca-viewsource'           => 'Halaman ini dilindungi. Anda hanya dapat melihat sumbernya.',
'tooltip-ca-history'              => 'Versi-versi sebelumnya dari halaman ini.',
'tooltip-ca-protect'              => 'Lindungi halaman ini',
'tooltip-ca-unprotect'            => 'Buka perlindungan halaman ini',
'tooltip-ca-delete'               => 'Hapus halaman ini',
'tooltip-ca-undelete'             => 'Kembalikan suntingan ke halaman ini sebelum halaman ini dihapus',
'tooltip-ca-move'                 => 'Pindahkan halaman ini',
'tooltip-ca-watch'                => 'Tambahkan halaman ini ke daftar pantauan Anda',
'tooltip-ca-unwatch'              => 'Hapus halaman ini dari daftar pantauan Anda',
'tooltip-search'                  => 'Cari dalam wiki ini',
'tooltip-search-go'               => 'Cari suatu halaman dengan nama yang persis seperti ini jika tersedia',
'tooltip-search-fulltext'         => 'Cari halaman yang memiliki teks seperti ini',
'tooltip-p-logo'                  => 'Kunjungi Halaman Utama',
'tooltip-n-mainpage'              => 'Kunjungi Halaman Utama',
'tooltip-n-mainpage-description'  => 'Kunjungi Halaman Utama',
'tooltip-n-portal'                => 'Tentang proyek, apa yang dapat anda lakukan, di mana mencari sesuatu',
'tooltip-n-currentevents'         => 'Temukan informasi tentang peristiwa terkini',
'tooltip-n-recentchanges'         => 'Daftar perubahan terbaru dalam wiki.',
'tooltip-n-randompage'            => 'Tampilkan sembarang halaman',
'tooltip-n-help'                  => 'Tempat mencari bantuan.',
'tooltip-t-whatlinkshere'         => 'Daftar semua halaman wiki yang memiliki pranala ke halaman ini',
'tooltip-t-recentchangeslinked'   => 'Perubahan terbaru halaman-halaman yang memiliki pranala ke halaman ini',
'tooltip-feed-rss'                => 'Umpan RSS untuk halaman ini',
'tooltip-feed-atom'               => 'Umpan Atom untuk halaman ini',
'tooltip-t-contributions'         => 'Lihat daftar kontribusi pengguna ini',
'tooltip-t-emailuser'             => 'Kirimkan surel kepada pengguna ini',
'tooltip-t-upload'                => 'Muatkan gambar atau berkas media',
'tooltip-t-specialpages'          => 'Daftar semua halaman istimewa',
'tooltip-t-print'                 => 'Versi cetak halaman ini',
'tooltip-t-permalink'             => 'Pranala permanen untuk revisi halaman ini',
'tooltip-ca-nstab-main'           => 'Lihat halaman isi',
'tooltip-ca-nstab-user'           => 'Lihat halaman pengguna',
'tooltip-ca-nstab-media'          => 'Lihat halaman media',
'tooltip-ca-nstab-special'        => 'Ini adalah halaman istimewa yang tidak dapat disunting.',
'tooltip-ca-nstab-project'        => 'Lihat halaman proyek',
'tooltip-ca-nstab-image'          => 'Lihat halaman berkas',
'tooltip-ca-nstab-mediawiki'      => 'Lihat pesan sistem',
'tooltip-ca-nstab-template'       => 'Lihat templat',
'tooltip-ca-nstab-help'           => 'Lihat halaman bantuan',
'tooltip-ca-nstab-category'       => 'Lihat halaman kategori',
'tooltip-minoredit'               => 'Tandai ini sebagai suntingan kecil',
'tooltip-save'                    => 'Simpan perubahan Anda',
'tooltip-preview'                 => 'Pratayang perubahan Anda, harap gunakan ini sebelum menyimpan!',
'tooltip-diff'                    => 'Lihat perubahan yang telah Anda lakukan.',
'tooltip-compareselectedversions' => 'Lihat perbedaan antara dua versi halaman yang dipilih.',
'tooltip-watch'                   => 'Tambahkan halaman ini ke daftar pantauan Anda',
'tooltip-recreate'                => 'Buat ulang halaman walaupun sebenarnya telah dihapus',
'tooltip-upload'                  => 'Mulai pemuatan',
'tooltip-rollback'                => 'Mengembalikan suntingan-suntingan di halaman ini ke kontributor terakhir dalam satu kali klik.',
'tooltip-undo'                    => 'Mengembalikan revisi ini dan membuka kotak penyuntingan dengan mode pratayang. Alasan dapat ditambahkan di kotak ringkasan.',
'tooltip-preferences-save'        => 'Simpan preferensi',
'tooltip-summary'                 => 'Masukkan sebuah ringkasan pendek',

# Stylesheets
'common.css'      => '/* CSS yang ada di sini akan diterapkan untuk semua kulit. */',
'standard.css'    => '/* CSS yang ada di sini akan diterapkan untuk kulit Standard. */',
'nostalgia.css'   => '/* CSS yang ada di sini akan diterapkan untuk kulit Nostalgia. */',
'cologneblue.css' => '/* CSS yang ada di sini akan diterapkan untuk kulit Cologne Blue. */',
'monobook.css'    => '/* CSS yang ada di sini akan diterapkan untuk kulit Monobook. */',
'myskin.css'      => '/* CSS yang ada di sini akan diterapkan untuk kulit Myskin. */',
'chick.css'       => '/* CSS yang ada di sini akan diterapkan untuk kulit Chick. */',
'simple.css'      => '/* CSS yang ada di sini akan diterapkan untuk kulit Simple. */',
'modern.css'      => '/* CSS yang ada di sini akan diterapkan untuk kulit Modern. */',
'vector.css'      => '/* CSS yang ada di sini akan diterapkan untuk kulit Vector. */',
'print.css'       => '/* CSS yang ada di sini akan diterapkan untuk tampilan cetak. */',
'handheld.css'    => '/* CSS yang ada di sini akan diterapkan untuk tampilan piranti genggam yang dikonfigurasi di $wgHandheldStyle. */',

# Scripts
'common.js'      => '/* JavaScript yang ada di sini akan diterapkan untuk semua kulit. */',
'standard.js'    => '/* Semua JavaScript di sini akan dimuatkan untuk para pengguna yang menggunakan kulit Standard */',
'nostalgia.js'   => '/* Semua JavaScript di sini akan dimuatkan untuk para pengguna yang menggunakan kulit Nostalgia */',
'cologneblue.js' => '/* Semua JavaScript di sini akan dimuatkan untuk para pengguna yang menggunakan kulit Cologne Blue */',
'monobook.js'    => '/* Semua JavaScript di sini akan dimuatkan untuk para pengguna yang menggunakan kulit MonoBook */',
'myskin.js'      => '/* Semua JavaScript di sini akan dimuatkan untuk para pengguna yang menggunakan kulit Myskin */',
'chick.js'       => '/* Semua JavaScript di sini akan dimuatkan untuk para pengguna yang menggunakan kulit Chick */',
'simple.js'      => '/* Semua JavaScript di sini akan dimuatkan untuk para pengguna yang menggunakan kulit Simple */',
'modern.js'      => '/* Semua JavaScript di sini akan dimuatkan untuk para pengguna yang menggunakan kulit Modern */',
'vector.js'      => '/* Semua JavaScript di sini akan dimuatkan untuk para pengguna yang menggunakan kulit Vector */',

# Metadata
'nodublincore'      => 'Metadata Dublin Core RDF dimatikan di server ini.',
'nocreativecommons' => 'Metadata Creative Commons RDF dimatikan di server ini.',
'notacceptable'     => 'Server wiki tidak dapat menyediakan data dalam format yang dapat dibaca oleh client Anda.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Pengguna|Pengguna-pengguna}} anonim di {{SITENAME}}',
'siteuser'         => 'Pengguna {{SITENAME}} $1',
'anonuser'         => 'Pengguna anonim {{SITENAME}} $1',
'lastmodifiedatby' => 'Halaman ini terakhir kali diubah $2, $1 oleh $3.',
'othercontribs'    => 'Didasarkan pada karya $1.',
'others'           => 'lainnya',
'siteusers'        => '{{PLURAL:$2|Pengguna|Pengguna-pengguna}} {{SITENAME}} $1',
'anonusers'        => '{{PLURAL:$2||}}Pengguna anonim {{SITENAME}} $1',
'creditspage'      => 'Penghargaan halaman',
'nocredits'        => 'Tidak ada informasi penghargaan yang tersedia untuk halaman ini.',

# Spam protection
'spamprotectiontitle' => 'Filter pencegah spam',
'spamprotectiontext'  => 'Halaman yang ingin Anda simpan telah diblokir oleh filter spam.
Ini mungkin disebabkan oleh pranala ke situs luar yang termasuk dalam daftar hitam.',
'spamprotectionmatch' => 'Teks berikut ini memancing filter spam kami: $1',
'spambot_username'    => 'Pembersihan span MediaWiki',
'spam_reverting'      => 'Mengembalikan ke versi terakhir yang tak memiliki pranala ke $1',
'spam_blanking'       => 'Semua revisi yang memiliki pranala ke $1, pengosongan',

# Info page
'infosubtitle'   => 'Informasi halaman',
'numedits'       => 'Jumlah suntingan (halaman): $1',
'numtalkedits'   => 'Jumlah suntingan (halaman pembicaraan): $1',
'numwatchers'    => 'Jumlah pengamat: $1',
'numauthors'     => 'Jumlah pengarang yang berbeda (halaman): $1',
'numtalkauthors' => 'Jumlah pengarang yang berbeda (halaman pembicaraan): $1',

# Skin names
'skinname-standard' => 'Klasik',
'skinname-simple'   => 'Sederhana',

# Math options
'mw_math_png'    => 'Selalu buat PNG',
'mw_math_simple' => 'HTML jika sangat sederhana atau PNG',
'mw_math_html'   => 'HTML jika mungkin atau PNG',
'mw_math_source' => 'Biarkan sebagai TeX (untuk penjelajah web teks)',
'mw_math_modern' => 'Disarankan untuk penjelajah web modern',
'mw_math_mathml' => 'MathML jika mungkin (percobaan)',

# Math errors
'math_failure'          => 'Gagal memparse',
'math_unknown_error'    => 'Kesalahan yang tidak diketahui',
'math_unknown_function' => 'fungsi yang tidak diketahui',
'math_lexing_error'     => 'kesalahan lexing',
'math_syntax_error'     => 'kesalahan sintaks',
'math_image_error'      => 'Konversi PNG gagal; periksa apakah latex, dvips, gs, dan convert terinstal dengan benar',
'math_bad_tmpdir'       => 'Tidak dapat menulisi atau membuat direktori sementara math',
'math_bad_output'       => 'Tidak dapat menulisi atau membuat direktori keluaran math',
'math_notexvc'          => 'Executable texvc hilang; silakan lihat math/README untuk cara konfigurasi.',

# Patrolling
'markaspatrolleddiff'                 => 'Tandai telah dipatroli',
'markaspatrolledtext'                 => 'Tandai halaman ini telah dipatroli',
'markedaspatrolled'                   => 'Ditandai telah dipatroli',
'markedaspatrolledtext'               => 'Revisi yang terpilih dari [[:$1]] telah ditandai sebagai terpatroli.',
'rcpatroldisabled'                    => 'Patroli perubahan terbaru dimatikan',
'rcpatroldisabledtext'                => 'Fitur patroli perubahan terbaru sedang dimatikan.',
'markedaspatrollederror'              => 'Tidak dapat menandai telah dipatroli',
'markedaspatrollederrortext'          => 'Anda harus menentukan satu revisi untuk ditandai sebagai yang dipatroli.',
'markedaspatrollederror-noautopatrol' => 'Anda tidak diizinkan menandai suntingan Anda sendiri dipatroli.',

# Patrol log
'patrol-log-page'      => 'Log patroli',
'patrol-log-header'    => 'Ini adalah log revisi terpatroli.',
'patrol-log-line'      => 'menandai $1 dari $2 terpatroli $3',
'patrol-log-auto'      => '(otomatis)',
'patrol-log-diff'      => 'revisi $1',
'log-show-hide-patrol' => '$1 log patroli',

# Image deletion
'deletedrevision'                 => 'Revisi lama yang dihapus $1',
'filedeleteerror-short'           => 'Kesalahan waktu menghapus berkas: $1',
'filedeleteerror-long'            => 'Terjadi kesalahan sewaktu menghapus berkas:

$1',
'filedelete-missing'              => 'Berkas "$1" tak dapat dihapus karena tak ditemukan.',
'filedelete-old-unregistered'     => 'Revisi berkas "$1" yang diberikan tidak ada dalam basis data.',
'filedelete-current-unregistered' => 'Berkas yang diberikan "$1" tidak ada dalam basis data.',
'filedelete-archive-read-only'    => 'Direktori arsip "$1" tak dapat ditulis oleh server web.',

# Browsing diffs
'previousdiff' => '← Revisi sebelumnya',
'nextdiff'     => 'Revisi selanjutnya →',

# Media information
'mediawarning'         => "'''Peringatan''': Berkas ini mungkin mengandung kode berbahaya.
Jika dijalankan, sistem Anda akan berisiko terserang.",
'imagemaxsize'         => "Batas ukuran gambar:<br />''(untuk halaman deskripsi berkas)''",
'thumbsize'            => 'Ukuran miniatur:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|halaman|halaman}}',
'file-info'            => '(ukuran berkas: $1, tipe MIME: $2)',
'file-info-size'       => '($1 × $2 piksel, ukuran berkas: $3, tipe MIME: $4)',
'file-nohires'         => '<small>Tak tersedia resolusi yang lebih tinggi.</small>',
'svg-long-desc'        => '(Berkas SVG, nominal $1 × $2 piksel, besar berkas: $3)',
'show-big-image'       => 'Resolusi penuh',
'show-big-image-thumb' => '<small>Ukuran pratayang ini: $1 × $2 piksel</small>',
'file-info-gif-looped' => 'melingkar',
'file-info-gif-frames' => '$1 {{PLURAL:$1||}}frame',
'file-info-png-looped' => 'ulang',
'file-info-png-repeat' => 'dimainkan $1 {{PLURAL:$1|kali|kali}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|bingkai|bingkai}}',

# Special:NewFiles
'newimages'             => 'Berkas baru',
'imagelisttext'         => "Di bawah ini adalah daftar '''$1''' {{PLURAL:$1|berkas|berkas}} diurutkan $2.",
'newimages-summary'     => 'Halaman istimewa berikut menampilkan daftar berkas yang terakhir dimuat',
'newimages-legend'      => 'Penyaring',
'newimages-label'       => 'Nama berkas (atau sebagian dari nama berkas):',
'showhidebots'          => '($1 bot)',
'noimages'              => 'Tidak ada yang dilihat.',
'ilsubmit'              => 'Cari',
'bydate'                => 'berdasarkan tanggal',
'sp-newimages-showfrom' => 'Tampilkan berkas baru dimulai dari $2, $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds-abbrev' => 'd',
'minutes-abbrev' => 'm',
'hours-abbrev'   => 'j',

# Bad image list
'bad_image_list' => 'Formatnya sebagai berikut:

Hanya butir daftar (baris yang diawali dengan tanda *) yang diperhitungkan.
Pranala pertama pada suatu baris haruslah pranala ke berkas yang buruk.
Pranala-pranala selanjutnya pada baris yang sama dianggap sebagai pengecualian, yaitu halaman yang dapat menampilkan berkas tersebut.',

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => 'Berkas ini mengandung informasi tambahan yang mungkin ditambahkan oleh kamera digital atau pemindai yang digunakan untuk membuat atau mendigitalisasi berkas. Jika berkas ini telah mengalami modifikasi, rincian yang ada mungkin tidak secara penuh merefleksikan informasi dari gambar yang sudah dimodifikasi ini.',
'metadata-expand'   => 'Tampilkan rincian tambahan',
'metadata-collapse' => 'Sembunyikan rincian tambahan',
'metadata-fields'   => 'Entri metadata EXIF berikut akan ditampilkan pada halaman informasi gambar jika tabel metadata disembunyikan. Entri lain secara baku akan disembunyikan
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
'exif-bitspersample'               => 'Bit per komponen',
'exif-compression'                 => 'Skema kompresi',
'exif-photometricinterpretation'   => 'Komposisi piksel',
'exif-orientation'                 => 'Orientasi',
'exif-samplesperpixel'             => 'Jumlah komponen',
'exif-planarconfiguration'         => 'Pengaturan data',
'exif-ycbcrsubsampling'            => 'Rasio subsampling Y ke C',
'exif-ycbcrpositioning'            => 'Penempatan Y dan C',
'exif-xresolution'                 => 'Resolusi horizontal',
'exif-yresolution'                 => 'Resolusi vertikal',
'exif-resolutionunit'              => 'Satuan resolusi X dan Y',
'exif-stripoffsets'                => 'Lokasi data gambar',
'exif-rowsperstrip'                => 'Jumlah baris per strip',
'exif-stripbytecounts'             => 'Bita per strip kompresi',
'exif-jpeginterchangeformat'       => 'Ofset ke JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bita data JPEG',
'exif-transferfunction'            => 'Fungsi transfer',
'exif-whitepoint'                  => 'Kromatisitas titik putih',
'exif-primarychromaticities'       => 'Kromatisitas warna primer',
'exif-ycbcrcoefficients'           => 'Koefisien matriks transformasi ruang warna',
'exif-referenceblackwhite'         => 'Nilai referensi pasangan hitam putih',
'exif-datetime'                    => 'Tanggal dan waktu perubahan berkas',
'exif-imagedescription'            => 'Judul gambar',
'exif-make'                        => 'Produsen kamera',
'exif-model'                       => 'Model kamera',
'exif-software'                    => 'Perangkat lunak',
'exif-artist'                      => 'Pembuat',
'exif-copyright'                   => 'Pemilik hak cipta',
'exif-exifversion'                 => 'Versi Exif',
'exif-flashpixversion'             => 'Dukungan versi Flashpix',
'exif-colorspace'                  => 'Ruang warna',
'exif-componentsconfiguration'     => 'Arti tiap komponen',
'exif-compressedbitsperpixel'      => 'Mode kompresi gambar',
'exif-pixelydimension'             => 'Lebar gambar yang sah',
'exif-pixelxdimension'             => 'Tinggi gambar yang sah',
'exif-makernote'                   => 'Catatan produsen',
'exif-usercomment'                 => 'Komentar pengguna',
'exif-relatedsoundfile'            => 'Berkas audio yang berhubungan',
'exif-datetimeoriginal'            => 'Tanggal dan waktu pembuatan data',
'exif-datetimedigitized'           => 'Tanggal dan waktu digitalisasi',
'exif-subsectime'                  => 'Subdetik DateTime',
'exif-subsectimeoriginal'          => 'Subdetik DateTimeOriginal',
'exif-subsectimedigitized'         => 'Subdetik DateTimeDigitized',
'exif-exposuretime'                => 'Waktu pajanan',
'exif-exposuretime-format'         => '$1 detik ($2)',
'exif-fnumber'                     => 'Nilai F',
'exif-exposureprogram'             => 'Program pajanan',
'exif-spectralsensitivity'         => 'Sensitivitas spektral',
'exif-isospeedratings'             => 'Rating kecepatan ISO',
'exif-oecf'                        => 'Faktor konversi optoelektronik',
'exif-shutterspeedvalue'           => 'Kecepatan rana',
'exif-aperturevalue'               => 'Bukaan',
'exif-brightnessvalue'             => 'Kecerahan',
'exif-exposurebiasvalue'           => 'Bias pajanan',
'exif-maxaperturevalue'            => 'Bukaan tanah maksimum',
'exif-subjectdistance'             => 'Jarak subjek',
'exif-meteringmode'                => 'Mode pengukuran',
'exif-lightsource'                 => 'Sumber cahaya',
'exif-flash'                       => 'Kilas',
'exif-focallength'                 => 'Jarak fokus lensa',
'exif-subjectarea'                 => 'Wilayah subjek',
'exif-flashenergy'                 => 'Energi kilas',
'exif-spatialfrequencyresponse'    => 'Respons frekuensi spasial',
'exif-focalplanexresolution'       => 'Resolusi bidang fokus X',
'exif-focalplaneyresolution'       => 'Resolusi bidang fokus Y',
'exif-focalplaneresolutionunit'    => 'Unit resolusi bidang fokus',
'exif-subjectlocation'             => 'Lokasi subjek',
'exif-exposureindex'               => 'Indeks pajanan',
'exif-sensingmethod'               => 'Metode penginderaan',
'exif-filesource'                  => 'Sumber berkas',
'exif-scenetype'                   => 'Tipe pemandangan',
'exif-cfapattern'                  => 'Pola CFA',
'exif-customrendered'              => 'Proses buatan gambar',
'exif-exposuremode'                => 'Mode pajanan',
'exif-whitebalance'                => 'Keseimbangan putih',
'exif-digitalzoomratio'            => 'Rasio pembesaran digital',
'exif-focallengthin35mmfilm'       => 'Panjang fokus pada fil 35 mm',
'exif-scenecapturetype'            => 'Tipe penangkapan',
'exif-gaincontrol'                 => 'Kontrol pemandangan',
'exif-contrast'                    => 'Kontras',
'exif-saturation'                  => 'Saturasi',
'exif-sharpness'                   => 'Ketajaman',
'exif-devicesettingdescription'    => 'Deskripsi pengaturan alat',
'exif-subjectdistancerange'        => 'Jarak subjek',
'exif-imageuniqueid'               => 'ID unik gambar',
'exif-gpsversionid'                => 'Versi tag GPS',
'exif-gpslatituderef'              => 'Lintang Utara atau Selatan',
'exif-gpslatitude'                 => 'Lintang',
'exif-gpslongituderef'             => 'Bujur Timur atau Barat',
'exif-gpslongitude'                => 'Bujur',
'exif-gpsaltituderef'              => 'Referensi ketinggian',
'exif-gpsaltitude'                 => 'Ketinggian',
'exif-gpstimestamp'                => 'Waktu GPS (jam atom)',
'exif-gpssatellites'               => 'Satelit untuk pengukuran',
'exif-gpsstatus'                   => 'Status penerima',
'exif-gpsmeasuremode'              => 'Mode pengukuran',
'exif-gpsdop'                      => 'Ketepatan pengukuran',
'exif-gpsspeedref'                 => 'Unit kecepatan',
'exif-gpsspeed'                    => 'Kecepatan penerima GPS',
'exif-gpstrackref'                 => 'Referensi arah gerakan',
'exif-gpstrack'                    => 'Arah gerakan',
'exif-gpsimgdirectionref'          => 'Referensi arah gambar',
'exif-gpsimgdirection'             => 'Arah gambar',
'exif-gpsmapdatum'                 => 'Data survei geodesi',
'exif-gpsdestlatituderef'          => 'Referensi lintang dari tujuan',
'exif-gpsdestlatitude'             => 'Lintang tujuan',
'exif-gpsdestlongituderef'         => 'Referensi bujur dari tujuan',
'exif-gpsdestlongitude'            => 'Bujur tujuan',
'exif-gpsdestbearingref'           => 'Referensi bearing tujuan',
'exif-gpsdestbearing'              => 'Bearing tujuan',
'exif-gpsdestdistanceref'          => 'Referensi jarak dari tujuan',
'exif-gpsdestdistance'             => 'Jarak dari tujuan',
'exif-gpsprocessingmethod'         => 'Nama metode proses GPS',
'exif-gpsareainformation'          => 'Nama wilayah GPS',
'exif-gpsdatestamp'                => 'Tanggal GPS',
'exif-gpsdifferential'             => 'Koreksi diferensial GPS',

# EXIF attributes
'exif-compression-1' => 'Tak terkompresi',

'exif-unknowndate' => 'Tanggal tak diketahui',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'Dibalik horisontal',
'exif-orientation-3' => 'Diputar 180°',
'exif-orientation-4' => 'Dibalik vertikal',
'exif-orientation-5' => 'Diputar 90° CCW dan dibalik vertikal',
'exif-orientation-6' => 'Diputar 90° CW',
'exif-orientation-7' => 'Diputar 90° CW dan dibalik vertikal',
'exif-orientation-8' => 'Diputar 90° CCW',

'exif-planarconfiguration-1' => 'format chunky',
'exif-planarconfiguration-2' => 'format planar',

'exif-componentsconfiguration-0' => 'tak tersedia',

'exif-exposureprogram-0' => 'Tak terdefinisi',
'exif-exposureprogram-1' => 'Manual',
'exif-exposureprogram-2' => 'Program normal',
'exif-exposureprogram-3' => 'Prioritas bukaan',
'exif-exposureprogram-4' => 'Prioritas penutup',
'exif-exposureprogram-5' => 'Program kreatif (condong ke kedalaman ruang)',
'exif-exposureprogram-6' => 'Program aksi (condong ke kecepatan rana)',
'exif-exposureprogram-7' => 'Modus potret (untuk foto closeup dengan latar belakang tak fokus)',
'exif-exposureprogram-8' => 'Modus pemandangan (untuk foto pemandangan dengan latar belakang fokus)',

'exif-subjectdistance-value' => '$1 meter',

'exif-meteringmode-0'   => 'Tidak diketahui',
'exif-meteringmode-1'   => 'Rerata',
'exif-meteringmode-2'   => 'RerataBerbobot',
'exif-meteringmode-3'   => 'Terpusat',
'exif-meteringmode-4'   => 'BanyakPusat',
'exif-meteringmode-5'   => 'Pola',
'exif-meteringmode-6'   => 'Parsial',
'exif-meteringmode-255' => 'Lain-lain',

'exif-lightsource-0'   => 'Tidak diketahui',
'exif-lightsource-1'   => 'Cahaya siang',
'exif-lightsource-2'   => 'Pendarflour',
'exif-lightsource-3'   => 'Wolfram (cahaya pijar)',
'exif-lightsource-4'   => 'Kilas',
'exif-lightsource-9'   => 'Cuaca baik',
'exif-lightsource-10'  => 'Cuaca berkabut',
'exif-lightsource-11'  => 'Bayangan',
'exif-lightsource-12'  => 'Pendarflour cahaya siang (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Pendarflour putih siang (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Pendarflour putih teduh (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Pendarflour putih (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Cahaya standar A',
'exif-lightsource-18'  => 'Cahaya standar B',
'exif-lightsource-19'  => 'Cahaya standar C',
'exif-lightsource-24'  => 'studio ISO tungsten',
'exif-lightsource-255' => 'Sumber cahaya lain',

# Flash modes
'exif-flash-fired-0'    => 'Lampu kilat tidak menyala',
'exif-flash-fired-1'    => 'Lampu kilat menyala',
'exif-flash-return-0'   => 'tidak ada fungsi pendeteksian strobo balik',
'exif-flash-return-2'   => 'lampu strobo balik tidak terdeteksi',
'exif-flash-return-3'   => 'lampu strobo balik terdeteksi',
'exif-flash-mode-1'     => 'lampu kilat diperlukan',
'exif-flash-mode-2'     => 'lampu kilat dimatikan',
'exif-flash-mode-3'     => 'modus otomatis',
'exif-flash-function-1' => 'Tidak ada fungsi lampu kilat',
'exif-flash-redeye-1'   => 'mode reduksi pantulan mata-merah',

'exif-focalplaneresolutionunit-2' => 'inci',

'exif-sensingmethod-1' => 'Tak terdefinisi',
'exif-sensingmethod-2' => 'Sensor area warna satu keping',
'exif-sensingmethod-3' => 'Sensor area warna dua keping',
'exif-sensingmethod-4' => 'Sensor area warna tiga keping',
'exif-sensingmethod-5' => 'Sensor area warna berurut',
'exif-sensingmethod-7' => 'Sensor trilinear',
'exif-sensingmethod-8' => 'Sensor linear warna berurut',

'exif-scenetype-1' => 'Gambar foto langsung',

'exif-customrendered-0' => 'Proses normal',
'exif-customrendered-1' => 'Proses kustom',

'exif-exposuremode-0' => 'Pajanan otomatis',
'exif-exposuremode-1' => 'Pajanan manual',
'exif-exposuremode-2' => 'Braket otomatis',

'exif-whitebalance-0' => 'Keseimbangan putih otomatis',
'exif-whitebalance-1' => 'Keseimbangan putih manual',

'exif-scenecapturetype-0' => 'Standar',
'exif-scenecapturetype-1' => 'Melebar',
'exif-scenecapturetype-2' => 'Potret',
'exif-scenecapturetype-3' => 'Pemandangan malam',

'exif-gaincontrol-0' => 'Tidak ada',
'exif-gaincontrol-1' => 'Naikkan fokus rendah',
'exif-gaincontrol-2' => 'Naikkan fokus tinggi',
'exif-gaincontrol-3' => 'Turunkan fokus rendah',
'exif-gaincontrol-4' => 'Turunkan fokus tinggi',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Lembut',
'exif-contrast-2' => 'Keras',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Saturasi rendah',
'exif-saturation-2' => 'Saturasi tinggi',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Lembut',
'exif-sharpness-2' => 'Keras',

'exif-subjectdistancerange-0' => 'Tidak diketahui',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Tampak dekat',
'exif-subjectdistancerange-3' => 'Tampak jauh',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Lintang utara',
'exif-gpslatitude-s' => 'Lintang selatan',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Bujur timur',
'exif-gpslongitude-w' => 'Bujur barat',

'exif-gpsstatus-a' => 'Pengukuran sedang berlangsung',
'exif-gpsstatus-v' => 'Interoperabilitas pengukuran',

'exif-gpsmeasuremode-2' => 'Pengukuran 2-dimensi',
'exif-gpsmeasuremode-3' => 'Pengukuran 3-dimensi',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometer per jam',
'exif-gpsspeed-m' => 'Mil per jam',
'exif-gpsspeed-n' => 'Knot',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Arah sejati',
'exif-gpsdirection-m' => 'Arah magnetis',

# External editor support
'edit-externally'      => 'Sunting berkas ini dengan aplikasi luar',
'edit-externally-help' => '(Lihat [http://www.mediawiki.org/wiki/Manual:External_editors instruksi pengaturan] untuk informasi lebih lanjut)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'semua',
'imagelistall'     => 'semua',
'watchlistall2'    => 'semua',
'namespacesall'    => 'semua',
'monthsall'        => 'semua',
'limitall'         => 'semua',

# E-mail address confirmation
'confirmemail'              => 'Konfirmasi alamat surel',
'confirmemail_noemail'      => 'Anda tidak memberikan alamat surel yang sah di [[Special:Preferences|preferensi pengguna]] Anda.',
'confirmemail_text'         => '{{SITENAME}} mengharuskan Anda untuk melakukan konfirmasi atas alamat surel Anda sebelum fitur-fitur surel dapat digunakan.
Tekan tombol di bawah ini untuk mengirimi Anda sebuah surel yang berisi kode konfirmasi yang berupa sebuah alamat internet.
Salin alamat tersebut ke penjelajah web Anda dan buka alamat tersebut untuk melakukan konfirmasi sehingga menginformasikan bahwa alamat surel Anda valid.',
'confirmemail_pending'      => 'Suatu kode konfirmasi telah dikirimkan kepada Anda; jika Anda baru saja membuat akun Anda, silakan tunggu beberapa menit untuk surat tersebut tiba sebelum mencoba untuk meminta satu kode baru.',
'confirmemail_send'         => 'Kirim kode konfirmasi',
'confirmemail_sent'         => 'Surel berisi kode konfirmasi telah dikirim.',
'confirmemail_oncreate'     => 'Suatu kode konfirmasi telah dikirimkan ke alamat surel Anda. Kode ini tidak dibutuhkan untuk masuk log, tapi dibutuhkan sebelum menggunakan semua fitur yang menggunakan surel di wiki ini.',
'confirmemail_sendfailed'   => '{{SITENAME}} tidak berhasil mengirimkan surat konfirmasi Anda.
Harap cek kemungkinan karakter ilegal pada alamat surel.

Aplikasi pengiriman surel menginformasikan: $1',
'confirmemail_invalid'      => 'Kode konfirmasi salah. Kode tersebut mungkin sudah kedaluwarsa.',
'confirmemail_needlogin'    => 'Anda harus melakukan $1 untuk mengkonfirmasikan alamat surel Anda.',
'confirmemail_success'      => 'Alamat surel Anda telah dikonfirmasi.
Sekarang Anda dapat [[Special:UserLogin|masuk log]] dan mulai menggunakan wiki.',
'confirmemail_loggedin'     => 'Alamat surel Anda telah dikonfirmasi.',
'confirmemail_error'        => 'Terjadi kesalahan sewaktu menyimpan konfirmasi Anda.',
'confirmemail_subject'      => 'Konfirmasi alamat surel {{SITENAME}}',
'confirmemail_body'         => 'Seseorang, mungkin Anda, dari alamat IP $1, telah mendaftarkan akun "$2" dengan alamat surel ini di {{SITENAME}}.

Untuk mengonfirmasikan bahwa akun ini benar dimiliki oleh Anda sekaligus mengaktifkan fitur surel di {{SITENAME}}, ikuti pranala berikut pada penjelajah web Anda:

$3

Jika Anda merasa *tidak pernah* mendaftar, jangan ikuti pranala di atas.
Klik pada pranala ini untuk membatalkan konfirmasi alamat surel:

$5

Kode konfirmasi ini akan kedaluwarsa pada $4.',
'confirmemail_body_changed' => 'Seseorang, mungkin Anda, dari alamat IP $1,
telah mengubah surel dari akun "$2" pada alamat ini di {{SITENAME}}.

Untuk mengkonfirmasi bahwa akun ini adalah benar milik Anda sekaligus mengaktifkan
kembali fitur surel pada {{SITENAME}}, ikuti pranala berikut pada browser Anda:

$3

Jika akun ini *bukan* milik Anda, ikuti pranala berikut
untuk membatalkan konfirmasi alamat surel:

$5

Kode konfirmasi ini akan kedaluwarsa pada $4.',
'confirmemail_invalidated'  => 'Konfirmasi alamat surel dibatalkan',
'invalidateemail'           => 'Batalkan konfirmasi surel',

# Scary transclusion
'scarytranscludedisabled' => '[Transklusi interwiki dimatikan]',
'scarytranscludefailed'   => '[Pengambilan templat $1 gagal]',
'scarytranscludetoolong'  => '[URL terlalu panjang]',

# Trackbacks
'trackbackbox'      => 'Lacak balik untuk halaman ini:<br />
$1',
'trackbackremove'   => '([$1 Hapus])',
'trackbacklink'     => 'Lacak balik',
'trackbackdeleteok' => 'Pelacakan balik berhasil dihapus.',

# Delete conflict
'deletedwhileediting' => "'''Peringatan''': Halaman ini telah dihapus setelah Anda mulai melakukan penyuntingan!",
'confirmrecreate'     => "Pengguna [[User:$1|$1]] ([[User talk:$1|bicara]]) telah menghapus halaman selagi Anda mulai melakukan penyuntingan dengan alasan:
: ''$2''
Silakan konfirmasi jika Anda ingin membuat ulang halaman ini.",
'recreate'            => 'Buat ulang',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => 'Hapus singgahan halaman ini?',
'confirm-purge-bottom' => 'Membersihkan halaman akan sekaligus menghapus singgahan dan menampilkan versi halaman terkini.',

# Multipage image navigation
'imgmultipageprev' => '&larr; halaman sebelumnya',
'imgmultipagenext' => 'halaman selanjutnya &rarr;',
'imgmultigo'       => 'Cari!',
'imgmultigoto'     => 'Pergi ke halaman $1',

# Table pager
'ascending_abbrev'         => 'naik',
'descending_abbrev'        => 'turun',
'table_pager_next'         => 'Halaman selanjutnya',
'table_pager_prev'         => 'Halaman sebelumnya',
'table_pager_first'        => 'Halaman pertama',
'table_pager_last'         => 'Halaman terakhir',
'table_pager_limit'        => 'Tampilkan $1 entri per halaman',
'table_pager_limit_label'  => 'Item per halaman:',
'table_pager_limit_submit' => 'Tuju ke',
'table_pager_empty'        => 'Tidak ditemukan',

# Auto-summaries
'autosumm-blank'   => '←Mengosongkan halaman',
'autosumm-replace' => "←Mengganti halaman dengan '$1'",
'autoredircomment' => '←Mengalihkan ke [[$1]]',
'autosumm-new'     => "←Membuat halaman berisi '$1'",

# Live preview
'livepreview-loading' => 'Mengunggah...',
'livepreview-ready'   => 'Memuat… Selesai!',
'livepreview-failed'  => 'Pratayang langsung gagal!
Coba dengan pratayang normal.',
'livepreview-error'   => 'Gagal tersambung: $1 "$2".
Coba dengan pratayang normal.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Perubahan yang lebih baru dari $1 {{PLURAL:$1|detik|detik}} mungkin tidak muncul di daftar ini.',
'lag-warn-high'   => 'Karenanya besarnya keterlambatan basis data server, perubahan yang lebih baru dari $1 {{PLURAL:$1|detik|detik}} mungkin tidak muncul di daftar ini.',

# Watchlist editor
'watchlistedit-numitems'       => 'Daftar pantauan Anda berisi {{PLURAL:$1|1 judul|$1 judul}}, tidak termasuk halaman pembicaraan.',
'watchlistedit-noitems'        => 'Daftar pantauan Anda kosong.',
'watchlistedit-normal-title'   => 'Sunting daftar pantauan',
'watchlistedit-normal-legend'  => 'Hapus judul dari daftar pantauan',
'watchlistedit-normal-explain' => 'Judul pada daftar pantauan Anda ditampilkan di bawah ini.
Untuk menghapus judul, berikan tanda cek pada kotak di sampingnya, dan klik "{{int:Watchlistedit-normal-submit}}".
Anda juga dapat [[Special:Watchlist/raw|menyunting daftar mentahnya]].',
'watchlistedit-normal-submit'  => 'Hapus judul',
'watchlistedit-normal-done'    => '{{PLURAL:$1|satu|$1}} judul telah dihapus dari daftar pantauan Anda:',
'watchlistedit-raw-title'      => 'Sunting daftar pantauan mentah',
'watchlistedit-raw-legend'     => 'Sunting daftar pantauan mentah',
'watchlistedit-raw-explain'    => 'Judul pada daftar pantauan Anda ditampilkan di bawah ini, dan dapat disunting dengan menambahkan atau menghapusnya dari daftar;
satu judul pada setiap barisnya.
Setelah selesai, klik "{{int:Watchlistedit-raw-submit}}".
Anda juga dapat [[Special:Watchlist/edit|menggunakan penyunting standar Anda]].',
'watchlistedit-raw-titles'     => 'Judul:',
'watchlistedit-raw-submit'     => 'Perbarui daftar pantauan',
'watchlistedit-raw-done'       => 'Daftar pantauan Anda telah diperbarui.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 judul telah|$1 judul telah}} ditambahkan:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 judul telah|$1 judul telah}} dikeluarkan:',

# Watchlist editing tools
'watchlisttools-view' => 'Tampilkan perubahan terkait',
'watchlisttools-edit' => 'Tampilkan dan sunting daftar pantauan',
'watchlisttools-raw'  => 'Sunting daftar pantauan mentah',

# Hijri month names
'hijri-calendar-m1'  => 'Muharram',
'hijri-calendar-m2'  => 'Safar',
'hijri-calendar-m3'  => 'Rabiul awal',
'hijri-calendar-m4'  => 'Rabiul akhir',
'hijri-calendar-m5'  => 'Jumadil awal',
'hijri-calendar-m6'  => 'Jumadil akhir',
'hijri-calendar-m7'  => 'Rajab',
'hijri-calendar-m8'  => "Sya'ban",
'hijri-calendar-m9'  => 'Ramadhan',
'hijri-calendar-m10' => 'Syawal',
'hijri-calendar-m11' => 'Dzulkaidah',
'hijri-calendar-m12' => 'Dzulhijjah',

# Hebrew month names
'hebrew-calendar-m1'      => 'Tisyri',
'hebrew-calendar-m2'      => 'Markhesywan',
'hebrew-calendar-m3'      => 'Kislew',
'hebrew-calendar-m4'      => 'Tebet',
'hebrew-calendar-m5'      => 'Syebat',
'hebrew-calendar-m6'      => 'Adar',
'hebrew-calendar-m7'      => 'Nisan',
'hebrew-calendar-m8'      => 'Iyar',
'hebrew-calendar-m9'      => 'Siwan',
'hebrew-calendar-m10'     => 'Tamus',
'hebrew-calendar-m11'     => 'Ab',
'hebrew-calendar-m12'     => 'Elul',
'hebrew-calendar-m1-gen'  => 'Tisyri',
'hebrew-calendar-m2-gen'  => 'Markhesywan',
'hebrew-calendar-m3-gen'  => 'Kislew',
'hebrew-calendar-m4-gen'  => 'Tebet',
'hebrew-calendar-m5-gen'  => 'Syebat',
'hebrew-calendar-m6-gen'  => 'Adar',
'hebrew-calendar-m7-gen'  => 'Nisan',
'hebrew-calendar-m8-gen'  => 'Iyar',
'hebrew-calendar-m9-gen'  => 'Siwan',
'hebrew-calendar-m10-gen' => 'Tamus',
'hebrew-calendar-m11-gen' => 'Ab',
'hebrew-calendar-m12-gen' => 'Elul',

# Core parser functions
'unknown_extension_tag' => 'Tag ekstensi tidak dikenal "$1"',
'duplicate-defaultsort' => 'Peringatan: Kunci pengurutan baku "$2" mengabaikan kunci pengurutan baku "$1" sebelumnya.',

# Special:Version
'version'                          => 'Versi',
'version-extensions'               => 'Ekstensi terinstal',
'version-specialpages'             => 'Halaman istimewa',
'version-parserhooks'              => 'Kait parser',
'version-variables'                => 'Variabel',
'version-other'                    => 'Lain-lain',
'version-mediahandlers'            => 'Penanganan media',
'version-hooks'                    => 'Kait',
'version-extension-functions'      => 'Fungsi ekstensi',
'version-parser-extensiontags'     => 'Tag ekstensi parser',
'version-parser-function-hooks'    => 'Kait fungsi parser',
'version-skin-extension-functions' => 'Fungsi ekstensi kulit',
'version-hook-name'                => 'Nama kait',
'version-hook-subscribedby'        => 'Dilanggani oleh',
'version-version'                  => '(Versi $1)',
'version-license'                  => 'Lisensi',
'version-poweredby-credits'        => "Wiki ini didukung oleh '''[http://www.mediawiki.org/ MediaWiki]''', hak cipta © 2001-$1 $2.",
'version-poweredby-others'         => 'lainnya',
'version-license-info'             => 'MediaWiki adalah perangkat lunak bebas; Anda diperbolehkan untuk mendistribusikan dan/atau memodfikasinya dengan persyaratan Lisensi Publik Umum GNU yang diterbitkan oleh Free Software Foundation; versi 2 atau terbaru.

MediaWiki didistribusikan dengan harapan dapat digunakan, tetapi TANPA JAMINAN APA PUN; tanpa jaminan PERDAGANGAN atau KECOCOKAN UNTUK TUJUAN TERTENTU. Lihat Lisensi Publik Umum GNU untuk informasi lebih lanjut.

Anda seharusnya telah menerima [{{SERVER}}{{SCRIPTPATH}}/COPYING salinan Lisensi Publik Umum GNU] bersama dengan program ini; jika tidak, kirim surat ke Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA atau [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html baca daring].',
'version-software'                 => 'Perangkat lunak terinstal',
'version-software-product'         => 'Produk',
'version-software-version'         => 'Versi',

# Special:FilePath
'filepath'         => 'Lokasi berkas',
'filepath-page'    => 'Berkas:',
'filepath-submit'  => 'Cari',
'filepath-summary' => 'Halaman istimewa ini menampilkan jalur lengkap untuk suatu berkas.
Gambar ditampilkan dalam resolusi penuh dan tipe lain berkas akan dibuka langsung dengan program terkaitnya.

Masukkan nama berkas tanpa prefiks "{{ns:file}}:"-nya.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Pencarian berkas duplikat',
'fileduplicatesearch-summary'  => 'Pencarian duplikat berkas berdasarkan nilai hash-nya.

Masukkan nama berkas tanpa prefiks "{{ns:file}}:".',
'fileduplicatesearch-legend'   => 'Cari duplikat',
'fileduplicatesearch-filename' => 'Nama berkas:',
'fileduplicatesearch-submit'   => 'Cari',
'fileduplicatesearch-info'     => '$1 × $2 piksel<br />Besar berkas: $3<br />Tipe MIME: $4',
'fileduplicatesearch-result-1' => 'Berkas "$1" tidak memiliki duplikat identik.',
'fileduplicatesearch-result-n' => 'Berkas "$1" memiliki {{PLURAL:$2|1 duplikat identik|$2 duplikat identik}}.',

# Special:SpecialPages
'specialpages'                   => 'Halaman istimewa',
'specialpages-note'              => '----
Keterangan tampilan:
* Halaman istimewa normal
* <strong class="mw-specialpagerestricted">Halaman istimewa terbatas</strong>',
'specialpages-group-maintenance' => 'Laporan pemeliharaan',
'specialpages-group-other'       => 'Halaman istimewa lainnya',
'specialpages-group-login'       => 'Masuk log / mendaftar',
'specialpages-group-changes'     => 'Perubahan terbaru dan log',
'specialpages-group-media'       => 'Laporan dan pemuatan berkas',
'specialpages-group-users'       => 'Pengguna dan hak pengguna',
'specialpages-group-highuse'     => 'Frekuensi tinggi',
'specialpages-group-pages'       => 'Daftar halaman',
'specialpages-group-pagetools'   => 'Peralatan halaman',
'specialpages-group-wiki'        => 'Data dan peralatan wiki',
'specialpages-group-redirects'   => 'Mengalihkan halaman istimewa',
'specialpages-group-spam'        => 'Peralatan spam',

# Special:BlankPage
'blankpage'              => 'Halaman kosong',
'intentionallyblankpage' => 'Halaman ini sengaja dibiarkan kosong dan digunakan di antaranya untuk pengukuran kinerja, dan lain-lain.',

# External image whitelist
'external_image_whitelist' => '#Biarkan baris ini sebagaimana adanya<pre>
#Gunakan fragmen-fragmen ekspresi regular (hanya bagian di antara //) di bawah ini
#Fragmen-fragmen ini akan dicocokkan dengan URL dari gambar-gambar eksternal (yang dihubungkan langsung)
#Fragmen yang cocok akan ditampilkan sebagai gambar, sisanya hanya sebagai pranala saja
#Baris yang diawali dengan # akan diperlakukan sebagai baris komentar
#Ini tidak membedakan huruf besar dan kecil
#Letakkan semua fragmen ekspresi regular di bawah baris ini. Biarkan baris ini sebagaimana adanya</pre>',

# Special:Tags
'tags'                    => 'Tag perubahan yang valid',
'tag-filter'              => 'Filter [[Special:Tags|tag]]:',
'tag-filter-submit'       => 'Penyaring',
'tags-title'              => 'Tanda',
'tags-intro'              => 'Halaman ini berisi daftar tag yang dapat ditandai oleh perangkat lunak terhadap suatu suntingan berikut artinya.',
'tags-tag'                => 'Nama tag',
'tags-display-header'     => 'Tampilan di daftar perubahan',
'tags-description-header' => 'Deskripsi lengkap atau makna',
'tags-hitcount-header'    => 'Perubahan bertag',
'tags-edit'               => 'sunting',
'tags-hitcount'           => '$1 {{PLURAL:$1|perubahan|perubahan}}',

# Special:ComparePages
'comparepages'     => 'Bandingkan halaman',
'compare-selector' => 'Bandingkan revisi halaman',
'compare-page1'    => 'Halaman 1',
'compare-page2'    => 'Halaman 2',
'compare-rev1'     => 'Revisi 1',
'compare-rev2'     => 'Revisi 2',
'compare-submit'   => 'Bandingkan',

# Database error messages
'dberr-header'      => 'Wiki ini bermasalah',
'dberr-problems'    => 'Maaf! Situs ini mengalami masalah teknis.',
'dberr-again'       => 'Cobalah menunggu beberapa menit dan muat ulang.',
'dberr-info'        => '(Tak dapat tersambung dengan server basis data: $1)',
'dberr-usegoogle'   => 'Anda dapat mencoba pencarian melalui Google untuk sementara waktu.',
'dberr-outofdate'   => 'Harap diperhatikan bahwa indeks mereka terhadap isi kami mungkin sudah kedaluwarsa.',
'dberr-cachederror' => 'Berikut adalah salinan tersimpan halaman yang diminta, dan mungkin bukan yang terbaru.',

# HTML forms
'htmlform-invalid-input'       => 'Ada kesalahan dalam beberapa input Anda',
'htmlform-select-badoption'    => 'Nilai yang Anda masukkan tidak sah',
'htmlform-int-invalid'         => 'Nilai yang Anda masukkan bukan integer.',
'htmlform-float-invalid'       => 'Yang Anda masukkan bukan merupakan angka.',
'htmlform-int-toolow'          => 'Nilai yang Anda masukkan terlalu rendah di bawah nilai minimum $1',
'htmlform-int-toohigh'         => 'Nilai yang Anda masukkan melebihi nilai maksimum $1',
'htmlform-required'            => 'Nilai ini diperlukan',
'htmlform-submit'              => 'Kirim',
'htmlform-reset'               => 'Batalkan perubahan',
'htmlform-selectorother-other' => 'Lain-lain',

# SQLite database support
'sqlite-has-fts' => '$1 dengan dukungan pencarian teks lengkap',
'sqlite-no-fts'  => '$1 tanpa dukungan pencarian teks lengkap',

);
