<?php
/** Malay (Bahasa Melayu)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Algazel-ms
 * @author Anakmalaysia
 * @author Aurora
 * @author Aviator
 * @author CoolCityCat
 * @author Diagramma Della Verita
 * @author Hydra
 * @author Izzudin
 * @author Kaganer
 * @author Kurniasan
 * @author Meno25
 * @author Putera Luqman Tunku Andre
 * @author SNN95
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

$namespaceNames = [
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Khas',
	NS_TALK             => 'Perbincangan',
	NS_USER             => 'Pengguna',
	NS_USER_TALK        => 'Perbincangan_pengguna',
	NS_PROJECT_TALK     => 'Perbincangan_$1',
	NS_FILE             => 'Fail',
	NS_FILE_TALK        => 'Perbincangan_fail',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Perbincangan_MediaWiki',
	NS_TEMPLATE         => 'Templat',
	NS_TEMPLATE_TALK    => 'Perbincangan_templat',
	NS_HELP             => 'Bantuan',
	NS_HELP_TALK        => 'Perbincangan_bantuan',
	NS_CATEGORY         => 'Kategori',
	NS_CATEGORY_TALK    => 'Perbincangan_kategori',
];

$namespaceAliases = [
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
];

$magicWords = [
	'redirect'                  => [ '0', '#LENCONG', '#REDIRECT' ],
	'currentmonth'              => [ '1', 'BULANSEMASA', 'BULANSEMASA2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'BULANSEMASA1', 'CURRENTMONTH1' ],
	'currentmonthname'          => [ '1', 'NAMABULANSEMASA', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'NAMABULANSEMASAGEN', 'CURRENTMONTHNAMEGEN' ],
	'currentmonthabbrev'        => [ '1', 'SINGBULANSEMASA', 'CURRENTMONTHABBREV' ],
	'currentday'                => [ '1', 'HARISEMASA', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'HARISEMASA2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'NAMAHARISEMASA', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'TAHUNSEMASA', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'WAKTUSEMASA', 'CURRENTTIME' ],
	'currenthour'               => [ '1', 'JAMSEMASA', 'CURRENTHOUR' ],
	'pagename'                  => [ '1', 'NAMALAMAN', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'NAMALAMANE', 'PAGENAMEE' ],
	'namespace'                 => [ '1', 'RUANGNAMA', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'RUANGNAMAE', 'NAMESPACEE' ],
	'namespacenumber'           => [ '1', 'NOMBORRUANGNAMA', 'NAMESPACENUMBER' ],
	'talkspace'                 => [ '1', 'RUANGBINCANG', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'RUANGBINCANGE', 'TALKSPACEE' ],
	'fullpagename'              => [ '1', 'NAMALAMANPENUH', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'NAMALAMANPENUHE', 'FULLPAGENAMEE' ],
	'msg'                       => [ '0', 'PESAN:', 'MSG:' ],
	'subst'                     => [ '0', 'TUKAR:', 'SUBST:' ],
	'img_right'                 => [ '1', 'kiri', 'right' ],
	'img_left'                  => [ '1', 'kanan', 'left' ],
	'img_none'                  => [ '1', 'tiada', 'none' ],
	'img_center'                => [ '1', 'tengah', 'center', 'centre' ],
	'img_border'                => [ '1', 'bingkai', 'border' ],
	'sitename'                  => [ '1', 'NAMATAPAK', 'SITENAME' ],
	'ns'                        => [ '0', 'RN:', 'NS:' ],
	'nse'                       => [ '0', 'RNE:', 'NSE:' ],
	'gender'                    => [ '0', 'JANTINA:', 'GENDER:' ],
	'currentweek'               => [ '1', 'MINGGUSEMASA', 'CURRENTWEEK' ],
];

$specialPageAliases = [
	'Activeusers'               => [ 'Pengguna_aktif' ],
	'Allmessages'               => [ 'Semua_pesanan', 'Semua_mesej' ],
	'AllMyUploads'              => [ 'Semua_muat_naik_saya', 'Semua_fail_saya' ],
	'Allpages'                  => [ 'Semua_laman' ],
	'ApiHelp'                   => [ 'Bantuan_API' ],
	'Ancientpages'              => [ 'Laman_lapuk' ],
	'Badtitle'                  => [ 'Tajuk_salah' ],
	'Blankpage'                 => [ 'Laman_kosong' ],
	'Block'                     => [ 'Sekat', 'Sekat_IP', 'Sekat_pengguna' ],
	'Booksources'               => [ 'Sumber_buku' ],
	'BrokenRedirects'           => [ 'Lencongan_rosak', 'Pelencongan_rosak' ],
	'Categories'                => [ 'Kategori' ],
	'ChangeEmail'               => [ 'Tukar_e-mel' ],
	'ChangePassword'            => [ 'Lupa_kata_laluan' ],
	'ComparePages'              => [ 'Banding_laman' ],
	'Confirmemail'              => [ 'Sahkan_e-mel' ],
	'Contributions'             => [ 'Sumbangan' ],
	'CreateAccount'             => [ 'Buka_akaun' ],
	'Deadendpages'              => [ 'Laman_buntu' ],
	'DeletedContributions'      => [ 'Sumbangan_dihapuskan' ],
	'Diff'                      => [ 'Beza' ],
	'DoubleRedirects'           => [ 'Lencongan_berganda', 'Pelencongan_berganda' ],
	'EditWatchlist'             => [ 'Sunting_senarai_pantau' ],
	'Emailuser'                 => [ 'E-mel_pengguna' ],
	'ExpandTemplates'           => [ 'Kembangkan_templat' ],
	'Export'                    => [ 'Eksport' ],
	'Fewestrevisions'           => [ 'Semakan_tersikit' ],
	'FileDuplicateSearch'       => [ 'Cari_fail_berganda' ],
	'Filepath'                  => [ 'Laluan_fail' ],
	'Invalidateemail'           => [ 'Batalkan_pengesahan_e-mel' ],
	'JavaScriptTest'            => [ 'Kaji_JavaScript' ],
	'BlockList'                 => [ 'Sekatan_IP' ],
	'LinkSearch'                => [ 'Cari_pautan' ],
	'Listadmins'                => [ 'Senarai_pentadbir' ],
	'Listbots'                  => [ 'Senarai_bot' ],
	'Listfiles'                 => [ 'Senarai_imej' ],
	'Listgrouprights'           => [ 'Hak_kumpulan', 'Senarai_hak_kumpulan' ],
	'Listredirects'             => [ 'Senarai_lencongan', 'Senarai_pelencongan' ],
	'ListDuplicatedFiles'       => [ 'Senarai_fail_disalin' ],
	'Listusers'                 => [ 'Senarai_pengguna' ],
	'Lockdb'                    => [ 'Kunci_pangkalan_data' ],
	'Lonelypages'               => [ 'Laman_yatim' ],
	'Longpages'                 => [ 'Laman_panjang' ],
	'MediaStatistics'           => [ 'Statistik_media' ],
	'MergeHistory'              => [ 'Gabung_sejarah' ],
	'MIMEsearch'                => [ 'Gelintar_MIME' ],
	'Mostcategories'            => [ 'Kategori_terbanyak' ],
	'Mostimages'                => [ 'Imej_terbanyak' ],
	'Mostinterwikis'            => [ 'Interwiki_terbanyak' ],
	'Mostlinked'                => [ 'Laman_dipaut_terbanyak' ],
	'Mostlinkedcategories'      => [ 'Kategori_dipaut_terbanyak' ],
	'Mostlinkedtemplates'       => [ 'Templat_dipaut_terbanyak' ],
	'Mostrevisions'             => [ 'Semakan_terbanyak' ],
	'Movepage'                  => [ 'Pindah_laman' ],
	'Mycontributions'           => [ 'Sumbangan_saya' ],
	'MyLanguage'                => [ 'Bahasa_saya' ],
	'Mypage'                    => [ 'Laman_saya' ],
	'Mytalk'                    => [ 'Perbincangan_saya' ],
	'Myuploads'                 => [ 'Muat_naik_saya' ],
	'Newimages'                 => [ 'Imej_baru' ],
	'Newpages'                  => [ 'Laman_baru' ],
	'PagesWithProp'             => [ 'Laman_dengan_sifat' ],
	'PageLanguage'              => [ 'Bahasa_laman' ],
	'PasswordReset'             => [ 'Tetap_semula_kata_kunci' ],
	'PermanentLink'             => [ 'Pautan_kekal' ],
	'Preferences'               => [ 'Keutamaan' ],
	'Prefixindex'               => [ 'Indeks_awalan' ],
	'Protectedpages'            => [ 'Laman_dilindungi' ],
	'Protectedtitles'           => [ 'Tajuk_dilindungi' ],
	'Randompage'                => [ 'Rawak', 'Laman_rawak' ],
	'RandomInCategory'          => [ 'Rawak_dalam_kategori' ],
	'Randomredirect'            => [ 'Lencongan_rawak', 'Pelencongan_rawak' ],
	'Recentchanges'             => [ 'Perubahan_terkini' ],
	'Recentchangeslinked'       => [ 'Perubahan_berkaitan' ],
	'Redirect'                  => [ 'Lencong' ],
	'Revisiondelete'            => [ 'Hapus_semakan' ],
	'Search'                    => [ 'Cari', 'Gelintar' ],
	'Shortpages'                => [ 'Laman_pendek' ],
	'Specialpages'              => [ 'Laman_khas' ],
	'Statistics'                => [ 'Statistik' ],
	'Tags'                      => [ 'Teg', 'Label' ],
	'TrackingCategories'        => [ 'Kategori_penjejak' ],
	'Unblock'                   => [ 'Nyahsekat' ],
	'Uncategorizedcategories'   => [ 'Kategori_tanpa_kategori' ],
	'Uncategorizedimages'       => [ 'Imej_tanpa_kategori' ],
	'Uncategorizedpages'        => [ 'Laman_tanpa_kategori' ],
	'Uncategorizedtemplates'    => [ 'Templat_tanpa_kategori' ],
	'Undelete'                  => [ 'Nyahhapus' ],
	'Unlockdb'                  => [ 'Buka_kunci_pangkalan_data' ],
	'Unusedcategories'          => [ 'Kategori_tak_digunakan' ],
	'Unusedimages'              => [ 'Imej_tak_digunakan' ],
	'Unusedtemplates'           => [ 'Templat_tak_digunakan' ],
	'Unwatchedpages'            => [ 'Laman_tak_dipantau' ],
	'Upload'                    => [ 'Muat_naik' ],
	'Userlogin'                 => [ 'Log_masuk' ],
	'Userlogout'                => [ 'Log_keluar' ],
	'Userrights'                => [ 'Hak_pengguna' ],
	'Version'                   => [ 'Versi' ],
	'Wantedcategories'          => [ 'Kategori_dikehendaki' ],
	'Wantedfiles'               => [ 'Fail_dikehendaki' ],
	'Wantedpages'               => [ 'Laman_dikehendaki' ],
	'Wantedtemplates'           => [ 'Templat_dikehendaki' ],
	'Watchlist'                 => [ 'Senarai_pantau' ],
	'Whatlinkshere'             => [ 'Pautan_ke_sini', 'Pautan_ke' ],
	'Withoutinterwiki'          => [ 'Laman_tanpa_pautan_bahasa' ],
];
