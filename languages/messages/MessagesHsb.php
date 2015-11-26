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

$namespaceNames = array(
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
);

$namespaceAliases = array(
	'Wobraz' => NS_FILE,
	'Diskusija_k_wobrazej' => NS_FILE_TALK,
);

$namespaceGenderAliases = array(
	NS_USER => array( 'male' => 'Wužiwar', 'female' => 'Wužiwarka' ),
	NS_USER_TALK => array( 'male' => 'Diskusija_z_wužiwarjom', 'female' => 'Diskusija_z_wužiwarku' ),
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

$specialPageAliases = array(
	'Activeusers'               => array( 'Aktiwni_wužiwarjo' ),
	'Allmessages'               => array( 'MediaWiki-zdźělenki' ),
	'Allpages'                  => array( 'Wšě_strony' ),
	'Ancientpages'              => array( 'Najstarše_strony' ),
	'Blankpage'                 => array( 'Prózdna_strona' ),
	'Block'                     => array( 'Blokować' ),
	'Booksources'               => array( 'Pytanje_po_ISBN' ),
	'BrokenRedirects'           => array( 'Skóncowane_daleposrědkowanja' ),
	'Categories'                => array( 'Kategorije' ),
	'ChangePassword'            => array( 'Hesło_wróćo_stajić' ),
	'Confirmemail'              => array( 'E-Mejl_potwjerdźić' ),
	'Contributions'             => array( 'Přinoški' ),
	'CreateAccount'             => array( 'Konto_wutworić' ),
	'Deadendpages'              => array( 'Strony_bjez_wotkazow' ),
	'DeletedContributions'      => array( 'Zničene_přinoški' ),
	'DoubleRedirects'           => array( 'Dwójne_daleposrědkowanja' ),
	'EditWatchlist'             => array( 'Wobkedźbowanki_wobdźěłać' ),
	'Emailuser'                 => array( 'E-Mejl' ),
	'Export'                    => array( 'Eksport' ),
	'Fewestrevisions'           => array( 'Strony_z_najmjenje_wersijemi' ),
	'FileDuplicateSearch'       => array( 'Duplikatowe_pytanje' ),
	'Filepath'                  => array( 'Datajowy_puć' ),
	'Import'                    => array( 'Importować' ),
	'Invalidateemail'           => array( 'Njepłaćiwa_e-mejl' ),
	'BlockList'                 => array( 'Blokowane_IP-adresy' ),
	'LinkSearch'                => array( 'Wotkazowe_pytanje' ),
	'Listadmins'                => array( 'Administratorojo' ),
	'Listbots'                  => array( 'Boćiki' ),
	'Listfiles'                 => array( 'Dataje' ),
	'Listgrouprights'           => array( 'Prawa_skupinow' ),
	'Listredirects'             => array( 'Daleposrědkowanja' ),
	'Listusers'                 => array( 'Wužiwarjo' ),
	'Lockdb'                    => array( 'Datowu_banku_zamknyć' ),
	'Log'                       => array( 'Protokol' ),
	'Lonelypages'               => array( 'Wosyroćene_strony' ),
	'Longpages'                 => array( 'Najdlěše_strony' ),
	'MergeHistory'              => array( 'Stawizny_zjednoćić' ),
	'MIMEsearch'                => array( 'Pytanje_po_MIME' ),
	'Mostcategories'            => array( 'Strony_z_najwjace_kategorijemi' ),
	'Mostimages'                => array( 'Z_najwjace_stronami_zwjazane_dataje' ),
	'Mostlinked'                => array( 'Z_najwjace_stronami_zwjazane_strony' ),
	'Mostlinkedcategories'      => array( 'Najhusćišo_wužiwane_kategorije' ),
	'Mostlinkedtemplates'       => array( 'Najhusćišo_wužiwane_předłohi' ),
	'Mostrevisions'             => array( 'Strony_z_najwjace_wersijemi' ),
	'Movepage'                  => array( 'Přesunyć' ),
	'Mycontributions'           => array( 'Moje_přinoški' ),
	'MyLanguage'                => array( 'Moja_rěč' ),
	'Mypage'                    => array( 'Moja_wužiwarska_strona' ),
	'Mytalk'                    => array( 'Moja_diskusijna_strona' ),
	'Newimages'                 => array( 'Nowe_dataje' ),
	'Newpages'                  => array( 'Nowe_strony' ),
	'PermanentLink'             => array( 'Trajny_wotkaz' ),
	'Preferences'               => array( 'Nastajenja' ),
	'Prefixindex'               => array( 'Prefiksindeks' ),
	'Protectedpages'            => array( 'Škitane_strony' ),
	'Protectedtitles'           => array( 'Škitane_titule' ),
	'Randompage'                => array( 'Připadna_strona' ),
	'Randomredirect'            => array( 'Připadne_daleposrědkowanje' ),
	'Recentchanges'             => array( 'Aktualne_změny' ),
	'Recentchangeslinked'       => array( 'Změny_zwjazanych_stronow' ),
	'Redirect'                  => array( 'Dalesposrědkowanje' ),
	'Revisiondelete'            => array( 'Wušmórnjenje_wersijow' ),
	'Search'                    => array( 'Pytać' ),
	'Shortpages'                => array( 'Najkrótše_strony' ),
	'Specialpages'              => array( 'Specialne_strony' ),
	'Statistics'                => array( 'Statistika' ),
	'Tags'                      => array( 'Taflički' ),
	'Uncategorizedcategories'   => array( 'Njekategorizowane_kategorije' ),
	'Uncategorizedimages'       => array( 'Njekategorizowane_dataje' ),
	'Uncategorizedpages'        => array( 'Njekategorizowane_strony' ),
	'Uncategorizedtemplates'    => array( 'Njekategorizowane_předłohi' ),
	'Undelete'                  => array( 'Wobnowić' ),
	'Unlockdb'                  => array( 'Datowu_banku_wotamknyć' ),
	'Unusedcategories'          => array( 'Njewužiwane_kategorije' ),
	'Unusedimages'              => array( 'Njewužiwane_dataje' ),
	'Unusedtemplates'           => array( 'Njewužiwane_předłohi' ),
	'Unwatchedpages'            => array( 'Njewobkedźbowane_strony' ),
	'Upload'                    => array( 'Nahraće' ),
	'Userlogin'                 => array( 'Přizwjewić' ),
	'Userlogout'                => array( 'Wotzjewić' ),
	'Userrights'                => array( 'Prawa' ),
	'Version'                   => array( 'Wersija' ),
	'Wantedcategories'          => array( 'Požadane_kategorije' ),
	'Wantedfiles'               => array( 'Falowace_dataje' ),
	'Wantedpages'               => array( 'Požadane_strony' ),
	'Wantedtemplates'           => array( 'Falowace_předłohi' ),
	'Watchlist'                 => array( 'Wobkedźbowanki' ),
	'Whatlinkshere'             => array( 'Lisćina_wotkazow' ),
	'Withoutinterwiki'          => array( 'Falowace_mjezyrěčne_wotkazy' ),
);

