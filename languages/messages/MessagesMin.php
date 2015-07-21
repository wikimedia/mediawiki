<?php
/** Minangkabau (Baso Minangkabau)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Bennylin
 * @author Iwan Novirion
 * @author Luthfi94
 * @author Naval Scene
 * @author Rahmatdenas
 * @author SpartacksCompatriot
 * @author VoteITP
 */

$fallback = 'id';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Istimewa',
	NS_TALK             => 'Rundiang',
	NS_USER             => 'Pangguno',
	NS_USER_TALK        => 'Rundiang_Pangguno',
	NS_PROJECT_TALK     => 'Rundiang_$1',
	NS_FILE             => 'Berkas',
	NS_FILE_TALK        => 'Rundiang_Berkas',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Rundiang_MediaWiki',
	NS_TEMPLATE         => 'Templat',
	NS_TEMPLATE_TALK    => 'Rundiang_Templat',
	NS_HELP             => 'Bantuan',
	NS_HELP_TALK        => 'Rundiang_Bantuan',
	NS_CATEGORY         => 'Kategori',
	NS_CATEGORY_TALK    => 'Rundiang_Kategori',
);

$namespaceAliases = array(
	# Indonesian namespaces
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

	'Maota'                 => NS_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'PanggunoAktip', 'Pangguno_aktip' ),
	'Allmessages'               => array( 'PasanSistim', 'Pasan_sistim' ),
	'Allpages'                  => array( 'DaptaLaman', 'Dapta_laman' ),
	'Ancientpages'              => array( 'LamanLamo', 'Laman_lamo' ),
	'Badtitle'                  => array( 'JudulBuruak', 'Judul_indak_rancak' ),
	'Blankpage'                 => array( 'LamanKosong', 'Laman_kosong' ),
	'Block'                     => array( 'Sakek', 'IPkanaiSakek', 'PanggunoTasakek' ),
	'Booksources'               => array( 'SumberBuku', 'Sumber_buku' ),
	'BrokenRedirects'           => array( 'PangaliahanRusak', 'Pangaliahan_rusak' ),
	'Categories'                => array( 'Kategori' ),
	'ChangeEmail'               => array( 'GantiSurel', 'Ganti_surel' ),
	'ChangePassword'            => array( 'GantiSandi', 'TukaKatoSandi' ),
	'ComparePages'              => array( 'BandiangkanLaman', 'Bandiangkan_laman' ),
	'Confirmemail'              => array( 'PastikanSurel', 'Pastikan_surel' ),
	'Contributions'             => array( 'SuntiangPangguno', 'Jariah' ),
	'CreateAccount'             => array( 'BuekAkun', 'Buek_akun' ),
	'Deadendpages'              => array( 'LamanBuntu', 'Laman_buntu' ),
	'DeletedContributions'      => array( 'SuntiangDihapuih', 'Suntiangan_kanai_hapuih' ),
	'DoubleRedirects'           => array( 'PangaliahanGanda', 'Pangaliahan_ganda' ),
	'EditWatchlist'             => array( 'SuntiangPantauan', 'Suntiang_pantauan' ),
	'Emailuser'                 => array( 'SurelPangguno', 'Surel_pangguno' ),
	'Export'                    => array( 'Ekspor' ),
	'Fewestrevisions'           => array( 'ParubahanTasangenek', 'Parubahan_tasangenek' ),
	'FileDuplicateSearch'       => array( 'CariBerkasDuplikat', 'Cari_berkas_duplikat' ),
	'Filepath'                  => array( 'LokasiBerkas', 'Lokasi_berkas' ),
	'Import'                    => array( 'Impor' ),
	'Invalidateemail'           => array( 'BatalSurel', 'Batalan_surel' ),
	'JavaScriptTest'            => array( 'TesSkripJava', 'Tes_skrip_Java' ),
	'BlockList'                 => array( 'DaptaSakek', 'Dapta_pemblokiran', 'Dapta_IP_disakek' ),
	'LinkSearch'                => array( 'CariPautan', 'Cari_pautan' ),
	'Listadmins'                => array( 'DaptaPanguruih', 'Dapta_panguruih' ),
	'Listbots'                  => array( 'DaptaBot' ),
	'Listfiles'                 => array( 'DaptaBerkas', 'DaptaGamba' ),
	'Listgrouprights'           => array( 'DaptaHakKalompok', 'HakKalompokPangguno' ),
	'Listredirects'             => array( 'DaptaPangaliahan', 'Dapta_pangaliahan' ),
	'Listusers'                 => array( 'DaptaPangguno', 'Dapta_pangguno' ),
	'Lockdb'                    => array( 'KunciBD', 'Kunci_basisdata' ),
	'Log'                       => array( 'Catatan' ),
	'Lonelypages'               => array( 'LamanYatim', 'Laman_indak_batuan' ),
	'Longpages'                 => array( 'LamanPanjang', 'Laman_panjang' ),
	'MergeHistory'              => array( 'SajarahPanggabuangan', 'Sajarah_panggabuangan' ),
	'MIMEsearch'                => array( 'CariMIME', 'PancarianMIME' ),
	'Mostcategories'            => array( 'KategoriTabanyak', 'Kategori_tabanyak' ),
	'Mostimages'                => array( 'BerkasAcokDipakai', 'BerkasTabanyak', 'GambaTabanyak' ),
	'Mostinterwikis'            => array( 'InterwikiAcokDipakai' ),
	'Mostlinked'                => array( 'LamanTautanTabanyak', 'TautanTabanyak' ),
	'Mostlinkedcategories'      => array( 'KategoriBatauikTabanyak', 'KategoriAcokTapakai' ),
	'Mostlinkedtemplates'       => array( 'TemplatTautanTabanyak', 'TemplatAcokDipakai' ),
	'Mostrevisions'             => array( 'ParubahanTabanyak' ),
	'Movepage'                  => array( 'PindahLaman', 'Pindahkan_laman' ),
	'Mycontributions'           => array( 'JariahDenai', 'Jariah_Ambo' ),
	'Mypage'                    => array( 'LamanDenai', 'Laman_Ambo' ),
	'Mytalk'                    => array( 'RundiangDenai', 'Laman_rundiang_Ambo' ),
	'Myuploads'                 => array( 'DenaiMuek', 'Nan_Ambo_muek' ),
	'Newimages'                 => array( 'BerkasBaru', 'Berkas_baru' ),
	'Newpages'                  => array( 'LamanBaru', 'Laman_baru' ),
	'PagesWithProp'             => array( 'LamanJoProperti', 'Laman_jo_properti' ),
	'PasswordReset'             => array( 'TukaSandi', 'Tuka_baliak_sandi' ),
	'PermanentLink'             => array( 'PautanPamanen', 'Pautan_pamanen' ),

	'Preferences'               => array( 'Rujuakan' ),
	'Prefixindex'               => array( 'DaptaAwalan' ),
	'Protectedpages'            => array( 'LamanTalinduang', 'Laman_nan_dilinduang' ),
	'Protectedtitles'           => array( 'JudulTalinduang' ),
	'Randompage'                => array( 'LamanSumbarang' ),
	'Randomredirect'            => array( 'PangaliahanSumbarang' ),
	'Recentchanges'             => array( 'ParubahanBaru' ),
	'Recentchangeslinked'       => array( 'ParubahanTakaik' ),
	'Redirect'                  => array( 'Pangaliahan' ),
	'Revisiondelete'            => array( 'HapuihRevisi' ),
	'Search'                    => array( 'Cari', 'Pancarian' ),
	'Shortpages'                => array( 'LamanPendek' ),
	'Specialpages'              => array( 'LamanIstimewa' ),
	'Unblock'                   => array( 'PambatalanSakek' ),
	'Uncategorizedcategories'   => array( 'KategoriIndakTakategori' ),
	'Uncategorizedimages'       => array( 'BerkasIndakTakategori' ),
	'Uncategorizedpages'        => array( 'LamanIndakTakategori' ),
	'Uncategorizedtemplates'    => array( 'TemplatIndakTakategori' ),
	'Undelete'                  => array( 'BatalHapuih' ),
	'Unlockdb'                  => array( 'BukakKunciBD' ),
	'Unusedcategories'          => array( 'KategoriKosong' ),
	'Unusedimages'              => array( 'BerkasIndakTapakai' ),
	'Unusedtemplates'           => array( 'TemplatIndakTapakai' ),
	'Unwatchedpages'            => array( 'LamanIndakTapantau' ),
	'Upload'                    => array( 'Muek' ),
	'Userlogin'                 => array( 'MasuakLog' ),
	'Userlogout'                => array( 'KaluaLog' ),
	'Userrights'                => array( 'HakPangguno' ),
	'Wantedcategories'          => array( 'KategoriNanParalu' ),
	'Wantedfiles'               => array( 'BerkasNanParalu' ),
	'Wantedpages'               => array( 'LamanNanParalu' ),
	'Wantedtemplates'           => array( 'TemplatNanParalu' ),
	'Watchlist'                 => array( 'Pantauan' ),
	'Whatlinkshere'             => array( 'PautanBaliak' ),
	'Withoutinterwiki'          => array( 'InterwikiIndakAdo' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#ALIAH', '#ALIH', '#REDIRECT' ),
	'pagesincategory_all'       => array( '0', 'sado', 'semua', 'all' ),
	'pagesincategory_pages'     => array( '0', 'laman', 'halaman', 'pages' ),
	'pagesincategory_files'     => array( '0', 'berkas', 'files' ),
);

