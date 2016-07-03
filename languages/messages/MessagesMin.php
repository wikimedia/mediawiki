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

$namespaceNames = [
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
];

$namespaceAliases = [
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
];

$specialPageAliases = [
	'Activeusers'               => [ 'PanggunoAktip', 'Pangguno_aktip' ],
	'Allmessages'               => [ 'PasanSistim', 'Pasan_sistim' ],
	'Allpages'                  => [ 'DaptaLaman', 'Dapta_laman' ],
	'Ancientpages'              => [ 'LamanLamo', 'Laman_lamo' ],
	'Badtitle'                  => [ 'JudulBuruak', 'Judul_indak_rancak' ],
	'Blankpage'                 => [ 'LamanKosong', 'Laman_kosong' ],
	'Block'                     => [ 'Sakek', 'IPkanaiSakek', 'PanggunoTasakek' ],
	'Booksources'               => [ 'SumberBuku', 'Sumber_buku' ],
	'BrokenRedirects'           => [ 'PangaliahanRusak', 'Pangaliahan_rusak' ],
	'Categories'                => [ 'Kategori' ],
	'ChangeEmail'               => [ 'GantiSurel', 'Ganti_surel' ],
	'ChangePassword'            => [ 'GantiSandi', 'TukaKatoSandi' ],
	'ComparePages'              => [ 'BandiangkanLaman', 'Bandiangkan_laman' ],
	'Confirmemail'              => [ 'PastikanSurel', 'Pastikan_surel' ],
	'Contributions'             => [ 'SuntiangPangguno', 'Jariah' ],
	'CreateAccount'             => [ 'BuekAkun', 'Buek_akun' ],
	'Deadendpages'              => [ 'LamanBuntu', 'Laman_buntu' ],
	'DeletedContributions'      => [ 'SuntiangDihapuih', 'Suntiangan_kanai_hapuih' ],
	'DoubleRedirects'           => [ 'PangaliahanGanda', 'Pangaliahan_ganda' ],
	'EditWatchlist'             => [ 'SuntiangPantauan', 'Suntiang_pantauan' ],
	'Emailuser'                 => [ 'SurelPangguno', 'Surel_pangguno' ],
	'Export'                    => [ 'Ekspor' ],
	'Fewestrevisions'           => [ 'ParubahanTasangenek', 'Parubahan_tasangenek' ],
	'FileDuplicateSearch'       => [ 'CariBerkasDuplikat', 'Cari_berkas_duplikat' ],
	'Filepath'                  => [ 'LokasiBerkas', 'Lokasi_berkas' ],
	'Import'                    => [ 'Impor' ],
	'Invalidateemail'           => [ 'BatalSurel', 'Batalan_surel' ],
	'JavaScriptTest'            => [ 'TesSkripJava', 'Tes_skrip_Java' ],
	'BlockList'                 => [ 'DaptaSakek', 'Dapta_pemblokiran', 'Dapta_IP_disakek' ],
	'LinkSearch'                => [ 'CariPautan', 'Cari_pautan' ],
	'Listadmins'                => [ 'DaptaPanguruih', 'Dapta_panguruih' ],
	'Listbots'                  => [ 'DaptaBot' ],
	'Listfiles'                 => [ 'DaptaBerkas', 'DaptaGamba' ],
	'Listgrouprights'           => [ 'DaptaHakKalompok', 'HakKalompokPangguno' ],
	'Listredirects'             => [ 'DaptaPangaliahan', 'Dapta_pangaliahan' ],
	'Listusers'                 => [ 'DaptaPangguno', 'Dapta_pangguno' ],
	'Lockdb'                    => [ 'KunciBD', 'Kunci_basisdata' ],
	'Log'                       => [ 'Catatan' ],
	'Lonelypages'               => [ 'LamanYatim', 'Laman_indak_batuan' ],
	'Longpages'                 => [ 'LamanPanjang', 'Laman_panjang' ],
	'MergeHistory'              => [ 'SajarahPanggabuangan', 'Sajarah_panggabuangan' ],
	'MIMEsearch'                => [ 'CariMIME', 'PancarianMIME' ],
	'Mostcategories'            => [ 'KategoriTabanyak', 'Kategori_tabanyak' ],
	'Mostimages'                => [ 'BerkasAcokDipakai', 'BerkasTabanyak', 'GambaTabanyak' ],
	'Mostinterwikis'            => [ 'InterwikiAcokDipakai' ],
	'Mostlinked'                => [ 'LamanTautanTabanyak', 'TautanTabanyak' ],
	'Mostlinkedcategories'      => [ 'KategoriBatauikTabanyak', 'KategoriAcokTapakai' ],
	'Mostlinkedtemplates'       => [ 'TemplatTautanTabanyak', 'TemplatAcokDipakai' ],
	'Mostrevisions'             => [ 'ParubahanTabanyak' ],
	'Movepage'                  => [ 'PindahLaman', 'Pindahkan_laman' ],
	'Mycontributions'           => [ 'JariahDenai', 'Jariah_Ambo' ],
	'Mypage'                    => [ 'LamanDenai', 'Laman_Ambo' ],
	'Mytalk'                    => [ 'RundiangDenai', 'Laman_rundiang_Ambo' ],
	'Myuploads'                 => [ 'DenaiMuek', 'Nan_Ambo_muek' ],
	'Newimages'                 => [ 'BerkasBaru', 'Berkas_baru' ],
	'Newpages'                  => [ 'LamanBaru', 'Laman_baru' ],
	'PagesWithProp'             => [ 'LamanJoProperti', 'Laman_jo_properti' ],
	'PasswordReset'             => [ 'TukaSandi', 'Tuka_baliak_sandi' ],
	'PermanentLink'             => [ 'PautanPamanen', 'Pautan_pamanen' ],
	'Preferences'               => [ 'Rujuakan' ],
	'Prefixindex'               => [ 'DaptaAwalan' ],
	'Protectedpages'            => [ 'LamanTalinduang', 'Laman_nan_dilinduang' ],
	'Protectedtitles'           => [ 'JudulTalinduang' ],
	'Randompage'                => [ 'LamanSumbarang' ],
	'Randomredirect'            => [ 'PangaliahanSumbarang' ],
	'Recentchanges'             => [ 'ParubahanBaru' ],
	'Recentchangeslinked'       => [ 'ParubahanTakaik' ],
	'Redirect'                  => [ 'Pangaliahan' ],
	'Revisiondelete'            => [ 'HapuihRevisi' ],
	'Search'                    => [ 'Cari', 'Pancarian' ],
	'Shortpages'                => [ 'LamanPendek' ],
	'Specialpages'              => [ 'LamanIstimewa' ],
	'Unblock'                   => [ 'PambatalanSakek' ],
	'Uncategorizedcategories'   => [ 'KategoriIndakTakategori' ],
	'Uncategorizedimages'       => [ 'BerkasIndakTakategori' ],
	'Uncategorizedpages'        => [ 'LamanIndakTakategori' ],
	'Uncategorizedtemplates'    => [ 'TemplatIndakTakategori' ],
	'Undelete'                  => [ 'BatalHapuih' ],
	'Unlockdb'                  => [ 'BukakKunciBD' ],
	'Unusedcategories'          => [ 'KategoriKosong' ],
	'Unusedimages'              => [ 'BerkasIndakTapakai' ],
	'Unusedtemplates'           => [ 'TemplatIndakTapakai' ],
	'Unwatchedpages'            => [ 'LamanIndakTapantau' ],
	'Upload'                    => [ 'Muek' ],
	'Userlogin'                 => [ 'MasuakLog' ],
	'Userlogout'                => [ 'KaluaLog' ],
	'Userrights'                => [ 'HakPangguno' ],
	'Wantedcategories'          => [ 'KategoriNanParalu' ],
	'Wantedfiles'               => [ 'BerkasNanParalu' ],
	'Wantedpages'               => [ 'LamanNanParalu' ],
	'Wantedtemplates'           => [ 'TemplatNanParalu' ],
	'Watchlist'                 => [ 'Pantauan' ],
	'Whatlinkshere'             => [ 'PautanBaliak' ],
	'Withoutinterwiki'          => [ 'InterwikiIndakAdo' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#ALIAH', '#ALIH', '#REDIRECT' ],
	'pagesincategory_all'       => [ '0', 'sado', 'semua', 'all' ],
	'pagesincategory_pages'     => [ '0', 'laman', 'halaman', 'pages' ],
	'pagesincategory_files'     => [ '0', 'berkas', 'files' ],
];

