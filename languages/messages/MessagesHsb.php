<?php
/** Upper Sorbian (hornjoserbsce)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'de';

$namespaceNames = [
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Specialnje',
	NS_TALK             => 'Diskusija',
	NS_USER             => 'Wužiwar',
	NS_USER_TALK        => 'Diskusija_z_wužiwarjom',
	NS_PROJECT_TALK     => '$1_diskusija',
	NS_FILE             => 'Dataja',
	NS_FILE_TALK        => 'Diskusija_k_dataji',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_diskusija',
	NS_TEMPLATE         => 'Předłoha',
	NS_TEMPLATE_TALK    => 'Diskusija_k_předłoze',
	NS_HELP             => 'Pomoc',
	NS_HELP_TALK        => 'Pomoc_diskusija',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Diskusija_ke_kategoriji',
];

$namespaceAliases = [
	'Wobraz' => NS_FILE,
	'Diskusija_k_wobrazej' => NS_FILE_TALK,
];

$namespaceGenderAliases = [
	NS_USER => [ 'male' => 'Wužiwar', 'female' => 'Wužiwarka' ],
	NS_USER_TALK => [ 'male' => 'Diskusija_z_wužiwarjom', 'female' => 'Diskusija_z_wužiwarku' ],
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

$specialPageAliases = [
	'Activeusers'               => [ 'Aktiwni_wužiwarjo' ],
	'Allmessages'               => [ 'MediaWiki-zdźělenki' ],
	'Allpages'                  => [ 'Wšě_strony' ],
	'Ancientpages'              => [ 'Najstarše_strony' ],
	'Blankpage'                 => [ 'Prózdna_strona' ],
	'Block'                     => [ 'Blokować' ],
	'Booksources'               => [ 'Pytanje_po_ISBN' ],
	'BrokenRedirects'           => [ 'Skóncowane_daleposrědkowanja' ],
	'Categories'                => [ 'Kategorije' ],
	'ChangePassword'            => [ 'Hesło_wróćo_stajić' ],
	'Confirmemail'              => [ 'E-Mejl_potwjerdźić' ],
	'Contributions'             => [ 'Přinoški' ],
	'CreateAccount'             => [ 'Konto_wutworić' ],
	'Deadendpages'              => [ 'Strony_bjez_wotkazow' ],
	'DeletedContributions'      => [ 'Zničene_přinoški' ],
	'DoubleRedirects'           => [ 'Dwójne_daleposrědkowanja' ],
	'EditWatchlist'             => [ 'Wobkedźbowanki_wobdźěłać' ],
	'Emailuser'                 => [ 'E-Mejl' ],
	'Export'                    => [ 'Eksport' ],
	'Fewestrevisions'           => [ 'Strony_z_najmjenje_wersijemi' ],
	'FileDuplicateSearch'       => [ 'Duplikatowe_pytanje' ],
	'Filepath'                  => [ 'Datajowy_puć' ],
	'Import'                    => [ 'Importować' ],
	'Invalidateemail'           => [ 'Njepłaćiwa_e-mejl' ],
	'BlockList'                 => [ 'Blokowane_IP-adresy' ],
	'LinkSearch'                => [ 'Wotkazowe_pytanje' ],
	'Listadmins'                => [ 'Administratorojo' ],
	'Listbots'                  => [ 'Boćiki' ],
	'Listfiles'                 => [ 'Dataje' ],
	'Listgrouprights'           => [ 'Prawa_skupinow' ],
	'Listredirects'             => [ 'Daleposrědkowanja' ],
	'Listusers'                 => [ 'Wužiwarjo' ],
	'Lockdb'                    => [ 'Datowu_banku_zamknyć' ],
	'Log'                       => [ 'Protokol' ],
	'Lonelypages'               => [ 'Wosyroćene_strony' ],
	'Longpages'                 => [ 'Najdlěše_strony' ],
	'MergeHistory'              => [ 'Stawizny_zjednoćić' ],
	'MIMEsearch'                => [ 'Pytanje_po_MIME' ],
	'Mostcategories'            => [ 'Strony_z_najwjace_kategorijemi' ],
	'Mostimages'                => [ 'Z_najwjace_stronami_zwjazane_dataje' ],
	'Mostlinked'                => [ 'Z_najwjace_stronami_zwjazane_strony' ],
	'Mostlinkedcategories'      => [ 'Najhusćišo_wužiwane_kategorije' ],
	'Mostlinkedtemplates'       => [ 'Najhusćišo_wužiwane_předłohi' ],
	'Mostrevisions'             => [ 'Strony_z_najwjace_wersijemi' ],
	'Movepage'                  => [ 'Přesunyć' ],
	'Mycontributions'           => [ 'Moje_přinoški' ],
	'MyLanguage'                => [ 'Moja_rěč' ],
	'Mypage'                    => [ 'Moja_wužiwarska_strona' ],
	'Mytalk'                    => [ 'Moja_diskusijna_strona' ],
	'Newimages'                 => [ 'Nowe_dataje' ],
	'Newpages'                  => [ 'Nowe_strony' ],
	'PermanentLink'             => [ 'Trajny_wotkaz' ],
	'Preferences'               => [ 'Nastajenja' ],
	'Prefixindex'               => [ 'Prefiksindeks' ],
	'Protectedpages'            => [ 'Škitane_strony' ],
	'Protectedtitles'           => [ 'Škitane_titule' ],
	'Randompage'                => [ 'Připadna_strona' ],
	'Randomredirect'            => [ 'Připadne_daleposrědkowanje' ],
	'Recentchanges'             => [ 'Aktualne_změny' ],
	'Recentchangeslinked'       => [ 'Změny_zwjazanych_stronow' ],
	'Redirect'                  => [ 'Dalesposrědkowanje' ],
	'Revisiondelete'            => [ 'Wušmórnjenje_wersijow' ],
	'Search'                    => [ 'Pytać' ],
	'Shortpages'                => [ 'Najkrótše_strony' ],
	'Specialpages'              => [ 'Specialne_strony' ],
	'Statistics'                => [ 'Statistika' ],
	'Tags'                      => [ 'Taflički' ],
	'Uncategorizedcategories'   => [ 'Njekategorizowane_kategorije' ],
	'Uncategorizedimages'       => [ 'Njekategorizowane_dataje' ],
	'Uncategorizedpages'        => [ 'Njekategorizowane_strony' ],
	'Uncategorizedtemplates'    => [ 'Njekategorizowane_předłohi' ],
	'Undelete'                  => [ 'Wobnowić' ],
	'Unlockdb'                  => [ 'Datowu_banku_wotamknyć' ],
	'Unusedcategories'          => [ 'Njewužiwane_kategorije' ],
	'Unusedimages'              => [ 'Njewužiwane_dataje' ],
	'Unusedtemplates'           => [ 'Njewužiwane_předłohi' ],
	'Unwatchedpages'            => [ 'Njewobkedźbowane_strony' ],
	'Upload'                    => [ 'Nahraće' ],
	'Userlogin'                 => [ 'Přizwjewić' ],
	'Userlogout'                => [ 'Wotzjewić' ],
	'Userrights'                => [ 'Prawa' ],
	'Version'                   => [ 'Wersija' ],
	'Wantedcategories'          => [ 'Požadane_kategorije' ],
	'Wantedfiles'               => [ 'Falowace_dataje' ],
	'Wantedpages'               => [ 'Požadane_strony' ],
	'Wantedtemplates'           => [ 'Falowace_předłohi' ],
	'Watchlist'                 => [ 'Wobkedźbowanki' ],
	'Whatlinkshere'             => [ 'Lisćina_wotkazow' ],
	'Withoutinterwiki'          => [ 'Falowace_mjezyrěčne_wotkazy' ],
];

