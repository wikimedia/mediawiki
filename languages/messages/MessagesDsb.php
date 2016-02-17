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

$namespaceNames = [
	NS_MEDIA            => 'Medija',
	NS_SPECIAL          => 'Specialne',
	NS_TALK             => 'Diskusija',
	NS_USER             => 'Wužywaŕ',
	NS_USER_TALK        => 'Diskusija_wužywarja',
	NS_PROJECT_TALK     => '$1_diskusija',
	NS_FILE             => 'Dataja',
	NS_FILE_TALK        => 'Diskusija_wó_dataji',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_diskusija',
	NS_TEMPLATE         => 'Pśedłoga',
	NS_TEMPLATE_TALK    => 'Diskusija_wó_pśedłoze',
	NS_HELP             => 'Pomoc',
	NS_HELP_TALK        => 'Diskusija_wó_pomocy',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Diskusija_wó_kategoriji',
];

$namespaceAliases = [
	'Wobraz' => NS_FILE,
	'Diskusija_wó_wobrazu' => NS_FILE_TALK,
];

$namespaceGenderAliases = [
	NS_USER => [ 'male' => 'Wužywaŕ', 'female' => 'Wužywarka' ],
	NS_USER_TALK => [ 'male' => 'Diskusija_wužywarja', 'female' => 'Diskusija_wužywarki' ],
];

$specialPageAliases = [
	'Activeusers'               => [ 'Aktiwne_wužywarje' ],
	'Allmessages'               => [ 'Systemowe_powěsći' ],
	'Allpages'                  => [ 'Wšykne_boki' ],
	'Ancientpages'              => [ 'Nejstarše_boki' ],
	'Blankpage'                 => [ 'Prozny_bok' ],
	'Block'                     => [ 'Blokěrowaś' ],
	'Booksources'               => [ 'Pytaś_pó_ISBN' ],
	'BrokenRedirects'           => [ 'Njefunkcioněrujuce_dalejpósrědnjenja' ],
	'Categories'                => [ 'Kategorije' ],
	'ChangePassword'            => [ 'Šćitne_gronidło_slědk_stajiś' ],
	'Confirmemail'              => [ 'E-mail_wobkšuśiś' ],
	'Contributions'             => [ 'Pśinoski' ],
	'CreateAccount'             => [ 'Wužywarske_konto_załožyś' ],
	'Deadendpages'              => [ 'Boki_kenž_su_slěpe_gasy' ],
	'DeletedContributions'      => [ 'Wulašowane_pśinoski' ],
	'DoubleRedirects'           => [ 'Dwójne_dalejpósrědnjenja' ],
	'Emailuser'                 => [ 'E-mail' ],
	'Export'                    => [ 'Eksportěrowaś' ],
	'Fewestrevisions'           => [ 'Nejmjenjej_wobźěłane_boki' ],
	'FileDuplicateSearch'       => [ 'Pytanje_datajowych_duplikatow' ],
	'Filepath'                  => [ 'Datajowa_sćažka' ],
	'Import'                    => [ 'Importěrowaś' ],
	'Invalidateemail'           => [ 'E-mail_njewobkšuśis' ],
	'BlockList'                 => [ 'Blokěrowane_IPje' ],
	'LinkSearch'                => [ 'Pytanje_wótkazow' ],
	'Listadmins'                => [ 'Administratory' ],
	'Listbots'                  => [ 'Boty' ],
	'Listfiles'                 => [ 'Lisćina_datajow' ],
	'Listgrouprights'           => [ 'Pšawa_wužywarskich_kupkow' ],
	'Listredirects'             => [ 'Pśesměrowanja' ],
	'Listusers'                 => [ 'Wužywarje' ],
	'Lockdb'                    => [ 'Datowu_banku_blokěrowaś' ],
	'Log'                       => [ 'Protokole' ],
	'Lonelypages'               => [ 'Wósyrośone_boki' ],
	'Longpages'                 => [ 'Nejdlěše_boki' ],
	'MergeHistory'              => [ 'Stawizny_wersijow_zjadnośiś' ],
	'MIMEsearch'                => [ 'Pytaś_pó_MIME-typje' ],
	'Mostcategories'            => [ 'Boki_z_nejwěcej_kategorijami' ],
	'Mostimages'                => [ 'Nejwěcej_wužywane_dataje' ],
	'Mostlinked'                => [ 'Boki_na_kótarež_wjeźo_nejwěcej_wótkazow' ],
	'Mostlinkedcategories'      => [ 'Nejwěcej_wužywane_kategorije' ],
	'Mostlinkedtemplates'       => [ 'Nejwěcej_wužywane_pśedłogi' ],
	'Mostrevisions'             => [ 'Nejwěcej_wobźěłane_boki' ],
	'Movepage'                  => [ 'Pśesunuś' ],
	'Mycontributions'           => [ 'Móje_pśinoski' ],
	'Mypage'                    => [ 'Mój_bok' ],
	'Mytalk'                    => [ 'Mója_diskusija' ],
	'Newimages'                 => [ 'Nowe_dataje' ],
	'Newpages'                  => [ 'Nowe_boki' ],
	'Preferences'               => [ 'Nastajenja' ],
	'Prefixindex'               => [ 'Indeks_prefiksow' ],
	'Protectedpages'            => [ 'Šćitane_boki' ],
	'Protectedtitles'           => [ 'Šćitane_title' ],
	'Randompage'                => [ 'Pśipadny_bok' ],
	'Randomredirect'            => [ 'Pśipadne_pśesměrowanje' ],
	'Recentchanges'             => [ 'Aktualne_změny' ],
	'Recentchangeslinked'       => [ 'Změny_na_zalinkowanych_bokach' ],
	'Revisiondelete'            => [ 'Wulašowanje_wersijow' ],
	'Search'                    => [ 'Pytaś' ],
	'Shortpages'                => [ 'Nejkrotše_boki' ],
	'Specialpages'              => [ 'Specialne_boki' ],
	'Statistics'                => [ 'Statistika' ],
	'Tags'                      => [ 'Toflicki' ],
	'Uncategorizedcategories'   => [ 'Njekategorizěrowane_kategorije' ],
	'Uncategorizedimages'       => [ 'Njekategorizěrowane_dataje' ],
	'Uncategorizedpages'        => [ 'Njekategorizěrowane_boki' ],
	'Uncategorizedtemplates'    => [ 'Njekategorizěrowane_pśedłogi' ],
	'Undelete'                  => [ 'Nawrośiś' ],
	'Unlockdb'                  => [ 'Datowu_banku_zasej_spśistupniś' ],
	'Unusedcategories'          => [ 'Njewužywane_kategorije' ],
	'Unusedimages'              => [ 'Njewužywane_dataje' ],
	'Unusedtemplates'           => [ 'Njewužywane_pśedłogi' ],
	'Unwatchedpages'            => [ 'Boki_kenž_njejsu_we_wobglědowańkach' ],
	'Upload'                    => [ 'Uploadowaś' ],
	'Userlogin'                 => [ 'Pśizjawiś_se' ],
	'Userlogout'                => [ 'Wótzjawiś_se' ],
	'Userrights'                => [ 'Pšawa_wužywarjow' ],
	'Version'                   => [ 'Wersija' ],
	'Wantedcategories'          => [ 'Póžedane_kategorije' ],
	'Wantedfiles'               => [ 'Felujuce_dataje' ],
	'Wantedpages'               => [ 'Póžedane_boki' ],
	'Wantedtemplates'           => [ 'Felujuce_pśedłogi' ],
	'Watchlist'                 => [ 'Wobglědowańka' ],
	'Whatlinkshere'             => [ 'Lisćina_wótkazow' ],
	'Withoutinterwiki'          => [ 'Interwikije_feluju' ],
];

$datePreferences = [
	'default',
	'dmy',
	'ISO 8601',
];

$defaultDateFormat = 'dmy';

$dateFormats = [
	'dmy time' => 'H:i',
	'dmy date' => 'j. xg Y',
	'dmy both' => 'j. xg Y, H:i',
];

