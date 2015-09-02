<?php
/** Tagalog (Tagalog)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author AnakngAraw
 * @author Aze
 * @author Dosmiin Barsbold
 * @author Felipe Aira
 * @author Jewel457
 * @author Jojit fb
 * @author Kaganer
 * @author Namayan
 * @author Sky Harbor
 * @author tl.wikipedia.org sysops
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Midya',
	NS_SPECIAL          => 'Natatangi',
	NS_TALK             => 'Usapan',
	NS_USER             => 'Tagagamit',
	NS_USER_TALK        => 'Usapang_tagagamit',
	NS_PROJECT_TALK     => 'Usapang_$1',
	NS_FILE             => 'Talaksan',
	NS_FILE_TALK        => 'Usapang_talaksan',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Usapang_MediaWiki',
	NS_TEMPLATE         => 'Padron',
	NS_TEMPLATE_TALK    => 'Usapang_padron',
	NS_HELP             => 'Tulong',
	NS_HELP_TALK        => 'Usapang_tulong',
	NS_CATEGORY         => 'Kategorya',
	NS_CATEGORY_TALK    => 'Usapang_kategorya',
);

$namespaceAliases = array(
	'Suleras'          => NS_TEMPLATE,
	'Usapang_suleras'  => NS_TEMPLATE_TALK,
	'Kaurian'          => NS_CATEGORY,
	'Usapang_kaurian'  => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'Masisiglang_mga_Tagagamit' ),
	'Allmessages'               => array( 'Lahat_ng_mga_mensahe' ),
	'Allpages'                  => array( 'Lahat_ng_mga_pahina', 'LahatPahina' ),
	'Ancientpages'              => array( 'Sinaunang_mga_pahina' ),
	'Badtitle'                  => array( 'Masamang_pamagat' ),
	'Blankpage'                 => array( 'Tanggalin_ang_nilalaman_ng_pahina' ),
	'Block'                     => array( 'Hadlangan', 'Hadlangan_ang_IP', 'Hadlangan_ang_tagagamit' ),
	'Booksources'               => array( 'Mga_pinagmulang_aklat' ),
	'BrokenRedirects'           => array( 'Naputol_na_mga_panturo_papunta_sa_ibang_pahina', 'NaputulangPanturo' ),
	'Categories'                => array( 'Mga_kategorya' ),
	'ChangeEmail'               => array( 'Baguhin_ang_e-liham' ),
	'ChangePassword'            => array( 'Baguhin_ang_hudyat', 'Muling_itakda_ang_hudyat', 'Muling_magtakda_ng_hudyat' ),
	'ComparePages'              => array( 'Paghambingin_ang_mga_Pahina' ),
	'Confirmemail'              => array( 'Tiyakin_ang_e-liham' ),
	'Contributions'             => array( 'Mga_ambag' ),
	'CreateAccount'             => array( 'Likhain_ang_kuwenta', 'LikhaKuwenta' ),
	'Deadendpages'              => array( 'Mga_pahinang_sukol', 'Mga_pahinang_walang_lagusan' ),
	'DeletedContributions'      => array( 'Naburang_mga_ambag' ),
	'DoubleRedirects'           => array( 'Nagkadalawang_mga_panturo_papunta_sa_ibang_pahina', 'DoblengPanturo' ),
	'EditWatchlist'             => array( 'Baguhin_ang_Bantayan' ),
	'Emailuser'                 => array( 'Tagagamit_ng_e-liham' ),
	'ExpandTemplates'           => array( 'Palawakin_ang_mga_suleras' ),
	'Export'                    => array( 'Pagluluwas' ),
	'Fewestrevisions'           => array( 'Pinakakaunting_mga_pagbabago' ),
	'FileDuplicateSearch'       => array( 'Paghahanap_ng_kamukhang_talaksan' ),
	'Filepath'                  => array( 'Daanan_ng_talaksan' ),
	'Import'                    => array( 'Pag-aangkat' ),
	'Invalidateemail'           => array( 'Hindi_tanggap_na_e-liham' ),
	'JavaScriptTest'            => array( 'Pagsubok_sa_JavaScript' ),
	'BlockList'                 => array( 'Talaan_ng_hinahadlangan', 'Talaan_ng_mga_hinahadlangan', 'Talaan_ng_hinahadlangang_IP' ),
	'LinkSearch'                => array( 'Paghahanap_ng_kawing' ),
	'Listadmins'                => array( 'Talaan_ng_mga_tagapangasiwa' ),
	'Listbots'                  => array( 'Talaan_ng_mga_bot' ),
	'Listfiles'                 => array( 'Itala_ang_mga_talaksan', 'Talaan_ng_mga_talaksan', 'Talaan_ng_mga_larawan' ),
	'Listgrouprights'           => array( 'Talaan_ng_mga_karapatan_ng_pangkat' ),
	'Listredirects'             => array( 'Talaan_ng_mga_pagturo_sa_ibang_pahina' ),
	'Listusers'                 => array( 'Talaan_ng_mga_tagagamit', 'Talaan_ng_tagagamit' ),
	'Lockdb'                    => array( 'Ikandado_ang_kalipunan_ng_datos' ),
	'Log'                       => array( 'Tala', 'Mga_tala' ),
	'Lonelypages'               => array( 'Nangungulilang_mga_pahina', 'Ulilang_mga_pahina' ),
	'Longpages'                 => array( 'Mahabang_mga_pahina' ),
	'MergeHistory'              => array( 'Pagsanibin_ang_kasaysayan' ),
	'MIMEsearch'                => array( 'Paghahanap_ng_MIME' ),
	'Mostcategories'            => array( 'Pinakamaraming_mga_kategorya' ),
	'Mostimages'                => array( 'Mga_talaksang_may_pinakamaraming_kawing', 'Pinakamaraming_talaksan', 'Pinakamaraming_larawan' ),
	'Mostlinked'                => array( 'Mga_pahinang_may_pinakamaraming_kawing', 'Pinakamaraming_kawing' ),
	'Mostlinkedcategories'      => array( 'Mga_kategoryang_may_pinakamaraming_kawing', 'Pinakagamiting_mga_kategorya' ),
	'Mostlinkedtemplates'       => array( 'Mga_suleras_na_may_pinakamaraming_kawing', 'Pinakagamiting_mga_suleras' ),
	'Mostrevisions'             => array( 'Pinakamaraming_mga_pagbabago' ),
	'Movepage'                  => array( 'Ilipat_ang_pahina' ),
	'Mycontributions'           => array( 'Mga_ambag_ko' ),
	'Mypage'                    => array( 'Pahina_ko' ),
	'Mytalk'                    => array( 'Usapan_ko' ),
	'Myuploads'                 => array( 'Mga_Pagkakarga_Kong_Paitaas' ),
	'Newimages'                 => array( 'Bagong_mga_talaksan', 'Bagong_mga_larawan' ),
	'Newpages'                  => array( 'Bagong_mga_pahina' ),
	'PasswordReset'             => array( 'Muling_Pagtatakda_ng_Hudyat' ),
	'PermanentLink'             => array( 'Pamalagiang_Kawing' ),
	'Preferences'               => array( 'Mga_nais' ),
	'Prefixindex'               => array( 'Talatuntunan_ng_unlapi' ),
	'Protectedpages'            => array( 'Mga_pahinang_nakasanggalang' ),
	'Protectedtitles'           => array( 'Mga_pamagat_na_nakasanggalang' ),
	'Randompage'                => array( 'Alin_man', 'Alin_mang_pahina' ),
	'RandomInCategory'          => array( 'Alinmang_kaurian' ),
	'Randomredirect'            => array( 'Pagtuturo_papunta_sa_alin_mang_pahina' ),
	'Recentchanges'             => array( 'Mga_huling_binago', 'HulingBinago' ),
	'Recentchangeslinked'       => array( 'Nakakawing_ng_kamakailang_pagbabago', 'Kaugnay_na_mga_pagbabago' ),
	'Revisiondelete'            => array( 'Pagbura_ng_pagbabago' ),
	'Search'                    => array( 'Maghanap' ),
	'Shortpages'                => array( 'Maikling_mga_pahina' ),
	'Specialpages'              => array( 'Natatanging_mga_pahina' ),
	'Statistics'                => array( 'Mga_estadistika', 'Estadistika' ),
	'Tags'                      => array( 'Mga_tatak' ),
	'Unblock'                   => array( 'Huwag_hadlangan' ),
	'Uncategorizedcategories'   => array( 'Mga_kauriang_walang_kaurian' ),
	'Uncategorizedimages'       => array( 'Mga_talaksang_walang_kaurian', 'Mga_larawang_walang_kaurian' ),
	'Uncategorizedpages'        => array( 'Mga_pahinang_walang_kaurian' ),
	'Uncategorizedtemplates'    => array( 'Mga_suleras_na_walang_kaurian' ),
	'Undelete'                  => array( 'Huwag_burahin' ),
	'Unlockdb'                  => array( 'Huwag_ikandado_ang_kalipunan_ng_dato' ),
	'Unusedcategories'          => array( 'Hindi_ginagamit_na_mga_kaurian' ),
	'Unusedimages'              => array( 'Hindi_ginagamit_na_mga_talaksan', 'Hindi_ginagamit_na_mga_larawan' ),
	'Unusedtemplates'           => array( 'Mga_suleras_na_hindi_ginagamit' ),
	'Unwatchedpages'            => array( 'Mga_pahinang_hindi_binabantayanan' ),
	'Upload'                    => array( 'Magkarga' ),
	'UploadStash'               => array( 'Pagkakarga_ng_mga_Nakatago' ),
	'Userlogin'                 => array( 'Paglagda_ng_tagagamit', 'Maglagda' ),
	'Userlogout'                => array( 'Pag-alis_sa_pagkalagda_ng_tagagamit', 'AlisLagda' ),
	'Userrights'                => array( 'Mga_karapatan_ng_tagagamit' ),
	'Version'                   => array( 'Bersiyon' ),
	'Wantedcategories'          => array( 'Ninanais_na_mga_kaurian' ),
	'Wantedfiles'               => array( 'Ninanais_na_mga_talaksan' ),
	'Wantedpages'               => array( 'Ninanais_na_mga_pahina', 'Putol_na_mga_kawing' ),
	'Wantedtemplates'           => array( 'Ninanais_na_mga_suleras' ),
	'Watchlist'                 => array( 'Talaan_ng_binabantayan', 'Bantayan' ),
	'Whatlinkshere'             => array( 'Ano_ang_nakakawing_dito' ),
	'Withoutinterwiki'          => array( 'Walang_ugnayang-wiki' ),
);

