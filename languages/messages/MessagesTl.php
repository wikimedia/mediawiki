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

$namespaceNames = [
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
];

$namespaceAliases = [
	'Suleras'          => NS_TEMPLATE,
	'Usapang_suleras'  => NS_TEMPLATE_TALK,
	'Kaurian'          => NS_CATEGORY,
	'Usapang_kaurian'  => NS_CATEGORY_TALK,
];

$specialPageAliases = [
	'Activeusers'               => [ 'Masisiglang_mga_Tagagamit' ],
	'Allmessages'               => [ 'Lahat_ng_mga_mensahe' ],
	'Allpages'                  => [ 'Lahat_ng_mga_pahina', 'LahatPahina' ],
	'Ancientpages'              => [ 'Sinaunang_mga_pahina' ],
	'Badtitle'                  => [ 'Masamang_pamagat' ],
	'Blankpage'                 => [ 'Tanggalin_ang_nilalaman_ng_pahina' ],
	'Block'                     => [ 'Hadlangan', 'Hadlangan_ang_IP', 'Hadlangan_ang_tagagamit' ],
	'Booksources'               => [ 'Mga_pinagmulang_aklat' ],
	'BrokenRedirects'           => [ 'Naputol_na_mga_panturo_papunta_sa_ibang_pahina', 'NaputulangPanturo' ],
	'Categories'                => [ 'Mga_kategorya' ],
	'ChangeEmail'               => [ 'Baguhin_ang_e-liham' ],
	'ChangePassword'            => [ 'Baguhin_ang_hudyat', 'Muling_itakda_ang_hudyat', 'Muling_magtakda_ng_hudyat' ],
	'ComparePages'              => [ 'Paghambingin_ang_mga_Pahina' ],
	'Confirmemail'              => [ 'Tiyakin_ang_e-liham' ],
	'Contributions'             => [ 'Mga_ambag' ],
	'CreateAccount'             => [ 'Likhain_ang_kuwenta', 'LikhaKuwenta' ],
	'Deadendpages'              => [ 'Mga_pahinang_sukol', 'Mga_pahinang_walang_lagusan' ],
	'DeletedContributions'      => [ 'Naburang_mga_ambag' ],
	'DoubleRedirects'           => [ 'Nagkadalawang_mga_panturo_papunta_sa_ibang_pahina', 'DoblengPanturo' ],
	'EditWatchlist'             => [ 'Baguhin_ang_Bantayan' ],
	'Emailuser'                 => [ 'Tagagamit_ng_e-liham' ],
	'ExpandTemplates'           => [ 'Palawakin_ang_mga_suleras' ],
	'Export'                    => [ 'Pagluluwas' ],
	'Fewestrevisions'           => [ 'Pinakakaunting_mga_pagbabago' ],
	'FileDuplicateSearch'       => [ 'Paghahanap_ng_kamukhang_talaksan' ],
	'Filepath'                  => [ 'Daanan_ng_talaksan' ],
	'Import'                    => [ 'Pag-aangkat' ],
	'Invalidateemail'           => [ 'Hindi_tanggap_na_e-liham' ],
	'JavaScriptTest'            => [ 'Pagsubok_sa_JavaScript' ],
	'BlockList'                 => [ 'Talaan_ng_hinahadlangan', 'Talaan_ng_mga_hinahadlangan', 'Talaan_ng_hinahadlangang_IP' ],
	'LinkSearch'                => [ 'Paghahanap_ng_kawing' ],
	'Listadmins'                => [ 'Talaan_ng_mga_tagapangasiwa' ],
	'Listbots'                  => [ 'Talaan_ng_mga_bot' ],
	'Listfiles'                 => [ 'Itala_ang_mga_talaksan', 'Talaan_ng_mga_talaksan', 'Talaan_ng_mga_larawan' ],
	'Listgrouprights'           => [ 'Talaan_ng_mga_karapatan_ng_pangkat' ],
	'Listredirects'             => [ 'Talaan_ng_mga_pagturo_sa_ibang_pahina' ],
	'Listusers'                 => [ 'Talaan_ng_mga_tagagamit', 'Talaan_ng_tagagamit' ],
	'Lockdb'                    => [ 'Ikandado_ang_kalipunan_ng_datos' ],
	'Log'                       => [ 'Tala', 'Mga_tala' ],
	'Lonelypages'               => [ 'Nangungulilang_mga_pahina', 'Ulilang_mga_pahina' ],
	'Longpages'                 => [ 'Mahabang_mga_pahina' ],
	'MergeHistory'              => [ 'Pagsanibin_ang_kasaysayan' ],
	'MIMEsearch'                => [ 'Paghahanap_ng_MIME' ],
	'Mostcategories'            => [ 'Pinakamaraming_mga_kategorya' ],
	'Mostimages'                => [ 'Mga_talaksang_may_pinakamaraming_kawing', 'Pinakamaraming_talaksan', 'Pinakamaraming_larawan' ],
	'Mostlinked'                => [ 'Mga_pahinang_may_pinakamaraming_kawing', 'Pinakamaraming_kawing' ],
	'Mostlinkedcategories'      => [ 'Mga_kategoryang_may_pinakamaraming_kawing', 'Pinakagamiting_mga_kategorya' ],
	'Mostlinkedtemplates'       => [ 'Mga_suleras_na_may_pinakamaraming_kawing', 'Pinakagamiting_mga_suleras' ],
	'Mostrevisions'             => [ 'Pinakamaraming_mga_pagbabago' ],
	'Movepage'                  => [ 'Ilipat_ang_pahina' ],
	'Mycontributions'           => [ 'Mga_ambag_ko' ],
	'Mypage'                    => [ 'Pahina_ko' ],
	'Mytalk'                    => [ 'Usapan_ko' ],
	'Myuploads'                 => [ 'Mga_Pagkakarga_Kong_Paitaas' ],
	'Newimages'                 => [ 'Bagong_mga_talaksan', 'Bagong_mga_larawan' ],
	'Newpages'                  => [ 'Bagong_mga_pahina' ],
	'PasswordReset'             => [ 'Muling_Pagtatakda_ng_Hudyat' ],
	'PermanentLink'             => [ 'Pamalagiang_Kawing' ],
	'Preferences'               => [ 'Mga_nais' ],
	'Prefixindex'               => [ 'Talatuntunan_ng_unlapi' ],
	'Protectedpages'            => [ 'Mga_pahinang_nakasanggalang' ],
	'Protectedtitles'           => [ 'Mga_pamagat_na_nakasanggalang' ],
	'Randompage'                => [ 'Alin_man', 'Alin_mang_pahina' ],
	'RandomInCategory'          => [ 'Alinmang_kaurian' ],
	'Randomredirect'            => [ 'Pagtuturo_papunta_sa_alin_mang_pahina' ],
	'Recentchanges'             => [ 'Mga_huling_binago', 'HulingBinago' ],
	'Recentchangeslinked'       => [ 'Nakakawing_ng_kamakailang_pagbabago', 'Kaugnay_na_mga_pagbabago' ],
	'Revisiondelete'            => [ 'Pagbura_ng_pagbabago' ],
	'Search'                    => [ 'Maghanap' ],
	'Shortpages'                => [ 'Maikling_mga_pahina' ],
	'Specialpages'              => [ 'Natatanging_mga_pahina' ],
	'Statistics'                => [ 'Mga_estadistika', 'Estadistika' ],
	'Tags'                      => [ 'Mga_tatak' ],
	'Unblock'                   => [ 'Huwag_hadlangan' ],
	'Uncategorizedcategories'   => [ 'Mga_kauriang_walang_kaurian' ],
	'Uncategorizedimages'       => [ 'Mga_talaksang_walang_kaurian', 'Mga_larawang_walang_kaurian' ],
	'Uncategorizedpages'        => [ 'Mga_pahinang_walang_kaurian' ],
	'Uncategorizedtemplates'    => [ 'Mga_suleras_na_walang_kaurian' ],
	'Undelete'                  => [ 'Huwag_burahin' ],
	'Unlockdb'                  => [ 'Huwag_ikandado_ang_kalipunan_ng_dato' ],
	'Unusedcategories'          => [ 'Hindi_ginagamit_na_mga_kaurian' ],
	'Unusedimages'              => [ 'Hindi_ginagamit_na_mga_talaksan', 'Hindi_ginagamit_na_mga_larawan' ],
	'Unusedtemplates'           => [ 'Mga_suleras_na_hindi_ginagamit' ],
	'Unwatchedpages'            => [ 'Mga_pahinang_hindi_binabantayanan' ],
	'Upload'                    => [ 'Magkarga' ],
	'UploadStash'               => [ 'Pagkakarga_ng_mga_Nakatago' ],
	'Userlogin'                 => [ 'Paglagda_ng_tagagamit', 'Maglagda' ],
	'Userlogout'                => [ 'Pag-alis_sa_pagkalagda_ng_tagagamit', 'AlisLagda' ],
	'Userrights'                => [ 'Mga_karapatan_ng_tagagamit' ],
	'Version'                   => [ 'Bersiyon' ],
	'Wantedcategories'          => [ 'Ninanais_na_mga_kaurian' ],
	'Wantedfiles'               => [ 'Ninanais_na_mga_talaksan' ],
	'Wantedpages'               => [ 'Ninanais_na_mga_pahina', 'Putol_na_mga_kawing' ],
	'Wantedtemplates'           => [ 'Ninanais_na_mga_suleras' ],
	'Watchlist'                 => [ 'Talaan_ng_binabantayan', 'Bantayan' ],
	'Whatlinkshere'             => [ 'Ano_ang_nakakawing_dito' ],
	'Withoutinterwiki'          => [ 'Walang_ugnayang-wiki' ],
];
