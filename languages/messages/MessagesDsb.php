<?php
/** Lower Sorbian (dolnoserbski)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'de';

$namespaceNames = array(
	NS_MEDIA            => 'Medija',
	NS_SPECIAL          => 'Specialne',
	NS_TALK             => 'Diskusija',
	NS_USER             => 'Wužywaŕ',
	NS_USER_TALK        => 'Diskusija_wužywarja',
	NS_PROJECT_TALK     => '$1 diskusija',
	NS_FILE             => 'Dataja',
	NS_FILE_TALK        => 'Diskusija wó dataji',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_diskusija',
	NS_TEMPLATE         => 'Pśedłoga',
	NS_TEMPLATE_TALK    => 'Diskusija_wó_pśedłoze',
	NS_HELP             => 'Pomoc',
	NS_HELP_TALK        => 'Diskusija_wó_pomocy',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Diskusija_wó_kategoriji',
);

$namespaceAliases = array(
	'Wobraz' => NS_FILE,
	'Diskusija_wó_wobrazu' => NS_FILE_TALK,
);

$namespaceGenderAliases = array(
	NS_USER => array( 'male' => 'Wužywaŕ', 'female' => 'Wužywarka' ),
	NS_USER_TALK => array( 'male' => 'Diskusija_wužywarja', 'female' => 'Diskusija_wužywarki' ),
);

$specialPageAliases = array(
	'Activeusers'               => array( 'Aktiwne_wužywarje' ),
	'Allmessages'               => array( 'Systemowe_powěsći' ),
	'Allpages'                  => array( 'Wšykne_boki' ),
	'Ancientpages'              => array( 'Nejstarše_boki' ),
	'Blankpage'                 => array( 'Prozny_bok' ),
	'Block'                     => array( 'Blokěrowaś' ),
	'Booksources'               => array( 'Pytaś_pó_ISBN' ),
	'BrokenRedirects'           => array( 'Njefunkcioněrujuce_dalejpósrědnjenja' ),
	'Categories'                => array( 'Kategorije' ),
	'ChangePassword'            => array( 'Šćitne_gronidło_slědk_stajiś' ),
	'Confirmemail'              => array( 'E-mail_wobkšuśiś' ),
	'Contributions'             => array( 'Pśinoski' ),
	'CreateAccount'             => array( 'Wužywarske_konto_załožyś' ),
	'Deadendpages'              => array( 'Boki_kenž_su_slěpe_gasy' ),
	'DeletedContributions'      => array( 'Wulašowane_pśinoski' ),
	'DoubleRedirects'           => array( 'Dwójne_dalejpósrědnjenja' ),
	'Emailuser'                 => array( 'E-mail' ),
	'Export'                    => array( 'Eksportěrowaś' ),
	'Fewestrevisions'           => array( 'Nejmjenjej_wobźěłane_boki' ),
	'FileDuplicateSearch'       => array( 'Pytanje_datajowych_duplikatow' ),
	'Filepath'                  => array( 'Datajowa_sćažka' ),
	'Import'                    => array( 'Importěrowaś' ),
	'Invalidateemail'           => array( 'E-mail_njewobkšuśis' ),
	'BlockList'                 => array( 'Blokěrowane_IPje' ),
	'LinkSearch'                => array( 'Pytanje_wótkazow' ),
	'Listadmins'                => array( 'Administratory' ),
	'Listbots'                  => array( 'Boty' ),
	'Listfiles'                 => array( 'Lisćina_datajow' ),
	'Listgrouprights'           => array( 'Pšawa_wužywarskich_kupkow' ),
	'Listredirects'             => array( 'Pśesměrowanja' ),
	'Listusers'                 => array( 'Wužywarje' ),
	'Lockdb'                    => array( 'Datowu_banku_blokěrowaś' ),
	'Log'                       => array( 'Protokole' ),
	'Lonelypages'               => array( 'Wósyrośone_boki' ),
	'Longpages'                 => array( 'Nejdlěše_boki' ),
	'MergeHistory'              => array( 'Stawizny_wersijow_zjadnośiś' ),
	'MIMEsearch'                => array( 'Pytaś_pó_MIME-typje' ),
	'Mostcategories'            => array( 'Boki_z_nejwěcej_kategorijami' ),
	'Mostimages'                => array( 'Nejwěcej_wužywane_dataje' ),
	'Mostlinked'                => array( 'Boki_na_kótarež_wjeźo_nejwěcej_wótkazow' ),
	'Mostlinkedcategories'      => array( 'Nejwěcej_wužywane_kategorije' ),
	'Mostlinkedtemplates'       => array( 'Nejwěcej_wužywane_pśedłogi' ),
	'Mostrevisions'             => array( 'Nejwěcej_wobźěłane_boki' ),
	'Movepage'                  => array( 'Pśesunuś' ),
	'Mycontributions'           => array( 'Móje_pśinoski' ),
	'Mypage'                    => array( 'Mój_bok' ),
	'Mytalk'                    => array( 'Mója_diskusija' ),
	'Newimages'                 => array( 'Nowe_dataje' ),
	'Newpages'                  => array( 'Nowe_boki' ),

	'Preferences'               => array( 'Nastajenja' ),
	'Prefixindex'               => array( 'Indeks_prefiksow' ),
	'Protectedpages'            => array( 'Šćitane_boki' ),
	'Protectedtitles'           => array( 'Šćitane_title' ),
	'Randompage'                => array( 'Pśipadny_bok' ),
	'Randomredirect'            => array( 'Pśipadne_pśesměrowanje' ),
	'Recentchanges'             => array( 'Aktualne_změny' ),
	'Recentchangeslinked'       => array( 'Změny_na_zalinkowanych_bokach' ),
	'Revisiondelete'            => array( 'Wulašowanje_wersijow' ),
	'Search'                    => array( 'Pytaś' ),
	'Shortpages'                => array( 'Nejkrotše_boki' ),
	'Specialpages'              => array( 'Specialne_boki' ),
	'Statistics'                => array( 'Statistika' ),
	'Tags'                      => array( 'Toflicki' ),
	'Uncategorizedcategories'   => array( 'Njekategorizěrowane_kategorije' ),
	'Uncategorizedimages'       => array( 'Njekategorizěrowane_dataje' ),
	'Uncategorizedpages'        => array( 'Njekategorizěrowane_boki' ),
	'Uncategorizedtemplates'    => array( 'Njekategorizěrowane_pśedłogi' ),
	'Undelete'                  => array( 'Nawrośiś' ),
	'Unlockdb'                  => array( 'Datowu_banku_zasej_spśistupniś' ),
	'Unusedcategories'          => array( 'Njewužywane_kategorije' ),
	'Unusedimages'              => array( 'Njewužywane_dataje' ),
	'Unusedtemplates'           => array( 'Njewužywane_pśedłogi' ),
	'Unwatchedpages'            => array( 'Boki_kenž_njejsu_we_wobglědowańkach' ),
	'Upload'                    => array( 'Uploadowaś' ),
	'Userlogin'                 => array( 'Pśizjawiś_se' ),
	'Userlogout'                => array( 'Wótzjawiś_se' ),
	'Userrights'                => array( 'Pšawa_wužywarjow' ),
	'Version'                   => array( 'Wersija' ),
	'Wantedcategories'          => array( 'Póžedane_kategorije' ),
	'Wantedfiles'               => array( 'Felujuce_dataje' ),
	'Wantedpages'               => array( 'Póžedane_boki' ),
	'Wantedtemplates'           => array( 'Felujuce_pśedłogi' ),
	'Watchlist'                 => array( 'Wobglědowańka' ),
	'Whatlinkshere'             => array( 'Lisćina_wótkazow' ),
	'Withoutinterwiki'          => array( 'Interwikije_feluju' ),
);

$datePreferences = array(
	'default',
	'dmy',
	'ISO 8601',
);

$defaultDateFormat = 'dmy';

$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j. xg Y',
	'dmy both' => 'j. xg Y, H:i',
);

