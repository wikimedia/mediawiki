<?php
/** Malay (Bahasa Melayu)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
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

$namespaceNames = array(
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

$magicWords = array(
	'redirect'                  => array( '0', '#LENCONG', '#REDIRECT' ),
	'currentmonth'              => array( '1', 'BULANSEMASA', 'BULANSEMASA2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', 'BULANSEMASA1', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'NAMABULANSEMASA', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'NAMABULANSEMASAGEN', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'SINGBULANSEMASA', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'HARISEMASA', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'HARISEMASA2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'NAMAHARISEMASA', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'TAHUNSEMASA', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'WAKTUSEMASA', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'JAMSEMASA', 'CURRENTHOUR' ),
	'pagename'                  => array( '1', 'NAMALAMAN', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'NAMALAMANE', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'RUANGNAMA', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'RUANGNAMAE', 'NAMESPACEE' ),
	'namespacenumber'           => array( '1', 'NOMBORRUANGNAMA', 'NAMESPACENUMBER' ),
	'talkspace'                 => array( '1', 'RUANGBINCANG', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'RUANGBINCANGE', 'TALKSPACEE' ),
	'fullpagename'              => array( '1', 'NAMALAMANPENUH', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'NAMALAMANPENUHE', 'FULLPAGENAMEE' ),
	'msg'                       => array( '0', 'PESAN:', 'MSG:' ),
	'subst'                     => array( '0', 'TUKAR:', 'SUBST:' ),
	'img_right'                 => array( '1', 'kiri', 'right' ),
	'img_left'                  => array( '1', 'kanan', 'left' ),
	'img_none'                  => array( '1', 'tiada', 'none' ),
	'img_center'                => array( '1', 'tengah', 'center', 'centre' ),
	'img_border'                => array( '1', 'bingkai', 'border' ),
	'sitename'                  => array( '1', 'NAMATAPAK', 'SITENAME' ),
	'ns'                        => array( '0', 'RN:', 'NS:' ),
	'nse'                       => array( '0', 'RNE:', 'NSE:' ),
	'gender'                    => array( '0', 'JANTINA:', 'GENDER:' ),
	'currentweek'               => array( '1', 'MINGGUSEMASA', 'CURRENTWEEK' ),
);

$specialPageAliases = array(
	'Activeusers'               => array( 'Pengguna_aktif' ),
	'Allmessages'               => array( 'Semua_pesanan', 'Semua_mesej' ),
	'Allpages'                  => array( 'Semua_laman' ),
	'Ancientpages'              => array( 'Laman_lapuk' ),
	'Blankpage'                 => array( 'Laman_kosong' ),
	'Block'                     => array( 'Sekat_IP' ),
	'Booksources'               => array( 'Sumber_buku' ),
	'BrokenRedirects'           => array( 'Lencongan_rosak', 'Pelencongan_rosak' ),
	'Categories'                => array( 'Kategori' ),
	'ChangeEmail'               => array( 'Tukar_e-mel' ),
	'ChangePassword'            => array( 'Lupa_kata_laluan' ),
	'ComparePages'              => array( 'Banding_laman' ),
	'Confirmemail'              => array( 'Sahkan_e-mel' ),
	'Contributions'             => array( 'Sumbangan' ),
	'CreateAccount'             => array( 'Buka_akaun' ),
	'Deadendpages'              => array( 'Laman_buntu' ),
	'DeletedContributions'      => array( 'Sumbangan_dihapuskan' ),
	'DoubleRedirects'           => array( 'Lencongan_berganda', 'Pelencongan_berganda' ),
	'Emailuser'                 => array( 'E-mel_pengguna' ),
	'ExpandTemplates'           => array( 'Kembangkan_templat' ),
	'Export'                    => array( 'Eksport' ),
	'Fewestrevisions'           => array( 'Semakan_tersikit' ),
	'FileDuplicateSearch'       => array( 'Cari_fail_berganda' ),
	'Filepath'                  => array( 'Laluan_fail' ),
	'Invalidateemail'           => array( 'Batalkan_pengesahan_e-mel' ),
	'BlockList'                 => array( 'Sekatan_IP' ),
	'LinkSearch'                => array( 'Cari_pautan' ),
	'Listadmins'                => array( 'Senarai_pentadbir' ),
	'Listbots'                  => array( 'Senarai_bot' ),
	'Listfiles'                 => array( 'Senarai_imej' ),
	'Listgrouprights'           => array( 'Senarai_hak_kumpulan' ),
	'Listredirects'             => array( 'Senarai_lencongan', 'Senarai_pelencongan' ),
	'Listusers'                 => array( 'Senarai_pengguna' ),
	'Lockdb'                    => array( 'Kunci_pangkalan_data' ),
	'Lonelypages'               => array( 'Laman_yatim' ),
	'Longpages'                 => array( 'Laman_panjang' ),
	'MergeHistory'              => array( 'Gabung_sejarah' ),
	'MIMEsearch'                => array( 'Gelintar_MIME' ),
	'Mostcategories'            => array( 'Kategori_terbanyak' ),
	'Mostimages'                => array( 'Imej_terbanyak' ),
	'Mostlinked'                => array( 'Laman_dipaut_terbanyak' ),
	'Mostlinkedcategories'      => array( 'Kategori_dipaut_terbanyak' ),
	'Mostlinkedtemplates'       => array( 'Templat_dipaut_terbanyak' ),
	'Mostrevisions'             => array( 'Semakan_terbanyak' ),
	'Movepage'                  => array( 'Pindah_laman' ),
	'Mycontributions'           => array( 'Sumbangan_saya' ),
	'Mypage'                    => array( 'Laman_saya' ),
	'Mytalk'                    => array( 'Perbincangan_saya' ),
	'Myuploads'                 => array( 'Muat_naik_saya' ),
	'Newimages'                 => array( 'Imej_baru' ),
	'Newpages'                  => array( 'Laman_baru' ),
	'Popularpages'              => array( 'Laman_popular' ),
	'Preferences'               => array( 'Keutamaan' ),
	'Prefixindex'               => array( 'Indeks_awalan' ),
	'Protectedpages'            => array( 'Laman_dilindungi' ),
	'Protectedtitles'           => array( 'Tajuk_dilindungi' ),
	'Randompage'                => array( 'Laman_rawak' ),
	'Randomredirect'            => array( 'Lencongan_rawak', 'Pelencongan_rawak' ),
	'Recentchanges'             => array( 'Perubahan_terkini' ),
	'Recentchangeslinked'       => array( 'Perubahan_berkaitan' ),
	'Revisiondelete'            => array( 'Hapus_semakan' ),
	'Search'                    => array( 'Gelintar' ),
	'Shortpages'                => array( 'Laman_pendek' ),
	'Specialpages'              => array( 'Laman_khas' ),
	'Statistics'                => array( 'Statistik' ),
	'Tags'                      => array( 'Label' ),
	'Unblock'                   => array( 'Nyahsekat' ),
	'Uncategorizedcategories'   => array( 'Kategori_tanpa_kategori' ),
	'Uncategorizedimages'       => array( 'Imej_tanpa_kategori' ),
	'Uncategorizedpages'        => array( 'Laman_tanpa_kategori' ),
	'Uncategorizedtemplates'    => array( 'Templat_tanpa_kategori' ),
	'Undelete'                  => array( 'Nyahhapus' ),
	'Unlockdb'                  => array( 'Buka_kunci_pangkalan_data' ),
	'Unusedcategories'          => array( 'Kategori_tak_digunakan' ),
	'Unusedimages'              => array( 'Imej_tak_digunakan' ),
	'Unusedtemplates'           => array( 'Templat_tak_digunakan' ),
	'Unwatchedpages'            => array( 'Laman_tak_dipantau' ),
	'Upload'                    => array( 'Muat_naik' ),
	'Userlogin'                 => array( 'Log_masuk' ),
	'Userlogout'                => array( 'Log_keluar' ),
	'Userrights'                => array( 'Hak_pengguna' ),
	'Version'                   => array( 'Versi' ),
	'Wantedcategories'          => array( 'Kategori_dikehendaki' ),
	'Wantedfiles'               => array( 'Fail_dikehendaki' ),
	'Wantedpages'               => array( 'Laman_dikehendaki' ),
	'Wantedtemplates'           => array( 'Templat_dikehendaki' ),
	'Watchlist'                 => array( 'Senarai_pantau' ),
	'Whatlinkshere'             => array( 'Pautan_ke' ),
	'Withoutinterwiki'          => array( 'Laman_tanpa_pautan_bahasa' ),
);

