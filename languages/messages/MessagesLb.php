<?php
/** Luxembourgish (Lëtzebuergesch)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Geitost
 * @author Hercule
 * @author Kaffi
 * @author Kaganer
 * @author Les Meloures
 * @author MF-Warburg
 * @author Purodha
 * @author Reedy
 * @author Robby
 * @author Soued031
 * @author Urhixidur
 * @author VT98Fan
 * @author Zinneke
 * @author לערי ריינהארט
 */

$fallback = 'de';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Spezial',
	NS_TALK             => 'Diskussioun',
	NS_USER             => 'Benotzer',
	NS_USER_TALK        => 'Benotzer_Diskussioun',
	NS_PROJECT_TALK     => '$1_Diskussioun',
	NS_FILE             => 'Fichier',
	NS_FILE_TALK        => 'Fichier_Diskussioun',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_Diskussioun',
	NS_TEMPLATE         => 'Schabloun',
	NS_TEMPLATE_TALK    => 'Schabloun_Diskussioun',
	NS_HELP             => 'Hëllef',
	NS_HELP_TALK        => 'Hëllef_Diskussioun',
	NS_CATEGORY         => 'Kategorie',
	NS_CATEGORY_TALK    => 'Kategorie_Diskussioun',
);

$namespaceAliases = array(
	'Bild' => NS_FILE,
	'Bild_Diskussioun' => NS_FILE_TALK,
);

// Remove German aliases
$namespaceGenderAliases = array();

$specialPageAliases = array(
	'Activeusers'               => array( 'Aktiv_Benotzer' ),
	'Allmessages'               => array( 'All_Systemmessagen' ),
	'AllMyUploads'              => array( 'All_meng_Fichieren' ),
	'Allpages'                  => array( 'All_Säiten' ),
	'Ancientpages'              => array( 'Al_Säiten' ),
	'Badtitle'                  => array( 'Falschen_Titel' ),
	'Blankpage'                 => array( 'Eidel_Säit' ),
	'Block'                     => array( 'Spären' ),
	'Booksources'               => array( 'Bicher_mat_hirer_ISBN_sichen' ),
	'BrokenRedirects'           => array( 'Futtis_Viruleedungen' ),
	'Categories'                => array( 'Kategorien' ),
	'ChangeEmail'               => array( 'E-Mailadress_änneren' ),
	'ChangePassword'            => array( 'Passwuert_zrécksetzen' ),
	'ComparePages'              => array( 'Säite_vergläichen' ),
	'Confirmemail'              => array( 'E-Mail_confirméieren' ),
	'Contributions'             => array( 'Kontributiounen' ),
	'CreateAccount'             => array( 'Benotzerkont_opmaachen' ),
	'Deadendpages'              => array( 'Sakgaasse-Säiten' ),
	'DeletedContributions'      => array( 'Geläscht_Kontributiounen' ),
	'DoubleRedirects'           => array( 'Duebel_Viruleedungen' ),
	'EditWatchlist'             => array( 'Iwwerwaachungslëscht_änneren' ),
	'Emailuser'                 => array( 'Dësem_Benotzer_eng_E-Mail_schécken' ),
	'ExpandTemplates'           => array( 'Schablounen_erweideren' ),
	'Export'                    => array( 'Exportéieren' ),
	'Fewestrevisions'           => array( 'Säite_mat_de_mannsten_Ännerungen' ),
	'FileDuplicateSearch'       => array( 'No_duebele_Fichiere_sichen' ),
	'Filepath'                  => array( 'Pad_bäi_de_Fichier' ),
	'Import'                    => array( 'Importéieren' ),
	'Invalidateemail'           => array( 'E-Mailadress_net_confirméieren' ),
	'JavaScriptTest'            => array( 'JavaScript-Test' ),
	'BlockList'                 => array( 'Lëscht_vu_gespaarten_IPen_a_Benotzer' ),
	'LinkSearch'                => array( 'Weblink-Sichen' ),
	'Listadmins'                => array( 'Lëscht_vun_den_Administrateuren' ),
	'Listbots'                  => array( 'Botten' ),
	'Listfiles'                 => array( 'Billerlëscht' ),
	'Listgrouprights'           => array( 'Lëscht_vun_de_Grupperechter' ),
	'Listredirects'             => array( 'Viruleedungen' ),
	'ListDuplicatedFiles'       => array( 'Lëscht_vun_den_duebele_Fichieren' ),
	'Listusers'                 => array( 'Lëscht_vun_de_Benotzer' ),
	'Lockdb'                    => array( 'Datebank_spären' ),
	'Log'                       => array( 'Logbicher' ),
	'Lonelypages'               => array( 'Weesesäiten' ),
	'Longpages'                 => array( 'Laang_Säiten' ),
	'MergeHistory'              => array( 'Versiounen_zesummeleeën' ),
	'MIMEsearch'                => array( 'No_MIME-Zorte_sichen' ),
	'Mostcategories'            => array( 'Säite_mat_de_meeschte_Kategorien' ),
	'Mostimages'                => array( 'Dacks_benotzt_Biller' ),
	'Mostinterwikis'            => array( 'Meescht_Interwikien' ),
	'Mostlinked'                => array( 'Dacks_verlinkt_Säiten' ),
	'Mostlinkedcategories'      => array( 'Dacks_benotzt_Kategorien' ),
	'Mostlinkedtemplates'       => array( 'Dacks_benotzt_Schablounen' ),
	'Mostrevisions'             => array( 'Säite_mat_de_meeschten_Ännerungen' ),
	'Movepage'                  => array( 'Säit_réckelen' ),
	'Mycontributions'           => array( 'Meng_Kontributiounen' ),
	'MyLanguage'                => array( 'Meng_Sprooch' ),
	'Mypage'                    => array( 'Meng_Benotzersäit' ),
	'Mytalk'                    => array( 'Meng_Diskussiounssäit' ),
	'Myuploads'                 => array( 'Meng_eropgeluede_Fichieren' ),
	'Newimages'                 => array( 'Nei_Biller' ),
	'Newpages'                  => array( 'Nei_Säiten' ),
	'PagesWithProp'             => array( 'Säite_mat_Eegeschaften' ),
	'PageLanguage'              => array( 'Sprooch_vun_der_Säit' ),
	'PasswordReset'             => array( 'Zrécksetze_vum_Passwuert' ),
	'PermanentLink'             => array( 'Permanente_Link' ),

	'Preferences'               => array( 'Astellungen' ),
	'Prefixindex'               => array( 'Indexsich' ),
	'Protectedpages'            => array( 'Protegéiert_Säiten' ),
	'Protectedtitles'           => array( 'Gespaart_Säiten' ),
	'Randompage'                => array( 'Zoufälleg_Säit' ),
	'RandomInCategory'          => array( 'Zoufälleg_Säit_an_der_Kategorie' ),
	'Randomredirect'            => array( 'Zoufälleg_Viruleedung' ),
	'Recentchanges'             => array( 'Rezent_Ännerungen' ),
	'Recentchangeslinked'       => array( 'Ännerungen_op_verlinkte_Säiten' ),
	'Redirect'                  => array( 'Viruleedung' ),
	'ResetTokens'               => array( 'Token_zrécksetzen' ),
	'Revisiondelete'            => array( 'Versioun_läschen' ),
	'Search'                    => array( 'Sichen' ),
	'Shortpages'                => array( 'Kuerz_Säiten' ),
	'Specialpages'              => array( 'Spezialsäiten' ),
	'Statistics'                => array( 'Statistik' ),
	'Tags'                      => array( 'Taggen' ),
	'Unblock'                   => array( 'Spär_ophiewen' ),
	'Uncategorizedcategories'   => array( 'Kategorien_ouni_Kategorie' ),
	'Uncategorizedimages'       => array( 'Biller_ouni_Kategorie' ),
	'Uncategorizedpages'        => array( 'Säiten_ouni_Kategorie' ),
	'Uncategorizedtemplates'    => array( 'Schablounen_ouni_Kategorie' ),
	'Undelete'                  => array( 'Restauréieren' ),
	'Unlockdb'                  => array( 'Spär_vun_der_Datebank_ophiewen' ),
	'Unusedcategories'          => array( 'Onbenotz_Kategorien' ),
	'Unusedimages'              => array( 'Onbenotzt_Biller' ),
	'Unusedtemplates'           => array( 'Onbenotzt_Schablounen' ),
	'Unwatchedpages'            => array( 'Säiten_déi_net_iwwerwaacht_ginn' ),
	'Upload'                    => array( 'Eroplueden' ),
	'Userlogin'                 => array( 'Umellen' ),
	'Userlogout'                => array( 'Ofmellen' ),
	'Userrights'                => array( 'Benotzerrechter' ),
	'Version'                   => array( 'Versioun' ),
	'Wantedcategories'          => array( 'Gewënscht_Kategorien' ),
	'Wantedfiles'               => array( 'Gewënscht_Fichieren' ),
	'Wantedpages'               => array( 'Gewënscht_Säiten' ),
	'Wantedtemplates'           => array( 'Gewënscht_Schablounen' ),
	'Watchlist'                 => array( 'Iwwerwaachungslëscht' ),
	'Whatlinkshere'             => array( 'Linken_op_dës_Säit' ),
	'Withoutinterwiki'          => array( 'Säiten_ouni_Interwiki-Linken' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#VIRULEEDUNG', '#WEITERLEITUNG', '#REDIRECT' ),
	'numberofpages'             => array( '1', 'Säitenzuel', 'SEITENANZAHL', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'Artikelen', 'ARTIKELANZAHL', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'Zuel_vu_Fichieren', 'DATEIANZAHL', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'Benotzerzuel', 'BENUTZERANZAHL', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'Aktiv_Benotzer', 'AKTIVE_BENUTZER', 'NUMBEROFACTIVEUSERS' ),
	'pagename'                  => array( '1', 'Säitennumm', 'SEITENNAME', 'PAGENAME' ),
	'namespace'                 => array( '1', 'Nummraum', 'NAMENSRAUM', 'NAMESPACE' ),
	'subjectspace'              => array( '1', 'Haaptnummraum', 'HAUPTNAMENSRAUM', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectpagename'           => array( '1', 'Haaptsäit', 'HAUPTSEITE', 'HAUPTSEITENNAME', 'VORDERSEITE', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'img_thumbnail'             => array( '1', 'Miniatur', 'miniatur', 'mini', 'thumbnail', 'thumb' ),
	'img_right'                 => array( '1', 'riets', 'rechts', 'right' ),
	'img_left'                  => array( '1', 'lénks', 'links', 'left' ),
	'img_none'                  => array( '1', 'ouni', 'ohne', 'none' ),
	'img_center'                => array( '1', 'zentréiert', 'zentriert', 'center', 'centre' ),
	'img_framed'                => array( '1', 'gerummt', 'gerahmt', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'net_gerummt', 'rahmenlos', 'frameless' ),
	'img_page'                  => array( '1', 'Säit=$1', 'Säit_$1', 'seite=$1', 'seite $1', 'page=$1', 'page $1' ),
	'img_border'                => array( '1', 'bord', 'rand', 'border' ),
	'img_top'                   => array( '1', 'uewen', 'oben', 'top' ),
	'img_middle'                => array( '1', 'mëtt', 'mitte', 'middle' ),
	'img_bottom'                => array( '1', 'ënnen', 'unten', 'bottom' ),
	'grammar'                   => array( '0', 'GRAMMAIRE', 'GRAMMATIK:', 'GRAMMAR:' ),
	'plural'                    => array( '0', 'PLURAL', 'PLURAL:' ),
	'currentversion'            => array( '1', 'AKTUELL_VERSIOUN', 'JETZIGE_VERSION', 'CURRENTVERSION' ),
	'language'                  => array( '0', '#SPROOCH:', '#SPRACHE:', '#LANGUAGE:' ),
	'formatnum'                 => array( '0', 'ZUELEFORMAT', 'ZAHLENFORMAT', 'FORMATNUM' ),
	'special'                   => array( '0', 'spezial', 'special' ),
	'hiddencat'                 => array( '1', '__VERSTOPPT_KATEGORIE__', '__VERSTECKTE_KATEGORIE__', '__WARTUNGSKATEGORIE__', '__HIDDENCAT__' ),
	'pagesincategory_pages'     => array( '0', 'Säiten', 'seiten', 'pages' ),
	'pagesincategory_files'     => array( '0', 'Fichieren', 'dateien', 'files' ),
);

